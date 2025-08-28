

<!-- CODE DONE BY VETRIVEL.N -->


<div id="dialog_booking" title="<? echo $la['CUSTOMER_BOOKING_REQ'];?>">
	

	
	
	
	
	
	
	<div id="booking_control_tabs">
	
	<div id="divbooking">
		<div class="title-block"><? echo $la['PLEASE_ENTER_PRIMARY_TO_BOOK'];?>
		</div>
		
			<div class="row">
		<div class="block width40">
			<div class="container">
				<div class="row2">
					<div class="width30"><? echo $la['ADDRESS'];?></div>
						
						
						<div class="width60">
						
						<input id="txt_b_address" class="inputbox"  class="width90" type="text" value="" >
						
						</div>
						
						<div class="width10">
						<input class="button icon-ambulance icon" type="button" onclick="openambulancepic();" style="min-width: 36px !important;background-position: center;background-size: cover;">
						</div>
						
						<!-- 
						<div class="width70">
						<div class="width10">
						<div class="icon-places-marker">
						<a href="#" onclick="placesMarkerSelectIcon('img/markers/places/pin-8.svg');">
						<img src="img/markers/places/pin-8.svg" style="width: 32px; height: 32px;"></a>
						</div>
							
						</div>
						</div>
						 -->
						
				</div>
				<div class="row2">
					<div class="width30"><? echo $la['PEOPLE_COUNT'];?></div>
					<div class="width70">
					<input id="txt_b_peoplecount" class="inputbox"  class="width100" type="text" value="" >
						</div>
				</div>
				<div class="row2">
					<div class="width30"><? echo $la['BREATHING'];?></div>
					<div class="width70">
						<select id="ddl_b_breathing" class="width100">
						<option>Yes</option>
						<option>No</option>
						</select>
						</div>
				</div>
				
					
				<div class="row2">
					<div class="width30"><? echo $la['NOTE'];?></div>
					<div class="width70">
						<input id="txt_b_note" class="inputbox"  class="width100" type="text" value="" >
				</div>
				
				</div>
				<div class="row2">
					<div class="width30"><? echo $la['PHONE'];?></div>
					<div class="width70">
					
					<input id="txt_b_phone" class="inputbox"  class="width100" type="text" value="" >
							
					</div>
				</div>
				<div class="row2">
					<div class="width30"><? echo $la['AGE'];?></div>
					<div class="width70">
					
					<input id="txt_b_age" class="inputbox"  class="width100" type="text" value="" >
							
					</div>
				</div>
			</div>
		
		</div>
		<div class="block width1"></div>
		<div class="block width35">
			<div class="container">
				<div class="row2">
					<div class="width30"><? echo $la['GENDER'];?></div>
					<div class="width70">
						<select id="ddl_b_gender" class="width100">
						<option>Select</option>
						<option>Male</option>
						<option>Female</option>

						</select>
						</div>
				</div>

			<div class="row2">
					<div class="width30"><? echo $la['LANDMARK'];?></div>
					<div class="width70">
						<input id="txt_b_note2" class="inputbox"  class="width100" type="text" value="" >
				</div>
				
				</div>
				<?php 
		$ro=array();
		$ro[]="1.Allergic Rx";
   		$ro[]="2.Animal Bite/Attack";
   		$ro[]="3.Assault";
   		$ro[]="4.Breathing Problems";
   		$ro[]="5.Burns";
   		$ro[]="6.Cardiac/Respiratory Arrest";
   		$ro[]="7.Chest Pain/Heart Problems";
   		$ro[]="8.Choking";
   		$ro[]="9.Diabetic ";
   		$ro[]="10.Drowning";
   		$ro[]="11.Electrocution/Lightening Strike";
   		$ro[]="12.Falls";
   		$ro[]="13.Heat Stroke/Problems";
   		$ro[]="14.Hanging/Strangulation/ Suffocation";
   		$ro[]="15.MVC/Traffic Accident";
   		$ro[]="16.Overdose/Poisoning";
   		$ro[]="17.Pregnancy";
   		$ro[]="18.Psychiatric";
   		$ro[]="19.Seizure";
   		$ro[]="20.Sick/Non-Traumatic Pain";
   		$ro[]="21.Stroke";
   		$ro[]="22.Trauma/Lacerations";
   		$ro[]="23.Unconscious";
   		$ro[]="24.Unknown";
		?>
		<div class="row2">
			<div class="width30"><? echo $la['EMERGENCY'];?></div>
			<div class="width70">
				<select id="ddl_b_emergency" class="width100">
				<option>Select</option>
				<?php 	foreach($ro as $key => $value) {
					  ?>
					<option><?php echo $value; ?></option>    <?php } ?>				
				</select>
				</div>
		</div>		
		<div class="row2">
			<div class="width30"><? echo $la['CONSCIOUS'];?></div>
			<div class="width70">
				<select id="ddl_b_conscious" class="width100">
				<option>Yes</option>
				<option>No</option>
				</select>
				</div>
		</div>
		<div class="row2">
			<div class="width30"><? echo $la['VNAME'];?></div>
				<div class="width70">
				
				<input id="txt_b_pateintname" class="inputbox"  class="width100" type="text" value="" >
						
			</div>
		</div>
					
		<div class="row2">
			<div class="width40"><input  class="button   width90" style="text-align:center;"  type="button" value="<? echo $la['CANCEL'];?>" id="btn_b_book_cancel" /></div>
			<div class="width60">
				<input  class="button   width100"  style="background:#3970ca;color:#fff;text-align:center;" onclick="saveb_booking()"  type="button" value="<? echo $la['BOOK_NOW'];?>" id="btn_b_book" />		
			</div>
		</div>
				
	</div>
