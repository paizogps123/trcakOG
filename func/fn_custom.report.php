<?php
session_start();
include ('../init.php');
include ('fn_common.php');
include ('fn_route.php');
checkUserSession();

if ($_SESSION["privileges"] == 'subuser')
	{
		$user_id = $_SESSION["manager_id"];
	}
	else
	{
		$user_id = $_SESSION["user_id"];
	}

// loadLanguage($_SESSION["language"], $_SESSION["units"]);
if(@$_POST['cmd'] == 'get_fuledistance_report')
{
	if(isset($_POST['values']) and $_POST['values']!=''){
		$excelvalue=$_POST['values'];

		for ($i=0; $i < count($excelvalue); $i++) { 
			$startdate='';
			$endtdate='';
			if(isset($excelvalue[$i]['VEHICLE NO'])){
				$object=$excelvalue[$i]['VEHICLE NO'];
			}
			if(isset($excelvalue[$i]['PREVIOUS DATE AND TIME'])){
				$startdate=$excelvalue[$i]['PREVIOUS DATE AND TIME'];
				$startdate=explode(' ',$startdate);
				$startdate=$startdate[0].' 00:00:00';
				$startdate=date("Y-m-d H:i:s", strtotime($startdate));
			}
			if(isset($excelvalue[$i]['CURRENT DATE & TIME'])){
				$endtdate=$excelvalue[$i]['CURRENT DATE & TIME'];
				$endtdate=explode(' ',$endtdate);
				$endtdate=$endtdate[0].' 23:59:59';
				$endtdate=date("Y-m-d H:i:s", strtotime($endtdate));			
			}
			$myfile = fopen("vvv.txt", "a");
				fwrite($myfile,$startdate);
				$myfile = fopen("vvv.txt", "a");
				fwrite($myfile,$endtdate);
				fwrite($myfile, "\n");
				fclose($myfile);
			if(isset($object) and isset($startdate) and isset($endtdate) and validateDate($startdate)==1 and validateDate($endtdate)==1){
				$returnvalue=getFuelvalue($object,$startdate,$endtdate);
				$excelvalue[$i]['previous_filling_date']=$returnvalue['start'];			
				$excelvalue[$i]['current_filling_date']=$returnvalue['end'];			
				$excelvalue[$i]['diffrence']=$returnvalue['diffrence'];			
			}else{
				$excelvalue[$i]['previous_filling_date']='invalid';			
				$excelvalue[$i]['current_filling_date']='';			
				$excelvalue[$i]['diffrence']='';	
				// echo base64_encode('<table><tr>No Data Found</tr></table>');
				// die;
			}
		}
		$tavlevalue='<table>';
		$tavlevalue.='<tr><th style="background-colour:#00BFFF;colour:white;">S.N</th>
		<th>VEHICLE NO</th>
		<th>TYPE OF VEHICLE</th>
		<th>COMPANY NAME</th>
		<th>BUNK DETAILS</th>
		<th>PREVIOUS DATE AND TIME</th>
		<th> CURRENT DATE & TIME</th>
		<th> OLD FILLING K.M.</th>
		<th>PRESENT FILLING KM</th>
		<th>TOTAL</th>
		<th>DIESEL QTY</th>
		<th>FIXED KM</th>
		<th>CURREND MILLAGE</th>
		<th>GPS Previous Fuel Filling Date & Time</th>
		<th>GPS Current Fuel Filling Date & Time</th>
		<th>GPS KM</th></tr>';
		for ($j=0; $j <count($excelvalue) ; $j++) { 
			$tavlevalue.='<tr>
			<td>'.$excelvalue[$j]['S.N'].'</td>
			<td>'.$excelvalue[$j]['VEHICLE NO'].'</td>
			<td>'.$excelvalue[$j]['TYPE OF VEHICLE'].'</td>
			<td>'.$excelvalue[$j]['COMPANY NAME'].'</td>
			<td>'.$excelvalue[$j]['BUNK DETAILS'].'</td>
			<td>'.$excelvalue[$j]['PREVIOUS DATE AND TIME'].'</td>
			<td>'.$excelvalue[$j]['CURRENT DATE & TIME'].'</td>
			<td>'.$excelvalue[$j]['OLD FILLING K.M.'].'</td>
			<td>'.$excelvalue[$j]['PRESENT FILLING KM'].'</td>
			<td>'.$excelvalue[$j]['TOTAL'].'</td>
			<td>'.$excelvalue[$j]['DIESEL QTY'].'</td>
			<td>'.$excelvalue[$j]['FIXED KM'].'</td>
			<td>'.$excelvalue[$j]['CURREND MILLAGE'].'</td>
			<td>'.$excelvalue[$j]['previous_filling_date'].'</td>
			<td>'.$excelvalue[$j]['current_filling_date'].'</td>
			<td>'.$excelvalue[$j]['diffrence'].'</td>
			</tr>';
		}
		$tavlevalue.'</table>';
		$excelvalue = base64_encode($tavlevalue);

		// header('Content-type: application/json');
		echo $excelvalue;
		die;
	}else{
		echo 'File Empty Or invalid file';
	}	
}

