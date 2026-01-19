import { defineConfig } from 'vitest/config'
import { playwright } from '@vitest/browser-playwright'
import containerDIPlugin from '@enhavo/app/vite/rollup-plugin-container-di'
import vue from "@vitejs/plugin-vue";

export default defineConfig({
    test: {
        browser: {
            provider: playwright(),
            enabled: true,
            headless: true,
            instances: [
                { browser: 'chromium' },
            ],
        },
    },
    plugins: [
        vue(),
        containerDIPlugin(),
    ],
})
