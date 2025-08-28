//#################################################
// DIALOGS, TABS, GROUPING
//#################################################

function initGui()
{
	// define calendar
	$('.inputbox-calendar').datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: "yy-mm-dd",
		firstDay: 1,
		dayNamesMin: [la['DAY_SUNDAY_S'], la['DAY_MONDAY_S'], la['DAY_TUESDAY_S'], la['DAY_WEDNESDAY_S'], la['DAY_THURSDAY_S'], la['DAY_FRIDAY_S'], la['DAY_SATURDAY_S']],
		monthNames: [la['MONTH_JANUARY'], la['MONTH_FEBRUARY'], la['MONTH_MARCH'], la['MONTH_APRIL'], la['MONTH_MAY'], la['MONTH_JUNE'], la['MONTH_JULY'], la['MONTH_AUGUST'], la['MONTH_SEPTEMBER'], la['MONTH_OCTOBER'], la['MONTH_NOVEMBER'], la['MONTH_DECEMBER']]
	}).datepicker("setDate", new Date());
	
	$('.inputbox-calendar-mmdd').datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: "mm-dd",
		firstDay: 1,
		dayNamesMin: [la['DAY_SUNDAY_S'], la['DAY_MONDAY_S'], la['DAY_TUESDAY_S'], la['DAY_WEDNESDAY_S'], la['DAY_THURSDAY_S'], la['DAY_FRIDAY_S'], la['DAY_SATURDAY_S']],
		monthNames: [la['MONTH_JANUARY'], la['MONTH_FEBRUARY'], la['MONTH_MARCH'], la['MONTH_APRIL'], la['MONTH_MAY'], la['MONTH_JUNE'], la['MONTH_JULY'], la['MONTH_AUGUST'], la['MONTH_SEPTEMBER'], la['MONTH_OCTOBER'], la['MONTH_NOVEMBER'], la['MONTH_DECEMBER']],
		monthNamesShort: [la['MONTH_JANUARY_S'], la['MONTH_FEBRUARY_S'], la['MONTH_MARCH_S'], la['MONTH_APRIL_S'], la['MONTH_MAY_S'], la['MONTH_JUNE_S'], la['MONTH_JULY_S'], la['MONTH_AUGUST_S'], la['MONTH_SEPTEMBER_S'], la['MONTH_OCTOBER_S'], la['MONTH_NOVEMBER_S'], la['MONTH_DECEMBER_S']]
	});
	
	// define tabs
	$("#manage_server_tabs, #dialog_user_edit_tabs").tabs({
		show: function() {
		var $target = $(ui.panel);
		$('.content:visible').effect(function(){ $target.fadeIn()}); }
	});
	
	// define tokenize
	$('#dialog_user_object_add_objects').tokenize({
		datas: "func/fn_cpanel.objects.php?cmd=load_object_search_list&manager_id=" + cpValues['manager_id'],
		placeholder: la['ENTER_OBJECT_NAME_OR_IMEI'],
		newElements: false
	});
	
	$('#dialog_object_add_users').tokenize({
		datas: "func/fn_cpanel.users.php?cmd=load_user_search_list&manager_id=" + cpValues['manager_id'],
		placeholder: la['ENTER_ACCOUNT_USERNAME_OR_EMAIL'],
		newElements: false
	});
	
	$('#dialog_object_edit_users').tokenize({
		datas: "func/fn_cpanel.users.php?cmd=load_user_search_list&manager_id=" + cpValues['manager_id'],
		placeholder: la['ENTER_ACCOUNT_USERNAME_OR_EMAIL'],
		newElements: false
	});
	
	$('#send_email_username').tokenize({
		datas: "func/fn_cpanel.users.php?cmd=load_user_search_list&manager_id=" + cpValues['manager_id'],
		placeholder: la['ENTER_ACCOUNT_USERNAME_OR_EMAIL'],
		newElements: false
	});
	
	// define dialogs
	$("#dialog_notify").dialog({
		autoOpen: false,
		width: "250px",
		height: "auto",
		minHeight: "auto",
		modal: true,
		resizable: false,
		draggable: false,
		dialogClass: 'dialog-notify-titlebar'
	});
	
	$("#dialog_confirm").dialog({
		autoOpen: false,
		width: "320px",
		height: "auto",
		minHeight: "auto",
		modal: true,
		resizable: false,
		draggable: false,
		dialogClass: 'dialog-notify-titlebar'
	});
		
	$("#dialog_set_expiration").dialog({
		autoOpen: false,
		width: "320px",
		height: "auto",
		minHeight: "auto",
		modal: true,
		resizable: false
	});
	
	$("#dialog_send_email").dialog({
		autoOpen: false,
		width: "700px",
		height: "auto",
		minHeight: "auto",
		modal: true,
		resizable: false
	});
	
	$("#dialog_user_add").dialog({
		autoOpen: false,
		width: "320px",
		height: "auto",
		minHeight: "auto",
		modal: true,
		resizable: false
	});
	
	$("#dialog_user_edit").dialog({
		autoOpen: false,
		width: "850px",
		height: "auto",
		minHeight: "auto",
		modal: true,
		resizable: false,
		close: function(event, ui) {
						$('#cpanel_object_list_grid').trigger("reloadGrid");
						$('#cpanel_user_list_grid').trigger("reloadGrid");
						$('#cpanel_billing_plan_list_grid').trigger("reloadGrid");
					}
	});
	
	
	$("#dialog_object_edit").dialog({
		autoOpen: false,
		width: "450px",
		height: "auto",
		minHeight: "auto",
		modal: true,
		resizable: false,
		close: function(event, ui) {
						$('#cpanel_object_list_grid').trigger("reloadGrid");
						$('#cpanel_user_list_grid').trigger("reloadGrid");
						if ($('#dialog_user_edit').dialog('isOpen') == true)
						{
						       $('#dialog_user_edit_object_list_grid').trigger("reloadGrid"); 
						}
					}
	});
	$("#dialog_view_supplier").dialog({
		autoOpen: false,
		width: "950px",
		height: "auto",
		minHeight: "auto",
		modal: true,
		resizable: false
	});

	$("#dialog_view_inventory_simcards").dialog({
		autoOpen: false,
		width: "650px",
		height: "550",
		minHeight: "auto",
		modal: true,
		resizable: false
	});

	$("#dialog_edit_supplier").dialog({
		autoOpen: false,
		width: "950px",
		height: "auto",
		minHeight: "auto",
		modal: true,
		resizable: false
	});
	
	$("#dialog_user_object_add").dialog({
		autoOpen: false,
		width: "320px",
		height: "auto",
		minHeight: "auto",
		modal: true,
		resizable: false
	});
	
	$("#dialog_user_billing_plan_add").dialog({
		autoOpen: false,
		width: "800px",
		height: "auto",
		minHeight: "auto",
		modal: true,
		resizable: false
	});
	
	$("#dialog_user_billing_plan_edit").dialog({
		autoOpen: false,
		width: "400px",
		height: "auto",
		minHeight: "auto",
		modal: true,
		resizable: false,
		close: function(event, ui) {
						$('#cpanel_billing_plan_list_grid').trigger("reloadGrid");
						if ($('#dialog_user_edit').dialog('isOpen') == true)
						{
						       $('#dialog_user_edit_billing_plan_list_grid').trigger("reloadGrid"); 
						}
					}
	});
	
	$("#dialog_object_add").dialog({
		autoOpen: false,
		width: "450px",
		height: "auto",
		minHeight: "auto",
		modal: true,
		resizable: false
	});
	$("#dialog_object_edit_dispenser_calibration_model").dialog({
		autoOpen: false,
		width: "450px",
		height: "auto",
		minHeight: "auto",
		modal: true,
		resizable: false
	});
	
	$("#dialog_staff_manag_employee_master").dialog({
		autoOpen: false,
		width: "1100px",
		height: "auto",
		minHeight: "auto",
		modal: true,
		resizable: false
	});
	
	$("#dialog_custom_map_properties").dialog({
		autoOpen: false,
		width: "600",
		height: "auto",
		minHeight: "auto",
		modal: true,
		resizable: false
	});
	
	$("#dialog_billing_properties").dialog({
		autoOpen: false,
		width: "400",
		height: "auto",
		minHeight: "auto",
		modal: true,
		resizable: false
	});
	
	$("#dialog_template_properties").dialog({
		autoOpen: false,
		width: "800",
		height: "auto",
		minHeight: "auto",
		modal: true,
		resizable: false
	});
	
	$("#dialog_staff_manag_add").dialog({  
		autoOpen: false,
		width: "1200px",
		height: "auto",
		minHeight: "auto",
		modal: true,
		resizable: false
	});
	
	$("#dialog_staff_report_add").dialog({  
		autoOpen: false,
		width: "1200px",
		height: "auto",
		minHeight: "auto",
		modal: true,
		resizable: false
	});


	$("#dialog_staff_view_service").dialog({  
		autoOpen: false,
		width: "900px",
		height: "auto",
		minHeight: "auto",
		modal: true,
		resizable: false
	});
	// init select lists
	initSelectList('object_device_list');
	initSelectList('object_dispenser_tracker_list');

}

//#################################################
// END DIALOGS, TABS, GROUPING
//#################################################

//#################################################
// NOTIFY/CONFIRM DIALOGS/POPUPS
//#################################################

function notifyDialog(text)
{
	document.getElementById('dialog_notify_text').innerHTML = text;
	
	$('#dialog_notify').dialog('open');
}

var confirmResponseValue = false;

