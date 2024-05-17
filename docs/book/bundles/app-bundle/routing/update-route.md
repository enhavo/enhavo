## Update Route

```yaml
enhavo_page_page_update:
    options:
        expose: true
    path: /enhavo/page/page/update/{id}
    methods: [GET,POST]
    defaults:
        _controller: enhavo_page.controller.page:updateAction
        _sylius:
            template: EnhavoAppBundle:Resource:update.html.twig
            viewer:
                type: edit
                translationDomain: EnhavoPageBundle
                tabs:
                    page:
                        label: page.label.page
                        template: EnhavoContentBundle:Tab:main.html.twig
                    content:
                        label: page.label.content
                        template: EnhavoContentBundle:Tab:content.html.twig
                    meta:
                        label: page.label.meta
                        template: EnhavoContentBundle:Tab:meta.html.twig
                buttons:
                    cancel:
                        route: ~
                        display: true
                        role: ~
                        label: label.cancel
                        icon: close
                    save:
                        route: ~
                        display: true
                        role: ~
                        label: label.save
                        icon: check
                    preview:
                        route: enhavo_page_page_preview
                        display: true
                        role: ~
                        label: label.preview
                        icon: eye
                    delete:
                        route: ~
                        display: true
                        role: ~
                        label: label.delete
                        icon: trash
                form:
                    template: EnhavoAppBundle:View:tab.html.twig
                    theme: ~
                    action: enhavo_page_page_update
                    delete: enhavo_page_page_delete
```
