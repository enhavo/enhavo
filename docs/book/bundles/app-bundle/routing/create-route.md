## Create Route

The create route is being called to render the form for creating a new
resource and subsequently to save the form settings.

Here is an example route displaying possible parameters:

```yaml
enhavo_page_page_create:
    path: /admin/enhavo/page/page/create
    methods: [GET,POST]
    options:
        expose: true
    defaults:
        _controller: enhavo_page.controller.page:createAction
        _sylius:
            template: EnhavoAppBundle:Resource:create.html.twig
            viewer:
                type: create
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
                form:
                    template: EnhavoAppBundle:View:tab.html.twig
                    theme: ~
                    action: enhavo_page_page_create
                sorting:
                    sortable: false
                    position: position
                    initial: max
```
