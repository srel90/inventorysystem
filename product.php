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

	
endswitch;
exit();	
endif;
if(isset($_POST) && !empty($_POST)):
/*START*/
function generateRandomString($randomString,$length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}
switch($_POST['mode']):
	case 'selectAllProduct':
		$strsql="SELECT p.*,c.category,t.type FROM products p LEFT OUTER JOIN productcategory c ON p.categoryID=c.categoryID LEFT OUTER JOIN producttype t ON p.typeID=t.typeID";
		$database->showDataAsJson($strsql);
	break;
	case 'selectAllCategory':
		$strsql="SELECT * FROM productcategory WHERE status='1'";
		$database->showDataAsJson($strsql);
	break;
	case 'selectAllTypeByCategoryID':
		$strsql="SELECT * FROM producttype WHERE categoryID='".$_POST['categoryID']."' AND status='1'";
		$database->showDataAsJson($strsql);
	break;
		case 'selectAllType':
		$strsql="SELECT * FROM producttype WHERE status='1'";
		$database->showDataAsJson($strsql);
	break;

	case 'insert':
		require_once('resizeimage.class.php');
		$strsql="SELECT AUTO_INCREMENT AS lastID FROM information_schema.tables WHERE table_name='products' AND table_schema = 'inventorysystem'";
		$lastid=$database->query($strsql);
		if(!file_exists($_SERVER['DOCUMENT_ROOT'].'/inventorysystem/images')){
			mkdir($_SERVER['DOCUMENT_ROOT'].'/inventorysystem/images',0777);
		}
		$targetFolder = $_SERVER['DOCUMENT_ROOT'].'/inventorysystem/images';
		$image='';
		if(!empty($_FILES['image'])){
			$tempFile = $_FILES['image']['tmp_name'];
			
			$filegen=generateRandomString($_FILES['image']['name']);			
			$targetFile=$targetFolder.'/'.$lastid[0]['lastID'].'_'.$filegen.$_FILES['image']['name'];
						
			move_uploaded_file($tempFile,$targetFile);
			
			$resizeObj = new resize($targetFile);
			$resizeObj -> resizeImage(640, 480, 'landscape');
			$resizeObj -> saveImage($targetFile, 80);
			$image=$lastid[0]['lastID'].'_'.$filegen.$_FILES['image']['name'];			
		}
		$strsql="INSERT INTO products (
		code
		,categoryID
		,typeID
		,name
		,description
		,image
		,color
		,size
		,listOfMaterial
		,price
		,cost
		,unit
		,quantity
		,pointOfOrder
		,supplier
		,contactPerson
		,contactNumber
		,lastUpdate
		,updateBy
		,status
		)VALUES(
		'".$_POST['code']."'
		,'".$_POST['categoryID']."'
		,'".$_POST['typeID']."'
		,'".$_POST['name']."'
		,'".$_POST['description']."'
		,'".$image."'
		,'".$_POST['color']."'
		,'".$_POST['size']."'
		,'".$_POST['listOfMaterial']."'
		,'".$_POST['price']."'
		,'".$_POST['cost']."'
		,'".$_POST['unit']."'
		,'".$_POST['quantity']."'
		,'".$_POST['pointOfOrder']."'
		,'".$_POST['supplier']."'
		,'".$_POST['contactPerson']."'
		,'".$_POST['contactNumber']."'
		,NOW()
		,'".$_SESSION['users'][0]['userID']."'
		,'".$_POST['status']."'
		)";
		if($database->execute($strsql)){
		echo 'true';
		}else{
		echo 'false';
		}
	break;
	case 'update':
		require_once('resizeimage.class.php');
		$strsql="SELECT * FROM products WHERE productID = '".$_POST['productID']."'";
		$data=$database->query($strsql);
		if(!file_exists($_SERVER['DOCUMENT_ROOT'].'/inventorysystem/images')){
			mkdir($_SERVER['DOCUMENT_ROOT'].'/inventorysystem/images',0777);
		}
		$targetFolder = $_SERVER['DOCUMENT_ROOT'].'/inventorysystem/images';
		$image=$data[0]['image'];
		if(!empty($_FILES['image'])){
			if(!empty($image)){
			$t=$targetFolder.'/'.$image;
			unset($t);
			}
			$tempFile = $_FILES['image']['tmp_name'];
			
			$filegen=generateRandomString($_FILES['image']['name']);			
			$targetFile=$targetFolder.'/'.$data[0]['productID'].'_'.$filegen.$_FILES['image']['name'];
						
			move_uploaded_file($tempFile,$targetFile);
			
			$resizeObj = new resize($targetFile);
			$resizeObj -> resizeImage(640, 480, 'landscape');
			$resizeObj -> saveImage($targetFile, 80);
			$image=$data[0]['productID'].'_'.$filegen.$_FILES['image']['name'];			
		}

		$strsql="
		UPDATE products SET 
		categoryID='".$_POST['categoryID']."'
		,typeID='".$_POST['typeID']."'
		,name='".$_POST['name']."'
		,image='".$image."'
		,color='".$_POST['color']."'
		,size='".$_POST['size']."'
		,listOfMaterial='".$_POST['listOfMaterial']."'
		,price='".$_POST['price']."'
		,cost='".$_POST['cost']."'
		,unit='".$_POST['unit']."'
		,quantity='".$_POST['quantity']."'
		,pointOfOrder='".$_POST['pointOfOrder']."'
		,supplier='".$_POST['supplier']."'
		,contactPerson='".$_POST['contactPerson']."'
		,contactNumber='".$_POST['contactNumber']."'
		,lastUpdate=NOW()
		,updateBy='".$_SESSION['users'][0]['userID']."'
		,status='".$_POST['status']."'
		WHERE productID='".$_POST['productID']."'
		";
		if($database->execute($strsql)){
		echo 'true';
		}else{
		echo 'false';
		}
	break;	
	case 'delete':
		$strsql="DELETE FROM products WHERE productID='".$_POST['productID']."'";	
		if($database->execute($strsql)){
		echo 'true';
		}else{
		echo 'false';
		}
	break;
	case 'getLastID':
		$strsql="SELECT AUTO_INCREMENT AS lastID FROM information_schema.tables WHERE table_name='products' AND table_schema = 'inventorysystem'";
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
	$('#m3').addClass('current-menu-item');
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
	        	transport: {
		        	read: {
		        		dataType:"json",
		        		type:"POST",
		        		data:({mode:'selectAllProduct'}),
		        		url:'product.php'
		        		}
	        	},	            
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
	        	{field: "productID",title: "ID",width: 60,type: "number"},
	        	{field: "code",title: "Code"},
	        	{field: "category",title: "Category"},	        	
	        	{field: "type",title: "Type"},
	        	{field: "name",title: "Name"},
	        	{field: "price",title: "Price",format:"{0:#.00}",type: "number"},
	        	{field: "cost",title: "Cost",format:"{0:#.00}",type: "number"},
	        	{field: "unit",title: "Unit"},
	        	{field: "quantity",title: "Quantity",format:"{0:#}",type: "number"},
	        	{field: "pointOfOrder",title: "Point Of Order",format:"{0:#}",type: "number"},	        	
	        	{field: "lastUpdate",title: "Last Update",format: "{0: yyyy-MM-dd HH:mm:ss}"},
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
	    $('#categoryID,#typeID').kendoDropDownList(); 
	    $("#description").kendoEditor({
            tools: [
                "bold",
                "italic",
                "underline",
                "strikethrough",
                "justifyLeft",
                "justifyCenter",
                "justifyRight",
                "justifyFull",
                "insertUnorderedList",
                "insertOrderedList",
                "indent",
                "outdent",
                "createLink",
                "unlink",
                "insertImage",
                "subscript",
                "superscript",
                "createTable",
                "addRowAbove",
                "addRowBelow",
                "addColumnLeft",
                "addColumnRight",
                "deleteRow",
                "deleteColumn",
                "viewHtml",
                "formatting",
                "fontName",
                "fontSize",
                "foreColor",
                "backColor"
            ]
        });  
        $("#price,#cost").kendoNumericTextBox({format:"#.00"});	
        $("#quantity,#pointOfOrder").kendoNumericTextBox({format:"#"}); 

        var categories = $("#categoryID").kendoDropDownList({
            optionLabel: "Select category...",
            dataTextField: "category",
            dataValueField: "categoryID",
            dataSource: {
                type: "json",
                transport: {
                    read: {
                    	dataType:"json",
                    	type:"POST",
                    	data:({mode:'selectAllCategory'}),
                    	url:'product.php'
                    }
                },
                schema: {
				    data: "data",
				    total:"total"
				}
            },
            change:function(){
            	$("#typeID").data("kendoDropDownList").dataSource.read({categoryID:this.value(),mode:'selectAllTypeByCategoryID'});
            }
        }).data("kendoDropDownList");
        
        var type = $("#typeID").kendoDropDownList({
            optionLabel: "Select type...",
            dataTextField: "type",
            dataValueField: "typeID", 
            dataSource: {
                type: "json",
                transport: {
                    read: {
                    	dataType:"json",
                    	type:"POST",
                    	data:({mode:'selectAllType'}),
                    	url:'product.php'
                    }
                },
                schema: {
				    data: "data",
				    total:"total"
				}
            }          
        }).data("kendoDropDownList");  
                    
	}//end initial
	this.eventhandle=function(){
		$('#image').change(function(event){
        	oFReader = new FileReader();
        	oFReader.readAsDataURL($(this)[0].files[0]);
        	oFReader.onload = function (oFREvent) {
        		$('#imgpreview').html('');
        		$('#imgpreview').append('<img style="width: 100px;" src="'+oFREvent.target.result+'" />');
        	};
        });
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
				
				$('#productID').val(selectedItem.productID);
				$('#code').val(selectedItem.code);
				$('#categoryID').data("kendoDropDownList").select(function(dataItem) {
				    return dataItem.categoryID=== selectedItem.categoryID;
				});
				$('#typeID').data("kendoDropDownList").select(function(dataItem) {
				    return dataItem.typeID=== selectedItem.typeID;
				});
				$('#name').val(selectedItem.name);
				$('#description').data("kendoEditor").value(selectedItem.description);
				$('#color').val(selectedItem.color);
				$('#size').val(selectedItem.size);
				$('#listOfMaterial').val(selectedItem.listOfMaterial);
				$('#price').data("kendoNumericTextBox").value(selectedItem.price);
				$('#cost').data("kendoNumericTextBox").value(selectedItem.cost);
				$('#unit').val(selectedItem.unit);
				$('#quantity').data("kendoNumericTextBox").value(selectedItem.quantity);
				$('#pointOfOrder').data("kendoNumericTextBox").value(selectedItem.pointOfOrder);
				$('#supplier').val(selectedItem.supplier);
				$('#contactPerson').val(selectedItem.contactPerson);
				$('#contactNumber').val(selectedItem.contactNumber);
				$('#imgpreview').append('<img style="width: 100px;" src="images/'+selectedItem.image+'" />');
				setRDOValue('status',selectedItem.status);			
		});
		$("#delete").click(function(){
		if($("#delete").hasClass("k-state-disabled"))return;
			var gridTable = $("#gridTable ").data("kendoGrid");
			var selectedItem = gridTable.dataItem(gridTable.select());
			if(selectedItem==null){alert('Please select record to delete.');return;}
			if(confirm('Do you want to delete this record?')){
				if(ajax('product.php',({mode:'delete',productID:selectedItem.productID}),false)=='true'){
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
	validator = $('#scriptForm').kendoValidator().data("kendoValidator"),status = $('#error');	
	}//end validator
	this.save=function(){
	$('#loading').show();
	var options = {
				success:function(response) {
				//console.log(response);
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
		var lastID=ajax('product.php',({mode:'getLastID'}),false);
		$('#productID').val(lastID);
		$('#code').val(pad(lastID,7));
		$('#description').data("kendoEditor").value("");
		$('#imgpreview').html("");
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
				<form id="scriptForm" action="product.php" method="post" name="scriptForm">
					<input id="mode" name="mode" type="hidden" value="insert" />
					<?php include_once('profileheader.php'); ?>
					<fieldset class="k-content">
					<legend style="color: #37b2d1">Manage product</legend>
					<fieldset id="fprofileList">
					<legend>Product List</legend>
					<div id="gridTable">
					</div>
					</fieldset> <fieldset>
					<legend>Product</legend>
					<div>
						<div>
							<label>ID :</label>
							<input id="productID" class="input" name="productID" readonly required="required" title="Click add new to gen new ID" type="text">
							<span class="k-invalid-msg" data-for="productID">
							</span></div>
						<div>
							<label>Code :</label>
							<input id="code" class="input" name="code" readonly required="required" title="Click add new to gen new code" type="text">
							<span class="k-invalid-msg" data-for="code"></span>
						</div>
						<div>
							<label>Product category :</label>
							<input id="categoryID" name="categoryID" required="required" title="Select category"/>*
							<span class="k-invalid-msg" data-for="categoryID">
							</span></div>
						<div>
							<label>Product type :</label>
							<input id="typeID" name="typeID" required="required" title="Select type"  />*
							<span class="k-invalid-msg" data-for="typeID">
							</span></div>
						<div>
							<label>Name :</label>
							<input id="name" class="input" name="name" required="required" title="Enter product name" type="text">*
							<span class="k-invalid-msg" data-for="name"></span>
						</div>
						<div>
							<label>Description :</label>
							<textarea id="description" cols="20" name="description" rows="2"></textarea>
							<span class="k-invalid-msg" data-for="description">
							</span></div>
						<div>
							<label>Image :</label>
							<input id="image" class="input" name="image" type="file" />
							<div id="imgpreview"><img src="" alt="" /></div>
						</div>
						<div>
							<label>Color :</label>
							<input id="color" class="input" name="color" title="Enter color" type="text">
							<span class="k-invalid-msg" data-for="color"></span>
						</div>
						<div>
							<label>Size :</label>
							<input id="size" class="input" name="size" title="Enter size" type="text">
							<span class="k-invalid-msg" data-for="size"></span>
						</div>
						<div>
							<label>List of materials :</label>
							<textarea id="listOfMaterial" name="listOfMaterial" class="input" cols="20" rows="2" title="Enter materials"></textarea>
							<span class="k-invalid-msg" data-for="listOfMaterial">
							</span></div>
						<div>
							<label>Price :</label>
							<input id="price" name="price" title="Enter price" type="number" type="text" value="0">
							<span class="k-invalid-msg" data-for="price"></span>
						</div>
						<div>
							<label>Cost :</label>
							<input id="cost" name="cost" title="Enter cost" type="number" type="text" value="0">
							<span class="k-invalid-msg" data-for="cost"></span>
						</div>
						<div>
							<label>Unit :</label>
							<input id="unit" class="input" name="unit" title="Enter unit." type="text">
							<span class="k-invalid-msg" data-for="unit"></span>
						</div>
						<div>
							<label>Quantity :</label>
							<input id="quantity" name="quantity" title="Enter quantity" type="number" type="text" value="0">
							<span class="k-invalid-msg" data-for="quantity">
							</span></div>
						<div>
							<label>Point of order :</label>
							<input id="pointOfOrder" name="pointOfOrder" title="Enter point of order" type="number" type="text" value="0">
							<span class="k-invalid-msg" data-for="pointOfOrder">
							</span></div>
						<div>
							<label>Supplier:</label>
							<input id="supplier" class="input" name="supplier" title="Enter supplier." type="text">
							<span class="k-invalid-msg" data-for="supplier">
							</span></div>
						<div>
							<label>Contact Person:</label>
							<input id="contactPerson" class="input" name="contactPerson" title="Enter contact person." type="text">
							<span class="k-invalid-msg" data-for="contactPerson">
							</span></div>
						<div>
							<label>Contact Number:</label>
							<input id="contactNumber" class="input" name="contactNumber" title="Enter contact number." type="text">
							<span class="k-invalid-msg" data-for="contactNumber">
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
