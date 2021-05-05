@extends('layouts.layout')

@section('content')

<style>
    .push-top {
        margin-top: 50px;
    }
    .push-bottom {
      padding-bottom: 5px;
    }
</style>

<div class="push-top push-bottom"><a href="{{ route('notes.create')}}" class="btn btn-primary btn-sm"">Create</a></div>
<div>
  @if(session()->get('success'))
    <div class="alert alert-success">
      {{ session()->get('success') }}  
    </div><br />
  @endif
  <table class="table">
    <thead>
        <tr class="table-warning">
          <td>ID</td>
          <td>Name</td>
          <td class="text-center">Action</td>
        </tr>
    </thead>
    <tbody>
        @foreach($notes as $note)
        <tr>
            <td>{{$note->id}}</td>
            <td>{{$note->name}}</td>
            <td class="text-center">
                <a href="{{ route('notes.edit', $note->id)}}" class="btn btn-primary btn-sm"">Edit</a>
                <a href="{{ route('notes.show', $note->id)}}" class="btn btn-primary btn-sm"">View</a>
                <form action="{{ route('notes.destroy', $note->id)}}" method="post" style="display: inline-block">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm"" type="submit">Delete</button>
                  </form>
            </td>
        </tr>
        @endforeach
    </tbody>
  </table>
<div>
@endsection