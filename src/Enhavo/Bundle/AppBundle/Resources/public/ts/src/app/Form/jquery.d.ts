interface JQuery {
    datetimepicker: (config: DateTimePickerConfig) => void
    datetime: (config: DateTimePickerConfig) => void
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