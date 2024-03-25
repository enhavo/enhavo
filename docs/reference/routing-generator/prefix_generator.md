## Prefix

Generate a route prefix.

### unique

If set to true, the generator will check if the generated prefix already
exists. If it exists, it will add a number to the end.

### unique_property

If `unique`is true and you add multiple properties. Only the
unique_property will increase and not the whole prefix string.

```yaml
type: prefix
unique: true
properties: ['title', 'subTitle']
unique_property: title
format: '/{title}/{subTitle}'
```

Will output a string like `/title-1/subtitle`.

### format

Format the prefix. Use the properties in curly brackets as placeholder.

```yaml
type: prefix
properties: ['title', 'subTitle']
format: '/{title}/{subTitle}'
```

### route_property

Define where to find the route property in resource.

### overwrite

If set to true, the prefix will generated every time you save.

### properties

Define the properties to generate a prefix
