<?php
if(!defined("LOAD")){ header('HTTP/1.0 404 Not Found');  die('Not Found'); }

$user = cmsUsers::getInstance();

if($user->isAuth()){  $this->redirect('/account/wallet'); $this->halt(); }

if($do == 'register'){


  if($this->inRequest('btc_register')) {

    $crypt = cmsCrypt::getInstance();

    $username = $this->request('username','str');
    $email = $this->request('email','email');
    $password = $this->request('password','str');
    $cpassword = $this->request('cpassword','str');
    $username = $this->request('username','str');

    $checl_u = $model->getCountUser("username='$username'");
    $check_e = $model->getCountUser("email='$email'");

    if(empty($username) or empty($email) or empty($password) or empty($cpassword)) {
      $this->addSessionMessage('All fields are required.');
      $this->redirectBack();
    }

    if(!$this->isValidUsername($username)) {
      $this->addSessionMessage('Please enter valid username.');
      $this->redirectBack();
    }

    if(!$this->isValidEmail($email)) {
      $this->addSessionMessage('Please enter valid email address.');
      $this->redirectBack();
    }

    if($check_u>0) {
      $this->addSessionMessage('This username is already used. Please choose another.');
      $this->redirectBack();
    }

    if($check_e>0) {
      $this->addSessionMessage('This email address is already used. Please choose another.');
      $this->redirectBack();
    }

    if(strlen($password)<8) {
      $this->addSessionMessage('Password must be more than 8 characters.');
      $this->redirectBack();
    }

    if($password !== $cpassword) {
      $this->addSessionMessage('Passwords does not match.');
      $this->redirectBack();
    }

    $email_hash = md5($email);
    $password_hash = $crypt->setEncrypt(md5($password));
    $time_signup = time();
    $ip = $_SERVER['REMOTE_ADDR'];

    $insert = $model->insert("('$username','$email','$password_hash','1','$email_hash','$time_signup','$ip')");

    $this->btc_generate_address($username,"default");

    $this->addSessionMessage('Your account was created.','success');
    $this->redirect('/');

  }

}
