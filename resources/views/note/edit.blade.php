@extends('layouts.layout')

@section('content')

<style>
    .container {
      max-width: 450px;
    }
    .push-top {
      margin-top: 50px;
    }
    .push-bottom {
        padding-bottom: 5px;
    }
</style>

<div class="push-top push-bottom"><a href="{{ route('notes.index')}}" class="btn btn-primary btn-sm"">Back</a></div>
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
      <form method="post" action="{{ route('notes.update', $note->id) }}">
          <div class="form-group">
              @csrf
              @method('PATCH')
              <label for="name">Name</label>
              <input type="text" class="form-control" name="name" value="{{ $note->name }}"/>
          </div>
          <div class="form-group">
              <label for="note">Note</label>
              <textarea class="form-control" name="note">{{ $note->note }}</textarea>
          </div>
          @foreach ($tags as $tag)
          <div class="checkbox">
            <label for="tags">{{ $tag->name }}</label>
            <input type="checkbox" name="tags[]" value="{{ $tag->id }}" 
            @if(in_array($tag->id,$tagids))
                checked
            @endif>
          </div>
          @endforeach
          <button type="submit" class="btn btn-block btn-danger">Update Note</button>
      </form>
  </div>
</div>
@endsection