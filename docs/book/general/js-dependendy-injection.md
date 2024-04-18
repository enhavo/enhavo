# JS Dependency Injection

Dependency injection for webpack. How does it work? First, you have to define all your services and their dependencies in a ``yaml`` or ``json`` format.
During the webpack compile time the service loader will create a container class. This container class
can be loaded in your entrypoint and you can retrieve your service with all its dependencies. This project is heavily inspired by the symfony dependency injection.
If you like it, please leave a github star.

### Install

Add the package to your project

```
$ yarn add @enhavo/dependency-injection
$ npm install @enhavo/dependency-injection
```

Create a `json` or `yaml` file for your service definitions. For example create ``container.di.yaml`` in your root directory.

Add the service loader to your webpack config.

```js
// webpack.config.js
module.exports = {
    module: {
        rules: [
            { test: /\.di.(yaml|yml|json)$/, use: require.resolve('@enhavo/dependency-injection/service-loader') },
        ],
    },
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

Now you can create a container definition file e.g. ``container.di.yaml`` and add the ``MyService`` module.

```yaml
# container.di.yaml

services:
    MyService:
        from: './MyService'

```

### Use service

Inside an entrypoint you can load the service via the dependency injection container.

```js
// my_entrypoint.js

import Container from "./container.di.yaml"

(async () => {
    let myService = await Container.get('MyService');
})();
```

### Import

You can import further files through import statements

```yaml
# container.di.yaml

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
# container.di.yaml

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
        # Set a service, which will be called to create the service (arguments, static, import and from will be ignored)
        factory: 'MyServiceFactory'
        # If factory set, you can define the method which should be called. If no method set then the function will be called directly
        factoryMethod: 'create'

```

### Parameters

tbc.

### Compiler pass

tbc.

### Multiple containers

tbc.

### Debugging

You can use the `di` cli tools.
```
$ yarn di inspect <pathToContainerFile>
$ yarn di compile <pathToContainerFile> <outputFile>
```


### Contributing

This is a subtree split of the main repository. For contributing please check the [main repository](https://github.com/enhavo/enhavo)


