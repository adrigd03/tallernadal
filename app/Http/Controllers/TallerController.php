<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Taller;
use Illuminate\Support\Facades\Auth;
use App\Rules\AdrecatValidation;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Gate;
use App\Rules\UserAjudant;
use App\Models\Usuari;

class TallerController extends Controller

{

    public function index()
    {
        $tallers = Taller::select('nom', 'descripcio', 'creador', 'adrecat', 'nalumnes', 'materials', 'espai', 'codi', 'participants')
            ->orderBy('codi', 'desc')
            ->when(Gate::allows('isAdmin'), function ($query) {
                return $query->addSelect('created_at');
            })
            ->paginate(5);

        $nextId = Taller::max('codi') + 1;

        return view('home', compact('tallers', 'nextId'));
    }

    public function creartaller(Request $request)
    {
        try {

            // Comprobem que el usuari no sigui admin o que no hagi creat 1 taller

            //Validem les dades rebudes del formulari i si el usuari es admin comprovem el camp ajudants
            $dataVerificada = $request->validate([
                'nomTaller' => 'required|max:30',
                'descripcio' => 'required|max:255',
                'adrecat' => ['required', new AdrecatValidation],
                'materials' => 'required|max:255',
                'ajudants' => Gate::allows('isAdmin') ? new UserAjudant : '',
                'espai' => Gate::allows('isAdmin') ? 'max:30' : '',
                'nalumnes' =>  Gate::allows('isAdmin') ? 'required|integer' : 'required|integer|min:2|max:20',
            ], [
                'nomTaller.required' => 'El nom del taller és obligatori.',
                'nomTaller.max' => 'El nom del taller no pot tenir més de 30 caràcters.',
                'descripcio.required' => 'La descripció del taller és obligatòria.',
                'descripcio.max' => 'La descripció del taller no pot tenir més de 255 caràcters.',
                'materials.required' => 'Els materials del taller són obligatoris.',
                'materials.max' => 'Els materials del taller no poden tenir més de 255 caràcters.',
                'adrecat.required' => 'Cal adreçar el taller a un curs.',
                'espai.max' => 'L\'espai del taller no pot tenir més de 30 caràcters.',
                'nalumnes.required' => 'El nombre d\'alumnes és obligatori.',
                'nalumnes.integer' => 'El nombre d\'alumnes ha de ser un número.',
                'nalumnes.min' => 'El nombre d\'alumnes ha de ser com a mínim 2.',
                'nalumnes.max' => 'El nombre d\'alumnes ha de ser com a màxim 20, si es vol afegir més s\'haurà d\'informar a un professor.',

            ]);

            if (Gate::allows('isParticipantofAnyTaller')) {
                return redirect()->back()->with('error', 'No pots crear un taller si estàs apuntat a un altre.');
            }
            if (Gate::allows('isCreator', Auth::user()) && Gate::denies('isAdmin')) {
                return redirect()->back()->with('error', 'No pots crear més d\'un taller.');
            }

            if(Gate::allows('isAjudant')){
                return redirect()->back()->with('error', 'No pots crear un taller si ja ets ajudant.');
            }

            //Creem el taller
            $taller = Taller::create([
                'nom' => $dataVerificada['nomTaller'],
                'creador' => Auth::user()->email,
                'descripcio' => $dataVerificada['descripcio'],
                'creador' => Auth::user()->email,
                'adrecat' => implode(', ', $dataVerificada['adrecat']),
                'materials' => $dataVerificada['materials'],
                'ajudants' => Gate::allows('isAdmin') ? $dataVerificada['ajudants'] : '',
                'espai' => Gate::allows('isAdmin') ? $dataVerificada['espai'] : '',
                'nalumnes' => $dataVerificada['nalumnes'],

            ]);

            //Retornem la resposta success si tot ha anat bé
            return redirect()->back()->with('success', 'Taller creat correctament.');
        } catch (ValidationException $e) {
            //Retornem la resposta error si ha ocorregut algun error de validació
            return redirect()->back()->withErrors($e->validator->getMessageBag(), 'crearTallerForm')->withInput();
        } catch (\Exception $e) {
            //Retornem la resposta error si ha ocorregut algun error
            return redirect()->back()->with('error', 'Error al crear el taller.')->withInput();
        }
    }

