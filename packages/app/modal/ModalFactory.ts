import {ModelFactory} from "@enhavo/app/factory/ModelFactory";
import {ModalInterface} from "@enhavo/app/modal/ModalInterface";

export class ModalFactory extends ModelFactory
{
    createWithData(name: string, data: object): ModalInterface
    {
        return <ModalInterface>super.createWithData(name, data)
    }

    createNew(name: string): ModalInterface
    {
        return <ModalInterface>super.createNew(name);
    }
}