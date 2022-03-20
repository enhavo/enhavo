import ModalInterface from "@enhavo/app/modal/ModalInterface";
import ModalRegistry from "@enhavo/app/modal/ModalRegistry";
import ComponentAwareInterface, { ComponentAwareType } from "@enhavo/core/ComponentAwareInterface";
import ComponentRegistryInterface from "@enhavo/core/ComponentRegistryInterface";

export default class ModalManager
{
    public modals: ModalInterface[];
    private readonly registry: ModalRegistry;
    private readonly componentRegistry: ComponentRegistryInterface;

    public constructor(modals: ModalInterface[], modalRegistry: ModalRegistry, componentRegistry: ComponentRegistryInterface)
    {
        this.modals = modals;
        this.registry = modalRegistry;
        this.componentRegistry = componentRegistry;
    }

    init() {
        for (let component of this.registry.getComponents()) {
            this.componentRegistry.registerComponent(component.name, component.component)
        }

        this.componentRegistry.registerStore('modalManager', this);
        this.modals = this.componentRegistry.registerData(this.modals);
    }

    public push(data: ComponentAwareInterface | ComponentAwareType)
    {
        let factory = this.registry.getFactory(data.component);
        let modal = factory.createFromData(data);
        modal.init();
        this.modals.push(modal);
    }

    public pop()
    {
        this.modals.pop();
    }
}
