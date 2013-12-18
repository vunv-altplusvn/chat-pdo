<?php 

class DB {
	protected static $db ;
	public static function connect() {
		self::$db = new PDO('mysql:host=localhost;dbname=chat;charset=utf8', 'root', 'example');;
	}
}
?>