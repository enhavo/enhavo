## Indexing

To index an entity we have to add some metadata in order to tell what we would like to index. By default, nothing will be indexed.
In this example we define metadata for the entity `App\Entity\Book`.

```yaml
enhavo_search:
    metadata:
        App\Entity\Book:
            index:
                title:
                    type: text
                    weight: 30
                    property: title
                description:
                    type: text
                    weight: 5
                    property: title
                chapters:
                    type: collection
                    property: chapters
```

The properties `title` and `description` are just a string, so we use the
type `text`. The key is not enough to tell the name of the property, you have to configure it explicit with `property`.
You can add als the `weight` options, to tell that `title` is more important then `description`.

Book has also `oneToMany` collection of chapters. So we use the type `collection`. Other types may be `model` for e.g. `manyToOne`
relations.

You can find more types in the [reference section](/reference/search-index/index.md) or [write your own type](/guides/search/index.md#create-index-type).

::: warning Note
You can only index root resources. They have to be defined as well.
:::

Define root resources:

```yaml
enhavo_search:
    index:
        classes:
            - App\Entity\Book
            - Enhavo\Bundle\PageBundle\Entity\Page
            - Enhavo\Bundle\ArticleBundle\Entity\Article
```

To index an entity, you can just inject the engine to any service and index the resource.

```php
namespace App\Service;

use Enhavo\Bundle\PageBundle\Repository\PageRepository;
use Enhavo\Bundle\SearchBundle\Engine\SearchEngineInterface;

class MyService
{
    public function __construct(
        private SearchEngineInterface $searchEngine,
        private PageRepository $repository,
    ) {}
    
    public function index() 
    {
        $page = $this->repository->find(1);
        $this->searchEngine->index($page);
    }
}
```

If you want to index all root resources at once just execute the `enhavo:search:index` command.

```bash
$ bin/console enhavo:search:index
```

On every `enhavo_app.post_create`, `enhavo_app.post_update` and `enhavo_app.pre_delete` 
the index will be automatically updated if it is a root resource.
