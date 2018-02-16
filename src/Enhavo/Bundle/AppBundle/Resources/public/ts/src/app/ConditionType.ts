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
        } else if(this.$element.prop('tagName').toLowerCase() == 'select') {
            this.$input = this.$element;
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
    operator: string;
}

export class ConditionObserver
{
    private $element: JQuery;

    private configs: ConditionObserverConfig[];

    private $row: JQuery;

    private subjects: ConditionType[] = [];

    constructor(element: HTMLElement)
    {
        this.$element = $(element);
        this.configs = this.$element.data('condition-type-observer');
        let parent = this.$element.parents('[data-form-row]').get(0);
        this.$row = $(parent);

        for (let subject of ConditionType.subjects) {
            let config = this.getConfig(subject);
            if(config !== null) {
                this.subjects.push(subject);
                subject.register(this);
            }
        }
    }

    private getConfig(subject: ConditionType) : ConditionObserverConfig|null
    {
        for (let config of this.configs) {
            if (subject.getId() == config.id) {
                return config;
            }
        }
        return null;
    }

    public wakeUp(subject: ConditionType)
    {
        let condition = null;
        for (let subject of this.subjects) {
            let config = this.getConfig(subject);
            let subjectCondition = config.values.indexOf(subject.getValue()) >= 0;
            if(condition === null) {
                condition = subjectCondition;
            } else {
                if(config.operator == 'and') {
                    condition = condition && subjectCondition;
                } else if(config.operator == 'or') {
                    condition = condition || subjectCondition;
                }
            }
        }

        if(condition) {
            this.show();
        } else {
            this.hide();
        }
    }

    private hide()
    {
        this.$row.hide();
    }

    private show()
    {
        this.$row.show();
    }
}

ConditionType.init();