@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.catalog.products.edit-title') }}
@stop

@section('content')
    <div class="content">

        {!! view_render_event('bagisto.admin.catalog.service.create_form_accordian.general.before') !!}

        <form method="POST" action="" @submit.prevent="onSubmit" enctype="multipart/form-data">

            <div class="page-header">

                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link"
                           onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                        {{ __('service::app.catalog.services.add-title') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('service::app.catalog.services.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                @csrf()

                <input type="hidden" name="locale" value="all"/>

                <accordian :title="'{{ __('service::app.catalog.services.general') }}'" :active="true">
                    <div slot="body">

                        <div class="control-group" :class="[errors.has('title') ? 'has-error' : '']">
                            <label for="title" class="required">{{ __('service::app.catalog.services.name') }}</label>
                            <input type="text" v-validate="'required'" class="control" id="title" name="title" value="{{ old('title') }}" data-vv-as="&quot;{{ __('service::app.catalog.services.title') }}&quot;" v-slugify-target="'slug'"/>
                            <span class="control-error" v-if="errors.has('title')">@{{ errors.first('title') }}</span>
                        </div>
                        <div class="control-group" :class="[errors.has('organization') ? 'has-error' : '']">
                            <label for="organization" class="required">{{ __('service::app.catalog.services.organization') }}</label>
                            <input type="text" v-validate="'required'" class="control" id="organization" name="organization" value="{{ old('title') }}" data-vv-as="&quot;{{ __('service::app.catalog.services.organization') }}&quot;"/>
                            <span class="control-error" v-if="errors.has('organization')">@{{ errors.first('organization') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('position') ? 'has-error' : '']">
                            <label for="position" class="required">{{ __('admin::app.catalog.categories.position') }}</label>
                            <input type="text" v-validate="'required|numeric'" class="control" id="position" name="position" value="{{ old('position') }}" data-vv-as="&quot;{{ __('admin::app.catalog.categories.position') }}&quot;"/>
                            <span class="control-error" v-if="errors.has('position')">@{{ errors.first('position') }}</span>
                        </div>

                        <div class="control-group boolean">
                            <label for="status">{{ __('admin::app.catalog.products.status') }}</label>
                            <label class="switch">
                                <input type="checkbox" class="control" id="status" name="status" data-vv-as="&quot;{{ __('admin::app.catalog.products.status') }}&quot;" value="1">
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <description></description>
                    </div>


                </accordian>
                @if ($categories->count())

                    <accordian :title="'{{ __('admin::app.catalog.products.categories') }}'" :active="false">
                        <div slot="body">

                            <tree-view behavior="normal" value-field="id" name-field="categories" input-type="checkbox" items='@json($categories)'></tree-view>

                        </div>
                    </accordian>

                @endif
                <accordian :title="'{{ __('admin::app.catalog.products.images') }}'" :active="false">
                    <div slot="body">

                        <div class="control-group {!! $errors->has('images.*') ? 'has-error' : '' !!}">
                            <label>{{ __('admin::app.catalog.categories.image') }}</label>

                            <image-wrapper :button-label="'{{ __('admin::app.catalog.products.add-image-btn-title') }}'" input-name="images" ></image-wrapper>

                            <span class="control-error" v-if="{!! $errors->has('images.*') !!}">
                            @php $count=1 @endphp
                                @foreach ($errors->get('images.*') as $key => $message)
                                    @php echo str_replace($key, 'Image'.$count, $message[0]); $count++ @endphp
                                @endforeach
                            </span>
                        </div>

                    </div>
                </accordian>
                <accordian :title="'{{ __('service::app.catalog.services.social') }}'" :active="true">
                    <div slot="body">
                        <div class="control-group">
                            <label for="facebook">{{ __('service::app.catalog.services.facebook') }}</label>
                            <input type="text" class="control" id="facebook" name="facebook" value="{{ old('facebook') }}"/>
                        </div>
                        <div class="control-group">
                            <label for="instagram">{{ __('service::app.catalog.services.instagram') }}</label>
                            <input type="text" class="control" id="instagram" name="instagram" value="{{ old('instagram') }}"/>
                        </div>
                        <div class="control-group">
                            <label for="linkedin">{{ __('service::app.catalog.services.linkedin') }}</label>
                            <input type="text" class="control" id="linkedin" name="linkedin" value="{{ old('linkedin') }}"/>
                        </div>
                    </div>
                </accordian>

                <accordian :title="'{{ __('admin::app.catalog.categories.seo') }}'" :active="true">
                    <div slot="body">

                        <div class="control-group">
                            <label for="meta_title">{{ __('admin::app.catalog.categories.meta_title') }}</label>
                            <input type="text" class="control" id="meta_title" name="meta_title" value="{{ old('meta_title') }}"/>
                        </div>

                        <div class="control-group" :class="[errors.has('slug') ? 'has-error' : '']">
                            <label for="slug" class="required">{{ __('admin::app.catalog.categories.slug') }}</label>
                            <input type="text" v-validate="'required'" class="control" id="slug" name="slug" value="{{ old('slug') }}" data-vv-as="&quot;{{ __('admin::app.catalog.categories.slug') }}&quot;" v-slugify/>
                            <span class="control-error" v-if="errors.has('slug')">@{{ errors.first('slug') }}</span>
                        </div>

                        <div class="control-group">
                            <label for="meta_description">{{ __('admin::app.catalog.categories.meta_description') }}</label>
                            <textarea class="control" id="meta_description" name="meta_description">{{ old('meta_description') }}</textarea>
                        </div>

                        <div class="control-group">
                            <label for="meta_keywords">{{ __('admin::app.catalog.categories.meta_keywords') }}</label>
                            <textarea class="control" id="meta_keywords" name="meta_keywords">{{ old('meta_keywords') }}</textarea>
                        </div>

                    </div>
                </accordian>
            </div>

        </form>

    </div>
@stop

@push('scripts')
    <script src="{{ asset('vendor/webkul/admin/assets/js/tinyMCE/tinymce.min.js') }}"></script>
    <script type="text/x-template" id="description-template">

        <div class="control-group" :class="[errors.has('description') ? 'has-error' : '']">
            <label for="description" :class="isRequired ? 'required' : ''">{{ __('admin::app.catalog.categories.description') }}</label>
            <textarea v-validate="isRequired ? 'required' : ''"  class="control" id="description" name="description" data-vv-as="&quot;{{ __('admin::app.catalog.categories.description') }}&quot;">{{ old('description') }}</textarea>
            <span class="control-error" v-if="errors.has('description')">@{{ errors.first('description') }}</span>
        </div>

    </script>
    <script>
        $(document).ready(function () {
            $('#channel-switcher, #locale-switcher').on('change', function (e) {
                $('#channel-switcher').val()
                var query = '?channel=' + $('#channel-switcher').val() + '&locale=' + $('#locale-switcher').val();

                window.location.href = "{{ route('admin.catalog.services.index')  }}" + query;
            })

            tinymce.init({
                selector: 'textarea#description, textarea#short_description',
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
                });
            }
        });
    </script>
@endpush
