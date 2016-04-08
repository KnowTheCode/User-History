/**
 * FAQ JavaScript handling.  This script handles opening/closing of the questions and answers,
 * toggling of the icon handle, and setting of the class states.
 *
 * @package     FAQ
 * @since       1.0.0
 * @author      hellofromTonya
 * @link        https://knowthcode.io
 * @license     GNU General Public License 2.0+
 */
;(function ($, window, document, undefined) {
	'use strict'

	function init() {
		$('.embed-video__activity').on( 'click', function( event ){
			event.preventDefault();

			var $element = $( this ),
				state = getState( $element ),
				data = buildDataPacket( $element, state );

			ajaxHandler( data, $element );

			return false;
		});

	}

	function getState( $element ) {
		return $element.hasClass('active') ? 0 : 1;
	}

	function buildDataPacket( $element, state ) {
		var data = {
			action:         uhParameters.action,
			security:       $element.data('nonce'),
			id:             $element.data('id'),
			video_id:       $element.data('videoId'),
			post_id:        $element.data('postId'),
			activity_id:    $element.data('activityId'),
			activity_state: state
		}

		return data;
	}

	function ajaxHandler( data, $element ) {

		$.post( uhParameters.ajaxurl, data, function( response ) {
			var recordID = parseInt(response);
			if ( recordID < 1 ) {
				return false;
			}

			$element.data( 'id', recordID );

			updateText( $element, data.activity_state );
			updateActivityState( $element, data.activity_state );
		})
		 .fail(function(){
//			console.log( 'failed' );
		 })
         .always(function(){

		 });
	}

	function updateText( $element, state ) {
		var $textElement = $element.find( 'span.activity-text');
		if ( typeof $textElement == "undefined" ) {
			return false;
		}
		var activeText = $element.data('activeText' ),
			inactiveText = $element.data('inactiveText' );

		if ( typeof activeText == "undefined" ) {
			return false;
		}

		$textElement.text( state ? activeText : inactiveText );
	}

	function updateActivityState( $element, state ) {
		if ( state ) {
			$element.addClass('active');
		} else {
			$element.removeClass('active');
		}
	}

	$(document).ready(function () {
		if ( typeof uhParameters === "object" ) {
			init();
		}
	});

}(jQuery, window, document));