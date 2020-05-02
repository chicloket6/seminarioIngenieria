<li class="nav-item dropdown pr-4">
  <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
    <img class="img-avatar" src="{{ backpack_user()->imagenPerfil ? url(backpack_user()->imagenPerfil->ruta) : backpack_avatar_url(backpack_auth()->user()) }}" alt="{{ backpack_auth()->user()->name }}">
  </a>
  <div class="dropdown-menu dropdown-menu-right mr-4 pb-1 pt-1" id="Dropdown">
    <a class="dropdown-item cambiarImagenPerfil" href="#"><i class="fa fa-image"></i> Cambiar imagen de perfil</a>
    
    <div class="d-flex justify-content-center flex-wrap align-items-center col-sm-12 ocultarPrevia vistaPrevia">
        <div id="msg"></div>
        <form class="w-100" action="{{ backpack_url('cambiar-imagen-perfil') }} " method="POST" id="image-form" enctype="multipart/form-data">@csrf
            <input type="file" name="imagen" class="file d-none" accept="image/*">
            <div class="input-group my-3">
                <input type="text" class="form-control" disabled placeholder="Subir imagen" id="file">
            <div class="input-group-append">
                <button type="button" id="elegirArchivo" class="browse btn btn-primary">Elegir</button>
            </div>
            </div>
        </form>
        <div class="w-100 text-center">
          <img src="https://via.placeholder.com/200x200?text=Vista+Previa" id="preview" class="img-thumbnail">
        </div>
        <button type="button" id="aceptarCambioImagen" class="mt-2 btn btn-success">Aceptar</button>
    </div>
    <div class="dropdown-divider"></div>
    <a class="dropdown-item" href="{{ route('backpack.account.info') }}"><i class="fa fa-user"></i> {{ trans('backpack::base.my_account') }}</a>
    <div class="dropdown-divider"></div>
    <a class="dropdown-item" href="{{ backpack_url('logout') }}"><i class="fa fa-lock"></i> {{ trans('backpack::base.logout') }}</a>
  </div>
</li>

<style>
  .ocultarPrevia{
    display: none !important;
  }
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
  $( document ).ready(function() {
    $('.cambiarImagenPerfil').on('click', function(e){
      $(".vistaPrevia").hasClass('ocultarPrevia') ? $(".vistaPrevia").removeClass('ocultarPrevia') : $(".vistaPrevia").addClass('ocultarPrevia');
    });

    $('.browse').on('click', function(){
      var file = $(this).parents().find(".file");
      file.trigger("click");
    });

    $("#aceptarCambioImagen").on('click', function(){
      $("#image-form").submit();
    });

    $('input[type="file"]').change(function(e) {       
        var fileName = e.target.files[0].name;
        $("#file").val(fileName);

        var reader = new FileReader();
        reader.onload = function(e) {
            // get loaded data and render thumbnail.
            document.getElementById("preview").src = e.target.result;
        };
        // read the image file as a data URL.
        reader.readAsDataURL(this.files[0]);
    });
  });

  document.getElementById("Dropdown").addEventListener('click', function (event) { 
      event.stopPropagation(); 
  });

  document.getElementById("elegirArchivo").addEventListener('click', function (event) { 
      event.stopPropagation(); 
  }); 
</script>