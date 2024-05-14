# Working with vue

Vue is a browser html rendering framework, that is widely used by frontend developers. Enhavo comes with a huge
of support function to integrate vue seamless. 


## Start a vue application

tbc. (Stimulus)


## Load components

tbc. (compiler pass)

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
enhavo_app:
    vue:
        route_providers:
            theme:
                type: file
                path: assets/theme/vue_routes.json
                groups: ['theme']
```

::: tip Note
We recommend to use groups with area names, because normally they need to loaded only in a specific area.
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
    resource: ../../assets/theme/vue_routes.json
    prefix: /
    type: vue
    defaults:
        _endpoint:
            type: area
            area: theme
            routes: ['theme', 'api']
            vue_routes: ['theme']
            template: theme/base.html.twig

```

If multiple endpoints are needed, you have to split your routes in separated files and load them separately.

