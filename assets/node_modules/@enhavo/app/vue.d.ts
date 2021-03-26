import WidgetManager from "@enhavo/dashboard/widget/WidgetManager";
import MenuManager from "@enhavo/app/menu/MenuManager";
import MainApp from "@enhavo/app/main/MainApp";
import Grid from "@enhavo/app/grid/Grid";
import ColumnManager from "@enhavo/app/grid/column/ColumnManager";
import BatchManager from "@enhavo/app/grid/batch/BatchManager";
import FilterManager from "@enhavo/app/grid/filter/FilterManager";
import EventDispatcher from "@enhavo/app/view-stack/EventDispatcher";
import DeleteApp from "@enhavo/app/delete/DeleteApp";
import Translator from "@enhavo/core/Translator";
import Router from "@enhavo/core/Router";

declare module 'vue/types/vue' {
    interface Vue {
        $widgetManager: WidgetManager
        $menuManager: MenuManager
        $mainApp: MainApp
        $grid: Grid
        $columnManager: ColumnManager
        $batchManager: BatchManager
        $filterManager: FilterManager
        eventDispatcher: EventDispatcher,
        $deleteApp: DeleteApp,
        $translator: Translator,
        $router: Router
    }
}
