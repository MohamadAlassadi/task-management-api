<?php
namespace Modules\Task\App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest; 
class ChangeTaskStatusRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'status' => 'required|in:pending,in_progress,completed',
        ];
    }
}