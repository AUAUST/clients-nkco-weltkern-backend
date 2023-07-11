<?php

namespace AUAUST\Headless;

use Kirby\Cms\Response as KirbyResponse;

class Response
{

  /**
   * Wrapper for \Kirby\Cms\Response::json() with status code 400.
   * Used when the request is invalid, e.g. missing or wrong parameters.
   *
   * @param string $message
   * @param array|null $data
   * @return Kirby\Cms\Response
   */
  public static function invalidRequest(string $message, array $data = null)
  {
    $content = [
      'status' => 'error',
      'message' => $message ?? 'Invalid request',
    ];

    if ($data) {
      $content['data'] = $data;
    }

    return KirbyResponse::json(
      $content,
      400
    );
  }

  /**
   * Wrapper for \Kirby\Cms\Response::json() with status code 404.
   * Used when the requested resource does not exist or is not found.
   *
   * @param string $message
   * @param array|null $data
   * @return Kirby\Cms\Response
   */
  public static function notFound(string $message, array $data = null)
  {
    $content = [
      'status' => 'error',
      'message' => $message ?? 'Not found',
    ];

    if ($data) {
      $content['data'] = $data;
    }

    return KirbyResponse::json(
      $content,
      404
    );
  }

  /**
   * Wrapper for \Kirby\Cms\Response::json() with status code 200.
   * Used when the request was successful and the requested resource is returned.
   *
   * @param array $data
   * @param string|null $message
   * @return Kirby\Cms\Response
   */
  public static function success(string $message = null, array $data)
  {
    $content = [
      'status' => 'ok',
      'data' => $data,
    ];

    if ($message) {
      $content['message'] = $message;
    }

    return KirbyResponse::json(
      $content,
      200
    );
  }
}
