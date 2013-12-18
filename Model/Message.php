<?php 

class Message extends DB{

	static function add($content, $userId) {
		$q = self::$db->prepare('INSERT INTO message(content, created_at, updated_at, user_id) VALUES (:content, :created_at, :updated_at, :user_id)');
		return $q->execute(array(
			':content' => $content,
			':created_at' => time(),
			':updated_at' => time(),
			':user_id' => $userId,
		));
	}

	static function getAll() {
		$result = self::$db->query('SELECT message.id as message_id, content, message.created_at, message.updated_at, user.id as user_id, username FROM message
									JOIN user on message.user_id = user.id WHERE message.deleted = 0');
		$return = array();
		while ($message = $result->fetchObject()) {
			$return[] = $message;
		}
		return $return;
	}

	static function getNewMessageByLastUpdate($lastUpdate) {
		$q = self::$db->prepare('SELECT message.id as message_id, content, message.created_at, message.updated_at, user.id as user_id, username FROM message
									JOIN user on message.user_id = user.id WHERE message.created_at > :lastUpdate AND message.deleted = 0');
		$q->execute(array(
			':lastUpdate' => $lastUpdate,
		));
		$return = array();
		while ($message = $q->fetchObject()) {
			$return[] = $message;
		}
		return $return;
	}

	static function getDeletedMessageByLastUpdate($lastUpdate) {
		$q = self::$db->prepare('SELECT message.id FROM message
									JOIN user on message.user_id = user.id WHERE message.updated_at > :lastUpdate AND message.deleted = 1');
		$q->execute(array(
			':lastUpdate' => $lastUpdate,
		));
		$return = array();
		while ($message = $q->fetchObject()) {
			$return[] = $message->id;
		}
		return $return;
	}

	static function getEditedMessageByLastUpdate($lastUpdate) {
		$q = self::$db->prepare('SELECT id, content, updated_at FROM message WHERE message.updated_at > :lastUpdate AND message.deleted = 0');
		$q->execute(array(
			':lastUpdate' => $lastUpdate,
		));
		$return = array();
		while ($message = $q->fetchObject()) {
			$return[] = $message;
		}
		return $return;
	}

	static function deleteById($id) {
		$q = self::$db->prepare('UPDATE message SET deleted = 1, updated_at = :updated_at WHERE id = :id');
		return $q->execute(array(
			':id' => $id,
			':updated_at' => time(),
		));
	}

	static function updateById($id, $content) {
		$q = self::$db->prepare('UPDATE message SET content = :content, updated_at = :updated_at WHERE id = :id');
		return $q->execute(array(
			':content' => $content,
			':updated_at' => time(),
			':id' => $id,
		));	
	}
}
?>