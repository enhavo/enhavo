const Loader = require('@enhavo/dependency-injection/Loader/Loader');
const builder = require('@enhavo/dependency-injection/builder');
const process = require('process');
const path = require('path');

class CommandLineInterface
{
    constructor() {
        this.servicePath = path.resolve(process.cwd(), process.argv[2]);
        this.command = process.argv[3];
        this.loader = new Loader;
    }

    execute() {
        this._list();
    }

    _list() {
        this.loader.loadFile(this.servicePath, builder);

        process.stdout.write('\u001b[42mLoaded files:\u001b[49m\n');
        for (let file of this.loader.getLoadedFiles()) {
            process.stdout.write(' - '+file+'\n');
        }

        process.stdout.write('\u001b[42mEntrypoints:\u001b[49m\n');
        for (let entrypoint of builder.getEntrypoints()) {
            process.stdout.write(' - '+ entrypoint.getName()+'\n');
        }

        process.stdout.write('\u001b[42mCompiler Passes:\u001b[49m\n');
        for (let compilerPass of builder.getCompilerPasses()) {
            process.stdout.write(' - '+ compilerPass.getName()+'\n');
        }

        process.stdout.write('\u001b[42mServices:\u001b[49m\n');
        for (let definitions of builder.getDefinitions()) {
            process.stdout.write(' - '+ definitions.getName()+'\n');
        }
    }
}

(new CommandLineInterface).execute();
