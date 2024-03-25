## Introduction


### Overview

With enhavo you are able to set global values for your storage and
strategy. Additionally you can define these values for every newsletter
subscription form individually. You are able to set one or more group
for every subscriber globally and add additional groups per subscription
form.

### Workflow

![image](/images/newsletter_workflow.png)

### Default Storage Type

The default storage type is applied to every subscription form on your
site if you don\'t override it. There are currently two storage types -
\'local\' and \'cleverreach\'. \'local\' is the default value - you need
no entry in your app/config/enhavo.yml. If you want to use Clever Reach,
put the following statement in your app/config/enhavo.yml and follow the
instructions in the Clever Reach Configuration help file.

``` yaml
enhavo_newsletter:
    storage:
        default: cleverreach
```

### Default Groups

You can associate subscribers with groups. This is mandatory for Clever
Reach and optional for the local storage. To set default groups use the
following example

```yaml
enhavo_newsletter:
    storage:
        groups:
            defaults:
                - group1
                - group2
                - ..
```

### Default Strategy

There are currently 3 different subscription strategies: notify, accept
and double_opt_in. The default strategy is notify - you don\'t need to
add the following statement if you want to use it. To set another
default strategy use this statement

```yaml
enhavo_newsletter:
    strategy:
        default: double_opt_in
```

### Individual Form Settings

You are able to override the default settings for storage, strategy and
groups for every individual form. Also you can define the type and
template individually. Do it as follows

```yaml
enhavo_newsletter:
    forms:
        <form_name>:
            default_groups:
                - 'code_of_group3'
            storage: 
                type: local
            strategy:
                type: accept
            type: enhavo_newsletter_subscribe
            template: EnhavoNewsletterBundle:Subscriber:subscribe.html.twig
```

The form name \"default\" is already in use and defines type:
enhavo_newsletter_subscribe and template:
EnhavoNewsletterBundle:Subscriber:subscribe.html.twig as default - of
course you can change these values, e.g.

```yaml
enhavo_newsletter:
    forms:
        default:
            type: <your_type>
            template: <your_template>
```
