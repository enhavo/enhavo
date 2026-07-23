## Form

To allow users to edit translations within a form, the translation bundle uses form hooks and extensions
to inject and overwrite additional form fields. After the form is submitted, the translation data is stored
into a buffer and only saved to the database on the next Doctrine flush.

![image](/images/translation-form-data.png)

### Access control

The hooks are only applied to forms that are configured in the access control.
You can use `access_control` to restrict or allow specific routes using regex patterns.

```yaml
# config/packages/enhavo_translation.yaml
enhavo_translation:
    form:
        default_access: true
        access_control:
            - '/^\/admin\/api\//'
```

If `default_access` is `true`, the patterns in `access_control` will exclude matching routes.
If `default_access` is `false`, the patterns will include matching routes instead.

```yaml
# only enable translation forms for specific routes
enhavo_translation:
    form:
        default_access: false
        access_control:
            - '/^\/admin\/api\/article/'
            - '/^\/admin\/api\/page/'
```
