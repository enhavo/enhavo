## Auto suggest

With auto suggest, you can auto complete single words. In the background you have to perform a search and the engine will 
analyze the results for words that start with the search term. So a fuzzy search will be ignored here, because we search occurrences
with an exact match.

```php
class MyService
{
    public function suggest() 
    {
        $filter = new Filter(); // [!code focus]
        $filter->setTerm('Hel'); // [!code focus]
        $filter->setLimit(10); // [!code focus]
        
        $words = $searchEngine->suggest($filter); // [!code focus]
        
        foreach ($words as $word) { // [!code focus]
            
        } // [!code focus]
    }
}


```