@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-md-12">
          <h1 class="title">@lang('Books Collection')</h1>
          <hr>
      </div>
    </div>

    <div class="row justify-content-center">
      <div class="show-book"></div>
      <div class="col-md-12" id="book-container"></div>
    </div>

    <div class="row" id="books">
      @foreach ($media as $medium)
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-img-top">
                  <img src="{{ $medium->image_full_path }}" alt="{{ $medium->title }}" class="img-responsive" style="width:100%; height: auto">
                </div>
                <div class="card-body text-center">
                  <div class="card-title">
                    <h2>{{ $medium->title }}</h2>
                  </div>
                  <p>{{ str_limit($medium->description, 200)}}</p>
      
                <a href="#" data-file="{{ $medium->full_path }}" class="btn btn-primary read-book" data-title="{{ $medium->title }}" data-id="{{ $medium->id }}">@lang('Read')</a>
                  <a href="{{ route('media.edit', $medium) }}" class="btn btn-default">@lang('Edit')</a>
                </div>
              </div>
        </div>
      @endforeach
    </div>
  </div>
@endsection

@push('css')
  <link rel="stylesheet" href="{{ asset('wow_book/wow_book.css') }}">
@endpush

@push('js')
  <script src="{{ asset('wow_book/wow_book.min.js') }}"></script>
  <script src="{{ asset('wow_book/pdf.combined.min.js') }}"></script>
@endpush

@push('js')
  <script>
    $(function(){

      $('#book-container').wowBook({
        height: 500,
        widht: 800,

        container: false,

        toolbarPosition: 'bottom',
        toolbar : 'lastLeft, left, right, lastRight, toc, zoomin, zoomout, slideshow, flipsound, fullscreen, thumbnails, download',
        thumbnailsPosition: 'left',

        lightbox: '.read-book'
      })
      var book = $.wowBook('#book-container')

      $('.read-book').click(function() {
        var path = $(this).data('file')
        var id = $(this).data('id')

        book.showLightbox()

        $.get(`/media/pages/${id}?preformat=true`, function(data){
          book.createToc(data)
        })
        
        book.setPDF(path)
      })
    })
  </script>
@endpush