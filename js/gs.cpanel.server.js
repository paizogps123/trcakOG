function loadSettings(type, response)
{
	switch (type)
	{
		case "cpanel":
			var data = {
				cmd: 'load_cpanel_data'
			};
			
			$.ajax({
				type: "POST",
				url: "func/fn_cpanel.php",
				data: data,
				dataType: 'json',
				cache: false,
				success: function(result)
				{
					cpValues = result;
					cpValues['user_edit_id'] = '';
					cpValues['user_edit_privileges'] = '';
					cpValues['template_edit_name'] = '';
					
					document.getElementById("system_language").value = cpValues['language'];
					
					initSelectList('manager_list');
					
					response(true);
				}
			});
			break;
		case "server":
			var data = {
				cmd: 'load_server_data'
			};
			
			$.ajax({
				type: "POST",
				url: "func/fn_cpanel.server.php",
				data: data,
				dataType: 'json',
				cache: false,
				success: function(result)
				{			
					// general
					document.getElementById('cpanel_manage_server_name').value = result['name'];
					document.getElementById('cpanel_manage_server_generator').value = result['generator'];
					document.getElementById('cpanel_manage_server_api_key').value = result['server_api_key'];
					document.getElementById('cpanel_manage_server_show_about').value = result['show_about'];
					
					var languages = document.getElementById('cpanel_manage_server_languages');
					var languages_selected = result['languages'].split(",");
					multiselectSetValues(languages, languages_selected);
					
					// logo
					document.getElementById('cpanel_manage_server_logo_filename').value = result['logo'];
					
					// url addresses
					document.getElementById('cpanel_manage_server_url_login').value = result['url_login'];
					document.getElementById('cpanel_manage_server_url_help').value = result['url_help'];
					document.getElementById('cpanel_manage_server_url_contact').value = result['url_contact'];
					document.getElementById('cpanel_manage_server_url_shop').value = result['url_shop'];
					document.getElementById('cpanel_manage_server_url_sms_gateway_app').value = result['url_sms_gateway_app'];
					
					// geocoder
					document.getElementById('cpanel_manage_server_geocoder_cache').value = result['geocoder_cache'];
					
					// objects
					document.getElementById('cpanel_manage_server_connection_timeout').value = result['connection_timeout'];
					document.getElementById('cpanel_manage_server_history_period').value = result['history_period'];
					
					// db backup email
					document.getElementById('cpanel_manage_server_backup_time').value  = result['db_backup_time'];
					document.getElementById('cpanel_manage_server_backup_email').value  = result['db_backup_email'];
					
					// maps
					document.getElementById('cpanel_manage_server_map_osm').value = result['map_osm'];
					document.getElementById('cpanel_manage_server_map_bing').value = result['map_bing'];
					document.getElementById('cpanel_manage_server_map_google').value = result['map_google'];
					document.getElementById('cpanel_manage_server_map_google_traffic').value = result['map_google_traffic'];
					document.getElementById('cpanel_manage_server_map_mapbox').value = result['map_mapbox'];
					document.getElementById('cpanel_manage_server_map_yandex').value = result['map_yandex'];
					document.getElementById('cpanel_manage_server_map_bing_key').value = result['map_bing_key'];
					document.getElementById('cpanel_manage_server_map_google_key').value = result['map_google_key'];
					document.getElementById('cpanel_manage_server_map_mapbox_key').value = result['map_mapbox_key'];
					document.getElementById('cpanel_manage_server_map_layer').value = result['map_layer'];
					document.getElementById('cpanel_manage_server_map_zoom').value = result['map_zoom'];
					document.getElementById('cpanel_manage_server_map_lat').value = result['map_lat'];
					document.getElementById('cpanel_manage_server_map_lng').value = result['map_lng'];
					
					// user
					document.getElementById('cpanel_manage_server_page_after_login').value = result['page_after_login'];
					document.getElementById('cpanel_manage_server_allow_registration').value = result['allow_registration'];
					document.getElementById('cpanel_manage_server_account_expire').value = result['account_expire'];
					document.getElementById('cpanel_manage_server_account_expire_period').value = result['account_expire_period'];
					document.getElementById('cpanel_manage_server_language').value = result['language'];			
					document.getElementById('cpanel_manage_server_distance_unit').value = result['unit_of_distance'];
					document.getElementById('cpanel_manage_server_capacity_unit').value = result['unit_of_capacity'];
					document.getElementById('cpanel_manage_server_temperature_unit').value = result['unit_of_temperature'];			
					document.getElementById('cpanel_manage_server_currency').value = result['currency'];			
					document.getElementById('cpanel_manage_server_timezone').value = result['timezone'];
					
					if ((result['dst_start'].length == 11) && (result['dst_end'].length == 11))
					{
						document.getElementById('cpanel_manage_server_dst').checked = strToBoolean(result['dst']);
						
						var dst_start = result['dst_start'].split(" ");
						document.getElementById('cpanel_manage_server_dst_start_mmdd').value = dst_start[0];
						document.getElementById('cpanel_manage_server_dst_start_hhmm').value = dst_start[1];
						
						var dst_end = result['dst_end'].split(" ");
						document.getElementById('cpanel_manage_server_dst_end_mmdd').value = dst_end[0];
						document.getElementById('cpanel_manage_server_dst_end_hhmm').value = dst_end[1];	
					}
					else
					{
						document.getElementById('cpanel_manage_server_dst').checked = false;
		
						document.getElementById('cpanel_manage_server_dst_start_mmdd').value = '';
						document.getElementById('cpanel_manage_server_dst_start_hhmm').value = '00:00';
						
						document.getElementById('cpanel_manage_server_dst_end_mmdd').value = '';
						document.getElementById('cpanel_manage_server_dst_end_hhmm').value = '00:00';	
					}
					
					document.getElementById('cpanel_manage_server_obj_add').value = result['obj_add'];
					document.getElementById('cpanel_manage_server_obj_limit').value = result['obj_limit'];
					document.getElementById('cpanel_manage_server_obj_limit_num').value = result['obj_limit_num'];
					document.getElementById('cpanel_manage_server_obj_days').value = result['obj_days'];
					document.getElementById('cpanel_manage_server_obj_days_num').value = result['obj_days_num'];
					document.getElementById('cpanel_manage_server_obj_days_trial').value = result['obj_days_trial'];
					document.getElementById('cpanel_manage_server_obj_edit').value = result['obj_edit'];
					document.getElementById('cpanel_manage_server_obj_history_clear').value = result['obj_history_clear'];
					
					document.getElementById('cpanel_manage_server_history').value = result['history'];
					document.getElementById('cpanel_manage_server_reports').value = result['reports'];
					document.getElementById('cpanel_manage_server_rilogbook').value = result['rilogbook'];
					document.getElementById('cpanel_manage_server_dtc').value = result['dtc'];
					document.getElementById('cpanel_manage_server_object_control').value = result['object_control'];
					document.getElementById('cpanel_manage_server_image_gallery').value = result['image_gallery'];
					document.getElementById('cpanel_manage_server_chat').value = result['chat'];
					document.getElementById('cpanel_manage_server_subaccounts').value = result['subaccounts'];
					document.getElementById('cpanel_manage_server_sms_gateway_server').value = result['sms_gateway_server'];
					document.getElementById('cpanel_manage_server_api').value = result['api'];
					
					document.getElementById('cpanel_manage_server_notify_obj_expire').value = result['notify_obj_expire'];
					document.getElementById('cpanel_manage_server_notify_obj_expire_period').value = result['notify_obj_expire_period'];
					document.getElementById('cpanel_manage_server_notify_account_expire').value = result['notify_account_expire'];
					document.getElementById('cpanel_manage_server_notify_account_expire_period').value = result['notify_account_expire_period'];
					
					document.getElementById('cpanel_manage_server_reports_schedule').value = result['reports_schedule'];
					document.getElementById('cpanel_manage_server_places_markers').value = result['places_markers'];
					document.getElementById('cpanel_manage_server_places_routes').value = result['places_routes'];
					document.getElementById('cpanel_manage_server_places_zones').value = result['places_zones'];
					
					document.getElementById('cpanel_manage_server_usage_email_daily').value = result['usage_email_daily'];
					document.getElementById('cpanel_manage_server_usage_sms_daily').value = result['usage_sms_daily'];
					document.getElementById('cpanel_manage_server_usage_api_daily').value = result['usage_api_daily'];
					
					// billing
					document.getElementById('cpanel_manage_server_billing').value = result['billing'];
					document.getElementById('cpanel_manage_server_billing_gateway').value = result['billing_gateway'];
					document.getElementById('cpanel_manage_server_billing_currency').value = result['billing_currency'];
					document.getElementById('cpanel_manage_server_billing_paypal_account').value = result['billing_paypal_account'];
					document.getElementById('cpanel_manage_server_billing_paypal_custom').value = result['billing_paypal_custom'];
					document.getElementById('cpanel_manage_server_billing_paypal_ipn_url').value = result['billing_paypal_ipn_url'];
					document.getElementById('cpanel_manage_server_billing_custom_url').value = result['billing_custom_url'];
					
					// e-mail settings
					result['email_signature'] = result['email_signature'].replace(/\\'/g,"'");
					
					document.getElementById('cpanel_manage_server_email').value = result['email'];
					document.getElementById('cpanel_manage_server_email_no_reply').value = result['email_no_reply'];
					document.getElementById('cpanel_manage_server_email_signature').value = result['email_signature'];
					document.getElementById('cpanel_manage_server_email_smtp').value = result['email_smtp'];
					document.getElementById('cpanel_manage_server_email_smtp_host').value = result['email_smtp_host'];
					document.getElementById('cpanel_manage_server_email_smtp_port').value = result['email_smtp_port'];
					document.getElementById('cpanel_manage_server_email_smtp_auth').value = result['email_smtp_auth'];
					document.getElementById('cpanel_manage_server_email_smtp_secure').value = result['email_smtp_secure'];
					document.getElementById('cpanel_manage_server_email_smtp_username').value = result['email_smtp_username'];
					document.getElementById('cpanel_manage_server_email_smtp_password').value = result['email_smtp_password'];
					
					// sms settings
					document.getElementById('cpanel_manage_server_sms_gateway').value = result['sms_gateway'];
					if (result['sms_gateway_type'] == '')
					{
						result['sms_gateway_type'] = 'app';
					}
					document.getElementById('cpanel_manage_server_sms_gateway_type').value = result['sms_gateway_type'];
					document.getElementById('cpanel_manage_server_sms_gateway_number_filter').value = result['sms_gateway_number_filter'];
					document.getElementById('cpanel_manage_server_sms_gateway_url').value = result['sms_gateway_url'];
					document.getElementById('cpanel_manage_server_sms_gateway_identifier').value = result['sms_gateway_identifier'];
					
					// tools
					document.getElementById('cpanel_manage_server_tools_server_cleanup_users_ae').value = result['server_cleanup_users_ae'];
					document.getElementById('cpanel_manage_server_tools_server_cleanup_objects_not_activated_ae').value = result['server_cleanup_objects_not_activated_ae'];
					document.getElementById('cpanel_manage_server_tools_server_cleanup_objects_not_used_ae').value = result['server_cleanup_objects_not_used_ae'];
					document.getElementById('cpanel_manage_server_tools_server_cleanup_db_junk_ae').value = result['server_cleanup_db_junk_ae'];
					document.getElementById('cpanel_manage_server_tools_server_cleanup_users_days').value = result['server_cleanup_users_days'];
					document.getElementById('cpanel_manage_server_tools_server_cleanup_objects_not_activated_days').value = result['server_cleanup_objects_not_activated_days'];
					
					serverCheck();
					
					response(true);
				}
			});
			break;
	}
}

function serverSave()
{
	var languages = document.getElementById('cpanel_manage_server_languages');
	languages = multiselectGetValues(languages);
		
	var account_expire = document.getElementById('cpanel_manage_server_account_expire').value;
	var account_expire_period = document.getElementById('cpanel_manage_server_account_expire_period').value;
	
	var obj_limit = document.getElementById('cpanel_manage_server_obj_limit').value;
	
	var obj_limit_num = document.getElementById('cpanel_manage_server_obj_limit_num').value;
	
	if ((obj_limit_num < 1) || !isIntValid(obj_limit_num))
	{
		obj_limit_num = 0;
	}
	
	var obj_days = document.getElementById('cpanel_manage_server_obj_days').value;
	
	var obj_days_num = document.getElementById('cpanel_manage_server_obj_days_num').value;
	
	if ((obj_days_num < 1) || !isIntValid(obj_days_num))
	{
		obj_days_num = 0;
	}
	
	var obj_days_trial = document.getElementById('cpanel_manage_server_obj_days_trial').value;
	
	if ((obj_days_trial < 1) || !isIntValid(obj_days_trial))
	{
		obj_days_trial = 0;
	}
	
	var dst = document.getElementById('cpanel_manage_server_dst').checked;
	var dst_start = document.getElementById('cpanel_manage_server_dst_start_mmdd').value + ' ' + document.getElementById('cpanel_manage_server_dst_start_hhmm').value;
	var dst_end = document.getElementById('cpanel_manage_server_dst_end_mmdd').value + ' ' + document.getElementById('cpanel_manage_server_dst_end_hhmm').value;
	
	if ((dst == false) || (dst_start.length != 11) || (dst_end.length != 11))
	{
		dst = false;
		dst_start = '';
		dst_end = '';
	}
	
	var history_period = document.getElementById('cpanel_manage_server_history_period').value;
	
	if ((history_period < 30) || !isIntValid(history_period))
	{
		notifyDialog(la['LOWEST_HISTORY_PERIOD_IS_30_DAYS']);
		return;
	}
	
	var db_backup_time = document.getElementById('cpanel_manage_server_backup_time').value;
	var db_backup_email = document.getElementById('cpanel_manage_server_backup_email').value;
	if((db_backup_email != '') && (!isEmailValid(db_backup_email)))
	{
		notifyDialog(la['THIS_EMAIL_IS_NOT_VALID']);
		return;
	}
	
	var notify_obj_expire = document.getElementById('cpanel_manage_server_notify_obj_expire').value;
	var notify_obj_expire_period = document.getElementById('cpanel_manage_server_notify_obj_expire_period').value;
	var notify_account_expire = document.getElementById('cpanel_manage_server_notify_account_expire').value;
	var notify_account_expire_period = document.getElementById('cpanel_manage_server_notify_account_expire_period').value;
	
	if ((account_expire_period < 1) || !isIntValid(account_expire_period))
	{
		account_expire_period = 1;
	}
	
	if ((notify_obj_expire_period < 1) || !isIntValid(notify_obj_expire_period))
	{
		notify_obj_expire_period = 1;
	}
	
	if ((notify_account_expire_period < 1) || !isIntValid(notify_account_expire_period))
	{
		notify_account_expire_period = 1;
	}
	
	var places_markers = document.getElementById('cpanel_manage_server_places_markers').value;
	if ((places_markers < 1) || !isIntValid(places_markers))
	{
		places_markers = 0;
	}
	
	var places_routes = document.getElementById('cpanel_manage_server_places_routes').value;
	if ((places_routes < 1) || !isIntValid(places_routes))
	{
		places_routes = 0;
	}
	
	var places_zones = document.getElementById('cpanel_manage_server_places_zones').value;
	if ((places_zones < 1) || !isIntValid(places_zones))
	{
		places_zones = 0;
	}
	
	var usage_email_daily = document.getElementById('cpanel_manage_server_usage_email_daily').value;
	if ((usage_email_daily < 1) || !isIntValid(usage_email_daily))
	{
		usage_email_daily = 0;
	}
	
	var usage_sms_daily = document.getElementById('cpanel_manage_server_usage_sms_daily').value;
	if ((usage_sms_daily < 1) || !isIntValid(usage_sms_daily))
	{
		usage_sms_daily = 0;
	}
	
	var usage_api_daily = document.getElementById('cpanel_manage_server_usage_api_daily').value;
	if ((usage_api_daily < 1) || !isIntValid(usage_api_daily))
	{
		usage_api_daily = 0;
	}
	
	var map_osm = document.getElementById('cpanel_manage_server_map_osm').value;
	var map_bing = document.getElementById('cpanel_manage_server_map_bing').value;
	var map_google = document.getElementById('cpanel_manage_server_map_google').value;
	var map_mapbox = document.getElementById('cpanel_manage_server_map_mapbox').value;
	var map_yandex = document.getElementById('cpanel_manage_server_map_yandex').value;
	
	if ((map_osm == 'false') && (map_bing == 'false') && (map_google == 'false') && (map_mapbox== 'false') && (map_yandex == 'false'))
	{
		notifyDialog(la['AT_LEAST_ONE_MAP_SHOULD_BE_ENABLED']);
		return;
	}
	
	var map_lat = document.getElementById('cpanel_manage_server_map_lat').value;
	if (isNumber(map_lat))
	{
		if ((map_lat < -90) || (map_lat > 90))
		{
			notifyDialog(la['LATITUDE_IS_OUT_OF_RANGE']);
			return;
		}
	}
	else
	{
		map_lat = 0;
	}
	
	var map_lng = document.getElementById('cpanel_manage_server_map_lng').value;
	if (isNumber(map_lng))
	{
		if ((map_lng < -180) || (map_lng > 180))
		{
			notifyDialog(la['LONGITUDE_IS_OUT_OF_RANGE']);
			return;
		}
	}
	else
	{
		map_lng = 0;
	}
	
	var server_cleanup_users_ae = document.getElementById('cpanel_manage_server_tools_server_cleanup_users_ae').value;
	var server_cleanup_objects_not_activated_ae = document.getElementById('cpanel_manage_server_tools_server_cleanup_objects_not_activated_ae').value;
	var server_cleanup_objects_not_used_ae = document.getElementById('cpanel_manage_server_tools_server_cleanup_objects_not_used_ae').value;
	var server_cleanup_db_junk_ae =	document.getElementById('cpanel_manage_server_tools_server_cleanup_db_junk_ae').value;
	
	var server_cleanup_users_days =	document.getElementById('cpanel_manage_server_tools_server_cleanup_users_days').value;
	if ((server_cleanup_users_days < 1) || !isIntValid(server_cleanup_users_days))
	{
		server_cleanup_users_days = 0;
	}
	
	var server_cleanup_objects_not_activated_days = document.getElementById('cpanel_manage_server_tools_server_cleanup_objects_not_activated_days').value;
	if ((server_cleanup_objects_not_activated_days < 1) || !isIntValid(server_cleanup_objects_not_activated_days))
	{
		server_cleanup_objects_not_activated_days = 0;
	}
	
	var data = {
		cmd: 'save_server_data',
		name: document.getElementById('cpanel_manage_server_name').value,
		generator: document.getElementById('cpanel_manage_server_generator').value,
		server_api_key: document.getElementById('cpanel_manage_server_api_key').value,
		show_about: document.getElementById('cpanel_manage_server_show_about').value,
		languages: languages,
		logo: document.getElementById('cpanel_manage_server_logo_filename').value,
		url_login: document.getElementById('cpanel_manage_server_url_login').value,
		url_help: document.getElementById('cpanel_manage_server_url_help').value,
		url_contact: document.getElementById('cpanel_manage_server_url_contact').value,
		url_shop: document.getElementById('cpanel_manage_server_url_shop').value,
		url_sms_gateway_app: document.getElementById('cpanel_manage_server_url_sms_gateway_app').value,
		geocoder_cache: document.getElementById('cpanel_manage_server_geocoder_cache').value,
		connection_timeout: document.getElementById('cpanel_manage_server_connection_timeout').value,
		history_period: history_period,
		db_backup_time: db_backup_time,
		db_backup_email: db_backup_email,
		map_osm: map_osm,
		map_bing: map_bing,
		map_google: map_google,
		map_google_traffic: document.getElementById('cpanel_manage_server_map_google_traffic').value,
		map_mapbox: map_mapbox,
		map_yandex: map_yandex,
		map_bing_key: document.getElementById('cpanel_manage_server_map_bing_key').value,
		map_google_key: document.getElementById('cpanel_manage_server_map_google_key').value,
		map_mapbox_key: document.getElementById('cpanel_manage_server_map_mapbox_key').value,
		map_layer: document.getElementById('cpanel_manage_server_map_layer').value,
		map_zoom: document.getElementById('cpanel_manage_server_map_zoom').value,
		map_lat: map_lat,
		map_lng: map_lng,
		page_after_login: document.getElementById('cpanel_manage_server_page_after_login').value,
		allow_registration: document.getElementById('cpanel_manage_server_allow_registration').value,
		account_expire: account_expire,
		account_expire_period: account_expire_period,
		language: document.getElementById('cpanel_manage_server_language').value,
		unit_of_distance: document.getElementById('cpanel_manage_server_distance_unit').value,
		unit_of_capacity: document.getElementById('cpanel_manage_server_capacity_unit').value,
		unit_of_temperature: document.getElementById('cpanel_manage_server_temperature_unit').value,
		currency: document.getElementById('cpanel_manage_server_currency').value,
		timezone: document.getElementById('cpanel_manage_server_timezone').value,
		dst: dst,
		dst_start: dst_start,
		dst_end: dst_end,
		obj_add: document.getElementById('cpanel_manage_server_obj_add').value,
		obj_limit: obj_limit,
		obj_limit_num: obj_limit_num,
		obj_days: obj_days,
		obj_days_num: obj_days_num,
		obj_days_trial: obj_days_trial,
		obj_edit: document.getElementById('cpanel_manage_server_obj_edit').value,
		obj_history_clear: document.getElementById('cpanel_manage_server_obj_history_clear').value,
		history: document.getElementById('cpanel_manage_server_history').value,
		reports: document.getElementById('cpanel_manage_server_reports').value,
		rilogbook: document.getElementById('cpanel_manage_server_rilogbook').value,
		dtc: document.getElementById('cpanel_manage_server_dtc').value,
		object_control: document.getElementById('cpanel_manage_server_object_control').value,
		image_gallery: document.getElementById('cpanel_manage_server_image_gallery').value,
		chat: document.getElementById('cpanel_manage_server_chat').value,
		subaccounts: document.getElementById('cpanel_manage_server_subaccounts').value,
		sms_gateway_server: document.getElementById('cpanel_manage_server_sms_gateway_server').value,
		api: document.getElementById('cpanel_manage_server_api').value,
		notify_obj_expire: notify_obj_expire,
		notify_obj_expire_period: notify_obj_expire_period,
		notify_account_expire: notify_account_expire,
		notify_account_expire_period: notify_account_expire_period,
		reports_schedule: document.getElementById('cpanel_manage_server_reports_schedule').value,
		places_markers: places_markers,
		places_routes: places_routes,
		places_zones: places_zones,
		usage_email_daily: usage_email_daily,
		usage_sms_daily: usage_sms_daily,
		usage_api_daily: usage_api_daily,
		billing: document.getElementById('cpanel_manage_server_billing').value,
		billing_gateway: document.getElementById('cpanel_manage_server_billing_gateway').value,
		billing_currency: document.getElementById('cpanel_manage_server_billing_currency').value,
		billing_paypal_account: document.getElementById('cpanel_manage_server_billing_paypal_account').value,
		billing_paypal_custom: document.getElementById('cpanel_manage_server_billing_paypal_custom').value,
		billing_custom_url: document.getElementById('cpanel_manage_server_billing_custom_url').value,
		email: document.getElementById('cpanel_manage_server_email').value,
		email_no_reply: document.getElementById('cpanel_manage_server_email_no_reply').value,
		email_signature: document.getElementById('cpanel_manage_server_email_signature').value,
		email_smtp: document.getElementById('cpanel_manage_server_email_smtp').value,
		email_smtp_host: document.getElementById('cpanel_manage_server_email_smtp_host').value,
		email_smtp_port: document.getElementById('cpanel_manage_server_email_smtp_port').value,
		email_smtp_auth: document.getElementById('cpanel_manage_server_email_smtp_auth').value,
		email_smtp_secure: document.getElementById('cpanel_manage_server_email_smtp_secure').value,
		email_smtp_username: document.getElementById('cpanel_manage_server_email_smtp_username').value,
		email_smtp_password: document.getElementById('cpanel_manage_server_email_smtp_password').value,
		sms_gateway: document.getElementById('cpanel_manage_server_sms_gateway').value,
		sms_gateway_type: document.getElementById('cpanel_manage_server_sms_gateway_type').value,
		sms_gateway_number_filter: document.getElementById('cpanel_manage_server_sms_gateway_number_filter').value,
		sms_gateway_url: document.getElementById('cpanel_manage_server_sms_gateway_url').value,
		sms_gateway_identifier: document.getElementById('cpanel_manage_server_sms_gateway_identifier').value,
		server_cleanup_users_ae: server_cleanup_users_ae,
		server_cleanup_objects_not_activated_ae: server_cleanup_objects_not_activated_ae,
		server_cleanup_objects_not_used_ae: server_cleanup_objects_not_used_ae,
		server_cleanup_db_junk_ae: server_cleanup_db_junk_ae,
		server_cleanup_users_days: server_cleanup_users_days,
		server_cleanup_objects_not_activated_days: server_cleanup_objects_not_activated_days
	};

	$.ajax({
		type: "POST",
		url: "func/fn_cpanel.server.php",
		data: data,
		success: function(result)
		{
			if (result == 'OK')
			{
				notifyDialog(la['CHANGES_SAVED_SUCCESSFULLY']);
				loadSettings('server', function(){});
			}
		}
	});
}

function serverCheck()
{
	if (document.getElementById('cpanel_manage_server_account_expire').value == 'true')
	{
                document.getElementById('cpanel_manage_server_account_expire_period').disabled = false;
        }
	else
	{
		document.getElementById('cpanel_manage_server_account_expire_period').disabled = true;
	}
	
	var dst = document.getElementById('cpanel_manage_server_dst').checked;
	
	if (dst)
	{
                document.getElementById('cpanel_manage_server_dst_start_mmdd').disabled = false;
		document.getElementById('cpanel_manage_server_dst_start_hhmm').disabled = false;
		document.getElementById('cpanel_manage_server_dst_end_mmdd').disabled = false;
		document.getElementById('cpanel_manage_server_dst_end_hhmm').disabled = false;
        }
	else
	{
		document.getElementById('cpanel_manage_server_dst_start_mmdd').disabled = true;
		document.getElementById('cpanel_manage_server_dst_start_hhmm').disabled = true;
		document.getElementById('cpanel_manage_server_dst_end_mmdd').disabled = true;
		document.getElementById('cpanel_manage_server_dst_end_hhmm').disabled = true;
	}
	
	switch (document.getElementById('cpanel_manage_server_obj_add').value)
	{
		case "true":
			document.getElementById('cpanel_manage_server_obj_limit').disabled = false;
			
			if (document.getElementById('cpanel_manage_server_obj_limit').value == 'true')
			{
				document.getElementById('cpanel_manage_server_obj_limit_num').disabled = false;
                        }
			else
			{
				document.getElementById('cpanel_manage_server_obj_limit_num').disabled = true;
			}
			
			document.getElementById('cpanel_manage_server_obj_days').disabled = false;
			
			if (document.getElementById('cpanel_manage_server_obj_days').value == 'true')
			{
				document.getElementById('cpanel_manage_server_obj_days_num').disabled = false;
                        }
			else
			{
				document.getElementById('cpanel_manage_server_obj_days_num').disabled = true;
			}
			
			document.getElementById('cpanel_manage_server_obj_days_trial').disabled = true;

			break;
		case "false":
			document.getElementById('cpanel_manage_server_obj_limit').disabled = true;
			document.getElementById('cpanel_manage_server_obj_limit_num').disabled = true;
			document.getElementById('cpanel_manage_server_obj_days').disabled = true;
			document.getElementById('cpanel_manage_server_obj_days_num').disabled = true;
			document.getElementById('cpanel_manage_server_obj_days_trial').disabled = true;
			break;
		case "trial":
			document.getElementById('cpanel_manage_server_obj_limit').disabled = true;
			document.getElementById('cpanel_manage_server_obj_limit_num').disabled = true;
			document.getElementById('cpanel_manage_server_obj_days').disabled = true;
			document.getElementById('cpanel_manage_server_obj_days_num').disabled = true;
			document.getElementById('cpanel_manage_server_obj_days_trial').disabled = false;
			break;
	}
	
	if (document.getElementById('cpanel_manage_server_notify_obj_expire').value == 'true')
	{
                document.getElementById('cpanel_manage_server_notify_obj_expire_period').disabled = false;
        }
	else
	{
		document.getElementById('cpanel_manage_server_notify_obj_expire_period').disabled = true;
	}
	
	if (document.getElementById('cpanel_manage_server_notify_account_expire').value == 'true')
	{
                document.getElementById('cpanel_manage_server_notify_account_expire_period').disabled = false;
        }
	else
	{
		document.getElementById('cpanel_manage_server_notify_account_expire_period').disabled = true;
	}
	
	if (document.getElementById('cpanel_manage_server_billing_gateway').value == 'paypal')
	{
		document.getElementById('cpanel_manage_server_billing_paypal').style.display = '';
		document.getElementById('cpanel_manage_server_billing_custom').style.display = 'none';
	}
	else
	{
		document.getElementById('cpanel_manage_server_billing_paypal').style.display = 'none';
		document.getElementById('cpanel_manage_server_billing_custom').style.display = '';
	}

	if (document.getElementById('cpanel_manage_server_email_smtp').value == 'true')
	{
		document.getElementById('cpanel_manage_server_email_smtp_host').disabled = false;
		document.getElementById('cpanel_manage_server_email_smtp_port').disabled = false;
		document.getElementById('cpanel_manage_server_email_smtp_auth').disabled = false;
		document.getElementById('cpanel_manage_server_email_smtp_secure').disabled = false;
		document.getElementById('cpanel_manage_server_email_smtp_username').disabled = false;
		document.getElementById('cpanel_manage_server_email_smtp_password').disabled = false;

	}
	else
	{
		document.getElementById('cpanel_manage_server_email_smtp_host').disabled = true;
		document.getElementById('cpanel_manage_server_email_smtp_port').disabled = true;
		document.getElementById('cpanel_manage_server_email_smtp_auth').disabled = true;
		document.getElementById('cpanel_manage_server_email_smtp_secure').disabled = true;
		document.getElementById('cpanel_manage_server_email_smtp_username').disabled = true;
		document.getElementById('cpanel_manage_server_email_smtp_password').disabled = true;
	}
	
	if (document.getElementById('cpanel_manage_server_sms_gateway_type').value == 'app')
	{
		document.getElementById('cpanel_manage_server_sms_app').style.display = '';
		document.getElementById('cpanel_manage_server_sms_http').style.display = 'none';
	}
	else
	{
		document.getElementById('cpanel_manage_server_sms_app').style.display = 'none';
		document.getElementById('cpanel_manage_server_sms_http').style.display = '';
	}
}

function uploadLogo()
{
	// a bit dirty sollution, maybe will make better in the feature :)
	document.getElementById('load_file').addEventListener('change', uploadLogoFile, false);
	document.getElementById('load_file').click();
}

function uploadLogoFile(evt)
{
	var files = evt.target.files;
	var reader = new FileReader();
	reader.onloadend = function(event)
	{
		var result = event.target.result;
		
		if ((files[0].type != ('image/png')) && (files[0].type != ('image/svg+xml')))
		{
			notifyDialog(la['FILE_TYPE_MUST_BE_PNG_OR_SVG']);
			return;
		}
		
		var image = new Image();
		image.src = result;
		
		image.onload = function () {
			if (image.src.includes("image/png"))
			{
				if ((image.width != 250) || (image.height != 56))
				{
					notifyDialog(la['IMAGE_WIGTH_OR_HEIGHT_DOES_NOT_MEET_REQUIREMENTS']);
					return;
				}
				
				var url = "func/fn_upload.php?file=logo_png";
			}
			else
			{
				var url = "func/fn_upload.php?file=logo_svg";
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
						document.getElementById('cpanel_manage_server_logo').src = result + "?t=" + new Date().getTime();
						
						if (image.src.includes("image/png"))
						{
							document.getElementById('cpanel_manage_server_logo_filename').value = 'logo.png';
						}
						else
						{
							document.getElementById('cpanel_manage_server_logo_filename').value = 'logo.svg';
						}
						
						serverSave();
                                        }
					else
					{
						notifyDialog(la['IMAGE_UPLOAD_FAILED']);
					}
				}
			});
		}
		
		document.getElementById('load_file').value = '';
	}
	reader.readAsDataURL(files[0]);
	
	this.removeEventListener('change', uploadLogoFile, false);
}

