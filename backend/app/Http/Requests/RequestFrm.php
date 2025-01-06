<?php

namespace App\Http\Requests;

use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Storage;

class RequestFrm extends FormRequest
{
    public $rulesArray = array(
        'publish_at' => 'nullable|date',
    );

    public $messagesArray = array();

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; //false; // default is false
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->rulesArray;
        /* return [
            //
            'publish_at' => 'nullable|date',
        ]; */
    }

    /**
     * Retorna as mensagens da validação
     */

    public function messages()
    {
        return $this->messagesArray;
        /* return [
            //
        ]; */
    }

    /**
     * $fileExists = false(skip) || add || replace
     */
    public function uploadFile($var, $retunOnly = false, $fileExists = 'add')
    {
        $r = false;
        $extension = 'jpg';
        $path = !empty($var['path']) ? $var['path'] : 'uploads';
        $destinationPath = false;

        if (is_string($var['file'])) {
            $name = Str::slug(last(explode('/', $var['file'])));
            $image = file_get_contents($var['file']);
            $r['originalName'] = $name;
            $r['tmp'] = null;
        }

        if (!is_string($var['file'])) {
            $image = $var['file'];
            $name = Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME), '-'); // slug no nome do arquivo
            $extension = pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);
            $r['originalName'] = $image->getClientOriginalName();
            $r['tmp'] = pathinfo($image);
        }

        $name = substr($name, 0, 200); // por conta do tamamnho máximo varchar 255
        $imagePath = $path . '/' . $name . '.' . $extension; // path destino do arquivo

        //verifica a existencia do arquivo e o renomeia, se for o caso
        if (Storage::disk('public')->exists($imagePath)) {

            switch ($fileExists) {
                case 'add':
                    $i = 1;
                    while (Storage::disk('public')->exists($path . '/' . $name . '.' . $extension)) {
                        $name = str_replace('(' . ($i - 1) . ')', '', $name);
                        $name = (string) $name . "($i)";
                        $i++;
                    }
                    break;

                case 'replace':
                    break;

                default: //skip
                    $fileExists = false;
                    $destinationPath = $imagePath;
                    break;
            }
        }

        if ($fileExists and is_string($var['file'])) {
            Storage::disk('public')->put($path . '/' . $name . '.' . $extension, $image);
            $destinationPath = $path . '/' . $name . '.' . $extension;
        }

        if ($fileExists and !is_string($var['file'])) {
            $destinationPath = $image->storeAs($path, $name . '.' . $extension, 'public');
        }

        if (!$destinationPath) {
            return false;
        }

        $r['relativePathFile'] = asset('storage/' . $destinationPath);
        $r['pathFile'] = $destinationPath; // path destino do arquivo
        $r['nameFile'] = $name; // nome do arquivo
        $r['extensionFile'] = $extension;

        //caso set $retunOnly
        if ($retunOnly) {
            $r = $r[$retunOnly];
        }

        return $r;
    }

    public function removeFile($var)
    {
        //return [$var, !empty($var['file']['data'])];

        $imagePath = false;

        // case temporary flies
        if (!empty($var['file']['data'])) {
            $imagePath = $var['file']['data']['pathFile'];
        }

        // case permanent flies
        if (!empty($var['file'] and is_string($var['file']))) {
            $imagePath = $var['path'] . '/' . basename($var['file']);
        }

        try {
            return [$var, Storage::disk('public')->exists($imagePath), Storage::disk('public')->delete($imagePath)];
        } catch (\Exception $e) {
            return [$var, $e];
        }
    }
}
