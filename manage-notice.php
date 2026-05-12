<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsaid']) == 0) {
  header('location:logout.php');
} else {
  $pageTitle = 'Manage Notices';
  include_once('includes/header.php');
  
  // Code for deletion
  if(isset($_GET['delid'])) {
    $rid = intval($_GET['delid']);
    $sql = "DELETE FROM tblnotice WHERE ID=:rid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':rid', $rid, PDO::PARAM_STR);
    
    if($query->execute()) {
      echo '<script>
        Swal.fire({
          icon: "success",
          title: "Success!",
          text: "Notice has been deleted successfully",
          showConfirmButton: true,
          timer: 3000
        }).then(() => {
          window.location.href = "manage-notice.php";
        });
      </script>';
    } else {
      echo '<script>
        Swal.fire({
          icon: "error",
          title: "Error!",
          text: "Failed to delete notice",
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
              <span class="text-sm font-medium text-primary">Manage Notices</span>
            </div>
          </li>
        </ol>
      </nav>
    </div>
    <a href="add-notice.php" class="mt-4 md:mt-0 inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
      <i class="fas fa-plus mr-2"></i> Add New Notice
    </a>
  </div>
  
  <!-- Notices Table Card -->
  <div class="bg-white rounded-xl shadow-sm overflow-hidden" data-aos="fade-up">
    <div class="px-6 py-4 border-b">
      <div class="flex items-center">
        <div class="p-2 bg-blue-100 text-blue-600 rounded-lg mr-4">
          <i class="fas fa-bullhorn text-xl"></i>
        </div>
        <div>
          <h3 class="text-lg font-semibold text-gray-800">All Notices</h3>
          <p class="text-sm text-gray-500">View and manage all school notices</p>
        </div>
      </div>
    </div>
    
    <div class="overflow-x-auto">
      <?php
      if (isset($_GET['pageno'])) {
        $pageno = $_GET['pageno'];
      } else {
        $pageno = 1;
      }
      
      // Pagination settings
      $no_of_records_per_page = 15;
      $offset = ($pageno-1) * $no_of_records_per_page;
      
      $ret = "SELECT ID FROM tblnotice";
      $query1 = $dbh->prepare($ret);
      $query1->execute();
      $total_rows = $query1->rowCount();
      $total_pages = ceil($total_rows / $no_of_records_per_page);
      
      $sql = "SELECT tblclass.ID, tblclass.ClassName, tblclass.Section, tblnotice.NoticeTitle, 
              tblnotice.CreationDate, tblnotice.ClassId, tblnotice.ID as nid 
              FROM tblnotice JOIN tblclass ON tblclass.ID = tblnotice.ClassId 
              LIMIT $offset, $no_of_records_per_page";
      $query = $dbh->prepare($sql);
      $query->execute();
      $results = $query->fetchAll(PDO::FETCH_OBJ);
      ?>
      
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notice Title</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Section</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notice Date</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <?php
          $cnt = 1;
          if($query->rowCount() > 0) {
            foreach($results as $row) {
          ?>
          <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlentities($cnt); ?></td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm font-medium text-gray-900"><?php echo htmlentities($row->NoticeTitle); ?></div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlentities($row->ClassName); ?></td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlentities($row->Section); ?></td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              <?php echo date('M d, Y', strtotime($row->CreationDate)); ?>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
              <div class="flex space-x-2">
                <a href="edit-notice-detail.php?editid=<?php echo htmlentities($row->nid); ?>" class="text-primary hover:text-primary-dark">
                  <i class="fas fa-edit"></i> Edit
                </a>
                <a href="#" onclick="confirmDelete(<?php echo htmlentities($row->nid); ?>)" class="text-red-600 hover:text-red-900">
                  <i class="fas fa-trash"></i> Delete
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
            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No notices found</td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
      
      <!-- Pagination -->
      <div class="px-6 py-4 border-t flex items-center justify-between">
        <div class="text-sm text-gray-500">
          Showing <span class="font-medium"><?php echo $offset+1; ?></span> to 
          <span class="font-medium"><?php echo min($offset + $no_of_records_per_page, $total_rows); ?></span> of 
          <span class="font-medium"><?php echo $total_rows; ?></span> notices
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
        window.location.href = `manage-notice.php?delid=${id}`;
      }
    });
  }
  
  // Initialize tooltips
  document.addEventListener('DOMContentLoaded', function() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
    });
  });
</script>

<?php 
  include_once('includes/footer.php');
}
?>