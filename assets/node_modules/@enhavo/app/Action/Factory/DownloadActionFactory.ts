import DownloadAction from "@enhavo/app/Action/Model/DownloadAction";
import AbstractFactory from "@enhavo/app/Action/Factory/AbstractFactory";

export default class DownloadActionFactory extends AbstractFactory
{
    createNew(): DownloadAction {
        return new DownloadAction(this.application);
    }
}