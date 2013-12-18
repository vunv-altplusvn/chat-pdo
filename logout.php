<?php
session_start();
session_destroy();
header("Location: /chat-pdo/login.php");
?>