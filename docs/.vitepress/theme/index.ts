import DefaultTheme from 'vitepress/theme'
import type { Theme } from 'vitepress'
import { enhanceAppWithTabs } from 'vitepress-plugin-tabs/client'
import EToolbar from '@enhavo/ui/app/components/EToolbar.vue'
import EToolbarBrand from '@enhavo/ui/app/components/EToolbarBrand.vue'
import EToolbarLeft from '@enhavo/ui/app/components/EToolbarLeft.vue'
import EToolbarRight from '@enhavo/ui/app/components/EToolbarRight.vue'
import "@enhavo/ui/styles.scss"

export default {
    extends: DefaultTheme,
    enhanceApp({ app }) {
        enhanceAppWithTabs(app)
        app.component('e-toolbar', EToolbar)
        app.component('e-toolbar-left', EToolbarLeft)
        app.component('e-toolbar-right', EToolbarRight)
        app.component('e-toolbar-brand', EToolbarBrand)
    },
} satisfies Theme
