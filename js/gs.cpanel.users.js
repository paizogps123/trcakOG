function userAdd(cmd)
{
	switch (cmd)
	{
		case "open":
			if(cpValues['accessSettings']['access_settings_user']['add']!=true){
				notifyDialog(la['NO_PERMISSION']);
				return;
			}
			document.getElementById('dialog_user_add_email').value = '';
			$("#dialog_user_edit").dialog("open");
			// $("#dialog_user_add").dialog("open");
			var data = {
				cmd: 'load_report_list'
			};
			
		   $.ajax({
				type: "POST",
				url: "func/fn_cpanel.users.php",
				dataType: 'json',
				data: data,
				success: function(r)
				{		

					if (cpValues['privileges'] == 'super_admin')
					{
						initSelectList("privileges_list_super_admin");
					}
					else if (cpValues['privileges'] == 'admin')
					{
						initSelectList("privileges_list_admin");
						
					}
					else
					{
						if (cpValues['privileges'] == 'manager')
						{
							initSelectList("privileges_list_user");
						}
					}
					document.getElementById('add_user_gst_documentview').style.display = 'none';
					document.getElementById("dialog_user_edit_account_tab").click();

					document.getElementById('adduser_register').style.display = "block";
					document.getElementById('adduser_save').style.display = "none";
					document.getElementById('adduser_loginuser').style.display = "none";

					document.getElementById('adduser_register_con').style.display = "block";
					document.getElementById('adduser_save_con').style.display = "none";
					document.getElementById('adduser_loginuser_con').style.display = "none";

					// document.getElementById('adduser_save_set').style.display = "none";
					// document.getElementById('adduser_loginuser_set').style.display = "none";
					// document.getElementById('adduser_register_set').style.display = "block";

					if(cpValues['privileges']=='super_admin'){
						document.getElementById('dialog_user_edit_billing_plans_tab').style.display = "none";
					}
					document.getElementById('dialog_user_edit_subaccounts_tab').style.display = "none";
					document.getElementById('dialog_user_edit_objects_tab').style.display = "none";
					document.getElementById('dialog_user_edit_usage_tab').style.display = "none";
					document.getElementById('dialog_user_add_send_display').style.display = "block";
					document.getElementById('dialog_user_edit_account_active').checked =false;
					document.getElementById('dialog_user_edit_account_ambulance').checked = false;
					document.getElementById('dialog_user_edit_account_staff_manag').checked = false;
					document.getElementById('dialog_user_edit_account_username').value = "";
					document.getElementById('dialog_user_edit_account_email').value = "";
					document.getElementById('dialog_user_edit_account_password').value = "";
					document.getElementById('dialog_user_edit_account_privileges').value = "user";
					if(cpValues['privileges']=='super_admin'){
						document.getElementById('dialog_user_edit_account_manager_id').value = "0";
					}
					document.getElementById('dialog_user_edit_account_expire').checked = false;
					document.getElementById('dialog_user_edit_account_expire_dt').value = "";
					document.getElementById('dialog_user_edit_account_history').value = "true";
					document.getElementById('dialog_user_edit_account_reports').value = "true";
					document.getElementById('dialog_user_edit_account_sos').value = "false";
					document.getElementById('dialog_user_edit_account_boarding').value = "true";
					document.getElementById('dialog_user_edit_account_sendcommand').value = "false";
					document.getElementById('dialog_user_edit_account_rilogbook').value = "false";
					document.getElementById('dialog_user_edit_account_object_control').value = "false";
					document.getElementById('dialog_user_edit_account_image_gallery').value = "false";
					document.getElementById('dialog_user_edit_account_chat').value = "false";
					document.getElementById('dialog_user_edit_account_live_tripreport').value = "false";
					var select = document.getElementById('dialog_generat_report_list');
					multiselectClear(select);
					var selgroup=getGroupsreportArray(r['report_list'],r['report_group']);
					// select.options.length = 0; // clear out existing items
					multiselectSetGroups(select, selgroup);	

					 for ( var i = 0, len = select.options.length; i < len; i++ ) {
					 	var re=select.options[i].value;

								select.options[i].selected = false;
				        }
					loadcpanel_report();
					if(cpValues['privileges']=='super_admin'){
						document.getElementById('dialog_user_edit_account_obj_add').value ="trial";
						document.getElementById('dialog_user_edit_account_obj_limit').value ="false";
						document.getElementById('dialog_user_edit_account_obj_limit_num').disabled =true;
						document.getElementById('dialog_user_edit_account_obj_days').value ="false";
						document.getElementById('dialog_user_edit_account_obj_days_dt').disabled ="false";
					}
					document.getElementById('dialog_user_edit_account_obj_edit').value ="true";
					document.getElementById('dialog_user_edit_account_obj_history_clear').value ="true";
					document.getElementById('dialog_user_edit_account_dtc').value ="true";
					document.getElementById('dialog_user_edit_account_subaccounts').value ="true";
					document.getElementById('dialog_user_edit_account_sms_gateway_server').value ="false";
					document.getElementById('dialog_user_edit_api_active').value ="false";
					document.getElementById('dialog_user_edit_api_key').value ="";
					document.getElementById('dialog_user_edit_places_markers').value ="";
					document.getElementById('dialog_user_edit_places_routes').value ="";
					document.getElementById('dialog_user_edit_places_zones').value ="";
					document.getElementById('dialog_user_edit_usage_email_daily').value ="";
					document.getElementById('dialog_user_edit_usage_sms_daily').value ="";
					document.getElementById('dialog_user_edit_usage_api_daily').value ="";
					document.getElementById('dialog_user_key').value ="";
					document.getElementById('dialog_user_ip').value ="";
					document.getElementById('chkspeed').checked =false;

					document.getElementById('user_category').value ="";
					document.getElementById('user_client_name').value ="";
					document.getElementById('user_client_gstn_no').checked =false;
					document.getElementById('user_client_gstn_no_txt').disabled =true;
					document.getElementById('user_client_gstn_no_txt').value ="";
					document.getElementById('user_document_img').value ="";
					document.getElementById('user_address').value ="";
					document.getElementById('user_emailid').value ="";
					document.getElementById('user_phone_number').value ="";
					document.getElementById('user_bank_name').value ="";
					document.getElementById('user_branck_name').value ="";
					document.getElementById('user_account_no').value ="";
					document.getElementById('user_ifsc').value ="";
					document.getElementById('user_billing_name').value ="";
					document.getElementById('user_billing_address').value ="";
					document.getElementById('user_billing_mode').value ="";
					document.getElementById('user_amc_checkbox').checked =false;
					document.getElementById('user_amc_checkbox_txt').value ="";
					document.getElementById('user_billing_cycle').value ="";
					document.getElementById('user_commercial_mailid').value ="";
					document.getElementById('user_add_name').value ="";
					document.getElementById('user_add_phone').value ="";
					document.getElementById('user_add_emailid').value ="";
					document.getElementById('user_add_designation').value ="";
					document.getElementById('user_add_description').value ="";
					document.getElementById('dialog_user_edit_account_comment').value ="";
					document.getElementById('dialog_user_add_send').checked =false;

					validateUserSettings();

					document.getElementById('settings_user_add').checked=false;
					document.getElementById('settings_user_edit').checked=false;
					document.getElementById('settings_user_delete').checked=false;
				
					document.getElementById('settings_subuser_add').checked=false;
					document.getElementById('settings_subuser_edit').checked=false;
					document.getElementById('settings_subuser_delete').checked=false;

					document.getElementById('settings_object_add').checked=false;
					document.getElementById('settings_object_edit').checked=false;
					document.getElementById('settings_object_delete').checked=false;
				
					document.getElementById('settings_group_add').checked=false;
					document.getElementById('settings_group_edit').checked=false;
					document.getElementById('settings_group_delete').checked=false;
				
					document.getElementById('settings_event_add').checked=false;
					document.getElementById('settings_event_edit').checked=false;
					document.getElementById('settings_event_delete').checked=false;
				
					document.getElementById('settings_zone_add').checked=false;
					document.getElementById('settings_zone_edit').checked=false;
					document.getElementById('settings_zone_delete').checked=false;

					document.getElementById('settings_route_add').checked=false;
					document.getElementById('settings_route_edit').checked=false;
					document.getElementById('settings_route_delete').checked=false;
				
					document.getElementById('settings_marker_add').checked=false;
					 document.getElementById('settings_marker_edit').checked=false;
					document.getElementById('settings_marker_delete').checked=false;
				
					document.getElementById('settings_duplicate_add').checked=false;
					document.getElementById('settings_duplicate_edit').checked=false;
					 document.getElementById('settings_duplicate_delete').checked=false;
				
					document.getElementById('settings_clr_history_add').checked=false;
					document.getElementById('settings_clr_history_edit').checked=false;
					document.getElementById('settings_clr_history_delete').checked=false;
					
					document.getElementById("settings_user_add").disabled = true;
					document.getElementById("settings_user_edit").disabled = true;
					document.getElementById("settings_user_delete").disabled = true;

					document.getElementById("settings_object_add").disabled = true;

					document.getElementById("settings_duplicate_edit").disabled = true;

					document.getElementById("settings_clr_history_delete").disabled = true;
				}
			});
			break;
		case "register":
			if(cpValues['accessSettings']['access_settings_user']['add']!=true){
				notifyDialog(la['NO_PERMISSION']);
				return;
			}
			var send = document.getElementById('dialog_user_add_send').checked;
			var user_active = document.getElementById('dialog_user_edit_account_active').checked;
			var user_ambulance = document.getElementById('dialog_user_edit_account_ambulance').checked;
			var user_staffmanag = document.getElementById('dialog_user_edit_account_staff_manag').checked;
			var user_username = document.getElementById('dialog_user_edit_account_username').value;
			var user_email = document.getElementById('dialog_user_edit_account_email').value;
			var user_password = document.getElementById('dialog_user_edit_account_password').value;
			var user_acc_privileges = document.getElementById('dialog_user_edit_account_privileges').value;
			if(cpValues['privileges']=='super_admin'){
				var user_managerid = document.getElementById('dialog_user_edit_account_manager_id').value;
			}else{
				var user_managerid=cpValues['user_id'];
			}
			var user_acc_expir = document.getElementById('dialog_user_edit_account_expire').checked;
			var user_acc_expirdt = document.getElementById('dialog_user_edit_account_expire_dt').value;
			var user_acc_history = document.getElementById('dialog_user_edit_account_history').value;
			var user_acc_report = document.getElementById('dialog_user_edit_account_reports').value;
			var user_acc_sos = document.getElementById('dialog_user_edit_account_sos').value;
			var user_acc_boarding = document.getElementById('dialog_user_edit_account_boarding').value;
			var user_acc_sendcommand = document.getElementById('dialog_user_edit_account_sendcommand').value;
			var user_acc_rilogbook = document.getElementById('dialog_user_edit_account_rilogbook').value;
			var user_acc_object = document.getElementById('dialog_user_edit_account_object_control').value;
			var user_acc_image = document.getElementById('dialog_user_edit_account_image_gallery').value;
			var user_acc_livetrip = document.getElementById('dialog_user_edit_account_live_tripreport').value;
			var user_acc_chat = document.getElementById('dialog_user_edit_account_chat').value;
			var user_select_report = multiselectGetValues(document.getElementById("dialog_generat_report_list"));

			if(cpValues['privileges']=='super_admin'){
				var user_obj_add = document.getElementById('dialog_user_edit_account_obj_add').value;
			}else{
				var user_obj_add ='trail';
			}
			if(cpValues['privileges']=='super_admin'){
				var user_obj_limit = document.getElementById('dialog_user_edit_account_obj_limit').value;
			}else{
				var user_obj_limit ='false';
			}
			if(cpValues['privileges']=='super_admin'){
				var user_obj_limit_num = document.getElementById('dialog_user_edit_account_obj_limit_num').disabled;
			}else{
				var user_obj_limit_num ='true';
			}
			if(cpValues['privileges']=='super_admin'){
				var user_obj_days = document.getElementById('dialog_user_edit_account_obj_days').value;
			}else{
				var user_obj_days ='false';
			}
			if(cpValues['privileges']=='super_admin'){
				var user_obj_daysdt = document.getElementById('dialog_user_edit_account_obj_days_dt').disabled;
			}else{
				var user_obj_daysdt ='false';
			}
			
			var user_obj_edit = document.getElementById('dialog_user_edit_account_obj_edit').value;
			var user_obj_clrhsitory = document.getElementById('dialog_user_edit_account_obj_history_clear').value;
			var user_acc_dtc = document.getElementById('dialog_user_edit_account_dtc').value;
			var user_subaccounts = document.getElementById('dialog_user_edit_account_subaccounts').value;
			var user_smsgw = document.getElementById('dialog_user_edit_account_sms_gateway_server').value;
			var user_api_active = document.getElementById('dialog_user_edit_api_active').value;
			var user_api_key = document.getElementById('dialog_user_edit_api_key').value;
			var user_place_marker = document.getElementById('dialog_user_edit_places_markers').value;
			var user_place_routes = document.getElementById('dialog_user_edit_places_routes').value;
			var user_places_zone = document.getElementById('dialog_user_edit_places_zones').value ;
			var user_email_daily = document.getElementById('dialog_user_edit_usage_email_daily').value;
			var user_sms_daily = document.getElementById('dialog_user_edit_usage_sms_daily').value;
			var user_api_daily = document.getElementById('dialog_user_edit_usage_api_daily').value;
			var user_user_key = document.getElementById('dialog_user_key').value ;
			var user_user_ip = document.getElementById('dialog_user_ip').value;
			var user_speed = document.getElementById('chkspeed').checked;

			var user_category = document.getElementById('user_category').value;
			var user_cli_name = document.getElementById('user_client_name').value;
			var user_gstnno = document.getElementById('user_client_gstn_no').checked;
			var user_gst_notxt = document.getElementById('user_client_gstn_no_txt').value;
			var user_docimg = document.getElementById('user_document_img').value;
			var user_address = document.getElementById('user_address').value;
			var user_emailid = document.getElementById('user_emailid').value;
			var user_phone = document.getElementById('user_phone_number').value;
			var user_bname = document.getElementById('user_bank_name').value;
			var user_brname = document.getElementById('user_branck_name').value;
			var user_acc_no = document.getElementById('user_account_no').value;
			var user_ifsc = document.getElementById('user_ifsc').value;
			var user_billname = document.getElementById('user_billing_name').value;
			var user_billaddress = document.getElementById('user_billing_address').value;
			var user_billmode = document.getElementById('user_billing_mode').value;
			var user_amcck=document.getElementById('user_amc_checkbox').checked;
			var user_amcnu=document.getElementById('user_amc_checkbox_txt').value;
			var user_billingcycle=document.getElementById('user_billing_cycle').value;
			var user_com_mailid = document.getElementById('user_commercial_mailid').value;
			var user_add_name = document.getElementById('user_add_name').value;
			var user_add_phone = document.getElementById('user_add_phone').value;
			var user_add_emailid = document.getElementById('user_add_emailid').value;
			var user_add_designation = document.getElementById('user_add_designation').value;
			var user_add_description = document.getElementById('user_add_description').value;
			var user_comment = document.getElementById('dialog_user_edit_account_comment').value;

			var gs_user_settings = {
				add: document.getElementById('settings_user_add').checked,
				edit: document.getElementById('settings_user_edit').checked,
				delete: document.getElementById('settings_user_delete').checked
			};			
			gs_user_settings = JSON.stringify(gs_user_settings);

			var gs_subuser_settings = {
				add: document.getElementById('settings_subuser_add').checked,
				edit: document.getElementById('settings_subuser_edit').checked,
				delete: document.getElementById('settings_subuser_delete').checked
			};			
			gs_subuser_settings = JSON.stringify(gs_subuser_settings);

			var gs_object_settings = {
				add: document.getElementById('settings_object_add').checked,
				edit: document.getElementById('settings_object_edit').checked,
				delete: document.getElementById('settings_object_delete').checked
			};			
			gs_object_settings = JSON.stringify(gs_object_settings);

			var gs_group_settings = {
				add: document.getElementById('settings_group_add').checked,
				edit: document.getElementById('settings_group_edit').checked,
				delete: document.getElementById('settings_group_delete').checked
			};			
			gs_group_settings = JSON.stringify(gs_group_settings);

			var gs_event_settings = {
				add: document.getElementById('settings_event_add').checked,
				edit: document.getElementById('settings_event_edit').checked,
				delete: document.getElementById('settings_event_delete').checked
			};			
			gs_event_settings = JSON.stringify(gs_event_settings);

			var gs_zone_settings = {
				add: document.getElementById('settings_zone_add').checked,
				edit: document.getElementById('settings_zone_edit').checked,
				delete: document.getElementById('settings_zone_delete').checked
			};			
			gs_zone_settings = JSON.stringify(gs_zone_settings);

			var gs_route_settings = {
				add: document.getElementById('settings_route_add').checked,
				edit: document.getElementById('settings_route_edit').checked,
				delete: document.getElementById('settings_route_delete').checked
			};			
			gs_route_settings = JSON.stringify(gs_route_settings);

			var gs_marker_settings = {
				add: document.getElementById('settings_marker_add').checked,
				edit: document.getElementById('settings_marker_edit').checked,
				delete: document.getElementById('settings_marker_delete').checked
			};			
			gs_marker_settings = JSON.stringify(gs_marker_settings);

			var gs_duplicate_settings = {
				add: document.getElementById('settings_duplicate_add').checked,
				edit: document.getElementById('settings_duplicate_edit').checked,
				delete: document.getElementById('settings_duplicate_delete').checked
			};			
			gs_duplicate_settings = JSON.stringify(gs_duplicate_settings);

			var gs_clr_history_settings = {
				add: document.getElementById('settings_clr_history_add').checked,
				edit: document.getElementById('settings_clr_history_edit').checked,
				delete: document.getElementById('settings_clr_history_delete').checked
			};			
			gs_clr_history_settings = JSON.stringify(gs_clr_history_settings);

			if(user_username=='' && user_password==''){
				notifyDialog(la['USERNAME_PASSWORD_SENT']);
				return;
			}

			if(user_email!=''){
			if(!isEmailValid(user_email))
				{
					notifyDialog(la['THIS_EMAIL_IS_NOT_VALID']);
					return;
				}
			}
			// if(user_emailid!=''){
			// 	if(!isEmailValid(user_emailid))
			// 	{
			// 		notifyDialog(la['THIS_USER_EMAIL_IS_NOT_VALID']);
			// 		return;
			// 	}
			// }
			
			var data = {
				cmd: 'register_user',
				send:send,
				u_active:user_active,
				u_ambulance:user_ambulance,
				u_staffmanag:user_staffmanag,
				u_username:user_username,
				u_email:user_email,
				u_password:user_password,
				u_acc_privileges:user_acc_privileges,
				u_managerid:user_managerid,
				u_acc_expir:user_acc_expir,
				u_acc_expirdt:user_acc_expirdt,
				u_acc_history:user_acc_history,
				u_acc_report:user_acc_report,
				u_acc_sos:user_acc_sos,
				u_acc_boarding:user_acc_boarding,
				u_acc_sendcommand:user_acc_sendcommand,
				u_acc_rilogbook:user_acc_rilogbook,
				u_acc_object:user_acc_object,
				u_acc_image:user_acc_image,
				u_acc_livetrip:user_acc_livetrip,
				u_acc_chat:user_acc_chat,
				u_select_report:user_select_report,
				u_obj_add:user_obj_add,
				u_obj_limit:user_obj_limit,
				u_obj_limit_num:user_obj_limit_num,
				u_obj_days:user_obj_days,
				u_obj_daysdt:user_obj_daysdt,
				u_obj_edit:user_obj_edit,
				u_obj_clrhsitory:user_obj_clrhsitory,
				u_acc_dtc:user_acc_dtc,
				u_subaccounts:user_subaccounts,
				u_smsgw:user_smsgw,
				u_api_active:user_api_active,
				u_api_key:user_api_key,
				u_place_marker:user_place_marker,
				u_place_routes:user_place_routes,
				u_places_zone:user_places_zone,
				u_email_daily:user_email_daily,
				u_sms_daily:user_sms_daily,
				u_api_daily:user_api_daily,
				u_user_key:user_user_key,
				u_user_ip:user_user_ip,
				u_speed:user_speed,
				
				u_category:user_category,
				u_cli_name:user_cli_name,
				u_gstnno:user_gstnno,
				u_gst_notxt:user_gst_notxt,
				u_docimg:user_docimg,
				u_address:user_address,
				u_emailid:user_emailid,
				u_phone:user_phone,
				u_bname:user_bname,
				u_brname:user_brname,
				u_acc_no:user_acc_no,
				u_ifsc:user_ifsc,
				u_billname:user_billname,
				u_billaddress:user_billaddress,
				u_billmode:user_billmode,
				u_amc_ch:user_amcck,
				u_amc_num:user_amcnu,
				u_billingcycle:user_billingcycle,
				u_com_mailid:user_com_mailid,
				u_add_name:user_add_name,
				u_add_phone:user_add_phone,
				u_add_emailid:user_add_emailid,
				u_add_designation:user_add_designation,
				u_add_description:user_add_description,
				u_comment:user_comment,
				manager_id: cpValues['manager_id'],

				u_user_settings:gs_user_settings,
				u_subuser_settings:gs_subuser_settings,
				u_object_settings:gs_object_settings,
				u_group_settings:gs_group_settings,
				u_event_settings:gs_event_settings,
				u_zone_settings:gs_zone_settings,
				u_route_settings:gs_route_settings,
				u_marker_settings:gs_marker_settings,
				u_duplicate_settings:gs_duplicate_settings,
				u_clr_history_settings:gs_clr_history_settings
			};
			
		   $.ajax({
				type: "POST",
				url: "func/fn_cpanel.users.php",
				data: data,
				success: function(result)
				{
					if (result == 'OK')
					{
						initStats();
						initSelectList('manager_list');
						
						$('#cpanel_user_list_grid').trigger("reloadGrid");
						$("#dialog_user_edit").dialog("close");
					}
					else if (result == 'ERROR_EMAIL_EXISTS')
					{
						notifyDialog(la['THIS_EMAIL_ALREADY_EXISTS']);
					}
					else if (result == 'ERROR_USERNAME_EXISTS')
					{
						notifyDialog(la['THIS_USERNAME_ALREADY_EXISTS']);
					}
					else if (result == 'ERROR_NOT_SENT')
					{
						notifyDialog(la['CANT_SEND_EMAIL'] + ' ' + la['CONTACT_ADMINISTRATOR']);
					}
					else if (result == 'NO_PERMISSION')
					{
						notifyDialog(la['NO_PERMISSION']);
					}
				}
			});
			break;
		case "cancel":
			$("#dialog_user_add").dialog("close");
			break;
	}
}

