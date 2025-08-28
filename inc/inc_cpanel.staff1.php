<div id="dialog_staff_manag_add" title="<? echo $la['ADD_STAFF']; ?>">
   <div class="row">
      <div class="row2">
         <div class="width40"><? echo $la['CLIENT_ID']; ?></div>
         <div class="width20"><input class="width100" id="dialog_staff_manag_clientid_search" placeholder=" <?php echo $la["CLIENT_ID"]; ?>" /></div>
         <div class="width40"><select class="width100" id="dialog_staff_manag_clientid" > </select></div>
      </div>
      <div class="row2">
         <div class="width40"><? echo $la['STAFFNAME']; ?></div>
         <div class="width20"><input class="width100" id="dialog_staff_manag_staff_data_search"  placeholder=" <?php echo $la["STAFFNAME"]; ?>" /></div>
         <div class="width40"><select class="width100" id="dialog_staff_manag_staff_data"></select></div>
      </div>
      <div class="row2">
         <div class="width40"><? echo $la['SITE_LOCATION']; ?></div>
         <div class="width60"><input id="dialog_staff_manag_location" class="inputbox" type="text" value="" maxlength="25"></div>
      </div>
      <div class="row2">
         <div class="width40"><? echo $la['SCHEDULE']; ?></div>
         <div class="width30">
            <input readonly class="inputbox-calendar inputbox width100" id="dialog_staff_manag_date_from" type="text" value=""/>
         </div>
         <div class="width2"></div>
         <div class="width13">
            <select class="width100" id="dialog_staff_manag_hour_schedule">
            <? include ("inc/inc_dt.hours.php"); ?>
            </select>
         </div>
         <div class="width2"></div>
         <div class="width13">
            <select class="width100" id="dialog_staff_manag_minute_schedule">
            <? include ("inc/inc_dt.minutes.php"); ?>
            </select>
         </div>
      </div>
      <div class="row2">
         <div class="width40"><? echo $la['IN_TIME']; ?></div>
         <div class="width30">
            <input readonly class="inputbox-calendar inputbox width100" id="dialog_staff_manag_date_in" type="text" value=""/>
         </div>
         <div class="width2"></div>
         <div class="width13">
            <select class="width100" id="dialog_staff_manag_hour_in">
            <? include ("inc/inc_dt.hours.php"); ?>
            </select>
         </div>
         <div class="width2"></div>
         <div class="width13">
            <select class="width100" id="dialog_staff_manag_minute_in">
            <? include ("inc/inc_dt.minutes.php"); ?>
            </select>
         </div>
      </div>
      <div class="row2">
         <div class="width40"><? echo $la['OUT_TIME']; ?></div>
         <div class="width30">
            <input readonly class="inputbox-calendar inputbox width100" id="dialog_staff_manag_date_out" type="text" value=""/>
         </div>
         <div class="width2"></div>
         <div class="width13">
            <select class="width100" id="dialog_staff_manag_hour_out">
            <? include ("inc/inc_dt.hours.php"); ?>
            </select>
         </div>
         <div class="width2"></div>
         <div class="width13">
            <select class="width100" id="dialog_staff_manag_minute_out">
            <? include ("inc/inc_dt.minutes.php"); ?>
            </select>
         </div>
      </div>
      <div class="row2">
         <div class="width40"><? echo $la['IMEI']; ?></div>
         <div class="width20"><input class="width100" id="dialog_staff_manag_imei_search"  placeholder=" <?php echo $la["FILTEROBJECTS"]; ?>" /></div>
         <div class="width40"><select class="width100" id="dialog_staff_manag_imei"></select></div>
      </div>
      <div class="row2">
         <div class="width40"><? echo $la['WORK']; ?></div>
         <div class="width60">
            <select class="width100" id="dialog_staff_manag_work">
               <option ><? echo $la['SELECT']; ?></option>
               <option ><? echo $la['INSTALLATION']; ?></option>
               <option ><? echo $la['OFFLINE']; ?></option>
               <option ><? echo $la['REPLACE']; ?></option>
               <option ><? echo $la['GENERAL']; ?></option>
            </select>
         </div>
      </div>
      <div class="row2">
         <div class="width40"><? echo $la['TYPES_WORK']; ?></div>
         <div class="width60">
            <select class="width100" id="dialog_staff_manag_worktype" name="state_id[]" multiple="multiple"  class="form-control" style="height:100px;"  >
            </select>
         </div>
      </div>
      <div class="row2">
         <div class="width40"><? echo $la['UNDER_WARRENTY'];?></div>
         <div class="width60">
            <input id="dialog_staff_manag_checkbox" name="stok"  checked  class="checkbox" type="checkbox"  checked="checked" /> 
         </div>
      </div>
      <div class="row2">
         <div class="width40"><? echo $la['NOTE']; ?></div>
         <div class="width60">
            <textarea id="dialog_staff_manag_note" class="width100" style="height:60px;" name="comment" ></textarea>
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