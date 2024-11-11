import {Form} from "@enhavo/vue-form/model/Form";
import {FileUrlResolverInterface} from "@enhavo/media/resolver/FileUrlResolverInterface";
import {File} from '@enhavo/media/model/File';
import {ActionManager} from '@enhavo/app/action/ActionManager';
import {ActionInterface} from "@enhavo/app/action/ActionInterface";
import {ActionMediaItemInterface} from "@enhavo/media/action/ActionMediaItemInterface";

export class MediaItemForm extends Form
{
    public file: File;
    public actions: ActionInterface[] = [];
    public formats: object;

    private _actions: ActionInterface[] = null;

    constructor(
        private fileResolver: FileUrlResolverInterface,
        private actionManager: ActionManager,
    ) {
        super();
    }

    path(format: string = null)
    {
        return this.fileResolver.resolve(this.file, format);
    }

    getActions()
    {
        if (this._actions !== null) {
            return this._actions
        }

        this._actions =  this.actionManager.createActions(this.actions);
        for (let action of this._actions) {
            (action as ActionMediaItemInterface).form = this;
        }
        return this._actions;
    }
}
