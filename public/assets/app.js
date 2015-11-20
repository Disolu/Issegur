/**
 * Created by User on 15/11/2015.
 */
//variables Globales
var GlobalParameters = new (function () {
    var me = this;

    me.appPath = "http://localhost:8080/IssegurReg/public";
    me.grupoIndex = 0;

    return {
        appPath : me.appPath,
        grupoIndex: me.grupoIndex
    };

});
