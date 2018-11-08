<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Swagger\Annotations as SWG;


/**
 * Class User
 *
 * @package Petstore30
 *
 * @author  Donii Sergii <doniysa@gmail.com>
 *
 * @OA\Schema(
 *     title="User model",
 *     description="User model",
 * )
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname','lastname','mobile', 'email', 'password'
    ];


   /**
     * @OA\Property(
     *     description="firstname",
     *     title="firstname",
     * )
     *
     * @var string
     */
    public $firstname;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];
}


