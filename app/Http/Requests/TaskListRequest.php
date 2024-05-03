<?php

namespace App\Http\Requests;

use App\Enums\CheckSheetType;
use App\Enums\TaskListStatus;
use App\Models\CheckSheet;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class TaskListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $authUser = User::findOrFail(auth()->id());
        return $authUser->isSuperAdmin() ||
        ($authUser->can('create-task-lists') && $authUser->can('update-task-lists')) ||
        CheckSheet::where('id', $this->input('checksheetId'))->where('user_id', $authUser->id)->exixts();
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
                        'checksheetId'  => ['nullable', 'integer', 'exists:check_sheets,id,user_id,'.$this->input('userId')],
                        'type'      => ['required', 'string',
                            Rule::in(CheckSheetType::toArray()),
                            Rule::unique('task_lists', 'type')->where('user_id', $this->input('userId'))->where('due_date', $this->input('dueDate'))
                        ],
                        'dueDate'  => ['nullable', 'date'],
                        'userId'  => ['nullable', 'integer', 'exists:users,id'],
                        'status'      => ['nullable', 'string',
                            Rule::in(TaskListStatus::toArray()),
                        ],
                        'items'    => 'nullable|array',
                        'items.*.checksheetItemId'    => 'required|integer|exists:checksheet_items,id',
                        'items.*.note'    => 'nullable|required_if:items.*.required,1|string',
                        'items.*.done'    => 'nullable|boolean',
                    ];
                }
            case 'PUT':
            case 'PATCH': {
                    return [
                        'checksheetId'  => ['nullable', 'integer', 'exists:check_sheets,id,user_id,'.$this->input('userId')],
                        'type'      => ['required', 'string',
                            Rule::in(CheckSheetType::toArray()),
                            Rule::unique('task_lists', 'type')->where('user_id', $this->input('userId'))->where('due_date', $this->input('dueDate'))->ignore($this->tasklist->id)
                        ],
                        'dueDate'  => ['nullable', 'date'],
                        'userId'  => ['nullable', 'integer', 'exists:users,id'],
                        'status'      => ['nullable', 'string',
                            Rule::in(TaskListStatus::toArray()),
                        ],
                        'items'    => 'nullable|array',
                        'items.*.checksheetItemId'    => 'required|integer|exists:checksheet_items,id',
                        'items.*.note'    => 'nullable|required_if:items.*.required,1|string',
                        'items.*.done'    => 'nullable|boolean',
                    ];
                }
            default:
                break;
        }
    }
}
