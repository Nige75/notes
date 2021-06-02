@extends('layouts.layout')

@section('content')

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
      <form method="post" action="{{ route('notes.update', $note->id) }}" enctype="multipart/form-data">
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

          <div class="form-group">
            <label for="tags">Attachments</label>
            <ul class="no-bullets">
          @foreach ($note->attachments as $attachment)
          <li>
            {{ $attachment->name }} Remove? <input type="checkbox" name="removeAttachments[]" value="{{ $attachment->id }}" >
          </li>
          @endforeach
            </ul>
        </div>

          <div class="input-group hdtuto control-group lst increment push-bottom " >
      
            <div class="input-group-btn"> 
      
              <button class="btn btn-success add-file-ui" type="button"><i class="fldemo glyphicon glyphicon-plus"></i>Add File</button>
      
            </div>
      
          </div>
      
          <div class="clone hide">
      
            <div class="hdtuto control-group lst input-group" style="margin-top:10px">
      
              <input type="file" name="filenames[]" class="myfrm form-control">
      
              <div class="input-group-btn"> 
      
                <button class="btn btn-danger remove-file-ui" type="button"><i class="fldemo glyphicon glyphicon-remove"></i> Remove</button>
      
              </div>
      
            </div>
      
          </div>

          <button type="submit" class="btn btn-block btn-danger">Update Note</button>
      </form>
  </div>
</div>
@endsection