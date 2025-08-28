<div id="dialog_dispensor_fullview" class="opensettings" style="display: none;padding-top: 55px;" title="<? echo $la['DISPENSOR']; ?>">
    <div id="boarding_main" class="fullview_mobvi">
        <div class="block fullviewheight width100">
            <div class="block width8">&nbsp;
            </div>
            <div class="block width17 boarfingfont_mobvi">
                <ul id="cmdmenu" class="changecmdmenu">
                    <? if ($_SESSION["privileges_dispensor_livebooking"] == true){?>
                        <li id="boarding_allocate_tab"><a href="#boarding_allocate" style="font-size: 11.5px;" class="hvr-sweep-to-bottom1  ">
                            <? echo $la['LIVE_BOOKING']; ?></a>
                        </li>
                    <?  }?> 
                </ul>
            </div>
            <div class="block width3">
                <div class="vl"></div>
            </div>
            <div class="block width70 overflow_mobvi" style="padding-left: 8px; height: -webkit-fill-available;width: 74%;">
                <? if ($_SESSION["privileges_dispensor_livebooking"] == true){?>
                <div id="boarding_allocate">
                    <div class="row">
                        <div class="sub-account-block block width100">
                            <div class="container">
                                <div class="title-block">
                                    <? echo $la['DEPARTMENTLIST'];?>
                                </div>
                                <div class="width100">
                                    <div id="boarding_dept_list">
                                        <table id="Dispensor_fuel_booking_list_grid"></table>
                                        <div id="Dispensor_fuel_booking_list_grid_pager"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?  }?> 
            </div>
        </div>
    </div>
</div>
<div id="dialog_dispensor_online_booking" title="<? echo $la['NEW_LIVE_BOOKING'];?>">
    <div class="row">
        <div class="block width100">
            <div class="container">
                <div class="title-block">
                    <? echo $la['NEW_LIVE_BOOKING']; ?>
                </div>
                <div class="row2">
                    <div class="width40">
                        <? echo $la['LOCATION']; ?>*
                    </div>
                    <div class="width50"><input id="dis_live_booking_location" type="text" class="inputbox" ><input id="dis_live_booking_id" type="text" class="inputbox" hidden=""></div>
                    <div class="width10">
                        <input class="button icon-ambulance icon" type="button" onclick="openaddresspic();" style="min-width: 36px !important;background-position: center;background-size: cover;">
                        </div>
                </div>
                <div class="row2">
                    <div class="width40">
                        <? echo $la['LANDMARK']; ?>*
                    </div>
                    <div class="width60"><input id="dis_live_booking_landmark" type="text" class="inputbox" ></div>
                </div>
                <div class="row2">
                    <div class="width40">
                        <? echo $la['NAME']; ?>*
                    </div>
                    <div class="width60"><input id="dis_live_booking_name" type="text" class="inputbox" ></div>
                </div>
                <div class="row2">
                    <div class="width40">
                        <? echo $la['CONTACT_NO']; ?>*
                    </div>
                    <div class="width60"><input id="dis_live_booking_contactno" type="text" class="inputbox" ></div>
                </div>
                <div class="row2">
                    <div class="width40">
                        <? echo $la['BOOKING_TYPE']; ?>*
                    </div>
                    <div class="width60">
                        <select id="dis_live_booking_type"  class="inputbox" >
                            <option value=''>selecct</option>
                            <option value='preset'><? echo $la['PRESET']; ?></option>
                            <option value='open'><? echo $la['OPEN']; ?></option>
                        </select>
                    </div>
                </div>
                <div class="row2">
                    <div class="width40">
                        <? echo $la['QTY']; ?>
                    </div>
                    <div class="width60"><input id="dis_live_booking_qty" type="text" class="inputbox" ></div>
                </div>
                <div class="row2">
                    <div class="width40">
                        <? echo $la['PRICE_LITTER']; ?>
                    </div>
                    <div class="width60"><input id="dis_live_booking_price_litter" type="text" class="inputbox" ></div>
                </div>
                <div class="row2">
                    <div class="width40">
                        <? echo $la['NOTE']; ?>
                    </div>
                    <div class="width60"><input id="dis_live_booking_note" type="text" class="inputbox" ></div>
                </div>
            </div>
        </div>
    </div>
    </br>
    <center>
        <input class="button icon-save icon" id="dis_booking_button_save" type="button" onclick="add_dis_newbooking('save');" value="<? echo $la['SAVE']; ?>" >&nbsp;
        <input class="button icon-save icon" id="dis_booking_button_update" type="button" onclick="add_dis_newbooking('update');" value="<? echo $la['UPDATE']; ?>" >&nbsp;
        <input class="button icon-close icon" type="button" onclick="add_dis_newbooking('cancel');" value="<? echo $la['CANCEL']; ?>" >
    </center>
</div>
<div id="dialog_dispensor_allocate_driver_model" title="<? echo $la['NEW_LIVE_BOOKING'];?>">
    <div class="width100">
        <div id="boarding_dept_list">
            <table id="Dispensor_avilable_driver_list_grid"></table>
            <div id="Dispensor_avilable_driver_list_grid_pager"></div>
        </div>
    </div>
</div>