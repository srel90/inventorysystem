<?php
session_start();
require_once('MySQL.class.php');
$database= new database();
if(!isset($_SESSION['users'])||empty($_SESSION['users'])){header("location:index.php");}else{
$role=$database->checkrole($_SESSION['users'][0]['typeID'],$_SERVER['PHP_SELF']);
if($role==0)header("location:nopermission.php");}
/*SERVER CODE---------------------------------------------------------------------------*/
$personal=$_SESSION['users'];
if(isset($_GET) && !empty($_GET)):
switch($_GET['mode']):
	case 'selectAllUser':
	$strsql="SELECT u.*,ut.type FROM users u LEFT OUTER JOIN usertype ut ON u.typeID=ut.typeID";
	$database->showDataAsJson($strsql);
	break;
endswitch;
exit();	
endif;
if(isset($_POST) && !empty($_POST)):
/*START*/
switch($_POST['mode']):
	case 'insert':
		$strsql="INSERT INTO users(
		code
		,IDCard
		,firstName
		,lastName
		,address
		,phone
		,mobile
		,email
		,position
		,registerDate
		,lastAccess
		,username
		,password
		,status
		,typeID
		)VALUES(
		'".$_POST['code']."'
		,'".$_POST['IDCard']."'
		,'".$_POST['firstName']."'
		,'".$_POST['lastName']."'
		,'".$_POST['address']."'
		,'".$_POST['phone']."'
		,'".$_POST['mobile']."'
		,'".$_POST['email']."'
		,'".$_POST['position']."'
		,'".$_POST['registerDate']."'
		,NOW()
		,'".$_POST['username']."'
		,MD5('".$_POST['password']."')
		,'".$_POST['status']."'
		,'".$_POST['typeID']."'
		)";
		if($database->execute($strsql)){
		echo 'true';
		}else{
		echo 'false';
		}
	break;
	case 'update':
		$strsql="
		UPDATE users SET 
		IDCard='".$_POST['IDCard']."'
		,firstName='".$_POST['firstName']."'
		,lastName='".$_POST['lastName']."'
		,address='".$_POST['address']."'
		,phone='".$_POST['phone']."'
		,mobile='".$_POST['mobile']."'
		,email='".$_POST['email']."'
		,position='".$_POST['position']."'
		,registerDate='".$_POST['registerDate']."'
		,status='".$_POST['status']."'
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
	case 'delete':
		$strsql="DELETE FROM users WHERE userID='".$_POST['userID']."'";	
		if($database->execute($strsql)){
		echo 'true';
		}else{
		echo 'false';
		}
	break;
	case 'getLastID':
		$strsql="SELECT AUTO_INCREMENT AS lastID FROM information_schema.tables WHERE table_name='users' AND table_schema = 'inventorysystem'";
		$data=$database->query($strsql);
		echo $data[0]['lastID'];
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
	$('#m2').addClass('current-menu-item');
	script.initial();
	script.validation();
	script.eventhandle();
	script.clearform();
});
var script= new function() {
	var validator = $("#profileForm");
	var status=$('#error');
	this.initial=function(){
    	$('#error').hide(); 
		$("#gridTable").kendoGrid({
	        dataSource: {
	        	transport: {read: "userprofile.php?mode=selectAllUser"},	            
	            dataType: "json",
	            autoSync: true,
	            pageSize: 5,
	            schema: {
				    data: "data",
				    total:"total"
				}	                        
			},	        
			filterable: true,
	        resizable: true,
	        reorderable: true,
	        groupable: true,
	        sortable: true,
	        columnMenu: true,
	        selectable: "multiple",
	        pageable: {
	            pageSizes: true
	        },
	        columns: [ 
	        	{field: "userID",title: "ID",width: 60},
	        	{field: "code",title: "Code"},
	        	{field: "username",title: "Username"},	        	
	        	{field: "IDCard",title: "ID Card"},
	        	{field: "firstName",title: "First name"},
	        	{field: "lastName",title: "Last name"},
	        	{field: "position",title: "Position"},
	        	{field: "type",title: "Type"},	        	
	        	{field: "lastAccess",title: "Last Access",format: "{0: yyyy-MM-dd HH:mm:ss}"},
	        	{field: "status", title:"Status",template:'#=status==1?"Active":"Inactive"#'}
	        	],
	        toolbar: [
	        	{
	        	template: '<a class="k-button" href="javascript:;" id="addNew"><img src="img/mono-icons/doc_plus.png" width="12px">Add new</a>'
	        	},
	        	{
	        	template: '<a class="k-button" href="javascript:;" id="edit"><img src="img/mono-icons/doc_edit.png" width="12px">Edit</a>'
	        	},
	        	{
	        	template: '<a class="k-button" href="javascript:;" id="delete"><img src="img/mono-icons/doc_delete.png" width="12px">Delete</a>'
	        	},
	        	{
	        	template: '<a class="k-button" href="javascript:;" id="cancel"><img src="img/mono-icons/undo.png" width="12px">Cancel</a>'
	        	}

				]
	    });
	    $('#typeID').kendoDropDownList();
	    $('#registerDate').kendoDatePicker({format: "yyyy-MM-dd"});    	                
	}//end initial
	this.eventhandle=function(){
		$("#addNew").click(function(){
		if($("#addNew").hasClass("k-state-disabled"))return;
				$("#addNew").addClass("k-state-disabled");
				$("#edit").addClass("k-state-disabled");
				$("#delete").addClass("k-state-disabled");			
				script.clearform();
				$('#mode').val('insert');				
	
		});
		$("#edit").click(function(){
		if($("#edit").hasClass("k-state-disabled"))return;
			var gridTable = $("#gridTable ").data("kendoGrid");
			var selectedItem = gridTable.dataItem(gridTable.select());
			if(selectedItem==null){alert('Please select record to edit.');return;}
				$("#addNew").addClass("k-state-disabled");
				$("#edit").addClass("k-state-disabled");
				$("#delete").addClass("k-state-disabled");								
				$('#mode').val('update');
				$('#userID').val(selectedItem.userID);
				$('#code').val(selectedItem.code);
				$('#typeID').data("kendoDropDownList").select(function(dataItem) {
				    return dataItem.value === selectedItem.typeID;
				});
				$('#firstName').val(selectedItem.firstName);
				$('#lastName').val(selectedItem.lastName);
				$('#IDCard').val(selectedItem.IDCard);
				$('#address').val(selectedItem.address);
				$('#phone').val(selectedItem.phone);
				$('#mobile').val(selectedItem.mobile);
				$('#email').val(selectedItem.email);
				$('#position').val(selectedItem.position);
				$('#registerDate').val(selectedItem.registerDate);
				$('#username').val(selectedItem.username);
				setRDOValue('status',selectedItem.status);			
		});
		$("#delete").click(function(){
		if($("#delete").hasClass("k-state-disabled"))return;
			var gridTable = $("#gridTable ").data("kendoGrid");
			var selectedItem = gridTable.dataItem(gridTable.select());
			if(selectedItem==null){alert('Please select record to delete.');return;}
			if(confirm('Do you want to delete this record?')){
				if(ajax('userprofile.php',({mode:'delete',userID:selectedItem.userID}),false)=='true'){
					script.clearform();
				}
			}							
		});		
		$("#cancel").click(function(){
			$("#addNew").removeClass("k-state-disabled");
			$("#edit").removeClass("k-state-disabled");
			$("#delete").removeClass("k-state-disabled");
			$('#mode').val('insert');
			
			script.clearform();
		});
		
		$("#save").click(function() {
			$("#addNew").removeClass("k-state-disabled");
			$("#edit").removeClass("k-state-disabled");
			$("#delete").removeClass("k-state-disabled");

            if (validator.validate()) {
                status.hide();
                script.save();
            } else {
                status.html("Oops! There is invalid data in the form.").show();
            }
        });
	}//end eventhandle
	this.validation=function(){
	validator = $('#scriptForm').kendoValidator({
		rules: {
		          requestIdCard: function(input){
		             var ret = true;
		             if (input.is('#IDCard')) {
		                 ret = input.val() !='';
		             }
		             return ret;
		          },
		          verifyIdCard: function(input){
		             var ret = true;
		             if (input.is('#IDCard')) {
		                 ret = chkIDCard(input.val());
		             }
		             return ret;
		          },
		          verifyPasswords: function(input){
		             var ret = true;
		             if (input.is('#confirmPassword')) {
		                 ret = input.val() === $('#password').val();
		             }
		             return ret;
		          }		          
		      },
		messages: {
			verifyIdCard: "ID Card is invalid!",
			requestIdCard:"Enter ID Card.",
			verifyPasswords: "Passwords do not match!"
		}
	}).data("kendoValidator"),status = $('#error');	
	}//end validator
	this.save=function(){
	$('#loading').show();
	var options = {
				success:function(response) {
				console.log(response);
					$('#loading').hide();
					if($.trim(response)=='true'){
			            alert("complete transection.");
			            script.clearform();
		            }else{
		            	alert("Cannot complete your transection!");
		            } 
			    }
			};
			$("#scriptForm").ajaxSubmit(options);
	}//end save
	this.clearform=function(){
		$("#gridTable").data("kendoGrid").clearSelection();
		$("#gridTable").data("kendoGrid").dataSource.read();
		$("#gridTable").data("kendoGrid").refresh();
		HTMLFormElement.prototype.reset.call($('#scriptForm')[0]);
		//$('#scriptForm')[0].reset();
		var lastID=ajax('userprofile.php',({mode:'getLastID'}),false);
		$('#userID').val(lastID);
		$('#code').val(pad(lastID,7));
	}//end clearForm

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
				<form id="scriptForm" action="userprofile.php" method="post" name="scriptForm">
					<input id="mode" name="mode" type="hidden" value="insert" /><?php include_once('profileheader.php'); ?>
					<fieldset class="k-content">
					<legend style="color: #37b2d1">Manage profile</legend>
					<fieldset id="fprofileList">
					<legend>Profile List</legend>
					<div id="gridTable">
					</div>
					</fieldset> <fieldset>
					<legend>Personal information</legend>
					<div>
					<div>
							<label>ID :</label>
							<input id="userID" class="input" name="userID" readonly title="Click add new to gen new ID" type="text" required="required">
							<span class="k-invalid-msg" data-for="userID">
							</span></div>

						<div>
							<label>Code :</label>
							<input id="code" class="input" name="code" readonly title="Click add new to gen new code"  type="text" required="required">
							<span class="k-invalid-msg" data-for="code">
							</span>
						</div>
						<div>
							<label>User Type :</label>
							<select id="typeID" name="typeID" required="required" title="Select user type">
							<option value="">Please Select</option>
							<?php 
								$strsql="SELECT * FROM usertype WHERE status='1'";
								$typeID=$database->query($strsql);
								foreach($typeID as $item):
							?>
							<option value="<?php echo $item['typeID'];?>"><?php echo $item['type'];?>
							</option>
							<?php endforeach;?></select>*
							<span class="k-invalid-msg" data-for="typeID">
							</span></div>
						<div>
							<label>First name :</label>
							<input id="firstName" class="input" name="firstName" required="required" title="Enter your first name" type="text">*
							<span class="k-invalid-msg" data-for="firstName">
							</span></div>
						<div>
							<label>Last name :</label>
							<input id="lastName" class="input" name="lastName" required="required" title="Enter your last name" type="text">*
							<span class="k-invalid-msg" data-for="lastName">
							</span></div>
						<div>
							<label>ID Card :</label>
							<input id="IDCard" class="input" name="IDCard"  type="text">*
							<span class="k-invalid-msg" data-for="IDCard">
							</span></div>
						<div>
							<label>Address :</label>
							<textarea id="address" class="input" cols="20" name="address" rows="2"></textarea>
							<span class="k-invalid-msg" data-for="address">
							</span></div>
						<div>
							<label>Phone :</label>
							<input id="phone" class="input" name="phone" title="Enter your phone number" type="text">
							<span class="k-invalid-msg" data-for="phone"></span>
						</div>
						<div>
							<label>Mobile :</label>
							<input id="mobile" class="input" name="mobile" title="Enter mobile number" type="text">
							<span class="k-invalid-msg" data-for="mobile">
							</span></div>
						<div>
							<label>E-mail :</label>
							<input id="email" class="input" data-email-msg="Email format is not valid" name="email" required="required" type="email" validationmessage="Enter your e-mail">*
							<span class="k-invalid-msg" data-for="email"></span>
						</div>
						<div>
							<label>Position :</label>
							<input id="position" class="input" name="position" title="Enter position." type="text" required="required">*
							<span class="k-invalid-msg" data-for="position">
							</span></div>
						<div>
							<label>Register Date :</label>
							<input id="registerDate" required="required"  name="registerDate" title="Enter register date."/>*
							<span class="k-invalid-msg" data-for="registerDate"></span>
						</div>	
						<div>
							<label>Username</label>
							<input id="username" class="input" name="username" required="required" type="text">*
							<span class="k-invalid-msg" data-for="username">
							</span></div>
						<div>
							<label>Password</label>
							<input id="password" class="input" name="password" title="Enter password" type="password" required="required">*
							<span class="k-invalid-msg" data-for="password">
							</span></div>
						<div>
							<label>Confirm password</label>
							<input id="confirmPassword" class="input" name="confirmPassword" type="password">
							<span class="k-invalid-msg" data-for="confirmPassword">
							</span></div>
						<div>
							<label>Status :</label> 
							<input id="status0" name="status" title="Inctive" type="radio" value="0">Inactive
							<input id="status1" checked="checked" name="status" title="Active" type="radio" value="1">Active
						</div>
					</div>
					<div class="clear" style="height: 10px">
					</div>
					<input id="save" class="k-button" name="save" type="button" value="Save" />
					<input id="reset" class="k-button" name="reset" type="reset" value="Reset" />
					<img id="loading" alt="" src="img/loading.gif" style="vertical-align: middle; display: none;">
					<p id="error" class="warning">Message</p>
					</fieldset> </fieldset></form>
			</div>
		</div>
		<!-- ENDS content --></div>
	<!-- ENDS wrapper-main --></div>
<!-- ENDS MAIN -->
<!-- Bottom --><?php include_once('bottom.php'); ?>
<!-- ENDS Bottom -->

</body>

</html>
