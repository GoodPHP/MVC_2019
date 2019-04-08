<?php if(!defined("LOAD")){ header('HTTP/1.0 404 Not Found');die('Not Found'); } ?>



<!--Form with header-->
<div class="card text-center col-md-6 mx-auto">
  <form id="LoginForm" method="post" action="">
    <div class="card-block">

        <!--Header-->
        <div class="form-header  blue-gradient darken-4">
            <h3><i class="fa fa-edit" aria-hidden="true"></i> Forgot Password:</h3>
        </div>

        <!--Body-->
        <div class="md-form text-left">
            <i class="fa fa-envelope prefix"></i>
            <input type="text" name="email" id="form2" class="form-control mb-4" required>
            <label for="form2">Your email</label>
        </div>

        <div class="text-center">
            <input type="hidden" name="btc_reset" value="1"/>
            <button class="btn blue-gradient" type="submit"><i class="fa fa-history" aria-hidden="true"></i> Send</button>
        </div>

    </div>

    <!--Footer-->
    <div class="modal-footer text-right">
        <div class="options">
            <p>Already have an account? <a href="/">Login <i class="fa fa-lock"></i></i></a></p>
            <p>Still you do not have an account? <a href="/register">Register New</a></p>
        </div>
    </div>
</form>
</div>
<!--/Form with header-->


<!--
<form class="text-center border border-light p-5" method="post" action="">
<p class="h4 mb-4">Forgot Password?</p>
<p>Use your email address to recover your password. Enter it in form below and will receive email with link to change your password.</p>

<input class="form-control mb-4" type="text" placeholder="Email address" name="email" required/>


<input type="hidden" name="btc_reset" value="1"/>
<button class="btn btn-info btn-block" type="submit">Send</button>

<div class="col-sm-12">
Already have an account? <a href="/">Login from here</a>.<br/>
Still you do not have an account? <a href="/register">Create new</a> here, it's free!
</div>

</form>
 -->
