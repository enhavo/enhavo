import EventDispatcher from '@enhavo/app/ViewStack/EventDispatcher';
import DataStorageEntry from '@enhavo/app/ViewStack/DataStorageEntry';
import SaveDataEvent from '@enhavo/app/ViewStack/Event/SaveDataEvent';
import LoadDataEvent from '@enhavo/app/ViewStack/Event/LoadDataEvent';
import StateManager from "@enhavo/app/State/StateManager";

export default class DataStorage
{
    private eventDispatcher: EventDispatcher;
    private stateManager: StateManager;
    private entries: DataStorageEntry[];

    constructor(entries: DataStorageEntry[], eventDispatcher: EventDispatcher, stateManager: StateManager)
    {
        this.eventDispatcher = eventDispatcher;
        this.stateManager = stateManager;
        this.entries = entries;

        this.eventDispatcher.on('save-data', (event: SaveDataEvent) => {
            this.set(event.key, event.value)
        });

        this.eventDispatcher.on('load-data', (event: LoadDataEvent) => {
            let entry = this.get(event.key);
            event.resolve(entry.value);
        });

        this.eventDispatcher.on('remove-data', (event: LoadDataEvent) => {
            this.delete(event.key);
        });
        this.stateManager.setStorage(this.entries);
    }

    private get(key: string): DataStorageEntry
    {
        for(let entry of this.entries) {
            if(entry.key == key) {
                return entry;
            }
        }
        return null;
    }

    private set(key: string, value: any): void
    {
        let entry = this.get(key);
        if(entry == null) {
            entry = new DataStorageEntry;
            entry.key = key;
            this.entries.push(entry);
        }
        entry.value = value;
        this.stateManager.updateStorage(this.entries);
    }

    private delete(key: string): boolean
    {
        let entry = this.get(key);
        if(entry != null) {
            let index = this.entries.indexOf(entry);
            this.entries.splice(index,1);
            return true;
        }
        this.stateManager.updateStorage(this.entries);
        return false;
    }
}