</div>
		
		<div class="block width25">
			<div class="container">		
				<!-- buttton -->
					<div class="controls-block width100" style="padding: 9px 15px 14px;background:white;">
	
	
	<div class="row">
		<div class="sub-holiday-block block width100">
	
		<div class="time-period  container">

			<div class="row2"> 
			<!--
			<div class="width10">
				<input class="button icon-new icon" type="button" onclick="newbooking();" value="<? echo $la['NEW']; ?>" />
			</div>
			<div class="width10">
				<input class="button icon-create icon" type="button" onclick="cmdSendnew();" value="<? echo $la['SEND']; ?>" />
			</div>	
			-->
			
					<div class="width45">
					
						<input  class="button   width100 buttonhoveramb" style="background:#3970ca;color:#fff;text-align:center;" type="button"  onclick="booking_search('Select');" value="<? echo $la['LIVE_BOOKING'];?>"id="btn_b_search" />
					</div>
					<div class="width10"></div>
					<div class="width45">
						<input  class="button   width100 buttonhoveramb" style="background:red;color:#fff;text-align:center;" type="button"  onclick="booking_search('Waiting');" value="<? echo $la['WAITING'];?>"id="btn_b_search_waiting" />		
					</div>
					<div class="width100">&nbsp;</div>
					<div class="width45">
						<input  class="button   width100 buttonhoveramb" style="background:#622bca;color:#fff;text-align:center;" type="button" onclick="booking_search('Allocated');"  value="<? echo $la['ALLOCATED'];?>" id="btn_b_allocated" />		
					</div>
					<div class="width10"></div>
					<div class="width45">
						<input  class="button   width100 buttonhoveramb" style="background:#13759c;color:#fff;text-align:center;" type="button"  onclick="booking_search('Accepted');"  value="<? echo $la['ACCEPTED'];?>" id="btn_b_search_accepted" />		
					</div>
					<div class="width100">&nbsp;</div>
					
					<div class="width45">
						<input  class="button   width100 buttonhoveramb" style="background: #82a203;color:#fff;text-align:center;" type="button"  onclick="booking_search('Reached');"  value="<? echo $la['REACHED'];?>" id="btn_b_reached" />		
					</div>
					<div class="width10"></div>
					
					<div class="width45">
						<input  class="button   width100 buttonhoveramb" style="background:#ba2bca;color:#fff;text-align:center;" type="button"  onclick="booking_search('Picked Up');" value="<? echo $la['PICKED_UP'];?>"  id="btn_b_search_pickedup" />		
					</div>
					<div class="width100">&nbsp;</div>
  					
					<? if ($_SESSION["privileges"] != 'subuser' && $_SESSION["privileges"] != 'viewer'){?>					
					<div class="width45">
						<input  class="button   width100 buttonhoveramb" style="background:black;color:#fff;text-align:center;" type="button"  onclick="booking_search('Canceled');" value="<? echo $la['CANCELED'];?>"  id="btn_b_search_pickedup" />		
					</div>
					<div class="width10"></div>
					
					<div class="width45">
						<input  class="button   width100 buttonhoveramb" style="background:#737373;color:#fff;text-align:center;" type="button"  onclick="booking_search('Deleted');" value="<? echo $la['DELETED'];?>"  id="btn_b_search_pickedup" />		
					</div>
					<div class="width100">&nbsp;</div>
					
					<? }?>	
															
					<div class="width45">
						<input  class="button   width100 buttonhoveramb" style="background:green;color:#fff;text-align:center;" type="button" onclick="booking_search('Finished');" value="<? echo $la['FINISHED'];?>"  id="btn_b_finished" />		
					</div>
					   
					
			 	
			
			<div class="width10" style="float:right;display:none;">
			
				<div class="width5" style="padding-left:65px;"><? echo $la['STATUS'];?></div>
					<div class="width10">
						<select id="ddl_b_status"  >
						<option>Select</option>
						<option>Waiting</option>
						<option>Allocated</option>
						<option>Accepted</option>
						<option>Reached</option>
						<option>Picked Up</option>
						<option>Finished</option>
						<option>Canceled</option>
						</select>
					</div>
					
					<div class="width5" style="padding-left:15px;"><? echo $la['FROM'];?></div>
					<div class="width15">
					<input id="txt_b_datefrom" readonly  class="inputbox-calendar inputbox width100" type="text" value="<?  echo date("Y-m-d");?>" />
					</div>
					<div class="width5" style="padding-left:15px;"><? echo $la['TO'];?></div>
					<div class="width15">
					<input id="txt_b_dateto" readonly  class="inputbox-calendar inputbox width100" type="text" value="<?  echo date("Y-m-d");?>" />
						</div>
									
				
					<div class="width10">
					<input  class="button   width100" style="background:#3970ca;color:#fff;text-align:center;"  type="button" value="<? echo $la['SEARCH'];?>" id="btn_b_search" />		
					</div>
					<div class="width1"></div>  
					
					<div class="width10">
					<input  class="button   width100" style="text-align:center;"  type="button" value="<? echo $la['CANCEL'];?>" id="btn_b_search_cancel" />		
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
	
	<br>
	
			<div id="amb_booking_list">
			<table id="amb_booking_list_grid"></table>
				<div id="amb_booking_list_grid_pager"></div>
			</div>
		</div>
	
	</div>
	
	
</div>


<div id="dialog_booking_vehicle" title="<? echo $la['ALLOCATE_TO_OBJECT'];?>">
	
	
	<div id="booking_control_tabs">
	
	<div id="divbooking">
	
			<div id="amb_booking_vehicle_list">
			<table id="amb_booking_vehicle_list_grid"></table>
				<div id="amb_booking_vehicle_list_grid_pager"></div>
			</div>
		</div>
	
	</div>
	
	
</div>



<!-- CODE DONE END BY VETRIVEL.N -->



		