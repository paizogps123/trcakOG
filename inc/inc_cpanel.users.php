<div id="dialog_send_email" title="<? echo $la['SEND_EMAIL']; ?>">
        <div class="row">
                <div class="row2">
                        <div class="width20"><? echo $la['SEND_TO']; ?></div>
                        <div class="width80">
                                <select id="send_email_send_to" class="width100" onchange="sendEmailSendToSwitch('test');">
                                        <option value="all"><? echo $la['ALL_USER_ACCOUNTS']; ?></option>
                                        <option value="selected"><? echo $la['SELECTED_USER_ACCOUNTS']; ?></option>
                                </select>
                        </div>
                </div>
                <div class="row2" id="send_email_username_row">
                        <div class="width20"><? echo $la['USERNAME']; ?></div>
                        <div class="width80"><select id="send_email_username" multiple="multiple" class="width100"></select></div>
                </div>
                <div class="row2">
                        <div class="width20"><? echo $la['SUBJECT']; ?></div>
                        <div class="width80"><input id="send_email_subject" class="inputbox" type="text" value="" maxlength="50"></div>
                </div>
                <div class="row3">
                        <div class="width20"><? echo $la['MESSAGE']; ?></div>
                        <div class="width80"><textarea id="send_email_message" class="inputbox" style="height: 250px;" type='text'></textarea></div>
                </div>
                <div class="row3">
                        <div class="width20"><? echo $la['STATUS']; ?></div>
                        <div class="width80"><div id="send_email_status" style="text-align:center;"></div></div>
                </div>
        </div>
        
        <center>
                <input class="button icon-time icon" type="button" onclick="sendEmail('test');" value="<? echo $la['TEST']; ?>" />&nbsp;
                <input class="button icon-create icon" type="button" onclick="sendEmail('send');" value="<? echo $la['SEND']; ?>" />&nbsp;
                <input class="button icon-close icon" type="button" onclick="sendEmail('cancel');" value="<? echo $la['CANCEL']; ?>" />
        </center>
</div>

<div id="dialog_user_add" title="<? echo $la['ADD_USER']; ?>">
        <div class="row">
                <div class="row2">
                        <div class="width40"><? echo $la['EMAIL']; ?></div>
                        <div class="width60"><input id="dialog_user_add_email" class="inputbox" type="text" maxlength="50"></div>
                </div>
                <div class="row2">
                        <div class="width40"><? echo $la['SEND_CREDENTIALS']; ?></div>
                        
                </div>
        </div>
        
        <center>
                <input class="button icon-new icon" type="button" onclick="userAdd('register');" style="padding-left: 23px !important;" value="<? echo $la['REGISTER']; ?>" />&nbsp;
                <input class="button icon-close icon" type="button" onclick="userAdd('cancel');" value="<? echo $la['CANCEL']; ?>" />
        </center>
</div>

