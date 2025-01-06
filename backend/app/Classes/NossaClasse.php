<?php

namespace App\Classes;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NossaClasse
{

    public $tableBuild;
    public $jqueryDataRule = [
        'data-rule-required',
        'data-rule-email',
        'data-rule-url',
        'data-rule-date',
        'data-rule-dateISO',
        'data-rule-number',
        'data-rule-digits',
        'data-rule-creditcard',
        'data-rule-minlength',
        'data-rule-maxlength',
        'data-rule-rangelength',
        // 'data-rule-min',
        // 'data-rule-max', retireii pq estava dando proble
        'data-rule-range',
        'data-rule-equalto',
        'data-rule-remote',
        'data-rule-accept',
        'data-rule-bankaccountNL',
        'data-rule-bankorgiroaccountNL',
        'data-rule-bic',
        'data-rule-cifES',
        'data-rule-creditcardtypes',
        'data-rule-currency',
        'data-rule-dateITA',
        'data-rule-dateNL',
        'data-rule-extension',
        'data-rule-giroaccountNL',
        'data-rule-iban',
        'data-rule-integer',
        'data-rule-ipv4',
        'data-rule-ipv6',
        'data-rule-mobileNL',
        'data-rule-mobileUK',
        'data-rule-lettersonly',
        'data-rule-nieES',
        'data-rule-nifES',
        'data-rule-nowhitespace',
        'data-rule-pattern',
        'data-rule-phoneNL',
        'data-rule-phoneUK',
        'data-rule-phoneUS',
        'data-rule-phonesUK',
        'data-rule-postalcodeNL',
        'data-rule-postcodeUK',
        'data-rule-require_from_group',
        'data-rule-skip_or_fill_minimum',
        'data-rule-strippedminlength',
        'data-rule-time',
        'data-rule-time12h',
        'data-rule-url2',
        'data-rule-vinUS',
        'data-rule-zipcodeUS',
        'data-rule-ziprange'
    ];

    /**
     * $var = colunas a serem inseridas no BD geralmente definida na classe Model do referido DB a ser criado
     *
     * return $table->'.$v["type"].'('.$k.')'.$objetos.';';
     */
    public function migrations($var)
    {
        /* echo '<pre>';
        print_r($var);
        die(); */
        $return = '';

        foreach ($var as $k => $v) {
            /**
             *             'type'=>'string',
             *             'nullable'=>true,
             */
            $objetos = '';
            if (is_array($v)) {
                foreach ($v as $kk => $vv) {
                    switch ($kk) {
                        case 'type':
                        case 'validation':
                        case 'name':
                        case 'options':
                            break;
                        default:
                            $objetos .=  '->' . $kk;
                            $objetos .=  $vv === true ? '()' : '(' . $vv . ')';
                            break;
                    }
                }
                $return .= '$table->' . $v["type"] . '('
                    . $k . //pega o nome
                    (isset($v["type"]) ? ',' . $v["type"] : '') . //caso tenha opções de valores, ex: $table->enum('comentario', [null, 1] )
                    ')'
                    . $objetos . ';'; // coloca os demais objetos
                $return .= '<br>';
            }
        }
        echo '<pre>';
        print_r($return);
        die();
    }


