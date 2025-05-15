<?php
require_once 'session.php';
checkUserAccess();
$uid = $_SESSION['user']['uid'];
?>
<!DOCTYPE html>
<html lang="en">

<head>    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SnapCert</title>
    <link rel="icon" type="image/png" sizes="32x32" href="image/icons/mkce_s.png">
    <link rel="stylesheet" href="global.css">
    <link rel="stylesheet" href="./utils/ui/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-5/bootstrap-5.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

        /* Update Bus Tab Styling */
        #update-bus-tab {
            background: linear-gradient(135deg, #1E90FF, #87CEFA);
            color: #fff;
        }

        #update-bus-tab:not(.active) {
            background: #fff;
            color: #1E90FF;
        }

        #update-bus-tab:hover:not(.active) {
            background: linear-gradient(135deg, #1E90FF, #87CEFA);
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

    <style>
        .hover-text {
            position: relative;
            display: inline-block;
            cursor: pointer;
        }

        .hover-text .tooltip-text {
            visibility: hidden;
            width: auto;
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 5px 10px;
            border-radius: 4px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            /* Position above the icon */
            left: 50%;
            transform: translateX(-50%);
            opacity: 0;
            transition: opacity 0.2s;
        }

        .hover-text .tooltip-text::after {
            content: "";
            position: absolute;
            top: 100%;
            /* Arrow below the tooltip */
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: #333 transparent transparent transparent;
        }

        .hover-text:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }

        /* General card styling */
        .card {
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }



        /* Header Styling */


        /* Card Body */
        .card-body {
            background-color: #f8f9fa;
            /* Subtle background for contrast */
            padding: 20px;
            border-radius: 0 0 8px 8px;
        }

        /* Button Styling */
        .btn-outline-primary {
            border: 1px solid #2575fc;
            color: #2575fc;
            font-weight: 600;
        }

        .btn-outline-primary:hover {
            background-color: #2575fc;
            color: #fff;
        }



        .text-primary {
            color: #2575fc !important;
        }

        .text-success {
            color: #28a745 !important;
        }

        /* Hover Effects */
        .hover-card:hover {
            transform: scale(1.02);
            transition: 0.3s ease-in-out;
        }

        .is-invalid {
            border: 2px solid red;
        }



        /* added css */
        .bg-gradient-primary {
            background: linear-gradient(90deg, #007bff, #0056b3);
        }

        .shadow-sm {
            box-shadow: 0 .125rem .25rem rgba(0, 0, 0, 0.075);
        }

        /* Hover effect for cards */
        .hover-shadow:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transform: translateY(-5px);
            transition: all 0.3s ease;
        }

        /* Hover effect for buttons */
        .hover-scale:hover {
            transform: scale(1.05);
            transition: transform 0.3s ease;
        }





        :root {
            --sidebar-width: 250px;
            --sidebar-collapsed-width: 70px;
            --topbar-height: 60px;
            --footer-height: 60px;
            --primary-color: #4e73df;
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --dark-bg: #1a1c23;
            --light-bg: #f8f9fc;
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* General Styles with Enhanced Typography */
        body {
            min-height: 100vh;
            margin: 0;
            background: var(--light-bg);
            overflow-x: hidden;
            padding-bottom: var(--footer-height);
            position: relative;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        /* Content Area Styles */
        .content {
            margin-left: var(--sidebar-width);
            padding-top: var(--topbar-height);
            transition: all 0.3s ease;
            min-height: 100vh;
        }

        /* Content Navigation */
        .content-nav {
            background: linear-gradient(45deg, #4e73df, #1cc88a);
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .content-nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            gap: 20px;
            overflow-x: auto;
        }

        .content-nav li a {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .content-nav li a:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .sidebar.collapsed+.content {
            margin-left: var(--sidebar-collapsed-width);
        }

        .breadcrumb-area {
            background: white;
            border-radius: 10px;
            box-shadow: var(--card-shadow);
            margin: 20px;
            padding: 15px 20px;
        }

        .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
            transition: var(--transition);
        }

        .breadcrumb-item a:hover {
            color: #224abe;
        }



        /* Table Styles */
        .custom-table {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .custom-table thead {
            background: var(--primary-color);
            color: white;
        }

        .custom-table th {
            font-weight: 500;
            padding: 15px;
        }

        th {
            background: linear-gradient(135deg, #4CAF50, #2196F3);
        }

        td {
            text-align: center;
        }

        .custom-table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }





        /* Responsive Styles */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: var(--sidebar-width) !important;
            }

            .sidebar.mobile-show {
                transform: translateX(0);
            }

            .topbar {
                left: 0 !important;
            }

            .mobile-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 999;
                display: none;
            }

            .mobile-overlay.show {
                display: block;
            }

            .content {
                margin-left: 0 !important;
            }

            .brand-logo {
                display: block;
            }

            .user-profile {
                margin-left: 0;
            }

            .sidebar .logo {
                justify-content: center;
            }

            .sidebar .menu-item span,
            .sidebar .has-submenu::after {
                display: block !important;
            }

            body.sidebar-open {
                overflow: hidden;
            }

            .footer {
                left: 0 !important;
            }

            .content-nav ul {
                flex-wrap: nowrap;
                overflow-x: auto;
                padding-bottom: 5px;
            }

            .content-nav ul::-webkit-scrollbar {
                height: 4px;
            }

            .content-nav ul::-webkit-scrollbar-thumb {
                background: rgba(255, 255, 255, 0.3);
                border-radius: 2px;
            }
        }

        .container-fluid {
            padding: 20px;
        }


        /* loader */
        .loader-container {
            position: fixed;
            left: var(--sidebar-width);
            right: 0;
            top: var(--topbar-height);
            bottom: var(--footer-height);
            background: rgba(255, 255, 255, 0.95);
            display: flex;
            /* Changed from 'none' to show by default */
            justify-content: center;
            align-items: center;
            z-index: 1000;
            transition: left 0.3s ease;
        }

        .sidebar.collapsed+.content .loader-container {
            left: var(--sidebar-collapsed-width);
        }

        @media (max-width: 768px) {
            .loader-container {
                left: 0;
            }
        }

        /* Hide loader when done */
        .loader-container.hide {
            display: none;
        }

        /* Loader Animation */
        .loader {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-radius: 50%;
            border-top: 5px solid var(--primary-color);
            border-right: 5px solid var(--success-color);
            border-bottom: 5px solid var(--primary-color);
            border-left: 5px solid var(--success-color);
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Hide content initially */
        .content-wrapper {
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        /* Show content when loaded */
        .content-wrapper.show {
            opacity: 1;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <?php include './utils/ui/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="content">

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


        <div class="modal fade" id="journalModal" tabindex="-1" aria-labelledby="journalModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="journalModalLabel">Course Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="course_creation_form" enctype="multipart/form-data">
                            <div class="row">
                                <!-- Course Name -->
                                <div class="form-group col-md-6">
                                    <label for="course_name" class="form-label">Course Name *</label>
                                    <input type="text" class="form-control" name="course_name" id="course_name"
                                        required>
                                </div>

                                <!-- Course Description -->
                                <div class="form-group col-md-6">
                                    <label for="course_description" class="form-label">Course Description</label>
                                    <textarea class="form-control" name="course_description"
                                        id="course_description"></textarea>
                                </div>

                                <!-- Course Syllabus -->
                                <div class="form-group col-md-6">
                                    <label for="course_syllabus" class="form-label">Course Syllabus</label>
                                    <input type="file" class="form-control" name="course_syllabus" accept=".pdf"
                                        onchange="fileValidation(this)" id="course_syllabus">
                                </div>

                                <!--- year -->
                                <div class="form-group col-md-6">
                                    <label for="year" class="form-label">Year *</label>
                                    <select class="form-select" name="year" id="year" required onchange="updateSemesters()">
                                        <option value='1'>I</option>
                                        <option value='2'>II</option>
                                        <option value='3'>III</option>
                                        <option value='4'>IV</option>
                                    </select>
                                </div>

                                <!-- Semester Year -->
                                <div class="form-group col-md-6">
                                    <label for="semester" class="form-label">Semester *</label>
                                    <select class="form-select" name="semester" id="semester" required>

                                    </select>
                                </div>

                                <!-- Department -->
                                <!-- <div class="form-group col-md-6">
                                        <label for="department" class="form-label">Department *</label>
                                        <select class="form-select" name="department" id="department" required>
                                            <option value="cse">CSE</option>
                                            <option value="it">IT</option>
                                            <option value="ece">ECE</option>
                                            <option value="eee">EEE</option>
                                        </select>
                                    </div> -->



                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Topics Modal -->

        <!-- Add Topics Modal -->

        <div class="modal" id="addTopicsModal" tabindex="-1" aria-labelledby="addTopicsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addTopicsModalLabel">Add Topics</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Course ID Field (disabled) -->
                        <div class="mb-3">
                            <label for="hidden" type="hidden" class="form-label"></label>
                            <input type="hidden" class="form-control" name="courseId" id="courseId" disabled>
                        </div>

                        <!-- Units Section -->
                        <div id="units-section">
                            <form id="courseForm" onsubmit="return validateForm()">
                                <div class="card mb-4 shadow-sm">
                                    <div class="card-header bg-light d-flex align-items-center justify-content-between">

                                        <h4>Unit 1</h4>


                                    </div>
                                    <div class="card-body">
                                        <div class="unit-group" id="unit-1">
                                            <div class="row mb-3">
                                                <div class="form-group col-md-12">
                                                    <label for="unit_1_title" class="form-label">Unit 1 title</label>
                                                    <input type="url" class="form-control" id="unit_1_title" name="unit_title[1]"
                                                        placeholder="Enter unit title" required>
                                                </div>

                                            </div>


                                            <div id="unit-1-topics-section" class="mt-2">
                                                <!-- Topics for Unit 1 will be added here dynamically -->
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-primary mt-3" onclick="addTopic('unit-1')"> <i class="bi bi-plus-circle"></i> Add Topic
                                            </button>

                                        </div>

                                    </div>

                                </div>
                                <div class="card shadow-sm mb-4">
                                    <div class="card-header bg-light d-flex align-items-center justify-content-between">
                                        <h4>Unit 2</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="unit-group" id="unit-2">
                                            <div class="form-group col-md-12">
                                                <label for="unit_2_title" class="form-label">Unit 2 title</label>
                                                <input type="url" class="form-control" id="unit_2_title" name="unit_title[2]"
                                                    placeholder="Enter unit title" required>
                                            </div>

                                            <div id="unit-2-topics-section">
                                                <!-- Topics for Unit 2 will be added here dynamically -->
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-primary mt-3" onclick="addTopic('unit-2')"> <i class="bi bi-plus-circle"></i> Add Topic

                                        </div>

                                    </div>

                                </div>

                                <div class="card mb-4 shadow-sm">
                                    <div class="card-header bg-light d-flex align-items-center justify-content-between">
                                        <h4>Unit 3</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="unit-group" id="unit-3">
                                            <div class="form-group col-md-12">
                                                <label for="unit_3_title" class="form-label">Unit 3 title</label>
                                                <input type="url" class="form-control" id="unit_3_title" name="unit_title[3]"
                                                    placeholder="Enter unit title" required>
                                            </div>

                                            <div id="unit-3-topics-section">
                                                <!-- Topics for Unit 3 will be added here dynamically -->
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-primary mt-3" onclick="addTopic('unit-3')"> <i class="bi bi-plus-circle"></i> Add Topic


                                        </div>

                                    </div>

                                </div>
                                <div class="card mb-4 shadow-sm">
                                    <div class="card-header bg-light d-flex align-items-center justify-content-between">
                                        <h4>Unit 4</h4>

                                    </div>
                                    <div class="card-body">
                                        <div class="unit-group" id="unit-4">

                                            <div class="form-group col-md-12">
                                                <label for="unit_4_title" class="form-label">Unit 4 title</label>
                                                <input type="url" class="form-control" id="unit_4_title" name="unit_title[4]"
                                                    placeholder="Enter unit title" required>
                                            </div>

                                            <div id="unit-4-topics-section">
                                                <!-- Topics for Unit 4 will be added here dynamically -->
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-primary mt-3" onclick="addTopic('unit-4')"> <i class="bi bi-plus-circle"></i> Add Topic


                                        </div>

                                    </div>
                                    <div class=" card shadow-sm mt-5">
                                        <div class="card-header  bg-light d-flex justify-content-between align-items-center">
                                            <h4>unit 5</h4>
                                        </div>

                                        <div class="card-body">

                                            <div class="unit-group" id="unit-5">

                                                <div class="form-group col-md-12">
                                                    <label for="unit_5_title" class="form-label">Unit 5 title</label>
                                                    <input type="url" class="form-control" id="unit_5_title" name="unit_title[5]"
                                                        placeholder="Enter unit title" required>
                                                </div>

                                                <div id="unit-5-topics-section">
                                                    <!-- Topics for Unit 2 will be added here dynamically -->
                                                </div>
                                                <button class="btn btn-sm btn-outline-primary mt-3" onclick="addTopic('unit-5')"> <i class="bi bi-plus-circle"></i> Add Topic


                                            </div>

                                        </div>
                                    </div>

                            </form>
                            <!-- Unit Template -->



                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="submitAddTopics()">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal Structure -->
    <div class="modal fade" id="courseDetailsModal" tabindex="-1" aria-labelledby="courseDetailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="courseDetailsModalLabel">Course Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="courseDetailsContent">
                        <!-- Course details will be dynamically loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Content Area -->
    <div class="container-fluid">
        <div class="custom-tabs">

            <ul class="nav nav-tabs" role="tablist"> <!-- Center the main tabs -->
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" data-bs-toggle="tab" id="edit-bus-tab" href="#publication" role="tab"
                        aria-selected="true">
                        <span class="hidden-xs-down"><i class="fas fa-book tab-icon"></i><b> Course
                                creation</b></span>
                    </a>
                </li>

            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="publication" role="tabpanel">


                    <div class="tab-pane p-20 active" id="journal" role="tabpanel">
                        <div class="row">

                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h4 class="mb-0">Course Information</h4>
                                    <button id="open_journal" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#journalModal">
                                        Course creation
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="">
                                        <table id="course_table" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>                                                    
                                                    <th style="color: white;"><b>S.No</b></th>
                                                    <th style="color: white;"><b>Course Name</b></th>
                                                    <th style="color: white;"><b>Year</b></th>
                                                    <th style="color: white;"><b>Course Syllabus</b></th>
                                                    <th style="color: white;"><b>Status</b></th>
                                                    <th style="color: white;"><b>Action</b></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                include("db.php");
                                                $sql = "SELECT c.*, COUNT(t.topic_id) AS count_topic 
                                                            FROM course c 
                                                            LEFT JOIN unit u ON c.course_id = u.course_id 
                                                            LEFT JOIN important_topic t ON u.unit_id = t.unit_id 
                                                            WHERE  c.staff_id = '{$uid}' 
                                                            GROUP BY c.course_id";


                                                $result = $conn->query($sql);

                                                if ($result->num_rows > 0) {
                                                    $serial_no = 1;

                                                    while ($row = $result->fetch_assoc()) {

                                                ?>
                                                        <tr>

                                                            <!-- Serial Number -->
                                                            <td class="text-center">
                                                                <span><?= $serial_no ?></span>
                                                            </td>

                                                            <!-- Course Name -->
                                                            <td>
                                                                <i class="bi bi-book me-1 text-info"></i>
                                                                <?= $row['course_name'] ?>
                                                            </td>

                                                            <!-- Academic Year -->
                                                            <td class="text-center">
                                                                <span><?= $row['academic_year'] ?></span>
                                                            </td>

                                                            <!-- Syllabus -->
                                                            <td class="text-center">
                                                                <?php if (!empty($row['syllabus'])): ?>
                                                                    <a href="<?= $row['syllabus'] ?>" target="_blank"
                                                                        class="btn btn-sm btn-link text-primary">
                                                                        <i class="bi bi-file-earmark-text"></i>
                                                                    </a>
                                                                <?php else: ?>
                                                                    <i class="bi bi-file-earmark-text text-muted"></i>
                                                                <?php endif; ?>
                                                            </td>

                                                            <!-- Material -->


                                                            <!-- Status -->
                                                            <td class="text-center">
                                                                <?php if ($row['status'] === 'Approved'): ?>
                                                                    <span class="badge bg-success">Approved</span>
                                                                <?php elseif ($row['status'] === 'Pending'): ?>
                                                                    <span class="badge bg-warning">Pending</span>
                                                                <?php elseif ($row['status'] === 'Rejected'): ?>
                                                                    <span class="badge bg-danger">Rejected</span>

                                                                <?php endif; ?>
                                                            </td>



                                                            <!-- Actions -->
                                                            <td class="text-center">
                                                                <?php if ($row['topic_count'] === '0' && $row['status'] !== 'Rejected'): ?>
                                                                    <!-- Add Topics Button -->
                                                                    <button class="btn btn-sm btn-success"
                                                                        onclick="openAddTopicsModal(<?= $row['course_id'] ?>)"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-placement="top"
                                                                        title="Add Topics">
                                                                        <i class="bi bi-plus-circle"></i>
                                                                    </button>

                                                                <?php endif; ?>
                                                                <?php if ($row['status'] === 'Rejected'): ?>
                                                                    <!-- Add Topics Button -->
                                                                    <button class="btn btn-sm btn-primary mt-2"
                                                                        onclick="showRejectionReason('<?= addslashes($row['rejection_reason']) ?>')"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-placement="top"
                                                                        title="Rejected">
                                                                        <i class="bi bi-info-circle"></i>
                                                                    </button>

                                                                <?php endif; ?>

                                                                <?php if ($row['topic_count'] > 0): ?>
                                                                    <!-- View Course Details Button -->
                                                                    <button
                                                                        class="btn btn-sm btn-info view-course-details"
                                                                        data-course-id="<?= $row['course_id'] ?>"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-placement="top"
                                                                        title="View details">
                                                                        <i class="bi bi-eye"></i>
                                                                    </button>
                                                                <?php endif; ?>

                                                                <?php if ($row['status'] === 'Pending'): ?>
                                                                    <!-- Delete Course Button -->
                                                                    <button class="btn btn-sm btn-danger"
                                                                        onclick="deleteCourse(<?= $row['course_id'] ?>)"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-placement="top"
                                                                        title="delete">
                                                                        <i class="bi bi-trash"></i>
                                                                    </button>
                                                                <?php endif; ?>
                                                            </td>

                                                        </tr>
                                                <?php
                                                        $serial_no++;
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='7' class='text-center text-muted'>No courses found</td></tr>";
                                                }

                                                $conn->close();
                                                ?>
                                            </tbody>


                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    </hea>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Unit Modal -->
    <div class="modal fade" id="editUnitModal" tabindex="-1" aria-labelledby="editUnitModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUnitModalLabel">Edit Unit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

            </div>
        </div>
    </div>


    <!-- Edit Topic Modal -->
    <div class="modal fade" id="editTopicModal" tabindex="-1" aria-labelledby="editTopicModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTopicModalLabel">Edit Topic</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editTopicForm" enctype="multipart/form-data">
                        <input type="hidden" name="unit_id" id="editTopicUnitId">
                        <input type="hidden" name="topic_id" id="editTopicId">
                        <input type="hidden" name="course_id">
                        <div class="mb-3">
                            <label for="topicName" class="form-label">Topic Name</label>
                            <input type="text" class="form-control" name="topic_name" id="editTopicName" required>
                        </div>
                        <div class="mb-3">
                            <label for="videoLink" class="form-label">Video Link</label>
                            <input type="url" class="form-control" name="video_link" id="editVideoLink" required>
                        </div>

                        <div class="mb-3">
                            <label for="topicFile" class="form-label">Upload Notes (optional)</label>
                            <input type="file" class="form-control" name="topic_file" id="editTopicFile">
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <!-- Footer -->
    <?php include './utils/ui/footer.php'; ?>
    </div>


    <script>
        const hamburger = document.getElementById('hamburger');
        const sidebar = document.getElementById('sidebar');
        const body = document.body;
        const mobileOverlay = document.getElementById('mobileOverlay');

        function toggleSidebar() {
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('mobile-show');
                mobileOverlay.classList.toggle('show');
                body.classList.toggle('sidebar-open');
            } else {
                sidebar.classList.toggle('collapsed');
            }
        }
        hamburger.addEventListener('click', toggleSidebar);
        mobileOverlay.addEventListener('click', toggleSidebar);
        // Toggle User Menu
        const userMenu = document.getElementById('userMenu');
        const dropdownMenu = userMenu.querySelector('.dropdown-menu');

        userMenu.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdownMenu.classList.toggle('show');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', () => {
            dropdownMenu.classList.remove('show');
        });

        // Toggle Submenu
        const menuItems = document.querySelectorAll('.has-submenu');
        menuItems.forEach(item => {
            item.addEventListener('click', () => {
                const submenu = item.nextElementSibling;
                item.classList.toggle('active');
                submenu.classList.toggle('active');
            });
        });

        // Handle responsive behavior
        window.addEventListener('resize', () => {
            if (window.innerWidth <= 768) {
                sidebar.classList.remove('collapsed');
                sidebar.classList.remove('mobile-show');
                mobileOverlay.classList.remove('show');
                body.classList.remove('sidebar-open');
            } else {
                sidebar.style.transform = '';
                mobileOverlay.classList.remove('show');
                body.classList.remove('sidebar-open');
            }
        });
        $("#course_creation_form").submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            formData.append('action', 'create_course');
            formData.append('role', 'staff');            $.ajax({
                type: "POST",
                url: "backend.php",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    response = JSON.parse(response);
                    console.log(response.status);
                    try {
                        // Assuming response is already a JSON object
                        if (response.status === 200) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message || 'Course created successfully!'
                            }).then(function() {
                                $("#course_creation_form")[0].reset();
                                $('#course_table').load(location.href + " #course_table > *", function() {
                                    // Remove existing pagination controls
                                    const courseTable = document.getElementById("course_table");
                                    const oldControls = courseTable.previousElementSibling;
                                    if (oldControls && oldControls.className === "table-controls") {
                                        oldControls.remove();
                                    }
                                    const oldPagination = courseTable.nextElementSibling;
                                    if (oldPagination && oldPagination.className === "custom-pagination") {
                                        oldPagination.remove();
                                    }

                                    // Reinitialize pagination
                                    new TablePagination("course_table");
                                });
                                $('.modal-backdrop').remove();

                                // Hide the modal
                                const modal = bootstrap.Modal.getInstance(document.getElementById('journalModal'));
                                modal.hide();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message || 'An error occurred.'
                            });
                        }
                    } catch (e) {
                        console.error("Invalid JSON response", e);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Received invalid response from the server.'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX error", status, error, xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred during submission.'
                    });
                }
            });
        });
    </script>

    <script>
        const loaderContainer = document.getElementById('loaderContainer');

        function showLoader() {
            loaderContainer.classList.add('show');
        }

        function hideLoader() {
            loaderContainer.classList.remove('show');
        }

        //    automatic loader
        document.addEventListener('DOMContentLoaded', function() {
            const loaderContainer = document.getElementById('loaderContainer');
            const contentWrapper = document.getElementById('contentWrapper');
            let loadingTimeout;

            function hideLoader() {
                loaderContainer.classList.add('hide');
                contentWrapper.classList.add('show');
            }

            function showError() {
                console.error('Page load took too long or encountered an error');
                // You can add custom error handling here
            }

            // Set a maximum loading time (10 seconds)
            loadingTimeout = setTimeout(showError, 10000);

            // Hide loader when everything is loaded
            window.onload = function() {
                clearTimeout(loadingTimeout);

                // Add a small delay to ensure smooth transition
                setTimeout(hideLoader, 500);
            };

            // Error handling
            window.onerror = function(msg, url, lineNo, columnNo, error) {
                clearTimeout(loadingTimeout);
                showError();
                return false;
            };
        });

        // Handle responsive behavior
        window.addEventListener('resize', () => {
            if (window.innerWidth <= 768) {
                sidebar.classList.remove('collapsed');
                sidebar.classList.remove('mobile-show');
                mobileOverlay.classList.remove('show');
                body.classList.remove('sidebar-open');
            } else {
                sidebar.style.transform = '';
                mobileOverlay.classList.remove('show');
                body.classList.remove('sidebar-open');
            }
        });

        function fileValidation(input) {
            const file = input.files[0];
            if (file.size > 2 * 1024 * 1024) { // 2 MB limit
                document.getElementById('descriptionError').innerText = "File size exceeds 2 MB!";
                input.value = ''; // Clear the file input
            } else {
                document.getElementById('descriptionError').innerText = "";
            }
        }
    </script>
