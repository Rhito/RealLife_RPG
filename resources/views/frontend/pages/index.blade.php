@extends('frontend.layout.layout')

@section('title', 'Bài viết - Tin tức')

@section('content')
    <div class="page-bai-viet py-4 px-40">
        <div class="post-wrapper">
            @if($posts->count() > 0)
                @foreach($posts as $post)
                    <article class="mb-4 pb-3 border-bottom">
                        <h2 class="h4 mb-3 text-primary">{{ $post->translated_title }}</h2>
                        <div class="post-content">
                            {!! $post->translated_content ?? $post->content !!}
                        </div>
                    </article>
                @endforeach
            @else
                <div class="alert alert-info text-center">
                    Chưa có bài viết nào.
                </div>
            @endif
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Bọc nội dung và co 2 bên theo px */
        .page-bai-viet .post-wrapper {
            max-width: 900px;   /* độ rộng tối đa */
            margin: 0 auto;     /* căn giữa */
            padding: 0 20px;    /* cách mép màn hình */
        }

        /* Bỏ min-height 100vh từ layout */
        .page-bai-viet {
            min-height: auto !important;
            height: auto !important;
        }

        /* Nội dung bài viết */
        .page-bai-viet .post-content {
            font-family: Arial, sans-serif;
            font-size: 15px;
            line-height: 1.7;
            color: #333;
            white-space: pre-line;
        }
    </style>
@endpush
