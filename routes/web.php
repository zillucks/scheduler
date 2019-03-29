<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('generate-adminuser', function (\Illuminate\Http\Request $request, \App\Models\User $user) {
    $exists = $user->where('username', 'admin')->first();
    if ($exists) {
        \Alert::warning('Admin Already Generated', 'User Exists');
        return redirect()->to('/');
    }

    DB::beginTransaction();
    try {
        $user->username = 'admin';
        $user->password = 'admin';
        if ($user->save()) {

            $role_id = \App\Models\Role::findBySlug('admin')->id;
            $identity = new \App\Models\Identity();
            $identity->full_name = 'Administrator';
            $identity->email = 'admin@email.com';

            $user->identity()->save($identity);

            $user->roles()->sync($role_id);

            DB::commit();

            Auth::login($user);

            \Alert::success('You are now login with Username: admin and password: admin', 'User Generated');

            return redirect()->to('/');
        }
    }
    catch(\Exception $e) {
        DB::rollback();
        \Alert::error($e->getMessage(), "Error {$e->getCode()}");
        return redirect()->to('/');
    }
});

Route::get('logout', function () {
    Session::forget('reservation');
    Auth::logout();
    return redirect('');
})->name('logout');

/**
 * for test only
 */
Route::get('test', function () {
    return 'Test Success';
});

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['role:admin, manager, helpdesk, user']], function () {

    Route::prefix('reservations')->group(function () {
        Route::get('/', 'ReservationController@index')->name('reservations');

        Route::get('booking-wizard', 'ReservationController@create')->name('reservations.booking-wizard');

        Route::post('form-wizard/step-1', 'ReservationController@formWizardTraining')->name('reservations.form-wizard.step-1');
        Route::post('form-wizard/step-2', 'ReservationController@formWizardGroup')->name('reservations.form-wizard.step-2');
        Route::post('form-wizard/step-3', 'ReservationController@formWizardConfirm')->name('reservations.form-wizard.step-3');

        Route::get('validate', 'ReservationController@validateReservation')->name('reservations.validate');

        Route::get('{slug}/view', 'ReservationController@show')->name('reservations.view');
        Route::post('save', 'ReservationController@store')->name('reservations.save');

        Route::get('{id}/reschedule', 'ReservationController@reschedule')->name('reservations.reschedule');
        Route::put('{id}/reschedule/submit', 'ReservationController@rescheduleSubmit')->name('reservations.reschedule.submit');

        Route::get('{id}/print', 'ReservationController@print')->name('reservations.print');

    });

    Route::get('profile', 'UserController@profile')->name('profile');
    Route::put('profile/update', 'UserController@profileUpdate')->name('profile.update');

    // Route::get('change-password', 'UserController@changePassword')->name('change-password');
    
});

