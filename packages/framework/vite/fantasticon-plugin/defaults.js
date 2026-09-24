import path from 'node:path';
import { fontAssetTypes } from "@enhavo/framework/vite/fantasticon-plugin/build-assets.js";

export function defaults(options)
{
    const name = options.name ?? "icons";

    return {
        name,
        inputDir: name === "icons" ? "icons" : `icons/${name}`,
        outputDir: "./dist",
        fontTypes: [...fontAssetTypes],
        assetTypes: ["ts", "css", "json", "html"],
        fontsUrl: "",
        prefix: name,
        descent: 60,
        normalize: true,
        assetVersion: Date.now(),
        templates: {
            html: path.resolve(import.meta.dirname, './html.hbs'),
            css: path.resolve(import.meta.dirname, './css.hbs'),
            scss: path.resolve(import.meta.dirname, './scss.hbs'),
        },
        formatOptions: {
            svg: { ascent: 0 },
            json: { indent: 2 },
            ts: { types: ["constant", "literalId"] },
        },
        pathOptions: {
            ts: name === "icons" ? "src/icons.ts" : `src/icons/${name}.ts`,
        },
        ...options,
    };
}
