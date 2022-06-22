<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\Album;
use Illuminate\Http\Request;
use View;
use Redirect;
use App\DataTables\ArtistsDataTable;
use DataTables;
use Yajra\DataTables\Html\Builder;

use App\Imports\ArtistImport;
use Excel;
use App\Rules\ExcelRule;




class ArtistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {
        //! ==========================New Codes===================
        //! Need Request $request sa parameter ng index for searching

        if (empty($request->get('search'))) {
            $artists = Artist::has('albums')->get();
            // dd($artists);
        }
        else 
        $artists = Artist::with(['albums'=> function($q) use($request){

            // SEARCHING FOR GENRE TO NA ATTRIBUTE NG ALBUM
            $q->where("genre","=",$request->get('search'))
            ->orWhere("album_name","LIKE", "%".$request->get('search')."%");
            }])->get();

        $url = 'artist';
        return View::make('artist.index',compact('artists','url'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View::make('artist.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $request->validate([
            'image' => 'mimes:jpeg,png,jpg,gif,svg',
    
        ]);

        if($file = $request->hasFile('image')) {
            $file = $request->file('image');
            // $fileName = uniqid().'_'.$file->getClientOriginalName();
            $fileName = $file->getClientOriginalName();
            $destinationPath = public_path().'/images' ;
            // dd($fileName);
            $input['img_path'] = 'images/'.$fileName;
            // $album = Album::create($input);
            $file->move($destinationPath,$fileName);
        }
            $album = Artist::create($input);
        return Redirect::to('/artists')->with('success','New Artist Added!');
        //return redirect::to('artist');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Artist  $artist
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $artist = Artist::find($id);
        // dd($artist);
        return view('artist.show',compact('artist'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Artist  $artist
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $artist = Artist::find($id);
        return View::make('artist.edit',compact('artist'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Artist  $artist
     * @return \Illuminate\Http\Response
     */
    public function update(request $request,$id)
    {
        $artist = Artist::find($id);
        $artist->update($request->all()); 
        return Redirect::to('/artists')->with('success','Artist Updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Artist  $artist
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $artist = Artist::find($id);
        $artist->delete();
        return Redirect::to('/artists')->with('success','Artist Deleted!');
    }
    public function getArtists(Builder $builder) {
        // dd($dataTable);
        // return $dataTable->render('artist.artists');

                // dd($dataTable);
        // return $dataTable->render('artist.artists');
        // dd(Artist::orderBy('artist_name', 'DESC')->get());
        // $artists = Artist::orderBy('artist_name', 'ASC')->get();
        // dd($artists);
        
        //$artists = Artist::orderBy('artist_name', 'DESC');
        $artist = Artist::query();
        //dd($artist);
        if (request()->ajax()) {
            // return DataTables::of($artists)
            //                 ->toJson();
            // return DataTables:
 //                 ->toJson();
            /*return DataTables::of($artist)->order(function ($query) {
                     $query->orderBy('created_at', 'DESC');
                 })->toJson();*/
                        // ->make();
             return DataTables::of($artist)
             /* may epektop sa pag sorting*/
            //  ->order(function ($query) {
            //                      $query->orderBy('artist_name', 'DESC');
            //                  })
                             ->addColumn('action', function($row) {
                                return "<a href=".route('artist.edit', $row->id). "
            class=\"btn btn-warning\">Edit</a><form action=". route('artist.destroy', $row->id). " method= \"POST\" >". csrf_field() .
                                '<input name="_method" type="hidden" value="DELETE">
                                <button class="btn btn-danger" type="submit">Delete</button>
                                  </form>';
                        })
                                // ->rawColumns(['action'])
                                ->toJson();
                    }
       
            $html = $builder->columns([
                            ['data' => 'id', 'name' => 'id', 'title' => 'Id'],
                            ['data' => 'artist_name', 'name' => 'artist_name', 'title' => 'Name'],
                            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Created At'],
                            ['data' => 'updated_at', 'name' => 'updated_at', 'title' => 'Updated At',"orderable" => false],
                            ['data' => 'action', 
                            'name' => 'action', 
                            'title' => 'Action', 
                            'seacrhable' => false, 
                            'orderable' => false, 
                            'exportable' => false],
                        ]);

                return view('artist.artists', compact('html'));

                }
                public function import(Request $request) {
                    //! import excel file
        
                         $request->validate([
                        'artist_upload' => ['required', new ExcelRule($request->file('artist_upload'))],
                    ]);
                        // dd($request);
                        Excel::import(new ArtistImport, request()->file('artist_upload'));
                        
                        return redirect()->back()->with('success', 'Excel file Imported Successfully');
                    }
}
