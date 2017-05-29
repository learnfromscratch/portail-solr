@extends ('admin.template')

@section('leftSidebar')
	<div id="sidebar-nav" class="sidebar">
		<div class="sidebar-scroll">
			<nav>
				<ul class="nav">
					<li><a href="{{ route('dashboard') }}" class="active"><i class="lnr lnr-home"></i> <span>Tableau de bord</span></a></li>
					<li>
						<a href="{{ route('users.all') }}"><i class="lnr lnr-pencil"></i> <span>Gestion des clients</span></a>
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
	Tableau de bord
@endsection

@section('content')

	<!-- VUE D'ENSEMBLE -->
	<div class="panel panel-headline">
		<div class="panel-heading">
			<h3 class="panel-title">Aperçu hebdomadaire</h3>

			@php
				$monday = new Carbon\Carbon('last monday');
			@endphp
			<p class="panel-subtitle">Période: {{ $monday->toFormattedDateString() }} - {{ Carbon\Carbon::now()->toFormattedDateString() }}</p>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-md-3">
					<div class="metric">
						<span class="icon"><i class="fa fa-user"></i></span>
						<p>
							<span class="number">{{ $nbrUser }}</span>
							<span class="title">Utilisateurs</span>
						</p>
					</div>
				</div>
				<div class="col-md-3">
					<div class="metric">
						<span class="icon"><i class="fa fa-file-text"></i></span>
						<p>
							<span class="number">{{ $notIndexed }}</span>
							<span class="title">Articles non indexés</span>
						</p>
					</div>
				</div>
				<div class="col-md-3">
					<div class="metric">
						<span class="icon"><i class="fa fa-eye"></i></span>
						<p>
							<span class="number">0</span>
							<span class="title">Visites</span>
						</p>
					</div>
				</div>
				<div class="col-md-3">
					<div class="metric">
						<span class="icon"><i class="fa fa-download"></i></span>
						<p>
							<span class="number">0</span>
							<span class="title">PDF téléchargés</span>
						</p>
					</div>
				</div>
			</div>

		</div>
	</div>
	<!-- FIN VUE D'ENSEMBLE -->

	<div class="row">
		<div class="col-md-7">
			<!-- TODO LIST -->
			<div class="panel">
				<div class="panel-heading">
					<h3 class="panel-title">Liste de choses à faire</h3>
					<div class="right">
						<button type="button" class="btn-toggle-collapse"><i class="lnr lnr-chevron-up"></i></button>
						<button type="button" class="btn-remove"><i class="lnr lnr-cross"></i></button>
					</div>
				</div>
				<div class="panel-body">
					<ul class="list-unstyled todo-list">
						<li>
							<label class="control-inline fancy-checkbox">
								<input type="checkbox"><span></span>
							</label>
							<p>
								<span class="title">Indexation</span>
								<span class="short-description">Indexer les articles qui viennent d'être ajouté.</span>
								<span class="date">Oct 9, 2016</span>
							</p>
							<div class="controls">
								<a href="#"><i class="icon-software icon-software-pencil"></i></a> <a href="#"><i class="icon-arrows icon-arrows-circle-remove"></i></a>
							</div>
						</li>
					</ul>
				</div>
			</div>
			<!-- END TODO LIST -->
		</div>
		<div class="col-md-5">
			<!-- TIMELINE -->
			<div class="panel panel-scrolling">
				<div class="panel-heading">
					<h3 class="panel-title">Actions</h3>
					<div class="right">
						<button type="button" class="btn-toggle-collapse"><i class="lnr lnr-chevron-up"></i></button>
						<button type="button" class="btn-remove"><i class="lnr lnr-cross"></i></button>
					</div>
				</div>
				<div class="panel-body">
					<a href="{{ route('indexing') }}" type="button" class="btn btn-primary btn-right center-block">Indexer les articles</a> <br>
				</div>
			</div>
			<!-- END TIMELINE -->
		</div>
	</div>
@endsection