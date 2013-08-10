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
if(isset($_GET['pointoforder'])):
		if(!isset($_SESSION['gridTempPurchase']))$_SESSION['gridTempPurchase']=array();
		if(!is_array($_SESSION['gridTempPurchase']))
			{
			$localgridTemp=array();
			}
			else
			{
			$localgridTemp=$_SESSION['gridTempPurchase'];
			}
		$productsID=explode(',',$_GET['pointoforder']);
		foreach($productsID as $productID):
		$strsql="SELECT p.*,c.category,t.type FROM products p LEFT OUTER JOIN productcategory c ON p.categoryID=c.categoryID LEFT OUTER JOIN producttype t ON p.typeID=t.typeID WHERE p.productID='".$productID."'";
		$product=$database->query($strsql);
		$found=false;
		for($i=0;$i<count($localgridTemp,2);$i++){
			if($localgridTemp[$i]['productID']==$productID){			
			$found=true;
			break;
			}
		}
		if(!$found)
		{
			$index=count($localgridTemp,2);
			$localgridTemp[$index]['productID']=$productID;
			$localgridTemp[$index]['code']=$product[0]['code'];
			$localgridTemp[$index]['category']=$product[0]['category'];
			$localgridTemp[$index]['type']=$product[0]['type'];
			$localgridTemp[$index]['name']=$product[0]['name'];
			$localgridTemp[$index]['unit']=$product[0]['unit'];
			$localgridTemp[$index]['cost']=$product[0]['cost'];
			$localgridTemp[$index]['quantity']=1;
		}
		endforeach;
		$_SESSION['gridTempPurchase'] = array_values($localgridTemp);