function geocoderClearCache()
{
	confirmDialog(la['ARE_YOU_SURE_YOU_WANT_TO_CLEAR_GEOCODER_CACHE'], function(response){
		if (response)
		{
			var data = {
				cmd: 'clear_geocoder_cache'
			};
			
			$.ajax({
				type: "POST",
				url: "func/fn_cpanel.server.php",
				data: data,
				success: function(result)
				{
					if (result == 'OK')
					{
						notifyDialog(la['GEOCODER_CACHE_CLEARED']);
					}
				}
			});
		}
	});
}

function customMapProperties(cmd)
{
	switch (cmd)
	{
		default:
			var id = cmd;
			
			cpValues['edit_custom_map_id'] = id;
			
			var data = {
				cmd: 'load_custom_map',
				map_id: cpValues['edit_custom_map_id']
			};
			
			$.ajax({
				type: "POST",
				url: "func/fn_cpanel.server.php",
				data: data,
				dataType: 'json',
				cache: false,
				success: function(result)
				{
					document.getElementById('dialog_custom_map_name').value = result['name'];
					document.getElementById('dialog_custom_map_active').checked = strToBoolean(result['active']);
					document.getElementById('dialog_custom_map_type').value = result['type'];
					document.getElementById('dialog_custom_map_url').value = result['url'];
					document.getElementById('dialog_custom_map_layers').value = result['layers'];
				}
			});
			
			$("#dialog_custom_map_properties").dialog("open");
			break;
		
		case "add":
			cpValues['edit_custom_map_id'] = false;
			document.getElementById('dialog_custom_map_name').value = '';
			document.getElementById('dialog_custom_map_active').checked = true;
			document.getElementById('dialog_custom_map_type').value = 'tms';
			document.getElementById('dialog_custom_map_url').value = '';
			document.getElementById('dialog_custom_map_layers').value = '';
			
			$("#dialog_custom_map_properties").dialog("open");	
			break;
			
		case "cancel":
			$("#dialog_custom_map_properties").dialog("close");	
			break;
			
		case "save":
			var name = document.getElementById('dialog_custom_map_name').value;
			var active = document.getElementById('dialog_custom_map_active').checked;
			var type = document.getElementById('dialog_custom_map_type').value;
			var url = document.getElementById('dialog_custom_map_url').value;
			var layers = document.getElementById('dialog_custom_map_layers').value;
			
			if (name == "")
			{
				notifyDialog(la['NAME_CANT_BE_EMPTY']);
				break;
			}
			
			if (url == "")
			{
				notifyDialog(la['URL_CANT_BE_EMPTY']);
				break;
			}
			
			var data = {
				cmd: 'save_custom_map',
				map_id: cpValues['edit_custom_map_id'],
				name: name,
				active: active,
				type: type,
				url: url,
				layers: layers
			};
			
			$.ajax({
				type: "POST",
				url: "func/fn_cpanel.server.php",
				data: data,
				cache: false,
				success: function(result)
				{
					if (result == 'OK')
					{
						loadGridList('custom_maps');
						$("#dialog_custom_map_properties").dialog("close");
					}
				}
			});
			break;
	}
}

