<?php

namespace App\Models;

use App\Core\Model;

class Role extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'name',
        'permissions'
    ];

    public function getAll()
    {
        return $this->query("SELECT * FROM roles ORDER BY name");
    }

    public function getById($id)
    {
        return $this->find($id);
    }

    public function getPermissions($id)
    {
        $role = $this->find($id);
        return $role ? json_decode($role['permissions'], true) : [];
    }

    public function hasPermission($roleId, $permission)
    {
        $permissions = $this->getPermissions($roleId);
        return in_array($permission, $permissions);
    }

    public function setPermissions($id, $permissions)
    {
        return $this->update($id, [
            'permissions' => json_encode($permissions)
        ]);
    }
}
