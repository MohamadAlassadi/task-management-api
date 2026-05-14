<?php
namespace Modules\Auth\App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Password_reset extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'email';

        protected $fillable = [
        'email',
        'token',
        'created_at',
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public $timestamps = false;


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
}
