@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-12">

      <div class="card">
        <form action="{{ route('media.store') }}" enctype="multipart/form-data" method="post">
          <div class="card-header">@lang('Upload PDF file')</div>

          <div class="card-body">
              {{ csrf_field() }}

              <div class="form-group">
                <label for="title">@lang('Title')</label>
                <input name="title" type="text" class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}">
                <div class="invalid-feedback">{{ $errors->first('title') }}</div>
              </div>

              <div class="form-group">
                <label for="description">@lang('Description')</label>
                <textarea name="description" class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" rows="5"></textarea>
                <div class="invalid-feedback">{{ $errors->first('title') }}</div>
              </div>

              <div class="form-group">
                <label for="pdf">@lang('PDF File')</label>
                <input name="medium" type="file" class="form-control-file is-invalid">
                <div class="invalid-feedback">{{ $errors->first('medium') }}</div>
              </div>
          </div>

          <div class="card-footer">
            <button class="btn btn-primary" type="submit">@lang('Upload')</button>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>
@endsection