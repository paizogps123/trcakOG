//Code written & updated by VETRIVEL.N
var myambulancepick=null;
var mydiss_bookingpick=null;
function getmycuurentpic()
{
	myambulancepick=placesMarkerData.edit_ambulance_layer;
	$('#dis_live_booking_location').val(placesMarkerData.ambulance_addr);
	closeambulancepic();
}

function openambulancepic()
{
	if(gsValues.map_bussy = !0)
	{
		$('#dialog_address_search_ambulance').dialog('open'),
		$('#dialog_booking').dialog('close'),
		openmapview();
		$('#dialog_address_search_addr_ambulance').val($('#txt_b_address').val());
		placesAmbulanceNew();
		if($('#dialog_address_search_addr_ambulance').val()!='')
		utilsSearchAddress();
	}
	 placesMarkerData.edit_ambulance_layer = !1;
}

function closeambulancepic()
{
	$('#dialog_address_search_ambulance').dialog('close'),
	$('#dialog_booking').dialog('open');
	//,$("#tglDBMV").click();	
	if(typeof(placesMarkerData.edit_ambulance_layer)!="undefined")
    {
     map.removeLayer(placesMarkerData.edit_ambulance_layer), map.off("click")
    }
    placesMarkerData.edit_ambulance_layer = !1,
    gsValues.map_bussy = !1;
}

function placesAmbulanceNew() {
    if(1 != gsValues.map_bussy )
    {gsValues.map_bussy = !0;
    }
    map.on("click", placesAmbulanceAddToMapByClick);
}

function placesAmbulanceAddToMapByClick(e) {
    map.removeLayer(placesMarkerData.edit_ambulance_layer),
    geocoderGetAddress(e.latlng.lat,e.latlng.lng, function(a) {
        var o = a,
            i = urlPosition(e.latlng.lat, e.latlng.lng),
            s = "<table><tr><td><strong>" + la.ADDRESS + ":</strong></td><td>" + o + "&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>					<tr><td><strong>" + la.POSITION + ":</strong></td><td>" + i + "</td></tr>					</table>";
        addPopupToMap(e.latlng.lat,e.latlng.lng, [0, 0], s, ""), map.panTo({
            lat: e.latlng.lat,
            lng: e.latlng.lng
        }),
        placesAmbulanceAddToMap(e.latlng.lat, e.latlng.lng,o)
    })
    
}

function placesAmbulanceAddToMap(e, t,add) {
    var s = settingsUserData.map_is,
        o = L.icon({
            iconUrl: "img/markers/places/pin-4.svg",
            iconSize: [28, 28],
            iconAnchor: [14, 28],
            popupAnchor: [0, 0]
        });
    placesMarkerData.edit_ambulance_layer = L.marker([e, t], {
        icon: o
    }),
    placesMarkerData.edit_ambulance_layer.addTo(map),
    placesMarkerData.ambulance_addr=add;
}

var timer_booking;
var prevbooking=undefined;
var prev_ary_vetri;
function BookingRefresh() {
    clearTimeout(timer_booking), timer_booking = setTimeout("BookingRefresh();", 10* 1000),booking_search(prevbooking)
}

function booking_search(state)
{
	if(typeof(state)=="undefined")
	{
		state="Select";
	}
	
	if(prevbooking!="Finished" && prevbooking!="Canceled" || (prevbooking!=state))
	{
		prevbooking=state;
		$("#amb_booking_list_grid").jqGrid("clearGridData");
		$("#amb_booking_list_grid").setGridParam({url: "func/fn_ambulance.php?cmd=select_booking&status="+state+"&from="+$("#txt_b_datefrom").val()+"&to="+$("#txt_b_dateto").val() });
		$("#amb_booking_list_grid").trigger("reloadGrid");
	}
	
}

function booking_allocate(id)
{
	$("#dialog_booking_vehicle").dialog("open");	
	$("#amb_booking_vehicle_list_grid").jqGrid("clearGridData");
	$("#amb_booking_vehicle_list_grid").setGridParam({url: "func/fn_ambulance.php?cmd=select_vehicle_list&bookingid="+id });
    $("#amb_booking_vehicle_list_grid").trigger("reloadGrid");
}

function allocateto_booking(id)
{
	var myGrid = $('#amb_booking_vehicle_list_grid');
	var rowData = myGrid.jqGrid("getRowData", id);
	
	confirmDialog("Are you want to allocate this vehicle for this patient? ", function(ve) {
	if (ve) {	
	 var a = {
             cmd: "allocate_to_driver",bookingid:rowData["bid"]
	 		,driver_id:rowData["uid"],driver_phone:rowData["phone"],driver_imei:rowData["imei"],driver_vehicle:rowData["vname"]
         };
         $.ajax({
             type: "POST",
             url: "func/fn_ambulance.php",
             data: a,
             success: function(e) {
                 switch (e) {
                     default: var t = "error",
                         a = la.ERROR,
                         s = e;notifyBox(t, a, s);
                     break;
                     case "OK":
                    	  a = la.INFO,
                          s = e;notifyBox(t, a, la.CHANGES_SAVED_SUCCESSFULLY);
                          $("#dialog_booking_vehicle").dialog("close");
                          booking_search();
                     break;
                 }
             }
         });
  	}
})
}

function bookingduplicate(id)
{	
	confirmDialog("Are you want to add patient(Duplicate)? ", function(ve) {
    if (ve) {
	 var a = {
             cmd: "duplicate",bookingid:id};
         $.ajax({
             type: "POST",
             url: "func/fn_ambulance.php",
             data: a,
             success: function(e) {
                 switch (e) {
                     default: var t = "error",
                         a = la.ERROR,
                         s = e;notifyBox(t, a, s);
                     break;
                     case "OK":
                    	  a = la.INFO,
                          s = e;notifyBox(t, a, la.CHANGES_SAVED_SUCCESSFULLY);
                          booking_search();
                     break;
                 }
             }
         });
  	}
 })
}

function cancel_booking(id)
{
	confirmDialog("Are you want to cancel this emergency? ", function(ve) {
	if (ve) 
  	{
	var vreason;
	if(vreason=prompt("Please enter your reason to cancel this emergency?"))
		{
	 var a = {cmd: "cancel_booking",bookingid:id,reason:vreason};
         $.ajax({
             type: "POST",
             url: "func/fn_ambulance.php",
             data: a,
             success: function(e) {
                 switch (e) {
                     default: var t = "error",
                         a = la.ERROR,
                         s = e;notifyBox(t, a, s);
                     break;
                     case "OK":
                    	  a = la.INFO,
                          s = e;notifyBox(t, a, la.CHANGES_SAVED_SUCCESSFULLY);
                          $("#dialog_booking_vehicle").dialog("close");
                          booking_search();
                     break;
                 }
             }
         });
		}
  	}
})
}


function booking_change_wait(id)
{
	confirmDialog("Are you want to change status as Waiting? ", function(ve) {
	if (ve) 
		  	{
	 var a = {cmd: "cange_status_wait",bookingid:id};
         $.ajax({
             type: "POST",
             url: "func/fn_ambulance.php",
             data: a,
             success: function(e) {
                 switch (e) {
                     default: var t = "error",
                         a = la.ERROR,
                         s = e;notifyBox(t, a, s);
                     break;
                     case "OK":
                    	  a = la.INFO,
                          s = e;notifyBox(t, a, la.CHANGES_SAVED_SUCCESSFULLY);
                          $("#dialog_booking_vehicle").dialog("close");
                          booking_search();
                     break;
                 }
             }
         });
  	}
	})
}

function checkisBooking()
{
	
}

function bookingdelete(id)
{
	confirmDialog("Are you want to delete? ", function(ve) {
	if (ve) {
	 var a = {
             cmd: "delete_booking",bookingid:id,reason:""
         };
         $.ajax({
             type: "POST",
             url: "func/fn_ambulance.php",
             data: a,
             success: function(e) {
                 switch (e) {
                     default: var t = "error",
                         a = la.ERROR,
                         s = e;notifyBox(t, a, s);
                     break;
                     case "OK":
                    	  a = la.INFO,
                          s = e;notifyBox(t, a, la.CHANGES_SAVED_SUCCESSFULLY);
                          clearbooking();
                          booking_search();
                     break;
                 }
             }
         });
  	}
	})
}

var vbookingidedit=0;
function bookingedit(id)
{
	$("#btn_b_book").val(la.UPDATE);
	vbookingidedit=id;
	var myGrid = $('#amb_booking_list_grid');
	var rowData = myGrid.jqGrid("getRowData", id);
	 $("#txt_b_address").val(rowData["emergency_address"]);
	 $("#txt_b_peoplecount").val(rowData["people_count"]);
	 $("#ddl_b_breathing").val(rowData["breathing"]);
	 $("#txt_b_phone").val(rowData["contact_no"]);
	 $("#txt_b_age").val(rowData["age"]);
	 $("#ddl_b_gender").val(rowData["gender"]);
	 $("#ddl_b_emergency").val(rowData["emergency_reason"]);
	 $("#ddl_b_conscious").val(rowData["conscious"]);
	 $("#txt_b_pateintname").val(rowData["person_name"]);
	 $("#txt_b_note").val(rowData["note1"]);
	 $("#txt_b_note2").val(rowData["note2"]);
	 myambulancepick=null;
}

function saveb_booking()
{
	if($("#txt_b_address").val()=="" || $("#txt_b_phone").val()=="")
	{
		var t = "error",
		a = la.ERROR;
		notifyBox(t, a, la.PLEASE_ENTER_PRIMARY_TO_BOOK);
		return;
	}
	var latv="",lngv="";
	if(myambulancepick==null || myambulancepick==false)
	{
		//var t = "error",
		//a = la.ERROR;
		//notifyBox(t, a, la.LOCATION_NOT_VALID);
		//return;
	}
	else
	{
		latv=myambulancepick.getLatLng().lat.toFixed(6);	
		lngv=myambulancepick.getLatLng().lng.toFixed(6);
	}
	
	 var a = {
             cmd: "save_booking",
             emergencyaddress:$("#txt_b_address").val(),
             contactno:$("#txt_b_phone").val(),
             emergencyreason:$("#ddl_b_emergency").val(),
             peoplecount:$("#txt_b_peoplecount").val(),
             age:$("#txt_b_age").val(),
             conscious:$("#ddl_b_conscious").val(),
             breathing:$("#ddl_b_breathing").val(),
             gender:$("#ddl_b_gender").val(),
             personname:$("#txt_b_pateintname").val(),
             note1:$("#txt_b_note").val(),
             note2:$("#txt_b_note2").val(),
             lat:latv,
             lng:lngv,
             bookingid:vbookingidedit
         };
         $.ajax({
             type: "POST",
             url: "func/fn_ambulance.php",
             data: a,
             success: function(e) {
                 switch (e) {
                     default: var t = "error",
                         a = la.ERROR,
                         s = e;notifyBox(t, a, s);
                     break;
                     case "OK":
                    	  a = la.INFO,
                          s = e;notifyBox(t, a, la.CHANGES_SAVED_SUCCESSFULLY);
                          clearbooking();
                          booking_search();
                     break;
                 }
             }
         });
}

var flag="";	
var vehidata=null;
function onlineofflineget( graph ) {
    flag=graph.legendTextReal;
	fnloadgrid('');
	$('#dall').dialog('open');
 }

function speednormalget( graph ) {
    flag=graph.legendTextReal;
	fnloadgrid('');
	$('#dall').dialog('open');
 }

function OpenBooking() {
	$('#dialog_booking').dialog('open');
 }

function clearbooking() {
	
	 $("#btn_b_book").val(la.BOOK_NOW);
	 $("#txt_b_address").val("");
	 $("#txt_b_peoplecount").val("");
	 $("#ddl_b_breathing").val("Yes");
	 $("#txt_b_phone").val("");
	 $("#txt_b_age").val("");
	 $("#ddl_b_gender").val("Select");
	 $("#ddl_b_emergency").val("Select");
	 $("#ddl_b_conscious").val("Yes");
	 $("#txt_b_pateintname").val("");
	 $("#txt_b_note").val("");
	 $("#txt_b_note2").val("");
	 vbookingidedit=0;
 }

function refreshdbdata()
{
	flag='';
	fnloadgrid('');	
}

function dwnlodrpt()
{
	 // $.generateFile({ filename: "VehicleDetails.xls",content: $("#divlive").html(),script: "func/fn_saveas.php?format=html"});
	if(vehidata!=null)
		  JSONToCSVConvertor(vehidata, "Vehicle Report_"+flag, true);
		else
			notifyBox("error", la.ERROR, la.PLSFILLALLDETAILS);
}


