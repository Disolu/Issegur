<script>
    /**
     * Created by User on 15/11/2015.
     */
    var ObservableParticipante = function(){
        var me = this;

        me.id = 0;
        me.dni = ko.observable(null);
        me.nombres = ko.observable(null);
        me.ape_paterno = ko.observable(null);
        me.ape_materno = ko.observable(null);
        me.isDniSupplied = ko.computed(function() { return $.trim(me.dni()).length > 0; }, me);
        me.isDniValid =  ko.computed(function() { return $.trim(me.dni()).length == 8 || $.trim(me.dni()).length == 0; }, me);
        me.isNombresSupplied = ko.computed(function () { return $.trim(me.nombres()).length > 0;}, me);
        me.isApePaternoSupplied = ko.computed(function () { return $.trim(me.ape_paterno()).length > 0;}, me);
        me.isApeMaternoSupplied = ko.computed(function () { return $.trim(me.ape_materno()).length > 0;}, me);
        me.supressValidationMessages = ko.observable(true);

        me.clone = function () {
            var clone = new ObservableParticipante({
                id: me.id,
                dni: me.dni(),
                nombres: me.nombres(),
                ape_paterno: me.ape_paterno(),
                ape_materno: me.ape_materno()
            });

            return clone;
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

        me.validate = function (options) {
            options = $.extend({ valid: function(){}, invalid: function(){} }, (options || {}));

            me.supressValidationMessages(false);

            if (me.isDniSupplied() && me.isDniValid() && me.isNombresSupplied() && me.isApePaternoSupplied() && me.isApeMaternoSupplied()) {
                options.valid();
            }
            else {
                options.invalid();
            }
        };

        me.loadFromParticipante = function (rawParticipante) {

            if(rawParticipante){
                me.nombres(rawParticipante.pa_nombres);
                me.ape_paterno(rawParticipante.pa_apellido_paterno);
                me.ape_materno(rawParticipante.pa_apellido_materno);
            }

        };

        return {
            id: me.id,
            dni: me.dni,
            nombres: me.nombres,
            ape_paterno: me.ape_paterno,
            ape_materno: me.ape_materno,
            validate: me.validate,
            isDniSupplied: me.isDniSupplied,
            isDniValid: me.isDniValid,
            isNombresSupplied: me.isNombresSupplied,
            isApePaternoSupplied: me.isApePaternoSupplied,
            isApeMaternoSupplied: me.isApeMaternoSupplied,
            supressValidationMessages: me.supressValidationMessages,
            clone: me.clone,
            consultarDNI: me.consultarDNI,
            consultarDNIButton: me.consultarDNIButton
        }

    };

    var CreateGrupoModalViewModal = new (function() {
        var me = this;

        me.id = 0;
        me.hasBeenInitialized = false;
        me.fechaProgramacion = ko.observable("");
        me.turnoId = ko.observable(0);
        me.turnoText = "";
        me.selectedOperadoresIds = ko.observableArray([]);
        me.selectedOperadoresText = ko.observableArray([]);
        me.participantes = ko.observableArray([]);
        me.currentParticipantes = ko.observableArray([]);
        me.isFechaProgramacionSupplied = ko.computed(function() { return (me.fechaProgramacion() != null && $.trim(me.fechaProgramacion()).length > 0); }, me);
        me.isTurnoSupplied = ko.computed(function() { return (me.turnoId() != null && $.trim(me.turnoId()).length > 0); }, me);
        me.isOperadorSupplied = ko.computed(function() {return me.selectedOperadoresIds().length > 0}, me);
        me.areParticipantesLoading = ko.observable(false);
        me.supressValidationMessages = ko.observable(true);
        me.state = "";

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

        me.initialize = function(currentGrupo) {
            if(!me.hasBeenInitialized){

                //we initialize the participantes Array
                me.initializeParticipantesArray();

                $(".checkboxOp").on("click", function (e) {
                    e.stopPropagation();
                    var currentId = $(this).data("id");
                    var currentTextRaw = $(this)[0].id;
                    var currentText = currentTextRaw.substring(3,currentTextRaw.length);

                    if($(this).is(":checked")){
                        me.selectedOperadoresIds.removeAll();
                        me.selectedOperadoresText.removeAll();
                        me.selectedOperadoresIds.push(currentId);
                        me.selectedOperadoresText.push(currentText);
                    }
                    /*
                     else{
                     me.selectedOperadoresIds.remove(currentId);
                     me.selectedOperadoresText.remove(currentText);
                     }
                     */
                });

                ko.applyBindings(CreateGrupoModalViewModal, $("#grupoDialog")[0]);

                //mark as initialized
                me.hasBeenInitialized = true;
            };

            if(me.state == "add"){
                me.id = grupoIndex;
                //if we are adding , then we clean all the object
                me.cleanGrupoInformation();
            }
            else{
                me.loadFromGrupo(currentGrupo);
            }


            $("#grupoDialog").modal("show");
        };

        me.setState = function (state) {
            me.state = state;
        };

        me.setSelectedOperadores = function () {
            me.cleanCheckboxes();
            for (var i = 0; i < me.selectedOperadoresText().length; i++) {
                var chkSelector = 'chk' + me.selectedOperadoresText()[i];
                $("#"+ chkSelector).prop("checked", true);
            }
        };

        me.cleanGrupoInformation = function(){
            me.fechaProgramacion("");
            me.turnoId(0);
            me.supressValidationMessages(true);
            $(".checkboxOp").prop("checked", false);
            $("#horarios").prop("disabled", true);
            me.selectedOperadoresIds.removeAll();
            me.selectedOperadoresText.removeAll();
            me.participantes.removeAll();
            me.currentParticipantes.removeAll();

            me.initializeParticipantesArray();
        };

        me.initializeParticipantesArray = function(){
            var o = {
                nombres:'',
                ape_paterno: '',
                ape_materno: '',
                dni: ''
            };
            me.participantes.push(new ObservableParticipante(o));
        };

        me.areParticipantesSupplied = function(){
            //recorremos la lita de participantes y verificamos que no haya ningún objeto vacío
            var result = false;
            $(".paData").each(function () {
                if($.trim($(this).val()).length > 0){
                    result = true;
                }
            });
            if(!result){
                $("#paValidationData").show();

            }
            return result;
        };

        me.areParticipantesValid = function () {
            var IsValid = false;
            for (var i = 0;  i < me.participantes().length; ++i) {
                var current = me.participantes()[i];
                if ($.trim(current.dni()).length > 0) {
                    current.validate({
                        valid: function () {
                            IsValid = true;
                        },
                        invalid: function () {
                            IsValid = false;
                        }
                    });
                }
            }

            return IsValid;
        };

        me.filterParticipantes = function(){
            me.currentParticipantes.removeAll();
            for (var i = 0;  i < me.participantes().length; ++i) {
                var current = me.participantes()[i];
                if (current.dni() != null || $.trim(current.dni()).length > 0) {
                    me.currentParticipantes.push(current);
                }
            }
        };

        me.clone = function (){
            var clone =  new Object({
                id: me.id,
                fechaProgramacion: me.fechaProgramacion(),
                turnoId: me.turnoId(),
                turnoText: $("#horarios option:selected").text(),
                selectedOperadoresIds: ko.observableArray([]),
                selectedOperadoresText: ko.observableArray([]),
                currentParticipantes: ko.observableArray([]),
                participantes: ko.observableArray([])
            });
            clone.selectedOperadoresIds(me.selectedOperadoresIds.slice(0));
            clone.selectedOperadoresText(me.selectedOperadoresText.slice(0));
            me.filterParticipantes();
            clone.currentParticipantes(me.currentParticipantes.slice(0));
            clone.participantes(me.participantes.slice(0));

            return clone;
        };

        me.loadFromGrupo = function (currentGrupo) {
            me.id = currentGrupo.id;
            me.fechaProgramacion(currentGrupo.fechaProgramacion);

            ko.utils.triggerEvent($("#dtpFechaProgramacion")[0], "change");
            $("#horarios").val(currentGrupo.turnoId);

            me.turnoId(currentGrupo.turnoId);
            me.selectedOperadoresIds(currentGrupo.selectedOperadoresIds.slice(0));
            me.selectedOperadoresText(currentGrupo.selectedOperadoresText.slice(0));
            me.currentParticipantes(currentGrupo.currentParticipantes.slice(0));
            me.participantes(currentGrupo.currentParticipantes.slice(0));

            me.setSelectedOperadores();
        };

        me.validate = function(options){
            options = $.extend({valid: function(){}, invalid: function(){} }, (options || {}));

            me.supressValidationMessages(false);

            if(me.areParticipantesSupplied() && me.areParticipantesValid() && me.isFechaProgramacionSupplied() && me.isTurnoSupplied() && me.isOperadorSupplied()){
                options.valid();
            }
            else{
                options.invalid();
            }
        };

        me.guardarGrupo = function(data,event){
            me.validate({
                valid: function(){
                    event.preventDefault();
                    var grupoViewModel = ko.dataFor($("#grupoDialog")[0]);
                    grupoViewModel.id = me.id;

                    var indexOfGrupo = -1;
                    for (var i = 0; indexOfGrupo < 0 && i < RegistroViewModel.grupos().length; ++i) {
                        var grupo = RegistroViewModel.grupos()[i];
                        if (grupoViewModel.id == grupo.id) {
                            indexOfGrupo = i;
                        }
                    }
                    // was the index of the group found?
                    if (indexOfGrupo >= 0) {
                        // replace it with the new group
                        RegistroViewModel.grupos.splice(indexOfGrupo, 1, me.clone());
                    }
                    else {
                        // put it on the end
                        RegistroViewModel.grupos.push(me.clone());
                        grupoIndex++;
                    }

                    $("#grupoDialog").modal("hide");
                },
                invalid: function(){
                    //no hacer nada
                    event.preventDefault();
                }
            });

        };

        me.cleanCheckboxes = function () {
            $(".checkboxOp").prop("checked",false);
        };

        me.agregarParticipante = function(data,event){
            event.preventDefault();
            if(me.participantes().length < 10){
                var o = {
                    nombres: '',
                    ape_paterno: '',
                    ape_materno: '',
                    dni: ''
                }

                me.participantes.push(new ObservableParticipante(o));
            }
            else{
                $("#alertaPa").show();
                $(".mensajeErrorPa").text("No puede agregar más de 10 participantes por registro.");
            }
        };

        return {
            id: me.id,
            fechaProgramacion: me.fechaProgramacion,
            onFechaProgramacionChange: me.onFechaProgramacionChange,
            turnoId: me.turnoId,
            turnoText: me.turnoText,
            selectedOperadoresIds: me.selectedOperadoresIds,
            selectedOperadoresText: me.selectedOperadoresText,
            participantes: me.participantes,
            currentParticipantes: me.currentParticipantes,
            isFechaProgramacionSupplied: me.isFechaProgramacionSupplied,
            isTurnoSupplied: me.isTurnoSupplied,
            isOperadorSupplied: me.isOperadorSupplied,
            areParticipantesSupplied: me.areParticipantesSupplied,
            areParticipantesValid: me.areParticipantesValid,
            areParticipantesLoading: me.areParticipantesLoading,
            supressValidationMessages: me.supressValidationMessages,
            setState: me.setState,
            agregarParticipante: me.agregarParticipante,
            guardarGrupo: me.guardarGrupo,
            initialize: me.initialize
        }

    });

    var DeleteGrupoModalViewModel = new (function () {
        var me =  this;

        me.grupoToDeleteId = -1;
        me.hasBeenInitialized = false;

        me.initialize = function () {
            if(!me.hasBeenInitialized){

                ko.applyBindings(DeleteGrupoModalViewModel, $("#confirmDeleteGrupo")[0]);

                //mark as initialized
                me.hasBeenInitialized = true;
            };
        };

        me.setGrupoToDelete = function (grupoId) {
            me.grupoToDeleteId = grupoId;
        };

        me.deleteGrupo = function () {
            var grupoToDelete = {};
            if(me.grupoToDeleteId >= 0){
                for (var i = 0; i < RegistroViewModel.grupos().length; ++i) {
                    var grupo = RegistroViewModel.grupos()[i];
                    if (grupo.id == me.grupoToDeleteId) {
                        grupoToDelete = grupo;
                    }
                }
            }
            RegistroViewModel.grupos.remove(grupoToDelete);
            $("#confirmDeleteGrupo").modal("hide");
        };

        return {
            initialize: me.initialize,
            deleteGrupo: me.deleteGrupo,
            setGrupoToDelete: me.setGrupoToDelete
        };
    });

    var RegistroViewModel =  function (){
        var me = this;

        me.razonSocial = ko.observable(null);
        me.razonSocialSupplied = ko.computed(function() { return $.trim(me.razonSocial()).length > 0; }, me);
        me.ruc = ko.observable("");
        me.rucSupplied = ko.computed(function() { return $.trim(me.ruc()).length > 0; }, me);
        me.isRucValid = ko.computed(function() { return $.trim(me.ruc()).length == 11 || $.trim(me.ruc()).length == 0}, me);
        me.nroOperacion = ko.observable("");
        me.nroOperacionSupplied = ko.computed(function() { return $.trim(me.nroOperacion()).length > 0; }, me);
        me.fechaOperacion = ko.observable("");
        me.fechaOperacionSupplied =  ko.computed(function() { return (me.fechaOperacion() != null && $.trim(me.fechaOperacion()).length > 0); }, me);
        me.montoPago = ko.observable(null);
        me.montoPagoSupplied = ko.computed(function(){ return $.trim(me.montoPago()).length > 0}, me);
        me.archivo = ko.observable("");
        me.archivoSupplied = ko.computed(function() { return $.trim(me.archivo()).length > 0; }, me);
        me.soliNombre = ko.observable(null);
        me.soliNombreSupplied = ko.computed(function() { return $.trim(me.soliNombre()).length > 0; }, me);
        me.soliApellido = ko.observable(null);
        me.soliApellidoSupplied = ko.computed(function() { return $.trim(me.soliApellido()).length > 0; }, me);
        me.soliTelefono = ko.observable(null);
        me.soliTelefonoSupplied = ko.computed(function() { return $.trim(me.soliTelefono()).length > 0; }, me);
        me.soliEmail = ko.observable(null);
        me.soliEmailSupplied = ko.computed(function() { return $.trim(me.soliEmail()).length > 0; }, me);
        me.grupos = ko.observableArray([]);
        me.gruposSupplied = ko.computed(function() { return me.grupos().length > 0; }, me);
        me.formatGrupos = [];
        me.operadores = ko.observableArray([]);
        me.supressValidationMessages = ko.observable(true);
        me.validationRegistros = ko.observableArray([]);
        me.validationParticipante = ko.observableArray([]);
        me.isSavingLoading = ko.observable(false);

        me.initialize = function () {

            // apply the knockout bindings
            ko.applyBindings(RegistroViewModel, $("#registro")[0]);

            //setup the handlers
            $(document.body).on('keydown', '.padni' , soloNumeros);
            $(document.body).on('keydown', '.soloNumeros' ,soloNumeros);
            $(document.body).on('keypress', '.letras' ,soloLetras);
            $(document.body).on("click", "#createGrupo", me.onCreateRecognitionButtonClick);
            $(document.body).on('click','a[href=editGrupo]', me.onEditGrupoButtonClick);
            $(document.body).on('click','#eliminarGrupo', me.onDeleteGroupButtonClick);
            $(document.body).on('click',"#btnRegistrar", me.onSaveRegistroButtonClick);

            var unavailableDates = ["8-10-2015", "9-10-2015"];

            function unavailable(date) {
                dmy = date.getDate() + "-" + (date.getMonth() + 1) + "-" + date.getFullYear();
                if ($.inArray(dmy, unavailableDates) == -1) {
                    return [true, ""];
                } else {
                    return [false, "", "Unavailable"];
                }
            }

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
                me.archivo(this.value.replace(/C:\\fakepath\\/i, ''));
            });

            //load Operadores
            me.datePickerSettings();
            me.loadOperadores();
        };

        me.onCreateRecognitionButtonClick = function(evt){
            evt.preventDefault();
            //titulo del modal
            $("#titleModal").text("Nuevo Grupo");
            CreateGrupoModalViewModal.setState("add");
            CreateGrupoModalViewModal.initialize();
            //something to initalize here.

        };

        me.onEditGrupoButtonClick = function(evt){
            evt.preventDefault();

            //get the request group Id
            var grupoId = $(evt.target).closest('div').data("id");
            //now we get the actual object
            var matchingGrupo = me.getGrupoFromArray(grupoId);

            $("#titleModal").text("Editar Grupo");
            CreateGrupoModalViewModal.setState("edit");
            CreateGrupoModalViewModal.initialize(matchingGrupo);
        };

        me.onDeleteGroupButtonClick = function (evt) {
            evt.preventDefault();
            //get the request group id
            var grupoId = $(evt.target).closest('div').data("id");

            DeleteGrupoModalViewModel.setGrupoToDelete(grupoId);
            DeleteGrupoModalViewModel.initialize();
            $("#confirmDeleteGrupo").modal("show");
        };

        me.getGrupoFromArray = function (grupoid) {
            var grupo = ko.utils.arrayFirst(me.grupos(), function(g) {
                return g.id == grupoid;
            });

            return grupo;
        };

        me.isEmailValid = function () {
            // http://stackoverflow.com/a/46181/11236
            var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(me.soliEmail());
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
                            $("#razonSocial").prop("disabled", true);
                        }
                        else{
                            me.razonSocial("");
                            $("#razonSocial").prop("disabled", false);
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
                                $("#razonSocial").prop("disabled", true);
                            }
                            else {
                                me.razonSocial("");
                                $("#razonSocial").prop("disabled", false);
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
                    data: { nroOperacion: me.nroOperacion()},
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
                    console.log(data);
                },
                error: function (data) {
                    console.log(data);
                    console.log("error ;(");
                }
            });
        };

        me.formatGruposToJSArray = function(){
            me.formatGrupos = [];
            for (var i = 0; i < me.grupos().length; i++) {
                var grupo = me.grupos()[i];
                var participantes =  me.grupos()[i].currentParticipantes();
                var formatParticipantes = [];
                for (var j = 0; j < participantes.length; j++) {
                    var participanteRaw = participantes[j];
                    var participante = {
                        dni: participanteRaw.dni(),
                        nombres: participanteRaw.nombres(),
                        ape_paterno: participanteRaw.ape_paterno(),
                        ape_materno: participanteRaw.ape_materno()
                    }
                    formatParticipantes.push(participante);
                }
                var newFormatGrupo = {
                    id: grupo.id,
                    fechaProgramacion: grupo.fechaProgramacion,
                    turnoId: grupo.turnoId,
                    selectedOperadoresIds: grupo.selectedOperadoresIds(),
                    selectedOperadoresText: grupo.selectedOperadoresText(),
                    participantes: formatParticipantes
                };
                me.formatGrupos.push(newFormatGrupo);
            }
        };

        me.validate = function (options) {
            options = $.extend({valid: function(){}, invalid: function(){} }, (options || {}));

            me.supressValidationMessages(false);

            if(me.isRucValid() && me.rucSupplied() && me.razonSocialSupplied() && me.nroOperacionSupplied() && me.fechaOperacionSupplied() && me.montoPagoSupplied() && //me.archivoSupplied() &&
                    me.soliNombreSupplied() && me.soliApellidoSupplied() && me.soliTelefonoSupplied() && me.soliEmailSupplied() && me.isEmailValid() && me.gruposSupplied()){
                options.valid();
            }
            else{
                options.invalid();
            }
        }

        me.onSaveRegistroButtonClick = function(){
            me.validate({
                valid: function () {
                    $("#loadingClock").show();
                    $("#btnRegistrar").prop("disabled",true);
                    me.isSavingLoading(true);

                    me.formatGruposToJSArray();

                    var registroRaw = {
                        ruc: $.trim(me.ruc()),
                        razonSocial: $.trim(me.razonSocial()),
                        nroOperacion: $.trim(me.nroOperacion()),
                        fechaOperacion: $.trim(me.fechaOperacion()),
                        //archivo: "/upload/" + me.archivo(),
                        montoPago: $.trim(me.montoPago()),
                        soliNombre: $.trim(me.soliNombre()),
                        soliApellido: $.trim(me.soliApellido()),
                        soliTelefono: $.trim(me.soliTelefono()),
                        soliEmail: $.trim(me.soliEmail()),
                        grupos: me.formatGrupos
                    };

                    //subimos primero la imagen
                    //me.uploadVoucher();
                    $.ajax({
                        type: "POST",
                        url: path + "/api/v1/guardarRegistroJuridica",
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
                                var validacionParticipante = data.validationParticipante;
                                me.validationRegistros.removeAll();
                                me.validationParticipante.removeAll();

                                if(validationArray.length > 0) {
                                    for (var i = 0; i < validationArray.length; i++) {
                                        me.validationRegistros.push(validationArray[i]);
                                    }
                                    $("#registroValidations").show();
                                }

                                if(validacionParticipante.length > 0){
                                    for (var i = 0; i < validacionParticipante.length; i++) {
                                        me.validationParticipante.push(validacionParticipante[i]);
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
            registroRaw.tipoPeronsa = "J";
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
            consultarRUC: me.consultarRUC,
            consultarRUCButton: me.consultarRUCButton,
            razonSocial: me.razonSocial,
            razonSocialSupplied: me.razonSocialSupplied,
            consultarNroOperacion: me.consultarNroOperacion,
            consultarNroOperacionButton: me.consultarNroOperacionButton,
            ruc: me.ruc,
            isRucValid: me.isRucValid,
            nroOperacion: me.nroOperacion,
            nroOperacionSupplied: me.nroOperacionSupplied,
            fechaOperacion: me.fechaOperacion,
            fechaOperacionSupplied: me.fechaOperacionSupplied,
            archivo: me.archivo,
            archivoSupplied: me.archivoSupplied,
            montoPago: me.montoPago,
            montoPagoSupplied: me.montoPagoSupplied,
            soliNombre: me.soliNombre,
            soliNombreSupplied: me.soliNombreSupplied,
            soliApellido: me.soliApellido,
            soliApellidoSupplied: me.soliApellidoSupplied,
            soliTelefono: me.soliTelefono,
            soliTelefonoSupplied: me.soliTelefonoSupplied,
            soliEmail: me.soliEmail,
            soliEmailSupplied: me.soliEmailSupplied,
            isEmailValid: me.isEmailValid,
            grupos: me.grupos,
            gruposSupplied: me.gruposSupplied,
            operadores: me.operadores,
            initialize: me.initialize,
            isSavingLoading: me.isSavingLoading,
            validationRegistros: me.validationRegistros,
            validationParticipante : me.validationParticipante
        }
    }();

    $(function(){

        RegistroViewModel.initialize();

    });

</script>