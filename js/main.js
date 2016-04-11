(function ($) {
	"use strict";
	$(function () {
		var pluginTogetherJs = {

			$el: $( '#wp-admin-bar-together-js a' ),

			init: function() {

				TogetherJS.config( 'siteName', pluginTogetherJsVars.siteName );
				TogetherJS.config( 'toolName', pluginTogetherJsVars.toolName );
				TogetherJS.config( 'enableShortcut', pluginTogetherJsVars.enableShortcut );
				TogetherJS.config( 'getUserName', function() { return pluginTogetherJsVars.userName; } );
				TogetherJS.config( 'getUserAvatar', function() { return pluginTogetherJsVars.avatarUrl; } );

				this.setupEventHandlers();
			},
			setupEventHandlers: function() {

				var _this = this;

				/**
				 * According to the TogetherJS docs, the button label should be
		 		 * switched automatically. As this does not seem to be the case,
		 		 * we're doing it ourselves for now.
				 */
				TogetherJS.on( 'ready', function () {
					_this.$el.html( pluginTogetherJsVars.labelStop );
					_this.$el.toggleClass('togetherjs-started');
				});

				TogetherJS.on( 'close', function () {
					_this.$el.html( pluginTogetherJsVars.labelStart );
					_this.$el.toggleClass('togetherjs-started');
				});

				this.$el.on( 'click', function(e) {
					e.preventDefault();
					TogetherJS();
				});
			}
		}

		pluginTogetherJs.init();
	});
}(jQuery));
