<?	
	function geocoderGetAddress($lat, $lng)
	{
		global $ms, $gsValues;
		
		$result = '';
		
		if ($gsValues['GEOCODER_CACHE'] == 'true')
		{
			$result = getGeocoderCache($lat, $lng);
		}
		
		if ($result == '')
		{			
			$addresscount=check_google_useage_count();			
			if($addresscount==true){
				usleep(150000);								
				update_google_useage();
				$search = $lat.','.$lng;
				$search = htmlentities(urlencode($search));
				$url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$search.'&oe=utf-8&key=AIzaSyCdaFQ4FD-bm-Nff2JlGkGXN7sVa3ZUYXA';
				$data = @file_get_contents($url);
				$jsondata = json_decode($data,true);
				
				if(is_array($jsondata) && $jsondata['status']=="OK")
				{
					$result = $jsondata['results'][0]['formatted_address'];
				}

				/*
				for ($i=0; $i<count($gsValues['URL_GC']); ++$i)
				{	
					$url = $gsValues['URL_GC'][$i].'?cmd=latlng&lat='.$lat.'&lng='.$lng;
					
					
					$result = @file_get_contents($url);
					$result = json_decode($result);


					if (($result != '') && ($result != '""'))
					{
						break;
					}
				}
				*/
				
				if ($gsValues['GEOCODER_CACHE'] == 'true')
				{
					insertGeocoderCache($lat, $lng, $result);
				}
			}else{
				$search_url = "https://nominatim.openstreetmap.org/search?q=".$lat.','.$lng."&format=json";
				// echo $search_url;

				$httpOptions = [
				    "http" => [
				        "method" => "GET",
				        "header" => "User-Agent: Nominatim-Test"
				    ]
				];

				$streamContext = stream_context_create($httpOptions);
				$json = file_get_contents($search_url, false, $streamContext);
				// echo $json;
				if(count($json)!=0){
						
				    $json=json_decode($json,true);
				    $result=json_encode($json[0]['display_name']);
					// return str_replace('"', '', $json);

				}
				writeLog('google_address', 'over limit- '.$lat.'-'.$lng);
			}
		}
		
		return $result;
	}
?>