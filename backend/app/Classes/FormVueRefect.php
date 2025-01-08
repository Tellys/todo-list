<?php

namespace App\Classes;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FormVueRefect
{
    public $error = 'Erro não identificado no sistem! Tente novamente';
    public $var = [];
    public $values = [];

    public function mountSchema($var, $values = [])
    {
        $return = [];
        $this->var = $var;
        $this->values = $values;

        if (!empty($var['config'])) {
            $return['config'] = $var['config'];
            unset($var['config']);
        }

        foreach ($var as $k => $v) {

            if (!is_array($v)) {
                return false;
            }

            $return[$k]['name'] = $k;
            $return[$k]['type'] = false;

            //  ref="name_column"
            /**
             * mounted() {
             *   this.$refs.name_column.change()
             * }
             */
            $return[$k]['ref'] = $return[$k]['name']; //

            if (!empty($values[$k])) {

                if (is_numeric($values[$k])) {
                    $values[$k] = (int) $values[$k];
                }

                $return[$k]['default'] = $values[$k];
            }

            if (old($k)) {
                $return[$k]['default'] = old($k);
            }

            // if (!empty($v['vMaska'])) {
            //     //$return[$k]['attrs'] = "'v-maska data-maska': '".$v['vMaska']."'";
            //     //$return[$k][':mask'] = "['###', '###-#', '###-##']";
            //     $return[$k]['data-slots'] = "_";
            // }

            foreach ($v['html'] as $kHtml => $vHtml) {
                switch ($kHtml) {
                    case 'validation':
                        $return[$k]['rules'] = $this->rulesRefactoring($vHtml);
                        break;
                    case 'label':
                    case 'placeholder':
                    case 'disabled':
                    case 'readonly':
                    case 'info':

                    case 'step':
                    case 'min':
                    case 'max':
                    case 'maxlength':
                    case 'minlength':
                    case 'columns':

                        $return[$k][$kHtml] = $vHtml;
                        break;

                    case 'events':
                        foreach ($vHtml as $kEvents => $vEvents) {
                            //$return[$k][$kEvents] = $vEvents;
                            $return[$k][$kEvents] = $vEvents;
                        }
                        break;

                    default:
                        break;
                }
            }

            if (!empty($v['html']["helpText"])) {
                $return[$k]['description'] = $v['html']["helpText"];
            }

            // caso tenhamos determinado no nível de acesso
            if (!empty($v['html']['user_level_admin'])) {

                $sanctumUser = auth('sanctum')->user();

                if (!empty($sanctumUser->users_level_id) and config('app.user_level_admin') < $sanctumUser->users_level_id) {
                    $return[$k]['disabled'] = true;
                }
            }

            switch ($v['html']['fieldType']) {

                case 'text':
                    $return[$k]['type'] = 'text';
                    break;
                case 'number':
                    $return[$k]['type'] = 'text';
                    $return[$k]['inputType'] = 'number';
                    break;
                case 'email':
                    $return[$k]['type'] = 'text';
                    $return[$k]['inputType'] = 'email';
                    break;
                case 'tel':
                    $return[$k]['type'] = 'text';
                    $return[$k]['inputType'] = 'tel';
                    //$return[$k]['v-maska data-maska'] = '[(##) ####-####, (##) # ####-####]';
                    break;
                case 'toggle':
                case 'location':
                    $return[$k]['type'] = $v['html']['fieldType'];
                    break;

                case 'password':
                    $return[$k]['type'] = 'text';
                    $return[$k]['inputType'] = 'password';
                    unset($return[$k]['default']);
                    break;

                case 'location':
                    $return[$k]['type'] = 'text';
                    $return[$k]['inputType'] = 'location';
                    break;

                case 'select':
                    $return[$k]['type'] = $v['html']['fieldType'];
                    $return[$k]["items"] = $this->returnOptions($v, $k);
                    //$return[$k]["search"] = true;
                    //in_array selected
                    break;

                case 'radio':
                    $return[$k]['type'] = 'radiogroup';
                    $return[$k]["items"] = $this->returnOptions($v, $k);
                    $return[$k]['view'] = 'blocks';
                    //$return[$k]['columns'] = ['container'=> 12, 'label'=> 3, 'wrapper'=> 12];

                    if ($k == 'icon_id') {
                        foreach ($return[$k]["items"] as $kRadio => $vRadio) {
                            //dd($kRadio , $vRadio, $return[$k]["items"][$kRadio]->label);
                            $return[$k]["items"][$kRadio]->label = '<i class=" mx-3 fs-3 ' . $vRadio->label . '"></i> <small>' . $vRadio->label . '<small>'; //<i class="bi bi-plus-circle"></i>
                        }
                    }

                    break;

                case 'checkbox':
                    $return[$k]['type'] = 'checkboxgroup';
                    $return[$k]['view'] = 'blocks';
                    $return[$k]["items"] = $this->returnOptions($v, $k);

                    if (!empty($return[$k]['default']) and !is_array($return[$k]['default'])) {
                        $return[$k]['default'] = explode(',', $return[$k]['default']);
                    }

                    break;

                case 'longText':
                case 'longtext':

                    $return[$k]['type'] = 'textarea';
                    $return[$k]['view'] = 'blocks';
                    //$return[$k]['value'] = $values[$k];
                    break;

                case 'textarea':

                    $return[$k]['type'] = 'editor';
                    $return[$k]['view'] = 'blocks';
                    $return[$k]['acceptMimes'] = [
                        'image/jpeg',
                        'image/png',
                        'image/gif',
                    ];
                    //$return[$k]['value'] = $values[$k];
                    break;

                    /* image
                    */
                case 'file':

                    $return[$k]['type'] = 'file';
                    $return[$k]['view'] = 'image';
                    $return[$k]['accept'] = 'image/*';
                    $return[$k]['drop'] = true;
                    $return[$k]['softRemove'] = false;
                    $return[$k]['rules'] = ['mimetypes:image/jpeg,image/png,image/gif'];
                    //$return[$k]['view'] = 'gallery';
                    $return[$k]['auto'] = false;
                    $return[$k]['submit'] = false;
                    $return[$k]['object'] = true;
                    $return[$k]['endpoint'] = false;

                    if (!empty($return[$k]['default'])) {
                        $return[$k]['default'] = '/' .
                            (explode(
                                '//',
                                url(
                                    Storage::url($return[$k]['default'])
                                )
                            )[1]
                            );
                    }

                    if (!empty($values[$k])) {
                        $return[$k]['value'] = $this->isImage($values[$k]);
                    }

                    break;

                    /**
                     * caso date
                     */
                case 'date':

                    $return[$k]['type'] = 'date';
                    $return[$k]['displayFormat'] = 'DD/MM/YYYY';
                    //$return[$k]['valueFormat'] = 'YYYY MM DD';

                    if (!empty($return[$k]['default'])) {
                        $return[$k]['default'] = \Illuminate\Support\Carbon::parse($return[$k]['default'])->toDateString();
                    }

                    break;
                case 'datetime':

                    $return[$k]['type'] = 'date';
                    $return[$k]['displayFormat'] = 'DD/MM/YYYY';
                    $return[$k]['time'] = true;

                    if (!empty($return[$k]['default'])) {
                        $return[$k]['default'] = \Illuminate\Support\Carbon::parse($return[$k]['default'])->toDateString();
                    }

                    break;
                case 'time':

                    $return[$k]['type'] = 'date';
                    $return[$k]['time'] = true;
                    $return[$k]['date'] = false;

                    if (!empty($return[$k]['default'])) {
                        //$return[$k]['default'] = \Illuminate\Support\Carbon::parse($return[$k]['default'])->toDateString();
                        //$return[$k]['default'] = \Illuminate\Support\Carbon::parse($return[$k]['default'])->format('h:i');
                        //$return[$k]['default'] = \Illuminate\Support\Carbon::parse($return[$k]['default'])->format('h');
                    }

                    break;
                case 'timeString': //the values into db iys string

                    $return[$k]['type'] = 'date';
                    $return[$k]['time'] = true;
                    $return[$k]['date'] = false;
                    $return[$k]['displayFormat'] = 'HH:MM';

                    break;

                    /**
                     * caso hidden
                     */
                case 'hidden':
                    $return[$k]['type'] = 'hidden';
                    break;

                case NULL:
                case FALSE:
                    unset($return[$k]);
                    break;

                    /**
                     * caso default
                     */
                default:
                    $return[$k]['type'] = 'text';
                    break;
            }

            if (empty($return[$k]['value'])) {
                unset($return[$k]['value']);
            }

            if (!empty($return[$k]['value'])) {
                $return[$k]['default'] = $return[$k]['value'];
            }

            if (empty($return[$k]['default']) and !empty($v['db']['default'])) {
                $return[$k]['default'] = $v['db']['default'];
            }

            if (!empty($v['mask'])) {
                $return[$k]['mask'] = $v['mask'];
            }
        }

        return $return;
    }

