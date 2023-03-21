<?php

use Kirby\Cms\App as Kirby;

require dirname(__DIR__)  . "/vendor/autoload.php";

echo (new Kirby([
  "roots" => [
    "index"       => __DIR__,

    "media"       => __DIR__ . "/media",
    "assets"      => __DIR__ . "/assets",

    "base"        => $base     = dirname(__DIR__),
    "content"     =>             $base . "/content",

    "site"        => $site     = $base . "/site",
    "snippets"    =>             $site . "/snippets",
    "templates"   =>             $site . "/templates",
    "controllers" =>             $site . "/controllers",
    "collections" =>             $site . "/collections",
    "models"      =>             $site . "/models",
    "plugins"     =>             $site . "/plugins",

    "storage"     => $storage  = $base . "/storage",
    "cache"       =>             $storage . "/cache",
    "sessions"    =>             $storage . "/sessions",

    "system"      => $system   = $base . "/system",
    "commands"    =>             $system . "/commands",
    "logs"        =>             $system . "/logs",

    "settings"    => $settings = $base . "/settings",
    "blueprints"  =>             $settings . "/blueprints",
    "config"      => $config   = $settings . "/config",
    "license"     =>             $config . "/.license",
    "users"       => $users    = $settings . "/users",
    "accounts"    =>             $users . "/accounts",
    "roles"       =>             $users . "/roles"
  ]
]))->render();
