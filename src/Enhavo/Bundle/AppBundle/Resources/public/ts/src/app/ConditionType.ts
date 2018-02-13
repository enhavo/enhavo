import * as $ from 'jquery'

export interface ConditionTypeConfig
{
    id: string;
}

export class ConditionType
{
    public static subjects: ConditionType[] = [];

    private $element: JQuery;

    private config: ConditionTypeConfig;

    private observers: ConditionObserver[] = [];

    private $input: JQuery;

    constructor(element: HTMLElement)
    {
        ConditionType.subjects.push(this);
        this.$element = $(element);
        this.config = this.$element.data('condition-type');

        let self = this;

        if(this.$element.prop('tagName').toLowerCase() == 'input') {
            this.$input = this.$element;
            this.$input.on('change', function(event) {
                self.notify();
            });
        } else {
            this.$input = this.$element.find('input');
        }

        this.$input.on('change ifChecked', function(event) {
            self.notify();
        });
    }

    public register(observer: ConditionObserver)
    {
        this.observers.push(observer);
        observer.wakeUp(this);
    }

    public notify()
    {
        for (let observer of this.observers) {
            observer.wakeUp(this);
        }
    }

    public static apply(element:HTMLElement)
    {
        $(element).find('[data-condition-type]').each(function(index, element:HTMLElement) {
            new ConditionType(element);
        });

        $(element).find('[data-condition-type-observer]').each(function(index, element:HTMLElement) {
            new ConditionObserver(element);
        });
    }

    public static init(): void
    {
        $(document).on('formOpenAfter', function (event, element:HTMLElement) {
            ConditionType.apply(element);
        });

        $(document).on('gridAddAfter', function (event, element:HTMLElement) {
            ConditionType.apply(element);
        });

        $(document).on('formListAddItem', function (event, element:HTMLElement) {
            ConditionType.apply(element);
        });
    }

    public getId(): string
    {
        return this.config.id;
    }

    public getValue(): string
    {
        if(this.$input.length > 1) {
            let value = null;
            this.$input.each(function (index, element) {
                if($(element).is(':checked')) {
                    value = $(element).val();
                }
            });
            return value;
        }

        return this.$input.val();
    }
}

export interface ConditionObserverConfig
{
    id: string;
    values: string[];
}


export class ConditionObserver
{
    private $element: JQuery;

    private config: ConditionObserverConfig;

    private $row: JQuery;

    constructor(element: HTMLElement)
    {
        this.$element = $(element);
        this.config = this.$element.data('condition-type-observer');
        let parent = this.$element.parents('[data-form-row]').get(0);
        this.$row = $(parent);

        for (let subject of ConditionType.subjects) {
            if(subject.getId() == this.config.id) {
                subject.register(this);
            }
        }
    }

    public wakeUp(subject: ConditionType)
    {
        if(this.config.values.indexOf(subject.getValue()) >= 0) {
            this.$row.show();
        } else {
            this.$row.hide();
        }
    }


}

ConditionType.init();