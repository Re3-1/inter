<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsaid']) == 0) {
  header('location:logout.php');
} else {
  $pageTitle = 'Date Range Reports';
  include_once('includes/header.php');
  
  // Code for deletion
  if(isset($_GET['delid'])) {
    $rid = intval($_GET['delid']);
    $sql = "DELETE FROM tblstudent WHERE ID=:rid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':rid', $rid, PDO::PARAM_STR);
    $query->execute();
    
    echo '<script>
      Swal.fire({
        title: "Deleted!",
        text: "Student record has been deleted.",
        icon: "success",
        confirmButtonColor: "#4F46E5",
        confirmButtonText: "OK"
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = "manage-students.php";
        }
      });
    </script>';
  }
  
  $fdate = $_POST['fromdate'];
  $tdate = $_POST['todate'];
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
              <span class="text-sm font-medium text-primary">Date Range Reports</span>
            </div>
          </li>
        </ol>
      </nav>
    </div>
    <div class="mt-4 md:mt-0">
      <div class="flex items-center bg-blue-50 rounded-lg px-4 py-2">
        <i class="fas fa-calendar-alt text-blue-500 mr-2"></i>
        <span class="text-sm font-medium text-blue-700"><?php echo date('M j, Y', strtotime($fdate)); ?> to <?php echo date('M j, Y', strtotime($tdate)); ?></span>
      </div>
    </div>
  </div>
  
  <!-- Report Card -->
  <div class="bg-white rounded-xl shadow-sm overflow-hidden" data-aos="fade-up">
    <div class="px-6 py-4 border-b">
      <div class="flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-800">Student Admissions Report</h3>
        <div class="flex items-center space-x-2">
          <span class="text-sm text-gray-500"><?php echo date('F j, Y'); ?></span>
          <button onclick="window.print()" class="p-2 text-gray-500 hover:text-primary focus:outline-none">
            <i class="fas fa-print"></i>
          </button>
        </div>
      </div>
    </div>
    
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student ID</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Name</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admission Date</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <?php
          if (isset($_GET['pageno'])) {
            $pageno = $_GET['pageno'];
          } else {
            $pageno = 1;
          }
          
          // Pagination settings
          $no_of_records_per_page = 10;
          $offset = ($pageno-1) * $no_of_records_per_page;
          
          $ret = "SELECT ID FROM tblstudent";
          $query1 = $dbh->prepare($ret);
          $query1->execute();
          $total_rows = $query1->rowCount();
          $total_pages = ceil($total_rows / $no_of_records_per_page);
          
          $sql = "SELECT tblstudent.StuID, tblstudent.ID as sid, tblstudent.StudentName, tblstudent.StudentEmail, tblstudent.DateofAdmission, tblclass.ClassName, tblclass.Section 
                  FROM tblstudent 
                  JOIN tblclass ON tblclass.ID = tblstudent.StudentClass 
                  WHERE date(tblstudent.DateofAdmission) BETWEEN '$fdate' AND '$tdate' 
                  LIMIT $offset, $no_of_records_per_page";
          $query = $dbh->prepare($sql);
          $query->execute();
          $results = $query->fetchAll(PDO::FETCH_OBJ);
          
          $cnt = 1;
          if($query->rowCount() > 0) {
            foreach($results as $row) {
          ?>
          <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlentities($cnt); ?></td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlentities($row->StuID); ?></td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                <?php echo htmlentities($row->ClassName); ?> <?php echo htmlentities($row->Section); ?>
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlentities($row->StudentName); ?></td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlentities($row->StudentEmail); ?></td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              <?php echo date('M j, Y', strtotime($row->DateofAdmission)); ?>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
              <div class="flex space-x-2">
                <a href="edit-student-detail.php?editid=<?php echo htmlentities($row->sid); ?>" class="text-blue-600 hover:text-blue-900">
                  <i class="fas fa-eye"></i>
                </a>
                <span class="text-gray-300">|</span>
                <a href="manage-students.php?delid=<?php echo ($row->sid); ?>" onclick="return confirmDelete()" class="text-red-600 hover:text-red-900">
                  <i class="fas fa-trash"></i>
                </a>
              </div>
            </td>
          </tr>
          <?php 
            $cnt++;
            }
          } else {
          ?>
          <tr>
            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
              No student admissions found between <?php echo date('M j, Y', strtotime($fdate)); ?> and <?php echo date('M j, Y', strtotime($tdate)); ?>
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
    
    <!-- Pagination -->
    <div class="px-6 py-3 border-t flex items-center justify-between">
      <div class="text-sm text-gray-500">
        Showing page <?php echo $pageno; ?> of <?php echo $total_pages; ?>
      </div>
      <div class="flex space-x-1">
        <a 
          href="?pageno=1" 
          class="px-3 py-1 rounded border <?php echo $pageno <= 1 ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-700 hover:bg-gray-50'; ?>"
          <?php echo $pageno <= 1 ? 'disabled' : ''; ?>
        >
          First
        </a>
        <a 
          href="<?php echo $pageno <= 1 ? '#' : '?pageno='.($pageno - 1); ?>" 
          class="px-3 py-1 rounded border <?php echo $pageno <= 1 ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-700 hover:bg-gray-50'; ?>"
          <?php echo $pageno <= 1 ? 'disabled' : ''; ?>
        >
          Previous
        </a>
        <a 
          href="<?php echo $pageno >= $total_pages ? '#' : '?pageno='.($pageno + 1); ?>" 
          class="px-3 py-1 rounded border <?php echo $pageno >= $total_pages ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-700 hover:bg-gray-50'; ?>"
          <?php echo $pageno >= $total_pages ? 'disabled' : ''; ?>
        >
          Next
        </a>
        <a 
          href="?pageno=<?php echo $total_pages; ?>" 
          class="px-3 py-1 rounded border <?php echo $pageno >= $total_pages ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-700 hover:bg-gray-50'; ?>"
          <?php echo $pageno >= $total_pages ? 'disabled' : ''; ?>
        >
          Last
        </a>
      </div>
    </div>
  </div>
  
  <!-- Summary Card -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
    <div class="bg-white rounded-xl shadow-sm p-6" data-aos="fade-up" data-aos-delay="100">
      <div class="flex items-center">
        <div class="p-3 rounded-full bg-blue-50 text-blue-600 mr-4">
          <i class="fas fa-users"></i>
        </div>
        <div>
          <p class="text-sm font-medium text-gray-500">Total Students</p>
          <?php
          $sql = "SELECT COUNT(*) as total FROM tblstudent WHERE date(DateofAdmission) BETWEEN '$fdate' AND '$tdate'";
          $query = $dbh->prepare($sql);
          $query->execute();
          $result = $query->fetch(PDO::FETCH_OBJ);
          ?>
          <h3 class="text-2xl font-bold mt-1"><?php echo $result->total; ?></h3>
        </div>
      </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6" data-aos="fade-up" data-aos-delay="200">
      <div class="flex items-center">
        <div class="p-3 rounded-full bg-green-50 text-green-600 mr-4">
          <i class="fas fa-calendar-day"></i>
        </div>
        <div>
          <p class="text-sm font-medium text-gray-500">Date Range</p>
          <h3 class="text-lg font-medium mt-1">
            <?php echo date('M j', strtotime($fdate)); ?> - <?php echo date('M j, Y', strtotime($tdate)); ?>
          </h3>
        </div>
      </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6" data-aos="fade-up" data-aos-delay="300">
      <div class="flex items-center">
        <div class="p-3 rounded-full bg-purple-50 text-purple-600 mr-4">
          <i class="fas fa-clock"></i>
        </div>
        <div>
          <p class="text-sm font-medium text-gray-500">Report Generated</p>
          <h3 class="text-lg font-medium mt-1">
            <?php echo date('M j, Y g:i A'); ?>
          </h3>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Include SweetAlert2 for beautiful alerts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  function confirmDelete() {
    return Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#4F46E5',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      return result.isConfirmed;
    });
  }
</script>

<?php 
  include_once('includes/footer.php');
}
?>