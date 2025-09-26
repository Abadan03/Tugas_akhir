<?php

use Symfony\Component\HttpFoundation\Response;

class UpgradeToHttpsUnderNgrok {

  public function handle(Request $request, Closure $next): Response
  {
    if (str_ends_with($request->getHost(), '.ngrok-free.app')) {
        URL::forceScheme('https');
    }

    return $next($request);
  }

}
