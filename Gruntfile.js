module.exports = function(grunt) {
  
  grunt.initConfig({
    useminPrepare: {
      html: ['app/views/editor.php']
    },
    concat: {
      generated: {
        files: [
          {
            dest: 'public/js/otworlds.concat.js',
            src: [
              'public/js/otworlds.mapeditor.js',
              'public/js/otworlds.materials.js',
              'public/js/otworlds.tile.js',
              'public/js/otworlds.tiles.js',
              'public/js/otworlds.multiplayer.js'
            ]
          }
        ]
      }
    },
    uglify: {
      generated: {
        files: 
          {
            'public/js/otworlds.min.js': 'public/js/otworlds.concat.js'
          }
        
      }
    },
    usemin: {
      html: ['app/views/editor.php']
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