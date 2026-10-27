# Working with vue

Vue is a browser html rendering framework, that is widely used by frontend developers. Enhavo comes with a huge
of support functions to integrate vue seamless. 


## Start a vue application

Use the stimulus controller `vue` to load a component into your twig template. 
You can choose the component with the `data-vue-component-value` attribute and if needed, you can pass properties with
`data-vue-props-value`.

```twig
<div data-controller="vue" 
     data-vue-component-value="my-component" 
     data-vue-props-value="{{ {message: "hello world"}|json_encode|e }}">
</div>
```

## Load components

Normally all components in enhavo are registered globally on the vue app. So you can use them in any template by their name,
without importing them first.

To register your own components, add the `vue_components` compiler pass to your `container.di.yaml`. It walks through
the given directory recursively and registers each `.vue` file as a global component. The component name is the
filename in kebab case, so `components/article/ArticleList.vue` will be available as `article-list`.

```yaml
# assets/admin/container.di.yaml
compiler_pass:
    vue_components:
        path: '@enhavo/app/compiler-pass/vue-components.js'
        dir: './components'
        chunkName: 'admin'
        priority: 40
```

| Option      | Description                                                                 |
|-------------|-----------------------------------------------------------------------------|
| `dir`       | The directory to search for `.vue` files, relative to the `container.di.yaml` |
| `chunkName` | Optional chunk to put the components into                                     |
| `priority`  | The compiler pass priority. Make sure a vue factory service already exsits    |    

Components are loaded asynchronously with `defineAsyncComponent`, so a component is only loaded if it is rendered.

::: tip Note
Packages register their components with the `vue.component` tag on a service definition, e.g.
`{ name: vue.component, component: 'form-choice' }`. You can use this as well, if you want to register a single component
with a different name than its filename.
:::

### Overwrite components

A big advantage of global components is, that you can overwrite any component, even those from packages in
`node_modules`. Because components are always resolved by their name, you just have to register your own component
with the same name. Every place that uses this name will render your component instead.

::: warning
Only the name is important, not the directory. If two of your files have the same filename, the last one wins.
:::

## Services

Services from the javascript dependency injection container can be made available to all vue components of an app.
They are passed to the app with vue's [provide/inject](https://vuejs.org/guide/components/provide-inject.html) mechanism.

### Register a service

To provide a service, add the `vue.service` tag to its definition. The `service` parameter is the name under which the
service can be injected.

```yaml
# assets/admin/container.di.yaml
services:
    'manager/ArticleManager':
        from: './manager/ArticleManager'
        import: ArticleManager
        chunk: 'admin'
        arguments:
            - '@enhavo/app/client/Client'
        tags:
            - { name: vue.service, service: articleManager, reactive: true }
```

| Parameter  | Default | Description                                                                          |
|------------|---------|--------------------------------------------------------------------------------------|
| `service`  |         | The injection key, which is used to inject the service in a component                 |
| `reactive` | `false` | Wraps the service with vue's `reactive()`, so changes of its properties update the view |
| `lazy`     | `true`  | Provide a promise instead of the service. The service is only loaded on first access  |

### Lazy services

By default, services are lazy. That means the app doesn't wait for the service to be loaded from the container on 
startup. Instead, a promise is provided, and the service (including its chunk and dependencies) is only loaded when
someone awaits this promise for the first time. Every following access returns the same instance.

So if you inject a lazy service, you will not get the service itself, but a promise.

```ts
import {inject} from "vue";
import {ArticleManager} from "@/admin/manager/ArticleManager";

const articleManager = await inject<Promise<ArticleManager>>('articleManager');
```

