<?php

namespace App\Models;

use App\Http\Controllers\Shared;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Persona extends Model
{
    use HasFactory;

    protected $table = 'persona';
    protected $fillable = ['nome', 'cognome', 'email', 'password', 'attivo', 'token', 'istante_creazione_token'];
    public $timestamps = false;
    protected $hidden = ['pivot', 'password'];

    public function attivita()
    {
        return $this->hasMany(Attivita::class);
    }

    public function sottoposti()
    {
        return $this->belongsToMany(Persona::class, 'manager_sottoposto', 'manager_id', 'sottoposto_id');
    }

    public function manager()
    {
        return $this->belongsToMany(Persona::class, 'manager_sottoposto', 'sottoposto_id', 'manager_id');
    }

    public function ruoli()
    {
        return $this->belongsToMany(Ruolo::class, 'persona_ruolo');
    }


    public static function listUsers()
    {
        return Persona::all()->sortBy('nome');
    }

    public static function validUser($email, $password, $user_id = -1)
    {
        if ($email != null) {
            $persona = Persona::where('email', $email);
        } else {
            $persona = Persona::find($user_id);
        }
        $persona = $persona->where('password', $password)->where('attivo', 1)->get()->first();
        return $persona ?? false;
    }

    public static function storeToken($email, $token): bool
    {
        if (Persona::where('email', $email)->get()->count() == 0) {
            return false;
        }
        Persona::where('email', $email)
            ->update([
                'token' => md5($token),
                'istante_creazione_token' => Carbon::now()->toDateTimeString()
            ]);
        return true;
    }

    public static function validToken($email, $token): bool
    {
        $user = Persona::where('email', $email)->get()->first();
        $before = new Carbon($user->istante_creazione_token);
        $now = Carbon::now();
        if ($user->token == md5($token) && $before->diffInSeconds($now) < 240) {
            Log::debug('Token valido');
            return true;
        }
        Log::debug('Token non valido');
        return false;
    }

    public static function resetPassword($email, $password)
    {
        $user_id = Persona::where('email', $email)->get()->first()->id;
        Persona::changePassword($user_id, $password);
    }

    public static function changePassword($user_id, $password_md5)
    {
        $user = Persona::find($user_id);
        $user->password = $password_md5;
        $user->save();
    }


    public static function haveUpdatePermissionOnActivity($user_id, Attivita $activity): bool
    {
        return (Persona::haveTechnicianPermissionOnActivity($user_id, $activity) && !Attivita::isApproved($activity))
            || Persona::haveManagerPermissionOnActivity($user_id, $activity);
    }

    public static function haveShowPermissionOnActivity($user_id, $activity): bool
    {
        return Persona::haveTechnicianPermissionOnActivity($user_id, $activity)
            || Persona::haveManagerPermissionOnActivity($user_id, $activity)
            || Persona::haveAdministrativePermissionOnActivity($user_id);
    }

    public static function haveTechnicianPermissionOnActivity($user_id, $activity): bool
    {
        $activity_user_id = $activity->persona_id;
        return $activity_user_id == $user_id;
    }

    public static function haveManagerPermissionOnActivity($user_id, $activity): bool
    {
        $activity_user_id = $activity->persona_id;
        return in_array($activity_user_id, Persona::listTeamIDS($user_id));
    }

    public static function haveAdministrativePermissionOnActivity($user_id): bool
    {
        return in_array(1, Persona::listUserRoles($user_id)->toArray());
    }

    public static function haveCommercialPermission($user_id): bool
    {
        return in_array(2, Persona::listUserRoles($user_id)->toArray());
    }

    public static function haveAdministratorPermission($user_id): bool
    {
        return in_array(4, Persona::listUserRoles($user_id)->toArray());
    }


    public static function listTeam(int $user_id)
    {
        $user = Persona::find($user_id);
        return $user->sottoposti()->orderBy("nome")->get();
    }

    public static function listTeamIDS(int $user_id)
    {
        return Persona::listTeam($user_id)->pluck('id')->toArray();
    }

    public static function listUserRoles(int $user_id)
    {
        $user = Persona::find($user_id);
        $roles = $user->ruoli()->get();
        $team = Persona::listTeam($user_id);
        if ($team->count() > 0) {
            $manager_role = Ruolo::find(3);
            $roles->push($manager_role);
        }
        return $roles->pluck('id');
    }

    public static function changeEmail(int $logged_user_id, int $target_user_id, $email): bool
    {
        if (self::haveAdministratorPermission($logged_user_id)) {
            $user = Persona::find($target_user_id);
            $user->email = $email;
            $user->save();
            return true;
        }
        return false;
    }

    public static function changeTeamMember(int $logged_user_id, int $manager_id, $team_member_id, bool $attach): bool
    {
        if (self::haveAdministratorPermission($logged_user_id)) {
            $user = Persona::find($manager_id);
            if ($attach) {
                $user->sottoposti()->attach($team_member_id);
            } else {
                $user->sottoposti()->detach($team_member_id);
            }
            return true;
        }
        return false;
    }

    public static function changeRole(int $logged_user_id, int $target_id, int $role_id, bool $attach): bool
    {
        if (self::haveAdministratorPermission($logged_user_id)) {
            $user = Persona::find($target_id);
            if ($attach) {
                $user->ruoli()->attach($role_id);
            } else {
                $user->ruoli()->detach($role_id);
            }
            return true;
        }
        return false;
    }

    public static function changeActiveState(int $logged_user_id, int $target_id, bool $active): bool
    {
        if (self::haveAdministratorPermission($logged_user_id)) {
            $user = Persona::find($target_id);
            $user->attivo = $active ? Shared::USER_ACTIVE['ACTIVE'] : Shared::USER_ACTIVE['NOT_ACTIVE'];
            $user->save();
            return true;
        }
        return false;
    }

    public static function getUserByID($current_user_id, $user_id) {
        if (self::haveAdministratorPermission($current_user_id)) {
            return Persona::find($user_id);
        }
        return false;
    }

    public static function storeUser($user_id, $name, $surname, $email) {
        if (self::haveAdministratorPermission($user_id)) {
            Persona::create([
                'nome' => $name,
                'cognome' => $surname,
                'email' => $email,
                'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                'attivo' => 0,
            ]);
            return true;
        }
        return false;
    }

    public static function destroyUser($current_user_id, $user_id) {
        $num_activities = Persona::find($user_id)->attivita->count();
        if ($num_activities < 1 && $user_id != Shared::ADMIN_ID && self::haveAdministratorPermission($current_user_id)) {
            if (Persona::find($user_id)->attivita()->count() == 0) {
                Persona::destroy($user_id);
            }
            return true;
        }
        return false;
    }


}
