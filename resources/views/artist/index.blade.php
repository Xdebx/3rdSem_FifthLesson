@extends('layouts.base')
@extends('layouts.app')
@section('content')
<div class="container">
    <br/>
    @if ( Session::has('success'))
      <div class="alert alert-success">
        <p>{{ Session::get('success') }}</p>
      </div><br/>
     @endif
     {{-- <form method="Get" action="{{url('artist')}}" >
       <div class="form-group col-md-4">
            <label for="genre">Search</label>
            <input type="text" class="form-control" name="search" id="genre" Placeholder="Search Artist name">
    </div>
     </form> --}}
      @include('partials.search')
    <table class="table table-striped">
      <tr>{{link_to_route('artist.create', 'Add new artist:')}}</tr>
     
<thead>
      <tr>
        <th>Artist ID</th>
        <th>Artist Name</th>
        <th>Artist Image</th>
        <th>Album Name</th>
        <th colspan="1">Action</th>
        <th colspan="1">Action</th>
      </tr>
    </thead>
 <tbody>
   @foreach($artists as $artist)
      
      <tr>
        <td>{{$artist->id}}</td>
        <td>{{$artist->artist_name}}</td>
        <td><img src="{{asset($artist->img_path)}}" width="80" height="80"></td> 
        <td>
        @foreach($artist->albums as $album)
          <li>{{$album->album_name}}:{{$album->genre}}</li>  
        @endforeach
        </td>
        </td>
{{-- <td><a href = "{{ route('artist.show', $artist->id ) }}"  class="btn btn-warning">show</a></td> --}}
        {{-- <td> --}}
        <td><a href="{{ action('ArtistController@edit', $artist->id)}}" class="btn btn-warning">Edit</a></td>
        <td>
<form action="{{ action('ArtistController@destroy', $artist->id)}}" method="post">
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