<div id="dialog_user_edit" title="<? echo $la['USER_DETAILS']; ?>">
        <div id="dialog_user_edit_tabs">
                <ul>           
                        <li id="dialog_user_edit_account_tab"><a href="#dialog_user_edit_account"><? echo $la['ACCOUNT']; ?></a></li>
                        <li><a href="#dialog_user_edit_contact_info"><? echo $la['CONTACT_INFO']; ?></a></li>
                        <li id="dialog_user_edit_settings_tab"><a href="#dialog_user_edit_settings"><? echo $la['SETTINGS']; ?></a></li>
                        <li id="dialog_user_edit_subaccounts_tab"><a href="#dialog_user_edit_subaccounts"><? echo $la['SUB_ACCOUNTS']; ?></a></li>
                        <li id="dialog_user_edit_objects_tab"><a href="#dialog_user_edit_objects"><? echo $la['OBJECTS']; ?></a></li>
                        <? if ($_SESSION["billing"] == true) { ?>
                        <li id="dialog_user_edit_billing_plans_tab"><a href="#dialog_user_edit_billing_plans"><? echo $la['BILLING_PLANS']; ?></a></li>
                        <? } ?>
                        <li id="dialog_user_edit_usage_tab"><a href="#dialog_user_edit_usage"><? echo $la['USAGE']; ?></a></li>
                </ul>
                
                <div id="dialog_user_edit_account">
                        <div class="controls">
                            <input class="button icon-save icon" type="button" onclick="userEdit('save');" value="<? echo $la['SAVE']; ?>" id="adduser_save" style='float: left;'>
                            <input class="button icon-key icon" type="button" onclick="userEditLogin();" value="<? echo $la['LOGIN_AS_USER']; ?>" id="adduser_loginuser">
                            <input class="button" type="button" onclick="userAdd('register');" value="<? echo $la['REGISTER']; ?>" hidden="" id="adduser_register">
                        </div>					
                        <div class="block width40">						
                                <div class="container">
                                        <div class="row">
                                                <div class="title-block"><? echo $la['USER']; ?></div>
                                                <div class="row2">
                                                        <div class="width40"><? echo $la['ACTIVE']; ?></div>
                                                        <div class="width60"><input id="dialog_user_edit_account_active" class="checkbox" type="checkbox" /></div>
                                                </div>
                                                  <div class="row2">
                                                        <div class="width40"><? echo $la['AMBULANCE']; ?></div>
                                                        <div class="width60"><input id="dialog_user_edit_account_ambulance" class="checkbox" type="checkbox" /></div>
                                                </div>
                                                <div class="row2" id="dialog_user_add_send_display">
                                                    <div class="width40"><? echo $la['SEND_CREDENTIALS']; ?></div>
                                                    <div class="width60"><input id="dialog_user_add_send" type="checkbox" class="checkbox" checked/></div>
                                            </div>
                                                
                                                <div class="row2">
                                                        <div class="width40"><? echo $la['STAFF_MANAG']; ?></div>
                                                        <div class="width60"><input id="dialog_user_edit_account_staff_manag" class="checkbox" type="checkbox" /></div>
                                                </div>
                                                <div class="row2">
                                                        <div class="width40"><? echo $la['INVENTORY']; ?></div>
                                                        <div class="width60"><input id="dialog_user_edit_account_inventory_manag" class="checkbox" type="checkbox" /></div>
                                                </div>                                                
                                                
                                                <div class="row2">
                                                        <div class="width40"><? echo $la['USERNAME']; ?></div>
                                                        <div class="width60"><input id="dialog_user_edit_account_username" class="inputbox" maxlength="50" /></div>
                                                </div>
                                                <div class="row2">
                                                        <div class="width40"><? echo $la['EMAIL']; ?></div>
                                                        <div class="width60"><input id="dialog_user_edit_account_email" class="inputbox" maxlength="50" /></div>
                                                </div>
                                                <div class="row2">
                                                        <div class="width40"><? echo $la['PASSWORD']; ?></div>
                                                        <div class="width60"><input id="dialog_user_edit_account_password" class="inputbox" maxlength="20" placeholder="<? echo $la['ENTER_NEW_PASSWORD']; ?>"/></div>
                                                </div>
                                                <div class="row2">
                                                        <div class="width40"><? echo $la['PRIVILEGES']; ?></div>
                                                        <div class="width60"><select class="width100" id="dialog_user_edit_account_privileges" onChange="userEditCheck();"></select></div>
                                                </div>
                                                <? if (($_SESSION["cpanel_privileges"] == 'super_admin') || ($_SESSION["cpanel_privileges"] == 'admin')) { ?>
                                                <div class="row2">
                                                        <div class="width40"><? echo $la['MANAGER']; ?></div>
                                                        <div class="width60"><select class="width100" id="dialog_user_edit_account_manager_id" onChange="userEditCheck();"></select></div>
                                                </div>
                                                <? } ?>
                                                <div class="row2">
                                                        <div class="width40">
                                                                <? echo $la['EXPIRE_ON']; ?>
                                                        </div>
                                                        <div class="width10">
                                                                <input id="dialog_user_edit_account_expire" type="checkbox" class="checkbox" onChange="userEditCheck();"/>
                                                        </div>
                                                        <div class="width50">
                                                                <input class="inputbox-calendar inputbox width100" id="dialog_user_edit_account_expire_dt"/>
                                                        </div>
                                                </div>
                                        </div>
                                        <? if (($_SESSION["cpanel_privileges"] == 'super_admin') || ($_SESSION["cpanel_privileges"] == 'admin')) { ?>
                                        <div class="row" id="manager_privileges_tab">
                                            <div class="title-block"><? echo $la['MANAGER_PRIVILEGES']; ?></div>
                                            <div class="row2">
                                                <div class="width40"><? echo $la['BILLING']; ?></div>
                                                <div class="width60">
                                                    <select style="width: 100px;" id="dialog_user_edit_account_manager_billing">
                                                            <option value="true"><? echo $la['YES']; ?></option>
                                                            <option value="false"><? echo $la['NO']; ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <? } ?>
                                </div>
                        </div>
                        
                       <div class="block width60">
                                <div class="container last">
                                        <div class="row">
                                             <div style="height: 460px; overflow-y: scroll;">
                                                  <?$manag_pri="";//if ($_SESSION["manager_id"] == $_SESSION["user_id"] ){ $manag_pri='style="display:none;"'; }?>
                                                  <div <?echo $manag_pri;?> id="displaymanager_settings">
                                                <div  class="title-block"><? echo $la['MENU_PRIVILEGES']; ?></div>
                                                        <div class="row2">
                                                            <div class="width50"><? echo $la['HISTORY']; ?></div>
                                                            <div class="width50">
                                                                    <select style="width: 100px;" id="dialog_user_edit_account_history">
                                                                            <option value="true"><? echo $la['YES']; ?></option>
                                                                            <option value="false"><? echo $la['NO']; ?></option>
                                                                    </select>
                                                            </div>
                                                        </div>
                     <!-- <?$obconsty="";if ($_SESSION["privileges_object_control"] != true && $_SESSION["cpanel_privileges"]=='manager'){ $obconsty='style="display:none;"'; }?> -->
                     <?$obconsty="";if ($_SESSION["privileges_object_control"] != 'true' && $_SESSION["cpanel_privileges"]=='manager'){ $obconsty='style="display:none;"'; }?>

                     <?$reportsty="";if ($_SESSION["privileges_reports"] != 'true' ){ $reportsty='style="display:none;"'; }?>

                     <?$imgsty="";if ($_SESSION["privileges_image_gallery"] != 'true' ){ $imgsty='style="display:none;"'; }?>

                     <?$rlogsty="";if ($_SESSION["privileges_rilogbook"] != 'true' ){ $rlogsty='style="display:none;"'; }?>

                     <?$balertsty="";if ($_SESSION["boarding_point_alert_system"] != 'true'){ $balertsty='style="display:none;"'; }?>

                     <?$smdsty="";if ($_SESSION["send_command"] != 'true'){ $smdsty='style="display:none;"'; }?>

                     <?$sossty="";if ($_SESSION["emergency_alert"] != 'true' ){ $sossty='style="display:none;"'; }?>

                    <?$livetrip="";if ($_SESSION["privileges_live_tripreport"] != 'true'){ $livetrip='style="display:none;"'; }?>


                                                        <div class="row2" <?echo $reportsty;?> >
                                                                <div class="width50"><? echo $la['REPORTS']; ?></div>
                                                                <div class="width50">
                                                                        <select style="width: 100px;" id="dialog_user_edit_account_reports">
                                                                                <option value="true"><? echo $la['YES']; ?></option>
                                                                                <option value="false"><? echo $la['NO']; ?></option>
                                                                        </select>
                                                                </div>
                                                        </div>
                                                        <div class="row2" <?echo $sossty;?> >
                                                            <div class="width50"><? echo $la['EMERGENCY_ALERT']; ?></div>
                                                            <div class="width50">
                                                                    <select style="width: 100px;" id="dialog_user_edit_account_sos">
                                                                            <option value="true"><? echo $la['YES']; ?></option>
                                                                            <option value="false"><? echo $la['NO']; ?></option>
                                                                    </select>
                                                            </div>
                                                        </div>
                                                        <div class="row2" <?echo $balertsty;?> >
                                                                <div class="width50"><? echo $la['BOARDING']; ?></div>
                                                                <div class="width50">
                                                                        <select style="width: 100px;" id="dialog_user_edit_account_boarding">
                                                                                <option value="true"><? echo $la['YES']; ?></option>
                                                                                <option value="false"><? echo $la['NO']; ?></option>
                                                                        </select>
                                                                </div>
                                                        </div>
                                                        <div <?echo $smdsty;?> class="row2">
                                                                <div class="width50"><? echo $la['SEND_COMMAND']; ?></div>
                                                                <div class="width50">
                                                                        <select style="width: 100px;" id="dialog_user_edit_account_sendcommand">
                                                                                <option value="true"><? echo $la['YES']; ?></option>
                                                                                <option value="false"><? echo $la['NO']; ?></option>
                                                                        </select>
                                                                </div>
                                                        </div>
                                                        <div <?echo $rlogsty;?> class="row2">
                                                                <div class="width50"><? echo $la['RFID_AND_IBUTTON_LOGBOOK']; ?></div>
                                                                <div class="width50">
                                                                        <select style="width: 100px;" id="dialog_user_edit_account_rilogbook">
                                                                                <option value="true"><? echo $la['YES']; ?></option>
                                                                                <option value="false"><? echo $la['NO']; ?></option>
                                                                        </select>
                                                                </div>
                                                        </div>
                                                         <div <?echo $obconsty;?> class="row2">
                                                                <div class="width50"><? echo $la['OBJECT_CONTROL']; ?></div>
                                                                <div class="width50">
                                                                        <select style="width: 100px;" id="dialog_user_edit_account_object_control">
                                                                                <option value="true"><? echo $la['YES']; ?></option>
                                                                                <option value="false"><? echo $la['NO']; ?></option>
                                                                        </select>
                                                                </div>
                                                        </div>
                                                        <div <? echo $imgsty;?> class="row2">
                                                                <div class="width50"><? echo $la['IMAGE_GALLERY']; ?></div>
                                                                <div class="width50">
                                                                        <select style="width: 100px;" id="dialog_user_edit_account_image_gallery">
                                                                                <option value="true"><? echo $la['YES']; ?></option>
                                                                                <option value="false"><? echo $la['NO']; ?></option>
                                                                        </select>
                                                                </div>
                                                        </div>
                                                        <div <? echo $livetrip;?> class="row2">
                                                                <div class="width50"><? echo $la['LIVE_TRIP_REPORT']; ?></div>
                                                                <div class="width50">
                                                                        <select style="width: 100px;" id="dialog_user_edit_account_live_tripreport">
                                                                                <option value="true"><? echo $la['YES']; ?></option>
                                                                                <option value="false"><? echo $la['NO']; ?></option>
                                                                        </select>
                                                                </div>
                                                        </div>
                                                        <div class="row2" style="display: none;">
                                                                <div class="width50"><? echo $la['CHAT']; ?></div>
                                                                <div class="width50">
                                                                        <select style="width: 100px;" id="dialog_user_edit_account_chat">
                                                                                <option value="true"><? echo $la['YES']; ?></option>
                                                                                <option value="false"><? echo $la['NO']; ?></option>
                                                                        </select>
                                                                </div>
                                                        </div>
                                                    </div>
                                                    <div id="displaymanager_report">  
                                                        <div class="title-block"><? echo $la['REPORTS']; ?></div>
                                                        <div class="row2">
                                                            <div class="width100">
                                                                <select class="multi-select " id="dialog_generat_report_list" style="height:186px;" name="dialog_generat_report_list" data-plugin="multiselect" data-selectable-optgroup="true" multiple="multiple"></select>
                                                            </div>
                                                         </div> 
                                                         </div> 
                                                        <div class="title-block"><? echo $la['ACCOUNT_PRIVILEGES']; ?></div>

                                                        <? if (($_SESSION["cpanel_privileges"] == 'super_admin') || ($_SESSION["cpanel_privileges"] == 'admin')) { ?>
                                                        <div class="row2">
                                                                <div class="width50"><? echo $la['ADD_OBJECTS']; ?></div>
                                                                <div class="width50">
                                                                        <select id="dialog_user_edit_account_obj_add" style="width: 100px;" onChange="userEditCheck();">
                                                                                <option value="true"><? echo $la['YES']; ?></option>
                                                                                <option value="false"><? echo $la['NO']; ?></option>
                                                                                <option value="trial"><? echo $la['TRIAL']; ?></option>
                                                                        </select>
                                                                </div>
                                                        </div>
                                                        <div class="row2">
                                                                <div class="width50"><? echo $la['OBJECT_LIMIT']; ?></div>
                                                                <div class="width50">
                                                                        <select style="width: 100px;" id="dialog_user_edit_account_obj_limit" onChange="userEditCheck();">
                                                                                <option value="true"><? echo $la['YES']; ?></option>
                                                                                <option value="false"><? echo $la['NO']; ?></option>
                                                                        </select>
                                                                        <input id="dialog_user_edit_account_obj_limit_num" onkeypress="return isNumberKey(event);" class="inputbox" style="width:100px;" maxlength="4"/>
                                                                </div>
                                                        </div>
                                                        <div class="row2">
                                                                <div class="width50"><? echo $la['OBJECT_DATE_LIMIT']; ?></div>
                                                                <div class="width50">
                                                                        <select style="width: 100px;" id="dialog_user_edit_account_obj_days" onChange="userEditCheck();">
                                                                                <option value="true"><? echo $la['YES']; ?></option>
                                                                                <option value="false"><? echo $la['NO']; ?></option>
                                                                        </select>
                                                                        <input class="inputbox-calendar inputbox" style="width:100px;" id="dialog_user_edit_account_obj_days_dt"/>
                                                                </div>
                                                        </div>
                                                        <? } ?>
                                                        <div class="row2">
                                                                <div class="width50"><? echo $la['EDIT_OBJECTS']; ?></div>
                                                                <div class="width50">
                                                                        <select style="width: 100px;" id="dialog_user_edit_account_obj_edit">
                                                                                <option value="true"><? echo $la['YES']; ?></option>
                                                                                <option value="false"><? echo $la['NO']; ?></option>
                                                                        </select>
                                                                </div>
                                                        </div>
                                                        <div class="row2">
                                                                <div class="width50"><? echo $la['CLEAR_OBJECTS_HISTORY']; ?></div>
                                                                <div class="width50">
                                                                        <select style="width: 100px;" id="dialog_user_edit_account_obj_history_clear">
                                                                                <option value="true"><? echo $la['YES']; ?></option>
                                                                                <option value="false"><? echo $la['NO']; ?></option>
                                                                        </select>
                                                                </div>
                                                        </div>      
                                                        <div class="row2">
                                                                <div class="width50"><? echo $la['DIAGNOSTIC_TROUBLE_CODES']; ?></div>
                                                                <div class="width50">
                                                                        <select style="width: 100px;" id="dialog_user_edit_account_dtc">
                                                                                <option value="true"><? echo $la['YES']; ?></option>
                                                                                <option value="false"><? echo $la['NO']; ?></option>
                                                                        </select>
                                                                </div>
                                                        </div>                                                       
                                                        <div class="row2">
                                                                <div class="width50"><? echo $la['SUB_ACCOUNTS']; ?></div>
                                                                <div class="width50">
                                                                        <select style="width: 100px;" id="dialog_user_edit_account_subaccounts">
                                                                                <option value="true"><? echo $la['YES']; ?></option>
                                                                                <option value="false"><? echo $la['NO']; ?></option>
                                                                        </select>
                                                                </div>
                                                        </div>
                                                        <div class="row2">
                                                                <div class="width50"><? echo $la['SERVER_SMS_GATEWAY']; ?></div>
                                                                <div class="width50">
                                                                        <select style="width: 100px;" id="dialog_user_edit_account_sms_gateway_server">
                                                                                <option value="true"><? echo $la['YES']; ?></option>
                                                                                <option value="false"><? echo $la['NO']; ?></option>
                                                                        </select>
                                                                </div>
                                                        </div>
                                                        <div class="row2">
                                                                <div class="width50"><? echo $la['API']; ?></div>
                                                                <div class="width50">
                                                                        <select style="width: 100px;" id="dialog_user_edit_api_active">
                                                                                <option value="true"><? echo $la['YES']; ?></option>
                                                                                <option value="false"><? echo $la['NO']; ?></option>
                                                                        </select>
                                                                </div>
                                                        </div>
                                                        <div class="row2">
                                                                <div class="width50"><? echo $la['API_KEY']; ?></div>
                                                                <div class="width50">
                                                                        <input id="dialog_user_edit_api_key" class="inputbox width100" readOnly="true"/>
                                                                </div>
                                                        </div>
                                                        <div class="row2">
                                                                <div class="width50">
                                                                        <? echo $la['MAX_MARKERS']; ?>
                                                                </div>
                                                                <div class="width50">
                                                                        <input id="dialog_user_edit_places_markers" onkeypress="return isNumberKey(event);" class="inputbox" style="width: 100px;" maxlength="4" />
                                                                </div>
                                                        </div>
                                                        <div class="row2">
                                                                <div class="width50">
                                                                        <? echo $la['MAX_ROUTES']; ?>
                                                                </div>
                                                                <div class="width50">
                                                                        <input id="dialog_user_edit_places_routes" onkeypress="return isNumberKey(event);" class="inputbox" style="width: 100px;" maxlength="4" />
                                                                </div>
                                                        </div>
                                                        <div class="row2">
                                                                <div class="width50">
                                                                        <? echo $la['MAX_ZONES']; ?>
                                                                </div>
                                                                <div class="width50">
                                                                        <input id="dialog_user_edit_places_zones" onkeypress="return isNumberKey(event);" class="inputbox" style="width: 100px;" maxlength="4" />
                                                                </div>
                                                        </div>
                                                        <div class="row2">
                                                                <div class="width50">
                                                                        <? echo $la['MAX_EMAILS_DAILY']; ?>
                                                                </div>
                                                                <div class="width50">
                                                                        <input id="dialog_user_edit_usage_email_daily" onkeypress="return isNumberKey(event);" class="inputbox" style="width: 100px;" maxlength="8" />
                                                                </div>
                                                        </div>
                                                        <div class="row2">
                                                                <div class="width50">
                                                                        <? echo $la['MAX_SMS_DAILY']; ?>
                                                                </div>
                                                                <div class="width50">
                                                                        <input id="dialog_user_edit_usage_sms_daily" onkeypress="return isNumberKey(event);" class="inputbox" style="width: 100px;" maxlength="8" />
                                                                </div>
                                                        </div>
                                                        <div class="row2">
                                                                <div class="width50">
                                                                        <? echo $la['MAX_API_DAILY']; ?>
                                                                </div>
                                                                <div class="width50">
                                                                        <input id="dialog_user_edit_usage_api_daily" onkeypress="return isNumberKey(event);" class="inputbox" style="width: 100px;" maxlength="8" />
                                                                </div>
                                                        </div>
                                                        
                                                        <div class="row2">
                                        <div class="width50">
                                            <? echo $la['USERKEY']; ?>
                                        </div>
                                        <div class="width50">
                                            <input id="dialog_user_key" class="inputbox"  />
                                        </div>
                                    </div>
                            
                                    <div class="row2">
                                        <div class="width50">
                                            <? echo $la['USERIP']; ?>
                                        </div>
                                        <div class="width50">
                                            <input id="dialog_user_ip" class="inputbox"  />
                                        </div>
                                    </div>
                            
                                    <div class="row2">
                                        <div class="width50">
                                            <? echo $la['PERMISSION']; ?>
                                        </div>
                                        <div class="width5">
                                            <input id="chkspeed"  type="checkbox" name="permissionchk[]"  value="Speed" />
                                        </div>
                                        <div class="width5">
                                            Speed
                                        </div>
                                    </div>
                                    
                                    
                                                </div>
                                        </div>
                                </div>
                        </div>
                </div>
                
                <div id="dialog_user_edit_contact_info">
                        <div class="controls">
                            <input class="button icon-save icon" type="button" onclick="userEdit('save');" value="<? echo $la['SAVE']; ?>" id="adduser_save_con" style="float: left;">
                            <input class="button icon-key icon" type="button" onclick="userEditLogin();" value="<? echo $la['LOGIN_AS_USER']; ?>" id="adduser_loginuser_con">
                            <input class="button" type="button" onclick="userAdd('register');" value="<? echo $la['REGISTER']; ?>" hidden="" id="adduser_register_con">
                        </div>
                        
                        <div class="block width100">	
                            <div class="container last">
                                <div class="block width45">
                                    <div class="title-block"><? echo $la['CONTACT_INFO']; ?></div>
                                    <div class="row2">
                                        <div class="width40"><? echo $la['CATEGORY']; ?></div>
                                        <div class="width60"><input class="inputbox" id="user_category"></div>
                                    </div>
                                    <div class="row2">
                                        <div class="width40"><? echo $la['CLIENT_NAME']; ?></div>
                                        <div class="width60"><input class="inputbox" id="user_client_name"></div>
                                    </div>
                                    <div class="row2">
                                        <div class="width40"><? echo $la['GST_NO']; ?></div>
                                        <div class="width5"><input class="" type="checkbox" id="user_client_gstn_no" onclick="checkBoxEnable('user_client_gstn_no');"></div>
                                        <div class="width55"><input class="inputbox" id="user_client_gstn_no_txt" disabled=""></div>
                                    </div>
                                    <div class="row2">
                                        <div class="width40"><? echo $la['DOCUMENT_IMAGE']; ?></div>
                                        <div class="width60">
                                            <!-- <input class="inputbox" id="user_document_img"></div> -->
                                            <input id="user_document_img" class="inputbox"  hidden="" />
                                            <input id="addusersuccessMsg" class="inputbox"  disabled="" />
                                            <div class="width40">
                                                 <input style="width: 70px;" class="button" type="button" value="<? echo $la['UPLOAD']; ?>" onclick="upAdduserDoc();" />
                                             </div><div class="width40">
                                                  <a href="" id="add_user_gst_documentview" target="_blank" download><input class="button" type="button" id="add_user_gst_docview" value="Download"></a>
                                             </div> 
                                        </div>
                                    </div>
                                    <div class="row2">
                                            <div class="width40"><? echo $la['ADDRESS']; ?></div>
                                            <div class="width60">
                                                <textarea class="inputbox" id="user_address" style="height:50px"></textarea></div>
                                    </div>
                                    <div class="row2">
                                            <div class="width40"><? echo $la['PHONE']; ?></div>
                                            <div class="width60"><input class="inputbox" id="user_phone_number"></div>
                                    </div>
                                     <div class="row2">
                                            <div class="width40"><? echo $la['EMAIL']; ?></div>
                                            <div class="width60"><input class="inputbox" id="user_emailid"></div>
                                    </div>
                                     <div class="title-block"><? echo $la['BANK_DETAILS']; ?></div>
                                    <div class="row2">
                                            <div class="width40"><? echo $la['BANK_NAME']; ?></div>
                                            <div class="width60"><input class="inputbox" id="user_bank_name"></div>
                                    </div>
                                    <div class="row2">
                                            <div class="width40"><? echo $la['BRANCH_NAME']; ?></div>
                                            <div class="width60"><input class="inputbox" id="user_branck_name"></div>
                                    </div>
                                    <div class="row2">
                                            <div class="width40"><? echo $la['ACCOUNT_NO']; ?></div>
                                            <div class="width60"><input class="inputbox" id="user_account_no"></div>
                                    </div>
                                    <div class="row2">
                                            <div class="width40"><? echo $la['IFSC_CODE']; ?></div>
                                            <div class="width60"><input class="inputbox" id="user_ifsc"></div>
                                    </div>
                                </div>
                                <div class="block width10">&nbsp;</div>
                                <div class="block width45">
                                   
                                    <div class="title-block"><? echo $la['BILLING_DETAILS']; ?></div>
                                    <div class="row2">
                                            <div class="width40"><? echo $la['BILLING_NAME']; ?></div>
                                            <div class="width60"><input class="inputbox" id="user_billing_name"></div>
                                    </div>
                                    <div class="row2">
                                            <div class="width40"><? echo $la['BILLING_ADDRESS']; ?></div>
                                            <div class="width60"><textarea class="inputbox" id="user_billing_address" style="height:50px"></textarea></div>
                                    </div>
                                    <div class="row2">
                                            <div class="width40"><? echo $la['BILLING_MODE']; ?></div>
                                            <div class="width60"><input class="inputbox" id="user_billing_mode"></div>
                                    </div>
                                    <div class="row2">
                                        <div class="width40"><? echo $la['AMC']; ?></div>
                                        <div class="width5"><input class="" type="checkbox" id="user_amc_checkbox" onclick="checkBoxEnable('user_amc_checkbox');"></div>
                                        <div class="width55"><input class="inputbox" id="user_amc_checkbox_txt" disabled=""></div>
                                    </div>
                                    <div class="row2">
                                        <div class="width40"><? echo $la['BILLING_CYCLE']; ?></div>
                                        <div class="width60">
                                            <select  class="inputbox" id="user_billing_cycle">
                                                <option>Monthly</option>
                                                <option>Quarterly</option>
                                                <option>Half-yearly</option>
                                                <option>yearly</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row2">
                                            <div class="width40"><? echo $la['COMMERCIAL_MAIL_ID']; ?></div>
                                            <div class="width60"><input class="inputbox" id="user_commercial_mailid"></div>
                                    </div>
                                    <div class="title-block"><? echo $la['ADDITIONAL_CONTACT']; ?></div>
                                    <div class="row2">
                                            <div class="width40"><? echo $la['NAME']; ?></div>
                                            <div class="width60"><input class="inputbox" id="user_add_name"></div>
                                    </div>
                                    <div class="row2">
                                            <div class="width40"><? echo $la['PHONE']; ?></div>
                                            <div class="width60"><input class="inputbox" id="user_add_phone"></div>
                                    </div>   
                                     <div class="row2">
                                            <div class="width40"><? echo $la['EMAILID']; ?></div>
                                            <div class="width60"><input class="inputbox" id="user_add_emailid"></div>
                                    </div>
                                     <div class="row2">
                                            <div class="width40"><? echo $la['DESIGNATION']; ?></div>
                                            <div class="width60"><textarea style="height:50px" class="inputbox" id="user_add_designation"></textarea></div>
                                    </div>
                                                                        
                                    <div class="row2">
                                            <div class="width40"><? echo $la['DESCRIPTION']; ?></div>
                                            <div class="width60">
                                                <textarea id="user_add_description" class="inputbox" style="height:50px;"></textarea>
                                            </div>
                                    </div>
                                    <div class="row2">
                                            <div class="width40"><? echo $la['COMMENT']; ?></div>
                                            <div class="width60"><textarea style="height:50px" class="inputbox" id="dialog_user_edit_account_comment"></textarea></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                <div id="dialog_user_edit_settings">
                        <!-- <div class="controls">
                            <input class="button icon-save icon" type="button" onclick="userEdit('save');" value="<? echo $la['SAVE']; ?>" id="adduser_save_set" style="float: left;">
                            <input class="button icon-key icon" type="button" onclick="userEditLogin();" value="<? echo $la['LOGIN_AS_USER']; ?>" id="adduser_loginuser_set">
                            <input class="button" type="button" onclick="userAdd('register');" value="<? echo $la['REGISTER']; ?>" hidden="" id="adduser_register_set">
                        </div> -->
                        
                        <div class="block width100">    
                            <div class="container last">
                                <!-- <div class="block width95"> -->
                                    <table style="width: 70%;">
                                        <thead>
                                            <tr style='height: 30px;'>
                                                <th><? echo $la['NAME']; ?></th>
                                                <th><? echo $la['ADD']; ?></th>
                                                <th><? echo $la['EDIT']; ?></th>
                                                <th><? echo $la['DELETE']; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr style='text-align: center;height: 30px;'>
                                                <th><? echo $la['USER']; ?></th>
                                                <td><input type="checkbox" id="settings_user_add"></div></td>
                                                <td><input type="checkbox" id="settings_user_edit"></div></td>
                                                <td><input type="checkbox" id="settings_user_delete"></div></td>
                                            </tr>
                                            <tr style='text-align: center;height: 30px;'>
                                                <th><? echo $la['SUB_ACCOUNTS']; ?></th>
                                                <td><input type="checkbox" id="settings_subuser_add"></div></td>
                                                <td><input type="checkbox" id="settings_subuser_edit"></div></td>
                                                <td><input type="checkbox" id="settings_subuser_delete"></div></td>
                                            </tr>
                                            <tr style='text-align: center;height: 30px;'>
                                                <th><? echo $la['OBJECTS']; ?></th>
                                                <td><input type="checkbox" id="settings_object_add"></div></td>
                                                <td><input type="checkbox" id="settings_object_edit"></div></td>
                                                <td><input type="checkbox" id="settings_object_delete"></div></td>
                                            </tr>
                                            <tr style='text-align: center;height: 30px;'>
                                                <th><? echo $la['GROUP']; ?></th>
                                                <td><input type="checkbox" id="settings_group_add"></div></td>
                                                <td><input type="checkbox" id="settings_group_edit"></div></td>
                                                <td><input type="checkbox" id="settings_group_delete"></div></td>
                                            </tr>
                                            <tr style='text-align: center;height: 30px;'>
                                                <th><? echo $la['EVENTS']; ?></th>
                                                <td><input type="checkbox" id="settings_event_add"></div></td>
                                                <td><input type="checkbox" id="settings_event_edit"></div></td>
                                                <td><input type="checkbox" id="settings_event_delete"></div></td>
                                            </tr>
                                            <tr style='text-align: center;height: 30px;'>
                                                <th><? echo $la['ZONES']; ?></th>
                                                <td><input type="checkbox" id="settings_zone_add"></div></td>
                                                <td><input type="checkbox" id="settings_zone_edit"></div></td>
                                                <td><input type="checkbox" id="settings_zone_delete"></div></td>
                                            </tr>
                                            <tr style='text-align: center;height: 30px;'>
                                                <th><? echo $la['ROUTES']; ?></th>
                                                <td><input type="checkbox" id="settings_route_add"></div></td>
                                                <td><input type="checkbox" id="settings_route_edit"></div></td>
                                                <td><input type="checkbox" id="settings_route_delete"></div></td>
                                            </tr>
                                            <tr style='text-align: center;height: 30px;'>
                                                <th><? echo $la['MARKERS']; ?></th>
                                                <td><input type="checkbox" id="settings_marker_add"></div></td>
                                                <td><input type="checkbox" id="settings_marker_edit"></div></td>
                                                <td><input type="checkbox" id="settings_marker_delete"></div></td>
                                            </tr>
                                            <tr style='text-align: center;height: 30px;'>
                                                <th><? echo $la['DUPLICATE']; ?></th>
                                                <td><input type="checkbox" id="settings_duplicate_add" disabled=""></div></td>
                                                <td><input type="checkbox" id="settings_duplicate_edit"></div></td>
                                                <td><input type="checkbox" id="settings_duplicate_delete" disabled=""></div></td>
                                            </tr>
                                            <tr style='text-align: center;height: 30px;'>
                                                <th><? echo $la['CLEAR_HISTORY']; ?></th>
                                                <td><input type="checkbox" id="settings_clr_history_add" disabled=""></div></td>
                                                <td><input type="checkbox" id="settings_clr_history_edit" disabled=""></div></td>
                                                <td><input type="checkbox" id="settings_clr_history_delete"></div></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <!-- <div class="title-block"><? echo $la['USER']; ?></div>
                                    <div class="row2">
                                        <div class="width10"><? echo $la['ADD']; ?></div>
                                        <div class="width10"><input  class="" type="checkbox" id=""></div>
                                        <div class="width10"><? echo $la['EDIT']; ?></div>
                                        <div class="width10"><input  class="" type="checkbox" id=""></div>
                                        <div class="width10"><? echo $la['DELETE']; ?></div>
                                        <div class="width10"><input  class="" type="checkbox" id=""></div>
                                        <div class="width15"><? echo $la['SUB_ACCOUNT']; ?></div>
                                        <div class="width5"><input  class="" type="checkbox" id=""></div>
                                    </div>
                                    <div class="title-block"><? echo $la['OBJECTS']; ?></div>
                                    <div class="row2">
                                        <div class="width10"><? echo $la['ADD']; ?></div>
                                        <div class="width10"><input  class="" type="checkbox" id=""></div>
                                        <div class="width10"><? echo $la['EDIT']; ?></div>
                                        <div class="width10"><input  class="" type="checkbox" id=""></div>
                                        <div class="width10"><? echo $la['DELETE']; ?></div>
                                        <div class="width10"><input  class="" type="checkbox" id=""></div>
                                        <div class="width15"><? echo $la['CLEAR_HISTORY']; ?></div>
                                        <div class="width5"><input  class="" type="checkbox" id=""></div>
                                        <div class="width15"><? echo $la['DUPLICATE']; ?></div>
                                        <div class="width5"><input  class="" type="checkbox" id=""></div>
                                    </div>
                                </div> -->
                                <!-- <div class="block width45">
                                    <div class="title-block"><? echo $la['GROUP']; ?></div>
                                    <div class="row2">
                                        <div class="width10"><? echo $la['ADD']; ?></div>
                                        <div class="width20"><input  class="" type="checkbox" id=""></div>
                                        <div class="width10"><? echo $la['EDIT']; ?></div>
                                        <div class="width20"><input  class="" type="checkbox" id=""></div>
                                        <div class="width15"><? echo $la['DELETE']; ?></div>
                                        <div class="width15"><input  class="" type="checkbox" id=""></div>
                                    </div>
                                    <div class="title-block"><? echo $la['EVENTS']; ?></div>
                                    <div class="row2">
                                        <div class="width10"><? echo $la['ADD']; ?></div>
                                        <div class="width20"><input  class="" type="checkbox" id=""></div>
                                        <div class="width10"><? echo $la['EDIT']; ?></div>
                                        <div class="width20"><input  class="" type="checkbox" id=""></div>
                                        <div class="width15"><? echo $la['DELETE']; ?></div>
                                        <div class="width15"><input  class="" type="checkbox" id=""></div>
                                    </div>                                  
                                    <div class="title-block"><? echo $la['ZONES']; ?></div>
                                    <div class="row2">
                                        <div class="width10"><? echo $la['ADD']; ?></div>
                                        <div class="width20"><input  class="" type="checkbox" id=""></div>
                                        <div class="width10"><? echo $la['EDIT']; ?></div>
                                        <div class="width20"><input  class="" type="checkbox" id=""></div>
                                        <div class="width15"><? echo $la['DELETE']; ?></div>
                                        <div class="width15"><input  class="" type="checkbox" id=""></div>
                                    </div>
                                </div>
                                <div class="block width45">
                                    <div class="title-block"><? echo $la['MARKERS']; ?></div>
                                    <div class="row2">
                                        <div class="width10"><? echo $la['ADD']; ?></div>
                                        <div class="width20"><input  class="" type="checkbox" id=""></div>
                                        <div class="width10"><? echo $la['EDIT']; ?></div>
                                        <div class="width20"><input  class="" type="checkbox" id=""></div>
                                        <div class="width15"><? echo $la['DELETE']; ?></div>
                                        <div class="width15"><input  class="" type="checkbox" id=""></div>
                                    </div>
                                    <div class="title-block"><? echo $la['PLACES']; ?></div>
                                    <div class="row2">
                                        <div class="width10"><? echo $la['ADD']; ?></div>
                                        <div class="width20"><input  class="" type="checkbox" id=""></div>
                                        <div class="width10"><? echo $la['EDIT']; ?></div>
                                        <div class="width20"><input  class="" type="checkbox" id=""></div>
                                        <div class="width15"><? echo $la['DELETE']; ?></div>
                                        <div class="width15"><input  class="" type="checkbox" id=""></div>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
                
                <div id="dialog_user_edit_subaccounts">
                        <div id="dialog_user_edit_subaccount_list">
                                <table id="dialog_user_edit_subaccount_list_grid"></table>
                                <div id="dialog_user_edit_subaccount_list_grid_pager"></div>
                        </div>
                </div>
                
                <div id="dialog_user_edit_objects">
                        <div id="dialog_user_edit_object_list">
                                <table id="dialog_user_edit_object_list_grid"></table>
                                <div id="dialog_user_edit_object_list_grid_pager"></div>
                        </div>
                </div>
                <? if ($_SESSION["billing"] == true) { ?>
                <div id="dialog_user_edit_billing_plans">
                        <div id="dialog_user_edit_billing_plan_list">
                                <table id="dialog_user_edit_billing_plan_list_grid"></table>
                                <div id="dialog_user_edit_billing_plan_list_grid_pager"></div>
                        </div>
                </div>
                <? } ?>
                <div id="dialog_user_edit_usage">
                        <div id="dialog_user_edit_usage_list">
                                <table id="dialog_user_edit_usage_list_grid"></table>
                                <div id="dialog_user_edit_usage_list_grid_pager"></div>
                        </div>
                </div>
        </div>
