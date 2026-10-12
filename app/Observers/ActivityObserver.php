<?php

namespace App\Observers;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityObserver
{
    public function created(Model $model): void
    {
        $this->log('created', $model);
    }

    public function updated(Model $model): void
    {
        $this->log('updated', $model);
    }

    public function deleted(Model $model): void
    {
        $this->log('deleted', $model);
    }

    protected function log(string $action, Model $model): void
    {
        $meta = ActivityLog::SUBJECTS[get_class($model)] ?? null;

        if (! $meta) {
            return;
        }

        [$label, $titleColumn] = $meta;

        // Pencatatan tidak boleh sampai menggagalkan aksi utama (simpan/hapus data).
        try {
            ActivityLog::create([
                'user_id'       => Auth::id(),
                'user_name'     => Auth::user()?->name ?? 'Sistem',
                'action'        => $action,
                'subject_label' => $label,
                'subject_title' => (string) ($model->{$titleColumn} ?? '-'),
            ]);
        } catch (\Throwable $e) {
            report($e);
        }
    }
}