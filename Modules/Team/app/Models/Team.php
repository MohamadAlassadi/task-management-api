<?php

namespace Modules\Team\App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Team\App\Models\TeamUser;
use Modules\User\App\Models\User;
class Team extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'teams';
    protected $primaryKey = 'team_id';
    protected $fillable = [
        'name',
        'description',
        'owner_id',
    ];
    public function team_users()
    {
        return $this->hasMany(TeamUser::class, 'team_id', 'team_id');
    }
     public function team_owner()
    {
        return $this->belongTo(User::class, 'user_id', 'owner_id');  
    }
    public function projects()
    {
        return $this->hasMany(Project::class, 'team_id', 'team_id');
    }
}