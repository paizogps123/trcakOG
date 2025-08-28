<div id="dialog_settings_template_properties" class="newdashboard" title="<? echo $la['TEMPLATE_PROPERTIES'];?>">
    <div class="row">
        <div class="block width60">
            <div class="container">
                <div class="title-block">
                    <? echo $la['TEMPLATE']; ?>
                </div>
                <div class="row2">
                    <div class="width30">
                        <? echo $la['NAME']; ?>
                    </div>
                    <div class="width70"><input id="dialog_settings_template_name" class="inputbox" type="text" value="" maxlength="50"></div>
                </div>
                <div class="row2">
                    <div class="width30">
                        <? echo $la['DESCRIPTION']; ?>
                    </div>
                    <div class="width70"><textarea id="dialog_settings_template_desc" class="inputbox" style="height:50px;" maxlength="100"></textarea></div>
                </div>
                <div class="row2">
                    <div class="width30">
                        <? echo $la['SUBJECT']; ?>
                    </div>
                    <div class="width70"><input id="dialog_settings_template_subject" class="inputbox" maxlength="100"></div>
                </div>
                <div class="row2">
                    <div class="width30">
                        <? echo $la['MESSAGE']; ?>
                    </div>
                    <div class="width70"><textarea id="dialog_settings_template_message" class="inputbox" style="height:200px;" maxlength="2000"></textarea></div>
                </div>
            </div>
        </div>
        <div class="block width40">
            <div class="container last">
                <div class="title-block">
                    <? echo $la['VARIABLES']; ?>
                </div>
                <div class="row2">
                    <div style="height: 305px; overflow-y: scroll;">
                        <div class="row">
                            <? echo $la['VAR_TEMPLATE_NAME']; ?>
                        </div>
                        <div class="row">
                            <? echo $la['VAR_TEMPLATE_IMEI']; ?>
                        </div>
                        <div class="row">
                            <? echo $la['VAR_TEMPLATE_EVENT']; ?>
                        </div>

                        <div class="row">
                            <? echo $la['VAR_TEMPLATE_LAT']; ?>
                        </div>
                        <div class="row">
                            <? echo $la['VAR_TEMPLATE_LNG']; ?>
                        </div>
                        <div class="row">
                            <? echo $la['VAR_TEMPLATE_ADDRESS']; ?>
                        </div>
                        <div class="row">
                            <? echo $la['VAR_TEMPLATE_SPEED']; ?>
                        </div>
                        <div class="row">
                            <? echo $la['VAR_TEMPLATE_ALT']; ?>
                        </div>
                        <div class="row">
                            <? echo $la['VAR_TEMPLATE_ANGLE']; ?>
                        </div>
                        <div class="row">
                            <? echo $la['VAR_TEMPLATE_DT_POS']; ?>
                        </div>
                        <div class="row">
                            <? echo $la['VAR_TEMPLATE_DT_SER']; ?>
                        </div>
                        <div class="row">
                            <? echo $la['VAR_TEMPLATE_G_MAP']; ?>
                        </div>

                        <div class="row">
                            <? echo $la['VAR_TEMPLATE_TR_MODEL']; ?>
                        </div>
                        <div class="row">
                            <? echo $la['VAR_TEMPLATE_PL_NUM']; ?>
                        </div>
                        <div class="row">
                            <? echo $la['VAR_TEMPLATE_DRIVER']; ?>
                        </div>
                        <div class="row">
                            <? echo $la['VAR_TEMPLATE_TRAILER']; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <center>
        <input class="button icon-save icon" type="button" onclick="settingsTemplateProperties('save');" value="<? echo $la['SAVE']; ?>" />&nbsp;
        <input class="button icon-close icon" type="button" onclick="settingsTemplateProperties('cancel');" value="<? echo $la['CANCEL']; ?>" />
    </center>
</div>

<div id="dialog_settings_subaccount_properties" title="<? echo $la['SUB_ACCOUNT_PROPERTIES'];?>" style="overflow: scroll;">
    <div class="row">
        <div class="block width50">
            <div class="container">
                <div class="title-block">
                    <? echo $la['SUB_ACCOUNT']; ?>
                </div>
                <div class="row2">
                    <div class="width40">
                        <? echo $la['ACTIVE']; ?>
                    </div>
                    <div class="width60"><input id="dialog_settings_subaccount_active" type="checkbox" checked="checked" ></div>
                     <!-- <div class="width1"></div> -->
                 </div>
                 <div class="row2">
                    <div class="width40">
                        <? echo $la['TYPE']; ?>
                    </div>
                    <div class="width60">
                        <select class="width100" id="dialog_settings_subaccount_type" style="height:25px;" >
                            <option><? echo $la['SUB_ACCOUNT']; ?></option>
                            <option><? echo $la['DRIVER_ACCOUNT']; ?></option>
							<option><? echo $la['SUPERVISOR_ACCOUNT']; ?></option>
                        </select>
                    </div>
                </div>

                <div class="row2">
                    <div class="width40">
                        <? echo $la['EMAIL']; ?>
                    </div>
                    <div class="width60"><input id="dialog_settings_subaccount_email" class="inputbox" type="text" value="" maxlength="50" ></div>
                </div>
                <div class="row2">
                    <!-- <div class="width1"></div> -->
                    <div class="width40">
                        <? echo $la['PASSWORD']; ?>
                    </div>
                    <div class="width60"><input id="dialog_settings_subaccount_password" class="inputbox" type="text" value="" maxlength="20" ></div>
                </div>
                <div class="row2">
                    <div class="width40">
                        <? echo $la['MOBILENO']; ?>
                    </div>
                    <div class="width60"><input id="dialog_settings_subaccount_mobile_num" class="inputbox" type="text" value="" maxlength="10" /></div>
                </div>
                <div class="row2">
                    <!-- <div class="width1"></div> -->
                    <div class="width40">
                        <? echo $la['EXPIRE_ON']; ?>
                    </div>
                    <div class="width15">
                        <input id="dialog_settings_subaccount_expire" type="checkbox" class="checkbox" onchange="settingsSubaccountCheck();" >
                    </div>
                    <div class="width45">
                        <input readonly class="inputbox-calendar inputbox width100" id="dialog_settings_subaccount_expire_dt" >
                    </div>
                </div>
                <div class="row2">
                    <div class="width100">
                        <table style="width: 100%;">
                            <thead>
                                <tr style='text-align: center;height: 30px;'>
                                    <th><? echo $la['NAME']; ?></th>
                                    <th><? echo $la['ADD']; ?></th>
                                    <th><? echo $la['EDIT']; ?></th>
                                    <th><? echo $la['DELETE']; ?></th>
                                </tr>
                            </thead>
                            <tbody>
                               <!--  <tr style='text-align: center;height: 30px;'>
                                    <th><? echo $la['OBJECTS']; ?></th>
                                    <td><input type="checkbox" id="dialog_settings_subaccount_object_add"></div></td>
                                    <td><input type="checkbox" id="dialog_settings_subaccount_object_edit"></div></td>
                                    <td><input type="checkbox" id="dialog_settings_subaccount_object_delete"></div></td>
                                </tr>
                                <tr style='text-align: center;height: 30px;'>
                                    <th><? echo $la['GROUP']; ?></th>
                                    <td><input type="checkbox" id="dialog_settings_subaccount_group_add"></div></td>
                                    <td><input type="checkbox" id="dialog_settings_subaccount_group_edit"></div></td>
                                    <td><input type="checkbox" id="dialog_settings_subaccount_group_delete"></div></td>
                                </tr>
                                <tr style='text-align: center;height: 30px;'>
                                    <th><? echo $la['EVENTS']; ?></th>
                                    <td><input type="checkbox" id="dialog_settings_subaccount_event_add"></div></td>
                                    <td><input type="checkbox" id="dialog_settings_subaccount_event_edit"></div></td>
                                    <td><input type="checkbox" id="dialog_settings_subaccount_event_delete"></div></td>
                                </tr> -->
                                <tr style='text-align: center;height: 30px;'>
                                    <th><? echo $la['ZONES']; ?></th>
                                    <td><input type="checkbox" id="dialog_settings_subaccount_zone_add" <?php if(checkSettingsPrivileges('zones','add')!=true){ echo 'disabled=""';}?> ></div></td>
                                    <td><input type="checkbox" id="dialog_settings_subaccount_zone_edit" <?php if(checkSettingsPrivileges('zones','edit')!=true){ echo 'disabled=""';}?> ></div></td>
                                    <td><input type="checkbox" id="dialog_settings_subaccount_zone_delete" <?php if(checkSettingsPrivileges('zones','delete')!=true){ echo 'disabled=""';}?> ></div></td>
                                </tr>
                                <tr style='text-align: center;height: 30px;'>
                                    <th><? echo $la['ROUTES']; ?></th>
                                    <td><input type="checkbox" id="dialog_settings_subaccount_route_add" <?php if(checkSettingsPrivileges('route','add')!=true){ echo 'disabled=""';}?> ></div></td>
                                    <td><input type="checkbox" id="dialog_settings_subaccount_route_edit" <?php if(checkSettingsPrivileges('route','edit')!=true){ echo 'disabled=""';}?> ></div></td>
                                    <td><input type="checkbox" id="dialog_settings_subaccount_route_delete"  <?php if(checkSettingsPrivileges('route','delete')!=true){ echo 'disabled=""';}?> ></div></td>
                                </tr>
                                <tr style='text-align: center;height: 30px;'>
                                    <th><? echo $la['MARKERS']; ?></th>
                                    <td><input type="checkbox" id="dialog_settings_subaccount_marker_add" <?php if(checkSettingsPrivileges('markers','add')!=true){ echo 'disabled=""';}?> ></div></td>
                                    <td><input type="checkbox" id="dialog_settings_subaccount_marker_edit" <?php if(checkSettingsPrivileges('markers','edit')!=true){ echo 'disabled=""';}?> ></div></td>
                                    <td><input type="checkbox" id="dialog_settings_subaccount_marker_delete" <?php if(checkSettingsPrivileges('markers','delete')!=true){ echo 'disabled=""';}?> ></div></td>
                                </tr>
                                <!-- <tr style='text-align: center;height: 30px;'>
                                    <th><? echo $la['DUPLICATE']; ?></th>
                                    <td><input type="checkbox" id="dialog_settings_subaccount_duplicate_add" disabled=""></div></td>
                                    <td><input type="checkbox" id="dialog_settings_subaccount_duplicate_edit"></div></td>
                                    <td><input type="checkbox" id="dialog_settings_subaccount_duplicate_delete" disabled=""></div></td>
                                </tr>
                                <tr style='text-align: center;height: 30px;'>
                                    <th><? echo $la['CLEAR_HISTORY']; ?></th>
                                    <td><input type="checkbox" id="dialog_settings_subaccount_clr_history_add" disabled=""></div></td>
                                    <td><input type="checkbox" id="dialog_settings_subaccount_clr_history_edit" disabled=""></div></td>
                                    <td><input type="checkbox" id="dialog_settings_subaccount_clr_history_delete"></div></td>
                                </tr> -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <!-- </div>
     <div class="row"> -->
        <div class="block width50">
            <div class="container last">
                <div class="title-block">
                    <? echo $la['PRIVILEGES']; ?>
                </div>
                <div class="row2">
                    <div class="width40">
                        <? echo $la['HISTORY']; ?>
                    </div>
                    <div class="width10"><input id="dialog_settings_subaccount_history" type="checkbox"/></div>
                     <?$obconsty="";if ($_SESSION["privileges_object_control"] != true){ $obconsty='style="display:none;"'; }?>

                     <?$reportsty="";if ($_SESSION["privileges_reports"] != 'true'){ $reportsty='style="display:none;"'; }?>

                     <?$imgsty="";if ($_SESSION["privileges_image_gallery"] != 'true'){ $imgsty='style="display:none;"'; }?>

                     <?$rlogsty="";if ($_SESSION["privileges_rilogbook"] != 'true'){ $rlogsty='style="display:none;"'; }?>

                     <?$balertsty="";if ($_SESSION["boarding_point_alert_system"] != 'true'){ $balertsty='style="display:none;"'; }?>

                     <?$smdsty="";if ($_SESSION["send_command"] != 'true'){ $smdsty='style="display:none;"'; }?>

                     <?$sossty="";if ($_SESSION["emergency_alert"] != 'true'){ $sossty='style="display:none;"'; }?>

                     <?$livetrip="";if ($_SESSION["privileges_live_tripreport"] != 'true'){ $livetrip='style="display:none;"'; }?>
                     
                    <div <?echo $obconsty;?> class="width40">
                        <? echo $la['OBJECT_CONTROL']; ?>
                    </div>
                    <div <?echo $obconsty;?> class="width10"><input id="dialog_settings_subaccount_object_control" type="checkbox"/>
                    </div>
                    <div <?echo $reportsty;?> class="width40">
                        <? echo $la['REPORTS']; ?>
                    </div>
                    <div <?echo $reportsty;?> class="width10"><input id="dialog_settings_subaccount_reports" type="checkbox" /></div>
                    <div <?echo $imgsty;?> class="width40">
                        <? echo $la['IMAGE_GALLERY']; ?>
                    </div>
                    <div <?echo $imgsty;?> class="width10"><input id="dialog_settings_subaccount_image_gallery" type="checkbox"/></div>
                    <div <?echo $rlogsty;?> class="width40">
                        <? echo $la['RFID_AND_IBUTTON_LOGBOOK']; ?>
                    </div>
                    <div <?echo $rlogsty;?> class="width10"><input id="dialog_settings_subaccount_rilogbook" type="checkbox"/></div>

                    <div class="width40" style="display: none">
                        <? echo $la['CHAT']; ?>
                    </div>
                    <div class="width10" style="display: none;"><input id="dialog_settings_subaccount_chat" type="checkbox"/></div>
                    <div <?echo $balertsty;?> class="width40">
                        <? echo $la['BOARDING']; ?>
                    </div>
                    <div  <?echo $balertsty;?> class="width10"><input id="dialog_settings_subaccount_boarding_point" type="checkbox" /></div>
                    <div <?echo $smdsty;?> class="width40">
                        <? echo $la['SEND_COMMAND']; ?>
                    </div>
                    <div <?echo $smdsty;?> class="width10"><input id="dialog_settings_subaccount_send_command" type="checkbox"/></div>
                    <div class="width40">
                        <? echo $la['DIAGNOSTIC_TROUBLE_CODES']; ?>
                    </div>
                    <div class="width10"><input id="dialog_settings_subaccount_dtc" type="checkbox"/></div>
                     <div <?echo $sossty;?> class="width40">
                        <? echo $la['EMERGENCY_ALERT']; ?>
                    </div>
                     <div <?echo $sossty;?> class="width10"><input id="dialog_settings_subaccount_sos" type="checkbox" /></div> 

                    <div <?echo $livetrip;?> class="width40">
                        <? echo $la['LIVE_TRIP_REPORT']; ?>
                    </div>
                     <div <?echo $livetrip;?> class="width10"><input id="dialog_settings_subaccount_live_tripreport" type="checkbox" /></div> 

                    <?$amb="";
					 if ($_SESSION["ambulance"] == 'true')
					 {$amb='style="display:contents;"';  }
					 else{ $amb='style="display:none;"'; }?>
                    <div <? echo $amb ?> class="width40">
                        <? echo $la['AMBULANCE']; ?>
                    </div>
                    <div <? echo $amb ?> class="width10"><input id="dialog_settings_subaccount_ambulamce" type="checkbox" /></div><br><br>
                </div>
            </div>
        <!-- </div>
        <div class="block width50"> -->
            <div class="container last">
                <div class="title-block">
                    <? echo $la['REPORT'],' ',$la['PRIVILEGES']; ?>
                </div>
                <div class="row2">
                    <select class="multi-select " id="subuser_generat_report_list" style="height:186px;" name="subuser_generat_report_list" data-plugin="multiselect" data-selectable-optgroup="true" multiple="multiple">
                       <?
                        $user_report=$_SESSION['listdata'];
                        $userreport_group=$_SESSION['report_group'];

                        for($ig=0;$ig<count($userreport_group);$ig++)
                        {
                        $arcount=chkreportgroup($user_report,$userreport_group[$ig]["gid"]);
                        if($arcount)
                        {
                            echo "<optgroup label='".$userreport_group[$ig]['group_name']."'>";
                           for($ir=0;$ir<count($user_report);$ir++)
                           {
                                if($user_report[$ir]['group_id']==$arcount && $user_report[$ir]["active"]=='A')
                                echo "<option  value='".$user_report[$ir]['rid']."' size='20'>".$la[$user_report[$ir]['report_lng']]."</option>";
                           }
                            echo "</optgroup>";
                        }

                        }
                        ?>

                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="objects-block block width25">
            <div class="container">
                <div class="title-block">
                    <? echo $la['OBJECTS'];?>
                </div>
                <select class="width100" id="dialog_settings_subaccount_available_objects" style="height:140px;" multiple="multiple" /></select>
            </div>
        </div>
        <div class="markers-block block width25">
            <div class="container">
                <div class="title-block">
                    <? echo $la['MARKERS'];?>
                </div>
                <select class="width100" id="dialog_settings_subaccount_available_markers" style="height:140px;" multiple="multiple"></select>
            </div>
        </div>
        <div class="routes-block block width25">
            <div class="container">
                <div class="title-block">
                    <? echo $la['ROUTES'];?>
                </div>
                <select class="width100" id="dialog_settings_subaccount_available_routes" style="height:140px;" multiple="multiple"></select>
            </div>
        </div>
        <div class="zones-block block width25">
            <div class="container last">
                <div class="title-block">
                    <? echo $la['ZONES'];?>
                </div>
                <select class="width100" id="dialog_settings_subaccount_available_zones" style="height:140px;" multiple="multiple"></select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="title-block">
            <? echo $la['ACCESS_VIA_URL']; ?>
        </div>
        <div class="row2">
            <div class="width20">
                <? echo $la['ACTIVE']; ?>
            </div>
            <div class="width80">
                <input id="dialog_settings_subaccount_au_active" type="checkbox" class="checkbox" />
            </div>
        </div>
        <div class="row2">
            <div class="width20">
                <? echo $la['URL_DESKTOP']; ?>
            </div>
            <div class="width80">
                <input class="inputbox" id="dialog_settings_subaccount_au" readonly />
            </div>
        </div>
        <div class="row2">
            <div class="width20">
                <? echo $la['URL_MOBILE']; ?>
            </div>
            <div class="width80">
                <input class="inputbox" id="dialog_settings_subaccount_au_mobile" readonly />
            </div>
        </div>
    </div>

    <center>
        <input class="button icon-save icon" type="button" onclick="settingsSubaccountProperties('save');" value="<? echo $la['SAVE']; ?>" />
        <input class="button icon-close icon" type="button" onclick="settingsSubaccountProperties('cancel');" value="<? echo $la['CANCEL']; ?>" />
    </center>
