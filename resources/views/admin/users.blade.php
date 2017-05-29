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

@section('content')
	<div class="col-md-12">
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title">Liste des clients</h3><br>
				@if (Session::has('success'))
					<div class="alert alert-success alert-dismissible" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<i class="fa fa-check-circle"></i> {{ Session::get('success') }}
					</div>
				@endif
				<div class="row">
					<a href="{{ route('users.create') }}" class="btn btn-success btn-toastr"><i class="fa fa-plus fa-fw"></i> Ajouter un client</a>
					
					<form class="pull-right">
						<div class="input-group">
							<input type="text" value="" class="form-control" id="myInput" onkeyup="filtrer()" placeholder="Rechercher par nom">
						</div>
					</form>
				</div>
			</div>
			<div class="panel-body">
				<table class="table table-hover" id="myTable">
					<thead>
						<tr>
							<th>CIN</th>
							<th>Nom</th>
							<th>E-Mail</th>
							<th>Status abonnement</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($users as $user)
							<tr>
								<td>{{ $user->cin }}</td>
								<td>{{ $user->name }}</td>
								<td>{{ $user->email }}</td>
								@if ($user->abonnement->end_date > Carbon\Carbon::now())
									<td><span class="label label-success">VALIDE</span></td>
								@else
									<td><span class="label label-success">NON VALIDE</span></td>
								@endif
								<td>
									<a href="{{ route('users.show', ['id' => $user->id]) }}" class="btn btn-info btn-xs">Afficher</a>
									<a href="{{ route('users.destroy', ['id' => $user->id]) }}" class="btn btn-danger btn-xs">Supprimer</a>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<script>
	function filtrer() {
	  var input, filter, table, tr, td, i;
	  input = document.getElementById("myInput");
	  filter = input.value.toUpperCase();
	  table = document.getElementById("myTable");
	  tr = table.getElementsByTagName("tr");
	  for (i = 0; i < tr.length; i++) {
	    td = tr[i].getElementsByTagName("td")[1];
	    if (td) {
	      if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
	        tr[i].style.display = "";
	      } else {
	        tr[i].style.display = "none";
	      }
	    }       
	  }
	}
	</script>
@endsection