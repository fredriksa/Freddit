<?php
include_once('databaseconnector.php');
include_once('codes/userloginstatus.php');
/**
 * UserAuthenticator is responsible for providing authentication 
 * functionality for users.
 */
class UserAuthenticator {
 
  /**
   * authenticate
   * Attempts to authenticate a user with the provided information.
   *
   * @param  string $username The username of the user.
   * @param  string $password The password belonging to that username.
   *
   * @return UserLoginStatus Status code depends on outcome of authentication attempt.
   */
  public function authenticate($username, $password) {
    $config = include_once dirname(__DIR__, 1) . '/config.php';
    if (!isset($username) || strlen($username) < $config['USER_USERNAME_LENGTH_MIN']) {
      return UserLoginStatus::FAILURE_INVALID_USERNAME;
    }

    if (!isset($password) || strlen($password) < $config['USER_PASSWORD_LENGTH_MIN']) {
      return UserLoginStatus::FAILURE_INVALID_PASSWORD;
    }

    if (!$this->authenticateWithDB($username, $password)) {
      return UserLoginStatus::FAILURE_INVALID_COMBINATION;
    }

    return UserLoginStatus::SUCCESS;
  }

  /**
   * authenticateWithDB
   * Safely tests whether or not the provided username and password  
   * combination is stored in the database.
   * 
   * @param  mixed $username The username of the user to be authenticated.
   * @param  mixed $password The password belonging to that username.
   *
   * @return True if authentication successful, False if not.
   */
  private function authenticateWithDB($username, $password) {
    $db = DatabaseConnector::getInstance();
    $stmt = $db->prepare('SELECT username, password FROM user WHERE username=?');
    
    $stmt->execute(array(
      $username 
    ));

    $result = $stmt->fetchAll();
    if (count($result) < 1)
      return false;
      
    return password_verify($password, $result[0]['password']);
  }
}
?>