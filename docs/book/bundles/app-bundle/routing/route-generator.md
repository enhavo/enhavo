## Routing generator

We implemented a short routing generator, which creates minimal CRUD
routing definitions for your resource.

```bash
app/console enhavo:generate:routing app resource [--sorting="property"]
```

If the optional parameter `sorting` is present, the entity is considered
to be sortable by the user. The value of `sorting` is the name of the
integer property of the resource entity used to save the position of the
object.
