<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\RequestFrm;
use App\Models\PaymentMethod;

class PaymentMethodController extends BaseController
{

    public function __construct()
    {
        $this->Model = new PaymentMethod();

        $this->data['campos'] = $this->Model->campos;
        $this->NamefolderImage = 'payment-method';
        $this->PathsView = 'PaymentMethod';
        $this->PathsRoute = 'payment-method';
        $this->ClassModel = 'PaymentMethod';
        $this->listVars['meta'] = [
            'title' => 'Formas de Pagamento do Sistema',
            'h1' => 'Formas de Pagamento do Sistema',
        ];
        $this->listVars['uriBase'] = 'payment-method/';
        $this->listVars['heads'] = [
            'name',
            'type',
            'status',
            'payment_method_controller',
            'financial_institution',
            'rate',
            'deadline_for_receipt',
            'description',
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
            'name',
            'type',
            'payment_method_controller',
            'financial_institution',
            'description',
        ];
    }

    ///
    /**
     * return [
     *   'payment_type' = ['cartao_de_credito' | 'pix' | 'boleto']
     *   'qrcode copia e cola',
     *   'img qrcode',
     *   'link qrcode'
     * ]
     */
    public function pix(RequestFrm $request)
    {
        //dd('chamou aqui function pix');
        try {
            // r = [http_code, return, extra]
            $paymentEfiPayPixCobController = new \App\Http\Controllers\Api\PaymentEfiPay\PaymentEfiPayPixCobController();
            return $paymentEfiPayPixCobController->pixCreateImmediateCharge($request);

            /* $r = [ // app\Http\Controllers\Api\PaymentMethodController.php:116
                "http_code" => 200,
                "return" => [
                    "responseBodyPix" => [
                        "calendario" => [
                            "criacao" => "2024-07-29T21:24:50.349Z",
                            "expiracao" => 3600,
                        ],
                        "txid" => "b1e7e128238e40ed87dba13b6ae77346",
                        "revisao" => 0,
                        "status" => "ATIVA",
                        "valor" => [
                            "original" => "130.00",
                        ],
                        "chave" => "chave@beachtennis.app.br",
                        "devedor" => [
                            "cpf" => "91846557046",
                            "nome" => "User Diretor Test",
                        ],
                        "solicitacaoPagador" => "Enter the order number or identifier.",
                        "infoAdicionais" =>  [
                            0 =>  [
                                "nome" => "Field 1",
                                "valor" => "Additional information1",
                            ],
                            1 =>  [
                                "nome" => "Field 2",
                                "valor" => "Additional information2",
                            ],
                        ],
                        "loc" =>  [
                            "id" => 22,
                            "location" => "qrcodespix-h.sejaefi.com.br/v2/f957a35def79405b94631cbcb2b08942",
                            "tipoCob" => "cob",
                            "criacao" => "2024-07-29T21:24:50.393Z",
                        ],
                        "location" => "qrcodespix-h.sejaefi.com.br/v2/f957a35def79405b94631cbcb2b08942",
                        "pixCopiaECola" => "00020101021226850014BR.GOV.BCB.PIX2563qrcodespix-h.sejaefi.com.br/v2/f957a35def79405b94631cbcb2b089425204000053039865802BR5905EFISA6008SAOPAULO62070503***63041BFA",
                    ],
                    "responseBodyQrcode" =>
                    //Efi\ Response 
                    [
                        'body' => [
                            "qrcode" => "00020101021226850014BR.GOV.BCB.PIX2563qrcodespix-h.sejaefi.com.br/v2/f957a35def79405b94631cbcb2b089425204000053039865802BR5905EFISA6008SAOPAULO62070503***63041BFA",
                            "imagemQrcode" => "data:image/png;base64,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",
                            "linkVisualizacao" => "https://pix.sejaefi.com.br/cob/pagar/f957a35def79405b94631cbcb2b08942"
                        ],
                        'headers' => [
                            "Server" => [
                                0 => "nginx",
                            ],
                            "Date" =>  [
                                0 => "Mon, 29 Jul 2024 21:24:50 GMT"
                            ],
                            "Content-Type" =>  [
                                0 => "application/json; charset=utf-8"
                            ],
                            "Content-Length" =>  [
                                0 => "4690"
                            ],
                            "Connection" =>  [
                                0 => "keep-alive"
                            ],
                            "x-request-id" =>  [
                                0 => "407bb301-6a54-4dcd-9df8-13125e0ced0a"
                            ],
                            "Vary" =>  [
                                0 => "Origin"
                            ],
                            "Access-Control-Allow-Credentials" =>  [
                                0 => "true"
                            ],
                            "ETag" =>  [
                                0 => 'W/1252-cZOWksBuZMGP0y46pdHXnEqTaaw'
                            ],
                        ]
                    ]
                ],
                "extra" =>  [
                    "Content-type" => "application/json; charset=utf-8"
                ],
                0 => 448
            ]; */

            // modificar o status do aluguel da quadra para reservado 
            //$r = json_decode('{"status":200,"success":true,"data":{"txid":"b1e7e128238e40ed87dba13b6ae77346","status":"ATIVA","http_status":200,"json":"{\"responseBodyPix\":{\"calendario\":{\"criacao\":\"2024-07-29T21:24:50.349Z\",\"expiracao\":3600},\"txid\":\"b1e7e128238e40ed87dba13b6ae77346\",\"revisao\":0,\"status\":\"ATIVA\",\"valor\":{\"original\":\"130.00\"},\"chave\":\"chave@beachtennis.app.br\",\"devedor\":{\"cpf\":\"91846557046\",\"nome\":\"User Diretor Test\"},\"solicitacaoPagador\":\"Enter the order number or identifier.\",\"infoAdicionais\":[{\"nome\":\"Field 1\",\"valor\":\"Additional information1\"},{\"nome\":\"Field 2\",\"valor\":\"Additional information2\"}],\"loc\":{\"id\":22,\"location\":\"qrcodespix-h.sejaefi.com.br\\\/v2\\\/f957a35def79405b94631cbcb2b08942\",\"tipoCob\":\"cob\",\"criacao\":\"2024-07-29T21:24:50.393Z\"},\"location\":\"qrcodespix-h.sejaefi.com.br\\\/v2\\\/f957a35def79405b94631cbcb2b08942\",\"pixCopiaECola\":\"00020101021226850014BR.GOV.BCB.PIX2563qrcodespix-h.sejaefi.com.br\\\/v2\\\/f957a35def79405b94631cbcb2b089425204000053039865802BR5905EFISA6008SAOPAULO62070503***63041BFA\"},\"responseBodyQrcode\":{\"body\":{\"qrcode\":\"00020101021226850014BR.GOV.BCB.PIX2563qrcodespix-h.sejaefi.com.br\\\/v2\\\/f957a35def79405b94631cbcb2b089425204000053039865802BR5905EFISA6008SAOPAULO62070503***63041BFA\",\"imagemQrcode\":\"data:image\\\/png;base64,iVBORw0KGgoAAAANSUhEUgAAAOQAAADkCAYAAACIV4iNAAAAAklEQVR4AewaftIAAAyQSURBVO3BQY4cSRLAQDLR\\\/\\\/8yV0c\\\/BZCoail24Gb2B2utKzysta7xsNa6xsNa6xoPa61rPKy1rvGw1rrGw1rrGg9rrWs8rLWu8bDWusbDWusaD2utazysta7xsNa6xsNa6xo\\\/fEjlb6r4hMobFZPKN1VMKlPFicpvqjhRmSomlanim1Smiknlb6r4xMNa6xoPa61rPKy1rvHDl1V8k8obKlPFGxWTylQxqUwVk8obFZPKVDFVfEJlqphU3lCZKiaVqeJE5Zsqvknlmx7WWtd4WGtd42GtdY0ffpnKGxVvqEwVk8pUcaLyRsWkMlVMKicqb6icVEwqb1RMKicVn1CZKiaVb1J5o+I3Pay1rvGw1rrGw1rrGj\\\/8x6icqEwVv0llqviEylQxqUwqJxUnKlPFb6p4o+K\\\/5GGtdY2HtdY1HtZa1\\\/jhP6bimypOVKaKSWVSOamYVE5U3qiYVKaKqeINlaliqphUpoqTiv+yh7XWNR7WWtd4WGtd44dfVvE3qUwVb1RMKlPFVHFScaIyqZxUvKFyUnGiclIxVZyonKicVHxTxU0e1lrXeFhrXeNhrXWNH75M5V+qmFSmikllqnhDZaqYVKaKk4pJ5URlqnhDZao4qZhUpopJZaqYVKaKSeVEZao4UbnZw1rrGg9rrWs8rLWu8cOHKv6fVUwqU8WkMlW8ofJNFZ+oeEPlROVE5URlqjipOKn4f\\\/Kw1rrGw1rrGg9rrWv88CGVqWJS+aaKqWJSmSomlZOKN1ROKr5J5RMqU8UbFZPKVPGGylQxqbxRMal8U8VvelhrXeNhrXWNh7XWNewPPqDyRsWkclJxonJScaIyVUwqU8UnVKaK36QyVUwqU8WJyhsVk8pUcaLyTRUnKlPFpDJVfNPDWusaD2utazysta5hf\\\/ABlaniDZWpYlKZKiaVqeJEZap4Q2WqmFQ+UXGiMlVMKm9UTConFZPKVPEJlaniEyonFTd5WGtd42GtdY2HtdY1fvgylZOKE5WpYlL5JpWp4jdVTCqTylTxRsWkMlVMKlPFGxWTyhsV\\\/5LKGxW\\\/6WGtdY2HtdY1HtZa1\\\/jhL1OZKiaVSWWqOFE5qXhDZaqYVKaKSWVSmSreqDhROVH5hMpJxaQyVXyTylRxUvGGyonKVPGJh7XWNR7WWtd4WGtd44cvq5hUpoo3Kt6oOFF5o2JSOVE5qThRmSomlaliqphUporfpDJVTConFScqJypTxaQyVUwqU8Xf9LDWusbDWusaD2uta\\\/zwoYpJZao4UTlR+aaKN1S+SWWqmCpOKiaVk4pJZao4UTmpmFTeqDhROak4UZkqJpWbPKy1rvGw1rrGw1rrGj98SGWqeKNiUpkq3lCZKt5QmSomlZOKSWWqOFGZKiaVk4qTipuoTBVTxYnKGypvqEwVv+lhrXWNh7XWNR7WWtf44ctUpoo3KiaVk4qpYlJ5o+KNiknlExUnFZPKGypTxaRyUnFScVIxqXxTxaQyVZyoTBV\\\/08Na6xoPa61rPKy1rvHDl1W8oXJSMalMKlPFN6mcqEwVk8pNKk4qJpVJZaqYVKaKSeUTKm9UTCqfUJkqvulhrXWNh7XWNR7WWtewP\\\/iAylRxojJVTConFZPKVPGGyknFb1I5qfgmlaliUjmp+CaVqeINlTcq3lB5o+ITD2utazysta7xsNa6hv3BP6RyUjGpTBWTylTxhspJxaTyL1W8oTJVvKEyVUwqU8WJylTxTSonFZPKVHGiMlV84mGtdY2HtdY1HtZa17A\\\/+IDKGxUnKicVk8pUcaIyVZyonFS8oTJVnKhMFScqU8WJylTxTSpTxSdUpopJ5aTiROWNim96WGtd42GtdY2HtdY17A9+kcpJxRsqU8WkMlWcqJxU\\\/E0qU8WkclJxojJVnKhMFW+oTBUnKlPFpDJVnKicVLyhMlV808Na6xoPa61rPKy1rmF\\\/8AGVqeJE5TdVfEJlqjhROamYVP5LKk5UpooTlaniROWbKv6lh7XWNR7WWtd4WGtd44cvU\\\/lExRsqJyonFVPFicpJxUnFpDJVTCpTxRsqU8WkMlWcqLxRMalMFW+onFS8oTKpTBUnKlPFJx7WWtd4WGtd42GtdY0fPlRxovIJlaniROUNlaliUvmEyknFpPKGylRxonKiclIxqUwVk8pU8YbKJ1SmipOKSWWqmCq+6WGtdY2HtdY1HtZa1\\\/jhl1WcqJxU\\\/KaKSWWqeENlqvhNFd9U8QmVE5WpYqo4UXmj4g2VqWJSmSq+6WGtdY2HtdY1HtZa1\\\/jhy1Q+ofJNFZPKGypTxb+k8k0Vk8pJxUnFpHKi8kbFpDKpfKLipOI3Pay1rvGw1rrGw1rrGvYHv0jlExUnKlPFicpJxRsqU8WJylRxojJVnKhMFScqU8WkclJxonJSMalMFZPKScWkclIxqUwVk8pJxSce1lrXeFhrXeNhrXUN+4MPqEwVk8pUcaLymyomlZOKN1ROKj6hclJxojJVnKhMFScqb1ScqJxUnKhMFW+oTBW\\\/6WGtdY2HtdY1HtZa1\\\/jhQxWTyhsqU8UbKicVJxWTym9SOamYVKaKSeVEZar4JpWp4hMqU8Wk8gmVqWJSeUNlqvjEw1rrGg9rrWs8rLWu8cOHVD5RcaLyRsUbKlPFpHJS8UbFpPKGyhsVk8pJxVQxqUwVk8pU8UbFpDJVfKLiZg9rrWs8rLWu8bDWuob9wT+kMlWcqLxRMalMFW+oTBWTylQxqZxUvKHyiYpJZar4hMpUMam8UfEJlaniROWNik88rLWu8bDWusbDWusaP3xIZap4o+JE5aTijYpJZaqYVKaKSWWq+CaVqeITFZPKVHGi8gmVqeJEZVKZKiaVT6j8Sw9rrWs8rLWu8bDWuob9wQdU3qiYVE4qPqEyVZyoTBVvqJxUfELlpOJE5Y2KE5WpYlKZKk5U3qiYVKaK\\\/ycPa61rPKy1rvGw1rqG\\\/cEXqXyiYlI5qThRmSomlaliUjmp+CaVk4pJZaqYVKaKSWWqmFQ+UTGpTBVvqLxRMalMFZPKVDGpTBXf9LDWusbDWusaD2uta9gffEBlqphUpopJZao4UTmpmFROKiaVk4pJZao4UTmp+ITKVDGpvFFxojJVnKi8UfGGyknFicpUcaIyVXziYa11jYe11jUe1lrX+OEvU5kqTlSmiknlpGJSmVSmijcqTlROKiaVT1ScVJyoTCpTxRsqU8WkcqIyVUwqb6h8QuU3Pay1rvGw1rrGw1rrGj98qOKk4hMVJxWTylRxUnGi8omKT1ScqHxTxaTyhso3VUwqU8WkMlWcqNzkYa11jYe11jUe1lrXsD\\\/4IpWp4g2Vk4rfpDJVTCpTxaRyUjGpnFT8TSpTxSdUpopJ5ZsqJpWpYlI5qfibHtZa13hYa13jYa11DfuDL1J5o+ITKicVb6icVEwqb1RMKlPFpDJVnKhMFScqU8WJylQxqUwVk8pJxRsqJxVvqHyi4hMPa61rPKy1rvGw1rrGDx9SeaPiDZU3Kk5UpopvqjhR+YTKGyonFW9UTCpTxaQyVUwqk8pUMam8ofJGxaQyVfymh7XWNR7WWtd4WGtdw\\\/7g\\\/5jKVPGGylQxqUwVk8pU8YbKGxVvqLxR8QmVqWJSOak4UTmpeEPlpGJSmSq+6WGtdY2HtdY1HtZa1\\\/jhQyp\\\/U8VUMamcVJyoTBWTylQxqZxUfJPKVPFGxaQyVUwqJxUnFScq36QyVZxUnFRMKlPFJx7WWtd4WGtd42GtdY0fvqzim1ROVKaKE5WpYlKZVE5U3lCZKk5UTireqJhUpoo3Kj6hMlWcqLxR8YbKGxXf9LDWusbDWusaD2uta\\\/zwy1TeqPgmlanijYoTlaniROVE5UTlEyqfqJhUTipOKr5J5RMVk8qJylTxiYe11jUe1lrXeFhrXeOH\\\/xiVqWJSOak4UZkqJpWTipOKE5WpYlJ5o+KbKn5TxaQyVZyofKJiUvmmh7XWNR7WWtd4WGtd44f\\\/mIqTiknlmyomlUllqphUTipOKiaVE5WpYlI5qZhUpooTlaniRGWqOFGZKk5U\\\/qWHtdY1HtZa13hYa13jh19W8ZsqPlHxTSonFb9J5aTiX1K5icpJxaTymx7WWtd4WGtd42GtdY0fvkzlb1KZKt5QmSomlanijYpJZaqYKiaVE5Wp4kRlqphUTipOKk4qJpVJZap4Q2Wq+KaKSeWbHtZa13hYa13jYa11DfuDtdYVHtZa13hYa13jYa11jYe11jUe1lrXeFhrXeNhrXWNh7XWNR7WWtd4WGtd42GtdY2HtdY1HtZa13hYa13jYa11jf8BKQEG\\\/Ka31dUAAAAASUVORK5CYII=\",\"linkVisualizacao\":\"https:\\\/\\\/pix.sejaefi.com.br\\\/cob\\\/pagar\\\/f957a35def79405b94631cbcb2b08942\"},\"headers\":{\"Server\":[\"nginx\"],\"Date\":[\"Mon, 29 Jul 2024 21:24:50 GMT\"],\"Content-Type\":[\"application\\\/json; charset=utf-8\"],\"Content-Length\":[\"4690\"],\"Connection\":[\"keep-alive\"],\"x-request-id\":[\"407bb301-6a54-4dcd-9df8-13125e0ced0a\"],\"Vary\":[\"Origin\"],\"Access-Control-Allow-Credentials\":[\"true\"],\"ETag\":[\"W\\\/1252-cZOWksBuZMGP0y46pdHXnEqTaaw\"]}}}","customer_request_id":1,"created_at":"2024-07-30T00:24:50.000000Z","updated_at":"2024-07-30T00:24:50.000000Z","user_id":1,"id":21},"message":"Tudo Certo!"}');

            /* $this->message = 'Tudo Certo!';
            $this->status = 200;

            return $this->sendResponse($r, $this->message, $this->status); */
        } catch (\Exception $e) {
            $this->message = $e; //$e->getMessage();
            $this->status = $e;
        }

        return $this->sendError($this->message, $this->status);
    }


    //
    public function pixShow() {}

    ///
    public function cartaoDeCredito() {}

    ///
    public function boleto() {}

    //
    public function boletoShow() {}
}
