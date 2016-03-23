<?php namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
//
use Psr\Log\LoggerInterface;
use Illuminate\Mail\Mailer;
//
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Validation\ValidationException;
use Swift_Message as SwiftMessage;
use Exception;


class Handler extends ExceptionHandler {

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /** @var \Illuminate\Mail\Mailer */
    protected $mailer;

    /**
     * Handler constructor.
     * @param \Psr\Log\LoggerInterface $log
     * @param \Illuminate\Mail\Mailer $mailer
     */
    public function __construct(LoggerInterface $log, Mailer $mailer) {
        parent::__construct($log);
        $this->mailer = $mailer;
    }

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e) {

        if(
            $this->shouldReport($e)
            && app()->environment() == 'local'
        ) {

            $this->mailer->getSwiftMailer()->send(SwiftMessage::newInstance(null)
                ->addTo('log@localhost')
                ->addFrom('noreply@localhost', 'Laravel Drydock')
                ->setBody($this->render(null, $e)->getContent())
                ->setContentType('text/html')
            );

        }
        
        parent::report($e);

    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e) {
        return parent::render($request, $e);
    }
}
