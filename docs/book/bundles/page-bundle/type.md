## Type

If you need to show e.g a different design for some pages, you can use the type attribute of page. 
In the config under `types` you can define all available types.

```yaml 
# config/packages/enhavo_page.yaml
enhavo_page:
    types:
        my_type:
            label: MyType

```

The attribute type will be normalized within the `endpoint` serialization groups.

```twig
<body class="{%if resource.type %}type-{{ resource.type }}{% endif %}">

</body>
```

