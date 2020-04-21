<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('cliente') }}'><i class='nav-icon fa fa-user-tie'></i> Clientes</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('reservacion') }}'><i class='nav-icon fa fa-concierge-bell'></i> Reservaciones</a></li>




@if(backpack_user()->hasRole('SuperAdmin') || backpack_user()->hasRole('Gerente'))
	<li class='nav-item'><a class='nav-link' href='{{ backpack_url('habitacion') }}'><i class='nav-icon fa fa-bed'></i> Habitaciones</a></li>
	<li class='nav-item'><a class='nav-link' href='{{ backpack_url('promocion') }}'><i class='nav-icon fa fa-gift'></i> Promociones</a></li>
	<li class='nav-item'><a class='nav-link' href='{{ backpack_url('reporte') }}'><i class='nav-icon fa fa-file-excel'></i> Reportes</a></li>

	<li class="nav-item nav-dropdown">
		<a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon fa fa-id-card-alt"></i> Autenticación</a>
		<ul class="nav-dropdown-items">
		<li class="nav-item"><a class="nav-link" href="{{ backpack_url('user') }}"><i class="nav-icon fa fa-user"></i> <span>Usuarios</span></a></li>
		<li class="nav-item"><a class="nav-link" href="{{ backpack_url('role') }}"><i class="nav-icon fa fa-user-tag"></i> <span>Roles</span></a></li>
		<!-- <li class="nav-item"><a class="nav-link" href="{{ backpack_url('permission') }}"><i class="nav-icon fa fa-key"></i> <span>Permissions</span></a></li> -->
		</ul>
	</li>

	<li class="nav-item nav-dropdown">
		<a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon fa fa-book-open"></i> Catálogo</a>
		<ul class="nav-dropdown-items">	  
			<li class='nav-item'><a class='nav-link' href='{{ backpack_url('tipohabitacion') }}'><i class='nav-icon fa fa-door-closed'></i> Tipo De Habitación</a></li>
			<li class='nav-item'><a class='nav-link' href='{{ backpack_url('statushabitacion') }}'><i class='nav-icon fa fa-door-open'></i> Status De La Habitación</a></li>
			<li class='nav-item'><a class='nav-link' href='{{ backpack_url('metodopago') }}'><i class='nav-icon fa fa-credit-card'></i> Métodos De Pagos</a></li>
			<li class='nav-item'><a class='nav-link' href='{{ backpack_url('servicioadicional') }}'><i class='nav-icon fa fa-cocktail'></i> Servicios Adicionales</a></li>
		</ul>
	</li>
@endif
