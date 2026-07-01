import {ColumnInterface} from "@enhavo/app/column/ColumnInterface";

export class AbstractColumn implements ColumnInterface
{
    public static readonly SORTING_DIRECTION_ASC = 'asc';
    public static readonly SORTING_DIRECTION_DESC = 'desc';

    public sortable: boolean;
    public sortingDirection: string = null;
    public key: string;
    public component: string;
    public model: string;
    public visibleCondition: string;
    public visible: boolean = true;
    public width: number;
    public label: string;

    public readonly mobileMaxWidth: number = 360;
    public readonly tabletMaxWidth: number = 768;

    private visibleValue: boolean = null;

    isVisible(): boolean
    {
        if (this.visibleValue === null) {
            this.checkVisibility()
        }
        return this.visibleValue;
    }

    checkVisibility(): void
    {
        if (typeof this.visibleCondition === 'string') {

            let context = {
                mobile: window.innerWidth <= this.mobileMaxWidth,
                tablet: window.innerWidth > this.mobileMaxWidth && window.innerWidth <= this.tabletMaxWidth,
                desktop: window.innerWidth > this.tabletMaxWidth,
                width: window.innerWidth,
                column: this
            };

            this.visibleValue = this.evaluate(this.visibleCondition, context) && (this.visible !== false);
        } else {
            this.visibleValue = this.visible !== false;
        }
    }

    private evaluate(code: string, args: Record<string, any> = {})
    {
        const keys = Object.keys(args);
        const values = keys.map(k => args[k]);
        return new Function(...keys, `return ${code};`)(...values);
    }
}
