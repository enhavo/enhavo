#!/usr/bin/env node
const Loader = require('@enhavo/dependency-injection/loader/Loader');
const Compiler = require('@enhavo/dependency-injection/compiler/Compiler');
const Validator = require('@enhavo/dependency-injection/validation/Validator');
const ContainerBuilder = require('@enhavo/dependency-injection/container/ContainerBuilder');
const process = require('process');
const path = require('path');
const fs = require('fs');
const {Command} = require('commander');

class CommandLineInterface
{
    constructor()
    {
        this.program = new Command();

        this.program
            .name('di')
            .description('CLI to analyse a dependency injection container')

        this.program.command('inspect')
            .description('Display all services')
            .argument('<string>', 'path to dependency injection container')
            .option('--name <string>', 'service which contain name')
            .option('--tag <string>', 'list only service with tag')
            .action((path, options) => {
                this._inspect(path, options.name, options.tag);
            });

        this.program.command('compile')
            .description('Write compiled container to target file')
            .argument('<string>', 'path to dependency injection container')
            .argument('<string>', 'target file')
            .action((path, target, options) => {
                this._compile(path, target);
            });
    }

    parse()
    {
        this.program.parse();
    }

    _inspect(containerPath, name, tag)
    {
        let builder = new ContainerBuilder;

        const loader = new Loader;

        loader.loadFile(containerPath, builder);
        builder.prepare();
        (new Validator).validate(builder);

        if (!name && !tag) {
            this._write('\u001b[42mLoaded files:\u001b[49m');
            for (let file of builder.getFiles()) {
                this._write(' - '+file);
            }
            this._write('');
        }

        if (!name && !tag) {
            this._write('\u001b[42mCompiler Passes:\u001b[49m');
            for (let compilerPass of builder.getCompilerPasses()) {
                this._write(' - '+ compilerPass.getName());
            }
            this._write('');
        }

        this._write('\u001b[42mServices:\u001b[49m');
        for (let definition of builder.getDefinitions()) {
            let show = true;
            if (name && !definition.getName().includes(name)) {
                show = false;
            }

            if (tag && !definition.getTag(tag)) {
                show = false;
            }

            if (show) {
                this._write(' - '+ definition.getName());
            }
        }
        this._write('');
    }

    _compile(containerPath, filePath)
    {
        const loader = new Loader;

        let builder = new ContainerBuilder;
        loader.loadFile(containerPath, builder);
        builder.prepare();
        (new Validator).validate(builder);
        let content = (new Compiler).compile(builder);
        let file = path.resolve(process.cwd(), filePath);
        fs.writeFileSync(file, content);

        this._write('\u001b[42mFile created!\u001b[49m\n');
    }

    _write(message) {
        process.stdout.write(message+'\n');
    }
}

(new CommandLineInterface).parse();
