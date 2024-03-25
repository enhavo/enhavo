## Migrate to 0.8

Providing a small guide to upgrade to enhavo 0.8. This guide is not
complete and covers only the most common update steps.

### Enhavo upgrade tasks

-   Update Grid entities to Blocks entities (Entities extends from
    `AbstractBlock`)
-   Update Grid factories to Blocks factories (Factories extend from
    `AbstractBlockFactory`)
-   Update Grid types to Blocks types (Types extend from
    `AbstractBlockType`)
-   Update Block form type (Extends from default `AbstractType`)
-   Update Grid content references (Using `NodeInterface`)
-   Migrate Grid data to block data structure (Using Doctrine
    migrations)
-   Rename Grid entities to Block (Optional)
-   Copy asset entrypoints structure (Copy also registry folder)
-   Migrate and add enhavo configs
-   Migrate and add enhavo routings
-   Migrate custom admin assets to webpack (Important for Version \<0.7)
-   Update or create `webpack.config.js`
-   Require separate enhavo packages by composer
-   Use symfony 4 config and routing structure
-   Set language in config under `enhavp_app.locale`

### Symfony upgrade tasks

-   Use Symfony 4 directory structure
-   Migrate config
-   Use FQCN for Form types
-   Remove getName and use getBlockPrefix for Form types
-   Inject private services
-   Update service definitions
-   Add Kernel
-   Add tag `console.command` to command services (Create service if not
    exists)
