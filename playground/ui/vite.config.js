import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import containerDIPlugin from '@enhavo/app/vite/rollup-plugin-container-di'

export default defineConfig({
    plugins: [
        vue(),
        containerDIPlugin(),
    ],
    publicDir: 'public',
    base: '/',
});
