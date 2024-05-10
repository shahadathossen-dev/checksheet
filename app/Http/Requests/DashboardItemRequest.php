<?php

namespace App\Http\Requests;

use App\Enums\CheckSheetType;
use App\Models\TaskItem;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class DashboardItemRequest extends FormRequest
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
                        'title' => ['nullable', 'string'],
                        'note'  => ['required_if:required,1', 'nullable', 'string'],
                        'done'  => ['nullable', 'boolean'],
                        'required'  => ['nullable', 'boolean'],
                    ];
                }
            case 'PUT':
            case 'PATCH': {
                    return [
                        'title' => ['nullable', 'string'],
                        'note'  => ['requried_if:required,1', 'nullable', 'string'],
                        'done'  => ['nullable', 'boolean'],
                        'required'  => ['nullable', 'boolean'],
                    ];
                }
            default:
                break;
        }
    }
}
