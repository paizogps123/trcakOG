<?
	set_time_limit(0);
	
	session_start();
	include ('../init.php');
	include ('fn_common.php');
	include ('fn_cleanup.php');
	include ('../tools/email.php');
	include ('../tools/sms.php');
	checkUserSession();
	checkUserCPanelPrivileges();
	
	loadLanguage($_SESSION["language"], $_SESSION["units"]);

if ($_SESSION["privileges"] == 'subuser')
	{
		$user_id = $_SESSION["manager_id"];
	}
	else
	{
		$user_id = $_SESSION["user_id"];
	}

if(@$_POST['cmd'] == 'save_supplier_data'){
	$q="INSERT INTO `ci_supplier`(`category`, `name`, `gst_no`, `gst_photo`, `commercial_addr`, `mail`, `phone`, `bank_name`, `branch_name`, `account_no`, `ifsc`, `contact_name`, `contact_mobile`, `contact_mail`, `contact_desi`, `contact_description`,`user_id`) VALUES ('".$_POST['s_category']."','".$_POST['s_name']."','".$_POST['s_gst']."','".$_POST['s_docp']."','".$_POST['s_cadd']."','".$_POST['s_cemail']."','".$_POST['s_c_phone']."','".$_POST['s_bname']."','".$_POST['s_brname']."','".$_POST['s_accno']."','".$_POST['s_ifsc']."','".$_POST['s_alname']."','".$_POST['s_alphone']."','".$_POST['s_alemail']."','".$_POST['s_aldesi']."','".$_POST['s_aldesc']."','".$user_id."') ";
	$r=mysqli_query($ms,$q);
	if($r==true){
		echo 'OK';
	}else{
		return ;
	}

}

if(@$_GET['cmd'] == 'load_unused_object_list')
{ 
	$page = $_GET['page']; // get the requested page
	$limit = $_GET['rows']; // get how many rows we want to have into the grid
	$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
	$sord = $_GET['sord']; // get the direction
	$search = strtoupper(@$_GET['s']); // get search
	
	if(!$sidx) $sidx = 1;
	
	$q = "SELECT * FROM `ci_supplier` ";
	
	$r = mysqli_query($ms, $q);
	$count = mysqli_num_rows($r);
	
	if ($count > 0) {
		$total_pages = ceil($count/$limit);
	} else {
		$total_pages = 1;
	}
	
	if ($page > $total_pages) $page=$total_pages;
	$start = $limit*$page - $limit; // do not put $limit*($page - 1)
	
	$q .= " ORDER BY $sidx $sord LIMIT $start, $limit";
	$r = mysqli_query($ms, $q);
	
	$response = new stdClass();
	$response->page = $page;
	$response->total = $total_pages;
	$response->records = $count;
	
	if ($r)
	{
		$i=0;
		while($row = mysqli_fetch_array($r))
		{
			$sid = $row['sup_id'];
			
			$last_connection = $row['created_at'];
			
			// set modify buttons
			$modify = '<a href="#" onclick="supplierView(\''.$sid.'\');" title="'.$la['VIEW_DETAIL'].'"><img src="theme/images/action4.svg" /></a>';
			$modify .= '<a href="#" onclick="supplierEdit(\''.$sid.'\');" title="'.$la['EDIT'].'"><img src="theme/images/edit.svg" /></a>';
			$modify.= '<a href="#" onclick="supplierDelte(\''.$sid.'\');" title="'.$la['DELETE'].'"><img src="theme/images/remove3.svg" /></a>';
			
			// set row
			$response->rows[$i]['id']=$sid;
			$response->rows[$i]['cell']=array($sid,$row['name'],$row['category'],$row['gst_no'],$row['commercial_addr'],$row['mail'],$row['phone'],$row['bank_name'],$modify);
			$i++;
		}
	}

	header('Content-type: application/json');
	echo json_encode($response);
	die;
}

