@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row" id="books">
      @foreach ($media as $medium)
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">{{ $medium->title }}</div>
                <div class="card-body">
                  <p>{{ str_limit($medium->description, 200)}}</p>
      
                  <a href="{{ route('media.show', $medium) }}" class="btn btn-primary">@lang('Show')</a>
                </div>
              </div>
        </div>
      @endforeach
    </div>
  </div>
@endsection