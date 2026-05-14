<?php 
namespace Modules\Project\App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
{
   public function authorize(): bool
    {

        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'team_id' => 'nullable|exists:teams,team_id',
            'created_by' => 'nullable|exists:users,user_id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'nullable|in:not_started,in_progress,completed',
        ];
    }
}