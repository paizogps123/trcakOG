/*
 * Flexigrid for jQuery -  v1.1
 *
 * Copyright (c) 2008 Paulo P. Marinas (code.google.com/p/flexigrid/)
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://jquery.org/license
 *
 */
(function ($) {
    /*
	 * jQuery 1.9 support. browser object has been removed in 1.9
	 */
    var browser = $.browser;

    if (!browser) {
        function uaMatch(ua) {
            ua = ua.toLowerCase();

            var match = /(chrome)[ \/]([\w.]+)/.exec(ua) ||
                /(webkit)[ \/]([\w.]+)/.exec(ua) ||
                /(opera)(?:.*version|)[ \/]([\w.]+)/.exec(ua) ||
                /(msie) ([\w.]+)/.exec(ua) ||
                ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec(ua) ||
                [];

            return {
                browser: match[1] || "",
                version: match[2] || "0"
            };
        };

        var matched = uaMatch(navigator.userAgent);
        browser = {};

        if (matched.browser) {
            browser[matched.browser] = true;
            browser.version = matched.version;
        }

        // Chrome is Webkit, but Webkit is also Safari.
        if (browser.chrome) {
            browser.webkit = true;
        } else if (browser.webkit) {
            browser.safari = true;
        }
    }

    /*!
     * START code from jQuery UI
     *
     * Copyright 2011, AUTHORS.txt (http://jqueryui.com/about)
     * Dual licensed under the MIT or GPL Version 2 licenses.
     * http://jquery.org/license
     *
     * http://docs.jquery.com/UI
     */

    if (typeof $.support.selectstart != 'function') {
        $.support.selectstart = "onselectstart" in document.createElement("div");
    }

    if (typeof $.fn.disableSelection != 'function') {
        $.fn.disableSelection = function () {
            return this.bind(($.support.selectstart ? "selectstart" : "mousedown") +
                ".ui-disableSelection", function (event) {
                event.preventDefault();
            });
        };
    }

    /* END code from jQuery UI */

    $.addFlex = function (t, p) {
        if (p.height == 'auto') {
            p.height = 'calc(100% - 26px)'
        }
        if (t.grid) return false; //return if already exist
        p = $.extend({ //apply default properties
            height: 'auto', //default height
            width: 'auto', //auto width
            striped: true, //apply odd even stripes
            novstripe: false,
            minwidth: 30, //min width of columns
            minheight: 80, //min height of columns
            resizable: true, //allow table resizing
            url: false, //URL if using data from AJAX
            method: 'POST',//'POST', //data sending method
            dataType: 'xml', //type of data for AJAX, either xml or json, type of data loading, xml, json
            contentType: "application/x-www-form-urlencoded;charset=UTF-8",
            errormsg: 'Connection Error',//Error message
            usePager: false,//Whether to paginate
            nowrap: true,//Whether not to wrap
            page: 1, //current page, default current page
            total: 1, //total pages, total number of pages
            useRp: true, //use the results per page select box, is it possible to dynamically set the number of results displayed on each page?
            rp: 20, //results per page, the default number of results per page
//rpOptions: [10, 15, 20, 30, 50],
            rpOptions: [10, 15, 20, 50, 100, 200, 500],//allowed per-page values
            title: false, //Whether to include a title
            checkbox: false, //Do you want multiple check boxes?
            singleSelect: false, //Single choice or not
            clickRowCenter: false, //Click row to center
            clickRowDefault: true, //Click row to execute default event
            idProperty: 'id',
            perNumber: 'perNumber',
            pageStat: 'Displaying {from} to {to} of {total} items',//Display the styles of the current page and total pages
            pageFrom: 'From',
            pageText: 'Page',
            pageTotal: 'Total',
            findText: 'Find',
            // allow optional parameters to be passed around
            //Allow passing optional parameters
            params: [],
            procMsg: 'Processing, please wait ...', //Prompt message being processed
            query: '',//Search query criteria
            qtype: '',//Category of search query
            noMsg: 'No items',//No results message
            minColToggle: 1, //minimum allowed column to be hidden
            showToggleBtn: true, //show or hide column toggle popup
            hideOnSubmit: true,//Whether to show the mask on callback
            showTableToggleBtn: false,//Whether to display the [Show Hide Grid] button
            autoload: true,//Automatic loading, that is, making an ajax request for the first time
            blockOpacity: 0.5,//Transparency settings
            showPicList: false,//Whether to display the picture list
            addCustomContent: false,//Add custom content and use it with showPicList
            preProcess: false,//Filter data
            addTitleToCell: false, // add a title attr to cells with truncated contents
            dblClickResize: false, //auto resize column by double clicking
            onDragCol: false,
            onToggleCol: false,//The default implementation can be overridden in this method when converting between rows
            onChangeSort: false,//When changing the sorting, you can override the default implementation in this method and implement client-side sorting by yourself
            changeTip: false,//Click the sorting parameter to check that it is not empty
            sortData: {},//Pass sorting parameters when exporting after sorting operation
            onDoubleClick: false,
            onSuccess: false,//Execute after success
            onDataError: false,//Return data error callback
            convertData: null,
            reloadSuccess: false,
            lstTableData: [], //Can be passed in from
            onError: false,
            onSubmit: false, //using a custom populate function
            __mw: { //extendable middleware function holding object
                datacol: function (p, col, val) { //middleware for formatting data columns
                    var _col = (typeof p.datacol[col] == 'function') ? p.datacol[col](val) : val; //format column using function
                    if (typeof p.datacol['*'] == 'function') { //if wildcard function exists
                        return p.datacol['*'](_col); //run wildcard function
                    } else {
                        return _col; //return column without wildcard
                    }
                }
            },
            getGridClass: function (g) { //get the grid class, always returns g
                return g;
            },
            datacol: {}, //datacol middleware object 'colkey': function(colval) {}
            colResize: true, //from: http://stackoverflow.com/a/10615589
            colMove: true,
            fixed: false,
            checkboxFixed: false,
            edit: false,
            //Automatic refresh callback function
            autoLoadFunction: {},
            createTableHead:false//Merge cells method
        }, p);


        try {
            if (typeof _getRootFrameElement == 'function' && _getRootFrameElement().LS) {
                // var pageName = "page-size" + "-" + location.pathname;
                var pageName = "customize-page-size";
                var pageSize = _getRootFrameElement().LS.get(pageName);
                if (pageSize && !isNaN(pageSize)) {
                    var pageSizes = p.rpOptions;
                    var isFindPage = false;
                    for (var i = 0; i < pageSizes.length; i++) {
                        if (pageSizes[i] == pageSize) {
                            isFindPage = true;
                            break;
                        }
                    }
                    if (isFindPage) {
                        p.rp = parseInt(pageSize);
                    } else {
                        p.rp = parseInt(pageSizes[0]);
                    }
                }
            }
        } catch (e) {

        }

        //show if hidden show if hidden
        $(t).show()
            //Remove padding content and spacing remove padding and spacing
            .attr({
                cellPadding: 0,
                cellSpacing: 0,
                border: 0
            })
            //remove width properties remove width properties
            .removeAttr('width');

        // create grid class
        var g = {

            hset: {},

            rePosDrag: function () {
                var cdleft = 0 - this.hDiv.scrollLeft;
                if (this.hDiv.scrollLeft > 0) cdleft -= Math.floor(p.cgwidth / 2);
                $(g.cDrag).css({
                    top: g.hDiv.offsetTop + 1
                });
                if (this.cdpad == 0) {
                    g.getCdpad();
                }
                var cdpad = this.cdpad;
                var cdcounter = 0;
                $('div', g.cDrag).hide();
                $('thead tr:first th:visible', this.hDiv).each(function () {
                    var n = $('thead tr:first th:visible', g.hDiv).index(this);
                    var cdpos = parseInt($('div', this).width());
                    if (cdleft == 0) cdleft -= Math.floor(p.cgwidth / 2);
                    cdpos = cdpos + cdleft + cdpad;
                    if (isNaN(cdpos)) {
                        cdpos = 0;
                    }
                    $('div:eq(' + n + ')', g.cDrag).css({
                        'left': (!(browser.mozilla) ? cdpos - cdcounter : cdpos) + 'px'
                    }).show();
                    cdleft = cdpos;
                    cdcounter++;
                });
                if (p.fixed) {
                    this.reloadcDrag(g.fixedParam);
                }
            },

            fixHeight: function (newH) {
                newH = false;
                if (!newH) newH = $(g.bDiv).height();
                var hdHeight = $(this.hDiv).height();
                var cdrhight = (newH + hdHeight) == 0 ? 24 : (newH + hdHeight);
                $('div', this.cDrag).each(
                    function () {
                        // $(this).height(cdrhight);
                        //TODO modified the height of the drag line (useless setting, css hard-coded height: 45px !important;)
                        $(this).height(24);
                    }
                );
                var nd = parseInt($(g.nDiv).height(), 10);
                if (nd > newH) {
                    if (newH == 0) newH = nd;
                    if (newH == 10 || newH == 10.5) {
                        setTimeout(function () {
                            $(g.nDiv).height($(g.bDiv).height());
                        }, 1000);
                        return;
                    }
                    if (newH < 40) newH = 'auto';
                    $(g.nDiv).height(newH).width('auto')/*.width(200)*/;
                    //Solve the problem #14857 that the width of the report drop-down content will change
                    var width_ = $(".nDiv").width();
                    var widthEx = width_ <= 20 ? width_ + 100 : width_ + 20;
                    $(".nDiv").css("width", widthEx);
                } else {
                    $(g.nDiv).height('auto').width('auto');
                    nd = parseInt($(g.nDiv).height(), 10);
                    if (nd > newH) {
                        $(g.nDiv).height(newH).width('auto');
                    }
                }
                $(g.block).css({
                    height: newH,
                    marginBottom: (newH * -1)
                });
                var hrH = g.bDiv.offsetTop + newH;
                if (p.height != 'auto' && p.resizable) hrH = g.vDiv.offsetTop;
                $(g.rDiv).css({
                    height: hrH
                });
            },

            dragStart: function (dragtype, e, obj) { //default drag function start
                if (dragtype == 'colresize' && p.colResize === true) {//column resize
                    $(g.nDiv).hide();
                    $(g.nBtn).hide();
                    var n = $('div', this.cDrag).index(obj);
                    var ow = $('th:visible div:eq(' + n + ')', this.hDiv).width();
                    $(obj).addClass('dragging').siblings().hide();
                    $(obj).prev().addClass('dragging').show();
                    this.colresize = {
                        startX: e.pageX,
                        ol: parseInt(obj.style.left, 10),
                        ow: ow,
                        n: n
                    };
                    $('body').css('cursor', 'col-resize');
                } else if (dragtype == 'vresize') {//table resize
                    var hgo = false;
                    $('body').css('cursor', 'row-resize');
                    if (obj) {
                        hgo = true;
                        $('body').css('cursor', 'col-resize');
                    }
                    this.vresize = {
                        h: p.height,
                        sy: e.pageY,
                        w: p.width,
                        sx: e.pageX,
                        hgo: hgo
                    };
                } else if (dragtype == 'colMove') {//column header drag
                    $(e.target).disableSelection(); //disable selecting the column header
                    if ((p.colMove === true)) {
                        $(g.nDiv).hide();
                        $(g.nBtn).hide();
                        this.hset = $(this.hDiv).offset();
                        this.hset.right = this.hset.left + $('table', this.hDiv).width();
                        this.hset.bottom = this.hset.top + $('table', this.hDiv).height();
                        this.dcol = obj;
                        this.dcoln = $('th', this.hDiv).index(obj);
                        this.colCopy = document.createElement("div");
                        this.colCopy.className = "colCopy";
                        this.colCopy.innerHTML = obj.innerHTML;
                        if (browser.msie) {
                            this.colCopy.className = "colCopy ie";
                        }
                        $(this.colCopy).css({
                            position: 'absolute',
                            'float': 'left',
                            display: 'none',
                            textAlign: obj.align
                        });
                        $('body').append(this.colCopy);
                        $(this.cDrag).hide();
                    }
                }
                $('body').noSelect();
            },

            dragMove: function (e) {
                if (this.colresize) {//column resize
                    var n = this.colresize.n;
                    var diff = e.pageX - this.colresize.startX;
                    var nleft = this.colresize.ol + diff;
                    var nw = this.colresize.ow + diff;
                    if (nw > p.minwidth) {
                        $('div:eq(' + n + ')', this.cDrag).css('left', nleft);
                        this.colresize.nw = nw;
                    }
                } else if (this.vresize) {//table resize
                    var v = this.vresize;
                    var y = e.pageY;
                    var diff = y - v.sy;
                    if (!p.defwidth) p.defwidth = p.width;
                    if (p.width != 'auto' && !p.nohresize && v.hgo) {
                        var x = e.pageX;
                        var xdiff = x - v.sx;
                        var newW = v.w + xdiff;
                        if (newW > p.defwidth) {
                            this.gDiv.style.width = newW + 'px';
                            p.width = newW;
                        }
                    }
                    var newH = v.h + diff;
                    if ((newH > p.minheight || p.height < p.minheight) && !v.hgo) {
                        this.bDiv.style.height = newH + 'px';
                        p.height = newH;
                        this.fixHeight(newH);
                    }
                    v = null;
                } else if (this.colCopy) {
                    $(this.dcol).addClass('thMove').removeClass('thOver');
                    if (e.pageX > this.hset.right || e.pageX < this.hset.left || e.pageY > this.hset.bottom || e.pageY < this.hset.top) {
                        //this.dragEnd();
                        $('body').css('cursor', 'move');
                    } else {
                        $('body').css('cursor', 'pointer');
                    }
                    $(this.colCopy).css({
                        top: e.pageY + 10,
                        left: e.pageX + 20,
                        display: 'block'
                    });
                }

                /* $(this.bDiv).css({
                    height: (p.height=='auto') ? 'auto' : p.height+
                        (p.height.toString().indexOf('%')>0 ? "":"px")
                });*/

                if (isBrowseIE()) { //IE solves the problem of scroll bar drop-down bDiv height change
                    throttle(function () {
                        var height = getWindowHeight();
                        height = height - getTop($(this.bDiv).get(0)) - $(this.pDiv).height() - 10;
                        height = height < 0 ? 0 : height;
                        $(this.bDiv).height(height);
                    }, 1000);
                }
            },

            dragEnd: function () {
                if (this.colresize) {
                    var n = this.colresize.n;
                    var nw = this.colresize.nw;
                    $('th:visible div:eq(' + n + ')', this.hDiv).css('width', nw);
                    $('tr', this.bDiv).each(
                        function () {
                            var $tdDiv = $('td:visible div:eq(' + n + ')', this);
                            $tdDiv.css('width', nw);
                            g.addTitleToCell($tdDiv);
                        }
                    );
                    this.hDiv.scrollLeft = this.bDiv.scrollLeft;
                    $('div:eq(' + n + ')', this.cDrag).siblings().show();
                    $('.dragging', this.cDrag).removeClass('dragging');
                    this.rePosDrag();
                    this.fixHeight();
                    this.colresize = false;
                    if ($.cookies) {
                        var name = p.colModel[n].name;		// Store the widths in the cookies
                        $.cookie('flexiwidths/' + name, nw);
                    }
                } else if (this.vresize) {
                    this.vresize = false;
                } else if (this.colCopy) {
                    $(".colCopy").remove();
                    if (browser.msie) {
                        $(".colCopy .ie").remove();
                    }
                    $(this.colCopy).remove();
                    if (this.dcolt !== null) {
                        if (this.dcoln > this.dcolt) $('th:eq(' + this.dcolt + ')', this.hDiv).before(this.dcol);
                        else $('th:eq(' + this.dcolt + ')', this.hDiv).after(this.dcol);
                        this.switchCol(this.dcoln, this.dcolt);
                        $(this.cdropleft).remove();
                        $(this.cdropright).remove();
                        this.rePosDrag();
                        if (p.onDragCol) {
                            p.onDragCol(this.dcoln, this.dcolt);
                        }
                    }
                    this.dcol = null;
                    this.hset = null;
                    this.dcoln = null;
                    this.dcolt = null;
                    this.colCopy = null;
                    $('.thMove', this.hDiv).removeClass('thMove');
                    $(this.cDrag).show();
                }

                $('body').css('cursor', 'default');
                $('body').noSelect(false);
            },

            toggleCol: function (cid, visible) {
                var ncol = $("th[axis='col" + cid + "']", this.hDiv)[0];
                var n = $('thead th', g.hDiv).index(ncol);
                var cb = $('input[value=' + cid + ']', g.nDiv)[0];
                if (visible == null) {
                    visible = ncol.hidden;
                }
                if ($('input:checked', g.nDiv).length < p.minColToggle && !visible) {
                    return false;
                }
                if (visible) {
                    ncol.hidden = false;
                    $(ncol).show();
                    cb.checked = true;
                } else {
                    ncol.hidden = true;
                    $(ncol).hide();
                    cb.checked = false;
                }
                $('tbody tr', t).each(
                    function () {
                        if (visible) {
                            $('td:eq(' + n + ')', this).show();
                        } else {
                            $('td:eq(' + n + ')', this).hide();
                        }
                    }
                );
                this.rePosDrag();
                if (p.onToggleCol) {
                    p.onToggleCol(cid, visible);
                }
                return visible;
            },

            switchCol: function (cdrag, cdrop) { //switch columns
                $('tbody tr', t).each(
                    function () {
                        if (cdrag > cdrop) $('td:eq(' + cdrop + ')', this).before($('td:eq(' + cdrag + ')', this));
                        else $('td:eq(' + cdrop + ')', this).after($('td:eq(' + cdrag + ')', this));
                    }
                );
                //switch order in nDiv
                if (cdrag > cdrop) {
                    $('tr:eq(' + cdrop + ')', this.nDiv).before($('tr:eq(' + cdrag + ')', this.nDiv));
                } else {
                    $('tr:eq(' + cdrop + ')', this.nDiv).after($('tr:eq(' + cdrag + ')', this.nDiv));
                }
                if (browser.msie && browser.version < 7.0) {
                    $('tr:eq(' + cdrop + ') input', this.nDiv)[0].checked = true;
                }
                this.hDiv.scrollLeft = this.bDiv.scrollLeft;
            },

            scroll: function () {
                this.hDiv.scrollLeft = this.bDiv.scrollLeft;
                this.rePosDrag();
            },

            addDataRowJson: function (data, type) { //External loading data json format
                if (data.pagination != null) {
                    data.page = data.pagination.currentPage;
                    data.total = data.pagination.totalRecords;
                }
                p.total = data.total;
                if (!data || p.total === 0) {
                    return;
                }
                p.pages = Math.ceil(p.total / p.rp);
                p.page = data.page;
                var tbody = null;
                if (type) {
                    tbody = $(t).find('tbody');
                } else {
                    tbody = document.createElement('tbody');
                }
                var k = (data.pagination.currentPage - 1) * data.pagination.pageRecords + 1;
                $.each(data.rows, function (i, row) {
                    var tr = document.createElement('tr');
                    var jtr = $(tr);
                    if (i % 2 && p.striped) tr.className = 'erow';
                    if (row[p.idProperty] != null) {
                        tr.id = 'row' + row[p.idProperty];
                        jtr.attr('data-id', row[p.idProperty]);
                        jtr.attr('data-index', i);
                    }

                    $('thead tr:first th', g.hDiv).each( //add cell
                        function () {
                            //With too much data, the performance of createElement is lower than innerHTML
                            var td = document.createElement('td');
                            var idx;
                            if ($(this).attr('axis')) {
                                idx = $(this).attr('axis').substr(3);
                            }
                            td.align = this.align;
                            if (this.className) {
                                td.className = this.className;
                            }
                            if (p.checkbox) {
                                td.innerHTML = "<input type=\"checkbox\" class=\"selectItem\" name=\"selectItem\"  value=\"" + row[p.idProperty] + "\"/>"
                                idx -= 1;
                            }
                            // If each row is the object itself (no 'cell' key)
                            if (idx >= 0 && p.colModel[idx]) {
                                if (p.colModel[idx].name == 'index') {
                                    td.innerHTML = k;
                                    k++;
                                } else {
                                    //td.innerHTML = row[p.colModel[idx].name];
                                    var index = 0;
                                    var start = p.colModel[idx].name.indexOf('count');
                                    var end = p.colModel[idx].name.length;
                                    if (start >= 0 && p.colModel[idx].name != 'count') {
                                        var index_ = p.colModel[idx].name.substring(5, end);
                                        index = index_ == '' || null ? 0 : index_;
                                    }
                                    td.innerHTML = fixCellInfos(p, row, idx, index);
                                }
                            }
                            // If the content has a <BGCOLOR=nnnnnn> option, decode it.
                            var offs = td.innerHTML.indexOf('<BGCOLOR=');
                            if (offs > 0) {
                                $(td).css('background', text.substr(offs + 7, 7));
                            }
                            $(td).attr('abbr', $(this).attr('abbr'));
                            $(tr).append(td);
                            td = null;
                        }
                    );
                    $(tbody).append(tr);
                    tr = null;
                });
                $('tr', t).unbind();
                $(t).empty();
                if (p.checkbox) {
                    $('table tr .selectAllItem', g.hDiv)[0].checked = false;
                }
                $(t).append(tbody);
                this.addCellProp();
                this.addRowProp();
                if (p.checkbox) {
                    this.selectAllItemRow();
                    this.selectItemRow();
                }
                this.rePosDrag();
                tbody = null;
                data = null;
                i = null;
                if (p.onSuccess) {
                    p.onSuccess(this);
                }
                if (p.hideOnSubmit) {
                    $(g.block).remove();
                }
                this.hDiv.scrollLeft = this.bDiv.scrollLeft;
                if (browser.opera) {
                    $(t).css('visibility', 'visible');
                }
            },

            //External loading data json format
            appendJsonData: function (rows, head) {
                var findbodys = $(t).find('tbody');
                var tbody = null;
                if (findbodys == null || findbodys.length == 0) {
                    tbody = document.createElement('tbody');
                    $(t).append(tbody);
                } else {
                    tbody = findbodys[0];
                }
                $.each(rows, function (i, row) {
                    var tr = document.createElement('tr');
                    var jtr = $(tr);
                    if (i % 2 && p.striped) tr.className = 'erow';
                    if (row[p.idProperty] != null) {
                        tr.id = 'row' + row[p.idProperty];
                        jtr.attr('data-id', row[p.idProperty]);
                        jtr.attr('data-index', i);
                    }
                    if (row.color && row.color.toString().indexOf('#') >= 0) {
                        jtr.css('color', row.color);
                    }
                    $('thead tr:first th', g.hDiv).each( //add cell
                        function () {
                            var td = document.createElement('td');
                            var idx;
                            if ($(this).attr('axis')) {
                                idx = $(this).attr('axis').substr(3);
                            }
                            td.align = this.align;
                            if (this.className) {
                                td.className = this.className;
                            }
                            if (p.checkbox) {
                                td.innerHTML = "<input type=\"checkbox\" class=\"selectItem\" name=\"selectItem\"  value=\"" + row[p.idProperty] + "\"/>"
                                idx -= 1;
                            }
                            // If each row is the object itself (no 'cell' key)
                            if (idx >= 0 && p.colModel[idx]) {
                                if (p.colModel[idx].name == 'index') {
                                    td.innerHTML = $(t).find('tr').length + 1;
                                } else {
                                    //td.innerHTML = row[p.colModel[idx].name];
                                    var index = 0;
                                    var start = p.colModel[idx].name.indexOf('count');
                                    var end = p.colModel[idx].name.length;
                                    if (start >= 0 && p.colModel[idx].name != 'count') {
                                        var index_ = p.colModel[idx].name.substring(5, end);
                                        index = index_ == '' || null ? 0 : index_;
                                    }
                                    if (typeof p.fillCellCallback == "function") {
                                        var pos = p.fillCellCallback(p, row, idx, index);
                                        var name = p.colModel[idx].name;
                                        if (name == 'operator' || name == 'operator1') {
                                            pos = replaceOperatorIcon(pos);
                                        }
                                        td.innerHTML = pos;
                                    } else {
                                        td.innerHTML = fixCellInfos(p, row, idx, index);
                                    }
                                }
                            }
                            // If the content has a <BGCOLOR=nnnnnn> option, decode it.
                            var offs = td.innerHTML.indexOf('<BGCOLOR=');
                            if (offs > 0) {
                                $(td).css('background', text.substr(offs + 7, 7));
                            }
                            $(td).attr('abbr', $(this).attr('abbr'));
                            $(tr).append(td);
                            td = null;
                        }
                    );
                    if (head) {
                        $(tbody).prepend(tr);
                    } else {
                        $(tbody).append(tr);
                    }
                    tr = null;
                });
                $('tr', t).unbind();
//				$(t).empty();
                if (p.checkbox) {
                    $('table tr .selectAllItem', g.hDiv)[0].checked = false;
                }
//				$(t).append(tbody);
                this.addCellProp();
                this.addRowProp();
                if (p.checkbox) {
                    this.selectAllItemRow();
                    this.selectItemRow();
                }
                this.rePosDrag();
                tbody = null;
                data = null;
                i = null;
                if (p.onSuccess) {
                    p.onSuccess(this);
                }
                if (p.hideOnSubmit) {
                    $(g.block).remove();
                }
                this.hDiv.scrollLeft = this.bDiv.scrollLeft;
                if (browser.opera) {
                    $(t).css('visibility', 'visible');
                }
                //remove spaces http:
                $(g.emptyWidthDiv).remove();

                if (!this.saveData) {
                    return;
                }

                if (this.saveData.rows) {
                    this.saveData.rows = this.saveData.rows.concat(rows);
                } else {
                    this.saveData.rows = rows;
                }
            },

            appendJsonDataHtml: function (rows, head) { //External loading data json format
                var findbodys = $(t).find('tbody');
                var tbody = null;
                if (findbodys == null || findbodys.length == 0) {
                    var tbody = $('<tbody></tbody>');
                    $(t).append(tbody);
                } else {
                    tbody = $(findbodys[0]);
                }
                var htmlArr = [];
                for (var i = 0; i < rows.length; i++) {
                    var row = rows[i];
                    htmlArr.push('<tr');
                    if (i % 2 && p.striped) {
                        htmlArr.push('class="erow"');
                    }

                    if (row[p.idProperty] != null) {
                        var trId = 'row' + row[p.idProperty];
                        var trDataId = row[p.idProperty];
                        htmlArr.push('id="' + trId + '"');
                        htmlArr.push('data-id="' + trDataId + '"');
                        htmlArr.push('data-index="' + i + '"');
                    }
                    if (row.color && row.color.toString().indexOf('#') >= 0) {
                        htmlArr.push('style="color:' + row.color + '"');
                    }
                    htmlArr.push('>');
                    //add cell
                    $('thead tr:first th', g.hDiv).each(function (j, obj) {
                        htmlArr.push('<td');
                        var idx;
                        if ($(this).attr('axis')) {
                            idx = $(this).attr('axis').substr(3);
                        }
                        htmlArr.push('align="' + this.align + '"');
                        //add class
                        var _className = [];
                        if (this.className) {
                            _className.push(this.className);
                        }
                        if (p.sortname == $(obj).attr('abbr') && p.sortname) {
                            _className.push('sorted');
                        }
                        if (obj.hidden) {
                            _className.push('hide');
                        }
                        htmlArr.push('class="' + _className.join(' ') + '"');
                        if ($(obj).attr('abbr')) {
                            htmlArr.push('abbr="' + $(obj).attr('abbr') + '"');
                        }
                        var tdInnerHTML = '';
                        if (p.checkbox) {
                            tdInnerHTML = '<input type="checkbox" class="selectItem" name="selectItem" value="' + row[p.idProperty] + '"/>';
                            idx -= 1;
                        }
                        // If each row is the object itself (no 'cell' key)
                        if (idx >= 0 && p.colModel[idx]) {
                            if (p.colModel[idx].name == 'index') {
                                tdInnerHTML = $(t).find('tr').length + 1;
                            } else {
                                //td.innerHTML = row[p.colModel[idx].name];
                                var index = 0;
                                var start = p.colModel[idx].name.indexOf('count');
                                var end = p.colModel[idx].name.length;
                                if (start >= 0 && p.colModel[idx].name != 'count') {
                                    var index_ = p.colModel[idx].name.substring(5, end);
                                    index = index_ == '' || null ? 0 : index_;
                                }
                                if (typeof p.fillCellCallback == "function") {
                                    var pos = p.fillCellCallback(p, row, idx, index);
                                    var name = p.colModel[idx].name;
                                    if (name == 'operator' || name == 'operator1') {
                                        pos = replaceOperatorIcon(pos);
                                    }
                                    tdInnerHTML = pos;
                                } else {
                                    tdInnerHTML = fixCellInfos(p, row, idx, index);
                                }
                            }
                        }
                        if (tdInnerHTML == '') {
                            tdInnerHTML = '&nbsp;';
                        }
                        // If the content has a <BGCOLOR=nnnnnn> option, decode it.
                        var offs = tdInnerHTML.indexOf('<BGCOLOR=');
                        if (offs > 0) {
                            htmlArr.push('style="background:' + text.substr(offs + 7, 7) + '"');
                        }
                        htmlArr.push('>');
                        //td end
                        //div start
                        htmlArr.push('<div');
                        var divWidth = $('div:first', obj)[0].style.width;
                        if (p.nowrap == true) {
                            htmlArr.push('style="text-align:' + obj.align + ';width:' + divWidth + '"');
                        } else {
                            htmlArr.push('style="text-align:' + obj.align + ';width:' + divWidth + ';white-space:' + normal + '"');
                        }
                        htmlArr.push('>');
                        htmlArr.push(tdInnerHTML);
                        htmlArr.push('</div>');
                        //div end
                    });
                    // htmlArr.push('<tr/>');
                }
                var htmlStr = htmlArr.join(' ');
                if (head) {
                    tbody.prepend(htmlStr);
                } else {
                    tbody.append(htmlStr);
                }
                $('tr', t).unbind();
                if (p.checkbox) {
                    $('table tr .selectAllItem', g.hDiv)[0].checked = false;
                }
                // this.addCellProp();
                this.addRowProp();

                if (p.checkbox) {
                    this.selectAllItemRow();
                    this.selectItemRow();
                }

                this.rePosDrag();
                tbody = null;
                data = null;
                i = null;
                if (p.onSuccess) {
                    p.onSuccess(this);
                }
                if (p.hideOnSubmit) {
                    $(g.block).remove();
                }
                this.hDiv.scrollLeft = this.bDiv.scrollLeft;
                if (browser.opera) {
                    $(t).css('visibility', 'visible');
                }
            },

            removeRow: function (rowid) {
                var findbodys = $(t).find('tbody');
                if (findbodys != null && findbodys.length > 0) {
                    //var tbody = findbodys[0];
                    //tbody.find("tr[id='row"+rowid+"']").remove();
                    //tbody.remove("#row" + rowid);
                    $("#row" + rowid, t).remove();
                }
            },

            setFillCellFun: function (fun) {
                p.fillCellCallback = fun;
            },

            selectRowPropFun: function (fun) {
                p.selectRowProp = fun;
            },

            clickCheckBoxFun: function (fun) {
                p.clickCheckBox = fun;
            },

            mouseUpRowPropFun: function (fun) {
                p.mouseUpRowProp = fun;
            },

            getData: function (fun) {
                return this.saveData;
            },

            // parse data
            addData: function (data, type) {
                if (p.dataType == 'json') {
                    data = $.extend({rows: [], page: 0, total: 0}, data);
                    data.rows = data.infos;
                    if (typeof data.extra != "undefined") {
                        data.extra = data.extra;
                    } else {
                        data.extra = null;
                    }
                    if (data.pagination != null) {
                        data.page = data.pagination.currentPage;
                        data.total = data.pagination.totalRecords;
                    }
                    this.saveData = data;
                }
                if (p.preProcess) {
                    data = p.preProcess(data);
                }
                //$('.pReload', this.pDiv).find('i').removeClass('fa-spin');
                this.loading = false;
                $(g.emptyWidthDiv).css('left', 0).hide();

                // fix #38149 bug When there is no data when searching on a hidden page, the page scroll bar disappears
                if (!$(g.gDiv).is(':visible')) {
                    var emptyWidthDivLeft=0;
                    $(g.hDiv).find('div').each(function () {
                        // fix: firefox Get the width of the hidden label is 0 processing
                        emptyWidthDivLeft += $(this).outerWidth() || ($($(this).parent().html()).width()+10);
                    })
                    $(g.emptyWidthDiv).css('left', emptyWidthDivLeft).show();
                }

                if (!data) {
                    $('.pPageStat', this.pDiv).html(p.errormsg);
                    if (p.onSuccess) p.onSuccess(this);
                    if (p.hideOnSubmit) {
                        $(g.block).remove();
                    }
                    g.showThisDiv('block', 'block', 'block', 'block', 'block', 'block');
                    return false;
                }
                if (!data || !data.infos || !data.infos.length) {
                    var lastTh = $(g.hDiv).find('th:visible').last();
                    if (lastTh && lastTh.offset()) {
                        var parentLeft = $(lastTh).closest('.flexigrid').offset().left;
                        var emptyWidthDivLeft = lastTh.offset().left + lastTh.width() - parentLeft;
                        $(g.emptyWidthDiv).css('left', emptyWidthDivLeft).show();
                    }
                }
                if (p.dataType == 'xml') {
                    p.total = +$('rows total', data).text();
                } else {
                    p.total = data.total;
                }
                if (p.total === 0) {
                    p.lstTableData = [];
                    if (p.checkbox) {
                        $('table tr .selectAllItem', g.hDiv)[0].checked = false;
                    }
                    if (p.fixed) {
                        $(".bDiv.fixed-col").remove();
                    }
                    $('tr, a, td, div', t).unbind();
                    $(t).empty();
                    p.pages = 1;
                    p.page = 1;
                    this.buildpager();
                    $('.pPageStat', this.pDiv).html(p.noMsg);
                    if (p.onSuccess) p.onSuccess(this);
                    if (p.hideOnSubmit) {
                        $(g.block).remove();
                    }
                    g.showThisDiv('block', 'block', 'block', 'block', 'block', 'block');
                    return false;
                }
                p.pages = Math.ceil(p.total / p.rp);
                if (p.dataType == 'xml') {
                    p.page = +$('rows page', data).text();
                } else {
                    p.page = data.page;
                }
                if (!p.newp) {
                    p.newp = 1;
                }
                g.showThisDiv('block', 'block', 'block', 'block', 'block', 'block');
                this.buildpager();

                if (p.showPicList) {
                    $(this.hDiv).hide();
                    $('table', this.bDiv).empty().addClass('picList');
                    $(this.cDrag).hide();
                    $(this.pDiv).find('.pGroup').first().hide();
                    $(this.block).hide();
                    /* $(this.rDiv).hide();
                     $(this.nDiv).hide();
                     $(this.nBtn).hide();*/

                    var table = $(this.bDiv)
                    if (p.addCustomContent) {
                        p.addCustomContent(this, data);
                    }
                    this.saveData = data;
                    if (p.onSuccess) {
                        p.onSuccess(this);
                    }
                    return;
                }
                $(this.hDiv).show();
                $('table', this.bDiv).empty().removeClass('picList');
                $(this.cDrag).show();
                $(this.pDiv).find('.pGroup').first().show();
                $(this.block).show();

                // build new body
                var tbody = null;
                if (type) {
                    tbody = $(t).find('tbody');
                } else {
                    tbody = document.createElement('tbody');
                }
                var k = (data.pagination.currentPage - 1) * data.pagination.pageRecords + 1;
                if (p.dataType == 'json') {

                    $.each(data.rows, function (i, row) {

                        var tr = document.createElement('tr');
                        var jtr = $(tr);
                        if (row.name) tr.name = row.name;
                        if (row.color && row.color.toString().indexOf('#') >= 0) {
                            jtr.css('color', row.color);
                        }
                        if (row.background) {
                            jtr.css('background', row.background);
                        } else {
                            if (i % 2 && p.striped) tr.className = 'erow';
                        }

                        if (typeof row[p.idProperty] != "undefined") {
                            if (row[p.idProperty] || row[p.idProperty] === 0) {
                                row[p.idProperty] = row[p.idProperty] + "";
                            }
                        }

                        if (row[p.idProperty] || row[p.idProperty] === 0) {
                            tr.id = 'row' + row[p.idProperty];
                            jtr.attr('data-id', row[p.idProperty]);
                            jtr.attr('data-index', i);
                        }

                        $('thead tr:first th', g.hDiv).each( //add cell
                            function () {
                                var td = document.createElement('td');
                                var idx;
                                if ($(this).attr('axis')) {
                                    idx = $(this).attr('axis').substr(3);
                                }

                                td.align = this.align;
                                if (this.className) {
                                    td.className = this.className;
                                }

                                if (p.checkbox) {
                                    td.innerHTML = "<input type=\"checkbox\" class=\"selectItem\" name=\"selectItem\"  value=\"" + row[p.idProperty] + "\"/>"
                                    idx -= 1;
                                }

                                // If each row is the object itself (no 'cell' key)
                                if (idx >= 0 && p.colModel[idx]) {
                                    if (p.colModel[idx].name == 'index') {
                                        row.index = k;
                                        $(td).attr('data-index', k);
                                        $(td).addClass('index' + k);
                                        td.innerHTML = k;
                                        k++;
                                    } else {
                                        if (typeof row.cell == 'undefined') {

                                            var colModel = p.colModel[idx];
                                            if (colModel.editType && colModel.editType != 'checkBox') {

                                                if (colModel.editType === 'input') colModel.editDisplay = {};

                                                var settings = {
                                                    editType: colModel.editType,
                                                    editDisplay: colModel.editDisplay,
                                                    //input parameter verification callback
                                                    inputValid: colModel.inputValid,
                                                    dateFmt: colModel.dateFmt
                                                }
                                                g.editTable(settings, td, row);
                                            }

                                            //td.innerHTML = row[p.colModel[idx].name];
                                            var index = 0;
                                            var start = p.colModel[idx].name.indexOf('count');
                                            var end = p.colModel[idx].name.length;
                                            if (start >= 0 && p.colModel[idx].name != 'count') {
                                                var index_ = p.colModel[idx].name.substring(5, end);
                                                index = index_ == '' || null ? 0 : index_;
                                            }
                                            if (typeof p.fillCellCallback == "function") {
                                                var pos = p.fillCellCallback(p, row, idx, index);
                                                var name = p.colModel[idx].name;
                                                if (name == 'operator' || name == 'operator1') {
                                                    pos = replaceOperatorIcon(pos);
                                                }
                                                td.innerHTML = pos;
                                            } else {
                                                var key = p.colModel[idx].name;
                                                td.setAttribute("data-key", key.toLowerCase());
                                                td.setAttribute("data-" + key, row[key]);
                                                td.innerHTML = fixCellInfos(p, row, idx, index);
                                            }
                                        } else {
                                            // If the json elements aren't named (which is typical), use numeric order
                                            //JSON element is not named
                                            var iHTML = '';
                                            if (typeof row.cell[idx] != "undefined") {
                                                iHTML = (row.cell[idx] !== null) ? row.cell[idx] : ''; //null-check for Opera-browser
                                            } else {
                                                iHTML = row.cell[p.colModel[idx].name];
                                            }
                                            td.innerHTML = p.__mw.datacol(p, $(this).attr('abbr'), iHTML); //use middleware datacol to format cols
                                        }
                                    }
                                }
                                // If the content has a <BGCOLOR=nnnnnn> option, decode it.
                                var offs = td.innerHTML.indexOf('<BGCOLOR=');
                                if (offs > 0) {
                                    $(td).css('background', text.substr(offs + 7, 7));
                                }
                                $(td).attr('abbr', $(this).attr('abbr'));
                                $(tr).append(td);


                                td = null;
                            }
                        );

                        if ($('thead', this.gDiv).length < 1 && row.cell) {//handle if grid has no headers no headers
                            for (idx = 0; idx < row.cell.length; idx++) {
                                var td = document.createElement('td');
                                // If the json elements aren't named (which is typical), use numeric order
                                if (typeof row.cell[idx] != "undefined") {
                                    td.innerHTML = (row.cell[idx] != null) ? row.cell[idx] : '';//null-check for Opera-browser
                                } else {
                                    td.innerHTML = row.cell[p.colModel[idx].name];
                                }
                                $(tr).append(td);
                                td = null;
                            }
                        }
                        $(tbody).append(tr);
                        tr = null;
                    });

                } else if (p.dataType == 'xml') {
                    var i = 1;
                    $("rows row", data).each(function () {
                        i++;
                        var tr = document.createElement('tr');
                        if ($(this).attr('name')) tr.name = $(this).attr('name');
                        if ($(this).attr('color')) {
                            $(tr).css('background', $(this).attr('id'));
                        } else {
                            if (i % 2 && p.striped) tr.className = 'erow';
                        }
                        var nid = $(this).attr('id');
                        if (nid) {
                            tr.id = 'row' + nid;
                        }
                        nid = null;
                        var robj = this;
                        $('thead tr:first th', g.hDiv).each(function () {
                            var td = document.createElement('td');
                            var idx = $(this).attr('axis').substr(3);
                            td.align = this.align;
                            var text = $("cell:eq(" + idx + ")", robj).text();
                            var offs = text.indexOf('<BGCOLOR=');
                            if (offs > 0) {
                                $(td).css('background', text.substr(offs + 7, 7));
                            }
                            td.innerHTML = p.__mw.datacol(p, $(this).attr('abbr'), text); //use middleware datacol to format cols
                            $(td).attr('abbr', $(this).attr('abbr'));
                            $(tr).append(td);
                            td = null;
                        });
                        if ($('thead', this.gDiv).length < 1) {//handle if grid has no headers
                            $('cell', this).each(function () {
                                var td = document.createElement('td');
                                td.innerHTML = $(this).text();
                                $(tr).append(td);
                                td = null;
                            });
                        }
                        $(tbody).append(tr);
                        tr = null;
                        robj = null;
                    });
                }

                this.saveData = data;

                $('tr', t).unbind();

                $(t).empty();

                if (p.checkbox) {
                    $('table tr .selectAllItem', g.hDiv)[0].checked = false;
                }

                //Insert table content into table
                $(t).html(tbody);

                //Build tbody content
                this.addCellProp();
                this.addRowProp();

                if (p.checkbox) {
                    this.selectItemRow();
                }

                this.rePosDrag();
                tbody = null;
                data = null;
                i = null;
                if (p.onSuccess) {
                    p.onSuccess(this);
                }
                if (p.hideOnSubmit) {
                    $(g.block).remove();
                }
                this.hDiv.scrollLeft = this.bDiv.scrollLeft;
                if (browser.opera) {
                    $(t).css('visibility', 'visible');
                }
                //If there is a fixed attribute, render it
                if (p.fixed) {
                    this.generatorFixedCol();
                }
            },
            /**
             * Data correction
             * @param bDivObject bDiv object
             * @param hDivObject hDiv object
             */
            repairData: function (bDivObject, hDivObject) {

                //Remove all hidden data
                hDivObject.find("table thead tr th").each(function () {
                    if ($(this).css("display") === "none") {
                        $(this).css("display", "");
                    }
                });

                //Unhide all tr ​​in bDiv
                bDivObject.find("tr").each(function () {
                    if ($(this).css("display") === "none") {
                        $(this).css("display", "");
                    }
                });

                //clear margin left
                $(".hDiv").find("table").eq(0).css("margin-left", "");
                $(".bDiv").find("table").eq(0).css("margin-left", "");

                //Clear the corresponding html content and keep it empty
                $(".bDiv.fixed-col").remove();
                $(".hDiv.fixed-col").remove();
                $(".bDiv.fixed-col.fixed-col-right").remove();
                $(".hDiv.fixed-col.fixed-col-right").remove();

            },

            /**
             * Generate the col on the left
             * @param hDivCloneObject the cloned object
             * @param bDivCloneObject the cloned object
             * @param bDivByCloneWidth object width after cloning
             * @param hDivByCloneHeight The height of the object after cloning
             * @param bDivByCloneHeight object height after cloning
             */
            generatorFixLeftCol: function (hDivCloneObject, bDivCloneObject, bDivByCloneWidth, hDivByCloneHeight, bDivByCloneHeight) {
                //Generate new g.hDiv
                hDivCloneObject.addClass("fixed-col fixed-col-left");
                hDivCloneObject.css("position", "absolute");
                hDivCloneObject.css("top", 0);
                hDivCloneObject.css("overflow", "hidden");
                hDivCloneObject.css("width", bDivByCloneWidth);
                hDivCloneObject.css("height", hDivByCloneHeight);
                hDivCloneObject.css("box-shadow", "0 -1px 8px rgb(0 0 0 / 8%)");

                //Generate new bDiv
                bDivCloneObject.addClass("fixed-col fixed-col-left");
                bDivCloneObject.css("position", "absolute");
                bDivCloneObject.css("top", hDivByCloneHeight + 2);//plus border height
                bDivCloneObject.css("overflow", "hidden");
                bDivCloneObject.css("padding-bottom", "23px");
                bDivCloneObject.css("width", bDivByCloneWidth);
                var firefoxHeight = 0;
                if (isBrowseFirefox()) {
                    firefoxHeight = 8;
                }
                bDivCloneObject.css("height", bDivByCloneHeight - 23 - 2 - firefoxHeight);//Subtract padding + scroll bar height
                bDivCloneObject.css("box-shadow", "0 -1px 8px rgb(0 0 0 / 8%)");
            },

            /**
             * Calculate the pixels that the right fixed div needs to offset right
             * @returns {number} offset
             */
            calculateRightWeight: function () {

                var right;
                var bDivObject = $(".bDiv");

                var bDivHeight = bDivObject.eq(0).width();
                var bDivTrHeight = bDivObject.eq(0).find("table tr").width();
                right = bDivHeight - bDivTrHeight;

                //If the data is negative, it means
                if (right < 0) {
                    right = 0;
                }
                return right;
            },

            /**
             * Generate right fixed div
             * @param right offset
             * @param hDivCloneObjectRight
             * @param bDivCloneObjectRight
             * @param bDivByCloneWidthRight
             * @param hDivByCloneHeight
             * @param bDivByCloneHeight
             */
            generatorFixRightCol: function (right, hDivCloneObjectRight, bDivCloneObjectRight, bDivByCloneWidthRight, hDivByCloneHeight, bDivByCloneHeight) {
                //Generate hDiv on the right
                hDivCloneObjectRight.addClass("fixed-col fixed-col-right");
                hDivCloneObjectRight.css("position", "absolute");
                hDivCloneObjectRight.css("top", 0);
                hDivCloneObjectRight.css("overflow", "hidden");
                hDivCloneObjectRight.css("right", right);
                hDivCloneObjectRight.css("width", bDivByCloneWidthRight);
                hDivCloneObjectRight.css("height", hDivByCloneHeight);
                hDivCloneObjectRight.css("box-shadow", "-1px 0 8px rgb(0 0 0 / 8%)");

                //Generate bDiv on the right
                bDivCloneObjectRight.addClass("fixed-col fixed-col-right");
                bDivCloneObjectRight.css("position", "absolute");
                bDivCloneObjectRight.css("top", hDivByCloneHeight + 2);//plus border height
                bDivCloneObjectRight.css("overflow-x", "hidden");
                bDivCloneObjectRight.css("overflow-y", "auto");
                bDivCloneObjectRight.css("padding-bottom", "23px");
                bDivCloneObjectRight.css("right", right);
                bDivCloneObjectRight.css("width", bDivByCloneWidthRight);
                var firefoxHeight = 0;
                if (isBrowseFirefox()) {
                    firefoxHeight = 8;
                }
                bDivCloneObjectRight.css("height", bDivByCloneHeight - 23 - 2 - firefoxHeight);
                bDivCloneObjectRight.css("box-shadow", "-1px 0 8px rgb(0 0 0 / 8%)");
            },

            /**
             * Hide generated columns
             * @param sortArr The sequence to be hidden
             * @param sortArrRight The right sequence that needs to be hidden
             * @param hDivObject
             * @param bDivObject
             */
            hideElement: function (sortArr, sortArrRight, hDivObject, bDivObject) {

                //Hide the corresponding element
                sortArr = $.merge(sortArr, sortArrRight);

                $.each(sortArr, function (i, index) {
                    //Hide original title
                    hDivObject.find("table thead tr th").eq(index).hide();
                    //Hide original form content
                    bDivObject.find("tr").each(function () {
                        $(this).find("td").eq(index).hide()
                    });
                });
            },

            /**
             * Overload column stretched div
             * @param fixedParam
             * @param removecDrag
             */
            reloadcDrag: function (fixedParam, removecDrag) {
                if (!fixedParam) {
                    return;
                }
                var sortArr = fixedParam.sortArr,
                    sortArrRight = fixedParam.sortArrRight,
                    hDivObject = fixedParam.hDivObject,
                    bDivByCloneWidth = fixedParam.bDivByCloneWidth,
                    bDivObject = fixedParam.bDivObject;


                //Hide the corresponding element
                sortArr = $.merge(sortArr, sortArrRight);

                $(g.cDrag).find('div').show();
                if (removecDrag) {
                    for (var i = sortArr.length - 1; i >= 0; i--) {
                        //Hide cDrag stretch column
                        $(g.cDrag).find('div').eq(sortArr[i]).hide();
                    }
                }

                var th = hDivObject.find("table thead tr th:visible");
                var cDragDiv = $(g.cDrag).find('div:visible');
                var srollLeft = parseInt(bDivObject.scrollLeft());
                cDragDiv.each(function (index, ele) {
                    if (index === 0) {
                        $(this).css('left', th.eq(index).width() + bDivByCloneWidth - srollLeft);
                    } else {
                        $(this).css('left', th.eq(index).width() + parseInt(cDragDiv.eq(index - 1).css('left')))
                    }
                })
                if (!removecDrag) {
                    return;
                }
                for (var i = sortArr.length - 1; i >= 0; i--) {
                    //Hide cDrag stretch column
                    $(g.cDrag).find('div').eq(sortArr[i]).remove();
                }
            },

            /**
             * Fix and insert data into .flexigrid
             * @param flexigridObject
             * @param bDivByCloneWidth
             * @param hDivObject
             * @param bDivObject
             * @param bDivCloneObject
             * @param hDivCloneObject
             * @param bDivCloneObjectRight
             * @param hDivCloneObjectRight
             * @param trsByGeneratorHTML
             * @param thsByGeneratorHTML
             * @param trsByGeneratorHTMLRight
             * @param thsByGeneratorHTMLRight
             */
            generatorFlexigridObject: function (flexigridObject, bDivByCloneWidth, hDivObject, bDivObject, bDivCloneObject, hDivCloneObject, bDivCloneObjectRight, hDivCloneObjectRight, trsByGeneratorHTML, thsByGeneratorHTML, trsByGeneratorHTMLRight, thsByGeneratorHTMLRight) {

                //Clear first, then insert data
                bDivCloneObject.find("tbody").empty().append(trsByGeneratorHTML.join(""));
                hDivCloneObject.find("tr").empty().append(thsByGeneratorHTML.join(""));

                bDivCloneObjectRight.find("tbody").empty().append(trsByGeneratorHTMLRight.join(""));
                hDivCloneObjectRight.find("tr").empty().append(thsByGeneratorHTMLRight.join(""));

                flexigridObject.append(bDivCloneObject.prop("outerHTML"));
                flexigridObject.append(hDivCloneObject.prop("outerHTML"));
                flexigridObject.append(bDivCloneObjectRight.prop("outerHTML"));
                flexigridObject.append(hDivCloneObjectRight.prop("outerHTML"));
                this.flexigridObjectBindFixedEvent(bDivCloneObject);
            },
            flexigridObjectBindFixedEvent: function (bDivCloneObject) {
                var bDivCloneObjectTable = bDivCloneObject[0].children[0];
                var tableId = bDivCloneObjectTable.id.replace('-fixed', '');
                $('#' + bDivCloneObjectTable.id + ' tbody tr').click(function (e) {
                    var obj = (e.target || e.srcElement);
                    if ($(obj).hasClass('selectItem')) {
                        return;
                    }
                    $('#' + tableId + ' #' + this.id).click();
                }).hover(function (e) {
                    if (this.id) {
                        var fixedTr = $('#' + tableId + ' #' + this.id);
                        if (!fixedTr.hasClass('trSelectedFixed')) {
                            fixedTr.addClass('trSelectedFixed');
                        }
                    }
                }, function () {
                    if (this.id) {
                        var fixedTr = $('#' + tableId + ' #' + this.id);
                        if (fixedTr.hasClass('trSelectedFixed')) {
                            fixedTr.removeClass('trSelectedFixed');
                        }
                    }
                });
            },
            /**
             * Correct the tr height of the original table
             */
            repairtOriginHeight: function () {
                var trsByOriginByBDiv = $(".bDiv").eq(0).find("table tr");
                var trsByOriginByBDivByRight = $(".bDiv.fixed-col.fixed-col-right").eq(0).find("table tr");

                //Iterate through all new bDivs
                var tbHeight = $(".bDiv.fixed-col table tr").height();
                trsByOriginByBDiv.height(tbHeight);
                trsByOriginByBDivByRight.height(tbHeight);
                //Iterate through all new hDivs
                var thHeight = $(".hDiv.fixed-col table tr").height();
                $(".hDiv table tr").height(thHeight);
            },

            removeRepairWeight: function () {
                $(".hDiv").eq(0).find("table th").each(function () {
                    if ($(this).attr("fixedth") === "true") {
                        $(this).remove();
                    }
                });
                $(".bDiv").eq(0).find("table td").each(function () {
                    if ($(this).attr("fixedth") === "true") {
                        $(this).remove();
                    }
                });
            },

            /**
             * Insert a placeholder div to expand the width of the table to avoid reducing the width of the table after hiding the td
             * @param bDivByCloneWidthRight
             * @param bDivByCloneWidth
             */
            insertWidthDiv: function (bDivByCloneWidthRight, bDivByCloneWidth) {

                var hDivObject = $(".hDiv").eq(0);
                var fixedColObject = $(".hDiv table");
                var hDivTh = fixedColObject.find('th').length;
                var widthDiv = $(".hDiv").find('#widthDiv').length;
                if (widthDiv > hDivTh) {
                    return;
                }
                var width = fixedColObject.width() + bDivByCloneWidthRight + bDivByCloneWidth;
                var content = "<div id='widthDiv' style='width: " + width + "px;height: 1px;color: #EEF2F7;'></div>";
                hDivObject.append(content);

            },

            /**
             * Insert a placeholder div to expand the width of the table to avoid reducing the width of the table after hiding the td
             * @param bDivByCloneWidthRight
             * @param bDivByCloneWidth
             */
            insertWidtbDiv: function (bDivByCloneWidthRight, bDivByCloneWidth) {

                var bDivObject = $(".bDiv").eq(0);
                var fixedColObject = $(".bDiv table");
                var bDivTh = fixedColObject.find('th').length;
                var widthDiv = $(".bDiv").find('#widtbDiv').length;
                if (widthDiv > bDivTh) {
                    return;
                }
                var width = fixedColObject.width() + bDivByCloneWidthRight + bDivByCloneWidth;
                var content = "<div id='widtbDiv' style='width: " + width + "px;height: 1px;color: #EEF2F7;'></div>";
                bDivObject.append(content);

            },

            repairOriginWeight: function () {

                var hDivCloneObject = $(".bDiv.fixed-col").eq(0);
                if (hDivCloneObject) {
                    var width = hDivCloneObject.width() - 8;
                    //insert new th
                    var th = "<th fixedth = 'true'><div style='width: " + width + "px;'></div></th>";
                    $(".hDiv").eq(0).find("table tr").prepend(th);
                    var td = "<td fixedth = 'true'><div style='width: " + width + "px;'></div></td>";
                    $(".bDiv").eq(0).find("tr").each(function () {
                        $(this).prepend(td);
                    });

                }
            },


            repairOriginWeightRight: function () {
                var hDivCloneObjectRight = $(".bDiv.fixed-col.fixed-col-right");
                if (hDivCloneObjectRight) {
                    var width = hDivCloneObjectRight.width() - 8;
                    //insert new th
                    var th = "<th fixedth = 'true'><div style='width: " + width + "px;'></div></th>";
                    var td = "<td fixedth = 'true'><div style='width: " + width + "px;'></div></td>";
                    $(".hDiv").eq(0).find("table tr").append(th);
                    $(".bDiv").eq(0).find("tr").each(function () {
                        $(this).append(td);
                    })
                }
            },

            /**
             * Generate fixed column content that needs to be fixed
             */
            generatorFixedCol: function () {
                //Delete header custom column configuration
                //$(g.nBtn).remove();

                var FIXED_PROPERTY = "fixed";

                //Table entity class
                var flexigridObject = $(".flexigrid");

                //hDiv is the header content
                var hDivObject = $(g.hDiv);

                //bDiv is the specific content of the form
                var bDivObject = $(g.bDiv);
                //Correct data
                this.repairData(bDivObject, hDivObject);

                //To prevent modification of the original content, use clone to copy the copy
                var hDivCloneObject = hDivObject.clone(true);
                var bDivCloneObject = bDivObject.clone(true);
                var bDivCloneObjectTable = $(bDivCloneObject[0].children[0]);
                bDivCloneObjectTable.prop('id', bDivCloneObjectTable.attr('id') + '-fixed')

                var hDivCloneObjectRight = hDivObject.clone(true);
                var bDivCloneObjectRight = bDivObject.clone(true);

                //Record the sorting position of fields with fixed attributes in the list
                var sortArr = [];
                var sortArrRight = [];

                var thsByClone = hDivCloneObject.find("table thead tr th");
                var thsByOrigin = hDivObject.find("table thead tr th");

                //Store th content with fixed attribute in the array
                var thsByGeneratorHTML = [];
                var thsByGeneratorHTMLRight = [];

                //new hDiv height
                var hDivByCloneHeight;

                //new bDiv height
                var bDivByCloneHeight = 0;

                //New bDiv width, default is 5
                var bDivByCloneWidth = 0;
                var bDivByCloneWidthRight = 0;

                //Determine whether there is a checkbox, the attribute is set in p
                if (p.checkbox) {

                    if (p.checkboxFixed === "right") {
                        sortArrRight.push(0);
                        thsByGeneratorHTMLRight.push(thsByClone.eq(0).prop("outerHTML"));
                        var width = thsByOrigin.eq(0).width();
                        if (!width){
                            width = 32; //checkbox width is 32
                        }
                        bDivByCloneWidthRight = bDivByCloneWidthRight + width;
                        //Hide original checkbox
                        hDivObject.find("table thead tr th").eq(0).hide();
                        //Monitor checkbox

                    } else if (p.checkboxFixed === "left") {
                        sortArr.push(0);
                        thsByGeneratorHTML.push(thsByClone.eq(0).prop("outerHTML"));
                        var width = thsByOrigin.eq(0).width();
                        if (!width){
                            width = 32; //checkbox width is 32
                        }
                        bDivByCloneWidth = bDivByCloneWidth + width;
                        //Hide original checkbox
                        hDivObject.find("table thead tr th").eq(0).hide();
                        //Monitor checkbox

                    }
                }

                //Get the height of th
                hDivByCloneHeight = thsByOrigin.height();
                //fix: #30536 If you switch quickly under Firefox, the height cannot be obtained.
                if (hDivByCloneHeight === 0 && isBrowseFirefox()){
                    hDivByCloneHeight = 44;
                }
                //Traverse all th and store the content with fixed attributes into thsByGeneratorHTML
                thsByClone.each(function () {
                    var th = $(this);
                    if (th.attr(FIXED_PROPERTY)) {
                        var index = th.index();
                        var width = thsByOrigin.eq(index).width();
                        if (!width){
                            //fix:#27849
                            var ix = index;
                            if (p.checkbox) {
                                --ix;
                            }
                            width = p.colModel[ix].width;
                        }
                        if (th.attr(FIXED_PROPERTY) === "right") {
                            sortArrRight.push(index);
                            thsByGeneratorHTMLRight.push(th.prop("outerHTML"));
                            bDivByCloneWidthRight = bDivByCloneWidthRight + width;
                        } else {
                            sortArr.push(index);
                            thsByGeneratorHTML.push(th.prop("outerHTML"));
                            bDivByCloneWidth = bDivByCloneWidth + width;
                        }
                    }
                });

                //Get all tr
                var trs = bDivCloneObject.find("table tbody tr");

                var trsByGeneratorHTML = [];
                var trsByGeneratorHTMLRight = [];

                //~ Generate tbody
                /*  var trsByOrigin = bDivObject.find("table tbody tr");
                  trsByOrigin.each(function () {
                      //Calculate the height of bDiv
                      bDivByCloneHeight = bDivByCloneHeight + $(this).height();
                  });*/

                trs.each(function () {

                    var tr = $(this);
                    var trRight = tr.clone(true);
                    var tds = tr.find("td");

                    //Clear the contents of the original tr
                    tr.empty();
                    $.each(sortArr, function (i, index) {
                        tr.append(tds.eq(index));
                    });

                    trRight.empty();
                    $.each(sortArrRight, function (i, index) {
                        trRight.append(tds.eq(index));
                    });

                    trsByGeneratorHTML.push(tr.prop("outerHTML"));
                    trsByGeneratorHTMLRight.push(trRight.prop("outerHTML"));
                });

                this.generatorFixLeftCol(hDivCloneObject, bDivCloneObject, bDivByCloneWidth, hDivByCloneHeight, bDivObject.height());

                var right = this.calculateRightWeight();

                this.generatorFixRightCol(right, hDivCloneObjectRight, bDivCloneObjectRight, bDivByCloneWidthRight, hDivByCloneHeight, bDivObject.height());

                this.generatorFlexigridObject(flexigridObject, bDivByCloneWidth, hDivObject, bDivObject, bDivCloneObject, hDivCloneObject,
                    bDivCloneObjectRight, hDivCloneObjectRight, trsByGeneratorHTML, thsByGeneratorHTML, trsByGeneratorHTMLRight, thsByGeneratorHTMLRight);

                $(".hDiv").eq(0).find("table").css("margin-left", bDivByCloneWidth);
                $(".bDiv").eq(0).find("table").css("margin-left", bDivByCloneWidth);

                this.hideElement(sortArr, sortArrRight, hDivObject, bDivObject);

                g.fixedParam = {
                    sortArr: sortArr,
                    sortArrRight: sortArrRight,
                    hDivObject: hDivObject,
                    bDivByCloneWidth: bDivByCloneWidth,
                    bDivObject: bDivObject
                }
                this.reloadcDrag(g.fixedParam, true);

                this.repairtOriginHeight();

                this.insertWidthDiv(bDivByCloneWidthRight, bDivByCloneWidth);

                this.insertWidtbDiv(bDivByCloneWidthRight, bDivByCloneWidth);

                //Listen for selected rows
                var bDivFixObject = $(".bDiv.fixed-col");
                var bDivFixRightObject = $(".bDiv.fixed-col.fixed-col-right");

                var initScrollTop = bDivObject.scrollTop();
                $('.bDiv.fixed-col').scrollTop(initScrollTop);

                //when browser size changes
                $(window).resize(function () {
                    var firefoxHeight = 0;
                    if (isBrowseFirefox()) {
                        firefoxHeight = 8;
                    }
                    var height = $('.flexigrid .bDiv:not(.fixed-col)').height() - 23 - 2 - firefoxHeight;
                    bDivFixObject.height(height);
                    bDivFixRightObject.height(height);

                    //Set the positioning of the right fixed table
                    var right;
                    var bDivObject = $(".bDiv");

                    var bDivHeight = bDivObject.eq(0).width();
                    var bDivTrHeight = bDivObject.eq(0).find("table tr").width();
                    var hDivFixedColWidth = $(".hDiv.fixed-col").width();
                    right = bDivHeight - bDivTrHeight - hDivFixedColWidth;

                    if (right < 0) {
                        right = 0;
                    }

                    bDivFixRightObject.css("right", right);
                    $(".hDiv.fixed-col.fixed-col-right").css("right", right);

                });

                var bDivscrollTimer = null;
                var fixedscrollTimer = null;

                bDivObject.scroll(function () {
                    var scrollTop = $(this).scrollTop();
                    var scrollHeight = $(this).get(0).scrollHeight;
                    var clientHeight = $(this).get(0).clientHeight;

                    var diff = scrollHeight - clientHeight;
                    if (scrollTop >= diff) {
                        $(this).scrollTop(diff - 10);
                        clearTimeout(bDivscrollTimer);
                        return;
                    } else {
                        diff = bDivObject.scrollTop();
                    }
                    clearTimeout(bDivscrollTimer);
                    bDivscrollTimer = setTimeout(function () {
                        $('.bDiv.fixed-col').scrollTop(diff);
                    }, 100);
                })

                $('.bDiv.fixed-col.fixed-col-right').scroll(function () {
                    var scrollTop = initScrollTop || $(this).scrollTop();
                    var scrollHeight = $(this).get(0).scrollHeight;
                    var clientHeight = $(this).get(0).clientHeight;

                    var diff = scrollHeight - clientHeight;
                    if (scrollTop >= diff) {
                        $(this).scrollTop(diff - 10);
                        clearTimeout(fixedscrollTimer);
                        return;
                    }

                    clearTimeout(fixedscrollTimer);
                    fixedscrollTimer = setTimeout(function () {
                        bDivObject.scrollTop(scrollTop);
                    }, 100);
                });


                $('.flexigrid .bDiv.fixed-col tr td>div').each(function (index, ele) {
                    $(ele).prop('title', $(ele).text());
                });
                if(bDivByCloneWidth === 0){
                    $('.fixed-col.fixed-col-left').hide();
                };
                if(bDivByCloneWidthRight === 0){
                    $('.fixed-col.fixed-col-right').hide();
                };

               /* $('.fixed-col').each(function () {
                    if ($(this).width() == 0) {
                        $(this).hide();
                    }
                })
*/

                //Monitor checkbox
                /*if (p.checkbox) {

                    var checkboxAll;
                    var checkboxes;

                    if (p.checkboxFixed === "right") {
                        checkboxAll = $(".hDiv.fixed-col.fixed-col-right :checkbox");
                        checkboxes = $(".bDiv.fixed-col.fixed-col-right :checkbox");
                    } else if (p.checkboxFixed === "left") {
                        checkboxAll = $(".hDiv.fixed-col").eq(0).find(":checkbox");
                        checkboxes = $(".bDiv.fixed-col").eq(0).find(":checkbox");
                    }

                    checkboxAll.on("change", function () {
                        //Determine the check status
                        var $checkbox = $(this);
                        var $checkboxOrigin = $(".bDiv").eq(0).find(":checkbox");
                        if ($checkbox.is(':checked')) {
                            $checkboxOrigin.addClass("trSelected");
                        } else {
                            $checkboxOrigin.removeClass("trSelected");
                        }
                    });


                }*/
                if (p.checkbox) {
                    this.selectAllItemRow();
                    this.selectItemRow();
                }


            },

            changeSort: function (th) { //change sortorder
                if (this.loading) {
                    return true;
                }
                $(g.nDiv).hide();
                $(g.nBtn).hide();
                if (p.sortname) {
                    if (p.sortname == $(th).attr('abbr')) {
                        if (p.sortorder) {
                            if (p.sortorder == 'asc') {
                                p.sortorder = 'desc';
                            } else {
                                p.sortorder = 'asc';
                            }
                        } else {
                            if (p.sortorder_ == 'asc') {
                                p.sortorder = 'desc';
                            } else {
                                p.sortorder = 'asc';
                            }
                        }
                    } else {
                        p.sortorder = 'desc';
                    }
                } else if (p.sortname_) {
                    if (p.sortname_ == $(th).attr('abbr')) {
                        if (p.sortorder) {
                            if (p.sortorder == 'asc') {
                                p.sortorder = 'desc';
                            } else {
                                p.sortorder = 'asc';
                            }
                        } else {
                            if (p.sortorder_ == 'asc') {
                                p.sortorder = 'desc';
                            } else {
                                p.sortorder = 'asc';
                            }
                        }
                    } else {
                        p.sortorder = 'desc';
                    }
                } else {
                    p.sortorder = 'desc';
                }

                $(th).addClass('sorted').siblings().removeClass('sorted');
                $('.sdesc', this.hDiv).removeClass('sdesc');
                $('.sasc', this.hDiv).removeClass('sasc');
                $('div', th).addClass('s' + p.sortorder);
                p.sortname = $(th).attr('abbr');
                p.sortType = $(th).attr('sortType');
                if (p.onChangeSort) {
                    p.lstTableData = [];
                    $('tr', this.bDiv).each(function () {
                        var obj = {};
                        var trInfo = $(this).prop('outerHTML');//currenttr
                        obj.trInfos = trInfo;
                        //There is a problem with getting the corresponding value
                        $('td', this.bDiv).each(function () {
                            var isSort = $(this).attr('abbr');
                            if (p.sortname == isSort) {
                                // innerText
                                //obj[isSort] = $('span', this).html();
                                obj[isSort] = this.innerText;//For sorting
                            }
                        });
                        p.lstTableData.push(obj);
                    });
//					// console.log(p.lstTableData);
                    //Descending order (Number<String<Chinese)
                    p.lstTableData.sort(function sortInfo(data1, data2) {
                        //First determine whether you are online, and then determine whether to park. Online will be ranked first.
                        //If both parameters are of type string
                        var paramVals = p.sortname;
                        var compareType = p.sortType;
                        if (compareType == 'number') {//need to compare numbers
                            var str1 = (data1[paramVals]).toString();//Incoming data Possible type is 45.2 km/h
                            var str2 = (data2[paramVals]).toString();//Incoming data
                            var num1 = parseFloat(str1);//number(),parseInt(),parseFloat()
                            var num2 = parseFloat(str2);
                            if (num1 > num2) return 1;
                            if (num1 == num2) return 0;
                            if (num1 < num2) return -1;
                        }
                        var bnum1 = Number(data1[paramVals]);
                        var bnum2 = Number(data2[paramVals]);
                        if (!bnum1 && !bnum2) {
                            var Regx = /^[A-Za-z0-9]*$/;
                            var flag1 = Regx.test(data1[paramVals]); //letter
                            var flag2 = Regx.test(data2[paramVals]);
                            if (flag1 || flag2) {//Sort alphabetically
                                if (flag1 && !flag2) {
                                    return -1;
                                }
                                if (!flag1 && flag2) {
                                    return 1;
                                }
                                if (flag1 && flag2) {
                                    var str1 = (data1[paramVals]).toLowerCase();
                                    var str2 = (data2[paramVals]).toLowerCase();
                                    if (str1 > str2) return 1;
                                    if (str1 == str2) return 0;
                                    if (str1 < str2) return -1;
                                }
                            }
                            return data1[paramVals].localeCompare(data2[paramVals], "zh");
                        }
                        //If parameter 1 is a number and parameter 2 is a string
                        if (bnum1 && !bnum2) {
                            return -1;
                        }
                        //If parameter 1 is a string and parameter 2 is a number
                        if (!bnum1 && bnum2) {
                            return 1;
                        }
                        //If both parameters are numbers
                        if (bnum1 && bnum2) {
                            var num1 = parseInt(data1[paramVals], 10);
                            var num2 = parseInt(data2[paramVals], 10);
                            if (num1 > num2) return 1;
                            if (num1 == num2) return 0;
                            if (num1 < num2) return -1;
                        }
                    });
                    if (p.sortorder == 'asc') {
                        p.lstTableData.reverse();
                    }
                    if (p.checkbox) {
                        $('table tr .selectAllItem', this.hDiv)[0].checked = false;
                    }
                    $('tr, a, td, div', t).unbind();
                    $(t).empty();
                    p.pages = 1;
                    p.page = 1;
                    this.buildpager();
                    $('.pPageStat', this.pDiv).html(p.noMsg);
                    if (p.onSuccess) p.onSuccess(this);
                    if (p.hideOnSubmit) {
                        $(this.block).remove();
                    }
                    this.showThisDiv('block', 'block', 'block', 'block', 'block', 'block');
                    //Append
                    for (var index = 0; index < p.lstTableData.length; index++) {
                        $(t).append(p.lstTableData[index].trInfos);
                    }
                    this.addRowProp();
                } else {
                    this.populate();//Send request to background
                }
            },

            buildpager: function () { //rebuild pager based on new properties
                $('.pcontrol input', this.pDiv).val(p.page);
                $('.pcontrol span', this.pDiv).html(p.pages);
                var r1 = p.total == 0 ? 0 : (p.page - 1) * p.rp + 1;
                var r2 = r1 + p.rp - 1;
                if (p.total < r2) {
                    r2 = p.total;
                }
                var stat = p.pageStat;
                stat = stat.replace(/{from}/, r1);
                stat = stat.replace(/{to}/, r2);
                stat = stat.replace(/{total}/, p.total);
                $('.pPageStat', this.pDiv).html(stat);
            },

            // get latest data
            populate: function (selectTrigger) {
//				this.disableForm(true);
                this.showLoading(true);
                if (this.loading) {
                    return true;
                }
                if (p.onSubmit) {
                    if (!p.newp) {
                        p.newp = 1;
                    }
                    if (p.page > p.pages) {
                        p.page = p.pages;
                    }
                    var gh = p.onSubmit(this, p);
                    if (!gh) {
//						this.disableForm(false);
                        this.showLoading(false);
                        return false;
                    }
                }
                this.loading = true;
                if (!p.url) {
//					this.disableForm(false);
                    this.showLoading(false);
                    this.loading = false;
                    return false;
                }
                $('.pPageStat', this.pDiv).html(p.procMsg);
                //$('.pReload', this.pDiv).find('i').addClass('fa-spin');
                $(g.block).css({
                    top: g.bDiv.offsetTop
                });
                if (p.hideOnSubmit) {
                    $(this.gDiv).prepend(g.block);
                }
                if (browser.opera) {
                    $(t).css('visibility', 'hidden');
                }
                if (!p.newp) {
                    p.newp = 1;
                }
                if (p.page > p.pages) {
                    p.page = p.pages;
                }

                if (p.showPicList && !p.oldRp) {
                    p.oldRp = p.rp;
                }

                if (selectTrigger) {
                    p.oldRp = p.rp;
                }

                if (p.showPicList && !p.picRp) {
                    p.picRp = 15;
                }

                if (p.showPicList) {
                    p.rp = p.picRp;
                } else {
                    if (p.rp === p.picRp && !selectTrigger && p.oldRp) {
                        p.rp = p.oldRp;
                    }
                }

                var pagination = {currentPage: p.newp, pageRecords: p.rp};
                var pagin = encodeURIComponent(JSON.stringify(pagination));
                //fix:#36463
                if(p.contentType === 'application/json'){
                    pagin = encodeURIComponent(pagin);
                }
                if (p.qtype == 'devIdno') {
                    p.query = gpsGetVehicleIdno(p.query) == '' ? p.query : gpsGetVehicleIdno(p.query);
                }

                var param = [{
                    name: 'page',
                    value: p.newp
                }, {
                    name: 'rp',
                    value: p.rp
                }, /*{
					name: 'sortname',
					value: p.sortname
				}, {
					name: 'sortorder',
					value: p.sortorder
				},*/ {
                    name: 'query',
                    value: p.query
                }, {
                    name: 'qtype',
                    value: p.qtype
                }, {
                    name: 'pagin',
                    value: pagin
                }];
                if (p.params.length) {
                    var paramsExist = {page: 1,rp:1,query:1,qtype:1,pagin:1  };
                    for (var pi = 0,pilen = p.params.length; pi < pilen; pi++) {
                        var params_ = p.params[pi];
                        if (params_ && (params_.name != 'sortname' && params_.name != 'sortorder')) {
                            if (!paramsExist[params_.name]) {
                                param.push(params_);
                                paramsExist[params_.name] = 1;
                            }
                        }
//						param[param.length] = p.params[pi];
                    }
                    paramsExist = {};
                    if (p.sortData) {
                        p.sortData.sortname = p.sortname;
                        p.sortData.sortorder = p.sortorder;
                    }
                }

                if (p.sortname && p.sortorder) {//Prioritize the use of clicked
                    console.log(" p.sortorder:" + p.sortname + "___" + p.sortorder);
                    param.push({
                        name: 'sortname',
                        value: p.sortname
                    });
                    param.push({
                        name: 'sortorder',
                        value: p.sortorder
                    });
                } else if (p.sortname_ && p.sortorder_) {//Not using the overloaded
                    // console.log(" p.sortorder_:" + p.sortname_ + "___" + p.sortorder_);
                    param.push({
                        name: 'sortname',
                        value: p.sortname_
                    });
                    param.push({
                        name: 'sortorder',
                        value: p.sortorder_
                    });
                }
                if (p.contentType && p.contentType.indexOf("json") !== -1) {
                    //Convert list array to json object
                    //Can correspond to @reponseBoby in the background
                    var jsonObject = {};

                    for (var i = 0; i < param.length; i++) {
                        var temp = param[i];
                        jsonObject[temp.name] = temp.value;
                    }
                    param = JSON.stringify(jsonObject);
                }
                //The interface removes the current directory during table query
                if(p.url && p.url.indexOf('http:') === -1 && p.url.indexOf('https:') === -1 && !p.url.startsWith('/')){
                    p.url = '/' + p.url;
                }
                $.ajax({
                    type: p.method,
                    url: p.url,
                    data: param,
                    dataType: p.dataType,
                    contentType: p.contentType,
                    success: function (data,textStatus, xhr) {
                        data = getdecryptRes(data)
                        if (data.result == 0) {
                            // if (p.dataType == 'json' && (!data.infos || data.infos.length === 0)){
                            //     if (typeof $.dialog != 'undefined' && _getRootFrameAttributes('lang').lang){
                            //         $.dialog.tipWarning(_getRootFrameAttributes('lang').lang.searchNullTip);
                            //     }
                            // }
                            console.time("addData");
                            if (p.convertData && typeof p.convertData == "function") {
                                data = p.convertData(data);
                            }
                            g.addData(data);
//							g.disableForm(false);
                            g.showLoading(false);
                            //If it is an ecological eye project, block the general version vehicle parameters
                            if (parent.myUserRole && parent.myUserRole.isSTY && !p.showPicList) {
                                var list = ['plateType', 'startSpeed', 'endSpeed', 'startPosition', 'endPosition', 'industryType'];
                                hideTableColList(list);
                            }
                            console.timeEnd("addData");
                        } else if (data.result == 2) {
                            showErrorMessage(data.result);
                            //Jump directly to the login interface
                            toLoginPage();
                        } else {
//							g.disableForm(false);
                            g.showLoading(false);
                            $('.pPageStat', this.pDiv).html(p.noMsg);
                            showErrorMessage(data.result, data.message);
                            //$('.pReload', this.pDiv).find('i').removeClass('fa-spin');
                            g.loading = false;
                            if (p.onDataError) {
                                p.onDataError(data);
                            }
                        }
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        try {
                            if (p.onError) p.onError(XMLHttpRequest, textStatus, errorThrown);
                        } catch (e) {
                        }
//						g.disableForm(false);
                        g.showLoading(false);
                        $('.pPageStat', this.pDiv).html(p.noMsg);
                        //$('.pReload', this.pDiv).find('i').removeClass('fa-spin');
                        g.loading = false;
                    }
                });
            },

            getHeaderInfo: function () {
                var columns = $(g.hDiv).find('th');
                var columnArr = [];
                columns.each(function (i, item) {
                    columnArr.push(item);
                });
                columnArr = columnArr.filter(function (th) {
                    var _th = $(th);
                    if (_th.hasClass('cth')) {
                        return false;
                    }
                    if (_th.hasClass('operator')) {
                        return false;
                    }
                    if (_th.is(':hidden') && !_th.attr('fixed')) {
                        return false;
                    }
                    return true;
                })
                var headerInfo = [];
                for (var i = 0; i < columnArr.length; i++) {
                    var _th = $(columnArr[i]);
                    headerInfo.push({
                        filed: _th.attr('class'),
                        title: _th.text(),
                    })
                }
                return JSON.stringify(headerInfo);
            },
            //Generate background set code
            generateRowSetCode: function () {
                var headerInfo = g.getHeaderInfo();
                headerInfo = JSON.parse(headerInfo);
                var codeStr = "";
                for (var i = 0; i < headerInfo.length; i++) {
                    codeStr += '// ' + headerInfo[i]['title'];
                    codeStr += '\n';
                    codeStr += 'exportDataDto.setExportRowData("' + headerInfo[i]['filed'] + '", "");'
                    codeStr += '\n';
                }
                console.log(codeStr);
            },
            //Get the top margin from the body
            getTop: function (e) {
                var offset = 0;
                var obj = e;
                while (obj != null && obj != document.body) {
                    offset += obj.offsetTop;
                    obj = obj.offsetParent;
                }
                while (obj != null && e != document.body) {
                    offset -= e.scrollTop;
                    e = e.parentElement;
                }
                return offset;
            },

            //Get the left margin from the body
            getLeft: function (e) {
                var offset = 0;
                var obj = e;
                while (obj != null && obj != document.body) {
                    offset += obj.offsetLeft;
                    obj = obj.offsetParent;
                }
                while (e != null && e != document.body) {
                    offset -= e.scrollLeft;
                    e = e.parentElement;
                }
                return offset;
            },

            //Page masking
            showLoading: function (flag) {
                if (flag) {
                    if (!($('.lockmask-flexgrid', 'body') && $('.lockmask-flexgrid', 'body').get(0))) {
                        var isBody = true;
                        var width = $('body').width();
                        var height = $('body').height();
                        if (width == 0) {
                            width = getWindowWidth();
                        }
                        if (height == 0) {
                            height = getWindowHeight();
                        }
                        var top = g.getTop($('body').get(0));
                        var left = g.getLeft($('body').get(0));

                        if (height < 42) {
                            top += (height - 42) / 2;
                        } else {
                            top += (height - 42) * 0.3;
                        }
                        left += (width - 106) / 2;
                        var content = '<div class="lockmask-flexgrid" style="z-index: 9999999;position: fixed;left: 0;top: 0;width: 100%;height: 100%;">';
                        if (isBody) {
                            content += '<div class="lockmask-top-flexgrid" style="width: 100%; height: 100%;"></div>';
                            if (width == 0) {
                                content += '<div class="lockmask-content-flexgrid" style="left: 40%; top: 30%">';
                            } else {
                                content += '<div class="lockmask-content-flexgrid" style="left: ' + left + 'px; top: ' + top + 'px;">';
                            }
                        } else {
                            content += '<div class="lockmask-top-flexgrid" style="width: ' + width + 'px; height: ' + height + 'px;"></div>';
                            content += '<div class="lockmask-content-flexgrid" style="left: ' + left + 'px; top: ' + top + 'px;">';
                        }
                        var loadingTitle = '';
                        if (_getRootFrameAttributes('lang').lang) {
                            loadingTitle = _getRootFrameAttributes('lang').lang.loading;
                        }
                        content += '<div class="lockmask-loading-flexgrid">' + loadingTitle + '</div>';
                        content += '</div>';
                        content += '</div>';
                        $('body').append(content);
                    }
                } else {
                    if ($('.lockmask-flexgrid', 'body') && $('.lockmask-flexgrid', 'body')[0]) {
                        $($('.lockmask-flexgrid', 'body')[0]).remove();
                    }
                }
            },

            doSearch: function () {
                p.query = $('input[name=q]', g.sDiv).val();
                p.qtype = $('select[name=qtype]', g.sDiv).val();
                p.newp = 1;
                this.populate();
            },

            changePage: function (ctype) { //change page
                if (this.loading) {
                    return true;
                }
                switch (ctype) {
                    case 'first':
                        if (p.pages && p.newp && p.page) {
                            p.newp = 1;
                        }
                        break;
                    case 'prev':
                        if (p.page > 1) {
                            p.newp = parseInt(p.page, 10) - 1;
                        }
                        break;
                    case 'next':
                        if (p.page < p.pages) {
                            p.newp = parseInt(p.page, 10) + 1;
                        }
                        break;
                    case 'last':
                        if (p.pages && p.newp && p.page) {
                            p.newp = p.pages;
                        }
                        break;
                    case 'input':
                        var nv = parseInt($('.pcontrol input', this.pDiv).val(), 10);
                        if (isNaN(nv)) {
                            nv = 1;
                        }
                        if (nv < 1) {
                            nv = 1;
                        } else if (!p.pages) {
                            nv = 1;
                        } else if (nv > p.pages) {
                            nv = p.pages;
                        }
                        $('.pcontrol input', this.pDiv).val(nv);
                        if (p.pages && p.newp && p.page) {
                            p.newp = nv;
                        }
                        break;
                }
                if (p.newp == p.page || !p.page || !p.newp || !p.pages) {
                    return false;
                }
                if (p.onChangePage) {
                    p.onChangePage(p.newp);
                } else {
                    this.populate();
                }
            },

            addCellProp: function () {

                $('tbody tr td', g.bDiv).each(function () {

                    var that = this;

                    if (!$(this).find('div').html()) {
                        var tdDiv = document.createElement('div');
                        var n = $('td', $(this).parent()).index(this);
                        var pth = $('th:eq(' + n + ')', g.hDiv).get(0);

                        if (pth != null) {
                            if (p.sortname == $(pth).attr('abbr') && p.sortname) {
                                this.className = 'sorted';
                            }
                            $(tdDiv).css({
                                textAlign: pth.align,
                                width: $('div:first', pth)[0].style.width
                            });
                            if (pth.hidden) {
                                $(this).css('display', 'none');
                            }
                        }
                        if (p.nowrap == false) {
                            $(tdDiv).css('white-space', 'normal');
                        }
                        if (this.innerHTML == '') {
                            this.innerHTML = '&nbsp;';
                        }
                        //HTML content of td
                        tdDiv.innerHTML = this.innerHTML;
                        var prnt = $(this).parent()[0];
                        var pid = false;
                        if (prnt.id) {
                            pid = prnt.id.substr(3);
                        }
                        if (pth != null) {
                            if (pth.process) pth.process(tdDiv, pid);
                        }
                        $(this).empty().append(tdDiv).removeAttr('width'); //wrap content
                        g.addTitleToCell(tdDiv);
                    }
                });


            },

            getCellDim: function (obj) {// get cell prop for editable event
                var ht = parseInt($(obj).height(), 10);
                var pht = parseInt($(obj).parent().height(), 10);
                var wt = parseInt(obj.style.width, 10);
                var pwt = parseInt($(obj).parent().width(), 10);
                var top = obj.offsetParent.offsetTop;
                var left = obj.offsetParent.offsetLeft;
                var pdl = parseInt($(obj).css('paddingLeft'), 10);
                var pdt = parseInt($(obj).css('paddingTop'), 10);
                return {
                    ht: ht,
                    wt: wt,
                    top: top,
                    left: left,
                    pdl: pdl,
                    pdt: pdt,
                    pht: pht,
                    pwt: pwt
                };
            },

            addRowProp: function (obj) {

                if (!obj) {
                    obj = $('tbody tr', g.bDiv);
                }

                var tableId = $('table', g.bDiv).attr('id');

                obj.on('click', function (e) {

                    var obj = (e.target || e.srcElement);
                    if (obj.href || obj.type) return true;
                    if (e.ctrlKey || e.metaKey) {
                        // mousedown already took care of this case
                        return;
                    }

                    var dataId = $(this).attr("data-id");
                    var fixColCheckboxObject = $('.bDiv.fixed-col').find('tr[data-id="' + dataId + '"]').find(".selectItem");

                    if (p.clickRowDefault) {
                        if (p.checkbox && !$(this).find("td .selectItem")[0].disabled) {
                            if ($(this).hasClass('trSelected')) {
                                $(this).find("td .selectItem")[0].checked = false;
                                fixColCheckboxObject.prop("checked", false);
                            } else {
                                $(this).find("td .selectItem")[0].checked = true;
                                fixColCheckboxObject.prop("checked", true);
                            }
                        }
                    }

                    if (p.clickRowCenter) {
                        $(g.bDiv).scrollTop(g.getTop(this) - g.getTop(g.bDiv) - $(g.bDiv).height() / 2);
                    }

                    if (p.clickRowDefault) {
                        if (p.singleSelect) {
                            $(this).addClass('trSelected');
                            if (p.fixed && this.id) {
                                $('#' + tableId + '-fixed #' + this.id).addClass('trSelected');
                            }
                        } else {
                            $(this).toggleClass('trSelected');
                            if (p.fixed && this.id) {
                                $('#' + tableId + '-fixed #' + this.id).toggleClass('trSelected');
                            }
                        }

                        if (p.singleSelect && !g.multisel) {
                            $(this).siblings().removeClass('trSelected');
                            if (p.fixed && this.id) {
                                $('#' + tableId + '-fixed #' + this.id).siblings().removeClass('trSelected');
                            }
                            if (p.checkbox && !$(this).find("td .selectItem")[0].disabled) {
                                $(this).find("td .selectItem")[0].checked = false;
                            }
                        }

                        if (p.checkbox) {
                            var checkAll = true;
                            $('tbody tr .selectItem', g.bDiv).each(function () {
                                if ($(this).val() != "" && !this.checked) {
                                    checkAll = false;
                                }
                            });

                            var fixedSelectAllItem = $(g.hDiv).parent().find('.hDiv.fixed-col table tr .selectAllItem')[0];
                            if (checkAll) {
                                $('table tr .selectAllItem', g.hDiv)[0].checked = true;
                                if (p.checkboxFixed && fixedSelectAllItem) {
                                    fixedSelectAllItem.checked = true;
                                }
                            } else {
                                $('table tr .selectAllItem', g.hDiv)[0].checked = false;
                                if (p.checkboxFixed && fixedSelectAllItem) {
                                    fixedSelectAllItem.checked = false;
                                }
                            }
                        }
                    }

                    if (typeof p.selectRowProp == "function") {
                        p.selectRowProp(this);
                    }
                    if (p.onClick) {
                        var index = $(this).attr('data-index');
                        var row = g.saveData.rows[index];
                        p.onClick(this, row, e, g, p);
                    }
                }).on('mousedown', function (e) {
                    if (e.shiftKey) {
                        $(this).toggleClass('trSelected');
                        g.multisel = true;
                        this.focus();
                        $(g.gDiv).noSelect();
                    }
                    if (e.ctrlKey || e.metaKey) {
                        $(this).toggleClass('trSelected');
                        g.multisel = true;
                        this.focus();
                    }
                }).on('mouseup', function (e) {
                    if (g.multisel && !(e.ctrlKey || e.metaKey)) {
                        g.multisel = false;
                        $(g.gDiv).noSelect(false);
                    }
                    if (typeof p.mouseUpRowProp == "function") {
                        p.mouseUpRowProp(this, e);
                    }
                }).on('dblclick', function (e) {
                    if (p.onDoubleClick) {
                        var index = $(this).attr('data-index');
                        var row = g.saveData.rows[index];
                        p.onDoubleClick(this, row, e, g, p);
                    }
                }).hover(function (e) {
                    if (g.multisel && e.shiftKey) {
                        $(this).toggleClass('trSelected');
                    }
                    if (p.fixed && this.id) {
                        var fixedTr = $('#' + tableId + '-fixed #' + this.id);
                        if (!fixedTr.hasClass('trSelectedFixed')) {
                            fixedTr.addClass('trSelectedFixed');
                        }
                    }
                }, function () {
                    if (p.fixed && this.id) {
                        var fixedTr = $('#' + tableId + '-fixed #' + this.id);
                        if (fixedTr.hasClass('trSelectedFixed')) {
                            fixedTr.removeClass('trSelectedFixed');
                        }
                    }
                });
                if (browser.msie && browser.version < 7.0) {
                    $(this).hover(function () {
                        $(this).addClass('trOver');
                    }, function () {
                        $(this).removeClass('trOver');
                    });
                }
            },

            combo_flag: true,

            combo_resetIndex: function (selObj) {
                if (this.combo_flag) {
                    selObj.selectedIndex = 0;
                }
                this.combo_flag = true;
            },

            combo_doSelectAction: function (selObj) {
                eval(selObj.options[selObj.selectedIndex].value);
                selObj.selectedIndex = 0;
                this.combo_flag = false;
            },

            //Add title attribute to div if cell contents is truncated
            addTitleToCell: function (tdDiv) {
                if (p.addTitleToCell) {
                    var $span = $('<span />').css('display', 'none'),
                        $div = (tdDiv instanceof jQuery) ? tdDiv : $(tdDiv),
                        div_w = $div.outerWidth(),
                        span_w = 0;

                    $('body').children(':first').before($span);
                    $span.html($div.html());
                    $span.css('font-size', '' + $div.css('font-size'));
                    $span.css('padding-left', '' + $div.css('padding-left'));
                    span_w = $span.innerWidth();
                    $span.remove();

                    if (span_w > div_w) {
                        $div.attr('title', $div.text());
                    } else {
                        $div.removeAttr('title');
                    }
                }
            },

            autoResizeColumn: function (obj) {
                if (!p.dblClickResize) {
                    return;
                }
                var n = $('div', this.cDrag).index(obj),
                    $th = $('th:visible div:eq(' + n + ')', this.hDiv),
                    ol = parseInt(obj.style.left, 10),
                    ow = $th.width(),
                    nw = 0,
                    nl = 0,
                    $span = $('<span />');
                $('body').children(':first').before($span);
                $span.html($th.html());
                $span.css('font-size', '' + $th.css('font-size'));
                $span.css('padding-left', '' + $th.css('padding-left'));
                $span.css('padding-right', '' + $th.css('padding-right'));
                nw = $span.width();
                $('tr', this.bDiv).each(function () {
                    var $tdDiv = $('td:visible div:eq(' + n + ')', this),
                        spanW = 0;
                    $span.html($tdDiv.html());
                    $span.css('font-size', '' + $tdDiv.css('font-size'));
                    $span.css('padding-left', '' + $tdDiv.css('padding-left'));
                    $span.css('padding-right', '' + $tdDiv.css('padding-right'));
                    spanW = $span.width();
                    nw = (spanW > nw) ? spanW : nw;
                });
                $span.remove();
                nw = (p.minWidth > nw) ? p.minWidth : nw;
                nl = ol + (nw - ow);
                $('div:eq(' + n + ')', this.cDrag).css('left', nl);
                this.colresize = {
                    nw: nw,
                    n: n
                };
                g.dragEnd();
            },

            selectItemRow: function () {
                var object, checkbox;
                if (p.checkboxFixed && p.checkboxFixed != 'none') {
                    object = $(g.bDiv).parent().find('.bDiv.fixed-col tbody tr .selectItem');
                    checkbox = $('tbody tr .selectItem', g.bDiv);
                } else {
                    object = $('tbody tr .selectItem', g.bDiv);
                }

                object.unbind('click');
                var tableId = $('table', g.bDiv).attr('id');
                object.bind('click', function () {
                    if (!p.checkboxFixed || p.checkboxFixed == 'none') {
                        $(this).parent('div').parent('td').parent('tr').toggleClass('trSelected');
                    } else {
                        var index = $(this).parent('div').parent('td').parent('tr').index();
                        var tr = checkbox.parent('div').parent('td').parent('tr').eq(index);
                        tr.toggleClass('trSelected');
                        if (p.fixed) {
                            $('#' + tableId + '-fixed #' + tr[0].id).toggleClass('trSelected');
                        }
                        tr.find('.selectItem')[0].checked = tr.hasClass('trSelected');
                    }

                    var checkAll = true;
                    object.each(function () {
                        if ($(this).val() != "" && !this.checked) {
                            checkAll = false;
                        }
                    });

                    var fixedSelectAllItem = $(g.hDiv).parent().find('.hDiv.fixed-col table tr .selectAllItem')[0];
                    if (checkAll) {
                        $('table tr .selectAllItem', g.hDiv)[0].checked = true;
                        if (checkbox && fixedSelectAllItem) {
                            fixedSelectAllItem.checked = true;
                        }
                    } else {
                        $('table tr .selectAllItem', g.hDiv)[0].checked = false;
                        if (checkbox && fixedSelectAllItem) {
                            fixedSelectAllItem.checked = false;
                        }
                    }
                    if (typeof p.clickCheckBox == "function") {
                        p.clickCheckBox(this);
                    }
                    /*					if (typeof p.selectRowProp == "function") {
                                            p.selectRowProp($(this).parent('div').parent('td').parent('tr').get(0));
                                        }*/
                });
            },

            //Monitor checkbox
            selectAllItemRow: function () {

                var object;
                if (p.checkboxFixed === "left" && p.fixed) {
                    object = $(".hDiv.fixed-col table tr .selectAllItem");
                } else if (p.checkboxFixed === "right" && p.fixed) {
                    object = $(".hDiv.fixed-col.fixed-col-right table tr .selectAllItem");
                } else {
                    object = $('table tr .selectAllItem', g.hDiv);
                }

                var tableId = $('table', g.bDiv).attr('id');
                object.unbind('click');
                object.bind('click', function () {
                    if (this.checked) {
                        $('tbody tr', g.bDiv).each(function () {
                            if (!$('.selectItem', this)[0].checked && !$('.selectItem', this)[0].disabled) {
                                $('.selectItem', this)[0].checked = true;
                            }
                            $(this).addClass("trSelected");
                            if (p.fixed && this.id) {
                                $('#' + tableId + '-fixed #' + this.id).addClass('trSelected');
                            }
                            if (typeof p.selectRowProp == "function") {
                                p.selectRowProp(this, 'selAll');
                            }
                        });
                        $(".bDiv.fixed-col :checkbox").prop("checked", true);
                    } else {
                        $('tbody tr', g.bDiv).each(function () {
                            if ($('.selectItem', this)[0].checked && !$('.selectItem', this)[0].disabled) {
                                $('.selectItem', this)[0].checked = false;
                            }
                            $(this).removeClass("trSelected");
                            if (p.fixed && this.id) {
                                $('#' + tableId + '-fixed #' + this.id).removeClass('trSelected');
                            }
                            if (typeof p.selectRowProp == "function") {
                                p.selectRowProp(this, 'delAll');
                            }
                        });
                        $(".bDiv.fixed-col :checkbox").prop("checked", false);
                    }
//					if(this.checked) {
//$('tbody tr .selectItem', g.bDiv)[0].checked = true;
//						$('tbody tr', g.bDiv).addClass("trSelected");
//					}else {
//$('tbody tr .selectItem', g.bDiv)[0].checked = false;
//						$('tbody tr', g.bDiv).removeClass("trSelected");
//					}
                });

            },

            addUsepager: function () {
                // add pager
                if (p.usePager) {
                    g.pDiv.className = 'pDiv';
                    g.pDiv.innerHTML = '<div class="pDiv2"></div>';
                    $(g.bDiv).after(g.pDiv);
                    var html = ' <div class="pGroup"> <div class="pFirst pButton"><i class="bi bi-chevron-double-left"></i></div><div class="pPrev pButton"><i class="bi bi-chevron-left"></i></div> </div> <div class="btnseparator"></div> <div class="pGroup"><span class="pcontrol">' + p.pageFrom + ' <input type="text" size="4" value="1" /> ' + p.pageText + '&nbsp&nbsp' + p.pageTotal + ' <span> 1 </span>' + p.pageText + '</span></div> <div class="btnseparator"></div> <div class="pGroup"> <div class="pNext pButton"><i class="bi bi-chevron-right"></i></div><div class="pLast pButton"><i class="bi bi-chevron-double-right"></i></div> </div> <div class="btnseparator"></div> <div class="pGroup"> <div class="pReload pButton"><i class="fa fa-spinner fa-3x fa-fw"></i></div> </div> <div class="btnseparator"></div> <div class="pGroup"><span class="pPageStat"></span></div>';
                    $('div', g.pDiv).html(html);
                    $('.pReload', g.pDiv).click(function () {
                        if (p.pages && p.newp && p.page) {
                            p.newp = p.page;
                            g.populate();
                        }

                        //If a callback function is set, it is executed here
                        if (p.autoLoadFunction && typeof p.autoLoadFunction === 'function') {
                            p.autoLoadFunction();
                        }

                    });
                    $('.pFirst', g.pDiv).click(function () {
                        g.changePage('first');
                    });
                    $('.pPrev', g.pDiv).click(function () {
                        g.changePage('prev');
                    });
                    $('.pNext', g.pDiv).click(function () {
                        g.changePage('next');
                    });
                    $('.pLast', g.pDiv).click(function () {
                        g.changePage('last');
                    });
                    $('.pcontrol input', g.pDiv).keydown(function (e) {
                        if (e.keyCode == 13) {
                            g.changePage('input');
                        }
                    });
                    if (browser.msie && browser.version < 7) $('.pButton', g.pDiv).hover(function () {
                        $(this).addClass('pBtnOver');
                    }, function () {
                        $(this).removeClass('pBtnOver');
                    });
                    if (p.useRp) {
                        var opt = '',
                            sel = '';
                        for (var nx = 0; nx < p.rpOptions.length; nx++) {
                            if (p.rp == p.rpOptions[nx]) sel = 'selected="selected"';
                            else sel = '';
                            opt += "<option value='" + p.rpOptions[nx] + "' " + sel + " >" + p.rpOptions[nx] + "&nbsp;&nbsp;</option>";
                        }
                        $('.pDiv2', g.pDiv).prepend("<div class='pGroup'><span> " + p.perNumber + " </span><select name='rp'>" + opt + "</select></div> <div class='btnseparator'></div>");
                        $('select', g.pDiv).change(function () {
                            if (p.onRpChange) {
                                p.onRpChange(+this.value);
                            } else {
                                p.rp = +this.value;
                                if (p.pages && p.page) {
                                    p.newp = 1;
                                    g.populate(true);
                                }
                            }

                            try {
                                if (typeof _getRootFrameElement == 'function' && _getRootFrameElement().LS) {
                                    // var pageName = "page-size" + "-" + location.pathname;
                                    var pageName = "customize-page-size";
                                    _getRootFrameElement().LS.set(pageName, this.value);
                                }
                            } catch (e) {

                            }
                        });
                    }
                    //add search button
                    if (p.searchitems) {
                        $('.pDiv2', g.pDiv).prepend("<div class='pGroup'> <div class='pSearch pButton'><span></span></div> </div>  <div class='btnseparator'></div>");
                        $('.pSearch', g.pDiv).click(function () {
                            $(g.sDiv).slideToggle('fast', function () {
                                $('.sDiv:visible input:first', g.gDiv).trigger('focus');
                            });
                        });
                        //add search box
                        g.sDiv.className = 'sDiv';
                        var sitems = p.searchitems;
                        var sopt = '', sel = '';
                        for (var s = 0; s < sitems.length; s++) {
                            if (p.qtype === '' && sitems[s].isdefault === true) {
                                p.qtype = sitems[s].name;
                                sel = 'selected="selected"';
                            } else {
                                sel = '';
                            }
                            sopt += "<option value='" + sitems[s].name + "' " + sel + " >" + sitems[s].display + "&nbsp;&nbsp;</option>";
                        }
                        if (p.qtype === '') {
                            p.qtype = sitems[0].name;
                        }
                        $(g.sDiv).append("<div class='sDiv2'>" + p.findText +
                            " <input type='text' value='" + p.query + "' size='30' name='q' class='qsbox' /> " +
                            " <select name='qtype'>" + sopt + "</select></div>");
                        //Split into separate selectors because of bug in jQuery 1.3.2
                        $('input[name=q]', g.sDiv).keydown(function (e) {
                            if (e.keyCode == 13) {
                                g.doSearch();
                            }
                        });
                        $('select[name=qtype]', g.sDiv).keydown(function (e) {
                            if (e.keyCode == 13) {
                                g.doSearch();
                            }
                        });
                        $('input[value=Clear]', g.sDiv).click(function () {
                            $('input[name=q]', g.sDiv).val('');
                            p.query = '';
                            g.doSearch();
                        });
                        $(g.bDiv).after(g.sDiv);
                    }
                }
                $(g.pDiv, g.sDiv).append("<div style='clear:both'></div>");
            },

            showThisDiv: function (mDiv, hDiv, bDiv, pDiv, vDiv, rDiv) {
                $(g.mDiv, g.gDiv).css('display', mDiv);  //title
                $(g.hDiv, g.gDiv).css('display', hDiv);  //Header
                $(g.bDiv, g.gDiv).css('display', bDiv);	//data
                $(g.pDiv, g.gDiv).css('display', pDiv);	//Page column
                $(g.vDiv, g.gDiv).css('display', vDiv);	//Bottom telescopic bar
                $(g.rDiv, g.gDiv).css('display', rDiv);  //Right telescopic bar

            },

            getCdpad: function () {
                var cdcol = $('thead tr:first th:first', g.hDiv).get(0);
                if (cdcol !== null) {
                    g.cdpad = 0;
                    g.cdpad += (isNaN(parseInt($('div', cdcol).css('borderLeftWidth'), 10)) ? 0 : parseInt($('div', cdcol).css('borderLeftWidth'), 10));
                    g.cdpad += (isNaN(parseInt($('div', cdcol).css('borderRightWidth'), 10)) ? 0 : parseInt($('div', cdcol).css('borderRightWidth'), 10));
                    g.cdpad += (isNaN(parseInt($('div', cdcol).css('paddingLeft'), 10)) ? 0 : parseInt($('div', cdcol).css('paddingLeft'), 10));
                    g.cdpad += (isNaN(parseInt($('div', cdcol).css('paddingRight'), 10)) ? 0 : parseInt($('div', cdcol).css('paddingRight'), 10));
                    g.cdpad += (isNaN(parseInt($(cdcol).css('borderLeftWidth'), 10)) ? 0 : parseInt($(cdcol).css('borderLeftWidth'), 10));
                    g.cdpad += (isNaN(parseInt($(cdcol).css('borderRightWidth'), 10)) ? 0 : parseInt($(cdcol).css('borderRightWidth'), 10));
                    g.cdpad += (isNaN(parseInt($(cdcol).css('paddingLeft'), 10)) ? 0 : parseInt($(cdcol).css('paddingLeft'), 10));
                    g.cdpad += (isNaN(parseInt($(cdcol).css('paddingRight'), 10)) ? 0 : parseInt($(cdcol).css('paddingRight'), 10));
                }
            },

            editTable: function (settings, td, row) {

                var $td = $(td);

                //edit cannot be set for class, operator, and checkbox
                var tdClass = $td.attr('class');
                if (tdClass.indexOf('index') !== -1 && tdClass === 'operator' && tdClass === 'cth') {
                    return;
                }

                var oldValue;

                //monitor
                $(td)
                    .on('dblclick', function (event) {
                        if ($(this).find('.editInput').length > 0) return;
                        //Get th width
                        var tdWidth = $td.width();
                        //Get the original value
                        var tdValue = oldValue = $td.text();
                        //Hide div content under td
                        $td.find('div').hide();
                        //Insert input box
                        var dom = g.createDom(settings.editType, settings.editDisplay, tdWidth, tdValue);
                        $td.append(dom);
                        $td.find('.editInput').focus();
                        //Add listening event
                        if (settings.inputValid && typeof settings.inputValid === 'function') {
                            settings.inputValid();
                        }
                        //Binding events
                        var _dom = $td.find('.editInput');
                        //time picker
                        if (settings.editType === 'datetime') {
                            settings.dateFmt = settings.dateFmt ? settings.dateFmt : 'yyyy-MM-dd HH:mm:ss';
                            WdatePicker({
                                el: _dom.get(0),
                                lang: parent.langWdatePickerCurLoacl(),
                                dateFmt: settings.dateFmt,
                                onpicked: function () {
                                    var returnData = g.getDomValue(settings.editType, $td);
                                    //show hidden div
                                    $td.find('div').show();
                                    //The data has not changed and the callback is not executed.
                                    if (oldValue === returnData.value) return;
                                    //Execute callback method
                                    if (typeof p.autoEditSave === 'function') {
                                        var key = $td.attr('class');
                                        var value = returnData.value;
                                        var response = {};
                                        response.id = row.id
                                        response[key] = value;
                                        autoEditSave(response);
                                    }
                                }
                            });
                        }
                        //Stop bubbling
                        g.stopEvent(event);
                    })
                    .on('blur', '.editInput', function () {
                        if (settings.editType === 'datetime' && $('#_my97DP').is(':visible')) {
                            return;
                        }
                        var returnData = g.getDomValue(settings.editType, $td);
                        //show hidden div
                        $td.find('div').show();
                        //The data has not changed and the callback is not executed.
                        if (oldValue === returnData.value) return;
                        //Execute callback method
                        if (typeof p.autoEditSave === 'function') {
                            var key = $td.attr('class');
                            var value = returnData.value;
                            var response = {};
                            response.id = row.id
                            response[key] = value;
                            autoEditSave(response);
                        }
                    })
                if (!g.isBindMousedownEvent) {
                    $(document).mousedown(function () {
                        if ($('.dateTimeInput').length > 0) {
                            setTimeout(function () {
                                if (!$('#_my97DP').is(':visible')) {
                                    var _td = $('.dateTimeInput').parent();
                                    var returnData = g.getDomValue('datetime', _td);
                                    //show hidden div
                                    _td.find('div').show();
                                    //The data has not changed and the callback is not executed.
                                    if (oldValue === returnData.value) return;
                                    //Execute callback method
                                    if (typeof p.autoEditSave === 'function') {
                                        var key = _td.attr('class');
                                        var value = returnData.value;
                                        var response = {};
                                        response.id = row.id
                                        response[key] = value;
                                        autoEditSave(response);
                                    }
                                }
                            }, 200)
                        }
                    });
                    g.isBindMousedownEvent = true;
                }
            },
            stopEvent: function (event) {
                event = event || window.event;
                event.stopPropagation();
            },

            createDom: function (type, display, width, value) {
                switch (type) {
                    case 'input':
                        return this.createInputDom(width, value);
                    case 'select':
                        return this.createSelectDom(width, value, display);
                    case 'datetime':
                        return this.createDateTimeDom(width, value, display);
                }
            },

            createInputDom: function (width, value) {
                return '<input class="editInput" type="text" style="outline-color: #bbcbdf; text-align:center; height: 26px; width: ' + width + 'px" value="' + value + '">';
            },

            createSelectDom: function (width, value, display) {
                if (display.length <= 0) return;
                var $select = $('<select class="editInput" style="outline-color: #bbcbdf;text-align:center; text-align-last:center;height:26px; width:' + width + 'px"></select>');
                for (var i = 0; i < display.length; i++) {
                    $select.append('<option value ="' + display[i].key + '">' + display[i].value + '</option>')
                }
                return $select.prop('outerHTML');
            },

            createDateTimeDom: function (width, value, display) {
                return '<input class="editInput dateTimeInput" readonly type="text" style="outline-color: #bbcbdf; text-align:center; height: 26px; width: ' + width + 'px" value="' + value + '">';
            },

            /**
             * The Flexigrid table will have a "data-key" attribute that needs to be modified.
             * For example:
             * <td align="center" class="vehicleStatus" data-key="vehiclestatus" data-vehiclestatus="1">
             * </td>
             */
            updateAttributes: function (newValue, $td) {
                var key = $td.attr('data-key');
                var attr = 'data-' + key;
                $td.attr(attr, newValue);
            },
            getDomValue: function (type, $td) {
                switch (type) {
                    case 'input':
                        return this.getInputDomValue($td);
                    case 'select':
                        return this.getSelectDomValue($td);
                    case 'datetime':
                        return this.getDateTimeDomValue($td);
                }
            },

            getInputDomValue: function ($td) {
                //Newly entered data value
                var newValue = $('.editInput').val();
                if (newValue) newValue = newValue.trim();
                this.updateAttributes(newValue, $td);
                //Delete input box
                $td.find('.editInput').remove();
                //Modify new data value
                var $div = $td.find('div');
                if ($div) $div.empty();
                $div.html(newValue);
                return {
                    value: newValue
                }
            },

            getSelectDomValue: function ($td) {
                var $editInput = $('.editInput option:selected');
                var val = $editInput.val();
                this.updateAttributes(val, $td);
                var text = $editInput.text();
                //Delete input box
                $td.find('.editInput').remove();
                //Add hidden input and save attributes
                var $input = $td.find('input');
                if ($input) $input.remove();
                $td.append('<input style="display: none;" value="' + val + '">');
                //Modify new data value
                var $div = $td.find('div');
                if ($div) $div.empty();
                $div.html(text);
                return {
                    value: val
                }
            },
            getDateTimeDomValue: function ($td) {
                //Newly entered data value
                var newValue = $('.editInput').val();
                if (newValue) newValue = newValue.trim()
                //Delete input box
                try {
                    $td.find('.editInput').remove();
                } catch (e) {

                }
                //Modify new data value
                $td.find('div').html(newValue);
                return {
                    value: newValue
                }
            },
            pager: 0

        };

        // get the grid class
        g = p.getGridClass(g);
        //Render column header content create model if any
        //if exists end if p.colmodel
        if (p.colModel) {
            thead = document.createElement('thead');
            var tr = document.createElement('tr');
            if (p.checkbox) {
                var cth = $('<th/>');
                var cthch = $('<input type=\"checkbox\" name=\"selectAllItem\" class=\"selectAllItem\"/>');
                cthch.addClass("noborder");
                cth.addClass("cth").attr({'axis': "col-1", width: "22", "isch": true}).append(cthch);
                $(tr).append(cth);
            }
            for (var i = 0; i < p.colModel.length; i++) {
                var th = document.createElement('th');
                $(th).attr('axis', 'col' + i);
                //Hidden titles do not display the corresponding nDiv
                if (p.colModel[i].hide) {
                    $(th).attr('hiddenNDiv', 'true');
                }
                //Get the contents of a single column
                var cm = p.colModel[i];
                if (cm) {	// only use cm if its defined
                    if ($.cookies) {
                        var cookie_width = 'flexiwidths/' + cm.name;		// Re-Store the widths in the cookies
                        if ($.cookie(cookie_width) != undefined) {
                            cm.width = $.cookie(cookie_width);
                        }
                    }
                    if (cm.display != undefined) {
                        th.innerHTML = cm.display;
                    }
                    if (cm.sortname && cm.sortable) {
                        $(th).attr('abbr', cm.sortname);
                    }
                    if (cm.sortType) {
                        $(th).attr('sortType', cm.sortType);
                    }
                    if (cm.name) {
                        $(th).addClass(cm.name);
                    }
                    if (cm.align) {
                        th.align = cm.align;
                    }
                    if (cm.width) {
                        $(th).attr('width', cm.width);
                    }
                    if ($(cm).attr('hide')) {
                        th.hidden = true;
                    }
                    if (cm.process) {
                        th.process = cm.process;
                    }
                    if (cm.fixed) {
                        th.fixed = cm.fixed;
                        p.fixed = true;
                    }
                } else {
                    th.innerHTML = "";
                    $(th).attr('width', 22);
                }
                $(tr).append(th);
            }
            $(thead).append(tr);
            $(t).prepend(thead);
            g.createTableHeadObj = $(tr);
        }

        //Initialize div init divs create global container create global container
        g.gDiv = document.createElement('div');

        //create title container create title container
        g.mDiv = document.createElement('div');

        //header create header container
        g.hDiv = document.createElement('div');

        //table content create body container
        g.bDiv = document.createElement('div');

        // create grip
        g.vDiv = document.createElement('div');

        //horizontal resizer create horizontal resizer
        g.rDiv = document.createElement('div');

        //create column drag create column drag
        g.cDrag = document.createElement('div');

        //Create blocker create blocker
        g.block = document.createElement('div');

        //Hide or popup columns create column show/hide popup
        g.nDiv = document.createElement('div');

        //Hide or popup button create column show/hide button
        g.nBtn = document.createElement('div');

        //create editable layer create editable layer
        g.iDiv = document.createElement('div');
        //When the form is empty, it is used for positioning
        g.emptyWidthDiv = document.createElement('div');

        //create toolbar create toolbar
        g.tDiv = document.createElement('div');
        g.sDiv = document.createElement('div');

        //Create container create pager container
        g.pDiv = document.createElement('div');

        if (p.colResize === false) { //don't display column drag if we are not using it
            $(g.cDrag).css('display', 'none');
        }

        if (!p.usePager) {
            g.pDiv.style.display = 'none';
        }

        g.hTable = document.createElement('table');

        g.gDiv.className = 'flexigrid';

        // if (p.width != 'auto') {
        // 	g.gDiv.style.width = p.width + (isNaN(p.width) ? '' : 'px');
        // }
        if (p.width != 'auto') {
            if (p.width.toString().indexOf('%') > 0)
                g.gDiv.style.width = p.width;
            else
                g.gDiv.style.width = p.width + (isNaN(p.width) ? '' : 'px');
        }

        // add conditional classes
        if (browser.msie) {
            $(g.gDiv).addClass('ie');
        }

        if (p.novstripe) {
            $(g.gDiv).addClass('novstripe');
        }

        $(t).before(g.gDiv);

        $(g.gDiv).append(t);

        //set toolbar
        if (p.buttons) {
            g.tDiv.className = 'tDiv';
            g.tDiv2 = document.createElement('div');
            g.tDiv2.className = 'tDiv2';
            for (var i = 0; i < p.buttons.length; i++) {
                var btn = p.buttons[i];
                if (!btn.separator) {
                    var btnDiv = document.createElement('div');
                    btnDiv.className = 'fbutton';
                    btnDiv.innerHTML = ("<div><span>") + (btn.hidename ? "&nbsp;" : btn.name) + ("</span></div>");
                    if (btn.bclass) $('span', btnDiv).addClass(btn.bclass).css({
                        paddingLeft: 20
                    });
                    if (btn.bimage) // if bimage defined, use its string as an image url for this buttons style (RS)
                        $('span', btnDiv).css('background', 'url(' + btn.bimage + ') no-repeat center left');
                    $('span', btnDiv).css('paddingLeft', 20);

                    if (btn.tooltip) // add title if exists (RS)
                        $('span', btnDiv)[0].title = btn.tooltip;

                    btnDiv.onpress = btn.onpress;
                    btnDiv.name = btn.name;
                    if (btn.id) {
                        btnDiv.id = btn.id;
                    }
                    if (btn.onpress) {
                        $(btnDiv).click(function () {
                            this.onpress(this.id || this.name, g.gDiv);
                        });
                    }
                    $(g.tDiv2).append(btnDiv);
                    if (browser.msie && browser.version < 7.0) {
                        $(btnDiv).hover(function () {
                            $(this).addClass('fbOver');
                        }, function () {
                            $(this).removeClass('fbOver');
                        });
                    }
                } else {
                    if (btn.audio) {
                        $(g.tDiv2).append("<audio  id=\"audioPlay\"   controls=\"controls\"></audio>");
                    } else {
                        $(g.tDiv2).append("<div class='btnseparator'></div>");
                    }

                }
            }
            $(g.tDiv).append(g.tDiv2);
            $(g.tDiv).append("<div style='clear:both'></div>");
            $(g.gDiv).prepend(g.tDiv);
        }
        g.hDiv.className = 'hDiv';

        // Define a combo button set with custom action'ed calls when clicked.
        if (p.combobuttons && $(g.tDiv2)) {
            var btnDiv = document.createElement('div');
            btnDiv.className = 'fbutton';

            var tSelect = document.createElement('select');

            // $(tSelect).change( function () { g.combo_doSelectAction( tSelect ) } );
            // $(tSelect).click( function () { g.combo_resetIndex( tSelect) } );

            $(tSelect).change(function () {
                p.combobuttons.onchange(tSelect.value)
            });

            tSelect.className = 'cselect';
            $(btnDiv).append(tSelect);

            var comboboxs = p.combobuttons.comboboxs;
            for (i = 0; i < comboboxs.length; i++) {
                var btn = comboboxs[i];
                if (!btn.separator) {
                    var btnOpt = document.createElement('option');
                    btnOpt.innerHTML = btn.name;

                    if (btn.bclass)
                        $(btnOpt)
                            .addClass(btn.bclass)
                            .css({paddingLeft: 20})
                        ;
                    if (btn.bimage)  // if bimage defined, use its string as an image url for this buttons style (RS)
                        $(btnOpt).css('background', 'url(' + btn.bimage + ') no-repeat center left');
                    $(btnOpt).css('paddingLeft', 20);

                    if (btn.tooltip) // add title if exists (RS)
                        $(btnOpt)[0].title = btn.tooltip;

                    if (btn.onpress != null) {
                        btnOpt.value = btn.onpress;
                    }
                    $(tSelect).append(btnOpt);
                }
            }
            $(g.tDiv2).append(btnDiv);
        }

        $(t).before(g.hDiv);
        g.hTable.cellPadding = 0;
        g.hTable.cellSpacing = 0;
        $(g.hDiv).append('<div class="hDivBox"></div>');
        $('div', g.hDiv).append(g.hTable);
        var thead = $("thead:first", t).get(0);
        if (thead) $(g.hTable).append(thead);

        thead = null;
        if (!p.colmodel) var ci = 0;

        $('thead tr:first th', g.hDiv).each(function (i, row) {

            //Insert div for th of thead
            var thdiv = document.createElement('div');
            if ($(this).attr('abbr')) {
                $(this).click(function (e) {
//					if (!$(this).hasClass('thOver')) return false;
                    var obj = (e.target || e.srcElement);
                    if (obj.href || obj.type) return true;
                    var isok = true;
                    if (p.changeTip) {
                        isok = p.changeTip();
                    }
                    if (isok)
                        g.changeSort(this);
                });
                if ($(this).attr('abbr') == p.sortname) {
                    this.className = 'sorted';
                    thdiv.className = 's' + p.sortorder;
                }
            }

            if (this.hidden) {
                $(this).hide();
            }
            if (!p.colmodel) {
                $(this).attr('axis', 'col' + ci++);
            }

            // if there isn't a default width, then the column headers don't match
            // i'm sure there is a better way, but this at least stops it failing
            if (this.width == '') {
                this.width = 100;
            }

            //Set th style
            $(thdiv).css({
                textAlign: this.align,
                width: this.width + 'px'
            });

            if (this.fixed) {
                $(this).attr("fixed", this.fixed);
            }

            if (this.checkboxFixed && this.checkboxFixed != 'none') {
                $(this).attr("checkboxFixed", this.checkboxFixed);
            }

            thdiv.innerHTML = this.innerHTML;
            $(this).empty().append(thdiv).removeAttr('width').mousedown(function (e) {
                if ($(this).find("input").attr("type") == 'checkbox') {
                    return;
                }
                g.dragStart('colMove', e, this);
            }).hover(function () {
                if ($(this).find("input").attr("type") == 'checkbox') {
                    return;
                }
                if (!g.colresize && !$(this).hasClass('thMove') && !g.colCopy) {
                    $(this).addClass('thOver');
                }
                if ($(this).attr('abbr') != p.sortname && !g.colCopy && !g.colresize && $(this).attr('abbr')) {
                    $('div', this).addClass('s' + p.sortorder);
                } else if ($(this).attr('abbr') == p.sortname && !g.colCopy && !g.colresize && $(this).attr('abbr')) {
                    var no = (p.sortorder == 'asc') ? 'desc' : 'asc';
                    $('div', this).removeClass('s' + p.sortorder).addClass('s' + no);
                }
                if (g.colCopy) {
                    var n = $('th', g.hDiv).index(this);
                    if (n == g.dcoln) {
                        return false;
                    }
                    if (n < g.dcoln) {
                        $(this).append(g.cdropleft);
                    } else {
                        $(this).append(g.cdropright);
                    }
                    g.dcolt = n;
                } else if (!g.colresize) {
                    var nv = $('th:visible', g.hDiv).index(this);
                    var onl = parseInt($('div:eq(' + nv + ')', g.cDrag).css('left'), 10);
                    onl = isNaN(onl) ? 0 : onl;
                    var nw = jQuery(g.nBtn).outerWidth();
                    var nl = onl - nw + Math.floor(p.cgwidth / 2);
                    $(g.nDiv).hide();
                    $(g.nBtn).hide();

                    $(g.nBtn).css({
                        'left': nl,
                        top: g.hDiv.offsetTop
                    }).show();
                    var ndw = parseInt($(g.nDiv).width(), 10);
                    $(g.nDiv).css({
                        top: g.bDiv.offsetTop - 1
                    });
                    if ((nl + ndw) > $(g.gDiv).width()) {
                        $(g.nDiv).css('left', onl - ndw + 1);
                    } else {
                        $(g.nDiv).css('left', nl);
                    }
                    if ($(this).hasClass('sorted')) {
                        $(g.nBtn).addClass('srtd');
                    } else {
                        $(g.nBtn).removeClass('srtd');
                    }
                }
            }, function () {
                if ($(this).find("input").attr("type") == 'checkbox') {
                    return;
                }
                $(this).removeClass('thOver');
                if ($(this).attr('abbr') != p.sortname) {
                    $('div', this).removeClass('s' + p.sortorder);
                } else if ($(this).attr('abbr') == p.sortname) {
                    var no = (p.sortorder == 'asc') ? 'desc' : 'asc';
                    $('div', this).addClass('s' + p.sortorder).removeClass('s' + no);
                }
                if (g.colCopy) {
                    $(g.cdropleft).remove();
                    $(g.cdropright).remove();
                    g.dcolt = null;
                }
            }); //wrap content
        });

        //Set tbody content set bDiv
        g.bDiv.className = 'bDiv';
        $(t).before(g.bDiv);
        $(g.bDiv).css({height: (p.height === 'auto') ? 'auto' : p.height + (p.height.toString().indexOf('%') > 0 ? "" : "px")}).scroll(function (e) {
            g.scroll()
        }).append(t);
        if (p.height === 'auto') {
            $('table', g.bDiv).addClass('autoht');
        }
        if (p.height && p.height !== 'auto' && p.height.toString().indexOf('%') === -1) {
            $(g.gDiv).css('height', p.height + $(g.hDiv).height() + 2 + 'px');
        }

        //add td & row properties
        g.addCellProp();
        g.addRowProp();

        if (p.checkbox) {
            g.selectAllItemRow();
            g.selectItemRow();
        }

        //set cDrag only if we are using it
        if (p.colResize === true) {
            var cdcol = $('thead tr:first th:first', g.hDiv).get(0);
            if (cdcol !== null) {
                g.cDrag.className = 'cDrag';
                g.getCdpad();
                $(g.bDiv).before(g.cDrag);
                var cdheight = $(g.bDiv).height();
                var hdheight = $(g.hDiv).height();
                $(g.cDrag).css({
                    top: -hdheight + 'px'
                });
                $('thead tr:first th', g.hDiv).each(function (i) {
                    var cgDiv = document.createElement('div');
                    $(g.cDrag).append(cgDiv);
                    if (!p.cgwidth) {
                        p.cgwidth = $(cgDiv).width() == 0 ? 5 : $(cgDiv).width();
                    }
                    var cgHeight = (cdheight + hdheight) == 0 ? 24 : (cdheight + hdheight);
                    $(cgDiv).height(cgHeight);
                    $(cgDiv).mousedown(function (e) {
                        g.dragStart('colresize', e, this);
                    }).dblclick(function (e) {
                        g.autoResizeColumn(this);
                    });
                    if (browser.msie && browser.version < 7.0) {
                        g.fixHeight($(g.gDiv).height());
                        $(cgDiv).hover(function () {
                            g.fixHeight();
                            $(this).addClass('dragging');
                        }, function () {
                            if (!g.colresize) {
                                $(this).removeClass('dragging');
                            }
                        });
                    }
                });
            }
        }

        //add strip
        if (p.striped) {
            $('tbody tr:odd', g.bDiv).addClass('erow');
        }
        if (p.resizable && p.height != 'auto') {
            g.vDiv.className = 'vGrip';
            $(g.vDiv).mousedown(function (e) {
                g.dragStart('vresize', e);
            }).html('<span></span>');
            $(g.bDiv).after(g.vDiv);
        }
        if (p.resizable && p.width != 'auto' && !p.nohresize) {
            g.rDiv.className = 'hGrip';
            $(g.rDiv).mousedown(function (e) {
                g.dragStart('vresize', e, true);
            }).html('<span></span>').css('height', $(g.gDiv).height());
            if (browser.msie && browser.version < 7.0) {
                $(g.rDiv).hover(function () {
                    $(this).addClass('hgOver');
                }, function () {
                    $(this).removeClass('hgOver');
                });
            }
            $(g.gDiv).append(g.rDiv);
        }

        g.addUsepager();

        // add title
        if (p.title) {
            g.mDiv.className = 'mDiv';
            g.mDiv.innerHTML = '<div class="ftitle">' + p.title + '</div>';
            $(g.gDiv).prepend(g.mDiv);
            if (p.showTableToggleBtn) {
                $(g.mDiv).append('<div class="ptogtitle" title="Minimize/Maximize Table"><span></span></div>');
                $('div.ptogtitle', g.mDiv).click(function () {
                    $(g.gDiv).toggleClass('hideBody');
                    $(this).toggleClass('vsble');
                });
            }
        }

        //setup cdrops
        g.cdropleft = document.createElement('span');
        g.cdropleft.className = 'cdropleft';
        g.cdropright = document.createElement('span');
        g.cdropright.className = 'cdropright';

        //add block
        g.block.className = 'gBlock';
        var gh = $(g.bDiv).height();
        var gtop = g.bDiv.offsetTop;
        $(g.block).css({
            width: g.bDiv.style.width,
            height: gh,
            background: 'white',
            position: 'relative',
            marginBottom: (gh * -1),
            zIndex: 1,
            top: gtop,
            left: '0px'
        });
        $(g.block).fadeTo(0, p.blockOpacity);

        // add column control
        if ($('th', g.hDiv).length) {
            g.nDiv.className = 'nDiv';
            g.nDiv.innerHTML = "<table cellpadding='0' cellspacing='0'><tbody></tbody></table>";
            $(g.nDiv).css({
                marginBottom: (gh * -1),
                display: 'none',
                top: gtop
            }).noSelect();
            var cn = 0;
            $('th div', g.hDiv).each(function () {
                var $kcol = $("th[axis='col" + cn + "']", g.hDiv);
                var fixed = $kcol.attr('fixed');
                if (fixed) {
                    cn++;
                    return;
                }
                var kcol = $kcol[0];
                var chk = 'checked="checked"';
                if (kcol.style.display == 'none') {
                    chk = '';
                }
                var text = this.innerHTML;
                var type = $(this).find("input").attr("type");
                if (type == 'checkbox') {
                    cn++;
                    return;
                }
                //	if(type == 'checkbox') {
                //	if(type == 'checkbox') {
                //		$('tbody', g.nDiv).find('tr').hide();
                //	}
                if ($(this).parent().attr('hiddenndiv') != 'true') {
                    if (text === '') {
                        $('tbody', g.nDiv).append('<tr style="display: none;"><td class="ndcol1"><input type="checkbox" ' + chk + ' class="togCol" value="' + cn + '" /></td><td class="ndcol2">' + text + '</td></tr>');
                    } else {
                        $('tbody', g.nDiv).append('<tr><td class="ndcol1"><input type="checkbox" ' + chk + ' class="togCol" value="' + cn + '" /></td><td class="ndcol2">' + text + '</td></tr>');
                    }
                }

                cn++;
            });

            if (browser.msie && browser.version < 7.0) $('tr', g.nDiv).hover(function () {
                $(this).addClass('ndcolover');
            }, function () {
                $(this).removeClass('ndcolover');
            });
            $('td.ndcol2', g.nDiv).click(function () {
                if ($('input:checked', g.nDiv).length <= p.minColToggle && $(this).prev().find('input')[0].checked) return false;
                return g.toggleCol($(this).prev().find('input').val());
            });
            $('input.togCol', g.nDiv).click(function () {
                if ($('input:checked', g.nDiv).length < p.minColToggle && this.checked === false) return false;
                $(this).parent().next().trigger('click');
            });
            var hideShowColumnsTitle = '';
            if (_getRootFrameAttributes('lang').lang) {
                hideShowColumnsTitle = _getRootFrameAttributes('lang').lang.hideShowColumns;
            }
            $(g.gDiv).prepend(g.nDiv);
            $(g.nBtn).addClass('nBtn')
                .html('<div></div>')
                .attr('title', hideShowColumnsTitle)
                .click(function () {
                        $(g.nDiv).toggle();
                        return true;
                    }
                );
            if (p.showToggleBtn) {
                $(g.gDiv).prepend(g.nBtn);
            }
        }

        // add date edit layer
        $(g.iDiv).addClass('iDiv').css({
            display: 'none'
        });
        $(g.bDiv).append(g.iDiv);

        var lastTh = $(g.hDiv).find('th:visible').last();

        var emptyWidthDivLeft = 0;
        if (lastTh && lastTh.length === 1 && lastTh.offset() && lastTh.width()) {
            var parentLeft = $(lastTh).closest('.flexigrid').offset().left;
            emptyWidthDivLeft = lastTh.offset().left + lastTh.width() - parentLeft;
        }
        if (emptyWidthDivLeft === 0 && !$(g.gDiv).is(':visible')) {
            $(g.hDiv).find('div').each(function () {
                emptyWidthDivLeft += $(this).width();
            })
        }
        $(g.emptyWidthDiv).css({
            width: 1,
            height: 1,
            position: 'absolute',
            top: 0,
            left: emptyWidthDivLeft,
            opacity: 0,
        });
        $(g.bDiv).append(g.emptyWidthDiv);


        // add flexigrid events
        $(g.bDiv).hover(function () {
            $(g.nDiv).hide();
            $(g.nBtn).hide();
        }, function () {
            if (g.multisel) {
                g.multisel = false;
            }
        });
        $(g.gDiv).hover(function () {
        }, function () {
            $(g.nDiv).hide();
            $(g.nBtn).hide();
        });
        //add document events
        $(document).mousemove(throttle(function (e) {
            g.dragMove(e);
        }, 100)).mouseup(function (e) {
            g.dragEnd();
        }).hover(function () {
        }, function () {
            g.dragEnd();
        });

        //browser adjustments
        if (browser.msie && browser.version < 7.0) {
            $('.hDiv,.bDiv,.mDiv,.pDiv,.vGrip,.tDiv, .sDiv', g.gDiv).css({
                width: '100%'
            });
            $(g.gDiv).addClass('ie6');
            if (p.width != 'auto') {
                $(g.gDiv).addClass('ie6fullwidthbug');
            }
        }

        g.rePosDrag();
        g.fixHeight();
        //make grid functions accessible
        t.p = p;
        t.grid = g;

        // load data
        if (p.url && p.autoload) {
            g.populate();
        } else {
            g.showThisDiv('block', 'block', 'block', 'block', 'block', 'block');
        }
        if (p.createTableHead) {
            p.createTableHead(g.createTableHeadObj);
            g.createTableHeadObj.addClass("headModel");
        }
        return t;
    };

    var docloaded = false;

    $(document).ready(function () {
        docloaded = true;
    });

    //Fill table style
    $.fn.flexigrid = function (p) {
        if (p && p.colModel && p.colModel.length > 0 && typeof _getRootFrameElement == 'function' && _getRootFrameElement().removeGridFixedColumn) {
            p.checkboxFixed = '';
            for (var i = 0; i < p.colModel.length; i++) {
                p.colModel[i].fixed = '';
            }
        }
        //Complex header: Fixed columns are not supported.
        if (p && p.createTableHead) {
            //Unhide drop-down
            p.showTableToggleBtn = false;
            p.showToggleBtn = false;
            //Cancel width change
            p.colResize = false;
            //List drag
            p.colMove = false;
            //Unpin column
            p.checkboxFixed = '';
            for (var i = 0; i < p.colModel.length; i++) {
                p.colModel[i].fixed = '';
            }
        }


        return this.each(function () {
            if (!docloaded) {
                $(this).hide();
                var t = this;
                $(document).ready(function () {
                    $.addFlex(t, p);
                });
            } else {
                $.addFlex(this, p);
            }
        });
    }; //end flexigrid

    $.fn.flexReload = function (p) { // function to reload grid
        return this.each(function () {
            if (this.grid && this.p.url) this.grid.populate();
        });
    }; //end flexReload

    $.fn.flexGetHeaderInfo = function (p) {
        var headerInfo = [];
        this.each(function () {
            if (this.grid) {
                headerInfo = this.grid.getHeaderInfo();
            }
        });
        return headerInfo;
    };
    $.fn.flexGenerateRowSetCode = function (p) {
        this.each(function () {
            if (this.grid) {
                this.grid.generateRowSetCode();
            }
        });
    };

    $.fn.flexOptions = function (p) { //function to update general options
        return this.each(function () {
            if (this.grid) $.extend(this.p, p);
        });
    }; //end flexOptions

    $.fn.flexToggleCol = function (cid, visible) { // function to reload grid
        return this.each(function () {
            if (this.grid) this.grid.toggleCol(cid, visible);
        });
    }; //end flexToggleCol

    $.fn.flexAddData = function (data, type) { // function to add data to grid
        return this.each(function () {
            if (this.grid) this.grid.addData(data, type);
        });
    };


    $.fn.flexAddDataRowJson = function (data, type) { // function to add data to grid
        return this.each(function () {
            if (this.grid) this.grid.addDataRowJson(data, type);
        });
    };

    $.fn.flexAppendRowJson = function (rows, head) { // function append data to grid, afu 150531
        return this.each(function () {
            if (this.grid) this.grid.appendJsonData(rows, head);
        });
    };

    $.fn.flexAppendRowJsonHtml = function (rows, head) { // function append data to grid, afu 150531
        return this.each(function () {
            if (this.grid) this.grid.appendJsonDataHtml(rows, head);
        });
    };

    $.fn.flexRemoveRow = function (rowid,isReplaceReg) { // function append data to grid, afu 150531
        if (isReplaceReg && rowid!=null){
            // () # $ . [] & + * ==> \\(  \\) \\. \\[ \\]
            rowid = rowid.replaceAll(/(\(|\)|\$|\\.|[|]|\||&|\+|\*)/,"\\$1")
        }
        return this.each(function () {
            if (this.grid) this.grid.removeRow(rowid);
        });
    };

    // end flexReload
    $.fn.flexGetRowid = function (id) { // function to reload grid
        return "#row" + id;
    };

    //Get rows, including fixed columns
    $.fn.flexGetRow = function (id) {
        return this.parents('.flexigrid').find('.bDiv #row' + id);
    }

    //Get rows, including fixed columns
    $.fn.flexGetRowCount = function () {
        return this.parents('.flexigrid').find('.bDiv table tbody > tr').length;
    }

    $.fn.flexGetParams = function () {
        var param = {};
        this.each(function () {
            if (this.grid) {
                if (this.p.rp != null) {
                    param.rp = this.p.rp;
                }
                if (this.p.page != null) {
                    param.page = this.p.page;
                    param.total = this.p.total;
                    param.newp = this.p.newp == null ? 1 : this.p.newp;
                }
            }
        });
        return param;
    };

    $.fn.noSelect = function (p) { //no select plugin by me :-)
        var prevent = (p === null) ? true : p;
        if (prevent) {
            return this.each(function () {
                if (browser.msie || browser.safari) $(this).bind('selectstart', function () {
                    return false;
                });
                else if (browser.mozilla) {
                    $(this).css('MozUserSelect', 'none');
                    $('body').trigger('focus');
                } else if (browser.opera) $(this).bind('mousedown', function () {
                    return false;
                });
                else $(this).attr('unselectable', 'on');
            });
        } else {
            return this.each(function () {
                if (browser.msie || browser.safari) $(this).unbind('selectstart');
                else if (browser.mozilla) $(this).css('MozUserSelect', 'inherit');
                else if (browser.opera) $(this).unbind('mousedown');
                else $(this).removeAttr('unselectable', 'on');
            });
        }
    }; //end noSelect

    $.fn.flexSearch = function (p) { // function to search grid
        return this.each(function () {
            if (this.grid && this.p.searchitems) this.grid.doSearch();
        });
    }; //end flexSearch

    $.fn.flexClear = function (p) { // function to clear grid
        return this.each(function () {
            if (this.grid) this.grid.addData({});
        });
    }; //end flexClear

    $.fn.selectedRows = function (p) { // Returns the selected rows as an array, taken and adapted from http://stackoverflow.com/questions/11868404/flexigrid-get-selected-row-columns-values
        var arReturn = [];
        var arRow = [];
        // var selector = $(this.selector + ' .trSelected');
        $(this).find('.trSelected').each(function (i, row) {
            arRow = [];
            var idr = $(row).data('id');
            var name = row.name;
            $.each(row.cells, function (c, cell) {
                var col = cell.abbr;
                var val = cell.firstChild.innerHTML;
                if (val == '&nbsp;') val = '';      // Trim the content
                var idx = cell.cellIndex;
                var dataValue = "";
                var dataset = getDataset(cell)
                var key = dataset.key
                if (key && dataset[key]) {
                    dataValue = dataset[key]
                }
                arRow.push({
                    Column: col,        // Column identifier
                    Value: val,         // Column value
                    CellIndex: idx,     // Cell index
                    RowIdentifier: idr,  // Identifier of this row element
                    name: name,
                    dataValue: dataValue
                });
            });
            arReturn.push(arRow);
        });
        return arReturn;
    };

    $.fn.selectedRowsData = function (p) {
        var arReturn = [];
        var data = $(this).flexGetData();
        $(this).find('.trSelected').each(function (i, row) {
            var index = $(row).data('index');
            arReturn.push(data.rows[index]);
        });
        return arReturn;
    };

    $.fn.allRows = function (p) { // Returns the selected rows as an array, taken and adapted from http://stackoverflow.com/questions/11868404/flexigrid-get-selected-row-columns-values
        var arReturn = [];
        var arRow = [];
        // var selector = $(this.selector + ' tr');
        $(this).find('tr').each(function (i, row) {
            arRow = [];
            var idr = $(row).data('id');
            var name = row.name;
            $.each(row.cells, function (c, cell) {
                var col = cell.abbr;
                var val = cell.firstChild.innerHTML;
                if (val == '&nbsp;') val = '';      // Trim the content
                var idx = cell.cellIndex;
                var dataValue = ""
                var dataset = getDataset(cell)
                var key = dataset.key
                if (key && dataset[key]) {
                    dataValue = dataset[key]
                }
                if(!dataValue){
                    dataValue = cell.firstChild.innerText;
                }
                arRow.push({
                    Column: col,        // Column identifier
                    Value: val,         // Column value
                    CellIndex: idx,     // Cell index
                    RowIdentifier: idr,  // Identifier of this row element
                    name: name,
                    dataValue: dataValue
                });
            });
            arReturn.push(arRow);
        });
        return arReturn;
    };

    //Find value by column name
    $.fn.getColValueByName = function (cols, colName) {
        var retVal = '';
        var param = $.grep(cols, function (e) {
            var found = e.Column == colName;
            if (found != null && found != undefined & found) {
                retVal = e.Value;
            }
        });
        return retVal;
    }

    //Get the checked checkbox value
    $.fn.selectedCheckedRows = function (p) { // Returns the selected rows as an array, taken and adapted from http://stackoverflow.com/questions/11868404/flexigrid-get-selected-row-columns-values
        var arReturn = [];
        // var selector = $(this.selector + ' .selectItem');
        $(this).find('.selectItem').each(function (i, row) {
            if (row.checked && !row.disabled) {
                var idr = $(row).val();
                arReturn.push(idr);
            }
        });
        return arReturn;
    };

    //Get the checked checkbox value
    $.fn.selectedCheckedRowsData = function (p) { // Returns the selected rows as an array, taken and adapted from http://stackoverflow.com/questions/11868404/flexigrid-get-selected-row-columns-values
        var arReturn = [];
        var arRow = [];
        // var selector = $(this.selector + ' .trSelected');
        $(this).find('.selectItem').each(function (i, row) {
            arRow = [];
            row = $(row).parents('tr').get(0);
            var idr = $(row).data('id');
            var name = row.name;
            $.each(row.cells, function (c, cell) {
                var col = cell.abbr;
                var val = cell.firstChild.innerHTML;
                if (val == '&nbsp;') val = '';      // Trim the content
                var idx = cell.cellIndex;
                var dataValue = "";
                var dataset = getDataset(cell)
                var key = dataset.key
                if (key && dataset[key]) {
                    dataValue = dataset[key]
                }
                arRow.push({
                    Column: col,        // Column identifier
                    Value: val,         // Column value
                    CellIndex: idx,     // Cell index
                    RowIdentifier: idr,  // Identifier of this row element
                    name: name,
                    dataValue: dataValue
                });
            });
            arReturn.push(arRow);
        });
        return arReturn;
    };

    //External fill table method
    $.fn.flexSetFillCellFun = function (fillCell) {
        return this.each(function () {
            if (this.grid) this.grid.setFillCellFun(fillCell);
        });
    }

    //Add select row event externally
    $.fn.flexSelectRowPropFun = function (selectRow) {
        return this.each(function () {
            if (this.grid) this.grid.selectRowPropFun(selectRow);
        });
    }

    //Add select row event externally
    $.fn.flexClickCheckBoxFun = function (clickCheckBox) {
        return this.each(function () {
            if (this.grid) this.grid.clickCheckBoxFun(clickCheckBox);
        });
    }

    //Add select row mouse event externally
    $.fn.flexMouseUpRowPropFun = function (mouseUpRow) {
        return this.each(function () {
            if (this.grid) this.grid.mouseUpRowPropFun(mouseUpRow);
        });
    }

    //Get loaded data externally
    $.fn.flexGetData = function () {
        var data = null;
        this.each(function () {
            if (this.grid) data = this.grid.getData();
        });
        return data;
    }

    $.fn.flexFixHeight = function () {
        return this.each(function () {
            if (this.grid) this.grid.fixHeight();
        });
    }

    //Fill in the form
    var fixCellInfos = function (p, row, idx, index) {
        var pos = "";
        try {
            var name = p.colModel[idx].name;
            row[name] = html2Escape(row[name]);
            pos = fillCellInfo(p, row, idx, index);
            if (name == 'operator' || name == 'operator1') {
                pos = replaceOperatorIcon(pos);
            }
        } catch (e) {
            pos = row[name];
        }
        return pos;
    };

    /**
     * Replace background icon with text icon
     * @param pos
     * @returns {*}
     */
    var replaceOperatorIcon = function (pos) {
        pos = pos.replaceAll('class="detail"', 'class="fa fa-eye"');
        pos = pos.replaceAll('class="vDetail"', 'class="fa fa-eye"');
        pos = pos.replaceAll('class="edit"', 'class="fa fa-edit"');
        pos = pos.replaceAll('class="not-edit"', 'class="fa fa-edit color-gray"');
        pos = pos.replaceAll('class="authorize"', 'class="bi bi-person-check-fill"');
        pos = pos.replaceAll('class="not-authorize"', 'class="bi bi-person-check-fill color-gray"');
        pos = pos.replaceAll('class="delete"', 'class="bi bi-trash-fill"');
        pos = pos.replaceAll('class="not-delete"', 'class="bi bi-trash-fill color-gray"');
        pos = pos.replaceAll('class="manage"', 'class="fa fa-tv"');
        pos = pos.replaceAll('class="select"', 'class="fa fa-search"');
        pos = pos.replaceAll('class="add"', 'class="fa fa-plus-circle"');
        pos = pos.replaceAll('class="not-add"', 'class="fa fa-plus-circle color-gray"');
        pos = pos.replaceAll('class="downLoad"', 'class="font iconfont icon-ttx-bendixiazai"');
        pos = pos.replaceAll('class="not-downLoad"', 'class="font iconfont icon-ttx-bendixiazai color-gray"');
        pos = pos.replaceAll('class="upgrade"', 'class="bi bi-upload"');
        pos = pos.replaceAll('class="not-upgrade"', 'class="bi bi-upload color-gray"');
        pos = pos.replaceAll('class="segDownLoad"', 'class="fa fa-download"');
        pos = pos.replaceAll('class="playback"', 'class="fa fa-play-circle"');
        pos = pos.replaceAll('class="not-playback"', 'class="fa fa-play-circle color-gray"');
        pos = pos.replaceAll('class="videoback"', 'class="fa fa-play-circle"');
        pos = pos.replaceAll('class="downTask"', 'class="fa fa-cloud-download"');
        pos = pos.replaceAll('class="clear"', 'class="fa fa-eraser"');
        pos = pos.replaceAll('class="view"', 'class="fa fa-eye"');
        pos = pos.replaceAll('class="not-view"', 'class="fa fa-eye color-gray"');
        pos = pos.replaceAll('class="all"', 'class="fa fa-google-wallet"');
        pos = pos.replaceAll('class="changePWD"', 'class="fa fa-key"');
        pos = pos.replaceAll('class="copy"', 'class="fa fa-copy"');
        pos = pos.replaceAll('class="videoDevice"', 'class="fa fa-align-justify"');
        pos = pos.replaceAll('class="assign"', 'class="fa fa-flag"');
        pos = pos.replaceAll('class="not-assign"', 'class="fa fa-flag color-gray"');
        pos = pos.replaceAll('class="associate"', 'class="fa fa-paperclip"');
        pos = pos.replaceAll('class="not-associate"', 'class="fa fa-paperclip color-gray"');
        pos = pos.replaceAll('class="drawLine"', 'class="fa fa-paper-plane"');
        pos = pos.replaceAll('class="uploadFile"', 'class="fa fa-upload"');
        pos = pos.replaceAll('class="not-uploadFile"', 'class="fa fa-upload color-gray"');
        pos = pos.replaceAll('class="mulvideoback"', 'class="fa fa-film"');
        pos = pos.replaceAll('class="sound"', 'class="fa fa-volume-up"');
        pos = pos.replaceAll('class="remark"', 'class="fa fa-bars"');
        pos = pos.replaceAll('class="upload"', 'class="fa fa-upload"');
        pos = pos.replaceAll('class="pause"', 'class="fa fa-pause"');
        pos = pos.replaceAll('class="not-pause"', 'class="fa fa-pause color-gray"');
        pos = pos.replaceAll('class="submit"', 'class="fa fa-pen"');
        pos = pos.replaceAll('class="not-submit"', 'class="fa fa-pen color-gray"');
        pos = pos.replaceAll('class="withdraw"', 'class="fa fa-long-arrow-alt-left"');
        pos = pos.replaceAll('class="not-withdraw"', 'class="fa fa-long-arrow-alt-left color-gray"');
        pos = pos.replaceAll('class="reply"', 'class="fa fa-reply"');
        pos = pos.replaceAll('class="not-reply"', 'class="fa fa-reply color-gray"');
        pos = pos.replaceAll('class="continue"', 'class="fa fa-bullseye"');
        pos = pos.replaceAll('class="not-continue"', 'class="fa fa-bullseye color-gray"');
        return pos;
    }

    $.fn.setPageOne = function () {
        this.each(function () {
            if (this.grid) {
                this.p.newp = 1;
                this.p.page = 1;
                this.p.total = 0;
            }
        });
    }

})(jQuery);
