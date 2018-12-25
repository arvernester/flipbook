@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">

      <div class="card">
        <form action="{{ route('media.update', $medium) }}" enctype="multipart/form-data" method="post">
          <div class="card-header">@lang('Edit Book')</div>

          <div class="card-body">
              <h2 class="card-title">@lang('Book Information')</h2>
              {{ method_field('put') }}
              {{ csrf_field() }}

              <div class="form-group">
                <label for="title">@lang('Title')</label>
                <input name="title" type="text" value="{{ old('title', $medium->title) }}" class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}">
                <div class="invalid-feedback">{{ $errors->first('title') }}</div>
              </div>

              <div class="form-group">
                <label for="description">@lang('Description')</label>
              <textarea name="description" class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" rows="5">{{ old('description', $medium->description) }}</textarea>
                <div class="invalid-feedback">{{ $errors->first('description') }}</div>
              </div>

              <div class="form-group">
                <label for="pdf">@lang('Image Cover')</label>
                <input name="image" type="file" class="form-control-file is-invalid">
                <div class="invalid-feedback">{{ $errors->first('image') }}</div>
              </div>

              <div class="form-group">
                <label for="pdf">@lang('PDF File')</label>
                <input name="file" type="file" class="form-control-file is-invalid">
                <div class="invalid-feedback">{{ $errors->first('file') }}</div>
              </div>

              <hr>

              <h2 class="card-title">@lang('Table of Contents')</h2>

              <book-page medium="{{ $medium->id }}"></book-page>
          </div>

          <div class="card-footer">
            <button class="btn btn-primary" type="submit">@lang('Update')</button>
            <button id="delete-button" class="btn btn-danger" type="submit">@lang('Delete')</button>
          </div>
        </form>

        <form id="delete-form" action="{{ route('media.destroy', $medium) }}" method="post">
          {{ method_field('delete') }}
          {{ csrf_field() }}
        </form>
      </div>

    </div>

    <div class="col-md-4">
      <div class="card">
        <div class="card-header">@lang('Image Cover')</div>
        <div class="card-body text-center">
          <img src="{{ $medium->image_full_path }}" alt="{{ $medium->title }}" class="img-thumbnail">
          
          <div class="mt-4">
              <a href="{{ route('media.show', $medium) }}" class="btn btn-primary" target="_blank">@lang('Read')</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection