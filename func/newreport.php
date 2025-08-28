<?php
include ('../init.php');
include ('fn_common.php');
?>
<!-- <form action="" method="post">
<input type="text" name="newreport" id="newreport">
<input type="text" name="reportlng" id="reportlng">
<input type="text" name="status" id="status">
<input type="text" name="groupid" id="groupid">
<input type="submit" name="addnewreport" value="submit" id="addnewreport"> -->
</form>
<?php
// $q="SELECT * FROM gs_objects a LEFT JOIN gs_user_objects b ON a.imei=b.imei WHERE user_id='439' and odometer>100";
// $r=mysqli_query($ms,$q);
// $ic=1;
// while ($row=mysqli_fetch_assoc($r)) {
// 	echo $ic;
// 	echo '<br>';
// 	$ic++;
// 	$param= json_decode($row['params'],true);

// 	$odo=getSensorFromType($row['imei'], 'odo');

// 	$myfile = fopen("vvv_add.txt", "a");
// 	fwrite($myfile,json_encode($param));
// 	fwrite($myfile, "\n");
// 	fclose($myfile);

// 	if(isset($param[$odo[0]['param']])){
// 		$param[$odo[0]['param']]=0;
// 	}

// 	if(isset($param['odo'])){
// 		$param['odo']='0';
// 	}
// 	if(isset($param['odor'])){
// 		$param['odor']='0';
// 	}
// 	$qu="UPDATE `gs_objects` SET `mileage`=0,`odometer`=0,`params`='".json_encode($param)."' WHERE `imei`='".$row['imei']."'";
// 	echo $qu;
// 	echo '<br>';

// 	$myfile = fopen("vvv_add.txt", "a");
// 	fwrite($myfile,$qu);
// 	fwrite($myfile, "\n");
// 	fclose($myfile);

// 	mysqli_query($ms,$qu);

// 	$qu="UPDATE gs_object_data_".$row['imei']." SET `mileage`=0,`params`='".json_encode($param)."' WHERE dt_tracker='".$row['dt_tracker']."'";
// 	echo $qu;
// 	echo '<br>';
// 	mysqli_query($ms,$qu);
// }

$q="SELECT * FROM gs_objects a LEFT JOIN gs_user_objects b ON a.imei=b.imei WHERE user_id='439' AND a.odometer_type='sen'";
$r=mysqli_query($ms,$q);
$ic=1;
while ($row=mysqli_fetch_assoc($r)) {
	$param= json_decode($row['params'],true);

	// $odo=getSensorFromType($row['imei'], 'odo');
	$odovalue='';
	$obinc=1;
	$olat='';
	$olng='';

	$qq="SELECT * FROM gs_object_data_".$row['imei']." WHERE dt_tracker>'2022-09-31 10:00:00' ORDER BY dt_tracker ASC";
	$rr=mysqli_query($ms,$qq);
	while($rowr=mysqli_fetch_assoc($rr)){
		$bparam= json_decode($rowr['params'],true);

		$qq1="SELECT * FROM gs_object_data_".$row['imei']." WHERE dt_tracker<'".$rowr['dt_tracker']."' ORDER BY dt_tracker DESC LIMIT 1";
		echo $qq1;
		echo '<br>';
		$rr1=mysqli_query($ms,$qq1);
		$loc_prev=mysqli_fetch_assoc($rr1);

		if($odovalue==''){
			$odometer=0;
			$odovalue='0';
		}
		else{
			$odometer = getLengthBetweenCoordinates($loc_prev['lat'], $loc_prev['lng'], $rowr['lat'], $rowr['lng']);
			$odometer=$loc_prev['mileage']+$odometer;
		}

		if(isset($bparam['odo'])){
			$bparam['odo']=round($odometer,2);
		}
		if(isset($bparam['odor'])){
			$bparam['odor']=round($odometer,2);
		}


		$qu="UPDATE gs_object_data_".$row['imei']." SET `mileage`='".round($odometer,2)."',`params`='".json_encode($bparam)."' WHERE dt_tracker='".$rowr['dt_tracker']."'";
		echo $qu;
		echo '<br>';
		$obinc++;
		mysqli_query($ms,$qu);

	}

	if(isset($bparam['odo'])){
		$param['odo']=round($odometer,2);
	}
	if(isset($bparam['odor'])){
		$param['odor']=round($odometer,2);
	}
	if(isset($odometer)){
		$qu="UPDATE `gs_objects` SET `mileage`='".round($odometer,2)."',`odometer`='".round($odometer,2)."',`params`='".json_encode($param)."',odometer_type='gps' WHERE `imei`='".$row['imei']."'";

		echo $qu;
		echo '<br>';
		mysqli_query($ms,$qu);
	}
}
?>