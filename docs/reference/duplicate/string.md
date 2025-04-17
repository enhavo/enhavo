## String

Copy a string.

<ReferenceTable 
    type="string"
    className="Enhavo\Bundle\ResourceBundle\Duplicate\Type\CloneDuplicateType"
    parent="Enhavo\Bundle\ResourceBundle\Duplicate\BaseActionType"
>
<template v-slot:options>
    <ReferenceOption name="prefix" :required="false" />
    <ReferenceOption name="postfix" :required="false" />
    <ReferenceOption name="translation_domain" :required="false" />
</template>
<template v-slot:inherit>
    <ReferenceOption name="groups" type="groups" />
</template>
</ReferenceTable>


### prefix

**type**: `string` **default:** `null`

Add a string at the beginning of the duplicated string.

### postfix

**type**: `string` **default:** `null`

Add a string at the end of the duplicated string.

### translation_domain

**type**: `string` **default:** `null`

Translation domain for pre- and postfix.
