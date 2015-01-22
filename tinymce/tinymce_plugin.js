(function($, tinymce) {
    tinymce.create('tinymce.plugins.GuruTheme', {
        init : function(ed, url) {
            
            ed.addButton('gmap', {
                title : 'Google MAP',
                cmd : 'gmap',
                //image : '../wp-includes/images/smilies/icon_mrgreen.gif'
                image : url + '/gmap.png'
            });
 
            ed.addButton('guru_button', {
                title : 'Ajouter un bouton',
                cmd : 'guru_button',
                //image : '../wp-includes/images/smilies/icon_mrgreen.gif'
                image : url + '/button.png'
            });
 
            ed.addCommand('gmap', function() {
                
                // triggers the thickbox
                var width = $(window).width(), H = $(window).height(), W = ( 720 < width ) ? 720 : width;
                W = W - 80;
                H = H - 160;
                tb_show( 'Google MAP', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=gmap_popup' );
                
            });

            ed.addCommand('guru_button', function() {
                if(ed.selection.getContent()) {
                    var shortcode = '[guru_button]' + ed.selection.getContent() + '[/guru_button]';
                    tinymce.activeEditor.selection.setContent(shortcode);
                }
                
            });
        },
        createControl : function(n, cm) {
            return null;
        },
 
        getInfo : function() {
            return {
                longname : 'TinyMCE Buttons by GuruMeditation',
                author : 'Guru Meditation',
                authorurl : 'http://gurumeditation.fr',
                infourl : 'http://gurumeditation.fr',
                version : "0.1"
            };
        }
    });
    // Register plugin
    tinymce.PluginManager.add( 'shortcode_tinymce_plugins', tinymce.plugins.GuruTheme );
    //
    
    /**
     * handling the function called by the GMAP Pop-up
     */
    $(function(){
            // creates a form to be displayed everytime the button is clicked
            // you should achieve this using AJAX instead of direct html code like this
            var form = $('#gmap_popup').hide();
            //alert('pop')
            var table = form.find('table');
            //form.appendTo('body').hide();

            // handles the click event of the submit button
            form.find('#gmap-submit').click(function(){
                    // defines the options and their default values
                    // again, this is not the most elegant way to do this
                    // but well, this gets the job done nonetheless
                    var options = { 
                            'addr'    : 'Paris'/*,
                            'id'         : ''*/
                            };
                    var shortcode = '[guru_gmap';

                    for( var index in options) {
                            var value = table.find('#gmap_' + index).val();

                            // attaches the attribute to the shortcode only if it's different from the default value
                            if ( value !== options[index] )
                                    shortcode += ' ' + index + '="' + value + '"';
                    }

                    shortcode += ']';
                    if(tinymce.activeEditor.selection.getContent()) {
                         tinymce.activeEditor.selection.setContent(shortcode + tinymce.activeEditor.selection.getContent() + '[/guru_gmap]');
                    }
                    else {
                        // inserts the shortcode into the active editor
                        tinymce.activeEditor.execCommand('mceInsertContent', 0, shortcode);
                    }
                    // closes Thickbox
                    tb_remove();
            });
    });
    
})(jQuery, tinymce);