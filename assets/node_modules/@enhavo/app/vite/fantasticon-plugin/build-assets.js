import { promises as fs } from "node:fs";
import { join, basename } from "node:path";
import { URL } from "node:url";
import { dirname, relative } from "path/posix";
import { assetPath, assetType, withoutQuery } from "@enhavo/app/vite/fantasticon-plugin/paths.js";
import { watchDebounced } from "@enhavo/app/vite/fantasticon-plugin/util.js";

const fontAssetTypes = [
    "svg",
    "ttf",
    "woff",
    "woff2",
    "eot",
];

const otherAssetTypes = [
    "ts",
    "css",
    "json",
    "scss",
    "sass",
    "html",
];

async function assetBuilder(config, generateFonts) {

    if (generateFonts === undefined) {
        generateFonts =  (await import("fantasticon")).generateFonts;
    }

    let assets = {};
    let watcher;

    async function build(writeToDisk = false)
    {
        const cfg = { ...config };
        if (!writeToDisk) {
            cfg.outputDir = undefined;
        }
        await fs.mkdir(cfg.inputDir, { recursive: true });
        cfg.inputDir = relative(".", cfg.inputDir);

        // assetVersion is not a fantasticon config, so we delete and reapply it
        const assetVersion = cfg.assetVersion;
        delete cfg.assetVersion;

        const results = await generateFonts(cfg, writeToDisk);

        cfg.assetVersion = assetVersion;

        assets = results.assetsOut;
        if (assets.ts) {
            const ts = assets.ts;
            delete assets.ts;
            const filePath = relative(".", assetPath(config, "ts"));
            const dir = dirname(filePath);
            await fs.mkdir(dir, { recursive: true });
            await fs.writeFile(filePath, ts);
        }

        if (writeToDisk) {
            const files = [];
            for (let writeResult of results.writeResults) {
                files.push(basename(writeResult.writePath));
            }

            const manifestFilePath = join(cfg.outputDir, 'fantasticon.json');
            await fs.writeFile(manifestFilePath, JSON.stringify({
                name: cfg.name,
                assetVersion: cfg.assetVersion,
                files: files,
            }));
        }

        return assets;
    }

    function has(ext)
    {
        return assets[ext] !== undefined;
    }

    function get(ext, buffer = false)
    {
        if (buffer) {
            return assets[ext] instanceof Buffer ? assets[ext] : Buffer.from(assets[ext]);
        }

        return assets[ext];
    }

    function match(path, assetTypes = [...fontAssetTypes, ...otherAssetTypes], base)
    {
        let basePath = '';
        if (base) {
            let url = new URL(base);
            basePath = url.pathname;
            if (basePath.startsWith("/")) {
                basePath = basePath.slice(1);
            }
        }

        path = withoutQuery(path);

        if (path.startsWith("/")) {
            path = path.slice(1);
        }

        const ext = assetType(path);
        if (!assetTypes.includes(ext)) {
            return undefined;
        }

        if (assetPath(config, ext, basePath) === path) {
            return get(ext);
        }

        return undefined;
    }

    function watch(ws, event)
    {
        if (watcher) {
            return end;
        }

        build().then(() => {
            watcher = watchDebounced(config.inputDir, async () => {
                await build();
                ws()?.send({ type: "custom", event, data: {} });
            });
        });

        return end;
    }

    function end()
    {
        if (watcher) {
            watcher.close();
            watcher = undefined;
        }
    }

    return { build, has, get, watch, end, match };
}

export { assetBuilder, fontAssetTypes, otherAssetTypes };
