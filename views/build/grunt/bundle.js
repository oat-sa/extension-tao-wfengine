module.exports = function(grunt) { 

    var requirejs   = grunt.config('requirejs') || {};
    var clean       = grunt.config('clean') || {};
    var copy        = grunt.config('copy') || {};

    var root        = grunt.option('root');
    var libs        = grunt.option('mainlibs');
    var ext         = require(root + '/tao/views/build/tasks/helpers/extensions')(grunt, root);
    var out         = 'output';

    /**
     * Remove bundled and bundling files
     */
    clean.wfenginebundle = [out];
    
    /**
     * Compile tao files into a bundle 
     */
    requirejs.wfenginebundle = {
        options: {
            baseUrl : '../js',
            dir : out,
            mainConfigFile : './config/requirejs.build.js',
            paths : { 'wfEngine' : root + '/wfEngine/views/js' },
            modules : [{
                name: 'wfEngine/controller/routes',
                include : ext.getExtensionsControllers(['wfEngine']),
                exclude : ['mathJax', 'mediaElement'].concat(libs)
            }]
        }
    };

    /**
     * copy the bundles to the right place
     */
    copy.wfenginebundle = {
        files: [
            { src: [out + '/wfEngine/controller/routes.js'],  dest: root + '/wfEngine/views/js/controllers.min.js' },
            { src: [out + '/wfEngine/controller/routes.js.map'],  dest: root + '/wfEngine/views/js/controllers.min.js.map' }
        ]
    };

    grunt.config('clean', clean);
    grunt.config('requirejs', requirejs);
    grunt.config('copy', copy);

    // bundle task
    grunt.registerTask('wfenginebundle', ['clean:wfenginebundle', 'requirejs:wfenginebundle', 'copy:wfenginebundle']);
};
