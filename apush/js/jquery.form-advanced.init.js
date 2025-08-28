function loadObjectRpt()
{
jQuery(document).ready(function () {

	$("#dialog_report_object_list").multiSelect('destroy');
    //advance multiselect start
    $('#dialog_report_object_list').multiSelect({
        selectableHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder=' search...'  style='width:100%' >",
        selectionHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder=' search...'  style='width:100%'>",
        afterInit: function (ms) {
            var that = this,
                $selectableSearch = that.$selectableUl.prev(),
                $selectionSearch = that.$selectionUl.prev(),
                selectableSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selectable:not(.ms-selected)',
                selectionSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selection.ms-selected';

            that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                .on('keydown', function (e) {
                    if (e.which === 40) {
                        that.$selectableUl.focus();
                        return false;
                    }
                });

            that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                .on('keydown', function (e) {
                    if (e.which == 40) {
                        that.$selectionUl.focus();
                        return false;
                    }
                });
        },
        afterSelect: function () {
            this.qs1.cache();
            this.qs2.cache();
        },
        afterDeselect: function () {
            this.qs1.cache();
            this.qs2.cache();
        }
    });

    // Select2
    //$(".select2").select2();

    //$(".select2-limiting").select2({ maximumSelectionLength: 2});

    //$('.selectpicker').selectpicker();
    //$(":file").filestyle({input: false});
});
}

function loadcpanel_report()
{
jQuery(document).ready(function () {

    $("#dialog_generat_report_list").multiSelect('destroy');
    //advance multiselect start
    $('#dialog_generat_report_list').multiSelect({
        selectableHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder=' search...'  style='width:100%' >",
        selectionHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder=' search...'  style='width:100%'>",
        afterInit: function (ms) {
            var that = this,
                $selectableSearch = that.$selectableUl.prev(),
                $selectionSearch = that.$selectionUl.prev(),
                selectableSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selectable:not(.ms-selected)',
                selectionSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selection.ms-selected';

            that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                .on('keydown', function (e) {
                    if (e.which === 40) {
                        that.$selectableUl.focus();
                        return false;
                    }
                });

            that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                .on('keydown', function (e) {
                    if (e.which == 40) {
                        that.$selectionUl.focus();
                        return false;
                    }
                });
        },
        afterSelect: function () {
            this.qs1.cache();
            this.qs2.cache();
        },
        afterDeselect: function () {
            this.qs1.cache();
            this.qs2.cache();
        }
    });
});
}


function loadsubuser_report()
{
jQuery(document).ready(function () {

    $("#subuser_generat_report_list").multiSelect('destroy');
    //advance multiselect start
    $('#subuser_generat_report_list').multiSelect({
        selectableHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder=' search...'  style='width:100%' >",
        selectionHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder=' search...'  style='width:100%'>",
        afterInit: function (ms) {
            var that = this,
                $selectableSearch = that.$selectableUl.prev(),
                $selectionSearch = that.$selectionUl.prev(),
                selectableSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selectable:not(.ms-selected)',
                selectionSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selection.ms-selected';

            that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                .on('keydown', function (e) {
                    if (e.which === 40) {
                        that.$selectableUl.focus();
                        return false;
                    }
                });

            that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                .on('keydown', function (e) {
                    if (e.which == 40) {
                        that.$selectionUl.focus();
                        return false;
                    }
                });
        },
        afterSelect: function () {
            this.qs1.cache();
            this.qs2.cache();
        },
        afterDeselect: function () {
            this.qs1.cache();
            this.qs2.cache();
        }
    });
});
}

function loadtrip_people()
{
jQuery(document).ready(function () {

    $("#dialog_boarding_trip_employee").multiSelect('destroy');
    //advance multiselect start
    $('#dialog_boarding_trip_employee').multiSelect({
        selectableHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder=' search...'  style='width:100%' >",
        selectionHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder=' search...'  style='width:100%'>",
        afterInit: function (ms) {
            var that = this,
                $selectableSearch = that.$selectableUl.prev(),
                $selectionSearch = that.$selectionUl.prev(),
                selectableSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selectable:not(.ms-selected)',
                selectionSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selection.ms-selected';

            that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                .on('keydown', function (e) {
                    if (e.which === 40) {
                        that.$selectableUl.focus();
                        return false;
                    }
                });

            that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                .on('keydown', function (e) {
                    if (e.which == 40) {
                        that.$selectionUl.focus();
                        return false;
                    }
                });
        },
        afterSelect: function () {
            this.qs1.cache();
            this.qs2.cache();
        },
        afterDeselect: function () {
            this.qs1.cache();
            this.qs2.cache();
        }
    });
});
}


