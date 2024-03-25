## Search engines

Enhavo comes with some build in search engines. The `database`, `elasticsearch` and `null` engine.
Both implements the `Enhavo\Bundle\SearchBundle\Engine\SearchEngineInterface`. 

### Database search engine

With the database search engine the content gets indexed into the database.
So no extra service is needed. Therefor all words get separated from each other, simplified and then
stored in a database table. In addition to that every word gets a score
which determines the relevance to the other stored words.

The downside is, that if your amount of data getting bigger, this engine could get very slow.

By default, the search term has some addons. You
have the possibility to get better search results by using the operators
`AND` and `OR`. If you want to exclude a word you can put `-` in front
of the word and if you want to get results with a whole phrase of words
just but them into `"`.

Configure the database engine with:

```
# .env.local
SEARCH_DSN=database://null
```

### Elastic search engine

With the elastic search engine you can index your data in an elasticsearch service and use their power and performance.

```
# .env.local
SEARCH_DSN=elastic://localhost:9200/elastic_search
```

### Null engine

The null engine is for `dev` mode, if you want to turn off the indexing. It will always return an empty result.

```
# .env.local
SEARCH_DSN=null://null
```





