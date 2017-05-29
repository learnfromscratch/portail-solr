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
	Profile
@endsection

@section('css')
	<link rel="stylesheet" type="text/css" href="/css/tags.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="/css/bootstrap-tagsinput.css">
@endsection

@section('content')
	<div class="panel panel-profile">
		<div class="clearfix">

			@if (Session::has('success'))
				<div class="alert alert-success alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<i class="fa fa-check-circle"></i> {{ Session::get('success') }}
				</div>
			@endif

			@if (Session::has('info'))
				<div class="alert alert-info alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<i class="fa fa-check-circle"></i> {{ Session::get('info') }}
				</div>
			@endif
			
			<!-- LEFT COLUMN -->
			<div class="profile-left">
				<!-- PROFILE HEADER -->
				<div class="profile-header">
					<div class="overlay"></div>
					<div class="profile-main">
						<h3 class="name">{{ strtoupper($user->name) }}</h3>
					</div>
					<div class="profile-stat">
						<div class="row">
							<div class="col-md-4 stat-item">
								{{ count($user->keywords) }} <span>Mot clés</span>
							</div>
							<div class="col-md-4 stat-item">
								0 <span>Visites</span>
							</div>
							<div class="col-md-4 stat-item">
								0 <span>PDF téléchagés</span>
							</div>
						</div>
					</div>
				</div>
				<!-- END PROFILE HEADER -->
				<!-- PROFILE DETAIL -->
				<div class="profile-detail">
					<div class="profile-info">
						<h4 class="heading">Infos personnels</h4>
						<ul class="list-unstyled list-justify">
							<li>E-mail <span>{{ $user->email }}</span></li>
							<li>Numéro de téléphone <span>{{ $user->tel }}</span></li>
							<li>Adresse <span>{{ $user->address }}</span></li>
						</ul>
					</div>
					<div class="profile-info">
						<h4 class="heading">Abonnement</h4>
						<ul class="list-unstyled list-justify">
							@if ($user->abonnement->end_date > Carbon\Carbon::now())
								<li>Status<span class="label label-success">VALIDE</span></li>
							@else
								<li>Status<span class="label label-success">NON VALIDE</span></li>
							@endif
						</ul>
					</div>
					<div class="text-center"><a href="" data-toggle="modal" data-target="#edit" class="btn btn-primary">Editer le profile</a></div>
				</div>
				<!-- END PROFILE DETAIL -->
			</div>
			<!-- LEFT COLUMN -->
			<!-- RIGHT COLUMN -->
			<div class="profile-right">
				<h4 class="heading"></h4>
				<!-- TABBED CONTENT -->
				<div class="custom-tabs-line tabs-line-bottom left-aligned">
					<ul class="nav" role="tablist">
						<li class="active"><a href="#activity" role="tab" data-toggle="tab">Activité Récente</a></li>
						<li><a href="#keyword" role="tab" data-toggle="tab">Mots clés <span class="badge">{{ count($user->keywords) }}</span></a></li>
					</ul>
				</div>
				<div class="tab-content">
					<div class="tab-pane fade in active" id="activity">
						<ul class="list-unstyled activity-timeline">
							<li>
								<i class="fa fa-download activity-icon"></i>
								<p>Téléchargé le PDF de <a href="#">Economie</a> <span class="timestamp">il y'a 2 minutes</span></p>
							</li>
							<li>
								<i class="fa fa-eye activity-icon"></i>
								<p>Consulté l'article <a href="#">Economie</a> <span class="timestamp">il y'a 7 minutes</span></p>
							</li>
							
						</ul>
						<div class="margin-top-30 text-center"><a href="#" class="btn btn-default">Voir toute l'activité</a></div>
					</div>
					<div class="tab-pane fade" id="keyword">
						<div class="table-responsive">
							<table class="table project-table">
								<thead>
									<tr>
										<th>Nom</th>
										<th>Newsletter</th>
									</tr>
								</thead>
								<tbody>
									@foreach($user->keywords as $keyword)
										<tr>
											<td><a href="#">{{ $keyword->name }}</a></td>
											<td><span class="label label-success">ACTIVE</span></td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<!-- END TABBED CONTENT -->
			</div>
			<!-- END RIGHT COLUMN -->

			<!-- Modal -->
			<div id="edit" class="modal fade" role="dialog">
			  <div class="modal-dialog">

			    <!-- Modal content-->
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal">&times;</button>
			        <h4 class="modal-title">Editer le profile</h4>
			      </div>
			      <form class="form-horizontal role="form" method="POST" action="{{route('users.update', ['id' => $user->id])}}">
			      	{{ csrf_field() }}

			      	<div class="modal-body">
			      		<!-- TABBED CONTENT -->
						<div class="custom-tabs-line tabs-line-bottom left-aligned">
							<ul class="nav" role="tablist">
								<li class="active"><a href="#editBasic" role="tab" data-toggle="tab">Infos personnelles</a></li>
								<li><a href="#editKeyword" role="tab" data-toggle="tab">Mots clés</a></li>
								<li><a href="#abonnement" role="tab" data-toggle="tab">Abonnement</a></li>
							</ul>
						</div>
						<div class="tab-content">
							<div class="tab-pane fade in active" id="editBasic">
								<form class="form-horizontal role="form" method="POST" action="{{ route('register'), ['id' => $user->id] }}">
									{{ csrf_field() }}

									<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
					                  <label for="email" class="col-sm-2 control-label">E-mail</label>

					                  <div class="col-sm-10">
					                    <input type="email" class="form-control" name="email" placeholder="Saisissez l'e-mail" value="{{ $user->email }}" required>

					                    @if ($errors->has('email'))
				                            <span class="help-block">
				                                <strong>{{ $errors->first('email') }}</strong>
				                            </span>
				                        @endif

					                  </div>
					                </div>

					                <div class="form-group{{ $errors->has('tel') ? ' has-error' : '' }}">
					                  <label for="tel" class="col-sm-2 control-label">Téléphone</label>

					                  <div class="col-sm-10">
					                    <input type="number" class="form-control" name="tel" placeholder="Saisissez le numéro de téléphone" value="{{ $user->tel }}">

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
					                    <input type="text" class="form-control" name="address" placeholder="Saisissez l'adresse" value="{{ $user->address }}">

					                    @if ($errors->has('address'))
				                            <span class="help-block">
				                                <strong>{{ $errors->first('address') }}</strong>
				                            </span>
				                        @endif

					                  </div>
					                </div>
							</div>

							<div class="tab-pane fade" id="editKeyword">
								<div class="form-group">
				                  <label for="tel" class="col-sm-2 control-label">Mots clés</label>
				                
				                  <div class="col-sm-10">
				                    <div class="tags-input" data-name="keywords[]"></div>
				                    @foreach($user->keywords as $key)

				                    @endforeach
				                    <input type="text" class="keywords-data"  value="" data-role="tagsinput" />
				                  </div>
				                </div>
							</div>

							<div class="tab-pane fade" id="abonnement">
								<div class="form-group{{ $errors->has('end_date') ? ' has-error' : '' }}">
				                  <label for="end_date" class="col-sm-2 control-label">Fini le</label>

				                  <div class="col-sm-4">
				                    <input type="date" class="form-control" name="end_date" value="{{ $user->abonnement->end_date }}">
				                  </div>
				                </div>
							</div>
						</div>
						<!-- END TABBED CONTENT -->
				        <p>
				        	
				        </p>
				    </div>
				    <div class="modal-footer">
				    	<button type="submit" class="btn btn-success">Valider les modifications</button>
				        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
			        </div>
			      </form>
			    </div>

			  </div>
			</div>
		</div>
	</div>
@endsection

@section('javascript')
<script src="{{ asset('js/bootstrap-tagsinput.min.js') }}"></script>

	<script type="text/javascript">
		var keywords = new Array();
	    <?php
	    foreach ($user->keywords as $keywords) {
	    ?>
	    keywords.push(<?php echo json_encode($keywords->name); ?>);
	    <?php
	    }
	    ?>
	</script>

<script>
$(".keywords-data").tagsinput({{$keywords }})
</script>
	<script type="text/javascript" src="/js/tagsUpdate.js"></script>
@endsection