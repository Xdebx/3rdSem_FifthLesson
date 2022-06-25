<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Album;
use App\Models\Artist;
use App\Models\Listener;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Queue\Console\ListenCommand;

class FirstSheetImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        
        foreach ($rows as $row) 
        {
            try {
    			// $artist = Artist::where('artist_name',$row['artist'])->first();
	        	$artist = Artist::where('artist_name',$row['artist'])->firstOrFail();
	        	}
                
        	catch (ModelNotFoundException $ex) {
	        	$artist = new Artist();
				$artist->artist_name = $row['artist'];
		        $artist->save();
        		
            }

            try {
    			// $artist = Artist::where('artist_name',$row['artist'])->first();
	        	$album = Album::where('album_name',$row['album'])->firstOrFail();
	           }
        	catch (ModelNotFoundException $ex) {
	        	// dd($ex);
        		$album = new Album();
		        $album->album_name = $row['album'];
		        $album->genre = $row['genre'];
		    }
            $album->artist()->associate($artist);
            $album->save();

            
            // $listener = new Listener();
            // $listener->listener_name = $row['listener'];
            // $listener->save();
            // $listener->albums()->attach($album->id);

            try {
                // $artist = Artist::where('artist_name',$row['artist'])->first();
                $listener = Listener::where('listener_name',$row['listener'])->firstOrFail();
               }
            catch (ModelNotFoundException $ex) {
                // dd($ex);
                $listener = new Listener();
                $listener->listener_name = $row['listener'];
                $listener->save();
            }
            $listener->albums()->attach($album->id);

}

}

}