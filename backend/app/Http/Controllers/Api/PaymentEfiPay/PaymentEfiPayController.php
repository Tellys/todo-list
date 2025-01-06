<?php

namespace App\Http\Controllers\Api\PaymentEfiPay;

use App\Http\Controllers\Api\BaseController;
use App\Models\PaymentEfiPay;

class PaymentEfiPayController extends BaseController
{
    /**
     * Environment
     */
    private $sandbox = true; // false = Production | true = Homologation

    public $currentCustomerRequest = null; // pedido atual
    public $paymentEfiPayPixCob = null; // dados da situação atual do pagamento via Pix

    /// Pix    
    public $constantsPaymentEfiPayPix;
    //public $constantsPaymentEfiPayPixChave = null;
    public $constantsPaymentEfiPayPixRotaBase;
    public $constantsPaymentEfiPayPixExpiration = 3600;  //seconds

    public function __construct()
    {
        $this->Model = new PaymentEfiPay();

        $this->data['campos'] = $this->Model->campos;
        $this->NamefolderImage = 'payment-efi-pay';
        $this->PathsView = 'PaymentEfiPay';
        $this->PathsRoute = 'payment-efi-pay';
        $this->ClassModel = 'PaymentEfiPay';
        $this->listVars['meta'] = [
            'title' => 'Sistema de Cobrança EfiPay',
            'h1' => 'Sistema de Cobrança EfiPay',
        ];
        $this->listVars['uriBase'] = 'payment-efi-pay/';
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
    }

    ///
    public function getVarsApiPix($sandbox = false)
    {
        //dd('chamou function getVarsApiPix');
        /**
         * Credentials of Production
         */
        $clientId = 'Client_Id_8bd3510efc81e61619f8dd000beadee19795bea8';
        $clientSecret = 'Client_Secret_0573831b6dea99fc4ac33ad762be40c0600455ea';

        //VM
        //$certificate = resource_path() . DIRECTORY_SEPARATOR . 'certs' . DIRECTORY_SEPARATOR . 'certificado_producao.pem';
        $certificate = resource_path() . DIRECTORY_SEPARATOR . 'certs' . DIRECTORY_SEPARATOR . 'producao-587660-efi_beach_tennis_sdk_php.p12';
        $this->constantsPaymentEfiPayPixRotaBase = 'https://pix.api.efipay.com.br';

        if ($sandbox) {
            /**
             * Credentials of Homologation
             */
            $clientId = 'Client_Id_03047a39d952f9594d8481bfc487d036040a44c7';
            $clientSecret = 'Client_Secret_47c6c417169143eb12f5573b36f835f89d2240df';
            //$certificate = resource_path() . DIRECTORY_SEPARATOR . 'certs' . DIRECTORY_SEPARATOR . 'homologacao-587660-efi_beach_tennis_sdk_php_homologacao.p12';
            
            //VM
            //$certificate = resource_path() . DIRECTORY_SEPARATOR . 'certs' . DIRECTORY_SEPARATOR . 'certificado_homologacao.pem';
            $certificate = resource_path() . DIRECTORY_SEPARATOR . 'certs' . DIRECTORY_SEPARATOR . 'homologacao-587660-efi_beach_tennis_sdk_php_homologacao.p12';
            $this->constantsPaymentEfiPayPixRotaBase = 'https://pix-h.api.efipay.com.br';
        }

        return $this->constantsPaymentEfiPayPix = [
            "clientId" => $clientId,
            "clientSecret" => $clientSecret,
            "certificate" => $certificate,
            "pwdCertificate" => "", // Optional | Default = ""
            "sandbox" => $sandbox, // Optional | Default = false
            "debug" => false, // Optional | Default = false
            "timeout" => 30, // Optional | Default = 30
            "responseHeaders" => true, //  Optional | Default = false
        ];
    }
}
