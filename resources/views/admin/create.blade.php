@extends ('admin.template')

@section('leftSidebar')
	<div id="sidebar-nav" class="sidebar">
		<div class="sidebar-scroll">
			<nav>
				<ul class="nav">
					<li><a href="{{ route('dashboard') }}"><i class="lnr lnr-home"></i> <span>Tableau de bord</span></a></li>
					<li>
						<a href="{{ route('users.all') }}" class="active"><i class="lnr lnr-pencil"></i> <span>Gestion des clients</span></a>
					</li>
					<li>
						<a href="#"><i class="lnr lnr-list"></i> <span>Listes des Keyword</span></a>
					</li>
					<li>
						<a href="#client" data-toggle="collapse" class="collapsed"><i class="lnr lnr-envelope"></i> <span>Messages</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
						<div id="client" class="collapse ">
							<ul class="nav">
								<li><a href="">Lister les clients</a></li>
								<li><a href="">Ajouter un client</a></li>
							</ul>
						</div>
					</li>
				</ul>
			</nav>
		</div>
	</div>
@endsection

@section('title')
	Gestion des clients
@endsection

@section('css')
	<link rel="stylesheet" type="text/css" href="/css/tags.css">
@endsection

@section('content')
	<div class="col-md-8 col-md-offset-1">
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title">Ajouter un nouveau client</h3>
			</div>
			<div class="panel-body">
				<form class="form-horizontal role="form" method="POST" action="{{ route('register') }}">
						{{ csrf_field() }}

						<div class="form-group{{ $errors->has('cin') ? ' has-error' : '' }}">
		                  <label for="cin" class="col-sm-2 control-label">CIN</label>

		                  <div class="col-sm-10">
		                    <input type="text" class="form-control" name="cin" placeholder="Saisissez le cin" value="{{ old('cin') }}" required autofocus>

		                    @if ($errors->has('cin'))
	                            <span class="help-block">
	                                <strong>{{ $errors->first('cin') }}</strong>
	                            </span>
	                        @endif

		                  </div>
		                </div>

		                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
		                  <label for="name" class="col-sm-2 control-label">Nom</label>

		                  <div class="col-sm-10">
		                    <input type="text" class="form-control" name="name" placeholder="Saisissez le nom" value="{{ old('name') }}" required>

		                    @if ($errors->has('name'))
	                            <span class="help-block">
	                                <strong>{{ $errors->first('name') }}</strong>
	                            </span>
	                        @endif

		                  </div>
		                </div>

		                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
		                  <label for="email" class="col-sm-2 control-label">E-mail</label>

		                  <div class="col-sm-10">
		                    <input type="email" class="form-control" name="email" placeholder="Saisissez l'e-mail" value="{{ old('email') }}" required>

		                    @if ($errors->has('email'))
	                            <span class="help-block">
	                                <strong>{{ $errors->first('email') }}</strong>
	                            </span>
	                        @endif

		                  </div>
		                </div>

		                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
		                  <label for="password" class="col-sm-2 control-label">Mot de passe</label>

		                  <div class="col-sm-10">
		                    <input type="password" class="form-control" name="password" placeholder="Saisissez le mot de passe" required>

		                    @if ($errors->has('password'))
	                            <span class="help-block">
	                                <strong>{{ $errors->first('password') }}</strong>
	                            </span>
	                        @endif

		                  </div>
		                </div>

		                <div class="form-group">
		                  <label for="password_confirmation" class="col-sm-2 control-label">Confirmer mot de passe</label>

		                  <div class="col-sm-10">
		                    <input type="password" class="form-control" name="password_confirmation" placeholder="Confirner mot de passe" required>
		                  </div>
		                </div>

		                <div class="form-group{{ $errors->has('start_date') ? ' has-error' : '' }}">
		                  <label for="start_date" class="col-sm-2 control-label">Début de l'abonnement</label>

		                  <div class="col-sm-4">
		                    <input type="date" class="form-control" name="start_date" value="{{ old('start_date') }}">
		                  </div>
		                </div>

		                <div class="form-group{{ $errors->has('end_date') ? ' has-error' : '' }}">
		                  <label for="end_date" class="col-sm-2 control-label">Fin de l'abonnement</label>

		                  <div class="col-sm-4">
		                    <input type="date" class="form-control" name="end_date" value="{{ old('end_date') }}">
		                  </div>
		                </div>

		                <div class="form-group{{ $errors->has('tel') ? ' has-error' : '' }}">
		                  <label for="tel" class="col-sm-2 control-label">Téléphone</label>

		                  <div class="col-sm-10">
		                    <input type="number" class="form-control" name="tel" placeholder="Saisissez le numéro de téléphone" value="{{ old('tel') }}">

		                    @if ($errors->has('tel'))
	                            <span class="help-block">
	                                <strong>{{ $errors->first('tel') }}</strong>
	                            </span>
	                        @endif

		                  </div>
		                </div>

		                <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
		                  <label for="address" class="col-sm-2 control-label">Adresse</label>

		                  <div class="col-sm-10">
		                    <input type="text" class="form-control" name="address" placeholder="Saisissez l'adresse" value="{{ old('address') }}">

		                    @if ($errors->has('address'))
	                            <span class="help-block">
	                                <strong>{{ $errors->first('address') }}</strong>
	                            </span>
	                        @endif

		                  </div>
		                </div>

		                <div class="form-group">
		                  <label for="tel" class="col-sm-2 control-label">Mots clés</label>

		                  <div class="col-sm-10">
		                    <div class="tags-input" data-name="keywords[]"></div>
		                  </div>
		                </div>

		                <div class="form-group">
		                    <div class="col-md-6 col-md-offset-4">
		                        <button type="submit" class="btn btn-primary pull-right btn-toastr">
		                            Ajouter
		                        </button>
		                    </div>
	                	</div>

                </form>
			</div>
		</div>
	</div>
@endsection

@section('javascript')
	<script type="text/javascript" src="/js/tags.js"></script>
@endsection