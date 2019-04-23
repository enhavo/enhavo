
export default interface RegistryInterface
{
    register(name: string, component: object, factory: object): void;
    getComponent(name: string): object;
    getFactory(name: string): object;
    has(name: string): boolean;
}