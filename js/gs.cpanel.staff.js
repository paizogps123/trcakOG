$(document).ready(function(){

	$('#dialog_staff_manag_imei_search').keyup(function()
	{
		var searchArea = $('#dialog_staff_manag_imei');
		searchFirstList($(this).val(), searchArea);	         
	});
	
	$('#dialog_staff_manag_clientid_search').keyup(function()
	{
		var searchArea = $('#dialog_staff_manag_clientid');
		searchFirstList($(this).val(), searchArea);	    
		loadobject($("#dialog_staff_manag_clientid").val());
	});
	
	$('#dialog_staff_manag_staff_data_search').keyup(function()
	{
		var searchArea = $('#dialog_staff_manag_staff_data');
		searchFirstList($(this).val(), searchArea);	         
	});	
	$('#dialog_staff_manag_search_sim').keyup(function()
	{
		if(document.getElementById('dialog_staff_manag_edit_sim').checked==true){
			var searchArea = $('#dialog_staff_manag_select_new_sim_number');
			searchFirstList($(this).val(), searchArea);	         
		}
	});

});
		

function staffAdd(cmd)
{
	switch (cmd)
	{
		case "open":
			ServiceAdd('cancel');
			selectWorkType('');
			$("#dialog_staff_manag_add").dialog("open");
			loadobject();
			break;
		case "register":
			var email = document.getElementById('dialog_staff_manag_add_email').value;
			var send = document.getElementById('dialog_staff_manag_add_send').checked;
			
			if(!isEmailValid(email))
			{
				notifyDialog(la['THIS_EMAIL_IS_NOT_VALID']);
				return;
			}
			
			var data = {
				cmd: 'register_staff',
				email: email,
				send: send,
				manager_id: cpValues['manager_id']
			};
			
		   $.ajax({
				type: "POST",
				url: "func/fn_cpanel.staff.php",
				data: data,
				success: function(result)
				{
					if (result == 'OK')
					{
						initStats();
						initSelectList('manager_list');
						
						$('#cpanel_staff_manag_grid_list').trigger("reloadGrid");
						$("#dialog_staff_manag_add").dialog("close");
					}
					else if (result == 'ERROR_EMAIL_EXISTS')
					{
						notifyDialog(la['THIS_EMAIL_ALREADY_EXISTS']);
					}
					else if (result == 'ERROR_NOT_SENT')
					{
						notifyDialog(la['CANT_SEND_EMAIL'] + ' ' + la['CONTACT_ADMINISTRATOR']);
					}
				}
			});
			break;
		case "cancel":
			$("#dialog_staff_manag_add").dialog("close");
			break;
			
	}
}




function loadworktype()
{
	   $.ajax({async: false,
           type: "GET",
           url: "func/fn_cpanel.staff.php",
           data:  {"cmd":"select_work_type"},
           dataType: "json",
           success: function (respns) {
           		if (respns.type=="S") { 
           			cpValues['staffworktype']=respns.mydata.type;
           			cpValues['staffworkreport']=respns.mydata.report;
           		}
               // var av=document.getElementById("dialog_staff_manag_worktype");
               // av.options.length = 0,av.options.add(new Option("Select","Select"));
               // if (respns.type=="S") { 
               //  for (var iv=0;iv<respns.mydata.length;iv++) {
               //     av.options.add(new Option(respns.mydata[iv].work, respns.mydata[iv].work_id))
                // }
               // }
           },
           failure: function (response) {
               
           }
       });
}



function loadclienttype()
{
	   $.ajax({
		   async: false,
           type: "GET",
           url: "func/fn_cpanel.staff.php",
           data:  {"cmd":"select_client_type"},
           dataType: "json",
           success: function (respns) {
              
               var av=document.getElementById("dialog_staff_manag_clientid");
               av.options.length = 0,av.options.add(new Option("Select","Select"));
               if (respns.type=="S") { 
                for (var iv=0;iv<respns.mydata.length;iv++) {
                   av.options.add(new Option(respns.mydata[iv].username, respns.mydata[iv].id))
                }
               }
           },
           failure: function (response) {
               
           }
       });
}
function loadstaffdata()
{
	   $.ajax({async: false,
           type: "GET",
           url: "func/fn_cpanel.staff.php",
           data:  {"cmd":"select_staff_data"},
           dataType: "json",
           success: function (respns) {              
               var av=document.getElementById("dialog_staff_manag_staff_data");
               av.options.length = 0,av.options.add(new Option("Select","Select"));
               if (respns.type=="S") { 
                for (var iv=0;iv<respns.mydata.length;iv++) {
                   av.options.add(new Option(respns.mydata[iv].staff_name, respns.mydata[iv].staff_id))
                }
               }
           },
           failure: function (response) {
               
           }
       });
}

