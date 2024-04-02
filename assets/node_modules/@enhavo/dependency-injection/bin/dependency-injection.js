#!/usr/bin/env node
import Loader from '@enhavo/dependency-injection/loader/Loader.js';
import Compiler from '@enhavo/dependency-injection/compiler/Compiler.js';
import Validator from '@enhavo/dependency-injection/validation/Validator.js';
import ContainerBuilder from '@enhavo/dependency-injection/container/ContainerBuilder.js';
import process from 'process';
import path from 'path';
import fs from 'fs';
import {Command} from 'commander';

class CommandLineInterface
{
    constructor()
    {
        this.program = new Command();
        this.containerPath = null;

        this.program
            .name('di')
            .description('CLI to analyse a dependency injection container')


        this.program
            .option('--file <string>', 'path to dependency injection container', null)
            .on("option:file", (value) => {
                this.containerPath = value;
            })


        this.program.command('inspect')
            .description('Display all services')
            .option('--name <string>', 'service which contain name')
            .option('--tag <string>', 'list only service with tag')
            .action(async (options) => {
                await this._inspect(this.containerPath, options.name, options.tag);
            });

        this.program.command('compile')
            .description('Write compiled container to target file')
            .argument('<string>', 'target file')
            .action(async (target, options) => {
                await this._compile(this.containerPath, target);
            });
    }

    parse()
    {
        this.program.parse();
    }

    async _inspect(containerPath, name, tag)
    {
        if (!containerPath) {
            throw '--file options must be set for inspect'
        }

        let builder = new ContainerBuilder;

        const loader = new Loader;

        loader.loadFile(containerPath, builder);
        await builder.prepare();
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

    async _compile(containerPath, filePath)
    {
        if (!containerPath) {
            throw '--file options must be set for inspect'
        }

        const loader = new Loader;

        let builder = new ContainerBuilder;
        loader.loadFile(containerPath, builder);
        await builder.prepare();
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
