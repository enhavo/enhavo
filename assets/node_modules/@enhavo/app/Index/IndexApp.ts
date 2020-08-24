import ActionManager from "@enhavo/app/Action/ActionManager";
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";
import View from "@enhavo/app/View/View";
import FlashMessenger from "@enhavo/app/FlashMessage/FlashMessenger";
import ModalManager from "@enhavo/app/Modal/ModalManager";
import Grid from "@enhavo/app/Grid/Grid";

export default class IndexApp
{
    private readonly eventDispatcher: EventDispatcher;
    private readonly view: View;
    private readonly actionManager: ActionManager;
    private readonly flashMessenger: FlashMessenger;
    private readonly modalManager: ModalManager;
    private readonly grid: Grid;

    constructor(
        eventDispatcher: EventDispatcher,
        view: View,
        actionManager: ActionManager,
        flashMessenger: FlashMessenger,
        modalManager: ModalManager,
        grid: Grid
    ) {
        this.eventDispatcher = eventDispatcher;
        this.view = view;
        this.actionManager = actionManager;
        this.flashMessenger = flashMessenger;
        this.modalManager = modalManager;
        this.grid = grid;
    }

    init()
    {
        this.view.init();
        this.actionManager.init();
        this.flashMessenger.init();
        this.modalManager.init();
        this.grid.init();

        this.grid.load();

        this.view.ready();
        this.view.addDefaultCloseListener();
    }
}
