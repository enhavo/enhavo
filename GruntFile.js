'use strict';

/**
 * Grunt Module
 */
module.exports = function(grunt) {

  grunt.initConfig({
    compassMultiple: {
      options : {
        relativeAssets: true,
        time: true,
        environment: 'production',
        outputStyle: 'compressed'
      },
      all: {
        options: {
          multiple: [
            {
              sassDir: 'src/Enhavo/Bundle/ThemeBundle/Resources/public/sass',
              cssDir: 'src/Enhavo/Bundle/ThemeBundle/Resources/public/css'
            },{
              sassDir: 'src/Enhavo/Bundle/AppBundle/Resources/public/sass',
              cssDir: 'src/Enhavo/Bundle/AppBundle/Resources/public/css'
            }
          ]
        }
      }
    },
    watch: {
      scripts: {
        files: 'src/Enhavo/Bundle/*Bundle/Resources/public/sass/*.scss',
        tasks: ['compassMultiple'],
        options: {
          interrupt: true
        }
      }
    }
  });

  grunt.loadNpmTasks('grunt-compass-multiple');
  grunt.loadNpmTasks('grunt-contrib-watch');

  grunt.task.registerTask('compass', ['compassMultiple']);
  grunt.task.registerTask('default', ['compass']);
};