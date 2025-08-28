
  window.onload = function () { Clear(); }
    function Clear() {          
        var Backlen=history.length;
        if (Backlen > 0) history.go(-Backlen);
    }
// Dashboard fullview
var openvalue;
var navstyle;
$(document).ready(function() {
    $("#tglDBMV").toggle(
            function() {
                if(openvalue!=''){
                    $('#newdashboard').css({"display": "none"}),
                    $('#newdashboard_style').css({"display": "none"}),
                    navstyle=1,
                    openvalue="";
                }else{
                     $('#newdashboard').css({"display": "block"}),
                    $('#newdashboard_style').css({"display": "block"}),
                    navstyle="",
                     openvalue=1;
                }                
                $('#dialog_settings_fullview').css({"display": "none"}),
                $('#dialog_report_properties_fullview').css({"display": "none"}),
                $('#dialog_boarding_fullview').css({"display": "none"}),
                $('#dialog_rfidtrip_fullview').css({"display": "none"}),
                $('#dialog_object_commandnew_fullview').css({"display": "none"}),
                $('#dialog_cmd_fullview').css({"display": "none"}),
                $('#dialog_rilogbook_fullview').css({"display": "none"}),
                $('#dialog_sos_fullview').css({"display": "none"}), 
                $('#dialog_image_gallery_fullview').css({"display": "none"});                /*,document.getElementById("ui-id-16").click();*/
            },
            function() {
                if(openvalue!=''){
                    $('#newdashboard').css({"display": "none"}),
                     $('#newdashboard_style').css({"display": "none"}),
                     navstyle=1,
                    openvalue="";
                }else{
                     $('#newdashboard').css({"display": "block"}),
                     $('#newdashboard_style').css({"display": "block"}),
                     navstyle=0,
                     openvalue=1;
                }
                $('#dialog_settings_fullview').css({"display": "none"}),
                $('#dialog_report_properties_fullview').css({"display": "none"}),
                $('#dialog_boarding_fullview').css({"display": "none"}),
                $('#dialog_rfidtrip_fullview').css({"display": "none"}),
                $('#dialog_object_commandnew_fullview').css({"display": "none"}),
                $('#dialog_cmd_fullview').css({"display": "none"}),
                $('#dialog_rilogbook_fullview').css({"display": "none"}),
                $('#dialog_sos_fullview').css({"display": "none"}), 
                $('#dialog_image_gallery_fullview').css({"display": "none"});
                // $('#newdashboard').css({"display": "block"}),
                $('#cbp-spmenu-s2').css({"display": "block"}),
                    // var mapmenu=document.getElementById("map").className ;
                    document.getElementById("map").style.left = "129px";
                document.getElementById("bottom_panel").style.left = "125px";

                if (document.getElementById("map").className == "block width75 leaflet-container leaflet-touch leaflet-fade-anim leaflet-grab leaflet-touch-drag leaflet-touch-zoom") {
                    document.getElementById("map").className = "block width75 leaflet-container leaflet-touch leaflet-fade-anim leaflet-grab leaflet-touch-drag leaflet-touch-zoom";
                    map.invalidateSize(!0)
                } else if (document.getElementById("map").className == "block width80 leaflet-container leaflet-touch leaflet-fade-anim leaflet-grab leaflet-touch-drag leaflet-touch-zoom") {
                    document.getElementById("map").className = "block width75 leaflet-container leaflet-touch leaflet-fade-anim leaflet-grab leaflet-touch-drag leaflet-touch-zoom";
                    map.invalidateSize(!0)
                    document.getElementById("maplayer_top").style.left = "135px";
                } else {
                    document.getElementById("map").className = "leaflet-container leaflet-touch leaflet-fade-anim leaflet-grab leaflet-touch-drag leaflet-touch-zoom";
                    map.invalidateSize(!0)
                    document.getElementById("maplayer_top").style.left = "135px";

                }
                $('#dashboardpop3').dialog('open');
            }
        ),
    // $(".leaflet-control-attribution").css({"display":"none"});


    // settings fullview settings_fullview1
    $("#settings_fullview").toggle(
            function() {
                if(navstyle!=""){
                    $('#newdashboard_style').css({"display": "block"});                    
                }
                $('.county').attr('id', 'dvDemoNew');
                $('#newdashboard').css({"display": "none"}),
                $('#dialog_settings_fullview').css({"display": "block"}),
                $('#dialog_report_properties_fullview').css({"display": "none"}),
                $('#dialog_boarding_fullview').css({"display": "none"}),
                $('#dialog_rfidtrip_fullview').css({"display": "none"}),
                $('#dialog_object_commandnew_fullview').css({"display": "none"}),
                $('#dialog_cmd_fullview').css({"display": "none"}),
                $('#dialog_rilogbook_fullview').css({"display": "none"}),
                $('#dialog_image_gallery_fullview').css({"display": "none"}),
                $('#dialog_sos_fullview').css({"display": "none"}),
                openvalue=1;
                               
            },
            function() {
                if(navstyle!=""){
                    $('#newdashboard_style').css({"display": "block"});                    
                }
               $('#newdashboard').css({"display": "none"}),
                $('#dialog_settings_fullview').css({"display": "block"}),
                $('#dialog_report_properties_fullview').css({"display": "none"}),
                $('#dialog_boarding_fullview').css({"display": "none"}),
                $('#dialog_rfidtrip_fullview').css({"display": "none"}),
                $('#dialog_object_commandnew_fullview').css({"display": "none"}),
                $('#dialog_cmd_fullview').css({"display": "none"}),
                $('#dialog_rilogbook_fullview').css({"display": "none"}),
                $('#dialog_image_gallery_fullview').css({"display": "none"}),
                $('#dialog_sos_fullview').css({"display": "none"}), 
                 openvalue=1;
                // $('#cbp-spmenu-s2').css({"display":"block"}),
                // $('#dashboardpop3').dialog('open');
            }
        ),
    // report

    $("#report_fullview").toggle(
            function() {
                if(navstyle!=""){
                    $('#newdashboard_style').css({"display": "block"});                    
                }
                $('#newdashboard').css({"display": "none"}),
                $('#dialog_settings_fullview').css({"display": "none"}),
                $('#dialog_report_properties_fullview').css({"display": "block"}),
                $('#dialog_boarding_fullview').css({"display": "none"}),
                $('#dialog_rfidtrip_fullview').css({"display": "none"}),
                $('#dialog_object_commandnew_fullview').css({"display": "none"}),
                $('#dialog_cmd_fullview').css({"display": "none"}),
                $('#dialog_rilogbook_fullview').css({"display": "none"}),
                $('#dialog_image_gallery_fullview').css({"display": "none"}),
                $('#dialog_sos_fullview').css({"display": "none"}),
                
                openvalue=1;
            },
            function() {
                if(navstyle!=""){
                    $('#newdashboard_style').css({"display": "block"});                    
                }
                $('#newdashboard').css({"display": "none"}),
                $('#dialog_settings_fullview').css({"display": "none"}),
                $('#dialog_report_properties_fullview').css({"display": "block"}),
                $('#dialog_boarding_fullview').css({"display": "none"}),
                $('#dialog_rfidtrip_fullview').css({"display": "none"}),
                $('#dialog_object_commandnew_fullview').css({"display": "none"}),
                $('#dialog_cmd_fullview').css({"display": "none"}),
                $('#dialog_rilogbook_fullview').css({"display": "none"}),
                $('#dialog_image_gallery_fullview').css({"display": "none"}),
                $('#dialog_sos_fullview').css({"display": "none"}),
                
                openvalue=1;
            }
        ),

    // boarding

     $("#boarding_fullview").toggle(
            function() {
                if(navstyle!=""){
                    $('#newdashboard_style').css({"display": "block"});                    
                }
                $('#newdashboard').css({"display": "none"}),
                $('#dialog_settings_fullview').css({"display": "none"}),
                $('#dialog_report_properties_fullview').css({"display": "none"}),
                $('#dialog_boarding_fullview').css({"display": "block"}),
                $('#dialog_rfidtrip_fullview').css({"display": "none"}),
                $('#dialog_object_commandnew_fullview').css({"display": "none"}),
                $('#dialog_cmd_fullview').css({"display": "none"}),
                $('#dialog_rilogbook_fullview').css({"display": "none"}),
                $('#dialog_image_gallery_fullview').css({"display": "none"}),
                $('#dialog_sos_fullview').css({"display": "none"}),
                
                openvalue=1;
            },
            function() {
                if(navstyle!=""){
                    $('#newdashboard_style').css({"display": "block"});                    
                }
                $('#newdashboard').css({"display": "none"}),
                $('#dialog_settings_fullview').css({"display": "none"}),
                $('#dialog_report_properties_fullview').css({"display": "none"}),
                $('#dialog_boarding_fullview').css({"display": "block"}),
                $('#dialog_rfidtrip_fullview').css({"display": "none"}),
                $('#dialog_object_commandnew_fullview').css({"display": "none"}),
                $('#dialog_cmd_fullview').css({"display": "none"}),
                $('#dialog_rilogbook_fullview').css({"display": "none"}),
                $('#dialog_image_gallery_fullview').css({"display": "none"}),
                $('#dialog_sos_fullview').css({"display": "none"}),
                
                openvalue=1;
                // $('#cbp-spmenu-s2').css({"display":"block"}),
                // $('#dashboardpop3').dialog('open');
            }
        ),

     // rfid
     $("#rfid_fullview").toggle(
            function() {
                if(navstyle!=""){
                    $('#newdashboard_style').css({"display": "block"});                    
                }
                $('#newdashboard').css({"display": "none"}),
                $('#dialog_settings_fullview').css({"display": "none"}),
                $('#dialog_report_properties_fullview').css({"display": "none"}),
                $('#dialog_boarding_fullview').css({"display": "none"}),
                $('#dialog_rfidtrip_fullview').css({"display": "block"}),
                $('#dialog_object_commandnew_fullview').css({"display": "none"}),
                $('#dialog_cmd_fullview').css({"display": "none"}),
                $('#dialog_rilogbook_fullview').css({"display": "none"}),
                $('#dialog_image_gallery_fullview').css({"display": "none"}),
                $('#dialog_sos_fullview').css({"display": "none"}),
                
                openvalue=1;

                /*,document.getElementById("ui-id-16").click();*/
            },
            function() {
                if(navstyle!=""){
                    $('#newdashboard_style').css({"display": "block"});                    
                }
                $('#newdashboard').css({"display": "none"}),
                $('#dialog_settings_fullview').css({"display": "none"}),
                $('#dialog_report_properties_fullview').css({"display": "none"}),
                $('#dialog_boarding_fullview').css({"display": "none"}),
                $('#dialog_rfidtrip_fullview').css({"display": "block"}),
                $('#dialog_object_commandnew_fullview').css({"display": "none"}),
                $('#dialog_cmd_fullview').css({"display": "none"}),
                $('#dialog_rilogbook_fullview').css({"display": "none"}),
                $('#dialog_image_gallery_fullview').css({"display": "none"}),
                $('#dialog_sos_fullview').css({"display": "none"}),
                
                openvalue=1;
                // $('#cbp-spmenu-s2').css({"display":"block"}),
                // $('#dashboardpop3').dialog('open');
            }
        ),

     // send command
      $("#sendcommand_fullview").toggle(
            function() {
                if(navstyle!=""){
                    $('#newdashboard_style').css({"display": "block"});                    
                }
               $('#newdashboard').css({"display": "none"}),
                $('#dialog_settings_fullview').css({"display": "none"}),
                $('#dialog_report_properties_fullview').css({"display": "none"}),
                $('#dialog_boarding_fullview').css({"display": "none"}),
                $('#dialog_rfidtrip_fullview').css({"display": "none"}),
                $('#dialog_object_commandnew_fullview').css({"display": "block"}),
                $('#dialog_cmd_fullview').css({"display": "none"}),
                $('#dialog_rilogbook_fullview').css({"display": "none"}),
                $('#dialog_image_gallery_fullview').css({"display": "none"}),
                $('#dialog_sos_fullview').css({"display": "none"}),
                
                openvalue=1;
                /*,document.getElementById("ui-id-16").click();*/
            },
            function() {
                if(navstyle!=""){
                    $('#newdashboard_style').css({"display": "block"});                    
                }
                $('#newdashboard').css({"display": "none"}),
                $('#dialog_settings_fullview').css({"display": "none"}),
                $('#dialog_report_properties_fullview').css({"display": "none"}),
                $('#dialog_boarding_fullview').css({"display": "none"}),
                $('#dialog_rfidtrip_fullview').css({"display": "none"}),
                $('#dialog_object_commandnew_fullview').css({"display": "block"}),
                $('#dialog_cmd_fullview').css({"display": "none"}),
                $('#dialog_rilogbook_fullview').css({"display": "none"}),
                $('#dialog_image_gallery_fullview').css({"display": "none"}),
                $('#dialog_sos_fullview').css({"display": "none"}),
                
                openvalue=1;
                // $('#cbp-spmenu-s2').css({"display":"block"}),
                // $('#dashboardpop3').dialog('open');
            }
        ),

      // Object Controll
       $("#objectcontrol_fullview").toggle(
            function() {
                if(navstyle!=""){
                    $('#newdashboard_style').css({"display": "block"});                    
                }
                $('#newdashboard').css({"display": "none"}),
                $('#dialog_settings_fullview').css({"display": "none"}),
                $('#dialog_report_properties_fullview').css({"display": "none"}),
                $('#dialog_boarding_fullview').css({"display": "none"}),
                $('#dialog_rfidtrip_fullview').css({"display": "none"}),
                $('#dialog_object_commandnew_fullview').css({"display": "none"}),
                $('#dialog_cmd_fullview').css({"display": "block"}),
                $('#dialog_rilogbook_fullview').css({"display": "none"}),
                $('#dialog_image_gallery_fullview').css({"display": "none"}),
                $('#dialog_sos_fullview').css({"display": "none"}),
                 
                openvalue=1;
                /*,document.getElementById("ui-id-16").click();*/
            },
            function() {
                if(navstyle!=""){
                    $('#newdashboard_style').css({"display": "block"});                    
                }
                $('#newdashboard').css({"display": "none"}),
                $('#dialog_settings_fullview').css({"display": "none"}),
                $('#dialog_report_properties_fullview').css({"display": "none"}),
                $('#dialog_boarding_fullview').css({"display": "none"}),
                $('#dialog_rfidtrip_fullview').css({"display": "none"}),
                $('#dialog_object_commandnew_fullview').css({"display": "none"}),
                $('#dialog_cmd_fullview').css({"display": "block"}),
                $('#dialog_rilogbook_fullview').css({"display": "none"}),
                $('#dialog_image_gallery_fullview').css({"display": "none"}),
                $('#dialog_sos_fullview').css({"display": "none"}),
                 
                openvalue=1;
            }
        ),

       // RFID loogbook
       $("#logbook_fullview").toggle(
            function() {
                if(navstyle!=""){
                    $('#newdashboard_style').css({"display": "block"});                    
                }
                $('#newdashboard').css({"display": "none"}),
                $('#dialog_settings_fullview').css({"display": "none"}),
                $('#dialog_report_properties_fullview').css({"display": "none"}),
                $('#dialog_boarding_fullview').css({"display": "none"}),
                $('#dialog_rfidtrip_fullview').css({"display": "none"}),
                $('#dialog_object_commandnew_fullview').css({"display": "none"}),
                $('#dialog_cmd_fullview').css({"display": "none"}),
                $('#dialog_rilogbook_fullview').css({"display": "block"}),
                $('#dialog_image_gallery_fullview').css({"display": "none"}),
                $('#dialog_sos_fullview').css({"display": "none"}),
                
                openvalue=1;
            },
            function() {
                if(navstyle!=""){
                    $('#newdashboard_style').css({"display": "block"});                    
                }
                $('#newdashboard').css({"display": "none"}),
                $('#dialog_settings_fullview').css({"display": "none"}),
                $('#dialog_report_properties_fullview').css({"display": "none"}),
                $('#dialog_boarding_fullview').css({"display": "none"}),
                $('#dialog_rfidtrip_fullview').css({"display": "none"}),
                $('#dialog_object_commandnew_fullview').css({"display": "none"}),
                $('#dialog_cmd_fullview').css({"display": "none"}),
                $('#dialog_rilogbook_fullview').css({"display": "block"}),
                $('#dialog_image_gallery_fullview').css({"display": "none"}),
                $('#dialog_sos_fullview').css({"display": "none"}),
                 
                openvalue=1;
            }
        ),

        // imagegallery loogbook
       $("#imagegallery_fullview").toggle(
            function() {
                if(navstyle!=""){
                    $('#newdashboard_style').css({"display": "block"});                    
                }
                $('#newdashboard').css({"display": "none"}),
                $('#dialog_settings_fullview').css({"display": "none"}),
                $('#dialog_report_properties_fullview').css({"display": "none"}),
                $('#dialog_boarding_fullview').css({"display": "none"}),
                $('#dialog_rfidtrip_fullview').css({"display": "none"}),
                $('#dialog_object_commandnew_fullview').css({"display": "none"}),
                $('#dialog_cmd_fullview').css({"display": "none"}),
                $('#dialog_rilogbook_fullview').css({"display": "none"}),
                $('#dialog_sos_fullview').css({"display": "none"}),
                $('#dialog_image_gallery_fullview').css({"display": "block"}),
                
                openvalue=1;
            },
            function() {
                if(navstyle!=""){
                    $('#newdashboard_style').css({"display": "block"});                    
                }
                $('#newdashboard').css({"display": "none"}),
                $('#dialog_settings_fullview').css({"display": "none"}),
                $('#dialog_report_properties_fullview').css({"display": "none"}),
                $('#dialog_boarding_fullview').css({"display": "none"}),
                $('#dialog_rfidtrip_fullview').css({"display": "none"}),
                $('#dialog_object_commandnew_fullview').css({"display": "none"}),
                $('#dialog_cmd_fullview').css({"display": "none"}),
                $('#dialog_rilogbook_fullview').css({"display": "none"}),
                $('#dialog_image_gallery_fullview').css({"display": "block"}),
                
                openvalue=1;
            }
        ),

       // imagegallery loogbook
       $("#sosalert_fullview").toggle(
            function() {
                if(navstyle!=""){
                    $('#newdashboard_style').css({"display": "block"});                    
                }
                $('#newdashboard').css({"display": "none"}),
                $('#dialog_settings_fullview').css({"display": "none"}),
                $('#dialog_report_properties_fullview').css({"display": "none"}),
                $('#dialog_boarding_fullview').css({"display": "none"}),
                $('#dialog_rfidtrip_fullview').css({"display": "none"}),
                $('#dialog_object_commandnew_fullview').css({"display": "none"}),
                $('#dialog_cmd_fullview').css({"display": "none"}),
                $('#dialog_rilogbook_fullview').css({"display": "none"}),
                $('#dialog_image_gallery_fullview').css({"display": "none"}),
                $('#dialog_sos_fullview').css({"display": "block"}),
                
                openvalue=1;
            },
            function() {
                if(navstyle!=""){
                    $('#newdashboard_style').css({"display": "block"});                    
                }
                $('#newdashboard').css({"display": "none"}),
                $('#dialog_settings_fullview').css({"display": "none"}),
                $('#dialog_report_properties_fullview').css({"display": "none"}),
                $('#dialog_boarding_fullview').css({"display": "none"}),
                $('#dialog_rfidtrip_fullview').css({"display": "none"}),
                $('#dialog_object_commandnew_fullview').css({"display": "none"}),
                $('#dialog_cmd_fullview').css({"display": "none"}),
                $('#dialog_rilogbook_fullview').css({"display": "none"}),
                $('#dialog_image_gallery_fullview').css({"display": "none"}),
                $('#dialog_sos_fullview').css({"display": "block"}),
                 
                openvalue=1;
            }
        ),

    // old
        $("#searchobjectsetting").bind("keyup", function(d) {
            jQuery("#settings_main_object_list_grid").jqGrid().setGridParam({
                url: "func/fn_settings.objects.php?cmd=load_object_list&nameimei=" + this.value
            }).trigger("reloadGrid");
        }),
        $("#searcheventsetting").bind("keyup", function(d) {
            jQuery("#settings_main_events_event_list_grid").jqGrid().setGridParam({
                url: "func/fn_settings.events.php?cmd=load_event_list&nameimei=" + this.value
            }).trigger("reloadGrid");
        })
        
    // $(".leaflet-control-attribution").css({"display":"none"});

});
function settingsOpenUser1(){
    if(document.getElementById("dialog_settings_fullview").style.display=='block'){
    $('#dialog_settings_fullview').css({"display": "none"}),        
    $('#newdashboard').css({"display": "block"});
    }else{
    document.getElementById("settings_main_my_account_tab").click();
    $('#newdashboard').css({"display": "none"}),
    $('#dialog_settings_fullview').css({"display": "block"}),
    $('#dialog_report_properties_fullview').css({"display": "none"}),
    $('#dialog_boarding_fullview').css({"display": "none"}),
    $('#dialog_rfidtrip_fullview').css({"display": "none"}),
    $('#dialog_object_commandnew_fullview').css({"display": "none"}),
    $('#dialog_cmd_fullview').css({"display": "none"}),
    $('#dialog_rilogbook_fullview').css({"display": "none"}),
    $('#dialog_image_gallery_fullview').css({"display": "none"});
}   
}

