<?php

namespace DOOD\Tonic\Core\Exceptions;

/**
 * The TonicError class is the base exception class for all Tonic exceptions.
 *
 * @since 1.0.0
 * @author Alexandros Raikos <alexandros@dood.gr>
 */
class TonicError extends \Exception
{
    /**
     * The exception constructor.
     */
    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
