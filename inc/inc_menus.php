<ul id="side_panel_objects_action_menu" class="menu">
        <li>
                <a class="icon-time first-item" href="#"><? echo $la['SHOW_HISTORY'];?></a>
                <ul class="child">
                        <li><a class="first-item" href="#" tag="shlh"><? echo $la['LAST_HOUR'];?></a></li>
                        <li><a href="#" tag="sht"><? echo $la['TODAY'];?></a></li>
                        <li><a href="#" tag="shy"><? echo $la['YESTERDAY'];?></a></li>
                        <li><a href="#" tag="shb2"><? echo $la['BEFORE_2_DAYS'];?></a></li>
                        <li><a href="#" tag="shb3"><? echo $la['BEFORE_3_DAYS'];?></a></li>
                        <li><a href="#" tag="shtw"><? echo $la['THIS_WEEK'];?></a></li>
                        <li><a href="#" tag="shlw"><? echo $la['LAST_WEEK'];?></a></li>
                        <li><a href="#" tag="shtm"><? echo $la['THIS_MONTH'];?></a></li>
                        <li><a href="#" tag="shlm"><? echo $la['LAST_MONTH'];?></a></li>
                </ul>
        </li>
        <li><a class="icon-follow" href="#" tag="follow"><? echo $la['FOLLOW'];?></a></li>
        <li><a class="icon-follow" href="#" tag="follow_new"><? echo $la['FOLLOW_NEW_WINDOW'];?></a></li>
        <li><a class="icon-street" href="#" tag="street_view_new"><? echo $la['STREET_VIEW_NEW_WINDOW'];?></a></li>
        <li><a class="icon-create" href="#" tag="cmd"><? echo $la['SEND_COMMAND'];?></a></li>
	<li><a class="icon-edit" href="#" tag="edit"><? echo $la['EDIT'];?></a></li>
	<?php if ($_SESSION["user_id"] != "" || $_SESSION["manager_id"] != "") { ?>
	<li><a class="icon-video" href="#" tag="video_new"><?php echo $la['VIDEO_NEW_WINDOW']; ?></a></li>	
	<?php } ?>
</ul>

<ul id="report_action_menu" class="menu">
        <li><a class="icon-arrow-right first-item" href="#" tag="grlh"><? echo $la['LAST_HOUR'];?></a></li>
        <li><a class="icon-arrow-right" href="#" tag="grt"><? echo $la['TODAY'];?></a></li>
        <li><a class="icon-arrow-right" href="#" tag="gry"><? echo $la['YESTERDAY'];?></a></li>
        <li><a class="icon-arrow-right" href="#" tag="grb2"><? echo $la['BEFORE_2_DAYS'];?></a></li>
        <li><a class="icon-arrow-right" href="#" tag="grb3"><? echo $la['BEFORE_3_DAYS'];?></a></li>
        <li><a class="icon-arrow-right" href="#" tag="grtw"><? echo $la['THIS_WEEK'];?></a></li>
        <li><a class="icon-arrow-right" href="#" tag="grlw"><? echo $la['LAST_WEEK'];?></a></li>
        <li><a class="icon-arrow-right" href="#" tag="grtm"><? echo $la['THIS_MONTH'];?></a></li>
        <li><a class="icon-arrow-right" href="#" tag="grlm"><? echo $la['LAST_MONTH'];?></a></li>
</ul>

<ul id="side_panel_history_import_export_action_menu" class="menu">
        <li><a class="icon-save first-item" href="#" onclick="historySaveAsRoute();"><? echo $la['SAVE_AS_ROUTE'];?></a></li>
        <li><a class="icon-import" href="#" onclick="historyLoadGSR();"><? echo $la['LOAD_GSR'];?></a></li>
        <li><a class="icon-export" href="#" onclick="historyExportGSR();"><? echo $la['EXPORT_GSR'];?></a></li>
        <li><a class="icon-export" href="#" onclick="historyExportKML();"><? echo $la['EXPORT_KML'];?></a></li>
        <li><a class="icon-export" href="#" onclick="historyExportGPX();"><? echo $la['EXPORT_GPX'];?></a></li>
        <li><a class="icon-export" href="#" onclick="historyExportCSV();"><? echo $la['EXPORT_CSV'];?></a></li>
</ul>

<ul id="settings_main_object_list_grid_action_menu" class="menu">
        <?php if(checkSettingsPrivileges('clr_history','delete')==true){ ?>
        <li><a class="icon-erase first-item" href="#" onclick="settingsObjectClearHistorySelected();"><? echo $la['CLEAR_HISTORY'];?></a></li>
        <?php } if(checkSettingsPrivileges('object','delete')==true){ ?>
                <li><a class="icon-remove3" href="#" onclick="settingsObjectDeleteSelected();"><? echo $la['DELETE'];?></a></li>
        <?php }?>
