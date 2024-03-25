## Boolean

Shows a tick or cross for a boolean value.

<ReferenceTable
type="boolean"
className="Enhavo\Bundle\AppBundle\Column\Type\BooleanType"
>
<template v-slot:options>
    <ReferenceOption name="property" type="boolean" :required="true" />
</template>
<template v-slot:inherit>
    <ReferenceOption name="label" />,
    <ReferenceOption name="translation_domain" />,
    <ReferenceOption name="condition" />,
    <ReferenceOption name="width" />,
    <ReferenceOption name="permission" />,
    <ReferenceOption name="component" />,
    <ReferenceOption name="sorting_property" />
</template>
</ReferenceTable>
