<?php
// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
    // should do a check here to match $_SERVER['HTTP_ORIGIN'] to a
    // whitelist of safe domains
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}
// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");         

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

}

// if(!isset($_GET['pdf_file']) || empty($_GET['pdf_file'])){
//       header("Location: error.php");
//       exit();
//     }else{
//       $pdf_file = $_GET['pdf_file'];
//     }

include('includes/header.php');
$qry = $conn->query("SELECT * FROM projects where project_id=".$_GET['project_id'])->fetch_array();

extract($_POST);

$fname=$qry['file_path'];   
$pdf_file = ("assets/uploads/".$fname);

// echo "<script>alert('.$pdf_file.');</script>";
?>

<canvas id="the-canvas"></canvas>

<div class="pagination">
    <div class="wrap" style="position: fixed; padding: 20px; bottom: 10px; width: 100%; text-align: center;">
        <button class="navigate" id="prev">Previous</button>
        <button class="navigate" id="next">Next</button>
        &nbsp; &nbsp;
        <span style="background-color: #e7e7e7; padding: 10px 20px; text-align: center;">Page: <span id="page_num"></span> / <span id="page_count"></span></span>
    </div>
</div>

<style>
body{
    background: black;
    margin:0px;
    }
.pagination{
    background: #ffffff;
    width: 100%;
    float: left;
}
.pagination .wrap{
    float:right;
    width: 300px;
} 
.navigate {
  background-color: #e7e7e7; 
  border: none;
  color: red;
  padding: 10px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
}

#the-canvas {
    border: 1px solid black;
    direction: ltr;
    margin: 0 auto;
    display: block;
}
@media print {
  body {display:none;}
}
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
// Disable Canvas Image Downloading    
document.addEventListener('contextmenu', event => event.preventDefault());

// If absolute URL from the remote server is provided, configure the CORS
// header on that server.
var url = '<?php echo $pdf_file;?>'; // your file location and file name with ext.


// Loaded via <script> tag, create shortcut to access PDF.js exports.
var pdfjsLib = window['pdfjs-dist/build/pdf'];

// The workerSrc property shall be specified.
pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

var pdfDoc = null,
    pageNum = 1,
    pageRendering = false,
    pageNumPending = null,
    scale = 2,
    canvas = document.getElementById('the-canvas'),
    ctx = canvas.getContext('2d');

/**
 * Get page info from document, resize canvas accordingly, and render page.
 * @param num Page number.
 */
function renderPage(num) {
  pageRendering = true;
  // Using promise to fetch the page
  pdfDoc.getPage(num).then(function(page) {
    var viewport = page.getViewport({scale: scale});
    canvas.height = viewport.height;
    canvas.width = viewport.width;

    // Render PDF page into canvas context
    var renderContext = {
      canvasContext: ctx,
      viewport: viewport
    };
    var renderTask = page.render(renderContext);

    // Wait for rendering to finish
    renderTask.promise.then(function() {
      pageRendering = false;
      if (pageNumPending !== null) {
        // New page rendering is pending
        renderPage(pageNumPending);
        pageNumPending = null;
      }
    });
  });

  // Update page counters
  document.getElementById('page_num').textContent = num;
}

/**
 * If another page rendering in progress, waits until the rendering is
 * finised. Otherwise, executes rendering immediately.
 */
function queueRenderPage(num) {
  if (pageRendering) {
    pageNumPending = num;
  } else {
    renderPage(num);
  }
}

/**
 * Displays previous page.
 */
function onPrevPage() {
  if (pageNum <= 1) {
    return;
  }
  pageNum--;
  queueRenderPage(pageNum);
}
document.getElementById('prev').addEventListener('click', onPrevPage);

/**
 * Displays next page.
 */
function onNextPage() {
  if (pageNum >= pdfDoc.numPages) {
    return;
  }
  pageNum++;
  queueRenderPage(pageNum);
}
document.getElementById('next').addEventListener('click', onNextPage);

/**
 * Asynchronously downloads PDF.
 */
pdfjsLib.getDocument(url).promise.then(function(pdfDoc_) {
  pdfDoc = pdfDoc_;
  document.getElementById('page_count').textContent = pdfDoc.numPages;

  // Initial/first page rendering
  renderPage(pageNum);
});
</script>