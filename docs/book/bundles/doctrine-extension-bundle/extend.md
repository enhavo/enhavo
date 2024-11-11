## Extend

Time by time you have to extend a entity. Doctrine is a powerful ORM and
of course this case is covered at all. Doctrine comes with several type
of inheritance. The Mapped Superclass, Single- and Multiple table
inheritance.

But they have all something in common. You have declare inheritance in
the mapping file of entity you want to extend from. This is no problem
if you can edit this file. But what if this file live in the vendor
directory because it is package?

Then you can only hook into doctrine with a listener and change the
mapping information of the entity. This is indeed uncomfortable. So this
extension will help you to make it a bit more easy by only adding some
meta information to the config.

Because the most use case is to extend a given entity to add a few
properties you need, the only supported inheritance type is the single
table inheritance. So let\'s have a deeper look how it works.

### Single table inheritance

Imagine we have a simple class `Person` with one property `id` and
`name`

```php
class Person
{
    private $id;
    private $name;

    // ... Getter and setters
}
```

Then the resulting table is very easy and straight forward.

``` 
+----+---------------+
| id | name          |
+====+===============+
| 1  |  Bob          |
+----+---------------+
```

But now we will extend our `Person` entity and add some more information
e.g. `gender`

```php
class GenderPerson extends Person
{
    private $gender;

    // ... Getter and setters
}
```

Because we are using single table inheritance the result will be of
course one table, which will be extended by the properties of our
`GenderPerson` and a further discriminator column, so we know what kind
of class we are talking about.

``` 
+----+---------------+---------------+----------+
| id | name          | gender        | discr    |
+====+===============+===============+==========+
| 1  | Bob           | male          | root     |
+----+---------------+---------------+----------+
| 2  | Alice         | female        | app      |
+----+---------------+---------------+----------+
```

### Configuration

To get the single table inheritance, we only need to tell
`EnhavoDoctrineExtensionBundle` that our class `GenderPerson` is
extending `Person` and what is the value of the `discr` column. The
entity which you will extend from will automatically have the value
`root`.

Just add following to your config.

```yaml
enhavo_doctrine_extension:
    metadata:
        App\Entity\GenderPerson:
            extends: App\Entity\Person
            discrName: 'app'
```

### Multiple hierarchy

Multiple inheritance are also possible. Just add another config and
change the `discrName` so it is unique over your hierarchy.
