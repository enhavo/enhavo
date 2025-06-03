import {AbstractAction} from "@enhavo/app/action/model/AbstractAction";
import {ResourcePreviewManager} from "@enhavo/app/manager/ResourcePreviewManager";

export class PreviewModeAction extends AbstractAction
{
    private frameClass: string;

    constructor(
        private readonly resourcePreviewManager: ResourcePreviewManager,
    ) {
        super();
    }

    async execute(): Promise<void>
    {
        this.resourcePreviewManager.frameClass = this.frameClass;
    }
}
