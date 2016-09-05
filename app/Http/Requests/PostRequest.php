<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class PostRequest extends Request
{
    public $minTitle = 6;
    public $minBody = 10;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'         =>'required|min:'.$this->minTitle,
            'body'          =>'required|min:'.$this->minBody,
            'user_id'       =>'required|numeric',
            'category_id'   =>'required|numeric',
            'tags'          =>'required',
            ];
    }

    public function messages()
    {
        return [
            'title.required'        =>'El titulo es requerido',
            'title.min'             =>'El titulo debe tener almenos '.$this->minTitle.' caracteres',
            
            'body.required'         =>'El cuerpo del post es requerido',
            'body.min'              =>'El cuerpo del post debe tener almenos '.$this->minBody.' caracteres',
            
            'user_id.required'      =>'El usuario es requerido',
            'user_id.numeric'      =>'El usuario debe ser un numero',

            'category_id.required'  =>'La categoria es requerido',
            'category_id.numeric'      =>'La categoria debe ser un numero',

            'tags.required'         =>'Es necesario al menos una etiqueta',
        ];
    }
}
