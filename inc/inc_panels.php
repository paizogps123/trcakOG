
<style>
.dropbtn {
      background-color: #3498DB;
    color: white;
    padding: 4px;
    font-size: 11px;
    border: none;
    margin-left: 10px;
    cursor: pointer;
}

.dropbtn:hover, .dropbtn:focus {
    background-color: #2980B9;
}

.dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-content {
    display: none;
    position: fixed;  
    overflow: auto;   
    z-index: 91;
    background: #fff;
    width: 26% ;
    margin-top: 17px;
    right: 80px;
}

.dropdown-content a {
    color: black;
   
    text-decoration: none;
    
}
.show {display: block;}
</style>

<div id="map" style="bottom: 0px !important;"></div>

<div class="map-layer-control" id="maplayer_top">
	<div class="row4">
		<select id="map_layer" onChange="switchMapLayer($(this).val());"></select>
	</div>	
	<div class="hideshow_btn">
		<button name="hideshow_value"  class="hideshowbtn_value" onclick="hideshowmap_btn();"><img src="theme/images/down-arrow.svg" style="width: 15px;"></button>
	</div>
</div>

<div id="history_view_control" class="history-view-control">
	<div class="row4">
		<div class="margin-right-3"><input id="history_view_control_route" type="checkbox" class="checkbox" onclick="historyRouteToggleRoute();" checked/></div>
		<div class="margin-right-3"><? echo $la['ROUTE']; ?></div>
		<div class="margin-right-3"><input id="history_view_control_snap" type="checkbox" class="checkbox" onclick="historyRouteToggleSnap();"/></div>
		<div class="margin-right-3"><? echo $la['SNAP']; ?></div>
		<div class="margin-right-3"><input id="history_view_control_arrows" type="checkbox" class="checkbox" onclick="historyRouteToggleArrows();"/></div>
		<div class="margin-right-3"><? echo $la['ARROWS']; ?></div>
		<div class="margin-right-3"><input id="history_view_control_data_points" type="checkbox" class="checkbox" onclick="historyRouteToggleDataPoints();"/></div>
		<div class="margin-right-3"><? echo $la['DATA_POINTS']; ?></div>
		<div class="margin-right-3"><input id="history_view_control_stops" type="checkbox" class="checkbox" onclick="historyRouteToggleStops();" checked/></div>
		<div class="margin-right-3"><? echo $la['STOPS']; ?></div>
		<div class="margin-right-3"><input id="history_view_control_events" type="checkbox" class="checkbox" onclick="historyRouteToggleEvents();" checked/></div>
		<div class="margin-right-3"><? echo $la['EVENTS']; ?></div>
		<div class="margin-left-3">
			<!-- <img class="hidegraph" id="hideshowgraph" onclick="hideshow_graph()" src="http://track.playgps.co.in/img/logo.png" style="height: 10px;width: 10px;"> -->
		<a class="hidegraph" id="hideshowgraph" onclick="hideshow_graph()"><span id="showhode_data" style="color: #007cff;  font-weight: bold;">Hide Graph</span></a>
		</div>
		<div class="margin-left-3">
			<a class="button icon-close" href="#" onclick="historyHideRoute();" title="<? echo $la['HIDE'];?>">&nbsp;</a>
		</div>		
	</div>
</div>

<div id="loading_panel">
	<div class="table">
		<div class="table-cell center-middle">
			<div id="loading_panel_text">
				<div class="row">
					<img class="logo" src="<? echo $gsValues['URL_LOGO']; ?>" />
				</div>
				<div class="row">
					<div class="lds-hourglass"></div>
				</div>
			</div>
		</div>    
	</div>
</div>

<div id="loading_data_panel" style="display: none;">
	<div class="table">
		<div class="table-cell center-middle">
			<div class="row">
					<div class="lds-hourglass"></div>
				</div>
		</div>
	</div>
</div>

<div id="blocking_panel">
	<div class="table">
		<div class="table-cell center-middle">
			<div id="blocking_panel_text">
				<div class="row">
					<img class="logo" src="<? echo $gsValues['URL_LOGO']; ?>" />
				</div>
				<? echo sprintf($la['SESSION_HAS_EXPIRED'], $gsValues['URL_LOGIN']); ?>	
			</div>
		</div>
	</div>
