## url

**url(string url)**

The `url` is mandatory for every url you define in your data. Because in the dev environment we need add a prefix to every link.

```json
{
  "resource": {
    "id": 1,
    "title": "Home",
    "url": "expr:url('page/homepage')"
  }
}