function upAdduserDoc(){
	// a bit dirty sollution, maybe will make better in the feature :)
	document.getElementById('load_file').addEventListener('change', uploadAdduserGSTFile, false);
	document.getElementById('load_file').click();
}

function uploadAdduserGSTFile(evt)
{
	var files = evt.target.files;
	var reader = new FileReader();
	reader.onloadend = function(event)
	{
		var result = event.target.result;
		var tt=result;
		
		if ((files[0].type != ('image/png')) && (files[0].type != ('application/pdf')))
		{
			notifyDialog(la['FILE_TYPE_MUST_BE_PNG_OR_SVG']);
			return;
		}
		
		var image = new Image();
		image.src = result;

			if (image.src.includes("image/png"))
			{
				var url = "func/fn_upload.php?file=adduser_gst_png";
			}
			else if(image.src.includes("application/pdf")){
				var url = "func/fn_upload.php?file=adduser_gst_pdf";
			}
			else if(image.src.includes("application/doc"))
			{
				var url = "func/fn_upload.php?file=adduser_gst_doc";
			}
			
			$.ajax({
				url: url,
				type: "POST",
				data: result,
				processData: false,
				contentType: false,
				success: function (result) {
					if (result != '')
					{
						// document.getElementById('cpanel_supplier_documentfile').src = result + "?t=" + new Date().getTime();
						document.getElementById('user_document_img').value = result;
						document.getElementById('addusersuccessMsg').value = 'Image Uploaded';
                    }
					else
					{
						notifyDialog(la['IMAGE_UPLOAD_FAILED']);
					}
				}
			});
		
		document.getElementById('load_file').value = '';
	}
	reader.readAsDataURL(files[0]);
	
	this.removeEventListener('change', uploadLogoFile, false);
}

