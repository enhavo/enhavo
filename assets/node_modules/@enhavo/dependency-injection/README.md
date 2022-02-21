![alt text](enhavo.svg "enhavo")
<br/>
<br/>

The enhavo CMS is a open source PHP project on top of the fullstack Symfony framework and uses awesome Sylius components to serve a very flexible software, that can handle most of complex data structure with a clean and usability interface.


# Dependency Injection

Dependency injection for webpack. How does it work? First, you have to define all your services and their dependencies in a ``yaml`` or ``json`` format.
During the webpack compile time the ``DependencyInjecitonPlugin`` will create a container class. This container class
can be imported in your entrypoint and you can retrieve your service with all its dependencies. This project is heavily inspired by the symfony dependency injection.
If you like it, please leave a github star.

### Install

Add the package to your project

```
$ yarn add @enhavo/dependency-injection
$ npm install @enhavo/dependency-injection
```

Create a `json` or `yaml` file for your service definitions. For example create ``service.yaml`` in your root directory.

Add the ``DependencyInjectionPlugin`` to your webpack config.

```js
// webpack.config.js

const DependencyInjectionPlugin = require('@enhavo/dependency-injection/webpack/DependencyInjectionPlugin');

module.exports = {
    plugins: [
        new DependencyInjectionPlugin('./service.yaml'), // define your path to the configuration file(s)
    ],
};
```

### Define services

A simple hello world service inside the project root dir.

```js
// MyService

module.exports = () => {
    console.log('Hello World!');
}

```

Now you can edit the ``service.yaml`` and add the ``MyService`` module.

```yaml
# service.yaml

services:
    MyService:
        from: './MyService'

```

### Use service

Inside an entrypoint you can load the service via the dependency injection container.

```js
// my_entrypoint.js

import Container from "@enhavo/dependency-injection"

(async () => {
    let myService = await Container.get('MyService');
})();
```

### Import

You can import further files through import statements

```yaml
# service.yaml

imports:
    -
        # This will include all configuration files from node_modules/mypackage/services
        path: 'mypackage/services/*'
    -
        # Relative import
        path: './mypackage/services/*'

```

### Overwrite

You can overwrite services by redefining it with the same service name.
Be careful that you are loading the services files in the correct order.
The last defined service will be used.

```yaml
# original service.yaml

services:
    MyService:
        from: './MyService'

```

```yaml
# custom service.yaml

services:
    MyService:
        from: './MyCustomService'

```

### Service options

Because the services are loaded dynamically. You can apply the [webpack magic options](https://webpack.js.org/api/module-methods/#magic-comments)

```yaml
# service.yaml

services:
    MyService:
        # From which path the service will be included (required)
        from: './MyService'
        # If you don't use the default import, you can define which import you need. Equal to "import { MyServiceClass } from "./MyService"
        import: MyServiceClass
        # Pass dependency by calling a setter
        calls:
            - [setDependency, ['MyDependService']]
        # Pass dependency over the constructor
        arguments:
            - 'MyDependService'
        # If there is no "new" operator required to create the service you can define the service as static true. The default value is false.
        static: false
        # Dynamic import mode ('lazy'(default)|'lazy-once'|'eager'|'weak')
        mode: ~
        #  Tells the browser that the resource is probably needed for some navigation in the future.
        prefetch: ~
        # Tells the browser that the resource might be needed during the current navigation
        preload: ~
        #  A name for the new chunk
        chunckName: ~
        # A regular expression that will be matched against during import resolution
        include: ~
        # A regular expression that will be matched against during import resolution
        exclude: ~
        # Tells webpack to only bundle the used exports of a module when using dynamic imports
        exports: ~
        # Disables dynamic import parsing when set to true
        ignore: ~
        # This service will be initialized if container.init() ist called
        init: false

```

### Entrypoint

Define a entrypoint via service configuration

```yaml
# service.yaml

entrypoints:
    'mypackage/entrypoint':
        path: '../entry/entrypoint'
```

### Parameters

tbc.

### Compiler pass

tbc.

### Multiple containers

tbc.

### Contributing

This is a subtree split of the main repository. For contributing please check the [main repository](https://github.com/enhavo/enhavo)


