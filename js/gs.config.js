// ############################################################
// All listed setting can be changed only by editing this file
// Other settings can be changed from CPanel/Manage server
// ############################################################

var gsValues = new Array(); // do not remove

// map min zoom
gsValues['map_min_zoom'] = 3;

// map max zoom
gsValues['map_max_zoom'] = 21;

// realtime object data refresh, default 10 seconds
gsValues['map_refresh'] = 10;

// events refresh, default 30 seconds
gsValues['event_refresh'] = 30;

// cmd status refresh, default 60 seconds
gsValues['cmd_status_refresh'] = 60;

// img gallery refresh, default 60 seconds
gsValues['img_refresh'] = 60;

// chat refresh, default 10 seconds
gsValues['chat_refresh'] = 10;

// billing refresh, default 60 seconds
gsValues['billing_refresh'] = 60;

// check if user session is still active if not block and ask for login, default 30 seconds, false - do not check;
gsValues['session_check'] = 30;

// show address in bottom left panel (not recommended, it will run out of geocoder daily limit very fast and address won't show that day anymore), true or false
gsValues['side_panel_address'] = false;

// supported GPS device protocols
gsValues['protocol_list'] = new Array();
gsValues['protocol_list'] = [
                                // HTTP protocols
                                {name: 'ais140'},
                                {name: 'android'},
                                {name: 'blackberry'},
                                {name: 'iphone'},
                                {name: 'wp'},
                                // TCP and UDP listener protocols
                                {name: 'Amwell'},
                                {name: '4000'},
                                {name: '5000'},
                                {name: '6000'},
                                {name: '6000F'},
                                {name: 'FM100'},
                                {name: 'Coban'},
                                {name: 'Concox'},
                                {name: 'TR20'},
                                
                                {name: 'T20'},
                                {name: '102G'},
                                {name: 'G200'},
                                {name: 'T09'},
                                
                                {name: 'demo'},
                                {name: 'agps'},
                                {name: 'apkcom'},
                                {name: 'aplicom'},
                                {name: 'aquilatrack'},
                                {name: 'atrack'},
                                {name: 'autofon'},
                                {name: 'autoleaders'},
                                {name: 'bce'},
                                {name: 'bitrek'},
                                {name: 'bofan'},
                                {name: 'bstechnotronics'},
                                {name: 'c2stek'},
                                {name: 'calamp'},
                                {name: 'careuueco'},
                                {name: 'carrideo'},
                                {name: 'castelobd'},
                                {name: 'castelsat802'},
                                {name: 'chinaradio'},
                                {name: 'cobangmt'},
                                {name: 'concoxgt100'},
                                {name: 'concoxgt02'},
                                {name: 'concoxgt03'},
                                {name: 'concoxgt06'},
                                {name: 'concoxgt300'},
                                {name: 'concoxgt710'},
                                {name: 'cybergraphy'},
                                {name: 'dct'},
                                {name: 'detero'},
                                {name: 'digitalsystemsdsf'},
                                {name: 'digitalsystemsdsf30'},
                                {name: 'disha'},
                                {name: 'eelink'},
                                {name: 'falcomsteppiii'},
                                {name: 'fifotrack'},
                                {name: 'flextrack'},
                                {name: 'galileosky'},
                                {name: 'gator'},
                                {name: 'genekofox'},
                                {name: 'globalsattr151'},
                                {name: 'globalsattr203'},
                                {name: 'globalsattr600'},
                                {name: 'globalsatgtr128'},
                                {name: 'gosafe'},
                                {name: 'gotop'},
                                {name: 'haicom'},
                                {name: 'hbt10'},
                                {name: 'inteliot'},
                                {name: 'intellitracx1'},
                                {name: 'internex'},
                                {name: 'istartekvt206'},
                                {name: 'istartekvt600'},
                                {name: 'itrac'},
                                {name: 'itraca1'},
                                {name: 'jointech'},
                                {name: 'jtrack'},
                                {name: 'keson'},
                                {name: 'khd'},
                                {name: 'khm100a'},
                                {name: 'maestro'},
                                {name: 'mastrack'},
                                {name: 'maxepor'},
                                {name: 'megastek'},
                                {name: 'megastekgvt369'},
                                {name: 'megastekgvt800'},
                                {name: 'meiligao'},
                                {name: 'meitrack'},
                                {name: 'meitrackt322'},
                                {name: 'minifinder'},
                                {name: 'minova'},
                                {name: 'mplatam100'},
                                {name: 'mplatat150'},
                                {name: 'navtelecom'},
                                {name: 'oigo'},
                                {name: 'oko'},
                                {name: 'oner'},
                                {name: 'oriontechnology'},
                                {name: 'pointer'},
                                {name: 'pretrace'},
                                {name: 'queclink'},
                                {name: 'queclinkgl200'},
                                {name: 'queclinkgl300'},
                                {name: 'queclinkgl300w'},
                                {name: 'queclinkgl500'},
                                {name: 'queclinkgmt100'},
                                {name: 'queclinkgmt200'},
                                {name: 'queclinkgs100'},
                                {name: 'queclinkgt300'},
                                {name: 'queclinkgt301'},
                                {name: 'queclinkgt500'},
                                {name: 'queclinkgv200'},
                                {name: 'queclinkgv300'},
                                {name: 'queclinkgv300w'},
                                {name: 'queclinkgv500'},
                                {name: 'queclinkgv55'},
                                {name: 'queclinkgv65'},
                                {name: 'queclinkgv75'},
                                {name: 'queclinkgv75w'},
                                {name: 'queclinktrs'},
                                {name: 'raveon'},
                                {name: 'roadsay'},
                                {name: 'ruptela'},
                                {name: 'sanav'},
                                {name: 'satellitesolutions'},
                                {name: 'silicontechlabs'},
                                {name: 'skypatrol'},
                                {name: 'skytrackjb101'},
                                {name: 'spystoreitaliaperfect3'},
                                {name: 'suntech'},
                                {name: 'telcomip'},
                                {name: 'teltonikaat'},
                                {name: 'teltonikafm'},
                                {name: 'teltonikagh'},
                                {name: 'tkstar'},
                                {name: 'tlt2h'},
                                {name: 'topflytech'},
                                {name: 'topten'},
                                {name: 'totemtech'},
                                {name: 'tracerx2'},
                                {name: 'trackpro'},
                                {name: 'twig'},
                                {name: 'trackertechnologymsp340'},
                                {name: 'trackertechnologymsp350'},
                                {name: 'tytan'},
                                {name: 'tzonedigital'},
                                {name: 'ulbotech'},
                                {name: 'uniguard'},
                                {name: 'unknown056'},
                                {name: 'unknown807rf'},
                                {name: 'unknowncctr800'},
                                {name: 'unknowng64'},
                                {name: 'unknowngm908'},
                                {name: 'unknowngp106m'},
                                {name: 'unknownhw18'},
                                {name: 'unknownm60'},
                                {name: 'unknownpg88'},
                                {name: 'unknownpolgps1'},
                                {name: 'unknownpt3000'},
                                {name: 'unknownstl060'},
                                {name: 'unknownt0024'},
                                {name: 'unknowntc45'},
                                {name: 'unknowntk103'},
                                {name: 'unknowntk103'},
                                {name: 'unknowntk103_2'},
                                {name: 'unknowntk103_3'},
                                {name: 'unknowntk103_4'},
                                {name: 'unknowntk103_5'},
                                {name: 'unknowntk106'},
                                {name: 'unknowntl206'},
                                {name: 'unknownvt206'},
                                {name: 'unknownwb1g'},
                                {name: 'visiontek86vtu'},
                                {name: 'visiontek87vtu'},
                                {name: 'carrideo'},
                                {name: 'wialonips'},
                                {name: 'xexun'},
                                {name: 'yuwei'}
                        ];