</div>

<div id="top_panel" style="background: url(img/hex.jpg"); ">

 <ul class="left-menu"> 
	<span  id="showRight"   class="mapleftobtitle" ><img  id="showRight" onclick="toggleMenu()"   class="demo-icon icon-menu" style="cursor: pointer;font-size: 39px;color: red; margin-top: 6px;" src="theme/images/menu.png" /></span></span>


</ul>


<div class="lo"> <a href="#" class="logo"><img class="imgpad" onclick='$("#tglDBMV").click();' src="<? echo $gsValues['URL_ROOT']; ?>/img/logo.png"> </a>    </div>

<div class="ndtopbar-right" >                  
 <div id="side_panel">

<div class="dropdown">
<a href="#side_panel_objects"><button onclick="myFunction()" class="dropbtn" style="
    padding-left: 14px; background-color: #1E9FF2!important;
    padding-right: 14px;border-radius: 4px;
"><? echo truncateString($la['OBJECTS'], 10); ?></button></a>
  <div id="myDropdown" class="dropdown-content">
    <div id="side_panel_objects" style="  margin-top: -29px;">
		<div id="side_panel_objects_object_list">
			<table id="side_panel_objects_object_list_grid"></table>
		</div>
		<div id="side_panel_objects_dragbar">
		</div>
		<div id="side_panel_objects_object_data_list" class="jqgridtablesize">
			<table id="side_panel_objects_object_data_list_grid"></table>
		</div>
	</div>
  </div>
</div>

<div class="dropdown">
<a href="#side_panel_events"><button onclick="myFunction2()" class="dropbtn" style="
    padding-left: 14px; background-color: #666EE8!important;
    padding-right: 14px;border-radius: 4px;
"><? echo truncateString($la['EVENTS'], 10); ?></button></a> 
 <div id="myDropdown2" class="dropdown-content" style="margin-left: -50px;">
    <div id="side_panel_events" style="    margin-top: -29px;">
		    <div id="side_panel_events_event_list">
		       <table id="side_panel_events_event_list_grid"></table>
		       <div id="side_panel_events_event_list_grid_pager"></div>
	       </div>
	       <div id="side_panel_events_dragbar">
	       </div>
	       <div id="side_panel_events_event_data_list">
		       <table id="side_panel_events_event_data_list_grid"></table>
	       </div>
	    </div>


  </div>
</div>

<div class="dropdown">
<a href="#side_panel_places" id="side_panel_places_tab"><button onclick="myFunction3()" class="dropbtn" style="
    padding-left: 14px; background-color: #FF4961!important;
    padding-right: 14px;border-radius: 4px;
"><? echo truncateString($la['PLACES'], 10); ?></button></a>
  <div id="myDropdown3" class="dropdown-content" style="margin-left: -121px;">
    <div id="side_panel_places">
		<ul>
			<li><a href="#side_panel_places_markers"><span><? echo $la['MARKERS']; ?> </span><span id="side_panel_places_markers_num"></span></a></li>
			<li><a href="#side_panel_places_routes" id="side_panel_places_routes_tab"><span><? echo $la['ROUTES']; ?> </span><span id="side_panel_places_routes_num"></span></a></li>
			<li><a href="#side_panel_places_zones"><span><? echo $la['ZONES']; ?> </span><span id="side_panel_places_zones_num"></span></a></li>
		</ul>
		
		<div id="side_panel_places_markers">
			<div id="side_panel_places_marker_list">
				<table id="side_panel_places_marker_list_grid"></table>
				<div id="side_panel_places_marker_list_grid_pager"></div>
			</div>
		</div>
		
		<div id="side_panel_places_routes">
			<div id="side_panel_places_route_list">
				<table id="side_panel_places_route_list_grid"></table>
				<div id="side_panel_places_route_list_grid_pager"></div>
			</div>
		</div>
		
		<div id="side_panel_places_zones">
			<div id="side_panel_places_zone_list">
				<table id="side_panel_places_zone_list_grid"></table>
				<div id="side_panel_places_zone_list_grid_pager"></div>
			</div>
		</div>
	</div>
  </div>
