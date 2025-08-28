!
function(t) {
    "use strict";
    var i = function() {};
     i.prototype.initMultiSelect = function() {
        t('[data-plugin="multiselect"]').length > 0 && t('[data-plugin="multiselect"]').multiSelect(t(this).data())
    }, i.prototype.init = function() {
        this.initMultiSelect()
    }, t.Components = new i, t.Components.Constructor = i
}(window.jQuery),
function(t) {
    "use strict";
    t.Components.init()
}(window.jQuery);