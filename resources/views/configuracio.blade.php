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
    <title>Assignar</title>
</head>

<body>

    <nav class="navbar navbar-light navbar-expand bg-white shadow mb-4 topbar static-top ">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="http://tallernadal.com/images/logosapa_transparent.png" alt="logo de l'institut sa palomera" width="145" height="64" class="d-inline-block align-text-top">
                <span class="mx-auto">Tallers de Nadal</span>

            </a>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="/home">Tallers</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="/assignar">Assignar Admins</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Configuracio</a>
                    </li>

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
                        <h6 class="text-primary m-0 fw-bold">Importar usuaris</h6>
                    </div>
                    <div class="card-body">
                        <!-- add form to import csv file  -->
                        <form action="{{ route('importar') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="csv" class="form-control">
                            <br>
                            <button class="btn btn-success">Importar CSV</button>
                        </form>


                    </div>
                </div>
            </div>

        </div>

    </div>

    </div>





</body>
<script src="{{ asset('js/configuracio.js')}}"></script>

</html>