import { createFilter } from '@rollup/pluginutils';
import Compiler from '@enhavo/dependency-injection/compiler/Compiler.js';
import ContainerBuilder from '@enhavo/dependency-injection/container/ContainerBuilder.js';
import Loader from '@enhavo/dependency-injection/loader/Loader.js';
import fs from 'fs';

const defaults = {
    transform: null,
    extensions: ['.di.yaml'],
    enableChunks: true,
    parameters: {},
    manualChunks: function (id, { getModuleInfo }) {
        return null
    }
};

export default async function (opts = {}) {
    const options = Object.assign({}, defaults, opts);
    const { extensions } = options;
    const filter = createFilter(options.include, options.exclude);

    let builder = new ContainerBuilder;
    let chunkMap = null;

    return {
        name: 'container-di',
        async transform(content, id) {
            if (!extensions.some((ext) => id.toLowerCase().endsWith(ext))) return null;
            if (!filter(id)) return null;

            // load and compile container
            if (!builder.isPrepared()) {
                builder.addParameters(options.parameters);
                let loader = new Loader();
                loader.loadFile(id, builder)

                for (let loadedFile of loader.loadedFiles) {
                    if (fs.existsSync(loadedFile) && fs.lstatSync(loadedFile).isFile()) {
                        this.addWatchFile(loadedFile);
                    }
                }
                await builder.prepare();
            }

            let compiler = new Compiler;
            let resultData = compiler.compile(builder);

            // generate chunk map
            if (chunkMap === null && options.enableChunks) {
                chunkMap = {};
                for (let definition of builder.getDefinitions()) {
                    if (definition.getChunkName()) {
                        let module = await this.resolve(definition.getFrom());
                        if (module) {
                            chunkMap[module.id] = definition.getChunkName();
                        }
                    }
                }
            }

            return {
                code: resultData,
                map: null,
            };
        },
        outputOptions(outputOptions) {
            if (!options.enableChunks) {
                return outputOptions;
            }

            if (import.meta.env !== undefined && import.meta.env.DEV) {
                return outputOptions;
            }

            outputOptions.manualChunks = (id, info) => {
                if (id.includes('container.di')) {
                    return 'container'
                }

                if (chunkMap[id]) {
                    return chunkMap[id];
                }

                if (typeof options.manualChunks === 'function') {
                    return options.manualChunks(id, info)
                }

                return null;
            };

            return outputOptions;
        },
    };
}
