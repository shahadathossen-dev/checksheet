<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ProductRequest extends FormRequest
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
    public function rules(Request $request)
    {
        // dd($this->product->id);
        switch ($this->method()) {
            case 'GET':
            case 'DELETE': {
                    return [];
                }
            case 'POST': {
                    return [
                        'product_name'  => ['required', 'string', 'unique:products'],
                        'attributes'    => 'nullable|array',
                        'price'         => 'nullable|numeric',
                        'price_with_vat'=> 'nullable|numeric',
                        'image'         => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
                    ];
                }
            case 'PUT':
            case 'PATCH': {
                    return [
                        'product_name'  => ['required', 'string', 'unique:products,id,'.$this->product->id],
                        'attributes'    => 'nullable|array',
                        'price'         => 'nullable|numeric',
                        'price_with_vat'=> 'nullable|numeric',
                        'image'         => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
                    ];
                }
            default:
                break;
        }
    }
}