</ul>

<ul id="settings_object_service_list_grid_action_menu" class="menu">
        <li><a class="icon-import first-item" href="#" onclick="settingsObjectServiceImport();"><? echo $la['IMPORT'];?></a></li>
        <li><a class="icon-export" href="#" onclick="settingsObjectServiceExport();"><? echo $la['EXPORT'];?></a></li>
        <li><a class="icon-remove3" href="#" onclick="settingsObjectServiceDeleteSelected();"><? echo $la['DELETE'];?></a></li>
</ul>

<ul id="settings_object_sensor_list_grid_action_menu" class="menu">
        <li><a class="icon-import first-item" href="#" onclick="settingsObjectSensorImport();"><? echo $la['IMPORT'];?></a></li>
        <li><a class="icon-export" href="#" onclick="settingsObjectSensorExport();"><? echo $la['EXPORT'];?></a></li>
        <li><a class="icon-remove3" href="#" onclick="settingsObjectSensorDeleteSelected();"><? echo $la['DELETE'];?></a></li>
</ul>

<ul id="settings_object_custom_fields_list_grid_action_menu" class="menu">
        <li><a class="icon-import first-item" href="#" onclick="settingsObjectCustomFieldImport();"><? echo $la['IMPORT'];?></a></li>
        <li><a class="icon-export" href="#" onclick="settingsObjectCustomFieldExport();"><? echo $la['EXPORT'];?></a></li>
        <li><a class="icon-remove3" href="#" onclick="settingsObjectCustomFieldDeleteSelected();"><? echo $la['DELETE'];?></a></li>
</ul>

<ul id="settings_main_object_group_list_grid_action_menu" class="menu">
        <li><a class="icon-import first-item" href="#" onclick="settingsObjectGroupImport();"><? echo $la['IMPORT'];?></a></li>
        <li><a class="icon-export" href="#" onclick="settingsObjectGroupExport();"><? echo $la['EXPORT'];?></a></li>
        <?php if(checkSettingsPrivileges('group','delete')==true){ ?>
                <li><a class="icon-remove3" href="#" onclick="settingsObjectGroupDeleteSelected();"><? echo $la['DELETE'];?></a></li>
        <?php }?>
</ul>

<ul id="settings_main_object_driver_list_grid_action_menu" class="menu">
        <li><a class="icon-import first-item" href="#" onclick="settingsObjectDriverImport();"><? echo $la['IMPORT'];?></a></li>
        <li><a class="icon-export" href="#" onclick="settingsObjectDriverExport();"><? echo $la['EXPORT'];?></a></li>
        <li><a class="icon-remove3" href="#" onclick="settingsObjectDriverDeleteSelected();"><? echo $la['DELETE'];?></a></li>
</ul>

<ul id="settings_main_object_passenger_list_grid_action_menu" class="menu">
        <li><a class="icon-import first-item" href="#" onclick="settingsObjectPassengerImport();"><? echo $la['IMPORT'];?></a></li>
        <li><a class="icon-export" href="#" onclick="settingsObjectPassengerExport();"><? echo $la['EXPORT'];?></a></li>
        <li><a class="icon-remove3" href="#" onclick="settingsObjectPassengerDeleteSelected();"><? echo $la['DELETE'];?></a></li>
</ul>

<ul id="settings_main_object_trailer_list_grid_action_menu" class="menu">
        <li><a class="icon-import first-item" href="#" onclick="settingsObjectTrailerImport();"><? echo $la['IMPORT'];?></a></li>
        <li><a class="icon-export" href="#" onclick="settingsObjectTrailerExport();"><? echo $la['EXPORT'];?></a></li>
        <li><a class="icon-remove3" href="#" onclick="settingsObjectTrailerDeleteSelected();"><? echo $la['DELETE'];?></a></li>
</ul>

<ul id="settings_main_events_event_list_grid_action_menu" class="menu">
        <?php if(checkSettingsPrivileges('events','delete')==true){ ?>
                <li><a class="icon-remove3 first-item" href="#" onclick="settingsEventDeleteSelected();"><? echo $la['DELETE'];?></a></li>
        <?php } ?>
</ul>

<ul id="settings_main_templates_template_list_grid_action_menu" class="menu">
        <li><a class="icon-import first-item" href="#" onclick="settingsTemplateImport();"><? echo $la['IMPORT'];?></a></li>
        <li><a class="icon-export" href="#" onclick="settingsTemplateExport();"><? echo $la['EXPORT'];?></a></li>
        <li><a class="icon-remove3" href="#" onclick="settingsTemplateDeleteSelected();"><? echo $la['DELETE'];?></a></li>
