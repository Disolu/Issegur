
<script>


    var ConfigurarFacturasViewModel = function () {
        var me = this;
        me.serie = ko.observable({{$serie->serie}});
        me.number = ko.observable({{$serie->number}});

        me.initialize = function(){
            $(document.body).on("keydown", ".soloNumeros", me.soloNumeros);
            ko.applyBindings(me);
        }

        me.save = function(){
            var data = {
                serie : serie(),
                number : number(),
            }

            $.ajax({
                type: "GET",
                url: path + "/api/v1/facturas/config",
                data: data,
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function (data) {
                },
                error: function (data) {
                    console.log(data);
                    console.log("error :(");
                }
            });
        }

        return {
            initialize: me.initialize
        };
    }();

    $(function () {
        ConfigurarFacturasViewModel.initialize();
    });
</script>
