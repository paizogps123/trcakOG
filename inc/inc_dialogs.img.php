<div id="dialog_image_gallery_fullview" class="opensettings" style="display: none;    padding-top: 55px;" title="<? echo $la['IMAGE_GALLERY'];?>">
	<div class="block fullviewheight width100">
		<div class="block width10">&nbsp;
		</div>
		<div class="block width90 imagal_mobvi">
	<div class="container">
			<div class="row2">
				<div class="width10"><? echo $la['OBJECT']; ?></div>
				<div class="width40"><select class="width90 textboxextra_style" id="dialog_image_gallery_object_list"></select></div>
				<div class="width10"><? echo $la['FILTER'];?></div>
				<div class="width40">
					<select class="width90 textboxextra_style" id="dialog_image_gallery_filter" onchange="switchHistoryReportsDateFilter('img');">
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
		</div>
		<div class="row2">
			<div class="width50">
				<div class="width19"><? echo $la['TIME_FROM']; ?></div>
				<div class="width20"><input readonly class="inputbox-calendar inputbox width100 textboxextra_style" id="dialog_image_gallery_date_from" type="text" value=""/></div>
				<div class="width1"></div>
				<div class="width25">
					<select class="width100 textboxextra_style" id="dialog_image_gallery_hour_from">
					<? include ("inc/inc_dt.hours.php"); ?>
					</select>
				</div>
				<div class="width1"></div>
				<div class="width25">
					<select class="width80 textboxextra_style" id="dialog_image_gallery_minute_from">
					<? include ("inc/inc_dt.minutes.php"); ?>
					</select>
				</div>
			</div>
			<div class="width50">
				<div class="width18"><? echo $la['TIME_TO']; ?></div>
				<div class="width20" style="    padding-left: 3px;">
					<input readonly class="inputbox-calendar inputbox width100 textboxextra_style" id="dialog_image_gallery_date_to" type="text" value=""/>
				</div>
				<div class="width1"></div>
				<div class="width25">
					<select class="width100 textboxextra_style" id="dialog_image_gallery_hour_to">
					<? include ("inc/inc_dt.hours.php"); ?>
					</select>
				</div>
				<div class="width1"></div>
				<div class="width25">
					<select class="width80 textboxextra_style" id="dialog_image_gallery_minute_to">
					<? include ("inc/inc_dt.minutes.php"); ?>
					</select>
				</div>
			</div>
			</div>
			<div class="row3">
				<center>
					<input style="width: 100px; margin-right: 3px;" class="button textboxextra_style" type="button" value="<? echo $la['DELETE_ALL']; ?>" onclick="imgDeleteAll();"/>
					<input style="width: 100px;" class="button textboxextra_style" type="button" value="<? echo $la['SHOW']; ?>" onclick="imgFilter();"/>
				</center>
			</div>
			<div class="row2">
				<div class="width25" style="    margin-top: -174px;">
					<div class="block float-left img-controls">
						<div class="container">
							
							<table id="image_gallery_list_grid"></table>
							<div id="image_gallery_list_grid_pager"></div>
						</div>
					</div>
				</div>
				<div class="width10"></div>
				<div class="width65">
					<div class="block float-left img-content">
						<div class="container last">
							<div class="row3">
								<div id="image_gallery_img"></div>
							</div>
							<div id="image_gallery_img_data"></div>
						</div>
					</div>
				</div>
			</div>
</div>
</div>
	
</div>
