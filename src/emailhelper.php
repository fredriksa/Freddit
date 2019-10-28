<?php
/**
 * EmailHelper is responsible for providing email functionality on Freddit.
 */

include_once dirname(__DIR__, 1) . '/src/sessionhelper.php';
include_once dirname(__DIR__, 1) . '/dependencies/PHPMailerAutoload.php';
include_once dirname(__DIR__, 1) . '/src/models/user.php';
include_once dirname(__DIR__, 1) . '/src/model.php';

class EmailHelper {

  /**
   * getEmails
   * Fetches all the emails for the logged in user.
   *
   * @return Array An array of all the logged in users emails or a empty one if none is fetched.
   */
  public static function getEmails() {
    if (!SessionHelper::isLoggedIn())
      die();

    // Prepares server connection information
    $server = "{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX";
  
    // Fetch the authentication information sent with the POST request
    $username = SessionHelper::getUser()->get('email');
    $password = SessionHelper::getUser()->get('emailpassword');
  
    // Establish connection
    $mail_server = imap_open($server, $username, $password);
  
    // Check if connection failed
    if (!isset($mail_server)) {
      return "Could not connect to " . $server . " with the following details: " . $username . ":" . $password . "\n";
      die();
    }
  
    // Attempt to fetch emails
    $foundMails = imap_search($mail_server, 'ALL');
    if (!isset($foundMails)) {
      echo "Could not retrieve any emails";
      die();
    }
  
    /* 
      Reverse sort foundMails because the messages are initially
      sorted so that the oldest mails are first in the array.
      For a user, it would be more logical to show the newest mail first on the webpage.
    */
    rsort($foundMails);
  
    $config = include('../config.php');

    // Represent the email's contents as HTML for the client.
    $mails = array();
    foreach ($foundMails as $mail_id) {
      $overview = imap_fetch_overview($mail_server, $mail_id, 0);

      $mail = array();
      $mail['subject'] = $overview[0]->subject;

      if (strpos($mail['subject'], 'Freddit Private Message') !== false) {
        $mail['subject'] = str_replace('Freddit Private Message', '', $mail['subject']);
        $headerContents = explode(':', $mail['subject']);
  
        $mail['subject'] = openssl_decrypt($headerContents[1], 'aes128', $config['EMAIL_ENCRYPTION_KEY']);
        $mail['from'] = openssl_decrypt($headerContents[0], 'aes128', $config['EMAIL_ENCRYPTION_KEY']);
        $mail['date'] = $overview[0]->date;
        // Call with bitmask flag 2 to avoid setting the seen flag when fetching the mail.
        $body = imap_fetchbody($mail_server, $mail_id, 1); 
        $mail['message'] = openssl_decrypt($body, 'aes128', $config['EMAIL_ENCRYPTION_KEY']);
  
        array_push($mails, $mail);
      }
    }
    
    imap_close($mail_server);
    return $mails;
  }

  /**
   * sendEmail
   * Sends an email to the target username.
   *
   * @param  mixed $target The username of the user to send the email to.
   * @param  mixed $subject The subject of the email.
   * @param  mixed $content The content of the email to send.
   *
   * @return void
   */
  public static function sendEmail($target, $subject, $content) {
    $mail = new PHPMailer(true);

    $config = include('../config.php');
    try {
      // Prepare server-side SMTP details.
      $mail->IsSMTP();
      $mail->Host = $config['EMAIL_SMTP_HOST'];
      $mail->Port = $config['EMAIL_SMTP_PORT'];
      $mail->SMTPAuth = true;
      $mail->Username = $config['EMAIL_SMTP_USERNAME'];
      $mail->Password = $config['EMAIL_SMTP_PASSWORD'];
      $mail->SMTPSecure = 'ssl';
    
      // Set the sender and recipient address.
      $mail->setFrom($config['EMAIL_SMTP_USERNAME']);

      $targetEmail = Model::getAttributeValue('user', 'email', 'username', $target);
      $mail->addAddress($targetEmail);
      
      // Prepare the content of the mail.
      $targetEncrypted = openssl_encrypt($target, 'aes128', $config['EMAIL_ENCRYPTION_KEY']);
      $mail->Subject = 'Freddit Private Message' . $targetEncrypted . ':' . openssl_encrypt($subject, 'aes128', $config['EMAIL_ENCRYPTION_KEY']);
      $mail->Body = openssl_encrypt($content, 'aes128', $config['EMAIL_ENCRYPTION_KEY']);
      $mail->send();
    } catch (Exception $e) {
      // Display exception if occurred to the client.
      echo 'Message could not be sent, error: ' . $e . "\n";
    }
  }
}
?>