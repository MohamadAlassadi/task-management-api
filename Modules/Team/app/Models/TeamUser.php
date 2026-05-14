<?php

namespace Modules\Team\App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Team\App\Models\Team;
use Modules\User\App\Models\User;
class TeamUser extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'team_users';
    protected $primaryKey = 'team_userID';
    protected $fillable = [
        'team_id',
        'user_id',
        'role',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id', 'team_id');
    }

}