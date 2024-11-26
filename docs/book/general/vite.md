# Vite

We use vite to control and build our assets. Vite itself is a great tool for bundling. For more information 
about vite, read the [docs](https://vite.dev/guide/why.html)

## Config

Add a build config to the vite configuration.

```yaml
enhavo_app:
    vite:
        builds:
            admin:
                port: '%env(VITE_ADMIN_PORT)%'
                manifest: '%kernel.project_dir%/public/build/admin/.vite/manifest.json'
                base: '/build/admin' 
```

You may find the values in your `vite.config.js`.

- `port` of the vite server `server.port`. Default `5200`
- `manifest` path of the vite build manifest file `build.manifest`
- `base` vite base path `base`. Default `/`
- `host` of the vite server `server.host`. Default `localhost`

::: warning Define a port
It is important that you define a fix port in your `vite.config.js` config, because if you start the vite server without
defining a port, vite will start it at port 5200 and if the port is occupied it just increase the port number. So
the symfony application, don't know what is the real port belong to the application if multiple vite server are started.
:::


## Mode

You can define a mode in the configuration, where to find the assets.

```yaml
enhavo_app:
    vite:
        mode: build
```

The mods are:
- `dev` Always using the vite dev server to get assets
- `build` Always using the build directory to get assets
- `test` Test if dev server is available and use it or use the build directory


## Static assets

For static assets, which are not referenced by any other file, you can use the vite public folder.
For example, you have an image `logo.png` and you want to display it on theme, you save it under
`assets/theme/public/images/logo.png`. All files in the public folder will be copied to the 
build directory on build time. So the file is available under the path `public/build/theme/images/logo.png`.


```
├── assets
│    ├── admin
│    └── theme
│         └── public
│              └──images
│                   └──logo.png <-- Save your file here
└── public
     └── build
          ├── admin
          └── theme
               └──images
                    └──logo.png <-- Automatically copied here
```

When you working in vite dev mode, the `logo.png` is not copied to the `public/build/theme/images/logo.png`,
instead it is available on the vite dev server. Use the twig `asset` function with package `vite` and it will automatically
use the file from the vite dev server, if it is available.

```twig
<img src="{{ asset('/build/theme/images/logo.png', 'vite') }}" />
```