::: warning
Using `await` on the top level of `<script setup>` turns the component into an async component, which can only be
rendered inside a [`<Suspense>`](https://vuejs.org/guide/built-ins/suspense.html) boundary. In most cases, you
should use the [`useAsyncService`](#use-async-service) composable instead.
:::

If a service needs to be available immediately, e.g. because it is used in a lot of components or must exist before
the app is mounted, you can disable lazy loading. Then the service is loaded before the app starts, and you can inject
it directly.

```yaml
tags:
    - { name: vue.service, service: articleManager, reactive: true, lazy: false }
```

```ts
const articleManager = inject('articleManager') as ArticleManager;
```

### Use async service

The `useAsyncService` composable injects a lazy service and returns a ref, which is `null` until the service is loaded.
So you don't need `await` in your setup function, and you can simply wait in the template until the service is ready.

```vue
<template>
    <div v-if="articleManager">
        <div v-for="article of articleManager.articles">{{ article.title }}</div>
    </div>
    <div v-else>Loading ...</div>
</template>

<script setup lang="ts">
import {useAsyncService} from "@enhavo/framework/composables/useAsyncService";
import {ArticleManager} from "@/admin/manager/ArticleManager";

const articleManager = useAsyncService<ArticleManager>('articleManager');
</script>
```

::: warning
Like `inject`, the composable must be called synchronously inside `setup()` or `<script setup>`. Don't call it 
after an `await` or inside a callback.
:::

#### Initialize the service

As a second argument you can pass an `init` callback. It is called with the loaded service, before the ref is set. If
it returns a promise, the composable waits for it. This way, a non-null ref always means the service is loaded
**and** initialized.

```ts
const articleManager = useAsyncService<ArticleManager>('articleManager', async (manager) => {
    await manager.load();
});
```

If `init` returns `false`, the ref stays `null`. You may use this for error handling, e.g. if the initialization
failed and the component should not work with a broken service.

```ts
const error = ref<string|null>(null);

const articleManager = useAsyncService<ArticleManager>('articleManager', async (manager) => {
    try {
        await manager.load();
    } catch (e) {
        error.value = 'Articles could not be loaded';
        return false;
    }
});
```

#### Depend on other services

Sometimes a service must be initialized after another one. Pass the refs of other `useAsyncService` calls as third
argument. The service is only initialized after all of these refs are loaded.

```ts
const userManager = useAsyncService<UserManager>('userManager', (manager) => manager.load());

const articleManager = useAsyncService<ArticleManager>('articleManager', (manager) => {
    manager.setUser(userManager.value.getUser());
}, [userManager]);
```

If a service fails to load or the `init` callback throws, the error is logged to the console and the ref stays `null`.

## Vue routing

For single page applications, it is recommended to use the vue router. The router maps urls to vue components, so if you
access a page like `/` it will load e.g. a homepage component, while `/users` will load the user list component.

::: tip Note
If you are not familiar with vue router, please read to [docs](https://router.vuejs.org/) first, before you go on reading. 
:::

::: warning Only HTML5 mode
Here we discuss only the HTML5 history mode, because here the url behave like a normal web page and we need some
integrations for symfony. For hash or memory mode we recommend do all work in javascript files, 
since normally we don't have further impacts to symfony.
:::

### Define vue routes

First of all, you have to define the routes for vue. The best place for the definition file 
is under the assets directory, e.g. `assets/theme/vue_routes.json`.

Here is a simple example for named and nested routes.

```json
[
  { "path": "/dashboard", "name": "app_dashboard_index", "component": "dashboard-index" },
  { "path": "/people", "name": "app_people_index", "component": "people-index", "children": [
    {"path": "/people/user", "name": "app_people_user", "component": "people-user-index"}
  ]}
]
```

Why do we need to define the routes in such a format instead defining it in a js file like it is mentioned in the 
vue router docs? Good question! Our goal is to have a single place for all vue routes. In HTML5 mode, the url
will change in the browser if you move to a different route. That works fine in the browser, but if you reload,
the http request arrives first in symfony and has no clue what to do with this url and may return a 404 not
found page. So it's necessary to introduce the urls also to symfony. That's why we created a simple format
for defining the routes and load it into the browser and in symfony.


### Load routes into the browser

To push your defined routes to the browser, first you need to load the file into the vue route provider. The `groups` 
option is optional and can be used to only load several routes later on.

```yaml
# config/packages/enhavo_app.yaml
enhavo_app:
    vue:
        route_providers:
            theme:
                type: file
                path: assets/theme/vue_routes.json
                groups: ['theme']
```

::: tip Note
We recommend to use groups with [area](/book/general/area.md) names, because normally they need to loaded only in a specific area.
:::

Now, you can use the `vue_routes` options in app or area endpoint to pass the data to the frontend. With a string or array
you can select which group will be loaded. A boolean `true` will load all routes, while `false` or `null` won't load any.

```yaml
my_route:
    path: /
    defaults:
        _endpoint:
            type: area
            vue_routes: ['theme'] // [!code highlight]
            template: theme/base.html.twig
```


### Load routes into symfony

To make the url available in symfony, you use the vue route loader (which is also a route). All routes will use the same endpoint, that is defined
here.

```yaml
# config/routes/vue.yaml
vue:
    resource: ../assets/theme/vue_routes.json
    prefix: /
    type: vue
    defaults:
        _endpoint:
            type: area
            routes: ['theme', 'api']
            vue_routes: ['theme']
            template: theme/base.html.twig

```

If multiple endpoints are needed, you have to split your routes in separated files and load them separately.

