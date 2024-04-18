import { defineConfig, splitVendorChunkPlugin } from 'vite'
import vue from '@vitejs/plugin-vue'
import liveReload from 'vite-plugin-live-reload'
import path from 'node:path'
import 'dotenv/config'
import containerDIPlugin from '@enhavo/app/vite/rollup-plugin-container-di'
import {fantasticon} from "@enhavo/app/vite/fantasticon-plugin/plugin.js";
import {fantasticonSetting} from "@enhavo/app/vite/fantasticon-settings.js";
import {watchNodeModules} from "@enhavo/app/vite/watch-node-modules-plugin.js";

export default defineConfig({
    optimizeDeps: {
        include: [
            'axios',
            'uuid/v4',
            'vuedraggable',
            'jquery',
            'async',
            'pako',
            'lodash',
            'ansi-to-html',
            'jexl',
            'blueimp-file-upload',
            'select2',
            'icheck',
            'tinymce',
            'tinymce/plugins/advlist',
            'tinymce/plugins/code',
            'tinymce/plugins/emoticons',
            'tinymce/plugins/emoticons/js/emojis',
            'tinymce/plugins/link',
            'tinymce/plugins/lists',
            'tinymce/plugins/table',
        ],
    },
    plugins: [
        vue(),
        liveReload([
            __dirname + '/../../templates/admin/*.twig',
        ]),
        splitVendorChunkPlugin(),
        containerDIPlugin(),
        fantasticon(fantasticonSetting({
            outputDir: path.resolve(__dirname, '../../public/build/admin'),
        })),
        watchNodeModules([
            '@enhavo/app',
            '@enhavo/api',
            '@enhavo/app',
            '@enhavo/article',
            '@enhavo/block',
            '@enhavo/calendar',
            '@enhavo/contact',
            '@enhavo/content',
            '@enhavo/core',
            '@enhavo/dashboard',
            '@enhavo/dependency-injection',
            '@enhavo/form',
            '@enhavo/media',
            '@enhavo/media-library',
            '@enhavo/multi-tenancy',
            '@enhavo/newsletter',
            '@enhavo/payment',
            '@enhavo/search',
            '@enhavo/shop',
            '@enhavo/slider',
            '@enhavo/theme',
            '@enhavo/translation',
            '@enhavo/user',
            '@enhavo/vue-form',
        ]),
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
    },
})
