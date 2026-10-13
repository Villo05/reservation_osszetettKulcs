<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Flight extends Model
{
    protected $fillable = ['date', 'airline_id', 'limit'];
    public $timestamps = false;
}
