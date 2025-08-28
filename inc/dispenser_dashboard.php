<style>
   .mini-stat
   {
   border-radius: 3px;
   margin-bottom: 5px;
   padding: 5px !important;
   color:fff;
   line-height: 1 !important;
   background:white;
   }
   #dall{
   overflow:hidden !important;
   }
   #parent  {
   overflow: scroll;
   }
   tr:first-child { overflow-y: hidden !important; }
   .btndashboard
   {
   display: block;
   float: left;
   color: #fff;
   background-color: #ff5533;
   padding: 8px 30px;
   text-shadow: 2px 2px 8px rgba(204, 48, 18, 1);
   border: solid 1px #cc3012;
   font-weight: 300;
   border-radius: 5px;
   font-size: 13px !important;
   }
   .newdashboard
   {
   width: 100%;
   margin: 0;
   padding: 0;
   background-color: #eeeded;
   height: 100%;
   position: absolute;
   top: 0px;
   z-index: 99;
   overflow: hidden;
   margin-top: 55px;
   }
   .newdashboardheader
   {
   position: fixed;
   z-index: 99;
   margin-top: -51px !important;
   /*margin:-3px 0;*/
   width: 100%;
   height: 51px;
   background: #0c297a;
   box-shadow: 1px 0 20px 0 rgba(0, 0, 0, 0.72);
   }
   .ndtopbar-left {
   float: left;
   position: relative;
   z-index: 1;
   margin-top: 19px;
   }
   .ndtopbar-right {
   float: right;
   width: 33%;
   }
   .imgpad
   {padding-left: 12px;
   width: 179px;
   height: 49px;
   padding: 6px;
   margin-left: 33rem;
   }
   .pdright
   {
   padding-right: 20px;
   }
   .lh50
   {
   font-size: 15px;
   color:#675f5f;
   line-height: 50px;
   }
   .dc
   {
   width: 100%;
   height: 100%;
   margin: 0;
   padding: 0;
   }
   .dl
   {
   }
   .dr
   {
   margin: 0px;
   left: 0px;
   position: absolute;
   float: right;
   width: 100%;
   height:100%;
   }
   .drinr
   {
   width: 95%;
   height: 100%;
   padding: 0px 0px 0px 141px;
   }
   .tv
   {
   color:#696969;
   border-bottom:1px solid black;
   }
   .bcf2
   {
   background-color: #f2f2f2;
   }
   .thbg {
   background-color: #4CAF50;
   color: white;
   }
   #parent { width: 100%; height: 100%; position:relative;}
   .object-follow-controlv {
   position: absolute;
   top: 78px;
   left: 400px;
   background-color: #ffffff;
   padding: 2px 5px 1px 5px;
   font-size: 16px;
   color: #808080;
   border-radius: 2px;
   box-shadow: 0 1px 5px rgba(0, 0, 0, 0.65);
   }
   .object-follow-controlv2 {
   height: 25px;
   position: absolute;
   top: 78px;
   left: 548px;
   background-color: #ffffff;
   padding: 2px 5px 1px 5px;
   font-size: 16px;
   color: #808080;
   border-radius: 2px;
   box-shadow: 0 1px 5px rgba(0, 0, 0, 0.65);
   }
   .county
   {
   background: #ef5350;
   color: white;
   border-radius: 49px;
   height: 10px;
   width: auto;
   line-height: 10px;
   padding: 5px 5px 2px 4px;position:absolute;
   top: 12px;
   font-size: 13px;
   }
   .a_count
   {
   margin-right: 16px;
   }
   nav#primary-nav {
   display: none;
   }
   .lines:before { 
   border-bottom: 10px double #840d0d;
   border-top: 10px double #840d0d;
   content: "";
   height: 5px;
   position: absolute;
   right: 8px;
   top: 17px;
   width: 27px;
   }
   .voverlay {
   width: 100%;
   height: 100%;
   background: rgba(16, 16, 21, 0.4);
   z-index: 800;
   position: fixed;
   top: 0;
   right: 0;
   }
   .dropdown-menu {
   min-width: 180px;
   padding: 8px 0;
   color: #333;
   -webkit-box-shadow: 0 3px 6px rgba(0,0,0,.16), 0 3px 6px rgba(0,0,0,.23);
   box-shadow: 0 3px 6px rgba(0,0,0,.16), 0 3px 6px rgba(0,0,0,.23);
   }
   .dropdown-menu {
   position: absolute;
   top: 100%;
   left: 0;
   z-index: 1000;
   display: none;
   min-width: 160px;
   padding: 5px 0;
   margin: 2px 0 0;
   list-style: none;
   font-size: 13px;
   text-align: left;
   background-color: #fff;
   border: 1px solid transparent;
   border-radius: 3px;
   -webkit-box-shadow: 0 6px 12px rgba(0,0,0,.175);
   box-shadow: 0 6px 12px rgba(0,0,0,.175);
   background-clip: padding-box;
   }
   .dropdown-menu>li {
   position: relative;
   margin-bottom: 1px;
   }
   .dropdown-menu-sm>li>a {
   padding-top: 7px;
   padding-bottom: 7px;
   font-size: 12px;
   line-height: 1.6666667;
   }
   .dropdown-menu>li>a {
   padding: 8px 16px;
   overflow: hidden;
   text-overflow: ellipsis;
   }
   .dropdown-menu>li>a {
   clear: both;
   font-weight: 400;
   color: #333;
   }
   .dropdown-header, .dropdown-menu>li>a {
   display: block;
   padding: 3px 20px;
   line-height: 1.5384616;
   white-space: nowrap;
   }
   .dropdown-menu>.dropdown-header>.badge.pull-right, .dropdown-menu>.dropdown-header>.label.pull-right, .dropdown-menu>li>a>.badge.pull-right, .dropdown-menu>li>a>.label.pull-right {
   margin-right: 0;
   margin-left: 16px;
   }
   .dropdown-menu>.dropdown-header>.badge, .dropdown-menu>.dropdown-header>.label, .dropdown-menu>li>a>.badge, .dropdown-menu>li>a>.label {
   float: left;
   margin-right: 16px;
   }
   .pull-right {
   float: right!important;
   }
   .label-primary {
   background-color: #2196F3;
   }
   .dropdown-menu .divider {
   margin: 8px 0;
   }
   .dropdown-menu .divider {
   height: 1px;
   margin: 9px 0;
   overflow: hidden;
   background-color: #e5e5e5;
   }
   .lo{
   text-align: center;
   float: left;
   }
   .he{
   width: 11%;
   float: left;
   margin: 5px;
   }
   .box {
   margin: 1em;
   position: relative;
   display: inline-block;
   border-radius: 12px;
   background-color: #fff;
   box-shadow: 0 10px 19px rgba(0, 0, 0, 0.56);
   transition: all 0.3s ease-in-out;
   }
   .box1 {
   margin: 1em;
   position: relative;
   display: inline-block;
   border-radius: 12px;
   background-color: #fff;
   box-shadow: 0 10px 19px rgba(0, 0, 0, 0.56);
   transition: all 0.3s ease-in-out;
   }
   .box1:after {
   content: '';
   position: absolute; 
   opacity: 0;
   border-radius: 5px;
   box-shadow: 0 5px 15px rgba(0,0,0,0.3);
   transition: opacity 0.3s ease-in-out;
   }
   /* Scale up the box */
   .box1:hover {
   transform: scale(1.1, 1.1);
   }
   /* Fade in the pseudo-element with the bigger shadow */
   .box1:hover:after {
   opacity: 1;
   }
   .imgpad1{
   margin-left: 21.8rem;
   margin-top: -4px;
   }