function JSONToCSVConvertor(JSONData, ReportTitle, ShowLabel) {
    //If JSONData is not an object then JSON.parse will parse the JSON string in an Object
    var arrData = typeof JSONData != 'object' ? JSON.parse(JSONData) : JSONData;
    
    var CSV = '';    
    //Set Report title in first row or line
    
    CSV += ReportTitle + '\r\n\n';

    //This condition will generate the Label/Header
    if (ShowLabel) {
        var row = "";
        
        //This loop will extract the label from 1st index of on array
        for (var index in arrData[0]) {
            
            //Now convert each value to string and comma-seprated
        	if(index!="i1" && index!="i2" && index!="i3")
            row += index + ',';
        }

        row = row.slice(0, -1);
        
        //append Label row with line break
        CSV += row + '\r\n';
    }
    
    //1st loop is to extract each row
    for (var i = 0; i < arrData.length; i++) {
        var row = "";
        
        //2nd loop will extract each column and convert it in string comma-seprated
        for (var index in arrData[i]) {
        	if(index!="i1" && index!="i2" && index!="i3")
            row += '"' + arrData[i][index] + '",';
        }

        row.slice(0, row.length - 1);
        
        //add a line break after each row
        CSV += row + '\r\n';
    }

    if (CSV == '') {   
    	notifyDialog("Invalid data");
        return;
    }   
    
    //Generate a file name
    var fileName = "";
    //this will remove the blank-spaces from the title and replace it with an underscore
    fileName += ReportTitle.replace(/ /g,"_");   
    
    //Initialize file format you want csv or xls
    var uri = 'data:text/csv;charset=utf-8,' + escape(CSV);
    
    // Now the little tricky part.
    // you can use either>> window.open(uri);
    // but this will not work in some browsers
    // or you will not get the correct file extension    
    
    //this trick will generate a temp <a /> tag
    var link = document.createElement("a");    
    link.href = uri;
    
    //set the visibility hidden so it will not effect on your web-layout
    link.style = "visibility:hidden";
    link.download = fileName + ".csv";
    
    //this part will append the anchor tag and remove it after automatic click
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
		
function dashbinddata(vch)
{
	 $("#newdashboarddtl").find("tr:gt(0)").remove();
	  vch=vch.sort(function(a, b){
          var a1= a["Speed"], b1= b["Speed"];
          if(a1== b1) return 0;
          return a1< b1? 1: -1;
      	});
    for (var h =0;h<vch.length;h++) 
    {
	  var newRow = document.getElementById("newdashboarddtl").insertRow();
      newRow.insertCell().innerHTML = document.getElementById("newdashboarddtl").rows.length - 1;
      newRow.insertCell().innerHTML = vch[h]["i1"];
      newRow.insertCell().innerHTML = vch[h]["Model"];
      newRow.insertCell().innerHTML = vch[h]["Time"];
      newRow.insertCell().innerHTML = vch[h]["i2"];
      newRow.insertCell().innerHTML = vch[h]["i3"];
      newRow.insertCell().innerHTML = vch[h]["ACI"];
      newRow.insertCell().innerHTML = vch[h]["Speed"];
      newRow.insertCell().innerHTML = vch[h]["Fuel_Level"];
      newRow.insertCell().innerHTML = vch[h]["Overspeed_Count"];
      newRow.insertCell().innerHTML = vch[h]["Route_Length"];
      newRow.insertCell().innerHTML = vch[h]["Last_Event"];
      newRow.insertCell().innerHTML = vch[h]["Nearest_Zone"];          
    }
}

var vch;
function fnloadgrid(type)
{
        groupid='';
        if (typeof(type) == "undefined")type="";
        
        var chartData = [ {
              "country": "USA",
              "visits": 4025,
              "color": "#FF0F00"
            }];
            
        getnotification();
        var path="func/dashboard.php?cmd=liveroute&condition="+flag+"&type="+type+"&group_id="+groupid;
        $.ajax({
            type: "POST",
            datatype: "json",
            url: path,
            cache: false,
            success: function(c) {
            //console.log(c);
            var vehicletitile=document.getElementById("demo").innerHTML = flag;
            $('#eventcount').html(c.todayeventstot);
            $('#reportdownoadcount').html(c.todayreports);
            $('#emailsendcount').html(c.todayemails);
            var vonline=parseInt(c.online);
            var voffline=parseInt(c.offline);
            var moffline=parseInt(c.mai_offline);
            $("#spantotalvehicle").text(vonline+voffline+moffline);
            var vnormal=c.normal;
            var voverspeed=c.overspeed;
            $("#spanonlinevehicle").text(vonline);
             $("#spanofflinevehicle").text(voffline);
             $("#spanmaintenancevehicle").text(moffline);
             // $("#spannormalspeedvehicle").text(vnormal);
             $("#spanoverspeedvehicle").text(voverspeed);
            vehidata=c.content;
            var f = $("#dashboard_detail");
            f.clearGridData(true);
            vch=vehidata;
            dashbinddata(vch);
            fnloadeventDB(c.event,c.sosalert);
            f.setGridParam({ shrinkToFit:false,
            forceFit:true,width : "1200px"}).trigger("reloadGrid")
            $('#grid_container div:not(.ui-jqgrid-titlebar)').width("100%");    
                //$("#divlive").html(c.content);
                $("#divlive").css({"display":"block"});
                //setTimeout("fnloadgrid()",90000);       
                $("#spantemp").text(c.tempabnormal);
                   var chart = AmCharts.makeChart( "chartonline", {
                          "type": "pie",
                          "theme": "none",
                          "innerRadius": "50%",
                          "gradientRatio": [ -0.4, -0.4, -0.4, 0, 0.1, 0.2, 0.1, 0],

                          "legend":{
                            "position":"bottom",
                            "marginRight":100,
                            "autoMargins":false,
                            "horizontalGap": 10,
                            "clickMarker": onlineofflineget,
                            "clickLabel": onlineofflineget,
                            "useMarkerColorForLabels":'true',
                            "useMarkerColorForValues":'true'
                          },
                          "dataProvider": [ {
                            "title": "Online Vehicles",
                            "value": vonline
                          }, {
                            "title": "Offline Vehicles",
                            "value": voffline
                          },{
                            "title": "Maintenance Vehicle",
                            "value": moffline
                          }, {
                            "title": "Over Speed",
                            "value": voverspeed
                          }
                          ],
                          "balloonText": "[[value]]",
                          "titleField": "title",
                          "valueField": "value",
                          "labelRadius": 30,
                         
                          "radius": "40%",
                          // "innerRadius": "0%",
                          // "angle": 30,
                          // "depth3D": 0,
                          "labelText": "",
                          "colorField": "color",
                          "balloon": {
                        "drop": true,
                        "adjustBorderColor": false,
                        "color": "#FFFFFF",
                        "fontSize": 16
                        },
                          "export": {
                            "enabled": true
                          }
                        //       var chart = AmCharts.makeChart("chartonline", {
                        // "type": "pie",
                        // "theme": "none",
                        // "innerRadius": "40%",
                        // "gradientRatio": [-0.4, -0.4, -0.4, -0.4, -0.4, -0.4, 0, 0.1, 0.2, 0.1, 0, -0.2, -0.5],
                        
                        // "dataProvider": [{
                        //     "title": "Online Vehicles",
                        //     "value": 5
                        // }, {
                        //     "title": "Offline Vehicles",
                        //     "value": 10
                        // }],
                        // "balloonText": "[[value]]",
                        // "valueField": "value",
                        // "titleField": "title",
                        // "balloon": {
                        //     "drop": true,
                        //     "adjustBorderColor": false,
                        //     "color": "#FFFFFF",
                        //     "fontSize": 16
                        // },
                        // "export": {
                        //     "enabled": true
                        // }
                        ,
                          "titles": [{"text": "VEHICLE STATUS","size": 12,color:"#258ecd"}]
                          
                        } );
                
                   chart.dataProvider[0].color = "#99b958";
                    chart.dataProvider[1].color = "#f5456d";
                    chart.dataProvider[2].color = "#5cabf5";
                    chart.dataProvider[3].color = "#ff8533";
                    chart.validateData();

                chart.addListener("init", handleInit);
             
                chart.addListener("rollOverSlice", function(e) {
                  handleRollOver(e);
                });

                function handleInit(){
                  chart.legend.addListener("rollOverItem", handleRollOver);
                }

                function handleRollOver(e){
                    try{
                  var wedge = e.dataItem.wedge.node;
                  wedge.parentNode.appendChild(wedge);  
                    }catch(e){}
                }
                
                var chart1 = AmCharts.makeChart( "chartoverspeed", {
                  "type": "pie",
                  "theme": "none"
                ,
                "legend":{
                    "position":"bottom",
                    "marginRight":100,
                    "autoMargins":false,
                    "horizontalGap": 10,
                    "clickMarker": speednormalget,
                    "clickLabel": speednormalget,
                    "useMarkerColorForLabels":'true',
                    "useMarkerColorForValues":'true'
                  },
                  "dataProvider": [ {
                    "title": "Normal Speed",
                    "value": vnormal
                  }, {
                    "title": "Over Speed",
                    "value": voverspeed
                  } ],
                  "titleField": "title",
                  "valueField": "value",
                  "labelRadius": 30,

                  "radius": "42%",
                  "innerRadius": "0%",
                  "angle": 30,
                  "depth3D": 8,
                  "labelText": "",
                  "colorField": "color",
                  "export": {
                    "enabled": true
                  }
                ,
                  "titles": [{"text": "OVERSPEED VEHICLE","size": 12,color:"#258ecd"}]
                } );
                chart1.dataProvider[0].color = "#6b5b48";
                chart1.dataProvider[1].color = "#f55f5d";
                chart1.validateData();
                
                chart1.addListener("init", handleInit);
                chart1.addListener("rollOverSlice", function(e) {
                    handleRollOver(e);
                  });

                  function handleInit1(){
                    chart1.legend.addListener("rollOverItem", handleRollOver);
                  }
                  document.getElementById("loading_panel").style.display = "none";
                  
                  setTimeout("fnloadgrid();", 300*1000);
            },
            error: function(c, d) {flag="";}
        });
}




function loadtripdata()
{  
	$.ajax({
        type: "POST",
        datatype: "json",
        url: "func/dashboard.php?cmd=tripdata",
        cache: false,
        success: function(c) {
  
        var f = $("#trip_detail");
        f.clearGridData(true);
        for (var h =0;h<c.length;h++) 
        {
         f.jqGrid("addRowData", (h), {
        	 		VNO:c[h][1].toLowerCase(),
        	 		SINO: c[h][0],
        	 		OBJECT: c[h][1],
        	 		TRIPNAME: c[h][3],
        	 		HOTSPOTNAME: c[h][4],
        	 		DATE: c[h][5],
        	 		ROUTE_START: c[h][6],
        	 		ROUTE_END: c[h][7],
        	 		PROUTE_START: c[h][8],
        	 		PROUTE_END: c[h][9],
        	 		AROUTE_START: c[h][10],
        	 		AROUTE_END:c[h][11],
        	 		AVG_SPEED:c[h][12],
        	 		DELAY:c[h][13],
        	 		TAKENKM:c[h][14],
        	 		DURATION:c[h][15],
        	 		OVERSPEED_COUNT:c[h][16],
        	 		OVRTIMEPARKING:c[h][17]
                });
 
        }
       
        f.setGridParam({ shrinkToFit:false,
            forceFit:true,width : "1200px"}).trigger("reloadGrid");
   
        },
        error: function(c, d) {}
    });
	
	  $("#dialog_tripdata").dialog("open");
}





	

var vboarding=[];
var vboardingdept=null;
vboarding.deptcode=0;
vboarding.sectioncode=0;
vboarding.holidaycode=0;
vboarding.hotspotcode=0;
vboarding.studentcode=0;
vboarding.allocatecode=0;
vboarding.allocatecodedaily=0;
var veprow="";


function boardingOpen() {
    $("#dialog_boarding").dialog("open");
    initSelectList("allocate_sound_list");
    hide1column();
  
   	var af = document.getElementById("dialog_studentadd_sectionddlsearch");
    var aa = document.getElementById("dialog_studentadd_sectionddl");
           
    af.options.length = 0;
    af.options.add(new Option("Select"));
            
    aa.options.length = 0;
    aa.options.add(new Option("Select"));
   
 }
 
 function boardingPlaySound() {
    var a = document.getElementById("dialog_boarding_allocate_notify_system_sound").value;
    var b = new Audio("snd/" + a);
    b.play();
}
   
  function boardingPlaySounddaily() {
     var a = document.getElementById("dialog_boarding_allocate_notify_system_sounddaily").value;
     var b = new Audio("snd/" + a);
     b.play();
 }
  
  //boarding daily start
  
  

  function boeardingallocatedaily(savecancel)
  {
  	if(savecancel=="save")
  	{
  		
  		var datefrom = $("#dialog_allocate_date_fromdaily").val();
  	 	var dateto = $("#dialog_allocate_date_todaily").val();
  	 	
  	   	var tfh = $("#dialog_allocate_hour_fromdaily").val();
  	 	var tfm = $("#dialog_allocate_minute_fromdaily").val();
  	 	var tth = $("#dialog_allocate_hour_todaily").val();
  	 	var ttm = $("#dialog_allocate_minute_todaily").val();
  	 	
  	 	
  		if($("#dialog_allocate_date_fromdaily").val()=="" || $("#dialog_allocate_date_todaily").val()=="")
  		{
  			 notifyBox("error", la.ERROR, la.PLSENTERVALIDTIME);
			 return ;
  		}
  	
  		
  		if($("#dialog_boarding_tripnamedaily").val()=="")
  		{
  			 notifyBox("error", la.ERROR, la.PLSTRIPNAME);
  			 return ;
  		}
  		
  		if($("#dialog_boarding_addobjectdaily  option:selected").val()=="Select" || $("#dialog_boarding_addhotspotdaily  option:selected").val()=="Select")
  		{
  			 notifyBox("error", la.ERROR, la.PLSOBJECTORHOTSPOT);
  			 return ;
  		}
  		
  	
  		   var vact =String(document.getElementById("dialog_boarding_allocate_activedaily").checked);
  		    
           var vholi = String(document.getElementById("dialog_boarding_allocate_activeholidaydaily").checked);
           
           var w = String(document.getElementById("dialog_boarding_allocate_notify_systemdaily").checked);
           var g = String(document.getElementById("dialog_boarding_allocate_notify_system_hidedaily").checked);
           var B = String(document.getElementById("dialog_boarding_allocate_notify_system_soundchkboxdaily").checked);
           var F = String(document.getElementById("dialog_boarding_allocate_notify_system_sounddaily").value);
           w = w + "," + g + "," + B + "," + F;
  		
  		   var q = String(document.getElementById("dialog_boarding_allocate_notify_emaildaily").checked);
           var r = document.getElementById("dialog_boarding_allocate_notify_email_addressdaily").value;
              if (q == true) {
                  var p = r.split(",");
                  for (var y = 0; y < p.length; y++) {
                      p[y] = p[y].trim();
                      if (!isEmailValid(p[y])) {
                          notifyBox("error", la.ERROR, la.THIS_EMAIL_IS_NOT_VALID);
                          return false;
                      }
                  }
              }
           
           var t = String(document.getElementById("dialog_boarding_allocate_notify_smsdaily").checked);
           var A = document.getElementById("dialog_boarding_allocate_notify_sms_numberdaily").value;
           
           var imei = document.getElementById("dialog_boarding_addobjectdaily").value;
           var route_id = document.getElementById("dialog_boarding_addhotspotdaily").value;
           
      
  		
  		   var dataaa = {
                  cmd: "save_allocatedaily",
                  event_id: vboarding.allocatecodedaily,
                  active: vact,
                  dontholi: vholi,
                  imei: imei,
                  route_id: route_id,
                  notify_system: w,
                  notify_email: q,
                  notify_email_address: r,
                  notify_sms: t,
                  notify_sms_number: A,
                  date_from:datefrom,
                  date_to:dateto,
                  tfh:tfh,
                  tfm:tfm,
                  tth:tth,
                  ttm:ttm,
                  tripname:$("#dialog_boarding_tripnamedaily").val()
              };
  		   
  		   $.ajax({
                  type: "POST",
                  url: "func/fn_boarding.php",
                  data: dataaa,
                  success: function(o) {
                      switch (o) {
                        
                          case "NO":
                           var p = "error";
                          var r = la.ERROR;
                          var q = la.DIFFOBJECTORHOTSPOT;
                          notifyBox(p, r, q);
                          break;
                          case "OK":
                          boeardingallocatedaily('cancel');
                       	boeardingallocatedaily('search');
                       	break;
                       	default:
                          var p = "error";
                          var r = la.ERROR;
                          notifyBox(p, r, o);
                          break;
                      }                 
                  }
              });
  	}
  	else if(savecancel =="cancel")
  	{
  		document.getElementById("dialog_boarding_allocate_activedaily").checked = true;
  		
  		
  		document.getElementById("dialog_boarding_allocate_activeholidaydaily").checked = true;
  		
  		document.getElementById("dialog_boarding_allocate_notify_systemdaily").checked = false;
  		document.getElementById("dialog_boarding_allocate_notify_system_hidedaily").checked = false;
  		document.getElementById("dialog_boarding_allocate_notify_system_soundchkboxdaily").checked = false;
  		document.getElementById("dialog_boarding_allocate_notify_emaildaily").checked = false;
  		document.getElementById("dialog_boarding_allocate_notify_smsdaily").checked = false;
  		
  		document.getElementById("dialog_boarding_allocate_notify_email_addressdaily").value="";
  	  	document.getElementById("dialog_boarding_allocate_notify_sms_numberdaily").value="";
  		
  		//document.getElementById("dialog_boarding_addobject").value="Select";
  	  	document.getElementById("dialog_boarding_addhotspotdaily").value="Select";

  	  	
  	  	$("#dialog_allocate_date_fromdaily").val("");
	 	$("#dialog_allocate_date_todaily").val("");
	 	
  	  	$("#txtdaysintervaldaily").val("1");
  	  	$("#dialog_boarding_tripnamedaily").val("");
  	 	$("#dialog_allocate_hour_fromdaily").val("00");
  	 	$("#dialog_allocate_minute_fromdaily").val("00");
  	 	
  	 	$("#dialog_allocate_hour_todaily").val("23");
  	 	$("#dialog_allocate_minute_todaily").val("00");

  	 	vboarding.allocatecodedaily=0;
  	 	boeardingallocatedaily('search');
  	 	$("#dialog_boarding_allocate_adddaily").dialog("close");
  	}
  	else if(savecancel =="search")
  	{  	
  		   $("#boarding_allocate_list_grid_daily").jqGrid("clearGridData");
  		   $("#boarding_allocate_list_grid_daily").setGridParam({url: "func/fn_boarding.php?cmd=select_allocatedaily&object_id="+ document.getElementById("dialog_boarding_addobjectdaily").value });
           $("#boarding_allocate_list_grid_daily").trigger("reloadGrid");
  	}
  	
  	
  } 
  
  function boardingallocateditdaily(deptcode)
  {
	
	  
  	if($("#dialog_boarding_addobjectdaily  option:selected").val()!="Select" )
  	 {
  		 boeardingallocate("cancel");
  		 $("#lbl_boarding_object_namedaily").text($("#dialog_boarding_addobjectdaily  option:selected").text());
  	 }
  	 else
      notifyBox("error", la.ERROR, la.PLSOBJECT);
  	
  	  $("#dialog_boarding_allocate_adddaily").dialog("open");
  	
  	   var dataaa = { cmd: "edit_allocatedaily", event_id: deptcode};
  	  	$.ajax({
                  type: "POST",
                  url: "func/fn_boarding.php",
                  data: dataaa,
                  success: function(o) {
                          var v1=o["rows"][0][0].active;
                      	vboarding.allocatecodedaily=deptcode;
  	 	document.getElementById("dialog_boarding_allocate_activedaily").checked = strToBoolean(o["rows"][0][0]["active"]);
  		
  		
  		var notisys=[];
  		notisys=o["rows"][0][0]["notify_system"].split(',');
  	
  		document.getElementById("dialog_boarding_allocate_activeholidaydaily").checked = strToBoolean(o["rows"][0][0]["dontholiday"]);
  		
  		document.getElementById("dialog_boarding_allocate_notify_systemdaily").checked = strToBoolean(notisys[0]);
  		document.getElementById("dialog_boarding_allocate_notify_system_hidedaily").checked = strToBoolean(notisys[1]);
  		document.getElementById("dialog_boarding_allocate_notify_system_soundchkboxdaily").checked = strToBoolean(notisys[2]);
  		document.getElementById("dialog_boarding_allocate_notify_system_sounddaily").value=notisys[3];
  		
  		document.getElementById("dialog_boarding_allocate_notify_emaildaily").checked = strToBoolean(o["rows"][0][0]["notify_email"]);
  		document.getElementById("dialog_boarding_allocate_notify_smsdaily").checked = strToBoolean(o["rows"][0][0]["notify_sms"]);
  		
  		document.getElementById("dialog_boarding_allocate_notify_email_addressdaily").value=o["rows"][0][0]["notify_email_address"];
  	  	document.getElementById("dialog_boarding_allocate_notify_sms_numberdaily").value=o["rows"][0][0]["notify_sms_number"];
  		
  		document.getElementById("dialog_boarding_addobjectdaily").value=o["rows"][0][0]["imei"];
  	  	document.getElementById("dialog_boarding_addhotspotdaily").value=o["rows"][0][0]["route_id"];

  	  	
  	  	
  	  	$("#dialog_boarding_tripnamedaily").val(o["rows"][0][0]["tripname"]);
  	  	
  		$("#dialog_allocate_date_fromdaily").val(o["rows"][0][0]["date_from"]);
  	 	$("#dialog_allocate_date_todaily").val(o["rows"][0][0]["date_to"]);
  	 	
  	 	$("#dialog_allocate_hour_fromdaily").val(o["rows"][0][0]["tfh"]);
  	 	$("#dialog_allocate_minute_fromdaily").val(o["rows"][0][0]["tfm"]);
  	 	
  	 	$("#dialog_allocate_hour_todaily").val(o["rows"][0][0]["tth"]);
  	 	$("#dialog_allocate_minute_todaily").val(o["rows"][0][0]["ttm"]);
  	 	

  	  					}
              });
  	
  }

  function boardingallocatedeletedaily(deptcode)
  {
	confirmDialog("Are you want to delete? ", function(ve) {
	if (ve) {
  	 var dat = {cmd: "delete_allocatedaily",uid:deptcode};
  		$.ajax({
                  type: "POST",
                  url: "func/fn_boarding.php",
                  data: dat,
                  success: function(o) {
                      switch (o) {
                          default: var p = "error";
                          var r = la.ERROR;
                          var q = o;notifyBox(p, r, q);
                          boeardingallocatedaily('cancel');
                          boeardingallocatedaily('search');
                          break;
                          case "OK":
                          boeardingallocatedaily('cancel');
                          boeardingallocatedaily('search');
                          break;
                      }                 
                  }
              });
     }
	})
  }
  
  //boarding daily end


//boarding begin

function boeardingallocate(savecancel)
{
	if(savecancel=="save")
	{
	   	var tfh = $("#dialog_allocate_hour_from").val();
	 	var tfm = $("#dialog_allocate_minute_from").val();
	 	var tth = $("#dialog_allocate_hour_to").val();
	 	var ttm = $("#dialog_allocate_minute_to").val();
	 	
	 	
		if(($("#dialog_allocate_hour_to_day").val()=="Same") && parseInt($("#txtdaysinterval").val())<=1)
		{
			if((tfh+"."+tfm)>(tth+"."+ttm))
			{
				 notifyBox("error", la.ERROR, la.PLSENTERVALIDTIME);
				 return ;
			}	
		}
		
		if(parseInt($("#txtdaysinterval").val())<1 || parseInt($("#txtdaysinterval").val())>5 )
		{
			 //notifyBox("error", la.ERROR, la.PLEASE_SELECT_WITHIN_5_DAYS);
			 //return ;
		}
		
		if($("#dialog_boarding_tripname").val()=="")
		{
			 notifyBox("error", la.ERROR, la.PLSTRIPNAME);
			 return ;
		}
		
		if($("#dialog_boarding_addobject  option:selected").val()=="Select" || $("#dialog_boarding_addhotspot  option:selected").val()=="Select")
		{
			 notifyBox("error", la.ERROR, la.PLSOBJECTORHOTSPOT);
			 return ;
		}
		
	
		 var vact =String(document.getElementById("dialog_boarding_allocate_active").checked);
		 var e='';
		 
		if(		document.getElementById("dialog_boarding_allocate_wd_sun").checked ==false &&
				document.getElementById("dialog_boarding_allocate_wd_mon").checked ==false &&
				document.getElementById("dialog_boarding_allocate_wd_tue").checked ==false &&
				document.getElementById("dialog_boarding_allocate_wd_wed").checked ==false &&
				document.getElementById("dialog_boarding_allocate_wd_thu").checked ==false &&
				document.getElementById("dialog_boarding_allocate_wd_fri").checked ==false &&
				document.getElementById("dialog_boarding_allocate_wd_sat").checked ==false)
		{
			 notifyBox("error", la.ERROR, la.PLSSELECTATLEASTONEDAY);
			 return ; 
		}
			 			 
         e = String(document.getElementById("dialog_boarding_allocate_wd_sun").checked) + ";";
         e += String(document.getElementById("dialog_boarding_allocate_wd_mon").checked) + ";";
         e += String(document.getElementById("dialog_boarding_allocate_wd_tue").checked) + ";";
         e += String(document.getElementById("dialog_boarding_allocate_wd_wed").checked) + ";";
         e += String(document.getElementById("dialog_boarding_allocate_wd_thu").checked) + ";";
         e += String(document.getElementById("dialog_boarding_allocate_wd_fri").checked) + ";";
         e += String(document.getElementById("dialog_boarding_allocate_wd_sat").checked) + ";";
         
         
         var vholi = String(document.getElementById("dialog_boarding_allocate_activeholiday").checked);
         
         var w = String(document.getElementById("dialog_boarding_allocate_notify_system").checked);
         var g = String(document.getElementById("dialog_boarding_allocate_notify_system_hide").checked);
         var B = String(document.getElementById("dialog_boarding_allocate_notify_system_soundchkbox").checked);
         var F = String(document.getElementById("dialog_boarding_allocate_notify_system_sound").value);
         w = w + "," + g + "," + B + "," + F;
		
		 var q = String(document.getElementById("dialog_boarding_allocate_notify_email").checked);
         var r = document.getElementById("dialog_boarding_allocate_notify_email_address").value;
            if (q == true) {
                var p = r.split(",");
                for (var y = 0; y < p.length; y++) {
                    p[y] = p[y].trim();
                    if (!isEmailValid(p[y])) {
                        notifyBox("error", la.ERROR, la.THIS_EMAIL_IS_NOT_VALID);
                        return false;
                    }
                }
            }
         
         var t = String(document.getElementById("dialog_boarding_allocate_notify_sms").checked);
         var A = document.getElementById("dialog_boarding_allocate_notify_sms_number").value;
         
         var imei = document.getElementById("dialog_boarding_addobject").value;
         var route_id = document.getElementById("dialog_boarding_addhotspot").value;
         
    
		
		 var dataaa = {
                cmd: "save_allocate",
                event_id: vboarding.allocatecode,
                active: vact,
                week_days: e,
                dontholi: vholi,
                imei: imei,
                route_id: route_id,
                notify_system: w,
                notify_email: q,
                notify_email_address: r,
                notify_sms: t,
                notify_sms_number: A,
                tfh:tfh,
                tfm:tfm,
                tth:tth,
                ttm:ttm,
                tripname:$("#dialog_boarding_tripname").val(),
                today:$("#dialog_allocate_hour_to_day").val()
            };
		$.ajax({
                type: "POST",
                url: "func/fn_boarding.php",
                data: dataaa,
                success: function(o) {
                    switch (o) {
                      
                        case "NO":
                         var p = "error";
                        var r = la.ERROR;
                        var q = la.DIFFOBJECTORHOTSPOT;
                        notifyBox(p, r, q);
                        break;
                        case "OK":
                        boeardingallocate('cancel');
                     	boeardingallocate('search');
                     	break;
                     	default:
                        var p = "error";
                        var r = la.ERROR;
                        notifyBox(p, r, o);
                        break;
                    }                 
                }
            });
	}
	else if(savecancel =="cancel")
	{
		document.getElementById("dialog_boarding_allocate_active").checked = true;
		
		document.getElementById("dialog_boarding_allocate_wd_sun").checked = true;
		document.getElementById("dialog_boarding_allocate_wd_mon").checked = true;
		document.getElementById("dialog_boarding_allocate_wd_tue").checked = true;
		document.getElementById("dialog_boarding_allocate_wd_wed").checked = true;
		document.getElementById("dialog_boarding_allocate_wd_thu").checked = true;
		document.getElementById("dialog_boarding_allocate_wd_fri").checked = true;
		document.getElementById("dialog_boarding_allocate_wd_sat").checked = true;
		
		document.getElementById("dialog_boarding_allocate_activeholiday").checked = true;
		
		document.getElementById("dialog_boarding_allocate_notify_system").checked = false;
		document.getElementById("dialog_boarding_allocate_notify_system_hide").checked = false;
		document.getElementById("dialog_boarding_allocate_notify_system_soundchkbox").checked = false;
		document.getElementById("dialog_boarding_allocate_notify_email").checked = false;
		document.getElementById("dialog_boarding_allocate_notify_sms").checked = false;
		
		document.getElementById("dialog_boarding_allocate_notify_email_address").value="";
	  	document.getElementById("dialog_boarding_allocate_notify_sms_number").value="";
		
		//document.getElementById("dialog_boarding_addobject").value="Select";
	  	document.getElementById("dialog_boarding_addhotspot").value="Select";

	  	
	  	$("#dialog_allocate_hour_to_day").val("Same");
	  	$("#txtdaysinterval").val("1")
	  	$("#dialog_boarding_tripname").val("");
	 	$("#dialog_allocate_hour_from").val("00");
	 	$("#dialog_allocate_minute_from").val("00");
	 	
	 	$("#dialog_allocate_hour_to").val("23");
	 	$("#dialog_allocate_minute_to").val("00");

	 	vboarding.allocatecode=0;
	 	boeardingallocate('search');
	 	$("#dialog_boarding_allocate_add").dialog("close");
	}
	else if(savecancel =="search")
	{  	
		   $("#boarding_allocate_list_grid").jqGrid("clearGridData");
		   $("#boarding_allocate_list_grid").setGridParam({url: "func/fn_boarding.php?cmd=select_allocate&object_id="+ document.getElementById("dialog_boarding_addobject").value });
           $("#boarding_allocate_list_grid").trigger("reloadGrid");
	}
	
	
} 

function addtripemployee(tripid)
{    
    var dataaa = { cmd: "get_vehicle_employee", tripid: tripid};
    $.ajax({
            type: "POST",
            url: "func/fn_boarding.php",
            data: dataaa,
            success: function(o) {
                document.getElementById("dialog_boarding_trip_employee_tripid").value=tripid;
                var dataset=o;
                var select= document.getElementById("dialog_boarding_trip_employee");
                multiselectClear(select);
                var option_i=0;
                for (var key in dataset)
                { 
                    select.options.add(new Option(dataset[key].emp_name, dataset[key].emp_id));
                    if(dataset[key].status=='yes'){
                        select.options[option_i].selected = true;
                    }else{
                        select.options[option_i].selected = false;
                    }
                    option_i+=1;
                }
                loadtrip_people();
                $("#dialog_boarding_trip_employee_list").dialog("open");

                // var select= document.getElementById("dialog_boarding_trip_employee");
                // for ( var i = 0, len = select.options.length; i < len; i++ ) {
                //     var re=select.options[i].value;
                // }
                // loadtrip_people();

            }
        });
}

function updatetripemployee(s){
    switch (s) {
        case 'save':
            var emplist=multiselectGetValues(document.getElementById("dialog_boarding_trip_employee"));
            var tripid=document.getElementById("dialog_boarding_trip_employee_tripid").value
            var dataaa = { cmd: "update_trip_employee", tripid: tripid,emplist:emplist};
            $.ajax({
                    type: "POST",
                    url: "func/fn_boarding.php",
                    data: dataaa,
                    success: function(o) {
                        if(o=='OK'){
                            notifyBox("info", la.INFORMATION, 'People Added Successfully');
                        }else{
                            notifyBox("error", la.ERROR, 'Unable to Add People');
                        }
                    }
                });
            $("#dialog_boarding_trip_employee_list").dialog("close");
        break;
        case 'cancel':
            var select= document.getElementById("dialog_boarding_trip_employee");
            multiselectClear(select);
            loadtrip_people();
            document.getElementById("dialog_boarding_trip_employee_tripid").value='';
            $("#dialog_boarding_trip_employee_list").dialog("close");
        break;
    }
}

function boardingallocatedit(deptcode)
{
	if($("#dialog_boarding_addobject  option:selected").val()!="Select" )
	 {
		 boeardingallocate("cancel");
		 $("#lbl_boarding_object_name").text($("#dialog_boarding_addobject  option:selected").text());
	 }
	 else
    notifyBox("error", la.ERROR, la.PLSOBJECT);
	
	  $("#dialog_boarding_allocate_add").dialog("open");
	
	   var dataaa = { cmd: "edit_allocate", event_id: deptcode};
	  	$.ajax({
                type: "POST",
                url: "func/fn_boarding.php",
                data: dataaa,
                success: function(o) {
                       
                        var v1=o["rows"][0][0].active;
                    	vboarding.allocatecode=deptcode;
	 	document.getElementById("dialog_boarding_allocate_active").checked = strToBoolean(o["rows"][0][0]["active"]);
		
		var daysplit=[];
		daysplit=o["rows"][0][0]["week_days"].split(';');
		
		var notisys=[];
		notisys=o["rows"][0][0]["notify_system"].split(',');
		
		document.getElementById("dialog_boarding_allocate_wd_sun").checked = strToBoolean(daysplit[0]);
		document.getElementById("dialog_boarding_allocate_wd_mon").checked = strToBoolean(daysplit[1]);
		document.getElementById("dialog_boarding_allocate_wd_tue").checked = strToBoolean(daysplit[2]);
		document.getElementById("dialog_boarding_allocate_wd_wed").checked = strToBoolean(daysplit[3]);
		document.getElementById("dialog_boarding_allocate_wd_thu").checked = strToBoolean(daysplit[4]);
		document.getElementById("dialog_boarding_allocate_wd_fri").checked = strToBoolean(daysplit[5]);
		document.getElementById("dialog_boarding_allocate_wd_sat").checked = strToBoolean(daysplit[6]);
		
		document.getElementById("dialog_boarding_allocate_activeholiday").checked = strToBoolean(o["rows"][0][0]["dontholiday"]);
		
		document.getElementById("dialog_boarding_allocate_notify_system").checked = strToBoolean(notisys[0]);
		document.getElementById("dialog_boarding_allocate_notify_system_hide").checked = strToBoolean(notisys[1]);
		document.getElementById("dialog_boarding_allocate_notify_system_soundchkbox").checked = strToBoolean(notisys[2]);
		document.getElementById("dialog_boarding_allocate_notify_system_sound").value=notisys[3];
		
		document.getElementById("dialog_boarding_allocate_notify_email").checked = strToBoolean(o["rows"][0][0]["notify_email"]);
		document.getElementById("dialog_boarding_allocate_notify_sms").checked = strToBoolean(o["rows"][0][0]["notify_sms"]);
		
		document.getElementById("dialog_boarding_allocate_notify_email_address").value=o["rows"][0][0]["notify_email_address"];
	  	document.getElementById("dialog_boarding_allocate_notify_sms_number").value=o["rows"][0][0]["notify_sms_number"];
		
		document.getElementById("dialog_boarding_addobject").value=o["rows"][0][0]["imei"];
	  	document.getElementById("dialog_boarding_addhotspot").value=o["rows"][0][0]["route_id"];

	  	$("#dialog_allocate_hour_to_day").val(o["rows"][0][0]["today"]);
	  	$("#txtdaysinterval").val(o["rows"][0][0]["daysinterval"])
	  	$("#dialog_boarding_tripname").val(o["rows"][0][0]["tripname"]);
	 	$("#dialog_allocate_hour_from").val(o["rows"][0][0]["tfh"]);
	 	$("#dialog_allocate_minute_from").val(o["rows"][0][0]["tfm"]);
	 	
	 	$("#dialog_allocate_hour_to").val(o["rows"][0][0]["tth"]);
	 	$("#dialog_allocate_minute_to").val(o["rows"][0][0]["ttm"]);
	 	

                }
            });
	
}

function boardingallocatedelete(deptcode)
{
	confirmDialog("Are you want to delete? ", function(ve) {
	if (ve) {
	 var dat = {cmd: "delete_allocate",uid:deptcode};
		$.ajax({
                type: "POST",
                url: "func/fn_boarding.php",
                data: dat,
                success: function(o) {
                    switch (o) {
                        default: var p = "error";
                        var r = la.ERROR;
                        var q = o;notifyBox(p, r, q);
                        boeardingallocate('cancel');
                        boeardingallocate('search');
                        break;
                        case "OK":
                        boeardingallocate('cancel');
                     	boeardingallocate('search');
                        break;
                    }                 
                }
            });
     }
	})
}


//boarding end


//student begin
function boardingsectionchangebydept()
{
	var custom = jQuery("#boarding_section_list_grid").jqGrid('getRowData');

	if($("#dialog_studentadd_deptnameddlsearch  option:selected").val()!="Select")
	{
		 var af = document.getElementById("dialog_studentadd_sectionddlsearch");
		 af.options.length = 0;
         af.options.add(new Option("Select"));
		 for($itr=0;$itr<custom.length;$itr++)
		 {
	 		if($("#dialog_studentadd_deptnameddlsearch option:selected").val()==custom[$itr]["dept_id"])
			{	
	 		 	af.options.add(new Option(custom[$itr]["section_name"],custom[$itr]["section_id"]));
	 		}
	 
		 }
	}
	else{
		 var af = document.getElementById("dialog_studentadd_sectionddlsearch");
		 af.options.length = 0;
         af.options.add(new Option("Select"));
	}
	
	if($("#dialog_studentadd_deptnameddl  option:selected").val()!="Select")
	{
		 var aa = document.getElementById("dialog_studentadd_sectionddl");
		 aa.options.length = 0;
         aa.options.add(new Option("Select"));
		 for($itr=0;$itr<custom.length;$itr++)
		 {
	 		if($("#dialog_studentadd_deptnameddl option:selected").val()==custom[$itr]["dept_id"])
			{	
	 		 	aa.options.add(new Option(custom[$itr]["section_name"],custom[$itr]["section_id"]));
	 		}
	 
		 }
	}
	else{
		 var aa = document.getElementById("dialog_studentadd_sectionddl");
		 aa.options.length = 0;
         aa.options.add(new Option("Select"));
	}
	
}


function boeardingstudent(savecancel)
{
	if(savecancel=="save")
	{
		if($("#dialog_studentadd_dob").val()=="" || 
				$("#dialog_studentadd_deptnameddl option:selected").val()=="Select" || 
				$("#dialog_studentadd_sectionddl option:selected").val()=="Select" ||
				$("#dialog_studentadd_id").val() ==""||$("#dialog_studentadd_name").val() ==""||
				$("#dialog_studentadd_phno").val() =="" ||
				$("#dialog_student_genderddl option:selected").val()=="Select" ||
				$("#dialog_studentadd_routename option:selected").val()=="Select" ||
				$("#dialog_studentadd_routename_down option:selected").val()=="Select" ||
				$("#dialog_student_ddlstatus option:selected").val()=="Select"
				|| $("#dialog_studentadd_morning_board option:selected").val()=="Select" ||
				$("#dialog_studentadd_evening_drop option:selected").val()=="Select" )
		{
			notifyBox("error", la.ERROR, la.PLSFILLALLDETAILS);
			return;
		}
		/*
		 if($("#dialog_studentadd_emailid").val()!=""){
			 var p = $("#dialog_studentadd_emailid").val().split(",");
	                for (var y = 0; y < p.length; y++) {
	                    p[y] = p[y].trim();
	                    if (!isEmailValid(p[y])) {
	                        notifyBox("error", la.ERROR, la.THIS_EMAIL_IS_NOT_VALID);
	                        return false;
	                    }
	                }
			 }
          */      
		 var dat = {cmd: "save_student",uid:vboarding.studentcode,dept_id: $("#dialog_studentadd_deptnameddl option:selected").val(),section_id: $("#dialog_studentadd_sectionddl option:selected").val(),sid: $("#dialog_studentadd_id").val(),name: $("#dialog_studentadd_name").val(),gender: $("#dialog_student_genderddl option:selected").val(),object_imei: $("#dialog_studentadd_object_imei option:selected").val(),phno: $("#dialog_studentadd_phno").val(),emailid: $("#dialog_studentadd_emailid").val(),route_id: $("#dialog_studentadd_routename option:selected").val(),route_id_down: $("#dialog_studentadd_routename_down option:selected").val(),status: $("#dialog_student_ddlstatus option:selected").val(),dob: $("#dialog_studentadd_dob").val(),
					parent:$("#dialog_studentadd_parentname").val(),rfidno:$("#dialog_studentadd_rfidno").val(),
					zone_id:$("#dialog_studentadd_morning_board option:selected").val(),zone_id_down:$("#dialog_studentadd_evening_drop option:selected").val()};
		$.ajax({
                type: "POST",
                url: "func/fn_boarding.php",
                data: dat,
                success: function(o) {
                    switch (o) {
                        default: var p = "error";
                        var r = la.ERROR;
                        var q = o;notifyBox(p, r, q);
                        boeardingstudent('cancel');
                      	boeardingstudent('search');
                        break;
                        case "Name Already Exists":
                         var p = "error";
                        var r = la.ERROR;
                        var q = o;notifyBox(p, r, q);
                        break;
                        case "OK":
                        boeardingstudent('cancel');
                     	boeardingstudent('search');
                        break;
                    }                 
                }
            });
		
		
	}
	else if(savecancel =="cancel")
	{  	document.getElementById("dialog_studentadd_sectionddl").value="Select";
	  	document.getElementById("dialog_studentadd_deptnameddl").value="Select";
	 	$("#dialog_studentadd_id").val("");
	 	$("#dialog_studentadd_name").val("");
	 	$("#dialog_studentadd_dob").val("");
	 	$("#dialog_studentadd_parentname").val("");
	 	$("#dialog_studentadd_rfidno").val("");
        document.getElementById("dialog_student_genderddl").value="Select";
	 	document.getElementById("dialog_studentadd_object_imei").value="";
	  	document.getElementById("dialog_studentadd_routename").value="Select";
	 	$("#dialog_studentadd_phno").val("");
	 	$("#dialog_studentadd_emailid").val("");
	 	document.getElementById("dialog_studentadd_routename_down").value="Select";
	 	document.getElementById("dialog_student_ddlstatus").value="Select";
	 	$("#dialog_studentadd_morning_board").val("Select");
	 	$("#dialog_studentadd_evening_drop").val("Select");
	 	vboarding.studentcode=0;
	 	boeardingstudent('search');
	 	$("#boarding_student_add").dialog("close");
	}
	else if(savecancel =="search")
	{  	
		   $("#boarding_student_list_grid").setGridParam({url: "func/fn_boarding.php?cmd=select_student&dept_id="+ document.getElementById("dialog_studentadd_deptnameddlsearch").value+"&section_id="+ document.getElementById("dialog_studentadd_sectionddlsearch").value+"&fidata="+ document.getElementById("dialog_studentadd_inputsearch").value });
           $("#boarding_student_list_grid").trigger("reloadGrid");
	}
	
	
} 

function boardingstudentedit(deptcode)
{
	  boeardingstudent('cancel');
	  $("#boarding_student_add").dialog("open");
	  
	 var custome = jQuery("#boarding_student_list_grid").jqGrid('getRowData');
	 for($itr=0;$itr<custome.length;$itr++)
	 {
	 	if(custome[$itr]["uid"]==deptcode)
	 	{
	 	
	 	$("#dialog_studentadd_id").val(custome[$itr]["sid"]);
	 	$("#dialog_studentadd_name").val(custome[$itr]["name"]);
	 	
		$("#dialog_studentadd_parentname").val(custome[$itr]["parent"]);
	 	$("#dialog_studentadd_rfidno").val(custome[$itr]["rfid"]);
	 	
        document.getElementById("dialog_student_genderddl").value=custome[$itr]["gender"];
	 	document.getElementById("dialog_studentadd_object_imei").value=custome[$itr]["pe_imei"];
	  	document.getElementById("dialog_studentadd_routename").value=custome[$itr]["route_id"];
	  	document.getElementById("dialog_studentadd_routename_down").value=custome[$itr]["route_id_down"];
	 	$("#dialog_studentadd_phno").val(custome[$itr]["phno"]);
	 	$("#dialog_studentadd_emailid").val(custome[$itr]["emailid"]);
	 	document.getElementById("dialog_student_ddlstatus").value=custome[$itr]["status"];
	 	$("#dialog_studentadd_dob").val(custome[$itr]["dob"]);
	 	document.getElementById("dialog_studentadd_deptnameddl").value=custome[$itr]["dept_id"];
	 	addboard_drop(custome[$itr]["route_id"],document.getElementById("dialog_studentadd_morning_board"),custome[$itr]["zone_id"]);
	  	addboard_drop(custome[$itr]["route_id_down"],document.getElementById("dialog_studentadd_evening_drop"),custome[$itr]["zone_id_down"]);
	  	var seid=custome[$itr]["section_id"];
	  	vboarding.studentcode=deptcode;	  	
	  	boardingsectionchangebydept();
	  	document.getElementById("dialog_studentadd_sectionddl").value=seid;
	  	
	 	 break;
	 	}
	 
	 }
	
	
}

function boardingstudentdelete(deptcode)
{
	confirmDialog("Are you want to delete? ", function(ve) {
	if (ve) {
	 var dat = {cmd: "delete_student",uid:deptcode};
		$.ajax({
                type: "POST",
                url: "func/fn_boarding.php",
                data: dat,
                success: function(o) {
                    switch (o) {
                        default: var p = "error";
                        var r = la.ERROR;
                        var q = o;notifyBox(p, r, q);
                        rfidemployee('cancel');
                     	rfidemployee('search');
                        break;
                        case "OK":
                        boeardingstudent('cancel');
                     	boeardingstudent('search');
                        break;
                    }                 
                }
            });
     }
	})
}

//student end

//hotspot begin

function boardinghotspotedit (idhotpot) {
  
   $("#boarding_hotspot_add").dialog("open");
  var dat = {cmd: "select_hotspot_single",hotspot_id:idhotpot};
		$.ajax({
                type: "POST",
                url: "func/fn_boarding.php",
                data: dat,
                dataType: "json",
               
                success: function(o) 
                {
                		
      			   	 var myTable= document.getElementById("boarding_hotspot_sub_table");
      				 var mtln=myTable.rows.length;
     			  	 for (var vp = myTable.rows.length-1; vp > 0; vp--) {
      			     	 if (vp!=0)
       				     myTable.rows[vp].remove();
     				   }
        
        			var vl=document.getElementById("boarding_hotspot_sub_table").rows.length;
                	if(o.length>0)
                	{
                    $("#dialog_hotspotadd_hotspotname").val(o[0]['routename']);
                    $("#dialog_hotspotadd_freezekm").val(o[0]['freezedkm']);
                    $("#dialog_hotspotadd_travelroute").val(o[0]['travelroute']);
                    
                    for (var i=0; i < o.length; i++) {
                    	 var newRow = document.getElementById("boarding_hotspot_sub_table").insertRow();
                    	newRow.insertCell().innerHTML=o[i]['sino'];
                       	newRow.insertCell().innerHTML=o[i]['zonecode'];
                       	newRow.insertCell().innerHTML=o[i]['zonename'];
                    	newRow.insertCell().innerHTML=o[i]['point'];
                       	newRow.insertCell().innerHTML=o[i]['zoneinout'];
                       	newRow.insertCell().innerHTML=o[i]['message'];
                       	newRow.insertCell().innerHTML="<center><a href=\"#\"  onclick=\"return editdatahotspot(" + o[i]['sino'] + ")\" title=\"Edit\"><img src=\"img/ico/pen_edit.png\"></a><a href=\"#\"  onclick=\"return deletedatahotspot(" + o[i]['sino'] + ") \" title=\"Edit\"><img src=\"img/ico/trash.png\"></a></center>";;
                       	
                    }
                    
                    hide1column();
                   }
                    vboarding.hotspotcode    =  idhotpot;  
                     
                }
            });
  
}

function boardinghotspotdelete (idhotpot) 
{
  confirmDialog("Are you want to delete , it will delete associate details", function(ve) {
	if (ve) {
	 var dat = {cmd: "delete_hotspot",hotspot_id:idhotpot};
		$.ajax({
                type: "POST",
                url: "func/fn_boarding.php",
                data: dat,
                success: function(o) {
                    switch (o) {
                        default: var p = "error";
                        var r = la.ERROR;
                        var q = o;notifyBox(p, r, q);
                        //hotspotin('close');
                        $("#boarding_hotspot_list_grid").trigger("reloadGrid");
                        break;
                        case "OK":
                     
                        hotspotin('close');
                         $("#boarding_hotspot_list_grid").trigger("reloadGrid");
                        break;
                    }                 
                }
            });
     }
  })
}

function hotspotin(savecancel)
{
	if(savecancel=="save")
	{
	
	var myTable = document.getElementById("boarding_hotspot_sub_table");
    var vt10data = "";
    for (var vjr = 1; vjr < myTable.rows.length; vjr++) {
            
            if (vt10data == "") {
                vt10data = myTable.rows[vjr].cells[1].innerHTML + "^" + myTable.rows[vjr].cells[4].innerHTML + "^" + myTable.rows[vjr].cells[5].innerHTML+ "^" + myTable.rows[vjr].cells[3].innerHTML;
            }
            else {
                vt10data = vt10data + "~" + myTable.rows[vjr].cells[1].innerHTML + "^" + myTable.rows[vjr].cells[4].innerHTML + "^" + myTable.rows[vjr].cells[5].innerHTML  + "^" + myTable.rows[vjr].cells[3].innerHTML;
            }  
    }
    
    if(vt10data=="")
    {
    	notifyBox("error", la.ERROR,'Please Add Atleast One Zone');
    	return;
    }
    
    var hotspotsavename=$("#dialog_hotspotadd_hotspotname").val();
    var hotspotfreezekm=$("#dialog_hotspotadd_freezekm").val();
	var hotspottravelroute=$("#dialog_hotspotadd_travelroute").val();
	if(hotspotsavename=="")
	 {
    	notifyBox("error", la.ERROR, la.PLSFILLHOTSPOTNAME);
    	return;
    }
	
	 var dat = {cmd: "save_hotspot",hotspot_code: vboarding.hotspotcode,hotspot_name: hotspotsavename,zonedetail:vt10data,freezekm:hotspotfreezekm ,travelroute:hotspottravelroute};
		$.ajax({
                type: "POST",
                url: "func/fn_boarding.php",
                data: dat,
                success: function(o) {
                    switch (o) {
                        default: var p = "error";
                        var r = la.ERROR;
                        var q = o;notifyBox(p, r, q);
                    	 hotspotin('close');
                        $("#boarding_hotspot_list_grid").trigger("reloadGrid");
                        break;
                        case "OK":
                    	 hotspotin('close');
                        $("#boarding_hotspot_list_grid").trigger("reloadGrid");
                        break;
                    }                 
                }
            });
	
	
	}
	else if(savecancel=="add")
	{
        var zoname=$('#dialog_hotspotadd_zonename').val();
		var travelroute=$('#dialog_hotspotadd_travelroute').val();
		var zoinout=$('#dialog_hotspotadd_zonetype').val();
		var zomsg=$('#dialog_hotspotadd_message').val();
		if(zoname=='Select' || zoname==null)
		{
			notifyBox("error", la.ERROR, la.PLSFILLALLDETAILS);
			return;
		}
		if(zoinout=='Select' || zoinout==null)
		{
			notifyBox("error", la.ERROR, la.PLSFILLALLDETAILS);
			return;
		}
		if(zomsg=='' || zomsg==null)
		{
			//notifyBox("error", la.ERROR, la.PLSFILLALLDETAILS);
			//return;
		}
		
		var	vpointget=$('#dialog_boarding_startingpoint').is(":checked");
		if($('#dialog_boarding_startingpoint').is(":checked"))
		{
			vpointget="Start";
		}
		else if($('#dialog_boarding_endpoint').is(":checked"))
		{
			vpointget="End";
		}
		else
			vpointget="";
		
		var myTable = document.getElementById("boarding_hotspot_sub_table");
	    var vtst =0 ;var vtend =0 ;
	    for (var vjr = 1; vjr < myTable.rows.length; vjr++) {
	            if ( myTable.rows[vjr].cells[3].innerHTML == "Start" && veprow!=vjr) {
	            	vtst++; 
	            }
	            else if( myTable.rows[vjr].cells[3].innerHTML=="End" && veprow!=vjr)
	            {
	            	vtend++;
	            }
	    }
		
	    if((vtst>=4 && vpointget=="Start" ) || ( vpointget=="End" && vtend>=4))
	    {
	    		notifyBox("error", la.ERROR, la.YOUCATNENTER1PSTEN);
	    		return;
	    }
	   

        if (veprow == '') {
            var newRow = document.getElementById("boarding_hotspot_sub_table").insertRow();
            newRow.insertCell().innerHTML = document.getElementById("boarding_hotspot_sub_table").rows.length - 1;
            newRow.insertCell().innerHTML = zoname;
            newRow.insertCell().innerHTML = $( "#dialog_hotspotadd_zonename option:selected" ).text();
            newRow.insertCell().innerHTML = vpointget;
            newRow.insertCell().innerHTML = zoinout;
            newRow.insertCell().innerHTML = zomsg;
            newRow.insertCell().innerHTML = "<center><a href=\"#\"  onclick=\"return editdatahotspot(" + (document.getElementById("boarding_hotspot_sub_table").rows.length - 1) + ")\" title=\"Edit\"><img src=\"img/ico/pen_edit.png\"></a><a href=\"#\"  onclick=\"return deletedatahotspot(" + (document.getElementById("boarding_hotspot_sub_table").rows.length - 1) + ")\" title=\"Edit\"><img src=\"img/ico/trash.png\"></a></center>";

            hotspotin('clear');
            
        }
        else {
            var myTable = document.getElementById("boarding_hotspot_sub_table");
            for (var vjr = 1; vjr < myTable.rows.length; vjr++) {
                if (veprow == vjr) {
                    myTable.rows[vjr].cells[1].innerHTML = zoname;
                    myTable.rows[vjr].cells[2].innerHTML =  $( "#dialog_hotspotadd_zonename option:selected" ).text();
                    myTable.rows[vjr].cells[3].innerHTML = vpointget;
                    myTable.rows[vjr].cells[4].innerHTML = zoinout;
                    myTable.rows[vjr].cells[5].innerHTML = zomsg;
                  hotspotin('clear');
                }
            }
        }

        hide1column();
        
        
   
		
	}
	else if(savecancel=="clear")
	{
		veprow="";
        $("#dialog_hotspotadd_zonename").val("Select");
		$("#dialog_hotspotadd_travelroute").val("Select");
		$("#dialog_hotspotadd_zonetype").val("Select");
		$("#dialog_hotspotadd_message").val("");
	}
	else if(savecancel=="close")
	{
		 hotspotin('clear');
		 vboarding.hotspotcode=0;
		 $("#dialog_hotspotadd_hotspotname").val("");
		 
		 var myTable= document.getElementById("boarding_hotspot_sub_table");
      				 var mtln=myTable.rows.length;
     			  	 for (var vp = myTable.rows.length-1; vp > 0; vp--) {
      			     	 if (vp!=0)
       				     myTable.rows[vp].remove();
     				   }
     				   
         $("#boarding_hotspot_add").dialog("close");
        
	}
	
}


function editdatahotspot(ro) {
    if (ro > 0) {
        veprow = ro;
        document.getElementById("dialog_hotspotadd_zonename").value = document.getElementById("boarding_hotspot_sub_table").rows[ro].cells[1].innerHTML;
        document.getElementById("dialog_hotspotadd_zonetype").value = document.getElementById("boarding_hotspot_sub_table").rows[ro].cells[4].innerHTML;
        document.getElementById("dialog_hotspotadd_message").value = document.getElementById("boarding_hotspot_sub_table").rows[ro].cells[5].innerHTML;
        if(document.getElementById("boarding_hotspot_sub_table").rows[ro].cells[3].innerHTML=="Start")
        {$( "#dialog_boarding_startingpoint" ).prop( "checked", true );$( "#dialog_boarding_endpoint" ).prop( "checked", false );}
        else if(document.getElementById("boarding_hotspot_sub_table").rows[ro].cells[3].innerHTML=="End")
        {$( "#dialog_boarding_startingpoint" ).prop( "checked", false );$( "#dialog_boarding_endpoint" ).prop( "checked", true );}
        else
        {$( "#dialog_boarding_startingpoint" ).prop( "checked", false );$( "#dialog_boarding_endpoint" ).prop( "checked", false );}
        chkboardingzoneinout();
    }
}


function deletedatahotspot(ro) {
	confirmDialog("Are you want to delete?", function(ve) {
		if (ve) {
        var myTable = document.getElementById("boarding_hotspot_sub_table");
        myTable.rows[ro].remove();

            for (var vjr = 1; vjr < myTable.rows.length; vjr++) {

                myTable.rows[vjr].cells[0].innerHTML = vjr;
                myTable.rows[vjr].cells[6].innerHTML = "<center><a href=\"#\"  onclick=\"return editdatahotspot(" + vjr + ")\" title=\"Edit\"><img src=\"img/ico/pen_edit.png\"></a><a href=\"#\"  onclick=\"return deletedatahotspot(" + vjr + ") \" title=\"Edit\"><img src=\"img/ico/trash.png\"></a></center>";

            }

        //document.getElementById("tblparticular").rows[ro].innerHTML="";
    }
	})
}


function hide1column() {
   
        var myTable = document.getElementById("boarding_hotspot_sub_table");
    
            for (var vjr = 0; vjr < myTable.rows.length; vjr++) {

                myTable.rows[vjr].cells[1].style.display = "none";
              
            }

        
}

//hotspot end

//department  begin
function boardingdepartment(savecancel)
{
	if(savecancel=="save")
	{
		if($("#dialog_boarding_deptnametxt").val()=='')
		{
			notifyBox("error", la.ERROR, la.PLSDEPTNAME);
			return;
		}
		 var dat = {cmd: "save_department",dept_id: vboarding.deptcode,dept_name: $("#dialog_boarding_deptnametxt").val() };
		$.ajax({
                type: "POST",
                url: "func/fn_boarding.php",
                data: dat,
                success: function(o) {
                    switch (o) {
                        default: var p = "error";
                        var r = la.ERROR;
                        var q = o;notifyBox(p, r, q);
                        vboarding.deptcode=0;
                        document.getElementById("dialog_boarding_deptnametxt").value="";
                        $("#boarding_dept_list_grid").trigger("reloadGrid");
                        break;
                        case "Name Already Exists":
                         var p = "error";
                         var r = la.ERROR;
                        var q = o;notifyBox(p, r, q);
                        break;
                        case "OK":
                        vboarding.deptcode=0;
                        document.getElementById("dialog_boarding_deptnametxt").value="";
                        $("#boarding_dept_list_grid").trigger("reloadGrid");
                        break;
                    }                 
                }
            });
		
		
	}
	else if(savecancel =="cancel")
	{   vboarding.deptcode=0;
		$("#dialog_boarding_deptnametxt").val("");	
	}
	
}

function boardingdepartmentedit(deptcode)
{
	 var custom = jQuery("#boarding_dept_list_grid").jqGrid('getRowData');
	 for($itr=0;$itr<custom.length;$itr++)
	 {
	 	if(custom[$itr]["dept_id"]==deptcode)
	 {
	 	$("#dialog_boarding_deptnametxt").val(custom[$itr]["dept_name"]);
	 	 vboarding.deptcode=deptcode;
	 	 break;
	 }
	 
	 }
	
	
}

function boardingdepartmentdelete(deptcode)
{
	confirmDialog("Are you want to delete , it will delete associate data (section and student details)", function(ve) {
	if (ve) {
	 var dat = {cmd: "delete_department",dept_id:deptcode};
		$.ajax({
                type: "POST",
                url: "func/fn_boarding.php",
                data: dat,
                success: function(o) {
                    switch (o) {
                        default: var p = "error";
                        var r = la.ERROR;
                        var q = o;notifyBox(p, r, q);
                           vboarding.deptcode=0;
                        document.getElementById("dialog_boarding_deptnametxt").value="";
                        $("#boarding_dept_list_grid").trigger("reloadGrid");
                        break;
                        case "OK":
                        vboarding.deptcode=0;
                        document.getElementById("dialog_boarding_deptnametxt").value="";
                        $("#boarding_dept_list_grid").trigger("reloadGrid");
                        break;
                    }                 
                }
            });
     }
	})
}

//department  end

//section  begin
function boardingsection(savecancel)
{
	if(savecancel=="save")
	{
		if( $("#dialog_boarding_sectiontxt").val() =="" || $("#dialog_boarding_deptnameddl option:selected").val()=="Select")
		{
			notifyBox("error", la.ERROR, la.PLSSECTIONNAME);
			return;
		}
		 var dat = {cmd: "save_section",oldsection_id:vboarding.oldsectioncode,section_id:vboarding.sectioncode,dept_id: $("#dialog_boarding_deptnameddl option:selected").val(),section_name: $("#dialog_boarding_sectiontxt").val() };
		$.ajax({
                type: "POST",
                url: "func/fn_boarding.php",
                data: dat,
                success: function(o) {
                    switch (o) {
                        default: var p = "error";
                        var r = la.ERROR;
                        var q = o;notifyBox(p, r, q);
                          vboarding.sectioncode=0;
                        document.getElementById("dialog_boarding_deptnameddl").value="Select";
                        document.getElementById("dialog_boarding_sectiontxt").value="";
                        $("#boarding_section_list_grid").setGridParam({url: "func/fn_boarding.php?cmd=select_section&dept_id=" + document.getElementById("dialog_boarding_deptnameddl").value });
                        $("#boarding_section_list_grid").trigger("reloadGrid");
                        break;
                        case "Name Already Exists":
                         var p = "error";
                        var r = la.ERROR;
                        var q = o;notifyBox(p, r, q);
                        break;
                        case "OK":
                        vboarding.sectioncode=0;
                        document.getElementById("dialog_boarding_deptnameddl").value="Select";
                        document.getElementById("dialog_boarding_sectiontxt").value="";
                         $("#boarding_section_list_grid").setGridParam({url: "func/fn_boarding.php?cmd=select_section&dept_id=" + document.getElementById("dialog_boarding_deptnameddl").value });
                         $("#boarding_section_list_grid").trigger("reloadGrid");
                        break;
                    }                 
                }
            });
		
		
	}
	else if(savecancel =="cancel")
	{  	document.getElementById("dialog_boarding_deptnameddl").value="Select";
	 	$("#dialog_boarding_sectiontxt").val("");
	 	 vboarding.sectioncode=0;
	 	
	}
	
}

function boardingsectionedit(deptcode)
{
	 var custom = jQuery("#boarding_section_list_grid").jqGrid('getRowData');
	 for($itr=0;$itr<custom.length;$itr++)
	 {
	 	if(custom[$itr]["section_id"]==deptcode)
	 {
	 	document.getElementById("dialog_boarding_deptnameddl").value=custom[$itr]["dept_id"];
	 	$("#dialog_boarding_sectiontxt").val(custom[$itr]["section_name"]);
	 	 vboarding.sectioncode=deptcode;
	 	
	 	 break;
	 }
	 
	 }
	
	
}

function boardingsectiondelete(deptcode)
{
	confirmDialog("Are you want to delete ,it will delete associate student details", function(ve) {
	if (ve) {
	 var dat = {cmd: "delete_section",section_id:deptcode};
		$.ajax({
                type: "POST",
                url: "func/fn_boarding.php",
                data: dat,
                success: function(o) {
                    switch (o) {
                        default: var p = "error";
                        var r = la.ERROR;
                        var q = o;notifyBox(p, r, q);
                         vboarding.sectioncode=0;
                        document.getElementById("dialog_boarding_deptnameddl").value="Select";
                        document.getElementById("dialog_boarding_sectiontxt").value="";
                        $("#boarding_section_list_grid").setGridParam({url: "func/fn_boarding.php?cmd=select_section&dept_id=" + document.getElementById("dialog_boarding_deptnameddl").value });
                        $("#boarding_section_list_grid").trigger("reloadGrid");
                        break;
                        case "OK":
                          vboarding.sectioncode=0;
                        document.getElementById("dialog_boarding_deptnameddl").value="Select";
                        document.getElementById("dialog_boarding_sectiontxt").value="";
                        $("#boarding_section_list_grid").setGridParam({url: "func/fn_boarding.php?cmd=select_section&dept_id=" + document.getElementById("dialog_boarding_deptnameddl").value });
                        $("#boarding_section_list_grid").trigger("reloadGrid");
                        break;
                    }                 
                }
            });
     }
	})
}

function onchange_boarding_deptnameddl()
{
	 $("#boarding_section_list_grid").setGridParam({
                url: "func/fn_boarding.php?cmd=select_section&dept_id=" + document.getElementById("dialog_boarding_deptnameddl").value });
	$("#boarding_section_list_grid").trigger("reloadGrid");
}

//section  end


//holiday  begin

function boardingholiday(savecancel)
{
	if(savecancel=="save")
	{
		if($("#dialog_boarding_holidaydate").val()=='' || $("#dialog_boarding_holidaydateto").val()==''  || $("#dialog_boarding_holidayreason").val()=='')
		{
			notifyBox("error", la.ERROR, la.PLSDATEREASON);
			return;
		}
     	
     	var dat = {cmd: "save_holiday",uid: vboarding.holidaycode,date: $("#dialog_boarding_holidaydate").val(),dateto: $("#dialog_boarding_holidaydateto").val(),reason: $("#dialog_boarding_holidayreason").val() };
		$.ajax({
                type: "POST",
                url: "func/fn_boarding.php",
                data: dat,
                success: function(o) {
                    switch (o) {
                        default: var p = "error";
                        var r = la.ERROR;
                        var q = o;notifyBox(p, r, q);
                        vboarding.deptcode=0;
                        document.getElementById("dialog_boarding_holidayreason").value="";
                        $("#boarding_holiday_list_grid").trigger("reloadGrid");
                        break;
                        case "Name Already Exists":
                         var p = "error";
                         var r = la.ERROR;
                        var q = o;notifyBox(p, r, q);
                        break;
                        case "OK":
                        vboarding.holidaycode=0;
                        document.getElementById("dialog_boarding_holidayreason").value="";
                        $("#boarding_holiday_list_grid").trigger("reloadGrid");
                        break;
                    }                 
                }
            });
		
		
	}
	else if(savecancel =="cancel")
	{   vboarding.holidaycode=0;
		$("#dialog_boarding_holidayreason").val("");	
	}
	else if(savecancel=="search")
	{


		
	$("#boarding_holiday_list_grid").setGridParam({  url: "func/fn_boarding.php?cmd=select_holiday&df=" + $("#dialog_boarding_holidayfromdate").val()+"&dt="+$("#dialog_boarding_holidaytodate").val()+"vetri=vetri" });
	$("#boarding_holiday_list_grid").trigger("reloadGrid");
		
	}
	
}

function boardingholidayedit(deptcode)
{
	 var custom = jQuery("#boarding_holiday_list_grid").jqGrid('getRowData');
	 for($itr=0;$itr<custom.length;$itr++)
	 {
	 	if(custom[$itr]["uid"]==deptcode)
	 {
	 	$("#dialog_boarding_holidaydate").val(custom[$itr]["date"]);
	 	$("#dialog_boarding_holidaydateto").val(custom[$itr]["dateto"]);
	 	$("#dialog_boarding_holidayreason").val(custom[$itr]["reason"]);
	 	 vboarding.holidaycode=deptcode;
	 	 break;
	 }
	 
	 }
	
	
}

function boardingholidaydelete(deptcode)
{
	confirmDialog("Are you want to delete?", function(ve) {
	if (ve) {
	 var dat = {cmd: "delete_holiday",uid:deptcode};
		$.ajax({
                type: "POST",
                url: "func/fn_boarding.php",
                data: dat,
                success: function(o) {
                    switch (o) {
                        default: var p = "error";
                        var r = la.ERROR;
                        var q = o;notifyBox(p, r, q);
                        vboarding.holidaycode=0;
                        document.getElementById("dialog_boarding_holidayreason").value="";
                        $("#boarding_holiday_list_grid").trigger("reloadGrid");
                        break;
                        case "OK":
                        vboarding.holidaycode=0;
                        document.getElementById("dialog_boarding_holidayreason").value="";
                        $("#boarding_holiday_list_grid").trigger("reloadGrid");
                        break;
                    }                 
                }
            });
     }
	})
}

//holiday  end


var vrfidtrip=[];
vrfidtrip.deptcode=0;
vrfidtrip.employeecode=0;
vrfidtrip.driverrfid=0;
vrfidtrip.gpsclose=0;

function rfidopen()
{
  $("#dialog_rfidtrip").dialog("open");
	
}


//department  begin
function rfidtripdepartment(savecancel)
{
	if(savecancel=="save")
	{
		if($("#dialog_rfidtrip_deptnametxt").val()=='')
		{
			notifyBox("error", la.ERROR, la.PLSDEPTNAME);
			return;
		}
		 var dat = {cmd: "save_department",dept_id: vrfidtrip.deptcode,dept_name: $("#dialog_rfidtrip_deptnametxt").val() };
		$.ajax({
                type: "POST",
                url: "func/fn__rfid.php",
                data: dat,
                success: function(o) {
                    switch (o) {
                        default: var p = "error";
                        var r = la.ERROR;
                        var q = o;notifyBox(p, r, q);
                        rfidtripdepartment('cancel');
                        rfidtripdepartment('refresh');
                        break;
                        case "Name Already Exists":
                         var p = "error";
                         var r = la.ERROR;
                        var q = o;notifyBox(p, r, q);
                        break;
                        case "OK":
                       
                        rfidtripdepartment('cancel');
                        rfidtripdepartment('refresh');
                        break;
                    }                 
                }
            });
		
		
	}
	else if(savecancel =="cancel")
	{   vrfidtrip.deptcode=0;
		$("#dialog_rfidtrip_deptnametxt").val("");	
	}
	else if(savecancel =="refresh")
	{   
		$("#rfidtrip_dept_list_grid").trigger("reloadGrid");	
		rfidemployee('search');
	}
	
}

function rfidtripdepartmentedit(deptcode)
{
	 var custom = jQuery("#rfidtrip_dept_list_grid").jqGrid('getRowData');
	 for($itr=0;$itr<custom.length;$itr++)
	 {
	 	if(custom[$itr]["dept_id"]==deptcode)
	 {
	 	$("#dialog_rfidtrip_deptnametxt").val(custom[$itr]["dept_name"]);
	 	 vrfidtrip.deptcode=deptcode;
	 	 break;
	 }
	 
	 }
	
	
}

function rfidtripdepartmentdelete(deptcode)
{
	confirmDialog("Are you want to delete , it will delete associate data?", function(ve) {
	if (ve) {
	 var dat = {cmd: "delete_department",dept_id:deptcode};
		$.ajax({
                type: "POST",
                url: "func/fn__rfid.php",
                data: dat,
                success: function(o) {
                    switch (o) {
                        default: var p = "error";
                        var r = la.ERROR;
                        var q = o;notifyBox(p, r, q);
                          rfidtripdepartment('cancel');
                        rfidtripdepartment('refresh');
                        break;
                        case "OK":
                        rfidtripdepartment('cancel');
                        rfidtripdepartment('refresh');
                        break;
                    }                 
                }
            });
     }
	})
}

//department  end

//section  begin
function rfidemployee(savecancel)
{
	if(savecancel=="save")
	{
		if( $("#dialog_employeeadd_name").val() =="" || $("#dialog_employee_deptnameddl option:selected").val()=="Select" || $("#dialog_employee_deptnameddl option:selected").val()=="0" ||$("#dialog_employeeadd_rfidid").val() =="" || $("#dialog_employee_genderddl option:selected").val()=="Select" || $("#dialog_employee_genderddl option:selected").val()=="0")
		{
			notifyBox("error", la.ERROR, la.PLSFILLALLDETAILS);
			return;
		}
		 var dat = {cmd: "save_employee",uid:vrfidtrip.employeecode,dept_id: $("#dialog_employee_deptnameddl option:selected").val(),name: $("#dialog_employeeadd_name").val(),gender: $("#dialog_employee_genderddl option:selected").val(),rfidid: $("#dialog_employeeadd_rfidid").val() };
		$.ajax({
                type: "POST",
                url: "func/fn__rfid.php",
                data: dat,
                success: function(o) {
                    switch (o) {
                        default: var p = "error";
                        var r = la.ERROR;
                        var q = o;notifyBox(p, r, q);
                        rfidemployee('cancel');
                        $("#rfidtrip_employee_list_grid").setGridParam({url: "func/fn__rfid.php?cmd=select_employee&name=" + document.getElementById("dialog_employee_search").value });
                        $("#rfidtrip_employee_list_grid").trigger("reloadGrid");
                        break;
                        case "Name Already Exists":
                         var p = "error";
                        var r = la.ERROR;
                        var q = o;notifyBox(p, r, q);
                        break;
                        case "OK":
                        rfidemployee('cancel');
                     	rfidemployee('search');
                        break;
                    }                 
                }
            });
		
		
	}
	else if(savecancel =="cancel")
	{  	document.getElementById("dialog_employee_deptnameddl").value="Select";
	  	document.getElementById("dialog_employee_genderddl").value="Select";
	 	$("#dialog_employeeadd_name").val("");
	 	$("#dialog_employeeadd_rfidid").val("");
	 	vrfidtrip.employeecode=0;
	 	rfidemployee('search');
	 	$("#rfidtrip_employee_add").dialog("close");
	}
	else if(savecancel =="search")
	{  	
		   $("#rfidtrip_employee_list_grid").setGridParam({url: "func/fn__rfid.php?cmd=select_employee&name=" + document.getElementById("dialog_employee_search").value });
           $("#rfidtrip_employee_list_grid").trigger("reloadGrid");
	}
	
}

function rfidemployeeedit(deptcode)
{
	  $("#rfidtrip_employee_add").dialog("open");
	  
	 var custom = jQuery("#rfidtrip_employee_list_grid").jqGrid('getRowData');
	 for($itr=0;$itr<custom.length;$itr++)
	 {
	 	if(custom[$itr]["uid"]==deptcode)
	 	{
	 	document.getElementById("dialog_employee_deptnameddl").value=custom[$itr]["dept_id"];
	 	$("#dialog_employeeadd_name").val(custom[$itr]["name"]);
	 	
	 	document.getElementById("dialog_employee_genderddl").value=custom[$itr]["gender"];
	 	$("#dialog_employeeadd_rfidid").val(custom[$itr]["rfidid"]);
	 	 vrfidtrip.employeecode=deptcode;
	 	
	 	 break;
	 	}
	 
	 }
	
	
}

function rfidemployeedelete(deptcode)
{
	confirmDialog("Are you want to delete?", function(ve) {
	if (ve) {
	 var dat = {cmd: "delete_employee",uid:deptcode};
		$.ajax({
                type: "POST",
                url: "func/fn__rfid.php",
                data: dat,
                success: function(o) {
                    switch (o) {
                        default: var p = "error";
                        var r = la.ERROR;
                        var q = o;notifyBox(p, r, q);
                        rfidemployee('cancel');
                     	rfidemployee('search');
                        break;
                        case "OK":
                        rfidemployee('cancel');
                     	rfidemployee('search');
                        break;
                    }                 
                }
            });
     }
	})
}

function rfidgpsissue(savecancel)
{
	if(savecancel=="save")
	{
		var vgpsobj=$("#dialog_rfidtrip_gpsi_objectnameddl").val();
		var vgpsdriverrfid=$("#dialog_rfidtrip_gpsi_driverrfidddl").val();
		
	
        if ($("#dialog_rfidtrip_gpsi_objectnameddl :selected").length >1 || $("#dialog_rfidtrip_gpsi_driverrfidddl :selected").length >1)
        {
            notifyBox("error", la.ERROR, la.PLEASE_SELECT_WITHIN_1);
            return;
        }
  		
		
		if(  vgpsobj=="Select" || vgpsdriverrfid=="Select" || $("#dialog_rfidtrip_gpsi_vehicleno").val()=="" ||$("#dialog_rfidtrip_gpsi_vendor").val() =="" || $("#dialog_rfidtrip_gpsi_driver").val()=="" || $("#dialog_rfidtrip_gpsi_driverph").val()=="")
		{
			notifyBox("error", la.ERROR, la.PLSFILLALLDETAILS);
			return;
		}
		 var dat = {cmd: "save_gpsissue",imei:vgpsobj[0],driverrfid:vgpsdriverrfid[0],
		 vehicleno: $("#dialog_rfidtrip_gpsi_vehicleno").val(),
		 vendorname: $("#dialog_rfidtrip_gpsi_vendor").val(),
		 drivername: $("#dialog_rfidtrip_gpsi_driver").val(),
		 driverphone: $("#dialog_rfidtrip_gpsi_driverph").val()
 };
		$.ajax({
                type: "POST",
                url: "func/fn__rfid.php",
                data: dat,
                success: function(o) {
                    switch (o) {
                        default: var p = "error";
                        var r = la.ERROR;
                        var q = o;notifyBox(p, r, q);
                        rfidgpsissue('cancel');
                     	rfidgpsissue('search');
                        break;
                        case "Name Already Exists":
                         var p = "error";
                        var r = la.ERROR;
                        var q = o;notifyBox(p, r, q);
                        break;
                        case "OK":
                        var p = la.INFO;
                        var r ="Info";
                        var q = "Details Updated Successfully";
                        notifyBox(p, r, q);
                        rfidgpsissue('cancel');
                     	rfidgpsissue('search');
                        break;
                    }                 
                }
            });
		
		
	}
	else if(savecancel =="cancel")
	{  	document.getElementById("dialog_rfidtrip_gpsi_objectnameddl").value="Select";
	  	document.getElementById("dialog_rfidtrip_gpsi_driverrfidddl").value="Select";
	 	$("#dialog_rfidtrip_gpsi_vehicleno").val("");
	 	$("#dialog_rfidtrip_gpsi_vendor").val("");
	 	$("#dialog_rfidtrip_gpsi_driver").val("");
	 	$("#dialog_rfidtrip_gpsi_driverph").val("");
	 	rfidgpsissue('search');
	 	vrfidtrip.gpsclose=0;
	 	reasontxt:$("#inputbox_gpsclose_reason").val("");
	}
	else if(savecancel =="search")
	{  	
		   $("#rfidtrip_gpsclose_list_grid").setGridParam({url: "func/fn__rfid.php?cmd=select_gpsclose&name=" + document.getElementById("dialog_gpsclose_search").value });
           $("#rfidtrip_gpsclose_list_grid").trigger("reloadGrid");
	}
	else if(savecancel =="closefinal")
	{  	
		confirmDialog("Are you want to Close Session?", function(ve) {
		if (ve) {
	 var dat = {cmd: "gpsissue_final_close",uid:vrfidtrip.gpsclose,reasontxt:$("#inputbox_gpsclose_reason").val()};
		$.ajax({
                type: "POST",
                url: "func/fn__rfid.php",
                data: dat,
                success: function(o) {
                    switch (o) {
                        default: var p = "error";
                        var r = la.ERROR;
                        var q = o;notifyBox(p, r, q);
                        rfidgpsissue('cancel');
                     	rfidgpsissue('search');
                        break;
                        case "OK":
                        rfidgpsissue('NO');
                     	rfidgpsissue('search');
                        break;
                    }                 
                }
            });
     }
		})
     
		 rfidgpsissue('cancel');
         rfidgpsissue('search');
	}
	else if(savecancel =="NO")
	{  
	 	 rfidgpsissue('cancel');
	 	 $('#rfidtrip_gpsclose_close').dialog('close');
	}
	
}

function rfidgpsissuedelete(uid)
{
	confirmDialog("Are you want to delete?", function(ve) {
	if (ve) {
	 var dat = {cmd: "delete_gpsissue",uid:uid};
		$.ajax({
                type: "POST",
                url: "func/fn__rfid.php",
                data: dat,
                success: function(o) {
                    switch (o) {
                        default: var p = "error";
                        var r = la.ERROR;
                        var q = o;notifyBox(p, r, q);
                        rfidgpsissue('cancel');
                     	rfidgpsissue('search');
                        break;
                        case "OK":
                        rfidgpsissue('cancel');
                     	rfidgpsissue('search');
                        break;
                    }                 
                }
            });
     }
	})
}


function rfidgpsissueclose(uid)
{
	$('#rfidtrip_gpsclose_close').dialog('open');
	vrfidtrip.gpsclose=uid;
	
}

function placesRoutePropertiesnew(b) {
	
	if(historyRouteData!='')
	{
	  var bvel = new Array();
    for (var d = 0; d < historyRouteData.route.length; d=d+5) {
        lat = historyRouteData.route[d]["lat"];
        lng = historyRouteData.route[d]["lng"];
        if ($.inArray(parseFloat(lat).toFixed(6) + "," + parseFloat(lng).toFixed(6), bvel) == -1)
	   {
        bvel.push(parseFloat(lat).toFixed(6) + "," + parseFloat(lng).toFixed(6));
       }
    }
    
	
    
    if (!utilsCheckPrivileges("viewer")) {
        return;
    }
    if (!utilsCheckPrivileges("places")) {
        return;
    }
    switch (b) {
        

        case "save":
      
            var h = document.getElementById("txtcreateroutename").value;
            var n = "#FF0000";
            var f = "true";
            var g = "true";
            var l = 0.5;
            if (h == "") {
                notifyBox("error", la.ERROR, la.NAME_CANT_BE_EMPTY);
                break;
            }
            if ((l < 0) || (l == "")) {
                notifyBox("error", la.ERROR, la.DEVIATION_CANT_BE_LESS_THAN_0);
                break;
            }
            if (!historyRouteData.route) {
                notifyBox("error", la.ERROR, la.DRAW_ROUTE_ON_MAP_BEFORE_SAVING);
                break;
            }
            
           
            var e = bvel.toString().slice(0,-1) ;//.substring(0,bvel.toString().length-1);//placesRouteLatLngsToPointsString(historyRouteData.edit_route_layer.getLatLngs());
           
            var d = {
                cmd: "save_route",
                route_id: false,
                route_name: h,
                route_color: n,
                route_visible: f,
                route_name_visible: g,
                route_deviation: l,
                route_points: e
            };$.ajax({
                type: "POST",
                url: "func/fn_places.php",
                data: d,
                success: function(o) {
                    switch (o) {
                        default: var p = "error";
                        var r = la.ERROR;
                        var q = o;notifyBox(p, r, q);
                        break;
                        case "OK":
                        document.getElementById("txtcreateroutename").value="";
                                placesRouteLoadData();
                                $("#left_panel_places_route_list_grid").trigger("reloadGrid");
                            break;
                    }                 
                }
            });
            break;
    }
    }
    else
    {
    	notifyDialog("Please Load Histroy");
    }
}

// list box search option for report



/*
function fnmenuright(thisv)
{
	var menuRight = document.getElementById( 'cbp-spmenu-s2' ),
	showRight = document.getElementById( 'showRight' ),
	body = document.body;
	classie.toggle( thisv, 'active' );
	classie.toggle( menuRight, 'cbp-spmenu-open' );
}*/

function toggleMenu() {
  var menuBox = document.getElementById('cbp-spmenu-s2');    
  if(menuBox.style.display == "block") { // if is menuBox displayed, hide it
   menuBox.style.display = "none";

   document.getElementById("map").style.left = "0px";  
   document.getElementById("bottom_panel").style.left = "0px"; 
   var mapmenu=document.getElementById("map").className ;
   if(mapmenu=="block width75 leaflet-container leaflet-touch leaflet-fade-anim leaflet-grab leaflet-touch-drag leaflet-touch-zoom")
   {
    document.getElementById("map").className="block width80 leaflet-container leaflet-touch leaflet-fade-anim leaflet-grab leaflet-touch-drag leaflet-touch-zoom";
    map.invalidateSize(!0)    
    document.getElementById("maplayer_top").style.left="0px";
   document.getElementById("bottom_panel").style.width = "74%";
     $("#bottom_panel_msg_list_grid").setGridWidth($(window).width() - 370)
      $("#bottom_panel_historytrack_list_grid").setGridWidth($(window).width() - 370)

      $(window).bind("resize", function() {
        "none" == document.getElementById("side_panel").style.display ? $("#bottom_panel_msg_list_grid").setGridWidth($(window).width() - 35) : $("#bottom_panel_msg_list_grid").setGridWidth($(window).width() - 370)
    }) 
    $(window).bind("resize", function () {
        if (document.getElementById("side_panel").style.display == "none") {
            $("#bottom_panel_historytrack_list_grid").setGridWidth($(window).width() - 35)
        } else {
            $("#bottom_panel_historytrack_list_grid").setGridWidth($(window).width() - 370)
        }
    })  

   }else{
    document.getElementById("map").className="leaflet-container leaflet-touch leaflet-fade-anim leaflet-grab leaflet-touch-drag leaflet-touch-zoom";
    map.invalidateSize(!0)
    document.getElementById("maplayer_top").style.left="0px";
   document.getElementById("bottom_panel").style.width = "100%"; 
     $("#bottom_panel_msg_list_grid").setGridWidth($(window).width() - 100)
      $("#bottom_panel_historytrack_list_grid").setGridWidth($(window).width() - 100)

      $(window).bind("resize", function() {
        "none" == document.getElementById("side_panel").style.display ? $("#bottom_panel_msg_list_grid").setGridWidth($(window).width() - 35) : $("#bottom_panel_msg_list_grid").setGridWidth($(window).width() - 100)
    }) 
    $(window).bind("resize", function () {
        if (document.getElementById("side_panel").style.display == "none") {
            $("#bottom_panel_historytrack_list_grid").setGridWidth($(window).width() - 35)
        } else {
            $("#bottom_panel_historytrack_list_grid").setGridWidth($(window).width() - 100)
        }
    })

   }
  }
  else { // if is menuBox hidden, display it
    var mapmenu=document.getElementById("map").className ;
    menuBox.style.display = "block";
    document.getElementById("map").style.left = "129px";
   document.getElementById("bottom_panel").style.left = "125px"; 

     if(mapmenu=="block width75 leaflet-container leaflet-touch leaflet-fade-anim leaflet-grab leaflet-touch-drag leaflet-touch-zoom")
   {
    document.getElementById("map").className="block width75 leaflet-container leaflet-touch leaflet-fade-anim leaflet-grab leaflet-touch-drag leaflet-touch-zoom";
    map.invalidateSize(!0)
   document.getElementById("bottom_panel").style.width = "64.9%";
     $("#bottom_panel_msg_list_grid").setGridWidth($(window).width() - 500)
      $("#bottom_panel_historytrack_list_grid").setGridWidth($(window).width() - 500)

      $(window).bind("resize", function() {
        "none" == document.getElementById("side_panel").style.display ? $("#bottom_panel_msg_list_grid").setGridWidth($(window).width() - 35) : $("#bottom_panel_msg_list_grid").setGridWidth($(window).width() - 500)
    }) 
    $(window).bind("resize", function () {
        if (document.getElementById("side_panel").style.display == "none") {
            $("#bottom_panel_historytrack_list_grid").setGridWidth($(window).width() - 35)
        } else {
            $("#bottom_panel_historytrack_list_grid").setGridWidth($(window).width() - 500)
        }
    }) 

   }else if (mapmenu=="block width80 leaflet-container leaflet-touch leaflet-fade-anim leaflet-grab leaflet-touch-drag leaflet-touch-zoom")
   {
    document.getElementById("map").className="block width75 leaflet-container leaflet-touch leaflet-fade-anim leaflet-grab leaflet-touch-drag leaflet-touch-zoom";
    map.invalidateSize(!0)
    document.getElementById("maplayer_top").style.left="135px";
    document.getElementById("bottom_panel").style.width = "64.9%";
     $("#bottom_panel_msg_list_grid").setGridWidth($(window).width() - 500)
      $("#bottom_panel_historytrack_list_grid").setGridWidth($(window).width() - 500)

      $(window).bind("resize", function() {
        "none" == document.getElementById("side_panel").style.display ? $("#bottom_panel_msg_list_grid").setGridWidth($(window).width() - 35) : $("#bottom_panel_msg_list_grid").setGridWidth($(window).width() - 500)
    }) 
    $(window).bind("resize", function () {
        if (document.getElementById("side_panel").style.display == "none") {
            $("#bottom_panel_historytrack_list_grid").setGridWidth($(window).width() - 35)
        } else {
            $("#bottom_panel_historytrack_list_grid").setGridWidth($(window).width() - 500)
        }
    }) 
   }
   else{
    document.getElementById("map").className="leaflet-container leaflet-touch leaflet-fade-anim leaflet-grab leaflet-touch-drag leaflet-touch-zoom";
    map.invalidateSize(!0)
    document.getElementById("maplayer_top").style.left="131px";
    document.getElementById("bottom_panel").style.width = "91.7%";
     $("#bottom_panel_msg_list_grid").setGridWidth($(window).width() - 200)
      $("#bottom_panel_historytrack_list_grid").setGridWidth($(window).width() - 200)

      $(window).bind("resize", function() {
        "none" == document.getElementById("side_panel").style.display ? $("#bottom_panel_msg_list_grid").setGridWidth($(window).width() - 35) : $("#bottom_panel_msg_list_grid").setGridWidth($(window).width() - 200)
    }) 
    $(window).bind("resize", function () {
        if (document.getElementById("side_panel").style.display == "none") {
            $("#bottom_panel_historytrack_list_grid").setGridWidth($(window).width() - 35)
        } else {
            $("#bottom_panel_historytrack_list_grid").setGridWidth($(window).width() - 200)
        }
    }) 

   }
  }
}

function chkboardingzoneinout() {
	$("#dialog_hotspotadd_zonetype").attr("disabled",false);
	 $.each($("input[name='vpoint[]']:checked"), function(){            
        if($(this).attr("id")=="dialog_boarding_startingpoint")
        {
        	$("#dialog_hotspotadd_zonetype").val("Zone Out");
        	$("#dialog_hotspotadd_zonetype").attr("disabled",true);
        }
        else if($(this).attr("id")=="dialog_boarding_endpoint")
        {
        	$("#dialog_hotspotadd_zonetype").val("Zone In");
        	$("#dialog_hotspotadd_zonetype").attr("disabled",true);
        }
        	
 });
}

function addboard_drop(hotspotid,selectid,selectedvalue)
{
			$.ajax({
	                type: "POST",
	                url: "func/fn_boarding.php",
	                data: {cmd: "select_hotspot_single",hotspot_id:hotspotid},
	                dataType: "json",
	                success: function(o) 
	                {      
                		selectid.options.length = 0,selectid.options.add(new Option("Select"));
	                       for (var i=0; i < o.length; i++) {
	                    	   selectid.options.add(new Option(o[i]['zonename'],o[i]['zonecode']))
	                    }
	                     
	                     if(selectedvalue!=null)selectid.value = selectedvalue
	                       
	                }
	            });
}



$(document).ready(function() {
	
	$('#dialog_studentadd_routename').on('change', function() {
		addboard_drop(this.value,document.getElementById("dialog_studentadd_morning_board"),null)
	});
	$('#dialog_studentadd_routename_down').on('change', function() {
		addboard_drop(this.value,document.getElementById("dialog_studentadd_evening_drop"),null)
	});


	$("#txtdaysinterval").prop('disabled', true);
	$('input[type="checkbox"]').on('change', function() {
		if(this.name=="vpoint[]")
		{
			$('input[name="' + this.name + '"]').not(this).prop('checked', false);
		}
		chkboardingzoneinout();
		 
	});
	getcommand();
	//BookingRefresh();
	document.getElementById( 'btn_b_search' ).onclick = function() {
		booking_search();
	};
	document.getElementById( 'btn_b_search_cancel' ).onclick = function() {
		fnmenuright(this);
	};
	
	document.getElementById( 'btn_b_book_cancel' ).onclick = function() {
		clearbooking();
	};
	
	document.getElementById( 'showRight' ).onclick = function() {
		fnmenuright(this);
	};
	
	document.getElementById( 'showRight' ).onclick = function() {fnmenuright(this);	};
	
	$('#btninqset').click(function() {
		if(nowcmd.length>0 && $('#cmd_devicetype  option:selected').val()!="Play FM100" && $('#cmd_object_listnew  option:selected').val()!="Select")
		{
		if(this.value=="Inquire")
		{
			this.value="Set";
			$('#cmd_stringnew').val(nowcmd[0]["command"].replace("DEVICED",$('#cmd_object_listnew  option:selected').val()).replace("INQSET",1));
		}
		else
		{
			this.value="Inquire";
			$('#cmd_stringnew').val(nowcmd[0]["command"].replace("DEVICED",$('#cmd_object_listnew  option:selected').val()).replace("INQSET",0));
		}
		}
	});
	
	$('html').click(function (e) {
		var subject = $("#showRight");
		if((e.target.id != "showRight" && e.target.id != "showRightD")&& $("#cbp-spmenu-s2" ).hasClass("cbp-spmenu-open"))
		{
			 $('#cbp-spmenu-s2').removeClass('cbp-spmenu-open');
			 $('.voverlay').css({"display":"none"});
		}
		else if(e.target.id == "showRight" || e.target.id == "showRightD")
		{
			 $('.voverlay').css({"display":"block"});
			
		}

	});
	
	$( "#dialog_allocate_hour_to_day" ).change(function() {
		 if($(this).val()=="Same")
	     {
			 $("#txtdaysinterval").val("1");
		 }
		 else
	     {
			 $("#txtdaysinterval").val("2");
	     }
	});
	
	$('#search_inputlist').keyup(function()
	{
		var searchArea = $('#dialog_report_object_list');
		searchFirstList($(this).val(), searchArea);
	});
	
	$('#search_inputlisthistory').keyup(function()
	{
		var searchArea = $('#side_panel_history_object_list');
		searchFirstList($(this).val(), searchArea);	         
	});
	
	$('#cmd_objectsearch').keyup(function()
	{
		var searchArea = $('#cmd_object_listnew');
		searchFirstList($(this).val(), searchArea);	         
	});
			
	
	$('#dialog_boarding_addobject_search').keyup(function()
	{
		var searchArea = $('#dialog_boarding_addobject');
		searchFirstList($(this).val(), searchArea);	   
		boeardingallocate('search');
	});
	
	$('#dialog_boarding_addobject_searchdaily').keyup(function()
	{
		var searchArea = $('#dialog_boarding_addobjectdaily');
		searchFirstList($(this).val(), searchArea);	   
		boeardingallocatedaily('search');
	});
    
	$('#dialog_rfidtrip_gpsi_objectnametxt').keyup(function()
	{	 
		var searchArea = $('#dialog_rfidtrip_gpsi_objectnameddl');
		searchFirstList($(this).val(), searchArea);	
	});
	
	$('#dialog_rfidtrip_gpsi_driverrfidtxt').keyup(function()
	{	 
		var searchArea = $('#dialog_rfidtrip_gpsi_driverrfidddl');
		searchFirstList($(this).val(), searchArea);	
	});			
});

function GetAddressht(lat, lng,id) 
 {
            var lat = parseFloat(lat);
            var lng = parseFloat(lng);
            var varp="http://maps.googleapis.com/maps/api/geocode/json?latlng="+lat+","+lng;
            $.get(varp, function(data, status){
            	document.getElementById(id).innerHTML =data.results[0].formatted_address;
          });
     
  }

//driver rfid  begin

function rfidtripdriverrfid(savecancel)
{
	if(savecancel=="save")
	{
		if($("#dialog_rfidtrip_driverrfid").val()=='')
		{
			notifyBox("error", la.ERROR, la.PLSFILLALLDETAILS);
			return;
		}
		 var dat = {cmd: "save_driverrfid",driverrfid: vrfidtrip.driverrfid,driverrfidno: $("#dialog_rfidtrip_driverrfid").val() };
		$.ajax({
                type: "POST",
                url: "func/fn__rfid.php",
                data: dat,
                success: function(o) {
                    switch (o) {
                        default: var p = "error";
                        var r = la.ERROR;
                        var q = o;notifyBox(p, r, q);
                        rfidtripdriverrfid('cancel');
                        rfidtripdriverrfid('refresh');
                        break;
                        case "Name Already Exists":
                         var p = "error";
                         var r = la.ERROR;
                        var q = o;notifyBox(p, r, q);
                        break;
                        case "OK":
                       
                        rfidtripdriverrfid('cancel');
                        rfidtripdriverrfid('refresh');
                        break;
                    }                 
                }
            });
		
		
	}
	else if(savecancel =="cancel")
	{   vrfidtrip.driverrfid=0;
		$("#dialog_rfidtrip_driverrfid").val("");	
	}
	else if(savecancel =="refresh")
	{   
		$("#rfidtrip_driverrfid_list_grid").trigger("reloadGrid");	
		//rfidemployee('search');
	}
	
}

function rfidtripdriverdelete(driverrfid)
{
	confirmDialog("Are you want to delete?", function(ve) {
	if (ve) {
	 var dat = {cmd: "delete_driverrfid",driverrfid:driverrfid};
		$.ajax({
                type: "POST",
                url: "func/fn__rfid.php",
                data: dat,
                success: function(o) {
                    switch (o) {
                        default: var p = "error";
                        var r = la.ERROR;
                        var q = o;notifyBox(p, r, q);
                          rfidtripdriverrfid('cancel');
                         rfidtripdriverrfid('refresh');
                        break;
                        case "OK":
                         rfidtripdriverrfid('cancel');
                          rfidtripdriverrfid('refresh');
                        break;
                    }                 
                }
            });
     }
	})
}

//driver rfid  end

//initialize





//code done by vetrivel.N
// FOR full screen

function launchIntoFullscreen() {
	//var element=document.documentElement;
	var element=document.getElementById("idbody");
  if(element.requestFullscreen) {
    element.requestFullscreen();		vfull=1;
  } else if(element.mozRequestFullScreen) {
    element.mozRequestFullScreen();		vfull=1;
  } else if(element.webkitRequestFullscreen) {
    element.webkitRequestFullscreen();		vfull=1;
  } else if(element.msRequestFullscreen) {
    element.msRequestFullscreen();		vfull=1;
  }
}


function exitFullscreen() {
  if(document.exitFullscreen) {
    document.exitFullscreen();vfull=0;
  } else if(document.mozCancelFullScreen) {
    document.mozCancelFullScreen();vfull=0;
  } else if(document.webkitExitFullscreen) {
    document.webkitExitFullscreen();vfull=0;
  }
}

var vfull=0;

function fnvetri()
{
	if(vfull==0)
	{
		launchIntoFullscreen();

	}
	else
	{
		exitFullscreen();
		
	}
}

$(document).keyup(function(e){
   if(e.keyCode ==27){
     	vfull=0;
   }
});

function openmap()
{
	  $('#alldiv').css({"display":"block"});
	  $('#loading_panel').css({"display":"none"});
}


function toglemoD(nowc)
{
	fnmenuright(nowc);
	openmap();
}

var vcommand=[];
var nowcmd =[];

function loadcommand()
{
 $.ajax({
        type: "POST",
        url: "func/command.php",
        dataType: "json",
        data: {cmd: "getcmd"},
        cache: false,
        success: function(g) {
	 		vcommand=g;
	 		fnfillcommanduse();
        },
        error: function(g, h) {
            notifyBox("info", la.INFORMATION, la.NOTHING_HAS_BEEN_FOUND);
        }
    });
}

function getobjectbydevicetype()
{   
	 var a = document.getElementById("cmd_object_listnew");
	 a.options.length = 0;
     a.options.add(new Option("Select"));
     
	 for (var h in settingsObjectData) 
	 {
		 var g = settingsObjectData[h];if (g.active == "true") 
		 {
			 if(g["device"]==$('#cmd_devicetype').val())
			 {
				 a.options.add(new Option(g["name"],h));
			 }
	     }
	 }
	 fnfillcommanduse();
       
}

function getcommand()
{

	$('#cmd_devicetype').on('change', function() {
		 //get vehicle list by device type
		 getobjectbydevicetype();
	});

	$('#cmd_typenew').on('change', function() {
		fnfillcommanduse();
	});
	
	$('#cmd_object_listnew').on('change', function() {
		replacecmd();
	});
	$('#cmd_gatewaynew').on('change', function() {
		replacecmdwithuse();
	});
}



function fnfillcommanduse()
{
	 nowcmd = $.grep(vcommand, function (ele, ind) {
	    return (ele.devtype === $('#cmd_devicetype').val() && ele.cmdtype === $('#cmd_typenew').val());
	});
	 var a = document.getElementById("cmd_gatewaynew");
   	 a.options.length = 0;
	if(nowcmd.length>0)
	{	
		 for($itr=0;$itr<nowcmd.length;$itr++)
		 {
 			 a.options.add(new Option(nowcmd[$itr]["use"],nowcmd[$itr]["use"]));
		 }
		 replacecmd();
	}
	else
	{a.options.add(new Option("Select"));}
}



function replacecmdwithuse()
{
	 nowcmd = $.grep(vcommand, function (ele, ind) {
	    return (ele.devtype === $('#cmd_devicetype').val() && ele.cmdtype === $('#cmd_typenew').val()&& ele.use === $('#cmd_gatewaynew').val());
	});

	 replacecmd();
}


function replacecmd()
{
	if(nowcmd.length>0 && $('#cmd_object_listnew').val()!="Select" && ($('#cmd_devicetype  option:selected').val()=="Play FM100" || $('#cmd_devicetype  option:selected').val()!="" ))
	{
		var veeee=$('#cmd_object_listnew').val();
		$('#cmd_stringnew').val(nowcmd[0]["command"].replace("DEVICED",$('#cmd_object_listnew  option:selected').val()));
	}
	else
	{
		$('#cmd_stringnew').val("");
		$('#btninqset').val("<->");
	}
}




function cmdSendnew() {
    var object = document.getElementById("cmd_object_listnew").value;
    var devicetype = document.getElementById("cmd_devicetype").value;
    var use = document.getElementById("cmd_gatewaynew").value;
    var cmd_type = document.getElementById("cmd_typenew").value;
    var cmd = document.getElementById("cmd_stringnew").value;
    if (cmdChecknew(true)) {
        cmdExecnew(object,devicetype,use,cmd_type,cmd);
    }
}

function cmdExecnew(object,devicetype,use,cmd_type,cmd) {
    if (!utilsCheckPrivileges("viewer")) {
        return
    }
    loadingData(!0);
    var a = settingsObjectData[object];
    if (cmd_type == "HEX") {
    	cmd = cmd.toUpperCase();
    }
    var e = {
        cmd: "save_cmd",
        imei: object,
        devicetype: devicetype,
        use: use,
       // sim_number: a,
        cmd_type: cmd_type,
        cmdo: cmd
    };
    $.ajax({
        type: "POST",
        url: "func/command.php",
        data: e,
        success: function(h) {
    	  loadingData(!1);
            switch (h) {
                default: var k = "error";
                var m = la.ERROR;
                var l = h;notifyBox(k, m, l);$('#cmd_stringnew').val("");
                break;
                case "OK":
                        $("#cmd_status_list_gridnew").trigger("reloadGrid");notifyBox("info", la.INFORMATION, la.COMMAND_SENT_FOR_EXECUTION, true);$('#cmd_stringnew').val("");;
                    break;
                case "error_sms":
                        $("#cmd_status_list_gridnew").trigger("reloadGrid");notifyBox("error", la.ERROR, la.UNABLE_TO_SEND_SMS_MESSAGE, true);$('#cmd_stringnew').val("");
                    break;
            }
        },
        error: function(h, k) {
        	loadingData(!1);
        }
    })
}

function cmdExecDeletenew(c) {
    if (!utilsCheckPrivileges("viewer")) {
        return
    }
    confirmDialog("Are you want to delete?", function(ve) {
    if (ve) {
    	
    var a = {
        cmd: "delete_cmd_exec",
        cmd_id: c
    };
    $.ajax({
        type: "POST",
        url: "func/command.php",
        data: a,
        success: function(d) {
            switch (d) {
                default: var e = "error";
                var g = la.ERROR;
                var f = d;notifyBox(e, g, f);
                break;
                case "OK":
                        $("#cmd_status_list_gridnew").trigger("reloadGrid");
            }
        }
    })
    
    	}
    })
}

function cmdChecknew(f) {
   
    //var e = document.getElementById("cmd_gateway").value;
	var e="123";
    var object = document.getElementById("cmd_object_listnew").value;
    var devicetype = document.getElementById("cmd_devicetype").value;
    var use = document.getElementById("cmd_gatewaynew").value;
    var cmd_type = document.getElementById("cmd_typenew").value;
    var cmd = document.getElementById("cmd_stringnew").value;
    
    if (object == "") {
        return false
    }
    if (cmd == "") {
        notifyBox("error", la.ERROR, la.COMMAND_CANT_BE_EMPTY, true);
        return false
    }
    if ((e == "SMS") && (f == true)) {
        var a = settingsObjectData[object]["sim_number"];
        if (a == "") {
            notifyBox("error", la.ERROR, la.OBJECT_SIM_CARD_NUMBER_IS_NOT_SET, true);
            return false
        }
    }
    if (cmd_type == "HEX") {
    	cmd = cmd.toUpperCase();
        if (!isHexValid(cmd)) {
            notifyBox("error", la.ERROR, la.COMMAND_HEX_NOT_VALID, true);
            return false
        }
    }
    return true
}


function getnotification()
{
	   var dataaa = {cmd: "select_notification"};
	  	$.ajax({
                type: "GET",
                url: "func/dashboard.php",
                data: dataaa,
                success: function(o) 
                {
	  				var dataadd="";
	  				for(var io=0;io<o.length;io++)
	  				{
	  					if(dataadd=="")
	  					dataadd=o[io].link;
	  					else
	  					dataadd=dataadd+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+o[io].link;
	  				}
	  		
	  				if(dataadd!="")
	  		 		$("#notify").html(dataadd);
                }
       });
	
}
function toogltraffic()
{
	if(trafficLayer!=null)
	{
		if(tglvel==true)
		{
			trafficLayer.setMap(null);
			tglvel=false;
		}
		else
		{
			switchMapType($("#top_panel_map_type").val());
		}
	}
	else if(tglvel==false)
	{
		switchMapType($("#top_panel_map_type").val());
	}
}
function clear_form_elements(ele) {

    $(ele).find(':input').each(function() {
        switch(this.type) {
            case 'password':
            case 'select-multiple':
            case 'select-one':
            case 'text':
            case 'textarea':
                $(this).val('');
                break;
            case 'checkbox':
            case 'radio':
                this.checked = false;
        }
    });

}

function changefueltype()
{
	if($("#difueltype").val()!="")
	{
		if($("#difueltype").val()=="FUEL Sensor")
		{
			  $("#difuel1").prop('disabled',false);
			  $("#difuel2").prop('disabled',false);
			  // $("#difuel3").prop('disabled',false);
			  // $("#difuel4").prop('disabled',false);
		}
		else
		{
			  $("#difuel1").prop('disabled',true);
			  $("#difuel2").prop('disabled',true);
			  // $("#difuel3").prop('disabled',true);
			  // $("#difuel4").prop('disabled',true);
			  
			  $("#difuel1").val('');
			  $("#difuel2").val('');
			  // $("#difuel3").val('');
			  // $("#difuel4").val('');
		}
	}
}

function mailenable_event(rpttyp)
{
 
 if(rpttyp=="fueldiscnt" || rpttyp=="fuelstolen" || rpttyp=="temp_abn" || rpttyp=="gpsantcut"  || rpttyp=="gsmantcut" || rpttyp=="overspeed")
 {
  $('#dialog_settings_event_notify_email').prop('disabled', false);
  $('#dialog_settings_event_notify_email_address').prop('disabled', false);
 }
 else
 {
  $('#dialog_settings_event_notify_email').prop('disabled', true);
  $('#dialog_settings_event_notify_email_address').prop('disabled', true);
 }
}


function tglnotify()
{
	if(notifyshow==true)
	{
		$('#idnoti').removeClass('demo-icon icon-bookmark');
		$('#idnoti').addClass('demo-icon icon-bookmark-empty');
		notifyshow=false;
	}
	else
	{
		$('#idnoti').removeClass('demo-icon icon-bookmark-empty');
		$('#idnoti').addClass('demo-icon icon-bookmark');
		notifyshow=true;
		eventsCheckForNew();
	}
}

// function tglnotify1(){
//      document.getElementById("idnoti").className = "mystyle";
// }

//code done by vetrivelht

function historyLoadRouteht() {
    if (!utilsCheckPrivileges("history")) {
        return
    }
    $("#dialog_loading").dialog("open");
    var e = document.getElementById("left_panel_history_reports_object_list").value;
    var c = $("#left_panel_history_reports_object_list  > option:selected").text();
    var d = document.getElementById("left_panel_history_reports_date_from").value + " " + document.getElementById("left_panel_history_reports_hour_from").value + ":" + document.getElementById("left_panel_history_reports_minute_from").value + ":00";
    var b = document.getElementById("left_panel_history_reports_date_to").value + " " + document.getElementById("left_panel_history_reports_hour_to").value + ":" + document.getElementById("left_panel_history_reports_minute_to").value + ":00";
    var a = document.getElementById("left_panel_history_reports_stop_duration").value;
    var f = {
        cmd: "load_route_data",
        imei: e,
        dtf: d,
        dtt: b,
        min_stop_duration: a
    };
    $.ajax({
        type: "POST",
        url: "func/fn_history.php",
        data: f,
        dataType: "json",
        cache: false,
        success: function (g) {

            $("#bottom_panel_historytrack_list_grid").setGridParam({
                url: "func/fn_history.php?cmd=load_historytrack&imei=" + e + "&dtf=" + d + "&dtt=" + b
            });
            $("#bottom_panel_historytrack_list_grid").trigger("reloadGrid")
        },
        error: function (g, h) {
            notifyBox("info", la.INFORMATION, la.NOTHING_HAS_BEEN_FOUND);
            $("#dialog_loading").dialog("close")
        }
    })
}

function dahsboardopen()
{
	$('#newdashboard').css({"display":"block"});
	$('#dashboardpop3').dialog('open');
	//$('#ui-id-107').append('<button type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-icon-only ui-dialog-titlebar-close" role="button" title="Toogle"><span id="showRight"   class="mapleftobtitle"><i class="fa fa-code"></i></span><span class="ui-button-text">Toggle</span></button>');
}

function openmapview()
{
	$('#newdashboard').css({"display":"none"});
	//document.getElementById("ui-id-23").click();
}

$(window).bind("resize", function() {
 $("#dashboard_detail").setGridWidth($(window).width() - 368)
}).trigger("resize")


function changeobjectreport()
{
	$("#search_inputlist").val("");
	var vobjectsel=document.getElementById("dialog_reports_object_listrpt");
	vobjectsel.options.length=0;
	var vobject=document.getElementById("dialog_report_object_list");
	vobject.options.length=0;
	var rpttyp=document.getElementById("dialog_reports_type").value;
	for (var h in settingsObjectData) 
	{
	   var g = settingsObjectData[h];
	   if (g.active == "true" ) 
	   {
		   if(rpttyp=="ifsinfograph" || rpttyp=="fuelana" ||rpttyp=="fuelgraph")
		   {
			   if( g["fueltype"]=="FUEL Sensor")
			   vobject.options.add(new Option(g["name"],h));
		   }
		   else if(rpttyp=="tempgraph" || rpttyp=="tempreport" )
		   {
			   if(g["temp1"]=="YES" || g["temp2"]=="YES" || g["temp3"]=="YES")
			   vobject.options.add(new Option(g["name"],h));
		   }
		   else
		   {
			   vobject.options.add(new Option(g["name"],h));
		   }
	   }
	}
}

function changeobjectreport()
{
	//temp function write in karthik system  code done by vetrivel.N
   var rpttyp=document.getElementById("dialog_reports_type").value;   
   var b = document.getElementById("dialog_reports_object_listrpt");
    var v=settingsObjectData;
        if(rpttyp=="ifsinfograph" || rpttyp=="fuelana" ||rpttyp=="fuelgraph" || rpttyp=="tempgraph" || rpttyp=="tempreport")
        {
         for(i=0;i<b.options.length;i++)    
         {
            if(rpttyp=="ifsinfograph" || rpttyp=="fuelana" ||rpttyp=="fuelgraph")
            {  
                 if(v[b.options[i].value].fueltype=='FUEL Sensor')
                 {
                    b.options[i].selected=true;   
                 }    
                else
                 {
                    b.options[i].selected=false;
                 }
            }
            else if(rpttyp=="tempgraph" || rpttyp=="tempreport")
            {             
                if(v[b.options[i].value].temp1=='YES' || v[b.options[i].value].temp2=='YES' || v[b.options[i].value].temp3=='YES')
                 {
                    b.options[i].selected=true;   
                 }    
                else
                 {
                    b.options[i].selected=false;
                 }
            }
          }
        }
        else
        {
            $("#dialog_reports_object_listrpt option").prop("selected",true);
        }
 }

function fnloadeventDB(data,sosalert)
{
	
	var edata={
            "month":'Today',
	        "gpsantcut": 0,
	        "gsmantcut": 0,
	        "fuelstolen": 0,
	        "fueldiscnt": 0,
	        "pwrcut": 0,
	        "lowbat": 0,
	        "lowdc": 0,
            "sos":0,
            "sosaction":0
	    }
	if(data.length>0)
	{
		for(var iv=0;iv<data.length;iv++)
		{
			if(data[iv]['type']=='gpsantcut')
			{
				edata.gpsantcut=data[iv]['event'];
			}
			else if(data[iv]['type']=='gsmantcut')
			{
				edata.gsmantcut=data[iv]['event'];
			}
			else if(data[iv]['type']=='fuelstolen')
			{
				edata.fuelstolen=data[iv]['event'];
			}
			else if(data[iv]['type']=='fueldiscnt')
			{
				edata.fueldiscnt=data[iv]['event'];
			}
			else if(data[iv]['type']=='pwrcut')
			{
				edata.pwrcut=data[iv]['event'];
			}
			else if(data[iv]['type']=='lowbat')
			{
				edata.lowbat=data[iv]['event'];
			}
			else if(data[iv]['type']=='lowdc')
			{
				edata.lowdc=data[iv]['event'];
			}
             else if(data[iv]['type']=='sos')
            {
                edata.sos=data[iv]['event']-sosalert;
                edata.sosaction=sosalert;
            }
		}
	}

	var chart = AmCharts.makeChart("eventgauge", {
	    "type": "serial",
		"theme": "light",
	    "legend": {
	        "horizontalGap": 10,
	        "maxColumns": 1,
	        "position": "right",
			"useGraphSettings": true,
			"markerSize": 10
	    },
	    "dataProvider": [ edata ],
	    "valueAxes": [{
	        "stackType": "regular",
	        "axisAlpha": 0.5,
	        "gridAlpha": 0
	    }],
	    "graphs": [{
	        "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
	        "fillAlphas": 0.8,
	        "labelText": "[[value]]",
	        "lineAlpha": 0.3,
	        "title": "GPS antenna cut",
	        "type": "column",
			"color": "#000000",
	        "valueField": "gpsantcut"
	    }, {
	        "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
	        "fillAlphas": 0.8,
	        "labelText": "[[value]]",
	        "lineAlpha": 0.3,
	        "title": "GSM antenna cut",
	        "type": "column",
			"color": "#000000",
	        "valueField": "gsmantcut"
	    }, {
	        "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
	        "fillAlphas": 0.8,
	        "labelText": "[[value]]",
	        "lineAlpha": 0.3,
	        "title": "Fuel Dip",
	        "type": "column",
			"color": "#000000",
	        "valueField": "fuelstolen"
	    }, {
	        "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
	        "fillAlphas": 0.8,
	        "labelText": "[[value]]",
	        "lineAlpha": 0.3,
	        "title": "Fuelwire disconnect",
	        "type": "column",
			"color": "#000000",
	        "valueField": "fueldiscnt"
	    }, {
	        "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
	        "fillAlphas": 0.8,
	        "labelText": "[[value]]",
	        "lineAlpha": 0.3,
	        "title": "Powercut",
	        "type": "column",
			"color": "#000000",
	        "valueField": "pwrcut"
	    }, {
	        "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
	        "fillAlphas": 0.8,
	        "labelText": "[[value]]",
	        "lineAlpha": 0.3,
	        "title": "Low Battery",
	        "type": "column",
			"color": "#000000",
	        "valueField": "lowbat"
	    }
	    , {
	        "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
	        "fillAlphas": 0.8,
	        "labelText": "[[value]]",
	        "lineAlpha": 0.3,
	        "title": "Low DC",
	        "type": "column",
			"color": "#000000",
	        "valueField": "lowdc"
	    }],
	    "rotate": true,
	    "categoryField": "month",
	    "categoryAxis": {
	        "gridPosition": "start",
	        "axisAlpha": 0,
	        "gridAlpha": 0,
	        "position": "left"
	    },
	    "export": {
	    	"enabled": true
	     }
	});
	
        var chart = AmCharts.makeChart("soschart", {
        "type": "serial",
        "theme": "light",
        "legend": {
            "horizontalGap": 10,
            "maxColumns": 2,
            "position": "top",
            "useGraphSettings": true,
            "markerSize": 10
        },
        "dataProvider": [ edata ],
        "valueAxes": [{
            "stackType": "regular",
            "axisAlpha": 0,
            "gridAlpha": 0
        }],
        "graphs": [{
            "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
            "fillAlphas": 0.8,
            "labelText": "[[value]]",
            "lineAlpha": 0.3,
            "title": "Emergency Alert",
            "type": "column",
            "fillColors":"#d46868",
            "color": "#000000",
            "valueField": "sos"
        },{
            "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
            "fillAlphas": 0.8,
            "labelText": "[[value]]",
            "lineAlpha": 0.3,
            "title": "Action Taken",
            "type": "column",
            "fillColors":"#9cc745",
            "color": "#000000",
            "valueField": "sosaction"
        }],
        "rotate": false,
        "categoryField": "month",
        "categoryAxis": {
            "gridPosition": "start",
            "axisAlpha": 0,
            "gridAlpha": 0,
            "position": "left"
        },
        "export": {
            "enabled": true
         }
    });

	
}

function openmap()
{
	  $('#alldiv').css({"display":"block"});
	  $('#loading_panel').css({"display":"none"});
}



function openevent()
{
	$('#tglDBMV').click();
	document.getElementById("ui-id-32").click();
    document.getElementById("myDropdown2").style.display = "block";
    document.getElementById("myDropdown").style.display = "none";
    document.getElementById("myDropdown3").style.display = "none";
    document.getElementById("myDropdown4").style.display = "none";
}


function reportsOpenAmb() {
    utilsCheckPrivileges("reports") && $("#dialog_reports_amb").dialog("open")
}



function reportPropertiesAmb(e) {
    switch (e) {
        default:
        	$("#dialog_report_properties_amb").dialog("open");
        var t = e;reportsData.edit_report_id_amb = t,
        document.getElementById("dialog_report_name_amb").value = reportsData.reportsamb[t].name,
        document.getElementById("dialog_report_type_amb").value = reportsData.reportsamb[t].type,
        reportsSwitchType(),
        document.getElementById("dialog_report_format_amb").value = reportsData.reportsamb[t].format,
        document.getElementById("dialog_report_bookby_amb").value = reportsData.reportsamb[t].book_by,
        
        document.getElementById("txt_b_address_amb").value = reportsData.reportsamb[t].address,
        document.getElementById("txt_b_peoplecount_amb").value = reportsData.reportsamb[t].people_count,
        document.getElementById("txt_b_phone_amb").value = reportsData.reportsamb[t].phone,
        document.getElementById("txt_b_pateintname_amb").value = reportsData.reportsamb[t].patient_name,
        document.getElementById("ddl_age_from_rpt_amb").value = reportsData.reportsamb[t].age_from,
        document.getElementById("ddl_age_to_rpt_amb").value = reportsData.reportsamb[t].age_to,
        document.getElementById("ddl_b_gender_amb").value = reportsData.reportsamb[t].gender,
        document.getElementById("ddl_b_breathing_amb").value = reportsData.reportsamb[t].breath,
        document.getElementById("ddl_b_conscious_amb").value = reportsData.reportsamb[t].conscious,
        
        document.getElementById("dialog_report_bookingstatus_amb").value = reportsData.reportsamb[t].book_status,
        document.getElementById("dialog_report_crewtime_amb").value = reportsData.reportsamb[t].crewtime,
        document.getElementById("dialog_report_hour_amb").value = reportsData.reportsamb[t].stop_duration,
        $("#ddl_b_emergency_amb").select2("val", reportsData.reportsamb[t].emergency);
        
        
        var _ = reportsData.reportsamb[t].schedule_period;
        "d" == _ ? (document.getElementById("dialog_report_schedule_period_daily").checked = !0, document.getElementById("dialog_report_schedule_period_weekly").checked = !1) : "w" == _ ? (document.getElementById("dialog_report_schedule_period_daily").checked = !1, document.getElementById("dialog_report_schedule_period_weekly").checked = !0) : "dw" == _ ? (document.getElementById("dialog_report_schedule_period_daily").checked = !0, document.getElementById("dialog_report_schedule_period_weekly").checked = !0) : (document.getElementById("dialog_report_schedule_period_daily").checked = !1, document.getElementById("dialog_report_schedule_period_weekly").checked = !1),
        document.getElementById("dialog_report_schedule_email_address").value = reportsData.reportsamb[t].schedule_email_address,
        document.getElementById("dialog_report_filter").value = 2,
        switchHistoryReportsDateFilter("ambulance"),
        $("#dialog_report_properties_amb").dialog("open");
        break;
        case "add":
        	try{
            	if(settingsUserData.ambulance=="true")
            		$("#ddl_b_emergency_amb").select2();
            }catch(erv)
            {
            	console.log(erv);
            }
            reportsData.edit_report_id_amb = !1,
            document.getElementById("dialog_report_name_amb").value = "",
            document.getElementById("dialog_report_type_amb").value = "ambulance_general",
            document.getElementById("dialog_report_format_amb").value = "html",
            document.getElementById("dialog_report_bookby_amb").value = "All",
            document.getElementById("dialog_report_speed_limit").value = "",
            document.getElementById("txt_b_address_amb").value = "",
            document.getElementById("txt_b_peoplecount_amb").value = "",
            document.getElementById("txt_b_phone_amb").value = "",
            document.getElementById("txt_b_pateintname_amb").value = "",
            document.getElementById("ddl_age_from_rpt_amb").value = "0",
            document.getElementById("ddl_age_to_rpt_amb").value = "100",
            document.getElementById("ddl_b_gender_amb").value = "All",
            document.getElementById("ddl_b_breathing_amb").value = "All",
            document.getElementById("ddl_b_conscious_amb").value = "All",
            
            document.getElementById("dialog_report_bookingstatus_amb").value = "All",
            document.getElementById("dialog_report_crewtime_amb").value = "All",
            document.getElementById("dialog_report_hour_amb").value = "24",
            $("#ddl_b_emergency_amb").select2("val", ["All"]),
            document.getElementById("dialog_report_schedule_period_daily").checked = !1,
            document.getElementById("dialog_report_schedule_period_weekly").checked = !1,
            document.getElementById("dialog_report_schedule_email_address").value = "",
            document.getElementById("dialog_report_filter").value = 2,
            switchHistoryReportsDateFilter("ambulance"),
            //initSelectList("report_object_list"),
            $("#dialog_report_properties_amb").dialog("open");
            break;
        case "cancel":
                $("#dialog_report_properties_amb").dialog("close");
            break;
        case "save":
                if (!utilsCheckPrivileges("viewer")) return;
            var c = document.getElementById("dialog_report_name_amb").value,
            	emer=$("#ddl_b_emergency_amb").select2("val"),
            	address= document.getElementById("txt_b_address_amb").value,
            	book_by= document.getElementById("dialog_report_bookby_amb").value,
            	peoplecount=document.getElementById("txt_b_peoplecount_amb").value,
            	phone=document.getElementById("txt_b_phone_amb").value,
            	patientname=document.getElementById("txt_b_pateintname_amb").value,
            	age_from=document.getElementById("ddl_age_from_rpt_amb").value,
            	age_to=document.getElementById("ddl_age_to_rpt_amb").value,
            	gender=document.getElementById("ddl_b_gender_amb").value,
            	breath=document.getElementById("ddl_b_breathing_amb").value,
            	conscious=document.getElementById("ddl_b_conscious_amb").value,
            	book_status= document.getElementById("dialog_report_bookingstatus_amb").value,
            	crewtime=document.getElementById("dialog_report_crewtime_amb").value,
            	taken= document.getElementById("dialog_report_hour_amb").value,
            	g = document.getElementById("dialog_report_type_amb").value,
            	m = document.getElementById("dialog_report_format_amb").value,
            	u = false,
                p = false,
                v = false,
                y = 1,
                E = 60,
                b = "",
                h = "",
                hv = "",
                f = "",
                d = "";
            if ("" == emer) return void notifyBox("error", la.ERROR, la.AT_LEAST_ONE_EMERGENCY);
            if ("" == c) return void notifyBox("error", la.ERROR, la.NAME_CANT_BE_EMPTY);

            var I = document.getElementById("dialog_report_schedule_period_daily").checked,
                D = document.getElementById("dialog_report_schedule_period_weekly").checked,
                B = document.getElementById("dialog_report_schedule_email_address").value,
                _ = "";
            if (1 == I && (_ = "d"), 1 == D && (_ += "w"), "" != _)
                for (var O = B.split(","), j = 0; j < O.length; j++)
                    if (O[j] = O[j].trim(), !isEmailValid(O[j])) return void notifyBox("error", la.ERROR, la.THIS_EMAIL_IS_NOT_VALID);
            var R = {
                cmd: "save_report",
                report_id: reportsData.edit_report_id_amb,
                name: c,
                type: g,
                format: m,
                show_coordinates: u,
                show_addresses: p,
                zones_addresses: v,
                stop_duration: y,
                speed_limit: E,
                imei: b,
                zone_ids: h,
                zone_idsv: hv,
                sensor_names: f,
                data_items: d,
                schedule_period: _,
                schedule_email_address: B,
                emergency:emer,
                address:address,
                peoplecount:peoplecount,
                phone:phone,
                patientname:patientname,
                age_from:age_from,
                age_to:age_to,
                gender:gender,
                breath:breath,
                conscious:conscious,
                book_status:book_status,
                crewtime:crewtime,
                taken:taken,
                book_by:book_by
            };$.ajax({
                type: "POST",
                url: "func/fn_reportsamb.php",
                data: R,
                cache: !1,
                success: function(e) {
                    "OK" == e && (reportsReloadAmb(), $("#dialog_report_properties_amb").dialog("close"), notifyBox("info", la.INFORMATION, la.CHANGES_SAVED_SUCCESSFULLY));
                }
            });
            break;
        case "generate":
                var c = document.getElementById("dialog_report_name_amb").value,
                emer=$("#ddl_b_emergency_amb").select2("val"),
                address= document.getElementById("txt_b_address_amb").value,
                book_by= document.getElementById("dialog_report_bookby_amb").value,
                peoplecount=document.getElementById("txt_b_peoplecount_amb").value,
                phone=document.getElementById("txt_b_phone_amb").value,
                patientname=document.getElementById("txt_b_pateintname_amb").value,
                age_from=document.getElementById("ddl_age_from_rpt_amb").value,
                age_to=document.getElementById("ddl_age_to_rpt_amb").value,
                gender=document.getElementById("ddl_b_gender_amb").value,
                breath=document.getElementById("ddl_b_breathing_amb").value,
                conscious=document.getElementById("ddl_b_conscious_amb").value,
                book_status= document.getElementById("dialog_report_bookingstatus_amb").value,
                crewtime=document.getElementById("dialog_report_crewtime_amb").value,
                taken= document.getElementById("dialog_report_hour_amb").value,
                g = document.getElementById("dialog_report_type_amb").value,
                m = document.getElementById("dialog_report_format_amb").value,
                u = false,
                p = false,
                v = false,
                y = 1,
                E = 60,
                b = "",
                h = "",
                hv = "",
                f = "",
                d = "",
                T = $("#ambrpt_dialog_report_date_from").val() + " " + $("#ambrpt_dialog_report_hour_from").val() + ":" + $("#ambrpt_dialog_report_minute_from").val() + ":00",
                k = $("#ambrpt_dialog_report_date_to").val() + " " + $("#ambrpt_dialog_report_hour_to").val() + ":" + $("#ambrpt_dialog_report_minute_to").val() + ":00";
            "" == c && (c = document.getElementById("dialog_report_type_amb").options[document.getElementById("dialog_report_type_amb").selectedIndex].text);
            var R = {
                cmd: "report",
                name: c,
                type: g,
                format: m,
                show_coordinates: u,
                show_addresses: p,
                zones_addresses: v,
                stop_duration: y,
                speed_limit: E,
                imei: b,
                zone_ids: h,
                zone_idsv: hv,
                sensor_names: f,
                data_items: d,
                dtf: T,
                dtt: k,
                rpt_type : "A",
                emergency:emer,
                address:address,
                peoplecount:peoplecount,
                phone:phone,
                patientname:patientname,
                age_from:age_from,
                age_to:age_to,
                gender:gender,
                breath:breath,
                conscious:conscious,
                book_status:book_status,
                crewtime:crewtime,
                taken:taken,
                book_by:book_by
            };reportGenerateAmb(R)
    }
}


function reportsGeneratedReloadAmb() {
    $("#reports_generated_list_grid_amb").trigger("reloadGrid")
}

function reportsGeneratedOpenAmb(e) {
    loadingData(!0);
    var t = {
        cmd: "open_generated",
        report_id: e
    };
    $.ajax({
        type: "POST",
        url: "func/fn_reportsamb.php",
        data: t,
        dataType: "json",
        cache: !1,
        success: function(e) {
            loadingData(!1), $.generateFile({
                filename: e.filename,
                content: e.content,
                script: "func/fn_saveas.php?format=" + e.format
            })
        },
        error: function() {
            loadingData(!1)
        }
    })
}

function reportsGeneratedDeleteAmb(e) {
    utilsCheckPrivileges("viewer") && confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE, function(t) {
        if (t) {
            var a = {
                cmd: "delete_report_generated",
                report_id: e
            };
            $.ajax({
                type: "POST",
                url: "func/fn_reportsamb.php",
                data: a,
                success: function(e) {
                    "OK" == e && reportsGeneratedReloadAmb()
                }
            })
        }
    })
}

