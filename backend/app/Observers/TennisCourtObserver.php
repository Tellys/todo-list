<?php

namespace App\Observers;

use App\Events\TennisCourtCreated;
use App\Models\Product;
use App\Models\TennisCourt;
use App\Models\TennisCourtOpeningHour;

class TennisCourtObserver
{
    /**
     * Listen to the User created event.
     *
     * @param  \App\TennisCourt  $tennisCourt
     * @return void
     */
    public function created(TennisCourt $tennisCourt)
    {
        // chama on TennisCourtCreated
        //event(new TennisCourtCreated($tennisCourt));

        $tennisCourt->registration_phase = 'dashboardProductCreate';
        $tennisCourt->save();

        Product::createProductsBase($tennisCourt->id);
        TennisCourtOpeningHour::createTennisCourtOpeningHoursBase($tennisCourt->id);
    }

    /**
     * Listen to the User deleting event.
     *
     * @param  \App\TennisCourt  $tennisCourt
     * @return void
     */
    public function deleted(TennisCourt $tennisCourt)
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
