## Installation

```bash
$ composer require enhavo/media-bundle
```

```bash
$ yarn add @enhavo/media
```

```ts
// import statement
import MediaFormRegistryPackage from "@enhavo/media/FormRegistryPackage";

// register the package
this.registerPackage(new MediaFormRegistryPackage(application));
```

```ts
// import
const MediaPackage = require('@enhavo/media/Encore/EncoreRegistryPackage');

// register package
.register(new MediaPackage());
```

If you want to display the media library in your application you can
change your `config/packages/enhavo.yaml` file.

```yaml
enhavo_app:
    menu:
        media_library:
            type: media_library
```
