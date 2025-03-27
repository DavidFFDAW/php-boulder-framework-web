<?php

class Db
{
	private $connection;
	private static $instance = null;

	public static function getInstance()
	{
		if (self::$instance === null) {

			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct()
	{
		$datas = [
			'host' => 'localhost',
			'dbname' => 'web_framework',
		];
        // $dsn = 'mysql:host=localhost;dbname=mandarinos;charset=utf8';
		$dsn = 'mysql:host=' . $datas['host'] . ';dbname=' . $datas['dbname'] . ';charset=utf8';
        $username = 'root';
        $password = '';
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];
        $this->connection = new PDO($dsn, $username, $password, $options);
		if ($this->connection->errorCode()) {
			die('Error de conexión a la base de datos');
		}
	}

	public function getConnection()
	{
		return $this->connection;
	}

	public function query($sql)
	{
		return $this->connection->query($sql);
	}

	public function preparedQuery($sql, $params)
	{
		$stmt = $this->connection->prepare($sql);
		$stmt->execute($params);
		return $stmt;
	}

	public function __destruct()
	{
		$this->connection = null;
	}
}
