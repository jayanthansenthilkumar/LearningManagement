<?php
require_once 'db.php';

// Get department from session
if (!isset($_SESSION['user'])) {
    require_once 'session.php';
    initializeUserSession();
}

$department = $_SESSION['user']['dept'];



function getCourseApprovals()
{
    global $conn, $department;

    $sql = "SELECT c.course_id, c.course_name, f.name as staff_name, 
            c.academic_year, c.semester, c.status, 
            DATE(c.created_at) as submission_date, 
            DATE(c.updated_at) as last_updated
            FROM course c
            JOIN faculty f ON c.staff_id = f.uid
            WHERE c.department = ? 
            AND c.status = 'Pending'
            ORDER BY c.course_id";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $department);
    $stmt->execute();

    return $stmt->get_result();
}

function getCourseDetails($courseId)
{
    global $conn;

    // Get course details
    $sql = "SELECT course_id, course_name, description FROM course WHERE course_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $courseId);
    $stmt->execute();
    $course = $stmt->get_result()->fetch_assoc();

    if (!$course) {
        return null;
    }

    // Get units
    $sql = "SELECT * FROM unit WHERE course_id = ? ORDER BY unit_number";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $courseId);
    $stmt->execute();
    $units = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // Get topics for each unit
    foreach ($units as &$unit) {
        $sql = "SELECT * FROM important_topic WHERE unit_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $unit['unit_id']);
        $stmt->execute();
        $unit['topics'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    return array_merge($course, ['units' => $units]);
}

function updateCourseStatus($courseId, $status, $reason = '')
{
    global $conn;

    $sql = "UPDATE course SET status = ?, rejection_reason = ? WHERE course_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $status, $reason, $courseId);

    if ($stmt->execute()) {
        // Fetch updated course data
        $sql = "SELECT c.course_id, c.course_name, u.name as staff_name, 
                c.academic_year, c.semester, c.status, 
                DATE(c.created_at) as submission_date, 
                DATE(c.updated_at) as last_updated
                FROM course c
                JOIN faculty u ON c.staff_id = u.uid
                WHERE c.course_id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $courseId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    return false;
}

function getSyllabus($courseId)
{
    global $conn;

    $sql = "SELECT syllabus FROM course WHERE course_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $courseId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        return ['success' => true, 'syllabus' => $row['syllabus']];
    }

    return ['success' => false];
}

function getAvailableCourses()
{
    global $conn, $department;

    $sql = "SELECT c.course_id, c.course_name, u.name as staff_name, 
            c.academic_year, c.semester
            FROM course c
            JOIN faculty u ON c.staff_id = u.uid
            WHERE c.department = ? 
            AND c.status = 'Approved'
            ORDER BY c.course_id";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $department);
    $stmt->execute();

    return $stmt->get_result();
}


function getApprovalTableHTML()
{
    $result = getCourseApprovals();
    $html = '';

    if ($result->num_rows > 0) {
        $counter = 1;
        while ($row = $result->fetch_assoc()) {
            $statusClass = match ($row['status']) {
                'Approved' => 'bg-success',
                'Rejected' => 'bg-danger',
                default => 'bg-warning'
            };

            $html .= "<tr data-course-id='{$row['course_id']}'>";
            $html .= "<td>" . htmlspecialchars((string)$counter++) . "</td>";
            $html .= "<td>" . htmlspecialchars($row['course_name']) . "</td>";
            $html .= "<td>" . htmlspecialchars($row['staff_name']) . "</td>";
            $html .= "<td>{$row['academic_year']}</td>";
            $html .= "<td>Semester {$row['semester']}</td>";
            $html .= "<td><span class='badge {$statusClass}'>{$row['status']}</span></td>";
            $html .= "<td class='text-center'>
                        <button type='button' class='btn btn-info btn-sm' onclick='viewSyllabus({$row['course_id']})' title='View Syllabus'>
                            <i class='fas fa-file-alt'></i>
                        </button>
                    </td>";
            $html .= "<td>
                        <div class='d-flex gap-2 justify-content-center'>
                            <button type='button' class='btn btn-primary btn-sm' onclick='loadCourseDetails({$row['course_id']})' title='Review'>
                                <i class='fas fa-eye'></i>
                            </button>
                            <button type='button' class='btn btn-success btn-sm' onclick='submitApproval({$row['course_id']})' title='Approve'>
                                <i class='fas fa-check'></i>
                            </button>
                            <button type='button' class='btn btn-danger btn-sm' onclick='showRejectModal({$row['course_id']})' title='Reject'>
                                <i class='fas fa-times'></i>
                            </button>
                        </div>
                    </td>";
            $html .= "</tr>";
        }
    } else {
        $html = "<tr><td colspan='8' class='text-center'>No courses found</td></tr>";
    }

    return $html;
}

function getAvailableTableHTML()
{
    $result = getAvailableCourses();
    $html = '';

    if ($result->num_rows > 0) {
        $counter = 1;
        while ($row = $result->fetch_assoc()) {
            $html .= "<tr data-course-id='{$row['course_id']}'>";
            $html .= "<td>" . htmlspecialchars((string)$counter++) . "</td>";
            $html .= "<td>" . htmlspecialchars($row['course_name']) . "</td>";
            $html .= "<td>" . htmlspecialchars($row['staff_name']) . "</td>";
            $html .= "<td>{$row['academic_year']}</td>";
            $html .= "<td>Semester {$row['semester']}</td>";
            $html .= "<td class='text-center'>
                        <button type='button' class='btn btn-info btn-sm' onclick='viewSyllabus({$row['course_id']})' title='View Syllabus'>
                            <i class='fas fa-file-alt'></i>
                        </button>
                    </td>";
            $html .= "<td class='text-center'>
                        <button type='button' class='btn btn-primary btn-sm' onclick='loadCourseDetails({$row['course_id']})' title='Review'>
                            <i class='fas fa-eye'></i>
                        </button>
                    </td>";
            $html .= "</tr>";
        }
    } else {
        $html = "<tr><td colspan='7' class='text-center'>No courses available</td></tr>";
    }

    return $html;
}

// Add this new function for serving files
function serveFile($filePath)
{
    // Define the base upload directory (adjust this path according to your project structure)
    $uploadDir = '';

    // Clean the file path to prevent directory traversal
    $cleanFile = str_replace('..', '', $filePath);
    $filePath = $uploadDir . $cleanFile;

    // Check if file exists and is within uploads directory
    if (file_exists($filePath) && strpos(realpath($filePath), realpath($uploadDir)) === 0) {
        $mimeType = mime_content_type($filePath);

        header('Content-Type: ' . $mimeType);
        header('Content-Disposition: inline; filename="' . basename($filePath) . '"');

        readfile($filePath);
        exit;
    }

    // If file not found or invalid
    header('HTTP/1.0 404 Not Found');
    echo 'File not found';
    exit;
}

/// student funcitons

function getcourses($semester)
{    
    global $conn, $department;
    
    // Debug information
    error_log("getcourses called - Original Department: '$department', Academic Year/Semester: '$semester'");
    
    // Special handling for first-year students
    $queryDepartment = $department;
    if ($semester == "1") {
        $queryDepartment = "Freshmen Engineering";
        error_log("First year student - using department: '$queryDepartment'");
    }
    
    // We'll try using exactly the semester value passed to us first
    $directQuery = "SELECT course_id, course_name, description, academic_year, syllabus 
                   FROM course 
                   WHERE status = 'Approved' AND 
                         department = ? AND semester = ?";
    
    error_log("Trying exact match: Department='$queryDepartment', Semester='$semester'");
    
    $stmt = $conn->prepare($directQuery);
    $stmt->bind_param("ss", $queryDepartment, $semester);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $courses = $result->fetch_all(MYSQLI_ASSOC);
    
    error_log("Direct query returned " . count($courses) . " courses");
    
    // If no courses found, try a more aggressive approach with multiple department names and formats
    if (count($courses) === 0) {
        error_log("No courses with direct match, trying alternatives...");
        
        // For debugging, let's see what's in the database
        $debugQuery = "SELECT DISTINCT department, semester FROM course WHERE status = 'Approved'";
        $debugResult = $conn->query($debugQuery);
        
        if ($debugResult && $debugResult->num_rows > 0) {
            error_log("Existing department/semester combinations in database:");
            while ($row = $debugResult->fetch_assoc()) {
                error_log("   Department: '{$row['department']}', Semester: '{$row['semester']}'");
            }
        }
        
        // Possible department alternatives
        $departmentOptions = [
            $department,
            'Freshmen Engineering',
            'freshmen engineering',
            'Freshman Engineering',
            'freshman engineering',
            'First Year',
            'first year',
            'common',
            'COMMON',
            'Common'
        ];
        
        // Possible semester formats to try
        $semesterOptions = [
            $semester,                      // Original value
            trim($semester),                // Trimmed
            strval(intval($semester)),      // Numeric string
            sprintf("%d", $semester),       // Formatted number 
            "Semester " . $semester,        // With prefix
            "semester " . $semester,        // Lowercase prefix
            "Sem " . $semester,             // Abbreviated
        ];
        
        // Try each combination of department and semester
        foreach ($departmentOptions as $deptOption) {
            foreach ($semesterOptions as $semOption) {
                if (count($courses) > 0) break; // Stop if we found courses
                
                error_log("Trying combination: Dept='$deptOption', Semester='$semOption'");
                
                $altQuery = "SELECT course_id, course_name, description, academic_year, syllabus 
                           FROM course 
                           WHERE status = 'Approved' AND 
                                department = ? AND semester = ?";
                
                $altStmt = $conn->prepare($altQuery);
                $altStmt->bind_param("ss", $deptOption, $semOption);
                $altStmt->execute();
                
                $altResult = $altStmt->get_result();
                $altCourses = $altResult->fetch_all(MYSQLI_ASSOC);
                
                if (count($altCourses) > 0) {
                    error_log("Found " . count($altCourses) . " courses with Dept='$deptOption', Semester='$semOption'");
                    $courses = $altCourses;
                }
            }
            if (count($courses) > 0) break; // Stop if we found courses
        }
        
        // Last resort: just try to match by semester only for this department or Freshmen Engineering
        if (count($courses) === 0) {
            error_log("Still no courses found. Trying final approach with semester only...");
            
            // Try a fallback query to find any courses that match just the semester for this department or 'Freshmen Engineering'
            $fallbackQuery = "SELECT course_id, course_name, description, academic_year, syllabus 
                              FROM course 
                              WHERE status = 'Approved' AND semester = ? 
                              AND (department = ? OR department = 'Freshmen Engineering')";
            
            $fallbackStmt = $conn->prepare($fallbackQuery);
            $fallbackStmt->bind_param("ss", $semester, $department);
            $fallbackStmt->execute();
            
            $fallbackResult = $fallbackStmt->get_result();
            $fallbackCourses = $fallbackResult->fetch_all(MYSQLI_ASSOC);
            
            error_log("Semester-only query returned " . count($fallbackCourses) . " courses");
            
            if (count($fallbackCourses) > 0) {
                $courses = $fallbackCourses;
            }
        }
    }
    
    return $courses;
}

function getunits($courseId)
{
    global $conn;

    if ($courseId > 0) {
        $query = "
            SELECT u.unit_id, u.unit_number, u.title, u.unit_material, 
                   t.topic_id, t.topic_name, t.notes, t.video_link 
            FROM unit u
            LEFT JOIN important_topic t ON u.unit_id = t.unit_id
            WHERE u.course_id = ?";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $courseId);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $units = [];

            while ($row = $result->fetch_assoc()) {
                $unitId = $row['unit_id'];

                // Ensure each unit is only added once
                if (!isset($units[$unitId])) {
                    $units[$unitId] = [
                        'unit_id' => $unitId,
                        'unit_number' => $row['unit_number'],
                        'title' => $row['title'],
                        'unit_material' => $row['unit_material'],
                        'topics' => []
                    ];
                }

                // If a topic exists, add it to the unit
                if ($row['topic_id']) {
                    $units[$unitId]['topics'][] = [
                        'topic_id' => $row['topic_id'],
                        'topic_name' => $row['topic_name'],
                        'notes' => $row['notes'],
                        'video_link' => $row['video_link']
                    ];
                }
            }

            return ['units' => array_values($units)];
        } else {
            return json_encode(['error' => 'Query execution failed: ' . $stmt->error]);
        }

        // Close the prepared statement

    } else {
        return json_encode(['error' => 'Invalid course ID.']);
    }
}

