<?php

namespace Modules\User\App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Team\App\Models\Team;
use Modules\Project\App\Models\Project;
use Modules\Task\App\Models\Task;


class User extends Authenticatable
{
    protected $primaryKey = 'user_id';
    protected $fillable = ['name', 'email', 'password'];
    protected $hidden = ['password', 'remember_token'];

    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable,HasApiTokens;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function team_users()
    {
        return $this->hasMany(\Modules\Team\App\TeamUser::class, 'user_id', 'user_id');
    }
    public function create_task()
    {
        return $this->hasMany(\Modules\Task\App\Task::class, 'user_id', 'created_by');

    }
        public function assigned_task()
    {
        return $this->hasMany(\Modules\User\App\Models\User::class, 'assigned_to', 'user_id');
    }
    public function created_project()
    {
        return $this->hasMany(\Modules\Project\App\Models\Project::class, 'created_by', 'user_id'); 
    }
    public function team_owner()
    {
        return $this->hasMany(\Modules\Team\App\Models\Team::class, 'owner_id', 'user_id');  
    }
}