function confirmDialog(text, response)
{
	confirmResponseValue = false;
	
	document.getElementById('dialog_confirm_text').innerHTML = text;
	
	$('#dialog_confirm').dialog('destroy');
	
	$("#dialog_confirm").dialog({
		autoOpen: false,
		width: "320px",
		height: "auto",
		minHeight: "auto",
		modal: true,
		resizable: false,
		draggable: false,
		dialogClass: 'dialog-notify-titlebar',
		close: function(event, ui) { response(confirmResponseValue); }
	});
	
	$('#dialog_confirm').dialog('open');
}

function confirmResponse(value)
{
        confirmResponseValue = value;
	$('#dialog_confirm').dialog('close');
}

//#################################################
// END NOTIFY/CONFIRM DIALOGS/POPUPS
//#################################################

function initGrids()
{
	// define user list grid
	$("#cpanel_user_list_grid").jqGrid({
		url:'func/fn_cpanel.users.php?cmd=load_user_list',
		datatype: "json",
		colNames:['ID',la['USERNAME'], la['EMAIL'],la['ACTIVE'],la['EXPIRES_ON'],la['PRIVILEGES'],la['API'], la['REG_TIME'],la['LOGIN_TIME'],la['IP'],la['SUB_ACC'],la['OBJECTS'],la['EMAIL'],la['SMS'],la['API'],'',''],
		colModel:[
			{name:'id',index:'id',width:50,align:"center"},
			{name:'username',index:'username',width:150},
			{name:'email',index:'email',width:150},
			{name:'active',index:'active',width:50,align:"center"},
			{name:'account_expire_dt',index:'account_expire_dt',width:60,align:"center"},
			{name:'privileges',index:'privileges',width:70,align:"center"},
			{name:'api',index:'api',width:50,align:"center"},
			{name:'dt_reg',index:'dt_reg',width:110,align:"center"},
			{name:'dt_login',index:'dt_login',width:110,align:"center"},		
			{name:'ip',index:'ip',width:110},
			{name:'subacc_cnt',index:'subacc_cnt',width:50,align:"center"},
			{name:'obj_cnt',index:'obj_cnt',width:50,align:"center"},
			{name:'usage_email_daily_cnt',index:'usage_email_daily_cnt',width:50,align:"center"},
			{name:'usage_sms_daily_cnt',index:'usage_sms_daily_cnt',width:50,align:"center"},
			{name:'usage_api_daily_cnt',index:'usage_api_daily_cnt',width:50,align:"center"},
			{name:'modify',index:'modify',width:75,align:"center",sortable: false, fixed: true},
			{name:'scroll_fix',index:'scroll_fix',width:13,sortable: false, fixed: true} // scroll fix
		],
		//altRows: true,
		//altclass: 'myAltRowClass',
		rowNum:50,
		rowList:[25,50,100,200,300,400,500],
		pager: '#cpanel_user_list_grid_pager',
		sortname: 'id',
		sortorder: "asc",
		viewrecords: true,
		rownumbers: true,
		height: '400px',
		shrinkToFit: true,
		multiselect: true,
		beforeSelectRow: function(id, e)
		{
			if (e.target.tagName.toLowerCase() === "input"){return true;}
			return false;
		},
		  loadComplete: function (dat) {
	        var custom = jQuery("#cpanel_user_list_grid").jqGrid('getRowData');
	        var a = document.getElementById("ddluser");
	        a.options.length = 0;
	        a.options.add(new Option("All"));
	      
			 for($itr=0;$itr<custom.length;$itr++)
			 {
	 			 a.options.add(new Option(custom[$itr]["username"],custom[$itr]["id"]));
			 }
				 
	        }
	}),//code done by vetrivel.N
	$("#notification_grid").jqGrid({
		url:'func/fn_cpanel.php?cmd=select_notification',
		datatype: "json",
		colNames:['ID',la['USER'],la['INFORMATION'],la['URL'],la['ACTIVE'],la['TIME_FROM'],la['TIME_TO'],''],
		colModel:[
			{name:'aid',index:'aid',align:"center", hidden: true},
			{name:'user',index:'user',align:"center",width:100},
			{name:'info',index:'active',align:"center",width:300},
			{name:'url',index:'url',align:"center",width:150},
			{name:'active',index:'active', hidden: true},
			{name:'datefrom',index:'datefrom',width:150},
			{name:'dateto',index:'dateto',align:"center",width:150},
			{name:'modify',index:'modify',align:"center",sortable: false}
		],
		
		rowNum:50,
		rowList:[25,50,100,200,300,400,500],
		pager: '#notification_pager',
		sortname: 'aid',
		sortorder: "asc",
		viewrecords: true,
		rownumbers: true,
		width:'1280',
		height: '400px',
		shrinkToFit: true		
	});
	
	$("#cpanel_user_list_grid").jqGrid('navGrid','#cpanel_user_list_grid_pager',{ 	add:true,
											edit:false,
											del:false,
											search:false,
											addfunc: function (e) {userAdd('open');}																		
											});
	
	$("#cpanel_user_list_grid").navButtonAdd('#cpanel_user_list_grid_pager',{	caption: "", 
											title: la['ACTION'],
											buttonicon: 'ui-icon-action',
											onClickButton: function(){}, 
											position:"last",
											id: "cpanel_user_list_grid_action_menu_button"
											});

	// action menu
	$("#cpanel_user_list_grid_action_menu").menu({
		role: 'listbox'
	});
	$("#cpanel_user_list_grid_action_menu").hide();
	
	$("#cpanel_user_list_grid_action_menu_button").click(function() {
			$("#cpanel_user_list_grid_action_menu").toggle().position({
			my: "left bottom",
			at: "right-5 top-5",
			of: this
		});
				
		$(document).one("click", function() {
			$("#cpanel_user_list_grid_action_menu").hide();
		});
		
		return false;
	});

	$("#cpanel_user_list_grid").setCaption(	'<div class="row4">\
							<div class="float-left">\
								<a href="#" onclick="sendEmail(\'open\');" title="'+la['SEND_EMAIL']+'">\
								<div class="panel-button">\
									<img src="theme/images/create.svg" width="16px" border="0"/>\
								</div>\
								</a>\
							</div>\
							<input id="cpanel_user_list_search" class="inputbox-search" type="text" value="" placeholder="'+la['SEARCH']+'" maxlength="25">\
						</div>');
	
	$("#cpanel_user_list_search").bind("keyup", function(e) {
		var manager_id = '&manager_id=' + cpValues['manager_id'];
		$('#cpanel_user_list_grid').setGridParam({url:'func/fn_cpanel.users.php?cmd=load_user_list&s=' + this.value + manager_id});
		$('#cpanel_user_list_grid').trigger("reloadGrid");	
	});
	
	$("#cpanel_user_list_grid").setGridWidth($(window).width() -60 );
	$("#cpanel_user_list_grid").setGridHeight($(window).height() - 208);
	$(window).bind('resize', function() {$("#cpanel_user_list_grid").setGridWidth($(window).width() - 60);});
	$(window).bind('resize', function() {$("#cpanel_user_list_grid").setGridHeight($(window).height() - 208);});
	
	// define object list grid
	$("#cpanel_object_list_grid").jqGrid({
		url:'func/fn_cpanel.objects.php?cmd=load_object_list',
		datatype: "json",
		colNames:[la['NAME'],la['IMEI'],la['ACTIVE'],la['EXPIRES_ON'],la['SIM_CARD_NUMBER'],la['LAST_CONNECTION'],la['PROTOCOL'],la['PORT'],la['STATUS'],la['USER_ACCOUNT'],'',''],
		colModel:[
			{name:'name',index:'name',width:80},
			{name:'imei',index:'imei',width:80},
			{name:'active',index:'active',width:50,align:"center"},
			{name:'object_expire_dt',index:'object_expire_dt',width:60,align:"center"},
			{name:'sim_number',index:'sim_number',width:80},
			{name:'dt_server',index:'dt_server',width:80,align:"center"},
			{name:'protocol',index:'protocol',width:60,align:"center"},
			{name:'port',index:'port',width:40,align:"center"},
			{name:'status',index:'status',width:80,sortable: false,align:"center"},
			{name:'used_in',index:'used_in',width:150,sortable: false},
			{name:'modify',index:'modify',width:75,align:"center",sortable: false, fixed: true},
			{name:'scroll_fix',index:'scroll_fix',width:13,sortable: false, fixed: true} // scroll fix
		],
		rowNum:50,
		rowList:[25,50,100,200,300,400,500],
		pager: '#cpanel_object_list_grid_pager',
		sortname: 'imei',
		sortorder: "asc",
		viewrecords: true,
		rownumbers: true,
		height: '400px',
		shrinkToFit: true,
		multiselect: true,
		beforeSelectRow: function(id, e)
		{
			if (e.target.tagName.toLowerCase() === "input"){return true;}
			return false;
		}
	});
	$("#cpanel_object_list_grid").jqGrid('navGrid','#cpanel_object_list_grid_pager',{	add:true,
												edit:false,																								
												del:false,
												search:false,
												addfunc: function (e) {objectAdd('open');}	
												});
	
	$("#cpanel_object_list_grid").navButtonAdd('#cpanel_object_list_grid_pager',{	caption: "", 
											title: la['ACTION'],
											buttonicon: 'ui-icon-action',
											onClickButton: function(){}, 
											position:"last",
											id: "cpanel_object_list_grid_action_menu_button"
											});

	// action menu
	$("#cpanel_object_list_grid_action_menu").menu({
		role: 'listbox'
	});
	$("#cpanel_object_list_grid_action_menu").hide();
	
	$("#cpanel_object_list_grid_action_menu_button").click(function() {
			$("#cpanel_object_list_grid_action_menu").toggle().position({
			my: "left bottom",
			at: "right-5 top-5",
			of: this
		});
				
		$(document).one("click", function() {
			$("#cpanel_object_list_grid_action_menu").hide();
		});
		
		return false;
	});
	
	$("#cpanel_object_list_grid").setCaption(	'<div class="row4">\
								<input id="cpanel_object_list_search" class="inputbox-search" type="text" value="" placeholder="'+la['SEARCH']+'" maxlength="25">\
							</div>');
	
	$("#cpanel_object_list_search").bind("keyup", function(e) {
		var manager_id = '&manager_id=' + cpValues['manager_id'];
		$('#cpanel_object_list_grid').setGridParam({url:'func/fn_cpanel.objects.php?cmd=load_object_list&s=' + this.value + manager_id});
		$('#cpanel_object_list_grid').trigger("reloadGrid");
	});
	
	$("#cpanel_object_list_grid").setGridWidth($(window).width() -60 );
	$("#cpanel_object_list_grid").setGridHeight($(window).height() - 208);
	$(window).bind('resize', function() {$("#cpanel_object_list_grid").setGridWidth($(window).width() -60 );});
	$(window).bind('resize', function() {$("#cpanel_object_list_grid").setGridHeight($(window).height() - 208);});
	
	// define unused object list grid
	if (document.getElementById('cpanel_unused_object_list_grid') != undefined)
	{
		$("#cpanel_unused_object_list_grid").jqGrid({
			url:'func/fn_cpanel.objects.php?cmd=load_unused_object_list',
			datatype: "json",
			colNames:[la['IMEI'],la['LAST_CONNECTION'],la['PROTOCOL'],la['PORT'],la['CONNECTION_ATTEMPTS'],'',''],
			colModel:[
				{name:'imei',index:'imei',width:160},
				{name:'dt_server',index:'dt_server',width:160,align:"center"},
				{name:'protocol',index:'protocol',width:100,align:"center"},
				{name:'port',index:'port',width:100,align:"center"},
				{name:'count',index:'count',width:100,align:"center"},
				{name:'modify',index:'modify',width:75,align:"center",sortable: false, fixed: true},
				{name:'scroll_fix',index:'scroll_fix',width:13,sortable: false, fixed: true} // scroll fix
			],
			rowNum:50,
			rowList:[25,50,100,200,300,400,500],
			pager: '#cpanel_unused_object_list_grid_pager',
			sortname: 'imei',
			sortorder: "asc",
			viewrecords: true,
			rownumbers: true,
			height: '400px',
			shrinkToFit: true,
			multiselect: true,
			beforeSelectRow: function(id, e)
			{
				if (e.target.tagName.toLowerCase() === "input"){return true;}
				return false;
			}
		});
		$("#cpanel_unused_object_list_grid").jqGrid('navGrid','#cpanel_unused_object_list_grid_pager',{	add:false,
														edit:false,																								
														del:false,
														search:false
														});
		
		$("#cpanel_unused_object_list_grid").navButtonAdd('#cpanel_unused_object_list_grid_pager',{	caption: "", 
														title: la['ACTION'],
														buttonicon: 'ui-icon-action',
														onClickButton: function(){}, 
														position:"last",
														id: "cpanel_unused_object_list_grid_action_menu_button"
														});
	
		// action menu
		$("#cpanel_unused_object_list_grid_action_menu").menu({
			role: 'listbox'
		});
		$("#cpanel_unused_object_list_grid_action_menu").hide();
		
		$("#cpanel_unused_object_list_grid_action_menu_button").click(function() {
				$("#cpanel_unused_object_list_grid_action_menu").toggle().position({
				my: "left bottom",
				at: "right-5 top-5",
				of: this
			});
					
			$(document).one("click", function() {
				$("#cpanel_unused_object_list_grid_action_menu").hide();
			});
			
			return false;
		});
			
		$("#cpanel_unused_object_list_grid").setCaption('<div class="row4">\
									<input id="cpanel_unused_object_list_search" class="inputbox-search" type="text" value="" placeholder="'+la['SEARCH']+'" maxlength="25">\
								</div>');
		
		$("#cpanel_unused_object_list_search").bind("keyup", function(e) {
			$('#cpanel_unused_object_list_grid').setGridParam({url:'func/fn_cpanel.objects.php?cmd=load_unused_object_list&s=' + this.value});
			$('#cpanel_unused_object_list_grid').trigger("reloadGrid");
		});
		
		$("#cpanel_unused_object_list_grid").setGridWidth($(window).width() -60 );
		$("#cpanel_unused_object_list_grid").setGridHeight($(window).height() - 208);
		$(window).bind('resize', function() {$("#cpanel_unused_object_list_grid").setGridWidth($(window).width() -60 );});
		$(window).bind('resize', function() {$("#cpanel_unused_object_list_grid").setGridHeight($(window).height() - 208);});
	}
	
	// define billing plan list grid
	if (document.getElementById("cpanel_billing_plan_list_grid") != undefined)
	{
		$("#cpanel_billing_plan_list_grid").jqGrid({
			url:'func/fn_cpanel.billing.php?cmd=load_billing_plan_list',
			datatype: "json",
			colNames:[la['TIME'],la['NAME'],la['OBJECTS'],la['PERIOD'],la['PRICE'],la['USER_ACCOUNT'],'',''],
			colModel:[
				{name:'dt_purchase',index:'dt_purchase',width:50,align:"center"},
				{name:'name',index:'name',width:80},
				{name:'objects',index:'objects',width:50,align:"center"},
				{name:'period',index:'period',width:50,align:"center"},
				{name:'price',index:'price',width:50,align:"center"},
				{name:'used_in',index:'used_in',width:80,sortable: false},
				{name:'modify',index:'modify',width:75,align:"center",sortable: false, fixed: true},
				{name:'scroll_fix',index:'scroll_fix',width:13,sortable: false, fixed: true} // scroll fix
			],
			rowNum:50,
			rowList:[25,50,100,200,300,400,500],
			pager: '#cpanel_billing_plan_list_grid_pager',
			sortname: 'dt_purchase',
			sortorder: "desc",
			viewrecords: true,
			rownumbers: true,
			height: '400px',
			shrinkToFit: true,
			multiselect: true,
			beforeSelectRow: function(id, e)
			{
				if (e.target.tagName.toLowerCase() === "input"){return true;}
				return false;
			}
		});
		$("#cpanel_billing_plan_list_grid").jqGrid('navGrid','#cpanel_billing_plan_list_grid_pager',{	add:false,
														edit:false,																								
														del:false,
														search:false
														});
		
		$("#cpanel_billing_plan_list_grid").navButtonAdd('#cpanel_billing_plan_list_grid_pager',{	caption: "", 
														title: la['ACTION'],
														buttonicon: 'ui-icon-action',
														onClickButton: function(){}, 
														position:"last",
														id: "cpanel_billing_plan_list_grid_action_menu_button"
														});
	
		// action menu
		$("#cpanel_billing_plan_list_grid_action_menu").menu({
			role: 'listbox'
		});
		$("#cpanel_billing_plan_list_grid_action_menu").hide();
		
		$("#cpanel_billing_plan_list_grid_action_menu_button").click(function() {
				$("#cpanel_billing_plan_list_grid_action_menu").toggle().position({
				my: "left bottom",
				at: "right-5 top-5",
				of: this
			});
					
			$(document).one("click", function() {
				$("#cpanel_billing_plan_list_grid_action_menu").hide();
			});
			
			return false;
		});
				
		$("#cpanel_billing_plan_list_grid").setCaption(	'<div class="row4">\
									<input id="cpanel_billing_plan_list_search" class="inputbox-search" type="text" value="" placeholder="'+la['SEARCH']+'" maxlength="25">\
								</div>');
		
		$("#cpanel_billing_plan_list_search").bind("keyup", function(e) {
			var manager_id = '&manager_id=' + cpValues['manager_id'];
			$('#cpanel_billing_plan_list_grid').setGridParam({url:'func/fn_cpanel.billing.php?cmd=load_billing_plan_list&s=' + this.value + manager_id});
			$('#cpanel_billing_plan_list_grid').trigger("reloadGrid");
		});
		
		$("#cpanel_billing_plan_list_grid").setGridWidth($(window).width() -60 );
		$("#cpanel_billing_plan_list_grid").setGridHeight($(window).height() - 208);
		$(window).bind('resize', function() {$("#cpanel_billing_plan_list_grid").setGridWidth($(window).width() -60 );});
		$(window).bind('resize', function() {$("#cpanel_billing_plan_list_grid").setGridHeight($(window).height() - 208);});
	}
	
	// define user subaccount list grid
	$("#dialog_user_edit_subaccount_list_grid").jqGrid({
		url:'func/fn_cpanel.users.php',
		datatype: "json",
		colNames:[la['USERNAME'],la['EMAIL'],la['PASSWORD'],la['ACTIVE'],la['IP'],''],
		colModel:[
			{name:'username',index:'username',width:190},
			{name:'email',index:'email',width:160},
			{name:'password',index:'password',width:160,sortable: false},
			{name:'active',index:'active',width:40,align:"center"},
			{name:'ip',index:'ip',width:108},
			{name:'modify',index:'modify',width:60,align:"center",sortable: false},
		],
		rowNum:25,
		rowList:[25,50,100],
		pager: '#dialog_user_edit_subaccount_list_grid_pager',
		sortname: 'username',
		sortorder: "asc",
		viewrecords: true,
		rownumbers: true,
		height: '443px',
		width: '820',
		shrinkToFit: false,
		multiselect: true,
		beforeSelectRow: function(id, e)
		{
			if (e.target.tagName.toLowerCase() === "input"){return true;}
			return false;
		}
	});
	$("#dialog_user_edit_subaccount_list_grid").jqGrid('navGrid','#dialog_user_edit_subaccount_list_grid_pager',{ 	add:false,
															edit:false,
															del:false,
															search:false													
															});
	
	$("#dialog_user_edit_subaccount_list_grid").navButtonAdd('#dialog_user_edit_subaccount_list_grid_pager',{	caption: "", 
															title: la['ACTION'],
															buttonicon: 'ui-icon-action',
															onClickButton: function(){}, 
															position:"last",
															id: "dialog_user_edit_subaccount_list_grid_action_menu_button"
															});
	
	// action menu
	$("#dialog_user_edit_subaccount_list_grid_action_menu").menu({
		role: 'listbox'
	});
	$("#dialog_user_edit_subaccount_list_grid_action_menu").hide();
	
	$("#dialog_user_edit_subaccount_list_grid_action_menu_button").click(function() {
			$("#dialog_user_edit_subaccount_list_grid_action_menu").toggle().position({
			my: "left bottom",
			at: "right-5 top-5",
			of: this
		});
				
		$(document).one("click", function() {
			$("#dialog_user_edit_subaccount_list_grid_action_menu").hide();
		});
		
		return false;
	});
	
	// define user object list grid
	$("#dialog_user_edit_object_list_grid").jqGrid({
		url:'func/fn_cpanel.users.php',
		datatype: "json",
		colNames:[la['NAME'],la['IMEI'],la['ACTIVE'],la['EXPIRES_ON'],la['LAST_CONNECTION'],la['STATUS'],''],
		colModel:[
			{name:'name',index:'name',width:190},
			{name:'imei',index:'imei',width:160},
			{name:'active',index:'active',width:40,align:"center"},
			{name:'object_expire_dt',index:'object_expire_dt',width:80,align:"center"},
			{name:'dt_server',index:'dt_server',width:143,align:"center"},
			{name:'status',index:'dt_server',width:40,align:"center",sortable: false},
			{name:'modify',index:'modify',width:60,align:"center",sortable: false},
		],
		rowNum:25,
		rowList:[25,50,100],
		pager: '#dialog_user_edit_object_list_grid_pager',
		sortname: 'name',
		sortorder: "asc",
		viewrecords: true,
		rownumbers: true,
		height: '443px',
		width: '820',
		shrinkToFit: false,
		multiselect: true,
		beforeSelectRow: function(id, e)
		{
			if (e.target.tagName.toLowerCase() === "input"){return true;}
			return false;
		}
	});
	$("#dialog_user_edit_object_list_grid").jqGrid('navGrid','#dialog_user_edit_object_list_grid_pager',{ 	add:true,
														edit:false,
														del:false,
														search:false,
														addfunc: function (e) {userObjectAdd('open');}																		
														});
	
	$("#dialog_user_edit_object_list_grid").navButtonAdd('#dialog_user_edit_object_list_grid_pager',{	caption: "", 
														title: la['ACTION'],
														buttonicon: 'ui-icon-action',
														onClickButton: function(){}, 
														position:"last",
														id: "dialog_user_edit_object_list_grid_action_menu_button"
														});
	
	// action menu
	$("#dialog_user_edit_object_list_grid_action_menu").menu({
		role: 'listbox'
	});
	$("#dialog_user_edit_object_list_grid_action_menu").hide();
	
	$("#dialog_user_edit_object_list_grid_action_menu_button").click(function() {
			$("#dialog_user_edit_object_list_grid_action_menu").toggle().position({
			my: "left bottom",
			at: "right-5 top-5",
			of: this
		});
				
		$(document).one("click", function() {
			$("#dialog_user_edit_object_list_grid_action_menu").hide();
		});
		
		return false;
	});
	
	// define user billing plan list grid
	if (document.getElementById("dialog_user_edit_billing_plan_list_grid") != undefined)
	{
		$("#dialog_user_edit_billing_plan_list_grid").jqGrid({
			url:'func/fn_cpanel.users.php',
			datatype: "json",
			colNames:[la['TIME'],la['NAME'],la['OBJECTS'],la['PERIOD'],la['PRICE'],''],
			colModel:[
				{name:'dt_purchase',index:'dt_purchase',width:135,align:"center"},
				{name:'name',index:'name',width:228},
				{name:'objects',index:'objects',width:85,align:"center"},
				{name:'period',index:'period',width:105,align:"center"},
				{name:'price',index:'price',width:105,align:"center"},
				{name:'modify',index:'modify',width:60,align:"center",sortable: false},
			],
			rowNum:25,
			rowList:[25,50,100],
			pager: '#dialog_user_edit_billing_plan_list_grid_pager',
			sortname: 'dt_purchase',
			sortorder: "desc",
			viewrecords: true,
			rownumbers: true,
			height: '443px',
			width: '820',
			shrinkToFit: false,
			multiselect: true,
			beforeSelectRow: function(id, e)
			{
				if (e.target.tagName.toLowerCase() === "input"){return true;}
				return false;
			}
		});
		$("#dialog_user_edit_billing_plan_list_grid").jqGrid('navGrid','#dialog_user_edit_billing_plan_list_grid_pager',{ 	add:true,
																	edit:false,
																	del:false,
																	search:false,
																	addfunc: function (e) {userBillingPlanAdd('open');}	
																	});
		
		$("#dialog_user_edit_billing_plan_list_grid").navButtonAdd('#dialog_user_edit_billing_plan_list_grid_pager',{	caption: "", 
																title: la['ACTION'],
																buttonicon: 'ui-icon-action',
																onClickButton: function(){}, 
																position:"last",
																id: "dialog_user_edit_billing_plan_list_grid_action_menu_button"
																});
		
		// action menu
		$("#dialog_user_edit_billing_plan_list_grid_action_menu").menu({
			role: 'listbox'
		});
		$("#dialog_user_edit_billing_plan_list_grid_action_menu").hide();
		
		$("#dialog_user_edit_billing_plan_list_grid_action_menu_button").click(function() {
				$("#dialog_user_edit_billing_plan_list_grid_action_menu").toggle().position({
				my: "left bottom",
				at: "right-5 top-5",
				of: this
			});
					
			$(document).one("click", function() {
				$("#dialog_user_edit_billing_plan_list_grid_action_menu").hide();
			});
			
			return false;
		});
	}
	
	// define user usage list grid
	$("#dialog_user_edit_usage_list_grid").jqGrid({
		url:'func/fn_cpanel.users.php',
		datatype: "json",
		colNames:[la['DATE'],la['LOGIN'],la['EMAIL'],la['SMS'],la['API'],''],
		colModel:[
			{name:'dt_usage',index:'dt_usage',width:135,align:"center"},
			{name:'login',index:'login',width:131,align:"center"},
			{name:'email',index:'email',width:131,align:"center"},
			{name:'sms',index:'sms',width:131,align:"center"},
			{name:'api',index:'api',width:131,align:"center"},
			{name:'modify',index:'modify',width:60,align:"center",sortable: false},
		],
		rowNum:25,
		rowList:[25,50,100],
		pager: '#dialog_user_edit_usage_list_grid_pager',
		sortname: 'dt_usage',
		sortorder: "desc",
		viewrecords: true,
		rownumbers: true,
		height: '443px',
		width: '820',
		shrinkToFit: false,
		multiselect: true,
		beforeSelectRow: function(id, e)
		{
			if (e.target.tagName.toLowerCase() === "input"){return true;}
			return false;
		}
	});
	$("#dialog_user_edit_usage_list_grid").jqGrid('navGrid','#dialog_user_edit_usage_list_grid_pager',{ 	add:false,
														edit:false,
														del:false,
														search:false,
														});
	
	$("#dialog_user_edit_usage_list_grid").navButtonAdd('#dialog_user_edit_usage_list_grid_pager',{	caption: "", 
													title: la['ACTION'],
													buttonicon: 'ui-icon-action',
													onClickButton: function(){}, 
													position:"last",
													id: "dialog_user_edit_usage_list_grid_action_menu_button"
													});
	
	// action menu
	$("#dialog_user_edit_usage_list_grid_action_menu").menu({
		role: 'listbox'
	});
	$("#dialog_user_edit_usage_list_grid_action_menu").hide();
	
	$("#dialog_user_edit_usage_list_grid_action_menu_button").click(function() {
			$("#dialog_user_edit_usage_list_grid_action_menu").toggle().position({
			my: "left bottom",
			at: "right-5 top-5",
			of: this
		});
				
		$(document).one("click", function() {
			$("#dialog_user_edit_usage_list_grid_action_menu").hide();
		});
		
		return false;
	});
	
	// define custom map list grid
	$("#cpanel_manage_server_custom_map_list_grid").jqGrid({
		datatype: "local",
		colNames:[la['NAME'],
			  la['ACTIVE'],
			  la['TYPE'],
			  la['URL'],
			  ''],
		colModel:[
			{name:'name',index:'name',width:230,fixed:true,align:"left",sortable:true},
			{name:'active',index:'active',width:80,fixed:true,align:"center",sortable:true},
			{name:'type',index:'type',width:180,fixed:true,align:"center",sortable:true},
			{name:'url',index:'url',width:643,fixed:true,align:"left",sortable:false},
			{name:'modify',index:'modify',width:120,fixed:true,align:"center",sortable: false}
		],
		sortname: '',
		sortorder: '',
		rowNum: 100,
		width: '1285',
		height: '350',
		shrinkToFit: true
	});
	
	
	$("#cpanel_inventory_supplier_list").jqGrid({
		url:'func/fn_cpanel.inventory.php?cmd=load_unused_object_list',
			datatype: "json",
			colNames:['ID',la['SUPPLIER_NAME'],la['CATEGORY'],la['GST_NO'],la['COMMERCIAL_ADDRESS'],la['EMAILID'],la['PHONE'],la['BANK_NAME'],'',''],
			colModel:[
				{name:'sid',index:'sid',width:10,align:"center"},
				{name:'name',index:'name',width:50},
				{name:'category',index:'category',width:50,align:"center"},
				{name:'gst_no',index:'gst_no',width:70,align:"center"},
				{name:'commercial_addr',index:'commercial_addr',width:150,align:"center"},
				{name:'mail',index:'mail',width:75,align:"center",sortable: false, fixed: true},
				{name:'phone',index:'phone',width:75,align:"center",sortable: false, fixed: true},
				{name:'bank_name',index:'bank_name',width:75,align:"center"},
				{name:'last_connection',index:'last_connection',width:75,align:"center",sortable: false, fixed: true},
				{name:'modify',index:'modify',width:13,sortable: false, fixed: true} // scroll fix
			],
		sortname: '',
		sortorder: '',
		rowNum:50,
		rowList:[25,50,100,200,300,400,500],
		pager: '#cpanel_inventory_supplier_list_pager',
		width: '1285',
		height: '350',
		shrinkToFit: true,
		viewrecords: true,
		rownumbers: true
	});
	$("#cpanel_inventory_supplier_list").jqGrid('navGrid','#cpanel_inventory_supplier_list_pager',{ 
		add:true,
		edit:false,
		del:false,
		search:false,
		addfunc: function (e) {adddNewSupplier('add');}
		});

	// SIM Card Details Grid
	$("#cpanel_inventory_simcard_list").jqGrid({
		url:'func/fn_cpanel.inventory.php?cmd=load_simcard_list',
			datatype: "json",
			colNames:[la['id'],la['MOBILENO'],la['SIM_CARD_NUMBER'],la['SIM_CARD_IMSI'],la['SIM_PROVIDER'],la['STATUS'],la['IMEI'],''],
			colModel:[
				{name:'sid',index:'sid',width:50,hidden:true},
				{name:'mobilenumber',index:'mobilenumber',width:50},
				{name:'simnumber',index:'simnumber',width:50,align:"center"},
				{name:'simimsi',index:'simimsi',width:70,align:"center"},
				{name:'simprovider',index:'simprovider',width:50,align:"center"},
				{name:'status',index:'status',width:75,align:"center",sortable: false, fixed: true},
				{name:'imei',index:'imei',width:100,align:"center",sortable: false, fixed: true},
				{name:'modify',index:'modify',width:75,align:"center",sortable: false, fixed: true}
			],
		rowNum:50,
		rowList:[25,50,100,200,300,400,500],
		pager: '#cpanel_inventory_simcard_list_pager',
		sortname: 'id',
		sortorder: "asc",
		viewrecords: true,
		rownumbers: true,
		width: '1285',
		height: '350',
		multiselect: true,
		shrinkToFit: true		
		// multiboxonly: true
	});
	$("#cpanel_inventory_simcard_list").jqGrid('navGrid','#cpanel_inventory_simcard_list_pager',{ 
		add:true,
		edit:false,
		del:false,
		search:false,
		addfunc: function (e) {inventorySimdetails('add');}
	});
	$("#cpanel_inventory_simcard_list").navButtonAdd('#cpanel_inventory_simcard_list_pager',{	caption: "", 
		title: la['ACTION'],
		buttonicon: 'ui-icon-action',
		onClickButton: function(){}, 
		position:"last",
		id: "cpanel_inventory_simcard_list_action_menu_button"
	});

	// action menu
	$("#cpanel_inventory_simcard_list__action_menu").menu({
		role: 'listbox'
	});
	$("#cpanel_inventory_simcard_list__action_menu").hide();
	
	$("#cpanel_inventory_simcard_list_action_menu_button").click(function() {
			$("#cpanel_inventory_simcard_list__action_menu").toggle().position({
			my: "left bottom",
			at: "right-5 top-5",
			of: this
		});		
				
		$(document).one("click", function() {
			$("#cpanel_inventory_simcard_list__action_menu").hide();			
		});
		
		return false;
	});

	// define billin plan list grid
	$("#cpanel_manage_server_billing_plan_list_grid").jqGrid({
		datatype: "local",
		colNames:[la['NAME'],
			  la['ACTIVE'],
			  la['OBJECTS'],
			  la['PERIOD'],
			  la['PRICE'],
			  ''],
		colModel:[
			{name:'name',index:'name',width:495,fixed:true,align:"left",sortable:true},
			{name:'active',index:'active',width:80,fixed:true,align:"center",sortable:true},
			{name:'objects',index:'objects',width:80,fixed:true,align:"center",sortable:true},
			{name:'period',index:'period',width:220,fixed:true,align:"center",sortable:true},
			{name:'price',index:'price',width:220,fixed:true,align:"center",sortable:true},
			{name:'modify',index:'modify',width:140,fixed:true,align:"center",sortable: false}
		],
		sortname: '',
		sortorder: '',
		rowNum: 100,
		width: '1270',
		height: '350',
		shrinkToFit: true
	});
	
	// define template list grid
	$("#cpanel_manage_server_template_list_grid").jqGrid({
		datatype: "local",
		colNames:[la['NAME'],
			  ''],
		colModel:[
			{name:'name',index:'name',width:613,fixed:true,align:"left",sortable:true},
			{name:'modify',index:'modify',width:70,fixed:true,align:"center",sortable: false}
		],
		sortname: '',
		sortorder: '',
		rowNum: 100,
		width: '700',
		height: '350',
		shrinkToFit: true
	});
	
	// define log list grid
	$("#cpanel_manage_server_log_list_grid").jqGrid({
		datatype: "local",
		colNames:[la['NAME'],
			  la['MODIFIED'],
			  la['SIZE_MB'],
			  ''],
		colModel:[
			{name:'name',index:'name',width:603,fixed:true,align:"left",sortable:true},
			{name:'modified',index:'modified',width:350,fixed:true,align:"center",sortable:true},
			{name:'size_mb',index:'size_mb',width:200,fixed:true,align:"center",sortable:true},
			{name:'modify',index:'modify',width:100,fixed:true,align:"center",sortable: false}
		],
		sortname: '',
		sortorder: '',
		rowNum: 100,
		width: '1290',
		height: '330',
		shrinkToFit: true
	});
	 $("#settings_object_dispenser_sensor_calibration_list_grid").jqGrid({
        datatype: "local",
        colNames: ["HZ", "Level", "Volum",""],
        colModel: [{
            name: "hz",
            index: "hz",
            width: 111,
            align: "center",
            sortable: !0,
            sorttype: "int"
        }, {
            name: "Level",
            index: "Level",
            align: "center",
            width: 110,
            sortable: !1
        }, {
            name: "volum",
            index: "volum",
            width: 100,
            align: "center",
            sortable: !1
        }, {
            name: "modify",
            index: "modify",
            width: 30,
            align: "center",
            sortable: !1
        }],
        width: "285",
        height: "306",
        rowNum: 100,
        shrinkToFit: !1
    });
	
	// hide jqgrid close button
	$(".ui-jqgrid-titlebar-close").hide();
	
	 if (document.getElementById("cpanel_staff_manag_list_grid") != undefined)
		{

			$("#cpanel_staff_manag_list_grid").jqGrid({
				url:'func/fn_cpanel.staff.php?cmd=select_staff&from='+$("#dialog_boarding_holidayfromdate").val()+'&to='+$("#dialog_boarding_holidaytodate").val(),
				datatype: "json",
				colNames:[la['SERVICE_ID'],la['CLIENT_ID'],la['STAFFID'],la['COMPANY'],la['SITE_LOCATION'],la['SCHEDULE_DATE'],'SIM',la['OBJECT'],la['IMEI'],la['WORK'],la['TYPES_WORK'],la['UNDER_WARRENTY'],la['NOTE'],'','','',''],
				colModel:[
				{name:'service_id',index:'service_id',width:50},
				{name:'client',index:'client',width:80,align:"center"},
				{name:'staff',index:'staff',width:80,align:"center"},
				{name:'company',index:'company',width:90,align:"center"},
				{name:'site_location',index:'site_location',width:90,align:"center"},
				{name:'schedule_date',index:'schedule_date',width:60,align:"center"},
				{name:'sim_id',index:'sim_id',width:60,align:"center"},
				{name:'objectname',index:'objectname',width:60,align:"center"},
				{name:'imei',index:'imei',width:60,align:"center"},
				{name:'works',index:'works',width:80,align:"center"},
				{name:'work_type',index:'work_type',width:80,sortable: false,align:"center" ,hidden:true},
				{name:'warrenty',index:'warrenty',width:50,sortable: false},
				{name:'reason',index:'reason',width:150,sortable: false},
				{name:'modify',index:'modify',width:100,align:"center",sortable: false},
				{name:'client_id',index:'client_id',width:80,hidden:true},
				{name:'staff_id',index:'staff_id',width:80,hidden:true}
				,{name:'scroll_fix',index:'scroll_fix',width:13,sortable: false, fixed: true}
				],
				rowNum:100,
				rowList:[100,200,300,400,500],
				pager: '#cpanel_staff_manag_list_grid_pager',
				sortname: 'id',
				sortorder: "asc",
				viewrecords: true,
				rownumbers: true,
				height: '400px',
				shrinkToFit: true
			});
			
			//updated by vetrivel.NR
			$("#cpanel_staff_manag_list_grid").setGridWidth($(window).width() -60 );
			$("#cpanel_staff_manag_list_grid").setGridHeight($(window).height() - 208);
			$(window).bind('resize', function() {$("#cpanel_staff_manag_list_grid").setGridWidth($(window).width() -60 );});
			$(window).bind('resize', function() {$("#cpanel_staff_manag_list_grid").setGridHeight($(window).height() - 208);});
		}

	    $("#cpanel_staff_manag_list_grid").jqGrid('navGrid','#cpanel_staff_manag_list_grid_pager',{	add:true,
	    	edit:false,																								
	    	del:false,
	    	search:false,
	    	addfunc: function (e) {staffAdd('open');$("#cpanel_staff_manag_old_service_list").trigger("reloadGrid");}	
	    });
	    
	    $("#cpanel_staff_manag_list_grid").navButtonAdd('#cpanel_staff_manag_list_grid_pager',{	caption: "", 
			title: la['ACTION'],
			buttonicon: 'ui-icon-action',
			onClickButton: function(){}, 
			position:"last",
			id: "cpanel_staff_manag_list_grid_action_menu_button"
			});

		// action menu
		$("#cpanel_staff_manag_list_grid_action_menu").menu({
			role: 'listbox'
		});
		$("#cpanel_staff_manag_list_grid_action_menu").hide();
		
		$("#cpanel_staff_manag_list_grid_action_menu_button").click(function() {
				$("#cpanel_staff_manag_list_grid_action_menu").toggle().position({
				my: "left bottom",
				at: "right-5 top-5",
				of: this
			});
					
			$(document).one("click", function() {
				$("#cpanel_staff_manag_list_grid_action_menu").hide();
			});
			
			return false;
		});
		
		// updated by ismani
		 if (document.getElementById("cpanel_staff_manag_emp_list_grid") != undefined)
			{
		    $("#cpanel_staff_manag_emp_list_grid").jqGrid({
			url:'func/fn_cpanel.staff.php?cmd=select_staff_emp',
			datatype: "json",
			colNames:[la['STAFFID'],la['STAFFNAME'],la['GENDER'],la['DOB'],la['DOJ'],la['QUALIFICATION'],la['EXPERIANCE'],la['MOBILENO'],la['ADDRESS'],la['ID'],la['DESIGNATION'],la['NOTE'],'','',''],
			colModel:[
			{name:'staff_id',index:'staff_id',width:50,align:true},
			{name:'staff_name',index:'staff_name',width:80,align:"center"},
			{name:'gender',index:'gender',width:80,align:"center"},
			{name:'dob',index:'dob',width:80,align:"center"},
			{name:'jod',index:'jod',width:60,align:"center"},
			{name:'qualification',index:'qualification',width:80},
			{name:'experiance',index:'experiance',width:80,align:"center"},
			{name:'mobile',index:'mobile',width:60,align:"center"},
			{name:'address',index:'address',width:80,align:"center"},		
			{name:'card_id',index:'card_id',width:80,align:"center"},			
			{name:'desi_name',index:'desi_name',width:80,align:"center"},
			{name:'note',index:'note',width:60,align:"center"},
			{name:'modify',index:'modify',width:100,align:"center",sortable: false},	
			{name:'desi_id',index:'desi_id',width:80,align:"center",hidden:true},
			{name:'scroll_fix',index:'scroll_fix',width:13,sortable: false, fixed: true}
			],
		     rowNum: 1000,    
		     height: "253"
		         
		 });
		    $("#cpanel_staff_manag_old_service_list").jqGrid({
			url:'func/fn_cpanel.staff.php?cmd=staff_client_service_list&client=Select&imei=Select&staffid=Select',
			datatype: "json",
			colNames:[la['OBJECT'],la['WORK'],''],
			colModel:[
			{name:'staff_id',index:'staff_id',width:90,align:true},
			{name:'staff_name',index:'staff_name',width:90,align:"center"},
			{name:'gender',index:'gender',width:30,align:"center"}
			],
		     rowNum: 1000,    
		     height: "500"
		         
		 });
		 }

	
	}
	
	
	// define billing plan list grid

    
    

	


