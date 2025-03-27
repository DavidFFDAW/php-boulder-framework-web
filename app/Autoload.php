<?php
class Autoload {
	private static $folders = ['core', 'models', 'pages', 'utils'];

	public static function register() {
		spl_autoload_register(function ($class) {
			$baseDir = __DIR__ . DIRECTORY_SEPARATOR;
			$file = $baseDir . str_replace('\\', '/', $class) . '.php';

			foreach (self::$folders as $folder) {
				$file = $baseDir . $folder . DIRECTORY_SEPARATOR . str_replace('\\', '/', $class) . '.php';

				if (file_exists($file)) {
					require_once $file;
					break;
				}
			}
		});
	}
}