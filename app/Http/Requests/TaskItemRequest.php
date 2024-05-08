<?php

namespace App\Http\Requests;

use App\Enums\CheckSheetType;
use App\Models\TaskItem;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class TaskItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return TaskItem::whereHas('tasklist', fn($q) => $q->where('user_id', auth()->id()))->exists();
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
                        'note'  => ['requried_if:required,1', 'string'],
                        'done'  => ['nullable', 'boolean'],
                    ];
                }
            case 'PUT':
            case 'PATCH': {
                    return [
                        'note'  => ['requried_if:required,1', 'string'],
                        'done'  => ['nullable', 'boolean'],
                    ];
                }
            default:
                break;
        }
    }
}