</div>

<div id="dialog_user_object_add" title="<? echo $la['ADD_OBJECT'] ?>">
        <div class="row">
                <div class="row2">
                        <div class="width100">
                                <select id="dialog_user_object_add_objects" multiple="multiple" class="width100"></select>
                        </div>
                </div>
        </div>
        <center>
                <input class="button icon-new icon" type="button" onclick="userObjectAdd('add');" value="<? echo $la['ADD']; ?>" />&nbsp;
                <input class="button icon-close icon" type="button" onclick="userObjectAdd('cancel');" value="<? echo $la['CANCEL']; ?>" />
        </center>
</div>

<div id="dialog_user_billing_plan_add" title="<? echo $la['ADD_PLAN'] ?>">
        <div class="row">
                <div class="row2">
                        <div class="width35"><? echo $la['PLAN']; ?></div>
                        <div class="width65">
                                <select id="dialog_user_billing_plan_add_plan" onchange="userBillingPlanAdd('load');" class="width100"></select>
                        </div>
                </div>
                <div class="row2">
                        <div class="width35"><? echo $la['NAME']; ?></div>
                        <div class="width65"><input id="dialog_user_billing_plan_add_name" class="inputbox" type="text" value="" maxlength="50"></div>
                </div>
                <div class="row2">
                        <div class="width35"><? echo $la['NUMBER_OF_OBJECTS']; ?></div>
                        <div class="width30"><input id="dialog_user_billing_plan_add_objects" onkeypress="return isNumberKey(event);" class="inputbox" type="text" value="" maxlength="10"></div>
                </div>
                <div class="row2">
                        <div class="width35"><? echo $la['PERIOD']; ?></div>
                        <div class="width30"><input id="dialog_user_billing_plan_add_period" onkeypress="return isNumberKey(event);" class="inputbox" type="text" value="" maxlength="10"></div>
                        <div class="width5"></div>
                        <div class="width30">
                                <select class="width100" id="dialog_user_billing_plan_add_period_type">
                                        <option value="days"><? echo $la['DAYS']; ?></option>
                                        <option value="months"><? echo $la['MONTHS']; ?></option>
                                        <option value="years"><? echo $la['YEARS']; ?></option>
                                </select>
                        </div>
                </div>
                <div class="row2">
                        <div class="width35"><? echo $la['PRICE']; ?></div>
                        <div class="width30"><input id="dialog_user_billing_plan_add_price" onkeypress="return isNumberKey(event);" class="inputbox" type="text" value="" maxlength="10"></div>
                </div>
        </div>
        <center>
                <input class="button icon-new icon" type="button" onclick="userBillingPlanAdd('add');" value="<? echo $la['ADD']; ?>" />&nbsp;
                <input class="button icon-close icon" type="button" onclick="userBillingPlanAdd('cancel');" value="<? echo $la['CANCEL']; ?>" />
        </center>
