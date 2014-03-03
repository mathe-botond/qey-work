if (typeof String.prototype.startsWith != 'function') {
  String.prototype.startsWith = function (str){
    return this.indexOf(str) == 0;
  };
}

function createInputs()
{
    $("input[type=text], textarea").not('input[data-editable=false]').each(function()
    {
        $(this).data("value", $(this).val());
        $(this).css("color", "#666");
        $(this).focus(function()
        {
            if ($(this).val() == $(this).data("value"))
            {
                $(this).css("color", "#000");
                $(this).val("");
            }
        });
        $(this).blur(function()
        {
            if ($(this).val() == "")
            {
                $(this).css("color", "#666");
                $(this).val($(this).data("value"));
            }
        });
    });
    
    /*$("form").submit(function () {
        $("input[type=text], textarea").each(function()
        {
            if ($(this).val() == $(this).data("value"))
                $(this).val('');
        });
    });*/
    
    $('input[data-editable=false]').click(function()
    {
        $(this).select();
    });
}

function resizeImage(image, maxWidth, maxHeight)
{
    if (image.height > maxHeight)
    {
        ratio = image.width / image.height;
        image.width = ratio * maxHeight;
        image.height = maxHeight;
    }
    
    if (image.width > maxWidth)
    {
        ratio = image.height / image.width;
        image.height = ratio * maxWidth;
        image.width = maxWidth;
    }
}

function getUrlVars(hash)
{
    var vars = [];
    if (hash == null || hash == '')
        hash = window.location.href;
    var hashes = hash.slice(hash.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}