<?php if(!defined("LOAD")){ header('HTTP/1.0 404 Not Found');die('Not Found'); } ?>
<?php $users = cmsUsers::getInstance();?>

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

<!-- Navbar -->
<header>
<nav class="navbar navbar-admin fixed-top navbar-expand-lg navbar-dark scrolling-navbar">
<div class="container">

<!-- Brand -->
<a class="navbar-brand" href="/account/wallet">
<strong>MYeWallet</strong>
</a>

<!-- Collapse -->
<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
aria-expanded="false" aria-label="Toggle navigation">
<span class="navbar-toggler-icon"></span>
</button>

<!-- Links -->
<div class="collapse navbar-collapse" id="navbarSupportedContent">

<!-- Left -->
<ul class="navbar-nav mr-auto">
<?php
$url = $_SERVER['REQUEST_URI'];
$url = explode('/',$url);
$b = $url[2];
?>
<li class="nav-item <?php if($b == "wallet") { ?>active<?php } ?>">
<a class="nav-link" href="/account/wallet">Wallet
<span class="sr-only">(current)</span>
</a>
</li>
<li class="nav-item <?php if($b == "addresses") { ?>active<?php } ?>">
<a class="nav-link" href="/account/addresses"> Addresses</a>
</li>
<li class="nav-item <?php if($b == "transactions") { ?>active<?php } ?>">
<a class="nav-link" href="/account/transactions"> Transactions</a>
</li>
<li class="nav-item <?php if($b == "security") { ?>active<?php } ?>">
<a class="nav-link" href="/account/security"> Security</a>
</li>
<li class="nav-item">
<a class="nav-link" href="/account/faq"> FAQ</a>
</li>

</ul>

<!-- Right -->
<ul class="navbar-nav nav-flex-icons">

<li class="nav-item dropdown">
<a class="nav-link dropdown-toggle waves-effect waves-light" id="navbarDropdownMenuLink-4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
<i class="fa fa-user"></i> <?php echo $users->getUser()['username']; ?> </a>
<div class="dropdown-menu dropdown-menu-right dropdown-info" aria-labelledby="navbarDropdownMenuLink-4">
<a href="/account/security"><i class="icon-lock"></i> Security </a>
<a href="/account/logout"><i class="icon-key"></i> Logout</a>
</div>
</li>

</ul>

</div>

</div>
</nav>
</header>
<!-- Navbar -->


<!--Main layout-->
<main>
</br>

<?php $messages = cmsCore::getSessionMessages(); ?>
<?php if ($messages) { ?>
<div class="container">
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
<script type="text/javascript" src="/theme/mdb/js/functions.js"></script>
<!-- Initializations -->
<script type="text/javascript">
// Animations initialization
new WOW().init();
</script>
<input type="hidden" id="url" value="/">
</body>
</html>
