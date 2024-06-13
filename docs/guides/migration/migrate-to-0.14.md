## Migrate to 0.14

Add doctrine migrations

```php
    public function up(Schema $schema): void
    {
        $this->addSql("UPDATE routing_route SET variablePattern = '' WHERE variablePattern IS NULL");
        $this->addSql("UPDATE media_file SET library = 0 WHERE library IS NULL");
        $this->addSql("UPDATE media_file SET parameters = 'a:0:{}' WHERE parameters = 'N;'");
    }
```


Add `#[Groups(['endpoint.block'])]` to your block properties

```
# security.yaml
IS_AUTHENTICATED_ANONYMOUSLY to PUBLIC_ACCESS
```

Add interface to user `LegacyPasswordAuthenticatedUserInterface`