// Other script
$(function() {
    $('a.waves-effect').click(function() {
        $('a.waves-effect').removeClass('active');
        $(this).addClass('active');
    });
});

 function hideshow_graph(){
    var graphclass=document.getElementById("hideshowgraph").className;
    if(graphclass=="hidegraph"){
        document.getElementById("hideshowgraph").className ="showgraph";        
        $('#bottom_panel').css({"display": "none"});
    }else{
         $('#bottom_panel').css({"display": "block"});
         document.getElementById("hideshowgraph").className="hidegraph";
    }
}

function hideshowmap_btn(){
    if($('.leaflet-control-container').css('display')=='block'){
        $('.leaflet-control-container').css({"display": "none"});
        $('.hideshow_btn').css({"padding-left": "14px"});
    }else{
    $('.leaflet-control-container').css({"display": "block"});
     $('.hideshow_btn').css({"padding-left": "50px"});
    }
}

if($(window).width()<1000){
   alert('mobile view');
}  

function emergencyalertSelectedit(){
     if (utilsCheckPrivileges("viewer") && utilsCheckPrivileges("subuser")) {
       // var e1 = $("#settings_main_sosallert_list_grid").jqGrid("getRowData");
       //  var e = $("#settings_main_sosallert_list_grid").jqGrid("getGridParam", "selarrrow"),n,e1;
       var $grid = $("#settings_main_sosallert_list_grid"), selIds = $grid.jqGrid("getGridParam", "selarrrow");
        if("" == selIds)
        {
          notifyBox("error", la.ERROR, la.NO_ITEMS_SELECTED) ;
          return;
        }

        var custom = $grid.jqGrid('getRowData');
        
        $('#settings_main_sosallert_token_grid').jqGrid('clearGridData');
 
         for (i = 0; i < selIds.length; i++) 
         {             
            $("#settings_main_sosallert_token_grid").jqGrid('addRowData', selIds[i],custom[selIds[i]]);
         }
           
        $("#dialog_sos_edit_select_token").dialog("open");
               
    }

}
var lastsel;
function save_soscompletetoken(){
    if (utilsCheckPrivileges("viewer") && utilsCheckPrivileges("subuser")) {
       var $grid = $("#settings_main_sosallert_token_grid"), selIds = $grid.jqGrid("getDataIDs");
        if("" == selIds)
        {
          notifyBox("error", la.ERROR, la.NO_ITEMS_SELECTED) ;
          return;
        }
        var custom = $grid.jqGrid('getRowData');

         for (i = 0; i < selIds.length; i++) 
         {
            adddat=custom[i];
            // leng=custom[selIds[i]].length;
            var eventvalue=adddat['eventid'];
            var responcevalue=$("#responsedataId"+selIds[i]).val();
            var actiontakenvalue=$("#actiontakenedataId"+selIds[i]).val();
            var actionbyvalue=$("#actionbydataId"+selIds[i]).val();            
            // $("#responsedataId").val()="";
            if(responcevalue=="" ||actiontakenvalue=="" || actionbyvalue=="" ){                
               notifyBox("error", la.ERROR, la.PLSFILLALLDETAILS);    
               $("#settings_main_sosallert_token_grid").trigger("reloadGrid");
                return;
            }
            var multipledta=[eventvalue,responcevalue,actiontakenvalue,actionbyvalue];
            var s = {
                cmd: "save_soscompletetoken_data",
                items: multipledta
            };
            $.ajax({
            type: "POST",
            url: "func/fn_sosalert.php",
            data: s,
            success: function(e) {
                if("OK" == e){ 
                    $('#settings_main_sosallert_token_grid').jqGrid('delRowData',i);
                    $("#settings_main_sosallert_list_grid").trigger("reloadGrid");                   
                }
            }
        })
         }
        $("#dialog_sos_edit_select_token").dialog("close");
        notifyBox("info", la.INFORMATION, la.CHANGES_SAVED_SUCCESSFULLY);          
       
    }
}

function responsedata(cellValue, options, rowobject, action) {
   
    return "<input type='text' rows='5' cols='20' id='responsedataId"+cellValue+"' value=''>";
}
function actiontakenedata(cellValue, options, rowobject, action) {
   
    return "<input type='text' rows='5' cols='20' id='actiontakenedataId"+cellValue+"' value=''>";
}
function actionbydata(cellValue, options, rowobject, action) {
   
    return "<input type='text' rows='5' cols='20' id='actionbydataId"+cellValue+"' value=''>";
}
