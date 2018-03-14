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
    <h1>oxd PHP Application UMA</h1>
</div>

<br><br><br>

<form action="Get_protected_resource.php" method="post" class="form-horizontal">
    <div class="row">
        <label class="col-sm-4">UMA Protected Resource Url:</label>
        <div class="col-sm-8">
            <div class="form-group text-left">
                <input type="text" value="" size="50" class="form-control ip_box" style="margin-top:25px; width: 50%;" name="protected_resource" required />
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-8">
            <div class="form-group text-left">
                <input class="btn btn-success text-center" type="submit" style="margin-top:25px; width: 50%;" value="Get Resource" />
            </div>
        </div>
    </div>
</form>
	<footer>
		<p>&copy; <?php echo date("Y"); ?> - My PHP Application</p>
	</footer>
</div>
</body>
</html>