<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsaid']) == 0) {
    header('location:logout.php');
} else {
    $pageTitle = 'Manage Students';
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
                    text: "Student record deleted successfully",
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
                            <span class="text-sm font-medium text-primary">Manage Students</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
        <div class="flex space-x-2 mt-4 md:mt-0">
            <a href="add-students.php" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                <i class="fas fa-user-plus mr-2"></i> Add New Student
            </a>
            <button class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors" id="exportBtn">
                <i class="fas fa-file-export mr-2"></i> Export
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Students</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">
                        <?php 
                        $sql = "SELECT COUNT(*) as total FROM tblstudent";
                        $query = $dbh->prepare($sql);
                        $query->execute();
                        $result = $query->fetch(PDO::FETCH_OBJ);
                        echo $result->total;
                        ?>
                    </h3>
                </div>
                <div class="p-3 bg-blue-100 text-blue-600 rounded-lg">
                    <i class="fas fa-users text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">New This Month</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">
                        <?php 
                        $sql = "SELECT COUNT(*) as total FROM tblstudent WHERE MONTH(DateofAdmission) = MONTH(CURRENT_DATE())";
                        $query = $dbh->prepare($sql);
                        $query->execute();
                        $result = $query->fetch(PDO::FETCH_OBJ);
                        echo $result->total;
                        ?>
                    </h3>
                </div>
                <div class="p-3 bg-green-100 text-green-600 rounded-lg">
                    <i class="fas fa-user-plus text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Active Classes</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">
                        <?php 
                        $sql = "SELECT COUNT(DISTINCT StudentClass) as total FROM tblstudent";
                        $query = $dbh->prepare($sql);
                        $query->execute();
                        $result = $query->fetch(PDO::FETCH_OBJ);
                        echo $result->total;
                        ?>
                    </h3>
                </div>
                <div class="p-3 bg-purple-100 text-purple-600 rounded-lg">
                    <i class="fas fa-chalkboard text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Latest Admission</p>
                    <h3 class="text-lg font-semibold text-gray-800 mt-1">
                        <?php
                        $sql = "SELECT StudentName FROM tblstudent ORDER BY ID DESC LIMIT 1";
                        $query = $dbh->prepare($sql);
                        $query->execute();
                        $result = $query->fetch(PDO::FETCH_OBJ);
                        echo $result ? htmlentities($result->StudentName) : 'N/A';
                        ?>
                    </h3>
                </div>
                <div class="p-3 bg-yellow-100 text-yellow-600 rounded-lg">
                    <i class="fas fa-history text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Table Card -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <h3 class="text-lg font-semibold text-gray-800 mb-2 md:mb-0">Student Records</h3>
                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                    <div class="relative">
                        <input 
                            type="text" 
                            placeholder="Search students..." 
                            class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                            id="searchInput"
                        >
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                    <select class="px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary transition-all" id="classFilter">
                        <option value="">All Classes</option>
                        <?php
                        $sql = "SELECT DISTINCT tblclass.ID, tblclass.ClassName, tblclass.Section FROM tblclass JOIN tblstudent ON tblclass.ID = tblstudent.StudentClass";
                        $query = $dbh->prepare($sql);
                        $query->execute();
                        $classes = $query->fetchAll(PDO::FETCH_OBJ);
                        
                        foreach($classes as $class) {
                            echo '<option value="'.htmlentities($class->ClassName).' '.htmlentities($class->Section).'">'.htmlentities($class->ClassName).' '.htmlentities($class->Section).'</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200" id="studentTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer sort-header" data-column="0">
                            <div class="flex items-center">
                                #
                                <i class="fas fa-sort ml-1 text-gray-400"></i>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer sort-header" data-column="1">
                            <div class="flex items-center">
                                Student ID
                                <i class="fas fa-sort ml-1 text-gray-400"></i>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer sort-header" data-column="2">
                            <div class="flex items-center">
                                Class
                                <i class="fas fa-sort ml-1 text-gray-400"></i>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer sort-header" data-column="3">
                            <div class="flex items-center">
                                Student Name
                                <i class="fas fa-sort ml-1 text-gray-400"></i>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer sort-header" data-column="4">
                            <div class="flex items-center">
                                Email
                                <i class="fas fa-sort ml-1 text-gray-400"></i>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer sort-header" data-column="5">
                            <div class="flex items-center">
                                Admission Date
                                <i class="fas fa-sort ml-1 text-gray-400"></i>
                            </div>
                        </th>
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
                    
                    $no_of_records_per_page = 15;
                    $offset = ($pageno-1) * $no_of_records_per_page;
                    
                    $ret = "SELECT ID FROM tblstudent";
                    $query1 = $dbh->prepare($ret);
                    $query1->execute();
                    $results1 = $query1->fetchAll(PDO::FETCH_OBJ);
                    $total_rows = $query1->rowCount();
                    $total_pages = ceil($total_rows / $no_of_records_per_page);
                    
                    $sql = "SELECT tblstudent.StuID, tblstudent.ID as sid, tblstudent.StudentName, tblstudent.StudentEmail, 
                            tblstudent.DateofAdmission, tblclass.ClassName, tblclass.Section 
                            FROM tblstudent JOIN tblclass ON tblclass.ID = tblstudent.StudentClass 
                            LIMIT $offset, $no_of_records_per_page";
                    $query = $dbh->prepare($sql);
                    $query->execute();
                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                    
                    $cnt = 1;
                    if($query->rowCount() > 0) {
                        foreach($results as $row) {
                    ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlentities($cnt + $offset); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlentities($row->StuID); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                <?php echo htmlentities($row->ClassName); ?> <?php echo htmlentities($row->Section); ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-gray-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-user text-gray-500"></i>
                                </div>
                                <div class="text-sm font-medium text-gray-900"><?php echo htmlentities($row->StudentName); ?></div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlentities($row->StudentEmail); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo date('M d, Y', strtotime($row->DateofAdmission)); ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="edit-student-detail.php?editid=<?php echo htmlentities($row->sid); ?>" class="text-primary hover:text-primary-dark" data-tooltip="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#" onclick="confirmDelete(<?php echo htmlentities($row->sid); ?>)" class="text-red-600 hover:text-red-900" data-tooltip="Delete">
                                    <i class="fas fa-trash"></i>
                                </a>
                                <a href="student-profile.php?studentid=<?php echo htmlentities($row->sid); ?>" class="text-purple-600 hover:text-purple-900" data-tooltip="Profile">
                                    <i class="fas fa-user-circle"></i>
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
                            <div class="flex flex-col items-center justify-center py-8">
                                <i class="fas fa-user-graduate text-4xl text-gray-300 mb-2"></i>
                                <p class="text-gray-500">No student records found</p>
                                <a href="add-student.php" class="mt-2 text-primary hover:underline">Add your first student</a>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t flex flex-col sm:flex-row items-center justify-between">
            <div class="text-sm text-gray-500 mb-2 sm:mb-0">
                Showing <span class="font-medium"><?php echo $offset + 1; ?></span> to 
                <span class="font-medium"><?php echo min($offset + $no_of_records_per_page, $total_rows); ?></span> of 
                <span class="font-medium"><?php echo $total_rows; ?></span> students
            </div>
            <nav class="flex space-x-2" aria-label="Pagination">
                <a 
                    href="?pageno=1" 
                    class="px-3 py-1 rounded-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 <?php echo $pageno <= 1 ? 'opacity-50 cursor-not-allowed' : ''; ?>"
                    <?php echo $pageno <= 1 ? 'tabindex="-1" aria-disabled="true"' : ''; ?>
                >
                    <i class="fas fa-angle-double-left"></i>
                </a>
                <a 
                    href="<?php echo $pageno <= 1 ? '#' : "?pageno=".($pageno - 1); ?>" 
                    class="px-3 py-1 rounded-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 <?php echo $pageno <= 1 ? 'opacity-50 cursor-not-allowed' : ''; ?>"
                    <?php echo $pageno <= 1 ? 'tabindex="-1" aria-disabled="true"' : ''; ?>
                >
                    <i class="fas fa-angle-left"></i>
                </a>
                <?php
                // Display page numbers
                $visible_pages = 3;
                $start = max(1, $pageno - floor($visible_pages/2));
                $end = min($total_pages, $start + $visible_pages - 1);
                
                if($start > 1) {
                    echo '<span class="px-3 py-1 text-gray-500">...</span>';
                }
                
                for($i = $start; $i <= $end; $i++) {
                    echo '<a href="?pageno='.$i.'" class="px-3 py-1 rounded-md border text-sm font-medium '.($pageno == $i ? 'bg-primary text-white border-primary' : 'bg-white text-gray-500 border-gray-300 hover:bg-gray-50').'">'.$i.'</a>';
                }
                
                if($end < $total_pages) {
                    echo '<span class="px-3 py-1 text-gray-500">...</span>';
                }
                ?>
                <a 
                    href="<?php echo $pageno >= $total_pages ? '#' : "?pageno=".($pageno + 1); ?>" 
                    class="px-3 py-1 rounded-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 <?php echo $pageno >= $total_pages ? 'opacity-50 cursor-not-allowed' : ''; ?>"
                    <?php echo $pageno >= $total_pages ? 'tabindex="-1" aria-disabled="true"' : ''; ?>
                >
                    <i class="fas fa-angle-right"></i>
                </a>
                <a 
                    href="?pageno=<?php echo $total_pages; ?>" 
                    class="px-3 py-1 rounded-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 <?php echo $pageno >= $total_pages ? 'opacity-50 cursor-not-allowed' : ''; ?>"
                    <?php echo $pageno >= $total_pages ? 'tabindex="-1" aria-disabled="true"' : ''; ?>
                >
                    <i class="fas fa-angle-double-right"></i>
                </a>
            </nav>
        </div>
    </div>
</div>

<script>
    // Confirm delete function
    function confirmDelete(id) {
        Swal.fire({
            title: 'Delete Student Record?',
            text: "This will permanently delete all student data including attendance and marks!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `manage-students.php?delid=${id}`;
            }
        });
    }
    
    // Search functionality
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const input = this.value.toLowerCase();
        const rows = document.querySelectorAll('#studentTable tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(input) ? '' : 'none';
        });
    });
    
    // Class filter functionality
    document.getElementById('classFilter').addEventListener('change', function() {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('#studentTable tbody tr');
        
        rows.forEach(row => {
            const classText = row.cells[2].textContent.toLowerCase();
            if(filter === '' || classText.includes(filter)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
    
    // Sort functionality
    document.querySelectorAll('.sort-header').forEach(header => {
        header.addEventListener('click', function() {
            const column = parseInt(this.getAttribute('data-column'));
            const table = document.getElementById('studentTable');
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr'));
            
            // Determine sort direction
            const isAsc = this.classList.contains('asc');
            this.classList.toggle('asc', !isAsc);
            
            // Update sort icon
            const icon = this.querySelector('i');
            icon.className = isAsc ? 'fas fa-sort-up ml-1' : 'fas fa-sort-down ml-1';
            
            // Sort rows
            rows.sort((a, b) => {
                const aText = a.cells[column].textContent.trim().toLowerCase();
                const bText = b.cells[column].textContent.trim().toLowerCase();
                
                if (column === 0 || column === 5) { // For numeric or date columns
                    return isAsc ? aText.localeCompare(bText, undefined, {numeric: true}) 
                                : bText.localeCompare(aText, undefined, {numeric: true});
                } else { // For text columns
                    return isAsc ? aText.localeCompare(bText) 
                                : bText.localeCompare(aText);
                }
            });
            
            // Re-append rows in sorted order
            rows.forEach(row => tbody.appendChild(row));
        });
    });
    
    // Export functionality
    document.getElementById('exportBtn').addEventListener('click', function() {
        Swal.fire({
            title: 'Export Student Data',
            text: 'Choose export format:',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Excel',
            cancelButtonText: 'PDF',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'export-students.php?format=excel';
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                window.location.href = 'export-students.php?format=pdf';
            }
        });
    });
</script>

<?php include_once('includes/footer.php'); ?>
<?php } ?>