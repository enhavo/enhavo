interface JQuery {
    datetimepicker(object: object): JQuery
    datetime: (config: DateTimePickerConfig) => void
}

interface DateTimePicker
{
    setLocale(locale: string): void;
}

interface DateTimePickerConfig
{
    timeFormat: string;
    timeText: string;
    hourText: string;
    minuteText: string;
    currentText: string;
    closeText: string;
    dateFormat: string;
    stepMinute: number,
    firstDay: number
}