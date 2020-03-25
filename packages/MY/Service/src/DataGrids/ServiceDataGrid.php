<?php


namespace My\Service\DataGrids;


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
        $queryBuilder = DB::table('services as srv')
            ->leftJoin('service_translations as st', function($leftJoin) {
                $leftJoin->on('srv.id', '=', 'st.service_id')
                    ->where('st.locale', app()->getLocale());
            })
            ->select(
                'st.service_id as service_id',
                'st.title',
                'st.organization',
                'srv.status',
                'srv.position')
            ->groupBy('srv.id');

        $this->addFilter('service_id', 'st.service_id');
        $this->addFilter('title', 'st.title');
        $this->addFilter('organization', 'st.organization');

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
            'index'      => 'title',
            'label'      => trans('admin::app.datagrid.title'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'organization',
            'label'      => trans('service::app.datagrid.organization'),
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

        $this->addColumn([
            'index'      => 'position',
            'label'      => trans('service::app.datagrid.position'),
            'type'       => 'number',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
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