function reportsGeneratedDeleteSelectedAmb() {
    if (utilsCheckPrivileges("viewer")) {
        var e = $("#reports_generated_list_grid_amb").jqGrid("getGridParam", "selarrrow");
        return "" == e ? void notifyBox("error", la.ERROR, la.NO_ITEMS_SELECTED) : void confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE_SELECTED_ITEMS, function(t) {
            if (t) {
                var a = {
                    cmd: "delete_selected_reports_generated",
                    items: e
                };
                $.ajax({
                    type: "POST",
                    url: "func/fn_reports.php",
                    data: a,
                    success: function(e) {
                        "OK" == e && reportsGeneratedReloadAmb()
                    }
                })
            }
        })
    }
}

function reportsReloadAmb() {
    reportsLoadDataAmb(), $("#report_list_grid_amb").trigger("reloadGrid")
}


function reportsLoadDataAmb() {
    var e = {
        cmd: "load_report_data"
    };
    $.ajax({
        type: "POST",
        url: "func/fn_reportsamb.php",
        data: e,
        dataType: "json",
        cache: !1,
        success: function(e) {
            reportsData.reportsamb = e
        }
    })
}

function reportsDeleteAmb(e) {
    utilsCheckPrivileges("viewer") && confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE, function(t) {
        if (t) {
            var a = {
                cmd: "delete_report",
                report_id: e
            };
            $.ajax({
                type: "POST",
                url: "func/fn_reports.php",
                data: a,
                success: function(e) {
                    "OK" == e && reportsReloadAmb()
                }
            })
        }
    })
}

