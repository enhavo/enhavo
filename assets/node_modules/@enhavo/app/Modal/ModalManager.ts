import ModalInterface from "@enhavo/app/Modal/ModalInterface";
import ModalRegistry from "@enhavo/app/Modal/ModalRegistry";
import ComponentAwareInterface, { ComponentAwareType } from "@enhavo/core/ComponentAwareInterface";
import ComponentRegistryInterface from "@enhavo/core/ComponentRegistryInterface";

export default class ModalManager
{
    public modals: ModalInterface[];
    private readonly modalRegistry: ModalRegistry;
    private readonly componentRegistry: ComponentRegistryInterface;

    public constructor(modals: ModalInterface[], modalRegistry: ModalRegistry, componentRegistry: ComponentRegistryInterface)
    {
        this.modals = modals;
        this.modalRegistry = modalRegistry;
        this.componentRegistry = componentRegistry;
    }

    init() {
        this.componentRegistry.registerStore('modalManager', this);
        this.componentRegistry.registerData(this.modals);
    }

    public push(data: ComponentAwareInterface | ComponentAwareType)
    {
        let factory = this.modalRegistry.getFactory(data.component);
        let modal = factory.createFromData(data);
        modal.init();
        this.modals.push(modal);
    }

    public pop()
    {
        this.modals.pop();
    }
}
