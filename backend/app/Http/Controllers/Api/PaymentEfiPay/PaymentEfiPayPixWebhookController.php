<?php

namespace App\Http\Controllers\Api\PaymentEfiPay;

use App\Classes\NossaClasse;
use App\Events\PaymentPixIsPayEvent;
use App\Http\Requests\RequestFrm;
use App\Models\PaymentEfiPayPixWebhook;
use Efi\Exception\EfiException;
use Efi\EfiPay;

class PaymentEfiPayPixWebhookController extends PaymentEfiPayController
{
    private $constantsPaymentEfiPayPixChave;

    ///
    public function __construct()
    {
        $this->Model = new PaymentEfiPayPixWebhook();

        $this->data['campos'] = $this->Model->campos;
        $this->NamefolderImage = 'payment-efi-pay-pix-webhook';
        $this->PathsView = 'PaymentEfiPayPixWebhook';
        $this->PathsRoute = 'payment-efi-pay-pix-webhook';
        $this->ClassModel = 'PaymentEfiPayPixWebhook';
        $this->listVars['meta'] = [
            'title' => 'Sistema de Cobrança EfiPay Webhook',
            'h1' => 'Sistema de Cobrança EfiPay Webhook',
        ];
        $this->listVars['uriBase'] = 'payment-efi-pay-pix-webhook/';
        $this->listVars['heads'] = [
            'http_status',
            'json',
            'customer_request_id',
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
            'http_status',
            'json',
            'customer_request_id',
        ];

        $this->getVarsApiPix();

        $this->constantsPaymentEfiPayPixChave = (new PaymentEfiPayPixKeyController())->getMyPixKey(false);
        $this->constantsPaymentEfiPayPixChave = $this->constantsPaymentEfiPayPixChave['key'] ?? null;
    }

    function index()
    {
        //return $this->sendResponse(true, 'success');
    }


