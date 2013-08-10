<?php
session_start();
/*SERVER CODE---------------------------------------------------------------------------*/
require_once('MySQL.class.php');
$database= new database();

//start function
function decryptIt( $q ) {
    $cryptKey  = 'OnlineSubmission.jgsee.org';
    $qDecoded      = rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), base64_decode( $q), MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ), "\0");
    return( $qDecoded );
}
function encryptIt( $q ) {
    $cryptKey  = 'OnlineSubmission.jgsee.org';
    $qEncoded      = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), $q, MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ) );
    return(urlencode($qEncoded));
}
function make_token($secret) {
	$str = "";
	for($i=0; $i<7; $i++) $str .= rand_alphanumeric();
	$pos = rand(0, 24);
	$str .= chr(65 + $pos);
	return $str . substr(md5($str . $secret), $pos, 8);
}
function validate_token($str,$secret) {
    $rs = substr($str, 0, 8);
    return $str == $rs . substr(md5($rs . $secret), ord($str[7])-65, 8);
}
function rand_alphanumeric() {
	$subsets[0] = array('min' => 48, 'max' => 57); // ascii digits
	$subsets[1] = array('min' => 65, 'max' => 90); // ascii lowercase English letters
	$subsets[2] = array('min' => 97, 'max' => 122); // ascii uppercase English letters
	
	// random choice between lowercase, uppercase, and digits
	$s = rand(0, 2);
	$ascii_code = rand($subsets[$s]['min'], $subsets[$s]['max']);
	
	return chr( $ascii_code );
}
function sendmail($email,$subject,$content){
	$to = $email;
	$mime_boundary = "----onlinesubmission.jgsee.org----".md5(time());
	$headers = "From: info@onlinesubmission.jgsee.org\r\n";
	$headers.= "Reply-To: info@onlinesubmission.jgsee.org\r\n";
	$headers.= "MIME-Version: 1.0\r\n";
	
	$headers .= "Content-Type: multipart/alternative; boundary=\"$mime_boundary\"\r\n";
	$message = "--$mime_boundary\r\n";
	$message .= "Content-Type: text/html; charset=UTF-8\r\n";
	$message .= "Content-Transfer-Encoding: 8bit\r\n\n";
	
	foreach ($content as $line_num => $line) {
	$message .= $line."\n";
	} 
	$message .= "--$mime_boundary--\n\n";		
	if (mail( $to, $subject, $message, $headers )){	
	
		return true;
	}else{
		return false;
	}
}
function sendMailReset($uid,$name,$email){
	$file=file("mail_forgetsomething_template.html");
	$aid=make_token($uid);
	$nad=sprintf('%1$05d', $uid).$aid;
	$content=array();
	foreach ($file as $line_num => $line) {			
	$line=str_ireplace("{name}",$name,$line);
	$line=str_ireplace("{ads}",encryptIt($nad),$line);
	$content[]=$line;
	}
	return sendmail($email,"Confirm reset password for ".$name." at OnlineSubmission.jgsee.org",$content);
}
function sendMailAccountInfo($name,$username,$password,$email){
	$file=file("mail_resetpassword_template.html");
	$content=array();
	foreach ($file as $line_num => $line) {			
	$line=str_ireplace("{name}",$name,$line);
	$line=str_ireplace("{username}",$username,$line);
	$line=str_ireplace("{password}",$password,$line);
	$content[]=$line;
	}
	return sendmail($email,"Replacement login information for ".$name." at OnlineSubmission.jgsee.org",$content);
}

