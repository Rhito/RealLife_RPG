{{--@if(in_array('bao_cao_dan_khach_add', $permissions) || in_array('super_admin', $permissions))--}}
{{--    <a href="/admin/bao_cao_dan_khach/add?code_id={{ $item->id }}" style="padding: 5px; border: 1px solid #ccc;">Báo cáo--}}
{{--        dẫn khách</a>--}}
{{--@endif--}}
<style>
    .hanh-dong{
        max-width: 80px;
        display: flex;
        flex-wrap: wrap;
        /*margin-top: 2px;*/

    }
    .hanh-dong span{
        background-color: transparent!important;



        /*padding: 4px;*/
    }
    .hanh-dong span a{
        text-decoration: none;
        /*color: #fff;*/
        font-weight: bold;
        font-size: 14px;
    }
    .hanh-dong .edit{
        /*background-color: #5d80f9;*/

        color: #5d80f9;
        margin-right: 20px;
        /*margin-bottom: 10px;*/

    }

    .hanh-dong .edit a{
        color: #1db2ff;

    }
    .hanh-dong .trash a{
             color: #eb3349;


         }
    .hanh-dong .trash{
        color: #eb3349;
    }
    .hanh-dong span{
        /*background-color: #1db2ff;*/
    }
</style>
<div style="height: auto;" class="hanh-dong">
    @if((in_array($module['code'] . '_edit', $permissions)&& $item->admin_id == \Auth::guard('admin')->user()->id) || in_array('super_admin', $permissions) )
        <span class="edit"><a
                    href="{{ url('/admin/'.$module['code'].'/edit/' . $item->id) }}"
                    title="{{__('lang.Edit_this_record')}}"> {{__('lang.edit')}}</a>  </span>
    @endif
    @if(in_array($module['code'] . '_delete', $permissions) || in_array('super_admin', $permissions))
        <span class="trash"><a class="delete-warning"
                               href="{{ url('/admin/'.$module['code'].'/delete/' . $item->id) }}"
                               title="{{__('lang.Delete_this_record')}}">{{__('lang.delete')}}</a></span>
    @endif
</div>