function getDepartmentStats($department)
{
    global $conn;

    $stats = [
        'available' => 0,
        'accepted' => 0,
        'pending' => 0
    ];

    // Get available courses (Approved courses)
    $sql = "SELECT COUNT(*) as count FROM course 
            WHERE department = ? AND (status = 'Approved' OR status = 'Pending' OR status = 'Rejected')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $department);
    $stmt->execute();
    $result = $stmt->get_result();
    $stats['available'] = $result->fetch_assoc()['count'];

    // Get accepted courses
    $sql = "SELECT COUNT(*) as count FROM course 
            WHERE department = ? AND status = 'Approved'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $department);
    $stmt->execute();
    $result = $stmt->get_result();
    $stats['accepted'] = $result->fetch_assoc()['count'];

    // Get pending requests
    $sql = "SELECT COUNT(*) as count FROM course 
            WHERE department = ? AND status = 'Pending'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $department);
    $stmt->execute();
    $result = $stmt->get_result();
    $stats['pending'] = $result->fetch_assoc()['count'];

    return $stats;
}

function getStaffStats($staffId)
{
    global $conn;

    $stats = [
        'total' => 0,
        'pending' => 0,
        'accepted' => 0,
        'rejected' => 0
    ];

    // Get total courses
    $sql = "SELECT COUNT(*) as count FROM course WHERE staff_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $staffId);
    $stmt->execute();
    $result = $stmt->get_result();
    $stats['total'] = $result->fetch_assoc()['count'];

    // Get pending courses
    $sql = "SELECT COUNT(*) as count FROM course WHERE staff_id = ? AND status = 'Pending'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $staffId);
    $stmt->execute();
    $result = $stmt->get_result();
    $stats['pending'] = $result->fetch_assoc()['count'];

    // Get accepted courses
    $sql = "SELECT COUNT(*) as count FROM course WHERE staff_id = ? AND status = 'Approved'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $staffId);
    $stmt->execute();
    $result = $stmt->get_result();
    $stats['accepted'] = $result->fetch_assoc()['count'];

    // Get rejected courses
    $sql = "SELECT COUNT(*) as count FROM course WHERE staff_id = ? AND status = 'Rejected'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $staffId);
    $stmt->execute();
    $result = $stmt->get_result();
    $stats['rejected'] = $result->fetch_assoc()['count'];

    return $stats;
}

