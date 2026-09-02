<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskAssigned extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Task $task
    ) {}

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'task_id' => $this->task->id,
            'title' => $this->task->title,
            'message' => "Sizga yangi vazifa berildi: {$this->task->title}",
            'url' => route('tasks.show', $this->task->id),
        ];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Yangi vazifa')
            ->line("Sizga yangi vazifa berildi: {$this->task->title}")
            ->action('Vazifani ko\'rish', route('tasks.show', $this->task))
            ->line('E\'tibor bergangiz uchun rahmat!');
    }
}