</div>

<div class="dropdown">
<a href="#side_panel_history"><button onclick="myFunction4()" class="dropbtn" style="
    padding-left: 14px; background-color: #FF9149!important;
    padding-right: 14px;border-radius: 4px;
"><? echo truncateString($la['HISTORY'], 10); ?></button></a>  <div id="myDropdown4" class="dropdown-content" style="margin-left: -156px;">
    <div id="side_panel_history">
		<div id="side_panel_history_parameters">
		
			<div class="row2">
			 	<div class="width35"><? echo $la['SEARCH']; ?></div>
				<div class="width65"><input id="search_inputlisthistory" class="width100" placeholder="  Filter Objects"></div>
	   		</div>
			
			<div class="row2">
			    <div class="width35"><? echo $la['OBJECT']; ?></div>
			    <div class="width65"><select id="side_panel_history_object_list" class="width100"></select></div>
			</div>
			<div class="row2">
				<div class="width35"><? echo $la['FILTER'];?></div>
				<div class="width65">
				    <select id="side_panel_history_filter" class="width100" onchange="switchHistoryReportsDateFilter('history');">
						<option value="0" selected></option>
						<option value="1"><? echo $la['LAST_HOUR'];?></option>
						<option value="2"><? echo $la['TODAY'];?></option>
						<option value="3"><? echo $la['YESTERDAY'];?></option>
						<option value="4"><? echo $la['BEFORE_2_DAYS'];?></option>
						<option value="5"><? echo $la['BEFORE_3_DAYS'];?></option>
						<option value="6"><? echo $la['THIS_WEEK'];?></option>
						<option value="7"><? echo $la['LAST_WEEK'];?></option>
						<option value="8"><? echo $la['THIS_MONTH'];?></option>
						<option value="9"><? echo $la['LAST_MONTH'];?></option>
				    </select>
				</div>
			</div>
			<div class="row2">
				<div class="width35"><? echo $la['TIME_FROM']; ?></div>
				<div class="width31">
					<input readonly class="inputbox-calendar inputbox width100" id="side_panel_history_date_from" type="text" value=""/>
				</div>
				<div class="width2"></div>
				<div class="width15">
					<select class="width100" id="side_panel_history_hour_from">
					<? include ("inc/inc_dt.hours.php"); ?>
					</select>
				</div>
				<div class="width2"></div>
				<div class="width15">
					<select class="width100" id="side_panel_history_minute_from">
					<? include ("inc/inc_dt.minutes.php"); ?>
					</select>
				</div>
			</div>
			<div class="row2">
				<div class="width35"><? echo $la['TIME_TO']; ?></div>
				<div class="width31">
					<input readonly class="inputbox-calendar inputbox width100" id="side_panel_history_date_to" type="text" value=""/>
				</div>
				<div class="width2"></div>
				<div class="width15">
					<select class="width100" id="side_panel_history_hour_to">
					<? include ("inc/inc_dt.hours.php"); ?>
					</select>
				</div>
				<div class="width2"></div>
				<div class="width15">
					<select class="width100" id="side_panel_history_minute_to">
					<? include ("inc/inc_dt.minutes.php"); ?>
					</select>
				</div>
			</div>
			
			<div class="row3">
				<div class="width35"><? echo $la['STOPS']; ?></div>
				<div class="width31">
					<select id="side_panel_history_stop_duration" class="width100">
						<option value=1>> 1 min</option>
						<option value=2>> 2 min</option>
						<option value=5>> 5 min</option>
						<option value=10>> 10 min</option>
						<option value=20>> 20 min</option>
						<option value=30>> 30 min</option>
						<option value=60>> 1 h</option>
						<option value=120>> 2 h</option>
						<option value=300>> 5 h</option>
					</select>
				</div>
			</div>
	    
			<div class="row3">
				<input style="width: 100px; margin-right: 3px;" class="button" type="button" value="<? echo $la['SHOW']; ?>" onclick="historyLoadRoute();"/>
				<input style="width: 100px; margin-right: 3px;" class="button" type="button" value="<? echo $la['HIDE']; ?>" onclick="historyHideRoute();"/>
				<input style="width: 124px;" id="side_panel_history_import_export_action_menu_button" class="button" type="button" value="<? echo $la['IMPORT_EXPORT']; ?>"/>
			</div>
		</div>
	
		<div id="side_panel_history_route">
			<table id="side_panel_history_route_detail_list_grid"></table>
		</div>
		
		<div id="side_panel_history_dragbar">
		</div>
		
		<div id="side_panel_history_route_data_list">
			<table id="side_panel_history_route_data_list_grid"></table>
		</div>
	</div>
  </div>
