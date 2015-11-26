<script>
    var ReprogramacionDialog = new (function () {
        var me = this;

        me.dniParticipante = ko.observable(null);
        me.nombreParticipante = ko.observable(null);
        me.nuevaFechaProgramacion = ko.observable(null);
        me.IsFechaProgramacionSupplied = ko.computed(function(){ return $.trim(me.nuevaFechaProgramacion()).length > 0}, me)
        me.nuevoTurno = ko.observable(null);
        me.isNuevoTurnoSupplied = ko.computed(function(){ return (me.nuevoTurno() > 0)}, me)
        me.hasBeenInitialized = false;
        me.supressValidationMessages = ko.observable(true);
        me.validationRegistros = ko.observableArray([]);

        me.initialize = function () {
            if (!me.hasBeenInitialized) {
                ko.applyBindings(ReprogramacionDialog, $("#reprogramacionDialog")[0]);

                $("#nuevaFechaProgramacion").datepicker({
                    dateFormat: 'dd/mm/yy',
                    minDate: new Date()
                });
                me.hasBeenInitialized = true;
            }
            $(document.body).on("change","#nuevaFechaProgramacion", me.onFechaProgramacionChange);

            me.datePickerSettings();
            $("#reprogramacionDialog").modal("show");
        };

        me.setParticipante = function (dni, nombre) {
            me.dniParticipante(dni);
            me.nombreParticipante(nombre);
        };

        me.onGuardarReprogramacion = function (data, event) {
            me.validate({
                valid: function () {
                    $.ajax({
                        type: "GET",
                        url: path + "/api/v1/reprogramarParticipante",
                        data: {dni: me.dniParticipante(),fechaProgramacion: me.nuevaFechaProgramacion(), turnoId: me.nuevoTurno()},
                        dataType: "json",
                        contentType: "application/json; charset=utf-8",
                        success: function (data) {
                            var validationArray = data.validation;
                            me.validationRegistros.removeAll();

                            if(validationArray.length > 0) {
                                for (var i = 0; i < validationArray.length; i++) {
                                    me.validationRegistros.push(validationArray[i]);
                                }
                                $("#registroValidations").show();
                            }
                            else{
                                $("#reprogramacionDialog").modal("hide");
                                toastr.success('El participante ha sido reprogramado con éxito','Participante Reprogramado');
                                ReprogramacionViewModel.loadParticipantesAReprogramar();
                            }

                        },
                        error: function (data) {
                            console.log(data);
                            console.log("error ;(");
                        }
                    });
                },
                invalid: function () {

                }
            });
        };

        me.validate = function (options) {
            me.supressValidationMessages(false);
            if (me.IsFechaProgramacionSupplied() && me.isNuevoTurnoSupplied()) {
                options.valid();
            }
            else{
                options.invalid();
            }
        };

        me.onFechaProgramacionChange = function () {
            var currentDate = $("#nuevaFechaProgramacion").datepicker("getDate");
            if (currentDate != null || $.trim(currentDate) != "") {
                var dia = $.datepicker.formatDate('DD', currentDate);
                var optionsAsString = "<option value=''>Elija su horario</option>";

                $.ajax({
                    type: "GET",
                    url: path + "/api/v1/consultarTurnosPorDia",
                    async: false,
                    data: {nombreDia: dia },
                    dataType: "json",
                    contentType: "application/json; charset=utf-8",
                    success: function (data) {
                        for (i = 0;i < data.turnos.length; i++){
                            optionsAsString += "<option value='" + data.turnos[i].turnoId+ "'>" + data.turnos[i].turnoHorario + "</option>";
                        }
                        $("#horarios").html(optionsAsString);
                        $("#horarios").prop('disabled', false);
                    },
                    error: function (data) {
                        console.log(data);
                        console.log("error ;(");
                    }
                });
            }
            else{
                //limpiamos el combobox de horarios y los deshabilitamos
                $("#horarios").html(optionsAsString);
                $("#horarios").prop('disabled', true);
            }
        };

        me.datePickerSettings = function(){
            $.datepicker.regional['es'] = {
                monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
                dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
                dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
                weekHeader: 'Sm',
                dateFormat: 'dd/mm/yy',
                firstDay: 1,
                isRTL: false,
                showMonthAfterYear: false,
                yearSuffix: ''
            };
            $.datepicker.setDefaults($.datepicker.regional['es']);
        };

        return {
            initialize: me.initialize,
            setParticipante: me.setParticipante,
            dniParticipante: me.dniParticipante,
            nombreParticipante: me.nombreParticipante,
            nuevaFechaProgramacion: me.nuevaFechaProgramacion,
            nuevoTurno: me.nuevoTurno,
            onGuardarReprogramacion: me.onGuardarReprogramacion,
            onFechaProgramacionChange: me.onFechaProgramacionChange,
            supressValidationMessages: me.supressValidationMessages,
            IsFechaProgramacionSupplied: me.IsFechaProgramacionSupplied,
            isNuevoTurnoSupplied: me.isNuevoTurnoSupplied,
            supressValidationMessages: me.supressValidationMessages,
            validationRegistros: me.validationRegistros
        };
    });

    var ReprogramacionViewModel = function (){
        var me = this;

        me.participantes = ko.observableArray([]);
        me.loadingParticipantes = ko.observable(false);

        me.initialize = function () {
            $(document.body).on('keydown', '#filtroPersonal' , me.search);
            me.loadParticipantesAReprogramar();

            ko.applyBindings(ReprogramacionViewModel, $("#reprogramacion")[0]);
        };

        me.search = function (e) {
            var inputValue = e.keyCode;
            //si se presiona enter
            if(inputValue == 13) {
                me.loadParticipantesAReprogramar();
            }
        };

        me.loadParticipantesAReprogramar = function () {
            me.loadingParticipantes(true);
            $.ajax({
                type: "GET",
                url: path + "/api/v1/obtenerParticipantesAReprogramar",
                data: {searchText: $("#filtroPersonal").val()},
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function (data) {
                    var participantesRaw = data.result;
                    me.loadingParticipantes(false);
                    me.participantes.removeAll();
                    for (var i = 0; i < participantesRaw.length; i++) {
                        me.participantes.push(participantesRaw[i]);
                    }
                },
                error: function (data) {
                    console.log(data);
                    console.log("error :(");
                }
            });
        };

        me.onReprogramarClick = function (data, event) {
            var nombreParticipante = data.pa_nombres + ' ' + data.pa_apellido_paterno + ' ' + data.pa_apellido_materno;
            ReprogramacionDialog.setParticipante(data.pa_dni, nombreParticipante);
            ReprogramacionDialog.initialize();
        };

        return {
            initialize: me.initialize,
            participantes : me.participantes,
            loadParticipantesAReprogramar: me.loadParticipantesAReprogramar,
            onReprogramarClick: me.onReprogramarClick
        }
    }();

    $(function () {
        ReprogramacionViewModel.initialize();
    });
</script>