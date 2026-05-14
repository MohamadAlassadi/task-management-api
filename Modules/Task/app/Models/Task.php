<?php
namespace Modules\Task\App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Project\App\Models\Project;
use Modules\User\App\Models\User;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tasks';
    protected $primaryKey = 'task_id';
    protected $fillable = [
        'title',
        'description',
        'status',
        'project_id',
        'assigned_to',
        'created_by',
    ];      
    public function project()
    {
        return $this->belongsTo(\Modules\Project\App\Models\Project::class, 'project_id', 'project_id');
    }
    public function assignedUser()
    {
        return $this->belongsTo(\Modules\User\App\Models\User::class, 'assigned_to', 'user_id');
    }
    public function createduser()
    {
        return $this->belongsTo(\Modules\User\App\Models\User::class, 'created_by', 'user_id');
    }

}
