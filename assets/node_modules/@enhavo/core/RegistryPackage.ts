import RegistryPackageInterface, {RegistryPackageEntry} from "@enhavo/core/RegistryPackageInterface";
import RegistryInterface from "@enhavo/core/RegistryInterface";

export default class RegistryPackage implements RegistryPackageInterface
{
    private entries: RegistryPackageEntry[] = [];
    protected registry: RegistryInterface[];

    register(name: string, component: object, factory: object): RegistryPackageInterface
    {
        let entry = new RegistryPackageEntry();
        entry.name = name;
        entry.component = component;
        entry.factory = factory;
        this.entries.push(entry);
        return this;
    }

    registerPackage(registerPackage: RegistryPackageInterface): RegistryPackageInterface
    {
        for(let entry of registerPackage.getEntries()) {
            this.entries.push(entry);
        }
        return this;
    }

    getEntries(): RegistryPackageEntry[]
    {
        return this.entries;
    }
}
