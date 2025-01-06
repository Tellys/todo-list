<?php

namespace App\Http\Controllers\Api;

use App\Classes\NossaClasse;
use App\Http\Requests\RequestFrm;
use App\Http\Resources\CustomerRequestCollection;
use App\Models\CustomerRequest;

class CustomerRequestController extends BaseController
{
    public function __construct()
    {
        $this->Model = new CustomerRequest();

        $this->data['campos'] = $this->Model->campos;
        $this->NamefolderImage = 'customer-request';
        $this->PathsView = 'CustomerRequest';
        $this->PathsRoute = 'customer-request';
        $this->ClassModel = 'CustomerRequest';
        $this->listVars['meta'] = [
            'title' => 'Carrinho de Compras',
            'h1' => 'Carrinho de Compras',
        ];
        $this->listVars['uriBase'] = 'customer-request/';
        $this->listVars['heads'] = [
            'status',
            'price',
            'price_promo',
            'discount',
            'discount_justification',
            'discount_policy_id',
            'payment_method_id',
            'payment_log',
            'client_id',
            'user_id',
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
            'status',
            'price',
            'price_promo',
        ];
    }

    ///
    public function current($clientId, $returnJson = false)
    {
        try {
            $r = $this->Model::where([['client_id', $clientId], ['status', 'shopping']])->latest('updated_at')->first();

            if (!$returnJson) {
                return $r;
            }

            $this->message = 'Item retornado com sucesso';
            $this->status = 200;

            return $this->sendResponse($r, $this->message, $this->status);
        } catch (\Exception $e) {
            $this->message = $e; //$e->getMessage();
            $this->status = $e;
        }
        return $this->sendError($this->message, $this->status);
    }

    ///
    public function store(RequestFrm $request)
    {
        //chama a função para acrescimo da restrição das permissões, se for o caso.
        $this->isRestrict();

        $request->merge($this->myTraits($request));

        // validação dos dados
        $NossaClasse = new NossaClasse();
        $request->validate(
            $NossaClasse->colunasValidate($this->data['campos'])
        );

        $modelCreate = $this->Model;
        $modelUpdate = $this->Model;

        $thereIsAnOpenItem = $this->current($request->client_id);

        if ($thereIsAnOpenItem) {
            return tap($modelUpdate::findOrFail((int)$thereIsAnOpenItem->id))->update($request->all());
        }

        $user_id = auth('sanctum')->user()->id;
        $request->merge(['user_id' => $user_id]);

        $r = $request->except(['urlReturn']);

        return $modelCreate->create($r);
    }