function userLogin(id)
{
	var data = {
		cmd: 'login_user',
		id: id
	};
	
	$.ajax({
		type: "POST",
		url: "func/fn_cpanel.users.php",
		data: data,
		success: function(result)
		{
			if (result == 'OK')
			{
				location.href = 'Dashboard.php';
			}
		}
	});
}

function userEditLogin()
{
	userLogin(cpValues['user_edit_id']);
}

function userEdit(cmd)
{
	switch (cmd)
	{
		default:
			// if(cpValues['accessSettings']['access_settings_user']['edit']!=true){
			// 	notifyDialog(la['NO_PERMISSION']);
			// 	return;
			// }
			cpValues['user_edit_id'] = cmd;
			
			var data = {
				cmd: 'load_user_data',
				user_id: cpValues['user_edit_id']
			};
			
			$.ajax({
				type: "POST",
				url: "func/fn_cpanel.users.php",
				data: data,
				dataType: 'json',
				cache: false,
				success: function(result)
				{		
						
					// set values						
					validateUserSettings();				
					document.getElementById('adduser_register').style.display = "none";
					document.getElementById('adduser_save').style.display = "block";
					document.getElementById('adduser_loginuser').style.display = "block";
					document.getElementById('adduser_register_con').style.display = "none";
					document.getElementById('adduser_save_con').style.display = "block";
					document.getElementById('adduser_loginuser_con').style.display = "block";

					if(cpValues['accessSettings']['access_settings_user']['edit']!=true){
						document.getElementById('adduser_save').style.display = "none";
						document.getElementById('adduser_save_con').style.display = "none";
					}

					if(cpValues['privileges']=='super_admin'){
						document.getElementById('dialog_user_edit_subaccounts_tab').style.display = "block";
						document.getElementById('dialog_user_edit_objects_tab').style.display = "block";
						document.getElementById('dialog_user_edit_billing_plans_tab').style.display = "block";
					}
					document.getElementById('dialog_user_edit_usage_tab').style.display = "block";
					document.getElementById('dialog_user_add_send_display').style.display = "none";

					// if(result['manager_id']==cpValues['user_edit_id'] && cpValues['privileges']!='super_admin'){
					if((cpValues['user_edit_id']==cpValues['user_id'])){
						document.getElementById("dialog_user_edit_settings_tab").style.display = "none";
						document.getElementById("displaymanager_settings").style.display = "none";
						document.getElementById("displaymanager_report").style.display = "none";
					}else{
						document.getElementById("dialog_user_edit_settings_tab").style.display = "block";
						document.getElementById("displaymanager_settings").style.display = "block";
						document.getElementById("displaymanager_report").style.display = "block";
					}
					document.getElementById('dialog_user_edit_account_active').checked = strToBoolean(result['active']);					
					document.getElementById('dialog_user_edit_account_expire').checked = strToBoolean(result['account_expire']);					
					if (document.getElementById('dialog_user_edit_account_expire').checked == true)
					{
						document.getElementById('dialog_user_edit_account_expire_dt').value = result['account_expire_dt'];
                    }
					else
					{
						document.getElementById('dialog_user_edit_account_expire_dt').value = '';
					}					
					document.getElementById('dialog_user_edit_account_ambulance').checked = strToBoolean(result['ambulance']);
					document.getElementById('dialog_user_edit_account_staff_manag').checked = strToBoolean(result['staff_manag']);
					document.getElementById('dialog_user_edit_account_inventory_manag').checked = strToBoolean(result['inventory_manag']);
					document.getElementById('dialog_user_edit_account_username').value = result['username'];
					document.getElementById('dialog_user_edit_account_email').value = result['email'];
					document.getElementById('dialog_user_edit_account_password').value = '';
					var privileges = result['privileges'];
					if (cpValues['privileges'] == 'super_admin')
					{
						initSelectList("privileges_list_super_admin");
					}
					else if (cpValues['privileges'] == 'admin')
					{
						if (privileges['type'] == 'admin')
						{
							initSelectList("privileges_list_admin");
						}
						else
						{
							initSelectList("privileges_list_manager");
						}
					}
					else
					{
						if (privileges['type'] == 'manager')
						{
							initSelectList("privileges_list_manager");
						}
						else
						{
							initSelectList("privileges_list_user");
						}
					}
					
					var select = document.getElementById('dialog_generat_report_list');
					multiselectClear(select);
					var selgroup=getGroupsreportArray(result['report_list'],result['report_group']);
					// select.options.length = 0; // clear out existing items
					multiselectSetGroups(select, selgroup);	

					 for ( var i = 0, len = select.options.length; i < len; i++ ) {
					 	var re=select.options[i].value;

							if(result['select_report'][re]["active"]=='A')
							{
								select.options[i].selected = true;
							}
							else
							{
								select.options[i].selected = false;
							}
				        }
					loadcpanel_report();	

					$("#dialog_user_edit_account_staff_manag").prop("disabled", false);
					if (privileges['type'] == 'viewer' || privileges['type'] == 'user' || privileges['type'] == 'subuser' )
					{
						$("#dialog_user_edit_account_staff_manag").prop("disabled", true);
					}
					
					document.getElementById('dialog_user_edit_account_privileges').value = privileges['type'];
					
					if (cpValues['privileges'] != 'manager')
					{
						document.getElementById('dialog_user_edit_account_manager_id').value = result['manager_id'];
						document.getElementById('dialog_user_edit_account_manager_billing').value = result['manager_billing'];
						
						document.getElementById('dialog_user_edit_account_obj_add').value = result['obj_add'];
						document.getElementById('dialog_user_edit_account_obj_limit').value = result['obj_limit'];
						
						if (result['obj_limit'] == 'true')
						{
							document.getElementById('dialog_user_edit_account_obj_limit_num').value = result['obj_limit_num'];
						}
						else
						{
							document.getElementById('dialog_user_edit_account_obj_limit_num').value = '';
						}
						
						document.getElementById('dialog_user_edit_account_obj_days').value = result['obj_days'];
						
						if (result['obj_days'] == 'true')
						{
							document.getElementById('dialog_user_edit_account_obj_days_dt').value = result['obj_days_dt'];
						}
						else
						{
							document.getElementById('dialog_user_edit_account_obj_days_dt').value = '';
						}
					}
					
					document.getElementById('dialog_user_edit_account_obj_edit').value = result['obj_edit'];
					document.getElementById('dialog_user_edit_account_obj_history_clear').value = result['obj_history_clear'];
					
					document.getElementById('dialog_user_edit_account_history').value = privileges['history'];
					document.getElementById('dialog_user_edit_account_reports').value = privileges['reports'];
					document.getElementById('dialog_user_edit_account_sos').value = privileges['sos'];
					document.getElementById('dialog_user_edit_account_boarding').value = privileges['boarding'];
					document.getElementById('dialog_user_edit_account_sendcommand').value = privileges['sendcommand'];
					document.getElementById('dialog_user_edit_account_rilogbook').value = privileges['rilogbook'];
					document.getElementById('dialog_user_edit_account_dtc').value = privileges['dtc'];
					document.getElementById('dialog_user_edit_account_object_control').value = privileges['object_control'];
					document.getElementById('dialog_user_edit_account_image_gallery').value = privileges['image_gallery'];					
					document.getElementById('dialog_user_edit_account_chat').value = privileges['chat'];
					document.getElementById('dialog_user_edit_account_subaccounts').value = privileges['subaccounts'];
					
					document.getElementById('dialog_user_edit_account_sms_gateway_server').value = result['sms_gateway_server'];

					document.getElementById('dialog_user_edit_api_active').value = result['api'];
					document.getElementById('dialog_user_edit_api_key').value = result['api_key'];
					
					document.getElementById('dialog_user_edit_places_markers').value = result['places_markers'];
					document.getElementById('dialog_user_edit_places_routes').value = result['places_routes'];
					document.getElementById('dialog_user_edit_places_zones').value = result['places_zones'];
					
					document.getElementById('dialog_user_edit_usage_email_daily').value = result['usage_email_daily'];
					document.getElementById('dialog_user_edit_usage_sms_daily').value = result['usage_sms_daily'];
					document.getElementById('dialog_user_edit_usage_api_daily').value = result['usage_api_daily'];
					
					var info = result['info'];
					
					document.getElementById('user_category').value =info['u_cat'];
					document.getElementById('user_client_name').value =info['name'];
					document.getElementById('user_client_gstn_no').checked =info['u_gstno_chk'];
					if(info['u_gstno_chk']==true){
						document.getElementById('user_client_gstn_no_txt').disabled =false;
					}else{
						document.getElementById('user_client_gstn_no_txt').disabled =true;
					}

					document.getElementById('user_document_img').disabled = false;
					document.getElementById('addusersuccessMsg').disabled = true;
					if((info['u_gstdoc']!='') || (info['u_gstdoc']!='document1')){
					document.getElementById('add_user_gst_documentview').style.display = 'block';
					document.getElementById('add_user_gst_documentview').href = "data/user/addusergst/"+info['u_gstdoc'];
					}else{
						document.getElementById('add_user_gst_documentview').style.display = 'none';
					}
					document.getElementById('user_client_gstn_no_txt').value =info['u_gstno'];
					// document.getElementById('user_document_img').value =info['u_gstdoc'];
					document.getElementById('user_address').value =info['address'];
					document.getElementById('user_emailid').value =info['u_mail'];
					document.getElementById('user_phone_number').value =info['phone1'];
					document.getElementById('user_bank_name').value =info['u_bname'];
					document.getElementById('user_branck_name').value =info['u_brname'];
					document.getElementById('user_account_no').value =info['u_accno'];
					document.getElementById('user_ifsc').value =info['u_ifsc'];
					document.getElementById('user_billing_name').value =info['u_bill_name'];
					document.getElementById('user_billing_address').value =info['u_bill_add'];
					document.getElementById('user_billing_mode').value =info['u_bil_mobe'];
					document.getElementById('user_amc_checkbox').checked=info['u_amc_ch'];
					document.getElementById('user_amc_checkbox_txt').value=info['u_amc_num'];
					document.getElementById('user_billing_cycle').value=info['u_billingcycle'];
					document.getElementById('user_commercial_mailid').value =info['u_caddress'];
					document.getElementById('user_add_name').value =info['u_aname'];
					document.getElementById('user_add_phone').value =info['u_aphoen'];
					document.getElementById('user_add_emailid').value =info['u_amail'];
					document.getElementById('user_add_designation').value =info['u_adesignation'];
					document.getElementById('user_add_description').value =info['u_adecriptoin'];

					document.getElementById('dialog_user_edit_account_comment').value = result['comment'];
					document.getElementById("ms-dialog_generat_report_list").style.width = "100%";
					
					// set values for later check while saving
					cpValues['user_edit_privileges'] = privileges['type'];
					
					document.getElementById('dialog_user_key').value = result['userapikey'];
					document.getElementById('dialog_user_ip').value = result['userapiip'];

					// user settings
					document.getElementById('settings_user_add').checked=result['access_settings_user']['add'];
					document.getElementById('settings_user_edit').checked=result['access_settings_user']['edit'];
					document.getElementById('settings_user_delete').checked=result['access_settings_user']['delete'];

					document.getElementById('settings_subuser_add').checked=result['access_settings_subuser']['add'];
					document.getElementById('settings_subuser_edit').checked=result['access_settings_subuser']['edit'];
					document.getElementById('settings_subuser_delete').checked=result['access_settings_subuser']['delete'];

					document.getElementById('settings_object_add').checked=result['access_settings_object']['add'];
					document.getElementById('settings_object_edit').checked=result['access_settings_object']['edit'];
					document.getElementById('settings_object_delete').checked=result['access_settings_object']['delete'];

					document.getElementById('settings_group_add').checked=result['access_settings_group']['add'];
					document.getElementById('settings_group_edit').checked=result['access_settings_group']['edit'];
					document.getElementById('settings_group_delete').checked=result['access_settings_group']['delete'];

					document.getElementById('settings_event_add').checked=result['access_settings_events']['add'];
					document.getElementById('settings_event_edit').checked=result['access_settings_events']['edit'];
					document.getElementById('settings_event_delete').checked=result['access_settings_events']['delete'];

					document.getElementById('settings_zone_add').checked=result['access_settings_zones']['add'];
					document.getElementById('settings_zone_edit').checked=result['access_settings_zones']['edit'];
					document.getElementById('settings_zone_delete').checked=result['access_settings_zones']['delete'];

					document.getElementById('settings_route_add').checked=result['access_settings_route']['add'];
					document.getElementById('settings_route_edit').checked=result['access_settings_route']['edit'];
					document.getElementById('settings_route_delete').checked=result['access_settings_route']['delete'];

					document.getElementById('settings_marker_add').checked=result['access_settings_markers']['add'];
					document.getElementById('settings_marker_edit').checked=result['access_settings_markers']['edit'];
					document.getElementById('settings_marker_delete').checked=result['access_settings_markers']['delete'];

					document.getElementById('settings_duplicate_add').checked=result['access_settings_duplicate']['add'];
					document.getElementById('settings_duplicate_edit').checked=result['access_settings_duplicate']['edit'];
					document.getElementById('settings_duplicate_delete').checked=result['access_settings_duplicate']['delete'];

					document.getElementById('settings_clr_history_add').checked=result['access_settings_clr_history']['add'];
					document.getElementById('settings_clr_history_edit').checked=result['access_settings_clr_history']['edit'];
					document.getElementById('settings_clr_history_delete').checked=result['access_settings_clr_history']['delete'];

					
					if(result['userapipermission'])
					{
							var vuserapipermission = result['userapipermission'].split(',');
							for(var iup=0;iup<vuserapipermission.length;iup++)
							{
								if(vuserapipermission[iup]=="Speed")
								{

									document.getElementById('chkspeed').checked=strToBoolean("true");
								}
								else
								{
									document.getElementById('chkspeed').checked=strToBoolean("false");
								}					
							}
					}
					else
					{
							document.getElementById('chkspeed').checked=strToBoolean("false");
					}
					
					// set object edit properties availability
					userEditCheck();
				}
			});
			
			$('#dialog_user_edit_object_list_grid').setGridParam({url:'func/fn_cpanel.users.php?cmd=load_user_object_list&id=' + cpValues['user_edit_id']});
			$('#dialog_user_edit_object_list_grid').trigger("reloadGrid");
			
			$('#dialog_user_edit_subaccount_list_grid').setGridParam({url:'func/fn_cpanel.users.php?cmd=load_user_subaccount_list&id=' + cpValues['user_edit_id']});
			$('#dialog_user_edit_subaccount_list_grid').trigger("reloadGrid");
			
			$('#dialog_user_edit_billing_plan_list_grid').setGridParam({url:'func/fn_cpanel.users.php?cmd=load_user_billing_plan_list&id=' + cpValues['user_edit_id']});
			$('#dialog_user_edit_billing_plan_list_grid').trigger("reloadGrid");
			
			$('#dialog_user_edit_usage_list_grid').setGridParam({url:'func/fn_cpanel.users.php?cmd=load_user_usage_list&id=' + cpValues['user_edit_id']});
			$('#dialog_user_edit_usage_list_grid').trigger("reloadGrid");
			
			$("#dialog_user_edit").dialog("open");
			break;
		case "save":
			if(cpValues['accessSettings']['access_settings_user']['edit']!=true){
				notifyDialog(la['NO_PERMISSION']);
				return;
			}
			var active = document.getElementById('dialog_user_edit_account_active').checked;
			var ambulance = document.getElementById('dialog_user_edit_account_ambulance').checked;
			var staff_manag = document.getElementById('dialog_user_edit_account_staff_manag').checked;
			var inventory_manag = document.getElementById('dialog_user_edit_account_inventory_manag').checked;
			var account_expire = document.getElementById('dialog_user_edit_account_expire').checked;
			var account_expire_dt = document.getElementById('dialog_user_edit_account_expire_dt').value;
			// expire account
			if (account_expire == true)
			{
				if (account_expire_dt == '')
				{
					notifyDialog(la['DATE_CANT_BE_EMPTY']);
					break;
				}
			}
			else
			{
                                account_expire_dt = '';
                        }
			
			var username = document.getElementById('dialog_user_edit_account_username').value;
			// username check
			if (username == '')
			{
				notifyDialog(la['USERNAME_CANT_BE_EMPTY']);
				break;
			}
			
			var password = document.getElementById('dialog_user_edit_account_password').value;
			
			var email = document.getElementById('dialog_user_edit_account_email').value;
			// email check
			if(!isEmailValid(email))
			{
				notifyDialog(la['THIS_EMAIL_IS_NOT_VALID']);
				break;
			}
			
			var report_list = multiselectGetValues(document.getElementById("dialog_generat_report_list"));

			var privileges_ = document.getElementById('dialog_user_edit_account_privileges').value;
			var history = strToBoolean(document.getElementById('dialog_user_edit_account_history').value);
			var reports = strToBoolean(document.getElementById('dialog_user_edit_account_reports').value);
			var sos = strToBoolean(document.getElementById('dialog_user_edit_account_sos').value);
			var boarding = strToBoolean(document.getElementById('dialog_user_edit_account_boarding').value);
			var sendcommand = strToBoolean(document.getElementById('dialog_user_edit_account_sendcommand').value);
			var rilogbook = strToBoolean(document.getElementById('dialog_user_edit_account_rilogbook').value);
			var dtc = strToBoolean(document.getElementById('dialog_user_edit_account_dtc').value);
			var object_control = strToBoolean(document.getElementById('dialog_user_edit_account_object_control').value);
			var image_gallery = strToBoolean(document.getElementById('dialog_user_edit_account_image_gallery').value);
			var live_tripreport =strToBoolean(document.getElementById('dialog_user_edit_account_live_tripreport').value);
			var chat = strToBoolean(document.getElementById('dialog_user_edit_account_chat').value);
			var subaccounts = strToBoolean(document.getElementById('dialog_user_edit_account_subaccounts').value);
			var sms_gateway_server = document.getElementById('dialog_user_edit_account_sms_gateway_server').value;
			
			var userapipermission="";
			var userapikey = document.getElementById('dialog_user_key').value;
			var userapiip = document.getElementById('dialog_user_ip').value;
			if( document.getElementById('chkspeed').checked)
			{
				userapipermission = "Speed";
			}
			
			var privileges = {
				type: privileges_,
				history: history,
				reports: reports,
				sos: sos,
				boarding: boarding,
				sendcommand: sendcommand,
				rilogbook: rilogbook,
				dtc: dtc,
				object_control: object_control,
				image_gallery: image_gallery,
				live_tripreport:live_tripreport,
				chat: chat,
				subaccounts: subaccounts
			};
			
			privileges = JSON.stringify(privileges);
			
			if (cpValues['privileges'] != 'manager')
			{
				var manager_id = document.getElementById('dialog_user_edit_account_manager_id').value;
				var manager_billing = document.getElementById('dialog_user_edit_account_manager_billing').value;
				
				var obj_add = document.getElementById('dialog_user_edit_account_obj_add').value;
				var obj_limit = document.getElementById('dialog_user_edit_account_obj_limit').value;
				var obj_limit_num = document.getElementById('dialog_user_edit_account_obj_limit_num').value;
				var obj_days = document.getElementById('dialog_user_edit_account_obj_days').value;
				var obj_days_dt = document.getElementById('dialog_user_edit_account_obj_days_dt').value;
				
				// account obj num check
				if (obj_limit == 'true')
				{
					if ((obj_limit_num < 1) || !isIntValid(obj_limit_num))
					{
						obj_limit_num = 0;
					}
                                }
				else
				{
					obj_limit_num = 0;
				}
				
				// account obj dt check
				if (obj_days == 'true')
				{
					if (obj_days_dt == '')
					{
						notifyDialog(la['DATE_CANT_BE_EMPTY']);
						break;
					}
				}
				else
				{
					obj_days_dt = '';
				}
			}
			else
			{
				var manager_id = '';
				var manager_billing = '';
				
				var obj_add = '';
				var obj_limit = '';
				var obj_limit_num = '';
				var obj_days = '';
				var obj_days_dt = '';
			}
			
			var obj_edit = document.getElementById('dialog_user_edit_account_obj_edit').value;
			var obj_history_clear = document.getElementById('dialog_user_edit_account_obj_history_clear').value;
			
			var api = document.getElementById('dialog_user_edit_api_active').value;
			var api_key = document.getElementById('dialog_user_edit_api_key').value;
			
			var places_markers = document.getElementById('dialog_user_edit_places_markers').value;
			var places_routes = document.getElementById('dialog_user_edit_places_routes').value;
			var places_zones = document.getElementById('dialog_user_edit_places_zones').value;
			
			var usage_email_daily = document.getElementById('dialog_user_edit_usage_email_daily').value;
			var usage_sms_daily = document.getElementById('dialog_user_edit_usage_sms_daily').value;
			var usage_api_daily = document.getElementById('dialog_user_edit_usage_api_daily').value;
			
			var user_category = document.getElementById('user_category').value;
			var user_client_name = document.getElementById('user_client_name').value;
			var user_gstno_check = document.getElementById('user_client_gstn_no').checked;
			var user_gstno = document.getElementById('user_client_gstn_no_txt').value;
			var user_gst_doc = document.getElementById('user_document_img').value;
			var user_address = document.getElementById('user_address').value;
			var user_mailid = document.getElementById('user_emailid').value;
			var user_phone = document.getElementById('user_phone_number').value;
			var user_bank_name = document.getElementById('user_bank_name').value;
			var user_branch_name = document.getElementById('user_branck_name').value;
			var user_accountno = document.getElementById('user_account_no').value;
			var user_ifsc = document.getElementById('user_ifsc').value;
			var user_billing_name = document.getElementById('user_billing_name').value;
			var user_billing_address = document.getElementById('user_billing_address').value;
			var user_billing_mode = document.getElementById('user_billing_mode').value;
			var user_amcck=document.getElementById('user_amc_checkbox').checked;
			var user_amcnu=document.getElementById('user_amc_checkbox_txt').value;
			var user_billingcycle=document.getElementById('user_billing_cycle').value;
			var user_commercial_address = document.getElementById('user_commercial_mailid').value;
			var user_add_name = document.getElementById('user_add_name').value;
			var user_add_phone = document.getElementById('user_add_phone').value;
			var user_add_mailid = document.getElementById('user_add_emailid').value;
			var user_add_designation = document.getElementById('user_add_designation').value;
			var user_add_decription = document.getElementById('user_add_description').value;

			var info = {
				u_cat:user_category,
				name:user_client_name,
				u_gstno_chk:user_gstno_check,
				u_gstno:user_gstno,
				u_gstdoc:user_gst_doc,
				address:user_address,
				u_mail:user_mailid,
				phone1:user_phone,
				u_bname:user_bank_name,
				u_brname:user_branch_name,
				u_accno:user_accountno,
				u_ifsc:user_ifsc,
				u_bill_name:user_billing_name,
				u_bill_add:user_billing_address,
				u_bil_mobe:user_billing_mode,
				u_caddress:user_commercial_address,
				u_aname:user_add_name,
				u_aphoen:user_add_phone,
				u_amail:user_add_mailid,
				u_adesignation:user_add_designation,
				u_adecriptoin:user_add_decription,
				u_amc_ch:user_amcck,
				u_amc_num:user_amcnu,
				u_billingcycle:user_billingcycle
			};
			
			info = JSON.stringify(info);

			var gs_user_settings = {
				add: document.getElementById('settings_user_add').checked,
				edit: document.getElementById('settings_user_edit').checked,
				delete: document.getElementById('settings_user_delete').checked
			};			
			gs_user_settings = JSON.stringify(gs_user_settings);

			var gs_subuser_settings = {
				add: document.getElementById('settings_subuser_add').checked,
				edit: document.getElementById('settings_subuser_edit').checked,
				delete: document.getElementById('settings_subuser_delete').checked
			};			
			gs_subuser_settings = JSON.stringify(gs_subuser_settings);

			var gs_object_settings = {
				add: document.getElementById('settings_object_add').checked,
				edit: document.getElementById('settings_object_edit').checked,
				delete: document.getElementById('settings_object_delete').checked
			};			
			gs_object_settings = JSON.stringify(gs_object_settings);

			var gs_group_settings = {
				add: document.getElementById('settings_group_add').checked,
				edit: document.getElementById('settings_group_edit').checked,
				delete: document.getElementById('settings_group_delete').checked
			};			
			gs_group_settings = JSON.stringify(gs_group_settings);

			var gs_event_settings = {
				add: document.getElementById('settings_event_add').checked,
				edit: document.getElementById('settings_event_edit').checked,
				delete: document.getElementById('settings_event_delete').checked
			};			
			gs_event_settings = JSON.stringify(gs_event_settings);

			var gs_zone_settings = {
				add: document.getElementById('settings_zone_add').checked,
				edit: document.getElementById('settings_zone_edit').checked,
				delete: document.getElementById('settings_zone_delete').checked
			};			
			gs_zone_settings = JSON.stringify(gs_zone_settings);

			var gs_route_settings = {
				add: document.getElementById('settings_route_add').checked,
				edit: document.getElementById('settings_route_edit').checked,
				delete: document.getElementById('settings_route_delete').checked
			};			
			gs_route_settings = JSON.stringify(gs_route_settings);

			var gs_marker_settings = {
				add: document.getElementById('settings_marker_add').checked,
				edit: document.getElementById('settings_marker_edit').checked,
				delete: document.getElementById('settings_marker_delete').checked
			};			
			gs_marker_settings = JSON.stringify(gs_marker_settings);

			var gs_duplicate_settings = {
				add: document.getElementById('settings_duplicate_add').checked,
				edit: document.getElementById('settings_duplicate_edit').checked,
				delete: document.getElementById('settings_duplicate_delete').checked
			};			
			gs_duplicate_settings = JSON.stringify(gs_duplicate_settings);

			var gs_clr_history_settings = {
				add: document.getElementById('settings_clr_history_add').checked,
				edit: document.getElementById('settings_clr_history_edit').checked,
				delete: document.getElementById('settings_clr_history_delete').checked
			};			
			gs_clr_history_settings = JSON.stringify(gs_clr_history_settings);
			
			var comment = document.getElementById('dialog_user_edit_account_comment').value;
			
			// password change
			if (password.length > 0)
			{
				if (password.length >= 6)
				{
					confirmDialog(la['ARE_YOU_SURE_YOU_WANT_TO_CHANGE_USER_PASSWORD'], function(response){
						if (response)
						{
							responseSave();
						}
					});
				}
				else
				{
					notifyDialog(la['PASSWORD_LENGHT_AT_LEAST']);
					break;
				}
			}
			else
			{
				responseSave();
			}
		break;
	}
	
	function responseSave()
	{
                var data = {
			cmd: 'edit_user',
			id: cpValues['user_edit_id'],
			active: active,
			ambulance: ambulance,
			staff_manag: staff_manag,
			inventory_manag:inventory_manag,
			account_expire: account_expire,
			account_expire_dt: account_expire_dt,
			privileges: privileges,
			manager_id: manager_id,
			manager_billing: manager_billing,
			username: username,
			password: password,
			email: email,
			api: api,
			api_key: api_key,
			info: info,
			comment: comment,
			obj_add: obj_add,
			obj_limit: obj_limit,
			obj_limit_num: obj_limit_num,
			obj_days: obj_days,
			obj_days_dt: obj_days_dt,
			obj_edit: obj_edit,
			obj_history_clear: obj_history_clear,				
			sms_gateway_server: sms_gateway_server,
			places_markers: places_markers,
			places_routes: places_routes,
			places_zones: places_zones,
			usage_email_daily: usage_email_daily,
			usage_sms_daily: usage_sms_daily,
			usage_api_daily: usage_api_daily,
			userapikey:userapikey,
			userapiip:userapiip,
			userapipermission:userapipermission,
			report_list:report_list,

			u_user_settings:gs_user_settings,
			u_subuser_settings:gs_subuser_settings,
			u_object_settings:gs_object_settings,
			u_group_settings:gs_group_settings,
			u_event_settings:gs_event_settings,
			u_zone_settings:gs_zone_settings,
			u_route_settings:gs_route_settings,
			u_marker_settings:gs_marker_settings,
			u_duplicate_settings:gs_duplicate_settings,
			u_clr_history_settings:gs_clr_history_settings
		};
		
		$.ajax({
			type: "POST",
			url: "func/fn_cpanel.users.php",
			data: data,
			success: function(result)
			{
				if (result == 'OK')
				{
					initSelectList('manager_list');
					$("#dialog_generat_report_list option:selected").removeAttr("selected");
					
					if ((cpValues['user_edit_privileges'] == 'manager') && (privileges_ != 'manager') && (cpValues['manager_id'] != 0))
					{
						switchCPManager(0);
					}
					
					$("#dialog_user_edit").dialog("close");
				}
				else if (result == 'ERROR_USERNAME_EXISTS')
				{
					notifyDialog(la['THIS_USERNAME_ALREADY_EXISTS']);
				}
				else if (result == 'ERROR_EMAIL_EXISTS')
				{
					notifyDialog(la['THIS_EMAIL_ALREADY_EXISTS']);
				}else if(result == 'NO_PERMISSION'){
					notifyDialog(la['NO_PERMISSION']);
				}
			}
		});
        }
}

