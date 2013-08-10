<?php
session_start();
if(!isset($_SESSION['users'])||empty($_SESSION['users']))header("location:index.php");
/*SERVER CODE---------------------------------------------------------------------------*/
require_once('MySQL.class.php');
$database= new database();
$personal=$_SESSION['users'];
$strsql="SELECT p.*,pc.category,pt.type FROM products p LEFT OUTER JOIN productcategory pc ON p.categoryID=pc.categoryID LEFT OUTER JOIN producttype pt ON p.typeID=pt.typeID WHERE p.status='1' AND pc.status='1' AND pt.status='1' AND p.quantity<=p.pointOfOrder";
$pointoforder=$database->query($strsql);

if(isset($_GET) && !empty($_GET)):
/*START*/
switch($_GET['mode']):
	case 'selectProductReachToPointOfOrder':
	$strsql="SELECT p.*,pc.category,pt.type FROM products p LEFT OUTER JOIN productcategory pc ON p.categoryID=pc.categoryID LEFT OUTER JOIN producttype pt ON p.typeID=pt.typeID WHERE p.status='1' AND pc.status='1' AND pt.status='1' AND p.quantity<=p.pointOfOrder";
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
	$('#m1').addClass('current-menu-item');
	script.initial();
});
var script= new function() {
	this.initial=function(){
	$("#productToOrder").kendoGrid({
                        dataSource: {
				        	transport: {read: "main.php?mode=selectProductReachToPointOfOrder"},	            
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
                        	{field: "productID",title: "Product ID",type: "number"},
                        	{field: "code",title: "Code"},
                        	{field: "category",title: "Category"},
                        	{field: "type",title: "Type"},
                        	{field: "name",title: "Name"},
                        	{field: "unit",title: "Unit"},
                        	{field: "quantity",title: "Quantity"},
                        	{field: "pointOfOrder",title: "Point Of Order"}
                        	],
                        toolbar: [
                        	<?php foreach($pointoforder as $item){
                        		$p[]=$item['productID'];
                        	}?>
                        	{template: '<a class="k-button" href="purchase.php?pointoforder=<?php echo implode(',',$p);?>" ><img src="img/mono-icons/paperplus32.png" width="12px"> Make an order now</a>'}
							]
                    });
	                    
	}
}
//-->
</script></head>

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
			<div class="box" >
				<form action="" method="post">
					<?php include_once('profileheader.php'); ?>
					<fieldset  class="k-content">
					<legend style="color:#37b2d1">Product reach to point of order</legend>
					<fieldset>
					<legend>Product list</legend>
					<div id="productToOrder" ></div>
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
