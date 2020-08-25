const Loader = require('@enhavo/dependency-injection/Loader/Loader');
const CommandException = require('@enhavo/dependency-injection/Exception/CommandException');
const Compiler = require('@enhavo/dependency-injection/Compiler/Compiler');
const Validator = require('@enhavo/dependency-injection/Validation/Validator');
const builder = require('@enhavo/dependency-injection/builder');
const process = require('process');
const path = require('path');
const fs = require('fs');

class CommandLineInterface
{
    constructor() {
        this.servicePath = path.resolve(process.cwd(), process.argv[2]);
        this.command = process.argv[3];
        this.loader = new Loader;
    }

    execute() {
        if(this.command === undefined || this.command === 'list') {
            this._list();
        } else if(this.command === 'compile') {
            this._compile(process.argv[4]);
        } else {
            this._write('Command "'+this.command+'" not exist.');
        }
    }

    _list() {
        this.loader.loadFile(this.servicePath, builder);
        (new Validator).validate(builder);

        this._write('\u001b[42mLoaded files:\u001b[49m');
        for (let file of builder.getFiles()) {
            this._write(' - '+file);
        }

        this._write('\u001b[42mEntrypoints:\u001b[49m');
        for (let entrypoint of builder.getEntrypoints()) {
            this._write(' - '+ entrypoint.getName());
        }

        this._write('\u001b[42mCompiler Passes:\u001b[49m');
        for (let compilerPass of builder.getCompilerPasses()) {
            this._write(' - '+ compilerPass.getName());
        }

        this._write('\u001b[42mServices:\u001b[49m\n');
        for (let definitions of builder.getDefinitions()) {
            this._write(' - '+ definitions.getName());
        }
    }

    _compile(filePath) {
        this.loader.loadFile(this.servicePath, builder);
        builder.prepare();
        (new Validator).validate(builder);
        let content = (new Compiler).compile(builder);
        let file = path.resolve(process.cwd(), filePath);
        fs.writeFileSync(file, content);
    }

    _write(message) {
        process.stdout.write(message+'\n');
    }
}

(new CommandLineInterface).execute();
