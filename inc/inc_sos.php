<!--  this page functions are developed on inc/fun_settings.shiftallocation.php . Developed by Nandhakumar-->

<div id="dialog_sos_fullview" class="opensettings" style="display: none;padding-top: 64px;" title="<? echo $la['SETTINGS']; ?>">
    <div id="settings_main" class="fullview_mobvi sosmobg">
        <div class="block fullviewheight width100">
            <div class="block width10">&nbsp;</div>
            <div class="block width85">
                <div class="container">
                    <div class="row2">
                        <div class="width70">
                            <button style="background: white;" onclick="emergencyalertSelectedit();">Don't Show Following alerts again</button>
                            <select onchange="selectEventaction(this.value);">
                                <option value=''>Select</option>
                                <option value='sos' selected>SOS</option>
                                <option value='fueldiscnt'>Fuel wire disconnect</option>
                                <option value='fuelstolen'>Fuel Siphon</option>
                                <option value='gpsantcut'>GPS antenna cut</option>
                                <option value='haccel'>Harsh acceleration</option>
                                <option value='hbrake'>Harsh braking</option>
                                <option value='aconidle'>A/C On when Engine Idle</option>
                                <option value='lowfuel'>Low Fuel</option>
                                <option value='overspeed'>Over Speed</option>
                                <option value='param'>Param</option>
                                <option value='pwrcut'>Power Cut</option>
                                <!-- <option value='zone_in'>Zone In</option>
                                <option value='zone_out' >Zone Out</option> -->
                            </select>
                        </div>
                    </div>
                </div>
        		<div id="settings_main_sosallert">
                    <div id="settings_main_sosallert_list">
                        <table id="settings_main_sosallert_list_grid"></table>
                        <div id="settings_main_sosallert_list_pager"></div>
                    </div>
                </div>
            </div>
            <div id="dialog_sos_edit_select_token" title="sos">
                 <div class="container">
                    <div class="row2">
                        <div class="width70">
                            <button style="background: white;" onclick="save_soscompletetoken();">Save</button>
                        </div>
                    </div>
                </div>
                <div id="settings_main_sosallert">
                    <div id="settings_main_sosallert_list">
                        <table id="settings_main_sosallert_token_grid"></table>
                        <div id="settings_main_sosallert_token_pager"></div>
                    </div>
                </div>
            </div>
		</div>
	</div>
</div>
 