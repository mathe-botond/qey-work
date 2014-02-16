(function ($)
{
    $.fn.qeyUploader = function (options)
    {
        defaults = {
            iframeID: 'qey-upload-iframe',       // Iframe ID.
            json: false,                        // Parse server response as a json object.
            post: function () {return true;},   // Form onsubmit.
            complete: function (response) {},    // After response from the server has been received.
            template:     '<form id="qey-upload-form" action="' + config.path.app + 'q/publisher/upload" target="qey-upload-iframe"'+
                                    'enctype="multipart/form-data" method="post">' +
                            '<div class="fake-input">\n' +
                                '<span id="qey-upload-input-name"></span>' +
                                '<span id="qey-upload-input-size"></span>' +
                                '<div id="qey-upload-browse" class="inside-button" class="si.cabinet">Browse...' +
                                    '<input id="qey-upload-fileinput" type="file" name="file" class="si.file" size="50"/>' +
                                '</div>' +
                            '</div>' +
                            '<input id="qey-upload-submit" type="submit" class="button" value="Upload">' +
                        '</form>' +
                        '<div id="qey-upload-error" style="display:none"></div>',
            allowedExtensions: ["png"],
            sizeLimit: 5 * 1048576 //5 MB
        };
        
        var $this = $(this),
            uid,
            response,
            returnResponse,
            status = true,            
            
            $iframe = null,
            $fname = null,
            $fsize = null,
            $browse = null,
            $form = null,
            $file = null,
            $error = null,
            
            messages = {
                typeError: "'{file}' has invalid extension. Only [{extensions}] is allowed.",
                sizeError: "'{file}' is too large, maximum file size is {sizeLimit}.",
                emptyError: "'{file}' is empty."            
            };
            
        options = $.extend({}, defaults, options);
        
        methods = {
            init: function() {        
                //Get jQuery objects
                $this.append(options.template);
                $iframe = $("#qey-upload-iframe", $this);
                $fname = $("#qey-upload-input-name", $this);
                $fsize = $("#qey-upload-input-size", $this);
                $browse = $("#qey-upload-browse", $this);
                $submit = $("#qey-upload-submit", $this);
                $form = $("#qey-upload-form", $this);
                $file = $("input[name=file]", $this);
                $error = $("#qey-upload-error", $this);
                
                //If multiple uploader instances are on a single page (lol)
                uid = $("body").data("qey-static");
                if (uid == undefined)
                    uid = 0;
                else
                    uid = uid + 1;
                $("body").data("qey-static", uid);
                
                //Add iframe
                var newid = options.iframeID + "-" + uid;
                $this.append('<iframe id="' + newid + '" name="' + newid + '" style="display:none" />');
                $iframe = $("iframe", $this);
                
                //Init file input
                SI.Files.stylizeById('qey-upload-fileinput');
                
                //Set form target
                $form.attr('target', newid);
                
                //Bind events
                $file.change(methods.change);
                $form.submit(methods.submit);
            },
            
            change: function() {
                var f = new Object();
                if ($.browser.msie)
                {
                    f.name = $(this).get(0).value;
                    f.size = 0;
                }
                else
                {
                    f = this.files[0];

                    if (! methods.validate(f.name, f.type, f.size))
                        return false;
                }
                
                $fname.html('<nobr>' + f.name + '</nobr>');                    
                return true;
            },
            
            validate: function(name, type, size)
            {
                var ext = (-1 !== type.indexOf('/')) ? type.replace(/.*[\/]/, '').toLowerCase() : '';
                var allowed = false;
                if (! options.allowedExtensions.length){allowed = true;}        
                else
                {
                    for (var i=0; i<options.allowedExtensions.length; i++){
                        if (options.allowedExtensions[i].toLowerCase() == ext){
                            allowed = true;
                            break;
                        }    
                    }
                }
                
                if (! allowed){            
                    this.error('typeError', name);
                    return false;
                    
                } else if (size === 0){            
                    this.error('emptyError', name);
                    return false;
                                                             
                } else if (size && options.sizeLimit && size > options.sizeLimit){            
                    this.error('sizeError', name);
                    return false;            
                }
                
                if ($error.is(":visible"))
                    $error.fadeOut('fast');
                return true;
            },
            
            submit: function()
            {
                // If status is false then abort.
                status = options.post.apply(this);
                
                if (status === false)
                    return false;
                    
                $iframe.load(methods.iframe);
            },
            
            iframe: function()
            {
                response = $iframe.contents().find('body');
                
                if (options.json)
                    returnResponse = $.parseJSON(response.html());
                else
                    returnResponse = response.html();
                
                options.complete.apply(this, [returnResponse]);
                
                $iframe.unbind('load');
                //response.html('');
            },
            
            error: function(code, filename)
            {    
                message = messages[code];
                message = message.replace('{file}', filename);
                message = message.replace('{extensions}', options.allowedExtensions.join(', '));
                message = message.replace('{sizeLimit}', methods.formatSize(options.sizeLimit));
                
                $error.text(message);
                if ($error.not(":visible"))
                    $error.fadeIn('fast');
            },
            
            formatSize: function(size)
            {
                if (size > 1073741824)
                    return (Math.floor(size / 107374182.4) / 10) + " GB";
                else if (size > 1048576)
                    return (Math.floor(size / 104857.6) / 10) + " MB";
                else if (size > 1024)
                    return (Math.floor(size / 102.4) / 10) + " KB";
                else
                    return size + " B";
            }
        };
        
        return $(this).each(function ()
        {
            methods.init.apply(this);
        });
    };
})(jQuery);