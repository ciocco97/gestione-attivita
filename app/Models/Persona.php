<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        return Persona::all()->sortBy("nome");
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


}