function loadobject(id)
{
	   $.ajax({
		   async: false,
           type: "GET",
           url: "func/fn_cpanel.staff.php",
           data:  {"cmd":"select_staff_imei","user":id},
           dataType: "json",
           success: function (respns) {
              
               var av=document.getElementById("dialog_staff_manag_imei");
               av.options.length = 0,av.options.add(new Option("Select","Select"));
               if (respns.type=="S") { 
                for (var iv=0;iv<respns.mydata.length;iv++) {
                   av.options.add(new Option(respns.mydata[iv].imei+'-'+respns.mydata[iv].name, respns.mydata[iv].imei))
                }
               }
           },
           failure: function (response) {
               
           }
       });
}




$(function(){
	$("#dialog_staff_manag_clientid").change(function () {
		loadobject($("#dialog_staff_manag_clientid").val());
	    });
	});

$(function(){
	$("#dialog_staff_manag_desi_id").change(function () {
		loadobject($("#dialog_staff_manag_desi_id").val());
	    });
	});

var vrfidtrip=[];
vrfidtrip.service_id=0;



function ServiceAdd(savecancel)
{
	if(savecancel=="save")
	{
		if( $("#dialog_staff_manag_clientid option:selected").val()=="Select" ||
			$("#dialog_staff_manag_staff_data option:selected").val()=="Select" ||
			$("#dialog_staff_manag_imei option:selected").val()=="Select" ||
			$("#dialog_staff_manag_company").val() =="" || 		
			$("#dialog_staff_manag_site_location").val() =="" || 		
			$("#dialog_staff_manag_work_date").val() =="" || 		
			$("#dialog_staff_manag_check_object_name").val() =="" ||           
			$("#dialog_staff_manag_work option:selected").val()==""
			)
		{
			notifyBox("error", la.ERROR, la.PLSSTAFFNAME);
			return;
		}

		if((document.getElementById('dialog_staff_manag_check_object_name').checked==true && $("#dialog_staff_manag_object").val()=='') || (document.getElementById('dialog_staff_manag_check_veticletype').checked==true && $("#dialog_staff_manag_vehicle_type").val()=='') || (document.getElementById('dialog_staff_manag_check_tank_size').checked==true && $("#dialog_staff_manag_fuel_tanksize").val()=='') || (document.getElementById('dialog_staff_manag_check_fuel1').checked==true && $("#dialog_staff_manag_fuel1").val()=='') || (document.getElementById('dialog_staff_manag_check_fuel2').checked==true && $("#dialog_staff_manag_fuel2").val()=='') || (document.getElementById('dialog_staff_manag_edit_sim').checked==true && $("#dialog_staff_manag_select_sim_number").val()=='')){
	   		notifyBox("error", la.ERROR, la.PLSSTAFFNAME);
			return;
	   }

		$("#dialog_staff_manag_add").dialog("close");

		if(document.getElementById('dialog_staff_manag_edit_sim').checked==true){
			newsimid=$("#dialog_staff_manag_select_new_sim_number").val();
		}else{
			newsimid=0;
		}

		var selWorkType= []; 
		var accessories_list= []; 
		var report_list= []; 
		$('#dialog_staff_manag_worktype :selected').each(function(i, selected){ 
			selWorkType[i] = $(selected).val(); 
		});
		// if(selWorkType.length==0){
		// 	var selWorkType='';
		// }
		
		$('#dialog_staff_manag_accessories :selected').each(function(i, selected){ 
			accessories_list[i] = $(selected).val(); 
		});

		// if(accessories_list.length==0){
		// 	var accessories_list='';
		// }
		
		$('#dialog_staff_manag_report :selected').each(function(i, selected){ 
			report_list[i] = $(selected).val(); 
		});

		// if(report_list.length==0){
		// 	var report_list='';
		// }

		var user_ids = $('#dialog_staff_manag_change_users').tokenize().toArray();			
		user_ids = JSON.stringify(user_ids);

		if(user_ids==''){
			notifyBox("error", la.ERROR, la.PLSSTAFFNAME);
			return;
		}
		
		 var data = {cmd: "save_staff",service_id: vrfidtrip.service_id,
          client_id:$("#dialog_staff_manag_clientid option:selected").val(),
          staff_id:$("#dialog_staff_manag_staff_data option:selected").val(),
          company:$("#dialog_staff_manag_company").val(),
          site_location:$("#dialog_staff_manag_site_location").val(),
          work_date:$("#dialog_staff_manag_work_date").val()+" "+$("#dialog_staff_manag_work_hour").val()+":"+$("#dialog_staff_manag_work_minute").val()+":00",
          imei:$("#dialog_staff_manag_imei").val(),  

          objectname:$("#dialog_staff_manag_object").val(),
          chobjectname:document.getElementById('dialog_staff_manag_check_object_name').checked,

          vehicletype:$("#dialog_staff_manag_vehicle_type").val(),  
          chvehicletype:document.getElementById('dialog_staff_manag_check_veticletype').checked, 

          chfuel_tanksize:document.getElementById('dialog_staff_manag_check_tank_size').checked,     
          fuel_tanksize:$("#dialog_staff_manag_fuel_tanksize").val(),

          chfuel1:document.getElementById('dialog_staff_manag_check_fuel1').checked,        
          fuel1:$("#dialog_staff_manag_fuel1").val(),

          chfuel2:document.getElementById('dialog_staff_manag_check_fuel2').checked,       
          fuel2:$("#dialog_staff_manag_fuel2").val(),  

          simnumber:$("#dialog_staff_manag_select_sim_number").val(),
          simaction:document.getElementById('dialog_staff_manag_edit_sim').checked,

          newsimid:newsimid,
          works:$("#dialog_staff_manag_work option:selected").val(),
          work_type:selWorkType,
          accessories_list:accessories_list,
          report_list:report_list,
          status:$("#dialog_staff_manag_status option:selected").val(),
          remark:$("#dialog_staff_manag_remark").val(),
          service_status:$("#dialog_staff_manag_service_close").val(),
          warrenty_expire:$("#dialog_staff_manag_warrenty_expire").val(),
          warrenty:document.getElementById("dialog_staff_manag_checkbox").checked,
          reason:$("#dialog_staff_manag_reason").val(),
          editstaffdata:staffentryselectObject,
          chuser:document.getElementById('dialog_staff_manag_check_change_users').checked,   
          users:user_ids
          // office_note:$("#dialog_staff_manag_note").val() 
		   };
	   
	  //  if(data['works']!='' && (data['work_type'].length==0) || (data['accessories_list'].length==0) || (data['report_list'].length==0)){
	  //  		notifyBox("error", la.ERROR, la.PLSSTAFFNAME);
			// return;
	  //  }
		$.ajax({
                type: "POST",
                url: "func/fn_cpanel.staff.php",
                data: data,
                success: function(o) {
                    switch (o) {
                        default: var p = "error";
                        var r = la.ERROR;
                        var q = o;
                        notifyBox(p, r, q);
                        ServiceAdd('cancel');
                        break;
                        case "Name Already Exists":
                         var p = "error";
                         var r = la.ERROR;
                        var q = o;notifyBox(p, r, q);
                        break;
                        case "OK":
                        ServiceAdd('cancel');
                        break;
                    }                 
                }
            });
	}
	else 
		if (savecancel == "cancel") {
			vrfidtrip.service_id = 0;
			document.getElementById("dialog_staff_manag_clientid").value = "Select";
			document.getElementById("dialog_staff_manag_staff_data").value = "Select";
			$("#dialog_staff_manag_company").val("");
			$("#dialog_staff_manag_site_location").val("");
			$("#dialog_staff_manag_work_hour").val("");
			$("#dialog_staff_manag_work_minute").val("");
			$("#dialog_staff_manag_imei").val("");
			$("#dialog_staff_manag_object").val("");
			$("#dialog_staff_manag_vehicle_type").val("");
			$("#dialog_staff_manag_fuel1").val("");
			$("#dialog_staff_manag_fuel2").val("");
			$("#dialog_staff_manag_fuel_tanksize").val("");
			$("#dialog_staff_manag_select_sim_number").val("");
			$("#dialog_staff_manag_select_new_sim_number").val("");
			$("#dialog_staff_manag_remark").val("");
			$("#dialog_staff_manag_service_close").val("");
			$("#dialog_staff_manag_reason").val("");
			document.getElementById("dialog_staff_manag_work").value = "";
			document.getElementById("dialog_staff_manag_worktype").value = "";
			document.getElementById("dialog_staff_manag_accessories").value = "";
			document.getElementById("dialog_staff_manag_report").value = "";
			$("#dialog_staff_manag_status option:selected").removeAttr("selected"), 
			document.getElementById("dialog_staff_manag_checkbox").checked = false;
			document.getElementById("dialog_staff_manag_check_object_name").checked = false;
			document.getElementById("dialog_staff_manag_check_veticletype").checked = false;
			document.getElementById("dialog_staff_manag_check_fuel1").checked = false;
			document.getElementById("dialog_staff_manag_check_fuel2").checked = false;
			document.getElementById("dialog_staff_manag_check_tank_size").checked = false;
			document.getElementById("dialog_staff_manag_edit_sim").checked = false;
			ServiceAdd('refresh');
			$('#dialog_staff_manag_change_users').tokenize().clear();
			$("#dialog_staff_manag_add").dialog("close");
		} 
	else if (savecancel == "refresh") {
		$("#cpanel_staff_manag_list_grid").setGridParam({url:'func/fn_cpanel.staff.php?cmd=select_staff&from='+$("#dialog_boarding_holidayfromdate").val()+'&to='+$("#dialog_boarding_holidaytodate").val()});
		$("#cpanel_staff_manag_list_grid").trigger("reloadGrid");
		ServiceAdd('search');
	}
}