</body>
<script>
    function showRejectionReason(reason) {
        Swal.fire({
            title: 'Rejection Reason',
            text: reason || 'No reason provided.',
            icon: 'info',
            confirmButtonText: 'Close',
        });
    }

    // Function to open the Add Topics modal and pass the course_id
    function openAddTopicsModal(courseId) {
        // Set the course_id to the disabled input field in the modal
        document.getElementById('courseId').value = courseId;

        // Open the modal using Bootstrap's modal method
        var myModal = new bootstrap.Modal(document.getElementById('addTopicsModal'), {});
        myModal.show();
    }

    // Function to dynamically add topics to a unit
    function addTopic(unitId) {
        const unitTopicsSection = document.getElementById(`${unitId}-topics-section`);
        const topicCount = unitTopicsSection.getElementsByClassName("topic-group").length + 1;

        const newTopicHTML = `
        <div class="topic-group card border-0 shadow-sm mb-4">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Topic ${topicCount}</h6>
                <button type="button" class="btn btn-sm btn-danger" onclick="removeTopic(this)">
                    <i class="bi bi-trash"></i> Remove
                </button>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="topic_name_${unitId}_${topicCount}" class="form-label">Topic Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="topic_name_${unitId}_${topicCount}" name="topic_name[${unitId}][]" placeholder="Enter topic name" required>
                    </div>
                    <div class="col-md-6">
                        <label for="topic_video_${unitId}_${topicCount}" class="form-label">Topic Video Link</label>
                        <input type="url" class="form-control" id="topic_video_${unitId}_${topicCount}" name="topic_video[${unitId}][]" placeholder="Enter YouTube link">
                    </div>
                </div>
                <div class="row g-3 mt-3">
                    <div class="col-md-12">
                        <label for="topic_material_${unitId}_${topicCount}" class="form-label">Topic Material (PDF/PPT)</label>
                        <input type="file" class="form-control" id="topic_material_${unitId}_${topicCount}" name="topic_material[${unitId}][]" accept=".pdf,.ppt,.pptx">
                    </div>
                </div>
            </div>
        </div>
    `;

        unitTopicsSection.insertAdjacentHTML('beforeend', newTopicHTML);
    }

    function removeTopic(topicElement) {
        const topicGroup = topicElement.closest('.topic-group');
        if (topicGroup) {
            topicGroup.remove();
        }
    }


    // Function to collect data and send it to the backend
    function submitAddTopics() {
        const units = document.querySelectorAll('.unit-group');
        const formData = new FormData();

        const courseId = document.getElementById('courseId').value;

        if (!courseId) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Course ID is missing. Please reload the page and try again.',
            });
            return;
        }
        formData.append('action', 'add_topics');
        formData.append('role', 'staff');

        formData.append('courseId', courseId);

        let isValid = true;
        let errorMessage = '';

        units.forEach((unit, index) => {
            const unitId = `unit-${index + 1}`;

            const unitTitle = unit.querySelector(`input[name="unit_title[${index + 1}]"]`)?.value.trim() || '';

            if (!unitTitle) {
                isValid = false;
                errorMessage = `Unit ${index + 1} title is missing.`;
            }

            if (!isValid) return;

            formData.append(`${unitId}_title`, unitTitle);



            const topics = unit.querySelectorAll('.topic-group');
            topics.forEach((topic, topicIndex) => {
                const topicName = topic.querySelector('input[name^="topic_name"]')?.value.trim() || '';
                const topicVideo = topic.querySelector('input[name^="topic_video"]')?.value.trim() || '';
                const topicMaterial = topic.querySelector('input[name^="topic_material"]')?.files[0] || null;

                if (!topicName) {
                    isValid = false;
                    errorMessage = `Topic ${topicIndex + 1} in Unit ${index + 1} is missing a name.`;
                }

                if (!isValid) return;

                formData.append(`${unitId}_topics[${topicIndex}][name]`, topicName);
                formData.append(`${unitId}_topics[${topicIndex}][video]`, topicVideo);
                if (topicMaterial) {
                    formData.append(`${unitId}_topics[${topicIndex}][material]`, topicMaterial);
                }
            });
        });

        if (!isValid) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: errorMessage,
            });
            return;
        }

        $.ajax({
            type: "POST",
            url: "backend.php",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                response = JSON.parse(response);
                try {


                    if (response.status === 200) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'topics and unit data added successfully!',
                        });
                        resetForm();
                        $('#addTopicsModal').modal('hide');
                        $('#course_table').load(location.href + " #course_table > *", function() {
                            // Remove existing pagination controls
                            const courseTable = document.getElementById("course_table");
                            const oldControls = courseTable.previousElementSibling;
                            if (oldControls && oldControls.className === "table-controls") {
                                oldControls.remove();
                            }
                            const oldPagination = courseTable.nextElementSibling;
                            if (oldPagination && oldPagination.className === "custom-pagination") {
                                oldPagination.remove();
                            }

                            // Reinitialize pagination
                            new TablePagination("course_table");
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message || 'An error occurred.',
                        });
                    }
                } catch (e) {
                    console.error("Invalid JSON response", e);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Received invalid response from the server.',
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX error", status, error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred during submission.',
                });
            },
        });
    }

    function resetForm() {
        // Clear all input fields in the form
        document.getElementById('courseForm').reset();

        // Remove all dynamically added topics
        const unitSections = document.querySelectorAll('.unit-group');
        unitSections.forEach(unit => {
            const topicsSection = unit.querySelector('.unit-group .topic-group');
            if (topicsSection) {
                topicsSection.innerHTML = ''; // Remove topic groups
            }
        });
    }
    $(document).ready(function() {
        $(document).on("click", ".view-course-details", function() {
            const courseId = $(this).data("course-id");            // Make an AJAX request to fetch course details
            $.ajax({
                url: "backend.php",
                type: "GET",
                data: {
                    action: "fetch_course_details",
                    course_id: courseId,
                    role: "staff"
                },
                success: function(response) {
                    const {
                        units,
                        courseStatus
                    } = JSON.parse(response);
                    console.log(response);

                    if (!units || Object.keys(units).length === 0) {
                        $("#courseDetailsContent").html(
                            '<div class="text-center p-4 text-muted">No details available for this course.</div>'
                        );
                    } else {
                        let modalContent = `
                        <div class="accordion" id="courseAccordion">
                    `;
                        for (const unitId in units) {
                            const unit = units[unitId];
                            modalContent += `
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading-${unitId}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-${unitId}" aria-expanded="false" aria-controls="collapse-${unitId}">
                                    <strong>Unit ${unit.unit_number}: ${unit.title}</strong>
                                </button>
                            </h2>
                            <div id="collapse-${unitId}" class="accordion-collapse collapse" aria-labelledby="heading-${unitId}" data-bs-parent="#courseAccordion">
                                <div class="accordion-body">
                                    ${unit.topics && unit.topics.length > 0 ? `
                                        <div class="row g-3">
                                            ${unit.topics.map((topic, index) => {
                                                if (!topic.topic_name || (!topic.video_link && !topic.notes)) return `
                                    <div class="text-center p-4 text-muted" style="font-size: 1.2rem; font-weight: 600;">
                                        <strong>Nothing to show for this unit.</strong>
                                    </div>
                                    `; // Skip if topic name, video, or notes are null
                                                return `
                                                <div class="col-md-6">
                                                    <div class="card h-100 shadow rounded-lg border-0 hover-card">
                                                        <div class="card-header text-white bg-gradient-primary d-flex align-items-center justify-content-between">
                                                            <!-- Topic Title -->
                                                            <h6 class="card-title mb-0 text-truncate" title="${topic.topic_name}" style="font-size: 1.1rem; font-weight: 700;">${topic.topic_name}</h6>
                                                            <span class="badge bg-success text-uppercase" style="font-size: 0.8rem;">Topic ${index + 1}</span>
                                                        </div>
                                                        <div class="card-body">
                                                            <!-- Video Link -->
                                                            ${topic.video_link ? `<p class="mb-2">
                                                                <strong><i class="fas fa-play-circle text-primary"></i> Video:</strong> 
                                                                <a href="${topic.video_link}" class="text-decoration-none text-primary" target="_blank" title="Watch Video">
                                                                    Watch Now
                                                                </a>
                                                            </p>` : ""}

                                                            <!-- Notes Link -->
                                                            ${topic.notes ? `<p class="mb-2">
                                                                <strong><i class="fas fa-file-alt text-success"></i> Notes:</strong> 
                                                                <a href="${topic.notes}" class="text-decoration-none text-success" target="_blank" title="Download Notes">
                                                                    Download
                                                                </a>
                                                            </p>` : ""}

                                                            <!-- Edit Button -->
                                                            <button class="btn btn-outline-primary btn-sm w-100 mt-3 shadow-sm" title="Edit Topic" 
                                                                onclick="editTopic(${unitId}, ${index}, '${topic.topic_name}', '${topic.video_link}', '${topic.notes}', '${topic.topic_id}')">
                                                                <i class="fas fa-pencil-alt"></i> Edit Topic
                                                            </button>

                                                            <!-- Conditionally Show Delete Button -->
                                                            ${courseStatus === "Pending" ? `
                                                                <button class="btn btn-outline-danger btn-sm w-100 mt-3 shadow-sm" title="Delete Topic" 
                                                                    onclick="deleteTopic(${topic.topic_id}, '${topic.topic_name}')">
                                                                    <i class="fas fa-trash"></i> Delete Topic
                                                                </button>
                                                            ` : ""}
                                                        </div>
                                                    </div>
                                                </div>
                                                `;
                                            }).join("")}
                                        </div>
                                    ` : `
                                    <div class="text-center p-4 text-muted" style="font-size: 1.2rem; font-weight: 600;">
                                        <strong>Nothing to show for this unit.</strong>
                                    </div>
                                    `}
                                </div>
                            </div>
                        </div>`;
                        }
                        modalContent += `
                        </div>
                    `;
                        $("#courseDetailsContent").html(modalContent);
                    }

                    // Show the modal
                    $("#courseDetailsModal").modal("show");
                },
                error: function() {
                    alert("Failed to fetch course details. Please try again.");
                },
            });
        });
    });









    function editTopic(unitId, topicIndex, topicName, videoLink, notesLink, topic_id) {
        // Populate the modal fields with the current topic data
        $('#editTopicUnitId').val(unitId); // Set the unit_id in the hidden input
        $('#editTopicId').val(topic_id); // Set the topic_id in the hidden input
        $('#editTopicName').val(topicName); // Set the topic name
        $('#editVideoLink').val(videoLink); // Set the video link
        $('#editTopicNotes').val(notesLink); // Set the notes link (if any)


        const courseId = $('.view-course-details[data-course-id]').data('course-id');
        $('#editTopicModal input[name="course_id"]').val(courseId); // Add the course ID to the modal form
        // Show the modal
        $('#editTopicModal').modal('show');
    }

    // Edit topic function
    $(document).ready(function() {
        // Open the Edit Topic Modal with the relevant data
        $('.edit-topic-btn').click(function() {
            const topicId = $(this).data('topic-id');
            const unitId = $(this).data('unit-id');
            const topicName = $(this).data('topic-name');
            const videoLink = $(this).data('video-link');
            const notesLink = $(this).data('notes-link');

            // Populate the modal with existing topic data
            $('#editTopicUnitId').val(unitId);
            $('#editTopicId').val(topicId);
            $('#editTopicName').val(topicName);
            $('#editVideoLink').val(videoLink);
            $('#editTopicNotes').val(notesLink);

            // Show the modal
            $('#editTopicModal').modal('show');
        });

        // Handle the form submission
        $('#editTopicForm').submit(function(e) {
            e.preventDefault(); // Prevent form submission

            const formData = new FormData(this);
            formData.append('action', 'edit_topic');
            formData.append('role', 'staff');            // Send AJAX request to update the topic
            $.ajax({
                url: 'backend.php', // PHP script to handle the update
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    response = JSON.parse(response);

                    if (response.status === 200) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Edited successfully!'
                        }).then(function() {

                            $('#editTopicModal').modal('hide');
                            $('#course_table').load(location.href + " #course_table > *", function() {
                                // Remove existing pagination controls
                                const courseTable = document.getElementById("course_table");
                                const oldControls = courseTable.previousElementSibling;
                                if (oldControls && oldControls.className === "table-controls") {
                                    oldControls.remove();
                                }
                                const oldPagination = courseTable.nextElementSibling;
                                if (oldPagination && oldPagination.className === "custom-pagination") {
                                    oldPagination.remove();
                                }

                                // Reinitialize pagination
                                new TablePagination("course_table");
                            });
                            $('#courseDetailsModal').modal('hide');
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message || 'An error occurred.'
                        });
                    }
                    // Optionally, you can refresh the page or update the UI

                },
                error: function() {
                    alert('Failed to update the topic. Please try again.');
                }
            });
        });
    });
