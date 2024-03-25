## Wysiwyg

This section explains how you can configure your wysiwyg editor
system-wide and in some special cases. Enhavo uses the `TinyMCE` editor,
so if you are familiar with its settings you should easily be able to
configure it. Otherwise you should also read the TinyMCE [configuration
docs](http://www.tinymce.com/wiki.php/Configuration).

### Configuration

You can find the system wide configurations under
`app/config/wysiwyg.yml`. All settings here are equivalent to the
`TinyMCE` configuration, mentioned above. Note that this is a yaml file
while the TinyMCE configuration is a Javascript Object or JSON, but yaml
can easily be converted to JSON.

These configurations are available:

-   [height](https://www.tinymce.com/docs/configure/editor-appearance/#height)
-   [toolbarN](https://www.tinymce.com/docs/configure/editor-appearance/#toolbarn)
-   [style_formats](https://www.tinymce.com/docs/configure/content-formatting/#style_formats)
-   [formats](https://www.tinymce.com/docs/configure/content-formatting/#formats)
-   [content_css](https://www.tinymce.com/docs/configure/content-appearance/#content_css)

```yaml
height: 150

toolbar1: "undo redo | bold italic underline strikethrough subscript superscript removeformat | link styleselect"

toolbar2: "table | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | code"

style_formats:
    - {title: 'Bold text', inline: 'b'}
    - {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}}
    - {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}}
    - {title: 'Example 1', inline: 'span', classes: 'example1'}
    - {title: 'Example 2', inline: 'span', classes: 'example2'}
    - {title: 'Table styles'}
    - {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}

formats:
      alignleft: {selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes: 'left'}
      aligncenter: {selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes: 'center'}
      alignright: {selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes: 'right'}
      alignfull: {selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes: 'full'}
      bold: {inline: 'span', 'classes': 'bold'}
      italic: {inline: 'span', 'classes': 'italic'}
      underline: {inline: 'span', 'classes': 'underline', exact: true}
      strikethrough: {inline: 'del'}
      customformat: {inline: 'span', styles: {color: '#00ff00', fontSize: '20px'}, attributes: {title: 'My custom format'}}

content_css: ~ # see below
```

**content_css**

Since we\'re using the assets/assetics commands for css file locations,
you need to use the \"@\" syntax for the configuration of `content_css`.

```yaml
#single file
content_css: '@FooBundle/Resources/public/css/styleOne.css'
```

```yaml
#multiple files
content_css:
  - '@FooBundle/Resources/public/css/styleOne.css'
  - '@FooBundle/Resources/public/css/styleTwo.css'
```

### FormType

If you need a special configuration for just one form, you can override
or filter some settings in your FormType class, in the option array.

-   **formats**: Needs an array, where you can list the formats that
    should be shown.
-   **height**: Override the height
-   **toolbar1**: Override the toolbar1
-   **toolbar2**: Override the toolbar2
-   **content_css**: Override the content_css, this could be an array or
    a string (use \"@\" syntax)

```php
$builder->add('text', 'wysiwyg', array(
    'formats' => array('Bold text', 'Red text'),
    'height' => 300,
    'toolbar1' => '',
    'toolbar2' => ''
    'content_css' => array(
        '@FooBundle/Resources/public/css/styleOne.css'
    )
);
```
