
export default interface RegistryPackageInterface
{
    register(name: string, component: object, factory: object): RegistryPackageInterface;
    registerPackage(registryPackage: RegistryPackageInterface): RegistryPackageInterface;
    getEntries(): RegistryPackageEntry[]
}

export class RegistryPackageEntry
{
    component: object;
    factory: object;
    name: string;
}