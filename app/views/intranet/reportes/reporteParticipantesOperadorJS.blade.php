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

        //reportDetails
        me.sortField = ko.observable("pa_apellido_paterno");
        me.sortDirection = ko.observable("asc");
        me.pageSize = ko.observable();
        me.numberOfPages = ko.observable();
        me.pageNumberArray = ko.observableArray([]);
        me.totalRows = ko.observable(0);
        //intially 1
        me.currentPageNumber = ko.observable(1);

        me.initialize = function(){
            //datepickers setting
            $("#inputDesdeFecha").datepicker({ dateFormat: 'dd/mm/yy' });
            $("#inputHastaFecha").datepicker({ dateFormat: 'dd/mm/yy' });
            //evento del sorting para la cabecera de la tabla
            $("th.sortable").on("click", me.headerClick);
            //cargamos los operadores
            me.loadOperadores();

            ko.applyBindings(reportViewModel, $("#reporteParticipantesOperador")[0]);
        };

        me.headerClick = function(e){
            var $clickedTh = $(e.target);
            var $clickedTh = $clickedTh.hasClass('sortable') ? $clickedTh : $clickedTh.closest('th.sortable');
            var $icon = $clickedTh.find('i.fa');
            var hasSortUp = $icon.hasClass("fa-sort-asc");
            var sortField = $clickedTh.attr("data-sort");
            var sortDirection;
            $("i.fa").removeClass("fa-sort").removeClass("fa-sort-asc")
                            .removeClass("fa-sort-desc").addClass("fa-sort");
            if(hasSortUp){
                $icon.removeClass("fa-sort").addClass("fa-sort-desc");
                sortDirection = "desc";
            }else{
                $icon.removeClass("fa-sort").addClass("fa-sort-asc");
                sortDirection = "asc";
            }
            me.participantes.removeAll();
            me.loadingParticipantes(true);
            me.sortDirection(sortDirection);
            me.sortField(sortField);
            var loadReport = me.reporteParticipantesByOperador(me.operadorId(), me.fechaDesde(),
                                                                me.fechaHasta(),0, me.pageSize(), me.sortField(), 
                                                                me.sortDirection());
            me.currentPageNumber(1);
            // loadReport.done(function(){
                
            // });
        }

        me.pageClick = function (clickedPage) {
            var previousPage = me.currentPageNumber();
            //variable to tell the table to slide or not
            var animateTable = false;
            if(previousPage != clickedPage){
                if(clickedPage == '&laquo;'){
                    //only want to lower it if it isn't one.
                    if(previousPage != 1){
                        me.currentPageNumber(previousPage - 1);
                        animateTable = true;
                    }
                }else if (clickedPage == '&raquo;'){   
                    if(previousPage < me.numberOfPages()){
                        me.currentPageNumber(previousPage + 1);
                        animateTable = true;
                    }
                }else{
                    me.currentPageNumber(clickedPage);
                    animateTable = true;
                }
                if(animateTable){
                    $(".data-row").fadeOut('fast');
                    //me.isLoading(true);
                    var loadNewData = me.reporteParticipantesByOperador(me.operadorId(), me.fechaDesde(),
                                                      me.fechaHasta(),(me.currentPageNumber() - 1) * me.pageSize(), 
                                                      me.pageSize(), me.sortField(), me.sortDirection());
                    loadNewData.done(function(){
                        $(".data-row").fadeIn('fast');
                    });
                    
                }
            }
        };

        me.setReportPagerDetails = function () {
            //this gets the values for pagesize and number of pages
            //and sets the observables in the viewmodel            
            $.ajax({
                type: "GET",
                url: path + "/api/v1/getReportParticipantesPorOperadorPagerDetails",
                dataType: "json",
                data: {operadorId: me.operadorId(), fechaInicio: me.fechaDesde(), fechaFin: me.fechaHasta()},
                contentType: "application/json"
            }).done(function (data) {
                me.pageSize(data.pageSize);
                me.numberOfPages(data.numberOfPages);
                me.totalRows(data.totalRows);
                var numArray = new Array();
                numArray.push('&laquo;');
                for(var i = 1; i <= me.numberOfPages(); i++){
                    numArray.push(i);
                }
                numArray.push('&raquo;');
                me.pageNumberArray(numArray);
                //once we have the pagesize and numberofpages we load the inital data
                me.reporteParticipantesByOperador(me.operadorId(), me.fechaDesde(),
                                                  me.fechaHasta(),0, me.pageSize(), me.sortField(), me.sortDirection());
            }).fail(function () {
                //viewModel.isLoading(false);
                console.log("Error al cargar la data");
            });
        }

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
            var desdeRaw = me.fechaDesde().split("/");
            var desde = new Date(desdeRaw[2], desdeRaw[1] - 1, desdeRaw[0]);

            var hastaRaw = me.fechaHasta().split("/");
            var hasta = new Date(hastaRaw[2], hastaRaw[1] - 1, hastaRaw[0]);

            if((desde < hasta)){
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
                    me.setReportPagerDetails();
                    //me.reporteParticipantesByOperador();
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

        me.reporteParticipantesByOperador = function(operadorId, fechaDesde,fechaHasta, skip, take, sortField, sortDirection)
                                                        
        {
            var deferred = $.Deferred();
            me.loadingParticipantes(true);
            $.ajax({
                type: "GET",
                url: path + "/api/v1/reporteParticipantesByOperador",
                dataType: "json",
                data: {operadorId: operadorId, fechaInicio: fechaDesde, fechaFin: fechaHasta,
                        skip: skip, take: take, sortField: sortField, sortDirection: sortDirection},
                contentType: "application/json; charset=utf-8",
                success: function (data) {
                    var rawParticipantes = data.participantes;
                    var index = skip + 1;
                    //limpiamos el array
                    me.participantes.removeAll();  
                    //llenamos el array observable para bindear
                    for(var i = 0; i < rawParticipantes.length; i++){
                        rawParticipantes[i].index = index;
                        me.participantes.push(rawParticipantes[i]);
                        index++;
                    }                    
                    me.loadingParticipantes(false);
                },
                error: function (data) {
                    toastr.error('Hubo un error al cargar los datos','Error');
                    console.log(data);
                }
            }); 

            return deferred.promise();           
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
            suppressValidationMessages: me.suppressValidationMessages,
            pageNumberArray: me.pageNumberArray,
            currentPageNumber: me.currentPageNumber,
            pageClick: me.pageClick,
            totalRows: me.totalRows
        }
    }();

    $(function(){
        reportViewModel.initialize();
    });
</script>