function staffedit(service_id)
{
	$("#dialog_staff_manag_add").dialog("open");
	 var custom = jQuery("#cpanel_staff_manag_list_grid").jqGrid('getRowData');
	
	 for($itr=0;$itr<custom.length;$itr++)
	 {
	 if(custom[$itr]["service_id"]==service_id)
	 {		
		document.getElementById("dialog_staff_manag_clientid").value=custom[$itr]["client_id"];
	 	document.getElementById("dialog_staff_manag_staff_data").value=custom[$itr]["staff_id"];
	 	$("#dialog_staff_manag_location").val(custom[$itr]["site_location"]);
		
	 	var schedule=custom[$itr]["schedule_date"].split(" ");
	 	$("#dialog_staff_manag_date_from").val(schedule[0]);
	 	schedule =schedule[1].split(":");
	 	$("#dialog_staff_manag_hour_schedule").val(schedule[0]);
	 	$("#dialog_staff_manag_minute_schedule").val(schedule[1]);
		
		var intime=custom[$itr]["intime"].split(" ");
		$("#dialog_staff_manag_date_in").val(intime[0]);
		intime =intime[1].split(":");
		$("#dialog_staff_manag_hour_in").val(intime[0]);
		$("#dialog_staff_manag_minute_in").val(intime[1]);
		
		var outtime=custom[$itr]["outtime"].split(" ");
		$("#dialog_staff_manag_date_out").val(outtime[0]);
		outtime =outtime[1].split(":");
		$("#dialog_staff_manag_hour_out").val(outtime[0]);
		$("#dialog_staff_manag_minute_out").val(outtime[1]);
		
		
		loadobject(custom[$itr]["client_id"]);
		$("#dialog_staff_manag_imei").val(custom[$itr]["imei"]);
		$("#dialog_staff_manag_work").val(custom[$itr]["works"]);
		var iv = document.getElementById("dialog_staff_manag_worktype");
		sv = custom[$itr]["work_type"].split(",");
		multiselectSetValues(iv, sv);
		document.getElementById("dialog_staff_manag_checkbox").checked=strToBoolean(custom[$itr]["warrenty"]);
		$("#dialog_staff_manag_note").val(custom[$itr]["office_note"]);
		vrfidtrip.service_id=service_id;
	 	break;
	 
	 }
	 
	 }
}


