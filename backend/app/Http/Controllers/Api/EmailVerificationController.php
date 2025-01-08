<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Auth\Events\Verified;

class EmailVerificationController extends BaseController
{

    private $user;

    //public function sendVerificationEmail(RequestFrm $request)
    public function sendVerificationEmail($bool = false)
    {
        $this->user = auth('sanctum')->user();

        if (!$this->user) {
            return $this->sendError( 'Usuário não autenticado via API', 401);
        }

        try {
            if ($this->user->hasVerifiedEmail()) {
                $this->message = 'Email já verificado!';
            } else {
                $this->message = 'E-mail para Verificação de conta enviado!';
                $this->user->sendEmailVerificationNotification();
            }

            $this->status = 200;

            return $this->sendResponse('success', $this->message, $this->status);
        } catch (\Throwable $th) {
            $this->message = $th;
            return $this->sendError( $this->message, $this->status);
        }
    }

    public function verify(User $user, $id, $hash)
    {
        $this->user = $user::findOrFail($id);

        $this->verifyAuthorize();

        $this->status = 200;
        $this->message ='E-mail Verificado!';

        if ($this->user->hasVerifiedEmail()) {
            $this->message ='O e-mail já havia sido verificado anteriormente';
        }

        if ($this->user->markEmailAsVerified()) {
            event(new Verified($this->user));
        }

        return $this->sendResponse('success', $this->message, $this->status);
    }

    public function verifyAuthorize()
    {
        //if (!hash_equals((string) $this->user->getKey(), (string) request()->route('id'))) {
        if (!hash_equals((string) $this->user->getKey(), (string) request()->id)) {
            return false;
        }

        //if (!hash_equals(sha1($this->user->getEmailForVerification()), (string) request()->route('hash'))) {
        if (!hash_equals(sha1($this->user->getEmailForVerification()), (string) request()->hash)) {
            return false;
        }

        return true;
    }
}
