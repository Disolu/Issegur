<script>
    var reportViewModel =  function (){
        var me = this;

        me.operadores = ko.observableArray([]);
        me.loadingParticipantes = ko.observable(false);
        me.participantes = ko.observableArray([]);
        //format dates
        me.today = new Date();
        me.formattedEndDate = me.today.getDate() + '/' + (me.today.getMonth() + 1)+ '/' + me.today.getFullYear();
        me.today.setDate(me.today.getDate() - 30);
        me.formattedStartDate = me.today.getDate() + '/' + (me.today.getMonth() + 1) + '/' + me.today.getFullYear();
        //
        me.fechaDesde = ko.observable(me.formattedStartDate);
        me.IsFechaDesdeSupplied = ko.computed(function(){ return $.trim(me.fechaDesde()).length > 0}, me);
        me.fechaHasta = ko.observable(me.formattedEndDate);
        me.IsFechaHastaSupplied = ko.computed(function(){ return $.trim(me.fechaHasta()).length > 0}, me);
        me.operadorId = ko.observable(null);
        me.IsOperadorSupplied = ko.computed(function(){ return me.operadorId()? true: false}, me);
        me.isSearchValid = ko.observable(true);
        me.suppressValidationMessages = ko.observable(true);

        me.initialize = function(){
            //datepickers setting
            $("#inputDesdeFecha").datepicker({ dateFormat: 'dd/mm/yy' });
            $("#inputHastaFecha").datepicker({ dateFormat: 'dd/mm/yy' });
            //cargamos los operadores
            me.loadOperadores();

            ko.applyBindings(reportViewModel, $("#reporteParticipantesOperador")[0])
        };

        me.loadOperadores = function(rawOperadores){
            if (rawOperadores) {
                me.operadores.removeAll();
                for(var i = 0; i < rawOperadores.length; i++){
                    me.operadores.push(rawOperadores[i]);
                }
            }
            else{
                $.ajax({
                    type: "GET",
                    url: path + "/api/v1/getOperadores",
                    dataType: "json",
                    contentType: "application/json; charset=utf-8",
                    success: function (data) {
                        me.loadOperadores(data.operadores);
                    },
                    error: function (data) {
                        toastr.error('Hubo un error al recuperar los operadores','Error');
                        console.log(data);
                    }
                });
            }
        };

        me.validateSearchOptions = function(options){
            var IsValid = true;
            me.suppressValidationMessages(false);
            if(me.IsFechaDesdeSupplied() && me.IsFechaHastaSupplied() && me.IsOperadorSupplied() && me.validateDates()){
                IsValid = true;
            }
            else{
                IsValid = false;
            }

            if(IsValid){
                options.valid();
            }
            else{
                options.invalid();
            }
        };

        me.validateDates = function(){
            if((me.fechaDesde() < me.fechaHasta())){
                me.isSearchValid(true);
                return true;
            }
            else{
                me.isSearchValid(false);
                return false;
            }
        };

        me.onBuscarButtonClick = function(){
            me.validateSearchOptions({
                valid: function(){
                    me.reporteParticipantesByOperador();
                },
                invalid: function(){
                    //no hacemos nada
                }
            });
        };

        me.setDetalleReporte = function(){
            $.get(path + "/api/v1/reporteParticipantesByOperadorDetalles", 
            function (data) {
                
            },
            function (data) {
                toastr.error('Hubo un error al cargar el detalle del reporte', 'Error');
            });

        };

        me.reporteParticipantesByOperador = function(rawParticipantes){
            if (rawParticipantes) {
                me.participantes.removeAll();
                for(var i = 0; i < rawParticipantes.length; i++){
                    me.participantes.push(rawParticipantes[i]);
                }
            }
            else{
                me.loadingParticipantes(true);
                $.ajax({
                    type: "GET",
                    url: path + "/api/v1/reporteParticipantesByOperador",
                    dataType: "json",
                    data: {operadorId: me.operadorId(), fechaInicio: me.fechaDesde(), fechaFin: me.fechaHasta()},
                    contentType: "application/json; charset=utf-8",
                    success: function (data) {
                        me.reporteParticipantesByOperador(data.participantes);
                        me.loadingParticipantes(false);
                    },
                    error: function (data) {
                        toastr.error('Hubo un error al cargar los datos','Error');
                        console.log(data);
                    }
                });
            }
        };

        return{
            initialize: me.initialize,
            operadores: me.operadores,
            loadOperadores: me.loadOperadores,
            loadingParticipantes: me.loadingParticipantes,
            participantes: me.participantes,
            fechaDesde: me.fechaDesde,
            fechaHasta: me.fechaHasta,
            operadorId: me.operadorId,
            onBuscarButtonClick: me.onBuscarButtonClick,
            isSearchValid: me.isSearchValid,
            suppressValidationMessages: me.suppressValidationMessages
        }
    }();

    $(function(){
        reportViewModel.initialize();
    });
</script>