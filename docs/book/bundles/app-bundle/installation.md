## Installation

```bash 
$ composer require enhavo/app-bundle
$ yarn install @enhavo/app
```

Add js dependency injection service file at `assets/admin/container.di.yaml`

```yaml
# 
imports:
    - path: '@enhavo/app/services/enhavo/*'
```

Create a vite config file at `assets/admin/vite.config.js`

```js
import { defineConfig, splitVendorChunkPlugin } from 'vite'
import vue from '@vitejs/plugin-vue'
import liveReload from 'vite-plugin-live-reload'
import path from 'node:path'
import 'dotenv/config'
import containerDIPlugin from '@enhavo/app/vite/rollup-plugin-container-di'
import {fantasticon} from "@enhavo/app/vite/fantasticon-plugin/plugin.js";
import {fantasticonSetting} from "@enhavo/app/vite/fantasticon-settings.js";

export default defineConfig({
    plugins: [
        vue(),
        splitVendorChunkPlugin(),
        containerDIPlugin(),
        fantasticon(fantasticonSetting({
            outputDir: path.resolve(__dirname, '../../public/build/admin'),
        })),
    ],

    // config
    root: path.resolve(__dirname),
    base: '/build/admin/',
    build: {
        // output dir for production build
        outDir: '../../public/build/admin',
        emptyOutDir: true,

        // emit manifest so PHP can find the hashed files
        manifest: true,

        rollupOptions: {
            input: '/entrypoints/application.js',
        }
    },
    server: {
        strictPort: true,
        port: process.env.VITE_ADMIN_PORT,
        origin: 'http://localhost:' + process.env.VITE_ADMIN_PORT
    },
    resolve: {
        preserveSymlinks: true,
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js'
        }
    },
})

```

Add ts config file at `assets/admin/tsconfig.json`

```json
{
  "compilerOptions": {
    "target": "ES2020",
    "useDefineForClassFields": true,
    "module": "ESNext",
    "lib": ["ES2020", "DOM", "DOM.Iterable"],
    "skipLibCheck": true,
    "strict": true,
    "noUnusedLocals": true,
    "noUnusedParameters": true,
    "noFallthroughCasesInSwitch": true
  }
}
```

Add entrypoint file at `assets/admin/entrypoints`

```js
import container from "../container.di.yaml";
import {Kernel} from "@enhavo/app/kernel/Kernel";
import $ from 'jquery';
import "fantasticon:icon";

window.$ = $;
window.jQuery = $;

let kernel = new Kernel(container);
kernel.boot();
```
