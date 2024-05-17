## Debugging

To check which data will be indexed you can use the `debug:search:analyze` command. It will need the
FQCN and the id of the resource as arguments

```bash
$ bin/console debug:search:analyze "Enhavo\Bundle\PageBundle\Entity\Page" 1
```

To check what are the results, you can use the `enhavo:search` command.

```bash
$ bin/console enhavo:search hello
```

To check the auto suggest results, you use the `enhavo:search:suggest`

```bash
$ bin/console enhavo:search:suggest hel
```
