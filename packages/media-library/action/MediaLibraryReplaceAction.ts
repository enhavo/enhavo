import {AbstractAction} from "@enhavo/app/action/model/AbstractAction";
import {MediaLibraryManager} from "../manager/MediaLibraryManager";
import {ResourceInputManager} from "@enhavo/app/manager/ResourceInputManager";
import {UiManager} from "@enhavo/app/ui/UiManager";
import {FlashMessenger} from "@enhavo/app/flash-message/FlashMessenger";

export class MediaLibraryReplaceAction extends AbstractAction
{
    public url: string;
    public updateUrl: string;
    public replaceElement: HTMLInputElement;

    constructor(
        private readonly mediaLibraryManager: MediaLibraryManager,
        private readonly resourceInputManager: ResourceInputManager,
        private readonly uiManager: UiManager,
        private readonly flashMessenger: FlashMessenger,
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
        try {
            await this.mediaLibraryManager.replace(this.replaceElement.files[0], this.url);
            let response = await fetch(this.updateUrl);
            await this.resourceInputManager.handleResponse(response, true);

        } catch (error) {
            this.flashMessenger.error('An error occurred')
            console.error(error);
        }
        this.uiManager.loading(false);
    }
}
