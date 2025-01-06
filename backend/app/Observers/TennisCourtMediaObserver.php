<?php

namespace App\Observers;

use App\Models\TennisCourt;
use App\Models\TennisCourtMedia;

class TennisCourtMediaObserver
{
    public function created(TennisCourtMedia $item)
    {
        //dd($product);
        // da um set no product como registrado
        // chama o envio de e-mail para verificação
        //event(new \Illuminate\Auth\Events\Registered($user));
        TennisCourt::where('id', $item->tennis_court_id)->update(['registration_phase' => 'full']);
    }

    /**
     * Listen to the User deleting event.
     *
     * @param  \App\Product  $product
     * @return void
     */
    public function deleted(TennisCourtMedia $item)
    {
        //
    }
}
