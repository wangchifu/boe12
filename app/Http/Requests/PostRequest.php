<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
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
            'title' => 'required',
            'content' => 'required',
            'files.*' => 'nullable|mimes:csv,txt,zip,jpg,jpeg,png,gif,pdf,odt,ods|max:10240',
            'photos.*' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:5120',

        ];
    }
    public function attributes()
    {
        return [
            'title' => '標題',
            'content' => '內容',
            'files.*' =>'附檔*',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => ':attribute 不可空白',
            'content.required' => ':attribute 不可空白',
        ];
    }

}
