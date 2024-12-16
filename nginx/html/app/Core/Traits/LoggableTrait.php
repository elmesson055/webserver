<?php
namespace App\Core\Traits;

trait LoggableTrait {
    protected function logAction($action, $details = []) {
        $user = auth()->user();
        $modelName = get_class($this);
        $primaryKey = $this->{$this->primaryKey};
        
        $log = new \App\Core\Models\SystemLog();
        $log->fill([
            'user_id' => $user->id,
            'action' => $action,
            'model' => $modelName,
            'model_id' => $primaryKey,
            'details' => json_encode($details),
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null
        ]);
        
        return $log->save();
    }
    
    public function logCreated() {
        return $this->logAction('create', [
            'data' => $this->data,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }
    
    public function logUpdated($changedAttributes = []) {
        return $this->logAction('update', [
            'changed' => $changedAttributes,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }
    
    public function logDeleted() {
        return $this->logAction('delete', [
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }
}
