<div id="dialog_rilogbook_fullview" class="opensettings" style="display: none;    padding-top: 55px;" title="<? echo $la['RFID_AND_IBUTTON_LOGBOOK']; ?>">

	<div class="block fullviewheight width100" style="    padding-top: 20px;">
		<div class="block width10">&nbsp;
		</div>
		<div class="block width90">
	<div class="controls-block width100">
		<input style="width: 100px;" class="button float-right rilogbookDeleteAll();" type="button" value="<? echo $la['SHOW']; ?>" onclick="rilogbookShow();"/>
		<input style="width: 100px; margin-right: 3px;" class="button float-right rilogbookDeleteAll();" type="button" value="<? echo $la['EXPORT_CSV']; ?>" onclick="rilogbookExportCSV();"/>
		<input style="width: 100px; margin-right: 3px;" class="button float-right rilogbookDeleteAll();" type="button" value="<? echo $la['DELETE_ALL']; ?>" onclick="rilogbookDeleteAll();"/>
	</div>
	
	<div class="row">
		<div class="block width33">
			<div class="container sendcmd_monvi">
				<div class="row2">
					<div class="width30"><? echo $la['OBJECT']; ?></div>
					<div class="width70"><select class="width100 textboxextra_style" id="dialog_rilogbook_object_list"></select></div>
				</div>
				<div class="row2">
					<div class="width30"><? echo $la['FILTER'];?></div>
					<div class="width70">
						<select class="width100 textboxextra_style" id="dialog_rilogbook_filter" onchange="switchHistoryReportsDateFilter('rilogbook');">
							<option value="0" selected><? echo $la['WHOLE_PERIOD'];?></option>
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
					<div class="width30"><? echo $la['TIME_FROM']; ?></div>
					<div class="width31">
						<input readonly class="inputbox-calendar inputbox width100 textboxextra_style" id="dialog_rilogbook_date_from" type="text" value=""/>
					</div>
					<div class="width4"></div>
					<div class="width15">
						<select class="width100 textboxextra_style" id="dialog_rilogbook_hour_from">
						<? include ("inc/inc_dt.hours.php"); ?>
						</select>
					</div>
					<div class="width4"></div>
					<div class="width15">
						<select class="width100 textboxextra_style" id="dialog_rilogbook_minute_from">
						<? include ("inc/inc_dt.minutes.php"); ?>
						</select>
					</div>
				</div>
				<div class="row2">
					<div class="width30"><? echo $la['TIME_TO']; ?></div>
					<div class="width31">
						<input readonly class="inputbox-calendar inputbox width100 textboxextra_style" id="dialog_rilogbook_date_to" type="text" value=""/>
					</div>
					<div class="width4"></div>
					<div class="width15">
						<select class="width100 textboxextra_style" id="dialog_rilogbook_hour_to">
						<? include ("inc/inc_dt.hours.php"); ?>
						</select>
					</div>
					<div class="width4"></div>
					<div class="width15">
						<select class="width100 textboxextra_style" id="dialog_rilogbook_minute_to">
						<? include ("inc/inc_dt.minutes.php"); ?>
						</select>
					</div>
				</div>
				<div class="row2">
					<div class="width30">
						<? echo $la['DRIVERS']; ?>
					</div>
					<div class="width20">
						<input id="dialog_rilogbook_drivers" type="checkbox" class="checkbox" checked/>
					</div>
				</div>
				<div class="row2">
					<div class="width30">
						<? echo $la['PASSENGERS']; ?>
					</div>
					<div class="width20">
						<input id="dialog_rilogbook_passengers" type="checkbox" class="checkbox" checked/>
					</div>
				</div>
				<div class="row2">
					<div class="width30">
						<? echo $la['TRAILERS']; ?>
					</div>
					<div class="width20">
						<input id="dialog_rilogbook_trailers" type="checkbox" class="checkbox" checked/>
					</div>
				</div>
			</div>
		</div>
		<div class="block width3">
            <div class="vl"></div>
            </div>
		<div class="block width63" style="
    width: 66.5%;
">
			<div class="container overflow_mobvi">
				<table id="rilogbook_logbook_grid"></table>
	<div id="rilogbook_logbook_grid_pager"></div>
			</div>
		</div>
	</div>

	
</div>
</div>
</div>