function loadGridList(list)
{
        switch (list)
	{
		case "custom_maps":
			var data = {
				cmd: 'load_custom_map_list'
			};
			
			$.ajax({
				type: "POST",
				url: "func/fn_cpanel.server.php",
				data: data,
				dataType: 'json',
				success: function(result)
				{
					var list_id = $("#cpanel_manage_server_custom_map_list_grid");
					var list_data = [];
					
					list_id.clearGridData(true);
					
					for (var i = 0; i < result.length; i++)
					{
						var map_id = result[i].map_id;
						var name = result[i].name;
						var active = result[i].active;
						var type = result[i].type;
						var url = result[i].url;
						
						if (active == 'true')
						{
							active= '<img src="theme/images/tick-green.svg" />';
						}
						else
						{
							active= '<img src="theme/images/remove-red.svg" style="width:12px;" />';
						}
						
						var modify = '<a href="#" onclick="customMapProperties(\''+map_id+'\');" title="'+la['EDIT']+'"><img src="theme/images/edit.svg" /></a>';
						modify += '<a href="#" onclick="customMapDelete(\''+map_id+'\');" title="'+la['DELETE']+'"><img src="theme/images/remove3.svg" /></a>';
						
						list_id.jqGrid('addRowData',i,{name: name, active: active, type: type, url: url, modify: modify});
					}
					
					list_id.setGridParam({sortname:'name', sortorder: 'asc'}).trigger('reloadGrid');
				}
			});
			break;
		
		case "billing":
			var data = {
				cmd: 'load_billing_plan_list'
			};
			
			$.ajax({
				type: "POST",
				url: "func/fn_cpanel.server.php",
				data: data,
				dataType: 'json',
				success: function(result)
				{
					var select = document.getElementById('dialog_user_billing_plan_add_plan');
					select.options.length = 0; // clear out existing items
					
					var list_id = $("#cpanel_manage_server_billing_plan_list_grid");
					var list_data = [];
					
					list_id.clearGridData(true);
					
					for (var i = 0; i < result.length; i++)
					{
						var plan_id = result[i].plan_id;
						var name = result[i].name;
						var active = result[i].active;
						var objects = result[i].objects;
						var period = result[i].period;
						var period_type = result[i].period_type;
						var price = result[i].price;
						
						if (period == 1)
						{
							var period_type = la[period_type.slice(0,-1).toUpperCase()];	
						}
						else
						{
							var period_type = la[period_type.toUpperCase()];	
						}
						
						period = period + ' ' + period_type.toLowerCase();
						
						if (active == 'true')
						{
							select.options.add(new Option(name, plan_id));
                                                }
						
						if (active == 'true')
						{
							active= '<img src="theme/images/tick-green.svg" />';
						}
						else
						{
							active= '<img src="theme/images/remove-red.svg" style="width:12px;" />';
						}
						
						var modify = '<a href="#" onclick="billingPlanProperties(\''+plan_id+'\');" title="'+la['EDIT']+'"><img src="theme/images/edit.svg" /></a>';
						modify += '<a href="#" onclick="billingPlanDelete(\''+plan_id+'\');" title="'+la['DELETE']+'"><img src="theme/images/remove3.svg" /></a>';
						
						list_id.jqGrid('addRowData',i,{name: name, active: active, objects: objects, period: period, price: price, modify: modify});
					}
					
					sortSelectList(select);
					
					select.options.add(new Option(la['CUSTOM'], ""), 0);
					
					list_id.setGridParam({sortname:'name', sortorder: 'asc'}).trigger('reloadGrid');
				}
			});
			break;
		
		case "templates":
			var data = {
				cmd: 'load_template_list'
			};
			
			$.ajax({
				type: "POST",
				url: "func/fn_cpanel.server.php",
				data: data,
				dataType: 'json',
				success: function(result)
				{
					var list_id = $("#cpanel_manage_server_template_list_grid");
					var list_data = [];
					
					list_id.clearGridData(true);
					
					for (var i = 0; i < result.length; i++)
					{
						var name = result[i].name;
						
						var name_ = la['TEMPLATE_' + name.toUpperCase()];
						
						var modify = '<a href="#" onclick="templateProperties(\''+name+'\');" title="'+la['EDIT']+'"><img src="theme/images/edit.svg" /></a>';
						
						list_id.jqGrid('addRowData',i,{name: name_, modify: modify});
					}
					
					list_id.setGridParam({sortname:'name', sortorder: 'asc'}).trigger('reloadGrid');
				}
			});
			
			break;
		case "logs":
			var data = {
				cmd: 'load_log_list'
			};
			
			$.ajax({
				type: "POST",
				url: "func/fn_cpanel.server.php",
				data: data,
				dataType: 'json',
				success: function(result)
				{
					var list_id = $("#cpanel_manage_server_log_list_grid");
					var list_data = [];
					
					list_id.clearGridData(true);
					
					for (var i = 0; i < result.length; i++)
					{
						var name = result[i].name;
						var modified = result[i].modified;
						var size = result[i].size;
						var modify = '<a href="#" onclick="logOpen(\''+name+'\');" title="'+la['OPEN']+'"><img src="theme/images/file.svg" /></a>';
						modify += '<a href="#" onclick="logDelete(\''+name+'\');" title="'+la['DELETE']+'"><img src="theme/images/remove3.svg" /></a>';
						
						list_id.jqGrid('addRowData',i,{name: name, modified: modified, size_mb: size, modify: modify});
					}
					
					list_id.setGridParam({sortname:'name', sortorder: 'asc'}).trigger('reloadGrid');
				}
			});
			
			break;
	}
}

