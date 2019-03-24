<?php

namespace App\Exports;

use App\Models\Unit;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class UnitsExport implements FromView
{
    use Exportable;

    public function view(): view
    {
        $data['units'] = Unit::all();

        return view('units.template', $data);
    }
}
