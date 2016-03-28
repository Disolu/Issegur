<script>


    var ConsultarFacturasViewModel = function () {
        var me = this;
        me.ruc = ko.observable();
        me.empresa = ko.observable();
        me.facturas = ko.observableArray();

        me.search = function(){
            var data = {
                ruc : ruc(),
                empresa : empresa(),
            }

            $.ajax({
                type: "GET",
                url: path + "/api/v1/facturas/search",
                data: data,
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function (data) {
                    me.facturas.removeAll();
                    for(var i = 0 ; i < data.facturas.length; i++){
                        me.facturas.push(data.facturas[i]);
                    }
                },
                error: function (data) {
                    console.log(data);
                    console.log("error :(");
                }
            });
        }

        me.initialize = function(){
            $(document.body).on("keydown", ".soloNumeros", me.soloNumeros);
            ko.applyBindings(me);
        }


        me.soloNumeros = function (e) {
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
            initialize: me.initialize
        };
    }();

    $(function () {
        ConsultarFacturasViewModel.initialize();
    });
</script>
