## Filter data

If you want to add additional filter data, that you will use during a search, you have to also provide metadata for it.

```yaml
enhavo_search:
    metadata:
        App\Entity\Book:
        	filter:
                category:
                    type: text
                    property: category.name
```

Like in index, you have to use the `property` config explizit, the key will not be enough. Here we just use a text filter
with property chain, because category itself is also an entity.

You can find more types in the [reference section](/reference/search-filter/index.md) or [write your own type](/guides/search/index.md#create-filter-type).
