<?php


namespace Webkul\Admin\DataGrids;


use Illuminate\Support\Facades\DB;
use Webkul\Ui\DataGrid\DataGrid;

class ServiceDataGrid extends DataGrid
{
    protected $index = 'service_id';

    protected $sortOrder = 'desc';

    protected $itemsPerPage = 10;

    protected $locale = 'all';

    public function __construct()
    {
        parent::__construct();

        $this->locale = request()->get('locale') ?? 'all';

    }

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('service_flat')
            ->leftJoin('services', 'service_flat.service_id', '=', 'services.id')
            ->select(
                'service_flat.service_id as service_id',
                'service_flat.name',
                'service_flat.company_name',
                'service_flat.status'
            );

        if ($this->locale !== 'all') {
            $queryBuilder->where('locale', $this->locale);
        } else {
            $queryBuilder->whereNotNull('service_flat.name');
        }

        $this->addFilter('service_id', 'service_flat.service_id');
        $this->addFilter('service_name', 'service_flat.name');
        $this->addFilter('company_name', 'service_flat.company_name');
        $this->addFilter('status', 'service_flat.status');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index'      => 'service_id',
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
            'index'      => 'company_name',
            'label'      => trans('admin::app.datagrid.company_name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);


        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('admin::app.datagrid.status'),
            'type'       => 'boolean',
            'sortable'   => true,
            'searchable' => false,
            'filterable' => true,
            'wrapper'    => function($value) {
                if ($value->status == 1) {
                    return trans('admin::app.datagrid.active');
                } else {
                    return trans('admin::app.datagrid.inactive');
                }
            },
        ]);


    }

    public function prepareActions()
    {
        $this->addAction([
            'title'     => trans('admin::app.datagrid.edit'),
            'method'    => 'GET',
            'route'     => 'admin.catalog.services.edit',
            'icon'      => 'icon pencil-lg-icon',
            'condition' => function() {
                return true;
            },
        ]);

        $this->addAction([
            'title'        => trans('admin::app.datagrid.delete'),
            'method'       => 'POST',
            'route'        => 'admin.catalog.services.delete',
            'confirm_text' => trans('ui::app.datagrid.massaction.delete', ['resource' => 'service']),
            'icon'         => 'icon trash-icon',
        ]);
    }

    public function prepareMassActions()
    {
        $this->addMassAction([
            'type'   => 'delete',
            'label'  => trans('admin::app.datagrid.delete'),
            'action' => route('admin.catalog.services.massdelete'),
            'method' => 'DELETE',
        ]);

        $this->addMassAction([
            'type'    => 'update',
            'label'   => trans('admin::app.datagrid.update-status'),
            'action'  => route('admin.catalog.services.massupdate'),
            'method'  => 'PUT',
            'options' => [
                'Active'   => 1,
                'Inactive' => 0,
            ],
        ]);
    }
}