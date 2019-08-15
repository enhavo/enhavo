import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import * as _ from 'lodash';
import ModalInterface from "@enhavo/app/Modal/ModalInterface";

export default abstract class AbstractFactory
{
    protected application: ApplicationInterface;

    constructor(application: ApplicationInterface)
    {
        this.application = application;
    }

    createFromData(data: object): ModalInterface
    {
        let modal = this.createNew();
        modal = _.extend(_.assign({}, data), modal);
        return modal;
    }

    abstract createNew(): ModalInterface;
}