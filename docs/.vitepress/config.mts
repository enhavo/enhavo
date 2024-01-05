import { defineConfig } from 'vitepress'

export default defineConfig({
  title: "Enhavo Docs",
  description: "Official enhavo documentation",
  themeConfig: {
    nav: [
      { text: 'Home', link: '/' },
      { text: 'Get Started', link: '/get-started/' },
      { text: 'Book', link: '/book/' },
      { text: 'Guides', link: '/guides/' },
      { text: 'Reference', link: '/reference/' },
      { text: 'Contribute', link: '/contribute/' },
    ],

    sidebar: {
      '/guides/': [
        {
          text: 'Guide',
          items: [
            { text: 'Action', link: '/guides/action/' },
          ]
        }
      ],
    },

    socialLinks: [
      { icon: 'github', link: 'https://github.com/enhavo/enhavo' }
    ]
  }
})
