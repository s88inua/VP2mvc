<?php

namespace MVC\MVC\Model;


use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $guarded = ['role'];

    public function getFIOAttribute()
    {
        return $this->lastname . ' ' . $this->firstname . ' ' . $this->midname;
    }

    public function getAgeAttribute()
    {
        $dt = new \DateTime($this->birthdate);
        $now = new \DateTime();
        $diff = $now->diff($dt);
        return $diff->y;
    }

    public function getStatusAttribute()
    {
        $dt = new \DateTime($this->birthdate);
        $now = new \DateTime();
        $diff = $now->diff($dt);
        return ($diff->y >= 18) ? 'Совершеннолетний' : 'Несовершеннолетний';
    }

    public function getRoleNameAttribute()
    {
        return ($this->role === 1) ? 'Администратор' : 'Юзер';
    }
}