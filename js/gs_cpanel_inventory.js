function swipe() {
   window.open('https://vignette.wikia.nocookie.net/watamote/images/1/1e/Megumi_Imae.png/revision/latest?cb=20130924141316','Image','width=largeImage.stylewidth,height=largeImage.style.height,resizable=1');
}

function loadSupplierList(){
	var data = {
			cmd:'load_supplier_data',
		};

		$.ajax({
			type: "POST",
			url: "func/fn_cpanel.inventory.php",
			data: data,
			success: function(result)
			{
			var supplierdata=result.length;
			if (document.getElementById('supplier_list_data') != undefined)
				{
					document.getElementById('supplier_list_data').innerHTML = '('+supplierdata+')'; 
				}
				// document.getElementById('cpanel_supplier_id').value=supplierdata.length+1;
			}
		});
}
function supplierView(e){
var data = {
			cmd:'load_supplier_id_data',
			sup_id:e
		};

		$.ajax({
			type: "POST",
			url: "func/fn_cpanel.inventory.php",
			data: data,
			success: function(r)
			{
				if(r!=''){
					document.getElementById("cpanel_hrefv_view_document").href = "data/user/supplier/"+r[0].gst_photo;
					document.getElementById("view_supplier_id").innerHTML = r[0].sup_id;
					document.getElementById("view_supplier_name").innerHTML = r[0].name;
					document.getElementById("view_supplier_category").innerHTML = r[0].category;
					document.getElementById("view_supplier_gstno").innerHTML = r[0].gst_no;
					document.getElementById("view_supplier_email").innerHTML = r[0].mail;
					document.getElementById("view_supplier_phone").innerHTML = r[0].phone;
					document.getElementById("view_supplier_address").innerHTML = r[0].commercial_addr;
					document.getElementById("view_supplier_bankname").innerHTML = r[0].bank_name;
					document.getElementById("view_supplier_branchname").innerHTML = r[0].branch_name;
					document.getElementById("view_supplier_accno").innerHTML = r[0].account_no;
					document.getElementById("view_supplier_banifsc").innerHTML = r[0].ifsc;
					document.getElementById("view_supplier_add_name").innerHTML = r[0].contact_name;
					document.getElementById("view_supplier_add_mobile").innerHTML = r[0].contact_mobile;
					document.getElementById("view_supplier_add_email").innerHTML = r[0].contact_mail;
					document.getElementById("view_supplier_add_designation").innerHTML = r[0].contact_desi;
					document.getElementById("view_supplier_add_description").innerHTML = r[0].contact_description;
					$("#dialog_view_supplier").dialog("open");
				}
			}
		});
}

function supplierEdit(id){
	var data = {
			cmd:'load_supplier_id_data',
			sup_id:id
		};

		$.ajax({
			type: "POST",
			url: "func/fn_cpanel.inventory.php",
			data: data,
			success: function(r)
			{
				if(r!=''){
					document.getElementById("cpanel_edit_view_document").style.display = "block";
					document.getElementById("cpanel_href_view_document").href = "data/user/supplier/"+r[0].gst_photo;
					document.getElementById("addnewSupplier").style.display = "none";
					document.getElementById("updateSupplier").style.display = "block";
					document.getElementById('supplieridrow').style.display='none';
					document.getElementById('cpanel_supplier_category').value=r[0].category;
					document.getElementById('cpanel_supplier_name').value=r[0].name;
					document.getElementById('cpanel_edit_supplier_id').value=r[0].sup_id;
					document.getElementById('cpanel_supplier_gstno').value=r[0].gst_no;
					document.getElementById('cpanel_supplier_documentfile').value=r[0].gst_photo;
					document.getElementById('cpanel_supplier_com_adderss').value=r[0].commercial_addr;
					document.getElementById('cpanel_supplier_com_mailid').value=r[0].mail;
					document.getElementById('cpanel_supplier_com_phone').value=r[0].phone;
					document.getElementById('cpanel_supplier_bank_name').value=r[0].bank_name;
					document.getElementById('cpanel_supplier_branch_name').value=r[0].branch_name;
					document.getElementById('cpanel_supplier_account_number').value=r[0].account_no;
					document.getElementById('cpanel_supplier_ifsc_code').value=r[0].ifsc;
					document.getElementById('cpanel_supplier_additional_name').value=r[0].contact_name;
					document.getElementById('cpanel_supplier_additional_phone').value=r[0].contact_mobile;
					document.getElementById('cpanel_supplier_additional_email').value=r[0].contact_mail;
					document.getElementById('cpanel_supplier_additional_designation').value=r[0].contact_desi;
					document.getElementById('cpanel_supplier_additional_description').value=r[0].contact_description;
					$("#dialog_edit_supplier").dialog("open");
				}
			}
		});
     
}