function userEditSettings(){
	var selected_privileges = document.getElementById('dialog_user_edit_account_privileges').value;
	document.getElementById("settings_user_add").disabled = false;
	document.getElementById("settings_user_edit").disabled = false;
	document.getElementById("settings_user_delete").disabled = false;

	document.getElementById("settings_object_add").disabled = false;

	document.getElementById("settings_duplicate_edit").disabled = false;
	
	document.getElementById("settings_clr_history_delete").disabled = false;
	switch (selected_privileges)
		{
			case "viewer":
				document.getElementById("settings_user_add").disabled = true;
				document.getElementById("settings_user_edit").disabled = true;
				document.getElementById("settings_user_delete").disabled = true;

				document.getElementById("settings_object_add").disabled = true;

				document.getElementById("settings_duplicate_edit").disabled = true;
				
				document.getElementById("settings_clr_history_delete").disabled = true;
			case "user":
				document.getElementById("settings_user_add").disabled = true;
				document.getElementById("settings_user_edit").disabled = true;
				document.getElementById("settings_user_delete").disabled = true;

				document.getElementById("settings_object_add").disabled = true;

				document.getElementById("settings_duplicate_edit").disabled = true;

				document.getElementById("settings_clr_history_delete").disabled = true;
			case "Company":
				document.getElementById("settings_user_add").disabled = true;
				document.getElementById("settings_user_edit").disabled = true;
				document.getElementById("settings_user_delete").disabled = true;

				document.getElementById("settings_object_add").disabled = true;

				document.getElementById("settings_duplicate_edit").disabled = true;

				document.getElementById("settings_clr_history_delete").disabled = true;	
		}
}

