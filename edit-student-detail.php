<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsaid']) == 0) {
  header('location:logout.php');
} else {
  $pageTitle = 'Update Student';
  include_once('includes/header.php');
  
  if(isset($_POST['submit'])) {
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
    $eid = $_GET['editid'];
    
    $sql = "UPDATE tblstudent SET StudentName=:stuname, StudentEmail=:stuemail, StudentClass=:stuclass, 
            Gender=:gender, DOB=:dob, StuID=:stuid, FatherName=:fname, MotherName=:mname, 
            ContactNumber=:connum, AltenateNumber=:altconnum, Address=:address WHERE ID=:eid";
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
    $query->bindParam(':eid', $eid, PDO::PARAM_STR);
    
    if($query->execute()) {
      echo '<script>
        Swal.fire({
          icon: "success",
          title: "Success!",
          text: "Student information has been updated successfully",
          showConfirmButton: true,
          timer: 3000
        });
      </script>';
    } else {
      echo '<script>
        Swal.fire({
          icon: "error",
          title: "Error!",
          text: "Failed to update student information",
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
            <a href="manage-students.php" class="text-sm font-medium text-gray-500 hover:text-primary">
              Manage Students
            </a>
          </li>
          <li aria-current="page">
            <div class="flex items-center">
              <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
              <span class="text-sm font-medium text-primary">Update Student</span>
            </div>
          </li>
        </ol>
      </nav>
    </div>
    <a href="manage-students.php" class="mt-4 md:mt-0 inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
      <i class="fas fa-list mr-2"></i> View All Students
    </a>
  </div>
  
  <!-- Student Update Card -->
  <div class="bg-white rounded-xl shadow-sm overflow-hidden max-w-6xl mx-auto" data-aos="fade-up">
    <div class="px-6 py-4 border-b">
      <div class="flex items-center">
        <div class="p-2 bg-blue-100 text-blue-600 rounded-lg mr-4">
          <i class="fas fa-user-graduate text-xl"></i>
        </div>
        <div>
          <h3 class="text-lg font-semibold text-gray-800">Student Details</h3>
          <p class="text-sm text-gray-500">Update student information and records</p>
        </div>
      </div>
    </div>
    
    <?php
    $eid = $_GET['editid'];
    $sql = "SELECT tblstudent.*, tblclass.ClassName, tblclass.Section 
            FROM tblstudent JOIN tblclass ON tblclass.ID=tblstudent.StudentClass 
            WHERE tblstudent.ID=:eid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':eid', $eid, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    
    if($query->rowCount() > 0) {
      foreach($results as $row) {
    ?>
    <form class="p-6" method="post" onsubmit="return validateStudentForm()">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Student Basic Information -->
        <div class="space-y-6">
          <h4 class="text-lg font-semibold text-gray-700 border-b pb-2">Basic Information</h4>
          
          <div>
            <label for="stuname" class="block text-sm font-medium text-gray-700 mb-1">Student Name*</label>
            <input 
              type="text" 
              id="stuname" 
              name="stuname" 
              value="<?php echo htmlspecialchars($row->StudentName); ?>" 
              class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary transition-all"
              required
              maxlength="50"
            >
          </div>
          
          <div>
            <label for="stuemail" class="block text-sm font-medium text-gray-700 mb-1">Student Email*</label>
            <input 
              type="email" 
              id="stuemail" 
              name="stuemail" 
              value="<?php echo htmlspecialchars($row->StudentEmail); ?>" 
              class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary transition-all"
              required
              pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
            >
          </div>
          
          <div>
            <label for="stuclass" class="block text-sm font-medium text-gray-700 mb-1">Class*</label>
            <select 
              id="stuclass" 
              name="stuclass" 
              class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary transition-all appearance-none"
              required
            >
              <option value="<?php echo htmlspecialchars($row->StudentClass); ?>" selected>
                <?php echo htmlspecialchars($row->ClassName) . ' ' . htmlspecialchars($row->Section); ?>
              </option>
              <?php 
              $sql2 = "SELECT * FROM tblclass";
              $query2 = $dbh->prepare($sql2);
              $query2->execute();
              $result2 = $query2->fetchAll(PDO::FETCH_OBJ);
              
              foreach($result2 as $row1) {
                if($row1->ID != $row->StudentClass) {
                  echo '<option value="' . htmlspecialchars($row1->ID) . '">' . 
                       htmlspecialchars($row1->ClassName) . ' ' . htmlspecialchars($row1->Section) . '</option>';
                }
              } 
              ?>
            </select>
          </div>
          
          <div>
            <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gender*</label>
            <select 
              id="gender" 
              name="gender" 
              class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary transition-all"
              required
            >
              <option value="<?php echo htmlspecialchars($row->Gender); ?>" selected><?php echo htmlspecialchars($row->Gender); ?></option>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
              <option value="Other">Other</option>
            </select>
          </div>
          
          <div>
            <label for="dob" class="block text-sm font-medium text-gray-700 mb-1">Date of Birth*</label>
            <input 
              type="date" 
              id="dob" 
              name="dob" 
              value="<?php echo htmlspecialchars($row->DOB); ?>" 
              class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary transition-all"
              required
              max="<?php echo date('Y-m-d'); ?>"
            >
          </div>
          
          <div>
            <label for="stuid" class="block text-sm font-medium text-gray-700 mb-1">Student ID</label>
            <input 
              type="text" 
              id="stuid" 
              name="stuid" 
              value="<?php echo htmlspecialchars($row->StuID); ?>" 
              class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-gray-100 cursor-not-allowed"
              readonly
            >
          </div>
        </div>
        
        <!-- Student Image -->
        <div class="flex flex-col items-center space-y-4">
          <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-white shadow-md">
            <img 
              src="assets/images/<?php echo htmlspecialchars($row->Image); ?>" 
              alt="Student Photo" 
              class="w-full h-full object-cover"
              onerror="this.src='assets/images/default-student.jpg'"
            >
          </div>
          <a 
            href="changeimage.php?editid=<?php echo htmlspecialchars($row->ID); ?>" 
            class="px-4 py-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-colors flex items-center"
          >
            <i class="fas fa-camera mr-2"></i> Change Image
          </a>
          <div class="text-sm text-gray-500">
            <p>Admission Date: <?php echo date('M j, Y', strtotime($row->DateofAdmission)); ?></p>
          </div>
        </div>
      </div>
      
      <!-- Parent/Guardian Information -->
      <div class="mt-8 pt-6 border-t">
        <h4 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-6">Parent/Guardian Information</h4>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label for="fname" class="block text-sm font-medium text-gray-700 mb-1">Father's Name*</label>
            <input 
              type="text" 
              id="fname" 
              name="fname" 
              value="<?php echo htmlspecialchars($row->FatherName); ?>" 
              class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary transition-all"
              required
              maxlength="50"
            >
          </div>
          
          <div>
            <label for="mname" class="block text-sm font-medium text-gray-700 mb-1">Mother's Name*</label>
            <input 
              type="text" 
              id="mname" 
              name="mname" 
              value="<?php echo htmlspecialchars($row->MotherName); ?>" 
              class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary transition-all"
              required
              maxlength="50"
            >
          </div>
          
          <div>
            <label for="connum" class="block text-sm font-medium text-gray-700 mb-1">Contact Number*</label>
            <input 
              type="tel" 
              id="connum" 
              name="connum" 
              value="<?php echo htmlspecialchars($row->ContactNumber); ?>" 
              class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary transition-all"
              required
              pattern="[0-9]{10}"
              maxlength="10"
              minlength="10"
              placeholder="10-digit number"
            >
          </div>
          
          <div>
            <label for="altconnum" class="block text-sm font-medium text-gray-700 mb-1">Alternate Contact Number*</label>
            <input 
              type="tel" 
              id="altconnum" 
              name="altconnum" 
              value="<?php echo htmlspecialchars($row->AltenateNumber); ?>" 
              class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary transition-all"
              required
              pattern="[0-9]{10}"
              maxlength="10"
              minlength="10"
              placeholder="10-digit number"
            >
          </div>
          
          <div class="md:col-span-2">
            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address*</label>
            <textarea 
              id="address" 
              name="address" 
              rows="3" 
              class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary transition-all"
              required
            ><?php echo htmlspecialchars($row->Address); ?></textarea>
          </div>
        </div>
      </div>
      
      <!-- Login Information (Readonly) -->
      <div class="mt-8 pt-6 border-t">
        <h4 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-6">Login Information</h4>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label for="uname" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
            <input 
              type="text" 
              id="uname" 
              value="<?php echo htmlspecialchars($row->UserName); ?>" 
              class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-gray-100 cursor-not-allowed"
              readonly
            >
          </div>
          
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <div class="relative">
              <input 
                type="password" 
                id="password" 
                value="<?php echo htmlspecialchars($row->Password); ?>" 
                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-gray-100 cursor-not-allowed"
                readonly
              >
              <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                <i class="fas fa-lock text-gray-400"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Form Actions -->
      <div class="flex justify-end space-x-3 pt-6 mt-8 border-t">
        <a 
          href="manage-students.php" 
          class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
        >
          <i class="fas fa-times mr-2"></i> Cancel
        </a>
        <button 
          type="submit" 
          name="submit" 
          class="px-6 py-2.5 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors flex items-center"
        >
          <i class="fas fa-save mr-2"></i> Update Student
        </button>
      </div>
    </form>
    <?php }} ?>
  </div>
</div>

<script>
  // Form validation
  function validateStudentForm() {
    const email = document.getElementById('stuemail').value;
    const connum = document.getElementById('connum').value;
    const altconnum = document.getElementById('altconnum').value;
    const dob = document.getElementById('dob').value;
    const today = new Date();
    const dobDate = new Date(dob);
    
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
    if (!/^\d{10}$/.test(connum)) {
      Swal.fire({
        icon: 'error',
        title: 'Invalid Contact Number',
        text: 'Please enter a 10-digit contact number',
        showConfirmButton: true
      });
      return false;
    }
    
    if (!/^\d{10}$/.test(altconnum)) {
      Swal.fire({
        icon: 'error',
        title: 'Invalid Alternate Contact',
        text: 'Please enter a 10-digit alternate contact number',
        showConfirmButton: true
      });
      return false;
    }
    
    // Date of Birth validation
    if (dobDate >= today) {
      Swal.fire({
        icon: 'error',
        title: 'Invalid Date of Birth',
        text: 'Date of birth must be in the past',
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