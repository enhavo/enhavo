import RegistryEntry from "@enhavo/core/RegistryEntry";

export default interface RegistryInterface
{
    register(entry: RegistryEntry): RegistryInterface;
    getComponent(name: string): object;
    getFactory(name: string): object;
    has(name: string): boolean;
}
