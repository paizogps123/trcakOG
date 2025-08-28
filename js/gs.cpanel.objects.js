function objectAdd(cmd)
{
	switch (cmd)
	{
		case "open":
			// set object add properties availability

			if(cpValues['accessSettings']['access_settings_object']['add']!=true){
				notifyDialog(la['NO_PERMISSION']);
				return;
			}

			if (cpValues['privileges'] == 'manager')
			{
				document.getElementById('dialog_object_add_manager_id').disabled = true;
			}
			
			document.getElementById('dialog_object_add_active').checked = true;
			document.getElementById('dialog_object_add_object_expire').checked = true;
			
			if (cpValues['privileges'] == 'manager')
			{
				if (cpValues['obj_days'] == 'true')
				{
					document.getElementById('dialog_object_add_object_expire_dt').value = cpValues['obj_days_dt'];
				}
				else
				{
					document.getElementById('dialog_object_add_object_expire_dt').value = moment().add('years', 1).format("YYYY-MM-DD");	
				}	
			}
			else
			{
				document.getElementById('dialog_object_add_object_expire_dt').value = moment().add('years', 1).format("YYYY-MM-DD");	
			}
			document.getElementById('dialog_object_add_object_install_date').value = moment().add('years', 1).format("YYYY-MM-DD");
			
			document.getElementById('dialog_object_add_name').value = '';
			document.getElementById('dialog_object_add_imei').value = '';
			document.getElementById('dialog_object_add_board_imei').value = '';
			document.getElementById('dialog_object_add_model').value = '';
			document.getElementById('dialog_object_add_vin').value = '';
			document.getElementById('dialog_object_add_plate_number').value = '';
			document.getElementById('dialog_object_add_device').value = '';
			document.getElementById('dialog_object_add_sim_number').value = '';
			document.getElementById('dialog_object_add_manager_id').value = 0;
			
			objectAddCheck();
			
			$('#dialog_object_add_users').tokenize().clear();
			$("#dialog_object_add").dialog("open");
			break;
		case "add":
			if(cpValues['accessSettings']['access_settings_object']['add']!=true){
				notifyDialog(la['NO_PERMISSION']);
				return;
			}
			var name = document.getElementById('dialog_object_add_name').value;
			var imei = document.getElementById('dialog_object_add_imei').value;
			var boardimei = document.getElementById('dialog_object_add_board_imei').value;
			var model = document.getElementById('dialog_object_add_model').value;
			var vin = document.getElementById('dialog_object_add_vin').value;
			var plate_number = document.getElementById('dialog_object_add_plate_number').value;
			var device = document.getElementById('dialog_object_add_device').value;
			var sim_number = document.getElementById('dialog_object_add_sim_number').value;
			var manager_id = document.getElementById('dialog_object_add_manager_id').value;
			var active = document.getElementById('dialog_object_add_active').checked;
			var object_expire = document.getElementById('dialog_object_add_object_expire').checked;
			var object_expire_dt = document.getElementById('dialog_object_add_object_expire_dt').value;
			var object_install_dt = document.getElementById('dialog_object_add_object_install_date').value;
			
			var user_ids = $('#dialog_object_add_users').tokenize().toArray();
			
			user_ids = JSON.stringify(user_ids);
			
			if (name == "")
			{
				notifyDialog(la['NAME_CANT_BE_EMPTY']);
				return;
			}
			
			if(!isIMEIValid(imei))
			{
				notifyDialog(la['IMEI_IS_NOT_VALID']);
				return;
			}
			
			if (device == '')
			{
				notifyDialog(la['DEVICE_CANT_EMPTY']);
				break;
			}
			
			// expire object
			if (object_expire == true)
			{
				if (object_expire_dt == '')
				{
					notifyDialog(la['DATE_CANT_BE_EMPTY']);
					break;
				}
			}
			else
			{
                object_expire_dt = '';
            }
			
			var data = {
				cmd: 'add_object',
				name: name,
				imei: imei,
				boardimei:boardimei,
				model: model,
				vin: vin,
				plate_number: plate_number,
				device: device,
				sim_number: sim_number,
				manager_id: manager_id,
				active: active,
				object_expire: object_expire,
				object_expire_dt: object_expire_dt,
				user_ids: user_ids,
				installdate:object_install_dt,
			};
			
		   $.ajax({
				type: "POST",
				url: "func/fn_cpanel.objects.php",
				data: data,
				success: function(result)
				{
					if (result == 'OK')
					{
						initSelectList('manager_list');
						initStats();
						
						$('#cpanel_user_list_grid').trigger("reloadGrid");
						$('#cpanel_object_list_grid').trigger("reloadGrid");
						$('#cpanel_unused_object_list_grid').trigger("reloadGrid");
						$("#dialog_object_add").dialog("close");
					}
					else if (result == 'ERROR_SYSTEM_OBJECT_LIMIT')
					{
						notifyDialog(la['SYSTEM_OBJECT_LIMIT_IS_REACHED']);
					}
					else if (result == 'ERROR_OBJECT_LIMIT')
					{
						notifyDialog(la['OBJECT_LIMIT_IS_REACHED']);
					}
					else if (result == 'ERROR_EXPIRATION_DATE_NOT_SET')
					{
						notifyDialog(la['OBJECT_EXPIRATION_DATE_MUST_BE_SET']);
					}
					else if (result == 'ERROR_EXPIRATION_DATE_TOO_LATE')
					{
						notifyDialog(la['OBJECT_EXPIRATION_DATE_IS_TOO_LATE']);
					}
					else if (result == 'ERROR_NO_PRIVILEGES')
					{
						notifyDialog(la['THIS_ACCOUNT_HAS_NO_PRIVILEGES_TO_DO_THAT']);
					}
					else if (result == 'ERROR_IMEI_EXISTS')
					{
						notifyDialog(la['THIS_IMEI_ALREADY_EXISTS']);
					}
					else if(result =='ERROR_BOARD_IMEI_EXISTS'){

						notifyDialog(la['THIS_BOARD_IMEI_ALREADY_EXISTS']);
					}
					else if(result =='NO_PERMISSION'){

						notifyDialog(la['NO_PERMISSION']);
					}
				}
			});
			break;
		case "cancel":
			$("#dialog_object_add").dialog("close");
			break;
	}
}

