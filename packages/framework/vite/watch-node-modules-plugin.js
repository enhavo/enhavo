export function watchNodeModules(modules) {
    return {
        name: 'watch-node-modules',
        configureServer: (server) => {
            const regexp = `/node_modules\\/(?!${modules.join('|')}).*/`
            server.watcher.options = {
                ...server.watcher.options,
                ignored: [
                    '**/.git/**',
                    '**/test-results/**',
                    new RegExp(regexp)
                ]
            }
            server.watcher._userIgnored = undefined
        },
        config () {
            return {
                optimizeDeps: {
                    exclude: modules
                }
            }
        }
    }
}
