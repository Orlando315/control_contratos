@if(Session::has('flash_message'))
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="alert {{ Session::get('flash_class') }}{{ Session::has('flash_important') ? ' alert-important' : '' }}">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong class="text-center">{{ Session::get('flash_message') }}</strong> 
      </div>
    </div>
  </div>
@endif
