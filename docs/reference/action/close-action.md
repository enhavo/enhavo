## Close

The close action closes the current window and can ask the user to save
previously made changes.

<ReferenceTable 
    type="close"
    className="Enhavo\Bundle\AppBundle\Action\Type\CloseActionType"
    parent="Enhavo\Bundle\AppBundle\ActionAbstractActionType"
>
<template v-slot:inherit>
    <ReferenceOption name="icon" type="comment" />
    <ReferenceOption name="label" />,
    <ReferenceOption name="translation_domain" />,
    <ReferenceOption name="hidden" />,
    <ReferenceOption name="permission" type="comment" />
</template>
</ReferenceTable>



