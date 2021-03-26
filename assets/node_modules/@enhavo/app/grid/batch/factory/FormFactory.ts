import FormBatch from "@enhavo/app/grid/batch/model/FormBatch";
import ModalManager from "@enhavo/app/modal/ModalManager";
import Grid from "@enhavo/app/grid/Grid";
import UrlFactory from "@enhavo/app/grid/batch/factory/UrlFactory";
import Translator from "@enhavo/core/Translator";
import View from "@enhavo/app/view/View";
import FlashMessenger from "@enhavo/app/flash-message/FlashMessenger";
import Router from "@enhavo/core/Router";
import BatchDataInterface from "@enhavo/app/grid/batch/BatchDataInterface";

export default class FormFactory extends UrlFactory
{
    protected readonly modalManager: ModalManager;
    protected readonly grid: Grid;

    constructor(
        batchData: BatchDataInterface,
        translator: Translator,
        view: View,
        flashMessenger: FlashMessenger,
        router: Router,
        modalManager: ModalManager,
        grid: Grid
    ) {
        super(batchData, translator, view, flashMessenger, router);
        this.modalManager = modalManager;
        this.grid = grid;
    }

    createNew(): FormBatch {
        return new FormBatch(this.batchData, this.translator, this.view, this.flashMessenger, this.router, this.modalManager, this.grid);
    }
}
