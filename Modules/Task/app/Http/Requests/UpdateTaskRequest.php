<?php
namespace Modules\Task\App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class UpdateTaskRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,done',
            'assigned_to' => 'nullable|exists:users,user_id',
        ];
    }
}