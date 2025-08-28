<div id="dialog_custom_report_fullview" class="opensettings" style="display: none;padding-top: 55px;" title="<? echo $la['BOARDING']; ?>" style="padding:0px;">    

    <div id="boarding_main" class="fullview_mobvi">
        <div class="block fullviewheight width100">
            <div class="block width8">&nbsp;
            </div>
            <div class="block width17 boarfingfont_mobvi">
                <ul id="cmdmenu" class="changecmdmenu">
                    <li id="custome_report_fueldistancereport_tab"><a href="#custom_fueldistancereport" style="font-size: 11.5px;" class="hvr-sweep-to-bottom1  ">
                        <? echo $la['FUEL_DISTANCE_REPORT']; ?></a></li>
                </ul>
            </div>
            <div class="block width3">
                <div class="vl"></div>
            </div>
            <div class="block width70 overflow_mobvi" style="padding-left: 8px; height: -webkit-fill-available;width: 74%;">

                <div id="custom_fueldistancereport">
                    <div class="row2">
                        <div class="width15"><label class="width15">
                            <? echo $la['CHOOSE_EXCEL_FILE']; ?></label> 
                        </div>
                        <div class="width20">
                            <input id="dialog_custom_report_fueldistancereport" type="file">
                        </div>
                        <div class="width20">
                            <input class="button textboxextra_style" type="button" onclick="readEXCEL_file();" value="<? echo $la['GENERATE']; ?>" />
                        </div>
                    </div>
                    <br />

                    <!-- <div class="row">
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
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</div>