module.exports = function(grunt) {

	// load all grunt tasks in package.json matching the `grunt-*` pattern
	require('load-grunt-tasks')(grunt);

	grunt.initConfig({
		//Pass in options to plugins, references to files etc
		pkg: grunt.file.readJSON( 'package.json' ),

		phpunit: {
			classes: {},
			options: {
				bin: 'vendor/bin/phpunit',
			}
		},

		dirs: {
			lang: 'languages'
		},

		asciify: {
			banner: {
				text    : 'cmb-ext',
				options : {
					font : 'univers',
					log  : true
				}
			}
		},

		makepot: {
			target: {
				options: {
					domainPath: 'languages/',
					potComments: '',
					potFilename: 'cmb-ext.pot',
					type: 'wp-plugin',
					updateTimestamp: true,
					potHeaders: {
						poedit: true,
						'language': 'en_US',
						'x-poedit-keywordslist': true
					},
					processPot: function( pot, options ) {
						pot.headers['report-msgid-bugs-to'] = 'http://wordpress.org/support/plugin/cmb2';
						pot.headers['last-translator'] = 'CMB2 EXT';
						pot.headers['language-team'] = 'CMB2 EXT';
						var today = new Date();
						pot.headers['po-revision-date'] = today.getFullYear() +'-'+ ( today.getMonth() + 1 ) +'-'+ today.getDate() +' '+ today.getUTCHours() +':'+ today.getUTCMinutes() +'+'+ today.getTimezoneOffset();
						return pot;
					}
				}
			}
		},

		potomo: {
			dist: {
				options: {
					poDel: false
				},
				files: [{
					expand: true,
					cwd: '<%= dirs.lang %>/',
					src: ['*.po'],
					dest: '<%= dirs.lang %>/',
					ext: '.mo',
					nonull: true
				}]
			}
		},

		checktextdomain: {
			options: {
				text_domain: 'cmb-ext',
				create_report_file: true,
				keywords: [
					'__:1,2d',
					'_e:1,2d',
					'_x:1,2c,3d',
					'esc_html__:1,2d',
					'esc_html_e:1,2d',
					'esc_html_x:1,2c,3d',
					'esc_attr__:1,2d',
					'esc_attr_e:1,2d',
					'esc_attr_x:1,2c,3d',
					'_ex:1,2c,3d',
					'_n:1,2,4d',
					'_nx:1,2,4c,5d',
					'_n_noop:1,2,3d',
					'_nx_noop:1,2,3c,4d',
					' __ngettext:1,2,3d',
					'__ngettext_noop:1,2,3d',
					'_c:1,2d',
					'_nc:1,2,4c,5d'
				]
			},
			files: {
				src: [
					'**/*.php', // Include all files
					'!node_modules/**', // Exclude node_modules/
					],
				expand: true
			}
		},

		cssjanus: {
			i18n: {
				options: {
					swapLtrRtlInUrl: false
				},
				files: [
					{ src: 'assets/css/admin/admin-style.css', dest: 'assets/css/admin/admin-style-rtl.css' },
					{ src: 'assets/css/global/style.css', dest: 'assets/css/global/style-rtl.css' },
					{ src: 'assets/css/site/front-style.css', dest: 'assets/css/site/front-style-rtl.css' }
				]
			}
		},

		cssmin: {
			options: {
				// banner: '/*! <%= pkg.title %> - v<%= pkg.version %> - <%= grunt.template.today("yyyy-mm-dd") %>' +
				// 	' | <%= pkg.homepage %>' +
				// 	' | Copyright (c) <%= grunt.template.today("yyyy") %> <%= pkg.author.name %>' +
				// 	' | Licensed <%= pkg.license %>' +
				// 	' */'
			},

			minify: {
				expand: true,
				src: [
					'assets/css/admin/admin-style.css',
					'assets/css/global/style.css',
					'assets/css/site/front-style.css',
					'assets/css/admin/admin-style-rtl.css',
					'assets/css/global/style-rtl.css',
					'assets/css/site/front-style-rtl.css'
				],
				// dest: '',
				ext: '.min.css'
			}
		},

		usebanner: {
			taskName: {
				options: {
					position: 'top',
					banner: '/*!\n' +
						' * <%= pkg.title %> - v<%= pkg.version %> - <%= grunt.template.today("yyyy-mm-dd") %>\n' +
						' * <%= pkg.homepage %>\n' +
						' * Copyright (c) <%= grunt.template.today("yyyy") %>\n' +
						' * Licensed GPLv2+\n' +
						' */\n',
					linebreak: true
				},
				files: {
					src: [
						'assets/css/admin/admin-style.css',
						'assets/css/global/style.css',
						'assets/css/site/front-style.css',
						'assets/css/admin/admin-style-rtl.css',
						'assets/css/global/style-rtl.css',
						'assets/css/site/front-style-rtl.css'
					],
				}
			}
		},

		jshint: {
			all: [
				'assets/js/script.js'
			],
			options: {
				curly   : true,
				eqeqeq  : true,
				immed   : true,
				latedef : true,
				newcap  : true,
				noarg   : true,
				sub     : true,
				unused  : true,
				undef   : true,
				boss    : true,
				eqnull  : true,
				globals : {
					"google":false,
					exports : true,
					module  : false
				},
				predef  :['document','window','jQuery','cmb2_ext_l10','wp','tinyMCEPreInit','tinyMCE','console','postboxes','pagenow', 'QTags', 'quicktags', '_']
			}
		},

		uglify: {
			all: {
				files: {
					'assets/js/script.min.js': ['assets/js/script.js']
				},
				options: {
					// banner: '/*! <%= pkg.title %> - v<%= pkg.version %> - <%= grunt.template.today("yyyy-mm-dd") %>\n' +
					// 	' * <%= pkg.homepage %>\n' +
					// 	' * Copyright (c) <%= grunt.template.today("yyyy") %>;' +
					// 	' * Licensed GPLv2+' +
					// 	' */\n',
					mangle: false
				}
			}
		},

		watch: {

			css: {
				tasks: ['styles'],
				options: {
					spawn: false,
				},
			},

			scripts: {
				files: ['assets/js/script.js'],
				tasks: ['js'],
				options: {
					debounceDelay: 500
				}
			},

			other: {
				files: [ '*.php', '**/*.php', '!node_modules/**', '!tests/**' ],
				tasks: [ 'makepot' ]
			}

		},

	});


	// Register tasks
	var asciify = ['asciify'];
	var styles  = ['cssjanus', 'cssmin'];
	var hint    = ['jshint'];
	var js      = ['jshint', 'uglify'];
	var tests   = ['jshint', 'phpunit'];

	grunt.registerTask( 'styles', asciify.concat( styles ) );
	grunt.registerTask( 'css', asciify.concat( styles ) );
	grunt.registerTask( 'hint', asciify.concat( hint ) );
	grunt.registerTask( 'js', asciify.concat( js ) );
	grunt.registerTask( 'tests', asciify.concat( tests ) );
	grunt.registerTask( 'default', asciify.concat( styles, js, tests ) );

	// Checktextdomain and makepot task(s)
	grunt.registerTask( 'build:i18n', asciify.concat( ['checktextdomain', 'makepot', 'newer:potomo'] ) );

};