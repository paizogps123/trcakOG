<?
	session_start();
	include ('../init.php');
	include ('fn_common.php');
	checkUserSession();

	if(@$_GET['file'] == 'logo_png')
	{
		if ($_SESSION["cpanel_privileges"] != 'super_admin')
		{
			die;
		}
		
		$postdata = file_get_contents("php://input");
		
		if (isset($postdata))
		{
			$imageData = $postdata;
			$filteredData = substr($imageData, strpos($imageData, ",")+1);
			
			$unencodedData=base64_decode($filteredData);
			
			$file_path = $gsValues['PATH_ROOT'].'img/logo.png';
			
			$fp = fopen( $file_path, 'wb' );
			fwrite( $fp, $unencodedData);
			fclose( $fp );
			
			$file_url = $gsValues['URL_ROOT'].'/img/logo.png';
			echo $file_url;
		}
	}
	
	if(@$_GET['file'] == 'logo_svg')
	{
		if ($_SESSION["cpanel_privileges"] != 'super_admin')
		{
			die;
		}
		
		$postdata = file_get_contents("php://input");
		
		if (isset($postdata))
		{
			$imageData = $postdata;
			$filteredData = substr($imageData, strpos($imageData, ",")+1);
			
			$unencodedData=base64_decode($filteredData);
			
			$file_path = $gsValues['PATH_ROOT'].'img/logo.svg';
			
			$fp = fopen( $file_path, 'wb' );
			fwrite( $fp, $unencodedData);
			fclose( $fp );
			
			$file_url = $gsValues['URL_ROOT'].'/img/logo.svg';
			echo $file_url;
		}   
	}
	
	if(@$_GET['file'] == 'driver_photo')
	{
		$postdata = file_get_contents("php://input");
		
		if (isset($postdata))
		{
			$imageData = $postdata;
			$filteredData = substr($imageData, strpos($imageData, ",")+1);
			
			$unencodedData=base64_decode($filteredData);
			
			$file_path = $gsValues['PATH_ROOT'].'data/user/drivers/'.$_SESSION["user_id"].'_temp.png';
			$file_url = $gsValues['URL_ROOT'].'/data/user/drivers/'.$_SESSION["user_id"].'_temp.png';
			
			$fp = fopen( $file_path, 'wb' );
			fwrite( $fp, $unencodedData);
			fclose( $fp );
			
			echo $file_url;
		}
	}
	
	if(@$_GET['file'] == 'object_icon_png')
	{
		$postdata = file_get_contents("php://input");
		
		if (isset($postdata))
		{
			$imageData = $postdata;
			$filteredData = substr($imageData, strpos($imageData, ",")+1);
			
			$unencodedData=base64_decode($filteredData);
			
			$file_path = $gsValues['PATH_ROOT'].'data/user/objects/'.$_SESSION["user_id"].'_'.md5(gmdate("Y-m-d H:i:s")).'.png';
			
			$fp = fopen( $file_path, 'wb' );
			fwrite( $fp, $unencodedData);
			fclose( $fp );
		}
	}
	
	if(@$_GET['file'] == 'object_icon_svg')
	{
		$postdata = file_get_contents("php://input");
		
		if (isset($postdata))
		{
			$imageData = $postdata;
			$filteredData = substr($imageData, strpos($imageData, ",")+1);
			
			$unencodedData=base64_decode($filteredData);
			
			$file_path = $gsValues['PATH_ROOT'].'data/user/objects/'.$_SESSION["user_id"].'_'.md5(gmdate("Y-m-d H:i:s")).'.svg';
			
			$fp = fopen( $file_path, 'wb' );
			fwrite( $fp, $unencodedData);
			fclose( $fp );
		}
	}
	
	if(@$_GET['file'] == 'places_icon_png')
	{
		$postdata = file_get_contents("php://input");
		
		if (isset($postdata))
		{
			$imageData = $postdata;
			$filteredData = substr($imageData, strpos($imageData, ",")+1);
			
			$unencodedData=base64_decode($filteredData);
			
			$file_path = $gsValues['PATH_ROOT'].'data/user/places/'.$_SESSION["user_id"].'_'.md5(gmdate("Y-m-d H:i:s")).'.png';
			
			$fp = fopen( $file_path, 'wb' );
			fwrite( $fp, $unencodedData);
			fclose( $fp );
		}
	}
	
	if(@$_GET['file'] == 'places_icon_svg')
	{
		$postdata = file_get_contents("php://input");
		
		if (isset($postdata))
		{
			$imageData = $postdata;
			$filteredData = substr($imageData, strpos($imageData, ",")+1);
			
			$unencodedData=base64_decode($filteredData);
			
			$file_path = $gsValues['PATH_ROOT'].'data/user/places/'.$_SESSION["user_id"].'_'.md5(gmdate("Y-m-d H:i:s")).'.svg';
			
			$fp = fopen( $file_path, 'wb' );
			fwrite( $fp, $unencodedData);
			fclose( $fp );
		}
	}
	if(@$_GET['file'] == 'adduser_gst_png')
	{
		$postdata = file_get_contents("php://input");
		
		if (isset($postdata))
		{
			$imageData = $postdata;
			$filteredData = substr($imageData, strpos($imageData, ",")+1);
			
			$unencodedData=base64_decode($filteredData);
			
			$file_path = $gsValues['PATH_ROOT'].'data/user/addusergst/'.md5(gmdate("Y-m-d H:i:s")).'_supdoc.png';
			$file_url = $gsValues['URL_ROOT'].'/data/user/addusergst/'.md5(gmdate("Y-m-d H:i:s")).'_supdoc.png';
			$file_name = md5(gmdate("Y-m-d H:i:s")).'_supdoc.png';
			
			$fp = fopen( $file_path, 'wb' );
			fwrite( $fp, $unencodedData);
			fclose( $fp );
			
			echo $file_name;
		}
	}
	if(@$_GET['file'] == 'adduser_gst_pdf')
	{
		$postdata = file_get_contents("php://input");
		
		if (isset($postdata))
		{
			$imageData = $postdata;
			$filteredData = substr($imageData, strpos($imageData, ",")+1);
			
			$unencodedData=base64_decode($filteredData);
			
			$file_path = $gsValues['PATH_ROOT'].'data/user/addusergst/'.md5(gmdate("Y-m-d H:i:s")).'_supdoc.pdf';
			$file_url = $gsValues['URL_ROOT'].'/data/user/addusergst/'.md5(gmdate("Y-m-d H:i:s")).'_supdoc.pdf';
			$file_name = md5(gmdate("Y-m-d H:i:s")).'_supdoc.pdf';
			
			$fp = fopen( $file_path, 'wb' );
			fwrite( $fp, $unencodedData);
			fclose( $fp );
			
			echo $file_name;
		}
	}
	

	if(@$_GET['file'] == 'adduser_gst_doc')
	{
		$postdata = file_get_contents("php://input");
		
		if (isset($postdata))
		{
			$imageData = $postdata;
			$filteredData = substr($imageData, strpos($imageData, ",")+1);
			
			$unencodedData=base64_decode($filteredData);
			
			$file_path = $gsValues['PATH_ROOT'].'data/user/addusergst/'.md5(gmdate("Y-m-d H:i:s")).'_supdoc.doc';
			$file_url = $gsValues['URL_ROOT'].'/data/user/addusergst/'.md5(gmdate("Y-m-d H:i:s")).'_supdoc.doc';
			$file_name = md5(gmdate("Y-m-d H:i:s")).'_supdoc.pdf';
			
			$fp = fopen( $file_path, 'wb' );
			fwrite( $fp, $unencodedData);
			fclose( $fp );
			
			echo $file_name;
		}
	}
	if(@$_GET['file'] == 'supplier_png')
	{
		$postdata = file_get_contents("php://input");
		
		if (isset($postdata))
		{
			$imageData = $postdata;
			$filteredData = substr($imageData, strpos($imageData, ",")+1);
			
			$unencodedData=base64_decode($filteredData);
			
			$file_path = $gsValues['PATH_ROOT'].'data/user/supplier/'.md5(gmdate("Y-m-d H:i:s")).'_supdoc.png';
			$file_url = $gsValues['URL_ROOT'].'/data/user/supplier/'.md5(gmdate("Y-m-d H:i:s")).'_supdoc.png';
			$file_name = md5(gmdate("Y-m-d H:i:s")).'_supdoc.png';
			
			$fp = fopen( $file_path, 'wb' );
			fwrite( $fp, $unencodedData);
			fclose( $fp );
			
			echo $file_name;
		}
	}
	if(@$_GET['file'] == 'supplier_pdf')
	{
		$postdata = file_get_contents("php://input");
		
		if (isset($postdata))
		{
			$imageData = $postdata;
			$filteredData = substr($imageData, strpos($imageData, ",")+1);
			
			$unencodedData=base64_decode($filteredData);
			
			$file_path = $gsValues['PATH_ROOT'].'data/user/supplier/'.md5(gmdate("Y-m-d H:i:s")).'_supdoc.pdf';
			$file_url = $gsValues['URL_ROOT'].'/data/user/supplier/'.md5(gmdate("Y-m-d H:i:s")).'_supdoc.pdf';
			$file_name = md5(gmdate("Y-m-d H:i:s")).'_supdoc.pdf';
			
			$fp = fopen( $file_path, 'wb' );
			fwrite( $fp, $unencodedData);
			fclose( $fp );
			
			echo $file_name;
		}
	}
	

	if(@$_GET['file'] == 'supplier_doc')
	{
		$postdata = file_get_contents("php://input");
		
		if (isset($postdata))
		{
			$imageData = $postdata;
			$filteredData = substr($imageData, strpos($imageData, ",")+1);
			
			$unencodedData=base64_decode($filteredData);
			
			$file_path = $gsValues['PATH_ROOT'].'data/user/supplier/'.md5(gmdate("Y-m-d H:i:s")).'_supdoc.doc';
			$file_url = $gsValues['URL_ROOT'].'/data/user/supplier/'.md5(gmdate("Y-m-d H:i:s")).'_supdoc.doc';
			$file_name = md5(gmdate("Y-m-d H:i:s")).'_supdoc.pdf';
			
			$fp = fopen( $file_path, 'wb' );
			fwrite( $fp, $unencodedData);
			fclose( $fp );
			
			echo $file_name;
		}
	}
 ?>