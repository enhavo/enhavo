import { defineConfig, splitVendorChunkPlugin } from 'vite'
import vue from '@vitejs/plugin-vue'
import liveReload from 'vite-plugin-live-reload'
import path from 'node:path'
import 'dotenv/config'
import containerDIPlugin from '@enhavo/app/vite/rollup-plugin-container-di'

export default defineConfig({

    plugins: [
        vue(),
        liveReload([
            __dirname + '/../../src/**/*.php',
            __dirname + '/../../templates/**/*.twig',
        ]),
        splitVendorChunkPlugin(),
        containerDIPlugin(),
    ],

    // config
    root: path.resolve(__dirname),
    base: '/build/theme',
    build: {
        // output dir for production build
        outDir: '../../public/build/theme',
        emptyOutDir: true,

        // emit manifest so PHP can find the hashed files
        manifest: true,

        rollupOptions: {
            input: '/entrypoints/main.js'
        }
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
