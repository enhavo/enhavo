## Strategies

For the indexing strategy you can choose between `index`, 
`index_new` and `reindex`

### Index

If you choose the `index` strategy, it means that every entry of your
entity you save gets indexed immediately. The advantage is that you can
search for the content just after you saved it but if your content
contains long text it probably takes a while to save it.

### Index_new

With this strategy only new entries get indexed immediately. If you
update an entry it gets marked and updated when you call the reindex
function.

### Reindex

In this case every entry you save gets marked. Only when you the reindex
function the entries get indexed.

### Start cron and run command

If you use the `index_new` or `reindex` strategy you have to call the
reindex function to index or update entries. The following command can
do this for you:

```bash
app/console enhavo:search:index
```

The easiest way you can call the command automatically is \... (CRON)
