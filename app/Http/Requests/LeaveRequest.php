<?php

namespace App\Http\Requests;

use App\Enums\LeaveType;
use App\Enums\LeaveStatus;
use App\Rules\CheckOverlap;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class LeaveRequest extends FormRequest
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
                        'type'      => ['required', 'string',
                            Rule::in(LeaveType::toArray()),
                        ],
                        'start_date'  => ['nullable', 'date', CheckOverlap::class],
                        'end_date'  => ['nullable', 'date'],
                        'user_id'  => ['nullable', 'integer', 'exists:users,id'],
                        'checked_by'  => ['nullable', 'integer', 'exists:users,id'],
                        'status'      => ['nullable', 'string',
                            Rule::in(LeaveStatus::toArray()),
                        ],
                    ];
                }
            case 'PUT':
            case 'PATCH': {
                    return [
                        'title'      => ['required', 'multi_space',  'string', 'max:100'],
                        'description'      => ['nullable', 'multi_space',  'string', 'max:100'],
                        'type'      => ['required', 'string',
                            Rule::in(LeaveType::toArray()),
                        ],
                        'start_date'  => ['nullable', 'date', CheckOverlap::class],
                        'end_date'  => ['nullable', 'date'],
                        'user_id'  => ['nullable', 'integer', 'exists:users,id'],
                        'checked_by'  => ['nullable', 'integer', 'exists:users,id'],
                        'status'      => ['nullable', 'string',
                            Rule::in(LeaveStatus::toArray()),
                        ],
                    ];
                }
            default:
                break;
        }
    }
}
