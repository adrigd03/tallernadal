<?php

namespace App\Http\Controllers;

use App\Models\Usuari;
use Illuminate\Http\Request;

class UsuariController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function configuracio()
    {
        return view('configuracio');
    }

    public function assignar()
    {
        //Agafe tots els usuaris que no tinguin un punt abans de l'arroba en el seu email i que el seu email no sigui cicles@sapalomera.cat
        $usuaris = Usuari::where('email', 'NOT LIKE', '%.%@sapalomera.cat')->where('email', 'NOT LIKE', 'cicles@sapalomera.cat')->get();


        //Retorna la vista assignar amb la variable usuaris
        return view('assignar', compact('usuaris'));
    }

    public function canviarAdmin(Request $request)
    {
        //Agafa l'usuari que té l'id que li passem per paràmetre
        $usuari = Usuari::find($request->email);

        //Si l'usuari no existeix, retorna la vista assignar
        if (!$usuari) {
            return response()->json([
                'error' => 'Usuari no trobat'
            ]);
        }

        //Si l'usuari és admin, li canvia el rol a usuari i viceversa
        if ($usuari->admin == 1) {
            $usuari->admin = null;
        } else {
            $usuari->admin = 1;
        }

        //Guarda els canvis
        $usuari->save();

        //Retorna la vista assignar
        return response()->json([
            'success' => 'Rol canviat'
        ]);
    }

    public function importar(Request $request)
    {

        try {


            // Agafem el fitxer csv que conte els usuaris
            $file = $request->file('csv');

            // Comprovem que el fitxer no sigui null i que sigui un fitxer csv
            if ($file == null || $file->getClientOriginalExtension() != 'csv') {
                return redirect()->back()->with('error', 'El fitxer ha de ser un csv');
            }
            // Agafem el contingut del fitxer
            $csv = file_get_contents($file);

            // Separem el contingut del fitxer per files

            $csv = explode("\n", $csv);

            // Agafem la primera fila del fitxer, que conte els noms de les columnes

            $columnes = explode(",", $csv[0]);

            // Eliminem la primera fila del fitxer, que conte els noms de les columnes
            unset($csv[0]);

            // Recorrem totes les files del fitxer
            foreach ($csv as $fila) {
                // Separem el contingut de la fila per columnes
                $fila = explode(",", $fila);

                // Comprovem que el nombre de columnes de la fila sigui igual al nombre de columnes de la primera fila
                if (count($columnes) != count($fila)) {
                    return redirect()->back()->with('error', 'El fitxer no té el format correcte');
                }

                // Recorrem totes les columnes de la fila
                foreach ($fila as $key => $columna) {
                    // Agafem el nom de la columna
                    $nomColumna = $columnes[$key];
                    // Si la columna es el nom, cognoms, email, etapa, curs o grup la guardem a la variable $usuari
                        $usuari[$nomColumna] = $columna;
                }

                // Comprovem que no existeixi a la base de dades
                if (Usuari::where('email', $usuari['email'])->first()) {
                    unset($usuari);                
                    continue;
                }

                if ($usuari['email'] != 'cicles@sapalomera.cat' ) {
                    Usuari::create($usuari);
                }

                // Eliminem la variable $usuari per a que no es guardi l'usuari anterior
                unset($usuari);
            }

            // Redirigim a la vista assignar amb un missatge de confirmació
            return redirect()->back()->with('success', 'Usuaris importats correctament.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al importar els usuaris.' . $e->getMessage() . "\t" . $e->getLine());
        }
    }
}
