## Newsletter Send

Creates a receiver with a link to the currently selected newsletter for
each member, all groups added to the newsletter. After that, the
newsletter was prepared, but has not yet been sent out. The
`enhavo:newsletter:send` command will
send all newsletters that have not been sent up to that point to their
respective receiver. To make sure that this happens regularly (e.g. at
the same time every day) it is recommended to set up a cronjob for this
command in your production environment.

<ReferenceTable
type="newsletter_send"
className="Enhavo\Bundle\NewsletterBundle\Action\SendActionType"
>
<template v-slot:options>
</template>
<template v-slot:inherit>
    <ReferenceOption name="label" />,
    <ReferenceOption name="translation_domain" />,
    <ReferenceOption name="hidden" />,
    <ReferenceOption name="permission" />
</template>
</ReferenceTable>
