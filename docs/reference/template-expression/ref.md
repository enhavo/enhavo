## ref

**ref(string file, string property)**

Use `ref` to load a specific value from another file. This approach helps us to keep your data at single point. Changes
at this point will also affect the other files.

For example, you have a file `/data/page/homepage.json` with following content:

```json
{
  "resource": {
    "id": 1,
    "title": "Home"
  }
}
```

And in the file `/data/navigation/main.json` you want to use the title from the homepage you use `ref`. 

```json
{
  "id": 2,
  "nodes": [
    {
      "id": 5,
      "name": "page",
      "children": [],
      "subject": {
        "title": "expr:ref('page/homepage.json', 'resource.title')"
      }
    }
  ]
}
```

The second argument of `ref` is mandatory, you can also pass a path to like `resource.block.0.title`
