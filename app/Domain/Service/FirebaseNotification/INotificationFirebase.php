<?php
namespace App\Domain\Service\FirebaseNotification;
interface INotificationFirebase
{
    public function notify($to, $title, $body, $type, $id, $route);
}