if(@$_GET['cmd'] == 'load_simcard_list')
{ 
	$page = $_GET['page']; // get the requested page
	$limit = $_GET['rows']; // get how many rows we want to have into the grid
	$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
	$sord = $_GET['sord']; // get the direction
	$search = strtoupper(@$_GET['s']); // get search
	
	if(!$sidx) $sidx = 1;
	
	$q = "SELECT a.id,a.sim_number,a.mob_number,a.sim_imsi,a.sim_provider,a.status,b.imei FROM gs_simcard_details a  LEFT JOIN gs_objects b ON a.id=b.sim_id";	
	if($_SESSION["cpanel_privileges"] == 'admin'){
		$q.=" WHERE a.status!='D'";
	}else if($_SESSION["cpanel_privileges"] == 'manager'){
		$q.=" WHERE (a.status!='D' and a.status!='R')";
	}else{
		$q.=" WHERE a.status!=''";
	}
	if(isset($_GET['provider']) && $_GET['provider']!=''){
		$q.=" AND a.sim_provider='".$_GET['provider']."'";
	}
	if(isset($_GET['simstatus']) && $_GET['simstatus']!='') {
		$q.=" AND a.status='".$_GET['simstatus']."'";
	}
	if(isset($_GET['searchsim']) && $_GET['searchsim']!='') {
		$q.=" AND (UPPER(a.mob_number) LIKE '%".$_GET['searchsim']."%' OR UPPER(a.sim_number) LIKE '%".$_GET['searchsim']."%')";
	}
	$r = mysqli_query($ms, $q);
	$count = mysqli_num_rows($r);
	
	if ($count > 0) {
		$total_pages = ceil($count/$limit);
	} else {
		$total_pages = 1;
	}
	
	if ($page > $total_pages) $page=$total_pages;
	$start = $limit*$page - $limit; // do not put $limit*($page - 1)	
	$q .= " ORDER BY $sidx $sord LIMIT $start, $limit";
	$r = mysqli_query($ms, $q);
	
	$response = new stdClass();
	$response->page = $page;
	$response->total = $total_pages;
	$response->records = $count;
	
	if ($r)
	{
		$i=0;
		while($row = mysqli_fetch_array($r))
		{
			$id=$row['id'];
			$imei=$row['imei'];
			$mobnumber = $row['mob_number'];
			$simnumber = $row['sim_number'];
			$simimsi = $row['sim_imsi'];
			$simprovider = $row['sim_provider'];
			if($row['status']=='A'){
				$status = '<img src="theme/images/tick-green.svg">';
			}else if($row['status']=='R'){
				$status = '<img src="theme/images/remove-red.svg">';
			}else if($row['status']=='D'){
				$status = '<img src="theme/images/remove3.svg">';
			}else{
				$status ='No';
			}
			
			// set modify buttons
			// $modify = '<a href="#" onclick="supplierView(\''.$sid.'\');" title="'.$la['VIEW_DETAIL'].'"><img src="theme/images/action4.svg" /></a>';
			$modify='';
			if (($_SESSION["cpanel_privileges"] == 'super_admin' || $_SESSION["cpanel_privileges"] == 'admin'))
			{
				$modify.= '<a href="#" onclick="editsimcarddetails(\''.$id.'\');" title="'.$la['EDIT'].'"><img src="theme/images/edit.svg" /></a>';	
			}		
			$modify.= '<a href="#" onclick="removesimcarddetails(\''.$id.'\');" title="'.$la['REMOVE'].'"><img src="theme/images/remove-red.svg" /></a>';
			if (($_SESSION["cpanel_privileges"] == 'super_admin' || $_SESSION["cpanel_privileges"] == 'admin'))
			{
				$modify.= '<a href="#" onclick="activesimcarddetails(\''.$id.'\');" title="'.$la['ACTIVE'].'"><img src="theme/images/tick-green.svg" /></a>';
				$modify.= '<a href="#" onclick="deletesimcarddetails(\''.$id.'\');" title="'.$la['DELETE'].'"><img src="theme/images/remove3.svg" /></a>';
			}
			
			// set row
			$response->rows[$i]['id']=$id;
			$response->rows[$i]['cell']=array($id,$mobnumber,$simnumber,$simimsi,$simprovider,$status,$imei,$modify);
			$i++;
		}
	}

	header('Content-type: application/json');
	echo json_encode($response);
	die;
}