</div>

<!-- <div class="dropdown">
	<button  onclick="displaydas();" style="
    padding-left: 14px; background-color: #1E9FF2!important;
    padding-right: 14px;border-radius: 4px;" id="ping" class="pincl"><img src="img/pin.png" id="chpinim"></button>
</div> -->
</div>
<div class="dropdown" style="float: right;">
<button onclick="displaydas();" style="
padding-left: 15px;
background-color: #1E9FF2!important;
padding-right: 14px;
border-radius: 6px;
margin-right: 67px;
margin-top: -17px;" id="ping" class="pincl"><img src="img/pin.png" id="chpinim"></button>
</div>
</div>
    
	<ul class="left-menuv" style="display:none;">
		<? if ($gsValues['SHOW_ABOUT'] == 'true') { ?>
		<li class="about-btn">
			<a href="#" onclick="$('#dialog_about').dialog('open');" title="<? echo $la['ABOUT']; ?>">
				<img src="theme/images/globe.svg" border="0"/>
			</a>
		</li>
		<? } ?>
		<li>
			<a class="help_btn" href="<? echo $gsValues['URL_HELP']; ?>" target="_blank" title="<? echo $la['HELP']; ?>">
				<img src="theme/images/info.svg" border="0"/>
			</a>
		</li>
		<li>
			<a class="settings_btn" href="#" onclick="settingsOpen();" title="<? echo $la['SETTINGS']; ?>">
				<img src="theme/images/settings.svg" border="0"/>
			</a>
		</li>
		<li>
			<a class="point_btn" href="#" onclick="$('#dialog_show_point').dialog('open');" title="<? echo $la['SHOW_POINT']; ?>">
				<img src="theme/images/marker.svg" border="0"/>
			</a>
		</li>
		<li>
			<a class="search_btn" href="#" onclick="$('#dialog_address_search').dialog('open');" title="<? echo $la['ADDRESS_SEARCH']; ?>">
				<img src="theme/images/search.svg" border="0"/>
			</a>
		</li>
		<? if ($_SESSION["privileges_reports"] == true){?>
		<li>
			<a class="report_btn" href="#" onclick="reportsOpen();" title="<? echo $la['REPORTS']; ?>">
				<img src="theme/images/report.svg" border="0"/>
			</a>
		</li>

		<? } ?>
		<? if ($_SESSION["privileges_rilogbook"] == true){?>
		<li>
			<a class="rilogbook_btn" href="#" onclick="rilogbookOpen();" title="<? echo $la['RFID_AND_IBUTTON_LOGBOOK']; ?>">
				<img src="theme/images/logbook.svg" border="0"/>
			</a>
		</li>
		<? } ?>
		<? if ($_SESSION["privileges_dtc"] == true){?>
		<li>
			<a class="dtc_btn" href="#" onclick="dtcOpen();" title="<? echo $la['DIAGNOSTIC_TROUBLE_CODES']; ?>">
				<img src="theme/images/dtc.svg" border="0"/>
			</a>
		</li>
		<? } ?>
		<? if ($_SESSION["privileges_object_control"] == true){?>
		<li>
			<a class="cmd_btn" href="#" onclick="cmdOpen();" title="<? echo $la['OBJECT_CONTROL']; ?>">
				<img src="theme/images/cmd.svg" border="0"/>
			</a>
		</li>
		<? } ?>
		<? if ($_SESSION["privileges_image_gallery"] == true){?>
		<li>
			<a class="gallery_btn" href="#" onclick="imgOpen();" title="<? echo $la['IMAGE_GALLERY']; ?>">
				<img src="theme/images/gallery.svg" border="0"/>
			</a>
		</li>
		<? } ?>
		<? if ($_SESSION["privileges_chat"] == true){?>
		<li>
			<a class="chat_btn" href="#" onclick="chatOpen();" title="<? echo $la['CHAT']; ?>">
				<img class="float-left" src="theme/images/chat.svg" border="0"/>
				<div id="chat_msg_count" class="chat-msg-count float-right">0</div>
			</a>
		</li>
		<? } ?>
	</ul>
    
	<ul class="right-menuv" style="display:none;">
		<li class="select-language <? if ($_SESSION["cpanel_privileges"]){?>cp<? }?>">
			<select id="system_language" onChange="switchLanguageTracking();">
			<? echo getLanguageList(); ?>
			</select>
		</li>
		<? if ($_SESSION["cpanel_privileges"]){?>
		<li class="cpanel-btn">
			<a href="cpanel.php" title="CPanel">
				<img src="theme/images/cogs-white.svg" border="0"/>
			</a>
		</li>
		<? }?>
		<? if ($_SESSION["billing"] == true){?>
		<li class="billing-btn">
			<a href="#" onclick="billingOpen();" title="<? echo $la['BILLING']; ?>">
				<img class="float-left" src="theme/images/cart-white.svg" border="0"/>
				<div id="billing_plan_count" class="billing-plan-count float-right">0</div>
			</a>
		</li>
		<? }?>
		<li style=" ">
			<a class="user-btn" href="#" onclick="settingsOpenUser();" title="<? echo $la['MY_ACCOUNT']; ?>">
				<img src="theme/images/user.svg" border="0" style="padding-top: 1px; padding-bottom: 17px; height: 27px;" />
				<span class="user-btn-text"><? echo truncateString($_SESSION["username"], 10);?></span>
			</a>
		</li>
		<li>
			<a class="mobile_btn" href="mobile/tracking.php" title="<? echo $la['MOBILE_VERSION']; ?>">
				<img src="theme/images/mobile.svg" border="0"/>
			</a>
		</li>
		<li class="logout-btn">
			<a href="#" onclick="connectLogout();" title="<? echo $la['LOGOUT']; ?>">
				<img src="theme/images/logout.svg" border="0"/>
			</a>
		</li>
	</ul>
