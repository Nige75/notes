@extends('layouts.layout')

@section('content')

<div class="push-top push-bottom"><a href="{{ route('tags.index')}}" class="btn btn-primary btn-sm"">Back</a></div>
<div class="card">
  <div class="card-header">
    Edit & Update
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
      <form method="post" action="{{ route('tags.update', $tag->id) }}">
          <div class="form-group">
              @csrf
              @method('PATCH')
              <label for="name">Name</label>
              <input type="text" class="form-control" name="name" value="{{ $tag->name }}"/>
          </div>
        

          <button type="submit" class="btn btn-block btn-danger">Update Tag</button>
      </form>
  </div>
</div>
@endsection