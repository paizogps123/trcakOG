<div id="dialog_custom_map_properties" title="<? echo $la['CUSTOM_MAP_PROPERTIES'];?>">
    <div class="row">
        <div class="title-block">
            <? echo $la['CUSTOM_MAP']; ?>
        </div>
        <div class="row2">
            <div class="width30">
                <? echo $la['NAME']; ?>
            </div>
            <div class="width30"><input id="dialog_custom_map_name" class="inputbox" type="text" value="" maxlength="50"></div>
        </div>
        <div class="row2">
            <div class="width30">
                <? echo $la['ACTIVE']; ?>
            </div>
            <div class="width70"><input id="dialog_custom_map_active" type="checkbox" checked="checked" /></div>
        </div>
        <div class="row2">
            <div class="width30">
                <? echo $la['TYPE']; ?>
            </div>
            <div class="width70">
                <select style="width: 100px;" id="dialog_custom_map_type">
                    <option value="tms">TMS</option>
                    <option value="wms">WMS</option>
                </select>
            </div>
        </div>
        <div class="row2">
            <div class="width30">
                <? echo $la['URL']; ?>
            </div>
            <div class="width70"><input id="dialog_custom_map_url" class="inputbox" type="text" value=""></div>
        </div>
        <div class="row2">
            <div class="width30">
                <? echo $la['LAYERS']; ?>
            </div>
            <div class="width70"><input id="dialog_custom_map_layers" class="inputbox" type="text" value=""></div>
        </div>
    </div>

    <center>
        <input class="button icon-save icon" type="button" onclick="customMapProperties('save');" value="<? echo $la['SAVE']; ?>" />&nbsp;
        <input class="button icon-close icon" type="button" onclick="customMapProperties('cancel');" value="<? echo $la['CANCEL']; ?>" />
    </center>
</div>

<div id="dialog_billing_properties" title="<? echo $la['BILLING_PROPERTIES'];?>">
    <div class="row">
        <div class="title-block">
            <? echo $la['BILLING_PLAN']; ?>
        </div>
        <div class="row2">
            <div class="width35">
                <? echo $la['NAME']; ?>
            </div>
            <div class="width65"><input id="dialog_billing_name" class="inputbox" type="text" value="" maxlength="50"></div>
        </div>
        <div class="row2">
            <div class="width35">
                <? echo $la['ACTIVE']; ?>
            </div>
            <div class="width65"><input id="dialog_billing_active" type="checkbox" checked="checked" /></div>
        </div>
        <div class="row2">
            <div class="width35">
                <? echo $la['NUMBER_OF_OBJECTS']; ?>
            </div>
            <div class="width30"><input id="dialog_billing_objects" onkeypress="return isNumberKey(event);" class="inputbox" type="text" value="" maxlength="10"></div>
        </div>
        <div class="row2">
            <div class="width35">
                <? echo $la['PERIOD']; ?>
            </div>
            <div class="width30"><input id="dialog_billing_period" onkeypress="return isNumberKey(event);" class="inputbox" type="text" value="" maxlength="10"></div>
            <div class="width5"></div>
            <div class="width30">
                <select class="width100" id="dialog_billing_period_type">
                    <option value="days">
                        <? echo $la['DAYS']; ?>
                    </option>
                    <option value="months">
                        <? echo $la['MONTHS']; ?>
                    </option>
                    <option value="years">
                        <? echo $la['YEARS']; ?>
                    </option>
                </select>
            </div>
        </div>
        <div class="row2">
            <div class="width35">
                <? echo $la['PRICE']; ?>
            </div>
            <div class="width30"><input id="dialog_billing_price" onkeypress="return isNumberKey(event);" class="inputbox" type="text" value="" maxlength="10"></div>
        </div>
    </div>

    <center>
        <input class="button icon-save icon" type="button" onclick="billingPlanProperties('save');" value="<? echo $la['SAVE']; ?>" />&nbsp;
        <input class="button icon-close icon" type="button" onclick="billingPlanProperties('cancel');" value="<? echo $la['CANCEL']; ?>" />
    </center>
</div>

<div id="dialog_template_properties" title="<? echo $la['TEMPLATE_PROPERTIES'];?>">
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
                    <div class="width70"><input id="dialog_template_name" class="inputbox" type="text" value="" maxlength="50" readonly></div>
                </div>
                <div class="row2">
                    <div class="width30">
                        <? echo $la['LANGUAGE']; ?>
                    </div>
                    <div class="width70">
                        <select id="dialog_template_language" onChange="templateProperties('load');">
                            <? echo getLanguageList(); ?>
                        </select>
                    </div>
                </div>
                <div class="row2">
                    <div class="width30">
                        <? echo $la['SUBJECT']; ?>
                    </div>
                    <div class="width70"><input id="dialog_template_subject" class="inputbox" maxlength="100"></div>
                </div>
                <div class="row2">
                    <div class="width30">
                        <? echo $la['MESSAGE']; ?>
                    </div>
                    <div class="width70"><textarea id="dialog_template_message" class="inputbox" style="height:255px;" maxlength="2000"></textarea></div>
                </div>
            </div>
        </div>
        <div class="block width40">
            <div class="container last">
                <div class="title-block">
                    <? echo $la['VARIABLES']; ?>
                </div>
                <div class="row2">
                    <div id="dialog_template_variables" style="height: 334px; overflow-y: scroll;"></div>
                </div>
            </div>
        </div>
    </div>

    <center>
        <input class="button icon-save icon" type="button" onclick="templateProperties('save');" value="<? echo $la['SAVE']; ?>" />&nbsp;
        <input class="button icon-close icon" type="button" onclick="templateProperties('cancel');" value="<? echo $la['CANCEL']; ?>" />
    </center>
</div>

