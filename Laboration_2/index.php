<?php

require_once ("/common/HTMLView.php");
require_once ("/src/LoginController.php");
require_once ("/src/LoginView.php");
require_once ("/src/LoginModel.php");

// Startar session & sätter cookie hos klienten
session_start();

$c = new LoginController();
$htmlBody = $c->doControll();

$view = new HTMLView();
$view->echoHTML($htmlBody);
