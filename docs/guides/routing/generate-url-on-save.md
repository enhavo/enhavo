## Generate url on save

You can also generate a url on save with the `EnhavoRoutingBundle`. To
do so, you need to make your entity ready for dynamic routing and add
meta informations for the generation in your config.

```yaml
enhavo_routing:
    classes:
        Enhavo\Bundle\ArticleBundle\Entity\Article:
            generators:
                slug:
                    type: slug
                    property: title
```
