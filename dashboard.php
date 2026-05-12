<?php
session_start();
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsaid']) == 0) {
  header('location:logout.php');
} else {
  $pageTitle = 'Dashboard';
  include_once('includes/header.php');
  
  // Get counts for dashboard cards
  $sql1 = "SELECT * FROM tblclass";
  $query1 = $dbh->prepare($sql1);
  $query1->execute();
  $totclass = $query1->rowCount();
  
  $sql2 = "SELECT * FROM tblstudent";
  $query2 = $dbh->prepare($sql2);
  $query2->execute();
  $totstu = $query2->rowCount();
  
  $sql3 = "SELECT * FROM tblnotice";
  $query3 = $dbh->prepare($sql3);
  $query3->execute();
  $totnotice = $query3->rowCount();
  
  $sql4 = "SELECT * FROM tblpublicnotice";
  $query4 = $dbh->prepare($sql4);
  $query4->execute();
  $totpublicnotice = $query4->rowCount();
?>
  
<div class="px-6 py-4">
  <!-- Welcome Banner -->
  <div class="gradient-bg rounded-xl p-6 mb-6 text-white" data-aos="fade-down">
    <div class="flex flex-col md:flex-row items-center justify-between">
      <div>
        <h1 class="text-2xl md:text-3xl font-bold mb-2">Welcome back, <?php echo htmlentities($row->AdminName); ?>!</h1>
        <p class="opacity-90">Here's what's happening with your institution today.</p>
      </div>
      <div class="mt-4 md:mt-0">
        <div class="flex items-center bg-white bg-opacity-20 rounded-full px-4 py-2">
          <i class="fas fa-calendar-day mr-2"></i>
          <span><?php echo date('l, F j, Y'); ?></span>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Stats Cards -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Total Classes -->
    <div class="bg-white rounded-xl shadow-sm p-6 card-hover transition-all" data-aos="fade-up" data-aos-delay="100">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm font-medium text-gray-500">Total Classes</p>
          <h3 class="text-2xl font-bold mt-1"><?php echo $totclass; ?></h3>
        </div>
        <div class="p-3 rounded-full bg-indigo-50 text-indigo-600">
          <i class="fas fa-layer-group text-xl"></i>
        </div>
      </div>
      <a href="manage-class.php" class="inline-flex items-center mt-4 text-sm font-medium text-indigo-600 hover:text-indigo-800">
        View all classes
        <i class="fas fa-arrow-right ml-1"></i>
      </a>
    </div>
    
    <!-- Total Students -->
    <div class="bg-white rounded-xl shadow-sm p-6 card-hover transition-all" data-aos="fade-up" data-aos-delay="200">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm font-medium text-gray-500">Total Students</p>
          <h3 class="text-2xl font-bold mt-1"><?php echo $totstu; ?></h3>
        </div>
        <div class="p-3 rounded-full bg-green-50 text-green-600">
          <i class="fas fa-users text-xl"></i>
        </div>
      </div>
      <a href="manage-students.php" class="inline-flex items-center mt-4 text-sm font-medium text-green-600 hover:text-green-800">
        View all students
        <i class="fas fa-arrow-right ml-1"></i>
      </a>
    </div>
    
    <!-- Class Notices -->
    <div class="bg-white rounded-xl shadow-sm p-6 card-hover transition-all" data-aos="fade-up" data-aos-delay="300">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm font-medium text-gray-500">Class Notices</p>
          <h3 class="text-2xl font-bold mt-1"><?php echo $totnotice; ?></h3>
        </div>
        <div class="p-3 rounded-full bg-blue-50 text-blue-600">
          <i class="fas fa-bell text-xl"></i>
        </div>
      </div>
      <a href="manage-notice.php" class="inline-flex items-center mt-4 text-sm font-medium text-blue-600 hover:text-blue-800">
        View all notices
        <i class="fas fa-arrow-right ml-1"></i>
      </a>
    </div>
    
    <!-- Public Notices -->
    <div class="bg-white rounded-xl shadow-sm p-6 card-hover transition-all" data-aos="fade-up" data-aos-delay="400">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm font-medium text-gray-500">Public Notices</p>
          <h3 class="text-2xl font-bold mt-1"><?php echo $totpublicnotice; ?></h3>
        </div>
        <div class="p-3 rounded-full bg-purple-50 text-purple-600">
          <i class="fas fa-bullhorn text-xl"></i>
        </div>
      </div>
      <a href="manage-public-notice.php" class="inline-flex items-center mt-4 text-sm font-medium text-purple-600 hover:text-purple-800">
        View all notices
        <i class="fas fa-arrow-right ml-1"></i>
      </a>
    </div>
  </div>
  
  <!-- Charts Row -->
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Pie Chart -->
    <div class="bg-white rounded-xl shadow-sm p-6" data-aos="fade-up">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold">System Distribution</h3>
        <div class="flex space-x-2">
          <button class="px-3 py-1 text-xs bg-gray-100 rounded-md">Weekly</button>
          <button class="px-3 py-1 text-xs bg-indigo-600 text-white rounded-md">Monthly</button>
          <button class="px-3 py-1 text-xs bg-gray-100 rounded-md">Yearly</button>
        </div>
      </div>
      <div class="h-80">
        <canvas id="pieChart"></canvas>
      </div>
    </div>
    
    <!-- Bar Chart -->
    <div class="bg-white rounded-xl shadow-sm p-6" data-aos="fade-up" data-aos-delay="100">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold">Recent Activity</h3>
        <div class="text-sm text-gray-500">Last 7 days</div>
      </div>
      <div class="h-80">
        <canvas id="barChart"></canvas>
      </div>
    </div>
  </div>
  
  <!-- Recent Notices -->
  <div class="bg-white rounded-xl shadow-sm overflow-hidden" data-aos="fade-up">
    <div class="px-6 py-4 border-b">
      <h3 class="text-lg font-semibold">Recent Notices</h3>
    </div>
    <div class="divide-y">
      <?php
      $sql = "SELECT * FROM tblnotice ORDER BY ID DESC LIMIT 5";
      $query = $dbh->prepare($sql);
      $query->execute();
      $results = $query->fetchAll(PDO::FETCH_OBJ);
      
      if($query->rowCount() > 0) {
        foreach($results as $notice) {
      ?>
      <div class="p-4 hover:bg-gray-50 transition-colors">
        <div class="flex items-start">
          <div class="p-2 bg-indigo-50 rounded-full text-indigo-600 mr-4">
            <i class="fas fa-bell"></i>
          </div>
          <div class="flex-1">
            <h4 class="font-medium"><?php echo htmlentities($notice->NoticeTitle); ?></h4>
            <p class="text-sm text-gray-500 mt-1"><?php echo substr(htmlentities($notice->NoticeMsg), 0, 100); ?>...</p>
            <div class="flex items-center mt-2 text-xs text-gray-400">
              <i class="fas fa-calendar-alt mr-1"></i>
              <span><?php echo htmlentities($notice->CreationDate); ?></span>
              <span class="mx-2">•</span>
              <i class="fas fa-clock mr-1"></i>
              <span><?php echo htmlentities($notice->ClassId); ?></span>
            </div>
          </div>
          <a href="manage-notice.php" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
            View
          </a>
        </div>
      </div>
      <?php }} else { ?>
      <div class="p-6 text-center text-gray-500">
        <i class="fas fa-info-circle text-2xl mb-2"></i>
        <p>No recent notices found</p>
      </div>
      <?php } ?>
    </div>
    <div class="px-6 py-3 border-t text-right">
      <a href="manage-notice.php" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
        View all notices <i class="fas fa-arrow-right ml-1"></i>
      </a>
    </div>
  </div>
</div>

<script>
  // Pie Chart
  const pieCtx = document.getElementById('pieChart').getContext('2d');
  const pieChart = new Chart(pieCtx, {
    type: 'pie',
    data: {
      labels: ['Classes', 'Students', 'Class Notices', 'Public Notices'],
      datasets: [{
        data: [<?php echo $totclass; ?>, <?php echo $totstu; ?>, <?php echo $totnotice; ?>, <?php echo $totpublicnotice; ?>],
        backgroundColor: [
          '#4F46E5',
          '#10B981',
          '#3B82F6',
          '#8B5CF6'
        ],
        borderWidth: 0
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'right',
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              let label = context.label || '';
              let value = context.raw || 0;
              let total = context.dataset.data.reduce((a, b) => a + b, 0);
              let percentage = Math.round((value / total) * 100);
              return `${label}: ${value} (${percentage}%)`;
            }
          }
        }
      }
    }
  });
  
  // Bar Chart
  const barCtx = document.getElementById('barChart').getContext('2d');
  const barChart = new Chart(barCtx, {
    type: 'bar',
    data: {
      labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
      datasets: [
        {
          label: 'Students Added',
          data: [12, 19, 8, 15, 12, 5, 0],
          backgroundColor: '#4F46E5',
          borderRadius: 4
        },
        {
          label: 'Notices Posted',
          data: [3, 5, 2, 4, 6, 1, 0],
          backgroundColor: '#10B981',
          borderRadius: 4
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        x: {
          grid: {
            display: false
          }
        },
        y: {
          beginAtZero: true,
          grid: {
            drawBorder: false
          }
        }
      },
      plugins: {
        legend: {
          position: 'top',
        }
      }
    }
  });
</script>

<?php 
  include_once('includes/footer.php');
}
?>