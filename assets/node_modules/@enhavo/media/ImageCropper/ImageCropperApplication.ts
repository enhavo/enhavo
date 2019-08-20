import ImageCropperApp from "@enhavo/media/ImageCropper/ImageCropperApp";

import Application from "@enhavo/app/Application";
import AppInterface from "@enhavo/app/AppInterface";
import ActionAwareApplication from "@enhavo/app/Action/ActionAwareApplication";

export class ImageCropperApplication extends Application implements ActionAwareApplication
{
    public getApp(): AppInterface
    {
        if(this.app == null) {
            this.app = new ImageCropperApp(this.getDataLoader(), this.getEventDispatcher(), this.getView(), this.getActionManager(), this.getFlashMessenger());
        }
        return this.app;
    }
}

let application = new ImageCropperApplication();
export default application;