function objectAddCheck()
{
	var object_expire = document.getElementById('dialog_object_add_object_expire').checked;
	if (object_expire == true)
	{
                document.getElementById('dialog_object_add_object_expire_dt').disabled = false;
        }
	else
	{
		document.getElementById('dialog_object_add_object_expire_dt').disabled = true;
	}
}

function objectEdit(cmd)
{
	switch (cmd)
	{
		default:			
			var data = {
				cmd: 'load_object_data',
				imei: cmd
			};
			
			$.ajax({
				type: "POST",
				url: "func/fn_cpanel.objects.php",
				data: data,
				dataType: 'json',
				cache: false,
				success: function(result)
				{
					// set object edit properties availability
					if (cpValues['privileges'] == 'manager')
					{
						document.getElementById('dialog_object_edit_manager_id').disabled = true;

					}
					
					if ((cpValues['privileges'] != 'admin') && (cpValues['privileges'] != 'super_admin'))
					{
						document.getElementById('dialog_object_edit_imei').disabled = true;
						document.getElementById('dialog_object_edit_board_imei').disabled = true;
					}
					
					// set loaded properties
					cpValues['edit_object_imei'] = result['imei'];
					cpValues['edit_object_new_imei'] = '';
					cpValues['edit_object_board_imei'] = result['board_id'];
					cpValues['edit_object_new_board_imei'] = '';
					
					document.getElementById('dialog_object_edit_active').checked = strToBoolean(result['active']);
					
					var object_expire = strToBoolean(result['object_expire']);
					document.getElementById('dialog_object_edit_object_expire').checked = object_expire;
					if (object_expire == true)
					{
                        document.getElementById('dialog_object_edit_object_expire_dt').value = result['object_expire_dt'];
                    }
					else
					{
						document.getElementById('dialog_object_edit_object_expire_dt').value = '';
					}
					
					document.getElementById('dialog_object_edit_name').value = result['name'];
					document.getElementById('dialog_object_edit_imei').value = result['imei'];
					document.getElementById('dialog_object_edit_board_imei').value = result['board_id'];
					document.getElementById('dialog_object_edit_model').value = result['model'];
					document.getElementById('dialog_object_edit_vin').value = result['vin'];
					document.getElementById('dialog_object_edit_plate_number').value = result['plate_number'];
					document.getElementById('dialog_object_edit_device').value = result['device'];
					document.getElementById('dialog_object_edit_sim_number').value = result['sim_number'];
					document.getElementById('dialog_object_edit_manager_id').value = result['manager_id'];
					document.getElementById('dialog_object_add_object_install_date').value = result['installdate'];					
					objectEditCheck();

					if(result['device']=='Dispenser'){
						$("#show_dispensor_edit_device_tracker").css("display","block");
						
					}else{
						$("#show_dispensor_edit_device_tracker").css("display","none");
					}

					$('#dialog_object_edit_users').tokenize().clear();
					
					$('#dialog_object_edit_users').tokenize().options.newElements = true;
					var users = result['users'];
					for(var i=0;i<users.length;i++)
					{
						var value = users[i].value;
						var text = users[i].text;
						$('#dialog_object_edit_users').tokenize().tokenAdd(value, text);
					}
					$('#dialog_object_edit_users').tokenize().options.newElements = false;
				}
			});
			
			$("#dialog_object_edit").dialog("open");
			break;
		case "save":
			if(cpValues['accessSettings']['access_settings_object']['edit']!=true){
				notifyDialog(la['NO_PERMISSION']);
				return;
			}
			var active = document.getElementById('dialog_object_edit_active').checked;
			var object_expire = document.getElementById('dialog_object_edit_object_expire').checked;
			var object_expire_dt = document.getElementById('dialog_object_edit_object_expire_dt').value;
			var object_install_dt = document.getElementById('dialog_object_add_object_install_date').value;
			var name = document.getElementById('dialog_object_edit_name').value;
			var imei = document.getElementById('dialog_object_edit_imei').value;
			var boardimei = document.getElementById('dialog_object_edit_board_imei').value;
			var model = document.getElementById('dialog_object_edit_model').value;
			var vin = document.getElementById('dialog_object_edit_vin').value;
			var plate_number = document.getElementById('dialog_object_edit_plate_number').value;
			var device = document.getElementById('dialog_object_edit_device').value;
			var sim_number = document.getElementById('dialog_object_edit_sim_number').value;
			var manager_id = document.getElementById('dialog_object_edit_manager_id').value;
			
			var user_ids = $('#dialog_object_edit_users').tokenize().toArray();
			
			user_ids = JSON.stringify(user_ids);
			
			if (name == "")
			{
				notifyDialog(la['NAME_CANT_BE_EMPTY']);
				return;
			}
			
			if(!isIMEIValid(imei))
			{
				notifyDialog(la['IMEI_IS_NOT_VALID']);
				return;
			}
			
			// expire object
			if (object_expire == true)
			{
				if (object_expire_dt == '')
				{
					notifyDialog(la['DATE_CANT_BE_EMPTY']);
					break;
				}
			}
			else
			{
                                object_expire_dt = '';
                        }
			
			if (imei != cpValues['edit_object_imei'])
			{
				cpValues['edit_object_new_imei'] = imei;
				cpValues['edit_object_new_board_imei'] = boardimei;				  
				confirmDialog(la['ARE_YOU_SURE_YOU_WANT_TO_CHANGE_OBJECT_IMEI'], function(response){
					if (response)
					{
						responseSave();
					}
				});
            }else if(boardimei != cpValues['edit_object_board_imei']){
            	if (cpValues['privileges'] == 'super_admin' || cpValues['privileges'] == 'admin')
				{
	            	cpValues['edit_object_new_board_imei'] = boardimei;				  
					confirmDialog(la['ARE_YOU_SURE_YOU_WANT_TO_CHANGE_OBJECT_IMEI'], function(response){
					if (response)
					{
						responseSave();
					}
					});
				}else{
					notifyDialog(la['CHANGE_ONLY_OBJECT_IMEI_CONTACT_ADMIN_SUPER_ADMIN']);
				}
            }
			else
			{
				responseSave();
			}
			
			break;
		case "cancel":
			$("#dialog_object_edit").dialog("close");
			break;
	}
	
	function responseSave()
	{
            var data = {
			cmd: 'edit_object',
			active: active,
			object_expire: object_expire,
			object_expire_dt: object_expire_dt,
			name: name,
			imei: cpValues['edit_object_imei'],
			new_imei: cpValues['edit_object_new_imei'],
			model: model,
			vin: vin,
			plate_number: plate_number,
			device: device,
			sim_number: sim_number,
			manager_id: manager_id,
			user_ids: user_ids,
			boardid:cpValues['edit_object_board_imei'],
			new_boardimei:cpValues['edit_object_new_board_imei'],
			installdate:object_install_dt,
		};
		
		$.ajax({
			type: "POST",
			url: "func/fn_cpanel.objects.php",
			data: data,
			success: function(result)
			{
				if (result == 'OK')
				{
					initSelectList('manager_list');
					
					$("#dialog_object_edit").dialog("close");
				}
				else if (result == 'ERROR_EXPIRATION_DATE_NOT_SET')
				{
					notifyDialog(la['OBJECT_EXPIRATION_DATE_MUST_BE_SET']);
				}
				else if (result == 'ERROR_EXPIRATION_DATE_TOO_LATE')
				{
					notifyDialog(la['OBJECT_EXPIRATION_DATE_IS_TOO_LATE']);
				}
				else if (result == 'ERROR_IMEI_EXISTS')
				{
					notifyDialog(la['THIS_IMEI_ALREADY_EXISTS']);
				}
				else if (result == 'ERROR_BOARD_IMEI_EXISTS')
				{
					notifyDialog(la['THIS_BOARD_IMEI_ALREADY_EXISTS']);
				}
				else if (result == 'NO_PERMISSION')
				{
					notifyDialog(la['NO_PERMISSION']);
				}
			}
		});
        }
}

