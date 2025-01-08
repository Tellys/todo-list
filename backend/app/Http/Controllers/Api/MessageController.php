<?php

namespace App\Http\Controllers\Api;

use App\Models\Message;
use Illuminate\Support\Facades\File;

class MessageController extends BaseController
{
    public string $messageShouldBroadcast = 'schedule'; //or now
    
    public int|array $messageSendToUsers;
    public string $messageTitle = 'Temos uma mensagem para você!';
    public string $messageBody = 'Temos uma mensagem para você!';
    public string $messageTemplate;
    public string $messageStatus = 'unread';
    public string $messageLevel = 'info';
    public array $varsToReplace;

    //private
    private string $messageTemplatePath;

    ///
    public function __construct()
    {
        $this->Model = new Message();

        $this->data['campos'] = $this->Model->campos;
        $this->NamefolderImage = 'message';
        $this->PathsView = 'Message';
        $this->PathsRoute = 'message';
        $this->ClassModel = 'Message';
        $this->listVars['meta'] = [
            'title' => 'Lista de Message',
            'h1' => 'Lista de Message',
        ];
        $this->listVars['uriBase'] = 'message/';
        $this->listVars['heads'] = [
            'name',
            'message',
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


        $this->messageStatus = $this->data['campos']['status']['html']['default'] ?? 'unread';
        $this->messageLevel = $this->data['campos']['level']['html']['default'] ?? 'info';
    }

    public function __set ($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

    ///
    public function index()
    {
        $user_id = auth('sanctum')->user()->id;
        $this->Model->where('user_id', $user_id);

        return parent::index();
    }

    ///
    public function messageRead()
    {
        try {
            $userId = auth('sanctum')->user()->id; 
            $this->Model = $this->Model->messageToIdUser($userId);
            return parent::index();
        } catch (\Exception $e) {
            $this->message = $e;
            $this->status = $e;
        }

        return $this->sendError($this->message, $this->status);
    }

    ///
    public function messageNotRead()
    {
        try {
            $userId = auth('sanctum')->user()->id;
            $this->Model = $this->Model->messageToIdUser($userId, ['status', '<>', 'read']);
            return parent::index();
        } catch (\Exception $e) {
            $this->message = $e;
            $this->status = $e;
        }

        return $this->sendError($this->message, $this->status);
    }

    ///
    public function readAll()
    {

        try {

            $userId = auth('sanctum')->user()->id;
            $this->Model->messageToIdUser($userId, ['status', '<>', 'read'])->update(['status' => 'read']);

            $this->message = 'Sucesso';
            $this->status = 200;

            return $this->sendResponse('success', $this->message, $this->status);
        } catch (\Exception $e) {
            $this->message = $e;
            $this->status = $e;
        }

        return $this->sendError($this->message, $this->status);
    }

    /**
     * $this->messageTemplate
     * $this->messageVars
     * $this->messageSendToUser
     * $this->messageShouldBroadcast
     * 
     * or 
     * 
     * ([messageTemplate, messageVars, messageSendToUser, messageShouldBroadcast])
     */

     /*
    public string $messageShouldBroadcast = 'schedule'; //or now
    
    public int|array $messageSendToUsers;

    public string $messageTitle;
    public array $varsInMessageTitle;

    public string $messageBody;
    public string $messageTemplate;
    public $varsInMessageMessageBody;     
     

     */
    public function sendMassive(array $vars = null){
        
        $this->typeOfReturn = 'b';
        $request = [];


/*
1- loop para cada usuário
2- foi setado o body?
ou
3- foi setado o template?

2- loop para injetar as veriaveis no template
*/
/* 
$messageBody = $this->messageBody;

if (!empty($this->messageTemplate)) { 
    $messageBody = $this->getMessageTemplate();
}

foreach($vars['messageSendToUsers'] as $k=> $v){
    $this->replaceVarWithinTemplate()
} */



        //dd($vars['messageItemsDanamicToBody']);

        //try {

            foreach($vars['messageItemsDanamicToBody'] as $k=> $v){
                $this->messageTemplate = $vars['messageTemplate'];
                $this->messageVars = $v;
                $this->messageSendToUser = $v['user_id'];
                $this->messageShouldBroadcast = $vars['messageShouldBroadcast'] ?? null;

                //dd($this->messageVars);

                //dd('aa', $v, $this->messageSendToUser);

                //dd(str_replace('${{ id }}', $v['id'], $vars['messageTitle']) );

                $request[] = [
                    'name' => $vars['messageTitle'] ? str_replace('${{ id }}', $v['id'], $vars['messageTitle']) : 'Titulo da mensagem',
                    'message' => $this->replaceVarWithinTemplate(), // aqui pegamos o texto e substituimos as variaveis já injetando elas no template
                    //'user_id'=>auth('sanctum')->user()->id
                    'user_id'=>$this->messageSendToUser->id ?? $this->messageSendToUser['id'],
                    'created_at'=>now(),
                ];

            }

            dd($request);
            
            $r = $this->Model::insert($request);

            // criar o event para aviso da mensagem enviada

        //     $this->return = $r;
        //     $this->status = 200;
        //     $this->message = 'Mensagem Enviada com Sucesso!';
        //     return $this->returnSuccess();

        // } catch (\Exception $e) {
        //     $this->status = $e; //409;
        //     $this->message = $e; //$e->getMessage();
        // }
        
        // return $this->returnError();       
    }

    /**
     * $this->messageTemplate
     * $this->messageVars
     * $this->messageSendToUser
     * $this->messageShouldBroadcast
     * 
     * or 
     * 
     * ([messageTemplate, messageVars, messageSendToUser, messageShouldBroadcast])
     */
    public function send(int $messageSendToUser, array $vars = null){

        $this->messageTemplate = $vars['messageTemplate'] ?? $this->messageTemplate;
        $this->messageVars = $vars['messageVars'] ?? $this->messageVars;
        $this->messageSendToUser = $messageSendToUser ?? $this->messageSendToUser;
        $this->messageShouldBroadcast = $vars['messageShouldBroadcast'] ?? $this->messageShouldBroadcast;

        $this->typeOfReturn = 'b';

        try {
            $request = [
                'name' => $vars['title'] ?? 'Titulo da mensagem',
                'message' => $this->replaceVarWithinTemplate(), // aqui pegamos o texto e substituimos as variaveis já injetando elas no template
                //'user_id'=>auth('sanctum')->user()->id
                'user_id'=>$this->messageSendToUser->id ?? $this->messageSendToUser['id']
            ];

            $r = $this->Model::create($request);

            // criar o event para aviso da mensagem enviada

            $this->return = $r;
            $this->status = 200;
            $this->message = 'Mensagem Enviada com Sucesso!';
            return $this->returnSuccess();

        } catch (\Exception $e) {
            $this->status = $e; //409;
            $this->message = $e; //$e->getMessage();
        }
        
        return $this->returnError();       
    }

    ///
    private function getMessageTemplate() {

        list($folder, $file) = explode('.', $this->messageTemplate);

        $messageTemplatePath = resource_path().DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'messages'.DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR.$file.'.tpl';
        
        return File::exists($messageTemplatePath) ? $messageTemplatePath : throw new \Exception('O template message não existe');
    }

    /**
     * @return array
     */
    private function replaceVarWithinTemplate():array {

        $r = [];
        $content = $this->messageBody;
        $created_at = now();

        if (!empty($this->messageTemplate)) { 
            $messageBody = $this->getMessageTemplate();
            $content = File::get($this->messageTemplatePath);
        }

        foreach ($this->varsToReplace as $key => $value) {
            $r[] =
                [
                    'user_id' => $value['user_id'],
                    'body' => str_replace('{{$'.$key.'}}', $value, $content),
                    'title' => str_replace('{{$'.$key.'}}', $value, $messageTitle),
                    'created_at'=>$created_at,
                    'status'=> $this->messageStatus,
                    'level'=>$this->messageLevel,
                ];
        }
        
        return $r;
    }

    /***
     * parei aqui para fazer a função para disparo de mensagem de compra
     */
}
