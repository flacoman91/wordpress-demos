(function ($) {
    
    /**
     * borrowed some code from here:
     * http://code.tutsplus.com/tutorials/guide-to-creating-your-own-wordpress-editor-buttons--wp-30182
     */
    tinymce.create( 'tinymce.plugins.tinymceYoutubeShortcode', {
        init : function (ed, url) {
            ed.addButton( 'tinymceYoutubeShortcode', {
                    title : 'Insert Youtube Video',
                    cmd   : 'tinymceYoutubeShortcode',
                    image : url + '/images/icon-youtube.png'
            } );
            ed.addCommand( 'tinymceYoutubeShortcode', function () {
                $("#dialog-form-tinymceYoutube").dialog("open");
                validateBCForm();

            } );
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
        getInfo : function () {
             return {
                     longname  : 'Youtube Shortcode Editor',
                     author    : 'Richard Dinh',
                     authorurl : 'http://northps.com/',
                     infourl   : 'http://northps.com/',
                     version   : "0.1"
             };
         }
    });

    tinymce.PluginManager.add( 'tinymceYoutubeShortcode', tinymce.plugins.tinymceYoutubeShortcode );
    
    // we are using jquery dialog to pull the embedded form in the admin menu and display it.
    $( "#dialog-form-tinymceYoutube" ).dialog({
        autoOpen: false,
        height: 200, // set the size of the modal dialog here
        width: 400,
        modal: true,
        buttons: {
            // create the button name.
            "Insert Video": function() {
            
                var ed = window.tinyMCE;

                /* This is with everything but we want an EW Specific one */
                /* EW Specific */
                var shortcode_string = '';

                if ($('#youtube-height').val() !== '')
                {
                    shortcode_string += 'height="' + $('#youtube-height').val() + '" ';
                }
                if ($('#youtube-width').val() !== '')
                {
                    shortcode_string += 'width="' + $('#youtube-width').val() + '" ';
                }
                
                var shortcode = '[youtube="' + $('#youtube-url').val() + '"'+ shortcode_string +']';

                ed.execCommand('mceInsertContent', 0, shortcode);
                $( this ).dialog( "close" );

            },
          // create the cancel button
          Cancel: function() {
            $( this ).dialog( "close" );
          }
        }
 });
    // some data validation
    $('#youtube-url').keyup(function(){        
           validateBCForm();
    });


   function validateBCForm(){

       var bValid = false;
       
       // make sure it's not empty
       if( $( '#youtube-url' ).val() !== '')
           bValid = true;        
       
       // if you have some more fields on the form, you can do more error checking to enable/disable the button. 
       // for instance you might want to make sure the values for height/width are numeric or a certain range.
       
       // you could also attach some jquery to make invalid boxes red or whatever to let users know they didn't do it right
        
       // disable button if what they did not enter is correct.
       if(bValid)
           $("button span:contains('Insert Video')").parent().attr("disabled", false);
       else
           $("button span:contains('Insert Video')").parent().attr("disabled", true);
   }

})(jQuery);