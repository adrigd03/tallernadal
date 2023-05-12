<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/2c36e9b7b1.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>Tallers</title>
</head>

<body>

    <nav class="navbar navbar-light navbar-expand bg-white shadow mb-4 topbar static-top ">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="http://tallernadal.com/images/logosapa_transparent.png" alt="logo de l'institut sa palomera" width="145" height="64" class="d-inline-block align-text-top">
                <span class="mx-auto">Tallers de Nadal</span>

            </a>
            <!-- barra navegació -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Tallers</a>
                    </li>
                    @can('isSuperAdmin')
                    <li class="nav-item">
                        <a class="nav-link" href="/assignar">Assignar Admins</a>
                    </li>
                    @endcan
                    @can('isSuperAdmin')
                    <li class="nav-item">
                        <a class="nav-link" href="/configuracio">Configuracio</a>
                    </li>
                    @endcan

                </ul>

            </div>
            <ul class="navbar-nav flex-nowrap ms-auto">
                <li class="nav-item dropdown no-arrow">
                    <div class="nav-item dropdown no-arrow">
                        <a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown" href="#">
                            <span class="d-none d-lg-inline me-2 text-gray-600 small">{{ Auth::user()->nom }} {{ Auth::user()->cognoms }}</span>

                            <img class="border rounded-circle img-profile" height="40px" width="40px" src="{{ session('avatarUrl')}}" alt="avatar del usuari" />
                        </a>
                        <div class="dropdown-menu shadow dropdown-menu-end animated--grow-in">

                            <a class="dropdown-item" href="#">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="btn me-2 text-gray-400" type="submit">Logout</button>
                                </form>
                            </a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>

    </nav>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="text-primary m-0 fw-bold">Crear Taller</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <button id="showForm" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tallerModal">Crear Taller</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="divAlert" class="row">
            @if (session('success'))
            <div class="col-12">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>S'ha completat l'acció!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
            @endif
            @if (session('error'))
            <div class="col-12">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> {{ session('error')}}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
            @endif

        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="text-primary m-0 fw-bold">Tallers</h6>
                    </div>
                    <div class="card-body">
                        <div class=" table mt-2" id="dataTable" role="grid" aria-describedby="dataTable_info">
                            <table class="table my-0" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>Taller</th>
                                        <th>Prof/Alum</th>
                                        <th>Descripció</th>
                                        <th>Adreçat a</th>
                                        <th>Nº alumnes</th>
                                        <th>Material</th>
                                        <th>Aula/Espai</th>
                                        @can('isAdmin')
                                        <th>Data creació</th>
                                        @endcan
                                        <th class="col-1">Opcions</th>
                                        @if(Gate::denies('isAdmin', Auth::user()))
                                        <th>Participar</th>
                                        @else
                                        <th>Afegir usuari no registrat</th>
                                        <th>Eliminar usuari participant</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tallers as $taller)
                                    <tr>
                                        <td>{{ $taller->nom }}</td>
                                        <td>{{ $taller->creador}}</td>
                                        <td>{{ $taller->descripcio }}</td>
                                        <td>{{ $taller->adrecat }}</td>
                                        <td>{{ $taller->nalumnes }}</td>
                                        <td>{{ $taller->materials }}</td>
                                        <td>{{ $taller->espai }}</td>
                                        @can('isAdmin')
                                        <td>{{ $taller->created_at }}</td>
                                        @endcan
                                        @can('isCreatorOrAdmin', $taller)
                                        <td class="text-center justify-content-center align-items-center ">
                                            <div class="row gap-2 m-0">


                                                <div class="col-5 p-0 ">
                                                    <!-- delete button -->
                                                    <form class="" method="POST" action="{{ route('esborrartaller', $taller->codi) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger w-100 ">
                                                            <i class="fas fa-trash text-center w-100"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                                <div class="col-5 p-0 ">
                                                    <!-- edit button -->
                                                    <button class="btn btn-primary w-100" id="editarTallerButton{{$taller->codi}}" name="editarButton" data-bs-id="{{ $taller->codi }}">
                                                        <i class="fas fa-edit text-center w-100"></i>
                                                    </button>
                                                </div>

                                            </div>

                                        </td>
                                        @endcan

                                        @if(Gate::denies('isCreatorOrAdmin', $taller))
                                        <td></td>
                                        @if(Gate::denies('isParticipantOrAboveLimit', $taller) && Gate::denies('maximParticipacio', Auth::user()) && Gate::denies('isCreator', Auth::user()) && Gate::denies('isAjudant'))
                                        <td class="col-1">
                                            <form id="participar" class="" method="POST" action="{{ route('participar', $taller->codi) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-primary w-100 ">
                                                    <i class="fas fa-user-plus text-center w-100"></i>
                                                </button>
                                            </form>
                                        </td>

                                        @elseif(Gate::allows('isParticipant', $taller))
                                        <td class="col-1">
                                            <form class="desapuntarse" method="POST" action="{{ route('desapuntarse', $taller->codi) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-danger w-100 ">
                                                    <i class="fas fa-user-minus text-center w-100"></i>
                                                </button>
                                            </form>
                                        </td>
                                        @else
                                        <td class="col-1">
                                            <button type="submit" class="btn btn-primary w-100 " disabled>
                                                <i class="fas fa-user-plus text-center disabled w-100"></i>
                                            </button>
                                        </td>
                                        @endif

                                        @elseif(GATE::allows('isAdmin', Auth::user()))
                                        <td class="col-1">
                                            <button type="button" name="afegirParticipant" class="btn btn-primary w-100" data-bs-id="{{$taller->codi}}">
                                                <i class="fas fa-user-plus text-center w-100"></i>
                                            </button>
                                        </td>
                                        <td class="col-1">
                                            <button type="button" name="eliminarParticipants" class="btn btn-danger w-100" data-bs-id="{{$taller->codi}}">
                                                <i class="fas fa-user-minus text-center w-100"></i>
                                            </button>
                                        </td>
                                        @endif

                                    </tr>
                                    @endforeach
                                </tbody>


                            </table>
                            <div class="row">
                                <div class="col d-flex justify-content-center">
                                    {{ $tallers->links()}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    </div>

    <div class="modal fade" id="tallerModal" tabindex="-1" aria-labelledby="tallerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tallerModalLabel">Crear Taller</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="tallerForm" action="/creartaller" method="POST">
                        @csrf


                        <div class="row mb-3">

                            <div class="col">
                                <label for="creador" class="form-label">Creador</label>
                                <input type="text" class="form-control" id="creador" name="creador" value="{{ Auth::user()->nom}} {{ Auth::user()->cognoms}}" disabled>
                            </div>

                            <div class="col">
                                <label for="id" class="form-label">ID</label>
                                <input type="text" class="form-control" id="id" name="id" value="{{ $nextId }}" disabled>
                            </div>
                        </div>

                        <div class="row mb-3">

                            <div class="col">
                                <label for="nomTaller" class="form-label">Nom del Taller</label>
                                <input type="text" class="form-control @error('nomTaller') is-invalid @enderror" id="nomTaller" name="nomTaller" value="{{old('nomTaller')}}" required>
                                @error('nomTaller', 'crearTallerForm')
                                <div class="alert alert-danger mt-2" name="error">{{ $message }}</div>
                                @enderror

                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="descripcio" class="form-label">Descripció</label>
                            <textarea class="form-control @error('descripcio') is-invalid @enderror" id="descripcio" name="descripcio" rows="3" required>{{old('descripcio')}}</textarea>
                            @error('descripcio', 'crearTallerForm')
                            <div class="alert alert-danger mt-2" name="error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <div class="form-group">
                                    <label for="adrecat" class="form-label">Adreçat a</label>
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" id="adrecatDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            Seleccionar
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="adrecatDropdown" id="adrecat">
                                            <li><input class="form-check-input ms-1 me-2" type="checkbox" name="adrecat[]" value="1er ESO" id="1er ESO" {{ (collect(old('adrecat'))->contains('1er ESO')) ? 'checked':'' }}>
                                                <label class="form-check-label" for="1er ESO">1er ESO</label>
                                            </li>
                                            <li><input class="form-check-input ms-1 me-2" type="checkbox" name="adrecat[]" value="2n ESO" id="2n ESO" {{ (collect(old('adrecat'))->contains('2n ESO')) ? 'checked':'' }}>
                                                <label class="form-check-label" for="2n ESO">2n ESO</label>
                                            </li>
                                            <li><input class="form-check-input ms-1 me-2" type="checkbox" name="adrecat[]" value="3er ESO" id="3er ESO" {{ (collect(old('adrecat'))->contains('3er ESO')) ? 'checked':'' }}>
                                                <label class="form-check-label" for="3er ESO">3er ESO</label>
                                            </li>
                                            <li><input class="form-check-input ms-1 me-2" type="checkbox" name="adrecat[]" value="4rt ESO" id="4rt ESO" {{ (collect(old('adrecat'))->contains('4rt ESO')) ? 'checked':'' }}>
                                                <label class="form-check-label" for="4rt ESO">4rt ESO</label>
                                            </li>
                                            <li><input class="form-check-input ms-1 me-2" type="checkbox" name="adrecat[]" value="1er SMX" id="1er SMX" {{ (collect(old('adrecat'))->contains('1er SMX')) ? 'checked':'' }}>
                                                <label class="form-check-label" for="1er SMX">1er SMX</label>
                                            </li>
                                            <li><input class="form-check-input ms-1 me-2" type="checkbox" name="adrecat[]" value="2n SMX" id="2n SMX" {{ (collect(old('adrecat'))->contains('2n SMX')) ? 'checked':'' }}>
                                                <label class="form-check-label" for="2n SMX">2n SMX</label>
                                            </li>
                                            <li><input class="form-check-input ms-1 me-2" type="checkbox" name="adrecat[]" value="1er FPB" id="1er FPB" {{ (collect(old('adrecat'))->contains('1er FPB')) ? 'checked':'' }}>
                                                <label class="form-check-label" for="1er FPB">1er FPB</label>
                                            </li>
                                            <li><input class="form-check-input ms-1 me-2" type="checkbox" name="adrecat[]" value="2n FPB" id="2n FPB" {{ (collect(old('adrecat'))->contains('2n FPB')) ? 'checked':'' }}>
                                                <label class="form-check-label" for="2n FPB">2n FPB</label>
                                            </li>
                                            <li><input class="form-check-input ms-1 me-2" type="checkbox" name="adrecat[]" value="1er BAT" id="1er BAT" {{ (collect(old('adrecat'))->contains('1er BAT')) ? 'checked':'' }}>
                                                <label class="form-check-label" for="1er BAT">1er BAT</label>
                                            </li>
                                            <li><input class="form-check-input ms-1 me-2" type="checkbox" name="adrecat[]" value="2n BAT" id="2n BAT" {{ (collect(old('adrecat'))->contains('2n BAT')) ? 'checked':'' }}>
                                                <label class="form-check-label" for="2n BAT">2n BAT</label>
                                            </li>
                                            <!-- '1er ESO','2n ESO', -->
                                        </ul>
                                    </div>
                                </div>
                                @error('adrecat', 'crearTallerForm')
                                <div class="alert alert-danger mt-2" name="error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="places" class="form-label">Places</label>
                                <input type="number" class="form-control @error('nalumnes') is-invalid @enderror" id="places" name="nalumnes" value="{{old('nalumnes')}}" min="2" max="20" required>
                                @error('nalumnes', 'crearTallerForm')
                                <div class="alert alert-danger mt-2" name="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class=" mb-3">
                            <label for="materials" class="form-label">Materials necessaris</label>
                            <textarea class="form-control @error('materials') is-invalid @enderror" id="materials" name="materials" rows="3" required>{{old('descripcio')}}</textarea>
                            @error('materials', 'crearTallerForm')
                            <div class="alert alert-danger mt-2" name="error">{{ $message }}</div>
                            @enderror

                        </div>

                        @can('isAdmin')
                        <div class=" mb-3">
                            <label for="ajudants" class="form-label">Afegir els correus dels ajudants separat per comas</label>

                            <input class="form-control" type="text" id="ajudants" name="ajudants" value="{{ old('ajudants')}}">

                            @error('ajudants', 'crearTallerForm')
                            <div class="alert alert-danger mt-2" name="error">{{ $message }}</div>
                            @enderror


                        </div>

                        <div class=" mb-3">
                            <label for="espai" class="form-label">Espai</label>

                            <input class="form-control" type="text" id="espai" name="espai" value="{{ old('espai')}}">

                            @error('espai', 'crearTallerForm')
                            <div class="alert alert-danger mt-2" name="error">{{ $message }}</div>
                            @enderror


                        </div>


                        @endcan

                        <button type="submit" class="btn btn-primary">Crear</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editarTallerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tallerModalLabel">Editar Taller</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if (session('codi'))
                    <form id="editarTallerForm" action="/editarTaller/{{ session('codi') }}" method="POST">
                        @else
                        <form id="editarTallerForm" action="/editarTaller" method="POST">
                            @endif
                            @csrf

                            <div class="row mb-3">

                                <div class="col">
                                    <label for="creador" class="form-label">Creador</label>
                                    <input type="text" class="form-control" id="editarcreador" name="creador" value="{{ Auth::user()->nom}} {{ Auth::user()->cognoms}}" disabled>
                                </div>

                                <div class="col">
                                    <label for="id" class="form-label">ID</label>
                                    <input type="text" class="form-control" id="editarid" name="id" value="{{ session('codi') ?? ''}}" disabled>
                                </div>
                            </div>

                            <div class="row mb-3">

                                <div class="col">
                                    <label for="nomTaller" class="form-label">Nom del Taller</label>
                                    <input type="text" class="form-control @error('nomTaller') is-invalid @enderror" id="editarnomTaller" name="nomTaller" value="{{old('nomTaller')}}" required>
                                    @error('nomTaller', 'editarTallerForm')
                                    <div class="alert alert-danger mt-2" name="error">{{ $message }}</div>
                                    @enderror

                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="editardescripcio" class="form-label">Descripció</label>
                                <textarea class="form-control @error('descripcio') is-invalid @enderror" id="editardescripcio" name="descripcio" rows="3" required>{{old('descripcio')}}</textarea>
                                @error('descripcio', 'editarTallerForm')
                                <div class="alert alert-danger mt-2" name="error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="adrecat" class="form-label">Adreçat a</label>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button" id="editaradrecatDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                                Seleccionar
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="adrecatDropdown" id="adrecat">
                                                <li><input class="form-check-input ms-1 me-2" type="checkbox" name="adrecat[]" value="1er ESO" id="edit1erESO" {{ (collect(old('adrecat'))->contains('1er ESO')) ? 'checked':'' }}>
                                                    <label class="form-check-label" for="edit1erESO">1er ESO</label>
                                                </li>
                                                <li><input class="form-check-input ms-1 me-2" type="checkbox" name="adrecat[]" value="2n ESO" id="edit2nESO" {{ (collect(old('adrecat'))->contains('2n ESO')) ? 'checked':'' }}>
                                                    <label class="form-check-label" for="edit2nESO">2n ESO</label>
                                                </li>
                                                <li><input class="form-check-input ms-1 me-2" type="checkbox" name="adrecat[]" value="3er ESO" id="edit3erESO" {{ (collect(old('adrecat'))->contains('3er ESO')) ? 'checked':'' }}>
                                                    <label class="form-check-label" for="edit3erESO">3er ESO</label>
                                                </li>
                                                <li><input class="form-check-input ms-1 me-2" type="checkbox" name="adrecat[]" value="4rt ESO" id="edit4rtESO" {{ (collect(old('adrecat'))->contains('4rt ESO')) ? 'checked':'' }}>
                                                    <label class="form-check-label" for="edit4rtESO">4rt ESO</label>
                                                </li>
                                                <li><input class="form-check-input ms-1 me-2" type="checkbox" name="adrecat[]" value="1er SMX" id="edit1erSMX" {{ (collect(old('adrecat'))->contains('1er SMX')) ? 'checked':'' }}>
                                                    <label class="form-check-label" for="edit1erSMX">1er SMX</label>
                                                </li>
                                                <li><input class="form-check-input ms-1 me-2" type="checkbox" name="adrecat[]" value="2n SMX" id="edit2nSMX" {{ (collect(old('adrecat'))->contains('2n SMX')) ? 'checked':'' }}>
                                                    <label class="form-check-label" for="edit2nSMX">2n SMX</label>
                                                </li>
                                                <li><input class="form-check-input ms-1 me-2" type="checkbox" name="adrecat[]" value="1er FPB" id="edit1erFPB" {{ (collect(old('adrecat'))->contains('1er FPB')) ? 'checked':'' }}>
                                                    <label class="form-check-label" for="edit1erFPB">1er FPB</label>
                                                </li>
                                                <li><input class="form-check-input ms-1 me-2" type="checkbox" name="adrecat[]" value="2n FPB" id="edit2nFPB" {{ (collect(old('adrecat'))->contains('2n FPB')) ? 'checked':'' }}>
                                                    <label class="form-check-label" for="edit2nFPB">2n FPB</label>
                                                </li>
                                                <li><input class="form-check-input ms-1 me-2" type="checkbox" name="adrecat[]" value="1er BAT" id="edit1erBAT" {{ (collect(old('adrecat'))->contains('1er BAT')) ? 'checked':'' }}>
                                                    <label class="form-check-label" for="edit1erBAT">1er BAT</label>
                                                </li>
                                                <li><input class="form-check-input ms-1 me-2" type="checkbox" name="adrecat[]" value="2n BAT" id="edit2nBAT" {{ (collect(old('adrecat'))->contains('2n BAT')) ? 'checked':'' }}>
                                                    <label class="form-check-label" for="edit2nBAT">2n BAT</label>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    @error('adrecat', 'editarTallerForm')
                                    <div class="alert alert-danger mt-2" name="error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <label for="editarplaces" class="form-label">Places</label>
                                    <input type="number" class="form-control @error('nalumnes') is-invalid @enderror" id="editarplaces" name="nalumnes" value="{{old('nalumnes')}}" min="2" max="20" required>
                                    @error('nalumnes', 'editarTallerForm')
                                    <div class="alert alert-danger mt-2" name="error">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                            <div class=" mb-3">
                                <label for="materials" class="form-label">Materials necessaris</label>
                                <textarea class="form-control @error('materials') is-invalid @enderror" id="editarmaterials" name="materials" rows="3" required>{{old('descripcio')}}</textarea>
                                @error('materials', 'editarTallerForm')
                                <div class="alert alert-danger mt-2" name="error">{{ $message }}</div>
                                @enderror

                            </div>
                            @can('isAdmin')
                            <div class=" mb-3">
                                <label for="editarajudants" class="form-label">Afegir els correus dels ajudants separat per comas</label>

                                <input class="form-control" type="text" id="editarajudants" name="ajudants" value="{{ old('ajudants')}}">

                                @error('ajudants', 'editarTallerForm')
                                <div class="alert alert-danger mt-2" name="error">{{ $message }}</div>
                                @enderror


                            </div>

                            <div class=" mb-3">
                                <label for="editarespai" class="form-label">Espai</label>

                                <input class="form-control" type="text" id="editarespai" name="espai" value="{{ old('espai')}}">

                                @error('espai', 'editarTallerForm')
                                <div class="alert alert-danger mt-2" name="error">{{ $message }}</div>
                                @enderror


                            </div>
                            @endcan

                            <button type="submit" class="btn btn-primary">Editar</button>
                        </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="afegirParticipantModal" tabindex="-1" aria-labelledby="afegirParticipantModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Afegir com participant a un usuari no registrat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if (session('codi'))

                    <form method="POST" id="formAfegirParticipant" action="/afegirParticipant/{{session('codi')}}">
                        @else
                        <form method="POST" id="formAfegirParticipant" action="/afegirParticipant">
                            @endif
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Correu de l'usuari</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="afegirParticipant" name="email" value="{{old('email')}}" required>
                                @error('email', 'afegirParticipant')
                                <div class="alert alert-danger mt-2" name="error">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Afegir</button>
                        </form>

                </div>
            </div>
        </div>

    </div>

    <div class="modal fade" id="eliminarParticipantModal" tabindex="-1" aria-labelledby="eliminarParticipantModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Eliminar a un participant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if (session('codi'))

                    <form method="POST" id="formeliminarParticipant" action="/eliminarParticipants/{{session('codi')}}">
                        @else
                        <form method="POST" id="formeliminarParticipant" action="/eliminarParticipants">
                            @endif
                            @csrf
                            <div class="mb-3">
                            <h4>Participants</h4>
                                <div id="listaParticipants">

                                </div>
                                @error('participants', 'eliminarParticipants')
                                <div class="alert alert-danger mt-2" name="error">{{ $message }}</div>
                                @enderror
                                <div class="row">
                                    <div class="col-12" id="divAlertEliminarParticipants">


                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>

                </div>
            </div>
        </div>

    </div>

</body>
<script src="{{ asset('js/home.js')}}"></script>
@if ($errors->crearTallerForm->any())
<script type="text/javascript">
    $(document).ready(function() {
        $('#tallerModal').modal('show');
    });
</script>
@endif

@if ($errors->editarTallerForm->any())
<script type="text/javascript">
    $(document).ready(function() {
        $('#editModal').modal('show');
    });
</script>
@endif

@if ($errors->afegirParticipant->any())
<script type="text/javascript">
    $(document).ready(function() {
        $('#afegirParticipantModal').modal('show');
    });
</script>
@endif


</html>