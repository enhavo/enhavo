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
- Update services with Regex `enhavo_([a-z]+).factory.([a-z_]+)` => `enhavo_$1.$2.factory`
- Update services with Regex `enhavo_([a-z]+).repository.([a-z_]+)` => `enhavo_$1.$2.repository`
- Update services with Regex `app.repository.([a-z_]+)` => `app.$1.repository`
- Update services with Regex `app.factory.([a-z_]+)` => `app.$1.factory`

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