<script>
    function LoginViewModel() {
        var me = this;
        var path = GlobalParameters.appPath;
        me.username = ko.observable(null);
        me.password = ko.observable(null);

        me.authenticate = function () {
            $.ajax({
                type: "GET",
                url: path + "/api/v1/login",
                data: { username: me.username(), password: me.password()},
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function (data) {
                    if(data.resultado == false){
                        $("#loginFailedMessage").show();
                    }
                    else{
                        window.location="{{URL::to('intranet/calendario')}}";
                    }
                },
                error: function (data) {
                    console.log(data);
                    console.log(data.d);
                    console.log("error :(");
                }
            });
        }

        return{
            username: me.username,
            password: me.password,
            authenticate: me.authenticate
        };

    }

    //we create the viewModel
    var viewModel = new LoginViewModel();

    function Login(e){
        e.preventDefault();
        validateLoginForm({
            invalid: function () {
                e.preventDefault();
            },
            valid: function () {
                viewModel.authenticate();
            }
        });


    }
    $(function (){
        //event hanlders
        $("#btnLogin").on("click", Login);
        $("#username").on("keyup", onUsernameKeyup);
        $("#password").on("keyup", onPasswordKeyup);

        //bind the object
        ko.applyBindings(viewModel, $("#main")[0]);
    });

    var initialized = false;

    function onUsernameKeyup(e) {
        var keyCode = e.which || e.keyCode;
        if (keyCode != 9 && keyCode != 13 && keyCode != 16) {
            $("#loginFailedMessage").fadeOut();
            validateUsernameSupplied();
        }
    }

    function onPasswordKeyup(e) {
        var keyCode = e.which || e.keyCode;
        if (keyCode != 9 && keyCode != 13 && keyCode != 16) {
            $("#loginFailedMessage").fadeOut();
            validatePasswordSupplied();
        }
        else if(keyCode == 13) {
            $("#btnLogin").trigger("click");
        }
    }


    function validateLoginForm(options) {
        options = $.extend({ valid: function () { }, invalid: function () { } }, (options || {}));
        var isValid = true;
        var validateOptions = { invalid: function () { isValid = false; } };
        validateUsernameSupplied(validateOptions);
        validatePasswordSupplied(validateOptions);

        if (isValid) {
            options.valid();
        }
        else {
            options.invalid();
        }
    }

    function validateUsernameSupplied(options) {
        options = $.extend({ valid: function () { }, invalid: function () { } }, (options || {}));
        if ($.trim($("#username").val())) {
            $("#username").removeClass("error");
            $("#usernameRequiredMessage").fadeOut();
            options.valid();
        }
        else {
            $("#username").addClass("error");
            $("#usernameRequiredMessage").fadeIn();
            options.invalid();
        }
    }

    function validatePasswordSupplied(options) {
        options = $.extend({ valid: function () { }, invalid: function () { } }, (options || {}));
        if ($.trim($("#password").val())) {
            $("#password").removeClass("error");
            $("#passwordRequiredMessage").fadeOut();
            options.valid();
        }
        else {
            $("#password").addClass("error");
            $("#passwordRequiredMessage").fadeIn();
            options.invalid();
        }
    }

</script>