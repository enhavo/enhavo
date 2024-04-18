# Vite

We use vite to control and build our assets. Vite itself if great tool for bundling.


## Config

```yaml
enhavo_app:
    vite:
        builds:
            admin:
                port: '%env(VITE_ADMIN_PORT)%'
                manifest: '%kernel.project_dir%/public/build/admin/.vite/manifest.json'
```

## Mode


```yaml
enhavo_app:
    vite:
        mode: build
```

The mods are `dev`, `build` or `test`.