</script>
<script>
    function deleteCourse(courseId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {                $.ajax({
                    url: 'backend.php',
                    type: 'POST',
                    data: {
                        action: "delete_course",
                        course_id: courseId,
                        role: "staff"
                    },
                    success: function(response) {
                        response = JSON.parse(response);
                        if (response.status === 200) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'Course deleted successfully.'
                            }).then(() => {

                                $('#course_table').load(location.href + " #course_table > *", function() {
                                    // Remove existing pagination controls
                                    const courseTable = document.getElementById("course_table");
                                    const oldControls = courseTable.previousElementSibling;
                                    if (oldControls && oldControls.className === "table-controls") {
                                        oldControls.remove();
                                    }
                                    const oldPagination = courseTable.nextElementSibling;
                                    if (oldPagination && oldPagination.className === "custom-pagination") {
                                        oldPagination.remove();
                                    }

                                    // Reinitialize pagination
                                    new TablePagination("course_table");
                                }); // Reload only the table content
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Failed',
                                text: 'Failed to delete the course: ' + response
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error while deleting the course. Please try again.'
                        });
                    }
                });
            }
        });
    }

    function validateForm() {
        // Get all input and select elements inside the form
        const form = document.getElementById('courseForm');
        const inputs = form.querySelectorAll('input, select');
        let isValid = true;

        inputs.forEach(input => {
            // Clear any previous error highlighting
            input.classList.remove('is-invalid');

            // Check if the input is required and empty
            if (input.hasAttribute('required') && !input.value.trim()) {
                isValid = false;
                input.classList.add('is-invalid'); // Add error highlighting
            }
        });

        if (!isValid) {
            alert('Please fill out all required fields.');
        }

        return isValid; // Prevent form submission if any field is invalid
    }
