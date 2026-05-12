<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['sturecmsaid']) == 0) {
  header('location:logout.php');
} else {
  $pageTitle = 'Update Contact Us';
  include_once('includes/header.php');

  if(isset($_POST['submit'])) {
    $pagetitle = $_POST['pagetitle'];
    $pagedes = $_POST['pagedes'];
    $mobnum = $_POST['mobnum'];
    $email = $_POST['email'];
    
    $sql = "UPDATE tblpage SET PageTitle=:pagetitle, PageDescription=:pagedes, Email=:email, MobileNumber=:mobnum WHERE PageType='contactus'";
    $query = $dbh->prepare($sql);
    $query->bindParam(':pagetitle', $pagetitle, PDO::PARAM_STR);
    $query->bindParam(':pagedes', $pagedes, PDO::PARAM_STR);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':mobnum', $mobnum, PDO::PARAM_STR);
    
    if($query->execute()) {
      echo '<script>
        Swal.fire({
          icon: "success",
          title: "Success!",
          text: "Contact information has been updated successfully",
          showConfirmButton: true,
          timer: 3000
        });
      </script>';
    } else {
      echo '<script>
        Swal.fire({
          icon: "error",
          title: "Error!",
          text: "Failed to update contact information",
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
              <span class="text-sm font-medium text-primary">Update Contact Us</span>
            </div>
          </li>
        </ol>
      </nav>
    </div>
    <a href="contact-details.php" class="mt-4 md:mt-0 inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
      <i class="fas fa-eye mr-2"></i> View Contact Page
    </a>
  </div>
  
  <!-- Contact Update Card -->
  <div class="bg-white rounded-xl shadow-sm overflow-hidden max-w-3xl mx-auto" data-aos="fade-up">
    <div class="px-6 py-4 border-b">
      <div class="flex items-center">
        <div class="p-2 bg-blue-100 text-blue-600 rounded-lg mr-4">
          <i class="fas fa-address-book text-xl"></i>
        </div>
        <div>
          <h3 class="text-lg font-semibold text-gray-800">Contact Information</h3>
          <p class="text-sm text-gray-500">Update your institution's contact details</p>
        </div>
      </div>
    </div>
    
    <?php
    $sql = "SELECT * FROM tblpage WHERE PageType='contactus'";
    $query = $dbh->prepare($sql);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    
    if($query->rowCount() > 0) {
      foreach($results as $row) {
    ?>
    <form class="p-6" method="post" onsubmit="return validateContactForm()">
      <!-- Page Title -->
      <div class="mb-6">
        <label for="pagetitle" class="block text-sm font-medium text-gray-700 mb-1">Page Title*</label>
        <input 
          type="text" 
          id="pagetitle" 
          name="pagetitle" 
          value="<?php echo htmlspecialchars($row->PageTitle); ?>" 
          class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary transition-all"
          required
          maxlength="100"
        >
      </div>
      
      <!-- Page Description -->
      <div class="mb-6">
        <label for="pagedes" class="block text-sm font-medium text-gray-700 mb-1">Page Description*</label>
        <textarea 
          id="pagedes" 
          name="pagedes" 
          rows="4" 
          class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary transition-all"
          required
        ><?php echo htmlspecialchars($row->PageDescription); ?></textarea>
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
          >
          <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
            <i class="fas fa-envelope text-gray-400"></i>
          </div>
        </div>
        <p class="mt-1 text-xs text-gray-500">Must be a valid email address</p>
      </div>
      
      <!-- Mobile Number -->
      <div class="mb-6">
        <label for="mobnum" class="block text-sm font-medium text-gray-700 mb-1">Mobile Number*</label>
        <div class="relative">
          <input 
            type="tel" 
            id="mobnum" 
            name="mobnum" 
            value="<?php echo htmlspecialchars($row->MobileNumber); ?>" 
            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary transition-all"
            required
            pattern="[0-9]{10}"
            maxlength="10"
            minlength="10"
          >
          <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
            <i class="fas fa-phone text-gray-400"></i>
          </div>
        </div>
        <p class="mt-1 text-xs text-gray-500">10-digit mobile number without spaces or special characters</p>
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
          <i class="fas fa-save mr-2"></i> Update Contact
        </button>
      </div>
    </form>
    <?php }} ?>
  </div>
  
  <!-- Preview Section -->
  <div class="bg-white rounded-xl shadow-sm p-6 max-w-3xl mx-auto mt-6" data-aos="fade-up" data-aos-delay="100">
    <div class="flex items-center mb-4">
      <div class="p-2 bg-purple-100 text-purple-600 rounded-lg mr-4">
        <i class="fas fa-eye text-xl"></i>
      </div>
      <h3 class="text-lg font-semibold text-gray-800">Contact Page Preview</h3>
    </div>
    <div class="border rounded-lg p-4 bg-gray-50">
      <h4 class="text-xl font-semibold mb-2" id="preview-title"><?php echo htmlspecialchars($row->PageTitle ?? 'Contact Us'); ?></h4>
      <p class="text-gray-600 mb-4" id="preview-description"><?php echo nl2br(htmlspecialchars($row->PageDescription ?? 'Get in touch with us')); ?></p>
      <div class="space-y-2">
        <p class="flex items-center text-gray-700">
          <i class="fas fa-envelope mr-2 text-primary"></i>
          <span id="preview-email"><?php echo htmlspecialchars($row->Email ?? 'contact@example.com'); ?></span>
        </p>
        <p class="flex items-center text-gray-700">
          <i class="fas fa-phone mr-2 text-primary"></i>
          <span id="preview-phone"><?php echo htmlspecialchars($row->MobileNumber ?? '+1234567890'); ?></span>
        </p>
      </div>
    </div>
  </div>
</div>

<script>
  // Live preview update
  document.addEventListener('DOMContentLoaded', function() {
    // Get form elements
    const titleInput = document.getElementById('pagetitle');
    const descInput = document.getElementById('pagedes');
    const emailInput = document.getElementById('email');
    const phoneInput = document.getElementById('mobnum');
    
    // Get preview elements
    const previewTitle = document.getElementById('preview-title');
    const previewDesc = document.getElementById('preview-description');
    const previewEmail = document.getElementById('preview-email');
    const previewPhone = document.getElementById('preview-phone');
    
    // Update preview on input
    [titleInput, descInput, emailInput, phoneInput].forEach(input => {
      input.addEventListener('input', function() {
        switch(this.id) {
          case 'pagetitle':
            previewTitle.textContent = this.value;
            break;
          case 'pagedes':
            previewDesc.innerHTML = this.value.replace(/\n/g, '<br>');
            break;
          case 'email':
            previewEmail.textContent = this.value;
            break;
          case 'mobnum':
            previewPhone.textContent = this.value;
            break;
        }
      });
    });
  });
  
  // Form validation
  function validateContactForm() {
    const email = document.getElementById('email').value;
    const phone = document.getElementById('mobnum').value;
    
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
    
    // Phone validation
    if (!/^\d{10}$/.test(phone)) {
      Swal.fire({
        icon: 'error',
        title: 'Invalid Phone Number',
        text: 'Please enter a 10-digit mobile number',
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