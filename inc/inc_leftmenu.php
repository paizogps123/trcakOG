     <div class="topbar" style="height:55px;">
            <div class="topbar-left" style="background:white;height:55px">
              
                
                	<a href="#" class="logo"><img width="210px" height="53px" src="<? echo $gsValues['URL_ROOT']; ?>/img/logo.png"> </a>
                	
              
            </div>
            <div class="navbar navbar-default" role="navigation" style="height:55px;">
                <div class="container">
                    <div class="">   
                       <!--  <div class="pull-left">
                            <button class="button-menu-mobile open-left"><i class="fa fa-bars"></i>
                            </button> <span class="clearfix"></span>
                       </div>-->
                        <ul class="nav navbar-nav navbar-right pull-right">
                            <li class="hidden-xs"><a style="line-height:53px" href="#" onclick="settingsOpenUser();"  title="<? echo $la['MY_ACCOUNT']; ?>" class="waves-effect waves-light"><i class="md md-account-box"></i> <? echo truncateString($_SESSION["username"], 15);?> </a>
                            </li>
                          <li class="hidden-xs"><a style="line-height:53px"  href="#" onclick="connectLogout();"   class="waves-effect waves-light" title="<? echo $la['LOGOUT']; ?>"><i class="md  md-settings-power"></i>  </a>
                            </li>                                                      
                        </ul>
                    </div>
                </div>
            </div>
        </div>
 
  <div class="left side-menu" style="top:55px;">
   <nav class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-leht cbp-spmenu-open" id="cbp-spmenu-s2">

	
			<a href="#" id="tglDBMV" class="waves-effect "> <i class='demo-icon icon-eercast'></i><span><? echo $la['GOTODASHBOARD']; ?></span></a>
			<a onclick="fnmenuright(this)" ><i class="ion-android-close"></i><span>Close Menu</span></a>
			<a href="#" onclick="$('#alldiv').hide()"  class="waves-effect "><i class="ion-android-earth"></i><span><? echo $la['MAPVIEW']; ?></span></a>
            <a href="#" class="waves-effect " onclick="OpenBooking();" ><i class="demo-icon icon-podcast"></i><span><? echo $la['AMBULANCE_BOOKING']; ?></span></a>
			<a href="<? echo $gsValues['URL_HELP']; ?>" class="waves-effect "><i class="demo-icon icon-handshake-o"> </i><span><? echo $la['HELP']; ?></span></a>
			<a href="#" class="waves-effect " onclick="settingsOpen();" ><i class="demo-icon icon-snowflake-o"></i><span><? echo $la['SETTINGS']; ?></span></a>
			<a href="#" id="top_panel_button_object_control"  onclick="cmdOpen();" class="waves-effect "><i class="demo-icon icon-code"></i><span><? echo $la['OBJECT_CONTROL']; ?></span></a>
			  <? if ($_SESSION["cpanel_privileges"]){?>
            <a href="#"  onclick="cmdOpennew();" class="waves-effect "><i class="demo-icon icon-terminal"></i><span><? echo $la['SEND_COMMAND']; ?></span></a>
            <?  }?>
            <a class="report_btn" href="#" onclick="rilogbookOpen();"><i class="demo-icon icon-file-code"></i> <span><? echo $la['RFID_AND_IBUTTON_LOGBOOK']; ?></span>	  </a>
			<a href="#" class="waves-effect " onclick="imgOpen();" ><i class="demo-icon icon-camera-alt"></i><span><? echo $la['IMAGE_GALLERY']; ?></span></a>
			<a href="#" class="waves-effect " onclick="historyReportsOpen();" ><i class="demo-icon icon-chart-area"></i><span><? echo $la['REPORTS']; ?></span></a>
			
			
			<a href="#" class="waves-effect " onclick="historyReportsOpen();" ><i class="demo-icon icon-chart-area"></i><span><? echo $la['REPORTS']; ?></span></a>
			
			
			<? // if ($_SESSION["cpanel_privileges"]){ ?>
            <a  href="#" onclick="rfidopen();" class="waves-effect "><i class="demo-icon icon-credit-card"></i><span><? echo $la['RFID']; ?></span></a>
			<? //}?>			
			<?// if ($_SESSION["cpanel_privileges"]){?>
            <a  href="#" onclick="boardingOpen();" class="waves-effect "><i class="demo-icon icon-road"></i><span><? echo $la['BOARDING']; ?></span></a>
			<? //}?>
			<a  href="mobile/tracking.php" class="waves-effect "><i class="demo-icon icon-mobile"></i><span><? echo $la['MOBILE_VERSION']; ?></span></a>
            <? if ($_SESSION["cpanel_privileges"]){?>
            <a  href="cpanel.php" class="waves-effect "><i class="demo-icon icon-rebel"></i><span>CPanel</span></a>
            <?  }?>
            <a href="#" class="waves-effect " onclick="connectLogout();"><i class="demo-icon icon-logout"></i><span><? echo $la['LOGOUT']; ?></span></a>
			<!-- <a href="#" onclick="fnmenuright(this)"  class="waves-effect "><i class="fa fa-times"></i><span><? echo $la['CLOSE']; ?></span></a> -->
			
</nav>      
</div>
     
			
                                
							