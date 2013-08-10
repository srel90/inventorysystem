<?php
session_start();
/*SERVER CODE---------------------------------------------------------------------------*/
if(isset($_POST) && !empty($_POST)):
/*START*/
require_once('MySQL.class.php');
$database= new database();
switch($_POST['mode']):
	case 'checkLogin':
	$strsql=sprintf("SELECT u.*,ut.type FROM users u LEFT OUTER JOIN usertype ut ON u.typeID=ut.typeID WHERE u.username='%s' AND u.password=MD5('%s') AND u.status='1'",mysql_real_escape_string($_POST['username']),mysql_real_escape_string($_POST['password']));	
	$data= $database->query($strsql);
	if(count($data)!=0){
	$strsql="UPDATE users SET lastAccess=NOW() WHERE userID='".$data[0]['userID']."'";
	$database->execute($strsql);
		$_SESSION['users']=$data;
		echo 'true';
	}else{
		unset($_SESSION['users']);
		echo 'false';
	}
	break;
endswitch;
/*ENDS*/
exit();
endif;
/*END SERVER CODE-----------------------------------------------------------------------*/
?>

<!DOCTYPE  html>
<html>

<head>
<meta charset="utf-8">
<title>The Inventory System</title>
<!-- CSS -->
<link href="css/style.css" media="screen" rel="stylesheet" type="text/css" />
<link href="css/social-icons.css" media="screen" rel="stylesheet" type="text/css" />
<!--[if IE 8]>
<link href="css/ie8-hacks.css" media="screen" rel="stylesheet" type="text/css" />
<![endif]-->
<!-- ENDS CSS -->
<!-- GOOGLE FONTS 
		<link href='http://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet' type='text/css'>-->
<!-- JS -->
<script src="js/jquery-1.5.1.min.js" type="text/javascript"></script>
<script src="js/jquery-ui-1.8.13.custom.min.js" type="text/javascript"></script>
<script src="js/easing.js" type="text/javascript"></script>
<script src="js/jquery.scrollTo-1.4.2-min.js" type="text/javascript"></script>
<script src="js/jquery.cycle.all.js" type="text/javascript"></script>
<script src="js/common.js" type="text/javascript"></script>
<script src="js/utility.js" type="text/javascript"></script>
<!-- Isotope -->
<script src="js/jquery.isotope.min.js"></script>
<!--[if IE]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]--><!--[if IE 6]>
<script src="js/DD_belatedPNG.js" type="text/javascript"></script>
<script>
	      		/* EXAMPLE */
	      		//DD_belatedPNG.fix('*');
	    	</script>
<![endif]-->
<!-- ENDS JS -->
<!-- Nivo slider -->
<link href="css/nivo-slider.css" media="screen" rel="stylesheet" type="text/css" />
<script src="js/nivo-slider/jquery.nivo.slider.js" type="text/javascript"></script>
<!-- ENDS Nivo slider -->
<!-- tabs -->
<link href="css/tabs.css" media="screen" rel="stylesheet" type="text/css" />
<script src="js/tabs.js" type="text/javascript"></script>
<!-- ENDS tabs -->
<!-- prettyPhoto -->
<script src="js/prettyPhoto/js/jquery.prettyPhoto.js" type="text/javascript"></script>
<link href="js/prettyPhoto/css/prettyPhoto.css" media="screen" rel="stylesheet" type="text/css" />
<!-- ENDS prettyPhoto -->
<!-- superfish -->
<link href="css/superfish.css" media="screen" rel="stylesheet" />
<script src="js/superfish-1.4.8/js/hoverIntent.js" type="text/javascript"></script>
<script src="js/superfish-1.4.8/js/superfish.js" type="text/javascript"></script>
<script src="js/superfish-1.4.8/js/supersubs.js" type="text/javascript"></script>
<!-- ENDS superfish -->
<!-- poshytip -->
<link href="js/poshytip-1.0/src/tip-twitter/tip-twitter.css" rel="stylesheet" type="text/css" />
<link href="js/poshytip-1.0/src/tip-yellowsimple/tip-yellowsimple.css" rel="stylesheet" type="text/css" />
<script src="js/poshytip-1.0/src/jquery.poshytip.min.js" type="text/javascript"></script>
<!-- ENDS poshytip -->
<!--Kendo UI-->
<link href="css/kendo.common.min.css" rel="stylesheet" />
<link href="css/kendo.default.min.css" rel="stylesheet" />
<script src="js/kendo/kendo.all.min.js"></script>
<!--ENDS Kendo UI-->
<!--JQUERY FORM-->
<script src="js/jquery.form.min.js" type="text/javascript"></script>
<!--ENDS JQUERY FORM-->
<script type="text/javascript">
<!--
$(document).ready(function() {
  // hide messages 
	$("#error").hide();
	$("#success").hide();
	$("#contactForm #submit").click(function() {
		//required:
		
		//username
		var name = $("input#username").val();
		if(name == ""){
			$("#error").fadeIn().text("username required.");
			$("input#username").focus();
			return false;
		}

		//password
		var name = $("input#password").val();
		if(name == ""){
			$("#error").fadeIn().text("password required.");
			$("input#password").focus();
			return false;
		}
		$('#loading').show();
		var options = {
			success:function(response) {
			//console.log(response);
			$('#loading').hide();
			if($.trim(response)=='true'){		            
		            _Redirect('main.php');
	            }else{
		            $("#error").fadeIn().text("Username or Password is invalid!");
	            } 
		    }
		};
		$("#contactForm").ajaxSubmit(options);

	});

});
//-->
</script>
</head>

<body class="home">

<!-- Menu -->
<div id="menu">
	<?php include_once('menu.php'); ?></div>
<!-- ENDS Menu -->
<!-- MAIN -->
<div id="main">
	<!-- wrapper-main -->
	<div class="wrapper">
		<!-- content -->
		<div id="content">
			<div class="clear">
			</div>
			<h2 class="line-divider" style="margin-left: 30px;"><br>Welcome to 
			the inventory system<br></h2>
			<!-- column (left)-->
			<div class="one-column">
				<!-- form -->
				<h2 class="line-divider">Login</h2>
				<form id="contactForm" name="contactForm" action="index.php" method="post">
				<input id="txtmode" name="mode" type="hidden" value="checkLogin" />


					<div>
						<label>Username</label>
						<input id="username" class="form-poshytip" name="username" title="Enter your username" type="text" />
					</div>
					<div>
						<label>Password</label>
						<input id="password" class="form-poshytip" name="password" title="Enter your password" type="password" />
					</div>
					<input id="submit" name="submit" type="button" value="Login" />
					<img alt="" src="img/loading.gif" id="loading"  style="vertical-align:middle;display:none;" >
				
					<p id="error" class="warning">Message</p>
				</form>
				<!-- ENDS form --></div>
			<!-- ENDS column -->
			<!-- column (right)-->
			<!-- ENDS column --></div>
		<!-- ENDS content --></div>
	<!-- ENDS wrapper-main --></div>
<!-- ENDS MAIN -->
<!-- Bottom --><?php include_once('bottom.php'); ?>
<!-- ENDS Bottom -->

</body>

</html>
