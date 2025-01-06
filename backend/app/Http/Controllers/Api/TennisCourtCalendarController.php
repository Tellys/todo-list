<?php

namespace App\Http\Controllers\Api;

use App\Events\TennisCourtCalendarForDateAndTennisCourtIdEvent;
use App\Http\Requests\RequestFrm;
use App\Models\TennisCourtCalendar;
use App\Models\TennisCourtOpeningHour;

class TennisCourtCalendarController extends BaseController
{
    public function __construct()
    {
        $this->Model = new TennisCourtCalendar();

        $this->data['campos'] = $this->Model->campos;
        $this->NamefolderImage = 'tennis-court-calendar';
        $this->PathsView = 'tennisCourtCalendar';
        $this->PathsRoute = 'tennis-court-calendar';
        $this->ClassModel = 'TennisCourtCalendar';
        $this->listVars['meta'] = [
            'title' => 'Lista de Datas',
            'h1' => 'Lista de Datas',
        ];
        $this->listVars['uriBase'] = 'tennis-court-calendar/';
        $this->listVars['heads'] = [
            'tennis_court_id',
            'date_time_start',
            'date_time_end',
            'status',
            'user_id',
            'created_at',
        ];

        $this->comandos = [
            //'show'=>[$this->PathsRoute.'.show', 'user_id'],
            'edit' => [$this->PathsRoute . '.edit', 'id'],
            'destroy' => [$this->PathsRoute . '.destroy', 'id'],
            'forceDelete' => [$this->PathsRoute . '.forceDelete', 'id'],
            'restore' => [$this->PathsRoute . '.restore', 'id']
        ];

        $this->searchParams = [
            'tennis_court_id',
            'date_time_start',
            'date_time_end',
            'user_id',
            'status',
        ];
    }

    private function diffDates($data)
    {

        //return $data->all();
        $datatime1 = new \DateTime($data->dateStart . ' ' . $data->timeStart);
        $datatime2 = new \DateTime($data->dateStart . ' ' . $data->timeEnd);

        $data1  = $datatime1->format('Y-m-d H:i:s');
        $data2  = $datatime2->format('Y-m-d H:i:s');

        $diff = $datatime1->diff($datatime2);
        return $diff->h + ($diff->days * 24);
    }

    ///
    public function checkDateIsEnable($tennis_court_id, $date)
    {
        return TennisCourtOpeningHour::isEnable(['tennis_court_id' => $tennis_court_id, 'date' => $date], true);
    }

    ///
    public function checkDateIsFree($tennis_court_id, $date): bool
    {
        //$date = date('Y-m-d H:i', strtotime($date));
        return
            !$this->Model
                ::where(
                    [
                        ['tennis_court_id', '=', $tennis_court_id],
                        ['time_start', $date],
                        //['user_id', auth('sanctum')->user()->id],
                    ]
                )
                // ->where('status', 'paid')
                // ->where('status', 'awaiting_payment')
                /* ->where(
                function ($query) use ($tennis_court_id, $date) {
                    return $query
                        ->where('tennis_court_id', $tennis_court_id)
                        //->whereDate('time_start', $date)
                        ;
                }
            ) */
                ->where(
                    function ($query) {
                        return $query
                            ->orWhere('status', 'paid')
                            ->orWhere('status', 'awaiting_payment');
                    }
                )
                //->toSql()
                //->count() ? 'false' : 'true'
                ->count()
            //->get()
            //->toArray()

            //  ? false : true
        ;
    }

    ///
    public function index()
    {
        $day = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

        $nItems = 50;
        $i = 1;
        $nn = [];
        while ($i <= $nItems) {

            $statusArray = $this->data['campos']['status']['html']['options']; // ['in_cart','awaiting payment', 'paid', 'canceled'],
            $status = array_rand($statusArray);

            //$dayArrayRand = array_rand($day);

            $j = 0;
            //while ($j <= $day[$dayArrayRand]) {
            while ($j <= count($day)) {
                $randDay = rand(0, 5);
                $randHour = rand(7, 20);

                $date = date("Y-m-d");
                $timeStart = date('Y-m-d H:i:s', strtotime('+' . $randDay . ' days +' . $randHour . ' hours', strtotime($date)));
                $timeEnd = date('Y-m-d H:i:s', strtotime('+1 hours', strtotime($timeStart)));

                $nn[] = [
                    'tennis_court_id' => (int) $i,
                    'user_id' => (int) rand(1, 4),
                    'time_start' => $timeStart,
                    'time_end' => $timeEnd,
                    'status' => $statusArray[$status],
                    'created_at' => now(),
                ];
                $j++;
            }
            $i++;
        }

        dd($nn);
    }