    /**
     * construi o formulario a partir da var $colunas definida na classe Model do referido DB a ser criado
     *
     * é importante fazer o filtro para que nem todas os intens sejam convertidos em campos, temos q retirar as colunas não editaveis
     * a partir da var $fillable
     *
     *
     * <div class="mb-3">
     *    <x-input
     *     name="address2"
     *     :label="['text' => 'Address 2 <span class=\'text-muted\'>(Optional)</span>']"
     *     placeholder="Apartment or suite"
     *     />
     * </div>
     *
     * @return string
     */
    //public function FormBuild($var, $values = [])
    public function FormBuild($var, $values = [])
    {
        $return = '';
        foreach ($var as $k => $v) {

            $atributos = '';
            $label = '';
            $required = '';

            if (is_array($v)) {

                $jqueryValidateInLine = '';
                if (isset($v['html']['validation'])) {
                    $setValidate = $this->setValidate($v['html']['validation']);

                    $jqueryValidateInLine = isset($setValidate['jqueryValidateInLine']) ? $setValidate['jqueryValidateInLine'] : '';
                    //$required = !empty($setValidate['required']) ? ' * ' : ''; // é acrescentado um * aos campos 'required'

                    if (empty($setValidate['required'][0])) {
                        $required = ' * ';
                    }

                    if ($setValidate['required'] == false) {
                        $required = '';
                    }
                }

                //$atributos .= ' id="'.$k.'"'; // retirei pq estava dando stress
                $atributos .= ' name="' . $k . '"'; // use essa var para dar destaque aos campos obrigatórios

                // user_level_admin
                if (isset($v['html']['user_level_admin']) and $v['html']['user_level_admin']) {
                    if (config('app.user_level_admin') < Auth::user()->users_level_id) {
                        $atributos .= ' disabled ';
                    }
                }

                foreach ($v['html'] as $kHtml => $vHtml) {
                    switch ($kHtml) {
                        case 'fieldType':
                        case 'validation':
                        case 'options':
                        case 'storagePath':
                        case 'value':
                            break;

                        case 'label':
                            $label = $v['html']["label"];
                            break;

                        case 'placeholder':
                            $atributos .= ' placeholder="' . $v['html']["placeholder"] . '"';
                            break;

                        default:
                            /* echo '<pre>***';
                            print_r($kHtml); */
                            $atributos .= ' ' . $kHtml . '="' . $vHtml . '"';
                            break;
                    }
                }

                $helpText = !empty($v['html']["helpText"]) ? '<small id="' . $k . '" class="form-text text-muted">
                ' . $v['html']["helpText"] . '
                </small>' : '';

                if (old($k)) {
                    $values[$k] = old($k);
                }

                // caso set value default
                if (empty($values[$k])) {
                    $values[$k] = $v['db']['default'] ?? '';
                }

                if (is_numeric($values[$k])) {
                    $values[$k] = (int) $values[$k];
                }

                switch ($v['html']['fieldType']) {

                    case 'text':

                        $return .=
                            '<div class="form-group col-12 mb-3">
                            <label for="' . $k . '">' . $label . $required . $helpText . '</label>
                            <input type="text"
                            class="form-control"
                            value="' . $values[$k] . '"
                            id="' . $k . '"
                            ' . $atributos . '
                            ' . $jqueryValidateInLine . '
                            >
                        </div>';

                        break;

                        //case strpos($v['type'], 'enum'):
                    case 'select':

                        if (empty($v['html']["options"])) {
                            die('Options ' . $k . ' Esta indefinido');
                        }

                        if (!is_array($v['html']["options"]) and substr($v['html']["options"], 0, 2) == 'db') {
                            $tableModelSelect = substr($v['html']["options"], 3);
                            $v['html']["options"] = DB::table($tableModelSelect)->orderBy('name', 'ASC')->get(['id', 'name'])->toArray();
                        }

                        if (!is_array($v['html']["options"])) {
                            die('Options ' . $k . ' precisa ser um array. Ou esta indefinido');
                        }

                        $return .=
                            '<div class="form-group col-md-4 mb-3">
                            <label for="' . $k . '">' . $label . $required . '</label>
                                <select
                                class="custom-select d-block w-100"
                                id="' . $k . '"
                                ' . $atributos . '
                                ' . $jqueryValidateInLine . '
                                >
                                ' . $this->multiOptions(
                                $v['html']["options"] ?? $v['db']["options"],
                                $values[$k],
                                $v['html']["icons"] ?? false
                            ) . '
                                </select>
                                ' . $helpText . '
                        </div>';

                        break;


                    case 'radio':

                        if (isset($v['html']["options"]) and !is_array($v['html']["options"]) and substr($v['html']["options"], 0, 2) == 'db') {
                            $tableModelSelect = substr($v['html']["options"], 3);
                            $v['html']["options"] = DB::table($tableModelSelect)->orderBy('name', 'ASC')->get(['id', 'name'])->toArray();
                        }

                        if (isset($v['html']["options"]) and !is_array($v['html']["options"])) {
                            die('Options ' . $k . ' precisa ser um array. Ou esta indefinido');
                        }

                        $return .=
                            '<div class="form-group col-md-4 mb-3">
                            <label for="' . $k . '" class="row">' . $label . $required . '</label>';

                        $return .= $helpText;

                        /* echo '<pre>';
                        print_r($v['html']["options"])
                        ;die; */

                        foreach ($v['html']["options"] as $radio_k => $radio_v) {

                            $radioChecked = $values[$k] == ($radio_v->id ?? $radio_k) ? 'checked' : '';

                            $return .=
                                '<div class="form-check form-check-inline m-2">
                                <input class="form-check-input" type="radio"
                                id="' . $k . ($radio_v->id ?? $radio_k) . '"
                                name="' . $k . '"
                                ' . $atributos . '
                                value="' . ($radio_v->id ?? $radio_k) . '"
                                ' . $radioChecked . '
                                >';

                            $return .= '<label class="form-check-label" for="' . $k . ($radio_v->id ?? $radio_k) . '">';

                            if (isset($v['html']["icons"]) and $v['html']["icons"]) {
                                $return .= '<i class="' . $radio_v->name  . ' m-1"></i>';
                            } else {
                                $return .= ($radio_v->name ?? $radio_v) . $required;
                            }

                            $return .=
                                '</label>
                            </div>';
                        }
                        $return .= '</div>';

                        break;

                    case 'checkbox':

                        if (isset($v['html']["options"]) and !is_array($v['html']["options"]) and substr($v['html']["options"], 0, 2) == 'db') {
                            $tableModelSelect = substr($v['html']["options"], 3);
                            $v['html']["options"] = DB::table($tableModelSelect)->orderBy('name', 'ASC')->get(['id', 'name'])->toArray();
                        }

                        if (isset($v['html']["options"]) and !is_array($v['html']["options"])) {
                            die('Options ' . $k . ' precisa ser um array. Ou esta indefinido');
                        }

                        $return .=
                            '<div class="form-group col-md-4 mb-3">
                            <label for="' . $k . '" class="row">' . $label . $required . '</label>';


                        foreach ($v['html']["options"] as $checkbox_k => $checkbox_v) {

                            $checkboxExplode = explode(',', $values[$k]);

                            $checkboxChecked = '';

                            // se options for um objeto db
                            if (!empty($tableModelSelect) and in_array($checkbox_v->id, $checkboxExplode)) {
                                $checkboxChecked = 'checked';
                            }

                            // se options for um array statico
                            if (empty($tableModelSelect) and in_array($checkbox_k, $checkboxExplode)) {
                                $checkboxChecked = 'checked';
                            }

                            $return .=
                                '<div class="form-check m-2">
                                    <input class="form-check-input" type="checkbox"
                                    value="' . ($checkbox_v->id ?? $checkbox_k) . '"
                                    id="' . $k . ($checkbox_v->id ?? $checkbox_k) . '"
                                    name="' . $k . '[]"
                                    ' . $atributos . '
                                    ' . $checkboxChecked . '
                                    >';

                            $return .= '<label class="form-check-label" for="' . $k . ($checkbox_v->id ?? $checkbox_k) . '">';

                            if (isset($v['html']["icons"]) and $v['html']["icons"]) {
                                $return .= '<i class="' . $checkbox_v->name  . ' m-1"></i>';
                            } else {
                                $return .= ($checkbox_v->name ?? $checkbox_v) . $required;
                            }

                            $return .=
                                '</label>
                                    ' . $helpText . '
                                </div>';
                        }
                        $return .= '</div>';

                        break;


                    case 'longText':
                    case 'longtext':

                        /*  $return .=
                            '<div class="form-group col-12 mb-3">
                            <label for="' . $k . '">' . $label . '</label>
                            <input type="text"
                                class="form-control"
                                id="' . $k . '"
                                value="' . $values[$k] . '"
                                ' . $atributos . '
                                ' . $jqueryValidateInLine . '
                                rows="3"
                            >
                            ' . $helpText . '
                        </div>'; */

                        $return .= '<div class="form-group col-12 mb-3">
                        <label for="' . $k . '" class="form-label">' . $label . '</label>
                        ' . $helpText . '
                            <textarea class="form-control"
                            ' . $atributos . '
                            ' . $jqueryValidateInLine . '
                            id="' . $k . '"
                            >
                            ' . $values[$k] . '
                            </textarea>
                        </div>';

                        break;


                        /* texto
                          <textarea name="descricao" id="summernote" placeholder="Escreva bastantes detalhes, ser&aacute; bom para o Google"><?=@ trim($this->vars['conteudo'][0]['descricao']);?></textarea>
                    */
                    case 'textarea':

                        $return .=
                            '<div class="mb-3">
                                <textarea
                                ' . $atributos . '
                                ' . $jqueryValidateInLine . '
                                id="summernote"
                                >
                                ' . $values[$k] . '
                                </textarea>
                                ' . $helpText . '
                            </div>';

                        break;


                        /* image
                    */
                    case 'file':

                        $return .=
                            '<div class="form-group col-12 mb-3">
                            <label for="' . $k . '">' . $label . $required . '</label>
                            ';

                        $return .= $helpText;

                        $isImage = !empty($values[$k]) ? $this->isImage(
                            //$this->storagePath($v['html']). //pega a path storage
                            $values[$k]
                        ) : false;

                        $collapse = '';

                        if ($isImage) {

                            $imgCollapseId = uniqid();
                            $return .= '
                                <div>
                                    <img src="' . $isImage . '" alt="' . $label . '" style="max-height:100px" class="img-thumbnail">
                                    <a class="btn btn-outline-dark" data-toggle="collapse" href=".collapse' . $imgCollapseId . '" role="button" aria-expanded="false" aria-controls="collapse' . $imgCollapseId . '">
                                    Trocar Imagem
                                    </a>
                                </div>
                                ';
                            // da valor à div abaixo para funcionar no esquema collapse
                            $collapse = 'class="collapse collapse' . $imgCollapseId . '"';
                        }

                        $return .= '
                            <div ' . $collapse . '>
                                <input type="file"
                                    class="form-control"
                                    value="' . $values[$k] . '"
                                    id="' . $k . '"
                                    ' . $atributos . '
                                    ' . $jqueryValidateInLine . '
                                >
                            </div>';

                        $return .=  '</div>';

                        break;


                        /**
                         * caso date
                         */
                    case 'date':
                        $return .= //Blade::compileString(
                            '<div class="form-group col-md-4 mb-3">
                                <label for="' . $k . '">' . $label . $required . '</label>
                                <input
                                    type="' . $v['html']['fieldType'] . '"
                                    class="form-control"
                                    value="' . \Carbon\Carbon::parse($values[$k])->format('Y-m-d') . '"
                                    id="' . $k . '"
                                    ' . $atributos . '
                                    ' . $jqueryValidateInLine . '
                                >
                                ' . $helpText . '
                            </div>';
                        break;


                        /**
                         * caso hidden
                         */
                    case 'hidden':
                        $return .= //Blade::compileString(
                            '<input
                                type="' . $v['html']['fieldType'] . '"
                                class="form-control"
                                value="' . $values[$k] . '"
                                id="' . $k . '"
                                ' . $atributos . '
                                ' . $jqueryValidateInLine . '
                            >';
                        break;

                    case NULL:
                        break;

                        /**
                         * caso default
                         */
                    default:

                        $return .= //Blade::compileString(
                            '<div class="form-group col-md-4 mb-3">
                                <label for="' . $k . '">' . $label . $required . '</label>
                                <input
                                    type="' . $v['html']['fieldType'] . '"
                                    class="form-control"
                                    value="' . $values[$k] . '"
                                    id="' . $k . '"
                                    ' . $atributos . '
                                    ' . $jqueryValidateInLine . '
                                >
                                ' . $helpText . '
                            </div>';

                        break;
                }
            }
        }

