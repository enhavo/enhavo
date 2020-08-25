import FormBatch from "@enhavo/app/Grid/Batch/Model/FormBatch";
import ModalManager from "@enhavo/app/Modal/ModalManager";
import Grid from "@enhavo/app/Grid/Grid";
import UrlFactory from "@enhavo/app/Grid/Batch/Factory/UrlFactory";
import Translator from "@enhavo/core/Translator";
import View from "@enhavo/app/View/View";
import FlashMessenger from "@enhavo/app/FlashMessage/FlashMessenger";
import Router from "@enhavo/core/Router";
import BatchDataInterface from "@enhavo/app/Grid/Batch/BatchDataInterface";

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
