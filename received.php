<?php
session_start();
if(!isset($_SESSION['users'])||empty($_SESSION['users']))header("location:index.php");
/*SERVER CODE---------------------------------------------------------------------------*/
require_once('MySQL.class.php');
$database= new database();
$personal=$_SESSION['users'];
$strsql="SELECT p.*,pc.category,pt.type FROM products p LEFT OUTER JOIN productcategory pc ON p.categoryID=pc.categoryID LEFT OUTER JOIN producttype pt ON p.typeID=pt.typeID WHERE p.status='1' AND pc.status='1' AND pt.status='1' AND p.quantity<=p.pointOfOrder";
$pointoforder=$database->query($strsql);

if(isset($_POST) && !empty($_POST)):
/*START*/
switch($_POST['mode']):
	case 'recievePurchaseOrder':
	$purchaseOrderDetailID=explode(',',$_POST['purchaseOrderDetailID']);
	$purchaseOrderID=$_POST['purchaseOrderID'];
	foreach($purchaseOrderDetailID as $id){
		$strsql="SELECT productID,quantity FROM purchaseorderdetail WHERE purchaseOrderID='".$purchaseOrderID."' AND purchaseOrderDetailID='".$id."'";
		$data=$database->query($strsql);
		$strsql="UPDATE products SET quantity=quantity+".$data[0]['quantity']." WHERE productID='".$data[0]['productID']."'";
		$database->execute($strsql);
		$strsql="UPDATE purchaseorderdetail SET status=2 WHERE purchaseOrderID='".$purchaseOrderID."' AND purchaseOrderDetailID='".$id."'";
		$database->execute($strsql);
		$strsql="SELECT * FROM purchaseorderdetail WHERE purchaseOrderID='".$purchaseOrderID."'";
		$data=$database->query($strsql);
		$found=false;
		foreach($data as $item){
			if($item['status']=='1'){
			$found=true;
			break;
			}		
		}
		if(!$found){
			$strsql="UPDATE purchaseorder SET status=2 WHERE purchaseOrderID='".$purchaseOrderID."'";
			$database->execute($strsql);
		}
	}
	echo 'true';
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
	$('#m6').addClass('current-menu-item');
	script.initial();
	script.eventhandle();
});
var script= new function() {
	this.initial=function(){
		var detailInit=function(e){
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
                            { field: "productID", title:"Product ID" },
                            { field: "name", title:"Name" },
                            { field: "cost", title:"Cost"},
                            { field: "quantity", title:"Quantity"},
                            { field: "status", title:"Status",template:'#=status==2?"Received":"Not received"#'}                          
                        ]
                    });
                }

	    $("#gridPurchaseTable").kendoGrid({
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
	        	{field: "grandTotal",title: "Grand Total",type:"number"},
	        	{field: "recordByName",title: "Record By"},
	        	{field: "status",title: "Status",template:'#=status==2?"Completed":"Pending"#'}      	
	        	],
	        toolbar: [
	        	{
	        	template: '<a class="k-button" href="javascript:;" id="detailList"><img src="img/mono-icons/zoom.png" width="12px">View Detail</a>'
	        	},
	        	{
	        	template: '<a class="k-button" href="javascript:;" id="cancel"><img src="img/mono-icons/undo.png" width="12px">Cancel</a>'
	        	}

				]

	    });
	    $("#gridPurchaseDetailTable").kendoGrid({
                        dataSource: {
                            type: "json",
                            transport: {
                            	read: {
				        		dataType:"json",
				        		type:"POST",
				        		data:({mode:'selectAllPurchaseDetail'}),
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
                        	{ width:40,template:'<input type="checkbox" name="chk" #= status==2 ? checked="checked"  : "" # #= status==2 ? disabled="disabled"  : "" # value="#=purchaseOrderDetailID#"></input>'},
                            { field: "productID", title:"Product ID" },
                            { field: "name", title:"Name" },
                            { field: "cost", title:"Cost"},
                            { field: "quantity", title:"Quantity"}
	        				
                            
                        ]
                    });
                    var grid = $("#gridPurchaseDetailTable").data("kendoGrid");

    grid.thead.find("th:first")
        .append($('<input class="selectAll" type="checkbox"/>'))
        .delegate(".selectAll", "click", function() {
            var checkbox = $(this);            

            grid.table.find("tr")
                .find("td:first input")
                .attr("checked", checkbox.is(":checked"))
                .trigger("change");
        });
	                    
	}
	this.eventhandle=function(){
		$("#cancel").click(function(){
			$("#gridPurchaseTable").data("kendoGrid").clearSelection();	
			$("#gridPurchaseDetailTable").data("kendoGrid").clearSelection();
			
			$("#gridPurchaseTable").data("kendoGrid").dataSource.read();	
			$("#gridPurchaseDetailTable").data("kendoGrid").dataSource.read({mode:'selectAllPurchaseDetail',purchaseOrderID:0});
			
			$("#detailList").removeClass("k-state-disabled");
			$('.selectAll').prop('checked',false);	
			$('#gridPurchaseTable').data('kendoGrid').tbody.unbind('mousedown');
		});
		$('#detailList').click(function(){			
			var gridTable = $("#gridPurchaseTable").data("kendoGrid");
			var selectedItem = gridTable.dataItem(gridTable.select());
			$('#gridPurchaseTable').data('kendoGrid').tbody.on('mousedown',function(e){e.stopImmediatePropagation();});
			if(selectedItem==null){alert('Please select record to view detail.');return;}
			if($("#detailList").hasClass("k-state-disabled"))return;
			$("#detailList").addClass("k-state-disabled");		
			$("#gridPurchaseDetailTable").data("kendoGrid").dataSource.read({mode:'selectAllPurchaseDetail',purchaseOrderID:selectedItem.purchaseOrderID});
		});
		$('#save').click(function(){
			var gridTable = $("#gridPurchaseTable").data("kendoGrid");
			var selectedItem = gridTable.dataItem(gridTable.select());
			if(selectedItem==null){alert('Please select record to view detail.');return;}
			var datastring=new Object();
			
			datastring.purchaseOrderID=selectedItem.purchaseOrderID;
			datastring.purchaseOrderDetailID=$("input[name=chk]:checked").map(function () {if($(this).is(":disabled")){return null;}else{return this.value;}}).get().join(",");
			if(datastring.purchaseOrderDetailID==""){alert('Please select record to receive.');return;}
			if(datastring.purchaseOrderDetailID==null){alert('This record is already received.');return;}
			//console.log(datastring);
			
			datastring.mode='recievePurchaseOrder';
			$('#loading').show();
			if(ajax('received.php',datastring,false)=='true'){
			$('#loading').hide();			
			alert("Compleate transection.");
			$('#cancel').trigger('click');
			}
		});
	}
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
				<form action="" method="post">
					<?php include_once('profileheader.php'); ?>
					<fieldset  class="k-content">
					<legend style="color: #37b2d1">Receive purchase order</legend>
					<fieldset>
					<legend>Purchase order list</legend>
					<div id="gridPurchaseTable" ></div>
					<div class="clear" style="height: 10px"></div>
					<div id="gridPurchaseDetailTable" ></div>
					<div class="clear" style="height: 10px"></div>
					<input id="save" class="k-button" name="save" type="button" value="Receive products" />
					<img id="loading" alt="" src="img/loading.gif" style="vertical-align: middle; display: none;">
					</fieldset>
					</fieldset>

				</form>
			</div>
		</div>
		<!-- ENDS content --></div>
	<!-- ENDS wrapper-main --></div>
<!-- ENDS MAIN -->
<!-- Bottom --><?php include_once('bottom.php'); ?>
<!-- ENDS Bottom -->

</body>

</html>
