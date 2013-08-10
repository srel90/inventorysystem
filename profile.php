<?php
session_start();
require_once('MySQL.class.php');
$database= new database();
if(!isset($_SESSION['users'])||empty($_SESSION['users']))header("location:index.php");
/*SERVER CODE---------------------------------------------------------------------------*/
$personal=$_SESSION['users'];
if(isset($_POST) && !empty($_POST)):
/*START*/
switch($_POST['mode']):
	case 'update':
		$strsql="
		UPDATE users SET 
		firstName='".$_POST['firstName']."'
		,lastName='".$_POST['lastName']."'
		,address='".$_POST['address']."'
		,phone='".$_POST['phone']."'
		,mobile='".$_POST['mobile']."'
		,email='".$_POST['email']."'
		,lastAccess=NOW()		
		";
		if(!empty($_POST['password'])){
		$strsql.=",password=MD5('".$_POST['password']."')";
		}
		$strsql.=" WHERE userID='".$_POST['userID']."'";
		if($database->execute($strsql)){
		$strsql="SELECT u.*,ut.type FROM users u LEFT OUTER JOIN usertype ut ON u.typeID=ut.typeID WHERE u.userID='".$_POST['userID']."'";
		$data=$database->query($strsql);
		$_SESSION['users']=$data;	
		echo 'true';
		}else{
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
<link href="css/kendo.black.min.css" rel="stylesheet" />
<script src="js/kendo/kendo.all.min.js"></script>
<!--ENDS Kendo UI-->
<!--JQUERY FORM-->
<script src="js/jquery.form.min.js" type="text/javascript"></script>
<!--ENDS JQUERY FORM-->
<script type="text/javascript">
<!--
$(function() {
	script.initial();
	script.validation();
	script.eventhandle();
});
var script= new function() {
	var validator = $("#profileForm");
	var status=$('#error');
	this.initial=function(){
    	$('#error').hide();     	                
	}//end initial
	this.eventhandle=function(){
		$("#save").click(function() {
            if (validator.validate()) {
                status.hide();
                script.update();
            } else {
                status.html("Oops! There is invalid data in the form.").show();
            }
        });
	}//end eventhandle
	this.validation=function(){
	validator = $('#profileForm').kendoValidator({
		rules: {
		          verifyPasswords: function(input){
		             var ret = true;
		             if (input.is('#confirmPassword')) {
		                 ret = input.val() === $('#password').val();
		             }
		             return ret;
		          }
		      },
		messages: {
			verifyPasswords: "Passwords do not match!"
		}
		}).data("kendoValidator"),status = $('#error');	
	}//end validator
	this.update=function(){
	$('#loading').show();
	var options = {
				success:function(response) {
				console.log(response);
					$('#loading').hide();
					if($.trim(response)=='true'){
			            alert("Update complete.\rPlease refresh page and see what it's changed.");
		            }else{
		            	alert("Cannot update!");
		            } 
			    }
			};
			$("#profileForm").ajaxSubmit(options);
	}//end update

}
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
			<div class="box">
				<?php include_once('profileheader.php'); ?>
				<form id="profileForm" action="profile.php" method="post" name="profileForm">
					<input id="userID" name="userID" type="hidden" value="<?php echo $personal[0]['userID'];?>" />
					<input id="mode" name="mode" type="hidden" value="update" />
					<fieldset class="k-content">
					<legend style="color: #37b2d1">Edit profile</legend>
					<fieldset>
					<legend>Personal information</legend>
					<div>
						<div>
							<label>Code :</label>
							<input id="code" class="input" disabled="disabled" name="code" type="text" value="<?php echo $personal[0]['code'];?>">
						</div>
						<div>
							<label>First name :</label>
							<input id="firstName" class="input" name="firstName" required="" title="Enter your first name" type="text" value="<?php echo $personal[0]['firstName'];?>">*
							<span class="k-invalid-msg" data-for="firstName">
							</span></div>
						<div>
							<label>Last name :</label>
							<input id="lastName" class="input" name="lastName" required="" title="Enter your last name" type="text" value="<?php echo $personal[0]['lastName'];?>">*
							<span class="k-invalid-msg" data-for="lastName">
							</span></div>
						<div>
							<label>ID Card :</label>
							<input id="IDCard" class="input" disabled="disabled" name="IDCard" required="" title="Enter your ID Card" type="text" value="<?php echo $personal[0]['IDCard'];?>">
							<span class="k-invalid-msg" data-for="IDCard">
							</span></div>
						<div>
							<label>Address :</label>
							<textarea id="address" class="input" cols="20" name="address" rows="2"><?php  echo $personal[0]['address'];?></textarea>
							<span class="k-invalid-msg" data-for="address">
							</span></div>
						<div>
							<label>Phone :</label>
							<input id="phone" class="input" name="phone" required="" title="Enter your phone number" type="text" value="<?php echo $personal[0]['phone'];?>">*
							<span class="k-invalid-msg" data-for="phone"></span>
						</div>
						<div>
							<label>Mobile :</label>
							<input id="mobile" class="input" name="mobile" title="Enter your mobile number" type="text" value="<?php echo $personal[0]['mobile'];?>">
							<span class="k-invalid-msg" data-for="mobile">
							</span></div>
						<div>
							<label>E-mail :</label>
							<input id="email" class="input" data-email-msg="Email format is not valid" name="email" required="" type="email" validationmessage="Enter your e-mail" value="<?php echo $personal[0]['email'];?>">*
							<span class="k-invalid-msg" data-for="email"></span>
						</div>
						<div>
							<label>Position :</label>
							<input id="position" class="input" disabled="disabled" name="position" title="Enter your position" type="text" value="<?php echo $personal[0]['position'];?>">
							<span class="k-invalid-msg" data-for="position">
							</span></div>
					</div>
					</fieldset> <fieldset>
					<legend>Login</legend>
					<div>
						<label>Username</label>
						<input id="username" class="input" disabled="disabled" name="username" type="text" value="<?php echo $personal[0]['username'];?>">
						<span class="k-invalid-msg" data-for="username"></span>
					</div>
					<div>
						<label>Password</label>
						<input id="password" class="input" name="password" title="Enter your password" type="password">
						<span class="k-invalid-msg" data-for="password"></span>
					</div>
					<div>
						<label>Confirm password</label>
						<input id="confirmPassword" class="input" name="confirmPassword" type="password">
						<span class="k-invalid-msg" data-for="confirmPassword">
						</span></div>
					</fieldset>
					<div class="clear" style="height: 10px">
					</div>
					<input id="save" class="k-button" name="save" type="button" value="Save" />
					<img id="loading" alt="" src="img/loading.gif" style="vertical-align: middle; display: none;">
					<p id="error" class="warning">Message</p>
					</fieldset></form>
			</div>
		</div>
		<!-- ENDS content --></div>
	<!-- ENDS wrapper-main --></div>
<!-- ENDS MAIN -->
<!-- Bottom --><?php include_once('bottom.php'); ?>
<!-- ENDS Bottom -->

</body>

</html>