function customMapDeleteAll()
{
	confirmDialog(la['ARE_YOU_SURE_YOU_WANT_TO_DELETE_ALL_CUSTOM_MAPS'], function(response){
		if (response)
		{
			var data = {
				cmd: 'delete_all_custom_maps'
			};
			
			$.ajax({
				type: "POST",
				url: "func/fn_cpanel.server.php",
				data: data,
				success: function(result)
				{
					if (result == 'OK')
					{
						loadGridList('custom_maps');
					}
				}
			});
		}
	});
}

function customMapDelete(map_id)
{
	confirmDialog(la['ARE_YOU_SURE_YOU_WANT_TO_DELETE'], function(response){
		if (response)
		{
			var data = {
				cmd: 'delete_custom_map',
				map_id: map_id
			};
			
			$.ajax({
				type: "POST",
				url: "func/fn_cpanel.server.php",
				data: data,
				success: function(result)
				{
					if (result == 'OK')
					{
						loadGridList('custom_maps');
					}
				}
			});
		}
	});
}

function billingPlanProperties(cmd)
{
	switch (cmd)
	{
		default:
			var id = cmd;
			
			cpValues['edit_billing_plan_id'] = id;
			
			var data = {
				cmd: 'load_billing_plan',
				plan_id: cpValues['edit_billing_plan_id']
			};
			
			$.ajax({
				type: "POST",
				url: "func/fn_cpanel.server.php",
				data: data,
				dataType: 'json',
				cache: false,
				success: function(result)
				{
					document.getElementById('dialog_billing_name').value = result['name'];
					document.getElementById('dialog_billing_active').checked = strToBoolean(result['active']);
					document.getElementById('dialog_billing_objects').value = result['objects'];
					document.getElementById('dialog_billing_period').value = result['period'];
					document.getElementById('dialog_billing_period_type').value = result['period_type'];
					document.getElementById('dialog_billing_price').value = result['price'];
				}
			});
			
			$("#dialog_billing_properties").dialog("open");
			break;
		
		case "add":
			cpValues['edit_billing_plan_id'] = false;
			document.getElementById('dialog_billing_name').value = '';
			document.getElementById('dialog_billing_active').checked = true;
			document.getElementById('dialog_billing_objects').value = '';
			document.getElementById('dialog_billing_period').value = '';
			document.getElementById('dialog_billing_period_type').value = 'years';
			document.getElementById('dialog_billing_price').value = '';
			
			$("#dialog_billing_properties").dialog("open");	
			break;
			
		case "cancel":
			$("#dialog_billing_properties").dialog("close");	
			break;
			
		case "save":
			var name = document.getElementById('dialog_billing_name').value;
			var active = document.getElementById('dialog_billing_active').checked;
			var objects = document.getElementById('dialog_billing_objects').value;
			var period = document.getElementById('dialog_billing_period').value;
			var period_type = document.getElementById('dialog_billing_period_type').value;
			var price = document.getElementById('dialog_billing_price').value;
			
			if ((name == "") || (objects == "") || (period == "") || (price == ""))
			{
				notifyDialog(la['ALL_AVAILABLE_FIELDS_SHOULD_BE_FILLED_OUT']);
				break;
			}
			
			var data = {
				cmd: 'save_billing_plan',
				plan_id: cpValues['edit_billing_plan_id'],
				name: name,
				active: active,
				objects: objects,
				period: period,
				period_type: period_type,
				price: price
			};
			
			$.ajax({
				type: "POST",
				url: "func/fn_cpanel.server.php",
				data: data,
				cache: false,
				success: function(result)
				{
					if (result == 'OK')
					{
						loadGridList('billing');
						$("#dialog_billing_properties").dialog("close");
					}
				}
			});
			break;
	}
}

