import MenuData from "@enhavo/app/menu/MenuData";
import MenuRegistry from "@enhavo/app/menu/MenuRegistry";
import * as _ from "lodash";
import MenuInterface from "@enhavo/app/menu/MenuInterface";
import GlobalDataStorageManager from "@enhavo/app/view-stack/GlobalDataStorageManager";
import ComponentRegistryInterface from "@enhavo/core/ComponentRegistryInterface";

export default class MenuManager
{
    public data: MenuData;

    private registry: MenuRegistry;
    private dataStorage: GlobalDataStorageManager;
    private componentRegistry: ComponentRegistryInterface;

    constructor(data: MenuData, registry: MenuRegistry, dataStorage: GlobalDataStorageManager, componentRegistry: ComponentRegistryInterface)
    {
        _.extend(data, new MenuData);
        this.data = data;
        this.registry = registry;
        this.dataStorage = dataStorage;
        this.componentRegistry = componentRegistry;
    }

    init(): void {
        for (let i in this.data.items) {
            let item = this.registry.getFactory(this.data.items[i].component).createFromData(this.data.items[i]);
            this.data.items[i] = item;
        }

        for (let component of this.registry.getComponents()) {
            this.componentRegistry.registerComponent(component.name, component.component)
        }

        this.componentRegistry.registerStore('menuManager', this);
        this.data = this.componentRegistry.registerData(this.data);

        let menuActiveKey = this.dataStorage.get('menu-active-key');
        if(menuActiveKey !== null) {
            for(let item of this.getItems()) {
                if(item.key == menuActiveKey.value) {
                    item.select();
                }
            }
        }
    }

    getData(): MenuData {
        return this.data;
    }

    isOpen(): boolean
    {
        return this.data.open;
    }

    toogleMenu(): void {
        this.data.open = !this.data.open;
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