</div>

<div id="dialog_settings_event_properties" title="<? echo $la['EVENT_PROPERTIES'];?>">
    <div id="settings_event">
	<div class="block width100">
            <div class="block width25">
        <ul id="cmdmenu">
            <li><a href="#settings_event_main" class="hvr-sweep-to-bottom  ">
                    <? echo $la['MAIN']; ?></a></li>
            <li><a href="#settings_event_time" class="hvr-sweep-to-bottom1  ">
                    <? echo $la['TIME']; ?></a></li>
            <li><a href="#settings_event_objects" class="hvr-sweep-to-bottom2  ">
                    <? echo $la['OBJECTS']; ?></a></li>
            <li><a href="#settings_event_routes" class="hvr-sweep-to-bottom3  ">
                    <? echo $la['ROUTES']; ?></a></li>
            <li><a href="#settings_event_zones" class="hvr-sweep-to-bottom4  ">
                    <? echo $la['ZONES']; ?></a></li>
            <li><a href="#settings_event_notification" class="hvr-sweep-to-bottom5  ">
                    <? echo $la['NOTIFICATIONS']; ?></a></li>
            <li><a href="#settings_event_object_control" class="hvr-sweep-to-bottom6  ">
                    <? echo $la['OBJECT_CONTROL']; ?></a></li>
        </ul>
		</div>
		 <div class="block width75">
        <div id="settings_event_main">
            <div class="row">
                <div class="title-block">
                    <? echo $la['EVENT']; ?>
                </div>
                <div class="row2">
                    <div class="width50">
                        <? echo $la['ACTIVE']; ?>
                    </div>
                    <div class="width50"><input id="dialog_settings_event_active" type="checkbox" checked="checked" /></div>
                </div>
                <div class="row2">
                    <div class="width50">
                        <? echo $la['NAME']; ?>
                    </div>
                    <div class="width50"><input id="dialog_settings_event_name" class="inputbox" type="text" value="" maxlength="30" /></div>
                </div>
                <div class="row2">
                    <div class="width50">
                        <? echo $la['TYPE']; ?>
                    </div>
                    <div class="width50">
                        <select class="width100" id="dialog_settings_event_type" onchange="settingsEventSwitchType();" />
                        <option value="sos">
                            <? echo $la['SOS']; ?>
                        </option>                        
                        <option value="dstudent_sos"><? echo $la['PEOPLESOS']; ?>                       
                        </option>
                        <option value="unauthpeople">
                            <? echo $la['UNAUTHORIZEDPEOPLE']; ?>
                        </option>
                        <option value="bracon">
                            <? echo $la['BRACELET_ON']; ?>
                        </option>
                        <option value="bracoff">
                            <? echo $la['BRACELET_OFF']; ?>
                        </option>
                        <option value="mandown">
                            <? echo $la['MAN_DOWN']; ?>
                        </option>
                        <option value="shock">
                            <? echo $la['SHOCK']; ?>
                        </option>
                        <option value="tow">
                            <? echo $la['TOW']; ?>
                        </option>
                        <option value="pwrcut">
                            <? echo $la['POWER_CUT']; ?>
                        </option>
                        <option value="gpsantcut">
                            <? echo $la['GPS_ANTENNA_CUT']; ?>
                        </option>
                        <option value="jamming">
                            <? echo $la['SIGNAL_JAMMING']; ?>
                        </option>
                        <option value="lowdc">
                            <? echo $la['LOW_DC']; ?>
                        </option>
                        <option value="lowbat">
                            <? echo $la['LOW_BATTERY']; ?>
                        </option>
                        <option value="connyes">
                            <? echo $la['CONNECTION_YES']; ?>
                        </option>
                        <option value="connno">
                            <? echo $la['CONNECTION_NO']; ?>
                        </option>
                        <option value="gpsyes">
                            <? echo $la['GPS_YES']; ?>
                        </option>
                        <option value="gpsno">
                            <? echo $la['GPS_NO']; ?>
                        </option>
                        <option value="stopped">
                            <? echo $la['STOPPED']; ?>
                        </option>
                        <option value="moving">
                            <? echo $la['MOVING']; ?>
                        </option>
                        <option value="engidle">
                            <? echo $la['ENGINE_IDLE']; ?>
                        </option>
                        <option value="overspeed">
                            <? echo $la['OVERSPEED']; ?>
                        </option>
                        <option value="underspeed">
                            <? echo $la['UNDERSPEED']; ?>
                        </option>
                        <option value="haccel">
                            <? echo $la['HARSH_ACCELERATION']; ?>
                        </option>
                        <option value="hbrake">
                            <? echo $la['HARSH_BRAKING']; ?>
                        </option>
                        <option value="hbrake_server">
                            <? echo $la['HARSH_BRAKING_SERVER']; ?>
                        </option>
                        <option value="haccel_server">
                            <? echo $la['HARSH_ACCELERATION_SERVER']; ?>
                        </option>
                        <option value="hcorn">
                            <? echo $la['HARSH_CORNERING']; ?>
                        </option>
                        <option value="param">
                            <? echo $la['PARAMETER']; ?>
                        </option>
                        <option value="sensor">
                            <? echo $la['SENSOR']; ?>
                        </option>
                        <option value="service">
                            <? echo $la['SERVICE']; ?>
                        </option>
                        <option value="dtc">
                            <? echo $la['DIAGNOSTIC_TROUBLE_CODES']; ?>
                        </option>
                        <option value="triproutedivert">
                            <? echo $la['TRIPROUTEDEVIATION']; ?>
                        </option>
                        <option value="route_in">
                            <? echo $la['ROUTE_IN']; ?>
                        </option>
                        <option value="route_out">
                            <? echo $la['ROUTE_OUT']; ?>
                        </option>
                        <option value="zone_in">
                            <? echo $la['ZONE_IN']; ?>
                        </option>
                        <option value="zone_out">
                            <? echo $la['ZONE_OUT']; ?>
                        </option>

                        <option value="tanker_lowfuel">
                            <? echo $la['TANKER_LOW_FUEL']; ?>
                        </option>

                        <option value="vehicle_lowfuel">
                            <? echo $la['VEHICLE_LOW_FUEL']; ?>
                        </option>

                        <option value="toll_zone_in">
                            <? echo $la['TOLL_IN']; ?>
                        </option>
                        <option value="toll_zone_out">
                            <? echo $la['TOLL_OUT']; ?>
                        </option>
                        <option value="removal_alarm">
                            <? echo $la['REMOVAL_ALARM']; ?>
                        </option>

                        <!--code done by vetrivel -->
                        <option value="temp_abn">
                            <? echo $la['TEMP_ABN']; ?>
                        </option>
                        <option value="gpssta">
                            <? echo $la['GPSSTA']; ?>
                        </option>
                        <option value="mainpwrsta">
                            <? echo $la['MAINPWRSTA']; ?>
                        </option>
                        <option value="lowpwr">
                            <? echo $la['LOWPWR']; ?>
                        </option>
                        <option value="ud1">
                            <? echo $la['UD1']; ?>
                        </option>
                        <option value="dooropen">
                            <? echo $la['DOOROPEN']; ?>
                        </option>
                        <option value="ud4">
                            <? echo $la['UD4']; ?>
                        </option>
                        <option value="ud5">
                            <? echo $la['UD5']; ?>
                        </option>
                        <option value="crash">
                            <? echo $la['CRASH']; ?>
                        </option>
                        <option value="Shake">
                            <? echo $la['SHAKE']; ?>
                        </option>
                        <option value="fuelstolen">
                            <? echo $la['FUELSTOLEN']; ?>
                        </option>
                        <option value="lowfuel">
                            <? echo $la['LOWFUEL']; ?>
                        </option>
                        <option value="fueldiscnt">
                            <? echo $la['FUELDISCNT']; ?>
                        </option>
                        <option value="stopovrtime">
                            <? echo $la['STOPOVRTIME']; ?>
                        </option>
                        <option value="ud2">
                            <? echo $la['UD2']; ?>
                        </option>
                        <option value="aconidle">
                            <? echo $la['ACONENGIDLE']; ?>
                        </option>
                        </select>
                    </div>
                </div>
                <div class="row2">
                    <div class="width50" id="time_period_and_lowfuel_value">
                        <? echo $la['TIME_PERIOD_MIN']; ?>
                    </div>
                    <div class="width20"><input class="inputbox width100" id="dialog_settings_event_time_period" onkeypress="return isNumberKey(event);" type="text" value="" maxlength="4" disabled /></div>
                </div>
                <div class="row2">
                    <div class="width50">
                        <? echo $la['SPEED_LIMIT']; ?> (
                        <? echo $la["UNIT_SPEED"]; ?>)</div>
                    <div class="width20"><input class="inputbox width100" id="dialog_settings_event_speed_limit" onkeypress="return isNumberKey(event);" type="text" value="" maxlength="3" disabled /></div>
                </div>
                <div class="row2">
                    <div class="width50">
                        <? echo $la['PARAMETERS_AND_SENSORS']; ?>
                    </div>
                    <div class="width50">
                        <div id="dialog_settings_event_param_sensor_condition_list">
                            <table id="settings_event_param_sensor_condition_list_grid"></table>
                        </div>
                    </div>
                </div>
                <div class="row2">
                    <div class="width50"></div>
                    <div class="width15">
                        <select class="width100" id="dialog_settings_event_param_sensor_condition_src" /></select>
                    </div>
                    <div class="width1"></div>
                    <div class="width8">
                        <select class="width100" id="dialog_settings_event_param_sensor_condition_cn" />
                        <option value=""></option>
                        <option value="gr">></option>
                        <option value="eq">=</option>
                        <option value="lw">
                            <</option> </select> </div> <div class="width1">
                    </div>
                    <div class="width15">
                        <input class="inputbox width100" id="dialog_settings_event_param_sensor_condition_val" type="text" value="" />
                    </div>
                    <div class="width1"></div>
                    <div class="width9">
                        <input id="dialog_settings_event_param_sensor_condition_add" style="min-width: 0;width: 58px;padding-left: 19px !important;" class="width100 button icon-new icon" type="button" value="<? echo $la['ADD']; ?>" onclick="settingsEventConditionAdd();" disabled />
                    </div>
                </div>
            </div>
        </div>
        <div id="settings_event_time">
            <div class="row">
                <div class="title-block">
                    <? echo $la['TIME']; ?>
                </div>
                <div class="row2">
                    <div class="width50">
                        <? echo $la['DURATION_FROM_LAST_EVENT']; ?>
                    </div>
                    <div class="width5">
                        <input id="dialog_settings_event_duration_from_last_event" type="checkbox" class="checkbox" />
                    </div>
                    <div class="width10">
                        <input id="dialog_settings_event_duration_from_last_event_minutes" class="inputbox" onkeypress="return isNumberKey(event);" type="text" value="" maxlength="4" />
                    </div>
                </div>
                <div class="row2">
                    <div class="width50">
                        <? echo $la['WEEK_DAYS']; ?>
                    </div>
                    <div class="width50">
                        <div style="text-align:center; margin-right: 5px;">
                            <? echo $la['DAY_MONDAY_S']; ?><br /><input id="dialog_settings_event_wd_mon" type="checkbox" checked="checked" /></div>
                        <div style="text-align:center; margin-right: 5px;">
                            <? echo $la['DAY_TUESDAY_S']; ?><br /><input id="dialog_settings_event_wd_tue" type="checkbox" checked="checked" /></div>
                        <div style="text-align:center; margin-right: 5px;">
                            <? echo $la['DAY_WEDNESDAY_S']; ?><br /><input id="dialog_settings_event_wd_wed" type="checkbox" checked="checked" /></div>
                        <div style="text-align:center; margin-right: 5px;">
                            <? echo $la['DAY_THURSDAY_S']; ?><br /><input id="dialog_settings_event_wd_thu" type="checkbox" checked="checked" /></div>
                        <div style="text-align:center; margin-right: 5px;">
                            <? echo $la['DAY_FRIDAY_S']; ?><br /><input id="dialog_settings_event_wd_fri" type="checkbox" checked="checked" /></div>
                        <div style="text-align:center; margin-right: 5px;">
                            <? echo $la['DAY_SATURDAY_S']; ?><br /><input id="dialog_settings_event_wd_sat" type="checkbox" checked="checked" /></div>
                        <div style="text-align:center; margin-right: 5px;">
                            <? echo $la['DAY_SUNDAY_S']; ?><br /><input id="dialog_settings_event_wd_sun" type="checkbox" checked="checked" /></div>
                    </div>
                </div>
                <!-- 
				<div class="row2">
					<div class="width50"><? echo $la['WORKING_HOURS']; ?></div>
					<div class="width5">
						<input id="dialog_settings_event_timing" type="checkbox" class="checkbox" />
					</div>
				</div>
				 -->
                <div class="row2">
                    <div class="width50">
                        <? echo $la['TRIGGER_WORKING_HOURS']; ?>
                    </div>
                    <div class="width50">
                        <select id="dialog_settings_event_timing" class="width100" />
                        <option value="off">
                            <? echo $la['OFF']; ?>
                        </option>
                        <option value="in">
                            <? echo $la['IN_SELECTED_WORKING_HOUR']; ?>
                        </option>
                        <option value="out">
                            <? echo $la['OUT_OF_SELECTED_WORKING_HOUR']; ?>
                        </option>
                        </select>
                    </div>
                </div>
                <div class="row2">
                    <div class="width50">
                        <? echo $la['DAY_TIME']; ?>
                    </div>
                    <div class="width5">
                        <input id="dialog_settings_event_dt" type="checkbox" class="checkbox" onclick="settingsEventSwitchDayTime();" />
                    </div>
                </div>
                <div class="row2">
                    <div class="width50">
                        <? echo $la['DAY_MONDAY']; ?>
                    </div>
                    <div class="width5">
                        <input id="dialog_settings_event_dt_mon" type="checkbox" class="checkbox" />
                    </div>
                    <div class="width10">
                        <select id="dialog_settings_event_dt_mon_from">
                            <? include ("inc/inc_dt.hours_minutes.php"); ?>
                        </select>
                    </div>
                    <div class="width5"></div>
                    <div class="width10">
                        <select id="dialog_settings_event_dt_mon_to">
                            <? include ("inc/inc_dt.hours_minutes_full.php"); ?>
                        </select>
                    </div>
                </div>
                <div class="row2">
                    <div class="width50">
                        <? echo $la['DAY_TUESDAY']; ?>
                    </div>
                    <div class="width5">
                        <input id="dialog_settings_event_dt_tue" type="checkbox" class="checkbox" />
                    </div>
                    <div class="width10">
                        <select id="dialog_settings_event_dt_tue_from">
                            <? include ("inc/inc_dt.hours_minutes.php"); ?>
                        </select>
                    </div>
                    <div class="width5"></div>
                    <div class="width10">
                        <select id="dialog_settings_event_dt_tue_to">
                            <? include ("inc/inc_dt.hours_minutes_full.php"); ?>
                        </select>
                    </div>
                </div>
                <div class="row2">
                    <div class="width50">
                        <? echo $la['DAY_WEDNESDAY']; ?>
                    </div>
                    <div class="width5">
                        <input id="dialog_settings_event_dt_wed" type="checkbox" class="checkbox" />
                    </div>
                    <div class="width10">
                        <select id="dialog_settings_event_dt_wed_from">
                            <? include ("inc/inc_dt.hours_minutes.php"); ?>
                        </select>
                    </div>
                    <div class="width5"></div>
                    <div class="width10">
                        <select id="dialog_settings_event_dt_wed_to">
                            <? include ("inc/inc_dt.hours_minutes_full.php"); ?>
                        </select>
                    </div>
                </div>
                <div class="row2">
                    <div class="width50">
                        <? echo $la['DAY_THURSDAY']; ?>
                    </div>
                    <div class="width5">
                        <input id="dialog_settings_event_dt_thu" type="checkbox" class="checkbox" />
                    </div>
                    <div class="width10">
                        <select id="dialog_settings_event_dt_thu_from">
                            <? include ("inc/inc_dt.hours_minutes.php"); ?>
                        </select>
                    </div>
                    <div class="width5"></div>
                    <div class="width10">
                        <select id="dialog_settings_event_dt_thu_to">
                            <? include ("inc/inc_dt.hours_minutes_full.php"); ?>
                        </select>
                    </div>
                </div>
                <div class="row2">
                    <div class="width50">
                        <? echo $la['DAY_FRIDAY']; ?>
                    </div>
                    <div class="width5">
                        <input id="dialog_settings_event_dt_fri" type="checkbox" class="checkbox" />
                    </div>
                    <div class="width10">
                        <select id="dialog_settings_event_dt_fri_from">
                            <? include ("inc/inc_dt.hours_minutes.php"); ?>
                        </select>
                    </div>
                    <div class="width5"></div>
                    <div class="width10">
                        <select id="dialog_settings_event_dt_fri_to">
                            <? include ("inc/inc_dt.hours_minutes_full.php"); ?>
                        </select>
                    </div>
                </div>
                <div class="row2">
                    <div class="width50">
                        <? echo $la['DAY_SATURDAY']; ?>
                    </div>
                    <div class="width5">
                        <input id="dialog_settings_event_dt_sat" type="checkbox" class="checkbox" />
                    </div>
                    <div class="width10">
                        <select id="dialog_settings_event_dt_sat_from">
                            <? include ("inc/inc_dt.hours_minutes.php"); ?>
                        </select>
                    </div>
                    <div class="width5"></div>
                    <div class="width10">
                        <select id="dialog_settings_event_dt_sat_to">
                            <? include ("inc/inc_dt.hours_minutes_full.php"); ?>
                        </select>
                    </div>
                </div>
                <div class="row2">
                    <div class="width50">
                        <? echo $la['DAY_SUNDAY']; ?>
                    </div>
                    <div class="width5">
                        <input id="dialog_settings_event_dt_sun" type="checkbox" class="checkbox" />
                    </div>
                    <div class="width10">
                        <select id="dialog_settings_event_dt_sun_from">
                            <? include ("inc/inc_dt.hours_minutes.php"); ?>
                        </select>
                    </div>
                    <div class="width5"></div>
                    <div class="width10">
                        <select id="dialog_settings_event_dt_sun_to">
                            <? include ("inc/inc_dt.hours_minutes_full.php"); ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div id="settings_event_objects">
            <div class="row">
                <div class="title-block">
                    <? echo $la['OBJECTS']; ?>
                </div>
                <div class="row2">
                    <div class="width50">
                        <? echo $la['SELECTED_OBJECTS']; ?></br>
                        <span class="subinfo">
                            <? echo $la['HOLD_CTRL_TO_SELECT_MULTIPLE_ITEMS']; ?></span>
                    </div>
                    <div class="width50">
                        <select class="width100" id="dialog_settings_event_selected_objects" style="height:312px;" multiple="multiple" /></select>
                    </div>
                </div>
            </div>
        </div>
        <div id="settings_event_routes">
            <div class="row">
                <div class="title-block">
                    <? echo $la['ROUTES']; ?>
                </div>
                <div class="row2">
                    <div class="width50">
                        <? echo $la['TRIGGER_EVENT_DEPENDING_ON_ROUTES']; ?>
                    </div>
                    <div class="width50">
                        <select id="dialog_settings_event_route_trigger" class="width100" />
                        <option value="off">
                            <? echo $la['OFF']; ?>
                        </option>
                        <option value="in">
                            <? echo $la['IN_SELECTED_ROUTES']; ?>
                        </option>
                        <option value="out">
                            <? echo $la['OUT_OF_SELECTED_ROUTES']; ?>
                        </option>
                        </select>
                    </div>
                </div>
                <div class="row2">
                    <div class="width50">
                        <? echo $la['SELECTED_ROUTES']; ?></br>
                        <span class="subinfo">
                            <? echo $la['HOLD_CTRL_TO_SELECT_MULTIPLE_ITEMS']; ?></span>
                    </div>
                    <div class="width50">
                        <select class="width100" id="dialog_settings_event_selected_routes" style="height:285px;" multiple="multiple" /></select>
                    </div>
                </div>
            </div>
        </div>
        <div id="settings_event_zones">
            <div class="row">
                <div class="title-block">
                    <? echo $la['ZONES']; ?>
                </div>
                <div class="row2">
                    <div class="width50">
                        <? echo $la['TRIGGER_EVENT_DEPENDING_ON_ZONES']; ?>
                    </div>
                    <div class="width50">
                        <select id="dialog_settings_event_zone_trigger" class="width100" />
                        <option value="off">
                            <? echo $la['OFF']; ?>
                        </option>
                        <option value="in">
                            <? echo $la['IN_SELECTED_ZONES']; ?>
                        </option>
                        <option value="out">
                            <? echo $la['OUT_OF_SELECTED_ZONES']; ?>
                        </option>
                        </select>
                    </div>
                </div>
                <div class="row2">
                    <div class="width50">
                        <? echo $la['SELECTED_ZONES']; ?></br>
                        <span class="subinfo">
                            <? echo $la['HOLD_CTRL_TO_SELECT_MULTIPLE_ITEMS']; ?></span>
                    </div>
                    <div class="width50">
                        <select class="width100" id="dialog_settings_event_selected_zones" style="height:285px;" multiple="multiple" /></select>
                    </div>
                </div>
            </div>
        </div>
        <div id="settings_event_notification">
            <div class="row">
                <div class="title-block">
                    <? echo $la['NOTIFICATIONS']; ?>
                </div>
                <div class="row2">
                    <div class="width45">
                        <? echo $la['SYSTEM_MESSAGE']; ?>
                    </div>
                    <div class="width5"></div>
                    <div class="width50">
                        <input id="dialog_settings_event_notify_system" type="checkbox" class="checkbox" />
                    </div>
                </div>
                <div class="row2">
                    <div class="width45">
                        <? echo $la['AUTO_HIDE']; ?>
                    </div>
                    <div class="width5"></div>
                    <div class="width50">
                        <input id="dialog_settings_event_notify_system_hide" type="checkbox" class="checkbox" />
                    </div>
                </div>
                <div class="row2">
                    <div class="width45">
                        <? echo $la['SOUND_ALERT']; ?>
                    </div>
                    <div class="width5"></div>
                    <div class="width5">
                        <input id="dialog_settings_event_notify_system_sound" type="checkbox" class="checkbox" />
                    </div>
                    <div class="width29">
                        <select class="width100" id="dialog_settings_event_notify_system_sound_file" /></select>
                    </div>
                    <div class="width1"></div>
                    <div class="width15">
                        <input class="button float-right width100" type="button" onclick="settingsEventPlaySound();" value="<? echo $la['PLAY']; ?>" />
                    </div>
                </div>
                <div class="row2">
                    <div class="width45">
                        <? echo $la['MESSAGE_TO_EMAIL']; ?>
                    </div>
                    <div class="width5"></div>
                    <div class="width5">
                        <input id="dialog_settings_event_notify_email" type="checkbox" class="checkbox" />
                    </div>
                    <div class="width45">
                        <input id="dialog_settings_event_notify_email_address" class="inputbox" type="text" value="" maxlength="500" placeholder="<? echo $la['EMAIL_ADDRESS']; ?>" />
                    </div>
                </div>
                <div class="row2">
                    <div class="width45">
                        <? echo $la['SMS_TO_MOBILE_PHONE']; ?>
                    </div>
                    <div class="width5"></div>
                    <div class="width5">
                        <input id="dialog_settings_event_notify_sms" type="checkbox" class="checkbox" />
                    </div>
                    <div class="width45">
                        <input id="dialog_settings_event_notify_sms_number" class="inputbox" type="text" value="" maxlength="500" placeholder="<? echo $la['PHONE_NUMBER_WITH_CODE']; ?>" />
                    </div>
                </div>
                <div class="row2">
                    <div class="width45">
                        <? echo $la['EMAIL_TEMPLATE']; ?>
                    </div>
                    <div class="width5"></div>
                    <div class="width5"></div>
                    <div class="width45">
                        <select class="width100" id="dialog_settings_event_notify_email_template" /></select>
                    </div>
                </div>
                <div class="row2">
                    <div class="width45">
                        <? echo $la['SMS_TEMPLATE']; ?>
                    </div>
                    <div class="width5"></div>
                    <div class="width5"></div>
                    <div class="width45">
                        <select class="width100" id="dialog_settings_event_notify_sms_template" /></select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="title-block">
                    <? echo $la['COLORS']; ?>
                </div>
                <div class="row2">
                    <div class="width45">
                        <? echo $la['OBJECT_ARROW_COLOR']; ?>
                    </div>
                    <div class="width5"></div>
                    <div class="width5">
                        <input id="dialog_settings_event_notify_arrow" type="checkbox" class="checkbox" />
                    </div>
                    <div class="width20">
                        <select id="dialog_settings_event_notify_arrow_color" class="width100">                            
    						 <? include ("inc_arrow_colors.php"); ?>
                        </select>
                    </div>
                </div>
                <div class="row2">
                    <div class="width45">
                        <? echo $la['OBJECT_LIST_COLOR']; ?>
                    </div>
                    <div class="width5"></div>
                    <div class="width5">
                        <input id="dialog_settings_event_notify_ohc" type="checkbox" class="checkbox" />
                    </div>
                    <div class="width45">
                        <input class="color inputbox" style="width:104px" type='text' id='dialog_settings_event_notify_ohc_color' />
                    </div>
                </div>
            </div>
        </div>
        <div id="settings_event_object_control">
            <div class="row">
                <div class="title-block">
                    <? echo $la['OBJECT_CONTROL']; ?>
                </div>
                <div class="row2">
                    <div class="width50">
                        <? echo $la['SEND_COMMAND']; ?>
                    </div>
                    <div class="width50">
                        <input id="dialog_settings_event_cmd_send" type="checkbox" class="checkbox" />
                    </div>
                </div>
                <div class="row2">
                    <div class="width50">
                        <? echo $la['GATEWAY']; ?>
                    </div>
                    <div class="width50">
                        <select id="dialog_settings_event_cmd_gateway" style="width: 70px;" />
                        <option value="gprs">GPRS</option>
                        <option value="sms">SMS</option>
                        </select>
                    </div>
                </div>
                <div class="row2">
                    <div class="width50">
                        <? echo $la['TYPE']; ?>
                    </div>
                    <div class="width50">
                        <select id="dialog_settings_event_cmd_type" style="width: 70px;" />
                        <option value="ascii">ASCII</option>
                        <option value="hex">HEX</option>
                        </select>
                    </div>
                </div>
                <div class="row2">
                    <div class="width50">
                        <? echo $la['COMMAND']; ?>
                    </div>
                    <div class="width50">
                        <input id="dialog_settings_event_cmd_string" class="inputbox float-right" type="text" value="" maxlength="256">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <center>
        <input class="button icon-save icon" id="settingsEventPropertiesSave" type="button" onclick="settingsEventProperties('save');" value="<? echo $la['SAVE']; ?>" />&nbsp;
        <input class="button icon-close icon" id="settingsEventPropertiesCancel" type="button" onclick="settingsEventProperties('cancel');" value="<? echo $la['CANCEL']; ?>" />
    </center>
	</div>
	</div>
