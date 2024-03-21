## Template

Renders specific template with a certain property of a resource.

<ReferenceTable
type="template"
className="Enhavo\Bundle\AppBundle\Column\Type\PropertyColumn"
>
<template v-slot:options>
    <ReferenceOption name="template" type="label" :required="true" />
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
