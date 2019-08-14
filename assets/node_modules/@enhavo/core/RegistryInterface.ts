import RegistryPackageInterface from "@enhavo/core/RegistryPackageInterface";

export default interface RegistryInterface
{
    register(name: string, component: object, factory: object): RegistryInterface;
    registerPackage(registerPackage: RegistryPackageInterface): RegistryInterface;
    getComponent(name: string): object;
    getFactory(name: string): object;
    has(name: string): boolean;
}