<?php
/**
 * Implements a route that allows for user registration through a POST request
 * containing username and password information.
 * @return bool True if registration successful false if not.
*/
include_once dirname(__DIR__, 1) . '/src/userregistrator.php';
include_once dirname(__DIR__, 1) . '/src/codes/userregistrationstatus.php';
include_once dirname(__DIR__, 1) . '/src/redirecthelper.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
  return false;
}

$registrator = new UserRegistrator();
$status = $registrator->register($_POST['username'], $_POST['password'], $_POST['email'], $_POST['emailpassword']);

if ($status != UserRegistrationStatus::SUCCESS) { // Something went wrong in registration process
  $errorMessage = null;
  
  switch ($status) {
    case UserRegistrationStatus::FAILURE_INVALID_USERNAME:
      $errorMessage = 'Invalid username';
      break;
    case UserRegistrationStatus::FAILURE_INVALID_PASSWORD:
      $errorMessage = 'Invalid password';    
      break;
    case UserRegistrationStatus::FAILURE_INVALID_EMAIL:
      $errorMessage = 'Invalid email';
      break;
    case UserRegistrationStatus::FAILURE_USERNAME_OCCUPIED:
      $errorMessage = 'Username already registered';
      break;
    default:
      $errorMessage = 'Unknown error occurred';
      break;
  }

  RedirectHelper::redirectWithMessage($_POST, 'Error: ' . $errorMessage);
  return false;
}

RedirectHelper::redirectWithMessage($_POST, 'Success: Registration completed');
return true;
?>