    public function esborrar($codi)
    {
        try {

            $taller = Taller::find($codi);

            //Comprovem si el taller existeix
            if (!$taller) {
                return redirect()->back()->with('error', 'No s\'ha trobat el taller.');
            }

            //Comprovem si l'usuari té permisos per esborrar el taller
            if (Gate::denies('isCreatorOrAdmin', $taller)) {
                return redirect()->back()->with('error', 'No tens permisos per esborrar aquest taller.');
            }

            //Esborrem el taller
            $taller->delete();

            //Retornem la resposta success si tot ha anat bé
            return redirect()->back()->with('success', 'Taller esborrat correctament.');
        } catch (\Exception $e) {
            //Retornem la resposta error si ha ocorregut algun error
            return redirect()->back()->with('error', 'Error al esborrar el taller.');
        }
    }

    public function editarTaller(Request $request, $codi)
    {
        try {
            //Validem les dades rebudes del formulari
            $dataVerificada = $request->validate([
                'nomTaller' => 'required|max:30',
                'descripcio' => 'required|max:255',
                'adrecat' => ['required', new AdrecatValidation],
                'materials' => 'required|max:255',
                'ajudants' => Gate::allows('isAdmin') ? new UserAjudant : '',
                'espai' => Gate::allows('isAdmin') ? 'max:30' : '',
                'nalumnes' =>  Gate::allows('isAdmin') ? 'required|integer' : 'required|integer|min:2|max:20',
            ], [
                'nomTaller.required' => 'El nom del taller és obligatori.',
                'nomTaller.max' => 'El nom del taller no pot tenir més de 30 caràcters.',
                'descripcio.required' => 'La descripció del taller és obligatòria.',
                'descripcio.max' => 'La descripció del taller no pot tenir més de 255 caràcters.',
                'materials.required' => 'Els materials del taller són obligatoris.',
                'materials.max' => 'Els materials del taller no poden tenir més de 255 caràcters.',
                'adrecat.required' => 'Cal adreçar el taller a un curs.',
                'espai.max' => 'L\'espai del taller no pot tenir més de 30 caràcters.',
                'nalumnes.required' => 'El nombre d\'alumnes és obligatori.',
                'nalumnes.integer' => 'El nombre d\'alumnes ha de ser un número.',
                'nalumnes.min' => 'El nombre d\'alumnes ha de ser com a mínim 2.',
                'nalumnes.max' => 'El nombre d\'alumnes ha de ser com a màxim 20, si es vol afegir més s\'haurà d\'informar a un professor.',

            ]);

            $codi = $request->codi;

            $taller = Taller::find($codi);

            //Comprovem si el taller existeix
            if (!$taller) {
                return redirect()->back()->with('error', 'No s\'ha trobat el taller.');
            }

            //Comprovem si l'usuari té permisos per editar el taller
            if (Gate::denies('isCreatorOrAdmin', $taller)) {
                return redirect()->back()->with('error', 'No tens permisos per editar aquest taller.');
            }

            //Actualitzem les dades del taller
            $taller->nom = $dataVerificada['nomTaller'];
            $taller->descripcio = $dataVerificada['descripcio'];
            $taller->adrecat = implode(', ', $dataVerificada['adrecat']);
            $taller->materials = $dataVerificada['materials'];
            $taller->nalumnes = $dataVerificada['nalumnes'];
            if (Gate::allows('isAdmin')) $taller->ajudants = $dataVerificada['ajudants'];
            if (Gate::allows('isAdmin')) $taller->espai = $dataVerificada['espai'];

            $taller->save();

            //Retornem la resposta success si tot ha anat bé
            return redirect()->back()->with('success', 'Taller editat correctament.');
        } catch (ValidationException $e) {
            //Retornem la resposta error si ha ocorregut algun error de validació
            return redirect()->back()->withErrors($e->validator->getMessageBag(), 'editarTallerForm')->withInput()->with('codi', $codi);
        } catch (\Exception $e) {
            //Retornem la resposta error si ha ocorregut algun error
            return redirect()->back()->with('error', 'Error al editar el taller.')->withInput();
        }
    }

