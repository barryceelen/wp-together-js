(function ($) {
	"use strict";
	$(function () {
		var pluginTogetherJs = {

			init: function() {

				if ( 'undefined' === typeof TogetherJS || 'undefined' === typeof pluginTogetherJsVars.options ) {
					return;
				}

				$.each( pluginTogetherJsVars.options, function( k, v ) {

					if ( 'userAvatar' === k ) {
						TogetherJS.config( 'getUserAvatar', function() { return v; } );
					} else if ( 'userName' === k ) {
						TogetherJS.config( 'getUserName', function() { return v; } );
					} else {
						TogetherJS.config( k, v );
					}
				});

				this.setupEventHandlers();
			},

			setupEventHandlers: function() {

				var $button = $( pluginTogetherJsVars.buttonEl ), $body = $( 'body' );

				$button.on( 'click', function(e) {
					e.preventDefault();
					TogetherJS();
				});

				TogetherJS.on( 'ready', function () {
					$button.html( pluginTogetherJsVars.labelStop );
					$body.toggleClass( 'togetherjs-active' );
				});

				TogetherJS.on( 'close', function () {
					$button.html( pluginTogetherJsVars.labelStart );
					$body.toggleClass( 'togetherjs-active' );
				});
			}
		}

		pluginTogetherJs.init();
	});
}(jQuery));
