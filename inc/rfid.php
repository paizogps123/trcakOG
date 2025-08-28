<div id="dialog_rfidtrip_fullview" class="opensettings" style="display: none;    padding-top: 55px;" title="<? echo $la['RFID']; ?>" style="padding:0px;">
    <div id="rfidtrip_main" class="fullview_mobvi">
        <div class="block fullviewheight width100">
            <div class="block width8">&nbsp;
        </div>
        <div class="block width17">
                <ul id="cmdmenu" class="changecmdmenu">
            <li id="rfidtrip_gpsclose_tab"><a href="#rfidtrip_gpsclose" class="hvr-sweep-to-bottom1">
                    <? echo $la['GPSRECEIPT']; ?></a></li>
            <li id="rfidtrip_gpsissue_tab"><a href="#rfidtrip_gpsissue" class="hvr-sweep-to-bottom  ">
                    <? echo $la['GPSISSUE']; ?></a></li>
            <li id="rfidtrip_driverrfid_tab"><a href="#rfidtrip_driverrfid" class="hvr-sweep-to-bottom2  ">
                    <? echo $la['DRIVERRFIDIDMASTER']; ?></a></li>
            <li id="rfidtrip_deptsection_tab"><a href="#rfidtrip_deptsection" class="hvr-sweep-to-bottom3  ">
                    <? echo $la['BDEPTSEC']; ?></a></li>
            <li id="rfidtrip_employee_tab"><a href="#rfidtrip_employee" class="hvr-sweep-to-bottom4  ">
                    <? echo $la['EMPLOYEEREG']; ?></a></li>

        </ul>
	</div>
    <div class="block width3">
    <div class="vl"></div>
    </div>
    <div class="block width70 overflow_mobvi" style="width: 74.9%;">
        <div id="rfidtrip_deptsection">

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
                            <div class="width30"><input id="dialog_rfidtrip_deptnametxt" class="inputbox" type="text" value="" maxlength="50" /></div>
                            <div class="width40">
                                <center>
                                    <input class="button icon-save icon" type="button" onclick="rfidtripdepartment('save');" value="<? echo $la['SAVE']; ?>" />
                                    <input class="button icon-close icon" type="button" onclick="rfidtripdepartment('cancel');" value="<? echo $la['CLEAR']; ?>" />
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

                        </div>
                    </div>
                </div>



            </div>

        </div>

        <div id="rfidtrip_driverrfid">

            <div class="row">
                <div class="sub-account-block block width100">
                    <div class="container">

                        <div class="row2">
                            <div class="width20">
                                <? echo $la['DRIVERRFIDID']; ?>
                            </div>
                            <div class="width30"><input id="dialog_rfidtrip_driverrfid" class="inputbox" type="text" value="" maxlength="50" /></div>
                            <div class="width40">
                                <center>
                                    <input class="button icon-save icon" type="button" onclick="rfidtripdriverrfid('save');" value="<? echo $la['SAVE']; ?>" />
                                    <input class="button icon-close icon" type="button" onclick="rfidtripdriverrfid('cancel');" value="<? echo $la['CLEAR']; ?>" />
                                </center>
                            </div>
                        </div>


                    </div>
                </div>


            </div>

            <div class="row">
                <div class="sub-driverrfid-block block width100">
                    <div class="container">
                        <div class="title-block">
                            <? echo $la['DRIVERRFIDIDLIST'];?>
                        </div>
                        <div class="width100">
                            <div id="rfidtrip_driverrfid_list">
                                <table id="rfidtrip_driverrfid_list_grid"></table>
                                <div id="rfidtrip_driverrfid_list_grid_pager"></div>
                            </div>
                        </div>
                    </div>
                </div>



            </div>

        </div>


        <div id="rfidtrip_employee">

            <div class="row">
                <div class="sub-Employee-block block width100">
                    <div class="container">
                        <div class="title-block">
                            <div class="row2">
                                <div class="width25">

                                    <? echo $la['EMPLOYEEREG']; ?>
                                </div>
                                <div class="width50">

                                    <input id="dialog_employee_search" class="inputbox" type="text" placeholder="Typer Your Search Data..." />
                                </div>


                                <div class="width20">
                                    &nbsp;&nbsp;&nbsp;
                                    <input class="button icon-search icon" type="button" style="padding-left: 18px !important;" onclick="rfidemployee('search');" value="<? echo $la['SEARCH']; ?>" />

                                </div>

                            </div>
                        </div>
                        <div class="width100">
                            <div id="rfidtrip_employee_list">
                                <table id="rfidtrip_employee_list_grid"></table>
                                <div id="rfidtrip_employee_list_grid_pager"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div id="rfidtrip_gpsclose">

            <div class="row">
                <div class="sub-gpsclose-block block width100">
                    <div class="container">
                        <div class="title-block">
                            <div class="row2">
                                <div class="width25">

                                    <? echo $la['GPSRECEIPT']; ?>
                                </div>
                                <div class="width50">

                                    <input id="dialog_gpsclose_search" class="inputbox" type="text" placeholder="Typer Your Search Data..." />
                                </div>


                                <div class="width20">
                                    &nbsp;&nbsp;&nbsp;
                                    <input class="button icon-search icon" type="button" style="padding-left:18px !important;" onclick="rfidgpsissue('search');" value="<? echo $la['SEARCH']; ?>" />

                                </div>

                            </div>
                        </div>
                        <div class="width100">
                            <div id="rfidtrip_gpsclose_list">
                                <table id="rfidtrip_gpsclose_list_grid"></table>
                                <div id="rfidtrip_gpsclose_list_grid_pager"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div id="rfidtrip_gpsissue">

            <div class="row">
                <div class="sub-gpsissue-block block width100">
                    <div class="container">
                        <div class="title-block">
                            <? echo $la['GPSISSUE']; ?>
                        </div>
                        <div class="row2">
                            <div class="width20">&nbsp;&nbsp;
                                <? echo $la['OBJECT_NAME']; ?>
                            </div>
                            <div class="width30">
                                <input id="dialog_rfidtrip_gpsi_objectnametxt" class="inputbox" type="text" value="" />
                            </div>


                            <div class="width20">&nbsp;&nbsp;
                                <? echo $la['DRIVERRFIDID']; ?>
                            </div>
                            <div class="width30"><input id="dialog_rfidtrip_gpsi_driverrfidtxt" class="inputbox" type="text" value="" /></div>

                        </div>
                        <div class="row2">
                            <div class="width20"></div>
                            <div class="width30">
                                <select class="width100" id="dialog_rfidtrip_gpsi_objectnameddl" multiple="multiple" style="height:150px;" /></select>
                            </div>

                            <div class="width20"></div>
                            <div class="width30">
                                <select class="width100" id="dialog_rfidtrip_gpsi_driverrfidddl" multiple="multiple" style="height:150px;" /></select>

                            </div>


                        </div>
                        <div class="row2">
                            <div class="width20">&nbsp;&nbsp;
                                <? echo $la['VEHICLENO']; ?>
                            </div>
                            <div class="width30"><input id="dialog_rfidtrip_gpsi_vehicleno" class="inputbox" type="text" value="" /></div>
                            <div class="width20">&nbsp;&nbsp;
                                <? echo $la['VENDOR']; ?>
                            </div>
                            <div class="width30"><input id="dialog_rfidtrip_gpsi_vendor" class="inputbox" type="text" value="" /></div>



                        </div>
                        <div class="row2">

                            <div class="width20">&nbsp;&nbsp;
                                <? echo $la['DRIVER']; ?>
                            </div>
                            <div class="width30"><input id="dialog_rfidtrip_gpsi_driver" class="inputbox" type="text" value="" /></div>

                            <div class="width20">&nbsp;&nbsp;
                                <? echo $la['DRIVERPH']; ?>
                            </div>
                            <div class="width30"><input id="dialog_rfidtrip_gpsi_driverph" class="inputbox" maxlength="10" type="text" value="" /></div>



                        </div>

                    </div>
                    <br />
                    <div class="row2">
                        <div class="width100">
                            <center>
                                <input class="button icon-save icon" type="button" onclick="rfidgpsissue('save');" value="<? echo $la['SAVE']; ?>" />
                                <input class="button icon-close icon" type="button" onclick="rfidgpsissue('cancel');" value="<? echo $la['CLEAR']; ?>" />
                            </center>
                        </div>
                    </div>



                </div>
            </div>


        </div>

	</div></div>
    </div>
