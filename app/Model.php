<?php

namespace App;

class Model extends \Illuminate\Database\Eloquent\Model
{
    public function getId()
    {
        return (int) $this->getKey();
    }
}