function initSelectList(list)
{
	switch (list)
	{
		case "object_device_list":
			var select_add = document.getElementById('dialog_object_add_device');
			var select_edit = document.getElementById('dialog_object_edit_device');
			select_add.options.length = 0; // clear out existing items
			select_edit.options.length = 0; // clear out existing items	
			for (var key in gsValues['device_list'])
			{
				var obj = gsValues['device_list'][key];
				select_add.options.add(new Option(obj.name, obj.value));
				select_edit.options.add(new Option(obj.name, obj.value));
			}
						
			break;
		case "privileges_list_super_admin":
			var select = document.getElementById('dialog_user_edit_account_privileges');
			select.options.length = 0; // clear out existing items
			
			select.options.add(new Option(la['VIEWER'], 'viewer'));
			select.options.add(new Option(la['USER'], 'user'));
			select.options.add(new Option(la['MANAGER'], 'manager'));
			select.options.add(new Option(la['ADMINISTRATOR'], 'admin'));
			select.options.add(new Option(la['SUPER_ADMINISTRATOR'], 'super_admin'));
		break;
		case "privileges_list_admin":
			var select = document.getElementById('dialog_user_edit_account_privileges');
			select.options.length = 0; // clear out existing items
			
			select.options.add(new Option(la['VIEWER'], 'viewer'));
			select.options.add(new Option(la['USER'], 'user'));
			select.options.add(new Option(la['MANAGER'], 'manager'));
			select.options.add(new Option(la['ADMINISTRATOR'], 'admin'));
		break;
		case "privileges_list_manager":
			var select = document.getElementById('dialog_user_edit_account_privileges');
			select.options.length = 0; // clear out existing items
			
			select.options.add(new Option(la['VIEWER'], 'viewer'));
			select.options.add(new Option(la['USER'], 'user'));
			select.options.add(new Option(la['MANAGER'], 'manager'));
		break;
		case "privileges_list_user":
			var select = document.getElementById('dialog_user_edit_account_privileges');
			select.options.length = 0; // clear out existing items
			
			select.options.add(new Option(la['VIEWER'], 'viewer'));
			select.options.add(new Option(la['USER'], 'user'));
		break;
		case "manager_list":
			if ((cpValues['privileges'] == 'super_admin') || (cpValues['privileges'] == 'admin'))
			{		
				var data = {
					cmd: 'load_manager_list'
				};
				
				$.ajax({
					type: "POST",
					url: "func/fn_cpanel.php",
					data: data,
					dataType: 'json',
					cache: false,
					success: function(result)
					{
						var select = document.getElementById('cpanel_manager_list');
						if (select)
						{
							select.options.length = 0; // clear out existing items
							select.options.add(new Option(la['ADMINISTRATOR'], 0));
							for (var key in result)
							{
								var obj = result[key];
								select.options.add(new Option(obj.username, key));
							}
						}
						
						document.getElementById('cpanel_manager_list').value = cpValues['manager_id'];
						
						var select = document.getElementById('dialog_user_edit_account_manager_id');
						if (select)
						{
							select.options.length = 0; // clear out existing items	
							select.options.add(new Option(la['NO_MANAGER'], 0));
							for (var key in result)
							{
								var obj = result[key];
								select.options.add(new Option(obj.username, key));
							}
						}
						
						var select = document.getElementById('dialog_object_add_manager_id');
						if (select)
						{
							select.options.length = 0; // clear out existing items	
							select.options.add(new Option(la['NO_MANAGER'], 0));
							for (var key in result)
							{
								var obj = result[key];
								select.options.add(new Option(obj.username, key));
							}
						}
						
						var select = document.getElementById('dialog_object_edit_manager_id');
						if (select)
						{
							select.options.length = 0; // clear out existing items	
							select.options.add(new Option(la['NO_MANAGER'], 0));
							for (var key in result)
							{
								var obj = result[key];
								select.options.add(new Option(obj.username, key));
							}
						}
					}
				});
			}
		break;
		case "fuel_sensor_list":
			var sfuel1 = document.getElementById('dialog_staff_manag_fuel1');
			var sfuel2 = document.getElementById('dialog_staff_manag_fuel2');
			sfuel1.options.length = 0; // clear out existing items
			sfuel2.options.length = 0; // clear out existing items	
			for (var key in gsValues['fuel_sensor_list'])
			{
				var obj = gsValues['fuel_sensor_list'][key];
				sfuel1.options.add(new Option(obj.name, obj.name));
				sfuel2.options.add(new Option(obj.name, obj.name));
			}
						
			break;
		case "accessories_list":
			// var access = document.getElementById('dialog_staff_manag_accessories');
			// access.options.length = 0; // clear out existing items
			// for (var key in gsValues['accessories_list'])
			// {
			// 	var obj = gsValues['accessories_list'][key];
			// 	access.options.add(new Option(obj.name, obj.name));
			// }
						
			break;
	}
}