function reportsDeleteSelectedAmb() {
    if (utilsCheckPrivileges("viewer")) {
        var e = $("#report_list_grid_amb").jqGrid("getGridParam", "selarrrow");
        return "" == e ? void notifyBox("error", la.ERROR, la.NO_ITEMS_SELECTED) : void confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE_SELECTED_ITEMS, function(t) {
            if (t) {
                var a = {
                    cmd: "delete_selected_reports",
                    items: e
                };
                $.ajax({
                    type: "POST",
                    url: "func/fn_reports.php",
                    data: a,
                    success: function(e) {
                        "OK" == e && reportsReloadAmb()
                    }
                })
            }
        })
    }
}



function reportGenerateAmb(e) {
    return "overspeed" != e.type && "underspeed" != e.type && "rag" != e.type || "" != e.speed_limit ?  "" == e.emergency ? void notifyBox("error", la.ERROR, la.AT_LEAST_ONE_EMERGENCY) : "logic_sensors" != e.type && "sensor_graph" != e.type || "" != e.sensor_names ? 
    		(loadingData(!0), void $.ajax({
        type: "POST",
        url: "func/fn_reports.gen.php",
        data: e,
        cache: !1,
        success: function(t) {
            loadingData(!1), $.generateFile({
                filename: e.type + "_" + e.dtf + "_" + e.dtt,
                content: t,
                script: "func/fn_saveas.php?format=" + e.format
            }), reportsGeneratedReloadAmb()
        },
        error: function() {
            loadingData(!1)
        }
    })) : void notifyBox("error", la.ERROR, la.AT_LEAST_ONE_SENSOR_SELECTED) : void notifyBox("error", la.ERROR, la.SPEED_LIMIT_CANT_BE_EMPTY)
}



