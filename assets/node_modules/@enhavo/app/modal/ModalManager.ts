import {ModalInterface} from "@enhavo/app/modal/ModalInterface";
import {ModalFactory} from "@enhavo/app/modal/ModalFactory";

export class ModalManager
{
    public modals: ModalInterface[] = [];

    public constructor(
        private modalFactory: ModalFactory
    )
    {
    }

    public push(data: object)
    {
        if (!data.hasOwnProperty('model')) {
            throw 'The modal data needs a "model" property!';
        }

        if (!data.hasOwnProperty('component')) {
            throw 'The modal data needs a "component" property!';
        }


        let model = data['model'];

        let modal = this.modalFactory.createWithData(model, data);
        modal.init();
        this.modals.push(modal);
    }

    public pop()
    {
        this.modals.pop();
    }
}