<div id="cpanel_manage_server" style="display:none;">
    <div class="float-left cpanel-title">
        <div class="version">v
            <? echo $gsValues['VERSION']; ?>
        </div>
        <h1 class="title">
            <? echo $la['CONTROL_PANEL']; ?> <span> -
                <? echo $la['MANAGE_SERVER']; ?></span></h1>
    </div>
    <div id="manage_server_tabs" class="clearfix">
        <ul>
            <li class="cp-server"><a href="#manage_server_server">
                    <? echo $la['SERVER']; ?></a></li>
            <li class="cp-maps"><a href="#manage_server_maps">
                    <? echo $la['MAPS']; ?></a></li>
            <li class="cp-user"><a href="#manage_server_user">
                    <? echo $la['USER']; ?></a></li>
            <li class="cp-billing"><a href="#manage_server_billing">
                    <? echo $la['BILLING']; ?></a></li>
            <li class="cp-templates"><a href="#manage_server_templates">
                    <? echo $la['TEMPLATES']; ?></a></li>
            <li class="cp-email"><a href="#manage_server_email">
                    <? echo $la['EMAIL']; ?></a></li>
            <li class="cp-sms"><a href="#manage_server_sms">
                    <? echo $la['SMS']; ?></a></li>
            <li class="cp-tools"><a href="#manage_server_tools">
                    <? echo $la['TOOLS']; ?></a></li>
            <li class="cp-logs"><a href="#manage_server_logs">
                    <? echo $la['LOGS']; ?></a></li>
            <!-- Code update by Vetrivel.N -->
            <li class="cp-logs"><a href="#manage_server_notification">
                    <? echo $la['NOTIFICATIONS']; ?></a></li>
            <li class="save-btn"><input class="button icon-save icon ms-save" type="button" onclick="serverSave();" value="<? echo $la['SAVE']; ?>"></li>
        </ul>
        <div class="cpanel-tabs-content">
            <div id="manage_server_server">
                <div class="width-100">
                	<div class="block width100">
                		<div class="block width45">
		                    <div class="row">
		                        <div class="title-block">
                            		<? echo $la['INFORMATION']; ?>
		                        </div>
		                        <div class="row3">
		                            <div class="width50">
		                                <? echo $la['SERVER_IP']; ?>
		                            </div>
		                            <div class="width50">
		                                <? echo $gsValues['SERVER_IP']; ?>
		                            </div>
		                        </div>
		                        <div class="row3">
		                            <div class="width50">
		                                <? echo $la['SERVER_PORTS']; ?>
		                            </div>
		                            <div class="width50">
		                                <a href="<? echo $gsValues['URL_SERVER_PORTS']; ?>" target="_blank">
		                                    <? echo $gsValues['URL_SERVER_PORTS']; ?></a>
		                            </div>
		                        </div>
		                        <div class="row3">
		                            <div class="width50">
		                                <? echo $la['SERVER_API_KEY']; ?>
		                            </div>
		                            <div class="width50">
		                                <input id="cpanel_manage_server_api_key" class="inputbox width100" readOnly="true" />
		                            </div>
		                        </div>
		                    </div>
		                </div>
                		<div class="block width10">&nbsp;</div>
                		<div class="block width45">
                			 <div class="row">
		                        <div class="title-block">
		                            <? echo $la['GENERAL']; ?>
		                        </div>
		                        <div class="row2">
		                            <div class="width50">
		                                <? echo $la['GPS_SERVER_NAME']; ?>
		                            </div>
		                            <div class="width50">
		                                <input id="cpanel_manage_server_name" class="inputbox width100" maxlength="50" placeholder="<? echo $la['EX_MY_GPS_SERVER']; ?>" />
		                            </div>
		                        </div>
		                        <div class="row2">
		                            <div class="width50">
		                                <? echo $la['PAGE_GENERATOR_TAG']; ?>
		                            </div>
		                            <div class="width50">
		                                <input id="cpanel_manage_server_generator" class="inputbox width100" maxlength="50" placeholder="<? echo $la['EX_MY_GPS_SERVER']; ?>" />
		                            </div>
		                        </div>
		                        <div class="row2">
		                            <div class="width50">
		                                <? echo $la['SHOW_ABOUT_BUTTON']; ?>
		                            </div>
		                            <div class="width50">
		                                <select style="width: 100%;" id="cpanel_manage_server_show_about">
		                                    <option value="true">
		                                        <? echo $la['YES']; ?>
		                                    </option>
		                                    <option value="false">
		                                        <? echo $la['NO']; ?>
		                                    </option>
		                                </select>
		                            </div>
		                        </div>
		                        <div class="row2">
		                            <div class="width50">
		                                <? echo $la['LANGUAGES']; ?></br></br>
		                                <span class="subinfo">
		                                    <? echo $la['HOLD_CTRL_TO_SELECT_MULTIPLE_ITEMS']; ?></span>
		                            </div>
		                            <div class="width50">
		                                <select id="cpanel_manage_server_languages" style="width: 100%; height:150px;" multiple="multiple">
		                                    <? echo getLanguageListFiles(); ?>
		                                </select>
		                            </div>
		                        </div>
		                    </div>
                		</div>
		            </div>
                   <div class="block width100">
                		<div class="block width45">
                			<div class="row">
		                        <div class="title-block">
		                            <? echo $la['URL_ADDRESSES']; ?>
		                        </div>
		                        <div class="row2">
		                            <div class="width50">
		                                <? echo $la['LOGIN_DIALOG_URL']; ?>
		                            </div>
		                            <div class="width50">
		                                <input id="cpanel_manage_server_url_login" class="inputbox width100" placeholder="<? echo $la['HTTP_FULL_ADDRESS_HERE']; ?>" />
		                            </div>
		                        </div>
		                        <div class="row2">
		                            <div class="width50">
		                                <? echo $la['HELP_PAGE_URL']; ?>
		                            </div>
		                            <div class="width50">
		                                <input id="cpanel_manage_server_url_help" class="inputbox width100" placeholder="<? echo $la['HTTP_FULL_ADDRESS_HERE']; ?>" />
		                            </div>
		                        </div>
		                        <div class="row2">
		                            <div class="width50">
		                                <? echo $la['CONTACT_PAGE_URL']; ?>
		                            </div>
		                            <div class="width50">
		                                <input id="cpanel_manage_server_url_contact" class="inputbox width100" placeholder="<? echo $la['HTTP_FULL_ADDRESS_HERE']; ?>" />
		                            </div>
		                        </div>
		                        <div class="row2">
		                            <div class="width50">
		                                <? echo $la['SHOP_PAGE_URL']; ?>
		                            </div>
		                            <div class="width50">
		                                <input id="cpanel_manage_server_url_shop" class="inputbox width100" placeholder="<? echo $la['HTTP_FULL_ADDRESS_HERE']; ?>" />
		                            </div>
		                        </div>
		                        <div class="row2">
		                            <div class="width50">
		                                <? echo $la['SMS_GATEWAY_APP_URL']; ?>
		                            </div>
		                            <div class="width50">
		                                <input id="cpanel_manage_server_url_sms_gateway_app" class="inputbox width100" placeholder="<? echo $la['HTTP_FULL_ADDRESS_HERE']; ?>" />
		                            </div>
		                        </div>
		                    </div>
                		</div>
                		<div class="block width10">&nbsp;</div>
                		<div class="block width45">
		                    <div class="row">
		                        <div class="title-block">
		                            <? echo $la['LOGO']; ?>
		                        </div>
		                        <div class="row2">
		                            <div class="width50">
		                                <div class="row">
		                                    <center><img class="logo" id="cpanel_manage_server_logo" src="<? echo $gsValues['URL_LOGO']; ?>" /></center>
		                                </div>

		                                <div class="row">
		                                    <center>
		                                        <? echo $la['LOGO_SIZE_FORMAT']; ?>
		                                    </center>
		                                </div>
		                            </div>
		                            <div class="width50">
		                                <input style="width: 100px;" class="button" type="button" value="<? echo $la['UPLOAD']; ?>" onclick="uploadLogo();" />
		                                <input id="cpanel_manage_server_logo_filename" class="inputbox" style="display: none;" />
		                            </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		            <div class="block width100">
                		<div class="block width45">
                			<div class="row">
		                        <div class="title-block">
		                            <? echo $la['GEOCODER']; ?>
		                        </div>
		                        <div class="row2">
		                            <div class="width50">
		                                <? echo $la['USE_GEOCODER_CACHE']; ?>
		                            </div>
		                            <div class="width50">
		                                <select style="width: 100px;" id="cpanel_manage_server_geocoder_cache" />
		                                <option value="true">
		                                    <? echo $la['YES']; ?>
		                                </option>
		                                <option value="false">
		                                    <? echo $la['NO']; ?>
		                                </option>
		                                </select>
		                            </div>
		                        </div>
		                        <div class="row2">
		                            <div class="width50">
		                                <? echo $la['CLEAR_GEOCODER_CACHE']; ?>
		                            </div>
		                            <div class="width50">
		                                <input style="width: 100px;" class="button" type="button" onclick="geocoderClearCache();" value="<? echo $la['CLEAR']; ?>" />
		                            </div>
		                        </div>
		                    </div>
                		</div>
                		<div class="block width10">&nbsp;</div>
                		<div class="block width45">
                			<div class="row">
		                        <div class="title-block">
		                            <? echo $la['OBJECTS']; ?>
		                        </div>
		                        <div class="row2">
		                            <div class="width50">
		                                <? echo $la['OBJECT_CONNECTION_TIMEOUT_RESETS_CONNECTION_AND_GPS_STATUS']; ?>
		                            </div>
		                            <div class="width50">
		                                <select style="width: 100px;" id="cpanel_manage_server_connection_timeout">
		                                    <option value="1">1
		                                        <? echo $la['UNIT_MIN']; ?>
		                                    </option>
		                                    <option value="2">2
		                                        <? echo $la['UNIT_MIN']; ?>
		                                    </option>
		                                    <option value="3">3
		                                        <? echo $la['UNIT_MIN']; ?>
		                                    </option>
		                                    <option value="4">4
		                                        <? echo $la['UNIT_MIN']; ?>
		                                    </option>
		                                    <option value="5">5
		                                        <? echo $la['UNIT_MIN']; ?>
		                                    </option>
		                                    <option value="10">10
		                                        <? echo $la['UNIT_MIN']; ?>
		                                    </option>
		                                    <option value="20">20
		                                        <? echo $la['UNIT_MIN']; ?>
		                                    </option>
		                                    <option value="30">30
		                                        <? echo $la['UNIT_MIN']; ?>
		                                    </option>
		                                    <option value="40">40
		                                        <? echo $la['UNIT_MIN']; ?>
		                                    </option>
		                                    <option value="50">50
		                                        <? echo $la['UNIT_MIN']; ?>
		                                    </option>
		                                    <option value="60">60
		                                        <? echo $la['UNIT_MIN']; ?>
		                                    </option>
		                                </select>
		                            </div>
		                        </div>
		                        <div class="row2">
		                            <div class="width50">
		                                <? echo $la['KEEP_HISTORY_PERIOD']; ?><br />
		                                <? echo $la['WARNING_CHANGING_THIS_VALUE_WILL_AFFECT_EXISTING_DATA']; ?>
		                            </div>
		                            <div class="width50">
		                                <input id="cpanel_manage_server_history_period" onkeypress="return isNumberKey(event);" class="inputbox" style="width:100px;" maxlength="4" placeholder="<? echo $la['EXAMPLE_SHORT']; ?> 30" />
		                            </div>
		                        </div>
		                    </div>
                		</div>
                	</div>
                    <div class="row">
                        <div class="title-block">
                            <? echo $la['BACKUP']; ?>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['SEND_DB_BACKUP_TO_EMAIL_AT_SET_UTC_TIME']; ?>
                            </div>
                            <div class="width10">
                                <select style="width:100px;" id="cpanel_manage_server_backup_time">
                                    <? include ("inc/inc_dt.hours_minutes.php"); ?>
                                </select>
                            </div>
                            <div class="width1"></div>
                            <div class="width39"><input id="cpanel_manage_server_backup_email" class="inputbox width100" maxlength="50" /></div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="manage_server_maps">
                <div class="width-100">
                    <div class="block width45">
                    <div class="row">
                        <div class="title-block">
                            <? echo $la['AVAILABLE_MAPS']; ?>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                OSM Map
                            </div>
                            <div class="width50">
                                <select style="width: 100px;" id="cpanel_manage_server_map_osm" />
                                <option value="true">
                                    <? echo $la['YES']; ?>
                                </option>
                                <option value="false">
                                    <? echo $la['NO']; ?>
                                </option>
                                </select>
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                Bing Maps
                            </div>
                            <div class="width50">
                                <select style="width: 100px;" id="cpanel_manage_server_map_bing" />
                                <option value="true">
                                    <? echo $la['YES']; ?>
                                </option>
                                <option value="false">
                                    <? echo $la['NO']; ?>
                                </option>
                                </select>
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                Google Maps
                            </div>
                            <div class="width50">
                                <select style="width: 100px;" id="cpanel_manage_server_map_google" />
                                <option value="true">
                                    <? echo $la['YES']; ?>
                                </option>
                                <option value="false">
                                    <? echo $la['NO']; ?>
                                </option>
                                </select>
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                Google Maps Traffic
                            </div>
                            <div class="width50">
                                <select style="width: 100px;" id="cpanel_manage_server_map_google_traffic" />
                                <option value="true">
                                    <? echo $la['YES']; ?>
                                </option>
                                <option value="false">
                                    <? echo $la['NO']; ?>
                                </option>
                                </select>
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                Mapbox Maps
                            </div>
                            <div class="width50">
                                <select style="width: 100px;" id="cpanel_manage_server_map_mapbox" />
                                <option value="true">
                                    <? echo $la['YES']; ?>
                                </option>
                                <option value="false">
                                    <? echo $la['NO']; ?>
                                </option>
                                </select>
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                Yandex Map
                            </div>
                            <div class="width50">
                                <select style="width: 100px;" id="cpanel_manage_server_map_yandex" />
                                <option value="true">
                                    <? echo $la['YES']; ?>
                                </option>
                                <option value="false">
                                    <? echo $la['NO']; ?>
                                </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    </div>
                    <div class="block width10">&nbsp;</div>
                    <div class="block width45">
                    <div class="row">
                        <div class="title-block">
                            <? echo $la['LICENSE_KEYS']; ?>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['BING_MAPS_KEY']; ?>
                            </div>
                            <div class="width50">
                                <input id="cpanel_manage_server_map_bing_key" class="inputbox" />
                            </div>
                        </div></br>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['GOOGLE_MAPS_KEY']; ?>
                            </div>
                            <div class="width50">
                                <input id="cpanel_manage_server_map_google_key" class="inputbox" />
                            </div>
                        </div></br>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['MAPBOX_MAPS_KEY']; ?>
                            </div>
                            <div class="width50">
                                <input id="cpanel_manage_server_map_mapbox_key" class="inputbox" />
                            </div>
                        </div></br>
                    </div>
                    </div>
                    <div class="row">
                        <div class="title-block">
                            <? echo $la['MAP_LAYER_ZOOM_POSITION_AFTER_LOGIN']; ?>
                        </div>
                        <div class="row2">
                            <div class="width20">
                                <? echo $la['LAYER']; ?>
                            </div>
                            <div class="width30">
                                <select class="width70" id="cpanel_manage_server_map_layer" />
                                <option value="osm">OSM Map</option>
                                <option value="broad">Bing Road</option>
                                <option value="baer">Bing Aerial</option>
                                <option value="bhyb">Bing Hybrid</option>
                                <option value="gmap">Google Streets</option>
                                <option value="gsat">Google Satellite</option>
                                <option value="ghyb">Google Hybrid</option>
                                <option value="gter">Google Terrain</option>
                                <option value="mbmap">Mapbox Streets</option>
                                <option value="mbsat">Mapbox Satellite</option>
                                <option value="yandex">Yandex</option>
                                </select>
                            </div>
                            <div class="width20">
                                <? echo $la['ZOOM']; ?>
                            </div>
                            <div class="width30">
                                <select class="width70" id="cpanel_manage_server_map_zoom" />
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                </select>
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width20">
                                <? echo $la['LATITUDE']; ?>
                            </div>
                            <div class="width30">
                                <input id="cpanel_manage_server_map_lat" onkeypress="return isNumberKey(event);" class="inputbox width70" maxlength="10" placeholder="<? echo $la['EXAMPLE_SHORT']; ?> 25.000000" />
                            </div>
                            <div class="width20">
                                <? echo $la['LONGITUDE']; ?>
                            </div>
                            <div class="width30">
                                <input id="cpanel_manage_server_map_lng" onkeypress="return isNumberKey(event);" class="inputbox width70"  maxlength="10" placeholder="<? echo $la['EXAMPLE_SHORT']; ?> 0.000000" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="title-block">
                            <? echo $la['CUSTOM_MAPS']; ?>
                        </div>
                        <div class="row2">
                            <div class="width100">
                                <div class="float-right">
                                    <a href="#" onclick="loadGridList('custom_maps');">
                                        <div class="panel-button" title="<? echo $la['RELOAD']; ?>">
                                            <img src="theme/images/refresh-color.svg" width="16px" border="0" />
                                        </div>
                                    </a>
                                    <a href="#" onclick="customMapProperties('add');">
                                        <div class="panel-button" title="<? echo $la['ADD']; ?>">
                                            <img src="theme/images/map.svg" width="16px" border="0" />
                                        </div>
                                    </a>
                                    <a href="#" onclick="customMapDeleteAll();">
                                        <div class="panel-button" title="<? echo $la['DELETE_ALL']; ?>">
                                            <img src="theme/images/remove2.svg" width="16px" border="0" />
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="width100">
                            <table id="cpanel_manage_server_custom_map_list_grid"></table>
                        </div>
                    </div>
                </div>
            </div>
            <div id="manage_server_user">
                <div class="width-100">
                    <div class="block width45">
                    <div class="row">
                        <div class="title-block">
                            <? echo $la['LOGIN']; ?>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['PAGE_AFTER_ADMIN_OR_MANAGER_LOGIN']; ?>
                            </div>
                            <div class="width50">
                                <select style="width: 100px;" id="cpanel_manage_server_page_after_login" />
                                <option value="account">
                                    <? echo $la['ACCOUNT']; ?>
                                </option>
                                <option value="cpanel">
                                    <? echo $la['CPANEL']; ?>
                                </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="title-block">
                            <? echo $la['NOTIFICATIONS']; ?>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['REMIND_USER_ABOUT_EXPIRING_OBJECTS']; ?>
                            </div>
                            <div class="width10">
                                <select id="cpanel_manage_server_notify_obj_expire" onChange="serverCheck();">
                                    <option value="true">
                                        <? echo $la['YES']; ?>
                                    </option>
                                    <option value="false">
                                        <? echo $la['NO']; ?>
                                    </option>
                                </select>
                            </div>
                            <div class="width1"></div>
                            <div class="width8">
                                <input id="cpanel_manage_server_notify_obj_expire_period" onkeypress="return isNumberKey(event);" class="inputbox width100" maxlength="4" placeholder="<? echo $la['EXAMPLE_SHORT']; ?> 7" />
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['REMIND_USER_ABOUT_EXPIRING_ACCOUNT']; ?>
                            </div>
                            <div class="width10">
                                <select id="cpanel_manage_server_notify_account_expire"  onChange="serverCheck();">
                                    <option value="true">
                                        <? echo $la['YES']; ?>
                                    </option>
                                    <option value="false">
                                        <? echo $la['NO']; ?>
                                    </option>
                                </select>
                            </div>
                            <div class="width1"></div>
                            <div class="width8">
                                <input id="cpanel_manage_server_notify_account_expire_period" onkeypress="return isNumberKey(event);" class="inputbox width100" maxlength="4" placeholder="<? echo $la['EXAMPLE_SHORT']; ?> 7" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="title-block">
                            <? echo $la['OTHER']; ?>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['SCHEDULE_REPORTS']; ?>
                            </div>
                            <div class="width50">
                                <select style="width: 100px;" id="cpanel_manage_server_reports_schedule" />
                                <option value="true">
                                    <? echo $la['YES']; ?>
                                </option>
                                <option value="false">
                                    <? echo $la['NO']; ?>
                                </option>
                                </select>
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['MAX_MARKERS']; ?>
                            </div>
                            <div class="width50">
                                <input id="cpanel_manage_server_places_markers" onkeypress="return isNumberKey(event);" class="inputbox" style="width: 100px;" maxlength="4" />
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['MAX_ROUTES']; ?>
                            </div>
                            <div class="width50">
                                <input id="cpanel_manage_server_places_routes" onkeypress="return isNumberKey(event);" class="inputbox" style="width: 100px;" maxlength="4" />
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['MAX_ZONES']; ?>
                            </div>
                            <div class="width50">
                                <input id="cpanel_manage_server_places_zones" onkeypress="return isNumberKey(event);" class="inputbox" style="width: 100px;" maxlength="4" />
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['MAX_EMAILS_DAILY']; ?>
                            </div>
                            <div class="width50">
                                <input id="cpanel_manage_server_usage_email_daily" onkeypress="return isNumberKey(event);" class="inputbox" style="width: 100px;" maxlength="8" />
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['MAX_SMS_DAILY']; ?>
                            </div>
                            <div class="width50">
                                <input id="cpanel_manage_server_usage_sms_daily" onkeypress="return isNumberKey(event);" class="inputbox" style="width: 100px;" maxlength="8" />
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['MAX_API_DAILY']; ?>
                            </div>
                            <div class="width50">
                                <input id="cpanel_manage_server_usage_api_daily" onkeypress="return isNumberKey(event);" class="inputbox" style="width: 100px;" maxlength="8" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="title-block">
                            <? echo $la['CREATE_ACCOUNT']; ?>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['USER_REGISTRATION_VIA_LOGIN_DIALOG']; ?>
                            </div>
                            <div class="width50">
                                <select style="width: 100px;" id="cpanel_manage_server_allow_registration" onChange="serverCheck();" />
                                <option value="true">
                                    <? echo $la['YES']; ?>
                                </option>
                                <option value="false">
                                    <? echo $la['NO']; ?>
                                </option>
                                </select>
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['EXPIRE_ACCOUNT_DAYS_AFTER_REGISTRATION']; ?>
                            </div>
                            <div class="width10">
                                <select id="cpanel_manage_server_account_expire" class="width100" onChange="serverCheck();">
                                    <option value="true">
                                        <? echo $la['YES']; ?>
                                    </option>
                                    <option value="false">
                                        <? echo $la['NO']; ?>
                                    </option>
                                </select>
                            </div>
                            <div class="width1"></div>
                            <div class="width8">
                                <input id="cpanel_manage_server_account_expire_period" onkeypress="return isNumberKey(event);" class="inputbox width100" maxlength="4" placeholder="<? echo $la['EXAMPLE_SHORT']; ?> 7" />
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
                    <div class="block width10">&nbsp;</div>
                    <div class="block width45">
                    <div class="row">
                        <div class="title-block">
                            <? echo $la['DEFAULTS']; ?>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['LANGUAGE']; ?>
                            </div>
                            <div class="width50">
                                <select style="width: 100px;" id="cpanel_manage_server_language">
                                    <? echo getLanguageList(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['UNIT_OF_DISTANCE']; ?>
                            </div>
                            <div class="width50">
                                <select style="width: 100px;" id="cpanel_manage_server_distance_unit">
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
                            <div class="width50">
                                <? echo $la['UNIT_OF_CAPACITY']; ?>
                            </div>
                            <div class="width50">
                                <select style="width: 100px;" id="cpanel_manage_server_capacity_unit">
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
                            <div class="width50">
                                <? echo $la['UNIT_OF_TEMPERATURE']; ?>
                            </div>
                            <div class="width50">
                                <select style="width: 100px;" id="cpanel_manage_server_temperature_unit">
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
                            <div class="width50">
                                <? echo $la['CURRENCY']; ?>
                            </div>
                            <div class="width50">
                                <input style="width: 100px;" id="cpanel_manage_server_currency" class="inputbox" type="text" value="" maxlength="3">
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['TIMEZONE']; ?>
                            </div>
                            <div class="width50">
                                <select style="width: 100px;" id="cpanel_manage_server_timezone">
                                    <? include ("inc/inc_timezones.php"); ?>
                                </select>
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['DAYLIGHT_SAVING_TIME']; ?>
                            </div>
                            <div class="width3">
                                <input id="cpanel_manage_server_dst" type="checkbox" class="checkbox" onchange="serverCheck();" />
                            </div>
                            <div class="width15">
                                <input class="inputbox-calendar-mmdd inputbox width100" id="cpanel_manage_server_dst_start_mmdd" type="text" value="" />
                            </div>
                            <div class="width1"></div>
                            <div class="width12">
                                <select class="width100" id="cpanel_manage_server_dst_start_hhmm">
                                    <? include ("inc/inc_dt.hours_minutes.php"); ?>
                                </select>
                            </div>                            
                        </div>
                        <div class="row2">
                            <div class="width50">
                                &nbsp;
                            </div>
                            <div class="width3">
                                  &nbsp; &nbsp;
                            </div>
                            <div class="width15">
                                <input class="inputbox-calendar-mmdd inputbox width100" id="cpanel_manage_server_dst_end_mmdd" type="text" value="" />
                            </div>
                            <div class="width1"></div>
                            <div class="width12">
                                <select class="width100" id="cpanel_manage_server_dst_end_hhmm">
                                    <? include ("inc/inc_dt.hours_minutes.php"); ?>
                                </select>
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['ADD_OBJECTS']; ?>
                            </div>
                            <div class="width50">
                                <select id="cpanel_manage_server_obj_add" style="width:100px;" onChange="serverCheck();">
                                    <option value="true">
                                        <? echo $la['YES']; ?>
                                    </option>
                                    <option value="false">
                                        <? echo $la['NO']; ?>
                                    </option>
                                    <option value="trial">
                                        <? echo $la['TRIAL']; ?>
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['OBJECT_LIMIT']; ?>
                            </div>
                            <div class="width10">
                                <select id="cpanel_manage_server_obj_limit" class="width100" onChange="serverCheck();">
                                    <option value="true">
                                        <? echo $la['YES']; ?>
                                    </option>
                                    <option value="false">
                                        <? echo $la['NO']; ?>
                                    </option>
                                </select>
                            </div>
                            <div class="width1"></div>
                            <div class="width8">
                                <input id="cpanel_manage_server_obj_limit_num" onkeypress="return isNumberKey(event);" class="inputbox width100" maxlength="4" placeholder="<? echo $la['EXAMPLE_SHORT']; ?> 10" />
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['OBJECT_DATE_LIMIT_DAYS_AFTER_REGISTRATION']; ?>
                            </div>
                            <div class="width10">
                                <select id="cpanel_manage_server_obj_days" class="width100" onChange="serverCheck();">
                                    <option value="true">
                                        <? echo $la['YES']; ?>
                                    </option>
                                    <option value="false">
                                        <? echo $la['NO']; ?>
                                    </option>
                                </select>
                            </div>
                            <div class="width1"></div>
                            <div class="width8">
                                <input id="cpanel_manage_server_obj_days_num" onkeypress="return isNumberKey(event);" class="inputbox width100" maxlength="4" placeholder="<? echo $la['EXAMPLE_SHORT']; ?> 30" />
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['OBJECT_TRIAL_LIMIT_DAYS']; ?>
                            </div>
                            <div class="width50">
                                <input id="cpanel_manage_server_obj_days_trial" onkeypress="return isNumberKey(event);" class="inputbox" style="width:100px;" maxlength="4" placeholder="<? echo $la['EXAMPLE_SHORT']; ?> 7" />
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['EDIT_OBJECTS']; ?>
                            </div>
                            <div class="width50">
                                <select id="cpanel_manage_server_obj_edit" style="width:100px;">
                                    <option value="true">
                                        <? echo $la['YES']; ?>
                                    </option>
                                    <option value="false">
                                        <? echo $la['NO']; ?>
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['CLEAR_OBJECTS_HISTORY']; ?>
                            </div>
                            <div class="width50">
                                <select id="cpanel_manage_server_obj_history_clear" style="width:100px;">
                                    <option value="true">
                                        <? echo $la['YES']; ?>
                                    </option>
                                    <option value="false">
                                        <? echo $la['NO']; ?>
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['HISTORY']; ?>
                            </div>
                            <div class="width50">
                                <select id="cpanel_manage_server_history" style="width:100px;">
                                    <option value="true">
                                        <? echo $la['YES']; ?>
                                    </option>
                                    <option value="false">
                                        <? echo $la['NO']; ?>
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['REPORTS']; ?>
                            </div>
                            <div class="width50">
                                <select id="cpanel_manage_server_reports" style="width:100px;">
                                    <option value="true">
                                        <? echo $la['YES']; ?>
                                    </option>
                                    <option value="false">
                                        <? echo $la['NO']; ?>
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['RFID_AND_IBUTTON_LOGBOOK']; ?>
                            </div>
                            <div class="width50">
                                <select id="cpanel_manage_server_rilogbook" style="width:100px;">
                                    <option value="true">
                                        <? echo $la['YES']; ?>
                                    </option>
                                    <option value="false">
                                        <? echo $la['NO']; ?>
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['DIAGNOSTIC_TROUBLE_CODES']; ?>
                            </div>
                            <div class="width50">
                                <select id="cpanel_manage_server_dtc" style="width:100px;">
                                    <option value="true">
                                        <? echo $la['YES']; ?>
                                    </option>
                                    <option value="false">
                                        <? echo $la['NO']; ?>
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['OBJECT_CONTROL']; ?>
                            </div>
                            <div class="width50">
                                <select id="cpanel_manage_server_object_control" style="width:100px;">
                                    <option value="true">
                                        <? echo $la['YES']; ?>
                                    </option>
                                    <option value="false">
                                        <? echo $la['NO']; ?>
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['IMAGE_GALLERY']; ?>
                            </div>
                            <div class="width50">
                                <select id="cpanel_manage_server_image_gallery" style="width:100px;">
                                    <option value="true">
                                        <? echo $la['YES']; ?>
                                    </option>
                                    <option value="false">
                                        <? echo $la['NO']; ?>
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['CHAT']; ?>
                            </div>
                            <div class="width50">
                                <select id="cpanel_manage_server_chat" style="width:100px;">
                                    <option value="true">
                                        <? echo $la['YES']; ?>
                                    </option>
                                    <option value="false">
                                        <? echo $la['NO']; ?>
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['SUB_ACCOUNTS']; ?>
                            </div>
                            <div class="width50">
                                <select id="cpanel_manage_server_subaccounts" style="width:100px;">
                                    <option value="true">
                                        <? echo $la['YES']; ?>
                                    </option>
                                    <option value="false">
                                        <? echo $la['NO']; ?>
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['SERVER_SMS_GATEWAY']; ?>
                            </div>
                            <div class="width50">
                                <select id="cpanel_manage_server_sms_gateway_server" style="width:100px;">
                                    <option value="true">
                                        <? echo $la['YES']; ?>
                                    </option>
                                    <option value="false">
                                        <? echo $la['NO']; ?>
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['API']; ?>
                            </div>
                            <div class="width50">
                                <select id="cpanel_manage_server_api" style="width:100px;">
                                    <option value="true">
                                        <? echo $la['YES']; ?>
                                    </option>
                                    <option value="false">
                                        <? echo $la['NO']; ?>
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div> 
                    
                </div>
            </div>
            <div id="manage_server_billing">
                <div class="width-1000">
                    <div class="row">
                        <div class="title-block">
                            <? echo $la['BILLING']; ?>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['ENABLE_BILLING']; ?>
                            </div>
                            <div class="width50">
                                <select id="cpanel_manage_server_billing" style="width:100px;">
                                    <option value="true">
                                        <? echo $la['YES']; ?>
                                    </option>
                                    <option value="false">
                                        <? echo $la['NO']; ?>
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['GATEWAY']; ?>
                            </div>
                            <div class="width50">
                                <select style="width: 100px;" id="cpanel_manage_server_billing_gateway" onChange="serverCheck();">
                                    <option value="paypal">PayPal</option>
                                    <option value="custom">
                                        <? echo $la['CUSTOM']; ?>
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['CURRENCY']; ?>
                            </div>
                            <div class="width50">
                                <input id="cpanel_manage_server_billing_currency" class="inputbox" style="width: 100px;" maxlength="4" placeholder="<? echo $la['EXAMPLE_SHORT']; ?> EUR" />
                            </div>
                        </div>
                    </div>
                    <div id="cpanel_manage_server_billing_paypal">
                        <div class="row">
                            <div class="title-block">
                                <? echo $la['PAYPAL_GATEWAY']; ?>
                            </div>
                            <div class="row2">
                                <div class="width50">
                                    <? echo $la['PAYPAL_ACCOUNT']; ?>
                                </div>
                                <div class="width50">
                                    <input id="cpanel_manage_server_billing_paypal_account" class="inputbox width70" maxlength="50" placeholder="<? echo $la['EXAMPLE_SHORT']; ?> my@email.com" />
                                </div>
                            </div>
                            <div class="row2">
                                <div class="width50">
                                    <? echo $la['PAYPAL_CUSTOM']; ?>
                                </div>
                                <div class="width50">
                                    <input id="cpanel_manage_server_billing_paypal_custom" class="inputbox width70" />
                                </div>
                            </div>
                            <div class="row2">
                                <div class="width50">
                                    <? echo $la['PAYPAL_IPN_URL']; ?>
                                </div>
                                <div class="width50">
                                    <input id="cpanel_manage_server_billing_paypal_ipn_url" class="inputbox width70" readOnly="true" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="cpanel_manage_server_billing_custom" style="display: none;">
                        <div class="row">
                            <div class="title-block">
                                <? echo $la['CUSTOM_GATEWAY']; ?>
                            </div>
                            <div class="row2">
                                <div class="width50">
                                    <? echo $la['CUSTOM_GATEWAY_URL']; ?>
                                </div>
                                <div class="width50">
                                    <textarea id="cpanel_manage_server_billing_custom_url" style="height: 75px;" class="inputbox width100" maxlength="2048" placeholder="<? echo $la['EXAMPLE_SHORT'].' '.$la['HTTP_FULL_ADDRESS_HERE']; ?>" /></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="title-block">
                                <? echo $la['VARIABLES']; ?>
                            </div>
                            <div class="row">
                                <? echo $la['VAR_BILLING_USER_EMAIL']; ?>
                            </div>
                            <div class="row">
                                <? echo $la['VAR_BILLING_PLAN_ID']; ?>
                            </div>
                            <div class="row">
                                <? echo $la['VAR_BILLING_PLAN_NAME']; ?>
                            </div>
                            <div class="row">
                                <? echo $la['VAR_BILLING_PLAN_PRICE']; ?>
                            </div>
                            <div class="row">
                                <? echo $la['VAR_BILLING_CURRENCY']; ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="title-block">
                            <? echo $la['BILLING_PLANS']; ?>
                        </div>
                        <div class="row2">
                            <div class="width100">
                                <div class="float-right">
                                    <a href="#" onclick="loadGridList('billing');">
                                        <div class="panel-button" title="<? echo $la['RELOAD']; ?>">
                                            <img src="theme/images/refresh-color.svg" width="16px" border="0" />
                                        </div>
                                    </a>
                                    <a href="#" onclick="billingPlanProperties('add');">
                                        <div class="panel-button" title="<? echo $la['ADD']; ?>">
                                            <img src="theme/images/billing-add.svg" width="16px" border="0" />
                                        </div>
                                    </a>
                                    <a href="#" onclick="billingPlanDeleteAll();">
                                        <div class="panel-button" title="<? echo $la['DELETE_ALL']; ?>">
                                            <img src="theme/images/remove2.svg" width="16px" border="0" />
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="width100">
                            <table id="cpanel_manage_server_billing_plan_list_grid"></table>
                        </div>
                    </div>
                </div>
            </div>
            <div id="manage_server_templates">
                <div class="width-100">
                    <div class="block width25">&nbsp;</div>
                    <div class="block width50">
                        <div class="row">
                            <div class="title-block">
                                <? echo $la['TEMPLATES']; ?>
                            </div>
                            <div class="width100">
                                <table id="cpanel_manage_server_template_list_grid"></table>
                            </div>
                        </div>
                    </div>
                    <div class="block width25">&nbsp;</div>
                </div>
            </div>
            <div id="manage_server_email">
                <div class="width-100">
                    <div class="row">
                        <div class="title-block">
                            <? echo $la['EMAIL_SETTINGS']; ?>
                        </div>
                        <div class="row2">
                            <div class="width20">
                                <? echo $la['EMAIL_ADDRESS']; ?>
                            </div>
                            <div class="width30">
                                <input id="cpanel_manage_server_email" class="inputbox width70" maxlength="50" placeholder="<? echo $la['EXAMPLE_SHORT']; ?> server@email.com" />
                            </div>
                            <div class="width20">
                                <? echo $la['NO_REPLY_EMAIL_ADDRESS']; ?>
                            </div>
                            <div class="width30">
                                <input id="cpanel_manage_server_email_no_reply" class="inputbox width70" maxlength="50" placeholder="<? echo $la['EXAMPLE_SHORT']; ?> no_reply@email.com" />
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width20">
                                <? echo $la['SIGNATURE']; ?>
                            </div>
                            <div class="width30">
                                <textarea id="cpanel_manage_server_email_signature" class="inputbox width70" style="height: 50px;" type='text' maxlength="200"></textarea>
                            </div>
                            <div class="width20">
                                <? echo $la['USE_SMTP_SERVER']; ?>
                            </div>
                            <div class="width30">
                                <select style="width: 270px;" id="cpanel_manage_server_email_smtp" onChange="serverCheck();">
                                    <option value="true">
                                        <? echo $la['YES']; ?>
                                    </option>
                                    <option value="false">
                                        <? echo $la['NO']; ?>
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width20">
                                <? echo $la['SMTP_SERVER_HOST']; ?>
                            </div>
                            <div class="width30">
                                <input id="cpanel_manage_server_email_smtp_host" class="inputbox width70" maxlength="50" placeholder="<? echo $la['EXAMPLE_SHORT']; ?> smtp.gmail.com" />
                            </div>
                             <div class="width20">
                                <? echo $la['SMTP_SERVER_PORT']; ?>
                            </div>
                            <div class="width30">
                                <input id="cpanel_manage_server_email_smtp_port" class="inputbox width70" maxlength="4" placeholder="<? echo $la['EXAMPLE_SHORT']; ?> 465" />
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width20">
                                <? echo $la['SMTP_AUTH']; ?>
                            </div>
                            <div class="width30">
                                <select style="width: 270px;" id="cpanel_manage_server_email_smtp_auth">
                                    <option value="true">
                                        <? echo $la['YES']; ?>
                                    </option>
                                    <option value="false">
                                        <? echo $la['NO']; ?>
                                    </option>
                                </select>
                            </div>
                             <div class="width20">
                                <? echo $la['SMTP_SECURITY']; ?>
                            </div>
                            <div class="width30">
                                <select style="width: 270px;" id="cpanel_manage_server_email_smtp_secure">
                                    <option value="">
                                        <? echo $la['NONE']; ?>
                                    </option>
                                    <option value="ssl">SSL</option>
                                    <option value="tls">TLS</option>
                                </select>
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width20">
                                <? echo $la['SMTP_USERNAME']; ?>
                            </div>
                            <div class="width30">
                                <input id="cpanel_manage_server_email_smtp_username" class="inputbox width70" maxlength="50" />
                            </div>
                            <div class="width20">
                                <? echo $la['SMTP_PASSWORD']; ?>
                            </div>
                            <div class="width30">
                                <input id="cpanel_manage_server_email_smtp_password" type="password" class="inputbox width70" maxlength="50" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="manage_server_sms">
                <div class="width-100">
                    <div class="row">
                        <div class="title-block">
                            <? echo $la['SMS_GATEWAY']; ?>
                        </div>
                        <div class="row2">
                            <div class="width20">
                                <? echo $la['ENABLE_SMS_GATEWAY']; ?>
                            </div>
                            <div class="width30">
                                <select class="width90" id="cpanel_manage_server_sms_gateway">
                                    <option value="true">
                                        <? echo $la['YES']; ?>
                                    </option>
                                    <option value="false">
                                        <? echo $la['NO']; ?>
                                    </option>
                                </select>
                            </div>
                            <div class="width20">
                                <? echo $la['SMS_GATEWAY_TYPE']; ?>
                            </div>
                            <div class="width30">
                                <select class="width100" id="cpanel_manage_server_sms_gateway_type" onchange="serverCheck()">
                                    <option value="app" selected>
                                        <? echo $la['MOBILE_APPLICATION'];?>
                                    </option>
                                    <option value="http">HTTP</option>
                                </select>
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width40">
                                <? echo $la['SMS_GATEWAY_NUMBER_FILTER']; ?>
                            </div>
                            <div class="width60">
                                <input class="inputbox" id="cpanel_manage_server_sms_gateway_number_filter" placeholder="<? echo $la['EXAMPLE_SHORT']; ?> +370, +7, +44, +..." />
                            </div>
                        </div>
                    </div>

                    <div id="cpanel_manage_server_sms_app">
                        <div class="row">
                            <div class="title-block">
                                <? echo $la['MOBILE_APPLICATION'];?>
                            </div>
                            <div class="row">
                                <? echo sprintf($la['SMS_GATEWAY_MOBILE_APPLICATION_EXPLANATION'], $gsValues['URL_SMS_GATEWAY_APP']); ?>
                            </div>
                            <div class="row2">
                                <div class="width40">
                                    <? echo $la['SMS_GATEWAY_IDENTIFIER']; ?>
                                </div>
                                <div class="width21">
                                    <input class="inputbox" id="cpanel_manage_server_sms_gateway_identifier" readonly />
                                </div>
                            </div>
                            <div class="row2">
                                <div class="width40">
                                    <? echo $la['TOTAL_SMS_IN_QUEUE_TO_SEND']; ?>
                                </div>
                                <div class="width10" id="cpanel_manage_server_sms_gateway_total_in_queue">0</div>
                                <div class="width1"></div>
                                <div class="width10">
                                    <input class="button width100" type="button" onclick="SMSGatewayClearQueue();" value="<? echo $la['CLEAR']; ?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="cpanel_manage_server_sms_http" style="display: none;">
                        <div class="row">
                            <div class="title-block">HTTP</div>
                            <div class="row">
                                <? echo $la['SMS_GATEWAY_EXPLANATION']; ?>
                            </div>
                            <div class="row">
                                <? echo $la['SMS_GATEWAY_EXAMPLE']; ?>
                            </div>
                            <div class="row2">
                                <div class="width40">
                                    <? echo $la['SMS_GATEWAY_URL']; ?>
                                </div>
                                <div class="width60">
                                    <textarea id="cpanel_manage_server_sms_gateway_url" style="height: 75px;" class="inputbox width100" maxlength="2048" placeholder="<? echo $la['EXAMPLE_SHORT'].' '.$la['HTTP_FULL_ADDRESS_HERE']; ?>" /></textarea>
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
            </div>

            <div id="manage_server_tools">
                <div class="width-100">
                    <div class="row">
                        <div class="title-block">
                            <? echo $la['SERVER_CLEANUP']; ?>
                        </div>
                        <div class="row2">
                            <div class="width40">
                                <? echo $la['SERVER_CLEANUP_USERS']; ?>
                            </div>
                            <div class="width12">
                                <? echo $la['LAST_LOGIN_DAYS_AGO']; ?>
                            </div>
                            <div class="width12">
                                <input id="cpanel_manage_server_tools_server_cleanup_users_days" onkeypress="return isNumberKey(event);" class="inputbox width90" maxlength="5" />
                            </div>
                            <div class="width12">
                                <? echo $la['AUTO_EXECUTE']; ?>
                            </div>
                            <div class="width12">
                                <select id="cpanel_manage_server_tools_server_cleanup_users_ae" class="width90">
                                    <option value="true">
                                        <? echo $la['YES']; ?>
                                    </option>
                                    <option value="false">
                                        <? echo $la['NO']; ?>
                                    </option>
                                </select>
                            </div>
                            <div class="width12">
                                <input class="button icon-create icon width100" type="button" onclick="serverCleanup('users');" value="<? echo $la['EXECUTE_NOW']; ?>" />
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width40">
                                <? echo $la['SERVER_CLEANUP_OBJECTS_NOT_ACTIVATED']; ?>
                            </div>
                            <div class="width12">
                                <? echo $la['MORE_THAN_DAYS']; ?>
                            </div>
                            <div class="width12">
                                <input id="cpanel_manage_server_tools_server_cleanup_objects_not_activated_days" onkeypress="return isNumberKey(event);" class="inputbox width90" maxlength="5" />
                            </div>
                            <div class="width12">
                                <? echo $la['AUTO_EXECUTE']; ?>
                            </div>
                            <div class="width12">
                                <select id="cpanel_manage_server_tools_server_cleanup_objects_not_activated_ae" class="width90">
                                    <option value="true">
                                        <? echo $la['YES']; ?>
                                    </option>
                                    <option value="false">
                                        <? echo $la['NO']; ?>
                                    </option>
                                </select>
                            </div>
                            <div class="width12">
                                <input class="button icon-create icon width100" type="button" onclick="serverCleanup('objects_not_activated');" value="<? echo $la['EXECUTE_NOW']; ?>" />
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width40">
                                <? echo $la['SERVER_CLEANUP_OBJECTS_NOT_USED']; ?>
                            </div>
                            <div class="width12">
                            </div>
                            <div class="width12">
                            </div>
                            <div class="width12">
                                <? echo $la['AUTO_EXECUTE']; ?>
                            </div>
                            <div class="width12">
                                <select id="cpanel_manage_server_tools_server_cleanup_objects_not_used_ae" class="width90">
                                    <option value="true">
                                        <? echo $la['YES']; ?>
                                    </option>
                                    <option value="false">
                                        <? echo $la['NO']; ?>
                                    </option>
                                </select>
                            </div>
                            <div class="width12">
                                <input class="button icon-create icon width100" type="button" onclick="serverCleanup('objects_not_used');" value="<? echo $la['EXECUTE_NOW']; ?>" />
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width40">
                                <? echo $la['SERVER_CLEANUP_DB_JUNK']; ?>
                            </div>
                            <div class="width12">
                            </div>
                            <div class="width12">
                            </div>
                            <div class="width12">
                                <? echo $la['AUTO_EXECUTE']; ?>
                            </div>
                            <div class="width12">
                                <select id="cpanel_manage_server_tools_server_cleanup_db_junk_ae" class="width90">
                                    <option value="true">
                                        <? echo $la['YES']; ?>
                                    </option>
                                    <option value="false">
                                        <? echo $la['NO']; ?>
                                    </option>
                                </select>
                            </div>
                            <div class="width12">
                                <input class="button icon-create icon width100" type="button" onclick="serverCleanup('db_junk');" value="<? echo $la['EXECUTE_NOW']; ?>" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="manage_server_logs">
                <div class="width-1000">
                    <div class="row">
                        <div class="title-block">
                            <? echo $la['LOG_VIEWER']; ?>
                        </div>
                        <div class="row2">
                            <div class="width100">
                                <div class="float-right">
                                    <a href="#" onclick="loadGridList('logs');">
                                        <div class="panel-button" title="<? echo $la['RELOAD']; ?>">
                                            <img src="theme/images/refresh-color.svg" width="16px" border="0" />
                                        </div>
                                    </a>
                                    <a href="#" onclick="logDeleteAll();">
                                        <div class="panel-button" title="<? echo $la['DELETE_ALL']; ?>">
                                            <img src="theme/images/remove2.svg" width="16px" border="0" />
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="width100">
                            <table id="cpanel_manage_server_log_list_grid"></table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CODE BY VETRIVEL.N -->
            <div id="manage_server_notification">
                <div class="row">
                    <div class="width-100">



                        <div class="row">
                            <div class="block width50">
                                <div class="container">
                                    <div class="title-block">
                                        <? echo $la['GENERAL_INFO']; ?>
                                    </div>
                                    <div class="row2">
                                        <div class="width40">
                                            <? echo $la['USER_LIST']; ?></br>

                                        </div>
                                        <div class="width60">
                                            <select id="ddluser">
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row2">
                                        <div class="width40">
                                            <? echo $la['INFORMATION']; ?></br>

                                        </div>
                                        <div class="width60">
                                            <input type="text" id="txtinfo" />
                                        </div>
                                    </div>

                                    <div class="row2">
                                        <div class="width40">
                                            <? echo $la['URL']; ?></br>

                                        </div>
                                        <div class="width60">
                                            <input type="text" id="txtinfourl" />
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="block width50">
                                <div class="container last">
                                    <div class="title-block">
                                        <? echo $la['TIME_PERIOD']; ?>
                                    </div>
                                    <!-- 
                  <div class="row2">
            <div class="width40"><? echo $la['DAYS_INTERVAL']; ?></div>
              <div class="width60">
               <input id="txtdaysintervaldaily"  style="width:53px" class="inputbox" type="text" disabled="disabled" value="1"  />
              </div>
            </div>
             	-->

                                    <div class="row2">
                                        <div class="width40">
                                            <? echo $la['TIME_FROM']; ?>
                                        </div>
                                        <div class="width60">

                                            <input id="dialog_allocate_date_fromdaily" readonly="" class="inputbox-calendar inputbox" type="text" maxlength="50" style="width:100px">
                                            &nbsp;
                                            <select style="width:53px" id="dialog_allocate_hour_fromdaily">
                                                <option value="00">00</option>
                                                <option value="01">01</option>
                                                <option value="02">02</option>
                                                <option value="03">03</option>
                                                <option value="04">04</option>
                                                <option value="05">05</option>
                                                <option value="06">06</option>
                                                <option value="07">07</option>
                                                <option value="08">08</option>
                                                <option value="09">09</option>
                                                <option value="10">10</option>
                                                <option value="11">11</option>
                                                <option value="12">12</option>
                                                <option value="13">13</option>
                                                <option value="14">14</option>
                                                <option value="15">15</option>
                                                <option value="16">16</option>
                                                <option value="17">17</option>
                                                <option value="18">18</option>
                                                <option value="19">19</option>
                                                <option value="20">20</option>
                                                <option value="21">21</option>
                                                <option value="22">22</option>
                                                <option value="23">23</option>
                                            </select>&nbsp;:
                                            <select style="width:53px" id="dialog_allocate_minute_fromdaily">
                                                <option value="00">00</option>
                                                <option value="01">01</option>
                                                <option value="02">02</option>
                                                <option value="03">03</option>
                                                <option value="04">04</option>
                                                <option value="05">05</option>
                                                <option value="06">06</option>
                                                <option value="07">07</option>
                                                <option value="08">08</option>
                                                <option value="09">09</option>
                                                <option value="10">10</option>
                                                <option value="11">11</option>
                                                <option value="12">12</option>
                                                <option value="13">13</option>
                                                <option value="14">14</option>
                                                <option value="15">15</option>
                                                <option value="16">16</option>
                                                <option value="17">17</option>
                                                <option value="18">18</option>
                                                <option value="19">19</option>
                                                <option value="20">20</option>
                                                <option value="21">21</option>
                                                <option value="22">22</option>
                                                <option value="23">23</option>
                                                <option value="24">24</option>
                                                <option value="25">25</option>
                                                <option value="26">26</option>
                                                <option value="27">27</option>
                                                <option value="28">28</option>
                                                <option value="29">29</option>
                                                <option value="30">30</option>
                                                <option value="31">31</option>
                                                <option value="32">32</option>
                                                <option value="33">33</option>
                                                <option value="34">34</option>
                                                <option value="35">35</option>
                                                <option value="36">36</option>
                                                <option value="37">37</option>
                                                <option value="38">38</option>
                                                <option value="39">39</option>
                                                <option value="40">40</option>
                                                <option value="41">41</option>
                                                <option value="42">42</option>
                                                <option value="43">43</option>
                                                <option value="44">44</option>
                                                <option value="45">45</option>
                                                <option value="46">46</option>
                                                <option value="47">47</option>
                                                <option value="48">48</option>
                                                <option value="49">49</option>
                                                <option value="50">50</option>
                                                <option value="51">51</option>
                                                <option value="52">52</option>
                                                <option value="53">53</option>
                                                <option value="54">54</option>
                                                <option value="55">55</option>
                                                <option value="56">56</option>
                                                <option value="57">57</option>
                                                <option value="58">58</option>
                                                <option value="59">59</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row2">
                                        <div class="width40">
                                            <? echo $la['TIME_TO']; ?>
                                        </div>
                                        <div class="width60">

                                            <input id="dialog_allocate_date_todaily" readonly="" class="inputbox-calendar inputbox" type="text" maxlength="50" style="width:100px">
                                            &nbsp;
                                            <select style="width:53px" id="dialog_allocate_hour_todaily">
                                                <option value="00">00</option>
                                                <option value="01">01</option>
                                                <option value="02">02</option>
                                                <option value="03">03</option>
                                                <option value="04">04</option>
                                                <option value="05">05</option>
                                                <option value="06">06</option>
                                                <option value="07">07</option>
                                                <option value="08">08</option>
                                                <option value="09">09</option>
                                                <option value="10">10</option>
                                                <option value="11">11</option>
                                                <option value="12">12</option>
                                                <option value="13">13</option>
                                                <option value="14">14</option>
                                                <option value="15">15</option>
                                                <option value="16">16</option>
                                                <option value="17">17</option>
                                                <option value="18">18</option>
                                                <option value="19">19</option>
                                                <option value="20">20</option>
                                                <option value="21">21</option>
                                                <option value="22">22</option>
                                                <option value="23">23</option>
                                            </select>&nbsp;:
                                            <select style="width:53px" id="dialog_allocate_minute_todaily">
                                                <option value="00">00</option>
                                                <option value="01">01</option>
                                                <option value="02">02</option>
                                                <option value="03">03</option>
                                                <option value="04">04</option>
                                                <option value="05">05</option>
                                                <option value="06">06</option>
                                                <option value="07">07</option>
                                                <option value="08">08</option>
                                                <option value="09">09</option>
                                                <option value="10">10</option>
                                                <option value="11">11</option>
                                                <option value="12">12</option>
                                                <option value="13">13</option>
                                                <option value="14">14</option>
                                                <option value="15">15</option>
                                                <option value="16">16</option>
                                                <option value="17">17</option>
                                                <option value="18">18</option>
                                                <option value="19">19</option>
                                                <option value="20">20</option>
                                                <option value="21">21</option>
                                                <option value="22">22</option>
                                                <option value="23">23</option>
                                                <option value="24">24</option>
                                                <option value="25">25</option>
                                                <option value="26">26</option>
                                                <option value="27">27</option>
                                                <option value="28">28</option>
                                                <option value="29">29</option>
                                                <option value="30">30</option>
                                                <option value="31">31</option>
                                                <option value="32">32</option>
                                                <option value="33">33</option>
                                                <option value="34">34</option>
                                                <option value="35">35</option>
                                                <option value="36">36</option>
                                                <option value="37">37</option>
                                                <option value="38">38</option>
                                                <option value="39">39</option>
                                                <option value="40">40</option>
                                                <option value="41">41</option>
                                                <option value="42">42</option>
                                                <option value="43">43</option>
                                                <option value="44">44</option>
                                                <option value="45">45</option>
                                                <option value="46">46</option>
                                                <option value="47">47</option>
                                                <option value="48">48</option>
                                                <option value="49">49</option>
                                                <option value="50">50</option>
                                                <option value="51">51</option>
                                                <option value="52">52</option>
                                                <option value="53">53</option>
                                                <option value="54">54</option>
                                                <option value="55">55</option>
                                                <option value="56">56</option>
                                                <option value="57">57</option>
                                                <option value="58">58</option>
                                                <option value="59">59</option>
                                            </select>


                                        </div>
                                    </div>
                                    <div class="row2">
                                        <center>
                                            <input class="button icon-save icon" type="button" onclick="notification('save');" value="<? echo $la['SAVE']; ?>" />&nbsp;
                                            <input class="button icon-close icon" type="button" onclick="notification('cancel');" value="<? echo $la['CANCEL']; ?>" />
                                        </center>
                                    </div>

                                </div>
                            </div>

                            <div class="block width100">
                                <div class="container">

                                </div>
                            </div>

                        </div>
                        <div class="width100">
                            <table id="notification_grid"></table>
                            <div id="notification_pager"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CODE END BY VETRIVEL.N -->

        </div>
    </div>
	</div>