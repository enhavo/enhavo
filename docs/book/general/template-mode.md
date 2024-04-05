# Template mode

The template mode is an environment to help frontend developers to focus on their work, without having any dependency
to backend features. That means, you don't need any 3rd party software like a database or an elastic search application 
nor don't you need controller, endpoints, entities etc. What a frontend developers wants, is to create assets like
javascript and stylesheet and testing it with his html, normally with a template file.

To switch to template mode, you need to set the `APP_ENV` to template in the `.env.local`

```
APP_ENV=template
```

## Routing

If you are in the template mode, all routes defined by the backend are not available. 
So if you just started from scratch, no routes exists.

To add some test pages, you can define routing files inside the `/data/routes` directory. All files in this dir
will automatically be loaded.

### Template testing

Here is simple example in `/data/routes/base.yaml` for a route to test the base template `/template/theme/base.html.twig`

```yaml
base:
    path: /base
    defaults:
        _format: 'html'
        _endpoint:
            type: template
            template: 'theme/base.html.twig'
```

In template mode you generally use the type `template`. If the format is html you also have to define a template.
Normally, you will get an error if you visit `/base` here, because the template depends on some data, that is not delivered yet.

### Json route

If you want to have a json route you can just change the format. A template is not needed anymore.

```yaml
base:
    path: /base
    defaults:
        _format: 'html' // [!code --]
        _format: 'json' // [!code ++]
        _endpoint:
            type: template
            template: 'theme/base.html.twig' // [!code --]
```

### Add data to route

To add data to a route you have some options. You can load a file with `load` options, that accept a single path or an
array of paths. The path shouldn't start with `/` and don't need the data prefix, but the file itself must be inside the `/data` directory. 

The following example will load the two files `/data/navigation.json` and `/data/page/homepage.json`.

```yaml 
base:
    path: /base
    defaults:
        _format: 'html'
        _endpoint:
            type: template
            template: 'theme/base.html.twig'
            load: [navigation.json, page/homepage.json] // [!code focus]
```

It's also possible to load data inline with the `data` option.

```yaml 
base:
    path: /base
    defaults:
        _format: 'html'
        _endpoint:
            type: template
            template: 'theme/base.html.twig'
            data: // [!code focus]
              # define data here as yaml // [!code focus]
              navigation: // [!code focus]
                firstElement: // [!code focus]
                  title: Hello // [!code focus]
```

First the `load` option will load the files in the order of the array (if array provided) and then load the inline `data`. 
By default, the keys in the data will be overwritten within the next file if the same key exists already.

If you want to merge the data you can set the `recursive` option to `true` and if it's recursive you can define a `depth`
level if needed, otherwise it will be fully merged.

```yaml 
base:
    path: /base
    defaults:
        _format: 'html'
        _endpoint:
            type: template
            template: 'theme/base.html.twig'
            load: [navigation.json, page/homepage.json]
            recursive: true // [!code focus]
            depth: 3 // [!code focus]
```

## Data

We recommend to use separate files to define your data, and just use the inline `data` option to overwrite specific values
for test cases.

You can define data in `json`, `yaml` and `php` format.

Here are some examples to define the same data.

::: code-group

```json
{
    "items": [
        {
            "title": "Hello folks",
            "description": "Some description"
        }
    ]
}
```

```yaml
items:
    -
        title": "Hello folks"
        description": "Some description"
]
```

```php
<?php

use Enhavo\Bundle\AppBundle\Endpoint\Template\Loader;

return function(Loader $loader) {
    return [
        'items' => [
            [
                'title' => 'Hello folks',
                'description' => 'Some description',
            ] 
        ]
    ];
};
```

:::


::: tip
We recommend to use `json` because later you can easily grab the data from the live api and store it into your data files.
:::

### Expressions

It is also possible to use some expressions to help defining the data more quickly and efficient. 
An expression is string and starts with `expr:` followed by the expression itself. 
In enhavo we defined some helpful functions such as `load` or `ref`.

So if you want to load a separate file in a data file you can use `load`

```json
{
    "items": [
        "expr:load('items/first.json')",
        "expr:load('items/second.json')"
    ]
}
```

Read more about all expression functiony in the [reference section](/reference/template-expression/index)


## Variants

Variants give you the ability to define one route with different data. That helps you to keep the number of routes small.

In a route, you have the `variants` option to define the variants.

```yaml 
base:
    path: /base
    defaults:
        _format: 'html'
        _endpoint:
            type: template
            template: 'theme/base.html.twig'
            load: [navigation.json, page/homepage.json]
            variants:
                'block=text':
                    recursive: true
                    depth: 3
                    data:
                        resource:
                            content:
                                children:
                                    - "expr:load('block/text.json')"
                    description: With text block
                'navigation=user':
                    load: 'navigation/main.json'
                    description: User navigation
```

