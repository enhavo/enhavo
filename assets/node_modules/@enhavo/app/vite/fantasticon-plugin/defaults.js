import { fontAssetTypes } from "@enhavo/app/vite/fantasticon-plugin/build-assets.js";

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
        descent: 33,
        normalize: true,
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
