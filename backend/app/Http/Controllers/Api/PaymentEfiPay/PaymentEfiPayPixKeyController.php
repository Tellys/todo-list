<?php

namespace App\Http\Controllers\Api\PaymentEfiPay;

use App\Classes\NossaClasse;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\RequestFrm;
use App\Models\PaymentEfiPayPixKey;
use Efi\Exception\EfiException;
use Efi\EfiPay;
use Efi\Request;

class PaymentEfiPayPixKeyController extends BaseController
{
    private $constantsPaymentEfiPayPix;

    ///
    public function __construct()
    {
        $this->Model = new PaymentEfiPayPixKey();

        $this->data['campos'] = $this->Model->campos;
        $this->NamefolderImage = 'payment-efi-pay-pix-key';
        $this->PathsView = 'PaymentEfiPayPixKey';
        $this->PathsRoute = 'payment-efi-pay-pix-key';
        $this->ClassModel = 'PaymentEfiPayPixKey';
        $this->listVars['meta'] = [
            'title' => 'Lista de Chaves Pix',
            'h1' => 'Lista de Chaves Pix',
        ];
        $this->listVars['uriBase'] = 'payment-efi-pay-pix-key/';
        $this->listVars['heads'] = [
            'key',
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
            'id',
            'key',
            'status',
        ];

        $this->constantsPaymentEfiPayPix = (new PaymentEfiPayController())->getVarsApiPix(false);
    }

    ///
    public function create($jsonResponse = true)
    {
        try {
            $api = new EfiPay($this->constantsPaymentEfiPayPix);
            $response = $api->pixCreateEvp();

            $newRequest = new RequestFrm();
            $newRequest->replace(['key' => $response->body['chave']]);
            $r = $this->store($newRequest);

            $r = ['key' => $r->key];

            if (!$jsonResponse) {
                return $r;
            }

            $this->message = 'Chave Pix criada com sucesso!';
            $this->status = 200;
            return $this->sendResponse($r, $this->message, $this->status);
        } catch (EfiException $e) {
            if (!$jsonResponse) {
                return null;
            }
            return $this->sendError($e->errorDescription, $e->code);
        } catch (\Exception $e) {
            if (!$jsonResponse) {
                return null;
            }
            return $this->sendError($e, $e);
        }
    }

    ///
    public function delete(RequestFrm $request, $jsonResponse = true)
    {
        try {
            $api = new EfiPay($this->constantsPaymentEfiPayPix);
            $api->pixDeleteEvp(['chave' => $request->chave]);

            $r = $this->Model->where('key', $request->chave)->delete();

            if (!$jsonResponse) {
                return $r;
            }

            $this->message = 'Chave Pix Deletada!';
            $this->status = 200;
            return $this->sendResponse($r, $this->message, $this->status);
        } catch (EfiException $e) {
            if (!$jsonResponse) {
                return null;
            }
            return $this->sendError($e->errorDescription, $e->code);
        } catch (\Exception $e) {
            if (!$jsonResponse) {
                return null;
            }
            return $this->sendError($e, $e);
        }
    }

    ///
    public function list($jsonResponse = true)
    {
        try {
            $api = new EfiPay($this->constantsPaymentEfiPayPix);
            $response = $api->pixListEvp();

            if (!$jsonResponse) {
                return $response;
            }

            $this->message = 'Listagem de Chaves Pix Válidas!';
            $this->status = 200;
            return $this->sendResponse($response, $this->message, $this->status);
        } catch (EfiException $e) {
            if (!$jsonResponse) {
                return null;
            }
            return $this->sendError($e->errorDescription, $e->code);
        } catch (\Exception $e) {
            if (!$jsonResponse) {
                return null;
            }
            return $this->sendError($e, $e);
        }
    }

    ///
    public function exists($jsonResponse = true)
    {
        try {
            $key = null;
            
            $pixKey = $this->list(false);
            if (empty($pixKey->body['chaves'][0])) {
                $key = $this->create(false);
            }

            $r = $key ?? ['key' => $pixKey->body['chaves'][0]];

            if (!$jsonResponse) {
                return $r;
            }

            $this->message = 'Chave Pix Retornada com Sucesso!';
            $this->status = 200;
            return $this->sendResponse($r, $this->message, $this->status);
        } catch (\Exception $e) {
            if (!$jsonResponse) {
                return null;
            }
            return $this->sendError($e, $e);
        }
    }

    ///
    public function getMyPixKey($jsonResponse = true)
    {
        try {
            $r = $this->Model->first();

            if (!$r) {
                $r = $this->exists(false);
            }

            if (!$jsonResponse) {
                return $r;
            }

            $this->message = 'Chave Pix Retornada com Sucesso!';
            $this->status = 200;
            return $this->sendResponse($r, $this->message, $this->status);
        } catch (\Exception $e) {
            if (!$jsonResponse) {
                return null;
            }
            return $this->sendError($e, $e);
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

        return $this->Model->create($r);
    }
}