</div>




<div id="rfidtrip_employee_add" title="<? echo $la['EMPLOYEEREGC']; ?>">

    <div class="row">
        <div class="sub-employeeadd-block block width100">
            <div class="container">

                <div class="row2">

                    <div class="width20">
                        <? echo $la['DEPARTMENTNAME']; ?>
                    </div>
                    <div class="width30">
                        <select class="width100" id="dialog_employee_deptnameddl" /></select>
                    </div>
                     <div class="width10">&nbsp;</div>
                    <div class="width10">
                        <? echo $la['VNAME']; ?>
                    </div>
                    <div class="width30"><input id="dialog_employeeadd_name" class="inputbox" type="text" /></div>


                </div>

                <div class="row2">

                    <div class="width20">
                        <? echo $la['RFIDID']; ?>
                    </div>
                    <div class="width30"><input id="dialog_employeeadd_rfidid" class="inputbox" type="text" /></div>

                    <div class="width10">&nbsp;</div>

                    <div class="width10">
                        <? echo $la['GENDER']; ?>
                    </div>
                    <div class="width30">
                        <select class="width100" id="dialog_employee_genderddl" />
                        <option>Select</option>
                        <option>Male</option>
                        <option>Female</option>
                        </select>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <br />
    <center>
        <input class="button icon-save icon" type="button" onclick="rfidemployee('save');" value="<? echo $la['SAVE']; ?>" />
        <input class="button icon-close icon" type="button" onclick="rfidemployee('cancel');" value="<? echo $la['CANCEL']; ?>" />

    </center>

    <br />

</div>

<div id="rfidtrip_gpsclose_close" title="<? echo $la['GPSRECEIPTC'];?>">
    <div class="row">
        <div class="width20">
            <? echo $la['REASON'];?>
        </div>
        <div class="width80">
            <input class="inputbox_gpsclose_reason width100" type='text' id='inputbox_gpsclose_reason' maxlength="100" />
        </div>
    </div>

    <center>
        <input class="button icon-save icon" type="button" onclick="rfidgpsissue('closefinal');" value="<? echo $la['YES']; ?>" />&nbsp;&nbsp;&nbsp;
        <input class="button icon-close icon" type="button" onclick="rfidgpsissue('NO')" value="<? echo $la['NO']; ?>" />
    </center>
</div>