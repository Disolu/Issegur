<script>
    var AgregarTurnoViewModel = new (function () {
        var me = this;

        me.hasbeenInitialized = false;
        me.currentFecha = ko.observable(null);
        me.currentDia = ko.observable(null);

        me.initialize = function () {
            if(!me.hasbeenInitialized){

                ko.applyBindings(AgregarTurnoViewModel, $("#agregarTurnoDialog")[0]);
                me.hasbeenInitialized = true;
            }
            me.cleanModalDialog();
        };

        me.cleanModalDialog = function () {
            $("#errores").hide();
            $("#iniHoras").val("");
            $("#iniMinutos").val("");
            $("#finHoras").val("");
            $("#finMinutos").val("");
        };

        me.setSelectedFecha = function (fecha, diaNombre) {
            var fechaRaw = new Date(fecha);
            fechaRaw.setDate(fechaRaw.getDate() + 1);
            var dia = (fechaRaw.getDate());
            var formattedDia = dia.toString().length == 1? '0'+ dia : dia;
            var month = (fechaRaw.getMonth() + 1);
            var formattedMonth = month.toString().length == 1? '0'+ month : month;
            var fechaFormatted = formattedDia + "/" + formattedMonth + "/" + fechaRaw.getFullYear();
            me.currentFecha(fechaFormatted);
            me.currentDia(diaNombre);
        };

        me.validate = function (options) {
            var iniHoras = $("#iniHoras").val();
            var iniMinutos = $("#iniMinutos").val();
            var finHoras = $("#finHoras").val();
            var finMinutos = $("#finMinutos").val();
            if(iniHoras.length > 0 && iniMinutos.length > 0 && finHoras.length > 0 && finMinutos.length > 0 ) {
                options.valid();
            }
            else{
                options.invalid();
            }
        };

        me.agregarTurno = function () {
            me.validate({
                valid: function () {
                    var newTurno = {
                        dia: me.currentDia(),
                        hora_inicio: $("#iniHoras").val() + ":" + $("#iniMinutos").val(),
                        hora_fin: $("#finHoras").val() + ":" + $("#finMinutos").val(),
                        fecha_unica: me.currentFecha()
                    };

                    $.ajax({
                        type: "GET",
                        url: path + "/api/v1/agregarTurnoManual",
                        data: {turno: newTurno},
                        contentType: "application/json; charset=utf-8",
                        dataType: "json",
                        success: function (data) {
                            toastr.success('Sus cambios fueron registrados con éxito','Turno Agregado');
                            $("#agregarTurnoDialog").modal("hide");
                            $(".actualizarParticipantes").trigger("click");
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
            });
        };

        return {
            initialize: me.initialize,
            setSelectedFecha: me.setSelectedFecha,
            currentFecha: me.currentFecha,
            agregarTurno: me.agregarTurno
        }
    });

    var CalendarViewModel = function () {
        var me = this;

        me.currentdayNumber = ko.observable(0);
        me.currentdayName = ko.observable(null);
        me.currentweekDayNumber = ko.observable(null);
        me.currentMonthName = ko.observable(null);
        me.currentdayNameLong = ko.observable(null);
        me.matchingDates = ko.observableArray([]);
        me.currentdatNameShort = "";
        me.currentTurnosArray = ko.observableArray([]);
        me.lastUpdateDate = ko.observable(null);
        me.currentSelectedDate = ko.observable(null);
        me.loadingTurnos = ko.observable(false);

        me.weekFrom = "";


        me.initialize = function () {
            // apply the knockout bindings
            $(document.body).on("click", "label.calendario-dia-block", me.onDayButtonClick);
            $(document.body).on("click", "#btnPrevious", me.onPreviousButtonClick);
            $(document.body).on("click", "#btnToday", me.onTodayButtonClick);
            $(document.body).on("click", "#btnNext", me.onNextButtonClick);
            $(document.body).on("click", "#btnAgregarTurno", me.onAgregarTurnoButtonClick);
            $(document.body).on("click", ".actualizarParticipantes", me.onActualizarParticipantesClick);
            $(document.body).on("click", ".participantesLink", me.onParticipantesLinkButtonClick);
            $(document.body).on("keydown", ".soloNumeros", me.soloNumeros);


            $('.default-date-picker').datepicker({
                format: 'mm/dd/yyyy'
            });

            $('.default-date-picker').datepicker().on('changeDate', function () {
                var fromDate = new Date($("#datepickerValue").val());
                var tmpDate = new Date($("#datepickerValue").val());
                fromDate.setDate(fromDate.getDate() - (fromDate.getDay() - 1));
                me.initializeWeek(fromDate, (tmpDate.getDay() - 1), $("#datepickerValue").val());
            });

            ko.applyBindings(CalendarViewModel, $("#calendar")[0]);

            me.datePickerSettings();

            if (Cookies.get("fromDate") && Cookies.get("fromParticipantes")) {
                me.initializeWeek(new Date(Cookies.get("fromDate")), Cookies.get("dayNumber") - 1);
                Cookies.remove('fromParticipantes');
            }
            else{
                me.initializeWeek();
            }

        };

        me.initializeWeek = function (fromDate , ini, noDate) {
            //we check if there is a value passing, if not , it means we are loading the page for the first time.
            if (fromDate) {
                Cookies("fromDate", fromDate);
                me.matchingDates.removeAll();
                for (var i = 0; i < 6; i++) {
                    var date = {
                        dayNumber: fromDate.getDate(),
                        dayWeekNumber: fromDate.getDay(),
                        shortDayName: $.datepicker.formatDate('D', fromDate).substring(0,2),
                        dayName: $.datepicker.formatDate('DD', fromDate),
                        monthName: $.datepicker.formatDate('M', fromDate),
                        dateRaw: (fromDate.getMonth() + 1) + '/' + fromDate.getDate() + '/' +  fromDate.getFullYear()
                    }
                    fromDate.setDate(fromDate.getDate() + 1);
                    me.matchingDates.push(date);
                }
                if (me.matchingDates()[ini]) {
                    me.setCurrentDayNameButton(me.matchingDates()[ini].shortDayName);
                }
                else{
                    me.setCurrentDayNameButton("Do", noDate);
                }
            }
            else {
                var currentDate = new Date(); // get current date
                var fromCurrentDate = new Date(currentDate);
                fromCurrentDate.setDate(currentDate.getDate() - (currentDate.getDay() - 1));
                var toCurrentDate =  new Date(fromCurrentDate);
                Cookies("fromDate", toCurrentDate);
                me.matchingDates.removeAll();
                for (var i = 0; i < 6; i++) {
                    var date = {
                        dayNumber: toCurrentDate.getDate(),
                        dayWeekNumber: toCurrentDate.getDay(),
                        shortDayName: $.datepicker.formatDate('D', toCurrentDate).substring(0,2),
                        dayName: $.datepicker.formatDate('DD', toCurrentDate),
                        monthName: $.datepicker.formatDate('M', toCurrentDate),
                        dateRaw: (toCurrentDate.getMonth() + 1) + '/' + toCurrentDate.getDate() + '/' +  toCurrentDate.getFullYear()
                    };
                    toCurrentDate.setDate(toCurrentDate.getDate() + 1);
                    me.matchingDates.push(date);
                }

                var currentDayNameShort = $.datepicker.formatDate('D', currentDate).substring(0,2);
                me.setCurrentDayNameButton(currentDayNameShort);
            }

        };

        me.setCurrentDayNameButton = function (dayName, currentDate) {
            //we clean all the active buttons first
            $('label.calendario-dia-block').each(function () {
                $(this).removeClass('active');
            });
            //then we set the current day with the corresponding button
            var $spanDayName = $("span.calendario-dia:contains('" + dayName + "')");
            if ($spanDayName.length > 0) {
                $spanDayName.closest('label').addClass('active');
                $spanDayName.closest('label').trigger("click");
            }
            else {
                var noday = new Date(currentDate || new Date());
                me.currentdayNumber(noday.getDate());
                me.currentweekDayNumber(noday.getDay());
                me.currentdayNameLong('Domingo');
                me.currentMonthName($.datepicker.formatDate('M', noday));
            }
        };

        me.onDayButtonClick = function (event) {
            var $target = $(event.target);
            if ($target.is('span')) {
                $target = $target.siblings().eq(0);
            }
            else{
                $target = $target.find('input');
            }

            me.currentdayNumber($target.prop("id"));
            me.currentweekDayNumber($target.data('id'));
            me.currentdayNameLong($target.data('dayname'));
            me.currentMonthName($target.data('monthname'));

            Cookies("dayNumber", me.currentweekDayNumber());

            //we set the quantity of participans per turno
            var dateRaw = $target.data('dateraw');
            var dateFormatted = new Date(dateRaw);
            var dia = (dateFormatted.getDate());
            var formattedDia = dia.toString().length == 1? '0'+ dia : dia;
            var month = (dateFormatted.getMonth() + 1);
            var formattedMonth = month.toString().length == 1? '0'+ month : month;
            var dateFormattedRaw = dateFormatted.getFullYear() + "-" + formattedMonth + "-" + formattedDia;
            me.currentSelectedDate(dateFormattedRaw);
            me.getParticipantesQuantityByTurno(dateFormattedRaw, me.currentdayNameLong());

            //we finally set the datepicker to have the current selected date
            $(".default-date-picker").datepicker("update", dateRaw);
        };

        me.onNextButtonClick = function () {
            var currentId = me.currentweekDayNumber();
            var datesRawList = me.matchingDates();
            if (currentId < datesRawList.length) {
                me.setCurrentDayNameButton(datesRawList[currentId].shortDayName);
            }
            else{
                var fromDate = new Date(datesRawList[5].dateRaw);
                fromDate.setDate(fromDate.getDate() + 2);
                var toDate = new Date(fromDate);
                toDate.setDate(toDate.getDate() + 5);
                me.initializeWeek(fromDate, 0);
            }
        };

        me.onTodayButtonClick = function () {
            var today =  new Date();
            //we first search if today's date exists on the current dates array
            var match = ko.utils.arrayFirst(me.matchingDates(), function(item) {
                return item.dateRaw === (today.getMonth() + 1) + '/' + today.getDate() + '/' +  today.getFullYear();
            });

            if (match) {
                var today =  new Date();
                me.setCurrentDayNameButton($.datepicker.formatDate('D', today).substring(0,2))
            }
            else{
                me.initializeWeek();
            }
        };

        me.onPreviousButtonClick = function () {
            var currentId = me.currentweekDayNumber();
            var datesRawList = me.matchingDates();
            if (currentId - 2 >= 0) {
                me.setCurrentDayNameButton(datesRawList[currentId - 2].shortDayName);
            }
            else{
                var toDate = new Date(datesRawList[0].dateRaw);
                toDate.setDate(toDate.getDate() - 2);
                var fromDate = new Date(toDate);
                fromDate.setDate(toDate.getDate() - 5);
                me.initializeWeek(fromDate, 5);
            }
        };

        me.onAgregarTurnoButtonClick = function () {
            AgregarTurnoViewModel.initialize();
            AgregarTurnoViewModel.setSelectedFecha(me.currentSelectedDate(), me.currentdayNameLong());
            $("#agregarTurnoDialog").modal("show");
        };

        me.onParticipantesLinkButtonClick = function (e) {
            var turno = ko.dataFor($(e.target)[0]).Turno;
            var turnoId = ko.dataFor($(e.target)[0]).TurnoId;
            //var fechaRaw =  new Date(me.currentSelectedDate());
            //fechaRaw.setDate(fechaRaw.getDate() + 1);
            var fechaRaw = me.currentSelectedDate().split("-");
            var day = fechaRaw[2];
            var month = fechaRaw[1];
            var year = fechaRaw[0];
            var fecha = day + "/" +  month + "/" + year;
            //setiamos las cookies para recuperarlas en la pantalla de Participantes
            Cookies("turno", turno);
            Cookies("turnoId", turnoId);
            Cookies("fecha", fecha);
            Cookies("fechaRaw", me.currentSelectedDate());
            //redireccionamos a participantes
            location.href = path + "/intranet/participantes";
        };

        me.onActualizarParticipantesClick = function () {
            me.getParticipantesQuantityByTurno(me.currentSelectedDate(), me.currentdayNameLong());
        };

        me.getParticipantesQuantityByTurno = function (fecha, nombreDia) {
            me.currentTurnosArray.removeAll();
            me.loadingTurnos(true);
            $.ajax({
                type: "GET",
                url: path + "/api/v1/obtenerParticipantesPorFechaYDia",
                data: {fecha: fecha , dia: nombreDia},
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function (data) {
                    turnosRaw = data.result;
                    me.loadingTurnos(false);
                    for (var i = 0; i < turnosRaw.length; i++) {
                        me.currentTurnosArray.push(turnosRaw[i]);
                    }
                    var today =  new Date();
                    var todayTime = me.formatAMPM(today);
                    me.lastUpdateDate(today.getDate() + "/" + (today.getMonth() + 1) + "/" + today.getFullYear() + " " +  todayTime);

                },
                error: function (data) {
                    console.log(data);
                    console.log("error :(");
                }
            });
        };

        me.formatAMPM = function(date) {
            var hours = date.getHours();
            var minutes = date.getMinutes();
            var ampm = hours >= 12 ? 'pm' : 'am';
            hours = hours % 12;
            hours = hours ? hours : 12; // the hour '0' should be '12'
            minutes = minutes < 10 ? '0'+minutes : minutes;
            var strTime = hours + ':' + minutes + ' ' + ampm;
            return strTime;
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

        me.soloNumeros = function (e) { //e,evt,event
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
        };


        return{
            currentdayNumber: me.currentdayNumber,
            currentdayName: me.currentdayName,
            currentMonthName: me.currentMonthName,
            currentdayNameLong: me.currentdayNameLong,
            currentSelectedDate: me.currentSelectedDate,
            initialize: me.initialize,
            matchingDates: me.matchingDates,
            currentTurnosArray: me.currentTurnosArray,
            lastUpdateDate: me.lastUpdateDate,
            loadingTurnos: me.loadingTurnos
        };
    }();

    $(function(){
        CalendarViewModel.initialize();
    });
</script>