<script>
	var reportViewModel = function(){
		var me = this;

		me.ruc = ko.observable(null);
		me.razonSocial = ko.observable(null);
		me.empresas = ko.observableArray([]);
		me.solicitantes = ko.observableArray([]);

		me.IsValid = function(){
			var valid = false;

			if($.trim(me.ruc()).length > 0 && $.trim($("#razonSocialText").val()).length > 0){
				valid = true;
			}

			return valid;
		};

		me.initialize = function(){
			//eventos
			$(document.body).on("click",".searchOption", me.onSearchOptionChange);
			$(document.body).on('keydown', '.soloNumeros' ,me.soloNumeros);
			//cargamos las empresas para el autcomplete
			me.getEmpresasNombresAutocomplete();
			//ponemos el foco en el textox de razonSocial
			$("#razonSocialText").focus();
			ko.applyBindings(reportViewModel, $("#reporteParticipantesEmpresa")[0]);
		};

		me.onSearchOptionChange = function(e){
			//we get the label jQuery control
			var $target = $(e.target);
			//we get the javascript checkbox inside that label
			var checkbox = $target.find("input[type='radio']")[0];

			if (checkbox.id == "chkEmpresa") 
			{				
				$("#rucText").hide();
				//limpiamos los textboxes antes de mostrarlos 
				$("#razonSocialText").val('');
				$("#razonSocialText").show();
				$("#razonSocialText").focus();
			}
			else
			{
				$("#razonSocialText").hide();
				//limpiamos el textbox antes de mostrarlo
				$("#rucText").val('');
				$("#rucText").show();
				$("#rucText").focus();	
			}
		}

		me.onBuscarButtonClick = function(data,event){
			var radio = $(".searchOption.active").find("input[type='radio']")[0];

			if (radio.id == "chkEmpresa") {
				me.getEmpresaPorRazonSocial();
			}
			else{

			}
			
		}

		me.getEmpresasNombresAutocomplete = function(){
			$.ajax({
                type: "GET",
                url: path + "/api/v1/obtenerEmpresasNombresAutocomplete",
                dataType: "json",
                contentType: "application/json; charset=utf-8",
                success: function (data) {                    
                var empresas = data.empresasAutocomplete;
                $('#razonSocialText').typeahead({source: empresas});

                },
                error: function (data) {
                    console.log(data);
                    console.log("error ;(");
                }
            });
		}

		me.getEmpresaPorRazonSocial = function(){
			$.ajax({
                type: "GET",
                url: path + "/api/v1/obtenerEmpresasPorRazonSocial",
                data: { razonSocial: me.razonSocial()},
                dataType: "json",
                contentType: "application/json; charset=utf-8",
                success: function (data) {                    
                var empresa = data.matchingEmpresa;
                var solicitantes = data.solicitantes;
                me.setSolicitantesArray(solicitantes);
                console.log(me.solicitantes());
            	},
                error: function (data) {
                    console.log(data);
                    console.log("error ;(");
                }
            });
		}

		me.setSolicitantesArray = function(solicitantesArray){
			for (var i = 0; i < solicitantesArray.length; i++) {
				if (i == 0) {
					var sol = {
						soliNombre: solicitantesArray[i].regsoli_Nombre + ' ' + solicitantesArray[i].regsoli_Apellidos,
						soliTelefono: solicitantesArray[i].regsoli_Telefono,
						soliEmail: solicitantesArray[i].regsoli_Email
					}

					me.solicitantes.push(sol);
				}
				else {
					//verificamos si ya existen el telefono y el email
					var matchingTelefono = me.findInArray(solicitantes(), 'soliTelefono', solicitantesArray[i].regsoli_Telefono);
					var matchingEmail = me.findInArray(solicitantes(), 'soliEmail', solicitantesArray[i].regsoli_Email);
					var sol = {};
					var IsAnyValue = false;
					
					if (!matchingTelefono) {
						sol.soliTelefono = solicitantesArray[i].regsoli_Telefono;
						IsAnyValue = true;
					}
					if (!matchingEmail) {
						sol.soliEmail = solicitantesArray[i].regsoli_Email;
						IsAnyValue = true;
					}

					//si se agrego algun valor , agregamos al array de solicitantes
					if (IsAnyValue) {
						me.solicitantes.push(sol);
					}
				};
				
			};
		}

		me.findInArray = function(observableArray, propertyName, valueToFind){
			var match = ko.utils.arrayFirst(observableArray, function(item) {
			    return valueToFind === item[propertyName];
			});

			return match;
		}

		//para permitir solo numeros en las casillas numéricas
        me.soloNumeros = function (e) {
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


		return {
			ruc: me.ruc,
			razonSocial: me.razonSocial,
			IsValid: me.IsValid,
			initialize: me.initialize,
			onBuscarButtonClick: me.onBuscarButtonClick
		}
	}();

	$(function(){
		reportViewModel.initialize();
	});
</script>