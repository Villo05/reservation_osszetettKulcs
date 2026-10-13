<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Travel extends Model
{
    protected $table = 'travel';
    protected $fillable = ['user_id', 'flight_id', 'evaluation'];
    protected $primaryKey = 'user_id';
    public $incrementing = false;
    public $timestamps = false;
    protected function setKeysForSaveQuery($query)
    {
        return $query->where('user_id', $this->getRawOriginal('user_id') ?? $this->user_id)
            ->where('flight_id', $this->getRawOriginal('flight_id') ?? $this->flight_id);
    }
    protected function setKeysForSelectQuery($query)
    {
        return $this->setKeysForSaveQuery($query);
    }
}
