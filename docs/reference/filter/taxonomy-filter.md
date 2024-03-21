## Taxonomy

The TaxonomyFilter filters by a taxonomy.

<ReferenceTable
type="taxonomy"
className="Enhavo\Bundle\TaxonomyBundle\Filter\TaxonomyFilterType"
>
<template v-slot:options>
    <ReferenceOption name="taxonomy" type="text" :required="true"/>,
    <ReferenceOption name="property" type="text" :required="true"/>,
    <ReferenceOption name="label" type="text" :required="true"/>
</template>
<template v-slot:inherit>
    <ReferenceOption name="label" />,
    <ReferenceOption name="locale" />,
    <ReferenceOption name="format" />,
    <ReferenceOption name="initial_active" />,
    <ReferenceOption name="initial_value" />,
    <ReferenceOption name="condition" />,
    <ReferenceOption name="width" />,
    <ReferenceOption name="permission" />,
    <ReferenceOption name="component" />
</template>
</ReferenceTable>

### taxonomy

**type**: `string`

The slug of the Taxonomy.

```yaml
columns:
    myFilter:
        type: taxonomy
        operator: article_category
```

### initial_value_method

**type**: `string|null`

Defines the repository method to use when setting the initial value.
Default [findOneByNameAndTaxonomy]{.title-ref}. If multiple results are
returned by the method, the first one is used.

```yaml
columns:
    myFilter:
        initial_value: Foo
        initial_value_method: findOneByBar
```

### initial_value_arguments

**type**: `string|null`

Optional arguments that will be added to the call of the repository
method. Default is an array containing the values of parameters
[initial_value]() and [taxonomy](#taxonomy).

```yaml
columns:
    myFilter:
        initial_value: Foo
        initial_value_method: findOneByFoo
        initial_value_arguments: { foo: 'bar' }
```