function settingsObjectWorkingHourDelete(e) {
    if (utilsCheckPrivileges("viewer")) {
        var t = settingsEditData.object_imei;
        confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE, function(a) {
            if (a) {
                var o = {
                    cmd: "delete_object_working_hour",
                    field_id: e,
                    imei: t
                };
                $.ajax({
                    type: "POST",
                    url: "func/fn_settings.workinghour.php",
                    data: o,
                    success: function(e) {
                        "OK" == e && (settingsReloadObjects(), $("#settings_object_working_hour_list_grid").trigger("reloadGrid"))
                    }
                })
            }
        })
    }
}

function settingsObjectWorkingHourDeleteSelected() {
    if (utilsCheckPrivileges("viewer")) {
        var e = settingsEditData.object_imei,
            t = $("#settings_object_working_hour_list_grid").jqGrid("getGridParam", "selarrrow");
        return "" == t ? void notifyBox("error", la.ERROR, la.NO_ITEMS_SELECTED) : void confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE_SELECTED_ITEMS, function(a) {
            if (a) {
                var o = {
                    cmd: "delete_selected_object_working_hour",
                    items: t,
                    imei: e
                };
                $.ajax({
                    type: "POST",
                    url: "func/fn_settings.workinghour.php",
                    data: o,
                    success: function(e) {
                        "OK" == e && (settingsReloadObjects(), $("#settings_object_working_hour_list_grid").trigger("reloadGrid"))
                    }
                })
            }
        })
    }
}