    public function dadesTaller(Request $request)
    {
        try {

            $codi = $request->codi;

            $taller = Taller::find($codi);



            //Comprovem si el taller existeix i si no existeix retornem error per javascript
            if (!$taller) {
                return  response()->json(['error' => 'No s\'ha trobat el taller.']);
            }

            //Comprovem si l'usuari té permisos per editar el taller
            if (Gate::denies('isCreatorOrAdmin', $taller)) {
                return  response()->json(['error' => 'No tens permisos per editar aquest taller.']);
            }

            //Retornem la resposta success si tot ha anat bé
            return  response()->json(['success' => 'Taller trobat correctament.', 'taller' => $taller, 'codi' => $codi]);
        } catch (\Exception $e) {
            //Retornem la resposta error si ha ocorregut algun error
            return  response()->json(['error' => 'Error al trobar el taller.']);
        }
    }

    // Funció per a que els alumnes puguin apuntar-se a un taller amb el nom de participar
    public function participar($codi)
    {
        try {

            $taller = Taller::find($codi);

            //Comprovem si el taller existeix
            if (!$taller) {
                return redirect()->back()->with('error', 'No s\'ha trobat el taller.');
            }

            if (Gate::allows('isCreatorOrAdmin', $taller)) {
                return redirect()->back()->with('error', 'No pots apuntar-te a aquest taller.');
            }

            if (Gate::allows('isCreator', Auth::user())) {
                return redirect()->back()->with('error', 'No pots apuntar-te a un taller si has creat un taller.');
            }

            if (Gate::allows('isAboveLimit', $taller)) {
                return redirect()->back()->with('error', 'Aquest taller ha arribat al màxim de participants.');
            }

            if (Gate::allows('maximParticipants', Auth::user())) {
                return redirect()->back()->with('error', 'Ja estàs apuntat a 3 tallers.');
            }

            if (Gate::allows('isParticipant', $taller)) {
                return redirect()->back()->with('error', 'Ja estàs apuntat a aquest taller.');
            }

            if(Gate::allows('isAjudant', Auth::user()->email)){
                return redirect()->back()->with('error', 'Ja ets ajudant de un taller.');
            }


            //Afegim el correu del usuari al camp participants del taller
            $taller->participants = $taller->participants . Auth::user()->email . ', ';
            $taller->save();

            //Retornem la resposta success si tot ha anat bé
            return redirect()->back()->with('success', 'Taller apuntat correctament.');
        } catch (\Exception $e) {
            //Retornem la resposta error si ha ocorregut algun error
            return redirect()->back()->with('error', 'Error al apuntar-se al taller.');
        }
    }

    // Funció per a que els alumnes puguin desapuntar-se d'un taller amb el nom de desapuntar
    public function desapuntarse($codi)
    {
        try {

            $taller = Taller::find($codi);

            //Comprovem si el taller existeix
            if (!$taller) {
                return redirect()->back()->with('error', 'No s\'ha trobat el taller.');
            }

            if (Gate::allows('isCreatorOrAdmin', $taller)) {
                return redirect()->back()->with('error', 'No pots utilitzar aquesta funció.');
            }

            if (Gate::denies('isParticipant', $taller)) {
                return redirect()->back()->with('error', 'No estàs apuntat a aquest taller.');
            }

            //Eliminem el correu del usuari al camp participants del taller
            $taller->participants = str_replace(Auth::user()->email . ', ', '', $taller->participants);
            $taller->save();

            //Retornem la resposta success si tot ha anat bé
            return redirect()->back()->with('success', 'Taller desapuntat correctament.');
        } catch (\Exception $e) {
            //Retornem la resposta error si ha ocorregut algun error
            return redirect()->back()->with('error', 'Error al desapuntar-se del taller.');
        }
    }

