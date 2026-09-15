## Create Entity

### Creating an Entity Class

Suppose you´re building an application where products with three
properties `id`, `title` and `price` need to be created as a Product
object. This class has to be in the directory `src/Entity` and the
namespace is `App\Entity`;

At this point, our object class looks like:

```php
<?php
// src/Entity/Product.php

namespace App\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;

class Product implements ResourceInterface
{
    protected $id;

    public function getId()
    {
        return $this->id;
    }
}
```

As you can see, this class also implements a interface called
`ResourceInterface` . Why we do that, will be explained
[later](http://docs.enhavo.com//get-started/create-routing.html), but it
has to be mentioned now, because it is the reason, why our Product class
needs the id property. This id will be used as our `primary key` in our
database table and the `getId()`-Function is the only function we need,
to include this interface.

For the other two properties, we also need a variable and as common in
classes, each of them has its own public getter and setter methods.

To define the database type of the variables, we use annotations. Our id
as primary key has to be unique, the best datatype is an integer. The
title of a product is usually a word, so we mark the title as string.
The price can be an integer, because we can save 1.78 USD as 178¢.

If we want to save an entity in a database-table, we need a column for
each class-property we want to save. For our example the columns would
be id, title and price. To create and map them we will use one more time
annotations. In that case, they start with `@ORM\...`

Let's take a look at our code:

```php
<?php
// src/Entity/Product.php

namespace App\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\Table(name="app_product")
 */
class Product implements ResourceInterface
{
    /**
     * @var integer
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var float
     * @ORM\Column(type="integer", nullable=true)
     */
    private $price;


    /**
    * @return int
    */
    public function getId()
    {
        return $this->id;
    }

    /**
    * @return string
    */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title
     * @param string $title
     * @return Product
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set price
     * @param integer $price
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }
}
```

We´ve already talked about the annotations for our attributes. We can
also use PHP-annotations for functions, as you can see in our example.
For more information about annotations, take a look at this
[documentation](https://php-annotations.readthedocs.io/en/latest/getting-started.html).

Or for the doctrine annotations, have a look at the doctrine annotation
[reference](https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/reference/annotations-reference.html)

An optional, but important annotation is `@ORM\Table`, which defines the
table name for this entity. A good structured and well-named database is
always a goal which should be sought.

One step is mapping all properties of the entity to columns in the
table. We can do this with `@ORM\Column(type="integer")`. Other common
datatypes are `string`, `float`, `boolean` etc. You can find a full list
and way more about basic mapping in doctrine
[here](https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/reference/basic-mapping.html).

Another option for the column is, if the value in the column can be NULL
or not. We define that with `nullable=true/false` (the default value is
false).

The id needs some special annotations, for example `@ORM\Id`, which mark
this property as `primary key` in a table and
`@ORM\GeneratedValue(strategy="AUTO")` specifies which strategy is used
for identifier generation for an instance variable which is annotated by
id.

At
[this](https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/reference/annotations-reference.html)
page, a reference of all Doctrine annotations is given with short
explanations on their context and usage.

Awesome! We´ve just created our first PHP-class, which is also called
`Entity` in Symfony.

### Creating an Repository Class

Our next step is, how we can easily save our entity in our database,
with the powerful Doctrine ORM, which helps us to manage our database
and synchronize it with our project.

Before we mark our entity class with `@ORM\Entity` and define the
`repositoryClass`, which we will need for more complex database queries,
in order to isolate, reuse and test these queries, it\'s a good practice
to create this custom repository class for your entity.

The common path for the Repository-classes are `src/Repository`.

``` php
<?php
// src/Repository/ProductRepository

namespace App\Repository;

use Enhavo\Bundle\AppBundle\Repository\EntityRepository;

class ProductRepository extends EntityRepository
{

}
```

An empty Repository is very unspectacular, but we will learn how useful
they can be later.

### Update Database

After this, we have a useable Product class with all important
information for Doctrine to create the product table. But after all, we
still have no table in our database, but creating it is very comfortable
now, just run:

``` 
$ php bin/console doctrine:schema:update --force
```

It seems to be nothing special, but this command does a lot! It checks,
how your database should look like (based on the mapping information
we´ve defined with the annotations in our product class before) and
compares it with how the database actually looks like. Only the
differences will be executed as SQL statements to update the database.

### Well-intentioned Advices

An even better way to synchronize your database with the mapping
information from your project is via migrations, which are as powerful
as the schema:update command. In addition, changes to your database
schema are safely and reliably tracked and reversible.

Even it is quite powerful, the doctrine:schema:update command should
only be used during development.

::: warning
It should never be used in a production environment with important
information in your database.
:::

You can also create or update an entity with the command:

``` 
$ php bin/console make:entity
```

which will ask you everything you need to create or update an entity.
You will find a good explanation in the [Symfony
Docs](https://symfony.com/doc/current/doctrine.html#creating-an-entity-class)
, but for the first time, we recommend to create your classes without
this command, to understand how they work.