function userEditCheck()
{	
	var selected_privileges = document.getElementById('dialog_user_edit_account_privileges').value;
	
	userEditSettings();
	
	// prevent self user deactivation, expire account, level change
	if ((cpValues['user_id'] == cpValues['user_edit_id']))
	{
		document.getElementById('dialog_user_edit_account_active').disabled = true;
		document.getElementById('dialog_user_edit_account_expire').disabled = true;
		document.getElementById('dialog_user_edit_account_expire_dt').disabled = true;
		document.getElementById('dialog_user_edit_account_expire').checked = false;
		document.getElementById('dialog_user_edit_account_expire_dt').value = '';
		document.getElementById('dialog_user_edit_account_privileges').disabled = true;
	}
	else
	{
		document.getElementById('dialog_user_edit_account_active').disabled = false;
		document.getElementById('dialog_user_edit_account_expire').disabled = false;
		document.getElementById('dialog_user_edit_account_expire_dt').disabled = false;
		document.getElementById('dialog_user_edit_account_privileges').disabled = false;			
	}

	// expire account
	if (document.getElementById('dialog_user_edit_account_expire').checked == true)
	{
                document.getElementById('dialog_user_edit_account_expire_dt').disabled = false;
        }
	else
	{
		document.getElementById('dialog_user_edit_account_expire_dt').disabled = true;
	}
	
	// if not manager
	if (cpValues['privileges'] != 'manager')
	{
		switch (selected_privileges)
		{
			case "viewer":			
				document.getElementById('dialog_user_edit_account_manager_id').disabled = false;
				
				document.getElementById('dialog_user_edit_account_manager_billing').disabled = true;
				document.getElementById('dialog_user_edit_account_manager_billing').value = 'false';
				break;
			case "user":				
				document.getElementById('dialog_user_edit_account_manager_id').disabled = false;
				
				document.getElementById('dialog_user_edit_account_manager_billing').disabled = true;
				document.getElementById('dialog_user_edit_account_manager_billing').value = 'false';
				break;
			case "manager":
				document.getElementById('dialog_user_edit_account_manager_id').disabled = true;
				document.getElementById('dialog_user_edit_account_manager_id').value = 0;
				
				document.getElementById('dialog_user_edit_account_manager_billing').disabled = false;
				break;
			case "dealer":
				document.getElementById('dialog_user_edit_account_manager_id').disabled = true;
				document.getElementById('dialog_user_edit_account_manager_id').value = 0;
				
				document.getElementById('dialog_user_edit_account_manager_billing').disabled = false;
				break;
			case "admin":			
				document.getElementById('dialog_user_edit_account_manager_id').disabled = true;
				document.getElementById('dialog_user_edit_account_manager_id').value = 0;
				
				document.getElementById('dialog_user_edit_account_manager_billing').disabled = true;
				document.getElementById('dialog_user_edit_account_manager_billing').value = 'false';
				break;
			case "super_admin":			
				document.getElementById('dialog_user_edit_account_manager_id').disabled = true;
				document.getElementById('dialog_user_edit_account_manager_id').value = 0;
				
				document.getElementById('dialog_user_edit_account_manager_billing').disabled = true;
				document.getElementById('dialog_user_edit_account_manager_billing').value = 'false';
				break;
		}
		
		// if user has manager
		if (document.getElementById('dialog_user_edit_account_manager_id').value != 0)
		{
			document.getElementById('dialog_user_edit_account_obj_add').disabled = true;
			document.getElementById('dialog_user_edit_account_obj_add').value = 'false';
		}
		else
		{
			document.getElementById('dialog_user_edit_account_obj_add').disabled = false;
		}
		
		switch (document.getElementById('dialog_user_edit_account_obj_add').value)
		{
			case "true":
				document.getElementById('dialog_user_edit_account_obj_limit').disabled = false;
				
				if (document.getElementById('dialog_user_edit_account_obj_limit').value == 'true')
				{
					document.getElementById('dialog_user_edit_account_obj_limit_num').disabled = false;
				}
				else
				{
					document.getElementById('dialog_user_edit_account_obj_limit_num').disabled = true;
				}
				
				document.getElementById('dialog_user_edit_account_obj_days').disabled = false;
				
				if (document.getElementById('dialog_user_edit_account_obj_days').value == 'true')
				{
					document.getElementById('dialog_user_edit_account_obj_days_dt').disabled = false;
				}
				else
				{
					document.getElementById('dialog_user_edit_account_obj_days_dt').disabled = true;
				}
				
				break;
			case "false":
				document.getElementById('dialog_user_edit_account_obj_limit').disabled = true;
				document.getElementById('dialog_user_edit_account_obj_limit').value = 'false';
				document.getElementById('dialog_user_edit_account_obj_limit_num').disabled = true;
				document.getElementById('dialog_user_edit_account_obj_limit_num').value = '';
				document.getElementById('dialog_user_edit_account_obj_days').disabled = true;
				document.getElementById('dialog_user_edit_account_obj_days').value = 'false';
				document.getElementById('dialog_user_edit_account_obj_days_dt').disabled = true;
				document.getElementById('dialog_user_edit_account_obj_days_dt').value = '';
				break;
			case "trial":
				document.getElementById('dialog_user_edit_account_obj_limit').disabled = true;
				document.getElementById('dialog_user_edit_account_obj_limit').value = 'false';
				document.getElementById('dialog_user_edit_account_obj_limit_num').disabled = true;
				document.getElementById('dialog_user_edit_account_obj_limit_num').value = '';
				document.getElementById('dialog_user_edit_account_obj_days').disabled = true;
				document.getElementById('dialog_user_edit_account_obj_days').value = 'false';
				document.getElementById('dialog_user_edit_account_obj_days_dt').disabled = true;
				document.getElementById('dialog_user_edit_account_obj_days_dt').value = '';
				break;
		}
	}
}