function switchCPTab(name)
{
	document.getElementById("top_panel_button_user_list").className = "user-list-btn";
	document.getElementById("top_panel_button_object_list").className = "object-list-btn";
	
	if (document.getElementById("top_panel_button_unused_object_list") != undefined)
	{
		document.getElementById("top_panel_button_unused_object_list").className = "unused-object-list-btn";
	}
	
	if (document.getElementById("top_panel_button_manage_server") != undefined)
	{
		document.getElementById("top_panel_button_manage_server").className = "manage-server-btn";
	}
	
	if (document.getElementById("top_panel_button_billing_plan_list") != undefined)
	{
		document.getElementById("top_panel_button_billing_plan_list").className = "billing-plan-list-btn";
	}
	
	if (document.getElementById("top_panel_button_manag_staff") != undefined)
	{
		document.getElementById("top_panel_button_manag_staff").className = "manage-staff-btn";
	}
	if (document.getElementById("top_panel_button_inventory") != undefined)
	{
		document.getElementById('cpanel_inventory').style.display = 'none';
	}
	
	switch (name)
	{
		case "user_list":
			document.getElementById("top_panel_button_user_list").className = "user-list-btn active";

			document.getElementById('cpanel_user_list').style.display = '';
			document.getElementById('cpanel_object_list').style.display = 'none';
			document.getElementById('cpanel_staff_manag_list').style.display = 'none';
			
			if (document.getElementById("top_panel_button_unused_object_list") != undefined)
			{
				document.getElementById('cpanel_unused_object_list').style.display = 'none';
			}
			
			if (document.getElementById("top_panel_button_manage_server") != undefined)
			{
				document.getElementById('cpanel_manage_server').style.display = 'none';
			}
			
			if (document.getElementById("top_panel_button_billing_plan_list") != undefined)
			{
				document.getElementById('cpanel_billing_plan_list').style.display = 'none';
			}
			if (document.getElementById("top_panel_button_inventory") != undefined)
			{
				document.getElementById('cpanel_inventory').style.display = 'none';
			}
			
			break;
		case "object_list":
			document.getElementById("top_panel_button_object_list").className = "object-list-btn active";
		
			document.getElementById('cpanel_user_list').style.display = 'none';
			document.getElementById('cpanel_object_list').style.display = '';
			document.getElementById('cpanel_staff_manag_list').style.display = 'none';
			
			if (document.getElementById("top_panel_button_unused_object_list") != undefined)
			{
				document.getElementById('cpanel_unused_object_list').style.display = 'none';
			}
			
			if (document.getElementById("top_panel_button_manage_server") != undefined)
			{
				document.getElementById('cpanel_manage_server').style.display = 'none';
			}
			
			if (document.getElementById("top_panel_button_billing_plan_list") != undefined)
			{
				document.getElementById('cpanel_billing_plan_list').style.display = 'none';
			}
			if (document.getElementById("top_panel_button_inventory") != undefined)
			{
				document.getElementById('cpanel_inventory').style.display = 'none';
			}
			
			break;
		case "unused_object_list":
			document.getElementById("top_panel_button_unused_object_list").className = "unused-object-list-btn active";
	
			document.getElementById('cpanel_user_list').style.display = 'none';
			document.getElementById('cpanel_object_list').style.display = 'none';
			document.getElementById('cpanel_staff_manag_list').style.display = 'none';
			
			if (document.getElementById("top_panel_button_unused_object_list") != undefined)
			{
				document.getElementById('cpanel_unused_object_list').style.display = '';
			}
			
			if (document.getElementById("top_panel_button_manage_server") != undefined)
			{
				document.getElementById('cpanel_manage_server').style.display = 'none';
			}
			
			if (document.getElementById("top_panel_button_billing_plan_list") != undefined)
			{
				document.getElementById('cpanel_billing_plan_list').style.display = 'none';
			}
			if (document.getElementById("top_panel_button_inventory") != undefined)
			{
				document.getElementById('cpanel_inventory').style.display = 'none';
			}
			
			break;
		case "billing_plan_list":
			document.getElementById("top_panel_button_billing_plan_list").className = "billing-plan-list-btn active";
	
			document.getElementById('cpanel_user_list').style.display = 'none';
			document.getElementById('cpanel_object_list').style.display = 'none';
			document.getElementById('cpanel_staff_manag_list').style.display = 'none';
			
			if (document.getElementById("top_panel_button_unused_object_list") != undefined)
			{
				document.getElementById('cpanel_unused_object_list').style.display = 'none';
			}
			
			if (document.getElementById("top_panel_button_manage_server") != undefined)
			{
				document.getElementById('cpanel_manage_server').style.display = 'none';
			}
			
			if (document.getElementById("top_panel_button_billing_plan_list") != undefined)
			{
				document.getElementById('cpanel_billing_plan_list').style.display = '';
			}
			if (document.getElementById("top_panel_button_inventory") != undefined)
			{
				document.getElementById('cpanel_inventory').style.display = 'none';
			}
			
			break;
		case "manage_server":
			document.getElementById("top_panel_button_manage_server").className = "manage-server-btn active";
						
			document.getElementById('cpanel_user_list').style.display = 'none';
			document.getElementById('cpanel_object_list').style.display = 'none';
			document.getElementById('cpanel_staff_manag_list').style.display = 'none';
			
			if (document.getElementById("top_panel_button_unused_object_list") != undefined)
			{
				document.getElementById('cpanel_unused_object_list').style.display = 'none';
			}
			
			if (document.getElementById("top_panel_button_manage_server") != undefined)
			{
				document.getElementById('cpanel_manage_server').style.display = '';
			}
			
			if (document.getElementById("top_panel_button_billing_plan_list") != undefined)
			{
				document.getElementById('cpanel_billing_plan_list').style.display = 'none';
			}
			if (document.getElementById("top_panel_button_inventory") != undefined)
			{
				document.getElementById('cpanel_inventory').style.display = 'none';
			}
			
			break;
		case "staff_manag":
			document.getElementById("top_panel_button_manag_staff").className = "manage-staff-btn active";
						
			document.getElementById('cpanel_user_list').style.display = 'none';
			document.getElementById('cpanel_object_list').style.display = 'none';
			document.getElementById('cpanel_staff_manag_list').style.display = 'block';
			
			if (document.getElementById("top_panel_button_unused_object_list") != undefined)
			{
				document.getElementById('cpanel_unused_object_list').style.display = 'none';
			}
			
			if (document.getElementById("top_panel_button_manage_server") != undefined)
			{
				document.getElementById('cpanel_manage_server').style.display = 'none';
			}
			
			if (document.getElementById("top_panel_button_billing_plan_list") != undefined)
			{
				document.getElementById('cpanel_billing_plan_list').style.display = 'none';
			}

			if (document.getElementById("top_panel_button_inventory") != undefined)
			{
				document.getElementById('cpanel_inventory').style.display = 'none';
			}
			
			break;

			case "inventory_manag": 
			document.getElementById("top_panel_button_inventory").className = "inventory-btn active";
						
			document.getElementById('cpanel_user_list').style.display = 'none';
			document.getElementById('cpanel_object_list').style.display = 'none';
			document.getElementById('cpanel_staff_manag_list').style.display = 'none';
			
			if (document.getElementById("top_panel_button_unused_object_list") != undefined)
			{
				document.getElementById('cpanel_unused_object_list').style.display = 'none';
			}
			
			if (document.getElementById("top_panel_button_manage_server") != undefined)
			{
				document.getElementById('cpanel_manage_server').style.display = 'none';
			}

			if (document.getElementById("top_panel_button_inventory") != undefined)
			{
				document.getElementById('cpanel_inventory').style.display = '';
			}
			
			if (document.getElementById("top_panel_button_billing_plan_list") != undefined)
			{
				document.getElementById('cpanel_billing_plan_list').style.display = 'none';
			}
			
			break;
	}
}




