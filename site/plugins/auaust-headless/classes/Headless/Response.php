<?php

namespace AUAUST\Headless;

use Kirby\Cms\Response as KirbyResponse;

class Response
{
  /**
   * Wrapper for \Kirby\Cms\Response::json() with status code 404.
   * Automatically formats the response as the API's response format.
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
   * Automatically formats the response as the API's response format.
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