function userDelete(id)
{
	if(cpValues['accessSettings']['access_settings_user']['delete']!=true){
		notifyDialog(la['NO_PERMISSION']);
		return;
	}
	confirmDialog(la['ARE_YOU_SURE_YOU_WANT_TO_DELETE'], function(response){
		if (response)
		{
			var data = {
				cmd: 'delete_user',
				id: id
			};
			
			$.ajax({
				type: "POST",
				url: "func/fn_cpanel.users.php",
				data: data,
				success: function(result)
				{
					if (result == 'OK')
					{
						initStats();
						initSelectList('manager_list');
							
						$('#cpanel_user_list_grid').trigger("reloadGrid");	
					}else if(result=='NO_PERMISSION'){
						notifyDialog(la['NO_PERMISSION'])
					}
				}
			});
		}
	});
}

function userActivateSelected()
{
	var users = $('#cpanel_user_list_grid').jqGrid ('getGridParam', 'selarrrow');
	
	if (users == '')
	{
		notifyDialog(la['NO_ITEMS_SELECTED']);
		return;
        }
	
	confirmDialog(la['ARE_YOU_SURE_YOU_WANT_TO_ACTIVATE_SELECTED_ITEMS'], function(response){
		if (response)
		{
			var data = {
				cmd: 'activate_selected_users',
				ids: users
			};
			
			$.ajax({
				type: "POST",
				url: "func/fn_cpanel.users.php",
				data: data,
				success: function(result)
				{
					if (result == 'OK')
					{
						initStats();
						initSelectList('manager_list');
							
						$('#cpanel_user_list_grid').trigger("reloadGrid");	
					}
				}
			});
		}
	});
}

function userDeactivateSelected()
{
	var users = $('#cpanel_user_list_grid').jqGrid ('getGridParam', 'selarrrow');
	
	if (users == '')
	{
		notifyDialog(la['NO_ITEMS_SELECTED']);
		return;
        }
	
	confirmDialog(la['ARE_YOU_SURE_YOU_WANT_TO_DEACTIVATE_SELECTED_ITEMS'], function(response){
		if (response)
		{
			var data = {
				cmd: 'deactivate_selected_users',
				ids: users
			};
			
			$.ajax({
				type: "POST",
				url: "func/fn_cpanel.users.php",
				data: data,
				success: function(result)
				{
					if (result == 'OK')
					{
						initStats();
						initSelectList('manager_list');
							
						$('#cpanel_user_list_grid').trigger("reloadGrid");	
					}
				}
			});
                }
	});
}

function userDeleteSelected()
{
	if(cpValues['accessSettings']['access_settings_user']['delete']!=true){
		notifyDialog(la['NO_PERMISSION']);
		return;
	}

	var users = $('#cpanel_user_list_grid').jqGrid ('getGridParam', 'selarrrow');
	
	if (users == '')
	{
		notifyDialog(la['NO_ITEMS_SELECTED']);
		return;
    }
	
	confirmDialog(la['ARE_YOU_SURE_YOU_WANT_TO_DELETE_SELECTED_ITEMS'], function(response){
		if (response)
		{
			var data = {
				cmd: 'delete_selected_users',
				ids: users
			};
			
			$.ajax({
				type: "POST",
				url: "func/fn_cpanel.users.php",
				data: data,
				success: function(result)
				{
					if (result == 'OK')
					{
						initStats();
						initSelectList('manager_list');
							
						$('#cpanel_user_list_grid').trigger("reloadGrid");	
					}else if(result=='NO_PERMISSION'){
						notifyDialog(la['NO_PERMISSION'])
					}
				}
			});
		}
	});
}

function userObjectAdd(cmd){
	switch (cmd)
	{
		case "open":
			$('#dialog_user_object_add_objects').tokenize().clear();
			$("#dialog_user_object_add").dialog("open");
			break;
		case "add":
			var imeis = $('#dialog_user_object_add_objects').tokenize().toArray();
			
			imeis = JSON.stringify(imeis);
			
			var data = {
				cmd: 'add_user_objects',
				user_id: cpValues['user_edit_id'],
				imeis: imeis
			};
			
		   $.ajax({
				type: "POST",
				url: "func/fn_cpanel.users.php",
				data: data,
				success: function(result)
				{
					if (result == 'OK')
					{
						$('#dialog_user_edit_object_list_grid').trigger("reloadGrid");
						$("#dialog_user_object_add").dialog("close");
					}
				}
			});
			break;
		case "cancel":
			$("#dialog_user_object_add").dialog("close");
			break;
	}
}

function userObjectDelete(imei){
	if(cpValues['accessSettings']['access_settings_object']['delete']!=true){
		notifyDialog(la['NO_PERMISSION']);
		return;
	}
	confirmDialog(la['ARE_YOU_SURE_YOU_WANT_TO_DELETE_THIS_OBJECT_FROM_USER_ACCOUNT'], function(response){
		if (response)
		{
			var data = {
				cmd: 'delete_user_object',
				user_id: cpValues['user_edit_id'],
				imei: imei
			};
			
			$.ajax({
				type: "POST",
				url: "func/fn_cpanel.users.php",
				data: data,
				success: function(result)
				{
					if (result == 'OK')
					{
						$('#dialog_user_edit_object_list_grid').trigger("reloadGrid");
					}
					else if(result=='NO_PERMISSION')
					{
						notifyDialog(la['NO_PERMISSION']);
					}
				}
			});
		}
	});
}

