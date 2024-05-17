# Template development

## Javascript

## Styles

## Images

## Navigation rendering

```twig
{% for node in navigation.main.nodes %}
    {{ navigation_node_render(node, 'main') }}
{% endfor %}
```

## Block rendering

```twig
{{ block_render(resource.content) }}
```

## Media rendering

```twig
{{ media_url(resource.picture) }}
```

