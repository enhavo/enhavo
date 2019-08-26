import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import * as _ from 'lodash';
import MenuInterface from "@enhavo/app/Menu/MenuInterface";

export default abstract class AbstractFactory
{
    protected application: ApplicationInterface;

    constructor(application: ApplicationInterface)
    {
        this.application = application;
    }

    createFromData(data: object): MenuInterface
    {
        let menu = this.createNew();
        menu = _.extend(menu, data);
        return menu;
    }

    abstract createNew(): MenuInterface;
}