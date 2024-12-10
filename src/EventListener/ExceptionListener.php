<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        // Obtienes la excepción
        $exception = $event->getThrowable();

        // Verificas si la excepción es del tipo MethodNotAllowedHttpException
        if ($exception instanceof MethodNotAllowedHttpException) {
            // Creas una respuesta JSON personalizada
            $response = new JsonResponse([
                'code' => 405,
                'message' => 'Método no permitido',
            ]);

            // Enviar la respuesta al evento
            $event->setResponse($response);
        }
    }
}
?>