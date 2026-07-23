## Form

To allow user to edit the translation within a form. The translation bundle uses form hooks and extensions 
to inject and overwrite additional form fields. After the form was submitted, the translation data is stored
into a buffer and only saved to the database on the next doctrine flush.

![image](/images/translation-form-data.png)

### Access control

The hooks are only applied to forms, that was configured in the access control before.
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
