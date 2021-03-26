import ViewFactory from "./ViewFactory";
import IframeView from "../model/IframeView";

export default class IframeViewFactory extends ViewFactory
{
    createFromData(data: object): IframeView
    {
        let view = <IframeView>super.createFromData(data);
        let object = <IframeView>data;
        view.url = object.url;
        return view;
    }

    createNew(): IframeView {
        let view = new IframeView();
        view.storage = [];
        return view;
    }
}