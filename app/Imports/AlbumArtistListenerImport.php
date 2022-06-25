<?php

namespace App\Imports;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AlbumArtistListenerImport implements  WithMultipleSheets

{
    	public function sheets(): array
	    {
	        return [
	            'Allsheets' => new FirstSheetImport(),
	            
	        ];
	    }
}
