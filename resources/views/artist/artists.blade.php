@extends('layouts.base')
@extends('layouts.app')
@section('body')
  <div class="container">
    <br />
    @if ( Session::has('success'))
      <div class="alert alert-success">
        <p>{{ Session::get('success') }}</p>
      </div><br />
     @endif
     {{-- //! import excel file --}}
     <div class="col-xs-6">
      <form method="post" enctype="multipart/form-data" action="{{ url('/artist/import') }}">
         @csrf
         <input type="file" id="uploadName" name="artist_upload" required>
     </div>

       @error('artist_upload')
         <small>{{ $message }}</small>
       @enderror
            <button type="submit" class="btn btn-info btn-primary " >Import Excel File</button>
            </form> 
  </div>
  <a href="#" data-toggle="modal" data-target="#artistModal" class="btn btn-primary a-btn-slide-text">
    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
    <span><strong>Add new artist</strong></span>            
</a>
 {{-- <div><a href="#" data-toggle="modal" data-target="#artistModal"><strong>Add New Artist</strong></a></div> --}}
 {{-- =============================== --}}
 {{-- <div>
  <button type="button" class="btn btn-sm" data-toggle="modal" data-target="#artistModal"><strong>Add new Artist</strong></button>
</div> --}}
</div>
  <div>
    {{$html->table(['class' => 'table table-bordered table-striped table-hover '], true)}}
  </div>
{{-- <button type="button" class="btn btn-sm" data-toggle="modal" data-target="#artistModal"> --}}
<div class="modal" id="artistModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document" style="width:75%;">
      <div class="modal-content">
<div class="modal-header text-center">
          <p class="modal-title w-100 font-weight-bold">Add New Artist</p>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form  method="POST" action="{{url('artist')}}">
        {{csrf_field()}}
<div class="modal-body mx-3" id="inputfacultyModal">
          <div class="md-form mb-5">
            <i class="fas fa-user prefix grey-text"></i>
            <label data-error="wrong" data-success="right" for="name" style="display: inline-block;
          width: 150px; ">Artist Name</label>
            <input type="text" id="artist_name" class="form-control validate" name="artist_name">
          </div>
<div class="modal-footer d-flex justify-content-center">
            <button type="submit" class="btn btn-success">Save</button>
            <button class="btn btn-light" data-dismiss="modal">Cancel</button>
            {{-- <button class="btn btn-light" data-dismiss="modal">Cancel</button> --}}
          </div>
        </form>
      </div>
    </div>
  </div>
  @push('scripts')
    {{$html->scripts()}}
  @endpush
@endsection