import BatchInterface from "@enhavo/app/Grid/Batch/BatchInterface";
import BatchRegistry from "@enhavo/app/Grid/Batch/BatchRegistry";
import BatchDataInterface from "@enhavo/app/Grid/Batch/BatchDataInterface";
import View from "@enhavo/app/View/View";
import Translator from "@enhavo/core/Translator";

export default class BatchManager
{
    private registry: BatchRegistry;
    private data: BatchDataInterface;
    private view: View;
    private translator: Translator;

    constructor(data: BatchDataInterface, registry: BatchRegistry, view: View, translator: Translator)
    {
        this.registry = registry;
        this.view = view;
        this.translator = translator;
        this.data = data;
        for (let i in data.batches) {
            data.batches[i] = registry.getFactory(data.batches[i].component).createFromData(data.batches[i]);;
        }
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

    private getBatch(key: string): BatchInterface
    {
        for (let batch of this.data.batches) {
            if(batch.key == key) {
                return batch
            }
        }
        return null;
    }
}