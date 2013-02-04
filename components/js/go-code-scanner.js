var go_code_scanner = {
	prefix: 'go-code-scanner-',
	types: {
		plugin: {
			show: [ 'plugin' ]
		},
		theme: {
			show: [ 'theme', 'theme-file' ]
		},
		"theme-file": {
			show: []
		},
		"vip-theme": {
			show: [ 'vip-theme', 'vip-theme-file' ]
		},
		"vip-theme-file": {
			show: []
		},
		"vip-theme-plugin": {
			show: [ 'vip-theme', 'vip-theme-plugin' ]
		}
	}
};

(function( $ ) {
	go_code_scanner.init = function() {
		$(document).on( 'change', '#go-code-scanner-type', go_code_scanner.select_type );

		$(document).on( 'change', '#go-code-scanner-vip-theme', function( e ) {
			go_code_scanner.select_theme( $(this), 'vip-theme' );
		});

		$(document).on( 'change', '#go-code-scanner-theme', function( e ) {
			go_code_scanner.select_theme( $(this), 'theme' );
		});

		for ( var item in go_code_scanner_selection ) {
			if ( go_code_scanner_selection[ item ] ) {
				$('select[name="' + item + '"]').val( go_code_scanner_selection[ item ] ).change();
			}//end if
		}//end for
	};

	go_code_scanner.select_type = function( e ) {
		var $el = $(this);
		var value = $el.val();

		if ( value.length > 0 ) {

			go_code_scanner.hide_types();

			for ( var index in go_code_scanner.types[ value ].show ) {
				var section = go_code_scanner.types[ value ].show[ index ];
				$('.' + section ).show();
			}//end for
		}//end if
	};

	/**
	 * hides a selection types
	 */
	go_code_scanner.hide_types = function() {
		for ( var section in go_code_scanner.types ) {
            if( !$('.' + section ).hide().find('select').length )
                continue;

			$('.' + section ).hide().find('select')[0].selectedIndex = 0;
			$('.' + section ).find('.note').show();
		}//end for
	};

	go_code_scanner.select_theme = function( $el, type ) {
		var $section = $('.' + type);
		var value    = $el.val();
		var $plugin  = $('.' + type + '-plugin');
		var $select  = $plugin.find('.' + value);
		var $file    = $('.' + type + '-file');

		if ( value.length > 0 ) {
			var $files   = $file.find('.' + value);

			$plugin.find('select').hide().each( function() {
				$(this)[0].selectedIndex = 0;
			});

			$file.find('select').hide().each( function() {
				$(this)[0].selectedIndex = 0;
			});

			$files.show();
			$file.find('.note').hide();

			if ( $select.length > 0 ) {
				$plugin.find('.note').hide();
				$select.show();
			} else {
				$plugin.find('.note').show();
			}//end else
		} else {
			$file.find('select').hide();
			$file.find('.note').show();
			$plugin.find('select').hide();
			$plugin.find('.note').show();
		}//end else
	};
})( jQuery );