    public function update(RequestFrm $request, $id)
    {
        try {
            //dd($request->all(), $id);
            $this->isRestrict();
            $except = ['urlReturn'];

            $request->merge($this->myTraits($request));

            // validação dos dados
            $NossaClasse = new NossaClasse();
            $request->validate(
                $NossaClasse->colunasValidate($this->data['campos'], $id)
            );

            $user_id = auth('sanctum')->user()->id;
            $request->merge(['user_id' => $user_id]);

            $r = $request->except($except);

            return $this->Model->findOrFail($id)->update($r);
            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $returnJson = true)
    {
        //chama a função para acrescimo da restrição das permissões, se for o caso.
        $this->isRestrict();

        try {
            $q = $this->Model->where('id', $id);

            if (!$q->count()) {
                throw new \Exception('Ocorreu um erro, verifique se o item para LIXEIRA ou tente novamente. Ou vocÊ não tem essa permissão!');
            }

            $q->delete();

            if (!$returnJson) {
                return true;
            }

            $this->status = 200;
            $this->message = 'Item para LIXEIRA com sucesso!';
            return $this->sendResponse('success', $this->message, $this->status);
        } catch (\Exception $e) {

            if (!$returnJson) {
                return $e;
            }

            $this->status = $e; //409;
            $this->message = $e; //$e->getMessage();
        }
        return $this->sendError($this->message, $this->status);
    }

    public function show($id)
    {       
        //chama a função para acrescimo da restrição das permissões, se for o caso.
        $this->isRestrict();

        try {
            $r = $this->Model->includeTablesRelations()->where('id', $id)->firstOrFail();
            $payment = $r['payment_method']['payment_method_controller'];
            $b = (new $payment())->Model::where('customer_request_id', $id)->latest()->first(['qrcode', 'txid', 'expires_in', 'image', 'status', 'end_to_end_id', 'created_at', 'updated_at', 'deleted_at']);
            
            if($b){
                $r['payment_method']['payment_selected'] = $b;
            }

            //$r['configSistems'] = ConfigSistem::all();
            $this->message = 'Item pesquisado com sucesso!';
            $this->status = 200;

            if (!$r) {
                $this->message = 'Item não localizado ou não existente em nosso banco de dados';
            }
            return $this->sendResponse($r, $this->message, $this->status);
        } catch (\Exception $e) {
            $this->status = $e;
            $this->message = $e;
        }

        return $this->sendError($this->message, $this->status);
    }

    ///
    public function all($returnJson = false)
    {
        try {
            $clientId = auth('sanctum')->user()->id;

            $r = $this->Model->where([['client_id', $clientId]])->orderBy('updated_at', 'DESC')->orderBy('status')->get();
            
            if ($returnJson) {
                return $r;
            }

            //dd($r);

            $r = new CustomerRequestCollection($r);
            
            $this->message = 'Listagem de pedidos retornada com sucesso';
            $this->status = 200;

            return $this->sendResponse($r, $this->message, $this->status);
        } catch (\Exception $e) {
            $this->message = $e; //$e->getMessage();
            $this->status = $e;
        }
        return $this->sendError($this->message, $this->status);
    }

    ///
    /**
     * $condtion = [id=1]
     * $typeOfReturn = bool|object|json - default = 'bool'
     */
    public function setIsPaid(array $condition, string $typeOfReturn = 'bool'){
        try {
            //dd($condition);
            $r = $this->Model->where([$condition])->includeTablesRelations()
            ->firstOrFail()
            ;
            $r->status = 'paid';
            $r->save();

            //dd($r->toArray());

            // tennis_court_calendar_id
            ////// Rotina 'paid' para 'tennis_court_calendar_id'
            // importante determinar todas as ações da reserva de fato
            if(!empty($r->cart) and $tennisCourtCalendarId = array_column($r->cart->toArray(), 'tennis_court_calendar_id')){
                (new TennisCourtCalendarController())->setIsPaid($tennisCourtCalendarId) ?? throw new \Exception('Ocorreu um erro ao atualziar tennis_court_calendar_id!');
            }

            // product_id
            ////// Rotina 'paid' para 'product_id'
            // importante determinar todas as ações da reserva de fato
            // tente ver o que se dá para aproveitar na rotina acima

            $this->currentCustomerRequest = $r;

            $this->return = $r;
            $this->status = 200;
            $this->message = 'Pedido atualizado como pago!';
            return $this->returnSuccess($typeOfReturn);

        } catch (\Exception $e) {
            $this->status = $e; //409;
            $this->message = $e; //$e->getMessage();
        }
        
        return $this->returnError($typeOfReturn);
    }

    public function setIsCanceled(array $condition, string $typeOfReturn = 'bool'){
        try {
            //dd($condition);
            $r = $this->Model->where([$condition])->where(['status','!=', 'paid'])->includeTablesRelations()->firstOrFail();
            $r->status = 'canceled';
            $r->save();

            // tennis_court_calendar_id
            ////// Rotina 'paid' para 'tennis_court_calendar_id'
            // importante determinar todas as ações da reserva de fato
            if(!empty($r->cart) and $tennisCourtCalendarId = array_column($r->cart->toArray(), 'tennis_court_calendar_id')){
                (new TennisCourtCalendarController())->setIsCanceled($tennisCourtCalendarId) ?? throw new \Exception('Ocorreu um erro ao atualziar tennis_court_calendar_id!');
            }

            // product_id
            ////// Rotina 'paid' para 'product_id'
            // importante determinar todas as ações da reserva de fato
            // tente ver o que se dá para aproveitar na rotina acima

            $this->currentCustomerRequest = $r;

            $this->return = $r;
            $this->status = 200;
            $this->message = 'Pedido cancelado!';
            return $this->returnSuccess($typeOfReturn);

        } catch (\Exception $e) {
            $this->status = $e; //409;
            $this->message = $e; //$e->getMessage();
        }
        
        return $this->returnError($typeOfReturn);
    }

    ///
    public function paid($id, $returnJson = true){
        try {
            $r = $this->Model->where('id', $id)->firstOrFail(['status', 'id']);
            $r->status = 'paid';
            $r->save();

            //dd($r);

            if (!$returnJson) {
                return true;
            }

            $this->status = 200;
            $this->message = 'Pedido atualizado como pago!';
            return $this->sendResponse('success', $this->message, $this->status);
        } catch (\Exception $e) {
            if (!$returnJson) {
                return $e;
            }

            $this->status = $e; //409;
            $this->message = $e; //$e->getMessage();
        }
        return $this->sendError($this->message, $this->status);
    }

    ///
    public function receiverPixWebhook($request) {
        return (new \App\Http\Controllers\Api\PaymentEfiPay\PaymentEfiPayPixWebhookController())->receiver($request);
    }
}
