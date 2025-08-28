<?php
include ('../init.php');
$q="ALTER TABLE `gs_users`	ADD COLUMN `company_start_time` TIME NULL DEFAULT NULL AFTER `trip_reg_key`";
mysqli_query($ms,$q);
?>