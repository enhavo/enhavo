import ViewFactory from "./ViewFactory";
import AjaxView from "../Model/AjaxView";

export default class AjaxViewFactory extends ViewFactory
{
    createFromData(data: object): AjaxView
    {
        let view = <AjaxView>super.createFromData(data);
        let object = <AjaxView>data;
        view.url = object.url;
        return view;
    }

    createNew(): AjaxView {
        return new AjaxView()
    }
}