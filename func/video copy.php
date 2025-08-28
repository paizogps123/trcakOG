<?php
        session_start();
        include ('../init.php');
	include ('fn_common.php');
	//checkUserSession();
        //Code Update By Vetrivel.NR
        // need to delete comments if there is no issue in live

	//echo json_encode($_SESSION);
	        global $gsValues, $ms, $la;
        if ($_SESSION["privileges"] == 'subuser')
        {
            $user_id = $_SESSION["user_id"];
            $saimeis = $_SESSION["privileges_imei"];


            $qv = "SELECT value FROM `gs_object_custom_fields` WHERE `name` = 'CameraDeviceID' and imei in (".$saimeis.")";
        }
        else
        {
            $user_id = $_SESSION["user_id"];
            $qv = "SELECT cf.value FROM `gs_object_custom_fields` cf  join gs_user_objects go on go.imei=cf.imei WHERE cf.name = 'CameraDeviceID' and go.user_id=".$user_id;
        }
	//echo $qv;
        $r = mysqli_query($ms, $qv);
        if (!$r)
	{
		#die("CameraDeviceID not found");
		echo '<center><blink style="color:red;font-weight:bold;font-size:34px" > Please Login Again From Vehicle Tracking System</blink></center>';
        }
		
        $cds = array();
        while($cd=mysqli_fetch_array($r))
		{
            $cds[] = $cd["value"];
        }


        function get_token() {
           	$url = "https://cam.paizogps.in/StandardApiAction_login.action?account=admin&password=Paizo@123";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            if (curl_errno($ch)) {
                throw new Exception('cURL error: ' . curl_error($ch));
            }
            curl_close($ch);
            $data = json_decode($response, true);
            if (isset($data['jsession'])) {
                return $data;
            } else {
                throw new Exception("JSESSION not found in the response.");
            }
        }

        function get_vehicles($jsession) {
            $url = "https://cam.paizogps.in/StandardApiAction_queryUserVehicle.action?jsession=".$jsession;
            // echo $url;
         $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL, $url);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         $response = curl_exec($ch);
         if (curl_errno($ch)) {
             throw new Exception('cURL error: ' . curl_error($ch));
         }
         curl_close($ch);
         $data = json_decode($response, true);
         if (isset($data['vehicles'])) {
            return $data;
        } else {
            throw new Exception("vehicles not found in the response.");
        }
     }


        $tokenData = get_token();

        $jsession = $tokenData['jsession'];
        $pri = $tokenData['pri'];

        $vehicle_list = get_vehicles($jsession);

        // echo  $jsession;

        // echo json_encode($vehicle_list);

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title></title>
    <link href="https://cam.paizogps.in/bootstrap/css/button.css?tv=${version}" type="text/css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cam.paizogps.in/808gps/css/labelIcon.css?tv=${version}">
    <link href="https://cam.paizogps.in/808gps/open/css/video.css?tv=${version}" type="text/css" rel="stylesheet">
    <script type="text/javascript" src="https://cam.paizogps.in/808gps/js/jquery.min.js?tv=${version}"></script>
    <script type="text/javascript" src="https://cam.paizogps.in/808gps/open/player/swfobject-all.js?tv=${version}"></script>
    <script type="text/javascript"
            src="https://cam.paizogps.in/808gps/open/player/jquery.query-2.1.7.js?tv=${version}"></script>
    <script type="text/javascript" src="https://cam.paizogps.in/808gps/js/changeTheme.js?tv=${version}"></script>
    <script type="text/javascript" src="https://cam.paizogps.in/js/WdatePicker/WdatePicker.js?tv=${version}"></script>
    <script type="text/javascript" src="https://cam.paizogps.in/js/flexigrid/flexigrid.js?tv=${version}"></script>
    <script type="text/javascript" src="https://cam.paizogps.in/js/lhgdialog.min.js?tv=${version}"></script>
    <script type="text/javascript" src="https://cam.paizogps.in/js/localStorage.js?tv=${version}"></script>
    <script type="text/javascript" src="https://cam.paizogps.in/js/public.js?tv=${version}"></script>
    <script type="text/javascript" src="https://cam.paizogps.in/808gps/js/public.js?tv=${version}"></script>
    <script type="text/javascript" src="https://cam.paizogps.in/808gps/js/date.js?tv=${version}"></script>
    <script type="text/javascript" src="https://cam.paizogps.in/808gps/js/userRole.js?tv=${version}"></script>
    <script type="text/javascript" src="https://cam.paizogps.in/808gps/js/hashtable.js?tv=${version}"></script>
    <script type="text/javascript" src="https://cam.paizogps.in/808gps/js/myajax.js?tv=${version}"></script>
    <script type="text/javascript" src="https://cam.paizogps.in/808gps/js/lang_fun.js?tv=${version}"></script>
    <style type="text/css">
	 blink {
            animation: blinker 1.5s linear infinite;
            color: red;
            font-size: 1.5em;
            margin-bottom: 20px;
        }

        @keyframes blinker {
            50% {
                opacity: 0;
            }
        }

        .flexigrid .bDiv a {
            margin: 0px 10px;
            outline: 0 none;
            text-decoration: none;
            cursor: pointer;
        }

        .flexigrid {
            overflow: visible;
        }

        .icon svg{
            width: 25px;
        }
    </style>
    <script type="text/javascript">
        let domainName = "cam.paizogps.in";
        $(function () {
            //Load language
            langInitByUrl();
            loadReadyPage();
		userLogin();
        });

        function loadReadyPage() {
            if(typeof lang === 'undefined'){
                setTimeout(loadReadyPage, 50);
                return;
            }
            //Solve the impact of PC screen scaling on page layout
            handleScreen();
            alert(lang.GetnumberPrompt);
            var datatime = new Date();

            if(crossDay == 1){
                $("#startTime").val(datatime.getFullYear() + "-" + (datatime.getMonth() + 1) + "-" + datatime.getDate() + " 00:00:00");
                $("#startTime").click(function () {
                    WdatePicker({lang: 'zh', dateFmt: 'yyyy-MM-dd HH:mm:ss'});
                });
                $("#endTime").val(datatime.getFullYear() + "-" + (datatime.getMonth() + 1) + "-" + datatime.getDate() + " " + 23 + ":" + 59 + ":" + 59);
                $("#endTime").click(function () {
                    WdatePicker({lang: 'zh', dateFmt: 'yyyy-MM-dd HH:mm:ss'});
                });
                $("#labelExTime").hide();
                $("#exTime").hide();

            }else{
                $("#startTime").val(datatime.getFullYear() + "-" + (datatime.getMonth() + 1) + "-" + datatime.getDate());
                $("#startTime").click(function () {
                    WdatePicker({lang: 'zh', dateFmt: 'yyyy-MM-dd'});
                });
                $("#endTime").val("00:00:00");
                $("#endTime").click(function () {
                    WdatePicker({lang: 'zh', dateFmt: 'HH:mm:ss'});
                });
                $("#labelExTime").show();
                $("#exTime").show();
                $("#exTime").val(23 + ":" + 59 + ":" + 59);
                $("#exTime").click(function () {
                    WdatePicker({lang: 'zh', dateFmt: 'HH:mm:ss'});
                });
            }


            //Custom header
            if ($.query.get("lang") == "zh") {
                // lang = new langZhCn();
                $('.languagePath').val('cn.xml');
            } else {
                // lang = new langEn();
                $('.languagePath').val('en.xml');
            }
            $("#videoFileTable").flexigrid({
                url: "",
                dataType: 'json',
                colModel: [
                    {display: lang.operator, name: 'operator', width: 100, sortable: false, align: 'center'},
                    {display: lang.index, name: 'fileIndex', width: 40, sortable: false, align: 'center'},
                    {display: lang.fileTime, name: 'fileTime', width: 150, sortable: false, align: 'center'},
                    {display: lang.alarm_record_type, name: 'type', width: 80, sortable: false, align: 'center'},
                    {display: lang.terminalDevice, name: 'vehiIdno', width: 100, sortable: false, align: 'center'},
                    {display: lang.alarm_channel, name: 'vehiChn', width: 70, sortable: false, align: 'center'},
                    {display: lang.fileLocation, name: 'loc', width: 80, sortable: false, align: 'center'},
                    {display: lang.fileSize, name: 'fileSize', width: 80, sortable: false, align: 'center'},
                    {display: lang.file, name: 'file', width: 380, sortable: false, align: 'center'},
                    {display: 'svr', name: 'svr', hide: true},
                    {display: 'devIdno', name: 'devIdno', hide: true},
                    {display: 'len', name: 'len', hide: true},
                    {display: 'chnMask', name: 'chnMask', hide: true},
                    {display: 'beg', name: 'beg', hide: true},
                    {display: 'end', name: 'end', hide: true},
                ],
                usepager: false,
                autoload: false,
                useRp: false,
                singleSelect: true,
                clickRowCenter: true,
                checkbox: true,
                rp: 15,
                showTableToggleBtn: true,
                showToggleBtn: false,
                width: '1300px',
                height: 'auto',
                resizable: false
            });

            //Load control Chinese or English name
            loadLang();

            $("#videoFileTable").flexSetFillCellFun(function (p, row, idx, index) {
                return fillVideoFileTable(p, row, idx, index);
            });

            //Initialize video plug ins
            initPlayerExample();

            $("#userLoginBtn").click(function () {
                userLogin();
            });

            //Monitor and playback Radio
            monitorPlaybackRadio()

            //Monitor multi-channel playback button
            monitorPlayBtn()
        }

        var jsion = "<?php echo $jsession; ?>";	//Used to determine whether the landing
        var ip_ = "";
        var port_ = "";
        var isLanding = false;//To determine whether the landing
        var IsSearching = false;//To determine whether the search
        var loadTimeLine = true; //Whether to load the timeline, if the return file date error is not loaded
        var mapVehicleInfo = new Hashtable();//vehicle info
        var isInitFinished = false;//Video plug-in is loaded to complete
        var serverIp = "";//Server IP
        var serverPort = "";//Server Port
        // var lang;
        var searchVehicle = null; //The vehicle being query
        var videoFileList = new Hashtable();  //Video file list
        //Define Chinese name
        // var crossDay = getUrlParameter("crossDay")
        var crossDay = 1;
        var myUserRole = new userRole();

        //Load control Chinese or English name
        function loadLang() {
			lang.login = "Authenticate";
            lang.search = "Search Video on Storage Server";
            document.title = lang.videoExample;
            $('#getJsessionTitle').text(lang.geSessionId);
            $('#accountTitle').text(lang.userId);
            $('#passwordTitle').text(lang.login_Password);
            $('#userLoginBtn').text(lang.login);
            $('#Condition').text(lang.Condition);
            $('#NumberPlates').text(lang.monitor_labelVehicleIdno);

            if(crossDay == 1){
                $('#labelStartTime').text(lang.startTime);
                $('#labelEndTime').text(lang.endTime)
            }else{
                $('#labelStartTime').text(lang.open_query_day);
                $('#labelEndTime').text(lang.startTime)
                $("#labelExTime").text(lang.endTime)
            }

            $('#filelocation').text(lang.filelocation);
            $('#spanDevice').text(lang.terminalDevice);
            $('#spanDownloadServer').text(lang.DownloadServer);
            $('#spanStorageServer').text(lang.server_storage);
            $('#filetype').text(lang.filetype);
            $('#spanVideoType').text(lang.videoType);
            $('#VideoType').text(lang.VideoType);
            $('#spanVideoNormal').text(lang.Normal);
            $('#spanVideoAlarm').text(lang.Alarm);
            $('#spanVideoAll').text(lang.allEx);
            $('#search').text(lang.search);
            $('#playbackTitle').text(lang.playbackTitle);
            $('#queryresults').text(lang.queryresults);
            $('#portTitle').text(lang.portTitle);
            $('#IPTitle').text(lang.IPTitle);

            setText('playbackChannelTitle', lang.channel);
            setText('playbackTypeSpan1', lang.singleChannel)
            setText('playbackTypeSpan2', lang.multiChannel)
            setText('playbackTypeSpan3', lang.multiChannelUnSync)

            $('#playVideoBtn').text(lang.play)

        }

        function monitorPlaybackRadio() {
            document.getElementById("playbackRadio").addEventListener("click", function (e) {
                if (e.target.tagName === "INPUT") {
                    var playbackChannelObj = document.getElementById("playbackChannel")
                    var playbackChannelObj2 = document.getElementById("playbackChannel2")
                    var playbackChannelObj3 = document.getElementById("playbackChannel3")
                    switch (e.target.value) {
                        case "1":
                            playbackChannelObj.value = "0"
                            playbackChannelObj.disabled = 'disabled'
                            playbackChannelObj2.style.display = 'none'
                            playbackChannelObj3.style.display = 'none'
                            break
                        case "2":
                            playbackChannelObj.value = "0,1,2"
                            playbackChannelObj.disabled = ''
                            playbackChannelObj2.style.display = 'none'
                            playbackChannelObj3.style.display = 'none'
                            break
                        case "3":
                            playbackChannelObj.value = ""
                            playbackChannelObj.disabled = 'disabled'
                            playbackChannelObj2.style.display = ''
                            playbackChannelObj3.style.display = ''
                            break
                    }
                }
            })
        }

        function monitorPlayBtn() {
            $('#playVideoBtn').on('click', function () {

                //Get selected rows
                var selectRows = $('#videoFileTable').selectedRows()
                var length = selectRows.length

                if (length === 0) {
                    alert(parent.lang.chooseVideo)
                    return
                }

                var next = true;
                var files = []
                for (var i = 0; i < length; i++) {
                    var row = selectRows[i]
                    var id = row[2].Value
                    var fileInfo = videoFileList.get(Number(id));
                    if (fileInfo) {
                        if(i === 0){
                            if(fileInfo.mulPlay){
                                next = false;
                                break;
                            }
                        }
                        var wsPath = fileInfo.PlaybackUrlWs
                        if (!wsPath) {
                            wsPath = fileInfo.PlaybackUrl
                        }
                        files.push(wsPath)
                    }
                }
                if(!next){
                    $.dialog.tipWarning(lang.current_device_does_not_support, 2);
                    return;
                }
                swfobject.stopVodM();
                swfobject.startVodM(files, length);

            })
        }

        function setText(domId, val) {
            var domItem = document.getElementById(domId);
            if (domItem != null) {
                domItem.innerHTML = val;
            }
        }

        function fillVideoFileTable(p, row, idx, index) {
            var name = p.colModel[idx].name;
            var ret = "";
            if (name == 'fileIndex') {
                ret = row.id + 1;
            } else if (name == 'fileTime') {
                var fileRealDate = getFileTime(row.year, row.mon, row.day);
                var relBeg = row.beg;
                var relEnd = row.end;
                var beginDate = fileRealDate + ' ' + second2ShortHourEx(row.beg);
                var endDate = fileRealDate + ' ' + second2ShortHourEx(row.end);
                var timeTitle = row.beginDate + ' - ' + second2ShortHourEx(row.end);
                ret = timeTitle;
            } else if (name == 'vehiIdno') {
                ret = row.vehiIdno;
            } else if (name == 'type') {
                if (row.type == "0") {
                    ret = lang.Normal;
                } else {
                    ret = lang.Alarm;
                }
            } else if (name == 'vehiChn') {
                ret = row.chnName;
            } else if (name == 'loc') {
                if (row.loc == 1) {
                    ret = lang.device;
                } else if (row.loc == 2) {
                    ret = lang.StorageServer;
                } else if (row.loc == 4) {
                    ret = lang.DownloadServer;
                }
            } else if (name == 'fileSize') {
                ret = (row.len / 1024 / 1024).toFixed(2) + 'MB';
            } else if (name == 'file') {
                ret = row.file;
            } else if (name == 'operator') {
                ret = '<a class="icon" onclick="downloadVideoFile(' + row['id'] + ');" title="' + lang.download + '">';
                ret += '<svg width="26" height="26" viewBox="0 0 64 64" id="svg5" version="1.1" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:svg="http://www.w3.org/2000/svg"><defs id="defs2"></defs><g id="layer1" transform="translate(-384,-384)"><path d="m 393.99999,393 h 49 v 6 h -49 z" id="path27011" style="fill:#3e4f59;fill-opacity:1;fill-rule:evenodd;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4.1"></path><path d="m 393.99999,399 h 49 v 40 h -49 z" id="path27013" style="fill:#acbec2;fill-opacity:1;fill-rule:evenodd;stroke-width:2.00001;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4.1"></path><path d="m 393.99999,399 v 40 h 29.76953 a 28.484051,41.392605 35.599482 0 0 18.625,-40 z" id="path27015" style="fill:#e8edee;fill-opacity:1;fill-rule:evenodd;stroke:none;stroke-width:2.00002;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4.1"></path><path d="m 395.99999,392 c -1.64501,0 -3,1.355 -3,3 v 40 c 0,0.55229 0.44772,1 1,1 0.55229,0 1,-0.44771 1,-1 v -40 c 0,-0.56413 0.43587,-1 1,-1 h 45 c 0.56413,0 1,0.43587 1,1 v 3 h -42 c -0.55228,0 -1,0.44772 -1,1 0,0.55229 0.44772,1 1,1 h 42 v 37 c 0,0.56413 -0.43587,1 -1,1 h -49 c -0.55228,0 -1,0.44772 -1,1 0,0.55229 0.44772,1 1,1 h 49 c 1.64501,0 3,-1.35499 3,-3 0,-14 0,-28 0,-42 0,-1.645 -1.35499,-3 -3,-3 z" id="path27017" style="color:#000000;fill:#000000;fill-rule:evenodd;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4.1"></path><path d="m 438.99999,395 c -0.55228,0 -1,0.44772 -1,1 0,0.55229 0.44772,1 1,1 0.55229,0 1,-0.44771 1,-1 0,-0.55228 -0.44771,-1 -1,-1 z" id="path27019" style="color:#000000;fill:#ed7161;fill-opacity:1;fill-rule:evenodd;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4.1;-inkscape-stroke:none"></path><path d="m 434.99999,395 c -0.55228,0 -1,0.44772 -1,1 0,0.55229 0.44772,1 1,1 0.55229,0 1,-0.44771 1,-1 0,-0.55228 -0.44771,-1 -1,-1 z" id="path27021" style="color:#000000;fill:#ecba16;fill-opacity:1;fill-rule:evenodd;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4.1;-inkscape-stroke:none"></path><path d="m 430.99999,395 c -0.55228,0 -1,0.44772 -1,1 0,0.55229 0.44772,1 1,1 0.55229,0 1,-0.44771 1,-1 0,-0.55228 -0.44771,-1 -1,-1 z" id="path27023" style="color:#000000;fill:#42b05c;fill-opacity:1;fill-rule:evenodd;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4.1;-inkscape-stroke:none"></path><path d="m 388.99999,438 a 1,1 0 0 0 -1,1 1,1 0 0 0 1,1 1,1 0 0 0 1,-1 1,1 0 0 0 -1,-1 z" id="path27025" style="color:#000000;fill:#000000;fill-rule:evenodd;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4.1;-inkscape-stroke:none"></path><path d="m 396.99999,398 c -0.55228,0 -1,0.44772 -1,1 0,0.55229 0.44772,1 1,1 0.55229,0 1,-0.44771 1,-1 0,-0.55228 -0.44771,-1 -1,-1 z" id="path27027" style="color:#000000;fill:#000000;fill-rule:evenodd;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4.1;-inkscape-stroke:none"></path><g id="g25645" transform="matrix(1,0,0,-1,25.26562,838)"><path d="m 382.73438,407 h 20.99994 v 4 h -20.99994 z" id="path25627" style="fill:#ffa221;fill-opacity:1;fill-rule:evenodd;stroke:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4.1"></path><path d="m 382.73438,407 v 1.90625 a 1.9763446,1.9763446 0 0 0 0.62695,0.11523 h 17.04688 a 1.9763446,1.9763446 0 0 0 1.97656,-1.97656 V 407 Z" id="path25629" style="fill:#ffc343;fill-opacity:1;fill-rule:evenodd;stroke:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4.1"></path><path d="M 393.18627,414.39896 387.23429,421 h 3.50003 v 10 h 5 v -10 h 3.49997 z" id="path25631" style="fill:#0075d3;fill-opacity:1;fill-rule:evenodd;stroke:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4.1"></path><path d="M 389.2461,418.76758 387.23438,421 h 3.5 v 10 h 4.35547 a 6.5794034,10.703726 0 0 0 0.0801,-1.58398 6.5794034,10.703726 0 0 0 -5.92383,-10.64844 z" id="path25633" style="fill:#0588e2;fill-opacity:1;fill-rule:evenodd;stroke:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4.1"></path><path d="m 393.18165,413.39844 c -0.28159,0.001 -0.54964,0.12102 -0.73829,0.33008 l -5.95117,6.60156 c -0.5801,0.64318 -0.12395,1.66951 0.74219,1.66992 h 2.5 c 0,3 0,6 0,9 6e-5,0.55226 0.44774,0.99994 1,1 h 5 c 0.55226,-6e-5 0.99994,-0.44774 1,-1 v -9 h 2.5 c 0.86964,-8.7e-4 1.32387,-1.03463 0.73633,-1.67578 l -2.30469,-2.51758 c -0.37354,-0.40766 -1.00701,-0.4348 -1.41406,-0.0606 -0.40637,0.37335 -0.43349,1.00532 -0.0606,1.41211 L 396.9629,420 h -1.22852 c -0.55226,6e-5 -0.99994,0.44774 -1,1 v 9 h -3 c 0,-3 0,-6 0,-9 -6e-5,-0.55226 -0.44774,-0.99994 -1,-1 h -1.25195 l 3.71093,-4.11328 0.80469,0.8789 c 0.37338,0.40634 1.00534,0.43342 1.41211,0.0605 0.40693,-0.37281 0.4349,-1.0048 0.0625,-1.41211 l -1.54883,-1.6914 c -0.19061,-0.20789 -0.46014,-0.32564 -0.74218,-0.32422 z" id="path25635" style="color:#000000;fill:#000000;fill-rule:evenodd;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4.1;-inkscape-stroke:none"></path><path d="m 382.73438,406 a 1.0001,1.0001 0 0 0 -1,1 v 4 a 1.0001,1.0001 0 0 0 1,1 h 14.97461 a 1,1 0 0 0 1,-1 1,1 0 0 0 -1,-1 h -13.97461 v -2 h 0.9668 a 1,1 0 0 0 1,-1 1,1 0 0 0 -1,-1 z m 6,0 a 1,1 0 0 0 -1,1 1,1 0 0 0 1,1 h 14 v 2 h -1.02344 a 1,1 0 0 0 -1,1 1,1 0 0 0 1,1 h 2.02344 a 1.0001,1.0001 0 0 0 1,-1 v -4 a 1.0001,1.0001 0 0 0 -1,-1 z" id="path25637" style="color:#000000;fill:#000000;fill-rule:evenodd;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4.1;-inkscape-stroke:none"></path></g></g></svg></a>'
                ret += '<a class="icon" onclick="videoFileReplay(this,' + row['id'] + ');" title="' + lang.video_playback + '">';
                ret += '<svg xmlns="http://www.w3.org/2000/svg" shape-rendering="geometricPrecision" text-rendering="geometricPrecision" image-rendering="optimizeQuality" fill-rule="evenodd" clip-rule="evenodd" viewBox="0 0 512 512"><circle fill="#01A437" cx="256" cy="256" r="256"/><path fill="#42C76E" d="M256 9.28c136.12 0 246.46 110.35 246.46 246.46 0 3.22-.08 6.42-.21 9.62C497.2 133.7 388.89 28.51 256 28.51S14.8 133.7 9.75 265.36c-.13-3.2-.21-6.4-.21-9.62C9.54 119.63 119.88 9.28 256 9.28z"/><path fill="#fff" d="M351.74 275.46c17.09-11.03 17.04-23.32 0-33.09l-133.52-97.7c-13.92-8.73-28.44-3.6-28.05 14.57l.54 191.94c1.2 19.71 12.44 25.12 29.04 16l131.99-91.72z"/></svg></a>'
                return ret;
            } else if (name == 'svr') {
                ret = row.svr;
            } else if (name == 'devIdno') {
                ret = row.devIdno;
            } else if (name == 'len') {
                ret = row.len;
            } else if (name == 'chnMask') {
                ret = row.chnMask;
            } else if (name == 'end') {
                ret = row.end;
            } else if (name == 'beg') {
                ret = row.beg;
            }
            return ret;
        }

        function userLogin1() {
            if (isLanding == true) {
                return;
            }
            var account = $.trim($('.account').val());
            if (account == '') {
                $('.account').focus();
                return;
            }
            var password = $.trim($('.password').val());
            if (password == '') {
                $('.password').focus();
                return;
            }
            ip_ = $('.ip').val();
            if (ip_ == '') {
                $('.ip').focus();
                return;
            }
            port_ = $('.port').val();
            if (port_ == '') {
                $('.port').focus();
                return;
            }
            isLanding = true;
            $("#userLoginBtn").text(lang.login);
            var param = [];
            param.push({name: 'account', value: account});
            param.push({name: 'password', value: password});

            $.ajax({
                type: 'POST',
                url: '//' + domainName + '/StandardApiAction_login.action',
                data: param,
                cache: false,
                success: function (data,textStatus, xhr) {
                    data = getdecryptRes(data,xhr)
                    if (data.result == 0) {
                        jsion = data.jsession;
                        myUserRole.setPrivileges(data.pri);
                        initPlayerExample();
                        var param = [];
                        param.push({name: 'jsession', value: jsion});
                        $.ajax({
                            type: 'POST',
                            url: '//' + domainName + '/StandardApiAction_queryUserVehicle.action',
                            data: param,
                            cache: false,
                            success: function (data,textStatus, xhr) {
                                data = getdecryptRes(data,xhr)
                                $("#selnumber option").remove();
                                if (data.result == 0) {
                                    mapVehicleInfo.clear();
                                    $("#selnumber").empty();
                                    if (data.vehicles && data.vehicles.length > 0) {
                                        loadMapVehicleInfo(data.vehicles);
                                        mapVehicleInfo.each(function (key, value) {
                                            $("#selnumber").append("<option value=" + value.device.idno + ">" + key + "</option>");
                                        });
                                    }
                                }
                                isLanding = false;
                                $("#userLoginBtn").text(lang.login);
                            }, error: function () {
                                alert(lang.GetnumberPrompt);
                                isLanding = false;
                                $("#userLoginBtn").text(lang.login);
                            }
                        });
                    }else{
                        alert(lang.loginError);
                        isLanding = false;
                        $("#userLoginBtn").text(lang.login);
                    }
                }, error: function () {
                    alert(lang.loginError);
                    isLanding = false;
                    $("#userLoginBtn").text(lang.login);
                }
            });
        }

        function userLogin() {
            if (isLanding == true) {
                return;
            }
          
            isLanding = true;
            jsion = "<?php echo $jsession; ?>";
            let pri = "<?php echo $pri; ?>";
            myUserRole.setPrivileges(pri);
            initPlayerExample();
            {
		let cds = <?php echo  json_encode($cds); ?>;
                data = <?php echo  json_encode($vehicle_list); ?>;

		let vehicleList = data.vehicles.filter(vehicle => 
                vehicle.dl.some(dlEntry => cds.includes(dlEntry.id))
                );
                $("#selnumber option").remove();
                if (data.result == 0) {
                    mapVehicleInfo.clear();
                    $("#selnumber").empty();
                    if (vehicleList && vehicleList.length > 0) {
                        loadMapVehicleInfo(vehicleList);
                        mapVehicleInfo.each(function (key, value) {
                            $("#selnumber").append("<option value=" + value.device.idno + ">" + key + "</option>");
                        });
                    }
                }
                isLanding = false;
                $("#userLoginBtn").text(lang.login);
            }

        }

        function loadMapVehicleInfo(vehicles) {
            for (var i = 0; i < vehicles.length; i++) {
                var vehicle = {};
                vehicle.idno = vehicles[i].nm.toString();
                if (vehicles[i].dl != null) {
                    if (vehicles[i].dl.length == 1) {
                        var device_ = vehicles[i].dl[0];
                        var device = {};
                        device.idno = device_.id.toString();
                        var channels = [];
                        if (device_.cn != null && device_.cn != "") {
                            var chns = device_.cn.split(",");
                            for (var j = 0; j < chns.length; j++) {
                                var chn = {};
                                chn.index = j;
                                chn.name = chns[j];
                                channels.push(chn);
                            }
                        }
                        device.channels = channels;
                        vehicle.device = device;
                        var mod = Number(device_.md);//.toString(2);
                        if((mod >> 0) & 1 > 0 && channels.length > 0){
                            mapVehicleInfo.put(vehicle.idno, vehicle);
                        }
                    } else {
                        var index = 0;
                        for (var j = 0; j < vehicles[i].dl.length; j++) {
                            var device_ = vehicles[i].dl[j];
                            if ((device_.md >> 0) & 1 > 0) {
                                var device = {};
                                device.idno = device_.id.toString();
                                var channels = [];
                                if (device_.cn != null && device_.cn != "") {
                                    var chns = device_.cn.split(",");
                                    for (var k = 0; k < chns.length; k++) {
                                        var chn = {};
                                        chn.index = index;
                                        chn.name = chns[k];
                                        channels.push(chn);

                                        index++;
                                    }
                                }
                                device.channels = channels;
                                vehicle.device = device;
                                if(channels.length > 0){
                                    mapVehicleInfo.put(vehicle.idno, vehicle);
                                }
                            } else {
                                if (device_.cn != null && device_.cn != "") {
                                    var chns = device_.cn.split(",");
                                    index = chns.length;
                                }
                            }
                        }
                    }
                }
            }
        }

        function ClearVideoList(){
            videoFileList.clear();
        }

        function FtpQuery(){
            if (jsion == "") {
                alert(lang.NotloggedPrompt);
                return;
            }
            const $selected = $("#selnumber option:selected");
            searchVehicle = $selected.text();
            const id = $selected.val();
            const beginStr = $("#startTime").val();
            const endStr = $("#endTime").val();

            let url =``;
            url = url +"//"+domainName+"/StandardApiAction_queryDownLoadReplayEx.action?jsession="+jsion+"&devIdno="+id+"&status=3&begintime="+beginStr+"&endtime="+endStr+"&currentPage=1&pageRecords=100"
            console.log(url)
            $.ajax({
                type: "GET",
                url: url,
                success: function (data, textStatus, xhr) {
                    if (data.result === 0) {
                        addVideoFileInfo(data);
                    } else {
                        alert("Error: Please check console log for more info");
                    }
                    console.log("debug", data, textStatus, xhr);
                },
                error: function (error) {
                    console.log("Error:", error);
                    alert("Error: Please check console log for more info");
                }
            });
            
        }

        

        function FtpRequest() {
            if (!jsion) {
                alert(lang.NotloggedPrompt);
                return;
            }

            // Cache selected vehicle element
            const $selected = $("#selnumber option:selected");
            searchVehicle = $selected.text();
            const id = $selected.val();

            // Retrieve start and end time values
            const beginStr = $("#startTime").val();
            const endStr = $("#endTime").val();

            const sDate = new Date(beginStr);
            const eDate = new Date(endStr);
            if (sDate > eDate) {
                alert(lang.starttime_more_endtime);
                return;
            }

            // Helper: Parse date/time string and return an object with year, month, day, and seconds
            function parseDateTime(dateTimeStr) {
                // Normalize the date string for cross-browser compatibility
                const normalizedStr = dateTimeStr.replace(/-/g, "/");
                const [datePart, timePart] = normalizedStr.split(" ");
                if (!datePart || !timePart) return null;
                
                const dateComponents = datePart.split("/");
                const timeComponents = timePart.split(":");
                if (dateComponents.length !== 3 || timeComponents.length !== 3) return null;
                
                return {
                    year: dateComponents[0],
                    month: dateComponents[1],
                    day: dateComponents[2],
                    sec: shortHour2Second(timePart)
                };
            }

            const startParsed = parseDateTime(beginStr);
            if (!startParsed) {
                alert("Invalid start date / time");
                return;
            }
            const endParsed = parseDateTime(endStr);
            if (!endParsed) {
                alert("Invalid end date / time");
                return;
            }

            const url = `//${domainName}/StandardApiAction_ftpUpload.action`;
            const requestData = {
                jsession: jsion,
                DevIDNO: id,
                CHN: 0,
                BEGYEAR: startParsed.year,
                BEGMON: startParsed.month,
                BEGDAY: startParsed.day,
                BEGSEC: startParsed.sec,
                ENDYEAR: endParsed.year,
                ENDMON: endParsed.month,
                ENDDAY: endParsed.day,
                ENDSEC: endParsed.sec,
                ARM1: 0,
                ARM2: 0,
                RES: 0,
                STREAM: 1,
                STORE: 0,
                NETMASK: 4
            };

            console.log("url", url);
            $.ajax({
                type: "POST",
                url: url,
                data: requestData,
                success: function (data, textStatus, xhr) {
                    if (data.result === 0) {
                        alert("Success: FTP upload request sent successfully");
                    } else {
                        alert("Error: Please check console log for more info");
                    }
                    console.log("debug", data, textStatus, xhr);
                },
                error: function (error) {
                    console.log("Error:", error);
                    alert("Error: Please check console log for more info");
                }
            });
        }


        function Search() {
            FtpQuery();
            return
            videoFileList.clear();
            if (jsion == "") {
                alert(lang.NotloggedPrompt);
                return;
            }
            if (IsSearching == true) {
                return;
            }
            IsSearching = true;
            $("#search").text(lang.searching);
            var number = $("#selnumber  option:selected").text();
            searchVehicle = number;
            var id = $("#selnumber  option:selected").val();
            var json = $("#namejsession").text();
            var radioFileLocation = $('input:radio[name="FileLocation"]:checked').val();
            var radioFileType = $('input:radio[name="FileType"]:checked').val();
            var radioVideoType = $('input:radio[name="VideoType"]:checked').val();

            var y = null;
            var m = null;
            var d = null;
            var ye = null;
            var me = null;
            var de = null;
            var beg = null;
            var end = null;
            if(crossDay == 1){
                var beginstr = $("#startTime").val();
                var endstr = $("#endTime").val();
                var sDate = new Date(beginstr);
                var eDate = new Date(endstr);
                if(sDate > eDate){
                    alert(lang.starttime_more_endtime)
                    IsSearching = false;
                    $("#search").text(lang.search);
                    return;
                }
                var str = beginstr.split(" ");
                var begstr = beginstr.replace(/-/g, "/");
                var begdate = new Date(begstr);
                var date = str[0].split("-");
                y = date[0].toString(); // year
                m = date[1].toString(); // month
                d = date[2].toString(); // date
                beg = shortHour2Second(str[1].toString());

                endstr = endstr.split(" ");
                var endDate =endstr[0].split("-")
                ye = endDate[0].toString(); // yeare
                me = endDate[1].toString(); // monthe
                de = endDate[2].toString(); // date
                end = shortHour2Second(endstr[1].toString());
            }else{
                var beginstr = $("#startTime").val();
                var str = beginstr.split("-");
                y = str[0].toString(); // year
                m = str[1].toString(); // month
                d = str[2].toString(); // date
                beg = shortHour2Second($("#endTime").val());
                end = shortHour2Second($("#exTime").val());
            }

            var param = [];
            param.push({name: 'MediaType', value: 2});
            param.push({name: 'DownType', value: 2});
            param.push({name: 'jsession', value: jsion});
            if (radioFileLocation != 1) {
                param.push({name: 'DevIDNO', value: number.toString()});
            } else {
                param.push({name: 'DevIDNO', value: id.toString()});
            }
            param.push({name: 'Location', value: Number(radioFileLocation)});

            var obj = {};
            if (radioFileLocation != 1) {
                obj.DevIDNO = number.toString();
            } else {
                obj.DevIDNO = id.toString();
            }
            //1 represents the device, 2 represents the storage server, and 4 represents the download server.
            obj.LOC = Number(radioFileLocation);
            obj.CHN = -1;
            obj.YEAR = Number(y);
            obj.MON = Number(m);
            obj.DAY = Number(d);
            obj.RECTYPE = Number(radioVideoType);
            obj.FILEATTR = Number(radioFileType);
            obj.BEG = beg;
            obj.END = end;
            obj.jsession = jsion;
            obj.DownType = 2;
            obj.ARM1 = 0;
            obj.ARM2 = 0;
            obj.RES = 0;
            obj.STREAM = -1;
            obj.STORE = 0;


            var url = '//' + domainName + '/StandardApiAction_getVideoFileInfo.action';
            if(crossDay == 1){
                obj.YEARE = Number(ye);
                obj.MONE = Number(me);
                obj.DAYE = Number(de);
                url = '//' + domainName + '/StandardApiAction_getVideoHistoryFile.action';
            }
            $.ajax({
                type: 'POST',
                url: url,
                data: obj,
                cache: false,
                success: function (data,textStatus, xhr) {
                    data = getdecryptRes(data,xhr)
                    $("#videoFileTable tr").remove();
                    if (data.result == 0) {
                        // if(data.files.length < 1){
                        //     alert(lang.VideoQueryPrompt);
                        // }else{
                            addVideoFileInfo(data);
                        // }
                    } else {
                        switch (data.result) {
                            case 5:
                                $.dialog.tipDanger(lang.errLogin_Session);
                                break;
                            case 6:
                                $.dialog.tipDanger(lang.errException);
                                break;
                            case 7:
                                $.dialog.tipDanger(lang.errRequireParam);
                                break;
                            case 8:
                                $.dialog.tipDanger(lang.vehicleNotOperate);
                                break;
                            case 13:
                                $.dialog.tipDanger(lang.no_permission);
                                break;
                            case 18:
                                $.dialog.tipDanger(lang.vehicle_not_exist_not_auth);
                                break;
                            case 19:
                                $.dialog.tipDanger(lang.errDeviceNotExsist);
                                break;
                            case 32:
                                $.dialog.tipDanger(lang.video_not_online);
                                break;
                            case 97:
                                $.dialog.tipDanger(lang.unStatus);
                                break;
                            case 98:
                                $.dialog.tipDanger(lang.service_period);
                                break;
                            default:
                                $.dialog.tipDanger(data.message);
                                break;
                        }
                        // alert(lang.VideoQueryPrompt);
                    }
                    IsSearching = false;
                    $("#search").text(lang.search);
                }
            });


            /*
                $.ajax({
                    type:'POST',
                    url:'//'+ ip_ +':' + port_+'/3/1/callback=getData',
                    data:param,
                    cache:false,
                    dataType: 'jsonp',
                    success: getData = function (data) {
                        if(data.result == 0){
                            serverIp=data.server.clientIp;
                            serverPort=data.server.clientPort;
                            var param2 = [];
                            param2.push({name: 'DownType', value: 2});
                             param2.push({name: 'jsession', value: Number(json)});
                             if(radioFileLocation != 1) {
                                 param2.push({name: 'DevIDNO', value: number.toString()});
                             }else {
                                 param2.push({name: 'DevIDNO', value: id.toString()});
                             }
                             param2.push({name: 'LOC', value: Number(radioFileLocation)});
                             param2.push({name: 'CHN', value: -1});
                             param2.push({name: 'YEAR', value: Number(y)});
                             param2.push({name: 'MON', value: Number(m)});
                             param2.push({name: 'DAY', value: Number(d)});
                             param2.push({name: 'RECTYPE', value: Number(radioVideoType)});
                             param2.push({name: 'FILEATTR', value: Number(radioFileType)});
                             param2.push({name: 'BEG', value:beg});
                             param2.push({name: 'END', value:end});
                             $.ajax({
                                type:'POST',
                                url:'//'+serverIp+':'+serverPort+'/3/5/callback=getData',
                                data:param2,
                                cache:false,
                                dataType: 'jsonp',
                                success: getData = function (json) {
                                    $("#videoFileTable tr").remove();
                                    if(data.result == 0){
                                        addVideoFileInfo(json);
                                    }else{
                                        alert(lang.VideoQueryPrompt);
                                    }
                                    IsSearching=false;
                                    $("#search").text(lang.search);
                                }
                             });
                        }else{
                            alert(lang.ServerQueryPrompt);
                            IsSearching=false;
                            $("#search").text(lang.search);
                        }
                    }
                });	 */
        }

        //0:0 converted to 0
        function shortHour2Second(hour) {
            var temp = hour.split(":");
            if (temp.length == 2) {
                return parseIntDecimal(temp[0]) * 3600 + parseIntDecimal(temp[1]) * 60;
            } else if (temp.length == 3) {
                return parseIntDecimal(temp[0]) * 3600 + parseIntDecimal(temp[1]) * 60 + parseIntDecimal(temp[2]);
            } else {
                return 0;
            }
        }



        function addVideoFileInfo(json) {
            //Adding list to search
            var files = new Array();
            if (json.files != null && json.files.length > 0) {
                //File list sort, according to the start time from small to large
                json.files.sort(function (a, b) {
                    return a.beg > b.beg ? 1 : -1
                });
                //Add to video file list
                var index = 0;
                for (var i = 0; i < json.files.length; i++) {
                    //The vehicle does not contain this channel, then the information is removed.
                    var isAdd = true;
                    //Can download the task of the whole file download
                    //Multiple channel files, can only be downloaded
                    //ChnMask>0 according to the position to determine the number of channels CHN is also a number of channels
                    if (json.files[i].chnMask > 0) {
                        var maskChnArray = getMaskChnArray(json.files[i].chnMask);
                        json.files[i].maskChns = maskChnArray.maskChns;
                        json.files[i].chnName = maskChnArray.maskChnNames;
                        json.files[i].isSegment = true;//Whether can only be downloaded
                        if (json.files[i].maskChns == '') {
                            isAdd = false;
                        }
                    } else {
                        //chn == 98 All channels
                        if (json.files[i].chn == 98) {
                            json.files[i].chnName = getAllChnName();
                            json.files[i].isSegment = true;
                            if (json.files[i].chnName == '') {
                                isAdd = false;
                            }
                        } else {
                            json.files[i].chnName = getChnName(json.files[i].chn);
                            if (json.files[i].chnName == '') {
                                isAdd = false;
                            }
                        }
                    }
                    if (isAdd) {
                        json.files[i].id = index;
                        videoFileList.put(index, json.files[i]);
                        if (json.files[i].type == 1) {
                            json.files[i].color = "#FF0000";
                        }
                        json.files[i].vehiIdno = $("#selnumber  option:selected").text();
                        //Handling files across days
                        processFileDay(json.files[i]);
                        json.files[i].isDirect = true;
                        files.push(json.files[i]);
                        index++;
                    }
                }
                if (files.length > 0) {
                    //Add to video file list
                    $("#videoFileTable").flexAppendRowJson(files, false);
                    $.dialog.tipSuccess(parent.lang.searchCompleted, 1);
                    $(".flexigrid div.bDiv").css('max-height', '264px');
                }
            }
            if (json.result == 0) {
                if (files.length <= 0) {
                    $.dialog.tipWarning(lang.NullVideoFileInfo, 2);
                }
            } else {
                var mess = '';
                if ((typeof showDialogErrorMessage) == 'function') {
                    mess = showDialogErrorMessage(json.result, json.cmsserver);
                }
                if (mess != null && mess == '') {
                    $.dialog.tipWarning(lang.errorGetVideoFile, 2);
                }
            }

        }

        //Gets the name of the channel, comma separated
        function getMaskChnArray(chnMask) {
            var chns = [];
            var chnNames = [];
            var vehicle = mapVehicleInfo.get(searchVehicle.toString());
            if (vehicle != null && vehicle.device != null && vehicle.device.channels && vehicle.device.channels.length > 0) {
                var channels = vehicle.device.channels;
                for (var i = 0; i < channels.length; i++) {
                    if ((chnMask >> channels[i].index) & 1 > 0) {
                        chns.push(channels[i].index);
                        chnNames.push(channels[i].name);
                    }
                }
            }
            var data = {};
            data.maskChns = chns.toString();
            data.maskChnNames = chnNames.toString();
            return data;
        }

        //Get the all channel name
        function getAllChnName() {
            var chnNames = [];
            var vehicle = mapVehicleInfo.get(searchVehicle.toString());
            if (vehicle != null && vehicle.device != null && vehicle.device.channels && vehicle.device.channels.length > 0) {
                var channels = vehicle.device.channels;
                for (var i = 0; i < channels.length; i++) {
                    chnNames.push(channels[i].name);
                }
            }
            return chnNames.toString();
        }

        //Get the channel name
        function getChnName(chn) {
            var vehicle = mapVehicleInfo.get(searchVehicle.toString());
            if (vehicle != null && vehicle.device != null && vehicle.device.channels && vehicle.device.channels.length > 0) {
                var channels = vehicle.device.channels;
                for (var i = 0; i < channels.length; i++) {
                    if (chn == channels[i].index) {
                        return channels[i].name;
                    }
                }
            }
            return 'CH' + (Number(chn) + 1);
        }

        //Deal with file information, cross day
        function processFileDay(data) {
            //File across the day before the day of the day or the day after the time
            //To judge the day before the cross, if the date is the day before
            var beginstr = $("#startTime").val();
            beginstr = beginstr.replace(/-/g, "/");
            var begindate = new Date(beginstr);
            data.yearMonthDay = dateFormat2DateString(begindate);
            var day = Number(data.yearMonthDay.substring(8, 10));
            var fileDay = Number(data.day);
            var fileRealDate = getFileTime(data.year, data.mon, data.day);
            if (!dateCompareStrDateRange(data.yearMonthDay, fileRealDate, 1) || !dateCompareStrDateRange(fileRealDate, data.yearMonthDay, 1)) {
                loadTimeLine = false;
                data.relBeg = data.beg;
                data.relEnd = data.end;
                data.beginDate = fileRealDate + ' ' + second2ShortHourEx(data.beg);
                data.endDate = fileRealDate + ' ' + second2ShortHourEx(data.end);
                data.timeTitle = data.beginDate + ' - ' + second2ShortHourEx(data.end);
            } else {
                loadTimeLine = true;
                //The day before
                if (fileDay < day || (day == 1 && fileDay <= 31 && fileDay >= 28)) {
                    data.relBeg = 0;
                    data.relEnd = Number(data.end) - 86400;
                    data.beginDate = fileRealDate + ' ' + second2ShortHourEx(data.beg);
                    data.endDate = dateFormat2DateString(dateGetNextMulDay(dateStrLongTime2Date(data.beginDate), 1)) + ' ' + second2ShortHourEx(data.relEnd);
                    data.timeTitle = data.beginDate + ' - ' + data.endDate;
                } else if (fileDay == day && Number(data.end) > 86400) {
                    //ay after day
                    data.relBeg = data.beg;
                    data.relEnd = 86399;
                    data.beginDate = fileRealDate + ' ' + second2ShortHourEx(data.beg);
                    data.endDate = dateFormat2DateString(dateGetNextMulDay(dateStrLongTime2Date(data.beginDate), 1)) + ' ' + second2ShortHourEx(Number(data.end) - 86400);
                    data.timeTitle = data.beginDate + ' - ' + data.endDate;
                } else {
                    data.relBeg = data.beg;
                    data.relEnd = data.end;
                    data.beginDate = fileRealDate + ' ' + second2ShortHourEx(data.beg);
                    data.endDate = fileRealDate + ' ' + second2ShortHourEx(data.end);
                    data.timeTitle = data.beginDate + ' - ' + second2ShortHourEx(data.end);
                }
            }
        }

        //Convert to 2012-05-11 format, short time format
        function dateFormat2DateString(date) {
            var y = date.getFullYear(), m = date.getMonth(), d = date.getDate();
            var str = y + "-" + dateFormatValue(m + 1) + "-" + dateFormatValue(d);
            return str;
        }

        //get file time
        function getFileTime(year, mon, day) {
            var retTime = "";
            retTime += Number(year) + 2000;
            retTime += "-";
            if (mon < 10) {
                retTime += "0" + mon;
            } else {
                retTime += mon;
            }
            retTime += "-";
            if (day < 10) {
                retTime += "0" + day;
            } else {
                retTime += day;
            }
            return retTime;
        }

        //Breakpoint download video file
        function downloadVideoFile(id) {
            if (id != null) {
                if (!confirm(lang.allowed)) {
                    return;
                }
                //Search and download the video file server information, after the successful download video file information
                QueryServer(id, 'down');
            }
        }

        //Query related server information
        function QueryServer(id, type) {
            var fileInfo = videoFileList.get(Number(id));
            if (type == 'down') {
                doDownloadVideoFileInfo(fileInfo);
            } else if (type == 'replay') {
                doReplayVehicleServer(id, fileInfo);
            }
        }

        //Download video file information
        function doDownloadVideoFileInfo(fileInfo) {
            var paths = fileInfo.file.split('/');
            if (paths.length == 1) {
                paths = fileInfo.file.split('\\');
            }
            var downUrl = analyFileInfoAddress(fileInfo, fileInfo.DownUrl);
            if (paths.length > 1) {
                var fileExtension = paths[paths.length - 1].split('.').pop(); //File extension
                if (fileExtension == 'picfile') {
                    var newPath = paths[paths.length - 1].replace('picfile', 'jpg');
                    downUrl += newPath;
                } else {
                    downUrl += paths[paths.length - 1];
                }
            }
            downloadFile(encodeURI(downUrl));
            return;
        }

        //download file
        function downloadFile1(url) {
            try {
                //Prevent garbled characters at noon
//	        url = decodeURIComponent(url);
                var elemIF = document.createElement("iframe");
                elemIF.src = url;
                elemIF.style.display = "none";
                document.body.appendChild(elemIF);
            } catch (e) {

            }
        }
	
	function downloadFile(url) {
	url = url.replace("140.245.6.5","cam.paizogps.in")
    fetch(url)
        .then(response => response.blob())
        .then(blob => {
            const blobUrl = window.URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = blobUrl;
            // Optionally, provide a default file name
            link.download = url.split('/').pop();
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            window.URL.revokeObjectURL(blobUrl);
        })
        .catch(error => console.error('Download error:', error));
	}

        function analyFileInfoAddress(fileInfo, url) {
            var address = url.replace(fileInfo.clientIp, analyFileInfoServerIP(fileInfo));
            return address;
        }

        function initPlayerExample() {
            var strLang = getUrlParameter('lang');
            //Video parameter
            var params = {
                allowFullscreen: "true",
                allowScriptAccess: "always",
                bgcolor: "#FFFFFF",
                wmode: "transparent",
                isVodMode: true,
                hideSoundBtn:!myUserRole.isVideoSoundSupport(),
                lang: strLang == "" ? "en" : strLang
            };
            //Initialization is not completed
            isInitFinished = false;
            //Video card width
            var width = "400";
            //Video card hight
            var hieght = "400";
            //Initialize flash
            // swfobject.embedSWF("player.swf", "cmsv6flash", width, hieght, "11.0.0", null, null, params, null);
            ttxVideoAll.init("cmsv6flash", width, hieght, params);
            setTimeout(initFlash, 50);
        }

        function initFlash() {
            if (typeof swfobject == "undefined" || swfobject.getObjectById("cmsv6flash") == null ||
                typeof swfobject.getObjectById("cmsv6flash").setWindowNum == "undefined") {
                setTimeout(initFlash, 50);
            } else {
                //Initialize plugin language
                var language = $.trim($('.languagePath').val());
                if (!language) {
                    $('.languagePath').focus();
                    return;
                }
                swfobject.getObjectById("cmsv6flash").setLanguage(language);
                //Create all windows
                swfobject.getObjectById("cmsv6flash").setWindowNum(36);
                //Configuring the current window number
                var windowNum = 1;
                swfobject.getObjectById("cmsv6flash").setWindowNum(windowNum);
                swfobject.getObjectById("cmsv6flash").setServerInfo(serverIp, serverPort);
                isInitFinished = true;
            }
        }

        function getComServerIp(lstSvrIp) {
            if (lstSvrIp != null && lstSvrIp.length > 0) {
                var host = domainName.split(':');
                if (host.length >= 1) {
                    var addr = host[0];
                    if (addr == 'localhost') {
                        return "127.0.0.1";
                    }
                    for (var i = 0; i < lstSvrIp.length; ++i) {
                        if (addr == lstSvrIp[i]) {
                            return lstSvrIp[i];
                        }
                    }
                }
                return lstSvrIp[0];
            }
            return "127.0.0.1";
        }

        function analyFileInfoServerIP(fileInfo) {
            var lstSvrIp = [];
            lstSvrIp.push(fileInfo.clientIp);
            lstSvrIp.push(fileInfo.lanip);
            lstSvrIp.push(fileInfo.clientIp2);
            lstSvrIp.push(fileInfo.clientIp3);
            return getComServerIp(lstSvrIp);
        }

        // Replay
        function videoFileReplay(obj, id) {
            QueryServer(id, 'replay');
        }

        function doReplayVehicleServer(id, fileInfo) {
            var chnCount = $('#playbackChannel').val();
            if(!fileInfo.mulPlay && chnCount && chnCount.split(',').length > 1){
                $.dialog.tipWarning(lang.current_device_does_not_support, 2);
                return;
            }
            var beg = Number($("#row" + id + " .beg div").text());
            var eng = Number($("#row" + id + " .end div").text());
            var DValue = 0;
            var chnStr = $("#row" + id + " .vehiChn div").text();
            var chn = chnStr.substring(2, chnStr.length) - 1;
            var filename = $("#row" + id + " .file div").text();
            var lastindex = filename.lastIndexOf('/');
            var title = filename.substring(lastindex + 1, filename.length);
            doReplayVehicleServerEx(fileInfo, chn, 0, title);
        }

        //Video playback file information
        function doReplayVehicleServerEx(fileInfo, chn, begTime, title) {
            //Video playback
            var PlaybackUrl_ = fileInfo.PlaybackUrl;
            if (fileInfo.PlaybackUrlWs && LS.get("playerType") != 'flash' && !isBrowseIE()) {
                PlaybackUrl_ = fileInfo.PlaybackUrlWs;
            }
           /* if (fileInfo.chnMask > 0 || fileInfo.chn == 98) {//Multiple channels may need to replace data
                PlaybackUrl_ = PlaybackUrl_.replace('PLAYCHN=0', 'PLAYCHN=' + chn);
            }*/
            var playUrl = analyFileInfoAddress(fileInfo, PlaybackUrl_);
			playUrl = playUrl.replaceAll(/140.245.6.5/g,"cam.paizogps.in");
            startPlayback(playUrl, title);
        }

        function setWindowTitle(title) {
            if (!isInitFinished) {
                return;
            } else {
                //window index
                var index = 0;
                swfobject.getObjectById("cmsv6flash").setVideoInfo(index, title);
            }
        }

        function startPlayback(url, title) {
            if (!isInitFinished) {
                return;
            } else {
                setWindowTitle(title);
                //Window index
                //var index = 0;
                //Stop before playback
                //swfobject.getObjectById('cmsv6flash').stopVideo(index);
                //Start playback
                //swfobject.getObjectById("cmsv6flash").startVod(index, url);

                var index = $('#playbackChannel').val()

                swfobject.getObjectById("cmsv6flash").stopVodM();
                swfobject.getObjectById("cmsv6flash").startVodM(url, index);
            }
        }

        function stopPlayback() {
            if (!isInitFinished) {
                return;
            } else {
                //Window index
                //var index = 0;
                //swfobject.getObjectById("cmsv6flash").stopVideo(index);
                swfobject.getObjectById("cmsv6flash").stopVodM();
            }
        }

        function setVideoLanguage() {
            if (!isInitFinished) {
                return;
            } else {
                //Language file
                var language = $.trim($('.languagePath').val());
                if (!language) {
                    $('.languagePath').focus();
                    return;
                }
                swfobject.getObjectById("cmsv6flash").setLanguage(language);
            }
        }
    </script>