</script>
<script>
    // Define semester options based on year
    const semesterOptions = {
        1: ['1', '2'], // First year: semesters I and II
        2: ['3', '4'], // Second year: semesters III and IV
        3: ['5', '6'], // Third year: semesters V and VI
        4: ['7', '8'] // Fourth year: semesters VII and VIII
    };

    function updateSemesters() {
        const yearSelect = document.getElementById('year');
        const semesterSelect = document.getElementById('semester');

        // Get the selected year
        const selectedYear = yearSelect.value;

        // Clear the existing options in the semester dropdown
        semesterSelect.innerHTML = '';

        // Populate the semester dropdown based on the selected year
        semesterOptions[selectedYear].forEach(sem => {
            const option = document.createElement('option');
            option.value = sem;
            option.textContent = `Semester ${sem}`;
            semesterSelect.appendChild(option);
        });
    }

    // Initialize semesters based on the default year
    document.addEventListener('DOMContentLoaded', updateSemesters);
</script>
<script>
    function deleteTopic(topicId, topicName) {
        // Show confirmation dialog
        Swal.fire({
            title: 'Are you sure?',
            text: `You are about to delete the topic: ${topicName}. This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Make AJAX request to delete the topic
                $.ajax({
                    url: 'backend.php',
                    type: 'POST',
                    data: {
                        action: "delete_topic",
                        topic_id: topicId,
                        role: "staff"
                    },
                    success: function(response) {
                        response = JSON.parse(response);
                        if (response.status === 200) {
                            Swal.fire(
                                'Deleted!',
                                `The topic "${topicName}" has been deleted.`,
                                'success'
                            ).then(() => {
                                $('#editTopicModal').modal('hide');
                                $('#course_table').load(location.href + " #course_table > *", function() {
                                    // Remove existing pagination controls
                                    const courseTable = document.getElementById("course_table");
                                    const oldControls = courseTable.previousElementSibling;
                                    if (oldControls && oldControls.className === "table-controls") {
                                        oldControls.remove();
                                    }
                                    const oldPagination = courseTable.nextElementSibling;
                                    if (oldPagination && oldPagination.className === "custom-pagination") {
                                        oldPagination.remove();
                                    }

                                    // Reinitialize pagination
                                    new TablePagination("course_table");
                                });
                                $('#courseDetailsModal').modal('hide');
                            });

                            // Reload the course details
                            $('.view-course-details[data-course-id]').trigger('click');
                        } else {
                            Swal.fire(
                                'Error!',
                                'Failed to delete the topic. Please try again.',
                                'error'
                            );
                        }
                    },
                    error: function() {
                        Swal.fire(
                            'Error!',
                            'An error occurred while deleting the topic. Please try again.',
                            'error'
                        );
                    }
                });
            }
        });
    }


    class TablePagination {
        constructor(tableId, rowsPerPage = 5) {
            this.table = document.getElementById(tableId);
            this.rowsPerPage = rowsPerPage;
            this.currentPage = 1;
            this.rows = Array.from(
                this.table.getElementsByTagName("tbody")[0].getElementsByTagName("tr")
            );
            this.totalRows = this.rows.length;
            this.totalPages = Math.ceil(this.totalRows / this.rowsPerPage);
            this.searchTerm = "";

            this.init();
        }

        init() {
            // Create table controls container
            const controls = document.createElement("div");
            controls.className = "table-controls";
            this.table.parentNode.insertBefore(controls, this.table);

            // Add entries dropdown
            const entriesDiv = document.createElement("div");
            entriesDiv.className = "entries-dropdown";
            const entriesLabel = document.createElement("label");
            entriesLabel.textContent = "Show ";
            const entriesSelect = document.createElement("select");
            [5, 10, 15, 20].forEach((value) => {
                const option = new Option(value, value);
                entriesSelect.appendChild(option);
            });
            const entriesLabelEnd = document.createElement("label");
            entriesLabelEnd.textContent = " entries";

            entriesSelect.value = this.rowsPerPage;
            entriesSelect.addEventListener("change", (e) => {
                this.rowsPerPage = parseInt(e.target.value);
                this.currentPage = 1;
                this.totalPages = Math.ceil(this.totalRows / this.rowsPerPage);
                this.updateTable();
                this.updatePagination();
            });

            entriesDiv.appendChild(entriesLabel);
            entriesDiv.appendChild(entriesSelect);
            entriesDiv.appendChild(entriesLabelEnd);
            controls.appendChild(entriesDiv);

            // Add search box (moved to right)
            const searchBox = document.createElement("div");
            searchBox.className = "search-box";
            const searchInput = document.createElement("input");
            searchInput.type = "text";
            searchInput.className = "search-input";
            searchInput.placeholder = "Search...";
            searchInput.addEventListener("input", (e) =>
                this.handleSearch(e.target.value)
            );
            searchBox.appendChild(searchInput);
            controls.appendChild(searchBox);

            // Create pagination container
            const container = document.createElement("div");
            container.className = "custom-pagination";
            this.table.parentNode.insertBefore(container, this.table.nextSibling);

            // Add entries info
            const info = document.createElement("div");
            info.className = "entries-info";
            container.appendChild(info);

            // Add pagination buttons
            const buttons = document.createElement("div");
            buttons.className = "pagination-buttons";
            container.appendChild(buttons);

            this.updateTable();
            this.updatePagination();
        }

        handleSearch(term) {
            this.searchTerm = term.toLowerCase();
            this.currentPage = 1;

            this.rows.forEach((row) => {
                const text = Array.from(row.cells)
                    .map((cell) => cell.textContent)
                    .join(" ")
                    .toLowerCase();

                const match = text.includes(this.searchTerm);
                row.classList.toggle("search-hidden", !match);
            });

            this.updateVisibleRows();
            this.updateTable();
            this.updatePagination();
        }

        updateVisibleRows() {
            const visibleRows = this.rows.filter(
                (row) => !row.classList.contains("search-hidden")
            );
            this.totalRows = visibleRows.length;
            this.totalPages = Math.ceil(this.totalRows / this.rowsPerPage);
            if (this.currentPage > this.totalPages) {
                this.currentPage = this.totalPages || 1;
            }
        }

        updateTable() {
            const visibleRows = this.rows.filter(
                (row) => !row.classList.contains("search-hidden")
            );
            const start = (this.currentPage - 1) * this.rowsPerPage;
            const end = start + this.rowsPerPage;

            this.rows.forEach((row) => {
                row.style.display = "none";
            });

            visibleRows.slice(start, end).forEach((row) => {
                row.style.display = "";
            });

            // Update entries info
            const info = this.table.nextSibling.querySelector(".entries-info");
            if (visibleRows.length === 0) {
                info.textContent = "No matching records found";
            } else {
                info.textContent = `Showing ${start + 1} to ${Math.min(
                end,
                visibleRows.length
            )} of ${visibleRows.length} entries`;
            }
        }

        updatePagination() {
            const buttons = this.table.nextSibling.querySelector(".pagination-buttons");
            buttons.innerHTML = "";

            if (this.totalRows === 0) return;

            // Previous button
            const prevBtn = document.createElement("button");
            prevBtn.innerHTML = '<i class="fas fa-chevron-left"></i>';
            prevBtn.disabled = this.currentPage === 1;
            prevBtn.onclick = () => this.goToPage(this.currentPage - 1);
            buttons.appendChild(prevBtn);

            // Page buttons
            for (let i = 1; i <= this.totalPages; i++) {
                const pageBtn = document.createElement("button");
                pageBtn.textContent = i;
                pageBtn.className = this.currentPage === i ? "active" : "";
                pageBtn.onclick = () => this.goToPage(i);
                buttons.appendChild(pageBtn);
            }

            // Next button
            const nextBtn = document.createElement("button");
            nextBtn.innerHTML = '<i class="fas fa-chevron-right"></i>';
            nextBtn.disabled = this.currentPage === this.totalPages;
            nextBtn.onclick = () => this.goToPage(this.currentPage + 1);
            buttons.appendChild(nextBtn);
        }

        goToPage(page) {
            this.currentPage = page;
            this.updateTable();
            this.updatePagination();
        }
    }
    // Initialize pagination for both tables
    document.addEventListener("DOMContentLoaded", function() {
        new TablePagination("course_table");
    });
</script>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

</html>