## Migrate to 0.9

**1. Add this line at the end of `assets/enhavo/form.ts`**

```js
Application.getView().checkUrl();
```

**2. Create registry file `assets/enhavo/registry/widget.ts`**

``` 
import RegistryPackage from "@enhavo/core/RegistryPackage";
import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import AppWidgetRegistryPackage from "@enhavo/app/Toolbar/Widget/WidgetRegistryPackage";

export default class ViewRegistryPackage extends RegistryPackage
{
    constructor(application: ApplicationInterface) {
        super();
        this.registerPackage(new AppWidgetRegistryPackage(application));
    }
}
```

**3. Add widget registry to `assets/enhavo/main.ts`**

``` 
// Add import line
import WidgetRegistryPackage from "./registry/widget";

// Add register package line before load
Application.getWidgetRegistry().registerPackage(new WidgetRegistryPackage(Application));

Application.getVueLoader().load(() => import("@enhavo/app/Main/Components/MainComponent.vue"));
```

**4. Rename routing manager service id `enhavo_routing.manager.route` to
FQCN `Enhavo\Bundle\RoutingBundle\Manager\RouteManager`**

**5. Rename `property` to `properties` for route PrefixGenerator type.**

```yaml
enhavo_routing:
    classes:
        App\MyEntity:
            generators:
                prefix:
                    type: prefix
                    # before
                    property: title
                    # now
                    properties: title
```

**6. If the action component `create-action` was used, you have to
migrate it to `open-action`. \`**

The action type `create` is not affected.

**7. Add form registry to `assets/enhavo/index.ts`**

``` 
// Add import line
import FormRegistryPackage from "./registry/form";

// Add register package line before load
Application.getFormRegistry().registerPackage(new FormRegistryPackage(Application));

Application.getVueLoader().load(() => import("@enhavo/app/Index/Components/IndexComponent.vue"));
```

**8. Add resource parameter to Action**

This changes only may apply if you have this functions define by your
own

``` 
// change this line
public function getPermission(array $options);

// to this line
public function getPermission(array $options, $resource = null);


// change this line
public function isHidden(array $options);

// to this line
public function isHidden(array $options, $resource = null);
```

**9. Update your `webpack.config.js`.**

The way how to include other bundles and configure your webpack/encore
has changed. Just use the following lines if you never edit your
`webpack.config.js` . If you edit this file before, you need to add the
configs inside the js callbacks.

``` js
const EnhavoEncore = require('@enhavo/core/EnhavoEncore');
const AppPackage = require('@enhavo/app/Encore/EncoreRegistryPackage');
const FormPackage = require('@enhavo/form/Encore/EncoreRegistryPackage');
const MediaPackage = require('@enhavo/media/Encore/EncoreRegistryPackage');
const DashboardPackage = require('@enhavo/dashboard/Encore/EncoreRegistryPackage');
const UserPackage = require('@enhavo/user/Encore/EncoreRegistryPackage');

EnhavoEncore
  // register packages
  .register(new AppPackage())
  .register(new FormPackage())
  .register(new MediaPackage())
  .register(new DashboardPackage())
  .register(new UserPackage())
;

EnhavoEncore.add('enhavo', (Encore) => {
  // custom encore config
  // Encore.enableBuildNotifications();
});

EnhavoEncore.add('theme', (Encore) => {
  Encore
    // add theme entry and config
    .addEntry('base', './assets/theme/base')
});

module.exports = EnhavoEncore.export();
```

**10. `Enhavo\Bundle\NewsletterBundle\Provider\ProviderInterface`
changed**

Return a test receiver with parameters now.

```php
// before
public function getTestParameters(): array;

// after
public function getTestReceivers(NewsletterInterface $newsletter): array;
```

**11. Newsletter template parameters changed.**

The parameter `parameters` is now `receiver.parameters`

**12. Delete all enhavo config files `config/packages/enhavo_*`**

If you have other contents in that files then import yaml files from
it\'s bundle. You may keep that changes. If you include search or
translation configs, you can keep that lines as well but change it to
`yaml` instead of `yml`

**13. Update your routes.**

Download
`this zip file </_static/download/migrate-routes-0.9.zip>`{.interpreted-text
role="download"} and overwrite the files in `config/routes` if they
exists. Note that the file in your project probably named `*.yml`
instead of `*.yaml`. If you made changes to the file before you have to
merge the file yourself.

**14. Delete file `config/routes/enhavo_taxonomy.yaml` if exists**

**15. Update BatchTypes to the new Type Component if you add custom
batches.**

**16. Add packages to your `composer.json`**

If you use `dev-master` as version in your `composer.json`, you have to
add following packages to prevent minimum stability violence. If you are
not use `dev-master` you can skip this step.

```json
"dependencies": {
  "enhavo/doctrine-extension-bundle": "dev-master",
  "enhavo/metadata": "dev-master",
  "enhavo/type": "dev-master",
  // other packages
}
```

**17. The DoctrineExtendListener has removed. You have to add metadata
information to all your entities which extend from enhavo.**

Check the
`Extend from resource </guides/resource/extend-from-resource>`{.interpreted-text
role="doc"} guide for more information. Notice that before the
`discrName` was `extend`. If you add some other name, beware to also
provide some Doctrine Migrations to update the `discr` column. If you
don\'t know, if and for what resource you have to put the extends
configuration. Just search for `model` inside `config/packages/*` and
see where you redefine a model. At least for this models you have to
provide the configuration.

**18. Rename strategy type `route` to `routable` for Router.**

```yaml
enhavo_routing:
    classes:
        App\MyEntity:
            router:
                default:
                    # before
                    type: route
                    # now
                    type: routable
```