endif;	
endif;
if(isset($_POST) && !empty($_POST)):
/*START*/
switch($_POST['mode']):
	case 'selectAllProduct':
		$strsql="SELECT p.*,c.category,t.type FROM products p LEFT OUTER JOIN productcategory c ON p.categoryID=c.categoryID LEFT OUTER JOIN producttype t ON p.typeID=t.typeID";
		$database->showDataAsJson($strsql);
	break;
	case 'search':
	$keyword = preg_replace('/\s\s+/', ' ', $_POST['search']);
	$searchTerms = explode(' ', $keyword);
	$searchTermBits = array();
	foreach ($searchTerms as $term) {
	    $term = trim($term);
	    if (!empty($term)) {
	        $searchTermBits[] = "p.code LIKE '%$term%'";
			$searchTermBits[] = "p.name LIKE '%$term%'";
			$searchTermBits[] = "c.category LIKE '%$term%'";
			$searchTermBits[] = "t.type LIKE '%$term%'";
			$searchTermBits[] = "p.color LIKE '%$term%'";
			$searchTermBits[] = "p.size LIKE '%$term%'";
			$searchTermBits[] = "p.listOfMaterial LIKE '%$term%'";
			$searchTermBits[] = "p.price LIKE '%$term%'";
			$searchTermBits[] = "p.cost LIKE '%$term%'";
			$searchTermBits[] = "p.unit LIKE '%$term%'";
			$searchTermBits[] = "p.quantity LIKE '%$term%'";
			$searchTermBits[] = "p.pointOfOrder LIKE '%$term%'";
			$searchTermBits[] = "p.supplier LIKE '%$term%'";
			$searchTermBits[] = "p.contactPerson LIKE '%$term%'";
			$searchTermBits[] = "p.contactNumber LIKE '%$term%'";
	    }
	}
		$strsql="SELECT p.*,c.category,t.type FROM products p LEFT OUTER JOIN productcategory c ON p.categoryID=c.categoryID LEFT OUTER JOIN producttype t ON p.typeID=t.typeID WHERE ".implode(' OR ', $searchTermBits)."";
		$database->showDataAsJson($strsql);
	break;
	case 'addItem':
		if(!isset($_SESSION['gridTempPurchase']))$_SESSION['gridTempPurchase']=array();
		if(!is_array($_SESSION['gridTempPurchase']))
			{
			$localgridTemp=array();
			}
			else
			{
			$localgridTemp=$_SESSION['gridTempPurchase'];
			}
		
		$productID=$_POST['productID'];
		$strsql="SELECT p.*,c.category,t.type FROM products p LEFT OUTER JOIN productcategory c ON p.categoryID=c.categoryID LEFT OUTER JOIN producttype t ON p.typeID=t.typeID WHERE p.productID='".$productID."'";
		$product=$database->query($strsql);
		$found=false;
		for($i=0;$i<count($localgridTemp,2);$i++){
			if($localgridTemp[$i]['productID']==$productID){
			$localgridTemp[$i]['quantity']+=$_POST['quantity'];				
			$found=true;
			break;
			}
		}
		if(!$found)
		{
			$index=count($localgridTemp,2);
			$localgridTemp[$index]['productID']=$productID;
			$localgridTemp[$index]['code']=$product[0]['code'];
			$localgridTemp[$index]['category']=$product[0]['category'];
			$localgridTemp[$index]['type']=$product[0]['type'];
			$localgridTemp[$index]['name']=$product[0]['name'];
			$localgridTemp[$index]['unit']=$product[0]['unit'];
			$localgridTemp[$index]['cost']=$product[0]['cost'];
			$localgridTemp[$index]['quantity']=$_POST['quantity'];
		}
		$_SESSION['gridTempPurchase'] = array_values($localgridTemp);
		echo "{\"data\":" .json_encode($_SESSION['gridTempPurchase']). ",\"total\":".count($_SESSION['gridTempPurchase'])."}";
	break;
	case 'updateItem':
		if(!isset($_SESSION['gridTempPurchase']))$_SESSION['gridTempPurchase']=array();
		if(!is_array($_SESSION['gridTempPurchase']))
			{
			$localgridTemp=array();
			}
			else
			{
			$localgridTemp=$_SESSION['gridTempPurchase'];
			}
		$productID=$_POST['productID'];	
		for($i=0;$i<count($localgridTemp,2);$i++){
			if($localgridTemp[$i]['productID']==$productID){
			$localgridTemp[$i]['quantity']=$_POST['quantity'];				
			break;
			}
		}
		$_SESSION['gridTempPurchase'] = array_values($localgridTemp);
		echo "{\"data\":" .json_encode($_SESSION['gridTempPurchase']). ",\"total\":".count($_SESSION['gridTempPurchase'])."}";
	break;
	case 'deleteItem':
		if(!isset($_SESSION['gridTempPurchase']))$_SESSION['gridTempPurchase']=array();
		if(!is_array($_SESSION['gridTempPurchase']))
			{
			$localgridTemp=array();
			}
			else
			{
			$localgridTemp=$_SESSION['gridTempPurchase'];
			}
		$productID=$_POST['productID'];	
		for($i=0;$i<count($localgridTemp,2);$i++){
			if($localgridTemp[$i]['productID']==$productID){
			unset($localgridTemp[$i]);				
			break;
			}
		}
		$_SESSION['gridTempPurchase'] = array_values($localgridTemp);
		echo "{\"data\":" .json_encode($_SESSION['gridTempPurchase']). ",\"total\":".count($_SESSION['gridTempPurchase'])."}";
	break;
	
	case 'selectTemp':
		echo "{\"data\":" .json_encode($_SESSION['gridTempPurchase']). ",\"total\":".count($_SESSION['gridTempPurchase'])."}";
	break;
	case 'selectAllPurchase':
		$strsql="SELECT r.*,concat(u.firstName,' ',u.lastName) as recordByName FROM purchaseorder r LEFT OUTER JOIN users u ON r.orderBy=u.userID";
		$database->showDataAsJson($strsql);
	break;	
	case 'selectAllPurchaseDetail':
		$strsql="SELECT rqd.*,p.name FROM purchaseorderdetail rqd LEFT OUTER JOIN products p ON rqd.productID=p.productID WHERE rqd.purchaseOrderID='".$_POST['purchaseOrderID']."'";
		$database->showDataAsJson($strsql);
	break;	
	case 'insert':
	if(!isset($_SESSION['gridTempPurchase']))$_SESSION['gridTempPurchase']=array();
		if(!is_array($_SESSION['gridTempPurchase']))
			{
			$localgridTemp=array();
			}
			else
			{
			$localgridTemp=$_SESSION['gridTempPurchase'];
			}
			$grandTotal=0;
			for($i=0;$i<count($localgridTemp,2);$i++){
				$grandTotal+=$localgridTemp[$i]['cost']*$localgridTemp[$i]['quantity'];
			}		
			$strsql="INSERT INTO purchaseorder(
			code
			,orderDate
			,orderBy
			,amount
			,grandTotal
			,status
			)VALUES(
			'".$_POST['code']."'
			,'".$_POST['orderDate']."'
			,'".$_SESSION['users'][0]['userID']."'
			,'".count($localgridTemp,2)."'
			,'".$grandTotal."'
			,'1'
			)";
			if($database->execute($strsql)){
				for($i=0;$i<count($localgridTemp,2);$i++){
				$strsql="INSERT INTO purchaseorderdetail (
				purchaseOrderID
				,productID
				,quantity
				,cost
				,total
				,status
				) VALUES (
				'".$_POST['purchaseOrderID']."'
				,'".$localgridTemp[$i]['productID']."'
				,'".$localgridTemp[$i]['quantity']."'
				,'".$localgridTemp[$i]['cost']."'
				,'".$localgridTemp[$i]['quantity']*$localgridTemp[$i]['cost']."'
				,'1'
				);";
				$database->execute($strsql);
				}
			}
			$_SESSION['gridTempPurchase'] = array();
			echo 'true';
			

	break;
	case 'getLastID':
		$strsql="SELECT AUTO_INCREMENT AS lastID FROM information_schema.tables WHERE table_name='purchaseorder' AND table_schema = 'inventorysystem'";
		$data=$database->query($strsql);
		echo $data[0]['lastID'];
	break;
	case 'count':
		echo count($_SESSION['gridTempPurchase']);
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
	$('#m5').addClass('current-menu-item');
	script.initial();
	script.validation();
	script.eventhandle();
	script.clearform();
});
var script= new function() {
	var validator = $("#scriptForm");
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
		        		url:'purchase.php'
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
	        pageable: {pageSizes: true},
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
	        	{field: "pointOfOrder",title: "Point Of Order",format:"{0:#}",type: "number"}	        	
	        	],
	        toolbar: [
	        	{
	        	template: '<input id="txtsearch" name="txtsearch" type="text"  value="" class="input">'
	        	},
	        	{
	        	template: '<a class="k-button" href="javascript:;" id="search"><img src="img/mono-icons/zoom.png" width="12px">Search</a>'
	        	},
	        	{
	        	template: '<input id="txtquantity" name="txtquantity" type="text"  value="1" style="width:100px">'
	        	},
				{
	        	template: '<a class="k-button" href="javascript:;" id="addNew"><img src="img/mono-icons/doc_plus.png" width="12px">Add item</a>'
	        	},
	        	{
	        	template: '<a class="k-button" href="javascript:;" id="cancel"><img src="img/mono-icons/undo.png" width="12px">Cancel</a>'
	        	}


				]
	    });
	    $("#gridTableSelect").kendoGrid({
	        dataSource: {
	        	transport: {
	        	 read: {
		        		dataType:"json",
		        		type:"POST",
		        		data:({mode:'selectTemp'}),
		        		url:'purchase.php'
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
	        sortable: true,
	        columnMenu: true,
	        selectable: "multiple",
	        pageable: {pageSizes: true},
	        columns: [ 
	        	{field: "productID",title: "ID",width: 60,type: "number"},
	        	{field: "code",title: "Code"},
	        	{field: "category",title: "Category"},	        	
	        	{field: "type",title: "Type"},
	        	{field: "name",title: "Name"},
	        	{field: "unit",title: "Unit"},
	        	{field: "quantity",title: "Quantity",format:"{0:#}",type: "number"}	        	
	        	],
	        toolbar: [
	        	{
	        	template: 'Edit Quantity :<input id="txtquantityedit" name="txtquantityedit" type="text"  value="" class="input" style="width:100px">'
	        	},
	        	{
	        	template: '<a class="k-button" href="javascript:;" id="btnupdate"><img src="img/mono-icons/doc_edit.png" width="12px">Update</a>'
	        	},
	        	{
	        	template: '<a class="k-button" href="javascript:;" id="btndelete"><img src="img/mono-icons/doc_delete.png" width="12px">Delete</a>'
	        	},
	        	{
	        	template: '<a class="k-button" href="javascript:;" id="btncancel"><img src="img/mono-icons/undo.png" width="12px">Cancel</a>'
	        	}
				]
	    });
	    var detailInit=function(e){
	    //console.log(e.data);
                    $("<div/>").appendTo(e.detailCell).kendoGrid({
                        dataSource: {
                            type: "json",
                            transport: {
                            	read: {
				        		dataType:"json",
				        		type:"POST",
				        		data:({mode:'selectAllPurchaseDetail',purchaseOrderID:e.data.purchaseOrderID}),
				        		url:'purchase.php'
				        		}

                            },
                            pageSize: 5,
                            schema: {
							    data: "data",
							    total:"total"
							}
                        },
			            filterable: true,
				        resizable: true,
				        reorderable: true,
				        sortable: true,
				        columnMenu: true,
				        selectable: "multiple",
				        pageable: {pageSizes: true},
                        columns: [
                            { field: "productID", title:"Product ID",width: "60px" },
                            { field: "name", title:"Name" },
                            { field: "cost", title:"Cost"},
                            { field: "quantity", title:"Quantity"},
                            { field: "status", title:"Status",template:'#=status==2?"Received":"Not received"#'} 
                            
                        ]
                    });
                }
	    $("#gridTableRequisitionList").kendoGrid({
	        dataSource: {
	        	transport: {
	        	 read: {
		        		dataType:"json",
		        		type:"POST",
		        		data:({mode:'selectAllPurchase'}),
		        		url:'purchase.php'
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
	        sortable: true,
	        columnMenu: true,
	        selectable: "multiple",
	        pageable: {pageSizes: true},
	        detailInit: detailInit,
/*	        dataBound: function() {
            	this.expandRow(this.tbody.find("tr.k-master-row").first());
            },
*/	        columns: [ 
	        	{field: "purchaseOrderID",title: "ID",width: 60,type: "number"},
	        	{field: "code",title: "Code",width: 100},
	        		        	
	        	{field: "orderDate",title: "Order Date",format: "{0: yyyy-MM-dd}"},
	        	{field: "amount",title: "Amount",width: 70},
	        	{field: "grandTotal",title: "Grand Total",type:"number",width: 70},
	        	{field: "recordByName",title: "Record By"},
	        	{field: "status",title: "Status",template:'#=status==2?"Completed":"Pending"#'}      	
	        	]
	    });
    	$("#txtquantity,#txtquantityedit").kendoNumericTextBox({format:"#",min:1,max:999}); 
    	$('#orderDate').kendoDatePicker({format: "yyyy-MM-dd"}).data("kendoDatePicker").min('<?php echo date("Y-m-d");?>');              
	}//end initial
	this.eventhandle=function(){
		$('#search').click(function(){
			if($('#txtsearch').val()=="")return;
			$("#gridTable").data("kendoGrid").dataSource.read({search:$('#txtsearch').val(),mode:'search'});			
		});	
		$('#addNew').click(function(){
			if($('#txtquantity').val()==""){alert('Please enter quantity!');return;}
			var gridTable = $("#gridTable ").data("kendoGrid");
			var selectedItem = gridTable.dataItem(gridTable.select());
			if(selectedItem==null){alert('Please select record to add!');return;}
			$("#gridTableSelect").data("kendoGrid").dataSource.read({mode:'addItem',productID:selectedItem.productID,quantity:$('#txtquantity').val()});
		});	
		$("#cancel").click(function(){
			$('#txtsearch').val('');	
			$("#gridTable").data("kendoGrid").dataSource.read({mode:'selectAllProduct'});	
			script.clearform();
		});
		$('#btncancel').click(function(){
			$("#gridTableSelect").data("kendoGrid").clearSelection();
			$("#txtquantityedit").data("kendoNumericTextBox").value(null);
		});
		$("#btnupdate").click(function(){
			var gridTable = $("#gridTableSelect").data("kendoGrid");
			var selectedItem = gridTable.dataItem(gridTable.select());
			if(selectedItem==null){alert('Please select record to update.');return;}
			if($("#txtquantityedit").data("kendoNumericTextBox").value()==null){alert('Please enter quantity!');return;}
			$("#gridTableSelect").data("kendoGrid").dataSource.read({mode:'updateItem',productID:selectedItem.productID,quantity:$('#txtquantityedit').val()});
			$("#txtquantityedit").data("kendoNumericTextBox").value(null);
			$("#gridTableSelect").data("kendoGrid").clearSelection();			
		});
		$("#btndelete").click(function(){
			var gridTable = $("#gridTableSelect").data("kendoGrid");
			var selectedItem = gridTable.dataItem(gridTable.select());
			if(selectedItem==null){alert('Please select record to delete.');return;}
			if(confirm('Do you want to delete this record?')){
			$("#gridTableSelect").data("kendoGrid").dataSource.read({mode:'deleteItem',productID:selectedItem.productID});
			}
			$("#gridTableSelect").data("kendoGrid").clearSelection();			
		});		
		$("#save").click(function() {
            if (validator.validate()) {
            	if($.trim(ajax('purchase.php',({mode:'count'}),false))!='0'){
                status.hide();
                script.save();
                }else{
                alert('There are no item to be recorded.');
                }
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
			        
			            $("#gridTable").data("kendoGrid").dataSource.read();
			            $("#gridTableSelect").data("kendoGrid").dataSource.read({mode:'selectTemp'});
			            $("#gridTableRequisitionList").data("kendoGrid").dataSource.read();
		            }else{
		            	alert("Cannot complete your transection!");
		            } 
			    }
			};
			$("#scriptForm").ajaxSubmit(options);
	}//end save
	this.clearform=function(){
	var lastID=ajax('purchase.php',({mode:'getLastID'}),false);
		$('#purchaseOrderID').val(lastID);
		$('#code').val(pad(lastID,7));
		$("#gridTable").data("kendoGrid").clearSelection();
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
				<form id="scriptForm" action="purchase.php" method="post" name="scriptForm">
					<input id="mode" name="mode" type="hidden" value="insert" />
					<?php include_once('profileheader.php'); ?>
					<fieldset class="k-content">
					<legend style="color: #37b2d1">Purchase product</legend>
					<fieldset>
					<legend>Product List</legend>
					<div id="gridTable">
					</div>
					<div class="clear" style="height: 10px">
					</div>
					<div id="gridTableSelect">
					</div>
					<div class="clear" style="height: 10px">
					</div>
					<div>
							<label>ID :</label>
							<input id="purchaseOrderID" class="input" name="purchaseOrderID" readonly required="required" title="Click add new to gen new ID" type="text">
							<span class="k-invalid-msg" data-for="purchaseOrderID">
							</span></div>

					<div>
							<label>Code :</label>
							<input id="code" class="input" name="code" readonly required="required" title="Click add new to gen new code" type="text">
							<span class="k-invalid-msg" data-for="code"></span>
						</div>

					<div>
						<label>Order Date :</label>
						<input id="orderDate" name="orderDate" required="required" title="Select order date" type="text" value="<?php echo date("Y-m-d");?>">*
						<span class="k-invalid-msg" data-for="orderDate"></span>
					</div>
					<div class="clear" style="height: 10px">
					</div>
					<input id="save" class="k-button" name="save" type="button" value="Save" />
					<img id="loading" alt="" src="img/loading.gif" style="vertical-align: middle; display: none;">
					<p id="error" class="warning">Message</p>
					</fieldset> <fieldset>
					<legend>Purchase List</legend>
					<div id="gridTableRequisitionList">
					</div>
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
