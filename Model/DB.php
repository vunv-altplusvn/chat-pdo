<?php 

class DB {
	protected static $db ;
	public static function connect() {
		self::$db = new PDO('mysql:host=localhost;dbname=chat1;charset=utf8', 'root', '123456');;
	}
}
?>