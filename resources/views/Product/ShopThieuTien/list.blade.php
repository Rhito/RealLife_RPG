@extends(config('core.admin_theme').'.template')

@section('main')

      <div class="container">
  <h2>{{__('lang.List_of_Shops_with_Insufficient_Funds')}}</h2>

  <table class="table table-bordered table-striped">
      <thead>
          <tr>
              <th>#</th>
              <th>{{__('lang.shop_name')}}</th>
              <th>{{__('lang.Shop_owner')}}</th>
              <th>{{__('lang.email')}}</th>
              <th>{{__('lang.so_dien_thoai')}}</th>
              <th>{{__('lang.Tinh_trang_thanh_toan')}}</th>

              <th>{{__('lang.Created_at')}}</th>
              <th>{{__('lang.Action')}}</th>
          </tr>
      </thead>
      <tbody>
          @forelse($shop as $index => $item)
              <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $item->ten_cua_hang ?? __('lang.The_shop_hasn’t_been_named_yet') }}</td>
                  <td>{{ $item->name }}</td>
                  <td>{{ $item->email }}</td>
                  <td>{{ $item->tel }}</td>
                  <td>{{ __("lang.{$item->trang_thai_tien}") }}</td>
                  <td>{{ $item->created_at }}</td>
                  <td>
                     <button class="btn btn-primary" >
                         <a href="{{ route('shop.update' ,$item->id) }}" style="color: white">{{__('lang.edit')}}</a>
                     </button>
                  </td>
              </tr>
          @empty
              <tr>
                  <td colspan="5" class="text-center">{{__('lang.No_shop_in_debt')}}</td>
              </tr>
          @endforelse
      </tbody>
  </table>
</div>

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
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