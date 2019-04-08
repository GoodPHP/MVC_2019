<?php
if(!defined("LOAD")){ header('HTTP/1.0 404 Not Found');  die('Not Found'); }

class cmsMailer{

  private static $instance;

  /**
  * Load function from class self
  * @return bool
  */
  public static function getInstance() {
    if (self::$instance === null) {
      self::$instance = new self;
    }
    return self::$instance;
  }

  /**
   * Creates and sends email
   * @param mixed $email
   * @param string $subject
   * @param string $message
   * @param mixed $attachment
   * @return bool
   */
  public static function mailText($email, $subject='', $message='', $attachment=''){

      $mailer = self::initMailSystem();

      // if an array of addresses came
      if(is_array($email)){

          foreach ($email as $address) {
              $mailer->AddAddress($address);
          }

      } else {
          $mailer->AddAddress($email);
      }

      // Topic of the letter
      // If the topic is set, install
      // otherwise look for the expression [subject: letter subject]
      $matches = array();
      if($subject){
          $mailer->Subject = $subject;
      } elseif (preg_match('/\[subject:(.+)\]/iu', $message, $matches)){

          list($subj_tag, $subj) = $matches;

          $message = trim(str_replace($subj_tag, '', $message));

          $mailer->Subject = $subj;

      }


      $matches = array();
      if($attachment){

    if(is_array($attachment)){
      foreach($attachment as $attach){
        $mailer->AddAttachment($attach);
      }
    } else {
      $mailer->AddAttachment($attachment);
    }

      } elseif(preg_match_all('/\[attachment:(.+)\]/iu', $message, $matches)){

          list($tags, $files) = $matches;

          foreach($tags as $idx => $att_tag){

              $message = trim(str_replace($att_tag, '', $message));

              $mailer->AddAttachment(PATH . $files[$idx]);

          }

      }

      // Message body in html
      $mailer->MsgHTML(nl2br($message));
      // Message body in text format
      $mailer->AltBody = strip_tags($message);

      return $mailer->Send();
  }

  /**
   * Initializes the object class PHPMailer
   * and forms presets
   */
  private static function initMailSystem(){

      include($_SERVER['DOCUMENT_ROOT'].'/includes/classes/PHPMailer/class.phpmailer.php');

      $mailer = new PHPMailer();
      $mailer->CharSet = 'UTF-8';
      $mailer->SetFrom('Test');

      if ($mail == 'smtp') {
          $mailer->IsSMTP();
          $mailer->Host          = '';
          $mailer->Port          = '';
          $mailer->SMTPAuth      = '';
          $mailer->SMTPKeepAlive = true;
          $mailer->Username      = '';
          $mailer->Password      = '';
          $mailer->SMTPSecure    = '';
      }

      $mailer->IsSendmail();
      return $mailer;
  }


}

?>
