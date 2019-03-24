<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class UsersExport implements FromView
{
    use Exportable;

    public function view(): view
    {
        $data['users'] = User::all();

        return view('users.template', $data);
    }
}