if(@$_POST['cmd']=='load_supplier_data'){
	$supplierdata=array();
	$q1="SELECT * FROM ci_supplier";
	$r1=mysqli_query($ms,$q1);
	while($row1=mysqli_fetch_array($r1)){
		$supplierdata[]=$row1;
	}
	header('Content-type: application/json');
	echo json_encode($supplierdata);
	die;
}

if(@$_POST['cmd']=='load_supplier_id_data'){
	$supplieriddate=array();
	$q="SELECT * FROM ci_supplier WHERE sup_id='".$_POST['sup_id']."'";
	$r=mysqli_query($ms,$q);
	while($row=mysqli_fetch_array($r)){
		$supplieriddate[]=$row;
	}
	header('Content-type: application/json');
	echo json_encode($supplieriddate);
	die;
}

if(@$_POST['cmd']=='delete_supplier_id_data'){
	$q="DELETE FROM ci_supplier WHERE sup_id='".$_POST['sup_id']."'";
	$r=mysqli_query($ms,$q);
	if($r==true){
		echo 'OK';
	}else{
		return;
	}
}

if(@$_POST['cmd']=='update_supplier_data'){
	$q="UPDATE ci_supplier SET `category`='".$_POST['se_category']."',`name`='".$_POST['se_name']."',`gst_no`='".$_POST['se_gst']."',`gst_photo`='".$_POST['se_docp']."',`commercial_addr`='".$_POST['se_cadd']."',`mail`='".$_POST['se_cemail']."',`phone`='".$_POST['se_c_phone']."',`bank_name`='".$_POST['se_bname']."',`branch_name`='".$_POST['se_brname']."',`account_no`='".$_POST['se_accno']."',`ifsc`='".$_POST['se_ifsc']."',`contact_name`='".$_POST['se_alname']."',`contact_mobile`='".$_POST['se_alphone']."',`contact_mail`='".$_POST['se_alemail']."',`contact_desi`='".$_POST['se_aldesi']."',`contact_description`='".$_POST['se_aldesc']."' WHERE `sup_id`='".$_POST['se_id']."'";
	$r=mysqli_query($ms,$q);
	if($r==true){
		echo 'ok';
	}else{
		return;
	}
}
// Sim Details

if(@$_POST['cmd']=='load_sim_usage'){
	$result=array();
	$q="SELECT COUNT(a.id) as totalsim,COUNT(b.sim_id) as usedsim FROM gs_simcard_details a LEFT JOIN gs_objects b ON a.id=b.sim_id AND b.sim_id!=0";
	if($_SESSION["cpanel_privileges"] == 'admin'){
		$q.=" WHERE status!='D'";
	}else if($_SESSION["cpanel_privileges"] == 'manager'){
		$q.=" WHERE (status!='D' and status!='R')";
	}else{
		$q.=" WHERE status!=''";
	}
	$q.=" and status='A'";
	$r=mysqli_query($ms,$q);
	if(mysqli_num_rows($r)>0){
		$row=mysqli_fetch_assoc($r);
		$result['totalsim']=$row['totalsim'];
		$result['usedsim']=$row['usedsim'];
	}
	header('Content-type: application/json');
	echo json_encode($result);
	die;
}
// Edit Single SIM Card
if(@$_POST['cmd']=='edit_simcard_list'){
	if (($_SESSION["cpanel_privileges"] != 'super_admin') && ($_SESSION["cpanel_privileges"] != 'manager') && ($_SESSION["cpanel_privileges"] != 'admin'))
	{
		echo '';
		die;
	}
	$q="SELECT `id`,`mob_number`,`sim_number`,`sim_imsi`,`sim_provider` FROM gs_simcard_details where id='".$_POST['id']."'";
	$r=mysqli_query($ms,$q);
	if(mysqli_num_rows($r)==0){
		echo 'NO';
		die;
	}
	$row=mysqli_fetch_assoc($r);
	header('Content-type: application/json');
	echo json_encode($row);
	die;

}

