<div id="dialog_boarding_fullview" class="opensettings" style="display: none;padding-top: 55px;" title="<? echo $la['BOARDING']; ?>" style="padding:0px;">
  
    <div id="boarding_main" class="fullview_mobvi">
        <div class="block fullviewheight width100">
            <div class="block width8">&nbsp;
        </div>
            <div class="block width17 boarfingfont_mobvi">
                <ul id="cmdmenu" class="changecmdmenu">
                    <li id="boarding_allocate_tab"><a href="#boarding_allocate" style="font-size: 11.5px;" class="hvr-sweep-to-bottom1  ">
                            <? echo $la['ALLOCATE']; ?></a></li>
                    <li id="boarding_allocate_daily_tab"><a href="#boarding_allocate_daily" class="hvr-sweep-to-bottom  ">
                            <? echo $la['ALLOCATEDAILY']; ?></a></li>
                    <li id="boarding_hotspot_tab"><a href="#boarding_hotspot" class="hvr-sweep-to-bottom2  ">
                            <? echo $la['HOTSPOT']; ?></a></li>
                    <li id="boarding_holiday_tab"><a href="#boarding_holiday" class="hvr-sweep-to-bottom3  ">
                            <? echo $la['HOLIDAY']; ?></a></li>
                    <li id="boarding_deptsection_tab"><a href="#boarding_deptsection" class="hvr-sweep-to-bottom4  ">
                            <? echo $la['BDEPTSEC']; ?></a></li>
                    <li id="boarding_deptsection_tab1"><a href="#boarding_deptsection1" class="hvr-sweep-to-bottom5  ">
                            <? echo $la['BDEPTSEC1']; ?></a></li>
                    <li id="boarding_student_tab"><a href="#boarding_student" class="hvr-sweep-to-bottom6  ">
                            <? echo $la['PEOPLEMASTER']; ?></a></li>
                </ul>
            </div>
            <div class="block width3">
            <div class="vl"></div>
            </div>
            <div class="block width70 overflow_mobvi" style="padding-left: 8px; height: -webkit-fill-available;
    width: 74%;">
            <div id="boarding_deptsection">
            <div class="row">
                <div class="sub-account-block block width100">
                    <div class="container">
                        <div class="title-block">
                            <? echo $la['DEPARTMENT']; ?>
                        </div>
                        <div class="row2">
                            <div class="width20">
                                <? echo $la['DEPARTMENTNAME']; ?>
                            </div>
                            <div class="width30"><input id="dialog_boarding_deptnametxt" class="inputbox" type="text" value="" maxlength="50" /></div>
                            <div class="width40">
                                <center>
                                    <input class="button icon-save icon" type="button" onclick="boardingdepartment('save');" value="<? echo $la['SAVE']; ?>" />
                                    <input class="button icon-close icon" type="button" onclick="boardingdepartment('cancel');" value="<? echo $la['CLEAR']; ?>" />
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="sub-account-block block width100">
                    <div class="container">
                        <div class="title-block">
                            <? echo $la['DEPARTMENTLIST'];?>
                        </div>
                        <div class="width100">
                            <div id="boarding_dept_list">
                                <table id="boarding_dept_list_grid"></table>
                                <div id="boarding_dept_list_grid_pager"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="boarding_deptsection1">
            <div class="row">
                <div class="privileges-block block width100 ">
                    <div class="container">
                        <div class="title-block">
                            <? echo $la['SECTION']; ?>
                        </div>
                        <div class="row2">
                            <div class="width15">
                                <? echo $la['DEPARTMENTNAME']; ?>
                            </div>
                            <div class="width20">
                                <select class="width100" id="dialog_boarding_deptnameddl" onchange="onchange_boarding_deptnameddl()" /></select>
                            </div>
                            <div class="width10"></div>
                            <div class="width10">
                                <? echo $la['SECTIONNAME']; ?>
                            </div>
                            <div class="width20"><input id="dialog_boarding_sectiontxt" class="inputbox" type="text" value="" maxlength="20" /></div>
                        </div>
                        <div class="row2">
                            <div class="width100">&nbsp;</div>
                            <div class="width100">
                                <center>
                                    <input class="button icon-save icon" type="button" onclick="boardingsection('save');" value="<? echo $la['SAVE']; ?>" />
                                    <input class="button icon-close icon" type="button" onclick="boardingsection('cancel');" value="<? echo $la['CLEAR']; ?>" />
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="privileges-block block width100 ">
                    <div class="container">
                        <div class="title-block">
                            <? echo $la['SECTIONLIST'];?>
                        </div>
                        <div class="width100">
                            <div id="boarding_section_list">
                                <table id="boarding_section_list_grid"></table>
                                <div id="boarding_section_list_grid_pager"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="boarding_holiday">
            <div class="row">
                <div class="sub-holiday-block block width100">
                    <div class="container">
                        <div class="title-block">
                            <? echo $la['HOLIDAY']; ?>
                        </div>

                        <div class="row2">
                            <div class="width12">
                                <? echo $la['FROMDATEH']; ?>
                            </div>
                            <div class="width20">
                                <input id="dialog_boarding_holidaydate" readonly class="inputbox-calendar inputbox width100" type="text" maxlength="50" />
                            </div>
                            <div class="width5"></div>
                            <div class="width8">
                                <? echo $la['TODATEH']; ?>
                            </div>
                            <div class="width15"><input id="dialog_boarding_holidaydateto" readonly class="inputbox-calendar inputbox width100" type="text" maxlength="50" /></div>
                            <div class="width5"></div>
                            <div class="width8">
                                <? echo $la['REASON']; ?>
                            </div>
                            <div class="width20"><input id="dialog_boarding_holidayreason" class="inputbox" type="text" value="" maxlength="50" /></div>


                        </div>
                        <div class="row2">
                            <br>
                            <div class="width100">
                                <center>
                                    <input class="button icon-save icon" type="button" onclick="boardingholiday('save');" value="<? echo $la['SAVE']; ?>" />
                                    <input class="button icon-close icon" type="button" onclick="boardingholiday('cancel');" value="<? echo $la['CLEAR']; ?>" />
                                </center>
                            </div>
                        </div>
                        <div class="title-block">
                            <? echo $la['SEARCH']; ?>
                        </div>
                        <div class="row2">
                        </div>
                        <div class="row2">
                            <div class="width12">
                                <? echo $la['FROMDATEH']; ?>
                            </div>
                            <div class="width20"><input id="dialog_boarding_holidayfromdate" readonly class="inputbox-calendar inputbox width100" type="text" maxlength="50" /></div>
                            <div class="width5"></div>
                            <div class="width8">
                                <? echo $la['TODATEH']; ?>
                            </div>
                            <div class="width20"><input id="dialog_boarding_holidaytodate" readonly class="inputbox-calendar inputbox width100" type="text" value="" maxlength="50" /></div>
                            <div class="width1"></div>
                            <div class="width12">
                                <input class="button icon-search icon" type="button" style="padding-left: 24px !important;" onclick="boardingholiday('search');" value="<? echo $la['SEARCH']; ?>" />
                            </div>
                        </div>


                    </div>
                </div>
            </div>


            <div class="row">
                <div class="sub-account-block block width100">
                    <div class="container">
                        <div class="title-block">
                            <? echo $la['HOLIDAYLIST'];?>
                        </div>
                        <div class="width100">
                            <div id="boarding_holiday_list">
                                <table id="boarding_holiday_list_grid"></table>
                                <div id="boarding_holiday_list_grid_pager"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="boarding_hotspot">

            <div class="row">
                <div class="sub-Hotspot-block block width100">
                    <div class="container">
                        <div class="title-block">
                            <? echo $la['HOTSPOTLIST'];?>
                        </div>
                        <div class="width100">
                            <div id="boarding_hotspot_list">
                                <table id="boarding_hotspot_list_grid"></table>
                                <div id="boarding_hotspot_list_grid_pager"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="boarding_student">
            <div class="title-block">
                <? echo $la['SEARCH']; ?>
            </div>
            <div class="row2">
                <div class="width15">
                    <? echo $la['DEPARTMENTNAME']; ?>
                </div>
                <div class="width20">
                    <select class="width100" id="dialog_studentadd_deptnameddlsearch" onchange="boardingsectionchangebydept()" /></select>
                </div>
                <div class="width1"></div>
                <div class="width15">
                    <? echo $la['SECTIONNAME']; ?>
                </div>
                <div class="width20">
                    <select class="width100" id="dialog_studentadd_sectionddlsearch" /></select>
                </div>
                <div class="width1"></div>

                <div class="width25"><input id="dialog_studentadd_inputsearch" class="inputbox" type="text" value="" placeholder="<? echo $la['TYPESEARCH']; ?>" /></div>

            </div>
            <br />
            <center>
                <input class="button icon-search icon" type="button" style="padding-left: 24px !important" onclick="boeardingstudent('search');" value="<? echo $la['SEARCH']; ?>" />
                <input type="file" id="employeelist_fileUpload" />
                <input type="button" id="employeelist_upload" value="Upload" onclick="Employeelist_Upload()" />
            </center>


            <div class="row">
                <div class="sub-hotspotadd-block block width100">
                    <div class="container">
                        <div class="title-block">
                            <? echo $la['PEOPLEMASTER']; ?>
                        </div>
                        <div class="width100">
                            <div id="boarding_student_list">
                                <table id="boarding_student_list_grid"></table>
                                <div id="boarding_student_list_grid_pager"></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>



        </div>


        <div id="boarding_allocate">


            <div class="row2">
                <div class="width15"><label class="width15">
                        <? echo $la['SEARCH']; ?></label> </div>
                <div class="width20">
                    <input id="dialog_boarding_addobject_search" class="inputbox width100" type="text" value="" placeholder="<? echo $la['SEARCH']; ?>" />
                </div>
            </div>

            <!-- 
       <div class="title-block"><label  class="width15"><? echo $la['SEARCH']; ?></label> <input id="dialog_boarding_addobject_search" class="inputbox width20" type="text" value="" placeholder="<? echo $la['SEARCH']; ?>" /> </div>
      -->
            <div class="row2">

                <div class="width15">
                    <? echo $la['OBJECT_NAME']; ?>
                </div>
                <div class="width20">
                    <select class="width100" id="dialog_boarding_addobject" onchange="boeardingallocate('search')" /></select>
                </div>
                <div class="width1"></div>

                <!-- 
                     
                    <div class="width15c"><? echo $la['HOTSPOTNAME']; ?></div>
                    <div class="width20">
                          <select class="width100" id="dialog_allocate_hotspotddlsearch"   /></select>
                    </div>
                    <div class="width1"></div>
                  
                     <div class="width1"></div>
                
                    <div class="width25"><input id="dialog_allocate_inputsearch" class="inputbox" type="text" value="" placeholder="<? echo $la['TYPESEARCH']; ?>" /></div>
    			-->
            </div>
            <br />
            <!-- 
           <center>
           <input class="button icon-search icon" type="button" onclick="boeardingallocate('search');" value="<? echo $la['SEARCH']; ?>" />              
           </center>
              -->

            <div class="row">
                <div class="sub-hotspotadd-block block width100">
                    <div class="container">
                        <div class="title-block">
                            <? echo $la['ALLOCATEDLIST']; ?>
                        </div>
                        <div class="width100">
                            <div id="boarding_allocate_list">
                                <table id="boarding_allocate_list_grid"></table>
                                <div id="boarding_allocate_list_grid_pager"></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div id="boarding_allocate_daily">

            <div class="row2">
                <div class="width15"><label class="width15">
                        <? echo $la['SEARCH']; ?></label> </div>
                <div class="width20">
                    <input id="dialog_boarding_addobject_searchdaily" class="inputbox width100" type="text" value="" placeholder="<? echo $la['SEARCH']; ?>" />
                </div>
            </div>
            <!-- 
       <div class="title-block"><label  class="width15"><? echo $la['SEARCH']; ?></label> <input id="dialog_boarding_addobject_searchdaily" class="inputbox width20" type="text" value="" placeholder="<? echo $la['SEARCH']; ?>" /> </div>
        -->
            <div class="row2">

                <div class="width15">
                    <? echo $la['OBJECT_NAME']; ?>
                </div>
                <div class="width20">
                    <select class="width100" id="dialog_boarding_addobjectdaily" onchange="boeardingallocatedaily('search')" /></select>
                </div>
                <div class="width1"></div>

                <!-- 
                     
                    <div class="width15c"><? echo $la['HOTSPOTNAME']; ?></div>
                    <div class="width20">
                          <select class="width100" id="dialog_allocate_hotspotddlsearch"   /></select>
                    </div>
                    <div class="width1"></div>
                  
                     <div class="width1"></div>
                
                    <div class="width25"><input id="dialog_allocate_inputsearch" class="inputbox" type="text" value="" placeholder="<? echo $la['TYPESEARCH']; ?>" /></div>
    			-->
            </div>
            <br />
            <!-- 
           <center>
           <input class="button icon-search icon" type="button" onclick="boeardingallocate('search');" value="<? echo $la['SEARCH']; ?>" />              
           </center>
              -->

            <div class="row">
                <div class="sub-hotspotadddaily-block block width100">
                    <div class="container">
                        <div class="title-block">
                            <? echo $la['ALLOCATEDLIST']; ?>
                        </div>
                        <div class="width100">
                            <div id="boarding_allocate_list_daily">
                                <table id="boarding_allocate_list_grid_daily"></table>
                                <div id="boarding_allocate_list_grid_pager_daily"></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
	</div>
	</div>
    </div>
