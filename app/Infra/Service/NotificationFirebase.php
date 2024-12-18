<?php

namespace App\Infra\Service;

use Illuminate\Support\Facades\Http;

use App\Domain\Service\FirebaseNotification\INotificationFirebase;

class NotificationFirebase implements INotificationFirebase
{

    public function notify($to, $title, $body, $type, $id, $route): bool
    {
        $token = env('TOKEN_FIREBASE');
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json', // Especifique o tipo de conteúdo como JSON
        ])->post('https://fcm.googleapis.com/fcm/send', [
            'to' => $to,
            'notification' => [
                'title' => $title,
                'body' => $body,
            ],
            'data' => [
                'type' => $type,
                'id' => $id,
                'route' => $route
            ]
        ]);
        return $response->ok();
    }
}
