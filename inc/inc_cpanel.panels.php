<div id="loading_panel">
    <div class="table">
        <div class="table-cell center-middle">
            <div id="loading_panel_text">
                <div class="row">
                    <img class="logo" src="<? echo $gsValues['URL_LOGO']; ?>" />
                </div>
                <div class="row">
                   <div class="lds-hourglass"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="blocking_panel">
    <div class="table">
        <div class="table-cell center-middle">
            <div id="blocking_panel_text">
                <div class="row">
                    <img class="logo" src="<? echo $gsValues['URL_LOGO']; ?>" />
                </div>
                <? echo sprintf($la['SESSION_HAS_EXPIRED'], $gsValues['URL_LOGIN']); ?>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    .cpanallogo li a img {
        height: 15px;
    }
</style>
<div id="top_panel" style="background: url('img/hex.jpg' );" >
		<ul class=" left-menu cpanallogo">
    <li class="back-btn " style="padding-top: 5px;padding-bottom: 17px;
">
        <a title="<? echo $la['BACK']; ?>" href="Dashboard.php">
            <img src="theme/images/home.svg" style="
    height: 26px;
" />
        </a>
    </li>

    <? if (($_SESSION["cpanel_privileges"] == 'super_admin') || ($_SESSION["cpanel_privileges"] == 'admin')) { ?>
    <li class="select-view" style="
    margin-top: 3px;
">
        <!-- <? echo $la['VIEW_AS']; ?>: --> <select id="cpanel_manager_list" style="background-color: #ff4962;
    border-radius: 8px; color: white; box-shadow: 1px 0 2px 0 rgba(0, 0, 0, 0.72);
   padding-bottom: 5px;
    padding-top: 4px;height: 30px;
   " onchange="switchCPManager(this.value);" /></select>
    </li>
    <? } ?>
    <li>
        <a title="<? echo $la['USER_LIST']; ?>" class="user-list-btn active" id="top_panel_button_user_list" href="#" style="background-color: #1E9FF2;
    border-radius: 8px; color: white; box-shadow: 1px 0 2px 0 rgba(0, 0, 0, 0.72);
   padding-bottom: 5px;
    padding-top: 4px;
    margin-top: 9px;" onClick="switchCPTab('user_list');">
            <img src="theme/images/user-white.svg" />
            <span id="user_list_stats"></span>
        </a>
    </li>
    <li>
        <a title="<? echo $la['OBJECT_LIST']; ?>" class="object-list-btn" id="top_panel_button_object_list" href="#" style="background-color: #666EE8;
    border-radius: 8px; color: white; box-shadow: 1px 0 2px 0 rgba(0, 0, 0, 0.72);margin-left: 10px;
    padding-bottom: 5px;
    padding-top: 4px;
    margin-top: 9px;" onClick="switchCPTab('object_list');">
            <img src="theme/images/marker-white.svg" />
            <span id="object_list_stats"></span>
        </a>
    </li>
    <? if (($_SESSION["cpanel_privileges"] == 'super_admin') || ($_SESSION["cpanel_privileges"] == 'admin')) { ?>
    <li>
        <a title="<? echo $la['UNUSED_OBJECT_LIST']; ?>" class="unused-object-list-btn" style="background-color: #FF4961;
    border-radius: 8px; color: white; box-shadow: 1px 0 2px 0 rgba(0, 0, 0, 0.72);margin-left: 10px;
    padding-bottom: 5px;
    padding-top: 4px;
    margin-top: 9px;" id="top_panel_button_unused_object_list" href="#" onClick="switchCPTab('unused_object_list');">
            <img src="theme/images/marker-crossed-white.svg" />
            <span id="unused_object_list_stats"></span>
        </a>
    </li>
    <? } ?>
    <? if ($_SESSION["billing"] == true) { ?>
    <li>
        <a title="<? echo $la['BILLING_PLAN_LIST']; ?>" class="billing-plan-list-btn" style="background-color: #FF9149;
    border-radius: 8px; color: white; box-shadow: 1px 0 2px 0 rgba(0, 0, 0, 0.72);margin-left: 10px;
    padding-bottom: 5px;
    padding-top: 4px;
    margin-top: 9px;" id="top_panel_button_billing_plan_list" href="#" onClick="switchCPTab('billing_plan_list');">
            <img src="theme/images/billing-white.svg" />
            <span id="billing_plan_list_stats"></span>
        </a>
    </li>
    <? } ?>
    <? if ($_SESSION["cpanel_privileges"] == 'super_admin') { ?>
    <li>
        <a title="<? echo $la['MANAGE_SERVER']; ?>" class="manage-server-btn" style="background-color: #1E9FF2;border-radius: 8px; color: white; box-shadow: 1px 0 2px 0 rgba(0, 0, 0, 0.72);margin-left: 10px;padding-bottom: 5px; padding-top: 4px;margin-top: 9px;" id="top_panel_button_manage_server" href="#" onClick="switchCPTab('manage_server');">
            <img src="theme/images/settings-white.svg" />
        </a>
    </li>
    <? } ?>

    <? if ($_SESSION["staff_manag"] == 'true') { ?>
    <li>
        <a title="<? echo $la['STAFF_MANAG']; ?>" class="manage-staff-btn" style="background-color: #ff5151;border-radius: 8px; color: white; box-shadow: 1px 0 2px 0 rgba(0, 0, 0, 0.72);margin-left: 10px;padding-bottom: 5px; padding-top: 4px;margin-top: 9px;" id="top_panel_button_manag_staff" href="#" onClick="switchCPTab('staff_manag');">
            <img src="theme/images/use-plan-white.svg" />
        </a>
    </li>
    <? } ?>

	<? if ($_SESSION["inventory_manag"] == 'true') { ?>
	<li>
		<center><a title="<? echo $la['INVENTORY']; ?>" class="inventory-btn" style="background-color: #ff5151;border-radius: 8px; color: white; box-shadow: 1px 0 2px 0 rgba(0, 0, 0, 0.72);margin-left: 10px;padding-bottom: 5px; padding-top: 4px;margin-top: 9px;" id="top_panel_button_inventory" href="#" onClick="switchCPTab('inventory_manag');">
			<img src="theme/images/inventory-2.svg" />
		</a></center>
	</li>
	<? } ?>

    </ul>

    <ul class="right-menu cpanallogo">
        <li class="select-language" style="padding-top: 10px !important;">
            <select id="system_language" class="select-language-style" onChange="switchLanguageCPanel();">
                <? echo getLanguageList(); ?></select></li>
        <li>
            <a class="user-btn user-btn-style" href="#" onclick="userEdit('<? echo $_SESSION["user_id"]; ?>');" title="
                <? echo $la['MY_ACCOUNT']; ?>">
                <img src="theme/images/user.svg" border="0" />
                <span class="user-btn-text">
                    <? echo truncateString($_SESSION["username"], 10);?></span>
            </a>
        </li>
        <li class="logout-btn">
            <a title="<? echo $la['LOGOUT']; ?>" href="#" onclick="connectLogout();">
                <img src="theme/images/logout.svg" style="height: 24px;
    padding-left: 7px;
    margin-top: 4px;
