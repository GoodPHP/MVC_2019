<?php
if(!defined("LOAD")){ header('HTTP/1.0 404 Not Found');  die('Not Found'); }


$users = cmsUsers::getInstance();

if($users->isAuth()){  $this->redirect('/account/wallet'); $this->halt(); }

if($do == 'login'){

  if($_SERVER['REQUEST_URI'] == '/login'){
    $this->redirect("/");
  }

  if($this->inRequest('btc_login')) {

    $crypt = cmsCrypt::getInstance();

    $email = $this->request('email','email');
    $password = $this->request('password','str');
    $password = md5($password);

    if(!$email){
      $this->addSessionMessage('Please enter your email.','danger');
      $this->redirectBack();
    }

    $check = $model->getUser($email);

    if(empty($email) or empty($password)){
      $this->addSessionMessage('Please enter your email address and password.','danger');
      $this->redirectBack();
    }

    if($crypt->checkPassword($password, $crypt->genericHash($check['password'])) == 0) {
      $this->addSessionMessage('Wrong email address or password.','danger');
      $this->redirectBack();
    }

    $row = $check;

    if($row['status'] == "3") {
      $this->addSessionMessage('Your account is blocked.','danger');
      $this->redirectBack();
    }

    if($this->request('remember_me','str') == "yes"){
      $users->setCookieUser("bitcoinwallet_uid", $row['id'], time() + (86400 * 30), '/'); // 86400 = 1 day
    }

    $_SESSION['btc_uid'] = $row['id'];
    $time = time();
    $update = $model->updateUser($time,$row['id']);

    $this->redirect("/account/wallet");
  }

}


if($do == 'forgot'){

  if($this->inRequest('btc_reset')) {

    $crypt = cmsCrypt::getInstance();
    $mail = cmsMailer::getInstance();

    $email = $this->request('email','email');

    $check = $model->getUser($email);

    if(!$email){
      $this->addSessionMessage('Please enter your email.','danger');
      $this->redirectBack();
    }

    $count = $model->getCountUser($email);

    if($count==0) {
      $this->addSessionMessage('No such user with this email address.','danger');
      $this->redirectBack();
    }

    $row = $check;

    $hash = $this->randomHash(20);

    $id = $row['id'];

    $update = $model->updateUserForgot("email_hash='$hash' WHERE id='$id'");

    $msubject = 'Reset password request';
    $mreceiver = $email;
    $message = 'Hello, You use form to reset password in our website. To change it click on link below.
<a href="https://myewallet.io/login/change/'.$row['email_hash'].'">Change password</a> If this email was not requested by you, please ignore it.If you have some problems please feel free to contact with us on ';

    $mail = $mail->mailText($mreceiver,$msubject,$message);

    if($mail) {
      $this->addSessionMessage('Link for password change was sent. Check your inbox or spam folder.','success');
      $this->redirect('/');
    } else {
      $this->addSessionMessage('Error email sent. Please contact with website admin ','danger');
      $this->redirectBack();
    }

  }
}


if($do == 'change'){
  $crypt = cmsCrypt::getInstance();

  $row = $model->getDataByHash($request['hash']);

  if(!$row){
    $this->error404();
  }

  if($this->inRequest('btc_reset')) {
    $password = $this->request('password','str');
    $cpassword = $this->request('cpassword','str');

    if(empty($password) OR empty($cpassword)) {
      $this->addSessionMessage('Please enter new password.','danger');
      $this->redirectBack();
    } elseif($password !== $cpassword) {
      $this->addSessionMessage('Passwords does not match.','danger');
      $this->redirectBack();
    } else {
      $pass = md5($password);
      $pass = $crypt->setEncrypt($pass);
      $update = $model->setPassword($pass,$row['id']);
      $this->addSessionMessage('Your password was changed!','success');
      $this->redirect('/');
  }
}

}
