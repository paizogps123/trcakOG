<div id="dialog_cmd_fullview" class="opensettings" style="display: none;    padding-top: 55px;"  title="<? echo $la['OBJECT_CONTROL'];?>">
    <div id="cmd_tabs" class="fullview_mobvi">
        <div class="block fullviewheight width100">
        <div class="block width8">&nbsp;
        </div>
            <div class="block width17">
                <ul id="cmdmenu" class="changecmdmenu">
                    <li><a href="#cmd_control_tab" class="hvr-sweep-to-bottom  ">
                            <? echo $la['CONTROL'];?></a></li>
                    <li><a href="#cmd_schedule_tab" class="hvr-sweep-to-bottom1 ">
                            <? echo $la['SCHEDULE'];?></a></li>
                    <li><a href="#cmd_templates_tab" class="hvr-sweep-to-bottom2 ">
                            <? echo $la['TEMPLATES'];?></a></li>
                </ul>
            </div>
             <div class="block width3">
            <div class="vl"></div>
            </div>
        <div class="block width70 overflow_mobvi" style="    width: 74%;"> 
            <div id="cmd_control_tab">
                <div class="row">
				<div class="block width90">
                    <div class="block width100">
                        <div class="container last">
                            <div class="row2">
                                <div class="width15">
                                    <? echo $la['OBJECT'];?>
                                </div>
                                <div class="width34"><select class="width80 cmdborder"  id="cmd_object_list" onchange="cmdTemplateList();"></select></div>
                                <div class="width1"></div>
                                <div class="width20">
                                    <? echo $la['GATEWAY'];?>
                                </div>
                                <div class="width30">
                                    <select id="cmd_gateway"  class="width90 cmdborder" />
                                    <option value="gprs">GPRS</option>
                                    <option value="sms">SMS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row2">
                                <div class="width15">
                                    <? echo $la['TEMPLATE'];?>
                                </div>
                                <div class="width34"><select class="width80 cmdborder" id="cmd_template_list" onchange="cmdTemplateSwitch();"></select></div>
                                <div class="width1"></div>
                                <div class="width20">
                                    <? echo $la['TYPE'];?>
                                </div>
                                <div class="width30">
                                    <select id="cmd_type"  class="width90 cmdborder"/>
                                    <option value="ascii">ASCII</option>
                                    <option value="hex">HEX</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="block width100">
                        <div class="container last">
                            <div class="row2">
                                <div class="width15">
                                    <? echo $la['COMMAND'];?>
                                </div>
                                <div class="width83">
                                    <input id="cmd_cmd" class="inputbox cmdborder" type="text" value="" maxlength="256">
                                </div>
                            </div>
                        </div>
                    </div>
				</div>
				<div class="block width10 "><br>
				<input class="button width100 cmdbutton" type="button" onclick="cmdSend();" value="<? echo $la['SEND']; ?>" />
				</div>
                </div>
                <table id="cmd_status_list_grid"></table>
                <div id="cmd_status_list_grid_pager"></div>
            </div>
            <div id="cmd_schedule_tab">
                <table id="cmd_schedule_list_grid"></table>
                <div id="cmd_schedule_list_grid_pager"></div>
            </div>

            <div id="cmd_templates_tab">
                <table id="cmd_template_list_grid"></table>
                <div id="cmd_template_list_grid_pager"></div>
            </div>
			</div>
        </div>
		</div>
    </div>

    <div id="dialog_cmd_schedule_properties" title="<? echo $la['SCHEDULE_PROPERTIES'];?>">
        <div class="row">
            <div class="block width50">
                <div class="container">
                    <div class="title-block">
                        <? echo $la['SCHEDULE']; ?>
                    </div>
                    <div class="row2">
                        <div class="width35">
                            <? echo $la['ACTIVE']; ?>
                        </div>
                        <div class="width65"><input id="dialog_cmd_schedule_active" type="checkbox" checked="checked" /></div>
                    </div>
                    <div class="row2">
                        <div class="width35">
                            <? echo $la['NAME']; ?>
                        </div>
                        <div class="width65"><input id="dialog_cmd_schedule_name" class="inputbox cmdborder" type="text" value="" maxlength="25"></div>
                    </div>
                    <div class="row2">
                        <div class="width35">
                            <? echo $la['EXACT_TIME'];?>
                        </div>
                        <div class="width10">
                            <input id="dialog_cmd_schedule_exact_time" type="checkbox" class="checkbox cmdborder" onchange="cmdScheduleSwitchExactTime();" />
                        </div>
                        <div class="width55">
                            <input readonly class="inputbox-calendar inputbox width50 cmdborder" id="dialog_cmd_schedule_exact_time_date" type="text" value="" />
                            <select id="dialog_cmd_schedule_exact_time_time" class="cmdborder">
                                <? include ("inc/inc_dt.hours_minutes.php"); ?>
                            </select>
                        </div>
                    </div>
                    <div class="row2">
                        <div class="width35">
                            <? echo $la['DAY_MONDAY']; ?>
                        </div>
                        <div class="width10">
                            <input id="dialog_cmd_schedule_daily_mon" type="checkbox" class="checkbox" />
                        </div>
                        <div class="width55">
                            <select id="dialog_cmd_schedule_daily_mon_time" class="cmdborder">
                                <? include ("inc/inc_dt.hours_minutes.php"); ?>
                            </select>
                        </div>
                    </div>
                    <div class="row2">
                        <div class="width35">
                            <? echo $la['DAY_TUESDAY']; ?>
                        </div>
                        <div class="width10">
                            <input id="dialog_cmd_schedule_daily_tue" type="checkbox" class="checkbox" />
                        </div>
                        <div class="width55">
                            <select id="dialog_cmd_schedule_daily_tue_time" class="cmdborder">
                                <? include ("inc/inc_dt.hours_minutes.php"); ?>
                            </select>
                        </div>
                    </div>
                    <div class="row2">
                        <div class="width35">
                            <? echo $la['DAY_WEDNESDAY']; ?>
                        </div>
                        <div class="width10">
                            <input id="dialog_cmd_schedule_daily_wed" type="checkbox" class="checkbox" />
                        </div>
                        <div class="width55">
                            <select id="dialog_cmd_schedule_daily_wed_time" class="cmdborder">
                                <? include ("inc/inc_dt.hours_minutes.php"); ?>
                            </select>
                        </div>
                    </div>
                    <div class="row2">
                        <div class="width35">
                            <? echo $la['DAY_THURSDAY']; ?>
                        </div>
                        <div class="width10">
                            <input id="dialog_cmd_schedule_daily_thu" type="checkbox" class="checkbox" />
                        </div>
                        <div class="width55">
                            <select id="dialog_cmd_schedule_daily_thu_time" class="cmdborder">
                                <? include ("inc/inc_dt.hours_minutes.php"); ?>
                            </select>
                        </div>
                    </div>
                    <div class="row2">
                        <div class="width35">
                            <? echo $la['DAY_FRIDAY']; ?>
                        </div>
                        <div class="width10">
                            <input id="dialog_cmd_schedule_daily_fri" type="checkbox" class="checkbox" />
                        </div>
                        <div class="width55">
                            <select id="dialog_cmd_schedule_daily_fri_time" class="cmdborder">
                                <? include ("inc/inc_dt.hours_minutes.php"); ?>
                            </select>
                        </div>
                    </div>
                    <div class="row2">
                        <div class="width35">
                            <? echo $la['DAY_SATURDAY']; ?>
                        </div>
                        <div class="width10">
                            <input id="dialog_cmd_schedule_daily_sat" type="checkbox" class="checkbox" />
                        </div>
                        <div class="width55">
                            <select id="dialog_cmd_schedule_daily_sat_time" class="cmdborder">
                                <? include ("inc/inc_dt.hours_minutes.php"); ?>
                            </select>
                        </div>
                    </div>
                    <div class="row2">
                        <div class="width35">
                            <? echo $la['DAY_SUNDAY']; ?>
                        </div>
                        <div class="width10">
                            <input id="dialog_cmd_schedule_daily_sun" type="checkbox" class="checkbox" />
                        </div>
                        <div class="width55">
                            <select id="dialog_cmd_schedule_daily_sun_time" class="cmdborder">
                                <? include ("inc/inc_dt.hours_minutes.php"); ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block width50">
                <div class="container last">
                    <div class="title-block">
                        <? echo $la['OBJECTS']; ?>
                    </div>
                    <div class="row2">
                        <div class="width100">
                            <select class="width100 cmdborder" id="dialog_cmd_schedule_protocol" onchange="cmdScheduleSwitchProtocol();"></select>
                        </div>
                    </div>
                    <div class="row2">
                        <div class="width100">
                            <select class="width100 cmdborder" id="dialog_cmd_schedule_object_list" style="height:239px;" multiple="multiple" onchange=""></select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="block width100">
                <div class="container last">
                    <div class="title-block">
                        <? echo $la['COMMAND']; ?>
                    </div>
                    <div class="block width50">
                        <div class="container">
                            <div class="row2">
                                <div class="width35">
                                    <? echo $la['TEMPLATE'];?>
                                </div>
                                <div class="width65"><select class="width100 cmdborder" id="dialog_cmd_schedule_template_list" onchange="cmdScheduleTemplateSwitch();"></select></div>
                            </div>
                        </div>
                    </div>
                    <div class="block width25">
                        <div class="container">
                            <div class="row2">
                                <div class="width50">
                                    <? echo $la['GATEWAY'];?>
                                </div>
                                <div class="width50">
                                    <select id="dialog_cmd_schedule_cmd_gateway" class="width100 cmdborder" />
                                    <option value="gprs">GPRS</option>
                                    <option value="sms">SMS</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="block width25">
                        <div class="container last">
                            <div class="row2">
                                <div class="width50">
                                    <? echo $la['TYPE'];?>
                                </div>
                                <div class="width50">
                                    <select id="dialog_cmd_schedule_cmd_type" class="width100 cmdborder" />
                                    <option value="ascii">ASCII</option>
                                    <option value="hex">HEX</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row2">
                        <div class="width17">
                            <? echo $la['COMMAND'];?>
                        </div>
                        <div class="width83"><input id="dialog_cmd_schedule_cmd_cmd" class="inputbox cmdborder" type="text" value="" maxlength="256"></div>
                    </div>
                </div>
            </div>
        </div>

        <center>
            <input class="button cmdbutton" type="button" onclick="cmdScheduleProperties('save');" value="<? echo $la['SAVE']; ?>" />&nbsp;
            <input class="button cmdbutton" type="button" onclick="cmdScheduleProperties('cancel');" value="<? echo $la['CANCEL']; ?>" />
        </center>
    </div>

    <div id="dialog_cmd_template_properties" title="<? echo $la['COMMAND_PROPERTIES'];?>">
        <div class="row">
            <div class="title-block">
                <? echo $la['TEMPLATE']; ?>
            </div>
            <div class="row2">
                <div class="width35">
                    <? echo $la['NAME']; ?>
                </div>
                <div class="width65"><input id="dialog_cmd_template_name" class="inputbox cmdborder" type="text" value="" maxlength="25"></div>
            </div>
            <div class="row2">
                <div class="width35">
                    <? echo $la['HIDE_UNUSED_PROTOCOLS']; ?>
                </div>
                <div class="width65">
                    <input id="dialog_cmd_template_hide_unsed_protocols" type="checkbox" class="checkbox" onchange="cmdTemplateProtocolList();" />
                </div>
            </div>
            <div class="row2">
                <div class="width35">
                    <? echo $la['PROTOCOL']; ?>
                </div>
                <div class="width65">
                    <select class="width100 cmdborder" id="dialog_cmd_template_protocol"></select>
                </div>
            </div>
            <div class="row2">
                <div class="width35">
                    <? echo $la['GATEWAY'];?>
                </div>
                <div class="width65">
                    <select id="dialog_cmd_template_gateway" class="cmdborder" style="width: 70px;" />
                    <option value="gprs">GPRS</option>
                    <option value="sms">SMS</option>
                    </select>
                </div>
            </div>
            <div class="row2">
                <div class="width35">
                    <? echo $la['TYPE'];?>
                </div>
                <div class="width65">
                    <select id="dialog_cmd_template_type" class="cmdborder" style="width: 70px;" />
                    <option value="ascii">ASCII</option>
                    <option value="hex">HEX</option>
                    </select>
                </div>
            </div>
            <div class="row2">
                <div class="width35">
                    <? echo $la['COMMAND'];?>
                </div>
                <div class="width65">
                    <input id="dialog_cmd_template_cmd" class="inputbox cmdborder" type="text" value="" maxlength="256">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="block width100">
                <div class="container last">
                    <div class="title-block">
                        <? echo $la['VARIABLES']; ?>
                    </div>
                    <div class="row2">
                        <div class="row">
                            <? echo $la['VAR_TEMPLATE_IMEI']; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <center >
            <input class="button cmdbutton" type="button" onclick="cmdTemplateProperties('save');" value="<? echo $la['SAVE']; ?>" />&nbsp;
            <input class="button cmdbutton" type="button" onclick="cmdTemplateProperties('cancel');" value="<? echo $la['CANCEL']; ?>" />
        </center>
    </div>