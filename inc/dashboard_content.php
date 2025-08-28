

<div class="newdashboardheader" id="newdashboard_style" style="background: url(img/hex.jpg); display: block;">
     <a id=" showRightD" href="#" title="<? echo $la['MENU']; ?>">
    </a>
      <div class="ndtopbar-left" id="hideadmin" style="height:55px">

        <a href="#" class="waves-effect" onclick="tglnotify();" title="<? echo $la['HIDE_NOTIFICATIONS']; ?>">
            <i id='idnoti' class="demo-icon icon-bookmark lh50"></i></a>

        <a class='a_count' onclick="openevent();" title="<? echo $la['EVENTS']; ?>" href="#" data-target="#" class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="true">
            <i class="demo-icon icon-bell-alt lh50"><span id="eventcount" class="county">0</span></i>
        </a>

        <a class='a_count' title="<? echo $la['REPORTS_DOWNLOADED']; ?>" href="#" data-target="#" class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="true">
            <i class="demo-icon icon-download lh50"><span id="reportdownoadcount" class="county">0</span></i>
        </a>

        <a class='a_count' title="<? echo $la['EMAIL_SEND_TODAY']; ?>" href="#" data-target="#" class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="true">
            <i class="demo-icon icon-mail-alt lh50"><span id="emailsendcount" class="county">0</span></i>
        </a>
    </div>
    <div class="lo"> <a href="#" class="logo"><img class="imgpad imgpad1" onclick='$("#tglDBMV").click();' src="<? echo $gsValues['URL_ROOT']; ?>/img/logo.png"> </a> </div>

    <div class="ndtopbar-right">

        <div onclick="settingsOpenUser1();" id="settingsOpenUser" title="Click me to view account details" style="cursor: pointer;float: left;" class="col-sm-9">
            <div>
                <h3 class="text-uppercase text-right">
                    <a href="#" pdright" title="<? echo $la['MY_ACCOUNT']; ?>"><button class="dropbtn" style="
                       padding-left: 7px;
                        background-color: #15921a!important;
                        padding-right: 17px;
                        border-radius: 4px;
                        padding-top: 6px;
                        padding-bottom: 6px;
                        font-size: 15px;box-shadow: 0 3px 12px rgba(0, 0, 0, 0.56)
                    "><i class="demo-icon icon-user "></i><span class="">
                        <? echo truncateString($_SESSION["username"], 15);?></span></button></a>
                </h3>
            </div>
        </div>

        <div onclick="connectLogout()" class="col-sm-9">
            <div>
                <h3 class="text-uppercase text-right">
                    <a href="#" class=" " title="<? echo $la['LOGOUT']; ?>"><button class="dropbtn" style="
   padding-left: 7px;
    background-color: #e41f11!important;
    padding-right: 17px;
    border-radius: 4px;
    padding-top: 6px;
    padding-bottom: 6px;
    font-size: 15px;box-shadow: 0 3px 12px rgba(0, 0, 0, 0.56)
"><i class="demo-icon icon-logout "></i><span class="">
                                <? echo $la['LOGOUT']; ?></button></a>

                </h3>
            </div>
        </div>

    </div>

    <!-- <div class="pull-right" style="margin-left: 16px;margin-top: -30px;">

              
             
                
            


<span class="clearfix"></span>

                            </div>
  -->



</div>