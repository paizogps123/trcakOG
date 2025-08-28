<?php
   function reportsAddHeaderStart($format)
   	{
   		global $ms, $gsValues;
   		
   		$result = '';
   		
   		if (($format == 'html') || ($format == 'pdf'))
   		{
   			$result = 	'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
   					<html>
   					<head>
   					<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   					<title>'.$gsValues['NAME'].' '.$gsValues['VERSION'].'</title>';
   		}
   		else if ($format == 'xls')
   		{
   			$result = 	'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
   					<html xmlns="http://www.w3.org/1999/xhtml">
   					<head>
   					<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   					<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">
   					<title></title>';
   		}
   		
   		return $result;
   	}
   	
   	function reportsAddHeaderEnd()
   	{
   		$result = '</head><body>';
   		
   		return $result;
   	}
   	
   	function reportsAddFooter($type)
   	{		
   		$result="";
   		 if (strpos($type,'ambulance') !== false) {
   		 	
   		 	$result='<style>
   						
   .modal {
   	display:none;
       position: fixed; 
       z-index: 1; 
       left: 0;
       top: 0;
       width: 100%; 
       height: 100%; 
       overflow: auto; 
       background-color: rgba(0,0,0,0.4); 
   }
   
   
   .modal-content {
       background-color: #fefefe;
       margin: 15% auto; 
       padding: 20px;
       width: 80%; 
   }
   
   .close {
       color: #aaa;
       float: right;
       font-size: 28px;
       font-weight: bold;
   }
   
   .close:hover,
   .close:focus {
       color: black;
       text-decoration: none;
       cursor: pointer;
   }
   #map_canvas { margin: 0; padding: 0; height: 100% }
   		
   		</style>';
     			
   			  $result .= ' <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js"></script>
              <script type="text/javascript">
   			 var map;
             	 var mapOptions = { center: new google.maps.LatLng(13.0827, 80.2707), zoom: 12,
                mapTypeId: google.maps.MapTypeId.ROADMAP };
                
   			 var modal = document.getElementById("myModal");
   			 var span = document.getElementsByClassName("close")[0];
   			 function createMarker(lat,lng) {
   		     modal.style.display = "block";
   		      var mapOptions = { center: new google.maps.LatLng(lat,lng), zoom: 12,
               mapTypeId: google.maps.MapTypeId.ROADMAP };
   		   	 map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
        		 marker = new google.maps.Marker({
                position: new google.maps.LatLng(lat,lng),
                map: map
           	 });
   			 }
   			 function closeMap() {
       		 modal.style.display = "none";
   			 }
   			 window.onclick = function(event) {
       			if (event.target == modal) {
           		modal.style.display = "none";
       			}
   			}
   			//google.maps.event.addDomListener(window, "load", initialize);
              </script>';
   		}
   		
   		
   		return $result;
   	}
   	
   	function reportsAddStyle($format)
   	{
   		$result = "<style type='text/css'>";
   		
   		if ($format == 'html')
   		{
   		$result .= "@import url(https://fonts.googleapis.com/css?family=Open+Sans:400,600,300,700&subset=latin,greek,greek-ext,cyrillic,cyrillic-ext,latin-ext,vietnamese);
   				
   				html, body {
   					text-align: left; 
   					margin: 10px;
   					padding: 0px;
   					font-size: 11px;
   					font-family: 'open sans';
   					color: #444444;
   				}";
   		}
   		else if ($format == 'pdf')
   		{
   		$result .= "	html, body {
   					text-align: left; 
   					margin: 10px;
   					padding: 0px;
   					font-size: 11px;
   					font-family: 'DejaVu Sans';
   					color: #444444;
   				}";
   		}
   		else if ($format == 'xls')
   		{
   		$result .= "	html, body {
   					text-align: left; 
   					margin: 10px;
   					padding: 0px;
   					font-size: 11px;
   					color: #444444;
   				}";
   		}
   		
   		$result .= ".logo { border:0px; width:300px; height:75px; }
   		
   				h3 { 
   					font-size: 13px;
   					font-weight: 600;
   				}
   				
   				hr {
   					border-color: #cccccc;
   					border-style: solid none none;
   					border-width: 1px 0 0;
   					height: 1px;
   					margin-left: 1px;
   					margin-right: 1px;
   				}
   				
   				a,
   				a:hover { text-decoration: none; color: #2b82d4; }
   				b, strong{ font-weight: 600; }
   				
   				.graph-controls
   				{
   					margin-bottom: 10px;
   					display: table;
   					width: 100%;
   				}
   				.graph-controls div
   				{
   					display: inline-block;
   					vertical-align: middle;
   					font-size: 11px;
   				}
   				.graph-controls-left
   				{
   					float: left;
   					margin-top: 5px;
   				}
   				.graph-controls-right
   				{
   					float: right;
   				}
   				.graph-controls a
   				{
   					margin: 5px;
   					display: inline-block;
   					-webkit-transition: all 0.3s ease;
   					-moz-transition: all 0.3s ease;
   					-ms-transition: all 0.3s ease;
   					-o-transition: all 0.3s ease;
   					transition: all 0.3s ease;
   				}
   				.graph-controls a:hover { opacity: 0.9; }
   				
   				caption,
   				th,
   				td { vertical-align: middle; }
   				
   				table.report {
   ]					border: 1px solid #eeeeee;
   					border-collapse: collapse;
   				}
   				
   				table.report th {
   					font-weight: 600;
   					padding: 2px;
   					border: 1px solid #eeeeee;
   					background-color: #eeeeee;
   				}
   				
   				table.report td {
   					padding: 2px;
   					border: 1px solid #eeeeee;
   				}
   				
   				table.report tr:hover { background-color: #f5f5f5; }
   				
   				td { mso-number-format:'\@';/*force text*/ }
   			
   			</style>";
   		
   			
   		return $result;
   	}
   	
   	function reportsAddJS($type)
   	{
   		global $ms, $gsValues;
   		
   		$result = '';
   		
   		if (($type == 'speed_graph') || ($type == 'altitude_graph') || ($type == 'acc_graph') || ($type == 'fuellevel_graph') || ($type == 'temperature_graph') || ($type == 'sensor_graph'))
   		{
   			$result .= '<script type="text/javascript" src="'.$gsValues['URL_ROOT'].'/js/jquery-2.1.4.min.js"></script>
   				<script type="text/javascript" src="'.$gsValues['URL_ROOT'].'/js/jquery-migrate-1.2.1.min.js"></script>
   				<script type="text/javascript" src="'.$gsValues['URL_ROOT'].'/js/jquery.flot.min.js"></script>
   				<script type="text/javascript" src="'.$gsValues['URL_ROOT'].'/js/jquery.flot.crosshair.min.js"></script>
   				<script type="text/javascript" src="'.$gsValues['URL_ROOT'].'/js/jquery.flot.navigate.min.js"></script>
   				<script type="text/javascript" src="'.$gsValues['URL_ROOT'].'/js/jquery.flot.selection.min.js"></script>
   				<script type="text/javascript" src="'.$gsValues['URL_ROOT'].'/js/jquery.flot.time.min.js"></script>
   				<script type="text/javascript" src="'.$gsValues['URL_ROOT'].'/js/jquery.flot.resize.min.js"></script>
   				<script type="text/javascript" src="'.$gsValues['URL_ROOT'].'/js/gs.common.js"></script>
   					
   				<script type="text/javascript">
   					var graphPlot = new Array();
   					
   					function initGraph(id, graph)
   					{
   						if (!graph)
   						{
   							var data = []; // if no data, just create array for empty graph
   							var units = "";
   							var steps_flag = false;
   							var points_flag = false;
   						} 
   						else
   						{
   							var data = graph["data"];
   							var units = graph["units"];
   							
   							if (graph["result_type"] == "logic")
   							{
   								var steps_flag = true;
   								var points_flag = false;
   							}
   							else
   							{
   								var steps_flag = false;
   								var points_flag = false;
   							}
   						}
   						
   						var minzoomRange = 30000;//	min zoom in is within 1 minute range (1*60*1000 = 60000)
   						var maxzoomRange = 30 * 86400000;//	max zoom out is 5 times greater then chosen period (default is equal to 30 days 30 * 24*60*60*1000 = 86400000 )
   						
   						var options = {
   							xaxis: {
   								mode: "time", 
   								zoomRange: [minzoomRange, maxzoomRange]
   								},
   							yaxis: {
   								//min: 0, 
   								tickFormatter: function (v) {
   										var result = "";
   										if (graph)
   										{
   											result = Math.round(v * 100)/100  + " " + units;
   										}
   										return result;
   									}, 
   								zoomRange: [0, 0], 
   								panRange: false
   								},
   							selection: {mode: "x"},
   							crosshair: {mode: "x"},
   							lines: {show: true, lineWidth: 1, fill: true, fillColor: "rgba(43,130,212,0.3)", steps: steps_flag},
   							series: {lines: {show: true} , points: { show: points_flag, radius: 1 }},
   							colors: ["#2b82d4"],
   							grid: {hoverable: true, autoHighlight: true, clickable: true},
   							zoom: {
   								//interactive: true,
   								animate: true,
   								trigger: "dblclick", // or "click" for single click
   								amount: 3         // 2 = 200% (zoom in), 0.5 = 50% (zoom out)
   							},
   							pan: {interactive: false, animate: true}
   						};
   						
   						graphPlot[id] = $.plot($("#graph_plot_"+id), [data], options);
   					
   						$("#graph_plot_"+id).unbind("plothover");
   						$("#graph_plot_"+id).bind("plothover", function (event, pos, item) {
   							if (item)
   							{
   								var dt_tracker = getDatetimeFromTimestamp(item.datapoint[0]);
   								
   								var value = item.datapoint[1];
   								document.getElementById("graph_label_"+id).innerHTML = value + " " + units + " - " + dt_tracker;			
   							}
   						});
   						
   						$("#graph_plot_"+id).unbind("plotselected");
   						$("#graph_plot_"+id).bind("plotselected", function (event, ranges) {
   							graphPlot[id] = $.plot($("#graph_plot_"+id), 
   							[data],
   							$.extend(true, {}, options, {
   								xaxis: { min: ranges.xaxis.from, max: ranges.xaxis.to }
   							}));
   							
   							// dont fire event on the overview to prevent eternal loop
   							overview.setSelection(ranges, true);
   						});
   					}
   					
   					function graphPanLeft(id)
   					{
   						graphPlot[id].pan({left: -100})
   					}
   					
   					function graphPanRight(id)
   					{
   						graphPlot[id].pan({left: +100})
   					}
   					
   					function graphZoomIn(id)
   					{
   						graphPlot[id].zoom();
   					}
   					
   					function graphZoomOut(id)
   					{
   						graphPlot[id].zoomOut();
   					}
   				</script>';
   		}
   	 	else if ($type == 'ifsinfograph' || $type == 'ifsinfograph_raw' ||$type == 'fuelgraph' ||$type == 'tempgraph') {
               $result .= '<script src="'.$gsValues['URL_ROOT'].'/js/amcharts/amcharts.js" type="text/javascript"></script>
               <script type="text/javascript" src="'.$gsValues['URL_ROOT'].'/js/jquery-2.1.4.min.js"></script>
                   <script src="'.$gsValues['URL_ROOT'].'/js/amcharts/serial.js" type="text/javascript"></script>
                   			<script type="text/javascript">
   				var chartData = new Array();
                   </script>';
           }
           else if($type == 'tripreport' || $type == 'tripreportdaily' || $type == 'roundtripreport' || $type == 'tripwise_rfiddata'|| $type == 'daily_km'  )
           {
           	$result .= 	'
   			<script type="text/javascript" src="'.$gsValues['URL_ROOT'].'/js/jquery-2.1.4.min.js"></script>
   				<script>
   			$(document).ready(function(){
       $(".toggler").click(function(e){
           e.preventDefault();
           $(this).toggleClass("expand");
           var className = "cat"+$(this).attr("data-prod-cat");
           var $current= $(this).closest("tr").next();
           while($current.hasClass(className)){
               if($(this).hasClass("expand")){
                  $current.show();
                  $current = $current.next().next();
               }
               else{
                  $current.hide();
                  $current = $current.next();
               }
              
           }
   
       });
           $(".pitcher").click(function(e){
           e.preventDefault();
           var className = "cat"+$(this).attr("data-prod-cat");
           $(this).closest("tr").next().toggle();
           });
   });
   
   </script>';
           }
     		
   		
   		return $result;
   	}
   ?>