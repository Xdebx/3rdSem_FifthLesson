@extends('layouts.base')
@extends('layouts.app')
@section('content')
<div class="container">
    <br />
    @if ( Session::has('success'))
      <div class="alert alert-success">
        <p>{{ Session::get('success') }}</p>
      </div><br />
     @endif
     {{-- <form method="GET" action="{{url('artist')}}" >
      <div class="form-group col-md-4">
       <label for="genre">Search</label>
       <input type="text" class="form-control" name="search" id="genre" Placeholder="Search Listener name or Album name">
    </div>
    </form> --}}
      @include('partials.search')
    <table class="table table-striped">
      <tr>{{ link_to_route('listener.create', 'Add new listener:')}}</tr>
      <thead>
      <tr>
        <th>listener ID</th>
        <th>listener Name</th>
        <th>Albums</th>
        <th colspan="1">Action</th>
        <th colspan="1">Action</th>
      </tr>
    </thead>
    <tbody>
      @foreach($listeners as $listener)
        <td>{{$listener->id}}</td>
        <td>{{$listener->listener_name}}</td>
        <td> @foreach($listener->albums as $album)
             <li>{{$album->album_name}} </li>   
        @endforeach
        </td>

        <td><a href="{{action('ListenerController@edit', $listener->id)}}" class="btn btn-warning">Edit</a></td>
       <td>
          <form action=" {{action('ListenerController@destroy', $listener->id)}}" method="post">
           {{ csrf_field() }}
            <input name="_method" type="hidden" value="DELETE">
            <button class="btn btn-danger" type="submit">Delete</button>
          </form>

          </td>
      </tr>
      @endforeach
  </tbody>
  </table>
  </div>
  @endsection