</div>

<div id="dialog_boarding_trip_employee_list" title="<? echo $la['EMPLOYEE_LIST'];?>">
    <div class="row">
        <div class="container">
            <input type="text" id="dialog_boarding_trip_employee_tripid" hidden="">
            <select class="multi-select " id="dialog_boarding_trip_employee" style="height:186px;" name="dialog_boarding_trip_employee" data-plugin="multiselect" data-selectable-optgroup="true" multiple="multiple"></select></br>
            <center>
                <input class="button icon-save icon" type="button" onclick="updatetripemployee('save');" value="<? echo $la['SAVE']; ?>" />&nbsp;
                <input class="button icon-close icon" type="button" onclick="updatetripemployee('cancel');" value="<? echo $la['CANCEL']; ?>" />
            </center>
        </div>
    </div>
</div>

<div id="dialog_boarding_allocate_add" title="<? echo $la['ALLOCATE'];?>">
    <div class="row">
        <div class="block width50">
            <div class="container">
                <div class="title-block">
                    <? echo $la['ALLOCATE']; ?>
                </div>
                <div class="row2">
                    <div class="width40">
                        <? echo $la['TRIPNAME']; ?>
                    </div>
                    <div class="width60"><input id="dialog_boarding_tripname" type="text" class="inputbox" /></div>
                </div>

                <div class="row2">
                    <div class="width40">
                        <? echo $la['ACTIVE']; ?>
                    </div>
                    <div class="width60"><input id="dialog_boarding_allocate_active" type="checkbox" checked="checked" /></div>
                </div>
                <div class="row2">
                    <div class="width40">
                        <? echo $la['WEEK_DAYS']; ?>
                    </div>
                    <div class="width60">
                        <div style="text-align:center; margin-right: 5px;">
                            <? echo $la['DAY_SUNDAY']; ?><br /><input id="dialog_boarding_allocate_wd_sun" type="checkbox" checked="checked" /></div>
                        <div style="text-align:center; margin-right: 5px;">
                            <? echo $la['DAY_MONDAY']; ?><br /><input id="dialog_boarding_allocate_wd_mon" type="checkbox" checked="checked" /></div>
                        <div style="text-align:center; margin-right: 5px;">
                            <? echo $la['DAY_TUESDAY']; ?><br /><input id="dialog_boarding_allocate_wd_tue" type="checkbox" checked="checked" /></div>
                        <div style="text-align:center; margin-right: 5px;">
                            <? echo $la['DAY_WEDNESDAY']; ?><br /><input id="dialog_boarding_allocate_wd_wed" type="checkbox" checked="checked" /></div>
                        <div style="text-align:center; margin-right: 5px;">
                            <? echo $la['DAY_THURSDAY']; ?><br /><input id="dialog_boarding_allocate_wd_thu" type="checkbox" checked="checked" /></div>
                        <div style="text-align:center; margin-right: 5px;">
                            <? echo $la['DAY_FRIDAY']; ?><br /><input id="dialog_boarding_allocate_wd_fri" type="checkbox" checked="checked" /></div>
                        <div style="text-align:center; margin-right: 5px;">
                            <? echo $la['DAY_SATURDAY']; ?><br /><input id="dialog_boarding_allocate_wd_sat" type="checkbox" checked="checked" /></div>
                    </div>
                </div>
                <div class="row2">
                    <div class="width40">
                        <? echo $la['DONTSENDINHOLIDAY']; ?>
                    </div>
                    <div class="width60"><input id="dialog_boarding_allocate_activeholiday" type="checkbox" checked="checked" /></div>
                </div>
            </div>
        </div>
        <div class="block width50">
            <div class="container last">
                <div class="title-block">
                    <? echo $la['NOTIFICATIONS']; ?>
                </div>
                <div class="row2">
                    <div class="width50">
                        <? echo $la['SYSTEM_MESSAGE']; ?>
                    </div>
                    <div class="width50">
                        <input id="dialog_boarding_allocate_notify_system" type="checkbox" class="checkbox" />
                        <span class="float-right" style="margin-left: 5px;">
                            <? echo $la['AUTO_HIDE']; ?>
                            <input id="dialog_boarding_allocate_notify_system_hide" type="checkbox" class="checkbox" />
                        </span>
                    </div>
                </div>
                <div class="row2">
                    <div class="width50">
                        <? echo $la['SOUND_ALERT']; ?>
                    </div>
                    <div class="width50">
                        <div class="width15">
                            <input id="dialog_boarding_allocate_notify_system_soundchkbox" type="checkbox" class="checkbox" />
                        </div>
                        <select id="dialog_boarding_allocate_notify_system_sound" style="width:137px;" /></select>
                        <input class="button" type="button" onclick="boardingPlaySound();" style="min-width:40px; width:46px; margin-bottom: 0px;" value="<? echo $la['PLAY']; ?>" />
                    </div>
                </div>
                <div class="row2">
                    <div class="width50">
                        <? echo $la['MESSAGE_TO_EMAIL']; ?>
                    </div>
                    <div class="width50">
                        <div class="width15">
                            <input id="dialog_boarding_allocate_notify_email" type="checkbox" class="checkbox" />
                        </div>
                        <div class="width84">
                            <input id="dialog_boarding_allocate_notify_email_address" style="width: 187px;" class="inputbox" type="text" value="" maxlength="500" placeholder="<? echo $la['EMAIL_ADDRESS']; ?>" />
                        </div>
                    </div>
                </div>
                <div class="row2">
                    <div class="width50">
                        <? echo $la['SMS_TO_MOBILE_PHONE']; ?>
                    </div>
                    <div class="width50">
                        <div class="width15">
                            <input id="dialog_boarding_allocate_notify_sms" type="checkbox" class="checkbox" />
                        </div>
                        <div class="width84">
                            <input id="dialog_boarding_allocate_notify_sms_number" style="width: 187px;" class="inputbox" type="text" value="" maxlength="50" placeholder="<? echo $la['PHONE_NUMBER_WITH_CODE']; ?>" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="block width50">
            <div class="container">
                <div class="title-block">
                    <? echo $la['OBJECTHOTSPOT']; ?>
                </div>
                <div class="row2">
                    <div class="width40">
                        <? echo $la['OBJECT_NAME']; ?></br>

                    </div>
                    <div class="width60">
                        <label id="lbl_boarding_object_name"></label>
                    </div>
                </div>

                <div class="row2">
                    <div class="width40">
                        <? echo $la['HOTSPOTNAME']; ?></br>

                    </div>
                    <div class="width60">
                        <select class="width100" id="dialog_boarding_addhotspot" /></select>
                    </div>
                </div>

            </div>
        </div>
        <div class="block width50">
            <div class="container last">
                <div class="title-block">
                    <? echo $la['TIME_PERIOD']; ?>
                </div>

                <div class="row2">
                    <div class="width40">
                        <? echo $la['DAYS_INTERVAL']; ?>
                    </div>
                    <div class="width60">
                        <input id="txtdaysinterval" style="width:53px" class="inputbox" type="text" disabled="disabled" value="1" />
                    </div>
                </div>

                <div class="row2">
                    <div class="width40">
                        <? echo $la['TIME_FROM']; ?>
                    </div>
                    <div class="width60">

                        <select style="width:70px" id="dialog_allocate_hour_from">
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
                        <select style="width:70px" id="dialog_allocate_minute_from">
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
                        </select>&nbsp;
                        <select style="width:100px" id="dialog_allocate_hour_from_day" disabled="disabled">
                            <option value="Same">Same Day</option>
                            <!-- <option value="Different">Different Day</option> -->
                        </select>
                    </div>
                </div>
                <div class="row2">
                    <div class="width40">
                        <? echo $la['TIME_TO']; ?>
                    </div>
                    <div class="width60">

                        <select style="width:70px" id="dialog_allocate_hour_to">
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
                        <select style="width:70px" id="dialog_allocate_minute_to">
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
                        </select>&nbsp;

                        <select style="width:100px" id="dialog_allocate_hour_to_day">
                            <option value="Same">Same Day</option>
                            <option value="Different">Different Day</option>
                        </select>
                    </div>
                </div>


            </div>
        </div>

    </div>

    </br>
    <center>
        <input class="button icon-save icon" type="button" onclick="boeardingallocate('save');" value="<? echo $la['SAVE']; ?>" />&nbsp;
        <input class="button icon-close icon" type="button" onclick="boeardingallocate('cancel');" value="<? echo $la['CANCEL']; ?>" />
    </center>
