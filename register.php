<?php
session_start();
require_once('MySQL.class.php');
$database= new database();

/*SERVER CODE---------------------------------------------------------------------------*/
if(isset($_POST) && !empty($_POST)):
/*START*/
//start function	
function make_token($secret) {
	$str = "";
	for($i=0; $i<7; $i++) $str .= rand_alphanumeric();
	$pos = rand(0, 24);
	$str .= chr(65 + $pos);
	return $str . substr(md5($str . $secret), $pos, 8);
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
	$headers = "From: info@mailserver.com\r\n";
	$headers.= "Reply-To: info@mailserver.com\r\n";
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
function sendMailActivate($uid,$name,$email){
	$file=file("mail_activate_template.html");
	$aid=make_token($uid);
	$content=array();
	foreach ($file as $line_num => $line) {			
				$line=str_ireplace("{name}",$name,$line);
				$line=str_ireplace("{uid}",$uid,$line);
				$line=str_ireplace("{aid}",$aid,$line);
				$content[]=$line;
				}
				return sendmail($email,"New Registration at OnlineSubmission.jgsee.org",$content);
}
//end function
switch($_POST['mode']):
	case 'checkUsername':
		$strsql="SELECT * FROM personal WHERE username='".$_POST['username']."'";
		echo count($database->query($strsql));
	break;
	case 'selectCountry':
		$strsql="SELECT * FROM country";
		$database->showDataAsJson($strsql);
	break;
	case 'add':
		if(isset($_POST['role2']) && !empty($_POST['role2'])){
		$requestRole='1';
		}else{
		$requestRole='0';
		}
		$strsql="INSERT INTO personal(
		 role
		,requestRole
		,title
		,firstName
		,middleName
		,lastName
		,highestEducation
		,primaryPhone
		,secoundaryPhone
		,mobilePhone
		,fax
		,primaryEmail
		,secoundaryEmail
		,academicPosition
		,institution
		,faculty
		,department
		,address
		,city
		,state
		,postCode
		,country
		,topic
		,subTopic
		,username
		,password
		,registerDate
		,lastUpdate
		,lastAccess
		,status
		)VALUES(
		 '1'
		,'".$requestRole."' 
		,'".$_POST['title']."'
		,'".$_POST['firstName']."'
		,'".$_POST['middleName']."'
		,'".$_POST['lastName']."'
		,'".$_POST['highestEducation']."'
		,'".$_POST['primaryPhone']."'
		,'".$_POST['secoundaryPhone']."'
		,'".$_POST['mobilePhone']."'
		,'".$_POST['fax']."'
		,'".$_POST['primaryEmail']."'
		,'".$_POST['secoundaryEmail']."'
		,'".$_POST['academicPosition']."'
		,'".$_POST['institution']."'
		,'".$_POST['faculty']."'
		,'".$_POST['department']."'
		,'".$_POST['address']."'
		,'".$_POST['city']."'
		,'".$_POST['state']."'
		,'".$_POST['postCode']."'
		,'".$_POST['country']."'
		,'".$_POST['topic_input']."'
		,'".$_POST['subTopic']."'
		,'".$_POST['username']."'
		,MD5('".$_POST['password']."')
		,NOW()
		,NOW()
		,NOW()
		,'".(isset($_POST['status'])?$_POST['status']:0)."'
		)";
		
		if($database->execute($strsql)){
		$strsql="SELECT TABLE_ROWS as uid FROM information_schema.tables WHERE table_name='personal' AND table_schema = 'jgsee_onlinesubmission';";
		$data=$database->query($strsql);
		
		$uid=$data[0]['uid'];
		$name=$_POST['firstName']." ".$_POST['middleName']." ".$_POST['lastName'];
		$email=$_POST['primaryEmail'];
		sendMailActivate($uid,$name,$email);		
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
<script src="js/easing.js" type="text/javascript"></script>
<script src="js/jquery.scrollTo-1.4.2-min.js" type="text/javascript"></script>
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
<link href="css/kendo.default.min.css" rel="stylesheet" />
<script src="js/kendo/kendo.all.min.js"></script>
<!--ENDS Kendo UI-->
<!--JQUERY FORM-->
<script src="js/jquery.form.min.js" type="text/javascript"></script>
<!--ENDS JQUERY FORM-->

<script type="text/javascript">
<!--
jQuery.fn.reset = function(fn) {
return fn ? this.bind("reset", fn) : this.trigger("reset");
};

$(function() {		
	script.initial();
	script.validation();
	script.eventhandle();	
});
var script= new function() {
	var datastring=new Object();
	var mode='';
	var validator = $("#registerForm");
	var status=$('#error');
	this.initial=function(){
		$('#error').hide();
		$("#title").kendoComboBox();
		$("#academicPosition").kendoComboBox();
		$("#topic").kendoMultiSelect();
		$("#country").kendoDropDownList();		
	}// end initial
	
	this.eventhandle=function(){
		$("#regis").click(function() {
		mode='add';
		$('#topic_input').val($("#topic").data("kendoMultiSelect").value());
            if (validator.validate()) {
                status.hide();
                script.register();
            } else {
                status.html("Oops! There is invalid data in the form.").show();
            }
        });
	}//end eventhandle	
	this.validation=function(){
	validator = $('#registerForm').kendoValidator({
		rules: {
		          verifyPasswords: function(input){
		             var ret = true;
		             if (input.is('#confirmPassword')) {
		                 ret = input.val() === $('#password').val();
		             }
		             return ret;
		          },
		          verifyUsername:function(input){
		          	var ret = true;
		          	if(input.is('#username')){
		          		var cdata=ajax('register.php',({mode:'checkUsername',username:input.val()}),false);
		          		if($.trim(cdata)!='0')ret=false;
		          	}
		          	return ret;
		          },
		          requestUsername:function(input){
		          	var ret = true;
		          	if(input.is('#username')){
		          		ret=$.trim(input.val()) !== "";
		          	}
		          	return ret;
		          }
		      },
		messages: {
			verifyPasswords: "Passwords do not match!",
			verifyUsername: "This username is already taken!",
			requestUsername:"Enter your username"
		}
		}).data("kendoValidator"),status = $('#error');	
	}//end validator
	this.register=function(){
	$('#loading').show();
	var options = {
				success:function(response) {
				//console.log(response);
					$('#loading').hide();
					if($.trim(response)=='true'){
			            if(mode=='add'){
			            alert("Registered.\rWe have send you an e-mail for Activating instructions, \rPlease activate your account before using our service.\r You could find this e-mail from your primary e-mail inbox.");
			            }else{
			            alert("Update complete.");
			            }
			            _Redirect('index.php');
			            $('#registerForm')[0].reset();
		            }else{
		            	if(mode=='add'){
			            alert("Cannot register!");
			            }else{
			            alert("Cannot update!");
			            }
		            } 
			    }
			};
			$("#registerForm").ajaxSubmit(options);
	}//end register
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
			<!-- column (left)-->
			<div>
				<!-- form -->
				<div class="box">
					<h2>Register</h2>
					<form id="registerForm" name="registerForm" action="register.php" method="post">
						<input id="txtid" name="id" type="hidden" />
						<input id="txtmode" name="mode" type="hidden" value="add" />
						<fieldset>
						<legend>Role</legend>
						<div>
							<label>Available as Author :</label>
							<input id="role1" checked="checked" class="input" name="role1" title="Request role as author." type="checkbox" value="1">
						</div>
						<div>
							<label>Available as Referee :</label>
							<input id="role2" class="input" name="role2" title="Request role as referee ." type="checkbox" value="2">
						</div>
						</fieldset> <fieldset>
						<legend>Personal information</legend>
							<div>
								<label>Title :</label>
								<select id="title" name="title" required="" title="Enter your title.">
								<option selected="" value="Mr.">Mr.</option>
								<option value="Ms.">Ms.</option>
								<option value="Mrs.">Mrs.</option>
								</select>*
								<span class="k-invalid-msg" data-for="title"></span>
							</div>							
							<div>
								<label>First name :</label>
								<input id="firstName" class="input" name="firstName" required="" title="Enter your first name" type="text">*
								<span class="k-invalid-msg" data-for="firstName">
								</span>
							</div>
							<div>
								<label>Middle name :</label>
								<input id="middleName" class="input" name="middleName" title="Enter your middle name." type="text">
							</div>
							<div>
								<label>Last name :</label>
								<input id="lastName" class="input" name="lastName" required="" title="Enter your last name" type="text">*
								<span class="k-invalid-msg" data-for="lastName">
								</span></div>
							<div>
								<label>Highest education :</label>
								<input id="highestEducation" class="input" name="highestEducation" required="" title="Enter your highest education" type="text">*
								<span class="k-invalid-msg" data-for="highestEducation">
								</span></div>
							<div>
								<label>Primary phone :</label>
								<input id="primaryPhone" class="input" name="primaryPhone" required="" title="Enter your primary phone" type="text">*
								<span class="k-invalid-msg" data-for="primaryPhone">
								</span></div>
							<div>
								<label>Secoundary phone :</label>
								<input id="secoundaryPhone" class="input" name="secoundaryPhone" title="Enter your secoundary phone." type="text"></div>
							<div>
								<label>Mobile phone :</label>
								<input id="mobilePhone" class="input" name="mobilePhone" title="Enter your mobile phone." type="text"></div>
							<div>
								<label>Fax :</label>
								<input id="fax" class="input" name="fax" title="Enter your fax." type="text"></div>
							<div>
								<label>Primary E-mail :</label>
								<input id="primaryEmail" class="input" data-email-msg="Email format is not valid" name="primaryEmail" required="" type="email" validationmessage="Enter your primary e-mail">*
								<span class="k-invalid-msg" data-for="primaryEmail">
								</span></div>
							<div>
								<label>Secoundary E-mail :</label>
								<input id="secoundaryEmail" class="input" data-email-msg="Email format is not valid" name="secoundaryEmail" title="Enter your secoundary e-mail." type="email">
							</div>
						
						</fieldset> <fieldset>
						<legend>Affiliation information</legend>
						<div>
							<label>Academic position :</label>
							<select id="academicPosition" name="academicPosition" required="" title="Enter your Academic Position">
							<option value="Dr.">Dr.</option>
							<option value="Prof.">Prof.</option>
							<option value="Prof. Dr.">Prof. Dr.</option>
							</select>*
							<span class="k-invalid-msg" data-for="academicPosition">
							</span></div>
						<div>
							<label>Institution \ Company :</label>
							<input id="institution" class="input" name="institution" required="" title="Enter your institution \ company" type="text">*
							<span class="k-invalid-msg" data-for="institution">
							</span></div>
						<div>
							<label>Faculty \ Division :</label>
							<input id="faculty" class="input" name="faculty" title="Enter your faculty \ division." type="text">
						</div>
						<div>
							<label>Department :</label>
							<input id="department" class="input" name="department" title="Enter your department." type="text">
						</div>
						<div>
							<label>Address :</label>
							<input id="address" class="input" name="address" required="" title="Enter your address" type="text">*
							<span class="k-invalid-msg" data-for="address">
							</span></div>
						<div>
							<label>City \ Amphur :</label>
							<input id="city" class="input" name="city" required="" title="Enter your city \ amphur" type="text">*
							<span class="k-invalid-msg" data-for="city"></span>
						</div>
						<div>
							<label>State \ Province :</label>
							<input id="state" class="input" name="state" required="" title="Enter your state \ province" type="text">*
							<span class="k-invalid-msg" data-for="state"></span>
						</div>
						<div>
							<label>Postel code :</label>
							<input id="postCode" class="input" name="postCode" required="" title="Enter your postel code" type="text">*
							<span class="k-invalid-msg" data-for="postCode">
							</span></div>
						<div>
							<label>Country :</label>
							<select name="country" id="country" title="Select your country">
						<?php $strsql="SELECT * FROM country";
						$country=$database->query($strsql);
						foreach($country as $item):
						?>
						<option value="<?php echo $item['id'];?>"><?php echo $item['name'];?></option>
						<?php endforeach;?>
						</select>*
							<span class="k-invalid-msg" data-for="country">
							</span></div>
						</fieldset> <fieldset>
						<legend>Topic</legend>
						<div style="margin-left: 224px; width: 172px;">
						<input id="topic_input" name="topic_input" type="hidden">
							<select id="topic" multiple="multiple" name="topic" required="" title="Select topic">
							<option>Steven White</option>
							<option>Nancy King</option>
							<option>Anne King</option>
							<option>Nancy Davolio</option>
							<option>Robert Davolio</option>
							<option>Michael Leverling</option>
							<option>Andrew Callahan</option>
							<option>Michael Suyama</option>
							<option>Anne King</option>
							<option>Laura Peacock</option>
							<option>Robert Fuller</option>
							<option>Janet White</option>
							<option>Nancy Leverling</option>
							<option>Robert Buchanan</option>
							<option>Margaret Buchanan</option>
							<option>Andrew Fuller</option>
							<option>Anne Davolio</option>
							<option>Andrew Suyama</option>
							<option>Nige Buchanan</option>
							<option>Laura Fuller</option>
							</select>*							  
						<span class="k-invalid-msg" data-for="topic"></span>Allowed multiple topics.
						</div>
						<div>
							<label>Sub Topic :</label>
							<textarea id="subTopic" class="input" cols="20" name="subTopic" rows="3"></textarea>
						</div>
						</fieldset> <fieldset>
						<legend>Login</legend>
						<div>
							<label>Username</label>
							<input id="username" class="input" name="username" type="text">*
							<span class="k-invalid-msg" data-for="username">
							</span></div>
						<div>
							<label>Password</label>
							<input id="password" class="input" name="password" required="" title="Enter your password" type="password">*
							<span class="k-invalid-msg" data-for="password">
							</span></div>
						<div>
							<label>Confirm password</label>
							<input id="confirmPassword" class="input" name="confirmPassword" type="password">
							<span class="k-invalid-msg" data-for="confirmPassword">
							</span></div>
						</fieldset>
						<div class="clear" style="height: 10px">
						</div>
						<input id="regis" class="k-button" name="regis" type="button" value="Regis" />
						<input id="reset" class="k-button" name="reset" type="reset" value="Clear" />
						<img alt="" src="img/loading.gif" id="loading"  style="vertical-align:middle;display:none;" >
						<p id="error" class="warning">Message</p>
					</form>
				</div>
				<!-- ENDS form --></div>
			<!-- ENDS column --></div>
		<!-- ENDS content --></div>
	<!-- ENDS wrapper-main --></div>
<!-- ENDS MAIN -->
<!-- Bottom --><?php include_once('bottom.php'); ?>
<!-- ENDS Bottom -->

</body>

</html>