if ((isset($_GET['role']) && $_GET['role'] === 'staff') || (isset($_POST['role']) && $_POST['role'] === 'staff')) {
    $uid = $_SESSION['user']['uid'];
    $response = ['success' => false];
    $action = $_POST['action'] ?? $_GET['action'];

    switch ($action) {
        case 'create_course':
            $course_name = $_POST['course_name'] ?? null;
            $course_description = $_POST['course_description'] ?? null;
            $semester = $_POST['semester'] ?? null;
            $year = $_POST['year'] ?? null;
            $status = "Pending";

            if (!$course_name || !$course_description || !$semester ||  !$year) {
                echo json_encode(['status' => 400, 'message' => 'Required fields are missing.']);
                exit;
            }

            $upload_dir = 'uploads/';
            if (isset($_FILES['course_syllabus']) && $_FILES['course_syllabus']['error'] === UPLOAD_ERR_OK) {
                $pdf_name = $_FILES['course_syllabus']['name'];
                $pdf_tmp_name = $_FILES['course_syllabus']['tmp_name'];
                $pdf_size = $_FILES['course_syllabus']['size'];

                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $pdf_path = $upload_dir . uniqid() . '_' . $pdf_name;

                if ($pdf_size <= 2 * 1024 * 1024) {
                    if (!move_uploaded_file($pdf_tmp_name, $pdf_path)) {
                        echo json_encode(['status' => 500, 'message' => 'Failed to upload PDF file.']);
                        exit;
                    }
                } else {
                    echo json_encode(['status' => 400, 'message' => 'PDF file size exceeds 2 MB.']);
                    exit;
                }
            } else {
                echo json_encode(['status' => 400, 'message' => 'PDF file is required.']);
                exit;
            }

            $sql = "INSERT INTO course (staff_id, course_name, description, syllabus, academic_year, semester, status, department) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                echo json_encode(['status' => 500, 'message' => 'SQL Error: ' . $conn->error]);
                exit;
            }

            $stmt->bind_param("isssssss", $uid, $course_name, $course_description, $pdf_path, $year, $semester, $status, $department);

            if ($stmt->execute()) {
                echo json_encode(['status' => 200, 'message' => 'Course created successfully.']);
            } else {
                echo json_encode(['status' => 500, 'message' => 'Failed to create course.']);
            }
            break;

        case 'add_topics':
            try {
                $courseId = $_POST['courseId'];
                $stmt1 = $conn->prepare("UPDATE course SET topic_count = 1 WHERE course_id = ?");

                if (!$stmt1) {
                    throw new Exception("Unit prepare failed: " . $conn->error);
                }

                $stmt1->bind_param("i", $courseId);
                if (!$stmt1->execute()) {
                    throw new Exception("Unit execution failed: " . $stmt->error);
                }

                for ($unitIndex = 1; $unitIndex <= 5; $unitIndex++) {
                    $unitTitle = $_POST["unit-{$unitIndex}_title"];

                    $stmt = $conn->prepare("INSERT INTO unit (course_id, unit_number, title, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");

                    if (!$stmt) {
                        throw new Exception("Unit prepare failed: " . $conn->error);
                    }

                    $stmt->bind_param("iis", $courseId, $unitIndex, $unitTitle);
                    if (!$stmt->execute()) {
                        throw new Exception("Unit execution failed: " . $stmt->error);
                    }

                    $unitId = $stmt->insert_id;
                    $stmt->close();

                    if (isset($_POST["unit-{$unitIndex}_topics"]) && is_array($_POST["unit-{$unitIndex}_topics"])) {
                        $topics = $_POST["unit-{$unitIndex}_topics"];
                        $topicMaterials = $_FILES["unit-{$unitIndex}_topics"];

                        foreach ($topics as $topicIndex => $topic) {
                            $topicName = $topic['name'];
                            $topicVideo = $topic['video'];

                            $topicMaterial = $topicMaterials['name'][$topicIndex]['material'];
                            $topicMaterialPath = 'uploads/' . basename($topicMaterial);
                            move_uploaded_file($topicMaterials['tmp_name'][$topicIndex]['material'], $topicMaterialPath);

                            $stmt = $conn->prepare("INSERT INTO important_topic (unit_id, topic_name, video_link, notes) VALUES (?, ?, ?, ?)");

                            if (!$stmt) {
                                throw new Exception("Topic prepare failed: " . $conn->error);
                            }

                            $stmt->bind_param("isss", $unitId, $topicName, $topicVideo, $topicMaterialPath);
                            if (!$stmt->execute()) {
                                throw new Exception("Topic execution failed: " . $stmt->error);
                            }
                            $stmt->close();
                        }
                    }
                }

                $conn->commit();
                echo json_encode(['status' => 200, 'message' => 'Topics inserted successfully.']);
            } catch (Exception $e) {
                $conn->rollback();
                echo json_encode(['status' => 500, 'message' => 'Error: ' . $e->getMessage()]);
            }
            break;
        case 'fetch_course_details':
            try {
                $course_id = $_GET['course_id'];

                $courseQuery = $conn->prepare("SELECT status FROM course WHERE course_id = ?");
                $courseQuery->bind_param("i", $course_id);
                $courseQuery->execute();
                $courseResult = $courseQuery->get_result();
                $courseStatus = $courseResult->fetch_assoc()['status'];
                $courseQuery->close();

                // Fetch units and their topics for the course
                $sql = "SELECT u.unit_id, u.unit_number, u.title, t.topic_name, t.video_link, t.notes,t.topic_id
        FROM unit u 
        LEFT JOIN important_topic t ON u.unit_id = t.unit_id 
        WHERE u.course_id = ? 
        ORDER BY u.unit_number, t.topic_id";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $course_id);
                $stmt->execute();
                $result = $stmt->get_result();

                $units = [];
                while ($row = $result->fetch_assoc()) {
                    // Add unit_number to the units array
                    $units[$row['unit_id']]['unit_number'] = $row['unit_number'];
                    $units[$row['unit_id']]['title'] = $row['title'];
                    $units[$row['unit_id']]['topics'][] = [
                        'topic_id' => $row['topic_id'],
                        'topic_name' => $row['topic_name'],
                        'video_link' => $row['video_link'],
                        'notes' => $row['notes']
                    ];
                }

                $stmt->close();
                $conn->close();

                // Return the data as JSON
                echo json_encode(['units' => $units, 'courseStatus' => $courseStatus]);
            } catch (Exception $e) {
                $conn->rollback();
                echo json_encode(['status' => 500, 'message' => 'Error: ' . $e->getMessage()]);
            }
            break;
        case 'delete_course':
            $course_id = (int)$_POST['course_id'];

            // Delete related topics and units first (if needed, due to foreign key constraints)
            $conn->query("DELETE FROM important_topic WHERE unit_id IN (SELECT unit_id FROM unit WHERE course_id = $course_id)");
            $conn->query("DELETE FROM unit WHERE course_id = $course_id");

            // Delete the course
            $sql = "DELETE FROM course WHERE course_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $course_id);

            if ($stmt->execute()) {
                echo json_encode(['status' => 200, 'message' => 'data deleted successfully.']);
            } else {
                echo "error: " . $stmt->error;
            }

            $stmt->close();
            break;
        case "edit_topic":
            if (isset($_POST['unit_id'], $_POST['topic_id'], $_POST['topic_name'], $_POST['video_link'], $_POST['course_id'])) {
                $unit_id = $_POST['unit_id'];
                $topic_id = $_POST['topic_id'];
                $topic_name = $_POST['topic_name'];
                $video_link = $_POST['video_link'];
                $notes_link = isset($_POST['notes']) ? $_POST['notes'] : '';
                $course_id = $_POST['course_id'];



                // Fetch the current notes (file path) from the database
                $sql = "SELECT notes FROM important_topic WHERE topic_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $topic_id);
                $stmt->execute();
                $stmt->bind_result($currentNotesLink);
                $stmt->fetch();
                $stmt->close();

                // Check if a new file is uploaded for notes
                if (isset($_FILES['topic_file']) && $_FILES['topic_file']['error'] == 0) {
                    $file = $_FILES['topic_file'];
                    $fileName = $file['name'];
                    $fileTmpName = $file['tmp_name'];
                    $uploadDir = 'uploads/';
                    $uploadFile = $uploadDir . basename($fileName);

                    // Delete the old file if it exists
                    if (!empty($currentNotesLink) && file_exists($currentNotesLink)) {
                        unlink($currentNotesLink); // Delete the old file
                    }

                    if (move_uploaded_file($fileTmpName, $uploadFile)) {
                        $notes_link = $uploadFile; // Update the notes link with the new file path
                    } else {
                        echo json_encode(['status' => 500, 'message' => 'Error uploading the file.']);
                        exit;
                    }
                }

                // Update the topic
                $sql = "UPDATE important_topic SET topic_name = ?, video_link = ?, notes = ? WHERE topic_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssi", $topic_name, $video_link, $notes_link, $topic_id);

                if ($stmt->execute()) {
                    if ($stmt->affected_rows > 0) {
                        // Update course status to Pending
                        $sql = "UPDATE course SET status = 'Pending' WHERE course_id = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $course_id);

                        if ($stmt->execute()) {

                            echo json_encode(['status' => 200, 'message' => 'Unit and course status updated successfully.']);
                        } else {
                            echo json_encode(['status' => 500, 'message' => 'Error updating course status.']);
                        }
                    } else {
                        echo json_encode(['status' => 400, 'message' => 'No rows updated for the topic.']);
                    }
                } else {
                    echo json_encode(['status' => 500, 'message' => 'Failed to update the topic.']);
                }

                $stmt->close();
            } else {
                echo json_encode(['status' => 400, 'message' => 'Missing required parameters.']);
                error_log('Missing parameters: ' . json_encode($_POST)); // Debug missing parameters
            }
            break;
        case 'delete_topic':
            $topic_id = intval($_POST['topic_id']);

            // Check if topic_id is valid
            if ($topic_id > 0) {
                $sql = "DELETE FROM important_topic WHERE topic_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $topic_id);

                if ($stmt->execute()) {
                    echo json_encode(['status' => 200, 'message' => 'deleted successfully.']);
                } else {
                    echo "error";
                }

                $stmt->close();
            } else {
                echo "invalid";
            }

            $conn->close();
            break;

        default:
            echo json_encode(['status' => 400, 'message' => 'Invalid action specified.']);
    }
}

