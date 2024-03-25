## Add dashboard widget

Let\'s assume, we want to build a list widget that shows the titles of
the latest articles. This is how we do it:

### Create Type

In the following, you see the code to create the ListWidgetType class.

The factory we pass to the constructor creates our provider.

Note the `$data['data']` in the `createViewData` method. This is the
data we hand to the vue component later.

The result of the `getName` method is the `type` we need to add to the
configuration later.

In the `configureOptions` method we set the vue component to use.

```php
class ListWidgetType extends AbstractWidgetType
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * ListWidgetType constructor.
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createViewData(array $options, ViewData $data, ResourceInterface $resource = null)
    {
        $provider = $this->factory->create($options['provider']);
        $data['data'] = $provider->getData();
    }

    public static function getName(): ?string
    {
        return 'list';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'component' => 'list-widget',
        ]);

        $resolver->setRequired([
            'provider'
        ]);
    }
}
```

The next thing we need to do is, make the widget visible to the
container:

```yaml
App\Dashboard\Widget\Type\ListWidgetType:
    arguments:
        - '@Enhavo\Component\Type\FactoryInterface[DashboardProvider]'
    tags:
        - { name: enhavo_dashboard.widget }
```

### Create Model

```ts
import ApplicationInterface from "@enhavo/app/ApplicationInterface";

export default class ListWidget
{
    protected application: ApplicationInterface;

    component: string;
    label: string;
    data: Array<string>;

    constructor(application: ApplicationInterface)
    {
        this.application = application;
    }
}
```

### Create Factory

Don\'t forget to fill in the path of your model at `YOUR_MODEL_PATH`!

```ts
import AbstractFactory from "@enhavo/dashboard/Widget/Factory/AbstractFactory";
import ListWidget from "YOUR_MODEL_PATH";

export default class ListWidgetFactory extends AbstractFactory
{
    createNew(): ListWidget {
        return new ListWidget(this.application)
    }
}
```

Create Vue Component \-\-\-\-\-\-\-\-\-\-\-\-\--

Don\'t forget to fill in the path of your model at `YOUR_MODEL_PATH`!

```html
<template>
    <div>
        <div class="label">{{ data.label }}</div>
        <div class="data">
            <div v-for="entry in data.data">{{ entry }}</div>
        </div>
    </div>
</template>

<script lang="ts">
    import {Component, Prop, Vue} from "vue-property-decorator";
    import ListWidget from "YOUR_MODEL_PATH";

    @Component
    export default class ListWidgetComponent extends Vue
    {
        name: 'list-widget';

        @Prop()
        data: ListWidget;
    }
</script>
```

### Register Component

Don\'t forget to fill in the path of your component at
`YOUR_COMPONENT_PATH`!

Open `assets/enhavo/dashboard.ts` in your project, and add the following
line before the Vue Loader call:

```ts
Application.getWidgetRegistry().register('list-widget', () => import('YOUR_COMPONENT_PATH'), new ListWidgetFactory(Application));
```

Your resulting file could look like this:

```ts
import Application from "@enhavo/dashboard/DashboardApplication";
import DashboardWidgetsRegistryPackage from "./registry/dashboard-widgets";
import ListWidgetFactory from "@enhavo/dashboard/Widget/Factory/ListWidgetFactory";

Application.getWidgetRegistry().registerPackage(new DashboardWidgetsRegistryPackage(Application));
Application.getWidgetRegistry().register('list-widget', () => import('YOUR_COMPONENT_PATH'), new ListWidgetFactory(Application));
Application.getVueLoader().load(() => import("@enhavo/dashboard/Widget/Components/ApplicationComponent.vue"));
```

### Add To Configuration

To add the widget, add the following code to the configuration:

```yaml
enhavo_dashboard:
    widgets:
        latest_articles:
            type: list
            label: Latest Articles
            provider:
                type: repository
                repository: enhavo_article.repository.article
                method: findLatestTitles
```

### Optional: Add Repository Method

Obviously, you need to add the respective method to your repository, if
you want to use the repository provider.
