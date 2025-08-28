<div id="cpanel_inventory" style="display:none;">
    <div class="float-left cpanel-title">
        <div class="version">v
            <? echo $gsValues['VERSION']; ?>
        </div>
        <h1 class="title">
                <? echo $la['CONTROL_PANEL']; ?> <span> -
                <? echo $la['INVENTORY_MANAGEMENT']; ?></span></h1>
    </div>
    <div id="manage_server_tabs" class="clearfix">
        <ul>
            <li class="cp-server"><a href="#inventory_simcard_list">Sim Details</a></li>
            <li class="cp-maps"><a href="#inventory_supplier_list"><? echo $la['SUPPLIER_LIST']; ?></a></li>
            <li class="cp-server"><a href="#inventory">Inventory</a></li>
        </ul>
        <div id="inventory_simcard_list">
            <div class="width-100">
                <div class="row3" >
                    <div class="width10">
                        <span id="simcard_list_usage_status" style="font-size: x-large;"></span>
                    </div>
                    <div class="width8">
                        <input type='button' class="btn btn-warning" value='Download' onclick="inventorySimdetails('download');">
                    </div>
                    <div class="width18">
                        <? echo $la['SEARCH'];?>: 
                        <input type="text" placeholder="Search" id="inventory_search_simcard_number" onkeyup="inventorySimdetails('loadsearchsim')">
                    </div>
                    <div class="width15">
                        <? echo $la['SIM_PROVIDER'];?>: 
                        <select id="inventory_simcard_provider" onchange="inventorySimdetails('loadprovider');">
                            <option value="">Select</option>
                            <option value="airtel">Airtel</option>
                            <option value="vodafone">Vodafone</option>
                            <option value="jio">Jio</option>
                        </select>
                    </div>                    
                    <?if ($_SESSION["cpanel_privileges"] == 'admin' || $_SESSION["cpanel_privileges"] == 'super_admin') { ?>
                    <div class="width12">
                        <? echo $la['STATUS'];?>: 
                        <select id="inventory_find_simcard_status" onchange="inventorySimdetails('loadsimstatus');">
                            <option value="">Select</option>
                            <option value="A">Active</option>
                            <option value="R">Removed</option>
                            <option value="S">Suspend </option>
                            <option value="DI">Deactivate </option>
                            <?if ($_SESSION["cpanel_privileges"] == 'super_admin') {?>)
                            <option value="D">Delete</option>
                            <?}?>
                        </select>
                    </div>
                    <?}?>                     
                </div>
                <table id="cpanel_inventory_simcard_list"></table>
                <div id="cpanel_inventory_simcard_list_pager"></div>
            </div>
        </div>
	    <div id="inventory_supplier_list">
            <div class="width-100">
				<table id="cpanel_inventory_supplier_list"></table>
				<div id="cpanel_inventory_supplier_list_pager"></div>
	        </div>
	    </div>
	</div>
</div>

