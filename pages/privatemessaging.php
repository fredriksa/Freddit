<?php
  /** 
   * Implements a webpage that provides private messaging functionality
   * through the use of mail
   */
  
  include_once dirname(__DIR__, 1) . '/src/sessionhelper.php';
  include_once dirname(__DIR__, 1) . '/src/redirecthelper.php';
  include_once dirname(__DIR__, 1) . '/src/models/user.php';
  include_once dirname(__DIR__, 1) . '/src/modelloader.php';
  include_once dirname(__DIR__, 1) . '/src/models/post.php';
  include_once dirname(__DIR__, 1) . '/src/models/subfreddit.php';
  include_once dirname(__DIR__, 1) . '/src/emailhelper.php';

  if (!SessionHelper::isLoggedIn())
    RedirectHelper::redirect('index.php');
    
  $html = file_get_contents('../html/privatemessaging.html');
  $html_pieces = explode('<!--===explodeEmail===-->', $html);

  echo $html_pieces[0];

  foreach (EmailHelper::getEmails() as $email) {
    $piece = str_replace('---$emailSubject---', $email['subject'], $html_pieces[1]);
    $piece = str_replace('---$emailSender---', $email['from'], $piece);
    $piece = str_replace('---$emailSentAt---', $email['date'], $piece);
    $piece = str_replace('---$message---', $email['message'], $piece);
    echo $piece;
  }

  echo $html_pieces[2];
?>