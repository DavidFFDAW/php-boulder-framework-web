<?php

class Logger {
	public static function log($datas, $filename = null) {
		$isJson = is_array($datas) || is_object($datas);
		$filename = (isset($filename) && !empty($filename)) ? $filename : date('YmdHis') . ($isJson ? '.json' : '.log');
		return file_put_contents(
			LOGS . $filename, 
			$isJson ? json_encode($datas, JSON_PRETTY_PRINT) : $datas
		);
	}
}