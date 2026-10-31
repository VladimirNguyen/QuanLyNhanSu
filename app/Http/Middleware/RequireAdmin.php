<?php
namespace App\Http\Middleware;
use Closure;
class RequireAdmin {
 public function handle($request, Closure $next) {
  abort_unless($request->user() && (int)$request->user()->status === 1 && (int)$request->user()->level === 1, 403);
  return $next($request);
 }
}
