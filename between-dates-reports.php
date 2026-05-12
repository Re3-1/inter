<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsaid']) == 0) {
  header('location:logout.php');
} else {
  $pageTitle = 'Generate Date Range Report';
  include_once('includes/header.php');
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
              <span class="text-sm font-medium text-primary">Date Range Report</span>
            </div>
          </li>
        </ol>
      </nav>
    </div>
    <a href="manage-reports.php" class="mt-4 md:mt-0 inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
      <i class="fas fa-list mr-2"></i> View All Reports
    </a>
  </div>
  
  <!-- Report Selection Card -->
  <div class="bg-white rounded-xl shadow-sm overflow-hidden max-w-2xl mx-auto" data-aos="fade-up">
    <div class="px-6 py-4 border-b">
      <div class="flex items-center">
        <div class="p-2 bg-blue-100 text-blue-600 rounded-lg mr-4">
          <i class="fas fa-calendar-alt text-xl"></i>
        </div>
        <div>
          <h3 class="text-lg font-semibold text-gray-800">Select Date Range</h3>
          <p class="text-sm text-gray-500">Choose the period for your report</p>
        </div>
      </div>
    </div>
    
    <form class="p-6" method="post" action="between-date-reprtsdetails.php">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- From Date -->
        <div>
          <label for="fromdate" class="block text-sm font-medium text-gray-700 mb-1">From Date*</label>
          <div class="relative">
            <input 
              type="date" 
              id="fromdate" 
              name="fromdate" 
              class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary transition-all"
              required
              max="<?php echo date('Y-m-d'); ?>"
              onchange="setMinToDate()"
            >
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
              <i class="fas fa-calendar text-gray-400"></i>
            </div>
          </div>
        </div>
        
        <!-- To Date -->
        <div>
          <label for="todate" class="block text-sm font-medium text-gray-700 mb-1">To Date*</label>
          <div class="relative">
            <input 
              type="date" 
              id="todate" 
              name="todate" 
              class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary transition-all"
              required
              max="<?php echo date('Y-m-d'); ?>"
            >
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
              <i class="fas fa-calendar text-gray-400"></i>
            </div>
          </div>
        </div>
        
        <!-- Report Type (if needed in future) -->
        <!-- <div class="md:col-span-2">
          <label for="reporttype" class="block text-sm font-medium text-gray-700 mb-1">Report Type</label>
          <select 
            id="reporttype" 
            name="reporttype" 
            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary appearance-none transition-all"
          >
            <option value="admissions">Student Admissions</option>
            <option value="attendance">Attendance</option>
            <option value="performance">Performance</option>
          </select>
        </div> -->
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
          <i class="fas fa-file-alt mr-2"></i> Generate Report
        </button>
      </div>
    </form>
  </div>
  
  <!-- Quick Stats -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
    <div class="bg-white rounded-xl shadow-sm p-6" data-aos="fade-up" data-aos-delay="100">
      <div class="flex items-center">
        <div class="p-3 rounded-full bg-blue-50 text-blue-600 mr-4">
          <i class="fas fa-users text-xl"></i>
        </div>
        <div>
          <p class="text-sm font-medium text-gray-500">Total Students</p>
          <?php
          $sql = "SELECT COUNT(*) as total FROM tblstudent";
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
          <i class="fas fa-calendar-day text-xl"></i>
        </div>
        <div>
          <p class="text-sm font-medium text-gray-500">Today's Date</p>
          <h3 class="text-lg font-medium mt-1">
            <?php echo date('M j, Y'); ?>
          </h3>
        </div>
      </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6" data-aos="fade-up" data-aos-delay="300">
      <div class="flex items-center">
        <div class="p-3 rounded-full bg-purple-50 text-purple-600 mr-4">
          <i class="fas fa-clock text-xl"></i>
        </div>
        <div>
          <p class="text-sm font-medium text-gray-500">Current Academic Year</p>
          <h3 class="text-lg font-medium mt-1">
            <?php echo date('Y'); ?>-<?php echo date('Y')+1; ?>
          </h3>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  // Set minimum to date based on from date selection
  function setMinToDate() {
    const fromDate = document.getElementById('fromdate').value;
    if (fromDate) {
      document.getElementById('todate').min = fromDate;
      
      // If to date is before from date, reset it
      const toDate = document.getElementById('todate').value;
      if (toDate && toDate < fromDate) {
        document.getElementById('todate').value = fromDate;
      }
    }
  }
  
  // Set max dates to today by default
  document.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('fromdate').max = today;
    document.getElementById('todate').max = today;
  });
</script>

<?php 
  include_once('includes/footer.php');
}
?>