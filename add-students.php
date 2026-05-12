<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsaid']) == 0) {
  header('location:logout.php');
} else {
  $pageTitle = 'Add Student';
  include_once('includes/header.php');
  
  if(isset($_POST['submit'])) {
    // Collect form data
    $stuname = $_POST['stuname'];
    $stuemail = $_POST['stuemail'];
    $stuclass = $_POST['stuclass'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $stuid = $_POST['stuid'];
    $fname = $_POST['fname'];
    $mname = $_POST['mname'];
    $connum = $_POST['connum'];
    $altconnum = $_POST['altconnum'];
    $address = $_POST['address'];
    $uname = $_POST['uname'];
    $password = md5($_POST['password']);
    $image = $_FILES["image"]["name"];
    
    // Check if username or student ID already exists
    $ret = "SELECT UserName from tblstudent where UserName=:uname || StuID=:stuid";
    $query = $dbh->prepare($ret);
    $query->bindParam(':uname', $uname, PDO::PARAM_STR);
    $query->bindParam(':stuid', $stuid, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    
    if($query->rowCount() == 0) {
      // Process image upload
      $extension = substr($image, strlen($image)-4, strlen($image));
      $allowed_extensions = array(".jpg", "jpeg", ".png", ".gif");
      
      if(!in_array($extension, $allowed_extensions)) {
        echo '<script>
          Swal.fire({
            title: "Invalid Image Format",
            text: "Only jpg/jpeg/png/gif formats allowed",
            icon: "error",
            confirmButtonColor: "#4F46E5"
          });
        </script>';
      } else {
        $image = md5($image).time().$extension;
        move_uploaded_file($_FILES["image"]["tmp_name"], "images/".$image);
        
        // Insert student record
        $sql = "INSERT INTO tblstudent(StudentName, StudentEmail, StudentClass, Gender, DOB, StuID, FatherName, MotherName, ContactNumber, AltenateNumber, Address, UserName, Password, Image) VALUES(:stuname, :stuemail, :stuclass, :gender, :dob, :stuid, :fname, :mname, :connum, :altconnum, :address, :uname, :password, :image)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':stuname', $stuname, PDO::PARAM_STR);
        $query->bindParam(':stuemail', $stuemail, PDO::PARAM_STR);
        $query->bindParam(':stuclass', $stuclass, PDO::PARAM_STR);
        $query->bindParam(':gender', $gender, PDO::PARAM_STR);
        $query->bindParam(':dob', $dob, PDO::PARAM_STR);
        $query->bindParam(':stuid', $stuid, PDO::PARAM_STR);
        $query->bindParam(':fname', $fname, PDO::PARAM_STR);
        $query->bindParam(':mname', $mname, PDO::PARAM_STR);
        $query->bindParam(':connum', $connum, PDO::PARAM_STR);
        $query->bindParam(':altconnum', $altconnum, PDO::PARAM_STR);
        $query->bindParam(':address', $address, PDO::PARAM_STR);
        $query->bindParam(':uname', $uname, PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR);
        $query->bindParam(':image', $image, PDO::PARAM_STR);
        $query->execute();
        
        $LastInsertId = $dbh->lastInsertId();
        if ($LastInsertId > 0) {
          echo '<script>
            Swal.fire({
              title: "Success!",
              text: "Student has been added successfully.",
              icon: "success",
              confirmButtonColor: "#4F46E5",
              confirmButtonText: "OK"
            }).then((result) => {
              if (result.isConfirmed) {
                window.location.href = "add-students.php";
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
    } else {
      echo '<script>
        Swal.fire({
          title: "Duplicate Entry",
          text: "Username or Student ID already exists.",
          icon: "warning",
          confirmButtonColor: "#4F46E5"
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
              <span class="text-sm font-medium text-primary">Add Student</span>
            </div>
          </li>
        </ol>
      </nav>
    </div>
    <a href="manage-students.php" class="mt-4 md:mt-0 inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
      <i class="fas fa-list mr-2"></i> View All Students
    </a>
  </div>
  
  <!-- Form Card -->
  <div class="bg-white rounded-xl shadow-sm overflow-hidden" data-aos="fade-up">
    <div class="px-6 py-4 border-b">
      <div class="flex items-center">
        <div class="p-2 bg-blue-100 text-blue-600 rounded-lg mr-4">
          <i class="fas fa-user-graduate text-xl"></i>
        </div>
        <div>
          <h3 class="text-lg font-semibold text-gray-800">Student Registration Form</h3>
          <p class="text-sm text-gray-500">Fill in all required details to register a new student</p>
        </div>
      </div>
    </div>
    
    <form class="p-6" method="post" enctype="multipart/form-data">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Student Basic Information -->
        <div class="md:col-span-2">
          <h4 class="text-md font-semibold text-gray-700 mb-3 border-b pb-2">Basic Information</h4>
        </div>
        
        <!-- Student Name -->
        <div>
          <label for="stuname" class="block text-sm font-medium text-gray-700 mb-1">Full Name*</label>
          <div class="relative">
            <input 
              type="text" 
              name="stuname" 
              id="stuname" 
              class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary transition-all"
              placeholder="Enter student's full name"
              required
            >
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
              <i class="fas fa-user text-gray-400"></i>
            </div>
          </div>
        </div>
        
        <!-- Student Email -->
        <div>
          <label for="stuemail" class="block text-sm font-medium text-gray-700 mb-1">Email*</label>
          <div class="relative">
            <input 
              type="email" 
              name="stuemail" 
              id="stuemail" 
              class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary transition-all"
              placeholder="student@example.com"
              required
            >
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
              <i class="fas fa-envelope text-gray-400"></i>
            </div>
          </div>
        </div>
        
        <!-- Student Class -->
        <div>
          <label for="stuclass" class="block text-sm font-medium text-gray-700 mb-1">Class*</label>
          <div class="relative">
            <select 
              name="stuclass" 
              id="stuclass" 
              class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary appearance-none transition-all"
              required
            >
              <option value="" disabled selected>Select Class</option>
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
              <i class="fas fa-layer-group text-gray-400"></i>
            </div>
          </div>
        </div>
        
        <!-- Gender -->
        <div>
          <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gender*</label>
          <div class="relative">
            <select 
              name="gender" 
              id="gender" 
              class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary appearance-none transition-all"
              required
            >
              <option value="" disabled selected>Select Gender</option>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
            </select>
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
              <i class="fas fa-venus-mars text-gray-400"></i>
            </div>
          </div>
        </div>
        
        <!-- Date of Birth -->
        <div>
          <label for="dob" class="block text-sm font-medium text-gray-700 mb-1">Date of Birth*</label>
          <div class="relative">
            <input 
              type="date" 
              name="dob" 
              id="dob" 
              class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary transition-all"
              required
            >
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
              <i class="fas fa-calendar-day text-gray-400"></i>
            </div>
          </div>
        </div>
        
        <!-- Student ID -->
        <div>
          <label for="stuid" class="block text-sm font-medium text-gray-700 mb-1">Student ID*</label>
          <div class="relative">
            <input 
              type="text" 
              name="stuid" 
              id="stuid" 
              class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary transition-all"
              placeholder="Enter student ID"
              required
            >
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
              <i class="fas fa-id-card text-gray-400"></i>
            </div>
          </div>
        </div>
        
        <!-- Student Photo -->
        <div>
          <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Student Photo*</label>
          <div class="relative">
            <input 
              type="file" 
              name="image" 
              id="image" 
              class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary transition-all file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-primary hover:file:bg-blue-100"
              accept=".jpg,.jpeg,.png,.gif"
              required
            >
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
              <i class="fas fa-camera text-gray-400"></i>
            </div>
          </div>
          <p class="mt-1 text-xs text-gray-500">Allowed formats: .jpg, .jpeg, .png, .gif</p>
        </div>
        
        <!-- Parent/Guardian Information -->
        <div class="md:col-span-2 mt-4">
          <h4 class="text-md font-semibold text-gray-700 mb-3 border-b pb-2">Parent/Guardian Information</h4>
        </div>
        
        <!-- Father's Name -->
        <div>
          <label for="fname" class="block text-sm font-medium text-gray-700 mb-1">Father's Name*</label>
          <div class="relative">
            <input 
              type="text" 
              name="fname" 
              id="fname" 
              class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary transition-all"
              placeholder="Enter father's name"
              required
            >
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
              <i class="fas fa-male text-gray-400"></i>
            </div>
          </div>
        </div>
        
        <!-- Mother's Name -->
        <div>
          <label for="mname" class="block text-sm font-medium text-gray-700 mb-1">Mother's Name*</label>
          <div class="relative">
            <input 
              type="text" 
              name="mname" 
              id="mname" 
              class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary transition-all"
              placeholder="Enter mother's name"
              required
            >
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
              <i class="fas fa-female text-gray-400"></i>
            </div>
          </div>
        </div>
        
        <!-- Contact Number -->
        <div>
          <label for="connum" class="block text-sm font-medium text-gray-700 mb-1">Contact Number*</label>
          <div class="relative">
            <input 
              type="tel" 
              name="connum" 
              id="connum" 
              class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary transition-all"
              placeholder="10-digit mobile number"
              pattern="[0-9]{10}"
              maxlength="10"
              required
            >
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
              <i class="fas fa-phone text-gray-400"></i>
            </div>
          </div>
        </div>
        
        <!-- Alternate Contact Number -->
        <div>
          <label for="altconnum" class="block text-sm font-medium text-gray-700 mb-1">Alternate Contact*</label>
          <div class="relative">
            <input 
              type="tel" 
              name="altconnum" 
              id="altconnum" 
              class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary transition-all"
              placeholder="10-digit mobile number"
              pattern="[0-9]{10}"
              maxlength="10"
              required
            >
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
              <i class="fas fa-phone-alt text-gray-400"></i>
            </div>
          </div>
        </div>
        
        <!-- Address -->
        <div class="md:col-span-2">
          <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address*</label>
          <textarea 
            name="address" 
            id="address" 
            rows="3"
            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary transition-all"
            placeholder="Enter complete address"
            required
          ></textarea>
        </div>
        
        <!-- Login Details -->
        <div class="md:col-span-2 mt-4">
          <h4 class="text-md font-semibold text-gray-700 mb-3 border-b pb-2">Login Details</h4>
        </div>
        
        <!-- Username -->
        <div>
          <label for="uname" class="block text-sm font-medium text-gray-700 mb-1">Username*</label>
          <div class="relative">
            <input 
              type="text" 
              name="uname" 
              id="uname" 
              class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary transition-all"
              placeholder="Choose a username"
              required
            >
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
              <i class="fas fa-user-circle text-gray-400"></i>
            </div>
          </div>
          <p class="mt-1 text-xs text-gray-500">Must be unique</p>
        </div>
        
        <!-- Password -->
        <div>
          <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password*</label>
          <div class="relative">
            <input 
              type="password" 
              name="password" 
              id="password" 
              class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary transition-all"
              placeholder="Create a password"
              required
            >
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer" onclick="togglePassword()">
              <i class="fas fa-eye text-gray-400 hover:text-primary"></i>
            </div>
          </div>
          <p class="mt-1 text-xs text-gray-500">Minimum 6 characters</p>
        </div>
        
        <!-- Form Actions -->
        <div class="md:col-span-2 flex justify-end space-x-3 pt-4 border-t mt-6">
          <button 
            type="reset" 
            class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
          >
            <i class="fas fa-eraser mr-2"></i> Reset Form
          </button>
          <button 
            type="submit" 
            name="submit" 
            class="px-6 py-2.5 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors flex items-center"
          >
            <i class="fas fa-user-plus mr-2"></i> Register Student
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Include SweetAlert2 for beautiful alerts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  // Toggle password visibility
  function togglePassword() {
    const passwordField = document.getElementById('password');
    const eyeIcon = document.querySelector('#password + div i');
    
    if (passwordField.type === 'password') {
      passwordField.type = 'text';
      eyeIcon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
      passwordField.type = 'password';
      eyeIcon.classList.replace('fa-eye-slash', 'fa-eye');
    }
  }
  
  // Validate contact numbers
  document.getElementById('connum').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9]/g, '');
  });
  
  document.getElementById('altconnum').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9]/g, '');
  });
</script>

<?php 
  include_once('includes/footer.php');
}
?>