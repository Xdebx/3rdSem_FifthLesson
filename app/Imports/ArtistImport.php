<?php

namespace App\Imports;

use App\Models\Artist;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ArtistImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Artist([
           'artist_name' => $row['name'],
            //  'artist_name' => $row[0],
           
        ]);
    }
}
