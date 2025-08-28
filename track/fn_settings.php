<? 

	include ('../init.php');
	
	global $ms, $gsValues;

	if(@$_POST['cmd'] == 'load_server_values')
	{	
		$custom_maps = array();
		
		$q = "SELECT * FROM `gs_maps` ORDER BY `name` ASC";
		$r = mysqli_query($ms, $q);
		
		while($row=mysqli_fetch_array($r))
		{
			$map_id = $row['map_id'];
			$name = $row['name'];
			$active = $row['active'];
			$type = $row['type'];
			$url = $row['url'];
			$layers = $row['layers'];
			
			$layer_id = 'map_'.strtolower($name).'_'.$map_id;
			
			if ($active == 'true')
			{
				$custom_maps[] = array('layer_id' => $layer_id,'name' => $name, 'active' => $active, 'type' => $type, 'url' => $url, 'layers' => $layers);	
			}
			
		}
		
		$result = array('url_root' => $gsValues['URL_ROOT'],
				'map_custom' => $custom_maps,
				'map_osm' => $gsValues['MAP_OSM'],
				'map_bing' => $gsValues['MAP_BING'],
				'map_google' => $gsValues['MAP_GOOGLE'],
				'map_google_traffic' => $gsValues['MAP_GOOGLE_TRAFFIC'],
				'map_mapbox' => $gsValues['MAP_MAPBOX'],
				'map_yandex' => $gsValues['MAP_YANDEX'],
				'map_bing_key' => $gsValues['MAP_BING_KEY'],
				'map_mapbox_key' => $gsValues['MAP_MAPBOX_KEY'],
				'map_layer' => $gsValues['MAP_LAYER'],
				'map_zoom' => $gsValues['MAP_ZOOM'],
				'map_lat' => $gsValues['MAP_LAT'],
				'map_lng' => $gsValues['MAP_LNG'],
				'notify_obj_expire' => $gsValues['NOTIFY_OBJ_EXPIRE'],
				'notify_obj_expire_period' => $gsValues['NOTIFY_OBJ_EXPIRE_PERIOD'],
				'notify_account_expire' => $gsValues['NOTIFY_ACCOUNT_EXPIRE'],
				'notify_account_expire_period' => $gsValues['NOTIFY_ACCOUNT_EXPIRE_PERIOD']
				);
		
		echo json_encode($result);
		die;
	}
	
	
	
	if(@$_POST['cmd'] == 'live_track_v')
	{	
				
		$key=$_POST['imei'];
		$key=hexdec($key);
		$key=substr($key,0,(count($key)-3));
			
		
		$q0 = "select * from gs_objects where imei=( SELECT allocated_imei from ah_booking  where booking_id='".$key."' and booking_status not in('Finished','Deleted','Canceled','Waiting') LIMIT 1)";  
		//$q0 = "select * from gs_objects where imei=(SELECT allocated_imei from ah_booking  where booking_id='".$key."'  LIMIT 1)";
    	$r0 = mysqli_query($ms,$q0);
    		$result = array('dt_tracker' => "",
				'dt_server' => "",
				'lat' => "",
				'lng' => "",
				'angle' => "",
				'altitude' => "",
				'speed' => "",
				'type'=>"e",
    			'acc'=>"0",
    			'name'=>"0",
				);
				
		if ($row0 = mysqli_fetch_array($r0))
    	{
    		$result = array('dt_tracker' => $row0["dt_tracker"],
				'dt_server' =>  $row0["dt_server"],
				'lat' =>  $row0["lat"],
				'lng' =>  $row0["lng"],
				'angle' =>  $row0["angle"],
				'altitude' =>  $row0["altitude"],
				'speed' =>  $row0["speed"],
				'type'=>"s",
    			'acc'=>"1",
    			'name'=>$row0["name"]
				);
    	}

		echo json_encode($result);
		die;
	}
	
		

?>