//end function
if(isset($_GET['app'])&&!empty($_GET['app'])&&$_GET['app']=='reset'){

$code=decryptIt($_GET['ad']);
$id=(int)substr($code,0,5);

$strsql="SELECT reset FROM personal WHERE id='".$id."' AND reset='".$_GET['ad']."'";
$cdata=count($database->query($strsql));
	if($cdata==0){
		$aid=substr($code,5,strlen($code));
			if(validate_token($aid,$id)){
				$password=substr(md5(make_token($aid.$id)),0,8);
				$strsql="UPDATE personal SET password=MD5('".$password."'),reset='".$_GET['ad']."' WHERE id='".$id."'";
				$database->execute($strsql);
				$strsql="SELECT * FROM personal WHERE id='".$id."'";
				$data=$database->query($strsql);
				$name=$data[0]['firstName']." ".$data[0]['middleName']." ".$data[0]['lastName'];
				sendMailAccountInfo($name,$data[0]['username'],$password,$data[0]['primaryEmail']);
				echo '<script type="text/javascript">';
				echo 'alert("Please check your primary e-mail inbox for new password.")';
				echo '</script>';
			}else{
				echo '<script type="text/javascript">';
				echo 'alert("Your ad code does\'t corect please contact administrator.")';
				echo '</script>';
		
			}
	}else{
	echo '<script type="text/javascript">';
	echo 'alert("Your ad code is expired Please request a new one.")';
	echo '</script>';

	}
}
if(isset($_POST) && !empty($_POST)):
/*START*/
switch($_POST['mode']):
	case 'forgotPassword':
	if(isset($_POST['primaryEmail'])&&!empty($_POST['primaryEmail'])){
		$strsql=sprintf("SELECT * FROM personal WHERE primaryEmail='%s'",mysql_real_escape_string($_POST['primaryEmail']));
	}else if(isset($_POST['secoundaryEmail'])&&!empty($_POST['secoundaryEmail'])){
		$strsql=sprintf("SELECT * FROM personal WHERE secoundaryEmail='%s'",mysql_real_escape_string($_POST['secoundaryEmail']));
	}
	$data= $database->query($strsql);
	if(count($data)!=0){
	$uid=$data[0]['id'];
	$name=$data[0]['firstName']." ".$data[0]['middleName']." ".$data[0]['lastName'];
	$email=$data[0]['primaryEmail'];
		if(sendMailReset($uid,$name,$email)){
		echo 'true';
		}else{
		echo 'false';
		}
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
<title>JGSEE Online Submission</title>
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
$(function() {
  // hide messages 
	$("#error").hide();	
	var validator=$('#contactForm').kendoValidator().data("kendoValidator"),status = $('#error');
	
	$('#submit').click(function() {
	if (validator.validate()) {
        status.hide();
        $('#loading').show();
		var options = {
			success:function(response) {
			console.log(response);
			$('#loading').hide();
			if($.trim(response)=='true'){		            
		            alert("Completed.\rWe have send you an e-mail for resetting instructions, \rPlease check your e-mail inbox.");
		            _Redirect('index.php');
		            $('#contactForm')[0].reset();
	            }else{
		            alert("Your e-mail address is not exist in our system!");
	            } 
		    }
		};
		$("#contactForm").ajaxSubmit(options);

    } else {
        status.html("Oops! There is invalid data in the form.").show();
    }


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
			<h2 class="line-divider" style="margin-left: 30px;">Welcome to the JGSEE 
			online manuscript submission service</h2>
			<p class="line-divider" style="margin-left: 30px; text-align: justify;">
			With this service you can submit manuscripts to a number of JGSEE journals, 
			you will be able to check the content of your submission, the electronic 
			file will be used for editorial assessment and online refereeing, and 
			the editorial decision on the manuscript will be communicated to you. 
			By using this service you will guarantee fast and safe submission of 
			your manuscript, and speed the assessment process. In order to use the 
			service you will need to be the SENIOR CORRESPONDENCE AUTHOR of the 
			paper, and click on “Sign up now” and follow the instructions. If you 
			have already done so, please proceed by entering your username and password 
			on the next page. You will need ONE text file of your manuscript, CONTAINING 
			all figures and tables etc. Supporting Information should be added to 
			the end of the same file. Acceptable file types are: Microsoft Word, 
			Rich Text Format, Postscript, and PDF. </p>
			<!-- column (left)-->
			<div class="one-column">
				<!-- form -->
				<h2 class="line-divider">Forget Username or Password</h2>
				<form id="contactForm" name="contactForm" action="forgetsomething.php" method="post">
				<input id="txtmode" name="mode" type="hidden" value="forgotPassword" />
				
					<fieldset>
					<div>
						<label>Enter your primary e-mail</label>
						<input id="primaryEmail" class="input" name="primaryEmail" data-email-msg="Email format is not valid" title="Enter your primary e-mail" type="email" />
						<span class="k-invalid-msg" data-for="primaryEmail"></span>
					</div>
					<div>
						<label>Or your secoundary e-mail</label>
						<input id="secoundaryEmail" class="input" name="secoundaryEmail" data-email-msg="Email format is not valid" title="Enter your secoundary e-mail" type="email" />
						<span class="k-invalid-msg" data-for="secoundaryEmail"></span>
					</div>
					<input id="submit" name="submit" type="button" value="Send me a new one" style="width:150px" />
					<img alt="" src="img/loading.gif" id="loading"  style="vertical-align:middle;display:none;" >
					</fieldset>
					<p id="error" class="warning">Message</p>
				</form>
				<!-- ENDS form --></div>
			<!-- ENDS column -->
			<!-- column (right)-->
			<div class="one-column">
				<!-- content -->
				<h2 class="line-divider">Register New</h2>
				<p>&nbsp;</p>
				<p>Don't have a Online submission account?
				<a href="register.php">Sign up now</a></p>
				<!-- ENDS content --></div>
			<!-- ENDS column --></div>
		<!-- ENDS content --></div>
	<!-- ENDS wrapper-main --></div>
<!-- ENDS MAIN -->
<!-- Bottom --><?php include_once('bottom.php'); ?>
<!-- ENDS Bottom -->

</body>

</html>
