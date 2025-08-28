<div id="dialog_staff_manag_add" title="<? echo $la['ADD_STAFF']; ?>">
    <div class="row2">
   		<div class="report-block block width40">
   			<div class="row2">
		        <div class="width20"><? echo $la['CLIENT_ID']; ?> <b style="color: red;">*</b></div>
		        <div class="width20"><input class="width100" id="dialog_staff_manag_clientid_search" onkeyup="refreshServiceEntryGrid();" placeholder=" <?php echo $la["CLIENT_ID"]; ?>" /></div>
		        <div class="width50"><select class="width100" id="dialog_staff_manag_clientid" onchange="refreshServiceEntryGrid()"> </select></div>
      		</div>
      		<div class="row2">
		        <div class="width20"><? echo $la['STAFFNAME']; ?><b style="color: red;">*</b></div>
		        <div class="width20"><input class="width100" id="dialog_staff_manag_staff_data_search"  placeholder=" <?php echo $la["STAFFNAME"]; ?>"  onkeyup="refreshServiceEntryGrid()" /></div>
		        <div class="width50"><select class="width100" id="dialog_staff_manag_staff_data" onchange="refreshServiceEntryGrid()"></select></div>
		    </div>
      		<div class="row2">
		        <div class="width20"><? echo $la['COMPANY']; ?><b style="color: red;">*</b></div>
		        <div class="width70"><input id="dialog_staff_manag_company" class="inputbox" type="text" value="" maxlength="25"></div>
		    </div>
		    <div class="row2">
		        <div class="width20"><? echo $la['SITE_LOCATION']; ?><b style="color: red;">*</b></div>
		        <div class="width70"><input id="dialog_staff_manag_site_location" class="inputbox" type="text" value="" maxlength="25"></div>
		    </div>
		    <div class="row2">
		        <div class="width20"><? echo $la['WORK_DATE']; ?><b style="color: red;">*</b></div>
		        <div class="width33">
		            <input readonly class="inputbox-calendar inputbox width100" id="dialog_staff_manag_work_date" type="text" value=""/>
		        </div>
		        <div class="width2"></div>
		        <div class="width15">
		            <select class="width100" id="dialog_staff_manag_work_hour">
		            <? include ("inc/inc_dt.hours.php"); ?>
		            </select>
		        </div>
		        <div class="width2"></div>
		        <div class="width15">
		        	<select class="width100" id="dialog_staff_manag_work_minute">
		            <? include ("inc/inc_dt.minutes.php"); ?>
		            </select>
		        </div>
		     </div>
		      <div class="row2">
		        <div class="width20"><? echo $la['IMEI']; ?><b style="color: red;">*</b></div>
		        <div class="width20"><input class="width100" id="dialog_staff_manag_imei_search"  placeholder=" <?php echo $la["FILTEROBJECTS"]; ?>" onkeyup="detailObjectlist_onkeyup(),refreshServiceEntryGrid()"/></div>
		        <div class="width50"><select class="width100" id="dialog_staff_manag_imei" onchange="detailObjectlist(this.value),refreshServiceEntryGrid()"></select></div>
		    </div>
		    <div class="row2">
		        <div class="width20"><? echo $la['OBJECT']; ?></div>
		        <div class="width10"><input class="width100" id="dialog_staff_manag_check_object_name" type="checkbox" onclick="staffcheckvariable('chobject')"/></div>
		        <div class="width60"><input id="dialog_staff_manag_object" class="inputbox" type="text" value="" maxlength="25" disabled="" ></div>
		    </div>
		    <div class="row2">
		        <div class="width20"><? echo $la['VEHICLE_TYPE']; ?></div>
		        <div class="width10"><input class="width100" id="dialog_staff_manag_check_veticletype" type="checkbox" onclick="staffcheckvariable('chvehicletype')"/></div>
		        <div class="width60"><input id="dialog_staff_manag_vehicle_type" class="inputbox" type="text" value="" maxlength="25" disabled="" ></div>
		    </div>
		    <div class="row2">
		        <div class="width20"><? echo $la['FUEL1']; ?></div>
		        <div class="width10"><input class="width100" id="dialog_staff_manag_check_fuel1" type="checkbox" onclick="staffcheckvariable('chfuel1')"/></div>
		        <div class="width60">
		        	<select class="width100" id="dialog_staff_manag_fuel1" disabled="" >                        
                    </select>
		        </div>
		    </div>
		    <div class="row2">
		        <div class="width20"><? echo $la['FUEL2']; ?></div>
		        <div class="width10"><input class="width100" id="dialog_staff_manag_check_fuel2" type="checkbox" onclick="staffcheckvariable('chfuel2')"/></div>
		        <div class="width60">
		        	<select class="width100" id="dialog_staff_manag_fuel2" disabled="">                     
                    </select>
		        </div>
		    </div>
		    <div class="row2">
		        <div class="width20"><? echo $la['FUELTANKSIZE']; ?></div>
		        <div class="width10"><input class="width100" id="dialog_staff_manag_check_tank_size" type="checkbox" onclick="staffcheckvariable('chfueltanksize')"/></div>
		        <div class="width60"><input id="dialog_staff_manag_fuel_tanksize" class="inputbox" type="text" value="" maxlength="25" disabled="" ></div>
		    </div>		    
		    <div class="row2">
		        <div class="width20"><? echo $la['SIM_CARD_NUMBER']; ?></div>
		        <div class="width70"><input id="dialog_staff_manag_select_sim_number" class="inputbox" type="text" value="" maxlength="25" disabled=""></div>
		    </div>
		    <div class="row2">
		        <div class="width20"><? echo $la['NEW_SIM_CARD_NUMBER']; ?></div>
		        <div class="width8"><input class="width100" id="dialog_staff_manag_edit_sim" type="checkbox" onclick="staffcheckvariable('changemewsim')"/></div>
		        <div class="width20"><input class="width100" id="dialog_staff_manag_search_sim" disabled="" placeholder=" <?php echo $la["SIM_CARD_NUMBER"]; ?>" /></div>
		        <div class="width40"><select class="width100" id="dialog_staff_manag_select_new_sim_number" disabled=""></select></div>
		    </div>
		    <div class="row2">
		        <div class="width20"><? echo $la['WORK']; ?><b style="color: red;">*</b></div>
		        <div class="width70">
		            <select class="width100" id="dialog_staff_manag_work" onchange="selectWorkType(this.value)">
		               <option value='' ><? echo $la['SELECT']; ?></option>
		               <option ><? echo $la['INSTALLATION']; ?></option>
		               <option ><? echo $la['REINSTALLATION']; ?></option>
		               <option ><? echo $la['OFFLINE']; ?></option>
		               <option ><? echo $la['REPLACE']; ?></option>
		               <option ><? echo $la['REMOVE']; ?></option>
		               <option ><? echo $la['GENERAL']; ?></option>
		            </select>
		        </div>
	      	</div>
	      	<div class="row2">
		        <div class="width20"><? echo $la['TYPES_WORK']; ?><b style="color: red;">*</b></div>
		        <div class="width70">
		            <select class="width100" id="dialog_staff_manag_worktype" name="state_id[]" multiple="multiple"  class="form-control" style="height:100px;"  >
		            </select>
		        </div>
		    </div>
   		</div>
   		<div class="report-block block width40">
   			<div class="row2">
		        <div class="width20"><? echo $la['REPORT']; ?><b style="color: red;">*</b></div>
		        <div class="width70">
		            <select class="width100" id="dialog_staff_manag_report" name="report_id[]" multiple="multiple"  class="form-control" style="height:100px;"  >
		            </select>
		        </div>
		    </div>			    
		    <div class="row2">
		        <div class="width20"><? echo $la['ACCESSORIES']; ?><b style="color: red;">*</b></div>
		        <div class="width70">
		            <select class="width100" id="dialog_staff_manag_accessories" name="accessories_id[]" multiple="multiple"  class="form-control" style="height:100px;"  >
		            	<!-- <option value=''>Select</option> -->
		            	<option value='A/C Line'>A/C Line</option>
		            	<option value='Temp Sensor'>Temp Sensor</option>
		            	<option value='RFID Reader'>RFID Reader</option>
		            	<option value='Panic Button'>Panic Button</option>
		            	<option value='Buzzer'>Buzzer</option>
		            	<option value='Ignition Line Relay'>Ignition Line Relay</option>
		            	<option value='CCTV'>CCTV</option>
		            </select>
		        </div>
		    </div>		    	    
		    <div class="row2">
		        <div class="width20"><? echo $la['STATUS']; ?><b style="color: red;">*</b></div>
		        <div class="width70">
		        	<select id="dialog_staff_manag_status" class="width100">
		        		<option value=''><? echo $la['SELECT']; ?></option>
		        		<option value='<? echo $la['ALL_OK']; ?>'><? echo $la['ALL_OK']; ?></option>
		        		<option value='<? echo $la['PENDING']; ?>'><? echo $la['PENDING']; ?></option>
		        		<option value='<? echo $la['CLOSE']; ?>'><? echo $la['CLOSE']; ?></option>
		        	</select>
		        </div>
		    </div>
		    <div class="row2">
		        <div class="width20"><? echo $la['REMARK']; ?></div>
		        <div class="width70"><textarea class="width100" id="dialog_staff_manag_remark"></textarea></div>
		    </div>
		    <div class="row2">
		        <div class="width20"><? echo $la['SERVICE'].' '.$la['CLOSE']; ?></div>
		        <div class="width70"><input readonly class="inputbox-calendar inputbox width100" id="dialog_staff_manag_service_close" type="text" value=""/></div>
		    </div>
		    <div class="row2">
		        <div class="width20"><? echo $la['UNDER_WARRENTY'];?></div>
		        <div class="width20">
		            <input id="dialog_staff_manag_checkbox" name="stok"  checked  class="checkbox" type="checkbox"  checked="checked" disabled="" /> 
		        </div>
		         <div class="width50"><input readonly class="inputbox-calendar inputbox width100" id="dialog_staff_manag_warrenty_expire" type="text" value="" disabled="disabled" /></div>
		     </div>
		    <div class="row2">
		        <div class="width20"><? echo $la['REASON']; ?></div>
		        <div class="width70"><input id="dialog_staff_manag_reason" class="inputbox" type="text" value="" maxlength="25"></div>
		    </div>
		    <div class="row2">
		        <div class="width20"><? echo $la['USER']; ?></div>
		        <div class="width10"><input class="width100" id="dialog_staff_manag_check_change_users" type="checkbox" onclick="staffcheckvariable('chchangeuser')"/></div>
		        <div class="width60"><select id="dialog_staff_manag_change_users" multiple="multiple" class="width100"></select></div>
		    </div>     
   		</div>
   		<div class="report-block block width20">
   			<div class="row2">
		        <div class="width70">
	               <div id="cpanel_staff_manag_service">
	                  <table id="cpanel_staff_manag_old_service_list"></table>
	                  <div id="cpanel_staff_manag_old_service_list_pager" ></div>
	               </div>
	            </div>
      		</div>
   		</div>
    </div>
	<center>
      <input class="button icon-save icon" type="button" onclick="ServiceAdd('save');" value="<? echo $la['SAVE']; ?>" />
      <input class="button icon-close icon" type="button" onclick="staffAdd('cancel');" value="<? echo $la['CLEAR']; ?>" />
   </center>
