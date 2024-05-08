<?php

namespace App\Http\Requests;

use App\Enums\AdditionalTaskStatus;
use App\Models\AdditionalTask;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequestUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return AdditionalTask::where('user_id', auth()->id())->exists();
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
                        'title'  => ['required', 'string'],
                        'description'  => ['nullable', 'string'],
                        'status'  => ['nullable', Rule::in(AdditionalTaskStatus::toArray())],
                        'userId'        => ['nullable', 'integer', 'exists:users,id'],
                    ];
                }
            case 'PUT':
            case 'PATCH': {
                    return [
                        'title'  => ['required', 'string'],
                        'description'  => ['nullable', 'string'],
                        'note'  => ['nullable', 'string'],
                        'status'  => ['nullable', Rule::in(AdditionalTaskStatus::toArray())],
                    ];
                }
            default:
                break;
        }
    }
}
