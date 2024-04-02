import { defineConfig, splitVendorChunkPlugin } from 'vite'
import vue from '@vitejs/plugin-vue'
import liveReload from 'vite-plugin-live-reload'
import path from 'node:path'
import 'dotenv/config'
import containerDIPlugin from '@enhavo/dependency-injection/rollup-plugin-container-di'

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
    base: process.env.APP_ENV === 'dev' ? '/' : '/dist/',
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
        port: process.env.VITE_THEME_PORT
    },
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js'
        }
    }
})
