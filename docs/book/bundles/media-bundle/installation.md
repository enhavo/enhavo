## Installation

```bash
$ composer require enhavo/media-bundle
```

```bash
$ yarn add @enhavo/media
```

```yaml
# assets/admin/container.di.yaml
imports:
    - path: '@enhavo/media/services/admin/*' // [!code ++]
```

```yaml
# assets/theme/container.di.yaml
imports:
    - path: '@enhavo/media/services/theme/*' // [!code ++]
```

