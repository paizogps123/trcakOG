<div id="dialog_object_add" title="<? echo $la['ADD_OBJECT'] ?>">		
        <div class="row">
                <div class="row2">
                        <div class="width40"><? echo $la['ACTIVE']; ?></div>
                        <div class="width60"><input id="dialog_object_add_active" class="checkbox" type="checkbox" /></div>
                </div>
                <div class="row2">
                        <div class="width40"><? echo $la['NAME']; ?></div>
                        <div class="width60"><input id="dialog_object_add_name" class="inputbox" type="text" value="" maxlength="25"></div>
                </div>
                <div class="row2">
                        <div class="width40"><? echo $la['IMEI']; ?></div>
                        <div class="width60"><input id="dialog_object_add_imei" class="inputbox" type="text" maxlength="15"></div>
                </div>
                <div class="row2">
                    <div class="width40"><? echo $la['BOART_IMEI']; ?></div>
                    <div class="width60"><input id="dialog_object_add_board_imei" class="inputbox" type="text" maxlength="15"></div>
                </div>
                <div class="row2">
                        <div class="width40"><? echo $la['TRANSPORT_MODEL']; ?></div>
                        <div class="width60"><input id="dialog_object_add_model" class="inputbox" type="text" value="" maxlength="30"></div>
                </div>
                <div class="row2">
                        <div class="width40"><? echo $la['VIN']; ?></div>
                        <div class="width60"><input id="dialog_object_add_vin" class="inputbox" type="text" maxlength="20"></div>
                </div>
                <div class="row2">
                        <div class="width40"><? echo $la['PLATE_NUMBER']; ?></div>
                        <div class="width60"><input id="dialog_object_add_plate_number" class="inputbox" type="text" maxlength="15"></div>
                </div>
                <div class="row2">
                        <div class="width40"><? echo $la['GPS_DEVICE']; ?></div>
                        <div class="width60"><select class="width100" id="dialog_object_add_device"></select></div>
                </div>
                <div class="row2">
                        <div class="width40"><? echo $la['SIM_CARD_NUMBER']; ?></div>
                        <div class="width60"><input id="dialog_object_add_sim_number" class="inputbox" type="text" value="" maxlength="30"></div>
                </div>
                <div class="row2">
                        <div class="width40"><? echo $la['MANAGER']; ?></div>
                        <div class="width60"><select class="width100" id="dialog_object_add_manager_id"></select></div>
                </div>
                <div class="row2">
                        <div class="width40"><? echo $la['INSTALLATION']; echo $la['DATE']; ?></div>
                        <div class="width60"><input class="inputbox-calendar inputbox width100" id="dialog_object_add_object_install_date"/></div>
                </div>
                <div class="row2">
                        <div class="width40"><? echo $la['EXPIRE_ON']; ?></div>
                        <div class="width10"><input id="dialog_object_add_object_expire" class="checkbox" type="checkbox" onChange="objectAddCheck();"/></div>
                        <div class="width50"><input class="inputbox-calendar inputbox width100" id="dialog_object_add_object_expire_dt"/></div>
                </div>                
                <div class="row2">
                        <div class="width100">
                                <select id="dialog_object_add_users" multiple="multiple" class="width100"></select>
                        </div>
                </div>	
        </div>
        
        <center>
                <input class="button icon-new icon" type="button" onclick="objectAdd('add');" value="<? echo $la['ADD']; ?>" />&nbsp;
                <input class="button icon-close icon" type="button" onclick="objectAdd('cancel');" value="<? echo $la['CANCEL']; ?>" />
        </center>
</div>

