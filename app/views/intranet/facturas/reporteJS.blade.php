<script>


    var ReporteFacturasViewModel = function () {
        var me = this;
        me.inidate = ko.observable();
        me.enddate = ko.observable();
        me.total = ko.observable();
        me.anuladas = ko.observable();
        me.valor = ko.observable();
        me.loaded = ko.observable(false);


        me.reporte = function(){
            window.open( path + '/intranet/facturas/excel?inidate='+me.inidate()+'&enddate='+me.enddate());
        }

        me.consultar = function(){
            var data = {
                inidate : me.inidate(),
                enddate : me.enddate(),
            };
            $.ajax({
                type: "GET",
                url: path + "/api/v1/facturas/report",
                data: data,
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function (data) {
                    if(data.report){
                        me.total(data.report.total);
                        me.anuladas(data.report.anuladas);
                        me.valor(data.report.valor);
                        me.loaded(true);
                    }
                },
                error: function (data) {
                    console.log(data);
                    console.log("error :(");
                }
            });
        }

        me.initialize = function(){
            ko.applyBindings(me);
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
            }).on('changeDate',function(e){
                var $this = $(this),
                    name = $this.data('name');
                me[name]($this.val());
            });
        }

        return {
            initialize: me.initialize
        };
    }();

    $(function () {
        ReporteFacturasViewModel.initialize();
    });
</script>