var aid=0;

function notification(savecancel)
{
	if(savecancel=="save")
	{
		
		var datefrom = $("#dialog_allocate_date_fromdaily").val();
	 	var dateto = $("#dialog_allocate_date_todaily").val();
	 	
	   	var tfh = $("#dialog_allocate_hour_fromdaily").val();
	 	var tfm = $("#dialog_allocate_minute_fromdaily").val();
	 	var tth = $("#dialog_allocate_hour_todaily").val();
	 	var ttm = $("#dialog_allocate_minute_todaily").val();
	 	
	 	
		if($("#dialog_allocate_date_fromdaily").val()=="" || $("#dialog_allocate_date_todaily").val()=="")
		{
			 notifyBox("error", la.ERROR, la.PLSENTERVALIDTIME);
			 return ;
		}
	
		
		if($("#txtinfo").val()=="")
		{
			 notifyBox("error", la.ERROR, la.NOTIFICATIONEMPTY);
			 return ;
		}
		

		
		   var dataaa = {
                cmd: "save_notification",
                aid: aid,
                active: "Active",
                date_from:datefrom,
                date_to:dateto,
                tfh:tfh,
                tfm:tfm,
                tth:tth,
                ttm:ttm,
                info:$("#txtinfo").val(),
                infourl:$("#txtinfourl").val(),
                userid:$("#ddluser").val()
            };
		   
		   $.ajax({
                type: "POST",
                url: "func/fn_cpanel.php",
                data: dataaa,
                success: function(o) {
                    switch (o.trim()) {
                      
                        case "NO":
                         var p = "error";
                        var r = la.ERROR;
                        var q = la.DIFFOBJECTORHOTSPOT;
                        notifyBox(p, r, q);
                        break;
                        case "OK":
                        	notification('cancel');
                        	notification('search');
                     	break;
                     	default:
                        var p = "error";
                        var r = la.ERROR;
                        notifyBox(p, r, o);
                        break;
                    }                 
                }
            });
	}
	else if(savecancel =="cancel")
	{

		$("#ddluser").val("All");
		$("#txtinfo").val("");
		$("#txtinfourl").val("");
		
		$("#dialog_allocate_date_fromdaily").val("");
	 	$("#dialog_allocate_date_todaily").val("");
	 	$("#dialog_allocate_hour_fromdaily").val("00");
	 	$("#dialog_allocate_minute_fromdaily").val("00");
	 	
	 	$("#dialog_allocate_hour_todaily").val("23");
	 	$("#dialog_allocate_minute_todaily").val("59");

	 	aid=0;
	 	notification('search');
	}
	else if(savecancel =="search")
	{  	
		   $("#notification_grid").jqGrid("clearGridData");
		   $("#notification_grid").setGridParam({url: "func/fn_cpanel.php?cmd=select_notification" });
		   $("#notification_grid").trigger("reloadGrid");
	}
	
	
} 

