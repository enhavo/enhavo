import { assetBuilder } from "@enhavo/app/vite/fantasticon-plugin/build-assets.js";
import { defaults } from "@enhavo/app/vite/fantasticon-plugin/defaults.js";
import { hmrLinkTag, hmrTemplate } from "@enhavo/app/vite/fantasticon-plugin/hmr-template.js";
import { middleware } from "@enhavo/app/vite/fantasticon-plugin/middleware.js";

export async function fantasticon(options) {
    const {
        generateFonts,
        injectHtml = true,
        ...config
    } = defaults(options ?? {});
    const builder = await assetBuilder(config, generateFonts);
    const name = `fantasticon:${config.name}`;
    const virtual = `\0${name}`;
    const updateEvent = `${name}:update`;
    let base = "/";

    let ws;

    function transformIndexHtml(html) {
        return { html, tags: [hmrLinkTag(config, base, name)] };
    }

    return {
        name,
        configResolved(config) {
            base = config.env.DEV ? config.server.origin + config.base : config.base;
        },
        async buildStart() {
            builder.watch(() => ws, updateEvent);
            await builder.build();
        },
        buildEnd() {
            builder.end();
        },
        async writeBundle() {
            await builder.build(true);
        },
        async handleHotUpdate(ctx) {
            ws = ctx.server.ws;
        },
        transformIndexHtml: injectHtml ? transformIndexHtml : undefined,
        resolveId(source) {
            if (source === name) {
                return virtual;
            }
        },
        load(source) {
            if (source === virtual) {
                return hmrTemplate(updateEvent, name, config, base);
            }
            return builder.match(source);
        },
        configureServer(server) {
            server.middlewares.use(middleware(builder, base));
        },
    };
}

