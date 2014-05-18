module.exports = function(grunt) {
  
  grunt.initConfig({
    useminPrepare: {
      html: ['page_main.php']
    },
    concat: {
      generated: {
        files: [
          {
            dest: 'js/otworlds.concat.js',
            src: [
              'js/otworlds.mapeditor.js',
              'js/otworlds.materials.js',
              'js/otworlds.tile.js',
              'js/otworlds.tiles.js',
              'js/otworlds.multiplayer.js'
            ]
          }
        ]
      }
    },
    uglify: {
      generated: {
        files: 
          {
            'js/otworlds.min.js': 'js/otworlds.concat.js'
          }
        
      }
    },
    usemin: {
      html: ['page_main.php']
    }
  });
  
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-cssmin');
  grunt.loadNpmTasks('grunt-usemin');
  
  grunt.registerTask('build', [
    //'useminPrepare',
    'concat',
    'uglify',
    'usemin'
  ]);
};