</div>

<nav class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-right scrollbar" id="cbp-spmenu-s2" style="overflow: scroll; display: block;">
			
	<? if ($_SESSION["cpanel_privileges"]){?>
	<a  href="cpanel.php" class="waves-effect hvr-bounce-to-right3 blue1"><i class="demo-icon icon-rebel icong "></i><span>CPanel</span></a>
	<?  }?>
	<a href="#" id="tglDBMV" class="waves-effect hvr-bounce-to-right red1" > <i class='demo-icon icon-map icong '></i><span><? echo $la['GOTODASHBOARD']; ?></span></a>
				
	<? if ($_SESSION["ambulance"] == 'true'){?>
	<a href="#" class="waves-effect hvr-bounce-to-right3 color4" onclick="OpenBooking();" ><i class="demo-icon la la-ambulance icong"></i><span><? echo $la['AMBULANCE_BOOKING']; ?></span></a>
	<?  }?>  	
	<a href="#" id="settings_fullview" class="waves-effect hvr-bounce-to-right9 color8" onclick="settingsOpen();" ><i class="demo-icon fa fa-cogs fa-3x icong "></i><span><? echo $la['SETTINGS']; ?></span></a>	
	<? if ($_SESSION["privileges_reports"] == true){?>
	<a href="#" id="report_fullview" class="waves-effect hvr-bounce-to-right5 color3" onclick="reportsOpen();" ><i class="demo-icon fa fa-line-chart fa-3x icong "></i><span><? echo $la['REPORTS']; ?></span></a>
	<?}?>
	<?if ($_SESSION["ambulance"] == 'true'){?>
	<a href="#" class="waves-effect hvr-bounce-to-right3 blue1"  onclick="reportsOpenAmb();" ><i class="demo-icon fa fa-ambulance fa-3x icong "></i><span><? echo $la['AMBULANCE'].' '.$la['REPORTS']; ?></span></a>
	<?}?>
	   <? if ($_SESSION["emergency_alert"] == true){?>
	 <?//if($_SESSION["user_id"]=="1035" || ($_SESSION["manager_id"]=="1035" && $_SESSION["privileges"] == 'subuser') || $_SESSION["cpanel_privileges"] == 'admin' || $_SESSION["cpanel_privileges"] == 'super_admin'  ){?>
	 <a  href="#" id="sosalert_fullview" onclick="boardingOpen();" class="waves-effect hvr-bounce-to-right red1"><i class="demo-icon fa fa-bullhorn fa-3x icong"></i><span><? echo $la['EMERGENCY_ALERT']; ?></span></a>
	<?}?>
	 
	<?if ($_SESSION["boarding_point_alert_system"]==true){?>
	<a  href="#" id="boarding_fullview" onclick="boardingOpen();" class="waves-effect hvr-bounce-to-right1 indigo1"><i class="demo-icon fa fa-road fa-3x icong "></i><span><? echo $la['BOARDING']; ?></span></a>
	<? }?>
	<!--
	<a  href="#" id="rfid_fullview" onclick="rfidopen();" class="waves-effect hvr-bounce-to-right2 color1"><i class="demo-icon fa fa-credit-card-alt  icong "></i><span><? echo $la['RFID']; ?></span></a>
	-->
	<? if ($_SESSION["send_command"] == true || ($_SESSION["send_command"] == false &&  isset($_SESSION["cpanel_user_id"]))){?>
	<a href="#"  id="sendcommand_fullview" onclick="cmdOpennew();" class="waves-effect hvr-bounce-to-right8 color6"><i class="demo-icon fa fa-terminal fa-3x icong "></i><span><? echo $la['SEND_COMMAND']; ?></span></a>
	<?  }?>
	
	<? if ($_SESSION["privileges_object_control"] == true){?>
	<!-- <a href="#" id="top_panel_button_object_control color2"  onclick="cmdOpen();" class="waves-effect hvr-bounce-to-right4"><i class="demo-icon la la-code icong "></i><span><? echo $la['OBJECT_CONTROL']; ?></span></a> -->
	<a href="#" id="objectcontrol_fullview"  onclick="cmdOpen();" class="waves-effect hvr-bounce-to-right4"><i class="demo-icon fa fa-code fa-3x icong "></i><span><? echo $la['OBJECT_CONTROL']; ?></span></a>
	<?}?>
	 <? if ($_SESSION["privileges_rilogbook"] == true){?>
	<a class="report_btn hvr-bounce-to-right6 color4" id="logbook_fullview"  href="#" onclick="rilogbookOpen();"><i class="demo-icon fa fa-object-group fa-3x icong "></i> <span><? echo $la['RFID_AND_IBUTTON_LOGBOOK']; ?></span>	  </a>
	<?  }?>

	 <? if ($_SESSION["privileges_image_gallery"] == true){?>
	<a href="#" id="imagegallery_fullview" class="waves-effect hvr-bounce-to-right7 color5" onclick="imgOpen();" ><i class="demo-icon fa fa-file-image-o fa-3x icong "></i><span><? echo $la['IMAGE_GALLERY']; ?></span></a>
	<?}?>

	<a  href="mobile/tracking.php" class="waves-effect hvr-bounce-to-right2 color1"><i class="demo-icon fa fa-android fa-3x icong "></i><span><? echo $la['MOBILE_VERSION']; ?></span></a>

	<a href="<? echo $gsValues['URL_HELP']; ?>" class="waves-effect hvr-bounce-to-right1 indigo1"><i class="demo-icon fa fa-handshake-o fa-3x icong "> </i><span><? echo $la['HELP']; ?></span></a>
	

   <!--  <?// if ($_SESSION["billing"] == true)
	   {?>
	   <a  href="#" onclick="billingOpen();" title="<? echo $la['BILLING']; ?>" class="waves-effect "><i class="demo-icon icon-cart-plus icong"></i><span><? echo $la['BILLING']; ?></span></a>
	   <?  }?> -->
	
	<a href="#" class="waves-effect hvr-bounce-to-right red1" onclick="connectLogout();"><i class="demo-icon fa fa-power-off fa-3x icong "></i><span><? echo $la['LOGOUT']; ?></span></a>
			
