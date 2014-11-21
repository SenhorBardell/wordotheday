module.exports = function(grunt) {
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		jst: {
			compile: {
				options: {
					namespace: 'window.App.JST',
					processName: function(filepath) {
						return filepath.substring(7, filepath.length -5)
					}
				},
				files: {
					"js/jst.js": ["js/jst/**/*.html"]
				}
			}
		},
		concat: {
			backbone: {
				src: [
					'js/main.js',
					'js/jst.js',
					'js/models/*.js', 
					'js/collections/*.js', 
					'js/views/*.js',
					'js/router.js'
				],
				dest: 'backbone.js'
			}
		}, 
		uglify: {
			options: {
				compress: {
					drop_console: true
				}
			},
			my_target: {
				files: {
					'script.min.js': ['backbone.js'] 
				}
			}
		}
	});

	grunt.loadNpmTasks('grunt-contrib-jst');
	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-uglify');

	grunt.registerTask('default', ['jst', 'concat', 'uglify']);
};