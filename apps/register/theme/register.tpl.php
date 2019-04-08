<?php if(!defined("LOAD")){ header('HTTP/1.0 404 Not Found');die('Not Found'); } ?>

<!--Form with header-->
<div class="card text-center col-md-6 mx-auto">
  <form id="LoginForm" method="post" action="">
    <div class="card-block">

        <!--Header-->
        <div class="form-header  blue-gradient darken-4">
            <h3><i class="fa fa-plug"></i> Create account:</h3>
        </div>

        <!--Body-->
        <div class="md-form text-left">
            <i class="fa fa-user-o prefix"></i>
            <input class="form-control mb-4" type="text" id="form1" name="username" required/>
            <label for="form1">Username</label>
        </div>

        <div class="md-form text-left">
            <i class="fa fa-mail-forward prefix"></i>
            <input class="form-control mb-4" type="text" id="form2" name="email" required/>
            <label for="form2">Email address</label>
        </div>

        <div class="md-form text-left">
            <i class="fa fa-key prefix"></i>
            <input class="form-control mb-4" type="password" id="form3" name="password" required/>
            <label for="form3">Password</label>
        </div>

        <div class="md-form text-left">
            <i class="fa fa-key prefix"></i>
            <input class="form-control mb-4" type="password" id="form4" name="cpassword" required/>
            <label for="form4">Confirm Password</label>
        </div>

        <div class="text-center">
            <input type="hidden" name="btc_register" value="1"/>
            <button class="btn blue-gradient" type="submit"><i class="fa fa-sign-in" aria-hidden="true"></i>  Create account</button>
        </div>

    </div>

    <!--Footer-->
    <div class="modal-footer text-right">
        <div class="options">
            <p>Already have an account? <a href="/">Login <i class="fa fa-lock" aria-hidden="true"></i></a></p>
            <p>If you don't remember password: <a href="/login/forgot-password">Forgot Password </a></p>
        </div>
    </div>
</form>
</div>
<!--/Form with header-->