function notificationedit(eid)
{

	   var dataaa = { cmd: "edit_notification", event_id: eid};
	  	$.ajax({
                type: "POST",
                url: "func/fn_cpanel.php",
                data: dataaa,
                success: function(o) {
         
	  	aid=eid;
	  	
	  	$("#txtinfo").val(o["rows"][0][0]["info"]);
	  	$("#txtinfourl").val(o["rows"][0][0]["url"]);
	  	
		$("#dialog_allocate_date_fromdaily").val(o["rows"][0][0]["date_from"]);
	 	$("#dialog_allocate_date_todaily").val(o["rows"][0][0]["date_to"]);
	 	
	 	$("#dialog_allocate_hour_fromdaily").val(o["rows"][0][0]["tfh"]);
	 	$("#dialog_allocate_minute_fromdaily").val(o["rows"][0][0]["tfm"]);
	 	
	 	$("#dialog_allocate_hour_todaily").val(o["rows"][0][0]["tth"]);
	 	$("#dialog_allocate_minute_todaily").val(o["rows"][0][0]["ttm"]);
	 	
	 	if(o["rows"][0][0]["user_id"]!=0)		
	 	$("#ddluser").val(o["rows"][0][0]["user_id"]);
	 	else
	 	$("#ddluser").val('All');

	  	}
            });
	
}

