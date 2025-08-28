<?
	session_start();
	include ('init.php');
	include ('func/fn_common.php');	
	checkUserSession();
	
	setUserSessionSettings($_SESSION["user_id"]);
	loadLanguage($_SESSION['language'], $_SESSION["units"]);
	
	if (isset($_SESSION["cpanel_privileges"]))
	{
		if ($_SESSION['cpanel_privileges'] == 'super_admin' || $_SESSION['cpanel_privileges'] == 'admin')
		{
			setUserSessionObjOverwrt($_SESSION['cpanel_user_id']);
		}
		else if ($_SESSION['cpanel_privileges'] == 'manager')
		{
			setUserSessionObjOverwrt($_SESSION['manager_id']);	
		}
	}
	
	$user_id_allv=0;
	if ($_SESSION["privileges"] == 'subuser')
	{
		$user_id_allv = $_SESSION["manager_id"];
	}
	else
	{
		$user_id_allv = $_SESSION["user_id"];
	}
	
	
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<? generatorTag(); ?>
        <title><? echo $gsValues['NAME'].' '.$gsValues['VERSION']; ?></title>
	
        <link type="text/css" href="theme/jquery-ui.css?v=<? echo $gsValues['VERSION_ID']; ?>" rel="Stylesheet" />
        <link type="text/css" href="theme/jquery.qtip.css?v=<? echo $gsValues['VERSION_ID']; ?>" rel="Stylesheet" />
        <link type="text/css" href="theme/ui.jqgrid.css?v=<? echo $gsValues['VERSION_ID']; ?>" rel="Stylesheet" />
        <link type="text/css" href="theme/jquery.pnotify.default.css?v=<? echo $gsValues['VERSION_ID']; ?>" rel="Stylesheet" />
	<link type="text/css" href="theme/style.css?v=<? echo $gsValues['VERSION_ID']; ?>" rel="Stylesheet" />
	
	<link type="text/css" href="theme/leaflet/leaflet.css?v=<? echo $gsValues['VERSION_ID']; ?>" rel="Stylesheet" />
	<link type="text/css" href="theme/leaflet/markercluster.css?v=<? echo $gsValues['VERSION_ID']; ?>" rel="Stylesheet" />
	<link type="text/css" href="theme/leaflet/leaflet-routing-machine.css?v=<? echo $gsValues['VERSION_ID']; ?>" rel="Stylesheet" />
	<link rel="stylesheet" type="text/css" href="apush/css/vselect.css" />
		<link rel="stylesheet" type="text/css" href="apush/css/hover.css" />
			<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
			<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"   rel="stylesheet">

			
	<?
	if ($gsValues['MAP_GOOGLE'] == 'true')
	{
		if ($gsValues['MAP_GOOGLE_KEY'] == '')
		{
			echo '<script src="'.$gsValues['HTTP_MODE'].'://maps.google.com/maps/api/js"></script>';
		}
		else
		{
			echo '<script src="'.$gsValues['HTTP_MODE'].'://maps.google.com/maps/api/js?key='.$gsValues['MAP_GOOGLE_KEY'].'"></script>';
		}
	}
	?>
	
	<?
	if ($gsValues['MAP_YANDEX'] == 'true')
	{
		echo '<script src="'.$gsValues['HTTP_MODE'].'://api-maps.yandex.ru/2.0/?load=package.map&lang=ru-RU"></script>';
	}
	?>
	
        <script type="text/javascript" src="js/leaflet/leaflet.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>
	
	<?
	if ($gsValues['MAP_MAPBOX'] == 'true')
	{
		echo '<script src="'.$gsValues['HTTP_MODE'].'://api.mapbox.com/mapbox.js/v3.0.1/mapbox.js"></script>';
	}
	?>
	
	<script type="text/javascript" src="js/es6-promise.min.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>
	<script>ES6Promise.polyfill();</script>
	<script type="text/javascript" src="js/md5.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>
	<script type="text/javascript" src="js/xml2json.min.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>
	<script type="text/javascript" src="js/sprintf.min.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>
	
	<script type="text/javascript" src="js/jscolor.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>
	
	<script type="text/javascript" src="js/leaflet/tile/google.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>
	<script type="text/javascript" src="js/leaflet/tile/bing.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>
	<script type="text/javascript" src="js/leaflet/tile/yandex.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>
	<script type="text/javascript" src="js/leaflet/leaflet.editable.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>
	<script type="text/javascript" src="js/leaflet/leaflet.markercluster.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>
	<script type="text/javascript" src="js/leaflet/leaflet.polylinedecorator.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>
	<script type="text/javascript" src="js/leaflet/leaflet.routingmachine.js?v='.$gsValues['VERSION_ID'].'"></script>
	<script type="text/javascript" src="js/leaflet/marker.rotate.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>
	<script type="text/javascript" src="js/leaflet/path.drag.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>
	
	<script type="text/javascript" src="js/jquery-2.1.4.min.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>
	<script type="text/javascript" src="js/jquery-migrate-1.2.1.min.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>
        <script type="text/javascript" src="js/jquery-ui.min.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>
        <script type="text/javascript" src="js/jquery.qtip.min.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>
        <script type="text/javascript" src="js/jquery.jqGrid.locale.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>
        <script type="text/javascript" src="js/jquery.jqGrid.min.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>
        <script type="text/javascript" src="js/jquery.pnotify.min.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>
	<script type="text/javascript" src="js/jquery.generatefile.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>
	<script type="text/javascript" src="js/jquery.blockUI.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>

        <script type="text/javascript" src="js/jquery.flot.min.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>
	<script type="text/javascript" src="js/jquery.flot.crosshair.min.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>
        <script type="text/javascript" src="js/jquery.flot.navigate.min.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>
        <script type="text/javascript" src="js/jquery.flot.selection.min.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>
	<script type="text/javascript" src="js/jquery.flot.time.min.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>
        <script type="text/javascript" src="js/jquery.flot.resize.min.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>
	
	<script type="text/javascript" src="js/moment.min.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>

	<script type="text/javascript" src="js/gs.config.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>
	<script type="text/javascript" src="js/gs.common.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>
	<script type="text/javascript" src="js/gs.connect.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>
    
    <script type="text/javascript" src="js/gs.main.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>
    <script type="text/javascript" src="js/gs.fullviewcontent.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>
    
    <script type="text/javascript" src="js/xlsx.full.min.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>
    <script type="text/javascript" src="js/jszip.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>
    
  
	<script type="text/javascript" src="js/add.js"></script>
	
   
    <script src="js/amcharts/amcharts.js"></script>
    <script src="js/amcharts/pie.js"></script>
    <script src="js/amcharts/gauge.js"></script>
    <script src="js/amcharts/serial.js"></script>
	<link rel="stylesheet" type="text/css" href="apush/css/default.css" />
	<link rel="stylesheet" type="text/css" href="apush/css/component.css" />
	
	<script src="apush/js/modernizr.custom.js"></script>
	<script src="apush/js/classie.js"></script>

	<script type="text/javascript" src="exp/html2canvas.js"></script>
	<script type="text/javascript" src="exp/jquery.base64.js"></script>
	<script type="text/javascript" src="exp/tableExport.js"></script>
	<!--<script type="text/javascript" src="js/materialize.min.js"></script>-->
	
	<link rel="stylesheet" type="text/css" href="theme/css/fontello.css" />
	<script src="apush/js/jquery.multi-select.js"></script>
	<script src="apush/js/jquery.quicksearch.js"></script>	
	<script src="apush/js/select3.js"></script>	
	
    </head>
    
    <body class="fixed-left cbp-spmenu-push" onload="load()" onUnload="unload()">
	<input id="load_file" type="file" style="display: none;" onchange=""/>
	
	<div style="background:white;">
        <? include ("inc/inc_panels.php"); ?>
		<? include ("inc/inc_menus.php"); ?>
        <? include ("inc/inc_dialogs.main.php"); ?>
        <? include ("inc/inc_dialogs.places.php"); ?>
        <? include ("inc/inc_dialogs.reports.php"); ?>
		<? include ("inc/inc_dialogs.rilogbook.php"); ?>
		<? include ("inc/inc_dialogs.dtc.php"); ?>
		<? include ("inc/inc_dialogs.cmd.php"); ?>
		<? include ("inc/inc_dialogs.img.php"); ?>
		<? include ("inc/inc_dialogs.chat.php"); ?>
        <? include ("inc/inc_dialogs.settings.php"); ?>
        <? include ("inc/inc_addemployee.php"); ?>
                
        <? include ("inc/boarding.php"); ?>
    	<? include ("inc/rfid.php"); ?>
    	<? include ("inc/hospital.php"); ?>
    	<? include ("inc/myextra.php"); ?>
    	<? include ("inc/ambulance.reports.php"); ?>
    	<? include ("inc/inc_sos.php"); ?>
     	<script src="apush/js/jquery.form-advanced.init.js"></script>	
		<script src="apush/js/jquery.core.js"></script>
		<? include ("inc/newdashboardv.php");?>    
     </div>           
    </body>   
</html>
