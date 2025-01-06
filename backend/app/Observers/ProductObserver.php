<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\TennisCourt;

class ProductObserver
{
    /**
     * Listen to the User created event.
     *
     * @param  \App\Product  $product
     * @return void
     */
    public function created(Product $product)
    {
        //dd($product);
        // da um set no product como registrado
        // chama o envio de e-mail para verificação
        //event(new \Illuminate\Auth\Events\Registered($user));
        TennisCourt::where('id', $product->tennis_court_id)->update(['registration_phase' => 'dashboardTennisCourtOpeningHourCreate']);
    }

    /**
     * Listen to the User deleting event.
     *
     * @param  \App\Product  $product
     * @return void
     */
    public function deleted(Product $product)
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
