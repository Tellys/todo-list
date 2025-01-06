<?php

namespace App\Classes;

class MyResponse
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message, $status = 200, $extra= [])
    {
        $response = [
            'status' => $status,
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];

        return response()->json($response, $status, $extra);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($errorMessages, $status = 404, $extra = [])
    {
        $returnErrorMessages = $errorMessages;

        if (is_object($errorMessages) and !empty($errorMessages->getMessage())) {

            switch (true) {
                case str_contains(strtolower($errorMessages->getMessage()), strtolower('No query results for model')):
                    $returnErrorMessages = explode("]", $errorMessages->getMessage());
                    $returnErrorMessages = !empty($returnErrorMessages[1]) ? $returnErrorMessages[1] : $returnErrorMessages;
                    $returnErrorMessages = 'O item "' . $returnErrorMessages . '" não foi localizado ou não existe!';

                    $status = 200;
                    break;

                // case str_contains(strtolower($errorMessages->getMessage()), strtolower('Integrity constraint violation')):
                //     //$returnErrorMessages = explode("]", $errorMessages->getMessage());
                //     $returnErrorMessages = $errorMessages->getMessage();
                //     //$returnErrorMessages = 'O item não pode ser excluido, pois esta interligado a outros no banco de dados!';

                //     $status = 403;
                //     break;

                case str_contains(strtolower($errorMessages->getMessage()), strtolower('Duplicate entry')):
                    $returnErrorMessages = explode("'", $errorMessages->getMessage());
                    $returnErrorMessages = !empty($returnErrorMessages[1]) ? $returnErrorMessages[1] : $returnErrorMessages;
                    //$returnErrorMessages = 'O item "' . $returnErrorMessages . '" já existe em nosso banco de dados!';

                    $status = 403;
                    break;

                    //case $errorMessages->getCod() 1364:
                case str_contains(strtolower($errorMessages->getMessage()), strtolower('General error: 1364 Field')):
                    $returnErrorMessages = explode("'", $errorMessages->getMessage());
                    $returnErrorMessages = !empty($returnErrorMessages[1]) ? $returnErrorMessages[1] : $returnErrorMessages;
                    //$returnErrorMessages = 'O item "' . $returnErrorMessages . '" é obrigatório. Verifique!';
                    break;

                default:
                    $returnErrorMessages = $errorMessages->getMessage();
                    break;
            }
        }
    
        if ( is_object($status) and (!empty($status->status) or !empty($status->getCode())) ) {
            $status = (int)($status->status ?? $status->getCode());
        }

        if (is_object($errorMessages) and !empty($errorMessages->statusText)) {
            $returnErrorMessages = $errorMessages->statusText;
        }

        if (!is_string($returnErrorMessages)) {
            $returnErrorMessages = 'O sistema não conseguiu tratar a mensagem de erro';
        }

        $status = is_int($status) ? $status : 404;
        $response = [
            'status' => $status,
            'success' => false,
            'message' => $returnErrorMessages,
        ];
        return response()->json($response, $status, $extra);
    }
}
