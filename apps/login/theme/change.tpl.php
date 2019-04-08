<?php if(!defined("LOAD")){ header('HTTP/1.0 404 Not Found');die('Not Found'); } ?>


<!--Form with header-->
<div class="card text-center col-md-6 mx-auto">
  <form id="ResetForm" method="post" action="">
    <div class="card-block">

        <!--Header-->
        <div class="form-header  blue-gradient darken-4">
            <h3> Change Password:</h3>
        </div>

        <!--Body-->
        <div class="md-form text-left">
            <i class="fa fa-lock prefix"></i>
            <input class="form-control form-control-solid placeholder-no-fix form-group" type="password" id="form1" name="password" required/>
            <label for="form1">New password</label>
        </div>

        <div class="md-form text-left">
            <i class="fa fa-lock prefix"></i>
            <input class="form-control form-control-solid placeholder-no-fix form-group" type="password" id="form2" name="cpassword" required/>
            <label for="form2">Confirm password</label>
        </div>

        <div class="text-center">
            <input type="hidden" name="btc_reset" value="1"/>
            <button class="btn blue-gradient" type="submit"><i class="fa fa-sign-in" aria-hidden="true"></i>  Change</button>
        </div>

    </div>

    <!--Footer-->
    <div class="modal-footer text-text">
        <div class="options">
        <p>This session is valid only 24 hours and you need to change your password before time expire.</p>
        </div>
    </div>
</form>
</div>
<!--/Form with header-->
