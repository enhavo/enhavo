## Translation String

Duplicate a string like [string](#string) and copy its stored translations of all locales.
Prefix and postfix are added to every translation as well.

<ReferenceTable 
    type="translation_string"
    className="Enhavo\Bundle\TranslationBundle\Duplicate\Type\TranslationStringDuplicateType"
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

The options are the same as for [string](#string).
