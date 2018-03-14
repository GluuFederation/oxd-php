<html>
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="css/index.css">
<script src="./js/jquery.js"></script>
<script src="https://npmcdn.com/tether@1.2.4/dist/js/tether.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
</head>

<body>
<div class="container body-content">
<div class="row" id="notify" style="position: fixed;
    top: 0px;
    z-index: 1000;
    width: 100%;
    opacity: 100%;
    height:50px">
    <div class="col-md-8 col-md-offset-2">
        <div id="notify-message"></div>
    </div>
</div>

<div class="jumbotron" style="margin-top: 70px;">
    <h1>Gluu OXD PHP Library Demo web application</h1>
    <p class="lead">This PHP application is used to demo the Gluu's OXD Server PHP Library APIs.</p>
</div>

<br><br><br>


<div class="row">
    <div class="col-md-4">
        <h2>Login by OpenID Provider</h2>
    </div>
    <div class="col-md-2">
        <div class="form-group text-right">
            <a href="Authorize.php">
                <button class="btn btn-success text-center" style="margin-top:25px; width: 50%;">Login</button>
            </a>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group text-left">
            <a href="Settings.php">
                <button class="btn btn-success text-center" style="margin-top:25px; width: 50%;">Settings</button>
            </a>
        </div>
    </div>
</div>
	<footer>
		<p>&copy; <?php echo date("Y"); ?> - My PHP Application</p>
	</footer>
</div>
</body>
</html>