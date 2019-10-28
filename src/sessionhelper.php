<?php
/**
 * SessionHelper is responsible for providing helper functions
 * that interacts with PHP's session variables.
 */

include_once dirname(__DIR__, 1) . '/src/models/user.php';

class SessionHelper {
  /**
   * isLoggedIn
   * Checks whether the user session is currently logged in or not.
   *
   * @return bool True if logged in false if not.
   */
  public static function isLoggedIn() {
    if (session_status() == PHP_SESSION_NONE)
      session_start();
      
    return isset($_SESSION['user']);
  }

  /**
   * loggout
   * Logs out by removing the current user session.ß
   *
   * @return void
   */
  public static function loggout() {
    if (session_status() == PHP_SESSION_NONE)
      session_start();

    unset($_SESSION['user']);
  }

  /**
   * getUser
   * Fetches the logged in user.
   * 
   * @return Any The user if logged in null if not.
   */
  public static function getUser() {
    if (session_status() == PHP_SESSION_NONE)
      session_start();
      
    return isset($_SESSION['user']) ? $_SESSION['user'] : null;
  }
}
?>