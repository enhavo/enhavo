import {ModelFactory} from "@enhavo/app/factory/ModelFactory";
import {MenuItemInterface} from "./MenuItemInterface";

export class MenuItemFactory extends ModelFactory
{
    createWithData(name: string, data: object): MenuItemInterface
    {
        return <MenuItemInterface>super.createWithData(name, data)
    }

    createNew(name: string): MenuItemInterface
    {
        return <MenuItemInterface>super.createNew(name);
    }
}
