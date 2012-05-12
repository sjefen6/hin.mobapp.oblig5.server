<?php
require 'settings.class.php';
require('userHandler.class.php');
$settings = new settings("../settings.xml");
$users = new userHandler();
$users -> addUser("sjefen6", "sjefen6@gmail.com", "asdf", 0, 1);
?>