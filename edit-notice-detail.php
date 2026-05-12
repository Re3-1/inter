<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsaid']) == 0) {
  header('location:logout.php');
} else {
  $pageTitle = 'Update Notice';
  include_once('includes/header.php');
  
  if(isset($_POST['submit'])) {
    $nottitle = $_POST['nottitle'];
    $classid = $_POST['classid'];
    $notmsg = $_POST['notmsg'];
    $eid = $_GET['editid'];
    
    $sql = "UPDATE tblnotice SET NoticeTitle=:nottitle, ClassId=:classid, NoticeMsg=:notmsg WHERE ID=:eid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':nottitle', $nottitle, PDO::PARAM_STR);
    $query->bindParam(':classid', $classid, PDO::PARAM_STR);
    $query->bindParam(':notmsg', $notmsg, PDO::PARAM_STR);
    $query->bindParam(':eid', $eid, PDO::PARAM_STR);
    
    if($query->execute()) {
      echo '<script>
        Swal.fire({
          icon: "success",
          title: "Success!",
          text: "Notice has been updated successfully",
          showConfirmButton: true,
          timer: 3000
        });
      </script>';
    } else {
      echo '<script>
        Swal.fire({
          icon: "error",
          title: "Error!",
          text: "Failed to update notice",
          showConfirmButton: true
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
          <li class="inline-flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
            <a href="manage-notice.php" class="text-sm font-medium text-gray-500 hover:text-primary">
              Manage Notices
            </a>
          </li>
          <li aria-current="page">
            <div class="flex items-center">
              <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
              <span class="text-sm font-medium text-primary">Update Notice</span>
            </div>
          </li>
        </ol>
      </nav>
    </div>
    <a href="manage-notice.php" class="mt-4 md:mt-0 inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
      <i class="fas fa-list mr-2"></i> View All Notices
    </a>
  </div>
  
  <!-- Notice Update Card -->
  <div class="bg-white rounded-xl shadow-sm overflow-hidden max-w-4xl mx-auto" data-aos="fade-up">
    <div class="px-6 py-4 border-b">
      <div class="flex items-center">
        <div class="p-2 bg-blue-100 text-blue-600 rounded-lg mr-4">
          <i class="fas fa-bullhorn text-xl"></i>
        </div>
        <div>
          <h3 class="text-lg font-semibold text-gray-800">Notice Information</h3>
          <p class="text-sm text-gray-500">Update notice details for students and staff</p>
        </div>
      </div>
    </div>
    
    <?php
    $eid = $_GET['editid'];
    $sql = "SELECT tblclass.ID, tblclass.ClassName, tblclass.Section, tblnotice.NoticeTitle, 
            tblnotice.CreationDate, tblnotice.ClassId, tblnotice.NoticeMsg, tblnotice.ID as nid 
            FROM tblnotice JOIN tblclass ON tblclass.ID=tblnotice.ClassId WHERE tblnotice.ID=:eid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':eid', $eid, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    
    if($query->rowCount() > 0) {
      foreach($results as $row) {
    ?>
    <form class="p-6" method="post" onsubmit="return validateNoticeForm()">
      <!-- Notice Title -->
      <div class="mb-6">
        <label for="nottitle" class="block text-sm font-medium text-gray-700 mb-1">Notice Title*</label>
        <input 
          type="text" 
          id="nottitle" 
          name="nottitle" 
          value="<?php echo htmlspecialchars($row->NoticeTitle); ?>" 
          class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary transition-all"
          required
          maxlength="100"
          placeholder="Enter notice title"
        >
        <p class="mt-1 text-xs text-gray-500">Maximum 100 characters</p>
      </div>
      
      <!-- Notice For (Class Selection) -->
      <div class="mb-6">
        <label for="classid" class="block text-sm font-medium text-gray-700 mb-1">Notice For*</label>
        <select 
          id="classid" 
          name="classid" 
          class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary transition-all appearance-none"
          required
        >
          <option value="<?php echo htmlspecialchars($row->ClassId); ?>" selected>
            <?php echo htmlspecialchars($row->ClassName) . ' ' . htmlspecialchars($row->Section); ?>
          </option>
          <?php 
          $sql2 = "SELECT * FROM tblclass";
          $query2 = $dbh->prepare($sql2);
          $query2->execute();
          $result2 = $query2->fetchAll(PDO::FETCH_OBJ);
          
          foreach($result2 as $row1) {
            if($row1->ID != $row->ClassId) {
              echo '<option value="' . htmlspecialchars($row1->ID) . '">' . 
                   htmlspecialchars($row1->ClassName) . ' ' . htmlspecialchars($row1->Section) . '</option>';
            }
          } 
          ?>
        </select>
      </div>
      
      <!-- Notice Message -->
      <div class="mb-6">
        <label for="notmsg" class="block text-sm font-medium text-gray-700 mb-1">Notice Message*</label>
        <textarea 
          id="notmsg" 
          name="notmsg" 
          rows="6" 
          class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary transition-all"
          required
          placeholder="Enter detailed notice message"
        ><?php echo htmlspecialchars($row->NoticeMsg); ?></textarea>
        <p class="mt-1 text-xs text-gray-500">Provide clear and concise information</p>
      </div>
      
      <!-- Creation Date (Readonly) -->
      <div class="mb-6 bg-gray-50 p-4 rounded-lg">
        <label class="block text-sm font-medium text-gray-700 mb-1">Original Post Date</label>
        <div class="flex items-center text-gray-600">
          <i class="fas fa-calendar-alt mr-2"></i>
          <span><?php echo date('F j, Y, g:i a', strtotime($row->CreationDate)); ?></span>
        </div>
      </div>
      
      <!-- Form Actions -->
      <div class="flex justify-end space-x-3 pt-6 mt-4 border-t">
        <a 
          href="manage-notice.php" 
          class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
        >
          <i class="fas fa-times mr-2"></i> Cancel
        </a>
        <button 
          type="submit" 
          name="submit" 
          class="px-6 py-2.5 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors flex items-center"
        >
          <i class="fas fa-save mr-2"></i> Update Notice
        </button>
      </div>
    </form>
    <?php }} ?>
  </div>
</div>

<script>
  // Form validation
  function validateNoticeForm() {
    const nottitle = document.getElementById('nottitle').value.trim();
    const notmsg = document.getElementById('notmsg').value.trim();
    
    if (nottitle.length === 0) {
      Swal.fire({
        icon: 'error',
        title: 'Title Required',
        text: 'Please enter a notice title',
        showConfirmButton: true
      });
      return false;
    }
    
    if (nottitle.length > 100) {
      Swal.fire({
        icon: 'error',
        title: 'Title Too Long',
        text: 'Notice title must be 100 characters or less',
        showConfirmButton: true
      });
      return false;
    }
    
    if (notmsg.length === 0) {
      Swal.fire({
        icon: 'error',
        title: 'Message Required',
        text: 'Please enter a notice message',
        showConfirmButton: true
      });
      return false;
    }
    
    return true;
  }
</script>

<?php 
  include_once('includes/footer.php');
}
?>