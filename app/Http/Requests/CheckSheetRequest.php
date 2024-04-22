<?php

namespace App\Http\Requests;

use App\Enums\CheckSheetStatus;
use App\Enums\CheckSheetType;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class CheckSheetRequest extends FormRequest
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
        switch ($this->method()) {
            case 'GET':
            case 'DELETE': {
                    return [];
                }
            case 'POST': {
                    return [
                        'title'      => ['required', 'multi_space',  'string', 'max:100'],
                        'description'      => ['required', 'multi_space',  'string', 'max:100'],
                        'type'      => ['nullable', new Enum(CheckSheetType::class)],
                        'due_by'  => ['nullable', 'integer'],
                        'user_id'  => ['nullable', 'integer', 'exists:users,id']
                    ];
                }
            case 'PUT':
            case 'PATCH': {
                    return [
                        'title'      => ['required', 'multi_space',  'string', 'max:100'],
                        'description'      => ['required', 'multi_space',  'string', 'max:100'],
                        'type'      => ['nullable', new Enum(CheckSheetType::class)],
                        'due_by'  => ['nullable', 'integer'],
                        'user_id'  => ['nullable', 'integer', 'exists:users,id']
                    ];
                }
            default:
                break;
        }
    }
}