    public function afegirParticipant(Request $request)
    {
        try {

            $codi = $request->codi;

            $email = $request->email;

            $taller = Taller::find($codi);

            //Comprovem si el taller existeix
            if (!$taller) {
                return redirect()->back()->with('error', 'No s\'ha trobat el taller.');
            }
            
            if (Gate::allows('isAboveLimit', $taller)) {
                return redirect()->back()->with('error', 'Aquest taller ha arribat al màxim de participants.');
            }

         

            //    validem el correu
            $request->validate([
                'email' => 'required|email',
            ], [
                'email.required' => 'El correu és obligatori.',
                'email.email' => 'El correu no és vàlid.',
            ]);


            //Comprovem si l'usuari existeix
            $usuari = Usuari::where('email', $email)->first();
            if ($usuari) {
                return redirect()->back()->with('error', 'Aquest usuari ja existeix, si es vol afegira una activitat has d\'accedir a la pestanya d\'alumnes.');
            }
            //Comprovem si l'usuari ja està apuntat al taller
            if (Gate::allows('isParticipantEmail', [$email, $taller])) {
                return redirect()->back()->with('error', 'Aquest usuari ja esta afegit a l\'activitat.');
            }

            //Afegim el correu del usuari al camp participants del taller
            $taller->participants = $taller->participants . $email . ', ';
            $taller->save();

            //Retornem la resposta success si tot ha anat bé
            return redirect()->back()->with('success', 'Usuari afegit correctament.');
        } catch (ValidationException $e) {
            //Retornem la resposta error si ha ocorregut algun error de validació
            return redirect()->back()->withErrors($e->validator->getMessageBag(), 'afegirParticipant')->withInput()->with('codi', $codi);
        } catch (\Exception $e) {
            //Retornem la resposta error si ha ocorregut algun error
            return redirect()->back()->with('error', 'Error a l\'afegir l\'usuari');
        }
    }

    public function participantsTaller($codi)
    {
        try {

            $taller = Taller::find($codi);

            //Comprovem si el taller existeix
            if (!$taller) {
                return redirect()->back()->with('error', 'No s\'ha trobat el taller.');
            }
            //Comprovem si el taller té participants i si no té retornem un error
            if ($taller->participants == null || $taller->participants == '') {
                return response()->json(['status' => 'error', 'error' => 'No hi ha participants.']);

                //Si té participants retornem la resposta json amb els participants
            } else {
                //Retornem la resposta json success si tot ha anat bé
                $participants = explode(',', $taller->participants);
                // delete last element of array
                array_pop($participants);
                return response()->json(['status' => 'success', 'participants' => $participants]);
            }
        } catch (\Exception $e) {
            //Retornem la resposta error si ha ocorregut algun error
            return response()->json(['status' => 'error', 'error' => 'Hi ha hagut un problema al agafar els participants.']);
        }
    }

    public function eliminarParticipants($codi, Request $request)
    {
        // validem els correus del array participants
        try{
            
            $participants = $request->participants;

            $taller = Taller::find($codi);

            //Comprovem si el taller existeix
            if (!$taller) {
                return redirect()->back()->with('error', 'No s\'ha trobat el taller.');
            }
            
            //validem si els correus del array participants estan participants al taller
            foreach ($participants as $participant) {
                if (Gate::denies('isParticipantEmail', [$participant, $taller])) {
                    return redirect()->back()->with('error', 'Alguns dels participants no està participant actualment.'  . $participant);
                }
            }

            //eliminem els participants del taller
            foreach ($participants as $participant) {
                $taller->participants = str_replace($participant . ', ', '', $taller->participants);
            }
            $taller->save();

            //Retornem la resposta success si tot ha anat bé
            return redirect()->back()->with('success', 'Participants eliminats correctament.');


        }catch(\Exception $e){
            return redirect()->back()->with('error', 'Error al eliminar els participants.');
        }
    }
}