</div>

<div id="dialog_settings_object_group_properties" title="<? echo $la['OBJECT_GROUP_PROPERTIES'];?>">
    <div class="row">
        <div class="row2">
            <div class="width40">
                <? echo $la['NAME']; ?>
            </div>
            <div class="width60"><input id="dialog_settings_object_group_name" class="inputbox" type="text" value="" maxlength="25"></div>
        </div>
        <div class="row2">
            <div class="width40">
                <? echo $la['DESCRIPTION']; ?>
            </div>
            <div class="width60"><textarea id="dialog_settings_object_group_desc" class="inputbox" style="height:50px;" maxlength="100"></textarea></div>
        </div>
    </div>

    <center>
        <input class="button icon-save icon" type="button" onclick="settingsObjectGroupProperties('save');" value="<? echo $la['SAVE']; ?>" />&nbsp;
        <input class="button icon-close icon" type="button" onclick="settingsObjectGroupProperties('cancel');" value="<? echo $la['CANCEL']; ?>" />
    </center>
</div>

<div id="dialog_settings_object_driver_properties" title="<? echo $la['OBJECT_DRIVER_PROPERTIES'];?>">
    <div class="row">
        <div class="report-block block width40">
            <div class="container">
                <div class="row2" style="height: 186px; vertical-align: middle; text-align: center; display: table;">
                    <img id="dialog_settings_object_driver_photo" style="border:0px; width: 144px;" src="img/user-blank.svg" />
                </div>
                <center>
                    <input class="button" type="button" value="<? echo $la['UPLOAD']; ?>" onclick="settingsObjectDriverPhotoUpload();" />&nbsp;
                    <input class="button" type="button" value="<? echo $la['DELETE']; ?>" onclick="settingsObjectDriverPhotoDelete();" />
                </center>
            </div>
        </div>
        <div class="report-block block width60">
            <div class="container last">
                <div class="row2">
                    <div class="width40">
                        <? echo $la['NAME']; ?>
                    </div>
                    <div class="width60"><input id="dialog_settings_object_driver_name" class="inputbox" type="text" value="" maxlength="30"></div>
                </div>
                <div class="row2">
                    <div class="width40">
                        <? echo $la['RFID_OR_IBUTTON']; ?>
                    </div>
                    <div class="width60"><input id="dialog_settings_object_driver_assign_id" class="inputbox" type="text" value="" maxlength="30"></div>
                </div>
                <div class="row2">
                    <div class="width40">
                        <? echo $la['ID_NUMBER']; ?>
                    </div>
                    <div class="width60"><input id="dialog_settings_object_driver_idn" class="inputbox" type="text" value="" maxlength="30"></div>
                </div>
                <div class="row2">
                    <div class="width40">
                        <? echo $la['ADDRESS']; ?>
                    </div>
                    <div class="width60"><input id="dialog_settings_object_driver_address" class="inputbox" type="text" value="" maxlength="100"></div>
                </div>
                <div class="row2">
                    <div class="width40">
                        <? echo $la['PHONE']; ?>
                    </div>
                    <div class="width60"><input id="dialog_settings_object_driver_phone" class="inputbox" type="text" value="" maxlength="50"></div>
                </div>
                <div class="row2">
                    <div class="width40">
                        <? echo $la['EMAIL']; ?>
                    </div>
                    <div class="width60"><input id="dialog_settings_object_driver_email" class="inputbox" type="text" value="" maxlength="50"></div>
                </div>
                <div class="row2">
                    <div class="width40">
                        <? echo $la['DESCRIPTION']; ?>
                    </div>
                    <div class="width60"><textarea id="dialog_settings_object_driver_desc" class="inputbox" style="height:51px;" maxlength="100"></textarea></div>
                </div>
            </div>
        </div>
    </div>

    <center>
        <input class="button icon-save icon" type="button" onclick="settingsObjectDriverProperties('save');" value="<? echo $la['SAVE']; ?>" />&nbsp;
        <input class="button icon-close icon" type="button" onclick="settingsObjectDriverProperties('cancel');" value="<? echo $la['CANCEL']; ?>" />
    </center>
