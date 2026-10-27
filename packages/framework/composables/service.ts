import { computed, inject, shallowRef, watch, type ComputedRef, type InjectionKey, type Ref, type ShallowRef, type WatchStopHandle } from 'vue'

type Unwrap<T extends Ref<unknown>[]> = { [K in keyof T]: NonNullable<T[K]['value']> }

/**
 * Resolves once the given ref holds a non-null value.
 * Must be called synchronously in setup so the watcher is bound to the component.
 */
function untilLoaded<T>(source: Ref<T | null>): Promise<T> {
    return new Promise((resolve) => {
        if (source.value !== null) {
            resolve(source.value)
            return
        }
        const stop = watch(source, (value) => {
            if (value !== null) {
                stop()
                resolve(value)
            }
        })
    })
}

/**
 * Injects an async service and exposes it as a ref that is null until
 * the service has resolved, all dependencies are loaded and `init` has finished.
 *
 * - `init`: optional setup per call site (e.g. `m => m.load()`).
 *           Return `false` to opt out: the ref then stays null.
 * - `dependsOn`: refs from other useAsyncService calls that must be loaded first.
 *
 * Non-null therefore means "initialized".
 * Must be called synchronously inside setup().
 */
export function useAsyncService<T>(
    key: InjectionKey<Promise<T>> | string,
    init?: (service: T) => void | boolean | Promise<void | boolean>,
    dependsOn: Ref<unknown>[] = [],
): ShallowRef<T | null> {
    // inject() must run synchronously in setup
    const promise = inject<Promise<T>>(key)
    if (!promise) {
        throw new Error(`Service "${String(key)}" not provided`)
    }

    const service = shallowRef<T | null>(null)

    // created synchronously so the watchers are stopped on unmount
    const dependencies = Promise.all(dependsOn.map(untilLoaded))

    Promise.all([promise, dependencies])
        .then(async ([s]) => {
            const result = await init?.(s)
            if (result === false) return // opted out, service stays null
            service.value = s
        })
        .catch((e) => console.error(`Failed to load service "${String(key)}"`, e))

    return service
}

/**
 * Calls `callback` once, as soon as all given refs (e.g. from useAsyncService) are non-null.
 * The resolved services are passed as arguments, so no `.value` or null checks are needed.
 * Must be called synchronously inside setup() so the watcher is stopped on unmount.
 */
export function useWhenReady<T extends Ref<unknown>[]>(
    services: [...T],
    callback: (...resolved: Unwrap<T>) => void,
): void {
    let stop: WatchStopHandle | undefined
    let done = false

    stop = watch(
        () => services.map((service) => service.value),
        (values) => {
            if (done || values.some((value) => value === null)) return
            done = true
            stop?.() // undefined if already ready on the immediate run
            callback(...(values as Unwrap<T>))
        },
        { immediate: true },
    )

    // all services were ready on the immediate run, before stop was assigned
    if (done) stop()
}

/**
 * Computed value from the given refs (e.g. from useAsyncService).
 * Returns `fallback` until all refs are non-null, then the result of `getter`
 * with the resolved services as arguments.
 */
export function computedWhenReady<T extends Ref<unknown>[], R>(
    services: [...T],
    getter: (...resolved: Unwrap<T>) => R,
    fallback: R,
): ComputedRef<R> {
    return computed(() => {
        const values = services.map((service) => service.value)
        if (values.some((value) => value === null)) return fallback
        return getter(...(values as Unwrap<T>))
    })
}

/**
 * Computed flag that is true once all given refs (e.g. from useAsyncService) are non-null.
 * Useful for a single `v-if` guard in the template.
 */
export function useServicesReady(...services: Ref<unknown>[]): ComputedRef<boolean> {
    return computed(() => services.every((service) => service.value !== null))
}
