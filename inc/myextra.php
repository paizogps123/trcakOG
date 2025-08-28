<!-- CODE DONE BY VETRIVEL.N -->


<div id="dialog_object_commandnew_fullview" class="opensettings" style="display: none;    padding-top: 55px;" title="<? echo $la['SEND_COMMAND'];?>">
       
   <!--  <div class="controls-block width100">
        <input class="button icon-new icon" type="button" onclick="cmdNewnew();" value="<? echo $la['NEW']; ?>" />
        <input class="button icon-create icon" type="button" onclick="cmdSendnew();" value="<? echo $la['SEND']; ?>" />
    </div> -->

    <div id="object_control_tabs">


        <!-- <ul>           
			<li><a href="#cmdnew"><? echo $la['COMMANDS'];?></a></li>
			
			<li><a href="#cmd_statusnew"><? echo $la['COMMANDS_STATUS'];?></a></li>
			
		</ul> -->

        <div id="cmdnew">
            <!-- <div class="title-block">
                <? echo $la['COMMAND_PROPERTIES'];?>
                <input type="button" value="<->" id="btninqset" style="float:right;" />

            </div> -->
			<div class="block fullviewheight width100">
        <div class="block width10">&nbsp;
        </div>
            <div class="block width30 " >
                <div class="title-block sendcmd_monvi">
                    <? echo $la['COMMAND_PROPERTIES'];?>
                         <input type="button" value="<->" id="btninqset" style="float:right;    margin-top: -6px;" />

                    </div>

                    <div class="container sendcmd_monvi">
                        <div class="row2">
                            <div class="width40">
                                <? echo $la['GPS_DEVICE'];?>
                            </div>
			    <div class="width60">
				<select id="cmd_devicetype" class="width100 textboxextra_style" />
                                <option value="Play FM100">PGS FM100</option>
                                <option value="Play 4000">PGS 4000</option>
                                <option value="Play 5000">PGS 5000</option>
                                <option value="Play 6000">PGS 6000</option>
                                <option value="Play 6000F">PGS 6000F</option>
                                <option value="Play T20">PGS T20</option>
                                <option value="Play T09">PGS T09</option>
                                <option value="Play T09+">PGS T09+</option>
                                </select>
                            </div>
                        </div>
						<div class="row2">
                            <div class="width40">
                                <? echo $la['CMMANDNAME'];?>
                            </div>
                            <div class="width60">
                                <select id="cmd_gatewaynew" class="width100 textboxextra_style" />
                                </select>
                            </div>
                        </div>
						<div class="row2">
                            <div class="width40">
                                <? echo $la['OBJECT'];?>
                            </div>
                                <div class="width25" style="margin-right: -3px;">
                                    <div class="container">
                                        <input id="cmd_objectsearch" class="inputbox textboxextra_style" style="width:81px;" class="width100" type="text" value="">
                                    </div>
                                </div>
								 <div class="width10"></div>
                                <div class="width25">
                                    <select class="width100 textboxextra_style" id="cmd_object_listnew"></select>
                                </div>
                        </div>
                        <div class="row2">
                            <div class="width40">
                                <? echo $la['COMMAND'];?>
                            </div>
							<div class="width25" style="margin-right: -3px;">
								<div class="container">
									<select id="cmd_typenew" class="width100 textboxextra_style" style="width:81px;" />
									<option value="ASCII">ASCII</option>
									<option value="HEX">HEX</option>
									</select>
								</div>
							</div>
							 <div class="width10"></div>
							<div class="width25">
								<input id="cmd_stringnew" class="inputbox textboxextra_style" type="text" value="" maxlength="160">
							</div>

                        </div>
					</div>
					<div class="width100 sendcmd_monvi" >
					<div class="width20"></div>
					<div class="width80">
						<input class="button icon-new icon textboxextra_style" type="button" onclick="cmdNewnew();" value="<? echo $la['NEW']; ?>" />
						<input class="button icon-create icon textboxextra_style" type="button" onclick="cmdSendnew();" value="<? echo $la['SEND']; ?>" />
						</div>
					</div>
			</div>
			<div class="block width1">
			 <div class="vl"></div>
			</div>
			<div class="block width50" style="width: 59%">
				<div id="cmd_status_listnew">
					<table id="cmd_status_list_gridnew"></table>
					<div id="cmd_status_list_grid_pagernew"></div>
				</div>
			</div>
			</div>
            <div class="row">
            </div>
            
        </div>

        <div id="cmd_listnew">
            <div id="cmd_status_listnew">
                <table id="cmd_list_gridnew"></table>
                <div id="cmd_list_grid_pagernew"></div>
            </div>
        </div>
    </div>


