import LoaderInterface from "@enhavo/app/Form/LoaderInterface";
import FormRegistry from "@enhavo/app/Form/FormRegistry";

export default class RegistryPackage
{
    private loaders: LoaderInterface[] = [];
    protected registry: FormRegistry[];

    register(loader: LoaderInterface): RegistryPackage
    {
        this.loaders.push(loader);
        return this;
    }

    registerPackage(registryPackage: RegistryPackage): RegistryPackage
    {
        for(let loader of registryPackage.getLoaders()) {
            this.loaders.push(loader);
        }
        return this;
    }

    getLoaders(): LoaderInterface[]
    {
        return this.loaders;
    }
}
