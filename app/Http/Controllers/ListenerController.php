<?php

namespace App\Http\Controllers;

use App\Models\Listener;
use App\Models\Album;
use Illuminate\Http\Request;
use View;
use Redirect;
use App\DataTables\ListenersDataTable;
use DataTables;

use App\Imports\ListenerImport;
use Excel;
use App\Rules\ExcelRule;

use App\Events\SendMail;
use Event;
use Auth;

class ListenerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {
        //!========================New CODE============================

        //Need Request $request parameter for searching
        if (empty($request->get('search'))) {
            $listeners = Listener::with('albums')->get();
            }
        else 
            {

            $listeners = Listener::whereHas('albums',function($q) use($request){
                $q->where("album_name","LIKE", "%".$request->get('search')."%");
                })->orWhere('listener_name',"LIKE","%".$request->get('search')."%")
                ->get();
            } 
        $url = 'listener';
        return View::make('listener.index',compact('listeners','url'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //! ===============New Method=============
        
        $albums = Album::with('artist')->get();
        // foreach($albums as $album)
        // {
        //     dump($album->artist->artist_name);
        // }
        return View::make('listener.create',compact('albums'));
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
        $input['password'] = bcrypt($request->password);
        $listener = Listener::create($input);
        Event::dispatch(new SendMail($listener));
        if(!(empty($request->album_id))){
                $listener->albums()->attach($request->album_id);
          }
        return Redirect::route('getListeners')->with('success','listener created!');
    }
    // public function store(Request $request)
    // {
    //     ///===============New Method=============
    //     $input = $request->all();
    //     // dd($request->album_id);
    //     $listener = Listener::create($input);

    //     if(!(empty($request->album_id))){
    //         foreach ($request->album_id as $album_id) 
    //         {
    //             $listener->albums()->attach($album_id);
    //         } 
    //         //end foreach
    //     }

    //     return Redirect::to('listeners')->with('success','New Listener created!');  
    // }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Listener  $listener
     * @return \Illuminate\Http\Response
     */
    public function show(Listener $listener)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Listener  $listener
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //! ===============New Method=============

       $listener_albums = array();
       $listener = Listener::with('albums')->where('id', $id)->first();
       if(!(empty($listener->albums))){
            foreach($listener->albums as $listener_album) {
                // dump($listener_album); 
                $listener_albums[$listener_album->id] = $listener_album->album_name ;
            }
        }
        $albums = Album::pluck('album_name','id')->toArray();
        //dd($albums, $listener->albums);
        //dd($albums, $listener_albums);
        return View::make('listener.edit',compact('albums','listener','listener_albums'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Listener  $listener
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //! ===============New Method=============

       $listener = Listener::find($id);
       $album_ids = $request->input('album_id');
       $listener->albums()->sync($album_ids);
       $listener->update($request->all());
        return Redirect::to('listeners')->with('success','Listener updated!');
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Listener  $listener
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //! ===============New Method=============

        $Listener = Listener::find($id);
        $Listener->albums()->detach();
        $Listener->delete();
        return Redirect::to('listeners')->with('success','Listener deleted!');
       
    }

    public function getListeners(ListenersDataTable $dataTable)
    {
        $albums = Album::with('artist')->get();
        return $dataTable->render('listener.listeners', compact('albums'));
    }

    public function import(Request $request) {
         //! import excel file
        
        $request->validate([
                'listener_upload' => ['required', new ExcelRule($request->file('listener_upload'))],
        ]);
        // dd($request);
        Excel::import(new ListenerImport, request()->file('listener_upload'));
return redirect()->back()->with('success', 'Excel file Imported Successfully');
    }
}
