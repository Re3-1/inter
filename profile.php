<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsaid']) == 0) {
  header('location:logout.php');
} else {
  $pageTitle = 'Admin Profile';
  include_once('includes/header.php');
  
  if(isset($_POST['submit'])) {
    $adminid = $_SESSION['sturecmsaid'];
    $AName = $_POST['adminname'];
    $mobno = $_POST['mobilenumber'];
    $email = $_POST['email'];
    
    $sql = "UPDATE tbladmin SET AdminName=:adminname, MobileNumber=:mobilenumber, Email=:email WHERE ID=:aid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':adminname', $AName, PDO::PARAM_STR);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':mobilenumber', $mobno, PDO::PARAM_STR);
    $query->bindParam(':aid', $adminid, PDO::PARAM_STR);
    
    if($query->execute()) {
      echo '<script>
        Swal.fire({
          icon: "success",
          title: "Profile Updated!",
          text: "Your profile has been updated successfully",
          showConfirmButton: true,
          timer: 3000
        }).then(() => {
          window.location.href = "profile.php";
        });
      </script>';
    } else {
      echo '<script>
        Swal.fire({
          icon: "error",
          title: "Update Failed",
          text: "Failed to update your profile",
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
          <li aria-current="page">
            <div class="flex items-center">
              <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
              <span class="text-sm font-medium text-primary">Admin Profile</span>
            </div>
          </li>
        </ol>
      </nav>
    </div>
    <a href="change-password.php" class="mt-4 md:mt-0 inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
      <i class="fas fa-lock mr-2"></i> Change Password
    </a>
  </div>
  
  <!-- Profile Card -->
  <div class="bg-white rounded-xl shadow-sm overflow-hidden max-w-3xl mx-auto" data-aos="fade-up">
    <div class="px-6 py-4 border-b">
      <div class="flex items-center">
        <div class="p-2 bg-purple-100 text-purple-600 rounded-lg mr-4">
          <i class="fas fa-user-cog text-xl"></i>
        </div>
        <div>
          <h3 class="text-lg font-semibold text-gray-800">Profile Information</h3>
          <p class="text-sm text-gray-500">Update your admin profile details</p>
        </div>
      </div>
    </div>
    
    <?php
    $sql = "SELECT * FROM tbladmin";
    $query = $dbh->prepare($sql);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    
    if($query->rowCount() > 0) {
      foreach($results as $row) {
    ?>
    <form class="p-6" method="post" onsubmit="return validateProfileForm()">
      <!-- Admin Name -->
      <div class="mb-6">
        <label for="adminname" class="block text-sm font-medium text-gray-700 mb-1">Admin Name*</label>
        <input 
          type="text" 
          id="adminname" 
          name="adminname" 
          value="<?php echo htmlspecialchars($row->AdminName); ?>" 
          class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary transition-all"
          required
          maxlength="50"
          placeholder="Enter your full name"
        >
      </div>
      
      <!-- Username (readonly) -->
      <div class="mb-6">
        <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
        <div class="relative">
          <input 
            type="text" 
            id="username" 
            name="username" 
            value="<?php echo htmlspecialchars($row->UserName); ?>" 
            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-gray-100 cursor-not-allowed"
            readonly
          >
          <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
            <i class="fas fa-user text-gray-400"></i>
          </div>
        </div>
      </div>
      
      <!-- Mobile Number -->
      <div class="mb-6">
        <label for="mobilenumber" class="block text-sm font-medium text-gray-700 mb-1">Contact Number*</label>
        <div class="relative">
          <input 
            type="tel" 
            id="mobilenumber" 
            name="mobilenumber" 
            value="<?php echo htmlspecialchars($row->MobileNumber); ?>" 
            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary transition-all"
            required
            pattern="[0-9]{10}"
            maxlength="10"
            minlength="10"
            placeholder="Enter 10-digit mobile number"
          >
          <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
            <i class="fas fa-phone text-gray-400"></i>
          </div>
        </div>
        <p class="mt-1 text-xs text-gray-500">10-digit number without spaces or special characters</p>
      </div>
      
      <!-- Email -->
      <div class="mb-6">
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address*</label>
        <div class="relative">
          <input 
            type="email" 
            id="email" 
            name="email" 
            value="<?php echo htmlspecialchars($row->Email); ?>" 
            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary transition-all"
            required
            pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
            placeholder="Enter your email address"
          >
          <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
            <i class="fas fa-envelope text-gray-400"></i>
          </div>
        </div>
        <p class="mt-1 text-xs text-gray-500">Must be a valid email address</p>
      </div>
      
      <!-- Registration Date (readonly) -->
      <div class="mb-6">
        <label for="regdate" class="block text-sm font-medium text-gray-700 mb-1">Registration Date</label>
        <div class="relative">
          <input 
            type="text" 
            id="regdate" 
            value="<?php echo date('F j, Y', strtotime($row->AdminRegdate)); ?>" 
            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-gray-100 cursor-not-allowed"
            readonly
          >
          <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
            <i class="fas fa-calendar-alt text-gray-400"></i>
          </div>
        </div>
      </div>
      
      <!-- Form Actions -->
      <div class="flex justify-end space-x-3 pt-6 mt-4 border-t">
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
          <i class="fas fa-save mr-2"></i> Update Profile
        </button>
      </div>
    </form>
    <?php }} ?>
  </div>
</div>

<script>
  // Form validation
  function validateProfileForm() {
    const adminName = document.getElementById('adminname').value.trim();
    const mobileNumber = document.getElementById('mobilenumber').value;
    const email = document.getElementById('email').value;
    
    // Name validation
    if (adminName.length === 0) {
      Swal.fire({
        icon: 'error',
        title: 'Name Required',
        text: 'Please enter your name',
        showConfirmButton: true
      });
      return false;
    }
    
    // Mobile validation
    if (!/^\d{10}$/.test(mobileNumber)) {
      Swal.fire({
        icon: 'error',
        title: 'Invalid Phone Number',
        text: 'Please enter a 10-digit mobile number',
        showConfirmButton: true
      });
      return false;
    }
    
    // Email validation
    if (!/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/i.test(email)) {
      Swal.fire({
        icon: 'error',
        title: 'Invalid Email',
        text: 'Please enter a valid email address',
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