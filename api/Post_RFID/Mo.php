
<?php
 
include ('../../init.php');  
include ('../../func/fn_common.php');
// include ('../../tools/sms.php'); 
//date_default_timezone_set("Asia/Kolkata");

function Get_RFID_Datails($getpost)
{
	global  $ms;
	$userapidetails=checkUserKey($getpost);
	$getpost['user_id']=$userapidetails['user_id'];

	$user_imei=getUserObjectsImei($getpost['user_id']);

	$resultary=array();
	$q="SELECT a.swipe_id,a.dt_swipe,a.lat,a.lng,a.imei,a.rfid,a.lat,a.lng,b.name,a.snr_number from gs_rfid_swipe_data a LEFT JOIN gs_objects b ON a.imei=b.imei where a.posted=0 and b.name!='' and a.imei in (".$user_imei.") ORDER BY a.dt_swipe ASC ";
	$r=mysqli_query($ms,$q);
	$postswipid=array();
	while($rowkey=mysqli_fetch_assoc($r))
	{
		if($getpost['user_id']!=1711){
			unset($rowkey['snr_number']);
		}
		unset($rowkey['imei']);
		$rfid = trim($rowkey["rfid"]);
		$rfid = intval($rfid);
		$rowkey["rfid"] = str_pad($rfid, 10, '0', STR_PAD_LEFT);
		$rowkey['imei']='';
		$rowkey["dt_swipe_gmt"]=$rowkey["dt_swipe"];
		$rowkey["dt_swipe"]= date('Y-m-d H:i:s',strtotime('+ 5 hours 30 minutes',strtotime($rowkey["dt_swipe"])));
		$resultary[]=$rowkey;
		$postswipid[]=$rowkey['swipe_id'];
		unset($resultary['swipe_id']);

	}
	if(count($postswipid)>0){
		$postswipid=implode(',',$postswipid);
		postdataidACK($postswipid);
	}
	// $myfile = fopen("req.txt", "a");
	// fwrite($myfile,"length : ".count(@$resultary).' From Client: '.json_encode(@$_POST)." Time :".gmdate("Y-m-d H:i:s"));
	// fwrite($myfile, "\n");
	// fclose($myfile);	
	// return_result('S','',$resultary);
}

function Get_RFID_DateWise($getpost){
	global  $ms;
	return_result('S','','inprogress');
}

function checkUserKey($getpost){
	global $ms;
	$q="SELECT * FROM api_setting WHERE active='true' and apikey='".$getpost['key']."' or  apikey='".decrypt($getpost['key'])."' LIMIT 1";

	$r=mysqli_query($ms,$q);
	if(mysqli_num_rows($r)>0){
		$row=mysqli_fetch_assoc($r);
		return $row;
	}else{
		return_result('E','Invalid Key Please Contact Admin',$data=array());
	}
}

function getUserObjectsImei($user_id){
	global $ms;
	$q="SELECT privileges FROM gs_users where id='".$user_id."'";
	// echo $q;
    $r=mysqli_query($ms,$q);
    if(mysqli_num_rows($r)>0){
	    $row=mysqli_fetch_assoc($r);
	    $privileges=json_decode($row['privileges'],true);
	    if($privileges['type']=='subuser'){

	        $imeis=$privileges['imei'];
	    }else{
	    	$imeiarray=array();
	        $imeis="select imei from gs_user_objects where user_id='".$user_id."'";
	        $rq=mysqli_query($ms,$imeis);
	        while ($rowq=mysqli_fetch_assoc($rq)) {
	             $imeiarray[]= $rowq['imei']; 	
	        }
	        $imeis=implode(',',$imeiarray);  
	    }
	    return $imeis;

	}else{
		return_result('E','Invalid User',$data=array());
	}
}

function postdataidACK($postidACK){
	global $ms;
	$q="UPDATE gs_rfid_swipe_data SET posted=1,status='1' WHERE swipe_id IN (".$postidACK.")";
	mysqli_query($ms,$q);
}

function return_result($type,$msg,$data=array(),$islogin='n')
{
	$result=array();
	$result['Type'] = $type;
	$result['Mydata'] = $data;
	$result['Message'] = $msg;

	// $myfile = fopen("vvv_data.txt", "a");
    // fwrite($myfile,json_encode($result));
    // fwrite($myfile, "\n");
    // fclose($myfile);
    
	header('Content-type: application/json');echo json_encode($result);
die; 

}

?>
