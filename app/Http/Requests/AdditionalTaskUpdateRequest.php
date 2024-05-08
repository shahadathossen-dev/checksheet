<?php

namespace App\Http\Requests;

use App\Enums\AdditionalTaskStatus;
use App\Models\AdditionalTask;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AdditionalTaskUpdateRequest extends FormRequest
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
                        'note'  => ['requried_if:note_required,1', 'string'],
                        'status'  => ['nullable', Rule::in(AdditionalTaskStatus::toArray())],
                    ];
                }
            case 'PUT':
            case 'PATCH': {
                    return [
                        'note'  => ['requried_if:note_required,1', 'string'],
                        'status'  => ['nullable', Rule::in(AdditionalTaskStatus::toArray())],
                    ];
                }
            default:
                break;
        }
    }
}
