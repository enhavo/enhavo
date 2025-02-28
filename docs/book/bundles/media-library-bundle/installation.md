## Installation

```bash
$ composer require enhavo/media-library-bundle
```

```bash
$ yarn add @enhavo/media-library
```

```yaml
# assets/admin/container.di.yaml
imports:
    - path: '@enhavo/media-library/services/admin/*' // [!code ++]
```

```yaml
# config/packages/enhavo.yaml
enhavo_app:
    menu:
        media_library:
            type: media_library
```
