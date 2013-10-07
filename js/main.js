(function ($) {
	"use strict";
	$(function () {
		/**
		 * Loads TogetherJS and toggles link label.
		 *
		 * According to the TogetherJS docs, the button label should be
		 * switched automatically. As this does not seem to be the case,
		 * we're doing it ourselves for now.
		 */
		var pluginTogetherJs = {
			$el: $('#wp-admin-bar-together-js a'),
			init: function() {
				if ( TogetherJS._loaded ) {
					this.$el.html( pluginTogetherJsVars.labelStop );
					_this.$el.addClass('togetherjs-started');
				}
				this.setupEventHandlers();
			},
			setupEventHandlers: function() {
				var _this = this;
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