function getFuelvalue($object,$dtf,$dtt){
	global $ms,$user_id;
	// $q="SELECT a.imei FROM gs_objects a WHERE REPLACE(a.name,' ','') LIKE '%".$object."%' ";
	$q="SELECT a.imei FROM gs_objects a LEFT JOIN gs_user_objects b ON a.imei=b.imei WHERE REPLACE(a.name,' ','') LIKE '%".$object."%' AND b.user_id='".$user_id."'";	
	$r=mysqli_query($ms,$q);
	$row=mysqli_fetch_assoc($r);
	$imei=$row['imei'];
	// $dtf = gmdate("Y-m-d H:i:s", strtotime($dtf.'+1 day'));
	// $dtt = gmdate("Y-m-d H:i:s", strtotime($dtt.'+1 day'));
	$dtf=convUserUTCTimezone(date("Y-m-d H:i:s", strtotime($dtf)));
	$dtt=convUserUTCTimezone(date("Y-m-d H:i:s", strtotime($dtt)));
	$start='';$end='';$diffrence=0;

	if (validateDate($dtf)==1 && validateDate($dtf)==1) {
		$qg="SELECT MIN(start_time) as `start_time`,MAX(start_time) AS `end_time` FROM fuel_fill_offline where imei='".$imei."' and start_time BETWEEN '".$dtf."' and '".$dtt."'";
		$rg=mysqli_query($ms,$qg);
		if(mysqli_num_rows($rg)>0){
			$rowg=mysqli_fetch_assoc($rg);			
			$start=$rowg['start_time'];
			$end=$rowg['end_time'];		
			if (strtotime($start)==strtotime($end)) {
				$end='';
			}
		}
	}
	$myfile = fopen("vvv.txt", "a");
	fwrite($myfile,$qg);
	fwrite($myfile, "\n");
	fwrite($myfile,$start);
	fwrite($myfile, "\n");
	fwrite($myfile,$end);
	fwrite($myfile, "\n");
	fclose($myfile);
	// else{
	// 	$start='2020-12-23 18:29:59';
	// 	$end='2021-01-06 18:29:59';
	// }
		
	if($start==''){	
		$data = getRoute($imei, $dtf, $dtt, '1', true,true);
		$diffrence=$data['route_length'];			
	}else if($end==''){
		$data = getRoute($imei, $start, $dtt, '1', true,true);
		$diffrence=$data['route_length'];
	}else{
		$data = getRoute($imei, $start, $end, '1', true,true);
		$diffrence=$data['route_length'];
	}
	$result=array('start'=>$start,'end'=>$end,'diffrence'=>$diffrence);

	return $result;
}

function validateDate($date, $format = 'Y-m-d H:i:s')
{
    $d = DateTime::createFromFormat($format, $date);
    // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
    return $d && $d->format($format) === $date;
}

function getFuelvalue_remove($object,$dtf,$dtt){
	global $ms,$user_id;
	// $q="SELECT a.imei FROM gs_objects a WHERE REPLACE(a.name,' ','') LIKE '%".$object."%' ";
	$q="SELECT a.imei FROM gs_objects a LEFT JOIN gs_user_objects b ON a.imei=b.imei WHERE REPLACE(a.name,' ','') LIKE '%".$object."%' AND b.user_id='".$user_id."'";	
	$r=mysqli_query($ms,$q);
	$row=mysqli_fetch_assoc($r);
	$imei=$row['imei'];
	// $dtf = gmdate("Y-m-d H:i:s", strtotime($dtf.'+1 day'));
	// $dtt = gmdate("Y-m-d H:i:s", strtotime($dtt.'+1 day'));
	$dtf=convUserUTCTimezone(date("Y-m-d H:i:s", strtotime($dtf)));
	$dtt=convUserUTCTimezone(date("Y-m-d H:i:s", strtotime($dtt)));

	// $myfile = fopen("vvv.txt", "a");
	// fwrite($myfile,$imei );
	// fwrite($myfile, "\n");
	// fwrite($myfile,$dtf );
	// fwrite($myfile, "\n");
	// fwrite($myfile,$dtt );
	// fwrite($myfile, "\n");
	// fclose($myfile);
	$accuracy = getObjectAccuracy($imei);
	$fuel_sensors = getSensorFromType($imei, 'fuel');
	$retndata = getIFSDataFuel($imei, $accuracy, $fuel_sensors, $dtf, $dtt);
	$route=array();
	if (count($retndata)> 0) // || ($fuel_sensors == false))
	{
		$route=$retndata[1];		
	}
	
	$ff = getRouteFuelFillingsnew($route, $accuracy, $fuel_sensors);
	

	$start='';$end='';$diffrence=0;
	if(count($ff['fillings'])>0){
		if(count($ff['fillings']['Fuel 1'])!=0){
			$start=$ff['fillings']['Fuel 1'][0]['start'];
			// $start_mil=$ff['fillings']['Fuel 1'][0]['mileage_start'];
			// $data = getRoute($imei, $start, $end, 1, false);		
		}
		if(count($ff['fillings']['Fuel 1'])>1){
			$end=$ff['fillings']['Fuel 1'][count($ff['fillings']['Fuel 1'])-1]['start'];
			if($end!='' and (strtotime($end)-strtotime($start))<1000){
				$end='';
			}
			// $end_mil=$ff['fillings']['Fuel 1'][count($ff['fillings']['Fuel 1'])-1]['mileage_start'];
		}
		
		if($start==''){	
			$data = getRoute($imei, $dtf, $dtt, '1', true,true);
			$diffrence=$data['route_length'];			
		}else if($end==''){
			$data = getRoute($imei, $start, $dtt, '1', true,true);
			$diffrence=$data['route_length'];
		}else{
			$data = getRoute($imei, $start, $end, '1', true,true);
			$diffrence=$data['route_length'];
		}
		// $diffrence=$diffrence['maxvalue']-$diffrence['minvalue'];		
	}
	$result=array('start'=>$start,'end'=>$end,'diffrence'=>$diffrence);

	return $result;
}
?>