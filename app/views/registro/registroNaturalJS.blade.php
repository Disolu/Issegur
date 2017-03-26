<script>
    var RegistroViewModel =  function (){
        var me = this;

        me.nroOperacion = ko.observable("");
        me.nroOperacionSupplied = ko.computed(function() { return $.trim(me.nroOperacion()).length > 0; }, me);
        me.fechaOperacion = ko.observable("");
        me.fechaOperacionSupplied =  ko.computed(function() { return (me.fechaOperacion() != null && $.trim(me.fechaOperacion()).length > 0); }, me);
        me.archivo = ko.observable("");
        me.archivoSupplied = ko.computed(function() { return $.trim(me.archivo()).length > 0; }, me);
        me.montoPago = ko.observable("");
        me.montoPagoSupplied = ko.computed(function(){ return $.trim(me.montoPago()).length > 0}, me);
        me.operadores = ko.observableArray([]);

        //Curso
        me.fechaProgramacion = ko.observable("");
        me.turnoId = ko.observable("");
        me.selectedOperadoresIds = ko.observableArray([]);
        me.isFechaProgramacionSupplied = ko.computed(function() { return (me.fechaProgramacion() != null && $.trim(me.fechaProgramacion()).length > 0); }, me);
        me.isTurnoSupplied = ko.computed(function() { return (me.turnoId() != null && me.turnoId() > 0) }, me);
        me.isOperadorSupplied = ko.computed(function() {return me.selectedOperadoresIds().length > 0}, me);

        // Participante
        me.email = ko.observable(null);
        me.dni = ko.observable(null);
        me.nombres = ko.observable(null);
        me.ape_paterno = ko.observable(null);
        me.ape_materno = ko.observable(null);
        me.isEmailSupplied = ko.computed(function() { return $.trim(me.email()).length > 0; }, me);
        me.isDniSupplied = ko.computed(function() { return $.trim(me.dni()).length > 0; }, me);
        me.isDniValid =  ko.computed(function() { return $.trim(me.dni()).length == 8 || $.trim(me.dni()).length == 0; }, me);
        me.isNombresSupplied = ko.computed(function () { return $.trim(me.nombres()).length > 0;}, me);
        me.isApePaternoSupplied = ko.computed(function () { return $.trim(me.ape_paterno()).length > 0;}, me);
        me.isApeMaternoSupplied = ko.computed(function () { return $.trim(me.ape_materno()).length > 0;}, me);

        me.supressValidationMessages = ko.observable(true);
        me.isSavingLoading = ko.observable(false);
        me.validationRegistros = ko.observableArray([]);
        me.validationParticipante = ko.observableArray([]);

        me.initialize = function () {

            // apply the knockout bindings
            ko.applyBindings(RegistroViewModel, $("#registro")[0]);

            //setup the handlers
            $(document.body).on('keydown', '.padni' , soloNumeros);
            $(document.body).on('keydown', '.soloNumeros' ,soloNumeros);
            $(document.body).on('keypress', '.letras' ,soloLetras);
            $(document.body).on('click',"#btnRegistrar", me.onSaveRegistroButtonClick);
            $(document.body).on('click',".checkboxOp", onOperadorChecked);

            var unavailableDates = ["8-10-2015", "9-10-2015" , "29-6-2016" , "28-7-2016" , "29-7-2016" , "30-8-2016" , "17-11-2016" , "18-11-2016" , "08-12-2016"];

            function unavailable(date) {
                dmy = date.getDate() + "-" + (date.getMonth() + 1) + "-" + date.getFullYear();
                if ($.inArray(dmy, unavailableDates) == -1) {
                    return [true, ""];
                } else {
                    return [false, "", "Unavailable"];
                }
            }

            //we initialize the datepicker
            $("#dtpFechaProgramacion").datepicker({
                prevText: '<i class="fa fa-chevron-left"></i>',
                nextText: '<i class="fa fa-chevron-right"></i>',
                showButtonPanel: false,
                beforeShow: function (input, inst) {
                    var themeClass = $(this).parents('.admin-form').attr('class');
                    var smartpikr = inst.dpDiv.parent();
                    if (!smartpikr.hasClass(themeClass)) {
                        inst.dpDiv.wrap('<div class="' + themeClass + '"></div>');
                    }

                },
                beforeShowDay: unavailable,
                minDate: new Date()
            });

            $("#dtpFechaProgramacion").keypress(function () {
                return false;
            });

            $("#dtpFechaOperacion").datepicker({
                prevText: '<i class="fa fa-chevron-left"></i>',
                nextText: '<i class="fa fa-chevron-right"></i>',
                showButtonPanel: false,
                beforeShow: function (input, inst) {
                    var themeClass = $(this).parents('.admin-form').attr('class');
                    var smartpikr = inst.dpDiv.parent();
                    if (!smartpikr.hasClass(themeClass)) {
                        inst.dpDiv.wrap('<div class="' + themeClass + '"></div>');
                    }
                }
            });

            //para permitir solo numeros en las casillas numéricas
            function soloNumeros(e) { //e,evt,event
                if($(e.target).hasClass("paData") && $.trim($(e.target).val()).length > 0){
                    $("#paValidationData").hide();
                }

                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                            // Allow: Ctrl+A
                        (e.keyCode == 65 && e.ctrlKey === true) ||
                            // Allow: Ctrl+C
                        (e.keyCode == 67 && e.ctrlKey === true) ||
                            // Allow: Ctrl+X
                        (e.keyCode == 88 && e.ctrlKey === true) ||
                            // Allow: home, end, left, right
                        (e.keyCode >= 35 && e.keyCode <= 39)) {
                    // let it happen, don't do anything
                    return;
                }
                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            }

            function soloLetras(e){
                var inputValue = e.which;
                // allow letters and whitespaces only.
                if($(e.target).hasClass("paData") && $.trim($(e.target).val()).length > 0){
                    $("#paValidationData").hide();
                }

                if((inputValue > 47 && inputValue < 58) && (inputValue != 32)){
                    e.preventDefault();
                }
            }

            $("input[type='file']").change(function(){
                //document.getElementById('uploaderVoucher').value = this.value.replace(/C:\\fakepath\\/i, '');
                me.archivo(this.value.replace(/C:\\fakepath\\/i, ''));
            });

            function onOperadorChecked(evt){
                evt.stopPropagation();
                var currentId = $(this).data("id");

                if($(this).is(":checked")){
                    me.selectedOperadoresIds.removeAll();
                    me.selectedOperadoresIds.push(currentId);
                }
            }

            //load Operadores
            me.datePickerSettings();
            me.loadOperadores();
        };

        me.isEmailValid = function () {
            // http://stackoverflow.com/a/46181/11236
            var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(me.email());
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

        me.formatDateFromDatabase = function(dateDB){
            var date =  new Date(dateDB);
            date.setDate(date.getDate() + 1);
            var dia = (date.getDate());
            var formattedDia = dia.toString().length == 1? '0'+ dia : dia;
            var month = (date.getMonth() + 1);
            var formattedMonth = month.toString().length == 1? '0'+ month : month;
            var fechaFormatted = formattedDia + "/" + formattedMonth + "/" + date.getFullYear();

            return fechaFormatted;
        };

        me.loadOperadores = function(rawOperadores){
            if (rawOperadores) {
                me.operadores.removeAll();
                for(var i = 0; i < rawOperadores.length; i++){
                    me.operadores.push(rawOperadores[i]);
                }
                me.setOperadores();
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
                        console.log('error');
                        console.log(data);
                    }
                });
            }
        };

        me.setOperadores = function(){
            var $divOperadores = $("#operadores");
            var operadoresContent = "";
            //recorremos la lista de operadores
            for (var i = 0; i < me.operadores().length; i++) {
                var operador = me.operadores()[i].op_nombre;
                var operadorId = me.operadores()[i].op_id;
                var operadoresContent = operadoresContent + "<div class='radio-custom radio-primary mb5'><input type='radio' name='operadoresGruopo' class='checkboxOp' id='chk" + operador + "' data-id='"+ operadorId +"'><label for='chk" +  operador + "'>"+ (operadorId == 3? "Almacenes " :"Almacén ") + operador +"</label></div>";
            }
            $divOperadores.html(operadoresContent);
        };

        me.uploadVoucher = function(){
            var formData = new FormData($("#voucherForm")[0]);

            $.ajax({
                type: "POST",
                url: path + "/uploadFile",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    console.log('archivo subido');
                },
                error: function (data) {
                    console.log(data);
                    console.log("error ;(");
                }
            });
        };

        //Curso

        me.onFechaProgramacionChange = function () {
            var dateRaw = me.fechaProgramacion();//$("#dtpFechaProgramacion").datepicker('getDate');
            var date = dateRaw.split("/");
            var currentDate = new Date(date[2], date[1] - 1, date[0]);
            if (dateRaw != null || $.trim(dateRaw) != "") {
                var dia = $.datepicker.formatDate('DD', currentDate);
                var optionsAsString = "<option value=''>Elija su horario</option>";

                $.ajax({
                    type: "GET",
                    url: path + "/api/v1/consultarTurnosPorDia",
                    async: false,
                    data: {nombreDia: dia , fecha: dateRaw},
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

        me.validateCurso = function(options){
            options = $.extend({valid: function(){}, invalid: function(){} }, (options || {}));


            if(me.isFechaProgramacionSupplied() && me.isTurnoSupplied() && me.isOperadorSupplied()){
                return true;
            }
            else{
                return false;
            }
        };

        //Participante
        me.loadFromParticipante = function (rawParticipante) {

            if(rawParticipante){
                me.nombres(rawParticipante.pa_nombres);
                me.ape_paterno(rawParticipante.pa_apellido_paterno);
                me.ape_materno(rawParticipante.pa_apellido_materno);
            }

        };

        me.consultarDNIButton = function (data,event) {
            $button = $(event.target);
            if ($.trim(me.dni()).length > 0) {
                $.ajax({
                    type: "GET",
                    url: path + "/api/v1/consultarDNI",
                    data:{ dni: $.trim(me.dni())},
                    dataType: "json",
                    contentType: "application/json; charset=utf-8",
                    success: function (data) {
                        if (data.participante.length > 0) {
                            me.loadFromParticipante(data.participante[0]);
                            $button.parents('td').siblings().find('.paData').prop("disabled",true);
                        }
                        else{
                            me.nombres('');
                            me.ape_paterno('');
                            me.ape_materno('');
                            $button.parents('td').siblings().find('.paData').prop("disabled",false);
                        }
                    },
                    error: function (data) {
                        console.log('error');
                        console.log(data);
                    }
                });
            }
            event.stopPropagation();
            event.preventDefault();
        };

        me.consultarDNI = function (data,event) {
            $input = $(event.target);
            if ($input.hasClass('padni')) {
                if (event.keyCode != 13) {
                    return true;
                }
                else{
                    if ($.trim(me.dni()).length > 0) {
                        $.ajax({
                            type: "GET",
                            url: path + "/api/v1/consultarDNI",
                            data:{ dni: $.trim(me.dni())},
                            dataType: "json",
                            contentType: "application/json; charset=utf-8",
                            success: function (data) {
                                if (data.participante.length > 0) {
                                    me.loadFromParticipante(data.participante[0]);
                                    $input.parents('td').siblings().find('.paData').prop("disabled",true);
                                }
                                else{
                                    me.nombres('');
                                    me.ape_paterno('');
                                    me.ape_materno('');
                                    $input.parents('td').siblings().find('.paData').prop("disabled",false);
                                }
                            },
                            error: function (data) {
                                console.log('error');
                                console.log(data);
                            }
                        });
                    }
                }
            }

            event.stopPropagation();
            event.preventDefault();

        };

        me.consultarNroOperacion = function(data, event){
            $input = $(event.target);
            if (event.keyCode != 13) {
                return true;
            }
            else {
                if ($.trim(me.nroOperacion()).length > 0) {
                    $.ajax({
                        type: "GET",
                        url: path + "/api/v1/consultarNroOperacion",
                        data: {nroOperacion: $.trim(me.nroOperacion())},
                        dataType: "json",
                        contentType: "application/json; charset=utf-8",
                        success: function (data) {
                            var rawPago = data.pago;
                            if (rawPago) {
                                me.montoPago(rawPago.detop_monto);
                                me.fechaOperacion(me.formatDateFromDatabase(rawPago.detop_fecha));
                                $("#montoPago").prop("disabled", true);
                                $("#dtpFechaOperacion").prop("disabled", true);
                            }
                            else {
                                me.montoPago("");
                                me.fechaOperacion("");
                                $("#montoPago").prop("disabled", false);
                                $("#dtpFechaOperacion").prop("disabled", false);
                            }
                        },
                        error: function (data) {
                            console.log('error');
                            console.log(data);
                        }
                    });
                }
            }
        };

        me.consultarNroOperacionButton = function(){
            if($.trim(me.nroOperacion()).length > 0){
                $.ajax({
                    type: "GET",
                    url: path + "/api/v1/consultarNroOperacion",
                    data: { nroOperacion: $.trim(me.nroOperacion())},
                    dataType: "json",
                    contentType: "application/json; charset=utf-8",
                    success: function (data) {
                        var rawPago = data.pago;
                        if(rawPago){
                            me.montoPago(rawPago.detop_monto);
                            me.fechaOperacion(me.formatDateFromDatabase(rawPago.detop_fecha));
                            $("#montoPago").prop("disabled", true);
                            $("#dtpFechaOperacion").prop("disabled", true);
                        }
                        else{
                            me.montoPago("");
                            me.fechaOperacion("");
                            $("#montoPago").prop("disabled", false);
                            $("#dtpFechaOperacion").prop("disabled", false);
                        }
                    },
                    error: function (data) {
                        console.log('error');
                        console.log(data);
                    }
                });
            }
        };

        me.validateParticipante = function (options) {
            options = $.extend({ valid: function(){}, invalid: function(){} }, (options || {}));

            //me.supressValidationMessages(false);

            if(me.isEmailSupplied() && me.isEmailValid() && me.isDniSupplied() && me.isDniValid() && me.isNombresSupplied() && me.isApePaternoSupplied() && me.isApeMaternoSupplied()) {
                return true;
            }
            else {
                return false;
            }
        };

        me.validatePago = function(options){
            options = $.extend({ valid: function(){}, invalid: function(){} }, (options || {}));

            if(me.nroOperacionSupplied() && me.fechaOperacionSupplied() && me.montoPagoSupplied()){
                return true;
            }
            else{
                return false;
            }
        };

        me.validate = function (options) {
            options = $.extend({valid: function(){}, invalid: function(){} }, (options || {}));

            me.supressValidationMessages(false);

            if(/*me.validatePago() &&*/ me.validateCurso() && me.validateParticipante() && me.isEmailValid()) {//me.archivoSupplied()){
                options.valid();
            }
            else{
                options.invalid();
            }
        };

        me.onSaveRegistroButtonClick = function(){
            me.validate({
                valid: function () {
                    $("#loadingClock").show();
                    $("#btnRegistrar").prop("disabled",true);
                    me.isSavingLoading(true);

                    var registroRaw = {
                        nroOperacion: $.trim(me.nroOperacion()),
                        fechaOperacion: $.trim(me.fechaOperacion()),
                        //archivo: "/upload/" + me.archivo(),
                        montoPago: $.trim(me.montoPago()),
                        fechaProgramacion: $.trim(me.fechaProgramacion()),
                        turnoId: $.trim(me.turnoId()),
                        selectedOperadoresIds: me.selectedOperadoresIds(),
                        email: $.trim(me.email()),
                        dni: $.trim(me.dni()),
                        nombres: $.trim(me.nombres()),
                        ape_paterno: $.trim(me.ape_paterno()),
                        ape_materno: $.trim(me.ape_materno())
                    };
                    //subimos primero la imagen
                    //me.uploadVoucher();

                    $.ajax({
                        type: "POST",
                        url: path + "/api/v1/guardarRegistroNatural",
                        data: {registro: registroRaw},
                        dataType: "json",
                        success: function (data) {
                            //mensaje de exito
                            $("#loadingClock").hide();
                            $("#btnRegistrar").prop("disabled",false);
                            if (data.resultado) {
                                me.sendConfirmationEmail(registroRaw);
                                window.location="{{URL::to('registro/confirmacion')}}";
                            }
                            else{
                                var validationArray = data.validation;
                                var validationParticipante = data.validationParticipante;
                                me.validationRegistros.removeAll();
                                me.validationParticipante.removeAll();

                                if(validationArray.length > 0) {
                                    for (var i = 0; i < validationArray.length; i++) {
                                        me.validationRegistros.push(validationArray[i]);
                                    }
                                    $("#registroValidations").show();
                                }

                                if(validationParticipante.length > 0){
                                    for (var i = 0; i < validationParticipante.length; i++) {
                                        me.validationParticipante.push(validationParticipante[i]);
                                    }
                                    $("#registroValidationsParticipantes").show();
                                }


                            }
                        },
                        error: function (data) {
                            console.log('error');
                            console.log(data);
                        }
                    });

                    me.isSavingLoading(false);
                },
                invalid: function () {

                }
            });

        };

        me.sendConfirmationEmail = function (registroRaw) {
            registroRaw.tipoPeronsa = "N";
            $.ajax({
                type: "POST",
                url: path + "/sendConfirmationMail",
                data: registroRaw,
                dataType: "json",
                cache: false,
                success: function (data) {
                    console.log(data);
                },
                error: function (data) {
                    console.log(data);
                    console.log("error ;(");
                }
            });
        };

        return {
            nroOperacion: me.nroOperacion,
            nroOperacionSupplied: me.nroOperacionSupplied,
            fechaOperacion: me.fechaOperacion,
            fechaOperacionSupplied: me.fechaOperacionSupplied,
            archivo: me.archivo,
            archivoSupplied: me.archivoSupplied,
            montoPagoo: me.montoPago,
            montoPagoSupplied: me.montoPagoSupplied,
            operadores: me.operadores,
            initialize: me.initialize,
            isSavingLoading: me.isSavingLoading,
            //Curso
            fechaProgramacion: me.fechaProgramacion,
            onFechaProgramacionChange: me.onFechaProgramacionChange,
            turnoId: me.turnoId,
            selectedOperadoresIds: me.selectedOperadoresIds,
            isFechaProgramacionSupplied: me.isFechaProgramacionSupplied,
            isTurnoSupplied: me.isTurnoSupplied,
            isOperadorSupplied: me.isOperadorSupplied,
            //Participante
            email: me.email,
            dni: me.dni,
            nombres: me.nombres,
            ape_paterno: me.ape_paterno,
            ape_materno: me.ape_materno,
            isEmailValid: me.isEmailValid,
            isEmailSupplied: me.isEmailSupplied,
            isDniSupplied: me.isDniSupplied,
            isDniValid: me.isDniValid,
            isNombresSupplied: me.isNombresSupplied,
            isApePaternoSupplied: me.isApePaternoSupplied,
            isApeMaternoSupplied: me.isApeMaternoSupplied,
            supressValidationMessages: me.supressValidationMessages,
            consultarDNI: me.consultarDNI,
            consultarDNIButton: me.consultarDNIButton,
            consultarNroOperacion: me.consultarNroOperacion,
            consultarNroOperacionButton: me.consultarNroOperacionButton,
            validationRegistros: me.validationRegistros,
            validationParticipante : me.validationParticipante
        }
    }();

    $(function(){

        RegistroViewModel.initialize();

    });
</script>
