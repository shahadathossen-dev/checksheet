<?php

namespace App\Http\Requests;

use App\Enums\CheckSheetType;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class TaskListSubmitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Rule::exists('check_sheets', 'id')->where('user_id', auth()->id);
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
                        'checksheet_id'  => ['nullable', 'integer', 'exists:check_sheets,id,user_id,'.$this->input('user_id')],
                        'type'      => ['required',
                            Rule::in(CheckSheetType::toArray()),
                            Rule::unique('task_lists', 'type')->where('user_id', $this->input('user_id'))->where('due_date', $this->input('due_date'))
                        ],
                        'due_date'  => ['nullable', 'date'],
                        'user_id'  => ['nullable', 'integer', 'exists:users,id'],
                        'items'    => 'nullable|array',
                        'items.*.checksheet_item_id'    => 'required|integer|exists:checksheet_items',
                        'items.*.note'    => 'required|string',
                        'items.*.done'    => 'nullable|boolean',
                    ];
                }
            case 'PUT':
            case 'PATCH': {
                    return [
                        'checksheet_id'  => ['nullable', 'integer', 'exists:check_sheets,id,user_id,'.$this->input('user_id')],
                        'type'      => ['required',
                            Rule::in(CheckSheetType::toArray()),
                            Rule::unique('check_sheets', 'type')->where('user_id', $this->input('user_id'))->where('due_date', $this->input('due_date'))->ignore($this->tasklist->id)
                        ],
                        'due_date'  => ['nullable', 'date'],
                        'user_id'  => ['nullable', 'integer', 'exists:users,id'],
                        'items'    => 'nullable|array',
                        'items.*.checksheet_item_id'    => 'required|integer|exists:checksheet_items',
                        'items.*.note'    => 'required|string',
                        'items.*.done'    => 'nullable|boolean',
                    ];
                }
            default:
                break;
        }
    }
}