</div>

<div id="dialog_settings_object_passenger_properties" title="<? echo $la['OBJECT_PASSENGER_PROPERTIES'];?>">
    <div class="row">
        <div class="row2">
            <div class="width40">
                <? echo $la['NAME']; ?>
            </div>
            <div class="width60"><input id="dialog_settings_object_passenger_name" class="inputbox" type="text" value="" maxlength="30"></div>
        </div>
        <div class="row2">
            <div class="width40">
                <? echo $la['RFID_OR_IBUTTON']; ?>
            </div>
            <div class="width60"><input id="dialog_settings_object_passenger_assign_id" class="inputbox" type="text" value="" maxlength="30"></div>
        </div>
        <div class="row2">
            <div class="width40">
                <? echo $la['ID_NUMBER']; ?>
            </div>
            <div class="width60"><input id="dialog_settings_object_passenger_idn" class="inputbox" type="text" value="" maxlength="30"></div>
        </div>
        <div class="row2">
            <div class="width40">
                <? echo $la['ADDRESS']; ?>
            </div>
            <div class="width60"><input id="dialog_settings_object_passenger_address" class="inputbox" type="text" value="" maxlength="100"></div>
        </div>
        <div class="row2">
            <div class="width40">
                <? echo $la['PHONE']; ?>
            </div>
            <div class="width60"><input id="dialog_settings_object_passenger_phone" class="inputbox" type="text" value="" maxlength="50"></div>
        </div>
        <div class="row2">
            <div class="width40">
                <? echo $la['EMAIL']; ?>
            </div>
            <div class="width60"><input id="dialog_settings_object_passenger_email" class="inputbox" type="text" value="" maxlength="50"></div>
        </div>
        <div class="row2">
            <div class="width40">
                <? echo $la['DESCRIPTION']; ?>
            </div>
            <div class="width60"><textarea id="dialog_settings_object_passenger_desc" class="inputbox" style="height:50px;" maxlength="100"></textarea></div>
        </div>
    </div>

    <center>
        <input class="button icon-save icon" type="button" onclick="settingsObjectPassengerProperties('save');" value="<? echo $la['SAVE']; ?>" />&nbsp;
        <input class="button icon-close icon" type="button" onclick="settingsObjectPassengerProperties('cancel');" value="<? echo $la['CANCEL']; ?>" />
    </center>
</div>

<div id="dialog_settings_object_trailer_properties" title="<? echo $la['OBJECT_TRAILER_PROPERTIES'];?>">
    <div class="row">
        <div class="row2">
            <div class="width40">
                <? echo $la['NAME']; ?>
            </div>
            <div class="width60"><input id="dialog_settings_object_trailer_name" class="inputbox" type="text" value="" maxlength="25"></div>
        </div>
        <div class="row2">
            <div class="width40">
                <? echo $la['RFID_OR_IBUTTON']; ?>
            </div>
            <div class="width60"><input id="dialog_settings_object_trailer_assign_id" class="inputbox" type="text" value="" maxlength="30"></div>
        </div>
        <div class="row2">
            <div class="width40">
                <? echo $la['TRANSPORT_MODEL']; ?>
            </div>
            <div class="width60"><input id="dialog_settings_object_trailer_model" class="inputbox" type="text" value="" maxlength="30"></div>
        </div>
        <div class="row2">
            <div class="width40">
                <? echo $la['VIN']; ?>
            </div>
            <div class="width60"><input id="dialog_settings_object_trailer_vin" class="inputbox" type="text" value="" maxlength="20"></div>
        </div>
        <div class="row2">
            <div class="width40">
                <? echo $la['PLATE_NUMBER']; ?>
            </div>
            <div class="width60"><input id="dialog_settings_object_trailer_plate_number" class="inputbox" type="text" value="" maxlength="20"></div>
        </div>
        <div class="row2">
            <div class="width40">
                <? echo $la['DESCRIPTION']; ?>
            </div>
            <div class="width60"><textarea id="dialog_settings_object_trailer_desc" class="inputbox" style="height:50px;" maxlength="100"></textarea></div>
        </div>
    </div>

    <center>
        <input class="button icon-save icon" type="button" onclick="settingsObjectTrailerProperties('save');" value="<? echo $la['SAVE']; ?>" />&nbsp;
        <input class="button icon-close icon" type="button" onclick="settingsObjectTrailerProperties('cancel');" value="<? echo $la['CANCEL']; ?>" />
    </center>
</div>

<div id="dialog_settings_object_sensor_properties" title="<? echo $la['SENSOR_PROPERTIES'];?>">
    <div class="row">
        <div class="width50 block">
            <div class="width100 block">
                <div class="container">
                    <div class="row">
                        <div class="title-block">
                            <? echo $la['SENSOR']; ?>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['NAME']; ?>
                            </div>
                            <div class="width50"><input id="dialog_settings_object_sensor_name" class="inputbox" type="text" value="" maxlength="25"></div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['TYPE']; ?>
                            </div>
                            <div class="width50">
                                <select class="width100" id="dialog_settings_object_sensor_type" onchange="settingsObjectSensorType();">
                                    <option value="di">
                                        <? echo $la['DIGITAL_INPUT']; ?>
                                    </option>
                                    <option value="do">
                                        <? echo $la['DIGITAL_OUTPUT']; ?>
                                    </option>
                                    <option value="da">
                                        <? echo $la['DRIVER_ASSIGN']; ?>
                                    </option>
                                    <option value="engh">
                                        <? echo $la['ENGINE_HOURS']; ?>
                                    </option>
                                    <option value="fuel">
                                        <? echo $la['FUEL_LEVEL']; ?>
                                    </option>
                                    <option value="fuelcons">
                                        <? echo $la['FUEL_CONSUMPTION']; ?>
                                    </option>
                                    <option value="acc">
                                        <? echo $la['IGNITION_ACC']; ?>
                                    </option>
                                    <option value="air_ac">
                                        <? echo $la['AIRCONDITION']; ?>
                                    </option>
                                    <option value="odo">
                                        <? echo $la['ODOMETER']; ?>
                                    </option>
                                    <option value="pa">
                                        <? echo $la['PASSENGER_ASSIGN']; ?>
                                    </option>
                                    <option value="temp">
                                        <? echo $la['TEMPERATURE']; ?>
                                    </option>
                                    <option value="ta">
                                        <? echo $la['TRAILER_ASSIGN']; ?>
                                    </option>
                                    <option value="cust">
                                        <? echo $la['CUSTOM']; ?>
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['PARAMETER']; ?>
                            </div>
                            <div class="width50"><select class="width100" id="dialog_settings_object_sensor_param" onchange="settingsObjectSensorResultPreview();"></select></div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['DATA_LIST']; ?>
                            </div>
                            <div class="width50"><input id="dialog_settings_object_sensor_data_list" type="checkbox" class="checkbox" /></div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['POPUP']; ?>
                            </div>
                            <div class="width50"><input id="dialog_settings_object_sensor_popup" type="checkbox" class="checkbox" /></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="width100 block">
                <div class="container">
                    <div class="row">
                        <div class="title-block">
                            <? echo $la['RESULT']; ?>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['TYPE']; ?>
                            </div>
                            <div class="width50"><select class="width100" id="dialog_settings_object_sensor_result_type" onchange="settingsObjectSensorResultType();"></select></div>
                        </div>
                        <div class="row2">
                            <div class="width50"><span>
                                    <? echo $la['UNITS_OF_MEASUREMENT']; ?></span></div>
                            <div class="width50"><input id="dialog_settings_object_sensor_units" class="inputbox" type="text" value="" maxlength="10"></div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['IF_SENSOR_1']; ?>
                            </div>
                            <div class="width50"><input id="dialog_settings_object_sensor_text_1" class="inputbox width100" type="text" value="" maxlength="15"></div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['IF_SENSOR_0']; ?>
                            </div>
                            <div class="width50"><input id="dialog_settings_object_sensor_text_0" class="inputbox width100" type="text" value="" maxlength="15"></div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['FORMULA']; ?>
                            </div>
                            <div class="width50">
                                <input id="dialog_settings_object_sensor_formula" class="inputbox" type="text" value="" placeholder="(X+1)/2*3" />
                            </div>
                        </div>
                        <div class="row2" id="teltonika_fuelSensor">
                            <div class="width50">
                                <? echo $la['TANK_SIZE']; ?>
                            </div>
                            <div class="width50">
                                <input id="dialog_settings_object_sensor_tanksize" class="inputbox" type="text" value="" placeholder="Only For Technoton Sensor" />
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['LOWEST_VALUE']; ?>
                            </div>
                            <div class="width50"><input id="dialog_settings_object_sensor_lv" onkeypress="return isNumberKey(event);" class="inputbox" type="text" value="" maxlength="10"></div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['HIGHEST_VALUE']; ?>
                            </div>
                            <div class="width50"><input id="dialog_settings_object_sensor_hv" onkeypress="return isNumberKey(event);" class="inputbox" type="text" value="" maxlength="10"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="width50 block">
            <div class="container last">
                <div class="row">
                    <div class="title-block">
                        <? echo $la['CALIBRATION']; ?>
                    </div>
                    <div class="row2">
                        <div id="settings_object_sensor_calibration_list">
                            <table id="settings_object_sensor_calibration_list_grid"></table>
                        </div>
                    </div>
                    <div class="row2">
                        <div class="width10">
                            X
                        </div>
                        <div class="width28">
                            <input id="settings_object_sensor_calibration_x" onkeypress="return isNumberKey(event);" class="inputbox width90" type="text" value="" maxlength="10" disabled>
                        </div>
                        <div class="width10">
                            Y
                        </div>
                        <div class="width28">
                            <input id="settings_object_sensor_calibration_y" onkeypress="return isNumberKey(event);" class="inputbox width90" type="text" value="" maxlength="10" disabled>
                        </div>
                        <div class="width24">
                            <input id="settings_object_sensor_calibration_add" style="min-width: 0;" class="width100 button icon-new icon" type="button" value="<? echo $la['ADD']; ?>" onclick="settingsObjectSensorCalibrationAdd();" disabled />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="width100 block">
            <div class="title-block">
                <? echo $la['SENSOR_RESULT_PREVIEW']; ?>
            </div>
            <div class="width45 block">
                <div class="container">
                    <div class="row2">
                        <div class="width50">
                            <? echo $la['CURRENT_VALUE']; ?>
                        </div>
                        <div class="width50">
                            <input id="dialog_settings_object_sensor_cur_param_val" class="inputbox" type="text" value="" readonly />
                        </div>
                    </div>
                </div>
            </div>
            <div class="width10 block">
                <div class="container">
                    <div class="row2">
                        <input style="min-width: 0;" class="width100 button icon-arrow-right no-text icon" type="button" onclick="settingsObjectSensorResultPreview();" />
                    </div>
                </div>
            </div>
            <div class="width45 block">
                <div class="container last">
                    <div class="row2">
                        <div class="width50">
                            <? echo $la['RESULT']; ?>
                        </div>
                        <div class="width50">
                            <input id="dialog_settings_object_sensor_result_preview" class="inputbox" type="text" value="" readonly />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <center>
        <input class="button icon-save icon" type="button" onclick="settingsObjectSensorProperties('save');" value="<? echo $la['SAVE']; ?>" />&nbsp;
        <input class="button icon-close icon" type="button" onclick="settingsObjectSensorProperties('cancel');" value="<? echo $la['CANCEL']; ?>" />
    </center>
</div>