</head>
<body >
<div class="container">
<div class="top">
<div id="flashExample" style="overflow: hidden; height: 500px;">
    <div id="cmsv6flash"></div>
    <a class="title" id="videoLangTitle" style="display: none;">：</a>
    <select style="width: 140px;display: none;" class="languagePath">
        <option>en.xml</option>
        <option>cn.xml</option>
    </select>
    <a style="margin-left: 20px;display: none;" class="button button-primary button-rounded button-small settings"
       onclick="setVideoLanguage()"></a>
</div>
<div id="operateExample" style="position:absolute;width: 800px;">
<!-- User login starts -->
    <div class="userLogin"  style="display:none;">
        <p id="getJsessionTitle"></p>
        <div class="player-params">
            <table class="player-params" style="text-align: right;">
                <tbody>
                <tr>
                    <td>
                        <a class="title" id="IPTitle">IP：</a>
                    </td>
                    <td>
                        <input style="width: 150px;" class="ip" value="140.245.6.5">
                    </td>
                    <td>
                        <a style="padding-left: 20px;" class="title" id="portTitle">：</a>
                    </td>
                    <td>
                        <input style="width: 150px;" class="port" value="6605">
                    </td>
                </tr>
                <tr>
                    <td>
                        <a class="title" id="accountTitle">：</a>
                    </td>
                    <td>
                        <input style="width: 150px;" class="account" value="admin">
                    </td>
                    <td>
                        <a style="padding-left: 20px;" class="title" id="passwordTitle">：</a>
                    </td>
                    <td>
                        <input type="password" style="width: 150px;" class="password" value="Paizo@123">
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="player-param">
                <a id="userLoginBtn" class="button button-primary button-rounded button-small"></a>
            </div>
        </div>
    </div>