// supported GPS device list
gsValues['device_list'] = new Array();
gsValues['device_list'] = [
                                {name: 'I-Tracker'},
                                {name: 'Dispenser'},
                           		{name: 'Play AIS140'},
                                {name: 'Play T09'},
                                {name: 'Play T09+'},
                                {name: 'Play Max'},
                                {name: 'Play FMT'},
                           		{name: 'Play 007'},
                                {name: 'Play FM100'},
                                {name: 'Play T20'},
                            	{name: 'Play G200'},
                            	{name: 'Play 102G'},
                            	{name: 'Play 4000'},
                            	{name: 'Play 5000'},
                            	{name: 'Play 6000'},
                            	{name: 'Play 6000F'},
                            	{name: 'Play Basic'},
                            	{name: 'Play TR20'},
                            	{name: 'Play Coban'},
                            	{name: 'Play Concox'},
                                {name: 'Play T100'},
                                {name: 'Play T200'},
                                {name: 'Play Mobile (Android)'},
                                {name: 'Play Mobile (iOS)'},
                                {name: 'Other'}
                                ];


   gsValues['device_list'] = [
	   {"name": "PGS AIS140", "value": "Play AIS140"},
	                                   {"name": "PGS T09", "value": "Play T09"},
	                                   {"name": "PGS T09+", "value": "Play T09+"},
	                                   {"name": "PGS FMT", "value": "Play FMT"},
	                                   {"name": "PGS FM100", "value": "Play FM100"},
	                                   {"name": "PGS T20", "value": "Play T20"},
	                                   {"name": "PGS G200", "value": "Play G200"},
	                                   {"name": "PGS 102G", "value": "Play 102G"},
	                                   {"name": "PGS Basic", "value": "Play Basic"},
	                                   {"name": "PGS TR20", "value": "Play TR20"},
	                                   {"name": "PGS Coban", "value": "Play Coban"},
	                                   {"name": "PGS Concox", "value": "Play Concox"},
	                                   {"name": "PGS T100", "value": "Play T100"},
	                                   {"name": "PGS T200", "value": "Play T200"},
	                                   {"name": "Other", "value": "Other"}
	                               ];

gsValues['fuel_sensor_list'] = new Array();
gsValues['fuel_sensor_list'] = [
                                {name:'No Sensor'},
                                {name:'RS232'},
                                {name:'RS485'},
                                {name:'RS232 Kus'},
                                {name:'RS232 Slvr'},
                                {name:'RS232 DrV'},
                                {name:'JT232'}
                                ];
gsValues['accessories_list'] = new Array();
gsValues['accessories_list'] = [
                            {name:'A/C Line'},
                            {name:'Temp Sensor'},
                            {name:'RFID Reader'},
                            {name:'Panic Button'},
                            {name:'Buzzer'},
                            {name:'Ignition Line Relay'},
                            {name:'CCTV'}
                            ];                        
