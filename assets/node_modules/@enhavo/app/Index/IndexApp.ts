import ActionManager from "@enhavo/app/Action/ActionManager";
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";
import View from "@enhavo/app/View/View";
import FlashMessenger from "@enhavo/app/FlashMessage/FlashMessenger";
import ModalManager from "@enhavo/app/Modal/ModalManager";
import Grid from "@enhavo/app/Grid/Grid";
import FormRegistry from "@enhavo/app/Form/FormRegistry";

export default class IndexApp
{
    private readonly eventDispatcher: EventDispatcher;
    private readonly view: View;
    private readonly actionManager: ActionManager;
    private readonly flashMessenger: FlashMessenger;
    private readonly modalManager: ModalManager;
    private readonly grid: Grid;
    private readonly formRegistry: FormRegistry;

    constructor(
        eventDispatcher: EventDispatcher,
        view: View,
        actionManager: ActionManager,
        flashMessenger: FlashMessenger,
        modalManager: ModalManager,
        grid: Grid,
        formRegistry: FormRegistry
    ) {
        this.eventDispatcher = eventDispatcher;
        this.view = view;
        this.actionManager = actionManager;
        this.flashMessenger = flashMessenger;
        this.modalManager = modalManager;
        this.grid = grid;
        this.formRegistry = formRegistry;
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
