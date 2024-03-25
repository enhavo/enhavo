## Cleverreach

<ReferenceTable
type="cleverreach"
className="Enhavo\Bundle\NewsletterBundle\Storage\Type\CleverReachStorageType"
>
<template v-slot:options>
    <ReferenceOption name="client_id" type="cleverreach" :required="true" />,
    <ReferenceOption name="client_secret" type="cleverreach" :required="true" />,
    <ReferenceOption name="global_attributes" type="cleverreach" />,
    <ReferenceOption name="attributes" type="cleverreach" />,
    <ReferenceOption name="groups" type="cleverreach" />
</template>
</ReferenceTable>

### client_id {#client_id_cleverreach}

**type**: `string`

This is the Client ID you can look up in your Cleverreach-Account -\> My
Account -\> Rest-API -\> OAuth-Apps. Mostly you set this in your
.env.local file of your project.

```yaml
storage:
    type: cleverreach
    client_id: '%env(string:CLEVERREACH_ID)%'
```

### client_secret {#client_secret_cleverreach}

**type**: `string`

This is the Client Secret you can look up in your Cleverreach-Account
-\> My Account -\> Rest-API -\> OAuth-Apps. Mostly you set this in your
.env.local file of your project.

``` yaml
storage:
    type: cleverreach
    client_secret: '%env(string:CLEVERREACH_SECRET)%'
```

### global_attributes {#global_attributes_cleverreach}

**type**: `array`

If you have defined additional (global) fields inside your
Cleverreach-account, which should be considered in your subscription,
you need to add them in your configuration as well.

cleverreachfieldname: That should be the name of the field in your
Cleverreach-account. modelfieldname: That\'s the name of the field in
your NewsletterSubscriber inside your application. Note: You don\'t need
to change your database scheme. global_attributes are stored inside a
json_array data type.

```yaml
storage:
    type: cleverreach
    global_attributes:
        cleverreachfieldname: modelfieldname
```

### attributes {#attributes_cleverreach}

**type**: `array`

Same like \"global_attributes\" with fields you have defined in your
Cleverreach-account specifically for only one receiver group and not
globally.

```yaml
storage:
    type: cleverreach
    attributes:
        cleverreachfieldname: modelfieldname
```

### groups {#groups_cleverreach}

**type**: `array`

These are the receiver groups of your Cleverreach-account your
subscriber should be stored in. Each receiver group has an ID you can
look up in your Cleverreach-account. This ID needs to be used here
(mostly set in your .env.local file).

```yaml
storage:
    type: cleverreach
    groups:
        - '%env(string:CLEVERREACH_GROUP)%'
```
