import { fontAssetTypes } from "@enhavo/app/vite/fantasticon-plugin/build-assets.js";

export const middleware = (builder, base) => {
    return async (req, res, next) => {
        if (!req.url) {
            return next();
        }

        let asset = builder.match(req.url, [...fontAssetTypes, "json"], base);

        if (!asset) {
            return next();
        }

        if (!(asset instanceof Buffer)) {
            asset = Buffer.from(asset);
        }

        res.writeHead(200, {
            "Content-Type": "application/octet-stream",
            "Content-Length": Buffer.byteLength(asset),
            "Access-Control-Allow-Origin": "*",
        });
        res.write(asset, "binary");
        res.end();
    };
};
