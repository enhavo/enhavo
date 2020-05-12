
class EnhavoThemeEncore
{
    constructor() {
        this.manifestFiles = [];
    }

    addThemes(EnhavoEncore, ThemeLoader) {
        for(let theme of ThemeLoader.getThemes()) {
            EnhavoEncore.add(theme.key, (Encore) => {
                for(let entry of theme.entries) {
                    Encore.addEntry(entry.name, entry.path)
                }
            })
        }
    }
}

module.exports = new EnhavoThemeEncore;