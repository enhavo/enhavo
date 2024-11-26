## Menu

The main menu in the admin backend is configurable via the parameter
`enhavo_app.menu` in the configuration file

```yaml
# config/package/enhavo_app.yaml
enhavo_app:
    menu:
        book:
            type: link
            label: Book
            route: app_admin_book_index
            role: ROLE_APP_BOOK_INDEX
```
