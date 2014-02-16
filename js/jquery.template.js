(function ($)
{
    $.fn.qeyPlugin = function (options)
    {
        defaults = {
            defaultVar: "defaultVal"
        };
        
        var $this = $(this);
        alert($this);
        
        $items = null;
            
        options = $.extend({}, defaults, options);
        
        methods = {
            init: function() {        
                //Get jQuery objects
                                
                //If multiple instances are on a single page and need unique id (lol)
                uid = $("body").data("qey-static");
                if (uid == undefined)
                    uid = 0;
                else
                    uid = uid + 1;
                $("body").data("qey-plugin", uid);
                
                //Bind events
            }
        };
        
        return $(this).each(function ()
        {
            methods.init.apply(this);
        });
    };
})(jQuery);