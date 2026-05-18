<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SistemNotifikasi extends Notification
{
    use Queueable;

    public $title;
    public $message;
    public $link;

    // Konstruktor untuk menerima data dinamis
    public function __construct($title, $message, $link)
    {
        $this->title = $title;
        $this->message = $message;
        $this->link = $link;
    }

    // Kita hanya menggunakan channel database
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    // Struktur data yang akan disimpan ke kolom 'data' di database (JSON)
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'link' => $this->link,
        ];
    }
}