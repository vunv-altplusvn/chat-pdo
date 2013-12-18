<?php 
session_start();
if (!isset($_SESSION['login_user'])) {
	header("location: /chat-pdo/login.php"); 
}
require 'Model/DB.php';
require 'Model/Message.php';
require 'Model/User.php';
DB::connect();
$listMessage = Message::getAll();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Title</title>
<link rel="stylesheet" type="text/css" href="css/reset.css">
<link rel="stylesheet" type="text/css" href="css/style.css">
<script type="text/javascript" src="js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="js/common.js"></script>
</head>
<body>
	<div class="wrapper">
		Hello <?php echo $_SESSION['login_user']?>, <a href="/chat-pdo/logout.php">Logout</a>
		<div id="chat-box" >
			<div id="content-box" data-lastupdate="<?php echo time() ?>">
				<?php foreach($listMessage as $message): ?>
				<div class="m" data-id="<?php echo $message->message_id ?>">
					<p><span><?php echo $message->username ?></span>: <input type="text" value="<?php echo $message->content ?>" readonly="readonly"></p>
					<p class="meta">
						<a href="#" class="edit-message">[EDIT]</a> <a href="#" class="delete-message">[DELETE]</a> <?php echo (new DateTime)->setTimestamp($message->created_at)->format('H:i:s, d/m/Y'); ?>
					</p>
					<div class="clear"></div>
				</div>	
				<?php endforeach; ?>
			</div>
			<div id="send-box">
				<input type="text" id="message" name="message">
				<button id="send">Send</button>
			</div>
		</div>	
	</div>
</body>
</html>