    public function receiver($request)
    {
        //return true;

        // pix nao pago
        //$body = '{"pix":[{"endToEndId":"9510dcea14264ab8b13ca1c8a2c03aba","txid": "9510dcea14264ab8b13ca1c8a2c03aba","chave": "bc34a16c-fecc-4f40-8d8c-d6fa1df38328","valor": "0.01","horario": "2024-09-05T17:05:57.000Z"}]}';
        // eyJwaXgiOlt7ImVuZFRvRW5kSWQiOiI5NTEwZGNlYTE0MjY0YWI4YjEzY2ExYzhhMmMwM2FiYSIsInR4aWQiOiAiOTUxMGRjZWExNDI2NGFiOGIxM2NhMWM4YTJjMDNhYmEiLCJjaGF2ZSI6ICJiYzM0YTE2Yy1mZWNjLTRmNDAtOGQ4Yy1kNmZhMWRmMzgzMjgiLCJ2YWxvciI6ICIwLjAxIiwiaG9yYXJpbyI6ICIyMDI0LTA5LTA1VDE3OjA1OjU3LjAwMFoifV19

        // // pix Pago
        // $body = '{"pix":[{"endToEndId":"E0000000020240905170531823639355","txid": "0f42202d09c1454289b62f144ced5217","chave": "bc34a16c-fecc-4f40-8d8c-d6fa1df38328","valor": "0.01","horario": "2024-09-05T17:05:57.000Z"}]}';
        // eyJwaXgiOlt7ImVuZFRvRW5kSWQiOiJFMDAwMDAwMDAyMDI0MDkwNTE3MDUzMTgyMzYzOTM1NSIsInR4aWQiOiAiMGY0MjIwMmQwOWMxNDU0Mjg5YjYyZjE0NGNlZDUyMTciLCJjaGF2ZSI6ICJiYzM0YTE2Yy1mZWNjLTRmNDAtOGQ4Yy1kNmZhMWRmMzgzMjgiLCJ2YWxvciI6ICIwLjAxIiwiaG9yYXJpbyI6ICIyMDI0LTA5LTA1VDE3OjA1OjU3LjAwMFoifV19

        // $body = '{"pix":[{"endToEndId":"E0000000020240905170531823639355","txid": "b6c4955bbc8943929af4a3e83724c8ec","chave": "bc34a16c-fecc-4f40-8d8c-d6fa1df38328","valor": "0.01","horario": "2024-09-05T17:05:57.000Z"}]}';
        // // // eyJwaXgiOlt7ImVuZFRvRW5kSWQiOiJFMDAwMDAwMDAyMDI0MDkwNTE3MDUzMTgyMzYzOTM1NSIsInR4aWQiOiAiZjUwOTBkOWY4M2E1NGE3MmI2ODM1NWM2ZmRiZDU4MWIiLCJjaGF2ZSI6ICJiYzM0YTE2Yy1mZWNjLTRmNDAtOGQ4Yy1kNmZhMWRmMzgzMjgiLCJ2YWxvciI6ICIwLjAxIiwiaG9yYXJpbyI6ICIyMDI0LTA5LTA1VDE3OjA1OjU3LjAwMFoifV19
        // $request = base64_encode($body);
        // dd($request);
        // //// para fezer o teste GET descomente estas duas linhas
        $request = json_decode(base64_decode($request));
        $request = (array) $request->pix[0];

        try{
            // verifica checkPaymentIsMade = return boll
            $paymentEfiPayPixCob = new PaymentEfiPayPixCobController();

            // check Payment Is Made in bank, return = boll
            // case error, 'checkPaymentIsMade()' ira disparar uma 'Exception', nao preocupar com return false
            // return true = se o pagamento tiver sido realizado
            // return false = ao contrario acima
            if(!$paymentEfiPayPixCob->checkPaymentIsMade($request['txid'], false)){
                return false;
            }

            // // get Current Payment Efi Pay Pix Cob
            $this->paymentEfiPayPixCob = $paymentEfiPayPixCob->paymentEfiPayPixCob;
            $this->currentCustomerRequest = $paymentEfiPayPixCob->currentCustomerRequest;
            // dd($this->paymentEfiPayPixCob,  $this->currentCustomerRequest);

            $r =  $this->currentCustomerRequest->toArray();
            $r['payment_method']['payment_selected'] = $this->paymentEfiPayPixCob->toArray();
            unset($r['payment_metxhod']['payment_selected']['customer_request']);

            // return $r;
            //dd($this->currentCustomerRequest, $this->currentCustomerRequest->cart, $r);
    
            // event para quitação do pagamento
            broadcast(new PaymentPixIsPayEvent($r));

            //dd($this->currentCustomerRequest, $this->currentCustomerRequest->cart, $r);

            return true;
        } catch (\Exception $e) {
            //dd($e);
            return 'Erro: '.$e->getMessage();
        }
    }
    ///
    public function config()
    {

        $return = false;

        /**
         * Detailed endpoint documentation
         * https://dev.efipay.com.br/docs/api-pix/webhooks#configurar-o-webhook-pix
         */

        $options = $this->constantsPaymentEfiPayPix;

        $options["headers"] = [
            "x-skip-mtls-checking" => false
        ];

        $params = [
            "chave" => $this->constantsPaymentEfiPayPixChave
        ];

        $body = [
            "webhookUrl" => env('WEBHOOK_URL')
        ];

        //dd($options,$params,$body );

        try {
            $api = new EfiPay($options);
            $response = $api->pixConfigWebhook($params, $body);

            //dd($response);

            $return = [
                'http_code' => 200,
                'return' => [
                    'response' => $response,
                ],
                'extra' => ['Content-type' => 'application/json; charset=utf-8'],
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
            ];

            return $this->sendResponse($return, 'success');
        } catch (EfiException $e) {
            $return = [
                'http_code' => $e->getCode(),
                'return' => $e->getMessage(),
                'extra' => ['Content-type' => 'application/json; charset=utf-8'],
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
            ];

            return $this->sendError($e, $e);
        } catch (\Exception $e) {
            $return = [
                'http_code' => 400,
                'return' => $e,
                'extra' => ['Content-type' => 'application/json; charset=utf-8'],
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
            ];
            return $this->sendError($e, $e);
        }
    }

