<? 

	$commandv=array();
	
	$commandv[]=array("devtype"=>"Play T20","use"=>"State command R","command"=>"*269#R","cmdtype"=>"ASCII","startwith"=>"");
	$commandv[]=array("devtype"=>"Play T20","use"=>"Query command C","command"=>"*269#C","cmdtype"=>"ASCII","startwith"=>"");
	$commandv[]=array("devtype"=>"Play T20","use"=>"Query FW Version","command"=>"*269#V","cmdtype"=>"ASCII","startwith"=>"");
	$commandv[]=array("devtype"=>"Play T20","use"=>"ACK Command","command"=>"*269#M,1#","cmdtype"=>"ASCII","startwith"=>"");
	
	$commandv[]=array("devtype"=>"Play T09","use"=>"State command R","command"=>"*269#R","cmdtype"=>"ASCII","startwith"=>"");
	$commandv[]=array("devtype"=>"Play T09","use"=>"Query command C","command"=>"*269#C","cmdtype"=>"ASCII","startwith"=>"");
	

	$commandv[]=array("devtype"=>"Play T09+","use"=>"State command R","command"=>"*269#R","cmdtype"=>"ASCII","startwith"=>"");
	$commandv[]=array("devtype"=>"Play T09+","use"=>"Query command C","command"=>"*269#C","cmdtype"=>"ASCII","startwith"=>"");


	$commandv[]=array("devtype"=>"ILOCKTracker","use"=>"Price of Delivered Qty","command"=>"*527#PriceDQty,1#","cmdtype"=>"ASCII","startwith"=>"*527#PriceDQty");

	$commandv[]=array("devtype"=>"ILOCKTracker","use"=>"Set Lock Screen","command"=>"*527#Lock,Meter,0/1#","cmdtype"=>"ASCII","startwith"=>"*527#Lock");
	$commandv[]=array("devtype"=>"ILOCKTracker","use"=>"Lock Screen Status","command"=>"*527#Lock,Meter#","cmdtype"=>"ASCII","startwith"=>"*527#Lock");
	$commandv[]=array("devtype"=>"ILOCKTracker","use"=>"Auto Lock","command"=>"*527#Autolock,Meter,0/1#","cmdtype"=>"ASCII","startwith"=>"*527#Autolock");
	$commandv[]=array("devtype"=>"ILOCKTracker","use"=>"Query Auto Lock","command"=>"*527#Autolock,Meter#","cmdtype"=>"ASCII","startwith"=>"*527#Autolock");

	$commandv[]=array("devtype"=>"ILOCKTracker","use"=>"Set Unit Price","command"=>"*527#Price,Meter,Rate#","cmdtype"=>"ASCII","startwith"=>"*527#Price");
	$commandv[]=array("devtype"=>"ILOCKTracker","use"=>"Query Unit Price","command"=>"*527#Price,Meter#","cmdtype"=>"ASCII","startwith"=>"*527#Price");

	$commandv[]=array("devtype"=>"I-Tracker","use"=>"Restart","command"=>"*527#REBOOT#","cmdtype"=>"ASCII","startwith"=>"*527#Price");

	$commandv[]=array("devtype"=>"Play FM100","use"=>"FUEL ID READ","command"=>"@FUELCNF:@01G030019C#","cmdtype"=>"ASCII","startwith"=>"FUEL:");
	
	$commandv[]=array("devtype"=>"Play FM100","use"=>"Set Fuel tank Empty :S1","command"=>"@FUELCNF:@01B01135#","cmdtype"=>"ASCII","startwith"=>"FUEL:");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"Set Fuel tank Full :S1","command"=>"@FUELCNF:@01C01136#","cmdtype"=>"ASCII","startwith"=>"FUEL:");
	
	$commandv[]=array("devtype"=>"Play FM100","use"=>"FUEL 2->1","command"=>"@FUELCNF:@02G031019E#","cmdtype"=>"ASCII","startwith"=>"FUEL:");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"FUEL1 Read","command"=>"@FUELCNF:@01E0006#","cmdtype"=>"ASCII","startwith"=>"FUEL:");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"Read Damping Time ->1","command"=>"@FUELCNF:@01H050006004#","cmdtype"=>"ASCII","startwith"=>"FUEL:");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"Set Damping Time 200 ->1","command"=>"@FUELCNF:@01H051020001#","cmdtype"=>"ASCII","startwith"=>"FUEL:");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"FMS List","command"=>"@FMSLIST","cmdtype"=>"ASCII","startwith"=>"Available PID");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"FMS Info","command"=>"@FMSINFO","cmdtype"=>"ASCII","startwith"=>"FMS");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"GSM Info","command"=>"@GSMINFO","cmdtype"=>"ASCII","startwith"=>"Rssi:");	
	$commandv[]=array("devtype"=>"Play FM100","use"=>"DIG2OUT OFF","command"=>"@DIG2OUT ON","cmdtype"=>"ASCII","startwith"=>"DIG2 output");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"DIG2OUT ON","command"=>"@DIG2OUT OFF","cmdtype"=>"ASCII","startwith"=>"DIG2 output");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"INVERT","command"=>"@INVERT:DIG1","cmdtype"=>"ASCII","startwith"=>"DIG1 polarity");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"Set Alarm Config","command"=>"@alarmcnfg=100,BF","cmdtype"=>"ASCII","startwith"=>"Fuel level alarm set");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"Get Alarm Config","command"=>"@alarmcnfg","cmdtype"=>"ASCII","startwith"=>"Fuel level alarm set");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"Set Baudrate","command"=>"@baudrate=9600","cmdtype"=>"ASCII","startwith"=>"Baudrate");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"Get Baudrate","command"=>"@baudrate","cmdtype"=>"ASCII","startwith"=>"Baudrate");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"Erase log","command"=>"@ERASE","cmdtype"=>"ASCII","startwith"=>"External Flash cleaning");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"RS485","command"=>"@RS485","cmdtype"=>"ASCII","startwith"=>"Supported protocols:");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"PING","command"=>"@PING","cmdtype"=>"ASCII","startwith"=>"PONG");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"REBOOT","command"=>"@REBOOT","cmdtype"=>"ASCII","startwith"=>"None");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"INFO","command"=>"@INFO","cmdtype"=>"ASCII","startwith"=>"INFO");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"COUNT-ON","command"=>"@COUNT-ON","cmdtype"=>"ASCII","startwith"=>"CT:DIG");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"COUNT-OFF","command"=>"@COUNT-OFF","cmdtype"=>"ASCII","startwith"=>"CT:DIG");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"NMEA-ON","command"=>"@NMEA-ON","cmdtype"=>"ASCII","startwith"=>"fix: fix");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"NMEA-OFF","command"=>"@NMEA-OFF","cmdtype"=>"ASCII","startwith"=>"fix: fix");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"CNFG","command"=>"@CNFG:30,5,15,1,300,0,0,0,0","cmdtype"=>"ASCII","startwith"=>"Data store interval");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"GETCONG","command"=>"@getcnfg","cmdtype"=>"ASCII","startwith"=>"GETCNFG");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"DATA","command"=>"@DATA","cmdtype"=>"ASCII","startwith"=>"Data");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"ACCINFO","command"=>"@ACCINFO","cmdtype"=>"ASCII","startwith"=>"ACCINFO");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"SET ID","command"=>"@SET ID=DEVICED","cmdtype"=>"ASCII","startwith"=>"ID SET");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"SET ACCLEVEL","command"=>"@ACCLEVEL=60","cmdtype"=>"ASCII","startwith"=>"ACCEL level");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"SET ACCTIME","command"=>"@ACCTIME=60","cmdtype"=>"ASCII","startwith"=>"ACCEL time");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"SET CAL1","command"=>"@SET CAL1=5","cmdtype"=>"ASCII","startwith"=>"ADC1 mult");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"SET CAL2","command"=>"@SET CAL2=5","cmdtype"=>"ASCII","startwith"=>"ADC2 mult");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"SET CAL3","command"=>"@SET CAL3=5","cmdtype"=>"ASCII","startwith"=>"ADC3 mult");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"SET CALP","command"=>"@SET CALP=16","cmdtype"=>"ASCII","startwith"=>"POWER mult");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"SET CALB","command"=>"@SET CALB=4.2","cmdtype"=>"ASCII","startwith"=>"BATTERY mult");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"SET ACCLEVEL","command"=>"@ACCLEVEL=60","cmdtype"=>"ASCII","startwith"=>"ACCEL level");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"APN CHECK","command"=>"@APN","cmdtype"=>"ASCII","startwith"=>"APN");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"SET APN","command"=>"@APN:airtelgprs.com,169.38.65.242,8005,,","cmdtype"=>"ASCII","startwith"=>"APN");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"SET EXP","command"=>"@SET EXP=5","cmdtype"=>"ASCII","startwith"=>"EXP SET:");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"SET LOGS","command"=>"@SET LOGS=8","cmdtype"=>"ASCII","startwith"=>"Number of logs in one TCP packet");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"PLCLIFETIME","command"=>"@PLCLIFETIME=1","cmdtype"=>"ASCII","startwith"=>"PLC life time");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"PLCSENDTIME","command"=>"@PLCSENDTIME=1","cmdtype"=>"ASCII","startwith"=>"PLC send time");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"fuel4","command"=>"4NADEVICEDNO","cmdtype"=>"HEX","startwith"=>"ASCII");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"alarm_gps_open","command"=>"ALARM:DEVICED:0001","cmdtype"=>"ASCII","startwith"=>"DA+OK");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"alarm_gps_short","command"=>"ALARM:DEVICED:0002","cmdtype"=>"ASCII","startwith"=>"DA+OK");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"alarm_power_off","command"=>"ALARM:DEVICED:0004","cmdtype"=>"ASCII","startwith"=>"DA+OK");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"alarm_fuel_drop","command"=>"ALARM:DEVICED:0010","cmdtype"=>"ASCII","startwith"=>"DA+OK");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"alarm_sensor_cut","command"=>"ALARM:DEVICED:0020","cmdtype"=>"ASCII","startwith"=>"DA+OK");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"UPDATE FW","command"=>"@UPDS:123,61850,56709","cmdtype"=>"ASCII","startwith"=>"UPOK,9999:");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"Clear Server Commands","command"=>"CLEAR_SERVER_CMDS","cmdtype"=>"ASCII","startwith"=>"CLEAR_SERVER_CMDS:");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"Retrive Server Commands","command"=>"RETRIVE_SERVER_CMDS","cmdtype"=>"ASCII","startwith"=>"RETRIVE_SERVER_CMDS:");
	
	$commandv[]=array("devtype"=>"Play FM100","use"=>"Temp 1 Set Wire","command"=>"@set 1wireid=1","cmdtype"=>"ASCII","startwith"=>"OneWire");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"Temp 2 Set Wire","command"=>"@set 1wireid=2","cmdtype"=>"ASCII","startwith"=>"OneWire");
	
	/*
	$commandv[]=array("devtype"=>"Play FM100","use"=>"UPDATE FW1.15","command"=>"@UPDS:123,60878,56195","cmdtype"=>"ASCII","startwith"=>"UPOK,9999:");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"UPDATE FW1.15 Alarm","command"=>"@UPDS:123,61142,34285","cmdtype"=>"ASCII","startwith"=>"UPOK,9999:");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"UPDATE FW1.15 Alarm Finalize","command"=>"@UPDS:123,61278,32680","cmdtype"=>"ASCII","startwith"=>"UPOK,9999:");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"UPDATE FW1.16","command"=>"@UPDS:123,61290,8118","cmdtype"=>"ASCII","startwith"=>"UPOK,9999:");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"UPDATE FW1.16.last","command"=>"@UPDS:123,61346,39512","cmdtype"=>"ASCII","startwith"=>"UPOK,9999:");
	$commandv[]=array("devtype"=>"Play FM100","use"=>"UPDATE FW1.17","command"=>"@UPDS:123,61506,55385","cmdtype"=>"ASCII","startwith"=>"UPOK,9999:");
	
	$commandv[]=array("devtype"=>"Play FM100","use"=>"UPDATE FW","command"=>"@UPDS:222,10382,57667","cmdtype"=>"ASCII","startwith"=>"UPOK,9999:");
	*/
	
	//@ERASE delete unsent logs
	
	

	$commandv[]=array("devtype"=>"Play 6000","use"=>"IP/PORT/APN","command"=>"(DEVICED,2,S02,129,INQSET,119.81.46.165,800,airtelgprs.com)","cmdtype"=>"ASCII","startwith"=>"S02");
	$commandv[]=array("devtype"=>"Play 6000","use"=>"SMS center number","command"=>"(DEVICED,2,S03,129,INQSET,SMS_Center_NO)","cmdtype"=>"ASCII","startwith"=>"S03");
	
	
	$commandv[]=array("devtype"=>"Play 6000","use"=>"Fuel Full Tank Empty Command","command"=>"(DEVICED,2,E14,123,4,1)","cmdtype"=>"ASCII","startwith"=>"E14");
	$commandv[]=array("devtype"=>"Play 6000","use"=>"Fuel Full Tank FULL Command","command"=>"(DEVICED,2,E14,123,5,1)","cmdtype"=>"ASCII","startwith"=>"E14");
	
	
	$commandv[]=array("devtype"=>"Play 6000","use"=>"Data uploading parameter","command"=>"(DEVICED,2,S04,129,INQSET,10,0,0,0,0)","cmdtype"=>"ASCII","startwith"=>"S04");
	$commandv[]=array("devtype"=>"Play 6000","use"=>"Set mileage synchronization","command"=>"(DEVICED,2,S09,129,INQSET,1200)","cmdtype"=>"ASCII","startwith"=>"S09");
	$commandv[]=array("devtype"=>"Play 6000","use"=>"SMS control Set","command"=>"(DEVICED,2,S12,129,INQSET,1)","cmdtype"=>"ASCII","startwith"=>"S12");
	$commandv[]=array("devtype"=>"Play 6000","use"=>"Inquire firmware version","command"=>"(DEVICED,2,S14,129)","cmdtype"=>"ASCII","startwith"=>"S14");
	$commandv[]=array("devtype"=>"Play 6000","use"=>"Data transfer mode","command"=>"(DEVICED,2,S15,129,INQSET)","cmdtype"=>"ASCII","startwith"=>"S15");
	$commandv[]=array("devtype"=>"Play 6000","use"=>"S19 - Set or Inquire the current loaded public external device drivers","command"=>"(DEVICED,2,S19,129,INQSET,1,3)","cmdtype"=>"ASCII","startwith"=>"S19");
	$commandv[]=array("devtype"=>"Play 6000","use"=>"S20 - Set external device baud rate","command"=>"(DEVICED,2,S20,129,INQSET,1,8)","cmdtype"=>"ASCII","startwith"=>"S20");
	$commandv[]=array("devtype"=>"Play 6000","use"=>"E20 Detect the quantity of the JT606","command"=>"(DEVICED,2,E20,129,1)","cmdtype"=>"ASCII","startwith"=>"E20");
	$commandv[]=array("devtype"=>"Play 6000","use"=>"Inquire the CCID of SIM","command"=>"(DEVICED,2,S23,129)","cmdtype"=>"ASCII","startwith"=>"S23");
	$commandv[]=array("devtype"=>"Play 6000","use"=>"Set the user name and password of APN","command"=>"(DEVICED,2,S24,129,INQSET)","cmdtype"=>"ASCII","startwith"=>"S24");
	$commandv[]=array("devtype"=>"Play 6000","use"=>"Inquire history location data","command"=>"(DEVICED,2,S25,129,100,10)","cmdtype"=>"ASCII","startwith"=>"S25");
	$commandv[]=array("devtype"=>"Play 6000","use"=>"Whether start real time uploading history location data","command"=>"(DEVICED,2,S33,129,INQSET,1)","cmdtype"=>"ASCII","startwith"=>"S33");
	$commandv[]=array("devtype"=>"Play 6000","use"=>"Set the history location data storage interval","command"=>"(DEVICED,2,S35,129,INQSET,60)","cmdtype"=>"ASCII","startwith"=>"S35");
	$commandv[]=array("devtype"=>"Play 6000","use"=>"Return to factory-set","command"=>"(DEVICED,2,S44,129)","cmdtype"=>"ASCII","startwith"=>"S44");
	$commandv[]=array("devtype"=>"Play 6000","use"=>"Cold start the terminal","command"=>"(DEVICED,2,C01,129)","cmdtype"=>"ASCII","startwith"=>"C01");
	$commandv[]=array("devtype"=>"Play 6000","use"=>"Test over speed alarm buzzer","command"=>"(DEVICED,2,C02,129)","cmdtype"=>"ASCII","startwith"=>"C02");
    $commandv[]=array("devtype"=>"Play 6000","use"=>"Cut off oil and power command","command"=>"(DEVICED,2,C04,129,INQSET)","cmdtype"=>"ASCII","startwith"=>"C04");
	$commandv[]=array("devtype"=>"Play 6000","use"=>"Enable oil and power","command"=>"(DEVICED,2,C05,129,INQSET))","cmdtype"=>"ASCII","startwith"=>"C05");
	$commandv[]=array("devtype"=>"Play 6000","use"=>"Remote listen-in command","command"=>"(DEVICED,2,C06,129,13710103232))","cmdtype"=>"ASCII","startwith"=>"C06");
	$commandv[]=array("devtype"=>"Play 6000","use"=>"Customize control command","command"=>"(DEVICED,2,C07,129,INQSET,2,1,10)","cmdtype"=>"ASCII","startwith"=>"C07");
	$commandv[]=array("devtype"=>"Play 6000","use"=>"Alarm Set command","command"=>"(DEVICED,2,A01,129,INQSET,7,1,5,1)","cmdtype"=>"ASCII","startwith"=>"A01");
	$commandv[]=array("devtype"=>"Play 6000","use"=>"Initialization alarm Set","command"=>"(DEVICED,2,A02,129))","cmdtype"=>"ASCII","startwith"=>"A02");
	$commandv[]=array("devtype"=>"Play 6000","use"=>"Set overtime parking alarm parameter","command"=>"(DEVICED,2,A04,129,INQSET,60,8,18)","cmdtype"=>"ASCII","startwith"=>"A04");
	$commandv[]=array("devtype"=>"Play 6000","use"=>"Set over speed alarm indication in vehicle","command"=>"(DEVICED,2,A06,129,INQSET)","cmdtype"=>"ASCII","startwith"=>"A06");
	$commandv[]=array("devtype"=>"Play 6000","use"=>"Set fuel level change alarm parameter","command"=>"(DEVICED,2,A09,129,INQSET,1,200,5,30)","cmdtype"=>"ASCII","startwith"=>"A09");
	$commandv[]=array("devtype"=>"Play 6000","use"=>"Set Current connected Cameras ID","command"=>"(DEVICED,2,E21,123,1,INQSET,3,2,5,6)","cmdtype"=>"ASCII","startwith"=>"E21");
	$commandv[]=array("devtype"=>"Play 6000","use"=>"Take photo instantly","command"=>"(DEVICED,2,E21,123,2,INQSET,10,4,2)","cmdtype"=>"ASCII","startwith"=>"E21");
	$commandv[]=array("devtype"=>"Play 6000","use"=>"Set triggered signal for photo taking","command"=>"(DEVICED,2,E21,123,3,INQSET,3,3,0,0,0)","cmdtype"=>"ASCII","startwith"=>"E21");
	$commandv[]=array("devtype"=>"Play 6000","use"=>"Set take photo in time point","command"=>"(DEVICED,2,E21,123,4,INQSET,2,2,3,30,5,480,495,510,1080,1095)","cmdtype"=>"ASCII","startwith"=>"E21");
	$commandv[]=array("devtype"=>"Play 6000","use"=>"Resend the image frames from tracker","command"=>"(DEVICED,2,E21,123,5,2,3,1,2)","cmdtype"=>"ASCII","startwith"=>"E21");
	$commandv[]=array("devtype"=>"Play 6000","use"=>"Acknowledge received the whole image to tracker","command"=>"(DEVICED,2,E21,123,6,2)","cmdtype"=>"ASCII","startwith"=>"E21");
	$commandv[]=array("devtype"=>"Play 6000","use"=>"Read Digital Fuel Sensors","command"=>"(DEVICED,E14,123,1,INQSET)","cmdtype"=>"ASCII","startwith"=>"E14");
	
	
	
	$commandv[]=array("devtype"=>"Play 4000","use"=>"IP/PORT/APN","command"=>"(DEVICED,2,S02,129,INQSET,119.81.46.165,800,airtelgprs.com)","cmdtype"=>"ASCII","startwith"=>"S02");
	//$commandv[]=array("devtype"=>"Play 4000","use"=>"Set or Inquire the IP address, Port and APN name","command"=>"(DEVICED,2,S02,129,INQSET,123.22.2.187,10000,CMNET)","cmdtype"=>"ASCII","startwith"=>"S02");
	$commandv[]=array("devtype"=>"Play 4000","use"=>"Set or Inquire the SMS center number","command"=>"(DEVICED,2,S03,129,INQSET,13999998888)","cmdtype"=>"ASCII","startwith"=>"S03");
	$commandv[]=array("devtype"=>"Play 4000","use"=>" Set or Inquire the IP address, Port and APN name","command"=>"(DEVICED,2,S04,129,INQSET,10,0,0,0,0)","cmdtype"=>"ASCII","startwith"=>"S04");
	
	$commandv[]=array("devtype"=>"Play 4000","use"=>"Fuel Full Tank Empty Command","command"=>"(DEVICED,2,E14,123,4,1)","cmdtype"=>"ASCII","startwith"=>"E14");
	$commandv[]=array("devtype"=>"Play 4000","use"=>"Fuel Full Tank FULL Command","command"=>"(DEVICED,2,E14,123,5,1)","cmdtype"=>"ASCII","startwith"=>"E14");
	
	
	$commandv[]=array("devtype"=>"Play 4000","use"=>"Set mileage synchronization","command"=>"(DEVICED,2,S09,129,INQSET,1200)","cmdtype"=>"ASCII","startwith"=>"S09");
	$commandv[]=array("devtype"=>"Play 4000","use"=>"SMS control Set","command"=>"(DEVICED,2,S12,129,INQSET,1)","cmdtype"=>"ASCII","startwith"=>"S12");
	$commandv[]=array("devtype"=>"Play 4000","use"=>"Inquire firmware version","command"=>"(DEVICED,2,S14,129,20081205)","cmdtype"=>"ASCII","startwith"=>"S14");
	$commandv[]=array("devtype"=>"Play 4000","use"=>"Data transfer mode","command"=>"(DEVICED,2,S15,129,INQSET,0)","cmdtype"=>"ASCII","startwith"=>"S15");
	$commandv[]=array("devtype"=>"Play 4000","use"=>"S19 - Set or Inquire the current loaded public external device drivers","command"=>"(DEVICED,2,S19,129,INQSET,1,3)","cmdtype"=>"ASCII","startwith"=>"S19");
	$commandv[]=array("devtype"=>"Play 4000","use"=>"S20 - Set external device baud rate","command"=>"(DEVICED,2,S20,129,INQSET,1,8)","cmdtype"=>"ASCII","startwith"=>"S20");
	$commandv[]=array("devtype"=>"Play 4000","use"=>"E20 Detect the quantity of the JT606","command"=>"(DEVICED,2,E20,129,1)","cmdtype"=>"ASCII","startwith"=>"E20");
	$commandv[]=array("devtype"=>"Play 4000","use"=>"Inquire the CCID of SIM","command"=>"(DEVICED,2,S23,129,89860020230950512536)","cmdtype"=>"ASCII","startwith"=>"S23");
	$commandv[]=array("devtype"=>"Play 4000","use"=>"Set the user name and password of APN","command"=>"(DEVICED,2,S24,129,INQSET,username,password)","cmdtype"=>"ASCII","startwith"=>"S24");
	$commandv[]=array("devtype"=>"Play 4000","use"=>"Inquire history location data","command"=>"(DEVICED,2,S25,129,100,10)","cmdtype"=>"ASCII","startwith"=>"S25");
	$commandv[]=array("devtype"=>"Play 4000","use"=>"Whether start real time uploading history location data","command"=>"(DEVICED,2,S33,129,INQSET,1)","cmdtype"=>"ASCII","startwith"=>"S33");
	$commandv[]=array("devtype"=>"Play 4000","use"=>"Set the history location data storage interval","command"=>"(DEVICED,2,S35,129,INQSET,60)","cmdtype"=>"ASCII","startwith"=>"S35");
	$commandv[]=array("devtype"=>"Play 4000","use"=>"Return to factory-set","command"=>"(DEVICED,2,S44,129)","cmdtype"=>"ASCII","startwith"=>"S44");
	$commandv[]=array("devtype"=>"Play 4000","use"=>"Cold start the terminal","command"=>"(DEVICED,2,C01,129)","cmdtype"=>"ASCII","startwith"=>"C01");
	$commandv[]=array("devtype"=>"Play 4000","use"=>"Test over speed alarm buzzer","command"=>"(DEVICED,2,C02,129)","cmdtype"=>"ASCII","startwith"=>"C02");
	$commandv[]=array("devtype"=>"Play 4000","use"=>"Cut off oil and power command","command"=>"(DEVICED,2,C04,129,1)","cmdtype"=>"ASCII","startwith"=>"C04");
	$commandv[]=array("devtype"=>"Play 4000","use"=>"Enable oil and power","command"=>"(DEVICED,2,C05,129,0)","cmdtype"=>"ASCII","startwith"=>"C05");	
	$commandv[]=array("devtype"=>"Play 4000","use"=>"Remote listen-in command","command"=>"(DEVICED,2,C06,129,13710103232)","cmdtype"=>"ASCII","startwith"=>"C06");	
	$commandv[]=array("devtype"=>"Play 4000","use"=>"Customize control command","command"=>"(DEVICED,2,C07,129,INQSET,2,1,10)","cmdtype"=>"ASCII","startwith"=>"C07");	
	$commandv[]=array("devtype"=>"Play 4000","use"=>"Alarm Set command","command"=>"(DEVICED,2,A01,129,INQSET,7,1,5,1)","cmdtype"=>"ASCII","startwith"=>"A01");	
	$commandv[]=array("devtype"=>"Play 4000","use"=>"Initialization alarm Set","command"=>"(DEVICED,2,A02,129)","cmdtype"=>"ASCII","startwith"=>"A02");	
	$commandv[]=array("devtype"=>"Play 4000","use"=>"Set overtime parking alarm parameter","command"=>"(DEVICED,2,A04,129,INQSET,60,8,18)","cmdtype"=>"ASCII","startwith"=>"A04");	
	$commandv[]=array("devtype"=>"Play 4000","use"=>"Set over speed alarm indication in vehicle","command"=>"(DEVICED,2,A06,129,INQSET,1)","cmdtype"=>"ASCII","startwith"=>"A06");	
	$commandv[]=array("devtype"=>"Play 4000","use"=>"Set fuel level change alarm parameter","command"=>"(DEVICED,2,A09,129,INQSET,1,200,5,30)","cmdtype"=>"ASCII","startwith"=>"A09");	
	$commandv[]=array("devtype"=>"Play 4000","use"=>"Set Current connected Cameras ID","command"=>"(DEVICED,2,E21,123,1,INQSET,3,2,5,6)","cmdtype"=>"ASCII","startwith"=>"E21");	
	$commandv[]=array("devtype"=>"Play 4000","use"=>"Take photo instantly","command"=>"(DEVICED,2,E21,123,2,1,10,4,2)","cmdtype"=>"ASCII","startwith"=>"E21");	
	$commandv[]=array("devtype"=>"Play 4000","use"=>"Set triggered signal for photo taking","command"=>"(DEVICED,2,E21,123,3,INQSET,3,3,0,0,0)","cmdtype"=>"ASCII","startwith"=>"E21");	
	$commandv[]=array("devtype"=>"Play 4000","use"=>"Set take photo in time point","command"=>"(DEVICED,2,E21,123,4,INQSET,2,2,3,30,5,480,495,510,1080,1095)","cmdtype"=>"ASCII","startwith"=>"E21");	
	$commandv[]=array("devtype"=>"Play 4000","use"=>"Resend the image frames from tracker","command"=>"(DEVICED,2,E21,123,5,2,3,4,3,9,15,18)","cmdtype"=>"ASCII","startwith"=>"E21");	
	$commandv[]=array("devtype"=>"Play 4000","use"=>"Acknowledge received the whole image to tracker","command"=>"(DEVICED,2,E21,123,6,2)","cmdtype"=>"ASCII","startwith"=>"E21");	
	
	
	
    $commandv[]=array("devtype"=>"Play 5000","use"=>"IP/PORT/APN","command"=>"(DEVICED,1,S02,129,INQSET,119.81.46.165,800,airtelgprs.com)","cmdtype"=>"ASCII","startwith"=>"S02");
	//$commandv[]=array("devtype"=>"Play 5000","use"=>"Setting or inquiring IP address,port and APN name","command"=>"(DEVICED,1,S02,123,INQSET)","cmdtype"=>"ASCII","startwith"=>"S02");
	$commandv[]=array("devtype"=>"Play 5000","use"=>"Setting or inquiring the SMS center number","command"=>"(DEVICED,1,S03,129,INQSET,13901821234)","cmdtype"=>"ASCII","startwith"=>"S03");
	$commandv[]=array("devtype"=>"Play 5000","use"=>"Setting or inquiring the current reply interval","command"=>"(DEVICED,1,S04,129,INQSET,10,0)","cmdtype"=>"ASCII","startwith"=>"S04");
	$commandv[]=array("devtype"=>"Play 5000","use"=>"Setting or inquiring mileage starting value","command"=>"(DEVICED,1,S09,129,INQSET,231)","cmdtype"=>"ASCII","startwith"=>"S09");
	$commandv[]=array("devtype"=>"Play 5000","use"=>"Setting car owners cell phone number control the tracker","command"=>"(DEVICED,1,S12, 129,INQSET,1)","cmdtype"=>"ASCII","startwith"=>"S12");
	$commandv[]=array("devtype"=>"Play 5000","use"=>"Inquiring the current firmware version","command"=>"(DEVICED,1,S14,129, 20080102)","cmdtype"=>"ASCII","startwith"=>"S14");
	$commandv[]=array("devtype"=>"Play 5000","use"=>"Setting or inquiring data upload mode","command"=>"(DEVICED,1,S15,129,INQSET,0)","cmdtype"=>"ASCII","startwith"=>"S15");
	$commandv[]=array("devtype"=>"Play 5000","use"=>"Setting or inquiring the current loaded public exernal device drivers","command"=>"(DEVICED,1,S19,123,1,1,1)","cmdtype"=>"ASCII","startwith"=>"S19");
	$commandv[]=array("devtype"=>"Play 5000","use"=>"Setting or inquiring the baud rate of the corresponding port","command"=>"(DEVICED,1,S20,123,INQSET,1)","cmdtype"=>"ASCII","startwith"=>"S20");
	$commandv[]=array("devtype"=>"Play 5000","use"=>"Inquiring the CCID number of the current SIM card","command"=>"(DEVICED,1,S23,123,86928384212823534984)","cmdtype"=>"ASCII","startwith"=>"S23");
	$commandv[]=array("devtype"=>"Play 5000","use"=>"Settting or inquiring GPRS usernamer and password","command"=>"(DEVICED,1,S24,123,INQSET,advan,888)","cmdtype"=>"ASCII","startwith"=>"S24");
	$commandv[]=array("devtype"=>"Play 5000","use"=>"Inquiring history data","command"=>"(DEVICED,1,S25,123,10,2)","cmdtype"=>"ASCII","startwith"=>"S25");
	$commandv[]=array("devtype"=>"Play 5000","use"=>"Cold start the tracker","command"=>"(DEVICED,1,C01,129)","cmdtype"=>"ASCII","startwith"=>"C01");
	$commandv[]=array("devtype"=>"Play 5000","use"=>"Test the overspeed alarm buzzer","command"=>"(DEVICED,1,C02,129)","cmdtype"=>"ASCII","startwith"=>"C02");
	$commandv[]=array("devtype"=>"Play 5000","use"=>"Fuel cut and power cut","command"=>"(DEVICED,1,C04,129)","cmdtype"=>"ASCII","startwith"=>"C04");
	$commandv[]=array("devtype"=>"Play 5000","use"=>"Enable oil and power","command"=>"(DEVICED,1,C05,129)","cmdtype"=>"ASCII","startwith"=>"C05");
	$commandv[]=array("devtype"=>"Play 5000","use"=>"Remote listen-in","command"=>"(DEVICED,1,C06,129,13999999999)","cmdtype"=>"ASCII","startwith"=>"C06");
	$commandv[]=array("devtype"=>"Play 5000","use"=>"Setting alarm switch","command"=>"(DEVICED,1,A01,123,INQSET,1,1,6)","cmdtype"=>"ASCII","startwith"=>"A01");
	$commandv[]=array("devtype"=>"Play 5000","use"=>"Initialized alarm setting","DEVICED"=>"(DEVICED,1,A02,123)","cmdtype"=>"ASCII","startwith"=>"A02");
	$commandv[]=array("devtype"=>"Play 5000","use"=>"Setting overtime parking alarm","command"=>"(DEVICED,1,A04,123,1,0,1200)","cmdtype"=>"ASCII","startwith"=>"A04");
	$commandv[]=array("devtype"=>"Play 5000","use"=>"Overspeed alarm indication in vehicle","command"=>"(DEVICED,1,A06,123,1,INQSET)","cmdtype"=>"ASCII","startwith"=>"A06");
	$commandv[]=array("devtype"=>"Play 5000","use"=>"Setting Fuel level alarm parameter","command"=>"((DEVICED,1,A09,123,INQSET,30)","cmdtype"=>"ASCII","startwith"=>"A09");
	
	
	
	$commandv[]=array("devtype"=>"Play 6000F","use"=>"IP/PORT/APN","command"=>"(DEVICED,3,S02,129,INQSET,119.81.46.165,800,airtelgprs.com)","cmdtype"=>"ASCII","startwith"=>"S02");
	//$commandv[]=array("devtype"=>"Play 6000F","use"=>"Set or Inquire the IP address, Port and APN","command"=>"(6081118888,3,S02,129,INQSET,123.22.2.187,10000,CMNET)","cmdtype"=>"ASCII","startwith"=>"S02");	    
	$commandv[]=array("devtype"=>"Play 6000F","use"=>"Set or Inquire the SMS center number","command"=>"(DEVICED,3,S03,129,INQSET,13999998888)","cmdtype"=>"ASCII","startwith"=>"S03");
	$commandv[]=array("devtype"=>"Play 6000F","use"=>"Set or Inquire the data uploading parameter","command"=>"(DEVICED,3,S04,129,INQSET,10,0)","cmdtype"=>"ASCII","startwith"=>"S04");
	
	$commandv[]=array("devtype"=>"Play 6000F","use"=>"Fuel Full Tank Empty Command","command"=>"(DEVICED,3,E14,123,4,1)","cmdtype"=>"ASCII","startwith"=>"E14");
	$commandv[]=array("devtype"=>"Play 6000F","use"=>"Fuel Full Tank FULL Command","command"=>"(DEVICED,3,E14,123,5,1)","cmdtype"=>"ASCII","startwith"=>"E14");
	
	$commandv[]=array("devtype"=>"Play 6000F","use"=>"Set mileage synchronization","command"=>"(DEVICED,3,S09,129,INQSET,1200)","cmdtype"=>"ASCII","startwith"=>"S09");
	$commandv[]=array("devtype"=>"Play 6000F","use"=>"SMS control Set","command"=>"(DEVICED,3,S12,129,INQSET,1)","cmdtype"=>"ASCII","startwith"=>"S12");
	$commandv[]=array("devtype"=>"Play 6000F","use"=>"Inquire firmware version","command"=>"((DEVICED,3,S14,129,20081205)","cmdtype"=>"ASCII","startwith"=>"S14");
	$commandv[]=array("devtype"=>"Play 6000F","use"=>"Data transfer mode","command"=>"(DEVICED,3,S15,129,INQSET,0)","cmdtype"=>"ASCII","startwith"=>"S15");
	$commandv[]=array("devtype"=>"Play 6000F","use"=>"S19 - Set or Inquire the current loaded public external device drivers","command"=>"(DEVICED,3,S19,129,INQSET,1,3)","cmdtype"=>"ASCII","startwith"=>"S19");
	$commandv[]=array("devtype"=>"Play 6000F","use"=>"S20 - Set external device baud rate","command"=>"DEVICED,3,S20,129,INQSET,1,8)","cmdtype"=>"ASCII","startwith"=>"S20");
	$commandv[]=array("devtype"=>"Play 6000F","use"=>"Inquire the CCID of SIM","command"=>"(DEVICED,3,S23,129)","cmdtype"=>"ASCII","startwith"=>"S23");
	$commandv[]=array("devtype"=>"Play 6000F","use"=>"Set the user name and password of APN","command"=>"(DEVICED,3,S24,129,INQSET,username,password)","cmdtype"=>"ASCII","startwith"=>"S24");
	$commandv[]=array("devtype"=>"Play 6000F","use"=>"Inquire history location data","command"=>"(DEVICED,3,S25,129,100,10)","cmdtype"=>"ASCII","startwith"=>"S25");
	$commandv[]=array("devtype"=>"Play 6000F","use"=>"Whether start real time uploading history location data","command"=>"(DEVICED,3,S33,129,INQSET,1)","cmdtype"=>"ASCII","startwith"=>"S33");
	$commandv[]=array("devtype"=>"Play 6000F","use"=>"Set the history location data storage interval","command"=>"(DEVICED,3,S35,129,INQSET,60)","cmdtype"=>"ASCII","startwith"=>"S35");
	$commandv[]=array("devtype"=>"Play 6000F","use"=>"Return to factory-set","command"=>"(DEVICED,3,S44,129)","cmdtype"=>"ASCII","startwith"=>"S44");
	$commandv[]=array("devtype"=>"Play 6000F","use"=>"Cold start the terminal","command"=>"(DEVICED,3,C01,129)","cmdtype"=>"ASCII","startwith"=>"C01");
	$commandv[]=array("devtype"=>"Play 6000F","use"=>"Test over speed alarm buzzer","command"=>"(DEVICED,3,C02,129)","cmdtype"=>"ASCII","startwith"=>"C02");
	$commandv[]=array("devtype"=>"Play 6000F","use"=>"Cut off oil and power command","command"=>"(DEVICED,3,C04,129)","cmdtype"=>"ASCII","startwith"=>"C04");
	$commandv[]=array("devtype"=>"Play 6000F","use"=>"Enable oil and power","command"=>"(DEVICED,3,C05,129)","cmdtype"=>"ASCII","startwith"=>"C05");
	$commandv[]=array("devtype"=>"Play 6000F","use"=>"Remote listen-in command","command"=>"(DEVICED,3,C06,129,13710103232)","cmdtype"=>"ASCII","startwith"=>"C06");
	$commandv[]=array("devtype"=>"Play 6000F","use"=>"Customize control command","command"=>"(DEVICED,3,C07,129,INQSET,2,1,10)","cmdtype"=>"ASCII","startwith"=>"C07");
	$commandv[]=array("devtype"=>"Play 6000F","use"=>"Alarm Set command","command"=>"(DEVICED,3,A01,129,INQSET,7,1,5,1)","cmdtype"=>"ASCII","startwith"=>"A01");
	$commandv[]=array("devtype"=>"Play 6000F","use"=>"Initialization alarm Set","DEVICED"=>"(DEVICED,3,A02,123)","cmdtype"=>"ASCII","startwith"=>"A02");
	$commandv[]=array("devtype"=>"Play 6000F","use"=>"Set overtime parking alarm parameter","command"=>"(DEVICED,3,A04,129,INQSET,60,8,18)","cmdtype"=>"ASCII","startwith"=>"A04");
	$commandv[]=array("devtype"=>"Play 6000F","use"=>"Set over speed alarm indication in vehicle","command"=>"(DEVICED,3,A06,129,INQSET,1	)","cmdtype"=>"ASCII","startwith"=>"A06");
	$commandv[]=array("devtype"=>"Play 6000F","use"=>"Set fuel level change alarm parameter","command"=>"(DEVICED,3,A09,129,INQSET,1,200,5,30)","cmdtype"=>"ASCII","startwith"=>"A09");
	$commandv[]=array("devtype"=>"Play 6000F","use"=>"E20 - Inquire current connected fuel sensor List","command"=>"(DEVICED,3,E20,129,1)","cmdtype"=>"ASCII","startwith"=>"E20");
	$commandv[]=array("devtype"=>"Play 6000F","use"=>"Inquire Set Multi Fuel level change alert threshold","command"=>"(DEVICED,3,E20,123,2,INQSET,40,30)","cmdtype"=>"ASCII","startwith"=>"E20");
	$commandv[]=array("devtype"=>"Play 6000F","use"=>"Enable or Disable Refuel / Abnormal fuel consumption alarm","command"=>"(DEVICED,3,E20,123,3,INQSET,0,1)","cmdtype"=>"ASCII","startwith"=>"E20");
	$commandv[]=array("devtype"=>"Play 6000F","use"=>"Set current fuel level as empty tank","command"=>"(DEVICED,3,E20,123,4,2)","cmdtype"=>"ASCII","startwith"=>"E20");
	$commandv[]=array("devtype"=>"Play 6000F","use"=>"Set current fuel level as full tank","command"=>"(DEVICED,3,E20,123,5,2)","cmdtype"=>"ASCII","startwith"=>"E20");
	$commandv[]=array("devtype"=>"Play 6000F","use"=>"Set current fuel level as full tank","command"=>"(DEVICED,3,E20,123,6,INQSET,1)","cmdtype"=>"ASCII","startwith"=>"E20");
	$commandv[]=array("devtype"=>"Play 6000F","use"=>"Set the damping time of JT606","command"=>"(DEVICED,3,E20,123,7,INQSET,3,60)","cmdtype"=>"ASCII","startwith"=>"E20");
	$commandv[]=array("devtype"=>"Play 6000F","use"=>"Inquire or Set Fuel tank volume of each JT606","command"=>"(DEVICED,3,E20,123,12,INQSET,2,2,300,3,400)","cmdtype"=>"ASCII","startwith"=>"E20");
	$commandv[]=array("devtype"=>"Play 6000F","use"=>"Set Current connected Cameras ID","command"=>"(DEVICED,3,E21,123,1,INQSET,3,2,5,6)","cmdtype"=>"ASCII","startwith"=>"E21");
	$commandv[]=array("devtype"=>"Play 6000F","use"=>"Take photo instantly","command"=>"(DEVICED,3,E21,123,2,1,10,4,2)","cmdtype"=>"ASCII","startwith"=>"E21");
	$commandv[]=array("devtype"=>"Play 6000F","use"=>"Set triggered signal for photo taking","command"=>"(DEVICED,3,E21,123,3,INQSET,3,3,0,0,0)","cmdtype"=>"ASCII","startwith"=>"E21");	
	$commandv[]=array("devtype"=>"Play 6000F","use"=>"Set take photo in time point","command"=>"(DEVICED,3,E21,123,4,INQSET,2,2,3,30,5,480,495,510,1080,1095)","cmdtype"=>"ASCII","startwith"=>"E21");	
	$commandv[]=array("devtype"=>"Play 6000F","use"=>"Resend the image frames from tracker","command"=>"(DEVICED,3,E21,123,5,2,3,4,3,9,15,18)","cmdtype"=>"ASCII","startwith"=>"E21");	
	$commandv[]=array("devtype"=>"Play 6000F","use"=>"Acknowledge received the whole image to tracker","command"=>"(DEVICED,3,E21,123,6,2)","cmdtype"=>"ASCII","startwith"=>"E21");	
	$commandv[]=array("devtype"=>"Play 6000F","use"=>"set the phone number","command"=>"(DEVICED,3,E25,129,1,INQSET,18711093291,13045678901)","cmdtype"=>"ASCII","startwith"=>"E25");	
	

	if(@$_POST["cmd"]=="getcmd")
	{
		header('Content-type: application/json');
		echo json_encode($commandv);        
		die;
	}

	if(@$_POST["cmd"]=="getcmdapi" )
	{
		$cmdretn=array();
		
		for($ic=0;$ic<count($commandv);$ic++)
		{
			//if($commandv[$ic]["devtype"]==@$_POST["devtype"])
			if($_POST["devtype"]=="Play FM100" && $commandv[$ic]["devtype"]==@$_POST["devtype"])
			{
				$cmdretn[]=$commandv[$ic];
			}
			else 
			{
				if($_POST["devtype"]!="Play FM100" && $commandv[$ic]["devtype"]!="Play FM100")
				{
					//$commandv[$ic]["devtype"]=$commandv[$ic]["devtype"];
					$cmdretn[]=$commandv[$ic];
				}
			}
		}
		

		
		header('Content-type: application/json');
		echo json_encode($cmdretn);        
		die;
	}
	
	
	
?>