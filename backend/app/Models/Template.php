<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;

    protected $table = 'templates';

    public function fields()
    {
        $fields = Field::where("template_id", $this->id)->orderBy("order", "ASC")->get();

        return $fields;
    }
}
