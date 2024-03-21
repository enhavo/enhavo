## Boolean

The BooleanFilter filters a boolean property. It has two distinct
variants depending on the parameter [checkbox](#checkbox).

If checkbox is true, the filter will be rendered as a checkbox. If the
checkbox is unchecked, the filter will be inactive and allow any value
on the target\'s [property]() field. If the checkbox is checked, only
values equal to this filter\'s parameter [equals](#equals) are allowed.
In the checkbox variant, there is no way to filter for the opposite of
what the parameter equals is set to.

If checkbox is false, the filter will be rendered as a dropdown with
both the option of filtering for true, for false and for an inactive
filter.


<ReferenceTable
type="boolean"
className="Enhavo\Bundle\AppBundle\Filter\Type\BooleanFilterType"
>
<template v-slot:options>
    <ReferenceOption name="property" type="text" :required="true" />,
    <ReferenceOption name="label" type="text" :required="true" />,
    <ReferenceOption name="checkbox" />,
    <ReferenceOption name="equals" />,
    <ReferenceOption name="label_true" />,
    <ReferenceOption name="label_false" />
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

### checkbox

**type**: `boolean`

Controls whether the filter is displayed as a checkbox or a dropdown.
Default is [true]{.title-ref}.

```yaml
filter:
    myFilter:
        type: boolean
        checkbox: false
```

### equals

**type**: `boolean`

Only used if [checkbox](#checkbox) is true. Controls whether an active
filter allows for the [property]() to be true or false. Default is
[true]{.title-ref}.

```yaml
filter:
    myFilter:
        type: boolean
        equals: true
```

### label_true

**type**: `string`

Only used if [checkbox](#checkbox) is false. Controls the label of the
dropdown entry for the value true. Default
[filter.boolean.label_true]{.title-ref}. Will be translated over the
translation service automatically. (See translation_domain)

```yaml
filter:
    myFilter:
        type: boolean
        label_true: Yes
```

### label_false

**type**: `string`

Only used if [checkbox](#checkbox) is false. Controls the label of the
dropdown entry for the value false. Default
[filter.boolean.label_false]{.title-ref}. Will be translated over the
translation service automatically. (See translation_domain)

```yaml
filter:
    myFilter:
        type: boolean
        label_false: No
```
