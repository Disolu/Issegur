/**
 * Created by User on 15/11/2015.
 */
//variables Globales
var GlobalParameters = new (function () {
    var me = this;

    me.appPath = "";
    me.grupoIndex = 0; 
    me.pageSize = 25;
    me.maxVisiblePages = 20;

    return {
        appPath : me.appPath,
        grupoIndex: me.grupoIndex,
        globalPageSize: me.pageSize,
        globalMaxVisiblePages: me.maxVisiblePages
    };

});
