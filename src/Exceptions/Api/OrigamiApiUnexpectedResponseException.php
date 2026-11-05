<?php

namespace OrigamiMp\OrigamiApiSdk\Exceptions\Api;

/**
 * Parent of the exceptions thrown when the API answered, but not in a usable form (5xx, a proxy's page, an unreadable
 * body, a response without data), to catch them all at once.
 */
abstract class OrigamiApiUnexpectedResponseException extends OrigamiApiException {}
