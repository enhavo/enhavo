import {assert} from "chai";
import ContainerBuilder from "@enhavo/dependency-injection/Container/ContainerBuilder";
import Definition from "@enhavo/dependency-injection/Container/Definition";
import Compiler from "@enhavo/dependency-injection/Compiler/Compiler";
import * as acorn from "acorn";

describe('dependency-injection/Compiler/Compiler', () => {
    describe('test definition function', () => {
        it('should return a correct AST', () => {
            let builder = new ContainerBuilder();
            let definition = new Definition('test');
            definition.setFrom('path');
            let compiler = new Compiler();
            builder.addDefinition(definition);

            let code = compiler.compile(builder);

            // since acorn is not supporting dynamic import statement, we have to wait until this is fixed parse AST
            // let tree = acorn.parse(code);
        });
    });
});

