<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsaid']) == 0) {
  header('location:logout.php');
} else {
  $pageTitle = 'Add Public Notice';
  include_once('includes/header.php');
  
  if(isset($_POST['submit'])) {
    $nottitle = $_POST['nottitle'];
    $notmsg = $_POST['notmsg'];
    
    $sql = "INSERT INTO tblpublicnotice(NoticeTitle, NoticeMessage) VALUES(:nottitle, :notmsg)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':nottitle', $nottitle, PDO::PARAM_STR);
    $query->bindParam(':notmsg', $notmsg, PDO::PARAM_STR);
    $query->execute();
    
    $LastInsertId = $dbh->lastInsertId();
    if ($LastInsertId > 0) {
      echo '<script>
        Swal.fire({
          title: "Published!",
          text: "Public notice has been published successfully.",
          icon: "success",
          confirmButtonColor: "#4F46E5",
          confirmButtonText: "OK"
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = "add-public-notice.php";
          }
        });
      </script>';
    } else {
      echo '<script>
        Swal.fire({
          title: "Error!",
          text: "Something went wrong. Please try again.",
          icon: "error",
          confirmButtonColor: "#4F46E5",
          confirmButtonText: "OK"
        });
      </script>';
    }
  }
?>

<div class="px-6 py-4">
  <!-- Page Header -->
  <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6" data-aos="fade-down">
    <div>
      <nav class="flex mt-2" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2">
          <li class="inline-flex items-center">
            <a href="dashboard.php" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-primary">
              <i class="fas fa-home mr-2"></i>
              Dashboard
            </a>
          </li>
          <li>
            <div class="flex items-center">
              <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
              <span class="text-sm font-medium text-gray-500">Notices</span>
            </div>
          </li>
          <li aria-current="page">
            <div class="flex items-center">
              <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
              <span class="text-sm font-medium text-primary">Add Public Notice</span>
            </div>
          </li>
        </ol>
      </nav>
    </div>
    <a href="manage-public-notice.php" class="mt-4 md:mt-0 inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
      <i class="fas fa-bullhorn mr-2"></i> View Public Notices
    </a>
  </div>
  
  <!-- Form Card -->
  <div class="bg-white rounded-xl shadow-sm overflow-hidden" data-aos="fade-up">
    <div class="px-6 py-4 border-b">
      <div class="flex items-center">
        <div class="p-2 bg-purple-100 text-purple-600 rounded-lg mr-4">
          <i class="fas fa-bullhorn text-xl"></i>
        </div>
        <div>
          <h3 class="text-lg font-semibold text-gray-800">Public Notice Details</h3>
          <p class="text-sm text-gray-500">This notice will be visible to all users</p>
        </div>
      </div>
    </div>
    
    <form class="p-6" method="post">
      <div class="grid grid-cols-1 gap-6">
        <!-- Notice Title Field -->
        <div>
          <label for="nottitle" class="block text-sm font-medium text-gray-700 mb-1">Notice Title*</label>
          <div class="relative">
            <input 
              type="text" 
              name="nottitle" 
              id="nottitle" 
              class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary transition-all"
              placeholder="Enter notice title (e.g., School Closure, Holiday Announcement)"
              required
            >
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
              <i class="fas fa-heading text-gray-400"></i>
            </div>
          </div>
        </div>
        
        <!-- Notice Message Field -->
        <div>
          <label for="notmsg" class="block text-sm font-medium text-gray-700 mb-1">Notice Content*</label>
          <textarea 
            name="notmsg" 
            id="notmsg" 
            rows="8"
            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary transition-all"
            placeholder="Enter detailed notice content here..."
            required
          ></textarea>
          <div class="mt-1 flex justify-between items-center text-xs text-gray-500">
            <div>
              <i class="fas fa-info-circle mr-1"></i> This notice will be visible to everyone
            </div>
            <div id="charCount">0 characters</div>
          </div>
        </div>
        
        <!-- Form Actions -->
        <div class="flex justify-end space-x-3 pt-4">
          <button 
            type="reset" 
            class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
          >
            <i class="fas fa-eraser mr-2"></i> Clear Form
          </button>
          <button 
            type="submit" 
            name="submit" 
            class="px-6 py-2.5 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors flex items-center"
          >
            <i class="fas fa-paper-plane mr-2"></i> Publish Notice
          </button>
        </div>
      </div>
    </form>
  </div>
  
  <!-- Recent Public Notices Preview -->
  <div class="bg-white rounded-xl shadow-sm overflow-hidden mt-6" data-aos="fade-up" data-aos-delay="100">
    <div class="px-6 py-4 border-b">
      <div class="flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-800">Recent Public Notices</h3>
        <span class="text-sm text-gray-500">Last 3 published</span>
      </div>
    </div>
    <div class="divide-y">
      <?php
      $sql = "SELECT * FROM tblpublicnotice ORDER BY ID DESC LIMIT 3";
      $query = $dbh->prepare($sql);
      $query->execute();
      $results = $query->fetchAll(PDO::FETCH_OBJ);
      
      if($query->rowCount() > 0) {
        foreach($results as $notice) {
      ?>
      <div class="p-4 hover:bg-gray-50 transition-colors">
        <div class="flex items-start">
          <div class="p-3 rounded-full bg-purple-50 text-purple-600 mr-4">
            <i class="fas fa-bullhorn"></i>
          </div>
          <div class="flex-1">
            <h4 class="font-medium text-gray-800"><?php echo htmlentities($notice->NoticeTitle); ?></h4>
            <div class="flex items-center mt-1 text-xs text-gray-500">
              <i class="fas fa-calendar-alt mr-1"></i>
              <span><?php echo date('M j, Y', strtotime($notice->CreationDate)); ?></span>
            </div>
            <p class="text-sm text-gray-600 mt-2 line-clamp-2"><?php echo htmlentities($notice->NoticeMessage); ?></p>
          </div>
          <a href="manage-public-notice.php" class="text-purple-600 hover:text-purple-800 text-sm font-medium">
            View
          </a>
        </div>
      </div>
      <?php }} else { ?>
      <div class="p-6 text-center text-gray-500">
        <i class="fas fa-info-circle text-2xl mb-2"></i>
        <p>No public notices found</p>
      </div>
      <?php } ?>
    </div>
    <div class="px-6 py-3 border-t text-right">
      <a href="manage-public-notice.php" class="text-sm font-medium text-purple-600 hover:text-purple-800">
        View all public notices <i class="fas fa-arrow-right ml-1"></i>
      </a>
    </div>
  </div>
</div>

<!-- Include SweetAlert2 for beautiful alerts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Character counter script -->
<script>
  // Character counter for notice content
  const notmsg = document.getElementById('notmsg');
  const charCount = document.getElementById('charCount');
  
  notmsg.addEventListener('input', function() {
    const length = this.value.length;
    charCount.textContent = `${length} characters`;
    
    if (length > 1000) {
      charCount.classList.add('text-red-500');
    } else {
      charCount.classList.remove('text-red-500');
    }
  });
  
  // Initialize rich text editor if needed
  // tinymce.init({ selector: '#notmsg' });
</script>

<?php 
  include_once('includes/footer.php');
}
?>