## Date Between

The DateBetweenFilter can be used to filter for dates in a range.

<ReferenceTable
type="date_between"
className="Enhavo\Bundle\AppBundle\Filter\Type\DateBetweenFilter"
>
<template v-slot:options>
    <ReferenceOption name="property" type="date_between" :required="true" />,
    <ReferenceOption name="label_from" />,
    <ReferenceOption name="label_to" />
</template>
<template v-slot:inherit>
    <ReferenceOption name="label" />,
    <ReferenceOption name="locale" />,
    <ReferenceOption name="format" />,
    <ReferenceOption name="initial_active" />,
    <ReferenceOption name="initial_value" />,
    <ReferenceOption name="condition" />,
    <ReferenceOption name="width" />,
    <ReferenceOption name="permission" />,
    <ReferenceOption name="component" />
</template>
</ReferenceTable>

### label_from

**type**: `string|null`

The label for the field where the user can set the lower value of the
range. It will be translated over the translation service automatically.
(See translation_domain) If not set, the value of [label](#label) will
be used. If that one is also null, no label will be displayed.

```yaml
columns:
    myFilter:
        label_from: myRange from
```

### label_to

**type**: `string|null`

The label for the field where the user can set the lower value of the
range. If not set, the value of [label](#label) will be used. If that
one is also null, no label will be displayed. It will be translated over
the translation service automatically. (See translation_domain)

```yaml
columns:
    myFilter:
        label_to: to
```

### label

**type**: `string|null`

The label of the filter in the filter dropdown. If not set, the value of
[label_from](#label_from) will be used. It will be translated over the
translation service automatically. (See translation_domain)

```yaml
columns:
    myFilter:
        label: myLabel
```

### locale

**type**: `string`

The locale used for the Datepicker form fields. Defaults is the default
locale of the project.

```yaml
columns:
    myFilter:
        locale: en
```

### format

**type**: `string`

The date format used for the Datepicker form fields. Uses the date
format of vue3-datepicker. Default is [dd.MM.yyyy]{.title-ref}.

```yaml
columns:
    myFilter:
        format: yyyy-MM-dd
```
