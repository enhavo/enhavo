## Inherit Options


### label

**type**: `string`

Overwrite the default label. It will be translated over the translation
service automatically (See translationDomain)

``` yaml
actions:
    myAction:
        label: myLabel
        # ... further option
```

### translation_domain

**type**: `string`

Overwrites the default translationDomain. The selected bundle implements
a translation service for automatic translation all translatable
designations, e.g. the label

``` yaml
actions:
    myAction:
        translation_domain: myTranslationDomain
        # ... further option
```

### confirm

**type**: `boolean`

If value is true, the action must be confirmed again by the user.

``` yaml
actions:
    myAction:
        confirm: true|false
        # ... further option
```

### confirm_changes

**type**: `boolean`

If this value is true, the form registers changes made and the user must
confirm that he wants to continue despite the changes made

``` yaml
actions:
    myAction:
        confirm_changes: true|false
        # ... further option
```

### confirm_label_cancel

**type**: `string`

Overwrites the default cancel-button lettering in the confirm modal
window. It will be translated over the translation service automatically
(See translationDomain)

``` yaml
actions:
    myAction:
        confirm_label_cancel: myMessage
        # ... further option
```

### confirm_label_ok

**type**: `string`

Overwrites the default confirm-button lettering in the confirm modal
window. It will be translated over the translation service automatically
(See translationDomain)

``` yaml
actions:
    myAction:
        confirm_label_ok: myMessage
        # ... further option
```


### confirm_message

**type**: `string`

Overwrites the default message in the confirm modal window. It will be
translated over the translation service automatically (See
translationDomain)

``` yaml
actions:
    myAction:
        confirm_message: myMessage
        # ... further option
```

### hidden

**type**: `boolean`

If value set true, the action is hidden

``` yaml
actions:
    myAction:
        hidden: true|false
        # ... further option
```

### icon

**type**: `string`

Overwrite the default icon. The Icon is part of the clickable button.

``` yaml
actions:
    myAction:
        icon: myIcon
        # ... further option
```

### permission

**type**: `string`

Defines the minimum access rights a user needs to use this action.

``` yaml
actions:
    myAction:
        permission: myPermission
        # ... further options
```

### route

**type**: `string`

Defines which route should be used for the overlay.

``` yaml
actions:
    myAction:
        route: my_route
```

### route_parameters

**type**: `array`

If route is defined, you can overwrite the standard parameters to
generate your own url.

``` yaml
actions:
    myAction:
        route_parameters:
            id: $id
```

### view_key

**type**: `string`

Set the enhavo view key of the new target window

``` yaml
actions:
    myAction:
        view_key: 'edit-view'
        # ... further option
```

