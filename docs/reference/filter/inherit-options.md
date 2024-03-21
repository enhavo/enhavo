## Inherit options

### hidden

**type**: boolean

Controls if the filter is visible. Default is `true`

```yaml
columns:
    myFilter:
        hidden: false
```

### initial_active

**type**: boolean

Controls if the filter is initially visible and doesn\'t need to be
activated via the filters dropdown first. Default is `false`

``` yaml
columns:
    myFilter:
        initial_active: true
```

### initial_value

**type**: `string|null`

If set, this filter will be initially have a set value and the list will
initially be filtered by this value. Default `null`.

``` yaml
columns:
    myFilter:
        initial_value: Foo
```

### label

**type**: `string`

The label of the filter. It will be translated over the translation
service automatically. (See translation_domain)

```yaml
columns:
    myFilter:
        label: myLabel
```

### permission

**type**: `string|null`

Symfony security role required for the user to be able to see and use
the filter. Default is `null`.

``` yaml
columns:
    myFilter:
        permission: ROLE_ENHAVO_ARTICLE_ARTICLE_EDIT
```

### property

**type**: `string`

Define which property of the resource is used for this filter. The
resource has to provide a getter method for that property.

``` yaml
filters:
    myFilter:
        property: name
```

### translation_domain

**type**: `string`

Overwrite the default translationDomain. `EnhavoAppBundle` is used by
default.

```yaml
columns:
    myFilter:
        translation_domain: myTranslationDomain
```