function objectEditCheck()
{
	var object_expire = document.getElementById('dialog_object_edit_object_expire').checked;
	if (object_expire == true)
	{
                document.getElementById('dialog_object_edit_object_expire_dt').disabled = false;
        }
	else
	{
		document.getElementById('dialog_object_edit_object_expire_dt').disabled = true;
	}
}

function objectClearHistory(imei)
{
	if(cpValues['accessSettings']['access_settings_clr_history']['delete']!=true){
		notifyDialog(la['NO_PERMISSION']);
		return;
	}
	confirmDialog(la['ARE_YOU_SURE_YOU_WANT_TO_CLEAR_HISTORY_EVENTS'], function(response){
		if (response)
		{
			var data = {
				cmd: 'clear_history_object',
				imei: imei
			};
			
			$.ajax({
				type: "POST",
				url: "func/fn_cpanel.objects.php",
				data: data,
				success: function(result)
				{
					if (result == 'OK')
					{
						$('#cpanel_object_list_grid').trigger("reloadGrid");	
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

function objectDelete(imei){
	if(cpValues['accessSettings']['access_settings_object']['delete']!=true){
		notifyDialog(la['NO_PERMISSION']);
		return;
	}
	confirmDialog(la['ARE_YOU_SURE_YOU_WANT_TO_DELETE_THIS_OBJECT_FROM_SYSTEM'], function(response){
		if (response)
		{
			var data = {
				cmd: 'delete_object',
				imei: imei
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
						
						$('#cpanel_user_list_grid').trigger("reloadGrid");
						$('#cpanel_object_list_grid').trigger("reloadGrid");	
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

function objectActivateSelected()
{
	var objects = $('#cpanel_object_list_grid').jqGrid ('getGridParam', 'selarrrow');
	
	if (objects == '')
	{
		notifyDialog(la['NO_ITEMS_SELECTED']);
		return;
        }
	
	confirmDialog(la['ARE_YOU_SURE_YOU_WANT_TO_ACTIVATE_SELECTED_ITEMS'], function(response){
		if (response)
		{
			var data = {
				cmd: 'activate_selected_objects',
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
							
						$('#cpanel_user_list_grid').trigger("reloadGrid");
						$('#cpanel_object_list_grid').trigger("reloadGrid");		
					}
				}
			});
		}
	});
}

function objectDeactivateSelected()
{
	var objects = $('#cpanel_object_list_grid').jqGrid ('getGridParam', 'selarrrow');
	
	if (objects == '')
	{
		notifyDialog(la['NO_ITEMS_SELECTED']);
		return;
        }
	
	confirmDialog(la['ARE_YOU_SURE_YOU_WANT_TO_DEACTIVATE_SELECTED_ITEMS'], function(response){
		if (response)
		{
			var data = {
				cmd: 'deactivate_selected_objects',
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
							
						$('#cpanel_user_list_grid').trigger("reloadGrid");
						$('#cpanel_object_list_grid').trigger("reloadGrid");		
					}
				}
			});
		}
	});
}

function objectClearHistorySelected()
{
	if(cpValues['accessSettings']['access_settings_clr_history']['delete']!=true){
		notifyDialog(la['NO_PERMISSION']);
		return;
	}

    var objects = $('#cpanel_object_list_grid').jqGrid ('getGridParam', 'selarrrow');
	
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
							
						$('#cpanel_object_list_grid').trigger("reloadGrid");	
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

function objectDeleteSelected()
{
	if(cpValues['accessSettings']['access_settings_object']['delete']!=true){
		notifyDialog(la['NO_PERMISSION']);
		return;
	}
	var objects = $('#cpanel_object_list_grid').jqGrid ('getGridParam', 'selarrrow');
	
	if (objects == '')
	{
		notifyDialog(la['NO_ITEMS_SELECTED']);
		return;
        }
	
	confirmDialog(la['ARE_YOU_SURE_YOU_WANT_TO_DELETE_SELECTED_ITEMS'], function(response){
		if (response)
		{
			var data = {
				cmd: 'delete_selected_objects',
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
							
						$('#cpanel_user_list_grid').trigger("reloadGrid");
						$('#cpanel_object_list_grid').trigger("reloadGrid");		
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

function unusedObjectDelete(imei){
	
	confirmDialog(la['ARE_YOU_SURE_YOU_WANT_TO_DELETE_THIS_UNUSED_OBJECT'], function(response){
		if (response)
		{
			var data = {
				cmd: 'delete_unused_object',
				imei: imei
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
						$('#cpanel_unused_object_list_grid').trigger("reloadGrid");	
					}
				}
			});	
		}
	});
}

function unusedObjectDeleteSelected()
{	
	var objects = $('#cpanel_unused_object_list_grid').jqGrid ('getGridParam', 'selarrrow');
	
	if (objects == '')
	{
		notifyDialog(la['NO_ITEMS_SELECTED']);
		return;
        }
	
	confirmDialog(la['ARE_YOU_SURE_YOU_WANT_TO_DELETE_SELECTED_ITEMS'], function(response){
		if (response)
		{
			var data = {
				cmd: 'delete_selected_unused_objects',
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
						$('#cpanel_unused_object_list_grid').trigger("reloadGrid");		
					}
				}
			});
		}
	});
}

edit_dispensercalibrationimei='';

function objectEdit_dispenser_calibration(imei){

	edit_dispensercalibrationimei=imei;
	editobject_calibratedata='';
	document.getElementById("object_dispenser_calibration_value_freqhz").value='';
	document.getElementById("object_dispenser_calibration_value_level").value='';
	document.getElementById("object_dispenser_calibration_value_volum").value='';
	$("#settings_object_dispenser_sensor_calibration_list_grid").jqGrid("clearGridData");
	$("#dialog_object_edit_dispenser_calibration_model").dialog("open");

	var data = {
		cmd: 'getobject_dispensercalibration_data',
		imei:imei
	};
	
	$.ajax({
		type: "POST",
		url: "func/fn_cpanel.objects.php",
		data: data,
		success: function(result)
		{
			editobject_calibratedata=result;
			if(editobject_calibratedata['fuelhz1']){
				dispenser_calibration=editobject_calibratedata['fuelhz1']['calibration_data'];
				document.getElementById("object_dispenser_calibration_value_param").value='fuelhz1';
				dispenser_calibration.sort(compare);
				Object_dispenserSensorCalibrationList();
			}
		}
	});

}

function check_param_dispenservalue(param){
	$("#settings_object_dispenser_sensor_calibration_list_grid").jqGrid("clearGridData");
	dispenser_calibration='';
	if(editobject_calibratedata[param]){
		dispenser_calibration=editobject_calibratedata[param]['calibration_data'];
		dispenser_calibration.sort(compare);
		Object_dispenserSensorCalibrationList();
	}
}

var dispenser_calibration = new Array;

function Object_dispenserSensorCalibrationAdd(){

	var hz = document.getElementById("object_dispenser_calibration_value_freqhz").value,
        level = document.getElementById("object_dispenser_calibration_value_level").value,
        volum = document.getElementById("object_dispenser_calibration_value_volum").value;
    isNumber(hz) || (hz = 0), isNumber(level) || (level = 0), isNumber(volum) || (volum = 0);
    for (var a = 0; a < dispenser_calibration.length; a++)
        if (dispenser_calibration[a].hz == hz) return void notifyBox("error", la.ERROR, la.SAME_X_CALIBRATION_CHECK_POINT_AVAILABLE);
	    dispenser_calibration.push({
	        hz:hz,
			level:level,
			volum:volum
	    });
	 dispenser_calibration.sort(compare);
	 for(var j=0;j<dispenser_calibration.length;j++){
		 	dispenser_calibration[j]['option']='custom';
	 }
	 if(dispenser_calibration[0]){
	 	dispenser_calibration[0]['option']='empty';
	 }
	 if(dispenser_calibration.length>1){
	 	dispenser_calibration[dispenser_calibration.length-1]['option']='full';
	 }
	 Object_dispenserSensorCalibrationList();
}

function Object_dispenserSensorCalibrationList() {

    var e = dispenser_calibration,
        t = [],
        a = $("#settings_object_dispenser_sensor_calibration_list_grid");
    if (a.clearGridData(!0), 0 != e.length) {
        for (var o = 0; o < e.length; o++) {
            var i = '<a href="#" onclick="Object_dispenserSensorCalibrationDel(' + o + ');" title="' + la.DELETE + '"><img src="theme/images/remove3.svg" /></a>';
            t.push({
                hz: e[o].hz,
                Level: e[o].level,
                volum: e[o].volum,
                modify: i
            })
        }
        for (var o = 0; o < t.length; o++) a.jqGrid("addRowData", o, t[o]);
        a.setGridParam({
            sortname: "x",
            sortorder: "asc"
        }).trigger("reloadGrid")
    }
}

function Object_dispenserSensorCalibrationDel(e) {
    dispenser_calibration.splice(e, 1), Object_dispenserSensorCalibrationList();
}

function save_objectdispenser_calibration(s){
	switch(s){
		case 'save':
			dispenser_calibration.sort(compare);
			 for(var j=0;j<dispenser_calibration.length;j++){
				 	dispenser_calibration[j]['option']='custom';
			 }
			 if(dispenser_calibration[0]){
			 	dispenser_calibration[0]['option']='empty';
			 }
			 if(dispenser_calibration.length>1){
			 	dispenser_calibration[dispenser_calibration.length-1]['option']='full';
			 }
			var param = document.getElementById("object_dispenser_calibration_value_param").value;
			if(dispenser_calibration.length<2){
				notifyBox("error", la.ERROR, la.PLEASE_SET_EMPTY_AND_FULL_VALUE);
				return;
			}
			var data = {
				cmd: 'save_dispenser_calibration',
				calibration: dispenser_calibration,
				imei:edit_dispensercalibrationimei,
				param:param
			};
			
			$.ajax({
				type: "POST",
				url: "func/fn_cpanel.objects.php",
				data: data,
				success: function(result)
				{
					if (result == 'OK')
					{
						$("#dialog_object_edit_dispenser_calibration_model").dialog("close");
					}
				}
			});
			break;
		case 'cancel':
			document.getElementById("object_dispenser_calibration_value_freqhz").value='';
			document.getElementById("object_dispenser_calibration_value_level").value='';
			document.getElementById("object_dispenser_calibration_value_volum").value='';
			$("#settings_object_dispenser_sensor_calibration_list_grid").jqGrid("clearGridData");
			$("#dialog_object_edit_dispenser_calibration_model").dialog("close");
			break;
	}
}

function get_object_current_freqhz(){
	var param = document.getElementById("object_dispenser_calibration_value_param").value;
	var data = {
		cmd: 'get_current_freq_hz',
		imei:edit_dispensercalibrationimei,
		param:param
	};
	
	$.ajax({
		type: "POST",
		url: "func/fn_cpanel.objects.php",
		data: data,
		success: function(result)
		{
			if(result==''){
				result=0;
			}
			document.getElementById("object_dispenser_calibration_value_freqhz").value=result;
		}
	});
}

function compare( a, b ) {
  if ( parseInt(a.hz) > parseInt(b.hz) ){
    return -1;
  }
  if ( parseInt(a.hz) < parseInt(b.hz) ){
    return 1;
  }
  return 0;
}