function staffdelete(deptcode)
{
	confirmDialog("Are you want to delete?", function(ve) {
	if (ve) {
	 var dat = {cmd: "delete_staff",service_id:deptcode};
		$.ajax({
                type: "POST",
                url: "func/fn_cpanel.staff.php",
                data: dat,
                success: function(o) {
                    switch (o) {
                        default: var p = "error";
                        var r = la.ERROR;
                        var q = o;notifyBox(p, r, q);
                        //ServiceAdd('cancel');
                        break;
                        case "OK":
                        	ServiceAdd('cancel');
                        break;
                    }                 
                }
            });
     }
	});
}





function OpenDialog()
{
	var users = $('#cpanel_staff_manag_list_grid').jqGrid ('getGridParam', 'selarrrow');
	
	if (users == '')
	{   
		ServiceAdd('cancel');
		
		$("#dialog_staff_manag_employee_master").dialog("open");
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
				url: "func/fn_cpanel.staff.php",
				data: data,
				success: function(result)
				{
					if (result == 'OK')
					{
						initStats();
						initSelectList('manager_list');
							
						$('#cpanel_staff_manag_list_grid').trigger("reloadGrid");	
					}
				}
			});
		}
	});
}


function loaddesignation()
{
	   $.ajax({async: false,
           type: "GET",
           url: "func/fn_cpanel.staff.php",
           data:  {"cmd":"select_designation"},
           dataType: "json",
           success: function (respns) {
               var av=document.getElementById("dialog_staff_manag_desi_id");
               av.options.length = 0,av.options.add(new Option("Select","Select"));
               if (respns.type=="S") { 
                for (var iv=0;iv<respns.mydata.length;iv++) {
                   av.options.add(new Option(respns.mydata[iv].desi_name, respns.mydata[iv].desi_id));
                }
               }
           },
           failure: function (response) {
               
           }
       });
}


var vrfidtrip=[];
vrfidtrip.staff_id=0;