function settingsObjectWorkingHourProperties(e) {
    var t = settingsEditData.object_imei;
    switch (e) {
        default: var a = e;settingsEditData.workinghour_field_id = a,
        document.getElementById("dialog_settings_work_time_from").value = settingsObjectData[t].timing[a].from,
        document.getElementById("dialog_settings_work_time_to").value = settingsObjectData[t].timing[a].to,
        document.getElementById("dialog_settings_work_hour_type").value = settingsObjectData[t].timing[a].type,
        document.getElementById("dialog_settings_work_hour_day").value = settingsObjectData[t].timing[a].day,
        document.getElementById("dialog_settings_work_hour_status").checked = strToBoolean(settingsObjectData[t].timing[a].status),

        $("#dialog_settings_working_hour_field_properties").dialog("open");
        break;
        case "add":
                settingsEditData.workinghour_field_id = !1,
            document.getElementById("dialog_settings_work_time_from").value = "00:00",
            document.getElementById("dialog_settings_work_time_to").value = "00:00",
            document.getElementById("dialog_settings_work_hour_type").value = "Same Day",
            document.getElementById("dialog_settings_work_hour_day").value = "Sun",
            document.getElementById("dialog_settings_work_hour_status").checked = !0,
            $("#dialog_settings_working_hour_field_properties").dialog("open");
            break;
        case "cancel":
                $("#dialog_settings_working_hour_field_properties").dialog("close");
            break;
        case "save":
                if (!utilsCheckPrivileges("viewer")) return;
            var ft = document.getElementById("dialog_settings_work_time_from").value,
                tt = document.getElementById("dialog_settings_work_time_to").value,
                type = document.getElementById("dialog_settings_work_hour_type").value,
                day = document.getElementById("dialog_settings_work_hour_day").value,
                status = document.getElementById("dialog_settings_work_hour_status").checked;
          
            var l = {
                cmd: "save_object_working_hour",
                field_id: settingsEditData.workinghour_field_id,
                imei: t,
               	ft:ft,
                tt:tt,
                type:type,
                day:day,
                status:status
            };$.ajax({
                type: "POST",
                url: "func/fn_settings.workinghour.php",
                data: l,
                cache: !1,
                success: function(e) {
                    "OK" == e && (settingsReloadObjects(), $("#settings_object_working_hour_list_grid").trigger("reloadGrid"), $("#dialog_settings_working_hour_field_properties").dialog("close"), notifyBox("info", la.INFORMATION, la.CHANGES_SAVED_SUCCESSFULLY))
                }
            })
    }
}


function settingsShiftAllocation(e) {
    switch (e) {
        default:
        document.getElementById("dialog_settings_shift_allocation_crew").value = settingsObjectData[t].timing[a].from,
        document.getElementById("dialog_settings_shift_allocation_date").value = settingsObjectData[t].timing[a].to,

        $("#dialog_settings_shift_allocation").dialog("open");
        break;
        case "add":
            document.getElementById("dialog_settings_shift_allocation_crew").value = "Select",
            document.getElementById("dialog_settings_shift_allocation_date").value = "",
            $("#dialog_settings_shift_allocation").dialog("open");
            break;
        case "cancel":
                $("#dialog_settings_shift_allocation").dialog("close");
            break;
        case "save":
                if (!utilsCheckPrivileges("viewer")) return;
            var crew = document.getElementById("dialog_settings_shift_allocation_crew").value,
                date = document.getElementById("dialog_settings_shift_allocation_date").value;
               
            if(crew=="Select" || date=="")
      		{
      			 notifyBox("error", la.ERROR, la.PLSFILLALLDETAILS);
      			 return ;
      		}
            
            var l = {
                cmd: "save_shift_allocation",
                date:date,
                crew:crew
            };$.ajax({
                type: "POST",
                url: "func/fn_settings.shiftallocation.php",
                data: l,
                cache: !1,
                success: function(e) {
                    "OK" == e ? ($("#settings_main_shift_allocation_list_grid").trigger("reloadGrid"), $("#dialog_settings_shift_allocation").dialog("close"), notifyBox("info", la.INFORMATION, la.CHANGES_SAVED_SUCCESSFULLY)) : notifyBox("error", la.ERROR,e);
                }
            })
    }
}


function settingsShiftAllocationDelete(e) {
    if (utilsCheckPrivileges("viewer")) {
        confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE, function(a) {
            if (a) {
                var o = {
                    cmd: "delete_shift_allocation",
                    field_id: e
                };
                $.ajax({
                    type: "POST",
                    url: "func/fn_settings.shiftallocation.php",
                    data: o,
                    success: function(e) {
                        "OK" == e && ($("#settings_main_shift_allocation_list_grid").trigger("reloadGrid"))
                    }
                })
            }
        })
    }
}

function settingsShiftAllocationDeleteSelected() {
    if (utilsCheckPrivileges("viewer")) {
        var t = $("#settings_main_shift_allocation_list_grid").jqGrid("getGridParam", "selarrrow");
        return "" == t ? void notifyBox("error", la.ERROR, la.NO_ITEMS_SELECTED) : void confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE_SELECTED_ITEMS, function(a) {
            if (a) {
                var o = {
                    cmd: "delete_selected_shift_allocation",
                    items: t
                };
                $.ajax({
                    type: "POST",
                    url: "func/fn_settings.shiftallocation.php",
                    data: o,
                    success: function(e) {
                        "OK" == e && ($("#settings_main_shift_allocation_list_grid").trigger("reloadGrid"))
                    }
                })
            }
        })
    }
}


// Nandha
// document.getElementById("bottom_panel").style.width = "91.7%";      

function displaydas(){
var clasch=document.getElementById("ping").className;
    if(clasch=='pincl'){
        if(document.getElementById("map").style.left=='0px'){
            document.getElementById("map").className = "block width75 leaflet-container leaflet-touch leaflet-fade-anim leaflet-grab leaflet-touch-drag leaflet-touch-zoom";
      map.invalidateSize(!0)
        }else{
      document.getElementById("map").className = "block width75 leaflet-container leaflet-touch leaflet-fade-anim leaflet-grab leaflet-touch-drag leaflet-touch-zoom";
      map.invalidateSize(!0)   
      }        
      document.getElementById("ping").className = "pingch";
      document.getElementById("chpinim").src="img/unpin.png";
      document.getElementById("bottom_panel").style.width = "64.7%";
      $("#bottom_panel_msg_list_grid").setGridWidth($(window).width() - 500)
      $("#bottom_panel_historytrack_list_grid").setGridWidth($(window).width() - 500)

      $(window).bind("resize", function() {
        "none" == document.getElementById("side_panel").style.display ? $("#bottom_panel_msg_list_grid").setGridWidth($(window).width() - 35) : $("#bottom_panel_msg_list_grid").setGridWidth($(window).width() - 500)
    }) 
    $(window).bind("resize", function () {
        if (document.getElementById("side_panel").style.display == "none") {
            $("#bottom_panel_historytrack_list_grid").setGridWidth($(window).width() - 35)
        } else {
            $("#bottom_panel_historytrack_list_grid").setGridWidth($(window).width() - 500)
        }
    })    
    var menuBox = document.getElementById('cbp-spmenu-s2');
    if(menuBox.style.display == "none"){
    document.getElementById("bottom_panel").style.width = "74%";
     $("#bottom_panel_msg_list_grid").setGridWidth($(window).width() - 365)
      $("#bottom_panel_historytrack_list_grid").setGridWidth($(window).width() - 365)

      $(window).bind("resize", function() {
        "none" == document.getElementById("side_panel").style.display ? $("#bottom_panel_msg_list_grid").setGridWidth($(window).width() - 35) : $("#bottom_panel_msg_list_grid").setGridWidth($(window).width() - 365)
    }) 
    $(window).bind("resize", function () {
        if (document.getElementById("side_panel").style.display == "none") {
            $("#bottom_panel_historytrack_list_grid").setGridWidth($(window).width() - 35)
        } else {
            $("#bottom_panel_historytrack_list_grid").setGridWidth($(window).width() - 365)
        }
    })  
}
     else if(document.getElementById("map").className = "block width75 leaflet-container leaflet-touch leaflet-fade-anim leaflet-grab leaflet-touch-drag leaflet-touch-zoom"){
         document.getElementById("bottom_panel").style.width = "64.9%";
     $("#bottom_panel_msg_list_grid").setGridWidth($(window).width() - 500)
      $("#bottom_panel_historytrack_list_grid").setGridWidth($(window).width() - 500)

      $(window).bind("resize", function() {
        "none" == document.getElementById("side_panel").style.display ? $("#bottom_panel_msg_list_grid").setGridWidth($(window).width() - 35) : $("#bottom_panel_msg_list_grid").setGridWidth($(window).width() - 500)
    }) 
    $(window).bind("resize", function () {
        if (document.getElementById("side_panel").style.display == "none") {
            $("#bottom_panel_historytrack_list_grid").setGridWidth($(window).width() - 35)
        } else {
            $("#bottom_panel_historytrack_list_grid").setGridWidth($(window).width() - 500)
        }
    })  

}


      // document.getElementById("bottom_panel").style.height = "213px !important";      
      document.getElementById("myDropdown").className = "dropdown-content-ping";
      document.getElementById("myDropdown2").className = "dropdown-content-ping";
      document.getElementById("myDropdown3").className = "dropdown-content-ping";
      document.getElementById("myDropdown4").className = "dropdown-content-ping";
      document.getElementById("myDropdown").style.display = "block";
    }else{
        var clas1= document.getElementById("map").className="block width100 leaflet-container leaflet-touch leaflet-fade-anim leaflet-grab leaflet-touch-drag leaflet-touch-zoom";
        map.invalidateSize(!0)
        document.getElementById("ping").className = "pincl";
        document.getElementById("chpinim").src="img/pin.png";
      document.getElementById("bottom_panel").style.width = "91.7%"; 
      $("#bottom_panel_msg_list_grid").setGridWidth($(window).width() - 300)
      $("#bottom_panel_historytrack_list_grid").setGridWidth($(window).width() - 300)

      $(window).bind("resize", function() {
        "none" == document.getElementById("side_panel").style.display ? $("#bottom_panel_msg_list_grid").setGridWidth($(window).width() - 35) : $("#bottom_panel_msg_list_grid").setGridWidth($(window).width() - 200)
    }) 
    $(window).bind("resize", function () {
        if (document.getElementById("side_panel").style.display == "none") {
            $("#bottom_panel_historytrack_list_grid").setGridWidth($(window).width() - 35)
        } else {
            $("#bottom_panel_historytrack_list_grid").setGridWidth($(window).width() - 300)
        }
    }) 
     var menuBox = document.getElementById('cbp-spmenu-s2');
     if(document.getElementById("map").className = "block width100 leaflet-container leaflet-touch leaflet-fade-anim leaflet-grab leaflet-touch-drag leaflet-touch-zoom" && menuBox.style.display == "none"){
         document.getElementById("bottom_panel").style.width = "100%";
     $("#bottom_panel_msg_list_grid").setGridWidth($(window).width() - 370)
      $("#bottom_panel_historytrack_list_grid").setGridWidth($(window).width() - 370)

      $(window).bind("resize", function() {
        "none" == document.getElementById("side_panel").style.display ? $("#bottom_panel_msg_list_grid").setGridWidth($(window).width() - 35) : $("#bottom_panel_msg_list_grid").setGridWidth($(window).width() - 370)
    }) 
    $(window).bind("resize", function () {
        if (document.getElementById("side_panel").style.display == "none") {
            $("#bottom_panel_historytrack_list_grid").setGridWidth($(window).width() - 35)
        } else {
            $("#bottom_panel_historytrack_list_grid").setGridWidth($(window).width() - 370)
        }
    })  

}
        document.getElementById("myDropdown").style.display = "none";
        var dismap1=document.getElementById("myDropdown").style.display;
        var dismap2=document.getElementById("myDropdown2").style.display ;
        var dismap3=document.getElementById("myDropdown3").style.display ;
        var dismap4=document.getElementById("myDropdown4").style.display ;
        document.getElementById("myDropdown").className = "dropdown-content";
        document.getElementById("myDropdown2").className = "dropdown-content";
        document.getElementById("myDropdown3").className = "dropdown-content";
        document.getElementById("myDropdown4").className = "dropdown-content";
        if(dismap2=="block" || dismap3=="block" || dismap4=="block"){
        
        document.getElementById("myDropdown").style.display = "none";

        }else{
            document.getElementById("myDropdown").style.display = "block";
        }
        
    }
}
function myFunction() {
  var zy = document.getElementById("myDropdown");
  
  if (zy.style.display === "block" && document.getElementById("myDropdown").className=="dropdown-content") {
  zy.style.display = "none";
  } 
  else{    
      document.getElementById("myDropdown").style.display = "block";
      document.getElementById("myDropdown2").style.display = "none";
      document.getElementById("myDropdown3").style.display = "none";
      document.getElementById("myDropdown4").style.display = "none";
  }
}
  function myFunction2() {
  var z = document.getElementById("myDropdown2");
  
  if (z.style.display === "block") {
  
  z.style.display = "none";
  } else{
    if(document.getElementById("myDropdown").className=="dropdown-content-ping"){
         document.getElementById("myDropdown").style.display = "block";
    }else{
        document.getElementById("myDropdown").style.display = "none";
    } 
  document.getElementById("myDropdown2").style.display = "block";
  document.getElementById("myDropdown3").style.display = "none";
  document.getElementById("myDropdown4").style.display = "none";
  }
  }
  function myFunction3() {
  var y = document.getElementById("myDropdown3");
  if (y.style.display === "block") {
  
  y.style.display = "none";
  } else{
  if(document.getElementById("myDropdown").className=="dropdown-content-ping"){
         document.getElementById("myDropdown").style.display = "block";
    }else{
        document.getElementById("myDropdown").style.display = "none";
    }
  document.getElementById("myDropdown2").style.display = "none";
  document.getElementById("myDropdown3").style.display = "block";
  document.getElementById("myDropdown4").style.display = "none";
  }
  }
  function myFunction4() {
  var x = document.getElementById("myDropdown4");
  
  if (x.style.display === "block") {
  
  x.style.display = "none";
  
  } else
  {
  if(document.getElementById("myDropdown").className=="dropdown-content-ping"){
         document.getElementById("myDropdown").style.display = "block";
    }else{
        document.getElementById("myDropdown").style.display = "none";
    }
  document.getElementById("myDropdown2").style.display = "none";
  document.getElementById("myDropdown3").style.display = "none";
  document.getElementById("myDropdown4").style.display = "block";
  }
  }