</div>



<div id="dialog_settings_working_hour_field_properties" title="<? echo $la['WORKING_HOUR_PROPERTIES'];?>">
    <div class="row">
        <div class="row2">
            <div class="width40">
                <? echo $la['TIME_FROM']; ?>
            </div>
            <div class="width60">
                <select id="dialog_settings_work_time_from" style="width:70px">
                    <? include ("inc/inc_dt.hours_minutes.php"); ?>
                </select>
            </div>
        </div>
        <div class="row2">
            <div class="width40">
                <? echo $la['TIME_TO']; ?>
            </div>
            <div class="width60">
                <select id="dialog_settings_work_time_to" style="width:70px">
                    <? include ("inc/inc_dt.hours_minutes.php"); ?>
                </select>
            </div>
        </div>
        <div class="row2">
            <div class="width40">
                <? echo $la['TYPE']; ?>
            </div>
            <div class="width60">

                <select style="width:70px" id="dialog_settings_work_hour_type">
                    <option>Same Day</option>
                    <option>Different Day</option>
                </select>
            </div>
        </div>
        <div class="row2">
            <div class="width40">
                <? echo $la['DAY']; ?>
            </div>
            <div class="width60">
                <select style="width:70px" id="dialog_settings_work_hour_day">
                    <option>Sun</option>
                    <option>Mon</option>
                    <option>Tue</option>
                    <option>Wed</option>
                    <option>Thr</option>
                    <option>Fri</option>
                    <option>San</option>
                </select>
            </div>
        </div>
        <div class="row2">
            <div class="width40">
                <? echo $la['STATUS']; ?>
            </div>
            <div class="width60"><input id="dialog_settings_work_hour_status" type="checkbox" class="checkbox" /></div>
        </div>
    </div>
    <center>
        <input class="button icon-save icon" type="button" onclick="settingsObjectWorkingHourProperties('save');" value="<? echo $la['SAVE']; ?>" />&nbsp;
        <input class="button icon-close icon" type="button" onclick="settingsObjectWorkingHourProperties('cancel');" value="<? echo $la['CANCEL']; ?>" />
    </center>
</div>


<div id="dialog_settings_shift_allocation" title="<? echo $la['SHIFT_ALLOCATION'];?>">
    <div class="row">
        <div class="row2">
            <div class="width40">
                <? echo $la['FORMAT']; ?>
            </div>
            <div class="width60">
                <select id="dialog_settings_shift_allocation_crew" class="width100">
                    <option>Select</option>
                    <?php
			 global $user_id_allv;
			 if(isset($gsValues['POST_RFID'][$user_id_allv]))
			 {
			 $crewtype=$gsValues['POST_RFID'][$user_id_allv]["crew_format"];
   			 for($icf=0;$icf<count($crewtype);$icf++) { ?>
                    <option>
                        <?= $crewtype[$icf] ?>
                    </option>
                    <?php
    		} }?>
                </select>
            </div>
        </div>
        <div class="row2">
            <div class="width40">
                <? echo $la['DATE']; ?>
            </div>
            <div class="width60">
                <input id="dialog_settings_shift_allocation_date" class="inputbox-calendar inputbox width100" readonly class="width100" type="text" value="">
            </div>
        </div>
        <br>
    </div>
    <center>
        <input class="button icon-save icon" type="button" onclick="settingsShiftAllocation('save');" value="<? echo $la['SAVE']; ?>" />&nbsp;
        <input class="button icon-close icon" type="button" onclick="settingsShiftAllocation('cancel');" value="<? echo $la['CANCEL']; ?>" />
    </center>
</div>
