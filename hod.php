<?php
require_once 'session.php';
checkUserAccess();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>MIC</title>    <link rel="icon" type="image/png" sizes="32x32" href="image/icons/mkce_s.png">
    <link rel="stylesheet" href="global.css">
    <link rel="stylesheet" href="./utils/ui/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-5/bootstrap-5.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <style>
        #edit-bus-tab {
            background: linear-gradient(135deg, #4A90E2, #50E3C2);
            color: #fff;
        }

        #edit-bus-tab:not(.active) {
            background: #fff;
            color: #4A90E2;
        }

        #edit-bus-tab:hover:not(.active) {
            background: linear-gradient(135deg, #4A90E2, #50E3C2);
            color: #fff;
        }



        .custom-pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
        }

        .pagination-buttons {
            display: flex;
            gap: 5px;
        }

        .pagination-buttons button {
            padding: 4px 8px;
            font-size: 12px;
            border: 1px solid #dee2e6;
            background: white;
            cursor: pointer;
            border-radius: 3px;
        }

        .pagination-buttons button:hover {
            background: #e9ecef;
        }

        .pagination-buttons button.active {
            background: #0d6efd;
            color: white;
            border-color: #0d6efd;
        }

        .pagination-buttons button:focus {
            outline: none;
            box-shadow: none;
        }

        .entries-info {
            font-size: 14px;
        }

        .table-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .entries-dropdown {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .entries-dropdown select {
            padding: 6px 12px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            font-size: 14px;
        }

        .entries-dropdown label {
            font-size: 14px;
            margin-bottom: 0;
        }

        .search-box {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .search-input {
            padding: 6px 12px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            font-size: 14px;
            width: 200px;
        }

        .search-input:focus {
            outline: none;
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgb(13 110 253 / 25%);
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <?php include './utils/ui/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="content">
        <!-- Loader -->
        <div class="loader-container" id="loaderContainer">
            <div class="loader"></div>
        </div>

        <!-- Topbar -->
        <?php include './utils/ui/topbar.php'; ?>

        <!-- Breadcrumb -->
        <div class="breadcrumb-area">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Learning_Management</li>
                </ol>
            </nav>
        </div>

        <!-- Review Modal -->
        <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title" id="reviewModalLabel">Course Review</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Course Details -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Course Name:</label>
                                <p id="modalCourseName" class="border rounded p-2 bg-light"></p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Description:</label>
                                <p id="modalDescription" class="border rounded p-2 bg-light"></p>
                            </div>
                        </div>
                        <!-- <div class="row mb-4">
                            <div class="col-12">
                                <label class="form-label fw-bold">Syllabus:</label>
                                <p id="modalSyllabus" class="border rounded p-2 bg-light"></p>
                            </div>
                        </div> -->

                        <!-- Units and Topics will be dynamically inserted here -->
                        <div id="unitsContainer"></div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Course Video Modal -->
        <div class="modal fade" id="courseVideoModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Video</h5>
                        <button type="button" class="btn-close" onclick="closeVideoModal()"></button>
                    </div>
                    <div class="modal-body p-0">
                        <div class="ratio ratio-16x9">
                            <iframe id="courseVideoFrame" src="" allowfullscreen></iframe>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="closeVideoModal()">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reject Modal -->
        <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rejectModalLabel">Reject Course</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="rejectForm">
                            <input type="hidden" id="rejectCourseId">
                            <div class="mb-3">
                                <label for="rejectReason" class="form-label">Reason for Rejection</label>
                                <textarea class="form-control" id="rejectReason" rows="3" required></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger" onclick="submitRejection()">Reject</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Syllabus Modal -->
        <div class="modal fade" id="syllabusModal" tabindex="-1" aria-labelledby="syllabusModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="syllabusModalLabel">Course Syllabus</h5>
                        <button type="button" class="btn-close" onclick="closeSyllabusModal()"></button>
                    </div>
                    <div class="modal-body p-0">
                        <iframe id="syllabusFrame" src="" style="width: 100%; height: 80vh;" frameborder="0"></iframe>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="closeSyllabusModal()">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="container-fluid">
            <div class="custom-tabs">
                <!-- Tab Navigation -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" data-bs-toggle="tab" id="update-bus-tab" href="#publication" role="tab" aria-selected="true">
                            <span class="hidden-xs-down"><i class="fas fa-book tab-icon"></i><b> Course Approvals</b></span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" id="edit-bus-tab" href="#available" role="tab" aria-selected="false">
                            <span class="hidden-xs-down"><i class="fas fa-graduation-cap tab-icon"></i><b> Available Courses</b></span>
                        </a>
                    </li>
                </ul>
                <!-- Tab Content -->
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="publication" role="tabpanel">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="mb-0">Course Approval Information</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="approval_table" class="table table-striped table-bordered custom-table">
                                                <thead>
                                                    <tr>
                                                        <th style="color: white;"><b>S.No</b></th>
                                                        <th style="color: white;"><b>Course Name</b></th>
                                                        <th style="color: white;"><b>Staff Name</b></th>
                                                        <th style="color: white;"><b>Academic Year</b></th>
                                                        <th style="color: white;"><b>Semester</b></th>
                                                        <th style="color: white;"><b>Status</b></th>
                                                        <th style="color: white;"><b>Syllabus</b></th>
                                                        <th style="color: white;"><b>Actions</b></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php require_once 'backend.php'; ?>
                                                    <?php echo getApprovalTableHTML(); ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="available" role="tabpanel">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="mb-0">Available Courses</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="available_table" class="table table-striped table-bordered custom-table">
                                                <thead>
                                                    <tr>
                                                        <th style="color: white;"><b>S.No</b></th>
                                                        <th style="color: white;"><b>Course Name</b></th>
                                                        <th style="color: white;"><b>Staff Name</b></th>
                                                        <th style="color: white;"><b>Academic Year</b></th>
                                                        <th style="color: white;"><b>Semester</b></th>
                                                        <th style="color: white;"><b>Syllabus</b></th>
                                                        <th style="color: white;"><b>Action</b></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php echo getAvailableTableHTML(); ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <?php include './utils/ui/footer.php'; ?>
    </div>
    <script src="hodHelper.js"></script>
</body>

</html>