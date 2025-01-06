<?php

namespace App\Http\Controllers\Api\PaymentEfiPay;

use App\Classes\NossaClasse;
use App\Http\Controllers\Api\CustomerRequestController;
use App\Http\Requests\RequestFrm;
use App\Models\CustomerRequest;
use App\Models\PaymentEfiPayPixCob;
use Efi\Exception\EfiException;
use Efi\EfiPay;
use Efi\Request;

class PaymentEfiPayPixCobController extends PaymentEfiPayController
{
    private $constantsPaymentEfiPayPixChave;

    // colunas que retornarão como resultado da variavel $this->paymentEfiPayPixCob
    private $collomnsToReturn = ['id', 'customer_request_id', 'valor', 'status', 'txid', 'http_code', 'end_to_end_id', 'solicitacao_pagador', 'created_at', 'updated_at', 'deleted_at'];

    ///
    public function __construct()
    {
        $this->Model = new PaymentEfiPayPixCob();

        $this->data['campos'] = $this->Model->campos;
        $this->NamefolderImage = 'payment-efi-pay-pix-cob';
        $this->PathsView = 'PaymentEfiPayPixCob';
        $this->PathsRoute = 'payment-efi-pay-pix-cob';
        $this->ClassModel = 'PaymentEfiPayPixCob';
        $this->listVars['meta'] = [
            'title' => 'Sistema de Cobrança EfiPay',
            'h1' => 'Sistema de Cobrança EfiPay',
        ];
        $this->listVars['uriBase'] = 'payment-efi-pay-pix-cob/';
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

    ///
    function pixCreateImmediateCharge(RequestFrm $request, $params = [])
    {
        $body = [
            "calendario" => [
                "expiracao" => $this->constantsPaymentEfiPayPixExpiration, // Charge lifetime, specified in seconds from creation date
            ],
            "devedor" => [
                "cpf" => $request['client']['cpf'],
                "nome" => $request['client']['name'],
            ],
            "valor" => [
                //"original" => preg_replace('/\D/', '', $request['price_promo'] ?? $request['price']),
                "original" => $request['price_promo'] ?? $request['price'],
            ],
            "chave" => $this->constantsPaymentEfiPayPixChave, // Pix key registered in the authenticated Efí account
            "solicitacaoPagador" => "Enter the order number or identifier.",
            "infoAdicionais" => [
                [
                    "nome" => "Field 1",
                    "valor" => "Additional information1"
                ],
                [
                    "nome" => "Field 2",
                    "valor" => "Additional information2"
                ]
            ]
        ];

        //dd($params, $body);

        try {
            $return = false;

            $api = new EfiPay($this->constantsPaymentEfiPayPix);
            $responsePix = $api->pixCreateImmediateCharge($params, $body); // Using this function the txid will be generated automatically by Efí API

            //dd($responsePix);

            $responseBodyPix = (isset($this->constantsPaymentEfiPayPix["responseHeaders"]) && $this->constantsPaymentEfiPayPix["responseHeaders"]) ? $responsePix->body : $responsePix;

            if ($responseBodyPix["txid"]) {
                $params = [
                    "id" => $responseBodyPix["loc"]["id"]
                ];

                try {
                    $responseQrcode = $api->pixGenerateQRCode($params);

                    $responseBodyQrcode = (isset($this->constantsPaymentEfiPayPix["responseHeaders"]) && $this->constantsPaymentEfiPayPix["responseHeaders"]) ? $responseQrcode->body : $responseQrcode;

                    //http_code, return, extra
                    $return = [
                        'http_code' => 200,
                        'return' => [
                            'responseBodyPix' => $responseBodyPix,
                            'responseBodyQrcode' => $responseBodyQrcode,
                        ],
                        'extra' => ['Content-type' => 'application/json; charset=utf-8'],
                        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
                    ];
                } catch (EfiException $e) {
                    $return = [
                        'http_code' => 400,
                        'return' => $e,
                        'extra' => ['Content-type' => 'application/json; charset=utf-8'],
                        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,

                    ];
                } catch (\Exception $e) {
                    $return = [
                        'http_code' => $e->getCode(),
                        'return' => $e->getMessage(),
                        'extra' => ['Content-type' => 'application/json; charset=utf-8'],
                        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,

                    ];
                }
            } else {
                $return = [
                    'http_code' => 200,
                    'return' => $responseBodyPix,
                    'extra' => ['Content-type' => 'application/json; charset=utf-8'],
                    JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,

                ];
            }
        } catch (EfiException $e) {
            $return = [
                'http_code' => 400,
                'return' => $e,
                'extra' => ['Content-type' => 'application/json; charset=utf-8'],
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,

            ];
        } catch (\Exception $e) {
            $return = [
                'http_code' => $e->getCode(),
                'return' => $e->getMessage(),
                'extra' => ['Content-type' => 'application/json; charset=utf-8'],
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,

            ];
        }

        try {
            //dd($return);
            $newRequest = new RequestFrm();

            if ($return['http_code'] != 200) {
                $newRequest->replace([
                    'txid' => null,
                    'status' => $return['return']->message ?? 'Mensagem de Erro Vazia',
                    'http_code' => $return['http_code'],
                    'json' => (string) json_encode($return['return']),
                    'customer_request_id' => $request->id,
                    "valor" => $request['price_promo'] ?? $request['price'],

                    //'created_at' => now(),
                    //'updated_at' => now(),
                ]);
                $this->store($newRequest);

                return [
                    'return' => $return['return']->message,
                    'http_code' => $return['http_code'],
                ];
            }

            //dd($return);

            $newRequest->replace([
                'txid' => $return['return']['responseBodyPix']['txid'] ?? null,
                'status' => $return['return']['responseBodyPix']['status'] ?? (!empty($return['return']->message) ? $return['return']->message : 'Mensagem de Erro Vazia'),
                'image' => $return['return']['responseBodyQrcode']['imagemQrcode'],
                'qrcode' => $return['return']['responseBodyQrcode']['qrcode'],
                'http_code' => $return['http_code'],
                'json' => (string) json_encode($return['return']),
                'customer_request_id' => $request->id,
                'created_at' => $return['return']['responseBodyPix']['calendario']['criacao'] ?? now(),
                'updated_at' => $return['return']['responseBodyPix']['calendario']['criacao'] ?? now(),
                'expires_in' => date('Y-m-d H:i:s', strtotime($return['return']['responseBodyPix']['calendario']['criacao']) + $return['return']['responseBodyPix']['calendario']['expiracao']),

                "valor" => $request['price_promo'] ?? $request['price'],
            ]);

            //dd($newRequest);

            $this->store($newRequest);
            return [
                'payment_type' => 'pix',
                'status' => $newRequest->status,
                'http_code' => $newRequest->http_code,
                'qrcode' => $newRequest->qrcode,
                'image' => $newRequest->image,
                'linkVisualizacao' => $return['return']['responseBodyQrcode']['linkVisualizacao'],
                'created_at' => $newRequest->created_at,
                'expires_in' => $newRequest->expires_in
            ];
        } catch (\Exception $e) {
            return [
                'return' => $e->getMessage(),
                'http_code' => 400,
            ];
        }
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

        $user_id = auth('sanctum')->user()->id;
        $request->merge(['user_id' => $user_id]);

        $r = $request->except(['urlReturn']);

        //dd($r);

        return $this->Model->create($r);
    }

    /// Detail {txid}
    public function pixDetailCharge($txid, $jsonResponse = true)
    {
        /* $api = new EfiPay($this->constantsPaymentEfiPayPix);
        $response = $api->pixDetailCharge(["txid" => $txid]);

        $responseBodyPix = (isset($this->constantsPaymentEfiPayPix["responseHeaders"]) && $this->constantsPaymentEfiPayPix["responseHeaders"]) ? $response->body : $response;
        $responseBodyQrcode = (isset($this->constantsPaymentEfiPayPix["responseHeaders"]) && $this->constantsPaymentEfiPayPix["responseHeaders"]) ? $response->body : $response;

        dd($response); */

        try {
            $api = new EfiPay($this->constantsPaymentEfiPayPix);
            $response = $api->pixDetailCharge(["txid" => $txid]);
            $response = (isset($this->constantsPaymentEfiPayPix["responseHeaders"]) && $this->constantsPaymentEfiPayPix["responseHeaders"]) ? $response->body : $response;

            //dd($response);

            $return = [
                'http_code' => 200,
                'return' => $response,
                'extra' => ['Content-type' => 'application/json; charset=utf-8'],
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
            ];
        } catch (EfiException $e) {
            $return = [
                'http_code' => 400,
                'return' => $e,
                'extra' => ['Content-type' => 'application/json; charset=utf-8'],
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
            ];
        } catch (\Exception $e) {
            $return = [
                'http_code' => $e->getCode(),
                'return' => $e->getMessage(),
                'extra' => ['Content-type' => 'application/json; charset=utf-8'],
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
            ];
        }

        //$newRequest = new RequestFrm();

        if ($return['http_code'] != 200) {
            // $newRequest->replace([
            //     'txid' => null,
            //     'status' => $return['return']->message ?? 'Mensagem de Erro Vazia',
            //     'http_code' => $return['http_code'],
            //     'json' => (string) json_encode($return['return']),
            //     //'customer_request_id' => $request->id,
            //     //'created_at' => now(),
            //     //'updated_at' => now(),

            //     //'end_to_end_id' => $end_to_end_id,

            // ]);
            //$this->store($newRequest);

            if (!$jsonResponse) {
                return [
                    'return' => $return['return']->message,
                    'http_code' => $return['http_code'],
                ];
            }
            return $this->sendError($return['return'], $return['http_code']);
        }

        //dd($return);

        //$newRequest->replace([
        $newRequest = [
            'txid' => $return['return']['txid'] ?? null,
            'status' => $return['return']['status'] ?? (!empty($return['return']->message) ? $return['return']->message : 'Mensagem de Erro Vazia'),
            //'image' => $return['return']['imagemQrcode'],
            //'qrcode' => $return['return']['qrcode'],
            'http_code' => $return['http_code'],
            //'json' => (string) json_encode($return['return']),
            //'customer_request_id' => $request->id,
            //'created_at' => $return['return']['calendario']['criacao'] ?? now(),
            'updated_at' => $return['return']['calendario']['criacao'] ?? now(),
            //'expires_in' => date('Y-m-d H:i:s', strtotime($return['return']['calendario']['criacao']) + $return['return']['calendario']['expiracao']),
            //'chave' => $return['return']['chave'] ,
            'solicitacao_pagador' => $return['return']['solicitacaoPagador'],
        ];

        // quando o pagamento não estiver sido realizado elas não existirão. Por isso NULL
        if (strtoupper($return['return']['status']) == 'CONCLUIDA') {
            $newRequest['valor'] = $return['return']['pix'][0]['valor'];
            $newRequest['end_to_end_id'] = $return['return']['pix'][0]['endToEndId'];
        }

        try {
            //dd($newRequest);
            $q = $this->paymentEfiPayPixCob ?? $this->Model->where('txid', $newRequest['txid'])->with('customer_request')->firstOrFail($this->collomnsToReturn);
            //$q = $this->Model->where('txid', $newRequest['txid'])->firstOrFail();
            $q->update($newRequest);

            //dd($q, $this->paymentEfiPayPixCob);

            // change dados da situação atual do pagamento via Pix
            $this->paymentEfiPayPixCob = $q;

            //return $newRequest;
            
            $r = [
                'payment_type' => 'pix',
                'status' => $q->status, // $newRequest['status'],
                'http_code' => $q->http_code, // $newRequest['http_code'],
                'valor' => $q->valor, //$newRequest['valor'] //ausente quando o pagamento não estiver sido realizado
            ];

            //dd($r);

            if (!$jsonResponse) {
                return $r;
            }

             return $this->sendResponse($r, 'Item quitado no Banco e atualizado no DB');
        } catch (\Exception $e) {

            // change dados da situação atual do pagamento via Pix
            $this->paymentEfiPayPixCob = null;

            if (!$jsonResponse) {
                return [
                    'return' => $e->getMessage(),
                    'http_code' => $e->getCode(),
                ];
            }

            return $this->sendError($e, $e);
        }
    }

    /**
     * @param txid String
     * @param jsonResponse Boll
     * @return bool or json
     */
    public function checkPaymentIsMade(string $txid, $jsonResponse = true)
    {
        try {
            ////// 1ª Fase - check into DB
            
            $this->paymentEfiPayPixCob = $this->Model->where('txid', $txid)
            //->includeTablesRelations()
            ->with('customer_request')
            ->first($this->collomnsToReturn)
            ;

            // set variável PaymentEfiPayController->currentCustomerRequest
            $this->currentCustomerRequest = $this->paymentEfiPayPixCob->customer_request;
            //$this->paymentEfiPayPixCob->customer_request_all_relations()->delete();

            //dd($this->paymentEfiPayPixCob->toArray());
            //dd($this->currentCustomerRequest->status);

            //caso encontre 'paid' return true
            if (strtolower($this->currentCustomerRequest->status) == 'paid') {
                //dd('aqui dentro "paid"');

                if (!$jsonResponse) {
                    return true;
                }

                return $this->sendResponse(true, 'Item pago!');
            }

            ////// 2ª Fase - to API
            
            // busca a API do Banco e retorna com o resultado do pagamento
            // aqui é pesquisado e JÁ atualizada a table 'payment_efi_pay_pix_cobs' 
            $pixDetailCharge = $this->pixDetailCharge($txid, false);
            
            //dd($pixDetailCharge);

            // se pagamento NAO realizado
            // se $pixDetailCharge['status'] != 'CONCLUIDA'
            if (empty($pixDetailCharge['status']) or strtoupper($pixDetailCharge['status']) != 'CONCLUIDA') {
                if (!$jsonResponse) {
                    return false;
                }

                return $this->sendError(false, 'Item não pago ou Ainda não Atualizado no sistema. Tente novamente ou gere um novo pix');
            }

            ////// 3ª Fase - Se tudo certo, item pago

            // atualizamos o DB->customerRequest
            // set variável PaymentEfiPayController->currentCustomerRequest
            $this->currentCustomerRequest = (new CustomerRequestController())->setIsPaid(['id', $this->paymentEfiPayPixCob->customer_request_id], 'obj');
 
            if (!$jsonResponse) {
                return true;
            }

            return $this->sendResponse(true, 'Item pago!');
        } catch (\Exception $e) {
            if (!$jsonResponse) {
                //return false;
                return $e;
            }
            return $this->sendError(false, 'Erro: Exception lançada');
        }
    }

    /// lista de Pix Pagos
    public function listPaid(RequestFrm $request)
    {
        $api = new EfiPay($this->constantsPaymentEfiPayPix);
        $response = $api->pixListCharges([
            "inicio" => "2024-01-01T00:00:00Z",
            "fim" => "2024-09-08T23:59:59Z",
        ]);
        dd($response);
    }
}