function staffemp(savecancel)
{
	if(savecancel=="save")
	{
		if( 		
			$("#dialog_staff_manag_staffname").val() =="" || 
			$("#dialog_staff_manag_gender option:selected").val()=="Select" ||
			$("#dialog_staff_manag_dob").val() =="" ||
			$("#dialog_staff_manag_doj").val() =="" || 
			$("#dialog_staff_manag_qualification").val() =="" || 
			$("#dialog_staff_manag_experiance").val() =="" ||             
			$("#dialog_staff_manag_mobile").val() =="" || 			
			$("#dialog_staff_manag_address").val() =="" ||
			$("#dialog_staff_manag_card_id").val() =="" ||
			$("#dialog_staff_manag_desi_id option:selected").val()=="Select" ||
			$("#dialog_staff_manag_notes").val() =="" 		
			)
		{
			notifyBox("error", la.ERROR, la.PLSSTAFFNAME);
			return;
		}		
		 var dat = {cmd: "save_emp",staff_id: vrfidtrip.staff_id,		                 
				          staff_name:$("#dialog_staff_manag_staffname").val(),
		                  gender:$("#dialog_staff_manag_gender option:selected").val(),			              
		                  dob:$("#dialog_staff_manag_dob").val(),
		                  jod:$("#dialog_staff_manag_doj").val(),
		                  qualification:$("#dialog_staff_manag_qualification").val(),
		                  experiance:$("#dialog_staff_manag_experiance").val(),
		                  mobile:$("#dialog_staff_manag_mobile").val(),			              
		                  address:$("#dialog_staff_manag_address").val(),
		                  card_id:$("#dialog_staff_manag_card_id").val(),
		                  desi_id:$("#dialog_staff_manag_desi_id option:selected").val(),			              
		                  note:$("#dialog_staff_manag_notes").val()
		            };
		$.ajax({
                type: "POST",
                url: "func/fn_cpanel.staff.php",
                data: dat,
                success: function(o) {
                    switch (o) {
                        default: var p = "error";
                        var r = la.ERROR;
                        var q = o;notifyBox(p, r, q);
                        staffemp('cancel');
                        break;
                        case "Name Already Exists":
                         var p = "error";
                         var r = la.ERROR;
                        var q = o;notifyBox(p, r, q);
                        break;
                        case "OK":
                        staffemp('cancel');
                        break;
                    }                 
                }
            });
	}
	else 
		if (savecancel == "cancel") {
		vrfidtrip.staff_id = 0;
		$("#dialog_staff_manag_staffname").val("");
		document.getElementById("dialog_staff_manag_gender").value = "Select";		
		$("#dialog_staff_manag_dob").val("");
		$("#dialog_staff_manag_doj").val("");
		$("#dialog_staff_manag_qualification").val("");
		$("#dialog_staff_manag_experiance").val("");		
		$("#dialog_staff_manag_mobile").val("");
		$("#dialog_staff_manag_address").val("");
		$("#dialog_staff_manag_card_id").val("");
		document.getElementById("dialog_staff_manag_desi_id").value = "Select";	
		
		$("#dialog_staff_manag_notes").val("");
		staffemp('refresh');
		$("#dialog_staff_manag_employee_master").dialog("close");
	} 
	else if (savecancel == "refresh") {
		$("#cpanel_staff_manag_emp_list_grid").setGridParam({url:'func/fn_cpanel.staff.php?cmd=select_staff_emp'});
		$("#cpanel_staff_manag_emp_list_grid").trigger("reloadGrid");
		staffemp('search');
	}
}

function staffempedit(staff_id)
{
	 var custom = jQuery("#cpanel_staff_manag_emp_list_grid").jqGrid('getRowData');
	
	 for($itr=0;$itr<custom.length;$itr++)
	 {
	 if(custom[$itr]["staff_id"]==staff_id)
	 {		
		$("#dialog_staff_manag_staffname").val(custom[$itr]["staff_name"]);
		document.getElementById("dialog_staff_manag_gender").value=custom[$itr]["gender"];
		$("#dialog_staff_manag_dob").val(custom[$itr]["dob"]);
		$("#dialog_staff_manag_doj").val(custom[$itr]["jod"]);
		$("#dialog_staff_manag_qualification").val(custom[$itr]["qualification"]);
		$("#dialog_staff_manag_experiance").val(custom[$itr]["experiance"]);	 	
		$("#dialog_staff_manag_mobile").val(custom[$itr]["mobile"]);
		$("#dialog_staff_manag_address").val(custom[$itr]["address"]);
		$("#dialog_staff_manag_card_id").val(custom[$itr]["card_id"]);
		document.getElementById("dialog_staff_manag_desi_id").value=custom[$itr]["desi_id"];		
		$("#dialog_staff_manag_notes").val(custom[$itr]["note"]);		
		vrfidtrip.staff_id=staff_id;
	 	break;
	 
	 }	 
	 }
}

