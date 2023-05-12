<?php

namespace App\Http\Middleware;

use App\Models\Taller;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectInvalidPage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
          // Agafem el número de pàgina de la URL
          $page = $request->query('page', 1);

          // Calculem el número d'elements per pàgina i el total de tallers
          $itemsPerPage = 5;
          $totalTallers = Taller::count();
          $lastPage = ceil($totalTallers / $itemsPerPage);
  
          // Si la pàgina és més gran que el número total de pàgines o és menor que 1, redirigim a la pàgina 1
          if ($page > $lastPage || $page < 1) {
              return redirect($request->fullUrlWithQuery(['page' => 1]));
          }
  
          // Si tot va bé, continuem
          return $next($request);
    }
}