<div id="dialog_view_supplier" title="<? echo $la['SUPPLIER_DETAILS']; ?>">
        <div class="row">
        	<div class="block width45">
	        	<div class="title-block">
	        		<? echo $la['SUPPLIER_DETAILS']; ?>
	            </div>
	            <div class="row3" >
	                <div class="width40"><? echo $la['SUPPLIER_ID']; ?></div>
	                <div class="width60" id="view_supplier_id"></div>
	            </div>
	            <div class="row3">
	                <div class="width40"><? echo $la['SUPPLIER_NAME']; ?></div>
	                <div class="width60" id="view_supplier_name"></div>
	            </div>
	            <div class="row3">
	                <div class="width40"><? echo $la['CATEGORY']; ?></div>
	                <div class="width60" id="view_supplier_category"></div>
	            </div>
	            <div class="row3">
	                <div class="width40"><? echo $la['GST_NO']; ?></div>
	                <div class="width60" id="view_supplier_gstno"></div>
	            </div>
	            <div class="row3">
	                <div class="width40"><? echo $la['EMAILID']; ?></div>
	                <div class="width60" id="view_supplier_email"></div>
	            </div>
	            <div class="row3">
	                <div class="width40"><? echo $la['PHONE']; ?></div>
	                <div class="width60" id="view_supplier_phone"></div>
	            </div>
	            <div class="row3">
	                <div class="width40"><? echo $la['COMMERCIAL_ADDRESS']; ?></div>
	                <div class="width60" id="view_supplier_address"></div>
	            </div>
	            <div class="row3">
	                <div class="width40"><? echo $la['DOCUMENT']; ?></div>
	                <div class="width60"><a href="" id="cpanel_hrefv_view_document" target="_blank" download>
                    <input class="button" type="button" id="" value="Download"></a></div>
	            </div>
	        </div>
	        <div class="block width10">&nbsp;</div>
	        <div class="block width45">
	        	<div class="row">
		        	<div class="title-block">
	                	<? echo $la['BANK_DETAILS']; ?>
	                </div>
		        	<div class="row3">
		                <div class="width40"><? echo $la['BANK_NAME']; ?></div>
		                <div class="width60" id="view_supplier_bankname"></div>
		            </div>
		            <div class="row3">
		                <div class="width40"><? echo $la['BRANCH_NAME']; ?></div>
		                <div class="width60" id="view_supplier_branchname"></div>
		            </div>
		            <div class="row3">
		                <div class="width40"><? echo $la['ACCOUNT_NO']; ?></div>
		                <div class="width60" id="view_supplier_accno"></div>
		            </div>
		             <div class="row3">
		                <div class="width40"><? echo $la['IFSC_CODE']; ?></div>
		                <div class="width60" id="view_supplier_banifsc"></div>
		            </div>
		        </div>
		        <div class="row">
                    <div class="title-block">
                		<? echo $la['ADDITIONAL_CONTACT']; ?>
                    </div>
                    <div class="row3">
                        <div class="width50">
                            <? echo $la['NAME']; ?>
                        </div>
                        <div class="width50" id="view_supplier_add_name"></div>
                    </div>
                    <div class="row3">
                        <div class="width50">
                            <? echo $la['MOBILENO']; ?>
                        </div>
                        <div class="width50" id="view_supplier_add_mobile"></div>
                    </div>
                    <div class="row3">
                        <div class="width50">
                            <? echo $la['EMAILID']; ?>
                        </div>
                        <div class="width50" id="view_supplier_add_email"></div>
                    </div>
                    <div class="row3">
                        <div class="width50">
                            <? echo $la['DESIGNATION']; ?>
                        </div>
                        <div class="width50" id="view_supplier_add_designation"></div>
                    </div>
                    <div class="row3">
                        <div class="width50">
                            <? echo $la['DESCRIPTION']; ?>
                        </div>
                        <div class="width50" id="view_supplier_add_description"></div>
                    </div>
                </div>
	        </div>
        </div>
</div>
<div id="dialog_edit_supplier" title="<? echo $la['SUPPLIER_DETAILS']; ?>">
        <div class="row">
        	<div class="block width100">
        		<div class="block width45">
                    <div class="row">
                        <div class="title-block">
                    		<? echo $la['SUPPLIER_DETAILS']; ?>
                        </div>
                        <div class="row2" id="supplieridrow">
                            <div class="width50">
                                <? echo $la['SUPPLIER_ID']; ?>
                            </div>
                            <div class="width50">
                                <input id="cpanel_edit_supplier_id" class="inputbox width100" disabled="">
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['SUPPLIER_NAME']; ?><b style="color: red;">*</b>
                            </div>
                            <div class="width50">
                                <input id="cpanel_supplier_name" class="inputbox width100" >
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['CATEGORY']; ?><b style="color: red;">*</b>
                            </div>
                            <div class="width50">
                                <select id="cpanel_supplier_category" class="inputbox width100" >
                                	<option value="">Select</option>
                                	<option value="International">International</option>
                                	<option value="Domastic">Domastic</option>
                                </select>
                            </div>
                        </div>
                         <div class="row2">
                            <div class="width50">
                                <? echo $la['GST_NO']; ?><b style="color: red;">*</b>
                            </div>
                            <div class="width50">
                                <input id="cpanel_supplier_gstno" class="inputbox width100" >
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['COMMERCIAL_ADDRESS']; ?><b style="color: red;">*</b>
                            </div>
                            <div class="width50">
                                <textarea id="cpanel_supplier_com_adderss" class="inputbox width100" style="height: 75px;"></textarea>
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['EMAILID']; ?><b style="color: red;">*</b>
                            </div>
                            <div class="width50">
                                <input id="cpanel_supplier_com_mailid" class="inputbox width100" >
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['PHONE']; ?><b style="color: red;">*</b>
                            </div>
                            <div class="width50">
                                <input id="cpanel_supplier_com_phone" class="inputbox width100" >
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['DOCUMENT']; ?>
                            </div>
                            <div class="width50">
                                <!-- <center><img class="logo" id="cpanel_edit_supplier_doc" src="<? echo $gsValues['URL_LOGO']; ?>" /></center> -->
                                <input id="cpanel_supplier_documentfile" class="inputbox"  disabled="" />
                                <div class="width40">
	                                 <input style="width: 70px;" class="button" type="button" value="<? echo $la['UPLOAD']; ?>" onclick="upSupplierDoc();" />
	                             </div><div class="width40">
	                             <a href="" id="cpanel_href_view_document" target="_blank" download>
                                <input class="button" type="button" id="cpanel_edit_view_document" value="Download"></a></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="block width10">&nbsp;</div>
                <div class="block width45">
                	<div class="row">
                    	<div class="title-block">
                        	<? echo $la['BANK_DETAILS']; ?>
	                    </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['BANK_NAME']; ?>
                            </div>
                            <div class="width50">
                                <input id="cpanel_supplier_bank_name" class="inputbox width100" >
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['BRANCH_NAME']; ?>
                            </div>
                            <div class="width50">
                                <input id="cpanel_supplier_branch_name" class="inputbox width100" >
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['ACCOUNT_NO']; ?>
                            </div>
                            <div class="width50">
                                <input id="cpanel_supplier_account_number" class="inputbox width100" >
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['IFSC_CODE']; ?>
                            </div>
                            <div class="width50">
                                <input id="cpanel_supplier_ifsc_code" class="inputbox width100" >
                            </div>
                        </div>
                    </div>
                	<div class="row">
                        <div class="title-block">
                    		<? echo $la['ADDITIONAL_CONTACT']; ?>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['NAME']; ?>
                            </div>
                            <div class="width50">
                                <input id="cpanel_supplier_additional_name" class="inputbox width100" >
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['MOBILENO']; ?>
                            </div>
                            <div class="width50">
                                <input id="cpanel_supplier_additional_phone" class="inputbox width100" >
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['EMAILID']; ?>
                            </div>
                            <div class="width50">
                                <input id="cpanel_supplier_additional_email" class="inputbox width100" >
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['DESIGNATION']; ?>
                            </div>
                            <div class="width50">
                                <input id="cpanel_supplier_additional_designation" class="inputbox width100" >
                            </div>
                        </div>
                        <div class="row2">
                            <div class="width50">
                                <? echo $la['DESCRIPTION']; ?>
                            </div>
                            <div class="width50">
                                <input id="cpanel_supplier_additional_description" class="inputbox width100" >
                            </div>
                        </div>
                    </div>
                </div>
                <div class="block width100">
                    <div class="row2">
                        <center>
                            <input class="button" type="button" id="updateSupplier" onclick="adddNewSupplier('update');" value="update">
                            <input class="button" type="button" id="addnewSupplier" onclick="adddNewSupplier('save');" value="Save">
                            <!-- <input class="button" type="button" onclick="adddNewSupplier('clear');" value="Clear"> -->
                        </center>
                    </div>
                </div>
            </div>
        </div>
