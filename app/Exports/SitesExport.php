<?php

namespace App\Exports;

use App\Models\Site;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class SitesExport implements FromView
{
    use Exportable;

    public function view(): view
    {
        $data['sites'] = Site::all();

        return view('sites.template', $data);
    }
}
