<?php

namespace App\Http\Requests;

use App\Enums\AdditionalTaskStatus;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AdditionalTaskRequest extends FormRequest
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
                        'title'         => ['required', 'string'],
                        'description'   => ['nullable', 'string'],
                        'status'        => ['nullable', 'string',
                            Rule::in(AdditionalTaskStatus::toArray()),
                        ],
                        'dueDate'       => ['nullable', 'date'],
                        'userId'        => ['nullable', 'integer', 'exists:users,id'],
                        
                    ];
                }
            case 'PUT':
            case 'PATCH': {
                    return [
                        'title'         => ['required', 'string'],
                        'description'   => ['nullable', 'string'],
                        'status'        => ['nullable', 'string',
                            Rule::in(AdditionalTaskStatus::toArray()),
                        ],
                        'dueDate'       => ['nullable', 'date'],
                        'userId'        => ['nullable', 'integer', 'exists:users,id'],
                    ];
                }
            default:
                break;
        }
    }
}
