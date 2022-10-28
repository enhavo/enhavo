import {FormVisitorInterface} from "@enhavo/vue-form/form/FormVisitor";

export class Theme
{
    private visitors: Array<FormVisitorInterface> = [];

    addVisitor(visitor: FormVisitorInterface)
    {
        this.visitors.push(visitor);
    }

    merge(theme: Theme|Array<Theme>)
    {
        if (Array.isArray(theme)) {
            let mergedTheme = new Theme();
            for (let singleTheme of theme) {
                mergedTheme.merge(singleTheme);
            }
            theme = mergedTheme
        }

        for (let visitor of theme.visitors) {
            this.addVisitor(visitor);
        }
    }

    getVisitors(): Array<FormVisitorInterface>
    {
        return this.visitors;
    }
}
