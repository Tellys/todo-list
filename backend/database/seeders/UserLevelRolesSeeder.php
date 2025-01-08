<?php

namespace Database\Seeders;

use App\Models\UserLevelRoles;
use App\Models\UsersLevel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Route;

class UserLevelRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserLevelRoles::insert($this->routeCollection());
    }

    function routeCollection()
    {
        //dd(Route::getRoutes());

        $routeCollection = Route::getRoutes();
        $created_at = now();
        $rr = [];

        foreach (UsersLevel::all() as $k => $v) {
            $action = [];

            foreach ($routeCollection as $value) {

                $action = 'none';
                $param = 0;

                if (
                    //$value->action['middleware'] != 'telescope'
                    !empty($value->action['controller'])
                    and is_array($value->action['middleware'])
                    and array_search('auth:sanctum', $value->action['middleware'])
                    // and is_int(strpos($value->action['controller'], 'App\Http\Controllers\\'))
                    // and !strpos($value->action['controller'], '@migrate')
                ) {

                    switch ($v->id) {
                            //level administr1ador
                        case 1:
                            $action = 'full';
                            break;

                            //level administr1ador
                        case 2:
                            $action = 'full';

                            // is_int(strpos(strtolower($value->action['controller']), '@delete'))
                            // or is_int(strpos(strtolower($value->action['controller']), '@restore'))
                            // or is_int(strpos(strtolower($value->action['controller']), '@forceDelete'))
                            // or is_int(strpos(strtolower($value->action['controller']), '@edit'))

                            // if (
                            //     is_int(strpos($value->action['controller'], 'BlogController'))
                            // ) {
                            //     $action = 'restrict';
                            //     $param = 'meu_level:>='; // vai pegar os levels menores também
                            // }

                            /* if (
                                is_int(strpos($value->action['controller'], 'UserLevel'))
                                or is_int(strpos(strtolower($value->action['controller']), '@forceDelete'))
                                or is_int(strpos(strtolower($value->action['controller']), '@migrate'))
                            ) {
                                $action = 'none';
                            } */
                            break;

                        default:

                            $action = 'restrict';

                            // casos de permissã com restrição
                            if (
                                
                                //TennisCourtController
                                is_int(strpos($value->action['controller'], 'TennisCourtController@search'))
                                or is_int(strpos($value->action['controller'], 'TennisCourtController@closeToMe'))
                                or is_int(strpos($value->action['controller'], 'TennisCourtController@filterItems'))

                                //UserController
                                or is_int(strpos($value->action['controller'], 'UserController@profile'))

                                //TennisCourtDescriptionController
                                or is_int(strpos($value->action['controller'], 'TennisCourtDescriptionController@index'))

                                //TennisCourtDescriptionTableController
                                or is_int(strpos($value->action['controller'], 'TennisCourtDescriptionTableController@index'))

                                //TennisCourtDescriptionTypeController
                                or is_int(strpos($value->action['controller'], 'TennisCourtTypeController@index'))

                                //or !is_int(strpos($value->action['controller'], 'LocationController'))
                                // TennisCourtOpeningHourController
                                // TennisCourtTypeController
                                // ProductDepartmentController
                            ) {
                                $action = 'full';
                                // echo '<br>{';
                                // echo '<br>name =' . $value->action['controller'];
                                // echo '<br>param =' . $param;
                                // echo '<br>users_level_id =' . $v->id;
                                // echo '<br>}<br>';

                            }

                            // casos de negativa
                            /* if (
                                is_int(strpos(strtolower($value->action['controller']), '@forceDelete'))
                            ) {
                                $action = 'none';
                            } */

                            break;
                    }

                    $rr[] = [
                        'name' => $value->action['controller'],
                        'users_level_id' => $v->id,
                        'user_id' => 1,
                        'created_at' => $created_at,
                        'action' => $action,
                        'param' => $param,
                    ];
                }
            }
        }
        return $rr;
    }
}
