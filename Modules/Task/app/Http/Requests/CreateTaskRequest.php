<?php
namespace Modules\Task\App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class CreateTaskRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,done',
            'assigned_to' => 'required|exists:users,user_id',
        ];
    }
}