import EventDispatcher from '@enhavo/app/ViewStack/EventDispatcher';
import DataStorageEntry from '@enhavo/app/ViewStack/DataStorageEntry';
import SaveDataEvent from '@enhavo/app/ViewStack/Event/SaveDataEvent';
import LoadDataEvent from '@enhavo/app/ViewStack/Event/LoadDataEvent';

export default class DataStorage
{
    private eventDispatcher: EventDispatcher;

    private entries: DataStorageEntry[] = [];

    constructor(eventDispatcher: EventDispatcher)
    {
        this.eventDispatcher = eventDispatcher;

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
    }

    private delete(key: string): boolean
    {
        let entry = this.get(key);
        if(entry != null) {
            let index = this.entries.indexOf(entry);
            this.entries.splice(index,1);
            return true;
        }
        return false;
    }
}