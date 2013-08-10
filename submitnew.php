<?php
session_start();
require_once('MySQL.class.php');
$database= new database();
if(!isset($_SESSION['personal'])||empty($_SESSION['personal']))header("location:index.php");
/*SERVER CODE---------------------------------------------------------------------------*/
$personal=$_SESSION['personal'];
if(isset($_POST) && !empty($_POST)):
/*START*/
switch($_POST['mode']):
	case 'update':
	if(isset($_POST['role2']) && !empty($_POST['role2'])){
		$requestRole='1';
		}else{
		$requestRole='0';
		}
		$strsql="
		UPDATE personal SET 
		requestRole='".$requestRole."'
		,title='".$_POST['title']."'
		,firstName='".$_POST['firstName']."'
		,middleName='".$_POST['middleName']."'
		,lastName='".$_POST['lastName']."'
		,highestEducation='".$_POST['highestEducation']."'
		,primaryPhone='".$_POST['primaryPhone']."'
		,secoundaryPhone='".$_POST['secoundaryPhone']."'
		,mobilePhone='".$_POST['mobilePhone']."'
		,fax='".$_POST['fax']."'
		,primaryEmail='".$_POST['primaryEmail']."'
		,secoundaryEmail='".$_POST['secoundaryEmail']."'
		,academicPosition='".$_POST['academicPosition']."'
		,institution='".$_POST['institution']."'
		,faculty='".$_POST['faculty']."'
		,department='".$_POST['department']."'
		,address='".$_POST['address']."'
		,city='".$_POST['city']."'
		,state='".$_POST['state']."'
		,postCode='".$_POST['postCode']."'
		,country='".$_POST['country']."'
		,topic='".$_POST['topic_input']."'
		,subTopic='".$_POST['subTopic']."'		
		,lastUpdate=NOW()
		,lastAccess=NOW()		
		";
		if(!empty($_POST['password'])){
		$strsql.=",password=MD5('".$_POST['password']."')";
		}
		$strsql.=" WHERE id='".$_POST['id']."'";
		if($database->execute($strsql)){
		$strsql="SELECT * FROM personal WHERE id='".$_POST['id']."'";
		$data=$database->query($strsql);
		$_SESSION['personal']=$data;	
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
<link href="css/kendo.default.min.css" rel="stylesheet" />
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
	var formAddAuthor;
	this.initial=function(){
    	$('#error').hide();
		$('#menuscriptType').kendoDropDownList();
		$('#menuscriptTopic').kendoDropDownList();
		$('#abstract').kendoEditor({tools: [
                "subscript",
                "superscript",
                "insertUnorderedList",
                "insertOrderedList",
            ]});
        $('#noteToEditor').kendoEditor();
        formAddAuthor = $("#formAddAuthor");
        if (!formAddAuthor.data("kendoWindow")) {
            formAddAuthor.kendoWindow({
            	width: "600px",
            	height:"300px",
                title: "Add author",
                modal:true,
                animation:false,
                visible:false
            });
        }
		$("#authorList").kendoGrid({
	                        dataSource: {
	                            data: null,
	                            pageSize: 10
	                        },
	                        resizable: true,
	                        reorderable: true,
	                        sortable: true,
	                        selectable: "multiple",
	                        pageable:true,
	                        columnMenu: true,
	                        columns: [
	                        	{ title: "Order"},
	                        	{ title: "Author's name" },
	                            { title: "Status" },
	                            { title: "Degree"},
	                            { title: "Institution" },
	                            { title: "Faculty" },
	                            { title: "Department" },
	                            { title: "Action",command: [
	                            	{
								         name: "edit",
								         click: function(e) {
								            // command button click handler
								         }
							        },
							        { 	name: "destroy",
								        click: function(e){
								        	// command button click handler
								        }
							        }
							     ]}
	                        ],
	                        toolbar: [
	                        	{ template: '<a class="k-button" href="javascript:script.btnAddAuthorList_click();" >Add author</a>'}
	                        ]
	    });
	    $("#files").kendoUpload();
	    $("#menuscriptFileList").kendoGrid({
	                        dataSource: {
	                            data: null,
	                            pageSize: 10
	                        },
	                        resizable: true,
	                        reorderable: true,
	                        sortable: true,
	                        selectable: "multiple",
	                        pageable:true,
	                        columnMenu: true,
	                        columns: [
	                        	{ title: "Order"},
	                        	{ title: "File name" },
	                            { title: "Content type" },
	                            { title: "Size"},
	                            { title: "Date time" },
	                            { title: "Action",command: [
	                            	{
								         name: "edit",
								         click: function(e) {
								            // command button click handler
								         }
							        },
							        { 	name: "destroy",
								        click: function(e){
								        	// command button click handler
								        }
							        }
							     ]}
	                        ],
	                        toolbar: [
	                        	{ template: '<a class="k-button" href="javascript:script.btnAddAuthorList_click();" >Add author</a>'}
	                        ]
	    });
	}//end initial
	this.btnAddAuthorList_click=function (){
		formAddAuthor.data("kendoWindow").center().open();
	}
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
					<input id="txtid" name="id" type="hidden" value="<?php echo $personal[0]['id'];?>" />
					<input id="txtmode" name="mode" type="hidden" value="update" />
					<fieldset class="k-content">
					<legend style="color: blue">Submis new menuscript</legend>
					<fieldset>
					<legend>Cover page information</legend>
					<label>Menuscript Title :</label>
					<input id="menuscriptTitle" class="input" name="menuscriptTitle" required="" style="width: 500px" title="Enter your menuscript title" type="text">*
					<span class="k-invalid-msg" data-for="menuscriptTitle">
					</span>
					<div>
						<label>Type :</label>
						<select id="menuscriptType" name="menuscriptType" required="" title="Select menuscript type.">
						<?php $strsql="SELECT * FROM menuscript_type";
							$menuscript_type=$database->query($strsql);
							foreach($menuscript_type as $item):
							?>
						<option value="<?php echo $item['id'];?>"><?php echo $item['type'];?>
						</option>
						<?php endforeach;?></select>*
						<span class="k-invalid-msg" data-for="menuscriptType">
						</span></div>
					<div>
						<label>Topic :</label>
						<select id="menuscriptTopic" name="menuscriptTopic" required="" title="Select menuscript topic.">
						<?php $strsql="SELECT * FROM menuscript_topic";
							$menuscript_type=$database->query($strsql);
							foreach($menuscript_type as $item):
							?>
						<option value="<?php echo $item['id'];?>"><?php echo $item['topic'];?>
						</option>
						<?php endforeach;?></select>*
						<span class="k-invalid-msg" data-for="menuscriptTopic">
						</span></div>
					<div>
						<label>Abstract* :</label>
						<textarea id="abstract" name="abstract" required=""  title="Enter abstract"></textarea>
						<span class="k-invalid-msg" data-for="abstract"></span>
					</div>
					<div>
						<label>keywords :</label>
						<input id="keywords" class="input" name="keywords" required="" style="width: 500px" title="Enter keywords" type="text">*
						<span class="k-invalid-msg" data-for="keywords"></span>
						<div style="margin-left: 224px">
							Keywords separeted by commar (,) and limited 5 keywords.</div>
					</div>
					<div>
						<label>Note to editor :</label>
						<textarea id="noteToEditor"  name="noteToEditor"></textarea>
						<div style="margin-left: 224px">
							Note to Editor limited 500 characters. You can nominate 
							uo to 5 referees. Please inclused email addresses and 
							affiliations.</div>
					</div>
					</fieldset> <fieldset>
					<legend>Authors Information</legend>
					<div id="formAddAuthor">
						<div>
							<label>Corresponding :</label>
							<input id="corresponding" name="corresponding" type="checkbox" value="1">
						</div>
						<div>
							<label>Title :</label>
							<select id="title" name="title" required="" title="Enter title.">
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
							</span></div>
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
							<label>Primary E-mail :</label>
							<input id="primaryEmail" class="input" data-email-msg="Email format is not valid" name="primaryEmail" required="" type="email" validationmessage="Enter your primary e-mail">*
							<span class="k-invalid-msg" data-for="primaryEmail">
							</span></div>
						<div>
							<label>Confirm E-mail :</label>
							<input id="confirmEmail" class="input" data-email-msg="Email format is not valid" name="confirmEmail" type="email">
							<span class="k-invalid-msg" data-for="confirmEmail">
							</span></div>
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
						<div class="clear" style="height: 10px">
						</div>
						<input id="btnAddAuthor" class="k-button" name="btnAddAuthor" type="button" value="Add author" />
					</div>
					<div id="authorList">
					</div>
					</fieldset> 
					<fieldset>
					<legend>Menuscript file</legend>
					
					<div id="menuscriptFileList">
					</div>
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
