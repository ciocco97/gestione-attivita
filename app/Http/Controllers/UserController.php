<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = Persona::listUsers();
        $this->addUserPhotoPath($users);
        $this->addUserTeam($users);
        $this->addUserActivityNum($users);
//        dump(Storage::disk('local')->allFiles());

        return view('user.administrator')->with('users', $users)->with('current_page', Shared::PAGES['ADMINISTRATOR']);
    }

    public function addUserPhotoPath($users)
    {
        foreach ($users as $user) {
            $file_name = '.jpg';
            if (Storage::disk('local')->exists($file_name)) {
                $user->photo_path = Storage::url($file_name);
            } else {
                $user->photo_path = Storage::url('default_user_photo.jpg');
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
