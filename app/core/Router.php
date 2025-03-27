<?php
class Router
{
    private $request;
    private $requestUri;
    private $isApiRouteRequest;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->requestUri = (URI === '/' || URI == '') ? '/home' : URI;
        $this->isApiRouteRequest = strpos($this->requestUri, '/api') === 0;
    }


    public function handle()
    {
        if ($this->isApiRouteRequest) {
            $this->handleApiRoute();
        } else {
            $this->handleWebRoute();
        }
    }

    private function handleApiRoute()
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json; charset=utf-8');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

        // get rid of trailing slash
        try {
            $requestUri = rtrim($this->requestUri, '/');
            $apiRouteWithNoApi = str_replace('/api', '', $requestUri);
            $apiFile = str_replace('/', DIRECTORY_SEPARATOR, API . $apiRouteWithNoApi . '.php');
            $apiRouteExists = file_exists($apiFile);

            if ($requestUri === '/api' || !$apiRouteExists)
                throw new Exception('No se ha encontrado la ruta solicitada', 404);

            $method = strtoupper($_SERVER['REQUEST_METHOD']);
            require_once $apiFile;
            if (!function_exists($method) || !is_callable($method))
                throw new Exception('El método ' . $method . ' no está disponible para esta ruta. Prueba otro método diferente.', 405);
            return $method();
        } catch (Exception $e) {
            $errorCode = in_array($e->getCode(), [400, 401, 403, 404, 405]) ? $e->getCode() : 500;

            return response([
                'haserror' => true,
                'error' => 'Ha ocurrido un error',
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            ], $errorCode);
        }
    }

    private function handleWebRoute()
    {
        $request = $this->request;
        $originalRequestUri = $this->requestUri;
        $requestUri = substr(str_replace('/', DIRECTORY_SEPARATOR, $this->requestUri), 1);
        $lastPart = explode(DIRECTORY_SEPARATOR, $requestUri);
        $lastPart = end($lastPart);

        $isGET = $_SERVER['REQUEST_METHOD'] === 'GET';
        // if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $file = PAGES . $requestUri;
        $directFileExists = file_exists(PAGES . $requestUri . '.php');
        $internalDirectFileExists = file_exists(PAGES . $requestUri . DIRECTORY_SEPARATOR . $lastPart . '.php');
		$indexSearch = file_exists(PAGES . $requestUri . DIRECTORY_SEPARATOR . 'index.php');

        if ($directFileExists) {
            $file = $requestUri;
        } else if ($internalDirectFileExists) {
            $file = str_replace('/', DIRECTORY_SEPARATOR, $requestUri . DIRECTORY_SEPARATOR . $lastPart);
        } else if ($indexSearch) {
			$file = str_replace('/', DIRECTORY_SEPARATOR, $requestUri . DIRECTORY_SEPARATOR . 'index');
		}

        if ($directFileExists || $internalDirectFileExists || $indexSearch) {
            $isUserRoute  = strpos($originalRequestUri, '/user') === 0;
            $isAdminRoute = strpos($originalRequestUri, '/admin') === 0;

            $continue = true;
            if ($isAdminRoute || $isUserRoute) {
                $requiredRole = $isAdminRoute ? 'admin' : 'user';
                $continue = Middlewares::canUserAccess($requiredRole);
            }

            if (!$continue) {
                http_response_code(302);
                if (!empty($originalRequestUri)) {
                    $nextRoute = base64_encode(rawurlencode($originalRequestUri));
                    redirect('/auth/login?next=' . $nextRoute);
                    exit;
                }
                redirect('/auth/login');
                exit;
            }

            if (!$isGET) {
                // $redirect = $request->post('_redirect');
                if (file_exists(PAGES . $file . '.server.php')) {
                    require_once PAGES . $file . '.server.php';
					$method = isset($_POST['_action']) ? $_POST['_action'] : strtoupper($_SERVER['REQUEST_METHOD']);
                    if (function_exists($method) && is_callable($method)) $method($request);
                    if (!function_exists($method) || !is_callable($method)) 
						die(View::render('404', ['request' => $request, 'message' => 'No se ha encontrado la accion solicitada']));
					
                }
            }

            die(View::render(
                $file,
                [
                    'isAdminRoute' => $isAdminRoute,
                    'request' => $request
                ]
            ));
        } else {
            http_response_code(404);
            die(View::render('404', ['request' => $request]));
        }
    }
}