<div id="dialog_settings_object_service_properties" title="<? echo $la['SERVICE_PROPERTIES'];?>">
    <div class="row">
        <div class="title-block">
            <? echo $la['SERVICE']; ?>
        </div>
        <div class="block width50">
            <div class="container">
                <div class="row2">
                    <div class="width50">
                        <? echo $la['NAME']; ?>
                    </div>
                    <div class="width50"><input id="dialog_settings_object_service_name" class="inputbox" type="text" value="" maxlength="30"></div>
                </div>
                <div class="row2">
                    <div class="width50">
                        <? echo $la['DATA_LIST']; ?>
                    </div>
                    <div class="width10"><input id="dialog_settings_object_service_data_list" type="checkbox" class="checkbox" /></div>
                </div>
                <div class="row2">
                    <div class="width50">
                        <? echo $la['POPUP']; ?>
                    </div>
                    <div class="width10"><input id="dialog_settings_object_service_popup" type="checkbox" class="checkbox" /></div>
                </div>
                <div class="row2">
                    <div class="width50">
                        <? echo $la['ODOMETER_INTERVAL'].' ('.$la["UNIT_DISTANCE"].')'; ?>
                    </div>
                    <div class="width10"><input id="dialog_settings_object_service_odo" onchange="settingsObjectServiceCheck();" type="checkbox" class="checkbox" /></div>
                    <div class="width40"><input id="dialog_settings_object_service_odo_interval" onkeypress="return isNumberKey(event);" class="inputbox" type="text" value="" maxlength="15"></div>
                </div>

                <div class="row2">
                    <div class="width50">
                        <? echo $la['ENGINE_HOURS_INTERVAL'].' (h)'; ?>
                    </div>
                    <div class="width10"><input id="dialog_settings_object_service_engh" onchange="settingsObjectServiceCheck();" type="checkbox" class="checkbox" /></div>
                    <div class="width40"><input id="dialog_settings_object_service_engh_interval" onkeypress="return isNumberKey(event);" class="inputbox" type="text" value="" maxlength="15"></div>
                </div>

                <div class="row2">
                    <div class="width50">
                        <? echo $la['DAYS_INTERVAL']; ?>
                    </div>
                    <div class="width10"><input id="dialog_settings_object_service_days" onchange="settingsObjectServiceCheck();" type="checkbox" class="checkbox" /></div>
                    <div class="width40"><input id="dialog_settings_object_service_days_interval" onkeypress="return isNumberKey(event);" class="inputbox" type="text" value="" maxlength="15"></div>
                </div>
            </div>
        </div>
        <div class="block width50">
            <div class="container last">
                <div class="row2 empty"></div>
                <div class="row2 empty"></div>
                <div class="row2 empty"></div>
                <div class="row2">
                    <div class="width50">
                        <? echo $la['LAST_SERVICE'].' ('.$la["UNIT_DISTANCE"].')'; ?>
                    </div>
                    <div class="width50"><input id="dialog_settings_object_service_odo_last" onkeypress="return isNumberKey(event);" class="inputbox" type="text" value="" maxlength="15"></div>
                </div>
                <div class="row2">
                    <div class="width50">
                        <? echo $la['LAST_SERVICE'].' (h)'; ?>
                    </div>
                    <div class="width50"><input id="dialog_settings_object_service_engh_last" onkeypress="return isNumberKey(event);" class="inputbox" type="text" value="" maxlength="15"></div>
                </div>
                <div class="row2">
                    <div class="width50">
                        <? echo $la['LAST_SERVICE']; ?>
                    </div>
                    <div class="width50"><input id="dialog_settings_object_service_days_last" readonly class="inputbox inputbox-calendar" onkeypress="return isNumberKey(event);" class="inputbox" type="text" value="" maxlength="15"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="title-block">
            <? echo $la['TRIGGER_EVENT']; ?>
        </div>
        <div class="block width50">
            <div class="container">
                <div class="row2">
                    <div class="width50">
                        <?  echo $la['ODOMETER_LEFT']. ' ('.$la["UNIT_DISTANCE"].')'; ?>
                    </div>
                    <div class="width10"><input id="dialog_settings_object_service_odo_left" onchange="settingsObjectServiceCheck();" type="checkbox" class="checkbox" /></div>
                    <div class="width40"><input id="dialog_settings_object_service_odo_left_num" onkeypress="return isNumberKey(event);" class="inputbox" type="text" value="" maxlength="15"></div>
                </div>
                <div class="row2">
                    <div class="width50">
                        <?  echo $la['ENGINE_HOURS_LEFT']. ' (h)'; ?>
                    </div>
                    <div class="width10"><input id="dialog_settings_object_service_engh_left" onchange="settingsObjectServiceCheck();" type="checkbox" class="checkbox" /></div>
                    <div class="width40"><input id="dialog_settings_object_service_engh_left_num" onkeypress="return isNumberKey(event);" class="inputbox" type="text" value="" maxlength="15"></div>
                </div>
                <div class="row2">
                    <div class="width50">
                        <? echo $la['DAYS_LEFT']; ?>
                    </div>
                    <div class="width10"><input id="dialog_settings_object_service_days_left" onchange="settingsObjectServiceCheck();" type="checkbox" class="checkbox" /></div>
                    <div class="width40"><input id="dialog_settings_object_service_days_left_num" onkeypress="return isNumberKey(event);" class="inputbox" type="text" value="" maxlength="15"></div>
                </div>
            </div>
        </div>
        <div class="block width50">
            <div class="container last">
                <div class="row2">
                    <div class="width50">
                        <?  echo $la['UPDATE_LAST_SERVICE']; ?>
                    </div>
                    <div class="width50"><input id="dialog_settings_object_service_update_last" type="checkbox" class="checkbox" /></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="title-block">
            <? echo $la['CURRENT_OBJECT_COUNTERS']; ?>
        </div>
        <div class="block width50">
            <div class="container">
                <div class="row2">
                    <div class="width50">
                        <? echo $la['CURRENT_ODOMETER']. ' ('.$la["UNIT_DISTANCE"].')'; ?>
                    </div>
                    <div class="width50"><input id="dialog_settings_object_service_odo_curr" onkeypress="return isNumberKey(event);" class="inputbox" type="text" value="" maxlength="15" disabled></div>
                </div>
                <div class="row2">
                    <div class="width50">
                        <? echo $la['CURRENT_ENGINE_HOURS']. ' (h)'; ?>
                    </div>
                    <div class="width50"><input id="dialog_settings_object_service_engh_curr" onkeypress="return isNumberKey(event);" class="inputbox" type="text" value="" maxlength="15" disabled></div>
                </div>
            </div>
        </div>
    </div>

    <center>
        <input class="button icon-save icon" type="button" onclick="settingsObjectServiceProperties('save');" value="<? echo $la['SAVE']; ?>" />&nbsp;
        <input class="button icon-close icon" type="button" onclick="settingsObjectServiceProperties('cancel');" value="<? echo $la['CANCEL']; ?>" />
    </center>
</div>

<div id="dialog_settings_object_custom_field_properties" title="<? echo $la['CUSTOM_FIELD_PROPERTIES'];?>">
    <div class="row">
        <div class="row2">
            <div class="width40">
                <? echo $la['NAME']; ?>
            </div>
            <div class="width60"><input id="dialog_settings_object_custom_field_name" class="inputbox" type="text" maxlength="25"></div>
        </div>
        <div class="row2">
            <div class="width40">
                <? echo $la['VALUE']; ?>
            </div>
            <div class="width60"><input id="dialog_settings_object_custom_field_value" class="inputbox" type="text" maxlength="50"></div>
        </div>
        <div class="row2">
            <div class="width40">
                <? echo $la['DATA_LIST']; ?>
            </div>
            <div class="width60"><input id="dialog_settings_object_custom_field_data_list" type="checkbox" class="checkbox" /></div>
        </div>
        <div class="row2">
            <div class="width40">
                <? echo $la['POPUP']; ?>
            </div>
            <div class="width60"><input id="dialog_settings_object_custom_field_popup" type="checkbox" class="checkbox" /></div>
        </div>
    </div>
    <center>
        <input class="button icon-save icon" type="button" onclick="settingsObjectCustomFieldProperties('save');" value="<? echo $la['SAVE']; ?>" />&nbsp;
        <input class="button icon-close icon" type="button" onclick="settingsObjectCustomFieldProperties('cancel');" value="<? echo $la['CANCEL']; ?>" />
    </center>
</div>

<div id="dialog_settings_object_edit_select_icon" title="<? echo $la['SELECT_ICON'];?>">
    <div id="settings_object_edit_select_icon_tabs">
        <ul>
            <li><a href="#settings_object_edit_select_icon_default_tab">
                    <? echo $la['DEFAULT']; ?></a></li>
            <li><a href="#settings_object_edit_select_icon_custom_tab">
                    <? echo $la['CUSTOM']; ?></a></li>
        </ul>
        <div id="settings_object_edit_select_icon_default_tab">
            <div class="row2">
                <div class="icon_selector width100" id="settings_object_edit_select_icon_default_list">
                </div>
            </div>
        </div>
        <div id="settings_object_edit_select_icon_custom_tab">
            <div class="row">
                <div class="row2">
                    <div class="icon_selector width100" id="settings_object_edit_select_icon_custom_list">
                    </div>
                </div>
            </div>
            <center>
                <input class="button" type="button" value="<? echo $la['UPLOAD']; ?>" onclick="settingsObjectEditUploadCustomIcon();" />&nbsp;
                <input class="button" type="button" value="<? echo $la['DELETE_ALL']; ?>" onclick="settingsObjectEditDeleteAllCustomIcon();" />
            </center>
        </div>
    </div>
</div>

