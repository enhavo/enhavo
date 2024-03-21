## DateTime

Shows a formatted date. Works exactly like the
`Date Column <date_column>`{.interpreted-text role="ref"}, only the
default format is different and shows a date and the time.

<ReferenceTable
type="action"
className="Enhavo\Bundle\AppBundle\Column\Type\DateTimeType"
>
<template v-slot:opions>
    <ReferenceOption name="action" type="action" :required="true"/>
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