function addNewEmployee(d) {
    switch (d) {
        case "add":
            loadStisaddemployeeSelect();
            $("#dialog_add_new_employee").dialog("open");
            document.getElementById("aaddnewemployee_eid").disabled=false;
            document.getElementById("save_add_Employee").style.display='block';
            document.getElementById("update_add_employee").style.display='none';
            document.getElementById("aaddnewemployee_eid").value='';
            document.getElementById("addnewemployee_department").value='';
            document.getElementById("addnewemployee_shift").value='';
            document.getElementById("addnewemployee_username").value='';
            document.getElementById("addnewemployee_mobile").value='';
            document.getElementById("addnewemployee_dob").value='';
            document.getElementById("addnewemployee_gender").value='';
            document.getElementById("addnewemployee_address").value='';
            document.getElementById("addnewemployee_status").value='';
            document.getElementById("addnewemployee_usertype").value='';
            break;
        case "save":
            var empid=document.getElementById("aaddnewemployee_eid").value,
            department=document.getElementById("addnewemployee_department").value,
            shift=document.getElementById("addnewemployee_shift").value,
            username=document.getElementById("addnewemployee_username").value,
            mobno=document.getElementById("addnewemployee_mobile").value,
            dob=document.getElementById("addnewemployee_dob").value,
            gender=document.getElementById("addnewemployee_gender").value,
            address=document.getElementById("addnewemployee_address").value,
            status=document.getElementById("addnewemployee_status").value,
            usertype=document.getElementById("addnewemployee_usertype").value;
            if (empid=='' || username=='' || mobno=='' || status=='' || usertype=='' || department=='' || shift==''){
                notifyBox("error", la.ERROR, la.PLSFILLALLREQUIREDDETAILS);
                break;
            }else{
                var l = {
                    cmd:"add_new_employee",
                    eid:empid,
                    un:username,
                    de:department,
                    sh:shift,
                    mo:mobno,
                    dob:dob,
                    gen:gender,
                    add:address,
                    st:status,
                    ut:usertype
                };$.ajax({
                    type: "POST",
                    url: "func/fn_addemployee.php",
                    data: l,
                    cache: !1,
                    success: function(e) {
                        if(e=='OK'){
                            $("#stis_main_addemployee_list_grid").trigger("reloadGrid");
                            $("#dialog_add_new_employee").dialog("close");
                            notifyBox("info", la.INFORMATION, la.EMPOYEE_ADD_SUCCESSFULLY);
                        }
                        else if(e=='USERID'){
                             notifyBox("error", la.ERROR, la.EMPOYEE_ID_ALREDY_REGISTERED);
                        }
                        else{
                             notifyBox("error", la.ERROR, e);
                        }
                        // "OK" == e ? ($("#settings_main_addemployee_list_grid").trigger("reloadGrid"), $("#dialog_add_new_employee").dialog("close"), notifyBox("info", la.INFORMATION, la.EMPOYEE_ADD_SUCCESSFULLY)) : notifyBox("error", la.ERROR,e);
                    }
                })
            break;
            }
        case "update":
            var empid=document.getElementById("aaddnewemployee_eid").value,
            username=document.getElementById("addnewemployee_username").value,
            department=document.getElementById("addnewemployee_department").value,
            shift=document.getElementById("addnewemployee_shift").value,
            mobno=document.getElementById("addnewemployee_mobile").value,
            dob=document.getElementById("addnewemployee_dob").value,
            gender=document.getElementById("addnewemployee_gender").value,
            address=document.getElementById("addnewemployee_address").value,
            status=document.getElementById("addnewemployee_status").value,
            usertype=document.getElementById("addnewemployee_usertype").value,
            liveuserid=document.getElementById("aaddnewemployee_liveid").value;
            if (empid=='' || username=='' || mobno=='' || status=='' || usertype=='' || department=='' || shift==''){
                notifyBox("error", la.ERROR, la.PLSFILLALLREQUIREDDETAILS);
                break;
            }else{
            var d = {
                cmd:"update_live_employee",
                eid:empid,
                un:username,
                de:department,
                sh:shift,
                mo:mobno,
                dob:dob,
                gen:gender,
                add:address,
                st:status,
                ut:usertype,
                li_id:liveuserid,
            };$.ajax({
                type: "POST",
                url: "func/fn_addemployee.php",
                data: d,
                cache: !1,
                success: function(e) {
                    "OK" == e ? ($("#stis_main_addemployee_list_grid").trigger("reloadGrid"), $("#dialog_add_new_employee").dialog("close"), notifyBox("info", la.INFORMATION, la.EMPOYEE_UPDATE_SUCCESSFULLY)) : notifyBox("error", la.ERROR,e);
                }
            })
        break;
        }            
    }       
}

function deleteaddEmployee(id){
    void confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE_SELECTED_ITEMS, function(t) {
        if (t) {
            live_id=id
            var a = {
                cmd: "delete_addemployee",
                id: live_id
            };
            $.ajax({
                type: "POST",
                url: "func/fn_addemployee.php",
                data: a,
                success: function(e) {
                    "OK" == e && $("#stis_main_addemployee_list_grid").trigger("reloadGrid");
                }
            })
        }
    })
}

function deleteaddSecurity(id){
    void confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE_SELECTED_ITEMS, function(t) {
        if (t) {
            live_id=id
            var a = {
                cmd: "delete_addemployee",
                id: live_id
            };
            $.ajax({
                type: "POST",
                url: "func/fn_addemployee.php",
                data: a,
                success: function(e) {
                    "OK" == e && $("#stis_main_self_addsecurity_list_grid").trigger("reloadGrid");
                }
            })
        }
    })
}

function editaddEmployee(id){
    loadStisaddemployeeSelect();
    live_id=id
    var a = {
        cmd: "edit_addemployee",
        eid: live_id
    };
    $.ajax({
        type: "POST",
        url: "func/fn_addemployee.php",
        data: a,
        success: function(e) {
            if(e!=''){
                $("#dialog_add_new_security").dialog("open");
                document.getElementById("aaddnewemployee_eid").disabled=true;
                document.getElementById("save_add_Employee").style.display='none';
                document.getElementById("update_add_employee").style.display='block';
                document.getElementById("aaddnewemployee_liveid").value=e['live_user_id'];
                document.getElementById("aaddnewemployee_eid").value=e['empid'];
                document.getElementById("addnewemployee_username").value=e['username'];
                document.getElementById("addnewemployee_department").value=e['department'];
                document.getElementById("addnewemployee_shift").value=e['shift'];
                document.getElementById("addnewemployee_mobile").value=e['mobile'];
                document.getElementById("addnewemployee_dob").value=e['dob'];
                document.getElementById("addnewemployee_gender").value=e['gender'];
                document.getElementById("addnewemployee_address").value=e['address'];
                document.getElementById("addnewemployee_status").value=e['status'];
                document.getElementById("addnewemployee_usertype").value=e['user_type'];
            }
        }
    })
}

function addStisDepartment(d) {
    switch (d) {
        case "save":
            document.getElementById("save_sist_department").style.display='block';
            document.getElementById("update_sist_department").style.display='none';
            var depname=document.getElementById("addsist_departmentname").value;
            if (depname==''){
                notifyBox("error", la.ERROR, la.PLSFILLALLREQUIREDDETAILS);
                break;
            }else{
                var l = {
                    cmd:"add_stis_department",
                    dname:depname
                };$.ajax({
                    type: "POST",
                    url: "func/fn_addemployee.php",
                    data: l,
                    cache: !1,
                    success: function(e) {
                        if(e=='OK'){
                            $("#stis_main_department_list_grid").trigger("reloadGrid");
                            document.getElementById("addsist_departmentname").value='';
                            document.getElementById("save_sist_department").style.display='block';
                            document.getElementById("update_sist_department").style.display='none';
                            notifyBox("info", la.INFORMATION, la.DEPARTMENT_ADD_SUCCESSFULLY);
                        }
                        else{
                             notifyBox("error", la.ERROR, e);
                        }
                        // "OK" == e ? ($("#settings_main_addemployee_list_grid").trigger("reloadGrid"), $("#dialog_add_new_employee").dialog("close"), notifyBox("info", la.INFORMATION, la.EMPOYEE_ADD_SUCCESSFULLY)) : notifyBox("error", la.ERROR,e);
                    }
                })
            break;
            }
        case "update":
            var depid=document.getElementById("addsist_departmentid").value,
            depname=document.getElementById("addsist_departmentname").value;
            if (depname==''){
                notifyBox("error", la.ERROR, la.PLSFILLALLREQUIREDDETAILS);
                break;
            }else{
            var d = {
                cmd:"update_stis_department",
                did:depid,
                dn:depname,
            };$.ajax({
                type: "POST",
                url: "func/fn_addemployee.php",
                data: d,
                cache: !1,
                success: function(e) {
                    "OK" == e ? ($("#stis_main_department_list_grid").trigger("reloadGrid"),
                    document.getElementById("addsist_departmentname").value='',
                    document.getElementById("save_sist_department").style.display='block',
                    document.getElementById("update_sist_department").style.display='none',
                    notifyBox("info", la.INFORMATION, la.DEPARTMENT_UPDATE_SUCCESSFULLY)) : notifyBox("error", la.ERROR,e);
                }
            })
        break;
        }            
    }  
}

function editSistDepartment(id){
    eid=id;
    var a={
        cmd:"edit_sits_department",
        edid:eid
    };
    $.ajax({
        type:"POST",
        url:"func/fn_addemployee.php",
        data:a,
        success: function(e){
            document.getElementById("save_sist_department").style.display='none';
            document.getElementById("update_sist_department").style.display='block';
            document.getElementById("addsist_departmentname").value=e['dept_name'];
            document.getElementById("addsist_departmentid").value=e['dept_id'];
        }
    })
}

function deleteSistDepartment(id){
    void confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE_SELECTED_ITEMS, function(t) {
        if (t) {
        eid=id;
        var a={
            cmd:"delete_sits_department",
            edid:eid
        };
        $.ajax({
            type:"POST",
            url:"func/fn_addemployee.php",
            data:a,
            success: function(e){
                "OK" == e ? ($("#stis_main_department_list_grid").trigger("reloadGrid")) : notifyBox("error", la.ERROR,e) ;
            }
        })
    }})
}

function addStisShift(d) {
    switch (d) {
        case "add":
            $("#dialog_stis_add_shift").dialog("open");
            document.getElementById("save_stis_shift").style.display='block';
            document.getElementById("update_stis_shift").style.display='none';
            document.getElementById("stis_shift_name").value='';
            document.getElementById("stis_shift_from").value='';
            document.getElementById("stis_shift_to").value='';
            document.getElementById("stis_shift_status").value='';
            break;
        case "save":
            var sh_name=document.getElementById("stis_shift_name").value,
            sh_from=document.getElementById("stis_shift_from").value,
            sh_to=document.getElementById("stis_shift_to").value,
            sh_status=document.getElementById("stis_shift_status").value;
            if (sh_name=='' || sh_from=='' || sh_to=='' || sh_status==''){
                notifyBox("error", la.ERROR, la.PLSFILLALLREQUIREDDETAILS);
                break;
            }else{
                var l = {
                    cmd:"add_stif_shift",
                    sna:sh_name,
                    sfr:sh_from,
                    sto:sh_to,
                    st:sh_status
                };$.ajax({
                    type: "POST",
                    url: "func/fn_addemployee.php",
                    data: l,
                    cache: !1,
                    success: function(e) {
                        if(e=='OK'){
                            $("#stis_main_shift_list_grid").trigger("reloadGrid");
                            $("#dialog_stis_add_shift").dialog("close");
                            notifyBox("info", la.INFORMATION, la.SHIFT_ADD_SUCCESSFULLY);
                        }
                        else{
                             notifyBox("error", la.ERROR, e);
                        }
                        // "OK" == e ? ($("#settings_main_addemployee_list_grid").trigger("reloadGrid"), $("#dialog_add_new_employee").dialog("close"), notifyBox("info", la.INFORMATION, la.EMPOYEE_ADD_SUCCESSFULLY)) : notifyBox("error", la.ERROR,e);
                    }
                })
            break;
            }
        case "update":
            var sh_name=document.getElementById("stis_shift_name").value,
            sh_id=document.getElementById("stis_shift_id").value,
            sh_from=document.getElementById("stis_shift_from").value,
            sh_to=document.getElementById("stis_shift_to").value,
            sh_status=document.getElementById("stis_shift_status").value;
            if (sh_name=='' || sh_from=='' || sh_to=='' || sh_status==''){
                notifyBox("error", la.ERROR, la.PLSFILLALLREQUIREDDETAILS);
                break;
            }else{
            var d = {
                cmd:"update_stif_shift",
                sna:sh_name,
                sid:sh_id,
                sfr:sh_from,
                sto:sh_to,
                st:sh_status
            };$.ajax({
                type: "POST",
                url: "func/fn_addemployee.php",
                data: d,
                cache: !1,
                success: function(e) {
                    "OK" == e ? ($("#stis_main_shift_list_grid").trigger("reloadGrid"), $("#dialog_stis_add_shift").dialog("close"), notifyBox("info", la.INFORMATION, la.SHIFT_UPDATE_SUCCESSFULLY)) : notifyBox("error", la.ERROR,e);
                }
            })
        break;
        }            
    } 
}

function editSistShift(id){
    sid=id;
    var a={
        cmd:"edit_sits_shift",
        sdid:sid
    };
    $.ajax({
        type:"POST",
        url:"func/fn_addemployee.php",
        data:a,
        success: function(e){
             $("#dialog_stis_add_shift").dialog("open");
            document.getElementById("save_stis_shift").style.display='none';
            document.getElementById("update_stis_shift").style.display='block';
            document.getElementById("stis_shift_name").value=e['name'];
            document.getElementById("stis_shift_id").value=e['sid'];
            document.getElementById("stis_shift_from").value=e['from'];
            document.getElementById("stis_shift_to").value=e['to'];
            document.getElementById("stis_shift_status").value=e['status'];
        }
    })
}

function deleteSistShift(id){
    void confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE_SELECTED_ITEMS, function(t) {
        if (t) {
        var a={
            cmd:'delete_stis_shift',
            sid:id
        };
        $.ajax({
            type:"POST",
            url:"func/fn_addemployee.php",
            data:a,
            success:function(e){
                "OK" == e ? ($("#stis_main_shift_list_grid").trigger("reloadGrid")) : notifyBox("error", la.ERROR,e) ;
            }
        })
    }})
}

function loadStisaddemployeeSelect(){
    var d = {
            cmd:"load_stis_data"
        };
    $.ajax({
        type:"POST",
        url:"func/fn_addemployee.php",
        data:d,
        success:function(e){
            var select_dep = document.getElementById('addnewemployee_department');
            select_dep.options.length = 0; // clear out existing items
            select_dep.options.add(new Option(la['SELECT'], ''));
            for (var key in e['department'])
            {
                select_dep.options.add(new Option(e['department'][key].dept_name, e['department'][key].dept_id));
            }
            var select_sft = document.getElementById('addnewemployee_shift');
            select_sft.options.length = 0; // clear out existing items
            select_sft.options.add(new Option(la['SELECT'], ''));
            for (var key in e['shift'])
            {
                select_sft.options.add(new Option(e['shift'][key].name, e['shift'][key].sid));
            }
        }
    })
}

function stisLoadclienttype()
{
   $.ajax({
       async: false,
       type: "POST",
       url: "func/fn_addemployee.php",
       data:  {"cmd":"select_client_type"},
       dataType: "json",
       success: function (respns) { 
           var mul_user=respns;       
           var av=document.getElementById("cabrequest_clientid");
           av.options.length = 0,av.options.add(new Option("Select",""));
           for (var iv=0;iv<respns.client.length;iv++) {
            av.options.add(new Option(respns.client[iv].username, respns.client[iv].id))
           }
       },
       failure: function (response) {
           
       }
   });
}

function stisLoadmultyuser()
{
   $.ajax({
       async: false,
       type: "POST",
       url: "func/fn_addemployee.php",
       data:  {"cmd":"select_liveuser_type"},
       dataType: "json",
       success: function (respns) {           
            var av= document.getElementById('cabrequest_userid');
            av.options.length = 0,av.options.add(new Option("Select",""));
            for (var iv=0;iv<respns.liveuser.length;iv++) {
            av.options.add(new Option(respns.liveuser[iv].live_username, respns.liveuser[iv].live_user_id))
             
           }         
       },
       failure: function (response) {
           
       }
   });
}


function addCabRequest(a){
    switch(a){
        case 'add':
            $("#dialog_stis_newcab_request").dialog("open");
            stisLoadclienttype();
            stisLoadmultyuser();
            // $('#stis_main_cabrequest_adduser_list_grid').jqGrid('clearGridData');             
            // $("#stis_main_cabrequest_adduser_list_grid").jqGrid('addRowData', 'selIds[i]','custom[selIds[i]]');
            $('#stis_main_cabrequest_adduser_list_grid').jqGrid('clearGridData');
            document.getElementById('save_add_cabrequest').style.display='block';
            document.getElementById('update_add_cabrequest').style.display='none';
            document.getElementById('cabrequest_clientid').value='';
            document.getElementById('cabrequest_cabtype').value='';
            document.getElementById('cabrequest_schedule_date').value='';
            document.getElementById('cabrequest_from').value='';
            document.getElementById('cabrequest_to').value='';
            document.getElementById('cabrequest_security').value='';
            document.getElementById('cabrequest_totaluser').value='';
            document.getElementById('cabrequest_status').value='';
            document.getElementById('cabrequest_triptype').value='';
        break;
        case "save":
            var $grid = $("#stis_main_cabrequest_adduser_list_grid"), selIds = $grid.jqGrid("getDataIDs");
            if("" == selIds)
            {
              notifyBox("error", la.ERROR, la.NO_ITEMS_SELECTED) ;
              return;
            }
            var custom = $grid.jqGrid('getRowData');
            for (i = 0; i < selIds.length; i++) 
            {
                if(i==0){
                    liveuser=custom[i]['id'];
                }else{
                    liveuser=liveuser+','+custom[i]['id'];
                }
            }
            creq_cli=document.getElementById('cabrequest_clientid').value;
            creq_ctype=document.getElementById('cabrequest_cabtype').value;
            creq_sch = $("#cabrequest_schedule_date").val() + " " + $("#cabrequest_schedule_hours").val() + ":" + $("#cabrequest_schedule_sce").val() + ":00",
            creq_fr=document.getElementById('cabrequest_from').value;
            creq_to=document.getElementById('cabrequest_to').value;
            creq_sec=document.getElementById('cabrequest_security').value;
            creq_tus=selIds.length;
            creq_st=document.getElementById('cabrequest_status').value;
            creq_tri=document.getElementById('cabrequest_triptype').value;
            if (creq_cli=='' || creq_sch=='' || creq_sch==' 00:00:00' || creq_fr==''|| creq_to=='' || creq_sec=='' || creq_st=='' || creq_tri==''){
                notifyBox("error", la.ERROR, la.PLSFILLALLREQUIREDDETAILS);
                break;
            }else{
                var d = {
                    cmd:"save_stis_newcab_request",
                    cid:creq_cli,
                    cty:creq_ctype,
                    csc:creq_sch,
                    cfr:creq_fr,    
                    cto:creq_to,
                    cse:creq_sec,
                    ctu:creq_tus,
                    cst:creq_st,
                    ctr:creq_tri,
                    liveuser:liveuser
                };
                $.ajax({
                    type:"POST",
                    url:"func/fn_addemployee.php",
                    data:d,
                    success:function(e){
                       if(e=='OK'){
                            $("#stis_main_cabrequest_list_grid").trigger("reloadGrid");
                            $("#dialog_stis_newcab_request").dialog("close");
                            notifyBox("info", la.INFORMATION, la.CABREQUEST_ADD_SUCCESSFULLY);
                        }
                        else{
                             notifyBox("error", la.ERROR, e);
                        }

                    }
                })
            }
        break;
        case "update":
            var $grid = $("#stis_main_cabrequest_adduser_list_grid"), selIds = $grid.jqGrid("getDataIDs");
            if("" == selIds)
            {
              notifyBox("error", la.ERROR, la.NO_ITEMS_SELECTED) ;
              return;
            }
            var custom = $grid.jqGrid('getRowData');
            for (i = 0; i < selIds.length; i++) 
            {
                if(i==0){
                    liveuser=custom[i]['id'];
                }else{
                    liveuser=liveuser+','+custom[i]['id'];
                }
            }
            creq_trid=document.getElementById('cabrequest_tripid').value;
            creq_cli=document.getElementById('cabrequest_clientid').value;
            creq_ctype=document.getElementById('cabrequest_cabtype').value;
            creq_sch = $("#cabrequest_schedule_date").val() + " " + $("#cabrequest_schedule_hours").val() + ":" + $("#cabrequest_schedule_sce").val() + ":00",
            creq_fr=document.getElementById('cabrequest_from').value;
            creq_to=document.getElementById('cabrequest_to').value;
            creq_sec=document.getElementById('cabrequest_security').value;
            creq_tus=selIds.length;
            creq_st=document.getElementById('cabrequest_status').value;
            creq_tri=document.getElementById('cabrequest_triptype').value;
            if (creq_cli=='' || creq_sch=='' || creq_sch==' 00:00:00' || creq_fr==''|| creq_to=='' || creq_sec=='' || creq_st=='' || creq_tri==''){
                notifyBox("error", la.ERROR, la.PLSFILLALLREQUIREDDETAILS);
                break;
            }else{
                var d = {
                    cmd:"update_stis_cabrequest",
                    ctid:creq_trid,
                    cid:creq_cli,
                    cty:creq_ctype,
                    csc:creq_sch,
                    cfr:creq_fr,    
                    cto:creq_to,
                    cse:creq_sec,
                    ctu:creq_tus,
                    cst:creq_st,
                    ctr:creq_tri,
                    liveuser:liveuser
                };
                $.ajax({
                    type:"POST",
                    url:"func/fn_addemployee.php",
                    data:d,
                    success:function(e){
                       if(e=='OK'){
                            $("#stis_main_cabrequest_list_grid").trigger("reloadGrid");
                            $("#dialog_stis_newcab_request").dialog("close");
                            notifyBox("info", la.INFORMATION, la.UPDATE_CABREQUEST_SUCCESSFULLY);
                        }
                        else{
                             notifyBox("error", la.ERROR, e);
                        }

                    }
                })
            }
        break;
    }
}

function addCabSelfRequest(v){
    switch(v){
        case 'add':
            $("#dialog_add_Self_cab_booking").dialog("open");
            loadCabrequestAllocateDriverList('cabrequest_self_setdriver_userid');
            document.getElementById('save_self_add_cabrequest').style.display='block';
            document.getElementById('update_self_add_cabrequest').style.display='none';
            document.getElementById('self_cabrequest_client_name').value='';
            document.getElementById('self_cabrequest_client_mobile').value='';
            document.getElementById('self_cabrequest_tripid').value='';
            document.getElementById('self_cabrequest_schedule_date').value='';
            document.getElementById('self_cabrequest_schedule_hours').value='';
            document.getElementById('self_cabrequest_schedule_sce').value='';
            document.getElementById('self_cabrequest_from').value='';
            document.getElementById('self_cabrequest_to').value='';
            document.getElementById('self_cabrequest_security').value='';
            document.getElementById('self_cabrequest_triptype').value='';
            document.getElementById('cabrequest_self_setdriver_userid').value='';
        break;
        case 'save':
            s_creq_cli=document.getElementById('self_cabrequest_client_name').value;
            s_creq_mob=document.getElementById('self_cabrequest_client_mobile').value;
            s_creq_sch = $("#self_cabrequest_schedule_date").val() + " " + $("#self_cabrequest_schedule_hours").val() + ":" + $("#self_cabrequest_schedule_sce").val() + ":00",
            s_creq_fr=document.getElementById('self_cabrequest_from').value;
            s_creq_fr_lat=document.getElementById('self_cabrequest_from_latitude').value;
            s_creq_fr_lon=document.getElementById('self_cabrequest_from_longitude').value;
            s_creq_to=document.getElementById('self_cabrequest_to').value;
            s_creq_to_lat=document.getElementById('self_cabrequest_to_latitude').value;
            s_creq_to_lon=document.getElementById('self_cabrequest_to_longitude').value;
            s_creq_sec=document.getElementById('self_cabrequest_security').value;
            s_creq_secname=document.getElementById('self_cabrequest_security_list').value;
            s_creq_dri=document.getElementById('cabrequest_self_setdriver_userid').value;
            s_creq_tri=document.getElementById('self_cabrequest_triptype').value;
            if (s_creq_sec=='Y' && s_creq_secname==''){
                notifyBox("error", la.ERROR, 'Select Driver');
                break;
            }
            if (s_creq_cli=='' || s_creq_sch=='' || s_creq_sch==' 00:00:00' || s_creq_fr==''|| s_creq_to=='' || s_creq_sec=='' ||s_creq_dri=='' || s_creq_tri==''){
                notifyBox("error", la.ERROR, la.PLSFILLALLREQUIREDDETAILS);
                break;
            }else{
                var d = {
                    cmd:"save_stis_self_newcab_request",
                    cna:s_creq_cli,
                    cmo:s_creq_mob,
                    csc:s_creq_sch,
                    cfr:s_creq_fr,    
                    cfrlat:s_creq_fr_lat,    
                    cfrlon:s_creq_fr_lon,    
                    cto:s_creq_to,
                    ctolat:s_creq_to_lat,
                    ctolon:s_creq_to_lon,
                    cse:s_creq_sec,
                    csena:s_creq_secname,
                    ctr:s_creq_tri,
                    cdr:s_creq_dri
                };
                $.ajax({
                    type:"POST",
                    url:"func/fn_addemployee.php",
                    data:d,
                    success:function(e){
                       if(e=='OK'){
                            $("#stis_main_self_cabrequest_vendor_list_grid").trigger("reloadGrid");
                            $("#dialog_add_Self_cab_booking").dialog("close");
                            notifyBox("info", la.INFORMATION, la.SEND_CABREQUEST_SUCCESSFULLY);
                        }
                        else{
                             notifyBox("error", la.ERROR, e);
                        }

                    }
                })
            }
        break;
        case 'update':
            s_creq_cli=document.getElementById('self_cabrequest_client_name').value;
            s_creq_id=document.getElementById('self_cabrequest_tripid').value;
            s_creq_mob=document.getElementById('self_cabrequest_client_mobile').value;
            s_creq_sch = $("#self_cabrequest_schedule_date").val() + " " + $("#self_cabrequest_schedule_hours").val() + ":" + $("#self_cabrequest_schedule_sce").val() + ":00",
            s_creq_fr=document.getElementById('self_cabrequest_from').value;
            s_creq_fr_lat=document.getElementById('self_cabrequest_from_latitude').value;
            s_creq_fr_lon=document.getElementById('self_cabrequest_from_longitude').value;
            s_creq_to=document.getElementById('self_cabrequest_to').value;
            s_creq_to_lat=document.getElementById('self_cabrequest_to_latitude').value;
            s_creq_to_lon=document.getElementById('self_cabrequest_to_longitude').value;
            s_creq_sec=document.getElementById('self_cabrequest_security').value;
            s_creq_secname=document.getElementById('self_cabrequest_security_list').value;
            s_creq_dri=document.getElementById('cabrequest_self_setdriver_userid').value;
            s_creq_tri=document.getElementById('self_cabrequest_triptype').value;
            if (s_creq_sec=='Y' && s_creq_secname==''){
                notifyBox("error", la.ERROR, 'Select Driver');
                break;
            }
            if (s_creq_cli=='' || s_creq_sch=='' || s_creq_sch==' 00:00:00' || s_creq_fr==''|| s_creq_to=='' || s_creq_sec=='' ||s_creq_dri=='' || s_creq_tri==''){
                notifyBox("error", la.ERROR, la.PLSFILLALLREQUIREDDETAILS);
                break;
            }else{
                var d = {
                    cmd:"update_stis_self_newcab_request",
                    cna:s_creq_cli,
                    cid:s_creq_id,
                    cmo:s_creq_mob,
                    csc:s_creq_sch,
                    cfr:s_creq_fr,    
                    cfrlat:s_creq_fr_lat,    
                    cfrlon:s_creq_fr_lon,    
                    cto:s_creq_to,
                    ctolat:s_creq_to_lat,
                    ctolon:s_creq_to_lon,
                    cse:s_creq_sec,
                    csena:s_creq_secname,
                    ctr:s_creq_tri,
                    cdr:s_creq_dri
                };
                $.ajax({
                    type:"POST",
                    url:"func/fn_addemployee.php",
                    data:d,
                    success:function(e){
                       if(e=='OK'){
                            $("#stis_main_self_cabrequest_vendor_list_grid").trigger("reloadGrid");
                            $("#dialog_add_Self_cab_booking").dialog("close");
                            notifyBox("info", la.INFORMATION, la.SEND_CABREQUEST_SUCCESSFULLY);
                        }
                        else{
                             notifyBox("error", la.ERROR, e);
                        }

                    }
                })
            }
        break;
    }
}

