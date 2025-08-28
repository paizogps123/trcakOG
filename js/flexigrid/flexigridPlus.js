//index page public data
var rootElement = _getRootFrameElement();
var lang = rootElement.lang;

/**
 * Secondary encapsulation of flexigrid.js to simplify the code
 * initTable: initialize table
 * selectedRows: selected rows, get the value directly from the page, not recommended
 * selectedRowsData: selected rows, original data, recommended
 * selectedRowsDataIds: ID array of selected rows
 * flexGetData: Get all row data, which is original data. rows and infos are the same
 * allRows: Get all rows. Note that value is the value actually displayed on the page, not the original data.
 * flexReload: Refresh the table
 * flexAddData: Manually add data to the table, used with onSubmit
 */
;(function (window) {
    "use strict";

    function extend(o, def) {
        for (var key in def) {
            if ((def.hasOwnProperty(key))) {
                if(!o.hasOwnProperty(key)){
                    o[key] = def[key];
                }
            }
        }
        return o;
    }

    var GridPlus = function (opts) {
        opts = opts || {};
        this._initial(opts);
        this.initTable();
        return this;
    }
    GridPlus.prototype = {
        constructor: this,
        _initial: function (opts) {
            //Default parameters
            var options = {
                domSelector: '',
                url: '',
                colModel: [],
                dataType: 'json',
                method: 'post',
                nowrap: true,//Whether not to wrap
                perNumber: lang.perNumber,
                pageStat: lang.pagestatInfo,
                pageFrom: lang.pageFrom,
                pageText: lang.page,
                pageTotal: lang.pageTotal,
                checkbox: true, //Do you want multiple check boxes?
                singleSelect: false, //Single choice or not
                findText: lang.find,
                procMsg: lang.procMsg,
                noMsg: lang.noMsg,
                usePager: true,//Whether to paginate
                autoload: true,//Automatic loading, that is, making an ajax request for the first time
                useRp: true,//Is it possible to dynamically set the number of results displayed per page?
                title: false,//Whether to include a title
                rp: 15,//Default number of results per page
                rpOptions: [10, 15, 20, 50, 100, 200, 500],//You can choose to set the number of results per page. There will be problems with parsing after 500.
                showTableToggleBtn: true,//Whether to display the [Show Hide Grid] button
                showToggleBtn: true,//Whether to allow hidden columns to be displayed. There is a bug in this attribute. Set it to false and click on the header script to report an error.
                striped: true, //Whether to display the stripe effect, the default is the odd-even interaction form
                idProperty: 'id',
                onSubmit: false,//When calling a custom request function, the plug-in will not automatically load data from
                onSuccess: false,//Execute after success
                onError: false,//Execute after failure
                onDoubleClick: false,//Double click callback
            };
            this.setColModelDefaultProperty(opts.colModel);
            this.options = extend(opts, options);

        },
        /**
         * Set colModel default value
         * Properties currently supported by colModel (not necessarily complete):
         * display: title
         * name: class name
         * width: width
         * sortable: whether to enable sorting, default false
         * align: text position default center
         * hide: Whether to hide, default false
         */
        setColModelDefaultProperty: function(colModel){
            for (var i = 0; i < colModel.length; i++) {
                var col = colModel[i];
                if(!col.sortable){
                    col.sortable = false;
                }
                if(!col.align){
                    col.align = 'center';
                }
                if(!col.hide){
                    col.hide = false;
                }
            }
        },
        /**
         * Initialization form
         */
        initTable: function () {
            //Initialization form
            this.tableObj = $(this.options.domSelector).flexigrid(this.options);
        },
        /**
         * The selected row is not recommended for use
         */
        selectedRows: function () {
            return this.tableObj.selectedRows();
        },
        /**
         * selected row
         */
        selectedRowsData: function () {
            return this.tableObj.selectedRowsData();
        },
        /**
         * Selected row ids
         */
        selectedRowsDataIds: function () {
            var rows = this.tableObj.selectedRowsData();
            var ids = [];
            for (var i = 0; i < rows.length; i++) {
                var row = rows[i];
                ids.push(row.id);
            }
            return ids;
        },
        /**
         * Get all row data, which is original data. rows and infos are the same
         */
        flexGetData: function () {
            return this.tableObj.flexGetData();
        },
        /**
         * Get all rows. Note that value is the value actually displayed on the page, not the original data.
         */
        allRows: function () {
            return this.tableObj.allRows();
        },
        /**
         * Refresh table
         * @param params request parameters, can be omitted
         */
        flexReload: function (params) {
            this.tableObj.flexOptions(params).flexReload();
        },
        /**
         * Manually add data to the table and use it with onSubmit
         */
        flexAddData: function (json, type) {
            this.tableObj.flexAddData(json, type);
        },
        flexGetHeaderInfo: function () {
            return this.tableObj.flexGetHeaderInfo();
        },
        flexFixHeight: function () {
            this.tableObj.flexFixHeight();
        },
        flexGenerateRowSetCode: function () {
            this.tableObj.flexGenerateRowSetCode();
        },
        getDataByIndex:function (index) {
            var datas = this.tableObj.flexGetData();
            if (!datas || !datas.rows){
                return null;
            }
            return datas.rows.find(function(item){
                return item.index == index;
            })
        },
    }

    window.GridPlus = GridPlus;

})(window);

