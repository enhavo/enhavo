import FactoryInterface from "@enhavo/core/FactoryInterface";

export default class RegistryEntry
{
    private name: string;
    private component: object;
    private factory: FactoryInterface;

    constructor(name: string, component: object, factory: FactoryInterface) {
        this.name = name;
        this.component = component;
        this.factory = factory;
    }

    public getName(): string {
        return this.name;
    }

    public getComponent(): object {
        return this.component;
    }

    public getFactory(): FactoryInterface {
        return this.factory;
    }
}
