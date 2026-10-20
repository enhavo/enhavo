## Translation File

Duplicate a media `File` object or a collection of it like [file](#file) and duplicate its translated files of all locales.

<ReferenceTable 
    type="translation_file"
    className="Enhavo\Bundle\TranslationBundle\Duplicate\Type\TranslationFileDuplicateType"
    parent="Enhavo\Bundle\ResourceBundle\Duplicate\BaseActionType"
>
<template v-slot:inherit>
    <ReferenceOption name="groups" type="groups" />
</template>
</ReferenceTable>
