<?
	if(isset($_GET['au']))
	{
		session_start();
		
		if (isset($_SESSION["user_id"]))
		{
			session_unset();
			session_destroy();
			session_start();
		}
		
		include ('init.php');
		include ('func/fn_common.php');
		
		$au = $_GET['au'];
		$mobile = @$_GET["m"];
		$object = @$_GET["object"];
		
		$user_id = getUserIdFromAU($au);
		
		if ($user_id == false)
		{
			if ($mobile == 'true')
			{
				header('Location: mobile/index.php');
				die;
			}
			else
			{
				header('Location: index.php');
				die;
			}
		}
		
		setUserSession($user_id);
		setUserSessionSettings($user_id);
		setUserSessionCPanel($user_id);
		
		//write log
		writeLog('user_access', 'User login via URL: successful');
		
		if ($mobile == 'true')
		{
			if($object!='')
			{
				header('Location: func/fn_object.follow.php?object='.$object.'&map_layer=gmap');
				die;
			}
			else 
			{
				header('Location: mobile/tracking.php');
				die;
			}
		}
		else
		{
			if($object!='')
			{
				header('Location: func/fn_object.follow.php?object='.$object.'&map_layer=gmap');
				die;
			}
			else 
			{
				header('Location: Dashboard.php');
				die;
			}
		}
		die;
	}

	session_start();
	include ('init.php');
	include ('func/fn_common.php');
	checkUserSession();
	
	loadLanguage($gsValues['LANGUAGE']);
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<? generatorTag(); ?>
	<title><? echo $gsValues['NAME'].' '.$gsValues['VERSION']; ?></title>
	
 	<link rel='stylesheet prefetch' href='https://www.google.com/fonts#UsePlace:use/Collection:Roboto:400,300,100,500'>
	<link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css'>
	<link rel='stylesheet prefetch' href='https://www.google.com/fonts#UsePlace:use/Collection:Roboto+Slab:400,700,300,100'>
	<link type="text/css" href="theme/jquery-ui.css?v=<? echo $gsValues['VERSION_ID']; ?>" rel="Stylesheet" />
	
     <link rel="stylesheet" href="theme/login.css">
	
	<script type="text/javascript" src="js/jquery-2.1.4.min.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>
	<script type="text/javascript" src="js/jquery-migrate-1.2.1.min.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>
	<script type="text/javascript" src="js/jquery-ui.min.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>
	<script type="text/javascript" src="js/jquery.show-pass.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>
	
	<script type="text/javascript" src="js/gs.common.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>
	<script type="text/javascript" src="js/gs.connect.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>
	
	<script>
		$(document).ready(function () {
			$(".custom-checkbox").click(function() {
				$(this).toggleClass('checked')
			});
		});
	</script>
	  <style type="text/css">
    #myVideo {
    position: fixed;
  
    min-width: 100%; 
    min-height: 100%;
}



  </style>
	
</head>

<body id="login" onload="connectLoad()">

	 <div class="country-wrap">
<div id="background-wrap">
          <div class="x1">
              <div class="nuvem"></div>
          </div>

          <div class="x2">
              <div class="nuvem"></div>
          </div>

          <div class="x3">
              <div class="nuvem"></div>
          </div>

          <div class="x4">
              <div class="nuvem"></div>
          </div>

         <!--  <div class="x5">
             <div class="nuvem"></div>
         </div>
                 
           <div class="x6">
             <div class="nuvem"></div>
         </div>
         
          <div class="x7">
             <div class="nuvem"></div>
         </div>
         
          <div class="x8">
             <div class="nuvem"></div>
         </div> -->

      </div>
  
  
	<!--<div class="mountain-1"></div>
	<div class="mountain-2"></div>-->

	<div class="sat"><img src="img/sat.gif"></div>
	<div class="build"><img src="img/build.png"></div>
<div class="tower"><img src="img/tower.gif" style="width:100%;"></div>
<div class="hills"><img src="img/hills.png"></div>
	<div class="grass"></div>
	
	<div class="street">

		<div class="car">
		<!--<div class="car-base"></div>-->

		<div class="car-body">
			<div class="car-top-back">
				<div class="back-curve"></div>
			</div>

			<div class="car-gate"></div>
			<div class="car-top-front">
				<div class="wind-sheild"></div>
			</div>

			<div class="bonet-front"></div>
			<div class="stepney"></div>
			<img src="img/loc.png" style="width: 53%;margin-top: -84%;">

		</div>
			
		<div class="boundary-tyre-cover">
			<div class="boundary-tyre-cover-back-bottom"></div>
			<div class="boundary-tyre-cover-inner"></div>	
		</div>
		<div class="tyre-cover-front">
			<div class="boundary-tyre-cover-inner-front"></div>
		</div>
		<div class="base-axcel">
			
		</div>
		<div class="front-bumper"></div>
		<div class="tyre">		
			<div class="gap"></div>	
		</div>
		<div class="tyre front">
			<div class="gap"></div>	
		</div>
		<div class="car-shadow"></div>

		
		
	</div>





	</div>
	<div class="street-stripe"></div>
	
	
</div>

<div id="dialog_notify" title="" style="display:none">
	<div class="row">
		<div class="row2">
			<div class="width100 center-middle">
				<span id="dialog_notify_text"></span>
			</div>
		</div>
	</div>
	<center>
		<input class="button" type="button" onclick="$('#dialog_notify').dialog('close');" value="<? echo $la['OK']; ?>" />
	</center>
</div>

