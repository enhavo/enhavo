## get_content

**get_content(string file)**

Read content from `file` and return it. The file must be inside the data directory.

```json
{
  "resource": {
    "id": 1,
    "title": "Home",
    "text": "expr:content('text.txt')"
  }
}
```