function staffempdelete(deptcode)
{
	confirmDialog("Are you want to delete?", function(ve) {
	if (ve) {
	 var dat = {cmd: "delete_staff_emp",staff_id:deptcode};
		$.ajax({
                type: "POST",
                url: "func/fn_cpanel.staff.php",
                data: dat,
                success: function(o) {
                    switch (o) {
                        default: var p = "error";
                        var r = la.ERROR;
                        var q = o;notifyBox(p, r, q);
                       // staffemp('cancel');
                        break;
                        case "OK":
                        	staffemp('cancel');
                        break;
                    }                 
                }
            });
     }
	});
}

function selectWorkType(select){
	document.getElementById("dialog_staff_manag_object").disabled = true;
	var wtype=cpValues['staffworktype'];
	var wreport=cpValues['staffworkreport'];
	var av=document.getElementById("dialog_staff_manag_worktype");
	var re=document.getElementById("dialog_staff_manag_report");
    av.options.length = 0;
    // av.options.add(new Option("Select","Select"));
    re.options.length = 0;
    // re.options.add(new Option("Select","Select"));
	switch(select){
		case "Installation":		    
            for (var iv=0;iv<wtype.length;iv++) {
            	if(wtype[iv].installation==true){
               		av.options.add(new Option(wtype[iv].work, wtype[iv].work_id))
               	}
            }
            for (var i=0;i<wreport.length;i++) {
            	if(wreport[i].installation==true){
               		re.options.add(new Option(wreport[i].work, wreport[i].work_id))
               	}
            }
            document.getElementById("dialog_staff_manag_object").disabled = false;
		break;
		case "Reinstallation":
			for (var iv=0;iv<wtype.length;iv++) {
            	if(wtype[iv].reinstallation==true){
               		av.options.add(new Option(wtype[iv].work, wtype[iv].work_id))
               	}
            }
            for (var i=0;i<wreport.length;i++) {
            	if(wreport[i].reinstallation==true){
               		re.options.add(new Option(wreport[i].work, wreport[i].work_id))
               	}
            }
		break;
		case "Offline":
			for (var iv=0;iv<wtype.length;iv++) {
            	if(wtype[iv].offline==true){
               		av.options.add(new Option(wtype[iv].work, wtype[iv].work_id))
               	}
            }
            for (var i=0;i<wreport.length;i++) {
            	if(wreport[i].offline==true){
               		re.options.add(new Option(wreport[i].work, wreport[i].work_id))
               	}
            }
		break;
		case "Replace":
			for (var iv=0;iv<wtype.length;iv++) {
            	if(wtype[iv].replace==true){
               		av.options.add(new Option(wtype[iv].work, wtype[iv].work_id))
               	}
            }
            for (var i=0;i<wreport.length;i++) {
            	if(wreport[i].replace==true){
               		re.options.add(new Option(wreport[i].work, wreport[i].work_id))
               	}
            }
		break;
		case "Remove":
			for (var iv=0;iv<wtype.length;iv++) {
            	if(wtype[iv].remove==true){
               		av.options.add(new Option(wtype[iv].work, wtype[iv].work_id))
               	}
            }
            for (var i=0;i<wreport.length;i++) {
            	if(wreport[i].remove==true){
               		re.options.add(new Option(wreport[i].work, wreport[i].work_id))
               	}
            }
		break;
		case "General":
			for (var iv=0;iv<wtype.length;iv++) {
            	if(wtype[iv].general==true){
               		av.options.add(new Option(wtype[iv].work, wtype[iv].work_id))
               	}
            }
            for (var i=0;i<wreport.length;i++) {
            	if(wreport[i].general==true){
               		re.options.add(new Option(wreport[i].work, wreport[i].work_id))
               	}
            }
		break;

	}
}