<div id="authentication_pin" title="" style="display:none">
	<div class="row">
		<div class="row2">
			<div class="width50 center-middle" style="padding-left: 43px;">
				<input class="form-control" id="loginuser_pin" name="login_pin" placeholder="Enter User PIN"  maxlength="50" type="password" required=""   tabindex="1" autofocus>
			</div>
		</div>
	</div>
	<center>
		<input class="button" type="button" onclick="checkloginAuthentication_pin('checkpin');" value="<? echo $la['OK']; ?>" />
	</center>
</div>

<div id="dialog" class="dialog dialog-effect-in">
  <div class="dialog-front">
    <div class="dialog-content">
      <form id="login_form"  class="form-horizontal" action="" method="POST">
        <fieldset>
  <legend>     <img src="theme/images/logo.png" alt="branding logo" style="width: 271px;">
       <!--  <legend>Log in</legend> -->
       </legend> 

        <div class="form-group">
                      <div class="input-group">
                        <span class="input-group-addon" style="background-color: #78797c;">
                         <img src="img/ico/user.png">
                        </span> 
                     <input class="form-control" id="username" name="user_username"  maxlength="50" type="text" required=""   tabindex="1" autofocus>
                      </div>
                    </div>

                     <div class="form-group">
                      <div class="input-group">
                        <span class="input-group-addon" style="background-color: #78797c;">
                         <img src="img/ico/lock.png">
                        </span>
                       <input class="form-control" type="password" name="user_password" id="password" maxlength="20" required=""  tabindex="2">
                      </div>
                    </div>
       
        
          <div class="text-center pad-top-20">
             <input class="checkbox float-right" type="checkbox" id="remember_me" style="display: table-row-group;    min-height: 5px;">
                                <label for="remember_me"><? echo $la['REMEMBER_ME']; ?></label>
          </div>
          <div class="pad-top-20">
          <input type="submit"  class="btn btn-default btn-block btn-lg" style="background: #78797c;"  value="<? echo $la['LOGIN']; ?>" onclick="connectLogin(); return false;" tabindex="3">
           
           <div class="pad-top-20 pad-btm-20">
           	<div class="text-center"><br>
            <p><a href="#"   onclick="window.open('mobile/index.php', '_self');" style="color:#60656f !important;"  class="link user-actions"><strong><? echo $la['MOBILE_VERSION']; ?></strong></a></p>
          </div>
            <!-- <input type="button" class="btn btn-default btn-block btn-lg" style="
       background: #ff4444;
"  value="<? echo $la['MOBILE_VERSION']; ?>"    onclick="window.open('mobile/index.php', '_self');" /> -->
          </div>
          
          </div>
          <div class="text-center">
            <p><a href="#"   style="color:#000 !important;"  class="link user-actions" onclick="forgetblock();"><strong><? echo $la['FORGET']; ?></strong></a></p>
          </div>
         
          <div class="pad-btm-20" style="display: none;">
            <select id="system_language" class="form-control" onChange="switchLanguageLogin();"><? echo getLanguageList(); ?></select>
          </div>
        </fieldset>
      </form>
    </div>
  </div>
 <div class="dialog-back" id="forgetpass_open" > 
   <div class="dialog-content">
     <form id="register_form" class="dialog-form" action="" method="POST">
 		<fieldset>
      		<legend>   <img src="theme/images/logo.png" alt="branding logo">
              <legend style="font-size:22px;"><? echo $la['FORGET']; ?></legend>
            </legend>
              <div class="form-group">
                <label for="user_username" class="control-label"><? echo $la['EMAIL']; ?>:</label>
                <input class="form-control" id="rec_email"  name="rec_email" maxlength="50" />
              </div>
              <div class="form-group">
                <label for="user_password" class="control-label"><? echo $la['ENTER_CODE']; ?>: <button style="background: transparent;" type="submit" class="btn btn-lg btn-primary waves-effect waves-light"><img class="security-code" src="tools/seccode.php" align="absmiddle"></button></label>
                <input  class="form-control " type="text" id="rec_seccode" />
              </div>
              <div class="form-group form-group-checkbox">
                <div class="checkbox">
                  <label>
                   <? echo $la['NEW_LOGIN_DATA_WILL_BE_SENT_TO_EMAIL']; ?>
                  </label>
                </div>
              </div>
              <div class="">
                <input type="button" class="btn btn-default btn-block btn-lg" value="<? echo $la['REMIND']; ?>"  onClick="connectRecoverURL();"  />
              </div>
              
                 <div class="pad-top-20 pad-btm-20">
                <input type="button" class="btn btn-default btn-block btn-lg"  value="<? echo $la['MOBILE_VERSION']; ?>"    onclick="window.open('mobile/index.php', '_self');" />
              </div>
              
              <div class="text-center">
                <p>Return to <a href="#" style="color:#9b7233 !important;" class="link user-actions"><strong>log in page</strong></a>?</p>
              </div>
              
               <div class="pad-btm-20">
                <select id="system_language" class="form-control" onChange="switchLanguageLogin();"><? echo getLanguageList(); ?></select>
              </div>
              
        </fieldset>      </form>
   </div>
 </div> 
</div>
<script src="js/login.js"></script>

<script>
/*
function detect_enter_keyboard(event) {
    var key_board_keycode = event.which || event.keyCode;
    if(key_board_keycode == 13)
    {
    	connectLogin(); return false;
    }
}
*/
document.getElementById("forgetpass_open").style.display = "none";

function forgetblock(){
document.getElementById("forgetpass_open").style.display = "block";
}
</script>

	
</body>
</html>
