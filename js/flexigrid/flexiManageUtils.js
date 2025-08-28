//index page public data
var rootElement = _getRootFrameElement();
// var TtxDialogInfo = rootElement.TtxDialogInfo;
var TtxCommonType = rootElement.TtxCommonType;
var lang = rootElement.lang;

/**
 * Secondary encapsulation of flexiManage.js to simplify the code
 */
var FlexPanelUtil = {
    extend: function (o, def) {
        for (var key in def) {
            if ((def.hasOwnProperty(key))) {
                if (!o.hasOwnProperty(key)) {
                    o[key] = def[key];
                }
            }
        }
        return o;
    },
    /**
     * ComBoboxModel default value
     */
    selectOpts: {
        bgcolor: 'gray',
        bgicon: 'true',
        readonly: true,
        hide: false,
        hidden: true,
        maxLength: 20
    },
    /**
     * Add dropdown selector button
     *      pid: pid
     *      id: id
     * display: title
     * arr: array of options
     * preicon: show checkbox
     * multiple: whether to select multiple
     * isHasAll: Select all in the first row
     * multipleTitle: button text
     * defaultValue: default value
     * generate
     * Hidden input box of id='hidden-' + opts.id
     * usually
     * Get the hidden value:
     *              $(opts.pid + ' #hidden-' + opts.id).val();
     * For example $('#toolbar-status #hidden-status').val();
     * Return $(opts.pid + ' #hidden-' + opts.id)
     */
    addSelectBtn: function (opts) {
        opts = this.extend(opts, this.selectOpts);
        //Initialize the top drop-down box query conditions
        $(opts.pid).flexPanel({
            ComBoboxModel: {
                label: opts.label,
                button:
                    [
                        [{
                            display: opts.display,
                            name: opts.id,
                            pclass: opts.pclass,
                            bgcolor: opts.bgcolor,
                            bgicon: opts.bgicon,
                            hide: opts.hide,
                            hidden: opts.hidden
                        }]
                    ],
                combox: {
                    search: opts.search,
                    name: opts.id,
                    option: arrayToStr(opts.arr),
                    preicon: opts.preicon,
                    multiple: opts.multiple,
                    isHasAll: opts.isHasAll,
                    multipleTitle: opts.multipleTitle,
                    defaultValue: opts.value,
                    checkValue: opts.checkValue
                }
            }
        });
        if (opts.onClick) {
            $('#select-' + opts.id + ' li.ui-menu-item').on('click', function () {
                opts.onClick($(this).attr('data-index'));
                //echo display
                if (opts.lockDisplay) {
                    $('#label-' + opts.id).html(opts.display);
                }
            });
        }
        return $(opts.pid + ' #hidden-' + opts.id);
    },
    /**
     * Add drop-down selector input box
     * generate
     * Hidden input box of id='hidden-' + opts.id
     * usually
     * Get the hidden value:
     *              $(opts.pid + ' #hidden-' + opts.id).val();
     * For example $('#toolbar-status #hidden-status').val();
     * return
     *      $(opts.pid + ' #hidden-' + opts.id);
     */
    addSelectInput: function (opts) {
        opts = this.extend(opts, this.selectOpts);
        if (opts.hideLabel) {
            opts.label = '';
        }
        //Initialize the top drop-down box query conditions
        $(opts.pid).flexPanel({
            ComBoboxModel: {
                label: opts.label,
                input: {
                    display: opts.display,
                    name: opts.id,
                    pclass: opts.pclass,
                    bgcolor: opts.bgcolor,
                    bgicon: opts.bgicon,
                    hide: opts.hide,
                    hidden: opts.hidden,
                    readonly: opts.readonly,
                    maxLength: opts.maxLength
                },
                combox: {
                    search: opts.search,
                    name: opts.id,
                    option: arrayToStr(opts.arr),
                    preicon: opts.preicon,
                    multiple: opts.multiple,
                    isHasAll: opts.isHasAll,
                    multipleTitle: opts.multipleTitle,
                    defaultValue: opts.value,
                    checkValue: opts.checkValue
                },
                style:{
                    left: opts.left,
                    width: opts.width
                }
            }
        });
        if (opts.value || opts.value === 0) {
            $('#combox-' + opts.id).val(getArrayName(opts.arr, opts.value));
            $('#hidden-' + opts.id).val(opts.value);
        }
        if (opts.onClick) {
            $('#select-' + opts.id + ' li.ui-menu-item').on('click', function () {
                opts.onClick($(this).attr('data-index'));
                //echo display
                if (opts.lockDisplay) {
                    $('#label-' + opts.id).html(opts.display);
                }
            });
        }
        return $(opts.pid + ' #hidden-' + opts.id);
    },
    /**
     * InputModel default value
     */
    inputOpts: {
        id: '',
        display: '',
        label: '',
        pfloat: 'left',
        maxLength: 20,
    },
    /**
     * Add a normal input box
     * generate
     * Input box of id='combox-' + opts.id
     * usually
     * Get the input value:
     *              $('#combox-' + opts.id).val()
     * return
     *      $(opts.pid + ' #combox-' + opts.id)/$(opts.pid + ' #hidden-' + opts.id);
     */
    addInput: function (opts) {
        opts = this.extend(opts, this.inputOpts);
        $(opts.pid).flexPanel({
            InputModel: {
                display: opts.display,
                value: opts.value,
                label: opts.label,
                name: opts.id,
                maxLength: opts.maxLength,
                readonly: opts.readonly,
                hidden: opts.hidden,
                hide: opts.hide,
                date: opts.date,
                onlySearch: opts.onlySearch
            }
        });
        if (opts.hidden) {
            return $(opts.pid + ' #hidden-' + opts.id);
        }
        if (opts.onClick) {
            $(opts.pid + ' #combox-' + opts.id).on('click', function (e) {
                opts.onClick();
            });
        }
        return $(opts.pid + ' #combox-' + opts.id);
    },
    /**
     * Add pop-up window selection input
     */
    addDialogInput: function (opts) {
        // opts.id = opts.inputId;
        // opts.maxLength = 32;
        // this.addInput(opts);
        // var _input = $(opts.pid + ' #combox-' + opts.id);
        // opts.id = opts.dialogId;
        // var _this = this;
        // opts.readonly = true;
        // var clickBtn = _input;
        //Show...button
        // if (opts.showMoreBtn) {
        //     _input.attr('style', 'width: ' + (_input.width() - 24) + 'px !important;');
        //     var moreBtn = $('<a style="margin-left:10px;" class="fa fa-ellipsis-h"></a>');
        //     _input.closest('.item').append(moreBtn);
        //     clickBtn = moreBtn;
        // }
        // clickBtn.on('click', function () {
        //     var dialogInfo = TtxDialogInfo[opts.type];
        //     if (!dialogInfo) {
        //         dialogInfo = opts;
        //     }
        //     dialogInfo = _this.extend(opts, dialogInfo);
        //     dialogInfo.content = dialogInfo.getContentUrl(dialogInfo);
        //     $.dialog(dialogInfo);
        // });
    },
    /**
     * Add time picker
     * generate
     * Input box of id='combox-' + opts.id
     * usually
     * Get the input value:
     *              $('#combox-' + opts.id).val()
     * return
     *      $(opts.pid + ' #combox-' + opts.id)/$(opts.pid + ' #hidden-' + opts.id);
     */
    addDateInput: function (opts) {
        opts.date = true;
        this.addInput(opts);
        var dateOpts = {
            lang: rootElement.langWdatePickerCurLoacl(),
            dateFmt: opts.dateFmt,
            onpicked: opts.onpicked
        };
        if (opts.maxDate) {
            dateOpts.maxDate = opts.maxDate;
        }
        if (opts.minDate) {
            dateOpts.minDate = opts.minDate;
        }
        initDateSelector("#combox-" + opts.id, dateOpts);
        return $(opts.pid + ' #combox-' + opts.id);
    },
    /**
     * The default value of the report page time selector is determined based on dateFmt
     */
    rptDateInputDefaultValue: '',
    /**
     * Initialize the default value of the report page time selector, determined based on dateFmt
     */
    initRptDateInputDefaultValue: function () {
        var isEnableHuanQi = rootElement.myUserRole &&rootElement.myUserRole.isEnableHuanQi();
        this.rptDateInputDefaultValue = {
            'yyyy-MM-dd HH:mm:ss': {
                start: dateCurDateBeginString(),
                end:  dateCurDateEndString()
            },
            'yyyy-MM-dd': {
                start: isEnableHuanQi ? dateCurrentDateString() : dateFormat2DateString(dateGetLastMonth()),
                end: isEnableHuanQi ? dateCurrentDateString() :dateYarDateString()
            },
            'yyyy-MM': {
                start: isEnableHuanQi ? dateCurrentMonthString() :dateFormat2MonthString(monthGetLastMonth()),
                end: dateCurrentMonthString()
            },
            'yyyy': {
                start: new Date().getFullYear()+'',
                end: new Date().getFullYear()+''
            }
        }
    },
    /**
     * index The time default value key in the global object
     */
    getIndexDefaultDateValueInfo: function () {
        return {
            'yyyy-MM-dd HH:mm:ss': {
                start: 'longbtime',
                end: 'longetime',
                label: lang.labelSelectTime
            },
            'yyyy-MM-dd': {
                start: 'daybtime',
                end: 'dayetime',
                maxDate: '%y-%M-%d',
                label: lang.labelSelectTime
            },
            'yyyy-MM': {
                start: 'monthbtime',
                end: 'monthetime',
                maxDate: '%y-{%M}',
                label: lang.selectMonth1
            },
            'yyyy': {
                start: 'yearbtime',
                end: 'yearetime',
                maxDate: '%y',
                label: lang.labelSelectYears
            }
        };
    },
    getDefaultDateValue: function (subKey, dateFmt) {
        var dateValueInfo = this.getIndexDefaultDateValueInfo()[dateFmt];
        var value = rootElement[dateValueInfo[subKey]];
        if (!value) {
            if (!this.rptDateInputDefaultValue) {
                this.initRptDateInputDefaultValue();
            }
            value = this.rptDateInputDefaultValue[dateFmt][subKey];
        }
        return value;
    },
    /**
     * Get the default time value in the index global object
     * opts.isStart: Whether to start the time, see initRptDateInputDefaultValue
     * opts.maxDate: Take the default value if not passed, see indexDefaultDateValueInfo
     * opts.label: Take the default value if not passed, see indexDefaultDateValueInfo
     */
    setDefaultDateValue: function (opts) {
        var subKey = opts.isStart ? 'start' : 'end';
        var dateValueInfo = this.getIndexDefaultDateValueInfo()[opts.dateFmt];
        opts.value = this.getDefaultDateValue(subKey, opts.dateFmt);
        if (!opts.maxDate && dateValueInfo.maxDate) {
            opts.maxDate = dateValueInfo.maxDate;
        }
        if (!opts.label && dateValueInfo.label) {
            opts.label = dateValueInfo.label;
        }
        if (opts.hideLabel) {
            opts.label = '';
        }
        opts.onpicked = function (obj) {
            rootElement[dateValueInfo[subKey]] = obj.el.value;
        }
    },
    /**
     * Report addition Add time picker
     * If you need to restrict the ability to query the current day, pass in opts.maxDate = '%y-%M-#{%d-1}'
     * return
     *      $(opts.pid + ' #combox-' + opts.id)/$(opts.pid + ' #hidden-' + opts.id);
     */
    addRptDateInput: function (opts) {
        this.setDefaultDateValue(opts);
        return this.addDateInput(opts);
    },
    /**
     * Add time range selector
     * opts.rangeType controls the switching type, see TtxCommonType.SelectTimeTypesExcludeRange for details
     * 1: Filter 6 7 8
     * 2: Filter 5 6 7 8
     * 3: Filter 1 6 7 8
     * 4: Filter 7 8
     * 5: Filter 1 6
     */
    addRangeDateInput: function (opts) {
        var _this = this;
        //Starting time
        var startTime = this.addRptDateInput({
            pid: opts.startPId,
            id: opts.startId,
            dateFmt: opts.dateFmt,
            maxDate: opts.maxDate,
            label: lang.report_beginTime,
            hideLabel: opts.hideLabel,
            isStart: true
        });
        //End Time
        var endTime = this.addRptDateInput({
            pid: opts.endPId,
            id: opts.endId,
            dateFmt: opts.dateFmt,
            maxDate: opts.maxDate,
            label: lang.endtime,
            hideLabel: opts.hideLabel,
            isStart: false
        });
        //Time type switching
        this.addSelectInput({
            pid: opts.rangePId,
            id: opts.rangeId,
            label: lang.labelSelectTime,
            hideLabel: opts.hideLabel,
            arr: TtxCommonType.getSelectTimeTypes(opts.rangeType),
            value: 0,
            onClick: function (id) {
                if (!id) {
                    id = 0;
                }
                id = parseInt(id);
                startTime.prop('disabled', true);
                endTime.prop('disabled', true);

                var rangeTimeValue = _this.getRangeTimeValue(id, opts.dateFmt)
                startTime.val(rangeTimeValue.start);
                endTime.val(rangeTimeValue.end);
                //You can choose the time for self-scheduled time
                if (id === 0) {
                    startTime.prop('disabled', false);
                    endTime.prop('disabled', false);
                }
            }
        });
        return {
            startTime: startTime,
            endTime: endTime,
            reset: function () {
                $('#select-' + opts.rangeId + ' li.ui-menu-item').eq(0).trigger('click');

                if ($('#hidden-' + opts.rangeId).val() == 0) {
                    _this.initRptDateInputDefaultValue();
                    var start = _this.rptDateInputDefaultValue[opts.dateFmt]['start'];
                    var end = _this.rptDateInputDefaultValue[opts.dateFmt]['end'];

                    $('#combox-' + opts.startId).val(start);
                    $('#combox-' + opts.endId).val(end);
                }
            }
        }
    },
    /**
     * Get time based on rangeType
     * 0. Custom time
     * 1.Today
     * 2.yesterday
     * 3.Last 2 days
     * 4. Last 7 days
     * 5.Last 1 month
     * 6. Last 90 days
     * 7.Last six months
     * 8. All dates
     */
    getRangeTimeValue: function (type, dateFmt) {
        var rangeTimeValue = {
            start: '',
            end: ''
        };
        var lastDefaultDate = dateYarDateString();
        //0. Custom time
        if (type === 0) {
            rangeTimeValue.start = this.getDefaultDateValue('start', dateFmt);
            rangeTimeValue.end = this.getDefaultDateValue('end', dateFmt);
            return rangeTimeValue;
        }
        //1.Today
        if (type === 1) {
            var dateStr = dateCurrentDateString();
            rangeTimeValue.start = dateStr;
            rangeTimeValue.end = dateStr;
            if (dateFmt === 'yyyy-MM-dd HH:mm:ss') {
                rangeTimeValue.start += ' 00:00:00';
                rangeTimeValue.end += ' 23:59:59';
            }
            return rangeTimeValue;
        }
        //2.yesterday
        if (type === 2) {
            var now = dateYarDateString();
            rangeTimeValue.start = now;
            rangeTimeValue.end = now;
            if (dateFmt === 'yyyy-MM-dd HH:mm:ss') {
                rangeTimeValue.start += ' 00:00:00';
                rangeTimeValue.end += ' 23:59:59';
            }
            return rangeTimeValue;
        }
        //3.Last 2 days
        if (type === 3) {
            rangeTimeValue.start = dateYarStaBeginString();
            rangeTimeValue.end = lastDefaultDate;
            if (dateFmt === 'yyyy-MM-dd HH:mm:ss') {
                rangeTimeValue.start += ' 00:00:00';
                rangeTimeValue.end += ' 23:59:59';
            }
            return rangeTimeValue;
        }
        //4. Last 7 days
        if (type === 4) {
            rangeTimeValue.start = dateWeekDateString();
            rangeTimeValue.end = lastDefaultDate;
            if (dateFmt === 'yyyy-MM-dd HH:mm:ss') {
                rangeTimeValue.start += ' 00:00:00';
                rangeTimeValue.end += ' 23:59:59';
            }
            return rangeTimeValue;
        }
        //5.Last 1 month
        if (type === 5) {
            rangeTimeValue.start = addMonths(-1);
            rangeTimeValue.end = lastDefaultDate;
            if (dateFmt === 'yyyy-MM-dd HH:mm:ss') {
                rangeTimeValue.start += ' 00:00:00';
                rangeTimeValue.end += ' 23:59:59';
            }
            return rangeTimeValue;
        }
        //6. Last 90 days
        if (type === 6) {
            rangeTimeValue.start = dateThreeBeginDayString();
            rangeTimeValue.end = lastDefaultDate;
            if (dateFmt === 'yyyy-MM-dd HH:mm:ss') {
                rangeTimeValue.start += ' 00:00:00';
                rangeTimeValue.end += ' 23:59:59';
            }
            return rangeTimeValue;
        }
        //7.Last six months
        if (type === 7) {
            rangeTimeValue.start = addMonths(-6);
            rangeTimeValue.end = lastDefaultDate;
            if (dateFmt === 'yyyy-MM-dd HH:mm:ss') {
                rangeTimeValue.start += ' 00:00:00';
                rangeTimeValue.end += ' 23:59:59';
            }
            return rangeTimeValue;
        }
        //8. All dates
        return rangeTimeValue;
    },
    /**
     * Add search box with magnifying glass icon
     * opts:
     * queryEvent: event bound to search button and enter
     * generate
     * Search button for class='y-btn y-btn-gray y-btn-submit'
     * Input box with class='search-input'
     * usually
     * Get the input value:
     *              $(opts.pid + ' .search-input').val()
     * For example $('#toolbar-search .search-input').val()
     * return
     *      $(opts.pid + ' .search-input');
     */
    addSearchInput: function (opts) {
        $(opts.pid).flexPanel({
            SerachBarModel: {display: opts.display, name: opts.id, pfloat: opts.pfloat}
        });
        if (opts.queryEvent) {
            //Search button binding event
            $(opts.pid + ' .y-btn').on('click', function () {
                opts.queryEvent();
            });
            //Search box binds enter event
            $(opts.pid + ' .search-input').on('keydown', function (e) {
                if (e.keyCode === 13) {
                    opts.queryEvent();
                }
            });
        }
        return $(opts.pid + ' .search-input');
    },
    /**
     * ButtonsModel default value
     */
    btnOpts: {
        bgcolor: 'gray',
        hide: false
    },
    /**
     * Add button
     * buttons:
     * onClick: Bind click event
     * generate
     * Button with id="label-" + id
     *      id="label-addRunStop"
     */
    addButtons: function (domSelector, buttons) {
        for (var i = 0; i < buttons.length; i++) {
            var mod = buttons[i];
            mod = this.extend(mod, this.btnOpts);
            mod.name = mod.id;
            mod.pclass = 'new';
            buttons[i] = mod;
        }
        $(domSelector).flexPanel({
            ButtonsModel: [buttons]
        });
        for (var i = 0; i < buttons.length; i++) {
            var mod = buttons[i];
            if (mod.onClick) {
                //Button binding click event
                $('#label-' + mod.id).on('click', mod.onClick);
            }
        }
    },

    /**
     * Add table
     * formLock: Whether to lock the form (usually used on the details page, which will hide the
     *      tabs[{
     *          id: id
     * display: title
     *          type: {
     * '': default
     * input: input box
     * date: time selector (see addDateInput for other parameters)
     *          }
     * length: maximum length
     * tip: Is it required? If required, please pass
     * value: default value
     * readonly: whether it is read-only
     * onClick: click event
     * onInput: input input event
     * selectArr: When dropping down the box, pass the type array
     * checkMethods: check method
     * Array form [{
     *                  method: function,
     * tip:'tip'
     *              }]
     * For common methods, see checkMethodUtils.js
     *          }]
     */
    addForm: function (domSelector, groupModels, formLock) {
        var inputMap = {};
        var models = [];
        for (var i = 0; i < groupModels.length; i++) {
            var groupModel = groupModels[i];
            var tabs = groupModel.tabs;
            var model = {
                title: groupModel,
                colgroup: groupModel.colgroup ? groupModel.colgroup : {width: ['150px', '325px', '150px', '325px']},
                tabs: {
                    name: this.getArrByKey('id', tabs),
                    display: this.getArrByKey('display', tabs),
                    type: this.getArrByKey('type', tabs),
                    length: this.getArrByKey('length', tabs),
                }
            }
            models.push(model);
        }
        $(domSelector).flexPanel({
            TableGroupModel: models
        });
        for (var i = 0; i < groupModels.length; i++) {
            var groupModel = groupModels[i];
            for (var j = 0; j < groupModel.tabs.length; j++) {
                var tab = groupModel.tabs[j];
                if (tab.tip && !formLock) {
                    $('.td-' + tab.id).append('<span class="span-tip red">' + tab.tip + '</span>');
                }
                var type = tab.type || 'input';
                var _input = $('#' + type + '-' + tab.id);
                if (tab.readonly) {
                    _input.attr('readonly', 'readonly');
                }
                if (tab.onClick) {
                    _input.on('click', tab.onClick);
                }
                if (tab.value) {
                    _input.val(tab.value);
                }
                if (tab.onInput) {
                    _input.on('input propertychange keypress', tab.onInput);
                }

                if (tab.selectArr) {
                    this.addSelectInput({
                        pid: '.td-' + tab.id,
                        id: tab.id,
                        arr: tab.selectArr,
                        value: tab.value,
                        preicon: tab.preicon,
                        multiple: tab.multiple,
                        isHasAll: tab.isHasAll,
                        multipleTitle: tab.multipleTitle,
                        onClick: tab.onChange
                    });
                    inputMap[tab.id] = $('.td-' + tab.id + ' #hidden-' + tab.id);
                } else if (tab.type === 'date') {
                    tab.pid = '.td-' + tab.id;
                    inputMap[tab.id] = this.addDateInput(tab);
                } else {
                    inputMap[tab.id] = $('.td-' + tab.id + ' #' + type + '-' + tab.id);
                }
                //Lock table
                if (formLock) {
                    //Drop-down box disables combox-xxx
                    if (tab.selectArr) {
                        $('#combox-' + tab.id).attr('disabled', 'disabled');
                    } else {
                        inputMap[tab.id].attr('disabled', 'disabled');
                    }
                }
            }
        }
        return inputMap;
    },
    getArrByKey: function (key, tabs) {
        var arr = [];
        for (var i = 0; i < tabs.length; i++) {
            var tab = tabs[i];
            if (tab[key]) {
                arr.push(tab[key])
            } else {
                arr.push('')
            }
        }
        return arr;
    },
    /**
     * Get form submission data
     * Format {
     *     key: value,
     *     key1: value1
     * }
     */
    getParamByDom: function (formInputDom) {
        var formParam = {};
        if (!formInputDom) {
            return formParam;
        }
        for (var key in formInputDom) {
            if (formInputDom.hasOwnProperty(key)) {
                formParam[key] = $.trim(formInputDom[key].val());
            }
        }
        return formParam;
    },
    /**
     * Get form submission data
     * Format {
     *     key: value,
     *     key1: value1
     * }
     */
    getParamByDomPlus: function (formInputDom, tail) {
        var formParam = {};
        if (!formInputDom) {
            return formParam;
        }
        for (var key in formInputDom) {
            if (formInputDom.hasOwnProperty(key) && key.indexOf(tail) > 0) {
                formParam[key.replaceAll(tail, "")] = $.trim(formInputDom[key].val());
            }
        }
        return formParam;
    },
    /**
     * Get the form submission data and the format received in the background
     * @params getParamByDom data obtained
     * Format[{
     *     name: key,
     *     value: value
     * },{
     *     name: key1,
     *     value: value1
     * }]
     */
    getParamNameAndValue: function (params) {
        var paramsArr = [];
        for (var key in params) {
            if (params.hasOwnProperty(key)) {
                paramsArr.push({
                    name: key,
                    value: params[key]
                })
            }
        }
        return paramsArr;
    },
    /**
     * Get the form submission data and the format received in the background
     * @params getParamByDom data obtained
     * Format[{
     *     name: key,
     *     value: value
     * },{
     *     name: key1,
     *     value: value1
     * }]
     */
    getParamNameAndValuePlus: function (params, tail) {
        var paramsArr = [];
        for (var key in params) {
            if (params.hasOwnProperty(key) && key.indexOf(tail) > 0) {
                paramsArr.push({
                    name: key.replaceAll(tail, ""),
                    value: params[key]
                })
            }
        }
        return paramsArr;
    },
    /**
     * Check data
     */
    checkParam: function (groupModels) {
        var flag = true;
        for (var i = 0; i < groupModels.length; i++) {
            var groupModel = groupModels[i];
            for (var j = 0; j < groupModel.tabs.length; j++) {
                var tab = groupModel.tabs[j];
                var _input = $('#hidden-' + tab.id);
                if (_input.length === 0) {
                    _input = $('#input-' + tab.id);
                }
                if (tab.type === "textArea") {
                    _input = $('#textArea-' + tab.id);
                }
                if (tab.type === "date") {
                    _input = $('#combox-' + tab.id);
                }
                var value = $.trim(_input.val());
                var _td = $('.td-' + tab.id);
                var _tipSpan = _td.find('.span-tip');
                //Is there any verification error?
                var isCheckError = false;
                //Check legality
                if (tab.checkMethods && tab.checkMethods.length > 0) {
                    for (var k = 0; k < tab.checkMethods.length; k++) {
                        var checkMethod = tab.checkMethods[k];
                        if (!checkMethod.method(value)) {
                            if (value || value === 0) {
                                if (_tipSpan.length === 0) {
                                    _td.append('<span class="span-tip red"></span>');
                                    _tipSpan = _td.find('.span-tip');
                                }
                                if (checkMethod.tip) {
                                    _tipSpan.text(checkMethod.tip);
                                } else {
                                    _tipSpan.text(lang.line_error_param);
                                }
                                if (flag) {
                                    $('#' + groupModel.pid + ' .panel-body').addClass('show');
                                    _input.focus();
                                }
                                isCheckError = true;
                                flag = false;
                            }
                        }
                    }

                }
                //Verify that it is not empty
                if (tab.tip && !isCheckError) {
                    if (!value && value !== 0) {
                        _tipSpan.text(lang.not_be_empty);
                        if (flag) {
                            $('#' + groupModel.pid + ' .panel-body').addClass('show');
                            _input.focus();
                        }
                        flag = false;
                    } else {
                        _tipSpan.text('*');
                    }
                }
                if (!tab.tip && !isCheckError) {
                    _tipSpan.text('');
                }
            }
        }
        return flag;
    },
    /**
     * Generate form based on the incoming params and submit it
     * @param url
     * @param params
     * @param method
     */
    submitFormByParams: function (url, params, method) {
        if (!method) {
            method = 'post';
        }
        var _contentHide = $('.export-content-hide');
        if (_contentHide.length === 0) {
            _contentHide = $('<div class="export-content-hide" style="display: none;"></div>');
            $('body').append(_contentHide);
        }
        _contentHide.empty();
        params['jsession'] = rootElement.jsessionId;
        var formStr = '<form action="' + url + '" method="' + method + '" name="hideForm">';
        for (var param in params) {
            if (params.hasOwnProperty(param)) {
                var param1 = params[param];
                if (param1 && typeof param1 == 'string') {
                    param1 = param1.replaceAll("\'", " ");
                }
                formStr += "<input name='" + param + "' value='" + param1 + "'/>"
            }
        }
        formStr += '</form>';
        _contentHide.append(formStr);
        //If xzOrPage is uploaded, go to the download center, otherwise go to the original
        if (params && params.xzOrPage) {
            $.myajax.showLoading(true, lang.saving);
            $.myajax.post(url, params, function (json, success) {
                $.myajax.showLoading(false);
                if (json == null || success) {
                    rootElement.showRedRound();
                    $.dialog.tipSuccess(lang.add_to_down_load_center, 1);
                } else {
                    $.dialog.tipDanger(lang.saveFailure, 1);
                }
            });
        } else {
            document.hideForm.submit();
        }
    },

    /**
     * Check the validity of the date
     * @param begin
     * @param end
     * @param type 1: time format 2: date format 3: month format
     */
    checkDateRange: function (begindate, enddate, type) {
        //Month format 2021-06
        if (type == 3) {
            if (!dateIsValidMonthDate(begindate)) {
                $.dialog.tipWarning(lang.errQueryTimeFormat);
                return false;
            }

            if (!dateIsValidMonthDate(enddate)) {
                $.dialog.tipWarning(lang.errQueryTimeFormat);
                return false;
            }
            if (dateCompareStrMonthDate(begindate, enddate) > 0) {
                $.dialog.tipWarning(lang.errQueryTimeRange);
                return false;
            }
            return true;
        }
        //Time format 2021-06-05 10:10:10
        if (type == 1) {
            if (!dateIsValidDateTime(begindate)) {
                $.dialog.tipWarning(lang.errQueryTimeFormat);
                return false;
            }

            if (!dateIsValidDateTime(enddate)) {
                $.dialog.tipWarning(lang.errQueryTimeFormat);
                return false;
            }

            if (dateCompareStrLongTime(begindate, enddate) > 0) {
                $.dialog.tipWarning(lang.errQueryTimeRange);
                return false;
            }
            return true;
        }

        //Default date format 2021-06-05
        if (!dateIsValidDate(begindate)) {
            $.dialog.tipWarning(lang.errQueryTimeFormat);
            return false;
        }
        if (!dateIsValidDate(enddate)) {
            $.dialog.tipWarning(lang.errQueryTimeFormat);
            return false;
        }
        if (dateCompareStrDate(begindate, enddate) > 0) {
            $.dialog.tipWarning(lang.errQueryTimeRange);
            return false;
        }
        return true;
    },


    /**
     * Array object sorting
     * @param {String} property The property corresponding to the array object
     * @param {boolean} desc sort in descending order
     * @returns {Function}
     */
    conpareFunction: function (property, desc) {
        return function (a, b) {
            var value1 = 'a';
            var value2 = 'a';
            if (a[property]) {
                value1 = 'a' + a[property];
            }
            if (b[property]) {
                value2 = 'a' + b[property];
            }
            //The default is ascending order
            if (desc) {
                return value2.localeCompare(value1, "zh");
            }
            return value1.localeCompare(value2, "zh");
        }
    },
    /**
     * Add required identifier
     * @param domId node selector
     */
    addRequiredTip: function (domId) {
        if (Array.isArray(domId)) {
            domId.forEach(function (item) {
                $(item).append('<span class="span-tip red">*</span>');
            })
            return;
        }
        $(domId).append('<span class="span-tip red">*</span>');
    },
    /**
     * Get params parameters based on form
     * @param reportForm
     */
    getParamsByForm: function (reportForm) {
        var inputs = $(reportForm).find('input[name]');
        var params = {};
        inputs.each(function (i,obj) {
            params[obj.name] = obj.value;
        })
        return params;
    }

}
