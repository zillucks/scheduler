<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Identity;
use App\Models\Department;
use App\Models\Organization;
use App\Models\Site;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;

class UsersImport implements ToModel, WithHeadingRow, SkipsOnError
{

    use Importable, SkipsErrors;
    
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // return new User([
        //     //
        // ]);
        $user = User::firstOrNew(['username' => $row['username']]);
        $user->password =$row['password'];
        $user->save();

        $identity = Identity::firstOrNew(['user_id' => $user->id]);
        $identity->full_name = $row['full_name'];
        $identity->email = $row['email'];
        $identity->department_id = Department::findBySlug($row['department'])->id;
        $identity->organization_id = Organization::findBySlug($row['organization'])->id;
        $identity->site_id = Site::findBySlug($row['site'])->id;
        $identity->identity_status = $row['status'];
        $identity->user_ldap = $row['user_ldap'];

        $user->identity()->save($identity);

        $role = Role::findBySlug($row['role_id']);

        $user->roles()->sync($role->id);
    }
}