" />
            </a>
        </li>
    </ul>
</div>

<div id="cpanel_user_list">

    <div class="float-left cpanel-title">
        <div class="version">v
            <? echo $gsValues['VERSION']; ?>
        </div>
        <h1 class="title">
            <? echo $la['CONTROL_PANEL']; ?> <span> -
                <? echo $la['USER_LIST']; ?></span></h1>
    </div>
    <table id="cpanel_user_list_grid"></table>
    <div id="cpanel_user_list_grid_pager"></div>
</div>



<div id="cpanel_object_list" style="display:none;">
    <div class="float-left cpanel-title">
        <div class="version">v
            <? echo $gsValues['VERSION']; ?>
        </div>
        <h1 class="title">
            <? echo $la['CONTROL_PANEL']; ?> <span> -
                <? echo $la['OBJECT_LIST']; ?></span></h1>
    </div>
    <table id="cpanel_object_list_grid"></table>
    <div id="cpanel_object_list_grid_pager"></div>
</div>

<? if (($_SESSION["cpanel_privileges"] == 'super_admin') || ($_SESSION["cpanel_privileges"] == 'admin')) { ?>
<div id="cpanel_unused_object_list" style="display:none;">
    <div class="float-left cpanel-title">
        <div class="version">v
            <? echo $gsValues['VERSION']; ?>
        </div>
        <h1 class="title">
            <? echo $la['CONTROL_PANEL']; ?> <span> -
                <? echo $la['UNUSED_OBJECT_LIST']; ?></span></h1>
    </div>
    <table id="cpanel_unused_object_list_grid"></table>
    <div id="cpanel_unused_object_list_grid_pager"></div>
</div>
<? } ?>

<? if ($_SESSION["billing"] == true) {?>
<div id="cpanel_billing_plan_list" style="display:none;">
    <div class="float-left cpanel-title">
        <div class="version">v
            <? echo $gsValues['VERSION']; ?>
        </div>
        <h1 class="title">
            <? echo $la['CONTROL_PANEL']; ?> <span> -
                <? echo $la['BILLING_PLAN_LIST']; ?></span></h1>
    </div>
    <table id="cpanel_billing_plan_list_grid"></table>
    <div id="cpanel_billing_plan_list_grid_pager"></div>
</div>
<? } ?>

<? if ($_SESSION["staff_manag"] == true) {?>
<div id="cpanel_staff_manag_list" style="display:none;">
    <div class="row4">
        <div class="float-left cpanel-title">
            <div class="version">v
                <? echo $gsValues['VERSION']; ?>
            </div>
            <h1 class="title">
                <? echo $la['CONTROL_PANEL']; ?> <span> -
                    <? echo $la['STAFF_MANAG']; ?></span></h1>
        </div>
        <div class="float-right">
            <div class="row2">
                <div class="width12">
                    <? echo $la['FROMDATEH']; ?>
                </div>
                <div class="width20"><input id="dialog_boarding_holidayfromdate" readonly class="inputbox-calendar inputbox width100" type="text" maxlength="50" /></div>
                <div class="width1"></div>
                <div class="width12">
                    <? echo $la['TODATEH']; ?>
                </div>
                <div class="width20"><input id="dialog_boarding_holidaytodate" readonly class="inputbox-calendar inputbox width100" type="text" value="" maxlength="50" /></div>
                <div class="width1"></div>
                <div class="width12">
                    <input id="dialog_staff_search" class="button icon-search icon" type="button" onclick="ServiceAdd('refresh');" value="<? echo $la['SEARCH']; ?>" />
                </div>
            </div>
        </div>
    </div>
    <div>
        <table id="cpanel_staff_manag_list_grid"></table>
    </div>
    <div id="cpanel_staff_manag_list_grid_pager"></div>

</div>

<? } ?>