function notificationdelete(deptcode)
{
	
	confirmDialog(la['ARE_YOU_SURE_YOU_WANT_TO_CLEAR_GEOCODER_CACHE'], function(response){
		if (response)
		{
			 var dat = {cmd: "delete_notification",uid:deptcode};
			 $.ajax({
	                type: "POST",
	                url: "func/fn_cpanel.php",
	                data: dat,
	                success: function(o) {
	                    switch (o.trim()) {
	                        default: var p = "error";
	                        var r = la.ERROR;
	                        var q = o;notifyBox(p, r, q);
	                        notification('cancel');
	                        notification('search');
	                        break;
	                        case "OK":
	                        	notification('cancel');
	                        	notification('search');
	                        	notifyDialog(la['GEOCODER_CACHE_CLEARED']);
	                        break;
	                    }                 
	                }
	            });
		
		}
	});
	
	confirmDialog(la['ARE_YOU_SURE_YOU_WANT_TO_DELETE'], function(response){
	if (response)
	{
	 var dat = {cmd: "delete_notification",uid:deptcode};
		$.ajax({
                type: "POST",
                url: "func/fn_cpanel.php",
                data: dat,
                success: function(o) {
                    switch (o.trim()) {
                        default: var p = "error";
                        var r = la.ERROR;
                        var q = o;notifyBox(p, r, q);
                        notification('cancel');
                        notification('search');
                        break;
                        case "OK":
                        	notification('cancel');
                        	notification('search');
                        break;
                    }                 
                }
            });
   }
	})
}


function notifyBox(b, d, c, a) {
	notifyDialog(c);
	return;
    $.pnotify({
        title: d,
        text: c,
        type: b,
        opacity: 0.8,
        closer_hover: false,
        sticker_hover: false,
        hide: a
    })
}
