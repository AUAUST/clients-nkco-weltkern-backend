<?php

use Kirby\Cms\App as Kirby;
use Kirby\Http\Remote;
use Kirby\Filesystem\Dir;
use Kirby\Filesystem\F;


Kirby::plugin('auaust/fetchFile', [
  'pageMethods' => [
    'fetchFile' => function (
      string $url,
      string $fileName = null,
      $template = null,
      bool $overwrite = true,
      string $type = null
    ) {

      $response = Remote::get($url);

      if ($response->code() !== 200) {
        // throw new Exception('Could not fetch image: Code ' . $response->code());
        return false;
      }


      $fileName ??= basename($url);
      // Trim extension, as it's determined by the source URL
      $fileName = pathinfo($fileName, PATHINFO_FILENAME);

      $fileExtension = pathinfo($url, PATHINFO_EXTENSION);

      $cacheRootPath = kirby()->root('cache');

      $temppath = sprintf(
        '%s/%s.%s',
        $cacheRootPath,
        bin2hex(random_bytes(5)),
        $fileExtension
      );

      if (!Dir::exists($cacheRootPath)) {
        Dir::make($cacheRootPath);
      }

      file_put_contents($temppath, $response->content());

      if ($file = $this->file($fileName)) {

        // TODO: Don't run the whole process if the file already exists since the beginning
        if ($overwrite === false) {
          return $file;
        }

        $file->replace($temppath);
      } else {
        $file = $this->createFile([
          'parent' => $this,
          'source' => $temppath,
          'filename' => $fileName . '.' . $fileExtension,
          'template' => $template
        ]);
      }

      if ($type && $file->type() !== $type) {
        return false;
      }

      F::remove($temppath);

      return $file;
    }
  ],
]);
