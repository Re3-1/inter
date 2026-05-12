<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsaid']) == 0) {
  header('location:logout.php');
} else {
  $pageTitle = 'Search Student';
  include_once('includes/header.php');
  
  // Code for deletion
  if(isset($_GET['delid'])) {
    $rid = intval($_GET['delid']);
    $sql = "DELETE FROM tblstudent WHERE ID=:rid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':rid', $rid, PDO::PARAM_STR);
    
    if($query->execute()) {
      echo '<script>
        Swal.fire({
          icon: "success",
          title: "Success!",
          text: "Student record has been deleted",
          showConfirmButton: true,
          timer: 3000
        }).then(() => {
          window.location.href = "manage-students.php";
        });
      </script>';
    } else {
      echo '<script>
        Swal.fire({
          icon: "error",
          title: "Error!",
          text: "Failed to delete student record",
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
              <span class="text-sm font-medium text-primary">Search Students</span>
            </div>
          </li>
        </ol>
      </nav>
    </div>
    <a href="add-student.php" class="mt-4 md:mt-0 inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
      <i class="fas fa-plus mr-2"></i> Add New Student
    </a>
  </div>
  
  <!-- Search Card -->
  <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6" data-aos="fade-up">
    <div class="px-6 py-4 border-b">
      <div class="flex items-center">
        <div class="p-2 bg-blue-100 text-blue-600 rounded-lg mr-4">
          <i class="fas fa-search text-xl"></i>
        </div>
        <div>
          <h3 class="text-lg font-semibold text-gray-800">Search Student Records</h3>
          <p class="text-sm text-gray-500">Search by Student ID or Name</p>
        </div>
      </div>
    </div>
    <form class="p-6" method="post">
      <div class="flex flex-col md:flex-row gap-4">
        <div class="flex-grow">
          <label for="searchdata" class="block text-sm font-medium text-gray-700 mb-1">Search Term*</label>
          <div class="relative">
            <input 
              type="text" 
              id="searchdata" 
              name="searchdata" 
              required 
              class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary transition-all"
              placeholder="Enter Student ID or Name"
            >
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
              <i class="fas fa-user-graduate text-gray-400"></i>
            </div>
          </div>
        </div>
        <div class="flex items-end">
          <button 
            type="submit" 
            name="search" 
            class="px-6 py-2.5 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors h-[42px]"
          >
            <i class="fas fa-search mr-2"></i> Search
          </button>
        </div>
      </div>
    </form>
  </div>

  <?php
  if(isset($_POST['search'])) { 
    $sdata = $_POST['searchdata'];
    $searchTerm = htmlspecialchars($sdata);
  ?>
  
  <!-- Results Card -->
  <div class="bg-white rounded-xl shadow-sm overflow-hidden" data-aos="fade-up" data-aos-delay="100">
    <div class="px-6 py-4 border-b">
      <div class="flex items-center justify-between">
        <div>
          <h4 class="text-lg font-semibold text-gray-800">Results for "<?php echo $searchTerm; ?>"</h4>
          <p class="text-sm text-gray-500"><?php echo $query->rowCount(); ?> records found</p>
        </div>
        <div class="text-sm text-gray-500">
          <?php
          if (isset($_GET['pageno'])) {
            $pageno = $_GET['pageno'];
          } else {
            $pageno = 1;
          }
          $no_of_records_per_page = 5;
          $offset = ($pageno-1) * $no_of_records_per_page;
          ?>
          Showing <?php echo $offset+1; ?>-<?php echo min($offset + $no_of_records_per_page, $query->rowCount()); ?> of <?php echo $query->rowCount(); ?>
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
          $ret = "SELECT ID FROM tblstudent";
          $query1 = $dbh->prepare($ret);
          $query1->execute();
          $total_rows = $query1->rowCount();
          $total_pages = ceil($total_rows / $no_of_records_per_page);
          
          $sql = "SELECT tblstudent.StuID, tblstudent.ID as sid, tblstudent.StudentName, 
                  tblstudent.StudentEmail, tblstudent.DateofAdmission, tblclass.ClassName, 
                  tblclass.Section FROM tblstudent 
                  JOIN tblclass ON tblclass.ID = tblstudent.StudentClass 
                  WHERE tblstudent.StuID LIKE :search OR tblstudent.StudentName LIKE :search 
                  LIMIT $offset, $no_of_records_per_page";
          $query = $dbh->prepare($sql);
          $query->bindValue(':search', '%'.$sdata.'%', PDO::PARAM_STR);
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
              <?php echo htmlentities($row->ClassName); ?> <?php echo htmlentities($row->Section); ?>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlentities($row->StudentName); ?></td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlentities($row->StudentEmail); ?></td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              <?php echo date('M d, Y', strtotime($row->DateofAdmission)); ?>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
              <div class="flex space-x-4">
                <a 
                  href="edit-student-detail.php?editid=<?php echo htmlentities($row->sid); ?>" 
                  class="text-blue-600 hover:text-blue-900"
                  title="View/Edit"
                >
                  <i class="fas fa-eye"></i>
                </a>
                <a 
                  href="#" 
                  onclick="confirmDelete(<?php echo htmlentities($row->sid); ?>)" 
                  class="text-red-600 hover:text-red-900"
                  title="Delete"
                >
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
            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">No records found matching your search</td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
      
      <!-- Pagination -->
      <div class="px-6 py-4 border-t flex items-center justify-between">
        <div class="text-sm text-gray-500">
          Page <?php echo $pageno; ?> of <?php echo $total_pages; ?>
        </div>
        <div class="flex space-x-2">
          <a 
            href="?pageno=1" 
            class="px-3 py-1 rounded border <?php echo $pageno <= 1 ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-700 hover:bg-gray-50'; ?>"
            <?php echo $pageno <= 1 ? 'disabled' : ''; ?>
          >
            First
          </a>
          <a 
            href="?pageno=<?php echo $pageno-1; ?>" 
            class="px-3 py-1 rounded border <?php echo $pageno <= 1 ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-700 hover:bg-gray-50'; ?>"
            <?php echo $pageno <= 1 ? 'disabled' : ''; ?>
          >
            Previous
          </a>
          <a 
            href="?pageno=<?php echo $pageno+1; ?>" 
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
  </div>
  <?php } ?>
</div>

<script>
  // Confirm delete function
  function confirmDelete(id) {
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = `manage-students.php?delid=${id}`;
      }
    });
  }
</script>

<?php 
  include_once('includes/footer.php');
}
?>