function sistSelfrequestView(id){    
    var l={
        cmd:"self_cabbooking_status",
        id:id
    };
    $.ajax({
        type:"POST",
        url:"func/fn_addemployee.php",
        data:l,
        success:function(e){
            if (e.security_allocated=='Y'){
                var sec='YES';
            }else{
                var sec='NO';
            }

            if (e.trip_type=='D'){
                var trip_type='Drop';
            }else{
                var trip_type='Pick up';
            }

            x = document.getElementById("show_self_booking_fullstatus");  // Find the element
            x.innerHTML='';
            data="<div class='width100'>";
            data+="<div class='width50' style='font-family: auto;font-weight: bold;padding-top: 2%;padding-bottom: 2%;'>Booking Status</div><div class='width50'>"+e.status+"</div>";
            data+="<br><div class='width50' style='font-family: auto;font-weight: bold;padding-top: 2%;padding-bottom: 2%;'>Client Name</div><div class='width50'>"+e.username+"</div>";
            data+="<div class='width50' style='font-family: auto;font-weight: bold;padding-top: 2%;padding-bottom: 2%;'>Contact Number</div><div class='width50'>"+e.mobile+"</div>";
            data+="<div class='width50' style='font-family: auto;font-weight: bold;padding-top: 2%;padding-bottom: 2%;'>Sedule at</div><div class='width50'>"+e.schedule_at+"</div>";
            data+="<div class='width50' style='font-family: auto;font-weight: bold;padding-top: 2%;padding-bottom: 2%;'>From</div><div class='width50'>"+e.from_loc+"</div>";
            data+="<div class='width50' style='font-family: auto;font-weight: bold;padding-top: 2%;padding-bottom: 2%;'>To</div><div class='width50'>"+e.to_loc+"</div>";
            data+="<div class='width50' style='font-family: auto;font-weight: bold;padding-top: 2%;padding-bottom: 2%;'>Trip Type</div><div class='width50'>"+trip_type+"</div>";
            data+="<div class='width50' style='font-family: auto;font-weight: bold;padding-top: 2%;padding-bottom: 2%;'>Securit</div><div class='width50'>"+sec+"</div>";
            if(sec=='YES'){
                data+="<div class='width50' style='font-family: auto;font-weight: bold;padding-top: 2%;padding-bottom: 2%;'>Security</div><div class='width50'>"+e.usertype_name+"</div>";
                data+="<div class='width50' style='font-family: auto;font-weight: bold;padding-top: 2%;padding-bottom: 2%;'>Security Number</div><div class='width50'>"+e.usertype_mobile+"</div>";
            }
            data+="<div class='width50' style='font-family: auto;font-weight: bold;padding-top: 2%;padding-bottom: 2%;'>Driver</div><div class='width50'>"+e.drivername+"</div>";
            data+="<div class='width50' style='font-family: auto;font-weight: bold;padding-top: 2%;padding-bottom: 2%;'>Driver Number</div><div class='width50'>"+e.drivermobile+"</div>";
            data+="<div class='width50' style='font-family: auto;font-weight: bold;padding-top: 2%;padding-bottom: 2%;'>PickUp Time</div><div class='width50'>"+e.all_picked+"</div>";
            data+="<div class='width50' style='font-family: auto;font-weight: bold;padding-top: 2%;padding-bottom: 2%;'>Drop Time</div><div class='width50'>"+e.all_droped+"</div>";
            data+="</table></div>";
            x.innerHTML=data;
            $("#Self_cabBooking_Status").dialog("open");
        }
    })

}

function changeSelfcabBookingGrid(f){
    $("#stis_main_self_cabrequest_vendor_list_grid").jqGrid("clearGridData");
    $("#stis_main_self_cabrequest_vendor_list_grid").setGridParam({url: "func/fn_addemployee.php?cmd=load_stis_self_cabrequest_vendor&tripstatus="+f});
    $("#stis_main_self_cabrequest_vendor_list_grid").trigger("reloadGrid");
}

function sistSelfrequestEdit(id){
    var l={
        cmd:"edit_self_request",
        id:id
    };
    $.ajax({
        type:"POST",
        url:"func/fn_addemployee.php",
        data:l,
        success:function(e){
            $("#dialog_add_Self_cab_booking").dialog("open");
            loadCabrequestAllocateDriverList('cabrequest_self_setdriver_userid');
            document.getElementById('save_self_add_cabrequest').style.display='none';
            document.getElementById('update_self_add_cabrequest').style.display='block';
            var splitarray = e.schedule_at.split(" ");
            var splittime=splitarray[1].split(":");
            document.getElementById('self_cabrequest_client_name').value=e.username;
            document.getElementById('self_cabrequest_tripid').value=e.tripm_id;
            document.getElementById('self_cabrequest_client_mobile').value=e.mobile;
            document.getElementById('self_cabrequest_schedule_date').value=splitarray[0];
            document.getElementById('self_cabrequest_schedule_hours').value=splittime[0];
            document.getElementById('self_cabrequest_schedule_sce').value=splittime[1];
            document.getElementById('self_cabrequest_from').value=e.from_loc;
            document.getElementById('self_cabrequest_from_latitude').value=e.from_lat;
            document.getElementById('self_cabrequest_from_longitude').value=e.from_lng;
            document.getElementById('self_cabrequest_to').value=e.to_loc;
            document.getElementById('self_cabrequest_to_latitude').value=e.to_lat;
            document.getElementById('self_cabrequest_to_longitude').value=e.to_lng;
            document.getElementById('self_cabrequest_security').value=e.security_allocated;
            document.getElementById('self_cabrequest_triptype').value=e.trip_type;
            document.getElementById('cabrequest_self_setdriver_userid').value=e.driverid;
            

        }
    })
}

function sistSelfrequestDelete(){
    var l={
        cmd:"delete_self_request",
        id:id
    };
    $.ajax({
        type:"POST",
        url:"func/fn_addemployee.php",
        data:l,
        success:function(e){
            alert(e);
        }
    })
}

function editsistCabRequest(id){
    stisLoadclienttype();
    stisLoadmultyuser();
    var l={
        cmd:"edit_stis_cabrequest",
        id:id
    };
    $.ajax({
        type:"POST",
        url:"func/fn_addemployee.php",
        data:l,
        success:function(e){
            stisLoadmultyuser();
            $("#dialog_stis_newcab_request").dialog("open");
            $('#stis_main_cabrequest_adduser_list_grid').jqGrid('clearGridData');
            var splitarray = e.tripmanual['schedule_at'].split(" ");
            var splittime=splitarray[1].split(":");
            for (i=0;i<e.tripmanualsub.length;i++){
                $("#cabrequest_userid option[value='"+e.tripmanualsub[i]['live_user_id']+"']").hide();
                var myData = [{"id":e.tripmanualsub[i]['live_user_id'],"username":e.tripmanualsub[i]['username'], "mobile":e.tripmanualsub[i]['mobile']}];
                $("#stis_main_cabrequest_adduser_list_grid").jqGrid('addRowData', myData[0].id, myData[0]);
            }               
            $("#stis_main_cabrequest_adduser_list_grid").trigger('reloadGrid');
            document.getElementById('save_add_cabrequest').style.display='none';
            document.getElementById('update_add_cabrequest').style.display='block';
            document.getElementById('cabrequest_tripid').value=e.tripmanual['tripm_id'];
            document.getElementById('cabrequest_clientid').value=e.tripmanual['client_id'];
            document.getElementById('cabrequest_cabtype').value=e.tripmanual['cab_type_id'];
            document.getElementById('cabrequest_schedule_date').value=splitarray[0];
            document.getElementById('cabrequest_schedule_hours').value=splittime[0];
            document.getElementById('cabrequest_schedule_sce').value=splittime[1];
            document.getElementById('cabrequest_from').value=e.tripmanual['from_loc'];
            document.getElementById('cabrequest_to').value=e.tripmanual['to_loc'];
            document.getElementById('cabrequest_security').value=e.tripmanual['security_allocated'];
            document.getElementById('cabrequest_totaluser').value=e.tripmanual['security_allocated'];
            document.getElementById('cabrequest_status').value=e.tripmanual['status'];
            document.getElementById('cabrequest_triptype').value=e.tripmanual['trip_type'];
        }
    })
}

function deletesistCabRequest(i){    
    void confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE_SELECTED_ITEMS, function(t) {
        if (t) {
            var l={
                cmd:"delete_stis_cabrequest",
                id:i
            };
            $.ajax({
                type:"POST",
                url:"func/fn_addemployee.php",
                data:l,
                success:function(e){
                    "OK" == e ? ($("#stis_main_cabrequest_list_grid").trigger("reloadGrid"),$("#stis_main_self_cabrequest_vendor_list_grid").trigger("reloadGrid")) : notifyBox("error", la.ERROR,e) ;
                }
            })
        }
    })
}

$(document).ready(function() {
    $('#cabrequest_clientid_search').keyup(function()
    {
        var searchArea = $('#cabrequest_clientid');
        searchFirstList($(this).val(), searchArea);
    });

    $('#cabrequest_userid_search').keyup(function()
    {
        var searchArea = $('#cabrequest_userid');
        searchFirstList($(this).val(), searchArea);
    });
});

function loadJqgriduser(){
    jq_userid=document.getElementById('cabrequest_userid').value;
    if (jq_userid!=''){
        var l={
            cmd:"add_jqgrid_liveuser_date",
            id:jq_userid
        };
        $.ajax({
            type:"POST",
            url:"func/fn_addemployee.php",
            data:l,
            success:function(e){
                $("#cabrequest_userid option[value='"+e.live_user_id+"']").hide();
                document.getElementById('cabrequest_userid').value='';
                var myData = [{"id":e.live_user_id,"username":e.username, "mobile":e.mobile}];
                $("#stis_main_cabrequest_adduser_list_grid").jqGrid('addRowData', myData[0].id, myData[0]);                
                $("#stis_main_cabrequest_adduser_list_grid").trigger('reloadGrid');                
            }
        })
    }else{
        notifyBox("error", la.ERROR, la.PLSFILLALLREQUIREDDETAILS);
    }
}

function removeCabRequestUser(cellValue, options, rowobject, action){
    return "<input type='button' value='Remove' onclick='remove_adduser("+options.rowId+");' rows='5' cols='20' value=''>";
}

function remove_adduser(id){
    $("#cabrequest_userid option[value='"+id+"']").show();
    $("#stis_main_cabrequest_adduser_list_grid").delRowData(id);
}
function viewSistCabRequestUser(id){
    viewSistCabAllocateDriver(id);
    var l={
        cmd:"edit_stis_cabrequest",
        id:id
    };
    $.ajax({
        type:"POST",
        url:"func/fn_addemployee.php",
        data:l,
        success:function(e){
            $("#dialog_stis_newcab_viewuser_request").dialog("open");
            $('#stis_main_cabrequest_viewuser_list_grid').jqGrid('clearGridData');
            var splitarray = e.tripmanual['schedule_at'].split(" ");
            var splittime=splitarray[1].split(":");
            for (i=0;i<e.tripmanualsub.length;i++){
                $("#cabrequest_userid option[value='"+e.tripmanualsub[i]['live_user_id']+"']").hide();
                var myData = [{"id":e.tripmanualsub[i]['live_user_id'],"username":e.tripmanualsub[i]['username'], "mobile":e.tripmanualsub[i]['mobile']}];
                $("#stis_main_cabrequest_viewuser_list_grid").jqGrid('addRowData', myData[0].id, myData[0]);
            }               
            $("#stis_main_cabrequest_viewuser_list_grid").trigger('reloadGrid');
        }
    })
}

function sistVendorAddDriver(id){
    loadCabrequestAllocateDriverList('cabrequest_setdriver_userid');
    document.getElementById('cabrequest_setdriver_tripid').value='';
    document.getElementById('cabrequest_setdriver_tripid').value=id;
    $("#dialog_stis_cabrequest_setdriver").dialog("open");
    $('#dialog_stis_cabrequest_setdriver_list_grid').jqGrid('clearGridData');
    var l={
        cmd:"load_vendor_allocatedriver_data",
        id:id
    };
    $.ajax({
        type:"POST",
        url:"func/fn_addemployee.php",
        data:l,
        success:function(e){
            for (i=0;i<e.length;i++){
                $("#cabrequest_setdriver_userid option[value='"+e[i]['id']+"']").hide();
                var myData = [{"id":e[i]['id'],"username":e[i]['username'], "mobile":e[i]['subuser_phone']}];
                $("#dialog_stis_cabrequest_setdriver_list_grid").jqGrid('addRowData', myData[0].id, myData[0]);
            }
            $("#dialog_stis_cabrequest_setdriver_list_grid").trigger('reloadGrid');
        }
    });
}

function loadCabrequestAllocateDriverList(id)
{
   $.ajax({
       async: false,
       type: "POST",
       url: "func/fn_addemployee.php",
       data:  {"cmd":"load_vendor_allocate_driver"},
       dataType: "json",
       success: function (respns) {           
            var av= document.getElementById(id);
            av.options.length = 0,av.options.add(new Option("Select",""));
            for (var iv=0;iv<respns.length;iv++) {
            av.options.add(new Option(respns[iv].username, respns[iv].id))
             
           }         
       },
       failure: function (response) {
           notifyBox("error", la.ERROR, 'Error');
       }
   });
}

function addTripDeriver(){
    jq_userid=document.getElementById('cabrequest_setdriver_userid').value;
    if (jq_userid!=''){
        var l={
            cmd:"add_vendor_allocatedriver_data",
            id:jq_userid
        };
        $.ajax({
            type:"POST",
            url:"func/fn_addemployee.php",
            data:l,
            success:function(e){
                $("#cabrequest_setdriver_userid option[value='"+e[0].id+"']").hide();
                document.getElementById('cabrequest_setdriver_userid').value='';
                var myData = [{"id":e[0].id,"username":e[0].username, "mobile":e[0].subuser_phone}];
                $("#dialog_stis_cabrequest_setdriver_list_grid").jqGrid('addRowData', myData[0].id, myData[0]);                
                $("#dialog_stis_cabrequest_setdriver_list_grid").trigger('reloadGrid'); 
            }
        })
    }else{
        notifyBox("error", la.ERROR, la.PLSFILLALLREQUIREDDETAILS);
    }
}

function removeCabRequestallocatedriver(cellValue, options, rowobject, action){
    return "<input type='button' value='Remove' onclick='remove_allocatedriver("+options.rowId+");' rows='5' cols='20' value=''>";
}

function remove_allocatedriver(id){
    $("#cabrequest_setdriver_userid option[value='"+id+"']").show();
    $("#dialog_stis_cabrequest_setdriver_list_grid").delRowData(id);
}

function saveVendorAllocateDriver(e){
    var tripid=document.getElementById('cabrequest_setdriver_tripid').value;
    switch(e){
        case "accept":
            var $grid = $("#dialog_stis_cabrequest_setdriver_list_grid"), selIds = $grid.jqGrid("getDataIDs");
            if("" == selIds)
            {
              notifyBox("error", la.ERROR, la.NO_ITEMS_SELECTED) ;
              return;
            }
            var custom = $grid.jqGrid('getRowData');
            for (i = 0; i < selIds.length; i++) 
            {
                if(i==0){
                    driverid=custom[i]['id'];
                }else{
                    driverid=driverid+','+custom[i]['id'];
                }
            }
            var l={
                cmd:"save_trip_allocate_driver",
                tid:tripid,
                driverid:driverid
            };
            $.ajax({
                type:"POST",
                url:"func/fn_addemployee.php",
                data:l,
                success:function(e){
                    if(e=='OK'){
                        $("#dialog_stis_cabrequest_setdriver").dialog("close");
                        $("#stis_main_cabrequest_vendor_list_grid").trigger('reloadGrid');
                        notifyBox("info", la.INFORMATION, la.ALLOCATE_DRIVER_SUCCESSFULLY);
                    }else{
                        notifyBox("error", la.ERROR, la.ERROR);
                    }
                }
            });
        break;
        case "cancel":
            var l={
                cmd:"vendor_cancel_trip",
                tid:tripid
            };
            $.ajax({
                type:"POST",
                url:"func/fn_addemployee.php",
                data:l,
                success:function(e){
                    if(e=='OK'){
                        $("#dialog_stis_cabrequest_setdriver").dialog("close");
                        $("#stis_main_cabrequest_vendor_list_grid").trigger('reloadGrid');
                        notifyBox("info", la.INFORMATION, la.CANCELED);
                    }else{
                        notifyBox("error", la.ERROR, la.ERROR);
                    }
                }
            });
        break;
    }
}

function viewSistCabAllocateDriver(id){
    var l={
        cmd:"grid_stis_allocatedriver",
        id:id
    };
    $.ajax({
        type:"POST",
        url:"func/fn_addemployee.php",
        data:l,
        success:function(e){
            $('#stis_main_cabrequest_viewdriver_list_grid').jqGrid('clearGridData');
            for (i=0;i<e.length;i++){
                var myData = [{"id":e[i]['id'],"username":e[i]['username'], "mobile":e[i]['subuser_phone']}];
                $("#stis_main_cabrequest_viewdriver_list_grid").jqGrid('addRowData', myData[0].id, myData[0]);
            }
            $("#stis_main_cabrequest_viewdriver_list_grid").trigger('reloadGrid');
        }
    })
}

function cancelsistCabRequest(id){
var l={
        cmd:"company_cancel_trip",
        tid:id
    };
    $.ajax({
        type:"POST",
        url:"func/fn_addemployee.php",
        data:l,
        success:function(e){
            if(e=='OK'){
                $("#stis_main_cabrequest_list_grid").trigger('reloadGrid');
                notifyBox("info", la.INFORMATION, la.CANCELED);
            }else{
                notifyBox("error", la.ERROR, la.ERROR);
            }
        }
    });
}

function getfromtoLatlngSelfCabBooking(loc,id){
     var e =loc;
    geocoderGetLocation(e, function(t) {
        if (void 0 == t[0].address) return void notifyBox("info", la.INFORMATION, la.NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST);
        e = t[0].address;
        var a = t[0].lat,
            o = t[0].lng;
            if (id=='from'){
                document.getElementById('self_cabrequest_from_latitude').value=a;
                document.getElementById('self_cabrequest_from_longitude').value=o;
            }else if(id=='to'){
                document.getElementById('self_cabrequest_to_latitude').value=a;
                document.getElementById('self_cabrequest_to_longitude').value=o;
            }
        })    
}

function selectEventaction(val){
    $("#settings_main_sosallert_list_grid").jqGrid("clearGridData");
    $("#settings_main_sosallert_list_grid").setGridParam({url: "func/fn_settings.shiftallocation.php?cmd=load_sos_alert&event="+val});
    $("#settings_main_sosallert_list_grid").trigger("reloadGrid");
}

$(document).ready(function() {
    setInterval("reloadGriddata30();",30000);
    setInterval("reloadGriddata120();",120000);
});

function reloadGriddata30(){
    $("#settings_main_sosallert_list_grid").trigger("reloadGrid");
    $("#stis_main_self_cabrequest_vendor_list_grid").trigger("reloadGrid");
}
function reloadGriddata120(){
    $("#dashboard_live_tripwisekm_report").trigger("reloadGrid");
}

function dashboardLivetripKM(e){
    $("#dashboard_live_tripwisekm_report").setGridParam({url: "func/dashboard.php?cmd=dashboard_live_tripwisereport&date="+e});
    $("#dashboard_live_tripwisekm_report").trigger("reloadGrid");
}

function dashboardOpenDallID(f){
    switch (f){
        default:
            flag='';
            fnloadgrid('');
            $('#dall').dialog('open');
            break;
        case 'online':
            flag='Online Vehicles';
            fnloadgrid('');
            $('#dall').dialog('open');
            break;
        case 'offline':
            flag='Offline Vehicles';
            fnloadgrid('');
            $('#dall').dialog('open');
            break;
        case 'moffline':
            flag='Maintenance Vehicle';
            fnloadgrid('');
            $('#dall').dialog('open');
            break;
        case 'nspeed':
            flag='Normal Speed';
            fnloadgrid('');
            $('#dall').dialog('open');
            break;
        case 'ospeed':
            flag='Over Speed';
            fnloadgrid('');
            $('#dall').dialog('open');
            break;
        case 'service':
            $('#dashboard_service_list').dialog('open');
            break;
    }
}

$(document).ready(function() {
    setInterval("reloadGriddata30();",30000);
    setInterval("reloadGriddata120();",120000);  
    //document.getElementById("dialog_dashb_hour_to").value = "23";
    //document.getElementById("dialog_dashb_minute_to").value = "59"; 
});

function reloadGriddata30(){
    $("#settings_main_sosallert_list_grid").trigger("reloadGrid");
    $("#stis_main_self_cabrequest_vendor_list_grid").trigger("reloadGrid");
    document.getElementById("dialog_dashb_hour_to").value = "23";
    document.getElementById("dialog_dashb_minute_to").value = "59";
}
function reloadGriddata120(){
    $("#dashboard_live_tripwisekm_report").trigger("reloadGrid");
}

function dashboardLivetripKM(){
    e=document.getElementById("dashboard_live_trip_searchobject").value;
    groupid=document.getElementById("dashboard_object_group_list").value;
    DRF = $("#dialog_dashb_hour_from").val() + ":" + $("#dialog_dashb_minute_from").val() + ":00",
    DRT = $("#dialog_dashb_hour_to").val() + ":" + $("#dialog_dashb_minute_to").val() + ":00";
    //$("#dashboard_live_tripwisekm_report").setGridParam({url: "func/dashboard.php?cmd=dashboard_live_tripwisereport&object="+e+"&groupid="+groupid});
    $("#dashboard_live_tripwisekm_report").setGridParam({url: "func/dashboard.php?cmd=dashboard_live_tripwisereport&object="+e+"&groupid="+groupid+"&df_time="+DRF+"&dt_time="+DRT});
    $("#dashboard_live_tripwisekm_report").trigger("reloadGrid");
}

function changeGroupwiseDashboadr(){
    dashboardLivetripKM();
    fnloadgrid('');
}

function liveTripReportGenerate(formate){
    loadingData(!0);
     DRF = $("#dialog_dashb_hour_from").val() + ":" + $("#dialog_dashb_minute_from").val() + ":00",
     DRT = $("#dialog_dashb_hour_to").val() + ":" + $("#dialog_dashb_minute_to").val() + ":00";

    var e={
        cmd:'downloadLiveTripReport',
        formate:formate,
        df_time: DRF,
        dt_time: DRT
    };
    $.ajax({
        type: "POST",
        url: "func/dashboard.php",
        data: e,
        success: function(t) {
            loadingData(!1),$.generateFile({
                filename: 'Live_Trip_Wise_Report',
                content: t,
                script: "func/fn_saveas.php?format="+formate
            })
        }
    })
}

function multipleReportDownload(imei,f,t,rname=false,r=false){
    var reportname=document.getElementById("dialog_report_type").value;
    if(r==false){
        report='drives_stops,events,ifsinfograph';
    }else{
        report=rname;
    }
    s=report.split(','),    
    dtf=document.getElementById("dialog_report_date_from").value,
    dtt=document.getElementById("dialog_report_date_to").value,
    totalobject=document.getElementById("dialog_report_object_list");
    document.getElementById("dialog_report_date_from").value=f;
    document.getElementById("dialog_report_date_to").value=t;    
    var selectobject=multiselectGetValues(document.getElementById("dialog_report_object_list"));
    for(var i=0;i<s.length;i++){
        var selectreport=document.getElementById("dialog_report_type");
        for ( var j = 0, len = selectreport.options.length; j < len; j++ ) {
                if(selectreport.options[j].value==s[i]){
                    selectreport.options[j].selected = true;
                }
        }
        reportsSwitchType();reportsListDataItems();reportsListSensors();
        var selectimeis=multiselectGetValues(document.getElementById("dialog_report_object_list"))
        document.getElementById("dialog_report_object_list").value=imei;
        $("#dialog_report_online_offline").prop("checked", false);
        reportsListDataItems();
        reportsListEventList();
        reportProperties('generate'); 
    }
    for (var j = 0, len = totalobject.options.length; j < len; j++ ) {
        var selectobject1=selectobject.split(',');
        for(var k=0;k<selectobject1.length;k++){
            if(totalobject.options[j].value==selectobject1[k]){
                totalobject.options[j].selected = true;
            }
        }
    }
    $("#dialog_report_online_offline").prop("checked", true);
    document.getElementById("dialog_report_date_from").value=dtf;
    document.getElementById("dialog_report_date_to").value=dtt;
    document.getElementById("dialog_report_type").value=reportname;
}

function mapObjectInfoReportDownload(imei,t,re){
    var t = t;
    var result = new Date(new Date(t).setDate(new Date(t).getDate() + -1));
    t = new Date(new Date(t).setDate(new Date(t).getDate()));
    t = t.getFullYear() + "-" + (t.getMonth() + 1) + "-" + t.getDate();
    let f = result.getFullYear() + "-" + (result.getMonth() + 1) + "-" + result.getDate();// let f = result.getFullYear() + "-" + (result.getMonth() + 1) + "-" + result.getDate() + " " + result.getHours() + ":" + result.getMinutes() + ":" + result.getSeconds();
    rname=re;
    document.getElementById("dialog_report_hour_from").value=result.getHours();
    document.getElementById("dialog_report_minute_from").value=result.getMinutes();
    document.getElementById("dialog_report_hour_to").value=result.getHours();
    document.getElementById("dialog_report_minute_to").value=result.getMinutes();
    var mulrer=true;
    if(rname=='drives_stops'){
        mulrer=false;
    }
    multipleReportDownload(imei,f,t,rname,mulrer);
}

function showobjectaddress(a,o,id){
    geocoderGetAddress(a, o,function(v){
        document.getElementById(id).innerHTML =v;
    })
}

// Created By nandha

// read excel data get rely in binary formate
function Employeelist_Upload() {
    //Reference the FileUpload element.
    var fileUpload = document.getElementById("employeelist_fileUpload");

    //Validate whether File is valid Excel file.
    var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
    if (regex.test(fileUpload.value.toLowerCase())) {
        if (typeof (FileReader) != "undefined") {
            var reader = new FileReader();

            //For Browsers other than IE.
            if (reader.readAsBinaryString) {
                reader.onload = function (e) {
                    PeoplelistProcessExcel(e.target.result);
                };
                reader.readAsBinaryString(fileUpload.files[0]);
            } else {
                //For IE Browser.
                reader.onload = function (e) {
                    var data = "";
                    var bytes = new Uint8Array(e.target.result);
                    for (var i = 0; i < bytes.byteLength; i++) {
                        data += String.fromCharCode(bytes[i]);
                    }
                    PeoplelistProcessExcel(data);
                };
                reader.readAsArrayBuffer(fileUpload.files[0]);
            }
        } else {
            // alert("This browser does not support HTML5.");
            notifyBox("info", la.INFORMATION, "This browser does not support HTML5.");
        }
    } else {
        // alert("Please upload a valid Excel file.");
        notifyBox("info", la.INFORMATION, "Please upload a valid Excel file.");

    }
}

 function PeoplelistProcessExcel(data) {
    //Read the Excel File data.
    var workbook = XLSX.read(data, {
        type: 'binary'
    });

    //Fetch the name of First Sheet.
    var firstSheet = workbook.SheetNames[0];

    //Read all rows from First Sheet into an JSON array.
    var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);

    var dat = {cmd: "upload_peoplelist",exceldata:excelRows};
    $.ajax({
            type: "POST",
            url: "func/fn_boarding.php",
            data: dat,
            success: function(o) {
                switch (o) {
                    default: var p = "error";
                    var r = la.ERROR;
                    var q = o;notifyBox(p, r, q);
                    rfidemployee('cancel');
                    rfidemployee('search');
                    break;
                    case "OK":
                    boeardingstudent('cancel');
                    boeardingstudent('search');
                    notifyBox("success", la.INFO, "Employee List Upload Successfully.");
                    break;
                    case "Invalid_Colums":
                    boeardingstudent('cancel');
                    boeardingstudent('search');
                    notifyBox("error", la.ERROR, "Please Use Valid Column Name.");
                    break;
                }            
            }
        });
}
