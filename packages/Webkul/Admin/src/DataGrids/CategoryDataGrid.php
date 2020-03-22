<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\Ui\DataGrid\DataGrid;

class CategoryDataGrid extends DataGrid
{
    protected $index = 'category_id';

    protected $sortOrder = 'desc';


    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('categories as cat')
            ->select('cat.id as category_id', 'ct.name as category_name', 'cat.position', 'cat.status', 'ct.locale',
            DB::raw('COUNT(DISTINCT ' . DB::getTablePrefix() . 'pc.product_id) as prd_count'),
            DB::raw('COUNT(DISTINCT ' . DB::getTablePrefix() . 'sr.service_id) as srv_count'))
            //todo count services also
            ->leftJoin('category_translations as ct', function($leftJoin) {
                $leftJoin->on('cat.id', '=', 'ct.category_id')
                         ->where('ct.locale', app()->getLocale());
            })
            ->leftJoin('product_categories as pc', 'cat.id', '=', 'pc.category_id')
            ->leftJoin('service_categories as sr', 'cat.id', '=', 'sr.category_id')
            ->groupBy('cat.id');

        $this->addFilter('category_id', 'cat.id');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index'      => 'category_id',
            'label'      => trans('admin::app.datagrid.id'),
            'type'       => 'number',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'category_name',
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
            'wrapper'    => function($value) {
                if ($value->status == 1) {
                    return trans('admin::app.datagrid.active');
                } else {
                    return trans('admin::app.datagrid.inactive');
                }
            },
        ]);

        $this->addColumn([
            'index'      => 'prd_count',
            'label'      => trans('admin::app.datagrid.no-of-products'),
            'type'       => 'number',
            'sortable'   => true,
            'searchable' => false,
            'filterable' => false,
        ]);

        $this->addColumn([
            'index'      => 'srv_count',
            'label'      => trans('admin::app.datagrid.no-of-services'),
            'type'       => 'number',
            'sortable'   => true,
            'searchable' => false,
            'filterable' => false,
        ]);

        //todo add services count column
    }

    public function prepareActions()
    {
        $this->addAction([
            'title'  => trans('admin::app.datagrid.edit'),
            'method' => 'GET',
            'route'  => 'admin.catalog.categories.edit',
            'icon'   => 'icon pencil-lg-icon',
        ]);

        $this->addAction([
            'title'        => trans('admin::app.datagrid.delete'),
            'method'       => 'POST',
            'route'        => 'admin.catalog.categories.delete',
            'confirm_text' => trans('ui::app.datagrid.massaction.delete', ['resource' => 'product']),
            'icon'         => 'icon trash-icon',
        ]);
    }
}