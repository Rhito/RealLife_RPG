@extends('frontend.layout.layout')

@section('content')
    <div class="app-container app-center"><h1 class="classification-title"> Category </h1>
        <div style="min-width: 1200px;">
            <div>
                <div class="el-row" style="margin-left: -10px; margin-right: -10px;">
                    @foreach($data as $item)

{{--                       {{dd($item)}}--}}
                        <div class="el-col el-col-24 el-col-xs-24 el-col-sm-12 el-col-md-12 el-col-lg-12 el-col-xl-12"
                             style="padding-left: 10px; padding-right: 10px;">
                            <a href="{{ route('products', ['category' => $item->id]) }}" style="display:block;">
                                <div class="classification-item el-row is-align-middle el-row--flex"
                                     style="margin-left: -5px; margin-right: -5px;">
                                    <div class="el-col el-col-8" style="padding-left: 5px; padding-right: 5px;">
                                        <div class="cla-img"><img src="{{ CommonHelper::getUrlImageThumb($item->image) }}" alt=""></div>
                                    </div>
                                    <div class="el-col el-col-15" style="padding-left: 5px; padding-right: 5px;">
                                        <div class="classification-item-text"><h2>{{$item->name}}</h2>
                                            {!! $item->content  !!}
                                        </div>
                                    </div>
                                </div>
                            </a>

                        </div>
                    @endforeach
                </div><!---->
            </div>
        </div>
    </div>
@endsection
{{--@extends('frontend.layout.layout')--}}

{{--@section('content')--}}
{{--    <div class="app-container app-center">--}}
{{--        <h1 class="classification-title"> Category </h1>--}}
{{--        <div style="min-width: 1200px;">--}}
{{--            <div>--}}
{{--                <div class="el-row" style="margin-left: -10px; margin-right: -10px;">--}}
{{--                    @foreach($data as $item)--}}
{{--                        <div class="el-col el-col-24 el-col-xs-24 el-col-sm-12 el-col-md-12 el-col-lg-12 el-col-xl-12"--}}
{{--                             style="padding-left: 10px; padding-right: 10px;">--}}
{{--                            <a href="{{ route('products.index', ['category' => $item->id]) }}">--}}
{{--                                <div class="classification-item el-row is-align-middle el-row--flex"--}}
{{--                                     style="margin-left: -5px; margin-right: -5px;">--}}
{{--                                    <div class="el-col el-col-8" style="padding-left: 5px; padding-right: 5px;">--}}
{{--                                        <div class="cla-img"><img--}}
{{--                                                    src="{{ CommonHelper::getUrlImageThumb($item->image) }}"--}}
{{--                                                    alt=""></div>--}}
{{--                                    </div>--}}
{{--                                    <div class="el-col el-col-15" style="padding-left: 5px; padding-right: 5px;">--}}
{{--                                        <div class="classification-item-text"><h2>{{$item->name}}</h2>--}}
{{--                                            {!! $item->content  !!}--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </a>--}}
{{--                        </div>--}}
{{--                    @endforeach--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--@endsection--}}
