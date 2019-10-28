<?php
/**
 * Implements a route that allows for users to login to Freddit
 * through a POST request containg username and password.
 */
include_once dirname(__DIR__, 1) . '/src/userauthenticator.php';
include_once dirname(__DIR__, 1) . '/src/codes/userloginstatus.php';
include_once dirname(__DIR__, 1) . '/src/redirecthelper.php';
include_once dirname(__DIR__, 1) . '/src/sessionhelper.php';
include_once dirname(__DIR__, 1) . '/src/models/user.php';
include_once dirname(__DIR__, 1) . '/src/model.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
  exit();
}

if (SessionHelper::isLoggedIn())
  RedirectHelper::redirect('/pages/frontpage.php');

$authenticator = new UserAuthenticator();
$status = $authenticator->authenticate($_POST['username'], $_POST['password']);

if ($status != UserLoginStatus::SUCCESS) {
  $message = null;

  switch ($status) {
    case UserLoginStatus::FAILURE_INVALID_USERNAME:
      $message = 'Username can not be empty';
      break;
    case UserLoginStatus::FAILURE_INVALID_PASSWORD:
      $message = 'Password can not be empty.';
      break;
    case UserLoginStatus::FAILURE_INVALID_COMBINATION:
      $message = 'Invalid crededentials entered.';
      break;
    default:
      $message = 'Unknown error occurred.';
      break;
  }

  RedirectHelper::redirectWithMessage($_POST, 'Error: ' . $message);
  exit();
}

session_start();
$_SESSION['user'] = new User(Model::getAttributeValue('user', 'id', 'username', $_POST['username']));
setcookie("lastlogin", date('Y-M-d h:m:s'), time() + 3 * 60 * 600000, '/');

RedirectHelper::redirect('/pages/frontpage.php');
return UserLoginStatus::SUCCESS;
?>