module.exports = function(grunt) {

    grunt.initConfig({

        /*jshint: {
            files: ['web/js/src/loader.js'],
            options: {
                // more options here if you want to override JSHint defaults
                globals: {
                    jQuery: true,
                    console: true,
                    module: true
                }
            }
        },*/

        uglify: {
            my_target: {
                files: {
                    'web/js/main.min.js': [
                        'web/js/src/main.js',
                        'web/js/src/canvas.js',
                        'web/js/src/loader.js'
                    ]
                }
            }
        }

        // concat: {}
    });

    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');

    grunt.registerTask('default', ['uglify']);
};