function userObjectActivateSelected()
{
        var objects = $('#dialog_user_edit_object_list_grid').jqGrid ('getGridParam', 'selarrrow');
	
	if (objects == '')
	{
		notifyDialog(la['NO_ITEMS_SELECTED']);
		return;
        }
	
	confirmDialog(la['ARE_YOU_SURE_YOU_WANT_TO_ACTIVATE_SELECTED_ITEMS'], function(response){
		if (response)
		{
			var data = {
				cmd: 'activate_selected_user_objects',
				user_id: cpValues['user_edit_id'],
				imeis: objects
			};
			
			$.ajax({
				type: "POST",
				url: "func/fn_cpanel.users.php",
				data: data,
				success: function(result)
				{
					if (result == 'OK')
					{
						initStats();
						initSelectList('manager_list');
							
						$('#dialog_user_edit_object_list_grid').trigger("reloadGrid");	
					}
				}
			});
		}
	});
}

function userObjectDeactivateSelected()
{
        var objects = $('#dialog_user_edit_object_list_grid').jqGrid ('getGridParam', 'selarrrow');
	
	if (objects == '')
	{
		notifyDialog(la['NO_ITEMS_SELECTED']);
		return;
        }
	
	confirmDialog(la['ARE_YOU_SURE_YOU_WANT_TO_DEACTIVATE_SELECTED_ITEMS'], function(response){
		if (response)
		{
			var data = {
				cmd: 'deactivate_selected_user_objects',
				user_id: cpValues['user_edit_id'],
				imeis: objects
			};
			
			$.ajax({
				type: "POST",
				url: "func/fn_cpanel.users.php",
				data: data,
				success: function(result)
				{
					if (result == 'OK')
					{
						initStats();
						initSelectList('manager_list');
							
						$('#dialog_user_edit_object_list_grid').trigger("reloadGrid");	
					}
				}
			});
		}
	});
}

function userObjectClearHistorySelected()
{
	if(cpValues['accessSettings']['access_settings_clr_history']['delete']!=true){
		notifyDialog(la['NO_PERMISSION']);
		return;
	}
    var objects = $('#dialog_user_edit_object_list_grid').jqGrid ('getGridParam', 'selarrrow');
	
	if (objects == '')
	{
		notifyDialog(la['NO_ITEMS_SELECTED']);
		return;
        }
	
	confirmDialog(la['ARE_YOU_SURE_YOU_WANT_TO_CLEAR_SELECTED_ITEMS_HISTORY_EVENTS'], function(response){
		if (response)
		{
			var data = {
				cmd: 'clear_history_selected_objects',
				user_id: cpValues['user_edit_id'],
				imeis: objects
			};
			
			$.ajax({
				type: "POST",
				url: "func/fn_cpanel.objects.php",
				data: data,
				success: function(result)
				{
					if (result == 'OK')
					{
						initStats();
						initSelectList('manager_list');
							
						$('#dialog_user_edit_object_list_grid').trigger("reloadGrid");	
					}
				}
			});
		}
	});
}

function userObjectDeleteSelected()
{
	if(cpValues['accessSettings']['access_settings_object']['delete']!=true){
		notifyDialog(la['NO_PERMISSION']);
		return;
	}
    var objects = $('#dialog_user_edit_object_list_grid').jqGrid ('getGridParam', 'selarrrow');
	
	if (objects == '')
	{
		notifyDialog(la['NO_ITEMS_SELECTED']);
		return;
        }
	
	confirmDialog(la['ARE_YOU_SURE_YOU_WANT_TO_DELETE_SELECTED_ITEMS'], function(response){
		if (response)
		{
			var data = {
				cmd: 'delete_selected_user_objects',
				user_id: cpValues['user_edit_id'],
				imeis: objects
			};
			
			$.ajax({
				type: "POST",
				url: "func/fn_cpanel.users.php",
				data: data,
				success: function(result)
				{
					if (result == 'OK')
					{
						initStats();
						initSelectList('manager_list');
							
						$('#dialog_user_edit_object_list_grid').trigger("reloadGrid");	
					}
					else if(result=='NO_PERMISSION'){
						notifyDialog(la['NO_PERMISSION']);
					}
				}
			});
		}
	});
}

function userSubaccountEdit(id)
{
	if(cpValues['accessSettings']['access_settings_subuser']['edit']!=true){
		notifyDialog(la['NO_PERMISSION']);
		return;
	}
	var username = document.getElementById('dialog_user_edit_subaccount_list_grid_username_' + id).value;
	var email = document.getElementById('dialog_user_edit_subaccount_list_grid_email_'+ id ).value;
	var password = document.getElementById('dialog_user_edit_subaccount_list_grid_password_'+ id ).value;
	
	// username check
	if (username == '')
	{
		notifyDialog(la['USERNAME_CANT_BE_EMPTY']);
		return;
	}
	
	// email check
	if(!isEmailValid(email))
	{
		notifyDialog(la['THIS_EMAIL_IS_NOT_VALID']);
		return;
	}
	
	// password change
	if (password.length > 0)
	{
		if (password.length >= 6)
		{
			confirmDialog(la['ARE_YOU_SURE_YOU_WANT_TO_CHANGE_USER_PASSWORD'], function(response){
				if (response)
				{
					responseSave();
				}
			});
		}
		else
		{
			notifyDialog(la['PASSWORD_LENGHT_AT_LEAST']);
			return;
		}
	}
	else
	{
		responseSave();
	}
	
	function responseSave() {
		if(cpValues['accessSettings']['access_settings_subuser']['edit']!=true){
			notifyDialog(la['NO_PERMISSION']);
			return;
		}
		var data = {
			cmd: 'edit_user_subaccount',
			id: id,
			username: username,
			email: email,
			password: password
		};
			
		$.ajax({
			type: "POST",
			url: "func/fn_cpanel.users.php",
			data: data,
			success: function(result)
			{
				if (result == 'OK')
				{
					$('#dialog_user_edit_subaccount_list_grid').trigger("reloadGrid");
				}
				else if (result == 'ERROR_USERNAME_EXISTS')
				{
					notifyDialog(la['THIS_USERNAME_ALREADY_EXISTS']);
				}
				else if (result == 'ERROR_EMAIL_EXISTS')
				{
					notifyDialog(la['THIS_EMAIL_ALREADY_EXISTS']);
				}
				else if (result =='NO_PERMISSION')
				{
					notifyDialog(la['NO_PERMISSION']);
				}
			}
		});	
        }
}

function userSubaccountDelete(id){
	if(cpValues['accessSettings']['access_settings_subuser']['delete']!=true){
		notifyDialog(la['NO_PERMISSION']);
		return;
	}
	confirmDialog(la['ARE_YOU_SURE_YOU_WANT_TO_DELETE'], function(response){
		if (response)
		{
			var data = {
				cmd: 'delete_user_subaccount',
				id: id
			};
			
			$.ajax({
				type: "POST",
				url: "func/fn_cpanel.users.php",
				data: data,
				success: function(result)
				{
					if (result == 'OK')
					{
						$('#dialog_user_edit_subaccount_list_grid').trigger("reloadGrid");
					}
					else if (result =='NO_PERMISSION')
					{
						notifyDialog(la['NO_PERMISSION']);
					}
				}
			});	
		}
	});
}

function userSubaccountActivateSelected()
{
        var subaccounts = $('#dialog_user_edit_subaccount_list_grid').jqGrid ('getGridParam', 'selarrrow');
	
	if (subaccounts == '')
	{
		notifyDialog(la['NO_ITEMS_SELECTED']);
		return;
        }
	
	confirmDialog(la['ARE_YOU_SURE_YOU_WANT_TO_ACTIVATE_SELECTED_ITEMS'], function(response){
		if (response)
		{
			var data = {
				cmd: 'activate_selected_user_subaccounts',
				ids: subaccounts
			};
			
			$.ajax({
				type: "POST",
				url: "func/fn_cpanel.users.php",
				data: data,
				success: function(result)
				{
					if (result == 'OK')
					{						
						$('#dialog_user_edit_subaccount_list_grid').trigger("reloadGrid");	
					}
				}
			});
		}
	});
}

function userSubaccountDeactivateSelected()
{
        var subaccounts = $('#dialog_user_edit_subaccount_list_grid').jqGrid ('getGridParam', 'selarrrow');
	
	if (subaccounts == '')
	{
		notifyDialog(la['NO_ITEMS_SELECTED']);
		return;
        }
	
	confirmDialog(la['ARE_YOU_SURE_YOU_WANT_TO_DEACTIVATE_SELECTED_ITEMS'], function(response){
		if (response)
		{
			var data = {
				cmd: 'deactivate_selected_user_subaccounts',
				ids: subaccounts
			};
			
			$.ajax({
				type: "POST",
				url: "func/fn_cpanel.users.php",
				data: data,
				success: function(result)
				{
					if (result == 'OK')
					{						
						$('#dialog_user_edit_subaccount_list_grid').trigger("reloadGrid");	
					}
				}
			});
		}
	});
}

function userSubaccountDeleteSelected()
{
	if(cpValues['accessSettings']['access_settings_subuser']['delete']!=true){
		notifyDialog(la['NO_PERMISSION']);
		return;
	}
    var subaccounts = $('#dialog_user_edit_subaccount_list_grid').jqGrid ('getGridParam', 'selarrrow');
	
	if (subaccounts == '')
	{
		notifyDialog(la['NO_ITEMS_SELECTED']);
		return;
        }
	
	confirmDialog(la['ARE_YOU_SURE_YOU_WANT_TO_DELETE_SELECTED_ITEMS'], function(response){
		if (response)
		{
			var data = {
				cmd: 'delete_selected_user_subaccounts',
				ids: subaccounts
			};
			
			$.ajax({
				type: "POST",
				url: "func/fn_cpanel.users.php",
				data: data,
				success: function(result)
				{
					if (result == 'OK')
					{						
						$('#dialog_user_edit_subaccount_list_grid').trigger("reloadGrid");	
					}
					else if (result =='NO_PERMISSION')
					{
						notifyDialog(la['NO_PERMISSION']);
					}
				}
			});
		}
	});
}

