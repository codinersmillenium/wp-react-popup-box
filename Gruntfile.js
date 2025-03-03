'use strict';
const sass = require('sass');
const fs = require('fs');
const path = require('path');
const tailwindcss = require('tailwindcss');
const autoprefixer = require('autoprefixer');

// get config path bundle js
const assetConfig = require( path.resolve(__dirname, 'assets/config.json') );
const jsDir = assetConfig.dev + '/pages';
const jsDirBuild = assetConfig.build;

module.exports = function(grunt) {

    // init file js for frontend/backend
    var jsFiles = {};
    Object.entries(assetConfig.output).map(([key, value]) => {
        fs.readdirSync(`${jsDir}/${key}`).forEach(file => {
            if (path.extname(file) === '.jsx') {
                jsFiles[`${assetConfig.build}/${value}`] = `${jsDir}/${key}/${file}`;
            }
        });
    });

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        // config default postcss tailwind autoprefixer
        postcss: {
            options: {
              map: true,
              processors: [
                tailwindcss,
                autoprefixer
              ]
            },
            dist: {
                files: {
                    [`${jsDirBuild}/css/tailwind.css`]: `${assetConfig.style}/tailwind.scss`
                }
            }
        },

        // config default sass
        sass: {
            options: {
                implementation: sass
            },
            dist: {
                files: {
                    [`${jsDirBuild}/css/style.css`]: `${assetConfig.style}/style.scss`
                }
            }
        },

        // compile jsx to js use browserify
        browserify: {
            dist: {
                options: {
                    transform: [
                        ['babelify', { presets: ['@babel/preset-env', '@babel/preset-react'] }]
                    ]
                },
                files: jsFiles,
                browserifyOptions: {
                    debug: true
                }
            }
        },

        // config terser for compress js
        terser: {
            options: {
              compress: true,
              mangle: true,
              output: {
                comments: false
              }
            },
            dist: {
                files: (() => {
                    let assets = {};
                    Object.entries(assetConfig.output).map(([_key, value]) => {
                        let path = `${jsDirBuild}/${value}`;
                        assets[path] = [path];
                    })
                    return assets;
                })()
            }
        },

        // disabled, replaced with a more optimal terser
        // config uglify for compress js ()
        // uglify: {
		// 	options: { sourceMap: false },
		// 	build: {
		// 		files: (() => {
		// 			let assets = {};
        //             Object.entries(assetConfig.output).map(([_key, value]) => {
        //                 let path = `${jsDirBuild}/${value}`;
		// 				assets[path] = [path];
        //             })
		// 			return assets;
		// 		})()
		// 	},
		// },

        // grunt watch
        watch: {
            css: {
                files: [`${assetConfig.style}/*.scss`],
                tasks: ['sass']
            },
            js: {
                files: [`${assetConfig.dev}/**/*.jsx`],
                tasks: ['browserify']
            }
        }
    });


    // grunt load task
    grunt.loadNpmTasks('grunt-sass');
    grunt.loadNpmTasks('grunt-browserify');
    grunt.loadNpmTasks('grunt-terser');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('@lodder/grunt-postcss');

    // grunt register task (default)
    grunt.registerTask('default', ['sass', 'postcss', 'browserify', 'terser', 'watch']);    
};