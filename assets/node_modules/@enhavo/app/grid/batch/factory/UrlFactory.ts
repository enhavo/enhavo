import AbstractFactory from "@enhavo/app/grid/batch/factory/AbstractFactory";
import UrlBatch from "@enhavo/app/grid/batch/model/UrlBatch";
import Translator from "@enhavo/core/Translator";
import View from "@enhavo/app/view/View";
import FlashMessenger from "@enhavo/app/flash-message/FlashMessenger";
import Router from "@enhavo/core/Router";
import BatchDataInterface from "@enhavo/app/grid/batch/BatchDataInterface";

export default class UrlFactory extends AbstractFactory
{
    protected readonly batchData: BatchDataInterface;
    protected readonly translator: Translator;
    protected readonly view: View;
    protected readonly flashMessenger: FlashMessenger;
    protected readonly router: Router;

    constructor(batchData: BatchDataInterface, translator: Translator, view: View, flashMessenger: FlashMessenger, router: Router) {
        super();
        this.batchData = batchData;
        this.translator = translator;
        this.view = view;
        this.flashMessenger = flashMessenger;
        this.router = router;
    }

    createNew(): UrlBatch {
        return new UrlBatch(this.batchData, this.translator, this.view, this.flashMessenger, this.router);
    }
}