<div id="dialog_object_edit" title="<? echo $la['EDIT_OBJECT']; ?>">
        <div class="row">
                <div class="row2">
                        <div class="width40"><? echo $la['ACTIVE']; ?></div>
                        <div class="width60"><input id="dialog_object_edit_active" class="checkbox" type="checkbox" /></div>
                </div>
                <div class="row2">
                        <div class="width40"><? echo $la['NAME']; ?></div>
                        <div class="width60"><input id="dialog_object_edit_name" class="inputbox" type="text" maxlength="25" /></div>
                </div>
                <div class="row2">
                        <div class="width40"><? echo $la['IMEI']; ?></div>
                        <div class="width60"><input id="dialog_object_edit_imei" class="inputbox" type="text" maxlength="15" /></div>
                </div>
                <div class="row2">
                    <div class="width40"><? echo $la['BOART_IMEI']; ?></div>
                    <div class="width60"><input id="dialog_object_edit_board_imei" class="inputbox" type="text" maxlength="15"></div>
                </div>
                <div class="row2">
                        <div class="width40"><? echo $la['TRANSPORT_MODEL']; ?></div>
                        <div class="width60"><input id="dialog_object_edit_model" class="inputbox" type="text" value="" maxlength="30"></div>
                </div>
                <div class="row2">
                        <div class="width40"><? echo $la['VIN']; ?></div>
                        <div class="width60"><input id="dialog_object_edit_vin" class="inputbox" type="text" maxlength="20"></div>
                </div>
                <div class="row2">
                        <div class="width40"><? echo $la['PLATE_NUMBER']; ?></div>
                        <div class="width60"><input id="dialog_object_edit_plate_number" class="inputbox" type="text" maxlength="20"></div>
                </div>
                <div class="row2">
                        <div class="width40"><? echo $la['GPS_DEVICE']; ?></div>
                        <div class="width60"><select class="width100" id="dialog_object_edit_device" ></select></div>
                </div>
                <!-- <div class="row2" id="show_dispensor_edit_device_tracker" style="display: none;">
                        <div class="width40"><? echo $la['SELECT_TRACKER']; ?></div>
                        <div class="width60"><select class="width100" id="dialog_object_edit_select_tracker"></select></div>
                </div> -->
                <div class="row2">
                        <div class="width40"><? echo $la['SIM_CARD_NUMBER']; ?></div>
                        <div class="width60"><input id="dialog_object_edit_sim_number" class="inputbox" type="text" value="" maxlength="30"></div>
                </div>
                <div class="row2">
                        <div class="width40"><? echo $la['MANAGER']; ?></div>
                        <div class="width60"><select class="width100" id="dialog_object_edit_manager_id"></select></div>
                </div>
                <div class="row2">
                        <div class="width40"><? echo $la['INSTALLATION']; echo $la['DATE']; ?></div>
                        <div class="width60"><input class="inputbox-calendar inputbox width100" id="dialog_object_add_object_install_date"/></div>
                </div>
                <div class="row2">
                        <div class="width40"><? echo $la['EXPIRE_ON']; ?></div>
                        <div class="width10"><input id="dialog_object_edit_object_expire" class="checkbox" type="checkbox" onChange="objectEditCheck();"/></div>
                        <div class="width50"><input class="inputbox-calendar inputbox width100" id="dialog_object_edit_object_expire_dt"/></div>
                </div>
                <div class="row2">
                        <div class="width100">
                                <select id="dialog_object_edit_users" multiple="multiple" class="width100"></select>
                        </div>
                </div>	
        </div>
        
        <center>
                <input class="button icon-save icon" type="button" onclick="objectEdit('save');" value="<? echo $la['SAVE']; ?>" />&nbsp;
                <input class="button icon-close icon" type="button" onclick="objectEdit('cancel');" value="<? echo $la['CANCEL']; ?>" />
        </center>
</div>

<div id="dialog_object_edit_dispenser_calibration_model" title="<? echo $la['CALIBRATION']; ?>">
        <div class="row">
            <div class="row2">
                    <div class="width40"><? echo $la['SENSOR_PARAMETER']; ?></div>
                    <div class="width20"> 
                        <select class="width100" id="object_dispenser_calibration_value_param" onchange ="check_param_dispenservalue(this.value);">
                            <option value='fuelhz1'>Fuel 1</option>
                            <option value='fuelhz2'>Fuel 2</option>
                            <option value='fuelhz3'>Fuel 3</option>
                        </select>
                    </div>
                    <div class="width40"><input class="button" type="button" onclick="get_object_current_freqhz();" value="<? echo $la['CURRENT_HZ']; ?>" ></div>
            </div> <br>
            <div class="row2">
                <div class="width25"><input type="text"  class="inputbox" id="object_dispenser_calibration_value_freqhz" placeholder="Freq HZ"></div>
                <div class="width25"><input type="text"  class="inputbox" id="object_dispenser_calibration_value_level" placeholder="Level mm"></div>
                <div class="width25"><input type="text"  class="inputbox" id="object_dispenser_calibration_value_volum" placeholder="Volum L"></div>
                <div class="width25"><input type="button"  class="button" onclick="Object_dispenserSensorCalibrationAdd();" value="ADD"></div>
            </div><br>
            <div class="row2">
                <div id="settings_object_sensor_calibration_list">
                    <table id="settings_object_dispenser_sensor_calibration_list_grid"></table>
                </div>
            </div>
        </div>
        
        <center>
                <input class="button icon-save icon" type="button" onclick="save_objectdispenser_calibration('save');" value="<? echo $la['SAVE']; ?>" />&nbsp;
                <input class="button icon-close icon" type="button" onclick="save_objectdispenser_calibration('cancel');" value="<? echo $la['CANCEL']; ?>" />
        </center>
</div>