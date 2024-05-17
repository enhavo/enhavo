## Search

To execute a search programmatically, you have to inject the search engine, create a filter and search. With the `ResultConverter`
you can change the results and highlight the search term in the text.

```php
namespace App\Service;

use Enhavo\Bundle\SearchBundle\Engine\SearchEngineInterface;
use Enhavo\Bundle\SearchBundle\Result\ResultConverter;
use Enhavo\Bundle\SearchBundle\Engine\Filter\Filter;

class MyService
{
    public function __construct(
        private SearchEngineInterface $searchEngine,
        private ResultConverter $resultConverter,
    ) {}
    
    public function search() 
    {
        $filter = new Filter();
        $filter->setTerm('Hello');
        $filter->setFuzzy(true);
        $filter->setLimit(10);
        
        $summary = $searchEngine->search($filter);
        
        foreach ($summary->getEntries() as $entry) {
            $entity = $entry->getSubject();
        }
        
        $results = $resultConverter->convert($summary, $term);
        
        foreach ($results as $entry) {
            $title = $entry->getTitle();
            $text = $entry->getText(); # highlighted text
            $entity = $entry->getSubject();
        }
    }
}
```