if(@$_POST['cmd']=='remove_simcard_list'){
	if($_SESSION["cpanel_privileges"] == 'admin' || $_SESSION["cpanel_privileges"] == 'super_admin' || $_SESSION["cpanel_privileges"] == 'manager'){
		$sq="SELECT * FROM gs_objects where sim_id='".$_POST['simid']."'";
		$sr=mysqli_query($ms,$sq);
		$rowR=mysqli_fetch_assoc($sr);
		if(mysqli_num_rows($sr)>0){
			$oldsim=$rowR['sim_number'].','.$rowR['old_sim_number'];
			$qu="UPDATE gs_objects SET sim_id='0',old_sim_number='".$oldsim."',sim_number='' where imei='".$rowR['imei']."'";
			
			mysqli_query($ms,$qu);

			$q="UPDATE gs_simcard_details SET status='A',action_by='".$user_id."' WHERE id='".$_POST['simid']."'";
			mysqli_query($ms,$q);
			echo 'OK';
		}
		// else{
		// 	$rows=mysqli_fetch_assoc($sr);
		// 	echo $la['SIM_USING_THE_DEVICE'].$rows['imei'];
		// }
	}else{
		echo $la['NO_PERMISSION'];
	}	
	die;

}

if(@$_POST['cmd']=='active_simcard_list_select'){
	if($_SESSION["cpanel_privileges"] == 'admin' || $_SESSION["cpanel_privileges"] == 'super_admin'){
		$simid = $_POST['simid'];				
		for ($i = 0; $i < count($simid); ++$i)
		{
			$id = $simid[$i];		
			$q="UPDATE gs_simcard_details SET status='A',action_by='".$user_id."' WHERE id='".$id."'";
			$r = mysqli_query($ms, $q);
		}		
		echo 'OK';
		die;
	}else{
		echo 'NO_PERMISSION';
		die;
	}
}


if(@$_POST['cmd']=='active_simcard_list'){
	if($_SESSION["cpanel_privileges"] == 'admin' || $_SESSION["cpanel_privileges"] == 'super_admin'){
		$q="UPDATE gs_simcard_details SET status='A',action_by='".$user_id."',update_date='".gmdate("Y-m-d H:i:s")."' WHERE id='".$_POST['simid']."'";
		mysqli_query($ms,$q);
		echo 'OK';
	}else{
		echo 'NO_PERMISSION';
	}	
	die;
}

if(@$_POST['cmd']=='delete_simcard_list'){
	if (($_SESSION["cpanel_privileges"] == 'super_admin' || $_SESSION["cpanel_privileges"] == 'admin'))
	{
		$sq="SELECT * FROM gs_objects where sim_id='".$_POST['simid']."'";
		$sr=mysqli_query($ms,$sq);
		if(mysqli_num_rows($sr)==0){
			$q="UPDATE gs_simcard_details SET status='D',action_by='".$user_id."',update_date='".gmdate("Y-m-d H:i:s")."' WHERE id='".$_POST['simid']."'";
			mysqli_query($ms,$q);
			echo 'OK';
		}else{
			$rows=mysqli_fetch_assoc($sr);
			echo $la['SIM_USING_THE_DEVICE'].$rows['imei'];
		}
	}else{
		echo 'NO_PERMISSION';
	}	
	die;
}

if(@$_POST['cmd']=='active_simcard_list_select'){
	if($_SESSION["cpanel_privileges"] == 'admin' || $_SESSION["cpanel_privileges"] == 'super_admin'){
		$simid = $_POST['simid'];				
		for ($i = 0; $i < count($simid); ++$i)
		{
			$id = $simid[$i];		
			$q="UPDATE gs_simcard_details SET status='A',action_by='".$user_id."',update_date='".gmdate("Y-m-d H:i:s")."' WHERE id='".$id."'";
			$r = mysqli_query($ms, $q);
		}		
		echo 'OK';
		die;
	}else{
		echo 'NO_PERMISSION';
		die;
	}
}