function billingPlanDeleteAll()
{
	confirmDialog(la['ARE_YOU_SURE_YOU_WANT_TO_DELETE_ALL_BILLING_PLANS'], function(response){
		if (response)
		{
			var data = {
				cmd: 'delete_all_billing_plans'
			};
			
			$.ajax({
				type: "POST",
				url: "func/fn_cpanel.server.php",
				data: data,
				success: function(result)
				{
					if (result == 'OK')
					{
						loadGridList('billing');
					}
				}
			});	
		}
	});
}

function billingPlanDelete(plan_id)
{
	confirmDialog(la['ARE_YOU_SURE_YOU_WANT_TO_DELETE'], function(response){
		if (response)
		{
			var data = {
				cmd: 'delete_billing_plan',
				plan_id: plan_id
			};
			
			$.ajax({
				type: "POST",
				url: "func/fn_cpanel.server.php",
				data: data,
				success: function(result)
				{
					if (result == 'OK')
					{
						loadGridList('billing');
					}
				}
			});
		}
	});
}

function templateProperties(cmd)
{
	switch (cmd)
	{
		default:
			var name = cmd;
			
			cpValues['template_edit_name'] = name;
			
			var variables = '';
			
			switch (name)
			{
				case "account_registration":
					variables = 	'<div class="row">'+la['VAR_TEMPLATE_SERVER_NAME']+'</div>\
							<div class="row">'+la['VAR_TEMPLATE_URL_LOGIN']+'</div>\
							<div class="row">'+la['VAR_TEMPLATE_EMAIL']+'</div>\
							<div class="row">'+la['VAR_TEMPLATE_USERNAME']+'</div>\
							<div class="row">'+la['VAR_TEMPLATE_PASSWORD']+'</div>';					
					break;
				
				case "account_registration_au":
					variables = 	'<div class="row">'+la['VAR_TEMPLATE_SERVER_NAME']+'</div>\
							<div class="row">'+la['VAR_TEMPLATE_URL_AU']+'</div>\
							<div class="row">'+la['VAR_TEMPLATE_URL_AU_MOBILE']+'</div>';	
					break;
				
				case "account_recover":
					variables = 	'<div class="row">'+la['VAR_TEMPLATE_SERVER_NAME']+'</div>\
							<div class="row">'+la['VAR_TEMPLATE_URL_LOGIN']+'</div>\
							<div class="row">'+la['VAR_TEMPLATE_EMAIL']+'</div>\
							<div class="row">'+la['VAR_TEMPLATE_USERNAME']+'</div>\
							<div class="row">'+la['VAR_TEMPLATE_PASSWORD']+'</div>';					
					break;
				
				case "account_recover_url":
					variables = 	'<div class="row">'+la['VAR_TEMPLATE_SERVER_NAME']+'</div>\
							<div class="row">'+la['VAR_TEMPLATE_URL_RECOVER']+'</div>';					
					break;
				
				case "database_backup":
					variables = 	'<div class="row">'+la['VAR_TEMPLATE_SERVER_NAME']+'</div>\
							<div class="row">'+la['VAR_TEMPLATE_URL_LOGIN']+'</div>';
					break;
				
				case "expiring_account":
					variables = 	'<div class="row">'+la['VAR_TEMPLATE_SERVER_NAME']+'</div>\
							<div class="row">'+la['VAR_TEMPLATE_URL_SHOP']+'</div>';				
					break;
				
				case "expiring_objects":
					variables = 	'<div class="row">'+la['VAR_TEMPLATE_SERVER_NAME']+'</div>\
							<div class="row">'+la['VAR_TEMPLATE_URL_SHOP']+'</div>';				
					break;
				
				case "event_email":
					variables = 	'<div class="row">'+la['VAR_TEMPLATE_NAME']+'</div>\
							<div class="row">'+la['VAR_TEMPLATE_IMEI']+'</div>\
							<div class="row">'+la['VAR_TEMPLATE_EVENT']+'</div>\
							<div class="row">'+la['VAR_TEMPLATE_LAT']+'</div>\
							<div class="row">'+la['VAR_TEMPLATE_LNG']+'</div>\
							<div class="row">'+la['VAR_TEMPLATE_ADDRESS']+'</div>\
							<div class="row">'+la['VAR_TEMPLATE_SPEED']+'</div>\
							<div class="row">'+la['VAR_TEMPLATE_ALT']+'</div>\
							<div class="row">'+la['VAR_TEMPLATE_ANGLE']+'</div>\
							<div class="row">'+la['VAR_TEMPLATE_DT_POS']+'</div>\
							<div class="row">'+la['VAR_TEMPLATE_DT_SER']+'</div>\
							<div class="row">'+la['VAR_TEMPLATE_G_MAP']+'</div>\
							<div class="row">'+la['VAR_TEMPLATE_TR_MODEL']+'</div>\
							<div class="row">'+la['VAR_TEMPLATE_PL_NUM']+'</div>\
							<div class="row">'+la['VAR_TEMPLATE_DRIVER']+'</div>';
					break;
				
				case "event_sms":
					variables = 	'<div class="row">'+la['VAR_TEMPLATE_NAME']+'</div>\
							<div class="row">'+la['VAR_TEMPLATE_IMEI']+'</div>\
							<div class="row">'+la['VAR_TEMPLATE_EVENT']+'</div>\
							<div class="row">'+la['VAR_TEMPLATE_LAT']+'</div>\
							<div class="row">'+la['VAR_TEMPLATE_LNG']+'</div>\
							<div class="row">'+la['VAR_TEMPLATE_ADDRESS']+'</div>\
							<div class="row">'+la['VAR_TEMPLATE_SPEED']+'</div>\
							<div class="row">'+la['VAR_TEMPLATE_ALT']+'</div>\
							<div class="row">'+la['VAR_TEMPLATE_ANGLE']+'</div>\
							<div class="row">'+la['VAR_TEMPLATE_DT_POS']+'</div>\
							<div class="row">'+la['VAR_TEMPLATE_DT_SER']+'</div>\
							<div class="row">'+la['VAR_TEMPLATE_G_MAP']+'</div>\
							<div class="row">'+la['VAR_TEMPLATE_TR_MODEL']+'</div>\
							<div class="row">'+la['VAR_TEMPLATE_PL_NUM']+'</div>\
							<div class="row">'+la['VAR_TEMPLATE_DRIVER']+'</div>';
					break;
			}
			
			document.getElementById('dialog_template_variables').innerHTML = variables;
			
			document.getElementById('dialog_template_name').value = la['TEMPLATE_' + name.toUpperCase()];
			
			templateProperties('load');
			
			$("#dialog_template_properties").dialog("open");
			break;
		
		case "load":
			
			var name = cpValues['template_edit_name'];
			var language = document.getElementById('dialog_template_language').value;
			
			var data = {
				cmd: 'load_template',
				name: name,
				language: language
			};
			
			$.ajax({
				type: "POST",
				url: "func/fn_cpanel.server.php",
				data: data,
				dataType: 'json',
				cache: false,
				success: function(result)
				{
					document.getElementById('dialog_template_subject').value = result['subject'];
					document.getElementById('dialog_template_message').value = result['message'];
				}
			});
			
			break;
			
		case "cancel":
			$("#dialog_template_properties").dialog("close");	
			break;
			
		case "save":
			var name = cpValues['template_edit_name'];
			var language = document.getElementById('dialog_template_language').value;
			var message = document.getElementById('dialog_template_message').value;
			var subject = document.getElementById('dialog_template_subject').value;
			
			var data = {
				cmd: 'save_template',
				name: name,
				language: language,
				message: message,
				subject: subject
			};
			
			$.ajax({
				type: "POST",
				url: "func/fn_cpanel.server.php",
				data: data,
				cache: false,
				success: function(result)
				{
					if (result == 'OK')
					{
						$("#dialog_template_properties").dialog("close");
					}
				}
			});
			break;
	}
}

