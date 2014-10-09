(function() {
    tinymce.create('tinymce.plugins.Apparition', {
        /**
         * Initializes the plugin, this will be executed after the plugin has been created.
         * This call is done before the editor instance has finished it's initialization so use the onInit event
         * of the editor instance to intercept that event.
         *
         * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
         * @param {string} url Absolute URL to where the plugin is located.
         */
        init : function(ed, url) {
        
        	ed.addButton('invisibleline', {
                title : 'Invisible Line',
                cmd : 'invisibleline',
                image : url + '/invisibleline.png'
            });
            
            ed.addButton('tekbutton', {
                title : 'TekButton',
                cmd : 'tekbutton',
                image : url + '/tekbutton.png'
            });
            
            ed.addButton('maptrigger', {
                title : 'Trigger for Directions Drawer',
                cmd : 'maptrigger',
                image : url + '/maptrigger.png'
            });
            
            ed.addButton('drawertrigger', {
                title : 'Trigger for Drawer',
                cmd : 'drawertrigger',
                image : url + '/drawertrigger.png'
            });
            
            ed.addCommand('invisibleline', function() {
            	var return_txt = '[invisibleline]';
            	ed.execCommand('mceInsertContent', 0, return_txt);
            });
            
            ed.addCommand('tekbutton', function() {
                var url = prompt("Enter URL to link to: "),
                    shortcode,
                    content = ed.selection.getContent();
                if (url !== null) {
                    shortcode = '[tekbutton linkurl="' + url + '"]' + content + '[/tekbutton]';
                    ed.execCommand('mceInsertContent', 0, shortcode);
                }
                else {
                    alert("Enter a valid link.");
                }
            });
            
            ed.addCommand('maptrigger', function() {
                var shortcode,
                    content = ed.selection.getContent();
                    shortcode = '[maptrigger]' + content + '[/maptrigger]';
                    ed.execCommand('mceInsertContent', 0, shortcode);
            });
            
            ed.addCommand('drawertrigger', function() {
                var shortcode,
                	drawer = prompt("Enter ID of drawer to trigger: "),
                    content = ed.selection.getContent();
                    if (drawer !== null) {
						shortcode = '[drawertrigger drawerid="' + drawer + '" title="' + content + '" swaptitle="Hide ' + content + '"]' + content + '[/drawertrigger]';
						ed.execCommand('mceInsertContent', 0, shortcode);
                    }
                    else {
						alert("Enter a valid drawer ID.");
					}
            });
 
        },
 
        /**
         * Creates control instances based in the incoming name. This method is normally not
         * needed since the addButton method of the tinymce.Editor class is a more easy way of adding buttons
         * but you sometimes need to create more complex controls like listboxes, split buttons etc then this
         * method can be used to create those.
         *
         * @param {String} n Name of the control to create.
         * @param {tinymce.ControlManager} cm Control manager to use inorder to create new control.
         * @return {tinymce.ui.Control} New control instance or null if no control was created.
         */
        createControl : function(n, cm) {
            return null;
        },
 
        /**
         * Returns information about the plugin as a name/value array.
         * The current keys are longname, author, authorurl, infourl and version.
         *
         * @return {Object} Name/value array containing information about the plugin.
         */
        getInfo : function() {
            return {
                longname : 'Apparition Buttons',
                author : 'Chad A. Carino',
                authorurl : 'http://www.chadacarino.com',
                infourl : 'http://www.github.com/bangerkuwranger',
                version : "0.1"
            };
        }
    });
 
    // Register plugin
    tinymce.PluginManager.add( 'apparition', tinymce.plugins.Apparition );
})();