import ModalInterface from "@enhavo/app/Modal/ModalInterface";
import ModalRegistry from "@enhavo/app/Modal/ModalRegistry";
import ComponentAwareInterface, { ComponentAwareType } from "@enhavo/core/ComponentAwareInterface";

export default class ModalManager
{
    private modals: ModalInterface[];

    private modalRegistry: ModalRegistry;

    public constructor(modals: ModalInterface[], modalRegistry: ModalRegistry)
    {
        this.modals = modals;
        this.modalRegistry = modalRegistry;
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