<script type="text/javascript">
    (function(){
        var css = {this.css};
        var container = (document.getElementsByTagName('head')[0]||document.getElementsByTagName('body')[0]);
        for (var i = 0; i<css.length; ++i) {
            var entry = document.createElement('link');

            entry.rel = 'stylesheet';
            entry.type = 'text/css';
            entry.href = css[i];
            container.appendChild(entry);
        }
    })();
</script>