</nav>
<div id="bottom_panel" style="left: 127px; height: auto !important;">
	<div id="bottom_panel_tabs" style="height: 100%;">
		<ul>           
		    <li><a href="#bottom_panel_graph"><? echo $la['GRAPH']; ?></a></li>
		    <li><a href="#bottom_panel_msg"><? echo $la['MESSAGES']; ?></a></li>
  		    <li><a href="#bottom_panel_historytrack"><? echo $la['HISTORYTRACKA']; ?> <a hre="#" id="excelvetri"  onClick ="$('#bottom_panel_historytrack_list_grid').tableExport({type:'excel',escape:'false',ignoreColumn:'[0]'});" > <img src="exp/icons/xls.png" width="16px" height="16px" ></a> </a>
  		    <li style="float:right;right: 13px;font-size: 20px;" onclick='historyHideRoute()' title="<? echo $la['HIDE']; ?>"><i class="demo-icon icon-resize-small"></i></li>  		    
  		     <!-- code done by vetrivelht-->
		</ul>
		<div id="bottom_panel_graph">			
			<div class="graph-controls">
				<div class="graph-controls-left">
					<select style="min-width:100px;" id="bottom_panel_graph_data_source" onchange="historyRouteChangeGraphSource();"></select>					
					<a href="#" onclick="historyRoutePlay();" title="<? echo $la['PLAY'];?>">
						<img src="theme/images/play.svg" width="10px" border="0"/>
					</a>
									    
					<a href="#" onclick="historyRoutePause();" title="<? echo $la['PAUSE'];?>">
						<img src="theme/images/pause.svg" width="10px" border="0"/>
					</a>
				    
					<a href="#" onclick="historyRouteStop();" title="<? echo $la['STOP'];?>">
						<img src="theme/images/stop.svg" width="10px" border="0"/>
					</a>					
					<select id="bottom_panel_graph_play_speed">
						<option value=1>x1</option>
						<option value=2>x2</option>
						<option value=3>x3</option>
						<option value=4>x4</option>
						<option value=5>x5</option>
						<option value=6>x6</option>
					</select>
				</div>
				<div class="graph-controls-right">
					<span id="bottom_panel_graph_label"></span>
					
					<a href="#" onclick="graphPanLeft();" title="<? echo $la['PAN_LEFT'];?>">
						<img src="theme/images/arrow-left.svg" width="10px" border="0"/>
					</a>
					
					<a href="#" onclick="graphPanRight();" title="<? echo $la['PAN_RIGHT'];?>">
						<img src="theme/images/arrow-right.svg" width="10px" border="0"/>
					</a>
					  
					<a href="#" onclick="graphZoomIn();" title="<? echo $la['ZOOM_IN'];?>">
						<img src="theme/images/plus.svg" width="10px" border="0"/>
					</a>
					
					<a href="#" onclick="graphZoomOut();" title="<? echo $la['ZOOM_OUT'];?>">
						<img src="theme/images/minus.svg" width="10px" border="0"/>
					</a>
				</div> 
			</div>
			
			<div id="bottom_panel_graph_plot"></div>
		</div>
		
		<div id="bottom_panel_msg">
			<table id="bottom_panel_msg_list_grid"></table>
			<div id="bottom_panel_msg_list_grid_pager"></div>
		</div>
		
		<div id="bottom_panel_historytrack">
        	<table id="bottom_panel_historytrack_list_grid"></table>
        	<div id="bottom_panel_historytrack_list_grid_pager"></div>
    	</div>
        <!-- code done by vetrivelht-->        
	</div>
</div>

<a href="#" onclick="showHideLeftPanel();">
	<div id="side_panel_dragbar">    
	</div>
</a>

<a href="#" onclick="showBottomPanel();">
	<div id="bottom_panel_dragbar" style="display: none;">    
	</div>
</a>


    

