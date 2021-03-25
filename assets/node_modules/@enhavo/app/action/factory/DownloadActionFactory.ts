import DownloadAction from "@enhavo/app/action/model/DownloadAction";
import AbstractFactory from "@enhavo/app/action/factory/AbstractFactory";

export default class DownloadActionFactory extends AbstractFactory
{
    createNew(): DownloadAction {
        return new DownloadAction();
    }
}
