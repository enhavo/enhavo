## Text

The TextFilter filters a property by user string

<ReferenceTable
type="text"
className="Enhavo\Bundle\AppBundle\Filter\Type\TextFilter"
>
<template v-slot:options>
    <ReferenceOption name="property" type="text" :required="true"/>,
    <ReferenceOption name="label" type="text" :required="true"/>,
    <ReferenceOption name="operator" />
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

### operator

**type**: `string`

The operator used when applying this filter to the database search. Can
be one of the following:

-   `=`
-   `!=`
-   `like` (default)
-   `start_like`
-   `end_like`

Default is [like]{.title-ref}.

```yaml
columns:
    myFilter:
        type: text
        operator: start_like
```
