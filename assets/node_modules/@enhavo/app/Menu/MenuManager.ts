import MenuData from "@enhavo/app/Menu/MenuData";
import MenuRegistry from "@enhavo/app/Menu/MenuRegistry";
import * as _ from "lodash";
import MenuInterface from "@enhavo/app/Menu/MenuInterface";
import GlobalDataStorageManager from "@enhavo/app/ViewStack/GlobalDataStorageManager";

export default class MenuManager
{
    private data: MenuData;
    private registry: MenuRegistry;
    private dataStorage: GlobalDataStorageManager;

    constructor(data: MenuData, registry: MenuRegistry, dataStorage: GlobalDataStorageManager)
    {
        _.extend(data, new MenuData);
        this.data = data;
        this.registry = registry;
        this.dataStorage = dataStorage;

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

    isCustomChange()
    {
        return this.data.customChange;
    }

    clearSelections() {
        for(let item of this.data.items) {
            item.unselect();
        }
    }

    start() {
        if(this.data.items.length > 0) {
            this.clearSelections();
            for(let item of this.data.items) {
                if(item.clickable) {
                    item.select();
                    item.open();
                    return;
                }
            }
        }
    }

    init() {
        let menuActiveKey = this.dataStorage.get('menu-active-key');
        if(menuActiveKey !== null) {
            for(let item of this.getItems()) {
                if(item.key == menuActiveKey.value) {
                    item.select();
                }
            }
        }
    }

    setActive(key: string) {
        this.dataStorage.set('menu-active-key', key);
    }

    getItems(): Array<MenuInterface>
    {
        let items = [];
        for(let item of this.data.items) {
            items.push(item);
            for(let descendant of item.getDescendants()) {
                items.push(descendant);
            }
        }
        return items;
    }
}