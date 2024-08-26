import ActionManager from "@enhavo/app/action/ActionManager";
import {FrameEventDispatcher} from "@enhavo/app/frame/FrameEventDispatcher";
import View from "@enhavo/app/view/View";
import FlashMessenger from "@enhavo/app/flash-message/FlashMessenger";
import ModalManager from "@enhavo/app/modal/ModalManager";
import Grid from "@enhavo/app/grid/Grid";
import FormRegistry from "@enhavo/app/form/FormRegistry";

export default class IndexApp
{
    private readonly eventDispatcher: FrameEventDispatcher;
    private readonly view: View;
    private readonly actionManager: ActionManager;
    private readonly flashMessenger: FlashMessenger;
    private readonly modalManager: ModalManager;
    private readonly grid: Grid;
    private readonly formRegistry: FormRegistry;

    constructor(
        eventDispatcher: FrameEventDispatcher,
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
