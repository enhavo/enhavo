## List

Shows all values of a collection as a list.


<ReferenceTable
type="list"
className="Enhavo\Bundle\AppBundle\Column\Type\LabelType"
>
<template v-slot:options>
    <ReferenceOption name="property" type="list" :required="true"/>,
    <ReferenceOption name="item_property" type="list" :required="true"/>,
    <ReferenceOption name="separator" type="list" />
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


### item_property {#item_property_list}

**type**: string

Defines the property of the items to be used for display within the
collection. You can use existing properties of the class or a specially
created getter method that returns a string composed of multiple
properties.

``` yaml
buttons:
    myColumn:
        property: groups
        item_property: myEntityProperty
```

