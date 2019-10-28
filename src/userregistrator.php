<?php
include_once('databaseconnector.php');
include_once('codes/userregistrationstatus.php');

/**
 * UserRegistrator is responsible for providing registration
 * functionality for users.
 */
class UserRegistrator {
 
 
  /**
   * register
   * Attempts to register a new user with the provided information.
   *
   * @param  string $username The username for the user to be registered.
   * @param  string $password The password for the user to be registered.
   * @param  string $email The email for the user to be registered.
   * @param  string $emailPassword the email password for the user to be registered.
   *
   * @return UserRegistrationStatus Code varies depending on outcome in registration process.
   */
  public function register($username, $password, $email, $emailPassword) {
    if (!$this->isUsernameValid($username)) {
      return UserRegistrationStatus::FAILURE_INVALID_USERNAME;
    }

    if ($this->isUsernameOccupied($username)) {
      return UserRegistrationStatus::FAILURE_USERNAME_OCCUPIED;
    }

    if (!$this->isPasswordValid($password)) {
      return UserRegistrationStatus::FAILURE_INVALID_PASSWORD;
    }

    if (!$this->isEmailValid($email)) {
      return UserRegistrationStatus::FAILURE_INVALID_EMAIL;
    }

    $this->registerInDatabase($username, $password, $email, $emailPassword);
    return UserRegistrationStatus::SUCCESS;
  }

  /**
   * registerInDatabase
   * Registers a new user with the provided username and password
   * combination. Stores the password as a hash in the database.
   *
   * @param  mixed $username The username to register.
   * @param  mixed $password The password belonging to that username.
   * @param  mixed $email The email belonging to that username.
   * @param  mixed $emailPassword The email password belonging to the user's email.
   *
   * @return void Returns nothing.
   */
  private function registerInDatabase($username, $password, $email, $emailPassword) {
    $db = DatabaseConnector::getInstance();
    $stmt = $db->prepare('INSERT INTO user (username, password, email, email_password) VALUES (?, ?, ?, ?)');
    
    $stmt->execute(array(
      $username,
      password_hash($password, PASSWORD_DEFAULT),
      $email,
      $emailPassword
    ));
  }

  /**
   * isUsernameOccupied
   * Tests whether or not a user with the username exists
   * in the database.
   * 
   * @param  mixed $username The username to test if it exists in the database.
   *
   * @return bool true if the username exists false if not.
   */
  private function isUsernameOccupied($username) {
    $db = DatabaseConnector::getInstance();
    $stmt = $db->prepare('SELECT count(*) FROM user WHERE username=?');
    
    $stmt->execute(array(
      $username
    ));

    $rows = $stmt->fetchAll();
    return $rows[0]['count(*)'] > 0;
  }

  /**
   * isPasswordValid
   * Tests whether or not the provided password is valid for registration.
   * 
   * @param string $password The password to be tested.
   *
   * @return bool True if the password is valid false if not.
   */
  private function isPasswordValid($password) {
    return isset($password) && strlen($password) > 4;
  }

  /**
   * isEmailValid
   * Tests whether or not the provided email is valid for registration.
   * 
   * @param  string $email The email to be tested.
   *
   * @return bool True if the email is valid false if not.
   */
  private function isEmailValid($email) {
    return isset($email) && strlen($email) > 3 && filter_var($email, FILTER_VALIDATE_EMAIL);
  }

  /**
   * isUsernameValid
   *
   * @param  mixed $username
   *
   * @return void
   */
  private function isUsernameValid($username) {
    $config = include('../config.php');
    return isset($username) && strlen($username) > $config['USER_USERNAME_LENGTH_MIN'];
  }
}
?>