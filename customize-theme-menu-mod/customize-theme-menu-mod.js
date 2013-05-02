/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {

	wp.customize( 'some_link_color', function( value ) {
		value.bind( function( to ) {
			$( 'a' ).css( 'color', to );
		} );
	} );

	wp.customize( 'rg_font_h1', function( value ) {
		value.bind( function( to ) {

			var obj = jQuery.parseJSON(to);
			for (var prop in obj) {
				// parse out the array and set each of the font values
				if (obj.hasOwnProperty(prop)) {
					// or if (Object.prototype.hasOwnProperty.call(obj,prop)) for safety...
					$( prop ).css( 'font-family', obj[prop] );
				}
			}
		} );
	} );
} )( jQuery );