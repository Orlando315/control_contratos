<div id="pdfModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
        </button>
        <h4 class="modal-title">Visualizar PDF</h4>
      </div>
      <div class="modal-body">
        <div class="text-center pdf-toolbar mb-3">
          <div class="btn-group">
            <button id="prev" class="btn btn-white">
              <i class="fa fa-long-arrow-left"></i> <span class="d-none d-sm-inline">Anterior</span>
            </button>
            <button id="next" class="btn btn-white">
              <i class="fa fa-long-arrow-right"></i> <span class="d-none d-sm-inline">Siguiente</span>
            </button>
            <span class="btn btn-white hidden-xs">Página: </span>

            <div class="input-group">
              <input type="text" class="form-control" id="page_num">
              <div class="input-group-append">
                <button type="button" class="btn btn-white" id="page_count">/ 1</button>
              </div>
            </div>
            <a id="preview-pdf-download-link" class="btn btn-white" href="#" target="_blank"><i class="fa fa-download"></i> Descargar</a>
          </div>
        </div>

        <div class="ibox">
          <div class="ibox-content text-center sk-loading">
            <div class="sk-spinner sk-spinner-double-bounce">
              <div class="sk-double-bounce1"></div>
              <div class="sk-double-bounce2"></div>
            </div>

            <canvas id="pdf-canvas" class="pdfcanvas w-100"></canvas>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Pdfs -->
<script type="text/javascript" src="{{ asset('js/plugins/pdfjs/pdf.js') }}"></script>
<script type="text/javascript">
  const PDF_IBOX  = $('#pdfModal .ibox-content');
  let pdfDoc = null,
      pageNum = 1,
      pageRendering = false,
      pageNumPending = null,
      scale = 1.5,
      canvas = document.getElementById('pdf-canvas'),
      ctx = canvas.getContext('2d'),
      downloadBtn = $('#preview-pdf-download-link');

  $(document).ready( function(){
    $('#pdfModal').on('show.bs.modal', function (e) {
      let url = $(e.relatedTarget).data('url'); 

      renderPdfOnCanvas(url);
      downloadBtn.attr('href', url);
    });

    $('#pdfModal').on('hide.bs.modal', function (e) {
      destroyPdf();
    });
  });

  function renderPdfOnCanvas(url){
    /**
     * Asynchronously downloads PDF.
     */
    PDFJS.getDocument(url).then(function (pdfDoc_) {
      pdfDoc = pdfDoc_;
      let documentPagesNumber = pdfDoc.numPages;
      document.getElementById('page_count').textContent = '/ ' + documentPagesNumber;

      $('#page_num').on('change', function() {
        let pageNumber = Number($(this).val());

        if(pageNumber > 0 && pageNumber <= documentPagesNumber){
          queueRenderPage(pageNumber, scale);
        }
      });

      PDF_IBOX.toggleClass('sk-loading', false);

      // Initial/first page rendering
      renderPage(pageNum, scale);
    });
  }

  function destroyPdf(){
    pdfDoc.destroy();
    $(canvas).empty();
    pdfDoc = null;
    let context = canvas.getContext('2d');
    context.clearRect(0, 0, canvas.width, canvas.height);
    downloadBtn.attr('href', '#');

    PDF_IBOX.toggleClass('sk-loading', true);
  }

  /**
   * Get page info from document, resize canvas accordingly, and render page.
   * @param num Page number.
   */
  function renderPage(num, scale) {
    pageRendering = true;
    // Using promise to fetch the page
    pdfDoc.getPage(num).then(function(page) {
      let viewport = page.getViewport(scale);
      canvas.height = viewport.height;
      canvas.width = viewport.width;

      // Render PDF page into canvas context
      let renderContext = {
        canvasContext: ctx,
        viewport: viewport
      };
      let renderTask = page.render(renderContext);

      // Wait for rendering to finish
      renderTask.promise.then(function () {
        pageRendering = false;
        if(pageNumPending !== null){
          // New page rendering is pending
          renderPage(pageNumPending);
          pageNumPending = null;
        }
      });
    });

    // Update page counters
    document.getElementById('page_num').value = num;
  }

  /**
   * If another page rendering in progress, waits until the rendering is
   * finised. Otherwise, executes rendering immediately.
   */
  function queueRenderPage(num) {
    if(pageRendering){
      pageNumPending = num;
    }else{
      renderPage(num, scale);
    }
  }

  /**
   * Displays previous page.
   */
  function onPrevPage() {
    if(pageNum <= 1){
      return;
    }
    pageNum--;
    let scale = pdfDoc.scale;
    queueRenderPage(pageNum, scale);
  }
  document.getElementById('prev').addEventListener('click', onPrevPage);

  /**
   * Displays next page.
   */
  function onNextPage() {
    if(pageNum >= pdfDoc.numPages){
      return;
    }
    pageNum++;
    let scale = pdfDoc.scale;
    queueRenderPage(pageNum, scale);
  }
  document.getElementById('next').addEventListener('click', onNextPage);
</script>
