<?php
session_start();
require_once('MySQL.class.php');
$database= new database();
if(!isset($_SESSION['users'])||empty($_SESSION['users'])){header("location:index.php");}else{
$role=$database->checkrole($_SESSION['users'][0]['typeID'],$_SERVER['PHP_SELF']);
if($role==0)header("location:nopermission.php");}
/*SERVER CODE---------------------------------------------------------------------------*/
$personal=$_SESSION['users'];
if(isset($_POST) && !empty($_POST)):
/*START*/
switch($_POST['mode']):
case 'search':
	$keyword = preg_replace('/\s\s+/', ' ', $_POST['search']);
	$searchTerms = explode(' ', $keyword);
	$searchTermBits = array();
	foreach ($searchTerms as $term) {
	    $term = trim($term);
	    if (!empty($term)) {
	        $searchTermBits[] = "p.productID LIKE '%$term%'";
			$searchTermBits[] = "p.code LIKE '%$term%'";
			$searchTermBits[] = "c.category LIKE '%$term%'";
			$searchTermBits[] = "t.type LIKE '%$term%'";
			$searchTermBits[] = "p.name LIKE '%$term%'";
			$searchTermBits[] = "p.description LIKE '%$term%'";
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
	    }
	}
		if(empty($_POST['search'])){
			$strsql="SELECT p.*,c.category,t.type FROM products p LEFT OUTER JOIN productcategory c ON p.categoryID=c.categoryID LEFT OUTER JOIN producttype t ON p.typeID=t.typeID";
		}else{
			$strsql="SELECT p.*,c.category,t.type FROM products p LEFT OUTER JOIN productcategory c ON p.categoryID=c.categoryID LEFT OUTER JOIN producttype t ON p.typeID=t.typeID WHERE ".implode(' OR ', $searchTermBits)."";
		}
		$database->showDataAsJson($strsql);
	break;

	case 'selectAllProduct':
		$strsql="SELECT p.*,c.category,t.type FROM products p LEFT OUTER JOIN productcategory c ON p.categoryID=c.categoryID LEFT OUTER JOIN producttype t ON p.typeID=t.typeID";
		$database->showDataAsJson($strsql);
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
	$('#m7').addClass('current-menu-item');
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
		        		url:'productlistreport.php'
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
	        	template: '<input id="txtsearch" name="txtsearch" type="text"  value="" class="input">'
	        	},
	        	{
	        	template: '<a class="k-button" href="javascript:;" id="search"><img src="img/mono-icons/zoom.png" width="12px">Search</a>'
	        	},
				{
	        	template: '<a class="k-button" href="javascript:;" id="print"><img src="img/mono-icons/print.png" width="12px">Print</a>'
	        	},
				{
	        	template: '<a class="k-button" href="javascript:;" id="export"><img src="img/mono-icons/doc_export.png" width="12px">Export to csv</a>'
	        	},
	        	{
	        	template: '<a class="k-button" href="javascript:;" id="cancel"><img src="img/mono-icons/undo.png" width="12px">Cancel</a>'
	        	}

				]
	    }); 	                
	}//end initial
	this.eventhandle=function(){
		$("#search").click(function(){		
			$("#gridTable").data("kendoGrid").dataSource.read({search:$('#txtsearch').val(),mode:'search'});
		});	
		$("#export").click(function(e) { 
			var rows=$.parseJSON(ajax('productlistreport.php',({mode:'search',search:$('#txtsearch').val()}),false));
			$("body").append('<form id="exportform" action="export.php" method="post" target="_blank"><input type="hidden" id="reportname" name="reportname" value="productListReport" /><input type="hidden" id="exportdata" name="exportdata" /></form>');
			    $("#exportdata").val(arrayToCSV(rows.data));
			    $("#exportform").submit().remove();
		});
		$("#print").click(function(e) { 
			printGrid($("#gridTable"));
		});
		$("#cancel").click(function(){		
			script.clearform();
		});	
	}//end eventhandle
	this.validation=function(){
	validator = $('#scriptForm').kendoValidator().data("kendoValidator"),status = $('#error');	
	}//end validator
	this.clearform=function(){
		$("#gridTable").data("kendoGrid").clearSelection();
		$("#gridTable").data("kendoGrid").dataSource.read();
		$("#gridTable").data("kendoGrid").refresh();
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
					<legend style="color: #37b2d1">Products report</legend>
					<fieldset id="fprofileList">
					<legend>Products List</legend>
					<div id="gridTable">
					</div>
					</fieldset>  </fieldset></form>
			</div>
		</div>
		<!-- ENDS content --></div>
	<!-- ENDS wrapper-main --></div>
<!-- ENDS MAIN -->
<!-- Bottom --><?php include_once('bottom.php'); ?>
<!-- ENDS Bottom -->

</body>

</html>