</div>



<div id="dialog_boarding_allocate_adddaily" title="<? echo $la['ALLOCATE'];?>">
    <div class="row">
        <div class="block width50">
            <div class="container">
                <div class="title-block">
                    <? echo $la['ALLOCATE']; ?>
                </div>
                <div class="row2">
                    <div class="width40">
                        <? echo $la['TRIPNAME']; ?>
                    </div>
                    <div class="width60"><input id="dialog_boarding_tripnamedaily" type="text" class="inputbox" /></div>
                </div>

                <div class="row2">
                    <div class="width40">
                        <? echo $la['ACTIVE']; ?>
                    </div>
                    <div class="width60"><input id="dialog_boarding_allocate_activedaily" type="checkbox" checked="checked" /></div>
                </div>

                <div class="row2">
                    <div class="width40">
                        <? echo $la['DONTSENDINHOLIDAY']; ?>
                    </div>
                    <div class="width60"><input id="dialog_boarding_allocate_activeholidaydaily" type="checkbox" checked="checked" /></div>
                </div>
            </div>
        </div>
        <div class="block width50">
            <div class="container last">
                <div class="title-block">
                    <? echo $la['NOTIFICATIONS']; ?>
                </div>
                <div class="row2">
                    <div class="width50">
                        <? echo $la['SYSTEM_MESSAGE']; ?>
                    </div>
                    <div class="width50">
                        <input id="dialog_boarding_allocate_notify_systemdaily" type="checkbox" class="checkbox" />
                        <span class="float-right" style="margin-left: 5px;">
                            <? echo $la['AUTO_HIDE']; ?>
                            <input id="dialog_boarding_allocate_notify_system_hidedaily" type="checkbox" class="checkbox" />
                        </span>
                    </div>
                </div>
                <div class="row2">
                    <div class="width50">
                        <? echo $la['SOUND_ALERT']; ?>
                    </div>
                    <div class="width50">
                        <div class="width15">
                            <input id="dialog_boarding_allocate_notify_system_soundchkboxdaily" type="checkbox" class="checkbox" />
                        </div>
                        <select id="dialog_boarding_allocate_notify_system_sounddaily" style="width:123px;" /></select>
                        <input class="button" type="button" onclick="boardingPlaySounddaily();" style="min-width:40px; width:40px; margin-bottom: 0px;" value="<? echo $la['PLAY']; ?>" />
                    </div>
                </div>
                <div class="row2">
                    <div class="width50">
                        <? echo $la['MESSAGE_TO_EMAIL']; ?>
                    </div>
                    <div class="width50">
                        <div class="width15">
                            <input id="dialog_boarding_allocate_notify_emaildaily" type="checkbox" class="checkbox" />
                        </div>
                        <div class="width83">
                            <input id="dialog_boarding_allocate_notify_email_addressdaily" class="inputbox" type="text" value="" maxlength="500" placeholder="<? echo $la['EMAIL_ADDRESS']; ?>" />
                        </div>
                    </div>
                </div>
                <div class="row2">
                    <div class="width50">
                        <? echo $la['SMS_TO_MOBILE_PHONE']; ?>
                    </div>
                    <div class="width50">
                        <div class="width15">
                            <input id="dialog_boarding_allocate_notify_smsdaily" type="checkbox" class="checkbox" />
                        </div>
                        <div class="width83">
                            <input id="dialog_boarding_allocate_notify_sms_numberdaily" class="inputbox" type="text" value="" maxlength="50" placeholder="<? echo $la['PHONE_NUMBER_WITH_CODE']; ?>" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="block width50">
            <div class="container">
                <div class="title-block">
                    <? echo $la['OBJECTHOTSPOT']; ?>
                </div>
                <div class="row2">
                    <div class="width40">
                        <? echo $la['OBJECT_NAME']; ?></br>

                    </div>
                    <div class="width60">
                        <label id="lbl_boarding_object_namedaily"></label>
                    </div>
                </div>

                <div class="row2">
                    <div class="width40">
                        <? echo $la['HOTSPOTNAME']; ?></br>

                    </div>
                    <div class="width60">
                        <select class="width100" id="dialog_boarding_addhotspotdaily" /></select>
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

                        <input id="dialog_allocate_date_fromdaily" readonly="" class="inputbox-calendar inputbox width100 " type="text" maxlength="50" style="width:100px">
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

                        <input id="dialog_allocate_date_todaily" readonly="" class="inputbox-calendar inputbox width100 " type="text" maxlength="50" style="width:100px">
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


            </div>
        </div>
    </div>

    </br>
    <center>
        <input class="button icon-save icon" type="button" onclick="boeardingallocatedaily('save');" value="<? echo $la['SAVE']; ?>" />&nbsp;
        <input class="button icon-close icon" type="button" onclick="boeardingallocatedaily('cancel');" value="<? echo $la['CANCEL']; ?>" />
    </center>
