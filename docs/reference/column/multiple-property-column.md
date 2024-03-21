## Multiple Property

Shows a list of properties of the given resource.


<ReferenceTable
type="multiply_property"
className="Enhavo\Bundle\AppBundle\Column\Type\MultiplePropertyColumn"
>
<template v-slot:options>
    <ReferenceOption name="properties" type="multiply_property" :required="true"/>,
    <ReferenceOption name="seperator" />,
</template>
<template v-slot:inherit>
    <ReferenceOption name="label" />,
    <ReferenceOption name="translation_domain" />,
    <ReferenceOption name="condition" />,
    <ReferenceOption name="width" />,
    <ReferenceOption name="permission" />,
    <ReferenceOption name="component" />
</template>
</ReferenceTable>


### properties

**type**: string

Define an array of properties that should be displayed as list.

```yaml
buttons:
    myColumn:
        properties:
            - firstname
            - lastname
```

