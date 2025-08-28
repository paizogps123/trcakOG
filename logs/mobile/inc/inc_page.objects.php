<?php
include ('../config.php');
?>

<div id="page_objects" class="page">
	<form role="form">
		<div class="input-group form-group btn-group">
			<input id="page_object_search" class="form-control" type="search" placeholder="<? echo $la['SEARCH']; ?>..." onkeyup="objectLoadList();"/>
			<span id="page_object_search_clear" class="input-group-addon">
				<span class="glyphicon glyphicon-remove"></span>
			</span>
		</div>
		<div id="page_object_list" class="list-group"></div>
	</form>
	<a href="#" class="btn btn-default btn-blue show-menu pull-right back-btn" onclick="switchPage('menu');">
		<i class="glyphicon glyphicon-menu-left"></i>
		<? echo $la['BACK']; ?>
	</a>
</div>

<div id="page_object_details" class="page">
	<div id="page_object_detail_list" class="panel panel-default"></div>
	<a href="#" class="btn btn-default btn-blue show-menu pull-right back-btn" onclick="switchPage('objects');">
		<i class="glyphicon glyphicon-menu-left"></i>
		<? echo $la['BACK']; ?>	
	</a>

	<a href="#" class="btn btn-info btn-blue show-menu pull-right back-btn" class="panel panel-default" onclick="switchPage('menu');" style="margin-right:20px">     	
		<i class="glyphicon glyphicon-home"></i>     	
		<?php echo $la['HOME']; ?> 
	</a>

	<a href="#" class="btn btn-warning btn-yellow show-menu pull-right back-btn" class="panel panel-default" onclick="objectLoadDetails();" style="margin-right: 20px;">     	
		<i class="glyphicon glyphicon-refresh"></i>     	
		<?php echo $la['REFRESH']; ?> 
	</a>

	<a href="#" class="btn btn-default btn-green show-menu pull-left back-btn" class="panel panel-default" onclick="switchPage('report');">
    	<i class="glyphicon glyphicon-menu-left"></i>
    	<? echo $la['REPORT']; ?>
	</a>


</div>
