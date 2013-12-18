<?php 


Class User extends DB {

	public static function create($username, $password) {
		$q = self::$db->prepare('INSERT INTO user(username, password, email) VALUES (:username, :password, :email)');
		return $q->execute(array(
			':username' => $username,
			':password' => sha1($password),
			':email' => $email,
		));
	}

	public static function update() {

	}

	public static function login($username, $password) {
		$q = self::$db->prepare('SELECT * FROM user WHERE username = :username AND password = :password');
		$q->execute(array(
			':username' => $username,
			':password' => sha1($password),
		));
		return $q->rowCount();
	}

	public static function getByUsername($username) {
		$q = self::$db->prepare('SELECT id, username FROM user WHERE username = :username');
		$q->execute(array(':username' => $username));
		return $q->fetchObject();
	}
}
?>