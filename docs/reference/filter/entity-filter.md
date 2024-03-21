## Entity

The EntityFilter filters a property for entities as a dropdown.

If the number of possible entities is large, this filter will negatively
effect performance. If this is the case, consider using
AutoCompleteEntityFilter instead.

<ReferenceTable
type="entity"
className="Enhavo\Bundle\AppBundle\Filter\Type\EntityFilter"
>
<template v-slot:options>
    <ReferenceOption name="repository" type="text" :required="true"/>,
    <ReferenceOption name="property" type="text" :required="true"/>,
    <ReferenceOption name="label" type="text" :required="true"/>,
    <ReferenceOption name="method" />,
    <ReferenceOption name="arguments" />,
    <ReferenceOption name="choice_label" />
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

### repository

**type**: `string`

Either the name of a public service that points to the entity\'s
repository or the FQCN of the entity to be used on
EntityManager::getRepository().

``` yaml
filter:
    myFilter:
        repository: AppBundle\Repository\MyEntityRepository
```


### method

**type**: `string`

The name of the method of the repository which should be called. Default
is [findAll]{.title-ref}.

```yaml
filter:
    myFilter:
        method: findAll
```

### arguments

**type**: [array\|null]{.title-ref}

Optional arguments that will be added to the call of the repository
method. Default is [null]{.title-ref}.

```yaml
filter:
    myFilter:
        method: findBy
        arguments: { public: true }
```

### choice_label

**type**: `string|null`

Property of the entity that will be used as label in the options list.

```yaml
filter:
    myFilter:
        choice_label: title
```

### initial_value

**type**: `string|null`

If set, this filter will be initially have a set value and the list will
initially be filtered by this value. This must be a method of the
repository defined by the parameter [repository](#repository) which
returns either a single object or an array with at least one entry (the
first entry will be used). Default [null]{.title-ref}.

``` yaml
columns:
    myFilter:
        initial_value: findByFoo
```

### initial_value_arguments

**type**: `array`

Optional arguments that will be added to the call of the repository
method in parameter [initial_value](#initial_value). Default is
[null]{.title-ref}.

```yaml
columns:
    myFilter:
        initial_value: findByFoo
        initial_value_arguments: { foo: 'bar' }
```
