<?php

namespace App\Events;

use App\Models\TennisCourtCalendar;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class TennisCourtCalendarForDateAndTennisCourtIdEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $date;


    /**
     * Create a new event instance.
     */
    public function __construct(public $var, public $tennisCourtCalendarIdToForceDelete = null)
    {
        $this->date = new Carbon( $this->var->time_start );
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        //$d = new Carbon( $this->var->time_start );        
        return [
            new PrivateChannel('tennis-court-calendar-for-date-and-tennis-court-id.' . (string) $this->var->tennis_court_id . '.' . (string) $this->date->year . '.' . (string) $this->date->month),
        ];
    }

    public function broadcastWith(): array
    {
        return $this->getData()->toArray();
    }

    private function getData() {
        $tennisCourtCalendar = new TennisCourtCalendar();
        $tennisCourtCalendar = $tennisCourtCalendar->where('tennis_court_id', $this->var->tennis_court_id)
        ->whereMonth('time_start', $this->date->month)
        ->whereDay('time_start', $this->date->day );

        if ($this->tennisCourtCalendarIdToForceDelete){
            $tennisCourtCalendar = $tennisCourtCalendar->where('id', '!=',$this->tennisCourtCalendarIdToForceDelete);
        }
        
        return $tennisCourtCalendar->get();
    }
}
