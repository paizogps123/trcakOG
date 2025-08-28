<ul id="cpanel_user_list_grid_action_menu" class="menu">
        <li><a class="icon-tick first-item" href="#" onclick="userActivateSelected();"><? echo $la['ACTIVATE'];?></a></li>
        <li><a class="icon-close" href="#" onclick="userDeactivateSelected();"><? echo $la['DEACTIVATE'];?></a></li>
        <li><a class="icon-time" href="#" onclick="setExpirationSelected('open_users');"><? echo $la['SET_EXPIRATION'];?></a></li>
        <?php if(checkSettingsPrivileges('cpanel_user','delete')==true){?>
            <li><a class="icon-remove3" href="#" onclick="userDeleteSelected();"><? echo $la['DELETE'];?></a></li>
        <?php } ?>
</ul>

<ul id="cpanel_object_list_grid_action_menu" class="menu">
        <li><a class="icon-tick first-item" href="#" onclick="objectActivateSelected();"><? echo $la['ACTIVATE'];?></a></li>
        <li><a class="icon-close" href="#" onclick="objectDeactivateSelected();"><? echo $la['DEACTIVATE'];?></a></li>
        <li><a class="icon-time" href="#" onclick="setExpirationSelected('open_objects');"><? echo $la['SET_EXPIRATION'];?></a></li>
        <?php if(checkSettingsPrivileges('clr_history','delete')==true){?>
        <li><a class="icon-erase" href="#" onclick="objectClearHistorySelected();"><? echo $la['CLEAR_HISTORY'];?></a></li>
        <?php } if(checkSettingsPrivileges('object','delete')==true){?>
            <li><a class="icon-remove3" href="#" onclick="objectDeleteSelected();"><? echo $la['DELETE'];?></a></li>
        <?php }?>
</ul>

<ul id="cpanel_inventory_simcard_list__action_menu" class="menu">
    <?if ($_SESSION["cpanel_privileges"] == 'admin' || $_SESSION["cpanel_privileges"] == 'super_admin') { ?>
    <li><a class="icon-tick first-item" href="#" onclick="activesimcarddetailsSelected();"><? echo $la['ACTIVATE'];?></a></li>
    <li><a class="icon-remove3" href="#" onclick="deletesimcarddetailsSelected();"><? echo $la['DELETE'];?></a></li>
    <li><a class="icon-remove3" href="#" onclick="suspendsimcarddetailsSelected();"><? echo $la['SUSPEND'];?></a></li>
    <li><a class="icon-remove3" href="#" onclick="deactivatesimcarddetailsSelected();"><? echo $la['DEACTIVATE'];?></a></li>
    <?}?>
    <li><a class="icon-close" href="#" onclick="removesimcarddetailsSelected();"><? echo $la['REMOVE'];?></a></li>
    <?if (($_SESSION["cpanel_privileges"] == 'super_admin' || $_SESSION["cpanel_privileges"] == 'admin')){?>
        <li><a class="icon-erase" href="#" onclick="editsimcarddetailsSelected();"><? echo $la['EDIT'];?></a></li>
    <?}?>
</ul>

<? if ($_SESSION["cpanel_privileges"] == 'admin' || $_SESSION["cpanel_privileges"] == 'super_admin') { ?>
<ul id="cpanel_unused_object_list_grid_action_menu" class="menu">
        <li><a class="icon-remove3" href="#" onclick="unusedObjectDeleteSelected();"><? echo $la['DELETE'];?></a></li>
</ul>
<?}?>

<? if ($_SESSION["billing"] == true) { ?>
<ul id="cpanel_billing_plan_list_grid_action_menu" class="menu">
        <li><a class="icon-remove3 first-item" href="#" onclick="billingPlanDeleteSelected();"><? echo $la['DELETE'];?></a></li>
</ul>
<? } ?>

<ul id="dialog_user_edit_subaccount_list_grid_action_menu" class="menu">
        <li><a class="icon-tick first-item" href="#" onclick="userSubaccountActivateSelected();"><? echo $la['ACTIVATE'];?></a></li>
        <li><a class="icon-close" href="#" onclick="userSubaccountDeactivateSelected();"><? echo $la['DEACTIVATE'];?></a></li>
        <?php if(checkSettingsPrivileges('subaccount','delete')==true){ ?>
            <li><a class="icon-remove3" href="#" onclick="userSubaccountDeleteSelected();"><? echo $la['DELETE'];?></a></li>
        <?php } ?>
</ul>


<ul id="cpanel_staff_manag_list_grid_action_menu" class="menu">
        <li><a class="icon-tick first-item" href="#" onclick="OpenDialog();"><? echo $la['EMPLOYEEREG'];?></a></li>
        
</ul>

<ul id="dialog_user_edit_object_list_grid_action_menu" class="menu">
        <li><a class="icon-tick first-item" href="#" onclick="userObjectActivateSelected();"><? echo $la['ACTIVATE'];?></a></li>
        <li><a class="icon-close" href="#" onclick="userObjectDeactivateSelected();"><? echo $la['DEACTIVATE'];?></a></li>
        <li><a class="icon-time" href="#" onclick="setExpirationSelected('open_user_objects');"><? echo $la['SET_EXPIRATION'];?></a></li>
        <?php if(checkSettingsPrivileges('clr_history','delete')==true){?>
            <li><a class="icon-erase" href="#" onclick="userObjectClearHistorySelected();"><? echo $la['CLEAR_HISTORY'];?></a></li>
        <?php } if(checkSettingsPrivileges('object','delete')==true){?>
            <li><a class="icon-remove3" href="#" onclick="userObjectDeleteSelected();"><? echo $la['DELETE'];?></a></li>
        <?php }?>
</ul>

<? if ($_SESSION["billing"] == true) { ?>
<ul id="dialog_user_edit_billing_plan_list_grid_action_menu" class="menu">
        <li><a class="icon-remove3 first-item" href="#" onclick="userBillingPlanDeleteSelected();"><? echo $la['DELETE'];?></a></li>
</ul>
<? } ?>

<ul id="dialog_user_edit_usage_list_grid_action_menu" class="menu">
        <li><a class="icon-remove3 first-item" href="#" onclick="userUsageDeleteSelected();"><? echo $la['DELETE'];?></a></li>
</ul>