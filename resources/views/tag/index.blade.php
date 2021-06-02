@extends('layouts.layout')

@section('content')

<div class="push-top push-bottom"><a href="{{ route('tags.create')}}" class="btn btn-primary btn-sm"">Create</a></div>
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
        @foreach($tags as $tag)
        <tr>
            <td>{{$tag->id}}</td>
            <td>{{$tag->name}}</td>
            <td class="text-center">
                <a href="{{ route('tags.edit', $tag->id)}}" class="btn btn-primary btn-sm"">Edit</a>
                <a href="{{ route('tags.show', $tag->id)}}" class="btn btn-primary btn-sm"">View</a>
                <form action="{{ route('tags.destroy', $tag->id)}}" method="post" style="display: inline-block">
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