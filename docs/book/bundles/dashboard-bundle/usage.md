## Usage

In the following you see an example on how widgets are configured in
enhavo:

```yaml
enhavo_dashboard:
    widgets:
        article_number:
            type: number
            label: Total Articles
            provider:
                type: total
                repository: enhavo_article.repository.article
```

In the example above, the widget key is `article_number`. The widget
type is `number` (you could use any other widget type as well). You can
configure the label shown with the `label` attribute.

To configure the provider, add the type and all necessary options.

See all possible options in our reference.