</div>
<div id="dialog_view_inventory_simcards" title="<? echo $la['SIM_ENTRY']; ?>">
    <div class="row">
        <input type='button' class="btn btn-warning" value='Add +' id='inventory_add_simlist_button'>
        <div class="block width100">
            <div class="block width100">
                <div class="row">
                    <div class="row2">
                        <div class="width25">
                            <? echo $la['MOBILENO']; ?><b style="color: red;">*</b>
                        </div>
                        <div class="width25">
                            <? echo $la['SIM_CARD_NUMBER']; ?><b style="color: red;">*</b>
                        </div>
                        <div class="width25">
                            <? echo $la['SIM_CARD_IMSI']; ?>
                        </div>
                        <div class="width25">
                            <? echo $la['SIM_PROVIDER']; ?><b style="color: red;">*</b>
                        </div>
                    </div>
                </div>
                <div id='inventory_simlist_div'>
                    <div class="inventory_simlist_multiple">
                        <div class="row">
                            <div class="row2">
                                <div class="width25">
                                    <input id="add_inventory_simcard_mobilenumber_1" class="inputbox width100">
                                    <input id="add_inventory_simcard_selectsim_id_1" class="inputbox width100" hidden="">
                                </div>
                                <div class="width25">
                                    <input id="add_inventory_simcard_cardnumber_1" class="inputbox width100" >
                                </div>
                                <div class="width25">
                                    <input id="add_inventory_simcard_cardimsi_1" class="inputbox width100" >
                                </div>
                                <div class="width25">
                                    <select id="add_inventory_simcard_provider_1">
                                        <option value="">Select</option>
                                        <option value="airtel">Airtel</option>
                                        <option value="vodafone">Vodafone</option>
                                        <option value="jio">Jio</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block width100">
                <div class="row2">
                    <center>
                        <input class="button" type="button" id="updatesimdetails" onclick="inventorySimdetails('update');" value="update" hidden="">
                        <input class="button" type="button" id="addnewsimdetails" onclick="inventorySimdetails('save');" value="Save" hidden="">
                        <!-- <input class="button" type="button" onclick="adddNewSupplier('clear');" value="Clear"> -->
                    </center>
                </div>
            </div>
        </div>
    </div>
</div>