</div>



<div id="boarding_hotspot_add" title="<? echo $la['HOTSPOTLIST']; ?>">

    <div class="row">
        <div class="sub-hotspotadd-block block width100">
            <div class="container">
                <div class="title-block">
                    <? echo $la['HOTSPOT']; ?>
                </div>
                <div class="row2">
                   <div class="width15">
                        <? echo $la['HOTSPOTNAME']; ?>
                    </div>
                    <div class="width20"><input id="dialog_hotspotadd_hotspotname" class="inputbox" type="text" /></div>

                    <div class="width1"></div>
                     <div class="width15">
                        <? echo $la['FREEZED_KM']; ?>
                    </div>
                    <div class="width15">
                        <input id="dialog_hotspotadd_freezekm" type="text"  class="inputbox" />
                    </div>
                    <div class="width1"></div>
                    <div class="width12">
                        <? echo $la['STARTING_POINT']; ?>
                    </div>
                    <div class="width5">
                        <input id="dialog_boarding_startingpoint" type="checkbox" name="vpoint[]" class="checkbox" />
                    </div>
                    <div class="width10">
                       <? echo $la['END_POINT']; ?>
                    </div>
                    <div class="width5">
                        <input id="dialog_boarding_endpoint" type="checkbox" name="vpoint[]" class="checkbox" />
                    </div>

                </div>

                <div class="row2">
                    <div class="width15">
                        <? echo $la['ZONE_NAME']; ?>
                    </div>
                    <div class="width20">
                        <select class="width100" id="dialog_hotspotadd_zonename" /></select>
                    </div>

                    <div class="width1"></div>
                    <div class="width15">
                        <? echo $la['ZONE_IN_OUT']; ?>
                    </div>
                    <div class="width15">
                        <select class="width100" id="dialog_hotspotadd_zonetype" />
                        <option>Select</option>
                        <option>Zone In</option>
                        <option>Zone Out</option>
                        </select>
                    </div>

                    <div class="width1"></div>
                    <div class="width15">
                        <? echo $la['TRAVELROUTE']; ?>
                    </div>
                    <div class="width15">
                        <select class="width100" id="dialog_hotspotadd_travelroute" />
                        <option>Select</option>
                        </select>
                    </div>                   


                </div>
                <div class="row2">
                    <div class="width15">
                        <? echo $la['MESSAGE']; ?>
                    </div>
                    <div class="width83"><input id="dialog_hotspotadd_message" class="inputbox" type="text" /></div>
                </div>



            </div>
        </div>
    </div>


    <br />
    <center>
        <input class="button  " type="button" onclick="hotspotin('add');" value="<? echo $la['ADD']; ?>" />&nbsp;&nbsp;&nbsp;
        <input class="button " type="button" onclick="hotspotin('clear');" value="<? echo $la['CLEAR']; ?>" />
    </center>

    <br />

    <div class="row">
        <div class="sub-hotspotadd-block block width100">
            <div class="container width100">
                <div class="ui-jqgrid-bdiv" style="height:230px;max-height:230px;overflow-y:scroll;">
                    <table id="boarding_hotspot_sub_table" border="0" class="ui-jqgrid-htable">

                        <tr style="background: rgb(222, 222, 222);">
                            <th class="ui-state-default ui-th-column ui-th-ltr" style="width:80px;">
                                <? echo $la['SINO']; ?>
                            </th>
                            <th class="ui-state-default ui-th-column ui-th-ltr" style="width: 150px;">
                                <? echo $la['ZONE_NAME']; ?>
                            </th>
                            <th class="ui-state-default ui-th-column ui-th-ltr" style="width: 150px;">
                                <? echo $la['ZONE_NAME']; ?>
                            </th>
                            <th class="ui-state-default ui-th-column ui-th-ltr" style="width:50px;"></th>
                            <th class="ui-state-default ui-th-column ui-th-ltr" style="width: 100px;">
                                <? echo $la['ZONE_IN_OUT']; ?>
                            </th>
                            <th class="ui-state-default ui-th-column ui-th-ltr" style="width: 280px;">
                                <? echo $la['MESSAGE']; ?>
                            </th>
                            <th class="ui-state-default ui-th-column ui-th-ltr" style="width: 100px;">Action</th>
                        </tr>


                    </table>
                </div>
            </div>
        </div>
    </div>

    <br />
    <center>
        <input class="button icon-save icon" type="button" onclick="hotspotin('save');" value="<? echo $la['SAVE']; ?>" />&nbsp;&nbsp;&nbsp;
        <input class="button icon-close icon" type="button" onclick="hotspotin('close');" value="<? echo $la['CANCEL']; ?>" />
    </center>

    <br />