<!-- User login completed -->

<!-- Recording query initialization starts -->
    <div class="videoInit">
        <p id="Condition"></p>
        <div class="player-params" style="width: 800px;">
            <div class="player-param">
                <a class="title" id="NumberPlates">：</a>
                <select id="selnumber" style="width: 140px;">
<!-- <option value='0'>Please select</option> -->
                </select>
                <br/><br/>
                <input id="namejsession" style="display:none;">
                <div id="datepicker" style="display: none;"></div>
                <span id="labelStartTime" style="padding-left: 0px;">：</span>
                <input id="startTime" class="Wdate" type="text" style="width: 140px;"  size="15">
                <span id="labelEndTime" style="padding-left: 20px;">：</span>
                <input id="endTime" class="Wdate" type="text" style="width: 140px;"  size="15">
                <span id="labelExTime" style="padding-left: 20px;">：</span>
                <input id="exTime" class="Wdate" type="text" style="width: 140px;" readonly size="15">
                <div id="fileLocation" class="hf_box" style="line-height:30px; ">
                    <div style="display:none;">
                        <a id="filelocation" class="title"></a>
                        <label>
                            <input id="wjwz-device" type="radio" value="1" name="FileLocation">
                            <span id="spanDevice" class="title"></span>
                        </label>
                        <label>
                            <input id="wjwz-storage" type="radio" checked value="2" name="FileLocation">
                            <span id="spanStorageServer" class="title"></span>
                        </label>
                        <label>
                            <input id="wjwz-download" type="radio" value="4" name="FileLocation">
                            <span id="spanDownloadServer" class="title"></span>
                        </label>
                    </div>
                    <div>
                        <a id="filetype" class="title"></a>
                        <label>
                            <input id="wjlx-video" type="radio" checked value="2" name="FileType">
                            <span id="spanVideoType"></span>
                        </label>
                    </div>
                    <div>
                        <a id="VideoType">：</a>
                        <label>
                            <input id="lxlx-normal" type="radio" value="0" name="VideoType">
                            <span id="spanVideoNormal" class="title"></span>
                        </label>
                        <label>
                            <input id="lxlx-alarm" type="radio" value="1" name="VideoType">
                            <span id="spanVideoAlarm" class="title"></span>
                        </label>
                        <label>
                            <input id="lxlx-all" type="radio" checked value="-1" name="VideoType">
                            <span id="spanVideoAll" class="title"></span>
                        </label>
                    </div>
                </div>
                <a id="search" class="button button-primary button-rounded button-small settings"
                   onclick="Search()"></a>

                <a id="ftpRequest" class="button button-primary button-rounded button-small settings"
                   onclick="FtpRequest()">Request Video From Terminal</a>

                   <a id="clearVideoList" class="button button-primary button-rounded button-small settings"
                   onclick="ClearVideoList()" style="display:none">Clear Video List</a>
                   <button id="openModalBtn" class="button button-primary button-rounded button-small settings">Requested File Video Status</button>
            </div>
        </div>
    </div>

