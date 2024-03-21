## Installation

```bash 
$ composer require enhavo/app-bundle
```

### Add packages

Add following `package.json` to your project root directory.

```json
{
  "scripts": {
    "routes:dump": "bin/console fos:js-routing:dump --format=json --target=public/js/fos_js_routes.json"
  },
  "dependencies": {
    "@enhavo/app": "^0.8.0",
  }
}
```

### Add webpack config

Add following `webpack.config.js` to your project root directory.

```js
const EnhavoEncore = require('@enhavo/core/EnhavoEncore');
const AppPackage = require('@enhavo/app/Encore/AppPackage');
const AppThemePackage = require('@enhavo/app/Encore/AppThemePackage');
const FormPackage = require('@enhavo/form/Encore/FormPackage');

EnhavoEncore.add(
  'enhavo',
  [new AppPackage(), new FormPackage()],
  Encore => {},
  config => {}
);

EnhavoEncore.add(
  'theme',
  [new AppThemePackage()],
  Encore => {
    Encore.addEntry('base', './assets/theme/base.js');
  },
  config => {}
);

module.exports = EnhavoEncore.export();
```

Update your `config/packages/webpack_encore.yaml`

```yaml
webpack_encore:
    output_path: '%kernel.project_dir%/public/build/enhavo'
    builds:
        enhavo: '%kernel.project_dir%/public/build/enhavo'
        theme: '%kernel.project_dir%/public/build/theme'
```

Update your `config/packages/assets.yaml`

```yaml
framework:
    assets:
        json_manifest_path: '%kernel.project_dir%/public/build/theme/manifest.json'
```

This is optional, but it helps to test your mails using within other
packages. Just update your `config/packages/dev/swiftmailer.yaml`

```yaml
swiftmailer:
    delivery_addresses: ['%env(resolve:MAILER_DELIVERY_ADDRESS)%']
```

### Add typescript config

Add following `tsconfig.json` to your project root directory.

``` 
{
  "compilerOptions": {
    "lib": [ "es2015", "dom" ],
    "module": "amd",
    "target": "es5",
    "allowJs": true,
    "noImplicitAny": true,
    "suppressImplicitAnyIndexErrors": true,
    "moduleResolution": "node",
    "sourceMap": true,
    "experimentalDecorators": true
  },
  "include": [
    "./assets/**/*"
  ]
}
```

### Start application

Open your application under the `/admin` url.