    public function returnOptions($var = null, $k)
    {
        if (empty($var['html']["options"])) {
            $this->returnError('Options ' . $k . ' Esta indefinido');
        }

        $options = $var['html']["options"];
        $fildes = $options;

        if (!is_array($options)) {

            if (substr($options, 0, 2) == 'db') {
                $tableModelSelect = substr($options, 3);
                $fildes = DB::table($tableModelSelect . ' as t')->orderBy('name', 'ASC')->get(['id as value', 'name as label'])->toArray();
            }

            if (substr($options, 0, 3) == 'url') {
                $tableModelSelect = substr($options, 3);
                $fildes = DB::table($tableModelSelect . ' as t')->orderBy('name', 'ASC')->get(['id as value', 'name as label'])->toArray();
            }

            /* $myConstrained = \Illuminate\Support\Str::singular($tableModelSelect) . '_id';
            $myConstrained = explode(',', $this->values[$myConstrained]);
            $registers = DB::table($tableModelSelect . ' as t')
                ->whereIn('id', $myConstrained)
                ->get(['id as value', 'name as label'])->toArray();

            if ($registers) {
                foreach ($fildes as $key => $value) {
                    if (!empty($registers[$key])) {
                        $fildes[$key]->default = true;
                    }
                }
            } */
        }

        // if (!is_array($fildes)) {
        //     $this->returnError('Options ' . $fildes . ' precisa ser um array. Ou esta indefinido');
        // }

        return $fildes;
    }

    function returnError($error = '')
    {
        return $error ?? $this->error;
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

    private function rulesRefactoring($var)
    {

        $return = $var;

        if (is_array($var)) {
            $return = [];

            foreach ($var as $k => $v) {
                switch ($v) {
                    case str_contains($v, 'unique:'):
                    case str_contains($v, 'decimal'):
                    case str_contains($v, 'cpfValidation'):
                    case str_contains($v, 'cnpjValidation'):
                    case str_contains($v, 'cpfCnpjValidation'):
                        break;

                    default:
                        $return[] = $v;
                        break;
                }
            }
        }

        return $return;
    }
}
