(function ($) {
    var options = {
        descriptor: "",
        validators: {
            mandatory: function($target) {
                if ($target.is(':hidden'))
                    return true;
                if ($target.is('input[type="radio"],input[type="checkbox"]'))
                    return $target.is(':checked');
                return $.trim($target.val()) != "";
            },
            numeric: function($target) {
                return $target.val().match(/^\d*$/) != null;
            },
            regex: function($target, params) {
                var expression = params["expression"];
                var flags = expression.replace(/.*\/([gimy]*)$/, '$1');
                var pattern = expression.replace(new RegExp('^/(.*?)/'+flags+'$'), '$1');
                var regex = new RegExp(pattern, flags);

                return $target.val().match(regex) != null;
            },
            email: function($target) {
                var pattern = /\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i;
                var regex = new RegExp(pattern);

                return $target.val().match(regex) != null;
            }
        }
    };
    
    var methods = {
        init: function(args) {
            if (args) {
                $.extend(true, options, args);
            }
            
            //init member vars
            var base = {};
            base.descriptor = options.descriptor;
            $(this).data("qeyValidator", base);
            
            $(this).find('form').submit(methods.submit);
            var fields = base.descriptor;
            
            for (var index in fields) {
                if (typeof fields[index]['validation'] === "undefined") {
                    continue;
                }
                
                var validation = fields[index]['validation'];
                var $target = methods.initField(index, validation);
                
                $target.keypress(function(){
                    methods.clearMessage($(this));
                });
                    
                if (typeof validation["type"] != "undefined") {
                    switch (validation["type"]) {
                        case "date":
                            $target.datepicker(
                                {
                                    showWeek: true,
                                    firstDay: 1,
                                    dateFormat: "yy-mm-dd" //ISO-8601 used by MySQL
                                }
                            );
                            break;
                            
                        default:
                            if (typeof options.validators[validation["type"]] != "undefined") {
                                $target.blur(function() {
                                    methods.validate($(this))
                                });
                            } else {
                                alert("Validator for '" + validation["type"] + "' was not provided and wasn't included");
                            }
                    }
                }
                
                if (validation["mandatory"] != null && validation["mandatory"] == true)
                    $target.blur(function() {
                        methods.validate($(this), "mandatory");
                    });
            }
        },
        
        initField: function(name, params) {
            var data = {};
            var $target = $('#row-' + name).find('input, textarea, select');
            data.errorFieldID = 'row-' + name + '-message-field';
            data.$errorField = $target.parent().find('#' + data.errorFieldID);
            if (data.$errorField.length == 0) {
                data.$errorField = $('<span>').attr('id', data.errorFieldID).addClass('form-validation-notification')
                    .appendTo($target.parent());
            }
            data.name = name;
            data.errorType = "ok";
            data.vData = params;
            $target.data("qeyValidationInputData", data);
            return $target;
        },
        
        numeric: function($target){
            $target.keydown(function(event) {
                var data = $(this).data("qeyValidationInputData");
                validation = data.vData;

                var key = event.charCode || event.keyCode || 0;
                if (!event.shiftKey && !event.ctrlKey && !event.altKey && (key == 8 || key == 9 || key == 46 || (key >= 37 && key <= 40) || (key >= 48 && key <= 57) || (key >= 96 && key <= 105)))
                {
                    //uncomment these lines if you would like to display a green ok sign
                    //clearMessage(errorFieldID);
                    //showMessage("", "SUCCESS", errorFieldID, 0); //0 = forever i.e.: no timeout
                    return true;
                }
                else
                {
                    if (validation["error"]["numeric"] != undefined)
                        message = validation["error"]["numeric"];
                    else message = validation["error"];
                    
                    methods.clearMessage(data.$errorField);
                    methods.showMessage(message, "ERROR", data.$errorField, 2000);
                    return false;
                }
            })
        },
        
        submit: function(reportResult) {
            var base = $(this).data("qeyValidator");
            var fields = base.descriptor;
            var result = true;
            
            for (var name in fields)
            {
                if (typeof fields[name]['validation'] == "undefined")
                    continue;
                
                var $target = $('#row-' + name).find('input, textarea, select');
                var data = $target.data("qeyValidationInputData");
                
                if (data.vData["mandatory"] != null && data.vData["mandatory"] == true)
                    result = methods.validate($target, "mandatory") && result;
                    
                if (typeof data.vData["type"] != "undefined" && data.vData["type"] != "date")
                    if (typeof options.validators[data.vData["type"]] != "undefined")
                        result = methods.validate($target, data.vData["type"]) && result;
                    else
                        alert("Validator for '" + data.vData["type"] + "' was not provided and wasn't included");
            }
            reportResult(result);
        },
            
        validate: function($target, type)
        {
            var data = $target.data("qeyValidationInputData");
            if (type == null) {
                type = data.vData.type;
            }
            
            if (typeof data.vData["error"][type] != "undefined") {
                message = data.vData["error"][type];
            } else {
                message = data.vData["error"];
            }
                
            if (! options.validators[type]($target, data.vData)) {
                methods.showMessage(message, "ERROR", $target, 0);
                data.errorType = type;
                return false;
            } else if (data.errorType == "ok" || data.errorType == type) {
                methods.showMessage("", "SUCCESS", $target, 0);
                data.errorType = "ok";
            }
            return true;
        },
        
        showMessage: function(message, type, $target, timeout)
        {
            var data = $target.data("qeyValidationInputData");
            var $field = data.$errorField;
            if (type == "SUCCESS")
            {
                methods.clearMessage($target)
                return;
            }
            
            $field.text(message).removeClass('validation-success validation-warning validation-error')
                .addClass('validation-' + type.toLowerCase()).fadeIn();
            if (timeout != undefined && timeout > 0)
                setTimeout(function() {methods.clearMessage($target)}, timeout);            
        },
        
        clearMessage: function($target)
        {
            var data = $target.data("qeyValidationInputData");
            var $field = data.$errorField;
            $field.removeClass("validation-success validation-warning validation-error").text("");
        }
    };
        
    $.fn.qeyValidator = function (method, args)
    {
        var a = arguments;
        return $(this).each(function ()
        {
            if ( methods[method] && method.length > 0 && method[0] != '_') {
                return methods[ method ].apply( this, Array.prototype.slice.call( a, 1 ));
            } else if ( typeof method === 'object' || ! method ) {
                return methods.init.apply( this, a );
            } else {
                $.error( 'Method ' +  method + ' does not exist or is a private function' );
            }    
        });
    };

})(jQuery);