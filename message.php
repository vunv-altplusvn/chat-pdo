<?php
session_start();
if (isset($_GET['action'])) {
	require 'Model/DB.php';
	require 'Model/Message.php';
	require 'Model/User.php';
	DB::connect();
	$user = User::getByUsername($_SESSION['login_user']);
	switch ($_GET['action']) {
		case 'add':
			if (isset($_GET['content'])) {
				Message::add($_GET['content'], $user->id);
				echo 'ok';
			} else {
				echo 'fail';
			}
			break;
		case 'reload':
			$return = new stdClass;
			$return->newMessage = Message::getNewMessageByLastUpdate($_GET['lastUpdate']);
			$return->deletedMessage = Message::getDeletedMessageByLastUpdate($_GET['lastUpdate']);
			$return->editedMessage = Message::getEditedMessageByLastUpdate($_GET['lastUpdate']);
			$return->lastUpdate = time();
			$return->hasUpdate = count($return->newMessage) + count($return->deletedMessage) + count($return->editedMessage) > 0 ? true : false;
			echo json_encode($return);
			break;
		case 'delete':
			// echo 1;die;
			$return = new stdClass;
			$return->status = Message::deleteById($_GET['id']);
			echo json_encode($return);
			break;
		case 'edit':
			$return = new stdClass;
			$return->status = Message::updateById($_GET['id'], $_GET['content']);
			echo json_encode($return);
			break;
		default:
			echo 'Invalid request!';
			break;
	}
}
?>