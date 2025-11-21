@extends(config('core.admin_theme').'.template')
@section('main')
    <form class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid {{ @$module['code'] }}"
          action="" method="POST"
          enctype="multipart/form-data">
        {{ csrf_field() }}
        <input name="return_direct" value="save_exit" type="hidden">
        <div class="row">
            <div class="col-lg-12">
                <!--begin::Portlet-->
                <div class="kt-portlet kt-portlet--last kt-portlet--head-lg kt-portlet--responsive-mobile"
                     id="kt_page_portlet">
                    <div class="kt-portlet__head kt-portlet__head--lg" style="">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">{{__('lang.Add_new')}} {{ __($module['label']) }}
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <a href="/admin/{{ $module['code'] }}" class="btn btn-clean kt-margin-r-10">
                                <i class="la la-arrow-left"></i>
                                <span class="kt-hidden-mobile">{{__('lang.back')}}</span>
                            </a>
                            <div class="btn-group">
                                {{--                                @if(in_array($module['code'] . '_add', $permissions))--}}
                                <button type="submit" class="btn btn-brand">
                                    <i class="la la-check"></i>
                                    <span class="kt-hidden-mobile">{{__('lang.save')}}</span>
                                </button>
                                <button type="button"
                                        class="btn btn-brand dropdown-toggle dropdown-toggle-split"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <ul class="kt-nav">
                                        <li class="kt-nav__item">
                                            <a class="kt-nav__link save_option" data-action="save_continue">
                                                <i class="kt-nav__link-icon flaticon2-reload"></i>
                                                {{__('lang.save_and_continue')}}
                                            </a>
                                        </li>
                                        <li class="kt-nav__item">
                                            <a class="kt-nav__link save_option" data-action="save_exit">
                                                <i class="kt-nav__link-icon flaticon2-power"></i>
                                                {{__('lang.save_and_exit')}}
                                            </a>
                                        </li>
                                        <li class="kt-nav__item">
                                            <a class="kt-nav__link save_option" data-action="save_create">
                                                <i class="kt-nav__link-icon flaticon2-add-1"></i>
                                                {{__('lang.save_and_create')}}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                {{--                                @endif--}}
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Portlet-->
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-8">
                <!--begin::Portlet-->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                {{__('lang.product_descriptions')}}
                            </h3>
                        </div>
                    </div>
                    {{--                    @include('CRMDV.form.fields.thong_tin_thanh_toan')--}}
                    <!--begin::Form-->
                    {{--                    <div class="kt-form">--}}
                    {{--                        <div class="kt-portlet__body">--}}
                    {{--                            <div class="kt-section kt-section--first">--}}
                    {{--                                @foreach($module['form']['general_tab'] as $field)--}}
                    {{--                                    <div class="form-group-div form-group {{ @$field['group_class'] }}"--}}
                    {{--                                         id="form-group-{{ $field['name'] }}">--}}
                    {{--                                        @if($field['type'] == 'custom')--}}
                    {{--                                            @include($field['field'], ['field' => $field])--}}
                    {{--                                        @else--}}
                    {{--                                            <label for="{{ $field['name'] }}">{{ @$field['label'] }} @if(strpos(@$field['class'], 'require') !== false)--}}
                    {{--                                                    <span class="color_btd">*</span>@endif</label>--}}
                    {{--                                            <div class="col-xs-12">--}}
                    {{--                                                @include(config('core.admin_theme').".form.fields.".$field['type'], ['field' => $field])--}}
                    {{--                                                <span class="form-text text-muted">{!! @$field['des'] !!}</span>--}}
                    {{--                                                <span class="text-danger">{{ $errors->first($field['name']) }}</span>--}}
                    {{--                                            </div>--}}
                    {{--                                        @endif--}}
                    {{--                                    </div>--}}
                    {{--                                @endforeach--}}
                    {{--                            </div>--}}
                    {{--                        </div>--}}
                    {{--                    </div>--}}
                    <div class="kt-form">
                        <div class="kt-portlet__body">
                            <div class="kt-section kt-section--first">
                                @foreach($module['form']['general_tab'] as $field)
                                    <div class="form-group-div form-group {{ @$field['group_class'] }}"
                                         id="form-group-{{ $field['name'] }}">
                                        @if($field['type'] == 'custom')
                                            @include($field['field'], ['field' => $field])
                                        @else

                                            <label for="{{ $field['name'] }}">{{ __(@$field['label']) }}
                                                @if(strpos(@$field['class'], 'require') !== false)
                                                    <span class="color_btd">*</span>
                                                @endif
                                            </label>
                                            <div class="col-xs-12">
                                                @if($field['name'] == 'category_id')
                                                    {{--                                                    @include("admin.form.fields.".$field['type'], ['field' => $field])--}}
                                                    @php
                                                        $data = App\Http\Helpers\CommonHelper::getCate();
                                                    @endphp
                                                    <select name="{{$field['name']}}" class="form-control" id="mySelect"
                                                            style="overflow-y: scroll;">
                                                        <option value="">{{__('lang.select_category')}}</option>
                                                        @foreach($data as $item)
                                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                                        @endforeach
                                                    </select>
                                                    <script>
                                                        $(document).ready(function () {
                                                            $('#mySelect').select2();
                                                        });
                                                    </script>
                                                @else
                                                    @include(config('core.admin_theme').".form.fields.".$field['type'], ['field' => $field])
                                                @endif

                                                <span class="form-text text-muted">{!! @$field['des'] !!}</span>
                                                <span class="text-danger">{{ $errors->first($field['name']) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    @if($field['name'] == 'order_no')
                                        <div class="form-group-div form-group"
                                             id="form-group-iframe">
                                            <label for="iframe"
                                                   style="font-size: 1.2rem; font-weight: bold; color: #48465b;">
                                                {{__('lang.product_classification')}}</label>
                                            <input name="product_attribute_id" id="product_attribute_id"
                                                   value="{{ time() }}" type="hidden">
                                            <iframe id="iframe" src="/admin/product_attributes?product_id={{ time() }}"
                                                    style="min-height: 280px; width: 100%;"></iframe>
                                            <script>
                                                document.addEventListener('DOMContentLoaded', function () {
                                                    const iframeSrc = document.getElementById('iframe').getAttribute('src');
                                                    const regex = /product_id=(\d+)/;
                                                    const match = regex.exec(iframeSrc);
                                                    if (match) {
                                                        const product_id = match[1];
                                                        document.getElementById('product_attribute_id').value = product_id;
                                                    }
                                                });
                                            </script>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>


                    <!--end::Form-->
                </div>

                <!--end::Portlet-->
                {{--                <div class="kt-portlet">--}}
                {{--                    <div class="kt-form">--}}
                {{--                        <div class="kt-portlet__body">--}}
                {{--                            <div class="kt-section kt-section--first">--}}
                {{--                                <div class="form-group-div form-group"--}}
                {{--                                     id="form-group-{{ $field['name'] }}">--}}
                {{--                                    <label for="{{ $field['name'] }}" style="font-size: 1.2rem;font-weight: bold;color: #48465b;">Thuộc tính sản phẩm</label>--}}
                {{--                                    <input name="product_attribute_id" id="product_attribute_id" value="{{ time() }}" type="hidden">--}}
                {{--                                    <iframe id="iframe" src="/admin/product_attributes?product_id={{ time() }}"--}}
                {{--                                            style="min-height: 550px; width: 100%;"></iframe>--}}
                {{--                                    <script>--}}
                {{--                                        document.addEventListener('DOMContentLoaded', function() {--}}
                {{--                                            const iframeSrc = document.getElementById('iframe').getAttribute('src');--}}
                {{--                                            const regex = /product_id=-(\d+)/;--}}
                {{--                                            const match = regex.exec(iframeSrc);--}}
                {{--                                            if (match) {--}}
                {{--                                                const product_id = match[1];--}}
                {{--                                                document.getElementById('product_attribute_id').value = product_id;--}}
                {{--                                            }--}}
                {{--                                        });--}}
                {{--                                    </script>--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
            </div>
            <script>
                document.getElementById('iframe').onload = function () {
                    const iframe = document.getElementById('iframe').contentWindow;
                    const productAttributeId = iframe.document.querySelector('input[name="product_attribute_id"]').value;
                    document.getElementById('product_attribute_id').value = productAttributeId;
                }
            </script>
            <div class="col-xs-12 col-md-4">
                <!--begin::Portlet-->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                {{__('lang.image')}}
                            </h3>
                        </div>
                    </div>
                    <!--begin::Form-->
                    <div class="kt-form">
                        <div class="kt-portlet__body">
                            <div class="kt-section kt-section--first">
                                @foreach($module['form']['image_tab'] as $field)
                                    @php
                                        $field['value'] = @$result->{$field['name']};
                                    @endphp
                                    <div class="form-group-div form-group {{ @$field['group_class'] }}"
                                         id="form-group-{{ $field['name'] }}">
                                        @if($field['type'] == 'custom')
                                            @include($field['field'], ['field' => $field])
                                        @else
                                            <label for="{{ $field['name'] }}">{{ __(@$field['label']) }} @if(strpos(@$field['class'], 'require') !== false)
                                                    <span class="color_btd">*</span>
                                                @endif</label>
                                            <div class="col-xs-12">
                                                @include(config('core.admin_theme').".form.fields.".$field['type'], ['field' => $field])
                                                <span class="form-text text-muted">{!! @$field['des'] !!}</span>
                                                <span class="text-danger">{{ $errors->first($field['name']) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                                @if(in_array($module['code'].'_add', $permissions))
                                    <div class="button-mobie text-center">
                                        <button class="btn btn-primary">{{__('lang.Edit_the_product_table')}}</button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!--end::Form-->
                </div>
                <!--end::Portlet-->
            </div>

        </div>

        <div class="kt-form">
            <div class="kt-portlet__body">
                <div class="kt-section kt-section--first">
                    @foreach($module['form']['seo_tab'] as $field)
                        @php
                            $field['value'] = @$result->{$field['name']};
                        @endphp
                        <div class="form-group-div form-group {{ @$field['group_class'] }}"
                             id="form-group-{{ $field['name'] }}">
                            @if($field['type'] == 'custom')
                                @include($field['field'], ['field' => $field])
                            @else
                                <label for="{{ $field['name'] }}">{{ __(@$field['label']) }} @if(strpos(@$field['class'], 'require') !== false)
                                        <span class="color_btd">*</span>
                                    @endif</label>
                                <div class="col-xs-12">
                                    @include(config('core.admin_theme').".form.fields.".$field['type'], ['field' => $field])
                                    <span class="form-text text-muted">{!! @$field['des'] !!}</span>
                                    <span class="text-danger">{{ $errors->first($field['name']) }}</span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                    @if(in_array($module['code'].'_add', $permissions))
                        <div class="button-mobie text-center">
                            <button class="btn btn-primary">{{__('lang.Edit_the_product_table')}}</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>



        <div class="kt-form">
            <div class="kt-portlet__body">
                <div class="kt-section kt-section--first">
                    @foreach($module['form']['variant_tab'] as $field)
                        @php
                            $field['value'] = @$result->{$field['name']};
                        @endphp
                        <div class="form-group-div form-group {{ @$field['group_class'] }}"
                             id="form-group-{{ $field['name'] }}">
                            @if($field['type'] == 'custom')
                                @include($field['field'], ['field' => $field])
                            @else
                                <label for="{{ $field['name'] }}">{{ __(@$field['label']) }} @if(strpos(@$field['class'], 'require') !== false)
                                        <span class="color_btd">*</span>
                                    @endif</label>
                                <div class="col-xs-12">
                                    @include(config('core.admin_theme').".form.fields.".$field['type'], ['field' => $field])
                                    <span class="form-text text-muted">{!! @$field['des'] !!}</span>
                                    <span class="text-danger">{{ $errors->first($field['name']) }}</span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                    @if(in_array($module['code'].'_add', $permissions))
                        <div class="button-mobie text-center">
                            <button class="btn btn-primary">{{__('lang.Edit_the_product_table')}}</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
      
    </form>
    <script>
        const cases = document.getElementById('cases');
        const bottles = document.getElementById('bottles');
        const quantity = document.getElementById('quantity');

        cases.addEventListener('input', function () {
            bottles.addEventListener('input', function () {
                quantity.value = (cases.value) * (bottles.value);
            });
        });
        bottles.addEventListener('input', function () {
            cases.addEventListener('input', function () {
                quantity.value = (cases.value) * (bottles.value);
            });
        });
    </script>
@endsection
@section('custom_head')
    <link type="text/css" rel="stylesheet" charset="UTF-8"
          href="{{ asset(config('core.admin_asset').'/css/form.css') }}">
    <script src="{{asset('ckeditor/ckeditor.js') }}"></script>
    <script src="{{asset('ckfinder/ckfinder.js') }}"></script>
    <script src="{{asset('libs/file-manager.js') }}"></script>
    <style>
        .kt-radio-list {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .button-mobie {
            display: none;
        }

        .form-group {
            margin-bottom: 4px !important;
        }

        @media (max-width: 435px) {
            .dropzone .dz-preview .dz-image {
                width: 80px;
                height: 80px;
            }

            .button-mobie {
                display: block;
            }

            .dropzone .dz-preview {
                margin: 10px !important;
            }

            .dropzone.dropzone-default {
                padding: 10px;

            }

        }
    </style>
    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>--}}
@endsection
@section('custom_footer')
    <script src="{{ asset(config('core.admin_asset').'/js/pages/crud/metronic-datatable/advanced/vertical.js') }}"
            type="text/javascript"></script>


    <script type="text/javascript" src="{{ asset('tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('tinymce/tinymce_editor.js') }}"></script>
    <script type="text/javascript">
        editor_config.selector = ".editor";
        editor_config.path_absolute = "{{ (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]" }}/";
        tinymce.init(editor_config);
    </script>
    <script type="text/javascript" src="{{ asset(config('core.admin_asset').'/js/form.js') }}"></script>

@endsection
@push('scripts')
    <script>
        $(document).ready(function () {
            // Auto render slug
            $('input[name=name]').keyup(function () {
                var str = $(this).val();
                str = str.toLowerCase();
                str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
                str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
                str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
                str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
                str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
                str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
                str = str.replace(/đ/g, "d");
                str = str.replace(/!|@|\$|%|\^|\*|\(|\)|\+|\=|\<|\>|\?|\/|,|\.|\:| |\&|~/g, "-");
                str = str.replace(/-+-/g, "-"); //thay thế 2- thành 1-
                str = str.replace(/^\-+|\-+$/g, "");//cắt bỏ ký tự - ở đầu và cuối chuỗi


                $('input[name=slug]').val(str);
                $('input[name=url]').val(str);
            });
        });

        $('.save_editor').click(function () {
            var action = $(this).data('action');
            $('input[name=return_direct]').val(action);
            $('form.kt-container').submit();
        });
    </script>
@endpush
