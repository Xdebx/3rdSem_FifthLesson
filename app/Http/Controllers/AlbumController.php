<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Illuminate\Http\Request;
use \App\Models\Artist;
use View;
use Redirect;
use App\DataTables\AlbumsDataTable;
use DataTables;

class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {
        //! ================Newcode index with search bar==================
        // ! need sa parameter ng request for searching
            if (empty($request->get('search'))) 
        {
            $albums = Album::with('artist','listeners')->get();
        }
            else 
        {
             $albums = Album::with(['artist' =>function($q) use($request){
                $q->where("artist_name","LIKE", "%".$request->get('search')."%");
            }])->get();

             $albums = Album::whereHas('artist', function($q) use($request) {
                $q->where("artist_name","LIKE", "%".$request->get('search')."%");
                })->orWhereHas('listeners', function($q) use($request){
                  $q->where("listener_name","LIKE", "%".$request->get('search')."%");
                })->orWhere('album_name',"LIKE", "%".$request->get('search')."%")
                ->get();
            }
            $url = 'album';
            return View::make('album.index',compact('albums','url'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $artists= Artist::all();
     //dd($artists);
        return View::make('album.create',compact('artists'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //! ==========New Method============
        $artist = Artist::find($request->artist_id);
        // dd($artist);
        $album = new Album();
        $album->album_name = $request->album_name;
        // $album->artist_id = $request->artist_id;
        $album->artist()->associate($artist);
        // $album->save();

        $input = $request->all();
        $request->validate([
            'image' => 'mimes:jpeg,png,jpg,gif,svg',
            // 'image' => ['mimes:jpeg,png,jpg,gif,svg|
            //  file|max:512' ]
        ]);

        if($file = $request->hasFile('image')) {
            $file = $request->file('image') ;
            // $fileName = uniqid().'_'.$file->getClientOriginalName();
            $fileName = $file->getClientOriginalName();
            $destinationPath = public_path().'/images' ;
            // dd($fileName);
            //$input['img_path'] = 'images/'.$fileName;
            
            $input['img_path'] = 'images/'.$fileName;
            // $album = Album::create($input);
            $file->move($destinationPath,$fileName);
        }
          $album = Album::create($input);
        return Redirect::to('/albums')->with('success','New Album Added');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function show(Album $album)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         //! kapag get() collection yan
        //! kapag isa lang first()
        $album = Album::with('artist')->where('id',$id)->first();
        $artists = Artist::pluck('artist_name','id');
        return View::make('album.edit',compact('album', 'artists'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //! ===================New Method=============

        $artist = Artist::find($request->artist_id);
        // dd($artist);
        $album = Album::find($id);
        // $album->artist_id = $request->artist_id;
        $album->album_name = $request->album_name;
        $album->artist()->associate($artist);
        $album->genre =$request->genre;
        $album->save();
        // return Redirect::route('album.index')->with('success','Album updated!');
        return Redirect::to('albums')->with('success','Album updated!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $album = Album::find($id);
        $album->listeners()->detach();
        $album->delete();
        
        return Redirect::route('album.index')->with('success','Album deleted!');
    }

    public function getAlbums(AlbumsDataTable $dataTable)
    {   
        $albums =  Album::with(['artist','listeners'])->get();
        return $dataTable->render('album.albums');

    }
}
