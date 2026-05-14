<?php
namespace Modules\Project\App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Team\App\Models\Team;
use Modules\User\App\Models\User;
use Modules\Task\App\Models\Task;
class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'projects';
    protected $primaryKey = 'project_id';
    protected $fillable = [
        'title',
        'description',
        'team_id',
        'created_by',
        'start_date',   
        'end_date',
        'status',
        'created_at',
        'updated_at',
    ];      
    public function team()
    {
        return $this->belongsTo(\Modules\Team\App\Models\Team::class, 'team_id', 'team_id');
    }
    public function createduser()
    {
        return $this->belongsTo(\Modules\User\App\Models\User::class,'created_by','user_id');
    }

    public function tasks()
    {
        return $this->hasMany(\Modules\Task\App\Models\Task::class, 'project_id', 'project_id');
    }
}