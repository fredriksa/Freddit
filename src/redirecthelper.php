<?php
/**
 * RedirectHelper is responsible for helping with redirections.
 */
class RedirectHelper {
  
  /**
   * redirectWithMessage
   * Redirects to the referer with a message.
   *
   * @param  mixed $post The variables passed via HTTP post ($_POST).
   * @param  mixed $message The message to be displayed.
   *
   * @return bool True if successful false if not.
   */
  public static function redirectWithMessage($POST, $message) {
    if (!isset($POST['referer']))
      return false;

    header('Location: http://' . $_SERVER['SERVER_NAME'] . '/' . $POST['referer'] . '?message=' . $message);
    return true;
  }

  /**
   * redirect
   * Redirects to the referer.
   *
   * @param  string $referer The referer is the resource, e.g.
   *        '/routes/foo.php' would end up in redirecting to 'http://localhost/routes/foo.php'.
   *
   * @return boolean True if redirect is performed false if not.
   */
  public static function redirect($referer) {
    return Redirecthelper::redirectFull('http://' . $_SERVER['SERVER_NAME'] . '/' . $referer);
  }

  /**
   * redirectFull
   * Redirects to the referer without appending details.
   * 
   * @param  string $referer The referer is the full path to the URL to refer to.
   *
   * @return boolean True if redirect is performed false if not.
   */
  public static function redirectFull($referer) {
    if (!isset($referer)) 
      return false;

    header('Location: ' . $referer);
    return true;
  }
}

?>