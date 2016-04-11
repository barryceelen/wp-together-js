(function ($) {
	"use strict";
	$(function () {
		var pluginTogetherJs = {

			init: function() {

				TogetherJS.config( 'siteName', pluginTogetherJsVars.siteName );
				TogetherJS.config( 'toolName', pluginTogetherJsVars.toolName );
				TogetherJS.config( 'enableShortcut', pluginTogetherJsVars.enableShortcut );
				TogetherJS.config( 'getUserName', function() { return pluginTogetherJsVars.userName; } );
				TogetherJS.config( 'getUserAvatar', function() { return pluginTogetherJsVars.avatarUrl; } );

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
