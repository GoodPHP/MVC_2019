<?php if(!defined("LOAD")){ header('HTTP/1.0 404 Not Found');die('Not Found'); } ?>


<!--Form with header-->
<div class="card text-center col-md-6 mx-auto">
  <form id="LoginForm" method="post" action="">
    <div class="card-block">

        <!--Header-->
        <div class="form-header  blue-gradient darken-4">
            <h3><i class="fa fa-lock"></i> Login:</h3>
        </div>

        <!--Body-->
        <div class="md-form text-left">
            <i class="fa fa-envelope prefix"></i>
            <input type="text" name="email" id="form2" class="form-control mb-4" required>
            <label for="form2">Your email</label>
        </div>

        <div class="md-form text-left">
            <i class="fa fa-lock prefix"></i>
            <input type="password" name="password" id="form4" class="form-control mb-4" required>
            <label for="form4">Your password</label>
        </div>

        <div class="form-check text-left">
        <input type="checkbox" name="remember_me" value="yes" class="form-check-input" id="materialUnchecked">
        <label class="form-check-label" for="materialUnchecked"> Remember me</label>
        </div>

        <div class="text-center">
            <input type="hidden" name="btc_login" value="1"/>
            <button class="btn blue-gradient" type="submit"><i class="fa fa-sign-in" aria-hidden="true"></i>  Sign up</button>
        </div>

    </div>

    <!--Footer-->
    <div class="modal-footer text-right">
        <div class="options">
            <p>Still you do not have an account? <a href="/register">Register New <i class="fa fa-plug" aria-hidden="true"></i></a></p>
            <p>If you don't remember password: <a href="/login/forgot-password">Forgot Password </a></p>
        </div>
    </div>
</form>
</div>
<!--/Form with header-->
