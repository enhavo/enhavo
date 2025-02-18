## render

**render(string template, ...string loadFile)**

Render a `template`. By default, no data will be applied to the template. Use the `loadFile` argument to add data.

```json
{
  "resource": {
    "id": 1,
    "title": "Home",
    "text": "expr:render('theme/block/text.html', 'text.json', 'link.json')"
  }
}
```