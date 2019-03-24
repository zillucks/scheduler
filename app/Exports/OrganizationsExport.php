<?php

namespace App\Exports;

use App\Models\Organization;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class OrganizationsExport implements FromView
{
    use Exportable;

    public function view(): view
    {
        $data['organizations'] = Organization::all();

        return view('organizations.template', $data);
    }
}