        return $return;
    }

    /**
     * retorna a child 'validation', caso ela exista no array
     * é usada para dar conteudo a função $request->validate()
     * @return $r[label] = [validate|rules]
     */
    public function colunasValidate($var, $id = NULL)
    {
        $r = array();
        foreach ($var as $k => $v) {
            if (!empty($v['html']['validation'])) {

                $r[$k] = $v['html']['validation'];
                if (!empty($id)) {

                    if (is_array($r[$k])) {
                        foreach ($r[$k] as $kk => $vv) {
                            //dd($vv);

                            /* if(is_int(strpos('unique',$vv))){
                                dd($vv);
                            } */

                            if (count(explode('unique', $vv)) > 1) {
                                $r[$k][$kk] = $vv . ',' . $id;
                            }
                        }
                    } 
                    // else {
                    //     die('Uma variável de validação do sistema esta com erro!: '.$k);
                    // }
                }
            }
        }
        return $r;
    }

    /**
     * list jquery validate inline rules https://gist.github.com/vishnukumarpv/cbac03386857e5a6204b09e56084d0be
     * is validate = return data
     * ex <input type="text" name="password-confirm" data-rule-required="true" data-rule-minlength="6" data-rule-equalsto="#password">
     * @return $r['jqueryValidateInLine'] = data-rule-required="true" data-rule-minlength="6" data-rule-equalsto="#password"
     *
     * -----
     *
     * is required = @return $r['required'] = true
     */
    private function setValidate($var)
    {
        $r = array();
        $r['jqueryValidateInLine'] = '';
        switch ($var) {
            case '':
                return false;
                break;

            case is_array($var):

                $r['required'] = in_array("required", $var);

                /**
                 *  verifica a existencia do data-rule dentro da lista $this->jqueryDataRule
                 *  ex $r['jqueryValidateInLine'] .= 'data-rule-required="true" data-msg-required="Required field"';
                 *  'validation'=> ['required','max:255',],
                 */

                foreach ($var as $k => $v) {
                    // verifica se temos regra 'max:255'
                    $varExplodeV = explode(':', $v);
                    if (count($varExplodeV) > 1) {
                        $r['jqueryValidateInLine'] .= in_array('data-rule-' . $varExplodeV[0], $this->jqueryDataRule, true) ? 'data-rule-' . $varExplodeV[0] . '="' . $varExplodeV[1] . '" ' : '';
                    } else {
                        $r['jqueryValidateInLine'] .= in_array('data-rule-' . $varExplodeV[0], $this->jqueryDataRule, true) ? 'data-rule-' . $varExplodeV[0] . '="true" ' : '';
                    }
                }
                break;

            default:

                $r['required'] = explode('required', $var);

                $var = explode('|', $var);

                //'validation'=> 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048|dimensions:min_width=100,min_height=100,max_width=1000,max_height=1000',
                foreach ($var as $k => $v) {
                    // verifica se temos regra 'max:255'
                    $varExplodeV = explode(':', $v);
                    if (count($varExplodeV) > 1) {
                        $r['jqueryValidateInLine'] .= in_array('data-rule-' . $varExplodeV[0], $this->jqueryDataRule, true) ? 'data-rule-' . $varExplodeV[0] . '="' . $varExplodeV[1] . '" ' : '';
                    } else {
                        $r['jqueryValidateInLine'] .= in_array('data-rule-' . $varExplodeV[0], $this->jqueryDataRule, true) ? 'data-rule-' . $varExplodeV[0] . '="true" ' : '';
                    }
                }

                break;
        }
        return count($r) ? $r : false;
    }

    /* private function jqueryValidateInLine($var){ //|
        //print_r($var).'<br>';
        $var = is_array($var) ? $var : explode('|', $var);
        //dd($var); die();
        $r = array();
        foreach ($var as $k => $v) {
            if(isset($v['validation'])){
                $r[$k] = $v['validation'];
            }
        }
        return $r;
    }

    private function labelRequired($var = '', $destaque = ' * '){
        switch ($var) {
            case '':
                return false;
                break;

            case is_array($var):
                return in_array("required", $var);
                break;

            case explode('required', $var):
                return true;
                break;
        }


    } */


    /**
     * criar slug
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    /**
     * checa se é um array multi ou comum
     */
    public function is_multidimensional(array $array)
    {
        foreach ($array as $part) {
            if (is_array($part)) return true;
        }
        return false;
    }

    public function arrayToString(array $var)
    {
        // vamos retornar uma string assim:
        //- se multi array {b{a[aa,bb]}}
        //- se array simples [a,b]

        $r = '[';
        foreach ($var as $k => $v) {
            //se array, faz o loop
            if (is_array($v)) {
                $r .= $this->arrayToString($v);
            } else {
                $r .= "'" . $v . "',";
            }
        }
        return $r .= ']';
    }

    public function multiOptions($var, $values = [], $icons = false)
    {
        $r = '<option value=""></option>';

        foreach ($var as $k => $v) {
            //se array, faz o loop
            if (is_array($v)) {
                $r .= '<optgroup label="' . $k . '">';
                $r .= $this->multiOptions($v);
                $r .= '</optgroup>';
            } elseif (is_object($v)) {
                if (is_array($values)) {
                    $selected = (array_search($v->id, $values) ? 'selected' : '');
                } else {
                    $selected = ($v->id === $values ? 'selected' : '');
                }

                $r .=
                    '<option value="' . $v->id . '" ' . $selected . '>';

                if ($icons) {
                    //$r .= '<i class="'.$v->name.'"></i>';
                }
                $r .= '<i class="' . $v->name . '"></i>';

                $r .= $v->name;
                $r .= '</option>';
            } else {
                if (is_array($values)) {
                    $selected = (array_search($k, $values) ? 'selected' : '');
                } else {
                    $selected = ($k === $values ? 'selected' : '');
                }

                $r .=
                    '<option value="' . $k . '" ' . $selected . '>';

                if ($icons) {
                    //$r .= '<i class="'.$v.'"></i>';
                }

                $r .= $v;
                $r .= '</option>';
            }
        }
        //die(print_r($r));
        return $r;
    }

    /**
     * cria as diretivas para a migration
     * $fileModel = $data['campos'] daquele arquivo
     */
    public function createMigratarions(array $fileModelDataCampos)
    {
        $r = '';
        $public_fillable = '';
        //    DB::statement('ALTER TABLE payroll ADD CONSTRAINT chk_salary_amount CHECK (salary < 150000.00);');
        foreach ($fileModelDataCampos as $k => $v) {

            $public_fillable .= "'" . $k . "',";
            $public_fillable .= "<br>";

            switch ($k) {

                case 'created_at':
                case 'updated_at':
                case 'deleted_at':
                    break;

                default:

                    foreach ($v['db'] as $kk => $vv) {

                        switch ($kk) {

                            case 'type':
                                //caso tenha valores definidos. Ex:[null, 1] em $table->enum('comentario', [null, 1] )
                                //$values = !empty($v['html']["options"]) ? ',' . json_encode($v['html']["options"]) : '';
                                $values = '';

                                if ($vv == 'enum' and !empty($v['html']["options"])) {
                                    if (is_array($v['html']["options"])) {
                                        $values = ',' . json_encode($v['html']["options"]);
                                    }
                                }

                                // monta a linha
                                $r .= '$table->' . $vv . '("' . $k . '"' . $values . ')';
                                break;

                                //invalidando a label options pois ela é acrescentada acima
                            case 'options':
                                break;

                                //default
                            default:
                                if ($vv === true) {
                                    $vv = '';
                                } elseif (!is_numeric($vv)) {
                                    $vv = '"' . $vv . '"';
                                }
                                $r .= '->' . $kk . '(' . $vv . ')';
                                break;
                        }
                    }
                    $r .= ';';
                    $r .= '<br>';

                    break;
            }
        }

        return $r . $public_fillable;
    }

    /**
     * Cria os dados dentro de uma tabela
     * $var = array
     */
    public function TableBuild($var = [])
    {

        $db = new $var['class'];

        $r = $var;

        $r['meta'] = isset($var['meta']) ? $var['meta'] : [
            'title' => 'Lista',
            'h1' => 'Lista',
        ];

        $r['heads'] = array_intersect($r['heads'] ?? [], $db->fillable);

        // inseri o id no começo do array
        array_splice($r['heads'], 0, 0, 'id');

        $r['dbTrash'] = $db::onlyTrashed();
        $r['db'] = $var['db'] ?? $db::paginate(30, $r['heads']);

        $r['meta']['trash'] = $r['dbTrash']->count();
        $r['meta']['data'] = $r['db']->count();

        //pega os lixos
        if (!empty($_REQUEST['trash'])) {
            $r['db'] = $r['dbTrash']->paginate(30, $r['heads']);
            $r['comandos']['show'] = $r['comandos']['edit'] = $r['comandos']['destroy'] = null;
        }

        if (empty($_REQUEST['trash'])) {
            $r['comandos']['restore'] = $r['comandos']['forceDelete'] = null;
        }

        $r['db'] = $r['db']->toArray();

        foreach ($r['heads'] as $k => $v) {
            switch ($v) {
                case 'id':
                    $r['heads_1'][$v] = [
                        'label' => 'id',
                        'type' => 'int',
                    ];
                    break;

                default:
                    $r['heads_1'][$v] = [
                        'label' => $db->campos[$v]['html']['label'],
                        'type' => $db->campos[$v]['db']['type'],
                    ];
                    break;
            }
        }

        $r['heads'] = $r['heads_1'];

        $r['heads']['comandos'] = 'CMD';

        foreach ($r['db']['data'] as $k => $v) {
            foreach ($v as $kk => $vv) {
                switch ($r['heads'][$kk]['type']) {
                        // se o campo for nos formatos abaixo, formata os dados
                    case 'date':
                    case 'timestamp':
                        // modifica a data conforme padrão
                        $r['db']['data'][$k][$kk] = \Carbon\Carbon::parse($vv)->format('d-m-Y H:i:s');
                        //die;
                        break;

                    case 'enum':
                        //dd(substr($db->campos[$kk]['html']['options'][$vv], 0, strlen('db.')));
                        if (is_array($db->campos[$kk]['html']['options'])) {
                            $r['db']['data'][$k][$kk] = $db->campos[$kk]['html']['options'][$vv] ?? $vv;
                            break;
                        }

                        if (substr($db->campos[$kk]['html']['options'][$vv], 0, strlen('db.')) == 'db.') {
                            $tableModelSelect = (array) explode('db.', $db->campos[$kk]['html']['options']);
                            $r['db']['data'][$k][$kk] = DB::table($tableModelSelect[1])->where('id', $vv)->get(['id', 'name'])->first();
                            $r['db']['data'][$k][$kk] = $r['db']['data'][$k][$kk]->name;
                            break;
                        }

                        $r['db']['data'][$k][$kk] = 'null';
                        break;

                        //$r['db']['data'][$k][$kk] = $db->campos[$kk]['html']['options'][$vv];
                        //break;

                    case 'foreignId':
                        if (!empty($db->campos[$kk]['html']['options'])) {
                            $tableModelSelect = (array) explode('db.', $db->campos[$kk]['html']['options']);
                            $r['db']['data'][$k][$kk] = DB::table($tableModelSelect[1])->where('id', $vv)->get(['id', 'name'])->first();
                            $r['db']['data'][$k][$kk] = $r['db']['data'][$k][$kk]->name ?? '';
                        }
                        break;

                    case 'decimal':
                        if (strpos($kk, 'price') !== false) {
                            $r['db']['data'][$k][$kk] = 'R$ ' . number_format($vv, 2, ',', '.');
                        }
                        break;

                    default:
                        # code...
                        break;
                }
            }
        }

        //dd($r['db']);
        return $r;

    }

    /*
    AJAX request
    */
    public function TableBuildGetEmployees($request)
    {
        die('ok');

        //https://yajrabox.com/docs/laravel-datatables/master/installation


        ## Read value
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        // Total records
        $db = new $this->tableBuild['class'];
        $totalRecords = $db::select('count(*) as allcount')->count();
        $totalRecordswithFilter = $db::select('count(*) as allcount')->where('name', 'like', '%' . $searchValue . '%')->count();

        //$totalRecords = DB::table($tableModelSelect[1])->select('count(*) as allcount')->count();
        //$totalRecordswithFilter = DB::table($tableModelSelect[1])->select('count(*) as allcount')->where('name', 'like', '%' .$searchValue . '%')->count();

        // Fetch records
        $records = $db::orderBy($columnName, $columnSortOrder)
            ->where('name', 'like', '%' . $searchValue . '%')
            ->select('*')
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();

        foreach ($records as $record) {
            $id = $record->id;
            $name = $record->username;
            //$name = $record->name;
            //$user_id = $record->user_id;

            $data_arr[] = array(
                "id" => $id,
                "name" => $name,
                //"name" => $name,
                //"email" => $email
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        echo json_encode($response);
        exit;
    }

    public function isImage(string $var)
    {
        if (file_exists('.' . $var) and exif_imagetype('.' . $var)) {
            //$allowedMimeTypes = ['image/jpeg','image/gif','image/png','image/bmp','image/svg+xml'];
            //return mime_content_type($var);
            return $var;
        }
        return false;
    }

    public function storagePath($var = '')
    {
        if (isset($var['storagePath'])) {
            $var = DIRECTORY_SEPARATOR . $var['storagePath'];
        }
        //$var = !empty($var) ? DIRECTORY_SEPARATOR.$var : '';
        return DIRECTORY_SEPARATOR . 'uploads' . $var;
    }
}
