## Between

The BetweenFilter can be used to filter in a range between two values.
It works on any type that can be compared using \"\>=\" and \"\<=\" in
the database query.


<ReferenceTable
type="between"
className="Enhavo\Bundle\AppBundle\Filter\Type\BetweenFilter"
>
<template v-slot:options>
    <ReferenceOption name="property" type="between" :required="true" />,
    <ReferenceOption name="label_from" />,
    <ReferenceOption name="label_to" type="between" />
</template>
<template v-slot:inherit>
    <ReferenceOption name="label" />,
    <ReferenceOption name="locale" />,
    <ReferenceOption name="format" />,
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
