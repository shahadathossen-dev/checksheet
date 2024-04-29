<?php

namespace App\Http\Requests;

use App\Enums\CheckSheetType;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
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
                        'description'      => ['nullable', 'multi_space',  'string', 'max:100'],
                        'type'      => ['required', Rule::in(CheckSheetType::toArray())],
                        'due_by'  => ['nullable', 'integer'],
                        'user_id'  => ['nullable', 'integer', 'exists:users,id'],
                        'check_sheet_items'    => 'nullable|array',
                        'check_sheet_items.*.title'    => 'required|string',
                        'check_sheet_items.*.required'    => 'nullable|boolean',
                    ];
                }
            case 'PUT':
            case 'PATCH': {
                    return [
                        'title'      => ['required', 'multi_space',  'string', 'max:100'],
                        'description'      => ['nullable', 'multi_space',  'string', 'max:100'],
                        'type'      => ['required', Rule::in(CheckSheetType::toArray())],
                        'due_by'  => ['nullable', 'integer'],
                        'user_id'  => ['nullable', 'integer', 'exists:users,id'],
                        'check_sheet_items'    => 'nullable|array',
                        'check_sheet_items.*.title'    => 'required|string',
                        'check_sheet_items.*.required'    => 'nullable|boolean',
                    ];
                }
            default:
                break;
        }
    }
}
