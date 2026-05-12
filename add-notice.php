<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsaid']) == 0) {
  header('location:logout.php');
} else {
  $pageTitle = 'Add Notice';
  include_once('includes/header.php');
  
  if(isset($_POST['submit'])) {
    $nottitle = $_POST['nottitle'];
    $classid = $_POST['classid'];
    $notmsg = $_POST['notmsg'];
    
    $sql = "INSERT INTO tblnotice(NoticeTitle, ClassId, NoticeMsg) VALUES(:nottitle, :classid, :notmsg)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':nottitle', $nottitle, PDO::PARAM_STR);
    $query->bindParam(':classid', $classid, PDO::PARAM_STR);
    $query->bindParam(':notmsg', $notmsg, PDO::PARAM_STR);
    $query->execute();
    
    $LastInsertId = $dbh->lastInsertId();
    if ($LastInsertId > 0) {
      echo '<script>
        Swal.fire({
          title: "Success!",
          text: "Notice has been added successfully.",
          icon: "success",
          confirmButtonColor: "#4F46E5",
          confirmButtonText: "OK"
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = "add-notice.php";
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
          <li aria-current="page">
            <div class="flex items-center">
              <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
              <span class="text-sm font-medium text-primary">Add Notice</span>
            </div>
          </li>
        </ol>
      </nav>
    </div>
    <a href="manage-notice.php" class="mt-4 md:mt-0 inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
      <i class="fas fa-list mr-2"></i> View All Notices
    </a>
  </div>
  
  <!-- Form Card -->
  <div class="bg-white rounded-xl shadow-sm overflow-hidden" data-aos="fade-up">
    <div class="px-6 py-4 border-b">
      <h3 class="text-lg font-semibold text-gray-800">Notice Information</h3>
      <p class="text-sm text-gray-500 mt-1">Fill in the details to create a new notice</p>
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
              placeholder="Enter notice title"
              required
            >
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
              <i class="fas fa-heading text-gray-400"></i>
            </div>
          </div>
        </div>
        
        <!-- Class Selection Field -->
        <div>
          <label for="classid" class="block text-sm font-medium text-gray-700 mb-1">Notice For*</label>
          <div class="relative">
            <select 
              name="classid" 
              id="classid" 
              class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary appearance-none transition-all"
              required
            >
              <option value="" disabled selected>Select a class</option>
              <?php 
                $sql2 = "SELECT * FROM tblclass";
                $query2 = $dbh->prepare($sql2);
                $query2->execute();
                $result2 = $query2->fetchAll(PDO::FETCH_OBJ);
                
                foreach($result2 as $row1) {          
              ?>  
              <option value="<?php echo htmlentities($row1->ID); ?>">
                <?php echo htmlentities($row1->ClassName); ?> <?php echo htmlentities($row1->Section); ?>
              </option>
              <?php } ?> 
            </select>
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
              <i class="fas fa-users text-gray-400"></i>
            </div>
          </div>
        </div>
        
        <!-- Notice Message Field -->
        <div>
          <label for="notmsg" class="block text-sm font-medium text-gray-700 mb-1">Notice Message*</label>
          <textarea 
            name="notmsg" 
            id="notmsg" 
            rows="6"
            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary transition-all"
            placeholder="Enter detailed notice message here..."
            required
          ></textarea>
        </div>
        
        <!-- Form Actions -->
        <div class="flex justify-end space-x-3 pt-4">
          <button 
            type="reset" 
            class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
          >
            <i class="fas fa-redo mr-2"></i> Reset
          </button>
          <button 
            type="submit" 
            name="submit" 
            class="px-6 py-2.5 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors flex items-center"
          >
            <i class="fas fa-bell mr-2"></i> Publish Notice
          </button>
        </div>
      </div>
    </form>
  </div>
  
  <!-- Recent Notices Preview -->
  <div class="bg-white rounded-xl shadow-sm overflow-hidden mt-6" data-aos="fade-up">
    <div class="px-6 py-4 border-b">
      <h3 class="text-lg font-semibold text-gray-800">Recent Notices</h3>
      <p class="text-sm text-gray-500 mt-1">Preview of recently added notices</p>
    </div>
    <div class="divide-y">
      <?php
      $sql = "SELECT n.*, c.ClassName, c.Section FROM tblnotice n JOIN tblclass c ON n.ClassId = c.ID ORDER BY n.ID DESC LIMIT 3";
      $query = $dbh->prepare($sql);
      $query->execute();
      $results = $query->fetchAll(PDO::FETCH_OBJ);
      
      if($query->rowCount() > 0) {
        foreach($results as $notice) {
      ?>
      <div class="p-4 hover:bg-gray-50 transition-colors">
        <div class="flex items-start">
          <div class="p-3 rounded-full bg-indigo-50 text-indigo-600 mr-4">
            <i class="fas fa-bell"></i>
          </div>
          <div class="flex-1">
            <h4 class="font-medium text-gray-800"><?php echo htmlentities($notice->NoticeTitle); ?></h4>
            <div class="flex items-center mt-1 text-xs text-gray-500">
              <i class="fas fa-users mr-1"></i>
              <span><?php echo htmlentities($notice->ClassName); ?> <?php echo htmlentities($notice->Section); ?></span>
              <span class="mx-2">•</span>
              <i class="fas fa-calendar-alt mr-1"></i>
              <span><?php echo date('M j, Y', strtotime($notice->CreationDate)); ?></span>
            </div>
            <p class="text-sm text-gray-600 mt-2 line-clamp-2"><?php echo htmlentities($notice->NoticeMsg); ?></p>
          </div>
          <a href="manage-notice.php" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
            View
          </a>
        </div>
      </div>
      <?php }} else { ?>
      <div class="p-6 text-center text-gray-500">
        <i class="fas fa-info-circle text-2xl mb-2"></i>
        <p>No recent notices found</p>
      </div>
      <?php } ?>
    </div>
    <div class="px-6 py-3 border-t text-right">
      <a href="manage-notice.php" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
        View all notices <i class="fas fa-arrow-right ml-1"></i>
      </a>
    </div>
  </div>
</div>

<!-- Include SweetAlert2 for beautiful alerts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php 
  include_once('includes/footer.php');
}
?>