The key of the variant must be a key value query like `block=text` or any `key=value`. This will be used as a query parameter
in the url. So `/base?block=text` will load it accordingly key. So multiple variants could be loaded if you add them to your
query string. `/base?block=text&navigation=user` for example will load both variants at the same time.

The normal template data will be applied first und then the active variants in order of their definition in the route.
For each applied variant you can use `recursive` and `depth` to control the merging behaviour.

## List routes

To get an overview over all your template routes, you can list them with the command:

```
$ bin/console debug:endpoint:template
```

All template routes and their variants will be displayed with a description if provided.

::: tip
Give each route and variant a clear and short description of what it is going to test, to help other developer
to understand the need of the route and variant.
:::


## Media files

To show test media files, we have to define two routes, because if a media file is loaded, it will always fire a
request to display the media file. To determine the url, the system will look up into the route definitions. 
In case of media files it will look for the name `enhavo_media_file_show` and `enhavo_media_file_format`.

Because the template mode doesn't come with predefined routes we have to make sure they exist in `/data/routes`.

```yaml
# /data/routes/_routes.yaml

enhavo_media_file_show:
    path: /file/show/{id}
    defaults:
        _endpoint:
            type: template_file

enhavo_media_file_format:
    path: /file/show/format/{format}/{id}
    defaults:
        _endpoint:
            type: template_file
```

The `template_file` is an endpoint type, that will take the `id` and the `format` parameter
from the url and check the folder `/data/media/file` for a file with the name as id. The file extension will be ignored.

So the url `/file/show/1` will expect a file at `/data/media/file/1.png`, whereas a format url `/file/show/format/header/1`
will expect a file at `/data/media/format/header/1.png`. If the format was not found, it will display the corresponding file in the file 
directory and if the endpoint can't find it either, it will throw a 404 response.

## Recommendations

### File structure

To organise your data, we recommend following file structure:

```
/data
├─ navigation.json
├─ routes
│  ├─ _routes.yaml
│  ├─ page.yaml
│  └─ article.yaml
├─ block
│  ├─ text-block.json
│  └─ picture-block.json
├─ article
│  ├─ one.json
│  └─ two.json
├─ navigation
│  ├─ main.json
│  └─ footer.json
├─ media
│  ├─ file
│  │ └─ 1.jpg
│  └─ format
│    └─ header
│       └─ 1.jpg
└─ page
   ├─ homepage.json
   └─ imprint.json
```

* Content blocks should be stored in `/data/block`. If you use the block maker, a sample could also be generated at this path.
* Data, that can be reapplied, have it's on folder and should be assembled to the data with `load` and `ref` expressions.
* Every main resource has its own directory, like `page`, `article`, `navigation`
* Every main resource has its own routing file
* Use the `/data/routes/_routes.yaml` for general route definition like media or vue routes
* Global data, that will be applied to many routes, can be stored in `/data` directory 

### Use ref to origin data

First, you have to check what is the origin data, or where does the data really belongs to.

Often it's the main resource, so think of the `title` that belongs to `page`, so the origin is the page resource.

```json
{
  "resource": {
    "id": 1,
    "title": "Home",
    "url": "expr:url('page/homepage')"
  }
}
```

So any data, that references data from the main resource should use the `ref` expression.

Here an example for the navigation.

```json
{
  "id": 1,
  "nodes": [
    {
      "id": 1,
      "name": "page",
      "children": [],
      "subject": {
        "id": "expr:ref('page/homepage.json', 'resource.id')",
        "title": "expr:ref('page/homepage.json', 'resource.title')",
        "url": "expr:ref('page/homepage.json', 'resource.url')"
      }
    }
  ]
}
```

### Use url for every link {#recommendations-url}

Every link you use, must be wrapped in an url expression. That is important to navigate through the template mode in the dev environment.

```json
{
  "resource": {
    "id": 1,
    "title": "Home",
    "url": "expr:url('page/homepage')"
  }
}
```


## Template mode in dev env

A frontend developer will use the template mode to test his templates and assets, while a backend developer will mainly
use the dev environment to load the full application. But if we would load routes from both environments, we will
end up in conflicts, e.g. both expect different behaviour for the `/` url. The backend developer want the dynamic
route to take over, while the frontend developer may define a template route here.

So all template routes in `dev` environment will automatically have a `/template` prefix, so the backend developer can 
still access the test data of the template mode.

That why it's important to follow the recommendations like [use url for every link](#recommendations-url)

