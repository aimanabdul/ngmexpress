(function( $ ) {

	/* Radio Image Select */
	jQuery( document ).on('click', '.wgl-radio-image label', function(e){
		$(this).addClass('selected').siblings().removeClass("selected");

	});
	/* \Radio Image Select */

	/* WGL Icon Extensions */
	jQuery( window ).on( 'elementor:init', function() {

		var WGLControlIconView = elementor.modules.controls.Select2.extend( {

			initialize: function initialize() {
				WGLControlSelect2View.prototype.initialize.apply(this, arguments);
				this.filterIcons();
			},

			filterIcons: function filterIcons() {
				var icons = this.model.get('options'),
				    include = this.model.get('include'),
				    exclude = this.model.get('exclude');

				if (include) {
					var filteredIcons = {};

					_.each(include, function (iconKey) {
						filteredIcons[iconKey] = icons[iconKey];
					});

					this.model.set('options', filteredIcons);
					return;
				}

				if (exclude) {
					_.each(exclude, function (iconKey) {
						delete icons[iconKey];
					});
				}
			},

			iconsList: function iconsList(icon) {
				if (!icon.id) {
					return icon.text;
				}

				return jQuery('<span><i class="' + icon.id + '"></i> ' + icon.text + '</span>');
			},

			getSelect2Options: function getSelect2Options() {
				return {
					allowClear: true,
					templateResult: this.iconsList.bind(this),
					templateSelection: this.iconsList.bind(this)
				};
			}



		} );
		elementor.addControlView( 'wgl-icon', WGLControlIconView );

		var WGLControlSelect2View = elementor.modules.controls.Select2;

		WGLControlSelect2View.extend({
			_enqueuedFonts: [],

			$previewContainer: null,

			enqueueFont: function enqueueFont(font) {
				if (-1 !== this._enqueuedFonts.indexOf(font)) {
					return;
				}

				var fontUrl = void 0;
				var fontType = elementor.config.controls.font.options[font];

				switch (fontType) {
					case 'googlefonts':
						fontUrl = 'https://fonts.googleapis.com/css?family=' + font + '&text=' + font;
						break;

					case 'earlyaccess':
						var fontLowerString = font.replace(/\s+/g, '').toLowerCase();
						fontUrl = 'https://fonts.googleapis.com/earlyaccess/' + fontLowerString + '.css';
						break;
				}

				if (!_.isEmpty(fontUrl)) {
					jQuery('head').find('link:last').after('<link href="' + fontUrl + '" rel="stylesheet" type="text/css">');
				}

				this._enqueuedFonts.push(font);
			},
			getSelect2Options: function getSelect2Options() {
				return {
					dir: elementorCommon.config.isRTL ? 'rtl' : 'ltr',
					templateSelection: this.fontPreviewTemplate,
					templateResult: this.fontPreviewTemplate
				};
			},
			onReady: function onReady() {
				var self = this;
				this.ui.select.select2(this.getSelect2Options());
				this.ui.select.on('select2:open', function () {
					self.$previewContainer = jQuery('.select2-results__options[role="tree"]:visible');
					// load initial?
					setTimeout(function () {
						self.enqueueFontsInView();
					}, 100);

					// On search
					jQuery('input.select2-search__field:visible').on('keyup', function () {
						self.typeStopDetection.action.apply(self);
					});

					// On scroll
					self.$previewContainer.on('scroll', function () {
						self.scrollStopDetection.onScroll.apply(self);
					});
				});
			},


			typeStopDetection: {
				idle: 350,
				timeOut: null,
				action: function action() {
					var parent = this,
					    self = this.typeStopDetection;
					clearTimeout(self.timeOut);
					self.timeOut = setTimeout(function () {
						parent.enqueueFontsInView();
					}, self.idle);
				}
			},

			scrollStopDetection: {
				idle: 350,
				timeOut: null,
				onScroll: function onScroll() {
					var parent = this,
					    self = this.scrollStopDetection;
					clearTimeout(self.timeOut);
					self.timeOut = setTimeout(function () {
						parent.enqueueFontsInView();
					}, self.idle);
				}
			},

			enqueueFontsInView: function enqueueFontsInView() {
				var self = this,
				    containerOffset = this.$previewContainer.offset(),
				    top = containerOffset.top,
				    bottom = top + this.$previewContainer.innerHeight(),
				    fontsInView = [];

				this.$previewContainer.children().find('li:visible').each(function (index, font) {
					var $font = jQuery(font),
					    offset = $font.offset();
					if (offset && offset.top > top && offset.top < bottom) {
						fontsInView.push($font);
					}
				});

				fontsInView.forEach(function (font) {
					var fontFamily = jQuery(font).find('span').html();
					self.enqueueFont(fontFamily);
				});
			},
			fontPreviewTemplate: function fontPreviewTemplate(state) {
				if (!state.id) {
					return state.text;
				}

				return jQuery('<span>', {
					text: state.text,
					css: {
						'font-family': state.element.value.toString()
					}
				});
			},
			templateHelpers: function templateHelpers() {
				var helpers = ControlSelect2View.prototype.templateHelpers.apply(this, arguments),
				    fonts = this.model.get('options');

				helpers.getFontsByGroups = function (groups) {
					var filteredFonts = {};

					_.each(fonts, function (fontType, fontName) {
						if (_.isArray(groups) && _.contains(groups, fontType) || fontType === groups) {
							filteredFonts[fontName] = fontName;
						}
					});

					return filteredFonts;
				};

				return helpers;
			}
		});


	} );

	/* \WGL Icon Extensions */

})( jQuery );