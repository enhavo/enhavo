import { IFrameView } from './IFrameView';

export class ViewFactory
{
    constructor() {

    }

    create(type: string, name: string, options: string): View
    {
        let view = new IFrameView(1, '');
        return view;
    }

    private createID()
    {
        return 'xxxx-xxxx'.replace(/[xy]/g, function(c) {
            let r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }
}