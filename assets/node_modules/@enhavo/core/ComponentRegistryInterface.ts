
export default interface ComponentRegistryInterface
{
    registerStore(name: string, store: object): ComponentRegistryInterface;
    registerDirective(name: string, store: object): ComponentRegistryInterface;
    registerComponent(name: string, component: object): ComponentRegistryInterface;
    registerPlugin(plugin: object): ComponentRegistryInterface;
    registerData<Type>(data: Type): Type
}
