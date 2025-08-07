<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Medicine;
class LowStockAlert extends Notification
{
    use Queueable;
   //  use Queueable;

    protected $medicine;

    /**
     * Create a new notification instance.
     */
    public function __construct(Medicine $medicine)
    {
        $this->medicine = $medicine;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail2($notifiable)
    {
        return (new MailMessage)
                    ->subject('Alerta de Estoque Baixo')
                    ->line("O medicamento {$this->medicine->name} está com estoque abaixo do mínimo.")
                    ->line("Estoque atual: {$this->medicine->stock}")
                    ->line("Estoque mínimo: {$this->medicine->minimum_stock}")
                    ->action('Ver Medicamento', url('/medicines/' . $this->medicine->id));
    }

    public function toArray($notifiable): array
    {
        return [
            'medicine_id' => $this->medicine->id,
            'medicine_name' => $this->medicine->name,
            'current_stock' => $this->medicine->stock,
            'minimum_stock' => $this->medicine->minimum_stock,
            'message' => "O medicamento {$this->medicine->name} está com estoque abaixo do mínimo."
        ];
    }

    public function toMail($notifiable)
{
    $level = $this->medicine->stock_level;
    $subject = match($level) {
        'out_of_stock' => '🚨 ESTOQUE ESGOTADO: ' . $this->medicine->name,
        'critical' => '⚠️ ALERTA CRÍTICO: ' . $this->medicine->name,
        'high_alert' => '⚠️ Alerta de Estoque: ' . $this->medicine->name,
        default => 'Alerta de Estoque: ' . $this->medicine->name,
    };

    $message = match($level) {
        'out_of_stock' => "O medicamento {$this->medicine->name} está completamente esgotado!",
        'critical' => "O medicamento {$this->medicine->name} está com estoque crítico!",
        'high_alert' => "O medicamento {$this->medicine->name} está com estoque baixo.",
        default => "O medicamento {$this->medicine->name} está abaixo do estoque mínimo.",
    };

    return (new MailMessage)
                ->subject($subject)
                ->line($message)
                ->line("Estoque atual: {$this->medicine->stock}")
                ->line("Estoque mínimo: {$this->medicine->minimum_stock}")
                ->action('Ver Medicamento', url('/medicines/' . $this->medicine->id));
}


}