function userBillingPlanAdd(cmd){
	switch (cmd)
	{
		case "open":
			document.getElementById('dialog_user_billing_plan_add_plan').value = '';
			document.getElementById('dialog_user_billing_plan_add_name').value = '';
			document.getElementById('dialog_user_billing_plan_add_objects').value = '';
			document.getElementById('dialog_user_billing_plan_add_period').value = '';
			document.getElementById('dialog_user_billing_plan_add_period_type').value = 'years';
			document.getElementById('dialog_user_billing_plan_add_price').value = '';
			$("#dialog_user_billing_plan_add").dialog("open");
			break;
		case "load":
			
			var plan_id = document.getElementById('dialog_user_billing_plan_add_plan').value;
			
			if (plan_id == '')
			{
					document.getElementById('dialog_user_billing_plan_add_name').value = '';
					document.getElementById('dialog_user_billing_plan_add_objects').value = '';
					document.getElementById('dialog_user_billing_plan_add_period').value = '';
					document.getElementById('dialog_user_billing_plan_add_period_type').value = 'years';
					document.getElementById('dialog_user_billing_plan_add_price').value = '';
					
					break;
                        }
			
			var data = {
				cmd: 'load_billing_plan',
				plan_id: plan_id
			};
			
			$.ajax({
				type: "POST",
				url: "func/fn_cpanel.server.php",
				data: data,
				dataType: 'json',
				cache: false,
				success: function(result)
				{
					document.getElementById('dialog_user_billing_plan_add_name').value = result['name'];
					document.getElementById('dialog_user_billing_plan_add_objects').value = result['objects'];
					document.getElementById('dialog_user_billing_plan_add_period').value = result['period'];
					document.getElementById('dialog_user_billing_plan_add_period_type').value = result['period_type'];
					document.getElementById('dialog_user_billing_plan_add_price').value = result['price'];
				}
			});
			
			break;
		case "add":
			var name = document.getElementById('dialog_user_billing_plan_add_name').value;
			var objects = document.getElementById('dialog_user_billing_plan_add_objects').value;
			var period = document.getElementById('dialog_user_billing_plan_add_period').value;
			var period_type = document.getElementById('dialog_user_billing_plan_add_period_type').value;
			var price = document.getElementById('dialog_user_billing_plan_add_price').value;
			
			var data = {
				cmd: 'add_user_billing_plan',
				user_id: cpValues['user_edit_id'],
				name: name,
				objects: objects,
				period: period,
				period_type: period_type,
				price: price
			};
			
			$.ajax({
				type: "POST",
				url: "func/fn_cpanel.users.php",
				data: data,
				success: function(result)
				{
					if (result == 'OK')
					{
						initStats();
						
						$('#dialog_user_edit_billing_plan_list_grid').trigger("reloadGrid");
						$("#dialog_user_billing_plan_add").dialog("close");
					}
				}
			});
			break;
		case "cancel":
			$("#dialog_user_billing_plan_add").dialog("close");
			break;
	}
}

function userBillingPlanEdit(cmd)
{
	switch (cmd)
	{
		default:
			var id = cmd;
			
			cpValues['edit_user_billing_plan_id'] = id;
			
			var data = {
				cmd: 'load_user_billing_plan',
				plan_id: cpValues['edit_user_billing_plan_id']
			};
			
			$.ajax({
				type: "POST",
				url: "func/fn_cpanel.users.php",
				data: data,
				dataType: 'json',
				cache: false,
				success: function(result)
				{
					document.getElementById('dialog_user_billing_plan_edit_name').value = result['name'];
					document.getElementById('dialog_user_billing_plan_edit_objects').value = result['objects'];
					document.getElementById('dialog_user_billing_plan_edit_period').value = result['period'];
					document.getElementById('dialog_user_billing_plan_edit_period_type').value = result['period_type'];
					document.getElementById('dialog_user_billing_plan_edit_price').value = result['price'];
				}
			});
			
			$("#dialog_user_billing_plan_edit").dialog("open");
			break;
			
		case "cancel":
			$("#dialog_user_billing_plan_edit").dialog("close");	
			break;
			
		case "save":
			var name = document.getElementById('dialog_user_billing_plan_edit_name').value;
			var objects = document.getElementById('dialog_user_billing_plan_edit_objects').value;
			var period = document.getElementById('dialog_user_billing_plan_edit_period').value;
			var period_type = document.getElementById('dialog_user_billing_plan_edit_period_type').value;
			var price = document.getElementById('dialog_user_billing_plan_edit_price').value;
			
			if ((name == "") || (objects == "") || (period == "") || (price == ""))
			{
				notifyDialog(la['ALL_AVAILABLE_FIELDS_SHOULD_BE_FILLED_OUT']);
				break;
			}
			
			var data = {
				cmd: 'save_user_billing_plan',
				plan_id: cpValues['edit_user_billing_plan_id'],
				name: name,
				objects: objects,
				period: period,
				period_type: period_type,
				price: price
			};
			
			$.ajax({
				type: "POST",
				url: "func/fn_cpanel.users.php",
				data: data,
				cache: false,
				success: function(result)
				{
					if (result == 'OK')
					{
						$('#dialog_user_edit_billing_plan_list_grid').trigger("reloadGrid");
						$("#dialog_user_billing_plan_edit").dialog("close");
					}
				}
			});
			break;
	}
}

function userBillingPlanDelete(id)
{
	confirmDialog(la['ARE_YOU_SURE_YOU_WANT_TO_DELETE'], function(response){
		if (response)
		{
			var data = {
				cmd: 'delete_user_billing_plan',
				id: id
			};
			
			$.ajax({
				type: "POST",
				url: "func/fn_cpanel.users.php",
				data: data,
				success: function(result)
				{
					if (result == 'OK')
					{
						initStats();
						
						if ($('#dialog_user_edit').dialog('isOpen') == true)
						{
						       $('#dialog_user_edit_billing_plan_list_grid').trigger("reloadGrid");
						}
						else
						{
							$('#cpanel_billing_plan_list_grid').trigger("reloadGrid"); 
						}
					}
				}
			});
		}
	});
}

function userBillingPlanDeleteSelected()
{
        var billing_plans = $('#dialog_user_edit_billing_plan_list_grid').jqGrid ('getGridParam', 'selarrrow');
	
	if (billing_plans == '')
	{
		notifyDialog(la['NO_ITEMS_SELECTED']);
		return;
        }
	
	confirmDialog(la['ARE_YOU_SURE_YOU_WANT_TO_DELETE_SELECTED_ITEMS'], function(response){
		if (response)
		{
			var data = {
				cmd: 'delete_selected_user_billing_plans',
				ids: billing_plans
			};
			
			$.ajax({
				type: "POST",
				url: "func/fn_cpanel.users.php",
				data: data,
				success: function(result)
				{
					if (result == 'OK')
					{
						initStats();
						
						$('#dialog_user_edit_billing_plan_list_grid').trigger("reloadGrid");	
					}
				}
			});
		}
	});
}

function userUsageDelete(id)
{
	confirmDialog(la['ARE_YOU_SURE_YOU_WANT_TO_DELETE'], function(response){
		if (response)
		{
			var data = {
				cmd: 'delete_user_usage',
				id: id
			};
			
			$.ajax({
				type: "POST",
				url: "func/fn_cpanel.users.php",
				data: data,
				success: function(result)
				{
					if (result == 'OK')
					{
						$('#dialog_user_edit_usage_list_grid').trigger("reloadGrid"); 
					}
				}
			});
		}
	});
}

function userUsageDeleteSelected()
{
        var usages = $('#dialog_user_edit_usage_list_grid').jqGrid ('getGridParam', 'selarrrow');
	
	if (usages == '')
	{
		notifyDialog(la['NO_ITEMS_SELECTED']);
		return;
        }
	
	confirmDialog(la['ARE_YOU_SURE_YOU_WANT_TO_DELETE_SELECTED_ITEMS'], function(response){
		if (response)
		{
			var data = {
				cmd: 'delete_selected_user_usages',
				ids: usages
			};
			
			$.ajax({
				type: "POST",
				url: "func/fn_cpanel.users.php",
				data: data,
				success: function(result)
				{
					if (result == 'OK')
					{
						$('#dialog_user_edit_usage_list_grid').trigger("reloadGrid");	
					}
				}
			});
		}
	});
}

function validateUserSettings(){
	var userset=cpValues['accessSettings'];
	if(userset['access_settings_user']['add']==false){
		document.getElementById("settings_user_add").disabled = true;
	}
	if(userset['access_settings_user']['edit']==false){
		document.getElementById("settings_user_edit").disabled = true;
	}
	if(userset['access_settings_user']['delete']==false){
		document.getElementById("settings_user_delete").disabled = true;						
	}

	if(userset['access_settings_object']['add']==false){
		document.getElementById("settings_object_add").disabled = true;
	}
	if(userset['access_settings_object']['edit']==false){
		document.getElementById("settings_object_edit").disabled = true;
	}
	if(userset['access_settings_object']['delete']==false){
		document.getElementById("settings_object_delete").disabled = true;						
	}

	if(userset['access_settings_subuser']['add']==false){
		document.getElementById("settings_subuser_add").disabled = true;
	}
	if(userset['access_settings_subuser']['edit']==false){
		document.getElementById("settings_subuser_edit").disabled = true;
	}
	if(userset['access_settings_subuser']['delete']==false){
		document.getElementById("settings_subuser_delete").disabled = true;						
	}

	if(userset['access_settings_group']['add']==false){
		document.getElementById("settings_group_add").disabled = true;
	}
	if(userset['access_settings_group']['edit']==false){
		document.getElementById("settings_group_edit").disabled = true;
	}
	if(userset['access_settings_group']['delete']==false){
		document.getElementById("settings_group_delete").disabled = true;						
	}

	if(userset['access_settings_events']['add']==false){
		document.getElementById("settings_event_add").disabled = true;
	}
	if(userset['access_settings_events']['edit']==false){
		document.getElementById("settings_event_edit").disabled = true;
	}
	if(userset['access_settings_events']['delete']==false){
		document.getElementById("settings_event_delete").disabled = true;						
	}

	if(userset['access_settings_zones']['add']==false){
		document.getElementById("settings_zone_add").disabled = true;
	}
	if(userset['access_settings_zones']['edit']==false){
		document.getElementById("settings_zone_edit").disabled = true;
	}
	if(userset['access_settings_zones']['delete']==false){
		document.getElementById("settings_zone_delete").disabled = true;						
	}
	if(userset['access_settings_route']['add']==false){
		document.getElementById("settings_route_add").disabled = true;
	}
	if(userset['access_settings_route']['edit']==false){
		document.getElementById("settings_route_edit").disabled = true;
	}
	if(userset['access_settings_route']['delete']==false){
		document.getElementById("settings_route_delete").disabled = true;						
	}
	if(userset['access_settings_markers']['add']==false){
		document.getElementById("settings_marker_add").disabled = true;
	}
	if(userset['access_settings_markers']['edit']==false){
		document.getElementById("settings_marker_edit").disabled = true;
	}
	if(userset['access_settings_markers']['delete']==false){
		document.getElementById("settings_marker_delete").disabled = true;						
	}
	if(userset['access_settings_duplicate']['add']==false){
		document.getElementById("settings_duplicate_add").disabled = true;
	}
	if(userset['access_settings_duplicate']['edit']==false){
		document.getElementById("settings_duplicate_edit").disabled = true;
	}
	if(userset['access_settings_duplicate']['delete']==false){
		document.getElementById("settings_duplicate_delete").disabled = true;						
	}
	if(userset['access_settings_clr_history']['add']==false){
		document.getElementById("settings_clr_history_add").disabled = true;
	}
	if(userset['access_settings_clr_history']['edit']==false){
		document.getElementById("settings_clr_history_edit").disabled = true;
	}
	if(userset['access_settings_clr_history']['delete']==false){
		document.getElementById("settings_clr_history_delete").disabled = true;						
	}
}