</div>



<div id="boarding_student_add" title="<? echo $la['STUDENTMASTERA']; ?>">

    <div class="row">

        <div class="title-block">
            <? echo $la['STUDENTMASTERL']; ?>
        </div>
        <div class="row">

            <div class="sub-studentadd-block block width50">
                <div class="container">
                    <div class="row2">
                        <div class="width40">
                            <? echo $la['DEPARTMENTNAME']; ?>
                        </div>
                        <div class="width60">
                            <select class="width100" id="dialog_studentadd_deptnameddl" onchange="boardingsectionchangebydept()" /></select>
                        </div>
                    </div>
                    <div class="row2">
                        <div class="width40">
                            <? echo $la['ID_NUMBER']; ?>
                        </div>
                        <div class="width60"><input id="dialog_studentadd_id" class="inputbox" type="text" /></div>
                    </div>
                    <div class="row2">
                        <div class="width40">
                            <? echo $la['GENDER']; ?>
                        </div>
                        <div class="width60">
                            <select class="width100" id="dialog_student_genderddl" />
                            <option>Select</option>
                            <option>Male</option>
                            <option>Female</option>
                            </select>
                        </div>
                    </div>

                    <div class="row2">
                        <div class="width40">
                            <? echo $la['MORNING_HOTSPOT']; ?>
                        </div>
                        <div class="width60">
                            <select class="width100" id="dialog_studentadd_routename" /></select>
                        </div>
                    </div>

                    <div class="row2">
                        <div class="width40">
                            <? echo $la['MORNING_BOARDING']; ?>
                        </div>
                        <div class="width60">
                            <select class="width100" id="dialog_studentadd_morning_board" />
                            <option>Select</option>
                            </select>
                        </div>
                    </div>

                    <div class="row2">
                        <div class="width40">
                            <? echo $la['STATUS']; ?>
                        </div>
                        <div class="width60">
                            <select class="width100" id="dialog_student_ddlstatus" />
                            <option>Select</option>
                            <option>Active</option>
                            <option>Deactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="row2">
                        <div class="width40">
                            <? echo $la['OBJECT']; ?>
                        </div>
                        <div class="width60"><select id="dialog_studentadd_object_imei" class="inputbox" ><option value=''>Select</option></select></div>
                    </div>
                </div>

            </div>


            <div class="block width50">
                <div class="container">
                    <div class="row2">
                        <div class="width40">
                            <? echo $la['SECTIONNAME']; ?>
                        </div>
                        <div class="width60">
                            <select class="width100" id="dialog_studentadd_sectionddl" /></select>
                        </div>
                    </div>
                    <div class="row2">
                        <div class="width40">
                            <? echo $la['PEOPLENAME']; ?>
                        </div>
                        <div class="width60"><input id="dialog_studentadd_name" class="inputbox" type="text" /></div>
                    </div>
                    <div class="row2">
                        <div class="width40">
                            <? echo $la['DOB']; ?>
                        </div>
                        <div class="width60">
                            <input id="dialog_studentadd_dob" readonly class="inputbox-calendar inputbox width100" type="text" />
                        </div>
                    </div>

                    <div class="row2">
                        <div class="width40">
                            <? echo $la['EVENING_HOTSPOT']; ?>
                        </div>
                        <div class="width60">
                            <select class="width100" id="dialog_studentadd_routename_down" /></select>
                        </div>
                    </div>

                    <div class="row2">
                        <div class="width40">
                            <? echo $la['EVENING_DROP']; ?>
                        </div>
                        <div class="width60">
                            <select class="width100" id="dialog_studentadd_evening_drop" />
                            <option>Select</option></select>
                        </div>
                    </div>

                    <div class="row2">
                        <div class="width40">
                            <? echo $la['RFIDID']; ?>
                        </div>
                        <div class="width60"><input id="dialog_studentadd_rfidno" class="inputbox" type="text" /></div>
                    </div>                

                </div>
            </div>
        </div>


        <div class="title-block">
            <? echo $la['PARENT_INFO']; ?>
        </div>


        <div class="sub-studentadd-block block width50">
            <div class="container">

                <div class="row2">
                    <div class="width40">
                        <? echo $la['PARENT_NAME']; ?>
                    </div>
                    <div class="width60"><input id="dialog_studentadd_parentname" class="inputbox" type="text" /></div>
                </div>

                <div class="row2">
                    <div class="width40">
                        <? echo $la['MOBILENO']; ?>
                    </div>
                    <div class="width60"><input id="dialog_studentadd_phno" class="inputbox" type="text" /></div>
                </div>

                <div class="row2">
                    <div class="width40">
                        <? echo $la['EMAILID']; ?>
                    </div>
                    <div class="width60"><input id="dialog_studentadd_emailid" class="inputbox" type="text" /></div>
                </div>

            </div>
        </div>


        <div style="clear:both">
            <br>
        </div>


        <div class="row2">
            <div class="width100">
                <center>
                    <input class="button icon-save icon" type="button" onclick="boeardingstudent('save');" value="<? echo $la['SAVE']; ?>" />
                    <input class="button icon-close icon" type="button" onclick="boeardingstudent('cancel');" value="<? echo $la['CANCEL']; ?>" />
                </center>
            </div>

        </div>


    </div>

    <br />
    <br />
</div>