## Option

The OptionFilter filters a property for specific options.


<ReferenceTable
type="option"
className="Enhavo\Bundle\AppBundle\Filter\Type\OptionFilter"
>
<template v-slot:options>
    <ReferenceOption name="options" type="text" :required="true"/>,
    <ReferenceOption name="property" type="text" :required="true"/>,
    <ReferenceOption name="label" type="text" :required="true"/>,
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

### options

**type**: `array<string>`

Define the options, which the user can choose

```yaml
filter:
    myFilter:
        options:
            Foo: Bar
            Hello: World
```

### initial_value

**type**: `string|null`

If set, this filter will be initially have a set value and the list will
initially be filtered by this value. This must be NULL or one of the
array keys in the parameter [options](#options). Default is
`null`.

``` yaml
columns:
    myFilter:
        initial_value: Foo
```
