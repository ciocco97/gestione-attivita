<?php

namespace App\Http\Controllers;

use App\DataLayer;
use App\Http\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class AuthController extends Controller
{

    /**
     * Funzione che "attua" il login e il logout andando a modificare i dati di
     * sessione
     * @param bool $logged true se deve essere eseguito il login, false se deve
     * essere eseguito il logout
     * @param type $user (opzionale) necessario se sta avvenendo il login per
     * salvare le giuste infos in $_SESSION
     */
    public function sessionAuthentication($logged, $user = null)
    {
        Log::debug('Stato sessione', ['stato' => session_status()]);

        $_SESSION['logged'] = $logged;
        if ($logged) {
            Log::debug('Utente loggato', ['id' => $user->id]);
            $_SESSION['user_id'] = $user->id;
            $_SESSION['username'] = $user->nome;
        } else {
            Log::debug('Utente disconnesso', ['id' => $_SESSION['user_id']]);
            $_SESSION['user_id'] = -1;
            $_SESSION['username'] = null;
        }
    }

    /**
     * getEmailFromCookie
     * @return string $email_cookie, la email salvata nei cookie. Se non è salvata alcuna
     * email, ritorna ''
     */
    public function getEmailFromCookie()
    {
        $email_cookie = filter_input(INPUT_COOKIE, 'email');
        if (!isset($email_cookie)) {
            $email_cookie = '';
        }
        Log::debug('Email da cookie', ['email' => $email_cookie]);
        return $email_cookie;
    }

    /**
     * authView rende parametrica l'invocazione della vista di autenticazione
     */
    private function authView()
    {
        $email_cookie = $this->getEmailFromCookie();
        $time_stamp = date('Y/m/d - h:i:sa');
        return view('user.auth')
            ->with('email', $email_cookie);
    }

    /**
     * Gestisce la richiesta di login (HTTP_GET).
     * @return view
     */
    public function login(Request $request)
    {

        Log::debug('____________________Richiesta login HTTP_GET____________________');

        if (isset($_SESSION['logged']) && $_SESSION['logged']) {
            return Redirect::to(route('activity.index'));
        }

        return $this->authView();
    }

    /**
     * Gestisce la richiesta di autenticazione (HTTP_POST).
     * Se le credenziali sono corrette, controlla il rememberMe check box:
     * - se è settato a true, salva l'email nei cookie
     * - se false elimina l'email nei cookie
     * @param Request $request
     * @return view
     */
    public function authentication(Request $request)
    {

        Log::debug('____________________Richiesta login HTTP_POST____________________');

        $email = $request->input('email');
        $password = $request->input('password');
        $remember_me = $request->input('rememberMe');

        if ($email == '' || $password == '') {
            Log::debug('Tentativo di login: email e/o password non inserite');
            return Redirect::to(route('user.login'));
        }

        // Check credenziali nel DB
        $dataLayer = new DataLayer();
        $user = $dataLayer->validUser($email, md5($password));

        if ($user) { // Credenziali corrette
            Log::debug('Login avvenuto correttamente', ['id' => $user->id]);

            $this->sessionAuthentication(true, $user);

            if ($remember_me) {
                setcookie('email', $email, time() + 3600 * 48);
                Log::debug('Email salvata nei cookie', ['email' => $email]);
            } else {
                setcookie('email', '', -1);
                Log::debug('Cookie dimenticato');
            }

            return Redirect::to(route('activity.index'));
        } else { // Credenziali errate
            Log::debug('Login fallito');
            return Redirect::to(route('user.login'));
        }
    }

    /**
     * Effettua il logout e reindirizza a login
     */
    public function logout()
    {
        $this->sessionAuthentication(false);
        return Redirect::to(route('user.login'));
    }

    public function choosePassword()
    {
        $username = $_SESSION['username'];
        return view('user.change_password')
            ->with('username', $username)
            ->with('previous_url', $_SESSION['previous_url']);
    }

    public function changePassword(Request $request)
    {
        $user_id = $_SESSION['user_id'];
        $old_password = $request->get('oldPassword');
        $new_password = $request->get('newPassword');

        // Check credenziali nel DB
        $dataLayer = new DataLayer();
        $user = $dataLayer->validUser(null, md5($old_password), $user_id);

        if ($user) { // pwd can be changed
            $dataLayer->changePassword($user_id, md5($new_password));
            return redirect($_SESSION['previous_url']);
        }
        return redirect()->back();

    }

}
