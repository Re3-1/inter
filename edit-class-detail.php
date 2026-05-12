<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsaid']) == 0) {
  header('location:logout.php');
} else {
  $pageTitle = 'Update Class';
  include_once('includes/header.php');
  
  if(isset($_POST['submit'])) {
    $cname = $_POST['cname'];
    $section = $_POST['section'];
    $eid = $_GET['editid'];

    $sql = "UPDATE tblclass SET ClassName=:cname, Section=:section WHERE ID=:eid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':cname', $cname, PDO::PARAM_STR);
    $query->bindParam(':section', $section, PDO::PARAM_STR);
    $query->bindParam(':eid', $eid, PDO::PARAM_STR);
    
    if($query->execute()) {
      echo '<script>
        Swal.fire({
          icon: "success",
          title: "Success!",
          text: "Class has been updated successfully",
          showConfirmButton: true,
          timer: 3000
        });
      </script>';
    } else {
      echo '<script>
        Swal.fire({
          icon: "error",
          title: "Error!",
          text: "Failed to update class information",
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
            <a href="manage-class.php" class="text-sm font-medium text-gray-500 hover:text-primary">
              Manage Classes
            </a>
          </li>
          <li aria-current="page">
            <div class="flex items-center">
              <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
              <span class="text-sm font-medium text-primary">Update Class</span>
            </div>
          </li>
        </ol>
      </nav>
    </div>
    <a href="manage-class.php" class="mt-4 md:mt-0 inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
      <i class="fas fa-list mr-2"></i> View All Classes
    </a>
  </div>
  
  <!-- Class Update Card -->
  <div class="bg-white rounded-xl shadow-sm overflow-hidden max-w-3xl mx-auto" data-aos="fade-up">
    <div class="px-6 py-4 border-b">
      <div class="flex items-center">
        <div class="p-2 bg-indigo-100 text-indigo-600 rounded-lg mr-4">
          <i class="fas fa-chalkboard-teacher text-xl"></i>
        </div>
        <div>
          <h3 class="text-lg font-semibold text-gray-800">Class Details</h3>
          <p class="text-sm text-gray-500">Update class information and section</p>
        </div>
      </div>
    </div>
    
    <?php
    $eid = $_GET['editid'];
    $sql = "SELECT * FROM tblclass WHERE ID=:eid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':eid', $eid, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    
    if($query->rowCount() > 0) {
      foreach($results as $row) {
    ?>
    <form class="p-6" method="post">
      <!-- Class Name -->
      <div class="mb-6">
        <label for="cname" class="block text-sm font-medium text-gray-700 mb-1">Class Name*</label>
        <input 
          type="text" 
          id="cname" 
          name="cname" 
          value="<?php echo htmlspecialchars($row->ClassName); ?>" 
          class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary transition-all"
          required
          maxlength="50"
          placeholder="Enter class name (e.g., Grade 10)"
        >
        <p class="mt-1 text-xs text-gray-500">Maximum 50 characters</p>
      </div>
      
      <!-- Section -->
      <div class="mb-6">
        <label for="section" class="block text-sm font-medium text-gray-700 mb-1">Section*</label>
        <select 
          id="section" 
          name="section" 
          class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary transition-all appearance-none"
          required
        >
          <option value="<?php echo htmlspecialchars($row->Section); ?>" selected><?php echo htmlspecialchars($row->Section); ?></option>
          <option value="A">A</option>
          <option value="B">B</option>
          <option value="C">C</option>
          <option value="D">D</option>
          <option value="E">E</option>
          <option value="F">F</option>
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
          <i class="fas fa-chevron-down"></i>
        </div>
      </div>
      
      <!-- Form Actions -->
      <div class="flex justify-end space-x-3 pt-6 mt-4 border-t">
        <a 
          href="manage-class.php" 
          class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
        >
          <i class="fas fa-times mr-2"></i> Cancel
        </a>
        <button 
          type="submit" 
          name="submit" 
          class="px-6 py-2.5 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors flex items-center"
        >
          <i class="fas fa-save mr-2"></i> Update Class
        </button>
      </div>
    </form>
    <?php }} ?>
  </div>
</div>

<script>
  // Form validation
  document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    
    form.addEventListener('submit', function(e) {
      const className = document.getElementById('cname').value.trim();
      const section = document.getElementById('section').value;
      
      if (className.length === 0) {
        e.preventDefault();
        Swal.fire({
          icon: 'error',
          title: 'Class Name Required',
          text: 'Please enter a valid class name',
          showConfirmButton: true
        });
        return false;
      }
      
      if (className.length > 50) {
        e.preventDefault();
        Swal.fire({
          icon: 'error',
          title: 'Class Name Too Long',
          text: 'Class name must be 50 characters or less',
          showConfirmButton: true
        });
        return false;
      }
      
      if (!['A', 'B', 'C', 'D', 'E', 'F'].includes(section)) {
        e.preventDefault();
        Swal.fire({
          icon: 'error',
          title: 'Invalid Section',
          text: 'Please select a valid section from the dropdown',
          showConfirmButton: true
        });
        return false;
      }
      
      return true;
    });
  });
</script>

<?php 
  include_once('includes/footer.php');
}
?>