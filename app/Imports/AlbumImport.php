<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Album;
use App\Models\Artist;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AlbumImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            // dd($row);

           	try {
    			// $artist = Artist::where('artist_name',$row['artist'])->first();
	        	$artist = Artist::where('artist_name',$row['artist'])->firstOrFail();
	              }
                catch(ModelNotFoundException $ex) {
	        	// dd($ex);
        		$artist = new Artist();
				$artist->artist_name = $row['artist'];
		        $artist->save();
        		
            }
			$album = new Album();
	        $album->album_name = $row['album'];
	        $album->genre = $row['genre'];
	        $album->artist()->associate($artist);
	        $album->save();
        }
    }
}