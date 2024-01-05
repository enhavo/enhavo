# Conventions

> **Note**
>
> This article is outdated and may contain information that is no longer in use

## Route name

- Don't use camel case, use underscore instead

For example, if you have a bundle called `enhavoAppBundle`, and an action called `index`, then you should name your route like this: `enhavo_app_index`.
A route should contain the following information divided by an underscore:

1) Company name
2) Bundle name
3) Entity name (optional)
4) Action name


## Bundle

- One menu is one app
- One app should be one Bundle
- Only in the Project Bundle can you add more than one app