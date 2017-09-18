$(function() {
    var sceditorConfig = {
        plugins: 'bbcode',
        style: '/tools/sceditor/minified/jquery.sceditor.default.min.css',
        emoticonsRoot : "/tools/sceditor/",
        width: '100%',
        height: '400px',
        resizeWidth : false
    }

   $('#editor').sceditor(sceditorConfig); 


 
});
