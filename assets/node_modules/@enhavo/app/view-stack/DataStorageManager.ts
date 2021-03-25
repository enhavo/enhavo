import EventDispatcher from '@enhavo/app/view-stack/EventDispatcher';
import DataStorageEntry from '@enhavo/app/view-stack/DataStorageEntry';
import SaveDataEvent from '@enhavo/app/view-stack/Event/SaveDataEvent';
import LoadDataEvent from '@enhavo/app/view-stack/Event/LoadDataEvent';
import RemoveDataEvent from '@enhavo/app/view-stack/Event/RemoveDataEvent';
import ViewStack from "@enhavo/app/view-stack/ViewStack";

export default class DataStorageManager
{
    private eventDispatcher: EventDispatcher;
    private viewStack: ViewStack;

    constructor(viewStack: ViewStack, eventDispatcher: EventDispatcher)
    {
        this.eventDispatcher = eventDispatcher;
        this.viewStack = viewStack;

        this.eventDispatcher.on('save-data', (event: SaveDataEvent) => {
            this.set(event.key, event.value, event.origin);
            event.resolve();
        });

        this.eventDispatcher.on('load-data', (event: LoadDataEvent) => {
            let entry = this.get(event.key, event.origin);
            event.resolve(entry);
        });

        this.eventDispatcher.on('remove-data', (event: RemoveDataEvent) => {
            this.delete(event.key, event.origin);
            event.resolve();
        });
    }

    private get(key: string, id: number): DataStorageEntry
    {
        let view = this.viewStack.get(id);
        if(view != null) {
            if(!view.storage) {
                return null
            }
            for(let entry of view.storage) {
                if(entry.key == key) {
                    return entry;
                }
            }
        }
        return null;
    }

    private set(key: string, value: any, id: number): void
    {
        let entry = this.get(key, id);
        if(entry == null) {
            entry = new DataStorageEntry;
            entry.key = key;
            let view =  this.viewStack.get(id);
            if(view != null) {
                if(!view.storage) {
                    view.storage = [];
                }
                view.storage.push(entry);
            }
        }
        entry.value = value;
    }

    private delete(key: string, id: number): boolean
    {
        let entry = this.get(key, id);
        if(entry != null) {
            let view =  this.viewStack.get(id);
            if(view != null) {
                let index = view.storage.indexOf(entry);
                view.storage.splice(index,1);
                return true;
            }
        }
        return false;
    }
}