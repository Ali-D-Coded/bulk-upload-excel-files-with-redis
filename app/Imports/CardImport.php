<?php

namespace App\Imports;

use App\Models\Cards;
use Maatwebsite\Excel\Concerns\ToModel;

class CardImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Cards([
            //
        ]);
    }
}
