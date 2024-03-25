## Custom templates

To customize the template for a block is very easy. You can just
override the template path in the configuration or if the origin
template file is located in the vendor code, you can add a file under
the same path in your `templates` directory. E.g. for the gallery
template, save a file under `theme/block/gallery.html.twig`.

```yaml
enhavo_block:
    blocks:
        gallery:
            type: gallery
            template: 'theme/block/gallery/custom.html.twig'
```

How if you want to define more templates? You can also add more
templates separated by keys. Only the key will store into the node, so
if you change the template path later, there is no migration needed.

If you define templates in that way, the user will have a dropdown field
in his block form. The choices are the labels you defined.

```yaml
enhavo_block:
    blocks:
        template:
            type: template
            template:
                chart:
                    template: 'theme/block/template/chart.html.twig'
                    label: Chart
                download:
                    template: 'theme/block/template/download.html.twig'
                    label: Download
```
