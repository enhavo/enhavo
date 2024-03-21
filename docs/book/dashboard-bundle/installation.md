## Installation

```bash
$ composer require enhavo/dashboard-bundle
```

```bash
$ yarn add @enhavo/dashboard
```

```js
// import
const DashboardPackage = require('@enhavo/dashboard/Encore/EncoreRegistryPackage');

// register package
.register(new DashboardPackage())
```

Update your `config/packages/enhavo.yaml`

```yaml
enhavo_app:
    menu:
      dashboard:
          type: dashboard
```
