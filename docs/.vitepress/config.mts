import {defineConfig} from 'vitepress'
import {vitepressDemoPlugin} from 'vitepress-demo-plugin/markdown';
import { tabsMarkdownPlugin } from 'vitepress-plugin-tabs'
import { fantasticon } from '@enhavo/framework/vite/fantasticon-plugin/plugin.js'
import { fantasticonSetting } from '@enhavo/ui/icon/fantasticon-settings.js'

export default defineConfig({
    vite: {
        plugins: [
            // need fixes:
            // fantasticon(fantasticonSetting({})),
        ],
        css: {
            preprocessorOptions: {
                scss: {
                    api: 'modern-compiler',
                },
            },
        },
    },
    title: "Enhavo Docs",
    description: "Official enhavo documentation",
    themeConfig: {
        nav: [
            {text: 'Guides', link: '/guides/'},
            {text: 'Book', link: '/book/'},
            {text: 'Reference', link: '/reference/'},
            {text: 'UI', link: '/ui/'},
        ],
        search: {
            provider: 'local'
        },

        sidebar: {
            '/guides/': [
                {
                    text: 'Guides',
                    items: [
                        { text: 'Get Started', link: '/guides/get-started/index' },
                        { text: 'Contribute', link: '/guides/contribute/index' },
                        { text: 'Action', link: '/guides/action/index' },
                        { text: 'Assets', link: '/guides/assets/index' },
                        { text: 'Batch', link: '/guides/batch/index' },
                        { text: 'Block', link: '/guides/block/index' },
                        { text: 'Column', link: '/guides/column/index' },
                        { text: 'Contact', link: '/guides/contact/index' },
                        { text: 'Controller', link: '/guides/controller/index' },
                        { text: 'Dashboard Widget', link: '/guides/dashboard-widget/index' },
                        { text: 'Filter', link: '/guides/filter/index' },
                        { text: 'Form', link: '/guides/form/index' },
                        { text: 'Icon', link: '/guides/icon/index' },
                        { text: 'Media', link: '/guides/media/index' },
                        { text: 'Migration', link: '/guides/migration/index' },
                        { text: 'Resource', link: '/guides/resource/index' },
                        { text: 'Routing', link: '/guides/routing/index' },
                        { text: 'Search', link: '/guides/search/index' },
                        { text: 'Setting', link: '/guides/setting/index' },
                        { text: 'Structured Data', link: '/guides/structured-data/index' },
                        { text: 'Toolbar Widget', link: '/guides/toolbar-widget/index' },
                        { text: 'View', link: '/guides/view/index' },
                        { text: 'Vue Form', link: '/guides/vue-form/index' },
                    ]
                },
            ],
            '/reference/': [
                {
                    text: 'Reference',
                    items: [
                        { text: 'Action', link: '/reference/action/index' },
                        { text: 'Block', link: '/reference/block/index' },
                        { text: 'Column', link: '/reference/column/index' },
                        { text: 'Duplicate', link: '/reference/duplicate/index' },
                        { text: 'Filter', link: '/reference/filter/index' },
                        { text: 'Form', link: '/reference/form/index' },
                        { text: 'Icon', link: '/reference/icon/index' },
                        { text: 'Make', link: '/reference/make/index' },
                        { text: 'Media Filter', link: '/reference/media-filter/index' },
                        { text: 'Menu', link: '/reference/menu/index' },
                        { text: 'Newsletter Storage', link: '/reference/newsletter-storage/index' },
                        { text: 'Routing Generator', link: '/reference/routing-generator/index' },
                        { text: 'Search Filter', link: '/reference/search-filter/index' },
                        { text: 'Search Index', link: '/reference/search-index/index' },
                        { text: 'Template Expression', link: '/reference/template-expression/index' },
                        { text: 'Toolbar Widget', link: '/reference/toolbar-widget/index' },
                        { text: 'Translation', link: '/reference/translation/index' },
                        { text: 'Twig Filter', link: '/reference/twig-filter/index' },
                        { text: 'Twig Function', link: '/reference/twig-function/index' },
                        { text: 'UI', link: '/reference/ui/index' },
                        { text: 'Validation', link: '/reference/validation/index' },
                        { text: 'Vue Form', link: '/reference/vue-form/index' },
                    ]
                },
            ],
            '/book/': [
                {
                    text: 'General',
                    items: [
                        { text: 'Introduction', link: '/book/general/introduction' },
                        { text: 'Bundle Structure', link: '/book/general/bundle-structure' },
                        { text: 'Headless Controller', link: '/book/general/headless-controller' },
                        { text: 'Vite', link: '/book/general/vite' },
                        { text: 'Area', link: '/book/general/area' },
                        { text: 'Template Mode', link: '/book/general/template-mode' },
                        { text: 'Working with Vue', link: '/book/general/working-with-vue' },
                        { text: 'Template Development', link: '/book/general/template-development' },
                    ]
                },
                {
                    text: 'Packages',
                    items: [
                        { text: 'Dependency Injection', link: '/book/packages/dependency-injection/index' },
                    ]
                },
                {
                    text: 'Components',
                    items: [
                        { text: 'Metadata', link: '/book/components/metadata/index' },
                        { text: 'Type', link: '/book/components/type/index' },
                    ]
                },
                {
                    text: 'Bundles',
                    items: [
                        { text: 'Api', link: '/book/bundles/api-bundle/index' },
                        { text: 'App', link: '/book/bundles/app-bundle/index' },
                        { text: 'Article', link: '/book/bundles/article-bundle/index' },
                        { text: 'Block', link: '/book/bundles/block-bundle/index' },
                        { text: 'Calendar', link: '/book/bundles/calendar-bundle/index' },
                        { text: 'Comment', link: '/book/bundles/comment-bundle/index' },
                        { text: 'Contact', link: '/book/bundles/contact-bundle/index' },
                        { text: 'Content', link: '/book/bundles/content-bundle/index' },
                        { text: 'Dashboard', link: '/book/bundles/dashboard-bundle/index' },
                        { text: 'Doctrine Extension', link: '/book/bundles/doctrine-extension-bundle/index' },
                        { text: 'Form', link: '/book/bundles/form-bundle/index' },
                        { text: 'Media', link: '/book/bundles/media-bundle/index' },
                        { text: 'Media Library', link: '/book/bundles/media-library-bundle/index' },
                        { text: 'Newsletter', link: '/book/bundles/newsletter-bundle/index' },
                        { text: 'Page', link: '/book/bundles/page-bundle/index' },
                        { text: 'Redirect', link: '/book/bundles/redirect-bundle/index' },
                        { text: 'Resource', link: '/book/bundles/resource-bundle/index' },
                        { text: 'Revision', link: '/book/bundles/revision-bundle/index' },
                        { text: 'Routing', link: '/book/bundles/routing-bundle/index' },
                        { text: 'Search', link: '/book/bundles/search-bundle/index' },
                        { text: 'Setting', link: '/book/bundles/setting-bundle/index' },
                        { text: 'Taxonomy', link: '/book/bundles/taxonomy-bundle/index' },
                        { text: 'Template', link: '/book/bundles/template-bundle/index' },
                        { text: 'Translation', link: '/book/bundles/translation-bundle/index' },
                        { text: 'User', link: '/book/bundles/user-bundle/index' },
                        { text: 'Vue Form', link: '/book/bundles/vue-form-bundle/index' },
                    ]
                },
            ],
            '/ui/': [
                {
                    text: 'User Interface',
                    items: [
                        { text: 'Icons', link: '/ui/icons/index' },
                        { text: 'Toolbar', link: '/ui/toolbar/index' },
                    ]
                },
            ],
        },

        socialLinks: [
            {icon: 'github', link: 'https://github.com/enhavo/enhavo'}
        ]
    },
    markdown: {
        config(md) {
            md.use(tabsMarkdownPlugin)
            md.use(vitepressDemoPlugin, {
                locale: {
                    'en-US': 'en-US',
                },
            });
        },
    },
})