if(@$_POST['cmd']=='remove_simcard_list_select'){
	$simid = $_POST['simid'];
	$simuse=0;			
	for ($i = 0; $i < count($simid); ++$i)
	{
		$id = $simid[$i];
		$sq="SELECT * FROM gs_objects where sim_id='".$id."'";
		$sr=mysqli_query($ms,$sq);
		if(mysqli_num_rows($sr)==0){		
			$q="UPDATE gs_simcard_details SET status='R',action_by='".$user_id."',update_date='".gmdate("Y-m-d H:i:s")."' WHERE id='".$id."'";
			$r = mysqli_query($ms, $q);
		}else{
			$simuse+=1;
		}
	}
	if($simuse>0){
		echo $simuse.$la['SIM_USING_THE_DEVICE_COUNT'];
	}else{
		echo 'OK';
	}
	die;
}

if(@$_POST['cmd']=='delete_simcard_list_select'){
	
	if($_SESSION["cpanel_privileges"] == 'admin' || $_SESSION["cpanel_privileges"] == 'super_admin'){
		$simid = $_POST['simid'];
		$simuse=0;			
		for ($i = 0; $i < count($simid); ++$i)
		{
			$id = $simid[$i];
			$sq="SELECT * FROM gs_objects where sim_id='".$id."'";
			$sr=mysqli_query($ms,$sq);
			if(mysqli_num_rows($sr)==0){		
				$q="UPDATE gs_simcard_details SET status='D',action_by='".$user_id."',update_date='".gmdate("Y-m-d H:i:s")."' WHERE id='".$id."'";
				$r = mysqli_query($ms, $q);
			}else{
				$simuse+=1;
			}
		}		
		if($simuse>0){
			echo $simuse.$la['SIM_USING_THE_DEVICE_COUNT'];
		}else{
			echo 'OK';
		}
		die;
	}else{
		echo $la['NO_PERMISSION'];
		die;
	}	
}

if(@$_POST['cmd']=='suspend_simcard_list_select'){
	
	if($_SESSION["cpanel_privileges"] == 'admin' || $_SESSION["cpanel_privileges"] == 'super_admin'){
		$simid = $_POST['simid'];
		$simuse=0;			
		for ($i = 0; $i < count($simid); ++$i)
		{
			$id = $simid[$i];
			$sq="SELECT * FROM gs_objects where sim_id='".$id."'";
			$sr=mysqli_query($ms,$sq);
			if(mysqli_num_rows($sr)==0){		
				$q="UPDATE gs_simcard_details SET status='S',action_by='".$user_id."',update_date='".gmdate("Y-m-d H:i:s")."' WHERE id='".$id."'";
				$r = mysqli_query($ms, $q);
			}else{
				$simuse+=1;
			}
		}		
		if($simuse>0){
			echo $simuse.$la['SIM_USING_THE_DEVICE_COUNT'];
		}else{
			echo 'OK';
		}
		die;
	}else{
		echo $la['NO_PERMISSION'];
		die;
	}	
}

if(@$_POST['cmd']=='deactivate_simcard_list_select'){
	
	if($_SESSION["cpanel_privileges"] == 'admin' || $_SESSION["cpanel_privileges"] == 'super_admin'){
		$simid = $_POST['simid'];
		$simuse=0;			
		for ($i = 0; $i < count($simid); ++$i)
		{
			$id = $simid[$i];
			$sq="SELECT * FROM gs_objects where sim_id='".$id."'";
			$sr=mysqli_query($ms,$sq);
			if(mysqli_num_rows($sr)==0){		
				$q="UPDATE gs_simcard_details SET status='DI',action_by='".$user_id."',update_date='".gmdate("Y-m-d H:i:s")."' WHERE id='".$id."'";
				$r = mysqli_query($ms, $q);
			}else{
				$simuse+=1;
			}
		}		
		if($simuse>0){
			echo $simuse.$la['SIM_USING_THE_DEVICE_COUNT'];
		}else{
			echo 'OK';
		}
		die;
	}else{
		echo $la['NO_PERMISSION'];
		die;
	}	
}

