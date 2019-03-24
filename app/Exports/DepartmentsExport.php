<?php

namespace App\Exports;

use App\Models\Department;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class DepartmentsExport implements FromView
{
    use Exportable;

    public function view(): view
    {
        $data['departments'] = Department::all();

        return view('departments.template', $data);
    }
}
