(function() {
    tinymce.create('tinymce.plugins.code', {
        init : function(ed, url) {
            ed.addButton('codebutton', {
                title : 'Insert Simple Update Shortcut',
                cmd : 'codebutton',
                image :  url + '/mce_button.png'
            });
			ed.addCommand('codebutton', function() {
                var selected_text = ed.selection.getContent();
                console.log(selected_text);
                if (selected_text != '') {
                	var title = get_title();
                	var insert = "<div class='simple_update' data-title='" + title + "'>" + selected_text + "</div>";
                    ed.execCommand('mceInsertContent', 0, insert);
	          		source_changed(ed.getContent());
                } else {
                	alert("Select the text you want to create a shortcut for first.");
                }
            });
            ed.on('keyup', function(e) {
	            source_changed(ed.getContent());
	        });
        },
    });
    tinymce.PluginManager.add( 'mycodebutton', tinymce.plugins.code );
})();

function get_title() {
    var title = prompt("Please enter shortcut name");
 	if (title != "") {
	   	return title;
	} else {
		return get_title();
	}
}