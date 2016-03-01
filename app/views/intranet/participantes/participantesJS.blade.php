    <script>
    var ObservableRegistroParticipante = new (function () {
        var me = this;

        me.tipoRegistro = ko.observable(null);
        me.ruc = ko.observable("");
        me.IsRucValid = ko.computed(function(){ return me.ruc().length == 11 || me.ruc().length == 0}, me);
        me.razonSocial = ko.observable("");
        me.detalleOperacionId = ko.observable(0);
        me.nroOperacion = ko.observable("");
        me.monto = ko.observable("");
        me.almacen = ko.observable(0);
        me.dni = ko.observable("");
        me.IsDniValid = ko.computed(function(){ return me.dni().length == 8 || me.dni().length == 0}, me);
        me.nombres = ko.observable("");
        me.ape_paterno = ko.observable("");
        me.ape_materno = ko.observable("");
        me.operador = ko.observable("");
        me.operadores = ko.observableArray([]);
        me.operadoresSeleccionados = ko.observableArray([]);
        me.supressValidationMessages = ko.observable(true);
        me.validationRegistros = ko.observableArray([]);
        me.hasBeenInitialized = false;

        me.initialize = function () {
            if(!me.hasBeenInitialized){
                $(document.body).on("keydown", ".soloNumeros", me.soloNumeros);
                $(document.body).on("click", "#btnRegistroJuridica", me.onRegistroJuridicaClick);
                $(document.body).on("click", "#btnRegistroNatural", me.onRegistroNaturalClick);
                $(document.body).on("click", ".regAlmacen", me.onSelectOperadorClick);

                //cargamos losm operadores
                me.loadOperadores();

                ko.applyBindings(ObservableRegistroParticipante, $("#IngresoParticipantesDialog")[0]);
                me.hasBeenInitialized = true;
            }

            me.cleanInfo();
            me.tipoRegistro("J");
            $(".regRUC").focus();
        };

        me.onRegistroJuridicaClick = function (e) {
            me.tipoRegistro("J");
            $("#ruc").show();
            $("#razonSocial").show();
        };

        me.onRegistroNaturalClick = function(e){
            me.tipoRegistro("N");
            me.ruc("");
            me.razonSocial("");
            $(".paRazonSocial").attr("disabled", true);

            $("#ruc").hide();
            $("#razonSocial").hide();
        };

        me.validate = function (options) {
            options = $.extend({valid: function(){}, invalid: function(){} }, (options || {}));

            me.supressValidationMessages(false);

            if(me.tipoRegistro() == "J"){
                if ((me.ruc().length > 0) && (me.IsRucValid()) && (me.razonSocial().toString().length > 0) && (me.nroOperacion().toString().length > 0) &&
                        (me.monto().toString().length > 0) && (me.operador().toString().length > 0) && (me.dni().toString().length > 0) && (me.IsDniValid()) && (me.nombres().length > 0) && (me.ape_paterno().length > 0) && (me.ape_materno().length > 0)) {
                    options.valid();
                }
                else{
                    options.invalid();
                }
            }
            else{
                if ((me.nroOperacion().toString().length > 0) && (me.monto().toString().length > 0) && (me.operador().toString().length > 0) && (me.dni().toString().length > 0) &&
                        (me.IsDniValid()) && (me.nombres().length > 0) && (me.ape_paterno().length > 0) && (me.ape_materno().length > 0)) {
                    options.valid();
                }
                else{
                    options.invalid();
                }
            }
        };

        me.parseObservableArray = function (observableArray) {
            var parsedArray = [];
            for (var i = 0; i < observableArray.length; i++) {
                parsedArray.push(observableArray[i]);
            }
            return parsedArray;
        };

        me.registrarParticipante = function () {
            me.validate({
                valid: function () {
                    var participanteModel = {};
                    if(me.tipoRegistro() == "J"){
                        var participanteRaw = {
                            tipoRegistro: me.tipoRegistro(),
                            fecha: Cookies.get("fechaRaw"),
                            turno: Cookies.get("turnoId"),
                            ruc: me.ruc(),
                            razonSocial: me.razonSocial(),
                            nroOperacion: me.nroOperacion(),
                            monto: me.monto(),
                            //almacenes: me.parseObservableArray(me.operadoresSeleccionados()),
                            almacen: me.operador(),
                            dni: me.dni(),
                            nombres: me.nombres(),
                            ape_paterno: me.ape_paterno(),
                            ape_materno: me.ape_materno()
                        };
                        participanteModel = participanteRaw;
                    }
                    else{
                        var participanteRaw = {
                            tipoRegistro: me.tipoRegistro(),
                            fecha: Cookies.get("fechaRaw"),
                            turno: Cookies.get("turnoId"),
                            nroOperacion: me.nroOperacion(),
                            monto: me.monto(),
                            //almacenes: me.operadoresSeleccionados(),
                            almacen: me.operador(),
                            dni: me.dni(),
                            nombres: me.nombres(),
                            ape_paterno: me.ape_paterno(),
                            ape_materno: me.ape_materno()
                        }
                        participanteModel = participanteRaw;
                    }

                    console.log(participanteModel);

                    $.ajax({
                        type: "GET",
                        url: path + "/api/v1/registarParticipanteManual",
                        data: {participante: participanteModel},
                        contentType: "application/json; charset=utf-8",
                        dataType: "json",
                        success: function (data) {
                            var result = data.resultado;
                            if(result){
                                toastr.success('Sus cambios fueron registrados con éxito','Participante Guardado');
                                //redirect them
                                //setTimeout(function () { window.location =  path + "/intranet/participantes"; }, 1000);
                                var turno = Cookies.get('turno');
                                var fechaRaw = Cookies.get('fechaRaw');
                                $("#IngresoParticipantesDialog").modal("hide");
                                ParticipantesViewModel.getParticipantesPorTurnoyFecha(fechaRaw,turno);
                            }
                            else{
                                var validationArray = data.validation;
                                me.validationRegistros.removeAll();
                                for (var i = 0; i < validationArray.length; i++) {
                                    me.validationRegistros.push(validationArray[i]);
                                }
                                $("#registroValidations").show();
                            }

                        },
                        error: function (data) {
                            console.log(data);
                            console.log("error :(");
                        }
                    });

                    $("#erroresParticipante").hide();
                },
                invalid: function () {
                    $("#erroresParticipante").show();
                }
            });
        };

        me.onSelectOperadorClick = function (e) {
            var $input = $(e.target);
            var $target = $input.children("input[name='opAlmacenes']");
            if(!$input.hasClass('active')){
                //me.operadoresSeleccionados.remove(parseInt($target[0].id));
                me.operador($target[0].id);
            }
            //else{
            //me.operadoresSeleccionados.push(parseInt($target[0].id));
            //}
        };

        me.consultarRUCButton = function () {
            if($.trim(me.ruc()).length > 0){
                $.ajax({
                    type: "GET",
                    url: path + "/api/v1/consultarRUC",
                    data: { ruc: me.ruc()},
                    dataType: "json",
                    contentType: "application/json; charset=utf-8",
                    success: function (data) {
                        var rawEmpresa = data.empresa;
                        if(rawEmpresa){
                            me.razonSocial(rawEmpresa.emp_razon_social);
                            $(".regRazonSocial").prop("disabled", true);
                        }
                        else{
                            me.razonSocial("");
                            $(".regRazonSocial").prop("disabled", false);
                        }
                    },
                    error: function (data) {
                        console.log('error');
                        console.log(data);
                    }
                });
            }
        };

        me.consultarRUC = function (data, event) {
            $input = $(event.target);
            if (event.keyCode != 13) {
                return true;
            }
            else {
                if ($.trim(me.ruc()).length > 0) {
                    $.ajax({
                        type: "GET",
                        url: path + "/api/v1/consultarRUC",
                        data: {ruc: me.ruc()},
                        dataType: "json",
                        contentType: "application/json; charset=utf-8",
                        success: function (data) {
                            var rawEmpresa = data.empresa;
                            if (rawEmpresa) {
                                me.razonSocial(rawEmpresa.emp_razon_social);
                                $(".regRazonSocial").prop("disabled", true);
                            }
                            else {
                                me.razonSocial("");
                                $(".regRazonSocial").prop("disabled", false);
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
                        data: {nroOperacion: me.nroOperacion()},
                        dataType: "json",
                        contentType: "application/json; charset=utf-8",
                        success: function (data) {
                            var rawPago = data.pago;
                            if (rawPago) {
                                me.monto(rawPago.detop_monto);
                                $(".regMonto").prop("disabled", true);
                            }
                            else {
                                me.monto("");
                                $(".regMonto").prop("disabled", false);
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
                    data: { nroOperacion: me.nroOperacion()},
                    dataType: "json",
                    contentType: "application/json; charset=utf-8",
                    success: function (data) {
                        var rawPago = data.pago;
                        if(rawPago){
                            me.monto(rawPago.detop_monto);
                            $(".regMonto").prop("disabled", true);
                        }
                        else{
                            me.monto("");
                            $(".regMonto").prop("disabled", false);
                        }
                    },
                    error: function (data) {
                        console.log('error');
                        console.log(data);
                    }
                });
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
                            $input.parents('#participante').find('.paData').prop("disabled",true);
                        }
                        else{
                            me.nombres('');
                            me.ape_paterno('');
                            me.ape_materno('');
                            $input.parents('#participante').find('.paData').prop("disabled",false);
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
            if ($input.hasClass('regDNI')) {
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
                                    $input.parents('#participante').find('.paData').prop("disabled",true);
                                }
                                else{
                                    me.nombres('');
                                    me.ape_paterno('');
                                    me.ape_materno('');
                                    $input.parents('#participante').find('.paData').prop("disabled",false);
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

        me.loadFromParticipante = function (rawParticipante) {

            if(rawParticipante){
                me.nombres(rawParticipante.pa_nombres);
                me.ape_paterno(rawParticipante.pa_apellido_paterno);
                me.ape_materno(rawParticipante.pa_apellido_materno);
            }

        };

        me.cleanInfo = function(){
            me.ruc("");
            me.razonSocial("");
            me.nroOperacion("");
            me.monto("");
            me.operadoresSeleccionados.removeAll();
            me.dni("");
            me.nombres("");
            me.ape_paterno("");
            me.ape_materno("");

            $(".regAlmacen").removeClass("active");
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
            var $divOperadores = $("#almacen");
            var operadoresContent = "";
            //recorremos la lista de operadores
            for (var i = 0; i < me.operadores().length; i++) {
                var operador = me.operadores()[i].op_nombre;
                var operadorId = me.operadores()[i].op_id;
                var operadoresContent = operadoresContent + "<label class='btn btn-default regAlmacen'> <input type='radio' name='opAlmacenes' id='" + operadorId + "' autocomplete='off'>" + operador + "</label>";

            }
            $divOperadores.html(operadoresContent);

            $(".regRUC").focus();
        };

        me.soloNumeros = function (e) { //e,evt,event
            if($(e.target).hasClass("paData") && $.trim($(e.target).val()).length > 0){
                $("#paValidationData").hide();
            }

            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                        // Allow: Ctrl+A
                    (e.keyCode == 65 && e.ctrlKey === true) ||
                        // Allow: Ctrl+C
                    (e.keyCode == 67 && e.ctrlKey === true) ||
                        // Allow: Ctrl+V
                    (e.keyCode == 86 && e.ctrlKey === true) ||
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
        };

        return {
            initialize: me.initialize,
            tipoRegistro: me.tipoRegistro,
            ruc: me.ruc,
            IsRucValid: me.IsRucValid,
            razonSocial: me.razonSocial,
            nroOperacion: me.nroOperacion,
            monto: me.monto,
            almacen: me.almacen,
            dni: me.dni,
            IsDniValid: me.IsDniValid,
            nombres: me.nombres,
            ape_paterno: me.ape_paterno,
            ape_materno: me.ape_materno,
            consultarRUC: me.consultarRUC,
            consultarRUCButton: me.consultarRUCButton,
            consultarNroOperacion: me.consultarNroOperacion,
            consultarNroOperacionButton: me.consultarNroOperacionButton,
            consultarDNI: me.consultarDNI,
            consultarDNIButton: me.consultarDNIButton,
            registrarParticipante: me.registrarParticipante,
            validationRegistros: me.validationRegistros,
            supressValidationMessages: me.supressValidationMessages
        };

    });

    var DetalleOperacionViewModel = new (function () {
        var me = this;

        me.fecha = ko.observable("");
//            me.horas = ko.observable("");
//            me.minutos = ko.observable("");
//            me.apm = ko.observable(null);
        me.monto = ko.observable(null);
        me.tipoPago = ko.observable(null);
        me.nroOperacion = ko.observable(null);
        me.detOperacionId = ko.observable(null);
        me.cantidadParticipantes = ko.observable(null);
        me.hasbeenInitialized = false;

        me.initialize = function () {
            if (!me.hasbeenInitialized) {
                $(document.body).on("keydown", ".soloNumeros", me.soloNumeros);

                $("#fechaNroOperacion").datepicker({
                    format: 'dd/mm/yyyy'
                });

                ko.applyBindings(DetalleOperacionViewModel, $("#detalleOperacionDialog")[0]);
                me.hasbeenInitialized = true;
            }
            $("#errores").hide();
            me.obtenerDetalleOperacionPorRegistroId();
        };

        me.setNroOperacion = function (nroOperacion, detOperacionId) {
            me.nroOperacion(nroOperacion);
            me.detOperacionId(detOperacionId);
        };

        me.obtenerDetalleOperacionPorRegistroId = function () {
            console.log(me.detOperacionId());
            $.ajax({
                type: "GET",
                url: path + "/api/v1/obtenerDetalleOperacionPorRegistro",
                data: {detOperacionId: me.detOperacionId()},
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function (data) {
                    var detalleRaw = data.result;
                    me.loadFromDetalleInfo(detalleRaw);

                },
                error: function (data) {
                    console.log(data);
                    console.log("error :(");
                }
            });
        };

        me.loadFromDetalleInfo = function (detalleRaw) {
            if (detalleRaw) {
                me.fecha(detalleRaw.fecha);
//                    me.horas(detalleRaw.horas);
//                    me.minutos(detalleRaw.minutos);
//                    me.apm(detalleRaw.apm);
                me.monto(detalleRaw.monto);
                me.cantidadParticipantes(detalleRaw.cantidadParticipantes);
                me.tipoPago(detalleRaw.tipoPago ? detalleRaw.tipoPago : "D");
            }
        };

        me.validate = function (options) {
            options = $.extend({valid: function(){}, invalid: function(){} }, (options || {}));

            me.fecha($("#fechaNroOperacion").val());
            if ((me.fecha().length > 0)  && (me.monto().toString().length > 0)) { //(me.horas()) && (me.minutos())
                options.valid();
            }
            else{
                options.invalid();
            }

        };

        me.guardarDetalleOperacion = function () {
            me.validate({
                valid: function () {
                    var detalle = {
                        detOperacionId: me.detOperacionId(),
                        nroOperacion: me.nroOperacion(),
                        fecha: me.fecha(),
//                            horas: me.horas(),
//                            minutos: me.minutos(),
//                            apm: me.apm(),
                        monto: me.monto(),
                        tipoPago: me.tipoPago()
                    };

                    $.ajax({
                        type: "GET",
                        url: path + "/api/v1/guardarDetalleOperacionPorRegistro",
                        data: {detalle: detalle},
                        contentType: "application/json; charset=utf-8",
                        dataType: "json",
                        success: function (data) {
                            $("#detalleOperacionDialog").modal('hide');
                        },
                        error: function (data) {
                            console.log(data);
                            console.log("error :(");
                        }
                    });
                    $("#errores").hide();
                },
                invalid: function () {
                    $("#errores").show();
                }
            })
        };

        me.soloNumeros = function (e) { //e,evt,event
            if($(e.target).hasClass("paData") && $.trim($(e.target).val()).length > 0){
                $("#paValidationData").hide();
            }

            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                        // Allow: Ctrl+A
                    (e.keyCode == 65 && e.ctrlKey === true) ||
                        // Allow: Ctrl+C
                    (e.keyCode == 67 && e.ctrlKey === true) ||
                        // Allow: Ctrl+V
                    (e.keyCode == 86 && e.ctrlKey === true) ||
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
        };

        return{
            fecha: me.fecha,
            horas: me.horas,
            minutos: me.minutos,
            apm: me.apm,
            monto: me.monto,
            nroOperacion: me.nroOperacion,
            tipoPago: me.tipoPago,
            detOperacionId: me.detOperacionId,
            guardarDetalleOperacion: me.guardarDetalleOperacion,
            setNroOperacion: me.setNroOperacion,
            cantidadParticipantes: me.cantidadParticipantes,
            initialize: me.initialize
        }

    });

    var ParticipantesViewModel = function () {
        var me = this;

        me.currentFecha = ko.observable(null);
        me.currentFechaRaw = ko.observable(null);
        me.currentTurno = ko.observable(null);
        me.participantes = ko.observableArray([]);
        me.loadingParticipantes = ko.observable(false);
        me.currentPhoto = ko.observable(null);
        me.fichaAsistenciaNombre = ko.observable(null);
        me.participantesSavedArray = [];
        me.guardardandoParticipantes = ko.observable(false);

        me.initialize = function () {
            $(document.body).tooltip({selector: '[data-toggle=tooltip]'});
            $('.hey').multiselect();

            $(document.body).on("keydown", ".paNota", me.soloNumeros);
            $(document.body).on("keydown", ".paNroOperacionTextBox", me.soloNumeros);
            $(document.body).on("keydown", ".paDNITextbox", me.soloNumeros);
            $(document.body).on("keydown", ".paMontoOperacionTextbox", me.soloNumeros);
            $(document.body).on('keydown', '#filtroPersonal' , me.search);

            $(document.body).on("click", "#subirFichaAsistencia", me.onUploadFichaAsistenciaClick);
            $(document.body).on("change", ".uploadFichaAsistenciaHidden", me.onSelectedFichaAsistenciaFile);
            $(document.body).on("click", ".paUploadPhoto", me.onUploadPhotoClick);
            $(document.body).on("click", ".paPhoto", me.onShowPreviewPhotoClick);
            $(document.body).on("change", ".uploadPhotoHidden", me.onSelectedPhotoFile);
            $(document.body).on("click", ".paUploadExamen", me.onUploadExamenClick);
            $(document.body).on("change", ".uploadExamenHidden", me.onSelectedExamenFile);
            $(document.body).on("click", ".paAsis", me.onAsistenciaClick);
            $(document.body).on("dblclick", ".tdNroOperacion", me.onEditNroOperacionClick);
            $(document.body).on("keydown", ".paNroOperacionTextbox", me.onUpdateNroOperacionClick);
            $(document.body).on("dblclick", ".tdDni", me.onEditDNIClick);
            $(document.body).on("keydown", ".paDNITextbox", me.onUpdateDNIClick);
            $(document.body).on("dblclick", ".tdNombres", me.onEditNombresClick);
            $(document.body).on("keydown", ".paNombresTextbox", me.onUpdateNombresClick);
            $(document.body).on("dblclick", ".tdApePaterno", me.onEditApePaternoClick);
            $(document.body).on("keydown", ".paApePaternoTextbox", me.onUpdateApePaternoClick);
            $(document.body).on("dblclick", ".tdApeMaterno", me.onEditApeMaternoClick);
            $(document.body).on("keydown", ".paApeMaternoTextbox", me.onUpdateApeMaternoClick);
            $(document.body).on("dblclick", ".tdRazonSocial", me.onEditRazonSocialClick);
            $(document.body).on("keydown", ".paRazonSocialTextbox", me.onUpdateRazonSocialClick);
            $(document.body).on("dblclick", ".tdRUC", me.onEditRucClick);
            $(document.body).on("keydown", ".paRUCTextbox", me.onUpdateRucClick);
            $(document.body).on("dblclick", ".tdFechaOperacion", me.onEditFechaOperacionClick);
            $(document.body).on("keydown", ".paFechaOperacionTextbox", me.onUpdateFechaOperacionClick);
            $(document.body).on("dblclick", ".tdMontoOperacion", me.onEditMontoOperacionClick);
            $(document.body).on("keydown", ".paMontoOperacionTextbox", me.onUpdateMontoOperacionClick);

            $(document.body).on("click", "#btnGuardarParticipantes", me.onGuardarParticipantesClick);
            $(document.body).on("click", "#btnGuardarParticipantesBottom", me.onGuardarParticipantesClick);            
            $(document.body).on("click", "#btnRegresarACalendario", me.onRegresarClick);
            $(document.body).on("click", "#btnGenerarFicha", me.onGenerarFichaButtonClick);
            $(document.body).on("click", "#btnAgregarParticipantes", me.onAgregarParticipantesClick);


            var fecha = Cookies.get('fecha');
            var turno = Cookies.get('turno');
            var fechaRaw = Cookies.get('fechaRaw');
            //if there are values...
            if (fecha && turno && fechaRaw) {
                //setiamos los valores de fecha y Turno
                me.currentFecha(fecha);
                me.currentTurno(turno);
                me.currentFechaRaw(fechaRaw);

                me.getParticipantesPorTurnoyFecha(me.currentFechaRaw(), me.currentTurno());
            }
            else {
            }
            ko.applyBindings(ParticipantesViewModel, $("#detalleParticipantes")[0]);
        };

        me.onRegistroJuridicaClick = function (e) {

        };

        me.onRegistroNaturalClick = function (e) {
            $(".regRazonSocial").val("");
        };

        me.search = function (e) {
            var inputValue = e.keyCode;

            //si se presiona enter
            if(inputValue == 13) {
                me.loadingParticipantes(true);
                $.ajax({
                    type: "GET",
                    url: path + "/api/v1/obtenerParticipantesPorFechaYTurno",
                    data: {fecha: me.currentFechaRaw(), turno:  me.currentTurno(), searchText: $("#filtroPersonal").val()},
                    contentType: "application/json; charset=utf-8",
                    dataType: "json",
                    success: function (data) {
                        var turnosRaw = data.result;
                        me.loadingParticipantes(false);
                        me.participantes.removeAll();
                        for (var i = 0; i < turnosRaw.length; i++) {
                            me.participantes.push(turnosRaw[i]);
                        }
                    },
                    error: function (data) {
                        console.log(data);
                        console.log("error :(");
                    }
                });
            }
        };

        me.getParticipantesPorTurnoyFecha = function (fecha, turno, searchText) {
            me.loadingParticipantes(true);
            if (!searchText) {
                searchText = "";
            }
            $.ajax({
                type: "GET",
                url: path + "/api/v1/obtenerParticipantesPorFechaYTurno",
                data: {fecha: fecha, turno: turno, searchText: searchText},
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function (data) {
                    var turnosRaw = data.result;
                    me.loadingParticipantes(false);
                    me.participantes.removeAll();
                    for (var i = 0; i < turnosRaw.length; i++) {
                        me.participantes.push(turnosRaw[i]);
                    }
                    me.fichaAsistenciaNombre(me.participantes()[0].pa_ficha_asistencia.replace("fichas/",""));
                },
                error: function (data) {
                    console.log(data);
                    console.log("error :(");
                }
            });
        };

        me.onUploadFichaAsistenciaClick = function (e) {
            $('.uploadFichaAsistenciaHidden').eq(0).trigger("click");
        };

        me.onSelectedFichaAsistenciaFile = function (e) {
            var $target = $(e.target);
            if ($target.val()) {
                var nombreArchivoArray = $target.val().split('\\');
                $("#fileTextFichaAsistencia").text(nombreArchivoArray[2]);
                $(".uploadFichaAsistenciaHidden").attr("data-url", nombreArchivoArray[2]);
            }
        };

        me.onUploadExamenClick = function (e) {
            var $target = $(e.target);
            $target.parents('td').find('.uploadExamenHidden').eq(0).trigger("click");
        };

        me.onSelectedExamenFile = function (e) {
            var $target = $(e.target);
            if ($target[0].files[0].name) {
                var nombreArchivoArray = $target.val().split('\\');
                $(e.target).parents('form').siblings('.examenClip').show();
                $(e.target).parents('form').siblings('.examenClip').children('.examenClipIcon').addClass('fa fa-paperclip');
                $(e.target).parents('form').siblings('.examenClip').attr('title', nombreArchivoArray[2]);
                $(e.target).parents('form').find('.uploadExamenHidden').attr('data-url', "../examenes/" + nombreArchivoArray[2]);
                $(e.target).parents('td').find('.paExamen').attr('href',"../examenes/" + nombreArchivoArray[2]);
                //$(e.target).parents('form').find('.uploadExamenHidden').attr('data-url', nombreArchivoArray[2]);
            }

        };

        me.onUploadPhotoClick = function (e) {
            //desencadenamos el click en el otro input file
            var $target = $(e.target);
            $target.parents('td').find('.uploadPhotoHidden').eq(0).trigger("click");
        };

        me.onShowPreviewPhotoClick = function (e) {
            var $target = $(e.target);
            var foto = $target.parents('td').attr("data-id");
            var fotoLocal =$target.parents('td').attr("data-file");
            var $inputFile = $target.parents('td').find('.uploadPhotoHidden').eq(0);
            if(foto && fotoLocal){
                me.currentPhoto("../fotos/" + $inputFile[0].files[0].name);
            }
            else if (foto && !fotoLocal){
                me.currentPhoto("../" + foto);
            }
            else{
                me.currentPhoto("../fotos/" + $inputFile[0].files[0].name);
            }
            $("#previewPhotoModal").modal("show");
        };

        me.onSelectedPhotoFile = function (e) {
            var $target = $(e.target);
            if ($target[0].files[0].name) {
                $(e.target).siblings('.photoClip').children('.photoClipIcon').addClass('fa fa-paperclip');
                $(e.target).siblings('.paPhoto').hide();
            }
            $target.parents('td').attr('data-file', $target[0].files[0].name);

            var reader = new FileReader();

            reader.onload = function (e) {
                $target.parents('td').find('.paImgPhoto')
                        .attr('src',e.target.result);
            };

            reader.readAsDataURL($target[0].files[0]);
        };

        me.onEditNroOperacionClick = function (e) {
            var $spanText = $(e.target).parents('td').children('.paNroOperacion');
            var $textBox = $(e.target).parents('td').children('.paNroOperacionTextbox');

            if ($spanText.length == 0 && $textBox.length == 0) {
                $spanText = $(e.target).children('.paNroOperacion');
                $textBox = $(e.target).children('.paNroOperacionTextbox');
            }

            var nroOperacionText = $spanText.text();
            $spanText.hide();

            $textBox.val(nroOperacionText);
            $textBox.css('display', 'inline');
        };

        me.onUpdateNroOperacionClick = function (e) {
            var inputValue = e.keyCode;
            if (inputValue == 13) {
                var $spanText = $(e.target).parents('td').children('.paNroOperacion');
                var $textBox = $(e.target).parents('td').children('.paNroOperacionTextbox');

                var newNroText = $textBox.val();
                $textBox.hide();

                $spanText.text(newNroText);
                $spanText.show();
            }
        };

        me.onDetalleOperacionClick = function (e){
            var nroOperacion = $(e.target).parents('td').children('.paNroOperacion').text();
            var detOperacionId = $(e.target).parents('td').data("id");
            DetalleOperacionViewModel.setNroOperacion(nroOperacion, detOperacionId);
            DetalleOperacionViewModel.initialize();
            $("#detalleOperacionDialog").modal('show');
        };

        me.onEditDNIClick = function (e) {
            var $spanText = $(e.target).parents('td').children('.paDNI');

            var $textBox = $(e.target).parents('td').children('.paDNITextbox');

            var DNIText = $spanText.text();
            $spanText.hide();

            $textBox.val(DNIText);
            $textBox.css('display', 'inline');
        };

        me.onUpdateDNIClick = function (e) {
            var inputValue = e.keyCode;
            if (inputValue == 13) {
                var $spanText = $(e.target).parents('td').children('.paDNI');

                var $textBox = $(e.target);

                var newDNIText = $textBox.val();

                $textBox.hide();

                $spanText.text(newDNIText);
                $spanText.show();
            }
        };

        me.onEditNombresClick = function (e) {
            var $spanText = $(e.target).parents('td').children('.paNombres');

            var $textBox = $(e.target).parents('td').children('.paNombresTextbox');

            var nombresText = $spanText.text();
            $spanText.hide();

            $textBox.val(nombresText);
            $textBox.css('display', 'inline');
        };

        me.onUpdateNombresClick = function (e) {
            var inputValue = e.keyCode;
            if (inputValue == 13) {
                var $spanText = $(e.target).parents('td').children('.paNombres');

                var $textBox = $(e.target).parents('td').children('.paNombresTextbox');

                var newNombresText = $textBox.val();

                $textBox.hide();

                $spanText.text(newNombresText);
                $spanText.show();
            }
        };

        me.onEditApePaternoClick = function (e) {
            var $spanText = $(e.target).parents('td').children('.paApePaterno');

            var $textBox = $(e.target).parents('td').children('.paApePaternoTextbox');

            var apePaternoText = $spanText.text();
            $spanText.hide();

            $textBox.val(apePaternoText);
            $textBox.css('display', 'inline');
        };

        me.onUpdateApePaternoClick = function (e) {
            var inputValue = e.keyCode;
            if (inputValue == 13) {
                var $spanText = $(e.target).parents('td').children('.paApePaterno');

                var $textBox = $(e.target).parents('td').children('.paApePaternoTextbox');

                var apePaternoText = $textBox.val();

                $textBox.hide();

                $spanText.text(apePaternoText);
                $spanText.show();
            }
        };

        me.onEditApeMaternoClick = function (e) {
            var $spanText = $(e.target).parents('td').children('.paApeMaterno');

            var $textBox = $(e.target).parents('td').children('.paApeMaternoTextbox');

            var apeMaternoText = $spanText.text();
            $spanText.hide();

            $textBox.val(apeMaternoText);
            $textBox.css('display', 'inline');
        };

        me.onUpdateApeMaternoClick = function (e) {
            var inputValue = e.keyCode;
            if (inputValue == 13) {
                var $spanText = $(e.target).parents('td').children('.paApeMaterno');

                var $textBox = $(e.target).parents('td').children('.paApeMaternoTextbox');

                var apeMaternoText = $textBox.val();
                $textBox.hide();

                $spanText.text(apeMaternoText);
                $spanText.show();
            }
        };

        me.onEditRazonSocialClick = function (e) {
            var $spanText = $(e.target).parents('td').children('.paRazonSocial');

            var $textBox = $(e.target).parents('td').children('.paRazonSocialTextbox');

            var razonSocialText = $spanText.text();
            $spanText.hide();

            $textBox.val(razonSocialText);
            $textBox.css('display', 'inline');
        };

        me.onUpdateRazonSocialClick = function (e) {
            var inputValue = e.keyCode;
            if (inputValue == 13) {
                var $spanText = $(e.target).parents('td').children('.paRazonSocial');

                var $textBox = $(e.target).parents('td').children('.paRazonSocialTextbox');

                var prevRazonSocialText = $spanText.text();
                var newRazonSocialText = $textBox.val();
                $(".participante").each(function () {
                    var razonSocialText = $(this).children('.tdRazonSocial').children('.paRazonSocial').text();
                    if (prevRazonSocialText == razonSocialText) {
                        $(this).children('.tdRazonSocial').children('.paRazonSocial').text(newRazonSocialText);
                    }
                });

                $textBox.hide();

                $spanText.text(newRazonSocialText);
                $spanText.show();
            }
        };

        me.onEditRucClick = function (e) {
            var $spanText = $(e.target).parents('td').children('.paRUC');
            var $textBox = $(e.target).parents('td').children('.paRUCTextbox');
            if ($spanText.length == 0 && $textBox.length == 0) {
                $spanText = $(e.target).children('.paRUC');
                $textBox = $(e.target).children('.paRUCTextbox');
            }

            var rucText = $spanText.text();
            $spanText.hide();

            $textBox.val(rucText);
            $textBox.css('display', 'inline');
        };

        me.onUpdateRucClick = function (e) {
            var inputValue = e.keyCode;
            if (inputValue == 13) {
                var $spanText = $(e.target).parents('td').children('.paRUC');
                var $textBox = $(e.target).parents('td').children('.paRUCTextbox');
                //razonSOcial
                var $razonSocialSpanText = $(e.target).parents('tr').children('.tdRazonSocial').children('.paRazonSocial');

                var rucText = $textBox.val();
                $textBox.hide();

                $spanText.text(rucText);
                $spanText.show();

                if ($.trim(rucText).length > 0) {
                    $.ajax({
                        type: "GET",
                        url: path + "/api/v1/consultarRUC",
                        data: {ruc: $.trim(rucText)},
                        dataType: "json",
                        contentType: "application/json; charset=utf-8",
                        success: function (data) {
                            var rawEmpresa = data.empresa;
                            if (rawEmpresa) {
                                $razonSocialSpanText.text(rawEmpresa.emp_razon_social.toUpperCase());
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

        me.onEditFechaOperacionClick = function (e) {
            var $spanText = $(e.target).parents('td').children('.paFechaOperacion');
            var $textBox = $(e.target).parents('td').children('.paFechaOperacionTextbox');

            if ($spanText.length == 0 && $textBox.length == 0) {
                $spanText = $(e.target).children('.paFechaOperacion');
                $textBox = $(e.target).children('.paFechaOperacionTextbox');
            }

            var fechaOperacionText = $spanText.text();
            $spanText.hide();

            $textBox.val(fechaOperacionText);
            $textBox.css('display', 'inline');
        };

        me.onUpdateFechaOperacionClick = function (e) {
            var inputValue = e.keyCode;
            if (inputValue == 13) {
                var $spanText = $(e.target).parents('td').children('.paFechaOperacion');
                var $textBox = $(e.target).parents('td').children('.paFechaOperacionTextbox');

                var fechaOperacionText = $textBox.val();
                $textBox.hide();

                $spanText.text(fechaOperacionText);
                $spanText.show();
            }
        };

        me.onEditMontoOperacionClick = function (e) {
            var $spanText = $(e.target).parents('td').children('.paMontoOperacion');
            var $textBox = $(e.target).parents('td').children('.paMontoOperacionTextbox');

            var montoOperacionTextbox = $spanText.text();
            $spanText.hide();

            $textBox.val(montoOperacionTextbox);
            $textBox.css('display', 'inline');
        };

        me.onUpdateMontoOperacionClick = function (e) {
            var inputValue = e.keyCode;
            if (inputValue == 13) {
                var $spanText = $(e.target).parents('td').children('.paMontoOperacion');
                var $textBox = $(e.target).parents('td').children('.paMontoOperacionTextbox');

                var montoOperacionTextbox = $textBox.val();
                $textBox.hide();

                $spanText.text(montoOperacionTextbox);
                $spanText.show();
            }
        };

        me.onRegresarClick = function () {
            Cookies("fromParticipantes", true);
            location.href = path + "/intranet/calendario";
        };

        me.onGuardarParticipantesClick = function (e) {
            //recorremos las filas
            var participantesArray = [];
            //setiamos el boton a Cargando..
            me.guardardandoParticipantes(true);

            //ficha asistencia
            var fichaAsistenciaNombre = $(".uploadFichaAsistenciaHidden").attr("data-url");
            var fichaAsistenciaUrl = "fichas/" + fichaAsistenciaNombre;
            var $fichaForm = $(".fichaAsistenciaForm");
            if (fichaAsistenciaNombre) {
                me.uploadFichaAsistencia($fichaForm);
            }


            $(".participante").each(function () {
                //asistencia
                var asistenciaInfo = null;
                var $activo = $(this).children('.tdAsistencia').find('.active');
                if ($activo.length > 0) {
                    asistenciaInfo = $activo.find('.textAsis').text()== "SI"? 1: 0;
                }
                //operador
                var operadorSelectedId = null;
                var $cboOperadores = $(this).children('.tdAlmacen').find(".paAlmacen");
                if($cboOperadores.length > 0){
                    var operadorSelectedId = $cboOperadores.val();
                }

                //foto
                var fotoInfo = null;
                var fotoFromDB =  $(this).children('.tdFoto').data("id");
                var fotoFromLocal = $(this).children('.tdFoto').attr("data-file");
                if(fotoFromLocal){
                    fotoInfo = "fotos/" + fotoFromLocal;
                    //subimos la foto si es que no hay una ya subida
                    //var $fotoForm = $(this).children('.tdFoto').find('.photoForm');
                    $(this).children('.tdFoto').find('.paImgPhoto').attr('src', jic.compress($(this).children('.tdFoto').find('.paImgPhoto')[0],50,'jpg').src);
                    //me.uploadPhotoParticipante($fotoForm);
                    var callback = function(response){ console.log(response); }
                    jic.upload($(this).children('.tdFoto').find('.paImgPhoto')[0], path + "/uploadParticipanteFoto", "photoImg", fotoFromLocal, callback);
                    //mostramos el icono de la camara y ocultamos el clip
                    $(this).children('.tdFoto').find('.photoClip').children('.photoClipIcon').removeClass('fa fa-paperclip');
                    $(this).children('.tdFoto').find('.paPhoto').show();
                }
                else{
                    fotoInfo = fotoFromDB;
                }

                //examen
                var examenFile = $(this).children('.tdExamen').find('.uploadExamenHidden').attr('data-url');
                console.log(examenFile);

                if (examenFile) {
                    // si es que recien se estan guardando...
                    /*if(examenFile.indexOf("examenes/") == -1){
                        examenUrl = "examenes/" + examenFile;    
                    }
                    else{
                        examenUrl = examenFile; 
                    } */               

                    var $examenForm = $(this).children('.tdExamen').find('.paExamenForm');

                    me.uploadExamenParticipante($examenForm);

                    //mostramos el icono de la camara y ocultamos el clip
                    $(this).children('.tdExamen').find('.examenClip').children('.examenClipIcon').removeClass('fa fa-paperclip');
                    $(this).children('.tdExamen').find('.paExamen').show();
                }
                
                
                var parObj = {
                    id: $(this).attr('data-id'),
                    dni: $(this).children('.tdDni').find('.paDNI').text(),
                    nombres: $(this).children('.tdNombres').find('.paNombres').text(),
                    apePaterno: $(this).children('.tdApePaterno').find('.paApePaterno').text(),
                    apeMaterno: $(this).children('.tdApeMaterno').find('.paApeMaterno').text(),
                    ruc: $(this).children('.tdRUC').find('.paRUC').text(),
                    razonSocial: $(this).children('.tdRazonSocial').find('.paRazonSocial').text(),
                    operador: operadorSelectedId,
                    asistencia: asistenciaInfo,
                    nota: $(this).children('.tdNota').children('.paNota').val()? $(this).children('.tdNota').children('.paNota').val() : null,
                    registroId: $(this).attr('data-reg-id'),
                    nroOperacion: $(this).children('.tdNroOperacion').children('.paNroOperacion').text(),
                    fechaOperacion: $(this).children('.tdFechaOperacion').children('.paFechaOperacion').text(),
                    montoOperacion: $(this).children('.tdMontoOperacion').children('.paMontoOperacion').text(),
                    foto: fotoInfo? fotoInfo : null,
                    ficha: fichaAsistenciaNombre ? fichaAsistenciaUrl : null,
                    examen: examenFile? examenFile : null
                };

                participantesArray.push(parObj);
            });


            $.ajax({
                type: "POST",
                async: false,
                url: path + "/api/v1/actualizarParticipantes",
                data: {participantes: participantesArray},
                dataType: "json",
                success: function (data) {

                },
                error: function (data) {
                    console.log(data);
                    console.log("error :(");
                }
            }).done(function(data){
                me.guardardandoParticipantes(false);
                toastr.success('Sus cambios fueron registrados con éxito','Participantes Guardados');
            });
        };

        me.uploadPhotoParticipante = function ($fotoForm) {
            var formData = new FormData($fotoForm[0]);
            if(formData != null){
                $.ajax({
                    type: "POST",
                    async: false,
                    url: path + "/uploadParticipanteFoto",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        console.log(data);
                    },
                    error: function (data) {
                        console.log(data);
                        console.log("error ;(");
                    }
                });
            }
            
        };

        me.uploadFichaAsistencia = function ($fichaAsistenciaForm) {
            var formData = new FormData($fichaAsistenciaForm[0]);
            if(formData != null){
                $.ajax({
                    type: "POST",
                    async: false,
                    url: path + "/uploadFichaAsistencia",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        console.log(data);
                    },
                    error: function (data) {
                        console.log(data);
                        console.log("error ;(");
                    }
                });
            }
        };

        me.uploadExamenParticipante = function ($examenForm) {
            var formData = new FormData($examenForm[0]);
            if(formData != null){
                $.ajax({
                    type: "POST",
                    async: false,
                    url: path + "/uploadExamenParticipante",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        console.log(data);
                    },
                    error: function (data) {
                        console.log(data);
                        console.log("error ;(");
                    }
                });
            }
        };

        me.onAsistenciaClick = function (e) {
            var $target = $(e.target);
            var asisText  = $target.text();
            if (asisText == 'No') {
                $target.parents('td').siblings('.tdNota').find('.paNota').prop('disabled','disabled');
                $target.parents('td').siblings('.tdNota').find('.paNota').val("");
                $target.parents('td').siblings('.tdFoto').find('.paUploadPhoto').prop('disabled','disabled');
            }
            else{
                $target.parents('td').siblings('.tdNota').find('.paNota').prop('disabled',false);
                $target.parents('td').siblings('.tdFoto').find('.paUploadPhoto').prop('disabled',false);
            }
        };

        me.onGenerarFichaButtonClick = function (e) {
            location.href = path + "/intranet/generarFichaExcel/" + me.currentTurno() + "/" + me.currentFechaRaw();
        };

        me.soloNumeros = function (e) { //e,evt,event
            if($(e.target).hasClass("paData") && $.trim($(e.target).val()).length > 0){
                $("#paValidationData").hide();
            }

            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                        // Allow: Ctrl+A
                    (e.keyCode == 65 && e.ctrlKey === true) ||
                        // Allow: Ctrl+C
                    (e.keyCode == 67 && e.ctrlKey === true) ||
                        // Allow: Ctrl+V
                    (e.keyCode == 86 && e.ctrlKey === true) ||
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
        };

        me.onAgregarParticipantesClick = function (e) {
            ObservableRegistroParticipante.initialize();
            $("#IngresoParticipantesDialog").modal("show");
        };

        return {
            initialize: me.initialize,
            participantes: me.participantes,
            currentFecha: me.currentFecha,
            currentTurno: me.currentTurno,
            loadingParticipantes: me.loadingParticipantes,
            getParticipantesPorTurnoyFecha: me.getParticipantesPorTurnoyFecha,
            guardardandoParticipantes: me.guardardandoParticipantes
        };
    }();

    $(function () {
        ParticipantesViewModel.initialize();
    });
</script>
