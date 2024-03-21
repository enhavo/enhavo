## Age

The AgeFilter can be used to filter an age in full years. The parameter
[property]() needs to refer to a date representing the date of
birth/origin, and the age is calculated from that date until today.


<ReferenceTable
    type="text"
    className="Enhavo\Bundle\AppBundle\Filter\Type\AgeFilter"
>
<template v-slot:options>
    <ReferenceOption name="property" type="text" :required="true"/>,
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
