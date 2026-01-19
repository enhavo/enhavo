import ContainerBuilder from "@enhavo/dependency-injection/container/ContainerBuilder"
import Definition from "@enhavo/dependency-injection/container/Definition";
import Compiler from "@enhavo/dependency-injection/compiler/Compiler"
import { expect, test } from 'vitest'

test('test definition function should return a correct AST', () => {
    let builder = new ContainerBuilder();
    let definition = new Definition('test');
    definition.setFrom('path');
    let compiler = new Compiler();
    builder.addDefinition(definition);

    let code = compiler.compile(builder);
});
