<?php

class View
{
    public static function getBaseMetas()
    {
        return [
            '<meta charset="UTF-8">',
            '<meta name="viewport" content="width=device-width, initial-scale=1.0">'
        ];
    }

    private static function getParsedView(string $view): string
    {
        return str_replace('.', DIRECTORY_SEPARATOR, $view);
    }

    public static function exists(string $view): bool
    {
        $view = self::getParsedView($view);
        return file_exists(ROOT . VIEWS . DIRECTORY_SEPARATOR . $view . '.php');
    }

    public static function render(string $view, array $data = []): string
    {
        $parsedView = str_replace('/', DIRECTORY_SEPARATOR, $view);
        $page = file_exists(PAGES . $parsedView . '.php')
            ? PAGES . $parsedView . '.php'
            : PAGES . '404.php';

        ob_start();
        if (!empty($data)) extract($data);

        $sessionUser = Auth::getStoredUser();
        if (!empty($sessionUser)) extract($sessionUser);

        $isAdminRoute = Tools::isAdminRoute();
        $pageType = "page-type-" . ($isAdminRoute ? 'admin' : 'public');
        $showSidebar = Tools::isUserAdmin() && $isAdminRoute;

        echo '<!DOCTYPE html>
		<html lang="es">
		<head>' . implode("", self::getBaseMetas());
        if (file_exists(ROOT . VIEWS . "/" . SHARED . "/header.php"))
            require_once ROOT . VIEWS . "/" . SHARED . "/header.php";
        echo '</head>';

        echo '<body>';
        if ($showSidebar) {
            require_once ROOT . VIEWS . "/" . SHARED . "/admin-sidebar.php";
        }
        echo '<main class="app-main main-app-main-content page-' . Tools::getParsedPageURI() . ' ' . $pageType . '">';
        require_once $page;
        echo '</main>';
        if (file_exists(ROOT . VIEWS . "/" . SHARED . "/footer.php"))
            require_once ROOT . VIEWS . "/" . SHARED . "/footer.php";
        echo '</body>';
        echo '</html>';

        return ob_get_clean();
    }
}
