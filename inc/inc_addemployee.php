<div id="dialog_add_employee_fullview" class="opensettings" style="display: none;    padding-top: 55px;" title="<? echo $la['ADD_EMPLOYEE'];?>">
	<div id="settings_main" class="fullview_mobvi sosmobg">
		<?php if($_SESSION["privileges"] == 'Company'){?>
		<div>
	        <div class="block fullviewheight width100">
	            <div class="block width10">&nbsp;</div>
	            <div class="block width17">
	            <ul id="cmdmenu" class="changecmdmenu">
	                <li id="stisviewcompany"><a href="#add_employee_tab" class="hvr-sweep-to-bottom">
	                        <? echo $la['ADD_EMPLOYEE'];?></a></li>
	                <li id="stisviewcompany"><a href="#stis_depertment_tab" class="hvr-sweep-to-bottom1">
	                        <? echo $la['DEPARTMENT'];?></a></li>
	                <li id="stisviewcompany"><a href="#sits_shift_tab" class="hvr-sweep-to-bottom2">
	                        <? echo $la['SHIFT'];?></a></li>
	                <li id="stisviewcompany"><a href="#sits_cab_request" class="hvr-sweep-to-bottom3">
	                        <? echo $la['CAB_REQUEST'];?></a></li>
	            </ul>
		        </div>
		        <div class="block width3">
		            <div class="vl"></div>
		        </div>
		        <div class="block width70 overflow_mobvi">
		        	<div id="add_employee_tab">
			    		<div id="settings_main_addemployee">
			                <div id="settings_main_addemployee_list">
			                    <table id="stis_main_addemployee_list_grid"></table>
			                    <div id="stis_main_addemployee_list_pager"></div>
			                </div>
			            </div>
			        </div>
			        <div id="stis_depertment_tab">
			        	<div class='block width100'>
			        		<div class="block width50">
				                <div id="stis_main_department_list">
				                    <table id="stis_main_department_list_grid"></table>
				                    <div id="stis_main_department_list_pager"></div>
				                </div>
				            </div>
				            <div class="block width5">&nbsp;</div>
				            <div class="block width45">
				            	<div class="container">
				            		<div class="title-block">
			                            <? echo $la['DEPARTMENT']; ?>
			                        </div>
					                <div class="row">
					                    <div class="width50">
					                        <? echo $la['DEPARTMENTNAME']; ?><b style="color: red;">*</b>
					                    </div>
					                    <div class="row">
						                    <div class="width70">
						                    	<input id="addsist_departmentname" class="inputbox" type="text" value="">
						                    	<input id="addsist_departmentid" class="inputbox" type="text" value="" hidden="" disabled="">
						                    </div>
						                </div>
					                </div>
					                <center>
					                	<input type="submit" id="save_sist_department" onclick="addStisDepartment('save')" value="Add">
					                	<input type="submit" id="update_sist_department" onclick="addStisDepartment('update')" style="display: none;" value="Update">
					                </center>
					            </div>
				            </div>
			            </div>
			        </div>
			        <div id="sits_shift_tab">
		                <div id="stis_main_shift_list">
		                    <table id="stis_main_shift_list_grid"></table>
		                    <div id="stis_main_shift_list_pager"></div>
		                </div>
			        </div>
			        <div id="sits_cab_request">
		                <div id="stis_main_cabrequest_list">
		                    <table id="stis_main_cabrequest_list_grid"></table>
		                    <div id="stis_main_cabrequest_list_pager"></div>
		                </div>
			        </div>
		        </div>
	       	</div>
	        <div id="dialog_add_new_employee" title="Add Employee">
	             <div class="container">
	                <div class="row">
			            <div class="container">
			                <div class="row2">
			                    <div class="width20">
			                        <? echo $la['EMPLOYEEID']; ?> <b style="color: red;">*</b>
			                    </div>
			                    <div class="width80">
			                    	<input id="aaddnewemployee_eid" class="inputbox" type="text" value="">
			                    	<input id="aaddnewemployee_liveid" class="inputbox" type="text" value="" hidden="" disabled="">
			                    </div>
			                </div>
			                <div class="row2">
			                    <div class="width20">
			                        <? echo $la['USERNAME']; ?><b style="color: red;">*</b>
			                    </div>
			                    <div class="width80"><input id="addnewemployee_username" class="inputbox" type="text" value=""></div>
			                </div>
			                <div class="row2">
			                    <div class="width20">
			                        <? echo $la['MOBILENO']; ?><b style="color: red;">*</b>
			                    </div>
			                    <div class="width80"><input id="addnewemployee_mobile" class="inputbox"></div>
			                </div>
			                <div class="row2">
			                    <div class="width20">
			                        <? echo $la['DOB']; ?>
			                    </div>
			                    <div class="width80"><input id="addnewemployee_dob" type="text" class="inputbox-calendar inputbox width100 textboxextra_style"></div>
			                </div>
			                <div class="row2">
			                    <div class="width20">
			                        <? echo $la['GENDER']; ?>
			                    </div>
			                    <div class="width80"><select id="addnewemployee_gender" class="inputbox">
			                    	<option value="">Select</option>
			                    	<option value="Male">Male</option>
			                    	<option value="Female">Female</option>
			                    </select>
			                    </div>
			                </div>
			                <div class="row2">
			                    <div class="width20">
			                        <? echo $la['DEPARTMENT']; ?><b style="color: red;">*</b>
			                    </div>
			                    <div class="width80"><select id="addnewemployee_department" class="inputbox"></select>	
			                    </div>
				            </div>
			                <div class="row2">
			                    <div class="width20">
			                        <? echo $la['SHIFT']; ?><b style="color: red;">*</b>
			                    </div>
			                    <div class="width80"><select id="addnewemployee_shift" class="inputbox"></select>	
			                    </div>
			                </div>
			                <div class="row2">
			                    <div class="width20">
			                        <? echo $la['ADDRESS']; ?>
			                    </div>
			                    <div class="width80"><textarea id="addnewemployee_address" class="inputbox" style="height:100px;"></textarea></div>
			                </div>
			                <div class="row2">
			                	<div class="width50">
				                    <div class="width40">
				                        <? echo $la['STATUS']; ?><b style="color: red;">*</b>
				                    </div>
				                    <div class="width50"><select id="addnewemployee_status" class="inputbox">
				                    	<option value=''>Selcet</option>
				                    	<option value='A'>Active</option>
				                    	<option value='D'>Deactive</option></select>
				                    </div>
				                </div>
				                <div class="width50">
				                	<div class="width40">
				                        <? echo $la['USER_TYPE']; ?><b style="color: red;">*</b>
				                    </div>
				                    <div class="width50"><select id="addnewemployee_usertype" class="inputbox">
				                    	<option value="">Select</option>
				                    	<option value="U">User</option>
				                    	<option value="S">Security</option>
				                    </select>	
				                    </div>
				                </div>
			                </div>
			                <center>
			                	<input type="submit" id="save_add_Employee" onclick="addNewEmployee('save')" value="Save">
			                	<input type="submit" id="update_add_employee" onclick="addNewEmployee('update')" style="display: none;" value="Update">
			                </center>
			            </div>
	                </div>
	            </div>
	        </div>	        
	        <div id="dialog_stis_add_shift" title="Shift Details">
	             <div class="container">
	                <div class="row">
			            <div class="container">
			                <div class="row2">
			                    <div class="width30">
			                        <? echo $la['SHIFT_NAME']; ?> <b style="color: red;">*</b>
			                    </div>
			                    <div class="width70">
			                    	<input id="stis_shift_name" class="inputbox" type="text" value="">
			                    	<input id="stis_shift_id" class="inputbox" type="text" value="" hidden="" disabled="">
			                    </div>
			                </div>
			                <div class="row2">
			                    <div class="width30">
			                        <? echo $la['FROM']; ?><b style="color: red;">*</b>
			                    </div>
			                    <div class="width70"><input id="stis_shift_from" class="inputbox" type="text" value=""></div>
			                </div>
			                <div class="row2">
			                    <div class="width30">
			                        <? echo $la['TO']; ?><b style="color: red;">*</b>
			                    </div>
			                    <div class="width70"><input id="stis_shift_to" class="inputbox"></div>
			                </div>
			                <div class="row2">
			                    <div class="width30">
			                        <? echo $la['STATUS']; ?><b style="color: red;">*</b>
			                    </div>
			                    <div class="width70"><select id="stis_shift_status" class="inputbox">
			                    	<option value=''></option>
			                    	<option value='A'>Active</option>
			                    	<option value='D'>Deactive</option></select>
			                    </div>
			                </div>
			                <center>
			                	<input type="submit" id="save_stis_shift" onclick="addStisShift('save')" value="Save">
			                	<input type="submit" id="update_stis_shift" onclick="addStisShift('update')" style="display: none;" value="Update">
			                </center>
			            </div>
	                </div>
	            </div>
	        </div>
	        <div id="dialog_stis_newcab_request" title="Cab Request" style="overflow:scroll;">
	             <div class="container">
	                <div class="row">
			            <div class="container">
			                <div class="row2">
			                    <div class="width20">
			                        <? echo $la['CLIENT_ID']; ?> <b style="color: red;">*</b>
			                    </div>
			                    <div class="width80">
			                    	<div class="width30">
			                    		<input id="cabrequest_clientid_search" placeholder="Search" class="inputbox" type="text" value="">
			                    	</div>
			                    	<div class="width65">
			                    		<select style="width: 106% !important;" id="cabrequest_clientid" class="inputbox" type="text" value=""></select>
			                    	</div>
			                    	<input id="cabrequest_tripid" class="inputbox" type="text" value="" hidden="" disabled="">
			                    </div>
			                </div>
			                <div class="row2" style="display: none;margin-top: 7px !important;">
			                    <div class="width20">
			                        <? echo $la['CAB_TYPE']; ?><b style="color: red;">*</b>
			                    </div>
			                    <div class="width80"><input id="cabrequest_cabtype" class="inputbox" type="text" value="0"></div>
			                </div>
			                <div class="row2" style="margin-top: 7px !important;">
			                    <div class="width20">
			                        <? echo $la['SCHEDULED_AT']; ?><b style="color: red;">*</b>
			                    </div>
			                    <div class="width80">
			                    	<div class="width50">
			                    		<input readonly class="inputbox-calendar inputbox width100 textboxextra_style" id="cabrequest_schedule_date" type="text" value="" />
			                    	</div>
			                    	<div class="width25">
			                    		<select class="width100 textboxextra_style" id="cabrequest_schedule_hours">
	                                    <? include ("inc/inc_dt.hours.php"); ?>
	                                </select>
			                    	</div>
			                    	<div class="width20">
			                    		<select style="width: 115%;" class="width100 textboxextra_style" id="cabrequest_schedule_sce">
	                                    <? include ("inc/inc_dt.minutes.php"); ?>
	                                </select>
			                    	</div>
			                    </div>
			                </div>
			                <div class="row2" style="margin-top: 7px !important;">
			                    <div class="width20">
			                        <? echo $la['FROM']; ?><b style="color: red;">*</b>
			                    </div>
			                    <div class="width80"><input id="cabrequest_from" type="text" class="inputbox"></div>
			                </div>
			                <div class="row2" style="margin-top: 7px !important;">
			                    <div class="width20">
			                        <? echo $la['TO']; ?><b style="color: red;">*</b>
			                    </div>
			                    <div class="width80"><input id="cabrequest_to" type="text" class="inputbox"></div>
			                </div>
			                <div class="row2" style="margin-top: 7px !important;">
			                    <div class="width20">
			                        <? echo $la['SECURITY']; ?><b style="color: red;">*</b>
			                    </div>
			                    <div class="width80">
			                    	<select id="cabrequest_security" type="text" class="inputbox">
			                    		<option value=""></option>
			                    		<option value="Y">Yes</option>
			                    		<option value="N">No</option>
			                    	</select>
			                    </div>
				            </div>
			                <div class="row2" style="display: none;margin-top: 7px !important;">
			                    <div class="width20">
			                        <? echo $la['TOTAl_USER']; ?><b style="color: red;">*</b>
			                    </div>
			                    <div class="width80"><input id="cabrequest_totaluser" type="text" class="inputbox"></div>
			                </div>
			                <div class="row2" style="margin-top: 7px !important;">
			                    <div class="width20">
			                        <? echo $la['STATUS']; ?><b style="color: red;">*</b>
			                    </div>
			                    <div class="width80"><select id="cabrequest_status" class="inputbox">
			                    	<option value=''></option>
			                    	<option value='A'>Active</option>
			                    	<option value='D'>Deactive</option></select>
			                    </div>
			                </div>
			                <div class="row2" style="margin-top: 7px !important;">
			                    <div class="width20">
			                        <? echo $la['TRIP_TYPE']; ?><b style="color: red;">*</b>
			                    </div>
			                    <div class="width80">
			                    	<select id="cabrequest_triptype" class="inputbox">
			                    		<option value=""></option>
			                    		<option value="P">Pick Up</option>
			                    		<option value="D">Drop</option>
			                    	</select>
			                    </div>
			                </div>
			                <div class="row2" style="margin-top: 7px !important;">
			                    <div class="width20">
			                        <? echo $la['USER']; ?> <b style="color: red;">*</b>
			                    </div>
			                    <div class="width80">
			                    	<div class="width20">
			                    		<input id="cabrequest_userid_search" placeholder="Search" class="inputbox" type="text" value="">
			                    	</div>
			                    	<div class="width60">
			                    		<select id="cabrequest_userid" class="inputbox" type="text" value=""></select>
			                    	</div>
			                    	<div class="width15">
			                    		<input type="submit" style="width: 119% !important;" onclick="loadJqgriduser()" class="inputbox"  value="Add">
			                    	</div>
			                    </div>
			                </div>
			                <div class="row2" style="margin-top: 7px !important;">
			                    <div class="width100">
			                    	<table id="stis_main_cabrequest_adduser_list_grid"></table>
		                    		<div id="stis_main_cabrequest_adduser_list_pager"></div>
			                    </div>
			                </div>
			                <div class="row2" style="padding-top: 13px;margin-top: 7px !important;">
				                <center>
				                	<input type="submit" id="save_add_cabrequest" onclick="addCabRequest('save')" value="Save">
				                	<input type="submit" id="update_add_cabrequest" onclick="addCabRequest('update')" style="display: none;" value="Update">
				                </center>
				            </div>
			            </div>
	                </div>
	            </div>
	        </div>
	        <div id="dialog_stis_newcab_viewuser_request" title="View User List" style="overflow:scroll;">
	             <div class="container">
	             	<div id="stis_main_cabrequest_list">
	                    <table id="stis_main_cabrequest_viewuser_list_grid"></table>
	                    <div id="stis_main_cabrequest_viewuser_list_pager"></div>
	                </div>
	            </div>
	        </div>
	    </div>
		<?php }else{?>
	    <div>
	    	<div class="block fullviewheight width100">
	            <div class="block width10">&nbsp;</div>
	            <div class="block width17">
	            <ul id="cmdmenu" class="changecmdmenu">
	                <li id="stisviewcompany"><a href="#sits_cab_request_vendor" class="hvr-sweep-to-bottom3">
	                        <? echo $la['CAB_REQUEST'];?></a></li>
	                <li id="stisviewcompany"><a href="#sits_self_cab_request" class="hvr-sweep-to-bottom3">
	                        <? echo $la['USER_CAB_REQUEST'];?></a></li>
	            </ul>
		        </div>
		        <div class="block width3">
		            <div class="vl"></div>
		        </div>
		        <div class="block width70 overflow_mobvi">
			        <div id="sits_cab_request_vendor">
		                <div id="stis_main_cabrequest_vendor_list">
		                    <table id="stis_main_cabrequest_vendor_list_grid"></table>
		                    <div id="stis_main_cabrequest_vendor_list_pager"></div>
		                </div>
			        </div>
			        <div id="sits_self_cab_request">
			        	<input type="button" value="Waiting List" id="self_grid_status_W" onclick="changeSelfcabBookingGrid('W');" style="border-radius: 7px;background: #105db3bd;color: white;">
			        	<input type="button" value="Accept List" id="self_grid_status_A" onclick="changeSelfcabBookingGrid('A');" style="border-radius: 7px;background: #0da02bc4;color: white;">
			        	<input type="button" value="Start List" id="self_grid_status_A" onclick="changeSelfcabBookingGrid('S');" style="border-radius: 7px;background: #c334fa;color: white;">
			        	<input type="button" value="Pickup List" id="self_grid_status_P" onclick="changeSelfcabBookingGrid('P');" style="border-radius: 7px;background: #bf9507a3;color: white;">
			        	<input type="button" value="Complete List" id="self_grid_status_F" onclick="changeSelfcabBookingGrid('F');" style="border-radius: 7px;background: #bd2d7ba8;color: white;">
			        	<!-- <input type="button" value="Cancel List" id="self_grid_status_C" onclick="changeSelfcabBookingGrid('C');" style="border-radius: 7px;background: #ca1037a6;color: white;">			        	 -->
			        	<input type="button" value="All List" id="self_grid_status_all" onclick="changeSelfcabBookingGrid('');" style="border-radius: 7px;background: #4d524ecf;color: white;">
		                <div id="stis_main_self_cabrequest_vendor_list">
		                    <table id="stis_main_self_cabrequest_vendor_list_grid"></table>
		                    <div id="stis_main_self_cabrequest_vendor_list_pager"></div>
		                </div>
			        </div>
		        </div>
		        <div id="dialog_stis_cabrequest_setdriver" title="Allocate Driver" style="overflow:scroll;">
		            <div class="row">
				    	<div class="container">
				    		<div class="row2">
				    			<div class="width30">&nbsp;</div>
			                    <div class="width10">
			                        <!-- <input type="button" style="background-color: #60a500; color: #fbfbfb;" onclick="saveVendorAllocateDriver('accept');" class="inputbox"  value="Accept"> -->
			                        <button style="background-color: #60a500; color: #fbfbfb;" onclick="saveVendorAllocateDriver('accept');">Accept</button>
			                    </div>
			                    <div class="width2">&nbsp;</div>
			                    <div class="width10">
			                        <button style="background-color: #d81429; color: #fbfbfb;" onclick="saveVendorAllocateDriver('accept');">Cancel</button>
			                    </div>
			                </div>
					    	<div class="row2" style="margin-top: 7px !important;">
			                    <div class="width20">
			                        <? echo $la['DRIVER']; ?> <b style="color: red;">*</b>
			                    </div>
			                    <div class="width80">
			                    	<div class="width20">
			                    		<input id="cabrequest_setdriver_userid_search" placeholder="Search" class="inputbox" type="text" value="">
			                    		<input id="cabrequest_setdriver_tripid" placeholder="Search" class="inputbox" type="text" value="" disabled="" hidden="">
			                    	</div>
			                    	<div class="width60">
			                    		<select id="cabrequest_setdriver_userid" class="inputbox" type="text" value=""></select>
			                    	</div>
			                    	<div class="width15">
			                    		<input type="submit" style="width: 119% !important;" onclick="addTripDeriver();" class="inputbox"  value="Add">
			                    	</div>
			                    </div>
			                </div>
			                <div class="row2" style="margin-top: 7px !important;">
				             	<div id="dialog_stis_cabrequest_setdriver_list">
				                    <table id="dialog_stis_cabrequest_setdriver_list_grid"></table>
				                    <div id="dialog_stis_cabrequest_setdriver_list_pager"></div>
				                </div>
				            </div>
				        </div>
		            </div>
		        </div>
		        <div id="dialog_stis_newcab_viewuser_request" title="Trip Details" style="overflow:scroll;">
		        	<div class="row">
			    		<div class="row2">
		                    <div class="width45">
				            	<div class="title-block">
                					User Details 
                				</div>
				             	<div id="stis_main_cabrequest_list">
				                    <table id="stis_main_cabrequest_viewuser_list_grid"></table>
				                    <div id="stis_main_cabrequest_viewuser_list_pager"></div>
				                </div>
					        </div>
					        <div class="width5">&nbsp;</div>
					        <div class="width50">
				            	<div class="title-block">
                					Driver Details 
                				</div>
				             	<div id="stis_main_cabrequest_list">
				                    <table id="stis_main_cabrequest_viewdriver_list_grid"></table>
				                    <div id="stis_main_cabrequest_viewdriver_list_pager"></div>
				                </div>
					        </div>
					    </div>
					</div>
		        </div>
		        <div id="dialog_add_Self_cab_booking" title="User Cab Book">
		            <div class="container">
		                <div class="row">
				            <div class="container">
				                <div class="row2">
				                    <div class="width20">
				                        <? echo $la['CLIENT_NAME']; ?> <b style="color: red;">*</b>
				                    </div>
				                    <div class="width80">
				                    	<input id="self_cabrequest_client_name" class="inputbox" type="text" value="">
				                    	<input id="self_cabrequest_tripid" class="inputbox" type="text" value="" hidden="" disabled="">
				                    </div>
				                </div>
				                <div class="row2">
				                    <div class="width20">
				                        <? echo $la['MOBILENO']; ?> <b style="color: red;">*</b>
				                    </div>
				                    <div class="width80">
				                    	<input id="self_cabrequest_client_mobile" class="inputbox" type="text" value="">
				                    </div>
				                </div>
				                <div class="row2" style="margin-top: 7px !important;">
				                    <div class="width20">
				                        <? echo $la['SCHEDULED_AT']; ?><b style="color: red;">*</b>
				                    </div>
				                    <div class="width80">
				                    	<div class="width50">
				                    		<input readonly class="inputbox-calendar inputbox width100 textboxextra_style" id="self_cabrequest_schedule_date" type="text" value="" />
				                    	</div>
				                    	<div class="width25">
				                    		<select class="width100 textboxextra_style" id="self_cabrequest_schedule_hours">
		                                    <? include ("inc/inc_dt.hours.php"); ?>
		                                </select>
				                    	</div>
				                    	<div class="width20">
				                    		<select style="width: 115%;" class="width100 textboxextra_style" id="self_cabrequest_schedule_sce">
		                                    <? include ("inc/inc_dt.minutes.php"); ?>
		                                </select>
				                    	</div>
				                    </div>
				                </div>
				                <div class="row2" style="margin-top: 7px !important;">
				                    <div class="width20">
				                        <? echo $la['FROM']; ?><b style="color: red;">*</b>
				                    </div>
				                    <div class="width70"><input id="self_cabrequest_from" type="text" class="inputbox" onchange="getfromtoLatlngSelfCabBooking(this.value,'from');">
				                    	<input type="text" id="self_cabrequest_from_latitude" value="" hidden="">
				                    	<input type="text" id="self_cabrequest_from_longitude" value="" hidden="">
				                    </div>
				                    <div class="width10">
				                    	<input class="button icon-ambulance icon" type="button" onclick="cabBookingSearchAddress();" style="min-width: 36px !important;background-position: center;background-size: cover;">
				                    </div>
				                </div>
				                <div class="row2" style="margin-top: 7px !important;">
				                    <div class="width20">
				                        <? echo $la['TO']; ?><b style="color: red;">*</b>
				                    </div>
				                    <div class="width80"><input id="self_cabrequest_to" type="text" class="inputbox" onchange="getfromtoLatlngSelfCabBooking(this.value,'to');">
				                    	<input type="text" id="self_cabrequest_to_latitude" value="" hidden="">
				                    	<input type="text" id="self_cabrequest_to_longitude" value="" hidden="">
				                    </div>
				                </div>
				                <div class="row2" style="margin-top: 7px !important;">
				                    <div class="width20">
				                        <? echo $la['SECURITY']; ?><b style="color: red;">*</b>
				                    </div>
				                    <div class="width80">
				                    	<select id="self_cabrequest_security" type="text" class="inputbox">
				                    		<option value=""></option>
				                    		<option value="Y">Yes</option>
				                    		<option value="N">No</option>
				                    	</select>
				                    </div>
					            </div>
					            <div class="row2" style="margin-top: 7px !important; display: none;" id="self_show_security_list">
				                    <div class="width20">
				                        <? echo $la['SECURITY']; ?><b style="color: red;">*</b>
				                    </div>
				                    <div class="width80">
				                    	<select id="self_cabrequest_security_list" type="text" class="inputbox">
				                    	</select>
				                    </div>
					            </div>
				                <div class="row2" style="margin-top: 7px !important;">
				                    <div class="width20">
				                        <? echo $la['TRIP_TYPE']; ?><b style="color: red;">*</b>
				                    </div>
				                    <div class="width80">
				                    	<select id="self_cabrequest_triptype" class="inputbox">
				                    		<option value=""></option>
				                    		<option value="P">Pick Up</option>
				                    		<option value="D">Drop</option>
				                    	</select>
				                    </div>
				                </div>
				                <div class="row2" style="margin-top: 7px !important;">
				                    <div class="width20">
				                        <? echo $la['DRIVER']; ?> <b style="color: red;">*</b>
				                    </div>
				                    <div class="width80">
				                    	<select id="cabrequest_self_setdriver_userid" class="inputbox" type="text" value=""></select>
				                    </div>
				                </div>
				                <div class="row2" style="margin-top: 7px !important;">
				                    <div class="width20">
				                        <? echo $la['OBJECT']; ?> <b style="color: red;">*</b>
				                    </div>
				                    <div class="width20">
				                    	<input id="cabrequest_self_setobject_search" class="inputbox" type="text" value="">
				                    </div>
				                    <div class="width60">
				                    	<select id="cabrequest_self_setobject" class="inputbox"></select>
				                    </div>
				                </div>
				                <div class="row2" style="padding-top: 13px;margin-top: 7px !important;">
					                <center>
					                	<input type="submit" id="save_self_add_cabrequest" onclick="addCabSelfRequest('save')" value="Save">
					                	<input type="submit" id="update_self_add_cabrequest" onclick="addCabSelfRequest('update')" style="display: none;" value="Update">
					                </center>
					            </div>
				            </div>
		                </div>
		            </div>
		        </div>
		        <div id="Self_cabBooking_Status" title="Booking Status">
		            <div class="container">
		                <div class="row">
				            <div class="container">
				                <div class="row2" id="show_self_booking_fullstatus">
				                    
				                </div>
				            </div>
		                </div>
		            </div>
		        </div>
	       	</div>
	    </div><?php }?>
	</div>
</div>
