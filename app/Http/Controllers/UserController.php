<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\Ruolo;
use http\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return
     */
    public function index()
    {
        $users = Persona::listUsers();
        $this->addUserPhotoPath($users);
        $this->addUserTeam($users);
        $this->addUserRoles($users);
        $this->addUserActivityNum($users);

        $user_roles = Ruolo::listRoles();

        return view('user.administrator')
            ->with('users', $users)
            ->with('user_roles', $user_roles)
            ->with('current_page', Shared::PAGES['ADMINISTRATOR']);
    }

    public function addUserPhotoPath($users)
    {
        foreach ($users as $user) {
            $file_name = 'profile_image.jpg';
            if (!Storage::disk('uploads')->exists($user->id . '/' . $file_name)) {
                $user->photo_path = Storage::url('profile_image.jpg');
            } else {
                $user->photo_path = route('image.profile.show', ['id' => $user->id]);
            }
        }
    }

    public function addUserTeam($users)
    {
        foreach ($users as $user) {
            $user->team_ids = $user->sottoposti()->get()->pluck('id')->toArray();
        }
    }

    public function addUserActivityNum($users)
    {
        foreach ($users as $user) {
            $user->num_activity = $user->attivita()->count();
        }
    }

    public function addUserRoles($users)
    {
        foreach ($users as $user) {
            $user->role_ids = $user->ruoli()->get()->pluck('id')->toArray();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
