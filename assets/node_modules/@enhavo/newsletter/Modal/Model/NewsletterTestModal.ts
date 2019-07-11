import AbstractModal from "@enhavo/app/Modal/Model/AbstractModal";

export default class IframeModal extends AbstractModal
{
    public email: string;

    send(): void
    {
        console.log(this.email);
    }
}