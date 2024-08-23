

export class ModelRegisterFactory
{
    protected entries: Entry[] = []

    registerModel(name: string, model: object): void
    {
        if (model === null || model === undefined) {
            throw 'No valid model for "'+name+'"!';
        }

        if (this.has(name)) {
            this.deleteEntry(name);
        }
        this.entries.push(new Entry(name, model));
    }

    protected deleteEntry(name: string): void
    {
        for (let i in this.entries) {
            if(this.entries[i].getName() == name) {
                this.entries.splice(parseInt(i), 1);
                break;
            }
        }
    }

    protected has(name: string): boolean
    {
        for (let entry of this.entries) {
            if(entry.getName() == name) {
                return true;
            }
        }
        return false;
    }

    protected get(name: string): Entry|null
    {
        for (let entry of this.entries) {
            if(entry.getName() == name) {
                return entry;
            }
        }
        return null;
    }
}

export class ModelFactory extends ModelRegisterFactory
{
    createWithData(name: string, data: object)
    {
        let model = this.doCreate(name);
        Object.assign(model, data);
        if (typeof model.onInit === 'function') {
            model.onInit();
        }
        return model;
    }

    createNew(name: string)
    {
        let model = this.doCreate(name);
        if (typeof model.onInit === 'function') {
            model.onInit();
        }
        return model;
    }

    private doCreate(name: string)
    {
        const entry = this.get(name);
        if (entry === null) {
            throw 'Model "'+name+'" does not exist!';
        }
        let originModel = entry.getModel();
        return Object.create(originModel);
    }
}

export class Entry
{
    constructor(
        private readonly name: string,
        private readonly model: object
    )
    {
    }

    public getName(): string {
        return this.name;
    }

    public getModel(): object {
        return this.model;
    }
}