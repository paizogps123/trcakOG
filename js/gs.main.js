    // code add by vetrivel.N

function multiselectGetValuesnot(select) {
    var selected = '';
    for (var i = 0; i < select.options.length; i++) {
            selected += (select.options[i].value) + ",";        
    }
    return selected.slice(0, -1);
}

// end code add by vetrivel.N

function dtcOpen() {
    utilsCheckPrivileges("dtc") && ($("#dialog_dtc").bind("resize", function() {
        $("#dtc_list_grid").setGridHeight($("#dialog_dtc").height() - 133)
    }).trigger("resize"), $("#dialog_dtc").bind("resize", function() {
        $("#dtc_list_grid").setGridWidth($("#dialog_dtc").width())
    }).trigger("resize"), $("#dialog_dtc").dialog("open"))
}

function dtcClose() {
    $("#dialog_dtc").unbind("resize")
}

function dtcShow() {
    var e = "func/fn_dtc.php?cmd=load_dtc_list",
        t = document.getElementById("dialog_dtc_object_list").value,
        a = document.getElementById("dialog_dtc_date_from").value + " " + document.getElementById("dialog_dtc_hour_from").value + ":" + document.getElementById("dialog_dtc_minute_from").value + ":00",
        o = document.getElementById("dialog_dtc_date_to").value + " " + document.getElementById("dialog_dtc_hour_to").value + ":" + document.getElementById("dialog_dtc_minute_to").value + ":00";
    "" != t && (e += "&imei=" + t), a != o && (e += "&dtf=" + a + "&dtt=" + o), $("#dtc_list_grid").jqGrid("setGridParam", {
        url: e
    }).trigger("reloadGrid")
}

function dtcDelete(e) {
    utilsCheckPrivileges("viewer") && confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE, function(t) {
        if (t) {
            var a = {
                cmd: "delete_record",
                dtc_id: e
            };
            $.ajax({
                type: "POST",
                url: "func/fn_dtc.php",
                data: a,
                success: function(e) {
                    "OK" == e && dtcShow()
                }
            })
        }
    })
}

function dtcDeleteSelected() {
    if (utilsCheckPrivileges("viewer")) {
        var e = $("#dtc_list_grid").jqGrid("getGridParam", "selarrrow");
        return "" == e ? void notifyBox("error", la.ERROR, la.NO_ITEMS_SELECTED) : void confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE_SELECTED_ITEMS, function(t) {
            if (t) {
                var a = {
                    cmd: "delete_selected_records",
                    items: e
                };
                $.ajax({
                    type: "POST",
                    url: "func/fn_dtc.php",
                    data: a,
                    success: function(e) {
                        "OK" == e && dtcShow()
                    }
                })
            }
        })
    }
}

function dtcDeleteAll() {
    utilsCheckPrivileges("viewer") && confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE_ALL_DTC_RECORDS, function(e) {
        if (e) {
            var t = {
                cmd: "delete_all_records"
            };
            $.ajax({
                type: "POST",
                url: "func/fn_dtc.php",
                data: t,
                success: function(e) {
                    "OK" == e && dtcShow()
                }
            })
        }
    })
}

function dtcExportCSV() {
    var e = "func/fn_export.php?format=dtc_csv",
        t = document.getElementById("dialog_dtc_object_list").value,
        a = document.getElementById("dialog_dtc_date_from").value + " " + document.getElementById("dialog_dtc_hour_from").value + ":" + document.getElementById("dialog_dtc_minute_from").value + ":00",
        o = document.getElementById("dialog_dtc_date_to").value + " " + document.getElementById("dialog_dtc_hour_to").value + ":" + document.getElementById("dialog_dtc_minute_to").value + ":00";
    "" != t && (e += "&imei=" + t), a != o && (e += "&dtf=" + a + "&dtt=" + o), window.location = e
}

function billingOpen() {
    utilsCheckPrivileges("subuser") && $("#dialog_billing").dialog("open")
}

function billingClose() {}

function billingLoadData() {
    clearTimeout(timer_billingLoadData), timer_billingLoadData = setTimeout("billingLoadData();", 1e3 * gsValues.billing_refresh), billingUpdateCount(), 1 == $("#dialog_billing").dialog("isOpen") && billingReload()
}

function billingReload() {
    $("#billing_plan_list_grid").trigger("reloadGrid")
}

function billingUpdateCount() {
    var e = {
        cmd: "get_billing_plan_total_objects"
    };
    $.ajax({
        type: "POST",
        url: "func/fn_billing.php",
        data: e,
        dataType: "json",
        cache: !1,
        success: function(e) {
            void 0 != document.getElementById("billing_plan_count") && (document.getElementById("billing_plan_count").innerHTML = e.objects)
        }
    })
}

function billingPlanPurchase() {
    if (utilsCheckPrivileges("viewer") && utilsCheckPrivileges("subuser")) {
        var e = {
            cmd: "load_billing_plan_purchase_list"
        };
        $.ajax({
            type: "POST",
            url: "func/fn_billing.php",
            data: e,
            cache: !1,
            success: function(e) {
                "" == e ? notifyBox("error", la.ERROR, la.NO_BILLING_PLANS_FOUND) : (document.getElementById("billing_plan_purchase_list").innerHTML = e, $("#dialog_billing_plan_purchase").dialog("open"))
            }
        })
    }
}

function billingPlanUse(e) {
    if (utilsCheckPrivileges("subuser")) {
        billingPlanUseObjectLoadList();
        var t = {
            cmd: "load_billing_plan",
            plan_id: e
        };
        $.ajax({
            type: "POST",
            url: "func/fn_billing.php",
            data: t,
            dataType: "json",
            cache: !1,
            success: function(e) {
                if (billingData.plan = e, document.getElementById("dialog_billing_plan_use_objects").innerHTML = billingData.plan.objects, 1 == billingData.plan.period) var t = la[billingData.plan.period_type.slice(0, -1).toUpperCase()];
                else var t = la[billingData.plan.period_type.toUpperCase()];
                var a = billingData.plan.period + " " + t.toLowerCase();
                document.getElementById("dialog_billing_plan_use_period").innerHTML = a, document.getElementById("dialog_billing_plan_use_selected").innerHTML = 0, $("#dialog_billing_plan_use").dialog("option", "title", la.BILLING_PLAN + " - " + billingData.plan.name), $("#dialog_billing_plan_use").dialog("open")
            }
        })
    }
}

function billingPlanUseObjectLoadList() {
    var e = $("#billing_plan_object_list_grid");
    e.clearGridData(!0);
    for (var t in settingsObjectData) {
        var a = settingsObjectData[t],
            o = a.name.toLowerCase(),
            i = a.name,
            s = a.active,
            n = a.object_expire,
            l = a.object_expire_dt;
        s = "true" == s ? '<img src="theme/images/tick-green.svg" />' : '<img src="theme/images/remove-red.svg" style="width:12px;" />', "true" == n && e.jqGrid("addRowData", t, {
            name_sort: o,
            name: i,
            imei: t,
            active: s,
            object_expire_dt: l
        })
    }
    e.setGridParam({
        sortname: "name_sort",
        sortorder: "asc"
    }).trigger("reloadGrid")
}

function billingPlanUseUpdateSelection() {
    var e = $("#billing_plan_object_list_grid").jqGrid("getGridParam", "selarrrow"),
        t = e.length;
    t > billingData.plan.objects ? (document.getElementById("dialog_billing_plan_use_objects").innerHTML = 0, document.getElementById("dialog_billing_plan_use_selected").innerHTML = e.length + ' <font color="red">(' + la.TOO_MANY + ")</font>") : (document.getElementById("dialog_billing_plan_use_objects").innerHTML = billingData.plan.objects - t, document.getElementById("dialog_billing_plan_use_selected").innerHTML = e.length)
}

function billingPlanUseActivate() {
    if (utilsCheckPrivileges("viewer") && utilsCheckPrivileges("subuser")) {
        var e = $("#billing_plan_object_list_grid").jqGrid("getGridParam", "selarrrow");
        if ("" == e) return void notifyBox("error", la.ERROR, la.NO_ITEMS_SELECTED);
        var t = e.length;
        if (t > billingData.plan.objects) return void notifyBox("error", la.ERROR, la.TOO_MANY_OBJECTS_SELECTED);
        var a = !1;
        for (i = 0; i < t; i++) {
            var o = e[i],
                s = settingsObjectData[o];
            if ("true" == s.active) {
                a = !0;
                break
            }
        }
        var n = la.ARE_YOU_SURE_YOU_WANT_TO_ACTIVATE_SELECTED_OBJECTS;
        a && (n = la.THERE_ARE_STILL_ACTIVE_OBJECTS + " " + n), confirmDialog(n, function(a) {
            if (a) {
                var o = JSON.stringify(e),
                    i = {
                        cmd: "use_billing_plan",
                        plan: billingData.plan,
                        imeis: o
                    };
                $.ajax({
                    type: "POST",
                    url: "func/fn_billing.php",
                    data: i,
                    cache: !1,
                    success: function(e) {
                        "OK" == e ? (loadSettings("objects", function() {
                            objectReloadData(), billingReload(), billingPlanUseObjectLoadList(), billingUpdateCount(), billingData.plan.objects -= t, billingPlanUseUpdateSelection()
                        }), notifyBox("info", la.INFORMATION, la.OBJECTS_ACTIVATED_SUCCESSFULLY)) : "ERROR_VERIFY" == e ? notifyBox("error", la.ERROR, la.PLAN_VERIFICATION_FAILED) : "ERROR_ACTIVATE" == e && notifyBox("error", la.ERROR, la.OBJECT_ACTIVATION_FAILED)
                    }
                })
            }
        })
    }
}

function billingPlanDelete(e) {
    utilsCheckPrivileges("viewer") && utilsCheckPrivileges("subuser") && confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE, function(t) {
        if (t) {
            var a = {
                cmd: "delete_billing_plan",
                plan_id: e
            };
            $.ajax({
                type: "POST",
                url: "func/fn_billing.php",
                data: a,
                success: function(e) {
                    "OK" == e && billingReload()
                }
            })
        }
    })
}

function chatOpen() {
    utilsCheckPrivileges("chat") && ($("#dialog_chat").bind("resize", function() {
        scrollToBottom("chat_msgs")
    }), $("#dialog_chat").bind("resize", function() {
        $("#chat_object_list_grid").setGridHeight($("#dialog_chat").height() - 84)
    }).trigger("resize"), $("#dialog_chat").dialog("open"), $("#chat_msgs").scroll(chatMsgsScrollHandler), chatLoadObjectList())
}

function chatClose() {
    chatData.imei = !1, chatData.first_msg_id = !1, chatData.last_msg_id = !1, document.getElementById("chat_msg").disabled = !0, document.getElementById("chat_msg_send").disabled = !0, chatClear(), $("#dialog_chat").unbind("resize"), $("#chat_msgs").off("scroll", chatMsgsScrollHandler)
}

function chatClear() {
    document.getElementById("chat_msgs_dt").style.display = "none", document.getElementById("chat_msgs_dt").innerHTML = "", document.getElementById("chat_msgs_text").innerHTML = "", document.getElementById("chat_msg_status").innerHTML = "", document.getElementById("chat_msg").value = ""
}

function chatLoadData() {
    clearTimeout(timer_chatLoadData);
    var e = {
        cmd: "load_chat_data",
        imei: chatData.imei,
        last_msg_id: chatData.last_msg_id
    };
    $.ajax({
        type: "POST",
        url: "func/fn_chat.php",
        data: e,
        dataType: "json",
        error: function() {
            timer_chatLoadData = setTimeout("chatLoadData();", 1e3 * gsValues.chat_refresh)
        },
        success: function(e) {
            chatData.msg_count = e.msg_count, chatData.msg_dt = e.msg_dt, chatUpdateMsgCount(), chatUpdateMsgDt(), 0 != e.last_msg_status && chatUpdateMsgDeliveryStatus(e.last_msg_status);
            var t = chatData.imei;
            void 0 != chatData.msg_count[t] && chatLoadMsgs("new"), timer_chatLoadData = setTimeout("chatLoadData();", 1e3 * gsValues.chat_refresh)
        }
    })
}

function chatReloadData() {
    chatLoadObjectList(), chatLoadData()
}

function chatIsObjectCompatible(e) {
    return "" != objectsData[e].data && null != objectsData[e].data[0].params && void 0 != objectsData[e].data[0].params.chat
}

function chatLoadObjectList() {
    var e = $("#chat_object_list_grid");
    e.clearGridData(!0);
    for (var t in settingsObjectData) {
        var a = settingsObjectData[t];
        if ("true" == a.active && chatIsObjectCompatible(t)) {
            var o = '<img src="' + a.icon + '" style="width: 26px;"/>',
                i = "<span>" + a.name + '</span><br/><span class="status" id="chat_object_msg_status_' + t + '">' + la.NO_MESSAGES + "</span>",
                s = '<span id="chat_object_msg_count_' + t + '"></span>';
            e.jqGrid("addRowData", t, {
                search: a.name.toLowerCase(),
                icon: o,
                name: i,
                msg_count: s
            })
        }
    }
    e.setGridParam({
        sortname: "search",
        sortorder: "asc"
    }).trigger("reloadGrid")
}

function chatUpdateMsgCount() {
    var e = 0;
    if (null != document.getElementById("chat_msg_count")) {
        for (var t in chatData.msg_count)
            if (chatIsObjectCompatible(t)) {
                var a = chatData.msg_count[t];
                e += a
            }
        if (e > 0 && "0" == document.getElementById("chat_msg_count").innerHTML && "" != settingsUserData.chat_notify) {
            var o = new Audio("snd/" + settingsUserData.chat_notify);
            o.play()
        }
        document.getElementById("chat_msg_count").innerHTML = e, e > 0 ? document.title = gsValues.title + " (" + e + ")" : document.title = gsValues.title;
        for (var t in settingsObjectData) null != document.getElementById("chat_object_msg_count_" + t) && (void 0 != chatData.msg_count[t] ? document.getElementById("chat_object_msg_count_" + t).innerHTML = chatData.msg_count[t] : document.getElementById("chat_object_msg_count_" + t).innerHTML = "")
    }
}

function chatUpdateMsgDt() {
    for (var e in chatData.msg_dt)
        if (null != document.getElementById("chat_object_msg_count_" + e)) {
            var t = chatData.msg_dt[e];
            "" == t ? document.getElementById("chat_object_msg_status_" + e).innerHTML = la.NO_MESSAGES : document.getElementById("chat_object_msg_status_" + e).innerHTML = t
        }
}

function chatDeleteAllMsgs() {
    utilsCheckPrivileges("viewer") && confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE_ALL_SELECTED_OBJECT_MESSAGES, function(e) {
        if (e) {
            var t = {
                cmd: "delete_all_msgs",
                imei: chatData.imei
            };
            $.ajax({
                type: "POST",
                url: "func/fn_chat.php",
                data: t,
                success: function(e) {
                    if ("OK" == e) {
                        chatClear();
                        var t = chatData.imei;
                        chatData.msg_dt[t] = "", chatUpdateMsgDt()
                    }
                }
            })
        }
    })
}

function chatSend() {
    var e = document.getElementById("chat_msg").value;
    if (0 != chatData.imei && "" != e) {
        e = stripHTML(e), e = strLink(e);
        var t = {
            cmd: "send_msg",
            imei: chatData.imei,
            msg: e
        };
        $.ajax({
            type: "POST",
            url: "func/fn_chat.php",
            data: t,
            cache: !1,
            success: function(e) {
                "OK" == e && (document.getElementById("chat_msg").value = "", chatLoadMsgs("new"))
            },
            error: function() {}
        })
    }
}

function chatLoadMsgs(e) {
    if ("old" == e) var t = 10;
    else var t = 40;
    var a = {
        cmd: "load_msgs",
        type: e,
        imei: chatData.imei,
        msg_limit: t,
        first_msg_id: chatData.first_msg_id,
        last_msg_id: chatData.last_msg_id
    };
    $.ajax({
        type: "POST",
        url: "func/fn_chat.php",
        data: a,
        dataType: "json",
        cache: !1,
        success: function(t) {
            if ("" != t) {
                "old" == e && (document.getElementById("chat_msgs").scrollTop = 1);
                var a = "";
                for (var o in t) {
                    o = parseInt(o);
                    var i = t[o].dt,
                        s = t[o].s,
                        n = t[o].m,
                        l = t[o].st;
                    a += "old" != e ? chatFormatMsg(o, i, s, n) : chatFormatMsg(o, i, s, n), (chatData.first_msg_id > o || 0 == chatData.first_msg_id) && (chatData.first_msg_id = o), (chatData.last_msg_id < o || 0 == chatData.last_msg_id) && (chatData.last_msg_id = o)
                }
                if ("old" != e) {
                    document.getElementById("chat_msgs_text").innerHTML = document.getElementById("chat_msgs_text").innerHTML + a, scrollToBottom("chat_msgs");
                    var d = chatData.imei,
                        o = chatData.last_msg_id,
                        s = t[o].s,
                        l = t[o].st;
                    chatUpdateMsgDeliveryStatus("S" == s ? l : 0), delete chatData.msg_count[d], chatUpdateMsgCount();
                    var i = t[o].dt;
                    chatData.msg_dt[d] = i, chatUpdateMsgDt()
                } else document.getElementById("chat_msgs_text").innerHTML = a + document.getElementById("chat_msgs_text").innerHTML
            }
        },
        error: function() {}
    })
}

function chatFormatMsg(e, t, a, o) {
    if ("S" == a) var i = "chat-msg-server",
        s = "chat-msg-dt-server";
    else var i = "chat-msg-client",
        s = "chat-msg-dt-client";
    t.substring(0, 10) == moment().format("YYYY-MM-DD") && (t = t.substring(11, 19));
    var n = t,
        l = '<div class="chat-msg-container"><div title="' + t + '" class="' + i + '">' + o + '<div class="' + s + '">' + n + "</div></div></div>";
    return l
}

function chatUpdateMsgDeliveryStatus(e) {
    var t = !1;
    0 == e ? document.getElementById("chat_msg_status").innerHTML = "" : 1 == e ? ("" == document.getElementById("chat_msg_status").innerHTML && (t = !0), document.getElementById("chat_msg_status").innerHTML = la.DELIVERED) : 2 == e && ("" == document.getElementById("chat_msg_status").innerHTML && (t = !0), document.getElementById("chat_msg_status").innerHTML = la.SEEN), t && scrollToBottom("chat_msgs")
}

function chatSelectObject(e) {
    chatData.imei != e && (chatClear(), document.getElementById("chat_msg").disabled = !1, document.getElementById("chat_msg_send").disabled = !1, chatData.imei = e, chatData.first_msg_id = !1, chatData.last_msg_id = !1, chatLoadMsgs("select"))
}

function imgOpen() {
    utilsCheckPrivileges("image_gallery") && ($("#dialog_image_gallery").dialog("open"), imgLoadData())
}

function imgLoadData() {
    clearTimeout(timer_imgLoadData), timer_imgLoadData = setTimeout("imgLoadData();", 1e3 * gsValues.img_refresh), 1 == $("#dialog_image_gallery").dialog("isOpen") ? $("#image_gallery_list_grid").trigger("reloadGrid") : clearTimeout(timer_imgLoadData)
}

function imgFilter() {
    var e = "func/fn_img.php?cmd=load_img_list",
        t = document.getElementById("dialog_image_gallery_object_list").value,
        a = document.getElementById("dialog_image_gallery_date_from").value + " " + document.getElementById("dialog_image_gallery_hour_from").value + ":" + document.getElementById("dialog_image_gallery_minute_from").value + ":00",
        o = document.getElementById("dialog_image_gallery_date_to").value + " " + document.getElementById("dialog_image_gallery_hour_to").value + ":" + document.getElementById("dialog_image_gallery_minute_to").value + ":00";
    "" != t && (e += "&imei=" + t), a != o && (e += "&dtf=" + a + "&dtt=" + o), $("#image_gallery_list_grid").jqGrid("setGridParam", {
        url: e
    }).trigger("reloadGrid")
}

function imgDelete(e) {
    utilsCheckPrivileges("viewer") && utilsCheckPrivileges("subuser") && confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE, function(t) {
        if (t) {
            var a = {
                cmd: "delete_img",
                img_id: e
            };
            $.ajax({
                type: "POST",
                url: "func/fn_img.php",
                data: a,
                success: function(e) {
                    "OK" == e && (document.getElementById("image_gallery_img").innerHTML = "", document.getElementById("image_gallery_img_data").innerHTML = "", $("#image_gallery_list_grid").trigger("reloadGrid"))
                }
            })
        }
    })
}

function imgDeleteSelected() {
    if (utilsCheckPrivileges("viewer")) {
        var e = $("#image_gallery_list_grid").jqGrid("getGridParam", "selarrrow");
        return "" == e ? void notifyBox("error", la.ERROR, la.NO_ITEMS_SELECTED) : void confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE_SELECTED_ITEMS, function(t) {
            if (t) {
                var a = {
                    cmd: "delete_selected_imgs",
                    items: e
                };
                $.ajax({
                    type: "POST",
                    url: "func/fn_img.php",
                    data: a,
                    success: function(e) {
                        "OK" == e && (document.getElementById("image_gallery_img").innerHTML = "", document.getElementById("image_gallery_img_data").innerHTML = "", $("#image_gallery_list_grid").trigger("reloadGrid"))
                    }
                })
            }
        })
    }
}

function imgDeleteAll() {
    utilsCheckPrivileges("viewer") && utilsCheckPrivileges("subuser") && confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE_ALL_IMAGES, function(e) {
        if (e) {
            var t = {
                cmd: "delete_all_imgs"
            };
            $.ajax({
                type: "POST",
                url: "func/fn_img.php",
                data: t,
                success: function(e) {
                    "OK" == e && (document.getElementById("image_gallery_img").innerHTML = "", document.getElementById("image_gallery_img_data").innerHTML = "", $("#image_gallery_list_grid").trigger("reloadGrid"))
                }
            })
        }
    })
}


//code update by vetrive.N
function cmdOpennew() {
if (utilsCheckPrivileges("object_control")) {
	loadcommand();
  $("#dialog_object_commandnew").dialog("open")
}
}


function cmdNewnew() {
	$('#cmd_stringnew').val("");
	$('#cmd_object_listnew').val("Select");
$("#cmd_status_list_gridnew").trigger("reloadGrid")
}


function cmdStatusRefreshnew() {
clearTimeout(timer_cmdReloadnew);
timer_cmdReloadnew = setTimeout("$('#cmd_status_list_gridnew').trigger('reloadGrid');", gsValues.cmd_status_refresh * 1000);
}

//code done by vetrive.N


function cmdOpen() {
    utilsCheckPrivileges("object_control") && ($("#dialog_cmd").dialog("open"), cmdStatusLoadData(), cmdTemplateList(), cmdScheduleTemplateList())
}

function cmdStatusLoadData() {
    clearTimeout(timer_cmdLoadData), timer_cmdLoadData = setTimeout("cmdStatusLoadData();", 1e3 * gsValues.cmd_status_refresh), 1 == $("#dialog_cmd").dialog("isOpen") ? $("#cmd_status_list_grid").trigger("reloadGrid") : clearTimeout(timer_cmdLoadData)
}

function cmdReset() {
    document.getElementById("cmd_template_list").value = "", document.getElementById("cmd_gateway").value = "gprs", document.getElementById("cmd_type").value = "ascii", document.getElementById("cmd_cmd").value = ""
}

function cmdSend() {
    var e = document.getElementById("cmd_object_list").value,
        t = $("#cmd_template_list :selected").text(),
        a = document.getElementById("cmd_gateway").value,
        o = document.getElementById("cmd_type").value,
        i = document.getElementById("cmd_cmd").value;
    cmdCheck() && cmdExec(e, t, a, o, i)
}

function cmdCheck() {
    var e = document.getElementById("cmd_object_list").value,
        t = document.getElementById("cmd_gateway").value,
        a = document.getElementById("cmd_type").value,
        o = document.getElementById("cmd_cmd").value;
    if ("" == e) return !1;
    if ("" == o) return notifyBox("error", la.ERROR, la.COMMAND_CANT_BE_EMPTY, !0), !1;
    if ("sms" == t) {
        var i = settingsObjectData[e].sim_number;
        if ("" == i) return notifyBox("error", la.ERROR, la.OBJECT_SIM_CARD_NUMBER_IS_NOT_SET, !0), !1
    }
    return "hex" != a || (o = o.toUpperCase(), isHexValid(o)) ? !0 : (notifyBox("error", la.ERROR, la.COMMAND_HEX_NOT_VALID, !0), !1)
}

function cmdExec(e, t, a, o, i) {
    if (utilsCheckPrivileges("viewer")) {
        var s = settingsObjectData[e].sim_number;
        "hex" == o && (i = i.toUpperCase()), loadingData(!0);
        var n = {
            cmd: "exec_cmd",
            imei: e,
            name: t,
            gateway: a,
            sim_number: s,
            type: o,
            cmd_: i
        };
        $.ajax({
            type: "POST",
            url: "func/fn_cmd.php",
            data: n,
            success: function(e) {
                loadingData(!1), "OK" == e ? (cmdReset(), $("#cmd_status_list_grid").trigger("reloadGrid"), notifyBox("info", la.INFORMATION, la.COMMAND_SENT_FOR_EXECUTION, !0)) : "ERROR_NOT_SENT" == e && ($("#cmd_status_list_grid").trigger("reloadGrid"), notifyBox("error", la.ERROR, la.UNABLE_TO_SEND_SMS_MESSAGE, !0))
            },
            error: function() {
                loadingData(!1)
            }
        })
    }
}

function cmdExecDelete(e) {
    utilsCheckPrivileges("viewer") && confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE, function(t) {
        if (t) {
            var a = {
                cmd: "delete_cmd_exec",
                cmd_id: e
            };
            $.ajax({
                type: "POST",
                url: "func/fn_cmd.php",
                data: a,
                success: function(e) {
                    "OK" == e && $("#cmd_status_list_grid").trigger("reloadGrid")
                }
            })
        }
    })
}

function cmdExecDeleteSelected() {
    if (utilsCheckPrivileges("viewer")) {
        var e = $("#cmd_status_list_grid").jqGrid("getGridParam", "selarrrow");
        return "" == e ? void notifyBox("error", la.ERROR, la.NO_ITEMS_SELECTED) : void confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE_SELECTED_ITEMS, function(t) {
            if (t) {
                var a = {
                    cmd: "delete_selected_cmd_execs",
                    items: e
                };
                $.ajax({
                    type: "POST",
                    url: "func/fn_cmd.php",
                    data: a,
                    success: function(e) {
                        "OK" == e && $("#cmd_status_list_grid").trigger("reloadGrid")
                    }
                })
            }
        })
    }
}

function cmdTemplateSwitch() {
    var e = document.getElementById("cmd_template_list").value;
    "" != e ? (document.getElementById("cmd_gateway").value = cmdData.cmd_templates[e].gateway, document.getElementById("cmd_type").value = cmdData.cmd_templates[e].type, document.getElementById("cmd_cmd").value = cmdData.cmd_templates[e].cmd) : (document.getElementById("cmd_gateway").value = "gprs", document.getElementById("cmd_type").value = "ascii", document.getElementById("cmd_cmd").value = "")
}

function cmdTemplateList() {
    var e = document.getElementById("cmd_object_list").value;
    if (void 0 != settingsObjectData[e]) {
        var t = settingsObjectData[e].protocol,
            a = document.getElementById("cmd_template_list");
        a.options.length = 0;
        for (var o in cmdData.cmd_templates) {
            var i = cmdData.cmd_templates[o];
            i.protocol.toLowerCase() == t.toLowerCase() ? a.options.add(new Option(i.name, o)) : "" == i.protocol.toLowerCase() && a.options.add(new Option(i.name, o))
        }
        sortSelectList(a), a.options.add(new Option(la.CUSTOM, ""), 0)
    }
    cmdReset()
}

function cmdScheduleProtocolList() {
    var e = document.getElementById("dialog_cmd_schedule_protocol").value,
        t = document.getElementById("dialog_cmd_schedule_protocol");
    t.options.length = 0;
    for (var a = getAllProtocolsArray(), o = 0; o < a.length; o++) "" != a[o] && t.options.add(new Option(a[o], a[o]));
    sortSelectList(t), t.options.add(new Option(la.ALL_PROTOCOLS, ""), 0), document.getElementById("dialog_cmd_schedule_protocol").value = e
}

function cmdScheduleObjectList() {
    var e = document.getElementById("dialog_cmd_schedule_protocol").value,
        t = document.getElementById("dialog_cmd_schedule_object_list");
    multiselectClear(t);
    var a = getGroupsObjectsArray(e);
    multiselectSetGroups(t, a)
}

function cmdScheduleTemplateList() {
    var e = document.getElementById("dialog_cmd_schedule_protocol").value,
        t = document.getElementById("dialog_cmd_schedule_template_list");
    t.options.length = 0;
    for (var a in cmdData.cmd_templates) {
        var o = cmdData.cmd_templates[a];
        "" == e ? t.options.add(new Option(o.name, a)) : o.protocol.toLowerCase() == e.toLowerCase() && t.options.add(new Option(o.name, a))
    }
    sortSelectList(t), t.options.add(new Option(la.CUSTOM, ""), 0), document.getElementById("dialog_cmd_schedule_template_list").value = "", document.getElementById("dialog_cmd_schedule_cmd_gateway").value = "gprs", document.getElementById("dialog_cmd_schedule_cmd_type").value = "ascii", document.getElementById("dialog_cmd_schedule_cmd_cmd").value = ""
}

function cmdScheduleSwitchExactTime() {
    var e = document.getElementById("dialog_cmd_schedule_exact_time").checked;
    1 == e ? (document.getElementById("dialog_cmd_schedule_exact_time_date").disabled = !1, document.getElementById("dialog_cmd_schedule_exact_time_time").disabled = !1, document.getElementById("dialog_cmd_schedule_daily_mon").disabled = !0, document.getElementById("dialog_cmd_schedule_daily_mon_time").disabled = !0, document.getElementById("dialog_cmd_schedule_daily_tue").disabled = !0, document.getElementById("dialog_cmd_schedule_daily_tue_time").disabled = !0, document.getElementById("dialog_cmd_schedule_daily_wed").disabled = !0, document.getElementById("dialog_cmd_schedule_daily_wed_time").disabled = !0, document.getElementById("dialog_cmd_schedule_daily_thu").disabled = !0, document.getElementById("dialog_cmd_schedule_daily_thu_time").disabled = !0, document.getElementById("dialog_cmd_schedule_daily_fri").disabled = !0, document.getElementById("dialog_cmd_schedule_daily_fri_time").disabled = !0, document.getElementById("dialog_cmd_schedule_daily_sat").disabled = !0, document.getElementById("dialog_cmd_schedule_daily_sat_time").disabled = !0, document.getElementById("dialog_cmd_schedule_daily_sun").disabled = !0, document.getElementById("dialog_cmd_schedule_daily_sun_time").disabled = !0) : (document.getElementById("dialog_cmd_schedule_exact_time_date").disabled = !0, document.getElementById("dialog_cmd_schedule_exact_time_time").disabled = !0, document.getElementById("dialog_cmd_schedule_daily_mon").disabled = !1, document.getElementById("dialog_cmd_schedule_daily_mon_time").disabled = !1, document.getElementById("dialog_cmd_schedule_daily_tue").disabled = !1, document.getElementById("dialog_cmd_schedule_daily_tue_time").disabled = !1, document.getElementById("dialog_cmd_schedule_daily_wed").disabled = !1, document.getElementById("dialog_cmd_schedule_daily_wed_time").disabled = !1, document.getElementById("dialog_cmd_schedule_daily_thu").disabled = !1, document.getElementById("dialog_cmd_schedule_daily_thu_time").disabled = !1, document.getElementById("dialog_cmd_schedule_daily_fri").disabled = !1, document.getElementById("dialog_cmd_schedule_daily_fri_time").disabled = !1, document.getElementById("dialog_cmd_schedule_daily_sat").disabled = !1, document.getElementById("dialog_cmd_schedule_daily_sat_time").disabled = !1, document.getElementById("dialog_cmd_schedule_daily_sun").disabled = !1, document.getElementById("dialog_cmd_schedule_daily_sun_time").disabled = !1)
}

function cmdScheduleSwitchProtocol() {
    cmdScheduleObjectList(), cmdScheduleTemplateList()
}

function cmdScheduleTemplateSwitch() {
    var e = document.getElementById("dialog_cmd_schedule_template_list").value;
    "" != e ? (document.getElementById("dialog_cmd_schedule_cmd_gateway").value = cmdData.cmd_templates[e].gateway, document.getElementById("dialog_cmd_schedule_cmd_type").value = cmdData.cmd_templates[e].type, document.getElementById("dialog_cmd_schedule_cmd_cmd").value = cmdData.cmd_templates[e].cmd) : (document.getElementById("dialog_cmd_schedule_cmd_gateway").value = "gprs", document.getElementById("dialog_cmd_schedule_cmd_type").value = "ascii", document.getElementById("dialog_cmd_schedule_cmd_cmd").value = "")
}

function cmdScheduleResetDailyTime() {
    document.getElementById("dialog_cmd_schedule_daily_mon").checked = !1, document.getElementById("dialog_cmd_schedule_daily_mon_time").value = "00:00", document.getElementById("dialog_cmd_schedule_daily_tue").checked = !1, document.getElementById("dialog_cmd_schedule_daily_tue_time").value = "00:00", document.getElementById("dialog_cmd_schedule_daily_wed").checked = !1, document.getElementById("dialog_cmd_schedule_daily_wed_time").value = "00:00", document.getElementById("dialog_cmd_schedule_daily_thu").checked = !1, document.getElementById("dialog_cmd_schedule_daily_thu_time").value = "00:00", document.getElementById("dialog_cmd_schedule_daily_fri").checked = !1, document.getElementById("dialog_cmd_schedule_daily_fri_time").value = "00:00", document.getElementById("dialog_cmd_schedule_daily_sat").checked = !1, document.getElementById("dialog_cmd_schedule_daily_sat_time").value = "00:00", document.getElementById("dialog_cmd_schedule_daily_sun").checked = !1, document.getElementById("dialog_cmd_schedule_daily_sun_time").value = "00:00"
}

function cmdScheduleProperties(e) {
    switch (e) {
        default: var t = e;cmdData.edit_cmd_schedule_id = t;
        var a = {
            cmd: "load_cmd_schedule",
            cmd_id: cmdData.edit_cmd_schedule_id
        };$.ajax({
            type: "POST",
            url: "func/fn_cmd.php",
            data: a,
            dataType: "json",
            cache: !1,
            success: function(e) {
                document.getElementById("dialog_cmd_schedule_active").checked = strToBoolean(e.active), document.getElementById("dialog_cmd_schedule_name").value = e.name;
                var t = strToBoolean(e.exact_time);
                document.getElementById("dialog_cmd_schedule_exact_time").checked = t, cmdScheduleSwitchExactTime(), 1 == t ? (document.getElementById("dialog_cmd_schedule_exact_time_date").value = e.exact_time_dt.substring(0, 10), document.getElementById("dialog_cmd_schedule_exact_time_time").value = e.exact_time_dt.substring(11, 16)) : (document.getElementById("dialog_cmd_schedule_exact_time_date").value = "", document.getElementById("dialog_cmd_schedule_exact_time_time").value = "00:00");
                var a = e.day_time;
                null != a ? (document.getElementById("dialog_cmd_schedule_daily_mon").checked = a.mon, document.getElementById("dialog_cmd_schedule_daily_mon_time").value = a.mon_time, document.getElementById("dialog_cmd_schedule_daily_tue").checked = a.tue, document.getElementById("dialog_cmd_schedule_daily_tue_time").value = a.tue_time, document.getElementById("dialog_cmd_schedule_daily_wed").checked = a.wed, document.getElementById("dialog_cmd_schedule_daily_wed_time").value = a.wed_time, document.getElementById("dialog_cmd_schedule_daily_thu").checked = a.thu, document.getElementById("dialog_cmd_schedule_daily_thu_time").value = a.thu_time, document.getElementById("dialog_cmd_schedule_daily_fri").checked = a.fri, document.getElementById("dialog_cmd_schedule_daily_fri_time").value = a.fri_time, document.getElementById("dialog_cmd_schedule_daily_sat").checked = a.sat, document.getElementById("dialog_cmd_schedule_daily_sat_time").value = a.sat_time, document.getElementById("dialog_cmd_schedule_daily_sun").checked = a.sun, document.getElementById("dialog_cmd_schedule_daily_sun_time").value = a.sun_time) : cmdScheduleResetDailyTime(), cmdScheduleProtocolList(), document.getElementById("dialog_cmd_schedule_protocol").value = e.protocol, cmdScheduleSwitchProtocol();
                var o = document.getElementById("dialog_cmd_schedule_object_list"),
                    i = e.imei.split(",");
                multiselectSetValues(o, i), document.getElementById("dialog_cmd_schedule_template_list").value = "", document.getElementById("dialog_cmd_schedule_cmd_gateway").value = e.gateway, document.getElementById("dialog_cmd_schedule_cmd_type").value = e.type, document.getElementById("dialog_cmd_schedule_cmd_cmd").value = e.cmd
            }
        }),
        $("#dialog_cmd_schedule_properties").dialog("open");
        break;
        case "add":
                cmdData.edit_cmd_schedule_id = !1,
            document.getElementById("dialog_cmd_schedule_active").checked = !0,
            document.getElementById("dialog_cmd_schedule_name").value = "",
            document.getElementById("dialog_cmd_schedule_exact_time").checked = !1,
            cmdScheduleSwitchExactTime(),
            document.getElementById("dialog_cmd_schedule_exact_time_date").value = "",
            document.getElementById("dialog_cmd_schedule_exact_time_time").value = "00:00",
            cmdScheduleResetDailyTime(),
            cmdScheduleProtocolList(),
            document.getElementById("dialog_cmd_schedule_protocol").value = "",
            cmdScheduleSwitchProtocol(),
            document.getElementById("dialog_cmd_schedule_template_list").value = "",
            document.getElementById("dialog_cmd_schedule_cmd_gateway").value = "gprs",
            document.getElementById("dialog_cmd_schedule_cmd_type").value = "ascii",
            document.getElementById("dialog_cmd_schedule_cmd_cmd").value = "",
            $("#dialog_cmd_schedule_properties").dialog("open");
            break;
        case "cancel":
                $("#dialog_cmd_schedule_properties").dialog("close");
            break;
        case "save":
                if (!utilsCheckPrivileges("viewer")) return;
            var o = document.getElementById("dialog_cmd_schedule_name").value;
            if ("" == o) return void notifyBox("error", la.ERROR, la.NAME_CANT_BE_EMPTY, !0);
            var i = document.getElementById("dialog_cmd_schedule_active").checked,
                s = document.getElementById("dialog_cmd_schedule_exact_time").checked,
                n = document.getElementById("dialog_cmd_schedule_exact_time_date").value,
                l = document.getElementById("dialog_cmd_schedule_exact_time_time").value;
            if (1 == s) {
                if ("" == n) return void notifyBox("error", la.ERROR, la.DATE_CANT_BE_EMPTY, !0);
                var d = n + " " + l + ":00"
            } else var d = "";
            var r = {
                mon: document.getElementById("dialog_cmd_schedule_daily_mon").checked,
                mon_time: document.getElementById("dialog_cmd_schedule_daily_mon_time").value,
                tue: document.getElementById("dialog_cmd_schedule_daily_tue").checked,
                tue_time: document.getElementById("dialog_cmd_schedule_daily_tue_time").value,
                wed: document.getElementById("dialog_cmd_schedule_daily_wed").checked,
                wed_time: document.getElementById("dialog_cmd_schedule_daily_wed_time").value,
                thu: document.getElementById("dialog_cmd_schedule_daily_thu").checked,
                thu_time: document.getElementById("dialog_cmd_schedule_daily_thu_time").value,
                fri: document.getElementById("dialog_cmd_schedule_daily_fri").checked,
                fri_time: document.getElementById("dialog_cmd_schedule_daily_fri_time").value,
                sat: document.getElementById("dialog_cmd_schedule_daily_sat").checked,
                sat_time: document.getElementById("dialog_cmd_schedule_daily_sat_time").value,
                sun: document.getElementById("dialog_cmd_schedule_daily_sun").checked,
                sun_time: document.getElementById("dialog_cmd_schedule_daily_sun_time").value
            };r = JSON.stringify(r);
            var _ = document.getElementById("dialog_cmd_schedule_protocol").value,
                c = document.getElementById("dialog_cmd_schedule_object_list");
            if (!multiselectIsSelected(c)) return void notifyBox("error", la.ERROR, la.AT_LEAST_ONE_OBJECT_SELECTED);c = multiselectGetValues(c);
            var g = document.getElementById("dialog_cmd_schedule_cmd_gateway").value,
                m = document.getElementById("dialog_cmd_schedule_cmd_type").value,
                u = document.getElementById("dialog_cmd_schedule_cmd_cmd").value;
            if ("" == u) return void notifyBox("error", la.ERROR, la.COMMAND_CANT_BE_EMPTY, !0);
            if ("hex" == m && (u = u.toUpperCase(), !isHexValid(u))) return void notifyBox("error", la.ERROR, la.COMMAND_HEX_NOT_VALID, !0);
            var a = {
                cmd: "save_cmd_schedule",
                cmd_id: cmdData.edit_cmd_schedule_id,
                name: o,
                active: i,
                exact_time: s,
                exact_time_dt: d,
                day_time: r,
                protocol: _,
                imei: c,
                gateway: g,
                type: m,
                cmd_: u
            };$.ajax({
                type: "POST",
                url: "func/fn_cmd.php",
                data: a,
                cache: !1,
                success: function(e) {
                    "OK" == e && ($("#cmd_schedule_list_grid").trigger("reloadGrid"), $("#dialog_cmd_schedule_properties").dialog("close"), notifyBox("info", la.INFORMATION, la.CHANGES_SAVED_SUCCESSFULLY))
                }
            })
    }
}

function cmdScheduleDelete(e) {
    utilsCheckPrivileges("viewer") && confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE, function(t) {
        if (t) {
            var a = {
                cmd: "delete_cmd_schedule",
                cmd_id: e
            };
            $.ajax({
                type: "POST",
                url: "func/fn_cmd.php",
                data: a,
                success: function(e) {
                    "OK" == e && $("#cmd_schedule_list_grid").trigger("reloadGrid")
                }
            })
        }
    })
}

function cmdScheduleDeleteSelected() {
    if (utilsCheckPrivileges("viewer")) {
        var e = $("#cmd_schedule_list_grid").jqGrid("getGridParam", "selarrrow");
        return "" == e ? void notifyBox("error", la.ERROR, la.NO_ITEMS_SELECTED) : void confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE, function(t) {
            if (t) {
                var a = {
                    cmd: "delete_selected_cmd_schedules",
                    items: e
                };
                $.ajax({
                    type: "POST",
                    url: "func/fn_cmd.php",
                    data: a,
                    success: function(e) {
                        "OK" == e && $("#cmd_schedule_list_grid").trigger("reloadGrid")
                    }
                })
            }
        })
    }
}

function cmdTemplateReload() {
    cmdTemplateLoadData(), $("#cmd_template_list_grid").trigger("reloadGrid")
}

function cmdTemplateLoadData() {
    var e = {
        cmd: "load_cmd_template_data"
    };
    $.ajax({
        type: "POST",
        url: "func/fn_cmd.php",
        data: e,
        dataType: "json",
        cache: !1,
        success: function(e) {
            cmdData.cmd_templates = e, cmdTemplateList(), cmdScheduleTemplateList()
        }
    })
}

function cmdTemplateImport() {
    utilsCheckPrivileges("viewer") && (document.getElementById("load_file").addEventListener("change", cmdTemplateImportCTEFile, !1), document.getElementById("load_file").click())
}

function cmdTemplateExport() {
    if (utilsCheckPrivileges("viewer")) {
        var e = "func/fn_export.php?format=cte";
        window.location = e
    }
}

function cmdTemplateImportCTEFile(e) {
    var t = e.target.files,
        a = new FileReader;
    a.onload = function(e) {
        try {
            var t = $.parseJSON(e.target.result);
            if ("0.1v" == t.cte) {
                var a = t.templates.length;
                if (0 == a) return void notifyBox("info", la.INFORMATION, la.NOTHING_HAS_BEEN_FOUND_TO_IMPORT);
                var o = sprintf(la.TEMPLATES_FOUND, a) + " " + la.ARE_YOU_SURE_YOU_WANT_TO_IMPORT;
                confirmDialog(o, function(t) {
                    if (t) {
                        loadingData(!0);
                        var a = {
                            format: "cte",
                            data: e.target.result
                        };
                        $.ajax({
                            type: "POST",
                            url: "func/fn_import.php",
                            data: a,
                            cache: !1,
                            success: function(e) {
                                loadingData(!1), "OK" == e && cmdTemplateReload()
                            },
                            error: function() {
                                loadingData(!1)
                            }
                        })
                    }
                })
            } else notifyBox("error", la.ERROR, la.INVALID_FILE_FORMAT)
        } catch (i) {
            notifyBox("error", la.ERROR, la.INVALID_FILE_FORMAT)
        }
        document.getElementById("load_file").value = ""
    }, a.readAsText(t[0], "UTF-8"), this.removeEventListener("change", cmdTemplateImportCTEFile, !1)
}

function cmdTemplateProtocolList() {
    var e = document.getElementById("dialog_cmd_template_protocol").value,
        t = document.getElementById("dialog_cmd_template_hide_unsed_protocols").checked,
        a = document.getElementById("dialog_cmd_template_protocol");
    if (a.options.length = 0, 1 == t)
        for (var o = getAllProtocolsArray(), i = 0; i < o.length; i++) "" != o[i] && a.options.add(new Option(o[i], o[i]));
    else
        for (var s in gsValues.protocol_list) {
            var n = gsValues.protocol_list[s];
            a.options.add(new Option(n.name, n.name))
        }
    sortSelectList(a), a.options.add(new Option(la.ALL_PROTOCOLS, ""), 0), document.getElementById("dialog_cmd_template_protocol").value = e
}

function cmdTemplateProperties(e) {
    switch (e) {
        default: var t = e;cmdData.edit_cmd_template_id = t,
        document.getElementById("dialog_cmd_template_hide_unsed_protocols").checked = !1,
        cmdTemplateProtocolList(),
        document.getElementById("dialog_cmd_template_name").value = cmdData.cmd_templates[t].name,
        document.getElementById("dialog_cmd_template_protocol").value = cmdData.cmd_templates[t].protocol,
        document.getElementById("dialog_cmd_template_gateway").value = cmdData.cmd_templates[t].gateway,
        document.getElementById("dialog_cmd_template_type").value = cmdData.cmd_templates[t].type,
        document.getElementById("dialog_cmd_template_cmd").value = cmdData.cmd_templates[t].cmd,
        $("#dialog_cmd_template_properties").dialog("open");
        break;
        case "add":
                cmdData.edit_cmd_template_id = !1,
            document.getElementById("dialog_cmd_template_hide_unsed_protocols").checked = !1,
            cmdTemplateProtocolList(),
            document.getElementById("dialog_cmd_template_name").value = "",
            document.getElementById("dialog_cmd_template_protocol").value = "",
            document.getElementById("dialog_cmd_template_gateway").value = "gprs",
            document.getElementById("dialog_cmd_template_type").value = "ascii",
            document.getElementById("dialog_cmd_template_cmd").value = "",
            $("#dialog_cmd_template_properties").dialog("open");
            break;
        case "cancel":
                $("#dialog_cmd_template_properties").dialog("close");
            break;
        case "save":
                if (!utilsCheckPrivileges("viewer")) return;
            var a = document.getElementById("dialog_cmd_template_name").value,
                o = document.getElementById("dialog_cmd_template_protocol").value,
                i = document.getElementById("dialog_cmd_template_gateway").value,
                s = document.getElementById("dialog_cmd_template_type").value,
                n = document.getElementById("dialog_cmd_template_cmd").value;
            if ("" == a) return void notifyBox("error", la.ERROR, la.NAME_CANT_BE_EMPTY, !0);
            if ("" == n) return void notifyBox("error", la.ERROR, la.COMMAND_CANT_BE_EMPTY, !0);
            if ("hex" == s && (n = n.toUpperCase(), !isHexValid(n))) return void notifyBox("error", la.ERROR, la.COMMAND_HEX_NOT_VALID, !0);
            var l = {
                cmd: "save_cmd_template",
                cmd_id: cmdData.edit_cmd_template_id,
                name: a,
                protocol: o,
                gateway: i,
                type: s,
                cmd_: n
            };$.ajax({
                type: "POST",
                url: "func/fn_cmd.php",
                data: l,
                cache: !1,
                success: function(e) {
                    "OK" == e && (cmdTemplateReload(), $("#dialog_cmd_template_properties").dialog("close"), notifyBox("info", la.INFORMATION, la.CHANGES_SAVED_SUCCESSFULLY))
                }
            })
    }
}

function cmdTemplateDelete(e) {
    utilsCheckPrivileges("viewer") && confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE, function(t) {
        if (t) {
            var a = {
                cmd: "delete_cmd_template",
                cmd_id: e
            };
            $.ajax({
                type: "POST",
                url: "func/fn_cmd.php",
                data: a,
                success: function(e) {
                    "OK" == e && cmdTemplateReload()
                }
            })
        }
    })
}

function cmdTemplateDeleteSelected() {
    if (utilsCheckPrivileges("viewer")) {
        var e = $("#cmd_template_list_grid").jqGrid("getGridParam", "selarrrow");
        return "" == e ? void notifyBox("error", la.ERROR, la.NO_ITEMS_SELECTED) : void confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE, function(t) {
            if (t) {
                var a = {
                    cmd: "delete_selected_cmd_templates",
                    items: e
                };
                $.ajax({
                    type: "POST",
                    url: "func/fn_cmd.php",
                    data: a,
                    success: function(e) {
                        "OK" == e && cmdTemplateReload()
                    }
                })
            }
        })
    }
}

function eventsReloadData() {
    eventsCheckForNew()
}

function eventsLoadData() {
    clearTimeout(timer_eventsLoadData), timer_eventsLoadData = setTimeout("eventsLoadData();", 1e3 * gsValues.event_refresh), eventsCheckForNew()
}

var notifyshow=true;
function eventsCheckForNew() {
    var e = {
        cmd: "load_last_event"
    };
    $.ajax({
        type: "POST",
        url: "func/fn_events.php",
        data: e,
        dataType: "json",
        success: function(e) {
            if (0 != e) {
                if (eventsData.last_id < e.event_id && 1 == eventsData.events_loaded && void 0 != settingsObjectData[e.imei] && "true" == settingsObjectData[e.imei].active) {
                	if(notifyshow==true)
                	{ 
                    var t = !1,
                        a = !1;
                    "true" == e.notify_arrow && (t = e.notify_arrow_color), "true" == e.notify_ohc && (a = e.notify_ohc_color), objectSetStatusEvent(e.imei, t, a);
                    var o = e.notify_system.split(",");
                    if ("true" == o[0]) {
                        var i = e.lat,
                            s = e.lng,
                            n = urlPosition(i, s),
                            l = '<div class="row">';
                        l += '<div class="row2"><div class="width40"><strong>' + la.OBJECT + ':</strong></div><div class="width60">' + e.name + "</div></div>", l += '<div class="row2"><div class="width40"><strong>' + la.EVENT + ':</strong></div><div class="width60">' + e.event_desc + "</div></div>", l += '<div class="row2"><div class="width40"><strong>' + la.POSITION + ':</strong></div><div class="width60">' + n + "</div></div>", l += '<div class="row2"><div class="width40"><strong>' + la.TIME + ':</strong></div><div class="width60">' + e.dt_tracker + "</div></div>", l += "</div>", l += '<div class="row">', l += '<center><a href="#" onclick="eventsShowEvent(' + e.event_id + ');">Show event</a></center>', l += "</div>";
                        var d = !1;
                        if ("true" == o[1] && (d = !0), notifyBox("error", la.NEW_EVENT, l, d), "true" == o[2]) {
                            void 0 == o[3] && (o[3] = "alarm1.mp3");
                            var r = new Audio("snd/" + o[3]);
                            r.play()
                        }
                    }
                	}
                }
                eventsData.last_id = e.event_id
            }
            $("#side_panel_events_event_list_grid").trigger("reloadGrid"), eventsData.events_loaded = !0
        }
    })
    var e = {
        cmd: "load_dstudent_last_event"
    };
    $.ajax({
        type: "POST",
        url: "func/fn_events.php",
        data: e,
        dataType: "json",
        success: function(e) {
            if (0 != e) {
                if (eventsData.dstudent_last_id < e.id && 1 == eventsData.events_loaded) {
                    if(notifyshow==true)
                    { 
                    var t = !1,
                        a = !1;
                    "true" == e.notify_arrow && (t = e.notify_arrow_color), "true" == e.notify_ohc && (a = e.notify_ohc_color), objectSetStatusEvent(e.imei, t, a);
                    var o = e.notify_system.split(",");
                    if ("true" == o[0]) {
                        var i = e.lat,
                            s = e.lng,
                            n = urlPosition(i, s),
                            l = '<div class="row">';
                        l += '<div class="row2"><div class="width40"><strong>' + la.NAME + ':</strong></div><div class="width60">' + e.name + "</div></div>", l += '<div class="row2"><div class="width40"><strong>' + la.EVENT + ':</strong></div><div class="width60">' + e.event_desc + "</div></div>", l += '<div class="row2"><div class="width40"><strong>' + la.POSITION + ':</strong></div><div class="width60">' + n + "</div></div>", l += '<div class="row2"><div class="width40"><strong>' + la.TIME + ':</strong></div><div class="width60">' + e.dt_tracker + "</div></div>", l += "</div>";
                        var d = !1;
                        if ("true" == o[1] && (d = !0), notifyBox("error", la.NEW_EVENT, l, d), "true" == o[2]) {
                            void 0 == o[3] && (o[3] = "alarm1.mp3");
                            var r = new Audio("snd/" + o[3]);
                            r.play()
                        }
                    }
                    }
                }
                eventsData.dstudent_last_id = e.id
            }
            $("#side_panel_events_event_list_grid").trigger("reloadGrid"), eventsData.events_loaded = !0
        }
    })
}

function eventsDeleteAll() {
    utilsCheckPrivileges("viewer") && utilsCheckPrivileges("subuser") && confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE_ALL_EVENTS, function(e) {
        if (e) {
            var t = {
                cmd: "delete_all_events"
            };
            $.ajax({
                type: "POST",
                url: "func/fn_events.php",
                data: t,
                success: function(e) {
                    "OK" == e && $("#side_panel_events_event_list_grid").trigger("reloadGrid")
                }
            })
        }
    })
}

function eventsShowEvent(e) {
    var t = {
        cmd: "load_event_data",
        event_id: e
    };
    $.ajax({
        type: "POST",
        url: "func/fn_events.php",
        data: t,
        dataType: "json",
        cache: !1,
        success: function(e) {
            showExtraData("event", e.imei, e);
            var t = e.lat,
                a = e.lng;
            // geocoderGetAddress(t, a, function(o) {
                var i = e.imei,
                    s = "<div id='showeventAddress'><a onclick='showobjectaddress("+t+","+"\""+a+"\""+","+"\"showeventAddress\""+")'>View Address</a></div>",
                    // s = o,
                    n = urlPosition(t, a),
                    l = e.params,
                    d = "",
                    r = new Array;
                for (var _ in settingsObjectData[i].sensors) r.push(settingsObjectData[i].sensors[_]);
                var c = sortArrayByElement(r, "name");
                for (var _ in c) {
                    var g = c[_];
                    if ("true" == g.popup) {
                        var m = getSensorValue(l, g);
                        d += "<tr><td><strong>" + g.name + ":</strong></td><td>" + m.value_full + "</td></tr>"
                    }
                }
                var u = "<table>					<tr><td><strong>" + la.OBJECT + ":</strong></td><td>" + e.name + "</td></tr>					<tr><td><strong>" + la.EVENT + ":</strong></td><td>" + e.event_desc + "</td></tr>					<tr><td><strong>" + la.ADDRESS + ":</strong></td><td>" + s + "</td></tr>					<tr><td><strong>" + la.POSITION + ":</strong></td><td>" + n + "</td></tr>					<tr><td><strong>" + la.ALTITUDE + ":</strong></td><td>" + e.altitude + " " + la.UNIT_HEIGHT + "</td></tr>					<tr><td><strong>" + la.ANGLE + ":</strong></td><td>" + e.angle + " &deg;</td></tr>					<tr><td><strong>" + la.SPEED + ":</strong></td><td>" + e.speed + " " + la.UNIT_SPEED + "</td></tr>					<tr><td><strong>" + la.TIME + ":</strong></td><td>" + e.dt_tracker + "</td></tr>",
                    p = getObjectOdometer(i, l); - 1 != p && (u += "<tr><td><strong>" + la.ODOMETER + ":</strong></td><td>" + p + " " + la.UNIT_DISTANCE + "</td></tr>");
                var v = getObjectEngineHours(i, l); - 1 != v && (u += "<tr><td><strong>" + la.ENGINE_HOURS + ":</strong></td><td>" + v + "</td></tr>");
                var y = u + d;
                u += "</table>", y += "</table>", addPopupToMap(t, a, [0, 0], u, y), map.panTo({
                    lat: t,
                    lng: a
                })
            // })
        }
    })
}

function initMap() {
    map = L.map("map", {
        minZoom: gsValues.map_min_zoom,
        maxZoom: gsValues.map_max_zoom,
        editable: !0,
        zoomControl: !1
    }), initSelectList("map_layer_list"), defineMapLayers(), mapLayers.realtime = createCluster("objects"), mapLayers.realtime.addTo(map), mapLayers.history = L.layerGroup(), mapLayers.history.addTo(map), mapLayers.places_markers = createCluster("markers"), mapLayers.places_markers.addTo(map), mapLayers.places_zones = L.layerGroup(), mapLayers.places_zones.addTo(map), mapLayers.places_routes = L.layerGroup(), mapLayers.places_routes.addTo(map), map.addControl(L.control.zoom({
        zoomInText: "",
        zoomOutText: "",
        zoomInTitle: la.ZOOM_IN,
        zoomOutTitle: la.ZOOM_OUT
    })), L.MapViewControls = mapViewControls(), map.addControl(new L.MapViewControls), L.MapViewControls = mapToolControls(), map.addControl(new L.MapViewControls), map.setView([gsValues.map_lat, gsValues.map_lng], gsValues.map_zoom), switchMapLayer(gsValues.map_layer), gsValues.map_objects || document.getElementById("map_control_objects").click(), gsValues.map_markers || document.getElementById("map_control_markers").click(), gsValues.map_routes || document.getElementById("map_control_routes").click(), gsValues.map_zones || document.getElementById("map_control_zones").click(), map.on("zoomend", function() {
        historyRouteToggleDataPoints()
    });
    var e = settingsUserData.map_is,
        t = 28 * e,
        a = 28 * e,
        o = 14 * e,
        i = 14 * e,
        t = 28 * e,
        a = 28 * e,
        o = 14 * e,
        i = 14 * e;
    mapMarkerIcons.arrow_black = L.icon({
        iconUrl: "img/markers/arrow-black.svg",
        iconSize: [t, a],
        iconAnchor: [o, i],
        popupAnchor: [0, 0]
    }), mapMarkerIcons.arrow_blue = L.icon({
        iconUrl: "img/markers/arrow-blue.svg",
        iconSize: [t, a],
        iconAnchor: [o, i],
        popupAnchor: [0, 0]
    }), mapMarkerIcons.arrow_green = L.icon({
        iconUrl: "img/markers/arrow-green.svg",
        iconSize: [t, a],
        iconAnchor: [o, i],
        popupAnchor: [0, 0]
    }), mapMarkerIcons.arrow_grey = L.icon({
        iconUrl: "img/markers/arrow-grey.svg",
        iconSize: [t, a],
        iconAnchor: [o, i],
        popupAnchor: [0, 0]
    }), mapMarkerIcons.arrow_orange = L.icon({
        iconUrl: "img/markers/arrow-orange.svg",
        iconSize: [t, a],
        iconAnchor: [o, i],
        popupAnchor: [0, 0]
    }), mapMarkerIcons.arrow_purple = L.icon({
        iconUrl: "img/markers/arrow-purple.svg",
        iconSize: [t, a],
        iconAnchor: [o, i],
        popupAnchor: [0, 0]
    }), mapMarkerIcons.arrow_red = L.icon({
        iconUrl: "img/markers/arrow-red.svg",
        iconSize: [t, a],
        iconAnchor: [o, i],
        popupAnchor: [0, 0]
    }), mapMarkerIcons.arrow_yellow = L.icon({
        iconUrl: "img/markers/arrow-yellow.svg",
        iconSize: [t, a],
        iconAnchor: [o, i],
        popupAnchor: [0, 0]
    }), t = 28 * e, a = 28 * e, o = 14 * e, i = 28 * e, mapMarkerIcons.route_start = L.icon({
        iconUrl: "img/markers/route-start.svg",
        iconSize: [t, a],
        iconAnchor: [o, i],
        popupAnchor: [0, 0]
    }), mapMarkerIcons.route_end = L.icon({
        iconUrl: "img/markers/route-end.svg",
        iconSize: [t, a],
        iconAnchor: [o, i],
        popupAnchor: [0, 0]
    }), mapMarkerIcons.route_stop = L.icon({
        iconUrl: "img/markers/route-stop.svg",
        iconSize: [t, a],
        iconAnchor: [o, i],
        popupAnchor: [0, 0]
    }), mapMarkerIcons.route_event = L.icon({
        iconUrl: "img/markers/route-event.svg",
        iconSize: [t, a],
        iconAnchor: [o, i],
        popupAnchor: [0, 0]
    }), mapMarkerIcons.route_data_point = L.icon({
        iconUrl: "img/markers/route-data-point.svg",
        iconSize: [8, 8],
        iconAnchor: [4, 4],
        popupAnchor: [0, 0]
    })
}

function initGui() {
    $("#side_panel_objects_action_menu").menu({
        role: "listbox",
        select: function(e, t) {
            var a = menuOnItem,
                o = t.item.children().attr("tag");
            "edit" == o && utilsCheckPrivileges("subuser") && utilsCheckPrivileges("obj_edit") && loadSettings("objects", function() {
                settingsObjectEdit(a)
            }), "cmd" == o && (document.getElementById("cmd_object_list").value = a, cmdOpen()), "follow" == o && utilsFollowObject(a, !1), "follow_new" == o && utilsFollowObject(a, !0),"video_new" == o && utilsVideoObject(a, !0), "street_view" == o && utilsStreetViewObject(a, !1), "street_view_new" == o && utilsStreetViewObject(a, !0), "shlh" == o && (document.getElementById("side_panel_history_filter").value = 1), "sht" == o && (document.getElementById("side_panel_history_filter").value = 2), "shy" == o && (document.getElementById("side_panel_history_filter").value = 3), "shb2" == o && (document.getElementById("side_panel_history_filter").value = 4), "shb3" == o && (document.getElementById("side_panel_history_filter").value = 5), "shtw" == o && (document.getElementById("side_panel_history_filter").value = 6), "shlw" == o && (document.getElementById("side_panel_history_filter").value = 7), "shtm" == o && (document.getElementById("side_panel_history_filter").value = 8), "shlm" == o && (document.getElementById("side_panel_history_filter").value = 9), "sh" == o.substring(0, 2) && (document.getElementById("side_panel_history_object_list").value = a, switchHistoryReportsDateFilter("history"),/*update by vetrivel.NR*/switchHistoryReportsDateFilter("ambulance"), historyLoadRoute())
        }
    }), $("#side_panel_objects_action_menu").hide(), $("#side_panel_history_import_export_action_menu").menu({
        role: "listbox"
    }), $("#side_panel_history_import_export_action_menu").hide(), $("#side_panel_history_import_export_action_menu_button").click(function() {
        return $("#side_panel_history_import_export_action_menu").toggle().position({
            my: "left top",
            at: "left bottom+2",
            of: this
        }), $(document).one("click", function() {
            $("#side_panel_history_import_export_action_menu").hide()
        }), !1
    }), $("#report_action_menu").menu({
        role: "listbox",
        select: function(e, t) {
            var a = menuOnItem,
                o = t.item.children().attr("tag");
            if ("grlh" == o) {
                var i = moment().format("YYYY-MM-DD"),
                    s = moment().format("YYYY-MM-DD");
                i += " " + moment().subtract("hour", 1).format("HH") + ":" + moment().subtract("hour", 1).format("mm") + ":00", s += " " + moment().format("HH") + ":" + moment().format("mm") + ":00"
            }
            if ("grt" == o) var i = moment().format("YYYY-MM-DD") + " 00:00:00",
                s = moment().add("days", 1).format("YYYY-MM-DD") + " 00:00:00";
            if ("gry" == o) var i = moment().subtract("days", 1).format("YYYY-MM-DD") + " 00:00:00",
                s = moment().format("YYYY-MM-DD") + " 00:00:00";
            if ("grb2" == o) var i = moment().subtract("days", 2).format("YYYY-MM-DD") + " 00:00:00",
                s = moment().subtract("days", 1).format("YYYY-MM-DD") + " 00:00:00";
            if ("grb3" == o) var i = moment().subtract("days", 3).format("YYYY-MM-DD") + " 00:00:00",
                s = moment().subtract("days", 2).format("YYYY-MM-DD") + " 00:00:00";
            if ("grtw" == o) var i = moment().isoWeekday(1).format("YYYY-MM-DD") + " 00:00:00",
                s = moment().add("days", 1).format("YYYY-MM-DD") + " 00:00:00";
            if ("grlw" == o) var i = moment().isoWeekday(1).subtract("week", 1).format("YYYY-MM-DD") + " 00:00:00",
                s = moment().isoWeekday(1).format("YYYY-MM-DD") + " 00:00:00";
            if ("grtm" == o) var i = moment().startOf("month").format("YYYY-MM-DD") + " 00:00:00",
                s = moment().add("days", 1).format("YYYY-MM-DD") + " 00:00:00";
            if ("grlm" == o) var i = moment().startOf("month").subtract("month", 1).format("YYYY-MM-DD") + " 00:00:00",
                s = moment().startOf("month").format("YYYY-MM-DD") + " 00:00:00";
            if ("gr" == o.substring(0, 2)) {
            	
            	if(typeof(reportsData.reports[a]) != "undefined" && reportsData.reports[a] !== null)
            	{
                var n = {
                    cmd: "report",
                    name: reportsData.reports[a].name,
                    type: reportsData.reports[a].type,
                    format: reportsData.reports[a].format,
                    show_coordinates: reportsData.reports[a].show_coordinates,
                    show_addresses: reportsData.reports[a].show_addresses,
                    zones_addresses: reportsData.reports[a].zones_addresses,
                    stop_duration: reportsData.reports[a].stop_duration,
                    speed_limit: reportsData.reports[a].speed_limit,
                    imei: reportsData.reports[a].imei,
                    zone_ids: reportsData.reports[a].zone_ids,
                    zone_idsv: reportsData.reports[a].zone_idsv,
                    sensor_names: reportsData.reports[a].sensor_names,
                    data_items: reportsData.reports[a].data_items,
                    dtf: i,
                    dtt: s,
                    rpt_type : "G"
                };
                reportGenerate(n)
            	}
            	else
            	{
            		 var R = {
            	                cmd: "report",
            	                name: reportsData.reportsamb[a].name,
                                type: reportsData.reportsamb[a].type,
                                format: reportsData.reportsamb[a].format,
                                show_coordinates: reportsData.reportsamb[a].show_coordinates,
                                show_addresses: reportsData.reportsamb[a].show_addresses,
                                zones_addresses: reportsData.reportsamb[a].zones_addresses,
                                stop_duration: reportsData.reportsamb[a].stop_duration,
                                speed_limit: reportsData.reportsamb[a].speed_limit,
                                imei: reportsData.reportsamb[a].imei,
                                zone_ids: reportsData.reportsamb[a].zone_ids,
                                zone_idsv: reportsData.reportsamb[a].zone_idsv,
                                sensor_names: reportsData.reportsamb[a].sensor_names,
                                data_items: reportsData.reportsamb[a].data_items,
                                dtf: i,
                                dtt: s,
            	                rpt_type : "A",
            	                emergency:reportsData.reportsamb[a].emergency,
            	                address:reportsData.reportsamb[a].address,
            	                peoplecount:reportsData.reportsamb[a].people_count,
            	                phone:reportsData.reportsamb[a].phone,
            	                patientname:reportsData.reportsamb[a].patient_name,
            	                age_from:reportsData.reportsamb[a].age_from,
            	                age_to:reportsData.reportsamb[a].age_to,
            	                gender:reportsData.reportsamb[a].gender,
            	                breath:reportsData.reportsamb[a].breath,
            	                conscious:reportsData.reportsamb[a].conscious,
            	                book_status:reportsData.reportsamb[a].book_status,
            	                crewtime:reportsData.reportsamb[a].crewtime,
            	                taken:reportsData.reportsamb[a].stop_duration,
            	                book_by:reportsData.reportsamb[a].book_by
            	            };reportGenerateAmb(R)
            	}
            }
        }
    }), $("#report_action_menu").hide(), $(".inputbox-calendar").datepicker({
        changeMonth: !0,
        changeYear: !0,
        dateFormat: "yy-mm-dd",
        firstDay: 1,
        dayNamesMin: [la.DAY_SUNDAY_S, la.DAY_MONDAY_S, la.DAY_TUESDAY_S, la.DAY_WEDNESDAY_S, la.DAY_THURSDAY_S, la.DAY_FRIDAY_S, la.DAY_SATURDAY_S],
        monthNames: [la.MONTH_JANUARY, la.MONTH_FEBRUARY, la.MONTH_MARCH, la.MONTH_APRIL, la.MONTH_MAY, la.MONTH_JUNE, la.MONTH_JULY, la.MONTH_AUGUST, la.MONTH_SEPTEMBER, la.MONTH_OCTOBER, la.MONTH_NOVEMBER, la.MONTH_DECEMBER],
        monthNamesShort: [la.MONTH_JANUARY_S, la.MONTH_FEBRUARY_S, la.MONTH_MARCH_S, la.MONTH_APRIL_S, la.MONTH_MAY_S, la.MONTH_JUNE_S, la.MONTH_JULY_S, la.MONTH_AUGUST_S, la.MONTH_SEPTEMBER_S, la.MONTH_OCTOBER_S, la.MONTH_NOVEMBER_S, la.MONTH_DECEMBER_S]
    }), $(".inputbox-calendar-mmdd").datepicker({
        changeMonth: !0,
        changeYear: !0,
        dateFormat: "mm-dd",
        firstDay: 1,
        dayNamesMin: [la.DAY_SUNDAY_S, la.DAY_MONDAY_S, la.DAY_TUESDAY_S, la.DAY_WEDNESDAY_S, la.DAY_THURSDAY_S, la.DAY_FRIDAY_S, la.DAY_SATURDAY_S],
        monthNames: [la.MONTH_JANUARY, la.MONTH_FEBRUARY, la.MONTH_MARCH, la.MONTH_APRIL, la.MONTH_MAY, la.MONTH_JUNE, la.MONTH_JULY, la.MONTH_AUGUST, la.MONTH_SEPTEMBER, la.MONTH_OCTOBER, la.MONTH_NOVEMBER, la.MONTH_DECEMBER],
        monthNamesShort: [la.MONTH_JANUARY_S, la.MONTH_FEBRUARY_S, la.MONTH_MARCH_S, la.MONTH_APRIL_S, la.MONTH_MAY_S, la.MONTH_JUNE_S, la.MONTH_JULY_S, la.MONTH_AUGUST_S, la.MONTH_SEPTEMBER_S, la.MONTH_OCTOBER_S, la.MONTH_NOVEMBER_S, la.MONTH_DECEMBER_S]
    }), $("#side_panel,	  #side_panel_places,	  #bottom_panel_tabs,	  #cmd_tabs,	  #settings_main,	  #settings_main_objects_groups_drivers,	  #settings_object,	  #settings_object_edit_select_icon_tabs,	  #settings_event,	  #reports_tabs,	  #report_tabs,	  #places_marker_icon_tabs,#boarding_main,#rfidtrip_main,#reports_tabs_amb").tabs({
        show: function() {
            var e = $(ui.panel);
            $(".content:visible").effect(function() {
                e.fadeIn()
            })
        }
    }), $("#dialog_notify").dialog({
        autoOpen: !1,
        width: "250px",
        height: "auto",
        minHeight: "auto",
        modal: !0,
        resizable: !1,
        draggable: !1,
        dialogClass: "dialog-notify-titlebar"
    }), $("#dialog_confirm").dialog({
        autoOpen: !1,
        width: "320px",
        height: "auto",
        minHeight: "auto",
        modal: !0,
        resizable: !1,
        draggable: !1,
        dialogClass: "dialog-notify-titlebar"
    }), $("#dialog_about").dialog({
        autoOpen: !1,
        width: "480",
        height: "auto",
        minHeight: "auto",
        modal: !0,
        resizable: !1
    }), $("#dialog_show_point").dialog({
        autoOpen: !1,
        width: "250",
        height: "auto",
        minHeight: "auto",
        position: {
            my: "left top",
            at: "left+412 top+47"
        },
        resizable: !1
    }), $("#dialog_address_search").dialog({
        autoOpen: !1,
        width: "250",
        height: "auto",
        minHeight: "auto",
        position: {
            my: "left top",
            at: "left+412 top+47"
        },
        resizable: !1
    }), $("#dialog_address_search_ambulance").dialog({
        autoOpen: !1,
        width: "250",
        height: "auto",
        minHeight: "auto",
        position: {
            my: "left top",
            at: "left+412 top+47"
        },
        resizable: !1,
        open: function() {
            $(this).parent().children().children(".ui-dialog-titlebar-close").remove()
        }
    }), $("#dialog_address_search_dispensor").dialog({
        autoOpen: !1,
        width: "250",
        height: "auto",
        minHeight: "auto",
        position: {
            my: "left top",
            at: "left+412 top+47"
        },
        resizable: !1,
        open: function() {
            $(this).parent().children().children(".ui-dialog-titlebar-close").remove()
        }
    }), $("#dialog_cmd").dialog({
        autoOpen: !1,
        width: "1050",
        direction: "right",
        height: "auto",
        minHeight: "auto",
        modal: !0,
        resizable: !1
    }), $("#dialog_cmd_schedule_properties").dialog({
        autoOpen: !1,
        width: "700",
        height: "auto",
        minHeight: "auto",
        modal: !0,
        resizable: !1
    }), $("#dialog_cmd_template_properties").dialog({
        autoOpen: !1,
        width: "450",
        height: "auto",
        minHeight: "auto",
        modal: !0,
        resizable: !1
    }),
    //CODE DONE BY VETRIVEL
    $("#dialog_object_commandnew").dialog({
        autoOpen: false,
        width: "1050px",
        height: "auto",
        minHeight: "auto",
        modal: !0,
        resizable: false
    }) ,$("#dialog_booking").dialog({
        autoOpen: false,
        width: "940px",
        height: "auto",
        minHeight: "auto",
        modal: !0,
        resizable: false,
        open: function() {
            $(this).parent().css({"width":"100%","height":"100%","left":"0px","top":"0px"}),
            $(this).css({"height":"100%"});;
            var $grid = $("#amb_booking_list_grid"),
            newWidth = $grid.closest(".ui-jqgrid").parent().width();
            $grid.jqGrid("setGridWidth", newWidth, true);
        }
       
    }) ,$("#dialog_booking_vehicle").dialog({
        autoOpen: false,
        width: "740px",
        height: "auto",
        minHeight: "auto",
        modal: !0,
        resizable: true
    })
    ,
    $("#dialog_image_gallery").dialog({
        autoOpen: !1,
        width: "992",
        height: "600",
        minHeight: "auto",
        modal: !0,
        resizable: !1
    }), $("#dialog_chat").dialog({
        autoOpen: !1,
        width: "992",
        height: "500",
        minWidth: 500,
        minHeight: 300,
        modal: !0,
        resizable: !0,
        close: function() {
            chatClose()
        }
    }), $("#dialog_reports").dialog({
        autoOpen: !1,
        width: "992",
        height: "auto",
        minHeight: "auto",
        modal: !0,
        resizable: !1
    }), $("#dialog_report_properties").dialog({
        autoOpen: !1,
        width: "1150",
        height: "auto",
        minHeight: "auto",
        modal: !0,
        resizable: !1
    }), $("#dialog_rilogbook").dialog({
        autoOpen: !1,
        width: "992",
        height: "700",
        minWidth: 992,
        minHeight: 350,
        resizable: !0,
        modal: !0,
        close: function() {
            rilogbookClose()
        }
    }), $("#dialog_dtc").dialog({
        autoOpen: !1,
        width: "992",
        height: "500",
        minWidth: 992,
        minHeight: 350,
        modal: !0,
        resizable: !0,
        close: function() {
            dtcClose()
        }
    }), $("#dialog_settings").dialog({
        autoOpen: !1,
        width: "1050",
        height: "auto",
        minHeight: "auto",
        modal: !0,
        resizable: !1,
        close: function() {
            settingsClose()
        }
    }), $("#dialog_settings_object_add").dialog({
        autoOpen: !1,
        width: "300",
        height: "auto",
        minHeight: "auto",
        modal: !0,
        resizable: !1
    }), $("#dialog_settings_object_edit").dialog({
        autoOpen: !1,
        width: "900",
        height: "auto",
        minHeight: "auto",
        modal: !0,
        resizable: !1
    }), $("#dialog_settings_object_duplicate").dialog({
        autoOpen: !1,
        width: "300",
        height: "auto",
        minHeight: "auto",
        modal: !0,
        resizable: !1
    }), $("#dialog_settings_object_edit_select_icon").dialog({
        autoOpen: !1,
        width: "412",
        height: "auto",
        minHeight: "auto",
        modal: !0,
        resizable: !1
    }), $("#dialog_settings_object_group_properties").dialog({
        autoOpen: !1,
        width: "300",
        height: "auto",
        minHeight: "auto",
        modal: !0,
        resizable: !1
    }), $("#dialog_settings_object_driver_properties").dialog({
        autoOpen: !1,
        width: "500",
        height: "auto",
        minHeight: "auto",
        modal: !0,
        resizable: !1
    }), $("#dialog_settings_object_passenger_properties").dialog({
        autoOpen: !1,
        width: "400",
        height: "auto",
        minHeight: "auto",
        modal: !0,
        resizable: !1
    }), $("#dialog_settings_object_trailer_properties").dialog({
        autoOpen: !1,
        width: "400",
        height: "auto",
        minHeight: "auto",
        modal: !0,
        resizable: !1
    }), $("#dialog_settings_object_sensor_properties").dialog({
        autoOpen: !1,
        width: "600",
        height: "auto",
        minHeight: "auto",
        modal: !0,
        resizable: !1
    }), $("#dialog_settings_object_service_properties").dialog({
        autoOpen: !1,
        width: "720",
        height: "auto",
        minHeight: "auto",
        modal: !0,
        resizable: !1
    }), $("#dialog_settings_object_custom_field_properties").dialog({
        autoOpen: !1,
        width: "350",
        height: "auto",
        minHeight: "auto",
        modal: !0,
        resizable: !1
    }), $("#dialog_settings_event_properties").dialog({
        autoOpen: !1,
        width: "730",
        height: "auto",
        minHeight: "auto",
        modal: !0,
        resizable: !1,
        open: function() {
            $("#settings_event").tabs("option", "active", 0)
        }
    }), $("#dialog_settings_template_properties").dialog({
        autoOpen: !1,
        width: "750",
        height: "auto",
        minHeight: "auto",
        modal: !0,
        resizable: !1
    }), $("#dialog_settings_subaccount_properties").dialog({
        autoOpen: !1,
		width: "950",
		height: "630",
		minHeight: "630",
		modal: !0,
		resizable: !1
    }), $("#dialog_places_groups").dialog({
        autoOpen: !1,
        width: "750",
        height: "auto",
        minHeight: "auto",
        modal: !0,
        resizable: !1,
        close: function() {
            placesGroupClose()
        }
    }), $("#dialog_places_group_properties").dialog({
        autoOpen: !1,
        width: "300",
        height: "auto",
        minHeight: "auto",
        modal: !0,
        resizable: !1
    }), $("#dialog_places_marker_properties").dialog({
        autoOpen: !1,
        width: "324",
        height: "auto",
        minHeight: "auto",
        resizable: !1,
        draggable: !1,
        position: {
            my: "left top",
            at: "left+1012px top+52px"
        },
        closeOnEscape: !1,
        open: function() {
            $(this).parent().children().children(".ui-dialog-titlebar-close").remove()
        }
    }), $("#dialog_places_zone_properties").dialog({
        autoOpen: !1,
        width: "265",
        height: "auto",
        minHeight: "auto",
        resizable: !1,
        draggable: !1,
        position: {
            my: "left top",
            at: "left+1012px top+52"
        },
        closeOnEscape: !1,
        open: function() {
            $(this).parent().children().children(".ui-dialog-titlebar-close").remove()
        }
    }), $("#dialog_places_route_properties").dialog({
        autoOpen: !1,
        width: "265",
        height: "auto",
        minHeight: "auto",
        resizable: !1,
        draggable: !1,
        position: {
            my: "left top",
            at: "left+1012px top+52"
        },
        closeOnEscape: !1,
        open: function() {
            $(this).parent().children().children(".ui-dialog-titlebar-close").remove()
        }
    }), $("#dialog_billing").dialog({
        autoOpen: !1,
        width: "750",
        height: "auto",
        minHeight: "auto",
        modal: !0,
        resizable: !1,
        dialogClass: "dialog-billing-titlebar",
        close: function() {
            billingClose()
        }
    }), $("#dialog_billing_plan_use").dialog({
        autoOpen: !1,
        width: "695",
        height: "auto",
        minHeight: "auto",
        modal: !0,
        resizable: !1,
        dialogClass: "dialog-billing-titlebar"
    }),$("#dialog_sos_edit_select_token").dialog({
        autoOpen: !1,
        width: "1000",
        height: "auto",
        minHeight: "auto",
        modal: !0,
        resizable: !1
    }),$("#dialog_add_new_employee").dialog({
        autoOpen: !1,
        width: "550",
        height: "auto",
        minHeight: "auto",
        modal: !0,
        resizable: !1
    }),$("#dialog_add_new_security").dialog({
        autoOpen: !1,
        width: "550",
        height: "auto",
        minHeight: "auto",
        modal: !0,
        resizable: !1
    }),$("#dialog_add_Self_cab_booking").dialog({
        autoOpen: !1,
        width: "550",
        height: "auto",
        minHeight: "auto",
        modal: !0,
        resizable: !1
    }),$("#Self_cabBooking_Status").dialog({
        autoOpen: !1,
        width: "550",
        height: "auto",
        minHeight: "auto",
        modal: !0,
        resizable: !1
    }),$("#dialog_stis_add_shift").dialog({
        autoOpen: !1,
        width: "350",
        height: "auto",
        minHeight: "auto",
        modal: !0,
        resizable: !1
    }),$("#dialog_stis_newcab_request").dialog({
        autoOpen: !1,
        width: "550",
        height: "600",
        minHeight: "auto",
        modal: !0,
        resizable: !1
    }),$("#dialog_stis_newcab_viewuser_request").dialog({
        autoOpen: !1,
        width: "850",
        height: "600",
        minHeight: "auto",
        modal: !0,
        resizable: !1
    }),$("#dialog_stis_cabrequest_setdriver").dialog({
        autoOpen: !1,
        width: "550",
        height: "auto",
        minHeight: "auto",
        modal: !0,
        resizable: !1
    }), $("#dialog_billing_plan_purchase").dialog({
        autoOpen: !1,
        width: "695",
        height: "auto",
        minHeight: "auto",
        modal: !0,
        resizable: !1,
        dialogClass: "dialog-billing-titlebar"
    }), initSelectList("object_device_list"), $(document).on("click", "optgroup", function() {
        $(this).children("option").length == $(this).children("option:selected").length ? $(this).children().prop("selected", !1) : $(this).children().prop("selected", !0)
    }), $(document).on("click", "optgroup option", function(e) {
        e.stopPropagation()
    }), $(document).on("click", "#dialog_report_object_list", function() {
        reportsSelectObject()
    }), document.getElementById("side_panel_history_filter").value = 2, document.getElementById("dialog_report_filter").value = 2, document.getElementById("dialog_image_gallery_filter").value = 0, switchHistoryReportsDateFilter("history"), switchHistoryReportsDateFilter("report"), switchHistoryReportsDateFilter("img"), switchHistoryReportsDateFilter("rilogbook"), switchHistoryReportsDateFilter("dtc"),/*updated by vetrivel.N*/ document.getElementById("ambrpt_dialog_report_filter").value = 2, $("#side_panel_objects_dragbar").mousedown(function(e) {
        e.preventDefault(), $(document).mousemove(guiDragbarObjectsHandler)
    }), $("#side_panel_events_dragbar").mousedown(function(e) {
        e.preventDefault(), $(document).mousemove(guiDragbarEventsHandler)
    }), $("#side_panel_history_dragbar").mousedown(function(e) {
        e.preventDefault(), $(document).mousemove(guiDragbarHistoryHandler)
    }), $("#bottom_panel_dragbar").mousedown(function(e) {
        e.preventDefault(), $(document).mousemove(guiDragbarBottomPanelHandler)
    }), $(document).mouseup(function() {
        map.invalidateSize(!0), $("#map").css("pointer-events", ""), $(document).unbind("mousemove", guiDragbarObjectsHandler), $(document).unbind("mousemove", guiDragbarEventsHandler), $(document).unbind("mousemove", guiDragbarHistoryHandler), $(document).unbind("mousemove", guiDragbarBottomPanelHandler)
    });
    
 //code done by Vetrivel.N.R.
    $("#dialog_settings_shift_allocation").dialog({
        autoOpen: !1,
        width: "350",
        height: "auto",
        minHeight: "auto",
        modal: !0,
        resizable: !1
    }),
    $("#dialog_settings_working_hour_field_properties").dialog({
        autoOpen: !1,
        width: "350",
        height: "auto",
        minHeight: "auto",
        modal: !0,
        resizable: !1
    }),
    $("#dialog_report_properties_amb").dialog({
        autoOpen: !1,
        width: "937",
        height: "auto",
        minHeight: "auto",
        modal: !0,
        resizable: !1
    }),
   $("#dialog_reports_amb").dialog({
        autoOpen: !1,
        width: "992",
        height: "auto",
        minHeight: "auto",
        modal: !0,
        resizable: !1,
        padding:"0px"
    }),
    $("#report_list_grid_amb").jqGrid({
        url: "func/fn_reportsamb.php?cmd=load_report_list",
        datatype: "json",
        colNames: [la.NAME, la.TYPE, la.FORMAT, la.OBJECTS, la.ZONES, la.SENSORS, la.DAILY, la.WEEKLY, ""],
        colModel: [{
            name: "name",
            index: "name",
            width: 240,
            sortable: !0
        }, {
            name: "type",
            index: "type",
            width: 215,
            align: "center",
            sortable: !1
        }, {
            name: "format",
            index: "format",
            width: 60,
            align: "center",
            sortable: !1
        }, {
            name: "objects",
            index: "objects",
            width: 60,
            align: "center",
            sortable: !1
        }, {
            name: "zones",
            index: "zones",
            width: 60,
            align: "center",
            sortable: !1
        }, {
            name: "sensors",
            index: "sensors",
            width: 60,
            align: "center",
            sortable: !1
        }, {
            name: "daily",
            index: "daily",
            width: 60,
            align: "center",
            sortable: !1
        }, {
            name: "weekly",
            index: "weekly",
            width: 60,
            align: "center",
            sortable: !1
        }, {
            name: "modify",
            index: "modify",
            width: 60,
            align: "center",
            sortable: !1
        }],
        rowNum: 2048,
        pager: "#report_list_grid_pager_amb",
        pgbuttons: !1,
        pgtext: "",
        recordtext: "",
        emptyrecords: "",
        sortname: "name",
        sortorder: "asc",
        viewrecords: !0,
        height: "450px",
        width: "962",
        shrinkToFit: !1,
        multiselect: !0,
        beforeSelectRow: function(e, t) {
    		loadObjectRpt()
            return "input" === t.target.tagName.toLowerCase()
        },
        loadComplete: function() {
            for (var e = $(this).getDataIDs(), t = 0; t < e.length; t++) {
                var a = e[t];
                $("#report_action_menu_" + a).click(function() {
                    return $("#report_action_menu").toggle().position({
                        my: "left top",
                        at: "right bottom",
                        of: this
                    }), menuOnItem = $(this).attr("tag"), $(document).one("click", function() {
                        $("#report_action_menu").hide()
                    }), !1
                })
            }
        }
    }), $("#report_list_grid_amb").jqGrid("navGrid", "#report_list_grid_pager_amb", {
        add: !0,
        edit: !1,
        del: !1,
        search: !1,
        addfunc: function() {
            reportPropertiesAmb("add")
        }
    }), $("#report_list_grid_amb").navButtonAdd("#report_list_grid_pager_amb", {
        caption: "",
        title: la.ACTION,
        buttonicon: "ui-icon-action",
        onClickButton: function() {},
        position: "last",
        id: "report_list_grid_action_menu_button_amb"
    }), $("#report_list_grid_action_menu_amb").menu({
        role: "listbox"
    }), $("#report_list_grid_action_menu_amb").hide(), $("#report_list_grid_action_menu_button_amb").click(function() {
        return $("#report_list_grid_action_menu_amb").toggle().position({
            my: "left bottom",
            at: "right-5 top-5",
            of: this
        }), $(document).one("click", function() {
            $("#report_list_grid_action_menu_amb").hide()
        }), !1
    }), $("#reports_generated_list_grid_amb").jqGrid({
        url: "func/fn_reportsamb.php?cmd=load_reports_generated_list",
        datatype: "json",
        colNames: [la.TIME, la.NAME, la.TYPE, la.FORMAT, la.OBJECTS, la.ZONES, la.SENSORS, la.SCHEDULE, ""],
        colModel: [{
            name: "dt_report",
            index: "dt_report",
            width: 110,
            fixed: !0,
            align: "center"
        }, {
            name: "name",
            index: "name",
            width: 215,
            sortable: !0
        }, {
            name: "type",
            index: "type",
            width: 190,
            align: "center",
            sortable: !1
        }, {
            name: "format",
            index: "format",
            width: 60,
            align: "center",
            sortable: !1
        }, {
            name: "objects",
            index: "objects",
            width: 60,
            align: "center",
            sortable: !1
        }, {
            name: "zones",
            index: "zones",
            width: 60,
            align: "center",
            sortable: !1
        }, {
            name: "sensors",
            index: "sensors",
            width: 60,
            align: "center",
            sortable: !1
        }, {
            name: "schedule",
            index: "schedule",
            width: 60,
            align: "center",
            sortable: !1
        }, {
            name: "modify",
            index: "modify",
            width: 60,
            align: "center",
            sortable: !1
        }],
        sortname: "dt_report",
        sortorder: "desc",
        rowNum: 50,
        rowList: [50, 100, 200, 300, 400, 500],
        pager: "#reports_generated_list_grid_pager_amb",
        viewrecords: !0,
        height: "450px",
        width: "962",
        shrinkToFit: !1,
        multiselect: !0,
        beforeSelectRow: function(e, t) {
            return "input" === t.target.tagName.toLowerCase()
        }
    }), $("#reports_generated_list_grid_amb").jqGrid("navGrid", "#reports_generated_list_grid_pager_amb", {
        add: !1,
        edit: !1,
        del: !1,
        search: !1
    }), $("#reports_generated_list_grid_amb").navButtonAdd("#reports_generated_list_grid_pager_amb", {
        caption: "",
        title: la.ACTION,
        buttonicon: "ui-icon-action",
        onClickButton: function() {},
        position: "last",
        id: "reports_generated_list_grid_action_menu_button_amb"
    }), $("#reports_generated_list_grid_action_menu_amb").menu({
        role: "listbox"
    }), $("#reports_generated_list_grid_action_menu_amb").hide(), $("#reports_generated_list_grid_action_menu_button_amb").click(function() {
        return $("#reports_generated_list_grid_action_menu_amb").toggle().position({
            my: "left bottom",
            at: "right-5 top-5",
            of: this
        }), $(document).one("click", function() {
            $("#reports_generated_list_grid_action_menu_amb").hide()
        }), !1
    }),
    
    $("#dialog_boarding").dialog({
     autoOpen: false,
     width: "1050px",
     height: "auto",
     minHeight: "auto",
     modal: !0,
     resizable: false
 }),$("#boarding_hotspot_add").dialog({
     autoOpen: false,
     width: "833px",
     height: "auto",
     minHeight: "auto",
     modal: !0,
     resizable: false
 }),$("#boarding_student_add").dialog({
     autoOpen: false,
     width: "833px",
     height: "auto",
     minHeight: "auto",
     modal: !0,
     resizable: false
 }),$("#dialog_boarding_allocate_add").dialog({
     autoOpen: false,
     width: "933px",
     height: "auto",
     minHeight: "auto",
     modal: !0,
     resizable: false
 }),
 $("#dialog_boarding_trip_employee_list").dialog({
     autoOpen: false,
     width: "400px",
     height: "auto",
     minHeight: "auto",
     modal: !0,
     resizable: false
 }), $("#dialog_tripdata").dialog({
       autoOpen: false,
       width: "1000",
       height: "610",
       minHeight: "auto",
       modal: !0,
       resizable: false
   }), $("#dall").dialog({
       autoOpen: false,
       width: "1000",
       height: "600",
       minHeight: "auto",
       modal: !0,
       resizable: false,
       open: function (event, ui) {
   	    $('#dall').css('overflow-y', 'scroll');
   	  }
   }), $("#dashboard_service_list").dialog({
       autoOpen: false,
       width: "1060",
       height: "600",
       minHeight: "auto",
       modal: !0,
       resizable: false
   }), $("#dashboardpop3").dialog({
	   autoOpen: true,
	     width: "100%",
	     height: "100%",
	     minHeight: "auto",
	     modal: !0,
	     resizable: false,
	     position:{my:"center",at:"top"},
	     draggable: false
	 }),$("#dialog_boarding_allocate_adddaily").dialog({
       autoOpen: false,
       width: "833px",
       height: "auto",
       minHeight: "auto",
       modal: !0,
       resizable: false
   }),
 
 //scheduled
   
   $("#boarding_allocate_list_grid").jqGrid({
    url: "func/fn_boarding.php?cmd=select_allocate&object_id="+ document.getElementById("dialog_boarding_addobject").value,
     datatype: "json",
     colNames: [la.TRIPNAME,la.HOTSPOTNAME,la.ACTIVE, la.SYSTEM, la.STARTTIME, la.ENDTIME,"",""],
     colModel: [
     {
     	name: "tripname",
     	index: "tripname",
     	width: 180,
     	fixed: true,
     	align: "center",
     	sortable: false
     }, {
         name: "routename",
         index: "routename",
         width: 250,
         fixed: true,
         align: "center",
         sortable: false
     }
     ,{
         name: "active",
         index: "active",
         width: 80,
         fixed: true,
         align: "center",
         sortable: false
     }
     ,{
         name: "notify_system",
         index: "notify_system",
         width: 120,
         fixed: true,
         align: "center",
         sortable: false
     }
     ,{
         name: "notify_email",
         index: "notify_email",
         width: 120,
         fixed: true,
         align: "center",
         sortable: false
     }
     ,{
         name: "notify_sms",
         index: "notify_sms",
         width: 80,
         fixed: true,
         align: "center",
         sortable: false
     }
     ,{
         name: "modify",
         index: "modify",
         width: 100,
         fixed: true,
         align: "center",
         sortable: false
     }
     ,{
         name: "event_id",
         index: "event_id",
         width: 90,
         fixed: true,
         align: "center",
         hidden: true,
         sortable: false
     }
     ],
     rowNum: 1000, 
     height: "375",
     width: "950",
      pager: "#boarding_allocate_list_grid_pager",
     pgbuttons: false,
     pgtext: "",
     recordtext: "",
     emptyrecords: "",
     sortname: "name",
     sortorder: "asc",
     viewrecords: true,
      loadComplete: function (dat) {
         
         }
         
 }),$("#boarding_allocate_list_grid").jqGrid("navGrid", "#boarding_allocate_list_grid_pager", {
     add: true,
     edit: false,
     del: false,
     search: false,
     addfunc: function(b) {
     	
     	 if($("#dialog_boarding_addobject  option:selected").val()!="Select" && $("#dialog_boarding_addobject  option:selected").val()!="" )
     	 {
     		 boeardingallocate("cancel");
     		 $("#dialog_boarding_allocate_add").dialog("open");
     		 $("#lbl_boarding_object_name").text($("#dialog_boarding_addobject  option:selected").text());
     	 }
     	 else
          notifyBox("error", la.ERROR, la.PLSOBJECT);
     }
 }),

//daily

	$("#boarding_allocate_list_grid_daily").jqGrid({
  url: "func/fn_boarding.php?cmd=select_allocatedaily&object_id="+ document.getElementById("dialog_boarding_addobjectdaily").value,
   datatype: "json",
   colNames: [la.TRIPNAME,la.HOTSPOTNAME,la.ACTIVE, la.SYSTEM, la.STARTTIME, la.ENDTIME,"",""],
   colModel: [
   {
   	name: "tripname",
   	index: "tripname",
   	width: 150,
   	fixed: true,
   	align: "center",
   	sortable: false
   }, {
       name: "routename",
       index: "routename",
       width: 240,
       fixed: true,
       align: "center",
       sortable: false
   }
   ,{
       name: "active",
       index: "active",
       width: 80,
       fixed: true,
       align: "center",
       sortable: false
   }
   ,{
       name: "notify_system",
       index: "notify_system",
       width: 80,
       fixed: true,
       align: "center",
       sortable: false
   }
   ,{
       name: "notify_email",
       index: "notify_email",
       width: 130,
       fixed: true,
       align: "center",
       sortable: false
   }
   ,{
       name: "notify_sms",
       index: "notify_sms",
       width: 130,
       fixed: true,
       align: "center",
       sortable: false
   }
   ,{
       name: "modify",
       index: "modify",
       width: 100,
       fixed: true,
       align: "center",
       sortable: false
   }
   ,{
       name: "event_id",
       index: "event_id",
       width: 90,
       fixed: true,
       align: "center",
       hidden: true,
       sortable: false
   }
   ],
   rowNum: 1000,
   height: "375",
    pager: "#boarding_allocate_list_grid_pager_daily",
   pgbuttons: false,
   pgtext: "",
   recordtext: "",
   emptyrecords: "",
   sortname: "name",
   sortorder: "asc",
   viewrecords: true,
    loadComplete: function (dat) {
       
       }
       
}),$("#boarding_allocate_list_grid_daily").jqGrid("navGrid", "#boarding_allocate_list_grid_pager_daily", {
   add: true,
   edit: false,
   del: false,
   search: false,
   addfunc: function(b) {
   	
   	 if($("#dialog_boarding_addobjectdaily  option:selected").val()!="Select" && $("#dialog_boarding_addobjectdaily  option:selected").val()!="" )
   	 {
   		 boeardingallocate("cancel");
   		 $("#dialog_boarding_allocate_adddaily").dialog("open");
   		 $("#lbl_boarding_object_namedaily").text($("#dialog_boarding_addobjectdaily  option:selected").text());
   	 }
   	 else
        notifyBox("error", la.ERROR, la.PLSOBJECT);
   }
}),

//end trip data.

  $("#boarding_hotspot_list_grid").jqGrid({
     url: "func/fn_boarding.php?cmd=select_hotspot",
     datatype: "json",
     colNames: [la.HOTSPOTID,la.HOTSPOTNAME,"No Of Zones",""],
     colModel: [
     {
         name: "route_id",
         index: "route_id",
         width: 140,
         fixed: true,
         align: "center",
         sortable: false
     },{
         name: "routename",
         index: "routename",
         width: 450,
         fixed: true,
         align: "center",
         sortable: false
     }
     ,{
         name: "cnt",
         index: "cnt",
         width: 180,
         fixed: true,
         align: "center",
         sortable: false
     }
     ,{
         name: "modify",
         index: "modify",
         width: 130,
         fixed: true,
         align: "center",
         sortable: false
     }
     ],
     rowNum: 1000,
     height: "400",
     width:"950",
     pager: "#boarding_hotspot_list_grid_pager",
     pgbuttons: false,
     pgtext: "",
     recordtext: "",
     emptyrecords: "",
     sortname: "name",
     sortorder: "asc",
     viewrecords: true,
      loadComplete: function (dat) {
         var custom = jQuery("#boarding_hotspot_list_grid").jqGrid('getRowData');
         var a = document.getElementById("dialog_studentadd_routename");
         var aa = document.getElementById("dialog_studentadd_routename_down");
         //var hp = document.getElementById("dialog_allocate_hotspotddlsearch");
         var hpadd = document.getElementById("dialog_boarding_addhotspot");
         var hpaddd = document.getElementById("dialog_boarding_addhotspotdaily");
         
         a.options.length = 0;
         aa.options.length = 0;
         //hp.options.length = 0;
         hpadd.options.length = 0;
         hpaddd.options.length = 0;
         a.options.add(new Option("Select"));
         aa.options.add(new Option("Select"));
         //hp.options.add(new Option("Select"));
			hpadd.options.add(new Option("Select"));
			hpaddd.options.add(new Option("Select"));
			 for($itr=0;$itr<custom.length;$itr++)
			 {
	 			 a.options.add(new Option(custom[$itr]["routename"],custom[$itr]["route_id"]));
	 			 aa.options.add(new Option(custom[$itr]["routename"],custom[$itr]["route_id"]));
				 //hp.options.add(new Option(custom[$itr]["routename"],custom[$itr]["route_id"]));
				 hpadd.options.add(new Option(custom[$itr]["routename"],custom[$itr]["route_id"]));
				 hpaddd.options.add(new Option(custom[$itr]["routename"],custom[$itr]["route_id"]));
			 }
 			 
         }
         
 }),$("#boarding_hotspot_list_grid").jqGrid("navGrid", "#boarding_hotspot_list_grid_pager", {
     add: true,
     edit: false,
     del: false,
     search: false,
     addfunc: function(b) {
	 hotspotin('close'),$("#boarding_hotspot_add").dialog("open");
     }
 }), $("#boarding_dept_list_grid").jqGrid({
     url: "func/fn_boarding.php?cmd=select_department",
     datatype: "json",
     colNames: [la.DEPARTMENTNAME,"","id"],
     colModel: [
     {
         name: "dept_name",
         index: "dept_name",
         width: 680,
         fixed: true,
         align: "center",
         sortable: false
     },{
         name: "modify",
         index: "modify",
         width: 90,
         fixed: true,
         align: "center",
         sortable: false
     }
     ,{
         name: "dept_id",
         index: "dept_id",
         width: 100,
         fixed: true,
         align: "center",
         hidden: true,
         sortable: false
     }],
     rowNum: 1000,    
     height: "253",
         loadComplete: function (dat) {
      
         var custom = jQuery("#boarding_dept_list_grid").jqGrid('getRowData');
         var a = document.getElementById("dialog_boarding_deptnameddl");
         var af = document.getElementById("dialog_studentadd_deptnameddlsearch");
         var aa = document.getElementById("dialog_studentadd_deptnameddl");
         a.options.length = 0;
         a.options.add(new Option("Select"));
         
         af.options.length = 0;
         af.options.add(new Option("Select"));
         
         aa.options.length = 0;
         aa.options.add(new Option("Select"));
			
			 for($itr=0;$itr<custom.length;$itr++)
			 {
	 			 a.options.add(new Option(custom[$itr]["dept_name"],custom[$itr]["dept_id"]));
	 			 af.options.add(new Option(custom[$itr]["dept_name"],custom[$itr]["dept_id"]));
	 			 aa.options.add(new Option(custom[$itr]["dept_name"],custom[$itr]["dept_id"]));
	 
			 }
			   $("#boarding_section_list_grid").setGridParam({url: "func/fn_boarding.php?cmd=select_section&dept_id=" + document.getElementById("dialog_boarding_deptnameddl").value });
            $("#boarding_section_list_grid").trigger("reloadGrid");
         }
 }),$("#boarding_section_list_grid").jqGrid({
     url: "func/fn_boarding.php?cmd=select_section&dept_id="+ document.getElementById("dialog_boarding_deptnameddl").value,
     datatype: "json",
     colNames: [la.DEPARTMENTNAME,la.SECTIONNAME,"","section_id","dept_id"],
     colModel: [
     {
         name: "dept_name",
         index: "dept_name",
         width: 340,
         fixed: true,
         align: "center",
         sortable: false
     },
     {
         name: "section_name",
         index: "section_name",
         width: 340,
         fixed: true,
         align: "center",
         sortable: false
     }
     ,{
         name: "modify",
         index: "modify",
         width: 90,
         fixed: true,
         align: "center",
         sortable: false
     }
      ,{
         name: "section_id",
         index: "section_id",
         width: 10,
         fixed: true,
         align: "center",
         hidden: true,
         sortable: false
     }
     ,{
         name: "dept_id",
         index: "dept_id",
         width: 10,
         fixed: true,
         align: "center",
         hidden: true,
         sortable: false
     }],
     rowNum: 1000,
     height: "253",
         loadComplete: function (dat) {
         var custom = jQuery("#boarding_dept_list_grid").jqGrid('getRowData');
         var a = document.getElementById("dialog_studentadd_sectionddlsearch");
        
         a.options.add(new Option("Select"));

			 for($itr=0;$itr<custom.length;$itr++)
			 {
	 			 a.options.add(new Option(custom[$itr]["section_name"],custom[$itr]["section_id"]));

			 }
 			 
         }
 }),$("#boarding_holiday_list_grid").jqGrid({
     url: "func/fn_boarding.php?cmd=select_holiday&df=" + $("#dialog_boarding_holidayfromdate").val()+"&dt="+$("#dialog_boarding_holidaytodate").val(),
     datatype: "json",
     colNames: [la.FROMDATEH,la.TODATEH,la.REASON,"","id"],
     colModel: [
     {
         name: "date",
         index: "date",
         width: 180,
         fixed: true,
         align: "center",
         sortable: false
     }
     ,
     {
         name: "dateto",
         index: "dateto",
         width: 180,
         fixed: true,
         align: "center",
         sortable: false
     },
     {
         name: "reason",
         index: "reason",
         width: 400,
         fixed: true,
         align: "center",
         sortable: false
     },{
         name: "modify",
         index: "modify",
         width: 120,
         fixed: true,
         align: "center",
         sortable: false
     }
     ,{
         name: "uid",
         index: "uid",
         width: 80,
         fixed: true,
         align: "center",
         hidden: true,
         sortable: false
     }],
     rowNum: 1000,
     height: "253"
       
 }),$("#boarding_student_list_grid").jqGrid({
     url: "func/fn_boarding.php?cmd=select_student&dept_id="+ document.getElementById("dialog_studentadd_deptnameddlsearch").value+"&section_id="+ document.getElementById("dialog_studentadd_sectionddlsearch").value+"&fidata="+ document.getElementById("dialog_studentadd_inputsearch").value,
     datatype: "json",
     colNames: [la.DEPARTMENT,la.SECTION,la.ID_NUMBER,la.VNAME,la.GENDER,la.MOBILENO,"",la.EMAILID,la.MORNING_HOTSPOT,la.EVENING_HOTSPOT,la.STATUS,"","uid","route_id","section_id","dept_id","route_id_down","parent","rfid","zone_id","zone_id_down","Object"],
     colModel: [
     {
         name: "dept_name",
         index: "dept_name",
         width: 80,
         fixed: true,
         align: "center",
         sortable: false
     },
     {
         name: "section_name",
         index: "section_name",
         width: 60,
         fixed: true,
         align: "center",
         sortable: false
     }
     ,
     {
         name: "sid",
         index: "sid",
         width: 80,
         fixed: true,
         align: "center",
         sortable: false
     },
     {
         name: "name",
         index: "name",
         width: 130,
         fixed: true,
         align: "center",
         sortable: false
     }
     ,{
         name: "gender",
         index: "gender",
         width: 40,
         fixed: true,
         align: "center",
         sortable: false
     },
     {
         name: "phno",
         index: "phno",
         width: 65,
         fixed: true,
         align: "center",
         sortable: false
     }
     ,
     {
         name: "dob",
         index: "dob",
         width: 65,
         fixed: true,
         align: "center",
         sortable: false,
         hidden:true
     }
     ,{
         name: "emailid",
         index: "emailid",
         width: 100,
         fixed: true,
         align: "center",
         sortable: false
     },
     {
         name: "routename",
         index: "routename",
         width: 110,
         fixed: true,
         align: "center",
         sortable: false
     },
     {
         name: "routename_down",
         index: "routename_down",
         width: 100,
         fixed: true,
         align: "center",
         sortable: false
     }
     ,{
         name: "status",
         index: "status",
         width:100,
         fixed: true,
         align: "center",
         sortable: false
     },
     
     {
         name: "modify",
         index: "modify",
         width: 60,
         fixed: true,
         align: "center",
         sortable: false
     },{
         name: "uid",
         index: "uid",
         width: 10,
         fixed: true,
         align: "center",
         hidden: true,
         sortable: false
     }
     ,{
         name: "route_id",
         index: "route_id",
         width: 10,
         fixed: true,
         align: "center",
         hidden: true,
         sortable: false
     }
      ,{
         name: "section_id",
         index: "section_id",
         width: 10,
         fixed: true,
         align: "center",
         hidden: true,
         sortable: false
     }
     ,{
         name: "dept_id",
         index: "dept_id",
         width: 10,
         fixed: true,
         align: "center",
         hidden: true,
         sortable: false
     } ,{
         name: "route_id_down",
         index: "route_id_down",
         width: 10,
         fixed: true,
         align: "center",
         hidden: true,
         sortable: false
     }
     ,{
         name: "parent",
         index: "parent",
         width: 10,
         fixed: true,
         align: "center",
         hidden: true,
         sortable: false
     }
     ,{
         name: "rfid",
         index: "rfid",
         width: 10,
         fixed: true,
         align: "center",
         hidden: true,
         sortable: false
     }
     ,{
         name: "zone_id",
         index: "zone_id",
         width: 10,
         fixed: true,
         align: "center",
         hidden: true,
         sortable: false
     }
     ,{
         name: "zone_id_down",
         index: "zone_id_down",
         width: 10,
         fixed: true,
         align: "center",
         hidden: true,
         sortable: false
     },{
         name: "pe_imei",
         index: "pe_imei",
         width: 10,
         fixed: true,
         align: "center",
         hidden: true,
         sortable: false
     }],
     rowNum: 50,
     rowList: [50, 100, 200, 300, 400, 500,1000,5000],
     height: "340",
     width:"950",
     pager: "#boarding_student_list_grid_pager",
     pgbuttons: false,
     pgtext: "",
     recordtext: "",
     emptyrecords: "",
     // viewrecords: true,
     viewrecords: !1,
     // height: "450px",
     // width: "962",
     shrinkToFit: !1,
     multiselect: !0,

 }),$("#boarding_student_list_grid").jqGrid("navGrid", "#boarding_student_list_grid_pager", {
     add: true,
     edit: false,
     del: false,
     search: false,
     addfunc: function(b) {
          $("#boarding_student_add").dialog("open");
     }
 }),
    //code end by Vetrivel.N.R.
 
   //code done by Vetrivel.N.R
   
  $("#dialog_rfidtrip").dialog({
     autoOpen: false,
     width: "1050",
     height: "auto",
     minHeight: "auto",
     modal: true,
     resizable: false
    
 }),$("#rfidtrip_dept_list_grid").jqGrid({
     url: "func/fn__rfid.php?cmd=select_department",
     datatype: "json",
     colNames: [la.DEPARTMENTNAME,"","id"],
     colModel: [
     {
         name: "dept_name",
         index: "dept_name",
         width: 700,
         fixed: true,
         align: "center",
         sortable: false
     },{
         name: "modify",
         index: "modify",
         width: 90,
         fixed: true,
         align: "center",
         sortable: false
     }
     ,{
         name: "dept_id",
         index: "dept_id",
         width: 90,
         fixed: true,
         align: "center",
         hidden: true,
         sortable: false
     }],
     rowNum: 1000,
     height: "300",
         loadComplete: function (dat) {
      
         var custom = jQuery("#rfidtrip_dept_list_grid").jqGrid('getRowData');
         var a = document.getElementById("dialog_employee_deptnameddl");
         a.options.length = 0;
         a.options.add(new Option("Select"));
		  	 for($itr=0;$itr<custom.length;$itr++)
			 {
	 	  		 a.options.add(new Option(custom[$itr]["dept_name"],custom[$itr]["dept_id"]));
	 
			 }
			  
         }
 }), $("#rfidtrip_driverrfid_list_grid").jqGrid({
     url: "func/fn__rfid.php?cmd=select_driverrfid",
     datatype: "json",
     colNames: ['uid',la.DRIVERRFIDID,""],
     colModel: [
     {
         name: "uid",
         index: "uid",
         width: 90,
         fixed: true,
         align: "center",
         sortable: false,
         hidden: true,
     }
     ,{
         name: "driverrfid",
         index: "driverrfid",
         width: 700,
         fixed: true,
         align: "center",
         sortable: false
     },{
         name: "modify",
         index: "modify",
         width: 90,
         fixed: true,
         align: "center",
         sortable: false
     }
     ],
     rowNum: 1000, 
     height: "300",
         loadComplete: function (dat) {
      
         var custom = jQuery("#rfidtrip_driverrfid_list_grid").jqGrid('getRowData');
         var a = document.getElementById("dialog_rfidtrip_gpsi_driverrfidddl");
         a.options.length = 0;
		  	 for($itr=0;$itr<custom.length;$itr++)
			 {
	 	  		 a.options.add(new Option(custom[$itr]["driverrfid"],custom[$itr]["driverrfid"]));
	 
			 }
			  
         }
 }),$("#rfidtrip_employee_list_grid").jqGrid({
     url: "func/fn__rfid.php?cmd=select_employee&name="+ document.getElementById("dialog_employee_search").value,
     datatype: "json",
     colNames: ["uid","dept_id",la.DEPARTMENTNAME,la.VNAME,la.RFIDID,la.GENDER,""],
     colModel: [
     {
         name: "uid",
         index: "uid",
         width: 100,
         fixed: true,
         align: "center",
         sortable: false,
         hidden: true
     },{
         name: "dept_id",
         index: "dept_id",
         width: 150,
         fixed: true,
         align: "center",
         sortable: false,
         hidden: true
     }
     ,{
         name: "dept_name",
         index: "dept_name",
         width: 220,
         fixed: true,
         align: "center",
         sortable: false
     }
     ,{
         name: "name",
         index: "name",
         width: 230,
         fixed: true,
         align: "center",
         sortable: false
     }
     ,{
         name: "rfidid",
         index: "rfidid",
         width: 230,
         fixed: true,
         align: "center",
         sortable: false
     }
     ,{
         name: "gender",
         index: "gender",
         width: 100,
         fixed: true,
         align: "center",
         sortable: false
     }
     ,{
         name: "modify",
         index: "modify",
         width: 100,
         fixed: true,
         align: "center",
         sortable: false
     }
     ],
     rowNum: 1000, 
     height: "400",
     pager: "#rfidtrip_employee_list_grid_pager",
     pgbuttons: false,
     pgtext: "",
     recordtext: "",
     emptyrecords: "",
     sortname: "name",
     sortorder: "asc",
     viewrecords: true,
         
 }), $("#rfidtrip_gpsclose_list_grid").jqGrid({
    url: "func/fn__rfid.php?cmd=select_gpsclose&name="+ document.getElementById("dialog_gpsclose_search").value,
     datatype: "json",
     colNames: ['uid',la.IMEI,la.NAME,la.DRIVERRFIDID,la.VEHICLENO,la.VENDOR,la.DRIVER,la.DRIVERPH,la.STARTTIME,""],
     colModel: [
     {
         name: "uid",
         index: "uid",
         width: 15,
         fixed: true,
         align: "center",
         sortable: false,
         hidden: true,
     },
     {
         name: "imei",
         index: "imei",
         width: 15,
         fixed: true,
         align: "center",
         sortable: false,
         hidden: true,
     }        
     ,{
         name: "name",
         index: "name",
         width: 200,
         fixed: true,
         align: "center",
         sortable: false
     }
     ,{
         name: "driverrfid",
         index: "driverrfid",
         width: 150,
         fixed: true,
         align: "center",
         sortable: false
     },{
         name: "vehicleno",
         index: "vehicleno",
         width: 100,
         fixed: true,
         align: "center",
         sortable: false
     },{
         name: "vendorname",
         index: "vendorname",
         width: 110,
         fixed: true,
         align: "center",
         sortable: false
     },{
         name: "drivername",
         index: "drivername",
         width: 110,
         fixed: true,
         align: "center",
         sortable: false
     },{
         name: "driverphone",
         index: "driverphone",
         width: 80,
         fixed: true,
         align: "center",
         sortable: false
     }
     ,{
         name: "starttime",
         index: "starttime",
         width: 110,
         fixed: true,
         align: "center",
         sortable: false
     }
     ,{
         name: "modify",
         index: "modify",
         width: 100,
         fixed: true,
         align: "center",
         sortable: false
     }
     ],
     rowNum: 1000,
     width: "950", 
     height: "350",
         loadComplete: function (dat) {
      
      
			  
         }
 }), $("#rfidtrip_employee_list_grid").jqGrid("navGrid", "#rfidtrip_employee_list_grid_pager", {
     add: true,
     edit: false,
     del: false,
     search: false,
     addfunc: function(b) {
          $("#rfidtrip_employee_add").dialog("open");
     }
 }),$("#rfidtrip_employee_add").dialog({
     autoOpen: false,
     width: "633px",
     height: "auto",
     minHeight: "auto",
     modal: true,
     resizable: false
 }),$("#rfidtrip_gpsclose_close").dialog({
     autoOpen: false,
     width: "300px",
     height: "auto",
     minHeight: "auto",
     modal: true,
     resizable: false
 });
 
  //code end by Vetrivel.N.R
    
    
}

function addPopupToMap(e, t, a, o, i) {
    if ("" != i && o != i) {
        if (1 == gsValues.map_popup_detailed) var s = 'style="display:none;"',
            n = "";
        else var s = "",
            n = 'style="display:none;"';
        o = '<div id="popup_short" ' + s + ">" + o, o += '<div style="width:100%; text-align: right;"><a href="#" class="" onClick="switchPopupDetailed(true)">' + la.DETAILED + "</a></div>", o += "</div>", o += '<div id="popup_detailed" ' + n + ">" + i, o += '<div style="width:100%; text-align: right;"><a href="#" class="" onClick="switchPopupDetailed(false)">' + la.SHORT + "</a></div>", o += "</div>"
    }
    mapPopup = L.popup({
        offset: a
    }).setLatLng([e, t]).setContent(o).openOn(map)
}

function switchPopupDetailed(e) {
    switch (e) {
        case !1:
            document.getElementById("popup_short").style.display = "", document.getElementById("popup_detailed").style.display = "none", gsValues.map_popup_detailed = !1;
            break;
        case !0:
            document.getElementById("popup_short").style.display = "none", document.getElementById("popup_detailed").style.display = "", gsValues.map_popup_detailed = !0
    }
}

function destroyMapPopup() {
    map.closePopup()
}

function loadingData(e) {
    1 == e ? document.getElementById("loading_data_panel").style.display = "" : document.getElementById("loading_data_panel").style.display = "none"
}

function notifyBox(e, t, a, o) {
    $.pnotify({
        title: t,
        text: a,
        type: e,
        opacity: .8,
        closer_hover: !1,
        sticker_hover: !1,
        hide: o
    })
}

function notifyDialog(e) {
    document.getElementById("dialog_notify_text").innerHTML = e, $("#dialog_notify").dialog("open")
}

function confirmDialog(e, t) {
    confirmResponseValue = !1, document.getElementById("dialog_confirm_text").innerHTML = e, $("#dialog_confirm").dialog("destroy"), $("#dialog_confirm").dialog({
        autoOpen: !1,
        width: "320px",
        height: "auto",
        minHeight: "auto",
        modal: !0,
        resizable: !1,
        draggable: !1,
        dialogClass: "dialog-notify-titlebar",
        close: function() {
            t(confirmResponseValue)
        }
    }), $("#dialog_confirm").dialog("open")
}

function confirmResponse(e) {
    confirmResponseValue = e, $("#dialog_confirm").dialog("close")
}

function loadObjectMapMarkerIcons() {
    var e = new Array;
    for (var t in settingsObjectData) {
        var a = settingsObjectData[t];
        e.push(a.icon)
    }
    for (e = uniqueArray(e), i = 0; i < e.length; i++) {
        var o = e[i],
            s = e[i],
            n = settingsUserData.map_is;
        mapMarkerIcons[o] = L.icon({
            iconUrl: s,
            iconSize: [28 * n, 28 * n],
            iconAnchor: [14 * n, 14 * n],
            popupAnchor: [0, 0]
        })
    }
}

function addPointerOverMarker(e) {
    var t = function(e) {
            document.getElementById("map").style.cursor = "pointer", OpenLayers.Event.stop(e)
        },
        a = function(e) {
            document.getElementById("map").style.cursor = "auto", OpenLayers.Event.stop(e)
        };
    e.events.register("mouseover", e, t), e.events.register("mouseout", e, a)
}

function rotateMarker(e, t, a) {
    $("#" + e.markers[t].icon.imageDiv.id).css("-moz-transform", "rotate(" + a + "deg)"), $("#" + e.markers[t].icon.imageDiv.id).css("-webkit-transform", "rotate(" + a + "deg)"), $("#" + e.markers[t].icon.imageDiv.id).css("-o-transform", "rotate(" + a + "deg)")
}

function createCluster(e) {
    var t = settingsUserData.map_is;
    if ("objects" == e) var a = "img/markers/clusters/objects.svg",
        o = "marker-cluster";
    else {
        if ("markers" != e) return !1;
        var a = "img/markers/clusters/markers.svg",
            o = "marker-cluster"
    }
    if (1 == gsValues.map_clusters) var i = gsValues.map_max_zoom + 1;
    else var i = gsValues.map_min_zoom;
    var s = new L.MarkerClusterGroup({
        spiderfyDistanceMultiplier: 2 * t,
        spiderfyOnMaxZoom: !0,
        showCoverageOnHover: !1,
        maxClusterRadius: 60,
        disableClusteringAtZoom: i,
        iconCreateFunction: function(e) {
            var i = e.getChildCount(),
                s = " cluster-";
            return s += 10 > i ? "small" : 100 > i ? "medium" : "large", L.divIcon({
                html: '<div><img src="' + a + '"><span>' + i + "</span></div>",
                className: o + s,
                iconSize: L.point(40 * t, 40 * t),
                iconAnchor: [14 * t, 14 * t],
                popupAnchor: [40 * t, 0 * t]
            })
        }
    });
    return s
}

function mapViewControls() {
    var e = L.Control.extend({
        options: {
            position: "topleft"
        },
        onAdd: function(e) {
            var t = L.DomUtil.create("div", "leaflet-control leaflet-bar");
            linkObjects = L.DomUtil.create("a", "", t), linkObjects.id = "map_control_objects", linkObjects.href = "#", linkObjects.title = la.ENABLE_DISABLE_OBJECTS, linkObjects.className = "", iconObjects = L.DomUtil.create("span", "", linkObjects), iconObjects.className = "icon-objects box1",iconObjects.id="icon-objects-id";
            var a = L.DomEvent.stopPropagation;
            L.DomEvent.on(linkObjects, "dblclick", a), L.DomEvent.on(linkObjects, "mousedown", a), L.DomEvent.on(linkObjects, "click", function() {
                1 == e.hasLayer(mapLayers.realtime) ? (e.removeLayer(mapLayers.realtime), iconObjects.className = "icon-objects disabled box1", gsValues.map_objects = !1) : (e.addLayer(mapLayers.realtime), iconObjects.className = "icon-objects box1",iconObjects.id="icon-objects-id", gsValues.map_objects = !0)
            }), linkMarkers = L.DomUtil.create("a", "", t), linkMarkers.id = "map_control_markers", linkMarkers.href = "#", linkMarkers.title = la.ENABLE_DISABLE_MARKERS, linkMarkers.className = "", iconMarkers = L.DomUtil.create("span", "", linkMarkers), iconMarkers.className = "icon-markers box1";
            var a = L.DomEvent.stopPropagation;
            L.DomEvent.on(linkMarkers, "dblclick", a), L.DomEvent.on(linkMarkers, "mousedown", a), L.DomEvent.on(linkMarkers, "click", function() {
                1 == e.hasLayer(mapLayers.places_markers) ? (e.removeLayer(mapLayers.places_markers), iconMarkers.className = "icon-markers disabled box1", gsValues.map_markers = !1) : (e.addLayer(mapLayers.places_markers), iconMarkers.className = "icon-markers box1", gsValues.map_markers = !0)
            }), linkRoutes = L.DomUtil.create("a", "", t), linkRoutes.id = "map_control_routes", linkRoutes.href = "#", linkRoutes.title = la.ENABLE_DISABLE_ROUTES, linkRoutes.className = "", iconRoutes = L.DomUtil.create("span", "", linkRoutes), iconRoutes.className = "icon-routes box1";
            var a = L.DomEvent.stopPropagation;
            L.DomEvent.on(linkRoutes, "dblclick", a), L.DomEvent.on(linkRoutes, "mousedown", a), L.DomEvent.on(linkRoutes, "click", function() {
                1 == e.hasLayer(mapLayers.places_routes) ? (e.removeLayer(mapLayers.places_routes), iconRoutes.className = "icon-routes disabled box1", gsValues.map_routes = !1) : (e.addLayer(mapLayers.places_routes), iconRoutes.className = "icon-routes box1", gsValues.map_routes = !0)
            }), linkZones = L.DomUtil.create("a", "", t), linkZones.id = "map_control_zones", linkZones.href = "#", linkZones.title = la.ENABLE_DISABLE_ZONES, linkZones.className = "", iconZones = L.DomUtil.create("span", "", linkZones), iconZones.className = "icon-zones box1";
            var a = L.DomEvent.stopPropagation;
            L.DomEvent.on(linkZones, "dblclick", a), L.DomEvent.on(linkZones, "mousedown", a), L.DomEvent.on(linkZones, "click", function() {
                1 == e.hasLayer(mapLayers.places_zones) ? (e.removeLayer(mapLayers.places_zones), iconZones.className = "icon-zones disabled box1", gsValues.map_zones = !1) : (e.addLayer(mapLayers.places_zones), iconZones.className = "icon-zones box1", gsValues.map_zones = !0)
            }), linkClusters = L.DomUtil.create("a", "", t), linkClusters.id = "map_control_clusters", linkClusters.href = "#", linkClusters.title = la.ENABLE_DISABLE_CLUSTERS, linkClusters.className = "", iconClusters = L.DomUtil.create("span", "", linkClusters), 1 == gsValues.map_clusters ? iconClusters.className = "icon-clusters box1 " : iconClusters.className = "icon-clusters disabled box1";
            var a = L.DomEvent.stopPropagation;
            if (L.DomEvent.on(linkClusters, "dblclick", a), L.DomEvent.on(linkClusters, "mousedown", a), L.DomEvent.on(linkClusters, "click", function() {
                    1 == gsValues.map_clusters ? (mapLayers.realtime.options.disableClusteringAtZoom = gsValues.map_min_zoom, mapLayers.places_markers.options.disableClusteringAtZoom = gsValues.map_min_zoom, iconClusters.className = "icon-clusters box1 disabled", gsValues.map_clusters = !1) : (mapLayers.realtime.options.disableClusteringAtZoom = gsValues.map_max_zoom + 1, mapLayers.places_markers.options.disableClusteringAtZoom = gsValues.map_max_zoom + 1, iconClusters.className = "icon-clusters  box1", gsValues.map_clusters = !0), objectAddAllToMap(), placesMarkerAddAllToMap()
                }), 1 == gsValues.map_google_traffic) {
                linkTraffic = L.DomUtil.create("a", "", t), linkTraffic.id = "map_control_traffic", linkTraffic.href = "#", linkTraffic.title = la.ENABLE_DISABLE_LIVE_TRAFFIC, linkTraffic.className = "", iconTraffic = L.DomUtil.create("span", "", linkTraffic), iconTraffic.className = "icon-traffic box1 disabled";
                var a = L.DomEvent.stopPropagation;
                L.DomEvent.on(linkTraffic, "dblclick", a), L.DomEvent.on(linkTraffic, "mousedown", a), L.DomEvent.on(linkTraffic, "click", function() {
                    1 == gsValues.map_traffic ? (iconTraffic.className = "icon-traffic disabled box1", gsValues.map_traffic = !1, strMatches("gmap,ghyb,gter", gsValues.map_layer.toString()) && switchMapLayer(gsValues.map_layer)) : strMatches("gmap,ghyb,gter", gsValues.map_layer.toString()) ? (iconTraffic.className = "icon-traffic  box1", gsValues.map_traffic = !0, switchMapLayer(gsValues.map_layer)) : notifyBox("error", la.LIVE_TRAFFIC, la.LIVE_TRAFFIC_FOR_THIS_MAP_IS_NOT_AVAILABLE)
                })
            }
            return t
        }
    });
    return e
}

function mapToolControls() {
    var e = L.Control.extend({
        options: {
            position: "topleft"
        },
        onAdd: function() {
            var e = L.DomUtil.create("div", "leaflet-control leaflet-bar");
            linkFitObjects = L.DomUtil.create("a", "", e), linkFitObjects.id = "map_fit_objects", linkFitObjects.href = "#", linkFitObjects.title = la.FIT_OBJECTS_ON_MAP, linkFitObjects.className = "", iconFitObjects = L.DomUtil.create("span", "", linkFitObjects), iconFitObjects.className = "icon-fit-objects";
            var t = L.DomEvent.stopPropagation;
            L.DomEvent.on(linkFitObjects, "dblclick", t), L.DomEvent.on(linkFitObjects, "mousedown", t), L.DomEvent.on(linkFitObjects, "click", function() {
                fitObjectsOnMap()
            }),linksearch = L.DomUtil.create("a", "", e), linksearch.id = "search_btn", linksearch.href = "#", linksearch.title = la.ADDRESS_SEARCH, linksearch.className = "", linksearch = L.DomUtil.create("span", "", linksearch), linksearch.className = "icon-searchv";
            var t = L.DomEvent.stopPropagation;
            L.DomEvent.on(linksearch, "dblclick", t), L.DomEvent.on(linksearch, "mousedown", t), L.DomEvent.on(linksearch, "click", function() {
            	$('#dialog_address_search').dialog('open');
            }), 
            linkshowpoint = L.DomUtil.create("a", "", e), linkshowpoint.id = "point_btn", linkshowpoint.href = "#", linkshowpoint.title = la.SHOW_POINT, linkshowpoint.className = "", linkshowpoint = L.DomUtil.create("span", "", linkshowpoint), linkshowpoint.className = "icon-showpointv";
            var t = L.DomEvent.stopPropagation;
            L.DomEvent.on(linkshowpoint, "dblclick", t), L.DomEvent.on(linkshowpoint, "mousedown", t), L.DomEvent.on(linkshowpoint, "click", function() {
            	$('#dialog_show_point').dialog('open');
            }),linkRuler = L.DomUtil.create("a", "", e), linkRuler.id = "map_ruler", linkRuler.href = "#", linkRuler.title = la.RULER, linkRuler.className = "", iconRuler = L.DomUtil.create("span", "", linkRuler), iconRuler.className = "icon-ruler disabled";
            var t = L.DomEvent.stopPropagation;
            L.DomEvent.on(linkRuler, "dblclick", t), L.DomEvent.on(linkRuler, "mousedown", t), L.DomEvent.on(linkRuler, "click", function() {
                utilsRuler(), 1 == utilsRulerData.enabled ? iconRuler.className = "icon-ruler" : iconRuler.className = "icon-ruler disabled"
            }), linkMeasure = L.DomUtil.create("a", "", e), linkMeasure.id = "map_measure", linkMeasure.href = "#", linkMeasure.title = la.MEASURE_AREA, linkMeasure.className = "", iconMeasure = L.DomUtil.create("span", "", linkMeasure), iconMeasure.className = "icon-measure disabled";
            var t = L.DomEvent.stopPropagation;
            return L.DomEvent.on(linkMeasure, "dblclick", t), L.DomEvent.on(linkMeasure, "mousedown", t), L.DomEvent.on(linkMeasure, "click", function() {
                utilsArea(), 1 == utilsAreaData.enabled ? iconMeasure.className = "icon-measure" : iconMeasure.className = "icon-measure disabled"
            }), e
        }
    });
    return e
}

function initGraph(e) {
    if (e) {
        var t = e.data,
            a = e.units;
        if ("logic" == e.result_type) var o = !0,
            i = !1;
        else var o = !1,
            i = !1
    } else var t = [],
        a = "",
        o = !1,
        i = !1;
    var s = 3e4,
        n = 2592e6,
        l = {
            xaxis: {
                mode: "time",
                zoomRange: [s, n]
            },
            yaxis: {
                tickFormatter: function(t) {
                    var o = "";
                    return e && (o = Math.round(100 * t) / 100 + " " + a), o
                },
                zoomRange: [0, 0],
                panRange: !1
            },
            selection: {
                mode: "x"
            },
            crosshair: {
                mode: "x"
            },
            lines: {
                show: !0,
                lineWidth: 1,
                fill: !0,
                fillColor: "rgba(43,130,212,0.3)",
                steps: o
            },
            series: {
                lines: {
                    show: !0
                },
                points: {
                    show: i,
                    radius: 1
                }
            },
            colors: ["#2b82d4"],
            grid: {
                hoverable: !0,
                autoHighlight: !0,
                clickable: !0
            },
            zoom: {
                animate: !0,
                trigger: "dblclick",
                amount: 3
            },
            pan: {
                interactive: !1,
                animate: !0
            }
        };
    historyGraphPlot = $.plot($("#bottom_panel_graph_plot"), [t], l), $("#bottom_panel_graph_plot").unbind("plothover"), $("#bottom_panel_graph_plot").bind("plothover", function(e, o, i) {
        if (i) {
            var s = i.datapoint[0],
                n = historyRouteData.graph.data_index[s],
                l = historyRouteData.route[n].dt_tracker;
            document.getElementById("bottom_panel_graph_label").innerHTML = t[n][1] + " " + a + " - " + l
        }
    }), $("#bottom_panel_graph_plot").unbind("plotselected"), $("#bottom_panel_graph_plot").bind("plotselected", function(e, a) {
        historyGraphPlot = $.plot($("#bottom_panel_graph_plot"), [t], $.extend(!0, {}, l, {
            xaxis: {
                min: a.xaxis.from,
                max: a.xaxis.to
            }
        }))
    }), $("#bottom_panel_graph_plot").unbind("plotclick"), $("#bottom_panel_graph_plot").bind("plotclick", function(e, o, i) {
        if (i) {
            var s = i.datapoint[0],
                n = historyRouteData.graph.data_index[s],
                l = historyRouteData.route[n].dt_tracker;
            document.getElementById("bottom_panel_graph_label").innerHTML = t[n][1] + " " + a + " - " + l, historyRouteData.play.position = n, historyRoutePanToPoint(n), historyRouteAddPointMarkerToMap(n), 0 == historyRouteData.play.status && historyRouteShowPoint(n)
        }
    })
}

function graphSetCrosshair(e) {
    var t = parseInt(historyGraphPlot.pointOffset({
            x: e,
            y: 0
        }).left, 10) - historyGraphPlot.getPlotOffset().left,
        a = historyGraphPlot.width(),
        o = parseInt(a / 2, 10);
    t > a - o && historyGraphPlot.pan({
        left: t - (a - o),
        top: 0
    }), o > t && historyGraphPlot.pan({
        left: t - o,
        top: 0
    }), historyGraphPlot.setCrosshair({
        x: e,
        y: 0
    })
}

function graphPanLeft() {
    historyGraphPlot.pan({
        left: -100
    })
}

function graphPanRight() {
    historyGraphPlot.pan({
        left: 100
    })
}

function graphZoomIn() {
    historyGraphPlot.zoom()
}

function graphZoomOut() {
    historyGraphPlot.zoomOut()
}

function initSelectList(e) {
    switch (e) {
        case "map_layer_list":
            var t = document.getElementById("map_layer");
            t.options.length = 0, gsValues.map_osm && t.options.add(new Option("OSM Map", "osm")), gsValues.map_bing && (t.options.add(new Option("Bing Road", "broad")), t.options.add(new Option("Bing Aerial", "baer")), t.options.add(new Option("Bing Hybrid", "bhyb"))), gsValues.map_google && (t.options.add(new Option("Google Streets", "gmap")), t.options.add(new Option("Google Satellite", "gsat")), t.options.add(new Option("Google Hybrid", "ghyb")), t.options.add(new Option("Google Terrain", "gter"))), gsValues.map_mapbox && (t.options.add(new Option("Mapbox Streets", "mbmap")), t.options.add(new Option("Mapbox Satellite", "mbsat"))), gsValues.map_yandex && t.options.add(new Option("Yandex", "yandex"));
            for (var a = 0; a < gsValues.map_custom.length; a++) {
                var o = gsValues.map_custom[a].layer_id,
                    i = gsValues.map_custom[a].name;
                t.options.add(new Option(i, o))
            }
            break;
        case "sound_list":
            var s = document.getElementById("settings_main_chat_notify_sound_file");
            s.options.length = 0;
            var n = document.getElementById("dialog_settings_event_notify_system_sound_file");
            n.options.length = 0, $.ajax({
                type: "POST",
                url: "func/fn_files.php",
                data: {
                    path: "snd"
                },
                dataType: "json",
                cache: !1,
                success: function(e) {
                    for (a = 0; a < e.length; a++) {
                        var t = e[a];
                        s.options.add(new Option(t, t)), n.options.add(new Option(t, t))
                    }
                    s.options.add(new Option(la.NO_SOUND, ""), 0)
                }
            });
            break;
        case "allocate_sound_list":
            var ahsound = document.getElementById("dialog_boarding_allocate_notify_system_sound");
            var ahsoundd = document.getElementById("dialog_boarding_allocate_notify_system_sounddaily");
            ahsound.options.length = 0;
            ahsoundd.options.length = 0;
            $.ajax({
            	  type: "POST",
                  url: "func/fn_files.php",
                  data: {
                      path: "snd"
                  },
                  dataType: "json",
                  cache: !1,
                  async: !1,
                success: function(f) {
                    for (i = 0; i < f.length; i++) {
                        var g = f[i];
                        ahsound.options.add(new Option(g, g));
                        ahsoundd.options.add(new Option(g, g));
                    }
                }
            });
            break;
        case "subaccounts_marker_list":
            var t = document.getElementById("dialog_settings_subaccount_available_markers");
            multiselectClear(t);
            var l = getGroupsPlacesArray("markers");
            multiselectSetGroups(t, l);
            break;
        case "events_route_list":
            var t = document.getElementById("dialog_settings_event_selected_routes");
            multiselectClear(t);
            var l = getGroupsPlacesArray("routes");
            multiselectSetGroups(t, l);
            break;
        case "subaccounts_route_list":
            var t = document.getElementById("dialog_settings_subaccount_available_routes");
            multiselectClear(t);
            var l = getGroupsPlacesArray("routes");
            multiselectSetGroups(t, l);
            break;
        case "events_zone_list":
            var t = document.getElementById("dialog_settings_event_selected_zones");
            multiselectClear(t);
            var l = getGroupsPlacesArray("zones");
            multiselectSetGroups(t, l);
            break;
        case "subaccounts_zone_list":
            var t = document.getElementById("dialog_settings_subaccount_available_zones");
            multiselectClear(t);
            var l = getGroupsPlacesArray("zones");
            multiselectSetGroups(t, l);
            break;
        case "report_zone_list":
            var t = document.getElementById("dialog_report_zone_list");
            var tv = document.getElementById("dialog_report_zone_list_second");
            var tt = document.getElementById("dialog_hotspotadd_zonename");
            var tr = document.getElementById("dialog_hotspotadd_travelroute");
            multiselectClear(t),multiselectClear(tv),multiselectClear(tt);
            var l = getGroupsPlacesArray("zones");
            var lr = getGroupsPlacesArray("routes");
            multiselectSetGroups(t, l);
            multiselectSetGroups(tv, l);
            multiselectSetGroups(tt, l);
            multiselectSetGroups(tr, lr);
            break;
        case "events_object_list":
            var t = document.getElementById("dialog_settings_event_selected_objects");
            multiselectClear(t);
            var d = getGroupsObjectsArray();
            multiselectSetGroups(t, d);
            break;
        case "subaccounts_object_list":
            var t = document.getElementById("dialog_settings_subaccount_available_objects");
            multiselectClear(t);
            var d = getGroupsObjectsArray();
            multiselectSetGroups(t, d);
            break;
        case "rilogbook_object_list":
            var t = document.getElementById("dialog_rilogbook_object_list");
            t.options.length = 0;
            for (var r in settingsObjectData) {
                var _ = settingsObjectData[r];
                "true" == _.active && t.options.add(new Option(_.name, r))
            }
            sortSelectList(t), t.options.add(new Option(la.ALL_OBJECTS, ""), 0), t.value = "";
            break;
        case "dtc_object_list":
            var t = document.getElementById("dialog_dtc_object_list");
            t.options.length = 0;
            for (var r in settingsObjectData) {
                var _ = settingsObjectData[r];
                "true" == _.active && t.options.add(new Option(_.name, r))
            }
            sortSelectList(t), t.options.add(new Option(la.ALL_OBJECTS, ""), 0), t.value = "";
            break;
        case "gallery_object_list":
            var t = document.getElementById("dialog_image_gallery_object_list");
            t.options.length = 0;
            for (var r in settingsObjectData) {
                var _ = settingsObjectData[r];
                "true" == _.active && t.options.add(new Option(_.name, r))
            }
            sortSelectList(t), t.options.add(new Option(la.ALL_OBJECTS, ""), 0), t.value = "";
            break;
        case "history_object_list":
            var t = document.getElementById("side_panel_history_object_list");
            t.options.length = 0;
            for (var r in settingsObjectData) {
                var _ = settingsObjectData[r];
                "true" == _.active && t.options.add(new Option(_.name, r))
            }
            sortSelectList(t);
            break;
        case "report_object_list":
            var t = document.getElementById("dialog_report_object_list");
            multiselectClear(t);           
            var d = getGroupsObjectsArray();   
            var vechiletype=document.getElementById("dialog_report_vechile_type").value;        
            //code done by Vetrivel.N.R            
            var ag = document.getElementById("dialog_rfidtrip_gpsi_objectnameddl");
            var aho = document.getElementById("dialog_boarding_addobject");
            var aho_trip = document.getElementById("dialog_studentadd_object_imei");
            var ahod = document.getElementById("dialog_boarding_addobjectdaily");
            multiselectClear(ag);
            multiselectClear(aho);
            // multiselectClear(aho_trip);
            multiselectClear(ahod);
            multiselectSetGroups(ag, d);
            multiselectSetGroups(aho, d);
            multiselectSetGroups(aho_trip, d);
            multiselectSetGroups(ahod, d);
            if(vechiletype!=''){
                var len=d.length;                
                var groupsarr = new Array();
                for(var i=0;i<len;i++){
                    var itemvalue= new Array();
                    var glen=d[i].items.length;
                    for(var j=0;j<glen;j++){
                        var value=d[i].items[j].value;
                        var name=d[i].items[j].name;
                        var type=d[i].items[j].vechiletype;
                        if(vechiletype==type){
                            itemvalue.push({ value: value, name: name})
                        }
                    }
                    groupsarr.push({ name: d[i].name, id: d[i].id, items: itemvalue });
                }
                groupsarr = sortArrayByElement(groupsarr, 'name');
                 for (var key in groupsarr)
                {
                        var group = groupsarr[key];
                        
                        if (group.id == 0)
                        {
                                arrayMove(groupsarr, key, 0);
                        }
                }
                var d=groupsarr;
            }
            multiselectSetGroups(t, d);
            loadObjectRpt();
            var selectedOpts = $('#dialog_reports_object_listrpt option');
            $(selectedOpts).remove();
              
            break;
        case "cmd_object_list":
            var t = document.getElementById("cmd_object_list");
            t.options.length = 0;
            for (var r in settingsObjectData) {
                var _ = settingsObjectData[r];
                "true" == _.active && t.options.add(new Option(_.name, r))
            }
            sortSelectList(t);
            break;
        case "cmd_object_listnew":
            var t = document.getElementById("cmd_object_listnew");
            t.options.length = 0;
            for (var r in settingsObjectData) {
                var _ = settingsObjectData[r];
                "true" == _.active && t.options.add(new Option(_.name, r))
            }
            sortSelectList(t);
            //code done by Vetrivel.N.R
            break;
        case "object_device_list":
            var t = document.getElementById("dialog_settings_object_edit_device");
            t.options.length = 0;
            for (var r in gsValues.device_list) {
                var _ = gsValues.device_list[r];
                t.options.add(new Option(_.name, _.value))
            }
            break;
        case "object_group_list":
            var t = document.getElementById("dialog_settings_object_edit_group");
            t.options.length = 0;
            for (var r in settingsObjectGroupData) {
                var c = settingsObjectGroupData[r];
                c.name != la.UNGROUPED && t.options.add(new Option(c.name, r))
            }
            sortSelectList(t), t.options.add(new Option(la.UNGROUPED, 0), 0);
            break;
        case "object_driver_list":
            var t = document.getElementById("dialog_settings_object_edit_driver");
            t.options.length = 0;
            for (var r in settingsObjectDriverData) {
                var g = settingsObjectDriverData[r];
                t.options.add(new Option(g.name, r))
            }
            sortSelectList(t), t.options.add(new Option(la.AUTO_ASSIGN, 0), 0), t.options.add(new Option(la.NO_DRIVER, -1), 0);
            break;
        case "object_trailer_list":
            var t = document.getElementById("dialog_settings_object_edit_trailer");
            t.options.length = 0;
            for (var r in settingsObjectTrailerData) {
                var m = settingsObjectTrailerData[r];
                t.options.add(new Option(m.name, r))
            }
            sortSelectList(t), t.options.add(new Option(la.AUTO_ASSIGN, 0), 0), t.options.add(new Option(la.NO_TRAILER, -1), 0);
            break;
        case "email_sms_template_list":
            var u = document.getElementById("dialog_settings_event_notify_email_template");
            u.options.length = 0;
            var p = document.getElementById("dialog_settings_event_notify_sms_template");
            p.options.length = 0;
            for (var r in settingsTemplateData) {
                var v = settingsTemplateData[r];
                u.options.add(new Option(v.name, r)), p.options.add(new Option(v.name, r))
            }
            sortSelectList(u), sortSelectList(p), u.options.add(new Option(la.DEFAULT, 0), 0), p.options.add(new Option(la.DEFAULT, 0), 0);
            break;
        case "places_group_list":
            var y = document.getElementById("dialog_places_marker_group");
            y.options.length = 0;
            var E = document.getElementById("dialog_places_route_group");
            E.options.length = 0;
            var b = document.getElementById("dialog_places_zone_group");
            b.options.length = 0;
            for (var r in placesGroupData.groups) {
                var c = placesGroupData.groups[r];
                c.name != la.UNGROUPED && (y.options.add(new Option(c.name, r)), E.options.add(new Option(c.name, r)), b.options.add(new Option(c.name, r)))
            }
            sortSelectList(y), sortSelectList(E), sortSelectList(b), y.options.add(new Option(la.UNGROUPED, 0), 0), E.options.add(new Option(la.UNGROUPED, 0), 0), b.options.add(new Option(la.UNGROUPED, 0), 0)
    }
}

function resizeGrids() {
    resizeGridObjects(), resizeGridEvents(), resizeGridHistory()
}

function resizeGridObjects(e) {
    void 0 == e ? e = window.innerHeight - guiDragbars.objects : guiDragbars.objects = window.innerHeight - e, 250 > e && (e = 250), e > window.innerHeight - 200 && (e = window.innerHeight - 200);
    var t = window.innerHeight - e - 16,
        a = window.innerHeight - t - 164;
    $("#side_panel_objects_object_data_list_grid").setGridHeight(t - 20), $("#side_panel_objects_object_list_grid").setGridHeight(a), $("#side_panel_objects_dragbar").css("bottom", t + 1)
}

function resizeGridEvents(e) {
    void 0 == e ? e = window.innerHeight - guiDragbars.events : guiDragbars.events = window.innerHeight - e, 250 > e && (e = 250), e > window.innerHeight - 200 && (e = window.innerHeight - 200);
    var t = window.innerHeight - e - 16,
        a = window.innerHeight - t - 194;
    $("#side_panel_events_event_data_list_grid").setGridHeight(t - 20), $("#side_panel_events_event_list_grid").setGridHeight(a), $("#side_panel_events_dragbar").css("bottom", t + 1)
}

function resizeGridHistory(e) {
    void 0 == e ? e = window.innerHeight - guiDragbars.history : guiDragbars.history = window.innerHeight - e, 440 > e && (e = 440), e > window.innerHeight - 200 && (e = window.innerHeight - 200);
    var t = window.innerHeight - e - 16,
        a = window.innerHeight - t - 302;
    $("#side_panel_history_route_data_list_grid").setGridHeight(t - 20), $("#side_panel_history_route_detail_list_grid").setGridHeight(a), $("#side_panel_history_dragbar").css("bottom", t + 1)
}

function initGrids() {
    function e(e) {
        return "gr" == e ? e = ">" : "eq" == e ? e = "=" : "lw" == e && (e = "<"), e
    }

    function t(e) {
        return e = e.substring(0, 10) == moment().format("YYYY-MM-DD") ? e.substring(11, 19) : e.substring(2, 10)
    }
    $("#settings_main_object_list_grid").jqGrid({
        url: "func/fn_settings.objects.php?cmd=load_object_list",
        datatype: "json",
        colNames: [la.NAME, la.IMEI, la.ACTIVE, la.EXPIRES_ON, ""],
        colModel: [{
            name: "name",
            index: "name",
            width: 250
        }, {
            name: "imei",
            index: "imei",
            width: 190
        }, {
            name: "active",
            index: "active",
            width: 120,
            align: "center"
        }, {
            name: "object_expire_dt",
            index: "object_expire_dt",
            width: 150,
            align: "center"
        }, {
            name: "modify",
            index: "modify",
            width: 200,
            align: "center",
            sortable: !1
        }],
        rowNum: 50,
        rowList: [25, 50, 75, 100, 200],
        pager: "#settings_main_object_list_grid_pager",
        sortname: "name",
        sortorder: "asc",
        viewrecords: !0,
        height: "375px",
        width: "950",
        shrinkToFit: !1,
        multiselect: !0,
        beforeSelectRow: function(e, t) {
            return "input" === t.target.tagName.toLowerCase()
        }
    }), $("#settings_main_object_list_grid").jqGrid("navGrid", "#settings_main_object_list_grid_pager", {
        add: !0,
        edit: !1,
        del: !1,
        search: !1,
        addfunc: function() {
            settingsObjectAdd("open")
        }
    }), $("#settings_main_object_list_grid").navButtonAdd("#settings_main_object_list_grid_pager", {
        caption: "",
        title: la.ACTION,
        buttonicon: "ui-icon-action",
        onClickButton: function() {},
        position: "last",
        id: "settings_main_object_list_grid_action_menu_button"
    }), $("#settings_main_object_list_grid_action_menu").menu({
        role: "listbox"
    }), $("#settings_main_object_list_grid_action_menu").hide(), $("#settings_main_object_list_grid_action_menu_button").click(function() {
        return $("#settings_main_object_list_grid_action_menu").toggle().position({
            my: "left bottom",
            at: "right-5 top-5",
            of: this
        }), $(document).one("click", function() {
            $("#settings_main_object_list_grid_action_menu").hide()
        }), !1
    }),   

    $("#dashboard_live_tripwisekm_report").jqGrid({
        url: "func/dashboard.php?cmd=dashboard_live_tripwisereport",
        datatype: "json",
        colNames: [la.NAME,la.TRIPNAME,'No.EMP',la.START,la.END,la.STARTTIME,la.ENDTIME,la.AROUTE_START,la.AROUTE_END,la.UNIT_KM,la.FREEZED_KM,la.EMPTYKM,la.DURATION,la.STARTING_FUEL,la.ENDING_FUEL,la.FUEL_FILLINGS,la.FUEL_CONSUMPTION,la.KMPL,'Close','Userid'],
        colModel: [{
            name: "name",
            index: "name",
            width: 100
        },{
            name: "tripname",
            index: "tripname",
            width: 150
        },{
            name: "noemp",
            index: "noemp",
            width: 60,
            align: "center",
            sortable: !1,
            hidden:!0
        },{
            name: "start",
            index: "start",
            width: 100
        },{
            name: "end",
            index: "end",
            width: 100
        },{
            name: "starttime",
            index: "starttime",
            width: 70,
            align: "center"
        },{
            name: "endtime",
            index: "endtime",
            width: 70,
            align: "center"
        },{
            name: "astarttime",
            index: "astarttime",
            width: 90,
            align: "center"
        },{
            name: "aendtime",
            index: "aendtime",
            width: 90,
            align: "center",
            sorttype: "time"
        },{
            name: "km",
            index: "km",
            width: 40,
            align: "center"
        },{
            name: "freezed",
            index: "freezed",
            width: 60,
            align: "center"
        },{
            name: "emptykm",
            index: "emptykm",
            width: 60,
            align: "center"
        },{
            name: "duration",
            index: "duration",
            width: 60,
            align: "center",
            sortable: !1
        },{
            name: "fuelstart",
            index: "fuelstart",
            width: 60,
            align: "center",
            sortable: !1
        },{
            name: "fuelend",
            index: "fuelend",
            width: 60,
            align: "center",
            sortable: !1
        },{
            name: "filling",
            index: "filling",
            width: 60,
            align: "center",
            sortable: !1
        },{
            name: "diffrence",
            index: "diffrence",
            width: 60,
            align: "center",
            sortable: !1
        },{
            name: "kmpl",
            index: "kmpl",
            width: 60,
            align: "center",
            sortable: !1
        },{
            name: "tripstatus",
            index: "tripstatus",
            width: 60,
            align: "center",
            sortable: !1,
            hidden:!0
        },{
            name: "userid",
            index: "userid",
            width: 60,
            align: "center",
            sortable: !1,
            hidden:!0
        }],
        rowNum: 50,
        rowList: [50, 75, 100, 200],
        pager: "#dashboard_live_tripwisekm_report_grid_pager",
        sortname: "name",
        sortorder: "asc",
        viewrecords: !1,
        height: "335px",
        width: "860",
        shrinkToFit: !1,
        multiselect: !1,

        loadComplete: function () {
          var rowIds = $('#dashboard_live_tripwisekm_report').jqGrid('getDataIDs');
           for (i = 0; i < rowIds.length; i++) {
               rowData = $('#dashboard_live_tripwisekm_report').jqGrid('getRowData', rowIds[i]);
               if (rowData['tripstatus'] == "Yes") {
                    $('#dashboard_live_tripwisekm_report').jqGrid('setRowData', rowIds[i], false, "gridTripEnd");
               }else{
                    $('#dashboard_live_tripwisekm_report').jqGrid('setRowData', rowIds[i], false, "gridTripWait");
               }

               if(rowData['userid'] == 1901) {
                    $("#dashboard_live_tripwisekm_report").showCol("noemp")
               }
          }
        }
    }), $("#dashboard_live_tripwisekm_report").jqGrid("navGrid", "#dashboard_live_tripwisekm_report_grid_pager", {
        add: !1,
        edit: !1,
        del: !1,
        search: !1
    }),  
    $("#dashboard_service_list_grid").jqGrid({
        url:"func/dashboard.php?cmd=dashboarServicelist",
        datatype:"json",
        colNames:[la.OBJECT,la.NAME,la.ODOMETER,la.ENGINE_HOURS,la.LAST_SERVICE,la.STATUS,''],
        colModel:[{
            name: "object",
            index: "object",
            width: 100,
             align: "center",
            sortable: !0
        },{
            name: "name",
            index: "name",
            width: 150,
            align: "center",
            sortable: !0
        },{
            name: "odo",
            index: "odo",
            width: 150,
            align: "center",
            sortable: !0
        },{
            name: "engine",
            index: "engine",
            width: 150,
            align: "center",
            sortable: !0
        },{
            name: "lastservice",
            index: "lastservice",
            width: 150,
            align: "center",
            sortable: !0
        },{
            name: "daysleft",
            index: "daysleft",
            width: 150,
            align: "center",
            sortable: !0
        },{
            name: "modify",
            index: "modify",
            width: 90,
            align: "center",
            sortable: !0
        }],
        rowNum: 50,
        rowList: [50, 75, 100, 200],
        pager: "#dashboard_service_list_grid_pager",
        sortname: "name",
        sortorder: "asc",
        viewrecords: !1,
        height: "440px",
        width: "auto",
        shrinkToFit: !1,
        multiselect: !1
    }),$("#dashboard_service_list_grid").jqGrid("navGrid", "#dashboard_service_list_grid_pager", {
        add: !1,
        edit: !1,
        del: !1,
        search: !1
    }),

     $("#settings_object_sensor_list_grid").jqGrid({
        url: "func/fn_settings.sensors.php",
        datatype: "json",
        colNames: [la.NAME, la.TYPE, la.PARAMETER, ""],
        colModel: [{
            name: "name",
            index: "name",
            width: 220,
            sortable: !0
        }, {
            name: "type",
            index: "type",
            width: 205,
            align: "center",
            sortable: !1
        }, {
            name: "param",
            index: "param",
            width: 158,
            align: "center",
            sortable: !1
        }, {
            name: "modify",
            index: "modify",
            width: 45,
            align: "center",
            sortable: !1
        }],
        rowNum: 512,
        pager: "#settings_object_sensor_list_grid_pager",
        pgbuttons: !1,
        pgtext: "",
        recordtext: "",
        emptyrecords: "",
        sortname: "name",
        sortorder: "asc",
        viewrecords: !0,
        width: "690",
        height: "347",
        shrinkToFit: !1,
        multiselect: !0,
        beforeSelectRow: function(e, t) {
            return "input" === t.target.tagName.toLowerCase()
        }
    }), $("#settings_object_sensor_list_grid").jqGrid("navGrid", "#settings_object_sensor_list_grid_pager", {
        add: !0,
        edit: !1,
        del: !1,
        search: !1,
        addfunc: function() {
            settingsObjectSensorProperties("add")
        }
    }), $("#settings_object_sensor_list_grid").navButtonAdd("#settings_object_sensor_list_grid_pager", {
        caption: "",
        title: la.ACTION,
        buttonicon: "ui-icon-action",
        onClickButton: function() {},
        position: "last",
        id: "settings_object_sensor_list_grid_action_menu_button"
    }), $("#settings_object_sensor_list_grid_action_menu").menu({
        role: "listbox"
    }), $("#settings_object_sensor_list_grid_action_menu").hide(), $("#settings_object_sensor_list_grid_action_menu_button").click(function() {
        return $("#settings_object_sensor_list_grid_action_menu").toggle().position({
            my: "left bottom",
            at: "right-5 top-5",
            of: this
        }), $(document).one("click", function() {
            $("#settings_object_sensor_list_grid_action_menu").hide()
        }), !1
    }), $("#settings_object_sensor_calibration_list_grid").jqGrid({
        datatype: "local",
        colNames: ["X", "Y", ""],
        colModel: [{
            name: "x",
            index: "x",
            width: 111,
            sortable: !0,
            sorttype: "int"
        }, {
            name: "y",
            index: "y",
            width: 110,
            sortable: !1
        }, {
            name: "modify",
            index: "modify",
            width: 30,
            align: "center",
            sortable: !1
        }],
        width: "285",
        height: "306",
        rowNum: 100,
        shrinkToFit: !1
    }), $("#settings_object_service_list_grid").jqGrid({
        url: "func/fn_settings.service.php",
        datatype: "json",
        colNames: [la.NAME, la.STATUS, ""],
        colModel: [{
            name: "name",
            index: "name",
            width: 220
        }, {
            name: "status",
            index: "status",
            width: 368,
            sortable: !1
        }, {
            name: "modify",
            index: "modify",
            width: 45,
            align: "center",
            sortable: !1
        }],
        rowNum: 512,
        pager: "#settings_object_service_list_grid_pager",
        pgbuttons: !1,
        pgtext: "",
        recordtext: "",
        emptyrecords: "",
        sortname: "name",
        sortorder: "asc",
        viewrecords: !0,
        width: "690",
        height: "347",
        shrinkToFit: !1,
        multiselect: !0,
        beforeSelectRow: function(e, t) {
            return "input" === t.target.tagName.toLowerCase()
        }
    }), $("#settings_object_service_list_grid").jqGrid("navGrid", "#settings_object_service_list_grid_pager", {
        add: !0,
        edit: !1,
        del: !1,
        search: !1,
        addfunc: function() {
            settingsObjectServiceProperties("add")
        }
    }), $("#settings_object_service_list_grid").navButtonAdd("#settings_object_service_list_grid_pager", {
        caption: "",
        title: la.ACTION,
        buttonicon: "ui-icon-action",
        onClickButton: function() {},
        position: "last",
        id: "settings_object_service_list_grid_action_menu_button"
    }), $("#settings_object_service_list_grid_action_menu").menu({
        role: "listbox"
    }), $("#settings_object_service_list_grid_action_menu").hide(), $("#settings_object_service_list_grid_action_menu_button").click(function() {
        return $("#settings_object_service_list_grid_action_menu").toggle().position({
            my: "left bottom",
            at: "right-5 top-5",
            of: this
        }), $(document).one("click", function() {
            $("#settings_object_service_list_grid_action_menu").hide()
        }), !1
    }), $("#settings_object_custom_fields_list_grid").jqGrid({
        url: "func/fn_settings.customfields.php",
        datatype: "json",
        colNames: [la.NAME, la.VALUE, la.DATA_LIST, la.POPUP, ""],
        colModel: [{
            name: "name",
            index: "name",
            width: 220,
            sortable: !0
        }, {
            name: "value",
            index: "value",
            width: 178,
            align: "center",
            sortable: !0
        }, {
            name: "data_list",
            index: "data_list",
            width: 90,
            align: "center",
            sortable: !1
        }, {
            name: "popup",
            index: "popup",
            width: 90,
            align: "center",
            sortable: !1
        }, {
            name: "modify",
            index: "modify",
            width: 45,
            align: "center",
            sortable: !1
        }],
        rowNum: 512,
        pager: "#settings_object_custom_fields_list_grid_pager",
        pgbuttons: !1,
        pgtext: "",
        recordtext: "",
        emptyrecords: "",
        sortname: "name",
        sortorder: "asc",
        viewrecords: !0,
        width: "690",
        height: "347",
        shrinkToFit: !1,
        multiselect: !0,
        beforeSelectRow: function(e, t) {
            return "input" === t.target.tagName.toLowerCase()
        }
    }), $("#settings_object_custom_fields_list_grid").jqGrid("navGrid", "#settings_object_custom_fields_list_grid_pager", {
        add: !0,
        edit: !1,
        del: !1,
        search: !1,
        addfunc: function() {
            settingsObjectCustomFieldProperties("add")
        }
    }), $("#settings_object_custom_fields_list_grid").navButtonAdd("#settings_object_custom_fields_list_grid_pager", {
        caption: "",
        title: la.ACTION,
        buttonicon: "ui-icon-action",
        onClickButton: function() {},
        position: "last",
        id: "settings_object_custom_fields_list_grid_action_menu_button"
    }), $("#settings_object_custom_fields_list_grid_action_menu").menu({
        role: "listbox"
    }), $("#settings_object_custom_fields_list_grid_action_menu").hide(), $("#settings_object_custom_fields_list_grid_action_menu_button").click(function() {
        return $("#settings_object_custom_fields_list_grid_action_menu").toggle().position({
            my: "left bottom",
            at: "right-5 top-5",
            of: this
        }), $(document).one("click", function() {
            $("#settings_object_custom_fields_list_grid_action_menu").hide()
        }), !1
    }), $("#settings_object_info_list_grid").jqGrid({
        url: "func/fn_settings.objects.php",
        datatype: "json",
        colNames: [la.DATA, la.VALUE],
        colModel: [{
            name: "data",
            index: "data",
            width: 170,
            sortable: !1
        }, {
            name: "value",
            index: "value",
            width: 493,
            sortable: !1
        }],
        rowNum: 512,
        pager: "#settings_object_info_list_grid_pager",
        pgbuttons: !1,
        pgtext: "",
        recordtext: "",
        emptyrecords: "",
        sortname: "data",
        sortorder: "asc",
        viewrecords: !0,
        width: "690",
        height: "347",
        shrinkToFit: !1
    }), $("#settings_object_info_list_grid").jqGrid("navGrid", "#settings_object_info_list_grid_pager", {
        add: !1,
        edit: !1,
        del: !1,
        search: !1
    }), $("#settings_main_object_group_list_grid").jqGrid({
        url: "func/fn_settings.groups.php?cmd=load_object_group_list",
        datatype: "json",
        colNames: [la.NAME, la.OBJECTS, la.DESCRIPTION, ""],
        colModel: [{
            name: "group_name",
            index: "group_name",
            width: 300,
            sortable: !0
        }, {
            name: "objects",
            index: "objects",
            width: 90,
            align: "center",
            sortable: !1
        }, {
            name: "description",
            index: "description",
            width: 355,
            sortable: !1
        }, {
            name: "modify",
            index: "modify",
            width: 145,
            align: "center",
            sortable: !1
        }],
        rowNum: 50,
        rowList: [25, 50, 75, 100, 200],
        pager: "#settings_main_object_group_list_grid_pager",
        sortname: "group_name",
        sortorder: "asc",
        viewrecords: !0,
        height: "375px",
        width: "950",
        shrinkToFit: !1,
        multiselect: !0,
        beforeSelectRow: function(e, t) {
            return "input" === t.target.tagName.toLowerCase()
        }
    }), $("#settings_main_object_group_list_grid").jqGrid("navGrid", "#settings_main_object_group_list_grid_pager", {
        add: !0,
        edit: !1,
        del: !1,
        search: !1,
        addfunc: function() {
            settingsObjectGroupProperties("add")
        }
    }), $("#settings_main_object_group_list_grid").navButtonAdd("#settings_main_object_group_list_grid_pager", {
        caption: "",
        title: la.ACTION,
        buttonicon: "ui-icon-action",
        onClickButton: function() {},
        position: "last",
        id: "settings_main_object_group_list_grid_action_menu_button"
    }), $("#settings_main_object_group_list_grid_action_menu").menu({
        role: "listbox"
    }), $("#settings_main_object_group_list_grid_action_menu").hide(), $("#settings_main_object_group_list_grid_action_menu_button").click(function() {
        return $("#settings_main_object_group_list_grid_action_menu").toggle().position({
            my: "left bottom",
            at: "right-5 top-5",
            of: this
        }), $(document).one("click", function() {
            $("#settings_main_object_group_list_grid_action_menu").hide()
        }), !1
    }), $("#settings_main_object_driver_list_grid").jqGrid({
        url: "func/fn_settings.drivers.php?cmd=load_object_driver_list",
        datatype: "json",
        colNames: [la.NAME, la.ID_NUMBER, la.DESCRIPTION, ""],
        colModel: [{
            name: "driver_name",
            index: "driver_name",
            width: 298,
            sortable: !0
        }, {
            name: "idn",
            index: "idn",
            width: 155,
            sortable: !1
        }, {
            name: "description",
            index: "description",
            width: 300,
            sortable: !1
        }, {
            name: "modify",
            index: "modify",
            width: 145,
            align: "center",
            sortable: !1
        }],
        rowNum: 50,
        rowList: [25, 50, 75, 100, 200],
        pager: "#settings_main_object_driver_list_grid_pager",
        sortname: "driver_name",
        sortorder: "asc",
        viewrecords: !0,
        height: "375px",
        width: "950",
        shrinkToFit: !1,
        multiselect: !0,
        beforeSelectRow: function(e, t) {
            return "input" === t.target.tagName.toLowerCase()
        }
    }), $("#settings_main_object_driver_list_grid").jqGrid("navGrid", "#settings_main_object_driver_list_grid_pager", {
        add: !0,
        edit: !1,
        del: !1,
        search: !1,
        addfunc: function() {
            settingsObjectDriverProperties("add")
        }
    }), $("#settings_main_object_driver_list_grid").navButtonAdd("#settings_main_object_driver_list_grid_pager", {
        caption: "",
        title: la.ACTION,
        buttonicon: "ui-icon-action",
        onClickButton: function() {},
        position: "last",
        id: "settings_main_object_driver_list_grid_action_menu_button"
    }), $("#settings_main_object_driver_list_grid_action_menu").menu({
        role: "listbox"
    }), $("#settings_main_object_driver_list_grid_action_menu").hide(), $("#settings_main_object_driver_list_grid_action_menu_button").click(function() {
        return $("#settings_main_object_driver_list_grid_action_menu").toggle().position({
            my: "left bottom",
            at: "right-5 top-5",
            of: this
        }), $(document).one("click", function() {
            $("#settings_main_object_driver_list_grid_action_menu").hide()
        }), !1
    }), $("#settings_main_object_passenger_list_grid").jqGrid({
        url: "func/fn_settings.passengers.php?cmd=load_object_passenger_list",
        datatype: "json",
        colNames: [la.NAME, la.ID_NUMBER, la.DESCRIPTION, ""],
        colModel: [{
            name: "passenger_name",
            index: "passenger_name",
            width: 258,
            sortable: !0
        }, {
            name: "idn",
            index: "idn",
            width: 175,
            sortable: !1
        }, {
            name: "description",
            index: "description",
            width: 260,
            sortable: !1
        }, {
            name: "modify",
            index: "modify",
            width: 185,
            align: "center",
            sortable: !1
        }],
        rowNum: 50,
        rowList: [25, 50, 75, 100, 200],
        pager: "#settings_main_object_passenger_list_grid_pager",
        sortname: "passenger_name",
        sortorder: "asc",
        viewrecords: !0,
        height: "375px",
        width: "950",
        shrinkToFit: !1,
        multiselect: !0,
        beforeSelectRow: function(e, t) {
            return "input" === t.target.tagName.toLowerCase()
        }
    }), $("#settings_main_object_passenger_list_grid").jqGrid("navGrid", "#settings_main_object_passenger_list_grid_pager", {
        add: !0,
        edit: !1,
        del: !1,
        search: !1,
        addfunc: function() {
            settingsObjectPassengerProperties("add")
        }
    }), $("#settings_main_object_passenger_list_grid").navButtonAdd("#settings_main_object_passenger_list_grid_pager", {
        caption: "",
        title: la.ACTION,
        buttonicon: "ui-icon-action",
        onClickButton: function() {},
        position: "last",
        id: "settings_main_object_passenger_list_grid_action_menu_button"
    }), $("#settings_main_object_passenger_list_grid_action_menu").menu({
        role: "listbox"
    }), $("#settings_main_object_passenger_list_grid_action_menu").hide(), $("#settings_main_object_passenger_list_grid_action_menu_button").click(function() {
        return $("#settings_main_object_passenger_list_grid_action_menu").toggle().position({
            my: "left bottom",
            at: "right-5 top-5",
            of: this
        }), $(document).one("click", function() {
            $("#settings_main_object_passenger_list_grid_action_menu").hide()
        }), !1
    }), $("#settings_main_object_trailer_list_grid").jqGrid({
        url: "func/fn_settings.trailers.php?cmd=load_object_trailer_list",
        datatype: "json",
        colNames: [la.NAME, la.DESCRIPTION, ""],
        colModel: [{
            name: "trailer_name",
            index: "trailer_name",
            width: 298,
            sortable: !0
        }, {
            name: "description",
            index: "description",
            width: 500,
            sortable: !1
        }, {
            name: "modify",
            index: "modify",
            width: 145,
            align: "center",
            sortable: !1
        }],
        rowNum: 50,
        rowList: [25, 50, 75, 100, 200],
        pager: "#settings_main_object_trailer_list_grid_pager",
        sortname: "trailer_name",
        sortorder: "asc",
        viewrecords: !0,
        height: "375px",
        width: "950",
        shrinkToFit: !1,
        multiselect: !0,
        beforeSelectRow: function(e, t) {
            return "input" === t.target.tagName.toLowerCase()
        }
    }), $("#settings_main_object_trailer_list_grid").jqGrid("navGrid", "#settings_main_object_trailer_list_grid_pager", {
        add: !0,
        edit: !1,
        del: !1,
        search: !1,
        addfunc: function() {
            settingsObjectTrailerProperties("add")
        }
    }), $("#settings_main_object_trailer_list_grid").navButtonAdd("#settings_main_object_trailer_list_grid_pager", {
        caption: "",
        title: la.ACTION,
        buttonicon: "ui-icon-action",
        onClickButton: function() {},
        position: "last",
        id: "settings_main_object_trailer_list_grid_action_menu_button"
    }), $("#settings_main_object_trailer_list_grid_action_menu").menu({
        role: "listbox"
    }), $("#settings_main_object_trailer_list_grid_action_menu").hide(), $("#settings_main_object_trailer_list_grid_action_menu_button").click(function() {
        return $("#settings_main_object_trailer_list_grid_action_menu").toggle().position({
            my: "left bottom",
            at: "right-5 top-5",
            of: this
        }), $(document).one("click", function() {
            $("#settings_main_object_trailer_list_grid_action_menu").hide()
        }), !1
    }), $("#settings_main_events_event_list_grid").jqGrid({
        url: "func/fn_settings.events.php?cmd=load_event_list",
        datatype: "json",
        colNames: [la.NAME, la.ACTIVE, la.SYSTEM, la.OBJECT, la.EMAIL, la.SMS, ""],
        colModel: [{
            name: "name",
            index: "name",
            width: 220,
            sortable: !0
        }, {
            name: "active",
            index: "active",
            width: 120,
            align: "center",
            sortable: !1
        }, {
            name: "notify",
            index: "notify",
            width: 120,
            align: "center",
            sortable: !1
        },{
            name: "object",
			index: "object",
            width: 80,
            align: "center",
            sortable: !1
        }, {
            name: "email",
            index: "email",
            width: 120,
            align: "center",
            sortable: !1
        }, {
            name: "sms",
            index: "sms",
            width: 120,
            align: "center",
            sortable: !1
        }, {
            name: "modify",
            index: "modify",
            width: 125,
            align: "center",
            sortable: !1
        }],
        rowNum: 50,
        rowList: [25, 50, 75, 100, 200],
        pager: "#settings_main_events_event_list_grid_pager",
        sortname: "name",
        sortorder: "asc",
        viewrecords: !0,
        height: "447px",
        width: "950",
        shrinkToFit: !1,
        multiselect: !0,
        beforeSelectRow: function(e, t) {
            return "input" === t.target.tagName.toLowerCase()
        }
    }), $("#settings_main_events_event_list_grid").jqGrid("navGrid", "#settings_main_events_event_list_grid_pager", {
        add: !0,
        edit: !1,
        del: !1,
        search: !1,
        addfunc: function() {
            settingsEventProperties("add")
        }
    }), $("#settings_main_events_event_list_grid").navButtonAdd("#settings_main_events_event_list_grid_pager", {
        caption: "",
        title: la.ACTION,
        buttonicon: "ui-icon-action",
        onClickButton: function() {},
        position: "last",
        id: "settings_main_events_event_list_grid_action_menu_button"
    }), $("#settings_main_events_event_list_grid_action_menu").menu({
        role: "listbox"
    }), $("#settings_main_events_event_list_grid_action_menu").hide(), $("#settings_main_events_event_list_grid_action_menu_button").click(function() {
        return $("#settings_main_events_event_list_grid_action_menu").toggle().position({
            my: "left bottom",
            at: "right-5 top-5",
            of: this
        }), $(document).one("click", function() {
            $("#settings_main_events_event_list_grid_action_menu").hide()
        }), !1
    }), $("#settings_event_param_sensor_condition_list_grid").jqGrid({
        datatype: "local",
        colNames: [la.SOURCE, "", la.VALUE, ""],
        colModel: [{
            name: "src",
            index: "src",
            width: 85,
            sortable: !0,
            sorttype: "text"
        }, {
            name: "cn",
            index: "cn",
            width: 35,
            align: "center",
            sortable: !1,
            formatter: e
        }, {
            name: "val",
            index: "val",
            width: 83,
            align: "center",
            sortable: !1
        }, {
            name: "modify",
            index: "modify",
            width: 30,
            align: "center",
            sortable: !1
        }],
        width: "255",
        height: "120",
        rowNum: 15,
        shrinkToFit: !1
    }), $("#settings_main_templates_template_list_grid").jqGrid({
        url: "func/fn_settings.templates.php?cmd=load_template_list",
        datatype: "json",
        colNames: [la.NAME, la.DESCRIPTION, ""],
        colModel: [{
            name: "name",
            index: "name",
            width: 353,
            sortable: !0
        }, {
            name: "description",
            index: "description",
            width: 500,
            sortable: !1
        }, {
            name: "modify",
            index: "modify",
            width: 135,
            align: "center",
            sortable: !1
        }],
        rowNum: 50,
        rowList: [25, 50, 75, 100, 200],
        pager: "#settings_main_templates_template_list_grid_pager",
        sortname: "name",
        sortorder: "asc",
        viewrecords: !0,
        height: "447px",
        width: "950",
        shrinkToFit: !1,
        multiselect: !0,
        beforeSelectRow: function(e, t) {
            return "input" === t.target.tagName.toLowerCase()
        }
    }), $("#settings_main_templates_template_list_grid").jqGrid("navGrid", "#settings_main_templates_template_list_grid_pager", {
        add: !0,
        edit: !1,
        del: !1,
        search: !1,
        addfunc: function() {
            settingsTemplateProperties("add")
        }
    }), $("#settings_main_templates_template_list_grid").navButtonAdd("#settings_main_templates_template_list_grid_pager", {
        caption: "",
        title: la.ACTION,
        buttonicon: "ui-icon-action",
        onClickButton: function() {},
        position: "last",
        id: "settings_main_templates_template_list_grid_action_menu_button"
    }), $("#settings_main_templates_template_list_grid_action_menu").menu({
        role: "listbox"
    }), $("#settings_main_templates_template_list_grid_action_menu").hide(), $("#settings_main_templates_template_list_grid_action_menu_button").click(function() {
        return $("#settings_main_templates_template_list_grid_action_menu").toggle().position({
            my: "left bottom",
            at: "right-5 top-5",
            of: this
        }), $(document).one("click", function() {
            $("#settings_main_templates_template_list_grid_action_menu").hide()
        }), !1
    }), $("#settings_main_subaccount_list_grid").jqGrid({
        url: "func/fn_settings.subaccounts.php?cmd=load_subaccount_list",
        datatype: "json",
        colNames: [la.EMAIL, la.ACTIVE, la.OBJECTS, la.PLACES, ""],
        colModel: [{
            name: "email",
            index: "email",
            width: 350,
            sortable: !0
        }, {
            name: "active",
            index: "active",
            width: 100,
            align: "center",
            sortable: !1
        }, {
            name: "objects",
            index: "objects",
            width: 100,
            align: "center",
            sortable: !1
        }, {
            name: "places",
            index: "places",
            width: 220,
            align: "center",
            sortable: !1
        }, {
            name: "modify",
            index: "modify",
            width: 150,
            align: "center",
            sortable: !1
        }],
        rowNum: 50,
        rowList: [25, 50, 75, 100, 200],
        pager: "#settings_main_subaccount_list_grid_pager",
        sortname: "email",
        sortorder: "asc",
        viewrecords: !0,
        height: "397px",
        width: "950",
        shrinkToFit: !1,
        multiselect: !0,
        beforeSelectRow: function(e, t) {
            return "input" === t.target.tagName.toLowerCase()
        }
    }), $("#settings_main_subaccount_list_grid").jqGrid("navGrid", "#settings_main_subaccount_list_grid_pager", {
        add: !0,
        edit: !1,
        del: !1,
        search: !1,
        addfunc: function() {
            settingsSubaccountProperties("add")
        }
    }), $("#settings_main_subaccount_list_grid").navButtonAdd("#settings_main_subaccount_list_grid_pager", {
        caption: "",
        title: la.ACTION,
        buttonicon: "ui-icon-action",
        onClickButton: function() {},
        position: "last",
        id: "settings_main_subaccount_list_grid_action_menu_button"
    }), $("#settings_main_subaccount_list_grid_action_menu").menu({
        role: "listbox"
    }), $("#settings_main_subaccount_list_grid_action_menu").hide(), $("#settings_main_subaccount_list_grid_action_menu_button").click(function() {
        return $("#settings_main_subaccount_list_grid_action_menu").toggle().position({
            my: "left bottom",
            at: "right-5 top-5",
            of: this
        }), $(document).one("click", function() {
            $("#settings_main_subaccount_list_grid_action_menu").hide()
        }), !1
    });
    var a = '<div style="float: left; margin-top: 2px; width: 25px;">';
    a += '<center><input id="object_group_visible_{0}" type="checkbox" onClick="objectGroupVisibleTrigger({0});"></center>', a += "</div>", a += '<div style="float: left; margin-top: 2px; width: 25px;">', a += '<center><input id="object_group_follow_{0}" type="checkbox" onClick="objectGroupFollowTrigger({0});"></center>', a += "</div>", a += '<div style="float: right;"><span id="object_group_name_{0}"></span> ({1})</div>', $("#side_panel_objects_object_list_grid").jqGrid({
        datatype: "local",
        colNames: ["", "", "", '<a href="#" onclick="objectVisibleAllTrigger();"><img  title="' + la.SHOW_HIDE_ALL + '" src="theme/images/eye.svg" width="14px" /></a>', '<a href="#" onclick="objectFollowAllTrigger();"><img style="padding-left: 0px !important;" title="' + la.FOLLOW_UNFOLLOW_ALL + '" src="theme/images/follow.svg" width="14px" /></a>', "", la.OBJECT, la.UNIT_SPEED, '<img title="' + la.CONNECTION_STATUS + '" src="theme/images/connection.svg" width="14px"/>', "","",""],
        colModel: [{
            name: "search",
            index: "search",
            hidden: !0
        }, {
            name: "name_sort",
            index: "name_sort",
            hidden: !0
        }, {
            name: "group_id",
            index: "group_id"
        }, {
            name: "show",
            index: "show",
            width: 20,
            sortable: !1,
            align: "center"
        }, {
            name: "follow",
            index: "follow",
            width: 20,
            sortable: !1,
            align: "center"
        }, {
            name: "icon",
            index: "icon",
            width: 28,
            sortable: !1
        }, {
            name: "name",
            index: "name_sort",
            width: 148,
            title: !1
        }, {
            name: "speed",
            index: "speed",
            width: 28,
            sortable: !1,
            align: "center"
        }, {
            name: "connection",
            index: "connection",
            width: 22,
            sortable: !1,
            align: "center"
        }, {
            name: "menu",
            index: "menu",
            width: 22,
            sortable: !1,
            align: "center"
        },{
            name: "myimei",
            index: "myimei",
            hidden: true,
            sortable: false,
        },{
            name: "groupsearch",
            index: "groupsearch",
            hidden: true,
            sortable: false,
        }//added by vetrivel.NR
        ],
        rowNum: 2048,
        viewrecords: !0,
        grouping: !0,
        groupingView: {
            groupField: ["group_id"],
            groupColumnShow: [!1],
            groupText: [a],
            groupCollapse: settingsUserData.groups_collapsed.objects,
            groupOrder: ["asc"],
            groupDataSorted: [!0]
        },
        width: "340",
        shrinkToFit: !1,
        loadComplete: function() {
            for (var e in settingsObjectGroupData) null != document.getElementById("object_group_name_" + e) && (document.getElementById("object_group_name_" + e).innerHTML = settingsObjectGroupData[e].name);
            "" != objectsData && (objectAddAllToMap(), objectUpdateList());
            for (var t = $(this).getDataIDs(), a = 0; a < t.length; a++) {
                var o = t[a];
                $("#object_action_menu_" + o).click(function() {
                    return $("#side_panel_objects_action_menu").toggle().position({
                        my: "left top",
                        at: "right bottom",
                        of: this
                    }), menuOnItem = $(this).attr("tag"), $(document).one("click", function() {
                        $("#side_panel_objects_action_menu").hide()
                    }), !1
                })
            }
        },
        onCellSelect: function(e, t) {
            objectSelect(e), 5 == t ? objectPanToZoom(e) : objectPanTo(e)
        }
    }), $("#side_panel_objects_object_list_grid").setCaption('<div class="row4">									<div class="width80">										<input id="side_panel_objects_object_list_search" class="inputbox-search" type="text" value="" placeholder="' + la.SEARCH + '" maxlength="25">									</div>									<div class="float-right">										<a href="#" onclick="objectReloadData();">										<div class="panel-button"  title="' + la.RELOAD + '">											<img src="theme/images/refresh-color.svg" width="16px" border="0"/>										</div>										</a>										<a href="#" onclick="settingsObjectAdd(\'open\');">										<div class="panel-button"  title="' + la.ADD_OBJECT + '">											<img src="theme/images/object-add.svg" width="16px" border="0"/>										</div>										</a>									</div>								</div>'), $("#side_panel_objects_object_list_search").bind("keyup", function() {
        //code below updated by vetrivel.N 22-9-2016   
        var c = $("#side_panel_objects_object_list_grid"),b = c.jqGrid("getGridParam", "postData");     
        $.extend(b, {
            filters: JSON.stringify({
            	groupOp: "AND",
            	rules: [],
                groups: [
                    {
                        groupOp: "OR",
                        rules: [
                            { field: "search", 		op: "cn", data: this.value.toLowerCase() },
                            { field: "groupsearch", op: "cn", data: this.value.toLowerCase() },
                            { field: "myimei", 		op: "cn", data: this.value },
                        ],
                        groups: []
                    }
                ]
            })
        }),c.jqGrid("setGridParam", {
        	search: true,
            postData: b
        }),c.trigger("reloadGrid")

        
    }), $("#side_panel_objects_object_data_list_grid").jqGrid({
        datatype: "local",
        colNames: [la.DATA, la.VALUE],
        colModel: [{
            name: "data",
            index: "data",
            width: 110,
            sortable: !1
        }, {
            name: "value",
            index: "value",
            width: 203,
            sortable: !1
        }],
        width: "340",
        height: "155",
        rowNum: 512,
        shrinkToFit: !1
    }), $("#side_panel_events_event_list_grid").jqGrid({
        url: "func/fn_events.php?cmd=load_event_list",
        datatype: "json",
        colNames: [la.TIME, la.OBJECT, la.EVENT],
        colModel: [{
            name: "dt_tracker",
            index: "dt_tracker",
            width: 50,
            sorttype: "datetime",
            formatter: t,
            align: "left"
        }, {
            name: "object",
            index: "object",
            width: 105,
            sortable: !1,
            align: "left"
        }, {
            name: "event",
            index: "event",
            width: 153,
            sortable: !1,
            align: "left"
        }],
        recordtext: "",
        emptyrecords: "",
        rowNum: 25,
        rowList: [25, 50, 75, 100, 200],
        pager: "#side_panel_events_event_list_grid_pager",
        sortname: "dt_tracker",
        sortorder: "desc",
        viewrecords: !0,
        width: "340",
        shrinkToFit: !1,
        onSelectRow: function(e) {
            eventsShowEvent(e)
        }
    }), $("#side_panel_events_event_list_grid").setCaption('<div class="row4">									<div class="width80">										<input id="side_panel_events_event_list_search" class="inputbox-search" type="text" value="" placeholder="' + la.SEARCH + '" maxlength="25">									</div>									<div class="float-right">										<a href="#" onclick="eventsReloadData();">										<div class="panel-button"  title="' + la.RELOAD + '">											<img src="theme/images/refresh-color.svg" width="16px" border="0"/>										</div>										</a>										<a href="#" onclick="eventsDeleteAll();">										<div class="panel-button"  title="' + la.DELETE_ALL_EVENTS + '">											<img src="theme/images/remove2.svg" width="16px" border="0"/>										</div>										</a>									</div>								</div>'), $("#side_panel_events_event_list_search").bind("keyup", function() {
        $("#side_panel_events_event_list_grid").setGridParam({
            url: "func/fn_events.php?cmd=load_event_list&s=" + this.value
        }), $("#side_panel_events_event_list_grid").trigger("reloadGrid")
    }), $("#side_panel_events_event_data_list_grid").jqGrid({
        datatype: "local",
        colNames: [la.DATA, la.VALUE],
        colModel: [{
            name: "data",
            index: "data",
            width: 110,
            sortable: !1
        }, {
            name: "value",
            index: "value",
            width: 203,
            sortable: !1
        }],
        width: "340",
        height: "155",
        rowNum: 512,
        shrinkToFit: !1
    }), $("#places_group_list_grid").jqGrid({
        url: "func/fn_places.php?cmd=load_places_group_list",
        datatype: "json",
        colNames: [la.NAME, la.PLACES, la.DESCRIPTION, ""],
        colModel: [{
            name: "group_name",
            index: "group_name",
            width: 228,
            sortable: !0
        }, {
            name: "places",
            index: "places",
            width: 90,
            align: "center",
            sortable: !1
        }, {
            name: "description",
            index: "description",
            width: 295,
            sortable: !1
        }, {
            name: "modify",
            index: "modify",
            width: 45,
            align: "center",
            sortable: !1
        }],
        rowNum: 50,
        rowList: [25, 50, 75, 100, 200],
        pager: "#places_group_list_grid_pager",
        sortname: "group_name",
        sortorder: "asc",
        viewrecords: !0,
        height: "311px",
        width: "720",
        shrinkToFit: !1,
        multiselect: !0,
        beforeSelectRow: function(e, t) {
            return "input" === t.target.tagName.toLowerCase()
        }
    }), $("#places_group_list_grid").jqGrid("navGrid", "#places_group_list_grid_pager", {
        add: !0,
        edit: !1,
        del: !1,
        search: !1,
        addfunc: function() {
            placesGroupProperties("add")
        }
    }), $("#places_group_list_grid").navButtonAdd("#places_group_list_grid_pager", {
        caption: "",
        title: la.ACTION,
        buttonicon: "ui-icon-action",
        onClickButton: function() {},
        position: "last",
        id: "places_group_list_grid_action_menu_button"
    }), $("#places_group_list_grid_action_menu").menu({
        role: "listbox"
    }), $("#places_group_list_grid_action_menu").hide(), $("#places_group_list_grid_action_menu_button").click(function() {
        return $("#places_group_list_grid_action_menu").toggle().position({
            my: "left bottom",
            at: "right-5 top-5",
            of: this
        }), $(document).one("click", function() {
            $("#places_group_list_grid_action_menu").hide()
        }), !1
    });
    var a = '<div style="float: left; margin-top: 2px; width: 25px;">';
    a += '<center><input id="marker_group_visible_{0}" type="checkbox" onClick="markerGroupVisibleTrigger({0});"></center>', a += "</div>", a += '<div style="float: right;"><span id="marker_group_name_{0}"></span> ({1})</div>', $("#side_panel_places_marker_list_grid").jqGrid({
        url: "func/fn_places.php?cmd=load_marker_list",
        datatype: "json",
        colNames: ["", "", '<a href="#" onclick="placesMarkerVisibleAllTrigger();"><img title="' + la.SHOW_HIDE_ALL + '" src="theme/images/eye.svg" width="14px"/></a>', "", la.NAME, ""],
        colModel: [{
            name: "marker_id",
            index: "marker_id",
            hidden: !0
        }, {
            name: "group_id",
            index: "group_id"
        }, {
            name: "show",
            index: "show",
            width: 20,
            sortable: !1,
            align: "center"
        }, {
            name: "icon",
            index: "icon",
            width: 20,
            sortable: !1,
            align: "center"
        }, {
            name: "name",
            index: "name",
            width: 218
        }, {
            name: "modify",
            index: "modify",
            width: 45,
            align: "center",
            sortable: !1
        }],
        recordtext: "",
        emptyrecords: "",
        rowNum: 50,
        rowList: [25, 50, 100, 200],
        pager: "#side_panel_places_marker_list_grid_pager",
        sortname: "name",
        sortorder: "asc",
        viewrecords: !0,
        width: "340",
        shrinkToFit: !1,
        grouping: !0,
        groupingView: {
            groupField: ["group_id"],
            groupColumnShow: [!1],
            groupText: [a],
            groupCollapse: settingsUserData.groups_collapsed.markers,
            groupOrder: ["asc"],
            groupDataSorted: [!0]
        },
        onSelectRow: function(e) {
            var t = $(this).jqGrid("getCell", e, "marker_id");
            placesMarkerPanTo(t)
        },
        loadComplete: function() {
            for (var e in placesGroupData.groups) null != document.getElementById("marker_group_name_" + e) && (document.getElementById("marker_group_name_" + e).innerHTML = placesGroupData.groups[e].name);
            placesMarkerSetListCheckbox()
        }
    }), $("#side_panel_places_marker_list_grid").setCaption('<div class="row4">									<div class="width44">										<input id="side_panel_places_marker_list_search" class="inputbox-search" type="text" value="" placeholder="' + la.SEARCH + '" maxlength="25">									</div>									<div class="float-right">										<a href="#" onclick="placesMarkerReload();">										<div class="panel-button"  title="' + la.RELOAD + '">											<img src="theme/images/refresh-color.svg" width="16px" border="0"/>										</div>										</a>										<a href="#" onclick="placesMarkerNew();">										<div class="panel-button" title="' + la.ADD_MARKER + '">											<img src="theme/images/marker-add.svg" width="16px" border="0"/>										</div>										</a>										<a href="#" onclick="placesGroupOpen();">										<div class="panel-button" title="' + la.GROUPS + '">											<img src="theme/images/groups.svg" width="16px" border="0"/>										</div>										</a>										<a href="#" onclick="placesImport();">										<div class="panel-button" title="' + la.IMPORT + '">											<img src="theme/images/import.svg" width="16px" border="0"/>										</div>										</a>										<a href="#" onclick="placesExport();">										<div class="panel-button" title="' + la.EXPORT + '">											<img src="theme/images/export.svg" width="16px" border="0"/>										</div>										</a>										<a href="#" onclick="placesMarkerDeleteAll();">										<div class="panel-button" title="' + la.DELETE_ALL_MARKERS + '">											<img src="theme/images/remove2.svg" width="16px" border="0"/>										</div>										</a>									</div>								</div>'), $("#side_panel_places_marker_list_search").bind("keyup", function() {
        $("#side_panel_places_marker_list_grid").setGridParam({
            url: "func/fn_places.php?cmd=load_marker_list&s=" + this.value
        }), $("#side_panel_places_marker_list_grid").trigger("reloadGrid"), placesMarkerSearchMap(this.value)
    }), $(window).bind("resize", function() {
        $("#side_panel_places_marker_list_grid").setGridHeight($(window).height() - 210)
    }).trigger("resize");
    var a = '<div style="float: left; margin-top: 2px; width: 25px;">';
    a += '<center><input id="route_group_visible_{0}" type="checkbox" onClick="routeGroupVisibleTrigger({0});"></center>', a += "</div>", a += '<div style="float: right;"><span id="route_group_name_{0}"></span> ({1})</div>', $("#side_panel_places_route_list_grid").jqGrid({
        url: "func/fn_places.php?cmd=load_route_list",
        datatype: "json",
        colNames: ["", "", '<a href="#" onclick="placesRouteVisibleAllTrigger();"><img title="' + la.SHOW_HIDE_ALL + '" src="theme/images/eye.svg" width="14px"/></a>', "", la.NAME, ""],
        colModel: [{
            name: "route_id",
            index: "route_id",
            hidden: !0
        }, {
            name: "group_id",
            index: "group_id"
        }, {
            name: "show",
            index: "show",
            width: 20,
            sortable: !1,
            align: "center"
        }, {
            name: "icon",
            index: "icon",
            width: 20,
            sortable: !1,
            align: "center"
        }, {
            name: "name",
            index: "name",
            width: 218
        }, {
            name: "modify",
            index: "modify",
            width: 45,
            align: "center",
            sortable: !1
        }],
        recordtext: "",
        emptyrecords: "",
        rowNum: 50,
        rowList: [25, 50, 100, 200],
        pager: "#side_panel_places_route_list_grid_pager",
        sortname: "name",
        sortorder: "asc",
        viewrecords: !0,
        width: "340",
        shrinkToFit: !1,
        grouping: !0,
        groupingView: {
            groupField: ["group_id"],
            groupColumnShow: [!1],
            groupText: [a],
            groupCollapse: settingsUserData.groups_collapsed.routes,
            groupOrder: ["asc"],
            groupDataSorted: [!0]
        },
        onSelectRow: function(e) {
            var t = $(this).jqGrid("getCell", e, "route_id");
            placesRoutePanTo(t)
        },
        loadComplete: function() {
            for (var e in placesGroupData.groups) null != document.getElementById("route_group_name_" + e) && (document.getElementById("route_group_name_" + e).innerHTML = placesGroupData.groups[e].name);
            placesRouteSetListCheckbox()
        }
    }), $("#side_panel_places_route_list_grid").setCaption('<div class="row4">									<div class="width44">										<input id="side_panel_places_route_list_search" class="inputbox-search" type="text" value="" placeholder="' + la.SEARCH + '" maxlength="25">									</div>									<div class="float-right">										<a href="#" onclick="placesRouteReload();">										<div class="panel-button"  title="' + la.RELOAD + '">											<img src="theme/images/refresh-color.svg" width="16px" border="0"/>										</div>										</a>										<a href="#" onclick="placesRouteNew();">										<div class="panel-button" title="' + la.ADD_ROUTE + '">											<img src="theme/images/route-add.svg" width="16px" border="0"/>										</div>										</a>										<a href="#" onclick="placesGroupOpen();">										<div class="panel-button" title="' + la.GROUPS + '">											<img src="theme/images/groups.svg" width="16px" border="0"/>										</div>										</a>										<a href="#" onclick="placesImport();">										<div class="panel-button" title="' + la.IMPORT + '">											<img src="theme/images/import.svg" width="16px" border="0"/>										</div>										</a>										<a href="#" onclick="placesExport();">										<div class="panel-button" title="' + la.EXPORT + '">											<img src="theme/images/export.svg" width="16px" border="0"/>										</div>										</a>										<a href="#" onclick="placesRouteDeleteAll();">										<div class="panel-button"  title="' + la.DELETE_ALL_ROUTES + '">											<img src="theme/images/remove2.svg" width="16px" border="0"/>										</div>										</a>									</div>								</div>'), $("#side_panel_places_route_list_search").bind("keyup", function() {
        $("#side_panel_places_route_list_grid").setGridParam({
            url: "func/fn_places.php?cmd=load_route_list&s=" + this.value
        }), $("#side_panel_places_route_list_grid").trigger("reloadGrid"), placesRouteSearchMap(this.value)
    }), $(window).bind("resize", function() {
        $("#side_panel_places_route_list_grid").setGridHeight($(window).height() - 210)
    }).trigger("resize");
    var a = '<div style="float: left; margin-top: 2px; width: 25px;">';
    a += '<center><input id="zone_group_visible_{0}" type="checkbox" onClick="zoneGroupVisibleTrigger({0});"></center>', a += "</div>", a += '<div style="float: right;"><span id="zone_group_name_{0}"></span> ({1})</div>', $("#side_panel_places_zone_list_grid").jqGrid({
        url: "func/fn_places.php?cmd=load_zone_list",
        datatype: "json",
        colNames: ["", "", '<a href="#" onclick="placesZoneVisibleAllTrigger();"><img title="' + la.SHOW_HIDE_ALL + '" src="theme/images/eye.svg" width="14px"/></a>', "", la.NAME, ""],
        colModel: [{
            name: "zone_id",
            index: "zone_id",
            hidden: !0
        }, {
            name: "group_id",
            index: "group_id"
        }, {
            name: "show",
            index: "show",
            width: 20,
            sortable: !1,
            align: "center"
        }, {
            name: "icon",
            index: "icon",
            width: 20,
            sortable: !1,
            align: "center"
        }, {
            name: "name",
            index: "name",
            width: 218
        }, {
            name: "modify",
            index: "modify",
            width: 45,
            align: "center",
            sortable: !1
        }],
        recordtext: "",
        emptyrecords: "",
        rowNum: 50,
        rowList: [25, 50, 100, 200],
        pager: "#side_panel_places_zone_list_grid_pager",
        sortname: "name",
        sortorder: "asc",
        viewrecords: !0,
        width: "340",
        shrinkToFit: !1,
        grouping: !0,
        groupingView: {
            groupField: ["group_id"],
            groupColumnShow: [!1],
            groupText: [a],
            groupCollapse: settingsUserData.groups_collapsed.zones,
            groupOrder: ["asc"],
            groupDataSorted: [!0]
        },
        onSelectRow: function(e) {
            var t = $(this).jqGrid("getCell", e, "zone_id");
            placesZonePanTo(t)
        },
        loadComplete: function() {
            for (var e in placesGroupData.groups) null != document.getElementById("zone_group_name_" + e) && (document.getElementById("zone_group_name_" + e).innerHTML = placesGroupData.groups[e].name);
            placesZoneSetListCheckbox()
        }
    }), $("#side_panel_places_zone_list_grid").setCaption('<div class="row4">									<div class="width44">										<input id="side_panel_places_zone_list_search" class="inputbox-search" type="text" value="" placeholder="' + la.SEARCH + '" maxlength="25">									</div>									<div class="float-right">										<a href="#" onclick="placesZoneReload();">										<div class="panel-button"  title="' + la.RELOAD + '">											<img src="theme/images/refresh-color.svg" width="16px" border="0"/>										</div>										</a>										<a href="#" onclick="placesZoneNew();">										<div class="panel-button" title="' + la.ADD_ZONE + '">											<img src="theme/images/zone-add.svg" width="16px" border="0"/>										</div>										</a>										<a href="#" onclick="placesGroupOpen();">										<div class="panel-button" title="' + la.GROUPS + '">											<img src="theme/images/groups.svg" width="16px" border="0"/>										</div>										</a>										<a href="#" onclick="placesImport();">										<div class="panel-button" title="' + la.IMPORT + '">											<img src="theme/images/import.svg" width="16px" border="0"/>										</div>										</a>										<a href="#" onclick="placesExport();">										<div class="panel-button" title="' + la.EXPORT + '">											<img src="theme/images/export.svg" width="16px" border="0"/>										</div>										</a>										<a href="#" onclick="placesZoneDeleteAll();">										<div class="panel-button"  title="' + la.DELETE_ALL_ZONES + '">											<img src="theme/images/remove2.svg" width="16px" border="0"/>										</div>										</a>									</div>								</div>'), $("#side_panel_places_zone_list_search").bind("keyup", function() {
        $("#side_panel_places_zone_list_grid").setGridParam({
            url: "func/fn_places.php?cmd=load_zone_list&s=" + this.value
        }), $("#side_panel_places_zone_list_grid").trigger("reloadGrid"), placesZoneSearchMap(this.value)
    }), $(window).bind("resize", function() {
        $("#side_panel_places_zone_list_grid").setGridHeight($(window).height() - 210)
    }).trigger("resize"), $("#side_panel_history_route_detail_list_grid").jqGrid({
        datatype: "local",
        colNames: ["", "", "", la.TIME, la.INFORMATION],
        colModel: [{
            name: "el_type",
            index: "el_type",
            hidden: !0
        }, {
            name: "el_id",
            index: "el_id",
            hidden: !0
        }, {
            name: "icon",
            index: "icon",
            width: 20,
            sortable: !1,
            align: "center"
        }, {
            name: "datetime",
            index: "datetime",
            width: 110,
            sortable: !1,
            datefmt: "Y-m-d H:i:s",
            align: "center"
        }, {
            name: "info",
            index: "info",
            width: 178,
            
            sortable: !1
        }],
        width: "340",
        height: "100%",

        rowNum: 4096,
        shrinkToFit: !1,
        loadComplete: function() {
            for (var e = $(this).getDataIDs(), t = historyRouteData.imei, a = 0; a < e.length; a++) {
                var o = e[a],
                    i = $(this).jqGrid("getCell", o, "el_type"),
                    s = $(this).jqGrid("getCell", o, "el_id");
                if ("point" == i) {
                    var n = "<table>";
                    n += "<tr><td>" + la.ROUTE_LENGTH + ":</td><td>" + historyRouteData.route_length + " " + la.UNIT_DISTANCE + "</td></tr>", n += "<tr><td>" + la.MOVE_DURATION + ":</td><td>" + historyRouteData.drives_duration + "</td></tr>", n += "<tr><td>" + la.STOP_DURATION + ":</td><td>" + historyRouteData.stops_duration + "</td></tr>", n += "<tr><td>" + la.TOP_SPEED + ":</td><td>" + historyRouteData.top_speed + " " + la.UNIT_SPEED + "</td></tr>", n += "<tr><td>" + la.AVG_SPEED + ":</td><td>" + historyRouteData.avg_speed + " " + la.UNIT_SPEED + "</td></tr>";
                    var l = historyRouteData.fuel_consumption;
                    0 != l && (n += "<tr><td>" + la.FUEL_CONSUMPTION + ":</td><td>" + l + " " + la.UNIT_CAPACITY + "</td></tr>");
                    var d = historyRouteData.fuel_cost;
                    0 != d && (n += "<tr><td>" + la.FUEL_COST + ":</td><td>" + d + " " + settingsUserData.currency + "</td></tr>"), 0 != getSensorFromType(t, "acc") && (n += "<tr><td>" + la.ENGINE_WORK + ":</td><td>" + historyRouteData.engine_work + "</td></tr>", n += "<tr><td>" + la.ENGINE_IDLE + ":</td><td>" + historyRouteData.engine_idle + "</td></tr>"), n += "</table>"
                } else if ("drive" == i) {
                    var n = "<table>";
                    n += "<tr><td>" + la.ROUTE_LENGTH + ":</td><td>" + historyRouteData.drives[s].route_length + " " + la.UNIT_DISTANCE + "</td></tr>", n += "<tr><td>" + la.TOP_SPEED + ":</td><td>" + historyRouteData.drives[s].top_speed + " " + la.UNIT_SPEED + "</td></tr>", n += "<tr><td>" + la.AVG_SPEED + ":</td><td>" + historyRouteData.drives[s].avg_speed + " " + la.UNIT_SPEED + "</td></tr>";
                    var l = historyRouteData.drives[s].fuel_consumption;
                    0 != l && (n += "<tr><td>" + la.FUEL_CONSUMPTION + ":</td><td>" + l + " " + la.UNIT_CAPACITY + "</td></tr>");
                    var d = historyRouteData.drives[s].fuel_cost;
                    0 != d && (n += "<tr><td>" + la.FUEL_COST + ":</td><td>" + d + " " + settingsUserData.currency + "</td></tr>"), n += "</table>"
                } else if ("stop" == i) {
                    var n = "<table>";
                    n += "<tr><td>" + la.CAME + ":</td><td>" + historyRouteData.stops[s].dt_start + "</td></tr>", n += "<tr><td>" + la.LEFT + ":</td><td>" + historyRouteData.stops[s].dt_end + "</td></tr>", 0 != getSensorFromType(t, "acc") && (n += "<tr><td>" + la.ENGINE_IDLE + ":</td><td>" + historyRouteData.stops[s].engine_idle + "</td></tr>"), n += "</table>"
                }
                "point" != i && "drive" != i && "stop" != i || $("#side_panel_history_route_detail_list_grid #" + o).qtip({
                    content: n,
                    position: {
                        my: "left bottom",
                        adjust: {
                            x: 0,
                            y: -9
                        }
                    }
                })
            }
        },
        onSelectRow: function(e) {
            var t = $(this).jqGrid("getCell", e, "el_type"),
                a = $(this).jqGrid("getCell", e, "el_id");
            "point" == t ? (0 == historyRouteData.play.status && historyRoutePanToPoint(a), historyRouteShowPoint(a)) : "stop" == t ? (0 == historyRouteData.play.status && historyRoutePanToStop(a), historyRouteShowStop(a)) : "event" == t ? (0 == historyRouteData.play.status && historyRoutePanToEvent(a), historyRouteShowEvent(a)) : "drive" == t && (0 == historyRouteData.play.status && historyRouteRemovePointMarker(), destroyMapPopup(), historyRouteShowDrive(a))
        }
    }), $("#side_panel_history_route_data_list_grid").jqGrid({
        datatype: "local",
        colNames: [la.DATA, la.VALUE],
        colModel: [{
            name: "data",
            index: "data",
            width: 110,
            sortable: !1
        }, {
            name: "value",
            index: "value",
            width: 203,
            sortable: !1
        }],
        width: "340",
        height: "155",
        rowNum: 512,
        shrinkToFit: !1
    }), $("#bottom_panel_msg_list_grid").jqGrid({
        url: "func/fn_history.php?cmd=load_msg_list_empty",
        datatype: "json",
        colNames: [la.TIME_POSITION, la.TIME_SERVER, la.LATITUDE, la.LONGITUDE, la.ALTITUDE, la.ANGLE, la.SPEED, la.PARAMETERS],
        colModel: [{
            name: "dt_tracker",
            index: "dt_tracker",
            width: 77,
            fixed: !0,
            align: "center",
            sortable: !0
        }, {
            name: "dt_server",
            index: "dt_server",
            width: 77,
            fixed: !0,
            align: "center",
            sortable: !0
        }, {
            name: "lat",
            index: "lat",
            width: 70,
            fixed: !0,
            align: "center",
            sortable: !0
        }, {
            name: "lng",
            index: "lng",
            width: 68,
            fixed: !0,
            align: "center",
            sortable: !0
        }, {
            name: "altitude",
            index: "altitude",
            width: 56,
            fixed: !0,
            align: "center",
            sortable: !0
        }, {
            name: "angle",
            index: "angle",
            width: 60,
            fixed: !0,
            align: "center",
            sortable: !0
        }, {
            name: "speed",
            index: "speed",
            width: 60,
            fixed: !0,
            align: "center",
            sortable: !0
        }, {
            name: "params",
            index: "params",
            align: "left",
            sortable: !0
        }],
        sortname: "dt_tracker",
        sortorder: "desc",
        rowNum: 50,
        rowList: [25, 50, 100, 200, 300, 400, 500],
        pager: "#bottom_panel_msg_list_grid_pager",
        width: "880",
        height: "111",
        beforeSelectRow: function(e, t) {
            if ("input" === t.target.tagName.toLowerCase()) return !0;
            var a = $(this).jqGrid("getCell", e, "lat"),
                o = $(this).jqGrid("getCell", e, "lng");
            return 0 != a && 0 != o && utilsPointOnMap(a, o), !1
        },
        shrinkToFit: !0,
        multiselect: !0
    }), $("#bottom_panel_msg_list_grid").jqGrid("navGrid", "#bottom_panel_msg_list_grid_pager", {
        add: !1,
        edit: !1,
        del: !1,
        search: !1
    }), $("#bottom_panel_msg_list_grid").navButtonAdd("#bottom_panel_msg_list_grid_pager", {
        caption: "",
        title: la.ACTION,
        buttonicon: "ui-icon-action",
        onClickButton: function() {},
        position: "last",
        id: "bottom_panel_msg_list_grid_action_menu_button"
    }), $("#bottom_panel_msg_list_grid_action_menu").menu({
        role: "listbox"
    }), $("#bottom_panel_msg_list_grid_action_menu").hide(), $("#bottom_panel_msg_list_grid_action_menu_button").click(function() {
        return $("#bottom_panel_msg_list_grid_action_menu").toggle().position({
            my: "left bottom",
            at: "right-5 top-5",
            of: this
        }), $(document).one("click", function() {
            $("#bottom_panel_msg_list_grid_action_menu").hide()
        }), !1
    }), $(window).bind("resize", function() {
        "none" == document.getElementById("side_panel").style.display ? $("#bottom_panel_msg_list_grid").setGridWidth($(window).width() - 35) : $("#bottom_panel_msg_list_grid").setGridWidth($(window).width() - 200)
    }).trigger("resize"), $("#cmd_schedule_list_grid").jqGrid({
        url: "func/fn_cmd.php?cmd=load_cmd_schedule_list",
        datatype: "json",
        colNames: [la.NAME, la.ACTIVE, la.SCHEDULE, la.GATEWAY, la.TYPE, la.COMMAND, ""],
        colModel: [{
            name: "name",
            index: "name",
            width: 190,
            sortable: !0
        }, {
            name: "active",
            index: "active",
            width: 60,
            align: "center",
            sortable: !0
        }, {
            name: "exact_time",
            index: "exact_time",
            width: 110,
            align: "center",
            sortable: !0
        }, {
            name: "gateway",
            index: "gateway",
            width: 80,
            align: "center",
            sortable: !0
        }, {
            name: "type",
            index: "type",
            width: 60,
            align: "center",
            sortable: !0
        }, {
            name: "cmd",
            index: "cmd",
            width: 308,
            sortable: !0
        }, {
            name: "modify",
            index: "modify",
            width: 45,
            align: "center",
            sortable: !1
        }],
        sortname: "name",
        sortorder: "asc",
        rowNum: 50,
        rowList: [25, 50, 100, 200],
        pager: "#cmd_schedule_list_grid_pager",
        sortname: "name",
        sortorder: "asc",
        viewrecords: !0,
        height: "470px",
        width: "950",
        shrinkToFit: !1,
        multiselect: !0,
        beforeSelectRow: function(e, t) {
            return "input" === t.target.tagName.toLowerCase()
        }
    }), $("#cmd_schedule_list_grid").jqGrid("navGrid", "#cmd_schedule_list_grid_pager", {
        add: !0,
        edit: !1,
        del: !1,
        search: !1,
        addfunc: function() {
            cmdScheduleProperties("add")
        }
    }), $("#cmd_schedule_list_grid").navButtonAdd("#cmd_schedule_list_grid_pager", {
        caption: "",
        title: la.ACTION,
        buttonicon: "ui-icon-action",
        onClickButton: function() {},
        position: "last",
        id: "cmd_schedule_list_grid_action_menu_button"
    }), $("#cmd_schedule_list_grid_action_menu").menu({
        role: "listbox"
    }), $("#cmd_schedule_list_grid_action_menu").hide(), $("#cmd_schedule_list_grid_action_menu_button").click(function() {
        return $("#cmd_schedule_list_grid_action_menu").toggle().position({
            my: "left bottom",
            at: "right-5 top-5",
            of: this
        }), $(document).one("click", function() {
            $("#cmd_schedule_list_grid_action_menu").hide()
        }), !1
    }), $("#cmd_template_list_grid").jqGrid({
        url: "func/fn_cmd.php?cmd=load_cmd_template_list",
        datatype: "json",
        colNames: [la.NAME, la.PROTOCOL, la.GATEWAY, la.TYPE, la.COMMAND, ""],
        colModel: [{
            name: "name",
            index: "name",
            width: 150,
            sortable: !0
        }, {
            name: "protocol",
            index: "protocol",
            width: 150,
            align: "center",
            sortable: !0
        }, {
            name: "gateway",
            index: "gateway",
            width: 60,
            align: "center",
            sortable: !0
        }, {
            name: "type",
            index: "type",
            width: 60,
            align: "center",
            sortable: !0
        }, {
            name: "cmd",
            index: "cmd",
            width: 385,
            sortable: !0
        }, {
            name: "modify",
            index: "modify",
            width: 85,
            align: "center",
            sortable: !1
        }],
        sortname: "name",
        sortorder: "asc",
        rowNum: 50,
        rowList: [25, 50, 100, 200],
        pager: "#cmd_template_list_grid_pager",
        sortname: "name",
        sortorder: "asc",
        viewrecords: !0,
        height: "450px",
        width: "900",
        shrinkToFit: !1,
        multiselect: !0,
        beforeSelectRow: function(e, t) {
            return "input" === t.target.tagName.toLowerCase()
        }
    }), $("#cmd_template_list_grid").jqGrid("navGrid", "#cmd_template_list_grid_pager", {
        add: !0,
        edit: !1,
        del: !1,
        search: !1,
        addfunc: function() {
            cmdTemplateProperties("add")
        }
    }), $("#cmd_template_list_grid").navButtonAdd("#cmd_template_list_grid_pager", {
        caption: "",
        title: la.ACTION,
        buttonicon: "ui-icon-action",
        onClickButton: function() {},
        position: "last",
        id: "cmd_template_list_grid_action_menu_button"
    }), $("#cmd_template_list_grid_action_menu").menu({
        role: "listbox"
    }), $("#cmd_template_list_grid_action_menu").hide(), $("#cmd_template_list_grid_action_menu_button").click(function() {
        return $("#cmd_template_list_grid_action_menu").toggle().position({
            my: "left bottom",
            at: "right-5 top-5",
            of: this
        }), $(document).one("click", function() {
            $("#cmd_template_list_grid_action_menu").hide()
        }), !1
    }), $("#cmd_status_list_grid").jqGrid({
        url: "func/fn_cmd.php?cmd=load_cmd_exec_list",
        datatype: "json",
        colNames: [la.TIME, la.OBJECT, la.NAME, la.GATEWAY, la.TYPE, la.COMMAND, la.STATUS, "", ""],
        colModel: [{
            name: "dt_cmd",
            index: "dt_cmd",
            width: 110,
            sortable: !0
        }, {
            name: "object",
            index: "object",
            width: 90,
            sortable: !1
        }, {
            name: "name",
            index: "name",
            width: 100,
            sortable: !1
        }, {
            name: "gateway",
            index: "gateway",
            width: 130,
            align: "center",
            sortable: !1
        }, {
            name: "type",
            index: "type",
            width: 95,
            align: "center",
            sortable: !1
        }, {
            name: "cmd",
            index: "cmd",
            width: 240,
            sortable: !1
        }, {
            name: "status",
            index: "status",
            width: 50,
            align: "center",
            sortable: !1
        }, {
            name: "modify",
            index: "modify",
            width: 40,
            align: "center",
            sortable: !1
        }, {
            name: "re_hex",
            index: "re_hex",
            hidden: !0
        }],
        rowNum: 2048,
        pager: "#cmd_status_list_grid_pager",
        pgbuttons: !1,
        pgtext: "",
        recordtext: "",
        emptyrecords: "",
        sortname: "dt_cmd",
        sortorder: "desc",
        viewrecords: !0,
        height: "475px",
        width: "950",
        shrinkToFit: !1,
        multiselect: !0,
        beforeSelectRow: function(e, t) {
            return "input" === t.target.tagName.toLowerCase()
        },
        subGrid: !0,
        subGridRowExpanded: function(e, t) {
            var a = $("#cmd_status_list_grid").getRowData(t).re_hex,
                o = hexToAscii(a),
                i = "";
            "" == o && "" == a ? i = la.NO_DATA : (i = '<table style="table-layout: fixed; width: 100%">', i += '<tr><td style="width: 40px;">ASCII:</td><td style="word-wrap: break-word;">' + o + "</td></tr>", i += '<tr><td>HEX:</td><td style="word-wrap:break-word;">' + a + "</td></tr>", i += "</table>"), $("#" + e).html(i)
        }
    }), $("#cmd_status_list_grid").jqGrid("navGrid", "#cmd_status_list_grid_pager", {
        add: !1,
        edit: !1,
        del: !1,
        search: !1
    }), $("#cmd_status_list_grid").navButtonAdd("#cmd_status_list_grid_pager", {
        caption: "",
        title: la.ACTION,
        buttonicon: "ui-icon-action",
        onClickButton: function() {},
        position: "last",
        id: "cmd_status_list_grid_action_menu_button"
    }), $("#cmd_status_list_grid_action_menu").menu({
        role: "listbox"
    }), $("#cmd_status_list_grid_action_menu").hide(), $("#cmd_status_list_grid_action_menu_button").click(function() {
        return $("#cmd_status_list_grid_action_menu").toggle().position({
            my: "left bottom",
            at: "right-5 top-5",
            of: this
        }), $(document).one("click", function() {
            $("#cmd_status_list_grid_action_menu").hide()
        }), !1
    }), $("#report_list_grid").jqGrid({
        url: "func/fn_reports.php?cmd=load_report_list",
        datatype: "json",
        colNames: [la.NAME, la.TYPE, la.FORMAT, la.OBJECTS, la.ZONES, la.SENSORS, la.DAILY, la.WEEKLY, ""],
        colModel: [{
            name: "name",
            index: "name",
            width: 240,
            sortable: !0
        }, {
            name: "type",
            index: "type",
            width: 215,
            align: "center",
            sortable: !1
        }, {
            name: "format",
            index: "format",
            width: 60,
            align: "center",
            sortable: !1
        }, {
            name: "objects",
            index: "objects",
            width: 60,
            align: "center",
            sortable: !1
        }, {
            name: "zones",
            index: "zones",
            width: 60,
            align: "center",
            sortable: !1
        }, {
            name: "sensors",
            index: "sensors",
            width: 60,
            align: "center",
            sortable: !1
        }, {
            name: "daily",
            index: "daily",
            width: 60,
            align: "center",
            sortable: !1
        }, {
            name: "weekly",
            index: "weekly",
            width: 60,
            align: "center",
            sortable: !1
        }, {
            name: "modify",
            index: "modify",
            width: 60,
            align: "center",
            sortable: !1
        }],
        rowNum: 2048,
        pager: "#report_list_grid_pager",
        pgbuttons: !1,
        pgtext: "",
        recordtext: "",
        emptyrecords: "",
        sortname: "name",
        sortorder: "asc",
        viewrecords: !0,
        height: "450px",
        width: "962",
        shrinkToFit: !1,
        multiselect: !0,
        beforeSelectRow: function(e, t) {
    		loadObjectRpt()
            return "input" === t.target.tagName.toLowerCase()
        },
        loadComplete: function() {
            for (var e = $(this).getDataIDs(), t = 0; t < e.length; t++) {
                var a = e[t];
                $("#report_action_menu_" + a).click(function() {
                    return $("#report_action_menu").toggle().position({
                        my: "left top",
                        at: "right bottom",
                        of: this
                    }), menuOnItem = $(this).attr("tag"), $(document).one("click", function() {
                        $("#report_action_menu").hide()
                    }), !1
                })
            }
        }
    }), $("#report_list_grid").jqGrid("navGrid", "#report_list_grid_pager", {
        
        edit: !1,
        del: !1,
        search: !1,
        
    }), $("#report_list_grid").navButtonAdd("#report_list_grid_pager", {
        caption: "",
        title: la.ACTION,
        buttonicon: "ui-icon-action",
        onClickButton: function() {},
        position: "last",
        id: "report_list_grid_action_menu_button"
    }), $("#report_list_grid_action_menu").menu({
        role: "listbox"
    }), $("#report_list_grid_action_menu").hide(), $("#report_list_grid_action_menu_button").click(function() {
        return $("#report_list_grid_action_menu").toggle().position({
            my: "left bottom",
            at: "right-5 top-5",
            of: this
        }), $(document).one("click", function() {
            $("#report_list_grid_action_menu").hide()
        }), !1
    }), $("#reports_generated_list_grid").jqGrid({
        url: "func/fn_reports.php?cmd=load_reports_generated_list",
        datatype: "json",
        colNames: [la.TIME, la.NAME, la.TYPE, la.FORMAT, la.OBJECTS, la.ZONES, la.SENSORS, la.SCHEDULE, ""],
        colModel: [{
            name: "dt_report",
            index: "dt_report",
            width: 110,
            fixed: !0,
            align: "center"
        }, {
            name: "name",
            index: "name",
            width: 215,
            sortable: !0
        }, {
            name: "type",
            index: "type",
            width: 190,
            align: "center",
            sortable: !1
        }, {
            name: "format",
            index: "format",
            width: 60,
            align: "center",
            sortable: !1
        }, {
            name: "objects",
            index: "objects",
            width: 60,
            align: "center",
            sortable: !1
        }, {
            name: "zones",
            index: "zones",
            width: 60,
            align: "center",
            sortable: !1
        }, {
            name: "sensors",
            index: "sensors",
            width: 60,
            align: "center",
            sortable: !1
        }, {
            name: "schedule",
            index: "schedule",
            width: 60,
            align: "center",
            sortable: !1
        }, {
            name: "modify",
            index: "modify",
            width: 60,
            align: "center",
            sortable: !1
        }],
        sortname: "dt_report",
        sortorder: "desc",
        rowNum: 50,
        rowList: [50, 100, 200, 300, 400, 500],
        pager: "#reports_generated_list_grid_pager",
        viewrecords: !0,
        height: "450px",
        width: "962",
        shrinkToFit: !1,
        multiselect: !0,
        beforeSelectRow: function(e, t) {
            return "input" === t.target.tagName.toLowerCase()
        }
    }), $("#reports_generated_list_grid").jqGrid("navGrid", "#reports_generated_list_grid_pager", {
        add: !1,
        edit: !1,
        del: !1,
        search: !1
    }), $("#reports_generated_list_grid").navButtonAdd("#reports_generated_list_grid_pager", {
        caption: "",
        title: la.ACTION,
        buttonicon: "ui-icon-action",
        onClickButton: function() {},
        position: "last",
        id: "reports_generated_list_grid_action_menu_button"
    }), $("#reports_generated_list_grid_action_menu").menu({
        role: "listbox"
    }), $("#reports_generated_list_grid_action_menu").hide(), $("#reports_generated_list_grid_action_menu_button").click(function() {
        return $("#reports_generated_list_grid_action_menu").toggle().position({
            my: "left bottom",
            at: "right-5 top-5",
            of: this
        }), $(document).one("click", function() {
            $("#reports_generated_list_grid_action_menu").hide()
        }), !1
    }), $("#rilogbook_logbook_grid").jqGrid({
        url: "",
        datatype: "json",
        colNames: [la.TIME, la.OBJECT, la.GROUP, la.NAME, la.POSITION, ""],
        colModel: [{
            name: "dt_tracker",
            index: "dt_tracker",
            width: 60,
            sortable: !0
        }, {
            name: "imei",
            index: "imei",
            width: 80,
            align: "left",
            sortable: !1
        }, {
            name: "group",
            index: "group",
            width: 60,
            align: "center",
            sortable: !1
        }, {
            name: "name",
            index: "name",
            width: 80,
            align: "left",
            sortable: !1
        }, {
            name: "position",
            index: "position",
            width: 175,
            align: "left",
            sortable: !1
        }, {
            name: "modify",
            index: "modify",
            width: 30,
            align: "center",
            sortable: !1
        }],
        sortname: "dt_tracker",
        sortorder: "desc",
        rowNum: 50,
        rowList: [50, 100, 200, 300, 400, 500],
        pager: "#rilogbook_logbook_grid_pager",
        viewrecords: !0,
        height: "450",
        width: "750",
        shrinkToFit: !0,
        multiselect: !0,
        beforeSelectRow: function(e, t) {
            return "input" === t.target.tagName.toLowerCase()
        }
    }), $("#rilogbook_logbook_grid").jqGrid("navGrid", "#rilogbook_logbook_grid_pager", {
        add: !1,
        edit: !1,
        del: !1,
        search: !1
    }), $("#rilogbook_logbook_grid").navButtonAdd("#rilogbook_logbook_grid_pager", {
        caption: "",
        title: la.ACTION,
        buttonicon: "ui-icon-action",
        onClickButton: function() {},
        position: "last",
        id: "rilogbook_logbook_grid_action_menu_button"
    }), $("#rilogbook_logbook_grid_action_menu").menu({
        role: "listbox"
    }), $("#rilogbook_logbook_grid_action_menu").hide(), $("#rilogbook_logbook_grid_action_menu_button").click(function() {
        return $("#rilogbook_logbook_grid_action_menu").toggle().position({
            my: "left bottom",
            at: "right-5 top-5",
            of: this
        }), $(document).one("click", function() {
            $("#rilogbook_logbook_grid_action_menu").hide()
        }), !1
    }), $("#dtc_list_grid").jqGrid({
        url: "",
        datatype: "json",
        colNames: [la.TIME, la.OBJECT, la.CODE, la.POSITION, ""],
        colModel: [{
            name: "dt_tracker",
            index: "dt_tracker",
            width: 60,
            sortable: !0
        }, {
            name: "imei",
            index: "imei",
            width: 80,
            align: "left",
            sortable: !1
        }, {
            name: "code",
            index: "code",
            width: 80,
            align: "left",
            sortable: !1
        }, {
            name: "position",
            index: "position",
            width: 215,
            align: "left",
            sortable: !1
        }, {
            name: "modify",
            index: "modify",
            width: 30,
            align: "center",
            sortable: !1
        }],
        sortname: "dt_tracker",
        sortorder: "desc",
        rowNum: 50,
        rowList: [50, 100, 200, 300, 400, 500],
        pager: "#dtc_list_grid_pager",
        viewrecords: !0,
        height: "150px",
        width: "750",
        shrinkToFit: !0,
        multiselect: !0,
        beforeSelectRow: function(e, t) {
            return "input" === t.target.tagName.toLowerCase()
        }
    }), $("#dtc_list_grid").jqGrid("navGrid", "#dtc_list_grid_pager", {
        add: !1,
        edit: !1,
        del: !1,
        search: !1
    }), $("#dtc_list_grid").navButtonAdd("#dtc_list_grid_pager", {
        caption: "",
        title: la.ACTION,
        buttonicon: "ui-icon-action",
        onClickButton: function() {},
        position: "last",
        id: "dtc_list_grid_action_menu_button"
    }), $("#dtc_list_grid_action_menu").menu({
        role: "listbox"
    }), $("#dtc_list_grid_action_menu").hide(), $("#dtc_list_grid_action_menu_button").click(function() {
        return $("#dtc_list_grid_action_menu").toggle().position({
            my: "left bottom",
            at: "right-5 top-5",
            of: this
        }), $(document).one("click", function() {
            $("#dtc_list_grid_action_menu").hide()
        }), !1
    }), $("#image_gallery_list_grid").jqGrid({
        url: "func/fn_img.php?cmd=load_img_list",
        datatype: "json",
        colNames: [la.TIME, la.OBJECT, "", "", "", "", ""],
        colModel: [{
            name: "dt_server",
            index: "dt_server",
            width: 110,
            sortable: !0
        }, {
            name: "object",
            index: "object",
            width: 111,
            sortable: !1
        }, {
            name: "modify",
            index: "modify",
            width: 30,
            align: "center",
            sortable: !1
        }, {
            name: "img_file",
            index: "img_file",
            hidden: !0
        }, {
            name: "lat",
            index: "lat",
            hidden: !0
        }, {
            name: "lng",
            index: "lng",
            hidden: !0
        }, {
            name: "speed",
            index: "speed",
            hidden: !0
        }],
        rowNum: 25,
        recordtext: "",
        emptyrecords: "",
        rowList: [25, 50, 75, 100],
        pager: "#image_gallery_list_grid_pager",
        sortname: "dt_server",
        sortorder: "desc",
        viewrecords: !0,
        height: "302px",
        width: "308",
        shrinkToFit: !1,
        multiselect: !0,
        beforeSelectRow: function(e, t) {
            if ("input" === t.target.tagName.toLowerCase()) return !0;
            var a = "data/img/" + $(this).jqGrid("getCell", e, "img_file"),
                o = $(this).jqGrid("getCell", e, "lat"),
                i = $(this).jqGrid("getCell", e, "lng");
            $(this).jqGrid("getCell", e, "speed");
            fileExist(a) ? document.getElementById("image_gallery_img").innerHTML = '<img style="image-orientation: from-image; height: 380px;" src="' + a + '">' : document.getElementById("image_gallery_img").innerHTML = '<img src="img/no-image.svg">';
            var s = '<span id="image_gallery_img_data_address"></span>',
                n = '<table border="0" cellspacing="0" height="100%"><tr><td style="white-space:nowrap;">' + urlPosition(o, i) + s + "</td></tr></table>";
            return document.getElementById("image_gallery_img_data").innerHTML = n, geocoderGetAddress(o, i, function(e) {
                "" != e && (document.getElementById("image_gallery_img_data_address").innerHTML = " - " + e)
            }), !1
        }
    }), $("#image_gallery_list_grid").jqGrid("navGrid", "#image_gallery_list_grid_pager", {
        add: !1,
        edit: !1,
        del: !1,
        search: !1
    }), $("#image_gallery_list_grid").navButtonAdd("#image_gallery_list_grid_pager", {
        caption: "",
        title: la.ACTION,
        buttonicon: "ui-icon-action",
        onClickButton: function() {},
        position: "last",
        id: "image_gallery_list_grid_action_menu_button"
    }), $("#image_gallery_list_grid_action_menu").menu({
        role: "listbox"
    }), $("#image_gallery_list_grid_action_menu").hide(), $("#image_gallery_list_grid_action_menu_button").click(function() {
        return $("#image_gallery_list_grid_action_menu").toggle().position({
            my: "left bottom",
            at: "right-5 top-5",
            of: this
        }), $(document).one("click", function() {
            $("#image_gallery_list_grid_action_menu").hide()
        }), !1
    }), $("#chat_object_list_grid").jqGrid({
        datatype: "local",
        colNames: ["", "", la.OBJECT, '<img src="theme/images/chat.svg" width="14px"/>'],
        colModel: [{
            name: "search",
            index: "search",
            hidden: !0
        }, {
            name: "icon",
            index: "icon",
            width: 28,
            sortable: !1
        }, {
            name: "name",
            index: "search",
            width: 135
        }, {
            name: "msg_count",
            index: "msg_count",
            width: 30,
            sortable: !1,
            align: "center"
        }],
        rowNum: 2048,
        sortname: "name",
        sortorder: "asc",
        viewrecords: !0,
        width: "225",
        shrinkToFit: !1,
        loadComplete: function() {
            chatUpdateMsgCount(), chatUpdateMsgDt()
        },
        onSelectRow: function(e) {
            chatSelectObject(e)
        }
    }), $("#chat_object_list_search").bind("keyup", function() {
        var e = $("#chat_object_list_grid"),
            t = e.jqGrid("getGridParam", "postData");
        jQuery.extend(t, {
            filters: "",
            searchField: "search",
            searchOper: "cn",
            searchString: this.value.toLowerCase()
        }), e.jqGrid("setGridParam", {
            search: !0,
            postData: t
        }), e.trigger("reloadGrid")
    }), $("#billing_plan_list_grid").jqGrid({
        url: "func/fn_billing.php?cmd=load_billing_plan_list",
        datatype: "json",
        colNames: [la.TIME, la.NAME, la.OBJECTS, la.PERIOD, la.PRICE, ""],
        colModel: [{
            name: "dt_purchase",
            index: "dt_purchase",
            width: 110,
            fixed: !0,
            align: "center"
        }, {
            name: "name",
            index: "name",
            width: 238
        }, {
            name: "objects",
            index: "objects",
            width: 80,
            fixed: !0,
            align: "center"
        }, {
            name: "period",
            index: "period",
            width: 100,
            fixed: !0,
            align: "center"
        }, {
            name: "price",
            index: "price",
            width: 100,
            fixed: !0,
            align: "center"
        }, {
            name: "modify",
            index: "modify",
            width: 45,
            align: "center",
            sortable: !1
        }],
        rowNum: 50,
        rowList: [25, 50, 75, 100, 200],
        pager: "#billing_plan_list_grid_pager",
        sortname: "dt_purchase",
        sortorder: "desc",
        viewrecords: !0,
        height: "388px",
        width: "720",
        shrinkToFit: !1
    }), $("#billing_plan_list_grid").jqGrid("navGrid", "#billing_plan_list_grid_pager", {
        add: !0,
        edit: !1,
        del: !1,
        search: !1,
        addfunc: function() {
            billingPlanPurchase()
        },
        addtitle: la.PURCHASE_PLAN
    }), $("#billing_plan_object_list_grid").jqGrid({
        datatype: "local",
        colNames: ["", la.NAME, la.IMEI, la.ACTIVE, la.EXPIRES_ON],
        colModel: [{
            name: "name_sort",
            index: "name_sort",
            hidden: !0
        }, {
            name: "name",
            index: "name_sort",
            width: 244
        }, {
            name: "imei",
            index: "imei",
            width: 160
        }, {
            name: "active",
            index: "active",
            width: 90,
            align: "center"
        }, {
            name: "object_expire_dt",
            index: "object_expire_dt",
            width: 110,
            align: "center"
        }],
        rowNum: 2048,
        pager: "#billing_plan_object_list_grid_pager",
        pgbuttons: !1,
        pgtext: "",
        recordtext: "",
        emptyrecords: "",
        sortname: "name",
        sortorder: "asc",
        viewrecords: !0,
        height: "270",
        width: "665",
        shrinkToFit: !1,
        multiselect: !0,
        onSelectRow: function() {
            billingPlanUseUpdateSelection()
        },
        onSelectAll: function() {
            billingPlanUseUpdateSelection()
        }
    }), $("#billing_plan_object_list_grid").jqGrid("navGrid", "#billing_plan_object_list_grid_pager", {
        add: !1,
        edit: !1,
        del: !1,
        search: !1,
        refresh: !1
    }), $(".ui-jqgrid-titlebar-close").hide(), $(window).bind("resize", function() {
        resizeGrids()
    }).trigger("resize");
    
    // code done by vetrivelht

    $("#settings_main_shift_allocation_list_grid").jqGrid({
        url: "func/fn_settings.shiftallocation.php?cmd=load_shift_allocation",
        datatype: "json",
        colNames: [la.DATE, la.FORMAT,""],
        colModel: [{
            name: "date",
            index: "date",
            width: 350,
            align: "center",
            sortable: !1
        }, {
            name: "crew",
            index: "crew",
            width: 350,
            align: "center",
            sortable: !1
        }, {
            name: "modify",
            index: "modify",
            width: 195,
            align: "center",
            sortable: !1
        }],
        rowNum: 512,
        pager: "#settings_main_shift_allocation_list_pager",
        pgbuttons: !1,
        pgtext: "",
        recordtext: "",
        emptyrecords: "",
        sortname: "date",
        sortorder: "asc",
        viewrecords: !0,
        width: "950",
        height: "437px",
        shrinkToFit: !1,
        multiselect: !0,
        beforeSelectRow: function(e, t) {
            return "input" === t.target.tagName.toLowerCase()
        }
    }), $("#settings_main_shift_allocation_list_grid").jqGrid("navGrid", "#settings_main_shift_allocation_list_pager", {
        add: !0,
        edit: !1,
        del: !1,
        search: !1,
        addfunc: function() {
    	settingsShiftAllocation("add")
        }
    }), $("#settings_main_shift_allocation_list_grid").navButtonAdd("#settings_main_shift_allocation_list_pager", {
        caption: "",
        title: la.ACTION,
        buttonicon: "ui-icon-action",
        onClickButton: function() {},
        position: "last",
        id: "settings_main_shift_allocation_list_grid_action_menu_button"
    }), $("#settings_main_shift_allocation_list_grid_action_menu").menu({
        role: "listbox"
    }), $("#settings_main_shift_allocation_list_grid_action_menu").hide(), $("#settings_main_shift_allocation_list_grid_action_menu_button").click(function() {
        return $("#settings_main_shift_allocation_list_grid_action_menu").toggle().position({
            my: "left bottom",
            at: "right-5 top-5",
            of: this
        }), $(document).one("click", function() {
            $("#settings_main_shift_allocation_list_grid_action_menu").hide()
        }), !1
    }),
     // Menu - SOS Alert List
   $("#settings_main_sosallert_list_grid").jqGrid({        
        url: "func/fn_settings.shiftallocation.php?cmd=load_sos_alert&event=sos",
        datatype: "json",
        colNames: [la.S_NO, la.NAME,la.VENDOR,la.TRANSPORT_MODEL,la.EVENT_DESCRIPTION ,la.STAFFDATE,la.DRIVER_NAME,la.ALLOCATED_DRIVER_PHONE,la.LOCATION,la.EVENT,la.RESPONSE,la.ACTION_TAKEN,la.ACTION_BY],
        colModel: [{
            name: "sno",
            index: "sno",
            width: 20,
            align: "center",
            hidden: true,
            sortable: !1
        },{
            name: "name",
            index: "name",
            width: 118,
            align: "center",
            sortable: !1
        }, {
            name: "groupname",
            index: "groupname",
            width: 80,
            align: "center",
            sortable: !1
        }, {
            name: "transportmodel",
            index: "transportmodel",
            width: 118,
            align: "center",
            sortable: !1
        },{
            name: "eventdesc",
            index: "eventdesc",
            width: 118,
            align: "center",
            sortable: !1
        }, {
            name: "date",
            index: "date",
            width: 118,
            align: "center",
            sortable: !1
        },{
            name: "drivername",
            index: "drivername",
            width: 118,
            align: "center",
            sortable: !1
        },{
            name: "drivernumber",
            index: "drivernumber",
            width: 90,
            align: "center",
            sortable: !1
        },{
            name: "address",
            index: "address",
            width: 200,
            align: "center",
            sortable: !1
        }, {
            name: "eventid",
            index: "eventid",
            width: 118,
            align: "center",
            hidden: true,
            sortable: !1
        },{
            name: "modify",
            index: "modify",
            width: 118,
            align: "center",
            hidden: true,
            sortable: !1
        },{
            name: "modify1",
            index: "modify1",
            width: 118,
            align: "center",
            hidden: true,
            sortable: !1
        },{
            name: "modify2",
            index: "modify2",
            width: 118,
            align: "center",
            hidden: true,
            sortable: !1
        }],
        rowNum:50,
        rowList: [50, 50, 75, 100, 200],
        viewrecords: !0,
        pager: "#settings_main_sosallert_list_pager",
        sortname: "date",
        sortorder: "asc",
        rownumbers: true,
        width: "950",
        height: "437px",
        shrinkToFit: !1,
        multiselect: !0,
        beforeSelectRow: function(e, t) {
            return "input" === t.target.tagName.toLowerCase()
        }
    }), $("#settings_main_sosallert_list_grid").jqGrid("navGrid", "#settings_main_sosallert_list_pager", {
        add: !1,
        edit: !1,
        del: !1,
        search: !1,
        
    }),
    // Menu - SOS Alert Token List
     $("#settings_main_sosallert_token_grid").jqGrid({                
        datatype: "local",
        colNames: [la.S_NO, la.NAME, la.EVENT_DESCRIPTION ,la.STAFFDATE,la.EVENT,la.RESPONSE,la.ACTION_TAKEN,la.ACTION_BY],
        colModel: [{
            name: "sno",
            index: "sno",
            width: 20,
            align: "center",
            hidden: true,
            sortable: !1
        },{
            name: "name",
            index: "name",
            width: 118,
            align: "center",
            sortable: !1
        },{
            name: "eventdesc",
            index: "eventdesc",
            width: 118,
            align: "center",
            sortable: !1
        },{
            name: "date",
            index: "date",
            width: 118,
            align: "center",            
            sortable: !1
        }, {
            name: "eventid",
            index: "eventid",
            width: 118,
            align: "center",
            hidden: true,
            sortable: !1
        },{
            name: "modify",
            index: "modify",
            width: 118,
            align: "center",
            editable: true,
            edittype: "text",
            formatter: responsedata,
            sortable: !1
        },{
            name: "modify1",
            index: "modify1",
            width: 118,
            align: "center",
            editable: true,
            edittype: "text",
            formatter: actiontakenedata,
            sortable: !1
        },{
            name: "modify2",
            index: "modify2",
            width: 118,
            align: "center",
            editable: true,
            edittype: "text",
            formatter: actionbydata,
            sortable: !1
        }],
        rowNum: 512,
        pager: "#settings_main_sosallert_token_pager",
        pgbuttons: false,
        pgtext: "",
        recordtext: "",
        emptyrecords: "",
        sortname: "date",
        sortorder: "asc",
        viewrecords: !0,
        width: "950",
        height: "437px",
        rownumbers: true,
        shrinkToFit: !1,
        // multiselect: !0,
        beforeSelectRow: function(e, t) {
            return "input" === t.target.tagName.toLowerCase()
        }
    }), $("#settings_main_sosallert_token_grid").jqGrid("navGrid", "#settings_main_sosallert_token_pager", {
        add: !1,
        edit: !1,
        del: !1,
        search: !1,
        
    }); 
    // Menu STIS - Add Employee List
    $("#stis_main_addemployee_list_grid").jqGrid({                
        url: "func/fn_addemployee.php?cmd=add_employee_list",
        datatype: "json",
        colNames: [la.EMPLOYEEID,la.USERNAME,la.DEPARTMENT,la.SHIFT, la.PHONE, la.ADDRESS,la.STATUS, la.USER_TYPE, ""],
        colModel: [{
            name: "empid",
            index: "empid",
            align: "center",
            width: 70,
            sortable: !0
        },{
            name: "username",
            index: "username",
            align: "center",
            width: 70,
            sortable: !0
        },{
            name: "department",
            index: "department",
            align: "center",
            width: 80,
            sortable: !0
        },{
            name: "shift",
            index: "shift",
            align: "center",
            width: 80,
            sortable: !0
        }, {
            name: "mobile",
            index: "mobile",
            width: 70,
            align: "center",
            sortable: !1
        }, {
            name: "address",
            index: "address",
            width: 200,
            align: "center",
            sortable: !1
        }, {
            name: "status",
            index: "status",
            align: "center",
            width: 50,
            sortable: !1
        }, {
            name: "user_type",
            index: "user_type",
            align: "center",
            width: 70,
            sortable: !1
        }, {
            name: "modify",
            index: "modify",
            align: "center",
            width: 40,
            sortable: !1
        }],
        rowNum: 512,
        pager: "#stis_main_addemployee_list_pager",
        pgbuttons: false,
        pgtext: "",
        recordtext: "",
        emptyrecords: "",
        sortname: "date",
        sortorder: "asc",
        viewrecords: !0,
        width: "950",
        height: "437px",
        rownumbers: true,
        shrinkToFit: !1,
        // multiselect: !0,
        beforeSelectRow: function(e, t) {
            return "input" === t.target.tagName.toLowerCase()
        }
    }), $("#stis_main_addemployee_list_grid").jqGrid("navGrid", "#stis_main_addemployee_list_pager", {
        add: !0,
        edit: !1,
        del: !1,
        search: !1,
        addfunc: function() {
        addNewEmployee("add")
        }
        
    }),
    // Menu STIS - department List
    $("#stis_main_department_list_grid").jqGrid({                
        url: "func/fn_addemployee.php?cmd=add_stis_department",
        datatype: "json",
        colNames: [la.DEPARTMENT,""],
        colModel: [{
            name: "dep_name",
            index: "dep_name",
            align: "center",
            width: 70,
            sortable: !0
        }, {
            name: "modify",
            index: "modify",
            align: "center",
            width: 40,
            sortable: !1
        }],
        rowNum: 512,
        pager: "#stis_main_department_list_pager",
        pgbuttons: false,
        pgtext: "",
        recordtext: "",
        emptyrecords: "",
        sortname: "date",
        sortorder: "asc",
        viewrecords: !0,
        width: "100",
        height: "437px",
        rownumbers: false,
        shrinkToFit: !1,
    }), $("#stis_main_department_list_grid").jqGrid("navGrid", "#stis_main_department_list_pager", {
        add: !1,
        edit: !1,
        del: !1,
        search: !1        
    }),

    // Menu STIS - shift List
    $("#stis_main_shift_list_grid").jqGrid({                
        url: "func/fn_addemployee.php?cmd=add_stis_shift",
        datatype: "json",
        colNames: [la.SHIFT_NAME,la.FROM,la.TO,la.DAYS,la.STATUS,""],
        colModel: [{
            name: "shnmae",
            index: "shnmae",
            align: "center",
            width: 70,
            sortable: !0
        },{
            name: "from",
            index: "from",
            align: "center",
            width: 70,
            sortable: !0
        },{
            name: "to",
            index: "to",
            align: "center",
            width: 70,
            sortable: !0
        },{
            name: "days",
            index: "days",
            align: "center",
            width: 70,
            sortable: !0,
            hidden: true
        },{
            name: "status",
            index: "status",
            align: "center",
            width: 70,
            sortable: !0
        }, {
            name: "modify",
            index: "modify",
            align: "center",
            width: 40,
            sortable: !1
        }],
        rowNum: 512,
        pager: "#stis_main_shift_list_pager",
        pgbuttons: false,
        pgtext: "",
        recordtext: "",
        emptyrecords: "",
        sortname: "date",
        sortorder: "asc",
        viewrecords: !0,
        width: "100",
        height: "437px",
        rownumbers: true,
        shrinkToFit: !1,
    }), $("#stis_main_shift_list_grid").jqGrid("navGrid", "#stis_main_shift_list_pager", {
        add: !0,
        edit: !1,
        del: !1,
        search: !1,
        addfunc: function() {
        addStisShift("add")
        }      
    }),
    // Menu STIS - Cab request List
    $("#stis_main_cabrequest_list_grid").jqGrid({                
        url: "func/fn_addemployee.php?cmd=load_stis_cabrequest",
        datatype: "json",
        colNames: [la.CLIENT,la.SCHEDULE,la.FROM,la.TO,la.SECURITY,la.TOTAl_USER,la.STATUS,la.TRIP_TYPE,la.TRIP_STATUS,""],
        colModel: [{
            name: "client",
            index: "client",
            align: "center",
            width: 70,
            sortable: !0
        },{
            name: "schedule",
            index: "schedule",
            align: "center",
            width: 70,
            sortable: !0
        },{
            name: "from",
            index: "from",
            align: "center",
            width: 70,
            sortable: !0
        },{
            name: "to",
            index: "to",
            align: "center",
            width: 70,
            sortable: !0
        },{
            name: "security",
            index: "security",
            align: "center",
            width: 70,
            sortable: !0
        },{
            name: "totuser",
            index: "totuser",
            align: "center",
            width: 70,
            sortable: !0
        },{
            name: "status",
            index: "status",
            align: "center",
            width: 40,
            sortable: !0
        },{
            name: "user_type",
            index: "user_type",
            align: "center",
            width: 40,
            sortable: !0
        },{
            name: "tripstatus",
            index: "tripstatus",
            align: "center",
            width: 70,
            sortable: !0
        }, {
            name: "modify",
            index: "modify",
            align: "center",
            width: 60,
            sortable: !1
        }],
        rowNum: 512,
        pager: "#stis_main_cabrequest_list_pager",
        pgbuttons: false,
        pgtext: "",
        recordtext: "",
        emptyrecords: "",
        sortname: "date",
        sortorder: "asc",
        viewrecords: !0,
        width: "100",
        height: "437px",
        rownumbers: true,
        shrinkToFit: !1,
    }), $("#stis_main_cabrequest_list_grid").jqGrid("navGrid", "#stis_main_cabrequest_list_pager", {
        add: !0,
        edit: !1,
        del: !1,
        search: !1,
        addfunc: function() {
        addCabRequest("add")
        }      
    }),
    // Menu STIS - Cab request List
    $("#stis_main_cabrequest_adduser_list_grid").jqGrid({                
        datatype: "local",
        colNames:[la.ID,la.USER_NAME,la.MOBILENO,''],
        colModel:[
            {name:'id',index:'is', sorttype:"int", width:22, align:"center", sortable:false,hidden:true},
            {name:'username',index:'username', sorttype:"int", width:22, align:"center", sortable:false},
            {name:'mobile',index:'mobile', width:60, align:"center", sortable:false},
            {name:'remove',index:'remove', width:60, align:"center", sortable:false,editable: true,edittype: "text",formatter: removeCabRequestUser},
        ],
        rowNum: 512,
        pager: "#stis_main_cabrequest_adduser_list_pager",
        pgbuttons: false,
        pgtext: "",
        recordtext: "",
        emptyrecords: "",
        sortname: "date",
        sortorder: "asc",
        viewrecords: !0,
        width: "100",
        height: "160px",
        rownumbers: true,
        shrinkToFit: !1,
    }), $("#stis_main_cabrequest_adduser_list_grid").jqGrid("navGrid", "#stis_main_cabrequest_adduser_list_pager", {
        add: !1,
        edit: !1,
        del: !1,
        search: !1  
    })

    // Menu STIS - Cab request user list List
    $("#stis_main_cabrequest_viewuser_list_grid").jqGrid({                
        datatype: "local",
        colNames:[la.ID,la.USER_NAME,la.MOBILENO],
        colModel:[
            {name:'id',index:'is', sorttype:"int", width:22, align:"center", sortable:false,hidden:true},
            {name:'username',index:'username', sorttype:"int", width:22, align:"center", sortable:false},
            {name:'mobile',index:'mobile', width:60, align:"center", sortable:false},
            // {name:'remove',index:'remove', width:60, align:"center", sortable:false,editable: true,edittype: "text",formatter: removeCabRequestUser},
        ],
        rowNum: 512,
        pager: "#stis_main_cabrequest_viewuser_list_pager",
        pgbuttons: false,
        pgtext: "",
        recordtext: "",
        emptyrecords: "",
        sortname: "date",
        sortorder: "asc",
        viewrecords: !0,
        width: "100",
        height: "420",
        rownumbers: true,
        shrinkToFit: !1,
    }), $("#stis_main_cabrequest_viewuser_list_grid").jqGrid("navGrid", "#stis_main_cabrequest_viewuser_list_pager", {
        add: !1,
        edit: !1,
        del: !1,
        search: !1    
    });

    // Menu STIS - Cab request driver list List
    $("#stis_main_cabrequest_viewdriver_list_grid").jqGrid({                
        datatype: "local",
        colNames:[la.ID,la.USER_NAME,la.MOBILENO],
        colModel:[
            {name:'id',index:'is', sorttype:"int", width:22, align:"center", sortable:false,hidden:true},
            {name:'username',index:'username', sorttype:"int", width:22, align:"center", sortable:false},
            {name:'mobile',index:'mobile', width:60, align:"center", sortable:false},
            // {name:'remove',index:'remove', width:60, align:"center", sortable:false,editable: true,edittype: "text",formatter: removeCabRequestUser},
        ],
        rowNum: 512,
        pager: "#stis_main_cabrequest_viewdriver_list_pager",
        pgbuttons: false,
        pgtext: "",
        recordtext: "",
        emptyrecords: "",
        sortname: "date",
        sortorder: "asc",
        viewrecords: !0,
        width: "100",
        height: "420",
        rownumbers: true,
        shrinkToFit: !1,
    }), $("#stis_main_cabrequest_viewdriver_list_grid").jqGrid("navGrid", "#stis_main_cabrequest_viewdriver_list_pager", {
        add: !1,
        edit: !1,
        del: !1,
        search: !1,
        addfunc: function() {
        addCabRequest("add")
        }      
    });

    // Menu STIS - Cab request Vendor Allocate Driver
    $("#dialog_stis_cabrequest_setdriver_list_grid").jqGrid({                
        datatype: "local",
        colNames:[la.ID,la.USER_NAME,la.MOBILENO,''],
        colModel:[
            {name:'id',index:'id', sorttype:"int", width:22, align:"center", sortable:false,hidden:true},
            {name:'username',index:'username', sorttype:"int", width:22, align:"center", sortable:false},
            {name:'mobile',index:'mobile', width:60, align:"center", sortable:false},
            {name:'remove',index:'remove', width:60, align:"center", sortable:false,editable: true,edittype: "text",formatter: removeCabRequestallocatedriver},
        ],
        rowNum: 512,
        pager: "#dialog_stis_cabrequest_setdriver_list_pager",
        pgbuttons: false,
        pgtext: "",
        recordtext: "",
        emptyrecords: "",
        sortname: "date",
        sortorder: "asc",
        viewrecords: !0,
        width: "100",
        height: "260px",
        rownumbers: true,
        shrinkToFit: !1,
    }), $("#dialog_stis_cabrequest_setdriver_list_grid").jqGrid("navGrid", "#dialog_stis_cabrequest_setdriver_list_pager", {
        add: !1,
        edit: !1,
        del: !1,
        search: !1,
        addfunc: function() {
        addCabRequest("add")
        }      
    });

    // Menu STIS - Cab request Vendor List
    $("#stis_main_cabrequest_vendor_list_grid").jqGrid({                
        url: "func/fn_addemployee.php?cmd=load_stis_cabrequest_vendor",
        datatype: "json",
        colNames: [la.CLIENT,la.SCHEDULE,la.FROM,la.TO,la.SECURITY,la.TOTAl_USER,la.STATUS,la.TRIP_TYPE,la.TRIP_STATUS,""],
        colModel: [{
            name: "client",
            index: "client",
            align: "center",
            width: 70,
            sortable: !0
        },{
            name: "schedule",
            index: "schedule",
            align: "center",
            width: 70,
            sortable: !0
        },{
            name: "from",
            index: "from",
            align: "center",
            width: 70,
            sortable: !0
        },{
            name: "to",
            index: "to",
            align: "center",
            width: 70,
            sortable: !0
        },{
            name: "security",
            index: "security",
            align: "center",
            width: 70,
            sortable: !0
        },{
            name: "totuser",
            index: "totuser",
            align: "center",
            width: 70,
            sortable: !0
        },{
            name: "status",
            index: "status",
            align: "center",
            width: 70,
            sortable: !0
        },{
            name: "user_type",
            index: "user_type",
            align: "center",
            width: 70,
            sortable: !0
        },{
            name: "tripstatus",
            index: "tripstatus",
            align: "center",
            width: 70,
            sortable: !0
        }, {
            name: "modify",
            index: "modify",
            align: "center",
            width: 40,
            sortable: !1
        }],
        rowNum: 512,
        pager: "#stis_main_cabrequest_vendor_list_pager",
        pgbuttons: false,
        pgtext: "",
        recordtext: "",
        emptyrecords: "",
        sortname: "date",
        sortorder: "asc",
        viewrecords: !0,
        width: "100",
        height: "437px",
        rownumbers: true,
        shrinkToFit: !1,
    }), $("#stis_main_cabrequest_vendor_list_grid").jqGrid("navGrid", "#stis_main_cabrequest_vendor_list_pager", {
        add: !1,
        edit: !1,
        del: !1,
        search: !1   
    }),
    // Menu STIS - Self Cab request Vendor List
    $("#stis_main_self_cabrequest_vendor_list_grid").jqGrid({                
        url: "func/fn_addemployee.php?cmd=load_stis_self_cabrequest_vendor&tripstatus=A",
        datatype: "json",
        colNames: [la.CLIENT,la.MOBILENO,la.SCHEDULE,la.FROM,la.TO,la.SECURITY,la.DRIVER,la.TRIP_TYPE,la.TRIP_STATUS,""],
        colModel: [{
            name: "client",
            index: "client",
            align: "center",
            width: 50,
            sortable: !0
        },{
            name: "clientnumber",
            index: "clientnumber",
            align: "center",
            width: 70,
            sortable: !0
        },{
            name: "schedule",
            index: "schedule",
            align: "center",
            width: 90,
            sortable: !0
        },{
            name: "from",
            index: "from",
            align: "center",
            width: 80,
            sortable: !0
        },{
            name: "to",
            index: "to",
            align: "center",
            width: 80,
            sortable: !0
        },{
            name: "security",
            index: "security",
            align: "center",
            width: 35,
            sortable: !0
        },{
            name: "totuser",
            index: "totuser",
            align: "center",
            width: 70,
            sortable: !0
        },{
            name: "user_type",
            index: "user_type",
            align: "center",
            width: 50,
            sortable: !0
        },{
            name: "tripstatus",
            index: "tripstatus",
            align: "center",
            width: 60,
            sortable: !0
        }, {
            name: "modify",
            index: "modify",
            align: "center",
            width: 40,
            sortable: !1
        }],
        rowNum: 50,
        rowList: [25, 50, 75, 100, 200],
        pager: "#stis_main_self_cabrequest_vendor_list_pager",
        sortname: "date",
        sortorder: "asc",
        viewrecords: !0,
        height: "437",
        width: "950",
        shrinkToFit: !1,
        rownumbers: true
    }), $("#stis_main_self_cabrequest_vendor_list_grid").jqGrid("navGrid", "#stis_main_self_cabrequest_vendor_list_pager", {
        add: !0,
        edit: !1,
        del: !1,
        search: !1,
        addfunc: function() {
        addCabSelfRequest("add")
        }      
    }),

    $("#settings_object_working_hour_list_grid").jqGrid({
        url: "func/fn_settings.workinghour.php",
        datatype: "json",
        colNames: [la.TIME_FROM, la.TIME_TO, la.DAY, la.STATUS, ""],
        colModel: [{
            name: "time_from",
            index: "time_from",
            width: 140,
            sortable: !0
        }, {
            name: "time_to",
            index: "time_to",
            width: 140,
            align: "center",
            sortable: !0
        }, {
            name: "day",
            index: "day",
            width: 140,
            align: "center",
            sortable: !1
        }, {
            name: "status",
            index: "status",
            width: 140,
            align: "center",
            sortable: !1
        }, {
            name: "modify",
            index: "modify",
            width: 45,
            align: "center",
            sortable: !1
        }],
        rowNum: 512,
        pager: "#settings_object_working_hour_list_grid_pager",
        pgbuttons: !1,
        pgtext: "",
        recordtext: "",
        emptyrecords: "",
        sortname: "from_time",
        sortorder: "asc",
        viewrecords: !0,
        width: "690",
        height: "347",
        shrinkToFit: !1,
        multiselect: !0,
        beforeSelectRow: function(e, t) {
            return "input" === t.target.tagName.toLowerCase()
        }
    }), $("#settings_object_working_hour_list_grid").jqGrid("navGrid", "#settings_object_working_hour_list_grid_pager", {
        add: !0,
        edit: !1,
        del: !1,
        search: !1,
        addfunc: function() {
    	settingsObjectWorkingHourProperties("add")
        }
    }), $("#settings_object_working_hour_list_grid").navButtonAdd("#settings_object_working_hour_list_grid_pager", {
        caption: "",
        title: la.ACTION,
        buttonicon: "ui-icon-action",
        onClickButton: function() {},
        position: "last",
        id: "settings_object_working_hour_list_grid_action_menu_button"
    }), $("#settings_object_working_hour_list_grid_action_menu").menu({
        role: "listbox"
    }), $("#settings_object_working_hour_list_grid_action_menu").hide(), $("#settings_object_working_hour_list_grid_action_menu_button").click(function() {
        return $("#settings_object_working_hour_list_grid_action_menu").toggle().position({
            my: "left bottom",
            at: "right-5 top-5",
            of: this
        }), $(document).one("click", function() {
            $("#settings_object_working_hour_list_grid_action_menu").hide()
        }), !1
    }),

    $("#bottom_panel_historytrack_list_grid").jqGrid({
        url: "func/fn_history.php?cmd=load_msg_empty",
        datatype: "json",
        colNames: [la.NAME,la.TIME_POSITION, la.TIME_SERVER, la.LATITUDE, la.LONGITUDE, la.ALTITUDE, la.ANGLE, la.SPEED,la.MILEAGE,la.GPS,la.IGNITION,la.AIRCONDITION,la.FUELPERCENTAGE,la.FUELLTRS,la.TMP1],
        colModel: [
        {
            name: "name",
            index: "name",
            width: 100,
            fixed: true,
            align: "center",
            sortable: true
        },
        {
            name: "dt_tracker",
            index: "dt_tracker",
            width: 120,
            fixed: true,
            align: "center",
            sortable: true
        }, {
            name: "dt_server",
            index: "dt_server",
            width: 120,
            fixed: true,
            align: "center",
            sortable: true
        }, {
            name: "lat",
            index: "lat",
            width: 100,
            fixed: true,
            align: "center",
            sortable: true
        }, {
            name: "lng",
            index: "lng",
            width: 100,
            fixed: true,
            align: "center",
            sortable: true
        }, {
            name: "altitude",
            index: "altitude",
            width: 90,
            fixed: true,
            align: "center",
            sortable: true
        }, {
            name: "angle",
            index: "angle",
            width: 80,
            fixed: true,
            align: "center",
            sortable: true
        }, {
            name: "speed",
            index: "speed",
            width: 80,
            fixed: true,
            align: "center",
            sortable: true
        }
        , {
            name: "mileage",
            index: "mileage", width: 100,
            align: "center",
            sortable: true
        }
        , {
            name: "gps",
            index: "gps", width: 100,
            align: "center",
            sortable: true
        }
        , {
            name: "ignition",
            index: "ignition", width: 100,
            align: "center",
            sortable: true
        }
        , {
            name: "aircondition",
            index: "aircondition", width: 100,
            align: "center",
            sortable: true
        }
        , {
            name: "fuelpercentage",
            index: "fuelpercentage", width: 100,
            align: "center",
            sortable: true
        }
         , {
             name: "fuelltrs",
             index: "fuelltrs", width: 100,
             align: "center",
             sortable: true
         }
      
         , {
             name: "tmp1",
             index: "tmp1", width: 100,
             align: "center",
             sortable: true
         }
        ],
        sortname: "dt_tracker",
        sortorder: "desc",
        rowNum: 50,
        rowList: [50, 100, 200, 300, 400, 500,1000,2000,3000,4000,5000],
        pager: "#bottom_panel_historytrack_list_grid_pager",
        height: "111",
        onCellSelect: function (g, c, e, d) {
            if (c != 7) {
                var f = $(this).jqGrid("getCell", g, "lat");
                var b = $(this).jqGrid("getCell", g, "lng");
                utilsPointOnMap(f, b)
            }
        },
         shrinkToFit: false,
        multiselect:true,
        beforeSelectRow: function (rowid, e) {
            if (!e.ctrlKey && !e.shiftKey) {
                $("#grid").jqGrid('resetSelection');
            }
            else if (e.shiftKey) {
                var initialRowSelect = $("#grid").jqGrid('getGridParam', 'selrow');
                $("#grid").jqGrid('resetSelection');

                var CurrentSelectIndex = $("#grid").jqGrid('getInd', rowid);
                var InitialSelectIndex = $("#grid").jqGrid('getInd', initialRowSelect);
                var startID = "";
                var endID = "";
                if (CurrentSelectIndex > InitialSelectIndex) {
                    startID = initialRowSelect;
                    endID = rowid;
                }
                else {
                    startID = rowid;
                    endID = initialRowSelect;
                }

                var shouldSelectRow = false;
                $.each($("#grid").getDataIDs(), function(_, id){
                    if ((shouldSelectRow = id == startID || shouldSelectRow)){
                      $("#grid").jqGrid('setSelection', id, false);
                    }
                    return id != endID;                        
                });
            }
            return true;
        }
        
    }),//.jqGrid('hideCol', 'cb') hide check box
    $(window).bind("resize", function () {
        if (document.getElementById("side_panel").style.display == "none") {
            $("#bottom_panel_historytrack_list_grid").setGridWidth($(window).width() - 35)
        } else {
            $("#bottom_panel_historytrack_list_grid").setGridWidth($(window).width() - 200)
        }
    }).trigger("resize"),
    // code finish by vetrivelht
    
    //code update by vetrivel.N
    $("#cmd_status_list_gridnew").jqGrid({
        url: "func/command.php?cmd=load_cmd_exec_list",
        datatype: "json",
        colNames: [la.TIME, la.OBJECT, la.DATA, la.STATUS, "", ""],
        colModel: [{
            name: "senddate",
            index: "senddate",
            width: 120,
            sortable: true
        }, {
            name: "object",
            index: "object",
            width: 150,
            sortable: false
        }, {
            name: "name_cmd",
            index: "name_cmd",
            width: 343,
            sortable: false
        }, {
            name: "status",
            index: "status",
            width: 50,
            align: "center",
            sortable: false
        }, {
            name: "modify",
            index: "modify",
            width: 30,
            align: "center",
            sortable: false
        }, {
            name: "re_hex",
            index: "re_hex",
            hidden: true
        }],
        rowNum: 1000,
        pager: "#cmd_status_list_grid_pagernew",
        pgbuttons: false,
        pgtext: "",
        recordtext: "",
        emptyrecords: "",
        sortname: "senddate",
        sortorder: "desc",
        viewrecords: true,
        height: "500px",
        width: "950",
        shrinkToFit: false,
        loadComplete: function(b) {
            cmdStatusRefreshnew()
        },
        loadError: function(b) {
            cmdStatusRefreshnew()
        },
        subGrid: true,
        subGridRowExpanded: function(f, b) {
            var e = $("#cmd_status_list_gridnew").getRowData(b).re_hex;
            var c = "";
            if (e == "") {
                e = la.NO_DATA
            } 
          
                c = '<table style="table-layout: fixed; width: 100%">';
                c += '<tr><td style="width:60px;">Response:</td><td style="word-wrap:break-word;">' + e + "</td></tr>"
                c += "</table>"
            
            $("#" + f).html(c)
        }
    }),$("#cmd_status_list_gridnew").jqGrid("navGrid", "#cmd_status_list_grid_pagernew", {
        add: false,
        edit: false,
        del: false,
        search: false
    }),
    //code done by vetrivelN.
    //Code updated ^ by vetrivel.N
    $("#dashboard_detail").jqGrid({
    	datatype: "local",
        colNames: ["",la.SINO,la.OBJECT,la.TIME_POSITION, la.STATUS, la.IGNITION,la.AIRCONDITION,  la.SPEED, la.FUEL_LEVEL, la.OVERSPEED, la.ODOMETER, la.EVENTS,la.NEAREST_ZONE],
        colModel: [
        {
    		name: "VNO",
    		index: "VNO",
    		width: 30,
    		fixed: true,
    		align: "center",
    		hidden:true
		},
			{
            name: "SINO",
            index: "SINO",
            width: 30,
            fixed: true,
            align: "center",
            
        }, {
            name: "OBJECT",
            index: "OBJECT",
            width: 120,
            fixed: true,
            align: "center",
        }, {
            name: "TIME_POSITION",
            index: "TIME_POSITION",
            width: 100,
            fixed: true,
            align: "center",
        }, {
            name: "STATUS",
            index: "STATUS",
            width: 50,
            fixed: true,
            align: "center",
        }, {
            name: "IGNITION",
            index: "IGNITION",
            width: 50,
            fixed: true,
            align: "center",
        }, {
            name: "AC",
            index: "AC",
            width: 50,
            fixed: true,
            align: "center",
        }, {
            name: "SPEED",
            index: "SPEED",
            width: 30,
            fixed: true,
            align: "center",
        }, {
            name: "FUEL_LEVEL",
            index: "FUEL_LEVEL",
            width: 50,
            fixed: true,
            align: "center",
        }, {
            name: "OVERSPEED_COUNT",
            index: "OVERSPEED_COUNT",
            width: 60,
            fixed: true,
            align: "center",
        }, {
            name: "ROUTE_LENGTH",
            index: "ROUTE_LENGTH",
            width: 80,
            fixed: true,
            align: "center",
        }
        , {
            name: "EVENTS",
            index: "EVENTS",
            fixed: false,
            align: "center",
        }
        , {
            name: "NEAREST_ZONE",
            index: "NEAREST_ZONE",
            fixed: false,
            align: "center",
        }
        ],
     
        rowNum: 1000,
        viewrecords: true,
        width:"100%",
        height:"100%",
        shrinkToFit: true,
        forceFit:true,  
        scroll: false,

    }),$("#dashboard_object_search").bind("keyup", function(d) {
        	
        	if(this.value!="")
        	{
        	var vtmpi=[];	
        	 for (var h =0;h<vch.length;h++) 
             {
        		 var vtmp=vch[h]["Object"].toLowerCase();
        		 if(vtmp.indexOf(this.value.toLowerCase()) > -1)
        		 {
        			 vtmpi[vtmpi.length]=vch[h];
        		 }
             }
        		dashbinddata(vtmpi);
        		vtmpi=[];
        	}
        	else
        	{	
        		dashbinddata(vch);
        	}
            
        }),
        //trip details updated by vetrivel.N
        
        $("#trip_detail").jqGrid({
        	datatype: "local",
            colNames: ["",la.SINO,la.OBJECT,la.TRIPNAME, la.HOTSPOTNAME, la.DATE, la.ROUTE_START, la.ROUTE_END, la.PROUTE_START, la.PROUTE_END, la.AROUTE_START,la.AROUTE_END ,la.AVG_SPEED,la.DELAY,la.TAKENKM,la.DURATION,la.OVERSPEED_COUNT,la.OVRTIMEPARKING],
            colModel: [
            {
        		name: "VNO",
        		index: "VNO",
        		width: 30,
        		fixed: true,
        		align: "center",
        		hidden:true
    		},
    			{
                name: "SINO",
                index: "SINO",
                width: 30,
                fixed: true,
                align: "center",
                
            }, {
                name: "OBJECT",
                index: "OBJECT",
                width: 100,
                fixed: true,
                align: "center",
            }, {
                name: "TRIPNAME",
                index: "TRIPNAME",
                width: 120,
                fixed: true,
                align: "center",
            }, {
                name: "HOTSPOTNAME",
                index: "HOTSPOTNAME",
                width: 120,
                fixed: true,
                align: "center",
            }, {
                name: "DATE",
                index: "DATE",
                width: 50,
                fixed: true,
                align: "center",
            }, {
                name: "ROUTE_START",
                index: "ROUTE_START",
                width: 100,
                fixed: true,
                align: "center",
            }, {
                name: "ROUTE_END",
                index: "ROUTE_END",
                width: 100,
                fixed: true,
                align: "center",
            }, {
                name: "PROUTE_START",
                index: "PROUTE_START",
                width: 120,
                fixed: true,
                align: "center",
            }, {
                name: "PROUTE_END",
                index: "PROUTE_END",
                width: 120,
                fixed: true,
                align: "center",
            }
            , {
                name: "AROUTE_START",
                index: "AROUTE_START",
                width: 120,
                fixed: true,
                align: "center",
            }
            , {
                name: "AROUTE_END",
                index: "AROUTE_END",
                width: 120,
                fixed: true,
                align: "center",
            }
            , {
                name: "AVG_SPEED",
                index: "AVG_SPEED",
                width: 120,
                fixed: true,
                align: "center",
            }
            , {
                name: "DELAY",
                index: "DELAY",
                width: 100,
                fixed: true,
                align: "center",
            }
            , {
                name: "TAKENKM",
                index: "TAKENKM",
                width: 100,
                fixed: true,
                align: "center",
            }
            , {
                name: "DURATION",
                index: "DURATION",
                width: 100,
                fixed: true,
                align: "center",
            }
            , {
                name: "OVERSPEED_COUNT",
                index: "OVERSPEED_COUNT",
                width: 120,
                fixed: true,
                align: "center",
            }
            , {
                name: "OVRTIMEPARKING",
                index: "OVRTIMEPARKING",
                width: 120,
                fixed: true,
                align: "center",
            }
            ],
         
            rowNum: 1000,
            viewrecords: true,
            width:"973",
            overflow:scroll,
            height: "500",
            shrinkToFit:false,
            forceFit:true,
        }),$("#amb_booking_list_grid").jqGrid({
        	url: "func/fn_ambulance.php?cmd=select_booking&status=Select&from=" + $("#txt_b_datefrom").val() + "&to=" + $("#txt_b_dateto").val(),
        	datatype: "json",
        	colNames: ["Action","id",la.BOOK_BY,la.CREATE_DATE,la.STATUS, la.APP_USERID, la.SELF_OTHERS, la.EMERGENCY_ADDRESS, la.CONTACT_NO, la.EMERGENCY_REASON,
        		la.PEOPLE_COUNT, la.AGE, la.CONSCIOUS, la.BREATHING, la.GENDER, la.PERSON_NAME, la.NOTE1,la.LANDMARK,
        		"", la.ALLOCATED_DRIVER_PHONE, la.ALLOCATED_VEHICLENO, la.ALLOCATED_IMEI,
        		la.ALLOCATED_TIME, la.DRIVER_ACCEPT_TIME, la.VEHICLE_REACHED_TIME, la.PICKEPED_TIME, la.REACHED_DEST_TIME
        	],
        	colModel: [{
        		name: "modify",
        		index: "modify",
        		width: 200,
        		align: "center",
        		sortable: !1
        	}, {
        		name: "booking_id",
        		index: "booking_id",
        		width: 50,
        		hidden:true
        	}, {
        		name: "book_by",
        		index: "book_by",
        		width: 60,
        	}, {
        		name: "createdate",
        		index: "creat_date",
        		width: 90,
        	}, {
        		name: "status",
        		index: "status",
        		width: 63,
        	}, {
        		name: "app_user_id",
        		index: "app_user_id",
        		width: 50,
        		hidden:true
        	}, {
        		name: "self_others",
        		index: "self_others",
        		width: 50,
        		hidden:true
        	}, {
        		name: "emergency_address",
        		index: "emergency_address",
        		width: 100,
        	}, {
        		name: "contact_no",
        		index: "contact_no",
        		width: 60,
        	}, {
        		name: "emergency_reason",
        		index: "emergency_reason",
        		width: 70,
        		sortable: !1
        	}, {
        		name: "people_count",
        		index: "people_count",
        		width: 60
        	}, {
        		name: "age",
        		index: "age",
        		width: 30
        	}, {
        		name: "conscious",
        		index: "conscious",
        		width: 60
        	}, {
        		name: "breathing",
        		index: "breathing",
        		width: 60
        	}, {
        		name: "gender",
        		index: "gender",
        		width: 50,
        	}, {
        		name: "person_name",
        		index: "person_name",
        		width: 60,
        		align: "center",
        		sortable: !1
        	}, {
        		name: "note1",
        		index: "note1",
        		width: 50
        	}, {
        		name: "note2",
        		index: "note2",
        		width: 50
        	}, {
        		name: "allocated_driver_id",
        		index: "allocated_driver_id",
        		width: 60,
        		hidden:true
        	}, {
        		name: "allocated_driver_phone",
        		index: "allocated_driver_phone",
        		width: 60
        	}, {
        		name: "allocated_vehicleno",
        		index: "allocated_vehicleno",
        		width: 60
        	}, {
        		name: "allocated_imei",
        		index: "allocated_imei",
        		width: 60
        	}, {
        		name: "allocated_time",
        		index: "allocated_time",
        		width: 60
        	}, {
        		name: "driver_accept_time",
        		index: "driver_accept_time",
        		width: 60,
        	}, {
        		name: "vehicle_reached_time",
        		index: "vehicle_reached_time",
        		width: 60,
        		align: "center",
        		sortable: !1
        	}, {
        		name: " pickedup_time",
        		index: " pickedup_time",
        		width: 60
        	}, {
        		name: "reached_dest_time",
        		index: "reached_dest_time",
        		width: 60
        	}],
        	rowNum: 50,
        	rowList: [50, 75, 100, 200, 300],
        	pager: "#amb_booking_list_grid_pager",
        	sortname: "booking_id",
        	sortorder: "asc",
        	viewrecords: !0,
        	height: "327px",
        	width: "910",
        	loadui: 'disable',
        	shrinkToFit: false
        }), $("#amb_booking_list_grid").jqGrid("navGrid", "#amb_booking_list_grid_pager", {
        	add: !0,
        	edit: !1,
        	del: !1,
        	search: !1,
        	addfunc: function() {
        		clearbooking();
        	}
        }),$("#amb_booking_vehicle_list_grid").jqGrid({
        	url: "func/fn_ambulance.php?cmd=select_vehicle_list&bookingid=",
             datatype: "json",
             colNames: [la.OBJECT, la.DRIVER, la.IMEI, la.DRIVERPH, la.STATUS,la.TAKENKM,"lat","lng",la.LOCATION,"loc_valid" ,"","bid","uid","vname"],
             colModel: [
{
    name: "vehicle",
    index: "vehicle",
    width: 120,
    sortable: false
}, {
    name: "driver",
    index: "driver",
    width: 140,
    sortable: false
}, {
    name: "imei",
    index: "imei",
    width: 343,
    sortable: false,
    hidden:true
}, {
    name: "phone",
    index: "phone",
    width: 73,
    align: "center",
    sortable: false
}
, {
    name: "status",
    index: "status",
    width: 50,
    align: "center",
    sortable: false
}
, {
    name: "distance",
    index: "distance",
    width: 60,
    align: "center",
    sortable: true
}
, {
    name: "lat",
    index: "lat",
    width: 50,
    align: "center",
    sortable: false,
    hidden:true
}
, {
    name: "lng",
    index: "lng",
    width: 50,
    align: "center",
    sortable: false,
    hidden:true
}
, {
    name: "location",
    index: "location",
    width: 150,
    align: "center",
    sortable: false
}, {
    name: "loc_valid",
    index: "loc_valid",
    width: 50,
    align: "center",
    sortable: false,
    hidden:true
}, {
    name: "modify",
    index: "modify",
    width: 30,
    align: "center",
    sortable: false
}
, {
    name: "bid",
    index: "bid",
    width: 30,
    align: "center",
    sortable: false,
    hidden:true
}
, {
    name: "uid",
    index: "uid",
    width: 30,
    align: "center",
    sortable: false,
    hidden:true
}
, {
    name: "vname",
    index: "vname",
    width: 30,
    align: "center",
    sortable: false,
    hidden:true
}
             ],
             rowNum: 1000, 
             height: "340",
             width: "710",
         	 pager: "#amb_booking_vehicle_list_grid_pager",
         	 sortable: true,
        	 sortname: "distance",
        	 sortorder: "asc",
             pgbuttons: false,
             pgtext: "",
             recordtext: "",
             emptyrecords: "All Ambulance Are Busy",
             viewrecords: true
          
         }), $("#amb_booking_vehicle_list_grid").jqGrid("navGrid", "#amb_booking_vehicle_list_grid_pager", {
        	add:  !1,
        	edit: !1,
        	del: !1,
        	search: !1,
        	
        }); 

    //Code done ^ by vetrivel.N
 
    
}

function gridElementTypeToggle(e, t, a) {
    e = $(e);
    var o = e.getRowData().length;
    for (i = 0; i < o; i++) e.jqGrid("getCell", i, "el_type") == t && $("#" + i, e).css({
        display: a
    })
}

function switchHistoryReportsDateFilter(e) {
    if ("history" == e) var t = "side_panel_history_";
    else if ("report" == e) var t = "dialog_report_";
    else if ("img" == e) var t = "dialog_image_gallery_";
    else if ("rilogbook" == e) var t = "dialog_rilogbook_";
    else if ("dtc" == e) var t = "dialog_dtc_";
    else if ("ambulance" == e) var t = "ambrpt_dialog_report_";
    switch (document.getElementById(t + "hour_from").value = "00", document.getElementById(t + "hour_to").value = "00", document.getElementById(t + "minute_from").value = "00", document.getElementById(t + "minute_to").value = "00", document.getElementById(t + "filter").value) {
        case "0":
            document.getElementById(t + "date_from").value = moment().format("YYYY-MM-DD"), document.getElementById(t + "date_to").value = moment().format("YYYY-MM-DD");
            break;
        case "1":
            document.getElementById(t + "date_from").value = moment().format("YYYY-MM-DD"), document.getElementById(t + "date_to").value = moment().format("YYYY-MM-DD"), document.getElementById(t + "hour_from").value = moment().subtract("hour", 1).format("HH"), document.getElementById(t + "hour_to").value = moment().format("HH"), document.getElementById(t + "minute_from").value = moment().subtract("hour", 1).format("mm"), document.getElementById(t + "minute_to").value = moment().format("mm");
            break;
        case "2":
            document.getElementById(t + "date_from").value = moment().format("YYYY-MM-DD"), document.getElementById(t + "date_to").value = moment().add("days", 1).format("YYYY-MM-DD");
            break;
        case "3":
            document.getElementById(t + "date_from").value = moment().subtract("days", 1).format("YYYY-MM-DD"), document.getElementById(t + "date_to").value = moment().format("YYYY-MM-DD");
            break;
        case "4":
            document.getElementById(t + "date_from").value = moment().subtract("days", 2).format("YYYY-MM-DD"), document.getElementById(t + "date_to").value = moment().subtract("days", 1).format("YYYY-MM-DD");
            break;
        case "5":
            document.getElementById(t + "date_from").value = moment().subtract("days", 3).format("YYYY-MM-DD"), document.getElementById(t + "date_to").value = moment().subtract("days", 2).format("YYYY-MM-DD");
            break;
        case "6":
            document.getElementById(t + "date_from").value = moment().isoWeekday(1).format("YYYY-MM-DD"), document.getElementById(t + "date_to").value = moment().add("days", 1).format("YYYY-MM-DD");
            break;
        case "7":
            document.getElementById(t + "date_from").value = moment().isoWeekday(1).subtract("week", 1).format("YYYY-MM-DD"), document.getElementById(t + "date_to").value = moment().isoWeekday(1).format("YYYY-MM-DD");
            break;
        case "8":
            document.getElementById(t + "date_from").value = moment().startOf("month").format("YYYY-MM-DD"), document.getElementById(t + "date_to").value = moment().add("days", 1).format("YYYY-MM-DD");
            break;
        case "9":
            document.getElementById(t + "date_from").value = moment().startOf("month").subtract("month", 1).format("YYYY-MM-DD"), document.getElementById(t + "date_to").value = moment().startOf("month").format("YYYY-MM-DD")
    }
}

function showHideLeftPanel() {
    "none" == document.getElementById("side_panel").style.display ? (document.getElementById("side_panel").style.display = "block", document.getElementById("bottom_panel").style.left = "365px", document.getElementById("side_panel_dragbar").style.left = "360px", document.getElementById("bottom_panel_dragbar").style.left = "365px", document.getElementById("map").style.left = "365px", document.getElementById("history_view_control").style.left = "416px", $("#bottom_panel_msg_list_grid").setGridWidth($(window).width() - 350), setTimeout(function() {
        map.invalidateSize(!0)
    }, 200)) : (document.getElementById("side_panel").style.display = "none", document.getElementById("bottom_panel").style.left = "5px", document.getElementById("side_panel_dragbar").style.left = "0px", document.getElementById("bottom_panel_dragbar").style.left = "5px", document.getElementById("map").style.left = "5px", document.getElementById("history_view_control").style.left = "51px", $("#bottom_panel_msg_list_grid").setGridWidth($(window).width() - 35), setTimeout(function() {
        map.invalidateSize(!0)
    }, 200))
}

function showBottomPanel(e) {
    void 0 != historyRouteData.route && ("undefined" == typeof e && (e = !0), document.getElementById("bottom_panel").style.display = "block", guiDragbars.bottom_panel < 150 && (guiDragbars.bottom_panel = 0), guiDragbars.bottom_panel > window.innerHeight / 2 && (guiDragbars.bottom_panel = window.innerHeight / 2), $("#bottom_panel").css("height", guiDragbars.bottom_panel), document.getElementById("map").style.bottom = parseInt(guiDragbars.bottom_panel) + 5 + "px", document.getElementById("bottom_panel_dragbar").style.bottom = guiDragbars.bottom_panel + "px", $("#bottom_panel_msg_list_grid").setGridHeight(guiDragbars.bottom_panel - 98), $("#bottom_panel_graph_plot").css("height", guiDragbars.bottom_panel - 75), $("#bottom_panel_dragbar").css("cursor", "row-resize"), 1 == e && map.invalidateSize(!0))
}

function hideBottomPanel(e) {
    "undefined" == typeof e && (e = !0), document.getElementById("bottom_panel").style.display = "none", document.getElementById("map").style.bottom = "4px", document.getElementById("bottom_panel_dragbar").style.bottom = "0px", $("#bottom_panel_dragbar").css("cursor", ""), 1 == e && map.invalidateSize(!0)
}

function resizeBottomPanel(e) {
    return guiDragbars.bottom_panel = window.innerHeight - e, guiDragbars.bottom_panel -= 3, guiDragbars.bottom_panel < 150 ? void hideBottomPanel() : void showBottomPanel(!1)
}

function showExtraData(e, t, a) {
    var o = "",
        i = [];
    switch (e) {
        case "object":
            o = $("#side_panel_objects_object_data_list_grid");
            break;
        case "event":
            o = $("#side_panel_events_event_data_list_grid");
            break;
        case "route":
            o = $("#side_panel_history_route_data_list_grid")
    }
    if (o.clearGridData(!0), "" != a) {
        var s = a.dt_server,
            n = a.dt_tracker,
            l = a.lat,
            d = a.lng,
            r = a.altitude,
            _ = a.angle,
            c = (a.speed, a.params);
        switch (e) {
            case "object":
                var g = getObjectOdometer(t, !1); - 1 != g && i.push({
                    data: la.ODOMETER,
                    value: g + " " + la.UNIT_DISTANCE
                });
                var m = getObjectEngineHours(t, !1); - 1 != m && i.push({
                    data: la.ENGINE_HOURS,
                    value: m
                });
                var u = objectsData[t].status_string;
                "" != u && i.push({
                    data: la.STATUS,
                    value: u
                }), i.push({
                    data: la.TIME_POSITION,
                    value: n
                }), i.push({
                    data: la.TIME_SERVER,
                    value: s
                });
                var p = objectsData[t].service;
                for (var v in p) "true" == p[v].data_list && i.push({
                    data: p[v].name,
                    value: p[v].status
                });
                var y = settingsObjectData[t].custom_fields;
                for (var v in y) {
                    var E = y[v];
                    "true" == E.data_list && i.push({
                        data: E.name,
                        value: E.value
                    })
                }
                break;
            case "event":
                var g = getObjectOdometer(t, a.params); - 1 != g && i.push({
                    data: la.ODOMETER,
                    value: g + " " + la.UNIT_DISTANCE
                });
                var m = getObjectEngineHours(t, a.params); - 1 != m && i.push({
                    data: la.ENGINE_HOURS,
                    value: m
                }), i.push({
                    data: la.TIME_POSITION,
                    value: n
                }), i.push({
                    data: la.TIME_SERVER,
                    value: s
                });
                break;
            case "route":
                var g = getObjectOdometer(t, a.params); - 1 != g && i.push({
                    data: la.ODOMETER,
                    value: g + " " + la.UNIT_DISTANCE
                });
                var m = getObjectEngineHours(t, a.params); - 1 != m && i.push({
                    data: la.ENGINE_HOURS,
                    value: m
                })
        }
        var b = settingsObjectData[t].model;
        "" != b && i.push({
            data: la.MODEL,
            value: b
        });
        var h = settingsObjectData[t].vin;
        "" != h && i.push({
            data: la.VIN,
            value: h
        });
        var f = settingsObjectData[t].plate_number;
        "" != f && i.push({
            data: la.PLATE,
            value: f
        });
        var I = settingsObjectData[t].sim_number;
        "" != I && i.push({
            data: la.SIM_CARD_NUMBER,
            value: I
        });
        var D = getDriver(t, a.params);
        if (0 != D) {
            var B = '<a href="#" onclick="utilsShowDriverInfo(\'' + D.driver_id + "');\">" + D.name + "</a>";
            i.push({
                data: la.DRIVER,
                value: B
            })
        }
        var O = getTrailer(t, a.params);
        if (0 != O) {
            var j = '<a href="#" onclick="utilsShowTrailerInfo(\'' + O.trailer_id + "');\">" + O.name + "</a>";
            i.push({
                data: la.TRAILER,
                value: j
            })
        }
        if (1 == gsValues.side_panel_address) {
            geocoderGetAddress(l, d, function(e) {
                document.getElementById("side_panel_objects_object_data_list_grid_address").innerHTML = e, objectsData[t].address = e
            });
            var R = '<div id="side_panel_objects_object_data_list_grid_address">' + objectsData[t].address + "</div>";
            i.push({
                data: la.ADDRESS,
                value: R
            })
        }
        var T = urlPosition(l, d);
        i.push({
            data: la.POSITION,
            value: T
        }), i.push({
            data: la.ALTITUDE,
            value: r + " " + la.UNIT_HEIGHT
        }), i.push({
            data: la.ANGLE,
            value: _ + " &deg;"
        });
        var k = getNearestZone(t, l, d);
        "" != k.name && i.push({
            data: la.NEAREST_ZONE,
            value: k.name + " (" + k.distance + ")"
        });
        var w = getNearestMarker(t, l, d);
        "" != w.name && i.push({
            data: la.NEAREST_MARKER,
            value: w.name + " (" + w.distance + ")"
        });
        var S = settingsObjectData[t].sensors;
        for (var v in S) {
            var L = S[v];
            if ("true" == L.data_list) {
                var A = getSensorValue(c, L);
                i.push({
                    data: L.name,
                    value: A.value_full
                })
            }
        }
        
        //code updated by vetrivel.N...
        /*
        var dat = {cmd: "live_driver_employee",imei:t};
   		$.ajax({
   				async:false,
                   type: "POST",
                   url: "func/fn__rfid.php",
                   data: dat,
                   success: function(outf) {
                     if(outf!=null && outf.length>0)
                     {
                       o.push({ data: 'Driver' + ":", value: outf[0]["driver"]  });
                       
                       if(outf[1]["employee"].length>0)
                       {
                       	o.push({ data: 'Employee ' + ":", value: outf[1]["employee"]  });
                       }
                       
                      }
                   }                 
               });    
   		*/
        for (var x = 0; x < i.length; x++) o.jqGrid("addRowData", x, i[x]);
        o.setGridParam({
            sortname: "data",
            sortorder: "asc"
        }).trigger("reloadGrid")
    }
}

function historyLoadGSR() {
    utilsCheckPrivileges("history") && (document.getElementById("load_file").addEventListener("change", historyLoadGSRFile, !1), document.getElementById("load_file").click())
}

function historyLoadGSRFile(e) {
    var t = e.target.files,
        a = new FileReader;
    a.onload = function(e) {
        loadingData(!0);
        try {
            var t = $.parseJSON(e.target.result);
            "0.2v" == t.gsr ? void 0 != settingsObjectData[t.imei] ? historyShowRoute(transformToHistoryRoute(t.route), t.imei, t.name) : notifyBox("error", la.ERROR, la.THERE_IS_NO_SUCH_OBJECT_IN_YOUR_ACCOUNT) : notifyBox("error", la.ERROR, la.INVALID_FILE_FORMAT)
        } catch (a) {
            notifyBox("error", la.ERROR, la.INVALID_FILE_FORMAT)
        }
        loadingData(!1), document.getElementById("load_file").value = ""
    }, a.readAsText(t[0], "UTF-8"), this.removeEventListener("change", historyLoadGSRFile, !1)
}

function historyExportGSR() {
    if (utilsCheckPrivileges("history")) {
        var e = document.getElementById("side_panel_history_object_list").value,
            t = document.getElementById("side_panel_history_object_list").options[document.getElementById("side_panel_history_object_list").selectedIndex].text,
            a = document.getElementById("side_panel_history_date_from").value + " " + document.getElementById("side_panel_history_hour_from").value + ":" + document.getElementById("side_panel_history_minute_from").value + ":00",
            o = document.getElementById("side_panel_history_date_to").value + " " + document.getElementById("side_panel_history_hour_to").value + ":" + document.getElementById("side_panel_history_minute_to").value + ":00",
            i = document.getElementById("side_panel_history_stop_duration").value;
        if ("" == e) return void notifyBox("info", la.INFORMATION, la.NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST);
        var s = "func/fn_export.php?format=gsr&imei=" + e + "&name=" + t + "&dtf=" + a + "&dtt=" + o + "&min_stop_duration=" + i;
        window.location = s
    }
}

function historyExportKML() {
    if (utilsCheckPrivileges("history")) {
        var e = document.getElementById("side_panel_history_object_list").value,
            t = document.getElementById("side_panel_history_object_list").options[document.getElementById("side_panel_history_object_list").selectedIndex].text,
            a = document.getElementById("side_panel_history_date_from").value + " " + document.getElementById("side_panel_history_hour_from").value + ":" + document.getElementById("side_panel_history_minute_from").value + ":00",
            o = document.getElementById("side_panel_history_date_to").value + " " + document.getElementById("side_panel_history_hour_to").value + ":" + document.getElementById("side_panel_history_minute_to").value + ":00";
        if ("" == e) return void notifyBox("info", la.INFORMATION, la.NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST);
        var i = "func/fn_export.php?format=kml&imei=" + e + "&name=" + t + "&dtf=" + a + "&dtt=" + o;
        window.location = i
    }
}

function historyExportGPX() {
    if (utilsCheckPrivileges("history")) {
        var e = document.getElementById("side_panel_history_object_list").value,
            t = document.getElementById("side_panel_history_object_list").options[document.getElementById("side_panel_history_object_list").selectedIndex].text,
            a = document.getElementById("side_panel_history_date_from").value + " " + document.getElementById("side_panel_history_hour_from").value + ":" + document.getElementById("side_panel_history_minute_from").value + ":00",
            o = document.getElementById("side_panel_history_date_to").value + " " + document.getElementById("side_panel_history_hour_to").value + ":" + document.getElementById("side_panel_history_minute_to").value + ":00";
        if ("" == e) return void notifyBox("info", la.INFORMATION, la.NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST);
        var i = "func/fn_export.php?format=gpx&imei=" + e + "&name=" + t + "&dtf=" + a + "&dtt=" + o;
        window.location = i
    }
}

function historyExportCSV() {
    if (utilsCheckPrivileges("history")) {
        var e = document.getElementById("side_panel_history_object_list").value,
            t = document.getElementById("side_panel_history_object_list").options[document.getElementById("side_panel_history_object_list").selectedIndex].text,
            a = document.getElementById("side_panel_history_date_from").value + " " + document.getElementById("side_panel_history_hour_from").value + ":" + document.getElementById("side_panel_history_minute_from").value + ":00",
            o = document.getElementById("side_panel_history_date_to").value + " " + document.getElementById("side_panel_history_hour_to").value + ":" + document.getElementById("side_panel_history_minute_to").value + ":00";
        if ("" == e) return void notifyBox("info", la.INFORMATION, la.NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST);
        var i = "func/fn_export.php?format=history_csv&imei=" + e + "&name=" + t + "&dtf=" + a + "&dtt=" + o;
        window.location = i
    }
}

function historySaveAsRoute() {
    if (utilsCheckPrivileges("viewer") && utilsCheckPrivileges("subuser") && utilsCheckPrivileges("history") && 1 != gsValues.map_bussy) {
        var e = document.getElementById("side_panel_history_object_list").value,
            t = (document.getElementById("side_panel_history_object_list").options[document.getElementById("side_panel_history_object_list").selectedIndex].text, document.getElementById("side_panel_history_date_from").value + " " + document.getElementById("side_panel_history_hour_from").value + ":" + document.getElementById("side_panel_history_minute_from").value + ":00"),
            a = document.getElementById("side_panel_history_date_to").value + " " + document.getElementById("side_panel_history_hour_to").value + ":" + document.getElementById("side_panel_history_minute_to").value + ":00",
            o = document.getElementById("side_panel_history_stop_duration").value;
        if ("" == e) return void notifyBox("info", la.INFORMATION, la.NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST);
        loadingData(!0);
        var s = {
            cmd: "load_route_data",
            imei: e,
            dtf: t,
            dtt: a,
            min_stop_duration: o
        };
        $.ajax({
            type: "POST",
            url: "func/fn_history.php",
            data: s,
            dataType: "json",
            cache: !1,
            success: function(e) {
                var t = transformToHistoryRoute(e);
                if ("" == t.route || t.route.length < 2) return notifyBox("info", la.INFORMATION, la.NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST), void loadingData(!1);
                var a = Math.ceil(t.route.length / 200),
                    o = new Array;
                for (i = 0; i < t.route.length; i += a) {
                    var s = t.route[i].lat,
                        n = t.route[i].lng;
                    o.push(L.latLng(s, n))
                }
                loadingData(!1), placesRouteSave(o)
            },
            error: function() {
                notifyBox("info", la.INFORMATION, la.NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST), loadingData(!1)
            }
        })
    }
}

function historyLoadRoute() {
    if (utilsCheckPrivileges("history")) {
        var e = document.getElementById("side_panel_history_object_list").value,
            t = document.getElementById("side_panel_history_object_list").options[document.getElementById("side_panel_history_object_list").selectedIndex].text,
            a = document.getElementById("side_panel_history_date_from").value + " " + document.getElementById("side_panel_history_hour_from").value + ":" + document.getElementById("side_panel_history_minute_from").value + ":00",
            o = document.getElementById("side_panel_history_date_to").value + " " + document.getElementById("side_panel_history_hour_to").value + ":" + document.getElementById("side_panel_history_minute_to").value + ":00",
            i = document.getElementById("side_panel_history_stop_duration").value;
        if ("" == e) return void notifyBox("info", la.INFORMATION, la.NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST);
        loadingData(!0);
        var s = {
            cmd: "load_route_data",
            imei: e,
            dtf: a,
            dtt: o,
            min_stop_duration: i
        };
        $.ajax({
            type: "POST",
            url: "func/fn_history.php",
            data: s,
            dataType: "json",
            cache: !1,
            success: function(i) {
                historyShowRoute(transformToHistoryRoute(i), e, t), $("#bottom_panel_msg_list_grid").setGridParam({
                    url: "func/fn_history.php?cmd=load_msg_list&imei=" + e + "&dtf=" + a + "&dtt=" + o
                }), $("#bottom_panel_msg_list_grid").trigger("reloadGrid"),
                $("#bottom_panel_historytrack_list_grid").setGridParam({
                    url: "func/fn_history.php?cmd=load_historytrack&imei=" + e + "&dtf=" + a + "&dtt=" + o });
                $("#bottom_panel_historytrack_list_grid").trigger("reloadGrid")
            },
            error: function() {
                notifyBox("info", la.INFORMATION, la.NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST), loadingData(!1)
            }
        })
    }
}

function historyShowRoute(e, t, a) {
    if (historyHideRoute(), objectFollowAll(!1), historyRouteData = e, "" == historyRouteData.route || historyRouteData.route.length < 2) return notifyBox("info", la.INFORMATION, la.NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST), loadingData(!1), void(historyRouteData = []);
    historyRouteData.name = a, historyRouteData.imei = t, historyRouteData.layers = new Array, historyRouteData.layers.route = !1, historyRouteData.layers.route_snap = !1, historyRouteData.layers.arrows = !1, historyRouteData.layers.arrows_snap = !1, historyRouteData.layers.stops = new Array, historyRouteData.layers.events = new Array, historyRouteData.layers.data_points = new Array, historyRouteData.play = new Array, historyRouteData.play.status = !1, historyRouteData.play.position = 0;
    var o = new Array;
    for (n = 0; n < historyRouteData.route.length; n++) {
        var i = historyRouteData.route[n].lat,
            s = historyRouteData.route[n].lng;
        o.push(L.latLng(i, s)), historyRouteAddDataPointMarkerToMap(n)
    }
    for (historyRouteData.layers.route = L.polyline(o, {
            color: settingsUserData.map_rc,
            opacity: .8,
            weight: 3
        }), mapLayers.history.addLayer(historyRouteData.layers.route), historyRouteData.layers.arrows = L.polylineDecorator(historyRouteData.layers.route, {
            patterns: [{
                offset: 25,
                repeat: 250,
                symbol: L.Symbol.arrowHead({
                    pixelSize: 14,
                    headAngle: 40,
                    pathOptions: {
                        fillOpacity: 1,
                        weight: 0
                    }
                })
            }]
        }), mapLayers.history.addLayer(historyRouteData.layers.arrows), historyRouteAddStartMarkerToMap(), historyRouteAddEndMarkerToMap(), n = 0; n < historyRouteData.stops.length; n++) historyRouteAddStopMarkerToMap(n);
    for (n = 0; n < historyRouteData.events.length; n++) historyRouteAddEventMarkerToMap(n);
    for (e = [], e.push({
            el_type: "point",
            el_id: 0,
            icon: '<img src="img/markers/route-start.svg"/>',
            datetime: historyRouteData.route[0].dt_tracker,
            info: ""
        }), e.push({
            el_type: "point",
            el_id: historyRouteData.route.length - 1,
            icon: '<img src="img/markers/route-end.svg"/>',
            datetime: historyRouteData.route[historyRouteData.route.length - 1].dt_tracker,
            info: ""
        }), n = 0; n < historyRouteData.stops.length; n++) e.push({
        el_type: "stop",
        el_id: n,
        icon: '<img src="img/markers/route-stop.svg"/>',
        datetime: historyRouteData.stops[n].dt_start,
        info: historyRouteData.stops[n].duration
    });
    for (n = 0; n < historyRouteData.events.length; n++) e.push({
        el_type: "event",
        el_id: n,
        icon: '<img src="img/markers/route-event.svg"/>',
        datetime: historyRouteData.events[n].dt_tracker,
        info: historyRouteData.events[n].event_desc
    });
    for (n = 0; n < historyRouteData.drives.length; n++) e.push({
        el_type: "drive",
        el_id: n,
        icon: '<img src="img/markers/route-drive.svg"/>',
        datetime: historyRouteData.drives[n].dt_start,
        info: historyRouteData.drives[n].duration
    });
    for (var n = 0; n <= e.length; n++) $("#side_panel_history_route_detail_list_grid").jqGrid("addRowData", n, e[n]);
    $("#side_panel_history_route_detail_list_grid").setGridParam({
        sortname: "datetime",
        sortorder: "asc"
    }).trigger("reloadGrid"), historyRouteToggleRoute(), historyRouteToggleSnap(), historyRouteToggleDataPoints(), historyRouteToggleStops(), historyRouteToggleEvents();
    var l = historyRouteData.layers.route.getBounds();
    map.fitBounds(l), showBottomPanel(), $("#bottom_panel_tabs").tabs("option", "active", 0), historyRouteCreateGraphSourceList(), historyRouteCreateGraph("speed"), document.getElementById("history_view_control").style.display = "block", loadingData(!1)
}

function historyHideRoute() {
    void 0 != historyRouteData.route && (document.getElementById("history_view_control").style.display = "none", hideBottomPanel(), document.getElementById("bottom_panel_graph_label").innerHTML = "", initGraph(), $("#bottom_panel_msg_list_grid").clearGridData(!0), $("#side_panel_history_route_detail_list_grid").clearGridData(!0), $("#side_panel_history_route_data_list_grid").clearGridData(!0), mapLayers.history.clearLayers(), historyRouteStop(), $(".qtip").each(function() {
        $(this).data("qtip").destroy()
    }), historyRouteData = [])
}

function historyRouteToggleRoute() {
    if (void 0 != historyRouteData.layers) {
        var e = document.getElementById("history_view_control_route"),
            t = document.getElementById("history_view_control_snap");
        mapLayers.history.removeLayer(historyRouteData.layers.route), 0 != historyRouteData.layers.route_snap && mapLayers.history.removeLayer(historyRouteData.layers.route_snap), 1 == e.checked && (0 != historyRouteData.layers.route_snap && 1 == t.checked ? mapLayers.history.addLayer(historyRouteData.layers.route_snap) : mapLayers.history.addLayer(historyRouteData.layers.route))
    }
}

function historyRouteToggleSnap() {
    if (void 0 != historyRouteData.route) {
        var e = document.getElementById("history_view_control_snap");
        if (1 == e.checked)
            if (0 == historyRouteData.layers.route_snap && 0 == historyRouteData.layers.arrows_snap) {
                var t = new Array,
                    a = historyRouteData.route[0].lat,
                    o = historyRouteData.route[0].lng;
                t.push(L.latLng(a, o));
                var s = Math.floor(historyRouteData.route.length / 10);
                for (0 == s && (s = 1), i = 0; i < historyRouteData.route.length; i += s) a = historyRouteData.route[i].lat, o = historyRouteData.route[i].lng, t.push(L.latLng(a, o));
                a = historyRouteData.route[historyRouteData.route.length - 1].lat, o = historyRouteData.route[historyRouteData.route.length - 1].lng, t.push(L.latLng(a, o));
                var n = L.Routing.control({
                    waypoints: t,
                    show: !1,
                    showAlternatives: !1,
                    waypointMode: "snap",
                    createMarker: function() {}
                }).addTo(map);
                n.on("routeselected", function(e) {
                    t = e.route.coordinates, mapLayers.history.removeLayer(historyRouteData.layers.route), mapLayers.history.removeLayer(historyRouteData.layers.arrows), historyRouteData.layers.route_snap = L.polyline(t, {
                        color: settingsUserData.map_rc,
                        opacity: .8,
                        weight: 3
                    }), mapLayers.history.addLayer(historyRouteData.layers.route_snap), historyRouteData.layers.arrows_snap = L.polylineDecorator(historyRouteData.layers.route_snap, {
                        patterns: [{
                            offset: 25,
                            repeat: 250,
                            symbol: L.Symbol.arrowHead({
                                pixelSize: 14,
                                headAngle: 40,
                                pathOptions: {
                                    fillOpacity: 1,
                                    weight: 0
                                }
                            })
                        }]
                    }), mapLayers.history.addLayer(historyRouteData.layers.arrows_snap), map.removeControl(n), historyRouteToggleArrows()
                })
            } else historyRouteToggleRoute(), historyRouteToggleArrows();
        else historyRouteToggleRoute(), historyRouteToggleArrows()
    }
}

function historyRouteToggleArrows() {
    if (void 0 != historyRouteData.layers) {
        var e = document.getElementById("history_view_control_arrows"),
            t = document.getElementById("history_view_control_snap");
        mapLayers.history.removeLayer(historyRouteData.layers.arrows), 0 != historyRouteData.layers.arrows_snap && mapLayers.history.removeLayer(historyRouteData.layers.arrows_snap), 1 == e.checked && (0 != historyRouteData.layers.arrows_snap && 1 == t.checked ? mapLayers.history.addLayer(historyRouteData.layers.arrows_snap) : mapLayers.history.addLayer(historyRouteData.layers.arrows))
    }
}

function historyRouteToggleDataPoints() {
    if (void 0 != historyRouteData.layers)
        if (map.getZoom() >= 14) {
            var e = document.getElementById("history_view_control_data_points");
            for (i = 0; i < historyRouteData.layers.data_points.length; i++) {
                var t = historyRouteData.layers.data_points[i];
                1 == e.checked ? 0 == mapLayers.history.hasLayer(t) && mapLayers.history.addLayer(t) : mapLayers.history.removeLayer(t)
            }
        } else
            for (i = 0; i < historyRouteData.layers.data_points.length; i++) {
                var t = historyRouteData.layers.data_points[i];
                mapLayers.history.removeLayer(t)
            }
}

function historyRouteToggleStops() {
    if (void 0 != historyRouteData.layers) {
        var e = document.getElementById("history_view_control_stops");
        for (i = 0; i < historyRouteData.layers.stops.length; i++) {
            var t = historyRouteData.layers.stops[i];
            1 == e.checked ? mapLayers.history.addLayer(t) : mapLayers.history.removeLayer(t)
        }
        1 == e.checked ? gridElementTypeToggle("#side_panel_history_route_detail_list_grid", "stop", "") : gridElementTypeToggle("#side_panel_history_route_detail_list_grid", "stop", "none")
    }
}

function historyRouteToggleEvents() {
    if (void 0 != historyRouteData.layers) {
        var e = document.getElementById("history_view_control_events");
        for (i = 0; i < historyRouteData.layers.events.length; i++) {
            var t = historyRouteData.layers.events[i];
            1 == e.checked ? mapLayers.history.addLayer(t) : mapLayers.history.removeLayer(t)
        }
        1 == e.checked ? gridElementTypeToggle("#side_panel_history_route_detail_list_grid", "event", "") : gridElementTypeToggle("#side_panel_history_route_detail_list_grid", "event", "none")
    }
}

function historyRouteCreateGraphSourceList() {
    var e = historyRouteData.imei,
        t = document.getElementById("bottom_panel_graph_data_source");
    t.options.length = 0, t.options.add(new Option(la.SPEED, "speed")), t.options.add(new Option(la.ALTITUDE, "altitude"));
    var a = settingsObjectData[e].sensors;
    for (var o in a) {
        var i = a[o];
        "string" != i.result_type && "rel" != i.result_type && t.options.add(new Option(i.name, o))
    }
}

function historyRouteChangeGraphSource() {
    var e = document.getElementById("bottom_panel_graph_data_source").value;
    historyRouteCreateGraph(e)
}

function historyRouteCreateGraph(e) {
    document.getElementById("bottom_panel_graph_label").innerHTML = "";
    var t = historyRouteData.imei;
    if (historyRouteData.graph = [], historyRouteData.graph.data = [], historyRouteData.graph.data_index = [], "speed" != e && "altitude" != e) var a = settingsObjectData[t].sensors[e];
    for (var o = 0; o < historyRouteData.route.length; o++) {
        var i = historyRouteData.route[o].dt_tracker,
            s = getTimestampFromDate(i.replace(/-/g, "/") + " UTC");
        if ("speed" == e) var n = historyRouteData.route[o].speed;
        else if ("altitude" == e) var n = historyRouteData.route[o].altitude;
        else {
            var n = getSensorValue(historyRouteData.route[o].params, a).value;
            "engh" == a.type && (n = n / 60 / 60, n = Math.round(100 * n) / 100)
        }
        historyRouteData.graph.data.push([s, n]), historyRouteData.graph.data_index[s] = o
    }
    "speed" == e ? (historyRouteData.graph.units = la.UNIT_SPEED, historyRouteData.graph.result_type = "") : "altitude" == e ? (historyRouteData.graph.units = la.UNIT_HEIGHT, historyRouteData.graph.result_type = "") : "odo" == a.type ? (historyRouteData.graph.units = la.UNIT_DISTANCE, historyRouteData.graph.result_type = a.result_type) : "engh" == a.type ? (historyRouteData.graph.units = la.UNIT_H, historyRouteData.graph.result_type = a.result_type) : (historyRouteData.graph.units = a.units, historyRouteData.graph.result_type = a.result_type), initGraph(historyRouteData.graph)
}

function historyRoutePlay() {
    if (clearTimeout(timer_historyRoutePlay), 0 == historyRouteData.play.status && destroyMapPopup(), historyRouteData.route.length > 0 && historyRouteData.play.position < historyRouteData.route.length) {
        var e = historyRouteData.route[historyRouteData.play.position].dt_tracker,
            t = getTimestampFromDate(e.replace(/-/g, "/") + " UTC");
        graphSetCrosshair(t);
        var a = historyRouteData.graph.data,
            o = historyRouteData.graph.units;
        document.getElementById("bottom_panel_graph_label").innerHTML = a[historyRouteData.play.position][1] + " " + o + " - " + e;
        var i = historyRouteData.route[historyRouteData.play.position];
        if (showExtraData("route", historyRouteData.imei, i), historyRoutePanToPoint(historyRouteData.play.position), historyRouteAddPointMarkerToMap(historyRouteData.play.position), historyRouteData.play.status = !0, historyRouteData.play.position == historyRouteData.route.length - 1) return clearTimeout(timer_historyRoutePlay), historyRouteData.play.status = !1, void(historyRouteData.play.position = 0);
        1 == document.getElementById("bottom_panel_graph_play_speed").value ? timer_historyRoutePlay = setTimeout("historyRoutePlay()", 2e3) : 2 == document.getElementById("bottom_panel_graph_play_speed").value ? timer_historyRoutePlay = setTimeout("historyRoutePlay()", 1e3) : 3 == document.getElementById("bottom_panel_graph_play_speed").value ? timer_historyRoutePlay = setTimeout("historyRoutePlay()", 500) : 4 == document.getElementById("bottom_panel_graph_play_speed").value ? timer_historyRoutePlay = setTimeout("historyRoutePlay()", 250) : 5 == document.getElementById("bottom_panel_graph_play_speed").value ? timer_historyRoutePlay = setTimeout("historyRoutePlay()", 125) : 6 == document.getElementById("bottom_panel_graph_play_speed").value && (timer_historyRoutePlay = setTimeout("historyRoutePlay()", 65)), historyRouteData.play.position++
    }
}

function historyRoutePause() {
    clearTimeout(timer_historyRoutePlay)
}

function historyRouteStop() {
    clearTimeout(timer_historyRoutePlay), historyRouteData.play.status = !1, historyRouteData.play.position = 0
}

function historyRouteAddStartMarkerToMap() {
    var e = historyRouteData.route[0].lng,
        t = historyRouteData.route[0].lat,
        a = L.marker([t, e], {
            icon: mapMarkerIcons.route_start
        });
    a.on("click", function() {
        historyRouteShowPoint(0)
    }), mapLayers.history.addLayer(a)
}

function historyRouteAddEndMarkerToMap() {
    var e = historyRouteData.route[historyRouteData.route.length - 1].lng,
        t = historyRouteData.route[historyRouteData.route.length - 1].lat,
        a = L.marker([t, e], {
            icon: mapMarkerIcons.route_end
        });
    a.on("click", function() {
        var e = historyRouteData.route.length - 1;
        historyRouteShowPoint(e)
    }), mapLayers.history.addLayer(a)
}

function historyRouteAddStopMarkerToMap(e) {
    var t = historyRouteData.stops[e].lng,
        a = historyRouteData.stops[e].lat,
        o = L.marker([a, t], {
            icon: mapMarkerIcons.route_stop
        });
    o.on("click", function() {
        historyRouteShowStop(e)
    }), mapLayers.history.addLayer(o), historyRouteData.layers.stops.push(o)
}

function historyRouteAddEventMarkerToMap(e) {
    var t = historyRouteData.events[e].lng,
        a = historyRouteData.events[e].lat,
        o = L.marker([a, t], {
            icon: mapMarkerIcons.route_event
        });
    o.on("click", function() {
        historyRouteShowEvent(e)
    }), mapLayers.history.addLayer(o), historyRouteData.layers.events.push(o)
}

function historyRouteAddDataPointMarkerToMap(e) {
    var t = (historyRouteData.imei, historyRouteData.route[e].lng),
        a = historyRouteData.route[e].lat,
        o = L.marker([a, t], {
            icon: mapMarkerIcons.route_data_point,
            iconAngle: 0
        }),
        i = e;
    o.on("click", function() {
        historyRouteShowPoint(i)
    }), historyRouteData.layers.data_points.push(o)
}

function historyRouteAddPointMarkerToMap(e) {
    historyRouteRemovePointMarker();
    var t = historyRouteData.imei,
        a = historyRouteData.route[e].lng,
        o = historyRouteData.route[e].lat,
        i = historyRouteData.route[e].angle,
        s = historyRouteData.route[e].speed,
        n = historyRouteData.route[e].dt_tracker,
        l = (historyRouteData.route[e].params, settingsUserData.map_is),
        d = i;
    "arrow" != settingsObjectData[t].map_icon && (d = 0);
    var r = getMarkerIcon(t, s, !1, !1),
        _ = L.marker([o, a], {
            icon: r,
            iconAngle: d
        }),
        c = s + " " + la.UNIT_SPEED + " - " + n;
    _.bindTooltip(c, {
        permanent: !0,
        offset: [20 * l, 0],
        direction: "right"
    }).openTooltip(), _.on("click", function() {
        historyRouteShowPoint(e)
    }), mapLayers.history.addLayer(_), historyRouteData.layers.point_marker = _
}

function historyRouteRemovePointMarker() {
    historyRouteData.layers.point_marker && mapLayers.history.removeLayer(historyRouteData.layers.point_marker)
}

function historyRoutePanToPoint(e) {
    var t = historyRouteData.route[e].lng,
        a = historyRouteData.route[e].lat;
    map.panTo({
        lat: a,
        lng: t
    })
}

function historyRouteShowPoint(e) {
    historyRouteRemoveDrive();
    var t = historyRouteData.name,
        a = historyRouteData.imei,
        o = historyRouteData.route[e].lng,
        i = historyRouteData.route[e].lat,
        s = historyRouteData.route[e].altitude,
        n = historyRouteData.route[e].angle,
        l = historyRouteData.route[e].speed,
        d = historyRouteData.route[e].dt_tracker,
        r = historyRouteData.route[e].params,
        _ = historyRouteData.route[e];
    showExtraData("route", a, _);
    var c = settingsUserData.map_is;
    // geocoderGetAddress(i, o, function(_) {
        var g ="<div id='historyRouteShowPointAddress'><a onclick='showobjectaddress("+i+","+"\""+o+"\""+","+"\"historyRouteShowPointAddress\""+")'>View Address</a></div>",
        // var g = _,
            m = urlPosition(i, o),
            u = "",
            p = new Array;
        for (var v in settingsObjectData[a].sensors) p.push(settingsObjectData[a].sensors[v]);
        var y = sortArrayByElement(p, "name");
        for (var v in y) {
            var E = y[v];
            if ("true" == E.popup) {
                var b = getSensorValue(r, E);
                u += "<tr><td><strong>" + E.name + ":</strong></td><td>" + b.value_full + "</td></tr>"
            }
        }
        var h = "<table>            <tr><td><strong>" + la.OBJECT + ":</strong></td><td>" + t + "</td></tr>         <tr><td><strong>" + la.ADDRESS + ":</strong></td><td>" + g + "</td></tr>            <tr><td><strong>" + la.POSITION + ":</strong></td><td>" + m + "</td></tr>           <tr><td><strong>" + la.ALTITUDE + ":</strong></td><td>" + s + " " + la.UNIT_HEIGHT + "</td></tr>            <tr><td><strong>" + la.ANGLE + ":</strong></td><td>" + n + " &deg;</td></tr>            <tr><td><strong>" + la.SPEED + ":</strong></td><td>" + l + " " + la.UNIT_SPEED + "</td></tr>            <tr><td><strong>" + la.TIME + ":</strong></td><td>" + d + "</td></tr>",
            f = getObjectOdometer(a, r); - 1 != f && (h += "<tr><td><strong>" + la.ODOMETER + ":</strong></td><td>" + f + " " + la.UNIT_DISTANCE + "</td></tr>");
        var I = getObjectEngineHours(a, r); - 1 != I && (h += "<tr><td><strong>" + la.ENGINE_HOURS + ":</strong></td><td>" + I + "</td></tr>");
        var D = h + u;
        h += "</table>", D += "</table>", 0 == e || historyRouteData.route.length - 1 == e ? addPopupToMap(i, o, [0, -28 * c], h, D) : addPopupToMap(i, o, [0, -14 * c], h, D)
    // })
}

function historyRoutePanToStop(e) {
    var t = historyRouteData.stops[e].lng,
        a = historyRouteData.stops[e].lat;
    map.panTo({
        lat: a,
        lng: t
    })
}

function historyRouteShowStop(e) {
    historyRouteRemoveDrive();
    var t = historyRouteData.name,
        a = historyRouteData.imei,
        o = historyRouteData.stops[e].lng,
        i = historyRouteData.stops[e].lat,
        s = historyRouteData.stops[e].altitude,
        n = historyRouteData.stops[e].angle,
        l = historyRouteData.stops[e].dt_start,
        d = historyRouteData.stops[e].dt_end,
        r = historyRouteData.stops[e].duration,
        _ = historyRouteData.stops[e].params,
        c = historyRouteData.stops[e];
    showExtraData("route", a, c);
    var g = settingsUserData.map_is;
    // geocoderGetAddress(i, o, function(e) {
        var c = "<div id='historyRouteShowStopAddress'><a onclick='showobjectaddress("+i+","+"\""+o+"\""+","+"\"historyRouteShowStopAddress\""+")'>View Address</a></div>",
        // var c = e,
            m = urlPosition(i, o),
            u = "",
            p = new Array;
        for (var v in settingsObjectData[a].sensors) p.push(settingsObjectData[a].sensors[v]);
        var y = sortArrayByElement(p, "name");
        for (var v in y) {
            var E = y[v];
            if ("true" == E.popup) {
                var b = getSensorValue(_, E);
                u += "<tr><td><strong>" + E.name + ":</strong></td><td>" + b.value_full + "</td></tr>"
            }
        }
        var h = "<table>            <tr><td><strong>" + la.OBJECT + ":</strong></td><td>" + t + "</td></tr>         <tr><td><strong>" + la.ADDRESS + ":</strong></td><td>" + c + "</td></tr>            <tr><td><strong>" + la.POSITION + ":</strong></td><td>" + m + "</td></tr>           <tr><td><strong>" + la.ALTITUDE + ":</strong></td><td>" + s + " " + la.UNIT_HEIGHT + "</td></tr>            <tr><td><strong>" + la.ANGLE + ":</strong></td><td>" + n + " &deg;</td></tr>            <tr><td><strong>" + la.CAME + ":</strong></td><td>" + l + "</td></tr>           <tr><td><strong>" + la.LEFT + ":</strong></td><td>" + d + "</td></tr>           <tr><td><strong>" + la.DURATION + ":</strong></td><td>" + r + "</td></tr>",
            f = getObjectOdometer(a, _); - 1 != f && (h += "<tr><td><strong>" + la.ODOMETER + ":</strong></td><td>" + f + " " + la.UNIT_DISTANCE + "</td></tr>");
        var I = getObjectEngineHours(a, _); - 1 != I && (h += "<tr><td><strong>" + la.ENGINE_HOURS + ":</strong></td><td>" + I + "</td></tr>");
        var D = h + u;
        h += "</table>", D += "</table>", addPopupToMap(i, o, [0, -28 * g], h, D)
    // })
}

function historyRoutePanToEvent(e) {
    var t = historyRouteData.events[e].lng,
        a = historyRouteData.events[e].lat;
    map.panTo({
        lat: a,
        lng: t
    })
}

function historyRouteShowEvent(e) {
    historyRouteRemoveDrive();
    var t = historyRouteData.name,
        a = historyRouteData.imei,
        o = historyRouteData.events[e].event_desc,
        i = historyRouteData.events[e].dt_tracker,
        s = historyRouteData.events[e].lng,
        n = historyRouteData.events[e].lat,
        l = historyRouteData.events[e].altitude,
        d = historyRouteData.events[e].angle,
        r = historyRouteData.events[e].speed,
        _ = historyRouteData.events[e].params,
        c = historyRouteData.events[e];
    showExtraData("route", a, c);
    var g = settingsUserData.map_is;
    // geocoderGetAddress(n, s, function(e) {
        var c = "<div id='showobjectinfoAddress'><a onclick='showobjectaddress("+n+","+"\""+s+"\""+","+"\"showobjectinfoAddress\""+")'>View Address</a></div>",
        // var c = e,
            m = urlPosition(n, s),
            u = "",
            p = new Array;
        for (var v in settingsObjectData[a].sensors) p.push(settingsObjectData[a].sensors[v]);
        var y = sortArrayByElement(p, "name");
        for (var v in y) {
            var E = y[v];
            if ("true" == E.popup) {
                var b = getSensorValue(_, E);
                u += "<tr><td><strong>" + E.name + ":</strong></td><td>" + b.value_full + "</td></tr>"
            }
        }
        var h = "<table>            <tr><td><strong>" + la.OBJECT + ":</strong></td><td>" + t + "</td></tr>         <tr><td><strong>" + la.EVENT + ":</strong></td><td>" + o + "</td></tr>          <tr><td><strong>" + la.ADDRESS + ":</strong></td><td>" + c + "</td></tr>            <tr><td><strong>" + la.POSITION + ":</strong></td><td>" + m + "</td></tr>           <tr><td><strong>" + la.ALTITUDE + ":</strong></td><td>" + l + " " + la.UNIT_HEIGHT + "</td></tr>            <tr><td><strong>" + la.ANGLE + ":</strong></td><td>" + d + " &deg;</td></tr>            <tr><td><strong>" + la.SPEED + ":</strong></td><td>" + r + " " + la.UNIT_SPEED + "</td></tr>            <tr><td><strong>" + la.TIME + ":</strong></td><td>" + i + "</td></tr>",
            f = getObjectOdometer(a, _); - 1 != f && (h += "<tr><td><strong>" + la.ODOMETER + ":</strong></td><td>" + f + " " + la.UNIT_DISTANCE + "</td></tr>");
        var I = getObjectEngineHours(a, _); - 1 != I && (h += "<tr><td><strong>" + la.ENGINE_HOURS + ":</strong></td><td>" + I + "</td></tr>");
        var D = h + u;
        h += "</table>", D += "</table>", addPopupToMap(n, s, [0, -28 * g], h, D)
    // })
}

function historyRouteRemoveDrive() {
    historyRouteData.layers.route_drive && mapLayers.history.removeLayer(historyRouteData.layers.route_drive)
}

function historyRouteShowDrive(e) {
    historyRouteRemoveDrive();
    var t = historyRouteData.drives[e].id_start_s,
        a = historyRouteData.drives[e].id_end,
        o = new Array;
    for (i = 0; i <= a - t; i++) {
        var s = historyRouteData.route[t + i].lat,
            n = historyRouteData.route[t + i].lng;
        o.push(L.latLng(s, n))
    }
    var l = L.polyline(o, {
        color: settingsUserData.map_rhc,
        opacity: .8,
        weight: 3
    });
    if (mapLayers.history.addLayer(l), 0 == historyRouteData.play.status) {
        var d = l.getBounds();
        map.fitBounds(d)
    }
    historyRouteData.layers.route_drive = l
}

function historyRouteMsgDeleteSelected() {
    if (utilsCheckPrivileges("viewer") && utilsCheckPrivileges("subuser") && utilsCheckPrivileges("obj_history_clear")) {
        var e = $("#bottom_panel_msg_list_grid").jqGrid("getGridParam", "selarrrow");
        return "" == e ? void notifyBox("error", la.ERROR, la.NO_ITEMS_SELECTED) : void confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE_SELECTED_ITEMS, function(t) {
            if (t) {
                var a = {
                    cmd: "delete_selected_msgs",
                    imei: historyRouteData.imei,
                    items: e
                };
                $.ajax({
                    type: "POST",
                    url: "func/fn_history.php",
                    data: a,
                    success: function(e) {
                        "OK" == e && $("#bottom_panel_msg_list_grid").trigger("reloadGrid")
                    }
                })
            }
        })
    }
}   

function generatereport() {
    utilsCheckPrivileges("reports") && $("#dialog_reports").dialog("open")
}
function reportsOpen() {
    utilsCheckPrivileges("reports") && $("#dialog_report_properties").dialog("open")
}

function reportsReload() {
    reportsLoadData(), $("#report_list_grid").trigger("reloadGrid")
}

function reportsLoadData() {
    var e = {
        cmd: "load_report_data"
    };
    $.ajax({
        type: "POST",
        url: "func/fn_reports.php",
        data: e,
        dataType: "json",
        cache: !1,
        success: function(e) {
            reportsData.reports = e
        }
    })
}

function reportProperties(e) {
    switch (e) {
        default: var t = e;reportsData.edit_report_id = t,
        document.getElementById("dialog_report_name").value = reportsData.reports[t].name,
        document.getElementById("dialog_report_type").value = reportsData.reports[t].type,
        reportsSwitchType(),
        document.getElementById("dialog_report_format").value = reportsData.reports[t].format,
        document.getElementById("dialog_report_show_coordinates").checked = strToBoolean(reportsData.reports[t].show_coordinates),
        document.getElementById("dialog_report_show_addresses").checked = strToBoolean(reportsData.reports[t].show_addresses),
        document.getElementById("dialog_report_zones_addresses").checked = strToBoolean(reportsData.reports[t].zones_addresses),
        document.getElementById("dialog_report_stop_duration").value = reportsData.reports[t].stop_duration,
        document.getElementById("dialog_report_speed_limit").value = reportsData.reports[t].speed_limit;
        var a = document.getElementById("dialog_report_object_list"),
            o = reportsData.reports[t].imei.split(",");multiselectSetValues(a, o);
        var i = document.getElementById("dialog_report_zone_list"),
            s = reportsData.reports[t].zone_ids.split(",");multiselectSetValues(i, s);
        var iv = document.getElementById("dialog_report_zone_list_second"),
            sv = reportsData.reports[t].zone_idsv.split(",");multiselectSetValues(iv, sv);
        reportsListSensors();
        var n = document.getElementById("dialog_report_sensor_list"),
            l = reportsData.reports[t].sensor_names.split(",");multiselectSetValues(n, l),
        reportsListDataItems();
        var d = document.getElementById("dialog_report_data_item_list"),
            r = reportsData.reports[t].data_items.split(",");multiselectSetValues(d, r);
        reportsListEventList();
        var ev = document.getElementById("dialog_report_event_list_list"),
            er = reportsData.reports[t].event_list.split(",");multiselectSetValues(ev, er);
        var _ = reportsData.reports[t].schedule_period;
        "d" == _ ? (document.getElementById("dialog_report_schedule_period_daily").checked = !0, document.getElementById("dialog_report_schedule_period_weekly").checked = !1) : "w" == _ ? (document.getElementById("dialog_report_schedule_period_daily").checked = !1, document.getElementById("dialog_report_schedule_period_weekly").checked = !0) : "dw" == _ ? (document.getElementById("dialog_report_schedule_period_daily").checked = !0, document.getElementById("dialog_report_schedule_period_weekly").checked = !0) : (document.getElementById("dialog_report_schedule_period_daily").checked = !1, document.getElementById("dialog_report_schedule_period_weekly").checked = !1),
        document.getElementById("dialog_report_schedule_email_address").value = reportsData.reports[t].schedule_email_address,
        document.getElementById("dialog_report_filter").value = 2,
        switchHistoryReportsDateFilter("report"),
		$("#dialog_reports").dialog("close");
        break;
        case "add":
                reportsData.edit_report_id = !1,
            document.getElementById("dialog_report_name").value = "",
            document.getElementById("dialog_report_type").value = "general",
            reportsSwitchType(),
            document.getElementById("dialog_report_format").value = "html",
            document.getElementById("dialog_report_show_coordinates").checked = !0,
            document.getElementById("dialog_report_show_addresses").checked = !1,
            document.getElementById("dialog_report_zones_addresses").checked = !1,
            document.getElementById("dialog_report_stop_duration").value = 1,
            document.getElementById("dialog_report_speed_limit").value = "",
            document.getElementById("dialog_report_maxspeed_limit").value="",
            $("#dialog_report_object_list option:selected").removeAttr("selected"),
            $("#dialog_report_zone_list option:selected").removeAttr("selected"),
            $("#dialog_report_zone_list_second option:selected").removeAttr("selected"),
            reportsListSensors(),
            $("#dialog_report_sensor_list option:selected").removeAttr("selected"),
            reportsListDataItems(),
            $("#dialog_report_data_items_list option:selected").removeAttr("selected"),
            reportsListEventList(),
            $("#dialog_report_event_list_list option:selected").removeAttr("selected"),
            document.getElementById("dialog_report_schedule_period_daily").checked = !1,
            document.getElementById("dialog_report_schedule_period_weekly").checked = !1,
            document.getElementById("dialog_report_schedule_email_address").value = "",
            document.getElementById("dialog_report_filter").value = 2,
            switchHistoryReportsDateFilter("report"),
            initSelectList("report_object_list"),
            $("#dialog_report_properties").dialog("open");
            break;
        case "cancel":
                reportsData.edit_report_id = !1,
				document.getElementById("dialog_report_name").value = "",
				document.getElementById("dialog_report_type").value = "general",
				reportsSwitchType(),
				document.getElementById("dialog_report_format").value = "html",
				document.getElementById("dialog_report_show_coordinates").checked = !0,
				document.getElementById("dialog_report_show_addresses").checked = !1,
				document.getElementById("dialog_report_zones_addresses").checked = !1,
				document.getElementById("dialog_report_stop_duration").value = 1,
				document.getElementById("dialog_report_speed_limit").value = "",
                document.getElementById("dialog_report_maxspeed_limit").value="",
				$("#dialog_report_object_list option:selected").removeAttr("selected"),
				$("#dialog_report_zone_list option:selected").removeAttr("selected"),
				$("#dialog_report_zone_list_second option:selected").removeAttr("selected"),
				reportsListSensors(),
				$("#dialog_report_sensor_list option:selected").removeAttr("selected"),
				reportsListDataItems(),
				$("#dialog_report_data_items_list option:selected").removeAttr("selected"),
                reportsListEventList(),
                $("#dialog_report_event_list_list option:selected").removeAttr("selected"),
				document.getElementById("dialog_report_schedule_period_daily").checked = !1,
				document.getElementById("dialog_report_schedule_period_weekly").checked = !1,
				document.getElementById("dialog_report_schedule_email_address").value = "",
				document.getElementById("dialog_report_filter").value = 2,
				switchHistoryReportsDateFilter("report"),
				initSelectList("report_object_list");
            break;
        case "save":
                if (!utilsCheckPrivileges("viewer")) return;
            var c = document.getElementById("dialog_report_name").value,
                g = document.getElementById("dialog_report_type").value,
                m = document.getElementById("dialog_report_format").value,
                u = document.getElementById("dialog_report_show_coordinates").checked,
                p = document.getElementById("dialog_report_show_addresses").checked,
                v = document.getElementById("dialog_report_zones_addresses").checked,
                y = document.getElementById("dialog_report_stop_duration").value,
                E = document.getElementById("dialog_report_speed_limit").value,
                ME=document.getElementById("dialog_report_maxspeed_limit").value,
                b = multiselectGetValues(document.getElementById("dialog_report_object_list")),
                h = multiselectGetValues(document.getElementById("dialog_report_zone_list")),
                hv = multiselectGetValues(document.getElementById("dialog_report_zone_list_second")),
                f = multiselectGetValues(document.getElementById("dialog_report_sensor_list")),
                d = multiselectGetValues(document.getElementById("dialog_report_data_item_list"));
                ev = multiselectGetValues(document.getElementById("dialog_report_event_list_list"));
            if ("" == c) return void notifyBox("error", la.ERROR, la.NAME_CANT_BE_EMPTY);
            if (("overspeed" == g || "underspeed" == g) && "" == E) return void notifyBox("error", la.ERROR, la.SPEED_LIMIT_CANT_BE_EMPTY);
            if ("" == b) return void notifyBox("error", la.ERROR, la.AT_LEAST_ONE_OBJECT_SELECTED);
            if ("zone_in_out" == g && "" == h) return void notifyBox("error", la.ERROR, la.AT_LEAST_ONE_ZONE_SELECTED);
            if (("logic_sensors" == g || "sensor_graph" == g) && "" == f) return void notifyBox("error", la.ERROR, la.AT_LEAST_ONE_SENSOR_SELECTED);
            var I = document.getElementById("dialog_report_schedule_period_daily").checked,
                D = document.getElementById("dialog_report_schedule_period_weekly").checked,
                B = document.getElementById("dialog_report_schedule_email_address").value,
                _ = "";
            if (1 == I && (_ = "d"), 1 == D && (_ += "w"), "" != _)
                for (var O = B.split(","), j = 0; j < O.length; j++)
                    if (O[j] = O[j].trim(), !isEmailValid(O[j])) return void notifyBox("error", la.ERROR, la.THIS_EMAIL_IS_NOT_VALID);
            var R = {
                cmd: "save_report",
                report_id: reportsData.edit_report_id,
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
                event_list: ev,
                schedule_period: _,
                schedule_email_address: B
            };$.ajax({
                type: "POST",
                url: "func/fn_reports.php",
                data: R,
                cache: !1,
                success: function(e) {
                    "OK" == e && (reportsReload(), $("#dialog_report_properties").dialog("close"), notifyBox("info", la.INFORMATION, la.CHANGES_SAVED_SUCCESSFULLY))
                    if(e=='OK'){
                        reportsData.edit_report_id = !1,
                        document.getElementById("dialog_report_name").value = "",
                        document.getElementById("dialog_report_type").value = "general",
                        reportsSwitchType(),
                        document.getElementById("dialog_report_format").value = "html",
                        document.getElementById("dialog_report_show_coordinates").checked = !0,
                        document.getElementById("dialog_report_show_addresses").checked = !1,
                        document.getElementById("dialog_report_zones_addresses").checked = !1,
                        document.getElementById("dialog_report_stop_duration").value = 1,
                        document.getElementById("dialog_report_speed_limit").value = "",
                        document.getElementById("dialog_report_maxspeed_limit").value="",
                        $("#dialog_report_object_list option:selected").removeAttr("selected"),
                        $("#dialog_report_zone_list option:selected").removeAttr("selected"),
                        $("#dialog_report_zone_list_second option:selected").removeAttr("selected"),
                        reportsListSensors(),
                        $("#dialog_report_sensor_list option:selected").removeAttr("selected"),
                        reportsListDataItems(),
                        $("#dialog_report_data_items_list option:selected").removeAttr("selected"),
                        reportsListEventList(),
                        $("#dialog_report_event_list_list option:selected").removeAttr("selected"),
                        document.getElementById("dialog_report_schedule_period_daily").checked = !1,
                        document.getElementById("dialog_report_schedule_period_weekly").checked = !1,
                        document.getElementById("dialog_report_schedule_email_address").value = "",
                        document.getElementById("dialog_report_filter").value = 2,
                        switchHistoryReportsDateFilter("report"),
                        initSelectList("report_object_list");
                    }
                }
            });
            break;
        case "generate":
                var c = document.getElementById("dialog_report_name").value,
                g = document.getElementById("dialog_report_type").value,
                m = document.getElementById("dialog_report_format").value,
                u = document.getElementById("dialog_report_show_coordinates").checked,
                p = document.getElementById("dialog_report_show_addresses").checked;
                if(g=='events' || g=='overspeed'){
                    p=false;
                }
                var v = document.getElementById("dialog_report_zones_addresses").checked,                
                y = document.getElementById("dialog_report_stop_duration").value,
                E = document.getElementById("dialog_report_speed_limit").value,
                ME = document.getElementById("dialog_report_maxspeed_limit").value,
                b = multiselectGetValues(document.getElementById("dialog_report_object_list")),
                h = multiselectGetValues(document.getElementById("dialog_report_zone_list")),
                hv = multiselectGetValues(document.getElementById("dialog_report_zone_list_second")),
                f = multiselectGetValues(document.getElementById("dialog_report_sensor_list")),
                d = multiselectGetValues(document.getElementById("dialog_report_data_item_list")),
                ev = multiselectGetValues(document.getElementById("dialog_report_event_list_list")),
                T = $("#dialog_report_date_from").val() + " " + $("#dialog_report_hour_from").val() + ":" + $("#dialog_report_minute_from").val() + ":00",
                k = $("#dialog_report_date_to").val() + " " + $("#dialog_report_hour_to").val() + ":" + $("#dialog_report_minute_to").val() + ":00";
            "" == c && (c = document.getElementById("dialog_report_type").options[document.getElementById("dialog_report_type").selectedIndex].text);
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
                max_speed:ME,
                imei: b,
                zone_ids: h,
                zone_idsv: hv,
                sensor_names: f,
                data_items: d,
                dtf: T,
                dtt: k,
                rpt_type:"G",
                event_list:ev
            };reportGenerate(R)
    }
}

function reportsDelete(e) {
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
                    "OK" == e && reportsReload()
                }
            })
        }
    })
}

function reportsDeleteSelected() {
    if (utilsCheckPrivileges("viewer")) {
        var e = $("#report_list_grid").jqGrid("getGridParam", "selarrrow");
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
                        "OK" == e && reportsReload()
                    }
                })
            }
        })
    }
}

function reportGenerate(e) {
    // e.type == "ifsinfograph" ||
	
	 if(( e.type == "ifsinfograph_raw" || e.type=="fuelgraph" || e.type=="fuelfillings" || e.type=="fuelthefts") && e.imei.split(',').length>10) {
	       notifyBox("error", la.ERROR, la.PLEASE_SELECT_WITHIN_10);
	       return
	}
	return "overspeed" != e.type && "underspeed" != e.type && "rag" != e.type || "" != e.speed_limit ? "master_lisid" != e.type && "emergencyalert" != e.type && "stisgeneralreport" != e.type && "maintenancereport" != e.type  && "" == e.imei ? void notifyBox("error", la.ERROR, la.AT_LEAST_ONE_OBJECT_SELECTED) : ("zone_in_out" == e.type  || "roundtripreport" == e.type ) && "" == e.zone_ids ? void notifyBox("error", la.ERROR, la.AT_LEAST_ONE_ZONE_SELECTED) : "logic_sensors" != e.type && "sensor_graph" != e.type || "" != e.sensor_names ? (loadingData(!0), void $.ajax({
        type: "POST",
        url: "func/fn_reports.gen.php",
        data: e,
        cache: !1,
        success: function(t) {
            loadingData(!1), $.generateFile({
                filename: e.type + "_" + e.dtf + "_" + e.dtt,
                content: t,
                script: "func/fn_saveas.php?format=" + e.format
            }), reportsGeneratedReload()
        },
        error: function() {
            loadingData(!1)
        }
    })) : void notifyBox("error", la.ERROR, la.AT_LEAST_ONE_SENSOR_SELECTED) : void notifyBox("error", la.ERROR, la.SPEED_LIMIT_CANT_BE_EMPTY)
}

function reportsSelectObject() {
    reportsListSensors()
}

function reportsListDataItems() {
    var e = document.getElementById("dialog_report_type").value;
    if (void 0 != reportsData.data_items[e]) {
        var t = reportsData.data_items[e],
            a = document.getElementById("dialog_report_data_item_list");
        a.options.length = 0;
        for (var o = 0; o < t.length; o++) {
            var i = t[o].toUpperCase();
            i = la[i];
            var s = t[o];
            a.options.add(new Option(i, s))
        }
    }
    $("#dialog_report_data_item_list option").prop("selected", !0)
}

function reportsListEventList() {
    var e = document.getElementById("dialog_report_type").value;
    if (void 0 != reportsData.event_list[e]){
        var t = reportsData.event_list[e],
            a = document.getElementById("dialog_report_event_list_list");
        a.options.length = 0;
        for (var o = 0; o < t.length; o++){
            var i = t[o+1].toUpperCase();
            i = la[i];
            var s = t[o];
            a.options.add(new Option(i, s))
            o++;
        }
        $("#dialog_report_event_list_list").prop("disabled", !1);
        $("#dialog_report_event_list_list option").prop("selected", !0);
    }else{
        $('#dialog_report_event_list_list').find('option').remove();
        $("#dialog_report_event_list_list").prop("disabled", !0); 
        $("#dialog_report_event_list_list option").prop("selected", !1);
    }
    
}

function reportsListSensors() {
    var e = document.getElementById("dialog_report_sensor_list");
    e.options.length = 0;
    var t = document.getElementById("dialog_report_type").value;
    if ("logic_sensors" == t || "sensor_graph" == t) {
        for (var a = document.getElementById("dialog_report_object_list"), o = new Array, i = 0; i < a.options.length; i++)
            if (a.options[i].selected) {
                var s = a.options[i].value,
                    n = settingsObjectData[s].sensors;
                for (var l in n) {
                    var d = n[l];
                    "string" != d.result_type && ("logic_sensors" == t ? "logic" == d.result_type && o.push(d.name) : "sensor_graph" == t && o.push(d.name))
                }
            }
        o = uniqueArray(o);
        for (var i = 0; i < o.length; i++) e.options.add(new Option(o[i], o[i]));
        sortSelectList(e)    }
}

function reportsSwitchType() {
    var e = document.getElementById("dialog_report_type").value,
        t = document.getElementById("dialog_report_format");
        document.getElementById("dialog_report_maxspeed_limit").value=0;
    switch (t.options.length = 0, "speed_graph" != e && "altitude_graph" != e && "acc_graph" != e && "fuellevel_graph" != e && "temperature_graph" != e && "sensor_graph" != e ? (t.options.add(new Option("HTML", "html")), t.options.add(new Option("PDF", "pdf")), t.options.add(new Option("XLS", "xls"))) : t.options.add(new Option("HTML", "html")), e) {
        case "general":
            document.getElementById("dialog_report_zone_list").disabled = !0,document.getElementById("dialog_report_zone_list_second").disabled = !0, $("#dialog_report_zone_list option:selected").removeAttr("selected"), document.getElementById("dialog_report_sensor_list").disabled = !0, $("#dialog_report_sensor_list option:selected").removeAttr("selected"), document.getElementById("dialog_report_speed_limit").disabled = !1,document.getElementById("dialog_report_maxspeed_limit").style.display='none', document.getElementById("dialog_report_stop_duration").disabled = !1, document.getElementById("dialog_report_show_coordinates").disabled = !0, document.getElementById("dialog_report_show_addresses").disabled = !0, document.getElementById("dialog_report_zones_addresses").disabled = !0;
            break;
        case "general_merged":
            document.getElementById("dialog_report_zone_list").disabled = !0,document.getElementById("dialog_report_zone_list_second").disabled = !0, $("#dialog_report_zone_list option:selected").removeAttr("selected"), document.getElementById("dialog_report_sensor_list").disabled = !0, $("#dialog_report_sensor_list option:selected").removeAttr("selected"), document.getElementById("dialog_report_speed_limit").disabled = !1,document.getElementById("dialog_report_maxspeed_limit").style.display='none', document.getElementById("dialog_report_stop_duration").disabled = !1, document.getElementById("dialog_report_show_coordinates").disabled = !0, document.getElementById("dialog_report_show_addresses").disabled = !0, document.getElementById("dialog_report_zones_addresses").disabled = !0;
            break;
        case "daily_km":
            document.getElementById("dialog_report_zone_list").disabled = !0,document.getElementById("dialog_report_zone_list_second").disabled = !0, $("#dialog_report_zone_list option:selected").removeAttr("selected"), document.getElementById("dialog_report_sensor_list").disabled = !0, $("#dialog_report_sensor_list option:selected").removeAttr("selected"), document.getElementById("dialog_report_speed_limit").disabled = !1,document.getElementById("dialog_report_maxspeed_limit").style.display='none', document.getElementById("dialog_report_stop_duration").disabled = !1, document.getElementById("dialog_report_show_coordinates").disabled = !0, document.getElementById("dialog_report_show_addresses").disabled = !0, document.getElementById("dialog_report_zones_addresses").disabled = !0;
            break;
        case "object_info":
            document.getElementById("dialog_report_zone_list").disabled = !0,document.getElementById("dialog_report_zone_list_second").disabled = !0, $("#dialog_report_zone_list option:selected").removeAttr("selected"), document.getElementById("dialog_report_sensor_list").disabled = !0, $("#dialog_report_sensor_list option:selected").removeAttr("selected"), document.getElementById("dialog_report_speed_limit").disabled = !0,document.getElementById("dialog_report_maxspeed_limit").style.display='none', document.getElementById("dialog_report_stop_duration").disabled = !0, document.getElementById("dialog_report_show_coordinates").disabled = !0, document.getElementById("dialog_report_show_addresses").disabled = !0, document.getElementById("dialog_report_zones_addresses").disabled = !0;
            break;
        case "current_position":
            document.getElementById("dialog_report_zone_list").disabled = !0,document.getElementById("dialog_report_zone_list_second").disabled = !0, $("#dialog_report_zone_list option:selected").removeAttr("selected"), document.getElementById("dialog_report_sensor_list").disabled = !0, $("#dialog_report_sensor_list option:selected").removeAttr("selected"), document.getElementById("dialog_report_speed_limit").disabled = !0,document.getElementById("dialog_report_maxspeed_limit").style.display='none', document.getElementById("dialog_report_stop_duration").disabled = !0, document.getElementById("dialog_report_show_coordinates").disabled = !1, document.getElementById("dialog_report_show_addresses").disabled = !1, document.getElementById("dialog_report_zones_addresses").disabled = !1;
            break;
        case "drives_stops":
            document.getElementById("dialog_report_zone_list").disabled = !0,document.getElementById("dialog_report_zone_list_second").disabled = !0, $("#dialog_report_zone_list option:selected").removeAttr("selected"), document.getElementById("dialog_report_sensor_list").disabled = !0, $("#dialog_report_sensor_list option:selected").removeAttr("selected"), document.getElementById("dialog_report_speed_limit").disabled = !0,document.getElementById("dialog_report_maxspeed_limit").style.display='none', document.getElementById("dialog_report_speed_limit").value = "", document.getElementById("dialog_report_stop_duration").disabled = !1, document.getElementById("dialog_report_show_coordinates").disabled = !1, document.getElementById("dialog_report_show_addresses").disabled = !1, document.getElementById("dialog_report_zones_addresses").disabled = !1;
            break;
        case "travel_sheet":
            document.getElementById("dialog_report_zone_list").disabled = !0,document.getElementById("dialog_report_zone_list_second").disabled = !0, $("#dialog_report_zone_list option:selected").removeAttr("selected"), document.getElementById("dialog_report_sensor_list").disabled = !0, $("#dialog_report_sensor_list option:selected").removeAttr("selected"), document.getElementById("dialog_report_speed_limit").disabled = !0,document.getElementById("dialog_report_maxspeed_limit").style.display='none', document.getElementById("dialog_report_speed_limit").value = "", document.getElementById("dialog_report_stop_duration").disabled = !1, document.getElementById("dialog_report_show_coordinates").disabled = !1, document.getElementById("dialog_report_show_addresses").disabled = !1, document.getElementById("dialog_report_zones_addresses").disabled = !1;
            break;
       
        case "overspeed":
            document.getElementById("dialog_report_zone_list").disabled = !0,document.getElementById("dialog_report_zone_list_second").disabled = !0, $("#dialog_report_zone_list option:selected").removeAttr("selected"), document.getElementById("dialog_report_sensor_list").disabled = !0, $("#dialog_report_sensor_list option:selected").removeAttr("selected"), document.getElementById("dialog_report_speed_limit").disabled = !1,document.getElementById("dialog_report_maxspeed_limit").style.display='block', document.getElementById("dialog_report_stop_duration").disabled = !0, document.getElementById("dialog_report_show_coordinates").disabled = !1, document.getElementById("dialog_report_show_addresses").disabled = !1, document.getElementById("dialog_report_zones_addresses").disabled = !1;
            break;
        case "underspeed":
            document.getElementById("dialog_report_zone_list").disabled = !0,document.getElementById("dialog_report_zone_list_second").disabled = !0, $("#dialog_report_zone_list option:selected").removeAttr("selected"), document.getElementById("dialog_report_sensor_list").disabled = !0, $("#dialog_report_sensor_list option:selected").removeAttr("selected"), document.getElementById("dialog_report_speed_limit").disabled = !1,document.getElementById("dialog_report_maxspeed_limit").style.display='none', document.getElementById("dialog_report_stop_duration").disabled = !0, document.getElementById("dialog_report_show_coordinates").disabled = !1, document.getElementById("dialog_report_show_addresses").disabled = !1, document.getElementById("dialog_report_zones_addresses").disabled = !1;
            break;
        case "zone_in_out":
            document.getElementById("dialog_report_zone_list").disabled = !1,document.getElementById("dialog_report_zone_list_second").disabled = !0, document.getElementById("dialog_report_sensor_list").disabled = !0, $("#dialog_report_sensor_list option:selected").removeAttr("selected"), document.getElementById("dialog_report_speed_limit").disabled = !0, document.getElementById("dialog_report_speed_limit").value = "",document.getElementById("dialog_report_maxspeed_limit").style.display='none', document.getElementById("dialog_report_stop_duration").disabled = !0, document.getElementById("dialog_report_show_coordinates").disabled = !1, document.getElementById("dialog_report_show_addresses").disabled = !1, document.getElementById("dialog_report_zones_addresses").disabled = !0;
            break;
        case "events":
            document.getElementById("dialog_report_zone_list").disabled = !0,document.getElementById("dialog_report_zone_list_second").disabled = !0, $("#dialog_report_zone_list option:selected").removeAttr("selected"), document.getElementById("dialog_report_sensor_list").disabled = !0, $("#dialog_report_sensor_list option:selected").removeAttr("selected"), document.getElementById("dialog_report_speed_limit").disabled = !0,document.getElementById("dialog_report_maxspeed_limit").style.display='none', document.getElementById("dialog_report_speed_limit").value = "", document.getElementById("dialog_report_stop_duration").disabled = !0, document.getElementById("dialog_report_show_coordinates").disabled = !1, document.getElementById("dialog_report_show_addresses").disabled = !1, document.getElementById("dialog_report_zones_addresses").disabled = !1;
            break;
        case "service":
            document.getElementById("dialog_report_zone_list").disabled = !0,document.getElementById("dialog_report_zone_list_second").disabled = !0, $("#dialog_report_zone_list option:selected").removeAttr("selected"), document.getElementById("dialog_report_sensor_list").disabled = !0, $("#dialog_report_sensor_list option:selected").removeAttr("selected"), document.getElementById("dialog_report_speed_limit").disabled = !0,document.getElementById("dialog_report_maxspeed_limit").style.display='none', document.getElementById("dialog_report_speed_limit").value = "", document.getElementById("dialog_report_stop_duration").disabled = !0, document.getElementById("dialog_report_show_coordinates").disabled = !0, document.getElementById("dialog_report_show_addresses").disabled = !0, document.getElementById("dialog_report_zones_addresses").disabled = !0;
            break;
        case "rag":
            document.getElementById("dialog_report_zone_list").disabled = !0,document.getElementById("dialog_report_zone_list_second").disabled = !0, $("#dialog_report_zone_list option:selected").removeAttr("selected"), document.getElementById("dialog_report_sensor_list").disabled = !0, $("#dialog_report_sensor_list option:selected").removeAttr("selected"), document.getElementById("dialog_report_speed_limit").disabled = !1,document.getElementById("dialog_report_maxspeed_limit").style.display='none', document.getElementById("dialog_report_stop_duration").disabled = !0, document.getElementById("dialog_report_show_coordinates").disabled = !0, document.getElementById("dialog_report_show_addresses").disabled = !0, document.getElementById("dialog_report_zones_addresses").disabled = !0;
            break;
        case "detailed_driver_behavior":
            document.getElementById("dialog_report_zone_list").disabled = !0,document.getElementById("dialog_report_zone_list_second").disabled = !0, $("#dialog_report_zone_list option:selected").removeAttr("selected"), document.getElementById("dialog_report_sensor_list").disabled = !0, $("#dialog_report_sensor_list option:selected").removeAttr("selected"), document.getElementById("dialog_report_speed_limit").disabled = !1,document.getElementById("dialog_report_maxspeed_limit").style.display='none', document.getElementById("dialog_report_stop_duration").disabled = !0, document.getElementById("dialog_report_show_coordinates").disabled = !0, document.getElementById("dialog_report_show_addresses").disabled = !0, document.getElementById("dialog_report_zones_addresses").disabled = !0;
            break;
        case "fuelfillings":
            document.getElementById("dialog_report_zone_list").disabled = !0,document.getElementById("dialog_report_zone_list_second").disabled = !0, $("#dialog_report_zone_list option:selected").removeAttr("selected"), document.getElementById("dialog_report_sensor_list").disabled = !0, $("#dialog_report_sensor_list option:selected").removeAttr("selected"), document.getElementById("dialog_report_speed_limit").disabled = !0,document.getElementById("dialog_report_maxspeed_limit").style.display='none', document.getElementById("dialog_report_speed_limit").value = "", document.getElementById("dialog_report_stop_duration").disabled = !0, document.getElementById("dialog_report_show_coordinates").disabled = !1, document.getElementById("dialog_report_show_addresses").disabled = !1, document.getElementById("dialog_report_zones_addresses").disabled = !1;
            break;
        case "fuelthefts":
            document.getElementById("dialog_report_zone_list").disabled = !0,document.getElementById("dialog_report_zone_list_second").disabled = !0, $("#dialog_report_zone_list option:selected").removeAttr("selected"), document.getElementById("dialog_report_sensor_list").disabled = !0, $("#dialog_report_sensor_list option:selected").removeAttr("selected"), document.getElementById("dialog_report_speed_limit").disabled = !0,document.getElementById("dialog_report_maxspeed_limit").style.display='none', document.getElementById("dialog_report_speed_limit").value = "", document.getElementById("dialog_report_stop_duration").disabled = !0, document.getElementById("dialog_report_show_coordinates").disabled = !1, document.getElementById("dialog_report_show_addresses").disabled = !1, document.getElementById("dialog_report_zones_addresses").disabled = !1;
            break;
        case "logic_sensors":
            document.getElementById("dialog_report_zone_list").disabled = !0,document.getElementById("dialog_report_zone_list_second").disabled = !0, $("#dialog_report_zone_list option:selected").removeAttr("selected"), document.getElementById("dialog_report_sensor_list").disabled = !1, document.getElementById("dialog_report_speed_limit").disabled = !0, document.getElementById("dialog_report_speed_limit").value = "",document.getElementById("dialog_report_maxspeed_limit").style.display='none', document.getElementById("dialog_report_stop_duration").disabled = !0, document.getElementById("dialog_report_show_coordinates").disabled = !1, document.getElementById("dialog_report_show_addresses").disabled = !1, document.getElementById("dialog_report_zones_addresses").disabled = !1;
            break;
        case "speed_graph":
            document.getElementById("dialog_report_zone_list").disabled = !0,document.getElementById("dialog_report_zone_list_second").disabled = !0, $("#dialog_report_zone_list option:selected").removeAttr("selected"), document.getElementById("dialog_report_sensor_list").disabled = !0, $("#dialog_report_sensor_list option:selected").removeAttr("selected"), document.getElementById("dialog_report_speed_limit").disabled = !0,document.getElementById("dialog_report_maxspeed_limit").style.display='none', document.getElementById("dialog_report_speed_limit").value = "", document.getElementById("dialog_report_stop_duration").disabled = !0, document.getElementById("dialog_report_show_coordinates").disabled = !0, document.getElementById("dialog_report_show_addresses").disabled = !0, document.getElementById("dialog_report_zones_addresses").disabled = !0;
            break;
        case "altitude_graph":
            document.getElementById("dialog_report_zone_list").disabled = !0,document.getElementById("dialog_report_zone_list_second").disabled = !0, $("#dialog_report_zone_list option:selected").removeAttr("selected"), document.getElementById("dialog_report_sensor_list").disabled = !0, $("#dialog_report_sensor_list option:selected").removeAttr("selected"), document.getElementById("dialog_report_speed_limit").disabled = !0,document.getElementById("dialog_report_maxspeed_limit").style.display='none', document.getElementById("dialog_report_speed_limit").value = "", document.getElementById("dialog_report_stop_duration").disabled = !0, document.getElementById("dialog_report_show_coordinates").disabled = !0, document.getElementById("dialog_report_show_addresses").disabled = !0, document.getElementById("dialog_report_zones_addresses").disabled = !0;
            break;
        case "acc_graph":
            document.getElementById("dialog_report_zone_list").disabled = !0,document.getElementById("dialog_report_zone_list_second").disabled = !0, $("#dialog_report_zone_list option:selected").removeAttr("selected"), document.getElementById("dialog_report_sensor_list").disabled = !0, $("#dialog_report_sensor_list option:selected").removeAttr("selected"), document.getElementById("dialog_report_speed_limit").disabled = !0,document.getElementById("dialog_report_maxspeed_limit").style.display='none', document.getElementById("dialog_report_speed_limit").value = "", document.getElementById("dialog_report_stop_duration").disabled = !0, document.getElementById("dialog_report_show_coordinates").disabled = !0, document.getElementById("dialog_report_show_addresses").disabled = !0, document.getElementById("dialog_report_zones_addresses").disabled = !0;
            break;
        case "fuellevel_graph":
            document.getElementById("dialog_report_zone_list").disabled = !0,document.getElementById("dialog_report_zone_list_second").disabled = !0, $("#dialog_report_zone_list option:selected").removeAttr("selected"), document.getElementById("dialog_report_sensor_list").disabled = !0, $("#dialog_report_sensor_list option:selected").removeAttr("selected"), document.getElementById("dialog_report_speed_limit").disabled = !0,document.getElementById("dialog_report_maxspeed_limit").style.display='none', document.getElementById("dialog_report_speed_limit").value = "", document.getElementById("dialog_report_stop_duration").disabled = !0, document.getElementById("dialog_report_show_coordinates").disabled = !0, document.getElementById("dialog_report_show_addresses").disabled = !0, document.getElementById("dialog_report_zones_addresses").disabled = !0;
            break;
        case "temperature_graph":
            document.getElementById("dialog_report_zone_list").disabled = !0,document.getElementById("dialog_report_zone_list_second").disabled = !0, $("#dialog_report_zone_list option:selected").removeAttr("selected"), document.getElementById("dialog_report_sensor_list").disabled = !0, $("#dialog_report_sensor_list option:selected").removeAttr("selected"), document.getElementById("dialog_report_speed_limit").disabled = !0,document.getElementById("dialog_report_maxspeed_limit").style.display='none', document.getElementById("dialog_report_speed_limit").value = "", document.getElementById("dialog_report_stop_duration").disabled = !0, document.getElementById("dialog_report_show_coordinates").disabled = !0, document.getElementById("dialog_report_show_addresses").disabled = !0, document.getElementById("dialog_report_zones_addresses").disabled = !0;
            break;
        case "sensor_graph":
            document.getElementById("dialog_report_zone_list").disabled = !0,document.getElementById("dialog_report_zone_list_second").disabled = !0, $("#dialog_report_zone_list option:selected").removeAttr("selected"), document.getElementById("dialog_report_sensor_list").disabled = !1, document.getElementById("dialog_report_speed_limit").disabled = !0, document.getElementById("dialog_report_speed_limit").value = "",document.getElementById("dialog_report_maxspeed_limit").style.display='none', document.getElementById("dialog_report_stop_duration").disabled = !0, document.getElementById("dialog_report_show_coordinates").disabled = !0, document.getElementById("dialog_report_show_addresses").disabled = !0, document.getElementById("dialog_report_zones_addresses").disabled = !0
            break;
        case "roundtripreport":
            document.getElementById("dialog_report_zone_list").disabled = !1,document.getElementById("dialog_report_zone_list_second").disabled = !1, $("#dialog_report_zone_list option:selected").removeAttr("selected"), document.getElementById("dialog_report_sensor_list").disabled = !0, $("#dialog_report_sensor_list option:selected").removeAttr("selected"), document.getElementById("dialog_report_speed_limit").disabled = !0,document.getElementById("dialog_report_maxspeed_limit").style.display='none', document.getElementById("dialog_report_speed_limit").value = "", document.getElementById("dialog_report_stop_duration").disabled = !0, document.getElementById("dialog_report_show_coordinates").disabled = !1, document.getElementById("dialog_report_show_addresses").disabled = !1, document.getElementById("dialog_report_zones_addresses").disabled = !1;
            break;
         default:
        	 document.getElementById("dialog_report_zone_list").disabled = !0,document.getElementById("dialog_report_zone_list_second").disabled = !0, $("#dialog_report_zone_list option:selected").removeAttr("selected"), document.getElementById("dialog_report_sensor_list").disabled = !0, $("#dialog_report_sensor_list option:selected").removeAttr("selected"), document.getElementById("dialog_report_speed_limit").disabled = !0,document.getElementById("dialog_report_maxspeed_limit").style.display='none', document.getElementById("dialog_report_stop_duration").disabled = !0, document.getElementById("dialog_report_show_coordinates").disabled = !1, document.getElementById("dialog_report_show_addresses").disabled = !1, document.getElementById("dialog_report_zones_addresses").disabled = !1;
        	 break;
    }
}

function reportsGeneratedReload() {
    $("#reports_generated_list_grid").trigger("reloadGrid")
}

function reportsGeneratedOpen(e) {
    loadingData(!0);
    var t = {
        cmd: "open_generated",
        report_id: e
    };
    $.ajax({
        type: "POST",
        url: "func/fn_reports.php",
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

function reportsGeneratedDelete(e) {
    utilsCheckPrivileges("viewer") && confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE, function(t) {
        if (t) {
            var a = {
                cmd: "delete_report_generated",
                report_id: e
            };
            $.ajax({
                type: "POST",
                url: "func/fn_reports.php",
                data: a,
                success: function(e) {
                    "OK" == e && reportsGeneratedReload()
                }
            })
        }
    })
}

function reportsGeneratedDeleteSelected() {
    if (utilsCheckPrivileges("viewer")) {
        var e = $("#reports_generated_list_grid").jqGrid("getGridParam", "selarrrow");
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
                        "OK" == e && reportsGeneratedReload()
                    }
                })
            }
        })
    }
}

function rilogbookOpen() {
    utilsCheckPrivileges("rilogbook") && ($("#dialog_rilogbook").bind("resize", function() {
        $("#rilogbook_logbook_grid").setGridHeight($("#dialog_rilogbook").height() - 133)
    }).trigger("resize"), $("#dialog_rilogbook").bind("resize", function() {
        $("#rilogbook_logbook_grid").setGridWidth($("#dialog_rilogbook").width())
    }).trigger("resize"), $("#dialog_rilogbook").dialog("open"))
}

function rilogbookClose() {
    $("#dialog_rilogbook").unbind("resize")
}

function rilogbookShow() {
    var e = "func/fn_rilogbook.php?cmd=load_rilogbook_list",
        t = document.getElementById("dialog_rilogbook_object_list").value,
        a = document.getElementById("dialog_rilogbook_drivers").checked,
        o = document.getElementById("dialog_rilogbook_passengers").checked,
        i = document.getElementById("dialog_rilogbook_trailers").checked,
        s = document.getElementById("dialog_rilogbook_date_from").value + " " + document.getElementById("dialog_rilogbook_hour_from").value + ":" + document.getElementById("dialog_rilogbook_minute_from").value + ":00",
        n = document.getElementById("dialog_rilogbook_date_to").value + " " + document.getElementById("dialog_rilogbook_hour_to").value + ":" + document.getElementById("dialog_rilogbook_minute_to").value + ":00";
    "" != t && (e += "&imei=" + t), e += "&drivers=" + a, e += "&passengers=" + o, e += "&trailers=" + i, s != n && (e += "&dtf=" + s + "&dtt=" + n), $("#rilogbook_logbook_grid").jqGrid("setGridParam", {
        url: e
    }).trigger("reloadGrid")
}

function rilogbookDelete(e) {
    utilsCheckPrivileges("viewer") && confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE, function(t) {
        if (t) {
            var a = {
                cmd: "delete_record",
                rilogbook_id: e
            };
            $.ajax({
                type: "POST",
                url: "func/fn_rilogbook.php",
                data: a,
                success: function(e) {
                    "OK" == e && rilogbookShow()
                }
            })
        }
    })
}

function rilogbookDeleteSelected() {
    if (utilsCheckPrivileges("viewer")) {
        var e = $("#rilogbook_logbook_grid").jqGrid("getGridParam", "selarrrow");
        return "" == e ? void notifyBox("error", la.ERROR, la.NO_ITEMS_SELECTED) : void confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE_SELECTED_ITEMS, function(t) {
            if (t) {
                var a = {
                    cmd: "delete_selected_records",
                    items: e
                };
                $.ajax({
                    type: "POST",
                    url: "func/fn_rilogbook.php",
                    data: a,
                    success: function(e) {
                        "OK" == e && rilogbookShow()
                    }
                })
            }
        })
    }
}

function rilogbookDeleteAll() {
    utilsCheckPrivileges("viewer") && confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE_ALL_LOGBOOK_RECORDS, function(e) {
        if (e) {
            var t = {
                cmd: "delete_all_records"
            };
            $.ajax({
                type: "POST",
                url: "func/fn_rilogbook.php",
                data: t,
                success: function(e) {
                    "OK" == e && rilogbookShow()
                }
            })
        }
    })
}

function rilogbookExportCSV() {
    var e = "func/fn_export.php?format=rilogbook_csv",
        t = document.getElementById("dialog_rilogbook_object_list").value,
        a = document.getElementById("dialog_rilogbook_drivers").checked,
        o = document.getElementById("dialog_rilogbook_passengers").checked,
        i = document.getElementById("dialog_rilogbook_trailers").checked,
        s = document.getElementById("dialog_rilogbook_date_from").value + " " + document.getElementById("dialog_rilogbook_hour_from").value + ":" + document.getElementById("dialog_rilogbook_minute_from").value + ":00",
        n = document.getElementById("dialog_rilogbook_date_to").value + " " + document.getElementById("dialog_rilogbook_hour_to").value + ":" + document.getElementById("dialog_rilogbook_minute_to").value + ":00";
    "" != t && (e += "&imei=" + t), e += "&drivers=" + a, e += "&passengers=" + o, e += "&trailers=" + i, s != n && (e += "&dtf=" + s + "&dtt=" + n), window.location = e
}

function notifyCheck(e) {
    switch (e) {
        case "expiring_objects":
            if (1 == gsValues.notify_obj_expire)
                for (var t in settingsObjectData) {
                    var a = settingsObjectData[t];
                    if ("true" == a.active && "true" == a.object_expire && getDateDifference(new Date(a.object_expire_dt), new Date) <= gsValues.notify_obj_expire_period) {
                        notifyBox("error", la.EXPIRING_OBJECTS, sprintf(la.SOME_OF_YOUR_OBJECTS_ACTIVATION_WILL_EXPIRE_SOON, "settingsOpen();"));
                        break
                    }
                }
            break;
        case "inactive_objects":
            if (1 == gsValues.notify_obj_expire)
                for (var t in settingsObjectData) {
                    var a = settingsObjectData[t];
                    if ("false" == a.active) {
                        notifyBox("error", la.INACTIVE_OBJECTS, sprintf(la.THERE_ARE_INACTIVE_OBJECTS_IN_YOUR_ACCOUNT, "settingsOpen();"));
                        break
                    }
                }
            break;
        case "session_check":
            if (0 == gsValues.session_check) break;
            clearTimeout(timer_sessionCheck);
            var o = {
                cmd: "session_check"
            };
            $.ajax({
                type: "POST",
                url: "func/fn_connect.php",
                data: o,
                cache: !1,
                error: function() {
                    timer_sessionCheck = setTimeout("notifyCheck('session_check');", 1e3 * gsValues.session_check)
                },
                success: function(e) {
                    "false" == e ? $("#blocking_panel").show() : timer_sessionCheck = setTimeout("notifyCheck('session_check');", 1e3 * gsValues.session_check)
                }
            })
    }
}

function placesSetListCheckbox(e, t) {
    null != document.getElementById(e) && (document.getElementById(e).checked = t)
}

function placesGroupOpen() {
    utilsCheckPrivileges("viewer") && utilsCheckPrivileges("subuser") && ($("#places_group_list_grid").trigger("reloadGrid"), $("#dialog_places_groups").dialog("open"))
}

function placesGroupClose() {
    placesMarkerReload(), placesRouteReload(), placesZoneReload()
}

function placesGroupReload() {
    placesGroupLoadData(), $("#places_group_list_grid").trigger("reloadGrid")
}

function placesGroupLoadData() {
    var e = {
        cmd: "load_place_group_data"
    };
    $.ajax({
        type: "POST",
        url: "func/fn_places.php",
        data: e,
        dataType: "json",
        cache: !1,
        success: function(e) {
            placesGroupData.groups = e, placesGroupData.edit_group_id = !1, initSelectList("places_group_list");
            for (var t in placesGroupData.groups) null != document.getElementById("marker_group_name_" + t) && (document.getElementById("marker_group_name_" + t).innerHTML = placesGroupData.groups[t].name), null != document.getElementById("route_group_name_" + t) && (document.getElementById("route_group_name_" + t).innerHTML = placesGroupData.groups[t].name), null != document.getElementById("zone_group_name_" + t) && (document.getElementById("zone_group_name_" + t).innerHTML = placesGroupData.groups[t].name)
        }
    })
}

function placesGroupDelete(e) {
    utilsCheckPrivileges("viewer") && confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE, function(t) {
        if (t) {
            var a = {
                cmd: "delete_place_group",
                group_id: e
            };
            $.ajax({
                type: "POST",
                url: "func/fn_places.php",
                data: a,
                success: function(e) {
                    "OK" == e && placesGroupReload()
                }
            })
        }
    })
}

function placesGroupDeleteSelected() {
    if (utilsCheckPrivileges("viewer")) {
        var e = $("#places_group_list_grid").jqGrid("getGridParam", "selarrrow");
        return "" == e ? void notifyBox("error", la.ERROR, la.NO_ITEMS_SELECTED) : void confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE_SELECTED_ITEMS, function(t) {
            if (t) {
                var a = {
                    cmd: "delete_selected_place_groups",
                    items: e
                };
                $.ajax({
                    type: "POST",
                    url: "func/fn_places.php",
                    data: a,
                    success: function(e) {
                        "OK" == e && placesGroupReload()
                    }
                })
            }
        })
    }
}

function placesGroupProperties(e) {
    switch (e) {
        default: var t = e;placesGroupData.edit_group_id = t,
        document.getElementById("dialog_places_group_name").value = placesGroupData.groups[t].name,
        document.getElementById("dialog_places_group_desc").value = placesGroupData.groups[t].desc,
        $("#dialog_places_group_properties").dialog("open");
        break;
        case "add":
                placesGroupData.edit_group_id = !1,
            document.getElementById("dialog_places_group_name").value = "",
            document.getElementById("dialog_places_group_desc").value = "",
            $("#dialog_places_group_properties").dialog("open");
            break;
        case "cancel":
                $("#dialog_places_group_properties").dialog("close");
            break;
        case "save":
                if (!utilsCheckPrivileges("viewer")) return;
            var a = document.getElementById("dialog_places_group_name").value,
                o = document.getElementById("dialog_places_group_desc").value;
            if ("" == a) {
                notifyBox("error", la.ERROR, la.NAME_CANT_BE_EMPTY);
                break
            }
            var i = {
                cmd: "save_place_group",
                group_id: placesGroupData.edit_group_id,
                group_name: a,
                group_desc: o
            };$.ajax({
                type: "POST",
                url: "func/fn_places.php",
                data: i,
                cache: !1,
                success: function(e) {
                    "OK" == e && (placesGroupReload(), $("#dialog_places_group_properties").dialog("close"), notifyBox("info", la.INFORMATION, la.CHANGES_SAVED_SUCCESSFULLY))
                }
            })
    }
}

function placesGroupImport() {
    utilsCheckPrivileges("viewer") && utilsCheckPrivileges("subuser") && 1 != gsValues.map_bussy && (document.getElementById("load_file").addEventListener("change", placesGroupImportPGRFile, !1), document.getElementById("load_file").click())
}

function placesGroupImportPGRFile(e) {
    var t = e.target.files,
        a = new FileReader;
    a.onload = function(e) {
        try {
            var t = $.parseJSON(e.target.result);
            if ("0.1v" == t.pgr) {
                var a = t.groups.length;
                if (0 == a) return void notifyBox("info", la.INFORMATION, la.NOTHING_HAS_BEEN_FOUND_TO_IMPORT);
                var o = sprintf(la.GROUPS_FOUND, a) + " " + la.ARE_YOU_SURE_YOU_WANT_TO_IMPORT;
                confirmDialog(o, function(t) {
                    if (t) {
                        loadingData(!0);
                        var a = {
                            format: "pgr",
                            data: e.target.result
                        };
                        $.ajax({
                            type: "POST",
                            url: "func/fn_import.php",
                            data: a,
                            cache: !1,
                            success: function(e) {
                                loadingData(!1), "OK" == e && placesGroupReload()
                            },
                            error: function() {
                                loadingData(!1)
                            }
                        })
                    }
                })
            } else notifyBox("error", la.ERROR, la.INVALID_FILE_FORMAT)
        } catch (i) {
            notifyBox("error", la.ERROR, la.INVALID_FILE_FORMAT)
        }
        document.getElementById("load_file").value = ""
    }, a.readAsText(t[0], "UTF-8"), this.removeEventListener("change", settingsObjectGroupImportOGRFile, !1)
}

function placesGroupExport() {
    if (utilsCheckPrivileges("viewer") && utilsCheckPrivileges("subuser") && 1 != gsValues.map_bussy) {
        var e = "func/fn_export.php?format=pgr";
        window.location = e
    }
}

function placesImport() {
    utilsCheckPrivileges("viewer") && utilsCheckPrivileges("subuser") && 1 != gsValues.map_bussy && (document.getElementById("load_file").addEventListener("change", placesImportPLCFile, !1), document.getElementById("load_file").click())
}

function placesImportPLCFile(e) {
    var t = e.target.files,
        a = new FileReader;
    a.onload = function(e) {
        try {
            if ("kml" == t[0].name.split(".").pop().toLowerCase()) var a = new X2JS,
                o = a.xml_str2json(e.target.result),
                i = placesKMLToPLC(o),
                s = JSON.stringify(i);
            else var i = $.parseJSON(e.target.result),
                s = e.target.result;
            if ("0.1v" == i.plc) {
                var n = i.markers.length,
                    l = i.routes.length,
                    d = i.zones.length;
                if (0 == n && 0 == l && 0 == d) return void notifyBox("info", la.INFORMATION, la.NOTHING_HAS_BEEN_FOUND_TO_IMPORT);
                var r = sprintf(la.MARKERS_ROUTES_ZONES_FOUND, n, l, d) + " " + la.ARE_YOU_SURE_YOU_WANT_TO_IMPORT;
                confirmDialog(r, function(e) {
                    if (e) {
                        loadingData(!0);
                        var t = {
                            format: "plc",
                            data: s,
                            markers: !0,
                            routes: !0,
                            zones: !0
                        };
                        $.ajax({
                            type: "POST",
                            url: "func/fn_import.php",
                            data: t,
                            cache: !1,
                            success: function(e) {
                                loadingData(!1), "OK" == e ? (placesMarkerLoadData(), placesRouteLoadData(), placesZoneLoadData(), $("#side_panel_places_marker_list_grid").trigger("reloadGrid"), $("#side_panel_places_route_list_grid").trigger("reloadGrid"), $("#side_panel_places_zone_list_grid").trigger("reloadGrid")) : "ERROR_MARKER_LIMIT" == e ? notifyBox("error", la.ERROR, la.MARKER_LIMIT_IS_REACHED) : "ERROR_ROUTE_LIMIT" == e ? notifyBox("error", la.ERROR, la.ROUTE_LIMIT_IS_REACHED) : "ERROR_ZONE_LIMIT" == e && notifyBox("error", la.ERROR, la.ZONE_LIMIT_IS_REACHED)
                            },
                            error: function() {
                                loadingData(!1)
                            }
                        })
                    }
                })
            } else notifyBox("error", la.ERROR, la.INVALID_FILE_FORMAT)
        } catch (_) {
            notifyBox("error", la.ERROR, la.INVALID_FILE_FORMAT)
        }
        document.getElementById("load_file").value = ""
    }, a.readAsText(t[0], "UTF-8"), this.removeEventListener("change", placesImportPLCFile, !1)
}

function placesExport() {
    if (utilsCheckPrivileges("viewer") && utilsCheckPrivileges("subuser") && 1 != gsValues.map_bussy) {
        var e = "func/fn_export.php?format=plc";
        window.location = e
    }
}

function placesKMLToPLC(e) {
    for (var t = 1, a = 1, o = {
            plc: "0.1v",
            markers: new Array,
            routes: new Array,
            zones: new Array
        }, i = e.kml.Document.Placemark, s = 0; s < i.length - 1; s++) {
        var n = i[s];
        if (void 0 != n.Point) {
            if (void 0 != n.name) var l = n.name;
            else {
                var l = "Marker " + t;
                t += 1
            }
            if (void 0 != n.description.__text) var d = n.description.toString();
            else var d = "";
            var r = "img/markers/places/pin-1.svg",
                _ = n.Point.coordinates.split(","),
                c = _[0],
                g = _[1];
            o.markers.push({
                name: l,
                desc: d,
                icon: r,
                visible: "true",
                lat: g,
                lng: c
            })
        }
        if (void 0 != n.Polygon) {
            if (void 0 != n.name) var l = n.name;
            else {
                var l = "Zone " + a;
                a += 1
            }
            if (void 0 != n.Style.PolyStyle.color) var m = "#" + n.Style.PolyStyle.color.slice(0, -2);
            else var m = "#FF0000";
            var u = n.Polygon.outerBoundaryIs.LinearRing.coordinates.split(" ");
            if (u.length <= 40) {
                for (var p = [], v = 0; v < u.length; v++) {
                    var _ = u[v].split(","),
                        c = _[0],
                        g = _[1];
                    p.push(parseFloat(g).toFixed(6) + "," + parseFloat(c).toFixed(6))
                }
                p = p.toString(), o.zones.push({
                    name: l,
                    color: m,
                    visible: "true",
                    name_visible: "true",
                    area: "0",
                    vertices: p
                })
            }
        }
    }
    return o
}

function placesMarkerReload() {
    placesGroupLoadData(), placesMarkerLoadData(), $("#side_panel_places_marker_list_grid").trigger("reloadGrid")
}

function placesMarkerLoadData() {
    var e = {
        cmd: "load_marker_data"
    };
    $.ajax({
        type: "POST",
        url: "func/fn_places.php",
        data: e,
        dataType: "json",
        cache: !1,
        success: function(e) {
            placesMarkerData.markers = e, placesMarkerInitLists(), placesMarkerSetListCheckbox(), placesMarkerSetListNumber(), "" != placesMarkerData.markers ? placesMarkerAddAllToMap() : placesMarkerRemoveAllFromMap()
        }
    })
}

function placesMarkerInitLists() {
    initSelectList("subaccounts_marker_list")
}

function placesMarkerSetListNumber() {
    document.getElementById("side_panel_places_markers_num").innerHTML = "(" + Object.keys(placesMarkerData.markers).length + ")"
}

function placesMarkerSetListCheckbox() {
    for (var e in placesGroupData.groups) placesSetListCheckbox("marker_group_visible_" + e, placesGroupData.groups[e].marker_visible);
    for (var e in placesMarkerData.markers) placesSetListCheckbox("marker_visible_" + e, placesMarkerData.markers[e].visible)
}

function placesMarkerClickListCheckbox(e, t) {
    placesMarkerData.markers[e].visible = t, placesMarkerVisibleOnMap(e, t)
}

function placesMarkerAddAllToMap() {
    var e = document.getElementById("side_panel_places_marker_list_search").value;
    placesMarkerRemoveAllFromMap();
    for (var t in placesMarkerData.markers) {
        var a = placesMarkerData.markers[t];
        if (strMatches(a.data.name, e)) {
            var o = a.data.name,
                i = a.data.desc,
                s = a.data.icon,
                n = a.data.visible,
                l = a.data.lat,
                d = a.data.lng;
            try {
                placesMarkerAddMarkerToMap(t, o, i, s, n, l, d)
            } catch (r) {}
        }
    }
}

function placesMarkerAddMarkerToMap(e, t, a, o, i, s, n) {
    var l = settingsUserData.map_is,
        d = L.icon({
            iconUrl: o,
            iconSize: [28 * l, 28 * l],
            iconAnchor: [14 * l, 28 * l],
            popupAnchor: [0, 0]
        }),
        r = L.marker([s, n], {
            icon: d
        }),
        _ = "<table><tr><td><strong>" + t + "</strong></td></tr>";
    "" != a && (_ += "<tr><td>" + a + "</td></tr>"), _ += "</table>", r.on("click", function() {
        addPopupToMap(s, n, [0, -28 * l], _, "")
    }), "false" != i && mapLayers.places_markers.addLayer(r), placesMarkerData.markers[e].marker_layer = r
}

function placesMarkerRemoveAllFromMap() {
    mapLayers.places_markers.clearLayers()
}

function placesMarkerSearchMap(e) {
    for (var t in placesMarkerData.markers) {
        var a = placesMarkerData.markers[t];
        strMatches(a.data.name, e) ? 1 == a.visible && placesMarkerVisibleOnMap(t, !0) : placesMarkerVisibleOnMap(t, !1)
    }
}

function placesMarkerDeleteAll() {
    if(settingsUserData['access_settings_markers']['delete']!=true){
        notifyBox("error", la.ERROR, la.NO_PERMISSION);
        return;
    }
    utilsCheckPrivileges("viewer") && utilsCheckPrivileges("subuser") && 1 != gsValues.map_bussy && confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE_ALL_MARKERS, function(e) {
        if (e) {
            var t = {
                cmd: "delete_all_markers"
            };
            $.ajax({
                type: "POST",
                url: "func/fn_places.php",
                data: t,
                success: function(e) {
                    "OK" == e ? (placesMarkerLoadData(), $("#side_panel_places_marker_list_grid").trigger("reloadGrid")) : "NO_PERMISSION"==e && notifyBox("error", la.ERROR, la.NO_PERMISSION)
                }
            })
        }
    })
}

function placesMarkerDelete(e) {
    if(settingsUserData['access_settings_markers']['delete']!=true){
        notifyBox("error", la.ERROR, la.NO_PERMISSION);
        return;
    }
    utilsCheckPrivileges("viewer") && utilsCheckPrivileges("subuser") && 1 != gsValues.map_bussy && (placesMarkerPanTo(e), confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE, function(t) {
        if (t) {
            var a = {
                cmd: "delete_marker",
                marker_id: e
            };
            $.ajax({
                type: "POST",
                url: "func/fn_places.php",
                data: a,
                success: function(t) {
                    "OK" == t ? (placesMarkerVisibleOnMap(e, !1), delete placesMarkerData.markers[e], placesMarkerSetListNumber(), placesMarkerInitLists(), $("#side_panel_places_marker_list_grid").trigger("reloadGrid")) : "NO_PERMISSION"==t && notifyBox("error", la.ERROR, la.NO_PERMISSION)
                }
            })
        }
    }))
}

function placesMarkerNew() {
    if(settingsUserData['access_settings_markers']['add']!=true){
        notifyBox("error", la.ERROR, la.NO_PERMISSION);
        return;
    }
    utilsCheckPrivileges("viewer") && utilsCheckPrivileges("subuser") && 1 != gsValues.map_bussy && (map.doubleClickZoom.disable(), gsValues.map_bussy = !0, document.getElementById("dialog_places_marker_name").value = la.NEW_MARKER + " " + placesMarkerData.new_marker_id, document.getElementById("dialog_places_marker_desc").value = "", document.getElementById("dialog_places_marker_group").value = 0, document.getElementById("dialog_places_marker_visible").checked = !0, $("#dialog_places_marker_properties").dialog("open"), placesMarkerLoadDefaultIconList(), placesMarkerLoadCustomIconList(), map.on("click", placesMarkerAddToMapByClick))
}

function placesMarkerAddToMapByClick(e) {
    map.removeLayer(placesMarkerData.edit_marker_layer), placesMarkerAddToMap(e.latlng.lat, e.latlng.lng, placesMarkerData.marker_icon)
}

function placesMarkerAddToMap(e, t, a) {
    var o = settingsUserData.map_is,
        i = L.icon({
            iconUrl: a,
            iconSize: [28 * o, 28 * o],
            iconAnchor: [14 * o, 28 * o],
            popupAnchor: [0, 0]
        });
    placesMarkerData.edit_marker_layer = L.marker([e, t], {
        icon: i
    }), placesMarkerData.edit_marker_layer.addTo(map)
}

function placesMarkerProperties(e) {
    if (utilsCheckPrivileges("viewer") && utilsCheckPrivileges("subuser")) switch (e) {
        default: if (1 == gsValues.map_bussy) return;map.doubleClickZoom.disable(),
        gsValues.map_bussy = !0;
        var t = e;placesMarkerData.edit_marker_id = t,
        document.getElementById("dialog_places_marker_name").value = placesMarkerData.markers[t].data.name,
        document.getElementById("dialog_places_marker_desc").value = placesMarkerData.markers[t].data.desc,
        document.getElementById("dialog_places_marker_group").value = placesMarkerData.markers[t].data.group_id,
        "true" == placesMarkerData.markers[t].data.visible ? document.getElementById("dialog_places_marker_visible").checked = !0 : document.getElementById("dialog_places_marker_visible").checked = !1,
        placesMarkerData.marker_icon = placesMarkerData.markers[t].data.icon,
        $("#dialog_places_marker_properties").dialog("open"),
        placesMarkerLoadDefaultIconList(),
        placesMarkerLoadCustomIconList(),
        mapLayers.places_markers.removeLayer(placesMarkerData.markers[t].marker_layer);
        var a = placesMarkerData.markers[t].marker_layer.getLatLng();placesMarkerAddToMap(a.lat, a.lng, placesMarkerData.marker_icon),
        map.on("click", placesMarkerAddToMapByClick);
        if(settingsUserData['access_settings_markers']['edit']!=true){

            document.getElementById("placesMarkerPropertiesSave").style.display='none';
        }
        break;
        case "cancel":
                if (map.removeLayer(placesMarkerData.edit_marker_layer), map.off("click"), 0 != placesMarkerData.edit_marker_id) {
                var t = placesMarkerData.edit_marker_id;
                "false" == placesMarkerData.markers[t].data.visible ? mapLayers.places_markers.removeLayer(placesMarkerData.markers[t].marker_layer) : mapLayers.places_markers.addLayer(placesMarkerData.markers[t].marker_layer)
            }placesMarkerData.edit_marker_id = !1,
            placesMarkerData.edit_marker_layer = !1,
            gsValues.map_bussy = !1,
            map.doubleClickZoom.enable(),
            $("#dialog_places_marker_properties").dialog("close");
            break;
        case "save":
            if(placesMarkerData.edit_marker_id ==false && settingsUserData['access_settings_markers']['add']!=true){
                notifyBox("error", la.ERROR, la.NO_PERMISSION);
                return;
            }else if(placesMarkerData.edit_marker_id !=false && settingsUserData['access_settings_markers']['edit']!=true){
                notifyBox("error", la.ERROR, la.NO_PERMISSION);
                return;
            }
                var o = document.getElementById("dialog_places_marker_name").value,
                i = document.getElementById("dialog_places_marker_desc").value,
                s = document.getElementById("dialog_places_marker_group").value,
                n = document.getElementById("dialog_places_marker_visible").checked,
                l = placesMarkerData.marker_icon;
            if ("" == o) {
                notifyBox("error", la.ERROR, la.NAME_CANT_BE_EMPTY);
                break
            }
            if (0 == placesMarkerData.edit_marker_layer) {
                notifyBox("error", la.ERROR, la.PLACE_MARKER_ON_MAP_BEFORE_SAVING);
                break
            }
            var a = placesMarkerData.edit_marker_layer.getLatLng();map.removeLayer(placesMarkerData.edit_marker_layer),
            map.off("click"),
            0 == placesMarkerData.edit_marker_id && (placesMarkerData.new_marker_id += 1);
            var d = {
                cmd: "save_marker",
                marker_id: placesMarkerData.edit_marker_id,
                group_id: s,
                marker_name: o,
                marker_desc: i,
                marker_icon: l,
                marker_visible: n,
                marker_lat: a.lat.toFixed(6),
                marker_lng: a.lng.toFixed(6)
            };$.ajax({
                type: "POST",
                url: "func/fn_places.php",
                data: d,
                success: function(e) {
                    placesMarkerData.edit_marker_layer = !1, placesMarkerData.edit_marker_id = !1, gsValues.map_bussy = !1, map.doubleClickZoom.enable(), $("#dialog_places_marker_properties").dialog("close"), "OK" == e ? (placesMarkerLoadData(), $("#side_panel_places_marker_list_grid").trigger("reloadGrid")) : "ERROR_MARKER_LIMIT" == e ? notifyBox("error", la.ERROR, la.MARKER_LIMIT_IS_REACHED) : "NO_PERMISSION" == e && notifyBox("error", la.ERROR, la.NO_PERMISSION)
                }
            })
    }
}

function placesMarkerLoadDefaultIconList() {
    0 == placesMarkerData.default_icons_loaded && $.ajax({
        type: "POST",
        url: "func/fn_files.php",
        data: {
            path: "img/markers/places"
        },
        dataType: "json",
        success: function(e) {
            var t = '<div class="row2">';
            for (document.getElementById("places_marker_icon_default_list").innerHTML = "", i = 0; i < e.length; i++) {
                var a = "img/markers/places/" + e[i];
                t += '<div class="icon-places-marker">', t += '<a href="#" onclick="placesMarkerSelectIcon(\'' + a + "');\">", t += '<img src="' + a + '" style="padding:5px; width: 32px; height: 32px;"/>', t += "</a>", t += "</div>"
            }
            t += "</div>", document.getElementById("places_marker_icon_default_list").innerHTML = t, placesMarkerData.default_icons_loaded = !0
        }
    })
}

function placesMarkerLoadCustomIconList() {
    0 == placesMarkerData.custom_icons_loaded && $.ajax({
        type: "POST",
        url: "func/fn_files.php",
        data: {
            path: "data/user/places"
        },
        dataType: "json",
        success: function(e) {
            var t = '<div class="row2">';
            for (document.getElementById("places_marker_icon_custom_list").innerHTML = "", i = 0; i < e.length; i++) {
                var a = "data/user/places/" + e[i];
                t += '<div class="icon-places-marker">', t += '<a href="#" onclick="placesMarkerSelectIcon(\'' + a + "');\">", t += '<img src="' + a + '" style="padding:5px; width: 32px; height: 32px;"/>', t += "</a>", t += '<div class="icon-custom-delete">', t += '<a href="#" onclick="placesMarkerDeleteCustomIcon(\'' + a + "');\">", t += '<img border="0" src="theme/images/remove.svg" width="8px">', t += "</a>", t += "</div>", t += "</div>"
            }
            t += "</div>", document.getElementById("places_marker_icon_custom_list").innerHTML = t, placesMarkerData.custom_icons_loaded = !0
        }
    })
}

function placesMarkerUploadCustomIcon() {
    utilsCheckPrivileges("viewer") && (document.getElementById("load_file").addEventListener("change", placesMarkerUploadCustomIconFile, !1), document.getElementById("load_file").click())
}

function placesMarkerUploadCustomIconFile(e) {
    var t = e.target.files,
        a = new FileReader;
    a.onloadend = function(e) {
        var a = e.target.result;
        if ("image/png" != t[0].type && "image/svg+xml" != t[0].type) return void notifyBox("error", la.ERROR, la.FILE_TYPE_MUST_BE_PNG_OR_SVG);
        var o = new Image;
        o.src = a, o.onload = function() {
            if (o.src.includes("image/png")) {
                if (32 != o.width || 32 != o.height) return void notifyBox("error", la.ERROR, la.ICON_SIZE_SHOULD_BE_32_32);
                var e = "func/fn_upload.php?file=places_icon_png"
            } else var e = "func/fn_upload.php?file=places_icon_svg";
            $.ajax({
                url: e,
                type: "POST",
                data: a,
                processData: !1,
                contentType: !1,
                success: function() {
                    placesMarkerData.custom_icons_loaded = !1, placesMarkerLoadCustomIconList()
                }
            })
        }, document.getElementById("load_file").value = ""
    }, a.readAsDataURL(t[0]), this.removeEventListener("change", placesMarkerUploadCustomIconFile, !1)
}

function placesMarkerDeleteCustomIcon(e) {
    utilsCheckPrivileges("viewer") && confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE_THIS_ICON, function(t) {
        if (t) {
            var a = {
                cmd: "delete_custom_icon",
                file: e
            };
            $.ajax({
                type: "POST",
                url: "func/fn_places.php",
                data: a,
                success: function(e) {
                    "OK" == e && (placesMarkerData.custom_icons_loaded = !1, placesMarkerLoadCustomIconList())
                }
            })
        }
    })
}

function placesMarkerDeleteAllCustomIcon() {
    utilsCheckPrivileges("viewer") && confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE_ALL_CUSTOM_ICONS, function(e) {
        if (e) {
            var t = {
                cmd: "delete_all_custom_icons"
            };
            $.ajax({
                type: "POST",
                url: "func/fn_places.php",
                data: t,
                success: function(e) {
                    "OK" == e && (placesMarkerData.custom_icons_loaded = !1, placesMarkerLoadCustomIconList())
                }
            })
        }
    })
}

function placesMarkerSelectIcon(e) {
    if (placesMarkerData.marker_icon = e, 0 != placesMarkerData.edit_marker_layer) {
        map.removeLayer(placesMarkerData.edit_marker_layer);
        var t = placesMarkerData.edit_marker_layer.getLatLng();
        placesMarkerAddToMap(t.lat, t.lng, placesMarkerData.marker_icon)
    }
}

function placesMarkerPanTo(e) {
    try {
        var t = placesMarkerData.markers[e].data.lng,
            a = placesMarkerData.markers[e].data.lat;
        map.panTo({
            lat: a,
            lng: t
        })
    } catch (o) {}
}

function placesMarkerVisibleOnMap(e, t) {
    var a = placesMarkerData.markers[e].marker_layer;
    1 == t ? "true" == placesMarkerData.markers[e].data.visible && mapLayers.places_markers.addLayer(a) : mapLayers.places_markers.removeLayer(a)
}

function markerGroupVisibleTrigger(e) {
    var t = document.getElementById("marker_group_visible_" + e).checked;
    for (var a in placesMarkerData.markers) placesMarkerData.markers[a].data.group_id == e && (placesGroupData.groups[e].marker_visible = t, placesMarkerData.markers[a].visible = t, placesSetListCheckbox("marker_visible_" + a, t), placesMarkerVisibleOnMap(a, t))
}

function placesMarkerVisibleAllTrigger() {
    placesMarkerVisibleAll(1 == gsValues.map_markers ? !1 : !0)
}

function placesMarkerVisibleAll(e) {
    if (gsValues.map_markers = e, 1 == e) {
        for (var t in placesGroupData.groups) placesGroupData.groups[t].marker_visible = !0, placesSetListCheckbox("marker_group_visible_" + t, !0);
        for (var t in placesMarkerData.markers) placesMarkerData.markers[t].visible = !0, placesSetListCheckbox("marker_visible_" + t, !0), placesMarkerVisibleOnMap(t, !0)
    } else {
        for (var t in placesGroupData.groups) placesGroupData.groups[t].marker_visible = !1, placesSetListCheckbox("marker_group_visible_" + t, !1);
        for (var t in placesMarkerData.markers) placesMarkerData.markers[t].visible = !1, placesSetListCheckbox("marker_visible_" + t, !1);
        placesMarkerRemoveAllFromMap()
    }
}

function placesZoneReload() {
    placesGroupLoadData(), placesZoneLoadData(), $("#side_panel_places_zone_list_grid").trigger("reloadGrid")
}

function placesZoneLoadData() {
    var e = {
        cmd: "load_zone_data"
    };
    $.ajax({
        type: "POST",
        url: "func/fn_places.php",
        data: e,
        dataType: "json",
        cache: !1,
        success: function(e) {
            placesZoneData.zones = e, placesZoneInitLists(), placesZoneSetListCheckbox(), placesZoneSetListNumber(), "" != placesZoneData.zones ? placesZoneAddAllToMap() : placesZoneRemoveAllFromMap()
        }
    })
}

function placesZoneInitLists() {
    initSelectList("report_zone_list"), initSelectList("events_zone_list"), initSelectList("subaccounts_zone_list")
}

function placesZoneSetListNumber() {
    document.getElementById("side_panel_places_zones_num").innerHTML = "(" + Object.keys(placesZoneData.zones).length + ")"
}

function placesZoneSetListCheckbox() {
    for (var e in placesGroupData.groups) placesSetListCheckbox("zone_group_visible_" + e, placesGroupData.groups[e].zone_visible);
    for (var e in placesZoneData.zones) placesSetListCheckbox("zone_visible_" + e, placesZoneData.zones[e].visible)
}

function placesZoneClickListCheckbox(e, t) {
    placesZoneData.zones[e].visible = t, placesZoneVisibleOnMap(e, t)
}

function placesZoneAddAllToMap() {
    var e = document.getElementById("side_panel_places_zone_list_search").value;
    placesZoneRemoveAllFromMap();
    for (var t in placesZoneData.zones) {
        var a = placesZoneData.zones[t];
        if (strMatches(a.data.name, e)) {
            var o = a.data.name,
                i = a.data.color,
                s = a.data.visible,
                n = a.data.name_visible,
                l = a.data.area,
                d = a.data.vertices;
            try {
                placesZoneAddZoneToMap(t, o, i, s, n, l, d)
            } catch (r) {}
        }
    }
}

function placesZoneAddZoneToMap(e, t, a, o, i, s, n) {
	//update by vetrivel.N hidden pop
    var l = placesZoneVerticesStringToLatLngs(n),
        d = L.polygon(l, {
            color: a,
            fill: !0,
            fillColor: a,
            fillOpacity: .4,
            opacity: .8,
            weight: 3
        });
    "false" == i && (d.bindTooltip(t),t = ""), "0" != s && (measure_area = getAreaFromLatLngs(d.getLatLngs()[0]), "1" == s && (measure_area = 247105e-9 * measure_area, measure_area = Math.round(100 * measure_area) / 100, measure_area = measure_area + " " + la.UNIT_ACRE), "2" == s && (measure_area = 1e-4 * measure_area, measure_area = Math.round(100 * measure_area) / 100, measure_area = measure_area + " " + la.UNIT_HECTARES), "3" == s && (measure_area = Math.round(100 * measure_area) / 100, measure_area = measure_area + " " + la.UNIT_SQ_M), "4" == s && (measure_area = 1e-6 * measure_area, measure_area = Math.round(100 * measure_area) / 100, measure_area = measure_area + " " + la.UNIT_SQ_KM), "5" == s && (measure_area = 10.7639 * measure_area, measure_area = Math.round(100 * measure_area) / 100, measure_area = measure_area + " " + la.UNIT_SQ_FT), "6" == s && (measure_area = 1e-6 * measure_area * .386102, measure_area = Math.round(100 * measure_area) / 100, measure_area = measure_area + " " + la.UNIT_SQ_MI), t = t + " (" + measure_area + ")");
    var r = d.getBounds().getCenter(),
        _ = L.tooltip({
            permanent: !0,
            direction: "center"
        });
     _.setLatLng(r), _.setContent(t), "false" != o && mapLayers.places_zones.addLayer(d), "false" == i && "0" == s || mapLayers.places_zones.addLayer(_), placesZoneData.zones[e].zone_layer = d, placesZoneData.zones[e].label_layer = _   
}

function placesZoneRemoveAllFromMap() {
    mapLayers.places_zones.clearLayers()
}

function placesZoneSearchMap(e) {
    for (var t in placesZoneData.zones) {
        var a = placesZoneData.zones[t];
        strMatches(a.data.name, e) ? 1 == a.visible && placesZoneVisibleOnMap(t, !0) : placesZoneVisibleOnMap(t, !1)
    }
}

function placesZoneDeleteAll() {
    if(settingsUserData['access_settings_zones']['delete']!=true){
        notifyBox("error", la.ERROR, la.NO_PERMISSION);
        return;
    }
    utilsCheckPrivileges("viewer") && utilsCheckPrivileges("subuser") && 1 != gsValues.map_bussy && confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE, function(e) {
        if (e) {
            var t = {
                cmd: "delete_all_zones"
            };
            $.ajax({
                type: "POST",
                url: "func/fn_places.php",
                data: t,
                success: function(e) {
                    "OK" == e ? (placesZoneLoadData(), $("#side_panel_places_zone_list_grid").trigger("reloadGrid")) : "NO_PERMISSION"==e && notifyBox("error", la.ERROR, la.NO_PERMISSION)
                }
            })
        }
    })
}

function placesZoneDelete(e) {
    if(settingsUserData['access_settings_zones']['delete']!=true){
        notifyBox("error", la.ERROR, la.NO_PERMISSION);
        return;
    }
    utilsCheckPrivileges("viewer") && utilsCheckPrivileges("subuser") && 1 != gsValues.map_bussy && (placesZonePanTo(e), confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE, function(t) {
        if (t) {
            var a = {
                cmd: "delete_zone",
                zone_id: e
            };
            $.ajax({
                type: "POST",
                url: "func/fn_places.php",
                data: a,
                success: function(t) {
                    "OK" == t ? (placesZoneVisibleOnMap(e, !1), delete placesZoneData.zones[e], placesZoneSetListNumber(), placesZoneInitLists(), $("#side_panel_places_zone_list_grid").trigger("reloadGrid")) : "NO_PERMISSION"==t && notifyBox("error", la.ERROR, la.NO_PERMISSION)
                }
            })
        }
    }))
}

function placesZoneNew() {
    if(settingsUserData['access_settings_zones']['add']!=true){
        notifyBox("error", la.ERROR, la.NO_PERMISSION);
        return;
    }
    utilsCheckPrivileges("viewer") && utilsCheckPrivileges("subuser") && 1 != gsValues.map_bussy && (map.doubleClickZoom.disable(), gsValues.map_bussy = !0, document.getElementById("dialog_places_zone_name").value = la.NEW_ZONE + " " + placesZoneData.new_zone_id, document.getElementById("dialog_places_zone_group").value = 0, document.getElementById("dialog_places_zone_color").value = "FF0000", document.getElementById("dialog_places_zone_color").style.backgroundColor = "#FF0000", document.getElementById("dialog_places_zone_visible").checked = !0, document.getElementById("dialog_places_zone_name_visible").checked = !0, document.getElementById("dialog_places_zone_area").value = 0, $("#dialog_places_zone_properties").dialog("open"), map.editTools.startPolygon(), map.on("editable:drawing:end", function(e) {
        return placesZoneData.edit_zone_layer = e.layer, placesZoneData.edit_zone_layer.getLatLngs()[0].length < 3 ? void placesZoneProperties("cancel") : placesZoneData.edit_zone_layer.getLatLngs()[0].length > 40 ? void notifyBox("error", la.ERROR, la.ZONE_CANT_HAVE_MORE_THAN_NUM_VERTICES) : void map.off("editable:drawing:end")
    }))
}

function placesZoneLatLngsToVerticesString(e) {
    for (var t = [], a = 0; a < e.length; a++) {
        var o = e[a],
            i = o.lat,
            s = o.lng;
        t.push(parseFloat(i).toFixed(6) + "," + parseFloat(s).toFixed(6))
    }
    return t.push(t[0]), t.toString()
}

function placesZoneVerticesStringToLatLngs(e) {
    var t = e.split(","),
        a = [];
    for (j = 0; j < t.length; j += 2) lat = t[j], lng = t[j + 1], a.push(L.latLng(lat, lng));
    return a
}

function placesZoneProperties(e) {
    if (utilsCheckPrivileges("viewer") && utilsCheckPrivileges("subuser")) switch (e) {
        default: if (1 == gsValues.map_bussy) return;map.doubleClickZoom.disable(),
        gsValues.map_bussy = !0;
        var t = e;placesZoneData.edit_zone_id = t,
        document.getElementById("dialog_places_zone_name").value = placesZoneData.zones[t].data.name,
        document.getElementById("dialog_places_zone_group").value = placesZoneData.zones[t].data.group_id,
        document.getElementById("dialog_places_zone_color").value = placesZoneData.zones[t].data.color.substr(1),
        document.getElementById("dialog_places_zone_color").style.backgroundColor = placesZoneData.zones[t].data.color,
        document.getElementById("dialog_places_zone_visible").checked = strToBoolean(placesZoneData.zones[t].data.visible),
        document.getElementById("dialog_places_zone_name_visible").checked = strToBoolean(placesZoneData.zones[t].data.name_visible),
        document.getElementById("dialog_places_zone_area").value = placesZoneData.zones[t].data.area,
        $("#dialog_places_zone_properties").dialog("open"),
        placesZoneVisibleOnMap(placesZoneData.edit_zone_id, !1);
        var a = placesZoneData.zones[placesZoneData.edit_zone_id],
            o = a.data.color,
            i = a.data.vertices,
            s = placesZoneVerticesStringToLatLngs(i);placesZoneData.edit_zone_layer = L.polygon(s, {
            color: o,
            fill: !0,
            fillColor: o,
            fillOpacity: .4,
            opacity: .8,
            weight: 3
        }),
        map.addLayer(placesZoneData.edit_zone_layer),
        placesZoneFitBounds(t),
        setTimeout(function() {
            placesZoneData.edit_zone_layer.enableEdit()
        }, 200);
        if(settingsUserData['access_settings_zones']['add']!=true){
            document.getElementById("placesZonePropertiesSave").style.display = "none";
        }
        
        break;
        case "cancel":
                map.editTools.stopDrawing(),
            map.off("editable:drawing:end"),
            map.removeLayer(placesZoneData.edit_zone_layer);
            var a = placesZoneData.zones[placesZoneData.edit_zone_id];0 != placesZoneData.edit_zone_id && 1 == a.visible && placesZoneVisibleOnMap(placesZoneData.edit_zone_id, !0),
            placesZoneData.edit_zone_layer = !1,
            placesZoneData.edit_zone_id = !1,
            gsValues.map_bussy = !1,
            map.doubleClickZoom.enable(),
            $("#dialog_places_zone_properties").dialog("close");
            break;
        case "save":
            if(placesZoneData.edit_zone_id==false && settingsUserData['access_settings_zones']['add']!=true){
                notifyBox("error", la.ERROR, la.NO_PERMISSION);
                return;
            }else if(placesZoneData.edit_zone_id!=false && settingsUserData['access_settings_zones']['edit']!=true){
                notifyBox("error", la.ERROR, la.NO_PERMISSION);
                return;
            }
                var n = document.getElementById("dialog_places_zone_name").value,
                l = document.getElementById("dialog_places_zone_group").value,
                d = "#" + document.getElementById("dialog_places_zone_color").value,
                r = document.getElementById("dialog_places_zone_visible").checked,
                _ = document.getElementById("dialog_places_zone_name_visible").checked,
                c = document.getElementById("dialog_places_zone_area").value;
            if ("" == n) {
                notifyBox("error", la.ERROR, la.NAME_CANT_BE_EMPTY);
                break
            }
            if (!placesZoneData.edit_zone_layer) {
                notifyBox("error", la.ERROR, la.DRAW_ZONE_ON_MAP_BEFORE_SAVING);
                break
            }
            if (placesZoneData.edit_zone_layer.getLatLngs()[0].length < 3) {
                notifyBox("error", la.ERROR, la.DRAW_ZONE_ON_MAP_BEFORE_SAVING);
                break
            }
            if (placesZoneData.edit_zone_layer.getLatLngs()[0].length > 40) return void notifyBox("error", la.ERROR, la.ZONE_CANT_HAVE_MORE_THAN_NUM_VERTICES);
            var g = placesZoneLatLngsToVerticesString(placesZoneData.edit_zone_layer.getLatLngs()[0]);map.editTools.stopDrawing(),
            map.off("editable:drawing:end"),
            map.removeLayer(placesZoneData.edit_zone_layer),
            0 == placesZoneData.edit_zone_id && (placesZoneData.new_zone_id += 1);
            var m = {
                cmd: "save_zone",
                zone_id: placesZoneData.edit_zone_id,
                group_id: l,
                zone_name: n,
                zone_color: d,
                zone_visible: r,
                zone_name_visible: _,
                zone_area: c,
                zone_vertices: g
            };$.ajax({
                type: "POST",
                url: "func/fn_places.php",
                data: m,
                success: function(e) {
                    placesZoneData.edit_zone_layer = !1, placesZoneData.edit_zone_id = !1, gsValues.map_bussy = !1, map.doubleClickZoom.enable(), $("#dialog_places_zone_properties").dialog("close"), "OK" == e ? (placesZoneLoadData(), $("#side_panel_places_zone_list_grid").trigger("reloadGrid")) : "ERROR_ZONE_LIMIT" == e ? notifyBox("error", la.ERROR, la.ZONE_LIMIT_IS_REACHED) : "NO_PERMISSION" == e && notifyBox("error", la.ERROR, la.NO_PERMISSION)
                }
            })
    }
}

function placesZonePanTo(e) {
    try {
        var t = placesZoneData.zones[e].zone_layer,
            a = t.getBounds().getCenter();
        map.panTo(a)
    } catch (o) {}
}

function placesZoneFitBounds(e) {
    var t = placesZoneData.zones[e].zone_layer,
        a = t.getBounds();
    map.fitBounds(a)
}

function placesZoneVisibleOnMap(e, t) {
    var a = placesZoneData.zones[e].zone_layer,
        o = placesZoneData.zones[e].label_layer;
    1 == t ? ("true" == placesZoneData.zones[e].data.visible ? mapLayers.places_zones.addLayer(a) : mapLayers.places_zones.removeLayer(a), "true" == placesZoneData.zones[e].data.name_visible || "0" != placesZoneData.zones[e].data.area ? mapLayers.places_zones.addLayer(o) : mapLayers.places_zones.removeLayer(o)) : (mapLayers.places_zones.removeLayer(a), mapLayers.places_zones.removeLayer(o))
}

function zoneGroupVisibleTrigger(e) {
    var t = document.getElementById("zone_group_visible_" + e).checked;
    for (var a in placesZoneData.zones) placesZoneData.zones[a].data.group_id == e && (placesGroupData.groups[e].zone_visible = t, placesZoneData.zones[a].visible = t, placesSetListCheckbox("zone_visible_" + a, t), placesZoneVisibleOnMap(a, t))
}

function placesZoneVisibleAllTrigger() {
    placesZoneVisibleAll(1 == gsValues.map_zones ? !1 : !0)
}

function placesZoneVisibleAll(e) {
    if (gsValues.map_zones = e, 1 == e) {
        for (var t in placesGroupData.groups) placesGroupData.groups[t].zone_visible = !0, placesSetListCheckbox("zone_group_visible_" + t, !0);
        for (var t in placesZoneData.zones) placesZoneData.zones[t].visible = !0, placesSetListCheckbox("zone_visible_" + t, !0), placesZoneVisibleOnMap(t, !0)
    } else {
        for (var t in placesGroupData.groups) placesGroupData.groups[t].zone_visible = !1, placesSetListCheckbox("zone_group_visible_" + t, !1);
        for (var t in placesZoneData.zones) placesZoneData.zones[t].visible = !1, placesSetListCheckbox("zone_visible_" + t, !1);
        placesZoneRemoveAllFromMap()
    }
}

function placesRouteReload() {
    placesGroupLoadData(), placesRouteLoadData(), $("#side_panel_places_route_list_grid").trigger("reloadGrid")
}

function placesRouteLoadData() {
    var e = {
        cmd: "load_route_data"
    };
    $.ajax({
        type: "POST",
        url: "func/fn_places.php",
        data: e,
        dataType: "json",
        cache: !1,
        success: function(e) {
            placesRouteData.routes = e, placesRouteInitLists(), placesRouteSetListCheckbox(), placesRouteSetListNumber(), "" != placesRouteData.routes ? placesRouteAddAllToMap() : placesRouteRemoveAllFromMap()
        }
    })
}

function placesRouteInitLists() {
    initSelectList("events_route_list"), initSelectList("subaccounts_route_list")
}

function placesRouteSetListNumber() {
    document.getElementById("side_panel_places_routes_num").innerHTML = "(" + Object.keys(placesRouteData.routes).length + ")"
}

function placesRouteSetListCheckbox() {
    for (var e in placesGroupData.groups) placesSetListCheckbox("route_group_visible_" + e, placesGroupData.groups[e].route_visible);
    for (var e in placesRouteData.routes) placesSetListCheckbox("route_visible_" + e, placesRouteData.routes[e].visible)
}

function placesRouteClickListCheckbox(e, t) {
    placesRouteData.routes[e].visible = t, placesRouteVisibleOnMap(e, t)
}

function placesRouteAddAllToMap() {
    var e = document.getElementById("side_panel_places_route_list_search").value;
    placesRouteRemoveAllFromMap();
    for (var t in placesRouteData.routes) {
        var a = placesRouteData.routes[t];
        if (strMatches(a.data.name, e)) {
            var o = a.data.name,
                i = a.data.color,
                s = a.data.visible,
                n = a.data.name_visible,
                l = a.data.points;
            try {
                placesRouteAddRouteToMap(t, o, i, s, n, l)
            } catch (d) {}
        }
    }
}

function placesRouteAddRouteToMap(e, t, a, o, i, s) {
    var n = placesRoutePointsStringToLatLngs(s),
        l = L.polyline(n, {
            color: a,
            fill: !1,
            opacity: .8,
            weight: 3
        }),
        d = n[0],
        r = L.tooltip({
            permanent: !0,
            direction: "top"
        });
    r.setLatLng(d), r.setContent(t), "false" != o && mapLayers.places_routes.addLayer(l), "false" != i && mapLayers.places_routes.addLayer(r), placesRouteData.routes[e].route_layer = l, placesRouteData.routes[e].label_layer = r
}

function placesRouteRemoveAllFromMap() {
    mapLayers.places_routes.clearLayers()
}

function placesRouteSearchMap(e) {
    for (var t in placesRouteData.routes) {
        var a = placesRouteData.routes[t];
        strMatches(a.data.name, e) ? 1 == a.visible && placesRouteVisibleOnMap(t, !0) : placesRouteVisibleOnMap(t, !1)
    }
}

function placesRouteDeleteAll() {
    if(settingsUserData['access_settings_route']['delete']!=true){
        notifyBox("error", la.ERROR, la.NO_PERMISSION);
        return;
    }
    utilsCheckPrivileges("viewer") && utilsCheckPrivileges("subuser") && 1 != gsValues.map_bussy && confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE_ALL_ROUTES, function(e) {
        if (e) {
            var t = {
                cmd: "delete_all_routes"
            };
            $.ajax({
                type: "POST",
                url: "func/fn_places.php",
                data: t,
                success: function(e) {
                    "OK" == e ? (placesRouteLoadData(), $("#side_panel_places_route_list_grid").trigger("reloadGrid")) : "NO_PERMISSION"==e && notifyBox("error", la.ERROR, la.NO_PERMISSION)
                }
            })
        }
    })
}

function placesRouteDelete(e) {
    if(settingsUserData['access_settings_route']['delete']!=true){
        notifyBox("error", la.ERROR, la.NO_PERMISSION);
        return;
    }
    utilsCheckPrivileges("viewer") && utilsCheckPrivileges("subuser") && 1 != gsValues.map_bussy && (placesRoutePanTo(e), confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE, function(t) {
        if (t) {
            var a = {
                cmd: "delete_route",
                route_id: e
            };
            $.ajax({
                type: "POST",
                url: "func/fn_places.php",
                data: a,
                success: function(t) {
                    "OK" == t ? (placesRouteVisibleOnMap(e, !1), delete placesRouteData.routes[e], placesRouteSetListNumber(), placesRouteInitLists(), $("#side_panel_places_route_list_grid").trigger("reloadGrid")) : "NO_PERMISSION"==t && notifyBox("error", la.ERROR, la.NO_PERMISSION)
                }
            })
        }
    }))
}

function placesRouteSave(e) {
    if (utilsCheckPrivileges("viewer") && utilsCheckPrivileges("subuser") && 1 != gsValues.map_bussy) {
        gsValues.map_bussy = !0, document.getElementById("side_panel_places_tab").click(), document.getElementById("side_panel_places_routes_tab").click(), document.getElementById("dialog_places_route_name").value = la.NEW_ROUTE + " " + placesRouteData.new_route_id, document.getElementById("dialog_places_route_group").value = 0, document.getElementById("dialog_places_route_color").value = "FF0000", document.getElementById("dialog_places_route_color").style.backgroundColor = "#FF0000", document.getElementById("dialog_places_route_visible").checked = !0, document.getElementById("dialog_places_route_name_visible").checked = !0, document.getElementById("dialog_places_route_deviation").value = "0.5", $("#dialog_places_route_properties").dialog("open");
        var t = "#FF0000";
        placesRouteData.edit_route_layer = L.polyline(e, {
            color: t,
            fill: !1,
            opacity: .8,
            weight: 3
        }), map.addLayer(placesRouteData.edit_route_layer), placesRouteData.edit_route_layer.enableEdit();
        var a = placesRouteData.edit_route_layer.getBounds();
        map.fitBounds(a)
    }
}

function placesRouteNew() {
    if(settingsUserData['access_settings_route']['add']!=true){
        notifyBox("error", la.ERROR, la.NO_PERMISSION);
        return;
    }
    utilsCheckPrivileges("viewer") && utilsCheckPrivileges("subuser") && 1 != gsValues.map_bussy && (map.doubleClickZoom.disable(), gsValues.map_bussy = !0, document.getElementById("dialog_places_route_name").value = la.NEW_ROUTE + " " + placesRouteData.new_route_id, document.getElementById("dialog_places_route_group").value = 0, document.getElementById("dialog_places_route_color").value = "FF0000", document.getElementById("dialog_places_route_color").style.backgroundColor = "#FF0000", document.getElementById("dialog_places_route_visible").checked = !0, document.getElementById("dialog_places_route_name_visible").checked = !0, document.getElementById("dialog_places_route_deviation").value = "0.5", $("#dialog_places_route_properties").dialog("open"), map.editTools.startPolyline(), map.on("editable:editing editable:drag", function(e) {
        placesRouteData.edit_route_layer = e.layer;
        var t = placesRouteData.edit_route_layer.getLatLngs(),
            a = t[0],
            o = t[t.length - 1];
        map.hasLayer(placesRouteData.edit_start_label_layer) ? placesRouteData.edit_start_label_layer.setLatLng(a) : (placesRouteData.edit_start_label_layer = L.tooltip({
            permanent: !0,
            offset: [10, 0],
            direction: "right"
        }), placesRouteData.edit_start_label_layer.setLatLng(o), placesRouteData.edit_start_label_layer.setContent(la.ROUTE_START), map.addLayer(placesRouteData.edit_start_label_layer)), t.length > 1 && (map.hasLayer(placesRouteData.edit_end_label_layer) ? placesRouteData.edit_end_label_layer.setLatLng(o) : (placesRouteData.edit_end_label_layer = L.tooltip({
            permanent: !0,
            offset: [10, 0],
            direction: "right"
        }), placesRouteData.edit_end_label_layer.setLatLng(o), placesRouteData.edit_end_label_layer.setContent(la.ROUTE_END), map.addLayer(placesRouteData.edit_end_label_layer)))
    }))
}

function placesRouteLatLngsToPointsString(e) {
    for (var t = [], a = 0; a < e.length; a++) {
        var o = e[a],
            i = o.lat,
            s = o.lng;
        t.push(parseFloat(i).toFixed(6) + "," + parseFloat(s).toFixed(6))
    }
    return t.toString()
}

function placesRoutePointsStringToLatLngs(e) {
    var t = e.split(","),
        a = [];
    for (j = 0; j < t.length; j += 2) lat = t[j], lng = t[j + 1], a.push(L.latLng(lat, lng));
    return a
}

function placesRouteProperties(e) {
    if (utilsCheckPrivileges("viewer") && utilsCheckPrivileges("subuser")) switch (e) {
        default: if (1 == gsValues.map_bussy) return;map.doubleClickZoom.disable(),
        gsValues.map_bussy = !0;
        var t = e;placesRouteData.edit_route_id = t,
        document.getElementById("dialog_places_route_name").value = placesRouteData.routes[t].data.name,
        document.getElementById("dialog_places_route_group").value = placesRouteData.routes[t].data.group_id,
        document.getElementById("dialog_places_route_color").value = placesRouteData.routes[t].data.color.substr(1),
        document.getElementById("dialog_places_route_color").style.backgroundColor = placesRouteData.routes[t].data.color,
        document.getElementById("dialog_places_route_visible").checked = strToBoolean(placesRouteData.routes[t].data.visible),
        document.getElementById("dialog_places_route_name_visible").checked = strToBoolean(placesRouteData.routes[t].data.name_visible),
        document.getElementById("dialog_places_route_deviation").value = placesRouteData.routes[t].data.deviation,
        $("#dialog_places_route_properties").dialog("open"),
        placesRouteVisibleOnMap(placesRouteData.edit_route_id, !1);
        var a = placesRouteData.routes[placesRouteData.edit_route_id],
            o = a.data.color,
            i = a.data.points,
            s = placesRoutePointsStringToLatLngs(i);placesRouteData.edit_route_layer = L.polyline(s, {
            color: o,
            fill: !1,
            opacity: .8,
            weight: 3
        }),
        map.addLayer(placesRouteData.edit_route_layer),
        placesRouteFitBounds(t),
        setTimeout(function() {
            placesRouteData.edit_route_layer.enableEdit()
        }, 200);
        if(settingsUserData['access_settings_route']['edit']!=true){
            document.getElementById("placesRoutePropertiesSave").style.display='none';
        }
        break;
        case "cancel":
                map.editTools.stopDrawing(),
            map.off("editable:editing editable:drag"),
            map.removeLayer(placesRouteData.edit_route_layer),
            map.removeLayer(placesRouteData.edit_start_label_layer),
            map.removeLayer(placesRouteData.edit_end_label_layer);
            var a = placesRouteData.routes[placesRouteData.edit_route_id];0 != placesRouteData.edit_route_id && 1 == a.visible && placesRouteVisibleOnMap(placesRouteData.edit_route_id, !0),
            placesRouteData.edit_route_layer = !1,
            placesRouteData.edit_start_label_layer = !1,
            placesRouteData.edit_end_label_layer = !1,
            placesRouteData.edit_route_id = !1,
            gsValues.map_bussy = !1,
            map.doubleClickZoom.enable(),
            $("#dialog_places_route_properties").dialog("close");
            break;
        case "save":
            if(placesRouteData.edit_route_id==false && settingsUserData['access_settings_route']['add']!=true){
                notifyBox("error", la.ERROR, la.NO_PERMISSION);
                return;
            }else if(placesRouteData.edit_route_id!=false && settingsUserData['access_settings_route']['edit']!=true){
                notifyBox("error", la.ERROR, la.NO_PERMISSION);
                return;
            }
                var n = document.getElementById("dialog_places_route_name").value,
                l = document.getElementById("dialog_places_route_group").value,
                d = "#" + document.getElementById("dialog_places_route_color").value,
                r = document.getElementById("dialog_places_route_visible").checked,
                _ = document.getElementById("dialog_places_route_name_visible").checked,
                c = document.getElementById("dialog_places_route_deviation").value;
            if ("" == n) {
                notifyBox("error", la.ERROR, la.NAME_CANT_BE_EMPTY);
                break
            }
            if (0 > c || "" == c) {
                notifyBox("error", la.ERROR, la.DEVIATION_CANT_BE_LESS_THAN_0);
                break
            }
            if (!placesRouteData.edit_route_layer) {
                notifyBox("error", la.ERROR, la.DRAW_ROUTE_ON_MAP_BEFORE_SAVING);
                break
            }
            if (placesRouteData.edit_route_layer.getLatLngs().length < 2) {
                notifyBox("error", la.ERROR, la.DRAW_ROUTE_ON_MAP_BEFORE_SAVING);
                break
            }
            if (placesRouteData.edit_route_layer.getLatLngs().length > 200) return void notifyBox("error", la.ERROR, la.ROUTE_CANT_HAVE_MORE_THAN_NUM_POINTS);
            var s = placesRouteLatLngsToPointsString(placesRouteData.edit_route_layer.getLatLngs());map.editTools.stopDrawing(),
            map.off("editable:editing editable:drag"),
            map.removeLayer(placesRouteData.edit_route_layer),
            map.removeLayer(placesRouteData.edit_start_label_layer),
            map.removeLayer(placesRouteData.edit_end_label_layer),
            0 == placesRouteData.edit_route_id && (placesRouteData.new_route_id += 1);
            var g = {
                cmd: "save_route",
                route_id: placesRouteData.edit_route_id,
                group_id: l,
                route_name: n,
                route_color: d,
                route_visible: r,
                route_name_visible: _,
                route_deviation: c,
                route_points: s
            };$.ajax({
                type: "POST",
                url: "func/fn_places.php",
                data: g,
                success: function(e) {
                    placesRouteData.edit_route_layer = !1, placesRouteData.edit_start_label_layer = !1, placesRouteData.edit_end_label_layer = !1, placesRouteData.edit_route_id = !1, gsValues.map_bussy = !1, map.doubleClickZoom.enable(), $("#dialog_places_route_properties").dialog("close"), "OK" == e ? (placesRouteLoadData(), $("#side_panel_places_route_list_grid").trigger("reloadGrid")) : "ERROR_ROUTE_LIMIT" == e ? notifyBox("error", la.ERROR, la.ROUTE_LIMIT_IS_REACHED) : "NO_PERMISSION" == e && notifyBox("error", la.ERROR, la.NO_PERMISSION)
                }
            })
    }
}

function placesRoutePanTo(e) {
    try {
        var t = placesRouteData.routes[e].route_layer,
            a = t.getBounds().getCenter();
        map.panTo(a)
    } catch (o) {}
}

function placesRouteFitBounds(e) {
    var t = placesRouteData.routes[e].route_layer,
        a = t.getBounds();
    map.fitBounds(a)
}

function placesRouteVisibleOnMap(e, t) {
    var a = placesRouteData.routes[e].route_layer,
        o = placesRouteData.routes[e].label_layer;
    1 == t ? ("true" == placesRouteData.routes[e].data.visible ? mapLayers.places_routes.addLayer(a) : mapLayers.places_routes.removeLayer(a), "true" == placesRouteData.routes[e].data.name_visible ? mapLayers.places_routes.addLayer(o) : mapLayers.places_routes.removeLayer(o)) : (mapLayers.places_routes.removeLayer(a), mapLayers.places_routes.removeLayer(o))
}

function routeGroupVisibleTrigger(e) {
    var t = document.getElementById("route_group_visible_" + e).checked;
    for (var a in placesRouteData.routes) placesRouteData.routes[a].data.group_id == e && (placesGroupData.groups[e].route_visible = t, placesRouteData.routes[a].visible = t, placesSetListCheckbox("route_visible_" + a, t), placesRouteVisibleOnMap(a, t))
}

function placesRouteVisibleAllTrigger() {
    placesRouteVisibleAll(1 == gsValues.map_routes ? !1 : !0)
}

function placesRouteVisibleAll(e) {
    if (gsValues.map_routes = e, 1 == e) {
        for (var t in placesGroupData.groups) placesGroupData.groups[t].route_visible = !0, placesSetListCheckbox("route_group_visible_" + t, !0);
        for (var t in placesRouteData.routes) placesRouteData.routes[t].visible = !0, placesSetListCheckbox("route_visible_" + t, !0), placesRouteVisibleOnMap(t, !0)
    } else {
        for (var t in placesGroupData.groups) placesGroupData.groups[t].route_visible = !1, placesSetListCheckbox("route_group_visible_" + t, !1);
        for (var t in placesRouteData.routes) placesRouteData.routes[t].visible = !1, placesSetListCheckbox("route_visible_" + t, !1);
        placesRouteRemoveAllFromMap()
    }
}

function settingsEventPlaySound() {
    var e = document.getElementById("dialog_settings_event_notify_system_sound_file").value,
        t = new Audio("snd/" + e);
    t.play()
}

function settingsEventProperties(e) {
    switch (e) {
        default: var t = e;settingsEditData.event_id = t,
        document.getElementById("dialog_settings_event_name").value = settingsEventData[t].name,
        document.getElementById("dialog_settings_event_type").value = settingsEventData[t].type,
        document.getElementById("dialog_settings_event_active").checked = strToBoolean(settingsEventData[t].active),
        document.getElementById("dialog_settings_event_duration_from_last_event").checked = strToBoolean(settingsEventData[t].duration_from_last_event),
        document.getElementById("dialog_settings_event_duration_from_last_event_minutes").value = settingsEventData[t].duration_from_last_event_minutes;
        var a = settingsEventData[t].week_days.split(",");document.getElementById("dialog_settings_event_wd_sun").checked = strToBoolean(a[0]),
        document.getElementById("dialog_settings_event_wd_mon").checked = strToBoolean(a[1]),
        document.getElementById("dialog_settings_event_wd_tue").checked = strToBoolean(a[2]),
        document.getElementById("dialog_settings_event_wd_wed").checked = strToBoolean(a[3]),
        document.getElementById("dialog_settings_event_wd_thu").checked = strToBoolean(a[4]),
        document.getElementById("dialog_settings_event_wd_fri").checked = strToBoolean(a[5]),
        document.getElementById("dialog_settings_event_wd_sat").checked = strToBoolean(a[6]),
        document.getElementById("dialog_settings_event_timing").value = (settingsEventData[t].workhour);
        var o = settingsEventData[t].day_time;null != o ? (document.getElementById("dialog_settings_event_dt").checked = o.dt, document.getElementById("dialog_settings_event_dt_mon").checked = o.mon, document.getElementById("dialog_settings_event_dt_mon_from").value = o.mon_from, document.getElementById("dialog_settings_event_dt_mon_to").value = o.mon_to, document.getElementById("dialog_settings_event_dt_tue").checked = o.tue, document.getElementById("dialog_settings_event_dt_tue_from").value = o.tue_from, document.getElementById("dialog_settings_event_dt_tue_to").value = o.tue_to, document.getElementById("dialog_settings_event_dt_wed").checked = o.wed, document.getElementById("dialog_settings_event_dt_wed_from").value = o.wed_from, document.getElementById("dialog_settings_event_dt_wed_to").value = o.wed_to, document.getElementById("dialog_settings_event_dt_thu").checked = o.thu, document.getElementById("dialog_settings_event_dt_thu_from").value = o.thu_from, document.getElementById("dialog_settings_event_dt_thu_to").value = o.thu_to, document.getElementById("dialog_settings_event_dt_fri").checked = o.fri, document.getElementById("dialog_settings_event_dt_fri_from").value = o.fri_from, document.getElementById("dialog_settings_event_dt_fri_to").value = o.fri_to, document.getElementById("dialog_settings_event_dt_sat").checked = o.sat, document.getElementById("dialog_settings_event_dt_sat_from").value = o.sat_from, document.getElementById("dialog_settings_event_dt_sat_to").value = o.sat_to, document.getElementById("dialog_settings_event_dt_sun").checked = o.sun, document.getElementById("dialog_settings_event_dt_sun_from").value = o.sun_from, document.getElementById("dialog_settings_event_dt_sun_to").value = o.sun_to) : settingsEventResetDayTime(),
        settingsEventSwitchDayTime();
        var i = document.getElementById("dialog_settings_event_selected_objects"),
            s = settingsEventData[t].imei.split(",");
        if (multiselectSetValues(i, s), "hbrake_server" == settingsEventData[t].type ||"haccel_server" == settingsEventData[t].type ||"connno" == settingsEventData[t].type || "gpsno" == settingsEventData[t].type || "stopped" == settingsEventData[t].type || "moving" == settingsEventData[t].type || "engidle" == settingsEventData[t].type|| "aconidle" == settingsEventData[t].type|| "tanker_lowfuel" == settingsEventData[t].type|| "vehicle_lowfuel" == settingsEventData[t].type ? (document.getElementById("dialog_settings_event_time_period").disabled = !1, document.getElementById("dialog_settings_event_time_period").value = settingsEventData[t].checked_value) : (document.getElementById("dialog_settings_event_time_period").disabled = !0, document.getElementById("dialog_settings_event_time_period").value = ""), "overspeed" == settingsEventData[t].type || "underspeed" == settingsEventData[t].type ? (document.getElementById("dialog_settings_event_speed_limit").disabled = !1, document.getElementById("dialog_settings_event_speed_limit").value = settingsEventData[t].checked_value) : (document.getElementById("dialog_settings_event_speed_limit").disabled = !0, document.getElementById("dialog_settings_event_speed_limit").value = ""), "param" == settingsEventData[t].type || "sensor" == settingsEventData[t].type ? ($("#settings_event_param_sensor_condition_list_grid").closest(".ui-jqgrid").unblock(), document.getElementById("dialog_settings_event_param_sensor_condition_src").disabled = !1, document.getElementById("dialog_settings_event_param_sensor_condition_cn").disabled = !1, document.getElementById("dialog_settings_event_param_sensor_condition_val").disabled = !1, document.getElementById("dialog_settings_event_param_sensor_condition_add").disabled = !1, "param" == settingsEventData[t].type ? settingsEventParamList() : "sensor" == settingsEventData[t].type && settingsEventSensorList(), settingsEditData.event_condition = settingsEventData[t].checked_value.slice(0), settingsEventConditionList()) : ($("#settings_event_param_sensor_condition_list_grid").closest(".ui-jqgrid").block({
                message: ""
            }), document.getElementById("dialog_settings_event_param_sensor_condition_src").disabled = !0, document.getElementById("dialog_settings_event_param_sensor_condition_cn").disabled = !0, document.getElementById("dialog_settings_event_param_sensor_condition_val").disabled = !0, document.getElementById("dialog_settings_event_param_sensor_condition_add").disabled = !0, settingsEditData.event_condition = [], $("#settings_event_param_sensor_condition_list_grid").clearGridData(!0)), "zone_in" != settingsEventData[t].type && "zone_out" != settingsEventData[t].type) {
            "route_in" == settingsEventData[t].type || "route_out" == settingsEventData[t].type ? (document.getElementById("dialog_settings_event_route_trigger").value = "off", document.getElementById("dialog_settings_event_route_trigger").disabled = !0) : (document.getElementById("dialog_settings_event_route_trigger").value = settingsEventData[t].route_trigger, document.getElementById("dialog_settings_event_route_trigger").disabled = !1), document.getElementById("dialog_settings_event_selected_routes").disabled = !1;
            var n = document.getElementById("dialog_settings_event_selected_routes"),
                l = settingsEventData[t].routes.split(",");
            multiselectSetValues(n, l)
        } else document.getElementById("dialog_settings_event_route_trigger").value = "off",
        document.getElementById("dialog_settings_event_route_trigger").disabled = !0,
        document.getElementById("dialog_settings_event_selected_routes").disabled = !0,
        $("#dialog_settings_event_selected_routes option:selected").removeAttr("selected");
        if ("route_in" != settingsEventData[t].type && "route_out" != settingsEventData[t].type) {
            "zone_in" == settingsEventData[t].type || "zone_out" == settingsEventData[t].type ? (document.getElementById("dialog_settings_event_zone_trigger").value = "off", document.getElementById("dialog_settings_event_zone_trigger").disabled = !0) : (document.getElementById("dialog_settings_event_zone_trigger").value = settingsEventData[t].zone_trigger, document.getElementById("dialog_settings_event_zone_trigger").disabled = !1), document.getElementById("dialog_settings_event_selected_zones").disabled = !1;
            var d = document.getElementById("dialog_settings_event_selected_zones"),
                r = settingsEventData[t].zones.split(",");
            multiselectSetValues(d, r)
        } else document.getElementById("dialog_settings_event_zone_trigger").value = "off",
        document.getElementById("dialog_settings_event_zone_trigger").disabled = !0,
        document.getElementById("dialog_settings_event_selected_zones").disabled = !0,
        $("#dialog_settings_event_selected_zones option:selected").removeAttr("selected");
        var _ = settingsEventData[t].notify_system.split(",");document.getElementById("dialog_settings_event_notify_system").checked = strToBoolean(_[0]),
        document.getElementById("dialog_settings_event_notify_system_hide").checked = strToBoolean(_[1]),
        document.getElementById("dialog_settings_event_notify_system_sound").checked = strToBoolean(_[2]),
        void 0 != _[3] && (document.getElementById("dialog_settings_event_notify_system_sound_file").value = _[3]),
        document.getElementById("dialog_settings_event_notify_email").checked = strToBoolean(settingsEventData[t].notify_email),
        document.getElementById("dialog_settings_event_notify_email_address").value = settingsEventData[t].notify_email_address,
        document.getElementById("dialog_settings_event_notify_sms").checked = strToBoolean(settingsEventData[t].notify_sms),
        document.getElementById("dialog_settings_event_notify_sms_number").value = settingsEventData[t].notify_sms_number,
        document.getElementById("dialog_settings_event_notify_email_template").value = settingsEventData[t].email_template_id,
        document.getElementById("dialog_settings_event_notify_sms_template").value = settingsEventData[t].sms_template_id,
        document.getElementById("dialog_settings_event_notify_arrow").checked = strToBoolean(settingsEventData[t].notify_arrow),
        document.getElementById("dialog_settings_event_notify_arrow_color").value = settingsEventData[t].notify_arrow_color,
        document.getElementById("dialog_settings_event_notify_ohc").checked = strToBoolean(settingsEventData[t].notify_ohc),
        document.getElementById("dialog_settings_event_notify_ohc_color").value = settingsEventData[t].notify_ohc_color,
        document.getElementById("dialog_settings_event_notify_ohc_color").value = settingsEventData[t].notify_ohc_color.substr(1),
        document.getElementById("dialog_settings_event_notify_ohc_color").style.backgroundColor = settingsEventData[t].notify_ohc_color,
        document.getElementById("dialog_settings_event_cmd_send").checked = strToBoolean(settingsEventData[t].cmd_send),
        document.getElementById("dialog_settings_event_cmd_gateway").value = settingsEventData[t].cmd_gateway,
        document.getElementById("dialog_settings_event_cmd_type").value = settingsEventData[t].cmd_type,
        document.getElementById("dialog_settings_event_cmd_string").value = settingsEventData[t].cmd_string;

        if("tanker_lowfuel" == settingsEventData[t].type|| "vehicle_lowfuel" == settingsEventData[t].type){
            document.getElementById('time_period_and_lowfuel_value').innerHTML=la.LOWFUEL;
        }else{
            document.getElementById('time_period_and_lowfuel_value').innerHTML=la.TIME_PERIOD_MIN;
        }

        if(settingsUserData['access_settings_events']['edit']!=true){
            document.getElementById("settingsEventPropertiesSave").style.display = 'none';
        }

        if(settingsEventData[t].type=='haccel_server' || settingsEventData[t].type=='hbrake_server'){
            document.getElementById('time_period_and_lowfuel_value').innerHTML='Value';
        }

        $("#dialog_settings_event_properties").dialog("open");

        
        break;
        case "add":
            if(settingsUserData['access_settings_events']['add']!=true){
                notifyBox("error", la.ERROR, la.NO_PERMISSION);
                return;
            }
            settingsEditData.event_id = !1,
            document.getElementById("dialog_settings_event_name").value = "",
            document.getElementById("dialog_settings_event_type").value = "sos",
            document.getElementById("dialog_settings_event_active").checked = !0,
            document.getElementById("dialog_settings_event_duration_from_last_event").checked = !1,
            document.getElementById("dialog_settings_event_duration_from_last_event_minutes").value = 0,
            document.getElementById("dialog_settings_event_wd_mon").checked = !0,
            document.getElementById("dialog_settings_event_wd_tue").checked = !0,
            document.getElementById("dialog_settings_event_wd_wed").checked = !0,
            document.getElementById("dialog_settings_event_wd_thu").checked = !0,
            document.getElementById("dialog_settings_event_wd_fri").checked = !0,
            document.getElementById("dialog_settings_event_wd_sat").checked = !0,
            document.getElementById("dialog_settings_event_timing").value = "off",
            document.getElementById("dialog_settings_event_wd_sun").checked = !0,
            $("#dialog_settings_event_selected_objects option:selected").removeAttr("selected"),
            document.getElementById("dialog_settings_event_time_period").value = "",
            document.getElementById("dialog_settings_event_speed_limit").value = "",
            document.getElementById("dialog_settings_event_param_sensor_condition_src").value = "",
            document.getElementById("dialog_settings_event_param_sensor_condition_cn").value = "",
            document.getElementById("dialog_settings_event_param_sensor_condition_val").value = "",
            document.getElementById("dialog_settings_event_notify_system").checked = !1,
            document.getElementById("dialog_settings_event_notify_system_hide").checked = !1,
            document.getElementById("dialog_settings_event_notify_system_sound").checked = !1,
            document.getElementById("dialog_settings_event_notify_email").checked = !1,
            document.getElementById("dialog_settings_event_notify_email_address").value = "",
            document.getElementById("dialog_settings_event_notify_sms").checked = !1,
            document.getElementById("dialog_settings_event_notify_sms_number").value = "",
            document.getElementById("dialog_settings_event_notify_email_template").value = 0,
            document.getElementById("dialog_settings_event_notify_sms_template").value = 0,
            document.getElementById("dialog_settings_event_notify_arrow").checked = !1,
            document.getElementById("dialog_settings_event_notify_arrow_color").value = "arrow_yellow",
            document.getElementById("dialog_settings_event_notify_ohc").checked = !1,
            document.getElementById("dialog_settings_event_notify_ohc_color").value = "FFFF00",
            document.getElementById("dialog_settings_event_notify_ohc_color").style.backgroundColor = "#FFFF00",
            document.getElementById("dialog_settings_event_cmd_send").checked = !1,
            document.getElementById("dialog_settings_event_cmd_gateway").value = "gprs",
            document.getElementById("dialog_settings_event_cmd_type").value = "ascii",
            document.getElementById("dialog_settings_event_cmd_string").value = "",
            settingsEventSwitchType(),
            settingsEventResetDayTime(),
            settingsEventSwitchDayTime(),
            $("#dialog_settings_event_properties").dialog("open");
            break;
        case "cancel":
                $("#dialog_settings_event_properties").dialog("close");
            break;
        case "save":
                if (!utilsCheckPrivileges("viewer")) return;
            var c = document.getElementById("dialog_settings_event_type").value,
                g = document.getElementById("dialog_settings_event_name").value,
                m = document.getElementById("dialog_settings_event_active").checked,
                TI = document.getElementById("dialog_settings_event_timing").value,
                u = document.getElementById("dialog_settings_event_duration_from_last_event").checked,
                p = document.getElementById("dialog_settings_event_duration_from_last_event_minutes").value,
                a = String(document.getElementById("dialog_settings_event_wd_sun").checked) + ",";a += String(document.getElementById("dialog_settings_event_wd_mon").checked) + ",",
            a += String(document.getElementById("dialog_settings_event_wd_tue").checked) + ",",
            a += String(document.getElementById("dialog_settings_event_wd_wed").checked) + ",",
            a += String(document.getElementById("dialog_settings_event_wd_thu").checked) + ",",
            a += String(document.getElementById("dialog_settings_event_wd_fri").checked) + ",",
            a += String(document.getElementById("dialog_settings_event_wd_sat").checked);
            var o = {
                dt: document.getElementById("dialog_settings_event_dt").checked,
                mon: document.getElementById("dialog_settings_event_dt_mon").checked,
                mon_from: document.getElementById("dialog_settings_event_dt_mon_from").value,
                mon_to: document.getElementById("dialog_settings_event_dt_mon_to").value,
                tue: document.getElementById("dialog_settings_event_dt_tue").checked,
                tue_from: document.getElementById("dialog_settings_event_dt_tue_from").value,
                tue_to: document.getElementById("dialog_settings_event_dt_tue_to").value,
                wed: document.getElementById("dialog_settings_event_dt_wed").checked,
                wed_from: document.getElementById("dialog_settings_event_dt_wed_from").value,
                wed_to: document.getElementById("dialog_settings_event_dt_wed_to").value,
                thu: document.getElementById("dialog_settings_event_dt_thu").checked,
                thu_from: document.getElementById("dialog_settings_event_dt_thu_from").value,
                thu_to: document.getElementById("dialog_settings_event_dt_thu_to").value,
                fri: document.getElementById("dialog_settings_event_dt_fri").checked,
                fri_from: document.getElementById("dialog_settings_event_dt_fri_from").value,
                fri_to: document.getElementById("dialog_settings_event_dt_fri_to").value,
                sat: document.getElementById("dialog_settings_event_dt_sat").checked,
                sat_from: document.getElementById("dialog_settings_event_dt_sat_from").value,
                sat_to: document.getElementById("dialog_settings_event_dt_sat_to").value,
                sun: document.getElementById("dialog_settings_event_dt_sun").checked,
                sun_from: document.getElementById("dialog_settings_event_dt_sun_from").value,
                sun_to: document.getElementById("dialog_settings_event_dt_sun_to").value
            };

            if(settingsUserData['access_settings_events']['add']!=true && settingsEditData.event_id==false){
                notifyBox("error", la.ERROR, la.NO_PERMISSION);
                return;
            }else if(settingsUserData['access_settings_events']['edit']!=true && settingsEditData.event_id!=false){
                notifyBox("error", la.ERROR, la.NO_PERMISSION);
                return;
            }
            if (o = JSON.stringify(o), "" == g) {
                notifyBox("error", la.ERROR, la.NAME_CANT_BE_EMPTY);
                break
            }
            var i = document.getElementById("dialog_settings_event_selected_objects");
            if (!multiselectIsSelected(i)) {
                notifyBox("error", la.ERROR, la.AT_LEAST_ONE_OBJECT_SELECTED);
                break
            }
            if (imei = multiselectGetValues(i), "sos" == c || "bracon" == c || "bracoff" == c || "mandown" == c || "shock" == c || "tow" == c || "pwrcut" == c || "gpsantcut" == c || "jamming" == c || "lowdc" == c || "lowbat" == c || "connyes" == c || "gpsyes" == c || "haccel" == c || "hbrake" == c || "hcorn" == c || "service" == c || "dtc" == c || "route_in" == c || "route_out" == c || "zone_in" == c || "zone_out" == c) var v = "";
            if ("connno" == c) {
                var v = document.getElementById("dialog_settings_event_time_period").value;
                if ("" == v) {
                    notifyBox("error", la.ERROR, la.ALL_AVAILABLE_FIELDS_SHOULD_BE_FILLED_OUT);
                    break
                }
                5 > v && (v = 5)
            }
            if ("gpsno" == c) {
                var v = document.getElementById("dialog_settings_event_time_period").value;
                if ("" == v) {
                    notifyBox("error", la.ERROR, la.ALL_AVAILABLE_FIELDS_SHOULD_BE_FILLED_OUT);
                    break
                }
                5 > v && (v = 5)
            }
            if ("stopped" == c || "moving" == c || "engidle" == c || "aconidle"==c || "tanker_lowfuel"==c || "vehicle_lowfuel"==c || "hbrake_server"==c || "haccel_server"==c) {
                var v = document.getElementById("dialog_settings_event_time_period").value;
                if ("" == v) {
                    notifyBox("error", la.ERROR, la.ALL_AVAILABLE_FIELDS_SHOULD_BE_FILLED_OUT);
                    break
                }
                1 > v && (v = 1)
            }
            if ("overspeed" == c || "underspeed" == c) {
                var v = document.getElementById("dialog_settings_event_speed_limit").value;
                if ("" == v) {
                    notifyBox("error", la.ERROR, la.ALL_AVAILABLE_FIELDS_SHOULD_BE_FILLED_OUT);
                    break
                }
            }
            if ("param" == c || "sensor" == c) {
                var y = settingsEditData.event_condition;
                if (0 == y.length) {
                    notifyBox("error", la.ERROR, la.AT_LEAST_ONE_CONDITION);
                    break
                }
                var v = JSON.stringify(y)
            }
            var E = "",
                n = "";
            if ("route_in" != c || "route_out" != c) {
                var b = document.getElementById("dialog_settings_event_selected_routes");
                if ("route_in" == c || "route_out" == c) {
                    if (E = "off", !multiselectIsSelected(b)) {
                        notifyBox("error", la.ERROR, la.AT_LEAST_ONE_ROUTE_SELECTED);
                        break
                    }
                } else E = document.getElementById("dialog_settings_event_route_trigger").value;
                n = multiselectGetValues(b)
            }
            var h = "",
                d = "";
            if ("route_in" != c && "route_out" != c) {
                var f = document.getElementById("dialog_settings_event_selected_zones");
                if ("zone_in" == c || "zone_out" == c) {
                    if (h = "off", !multiselectIsSelected(f)) {
                        notifyBox("error", la.ERROR, la.AT_LEAST_ONE_ZONE_SELECTED);
                        break
                    }
                } else h = document.getElementById("dialog_settings_event_zone_trigger").value;
                d = multiselectGetValues(f)
            }
            var _ = document.getElementById("dialog_settings_event_notify_system").checked,
                I = document.getElementById("dialog_settings_event_notify_system_hide").checked,
                D = document.getElementById("dialog_settings_event_notify_system_sound").checked,
                B = document.getElementById("dialog_settings_event_notify_system_sound_file").value;_ = _ + "," + I + "," + D + "," + B;
            var O = document.getElementById("dialog_settings_event_notify_email").checked,
                j = document.getElementById("dialog_settings_event_notify_email_address").value;
            if (1 == O)
                for (var R = j.split(","), T = 0; T < R.length; T++)
                    if (R[T] = R[T].trim(), !isEmailValid(R[T])) return notifyBox("error", la.ERROR, la.THIS_EMAIL_IS_NOT_VALID), !1;
            var k = document.getElementById("dialog_settings_event_notify_sms").checked,
                w = document.getElementById("dialog_settings_event_notify_sms_number").value,
                S = document.getElementById("dialog_settings_event_notify_email_template").value,
                L = document.getElementById("dialog_settings_event_notify_sms_template").value,
                A = document.getElementById("dialog_settings_event_notify_arrow").checked,
                x = document.getElementById("dialog_settings_event_notify_arrow_color").value,
                N = document.getElementById("dialog_settings_event_notify_ohc").checked,
                M = "#" + document.getElementById("dialog_settings_event_notify_ohc_color").value,
                C = document.getElementById("dialog_settings_event_cmd_send").checked,
                P = document.getElementById("dialog_settings_event_cmd_gateway").value,
                U = document.getElementById("dialog_settings_event_cmd_type").value,
                G = document.getElementById("dialog_settings_event_cmd_string").value;
            if (1 == C) {
                if ("" == G) return notifyBox("error", la.ERROR, la.COMMAND_CANT_BE_EMPTY, !0), !1;
                if ("hex" == U && (G = G.toUpperCase(), !isHexValid(G))) return notifyBox("error", la.ERROR, la.COMMAND_HEX_NOT_VALID, !0), !1
            }
            
            if(c=="gpssta" || c=="mainpwrsta" || c=="lowpwr" || c=="ud1" || c=="dooropen" || c=="ud4" || c=="ud5" || c=="crash" || c=="Shake" || c=="fuelstolen" || c=="lowfuel" || c=="fueldiscnt" || c=="stopovrtime" || c=="ud2" || c=="temp_abn"|| c=="digitalfuellevelchange")
            {
            	v="";
            }
            var H = {
                cmd: "save_event",
                event_id: settingsEditData.event_id,
                type: c,
                name: g,
                active: m,
                duration_from_last_event: u,
                duration_from_last_event_minutes: p,
                week_days: a,
                day_time: o,
                imei: imei,
                checked_value: v,
                route_trigger: E,
                zone_trigger: h,
                routes: n,
                zones: d,
                notify_system: _,
                notify_email: O,
                notify_email_address: j,
                notify_sms: k,
                notify_sms_number: w,
                email_template_id: S,
                sms_template_id: L,
                notify_arrow: A,
                notify_arrow_color: x,
                notify_ohc: N,
                notify_ohc_color: M,
                cmd_send: C,
                cmd_gateway: P,
                cmd_type: U,
                cmd_string: G,
                timing:TI
            };$.ajax({
                type: "POST",
                url: "func/fn_settings.events.php",
                data: H,
                cache: !1,
                success: function(e) {
                    "OK" == e ? (settingsReloadEvents(), $("#dialog_settings_event_properties").dialog("close"), notifyBox("info", la.INFORMATION, la.CHANGES_SAVED_SUCCESSFULLY)) : "NO_PERMISSION"==e && notifyBox("error", la.ERROR, la.NO_PERMISSION)
                }
            })
    }
}

function settingsEventDelete(e) {
    if(settingsUserData['access_settings_events']['delete']!=true){
        notifyBox("error", la.ERROR, la.NO_PERMISSION);
        return;
    }
    utilsCheckPrivileges("viewer") && confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE, function(t) {
        if (t) {
            var a = {
                cmd: "delete_event",
                event_id: e
            };
            $.ajax({
                type: "POST",
                url: "func/fn_settings.events.php",
                data: a,
                success: function(e) {
                    "OK" == e ? settingsReloadEvents() : "NO_PERMISSION"==e && notifyBox("error", la.ERROR, la.NO_PERMISSION)
                }
            })
        }
    })
}

function settingsEventDeleteSelected() {
    if(settingsUserData['access_settings_events']['delete']!=true){
        notifyBox("error", la.ERROR, la.NO_PERMISSION);
        return;
    }
    if (utilsCheckPrivileges("viewer")) {
        var e = $("#settings_main_events_event_list_grid").jqGrid("getGridParam", "selarrrow");
        return "" == e ? void notifyBox("error", la.ERROR, la.NO_ITEMS_SELECTED) : void confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE_SELECTED_ITEMS, function(t) {
            if (t) {
                var a = {
                    cmd: "delete_selected_events",
                    items: e
                };
                $.ajax({
                    type: "POST",
                    url: "func/fn_settings.events.php",
                    data: a,
                    success: function(e) {
                        "OK" == e ? settingsReloadEvents() : "NO_PERMISSION"==e && notifyBox("error", la.ERROR, la.NO_PERMISSION)
                    }
                })
            }
        })
    }
}

function settingsEventConditionList() {
    var e = settingsEditData.event_condition,
        t = [],
        a = $("#settings_event_param_sensor_condition_list_grid");
    if (a.clearGridData(!0), 0 != e.length) {
        for (var o = 0; o < e.length; o++) {
            var i = '<a href="#" onclick="settingsEventConditionDel(' + o + ');" title="' + la.DELETE + '"><img src="theme/images/remove3.svg" /></a>';
            t.push({
                src: e[o].src,
                cn: e[o].cn,
                val: e[o].val,
                modify: i
            })
        }
        for (var o = 0; o < t.length; o++) a.jqGrid("addRowData", o, t[o]);
        a.setGridParam({
            sortname: "src",
            sortorder: "asc"
        }).trigger("reloadGrid")
    }
}

function settingsEventConditionAdd() {
    var e = document.getElementById("dialog_settings_event_param_sensor_condition_src").value,
        t = document.getElementById("dialog_settings_event_param_sensor_condition_cn").value,
        a = document.getElementById("dialog_settings_event_param_sensor_condition_val").value;
    if ("" == e || "" == t || "" == a) return void notifyBox("error", la.ERROR, la.ALL_AVAILABLE_FIELDS_SHOULD_BE_FILLED_OUT);
    for (var o = 0; o < settingsEditData.event_condition.length; o++)
        if (settingsEditData.event_condition[o].src == e) return void notifyBox("error", la.ERROR, la.SAME_SOURCE_ITEM_AVAILABLE);
    settingsEditData.event_condition.push({
        src: e,
        cn: t,
        val: a
    }), document.getElementById("dialog_settings_event_param_sensor_condition_src").value = "", document.getElementById("dialog_settings_event_param_sensor_condition_cn").value = "", document.getElementById("dialog_settings_event_param_sensor_condition_val").value = "", settingsEventConditionList()
}

function settingsEventConditionDel(e) {
    settingsEditData.event_condition.splice(e, 1), settingsEventConditionList()
}

function settingsEventParamList() {
    var e = document.getElementById("dialog_settings_event_param_sensor_condition_src");
    e.options.length = 0, e.options.add(new Option("", "")), e.options.add(new Option(la.SPEED.toLowerCase(), "speed"));
    for (var t = getAllParamsArray(), a = 0; a < t.length; a++) e.options.add(new Option(t[a], t[a]));
    sortSelectList(e)
}

function settingsEventSensorList() {
    var e = document.getElementById("dialog_settings_event_param_sensor_condition_src");
    e.options.length = 0;
    var t = getAllSensorsArray();
    e.options.add(new Option("", "")), e.options.add(new Option(la.SPEED, "speed"));
    for (var a = 0; a < t.length; a++) e.options.add(new Option(t[a], t[a]));
    sortSelectList(e)
}

function settingsEventSwitchType() {
    document.getElementById("dialog_settings_event_time_period").value = "", document.getElementById("dialog_settings_event_speed_limit").value = "", $("#dialog_settings_event_selected_routes option:selected").removeAttr("selected"), $("#dialog_settings_event_selected_zones option:selected").removeAttr("selected"), document.getElementById("dialog_settings_event_route_trigger").value = "off", document.getElementById("dialog_settings_event_zone_trigger").value = "off", settingsEditData.event_condition = [], $("#settings_event_param_sensor_condition_list_grid").clearGridData(!0);
    var e = document.getElementById("dialog_settings_event_type").value;
    document.getElementById('time_period_and_lowfuel_value').innerHTML=la.TIME_PERIOD_MIN;
    if(e=='haccel_server' || e=='hbrake_server'){
        document.getElementById('time_period_and_lowfuel_value').innerHTML='Value';
    }
    switch (e) {
        case "sos":
        case "bracon":
        case "bracoff":
        case "mandown":
        case "shock":
        case "tow":
        case "pwrcut":
        case "gpsantcut":
        case "jamming":
        case "lowdc":
        case "lowbat":
        case "connyes":
        case "gpsyes":
        case "haccel":
        case "hbrake":
        case "hcorn":
        case "service":
        case "dtc":
            document.getElementById("dialog_settings_event_time_period").disabled = !0, document.getElementById("dialog_settings_event_speed_limit").disabled = !0, $("#settings_event_param_sensor_condition_list_grid").closest(".ui-jqgrid").block({
                message: ""
            }), document.getElementById("dialog_settings_event_param_sensor_condition_src").disabled = !0, document.getElementById("dialog_settings_event_param_sensor_condition_cn").disabled = !0, document.getElementById("dialog_settings_event_param_sensor_condition_val").disabled = !0, document.getElementById("dialog_settings_event_param_sensor_condition_add").disabled = !0, document.getElementById("dialog_settings_event_route_trigger").disabled = !1, document.getElementById("dialog_settings_event_zone_trigger").disabled = !1, document.getElementById("dialog_settings_event_selected_routes").disabled = !1, document.getElementById("dialog_settings_event_selected_zones").disabled = !1;
            break;
        case "connno":
        case "gpsno":
            document.getElementById("dialog_settings_event_time_period").disabled = !1, document.getElementById("dialog_settings_event_speed_limit").disabled = !0, "" == document.getElementById("dialog_settings_event_time_period").value && (document.getElementById("dialog_settings_event_time_period").value = 60), $("#settings_event_param_sensor_condition_list_grid").closest(".ui-jqgrid").block({
                message: ""
            }), document.getElementById("dialog_settings_event_param_sensor_condition_src").disabled = !0, document.getElementById("dialog_settings_event_param_sensor_condition_cn").disabled = !0, document.getElementById("dialog_settings_event_param_sensor_condition_val").disabled = !0, document.getElementById("dialog_settings_event_param_sensor_condition_add").disabled = !0, document.getElementById("dialog_settings_event_route_trigger").disabled = !1, document.getElementById("dialog_settings_event_zone_trigger").disabled = !1, document.getElementById("dialog_settings_event_selected_routes").disabled = !1, document.getElementById("dialog_settings_event_selected_zones").disabled = !1;
            break;
        case "stopped":
        case "moving":
        case "engidle":
        case "aconidle":
            document.getElementById("dialog_settings_event_time_period").disabled = !1, document.getElementById("dialog_settings_event_speed_limit").disabled = !0, "" == document.getElementById("dialog_settings_event_time_period").value && (document.getElementById("dialog_settings_event_time_period").value = 5), $("#settings_event_param_sensor_condition_list_grid").closest(".ui-jqgrid").block({
                message: ""
            }), document.getElementById("dialog_settings_event_param_sensor_condition_src").disabled = !0, document.getElementById("dialog_settings_event_param_sensor_condition_cn").disabled = !0, document.getElementById("dialog_settings_event_param_sensor_condition_val").disabled = !0, document.getElementById("dialog_settings_event_param_sensor_condition_add").disabled = !0, document.getElementById("dialog_settings_event_route_trigger").disabled = !1, document.getElementById("dialog_settings_event_zone_trigger").disabled = !1, document.getElementById("dialog_settings_event_selected_routes").disabled = !1, document.getElementById("dialog_settings_event_selected_zones").disabled = !1;
            break;
        case "overspeed":
        case "underspeed":
            document.getElementById("dialog_settings_event_time_period").disabled = !0, document.getElementById("dialog_settings_event_speed_limit").disabled = !1, "" == document.getElementById("dialog_settings_event_speed_limit").value && (document.getElementById("dialog_settings_event_speed_limit").value = 60), $("#settings_event_param_sensor_condition_list_grid").closest(".ui-jqgrid").block({
                message: ""
            }), document.getElementById("dialog_settings_event_param_sensor_condition_src").disabled = !0, document.getElementById("dialog_settings_event_param_sensor_condition_cn").disabled = !0, document.getElementById("dialog_settings_event_param_sensor_condition_val").disabled = !0, document.getElementById("dialog_settings_event_param_sensor_condition_add").disabled = !0, document.getElementById("dialog_settings_event_route_trigger").disabled = !1, document.getElementById("dialog_settings_event_zone_trigger").disabled = !1, document.getElementById("dialog_settings_event_selected_routes").disabled = !1, document.getElementById("dialog_settings_event_selected_zones").disabled = !1;
            break;
        case "param":
            document.getElementById("dialog_settings_event_time_period").disabled = !0, document.getElementById("dialog_settings_event_speed_limit").disabled = !0, $("#settings_event_param_sensor_condition_list_grid").closest(".ui-jqgrid").unblock(), document.getElementById("dialog_settings_event_param_sensor_condition_src").disabled = !1, document.getElementById("dialog_settings_event_param_sensor_condition_cn").disabled = !1, document.getElementById("dialog_settings_event_param_sensor_condition_val").disabled = !1, document.getElementById("dialog_settings_event_param_sensor_condition_add").disabled = !1, settingsEventParamList(), document.getElementById("dialog_settings_event_route_trigger").disabled = !1, document.getElementById("dialog_settings_event_zone_trigger").disabled = !1, document.getElementById("dialog_settings_event_selected_routes").disabled = !1, document.getElementById("dialog_settings_event_selected_zones").disabled = !1;
            break;
        case "sensor":
            document.getElementById("dialog_settings_event_time_period").disabled = !0, document.getElementById("dialog_settings_event_speed_limit").disabled = !0, $("#settings_event_param_sensor_condition_list_grid").closest(".ui-jqgrid").unblock(), document.getElementById("dialog_settings_event_param_sensor_condition_src").disabled = !1, document.getElementById("dialog_settings_event_param_sensor_condition_cn").disabled = !1, document.getElementById("dialog_settings_event_param_sensor_condition_val").disabled = !1, document.getElementById("dialog_settings_event_param_sensor_condition_add").disabled = !1, settingsEventSensorList(), document.getElementById("dialog_settings_event_route_trigger").disabled = !1, document.getElementById("dialog_settings_event_zone_trigger").disabled = !1, document.getElementById("dialog_settings_event_selected_routes").disabled = !1, document.getElementById("dialog_settings_event_selected_zones").disabled = !1;
            break;
        case "route_in":
        case "route_out":
            document.getElementById("dialog_settings_event_time_period").disabled = !0, document.getElementById("dialog_settings_event_speed_limit").disabled = !0, $("#settings_event_param_sensor_condition_list_grid").closest(".ui-jqgrid").block({
                message: ""
            }), document.getElementById("dialog_settings_event_param_sensor_condition_src").disabled = !0, document.getElementById("dialog_settings_event_param_sensor_condition_cn").disabled = !0, document.getElementById("dialog_settings_event_param_sensor_condition_val").disabled = !0, document.getElementById("dialog_settings_event_param_sensor_condition_add").disabled = !0, document.getElementById("dialog_settings_event_route_trigger").disabled = !0, document.getElementById("dialog_settings_event_zone_trigger").disabled = !0, document.getElementById("dialog_settings_event_selected_routes").disabled = !1, document.getElementById("dialog_settings_event_selected_zones").disabled = !0;
            break;
        case "zone_in":
        case "zone_out":
            document.getElementById("dialog_settings_event_time_period").disabled = !0, document.getElementById("dialog_settings_event_speed_limit").disabled = !0, $("#settings_event_param_sensor_condition_list_grid").closest(".ui-jqgrid").block({
                message: ""
            }), document.getElementById("dialog_settings_event_param_sensor_condition_src").disabled = !0, document.getElementById("dialog_settings_event_param_sensor_condition_cn").disabled = !0, document.getElementById("dialog_settings_event_param_sensor_condition_val").disabled = !0, document.getElementById("dialog_settings_event_param_sensor_condition_add").disabled = !0, document.getElementById("dialog_settings_event_route_trigger").disabled = !0, document.getElementById("dialog_settings_event_zone_trigger").disabled = !0, document.getElementById("dialog_settings_event_selected_routes").disabled = !0, document.getElementById("dialog_settings_event_selected_zones").disabled = !1;
            break;
        case "tanker_lowfuel":
        case "vehicle_lowfuel":
            document.getElementById('time_period_and_lowfuel_value').innerHTML=la.LOWFUEL;
            document.getElementById("dialog_settings_event_time_period").disabled = !1, document.getElementById("dialog_settings_event_speed_limit").disabled = !0, $("#settings_event_param_sensor_condition_list_grid").closest(".ui-jqgrid").block({
                message: ""
            }), document.getElementById("dialog_settings_event_param_sensor_condition_src").disabled = !0, document.getElementById("dialog_settings_event_param_sensor_condition_cn").disabled = !0, document.getElementById("dialog_settings_event_param_sensor_condition_val").disabled = !0, document.getElementById("dialog_settings_event_param_sensor_condition_add").disabled = !0, document.getElementById("dialog_settings_event_route_trigger").disabled = !0, document.getElementById("dialog_settings_event_zone_trigger").disabled = !0, document.getElementById("dialog_settings_event_selected_routes").disabled = !1, document.getElementById("dialog_settings_event_selected_zones").disabled = !0;
            
            break;
        case "haccel_server":
        case "hbrake_server":
            // document.getElementById('time_period_and_lowfuel_value').innerHTML=la.LOWFUEL;
            document.getElementById("dialog_settings_event_time_period").disabled = !1, document.getElementById("dialog_settings_event_speed_limit").disabled = !0, $("#settings_event_param_sensor_condition_list_grid").closest(".ui-jqgrid").block({
                message: ""
            }), document.getElementById("dialog_settings_event_param_sensor_condition_src").disabled = !0, document.getElementById("dialog_settings_event_param_sensor_condition_cn").disabled = !0, document.getElementById("dialog_settings_event_param_sensor_condition_val").disabled = !0, document.getElementById("dialog_settings_event_param_sensor_condition_add").disabled = !0, document.getElementById("dialog_settings_event_route_trigger").disabled = !0, document.getElementById("dialog_settings_event_zone_trigger").disabled = !0, document.getElementById("dialog_settings_event_selected_routes").disabled = !1, document.getElementById("dialog_settings_event_selected_zones").disabled = !0;
            
            break;
    }
}

function settingsEventResetDayTime() {
    document.getElementById("dialog_settings_event_dt").checked = !1, document.getElementById("dialog_settings_event_dt_mon").checked = !1, document.getElementById("dialog_settings_event_dt_mon_from").value = "00:00", document.getElementById("dialog_settings_event_dt_mon_to").value = "24:00", document.getElementById("dialog_settings_event_dt_tue").checked = !1, document.getElementById("dialog_settings_event_dt_tue_from").value = "00:00", document.getElementById("dialog_settings_event_dt_tue_to").value = "24:00", document.getElementById("dialog_settings_event_dt_wed").checked = !1, document.getElementById("dialog_settings_event_dt_wed_from").value = "00:00", document.getElementById("dialog_settings_event_dt_wed_to").value = "24:00", document.getElementById("dialog_settings_event_dt_thu").checked = !1, document.getElementById("dialog_settings_event_dt_thu_from").value = "00:00", document.getElementById("dialog_settings_event_dt_thu_to").value = "24:00", document.getElementById("dialog_settings_event_dt_fri").checked = !1, document.getElementById("dialog_settings_event_dt_fri_from").value = "00:00", document.getElementById("dialog_settings_event_dt_fri_to").value = "24:00", document.getElementById("dialog_settings_event_dt_sat").checked = !1, document.getElementById("dialog_settings_event_dt_sat_from").value = "00:00", document.getElementById("dialog_settings_event_dt_sat_to").value = "24:00", document.getElementById("dialog_settings_event_dt_sun").checked = !1, document.getElementById("dialog_settings_event_dt_sun_from").value = "00:00", document.getElementById("dialog_settings_event_dt_sun_to").value = "24:00"
}

function settingsEventSwitchDayTime() {
    0 == document.getElementById("dialog_settings_event_dt").checked ? (document.getElementById("dialog_settings_event_dt_mon").disabled = !0, document.getElementById("dialog_settings_event_dt_mon_from").disabled = !0, document.getElementById("dialog_settings_event_dt_mon_to").disabled = !0, document.getElementById("dialog_settings_event_dt_tue").disabled = !0, document.getElementById("dialog_settings_event_dt_tue_from").disabled = !0, document.getElementById("dialog_settings_event_dt_tue_to").disabled = !0, document.getElementById("dialog_settings_event_dt_wed").disabled = !0, document.getElementById("dialog_settings_event_dt_wed_from").disabled = !0, document.getElementById("dialog_settings_event_dt_wed_to").disabled = !0, document.getElementById("dialog_settings_event_dt_thu").disabled = !0, document.getElementById("dialog_settings_event_dt_thu_from").disabled = !0, document.getElementById("dialog_settings_event_dt_thu_to").disabled = !0, document.getElementById("dialog_settings_event_dt_fri").disabled = !0, document.getElementById("dialog_settings_event_dt_fri_from").disabled = !0, document.getElementById("dialog_settings_event_dt_fri_to").disabled = !0, document.getElementById("dialog_settings_event_dt_sat").disabled = !0, document.getElementById("dialog_settings_event_dt_sat_from").disabled = !0, document.getElementById("dialog_settings_event_dt_sat_to").disabled = !0, document.getElementById("dialog_settings_event_dt_sun").disabled = !0, document.getElementById("dialog_settings_event_dt_sun_from").disabled = !0, document.getElementById("dialog_settings_event_dt_sun_to").disabled = !0) : (document.getElementById("dialog_settings_event_dt_mon").disabled = !1, document.getElementById("dialog_settings_event_dt_mon_from").disabled = !1, document.getElementById("dialog_settings_event_dt_mon_to").disabled = !1, document.getElementById("dialog_settings_event_dt_tue").disabled = !1, document.getElementById("dialog_settings_event_dt_tue_from").disabled = !1, document.getElementById("dialog_settings_event_dt_tue_to").disabled = !1, document.getElementById("dialog_settings_event_dt_wed").disabled = !1, document.getElementById("dialog_settings_event_dt_wed_from").disabled = !1, document.getElementById("dialog_settings_event_dt_wed_to").disabled = !1, document.getElementById("dialog_settings_event_dt_thu").disabled = !1, document.getElementById("dialog_settings_event_dt_thu_from").disabled = !1, document.getElementById("dialog_settings_event_dt_thu_to").disabled = !1, document.getElementById("dialog_settings_event_dt_fri").disabled = !1, document.getElementById("dialog_settings_event_dt_fri_from").disabled = !1, document.getElementById("dialog_settings_event_dt_fri_to").disabled = !1, document.getElementById("dialog_settings_event_dt_sat").disabled = !1, document.getElementById("dialog_settings_event_dt_sat_from").disabled = !1, document.getElementById("dialog_settings_event_dt_sat_to").disabled = !1, document.getElementById("dialog_settings_event_dt_sun").disabled = !1, document.getElementById("dialog_settings_event_dt_sun_from").disabled = !1, document.getElementById("dialog_settings_event_dt_sun_to").disabled = !1)
}

function settingsObjectGroupImport() {
    utilsCheckPrivileges("viewer") && (document.getElementById("load_file").addEventListener("change", settingsObjectGroupImportOGRFile, !1), document.getElementById("load_file").click())
}

function settingsObjectGroupExport() {
    if (utilsCheckPrivileges("viewer")) {
        var e = "func/fn_export.php?format=ogr";
        window.location = e
    }
}

function settingsObjectGroupImportOGRFile(e) {
    var t = e.target.files,
        a = new FileReader;
    a.onload = function(e) {
        try {
            var t = $.parseJSON(e.target.result);
            if ("0.1v" == t.ogr) {
                var a = t.groups.length;
                if (0 == a) return void notifyBox("info", la.INFORMATION, la.NOTHING_HAS_BEEN_FOUND_TO_IMPORT);
                var o = sprintf(la.GROUPS_FOUND, a) + " " + la.ARE_YOU_SURE_YOU_WANT_TO_IMPORT;
                confirmDialog(o, function(t) {
                    if (t) {
                        loadingData(!0);
                        var a = {
                            format: "ogr",
                            data: e.target.result
                        };
                        $.ajax({
                            type: "POST",
                            url: "func/fn_import.php",
                            data: a,
                            cache: !1,
                            success: function(e) {
                                loadingData(!1), "OK" == e && settingsReloadObjectGroups()
                            },
                            error: function() {
                                loadingData(!1)
                            }
                        })
                    }
                })
            } else notifyBox("error", la.ERROR, la.INVALID_FILE_FORMAT)
        } catch (i) {
            notifyBox("error", la.ERROR, la.INVALID_FILE_FORMAT)
        }
        document.getElementById("load_file").value = ""
    }, a.readAsText(t[0], "UTF-8"), this.removeEventListener("change", settingsObjectGroupImportOGRFile, !1)
}

function settingsObjectGroupDelete(e) {
    if( settingsUserData['access_settings_group']['delete']!=true){
        notifyBox("error", la.ERROR, la.NO_PERMISSION);
        return;
    }
    utilsCheckPrivileges("viewer") && confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE, function(t) {
        if (t) {
            var a = {
                cmd: "delete_object_group",
                group_id: e
            };
            $.ajax({
                type: "POST",
                url: "func/fn_settings.groups.php",
                data: a,
                success: function(e) {
                    "OK" == e ? settingsReloadObjectGroups() : "NO_PERMISSION"==e && notifyBox("error", la.ERROR, la.NO_PERMISSION)
                }
            })
        }
    })
}

function settingsObjectGroupDeleteSelected() {
     if( settingsUserData['access_settings_group']['delete']!=true){
        notifyBox("error", la.ERROR, la.NO_PERMISSION);
        return;
    }
    if (utilsCheckPrivileges("viewer")) {
        var e = $("#settings_main_object_group_list_grid").jqGrid("getGridParam", "selarrrow");
        return "" == e ? void notifyBox("error", la.ERROR, la.NO_ITEMS_SELECTED) : void confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE_SELECTED_ITEMS, function(t) {
            if (t) {
                var a = {
                    cmd: "delete_selected_object_groups",
                    items: e
                };
                $.ajax({
                    type: "POST",
                    url: "func/fn_settings.groups.php",
                    data: a,
                    success: function(e) {
                        "OK" == e ? settingsReloadObjectGroups() : "NO_PERMISSION"==e && notifyBox("error", la.ERROR, la.NO_PERMISSION)
                    }
                })
            }
        })
    }
}

function settingsObjectGroupProperties(e) {
    switch (e) {
        default: var t = e;settingsEditData.group_id = t,
        document.getElementById("dialog_settings_object_group_name").value = settingsObjectGroupData[t].name,
        document.getElementById("dialog_settings_object_group_desc").value = settingsObjectGroupData[t].desc,
        $("#dialog_settings_object_group_properties").dialog("open");
        break;
        case "add":
            if(settingsUserData['access_settings_group']['add']!=true){
                notifyBox("error", la.ERROR, la.NO_PERMISSION);
                return;
            }
                settingsEditData.group_id = !1,
            document.getElementById("dialog_settings_object_group_name").value = "",
            document.getElementById("dialog_settings_object_group_desc").value = "",
            $("#dialog_settings_object_group_properties").dialog("open");
            break;
        case "cancel":
                $("#dialog_settings_object_group_properties").dialog("close");
            break;
        case "save":
            if(settingsEditData.group_id==false && settingsUserData['access_settings_group']['add']!=true){
                notifyBox("error", la.ERROR, la.NO_PERMISSION);
                return;
            }
            if(settingsEditData.group_id!=false && settingsUserData['access_settings_group']['edit']!=true){
                notifyBox("error", la.ERROR, la.NO_PERMISSION);
                return;
            }
                if (!utilsCheckPrivileges("viewer")) return;
            var a = document.getElementById("dialog_settings_object_group_name").value,
                o = document.getElementById("dialog_settings_object_group_desc").value;
            if ("" == a) {
                notifyBox("error", la.ERROR, la.NAME_CANT_BE_EMPTY);
                break
            }
            var i = {
                cmd: "save_object_group",
                group_id: settingsEditData.group_id,
                group_name: a,
                group_desc: o
            };$.ajax({
                type: "POST",
                url: "func/fn_settings.groups.php",
                data: i,
                cache: !1,
                success: function(e) {
                    "OK" == e ? (settingsReloadObjectGroups(), $("#dialog_settings_object_group_properties").dialog("close"), notifyBox("info", la.INFORMATION, la.CHANGES_SAVED_SUCCESSFULLY)) : "NO_PERMISSION"==e && notifyBox("error", la.ERROR, la.NO_PERMISSION)
                }
            })
    }
}

function settingsObjectDriverImport() {
    utilsCheckPrivileges("viewer") && (document.getElementById("load_file").addEventListener("change", settingsObjectDriverImportODRFile, !1), document.getElementById("load_file").click())
}

function settingsObjectDriverExport() {
    if (utilsCheckPrivileges("viewer")) {
        var e = "func/fn_export.php?format=odr";
        window.location = e
    }
}

function settingsObjectDriverImportODRFile(e) {
    var t = e.target.files,
        a = new FileReader;
    a.onload = function(e) {
        try {
            var t = $.parseJSON(e.target.result);
            if ("0.1v" == t.odr) {
                var a = t.drivers.length;
                if (0 == a) return void notifyBox("info", la.INFORMATION, la.NOTHING_HAS_BEEN_FOUND_TO_IMPORT);
                var o = sprintf(la.DRIVERS_FOUND, a) + " " + la.ARE_YOU_SURE_YOU_WANT_TO_IMPORT;
                confirmDialog(o, function(t) {
                    if (t) {
                        loadingData(!0);
                        var a = {
                            format: "odr",
                            data: e.target.result
                        };
                        $.ajax({
                            type: "POST",
                            url: "func/fn_import.php",
                            data: a,
                            cache: !1,
                            success: function(e) {
                                loadingData(!1), "OK" == e && settingsReloadObjectDrivers()
                            },
                            error: function() {
                                loadingData(!1)
                            }
                        })
                    }
                })
            } else notifyBox("error", la.ERROR, la.INVALID_FILE_FORMAT)
        } catch (i) {
            notifyBox("error", la.ERROR, la.INVALID_FILE_FORMAT)
        }
        document.getElementById("load_file").value = ""
    }, a.readAsText(t[0], "UTF-8"), this.removeEventListener("change", settingsObjectDriverImportODRFile, !1)
}

function settingsObjectDriverDelete(e) {
    utilsCheckPrivileges("viewer") && confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE, function(t) {
        if (t) {
            var a = {
                cmd: "delete_object_driver",
                driver_id: e
            };
            $.ajax({
                type: "POST",
                url: "func/fn_settings.drivers.php",
                data: a,
                success: function(e) {
                    "OK" == e && settingsReloadObjectDrivers()
                }
            })
        }
    })
}

function settingsObjectDriverDeleteSelected() {
    if (utilsCheckPrivileges("viewer")) {
        var e = $("#settings_main_object_driver_list_grid").jqGrid("getGridParam", "selarrrow");
        return "" == e ? void notifyBox("error", la.ERROR, la.NO_ITEMS_SELECTED) : void confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE_SELECTED_ITEMS, function(t) {
            if (t) {
                var a = {
                    cmd: "delete_selected_object_drivers",
                    items: e
                };
                $.ajax({
                    type: "POST",
                    url: "func/fn_settings.drivers.php",
                    data: a,
                    success: function(e) {
                        "OK" == e && settingsReloadObjectDrivers()
                    }
                })
            }
        })
    }
}

function settingsObjectDriverProperties(e) {
    switch (e) {
        default: var t = e;settingsEditData.driver_id = t,
        settingsEditData.driver_img_file = !1;
        var a = document.getElementById("dialog_settings_object_driver_photo");
        "" == settingsObjectDriverData[t].img ? a.src = "img/user-blank.svg" : a.src = "data/user/drivers/" + settingsObjectDriverData[t].img,
        document.getElementById("dialog_settings_object_driver_name").value = settingsObjectDriverData[t].name,
        document.getElementById("dialog_settings_object_driver_assign_id").value = settingsObjectDriverData[t].assign_id,
        document.getElementById("dialog_settings_object_driver_idn").value = settingsObjectDriverData[t].idn,
        document.getElementById("dialog_settings_object_driver_address").value = settingsObjectDriverData[t].address,
        document.getElementById("dialog_settings_object_driver_phone").value = settingsObjectDriverData[t].phone,
        document.getElementById("dialog_settings_object_driver_email").value = settingsObjectDriverData[t].email,
        document.getElementById("dialog_settings_object_driver_desc").value = settingsObjectDriverData[t].desc,
        $("#dialog_settings_object_driver_properties").dialog("open");
        break;
        case "add":
                settingsEditData.driver_id = !1,
            settingsEditData.driver_img_file = !1;
            var a = document.getElementById("dialog_settings_object_driver_photo");a.src = "img/user-blank.svg",
            document.getElementById("dialog_settings_object_driver_name").value = "",
            document.getElementById("dialog_settings_object_driver_assign_id").value = "",
            document.getElementById("dialog_settings_object_driver_idn").value = "",
            document.getElementById("dialog_settings_object_driver_address").value = "",
            document.getElementById("dialog_settings_object_driver_phone").value = "",
            document.getElementById("dialog_settings_object_driver_email").value = "",
            document.getElementById("dialog_settings_object_driver_desc").value = "",
            $("#dialog_settings_object_driver_properties").dialog("open");
            break;
        case "cancel":
                $("#dialog_settings_object_driver_properties").dialog("close");
            break;
        case "save":
                if (!utilsCheckPrivileges("viewer")) return;
            var o = document.getElementById("dialog_settings_object_driver_name").value,
                i = document.getElementById("dialog_settings_object_driver_assign_id").value,
                s = document.getElementById("dialog_settings_object_driver_idn").value,
                n = document.getElementById("dialog_settings_object_driver_address").value,
                l = document.getElementById("dialog_settings_object_driver_phone").value,
                d = document.getElementById("dialog_settings_object_driver_email").value,
                r = document.getElementById("dialog_settings_object_driver_desc").value;
            if ("" == o) {
                notifyBox("error", la.ERROR, la.NAME_CANT_BE_EMPTY);
                break
            }
            var _ = {
                cmd: "save_object_driver",
                driver_id: settingsEditData.driver_id,
                driver_name: o,
                driver_assign_id: i,
                driver_idn: s,
                driver_address: n,
                driver_phone: l,
                driver_email: d,
                driver_desc: r,
                driver_img_file: settingsEditData.driver_img_file
            };$.ajax({
                type: "POST",
                url: "func/fn_settings.drivers.php",
                data: _,
                cache: !1,
                success: function(e) {
                    "OK" == e && (settingsReloadObjectDrivers(), $("#dialog_settings_object_driver_properties").dialog("close"), notifyBox("info", la.INFORMATION, la.CHANGES_SAVED_SUCCESSFULLY))
                }
            })
    }
}

function settingsObjectDriverPhotoDelete() {
    if (utilsCheckPrivileges("viewer")) {
        settingsEditData.driver_img_file = "delete";
        var e = document.getElementById("dialog_settings_object_driver_photo");
        e.src = "img/user-blank.svg"
    }
}

function settingsObjectDriverPhotoUpload() {
    utilsCheckPrivileges("viewer") && (document.getElementById("load_file").addEventListener("change", settingsObjectDriverPhotoUploadFile, !1), document.getElementById("load_file").click())
}

function settingsObjectDriverPhotoUploadFile(e) {
    var t = e.target.files,
        a = new FileReader;
    a.onloadend = function(e) {
        var a = e.target.result;
        if (!t[0].type.match("image/png") && !t[0].type.match("image/jpeg")) return void notifyBox("error", la.ERROR, la.FILE_TYPE_MUST_BE_PNG_OR_JPG);
        var o = new Image;
        o.src = a, o.onload = function() {
            return o.width > 640 || o.height > 480 ? void notifyBox("error", la.ERROR, la.IMAGE_SIZE_SHOULD_NOT_BE_BIGGER_THAN_640_480) : void $.ajax({
                url: "func/fn_upload.php?file=driver_photo",
                type: "POST",
                data: a,
                processData: !1,
                contentType: !1,
                success: function(e) {
                    var t = document.getElementById("dialog_settings_object_driver_photo");
                    t.src = e + "?t=" + (new Date).getTime(), settingsEditData.driver_img_file = !0
                }
            }  )
        }, document.getElementById("load_file").value = ""
    }, a.readAsDataURL(t[0]), this.removeEventListener("change", settingsObjectDriverPhotoUploadFile, !1)
}

function settingsObjectPassengerImport() {
    utilsCheckPrivileges("viewer") && (document.getElementById("load_file").addEventListener("change", settingsObjectPassengerImportOPAFile, !1), document.getElementById("load_file").click())
}

function settingsObjectPassengerExport() {
    if (utilsCheckPrivileges("viewer")) {
        var e = "func/fn_export.php?format=opa";
        window.location = e
    }
}

function settingsObjectPassengerImportOPAFile(e) {
    var t = e.target.files,
        a = new FileReader;
    a.onload = function(e) {
        try {
            var t = $.parseJSON(e.target.result);
            if ("0.1v" == t.opa) {
                var a = t.passengers.length;
                if (0 == a) return void notifyBox("info", la.INFORMATION, la.NOTHING_HAS_BEEN_FOUND_TO_IMPORT);
                var o = sprintf(la.PASSENGERS_FOUND, a) + " " + la.ARE_YOU_SURE_YOU_WANT_TO_IMPORT;
                confirmDialog(o, function(t) {
                    if (t) {
                        loadingData(!0);
                        var a = {
                            format: "opa",
                            data: e.target.result
                        };
                        $.ajax({
                            type: "POST",
                            url: "func/fn_import.php",
                            data: a,
                            cache: !1,
                            success: function(e) {
                                loadingData(!1), "OK" == e && settingsReloadObjectPassengers()
                            },
                            error: function() {
                                loadingData(!1)
                            }
                        })
                    }
                })
            } else notifyBox("error", la.ERROR, la.INVALID_FILE_FORMAT)
        } catch (i) {
            notifyBox("error", la.ERROR, la.INVALID_FILE_FORMAT)
        }
        document.getElementById("load_file").value = ""
    }, a.readAsText(t[0], "UTF-8"), this.removeEventListener("change", settingsObjectPassengerImportOPAFile, !1)
}

function settingsObjectPassengerDelete(e) {
    utilsCheckPrivileges("viewer") && confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE, function(t) {
        if (t) {
            var a = {
                cmd: "delete_object_passenger",
                passenger_id: e
            };
            $.ajax({
                type: "POST",
                url: "func/fn_settings.passengers.php",
                data: a,
                success: function(e) {
                    "OK" == e && settingsReloadObjectPassengers()
                }
            })
        }
    })
}

function settingsObjectPassengerDeleteSelected() {
    if (utilsCheckPrivileges("viewer")) {
        var e = $("#settings_main_object_passenger_list_grid").jqGrid("getGridParam", "selarrrow");
        return "" == e ? void notifyBox("error", la.ERROR, la.NO_ITEMS_SELECTED) : void confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE_SELECTED_ITEMS, function(t) {
            if (t) {
                var a = {
                    cmd: "delete_selected_object_passengers",
                    items: e
                };
                $.ajax({
                    type: "POST",
                    url: "func/fn_settings.passengers.php",
                    data: a,
                    success: function(e) {
                        "OK" == e && settingsReloadObjectPassengers()
                    }
                })
            }
        })
    }
}

function settingsObjectPassengerProperties(e) {
    switch (e) {
        default: var t = e,
            a = {
                cmd: "load_object_passenger_data",
                passenger_id: t
            };$.ajax({
            type: "POST",
            url: "func/fn_settings.passengers.php",
            data: a,
            dataType: "json",
            cache: !1,
            success: function(e) {
                settingsEditData.passenger_id = t, document.getElementById("dialog_settings_object_passenger_name").value = e.name, document.getElementById("dialog_settings_object_passenger_assign_id").value = e.assign_id, document.getElementById("dialog_settings_object_passenger_idn").value = e.idn, document.getElementById("dialog_settings_object_passenger_address").value = e.address, document.getElementById("dialog_settings_object_passenger_phone").value = e.phone, document.getElementById("dialog_settings_object_passenger_email").value = e.email, document.getElementById("dialog_settings_object_passenger_desc").value = e.desc, $("#dialog_settings_object_passenger_properties").dialog("open")
            }
        });
        break;
        case "add":
                settingsEditData.passenger_id = !1,
            document.getElementById("dialog_settings_object_passenger_name").value = "",
            document.getElementById("dialog_settings_object_passenger_assign_id").value = "",
            document.getElementById("dialog_settings_object_passenger_idn").value = "",
            document.getElementById("dialog_settings_object_passenger_address").value = "",
            document.getElementById("dialog_settings_object_passenger_phone").value = "",
            document.getElementById("dialog_settings_object_passenger_email").value = "",
            document.getElementById("dialog_settings_object_passenger_desc").value = "",
            $("#dialog_settings_object_passenger_properties").dialog("open");
            break;
        case "cancel":
                $("#dialog_settings_object_passenger_properties").dialog("close");
            break;
        case "save":
                if (!utilsCheckPrivileges("viewer")) return;
            var o = document.getElementById("dialog_settings_object_passenger_name").value,
                i = document.getElementById("dialog_settings_object_passenger_assign_id").value,
                s = document.getElementById("dialog_settings_object_passenger_idn").value,
                n = document.getElementById("dialog_settings_object_passenger_address").value,
                l = document.getElementById("dialog_settings_object_passenger_phone").value,
                d = document.getElementById("dialog_settings_object_passenger_email").value,
                r = document.getElementById("dialog_settings_object_passenger_desc").value;
            if ("" == o) {
                notifyBox("error", la.ERROR, la.NAME_CANT_BE_EMPTY);
                break
            }
            var a = {
                cmd: "save_object_passenger",
                passenger_id: settingsEditData.passenger_id,
                passenger_name: o,
                passenger_assign_id: i,
                passenger_idn: s,
                passenger_address: n,
                passenger_phone: l,
                passenger_email: d,
                passenger_desc: r
            };$.ajax({
                type: "POST",
                url: "func/fn_settings.passengers.php",
                data: a,
                cache: !1,
                success: function(e) {
                    "OK" == e && (settingsReloadObjectPassengers(), $("#dialog_settings_object_passenger_properties").dialog("close"), notifyBox("info", la.INFORMATION, la.CHANGES_SAVED_SUCCESSFULLY))
                }
            })
    }
}

function settingsObjectTrailerImport() {
    utilsCheckPrivileges("viewer") && (document.getElementById("load_file").addEventListener("change", settingsObjectTrailerImportOTRFile, !1), document.getElementById("load_file").click())
}

function settingsObjectTrailerExport() {
    if (utilsCheckPrivileges("viewer")) {
        var e = "func/fn_export.php?format=otr";
        window.location = e
    }
}

function settingsObjectTrailerImportOTRFile(e) {
    var t = e.target.files,
        a = new FileReader;
    a.onload = function(e) {
        try {
            var t = $.parseJSON(e.target.result);
            if ("0.1v" == t.otr) {
                var a = t.trailers.length;
                if (0 == a) return void notifyBox("info", la.INFORMATION, la.NOTHING_HAS_BEEN_FOUND_TO_IMPORT);
                var o = sprintf(la.TRAILERS_FOUND, a) + " " + la.ARE_YOU_SURE_YOU_WANT_TO_IMPORT;
                confirmDialog(o, function(t) {
                    if (t) {
                        loadingData(!0);
                        var a = {
                            format: "otr",
                            data: e.target.result
                        };
                        $.ajax({
                            type: "POST",
                            url: "func/fn_import.php",
                            data: a,
                            cache: !1,
                            success: function(e) {
                                loadingData(!1), "OK" == e && settingsReloadObjectTrailers()
                            },
                            error: function() {
                                loadingData(!1)
                            }
                        })
                    }
                })
            } else notifyBox("error", la.ERROR, la.INVALID_FILE_FORMAT)
        } catch (i) {
            notifyBox("error", la.ERROR, la.INVALID_FILE_FORMAT)
        }
        document.getElementById("load_file").value = ""
    }, a.readAsText(t[0], "UTF-8"), this.removeEventListener("change", settingsObjectTrailerImportOTRFile, !1)
}

function settingsObjectTrailerDelete(e) {
    utilsCheckPrivileges("viewer") && confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE, function(t) {
        if (t) {
            var a = {
                cmd: "delete_object_trailer",
                trailer_id: e
            };
            $.ajax({
                type: "POST",
                url: "func/fn_settings.trailers.php",
                data: a,
                success: function(e) {
                    "OK" == e && settingsReloadObjectTrailers()
                }
            })
        }
    })
}

function settingsObjectTrailerDeleteSelected() {
    if (utilsCheckPrivileges("viewer")) {
        var e = $("#settings_main_object_trailer_list_grid").jqGrid("getGridParam", "selarrrow");
        return "" == e ? void notifyBox("error", la.ERROR, la.NO_ITEMS_SELECTED) : void confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE_SELECTED_ITEMS, function(t) {
            if (t) {
                var a = {
                    cmd: "delete_selected_object_trailers",
                    items: e
                };
                $.ajax({
                    type: "POST",
                    url: "func/fn_settings.trailers.php",
                    data: a,
                    success: function(e) {
                        "OK" == e && settingsReloadObjectTrailers()
                    }
                })
            }
        })
    }
}

function settingsObjectTrailerProperties(e) {
    switch (e) {
        default: var t = e;settingsEditData.trailer_id = t,
        document.getElementById("dialog_settings_object_trailer_name").value = settingsObjectTrailerData[t].name,
        document.getElementById("dialog_settings_object_trailer_assign_id").value = settingsObjectTrailerData[t].assign_id,
        document.getElementById("dialog_settings_object_trailer_model").value = settingsObjectTrailerData[t].model,
        document.getElementById("dialog_settings_object_trailer_vin").value = settingsObjectTrailerData[t].vin,
        document.getElementById("dialog_settings_object_trailer_plate_number").value = settingsObjectTrailerData[t].plate_number,
        document.getElementById("dialog_settings_object_trailer_desc").value = settingsObjectTrailerData[t].desc,
        $("#dialog_settings_object_trailer_properties").dialog("open");
        break;
        case "add":
                settingsEditData.trailer_id = !1,
            document.getElementById("dialog_settings_object_trailer_name").value = "",
            document.getElementById("dialog_settings_object_trailer_assign_id").value = "",
            document.getElementById("dialog_settings_object_trailer_model").value = "",
            document.getElementById("dialog_settings_object_trailer_vin").value = "",
            document.getElementById("dialog_settings_object_trailer_plate_number").value = "",
            document.getElementById("dialog_settings_object_trailer_desc").value = "",
            $("#dialog_settings_object_trailer_properties").dialog("open");
            break;
        case "cancel":
                $("#dialog_settings_object_trailer_properties").dialog("close");
            break;
        case "save":
                if (!utilsCheckPrivileges("viewer")) return;
            var a = document.getElementById("dialog_settings_object_trailer_name").value,
                o = document.getElementById("dialog_settings_object_trailer_assign_id").value,
                i = document.getElementById("dialog_settings_object_trailer_model").value,
                s = document.getElementById("dialog_settings_object_trailer_vin").value,
                n = document.getElementById("dialog_settings_object_trailer_plate_number").value,
                l = document.getElementById("dialog_settings_object_trailer_desc").value;
            if ("" == a) {
                notifyBox("error", la.ERROR, la.NAME_CANT_BE_EMPTY);
                break
            }
            var d = {
                cmd: "save_object_trailer",
                trailer_id: settingsEditData.trailer_id,
                trailer_name: a,
                trailer_assign_id: o,
                trailer_model: i,
                trailer_vin: s,
                trailer_plate_number: n,
                trailer_desc: l
            };$.ajax({
                type: "POST",
                url: "func/fn_settings.trailers.php",
                data: d,
                cache: !1,
                success: function(e) {
                    "OK" == e && (settingsReloadObjectTrailers(), $("#dialog_settings_object_trailer_properties").dialog("close"), notifyBox("info", la.INFORMATION, la.CHANGES_SAVED_SUCCESSFULLY))
                }
            })
    }
}

function settingsOpen() {
    0 == settingsEditData.sounds_loaded && (initSelectList("sound_list"), settingsEditData.sounds_loaded = !0), loadSettings("user", function() {
        loadSettings("objects", function() {
            $("#settings_main_object_list_grid").trigger("reloadGrid"), $("#dialog_settings").dialog("open")
        })
    })
}

function settingsClose() {
    loadSettings("objects", function() {
        objectReloadData()
    })
}

function settingsOpenUser() {
    settingsOpen(), document.getElementById("settings_main_my_account_tab").click()
}

function settingsReloadUser() {
    setTimeout(function() {
        window.location.reload()
    }, 2e3)
}

function settingsReloadObjects() {
    loadSettings("objects", function() {
        1 != $("#dialog_settings").dialog("isOpen") && objectReloadData()
    }), $("#settings_main_object_list_grid").trigger("reloadGrid")
}

function settingsReloadObjectGroups() {
    loadSettings("object_groups", function() {}), $("#settings_main_object_group_list_grid").trigger("reloadGrid")
}

function settingsReloadObjectDrivers() {
    loadSettings("object_drivers", function() {}), $("#settings_main_object_driver_list_grid").trigger("reloadGrid")
}

function settingsReloadObjectPassengers() {
    $("#settings_main_object_passenger_list_grid").trigger("reloadGrid")
}

function settingsReloadObjectTrailers() {
    loadSettings("object_trailers", function() {}), $("#settings_main_object_trailer_list_grid").trigger("reloadGrid")
}

function settingsReloadEvents() {
    loadSettings("events", function() {}), $("#settings_main_events_event_list_grid").trigger("reloadGrid")
}

function settingsReloadTemplates() {
    loadSettings("templates", function() {}), $("#settings_main_templates_template_list_grid").trigger("reloadGrid")
}

function settingsReloadSubaccounts() {
    loadSettings("subaccounts", function() {}), $("#settings_main_subaccount_list_grid").trigger("reloadGrid")
}

function settingsCheck() {
    var e = document.getElementById("settings_main_dst").checked;
    e ? (document.getElementById("settings_main_dst_start_mmdd").disabled = !1, document.getElementById("settings_main_dst_start_hhmm").disabled = !1, document.getElementById("settings_main_dst_end_mmdd").disabled = !1, document.getElementById("settings_main_dst_end_hhmm").disabled = !1) : (document.getElementById("settings_main_dst_start_mmdd").disabled = !0, document.getElementById("settings_main_dst_start_hhmm").disabled = !0, document.getElementById("settings_main_dst_end_mmdd").disabled = !0, document.getElementById("settings_main_dst_end_hhmm").disabled = !0)
}

function loadSettings(e, t) {
    switch (e) {
        case "cookies":
            var a = getCookie("gs_dragbars");
            void 0 == a && (a = guiDragbars.objects + ";" + guiDragbars.events + ";" + guiDragbars.history + ";" + guiDragbars.bottom_panel), a = a.split(";"), null != a[0] && "" != a[0] && (guiDragbars.objects = a[0]), null != a[1] && "" != a[1] && (guiDragbars.events = a[1]), null != a[2] && "" != a[2] && (guiDragbars.history = a[2]), null != a[3] && "" != a[3] && (guiDragbars.bottom_panel = a[3]);
            var o = getCookie("gs_map");
            void 0 == o && (o = gsValues.map_lat + ";" + gsValues.map_lng + ";" + gsValues.map_zoom + ";" + gsValues.map_layer + ";", o += gsValues.map_objects + ";" + gsValues.map_markers + ";" + gsValues.map_routes + ";" + gsValues.map_zones + ";" + gsValues.map_clusters), o = o.split(";"), "last" == settingsUserData.map_sp && (null != o[0] && "" != o[0] && (gsValues.map_lat = o[0]), null != o[1] && "" != o[1] && (gsValues.map_lng = o[1]), null != o[2] && "" != o[2] && (gsValues.map_zoom = o[2])), null != o[3] && "" != o[3] && (gsValues.map_layer = o[3]), null != o[4] && "" != o[4] && (gsValues.map_objects = strToBoolean(o[4])), null != o[5] && "" != o[5] && (gsValues.map_markers = strToBoolean(o[5])), null != o[6] && "" != o[6] && (gsValues.map_routes = strToBoolean(o[6])), null != o[7] && "" != o[7] && (gsValues.map_zones = strToBoolean(o[7])), null != o[8] && "" != o[8] && (gsValues.map_clusters = strToBoolean(o[8])), t(!0);
            break;
        case "server":
            var i = {
                cmd: "load_server_data"
            };
            $.ajax({
                type: "POST",
                url: "func/fn_settings.php",
                data: i,
                dataType: "json",
                cache: !1,
                success: function(e) {
                    gsValues.url_root = e.url_root, gsValues.map_custom = e.map_custom, gsValues.map_osm = strToBoolean(e.map_osm), gsValues.map_bing = strToBoolean(e.map_bing), gsValues.map_google = strToBoolean(e.map_google), gsValues.map_google_traffic = strToBoolean(e.map_google_traffic), gsValues.map_mapbox = strToBoolean(e.map_mapbox), gsValues.map_yandex = strToBoolean(e.map_yandex), gsValues.map_bing_key = e.map_bing_key, gsValues.map_mapbox_key = e.map_mapbox_key, gsValues.map_layer = e.map_layer, gsValues.map_zoom = e.map_zoom, gsValues.map_lat = e.map_lat, gsValues.map_lng = e.map_lng, gsValues.notify_obj_expire = strToBoolean(e.notify_obj_expire), gsValues.notify_obj_expire_period = e.notify_obj_expire_period, gsValues.notify_account_expire = strToBoolean(e.notify_account_expire), gsValues.notify_account_expire_period = e.notify_account_expire_period, t(!0)
                }
            });
            break;
        case "user":
            var i = {
                cmd: "load_user_data"
            };
            $.ajax({
                type: "POST",
                url: "func/fn_settings.php",
                data: i,
                dataType: "json",
                cache: !1,
                success: function(e) {
            	settingsUserData = e, "subuser" != settingsUserData.privileges && (document.getElementById("settings_main_sms_gateway").checked = strToBoolean(settingsUserData.sms_gateway), "" == settingsUserData.sms_gateway_type && (settingsUserData.sms_gateway_type = "app"), document.getElementById("settings_main_sms_gateway_type").value = settingsUserData.sms_gateway_type, document.getElementById("settings_main_sms_gateway_url").value = settingsUserData.sms_gateway_url, document.getElementById("settings_main_sms_gateway_identifier").value = settingsUserData.sms_gateway_identifier, document.getElementById("settings_main_sms_gateway_total_in_queue").innerHTML = settingsUserData.sms_gateway_total_in_queue, settingsSMSGatewaySwitchType()), document.getElementById("settings_main_chat_notify_sound_file").value = settingsUserData.chat_notify, document.getElementById("settings_main_map_startup_possition").value = settingsUserData.map_sp, document.getElementById("settings_main_map_icon_size").value = settingsUserData.map_is, document.getElementById("settings_main_history_route_color").value = settingsUserData.map_rc.substr(1), document.getElementById("settings_main_history_route_color").style.backgroundColor = settingsUserData.map_rc, document.getElementById("settings_main_history_route_highlight_color").value = settingsUserData.map_rhc.substr(1), document.getElementById("settings_main_history_route_highlight_color").style.backgroundColor = settingsUserData.map_rhc;
    			settingsUserData.groups_collapsed;
    			document.getElementById("settings_main_groups_collapsed_objects").checked = settingsUserData.groups_collapsed.objects, document.getElementById("settings_main_groups_collapsed_markers").checked = settingsUserData.groups_collapsed.markers, document.getElementById("settings_main_groups_collapsed_routes").checked = settingsUserData.groups_collapsed.routes, document.getElementById("settings_main_groups_collapsed_zones").checked = settingsUserData.groups_collapsed.zones, document.getElementById("settings_main_od").value = settingsUserData.od;
    			settingsUserData.ohc;
    			if (document.getElementById("settings_main_ohc_no_connection").checked = settingsUserData.ohc.no_connection, document.getElementById("settings_main_ohc_no_connection_color").value = settingsUserData.ohc.no_connection_color.substr(1), document.getElementById("settings_main_ohc_no_connection_color").style.backgroundColor = settingsUserData.ohc.no_connection_color, document.getElementById("settings_main_ohc_stopped").checked = settingsUserData.ohc.stopped, document.getElementById("settings_main_ohc_stopped_color").value = settingsUserData.ohc.stopped_color.substr(1), document.getElementById("settings_main_ohc_stopped_color").style.backgroundColor = settingsUserData.ohc.stopped_color, document.getElementById("settings_main_ohc_moving").checked = settingsUserData.ohc.moving, document.getElementById("settings_main_ohc_moving_color").value = settingsUserData.ohc.moving_color.substr(1), document.getElementById("settings_main_ohc_moving_color").style.backgroundColor = settingsUserData.ohc.moving_color, document.getElementById("settings_main_ohc_engine_idle").checked = settingsUserData.ohc.engine_idle, document.getElementById("settings_main_ohc_engine_idle_color").value = settingsUserData.ohc.engine_idle_color.substr(1), document.getElementById("settings_main_ohc_engine_idle_color").style.backgroundColor = settingsUserData.ohc.engine_idle_color, document.getElementById("settings_main_language").value = settingsUserData.language, document.getElementById("system_language").value = settingsUserData.language, document.getElementById("settings_main_distance_unit").value = settingsUserData.unit_distance, document.getElementById("settings_main_capacity_unit").value = settingsUserData.unit_capacity, document.getElementById("settings_main_temperature_unit").value = settingsUserData.unit_temperature, document.getElementById("settings_main_currency").value = settingsUserData.currency, document.getElementById("settings_main_timezone").value = settingsUserData.timezone, 11 == settingsUserData.dst_start.length && 11 == settingsUserData.dst_end.length) {
    				document.getElementById("settings_main_dst").checked = strToBoolean(settingsUserData.dst);
    				var a = settingsUserData.dst_start.split(" ");
    				document.getElementById("settings_main_dst_start_mmdd").value = a[0], document.getElementById("settings_main_dst_start_hhmm").value = a[1];
    				var o = settingsUserData.dst_end.split(" ");
    				document.getElementById("settings_main_dst_end_mmdd").value = o[0], document.getElementById("settings_main_dst_end_hhmm").value = o[1]
    			} else document.getElementById("settings_main_dst").checked = !1, document.getElementById("settings_main_dst_start_mmdd").value = "", document.getElementById("settings_main_dst_start_hhmm").value = "00:00", document.getElementById("settings_main_dst_end_mmdd").value = "", document.getElementById("settings_main_dst_end_hhmm").value = "00:00";
    			var i = settingsUserData.info;
    			document.getElementById("settings_main_user_category").value = i.u_cat,
    			document.getElementById("settings_main_user_client_name").value = i.name, 
    			document.getElementById("settings_main_user_client_gstn_no").checked = i.u_gstno_chk;
    			if(i.u_gstno_chk==true){
    				document.getElementById("settings_main_user_client_gstn_no_txt").disabled=false;
    			}else{
    				document.getElementById("settings_main_user_client_gstn_no_txt").disabled=true;
    			}
    		   document.getElementById("settings_main_user_client_gstn_no_txt").value = i.u_gstno, 
    			document.getElementById("settings_main_user_document_img").value = i.u_gstdoc, 
    			document.getElementById("settings_main_user_address").value = i.address, 
    			document.getElementById("settings_main_user_phone_number").value = i.phone1, 
    			document.getElementById("settings_main_user_emailid").value = i.u_mail, 
    			document.getElementById("settings_main_user_bank_name").value = i.u_bname, 
    			document.getElementById("settings_main_user_branck_name").value = i.u_brname, 
    			document.getElementById("settings_main_user_account_no").value = i.u_accno, 
    			document.getElementById("settings_main_user_ifsc").value = i.u_ifsc, 
    			document.getElementById("settings_main_user_billing_name").value = i.u_bill_name, 
    			document.getElementById("settings_main_user_billing_address").value = i.u_bill_add, 
                document.getElementById("settings_main_user_billing_mode").value = i.u_bil_mobe, 
                document.getElementById("settings_main_user_amc_checkbox").checked = i.u_amc_ch, 
                document.getElementById("settings_main_user_amc_checkbox_txt").value = i.u_amc_num, 
    			document.getElementById("settings_main_user_billing_cycle").value = i.u_billingcycle, 
    			document.getElementById("settings_main_user_commercial_mailid").value = i.u_caddress, 
    			document.getElementById("settings_main_user_add_name").value = i.u_aname, 
    			document.getElementById("settings_main_user_add_phone").value = i.u_aphoen, 
    			document.getElementById("settings_main_user_add_emailid").value = i.u_amail, 
    			document.getElementById("settings_main_user_add_designation").value = i.u_adesignation, 
    			document.getElementById("settings_main_user_add_description").value = i.u_adecriptoin, 
    			 settingsCheck(), t(!0)
    			 
            	}
            });
            break;
        case "objects":
            var i = {
                cmd: "load_object_data"
            };
            $.ajax({
                type: "POST",
                url: "func/fn_settings.objects.php",
                data: i,
                dataType: "json",
                cache: !1,
                success: function(e) {
					settingsObjectData = e, settingsEditData.sensor_id = !1, initSelectList("history_object_list"), initSelectList("report_object_list"), initSelectList("gallery_object_list"), initSelectList("cmd_object_list"), initSelectList("cmd_object_listnew"), initSelectList("subaccounts_object_list"), initSelectList("events_object_list"), initSelectList("gallery_object_list"), initSelectList("rilogbook_object_list"), initSelectList("dtc_object_list"),initSelectList("subaccounts_report_list"), loadObjectMapMarkerIcons(), t(!0)
                }
            });
            break;
        case "object_groups":
            var i = {
                cmd: "load_object_group_data"
            };
            $.ajax({
                type: "POST",
                url: "func/fn_settings.groups.php",
                data: i,
                dataType: "json",
                cache: !1,
                success: function(e) {
                    settingsObjectGroupData = e, settingsEditData.group_id = !1, initSelectList("object_group_list"), t(!0)
                }
            });
            break;
        case "object_drivers":
            var i = {
                cmd: "load_object_driver_data"
            };
            $.ajax({
                type: "POST",
                url: "func/fn_settings.drivers.php",
                data: i,
                dataType: "json",
                cache: !1,
                success: function(e) {
                    settingsObjectDriverData = e, settingsEditData.driver_id = !1, initSelectList("object_driver_list"), t(!0)
                }
            });
            break;
        case "object_trailers":
            var i = {
                cmd: "load_object_trailer_data"
            };
            $.ajax({
                type: "POST",
                url: "func/fn_settings.trailers.php",
                data: i,
                dataType: "json",
                cache: !1,
                success: function(e) {
                    settingsObjectTrailerData = e, settingsEditData.trailer_id = !1, initSelectList("object_trailer_list"), t(!0)
                }
            });
            break;
        case "events":
            var i = {
                cmd: "load_event_data"
            };
            $.ajax({
                type: "POST",
                url: "func/fn_settings.events.php",
                data: i,
                dataType: "json",
                cache: !1,
                success: function(e) {
                    settingsEventData = e, settingsEditData.event_id = !1, t(!0)
                }
            });
            break;
        case "templates":
            var i = {
                cmd: "load_template_data"
            };
            $.ajax({
                type: "POST",
                url: "func/fn_settings.templates.php",
                data: i,
                dataType: "json",
                cache: !1,
                success: function(e) {
                    settingsTemplateData = e, settingsEditData.template_id = !1, initSelectList("email_sms_template_list"), t(!0)
                }
            });
            break;
        case "subaccounts":
            var i = {
                cmd: "load_subaccount_data"
            };
            $.ajax({
                type: "POST",
                url: "func/fn_settings.subaccounts.php",
                data: i,
                dataType: "json",
                cache: !1,
                success: function(e) {
                    settingsSubaccountData = e, settingsEditData.subaccount_id = !1, t(!0)
                }
            })
    }
}

function settingsSave() {
    if (utilsCheckPrivileges("viewer")) {
        if ("subuser" != settingsUserData.privileges) var e = document.getElementById("settings_main_sms_gateway").checked,
            t = document.getElementById("settings_main_sms_gateway_type").value,
            a = document.getElementById("settings_main_sms_gateway_url").value,
            o = document.getElementById("settings_main_sms_gateway_identifier").value;
        else var e = "",
            t = "",
            a = "",
            o = "";
        var i = document.getElementById("settings_main_chat_notify_sound_file").value,
            s = document.getElementById("settings_main_map_startup_possition").value,
            n = document.getElementById("settings_main_map_icon_size").value,
            l = "#" + document.getElementById("settings_main_history_route_color").value,
            d = "#" + document.getElementById("settings_main_history_route_highlight_color").value,
            r = {
                objects: document.getElementById("settings_main_groups_collapsed_objects").checked,
                markers: document.getElementById("settings_main_groups_collapsed_markers").checked,
                routes: document.getElementById("settings_main_groups_collapsed_routes").checked,
                zones: document.getElementById("settings_main_groups_collapsed_zones").checked
            };
        r = JSON.stringify(r);
        var _ = document.getElementById("settings_main_od").value,
            c = {
                no_connection: document.getElementById("settings_main_ohc_no_connection").checked,
                no_connection_color: "#" + document.getElementById("settings_main_ohc_no_connection_color").value,
                stopped: document.getElementById("settings_main_ohc_stopped").checked,
                stopped_color: "#" + document.getElementById("settings_main_ohc_stopped_color").value,
                moving: document.getElementById("settings_main_ohc_moving").checked,
                moving_color: "#" + document.getElementById("settings_main_ohc_moving_color").value,
                engine_idle: document.getElementById("settings_main_ohc_engine_idle").checked,
                engine_idle_color: "#" + document.getElementById("settings_main_ohc_engine_idle_color").value
            };
        c = JSON.stringify(c);
        var g = document.getElementById("settings_main_language").value,
            m = document.getElementById("settings_main_distance_unit").value;
        m += "," + document.getElementById("settings_main_capacity_unit").value, m += "," + document.getElementById("settings_main_temperature_unit").value;
        var u = document.getElementById("settings_main_currency").value,
            p = document.getElementById("settings_main_timezone").value,
            v = document.getElementById("settings_main_dst").checked,
            y = document.getElementById("settings_main_dst_start_mmdd").value + " " + document.getElementById("settings_main_dst_start_hhmm").value,
            E = document.getElementById("settings_main_dst_end_mmdd").value + " " + document.getElementById("settings_main_dst_end_hhmm").value;
        0 != v && 11 == y.length && 11 == E.length || (v = !1, y = "", E = "");

            var user_category = document.getElementById('settings_main_user_category').value;
            var user_client_name = document.getElementById('settings_main_user_client_name').value;
            var user_gstno_check = document.getElementById('settings_main_user_client_gstn_no').checked;
            var user_gstno = document.getElementById('settings_main_user_client_gstn_no_txt').value;
            var user_gst_doc = document.getElementById('settings_main_user_document_img').value;
            var user_address = document.getElementById('settings_main_user_address').value;
            var user_mailid = document.getElementById('settings_main_user_emailid').value;
            var user_phone = document.getElementById('settings_main_user_phone_number').value;
            var user_bank_name = document.getElementById('settings_main_user_bank_name').value;
            var user_branch_name = document.getElementById('settings_main_user_branck_name').value;
            var user_accountno = document.getElementById('settings_main_user_account_no').value;
            var user_ifsc = document.getElementById('settings_main_user_ifsc').value;
            var user_billing_name = document.getElementById('settings_main_user_billing_name').value;
            var user_billing_address = document.getElementById('settings_main_user_billing_address').value;
            var user_billing_mode = document.getElementById('settings_main_user_billing_mode').value;
            var user_amc_ch=document.getElementById("settings_main_user_amc_checkbox").value;
            var user_amc_num=document.getElementById("settings_main_user_amc_checkbox_txt").value;
            var user_billingcycle=document.getElementById("settings_main_user_billing_cycle").value;
            var user_commercial_address = document.getElementById('settings_main_user_commercial_mailid').value;
            var user_add_name = document.getElementById('settings_main_user_add_name').value;
            var user_add_phone = document.getElementById('settings_main_user_add_phone').value;
            var user_add_mailid = document.getElementById('settings_main_user_add_emailid').value;
            var user_add_designation = document.getElementById('settings_main_user_add_designation').value;
            var user_add_decription = document.getElementById('settings_main_user_add_description').value;

            T = {
                u_cat:user_category,
                name:user_client_name,
                u_gstno_chk:user_gstno_check,
                u_gstno:user_gstno,
                u_gstdoc:user_gst_doc,
                address:user_address,
                u_mail:user_mailid,
                phone1:user_phone,
                u_bname:user_bank_name,
                u_brname:user_branch_name,
                u_accno:user_accountno,
                u_ifsc:user_ifsc,
                u_bill_name:user_billing_name,
                u_bill_add:user_billing_address,
                u_bil_mobe:user_billing_mode,                
                u_caddress:user_commercial_address,
                u_aname:user_add_name,
                u_aphoen:user_add_phone,
                u_amail:user_add_mailid,
                u_adesignation:user_add_designation,
                u_adecriptoin:user_add_decription,
                u_amc_ch:user_amc_ch,
                u_amc_num:user_amc_num,
                u_billingcycle:user_billingcycle
            };
        T = JSON.stringify(T);
        var k = document.getElementById("settings_main_old_password").value,
            w = document.getElementById("settings_main_new_password").value,
            S = document.getElementById("settings_main_new_password_rep").value;
        if (k.length > 0) {
            if (w.length < 6) return void notifyBox("error", la.ERROR, la.PASSWORD_LENGHT_AT_LEAST);
            if (w != S) return void notifyBox("error", la.ERROR, la.REPEATED_PASSWORD_IS_INCORRECT)
        }
        var L = {
            cmd: "save_user_settings",
            sms_gateway: e,
            sms_gateway_type: t,
            sms_gateway_url: a,
            sms_gateway_identifier: o,
            chat_notify: i,
            map_sp: s,
            map_is: n,
            map_rc: l,
            map_rhc: d,
            groups_collapsed: r,
            od: _,
            ohc: c,
            language: g,
            units: m,
            currency: u,
            timezone: p,
            dst: v,
            dst_start: y,
            dst_end: E,
            info: T,
            old_password: k,
            new_password: w
        };
        $.ajax({
            type: "POST",
            url: "func/fn_settings.php",
            data: L,
            cache: !1,
            success: function(e) {
                "OK" == e ? (settingsReloadUser(), notifyBox("info", la.INFORMATION, la.CHANGES_SAVED_SUCCESSFULLY)) : "ERROR_INCORRECT_PASSWORD" == e && notifyBox("error", la.ERROR, la.INCORRECT_PASSWORD)
            }
        })
    }
}

function settingsSaveCookies() {
    var e = guiDragbars.objects + ";" + guiDragbars.events + ";" + guiDragbars.history + ";" + guiDragbars.bottom_panel;
    if (setCookie("gs_dragbars", e, 30), void 0 != map && map.getZoom() && map.getCenter() && map.getCenter()) {
        var t = map.getCenter().lat + ";" + map.getCenter().lng + ";" + map.getZoom() + ";" + gsValues.map_layer + ";";
        t += gsValues.map_objects + ";" + gsValues.map_markers + ";" + gsValues.map_routes + ";" + gsValues.map_zones + ";" + gsValues.map_clusters, setCookie("gs_map", t, 30)
    }
}

function settingsChatPlaySound() {
    var e = document.getElementById("settings_main_chat_notify_sound_file").value;
    if ("" != e) {
        var t = new Audio("snd/" + e);
        t.play()
    }
}

function settingsSMSGatewayClearQueue() {
    confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_CLEAR_SMS_QUEUE, function(e) {
        if (e) {
            var t = {
                cmd: "clear_sms_queue"
            };
            $.ajax({
                type: "POST",
                url: "func/fn_settings.php",
                data: t,
                success: function(e) {
                    "OK" == e && (document.getElementById("settings_main_sms_gateway_total_in_queue").innerHTML = "0")
                }
            })
        }
    })
}

function settingsSMSGatewaySwitchType() {
    var e = document.getElementById("settings_main_sms_gateway_type").value;
    "app" == e ? (document.getElementById("settings_main_sms_app").style.display = "", document.getElementById("settings_main_sms_http").style.display = "none") : (document.getElementById("settings_main_sms_app").style.display = "none", document.getElementById("settings_main_sms_http").style.display = "")
}

function settingsObjectAdd(e) {
    // if (utilsCheckPrivileges("subuser") && utilsCheckPrivileges("obj_add")) switch (e) {
    if (utilsCheckPrivileges("subuser")) switch (e) {
        case "open":
            if(settingsUserData['access_settings_object']['add']!=true){
                notifyBox("error", la.ERROR, la.NO_PERMISSION);
                return;
            }
        	 document.getElementById("dialog_settings_object_edit_imei").disabled = false,
             clear_form_elements('#settings_object'),
             $('#settings_object').tabs({ active: 0}),
             $('#settings_object').tabs( "option", "disabled", [ 1,2,3,4,5,6,7,8,9]),
             $("#dialog_settings_object_edit").dialog("open"),
             addedit="add";
            //document.getElementById("dialog_settings_object_add_name").value = "",
            //document.getElementById("dialog_settings_object_add_imei").value = "",
            //$("#dialog_settings_object_add").dialog("open");
            break;
        case "cancel":
            $("#dialog_settings_object_add").dialog("close");
            break;
        case "add":
            if(settingsUserData['access_settings_object']['add']!=true){
                notifyBox("error", la.ERROR, la.NO_PERMISSION);
                return;
            }
            if (!utilsCheckPrivileges("viewer")) return;
            var t = document.getElementById("dialog_settings_object_add_name").value,
                a = document.getElementById("dialog_settings_object_add_imei").value;
            if ("" == t) return void notifyBox("error", la.ERROR, la.NAME_CANT_BE_EMPTY);
            if (!isIMEIValid(a)) return void notifyBox("error", la.ERROR, la.IMEI_IS_NOT_VALID);
            var o = {
                cmd: "add_object",
                name: t,
                imei: a
            };
            $.ajax({
                type: "POST",
                url: "func/fn_settings.objects.php",
                data: o,
                cache: !1,
                success: function(e) {
                    "OK" == e ? (settingsReloadObjects(), $("#dialog_settings_object_add").dialog("close"), notifyBox("info", la.INFORMATION, la.CHANGES_SAVED_SUCCESSFULLY)) : "ERROR_IMEI_EXISTS" == e ? notifyBox("error", la.ERROR, la.THIS_IMEI_ALREADY_EXISTS) : "ERROR_OBJECT_LIMIT" == e ? notifyBox("error", la.ERROR, la.OBJECT_LIMIT_IS_REACHED) : "NO_PERMISSION" == e && notifyBox("error", la.ERROR, la.NO_PERMISSION)
                }
            })
    }
}

function settingsObjectClearHistory(e) {
    if(settingsUserData['access_settings_clr_history']['delete']!=true){
        notifyBox("error", la.ERROR, la.NO_PERMISSION);
        return;
    }
    // utilsCheckPrivileges("viewer") && utilsCheckPrivileges("subuser") && utilsCheckPrivileges("obj_history_clear") && confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_CLEAR_HISTORY_EVENTS, function(t) {
    utilsCheckPrivileges("viewer") && utilsCheckPrivileges("subuser") && confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_CLEAR_HISTORY_EVENTS, function(t) {
        if (t) {
            var a = {
                cmd: "clear_history_object",
                imei: e
            };
            $.ajax({
                type: "POST",
                url: "func/fn_settings.objects.php",
                data: a,
                success: function(e) {
                    "OK" == e ? settingsReloadObjects() : "NO_PERMISSION"==e && notifyBox("error", la.ERROR, la.NO_PERMISSION)
                }
            })
        }
    })
}

function settingsObjectDelete(e) {
    if(settingsUserData['access_settings_object']['delete']!=true){
        notifyBox("error", la.ERROR, la.NO_PERMISSION);
        return;
    }
    // utilsCheckPrivileges("viewer") && utilsCheckPrivileges("subuser") && utilsCheckPrivileges("obj_edit") && confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE, function(t) {
    utilsCheckPrivileges("viewer") && utilsCheckPrivileges("subuser") && confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE, function(t) {
    // confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE, function(t) {
        if (t) {
            var a = {
                cmd: "delete_object",
                imei: e
            };
            $.ajax({
                type: "POST",
                url: "func/fn_settings.objects.php",
                data: a,
                success: function(e) {
					if("OK" == e){
                        settingsReloadObjects();
                        // $("#settings_main_events_event_list_grid").trigger("reloadGrid");
                        $("#settings_main_object_list_grid").trigger("reloadGrid");
                    }else if("NO_PERMISSION" == e){
                        notifyBox("error", la.ERROR, la.NO_PERMISSION);
                    }
                }
            })
        }
    })
}

function settingsObjectClearHistorySelected() {
    if(settingsUserData['access_settings_clr_history']['delete']!=true){
        notifyBox("error", la.ERROR, la.NO_PERMISSION);
        return;
    }
    if (utilsCheckPrivileges("viewer") && utilsCheckPrivileges("subuser") && utilsCheckPrivileges("obj_edit")) {
        var e = $("#settings_main_object_list_grid").jqGrid("getGridParam", "selarrrow");
        return "" == e ? void notifyBox("error", la.ERROR, la.NO_ITEMS_SELECTED) : void confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_CLEAR_SELECTED_ITEMS_HISTORY_EVENTS, function(t) {
            if (t) {
                var a = {
                    cmd: "clear_history_selected_objects",
                    items: e
                };
                $.ajax({
                    type: "POST",
                    url: "func/fn_settings.objects.php",
                    data: a,
                    success: function(e) {
                        if("OK" == e){
                            settingsReloadObjects();
                        }else if("NO_PERMISSION" == e){
                            notifyBox("error", la.ERROR, la.NO_PERMISSION);
                        }
                    }
                })
            }
        })
    }
}

function settingsObjectDeleteSelected() {
    if(settingsUserData['access_settings_object']['delete']!=true){
        notifyBox("error", la.ERROR, la.NO_PERMISSION);
        return;
    }
    
    // if (utilsCheckPrivileges("viewer") && utilsCheckPrivileges("subuser") && utilsCheckPrivileges("obj_edit")) {
    if (utilsCheckPrivileges("viewer") && utilsCheckPrivileges("subuser")) {
        var e = $("#settings_main_object_list_grid").jqGrid("getGridParam", "selarrrow");
        return "" == e ? void notifyBox("error", la.ERROR, la.NO_ITEMS_SELECTED) : void confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE_SELECTED_ITEMS, function(t) {
            if (t) {
                var a = {
                    cmd: "delete_selected_objects",
                    items: e
                };
                $.ajax({
                    type: "POST",
                    url: "func/fn_settings.objects.php",
                    data: a,
                    success: function(e) {
                        if("OK" == e){
                            settingsReloadObjects();
                            $("#settings_main_object_list_grid").trigger("reloadGrid");
                        }else if("NO_PERMISSION" == e){
                            notifyBox("error", la.ERROR, la.NO_PERMISSION);
                        }
                    }
                })
            }
        })
    }
}

function settingsObjectDuplicate(e) {
    if (utilsCheckPrivileges("subuser") && utilsCheckPrivileges("obj_add")) switch (e) {
        default: var t = e;settingsEditData.object_duplicate_imei = t,
        document.getElementById("dialog_settings_object_duplicate_name").value = "",
        document.getElementById("dialog_settings_object_duplicate_imei").value = "",
        $("#dialog_settings_object_duplicate").dialog("open");
        break;
        case "duplicate":
            if(settingsUserData['access_settings_duplicate']['edit']!=true){
                notifyBox("error", la.ERROR, la.NO_PERMISSION);
                return;
            }
                if (!utilsCheckPrivileges("viewer")) return;
            var a = settingsEditData.object_duplicate_imei,
                o = document.getElementById("dialog_settings_object_duplicate_name").value,
                t = document.getElementById("dialog_settings_object_duplicate_imei").value;
            if ("" == o) return void notifyBox("error", la.ERROR, la.NAME_CANT_BE_EMPTY);
            if (!isIMEIValid(t)) return void notifyBox("error", la.ERROR, la.IMEI_IS_NOT_VALID);
            var i = {
                cmd: "duplicate_object",
                duplicate_imei: a,
                name: o,
                imei: t
            };$.ajax({
                type: "POST",
                url: "func/fn_settings.objects.php",
                data: i,
                cache: !1,
                success: function(e) {
                    "OK" == e ? (settingsReloadObjects(), $("#dialog_settings_object_duplicate").dialog("close"), notifyBox("info", la.INFORMATION, la.CHANGES_SAVED_SUCCESSFULLY)) : "ERROR_IMEI_EXISTS" == e ? notifyBox("error", la.ERROR, la.THIS_IMEI_ALREADY_EXISTS) : "ERROR_OBJECT_LIMIT" == e ? notifyBox("error", la.ERROR, la.OBJECT_LIMIT_IS_REACHED) : "NO_PERMISSION" == e && notifyBox("error", la.ERROR, la.NO_PERMISSION)
                }
            });
            break;
        case "cancel":
                $("#dialog_settings_object_duplicate").dialog("close")
    }
}

function settingsObjectEdit(e) {
    // if (utilsCheckPrivileges("subuser") && utilsCheckPrivileges("obj_edit")) switch (e) {
    if (utilsCheckPrivileges("subuser")) switch (e) {
        default: var t = e;settingsEditData.object_imei = t;
        if(settingsUserData['access_settings_object']['edit']!=true){
            notifyBox("error", la.ERROR, la.NO_PERMISSION);
            return;
        }
        var a = settingsObjectData[t].group_id;void 0 == settingsObjectGroupData[a] ? document.getElementById("dialog_settings_object_edit_group").value = 0 : document.getElementById("dialog_settings_object_edit_group").value = settingsObjectData[t].group_id;
        var o = settingsObjectData[t].driver_id;
        if (o=='-1'){
            document.getElementById("dialog_settings_object_edit_driver").value = o;
        }else{
            void 0 == settingsObjectDriverData[o] ? document.getElementById("dialog_settings_object_edit_driver").value = 0 : document.getElementById("dialog_settings_object_edit_driver").value = settingsObjectData[t].driver_id;
        }

        var i = settingsObjectData[t].trailer_id;void 0 == settingsObjectTrailerData[i] ? document.getElementById("dialog_settings_object_edit_trailer").value = 0 : document.getElementById("dialog_settings_object_edit_trailer").value = settingsObjectData[t].trailer_id;
        if(settingsUserData.cpanel_privileges!=false){
            document.getElementById("dialog_settings_object_edit_name").value = settingsObjectData[t].name;
        }else{
            document.getElementById("dialog_settings_object_edit_name_user").value = settingsObjectData[t].name;
        }

        if(settingsUserData.cpanel_privileges=="super_admin" && settingsUserData.cpanel_privileges=="admin"){
            document.getElementById('settings_object_accuracy_enable/de').style.display='block';      
        }else{
             document.getElementById('settings_object_accuracy_enable/de').style.display='none';       
        }

        document.getElementById("dialog_settings_object_edit_imei").value = t,
        document.getElementById("dialog_settings_object_edit_device").value = settingsObjectData[t].device,
        document.getElementById("dialog_settings_object_edit_sim_number").value = settingsObjectData[t].sim_number,
        document.getElementById("dialog_settings_object_edit_model").value = settingsObjectData[t].model,
        document.getElementById("dialog_settings_object_edit_vin").value = settingsObjectData[t].vin,
        document.getElementById("dialog_settings_object_edit_plate_number").value = settingsObjectData[t].plate_number,
        document.getElementById("dialog_settings_object_edit_icon").innerHTML = '<img src="' + settingsObjectData[t].icon + '" />',
        settingsEditData.object_icon = settingsObjectData[t].icon;
        var s = settingsObjectData[t].map_arrows;document.getElementById("dialog_settings_object_edit_arrow_no_connection").value = s.arrow_no_connection,
        document.getElementById("dialog_settings_object_edit_arrow_stopped").value = s.arrow_stopped,
        document.getElementById("dialog_settings_object_edit_arrow_moving").value = s.arrow_moving,
        document.getElementById("dialog_settings_object_edit_arrow_engine_idle").value = s.arrow_engine_idle,
        document.getElementById("dialog_settings_object_edit_map_icon").value = settingsObjectData[t].map_icon,
        document.getElementById("dialog_settings_object_edit_tail_color").value = settingsObjectData[t].tail_color.substr(1),
        document.getElementById("dialog_settings_object_edit_tail_color").style.backgroundColor = settingsObjectData[t].tail_color,
        document.getElementById("dialog_settings_object_edit_tail_points").value = settingsObjectData[t].tail_points,
        document.getElementById("dialog_settings_object_edit_fcr_source").value = settingsObjectData[t].fcr.source,
        document.getElementById("dialog_settings_object_edit_fcr_measurement").value = settingsObjectData[t].fcr.measurement,
        document.getElementById("dialog_settings_object_edit_fcr_cost").value = settingsObjectData[t].fcr.cost,
        document.getElementById("dialog_settings_object_edit_fcr_summer").value = settingsObjectData[t].fcr.summer,
        document.getElementById("dialog_settings_object_edit_fcr_winter").value = settingsObjectData[t].fcr.winter,
        document.getElementById("dialog_settings_object_edit_fcr_winter_start").value = settingsObjectData[t].fcr.winter_start,
        document.getElementById("dialog_settings_object_edit_fcr_winter_end").value = settingsObjectData[t].fcr.winter_end,
        settingsObjectEditSwitchFCRMeasurement(),
        document.getElementById("settings_object_accuracy_time_adj").value = settingsObjectData[t].time_adj,
        document.getElementById("settings_object_accuracy_detect_stops").value = settingsObjectData[t].accuracy.stops,
        document.getElementById("settings_object_accuracy_moving_speed").value = settingsObjectData[t].accuracy.min_moving_speed,
        document.getElementById("settings_object_accuracy_idle_speed").value = settingsObjectData[t].accuracy.min_idle_speed,
        document.getElementById("settings_object_accuracy_diff_points").value = settingsObjectData[t].accuracy.min_diff_points,
        document.getElementById("settings_object_accuracy_use_gpslev").checked = settingsObjectData[t].accuracy.use_gpslev,
        document.getElementById("settings_object_accuracy_gpslev").value = settingsObjectData[t].accuracy.min_gpslev,
        document.getElementById("settings_object_accuracy_use_hdop").checked = settingsObjectData[t].accuracy.use_hdop,
        document.getElementById("settings_object_accuracy_hdop").value = settingsObjectData[t].accuracy.max_hdop,
        document.getElementById("settings_object_accuracy_ff").value = settingsObjectData[t].accuracy.min_ff,
        document.getElementById("settings_object_accuracy_ft").value = settingsObjectData[t].accuracy.min_ft,
        document.getElementById("dialog_settings_object_edit_odometer_type").value = settingsObjectData[t].odometer_type,
        document.getElementById("dialog_settings_object_edit_engine_hours_type").value = settingsObjectData[t].engine_hours_type,
        document.getElementById("dialog_settings_object_edit_odometer").value = settingsObjectData[t].odometer,
        document.getElementById("dialog_settings_object_edit_engine_hours").value = settingsObjectData[t].engine_hours,
        settingsEditData.odometer = settingsObjectData[t].odometer,
        settingsEditData.engine_hours = settingsObjectData[t].engine_hours,
        document.getElementById("dialog_settings_object_edit_seat_capacity").value = settingsObjectData[t].seat_capacity,
        document.getElementById("dialog_settings_object_edit_freeze_km").value = settingsObjectData[t].ob_freezkm,
        document.getElementById("dialog_settings_object_edit_vechile_type").value = settingsObjectData[t].vehicle_type,
        document.getElementById("dialog_settings_object_edit_vehclie_status").value = settingsObjectData[t].vehicle_status;

        $("#settings_object_sensor_list_grid").jqGrid("setGridParam", {
            url: "func/fn_settings.sensors.php?cmd=load_object_sensor_list&imei=" + t
        }).trigger("reloadGrid"),
        $("#settings_object_service_list_grid").jqGrid("setGridParam", {
            url: "func/fn_settings.service.php?cmd=load_object_service_list&imei=" + t
        }).trigger("reloadGrid"),
        $("#settings_object_custom_fields_list_grid").jqGrid("setGridParam", {
            url: "func/fn_settings.customfields.php?cmd=load_object_custom_field_list&imei=" + t
        }).trigger("reloadGrid"),
        $("#settings_object_working_hour_list_grid").jqGrid("setGridParam", {
            url: "func/fn_settings.workinghour.php?cmd=load_object_working_hour&imei=" + t
        }).trigger("reloadGrid"),
        $("#settings_object_info_list_grid").jqGrid("setGridParam", {
            url: "func/fn_settings.objects.php?cmd=load_object_info_list&imei=" + t
        }).trigger("reloadGrid"),
        $("#dialog_settings_object_edit").dialog("open");
        $('#settings_object').tabs("enable",1),
    	$('#settings_object').tabs("enable",2),
    	$('#settings_object').tabs("enable",3),
    	$('#settings_object').tabs("enable",4),
    	$('#settings_object').tabs("enable",5),
    	$('#settings_object').tabs("enable",6),
    	$('#settings_object').tabs("enable",7),
    	$('#settings_object').tabs("enable",8),
    	$('#settings_object').tabs("enable",9),
    	$('#difueltype').val(settingsObjectData[t]["fueltype"]),
        $('#difuel1').val(settingsObjectData[t]["fuel1"]),
    	$('#difuel2').val(settingsObjectData[t]["fuel2"]),
    	// $('#difuel3').val(settingsObjectData[t]["fuel3"]),
    	// $('#difuel4').val(settingsObjectData[t]["fuel4"]),
    	$('#ditemp1').val(settingsObjectData[t]["temp1"]),
    	$('#ditemp2').val(settingsObjectData[t]["temp2"]),
    	// $('#ditemp3').val(settingsObjectData[t]["temp3"]),
    	$('#distaffname').val(settingsObjectData[t]["staff"]),
    	$('#dialog_settings_trip_type').val(settingsObjectData[t]["triptype"]),
    	document.getElementById("dialog_settings_object_edit_imei").disabled = true,
    	addedit="edit",
    	changefueltype();
        if(settingsUserData.cpanel_privileges=="super_admin" || settingsUserData.cpanel_privileges=="admin" ||settingsUserData.cpanel_privileges=="manager" || settingsUserData.obj_add=="true" )
        	$('#settings_object').tabs("option", "active", 0);
        else
        	$('#settings_object').tabs("option", "active", 1);

        if(settingsUserData['access_settings_object']['edit']!=true){
            document.getElementById("settingsObjectEditSave").style.display ='none';
        }

        break;
        case "save":
            if(settingsUserData['access_settings_object']['edit']!=true && addedit=="edit"){
                notifyBox("error", la.ERROR, la.NO_PERMISSION);
                return;
            }

            if(settingsUserData['access_settings_object']['add']!=true && addedit=="add"){
                notifyBox("error", la.ERROR, la.NO_PERMISSION);
                return;
            }
            if (!utilsCheckPrivileges("viewer")) return;
            var a = document.getElementById("dialog_settings_object_edit_group").value,
                o = document.getElementById("dialog_settings_object_edit_driver").value,
                i = document.getElementById("dialog_settings_object_edit_trailer").value;
                if(settingsUserData.cpanel_privileges!=false){
                    var n = document.getElementById("dialog_settings_object_edit_name").value;
                }else{
                    var n = document.getElementById("dialog_settings_object_edit_name_user").value;
                }
                l = settingsEditData.object_icon;fileExist(l) || (l = "img/markers/objects/land-truck.svg");
            var s = {
                arrow_no_connection: document.getElementById("dialog_settings_object_edit_arrow_no_connection").value,
                arrow_stopped: document.getElementById("dialog_settings_object_edit_arrow_stopped").value,
                arrow_moving: document.getElementById("dialog_settings_object_edit_arrow_moving").value,
                arrow_engine_idle: document.getElementById("dialog_settings_object_edit_arrow_engine_idle").value
            };s = JSON.stringify(s);
            var d = document.getElementById("dialog_settings_object_edit_map_icon").value,
                t = settingsEditData.object_imei,
                r = document.getElementById("dialog_settings_object_edit_device").value,
                _ = document.getElementById("dialog_settings_object_edit_model").value,
                c = document.getElementById("dialog_settings_object_edit_vin").value,
                g = document.getElementById("dialog_settings_object_edit_plate_number").value,
                m = document.getElementById("dialog_settings_object_edit_sim_number").value,
                u = "#" + document.getElementById("dialog_settings_object_edit_tail_color").value,
                p = document.getElementById("dialog_settings_object_edit_tail_points").value,
                sc = document.getElementById("dialog_settings_object_edit_seat_capacity").value,
                fz = document.getElementById("dialog_settings_object_edit_freeze_km").value,
                vt = document.getElementById("dialog_settings_object_edit_vechile_type").value;
                vs=document.getElementById("dialog_settings_object_edit_vehclie_status").value ;
                v = {
                    source: document.getElementById("dialog_settings_object_edit_fcr_source").value,
                    measurement: document.getElementById("dialog_settings_object_edit_fcr_measurement").value,
                    cost: document.getElementById("dialog_settings_object_edit_fcr_cost").value,
                    summer: document.getElementById("dialog_settings_object_edit_fcr_summer").value,
                    winter: document.getElementById("dialog_settings_object_edit_fcr_winter").value,
                    winter_start: document.getElementById("dialog_settings_object_edit_fcr_winter_start").value,
                    winter_end: document.getElementById("dialog_settings_object_edit_fcr_winter_end").value
                };v = JSON.stringify(v),
            "" == document.getElementById("settings_object_accuracy_moving_speed").value && (document.getElementById("settings_object_accuracy_moving_speed").value = 6),
            "" == document.getElementById("settings_object_accuracy_idle_speed").value && (document.getElementById("settings_object_accuracy_idle_speed").value = 3),
            "" == document.getElementById("settings_object_accuracy_diff_points").value && (document.getElementById("settings_object_accuracy_diff_points").value = 5e-4),
            document.getElementById("settings_object_accuracy_gpslev").value < 1 && (document.getElementById("settings_object_accuracy_gpslev").value = 5),
            document.getElementById("settings_object_accuracy_hdop").value < 1 && (document.getElementById("settings_object_accuracy_hdop").value = 3),
            document.getElementById("settings_object_accuracy_ff").value < 1 && (document.getElementById("settings_object_accuracy_ff").value = 10),
            document.getElementById("settings_object_accuracy_ft").value < 1 && (document.getElementById("settings_object_accuracy_ft").value = 10);
            var y = document.getElementById("settings_object_accuracy_time_adj").value,
                E = {
                    stops: document.getElementById("settings_object_accuracy_detect_stops").value,
                    min_moving_speed: document.getElementById("settings_object_accuracy_moving_speed").value,
                    min_idle_speed: document.getElementById("settings_object_accuracy_idle_speed").value,
                    min_diff_points: document.getElementById("settings_object_accuracy_diff_points").value,
                    use_gpslev: document.getElementById("settings_object_accuracy_use_gpslev").checked,
                    min_gpslev: document.getElementById("settings_object_accuracy_gpslev").value,
                    use_hdop: document.getElementById("settings_object_accuracy_use_hdop").checked,
                    max_hdop: document.getElementById("settings_object_accuracy_hdop").value,
                    min_ff: document.getElementById("settings_object_accuracy_ff").value,
                    min_ft: document.getElementById("settings_object_accuracy_ft").value
                };E = JSON.stringify(E);
            var b = document.getElementById("dialog_settings_object_edit_odometer_type").value,
                h = document.getElementById("dialog_settings_object_edit_engine_hours_type").value,
                f = document.getElementById("dialog_settings_object_edit_odometer").value,
                I = document.getElementById("dialog_settings_object_edit_engine_hours").value;
            if (f == settingsEditData.odometer && (f = !1), I == settingsEditData.engine_hours && (I = !1), "" == n) {
                notifyBox("error", la.ERROR, la.NAME_CANT_BE_EMPTY);
                break
            }
            var ft = document.getElementById("difueltype").value;
            var f1 = document.getElementById("difuel1").value;
            var f2 = document.getElementById("difuel2").value;
            // var f3 = document.getElementById("difuel3").value;
            // var f4 = document.getElementById("difuel4").value;
            var f3='';
            var f4='';
            var t1 = document.getElementById("ditemp1").value;
            var t2 = document.getElementById("ditemp2").value;
            // var t3 = document.getElementById("ditemp3").value;
            var t3='NO';
            var staff = document.getElementById("distaffname").value;
            
            if(addedit!="edit")
        	{
        		t=document.getElementById("dialog_settings_object_edit_imei").value;	
        	}

            if(settingsUserData.cpanel_privileges=="super_admin" || settingsUserData.cpanel_privileges=="admin" ||settingsUserData.cpanel_privileges=="manager"  || settingsUserData.obj_add=="true" )
            {
            	if (t=="" || r=="" || u=="" || ft == "" || t1 == "" || t2 == "" || t3 == "" || staff == "" )
            	{
            		notifyBox("error", la.ERROR, la.PLSFILLALLDETAILS);
            		break
            	}
                // if(ft=="FUEL Sensor" &&( f1 == ""  || f2 == ""  || f3 == ""  || f4 == "" ))
            	if(ft=="FUEL Sensor" &&( f1 == ""  || f2 == ""))
            	{
            		notifyBox("error", la.ERROR, la.PLSFILLALLDETAILS);
            		return;
            	}
            }
            
            var D = {
                cmd: "edit_object",
                group_id: a,
                driver_id: o,
                trailer_id: i,
                name: n,
                imei: t,
                device: r,
                sim_number: m,
                model: _,
                vin: c,
                plate_number: g,
                icon: l,
                map_arrows: s,
                map_icon: d,
                tail_color: u,
                tail_points: p,
                fcr: v,
                time_adj: y,
                accuracy: E,
                odometer_type: b,
                engine_hours_type: h,
                odometer: f,
                engine_hours: I,
                fueltype: ft,
                fuel1: f1,
                fuel2: f2,
                fuel3: f3,
                fuel4: f4,
                temp1: t1,
                temp2: t2,
                temp3: t3,
                staff: staff,
                imeiadd: addedit,
                triptype:$('#dialog_settings_trip_type').val(),
                seat_capacity:sc,
                vechiletype:vt,
                ob_freezkm:fz,
                vehicle_status:vs
            };$.ajax({
                type: "POST",
                url: "func/fn_settings.objects.php",
                data: D,
                cache: !1,
                success: function(e) {
                    if("OK" == e){
                        settingsReloadObjects();
                        $("#dialog_settings_object_edit").dialog("close");
                        notifyBox("info", la.INFORMATION, la.CHANGES_SAVED_SUCCESSFULLY);
                    }else if("NO_PERMISSION" == e){
                        notifyBox("error", la.ERROR, la.NO_PERMISSION);
                    }
                }
            });
            break;
        case "cancel":
        	addedit="";
        	$('#settings_object').tabs("enable",1),
        	$('#settings_object').tabs("enable",2),
        	$('#settings_object').tabs("enable",3),
        	$('#settings_object').tabs("enable",4),
        	$('#settings_object').tabs("enable",5),
        	$('#settings_object').tabs("enable",6),
        	$('#settings_object').tabs("enable",7),
        	$('#settings_object').tabs("enable",8),
        	$("#dialog_settings_object_edit").dialog("close"),
            document.getElementById("dialog_settings_object_edit_imei").disabled = true,
            addedit="";
        	break;
    }
}

function settingsObjectEditSwitchTimeAdj() {
    confirmDialog(la.TIME_ADJ_WARNING, function(e) {
        if (!e) {
            var t = settingsEditData.object_imei;
            document.getElementById("settings_object_accuracy_time_adj").value = settingsObjectData[t].time_adj
        }
    })
}

function settingsObjectEditIcon() {
    $("#dialog_settings_object_edit_select_icon").dialog("open"), settingsObjectEditLoadDefaultIconList(), settingsObjectEditLoadCustomIconList()
}

function settingsObjectEditSelectDefaultIcon(e) {
    settingsEditData.object_icon = e, document.getElementById("dialog_settings_object_edit_icon").innerHTML = '<img src="' + e + '" />', $("#dialog_settings_object_edit_select_icon").dialog("close")
}

function settingsObjectEditSelectCustomIcon(e) {
    settingsEditData.object_icon = e, document.getElementById("dialog_settings_object_edit_icon").innerHTML = '<img src="' + e + '" />', $("#dialog_settings_object_edit_select_icon").dialog("close")
}

function settingsObjectEditLoadDefaultIconList() {
    0 == settingsEditData.default_icons_loaded && $.ajax({
        type: "POST",
        url: "func/fn_files.php",
        data: {
            path: "img/markers/objects"
        },
        dataType: "json",
        success: function(e) {
            var t = '<div class="row2">';
            for (document.getElementById("settings_object_edit_select_icon_default_list").innerHTML = "", i = 0; i < e.length; i++) {
                var a = "img/markers/objects/" + e[i];
                t += '<div class="icon-object-edit">', t += '<a href="#" onclick="settingsObjectEditSelectDefaultIcon(\'' + a + "');\">", t += '<img src="' + a + '" style="padding:5px; width: 32px; height: 32px;"/>', t += "</a>", t += "</div>"
            }
            t += "</div>", document.getElementById("settings_object_edit_select_icon_default_list").innerHTML = t, settingsEditData.default_icons_loaded = !0
        }
    })
}

function settingsObjectEditLoadCustomIconList() {
    0 == settingsEditData.custom_icons_loaded && $.ajax({
        type: "POST",
        url: "func/fn_files.php",
        data: {
            path: "data/user/objects"
        },
        dataType: "json",
        success: function(e) {
            var t = '<div class="row2">';
            for (document.getElementById("settings_object_edit_select_icon_custom_list").innerHTML = "", i = 0; i < e.length; i++) {
                var a = "data/user/objects/" + e[i];
                t += '<div class="icon-object-edit">', t += '<a href="#" onclick="settingsObjectEditSelectCustomIcon(\'' + a + "');\">", t += '<img src="' + a + '" style="padding:5px; width: 32px; height: 32px;"/>', t += "</a>", t += '<div class="icon-custom-delete">', t += '<a href="#" onclick="settingsObjectEditDeleteCustomIcon(\'' + a + "');\">", t += '<img border="0" src="theme/images/remove.svg" width="8px">', t += "</a>", t += "</div>", t += "</div>"
            }
            t += "</div>", document.getElementById("settings_object_edit_select_icon_custom_list").innerHTML = t, settingsEditData.custom_icons_loaded = !0
        }
    })
}

function settingsObjectEditUploadCustomIcon() {
    utilsCheckPrivileges("viewer") && (document.getElementById("load_file").addEventListener("change", settingsObjectEditUploadCustomIconFile, !1), document.getElementById("load_file").click())
}

function settingsObjectEditUploadCustomIconFile(e) {
    var t = e.target.files,
        a = new FileReader;
    a.onloadend = function(e) {
        var a = e.target.result;
        if ("image/png" != t[0].type && "image/svg+xml" != t[0].type) return void notifyBox("error", la.ERROR, la.FILE_TYPE_MUST_BE_PNG_OR_SVG);
        var o = new Image;
        o.src = a, o.onload = function() {
            if (o.src.includes("image/png")) {
                if (32 != o.width || 32 != o.height) return void notifyBox("error", la.ERROR, la.ICON_SIZE_SHOULD_BE_32_32);
                var e = "func/fn_upload.php?file=object_icon_png"
            } else var e = "func/fn_upload.php?file=object_icon_svg";
            $.ajax({
                url: e,
                type: "POST",
                data: a,
                processData: !1,
                contentType: !1,
                success: function() {
                    settingsEditData.custom_icons_loaded = !1, settingsObjectEditLoadCustomIconList()
                }
            })
        }, document.getElementById("load_file").value = ""
    }, a.readAsDataURL(t[0]), this.removeEventListener("change", settingsObjectEditUploadCustomIconFile, !1)
}

function settingsObjectEditDeleteCustomIcon(e) {
    utilsCheckPrivileges("viewer") && confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE_THIS_ICON, function(t) {
        if (t) {
            var a = {
                cmd: "delete_custom_icon",
                file: e
            };
            $.ajax({
                type: "POST",
                url: "func/fn_settings.objects.php",
                data: a,
                success: function(e) {
                    "OK" == e && (settingsEditData.custom_icons_loaded = !1, settingsObjectEditLoadCustomIconList())
                }
            })
        }
    })
}

function settingsObjectEditDeleteAllCustomIcon() {
    utilsCheckPrivileges("viewer") && confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE_ALL_CUSTOM_ICONS, function(e) {
        if (e) {
            var t = {
                cmd: "delete_all_custom_icons"
            };
            $.ajax({
                type: "POST",
                url: "func/fn_settings.objects.php",
                data: t,
                success: function(e) {
                    "OK" == e && (settingsEditData.custom_icons_loaded = !1, settingsObjectEditLoadCustomIconList())
                }
            })
        }
    })
}

function settingsObjectEditSwitchFCRMeasurement() {
    var e = document.getElementById("dialog_settings_object_edit_fcr_measurement").value;
    "l100km" == e ? (document.getElementById("dialog_settings_object_edit_fcr_cost_label").innerHTML = la.COST_PER_LITER, document.getElementById("dialog_settings_object_edit_fcr_summer_label").innerHTML = la.SUMMER_RATE_L100KM, document.getElementById("dialog_settings_object_edit_fcr_winter_label").innerHTML = la.WINTER_RATE_L100KM) : (document.getElementById("dialog_settings_object_edit_fcr_cost_label").innerHTML = la.COST_PER_GALLON, document.getElementById("dialog_settings_object_edit_fcr_summer_label").innerHTML = la.SUMMER_RATE_MPG, document.getElementById("dialog_settings_object_edit_fcr_winter_label").innerHTML = la.WINTER_RATE_MPG)
}

function settingsObjectSensorResultPreview() {
    var e = settingsEditData.object_imei;
    if (void 0 != objectsData[e].data[0]) {
        var t = objectsData[e].data[0].params,
            a = document.getElementById("dialog_settings_object_sensor_type").value,
            o = document.getElementById("dialog_settings_object_sensor_param").value,
            i = document.getElementById("dialog_settings_object_sensor_result_type").value,
            s = document.getElementById("dialog_settings_object_sensor_units").value,
            n = document.getElementById("dialog_settings_object_sensor_text_1").value,
            l = document.getElementById("dialog_settings_object_sensor_text_0").value,
            d = document.getElementById("dialog_settings_object_sensor_formula").value,
            tn = document.getElementById("dialog_settings_object_sensor_tanksize").value,
            r = document.getElementById("dialog_settings_object_sensor_lv").value,
            _ = document.getElementById("dialog_settings_object_sensor_hv").value,
            c = settingsEditData.sensor_calibration;
        1 == c.length && (c = []);
        var g = getParamValue(t, o);
        document.getElementById("dialog_settings_object_sensor_cur_param_val").value = g;
        var m = {
                type: a,
                param: o,
                result_type: i,
                text_1: n,
                text_0: l,
                units: s,
                lv: r,
                hv: _,
                formula: d,
                tanksize:tn,
                calibration: c
            },
            u = getSensorValue(t, m);
        document.getElementById("dialog_settings_object_sensor_result_preview").value = u.value_full
    }
}

function settingsObjectSensorImport() {
    utilsCheckPrivileges("viewer") && (document.getElementById("load_file").addEventListener("change", settingsObjectSensorImportSENFile, !1), document.getElementById("load_file").click())
}

function settingsObjectSensorExport() {
    if (utilsCheckPrivileges("viewer")) {
        var e = settingsEditData.object_imei,
            t = "func/fn_export.php?format=sen&imei=" + e;
        window.location = t
    }
}

function settingsObjectSensorImportSENFile(e) {
    var t = e.target.files,
        a = new FileReader;
    a.onload = function(e) {
        try {
            var t = $.parseJSON(e.target.result);
            if ("0.1v" == t.sen) {
                var a = settingsEditData.object_imei,
                    o = t.sensors.length;
                if (0 == o) return void notifyBox("info", la.INFORMATION, la.NOTHING_HAS_BEEN_FOUND_TO_IMPORT);
                var i = sprintf(la.SENSORS_FOUND, o) + " " + la.ARE_YOU_SURE_YOU_WANT_TO_IMPORT;
                confirmDialog(i, function(t) {
                    if (t) {
                        loadingData(!0);
                        var o = {
                            format: "sen",
                            data: e.target.result,
                            imei: a
                        };
                        $.ajax({
                            type: "POST",
                            url: "func/fn_import.php",
                            data: o,
                            cache: !1,
                            success: function(e) {
                                loadingData(!1), "OK" == e && (settingsReloadObjects(), $("#settings_object_sensor_list_grid").trigger("reloadGrid"))
                            },
                            error: function() {
                                loadingData(!1)
                            }
                        })
                    }
                })
            } else notifyBox("error", la.ERROR, la.INVALID_FILE_FORMAT)
        } catch (s) {
            notifyBox("error", la.ERROR, la.INVALID_FILE_FORMAT)
        }
        document.getElementById("load_file").value = ""
    }, a.readAsText(t[0], "UTF-8"), this.removeEventListener("change", settingsObjectSensorImportSENFile, !1)
}

function settingsObjectClearDetectedSensorCache() {
    var e = settingsEditData.object_imei;
    confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_CLEAR_DETECTED_SENSOR_CACHE, function(t) {
        if (t) {
            var a = {
                cmd: "clear_detected_sensor_cache",
                imei: e
            };
            $.ajax({
                type: "POST",
                url: "func/fn_settings.sensors.php",
                data: a,
                success: function(e) {
                    "OK" == e && (settingsReloadObjects(), $("#settings_object_sensor_list_grid").trigger("reloadGrid"))
                }
            })
        }
    })
}

function settingsObjectSensorProperties(a) {
    var b = settingsEditData.object_imei;
    switch (a) {
        default: var c = a;settingsEditData.sensor_id = c;
        var d = document.getElementById("dialog_settings_object_sensor_param");d.options.length = 0;
        for (var f = getObjectParamsArray(b), i = 0; i < f.length; i++) d.options.add(new Option(f[i], f[i]));document.getElementById("dialog_settings_object_sensor_type").value = settingsObjectData[b].sensors[c].type,
        settingsObjectSensorType(),
        document.getElementById("dialog_settings_object_sensor_result_type").value = settingsObjectData[b].sensors[c].result_type,
        settingsObjectSensorResultType(),
        document.getElementById("dialog_settings_object_sensor_name").value = settingsObjectData[b].sensors[c].name,
        document.getElementById("dialog_settings_object_sensor_param").value = settingsObjectData[b].sensors[c].param,
        document.getElementById("dialog_settings_object_sensor_units").value = settingsObjectData[b].sensors[c].units,
        document.getElementById("dialog_settings_object_sensor_text_1").value = settingsObjectData[b].sensors[c].text_1,
        document.getElementById("dialog_settings_object_sensor_text_0").value = settingsObjectData[b].sensors[c].text_0;
        var h = settingsObjectData[b].sensors[c].result_type;
        "abs" == h || "rel" == h ? (document.getElementById("dialog_settings_object_sensor_data_list").checked = !1, document.getElementById("dialog_settings_object_sensor_popup").checked = !1) : (document.getElementById("dialog_settings_object_sensor_data_list").checked = strToBoolean(settingsObjectData[b].sensors[c].data_list), document.getElementById("dialog_settings_object_sensor_popup").checked = strToBoolean(settingsObjectData[b].sensors[c].popup)),
        "value" != h && "accum" != h && "abs" != h && "rel" != h || (document.getElementById("dialog_settings_object_sensor_formula").value = settingsObjectData[b].sensors[c].formula),
        "percentage" == h ? (document.getElementById("dialog_settings_object_sensor_lv").value = settingsObjectData[b].sensors[c].lv, document.getElementById("dialog_settings_object_sensor_hv").value = settingsObjectData[b].sensors[c].hv) : (document.getElementById("dialog_settings_object_sensor_lv").value = "", document.getElementById("dialog_settings_object_sensor_hv").value = ""),
        document.getElementById("settings_object_sensor_calibration_x").value = "",
        document.getElementById("settings_object_sensor_calibration_y").value = "",
        settingsEditData.sensor_calibration = settingsObjectData[b].sensors[c].calibration.slice(0),
        settingsObjectSensorCalibrationList(),
        document.getElementById("dialog_settings_object_sensor_cur_param_val").value = "",
        document.getElementById("dialog_settings_object_sensor_result_preview").value = "",
        document.getElementById("dialog_settings_object_sensor_tanksize").value = settingsObjectData[b].sensors[c].tanksize,
        $("#dialog_settings_object_sensor_properties").dialog("open"),
        settingsObjectSensorResultPreview();
        break;
        case "add":
                if (settingsEditData.sensor_id = !1, "" != settingsObjectData[b].params) {
                var d = document.getElementById("dialog_settings_object_sensor_param");
                d.options.length = 0;
                for (var f = getObjectParamsArray(b), i = 0; i < f.length; i++) d.options.add(new Option(f[i], f[i]));
                document.getElementById("dialog_settings_object_sensor_type").value = "di", settingsObjectSensorType(), document.getElementById("dialog_settings_object_sensor_result_type").value = "logic", settingsObjectSensorResultType(), document.getElementById("dialog_settings_object_sensor_name").value = "", document.getElementById("dialog_settings_object_sensor_param").value = "", document.getElementById("dialog_settings_object_sensor_data_list").checked = !0, document.getElementById("dialog_settings_object_sensor_popup").checked = !1, document.getElementById("dialog_settings_object_sensor_cur_param_val").value = "", document.getElementById("dialog_settings_object_sensor_result_preview").value = "", $("#dialog_settings_object_sensor_properties").dialog("open")
            } else notifyBox("info", la.INFORMATION, la.SENSOR_PARAMETERS_ARE_NOT_DETECTED_FOR_THIS_GPS_DEVICE);
            break;
        case "cancel":
                $("#dialog_settings_object_sensor_properties").dialog("close");
            break;
        case "save":
                if (!utilsCheckPrivileges("viewer")) return;
            var j = document.getElementById("dialog_settings_object_sensor_name").value,
                type = document.getElementById("dialog_settings_object_sensor_type").value,
                param = document.getElementById("dialog_settings_object_sensor_param").value,
                data_list = document.getElementById("dialog_settings_object_sensor_data_list").checked,
                popup = document.getElementById("dialog_settings_object_sensor_popup").checked,
                h = document.getElementById("dialog_settings_object_sensor_result_type").value,
                units = document.getElementById("dialog_settings_object_sensor_units").value,
                text_1 = document.getElementById("dialog_settings_object_sensor_text_1").value,
                text_0 = document.getElementById("dialog_settings_object_sensor_text_0").value,
                lv = document.getElementById("dialog_settings_object_sensor_lv").value,
                hv = document.getElementById("dialog_settings_object_sensor_hv").value,
                formula = document.getElementById("dialog_settings_object_sensor_formula").value;
                tanksize = document.getElementById("dialog_settings_object_sensor_tanksize").value;
            if (("" == j || "" == param) && "abs" == h) {
                notifyBox("error", la.ERROR, la.ALL_AVAILABLE_FIELDS_SHOULD_BE_FILLED_OUT);
                break
            }            
            if (("" == j || "" == param) && "rel" == h) {
                notifyBox("error", la.ERROR, la.ALL_AVAILABLE_FIELDS_SHOULD_BE_FILLED_OUT);
                break
            }
            if (("" == j || "" == param || "" == text_1 || "" == text_0) && "logic" == h) {
                notifyBox("error", la.ERROR, la.ALL_AVAILABLE_FIELDS_SHOULD_BE_FILLED_OUT);
                break
            }
            if (("" == j || "" == param) && "value" == h) {
                notifyBox("error", la.ERROR, la.ALL_AVAILABLE_FIELDS_SHOULD_BE_FILLED_OUT);
                break
            }
            if (("" == j || "" == param) && "string" == h) {
                notifyBox("error", la.ERROR, la.ALL_AVAILABLE_FIELDS_SHOULD_BE_FILLED_OUT);
                break
            }
            if (("" == j || "" == param || "" == lv || "" == hv) && "percentage" == h) {
                notifyBox("error", la.ERROR, la.ALL_AVAILABLE_FIELDS_SHOULD_BE_FILLED_OUT);
                break
            }
            if ("" != formula) {
                formula = formula.toLowerCase(), formula = formula.replace(/[^-()\d/*+.x]/g, "");
                var k = formula.replace("x", "1");
                try {
                    eval(k)
                } catch (e) {
                    if (e instanceof SyntaxError) {
                        notifyBox("error", la.ERROR, la.FORMULA_IS_NOT);
                        break
                    }
                    notifyBox("error", la.ERROR, la.FORMULA_IS_NOT);
                    break
                }
                if (!formula.match("x")) {
                    notifyBox("error", la.ERROR, la.FORMULA_IS_NOT);
                    break
                }
            }
            if (0 == settingsEditData.sensor_id) {
                if (0 != getSensorFromType(b, "acc") && "acc" == type) {
                    notifyBox("error", la.ERROR, la.IGNITION_SENSOR_IS_ALREADY_AVAILABLE);
                    break
                }
                if (0 != getSensorFromType(b, "da") && "da" == type) {
                    notifyBox("error", la.ERROR, la.DRIVER_ASSIGN_SENSOR_IS_ALREADY_AVAILABLE);
                    break
                }
                if (0 != getSensorFromType(b, "engh") && "engh" == type) {
                    notifyBox("error", la.ERROR, la.ENGINE_HOURS_SENSOR_IS_ALREADY_AVAILABLE);
                    break
                }
                if (0 != getSensorFromType(b, "fuelcons") && "fuelcons" == type) {
                    notifyBox("error", la.ERROR, la.FUEL_CONSUMPTION_SENSOR_IS_ALREADY_AVAILABLE);
                    break
                }
                if (0 != getSensorFromType(b, "odo") && "odo" == type) {
                    notifyBox("error", la.ERROR, la.ODOMETER_SENSOR_IS_ALREADY_AVAILABLE);
                    break
                }
                if (0 != getSensorFromType(b, "pa") && "pa" == type) {
                    notifyBox("error", la.ERROR, la.PASSENGER_ASSIGN_SENSOR_IS_ALREADY_AVAILABLE);
                    break
                }
                if (0 != getSensorFromType(b, "ta") && "ta" == type) {
                    notifyBox("error", la.ERROR, la.TRAILER_ASSIGN_SENSOR_IS_ALREADY_AVAILABLE);
                    break
                }
            }
            var l = settingsEditData.sensor_calibration;
            if (1 == l.length) {
                notifyBox("error", la.ERROR, la.AT_LEAST_TWO_CALIBRATION_POINTS);
                break
            }
            var l = JSON.stringify(l),
                data = {
                    cmd: "save_object_sensor",
                    sensor_id: settingsEditData.sensor_id,
                    imei: b,
                    name: j,
                    type: type,
                    param: param,
                    data_list: data_list,
                    popup: popup,
                    result_type: h,
                    text_1: text_1,
                    text_0: text_0,
                    units: units,
                    lv: lv,
                    hv: hv,
                    formula: formula,
                    tanksize:tanksize,
                    calibration: l
                };$.ajax({
                type: "POST",
                url: "func/fn_settings.sensors.php",
                data: data,
                cache: !1,
                success: function(e) {
                    "OK" == e && (settingsReloadObjects(), $("#settings_object_sensor_list_grid").trigger("reloadGrid"), $("#dialog_settings_object_sensor_properties").dialog("close"), notifyBox("info", la.INFORMATION, la.CHANGES_SAVED_SUCCESSFULLY))
                }
            })
    }
}

function settingsObjectSensorDelete(e) {
    if (utilsCheckPrivileges("viewer")) {
        var t = settingsEditData.object_imei;
        confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE, function(a) {
            if (a) {
                var o = {
                    cmd: "delete_object_sensor",
                    sensor_id: e,
                    imei: t
                };
                $.ajax({
                    type: "POST",
                    url: "func/fn_settings.sensors.php",
                    data: o,
                    success: function(e) {
                        "OK" == e && (settingsReloadObjects(), $("#settings_object_sensor_list_grid").trigger("reloadGrid"))
                    }
                })
            }
        })
    }
}

function settingsObjectSensorDeleteSelected() {
    if (utilsCheckPrivileges("viewer")) {
        var e = settingsEditData.object_imei,
            t = $("#settings_object_sensor_list_grid").jqGrid("getGridParam", "selarrrow");
        return "" == t ? void notifyBox("error", la.ERROR, la.NO_ITEMS_SELECTED) : void confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE_SELECTED_ITEMS, function(a) {
            if (a) {
                var o = {
                    cmd: "delete_selected_object_sensors",
                    items: t,
                    imei: e
                };
                $.ajax({
                    type: "POST",
                    url: "func/fn_settings.sensors.php",
                    data: o,
                    success: function(e) {
                        "OK" == e && (settingsReloadObjects(), $("#settings_object_sensor_list_grid").trigger("reloadGrid"))
                    }
                })
            }
        })
    }
}

function settingsObjectSensorCalibrationList() {
    var e = settingsEditData.sensor_calibration,
        t = [],
        a = $("#settings_object_sensor_calibration_list_grid");
    if (a.clearGridData(!0), 0 != e.length) {
        for (var o = 0; o < e.length; o++) {
            var i = '<a href="#" onclick="settingsObjectSensorCalibrationDel(' + o + ');" title="' + la.DELETE + '"><img src="theme/images/remove3.svg" /></a>';
            t.push({
                x: e[o].x,
                y: e[o].y,
                modify: i
            })
        }
        for (var o = 0; o < t.length; o++) a.jqGrid("addRowData", o, t[o]);
        a.setGridParam({
            sortname: "x",
            sortorder: "asc"
        }).trigger("reloadGrid")
    }
}

function settingsObjectSensorCalibrationAdd() {
    var e = document.getElementById("settings_object_sensor_calibration_x").value,
        t = document.getElementById("settings_object_sensor_calibration_y").value;
    isNumber(e) || (e = 0), isNumber(t) || (t = 0);
    for (var a = 0; a < settingsEditData.sensor_calibration.length; a++)
        if (settingsEditData.sensor_calibration[a].x == e) return void notifyBox("error", la.ERROR, la.SAME_X_CALIBRATION_CHECK_POINT_AVAILABLE);
    settingsEditData.sensor_calibration.push({
        x: e,
        y: t
    }), document.getElementById("settings_object_sensor_calibration_x").value = "", document.getElementById("settings_object_sensor_calibration_y").value = "", settingsObjectSensorCalibrationList()
}

function settingsObjectSensorCalibrationDel(e) {
    settingsEditData.sensor_calibration.splice(e, 1), settingsObjectSensorCalibrationList()
}

function settingsObjectSensorType() {
    var e = document.getElementById("dialog_settings_object_sensor_type").value,
        t = document.getElementById("dialog_settings_object_sensor_result_type");
    switch (t.options.length = 0, e) {
        case "di":
            document.getElementById("dialog_settings_object_sensor_tanksize").disabled = !0,document.getElementById("dialog_settings_object_sensor_data_list").disabled = !1, document.getElementById("dialog_settings_object_sensor_popup").disabled = !1, t.options.add(new Option(la.LOGIC, "logic"));
            break;
        case "do":
            document.getElementById("dialog_settings_object_sensor_tanksize").disabled = !0,document.getElementById("dialog_settings_object_sensor_data_list").disabled = !1, document.getElementById("dialog_settings_object_sensor_popup").disabled = !1, t.options.add(new Option(la.LOGIC, "logic"));
            break;
        case "da":
            document.getElementById("dialog_settings_object_sensor_tanksize").disabled = !0,document.getElementById("dialog_settings_object_sensor_data_list").disabled = !0, document.getElementById("dialog_settings_object_sensor_data_list").checked = !1, document.getElementById("dialog_settings_object_sensor_popup").disabled = !0, document.getElementById("dialog_settings_object_sensor_popup").checked = !1, t.options.add(new Option(la.STRING, "string"));
            break;
        case "engh":
            document.getElementById("dialog_settings_object_sensor_tanksize").disabled = !0,document.getElementById("dialog_settings_object_sensor_data_list").disabled = !0, document.getElementById("dialog_settings_object_sensor_data_list").checked = !1, document.getElementById("dialog_settings_object_sensor_popup").disabled = !0, document.getElementById("dialog_settings_object_sensor_popup").checked = !1, t.options.add(new Option(la.ABSOLUTE, "abs")), t.options.add(new Option(la.RELATIVE, "rel"));
            break;
        case "fuel":
            document.getElementById("dialog_settings_object_sensor_tanksize").disabled = !1,document.getElementById("dialog_settings_object_sensor_data_list").disabled = !1, document.getElementById("dialog_settings_object_sensor_popup").disabled = !1, t.options.add(new Option(la.VALUE, "value")), t.options.add(new Option("Percentage", "percentage"));
            break;
        case "fuelcons":
            document.getElementById("dialog_settings_object_sensor_tanksize").disabled = !0,document.getElementById("dialog_settings_object_sensor_data_list").disabled = !0, document.getElementById("dialog_settings_object_sensor_data_list").checked = !1, document.getElementById("dialog_settings_object_sensor_popup").disabled = !0, document.getElementById("dialog_settings_object_sensor_popup").checked = !1, t.options.add(new Option(la.RELATIVE, "rel"));
            break;
        case "acc":
            document.getElementById("dialog_settings_object_sensor_tanksize").disabled = !0,document.getElementById("dialog_settings_object_sensor_data_list").disabled = !1, document.getElementById("dialog_settings_object_sensor_popup").disabled = !1, t.options.add(new Option(la.LOGIC, "logic"));
            break;
        case "air_ac":
            document.getElementById("dialog_settings_object_sensor_tanksize").disabled = !0,document.getElementById("dialog_settings_object_sensor_data_list").disabled = !1, document.getElementById("dialog_settings_object_sensor_popup").disabled = !1, t.options.add(new Option(la.LOGIC, "logic"));
            break;
        case "odo":
            document.getElementById("dialog_settings_object_sensor_tanksize").disabled = !0,document.getElementById("dialog_settings_object_sensor_data_list").disabled = !0, document.getElementById("dialog_settings_object_sensor_data_list").checked = !1, document.getElementById("dialog_settings_object_sensor_popup").disabled = !0, document.getElementById("dialog_settings_object_sensor_popup").checked = !1, t.options.add(new Option(la.ABSOLUTE, "abs")), t.options.add(new Option(la.RELATIVE, "rel"));
            break;
        case "pa":
            document.getElementById("dialog_settings_object_sensor_tanksize").disabled = !0,document.getElementById("dialog_settings_object_sensor_data_list").disabled = !0, document.getElementById("dialog_settings_object_sensor_data_list").checked = !1, document.getElementById("dialog_settings_object_sensor_popup").disabled = !0, document.getElementById("dialog_settings_object_sensor_popup").checked = !1, t.options.add(new Option(la.STRING, "string"));
            break;
        case "temp":
            document.getElementById("dialog_settings_object_sensor_tanksize").disabled = !0,document.getElementById("dialog_settings_object_sensor_data_list").disabled = !1, document.getElementById("dialog_settings_object_sensor_popup").disabled = !1, t.options.add(new Option(la.VALUE, "value"));
            break;
        case "ta":
            document.getElementById("dialog_settings_object_sensor_tanksize").disabled = !0,document.getElementById("dialog_settings_object_sensor_data_list").disabled = !0, document.getElementById("dialog_settings_object_sensor_data_list").checked = !1, document.getElementById("dialog_settings_object_sensor_popup").disabled = !0, document.getElementById("dialog_settings_object_sensor_popup").checked = !1, t.options.add(new Option(la.STRING, "string"));
            break;
        case "cust":
            document.getElementById("dialog_settings_object_sensor_tanksize").disabled = !0,document.getElementById("dialog_settings_object_sensor_data_list").disabled = !1, document.getElementById("dialog_settings_object_sensor_popup").disabled = !1, t.options.add(new Option(la.LOGIC, "logic")), t.options.add(new Option(la.VALUE, "value")), t.options.add(new Option(la.STRING, "string")), t.options.add(new Option(la.PERCENTAGE, "percentage"))
    }
    settingsObjectSensorResultType()
}

function settingsObjectSensorResultType() {
    document.getElementById("dialog_settings_object_sensor_units").value = "", document.getElementById("dialog_settings_object_sensor_text_1").value = "", document.getElementById("dialog_settings_object_sensor_text_0").value = "", document.getElementById("dialog_settings_object_sensor_lv").value = "", document.getElementById("dialog_settings_object_sensor_hv").value = "", document.getElementById("dialog_settings_object_sensor_formula").value = "", document.getElementById("dialog_settings_object_sensor_tanksize").value = "", document.getElementById("settings_object_sensor_calibration_x").value = "", document.getElementById("settings_object_sensor_calibration_y").value = "", settingsEditData.sensor_calibration = [], $("#settings_object_sensor_calibration_list_grid").clearGridData(!0);
    var e = document.getElementById("dialog_settings_object_sensor_result_type").value;
    switch (e) {
        case "abs":
            document.getElementById("dialog_settings_object_sensor_units").disabled = !0, document.getElementById("dialog_settings_object_sensor_text_1").disabled = !0, document.getElementById("dialog_settings_object_sensor_text_0").disabled = !0, document.getElementById("dialog_settings_object_sensor_lv").disabled = !0, document.getElementById("dialog_settings_object_sensor_hv").disabled = !0, document.getElementById("dialog_settings_object_sensor_formula").disabled = !1, $("#settings_object_sensor_calibration_list_grid").closest(".ui-jqgrid").block({
                message: ""
            }), document.getElementById("settings_object_sensor_calibration_x").disabled = !0, document.getElementById("settings_object_sensor_calibration_y").disabled = !0, document.getElementById("settings_object_sensor_calibration_add").disabled = !0;
            break;
        case "rel":
            document.getElementById("dialog_settings_object_sensor_units").disabled = !0, document.getElementById("dialog_settings_object_sensor_text_1").disabled = !0, document.getElementById("dialog_settings_object_sensor_text_0").disabled = !0, document.getElementById("dialog_settings_object_sensor_lv").disabled = !0, document.getElementById("dialog_settings_object_sensor_hv").disabled = !0, document.getElementById("dialog_settings_object_sensor_formula").disabled = !1, $("#settings_object_sensor_calibration_list_grid").closest(".ui-jqgrid").block({
                message: ""
            }), document.getElementById("settings_object_sensor_calibration_x").disabled = !0, document.getElementById("settings_object_sensor_calibration_y").disabled = !0, document.getElementById("settings_object_sensor_calibration_add").disabled = !0;
            break;
        case "logic":
            document.getElementById("dialog_settings_object_sensor_units").disabled = !0, document.getElementById("dialog_settings_object_sensor_text_1").disabled = !1, document.getElementById("dialog_settings_object_sensor_text_0").disabled = !1, document.getElementById("dialog_settings_object_sensor_lv").disabled = !0, document.getElementById("dialog_settings_object_sensor_hv").disabled = !0, document.getElementById("dialog_settings_object_sensor_formula").disabled = !0, $("#settings_object_sensor_calibration_list_grid").closest(".ui-jqgrid").block({
                message: ""
            }), document.getElementById("settings_object_sensor_calibration_x").disabled = !0, document.getElementById("settings_object_sensor_calibration_y").disabled = !0, document.getElementById("settings_object_sensor_calibration_add").disabled = !0;
            break;
        case "value":
            document.getElementById("dialog_settings_object_sensor_units").disabled = !1, document.getElementById("dialog_settings_object_sensor_text_1").disabled = !0, document.getElementById("dialog_settings_object_sensor_text_0").disabled = !0, document.getElementById("dialog_settings_object_sensor_lv").disabled = !0, document.getElementById("dialog_settings_object_sensor_hv").disabled = !0, document.getElementById("dialog_settings_object_sensor_formula").disabled = !1, $("#settings_object_sensor_calibration_list_grid").closest(".ui-jqgrid").unblock(), document.getElementById("settings_object_sensor_calibration_x").disabled = !1, document.getElementById("settings_object_sensor_calibration_y").disabled = !1, document.getElementById("settings_object_sensor_calibration_add").disabled = !1;
            break;
        case "string":
            document.getElementById("dialog_settings_object_sensor_units").disabled = !0, document.getElementById("dialog_settings_object_sensor_text_1").disabled = !0, document.getElementById("dialog_settings_object_sensor_text_0").disabled = !0, document.getElementById("dialog_settings_object_sensor_lv").disabled = !0, document.getElementById("dialog_settings_object_sensor_hv").disabled = !0, document.getElementById("dialog_settings_object_sensor_formula").disabled = !0, $("#settings_object_sensor_calibration_list_grid").closest(".ui-jqgrid").block({
                message: ""
            }), document.getElementById("settings_object_sensor_calibration_x").disabled = !0, document.getElementById("settings_object_sensor_calibration_y").disabled = !0, document.getElementById("settings_object_sensor_calibration_add").disabled = !0;
            break;
        case "percentage":
            document.getElementById("dialog_settings_object_sensor_units").value = "%", document.getElementById("dialog_settings_object_sensor_units").disabled = !0, document.getElementById("dialog_settings_object_sensor_text_1").disabled = !0, document.getElementById("dialog_settings_object_sensor_text_0").disabled = !0, document.getElementById("dialog_settings_object_sensor_lv").disabled = !1, document.getElementById("dialog_settings_object_sensor_hv").disabled = !1, document.getElementById("dialog_settings_object_sensor_formula").disabled = !0, $("#settings_object_sensor_calibration_list_grid").closest(".ui-jqgrid").block({
                message: ""
            }), document.getElementById("settings_object_sensor_calibration_x").disabled = !0, document.getElementById("settings_object_sensor_calibration_y").disabled = !0, document.getElementById("settings_object_sensor_calibration_add").disabled = !0
    }
}

function settingsObjectServiceImport() {
    utilsCheckPrivileges("viewer") && (document.getElementById("load_file").addEventListener("change", settingsObjectServiceImportSERFile, !1), document.getElementById("load_file").click())
}

function settingsObjectServiceExport() {
    if (utilsCheckPrivileges("viewer")) {
        var e = settingsEditData.object_imei,
            t = "func/fn_export.php?format=ser&imei=" + e;
        window.location = t
    }
}

function settingsObjectServiceImportSERFile(e) {
    var t = e.target.files,
        a = new FileReader;
    a.onload = function(e) {
        try {
            var t = $.parseJSON(e.target.result);
            if ("0.1v" == t.ser) {
                var a = settingsEditData.object_imei,
                    o = t.services.length;
                if (0 == o) return void notifyBox("info", la.INFORMATION, la.NOTHING_HAS_BEEN_FOUND_TO_IMPORT);
                var i = sprintf(la.SERVICES_FOUND, o) + " " + la.ARE_YOU_SURE_YOU_WANT_TO_IMPORT;
                confirmDialog(i, function(t) {
                    if (t) {
                        loadingData(!0);
                        var o = {
                            format: "ser",
                            data: e.target.result,
                            imei: a
                        };
                        $.ajax({
                            type: "POST",
                            url: "func/fn_import.php",
                            data: o,
                            cache: !1,
                            success: function(e) {
                                loadingData(!1), "OK" == e && (settingsReloadObjects(), $("#settings_object_service_list_grid").trigger("reloadGrid"))
                            },
                            error: function() {
                                loadingData(!1)
                            }
                        })
                    }
                })
            } else notifyBox("error", la.ERROR, la.INVALID_FILE_FORMAT)
        } catch (s) {
            notifyBox("error", la.ERROR, la.INVALID_FILE_FORMAT)
        }
        document.getElementById("load_file").value = ""
    }, a.readAsText(t[0], "UTF-8"), this.removeEventListener("change", settingsObjectServiceImportSERFile, !1)
}

function settingsObjectServiceProperties(e) {
    var t = settingsEditData.object_imei;
    switch (e) {
        default: var a = e;settingsEditData.service_id = a,
        document.getElementById("dialog_settings_object_service_name").value = settingsObjectData[t].service[a].name,
        document.getElementById("dialog_settings_object_service_data_list").checked = strToBoolean(settingsObjectData[t].service[a].data_list),
        document.getElementById("dialog_settings_object_service_popup").checked = strToBoolean(settingsObjectData[t].service[a].popup),
        document.getElementById("dialog_settings_object_service_odo").checked = strToBoolean(settingsObjectData[t].service[a].odo),
        1 == document.getElementById("dialog_settings_object_service_odo").checked ? (document.getElementById("dialog_settings_object_service_odo_interval").value = settingsObjectData[t].service[a].odo_interval, document.getElementById("dialog_settings_object_service_odo_last").value = settingsObjectData[t].service[a].odo_last, document.getElementById("dialog_settings_object_service_odo_left").checked = strToBoolean(settingsObjectData[t].service[a].odo_left), document.getElementById("dialog_settings_object_service_odo_left_num").value = settingsObjectData[t].service[a].odo_left_num) : (document.getElementById("dialog_settings_object_service_odo_interval").value = "", document.getElementById("dialog_settings_object_service_odo_last").value = "", document.getElementById("dialog_settings_object_service_odo_left").checked = !1, document.getElementById("dialog_settings_object_service_odo_left_num").value = ""),
        document.getElementById("dialog_settings_object_service_engh").checked = strToBoolean(settingsObjectData[t].service[a].engh),
        1 == document.getElementById("dialog_settings_object_service_engh").checked ? (document.getElementById("dialog_settings_object_service_engh_interval").value = settingsObjectData[t].service[a].engh_interval, document.getElementById("dialog_settings_object_service_engh_last").value = settingsObjectData[t].service[a].engh_last, document.getElementById("dialog_settings_object_service_engh_left").checked = strToBoolean(settingsObjectData[t].service[a].engh_left), document.getElementById("dialog_settings_object_service_engh_left_num").value = settingsObjectData[t].service[a].engh_left_num) : (document.getElementById("dialog_settings_object_service_engh_interval").value = "", document.getElementById("dialog_settings_object_service_engh_last").value = "", document.getElementById("dialog_settings_object_service_engh_left").checked = !1, document.getElementById("dialog_settings_object_service_engh_left_num").value = ""),
        document.getElementById("dialog_settings_object_service_days").checked = strToBoolean(settingsObjectData[t].service[a].days),
        1 == document.getElementById("dialog_settings_object_service_days").checked ? (document.getElementById("dialog_settings_object_service_days_interval").value = settingsObjectData[t].service[a].days_interval, document.getElementById("dialog_settings_object_service_days_last").value = settingsObjectData[t].service[a].days_last, document.getElementById("dialog_settings_object_service_days_left").checked = strToBoolean(settingsObjectData[t].service[a].days_left), document.getElementById("dialog_settings_object_service_days_left_num").value = settingsObjectData[t].service[a].days_left_num) : (document.getElementById("dialog_settings_object_service_days_interval").value = "", document.getElementById("dialog_settings_object_service_days_last").value = "", document.getElementById("dialog_settings_object_service_days_left").checked = !1, document.getElementById("dialog_settings_object_service_days_left_num").value = ""),
        document.getElementById("dialog_settings_object_service_update_last").checked = strToBoolean(settingsObjectData[t].service[a].update_last),
        document.getElementById("dialog_settings_object_service_odo_curr").value = settingsObjectData[t].odometer,
        document.getElementById("dialog_settings_object_service_engh_curr").value = settingsObjectData[t].engine_hours,
        settingsObjectServiceCheck(),
        $("#dialog_settings_object_service_properties").dialog("open");
        break;
        case "add":
                settingsEditData.service_id = !1,
            document.getElementById("dialog_settings_object_service_name").value = "",
            document.getElementById("dialog_settings_object_service_data_list").checked = !1,
            document.getElementById("dialog_settings_object_service_popup").checked = !1,
            document.getElementById("dialog_settings_object_service_odo").checked = !1,
            document.getElementById("dialog_settings_object_service_odo_interval").value = "",
            document.getElementById("dialog_settings_object_service_odo_last").value = "",
            document.getElementById("dialog_settings_object_service_engh").checked = !1,
            document.getElementById("dialog_settings_object_service_engh_interval").value = "",
            document.getElementById("dialog_settings_object_service_engh_last").value = "",
            document.getElementById("dialog_settings_object_service_days").checked = !1,
            document.getElementById("dialog_settings_object_service_days_interval").value = "",
            document.getElementById("dialog_settings_object_service_days_last").value = "",
            document.getElementById("dialog_settings_object_service_odo_left").checked = !1,
            document.getElementById("dialog_settings_object_service_odo_left_num").value = "",
            document.getElementById("dialog_settings_object_service_engh_left").checked = !1,
            document.getElementById("dialog_settings_object_service_engh_left_num").value = "",
            document.getElementById("dialog_settings_object_service_days_left").checked = !1,
            document.getElementById("dialog_settings_object_service_days_left_num").value = "",
            document.getElementById("dialog_settings_object_service_update_last").checked = !1,
            document.getElementById("dialog_settings_object_service_odo_curr").value = settingsObjectData[t].odometer,
            document.getElementById("dialog_settings_object_service_engh_curr").value = settingsObjectData[t].engine_hours,
            settingsObjectServiceCheck(),
            $("#dialog_settings_object_service_properties").dialog("open");
            break;
        case "cancel":
                $("#dialog_settings_object_service_properties").dialog("close");
            break;
        case "save":
                if (!utilsCheckPrivileges("viewer")) return;
            var o = document.getElementById("dialog_settings_object_service_name").value,
                i = document.getElementById("dialog_settings_object_service_data_list").checked,
                s = document.getElementById("dialog_settings_object_service_popup").checked,
                n = document.getElementById("dialog_settings_object_service_odo").checked,
                l = document.getElementById("dialog_settings_object_service_odo_interval").value,
                d = document.getElementById("dialog_settings_object_service_odo_last").value,
                r = document.getElementById("dialog_settings_object_service_engh").checked,
                _ = document.getElementById("dialog_settings_object_service_engh_interval").value,
                c = document.getElementById("dialog_settings_object_service_engh_last").value,
                g = document.getElementById("dialog_settings_object_service_days").checked,
                m = document.getElementById("dialog_settings_object_service_days_interval").value,
                u = document.getElementById("dialog_settings_object_service_days_last").value,
                p = document.getElementById("dialog_settings_object_service_odo_left").checked,
                v = document.getElementById("dialog_settings_object_service_odo_left_num").value,
                y = document.getElementById("dialog_settings_object_service_engh_left").checked,
                E = document.getElementById("dialog_settings_object_service_engh_left_num").value,
                b = document.getElementById("dialog_settings_object_service_days_left").checked,
                h = document.getElementById("dialog_settings_object_service_days_left_num").value,
                f = document.getElementById("dialog_settings_object_service_update_last").checked;
            if ("" == o) {
                notifyBox("error", la.ERROR, la.ALL_AVAILABLE_FIELDS_SHOULD_BE_FILLED_OUT);
                break
            }
            if (1 == n && ("" == l || "" == d)) {
                notifyBox("error", la.ERROR, la.ALL_AVAILABLE_FIELDS_SHOULD_BE_FILLED_OUT);
                break
            }
            if (1 == r && ("" == _ || "" == c)) {
                notifyBox("error", la.ERROR, la.ALL_AVAILABLE_FIELDS_SHOULD_BE_FILLED_OUT);
                break
            }
            if (1 == g && ("" == m || "" == u)) {
                notifyBox("error", la.ERROR, la.ALL_AVAILABLE_FIELDS_SHOULD_BE_FILLED_OUT);
                break
            }
            if (parseFloat(l) <= parseFloat(v) && 1 == p) {
                notifyBox("error", la.ERROR, la.INTERVAL_VALUE_SHOULD_BE_GREATER_THAN_LEFT_VALUE);
                break
            }
            if (parseFloat(_) <= parseFloat(E) && 1 == y) {
                notifyBox("error", la.ERROR, la.INTERVAL_VALUE_SHOULD_BE_GREATER_THAN_LEFT_VALUE);
                break
            }
            if (parseFloat(m) <= parseFloat(h) && 1 == b) {
                notifyBox("error", la.ERROR, la.INTERVAL_VALUE_SHOULD_BE_GREATER_THAN_LEFT_VALUE);
                break
            }
            var I = {
                cmd: "save_object_service",
                service_id: settingsEditData.service_id,
                imei: t,
                name: o,
                data_list: i,
                popup: s,
                odo: n,
                odo_interval: l,
                odo_last: d,
                engh: r,
                engh_interval: _,
                engh_last: c,
                days: g,
                days_interval: m,
                days_last: u,
                odo_left: p,
                odo_left_num: v,
                engh_left: y,
                engh_left_num: E,
                days_left: b,
                days_left_num: h,
                update_last: f
            };$.ajax({
                type: "POST",
                url: "func/fn_settings.service.php",
                data: I,
                cache: !1,
                success: function(e) {
                    "OK" == e && (settingsReloadObjects(), $("#dialog_settings_object_service_properties").dialog("close"), $("#settings_object_service_list_grid").trigger("reloadGrid"), notifyBox("info", la.INFORMATION, la.CHANGES_SAVED_SUCCESSFULLY))
                }
            })
    }
}

function settingsObjectServiceDelete(e) {
    if (utilsCheckPrivileges("viewer")) {
        var t = settingsEditData.object_imei;
        confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE, function(a) {
            if (a) {
                var o = {
                    cmd: "delete_object_service",
                    service_id: e,
                    imei: t
                };
                $.ajax({
                    type: "POST",
                    url: "func/fn_settings.service.php",
                    data: o,
                    success: function(e) {
                        "OK" == e && (settingsReloadObjects(), $("#settings_object_service_list_grid").trigger("reloadGrid"))
                    }
                })
            }
        })
    }
}

function settingsObjectServiceDeleteSelected() {
    if (utilsCheckPrivileges("viewer")) {
        var e = settingsEditData.object_imei,
            t = $("#settings_object_service_list_grid").jqGrid("getGridParam", "selarrrow");
        return "" == t ? void notifyBox("error", la.ERROR, la.NO_ITEMS_SELECTED) : void confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE_SELECTED_ITEMS, function(a) {
            if (a) {
                var o = {
                    cmd: "delete_selected_object_services",
                    items: t,
                    imei: e
                };
                $.ajax({
                    type: "POST",
                    url: "func/fn_settings.service.php",
                    data: o,
                    success: function(e) {
                        "OK" == e && (settingsReloadObjects(), $("#settings_object_service_list_grid").trigger("reloadGrid"))
                    }
                })
            }
        })
    }
}

function settingsObjectServiceCheck() {
    1 == document.getElementById("dialog_settings_object_service_odo").checked ? (document.getElementById("dialog_settings_object_service_odo_interval").disabled = !1, document.getElementById("dialog_settings_object_service_odo_last").disabled = !1, document.getElementById("dialog_settings_object_service_odo_left").disabled = !1, document.getElementById("dialog_settings_object_service_odo_left_num").disabled = !1) : (document.getElementById("dialog_settings_object_service_odo_interval").disabled = !0, document.getElementById("dialog_settings_object_service_odo_last").disabled = !0, document.getElementById("dialog_settings_object_service_odo_left").disabled = !0, document.getElementById("dialog_settings_object_service_odo_left_num").disabled = !0), 1 == document.getElementById("dialog_settings_object_service_engh").checked ? (document.getElementById("dialog_settings_object_service_engh_interval").disabled = !1, document.getElementById("dialog_settings_object_service_engh_last").disabled = !1, document.getElementById("dialog_settings_object_service_engh_left").disabled = !1, document.getElementById("dialog_settings_object_service_engh_left_num").disabled = !1) : (document.getElementById("dialog_settings_object_service_engh_interval").disabled = !0, document.getElementById("dialog_settings_object_service_engh_last").disabled = !0, document.getElementById("dialog_settings_object_service_engh_left").disabled = !0, document.getElementById("dialog_settings_object_service_engh_left_num").disabled = !0), 1 == document.getElementById("dialog_settings_object_service_days").checked ? (document.getElementById("dialog_settings_object_service_days_interval").disabled = !1, document.getElementById("dialog_settings_object_service_days_last").disabled = !1, document.getElementById("dialog_settings_object_service_days_left").disabled = !1, document.getElementById("dialog_settings_object_service_days_left_num").disabled = !1) : (document.getElementById("dialog_settings_object_service_days_interval").disabled = !0, document.getElementById("dialog_settings_object_service_days_last").disabled = !0, document.getElementById("dialog_settings_object_service_days_left").disabled = !0, document.getElementById("dialog_settings_object_service_days_left_num").disabled = !0), 1 == document.getElementById("dialog_settings_object_service_odo").checked || 1 == document.getElementById("dialog_settings_object_service_engh").checked || 1 == document.getElementById("dialog_settings_object_service_days").checked ? document.getElementById("dialog_settings_object_service_update_last").disabled = !1 : document.getElementById("dialog_settings_object_service_update_last").disabled = !0
}

function settingsObjectCustomFieldImport() {
    utilsCheckPrivileges("viewer") && (document.getElementById("load_file").addEventListener("change", settingsObjectCustomFieldImportCFLFile, !1), document.getElementById("load_file").click())
}

function settingsObjectCustomFieldExport() {
    if (utilsCheckPrivileges("viewer")) {
        var e = settingsEditData.object_imei,
            t = "func/fn_export.php?format=cfl&imei=" + e;
        window.location = t
    }
}

function settingsObjectCustomFieldImportCFLFile(e) {
    var t = e.target.files,
        a = new FileReader;
    a.onload = function(e) {
        try {
            var t = $.parseJSON(e.target.result);
            if ("0.1v" == t.cfl) {
                var a = settingsEditData.object_imei,
                    o = t.fields.length;
                if (0 == o) return void notifyBox("info", la.INFORMATION, la.NOTHING_HAS_BEEN_FOUND_TO_IMPORT);
                var i = sprintf(la.CUSTOM_FIELDS_FOUND, o) + " " + la.ARE_YOU_SURE_YOU_WANT_TO_IMPORT;
                confirmDialog(i, function(t) {
                    if (t) {
                        loadingData(!0);
                        var o = {
                            format: "cfl",
                            data: e.target.result,
                            imei: a
                        };
                        $.ajax({
                            type: "POST",
                            url: "func/fn_import.php",
                            data: o,
                            cache: !1,
                            success: function(e) {
                                loadingData(!1), "OK" == e && (settingsReloadObjects(), $("#settings_object_custom_fields_list_grid").trigger("reloadGrid"))
                            },
                            error: function() {
                                loadingData(!1)
                            }
                        })
                    }
                })
            } else notifyBox("error", la.ERROR, la.INVALID_FILE_FORMAT)
        } catch (s) {
            notifyBox("error", la.ERROR, la.INVALID_FILE_FORMAT)
        }
        document.getElementById("load_file").value = ""
    }, a.readAsText(t[0], "UTF-8"), this.removeEventListener("change", settingsObjectCustomFieldImportCFLFile, !1)
}

function settingsObjectCustomFieldDelete(e) {
    if (utilsCheckPrivileges("viewer")) {
        var t = settingsEditData.object_imei;
        confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE, function(a) {
            if (a) {
                var o = {
                    cmd: "delete_object_custom_field",
                    field_id: e,
                    imei: t
                };
                $.ajax({
                    type: "POST",
                    url: "func/fn_settings.customfields.php",
                    data: o,
                    success: function(e) {
                        "OK" == e && (settingsReloadObjects(), $("#settings_object_custom_fields_list_grid").trigger("reloadGrid"))
                    }
                })
            }
        })
    }
}

function settingsObjectCustomFieldDeleteSelected() {
    if (utilsCheckPrivileges("viewer")) {
        var e = settingsEditData.object_imei,
            t = $("#settings_object_custom_fields_list_grid").jqGrid("getGridParam", "selarrrow");
        return "" == t ? void notifyBox("error", la.ERROR, la.NO_ITEMS_SELECTED) : void confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE_SELECTED_ITEMS, function(a) {
            if (a) {
                var o = {
                    cmd: "delete_selected_object_custom_fields",
                    items: t,
                    imei: e
                };
                $.ajax({
                    type: "POST",
                    url: "func/fn_settings.customfields.php",
                    data: o,
                    success: function(e) {
                        "OK" == e && (settingsReloadObjects(), $("#settings_object_custom_fields_list_grid").trigger("reloadGrid"))
                    }
                })
            }
        })
    }
}

function settingsObjectCustomFieldProperties(e) {
    var t = settingsEditData.object_imei;
    switch (e) {
        default: var a = e;settingsEditData.custom_field_id = a,
        document.getElementById("dialog_settings_object_custom_field_name").value = settingsObjectData[t].custom_fields[a].name,
        document.getElementById("dialog_settings_object_custom_field_value").value = settingsObjectData[t].custom_fields[a].value,
        document.getElementById("dialog_settings_object_custom_field_data_list").checked = strToBoolean(settingsObjectData[t].custom_fields[a].data_list),
        document.getElementById("dialog_settings_object_custom_field_popup").checked = strToBoolean(settingsObjectData[t].custom_fields[a].popup),
        $("#dialog_settings_object_custom_field_properties").dialog("open");
        break;
        case "add":
                settingsEditData.custom_field_id = !1,
            document.getElementById("dialog_settings_object_custom_field_name").value = "",
            document.getElementById("dialog_settings_object_custom_field_value").value = "",
            document.getElementById("dialog_settings_object_custom_field_data_list").checked = !0,
            document.getElementById("dialog_settings_object_custom_field_popup").checked = !0,
            $("#dialog_settings_object_custom_field_properties").dialog("open");
            break;
        case "cancel":
                $("#dialog_settings_object_custom_field_properties").dialog("close");
            break;
        case "save":
                if (!utilsCheckPrivileges("viewer")) return;
            var o = document.getElementById("dialog_settings_object_custom_field_name").value,
                i = document.getElementById("dialog_settings_object_custom_field_value").value,
                s = document.getElementById("dialog_settings_object_custom_field_data_list").checked,
                n = document.getElementById("dialog_settings_object_custom_field_popup").checked;
            if ("" == o) {
                notifyBox("error", la.ERROR, la.NAME_CANT_BE_EMPTY);
                break
            }
            var l = {
                cmd: "save_object_custom_field",
                field_id: settingsEditData.custom_field_id,
                imei: t,
                name: o,
                value: i,
                data_list: s,
                popup: n
            };$.ajax({
                type: "POST",
                url: "func/fn_settings.customfields.php",
                data: l,
                cache: !1,
                success: function(e) {
                    "OK" == e && (settingsReloadObjects(), $("#settings_object_custom_fields_list_grid").trigger("reloadGrid"), $("#dialog_settings_object_custom_field_properties").dialog("close"), notifyBox("info", la.INFORMATION, la.CHANGES_SAVED_SUCCESSFULLY))
                }
            })
    }
}

function settingsTemplateImport() {
    utilsCheckPrivileges("viewer") && (document.getElementById("load_file").addEventListener("change", settingsTemplateImportTEMFile, !1), document.getElementById("load_file").click())
}

function settingsTemplateExport() {
    if (utilsCheckPrivileges("viewer")) {
        var e = "func/fn_export.php?format=tem";
        window.location = e
    }
}

function settingsTemplateImportTEMFile(e) {
    var t = e.target.files,
        a = new FileReader;
    a.onload = function(e) {
        try {
            var t = $.parseJSON(e.target.result);
            if ("0.1v" == t.tem) {
                var a = t.templates.length;
                if (0 == a) return void notifyBox("info", la.INFORMATION, la.NOTHING_HAS_BEEN_FOUND_TO_IMPORT);
                var o = sprintf(la.TEMPLATES_FOUND, a) + " " + la.ARE_YOU_SURE_YOU_WANT_TO_IMPORT;
                confirmDialog(o, function(t) {
                    if (t) {
                        loadingData(!0);
                        var a = {
                            format: "tem",
                            data: e.target.result
                        };
                        $.ajax({
                            type: "POST",
                            url: "func/fn_import.php",
                            data: a,
                            cache: !1,
                            success: function(e) {
                                loadingData(!1), "OK" == e && settingsReloadTemplates()
                            },
                            error: function() {
                                loadingData(!1)
                            }
                        })
                    }
                })
            } else notifyBox("error", la.ERROR, la.INVALID_FILE_FORMAT)
        } catch (i) {
            notifyBox("error", la.ERROR, la.INVALID_FILE_FORMAT)
        }
        document.getElementById("load_file").value = ""
    }, a.readAsText(t[0], "UTF-8"), this.removeEventListener("change", settingsTemplateImportTEMFile, !1)
}

function settingsTemplateProperties(e) {
    switch (e) {
        default: var t = e;settingsEditData.template_id = t,
        document.getElementById("dialog_settings_template_name").value = settingsTemplateData[t].name,
        document.getElementById("dialog_settings_template_desc").value = settingsTemplateData[t].desc,
        document.getElementById("dialog_settings_template_subject").value = settingsTemplateData[t].subject,
        document.getElementById("dialog_settings_template_message").value = settingsTemplateData[t].message,
        $("#dialog_settings_template_properties").dialog("open");
        break;
        case "add":
                settingsEditData.template_id = !1,
            document.getElementById("dialog_settings_template_name").value = "",
            document.getElementById("dialog_settings_template_desc").value = "",
            document.getElementById("dialog_settings_template_subject").value = "",
            document.getElementById("dialog_settings_template_message").value = "",
            $("#dialog_settings_template_properties").dialog("open");
            break;
        case "cancel":
                $("#dialog_settings_template_properties").dialog("close");
            break;
        case "save":
                if (!utilsCheckPrivileges("viewer")) return;
            var a = document.getElementById("dialog_settings_template_name").value,
                o = document.getElementById("dialog_settings_template_desc").value,
                i = document.getElementById("dialog_settings_template_subject").value,
                s = document.getElementById("dialog_settings_template_message").value;
            if ("" == a) {
                notifyBox("error", la.ERROR, la.NAME_CANT_BE_EMPTY);
                break
            }
            var n = {
                cmd: "save_template",
                template_id: settingsEditData.template_id,
                name: a,
                desc: o,
                subject: i,
                message: s
            };$.ajax({
                type: "POST",
                url: "func/fn_settings.templates.php",
                data: n,
                cache: !1,
                success: function(e) {
                    "OK" == e && (settingsReloadTemplates(), $("#dialog_settings_template_properties").dialog("close"), notifyBox("info", la.INFORMATION, la.CHANGES_SAVED_SUCCESSFULLY))
                }
            })
    }
}

function settingsTemplateDelete(e) {
    utilsCheckPrivileges("viewer") && confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE, function(t) {
        if (t) {
            var a = {
                cmd: "delete_template",
                template_id: e
            };
            $.ajax({
                type: "POST",
                url: "func/fn_settings.templates.php",
                data: a,
                success: function(e) {
                    "OK" == e && settingsReloadTemplates()
                }
            })
        }
    })
}

function settingsTemplateDeleteSelected() {
    if (utilsCheckPrivileges("viewer")) {
        var e = $("#settings_main_templates_template_list_grid").jqGrid("getGridParam", "selarrrow");
        return "" == e ? void notifyBox("error", la.ERROR, la.NO_ITEMS_SELECTED) : void confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE_SELECTED_ITEMS, function(t) {
            if (t) {
                var a = {
                    cmd: "delete_selected_templates",
                    items: e
                };
                $.ajax({
                    type: "POST",
                    url: "func/fn_settings.templates.php",
                    data: a,
                    success: function(e) {
                        "OK" == e && settingsReloadTemplates()
                    }
                })
            }
        })
    }
}

function settingsSubaccountGenerateAU() {
    var e = settingsUserData.email + moment(),
        t = CryptoJS.MD5(e).toString().toUpperCase();
    return t
}

function settingsSubaccountCheck() {
    1 == document.getElementById("dialog_settings_subaccount_expire").checked ? document.getElementById("dialog_settings_subaccount_expire_dt").disabled = !1 : document.getElementById("dialog_settings_subaccount_expire_dt").disabled = !0
}

function settingsSubaccountProperties(e) {
	//updated by vetrivel.N
    if (utilsCheckPrivileges("subuser") && utilsCheckPrivileges("subaccounts")) switch (e) {
        default: var t = e;settingsEditData.subaccount_id = t,
        document.getElementById("dialog_settings_subaccount_email").value = settingsSubaccountData[t].email;
		var select= document.getElementById("subuser_generat_report_list");
		for ( var i = 0, len = select.options.length; i < len; i++ ) {
			var re=select.options[i].value;
				if(settingsSubaccountData[t]['select_report'][re]["active"]=='A')
				{
					select.options[i].selected = true;
				}
				else
				{
					select.options[i].selected = false;
				}
			}
		loadsubuser_report();
        document.getElementById("dialog_settings_subaccount_password").value = "",
        document.getElementById("dialog_settings_subaccount_mobile_num").value =settingsSubaccountData[t].mobile_num,
        document.getElementById("dialog_settings_subaccount_active").checked = strToBoolean(settingsSubaccountData[t].active),
        document.getElementById("dialog_settings_subaccount_ambulamce").checked = strToBoolean(settingsSubaccountData[t].ambulance);
        var a = strToBoolean(settingsSubaccountData[t].account_expire);document.getElementById("dialog_settings_subaccount_expire_dt").checked = a,
        1 == a ? document.getElementById("dialog_settings_subaccount_expire_dt").value = settingsSubaccountData[t].account_expire_dt : document.getElementById("dialog_settings_subaccount_expire_dt").value = "";
        var o = document.getElementById("dialog_settings_subaccount_available_objects"),
            i = settingsSubaccountData[t].imei.split(",");multiselectSetValues(o, i);
        var s = document.getElementById("dialog_settings_subaccount_available_markers"),
            n = settingsSubaccountData[t].marker.split(",");multiselectSetValues(s, n);
        var l = document.getElementById("dialog_settings_subaccount_available_routes"),
            d = settingsSubaccountData[t].route.split(",");multiselectSetValues(l, d);
        var r = document.getElementById("dialog_settings_subaccount_available_zones"),
            _ = settingsSubaccountData[t].zone.split(",");multiselectSetValues(r, _),
        document.getElementById("dialog_settings_subaccount_history").checked = settingsSubaccountData[t].history,
        document.getElementById("dialog_settings_subaccount_reports").checked = settingsSubaccountData[t].reports,
        document.getElementById("dialog_settings_subaccount_sos").checked = settingsSubaccountData[t].sos,
		document.getElementById("dialog_settings_subaccount_live_tripreport").checked = settingsSubaccountData[t].live_tripreport,
		document.getElementById("dialog_settings_subaccount_send_command").checked = settingsSubaccountData[t].sendcommand,
		document.getElementById("dialog_settings_subaccount_boarding_point").checked = settingsSubaccountData[t].boarding,
        document.getElementById("dialog_settings_subaccount_rilogbook").checked = settingsSubaccountData[t].rilogbook,
        document.getElementById("dialog_settings_subaccount_dtc").checked = settingsSubaccountData[t].dtc,
        document.getElementById("dialog_settings_subaccount_object_control").checked = settingsSubaccountData[t].object_control,
        document.getElementById("dialog_settings_subaccount_image_gallery").checked = settingsSubaccountData[t].image_gallery,
        document.getElementById("dialog_settings_subaccount_chat").checked = settingsSubaccountData[t].chat,
        document.getElementById("dialog_settings_subaccount_au_active").checked = settingsSubaccountData[t].au_active,
        settingsEditData.subaccount_au = settingsSubaccountData[t].au,
        "" == settingsEditData.subaccount_au && (settingsEditData.subaccount_au = settingsSubaccountGenerateAU()),
        document.getElementById("dialog_settings_subaccount_au").value = gsValues.url_root + "/index.php?au=" + settingsEditData.subaccount_au,
        document.getElementById("dialog_settings_subaccount_au_mobile").value = gsValues.url_root + "/index.php?au=" + settingsEditData.subaccount_au + "&m=true",
        settingsSubaccountCheck();

        // user settings
        // document.getElementById('dialog_settings_subaccount_object_add').checked=settingsSubaccountData[t]['access_settings_object']['add'];
        // document.getElementById('dialog_settings_subaccount_object_edit').checked=settingsSubaccountData[t]['access_settings_object']['edit'];
        // document.getElementById('dialog_settings_subaccount_object_delete').checked=settingsSubaccountData[t]['access_settings_object']['delete'];

        // document.getElementById('dialog_settings_subaccount_group_add').checked=settingsSubaccountData[t]['access_settings_group']['add'];
        // document.getElementById('dialog_settings_subaccount_group_edit').checked=settingsSubaccountData[t]['access_settings_group']['edit'];
        // document.getElementById('dialog_settings_subaccount_group_delete').checked=settingsSubaccountData[t]['access_settings_group']['delete'];

        // document.getElementById('dialog_settings_subaccount_event_add').checked=settingsSubaccountData[t]['access_settings_events']['add'];
        // document.getElementById('dialog_settings_subaccount_event_edit').checked=settingsSubaccountData[t]['access_settings_events']['edit'];
        // document.getElementById('dialog_settings_subaccount_event_delete').checked=settingsSubaccountData[t]['access_settings_events']['delete'];

        document.getElementById('dialog_settings_subaccount_zone_add').checked=settingsSubaccountData[t]['access_settings_zones']['add'];
        document.getElementById('dialog_settings_subaccount_zone_edit').checked=settingsSubaccountData[t]['access_settings_zones']['edit'];
        document.getElementById('dialog_settings_subaccount_zone_delete').checked=settingsSubaccountData[t]['access_settings_zones']['delete'];

        document.getElementById('dialog_settings_subaccount_route_add').checked=settingsSubaccountData[t]['access_settings_route']['add'];
        document.getElementById('dialog_settings_subaccount_route_edit').checked=settingsSubaccountData[t]['access_settings_route']['edit'];
        document.getElementById('dialog_settings_subaccount_route_delete').checked=settingsSubaccountData[t]['access_settings_route']['delete'];

        document.getElementById('dialog_settings_subaccount_marker_add').checked=settingsSubaccountData[t]['access_settings_markers']['add'];
        document.getElementById('dialog_settings_subaccount_marker_edit').checked=settingsSubaccountData[t]['access_settings_markers']['edit'];
        document.getElementById('dialog_settings_subaccount_marker_delete').checked=settingsSubaccountData[t]['access_settings_markers']['delete'];

        // document.getElementById('dialog_settings_subaccount_duplicate_add').checked=settingsSubaccountData[t]['access_settings_duplicate']['add'];
        // document.getElementById('dialog_settings_subaccount_duplicate_edit').checked=settingsSubaccountData[t]['access_settings_duplicate']['edit'];
        // document.getElementById('dialog_settings_subaccount_duplicate_delete').checked=settingsSubaccountData[t]['access_settings_duplicate']['delete'];

        // document.getElementById('dialog_settings_subaccount_clr_history_add').checked=settingsSubaccountData[t]['access_settings_clr_history']['add'];
        // document.getElementById('dialog_settings_subaccount_clr_history_edit').checked=settingsSubaccountData[t]['access_settings_clr_history']['edit'];
        // document.getElementById('dialog_settings_subaccount_clr_history_delete').checked=settingsSubaccountData[t]['access_settings_clr_history']['delete'];

        if(settingsSubaccountData[t].subaccnt_type=="true")
        	$("#dialog_settings_subaccount_type").val("Driver account");
        else
        	$("#dialog_settings_subaccount_type").val("Sub account");
        $("#dialog_settings_subaccount_properties").dialog("open");
        break;
        case "add":
            if(settingsUserData['access_settings_subuser']['add']!=true){
                notifyBox("error", la.ERROR, la.NO_PERMISSION);
                return;
            }
                settingsEditData.subaccount_id = !1,
            document.getElementById("dialog_settings_subaccount_email").value = "",
            document.getElementById("dialog_settings_subaccount_password").value = "",
            document.getElementById("dialog_settings_subaccount_mobile_num").value = "",
            document.getElementById("dialog_settings_subaccount_active").checked = !0,
            document.getElementById("dialog_settings_subaccount_expire").checked = !1,
            document.getElementById("dialog_settings_subaccount_ambulamce").checked = !1,
            document.getElementById("dialog_settings_subaccount_expire_dt").value = "",
            $("#dialog_settings_subaccount_available_objects option:selected").removeAttr("selected"),
            $("#dialog_settings_subaccount_available_markers option:selected").removeAttr("selected"),
            $("#dialog_settings_subaccount_available_routes option:selected").removeAttr("selected"),
            $("#dialog_settings_subaccount_available_zones option:selected").removeAttr("selected"),
            document.getElementById("dialog_settings_subaccount_history").checked = !1,
            document.getElementById("dialog_settings_subaccount_reports").checked = !1,
			document.getElementById("dialog_settings_subaccount_boarding_point").checked = !1,
			document.getElementById("dialog_settings_subaccount_send_command").checked = !1,
            document.getElementById("dialog_settings_subaccount_sos").checked = !1,
			document.getElementById("dialog_settings_subaccount_live_tripreport").checked = !1,
            document.getElementById("dialog_settings_subaccount_rilogbook").checked = !1,
            document.getElementById("dialog_settings_subaccount_dtc").checked = !1,
            document.getElementById("dialog_settings_subaccount_object_control").checked = !1,
            document.getElementById("dialog_settings_subaccount_image_gallery").checked = !1,
            document.getElementById("dialog_settings_subaccount_chat").checked = !1,
            document.getElementById("dialog_settings_subaccount_au_active").checked = !1,
            settingsEditData.subaccount_au = settingsSubaccountGenerateAU(),
            document.getElementById("dialog_settings_subaccount_au").value = gsValues.url_root + "/index.php?au=" + settingsEditData.subaccount_au,
            document.getElementById("dialog_settings_subaccount_au_mobile").value = gsValues.url_root + "/index.php?au=" + settingsEditData.subaccount_au + "&m=true",
            settingsSubaccountCheck(),
			loadsubuser_report(),
            $("#dialog_settings_subaccount_type").val(""),
            $("#dialog_settings_subaccount_properties").dialog("open");
            break;
        case "cancel":
                $("#dialog_settings_subaccount_properties").dialog("close");
            break;
        case "save":

            if(settingsEditData.subaccount_id==false && settingsUserData['access_settings_subuser']['add']!=true){
                notifyBox("error", la.ERROR, la.NO_PERMISSION);
                return;
            }else  if(settingsEditData.subaccount_id!=false && settingsUserData['access_settings_subuser']['edit']!=true){
                notifyBox("error", la.ERROR, la.NO_PERMISSION);
                return;
            }
                if (!utilsCheckPrivileges("viewer")) return;
            var c = document.getElementById("dialog_settings_subaccount_email").value,
                g = document.getElementById("dialog_settings_subaccount_password").value,
                mn = document.getElementById("dialog_settings_subaccount_mobile_num").value,
                m = document.getElementById("dialog_settings_subaccount_active").checked,
                amb = document.getElementById("dialog_settings_subaccount_ambulamce").checked,
                a = document.getElementById("dialog_settings_subaccount_expire").checked,
                u = document.getElementById("dialog_settings_subaccount_expire_dt").value;
            if (1 == a) {
                if ("" == u) return void notifyBox("error", la.ERROR, la.DATE_CANT_BE_EMPTY, !0)
            } else var u = "";
            
            var vacctype=$("#dialog_settings_subaccount_type").val();
            if(vacctype=="Sub account")
            {
            	if (!isEmailValid(c)) {
            		notifyBox("error", la.ERROR, la.THIS_EMAIL_IS_NOT_VALID);
            		break
            	}
            }
            
            if(vacctype==""|| vacctype==null)
            {
            	notifyBox("error", la.ERROR, la.ACC_TYPE_CANT_EMPTY);
            	break
            }
            
            if (0 == settingsEditData.subaccount_id) {
                if ("" == g) {
                    notifyBox("error", la.ERROR, la.PASSWORD_CANT_BE_EMPTY);
                    break
                }
                if (g.length < 6) {
                    notifyBox("error", la.ERROR, la.PASSWORD_LENGHT_AT_LEAST);
                    break
                }
            } else if ("" != g && g.length < 6) {
                notifyBox("error", la.ERROR, la.PASSWORD_LENGHT_AT_LEAST);
                break
            }
            var o = document.getElementById("dialog_settings_subaccount_available_objects"),
                s = document.getElementById("dialog_settings_subaccount_available_markers"),
                l = document.getElementById("dialog_settings_subaccount_available_routes"),
                r = document.getElementById("dialog_settings_subaccount_available_zones"),
				select = document.getElementById('subuser_generat_report_list');
				report = multiselectGetValues(select);

                var gs_user_settings = {
                    add: false,
                    edit: false,
                    delete: false
                };          
                gs_user_settings = JSON.stringify(gs_user_settings);

                var gs_subuser_settings = {
                    add: false,
                    edit: false,
                    delete: false
                };          
                gs_subuser_settings = JSON.stringify(gs_subuser_settings);

                var gs_object_settings = {
                    add: false,
                    edit: false,
                    delete: false
                };          
                gs_object_settings = JSON.stringify(gs_object_settings);

                var gs_group_settings = {
                    add: false,
                    edit: false,
                    delete: false
                };          
                gs_group_settings = JSON.stringify(gs_group_settings);

                var gs_event_settings = {
                    add: false,
                    edit: false,
                    delete: false
                };          
                gs_event_settings = JSON.stringify(gs_event_settings);

                var gs_zone_settings = {
                    add: document.getElementById('dialog_settings_subaccount_zone_add').checked,
                    edit: document.getElementById('dialog_settings_subaccount_zone_edit').checked,
                    delete: document.getElementById('dialog_settings_subaccount_zone_delete').checked
                };          
                gs_zone_settings = JSON.stringify(gs_zone_settings);

                var gs_route_settings = {
                    add: document.getElementById('dialog_settings_subaccount_route_add').checked,
                    edit: document.getElementById('dialog_settings_subaccount_route_edit').checked,
                    delete: document.getElementById('dialog_settings_subaccount_route_delete').checked
                };          
                gs_route_settings = JSON.stringify(gs_route_settings);

                var gs_marker_settings = {
                    add: document.getElementById('dialog_settings_subaccount_marker_add').checked,
                    edit: document.getElementById('dialog_settings_subaccount_marker_edit').checked,
                    delete: document.getElementById('dialog_settings_subaccount_marker_delete').checked
                };          
                gs_marker_settings = JSON.stringify(gs_marker_settings);

                var gs_duplicate_settings = {
                    add: false,
                    edit: false,
                    delete: false
                };          
                gs_duplicate_settings = JSON.stringify(gs_duplicate_settings);

                var gs_clr_history_settings = {
                    add: false,
                    edit: false,
                    delete: false
                };          
                gs_clr_history_settings = JSON.stringify(gs_clr_history_settings);

                p = {
                    type: "subuser",
                    imei: multiselectGetValues(o),
                    marker: multiselectGetValues(s),
                    route: multiselectGetValues(l),
                    zone: multiselectGetValues(r),
                    history: document.getElementById("dialog_settings_subaccount_history").checked,
                    reports: document.getElementById("dialog_settings_subaccount_reports").checked,
					boarding: document.getElementById("dialog_settings_subaccount_boarding_point").checked,
					sendcommand: document.getElementById("dialog_settings_subaccount_send_command").checked,
                    sos: document.getElementById("dialog_settings_subaccount_sos").checked,
					live_tripreport: document.getElementById("dialog_settings_subaccount_live_tripreport").checked,
                    rilogbook: document.getElementById("dialog_settings_subaccount_rilogbook").checked,
                    dtc: document.getElementById("dialog_settings_subaccount_dtc").checked,
                    object_control: document.getElementById("dialog_settings_subaccount_object_control").checked,
                    image_gallery: document.getElementById("dialog_settings_subaccount_image_gallery").checked,
                    chat: document.getElementById("dialog_settings_subaccount_chat").checked,
                    au_active: document.getElementById("dialog_settings_subaccount_au_active").checked,
                    au: settingsEditData.subaccount_au
                };p = JSON.stringify(p);
            var v = {
                cmd: "save_subaccount",
                subaccount_id: settingsEditData.subaccount_id,
                email: c,
                password: g,
                active: m,
                account_expire: a,
                account_expire_dt: u,
                privileges: p,
                mobile_num: mn,
                subaccnt_type:$("#dialog_settings_subaccount_type").val(),
                report_list: report,
				ambulance:amb,
                u_user_settings:gs_user_settings,
                u_subuser_settings:gs_subuser_settings,
                u_object_settings:gs_object_settings,
                u_group_settings:gs_group_settings,
                u_event_settings:gs_event_settings,
                u_zone_settings:gs_zone_settings,
                u_route_settings:gs_route_settings,
                u_marker_settings:gs_marker_settings,
                u_duplicate_settings:gs_duplicate_settings,
                u_clr_history_settings:gs_clr_history_settings
            };$.ajax({
                type: "POST",
                url: "func/fn_settings.subaccounts.php",
                data: v,
                cache: !1,
                success: function(e) {
                    "OK" == e ? (settingsReloadSubaccounts(), $("#dialog_settings_subaccount_properties").dialog("close"), notifyBox("info", la.INFORMATION, la.CHANGES_SAVED_SUCCESSFULLY)) : "ERROR_EMAIL_EXISTS" == e ? notifyBox("error", la.ERROR, la.THIS_EMAIL_ALREADY_EXISTS) : "ERROR_NOT_SENT" == e ? notifyBox("error", la.ERROR, la.CANT_SEND_EMAIL + " " + la.CONTACT_ADMINISTRATOR) : "NO_PERMISSION" == e && notifyBox("error", la.ERROR, la.NO_PERMISSION)
                }
            })
    }
}

function settingsSubaccountDelete(e) {
    if(settingsUserData['access_settings_subuser']['delete']!=true){
        notifyBox("error", la.ERROR, la.NO_PERMISSION);
        return;
    }
    // utilsCheckPrivileges("viewer") && utilsCheckPrivileges("subuser") && utilsCheckPrivileges("subaccounts") && confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE, function(t) {
    utilsCheckPrivileges("viewer") && utilsCheckPrivileges("subuser") && confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE, function(t) {
        if (t) {
            var a = {
                cmd: "delete_subaccount",
                subaccount_id: e
            };
            $.ajax({
                type: "POST",
                url: "func/fn_settings.subaccounts.php",
                data: a,
                success: function(e) {
                    "OK" == e ? settingsReloadSubaccounts() : "NO_PERMISSION" == e && notifyBox("error", la.ERROR, la.NO_PERMISSION)
                }
            })
        }
    })
}

function settingsSubaccountDeleteSelected() {
    if(settingsUserData['access_settings_subuser']['delete']!=true){
        notifyBox("error", la.ERROR, la.NO_PERMISSION);
        return;
    }
    if (utilsCheckPrivileges("viewer") && utilsCheckPrivileges("subuser") && utilsCheckPrivileges("subaccounts")) {
        var e = $("#settings_main_subaccount_list_grid").jqGrid("getGridParam", "selarrrow");
        return "" == e ? void notifyBox("error", la.ERROR, la.NO_ITEMS_SELECTED) : void confirmDialog(la.ARE_YOU_SURE_YOU_WANT_TO_DELETE_SELECTED_ITEMS, function(t) {
            if (t) {
                var a = {
                    cmd: "delete_selected_subaccounts",
                    items: e
                };
                $.ajax({
                    type: "POST",
                    url: "func/fn_settings.subaccounts.php",
                    data: a,
                    success: function(e) {
                        "OK" == e ? settingsReloadSubaccounts() : "NO_PERMISSION" == e && notifyBox("error", la.ERROR, la.NO_PERMISSION)
                    }
                })
            }
        })
    }
}

function load() {
    if (isIE) {
        var e = '<div class="row">This application uses features that are unavailables in your browser.</div>';
        return e += '<div class="row">Please use one of these browsers:</div>', e += '<div class="row"><a href="http://www.mozilla.org/en-US/" target="_blank"><img style="border:0px" src="img/firefox.png" /></a>', e += "&nbsp;&nbsp;&nbsp;", e += '<a href="https://www.google.com/intl/en/chrome/browser/" target="_blank"><img style="border:0px" src="img/chrome.png" /></a></div>', void(document.getElementById("loading_panel_text").innerHTML = e)
    }
    loadLanguage(function() {
        loadSettings("server", function() {
            loadSettings("user", function() {
                loadSettings("cookies", function() {
                    loadSettings("object_groups", function() {
                        loadSettings("object_drivers", function() {
                            loadSettings("object_trailers", function() {
                                loadSettings("objects", function() {
                                    loadSettings("events", function() {
                                        loadSettings("templates", function() {
                                            loadSettings("subaccounts", function() {
                                                load2()
                                            })
                                        })
                                    })
                                })
                            })
                        })
                    })
                })
            })
        })
    })
}

function load2() {
    if (initMap(), initGui(), initGrids(), initGraph(),fnloadgrid(),reportsLoadDataAmb(),/*code update by vetrivel.N*/  objectLoadList(), objectLoadData(), eventsLoadData(),BookingRefresh(), placesGroupLoadData(), placesMarkerLoadData(), placesRouteLoadData(), placesZoneLoadData(), 1 == settingsUserData.privileges_reports && reportsLoadData(), 1 == settingsUserData.privileges_object_control && cmdTemplateLoadData(), 1 == settingsUserData.privileges_chat && chatLoadData(), 1 == settingsUserData.billing && billingLoadData(), "subuser" == settingsUserData.privileges) {
        $("#settings_main").tabs("option", "active", 4);
        var e;
        e = document.getElementById("settings_main_objects"), e.parentNode.removeChild(e), e = document.getElementById("settings_main_objects_tab"), e.parentNode.removeChild(e), e = document.getElementById("settings_main_events"), e.parentNode.removeChild(e), e = document.getElementById("settings_main_events_tab"), e.parentNode.removeChild(e), e = document.getElementById("settings_main_templates"), e.parentNode.removeChild(e), e = document.getElementById("settings_main_templates_tab"), e.parentNode.removeChild(e), e = document.getElementById("settings_main_sms"), e.parentNode.removeChild(e), e = document.getElementById("settings_main_sms_tab"), e.parentNode.removeChild(e), e = document.getElementById("settings_main_subaccounts"), e.parentNode.removeChild(e), e = document.getElementById("settings_main_subaccounts_tab"), e.parentNode.removeChild(e)
    } else 0 == settingsUserData.privileges_subaccounts && (e = document.getElementById("settings_main_subaccounts"), e.parentNode.removeChild(e), e = document.getElementById("settings_main_subaccounts_tab"), e.parentNode.removeChild(e));
    document.getElementById("loading_panel").style.display = "none", notifyCheck("expiring_objects"), notifyCheck("inactive_objects"), notifyCheck("session_check")
    
}

function unload() {
    settingsSaveCookies()
}

function objectLoadList() {
    var e = $("#side_panel_objects_object_list_grid");
    e.clearGridData(!0);

    for (var t in settingsObjectData) {
        var a = settingsObjectData[t];
        if ("true" == a.active) {
            var o = a.name.toLowerCase() + t.toLowerCase(),
                i = a.name.toLowerCase(),
                s = '<input id="object_visible_' + t + '" onClick="objectVisibleTrigger(\'' + t + '\');" class="checkbox" type="checkbox"/>',
                n = '<input id="object_follow_' + t + '" onClick="objectFollowTrigger(\'' + t + '\');" class="checkbox" type="checkbox"/>',
                l = '<a href="#"><img src="' + a.icon + '" style="width: 26px;"/></a>',
                d = "<span>" + a.name + '</span><br/><span class="status" id="object_status_' + t + '">' + la.NO_DATA + "</span>",
                r = '<span id="object_speed_' + t + '">0</span>',
                _ = '<span id="object_connection_' + t + '">' + getConnectionIcon(0) + "</span>",
                c = '<span id="object_action_menu_' + t + '" tag="' + t + '"><a href="#"><img src="theme/images/menu.svg" style="width: 16px;" title="' + la.OBJECT_CONTROL + '"/></a></span>';
            var vel='';
            try {
            vel=settingsObjectGroupData[a.group_id].name.toLowerCase();
            }
            catch(err) {
                console.log(err.message);
            }
            e.jqGrid("addRowData", t, {
                search: o,
                name_sort: i,
                group_id: a.group_id,
                show: s,
                follow: n,
                icon: l,
                name: d,
                speed: r,
                connection: _,
                menu: c,
                myimei: t,
                groupsearch:vel
            });
        }
    }
    e.setGridParam({
        sortname: "name_sort",
        sortorder: "asc"
    }).trigger("reloadGrid")
}

function objectReloadData() {
    objectsData = [], objectLoadList(), objectLoadData()
}

function objectLoadData() {
    clearTimeout(timer_objectLoadData);
    var e = {
        cmd: "load_object_data"
    };
    $.ajax({
        type: "POST",
        url: "func/fn_objects.php",
        data: e,
        dataType: "json",
        cache: !1,
        error: function() {
            timer_objectLoadData = setTimeout("objectLoadData();", 1e3 * gsValues.map_refresh)
        },
        success: function(e) {
        	try{
            for (var t in e) e[t] = transformToObjectData(e[t]);
            if (Object.keys(objectsData).length != Object.keys(e).length) objectsData = e;
            else
                for (var t in e) objectsData[t].connection = e[t].connection, objectsData[t].status = e[t].status, objectsData[t].status_string = e[t].status_string, objectsData[t].odometer = e[t].odometer, objectsData[t].engine_hours = e[t].engine_hours, objectsData[t].service = e[t].service, "" == objectsData[t].data ? objectsData[t].data = e[t].data : (objectsData[t].data.length >= settingsObjectData[t].tail_points && objectsData[t].data.pop(), objectsData[t].data.unshift(e[t].data[0])), settingsObjectData[t].protocol = e[t].protocol, settingsObjectData[t].odometer = e[t].odometer, settingsObjectData[t].engine_hours = Math.floor(e[t].engine_hours / 60 / 60);
            objectUpdateList(), objectAddAllToMap(), "fit" == settingsUserData.map_sp && 0 == gsValues.map_fit_objects_finished && (fitObjectsOnMap(), gsValues.map_fit_objects_finished = !0), objectFollow(), timer_objectLoadData = setTimeout("objectLoadData();", 1e3 * gsValues.map_refresh)
        } catch (err) {console.log(err.message)}
        }
    })
}

function objectUpdateList() {
	try{
    for (var e in objectsData) {
        if ("" != objectsData[e].data) {
            if (null != document.getElementById("object_status_" + e)) {
                document.getElementById("object_visible_" + e).checked = objectsData[e].visible, document.getElementById("object_follow_" + e).checked = objectsData[e].follow;
                var t = objectsData[e].status_string;
                if ("server" == settingsUserData.od ? document.getElementById("object_status_" + e).innerHTML = objectsData[e].data[0].dt_server : "status" == settingsUserData.od && "" != t ? document.getElementById("object_status_" + e).innerHTML = t : document.getElementById("object_status_" + e).innerHTML = objectsData[e].data[0].dt_tracker, document.getElementById("object_speed_" + e).innerHTML = objectsData[e].data[0].speed, document.getElementById("object_connection_" + e).innerHTML = getConnectionIcon(objectsData[e].connection), 1 == objectsData[e].selected) {
                    var a = objectsData[e].data[0];
                    showExtraData("object", e, a)
                }
            }
        } else null != document.getElementById("object_status_" + e) && (document.getElementById("object_visible_" + e).checked = objectsData[e].visible, document.getElementById("object_follow_" + e).checked = objectsData[e].follow, document.getElementById("object_status_" + e).innerHTML = la.NO_DATA, document.getElementById("object_speed_" + e).innerHTML = "0", document.getElementById("object_connection_" + e).innerHTML = getConnectionIcon(objectsData[e].connection));
        var o = objectsData[e].status,
            i = objectsData[e].event_ohc_color;
        objectSetListStatus(e, o, i)
    }
    for (var s in settingsObjectGroupData) null != document.getElementById("object_group_visible_" + s) && (document.getElementById("object_group_visible_" + s).checked = settingsObjectGroupData[s].visible), null != document.getElementById("object_group_follow_" + s) && (document.getElementById("object_group_follow_" + s).checked = settingsObjectGroupData[s].follow)
	}
	catch(err)
	{
		console.log(err.message);
	}
}

function objectSetListStatus(e, t, a) {
    var o = getObjectListColor(t, a),
        i = $("#side_panel_objects_object_list_grid");
    $(i).jqGrid("setRowData", e, !1, {
        background: o
    })
}

function objectAddAllToMap() {
	try{
    var e = document.getElementById("side_panel_objects_object_list_search").value;       
    objectRemoveAllFromMap();
    for (var t in objectsData) "true" == settingsObjectData[t].active && (strMatches(settingsObjectData[t].name, e) || strMatches(t, e) || strMatches(settingsObjectGroupData[settingsObjectData[t].group_id].name.toLowerCase(), e.toLowerCase()) ) && (objectAddToMap(t), objectVisible(t))
	} catch (err) {console.log(err.message)}
}

function objectRemoveAllFromMap() {
    mapLayers.realtime.clearLayers()
}

function objectSetStatusEvent(e, t, a) {
    if (void 0 != objectsData[e])
        if (0 == t && 0 == a) {
            if (objectsData[e].event = !1, objectsData[e].event_arrow_color = !1, objectsData[e].event_ohc_color = !1, "arrow" == settingsObjectData[e].map_icon) {
                var o = objectsData[e].data[0].speed,
                    i = objectsData[e].status,
                    s = getMarkerIcon(e, o, i, !1);
                objectsData[e].layers.marker.setIcon(s)
            }
            objectSetListStatus(e, objectsData[e].status, !1)
        } else {
            if (objectsData[e].event = !0, objectsData[e].event_arrow_color = t, objectsData[e].event_ohc_color = a, "arrow" == settingsObjectData[e].map_icon) {
                var o = objectsData[e].data[0].speed,
                    i = objectsData[e].status,
                    s = getMarkerIcon(e, o, i, t);
                objectsData[e].layers.marker.setIcon(s)
            }
            objectSetListStatus(e, i, a)
        }
}

function objectAddToMap(e) {
	try{
    var t = settingsObjectData[e].name;
    if ("" != objectsData[e].data) var a = objectsData[e].data[0].lat,
        o = objectsData[e].data[0].lng,
        i = objectsData[e].data[0].altitude,
        s = objectsData[e].data[0].angle,
        n = objectsData[e].data[0].speed,
        l = objectsData[e].data[0].dt_tracker,
        d = objectsData[e].data[0].params;
    else var a = 0,
        o = 0,
        n = 0,
        d = !1;
    var r = settingsUserData.map_is,
        _ = s;
    "arrow" != settingsObjectData[e].map_icon && (_ = 0);
    var c = objectsData[e].status,
        g = objectsData[e].event_arrow_color,
        m = getMarkerIcon(e, n, c, g),
        u = L.marker([a, o], {
            icon: m,
            iconAngle: _
        }),
        p = t + " (" + n + " " + la.UNIT_SPEED + ")";
    u.bindTooltip(p, {
        permanent: !0,
        offset: [20 * r, 0],
        direction: "right"
    }).openTooltip(), u.on("click", function() {
        // objectSelect(e), "" != objectsData[e].data && geocoderGetAddress(a, o, function(_) {
            objectSelect(e), "" != objectsData[e].data;
            var c = "<div id='showobjectinfoAddress'><a onclick='showobjectaddress("+a+","+"\""+o+"\""+","+"\"showobjectinfoAddress\""+")'>View Address</a></div>",
            // var c = _,
                g = urlPosition(a, o),
                m = "",
                u = "",
                p = "",
                v = new Array;
            for (var y in settingsObjectData[e].sensors) v.push(settingsObjectData[e].sensors[y]);
            var E = sortArrayByElement(v, "name");
            for (var y in E) {
                var b = E[y];
                if ("true" == b.popup) {
                    var h = getSensorValue(d, b);
                    m += "<tr><td><strong>" + b.name + ":</strong></td><td>" + h.value_full + "</td></tr>"
                }
            }
            var f = new Array;
            for (var y in settingsObjectData[e].custom_fields) f.push(settingsObjectData[e].custom_fields[y]);
            var I = sortArrayByElement(f, "name");
            for (var y in I) {
                var D = I[y];
                "true" == D.popup && (u += "<tr><td><strong>" + D.name + ":</strong></td><td>" + D.value + "</td></tr>")
            }
            var B = new Array;
            for (var y in objectsData[e].service) B.push(objectsData[e].service[y]);
            var O = sortArrayByElement(B, "name");
            for (var y in O) "true" == O[y].popup && (p += "<tr><td><strong>" + O[y].name + ":</strong></td><td>" + O[y].status + "</td></tr>");
            var j = "<table><tr><td><a onclick='mapObjectInfoReportDownload("+e+","+"\""+l+"\""+","+"\"events\""+")'>" + la.EVENTS + "</a></td><td><a onclick='mapObjectInfoReportDownload("+e+","+"\""+l+"\""+","+"\"drives_stops\""+")'>" + la.DRIVES_AND_STOPS + "</a></td></tr><tr><td><strong>" + la.OBJECT + ":</strong></td><td>" + t + "</td></tr>                  <tr><td><strong>" + la.ADDRESS + ":</strong></td><td>" + c + "</td></tr>                    <tr><td><strong>" + la.POSITION + ":</strong></td><td>" + g + "</td></tr>                   <tr><td><strong>" + la.ALTITUDE + ":</strong></td><td>" + i + " " + la.UNIT_HEIGHT + "</td></tr>                    <tr><td><strong>" + la.ANGLE + ":</strong></td><td>" + s + " &deg;</td></tr>                    <tr><td><strong>" + la.SPEED + ":</strong></td><td>" + n + " " + la.UNIT_SPEED + "</td></tr>                    <tr><td><strong>" + la.TIME + ":</strong></td><td>" + l + "</td></tr>",
            // var j = "<table>					<tr><td><strong>" + la.OBJECT + ":</strong></td><td>" + t + "</td></tr>					<tr><td><strong>" + la.ADDRESS + ":</strong></td><td>" + c + "</td></tr>					<tr><td><strong>" + la.POSITION + ":</strong></td><td>" + g + "</td></tr>					<tr><td><strong>" + la.ALTITUDE + ":</strong></td><td>" + i + " " + la.UNIT_HEIGHT + "</td></tr>					<tr><td><strong>" + la.ANGLE + ":</strong></td><td>" + s + " &deg;</td></tr>					<tr><td><strong>" + la.SPEED + ":</strong></td><td>" + n + " " + la.UNIT_SPEED + "</td></tr>					<tr><td><strong>" + la.TIME + ":</strong></td><td>" + l + "</td></tr>",
                R = getObjectOdometer(e, !1); - 1 != R && (j += "<tr><td><strong>" + la.ODOMETER + ":</strong></td><td>" + R + " " + la.UNIT_DISTANCE + "</td></tr>");
            var T = getObjectEngineHours(e, !1); - 1 != T && (j += "<tr><td><strong>" + la.ENGINE_HOURS + ":</strong></td><td>" + T + "</td></tr>");
            var k = j + m + u + p;
            j += "</table>", k += "</table>", addPopupToMap(a, o, [0, -14 * r], j, k)
        // })
    }), u.on("add", function() {
        objectAddTailToMap(e)
    }), u.on("remove", function() {
        void 0 != objectsData[e] && objectsData[e].layers.tail && mapLayers.realtime.removeLayer(objectsData[e].layers.tail)
    }), mapLayers.realtime.addLayer(u), objectsData[e].layers.marker = u
	}
	catch(err)
	{
		console.log(err.message);
	}
}

function objectAddTailToMap(e) {
    if (settingsObjectData[e].tail_points > 0) {
        objectsData[e].layers.tail && mapLayers.realtime.removeLayer(objectsData[e].layers.tail);
        var t, a = new Array;
        for (t = 0; t < objectsData[e].data.length; t++) {
            var o = objectsData[e].data[t].lat,
                i = objectsData[e].data[t].lng;
            a.push(L.latLng(o, i))
        }
        var s = L.polyline(a, {
            color: settingsObjectData[e].tail_color,
            opacity: .8,
            weight: 3
        });
        mapLayers.realtime.addLayer(s), objectsData[e].layers.tail = s
    }
}

function objectGroupVisibleTrigger(e) {
    var t = document.getElementById("object_group_visible_" + e).checked;
    for (var a in settingsObjectData) settingsObjectData[a].group_id == e && (settingsObjectGroupData[e].visible = t, null != document.getElementById("object_visible_" + a) && (document.getElementById("object_visible_" + a).checked = t, objectsData[a].visible = t, objectVisible(a)))
}

function objectVisibleTrigger(e) {
    var t = document.getElementById("object_visible_" + e).checked;
    objectsData[e].visible = t, objectVisible(e)
}

function objectVisible(e) {
    1 == objectsData[e].visible ? mapLayers.realtime.addLayer(objectsData[e].layers.marker) : mapLayers.realtime.removeLayer(objectsData[e].layers.marker)
}

function objectVisibleAllTrigger() {
    objectVisibleAll(1 == gsValues.objects_visible ? !1 : !0)
}

function objectVisibleAll(e) {
    gsValues.objects_visible = e;
    for (var t in objectsData) objectsData[t].visible = e, null != document.getElementById("object_visible_" + t) && (document.getElementById("object_visible_" + t).checked = e), objectVisible(t);
    for (var a in settingsObjectGroupData) null != document.getElementById("object_group_visible_" + a) && (settingsObjectGroupData[a].visible = e, document.getElementById("object_group_visible_" + a).checked = e)
}

function objectGroupFollowTrigger(e) {
    var t = document.getElementById("object_group_follow_" + e).checked;
    for (var a in settingsObjectData) settingsObjectData[a].group_id == e && (settingsObjectGroupData[e].follow = t, null != document.getElementById("object_follow_" + a) && (document.getElementById("object_follow_" + a).checked = t, objectsData[a].follow = t));
    objectFollow()
}

function objectFollowTrigger(e) {
    var t = document.getElementById("object_follow_" + e).checked;
    objectsData[e].follow = t, objectFollow()
}

function objectFollow() {
    var e = document.getElementById("side_panel_objects_object_list_search").value,
        t = 0,
        a = new Array;
    for (var o in objectsData)
        if (strMatches(settingsObjectData[o].name, e) && "" != objectsData[o].data && 1 == objectsData[o].follow) {
            var i = objectsData[o].data[0].lat,
                s = objectsData[o].data[0].lng;
            a.push([i, s]), t += 1
        }
    t > 1 ? map.fitBounds(a) : 1 == t && map.panTo({
        lat: i,
        lng: s
    })
}

function objectFollowAllTrigger() {
    objectFollowAll(1 == gsValues.objects_follow ? !1 : !0)
}

function objectFollowAll(e) {
    gsValues.objects_follow = e;
    for (var t in objectsData) objectsData[t].follow = e, null != document.getElementById("object_follow_" + t) && (document.getElementById("object_follow_" + t).checked = e);
    for (var a in settingsObjectGroupData) null != document.getElementById("object_group_follow_" + a) && (settingsObjectGroupData[a].follow = e, document.getElementById("object_group_follow_" + a).checked = e);
    objectFollow()
}

function objectPanToZoom(e) {
    if ("" != objectsData[e].data) {
        var t = objectsData[e].data[0].lat,
            a = objectsData[e].data[0].lng;
        map.setView([t, a], 15)
    }
}

function objectPanTo(e) {
    if ("" != objectsData[e].data) {
        var t = objectsData[e].data[0].lat,
            a = objectsData[e].data[0].lng;
        map.panTo({
            lat: t,
            lng: a
        })
    }
}

function objectSelect(e) {
    if (objectUnSelectAll(), 0 != objectsData[e].event && objectSetStatusEvent(e, !1, !1), "" != objectsData[e].data) {
        objectsData[e].selected = !0;
        var t = objectsData[e].data[0];
        showExtraData("object", e, t)
    } else notifyBox("info", la.INFORMATION, la.NO_DATA_HAS_BEEN_RECEIVED_YET), showExtraData("object", e, "")
}

function objectUnSelectAll() {
    for (var e in objectsData) objectsData[e].selected = !1
}

function utilsCheckPrivileges(e) {
    switch (e) {
        case "viewer":
            if (("" == settingsUserData.privileges || "viewer" == settingsUserData.privileges) && 0 == settingsUserData.cpanel_privileges) return notifyBox("error", la.ERROR, la.THIS_ACCOUNT_HAS_NO_PRIVILEGES_TO_DO_THAT), !1;
            break;
        case "subuser":
            if ("subuser" == settingsUserData.privileges) return notifyBox("error", la.ERROR, la.THIS_ACCOUNT_HAS_NO_PRIVILEGES_TO_DO_THAT), !1;
            break;
        case "obj_add":
        	//0 != settingsUserData.manager_id || 
            if ("false" == settingsUserData.obj_add) return notifyBox("error", la.ERROR, la.THIS_ACCOUNT_HAS_NO_PRIVILEGES_TO_DO_THAT), !1;
            break;
        case "obj_history_clear":
            if ("true" != settingsUserData.obj_history_clear) return notifyBox("error", la.ERROR, la.THIS_ACCOUNT_HAS_NO_PRIVILEGES_TO_DO_THAT), !1;
            break;
        case "obj_edit":
            if ("true" != settingsUserData.obj_edit) return notifyBox("error", la.ERROR, la.THIS_ACCOUNT_HAS_NO_PRIVILEGES_TO_DO_THAT), !1;
            break;
        case "history":
            if (1 != settingsUserData.privileges_history) return notifyBox("error", la.ERROR, la.THIS_ACCOUNT_HAS_NO_PRIVILEGES_TO_DO_THAT), !1;
            break;
        case "reports":
            if (1 != settingsUserData.privileges_reports) return notifyBox("error", la.ERROR, la.THIS_ACCOUNT_HAS_NO_PRIVILEGES_TO_DO_THAT), !1;
            break;
        case "rilogbook":
            if (1 != settingsUserData.privileges_rilogbook) return notifyBox("error", la.ERROR, la.THIS_ACCOUNT_HAS_NO_PRIVILEGES_TO_DO_THAT), !1;
            break;
        case "dtc":
            if (1 != settingsUserData.privileges_dtc) return notifyBox("error", la.ERROR, la.THIS_ACCOUNT_HAS_NO_PRIVILEGES_TO_DO_THAT), !1;
            break;
        case "object_control":
            if (1 != settingsUserData.privileges_object_control && settingsUserData.cpanel_privileges==false) return notifyBox("error", la.ERROR, la.THIS_ACCOUNT_HAS_NO_PRIVILEGES_TO_DO_THAT), !1;
            break;
        case "image_gallery":
            if (1 != settingsUserData.privileges_image_gallery) return notifyBox("error", la.ERROR, la.THIS_ACCOUNT_HAS_NO_PRIVILEGES_TO_DO_THAT), !1;
            break;
        case "live_tripreport":
            if (1 != settingsUserData.privileges_live_tripreport) return notifyBox("error", la.ERROR, la.THIS_ACCOUNT_HAS_NO_PRIVILEGES_TO_DO_THAT), !1;
            break;
        case "chat":
            if (1 != settingsUserData.privileges_chat) return notifyBox("error", la.ERROR, la.THIS_ACCOUNT_HAS_NO_PRIVILEGES_TO_DO_THAT), !1;
            break;
        case "subaccounts":
            if (1 != settingsUserData.privileges_subaccounts) return notifyBox("error", la.ERROR, la.THIS_ACCOUNT_HAS_NO_PRIVILEGES_TO_DO_THAT), !1
    }
    return !0
}

function utilsArea() {
    0 == utilsAreaData.enabled ? 1 != gsValues.map_bussy && (utilsAreaData.area_layer = map.editTools.startPolygon(), map.on("editable:drawing:end", function() {
        if (!(utilsAreaData.area_layer.getLatLngs()[0].length < 3)) {
            var e = getAreaFromLatLngs(utilsAreaData.area_layer.getLatLngs()[0]);
            if ("km" == settingsUserData.unit_distance) {
                var t = 1e-6 * e;
                t = Math.round(100 * t) / 100, t = t + " " + la.UNIT_SQ_KM
            } else {
                var t = 1e-6 * e * .386102;
                t = Math.round(100 * t) / 100, t = t + " " + la.UNIT_SQ_MI
            }
            var a = 1e-4 * e;
            a = Math.round(100 * a) / 100, a = a + " " + la.UNIT_HECTARES;
            var o = t + "</br>" + a;
            utilsAreaData.area_layer.bindTooltip(o, {
                permanent: !0,
                direction: "center"
            }).openTooltip(), map.on("editable:editing editable:drag", function() {
                var e = getAreaFromLatLngs(utilsAreaData.area_layer.getLatLngs()[0]);
                if ("km" == settingsUserData.unit_distance) {
                    var t = 1e-6 * e;
                    t = Math.round(100 * t) / 100, t = t + " " + la.UNIT_SQ_KM
                } else {
                    var t = 1e-6 * e * .386102;
                    t = Math.round(100 * t) / 100, t = t + " " + la.UNIT_SQ_MI
                }
                var a = 1e-4 * e;
                a = Math.round(100 * a) / 100, a = a + " " + la.UNIT_HECTARES;
                var o = t + "</br>" + a;
                utilsAreaData.area_layer.setTooltipContent(o), utilsAreaData.area_layer.openTooltip()
            }), map.off("editable:drawing:end")
        }
    }), utilsAreaData.enabled = !0, gsValues.map_bussy = !0, map.doubleClickZoom.disable()) : (map.editTools.stopDrawing(), 1 == map.hasLayer(utilsAreaData.area_layer) && map.removeLayer(utilsAreaData.area_layer), map.off("editable:editing editable:drag"), utilsAreaData.enabled = !1, gsValues.map_bussy = !1, map.doubleClickZoom.enable())
}

function utilsRuler() {
    0 == utilsRulerData.enabled ? 1 != gsValues.map_bussy && (utilsRulerData.line_layer = map.editTools.startPolyline(), map.on("editable:editing editable:drag", function() {
        var e = utilsRulerData.line_layer.getLatLngs(),
            t = e[e.length - 1];
        if (map.hasLayer(utilsRulerData.label_layer)) {
            var a = getLengthFromLatLngs(e);
            a = convDistanceUnits(a, "km", settingsUserData.unit_distance), a = a.toFixed(2), a += " " + la.UNIT_DISTANCE, utilsRulerData.label_layer.setLatLng(t), utilsRulerData.label_layer.setContent(a)
        } else utilsRulerData.label_layer = L.tooltip({
            permanent: !0,
            offset: [10, 0],
            direction: "right"
        }), utilsRulerData.label_layer.setLatLng(t), utilsRulerData.label_layer.setContent("0 " + la.UNIT_DISTANCE), map.addLayer(utilsRulerData.label_layer)
    }), utilsRulerData.enabled = !0, gsValues.map_bussy = !0, map.doubleClickZoom.disable()) : (map.editTools.stopDrawing(), 1 == map.hasLayer(utilsRulerData.line_layer) && map.removeLayer(utilsRulerData.line_layer), 1 == map.hasLayer(utilsRulerData.label_layer) && map.removeLayer(utilsRulerData.label_layer), map.off("editable:editing editable:drag"), utilsRulerData.enabled = !1, gsValues.map_bussy = !1, map.doubleClickZoom.enable())
}

function utilsShowDriverInfo(e) {
    var t = settingsObjectDriverData[e].name,
        a = settingsObjectDriverData[e].idn,
        o = settingsObjectDriverData[e].address,
        i = settingsObjectDriverData[e].phone,
        s = settingsObjectDriverData[e].email,
        n = settingsObjectDriverData[e].desc,
        l = settingsObjectDriverData[e].img;
    l = "" == l ? "img/user-blank.svg" : "data/user/drivers/" + l, l = '<center><img style="border:0px; width: 80px;" src="' + l + '" /></center>', text = '<div class="row">', text += '<div class="row2"><div class="width40">' + l + '</div><div class="width60">' + t + "</div></div>", "" != a && (text += '<div class="row2"><div class="width40"><strong>' + la.ID_NUMBER + ':</strong></div><div class="width60">' + a + "</div></div>"), "" != o && (text += '<div class="row2"><div class="width40"><strong>' + la.ADDRESS + ':</strong></div><div class="width60">' + o + "</div></div>"), "" != i && (text += '<div class="row2"><div class="width40"><strong>' + la.PHONE + ':</strong></div><div class="width60">' + i + "</div></div>"), "" != s && (s = '<a href="mailto:' + s + '">' + s + "</a>", text += '<div class="row2"><div class="width40"><strong>' + la.EMAIL + ':</strong></div><div class="width60">' + s + "</div></div>"), "" != n && (text += '<div class="row2"><div class="width40"><strong>' + la.DESCRIPTION + ':</strong></div><div class="width60">' + n + "</div></div>"), text += "</div>", notifyBox("info", la.DRIVER_INFO, text)
}

function utilsShowTrailerInfo(e) {
    var t = settingsObjectTrailerData[e].name,
        a = settingsObjectTrailerData[e].model,
        o = settingsObjectTrailerData[e].vin,
        i = settingsObjectTrailerData[e].plate_number,
        s = settingsObjectTrailerData[e].desc;
    text = '<div class="row">', text += '<div class="row2"><div class="width40"><strong>' + la.NAME + ':</strong></div><div class="width60">' + t + "</div></div>", "" != a && (text += '<div class="row2"><div class="width40"><strong>' + la.MODEL + ':</strong></div><div class="width60">' + a + "</div></div>"), "" != o && (text += '<div class="row2"><div class="width40"><strong>' + la.VIN + ':</strong></div><div class="width60">' + o + "</div></div>"), "" != i && (text += '<div class="row2"><div class="width40"><strong>' + la.PLATE_NUMBER + ':</strong></div><div class="width60">' + i + "</div></div>"), "" != s && (text += '<div class="row2"><div class="width40"><strong>' + la.DESCRIPTION + ':</strong></div><div class="width60">' + s + "</div></div>"), text += "</div>", notifyBox("info", la.TRAILER_INFO, text)
}

function utilsShowPassengerInfo(e) {
    var t = {
        cmd: "load_object_passenger_data",
        passenger_id: e
    };
    $.ajax({
        type: "POST",
        url: "func/fn_settings.passengers.php",
        data: t,
        dataType: "json",
        cache: !1,
        success: function(e) {
            var t = e.name,
                a = e.idn,
                o = e.address,
                i = e.phone,
                s = e.email,
                n = e.desc;
            text = '<div class="row">', text += '<div class="row2"><div class="width40"><strong>' + la.NAME + ':</strong></div><div class="width60">' + t + "</div></div>", "" != a && (text += '<div class="row2"><div class="width40"><strong>' + la.ID_NUMBER + ':</strong></div><div class="width60">' + a + "</div></div>"), "" != o && (text += '<div class="row2"><div class="width40"><strong>' + la.ADDRESS + ':</strong></div><div class="width60">' + o + "</div></div>"), "" != i && (text += '<div class="row2"><div class="width40"><strong>' + la.PHONE + ':</strong></div><div class="width60">' + i + "</div></div>"), "" != s && (s = '<a href="mailto:' + s + '">' + s + "</a>", text += '<div class="row2"><div class="width40"><strong>' + la.EMAIL + ':</strong></div><div class="width60">' + s + "</div></div>"), "" != n && (text += '<div class="row2"><div class="width40"><strong>' + la.DESCRIPTION + ':</strong></div><div class="width60">' + n + "</div></div>"), text += "</div>", notifyBox("info", la.PASSENGER_INFO, text)
        }
    })
}

function utilsShowPoint() {
    var e = document.getElementById("dialog_show_point_lat").value,
        t = document.getElementById("dialog_show_point_lng").value;
    utilsPointOnMap(e, t)
}

function utilsPointOnMap(e, t) {
    "" == e && (e = 0), "" == t && (t = 0), geocoderGetAddress(e, t, function(a) {
        var o = a,
            i = urlPosition(e, t),
            s = "<table>					<tr><td><strong>" + la.ADDRESS + ":</strong></td><td>" + o + "&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>					<tr><td><strong>" + la.POSITION + ":</strong></td><td>" + i + "</td></tr>					</table>";
        addPopupToMap(e, t, [0, 0], s, ""), map.panTo({
            lat: e,
            lng: t
        })
    })
}

function utilsSearchAddress() {
    var e = document.getElementById("dialog_address_search_addr").value;
    geocoderGetLocation(e, function(t) {
        if (void 0 == t[0].address) return void notifyBox("info", la.INFORMATION, la.NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST);
        e = t[0].address;
        var a = t[0].lat,
            o = t[0].lng,
            i = urlPosition(a, o),
            s = "<table>					<tr><td><strong>" + la.ADDRESS + ":</strong></td><td>" + e + "&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>					<tr><td><strong>" + la.POSITION + ":</strong></td><td>" + i + "</td></tr>					</table>";
        addPopupToMap(a, o, [0, 0], s, ""), map.panTo({
            lat: a,
            lng: o
        }); 
    })
}

function utilsSearchAddressAmbulance() {
    var e = document.getElementById("dialog_address_search_addr_ambulance").value;
    geocoderGetLocation(e, function(t) {
        if (void 0 == t[0].address) return void notifyBox("info", la.INFORMATION, la.NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST);
        e = t[0].address;
        var a = t[0].lat,
            o = t[0].lng,
            i = urlPosition(a, o),
            s = "<table>					<tr><td><strong>" + la.ADDRESS + ":</strong></td><td>" + e + "&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>					<tr><td><strong>" + la.POSITION + ":</strong></td><td>" + i + "</td></tr>					</table>";
        addPopupToMap(a, o, [0, 0], s, ""), map.panTo({
            lat: a,
            lng: o
        });
        map.removeLayer(placesMarkerData.edit_ambulance_layer);
        placesAmbulanceAddToMap(a, o,e);
        //map.setZoom(15),map.panTo(L.latLng(a,s));   
    })
}

function utilsSearchAddressdispensor() {
    var e = document.getElementById("dialog_address_search_addr_dispensor").value;
    geocoderGetLocation(e, function(t) {
        if (void 0 == t[0].address) return void notifyBox("info", la.INFORMATION, la.NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST);
        e = t[0].address;
        var a = t[0].lat,
            o = t[0].lng,
            i = urlPosition(a, o),
            s = "<table>                    <tr><td><strong>" + la.ADDRESS + ":</strong></td><td>" + e + "&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>                    <tr><td><strong>" + la.POSITION + ":</strong></td><td>" + i + "</td></tr>                   </table>";
        addPopupToMap(a, o, [0, 0], s, ""), map.panTo({
            lat: a,
            lng: o
        });
        map.removeLayer(placesMarkerData.edit_ambulance_layer);
        placesAmbulanceAddToMap(a, o,e);
        //map.setZoom(15),map.panTo(L.latLng(a,s));   
    })
}

function utilsVideoObject(e, t){
	var custom_fields = settingsObjectData[e].custom_fields;
	    var devID= ""
	    for (let key in custom_fields) {
		        if (custom_fields.hasOwnProperty(key)) {
				        let custom_field = custom_fields[key];
				        if (custom_field.name === "CameraDeviceID") {
						            devID = custom_field.value
						        }
				    }
		        }
	if(e !== "" && devID!==""){
		        var o = "func/fn_object.video.php?imei=" + e+"&devID="+devID;
		        if (1 == t) window.open(o, "_blank");
		    }else{
			            notifyBox("error", la.INFORMATION, la.NO_DATA)
	}
}

function utilsFollowObject(e, t) {
    if ("" == objectsData[e].data) return void notifyBox("info", la.INFORMATION, la.NO_DATA_HAS_BEEN_RECEIVED_YET);
    var a = document.getElementById("map_layer").value,
        o = "func/fn_object.follow.php?imei=" + e + "&map_layer=" + a;
    if (1 == t) window.open(o, "_blank");
    else if (void 0 == utilsFollowObjectData[e]) {
        var i = '<div style="position:absolute; top: 0px; bottom: 0px; left: 0px; right: 0px;">';
        i += '<iframe src="' + o + '" style="border: 0px; width: 100%; height: 100%;"></iframe>', i += "</div>";
        var s = settingsObjectData[e].name,
            n = $(document.createElement("div"));
        n.attr("title", la.FOLLOW + " (" + s + ")"), n.html(i), $(n).dialog({
            autoOpen: !1,
            width: 500,
            height: 400,
            minWidth: 350,
            minHeight: 250,
            resizable: !0,
            close: function() {
                utilsFollowObjectData[e] = void 0
            }
        }), $(n).dialog("open"), utilsFollowObjectData[e] = new Array, utilsFollowObjectData[e].dialog = n
    } else utilsFollowObjectData[e].dialog.dialog("moveToTop")
}

function utilsStreetViewObject(e, t) {
    if ("" == objectsData[e].data) return void notifyBox("info", la.INFORMATION, la.NO_DATA_HAS_BEEN_RECEIVED_YET);
    var a = objectsData[e].data[0].lat,
        o = objectsData[e].data[0].lng,
        i = "http://maps.google.com/maps?q=&layer=c&cbll=" + a + "," + o;
    1 == t && window.open(i, "_blank")
}

var billingData = new Array;
billingData.plan = new Array;
var timer_billingLoadData, chatData = new Array;
chatData.imei = !1, chatData.first_msg_id = !1, chatData.last_msg_id = !1, chatData.msg_count = new Array;
var timer_chatLoadData, timer_chatMsgsDTHide, chatMsgsScrollHandler = function() {
        0 == $(this).scrollTop() && 0 != chatData.first_msg_id && chatLoadMsgs("old"), $("#chat_msgs div").each(function() {
            if ($(this).position().top > 0) {
                var e = $(this).attr("title");
                if (void 0 != e && e.length > 10) return "none" == document.getElementById("chat_msgs_dt").style.display && (document.getElementById("chat_msgs_dt").style.display = "block"), clearTimeout(timer_chatMsgsDTHide), timer_chatMsgsDTHide = setTimeout(function() {
                    $("#chat_msgs_dt").fadeOut("slow")
                }, 3e3), document.getElementById("chat_msgs_dt").innerHTML = e.substring(0, 10), !1
            }
        })
    },
    timer_imgLoadData, cmdData = new Array;
cmdData.cmd_templates = new Array, cmdData.edit_cmd_schedule_id = !1, cmdData.edit_cmd_template_id = !1;
var timer_cmdLoadData,timer_cmdReloadnew, eventsData = new Array;
eventsData.last_id = -1, eventsData.events_loaded = !1;
var timer_eventsLoadData, guiDragbarObjectsHandler = function(e) {
        $("#map").css("pointer-events", "none"), resizeGridObjects(e.pageY)
    },
    guiDragbarEventsHandler = function(e) {
        $("#map").css("pointer-events", "none"), resizeGridEvents(e.pageY)
    },
    guiDragbarHistoryHandler = function(e) {
        $("#map").css("pointer-events", "none"), resizeGridHistory(e.pageY)
    },
    guiDragbarBottomPanelHandler = function(e) {
        "block" == document.getElementById("bottom_panel").style.display && ($("#map").css("pointer-events", "none"), resizeBottomPanel(e.pageY))
    };
$.pnotify.defaults.history = !1, $.pnotify.defaults.styling = "jqueryui";
var confirmResponseValue = !1,
    historyRouteData = new Array,
    historyGraphPlot, timer_historyRoutePlay, reportsData = new Array;
reportsData.reports = new Array, reportsData.edit_report_id = !1, reportsData.data_items = new Array, reportsData.event_list = new Array,reportsData.data_items.general = ["route_start", "route_end", "route_length", "move_duration", "stop_duration", "stop_count", "top_speed", "avg_speed", "overspeed_count", //"fuel_consumption", "fuel_cost", 
                                                                                                                                        "engine_work", "engine_idle", "odometer", "engine_hours", "driver", 
                                                                                                                                        //"trailer"
], reportsData.data_items.general_merged = ["group","route_start", "route_end", "route_length", "move_duration", "stop_duration", "stop_count", "top_speed", "avg_speed", "overspeed_count", 
                                                                                                                                                                                    //"fuel_consumption", "fuel_cost", 
                                                                                                                                                                                    "engine_work", "engine_idle", "odometer", "engine_hours", "driver", //"trailer",
                                                                                                                                                                                    "total"],
   reportsData.data_items.daily_km = ["route_start", "route_end", "route_length", "move_duration", "stop_duration", "stop_count", "top_speed", "avg_speed", "overspeed_count", 
                                                                                                                                                                                    //"fuel_consumption", "fuel_cost", 
                                                                                                                                                                                    "engine_work", "engine_idle", "odometer", "engine_hours", "driver", //"trailer",
                                                                                                                                                                                    "total"],
reportsData.data_items.object_info = ["imei","installation", "transport_model", "vin", "plate_number", "odometer", "engine_hours", "driver", "trailer", "gps_device", "sim_card_number","group_name","fueltype","fuel1","fuel2","temp1","temp2"],
reportsData.data_items.current_position = ["time", "position", "speed", "altitude", "angle", "status", "odometer", "engine_hours"], 
reportsData.data_items.drives_stops = ["status", "start", "end", "duration", "move_duration", "stop_duration", "route_length", "top_speed", "avg_speed",
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    //"fuel_consumption", "fuel_cost", 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    "engine_work", "engine_idle"], reportsData.data_items.travel_sheet = ["time_a", "position_a", "time_b", "position_b", "duration", "length", 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          //"fuel_consumption", "fuel_cost", 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          "total"], reportsData.data_items.overspeed = ["start", "end", "duration", "top_speed", "avg_speed", "overspeed_position"], reportsData.data_items.underspeed = ["start", "end", "duration", "top_speed", "avg_speed", "underspeed_position"], reportsData.data_items.zone_in_out = ["zone_in", "zone_out", "duration", "zone_name", "zone_position","TOTAL_ROUTE_LENGTH"], reportsData.data_items.events = ["time", "event", "event_position", "total"], reportsData.data_items.service = ["service", "last_service", "status"], reportsData.data_items.logic_sensors = ["sensor", "activation_time", "deactivation_time", "duration", "activation_position", "deactivation_position"], reportsData.data_items.acc_graph = [], reportsData.data_items.apeed_graph = [], reportsData.data_items.altitude_graph = [], reportsData.data_items.fuellevel_graph = [], reportsData.data_items.fuelfillings = ["time", "position", "before", "after", "filled", "sensor", "driver", "total"], reportsData.data_items.fuelthefts = ["time", "position", "before", "after", "stolen", "sensor", "driver", "total"], reportsData.data_items.temperature_graph = [], reportsData.data_items.sensor_graph = [],
reportsData.data_items.tripreport = ["VEHICLENO", "TRIPNAME", "HOTSPOTNAME", "DATE", "ROUTE_START", "ROUTE_END", "PROUTE_START", "PROUTE_END", "AROUTE_START", "AROUTE_END", "AVG_SPEED", "DELAY", "TAKENKM", "DURATION", "OVERSPEED_COUNT" ,"OVRTIMEPARKING" ,"VIEW_DETAIL"],
reportsData.data_items.offline = ["imei","object","date", "duration", "altitude", "angle", "speed", "position","GPS_DEVICE","sim_card_number","GROUP","fueltype","fuel1","fuel2","temp1","temp2"],
reportsData.data_items.maintenancereport = ["CLIENT_ID","STAFFID","SITE_LOCATION","OBJECT","IMEI","WORK","UNDER_WARRENTY","NOTE","TYPES_WORK","COMPANY","WORK_DATE","VEHICLE_TYPE","FUEL1","REPORTS","STAFFNAME","STATUS","SERVICE_CLOSE","REASON"], //"ACCESSORIES" remover by nandha & makesh
reportsData.data_items.tempreport = ["TIME","POSITION", "ALTITUDE", "SPEED", "IGNITION", "TEMP1", "TEMP2","TEMP3"],
reportsData.data_items.booking_report = ["control_user_name","create_date", "book_by", "emergency_address", "contact_no", "emergency_reason", "people_count","age" , "conscious", "breathing","gender" ,"person_name" , "note1" , "status","app_user_id" , "self_others", "allocated_driver_id","allocated_driver_phone" ,"allocated_vehicleno" , "allocated_imei"  , "driver_accept_time","vehicle_reached_time" ,"pickedup_time" , "reached_dest_time"],
reportsData.data_items.rfidtripreport = ["VNAME", "VEHICLENO", "VENDOR", "DRIVER", "DRIVERPH", "DRIVERRFIDID", "STARTTIME", "ENDTIME", "MILEAGE"],
reportsData.data_items.derfidtripreport=[],
reportsData.data_items.rag = ["overspeed_score", "harsh_acceleration_score", "harsh_braking_score", "harsh_cornering_score"],
reportsData.data_items.volvoreport=[],
reportsData.data_items.fuelana=[],
reportsData.data_items.fuelgraph=[],
reportsData.data_items.tempgraph=[],
reportsData.data_items.ifsinfograph=[],
reportsData.data_items.toll_report = ["zone_in", "zone_out", "duration", "zone_name", "zone_position"],
reportsData.event_list.events=["sos","SOS","pwrcut","POWER_CUT","gpsantcut","GPS_ANTENNA_CUT","lowbat","LOW_BATTERY","stopped","STOPPED","moving","MOVING","engidle","ENGINE_IDLE","overspeed","OVERSPEED","haccel","HARSH_ACCELERATION","hbrake","HARSH_BRAKING","hcorn","HARSH_CORNERING","param","PARAMETER","sensor","SENSOR","service","SERVICE","route_in","ROUTE_IN","route_out","ROUTE_OUT","zone_in","ZONE_IN","zone_out","ZONE_OUT","fuelstolen","FUELSTOLEN","lowfuel","LOWFUEL","fueldiscnt","FUELDISCNT","stopovrtime","STOPOVRTIME","aconidle","ACONENGIDLE","dstudent_sos","PEOPLESOS","unauthpeople","UNAUTHORIZEDPEOPLE"];
var timer_sessionCheck, placesGroupData = new Array;
placesGroupData.groups = new Array, placesGroupData.edit_group_id = !1;
var placesMarkerData = new Array;
placesMarkerData.markers = new Array, placesMarkerData.default_icons_loaded = !1, placesMarkerData.custom_icons_loaded = !1, placesMarkerData.marker_icon = "img/markers/places/pin-1.svg", placesMarkerData.new_marker_id = 1, placesMarkerData.edit_marker_id = !1, placesMarkerData.edit_marker_layer = !1;
var placesZoneData = new Array;
placesZoneData.zones = new Array, placesZoneData.new_zone_id = 1, placesZoneData.edit_zone_id = !1, placesZoneData.edit_zone_layer = !1;
var placesRouteData = new Array;
placesRouteData.routes = new Array, placesRouteData.new_route_id = 1, placesRouteData.edit_route_id = !1, placesRouteData.edit_route_layer = !1, placesRouteData.edit_start_label_layer = !1, placesRouteData.edit_end_label_layer = !1;
var settingsEditData = new Array,
    settingsUserData = new Array,
    settingsObjectData = new Array,
    settingsObjectGroupData = new Array,
    settingsObjectDriverData = new Array,
    settingsObjectTrailerData = new Array,
    settingsEventData = new Array,
    settingsTemplateData = new Array,
    settingsSubaccountData = new Array;
settingsEditData.object_icon = !1, settingsEditData.object_imei = !1, settingsEditData.object_duplicate_imei = !1, settingsEditData.sensor_id = !1, settingsEditData.sensor_calibration = new Array, settingsEditData.service_id = !1, settingsEditData.custom_field_id = !1, settingsEditData.group_id = !1, settingsEditData.driver_id = !1, settingsEditData.driver_img_file = !1, settingsEditData.passenger_id = !1, settingsEditData.trailer_id = !1, settingsEditData.event_id = !1, settingsEditData.event_condition = new Array, settingsEditData.template_id = !1, settingsEditData.subaccount_id = !1, settingsEditData.subaccount_au = !1, settingsEditData.default_icons_loaded = !1, settingsEditData.custom_icons_loaded = !1, settingsEditData.sounds_loaded = !1, gsValues.title = document.title, gsValues.map_fit_objects_finished = !1, gsValues.map_objects = !0, gsValues.map_markers = !0, gsValues.map_routes = !0, gsValues.map_zones = !0, gsValues.map_clusters = !0, gsValues.map_traffic = !1, gsValues.objects_visible = !0, gsValues.objects_follow = !1;
var la = [],
    map, mapMarkerIcons = new Array,
    mapLayers = new Array,
    objectsData = new Array,
    guiDragbars = new Array;
guiDragbars.objects = 200, guiDragbars.events = 200, guiDragbars.history = 200, guiDragbars.bottom_panel = 200;
var menuOnItem, timer_objectLoadData, isIE = eval("/*@cc_on!@*/!1"),
    utilsRulerData = new Array;
utilsRulerData.enabled = !1, utilsRulerData.line_layer, utilsRulerData.label_layer;
var utilsAreaData = new Array;
utilsAreaData.enabled = !1, utilsAreaData.area_layer;
var utilsFollowObjectData = new Array;


