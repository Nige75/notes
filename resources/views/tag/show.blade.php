@extends('layouts.layout')

@section('content')

<div class="push-top push-bottom"><a href="{{ route('tags.index')}}" class="btn btn-primary btn-sm"">Back</a></div>
<div class="card">
  <div class="card-header">
    View
  </div>

  <div class="card-body">
    @if ($errors->any())
      <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
        </ul>
      </div><br />
    @endif
      <div>
          <div class="form-group">
              <label for="name">Name</label>
              <input type="text" class="form-control" name="name" value="{{ $tag->name }}"/>
          </div>

      </div>
  </div>
</div>
@endsection