function detailObjectlist(imei){
	 var data = {
	 	cmd: "load_detail_object_list",
	 	object:imei
	 };
	$.ajax({
            type: "POST",
            url: "func/fn_cpanel.staff.php",
            data: data,
            success: function(o) {
            	staffentryselectObject=o;
            	initSelectList('fuel_sensor_list');
            	// initSelectList('accessories_list');
            	document.getElementById('dialog_staff_manag_object').value=o.name;
            	document.getElementById('dialog_staff_manag_warrenty_expire').value=o.installdate;
            	document.getElementById('dialog_staff_manag_checkbox').checked=o.warrenty_status;
            	document.getElementById('dialog_staff_manag_fuel1').value=o.fuel1;            	
            	document.getElementById('dialog_staff_manag_fuel2').value=o.fuel2;          	
            	document.getElementById('dialog_staff_manag_select_sim_number').value=o.mob_number+'/'+o.sim_provider;
            	var ss = document.getElementById("dialog_staff_manag_accessories");
            	if(o.accessories!=''){
	            	for (var i=0; i<ss.options.length; i++)
				    {
			            if(ss.options[i]['value']=="A/C Line" && o.accessories['ac_line']=='yes'){
			            	ss.options[i].selected = true;
			            }
			            else if(ss.options[i]['value']=="Temp Sensor" && o.accessories['temp_sensor']=='yes'){
			            	ss.options[i].selected = true;
			            }
			            else if(ss.options[i]['value']=="RFID Reader" && o.accessories['rfid']=='yes'){
			            	ss.options[i].selected = true;
			            }
			            else if(ss.options[i]['value']=="Panic Button" && o.accessories['panic_button']=='yes'){
			            	ss.options[i].selected = true;
			            }
			            else if(ss.options[i]['value']=="Buzzer" && o.accessories['buzzer']=='yes'){
			            	ss.options[i].selected = true;
			            }
			            else if(ss.options[i]['value']=="Ignition Line Relay" && o.accessories['ignition_line_relay']=='yes'){
			            	ss.options[i].selected = true;
			            }
			            else if(ss.options[i]['value']=="CCTV" && o.accessories['cctv']=='yes'){
			            	ss.options[i].selected = true;
			            }
				    }
				}
			    $('#dialog_staff_manag_change_users').tokenize().clear();				
				$('#dialog_staff_manag_change_users').tokenize().options.newElements = true;
				var users = o['users'];
				for(var i=0;i<users.length;i++)
				{
					var value = users[i].value;
					var text = users[i].text;
					$('#dialog_staff_manag_change_users').tokenize().tokenAdd(value, text);
				}
				$('#dialog_staff_manag_change_users').tokenize().options.newElements = false;

            }
        });
}

function detailObjectlist_onkeyup(){
	var simei=$("#dialog_staff_manag_imei").val();
	detailObjectlist(simei);
}

function staffcheckvariable(e){
	switch(e){
		case "chobject":
			document.getElementById("dialog_staff_manag_object").disabled = true;			
			if(document.getElementById('dialog_staff_manag_check_object_name').checked==true){
				document.getElementById("dialog_staff_manag_object").disabled = false;
			}
		break;
		case "chvehicletype":
			document.getElementById("dialog_staff_manag_vehicle_type").disabled = true;			
			if(document.getElementById('dialog_staff_manag_check_veticletype').checked==true){
				document.getElementById("dialog_staff_manag_vehicle_type").disabled = false;
			}
		break;
		case "chfueltanksize":
			document.getElementById("dialog_staff_manag_fuel_tanksize").disabled = true;			
			if(document.getElementById('dialog_staff_manag_check_tank_size').checked==true){
				document.getElementById("dialog_staff_manag_fuel_tanksize").disabled = false;
			}
		break;
		case "chfuel1":
			document.getElementById("dialog_staff_manag_fuel1").disabled = true;			
			if(document.getElementById('dialog_staff_manag_check_fuel1').checked==true){
				document.getElementById("dialog_staff_manag_fuel1").disabled = false;
			}
		break;
		case "chfuel2":
			document.getElementById("dialog_staff_manag_fuel2").disabled = true;			
			if(document.getElementById('dialog_staff_manag_check_fuel2').checked==true){
				document.getElementById("dialog_staff_manag_fuel2").disabled = false;
			}
		break;
		case "chchangeuser":
			document.getElementById("dialog_staff_manag_change_users").disabled = true;			
			if(document.getElementById('dialog_staff_manag_check_change_users').checked==true){
				document.getElementById("dialog_staff_manag_change_users").disabled = false;
			}
		break;
		case "changemewsim":
			document.getElementById("dialog_staff_manag_search_sim").disabled = true;	
			document.getElementById("dialog_staff_manag_select_new_sim_number").disabled = true;			
		
			if(document.getElementById('dialog_staff_manag_edit_sim').checked==true){
				document.getElementById("dialog_staff_manag_search_sim").disabled = false;	
				document.getElementById("dialog_staff_manag_select_new_sim_number").disabled = false;

			}
			if(document.getElementById('dialog_staff_manag_edit_sim').checked==true){
				if($("#dialog_staff_manag_select_sim_number").val()!=''){
					confirmDialog("Are you want to remove old sim?", function(ve) {
						if(ve){
							newsimid=$("#dialog_staff_manag_select_new_sim_number").val();
						}
					});
				}
				var data={
					cmd:"load_sim_details"
				}
				$.ajax({
					type:"POST",
					url:"func/fn_cpanel.staff.php",
					data:data,
					success:function(result){
						var simselect = document.getElementById('dialog_staff_manag_select_new_sim_number');
						if (simselect)
						{
							simselect.options.length = 0; // clear out existing items	
							simselect.options.add(new Option('Select',''));
							for (var key in result)
							{
								var sim = result[key];
								simselect.options.add(new Option(sim.number,sim.id));
							}
						}
					}	
				});
			}else{
				var re=document.getElementById("dialog_staff_manag_select_new_sim_number");
		    	re.options.length = 0;
			}
		break;
	}	
}

