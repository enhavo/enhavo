## Migrate to 0.14

Add doctrine migrations

```php
    public function up(Schema $schema): void
    {
        $this->addSql("UPDATE routing_route SET variablePattern = '' WHERE variablePattern IS NULL");

    }
```