    ///
    public function detail(string $chave = 'bc34a16c-fecc-4f40-8d8c-d6fa1df38328' )
    {

        $return = false;
        $options = $this->constantsPaymentEfiPayPix;
        $params = [
            //"chave" => $chave ?? $this->constantsPaymentEfiPayPixChave
            "chave" => $chave
        ];

        try {
            $api = new EfiPay($options);
            $response = $api->pixDetailWebhook($params);

            $return = [
                'http_code' => 200,
                'return' => [
                    'response' => $response,
                ],
                'extra' => ['Content-type' => 'application/json; charset=utf-8'],
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
            ];

            return $this->sendResponse($return, 'success');
        } catch (EfiException $e) {
            $return = [
                'http_code' => $e->getCode(),
                'return' => $e->getMessage(),
                'extra' => ['Content-type' => 'application/json; charset=utf-8'],
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
            ];

            return $this->sendError($e, $e);
        } catch (\Exception $e) {
            $return = [
                'http_code' => 400,
                'return' => $e,
                'extra' => ['Content-type' => 'application/json; charset=utf-8'],
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
            ];
            return $this->sendError($e, $e);
        }
    }

    ///
    public function delete(string $chave = 'bc34a16c-fecc-4f40-8d8c-d6fa1df38328' )
    {

        $return = false;
        $options = $this->constantsPaymentEfiPayPix;

        $params = [
            "chave" => $chave
        ];

        try {
            $api = new EfiPay($options);
            $response = $api->pixDeleteWebhook($params);

            $return = [ 
                'http_code' => 200,
                'return' => [
                    'response' => $response,
                ],
                'extra' => ['Content-type' => 'application/json; charset=utf-8'],
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
            ];

            return $this->sendResponse($return, 'success');
        } catch (EfiException $e) {
            $return = [
                'http_code' => $e->getCode(),
                'return' => $e->getMessage(),
                'extra' => ['Content-type' => 'application/json; charset=utf-8'],
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
            ];

            return $this->sendError($e, $e);
        } catch (\Exception $e) {
            $return = [
                'http_code' => 400,
                'return' => $e,
                'extra' => ['Content-type' => 'application/json; charset=utf-8'],
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
            ];
            return $this->sendError($e, $e);
        }
    }

    ///
    public function list($var = null)
    {

        $return = false;
        $options = $this->constantsPaymentEfiPayPix;

        $params = [
            "inicio" => date('Y-m-d\TH:i:s\Z',strtotime("-1 days")),
            "fim" => date('Y-m-d\TH:i:s\Z')
        ];

        if ($var) {
            $params = $var;
        }

        try {
            $api = new EfiPay($options);
            $response = $api->pixListWebhook($params);

            $return = [
                'http_code' => 200,
                'return' => [
                    'response' => $response,
                ],
                'extra' => ['Content-type' => 'application/json; charset=utf-8'],
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
            ];

            return $this->sendResponse($return, 'success');
        } catch (EfiException $e) {
            $return = [
                'http_code' => $e->getCode(),
                'return' => $e->getMessage(),
                'extra' => ['Content-type' => 'application/json; charset=utf-8'],
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
            ];

            return $this->sendError($e, $e);
        } catch (\Exception $e) {
            $return = [
                'http_code' => 400,
                'return' => $e,
                'extra' => ['Content-type' => 'application/json; charset=utf-8'],
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
            ];
            return $this->sendError($e, $e);
        }
    }

    ///
    public function checkPaymentIsMade($txid){

        try {
            $r = $this->Model->where('payment_efi_pay_pix_cob_txid', $txid)->firstOrFail();
            //$r = PaymentEfiPayPixCob::where('txid', $paymentEfiPayPixCob)->firstOrFail();

            // $customerRequestPaid = new \App\Http\Controllers\Api\CustomerRequestController();
            // $customerRequestPaid->paid($r->customer_request_id, false);

            return $this->sendResponse($r,'Pagamento recebido no banco de dados');
        } catch (\Exception $e) {
            return $this->sendError($e, $e);
        }
    }

    ///
    public function store(RequestFrm $request)
    {
        try {
            //chama a função para acrescimo da restrição das permissões, se for o caso.
            //$this->isRestrict();

            $request->merge($this->myTraits($request));

            // validação dos dados
            $nossaClasse = new NossaClasse();
            $request->validate($nossaClasse->colunasValidate($this->data['campos']));
            $user_id = auth('sanctum')->user()->id ?? 1;
            $request->merge(['user_id' => $user_id]);

            $r = $request->except(['urlReturn']);
            return $this->Model->create($r);
        } catch (\Exception $e) {
            //return $this->sendError($e, $e);
            return 'Erro:'. $e->getCode().' - '. $e->getMessage();
            return 'false';
        }
    }
}
