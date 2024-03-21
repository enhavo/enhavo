## Search engines

### Enhavo Search Engine

Enhavo offers his own search and index engines which you have configured
by default in the [enhavo.yml]{.title-ref} under
[enhavo_search]{.title-ref}

``` yaml
search_engine: enhavo_search_search_engine
index_engine: enhavo_search_index_engine
```

With this `index engine` the content gets indexed into the database.
Therefor all words get separated from each other, simplified and then
stored in a database table. In addition to that every word gets a score
which determines the relevance to the other stored words.

When you are searching you use the `search engine` of enhavo. There you
have the possibility to get better search results by using the operators
`AND` and `OR`. If you want to exclude a word you can put `-` in front
of the word and if you want to get results with a whole phrase of words
just but them into `""`.

### Elastic-Search

If you don\'t want to use the enhavo search there is also the
possibility to use elasic-search. Just change the `search_engine` and
`index_engine` in the enhavo.yml.

``` yaml
search_engine: enhavo_search_elasticsearch_engine
index_engine: enhavo_search_index_elasticsearch_engine
```

Apart from that you have to download elastic search and start it.

For elastic-search you do not need the indexing strategy.
