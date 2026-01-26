import { createFilter } from '@rollup/pluginutils';
import Compiler from '@enhavo/dependency-injection/compiler/Compiler.js';
import ContainerBuilder from '@enhavo/dependency-injection/container/ContainerBuilder.js';
import Loader from '@enhavo/dependency-injection/loader/Loader.js';
import fs from 'fs';

const defaults = {
    transform: null,
    extensions: ['.di.yaml'],
    enableChunks: true,
    manualChunks: function (id, { getModuleInfo }) {
        return null
    }
};

export default function (opts = {}) {
    const options = Object.assign({}, defaults, opts);
    const { extensions } = options;
    const filter = createFilter(options.include, options.exclude);

    let builder = new ContainerBuilder;

    return {
        name: 'container-di',
        async transform(content, id) {
            if (!extensions.some((ext) => id.toLowerCase().endsWith(ext))) return null;
            if (!filter(id)) return null;


            if (!builder.isPrepared()) {
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

            let chunkMap = {};
            for (let definition of builder.getDefinitions()) {
                if (definition.getChunkName()) {
                    chunkMap[definition.getPath()] = definition.getChunkName();
                }
            }

            outputOptions.manualChunks = function(id, info) {
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
