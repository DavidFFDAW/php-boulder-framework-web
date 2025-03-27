<?php

class Request
{
    private $path;
    private $method;
	private $get;
	private $post;
	private $files;
	private $server;

    public function __construct()
    {
        $this->path = $_SERVER['REQUEST_URI'];
        $this->method = $_SERVER['REQUEST_METHOD'];
		$this->get = $this->sanitizeArray($_GET);
		$this->post = $this->getRequestBody();
		$this->files = $_FILES;
		$this->server = $_SERVER;
    }

	private function sanitizeArray($array) {
		return array_map(function ($item) {
			return htmlspecialchars(trim(strip_tags(filter_var($item, FILTER_SANITIZE_SPECIAL_CHARS))));
		}, $array);
	}

    public function getRequestBody() {
		if ($_SERVER['REQUEST_METHOD'] === 'GET') return [];
        if ($_SERVER['CONTENT_TYPE'] === 'application/json')
            return $this->sanitizeArray(json_decode(file_get_contents('php://input'), true));
        return $this->sanitizeArray($_POST);
    }

	public function getUUID($key = 'id') {
		$id = $this->get($key);
		if (!$id || isset($id)) return null;
		return Crypt::decrypt($id);
	}

	public function get($key) {
		return isset($this->get[$key]) ? $this->get[$key] : null;
	}
	
	public function post($key) {
		return isset($this->post[$key]) ? $this->post[$key] : null;
	}
}
