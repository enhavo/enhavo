## Add search for an entity

To make an entity searchable, just create a search.yml for your entity.
Put the search.yml into `AcmeBundle/Resource/config`.

```yaml
Acme\AcmeBundle\Entity\Acme:
    properties:
        title:
            - Plain:
                weight: 10
                type: title
        text:
            - Html:
                type: text
                weights:
                  h1: 12
                  h2: 10
                  p: 7
        tag:
            - Collection:
                - Plain:
                    weight: 3
                    type: tag
        category:
            - Collection:
                entity: Enhavo\Bundle\CategoryBundle\Entity\Category
        grid:
            - Model
```

Under [properties]{.title-ref} you can define the fields of your entity
that should be indexed. Under each field you tell the search engine what
kind of content is in the current field. If you have just a plain text
you choose `Plain` field:

```yaml
title:
    - Plain:
        weight: 10
        type: title
```

If there is a HTML text which contains HTML-tags, you should choose the
`Html` field.

```yaml
text:
    - Html:
        type: text
        weights:
          h1: 12
          h2: 10
          p: 7
```

With the [type]{.title-ref} you can set the type as which the field gets
stored in the database and with the [weight]{.title-ref} you choose the
weight of the field compared to all the other fields. If you have the
[Html]{.title-ref} field you can give weights to each HTML-tag (if you
just skip the [weights]{.title-ref} there are default weights for the
HTML-tags).

Attention: If you use elastic-search you can not define the weight for
each HTML-tag. Just use the weight like explained for the
[Plain]{.title-ref} type.

If your field is a `Collection`, you can choose between to types.

```yaml
tag:
    - Collection:
        - Plain:
            weight: 3
            type: tag
category:
    - Collection:
        entity: Enhavo\Bundle\CategoryBundle\Entity\Category
```

The first type means that the collection are just fiels of type text or
HTML. The second one means that your collection consists of an other
entity. In this case the other entity has to have a search.yml, too.

The last field is a `Model`.

```yaml
grid:
    - Model
```

In this case the search engine takes the class of the given field and
looks for the search.yml in belonging bundle. This assumes that the
bundle has a own search.yml.
