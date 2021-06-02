@extends('layouts.layout')

@section('content')


<div class="push-top push-bottom"><a href="{{ route('tags.index')}}" class="btn btn-primary btn-sm"">Back</a></div>
<div class="card">
  <div class="card-header">
    Add Tag
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
      <form method="post" action="{{ route('tags.store') }}">
          <div class="form-group">
              @csrf
              <label for="name">Name</label>
              <input type="text" class="form-control" name="name"/>
          </div>
          
          <button type="submit" class="btn btn-block btn-danger">Create Tag</button>
      </form>

  </div>
</div>
@endsection