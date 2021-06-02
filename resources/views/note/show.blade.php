@extends('layouts.layout')

@section('content')

<div class="push-top push-bottom"><a href="{{ route('notes.index')}}" class="btn btn-primary btn-sm"">Back</a></div>
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
              <input type="text" class="form-control" name="name" value="{{ $note->name }}"/>
          </div>
          <div class="form-group">
              <label for="note">Note</label>
              <textarea class="form-control" name="note">{{ $note->note }}</textarea>
          </div>
          <div class="form-group">
            <label for="tags">Tags</label>
            <ul class="no-bullets">
          @foreach ($note->tags as $tag)
          <li>
            {{ $tag->name }}
          </li>
          @endforeach
            </ul>
          </div>
          <div class="form-group">
              <label for="tags">Attachments</label>
              <ul class="no-bullets">
            @foreach ($note->attachments as $attachment)
            <li>
              {{ $attachment->name }} <a href="{{ route('notes.download', $attachment->id)}}" class="btn btn-primary btn-sm"">Download</a>
            </li>
            @endforeach
              </ul>
          </div>

      </div>
  </div>
</div>
@endsection