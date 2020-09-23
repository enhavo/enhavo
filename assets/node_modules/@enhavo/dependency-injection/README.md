# Dependency Injection

The missing dependency injection for webpack.

### Install

Add package the package to your project

```
$ yarn add @enhavo/dependency-injection
$ npm install @enhavo/dependency-injection
```

Create a `json` or `yaml` for your service definitions. For example a ``service.yaml`` in your root directory.

Add the ``DependencyInjectionPlugin`` to your webpack config.

```
// webpack.config.js

const DependencyInjectionPlugin = require('@enhavo/dependency-injection/Webpack/DependencyInjectionPlugin');

module.exports = {
  plugins: [
    new DependencyInjectionPlugin('./service.yaml'),
  ],
};
```

### Define services

A simple hello world service inside the project root dir.

``` service.yaml
// MyService

module.exports = () => {
    console.log('Hello World!');
}
    
```

Now you can edit the ``service.yaml`` and add the ``MyService`` module.

```
# service.yaml

services:
    MyService:
        from: './MyService'
    
```

### Use service

Inside an entrypoint you can load the service via the dependency injection container.

```
// my_entrypoint.js

import Container from "@enhavo/dependency-injection"

(async () => {
    let myService = await Container.get('MyService');
})();
```
