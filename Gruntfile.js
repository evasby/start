module.exports = function(grunt) {

    // 1. Вся настройка находится здесь
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        /*concat: {
            // 2. Настройка для объединения файлов находится тут
            dist: {
                src: [
                    'js/libs/*.js', // Все JS в папке libs
                    'js/script.js'  // Конкретный файл
                ],
                dest: 'js/build/production.js',
            }
        },*/
        /*uglify: {
            build: {
                src: 'js/build/production.js',
                dest: 'js/build/production.min.js'
            }
        },*/    
        sass: {
            dist: {
                options: {
                    style: 'expanded',
                    noCache: true
                },
                files: {
                    'css/all.css': 'sass/all.scss',
                    'css/all-old-ie.css': 'sass/all-old-ie.scss',
                }
            }
        },
        /*ftp_upload: {
            build: {
                auth: {
                    host: 'wfs.by',
                    port: 21,
                    authKey: 'key1'
                },
                src: ['php/index.php', 'css/all.css', 'css/all-old-ie.css'],
                dest: ['home/evasby/www/wfs.by/css'],
                exclusions: ['.gitignore', '.ftppass']
            }
        },*/
        ftp_push: {
			    css: {
                    options: {
                        authKey: 'key1',
                        host: 'wfs.by',
                        dest: '/home/evasby/www/start.wfs.by/layout',
                        //dest: '/home/evasby/www/iphone.wfs.by/sites/all/themes/iphone',
                        port: 21
                    },
                    files: [
                        {
                            expand: true,
                            cwd: '.',
                            src: [
                                'css/all.css',
                                'css/all-old-ie.css',
                            ]
                        }
                    ]
			    },
                php: {
                    options: {
                        authKey: 'key1',
                        host: 'wfs.by',
                        dest: '/home/evasby/www/start.wfs.by/layout',
                        port: 21
                    },
                    files: [
                        {
                            expand: true,
                            cwd: '.',
                            src: [
                                '*.php'
                            ]
                        }
                    ]
                },
                js: {
                    options: {
                        authKey: 'key1',
                        host: 'wfs.by',
                        dest: '/home/evasby/www/start.wfs.by/layout',
                        port: 21
                    },
                    files: [
                        {
                            expand: true,
                            cwd: '.',
                            src: [
                                'js/*.js'
                            ]
                        }
                    ]
                }
			  },
        watch: {
            css: {
                files: ['sass/*.scss'],
                tasks: ['sass', 'ftp_push:css'],
                options: {
                    spawn: false,
                }
            },
            php: {
                files: ['*.php'],
                tasks: ['ftp_push:php'],
                options: {
                    spawn: false,
                }
            },
            js: {
                files: ['js/*.js'],
                tasks: ['ftp_push:js'],
                options: {
                    spawn: false,
                }
            }
        }
    });
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-ftp-upload');
    grunt.loadNpmTasks('grunt-ftp-push');
    //grunt.registerTask('default', ['sass', 'ftp_push', 'watch']);
    grunt.registerTask('default', ['sass', 'watch']);
};