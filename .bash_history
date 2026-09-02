./vendor/bin/pint
php artisan make:controller NotificationController
php artisan migrate:fresh --seed
php artisan route:clear
php artisan config:clear
php artisan cache:clear
php artisan tinker
>>> class_exists(\App\Http\Controllers\DealController::class)
ls -la app/Http/Controllers/DealController.php
head -20 app/Http/Controllers/DealController.php
clear
php artisan test --filter DealTest
clear
php artisan notifications:table
php artisan migrate:fresh --seed
php artisan make:notification TaskAssigned
<?php
namespace App\Notifications;
use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
class TaskAssigned extends Notification implements ShouldQueue
{     use Queueable;     public function __construct(
        public Task $task
    ) {}
    public function via($notifiable): array
    {         return ['database', 'mail'];     }
    public function toDatabase($notifiable): array
    {         return [;             'task_id' => $this->task->id,;             'title' => $this->task->title,;             'message' => "Sizga yangi vazifa berildi: {$this->task->title}",;         ];     }
    public function toMail($notifiable): MailMessage
    {         return (new MailMessage)
            ->subject('Yangi vazifa')
            ->line("Sizga yangi vazifa berildi: {$this->task->title}")
            ->action('Vazifani ko\'rish', route('tasks.show', $this->task))
    }
}<?php
namespace App\Notifications;
use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
class TaskAssigned extends Notification implements ShouldQueue
{     use Queueable;     public function __construct(
        public Task $task
    ) {}
    public function via($notifiable): array
    {         return ['database', 'mail'];     }
    public function toDatabase($notifiable): array
    {         return [;             'task_id' => $this->task->id,;             'title' => $this->task->title,;             'message' => "Sizga yangi vazifa berildi: {$this->task->title}",;         ];     }
    public function toMail($notifiable): MailMessage
    {         return (new MailMessage)
            ->subject('Yangi vazifa')
            ->line("Sizga yangi vazifa berildi: {$this->task->title}")
            ->action('Vazifani ko\'rish', route('tasks.show', $this->task)
clear
php artisan tinker
php artisan make:migration add_stage_to_deals_table --table=deals
php artisan migrate:fresh --seed
php artisan tinker
php artisan make:notification TaskAssignedNotification
php artisan tinker
