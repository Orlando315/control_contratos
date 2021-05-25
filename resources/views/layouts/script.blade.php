<!-- Mainly scripts -->
<script src="{{ asset('js/jquery-3.1.1.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
<script src="{{ asset('js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>

<!-- Custom and plugin javascript -->
<script src="{{ asset('js/inspinia.js') }}"></script>
<script src="{{ asset('js/plugins/pace/pace.min.js') }}"></script>

<!-- GITTER -->
<script src="{{ asset('js/plugins/gritter/jquery.gritter.min.js') }}"></script>

<!-- Toastr -->
<script src="{{ asset('js/plugins/toastr/toastr.min.js') }}"></script>

<!-- Datatable -->
<script src="{{ asset('js/plugins/dataTables/datatables.min.js') }}"></script>
<script src="{{ asset('js/plugins/dataTables/dataTables.bootstrap4.min.js') }}"></script>

<!-- Custom js -->
<script type="text/javascript">
  $(document).ready(function() {
    $('div.alert').not('.alert-important').delay(7000).slideUp(300);

    $('.data-table').DataTable({
      dom: `
        <"row"<"col-md-4"l><"col-md-3 text-center"B><"col-md-5"f>>
        <"row"<"col-12"tr>>
        <"row"<"col-md-5"i><"col-md-7"p>>
      `,
      responsive: true,
      language: {
        url:'{{ asset( "js/plugins/dataTables/spanish.json" ) }}'
      },
      buttons: [{
        extend: 'print',
        text: 'Imprimir',
        footer: true,
      }],
      pageLength: 25,
      columnDefs: [
        {
          targets: [0],
          visible: false,
          searchable: false
        }
      ]
    });

    $('.btn-confirmar').click(function(){
      let btn = $(this),
          action = btn.data('path');

      btn.prop('disabled', true)

      $.ajax({
        type: 'POST',
        url: action,
        data: {
          _token: '{{ csrf_token() }}',
          _method: 'PATCH',
        },
        dataType: 'json',
      })
      .done(function (data) {
        if(data.response){
          btn.closest('.alert').alert('close')
        }
      })
      .fail(function(){
        console.log('fail')
      })
      .always(function () {
        btn.prop('disabled', true)
      })
    })

    $('.btn-print').click( function () {
      window.print();
    })
  });
</script>

@if(Auth::user()->isEmpleado() && Auth::user()->empresa->configuracion->hasActiveTerminos() && Auth::user()->haventAcceptedTerms())
  <script type="text/javascript">
    const termsBanner = $('.terms-banner');

    $(document).ready(function() {
      termsBanner.slideDown();

      $('.btn-accept-terms').click(function () {
        let btn = $(this);

        btn.prop('disabled', true);

        $.ajax({
          type: 'POST',
          url: '{{ route("terminos.accept") }}',
          data: {
            _method: 'PATCH',
          },
          dataType: 'json',
        })
        .done(function (data) {
          if(data.response){
            termsBanner.slideUp();
          }
        })
        .always(function () {
          btn.prop('disabled', false);
        });
      });
    });
  </script>
@endif

@yield('script')
