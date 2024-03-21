## Storages


There are currently two storage types - \'local\' and \'clever_reach\'.
\'local\' is the default value - you need no entry in your
app/config/config.yml. If you want to use \'clever_reach\', put the
following statement in your app/config/config.yml and (follow the
instructions for the credentials and group!):

### Clever Reach

To use Clever Reach as your newsletter service, put the credentials in
your app/config/config.yml as follows:

If you want to use Clever Reach as default storage, put the following
statement in your app/config/enhavo.yml

```yaml
enhavo_newsletter:
    storage: 'cleverreach'
```

```yaml
enhavo_newsletter:
    storage:
        default: cleverreach
        settings:
            cleverreach:
                credentials:
                    client_id: '<YOUR_CLIENT_ID>'
                    login: '<YOUR_LOGIN>'
                    password: '<YOUR_PASSWORD>'
                groups:
                    mapping:
                        <MAPPINGS>
                group: '<YOUR_GROUP_ID>'
```

**Mapping**

A mapping contain the group name and the clever reach id

**Group**

You have to define a Clever Reach group, to which you add your new
subscribers (find your group ID on the Clever Reach website). Define it
as follows:

*Clever Reach group mapping* Clever Reach uses group IDs to identify a
group in their system. To map the group name of the Enhavo Newsletter
Bundle to a Clever Reach group use the following statement (keep in mind
to rename group1 and group2 to your own enhavo group name)

```yaml
enhavo_newsletter:
    storage:
        settings:
            cleverreach:
                groups:
                    mapping:
                        group1: <CR_GROUP_ID_1>
                        group2: <CR_GROUP_ID_2>
```
