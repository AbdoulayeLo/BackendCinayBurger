<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BurgerStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
//        return false;
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->request()->isMethod('post')) {
            return [
                'nom' => 'required|string|max:255',
                'description' => 'required|string',
                'prix' => 'required|integer',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
            ];
        }else{
            return [
                'nom' => 'required|string|max:255',
                'description' => 'required|string',
                'prix' => 'required|integer',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
            ];
        }
    }
}
