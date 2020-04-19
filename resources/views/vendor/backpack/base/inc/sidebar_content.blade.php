<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li class="nav-item nav-dropdown">
	<a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon fa fa-group"></i> Autenticación</a>
	<ul class="nav-dropdown-items">
	  <li class="nav-item"><a class="nav-link" href="{{ backpack_url('user') }}"><i class="nav-icon fa fa-user"></i> <span>Usuarios</span></a></li>
	  <li class="nav-item"><a class="nav-link" href="{{ backpack_url('role') }}"><i class="nav-icon fa fa-group"></i> <span>Roles</span></a></li>
	  <!-- <li class="nav-item"><a class="nav-link" href="{{ backpack_url('permission') }}"><i class="nav-icon fa fa-key"></i> <span>Permissions</span></a></li> -->
	</ul>
</li>

<li class="nav-item nav-dropdown">
	<a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon fa fa-group"></i> Catálogo</a>
	<ul class="nav-dropdown-items">	  
		<li class='nav-item'><a class='nav-link' href='{{ backpack_url('tipohabitacion') }}'><i class='nav-icon fa fa-question'></i> Tipo De Habitación</a></li>
		<li class='nav-item'><a class='nav-link' href='{{ backpack_url('statushabitacion') }}'><i class='nav-icon fa fa-question'></i> Status De La Habitación</a></li>
		<li class='nav-item'><a class='nav-link' href='{{ backpack_url('metodopago') }}'><i class='nav-icon fa fa-question'></i> Métodos De Pagos</a></li>
	</ul>
</li>

<li class='nav-item'><a class='nav-link' href='{{ backpack_url('cliente') }}'><i class='nav-icon fa fa-question'></i> Clientes</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('habitacion') }}'><i class='nav-icon fa fa-question'></i> Habitaciones</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('promocion') }}'><i class='nav-icon fa fa-question'></i> Promociones</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('reservacion') }}'><i class='nav-icon fa fa-question'></i> Reservaciones</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('servicioadicional') }}'><i class='nav-icon fa fa-question'></i> Servicios Adicionales</a></li>