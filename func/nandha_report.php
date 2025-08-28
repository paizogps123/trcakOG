<?php 
include ('../init.php');
include ('fn_common.php');

$q="SELECT b.* FROM c_report_privilege a LEFT JOIN gs_users b on a.user_id!=b.id";
echo $q;
$r=mysqli_query($ms,$q);
while($row=mysqli_fetch_array($r)){
	echo $row['manager_id'];
	$qi="DELETE FROM c_report_privilege where `user_id`='". $row['id']."'";
	echo $qi;
	echo '</br>';
	// mysqli_query($ms,$qi);
}
?>