// Modify the AJAX request handler to include the serve file action
else if (isset($_GET['action']) || isset($_POST['action'])) {
    $response = ['success' => false];

    $action = $_POST['action'] ?? $_GET['action'];

    switch ($action) {
        case 'getCourseDetails':
            if (isset($_POST['course_id'])) {
                $details = getCourseDetails($_POST['course_id']);
                if ($details) {
                    $response = ['success' => true, 'data' => $details];
                }
            }
            break;

        case 'updateStatus':
            if (isset($_POST['course_id']) && isset($_POST['status'])) {
                $reason = $_POST['reason'] ?? '';
                $result = updateCourseStatus($_POST['course_id'], $_POST['status'], $reason);
                if ($result) {
                    $response = ['success' => true, 'data' => $result];
                }
            }
            break;

        case 'getSyllabus':
            if (isset($_POST['course_id'])) {
                echo json_encode(getSyllabus($_POST['course_id']));
                exit;
            }
            break;

        case 'getApprovalTableData':
            $result = getCourseApprovals();
            $data = [];
            if ($result->num_rows > 0) {
                $counter = 1;
                while ($row = $result->fetch_assoc()) {
                    $statusClass = match ($row['status']) {
                        'Approved' => 'bg-success',
                        'Rejected' => 'bg-danger',
                        default => 'bg-warning'
                    };

                    $data[] = [
                        'counter' => $counter++,
                        'course_id' => $row['course_id'],
                        'course_name' => htmlspecialchars($row['course_name']),
                        'staff_name' => htmlspecialchars($row['staff_name']),
                        'academic_year' => $row['academic_year'],
                        'semester' => $row['semester'],
                        'status' => $row['status'],
                        'statusClass' => $statusClass
                    ];
                }
            }
            $response = ['success' => true, 'data' => $data];
            break;

        case 'getAvailableTableData':
            $result = getAvailableCourses();
            $data = [];
            if ($result->num_rows > 0) {
                $counter = 1;
                while ($row = $result->fetch_assoc()) {
                    $data[] = [
                        'counter' => $counter++,
                        'course_id' => $row['course_id'],
                        'course_name' => htmlspecialchars($row['course_name']),
                        'staff_name' => htmlspecialchars($row['staff_name']),
                        'academic_year' => $row['academic_year'],
                        'semester' => $row['semester']
                    ];
                }
            }
            $response = ['success' => true, 'data' => $data];
            break;

        case 'serveFile':
            if (isset($_GET['file'])) {
                serveFile($_GET['file']);
            }
            break;

        case 'getcourse':
            if (isset($_GET['semester'])) {
                $semester = trim($_GET['semester']);
                
                error_log("getcourse action called - Department: $department, Semester: $semester");
                
                $courses = getcourses($semester);
                
                // Add header to help with debugging
                header('Content-Type: application/json');
                
                if (empty($courses)) {
                    error_log("No courses found for semester: $semester");
                    echo json_encode([]);
                } else {
                    error_log("Returning " . count($courses) . " courses");
                    echo json_encode($courses);
                }
                exit;
            } else {
                error_log("Semester parameter missing in getcourse action");
                echo json_encode(['error' => 'Semester is missing.']);
                exit;
            }
            break;
        case 'getunits':
            if (isset($_POST['course_id'])) {
                $courseId = $_POST['course_id'];
                $response = getunits($courseId);
            } else {
                $response = json_encode(['error' => 'Course ID is missing.']);
            }
            break;
        
        case 'getDepartmentStats':
            if (isset($_SESSION['user']['dept'])) {
                $department = $_SESSION['user']['dept'];
                $stats = getDepartmentStats($department);
                $response = ['success' => true, 'data' => $stats];
            } else {                
                echo json_encode(['success' => false, 'message' => 'Department not found']);
                exit;
            }
            break;        
        case 'getStaffStats':
            if (isset($_SESSION['user']['uid'])) {
                $staffId = $_SESSION['user']['uid'];
                $stats = getStaffStats($staffId);
                echo json_encode(['success' => true, 'data' => $stats]);
            } else {                echo json_encode(['success' => false, 'message' => 'Staff ID not found']);
            }
            exit;
            break;
    }

    echo json_encode($response);
    exit;
}