</ul>

<ul id="settings_main_subaccount_list_grid_action_menu" class="menu">
        <?php if(checkSettingsPrivileges('subaccount','delete')==true){ ?>
                <li><a class="icon-remove3 first-item" href="#" onclick="settingsSubaccountDeleteSelected();"><? echo $la['DELETE'];?></a></li>
        <?php } ?>
</ul>

<ul id="places_group_list_grid_action_menu" class="menu">
        <li><a class="icon-import first-item" href="#" onclick="placesGroupImport();"><? echo $la['IMPORT'];?></a></li>
        <li><a class="icon-export" href="#" onclick="placesGroupExport();"><? echo $la['EXPORT'];?></a></li>
        <li><a class="icon-remove3" href="#" onclick="placesGroupDeleteSelected();"><? echo $la['DELETE'];?></a></li>
</ul>

<ul id="bottom_panel_msg_list_grid_action_menu" class="menu">
        <li><a class="icon-remove3 first-item" href="#" onclick="historyRouteMsgDeleteSelected();"><? echo $la['DELETE'];?></a></li>
</ul>

<ul id="cmd_schedule_list_grid_action_menu" class="menu">
        <li><a class="icon-remove3 first-item" href="#" onclick="cmdScheduleDeleteSelected();"><? echo $la['DELETE'];?></a></li>
</ul>

<ul id="cmd_template_list_grid_action_menu" class="menu">
        <li><a class="icon-import first-item" href="#" onclick="cmdTemplateImport();"><? echo $la['IMPORT'];?></a></li>
        <li><a class="icon-export" href="#" onclick="cmdTemplateExport();"><? echo $la['EXPORT'];?></a></li>
        <li><a class="icon-remove3" href="#" onclick="cmdTemplateDeleteSelected();"><? echo $la['DELETE'];?></a></li>
</ul>

<ul id="cmd_status_list_grid_action_menu" class="menu">
        <li><a class="icon-remove3 first-item" href="#" onclick="cmdExecDeleteSelected();"><? echo $la['DELETE'];?></a></li>
</ul>

<ul id="report_list_grid_action_menu" class="menu">
        <li><a class="icon-remove3 first-item" href="#" onclick="reportsDeleteSelected();"><? echo $la['DELETE'];?></a></li>
</ul>

<ul id="reports_generated_list_grid_action_menu" class="menu">
        <li><a class="icon-remove3 first-item" href="#" onclick="reportsGeneratedDeleteSelected();"><? echo $la['DELETE'];?></a></li>
</ul>

<!-- 
Updated by vetrivel.NR
 -->
 
 
<ul id="settings_object_working_hour_list_grid_action_menu" class="menu">
		<!-- 
        <li><a class="icon-import first-item" href="#" onclick="settingsObjectCustomFieldImport();"><? echo $la['IMPORT'];?></a></li>
        <li><a class="icon-export" href="#" onclick="settingsObjectCustomFieldExport();"><? echo $la['EXPORT'];?></a></li>
 		-->
        <li><a class="icon-remove3" href="#" onclick="settingsObjectWorkingHourDeleteSelected();"><? echo $la['DELETE'];?></a></li>
</ul>
 
 
<ul id="settings_main_shift_allocation_list_grid_action_menu" class="menu">
<li><a class="icon-remove3" href="#" onclick="settingsShiftAllocationDeleteSelected();"><? echo $la['DELETE'];?></a></li>
</ul>
 
 
<ul id="report_list_grid_action_menu_amb" class="menu">
        <li><a class="icon-remove3 first-item" href="#" onclick="reportsDeleteSelectedAmb();"><? echo $la['DELETE'];?></a></li>
</ul>

<ul id="reports_generated_list_grid_action_menu_amb" class="menu">
        <li><a class="icon-remove3 first-item" href="#" onclick="reportsGeneratedDeleteSelectedAmb();"><? echo $la['DELETE'];?></a></li>
</ul>

<ul id="rilogbook_logbook_grid_action_menu" class="menu">
        <li><a class="icon-remove3 first-item" href="#" onclick="rilogbookDeleteSelected();"><? echo $la['DELETE'];?></a></li>
</ul>

<ul id="dtc_list_grid_action_menu" class="menu">
        <li><a class="icon-remove3 first-item" href="#" onclick="dtcDeleteSelected();"><? echo $la['DELETE'];?></a></li>
</ul>

<ul id="image_gallery_list_grid_action_menu" class="menu">
        <li><a class="icon-remove3 first-item" href="#" onclick="imgDeleteSelected();"><? echo $la['DELETE'];?></a></li>
</ul>
