@extends ('layouts.app')

@section('css')

@endsection

@section('content')
    <div class="container-fluid margin">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar w3-light-gray w3-padding-32">
          <ul class="nav nav-sidebar">
            <li class="active">
              <a href="{{ route('dashboard') }}"><i class="fa fa-bar-chart fa-lg fa-fw w3-text-blue" aria-hidden="true"></i> Tableau de bord</a>
            </li>
            <li>
              <a data-toggle="collapse" data-target="#second">
                <i class="fa fa-pencil fa-lg fa-fw w3-text-blue" aria-hidden="true"></i> Gestion des clients 
                <i class="fa fa-angle-left fa-fw w3-text-blue"></i>
              </a>
              <ul class="nav nav-second-level collapse" id="second">
                <li>
                    <a href="{{ route('list') }}"><i class="fa fa-list fa-fw w3-text-blue"></i> Liste des clients</a>
                </li>
                <li>
                    <a href="{{ route('create') }}"><i class="fa fa-plus fa-fw w3-text-blue"></i> Ajouter un client</a>
                </li>
              </ul>
            </li>
            <li>
              <a href="#">
                <i class="fa fa-list fa-lg fa-fw w3-text-blue" aria-hidden="true"></i> Listes des articles
              </a>
            </li>
          </ul>
        </div>
        
        <div class="col-sm-9 col-md-10">
        	@yield('page')
        </div>
      </div>
    </div>

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
@endsection