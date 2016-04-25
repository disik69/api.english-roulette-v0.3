<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Kodeine\Acl\Traits\HasRole;

/**
 * Class User
 * @package App
 *
 * @property $name
 * @property $email
 * @property $reading_count
 * @property $memory_count
 * @property $repeat_term
 * @property $lesson_size
 */
class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, CanResetPassword, HasRole;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'reading_count', 'memory_count', 'repeat_term', 'lesson_size'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function exercises()
    {
        return $this->hasMany(Exercise::class);
    }

    public function view()
    {
        $view['name'] = $this->name;
        $view['email'] = $this->email;
        $view['lesson_size'] = (int) $this->lesson_size;
        $view['reading_count'] = (int) $this->reading_count;
        $view['memory_count'] = (int) $this->memory_count;
        $view['repeat_term'] = (int) $this->repeat_term;

        return $view;
    }
}