</div>

<div id="dialog_user_billing_plan_edit" title="<? echo $la['EDIT_PLAN'] ?>">
        <div class="row">
                <div class="row2">
                        <div class="width35"><? echo $la['NAME']; ?></div>
                        <div class="width65"><input id="dialog_user_billing_plan_edit_name" class="inputbox" type="text" value="" maxlength="50"></div>
                </div>
                <div class="row2">
                        <div class="width35"><? echo $la['NUMBER_OF_OBJECTS']; ?></div>
                        <div class="width30"><input id="dialog_user_billing_plan_edit_objects" onkeypress="return isNumberKey(event);" class="inputbox" type="text" value="" maxlength="10"></div>
                </div>
                <div class="row2">
                        <div class="width35"><? echo $la['PERIOD']; ?></div>
                        <div class="width30"><input id="dialog_user_billing_plan_edit_period" onkeypress="return isNumberKey(event);" class="inputbox" type="text" value="" maxlength="10"></div>
                        <div class="width5"></div>
                        <div class="width30">
                                <select class="width100" id="dialog_user_billing_plan_edit_period_type">
                                        <option value="days"><? echo $la['DAYS']; ?></option>
                                        <option value="months"><? echo $la['MONTHS']; ?></option>
                                        <option value="years"><? echo $la['YEARS']; ?></option>
                                </select>
                        </div>
                </div>
                <div class="row2">
                        <div class="width35"><? echo $la['PRICE']; ?></div>
                        <div class="width30"><input id="dialog_user_billing_plan_edit_price" onkeypress="return isNumberKey(event);" class="inputbox" type="text" value="" maxlength="10"></div>
                </div>
        </div>
        <center>
                <input class="button icon-new icon" type="button" onclick="userBillingPlanEdit('save');" value="<? echo $la['SAVE']; ?>" />&nbsp;
                <input class="button icon-close icon" type="button" onclick="userBillingPlanEdit('cancel');" value="<? echo $la['CANCEL']; ?>" />
        </center>
</div>