@extends('admin::layouts.content')

@section('page_title')
    {{ __('service::app.catalog.services.title') }}
@stop

@section('content')
    <div class="content" style="height: 100%;">
        <?php $locale = request()->get('locale') ?: null; ?>
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('service::app.catalog.services.title') }}</h1>
            </div>

            <div class="page-action">
                <div class="export-import" @click="showModal('downloadDataGrid')">
                    <i class="export-icon"></i>
                    <span >
                        {{ __('admin::app.export.export') }}
                    </span>
                </div>

                <a href="{{ route('admin.catalog.services.create') }}" class="btn btn-lg btn-primary">
                    {{ __('service::app.catalog.services.add-title') }}
                </a>
            </div>
        </div>

        {!! view_render_event('bagisto.admin.catalog.services.list.before') !!}

        <div class="page-content">
            @inject('services', 'MY\Service\DataGrids\ServiceDataGrid')
            {!! $services->render() !!}
        </div>

        {!! view_render_event('bagisto.admin.catalog.services.list.after') !!}

    </div>

    <modal id="downloadDataGrid" :is-open="modalIds.downloadDataGrid">
        <h3 slot="header">{{ __('admin::app.export.download') }}</h3>
        <div slot="body">
            <export-form></export-form>
        </div>
    </modal>
@stop

@push('scripts')
    @include('admin::export.export', ['gridName' => $services])
    <script>

        function reloadPage(getVar, getVal) {
            let url = new URL(window.location.href);
            url.searchParams.set(getVar, getVal);

            window.location.href = url.href;
        }

    </script>
@endpush