</div>
<div id="dialog_staff_manag_employee_master">
   <div class="row">
      <div class="sub-account-block block width100">
         <div class="container">
            <div class="title-block"><? echo $la['EMPLOYEEREG']; ?></div>
            <div class="row2">
               <div class="width1"></div>
               <div class="width10"><? echo $la['STAFFNAME']; ?></div>
               <div class="width15"><input id="dialog_staff_manag_staffname" class="inputbox" type="text" /></div>
               <div class="width1"></div>
               <div class="width10"><? echo $la['GENDER']; ?></div>
               <div class="width15">
                  <select class="width100" id="dialog_staff_manag_gender" />
                     <option>Male</option>
                     <option >Female</option>
                  </select>
               </div>
               <div class="width1"></div>
               <div class="width5"><? echo $la['DOB']; ?></div>
               <div class="width15">
                  <input readonly class="inputbox-calendar inputbox width100" id="dialog_staff_manag_dob" type="text" value=""/>
               </div>
               <div class="width1"></div>
               <div class="width10"><? echo $la['MOBILENO']; ?></div>
               <div class="width15"><input id="dialog_staff_manag_mobile" class="inputbox" type="text"  /></div>
            </div>
            <div class="row2">
               <div class="width1"></div>
               <div class="width10"><? echo $la['QUALIFICATION']; ?></div>
               <div class="width15"><input id="dialog_staff_manag_qualification" class="inputbox" type="text"  /></div>
               <div class="width1"></div>
               <div class="width10"><? echo $la['EXPERIANCE']; ?></div>
               <div class="width15"><input id="dialog_staff_manag_experiance" class="inputbox" type="text"  /></div>
               <div class="width1"></div>
               <div class="width5"><? echo $la['DOJ']; ?></div>
               <div class="width15">
                  <input readonly class="inputbox-calendar inputbox width100" id="dialog_staff_manag_doj" type="text" value=""/>
               </div>
               <div class="width1"></div>
               <div class="width10"><? echo $la['ADDRESS']; ?></div>
               <div class="width15"><input id="dialog_staff_manag_address" class="inputbox" type="text"  /></div>
            </div>
            <div class="row2">
               <div class="width1"></div>
               <div class="width10"><? echo $la['DESIGNATION']; ?></div>
               <div class="width15"><select class="width100" id="dialog_staff_manag_desi_id" ></select></div>
               <div class="width1"></div>
               <div class="width10"><? echo $la['ID']; ?></div>
               <div class="width15"><input id="dialog_staff_manag_card_id" class="inputbox" type="text"  /></div>
               <div class="width1"></div>
               <div class="width5"><? echo $la['NOTE']; ?></div>
               <div class="width40">
                  <textarea id="dialog_staff_manag_notes"  style="height:30px;" name="comment" ></textarea>
               </div>
            </div>
            <center>				
               <input class="button icon-save icon" type="button" onclick="staffemp('save');" value="<? echo $la['SAVE']; ?>" />
               <input class="button icon-close icon" type="button" onclick="staffemp('cancel');" value="<? echo $la['CLEAR']; ?>" />
            </center>
         </div>
      </div>
   </div>
   <div class="row">
      <div class="sub-account-block block width100">
         <div class="container">
            <div class="title-block"><? echo $la['EMPLOYEE_MASTER_LIST'];?></div>
            <div class="width100">
               <div id="boarding_dept_list">
                  <table id="cpanel_staff_manag_emp_list_grid"></table>
                  <div id="boarding_dept_list_grid_pager" ></div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<div id="dialog_staff_view_service" title="<? echo $la['SERVICE_PROPERTIES']; ?>">
	<div class="row2">
   		<div class="report-block block width50">
   			<div class="row2"><br>
		        <div class="width20"><b><? echo $la['ID']; ?></b></div>
		        <div class="width2">:</div>
		        <div class="width70"><span id="view_service_id"></span></div>
      		</div>
      		<div class="row2"><br>
		        <div class="width20"><b><? echo $la['CLIENT_NAME']; ?></b></div>
		        <div class="width2">:</div>
		        <div class="width70"><span id="view_service_client_name"></span></div>
      		</div>
      		<div class="row2"><br>
		        <div class="width20"><b><? echo $la['STAFFNAME']; ?></b></div>
		        <div class="width2">:</div>
		        <div class="width70"><span id="view_service_staff_name"></span></div>
		    </div>
		    <div class="row2"><br>
		        <div class="width20"><b><? echo $la['COMPANY']; ?></b></div>
		        <div class="width2">:</div>
		        <div class="width70"><span id="view_service_company"></span></div>
		    </div>
		    <div class="row2"><br>
		        <div class="width20"><b><? echo $la['SITE_LOCATION']; ?></b></div>
		        <div class="width2">:</div>
		        <div class="width70"><span id="view_service_site_location"></span></div>
		    </div>
		    <div class="row2"><br>
		        <div class="width20"><b><? echo $la['WORK_DATE']; ?></b></div>
		        <div class="width2">:</div>
		        <div class="width70"><span id="view_service_work_date"></span></div>
		    </div>
		    <div class="row2"><br>
		        <div class="width20"><b><? echo $la['OBJECT']; ?></b></div>
		        <div class="width2">:</div>
		        <div class="width70"><span id="view_service_object_name"></span></div>
		    </div>
		    <div class="row2"><br>
		        <div class="width20"><b><? echo $la['VEHICLE_TYPE']; ?></b></div>
		        <div class="width2">:</div>
		        <div class="width70"><span id="view_service_vehicle_type"></span></div>
		    </div>
		    <div class="row2"><br>
		        <div class="width20"><b><? echo $la['FUELTANKSIZE']; ?></b></div>
		        <div class="width2">:</div>
		        <div class="width70"><span id="view_service_fuel_tank_size"></span></div>
		    </div>
		    <div class="row2"><br>
		        <div class="width20"><? echo $la['FUEL1']; ?></div>
		        <div class="width2">:</div>
		        <div class="width70"><span id="view_service_fuel1"></span></div>
		    </div>
		    <div class="row2"><br>
		        <div class="width20"><b><? echo $la['FUEL2']; ?></b></div>
		        <div class="width2">:</div>
		        <div class="width70"><span id="view_service_fuel2"></span></div>
		    </div>
		    <div class="row2"><br>
		        <div class="width20"><b><? echo $la['SIM_CARD_NUMBER']; ?></b></div>
		        <div class="width2">:</div>
		        <div class="width70"><span id="view_service_sim_number"></span></div>
		    </div>
		    <div class="row2"><br>
		        <div class="width20"><b><? echo $la['WORK']; ?></b></div>
		        <div class="width2">:</div>
		        <div class="width70"><span id="view_service_work"></span></div>
		    </div>
		    <div class="row2"><br>
		        <div class="width20"><b><? echo $la['TYPES_WORK']; ?></b></div>
		        <div class="width2">:</div>
		        <div class="width70"><span id="view_service_type_work"></span></div>
		    </div>
		    <div class="row2"><br>
		        <div class="width20"><b><? echo $la['REPORT']; ?></b></div>
		        <div class="width2">:</div>
		        <div class="width70"><span id="view_service_work_report"></span></div>
		    </div>
		</div>
		<div class="report-block block width50">
   			<div class="row2"><br>
		        <div class="width20"><b><? echo $la['ACCESSORIES']; ?></b></div>
		        <div class="width2">:</div>
		        <div class="width70"><span id="view_service_accessories"></span></div>
		    </div>
      		<div class="row2"><br>
		        <div class="width20"><b><? echo $la['STATUS']; ?></b></div>
		        <div class="width2">:</div>
		        <div class="width70"><span id="view_service_status"></span></div>
		    </div>
		    <div class="row2"><br>
		        <div class="width20"><b><? echo $la['REMARK']; ?></b></div>
		        <div class="width2">:</div>
		        <div class="width70"><span id="view_service_remark"></span></div>
		    </div>
		    <div class="row2"><br>
		        <div class="width20"><b><? echo $la['SERVICE'].' '.$la['CLOSE']; ?></b></div>
		        <div class="width2">:</div>
		        <div class="width70"><span id="view_service_service_close"></span></div>
		    </div>
		    <div class="row2"><br>
		        <div class="width20"><b><? echo $la['UNDER_WARRENTY']; ?></b></div>
		        <div class="width2">:</div>
		        <div class="width70"><span id="view_service_warrenty"></span></div>
		    </div>
		    <div class="row2"><br>
		        <div class="width20"><b><? echo $la['REASON']; ?></b></div>
		        <div class="width2">:</div>
		        <div class="width70"><span id="view_service_reason"></span></div>
		    </div>
		     <div class="row2"><br>
		        <div class="width90"><span id="view_service_update_settings"></span></div>
		    </div>
		</div>
	</div>
</div>