function SMSGatewayClearQueue()
{
	confirmDialog(la['ARE_YOU_SURE_YOU_WANT_TO_CLEAR_SMS_QUEUE'], function(response){
		if (response)
		{
			var data = {
				cmd: 'clear_sms_queue'
			};
			
			$.ajax({
				type: "POST",
				url: "func/fn_cpanel.server.php",
				data: data,
				success: function(result)
				{
					if (result == 'OK')
					{
						document.getElementById('cpanel_manage_server_sms_gateway_total_in_queue').innerHTML = '0';
					}
				}
			});
		}
	});
}

function logDeleteAll()
{
	confirmDialog(la['ARE_YOU_SURE_YOU_WANT_TO_DELETE_ALL_LOGS'], function(response){
		if (response)
		{
			var data = {
				cmd: 'delete_all_logs'
			};
			
			$.ajax({
				type: "POST",
				url: "func/fn_cpanel.server.php",
				data: data,
				success: function(result)
				{
					if (result == 'OK')
					{
						loadGridList('logs');
					}
				}
			});
		}
	});
}

function logDelete(file)
{
	confirmDialog(la['ARE_YOU_SURE_YOU_WANT_TO_DELETE'], function(response){
		if (response)
		{
			var data = {
				cmd: 'delete_log',
				file: file
			};
			
			$.ajax({
				type: "POST",
				url: "func/fn_cpanel.server.php",
				data: data,
				success: function(result)
				{
					if (result == 'OK')
					{
						loadGridList('logs');
					}
				}
			});
		}
	});
}

