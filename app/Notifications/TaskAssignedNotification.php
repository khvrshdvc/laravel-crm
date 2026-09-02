<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TaskAssignedNotification extends Notification
{
    use Queueable;

    public function __construct(public Task $task)
    {
        //
    }

    public function via(object $notifiable): array
    {
        // DB ga saqlash uchun 'database' kanalini ko'rsatamiz
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        // Notification dropdown va sahifada ko'rinadigan ma'lumotlar
        return [
            'task_id' => $this->task->id,
            'title'   => 'Yangi vazifa!',
            'message' => "Sizga yangi vazifa biriktirildi: {$this->task->title}",
            'url'     => route('tasks.show', $this->task->id),
        ];
    }
}
