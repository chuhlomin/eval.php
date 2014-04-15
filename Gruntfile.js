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
            main: {
                files: {
                    'web/js/main.min.js': [
                        'bower_components/codemirror/lib/codemirror.js',
                        'bower_components/codemirror/mode/htmlmixed/htmlmixed.js',
                        'bower_components/codemirror/mode/xml/xml.js',
                        'bower_components/codemirror/mode/javascript/javascript.js',
                        'bower_components/codemirror/mode/css/css.js',
                        'bower_components/codemirror/mode/clike/clike.js',
                        'bower_components/codemirror/mode/php/php.js',
                        'bower_components/browserdetection/src/browser-detection.js',
                        'bower_components/KeyboardJS/keyboard.js',
                        'web/js/src/main.js',
                        'web/js/src/canvas.js',
                        'web/js/src/loader.js'
                    ]
                }
            }
        },

        cssmin: {
            combine: {
                files: {
                    'web/css/main.min.css': [
                        'bower_components/codemirror/lib/codemirror.css',
                        'web/css/custom.css'
                    ]
                }
            }
        }

        // concat: {}
    });

    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-cssmin');

    grunt.registerTask('default', ['uglify', 'cssmin']);
};