function supplierDelte(r) {
	var data = {
			cmd:'delete_supplier_id_data',
			sup_id:r
		};

		$.ajax({
			type: "POST",
			url: "func/fn_cpanel.inventory.php",
			data: data,
			success: function(r)
			{
				if(r!=''){
					$('#cpanel_inventory_supplier_list').trigger("reloadGrid");
				}
			}
		});
}

function upSupplierDoc()
{
	// a bit dirty sollution, maybe will make better in the feature :)
	document.getElementById('load_file').addEventListener('change', uploadSupplierFile, false);
	document.getElementById('load_file').click();
}

function uploadSupplierFile(evt)
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
				var url = "func/fn_upload.php?file=supplier_png";
			}
			else if(image.src.includes("application/pdf")){
				var url = "func/fn_upload.php?file=supplier_pdf";
			}
			else if(image.src.includes("application/doc"))
			{
				var url = "func/fn_upload.php?file=supplier_doc";
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
						document.getElementById('cpanel_supplier_documentfile').src = result + "?t=" + new Date().getTime();
						
						document.getElementById('cpanel_supplier_documentfile').value = result;
						
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

function adddNewSupplier(e){
	 switch (e){
	    case "save":
		var sup_category= document.getElementById('cpanel_supplier_category').value,
			sup_name= document.getElementById('cpanel_supplier_name').value,
			sup_gstno= document.getElementById('cpanel_supplier_gstno').value,
			sup_doc_pho= document.getElementById('cpanel_supplier_documentfile').value,
			sup_con_add= document.getElementById('cpanel_supplier_com_adderss').value,
			sup_com_mail= document.getElementById('cpanel_supplier_com_mailid').value,
			sup_com_phone= document.getElementById('cpanel_supplier_com_phone').value,
			sup_bankname= document.getElementById('cpanel_supplier_bank_name').value,
			sup_branchname= document.getElementById('cpanel_supplier_branch_name').value,
			sup_accountno= document.getElementById('cpanel_supplier_account_number').value,
			sup_ifsc= document.getElementById('cpanel_supplier_ifsc_code').value,
			sup_adtnl_name= document.getElementById('cpanel_supplier_additional_name').value,
			sup_adtnl_phone= document.getElementById('cpanel_supplier_additional_phone').value,
			sub_adtnl_email= document.getElementById('cpanel_supplier_additional_email').value,
			sub_adtinl_designation= document.getElementById('cpanel_supplier_additional_designation').value,
			sub_adtinl_description= document.getElementById('cpanel_supplier_additional_description').value;

			if(sup_category=='' ||sup_name=='' ||sup_gstno=='' ||sup_con_add=='' ||sup_com_mail=='' ||sup_com_phone=='' ){
				notifyDialog(la['PLSFILLALLREQUIREDDETAILS']);
			}else{

				var data = {
					cmd: 'save_supplier_data',
					s_category:sup_category,
					s_name:sup_name,
					s_gst:sup_gstno,
					s_docp:sup_doc_pho,
					s_cadd:sup_con_add,
					s_cemail:sup_com_mail,
					s_c_phone:sup_com_phone,
					s_bname:sup_bankname,
					s_brname:sup_branchname,
					s_accno:sup_accountno,
					s_ifsc:sup_ifsc,
					s_alname:sup_adtnl_name,
					s_alphone:sup_adtnl_phone,
					s_alemail:sub_adtnl_email,
					s_aldesi:sub_adtinl_designation,
					s_aldesc:sub_adtinl_description
				};

				$.ajax({
					type: "POST",
					url: "func/fn_cpanel.inventory.php",
					data: data,
					success: function(result)
					{
						if (result == 'OK')
						{
							notifyDialog(la['SUPPLIER_SAVED_SUCCESSFULLY']);
							$('#cpanel_inventory_supplier_list').trigger("reloadGrid");
							$("#dialog_edit_supplier").dialog("close");
						}
					}
				});
			}
		break;
		case "add":
			$("#dialog_edit_supplier").dialog("open");
			document.getElementById('cpanel_supplier_category').value='';
			document.getElementById('cpanel_supplier_name').value='';
			document.getElementById('cpanel_supplier_gstno').value='';
			document.getElementById('cpanel_supplier_documentfile').value='';
			document.getElementById('cpanel_supplier_com_adderss').value='';
			document.getElementById('cpanel_supplier_com_mailid').value='';
			document.getElementById('cpanel_supplier_com_phone').value='';
			document.getElementById('cpanel_supplier_bank_name').value='';
			document.getElementById('cpanel_supplier_branch_name').value='';
			document.getElementById('cpanel_supplier_account_number').value='';
			document.getElementById('cpanel_supplier_ifsc_code').value='';
			document.getElementById('cpanel_supplier_additional_name').value='';
			document.getElementById('cpanel_supplier_additional_phone').value='';
			document.getElementById('cpanel_supplier_additional_email').value='';
			document.getElementById('cpanel_supplier_additional_designation').value='';
			document.getElementById('cpanel_supplier_additional_description').value='';
			document.getElementById('cpanel_edit_supplier_id').value='';
			document.getElementById('supplieridrow').style.display='none';
			document.getElementById("updateSupplier").style.display = "none";
			document.getElementById("addnewSupplier").style.display = "block";
			document.getElementById("cpanel_edit_view_document").style.display = "none";


		break;
		case 'update':
		var supe_category= document.getElementById('cpanel_supplier_category').value,
			supe_name= document.getElementById('cpanel_supplier_name').value,
			supe_id= document.getElementById('cpanel_edit_supplier_id').value,
			supe_gstno= document.getElementById('cpanel_supplier_gstno').value,
			supe_doc_pho= document.getElementById('cpanel_supplier_documentfile').value,
			supe_con_add= document.getElementById('cpanel_supplier_com_adderss').value,
			supe_com_mail= document.getElementById('cpanel_supplier_com_mailid').value,
			supe_com_phone= document.getElementById('cpanel_supplier_com_phone').value,
			supe_bankname= document.getElementById('cpanel_supplier_bank_name').value,
			supe_branchname= document.getElementById('cpanel_supplier_branch_name').value,
			supe_accountno= document.getElementById('cpanel_supplier_account_number').value,
			supe_ifsc= document.getElementById('cpanel_supplier_ifsc_code').value,
			supe_adtnl_name= document.getElementById('cpanel_supplier_additional_name').value,
			supe_adtnl_phone= document.getElementById('cpanel_supplier_additional_phone').value,
			sube_adtnl_email= document.getElementById('cpanel_supplier_additional_email').value,
			sube_adtinl_designation= document.getElementById('cpanel_supplier_additional_designation').value,
			sube_adtinl_description= document.getElementById('cpanel_supplier_additional_description').value;
			var data = {
				cmd: 'update_supplier_data',
				se_category:supe_category,
				se_name:supe_name,
				se_id:supe_id,
				se_gst:supe_gstno,
				se_docp:supe_doc_pho,
				se_cadd:supe_con_add,
				se_cemail:supe_com_mail,
				se_c_phone:supe_com_phone,
				se_bname:supe_bankname,
				se_brname:supe_branchname,
				se_accno:supe_accountno,
				se_ifsc:supe_ifsc,
				se_alname:supe_adtnl_name,
				se_alphone:supe_adtnl_phone,
				se_alemail:sube_adtnl_email,
				se_aldesi:sube_adtinl_designation,
				se_aldesc:sube_adtinl_description
			};

			$.ajax({
				type: "POST",
				url: "func/fn_cpanel.inventory.php",
				data: data,
				success: function(result)
				{
					if (result != 'OK')
					{
						notifyDialog(la['SUPPLIER_UPDATE_SUCCESSFULLY']);
						$("#dialog_edit_supplier").dialog("close");
						$('#cpanel_inventory_supplier_list').trigger("reloadGrid");
					}
				}
			});
	}
}

function loadinventorydata(){
	var data={
		cmd:'load_sim_usage'
	};
	$.ajax({
		type:"POST",
		url:"func/fn_cpanel.inventory.php",
		data:data,
		success:function(s){
			if(s!=''){
				document.getElementById('simcard_list_usage_status').innerHTML = '(' + s['usedsim']+'/'+s['totalsim'] + ')';
			}else{
				notifyDialog(la['NO_DATA']);
			}
		}
	});
}

function editsimcarddetails(id){
	if ((cpValues['privileges'] != 'super_admin') && (cpValues['privileges'] != 'admin') && (cpValues['privileges'] != 'admin'))
	{
		notifyDialog(la['NO_PERMISSION']);
		return;
	}
	var data={
		cmd:'edit_simcard_list',
		id:id
	};
	$.ajax({
		type:"POST",
		url:"func/fn_cpanel.inventory.php",
		data:data,
		success:function(e){
			if(e!='NO'){
				$(".inventory_simlist_multiple").remove();
				var edcounter=document.getElementsByClassName("inventory_simlist_multiple");
				edcounter=edcounter.length;
				if(edcounter==0){
					$("#inventory_add_simlist_button").click();
				}
				$('#addnewsimdetails').hide();
				$('#updatesimdetails').show();
				$("#dialog_view_inventory_simcards").dialog("open");
				document.getElementById('add_inventory_simcard_mobilenumber_1').value=e.mob_number;
				document.getElementById('add_inventory_simcard_selectsim_id_1').value=e.id;
				document.getElementById('add_inventory_simcard_cardnumber_1').value=e.sim_number;
				document.getElementById('add_inventory_simcard_cardimsi_1').value=e.sim_imsi;
				document.getElementById('add_inventory_simcard_provider_1').value=e.sim_provider;
			}	
		}
	})
}

function removesimcarddetails(id){
	if ((cpValues['privileges'] != 'super_admin') && (cpValues['privileges'] != 'admin') && (cpValues['privileges'] != 'manager'))
	{
		notifyDialog(la['NO_PERMISSION']);
		return;
	}
	confirmDialog(la['ARE_YOU_SURE_YOU_WANT_TO_REMOVE'], function(response){
		if (response)
		{
			var data={
		  		cmd:'remove_simcard_list',
		  		simid:id
		  	};
		  	$.ajax({
		  		type:"POST",
		  		url: "func/fn_cpanel.inventory.php",
				data: data,
				success: function(result)
				{
					if(result=='OK'){
						notifyDialog(la['SIM_REMOVE_SUCCESSFULLY']);
						$("#cpanel_inventory_simcard_list").trigger("reloadGrid");
					}else{
						notifyDialog(result);
					}			
				}
		  	})
		}
	});
}

function activesimcarddetails(id){
	if ((cpValues['privileges'] != 'super_admin') && (cpValues['privileges'] != 'admin'))
	{
		notifyDialog(la['NO_PERMISSION']);
		return;
	}
	var data={
  		cmd:'active_simcard_list',
  		simid:id
  	};
  	$.ajax({
  		type:"POST",
  		url: "func/fn_cpanel.inventory.php",
		data: data,
		success: function(result)
		{
			if(result=='OK'){
				notifyDialog(la['SIM_ACTIVE_SUCCESSFULLY']);
				$("#cpanel_inventory_simcard_list").trigger("reloadGrid");
			}else{
				notifyDialog(la[result]);
			}			
		}
  	})
}

function deletesimcarddetails(id){
	if ((cpValues['privileges'] != 'super_admin') && (cpValues['privileges'] != 'admin'))
	{
		notifyDialog(la['NO_PERMISSION']);
		return;
	}
	confirmDialog(la['ARE_YOU_SURE_YOU_WANT_TO_DELETE'], function(response){
		if (response)
		{
			var data={
		  		cmd:'delete_simcard_list',
		  		simid:id
		  	};
		  	$.ajax({
		  		type:"POST",
		  		url: "func/fn_cpanel.inventory.php",
				data: data,
				success: function(result)
				{
					if(result=='OK'){
						notifyDialog(la['SIM_DELETE_SUCCESSFULLY']);
						$(".inventory_simlist_multiple").remove();
						$("#cpanel_inventory_simcard_list").trigger("reloadGrid");
					}else{
						notifyDialog(la[result]);
					}			
				}
		  	})
		}
	});
}

function activesimcarddetailsSelected(){
	if ((cpValues['privileges'] != 'super_admin') && (cpValues['privileges'] != 'admin'))
	{
		notifyDialog(la['NO_PERMISSION']);
		return;
	}
	var $grid = $("#cpanel_inventory_simcard_list"), selIds = $grid.jqGrid("getGridParam", "selarrrow");
    if("" == selIds)
    {
      notifyBox("error", la.ERROR, la.NO_ITEMS_SELECTED) ;
      return;
    }
    var data={
  		cmd:'active_simcard_list_select',
  		simid:selIds
  	};
  	$.ajax({
  		type:"POST",
  		url: "func/fn_cpanel.inventory.php",
		data: data,
		success: function(result)
		{
			if(result=='OK'){
				notifyDialog(la['SIM_ACTIVE_SUCCESSFULLY']);
				$("#cpanel_inventory_simcard_list").trigger("reloadGrid");
			}else{
				notifyDialog(la[result]);
			}			
		}
  	})
}

function removesimcarddetailsSelected(){
	if ((cpValues['privileges'] != 'super_admin') && (cpValues['privileges'] != 'admin') && (cpValues['privileges'] != 'manager'))
	{
		notifyDialog(la['NO_PERMISSION']);
		return;
	}
	confirmDialog(la['ARE_YOU_SURE_YOU_WANT_TO_REMOVE'], function(response){
		if (response)
		{
			var $grid = $("#cpanel_inventory_simcard_list"), selIds = $grid.jqGrid("getGridParam", "selarrrow");
		    if("" == selIds)
		    {
		      notifyBox("error", la.ERROR, la.NO_ITEMS_SELECTED) ;
		      return;
		    }
		    var data={
		  		cmd:'remove_simcard_list_select',
		  		simid:selIds
		  	};
		  	$.ajax({
		  		type:"POST",
		  		url: "func/fn_cpanel.inventory.php",
				data: data,
				success: function(result)
				{
					if(result=='OK'){
						notifyDialog(la['SIM_REMOVE_SUCCESSFULLY']);
						$(".inventory_simlist_multiple").remove();
						$("#cpanel_inventory_simcard_list").trigger("reloadGrid");
					}else{
						notifyDialog(result);
						$(".inventory_simlist_multiple").remove();
						$("#cpanel_inventory_simcard_list").trigger("reloadGrid");
					}			
				}
		  	})
		}
	});
}

function deletesimcarddetailsSelected(){
	if ((cpValues['privileges'] != 'super_admin') && (cpValues['privileges'] != 'admin'))
	{
		notifyDialog(la['NO_PERMISSION']);
		return;
	}
	confirmDialog(la['ARE_YOU_SURE_YOU_WANT_TO_DELETE'], function(response){
		if (response)
		{
			var $grid = $("#cpanel_inventory_simcard_list"), selIds = $grid.jqGrid("getGridParam", "selarrrow");
		    if("" == selIds)
		    {
		      notifyBox("error", la.ERROR, la.NO_ITEMS_SELECTED) ;
		      return;
		    }
		    var data={
		  		cmd:'delete_simcard_list_select',
		  		simid:selIds
		  	};
		  	$.ajax({
		  		type:"POST",
		  		url: "func/fn_cpanel.inventory.php",
				data: data,
				success: function(result)
				{
					if(result=='OK'){
						notifyDialog(la['SIM_DELETE_SUCCESSFULLY']);
						$(".inventory_simlist_multiple").remove();
						$("#cpanel_inventory_simcard_list").trigger("reloadGrid");
					}else{
						notifyDialog(la[result]);
					}			
				}
		  	})
		}
	});
     
}

function suspendsimcarddetailsSelected(){
	if ((cpValues['privileges'] != 'super_admin') && (cpValues['privileges'] != 'admin'))
	{
		notifyDialog(la['NO_PERMISSION']);
		return;
	}
	confirmDialog(la['ARE_YOU_SURE_YOU_WANT_TO_SUSPEND'], function(response){
		if (response)
		{
			var $grid = $("#cpanel_inventory_simcard_list"), selIds = $grid.jqGrid("getGridParam", "selarrrow");
		    if("" == selIds)
		    {
		      notifyBox("error", la.ERROR, la.NO_ITEMS_SELECTED) ;
		      return;
		    }
		    var data={
		  		cmd:'suspend_simcard_list_select',
		  		simid:selIds
		  	};
		  	$.ajax({
		  		type:"POST",
		  		url: "func/fn_cpanel.inventory.php",
				data: data,
				success: function(result)
				{
					if(result=='OK'){
						notifyDialog(la['SIM_DEACTIVATE_SUSPEND']);
						$(".inventory_simlist_multiple").remove();
						$("#cpanel_inventory_simcard_list").trigger("reloadGrid");
					}else{
						notifyDialog(la[result]);
					}			
				}
		  	})
		}
	});
     
}

function deactivatesimcarddetailsSelected(){
	if ((cpValues['privileges'] != 'super_admin') && (cpValues['privileges'] != 'admin'))
	{
		notifyDialog(la['NO_PERMISSION']);
		return;
	}
	confirmDialog(la['ARE_YOU_SURE_YOU_WANT_TO_DEACTIVATE'], function(response){
		if (response)
		{
			var $grid = $("#cpanel_inventory_simcard_list"), selIds = $grid.jqGrid("getGridParam", "selarrrow");
		    if("" == selIds)
		    {
		      notifyBox("error", la.ERROR, la.NO_ITEMS_SELECTED) ;
		      return;
		    }
		    var data={
		  		cmd:'deactivate_simcard_list_select',
		  		simid:selIds
		  	};
		  	$.ajax({
		  		type:"POST",
		  		url: "func/fn_cpanel.inventory.php",
				data: data,
				success: function(result)
				{
					if(result=='OK'){
						notifyDialog(la['SIM_DEACTIVATE_SUCCESSFULLY']);
						$(".inventory_simlist_multiple").remove();
						$("#cpanel_inventory_simcard_list").trigger("reloadGrid");
					}else{
						notifyDialog(la[result]);
					}			
				}
		  	})
		}
	});
     
}


function inventorySimdetails(s){
	switch(s){
		default:
			$("#dialog_view_inventory_simcards").dialog("open");
		case 'save':
			var a = [];
			var simlistcount=document.getElementsByClassName("inventory_simlist_multiple");
		  	simlistcount=simlistcount.length;
		  	for(var i=1;i<=simlistcount;i++){
		  		mobnnum=document.getElementById('add_inventory_simcard_mobilenumber_'+i).value;
		  		if(mobnnum!=''){
			  		simnum=document.getElementById('add_inventory_simcard_cardnumber_'+i).value;
			  		simimsi=document.getElementById('add_inventory_simcard_cardimsi_'+i).value;
			  		simpro=document.getElementById('add_inventory_simcard_provider_'+i).value;
			  		if(mobnnum=='' || simnum=='' || simpro==''){
			  			notifyDialog(la['PLSFILLALLDETAILS']);
			  			return;
			  		}
			  		a.push({mobno:mobnnum,simno:simnum,simimsi:simimsi,simprovider:simpro});
			  	}
		  	}
		  	if(a.length<0){
		  		notifyDialog(la['NO_DATA']);
		  		return;
		  	}
		  	var data={
		  		cmd:'insert_simcard_list',
		  		value:a
		  	};
		  	$.ajax({
		  		type:"POST",
		  		url: "func/fn_cpanel.inventory.php",
				data: data,
				success: function(result)
				{
					if (result == 'Data Empty')
					{
						notifyDialog(la['NO_DATA']);
					}else if(result == 'OK'){
						notifyDialog(la['SIM_REGISTER_SUCCESSFULLY']);
						$(".inventory_simlist_multiple").remove();
						$("#cpanel_inventory_simcard_list").trigger("reloadGrid");
						$("#dialog_view_inventory_simcards").dialog("close");
					}else{
						notifyDialog('This number is already Register Contact Admin/Super Admin-'+result);
						$(".inventory_simlist_multiple").remove();
						$("#cpanel_inventory_simcard_list").trigger("reloadGrid");
						$("#dialog_view_inventory_simcards").dialog("close");						

					}
				}
		  	})

		break;
		case 'add':
			$('#addnewsimdetails').show();
			$('#updatesimdetails').hide();
			$(".inventory_simlist_multiple").remove();
			$("#inventory_add_simlist_button").click();
			$("#dialog_view_inventory_simcards").dialog("open");
		break;
		case 'update':
			var a = [];
			var usimlistcount=document.getElementsByClassName("inventory_simlist_multiple");
		  	usimlistcount=usimlistcount.length;
		  	for(var i=1;i<=usimlistcount;i++){
		  		simid=document.getElementById('add_inventory_simcard_selectsim_id_'+i).value;
		  		umobnnum=document.getElementById('add_inventory_simcard_mobilenumber_'+i).value;
		  		if(umobnnum!=''){
			  		usimnum=document.getElementById('add_inventory_simcard_cardnumber_'+i).value;
			  		usimimsi=document.getElementById('add_inventory_simcard_cardimsi_'+i).value;
			  		usimpro=document.getElementById('add_inventory_simcard_provider_'+i).value;
			  		if(umobnnum=='' || usimnum=='' || usimpro==''){
			  			notifyDialog(la['PLSFILLALLDETAILS']);
			  			return;
			  		}
			  		a.push({sid:simid,mobno:umobnnum,simno:usimnum,simimsi:usimimsi,simprovider:usimpro});
			  	}
		  	}
		  	data={
		  		cmd:"update_sim_details",
		  		simdata:a
		  	};
		  	$.ajax({
		  		type:"POST",
		  		url:"func/fn_cpanel.inventory.php",
		  		data:data,
		  		success:function(e){
		  			if(e=='OK'){
			  			notifyDialog(la['SIM_UPDATE_SUCCESSFULLY']);
						$(".inventory_simlist_multiple").remove();
						$("#cpanel_inventory_simcard_list").trigger("reloadGrid");
						$("#dialog_view_inventory_simcards").dialog("close");
					}else{
						notifyDialog(la['NO_PERMISSION']);
					}
		  		}
		  	})
		break;
		case 'loadprovider':
			var provider=document.getElementById('inventory_simcard_provider').value;
			$("#cpanel_inventory_simcard_list").setGridParam({url: "func/fn_cpanel.inventory.php?cmd=load_simcard_list&provider="+provider});
		    $("#cpanel_inventory_simcard_list").trigger("reloadGrid");
		break;
		case 'loadsimstatus':
			if ((cpValues['privileges'] == 'super_admin') || (cpValues['privileges'] == 'admin')){
				var simstatus=document.getElementById('inventory_find_simcard_status').value;
				var provider=document.getElementById('inventory_simcard_provider').value;
				$("#cpanel_inventory_simcard_list").setGridParam({url: "func/fn_cpanel.inventory.php?cmd=load_simcard_list&provider="+provider+"&simstatus="+simstatus});
			    $("#cpanel_inventory_simcard_list").trigger("reloadGrid");
			}else{
				notifyDialog(la['NO_PERMISSION']);
			}
		break;
		case 'loadsearchsim':
			var searchsim=document.getElementById('inventory_search_simcard_number').value;
			$("#cpanel_inventory_simcard_list").setGridParam({url: "func/fn_cpanel.inventory.php?cmd=load_simcard_list&searchsim="+searchsim});
		    $("#cpanel_inventory_simcard_list").trigger("reloadGrid");
			
		break;
		case 'download':
			var data={
		  		cmd:'download_sim_details'
		  	};
		  	$.ajax({
		  		type:"POST",
		  		url: "func/fn_cpanel.inventory.php",
				data: data,
				success: function(result)
				{
					$.generateFile({
		                filename: 'Sim Details',
		                content: result,
		                script: "func/fn_saveas.php?format=xls"
		            })			
				}
		  	})
		break;

			
	}
}

function editsimcarddetails(id){
	var data={
		cmd:'edit_simcard_list',
		id:id
	};
	$.ajax({
		type:"POST",
		url:"func/fn_cpanel.inventory.php",
		data:data,
		success:function(e){
			if(e!='NO'){
				$(".inventory_simlist_multiple").remove();
				var edcounter=document.getElementsByClassName("inventory_simlist_multiple");
				edcounter=edcounter.length;
				if(edcounter==0){
					$("#inventory_add_simlist_button").click();
				}
				$('#addnewsimdetails').hide();
				$('#updatesimdetails').show();
				$("#dialog_view_inventory_simcards").dialog("open");
				document.getElementById('add_inventory_simcard_mobilenumber_1').value=e.mob_number;
				document.getElementById('add_inventory_simcard_selectsim_id_1').value=e.id;
				document.getElementById('add_inventory_simcard_cardnumber_1').value=e.sim_number;
				document.getElementById('add_inventory_simcard_cardimsi_1').value=e.sim_imsi;
				document.getElementById('add_inventory_simcard_provider_1').value=e.sim_provider;
			}	
		}
	})
}

function editsimcarddetailsSelected(){
	var $grid = $("#cpanel_inventory_simcard_list"), selIds = $grid.jqGrid("getGridParam", "selarrrow");
    if("" == selIds)
    {
      notifyBox("error", la.ERROR, la.NO_ITEMS_SELECTED) ;
      return;
    }
    $(".inventory_simlist_multiple").remove();
    var custom = $grid.jqGrid('getRowData'); 
    for (i = 0; i < selIds.length; i++) 
    {
     	$("#inventory_add_simlist_button").click();
     	var tid=i+1;
     	mobilenumber = $grid.jqGrid ('getCell', selIds[i], 'mobilenumber');
     	sid = $grid.jqGrid ('getCell', selIds[i], 'sid');
     	simnumber = $grid.jqGrid ('getCell', selIds[i], 'simnumber');
     	simimsi = $grid.jqGrid ('getCell', selIds[i], 'simimsi');
     	simprovider = $grid.jqGrid ('getCell', selIds[i], 'simprovider');
        document.getElementById('add_inventory_simcard_mobilenumber_'+tid).value=mobilenumber;
		document.getElementById('add_inventory_simcard_selectsim_id_'+tid).value=sid;
		document.getElementById('add_inventory_simcard_cardnumber_'+tid).value=simnumber;
		document.getElementById('add_inventory_simcard_cardimsi_'+tid).value=simimsi;
		document.getElementById('add_inventory_simcard_provider_'+tid).value=simprovider;
    }
    $('#addnewsimdetails').hide();
	$('#updatesimdetails').show();
    $("#dialog_view_inventory_simcards").dialog("open");
}

function loadinventorydata(){
	var data={
		cmd:'load_sim_usage'
	};
	$.ajax({
		type:"POST",
		url:"func/fn_cpanel.inventory.php",
		data:data,
		success:function(s){
			if(s!=''){
				document.getElementById('simcard_list_usage_status').innerHTML = '(' + s['usedsim']+'/'+s['totalsim'] + ')';
			}else{
				notifyDialog(la['NO_DATA']);
			}
		}
	});
}

$(document).ready(function(){		
    $("#inventory_add_simlist_button").click(function () {
		var counter=document.getElementsByClassName("inventory_simlist_multiple");
		counter=counter.length;
		counter+=1;
		if(counter>10){
	            notifyDialog('Only 10 textboxes allow');
	            return false;
		}			
		var newTextBoxDiv = $(document.createElement('div'))
		     .attr("class",'inventory_simlist_multiple');
	                
		newTextBoxDiv.after().html('<div class="row"><div class="row2"><div class="width25"><input id="add_inventory_simcard_mobilenumber_'+counter+'" class="inputbox width100"><input id="add_inventory_simcard_selectsim_id_'+counter+'" class="inputbox width100" hidden=""></div><div class="width25"><input id="add_inventory_simcard_cardnumber_'+counter+'" class="inputbox width100" ></div><div class="width25"><input id="add_inventory_simcard_cardimsi_'+counter+'" class="inputbox width100" ></div><div class="width25"><select id="add_inventory_simcard_provider_'+counter+'"><option value="">Select</option><option value="airtel">Airtel</option><option value="vodafone">Vodafone</option><option value="jio">Jio</option></select></div></div></div>');
	            
		newTextBoxDiv.appendTo("#inventory_simlist_div");
		counter++;
    });
});