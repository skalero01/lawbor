import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { viteStaticCopy } from 'vite-plugin-static-copy';
import * as packages from './package.json';
import fsExtra from 'fs-extra'; 
import { join, dirname } from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // Resources paths 
                'resources/css/app.css', 
                'resources/sass/app.scss', 
                'resources/js/app.js',

                // Resources assets js file paths
                'resources/assets/js/custom-switcher.js',
                'resources/assets/js/defaultmenu.js',
                'resources/assets/js/simplebar.js',
            ],
            refresh: true,
        }),

        viteStaticCopy({
            targets: [
                {
                    src: ([
                        'resources/assets/images/', 
                        'resources/assets/js/main.js', 
                        'resources/assets/js/sticky.js', 
                        'resources/assets/js/switch.js',
                    ]),
                    dest: 'assets/'
                }
            ]
        }),

        {
            // Use a custom plugin for copying only specific files we need
            name: 'copy-dist-files',
            writeBundle: async () => {
                const destDir = 'public/build/assets/libs';  // Destination directory
                
                // Define specific files to copy with their source and destination paths
                const filesToCopy = [
                    // @popperjs/core
                    {
                        src: 'node_modules/@popperjs/core/dist/umd/popper.min.js',
                        dest: `${destDir}/@popperjs/core/umd/popper.min.js`
                    },
                    // node-waves
                    {
                        src: 'node_modules/node-waves/dist/waves.min.js',
                        dest: `${destDir}/node-waves/waves.min.js`
                    },
                    {
                        src: 'node_modules/node-waves/dist/waves.min.css',
                        dest: `${destDir}/node-waves/waves.min.css`
                    },
                    // preline
                    {
                        src: 'node_modules/preline/dist/preline.js',
                        dest: `${destDir}/preline/preline.js`
                    },
                    // simplebar
                    {
                        src: 'node_modules/simplebar/dist/simplebar.min.js',
                        dest: `${destDir}/simplebar/simplebar.min.js`
                    },
                    {
                        src: 'node_modules/simplebar/dist/simplebar.min.css',
                        dest: `${destDir}/simplebar/simplebar.min.css`
                    }
                ];
      
                // Ensure destination directories exist
                for (const file of filesToCopy) {
                    const destDir = dirname(file.dest);
                    await fsExtra.ensureDir(destDir);
                }
                
                // Copy each file individually
                for (const file of filesToCopy) {
                    if (await fsExtra.pathExists(file.src)) {
                        await fsExtra.copy(file.src, file.dest, {
                            overwrite: true
                        });
                        console.log(`Copied: ${file.src} -> ${file.dest}`);
                    } else {
                        console.warn(`Source file not found: ${file.src}`);
                    }
                }
            },
        },

        {
            name: 'blade',
            handleHotUpdate({ file, server }) {
                if (file.endsWith('.blade.php')) {
                    server.ws.send({
                        type: 'full-reload',
                        path: '*',
                    });
                }
            },
        }
    ],
    css: {
      preprocessorOptions: {
          scss: {
            api: 'modern-compiler',
          },
      },
    },
    build: {
        chunkSizeWarningLimit: 1600,
        outDir: 'public/build',
        emptyOutDir: true,
    },

});
