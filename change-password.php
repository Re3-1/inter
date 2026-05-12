<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['sturecmsaid']) == 0) {
  header('location:logout.php');
} else {
  $pageTitle = 'Change Password';
  include_once('includes/header.php');

  if(isset($_POST['submit'])) {
    $adminid = $_SESSION['sturecmsaid'];
    $cpassword = md5($_POST['currentpassword']);
    $newpassword = md5($_POST['newpassword']);
    
    $sql = "SELECT ID FROM tbladmin WHERE ID=:adminid and Password=:cpassword";
    $query = $dbh->prepare($sql);
    $query->bindParam(':adminid', $adminid, PDO::PARAM_STR);
    $query->bindParam(':cpassword', $cpassword, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if($query->rowCount() > 0) {
      $con = "UPDATE tbladmin SET Password=:newpassword WHERE ID=:adminid";
      $chngpwd1 = $dbh->prepare($con);
      $chngpwd1->bindParam(':adminid', $adminid, PDO::PARAM_STR);
      $chngpwd1->bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
      $chngpwd1->execute();

      echo '<script>
        Swal.fire({
          icon: "success",
          title: "Success!",
          text: "Your password has been successfully changed",
          showConfirmButton: true,
          timer: 3000
        });
      </script>';
    } else {
      echo '<script>
        Swal.fire({
          icon: "error",
          title: "Error!",
          text: "Your current password is incorrect",
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
              <span class="text-sm font-medium text-primary">Change Password</span>
            </div>
          </li>
        </ol>
      </nav>
    </div>
    <a href="profile.php" class="mt-4 md:mt-0 inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
      <i class="fas fa-user mr-2"></i> View Profile
    </a>
  </div>
  
  <!-- Password Change Card -->
  <div class="bg-white rounded-xl shadow-sm overflow-hidden max-w-2xl mx-auto" data-aos="fade-up">
    <div class="px-6 py-4 border-b">
      <div class="flex items-center">
        <div class="p-2 bg-blue-100 text-blue-600 rounded-lg mr-4">
          <i class="fas fa-lock text-xl"></i>
        </div>
        <div>
          <h3 class="text-lg font-semibold text-gray-800">Update Your Password</h3>
          <p class="text-sm text-gray-500">For security, choose a strong password</p>
        </div>
      </div>
    </div>
    
    <form class="p-6" name="changepassword" method="post" onsubmit="return validatePasswordForm()">
      <!-- Current Password -->
      <div class="mb-6">
        <label for="currentpassword" class="block text-sm font-medium text-gray-700 mb-1">Current Password*</label>
        <div class="relative">
          <input 
            type="password" 
            id="currentpassword" 
            name="currentpassword" 
            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary transition-all"
            required
            autocomplete="current-password"
          >
          <div class="absolute inset-y-0 right-0 flex items-center pr-3">
            <button type="button" class="text-gray-400 hover:text-gray-500 focus:outline-none toggle-password" data-target="currentpassword">
              <i class="fas fa-eye"></i>
            </button>
          </div>
        </div>
      </div>
      
      <!-- New Password -->
      <div class="mb-6">
        <label for="newpassword" class="block text-sm font-medium text-gray-700 mb-1">New Password*</label>
        <div class="relative">
          <input 
            type="password" 
            id="newpassword" 
            name="newpassword" 
            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary transition-all"
            required
            pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
            title="Must contain at least one number, one uppercase letter, one lowercase letter, and at least 8 or more characters"
            autocomplete="new-password"
          >
          <div class="absolute inset-y-0 right-0 flex items-center pr-3">
            <button type="button" class="text-gray-400 hover:text-gray-500 focus:outline-none toggle-password" data-target="newpassword">
              <i class="fas fa-eye"></i>
            </button>
          </div>
        </div>
        <div id="password-strength" class="mt-2 text-xs">
          <div class="grid grid-cols-4 gap-2">
            <div class="h-1 rounded bg-gray-200" id="strength-bar-1"></div>
            <div class="h-1 rounded bg-gray-200" id="strength-bar-2"></div>
            <div class="h-1 rounded bg-gray-200" id="strength-bar-3"></div>
            <div class="h-1 rounded bg-gray-200" id="strength-bar-4"></div>
          </div>
          <p id="password-strength-text" class="mt-1 text-gray-500">Password strength</p>
        </div>
      </div>
      
      <!-- Confirm Password -->
      <div class="mb-6">
        <label for="confirmpassword" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password*</label>
        <div class="relative">
          <input 
            type="password" 
            id="confirmpassword" 
            name="confirmpassword" 
            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary transition-all"
            required
            autocomplete="new-password"
          >
          <div class="absolute inset-y-0 right-0 flex items-center pr-3">
            <button type="button" class="text-gray-400 hover:text-gray-500 focus:outline-none toggle-password" data-target="confirmpassword">
              <i class="fas fa-eye"></i>
            </button>
          </div>
        </div>
        <p id="password-match" class="mt-1 text-xs text-gray-500 hidden">
          <i class="fas fa-check-circle text-green-500 mr-1"></i>
          <span>Passwords match</span>
        </p>
        <p id="password-mismatch" class="mt-1 text-xs text-red-500 hidden">
          <i class="fas fa-times-circle mr-1"></i>
          <span>Passwords do not match</span>
        </p>
      </div>
      
      <!-- Password Requirements -->
      <div class="bg-gray-50 p-4 rounded-lg mb-6">
        <h4 class="text-sm font-medium text-gray-700 mb-2">Password Requirements:</h4>
        <ul class="text-xs text-gray-600 space-y-1">
          <li class="flex items-center" id="req-length">
            <i class="fas fa-check-circle text-gray-300 mr-2" id="length-icon"></i>
            <span>Minimum 8 characters</span>
          </li>
          <li class="flex items-center" id="req-uppercase">
            <i class="fas fa-check-circle text-gray-300 mr-2" id="uppercase-icon"></i>
            <span>At least one uppercase letter</span>
          </li>
          <li class="flex items-center" id="req-lowercase">
            <i class="fas fa-check-circle text-gray-300 mr-2" id="lowercase-icon"></i>
            <span>At least one lowercase letter</span>
          </li>
          <li class="flex items-center" id="req-number">
            <i class="fas fa-check-circle text-gray-300 mr-2" id="number-icon"></i>
            <span>At least one number</span>
          </li>
        </ul>
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
          id="submit-btn"
        >
          <i class="fas fa-key mr-2"></i> Change Password
        </button>
      </div>
    </form>
  </div>
  
  <!-- Security Tips -->
  <div class="bg-white rounded-xl shadow-sm p-6 max-w-2xl mx-auto mt-6" data-aos="fade-up" data-aos-delay="100">
    <div class="flex items-center mb-4">
      <div class="p-2 bg-yellow-100 text-yellow-600 rounded-lg mr-4">
        <i class="fas fa-shield-alt text-xl"></i>
      </div>
      <h3 class="text-lg font-semibold text-gray-800">Security Tips</h3>
    </div>
    <ul class="space-y-3 text-sm text-gray-600">
      <li class="flex items-start">
        <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
        <span>Use a unique password that you don't use elsewhere</span>
      </li>
      <li class="flex items-start">
        <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
        <span>Consider using a password manager to generate and store strong passwords</span>
      </li>
      <li class="flex items-start">
        <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
        <span>Change your password regularly (every 3-6 months)</span>
      </li>
      <li class="flex items-start">
        <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
        <span>Never share your password with anyone</span>
      </li>
    </ul>
  </div>
</div>

<script>
  // Toggle password visibility
  document.querySelectorAll('.toggle-password').forEach(button => {
    button.addEventListener('click', function() {
      const targetId = this.getAttribute('data-target');
      const input = document.getElementById(targetId);
      const icon = this.querySelector('i');
      
      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      }
    });
  });

  // Password strength checker
  document.getElementById('newpassword').addEventListener('input', function() {
    const password = this.value;
    const strengthBars = [
      document.getElementById('strength-bar-1'),
      document.getElementById('strength-bar-2'),
      document.getElementById('strength-bar-3'),
      document.getElementById('strength-bar-4')
    ];
    const strengthText = document.getElementById('password-strength-text');
    
    // Reset all bars
    strengthBars.forEach(bar => {
      bar.classList.remove('bg-red-500', 'bg-yellow-500', 'bg-green-500');
      bar.classList.add('bg-gray-200');
    });
    
    // Check password strength
    let strength = 0;
    
    // Length check
    if (password.length >= 8) {
      strength += 1;
      document.getElementById('length-icon').classList.remove('text-gray-300');
      document.getElementById('length-icon').classList.add('text-green-500');
    } else {
      document.getElementById('length-icon').classList.remove('text-green-500');
      document.getElementById('length-icon').classList.add('text-gray-300');
    }
    
    // Uppercase check
    if (/[A-Z]/.test(password)) {
      strength += 1;
      document.getElementById('uppercase-icon').classList.remove('text-gray-300');
      document.getElementById('uppercase-icon').classList.add('text-green-500');
    } else {
      document.getElementById('uppercase-icon').classList.remove('text-green-500');
      document.getElementById('uppercase-icon').classList.add('text-gray-300');
    }
    
    // Lowercase check
    if (/[a-z]/.test(password)) {
      strength += 1;
      document.getElementById('lowercase-icon').classList.remove('text-gray-300');
      document.getElementById('lowercase-icon').classList.add('text-green-500');
    } else {
      document.getElementById('lowercase-icon').classList.remove('text-green-500');
      document.getElementById('lowercase-icon').classList.add('text-gray-300');
    }
    
    // Number check
    if (/\d/.test(password)) {
      strength += 1;
      document.getElementById('number-icon').classList.remove('text-gray-300');
      document.getElementById('number-icon').classList.add('text-green-500');
    } else {
      document.getElementById('number-icon').classList.remove('text-green-500');
      document.getElementById('number-icon').classList.add('text-gray-300');
    }
    
    // Update strength bars
    if (password.length > 0) {
      for (let i = 0; i < strength; i++) {
        strengthBars[i].classList.remove('bg-gray-200');
        if (strength === 1) {
          strengthBars[i].classList.add('bg-red-500');
          strengthText.textContent = 'Weak';
          strengthText.className = 'mt-1 text-xs text-red-500';
        } else if (strength <= 3) {
          strengthBars[i].classList.add('bg-yellow-500');
          strengthText.textContent = strength === 2 ? 'Fair' : 'Good';
          strengthText.className = 'mt-1 text-xs text-yellow-500';
        } else {
          strengthBars[i].classList.add('bg-green-500');
          strengthText.textContent = 'Strong';
          strengthText.className = 'mt-1 text-xs text-green-500';
        }
      }
    } else {
      strengthText.textContent = 'Password strength';
      strengthText.className = 'mt-1 text-xs text-gray-500';
    }
    
    // Check password match
    checkPasswordMatch();
  });

  // Check password match
  function checkPasswordMatch() {
    const password = document.getElementById('newpassword').value;
    const confirmPassword = document.getElementById('confirmpassword').value;
    const matchElement = document.getElementById('password-match');
    const mismatchElement = document.getElementById('password-mismatch');
    
    if (confirmPassword.length === 0) {
      matchElement.classList.add('hidden');
      mismatchElement.classList.add('hidden');
      return;
    }
    
    if (password === confirmPassword) {
      matchElement.classList.remove('hidden');
      mismatchElement.classList.add('hidden');
    } else {
      matchElement.classList.add('hidden');
      mismatchElement.classList.remove('hidden');
    }
  }
  
  document.getElementById('confirmpassword').addEventListener('input', checkPasswordMatch);

  // Form validation
  function validatePasswordForm() {
    const currentPassword = document.getElementById('currentpassword').value;
    const newPassword = document.getElementById('newpassword').value;
    const confirmPassword = document.getElementById('confirmpassword').value;
    
    // Check if current password is entered
    if (currentPassword.length === 0) {
      Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: 'Please enter your current password',
        showConfirmButton: true
      });
      return false;
    }
    
    // Check if new password meets requirements
    if (newPassword.length < 8 || !/[A-Z]/.test(newPassword) || !/[a-z]/.test(newPassword) || !/\d/.test(newPassword)) {
      Swal.fire({
        icon: 'error',
        title: 'Error!',
        html: 'New password must meet all requirements:<ul class="text-left mt-2">' +
              '<li>• Minimum 8 characters</li>' +
              '<li>• At least one uppercase letter</li>' +
              '<li>• At least one lowercase letter</li>' +
              '<li>• At least one number</li></ul>',
        showConfirmButton: true
      });
      return false;
    }
    
    // Check if passwords match
    if (newPassword !== confirmPassword) {
      Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: 'New password and confirmation do not match',
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