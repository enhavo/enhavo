import {AbstractAction} from "@enhavo/app/action/model/AbstractAction";

export class MediaLibraryReplaceAction extends AbstractAction
{
    public replaceElement: HTMLInputElement;

    constructor() {
        super();
    }

    execute(): void
    {
        this.replaceElement.click();
    }
}
