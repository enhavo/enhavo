import ViewFactoryInterface from "../ViewFactoryInterface";
import View from "../model/View";
import * as _ from 'lodash';

export default class ViewFactory implements ViewFactoryInterface
{
    createFromData(data: object): View
    {
        let view = this.createNew();
        return _.assign(view, data);
    }

    createNew(): View {
        let view = new View();
        view.storage = [];
        return view;
    }
}