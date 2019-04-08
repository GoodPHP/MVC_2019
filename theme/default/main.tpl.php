<?php if(!defined("LOAD")){ header('HTTP/1.0 404 Not Found');die('Not Found'); } ?>
<!DOCTYPE html>
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<?php echo $this->printHead($head); ?>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1" name="viewport" />
<meta charset="utf-8" />
<!-- Font Awesome -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<!-- Bootstrap core CSS -->
<link href="/theme/mdb/css/bootstrap.min.css" rel="stylesheet">
<!-- Material Design Bootstrap -->
<link href="/theme/mdb/css/mdb.min.css" rel="stylesheet">
<!-- Your custom styles (optional) -->
<link href="/theme/mdb/css/style.min.css" rel="stylesheet">
<style type="text/css">
html,
body,
.view {
height: 100%;
}

header,
.view {
height: 80px;
}

@media (max-width: 740px) {
html,
body,
.view {
height: 1050px;
}

#block_pt_left{
  float: none;
  margin-top: 0px;
  overflow: hidden;
}

#block_pt_left .col-md-12{
  margin-left: 5px;
  float:left;
  width: 47%;
}

}
@media (min-width: 800px) and (max-width: 850px) {
html,
body,
.view {
height: 700px;
}
}
@media (min-width: 800px) and (max-width: 850px) {
.navbar:not(.top-nav-collapse) {
background: #1C2331!important;
}
}
</style>
<!-- END THEME LAYOUT STYLES -->
<link rel="shortcut icon" href="favicon.ico" />
</head>
<body>

<header>
<!-- Navbar -->
<nav class="navbar fixed-top navbar-expand-lg navbar-dark scrolling-navbar">
<div class="container">

<!-- Brand -->
<a class="navbar-brand" href="/">
<strong>MYeWallet</strong>
</a>

<!-- Collapse -->
<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
aria-expanded="false" aria-label="Toggle navigation">
<span class="navbar-toggler-icon"></span>
</button>

<!-- Links -->
<div class="collapse navbar-collapse" id="navbarSupportedContent">

<!-- Right -->
<ul class="navbar-nav form-inline my-2 my-lg-0 ml-auto">
<li class="nav-item">
<a href="/register" class="nav-link btn btn-deep-orange"
target="_blank">
<i class="fa fa-user mr-2"></i>Get My Free Wallet NOW
</a>
</li>
</ul>

</div>

</div>
</nav>
<!-- Navbar -->

</header>

<!--Main layout-->
<main class="padding-90">

<?php $messages = cmsCore::getSessionMessages(); ?>
<?php if ($messages) { ?>
<div class="container" id="alert_mar_pad">
<div class="row">
<div class="col-md-12 mb-8">
<div class="sess_messages" id="sess_messages">
<?php foreach($messages as $message){ ?>
<?php echo $message; ?>
<?php } ?>
</div>
</div>
</div>
</div>

<?php } ?>

<div class="container" id="mainbox">
<div class="row">
<div class="col-md-12 mb-8">
<?php $mvc->initApps(); ?>
</div>
</div>

<?php if($_SERVER['REQUEST_URI'] == '/'){ ?>
<div class="pt-4" id="block_pt_left">
<div class="col-md-12 col-sm-6">
<a class="btn waves-effect waves-light" href="#" target="_blank" role="button"> <span class="h3">4000+</span> <br/> <span>Wallets</span></a>
</div>
<div class="col-md-12 col-sm-6">
<a class="btn waves-effect waves-light" href="#" target="_blank" role="button"> <span class="h3">$14B</span> <br/> <span>Send/Get</span></a>
</div>
<div class="col-md-12 col-sm-6">
<a class="btn waves-effect waves-light" href="#" target="_blank" role="button"> <span class="h3">140</span> <br/> <span>Countries</span></a>
</div>
<div class="col-md-12 col-sm-6">
<a class="btn waves-effect waves-light" href="#" target="_blank" role="button"> <span class="h3">2017</span> <br/> <span>Based</span></a>
</div>
</div>
<?php } ?>

</div>

</main>
<!--Main layout-->


<!--Footer-->
<footer class="page-footer text-center font-small mt-4 wow fadeIn">
<!--Copyright-->
<div class="footer-copyright py-3">
MyEwallet © 2017 - <?php echo date('Y');?>
</div>
<!--/.Copyright-->

</footer>
<!--/.Footer-->

<!-- SCRIPTS -->
<!-- JQuery -->
<script type="text/javascript" src="/theme/mdb/js/jquery-3.3.1.min.js"></script>
<!-- Bootstrap tooltips -->
<script type="text/javascript" src="/theme/mdb/js/popper.min.js"></script>
<!-- Bootstrap core JavaScript -->
<script type="text/javascript" src="/theme/mdb/js/bootstrap.min.js"></script>
<!-- MDB core JavaScript -->
<script type="text/javascript" src="/theme/mdb/js/mdb.min.js"></script>
<!-- Initializations -->
<script type="text/javascript">
// Animations initialization
new WOW().init();
</script>
<input type="hidden" id="url" value="/">
</body>
</html>
