import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import liveReload from 'vite-plugin-live-reload'
import path from 'node:path'
import 'dotenv/config'
import containerDIPlugin from '@enhavo/framework/vite/rollup-plugin-container-di'
import {jqueryGlobalPlugin} from '@enhavo/framework/vite/jquery-global-plugin.js'

export default defineConfig({
    optimizeDeps: {
        include: [
            'vuedraggable',
            'jquery',
            'select2',
            'icheck',
        ],
    },
    plugins: [
        jqueryGlobalPlugin(['icheck', 'select2']),
        vue(),
        liveReload([
            import.meta.dirname + '/../../src/**/*.php',
            import.meta.dirname + '/../../templates/**/*.twig',
        ]),
        containerDIPlugin(),
    ],

    // config
    root: path.resolve(import.meta.dirname),
    base: '/build/theme',
    build: {
        // output dir for production build
        outDir: '../../public/build/theme',
        emptyOutDir: true,
        chunkSizeWarningLimit: 1024,

        // emit manifest so PHP can find the hashed files
        manifest: true,

        rollupOptions: {
            input: '/entrypoints/main.js'
        },
    },
    server: {
        strictPort: true,
        port: process.env.VITE_THEME_PORT,
        origin: 'http://localhost:' + process.env.VITE_THEME_PORT,
        cors: true,
    },
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js'
        }
    },
    css: {
        preprocessorOptions: {
            scss: {
                api: 'modern-compiler' // or "modern"
            }
        }
    }
})
