## Installation


```bash
$ composer require enhavo/search-bundle
```

```bash
$ yarn add @enhavo/search
```

To initialize the engine you have to fire the `enhavo:search:init` command.

```bash
$ bin/console enhavo:search:init
```

Use the `--force` option, if you make changes
that affect the index settings. Force will drop the index first and initialize, so you will have an empty index.

```bash
$ bin/console enhavo:search:init --force
```