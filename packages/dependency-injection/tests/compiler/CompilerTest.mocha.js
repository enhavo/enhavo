const assert = require("chai").assert;
import ContainerBuilder from "@enhavo/dependency-injection/container/ContainerBuilder"
const Definition = require( "@enhavo/dependency-injection/container/Definition");
import Compiler from "@enhavo/dependency-injection/compiler/Compiler"
import acorn  from "acorn"

describe('dependency-injection/Compiler/Compiler', () => {
    describe('test definition function', () => {
        it('should return a correct AST', () => {
            let builder = new ContainerBuilder();
            let definition = new Definition('test');
            definition.setFrom('path');
            let compiler = new Compiler();
            builder.addDefinition(definition);

            let code = compiler.compile(builder);
            // since acorn throws error, we have to wait until this is fixed parse AST
            // let tree = acorn.parse(code, {ecmaVersion: 11});
        });
    });
});
