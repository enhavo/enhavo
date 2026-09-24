import DefaultTheme from 'vitepress/theme'
import type { Theme } from 'vitepress'
import { enhanceAppWithTabs } from 'vitepress-plugin-tabs/client'
// import "fantasticon:icon";
import "@enhavo/ui/styles.scss"

export default {
    extends: DefaultTheme,
    enhanceApp({ app }) {
        enhanceAppWithTabs(app)
    },
} satisfies Theme