</style>

<?php include "dashboard_content.php";?>
<div id="newdashboard" class="newdashboard" style="display: block;">
   <div class="dc">
      <div class="dl">
      </div>
      <div class="dr">
         <div class="drinr">
            <div style="height:40%;" class="dashboard_mobvi">
               <div class="">
                  <marquee id="notify"  scrollamount="3" direction="left" behaviour="scroll" onMouseOver="this.stop();" onMouseOut="this.start();">
                     <ol><a style="color:red;font-size: small;" target="_blank" >Notification Not Available</a></ol>
                  </marquee>
               </div>
               <div class="row">
                  <div class=" width100">
                     <div class="container">
                        <div class="">
                           <div class="width90">
                              <div title="Click me to view more vehicle information" class="col-sm-3 zoom">
                                 <div class="mini-stat clearfix bx-shadow bg-white he box1" style="border: 5px solid #317eeb;border-top:none;border-bottom:none;padding:3px 5px 4px 5px !important" onclick="disDashboardFilter_data('total_dispenser');" >
                                    <h3 class="text-uppercase text-right" style="margin: 10px;font-size: inherit;">Total Dispenser  
                                       <span  id="span_totalvehicle"  class="counter text-dark float-right">100</span>
                                    </h3>
                                 </div>
                              </div>
                              <div title="Click me to view more vehicle information" class="col-sm-3 zoom">
                                 <div class="mini-stat clearfix bx-shadow bg-white he box1" style="border: 5px solid #F5456D;border-top:none;border-bottom:none;padding:3px 5px 4px 5px !important" onclick="disDashboardFilter_data('dis_status');">
                                    <h3 class="text-uppercase text-right" style="margin: 10px;font-size: inherit;">Current Dispensing  
                                       <span  id="span_current_dispensing"  class="counter text-dark float-right">100</span>
                                    </h3>
                                 </div>
                              </div>
                              <div title="Click me to view more vehicle information" class="col-sm-3 zoom">
                                 <div class="mini-stat clearfix bx-shadow bg-white he box1" style="border: 5px solid #99B958;border-top:none;border-bottom:none;padding:3px 5px 4px 5px !important" onclick="disDashboardFilter_data('tanker_lowfuel');">
                                    <h3 class="text-uppercase text-right" style="margin: 10px;font-size: inherit;">Tanker Low Fuel
                                       <span  id="span_tanker_low_fuel"  class="counter text-dark float-right">100</span>
                                    </h3>
                                 </div>
                              </div>
                              <div title="Click me to view more vehicle information" class="col-sm-3 zoom">
                                 <div class="mini-stat clearfix bx-shadow bg-white he box1" style="border: 5px solid #5c589a;border-top:none;border-bottom:none;padding:3px 5px 4px 5px !important" onclick="disDashboardFilter_data('vehicle_lowfuel');">
                                    <h3 class="text-uppercase text-right" style="margin: 10px;font-size: inherit;">Vehicle Low Fuel
                                       <span  id="span_vehicle_low_fuel"  class="counter text-dark float-right">100</span>
                                    </h3>
                                 </div>
                              </div>
                              <div title="Click me to view more vehicle information" class="col-sm-3 zoom">
                                 <div class="mini-stat clearfix bx-shadow bg-white he box1" style="border: 5px solid #388c98;border-top:none;border-bottom:none;padding:3px 5px 4px 5px !important">
                                    <h3 class="text-uppercase text-right" style="margin: 10px;font-size: inherit;">Dispensing QTY
                                       <span  id="span_dispensing_qty"  class="counter text-dark float-right">100</span>
                                    </h3>
                                 </div>
                              </div>
                              <div title="Click me to view more vehicle information" class="col-sm-3 zoom">
                                 <div class="mini-stat clearfix bx-shadow bg-white he box1" style="border: 5px solid #d84242;border-top:none;border-bottom:none;padding:3px 5px 4px 5px !important" onclick="disDashboardFilter_data('tanker_lock');">
                                    <h3 class="text-uppercase text-right" style="margin: 10px;font-size: inherit;">Tanker I-Lock
                                       <span  id="span_tanker_ilock"  class="counter text-dark float-right">100</span>
                                    </h3>
                                 </div>
                              </div>
                              <div title="Click me to view more vehicle information" class="col-sm-3 zoom">
                                 <div class="mini-stat clearfix bx-shadow bg-white he box1" style="border: 5px solid #589a5f;border-top:none;border-bottom:none;padding:3px 5px 4px 5px !important" onclick="disDashboardFilter_data('dis_lock');">
                                    <h3 class="text-uppercase text-right" style="margin: 10px;font-size: inherit;">Dispenser I-Lock
                                       <span  id="span_dispenser_ilock"  class="counter text-dark float-right">100</span>
                                    </h3>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="block width83" style="margin-left: 20px;margin-top: 13px;">
                     <div id="dispenser_dashboard">
                        <div id="dashboard_dispenser">
                           <div id="dispenser_dashboard_list">
                                 <input type="text" placeholder="Search Dispenser" id="dispenser_dashboard_objectr_list_search" style="border-radius: 18px;border-color: #deaaaa!important;padding-left: 9px !important;margin-left: 0%;height: 33px;">
                                 <!-- <button class="btn_dis">Download</button>                             -->
                              <table id="dispenser_dashboard_grid_list"></table>
                              <div id="dispenser_dashboard_grid_list_pager"></div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div style="clear:both;height:10px;"></div>
         </div>
      </div>
   </div>
</div>

<style>
   body { font-family: "Roboto Condensed", Arial, "Helvetica Neue", Helvetica, sans-serif;}