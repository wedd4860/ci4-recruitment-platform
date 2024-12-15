
String.prototype.iv_split = String.prototype.iv_split || function () { 
    "use strict";
    var str = this.toString();
    if (arguments.length) {
        var t = typeof arguments[0];
        var key;
        var args = ("string" === t || "number" === t) ? Array.prototype.slice.call(arguments) : arguments[0];
        for (key in args) {
            str = str.replace(new RegExp("\\%" + key + "\\%", "gi"), args[key]);
        }
    }
    return str;
};
String.prototype.iv_trim = String.prototype.iv_trim || function () {
    "use strict";
    return this.replace(/ /gi, '');
};