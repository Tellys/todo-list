<?php

namespace App\Observer;

use App\Models\User;

class UserObserver
{
    /**
     * Listen to the User created event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function created(User $user)
    {
        // da um set no user como registrado
        // chama o envio de e-mail para verificação
        event(new \Illuminate\Auth\Events\Registered($user));

    }

    /**
     * Listen to the User deleting event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /*
    creating: Call Before Create Record.
    created: Call After Created Record.
    updating: Call Before Update Record.
    updated: Class After Updated Record.
    deleting: Call Before Delete Record.
    deleted: Call After Deleted Record.
    retrieved: Call Retrieve Data from Database.
    saving: Call Before Creating or Updating Record.
    saved: Call After Created or Updated Record.
    restoring: Call Before Restore Record.
    restored: Call After Restore Record.
    replicating: Call on replicate Record.
    */
}