function staffserviceDetails(service_id){
	var data={
			cmd:"view_service_entry_details",
			service_id:service_id
		}
		$.ajax({
			type:"POST",
			url:"func/fn_cpanel.staff.php",
			data:data,
			success:function(result){
				document.getElementById('view_service_id').innerHTML = result['service_id'];
				document.getElementById('view_service_client_name').innerHTML =result['username'] ;
				document.getElementById('view_service_staff_name').innerHTML =result['staff_name'] ;
				document.getElementById('view_service_company').innerHTML =result['company'] ;
				document.getElementById('view_service_site_location').innerHTML =result['site_location'] ;
				document.getElementById('view_service_work_date').innerHTML =result['schedule_date'] ;
				document.getElementById('view_service_object_name').innerHTML =result['object_name'] ;
				document.getElementById('view_service_vehicle_type').innerHTML =result['vehicle_type'] ;
				document.getElementById('view_service_fuel_tank_size').innerHTML =result['fuel_tank_size'] ;
				document.getElementById('view_service_fuel1').innerHTML =result['fuel1'] ;
				document.getElementById('view_service_fuel2').innerHTML =result['fuel2'] ;
				document.getElementById('view_service_sim_number').innerHTML =result['mob_number']+'/'+result['sim_provider'] ;
				document.getElementById('view_service_work').innerHTML =result['works'] ;
				document.getElementById('view_service_type_work').innerHTML ='No Data found';
				document.getElementById('view_service_work_report').innerHTML ='No Data found';
				document.getElementById('view_service_accessories').innerHTML ='No Data found';
				var worktype='';
				if(result['worktype'].length>0){
					for(var i=0;i<result['worktype'].length;i++){
						worktype+=result['worktype'][i]['work']+'<br><br>  '
					}
				}
				document.getElementById('view_service_type_work').innerHTML =worktype;
				var workreport='';
				if(result['workreport'].length>0){
					for(var i=0;i<result['workreport'].length;i++){
						workreport+=result['workreport'][i]['work']+'<br><br>  '
					}
				}				
				document.getElementById('view_service_work_report').innerHTML =workreport;
				var accessories='';
				if(result['accessories']!=''){
					if(result['accessories']['ac_line']=='yes'){
						accessories+='AC line <br><br>  ';
					}
					if(result['accessories']['buzzer']=='yes'){
						accessories+='Buzzer <br>  ';
					}
					if(result['accessories']['cctv']=='yes'){
						accessories+='CCTV <br><br>  ';
					}
					if(result['accessories']['ignition_line_relay']=='yes'){
						accessories+='Ignition Line Relay <br><br>  ';
					}
					if(result['accessories']['panic_button']=='yes'){
						accessories+='Panic Button <br><br>  ';
					}
					if(result['accessories']['rfid']=='yes'){
						accessories+='Rfid <br><br>  ';
					}
					if(result['accessories']['temp_sensor']=='yes'){
						accessories+='Temp Sensor';
					}
				}				
				document.getElementById('view_service_accessories').innerHTML =accessories;
				document.getElementById('view_service_status').innerHTML =result['status'] ;
				document.getElementById('view_service_remark').innerHTML =result['remark'] ;
				document.getElementById('view_service_service_close').innerHTML =result['service_close'] ;
				document.getElementById('view_service_warrenty').innerHTML =result['warrenty'] ;
				document.getElementById('view_service_reason').innerHTML =result['reason'] ;
				document.getElementById('view_service_update_settings').innerHTML ='';
				var cupdate='';
				if(result['updatechanges']!=''){
					cupdate='<table class="mws-table"><tr class="tv thbg"><th>Name</th><th>Current Settings</th><th>Old Settings</th><tr>';
					for(var j=0;j<result['updatechanges'].length;j++){
						cupdate+='<tr class="tv thbg"><td>'+result['updatechanges'][j]['name']+'</td><td>'+result['updatechanges'][j]['new']+'</td><td>'+result['updatechanges'][j]['value']+'</td></tr>';
					}
					cupdate+='</table>';
				}
				document.getElementById('view_service_update_settings').innerHTML =cupdate;
				$("#dialog_staff_view_service").dialog("open");
			}	
		});
}

function refreshServiceEntryGrid(){
	var clientid=$("#dialog_staff_manag_clientid").val();
	$("#cpanel_staff_manag_old_service_list").setGridParam({url:'func/fn_cpanel.staff.php?cmd=staff_client_service_list&client='+clientid+'&imei='+$("#dialog_staff_manag_imei").val()+'&staffid='+$("#dialog_staff_manag_staff_data").val()+''});
	$("#cpanel_staff_manag_old_service_list").trigger("reloadGrid");
}

var staffentryselectObject=new Array();