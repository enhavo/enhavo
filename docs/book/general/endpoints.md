# Endpoints


```
app_page:
  controller: PageController::index
  defaults:
    _expose: true


app_page:
  defaults:
    _expose: theme
    _endpoint:
      type: page
```
