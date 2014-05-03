module.exports = function (grunt) {
  'use strict';

  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),

    // Watch for changes and trigger compass with livereload on CSS files.
    watch: {
      scss: {
        options: {
          livereload: false
        },
        files: ['css/sass/*.scss'],
        tasks: ['compass:dev','compass:prod']
      },
      css: {
        files: ['css/*.css','css/*.css'],
        options: {
          livereload: true
        },
      },
      jshint: {
        files: ['Gruntfile.js' ,'js/*.js'],
        tasks: ['jshint'],
      }
    },

    // Checkstyle on javascript with jshint.
    jshint: {
      files: ['Gruntfile.js' ,'js/*.js'],
      options: {
        reporter: require('jshint-stylish'),
        curly: true,
        eqeqeq: true,
        eqnull: true,
        browser: true,
        globals: {
          jQuery: true
        }
      }
    },

    // Compass and SCSS
    compass: {
      options: {
        httpPath: '/themes/santa',
        cssDir: 'css_dev',
        sassDir: 'css/sass',
        imagesDir: 'images',
        javascriptsDir: 'scripts',
        fontsDir: 'css/fonts',
        assetCacheBuster: 'none'
      },
      dev: {
        options: {
          environment: 'development',
          outputStyle: 'expanded',
          relativeAssets: true,
          raw: 'line_numbers = :true\n'
        }
      },
      prod: {
        options: {
          environment: 'production',
          outputStyle: 'compact',
          force: true,
          cssDir: 'css',
        }
      }
    }
  });

  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-compass');
  grunt.loadNpmTasks('grunt-contrib-jshint');

  grunt.registerTask('build', [
    'compass:prod'
  ]);

  grunt.registerTask('default', [
    'compass:dev',
    'watch',
    'jshint'
  ]);
};
