<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\Ruolo;
use http\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
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

        $_SESSION['previous_url'] = url()->current();

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
            if ($user->id != 1) {
                $user->num_activity = $user->attivita()->count();
            } else {
                $user->num_activity = 1;
            }
        }
    }

    public function addUserRoles($users)
    {
        foreach ($users as $user) {
            $user->role_ids = $user->ruoli()->get()->pluck('id')->toArray();
        }
    }

    public function sedUserView($method, int $user_id = -1)
    {
        Log::debug('sedUserView');
        $current_user_id = $_SESSION['user_id'];

        $user = null;
        if ($user_id != -1) {
            $user = Persona::getUserByID($current_user_id, $user_id);
        }

        return view('user.show_user')
            ->with('method', $method)
            ->with('user', $user)
            ->with('SHOW', Shared::METHODS['SHOW'])
            ->with('EDIT', Shared::METHODS['EDIT'])
            ->with('DELETE', Shared::METHODS['DELETE'])
            ->with('ADD', Shared::METHODS['ADD'])
            ->with('previous_url', $_SESSION['previous_url']);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->sedUserView(Shared::METHODS['ADD']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_id = $_SESSION['user_id'];
        $name = $request->get('name');
        $surname = $request->get('surname');
        $email = $request->get('email');
        Log::debug('Store_user', [
            'name' => $name,
            'surname' => $surname,
            'email' => $email
        ]);
        Persona::storeUser($user_id, $name, $surname, $email);

        return Redirect::to(route('user.index'));
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
        $current_user_id = $_SESSION['user_id'];
        Persona::destroyUser($current_user_id, $id);
        return Redirect::to($_SESSION['previous_url']);
    }
}
