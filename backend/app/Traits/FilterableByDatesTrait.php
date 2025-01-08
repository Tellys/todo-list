<?php
namespace App\Traits;

trait FilterableByDates
{
    public function scopeWhereDateBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween($this->getTable() . '.' . $this->getCreatedAtColumn(), [$startDate, $endDate]);
    }

    public function scopeWhereDateIs($query, $date)
    {
        return $query->where($this->getTable() . '.' . $this->getCreatedAtColumn(), $date);
    }
    public function scopeWhereDateIsBefore($query, $date)
    {
        return $query->where($this->getTable() . '.' . $this->getCreatedAtColumn(), '<', $date);
    }
    public function scopeWhereDateIsAfter($query, $date)
    {
        return $query->where($this->getTable() . '.' . $this->getCreatedAtColumn(), '>', $date);
    }
}

/*
https://medium.com/@miladev95/laravel-eloquent-models-how-to-filter-by-dates-like-a-pro-with-traits-91a5d63e1d1c
use Illuminate\Database\Eloquent\Model;
use FilterableByDates;

class User extends Model
{
    use FilterableByDates;
    protected $table = 'users';
}


// Get all of the users who have signed up in the past month.
$users = User::whereDateBetween('created_at', Carbon::now()->subMonth(), Carbon::now());

// Get all of the users who signed up on January 1, 2023.
$users = User::whereDateIs('created_at', '2023-01-01');
// Get all of the users who signed up before January 1, 2023.
$users = User::whereDateIsBefore('created_at', '2023-01-01');
// Get all of the users who signed up after January 1, 2023.
$users = User::whereDateIsAfter('created_at', '2023-01-01');
*/

?>