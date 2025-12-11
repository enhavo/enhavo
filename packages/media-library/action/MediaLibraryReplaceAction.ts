import {AbstractAction} from "@enhavo/app/action/model/AbstractAction";
import {MediaLibraryManager} from "../manager/MediaLibraryManager";
import {ResourceInputManager} from "@enhavo/app/manager/ResourceInputManager";
import {UiManager} from "@enhavo/app/ui/UiManager";

export class MediaLibraryReplaceAction extends AbstractAction
{
    public url: string;
    public updateUrl: string;
    public replaceElement: HTMLInputElement;

    constructor(
        private readonly mediaLibraryManager: MediaLibraryManager,
        private readonly resourceInputManager: ResourceInputManager,
        private readonly uiManager: UiManager,
    ) {
        super();
    }

    execute(): void
    {
        this.replaceElement.click();
    }

    async replaceFile(): Promise<void>
    {
        this.uiManager.loading(true);
        await this.mediaLibraryManager.replace(this.replaceElement.files[0], this.url);
        let response = await fetch(this.updateUrl);
        try {
            await this.resourceInputManager.handleResponse(response, true);
        } catch (e) {}
        this.uiManager.loading(false);
    }
}
