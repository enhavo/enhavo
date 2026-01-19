import { describe, expect, test } from "vitest";
import ContainerBuilder from "@enhavo/dependency-injection/container/ContainerBuilder"
import Definition from "@enhavo/dependency-injection/container/Definition"


test('test definition functions', () => {
    let builder = new ContainerBuilder();

    let definitionOne = new Definition('test');
    builder.addDefinition(definitionOne);
    expect(builder.getDefinition('test') === definitionOne).toBe(true);

    let definitionTwo = new Definition('something');
    builder.addDefinition(definitionTwo);
    expect(builder.getDefinition('something') === definitionTwo).toBe(true);

    let definitionThree = new Definition('test');
    builder.addDefinition(definitionThree);
    expect(builder.getDefinition('test') === definitionThree).toBe(true);
});

