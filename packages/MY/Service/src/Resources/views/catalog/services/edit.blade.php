@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.catalog.categories.edit-title') }}
@stop

@section('content')
    <div class="content">
        <?php $locale = request()->get('locale') ?: app()->getLocale(); ?>

        <form method="POST" action="" @submit.prevent="onSubmit" enctype="multipart/form-data">

            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                        {{ __('service::app.catalog.services.edit-title') }}
                    </h1>

                    <div class="control-group">
                        <select class="control" id="locale-switcher" onChange="window.location.href = this.value">
                            @foreach (core()->getAllLocales() as $localeModel)

                                <option value="{{ route('admin.catalog.services.update', $service->id) . '?locale=' . $localeModel->code }}" {{ ($localeModel->code) == $locale ? 'selected' : '' }}>
                                    {{ $localeModel->name }}
                                </option>

                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('service::app.catalog.services.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    @csrf()
                    <input name="_method" type="hidden" value="PUT">

                    <accordian :title="'{{ __('service::app.catalog.services.general') }}'" :active="true">
                        <div slot="body">

                            <div class="control-group" :class="[errors.has('{{$locale}}[title]') ? 'has-error' : '']">
                                <label for="title" class="required">{{ __('service::app.catalog.services.name') }}</label>
                                <input type="text" v-validate="'required'" class="control" id="title" name="{{$locale}}[title]" value="{{ old($locale)['title'] ?? $service->translate($locale)['title'] }}"
                                       data-vv-as="&quot;{{ __('service::app.catalog.services.name') }}&quot;" v-slugify-target="'slug'"/>
                                <span class="control-error" v-if="errors.has('{{$locale}}[title]')">@{{ errors.first('{!!$locale!!}[title]') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('{{$locale}}[organization]') ? 'has-error' : '']">
                                <label for="organization" class="required">{{ __('service::app.catalog.services.organization') }}</label>
                                <input type="text" v-validate="'required'" class="control" id="organization" name="{{$locale}}[organization]" value="{{ old($locale)['organization']??$service->translate($locale)['organization'] }}"
                                       data-vv-as="&quot;{{ __('service::app.catalog.services.organization') }}&quot;"/>
                                <span class="control-error" v-if="errors.has('{{$locale}}[organization]')">@{{ errors.first('{!!$locale!!}[organization]') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('position') ? 'has-error' : '']">
                                <label for="position" class="required">{{ __('admin::app.catalog.categories.position') }}</label>
                                <input type="text" v-validate="'required|numeric'" class="control" id="position" name="position" value="{{ old('position') ?: $service->position }}" data-vv-as="&quot;{{ __('admin::app.catalog.categories.position') }}&quot;"/>
                                <span class="control-error" v-if="errors.has('position')">@{{ errors.first('position') }}</span>
                            </div>

                            <div class="control-group boolean">
                                <label for="status">{{ __('admin::app.catalog.products.status') }}</label>
                                <label class="switch">
                                    <input type="checkbox" class="control" id="status" name="status" data-vv-as="&quot;{{ __('admin::app.catalog.products.status') }}&quot;" {{ $service->status ? 'checked' : ''}} value="1">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                    </accordian>

                    <accordian :title="'{{ __('admin::app.catalog.categories.description-and-images') }}'" :active="true">
                        <div slot="body">

                            <description></description>

                            <div class="control-group {!! $errors->has('image.*') ? 'has-error' : '' !!}">
                                <label>{{ __('admin::app.catalog.categories.image') }}</label>

                                <image-wrapper :button-label="'{{ __('admin::app.catalog.products.add-image-btn-title') }}'" input-name="images" :images='@json($service->images)'></image-wrapper>

                                <span class="control-error" v-if="{!! $errors->has('image.*') !!}">
                                    @foreach ($errors->get('image.*') as $key => $message)
                                        @php echo str_replace($key, 'Image', $message[0]); @endphp
                                    @endforeach
                                </span>

                            </div>

                        </div>
                    </accordian>

                    <accordian :title="'{{ __('service::app.catalog.services.social') }}'" :active="true">
                        <div slot="body">
                            <div class="control-group">
                                <label for="facebook">{{ __('service::app.catalog.services.facebook') }}</label>
                                <input type="text" class="control" id="facebook" name="facebook" value="{{ old('facebook')?: $service->facebook }}"/>
                            </div>
                            <div class="control-group">
                                <label for="instagram">{{ __('service::app.catalog.services.instagram') }}</label>
                                <input type="text" class="control" id="instagram" name="instagram" value="{{ old('instagram')?: $service->instagram }}"/>
                            </div>
                            <div class="control-group">
                                <label for="linkedin">{{ __('service::app.catalog.services.linkedin') }}</label>
                                <input type="text" class="control" id="linkedin" name="linkedin" value="{{ old('linkedin')?: $service->linkedin }}"/>
                            </div>
                        </div>
                    </accordian>

                    <accordian :title="'{{ __('admin::app.catalog.categories.seo') }}'" :active="true">
                        <div slot="body">

                            <div class="control-group">
                                <label for="meta_title">{{ __('admin::app.catalog.categories.meta_title') }}</label>
                                <input type="text" class="control" id="meta_title" name="{{$locale}}[meta_title]" value="{{ old($locale)['meta_title'] ?? $service->translate($locale)['meta_title'] }}"/>
                            </div>

                            <div class="control-group" :class="[errors.has('{{$locale}}[slug]') ? 'has-error' : '']">
                                <label for="slug" class="required">{{ __('admin::app.catalog.categories.slug') }}</label>
                                <input type="text" v-validate="'required'" class="control" id="slug" name="{{$locale}}[slug]" value="{{ old($locale)['slug'] ?? $service->translate($locale)['slug'] }}" data-vv-as="&quot;{{ __('admin::app.catalog.categories.slug') }}&quot;" v-slugify/>
                                <span class="control-error" v-if="errors.has('{{$locale}}[slug]')">@{{ errors.first('{!!$locale!!}[slug]') }}</span>
                            </div>

                            <div class="control-group">
                                <label for="meta_description">{{ __('admin::app.catalog.categories.meta_description') }}</label>
                                <textarea class="control" id="meta_description" name="{{$locale}}[meta_description]">{{ old($locale)['meta_description'] ?? $service->translate($locale)['meta_description'] }}</textarea>
                            </div>

                            <div class="control-group">
                                <label for="meta_keywords">{{ __('admin::app.catalog.categories.meta_keywords') }}</label>
                                <textarea class="control" id="meta_keywords" name="{{$locale}}[meta_keywords]">{{ old($locale)['meta_keywords'] ?? $service->translate($locale)['meta_keywords'] }}</textarea>
                            </div>

                        </div>
                    </accordian>

                </div>
            </div>

        </form>
    </div>
@stop

@push('scripts')
    <script src="{{ asset('vendor/webkul/admin/assets/js/tinyMCE/tinymce.min.js') }}"></script>

    <script type="text/x-template" id="description-template">

        <div class="control-group" :class="[errors.has('{{$locale}}[description]') ? 'has-error' : '']">
            <label for="description" :class="isRequired ? 'required' : ''">{{ __('admin::app.catalog.categories.description') }}</label>
            <textarea v-validate="isRequired ? 'required' : ''" class="control" id="description" name="{{$locale}}[description]" data-vv-as="&quot;{{ __('admin::app.catalog.categories.description') }}&quot;">{{ old($locale)['description'] ?? $service->translate($locale)['description'] }}</textarea>
            <span class="control-error" v-if="errors.has('{{$locale}}[description]')">@{{ errors.first('{!!$locale!!}[description]') }}</span>
        </div>

    </script>

    <script>
        $(document).ready(function () {
            tinymce.init({
                selector: 'textarea#description',
                height: 200,
                width: "100%",
                plugins: 'image imagetools media wordcount save fullscreen code',
                toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent  | removeformat | code',
                image_advtab: true
            });
        });

        Vue.component('description', {

            template: '#description-template',

            inject: ['$validator'],

            data: function() {
                return {
                    isRequired: true,
                }
            },

            created: function () {
                var this_this = this;

                $(document).ready(function () {
                    $('#display_mode').on('change', function (e) {
                        if ($('#display_mode').val() != 'products_only') {
                            this_this.isRequired = true;
                        } else {
                            this_this.isRequired = false;
                        }
                    })

                    if ($('#display_mode').val() != 'products_only') {
                        this_this.isRequired = true;
                    } else {
                        this_this.isRequired = false;
                    }
                });
            }
        })
    </script>
@endpush