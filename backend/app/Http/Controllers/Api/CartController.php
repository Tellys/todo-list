<?php

namespace App\Http\Controllers\Api;

use App\Events\TennisCourtCalendarForDateAndTennisCourtIdEvent;
use App\Http\Requests\RequestFrm;
use App\Models\Cart;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class CartController extends BaseController
{
    private $cart = null;
    private $price = null;
    private $pricePromo = null;
    private $tennisCourtCalendarInCart = null;

    public function __construct()
    {
        $this->Model = new Cart();

        $this->data['campos'] = $this->Model->campos;
        $this->NamefolderImage = 'cart';
        $this->PathsView = 'cart';
        $this->PathsRoute = 'cart';
        $this->ClassModel = 'Cart';
        $this->listVars['meta'] = [
            'title' => 'Carrinho de Compras',
            'h1' => 'Carrinho de Compras',
        ];
        $this->listVars['uriBase'] = 'cart/';
        $this->listVars['heads'] = [
            'status',
            'tennis_court_calendar_id',
            'product_id',
            'product_name',
            'qty',
            'price',
            'price_promo',
            'discount',
            'discount_justification',
            'payment_log',
            'client_id',
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
            'id',
            'product_name',
            'payment_log',
            'discount_justification',
        ];
    }

    /// 
    private function getLastCartValid()
    {
        return $this->cart = $this->Model->getLastCartValid();
    }

    ///
    private function checkValueCart($value) //:bool
    {
        $cart = $this->Model::includeTablesRelations()->getLastCartValid();

        $price = $cart;
        $pricePromo = $cart;
        $this->cart = $cart->get()->toArray();

        // no caso de carrinho vazio
        if (!$this->cart) {
            throw new \Exception('Carrinho Vazio!, ', 400);
        }

        $this->pricePromo = $pricePromo->getSunCart();
        $this->price = $price->sum('price');

        // compara os preços vindo do formulário com o do banco (proteção contra manipulação externa do html)
        if ($value != $this->pricePromo) {
            return false;
        }

        return true;
    }

    private function createCustomerRequest(RequestFrm $request, $status): \App\Models\CustomerRequest
    {
        $customerRequest = (new CustomerRequestController())->store($request);

        // aqui atualizamos os itens do carrinho com 'customer_request_id'
        $cart = $this->Model->getLastCartValid();

        $cartItems = $cart->get(['id', 'product_id', 'tennis_court_calendar_id'])
        ->toArray()
        ;

        $cartItemsTennisCourtCalendarId = array_column($cartItems, 'tennis_court_calendar_id', 'id');
        $cartItemsTennisCourtCalendarId = array_values($cartItemsTennisCourtCalendarId);
        // var interna da func
        $tennisCourtCalendarInCart = \App\Models\TennisCourtCalendar::whereIn('id', $cartItemsTennisCourtCalendarId);
        
        // set var da class
        // importante pois precisaremos destes items na linha 'function pay(){} -> if ($paymentMethodSelected['http_code'] != 200)'
        $tennisCourtCalendarInCart->update(['status'=>$status]);

        $this->tennisCourtCalendarInCart = $tennisCourtCalendarInCart->get();

        // // implemtar rotina de atualização de status de produtos, quando for o caso
        // $cartItemsProductId = array_column($cartItems, 'product_id', 'id');
        // $cartItemsProductId = array_values($cartItemsProductId);
        
        $cart->update(['customer_request_id' => $customerRequest->id]);

        return \App\Models\CustomerRequest::where([['id', $customerRequest->id]])->includeTablesRelations()->first();
    }

    function pay(RequestFrm $request)
    {
        $status = 'awaiting_payment';
        try {
            // checa o valor do carrinho com o valor que esta sendo aplicado nesta requisição
            // checa se o carrinho esta vazio 
            if (!$this->checkValueCart($request->total_price)) {
                return throw new \Exception('Não conseguimos validar seu carrinho de compras, tente novamente!, ', 400);
            }

            $paymentClass = (string) Str::camel($request->payment_method_type);
            $request->merge([
                'price_promo' => $this->pricePromo,
                'price' => $this->price,
                'client_id' => $this->cart[0]['client_id'],
                'status' => $status,
            ]);

            //dd($request->all());

            $request->replace($request->except(['payment_method_type', 'total_price'])); //remove request param

            // ? Preciso confirmar os preços? se sim, precisarei de implementar a function confirmPriceAndStock()
            $customerRequest = $this->createCustomerRequest($request, $status); // returna obj \App\Models\CustomerRequest
            //dd($customerRequest->toArray());

            $request->merge($customerRequest->toArray());

            //dd($paymentClass, $request);
            // 1) Aciona a forma de pagamento escolhinda no carrinho
            $paymentMethodSelected = (new PaymentMethodController())->{$paymentClass}($request);

            // 2) É retornado $paymentMethodController com os dados do pagamento escolhido (ex: no caso pix, retorna o pix cod e img):
            // cart 'options' => ['shopping', 'paid', 'abandoned', 'canceled', 'waiting'], // comprando, pago, pagamento recusado, abandonado, cancelado, waiting for external response
            // no cart foi retirado o 'paymentRefused' para poder manter os produtos no carrinho e dar oportunidade de novas tentativas de pagamento
            // customerRequest 'options' => ['shopping', 'paid', 'paymentRefused', 'abandoned', 'canceled', 'waiting'], // comprando, pago, pagamento recusado, abandonado, cancelado, waiting for external response

            // no caso do pix, 'http_code' != 200 pode ser qualquer coisa diferente de pagar
            // [não implementado ainda] no caso do cartão de credito AVALIAR um novo if

            // se o pagamento retonar 'http_code' != 200
            // - finalizar o pedido atual para controle do fluxo
            // - criar um novo para oportunizar nova tentativa de pagamento

            if ($paymentMethodSelected['http_code'] != 200) {
                $status = 'canceled';

                // cancela o pedido
                // o cliente terá a opção de ir ao carrinho e iniciar o novo pedido assim q escolhar a forma de pagamento
                $customerRequest->status = $status;
                $customerRequest->deleted_at = now();
                $customerRequest->save();

                $camposWhitOutId = $this->data['campos'];
                //$camposWhitOutId['deleted_at'] = null;
                $camposWhitOutId['created_at'] = null;
                $camposWhitOutId['updated_at'] = null;

                unset(
                    $camposWhitOutId['id'],
                    $camposWhitOutId['status'],
                    // $camposWhitOutId['tennis_court_calendar_id'],
                    // $camposWhitOutId['product_id'],
                    // $camposWhitOutId['product_name'],
                    // $camposWhitOutId['qty'],
                    // $camposWhitOutId['price'],
                    // $camposWhitOutId['price_promo'],
                    // $camposWhitOutId['discount'],
                    // $camposWhitOutId['discount_justification'],
                    // $camposWhitOutId['discount_policy_id'],
                    // $camposWhitOutId['customer_request_id'],
                    // $camposWhitOutId['client_id'],
                    // $camposWhitOutId['user_id'],
                );
                $camposWhitOutId = array_keys($camposWhitOutId);

                // get status and save into DB
                $newCart = $this->Model->getLastCartValid()->get($camposWhitOutId)->toArray();

                //dd((string) trim($camposWhitOutId));
                $this->Model->getLastCartValid()->update([
                    //'status'=>$status, // não mais necessário. O controle passa a ser o softdelete
                    //'customer_request_id'=>$customerRequest->id,
                    'deleted_at' => now(),
                ]);

                $this->tennisCourtCalendarInCart->update(['status'=>$status]);

                // duplica o pedido para eventual insistencia em nova tentativa de pagamento
                // caso o carrinho vença.. que se aplique as regras normais
                $this->Model->insert($newCart);

                // após cancelar o pedido
                // duplicaremos o carrinho, voltando ele para a nova seleção da força de pagamento, mostrando o erro acontecido
                // com o carrinho duplicado, o cliente pode:
                // - continuar 
                // - deixar vencer o carrinho
                // - limpar (cancelar) o carrinho todo
                return throw new \Exception($paymentMethodSelected['return'] ?? 'Erro, tente novamente!', $paymentMethodSelected['http_code']);
            }

            //dd($paymentMethodSelected);

            // // 3) 
            // // update tennis_court_calendars horarios das quadras para reservado
            // // update no estoque e faturamento das demais compras
            // // atenção para o 
            // // - product_id = 1 ele é um produto default q deve ser ignorado, pois foi criado apenas para a questão das relações de tabelas
            // // - discount_policy_id o mesmo acima
            // $this->cart = tap($this->Model->getLastCartValid())->update([
            //     'status' => $status,
            // ]);

            //dd($this->cart);
            $customerRequest->toArray();
            
            $customerRequest['payment_method']['payment_selected'] = $paymentMethodSelected;

            //dd($customerRequest->toArray(),$this->tennisCourtCalendarInCart);

            // aqui a '$this->tennisCourtCalendarInCart' esta sendo redefinida em '$this->createCustomerRequest()'
            // aqui o loop é necessário pois poderá acontecer de termos datas diferentes e o 'TennisCourtCalendarForDateAndTennisCourtIdEvent()' é baseado na data
            foreach ($this->tennisCourtCalendarInCart as $key => $value) {
                broadcast(new TennisCourtCalendarForDateAndTennisCourtIdEvent($value));
            }

            return $this->sendResponse(
                $customerRequest,
                // [
                //     'customer_request' => $customerRequest,
                //     'payment_method_selected' => $paymentMethodSelected,
                // ],
                'Forma de pagamento Selecionada e pronta para quitação'
            );
        } catch (\Exception $e) {
            $this->status = $this->message = $e;
        }

        return $this->sendError($this->message, $this->status);
    }

    function addItemTennisCourt(RequestFrm $request)
    {
        //dd($request->all());
        //dd(date('Y-m-d'));

        //chama a função para acrescimo da restrição das permissões, se for o caso.
        $this->isRestrict();
        $q = [];
        $erros = '';
        $r = [];
        $tennisCourtCalendarController = new TennisCourtCalendarController();
        $tennisCourtCalendar = [];
        $arrayCreate = [];

        $user_id = auth('sanctum')->user()->id;
        $dateToDb = now();

        foreach ($request->tennisCourtOpeningHour as $key => $tennisCourtOpeningHourRequest) {

            // autorizará o create no DB
            $create = true;

            //$tennisCourtOpeningHour = $request->tennisCourtOpeningHour[$key];
            $cart = $request->cart[$key];

            //dd($tennisCourtOpeningHour, $cart);
            //dd((string) $tennisCourtOpeningHour['tennis_court_id'], $tennisCourtOpeningHour['time_start']);
            $tennisCourtOpeningHour = $tennisCourtCalendarController->checkDateIsEnable($tennisCourtOpeningHourRequest['tennis_court_id'], $tennisCourtOpeningHourRequest['time_start'])->with('tennis_court')->first();
            //dd($tennisCourtOpeningHour->price);
            if (!$tennisCourtOpeningHour) {
                $erros .= empty($erros) ? '' : "<br/>";
                $erros .= 'A quadra estará fechada nesta data e horário <br/>[ ' . date('d-m-Y H:i', strtotime($tennisCourtOpeningHourRequest['time_start'])) . ' / ' . date('d-m-Y H:i', strtotime($tennisCourtOpeningHourRequest['time_end'])) . ' ], verifique os horários de funcionamento';
                $create = false;
            }

            if (!$tennisCourtCalendarController->checkDateIsFree($tennisCourtOpeningHourRequest['tennis_court_id'], $tennisCourtOpeningHourRequest['time_start'])) {
                $erros .= empty($erros) ? '' : "<br/>";
                $erros .= 'Data e horário já ocupados <br/>[' . date('d-m-Y H:i', strtotime($tennisCourtOpeningHourRequest['time_start'])) . ' / ' . date('d-m-Y H:i', strtotime($tennisCourtOpeningHourRequest['time_end'])) . ']';
                $create = false;
            }

            if ($create) {

                // é necessário criar o TennisCourtCalendar primeiro pois
                // para criar o cart, será necessário o 'tennis_court_calendar_id', que será gerado aqui
                $tennisCourtCalendar = \App\Models\TennisCourtCalendar::create(
                    [
                        'user_id' => $user_id, 
                        'time_start' => $tennisCourtOpeningHourRequest['time_start'],
                        'time_end' => $tennisCourtOpeningHourRequest['time_end'],
                        'tennis_court_id' => $tennisCourtOpeningHour->tennis_court->id,
                        'tennis_court_opening_hour_id' => $tennisCourtOpeningHour->id,
                        'created_at' => $dateToDb,
                        'updated_at' => $dateToDb,
                        'expires_at' => $this->purchaseExpirationTime($tennisCourtOpeningHour->purchase_expiration_time),
                    ]
                );

                $arrayCreate[] = [
                    'user_id' => $user_id,
                    'tennis_court_calendar_id' => $tennisCourtCalendar->id,
                    'product_name' => $tennisCourtOpeningHour->tennis_court->name . ' (id:' . $tennisCourtOpeningHour->tennis_court->id . ') [' . date('d-m-Y H:i', strtotime($tennisCourtOpeningHourRequest['time_start'])) . ' / ' . date('d-m-Y H:i', strtotime($tennisCourtOpeningHourRequest['time_end'])),
                    'qty' => $cart['qty'],
                    'price' => $tennisCourtOpeningHour->price, //$cart['price'],
                    'price_promo' => $tennisCourtOpeningHour->price_promo, //$cart['price_promo'],
                    'client_id' => $cart['client_id'],
                    'created_at' => $dateToDb,
                    'updated_at' => $dateToDb,
                ];

                $erros .= empty($erros) ? '' : "<br/>";
                $erros .= 'Agendamento realizado com sucesso!<br/> [' . date('d-m-Y H:i', strtotime($tennisCourtOpeningHourRequest['time_start'])) . ' / ' . date('d-m-Y H:i', strtotime($tennisCourtOpeningHourRequest['time_end'])) . ']';
            }
        }

        //dd($arrayCreate);
        
        $this->Model->insert($arrayCreate);

        if (!$tennisCourtCalendar) {
            return $this->sendError($erros, 400);
        }

        broadcast(new TennisCourtCalendarForDateAndTennisCourtIdEvent($tennisCourtCalendar));
        $this->message = $erros;
        $this->status = 200;

        return $this->sendResponse([], $this->message, $this->status);
    }

    function confirmPriceAndStock() {}

    function store(RequestFrm $request)
    {
        /* $r = $this->Model->updateOrCreate([
            //Add unique field combo to match here
            //For example, perhaps you only want one entry per user:
            'client_id'   => auth('sanctum')->user()->id,
        ],[
            'about'     => $request->get('about'),
            'sec_email' => $request->get('sec_email'),
            'gender'    => $request->get("gender"),
            'country'   => $request->get('country'),
            'dob'       => $request->get('dob'),
            'address'   => $request->get('address'),
            'mobile'    => $request->get('cell_no')
        ]); */
    }

    public function all($id = null)
    {
        //chama a função para acrescimo da restrição das permissões, se for o caso.
        $this->isRestrict();

        try {
            //$user = auth('sanctum')->user();

            $q = $this->Model->includeTablesRelations()->getLastCartValid();

            $r = $q->get();

            $this->message = 'Itens do carrinho!';
            $this->status = 200;

            if (!$r->count()) {
                $this->message = 'Carrinho Vazio';
            }

            $r['total_price'] = $q->getSunCart();

            return $this->sendResponse($r, $this->message, $this->status);
        } catch (\Exception $e) {
            $this->status = $e;
            $this->message = $e;
        }

        return $this->sendError($this->message, $this->status);
    }

    public function show($id)
    {
        //chama a função para acrescimo da restrição das permissões, se for o caso.
        $this->isRestrict();

        try {
            $user = auth('sanctum')->user();

            $q = $this->Model->includeTablesRelations();

            $r = $q
                ->where('client_id', $user->id)
                ->where('status', 'shopping')
                ->get();

            $this->message = 'Itens do carrinho!';
            $this->status = 200;

            if (!$r->count()) {
                $this->message = 'Carrinho Vazio';
            }

            $r['total_price'] = $q->getSunCart();

            return $this->sendResponse($r, $this->message, $this->status);
        } catch (\Exception $e) {
            $this->status = $e;
            $this->message = $e;
        }

        return $this->sendError($this->message, $this->status);
    }

    public function forceDelete(RequestFrm $request, $id)
    {
        //chama a função para acrescimo da restrição das permissões, se for o caso.
        $this->isRestrict();

        try {
            $q = $this->Model->withTrashed();

            if (is_numeric($request->id)) {
                $q = $q->where($request->collumn ?? 'id', $request->id);
                //$q = $q->findOrFail($request->id);
            } elseif (!empty($id)) {
                $q = $q->where($request->collumn ?? 'id', $id);
            }

            if (!$q->count()) {
                throw new \Exception('Nenhum item localizado');
            }

            $q2 = $q->firstOrFail();

            $tennisCourtCalendar = \App\Models\TennisCourtCalendar::find($q2->tennis_court_calendar_id);

            broadcast(new TennisCourtCalendarForDateAndTennisCourtIdEvent($tennisCourtCalendar, $tennisCourtCalendar->id));

            $tennisCourtCalendar->forceDelete();
            $q->forceDelete();

            $this->status = 200;
            $this->message = 'Item REMOVIDO permanentemente!';

            return $this->sendResponse($q, $this->message, $this->status);
        } catch (\Exception $e) {
            $this->status = $e; //409;
            $this->message = $e; //$e->getMessage();
        }

        return $this->sendError($this->message, $this->status);
    }

    public function purchaseExpirationTime($expirationTime)
    {
    
        try {
            $times = explode(':', $expirationTime);
            $hours = (int) $times[0];
            $minutes = (int)$times[1];
            $seconds = (int)$times[2];

            return Carbon::now()
                ->addHours($hours)
                ->addMinutes($minutes)
                ->addSeconds($seconds);
        } catch (\Exception $e) {
            return $this->getDateToCartExpiresAt();
        }
    }
}
