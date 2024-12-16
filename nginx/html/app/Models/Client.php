<?php
namespace App\Models\;

use App\Core\Model;

class Client extends Model {
    protected $table = 'clients';
    protected $primaryKey = 'id';
    protected $fillable = array (
  0 => 'id',
  1 => 'name',
  2 => 'email',
  3 => 'phone',
  4 => 'address',
  5 => 'city',
  6 => 'state',
  7 => 'created_at',
  8 => 'updated_at',
);
}