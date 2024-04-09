import { defineConfig, splitVendorChunkPlugin } from 'vite'
import vue from '@vitejs/plugin-vue'
import liveReload from 'vite-plugin-live-reload'
import path from 'node:path'
import 'dotenv/config'
import containerDIPlugin from '@enhavo/dependency-injection/rollup-plugin-container-di'

export default defineConfig({
    optimizeDeps: {
        include: ['axios', 'uuid/v4', 'vuedraggable', 'jquery'],
    },
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
    base: '/build/admin/',
    build: {
        // output dir for production build
        outDir: '../../public/build/admin',
        emptyOutDir: true,

        // emit manifest so PHP can find the hashed files
        manifest: true,

        rollupOptions: {
            input: '/entrypoints/application.js',
        }
    },
    server: {
        strictPort: true,
        port: process.env.VITE_ADMIN_PORT,
        origin: 'http://localhost:' + process.env.VITE_ADMIN_PORT
    },
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js'
        }
    }
})
