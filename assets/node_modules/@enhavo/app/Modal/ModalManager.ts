import ModalInterface from "@enhavo/app/Modal/ModalInterface";
import ModalRegistry from "@enhavo/app/Modal/ModalRegistry";

export default class ModalManager
{
    private modals: ModalInterface[];

    private modalRegistry: ModalRegistry;

    public constructor(modals: ModalInterface[], modalRegistry: ModalRegistry)
    {
        this.modals = modals;
        this.modalRegistry = modalRegistry;
    }

    public push(type: string, data?: any)
    {
        let factory = this.modalRegistry.getFactory(type);
        let modal = factory.createNew();
        modal.open(data);
        this.modals.push(modal);
    }

    public pop()
    {
        this.modals.pop();
    }
}