if(@$_POST['cmd']=='insert_simcard_list'){
	if(isset($_POST['value'])){
		$alreadyuser=array();
		$simdetails=$_POST['value'];
		for($i=0;$i<count($simdetails);$i++){
			$q="SELECT * FROM gs_simcard_details where mob_number='".$simdetails[$i]['mobno']."'";
			$r=mysqli_query($ms,$q);
			if(mysqli_num_rows($r)>0){
				array_push($alreadyuser,$simdetails[$i]['mobno']);
			}else{
				$q="INSERT INTO gs_simcard_details (`mob_number`,`sim_number`,`sim_imsi`,`sim_provider`,`status`,`update_date`,`action_by`) VALUES ('".$simdetails[$i]['mobno']."','".$simdetails[$i]['simno']."','".$simdetails[$i]['simimsi']."','".$simdetails[$i]['simprovider']."','A','".gmdate("Y-m-d H:i:s")."','".$user_id."')";
				$r=mysqli_query($ms,$q);
			}
		}
		if(count($alreadyuser)>0){
			header('Content-type: application/json');
			echo json_encode($alreadyuser);
		}else{
			echo 'OK';
		}
	}else{
		echo 'Data Empty';
	}
}

if(@$_POST['cmd']=='update_sim_details'){
	if (($_SESSION["cpanel_privileges"] == 'super_admin' || $_SESSION["cpanel_privileges"] == 'admin'))
	{
		$alreadyuser=array();
		$simdetails=$_POST['simdata'];
		for($i=0;$i<count($simdetails);$i++){
			$q="UPDATE gs_simcard_details SET `mob_number`='".$simdetails[$i]['mobno']."',`sim_number`='".$simdetails[$i]['simno']."',`sim_imsi`='".$simdetails[$i]['simimsi']."',`sim_provider`='".$simdetails[$i]['simprovider']."',action_by='".$user_id."',update_date='".gmdate("Y-m-d H:i:s")."' WHERE id='".$simdetails[$i]['sid']."'";
			mysqli_query($ms,$q);
		}
		echo 'OK';
		die;
	}else{
		echo '';
		die;
	}

}

if(@$_POST['cmd']=='download_sim_details'){
	$result = 	'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
					<html xmlns="http://www.w3.org/1999/xhtml">
					<head>
					<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
					<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">
					<title></title>';

	$result.="<body>";
	$result.="<table>
				<tr>
					<th>Mobile Number</th>
					<th>Sim Number</th>
					<th>Sim IMSI</th>
					<th>Sim Provider</th>";
					if($_SESSION["cpanel_privileges"] == 'admin' || $_SESSION["cpanel_privileges"] == 'super_admin'){
						$result.="<th>Status</th>";}
					$result.="<th>Device ID</th><th>Board IMEI</th>
					<th>Device IMEI</th>					
					<th>Purchased by</th>					
				</tr>";
	$q = "SELECT a.id,a.sim_number,a.mob_number,a.sim_imsi,a.sim_provider,a.status,b.imei,b.name,b.board_id,a.purchase_by FROM gs_simcard_details a  LEFT JOIN gs_objects b ON a.id=b.sim_id";
	if($_SESSION["cpanel_privileges"] == 'admin'){
		$q.=" WHERE a.status!='D'";
	}else if($_SESSION["cpanel_privileges"] == 'manager'){
		$q.=" WHERE (a.status!='D' and a.status!='R')";
	}else{
		$q.=" WHERE a.status!=''";
	}
	$r=mysqli_query($ms,$q);
	while($row=mysqli_fetch_assoc($r)){
		$result.="<tr>
					<td>".$row['mob_number']."</td>
					<td>".$row['sim_number']."/U</td>
					<td>".$row['sim_imsi']."</td>
					<td>".$row['sim_provider']."</td>";
			if($_SESSION["cpanel_privileges"] == 'admin' || $_SESSION["cpanel_privileges"] == 'super_admin'){
						$result.="<td>".$row['status']."</td>";}
			$result.="<td>".$row['imei']."</td>
					  <td>".$row['board_id']."</td>
					  <td>".$row['name']."</td>
					  <td>".$row['purchase_by']."</td>
				  	</tr>";
	}
	$result .= '</table></head><body>';
	$report = base64_encode($result);
	echo $report;
}

?>