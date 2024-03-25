## Add new setting


First add a file in your config directory under Resources and name it
`setting.yml`. Each setting has a single key followed by configuration
for this specific setting. You yaml file can look like following file:

```yaml
project.start_date
    label: project.label.start_date
    type: date
    translationDomain: ProjectBundle
```

### Possibles types

| Type       | Description      |
|------------|------------------|
| text       | Text             |
| boolean    | Boolean          |
| file       | Single File      |
| files      | Multiple Files   |
| wysiwyg    | Text editor      |
| date       | Date             |
| datetime   | Date with time   |