<!-- Recording query initialization ends -->

<!-- Video query -->
    <div class="playback" style="margin: 0;">

        <p id="playbackTitle"></p>

<!--Multi-channel playback -->
        <div id="realTimePlayer" style="padding-left: 20px">

            <div class="player-param" style="line-height: 30px;">
                <a class="title" id="playbackChannelTitle">：</a>
                <input style="width: 100px;" value="0" id="playbackChannel" disabled>
            </div>
            <div class="player-param" style="line-height: 30px;">
                <div id="playbackRadio">
                    <input type="radio" id="playbackType1" name="playbackType" value="1" checked><span
                        id="playbackTypeSpan1"></span>
                    <input type="radio" id="playbackType2" name="playbackType" value="2"><span
                        id="playbackTypeSpan2"></span>
                    <input type="radio" id="playbackType3" name="playbackType" value="3"><span
                        id="playbackTypeSpan3"></span>
                </div>
            </div>
            <div class="player-param" id="playbackChannel2" style="display: none; line-height: 30px;">
                <a id="playVideoBtn" class="button button-primary button-rounded button-small"></a>
            </div>
<!-- Multi-channel simultaneous playback -->
            <div class="player-param" id="playbackChannel3" style="display: none;">

            </div>

        </div>


        <div class="gps_box">

            <ul style="list-style-type:none;margin: 0;padding:0px;">
                <li id="ftpvideoFile">
                    <table id="ftpvideoFileTable"></table>
                </li>
            </ul>
        </div>

        <div class="player-params">
            <div class="player-param">
                <a id="queryresults" class="title windowIndex">：</a>
            </div>
            <div class="player-param" style="margin: 0;width: 800px;max-height:300px;overflow: auto;">
                <textarea id="videosearch" style="width: 800px;height:250px; display: none;"
                          class="playbackUrl"></textarea>
                <div class="flexigrid" style="margin: 0;overflow: visible;">
                    <div class="d_table map_action">
                        <div class="map_drag_box">
                            <i class="icon icon_drag"></i>
                        </div>
                        <div class="gps_box">
