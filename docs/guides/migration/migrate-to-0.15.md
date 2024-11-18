## Migrate to 0.15


### Add bundles

- ResourceBundle
- RevisionBundle

### Use new Type system

- Action
- Filter
- Menu

### Migrate sylius resources

- Migrate resources `$ enhavo migrate-enhavo-resource config/packages/sylius_resource.yml src/config/routes/admin templates`
- Update services with Regex `([a-z]+).factory.([a-z_]+)` => `$1.$2.factory`
- Update services with Regex `([a-z]+).repository.([a-z_]+)` => `$1.$2.repository`
- Update parameters with Regex `([a-z_]+).model.([a-z_]+).class` => `$1.$2.model.class`
- Update parameters with Regex `([a-z_]+).factory.([a-z_]+).class` => `$1.$2.factory.class`
- Update parameters with Regex `([a-z_]+).repository.([a-z_]+).class` => `$1.$2.repository.class`

### Config

* add `config/packages/vite.yaml`
* add `config/packages/area.yaml`
* add `config/packages/vue.yaml`
* update `access_control` in `config/packages/security.yaml`
* update `config/packages/assets.yaml`


### Vite

`.env`

```
VITE_THEME_PORT="5142"
VITE_ADMIN_PORT="15142"
```

`package.json`

```
"type": "module",
```