Route::group(['middleware' => ['role:admin,helpdesk']], function () {

    Route::get('roles', 'HomeController@roles')->name('roles');

    Route::prefix('sites')->group(function () {
        Route::get('/', 'SiteController@index')->name('sites');
        Route::get('create', 'SiteController@create')->name('sites.create');
        Route::get('{slug}/view', 'SiteController@show')->name('sites.view');
        Route::get('{slug}/edit', 'SiteController@edit')->name('sites.edit');
        Route::post('save', 'SiteController@store')->name('sites.save');
        Route::put('{slug}/update', 'SiteController@update')->name('sites.update');
        Route::delete('{slug}/delete', 'SiteController@delete')->name('sites.delete');
        Route::delete('{slug}/force-delete', 'SiteController@forceDelete')->name('sites.force-delete');

        Route::match(['get', 'post'], 'import', 'SiteController@import')->name('sites.import');
        Route::get('download/{type}', 'SiteController@downloadTemplate')->name('sites.download');

        Route::get('{id}/classes', function (\App\Models\Classes $class, $id) {
            $classes = $class->where('site_id', $id)->pluck('class_name', 'id');
            $options = '';
            if ($classes->count() > 0) {
                foreach ($classes as $id => $value) {
                    $options .= "<option value='{$id}'>{$value}</option>";
                }
            }
            else {
                $options = "<option>No Classes in Site</option>";
            }

            return $options;

        })->name('sites.classes');

    });

    Route::prefix('directorates')->group(function () {
        Route::get('/', 'DirectorateController@index')->name('directorates');
        Route::get('create', 'DirectorateController@create')->name('directorates.create');
        Route::get('{slug}/view', 'DirectorateController@show')->name('directorates.view');
        Route::get('{slug}/edit', 'DirectorateController@edit')->name('directorates.edit');
        Route::post('save', 'DirectorateController@store')->name('directorates.save');
        Route::put('{slug}/update', 'DirectorateController@update')->name('directorates.update');
        Route::delete('{slug}/delete', 'DirectorateController@delete')->name('directorates.delete');
        Route::delete('{slug}/force-delete', 'DirectorateController@forceDelete')->name('directorates.force-delete');

        Route::match(['get', 'post'], 'import', 'DirectorateController@import')->name('directorates.import');
        Route::get('download/{type}', 'DirectorateController@downloadTemplate')->name('directorates.download');
    });

    Route::prefix('organizations')->group(function () {
        Route::get('/', 'OrganizationController@index')->name('organizations');
        Route::get('create', 'OrganizationController@create')->name('organizations.create');
        Route::get('{slug}/view', 'OrganizationController@show')->name('organizations.view');
        Route::get('{slug}/edit', 'OrganizationController@edit')->name('organizations.edit');
        Route::post('save', 'OrganizationController@store')->name('organizations.save');
        Route::put('{slug}/update', 'OrganizationController@update')->name('organizations.update');
        Route::delete('{slug}/delete', 'OrganizationController@delete')->name('organizations.delete');
        Route::delete('{slug}/force-delete', 'OrganizationController@forceDelete')->name('organizations.force-delete');

        Route::match(['get', 'post'], 'import', 'OrganizationController@import')->name('organizations.import');
        Route::get('download/{type}', 'OrganizationController@downloadTemplate')->name('organizations.download');
    });

    Route::prefix('departments')->group(function () {
        Route::get('/', 'DepartmentController@index')->name('departments');
        Route::get('create', 'DepartmentController@create')->name('departments.create');
        Route::get('{slug}/view', 'DepartmentController@show')->name('departments.view');
        Route::get('{slug}/edit', 'DepartmentController@edit')->name('departments.edit');
        Route::post('save', 'DepartmentController@store')->name('departments.save');
        Route::put('{slug}/update', 'DepartmentController@update')->name('departments.update');
        Route::delete('{slug}/delete', 'DepartmentController@delete')->name('departments.delete');
        Route::delete('{slug}/force-delete', 'DepartmentController@forceDelete')->name('departments.force-delete');

        Route::match(['get', 'post'], 'import', 'DepartmentController@import')->name('departments.import');
        Route::get('download/{type}', 'DepartmentController@downloadTemplate')->name('departments.download');
    });

    Route::prefix('units')->group(function () {
        Route::get('/', 'UnitController@index')->name('units');
        Route::get('create', 'UnitController@create')->name('units.create');
        Route::get('{slug}/view', 'UnitController@show')->name('units.view');
        Route::get('{slug}/edit', 'UnitController@edit')->name('units.edit');
        Route::post('save', 'UnitController@store')->name('units.save');
        Route::put('{slug}/update', 'UnitController@update')->name('units.update');
        Route::delete('{slug}/delete', 'UnitController@delete')->name('units.delete');
        Route::delete('{slug}/force-delete', 'UnitController@forceDelete')->name('units.force-delete');

        Route::match(['get', 'post'], 'import', 'UnitController@import')->name('units.import');
        Route::get('download/{type}', 'UnitController@downloadTemplate')->name('units.download');
    });

    Route::prefix('classes')->group(function () {
        Route::get('/', 'ClassController@index')->name('classes');
        Route::get('create', 'ClassController@create')->name('classes.create');
        Route::get('{slug}/view', 'ClassController@show')->name('classes.view');
        Route::get('{slug}/edit', 'ClassController@edit')->name('classes.edit');
        Route::post('save', 'ClassController@store')->name('classes.save');
        Route::put('{slug}/update', 'ClassController@update')->name('classes.update');
        Route::delete('{slug}/delete', 'ClassController@delete')->name('classes.delete');
        Route::delete('{slug}/force-delete', 'ClassController@forceDelete')->name('classes.force-delete');

    });

    Route::prefix('users')->group(function () {
        Route::get('/', 'UserController@index')->name('users');
        Route::get('create', 'UserController@create')->name('users.create');
        Route::get('{slug}/view', 'UserController@show')->name('users.view');
        Route::get('{slug}/edit', 'UserController@edit')->name('users.edit');
        Route::post('save', 'UserController@store')->name('users.save');
        Route::put('{slug}/update', 'UserController@update')->name('users.update');
        Route::delete('{slug}/delete', 'UserController@delete')->name('users.delete');
        Route::delete('{slug}/force-delete', 'UserController@forceDelete')->name('users.force-delete');

        Route::get('{slug}/setting', 'UserController@setting')->name('users.setting');
        Route::post('{slug}/setting/submit', 'UserController@settingSubmit')->name('users.setting.submit');

        Route::match(['get', 'post'], 'import', 'UserController@import')->name('users.import');
        Route::get('download/{type}', 'UserController@downloadTemplate')->name('users.download');
    });

    Route::prefix('trainings')->group(function () {
        Route::get('/', 'TrainingController@index')->name('trainings');
        Route::get('create', 'TrainingController@create')->name('trainings.create');
        Route::get('{slug}/view', 'TrainingController@show')->name('trainings.view');
        Route::get('{slug}/edit', 'TrainingController@edit')->name('trainings.edit');
        Route::post('save', 'TrainingController@store')->name('trainings.save');
        Route::put('{slug}/update', 'TrainingController@update')->name('trainings.update');
        Route::delete('{slug}/delete', 'TrainingController@delete')->name('trainings.delete');
        Route::delete('{slug}/force-delete', 'TrainingController@forceDelete')->name('trainings.force-delete');

        Route::get('schedules', 'TrainingController@schedules')->name('trainings.schedules');
        Route::get('schedules/{id}/participants', 'TrainingController@participants')->name('trainings.schedules.participants');
    });

    Route::prefix('available-times')->group(function () {
        Route::get('/', 'AvailableTimeController@index')->name('available-times');
        Route::get('create', 'AvailableTimeController@create')->name('available-times.create');
        Route::post('save', 'AvailableTimeController@store')->name('available-times.save');
        Route::get('{id}/edit', 'AvailableTimeController@edit')->name('available-times.edit');
        Route::put('{id}/update', 'AvailableTimeController@update')->name('available-times.update');
        Route::delete('{id}/delete', 'AvailableTimeController@delete')->name('available-times.delete');
        Route::delete('{id}/force-delete', 'AvailableTimeController@forceDelete')->name('available-times.force-delete');
    });

    Route::prefix('attendances')->group(function () {
        Route::get('re-registration', 'AttendanceController@reRegistration')->name('attendances.re-registration');
        Route::get('{id}/checkin', 'AttendanceController@checkin')->name('attendances.checkin');
        Route::post('{id}/checkin/presence', 'AttendanceController@checkinPresence')->name('attendances.checkin.presence');
        Route::get('{id}/checkin/submit', 'AttendanceController@checkinSubmit')->name('attendances.checkin.submit');
    });

});