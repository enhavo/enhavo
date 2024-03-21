## Routing and Configuration

### Add configuration

The next step is adding the new resource consisting of the Product
entity and the associated ProductType form to the Enhavo configuration.
In the `config/packages/enhavo.yml` we need to add the menu entry as
well, so we can navigate to our product. There are already some default
types for the menu, used by the Enhavo standard menu items. In order to
add our product to the menu we should use the base type, which we extend
with details of our product:

```yaml
# config/packages/enhavo.yml

parameters:
    locale: en

enhavo_app:
    menu:
        dashboard:
            type: dashboard
        user:
            type: user_user
        group:
            type: user_group
        # add this lines for the menu entry
        product:
            type: base
            label: Product
            route: app_product_index
            icon: widgets

# add this lines to the end of the file
sylius_resource:
    resources:
        app.product:
            classes:
                model: App\Entity\Product
                controller: Enhavo\Bundle\AppBundle\Controller\ResourceController
                form: App\Form\Type\ProductType
                repository: App\Repository\ProductRepository
```

### Generate routes

After we add our new resource to the configuration, we still have to
connect the resource, form and actions (Create, View, Edit, Delete) with
the still existing Resource Controller. Normally, that would be much
work, but Enhavo gives us a Command, which generates the basic routing
after some simple questions.

```bash 
$ bin/console make:enhavo:routing

 What is the name of the resource?:
 > Product

 What is the bundle name? Type "no" if no bundle is needed:
 > no

 Is the resource sortable? [yes/no]:
 > no

 created: config/routes/admin/product.yaml
```

The routes will now be saved automatically to
`config/routes/admin/product.yaml`. Later we will lear how we can edit
this file to customize our user interface.

We have to dump all new routes with the following command, otherwise,
they are not in our public application web folder and Enhavo won´t find
them.

```bash 
$ yarn routes:dump
```

### Finished

Now we have done all steps to add our own model to enhavo. Just start
your webserver again if it\'s not running and login into the admin.

```bash 
$ php bin/console server:run
```

See the result in your browser under `http://127.0.0.1:8001/admin`. You
should able to create and edit products.
