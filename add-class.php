<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsaid']) == 0) {
  header('location:logout.php');
} else {
  $pageTitle = 'Add Class';
  include_once('includes/header.php');
  
  if(isset($_POST['submit'])) {
    $cname = $_POST['cname'];
    $section = $_POST['section'];
    
    $sql = "INSERT INTO tblclass(ClassName, Section) VALUES(:cname, :section)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':cname', $cname, PDO::PARAM_STR);
    $query->bindParam(':section', $section, PDO::PARAM_STR);
    $query->execute();
    
    $LastInsertId = $dbh->lastInsertId();
    if ($LastInsertId > 0) {
      echo '<script>
        Swal.fire({
          title: "Success!",
          text: "Class has been added successfully.",
          icon: "success",
          confirmButtonColor: "#4F46E5",
          confirmButtonText: "OK"
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = "add-class.php";
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
              <span class="text-sm font-medium text-primary">Add Class</span>
            </div>
          </li>
        </ol>
      </nav>
    </div>
    <a href="manage-class.php" class="mt-4 md:mt-0 inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
      <i class="fas fa-list mr-2"></i> View All Classes
    </a>
  </div>
  
  <!-- Form Card -->
  <div class="bg-white rounded-xl shadow-sm overflow-hidden" data-aos="fade-up">
    <div class="px-6 py-4 border-b">
      <h3 class="text-lg font-semibold text-gray-800">Class Information</h3>
      <p class="text-sm text-gray-500 mt-1">Fill in the details to add a new class</p>
    </div>
    
    <form class="p-6" method="post">
      <div class="grid grid-cols-1 gap-6">
        <!-- Class Name Field -->
        <div>
          <label for="cname" class="block text-sm font-medium text-gray-700 mb-1">Class Name</label>
          <div class="relative">
            <input 
              type="text" 
              name="cname" 
              id="cname" 
              class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary transition-all"
              placeholder="e.g. Grade 10, Class 12, etc."
              required
            >
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
              <i class="fas fa-chalkboard text-gray-400"></i>
            </div>
          </div>
          <p class="mt-1 text-xs text-gray-500">Enter the full name of the class</p>
        </div>
        
        <!-- Section Field -->
        <div>
          <label for="section" class="block text-sm font-medium text-gray-700 mb-1">Section</label>
          <div class="relative">
            <select 
              name="section" 
              id="section" 
              class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary appearance-none transition-all"
              required
            >
              <option value="" disabled selected>Select a section</option>
              <option value="A">Section A</option>
              <option value="B">Section B</option>
              <option value="C">Section C</option>
              <option value="D">Section D</option>
              <option value="E">Section E</option>
              <option value="F">Section F</option>
            </select>
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
              <i class="fas fa-chevron-down text-gray-400"></i>
            </div>
          </div>
          <p class="mt-1 text-xs text-gray-500">Select the section for this class</p>
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
            <i class="fas fa-plus-circle mr-2"></i> Add Class
          </button>
        </div>
      </div>
    </form>
  </div>
  
  <!-- Quick Stats -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
    <div class="bg-white rounded-xl shadow-sm p-6" data-aos="fade-up" data-aos-delay="100">
      <div class="flex items-center">
        <div class="p-3 rounded-full bg-indigo-50 text-indigo-600 mr-4">
          <i class="fas fa-layer-group text-xl"></i>
        </div>
        <div>
          <p class="text-sm font-medium text-gray-500">Total Classes</p>
          <?php
          $sql = "SELECT * FROM tblclass";
          $query = $dbh->prepare($sql);
          $query->execute();
          $totalClasses = $query->rowCount();
          ?>
          <h3 class="text-2xl font-bold mt-1"><?php echo $totalClasses; ?></h3>
        </div>
      </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6" data-aos="fade-up" data-aos-delay="200">
      <div class="flex items-center">
        <div class="p-3 rounded-full bg-green-50 text-green-600 mr-4">
          <i class="fas fa-users text-xl"></i>
        </div>
        <div>
          <p class="text-sm font-medium text-gray-500">Total Students</p>
          <?php
          $sql = "SELECT * FROM tblstudent";
          $query = $dbh->prepare($sql);
          $query->execute();
          $totalStudents = $query->rowCount();
          ?>
          <h3 class="text-2xl font-bold mt-1"><?php echo $totalStudents; ?></h3>
        </div>
      </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6" data-aos="fade-up" data-aos-delay="300">
      <div class="flex items-center">
        <div class="p-3 rounded-full bg-blue-50 text-blue-600 mr-4">
          <i class="fas fa-bell text-xl"></i>
        </div>
        <div>
          <p class="text-sm font-medium text-gray-500">Active Notices</p>
          <?php
          $sql = "SELECT * FROM tblnotice";
          $query = $dbh->prepare($sql);
          $query->execute();
          $totalNotices = $query->rowCount();
          ?>
          <h3 class="text-2xl font-bold mt-1"><?php echo $totalNotices; ?></h3>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Include SweetAlert2 for beautiful alerts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php 
  include_once('includes/footer.php');
}
?>