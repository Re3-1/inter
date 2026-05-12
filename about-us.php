<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsaid']) == 0) {
  header('location:logout.php');
} else {
  if(isset($_POST['submit'])) {
    $pagetitle = $_POST['pagetitle'];
    $pagedes = $_POST['pagedes'];
    
    // Validate inputs
    if(empty($pagetitle) || empty($pagedes)) {
      echo '<script>
        Swal.fire({
          icon: "error",
          title: "Error!",
          text: "All fields are required",
          showConfirmButton: true
        });
      </script>';
    } else {
      $sql = "UPDATE tblpage SET PageTitle=:pagetitle, PageDescription=:pagedes WHERE PageType='aboutus'";
      $query = $dbh->prepare($sql);
      $query->bindParam(':pagetitle', $pagetitle, PDO::PARAM_STR);
      $query->bindParam(':pagedes', $pagedes, PDO::PARAM_STR);
      
      if($query->execute()) {
        echo '<script>
          Swal.fire({
            icon: "success",
            title: "Success!",
            text: "About us page has been updated",
            showConfirmButton: true,
            timer: 3000
          });
        </script>';
      } else {
        echo '<script>
          Swal.fire({
            icon: "error",
            title: "Error!",
            text: "Failed to update about us page",
            showConfirmButton: true
          });
        </script>';
      }
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update About Us - <?php echo htmlspecialchars($siteName); ?></title>
  <!-- Tailwind CSS with Inter font -->
  <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <!-- Include CKEditor for rich text editing -->
  <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f9fafb;
    }
    .gradient-bg {
      background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
    }
    .card-hover {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card-hover:hover {
      transform: translateY(-3px);
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    .textarea-animate:focus {
      border-color: #7c3aed;
      box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
    }
    .ck-editor__editable {
      min-height: 300px;
    }
  </style>
</head>
<body class="bg-gray-50">
  <!-- Header -->
  <?php include_once('includes/header.php');?>
  
  <div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <?php include_once('includes/sidebar.php');?>
    
    <!-- Main Content -->
    <div class="flex-1 overflow-x-hidden overflow-y-auto">
      <div class="container mx-auto px-4 py-8">
        <!-- Animated Header -->
        <div class="animate__animated animate__fadeIn mb-8">
          <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4">
            <div>
              <h1 class="text-3xl font-bold text-gray-800">Update About Us</h1>
              <p class="text-gray-600 mt-1">Update your institution's about page content</p>
            </div>
            <nav class="flex mt-4 md:mt-0" aria-label="Breadcrumb">
              <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                  <a href="dashboard.php" class="inline-flex items-center text-sm font-medium text-purple-600 hover:text-purple-700">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                    Dashboard
                  </a>
                </li>
                <li aria-current="page">
                  <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">About Us</span>
                  </div>
                </li>
              </ol>
            </nav>
          </div>
          <div class="h-1 bg-gradient-to-r from-purple-500 to-indigo-500 rounded-full"></div>
        </div>

        <!-- Preview and Edit Tabs -->
        <div class="mb-6 animate__animated animate__fadeIn">
          <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8">
              <button id="edit-tab" class="border-purple-500 text-purple-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm focus:outline-none">
                Edit Content
              </button>
              <button id="preview-tab" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm focus:outline-none">
                Preview
              </button>
            </nav>
          </div>
        </div>

        <!-- Edit Content Section -->
        <div id="edit-section" class="animate__animated animate__fadeInUp">
          <div class="bg-white rounded-xl shadow-md overflow-hidden card-hover">
            <div class="gradient-bg px-6 py-4">
              <h2 class="text-xl font-semibold text-white text-center">Edit About Us Content</h2>
            </div>
            <div class="p-6">
              <form class="space-y-6" method="post" id="aboutForm">
                <?php
                $sql = "SELECT * from tblpage where PageType='aboutus'";
                $query = $dbh->prepare($sql);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);
                $cnt = 1;
                if($query->rowCount() > 0) {
                  foreach($results as $row) {
                ?>
                <div class="space-y-6">
                  <div>
                    <label for="pagetitle" class="block text-sm font-medium text-gray-700 mb-1">Page Title</label>
                    <input type="text" name="pagetitle" value="<?php echo htmlspecialchars($row->PageTitle); ?>" id="pagetitle" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-300" required maxlength="100">
                  </div>
                  
                  <div>
                    <label for="pagedes" class="block text-sm font-medium text-gray-700 mb-1">Page Description</label>
                    <textarea name="pagedes" id="pagedes" rows="6" class="w-full px-4 py-2 rounded-lg border border-gray-300 textarea-animate focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-300" required><?php echo htmlspecialchars($row->PageDescription); ?></textarea>
                  </div>
                  
                  <div class="flex justify-end space-x-3">
                    <button type="reset" class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition duration-300">
                      Reset
                    </button>
                    <button type="submit" name="submit" class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg shadow-md transition duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-opacity-50">
                      Update Content
                    </button>
                  </div>
                </div>
                <?php $cnt=$cnt+1;}} ?>
              </form>
            </div>
          </div>
        </div>

        <!-- Preview Section (Hidden by default) -->
        <div id="preview-section" class="hidden animate__animated animate__fadeInUp">
          <div class="bg-white rounded-xl shadow-md overflow-hidden card-hover">
            <div class="gradient-bg px-6 py-4">
              <h2 class="text-xl font-semibold text-white text-center">About Us Preview</h2>
            </div>
            <div class="p-6">
              <div class="prose max-w-none">
                <h1 id="preview-title" class="text-3xl font-bold text-gray-800 mb-4"><?php echo isset($row) ? htmlspecialchars($row->PageTitle) : 'About Us'; ?></h1>
                <div id="preview-content" class="text-gray-700">
                  <?php echo isset($row) ? nl2br(htmlspecialchars($row->PageDescription)) : 'Content will appear here...'; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <?php include_once('includes/footer.php');?>

  <script>
    // Initialize CKEditor
    CKEDITOR.replace('pagedes', {
      toolbar: [
        { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', '-', 'RemoveFormat'] },
        { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Blockquote'] },
        { name: 'links', items: ['Link', 'Unlink'] },
        { name: 'insert', items: ['Image', 'Table'] },
        { name: 'document', items: ['Source'] }
      ],
      height: 300
    });

    // Tab switching functionality
    const editTab = document.getElementById('edit-tab');
    const previewTab = document.getElementById('preview-tab');
    const editSection = document.getElementById('edit-section');
    const previewSection = document.getElementById('preview-section');

    previewTab.addEventListener('click', function() {
      // Update preview content before showing
      document.getElementById('preview-title').textContent = document.getElementById('pagetitle').value;
      document.getElementById('preview-content').innerHTML = CKEDITOR.instances.pagedes.getData();
      
      // Switch tabs
      editTab.classList.remove('border-purple-500', 'text-purple-600');
      editTab.classList.add('border-transparent', 'text-gray-500');
      previewTab.classList.remove('border-transparent', 'text-gray-500');
      previewTab.classList.add('border-purple-500', 'text-purple-600');
      
      editSection.classList.add('hidden');
      previewSection.classList.remove('hidden');
    });

    editTab.addEventListener('click', function() {
      editTab.classList.add('border-purple-500', 'text-purple-600');
      editTab.classList.remove('border-transparent', 'text-gray-500');
      previewTab.classList.add('border-transparent', 'text-gray-500');
      previewTab.classList.remove('border-purple-500', 'text-purple-600');
      
      editSection.classList.remove('hidden');
      previewSection.classList.add('hidden');
    });

    // Form validation
    document.getElementById('aboutForm').addEventListener('submit', function(e) {
      const title = document.getElementById('pagetitle').value.trim();
      const content = CKEDITOR.instances.pagedes.getData().trim();
      
      if(title === '' || content === '') {
        e.preventDefault();
        Swal.fire({
          icon: 'error',
          title: 'Validation Error',
          text: 'Both title and content are required',
          showConfirmButton: true
        });
      }
    });

    // Animation observer
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('animate__animated', 'animate__fadeInUp');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1 });

    document.querySelectorAll('.animate-on-scroll').forEach(el => {
      observer.observe(el);
    });
  </script>
</body>
</html>
<?php } ?>