### condition

**type**: string

If a condition is specified, the column will only displayed if the
condition is fulfilled.

```yaml
columns:
    myColumn:
        condition: my_condition
        # ... further option
```


### format

**type**: string

Use format to display date. You can find all possible values on the
[date](http://php.net/manual/de/function.date.php) page of the php
documentation.

```yaml
columns:
    myColumn:
        date: r
        # ... further option
```

### label

**type**: string

Overwrite the default label. It will be translated over the translation
service automatically. (See translation_domain)

```yaml
columns:
    myColumn:
        label: myLabel
        # ... further option
```


### property

**type**: string

Defines which property of the resource is used for the column. The
resource has to provide a getter method for that property.

```yaml
column:
    myColumn:
        property: myEntityProperty
```

### separator

**type**: string

Define with which char the list should be separated.

``` yaml
columns:
    myColumn:
        separator: 'mySeparator'
```

### separator

**type**: string

Define with which char the list should be separated.

``` yaml
columns:
    myColumn:
        separator: 'mySeparator'
```

### sortable

**type**: boolean

If value is true, the table can be sorted by the value in this column.

```yaml
columns:
    myColumn:
        sortable: true|false
        # ... further option
```

### sorting_property

**type**: string

Defines which property of the resource the column is sorted by. The
resource must provide a getter method for this property.

```yaml
columns:
    myColumn:
        sorting_property: myEntityProperty
        # ... further option
```

### translation_domain

**type**: string

Overwrites the default translation_domain.

```yaml
columns:
    myColumn:
        translation_domain: my_translation_domain
        # ... further option
```

### width

**type**: integer

Define the width of the column. The default value is `1`. Because we are
using a `12` column bootstrap grid you have to define a width between
`1` and `12`. Remind that the sum of all columns in a certain table
should be `12`.

```yaml
columns:
    myColumn:
        width: [1-12]
        # ... further option
```