function logOpen(file)
{
	window.open('logs/'+file,'_blank');
}

function serverCleanup(cmd)
{
        switch (cmd)
	{
		case "users":
			var server_cleanup_users_days =	document.getElementById('cpanel_manage_server_tools_server_cleanup_users_days').value;
			if ((server_cleanup_users_days < 1) || !isIntValid(server_cleanup_users_days))
			{
				server_cleanup_users_days = 0;
			}
	
			confirmDialog(la['ARE_YOU_SURE_YOU_WANT_TO_DELETE'], function(response){
				if (response)
				{
					var data = {
						cmd: 'server_cleanup_users',
						days: server_cleanup_users_days
					};
					
					$.ajax({
						type: "POST",
						url: "func/fn_cpanel.server.php",
						data: data,
						success: function(result)
						{
							if (result != '')
							{
								notifyDialog(la['TOTAL_ITEMS_DELETED'] + ' ' + result);
							}						
						}
					});
				}
			});

			break;
		case "objects_not_activated":
			var server_cleanup_objects_not_activated_days = document.getElementById('cpanel_manage_server_tools_server_cleanup_objects_not_activated_days').value;
			if ((server_cleanup_objects_not_activated_days < 1) || !isIntValid(server_cleanup_objects_not_activated_days))
			{
				server_cleanup_objects_not_activated_days = 0;
			}
			
			confirmDialog(la['ARE_YOU_SURE_YOU_WANT_TO_DELETE'], function(response){
				if (response)
				{
					var data = {
						cmd: 'server_cleanup_objects_not_activated',
						days: server_cleanup_objects_not_activated_days
					};
					
					$.ajax({
						type: "POST",
						url: "func/fn_cpanel.server.php",
						data: data,
						success: function(result)
						{
							if (result != '')
							{
								notifyDialog(la['TOTAL_ITEMS_DELETED'] + ' ' + result);
							}
						}
					});
				}
			});
			
			break;
		case "objects_not_used":
			confirmDialog(la['ARE_YOU_SURE_YOU_WANT_TO_DELETE'], function(response){
				if (response)
				{
					var data = {
						cmd: 'server_cleanup_objects_not_used'
					};
					
					$.ajax({
						type: "POST",
						url: "func/fn_cpanel.server.php",
						data: data,
						success: function(result)
						{
							if (result != '')
							{
								notifyDialog(la['TOTAL_ITEMS_DELETED'] + ' ' + result);
							}
						}
					});
				}
			});
			
			break;
		case "db_junk":
			confirmDialog(la['ARE_YOU_SURE_YOU_WANT_TO_DELETE'], function(response){
				if (response)
				{
					var data = {
						cmd: 'server_cleanup_db_junk'
					};
					
					$.ajax({
						type: "POST",
						url: "func/fn_cpanel.server.php",
						data: data,
						success: function(result)
						{
							if (result != '')
							{
								notifyDialog(la['TOTAL_ITEMS_DELETED'] + ' ' + result);
							}
						}
					});
				}
			});
			
			break;
	}
}