## load

**load(string|array files, bool recursive = false, int depth = null)**

The expression `load` will load the data from a separate file into our file. 

```json
{
    "items": [
        "expr:load('items/first.json')",
        "expr:load('items/second.json')"
    ]
}
```

It is also possible to load multiple files using an array

```json
{
    "navigation": "expr:load(['navigation/main.json', 'navigation/main-active-contact.json'], true, 3)"
}
```

In this `/data/navigation/main.json` and  `/data/navigation/main-active-contact.json` are loaded. By default, they
will not be merged, so a later file will overwrite an existing key. Use the optional `recursive` and `depth` parameter
to change this behaviour.




