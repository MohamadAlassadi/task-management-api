<?php

namespace App\Http\Controllers;

use App\Support\Api\ApiResponse;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Modules\Auth\Models\User;

class ApiController extends BaseController
{
    use ApiResponse;
    use DispatchesJobs;
    use ValidatesRequests;

    public static array $orderBy = ['id' => 'desc'];

    public static ?string $model = null;

    protected ?int $perPage = 10;

    protected ?User $user;

    protected bool $pagination = true;
}
