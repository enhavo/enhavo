#!/usr/bin/env node
const Loader = require('@enhavo/dependency-injection/loader/Loader');
const Compiler = require('@enhavo/dependency-injection/compiler/Compiler');
const Validator = require('@enhavo/dependency-injection/validation/Validator');
const ContainerBuilder = require('@enhavo/dependency-injection/container/ContainerBuilder');
const process = require('process');
const path = require('path');
const fs = require('fs');

class CommandLineInterface
{
    constructor() {
        this.command = process.argv[3];
        this.loader = new Loader;
    }

    execute() {
        if(this.command === 'list') {
            this._list(process.argv[4]);
        } else if(this.command === 'compile') {
            this._compile(process.argv[4], process.argv[5]);
        } else {
            this._write('Command "'+this.command+'" not exist. Try list or compile');
        }
    }

    _list(containerPath) {
        let builder = new ContainerBuilder;

        this.loader.loadFile(containerPath, builder);
        (new Validator).validate(builder);

        this._write('\u001b[42mLoaded files:\u001b[49m');
        for (let file of builder.getFiles()) {
            this._write(' - '+file);
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

    _compile(containerPath, filePath) {
        let builder = new ContainerBuilder;
        this.loader.loadFile(containerPath, builder);
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