<div id="dialog_settings_object_edit" title="<? echo $la['EDIT_OBJECT'];?>">
    <div id="settings_object">
	<div class="block width100">
        <div class="block width20">
            <ul id="cmdmenu"> 
                <!-- Code update by vetrivel.NR -->
                <? if ($_SESSION["cpanel_privileges"] == false && $_SESSION["obj_add"] != 'true'){ ?>
                <li style="display:none;"><a href="#settings_object_main">
                        <? echo $la['MAIN']; ?></a></li>
                <?} else {?>
                <li><a href="#settings_object_main" class="hvr-sweep-to-bottom  ">
                        <? echo $la['MAIN']; ?></a></li>
                <?}?>
                <li><a href="#settings_object_main2" class="hvr-sweep-to-bottom1  ">
                        <? echo $la['VEHICLEDATA']; ?></a></li>
                <li><a href="#settings_object_icon" class="hvr-sweep-to-bottom2  ">
                        <? echo $la['ICON']; ?></a></li>
                <li><a href="#settings_object_fuel" class="hvr-sweep-to-bottom3  ">
                        <? echo $la['FUEL_CONSUMPTION']; ?></a></li>
                <li id="settings_object_accuracy_enable/de"><a href="#settings_object_accuracy" class="hvr-sweep-to-bottom4  ">
                        <? echo $la['ACCURACY']; ?></a></li>
                <li><a href="#settings_object_sensors" class="hvr-sweep-to-bottom5  ">
                        <? echo $la['SENSORS']; ?></a></li>
                <li><a href="#settings_object_service" class="hvr-sweep-to-bottom6  ">
                        <? echo $la['SERVICE']; ?></a></li>
                <li><a href="#settings_object_custom_fields" class="hvr-sweep-to-bottom3  ">
                        <? echo $la['CUSTOM_FIELDS']; ?></a></li>
                <li><a href="#settings_object_working_hour" class="hvr-sweep-to-bottom2  ">
                        <? echo $la['WORKING_HOURS']; ?></a></li>
                <li><a href="#settings_object_info" class="hvr-sweep-to-bottom5  ">
                        <? echo $la['INFO']; ?></a></li>
            </ul>
		</div>
		<div class="block width3">
			<div class="vl"></div>	
		</div>
		<div class="block width70">
		
        <!-- Code update by vetrivel.NR -->
        <div id="settings_object_main" style="width:100%;">
            <?
			if ($_SESSION["cpanel_privileges"] == false && $_SESSION["obj_add"] != 'true'){ ?>
            <div style="display:none;" id="settings_object_mainnewsub">
                <?} else {?>
                <div id="settings_object_mainnewsub">
                    <?}?>
                    <div class="title-block">
                        <? echo $la['OBJECT_INFO']; ?>
                    </div>

                    <div class="row2">
                        <div class="width20">
                            <? echo $la['IMEI']; ?>
                        </div>
                        <div class="width30"><input id="dialog_settings_object_edit_imei" class="inputbox width80" type="text" maxlength="15"></div>
                        <? if ($_SESSION["cpanel_privileges"] == true || $_SESSION["obj_add"] == true){ ?>
                        <div class="width20">
                            <? echo $la['NAME']; ?>
                        </div>
                        <div class="width30"><input id="dialog_settings_object_edit_name" class="inputbox width80" type="text" value="" maxlength="50"></div>
                        <?} ?>
                    </div>
                    <!-- 
			<div class="row2">
					<div class="width20"><? echo $la['ODOMETER']. ' ('.$la["UNIT_DISTANCE"].')'; ?></div>
					<div class="width30">
						<select  style="display:none" id="dialog_settings_object_edit_odometer_type" class="width100" disabled>
							<option value="off"><? echo $la['OFF']; ?></option>
							<option value="gps">GPS</option>
							<option value="sen"><? echo $la['SENSOR']; ?></option>
						</select>
					</div>
				<div class="width20"><? echo $la['ENGINE_HOURS']. ' (h)'; ?></div>
					<div class="width30" >
						<select  style="display:none" id="dialog_settings_object_edit_engine_hours_type" class="width100" disabled>
							<option value="off"><? echo $la['OFF']; ?></option>
							<option value="acc">ACC</option>
							<option value="sen"><? echo $la['SENSOR']; ?></option>
						</select>
					</div>
					
			</div>
			 -->
                    <div class="row2">

                        <div class="width20">
                            <? echo $la['GPS_DEVICE']; ?>
                        </div>
                        <div class="width30"><select class="width80" id="dialog_settings_object_edit_device"></select></div>

                        <div class="width20">
                            <? echo $la['SIM_CARD_NUMBER']; ?>
                        </div>
                        <div class="width30"><input id="dialog_settings_object_edit_sim_number" class="inputbox width80" type="text" value="" maxlength="30"></div>
                    </div>
                    <div class="row2">
                        <div class="width20">
                            <? echo $la['FUELTYPE']; ?>
                        </div>
                        <div class="width30"><select class="width80" id="difueltype" onchange="changefueltype()">
                                <option>FMS</option>
                                <option>FUEL Sensor</option>
                                <option>No Sensor</option>
                            </select></div>

                        <div class="width20">
                            <? echo $la['FUEL1']; ?>
                        </div>
                        <div class="width30"><select class="width80" id="difuel1">
                                <option value="RS232">RS232</option>
                                <option value="RS485">RS485</option>
                                <option value="RS232KUS">RS232 Kus</option>
                                <option value="RS232SLVR">RS232 Slvr</option>
                                <option value="RS232DRV">RS232 DrV</option>
                                <option value="DUTE232">DUT-E 232</option>
                                <option value="DUTE485">DUT-E 485</option>
				<option value="JT232">JT232</option>
                                <option value="No Sensor">No Sensor</option>
                            </select></div>

                    </div>



                    <div class="row2">
                        <div class="width20">
                            <? echo $la['FUEL2']; ?>
                        </div>
                        <div class="width30"><select class="width80" id="difuel2">
                                <option value="RS232">RS232</option>
                                <option value="RS485">RS485</option>
                                <option value="RS232KUS">RS232 Kus</option>
                                <option value="RS232SLVR">RS232 Slvr</option>
                                <option value="RS232DRV">RS232 DrV</option>
                                <option value="DUTE232">DUT-E 232</option>
                                <option value="DUTE485">DUT-E 485</option>
                                <option value="No Sensor">No Sensor</option>
                            </select></div>
                       <!--  <div class="width20">
                            <? echo $la['FUEL3']; ?>
                        </div>
                        <div class="width30"><select class="width80" id="difuel3">
                                <option value="RS232">RS232</option>
                                <option value="RS485">RS485</option>
                                <option value="RS232KUS">RS232 Kus</option>
                                <option value="RS232SLVR">RS232 Slvr</option>
                                <option value="RS232DRV">RS232 DrV</option>
                                <option value="No Sensor">No Sensor</option>
                            </select></div> -->

                    <!-- </div>

                    <div class="row2"> -->

                        <!-- <div class="width20">
                            <? echo $la['FUEL4']; ?>
                        </div>
                        <div class="width30"><select class="width80" id="difuel4">
                                <option value="RS232">RS232</option>
                                <option value="RS485">RS485</option>
                                <option value="RS232KUS">RS232 Kus</option>
                                <option value="RS232SLVR">RS232 Slvr</option>
                                <option value="RS232DRV">RS232 DrV</option>
                                <option value="No Sensor">No Sensor</option>
                            </select></div> -->

                        <div class="width20">
                            <? echo $la['TEMP1']; ?>
                        </div>
                        <div class="width30"><select class="width80" id="ditemp1">
                                <option>YES</option>
                                <option>NO</option>
                            </select></div>
                    </div>

                    <div class="row2">
                        <div class="width20">
                            <? echo $la['TEMP2']; ?>
                        </div>
                        <div class="width30"><select class="width80" id="ditemp2">
                                <option>YES</option>
                                <option>NO</option>
                            </select></div>

                        <!-- <div class="width20">
                            <? echo $la['TEMP3']; ?>
                        </div>
                        <div class="width30"><select class="width80" id="ditemp3">
                                <option>YES</option>
                                <option>NO</option>
                            </select></div> -->

                    <!-- </div>
                    <div class="row2"> -->
                        <div class="width20">
                            <? echo $la['STAFFNAME']; ?>
                        </div>
                        <div class="width30"><input class="width80" id="distaffname" class="inputbox" type="text"></div>

                    </div>

                </div>
            </div>



            <div id="settings_object_main2">
                <div class="row">
                    <div class="title-block">
                        <? echo $la['MAIN']; ?>
                    </div>

                    <? if ($_SESSION["cpanel_privileges"] == false || $_SESSION["obj_add"] == 'false'){ ?>
                    <div class="row2">
                        <div class="width40">
                            <? echo $la['NAME']; ?>
                        </div>
                        <div class="width60"><input id="dialog_settings_object_edit_name_user" class="inputbox" type="text" value="" maxlength="25"></div>
                    </div>
                    <? } ?>
                    <!-- Code update by Vetrivel.NR
				<div class="row2">
					<div class="width40"><? echo $la['IMEI']; ?></div>
					<div class="width60"><input id="dialog_settings_object_edit_imei" class="inputbox" type="text" maxlength="15" disabled></div>
				</div>
				 -->
                    <div class="row2">
                        <div class="width40">
                            <? echo $la['TRANSPORT_MODEL']; ?>
                        </div>
                        <div class="width60"><input id="dialog_settings_object_edit_model" class="inputbox" type="text" value="" maxlength="30"></div>
                    </div>
                    <div class="row2">
                        <div class="width40">
                            <? echo $la['VIN']; ?>
                        </div>
                        <div class="width60"><input id="dialog_settings_object_edit_vin" class="inputbox" type="text" maxlength="20"></div>
                    </div>
                    <div class="row2">
                        <div class="width40">
                            <? echo $la['PLATE_NUMBER']; ?>
                        </div>
                        <div class="width60"><input id="dialog_settings_object_edit_plate_number" class="inputbox" type="text" maxlength="15"></div>
                    </div>
                    <div class="row2">
                        <div class="width40">
                            <? echo $la['GROUP']; ?>
                        </div>
                        <div class="width60"><select class="width100" id="dialog_settings_object_edit_group"></select></div>
                    </div>
                    <div class="row2">
                        <div class="width40">
                            <? echo $la['DRIVER']; ?>
                        </div>
                        <div class="width60"><select class="width100" id="dialog_settings_object_edit_driver"></select></div>
                    </div>
                    <div class="row2">
                        <div class="width40">
                            <? echo $la['TRAILER']; ?>
                        </div>
                        <div class="width60"><select class="width100" id="dialog_settings_object_edit_trailer"></select></div>
                    </div>
                    <!-- 
				<div class="row2">
					<div class="width40"><? echo $la['GPS_DEVICE']; ?></div>
					<div class="width60"><select class="width100" id="dialog_settings_object_edit_device"></select></div>
				</div>
				<div class="row2">
					<div class="width40"><? echo $la['SIM_CARD_NUMBER']; ?></div>
					<div class="width60"><input id="dialog_settings_object_edit_sim_number" class="inputbox" type="text" value="" maxlength="30"></div>
				</div>
				 -->
                    <div class="row2">
                        <div class="width40">
                            <? echo $la['TRIP_TYPE']; ?>
                        </div>
                        <div class="width40">
                            <select class="width100" id="dialog_settings_trip_type">
                                <option>None</option>
                                <option>Scheduled</option>
                                <option>Daily</option>
                            </select>
                        </div>
                    </div>
					<div class="row2">
						<div class="width40">
							<? echo $la['SEAT_CAPACITY']; ?>
						</div>
						<div class="width60"><input id="dialog_settings_object_edit_seat_capacity" class="inputbox" type="text" maxlength="15"></div>
					</div>
                    <div class="row2">
                     <div class="width40">
                        <? echo $la['VEHICLE_TYPE']; ?>
                     </div>
                     <div class="width60">
                        <select id="dialog_settings_object_edit_vechile_type" class="inputbox" maxlength="15">
                           <option value=''>Select</option>
                           <option value='Own'>Own</option>
                           <option value='Attached'>Attached</option>
                        </select>
                     </div>
                  </div>
                  <div class="row2">
                     <div class="width40">
                        <? echo $la['FREEZED_KM']; ?>
                     </div>
                     <div class="width60"><input id="dialog_settings_object_edit_freeze_km" class="inputbox" type="text" maxlength="15"></div>
                  </div>
                  <div class="row2">
                     <div class="width40">
                        <? echo $la['VEHICLE_STATUS']; ?>
                     </div>
                     <div class="width60">
                        <select id="dialog_settings_object_edit_vehclie_status" class="inputbox" maxlength="15">
                            <option value=''>Select</option>
                            <!-- <option value='service'>Service</option>
                            <option value='sales'>Sale</option> -->
                            <option value='maintenance'>Maintenance</option>
                        </select>
                     </div>
                  </div>
					
                </div>
                <div class="row">
                    <div class="title-block">
                        <? echo $la['COUNTERS']; ?>
                    </div>
                    <div class="row2">
                        <div class="width40">
                            <? echo $la['ODOMETER']. ' ('.$la["UNIT_DISTANCE"].')'; ?>
                        </div>
                        <div class="width19">
                            <select style="display:none" id="dialog_settings_object_edit_odometer_type" class="width100">
                                <option value="off">
                                    <? echo $la['OFF']; ?>
                                </option>
                                <option value="gps">GPS</option>
                                <option value="sen">
                                    <? echo $la['SENSOR']; ?>
                                </option>
                            </select>
                        </div>
                        <div class="width1"></div>
                        <div class="width40">
                            <input id="dialog_settings_object_edit_odometer" onkeypress="return isNumberKey(event);" class="inputbox width100" type="text" value="" maxlength="15">
                        </div>
                    </div>
                    <div class="row2">
                        <div class="width40">
                            <? echo $la['ENGINE_HOURS']. ' (h)'; ?>
                        </div>
                        <div class="width19">
                            <select style="display:none" id="dialog_settings_object_edit_engine_hours_type" class="width100">
                                <option value="off">
                                    <? echo $la['OFF']; ?>
                                </option>
                                <option value="acc">ACC</option>
                                <option value="sen">
                                    <? echo $la['SENSOR']; ?>
                                </option>
                            </select>
                        </div>
                        <div class="width1"></div>
                        <div class="width40">
                            <input id="dialog_settings_object_edit_engine_hours" onkeypress="return isNumberKey(event);" class="inputbox width100" type="text" value="" maxlength="15">
                        </div>
                    </div>
                </div>
            </div>
            <div id="settings_object_icon">
                <div class="row">
                    <div class="title-block">
                        <? echo $la['ICON']; ?>
                    </div>
                    <div class="row2">
                        <div class="width40">
                            <? echo $la['SHOWN_ICON_ON_MAP']; ?>
                        </div>
                        <div class="width60">
                            <select id="dialog_settings_object_edit_map_icon" style="width:100px;">
                                <option value="arrow">
                                    <? echo $la['ARROW']; ?>
                                </option>
                                <option value="icon">
                                    <? echo $la['ICON']; ?>
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="row2">
                        <div class="width40">
                            <? echo $la['NO_CONNECTION_ARROW_COLOR']; ?>
                        </div>
                        <div class="width60">
                            <select id="dialog_settings_object_edit_arrow_no_connection" style="width:100px;">
                                <? include ("inc/inc_arrow_colors.php"); ?>
                            </select>
                        </div>
                    </div>
                    <div class="row2">
                        <div class="width40">
                            <? echo $la['STOPPED_ARROW_COLOR']; ?>
                        </div>
                        <div class="width60">
                            <select id="dialog_settings_object_edit_arrow_stopped" style="width:100px;">
                                <? include ("inc/inc_arrow_colors.php"); ?>
                            </select>
                        </div>
                    </div>
                    <div class="row2">
                        <div class="width40">
                            <? echo $la['MOVING_ARROW_COLOR']; ?>
                        </div>
                        <div class="width60">
                            <select id="dialog_settings_object_edit_arrow_moving" style="width:100px;">
                                <? include ("inc/inc_arrow_colors.php"); ?>
                            </select>
                        </div>
                    </div>
                    <div class="row2">
                        <div class="width40">
                            <? echo $la['ENGINE_IDLE_ARROW_COLOR']; ?>
                        </div>
                        <div class="width60">
                            <select id="dialog_settings_object_edit_arrow_engine_idle" style="width:100px;">
                                <option value="off">
                                    <? echo $la['OFF']; ?>
                                </option>
                                <? include ("inc/inc_arrow_colors.php"); ?>
                            </select>
                        </div>
                    </div>
                    <div class="row2">
                        <div class="width40">
                            <? echo $la['ICON']; ?>
                        </div>
                        <div class="width60">
                            <a href="#" onclick="settingsObjectEditIcon();">
                                <div class="icon_selector" id="dialog_settings_object_edit_icon" style="width:32px; height: 32px;"></div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="title-block">
                        <? echo $la['TAIL']; ?>
                    </div>
                    <div class="row2">
                        <div class="width40">
                            <? echo $la['TAIL_COLOR']; ?>
                        </div>
                        <div class="width60">
                            <input class="color inputbox" style="width:64px" type='text' id='dialog_settings_object_edit_tail_color' />
                        </div>
                    </div>
                    <div class="row2">
                        <div class="width40">
                            <? echo $la['TAIL_POINTS_QUANTITY']; ?>
                        </div>
                        <div class="width60">
                            <input class="inputbox" style="width:100px" type='text' id='dialog_settings_object_edit_tail_points' maxlength="4" />
                        </div>
                    </div>
                </div>
            </div>
            <div id="settings_object_fuel">
                <div class="row">
                    <div class="title-block">
                        <? echo $la['CALCULATION']; ?>
                    </div>
                    <div class="row2">
                        <div class="width40">
                            <? echo $la['SOURCE'] ?>
                        </div>
                        <div class="width40">
                            <select id="dialog_settings_object_edit_fcr_source" style="width:100px;">
                                <option value="rates">
                                    <? echo $la['RATES'] ?>
                                </option>
                                <option value="fuel">
                                    <? echo $la['FUEL_LEVEL'] ?>
                                </option>
                                <option value="fuelcons">
                                    <? echo $la['FUEL_CONSUMPTION'] ?>
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="row2">
                        <div class="width40">
                            <? echo $la['MEASUREMENT'] ?>
                        </div>
                        <div class="width60">
                            <select style="width:100px;" id="dialog_settings_object_edit_fcr_measurement" onchange="settingsObjectEditSwitchFCRMeasurement();">
                                <option value="l100km">l/100km</option>
                                <option value="mpg">MPG</option>
                            </select>
                        </div>
                    </div>
                    <div class="row2">
                        <div class="width40" id="dialog_settings_object_edit_fcr_cost_label">
                            <? echo $la['COST_PER_LITER']; ?>
                        </div>
                        <div class="width60"><input style="width:100px;" id="dialog_settings_object_edit_fcr_cost" onkeypress="return isNumberKey(event);" class="inputbox" type="text" value="" maxlength="5" width="30px"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="title-block">
                        <? echo $la['RATES']; ?>
                    </div>
                    <div class="row2">
                        <div class="width40" id="dialog_settings_object_edit_fcr_summer_label">
                            <? echo $la['SUMMER_RATE_L100KM']; ?>
                        </div>
                        <div class="width60"><input style="width:100px;" id="dialog_settings_object_edit_fcr_summer" onkeypress="return isNumberKey(event);" class="inputbox" type="text" value="" maxlength="5" width="30px"></div>
                    </div>
                    <div class="row2">
                        <div class="width40" id="dialog_settings_object_edit_fcr_winter_label">
                            <? echo $la['WINTER_RATE_L100KM']; ?>
                        </div>
                        <div class="width60"><input style="width:100px;" id="dialog_settings_object_edit_fcr_winter" onkeypress="return isNumberKey(event);" class="inputbox" type="text" value="" maxlength="5" width="30px"></div>
                    </div>
                    <div class="row2">
                        <div class="width40">
                            <? echo $la['WINTER_FROM']; ?>
                        </div>
                        <div class="width60">
                            <input style="width:100px;" readonly class="inputbox-calendar-mmdd inputbox" id="dialog_settings_object_edit_fcr_winter_start" type="text" value="" />
                        </div>
                    </div>
                    <div class="row2">
                        <div class="width40">
                            <? echo $la['WINTER_TO'];?>
                        </div>
                        <div class="width60">
                            <input style="width:100px;" readonly class="inputbox-calendar-mmdd inputbox" id="dialog_settings_object_edit_fcr_winter_end" type="text" value="" />
                        </div>
                    </div>
                </div>
            </div>
            <div id="settings_object_accuracy">
                <div class="row">
                    <div class="title-block">
                        <? echo $la['ACCURACY']; ?>
                    </div>
                    <div class="row2">
                        <div class="width65">
                            <? echo $la['TIME_ADJ_EXPLANATION']; ?>
                        </div>
                        <div class="width5"></div>
                        <div class="width30">
                            <select class="width100" id="settings_object_accuracy_time_adj" onchange="settingsObjectEditSwitchTimeAdj();" />
                            <? include ("inc/inc_time_adj.php"); ?>
                            </select>
                        </div>
                    </div>
                    <div class="row2">
                        <div class="width65">
                            <? echo $la['DETECT_STOPS_USING']; ?>
                        </div>
                        <div class="width5"></div>
                        <div class="width30">
                            <select class="width100" id="settings_object_accuracy_detect_stops" />
                            <option value="gps">GPS</option>
                            <option value="acc">ACC</option>
                            <option value="gpsacc">GPS + ACC</option>
                            </select>
                        </div>
                    </div>
                    <div class="row2">
                        <div class="width65"><span class="container">
                                <? echo $la['MIN_MOVING_SPEED']; ?></span></div>
                        <div class="width5"></div>
                        <div class="width30"><input id="settings_object_accuracy_moving_speed" onkeypress="return isNumberKey(event);" class="inputbox" type="text" value="" maxlength="3" /></div>
                    </div>
                    <div class="row2">
                        <div class="width65"><span class="container">
                                <? echo $la['MIN_IDLE_SPEED']; ?></span></div>
                        <div class="width5"></div>
                        <div class="width30"><input id="settings_object_accuracy_idle_speed" onkeypress="return isNumberKey(event);" class="inputbox" type="text" value="" maxlength="3" /></div>
                    </div>
                    <div class="row2">
                        <div class="width65"><span class="container">
                                <? echo $la['MIN_DIFF_BETWEEN_POINTS']; ?></span></div>
                        <div class="width5"></div>
                        <div class="width30"><input id="settings_object_accuracy_diff_points" onkeypress="return isNumberKey(event);" class="inputbox" type="text" value="" maxlength="11" /></div>
                    </div>
                    <div class="row2">
                        <div class="width65"><span class="container">
                                <? echo $la['MIN_GPSLEV_VALUE']; ?></span></div>
                        <div class="width5"></div>
                        <div class="width5"><input id="settings_object_accuracy_use_gpslev" type="checkbox" class="checkbox" /></div>
                        <div class="width25"><input id="settings_object_accuracy_gpslev" onkeypress="return isNumberKey(event);" class="inputbox" type="text" value="" maxlength="2" /></div>
                    </div>
                    <div class="row2">
                        <div class="width65"><span class="container">
                                <? echo $la['MAX_HDOP_VALUE']; ?></span></div>
                        <div class="width5"></div>
                        <div class="width5"><input id="settings_object_accuracy_use_hdop" type="checkbox" class="checkbox" /></div>
                        <div class="width25"><input id="settings_object_accuracy_hdop" onkeypress="return isNumberKey(event);" class="inputbox" type="text" value="" maxlength="2" /></div>
                    </div>
                    <div class="row2">
                        <div class="width65"><span class="container">
                                <? echo $la['MIN_FF']; ?></span></div>
                        <div class="width5"></div>
                        <div class="width30"><input id="settings_object_accuracy_ff" onkeypress="return isNumberKey(event);" class="inputbox" type="text" value="" maxlength="2" /></div>
                    </div>
                    <div class="row2">
                        <div class="width65"><span class="container">
                                <? echo $la['MIN_FT']; ?></span></div>
                        <div class="width5"></div>
                        <div class="width30"><input id="settings_object_accuracy_ft" onkeypress="return isNumberKey(event);" class="inputbox" type="text" value="" maxlength="2" /></div>
                    </div>
                </div>
                <div class="row">
                    <div class="title-block">
                        <? echo $la['OTHER']; ?>
                    </div>
                    <div class="row2">
                        <div class="width65"><span class="container">
                                <? echo $la['CLEAR_DETECTED_SENSOR_CACHE']; ?></span></div>
                        <div class="width5"></div>
                        <div class="width30"><input style="width: 100px;" class="button" type="button" onclick="settingsObjectClearDetectedSensorCache();" value="<? echo $la['CLEAR']; ?>" /></div>
                    </div>
                </div>
            </div>

            <div id="settings_object_sensors">
                <div id="settings_object_sensors_list">
                    <table id="settings_object_sensor_list_grid"></table>
                    <div id="settings_object_sensor_list_grid_pager"></div>
                </div>
            </div>
            <div id="settings_object_service">
                <div id="settings_object_service_list">
                    <table id="settings_object_service_list_grid"></table>
                    <div id="settings_object_service_list_grid_pager"></div>
                </div>
            </div>
            <div id="settings_object_custom_fields">
                <div id="settings_object_custom_fields_list">
                    <table id="settings_object_custom_fields_list_grid"></table>
                    <div id="settings_object_custom_fields_list_grid_pager"></div>
                </div>
            </div>
            <div id="settings_object_working_hour">
                <div id="settings_object_working_hour_list">
                    <table id="settings_object_working_hour_list_grid"></table>
                    <div id="settings_object_working_hour_list_grid_pager"></div>
                </div>
            </div>
            <div id="settings_object_info">
                <div id="settings_object_info_list">
                    <table id="settings_object_info_list_grid"></table>
                    <div id="settings_object_info_list_grid_pager"></div>
                </div>
            </div>
			<center>
            <input class="button icon-save icon" id="settingsObjectEditSave" type="button" onclick="settingsObjectEdit('save');" value="<? echo $la['SAVE']; ?>" />&nbsp;
            <input class="button icon-close icon" id="settingsObjectEditcancel" type="button" onclick="settingsObjectEdit('cancel');" value="<? echo $la['CANCEL']; ?>" />
        </center>
        </div>

        
		</div>
		</div>
    </div>

    <div id="dialog_settings_object_duplicate" title="<? echo $la['DUPLICATE_OBJECT'];?>">
        <div class="row">
            <div class="row2">
                <div class="width30">
                    <? echo $la['NAME']; ?>
                </div>
                <div class="width70"><input id="dialog_settings_object_duplicate_name" class="inputbox" type="text" maxlength="25"></div>
            </div>
            <div class="row2">
                <div class="width30">
                    <? echo $la['IMEI']; ?>
                </div>
                <div class="width70"><input id="dialog_settings_object_duplicate_imei" class="inputbox" type="text" maxlength="15"></div>
            </div>
        </div>

        <center>
            <input class="button icon-save icon" type="button" onclick="settingsObjectDuplicate('duplicate');" value="<? echo $la['DUPLICATE']; ?>" />&nbsp;&nbsp;&nbsp;
            <input class="button icon-close icon" type="button" onclick="settingsObjectDuplicate('cancel');" value="<? echo $la['CANCEL']; ?>" />
        </center>
    </div>

    <div id="dialog_settings_object_add" title="<? echo $la['ADD_OBJECT'];?>">
        <div class="row">
            <div class="row2">
                <div class="width30">
                    <? echo $la['NAME']; ?>
                </div>
                <div class="width70"><input id="dialog_settings_object_add_name" class="inputbox" type="text" maxlength="25"></div>
            </div>
            <div class="row2">
                <div class="width30">
                    <? echo $la['IMEI']; ?>
                </div>
                <div class="width70"><input id="dialog_settings_object_add_imei" class="inputbox" type="text" maxlength="15"></div>
            </div>
        </div>

        <center>
            <input class="button icon-save icon" type="button" onclick="settingsObjectAdd('add');" value="<? echo $la['SAVE']; ?>" />&nbsp;
            <input class="button icon-close icon" type="button" onclick="settingsObjectAdd('cancel');" value="<? echo $la['CANCEL']; ?>" />
        </center>
    </div>

    <div id="dialog_settings_fullview" class="opensettings" style="display: none;padding-top: 40px;" title="<? echo $la['SETTINGS']; ?>">
 ]
        <div id="settings_main" class="fullview_mobvi">
        <div class="block fullviewheight width100">
		<div class="block width8">&nbsp;
        </div>
            <div class="block width17">
            <ul id="cmdmenu" class="changecmdmenu">
                <li id="settings_main_objects_tab" ><a href="#settings_main_objects" class="hvr-sweep-to-bottom  ">
                        <? echo $la['OBJECTS']; ?></a></li>
                <li id="settings_main_events_tab"><a href="#settings_main_events" class="hvr-sweep-to-bottom1  ">
                        <? echo $la['EVENTS']; ?></a></li>
                <li id="settings_main_templates_tab"><a href="#settings_main_templates" class="hvr-sweep-to-bottom2  ">
                        <? echo $la['TEMPLATES']; ?></a></li>
                <li id="settings_main_sms_tab"><a href="#settings_main_sms" class="hvr-sweep-to-bottom3  ">
                        <? echo $la['SMS']; ?></a></li>
                <li><a href="#settings_main_user_interface" class="hvr-sweep-to-bottom4  ">
                        <? echo $la['USER_INTERFACE']; ?></a></li>
                <li><a href="#settings_main_my_account" id="settings_main_my_account_tab" class="hvr-sweep-to-bottom5  ">
                        <? echo $la['MY_ACCOUNT']; ?></a></li>
                <li id="settings_main_subaccounts_tab"><a href="#settings_main_subaccounts" class="hvr-sweep-to-bottom6  ">
                        <? echo $la['SUB_ACCOUNTS']; ?></a></li>
                <?php global $user_id_allv; if(isset($gsValues['POST_RFID'][$user_id_allv])) {?>
                <li id="settings_main_shift_allocation_tab"><a href="#settings_main_shift_allocation" class="hvr-sweep-to-bottom3  ">
                        <? echo $la['SHIFT_ALLOCATION']; ?></a></li>
                <?php }?>

            </ul></div>
            <div class="block width3">
            <div class="vl"></div>
            </div>
            <div class="block width70" style="padding-left: 8px; height: -webkit-fill-available;
    width: 74%;">
            <div id="settings_main_objects">
                <div class="info">
                    <?
					if ($_SESSION["manager_id"] == 0)
					{
						$obj_add = $_SESSION["obj_add"];
						$obj_limit = $_SESSION["obj_limit"];
						$obj_limit_num = $_SESSION["obj_limit_num"];
						$obj_days = $_SESSION["obj_days"];
						$obj_days_dt = $_SESSION["obj_days_dt"];
					
						if ($obj_add == 'true')
						{
							if (($obj_limit == 'true') && ($obj_days == 'true'))
							{
								echo $la['YOU_CAN_ADD'].' '.$obj_limit_num.' '.$la['OBJECTS_TILL'].' '.$obj_days_dt.'.';	
							}
							
							if (($obj_limit == 'true') && ($obj_days == 'false'))
							{
								echo $la['YOU_CAN_ADD'].' '.$obj_limit_num.' '.strtolower($la['OBJECTS']).'.';	
							}
							
							if (($obj_limit == 'false') && ($obj_days == 'true'))
							{
								echo $la['YOU_CAN_ADD_UNLIMITED_NUMBER_OF_OBJECTS_TILL'].' '.$obj_days_dt.'.';
							}
							
							if (($obj_limit == 'false') && ($obj_days == 'false'))
							{
								echo $la['YOU_CAN_ADD_UNLIMITED_NUMBER_OF_OBJECTS'];
							}
						}
						else if($obj_add == 'false')
						{
							echo $la['CONTACT_ADMIN_IF_YOU_WANT_TO_DO_ANY_CHANGES'];
						}
						else if($obj_add == 'trial')
						{
							echo sprintf($la['NEWLY_ADDED_OBJECTS_CAN_BE_USED_FOR'], $gsValues['OBJ_DAYS_TRIAL']);
						}
					}
					else
					{
						echo $la['CONTACT_ADMIN_IF_YOU_WANT_TO_DO_ANY_CHANGES'];
					}
					
					if ($_SESSION["billing"] == true)
					{
						echo ' ';
						echo sprintf($la['ADDITIONAL_PLANS_CAN_BE_PURCHASED_VIA_BILLING_SYSTEM'], 'billingOpen();');
					}
				?>
                </div>
                <div id="settings_main_objects_groups_drivers">
                    <ul>
                        <li><a href="#settings_main_object_list">
                                <? echo $la['OBJECTS']; ?></a></li>
                        <li><a href="#settings_main_object_group_list">
                                <? echo $la['GROUPS']; ?></a></li>
                        <li><a href="#settings_main_object_driver_list">
                                <? echo $la['DRIVERS']; ?></a></li>
                        <li><a href="#settings_main_object_passenger_list">
                                <? echo $la['PASSENGERS']; ?></a></li>
                        <li><a href="#settings_main_object_trailer_list">
                                <? echo $la['TRAILERS']; ?></a></li>
                    </ul>
                    <div id="settings_main_object_list">
                        <div class="width100" style="padding-top: 7px;padding-bottom: 7px;"><input type="text" id="searchobjectsetting" class="width50" placeholder="Filter Objects" /></div>
                        <table id="settings_main_object_list_grid"></table>
                        <div id="settings_main_object_list_grid_pager"></div>
                    </div>
                    <div id="settings_main_object_group_list">
                        <table id="settings_main_object_group_list_grid"></table>
                        <div id="settings_main_object_group_list_grid_pager"></div>
                    </div>
                    <div id="settings_main_object_driver_list">
                        <table id="settings_main_object_driver_list_grid"></table>
                        <div id="settings_main_object_driver_list_grid_pager"></div>
                    </div>
                    <div id="settings_main_object_passenger_list">
                        <table id="settings_main_object_passenger_list_grid"></table>
                        <div id="settings_main_object_passenger_list_grid_pager"></div>
                    </div>
                    <div id="settings_main_object_trailer_list">
                        <table id="settings_main_object_trailer_list_grid"></table>
                        <div id="settings_main_object_trailer_list_grid_pager"></div>
                    </div>
                </div>
            </div>
            <div id="settings_main_events">
                <div id="settings_main_events_event_list">
                    <table id="settings_main_events_event_list_grid"></table>
                    <div id="settings_main_events_event_list_grid_pager"></div>
                </div>
            </div>
            <div id="settings_main_templates">
                <div id="settings_main_templates_template_list">
                    <table id="settings_main_templates_template_list_grid"></table>
                    <div id="settings_main_templates_template_list_grid_pager"></div>
                </div>
            </div>

            <div id="settings_main_sms">
                <div class="controls">
                    <input class="button icon-save icon" type="button" onclick="settingsSave();" value="<? echo $la['SAVE']; ?>">
                </div>

                <div class="row">
                    <div class="title-block">
                        <? echo $la['SMS_GATEWAY']; ?>
                    </div>
                    <div class="row2">
                        <div class="width30">
                            <? echo $la['ENABLE_SMS_GATEWAY']; ?>
                        </div>
                        <div class="width25"><input id="settings_main_sms_gateway" type="checkbox" class="checkbox" /></div>
                    </div>
                    <div class="row2">
                        <div class="width30">
                            <? echo $la['SMS_GATEWAY_TYPE']; ?>
                        </div>
                        <div class="width25">
                            <select class="width100" id="settings_main_sms_gateway_type" onchange="settingsSMSGatewaySwitchType();">
                                <option value="app" selected>
                                    <? echo $la['MOBILE_APPLICATION'];?>
                                </option>
                                <option value="http">HTTP</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div id="settings_main_sms_app">
                    <div class="row">
                        <div class="title-block">
                            <? echo $la['MOBILE_APPLICATION'];?>
                        </div>
                        <div class="row">
                            <? echo sprintf($la['SMS_GATEWAY_MOBILE_APPLICATION_EXPLANATION'], $gsValues['URL_SMS_GATEWAY_APP']); ?>
                        </div>
                        <div class="row2">
                            <div class="width30">
                                <? echo $la['SMS_GATEWAY_IDENTIFIER']; ?>
                            </div>
                            <div class="width25">
                                <input class="inputbox" id="settings_main_sms_gateway_identifier" readonly />
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width30">
                                <? echo $la['TOTAL_SMS_IN_QUEUE_TO_SEND']; ?>
                            </div>
                            <div class="width10" id="settings_main_sms_gateway_total_in_queue">0</div>
                            <div class="width1"></div>
                            <div class="width14">
                                <input class="button width100" type="button" onclick="settingsSMSGatewayClearQueue();" value="<? echo $la['CLEAR']; ?>" />
                            </div>
                        </div>
                    </div>
                </div>

                <div id="settings_main_sms_http" style="display: none;">
                    <div class="row">
                        <div class="title-block">HTTP</div>
                        <div class="row">
                            <? echo $la['SMS_GATEWAY_EXPLANATION']; ?>
                        </div>
                        <div class="row">
                            <? echo $la['SMS_GATEWAY_EXAMPLE']; ?>
                        </div>
                        <div class="row2">
                            <div class="width30">
                                <? echo $la['SMS_GATEWAY_URL']; ?>
                            </div>
                            <div class="width70">
                                <textarea id="settings_main_sms_gateway_url" style="height: 75px;" class="inputbox width100" maxlength="2048" placeholder="<? echo $la['EXAMPLE_SHORT'].' '.$la['HTTP_FULL_ADDRESS_HERE']; ?>" /></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="title-block">
                            <? echo $la['VARIABLES']; ?>
                        </div>
                        <div class="row">
                            <? echo $la['VAR_SMS_GATEWAY_NUMBER']; ?>
                        </div>
                        <div class="row">
                            <? echo $la['VAR_SMS_GATEWAY_MESSAGE']; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div id="settings_main_user_interface">
                <div class="controls">
                    <input class="button icon-save icon" type="button" onclick="settingsSave();" value="<? echo $la['SAVE']; ?>">
                </div>

                <div class="scroll-y">
                    <div class="row">
                        <div class="title-block">
                            <? echo $la['CHAT']; ?>
                        </div>
                        <div class="row2">
                            <div class="width40">
                                <? echo $la['NEW_CHAT_MESSAGE_SOUND_ALERT']; ?>
                            </div>
                            <div class="width25">
                                <select class="width100" id="settings_main_chat_notify_sound_file" /></select>
                            </div>
                            <div class="width1"></div>
                            <div class="width20">
                                <input class="button" type="button" onclick="settingsChatPlaySound();" value="<? echo $la['PLAY']; ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="title-block">
                            <? echo $la['MAP']; ?>
                        </div>
                        <div class="row2">
                            <div class="width40">
                                <? echo $la['MAP_STARTUP_POSITION']; ?>
                            </div>
                            <div class="width25">
                                <select class="width100" id="settings_main_map_startup_possition">
                                    <option value="default">
                                        <? echo $la['DEFAULT'];?>
                                    </option>
                                    <option value="last">
                                        <? echo $la['REMEMBER_LAST'];?>
                                    </option>
                                    <option value="fit">
                                        <? echo $la['FIT_OBJECTS'];?>
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width40">
                                <? echo $la['MAP_ICON_SIZE']; ?>
                            </div>
                            <div class="width25">
                                <select class="width100" id="settings_main_map_icon_size">
                                    <option value="1">100%</option>
                                    <option value="1.25">125%</option>
                                    <option value="1.5">150%</option>
                                    <option value="1.75">175%</option>
                                    <option value="2">200%</option>
                                </select>
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width40">
                                <? echo $la['HISTORY_ROUTE_COLOR']; ?>
                            </div>
                            <div class="width30"><input class="color inputbox" style="width:65px" type='text' id='settings_main_history_route_color' /></div>
                        </div>
                        <div class="row2">
                            <div class="width40">
                                <? echo $la['HISTORY_ROUTE_HIGHLIGHT_COLOR']; ?>
                            </div>
                            <div class="width30"><input class="color inputbox" style="width:65px" type='text' id='settings_main_history_route_highlight_color' /></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="title-block">
                            <? echo $la['GROUPS']; ?>
                        </div>
                        <div class="row2">
                            <div class="width40">
                                <? echo $la['COLLAPSED']; ?>
                            </div>
                            <div class="width60">
                                <div class="margin-right-3"><input id="settings_main_groups_collapsed_objects" type="checkbox" class="checkbox" /></div>
                                <div class="margin-right-3">
                                    <? echo $la['OBJECTS']; ?>
                                </div>
                                <div class="margin-right-3"><input id="settings_main_groups_collapsed_markers" type="checkbox" class="checkbox" /></div>
                                <div class="margin-right-3">
                                    <? echo $la['MARKERS']; ?>
                                </div>
                                <div class="margin-right-3"><input id="settings_main_groups_collapsed_routes" type="checkbox" class="checkbox" /></div>
                                <div class="margin-right-3">
                                    <? echo $la['ROUTES']; ?>
                                </div>
                                <div class="margin-right-3"><input id="settings_main_groups_collapsed_zones" type="checkbox" class="checkbox" /></div>
                                <div class="margin-right-3">
                                    <? echo $la['ZONES']; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="title-block">
                            <? echo $la['OBJECT_LIST']; ?>
                        </div>
                        <div class="row2">
                            <div class="width40">
                                <? echo $la['DETAILS']; ?>
                            </div>
                            <div class="width25">
                                <select class="width100" id="settings_main_od">
                                    <option value="">
                                        <? echo $la['TIME_POSITION'];?>
                                    </option>
                                    <option value="server">
                                        <? echo $la['TIME_SERVER'];?>
                                    </option>
                                    <option value="status">
                                        <? echo $la['STATUS'];?>
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width40">
                                <? echo $la['NO_CONNECTION_COLOR']; ?>
                            </div>
                            <div class="width4">
                                <input id="settings_main_ohc_no_connection" type="checkbox" class="checkbox" />
                            </div>
                            <div class="width30"><input class="color inputbox" style="width:65px" type='text' id='settings_main_ohc_no_connection_color' /></div>
                        </div>
                        <div class="row2">
                            <div class="width40">
                                <? echo $la['STOPPED_COLOR']; ?>
                            </div>
                            <div class="width4">
                                <input id="settings_main_ohc_stopped" type="checkbox" class="checkbox" />
                            </div>
                            <div class="width30"><input class="color inputbox" style="width:65px" type='text' id='settings_main_ohc_stopped_color' /></div>
                        </div>
                        <div class="row2">
                            <div class="width40">
                                <? echo $la['MOVING_COLOR']; ?>
                            </div>
                            <div class="width4">
                                <input id="settings_main_ohc_moving" type="checkbox" class="checkbox" />
                            </div>
                            <div class="width30"><input class="color inputbox" style="width:65px" type='text' id='settings_main_ohc_moving_color' /></div>
                        </div>
                        <div class="row2">
                            <div class="width40">
                                <? echo $la['ENGINE_IDLE_COLOR']; ?>
                            </div>
                            <div class="width4">
                                <input id="settings_main_ohc_engine_idle" type="checkbox" class="checkbox" />
                            </div>
                            <div class="width30"><input class="color inputbox" style="width:65px" type='text' id='settings_main_ohc_engine_idle_color' /></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="title-block">
                            <? echo $la['OTHER']; ?>
                        </div>
                        <div class="row2">
                            <div class="width40">
                                <? echo $la['LANGUAGE']; ?>
                            </div>
                            <div class="width25">
                                <select class="width100" id="settings_main_language">
                                    <? echo getLanguageList(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width40">
                                <? echo $la['UNIT_OF_DISTANCE']; ?>
                            </div>
                            <div class="width25">
                                <select class="width100" id="settings_main_distance_unit">
                                    <option value="km">
                                        <? echo $la['KILOMETER'];?>
                                    </option>
                                    <option value="mi">
                                        <? echo $la['MILE'];?>
                                    </option>
                                    <option value="nm">
                                        <? echo $la['NAUTICAL_MILE'];?>
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width40">
                                <? echo $la['UNIT_OF_CAPACITY']; ?>
                            </div>
                            <div class="width25">
                                <select class="width100" id="settings_main_capacity_unit">
                                    <option value="l">
                                        <? echo $la['LITER'];?>
                                    </option>
                                    <option value="g">
                                        <? echo $la['GALLON'];?>
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width40">
                                <? echo $la['UNIT_OF_TEMPERATURE']; ?>
                            </div>
                            <div class="width25">
                                <select class="width100" id="settings_main_temperature_unit">
                                    <option value="c">
                                        <? echo $la['CELSIUS'];?>
                                    </option>
                                    <option value="f">
                                        <? echo $la['FAHRENHEIT'];?>
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width40">
                                <? echo $la['CURRENCY']; ?>
                            </div>
                            <div class="width25">
                                <input id="settings_main_currency" class="inputbox width100" type="text" value="" maxlength="3">
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width40">
                                <? echo $la['TIMEZONE']; ?>
                            </div>
                            <div class="width25">
                                <select class="width100" id="settings_main_timezone">
                                    <? include ("inc/inc_timezones.php"); ?>
                                </select>
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width40">
                                <? echo $la['DAYLIGHT_SAVING_TIME']; ?>
                            </div>
                            <div class="width4">
                                <input id="settings_main_dst" type="checkbox" class="checkbox" onchange="settingsCheck();" />
                            </div>
                            <div class="width10">
                                <input readonly class="inputbox-calendar-mmdd inputbox width100" id="settings_main_dst_start_mmdd" type="text" value="" />
                            </div>
                            <div class="width1"></div>
                            <div class="width10">
                                <select class="width100" id="settings_main_dst_start_hhmm">
                                    <? include ("inc/inc_dt.hours_minutes.php"); ?>
                                </select>
                            </div>
                            <div class="width2 center-middle">-</div>
                            <div class="width10">
                                <input readonly class="inputbox-calendar-mmdd inputbox width100" id="settings_main_dst_end_mmdd" type="text" value="" />
                            </div>
                            <div class="width1"></div>
                            <div class="width10">
                                <select class="width100" id="settings_main_dst_end_hhmm">
                                    <? include ("inc/inc_dt.hours_minutes.php"); ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

           <div id="settings_main_my_account">
	<div class="controls">
		<input class="button icon-save icon" type="button" onclick="settingsSave();" value="<? echo $la['SAVE']; ?>">
	</div>
	<div class="row">
	   
		<div class="block width100">    
				<div class="container last">
					<div class="block width45">
						<div class="title-block"><? echo $la['CONTACT_INFO']; ?></div>
						<div class="row2">
							<div class="width40"><? echo $la['CATEGORY']; ?></div>
							<div class="width60"><input class="inputbox" id="settings_main_user_category"></div>
						</div>
						<div class="row2">
							<div class="width40"><? echo $la['CLIENT_NAME']; ?></div>
							<div class="width60"><input class="inputbox" id="settings_main_user_client_name"></div>
						</div>
						<div class="row2">
							<div class="width40"><? echo $la['GST_NO']; ?></div>
							<div class="width5"><input class="" type="checkbox" id="settings_main_user_client_gstn_no" onclick="checkBoxEnable('settings_main_user_client_gstn_no');"></div>
							<div class="width55"><input class="inputbox" id="settings_main_user_client_gstn_no_txt" disabled=""></div>
						</div>
						<div class="row2">
							<div class="width40"><? echo $la['DOCUMENT_IMAGE']; ?></div>
							<div class="width60"><input class="inputbox" id="settings_main_user_document_img"></div>
						</div>
						<div class="row2">
								<div class="width40"><? echo $la['ADDRESS']; ?></div>
								<div class="width60">
									<textarea class="inputbox" id="settings_main_user_address" style="height:50px"></textarea></div>
						</div>
						<div class="row2">
								<div class="width40"><? echo $la['PHONE']; ?></div>
								<div class="width60"><input class="inputbox" id="settings_main_user_phone_number"></div>
						</div>
						 <div class="row2">
								<div class="width40"><? echo $la['EMAIL']; ?></div>
								<div class="width60"><input class="inputbox" id="settings_main_user_emailid"></div>
						</div>
						 <div class="title-block"><? echo $la['BANK_DETAILS']; ?></div>
						<div class="row2">
								<div class="width40"><? echo $la['BANK_NAME']; ?></div>
								<div class="width60"><input class="inputbox" id="settings_main_user_bank_name"></div>
						</div>
						<div class="row2">
								<div class="width40"><? echo $la['BRANCH_NAME']; ?></div>
								<div class="width60"><input class="inputbox" id="settings_main_user_branck_name"></div>
						</div>
						<div class="row2">
								<div class="width40"><? echo $la['ACCOUNT_NO']; ?></div>
								<div class="width60"><input class="inputbox" id="settings_main_user_account_no"></div>
						</div>
						<div class="row2">
								<div class="width40"><? echo $la['IFSC_CODE']; ?></div>
								<div class="width60"><input class="inputbox" id="settings_main_user_ifsc"></div>
						</div>
					</div>
					<div class="block width10">&nbsp;</div>
					<div class="block width45">
					   
						<div class="title-block"><? echo $la['BILLING_DETAILS']; ?></div>
						<div class="row2">
								<div class="width40"><? echo $la['BILLING_NAME']; ?></div>
								<div class="width60"><input class="inputbox" id="settings_main_user_billing_name"></div>
						</div>
						<div class="row2">
								<div class="width40"><? echo $la['BILLING_ADDRESS']; ?></div>
								<div class="width60"><textarea class="inputbox" id="settings_main_user_billing_address" style="height:50px"></textarea></div>
						</div>
						<div class="row2">
								<div class="width40"><? echo $la['BILLING_MODE']; ?></div>
								<div class="width60"><input class="inputbox" id="settings_main_user_billing_mode"></div>
						</div>
                        <div class="row2">
                                <div class="width40"><? echo $la['AMC']; ?></div>
                               <div class="width5"><input class="" type="checkbox" id="settings_main_user_amc_checkbox" onclick="checkBoxEnable('settings_main_user_amc_checkbox');"></div>
                                <div class="width55"><input class="inputbox" id="settings_main_user_amc_checkbox_txt" disabled=""></div>
                        </div>
                        <div class="row2">
                                <div class="width40"><? echo $la['BILLING_CYCLE']; ?></div>
                                <div class="width60">
                                    <select  class="inputbox" id="settings_main_user_billing_cycle">
                                        <option>Monthly</option>
                                        <option>Quarterly</option>
                                        <option>Half-yearly</option>
                                        <option>yearly</option>
                                    </select>
                                </div>
                        </div>
						<div class="row2">
								<div class="width40"><? echo $la['COMMERCIAL_MAIL_ID']; ?></div>
								<div class="width60"><input class="inputbox" id="settings_main_user_commercial_mailid"></div>
						</div>
						<div class="title-block"><? echo $la['ADDITIONAL_CONTACT']; ?></div>
						<div class="row2">
								<div class="width40"><? echo $la['NAME']; ?></div>
								<div class="width60"><input class="inputbox" id="settings_main_user_add_name"></div>
						</div>
						<div class="row2">
								<div class="width40"><? echo $la['PHONE']; ?></div>
								<div class="width60"><input class="inputbox" id="settings_main_user_add_phone"></div>
						</div>   
						 <div class="row2">
								<div class="width40"><? echo $la['EMAILID']; ?></div>
								<div class="width60"><input class="inputbox" id="settings_main_user_add_emailid"></div>
						</div>
						 <div class="row2">
								<div class="width40"><? echo $la['DESIGNATION']; ?></div>
								<div class="width60"><textarea style="height:50px" class="inputbox" id="settings_main_user_add_designation"></textarea></div>
						</div>
															
						<div class="row2">
								<div class="width40"><? echo $la['DESCRIPTION']; ?></div>
								<div class="width60">
									<textarea id="settings_main_user_add_description" class="inputbox" style="height:50px;"></textarea>
								</div>
						</div>
					</div>
				</div>
			</div>
				</div>
				<div class="row">
					<div class="title-block">
						<? echo $la['CHANGE_PASSWORD']; ?>
					</div>
					<div class="row2">
						<div class="width25">
							<? echo $la['OLD_PASSWORD']; ?>
						</div>
						<div class="width75"><input class="inputbox width50" type="password" id="settings_main_old_password" maxlength=\"20\"></div>
					</div>
					<div class="row2">
						<div class="width25">
							<? echo $la['NEW_PASSWORD']; ?>
						</div>
						<div class="width75"><input class="inputbox width50" type="password" id="settings_main_new_password" maxlength=\"20\"></div>
					</div>
					<div class="row2">
						<div class="width25">
							<? echo $la['REPEAT_NEW_PASSWORD']; ?>
						</div>
						<div class="width75"><input class="inputbox width50" type="password" id="settings_main_new_password_rep" maxlength=\"20\"></div>
					</div>
				</div>
			</div>
			
            <div id="settings_main_subaccounts">
                <div class="info">
                    <? echo $la['SUB_ACCOUNTS_CAN_SPLIT_THIS_ACCOUNT_INTO_MULTIPLE_SMALLER_ACCOUNTS']; ?>
                </div>
                <div id="settings_main_subaccount_list">
                    <table id="settings_main_subaccount_list_grid"></table>
                    <div id="settings_main_subaccount_list_grid_pager"></div>
                </div>
            </div>

            <?php global $user_id_allv; if(isset($gsValues['POST_RFID'][$user_id_allv])) {?>
            <div id="settings_main_shift_allocation">
                <?php } else {?>
                <div id="settings_main_shift_allocation" style="display:none;">
                    <?php }?>
                    <div id="settings_main_shift_allocation_list">
                        <table id="settings_main_shift_allocation_list_grid"></table>
                        <div id="settings_main_shift_allocation_list_pager"></div>
                    </div>
                </div>


            </div>
			</div>
			</div>
        </div>
