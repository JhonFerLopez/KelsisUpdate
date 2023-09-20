<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\Core\Models\Locale;
use Webkul\Ui\DataGrid\DataGrid;

class PreparationDataGrid extends DataGrid
{
    /**
     * Index.
     *
     * @var string
     */
    protected $index = 'preparation_id';

    /**
     * Sort order.
     *
     * @var string
     */
    protected $sortOrder = 'desc';

    /**
     * Locale.
     *
     * @var string
     */
    protected $locale = 'all';

     /**
     * Contains the keys for which extra filters to show.
     *
     * @var string[]
     */
    protected $extraFilters = [
        'locales',
    ];

    /**
     * Create a new datagrid instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->locale = core()->getRequestedLocaleCode();
    }

    /**
     * Prepare query builder.
     *
     * @return void
     */
    public function prepareQueryBuilder()
    {
        if ($this->locale === 'all') {
            $whereInLocales = Locale::query()->pluck('code')->toArray();
        } else {
            $whereInLocales = [$this->locale];
        }

        $queryBuilder = DB::table('preparations as cat')
            ->select(
                'cat.id as preparation_id',
                'ct.name',
                'cat.position',
                'cat.status',
                'ct.locale',
                DB::raw('COUNT(DISTINCT ' . DB::getTablePrefix() . 'pc.product_id) as count')
            )
            ->leftJoin('preparation_translations as ct', function ($leftJoin) use ($whereInLocales) {
                $leftJoin->on('cat.id', '=', 'ct.preparation_id')
                    ->whereIn('ct.locale', $whereInLocales);
            })
            ->leftJoin('product_preparations as pc', 'cat.id', '=', 'pc.preparation_id')
            ->groupBy('cat.id', 'ct.locale',);


        $this->addFilter('status', 'cat.status');
        $this->addFilter('preparation_id', 'cat.id');

        $this->setQueryBuilder($queryBuilder);
    }

    /**
     * Add columns.
     *
     * @return void
     */
    public function addColumns()
    {
        $this->addColumn([
            'index'      => 'preparation_id',
            'label'      => trans('admin::app.datagrid.id'),
            'type'       => 'number',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'name',
            'label'      => trans('admin::app.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'position',
            'label'      => trans('admin::app.datagrid.position'),
            'type'       => 'number',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('admin::app.datagrid.status'),
            'type'       => 'boolean',
            'sortable'   => true,
            'searchable' => true,
            'filterable' => true,
            'closure'    => function ($value) {
                if ($value->status) {
                    return '<span class="badge badge-md badge-success">'. trans('admin::app.datagrid.active') . '</span>';
                }

                return '<span class="badge badge-md badge-danger">'. trans('admin::app.datagrid.inactive') . '</span>';
            },
        ]);

        $this->addColumn([
            'index'      => 'count',
            'label'      => trans('admin::app.datagrid.no-of-products'),
            'type'       => 'number',
            'sortable'   => true,
            'searchable' => false,
            'filterable' => false,
        ]);
    }

    /**
     * Prepare actions.
     *
     * @return void
     */
    public function prepareActions()
    {
        $this->addAction([
            'title'  => trans('admin::app.datagrid.edit'),
            'method' => 'GET',
            'route'  => 'admin.catalog.preparations.edit',
            'icon'   => 'icon pencil-lg-icon',
        ]);

        $this->addAction([
            'title'        => trans('admin::app.datagrid.delete'),
            'method'       => 'POST',
            'route'        => 'admin.catalog.preparations.delete',
            'confirm_text' => trans('ui::app.datagrid.mass-action.delete', ['resource' => 'product']),
            'icon'         => 'icon trash-icon',
        ]);

        $this->addMassAction([
            'type'   => 'delete',
            'label'  => trans('admin::app.datagrid.delete'),
            'action' => route('admin.catalog.preparations.mass_delete'),
            'method' => 'POST',
        ]);

        $this->addMassAction([
            'type'    => 'update',
            'label'   => trans('admin::app.datagrid.update-status'),
            'action'  => route('admin.catalog.preparations.mass_update'),
            'method'  => 'POST',
            'options' => [
                trans('admin::app.datagrid.active')    => 1,
                trans('admin::app.datagrid.inactive')  => 0,
            ],
        ]);
    }
}
