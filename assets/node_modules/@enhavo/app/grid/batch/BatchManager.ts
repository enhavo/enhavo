import BatchInterface from "@enhavo/app/grid/batch/BatchInterface";
import BatchRegistry from "@enhavo/app/grid/batch/BatchRegistry";
import BatchDataInterface from "@enhavo/app/grid/batch/BatchDataInterface";
import View from "@enhavo/app/view/View";
import Translator from "@enhavo/core/Translator";
import ComponentRegistryInterface from "@enhavo/core/ComponentRegistryInterface";

export default class BatchManager
{
    public data: BatchDataInterface;

    private readonly registry: BatchRegistry;
    private readonly view: View;
    private readonly translator: Translator;
    private readonly componentRegistry: ComponentRegistryInterface;

    constructor(data: BatchDataInterface, registry: BatchRegistry, view: View, translator: Translator, componentRegistry: ComponentRegistryInterface)
    {
        this.registry = registry;
        this.view = view;
        this.translator = translator;
        this.data = data;
        this.componentRegistry = componentRegistry;
    }

    init() {
        for (let i in this.data.batches) {
            this.data.batches[i] = this.registry.getFactory(this.data.batches[i].component).createFromData(this.data.batches[i]);
        }

        for (let component of this.registry.getComponents()) {
            this.componentRegistry.registerComponent(component.name, component.component)
        }

        this.componentRegistry.registerStore('batchManager', this);
        this.data = this.componentRegistry.registerData(this.data);
    }

    async execute(ids: number[]): Promise<boolean>
    {
        let batch = this.getCurrentBatch();

        if(batch == null) {
            this.view.alert(this.translator.trans('enhavo_app.batch.message.no_batch_select'));
            return;
        }

        if(ids.length == 0) {
            this.view.alert(this.translator.trans('enhavo_app.batch.message.no_row_select'));
            return;
        }

        return await batch.execute(ids);
    }

    public getCurrentBatch(): BatchInterface
    {
        return this.getBatch(this.data.batch);
    }

    public changeBatch(value: string)
    {
        this.data.batch = value;
    }

    private getBatch(key: string): BatchInterface
    {
        for (let batch of this.data.batches) {
            if(batch.key == key) {
                return batch
            }
        }
        return null;
    }

    public hasBatches()
    {
        return this.data.batches.length > 0;
    }
}