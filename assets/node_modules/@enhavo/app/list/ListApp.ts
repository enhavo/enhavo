import ActionManager from "@enhavo/app/action/ActionManager";
import View from "@enhavo/app/view/View";
import List from "@enhavo/app/list/List";

export default class ListApp
{
    private readonly view: View;
    private readonly actionManager: ActionManager;
    private readonly list: List;

    constructor(view: View, actionManager: ActionManager, list: List)
    {
        this.view = view;
        this.actionManager = actionManager;
        this.list = list;
    }

    init() {
        this.view.init();
        this.actionManager.init();
        this.list.init();
        this.list.load();

        this.view.addDefaultCloseListener();
        this.view.ready();
    }
}
