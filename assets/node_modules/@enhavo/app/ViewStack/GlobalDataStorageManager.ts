import EventDispatcher from '@enhavo/app/ViewStack/EventDispatcher';
import DataStorageEntry from '@enhavo/app/ViewStack/DataStorageEntry';
import SaveGlobalDataEvent from '@enhavo/app/ViewStack/Event/SaveGlobalDataEvent';
import LoadGlobalDataEvent from '@enhavo/app/ViewStack/Event/LoadGlobalDataEvent';
import LoadRemoveDataEvent from '@enhavo/app/ViewStack/Event/LoadGlobalDataEvent';

export default class GlobalDataStorageManager
{
    private eventDispatcher: EventDispatcher;
    private entries: DataStorageEntry[];

    constructor(eventDispatcher: EventDispatcher, data: any)
    {
        this.eventDispatcher = eventDispatcher;
        this.entries = this.load(data);

        this.eventDispatcher.on('save-global-data', (event: SaveGlobalDataEvent) => {
            this.set(event.key, event.value);
            event.resolve();
        });

        this.eventDispatcher.on('load-global-data', (event: LoadGlobalDataEvent) => {
            let entry = this.get(event.key);
            event.resolve(entry);
        });

        this.eventDispatcher.on('remove-global-data', (event: LoadRemoveDataEvent) => {
            this.delete(event.key);
            event.resolve();
        });
    }

    public get(key: string): DataStorageEntry
    {
        for(let entry of this.entries) {
            if(entry.key == key) {
                return entry;
            }
        }
        return null;
    }

    public set(key: string, value: any): void
    {
        let entry = this.get(key);
        if(entry == null) {
            entry = new DataStorageEntry;
            entry.key = key;
            this.entries.push(entry);
        }
        entry.value = value;
    }

    public delete(key: string): boolean
    {
        let entry = this.get(key);
        if(entry != null) {
            let index = this.entries.indexOf(entry);
            if(index != -1) {
                this.entries.splice(index, 1);
                return true;
            }
        }
        return false;
    }

    private load(data: any): DataStorageEntry[]
    {
        return [];
    }

    public getStorage()
    {
        return this.entries;
    }
}