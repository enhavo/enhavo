import MenuData from "@enhavo/app/Menu/MenuData";
import MenuRegistry from "@enhavo/app/Menu/MenuRegistry";
import * as _ from "lodash";

export default class MenuManager
{
    private data: MenuData;
    private registry: MenuRegistry;

    constructor(data: MenuData, registry: MenuRegistry)
    {
        _.extend(data, new MenuData);
        this.data = data;
        this.registry = registry;

        for (let i in this.data.items) {
            let item = registry.getFactory(this.data.items[i].component).createFromData(this.data.items[i]);
            _.extend(this.data.items[i], item);
        }
    }

    isOpen(): boolean
    {
        return this.data.open;
    }

    open() {
        this.data.open = true;
    }

    close() {
        this.data.open = false;
    }

    clearSelections() {
        for(let item of this.data.items) {
            item.unselect();
        }
    }

    start() {
        if(this.data.items.length > 0) {
            this.clearSelections();
            this.data.items[0].select();
            this.data.items[0].open();
        }
    }
}