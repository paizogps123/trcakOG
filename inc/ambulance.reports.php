<?php 
 $roage=array();
 for($i=0;$i<=100;$i++)
 $roage[]=$i;
 //$roage[]="100+";
?>

<div id="dialog_report_properties_amb" title="<? echo $la['REPORT_PROPERTIES']; ?>">
	<div class="row">	
		<div class="title-block"><? echo $la['REPORT'];?></div>
		
		<div class="row">	
			<div class="row2">
				<div class="width10"><? echo $la['EMERGENCY']; ?></div>
				<div class="width90" >
					<select id="ddl_b_emergency_amb" class="width100"   multiple="multiple"  class="js-example-basic-multiple" >
						<option selected >All</option>
						<?php 	foreach($ro as $key => $value) { ?>
	    				<option><?php echo $value; ?></option>    <?php } ?>				
					</select>
				</div>
			</div>
		</div>
				
		<div class="report-block block width30">
			<div class="container">
				<div class="row2">
					<div class="width40"><? echo $la['NAME'];?></div>
					<div class="width60"><input id="dialog_report_name_amb" class="inputbox" type="text" value="" maxlength="30"></div>
				</div>
				<div class="row2">
					<div class="width40"><? echo $la['TYPE']; ?></div>
					<div class="width60">
						<select class="width100" id="dialog_report_type_amb" >
							<optgroup label="<? echo $la['TEXT_REPORTS']; ?>">
							<option value="ambulance_general" selected><? echo $la['AMBULANCE_RPT_GENERAL']; ?></option>
							<option value="ambulance_emergency" selected><? echo $la['AMBULANCE_RPT_EMERGENCY']; ?></option>
							<!-- 
							<optgroup label="<? echo $la['GRAPHICAL_REPORTS']; ?>">
                			<option value="ambulance_count"><? echo $la['TEMPDATA']; ?></option>
                			 -->					
						</select>
					</div>
				</div>
				<div class="row2">
					<div class="width40"><? echo $la['FORMAT']; ?></div>
					<div class="width60">
						<select id="dialog_report_format_amb" style="width:80px;"/>
							<option value="html">HTML</option>
							<option value="pdf">PDF</option>
							<option value="xls">XLS</option>
						</select>
					</div>
				</div>
				
				<div class="row2">
					<div class="width40"><? echo $la['BOOK_BY']; ?></div>
					<div class="width60">
						<select id="dialog_report_bookby_amb" style="width:80px;"/>
							<option value="All">All</option>
							<option value="Web">Web</option>
							<option value="Mobile">Mobile</option>
						</select>
					</div>
				</div>
				
					<div class="row2">
					<div class="width40"><? echo $la['STATUS']; ?></div>
					<div class="width60">
						<select id="dialog_report_bookingstatus_amb" style="width:80px;"/>
						<option><? echo $la['ALL']; ?></option>
						<option><? echo $la['WAITING']; ?></option>
						<option><? echo $la['ALLOCATED']; ?></option>
						<option><? echo $la['ACCEPTED']; ?></option>
						<option><? echo $la['REACHED']; ?></option>
						<option><? echo $la['PICKED_UP']; ?></option>
						<option><? echo $la['FINIHSED']; ?></option>
						<option><? echo $la['CANCELED']; ?></option>
						<option><? echo $la['DELETED']; ?></option>
						</select>
					</div>
				</div>
				
				<div class="row2">
					<div class="width40"><? echo $la['CREW_TIME']; ?></div>
					<div class="width60">
						<select id="dialog_report_crewtime_amb" style="width:80px;"/>
						<option><? echo $la['ALL']; ?></option>
						<option value="<? echo $la['ACCEPTANCE']; ?>">Acceptance (Allocated -> Accepted)</option>
						<option value="<? echo $la['RESPONSE']; ?>">Response (Accepted -> Reached)</option>
						<option value="<? echo $la['ON_SCENE']; ?>">On scene (Reached -> Picked Up)</option>
						<option value="<? echo $la['TRANSPORT']; ?>">Transport (Picked Up -> Finished)</option>
						<option value="<? echo $la['TOTAL']; ?>">Total (Allocated -> Finished)</option>
						<!-- <option value="Turnaround">Turnaround (Finished -> Waiting)</option> -->
						</select>
					</div>
				</div>
				
					<div class="row2">
					<div class="width40"><? echo $la['DURATION']; ?></div>
					<div class="width60">
						<select id="dialog_report_hour_amb" style="width:80px;"/>
						<?php 	for($i=1;$i<=24;$i++) {
  						  ?>
							<option value="<?php echo $i;  ?>" >> <?php echo $i;  ?> h</option>
							<?php 	}
  						  ?>
						</select>
					</div>
				</div>
				
				
			</div>
		</div>

		<div class="report-block block width70">
			<div class="container last">
				<div>
				       
				
					
					
	<div >
			<div class="row">
		<div class="block width40">
			<div class="container">
				<div class="row2">
					<div class="width30"><? echo $la['ADDRESS'];?></div>
						
						
						<div class="width70">
						
						<input id="txt_b_address_amb" class="inputbox"  class="width90" type="text" value="" >
						
						</div>
					
						
				</div>
				<div class="row2">
					<div class="width30"><? echo $la['PEOPLE_COUNT'];?></div>
					<div class="width70">
					<input id="txt_b_peoplecount_amb" class="inputbox"  class="width100" type="text" value="" >
						</div>
				</div>
				
				<div class="row2">
					<div class="width30"><? echo $la['GENDER'];?></div>
					<div class="width70">
						<select id="ddl_b_gender_amb" class="width100">
						<option><? echo $la['ALL'];?></option>
						<option>Male</option>
						<option>Female</option>

						</select>
						</div>
				</div>
			
			
			</div>
		
		</div>
		<div class="block width30">
			<div class="container">
				<div class="row2">
					<div class="width50"><? echo $la['PHONE'];?></div>
					<div class="width50">
					
					<input id="txt_b_phone_amb" class="inputbox"  class="width100" type="text" value="" >
							
					</div>
				</div>
				
				<div class="row2">
					<div class="width50"><? echo $la['AGE'];?></div>
					<div class="width50">
					
					<select id="ddl_age_from_rpt_amb" class="width100">
						<?php 	foreach($roage as $key => $value) {
  						  ?>
    						<option 
						<?php if($value==0) echo "selected"; ?>
    						><?php echo $value; ?></option>    <?php } ?>
						</select>
							
					</div>
				</div>
					<div class="row2">
					<div class="width50"><? echo $la['BREATHING'];?></div>
					<div class="width50">
						<select id="ddl_b_breathing_amb" class="width100">
						<option><? echo $la['ALL'];?></option>
						<option>Yes</option>
						<option>No</option>
						</select>
						</div>
				</div>
				

				
			</div>
		</div>
	
		<div class="block width30">
			<div class="container">
				
				<div class="row2">
				<div class="width40"><? echo $la['VNAME'];?></div>
					<div class="width60">
					
					<input id="txt_b_pateintname_amb" class="inputbox"  class="width100" type="text" value="" >
							
					</div>
					</div>
				<div class="row2">
					<div class="width40"><? echo $la['AGE'];?></div>
					<div class="width60">
					
					<select id="ddl_age_to_rpt_amb" class="width100">
						<?php 	foreach($roage as $key => $value) {
  						  ?>
    						<option <?php if($value==100) echo "selected"; ?>><?php echo $value; ?></option>    <?php } ?>
						</select>
							
					</div>
				</div>
				
				<div class="row2">
					<div class="width40"><? echo $la['CONSCIOUS'];?></div>
					<div class="width60">
						<select id="ddl_b_conscious_amb" class="width100">
						<option><? echo $la['ALL'];?></option>
						<option>Yes</option>
						<option>No</option>
						</select>
						</div>
				</div>
				
				
			</div>
		
		</div>
		
			
	</div>	
	
	
		</div>
	
					
					
					
						
					
					</div>
					
				
				
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="schedule-block block width60">
			<div class="container">
				<div class="title-block"><? echo $la['SCHEDULE'];?></div>
				<div class="row2">
					<div class="width40"><? echo $la['DAILY'];?></div>
					<div class="width60"><input id="dialog_report_schedule_period_daily_amb" type="checkbox" <? if ($gsValues['REPORTS_SCHEDULE'] == 'false') { ?> disabled=disabled <? } ?>/></div>
				</div>
				<div class="row2">
					<div class="width40"><? echo $la['WEEKLY'];?></div>
					<div class="width60"><input id="dialog_report_schedule_period_weekly_amb" type="checkbox" <? if ($gsValues['REPORTS_SCHEDULE'] == 'false') { ?> disabled=disabled <? } ?>/></div>
				</div>
				<div class="row2">
					<div class="width40"><? echo $la['SEND_TO_EMAIL'];?></div>
					<div class="width60"><input id="dialog_report_schedule_email_address_amb" class="inputbox" type="text" value="" maxlength="500" placeholder="<? echo $la['EMAIL_ADDRESS']; ?>" <? if ($gsValues['REPORTS_SCHEDULE'] == 'false') { ?> disabled=disabled <? } ?>/></div>
				</div>
			</div>
		</div>
		<div class="time-period block width40">
			<div class="container last">
				<div class="title-block"><? echo $la['TIME_PERIOD'];?></div>
				<div class="row2">
					<div class="width40"><? echo $la['FILTER'];?></div>
					<div class="width60">
						<select class="width100" id="ambrpt_dialog_report_filter" onchange="switchHistoryReportsDateFilter('ambulance');">
							<option value="0" selected></option>
							<option value="1"><? echo $la['LAST_HOUR'];?></option>
							<option value="2"><? echo $la['TODAY'];?></option>
							<option value="3"><? echo $la['YESTERDAY'];?></option>
							<option value="4"><? echo $la['BEFORE_2_DAYS'];?></option>
							<option value="5"><? echo $la['BEFORE_3_DAYS'];?></option>
							<option value="6"><? echo $la['THIS_WEEK'];?></option>
							<option value="7"><? echo $la['LAST_WEEK'];?></option>
							<option value="8"><? echo $la['THIS_MONTH'];?></option>
							<option value="9"><? echo $la['LAST_MONTH'];?></option>
						</select>
					</div>
				</div>
				<div class="row2">
					<div class="width40"><? echo $la['TIME_FROM']; ?></div>
					<div class="width30">
						<input readonly class="inputbox-calendar inputbox width100" id="ambrpt_dialog_report_date_from" type="text" value=""/>
					</div>
					<div class="width2"></div>
					<div class="width13">
						<select class="width100" id="ambrpt_dialog_report_hour_from">
						<? include ("inc/inc_dt.hours.php"); ?>
						</select>
					</div>
					<div class="width2"></div>
					<div class="width13">
						<select class="width100" id="ambrpt_dialog_report_minute_from">
						<? include ("inc/inc_dt.minutes.php"); ?>
						</select>
					</div>
				</div>
				<div class="row2">
					<div class="width40"><? echo $la['TIME_TO']; ?></div>
					<div class="width30">
						<input readonly class="inputbox-calendar inputbox width100" id="ambrpt_dialog_report_date_to" type="text" value=""/>
					</div>
					<div class="width2"></div>
					<div class="width13">
						<select class="width100" id="ambrpt_dialog_report_hour_to">
						<? include ("inc/inc_dt.hours.php"); ?>
						</select>
					</div>
					<div class="width2"></div>
					<div class="width13">
						<select class="width100" id="ambrpt_dialog_report_minute_to">
						<? include ("inc/inc_dt.minutes.php"); ?>
						</select>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<center>
		<input class="button icon-action2 icon" type="button" onclick="reportPropertiesAmb('generate');" value="<? echo $la['GENERATE']; ?>" />&nbsp;
		<input class="button icon-save icon" type="button" onclick="reportPropertiesAmb('save');" value="<? echo $la['SAVE']; ?>" />&nbsp;
		<input class="button icon-close icon" type="button" onclick="reportPropertiesAmb('cancel');" value="<? echo $la['CANCEL']; ?>" />
	</center>
</div>

<div id="dialog_reports_amb" title="<? echo $la['AMBULANCE'].' '.$la['REPORTS']; ?>">
	<div id="reports_tabs_amb">
		<ul>           
			<li><a href="#reports_reports_tab_amb"><? echo $la['REPORTS'];?></a></li>
			<li><a href="#reports_generated_tab_amb"><? echo $la['GENERATED'];?></a></li>
		</ul>
		<div id="reports_reports_tab_amb">
			<table id="report_list_grid_amb"></table>
			<div id="report_list_grid_pager_amb"></div>
		</div>
		<div id="reports_generated_tab_amb">
			<table id="reports_generated_list_grid_amb"></table>
			<div id="reports_generated_list_grid_pager_amb"></div>
		</div>
	</div>
</div>
