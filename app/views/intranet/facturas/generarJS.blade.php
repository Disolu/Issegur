<script>


    var GenerarFacturasViewModel = function () {
        var me = this;
        me.today = new Date();
        me.currentday =  me.today.getDate() + '/' + (me.today.getMonth() + 1)+ '/' + me.today.getFullYear();
        me.ruc = ko.observable();
        me.address = ko.observable();
        me.empresa = ko.observable();
        me.emp_id = ko.observable();
        me.viewfactura = ko.observable();

        me.cobj = {
            name: ko.observable(),
            id: ko.observable(),
            address: ko.observable(),
            ruc: ko.observable()
        };



        me.types = ko.observableArray([
            {name: 'Factura Exonerada',id:'1'},
            {name: 'No Exonerada',id:'0'}
        ]);

        me.type = ko.observable('1');

        var item = {};
        item.cant = ko.observable('');
        item.cant.subscribe(function(nV) {
            me.calculate();
        });
        item.price = ko.observable('');
        item.price.subscribe(function(nV) {
            me.calculate();
        });
        item.ptotal = ko.observable('');
        item.description = ko.observable('');
        item.voucher = ko.observable('');
        item.date = ko.observable('');

        me.items = ko.observableArray([item]);

        me.stotal = ko.observable();
        me.igv = ko.observable();
        me.total = ko.observable();
        me.letters = ko.observable();


        me.calculate = function(){
            var items = me.items().slice(0);
            var stotal = 0;
            for(var i = 0; i < items.length; i++){
                items[i].ptotal(items[i].cant() * items[i].price())
                stotal += parseInt(items[i].ptotal());
            }

            me.items([]);
            me.items(items);
            var igv = 0;
            me.stotal(stotal);
            if(me.type() == '1'){
                me.igv(0);
            }else{
                igv = stotal * 0.18;
                me.igv(igv);
            }

            me.total(stotal+igv);
        }

        me.type.subscribe(function(newValue) {
            me.calculate();
        });


        me.addline = function(){
            var item = {};
            item.cant = ko.observable('');
            item.cant.subscribe(function(nV) {
                me.calculate();
            });
            item.price = ko.observable('');
            item.price.subscribe(function(nV) {
                me.calculate();
            });
            item.ptotal = ko.observable('');
            item.description = ko.observable('');
            item.voucher = ko.observable('');
            item.date = ko.observable('');
            me.items.push(item);
        }

        me.initialize = function(){
            $(document.body).on("keydown", ".soloNumeros", me.soloNumeros);
            ko.applyBindings(me);
        }

        me.newcompany = function(){
            me.cobj.name('');
            me.cobj.id('');
            me.cobj.address('');
            me.cobj.ruc('');
            $('#editcreate').modal();
        }

        me.editcompany = function(){
            me.cobj.name(me.empresa());
            me.cobj.id(me.emp_id());
            me.cobj.address(me.address());
            me.cobj.ruc(me.ruc());
            $('#editcreate').modal();
        }


        me.preview = function(){
            var items = [];
            var titems  = me.items().slice(0);
            var citems = false;

            for(var i = 0; i < titems.length; i++){
                var titem = {
                    cant : titems[i].cant(),
                    price : titems[i].price(),
                    ptotal : titems[i].ptotal(),
                    description : titems[i].description(),
                    voucher : titems[i].voucher(),
                    date : titems[i].date()
                }
                items.push(titem);

                if(titem.cant == '' || titem.price == '' || titem.description == ''){
                    citems = true;
                }
            }

            var data = {
                ruc : me.ruc(),
                date : me.currentday,
                address : me.address(),
                empresa : me.empresa(),
                igv : me.igv(),
                total : me.total(),
                stotal : me.stotal(),
                letters : me.letters(),
                id : me.emp_id(),
                items : items
            }

            if(data.ruc == '' || !data.ruc ||
                data.address == '' || !data.address ||
                data.empresa == '' || !data.empresa ||
                data.letters == '' || !data.letters ||
                citems
            ){
                $("#modalerror").modal();
            }else{
                me.viewfactura(data);
                $('#pmodal').modal();
            }
        }

        me.print = function(){

            var items = [];
            var titems  = me.items().slice(0);

            for(var i = 0; i < titems.length; i++){
                var titem = {
                    cant : titems[i].cant(),
                    price : titems[i].price(),
                    ptotal : titems[i].ptotal(),
                    description : titems[i].description(),
                    voucher : titems[i].voucher(),
                    date : titems[i].date()
                }
                items.push(titem);
            }


            var data = {
                ruc : me.ruc(),
                date : me.currentday,
                address : me.address(),
                empresa : me.empresa(),
                igv : me.igv(),
                total : me.total(),
                stotal : me.stotal(),
                letters : me.letters(),
                id : me.emp_id(),
                items : items
            }

            $.ajax({
                type: "POST",
                url: path + "/api/v1/facturas/new",
                data: data,
                success: function (data) {
                    window.open(path+"/intranet/facturas/ver/"+data.factura.id);
                    window.location = path+"/intranet/facturas";
                },
                error: function (data) {
                    console.log(data);
                    console.log("error :(");
                }
            });
        }

        me.createedit = function(){
            var data = {
                name : me.cobj.name(),
                id : me.cobj.id(),
                address : me.cobj.address(),
                ruc : me.cobj.ruc()
            }

            if(data.ruc == '' || !data.ruc ||
                data.address == '' || !data.address ||
                data.name == '' || !data.name
            ){
                $("#modalerror").modal();
            }else{
                $.ajax({
                    type: "GET",
                    url: path + "/api/v1/facturas/createedit",
                    data: data,
                    contentType: "application/json; charset=utf-8",
                    dataType: "json",
                    success: function (data) {
                        if(data.empresa){
                            me.ruc(data.empresa.emp_ruc);
                            me.empresa(data.empresa.emp_razon_social);
                            me.address(data.empresa.emp_direccion);
                            me.emp_id(data.empresa.emp_id);
                        }
                    },
                    error: function (data) {
                        console.log(data);
                        console.log("error :(");
                    }
                });
                $('#editcreate').modal('hide');
            }
        }

        me.loadbyruc = function(data,e){
            if(e.keyCode == 13){
                var ruc = me.ruc();
                $.ajax({
                    type: "GET",
                    url: path + "/api/v1/facturas/loadbyruc",
                    data: {ruc: ruc},
                    contentType: "application/json; charset=utf-8",
                    dataType: "json",
                    success: function (data) {
                        if(data.empresa){
                            me.empresa(data.empresa.emp_razon_social);
                            me.address(data.empresa.emp_direccion);
                            me.emp_id(data.empresa.emp_id);
                        }
                    },
                    error: function (data) {
                        console.log(data);
                        console.log("error :(");
                    }
                });
            }
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
        GenerarFacturasViewModel.initialize();
    });
</script>