    ///
    public function store(RequestFrm $request)
    {
        //chama a função para acrescimo da restrição das permissões, se for o caso.
        $this->isRestrict();
        $q = [];
        $erros = '';
        $r = [];

        try {

            foreach ($request->all() as $key => $value) {

                // autorizará o create no DB
                $create = true;

                if (!$this->checkDateIsEnable($value['tennis_court_id'], $value['time_start'])) {
                    $erros .= "<br/>";
                    $erros .= 'A quadra estará fechada nesta data e horário [ ' . $value['time_start'] . ' ], verifique os horários de funcionamento';
                    $create = false;
                }

                //  dd(Carbon::parse($value['time_start'])->timestamp, $this->checkDateIsFree($value['tennis_court_id'], $value['time_start']), !$this->checkDateIsFree($value['tennis_court_id'], $value['time_start']));

                if (!$this->checkDateIsFree($value['tennis_court_id'], $value['time_start'])) {
                    $erros .= "<br/>";
                    $erros .= 'Data e horário já ocupados [' . $value['time_start'] . ' / ' . $value['time_end'] . ']';
                    $create = false;
                }

                if ($create) {
                    $user_id = auth('sanctum')->user()->id;

                    $r = [
                        'user_id' => $user_id,
                        'time_start' => $value['time_start'],
                        'time_end' => $value['time_end'],
                        'tennis_court_id' => $value['tennis_court_id'],
                        'created_at' => now(),
                        'expires_at' => $this->getDateToCartExpiresAt()
                    ];

                    $erros .= "<br/>";
                    $erros .= 'Agendamento realizado com sucesso! [' . $value['time_start'] . ' / ' . $value['time_end'] . ']';
                }
            }

            if (!count($r)) {
                return throw new \Exception($erros, 400);
            }

            $q = $this->Model->insert($r);

            $this->return = [$q, $r];
            $this->status = 200;
            $this->message = $erros;
            return $this->returnSuccess();
        } catch (\Exception $e) {
            $this->message = $e; //$e->getMessage();
            $this->status = $e;
        }

        return $this->returnError();
    }

    ///
    public function listItemsToTennisCourtId($id)
    {
        try {
            $q = $this->Model
                ->where('tennis_court_id', $id)
                ->where('status', '!=', 'canceled')
                //->toSql();
                ->get();

            //dd($q);

            $this->status = 200;
            $this->message = 'Itens listados com sucesso!';

            return $this->sendResponse($q, $this->message, $this->status);
        } catch (\Exception $e) {
            $this->status = $e;
            $this->message = $e;
        }

        return $this->sendError($this->message, $this->status);
    }

    ///
    // public function setIsPaid(array $listIds, string $typeOfReturn = 'bool')
    // {
    //     try {
    //         //dd($condition);
    //         $r = $this->Model->whereIn('id', $listIds)->includeTablesRelations();
    //         $r->update(['status' => 'paid']);

    //         // evento para sinalização do item pago na lista de horários na pagina '/tennis-court/{id}'
    //         foreach ($r->get() as $key => $value) {
    //             if (!empty($value)) {
    //                 broadcast(new TennisCourtCalendarForDateAndTennisCourtIdEvent($value));
    //             }
    //         }

    //         $this->return = $r;
    //         $this->status = 200;
    //         $this->message = 'Horário atualizado como pago!';
    //         return $this->returnSuccess($typeOfReturn);
    //     } catch (\Exception $e) {
    //         $this->status = $e; //409;
    //         $this->message = $e; //$e->getMessage();
    //     }

    //     return $this->returnError($typeOfReturn);
    // }

    ///
    // public function setIsCanceled(array $listIds, string $typeOfReturn = 'bool')
    // {
    //     try {
    //         //dd($condition);
    //         $r = $this->Model->whereIn('id', $listIds)->where(['status', '!=', 'paid'])->includeTablesRelations();
    //         $r->update(['status' => 'canceled']);

    //         $this->return = $r;
    //         $this->status = 200;
    //         $this->message = 'Horário cancelado!';
    //         return $this->returnSuccess($typeOfReturn);
    //     } catch (\Exception $e) {
    //         $this->status = $e; //409;
    //         $this->message = $e; //$e->getMessage();
    //     }

    //     return $this->returnError($typeOfReturn);
    // }

}