<!-- Put the report here -->
                            <ul style="list-style-type:none;margin: 0;padding:0px;">
                                <!-- <li id="videoTime" class="active"></li> -->
                                <li id="videoFile">
                                    <table id="videoFileTable"></table>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
</div>

    <div class="bottom">
    <style>
    /* Modal overlay covers the entire viewport */
    .modal-overlay {
      display: none; /* Hidden by default */
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
      justify-content: center;
      align-items: center;
      z-index: 1000;
    }

    /* Modal content styling */
    .modal-content {
      background-color: #fff;
      padding: 20px;
      width: 100%; /* Adjust width as needed */
      height: 100%;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      position: relative;
    }

    /* Close button styling */
    .modal-close {
        position: absolute;
  top: 12px;
  right: 12px;
  font-size: 45px;
  cursor: pointer;
  color: #000;
    }
    iframe {
      border: none;
    }
  </style>



<!-- Modal Structure -->
<div class="modal-overlay" id="modalOverlay">
  <div class="modal-content">
    <span class="modal-close" id="modalClose">&times;</span>

    <iframe id="myIframe" width="100%" height="100%"  src=""></iframe>
        <script>
    // Get modal elements
    const openModalBtn = document.getElementById('openModalBtn');
    const modalOverlay = document.getElementById('modalOverlay');
    const modalClose = document.getElementById('modalClose');

    // Function to open the modal
    function openModal() {
        const myIframe = document.getElementById('myIframe');
        myIframe.src = "https://cam.paizogps.in/808gps/open/player/ftp.html?jsession="+jsion+"&vehiIdno="+$("#selnumber option:selected").text()
      modalOverlay.style.display = 'flex';
    }

    // Function to close the modal
    function closeModal() {
      modalOverlay.style.display = 'none';
    }

    // Event listeners for opening and closing the modal
    openModalBtn.addEventListener('click', openModal);
    modalClose.addEventListener('click', closeModal);

    // Close modal when clicking outside the modal content
    modalOverlay.addEventListener('click', function (e) {
      if (e.target === modalOverlay) {
        closeModal();
      }
    });
  </script>
    </div>
</div>
    </div>


    </div>
</body>
</html>

