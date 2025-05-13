<?php
include 'db.php';
include 'session.php';
include 'backend.php';
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script src="fontFix.js"></script>



</head>

<body>
    <?php include './utils/ui/sidebar.php'; ?>

    <div class="content">
        <div class="loader-container" id="loaderContainer">
            <div class="loader"></div>
        </div>

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

        <div class="container-fluid">
            <div class="backToDashboard " id="backToDashboard">
                <button class="btn btn-primary d-block m-3  " id="backToDashboard" onclick="backToDashboard()">Back to Dashboard</button>
            </div>
            <div class="container" id="courseContainer">

                <div id="contentWrapper" class="d-flex">


                    <div id="coursesContainer" class="row flex-grow-1">

                        <!-- Courses will be dynamically added here -->
                    </div>
                </div>
            </div>

            <div class="main-wrapper-unique p-2" id="maincontentWrapper" style="display: none;">
                <div class="d-flex justify-content-between">
                    <button id="fullscreenButton" class="btn btn-danger m-2 d-block" onclick="toggleFullScreen()">Fullscreen</button>
                    <button class="btn btn-secondary d-block m-2" id="backToCourse" onclick="backToCourse()">Back to Course</button>
                    <button class="btn btn-primary d-lg-none m-2" id="sidebarToggler" onclick="toggleSidebar2('sidebar-container-unique')" style="cursor: pointer;">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>

                <div class="content-container d-flex ">
                    <!-- Video Wrapper -->
                    <div class="video-section flex-grow-1">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="video-wrapper-unique" id="videoWrapper">
                                <!-- Video or PDF will be dynamically inserted -->
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="sidebar-section">
                        <div class="sidebar-container-unique p-4">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-graduation-cap text-primary me-2"></i>
                                    <h5 class="mb-0">Course Content</h5>
                                </div>
                                <div class="sidebarclose">
                                    <i class="fas fa-times close-icon" id="sidebarClose" style="cursor: pointer;" onclick="toggleSidebar2('sidebar-container-unique')"></i>
                                </div>
                            </div>
                            <div class="accordion" id="unitsAccordion">
                                <!-- Units will be dynamically inserted here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="overlay-unique" onclick="toggleSidebar2('sidebar-container-unique')"></div>

        <?php include './utils/ui/footer.php'; ?>
    </div>

    <script>
        function backToDashboard() {
            sessionStorage.removeItem('courses');
            window.location.href = 'studentDashboard.php';
        }

        $(document).ready(function() {
            var sessionKey = "courses";
            var semester = sessionStorage.getItem("semester");
            
            // Clear session storage to force a refresh for debugging
            sessionStorage.removeItem(sessionKey);
            
            // If semester is not set in sessionStorage, get it from PHP session
            if (!semester) {
                semester = "<?php echo isset($_SESSION['user']['year']) ? $_SESSION['user']['year'] : '1'; ?>";
                sessionStorage.setItem("semester", semester);
            }
            
            console.log("Using semester:", semester); 
            console.log("Department from session:", "<?php echo $_SESSION['user']['dept']; ?>");
            console.log("Session data:", <?php echo json_encode($_SESSION['user']); ?>);
            
            if (sessionStorage.getItem(sessionKey)) {
                var courses = JSON.parse(sessionStorage.getItem(sessionKey));
                console.log("Courses from sessionStorage:", courses.length);
                renderCourses(courses);
            } else {
                // Show loader while fetching courses
                $("#loaderContainer").addClass("show");
                
                // Clear sessionStorage to avoid using cached data
                sessionStorage.removeItem(sessionKey);
                
                $.ajax({
                    url: "backend.php",
                    type: "GET",
                    data: {
                        action: "getcourse",
                        semester: semester
                    },
                    dataType: "json",
                    success: function(data) {
                        $("#loaderContainer").removeClass("show");
                        
                        console.log("AJAX success, received data:", data);
                        
                        if (data && Array.isArray(data) && data.length > 0) {
                            console.log("Found " + data.length + " courses");
                            sessionStorage.setItem(sessionKey, JSON.stringify(data));
                            renderCourses(data);
                        } else {
                            console.warn("No courses found. Adding debug information to UI");
                            $("#coursesContainer").html(`
                                <div class="col-12 text-center">
                                    <div class="alert alert-info" role="alert">
                                        <h3>No courses available for this semester.</h3>
                                        <p>Please check back later or contact your administrator.</p>
                                        <hr>
                                        <p class="mb-0">Debug info: Semester=${semester}, Dept=<?php echo $_SESSION['user']['dept']; ?></p>
                                    </div>
                                </div>
                            `);
                        }
                    },
                    error: function(xhr, status, error) {
                        $("#loaderContainer").removeClass("show");
                        console.error("AJAX Error:", status, error);
                        console.error("Response:", xhr.responseText);
                        
                        $("#coursesContainer").html(`
                            <div class="col-12 text-center">
                                <div class="alert alert-danger" role="alert">
                                    <h3>Error fetching courses</h3>
                                    <p>Please try again later or contact your administrator.</p>
                                    <p>Error: ${error}</p>
                                </div>
                            </div>
                        `);
                    }
                });
            }
            
            function renderCourses(courses) {
                $("#coursesContainer").empty();
                
                // Add custom style for consistent card sizing
                if (!$('#course-card-styles').length) {
                    $('head').append(`
                        <style id="course-card-styles">
                            .course-card .image-wrapper {
                                height: 160px;
                                overflow: hidden;
                            }
                            .course-card .image-wrapper img {
                                width: 100%;
                                height: 100%;
                                object-fit: cover;
                            }
                            .course-card .card-title {
                                font-size: 1.2rem;
                                height: 3rem;
                                overflow: hidden;
                                display: -webkit-box;
                                -webkit-line-clamp: 2;
                                -webkit-box-orient: vertical;
                                margin-bottom: 0.75rem;
                            }
                            .course-card .course-details {
                                min-height: 4rem;
                            }
                            .course-card .detail-item span {
                                white-space: nowrap;
                                overflow: hidden;
                                text-overflow: ellipsis;
                                display: block;
                            }
                            .course-card {
                                transition: transform 0.3s, box-shadow 0.3s;
                                margin-bottom: 20px;
                            }
                            .course-card:hover {
                                transform: translateY(-5px);
                                box-shadow: 0 10px 20px rgba(0,0,0,0.1);
                            }
                            
                            /* Enhanced styles for more consistent card sizing */
                            .course-card {
                                display: flex;
                                flex-direction: column;
                                height: 380px !important; /* Force consistent height */
                            }
                            .course-card .card-body {
                                display: flex;
                                flex-direction: column;
                                flex: 1;
                                padding: 1rem;
                            }
                            .course-card .card-title {
                                height: 48px; /* Fixed height for title */
                                margin-bottom: 10px;
                                display: -webkit-box;
                                -webkit-line-clamp: 2;
                                -webkit-box-orient: vertical;
                                overflow: hidden;
                            }
                            .course-card .course-details {
                                flex: 1;
                                overflow: hidden;
                            }
                            .course-card .detail-item {
                                margin-bottom: 8px;
                                white-space: nowrap;
                                overflow: hidden;
                                text-overflow: ellipsis;
                            }
                            .semester-1-card {
                                border: 2px solid #4e73df !important;
                            }
                        </style>
                    `);
                }
                
                courses.forEach(function(course) {
                    // Limit description length to prevent layout issues
                    const shortDescription = course.description.length > 30 ? 
                        course.description.substring(0, 30) + '...' : 
                        course.description;
                    
                    // Add semester-specific class for debugging
                    const currentSemester = "<?php echo isset($_SESSION['user']['year']) ? $_SESSION['user']['year'] : '1'; ?>";
                    const semesterClass = currentSemester === "1" ? "semester-1-card" : "";
                    
                    $("#coursesContainer").append(`
                        <div class="col-sm-6 col-lg-4 col-xl-3 mb-4">
                            <div class="course-card card h-100 shadow-sm ${semesterClass}">
                                <div class="image-wrapper">
                                    <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-4.0.3&auto=format&fit=crop&w=1740&q=80" class="card-img-top" alt="Course Image">
                                    <div class="image-overlay"></div>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <h3 class="card-title">${course.course_name}</h3>
                                    <div class="course-details">
                                        <div class="detail-item">
                                            <i class="fas fa-graduation-cap text-primary"></i>
                                            <span>Academic Year: ${course.academic_year}</span>
                                        </div>
                                        <div class="detail-item">
                                            <i class="fas fa-calendar text-primary"></i>
                                            <span>${shortDescription}</span>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary mt-auto view-course-btn" onclick="showUnitDetails('${course.course_id}', '${course.syllabus}')">
                                        <i class="fas fa-book me-2"></i>
                                        View Course
                                    </button>
                                </div>
                            </div>
                        </div>
                    `);
                });
            }
        });

        function showUnitDetails(courseId, syllabus) {

            $('#backToDashboard').hide();
            $('#coursesContainer').hide();
            $('#maincontentWrapper').show();

            $.ajax({
                url: 'backend.php',
                type: 'POST',
                data: {
                    action: 'getunits',
                    course_id: courseId
                },
                dataType: 'json',
                success: function(data) {

                    const accordion = document.getElementById('unitsAccordion');
                    if (accordion) {
                        accordion.innerHTML = '';
                        if (!document.getElementById('syllabusButton')) {
                            const syllabusButtonHtml = `
                    <div class="mb-3" id="syllabusButton">
                        <a href="#" class="btn btn-outline-primary w-100" onclick="showTopicContent('${syllabus}', 'pdf')" style="font-family: Arial, sans-serif;">
                            <i class="fas fa-file-alt me-2"></i>
                            View Syllabus
                        </a>
                    </div>
                    `;
                            accordion.insertAdjacentHTML('beforebegin', syllabusButtonHtml);
                        }

                        // Render units and topics
                        if (data.units.length > 0) {
                            data.units.forEach((unit) => {
                                const unitHtml = `
                        <div class="accordion-item border-0 mb-2">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed bg-light" type="button" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#unit${unit.unit_id}">
                                    <i class="fas fa-book text-primary me-2"></i>
                                    Unit ${unit.unit_number}: ${unit.title}
                                </button>
                            </h2>
                            <div id="unit${unit.unit_id}" class="accordion-collapse collapse" data-bs-parent="#unitsAccordion">
                                <div class="accordion-body p-0">
                                    <div class="accordion" id="topicsAccordion${unit.unit_id}">
                                        <!-- Topics will be dynamically populated -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        `;
                                accordion.insertAdjacentHTML('beforeend', unitHtml);

                                // Pass correct topics and unit_id to loadTopicsIntoUnit
                                loadTopicsIntoUnit(unit.unit_id, unit.topics);
                            });

                            // Show the first unit by default
                            $('#unit' + data.units[0].unit_id).addClass('show');
                            if (data.units[0].topics && data.units[0].topics.length > 0) {
                                showTopicContent(data.units[0].topics[0].video_link, 'video');
                            }
                        } else {
                            accordion.innerHTML = '<p>No units found for this course.</p>';
                        }
                    } else {
                        console.error("Accordion element not found!");
                    }

                    $('#unitcontent').show();
                    $('#sidebarOverlay').show();
                },
                error: function() {
                    alert('Error fetching units.');
                }
            });
        }

        function loadTopicsIntoUnit(unitId, topics) {
            const topicsAccordion = document.getElementById(`topicsAccordion${unitId}`);
            if (topicsAccordion) {
                topicsAccordion.innerHTML = '';

                if (Array.isArray(topics) && topics.length > 0) {
                    topics.forEach(function(topic) {
                        const topicHtml = `
                <div class="accordion-item border-0">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed topic-item" 
                                type="button" 
                                data-bs-toggle="collapse" 
                                data-bs-target="#topic${unitId}-${topic.topic_id}">
                            <i class="fas fa-file-alt text-muted me-2"></i>
                            ${topic.topic_name}
                        </button>
                    </h3>
                    <div id="topic${unitId}-${topic.topic_id}" class="accordion-collapse collapse" data-bs-parent="#topicsAccordion${unitId}">
                        <div class="accordion-body subtopic-actions">
                            <a href="#" class="nav-link mb-2" onclick="showTopicContent('${topic.video_link}', 'video')" style="font-family: Arial, sans-serif;">
                                <i class="fab fa-youtube me-2" style="color: red;"></i>
                                Watch Video
                            </a>
                            <a href="#" class="nav-link" onclick="showTopicContent('${topic.notes}', 'pdf')" style="font-family: Arial, sans-serif;">
                                <i class="fas fa-file-alt me-2" style="color: blue;"></i>
                                View Materials
                            </a>
                        </div>
                    </div>
                </div>
                `;
                        topicsAccordion.insertAdjacentHTML('beforeend', topicHtml);
                    });
                } else {
                    topicsAccordion.innerHTML = '<p>No topics found for this unit.</p>';
                }
            } else {
                console.error(`Topics accordion for unit ${unitId} not found!`);
            }
        }

        function showTopicContent(data, type) {
            if (document.fullscreenElement) {
                document.exitFullscreen();
            }
            toggleSidebar2('sidebar-container-unique');
            if (type === 'video') {
                let videoId;
                if (data.includes('youtube.com/watch?v=')) {
                    videoId = data.split('v=')[1].split('&')[0];
                    videoId = data.split('v=')[1].split('&')[0];
                } else if (data.includes('youtu.be/')) {
                    videoId = data.split('youtu.be/')[1].split('?')[0];
                    videoId = data.split('youtu.be/')[1].split('?')[0];
                } else {
                    videoId = data;
                    videoId = data;
                }

                const videoHtml = `
                    <iframe id="videoFrame"
                            src="https://www.youtube.com/embed/${videoId}"   
                            width="100%" height="100%" frameborder="0"
                            title="Video"
                            allowfullscreen></iframe>
                `;
                videoWrapper.innerHTML = videoHtml;
            } else if (type === 'pdf') {
                const pdfHtml = `
            <div class="pdf-container" style="height: 100%; overflow: hidden; background: white; display: flex; flex-direction: column;">
                <div class="pdf-scroll-container" style="flex-grow: 1; overflow-y: auto; padding: 10px;">
                    <div id="pdf-pages-container"></div>
                </div>
                <div class="pdf-controls" style="position: sticky; bottom: 0; background: white; z-index: 100; padding: 10px; border-top: 1px solid #ddd; text-align: center;">
                    <span class="nav-buttons">
                        <button id="prev" class="btn btn-sm btn-primary">
                            <i class="fas fa-chevron-left"></i> Previous
                        </button>
                        <span style="margin: 0 15px;">Page: <span id="page_num"></span> / <span id="page_count"></span></span>
                        <button id="next" class="btn btn-sm btn-primary">
                            Next <i class="fas fa-chevron-right"></i>
                        </button>
                    </span>
                </div>
            </div>
        `;
                videoWrapper.innerHTML = pdfHtml;

                // Add style for hiding navigation in mobile fullscreen
                const style = document.createElement('style');
                style.textContent = `
                    @media (max-width: 768px) {
                        .pdf-container:fullscreen .nav-buttons button {
                            display: none;
                        }
                    }
                `;
                document.head.appendChild(style);

                let pdfDoc = null,
                    pageNum = 1,
                    pageRendering = false,
                    pageNumPending = null,
                    scale = window.innerWidth <= 768 ? 2.0 : 1.5,
                    isFullscreen = false;

                // Cleanup function to reset PDF viewer state
                function cleanupPdfViewer() {
                    const pagesContainer = document.getElementById('pdf-pages-container');
                    if (pagesContainer) {
                        pagesContainer.innerHTML = '';
                    }

                    // Remove any existing scroll listeners
                    const scrollContainer = document.querySelector('.pdf-scroll-container');
                    if (scrollContainer) {
                        const oldScrollContainer = scrollContainer.cloneNode(true);
                        scrollContainer.parentNode.replaceChild(oldScrollContainer, scrollContainer);
                    }

                    // Reset variables
                    pageNum = 1;
                    pageRendering = false;
                    pageNumPending = null;
                    scale = window.innerWidth <= 768 ? 2.0 : 1.5;
                }

                document.addEventListener('fullscreenchange', function() {
                    isFullscreen = !!document.fullscreenElement;
                    if (pdfDoc) {
                        if (isFullscreen && window.innerWidth <= 768) {
                            renderAllPages();
                        } else {
                            cleanupPdfViewer(); // Clean up when exiting fullscreen
                            renderSinglePage(pageNum);
                        }
                    }
                });

                async function renderAllPages() {
                    const pagesContainer = document.getElementById('pdf-pages-container');
                    pagesContainer.innerHTML = '';

                    for (let pageNumber = 1; pageNumber <= pdfDoc.numPages; pageNumber++) {
                        const canvas = document.createElement('canvas');
                        canvas.id = `page-${pageNumber}`;
                        canvas.dataset.pageNumber = pageNumber;
                        canvas.style.display = 'block';
                        canvas.style.margin = '10px auto';
                        pagesContainer.appendChild(canvas);

                        const page = await pdfDoc.getPage(pageNumber);
                        const viewport = page.getViewport({
                            scale: 4.0
                        });

                        canvas.height = viewport.height;
                        canvas.width = viewport.width;
                        canvas.style.width = '100%';
                        canvas.style.height = 'auto';

                        const renderContext = {
                            canvasContext: canvas.getContext('2d'),
                            viewport: viewport
                        };

                        await page.render(renderContext).promise;
                    }

                    // Add scroll event listener for page tracking
                    const scrollContainer = document.querySelector('.pdf-scroll-container');
                    scrollContainer.addEventListener('scroll', function() {
                        if (isFullscreen && window.innerWidth <= 768) {
                            const canvases = document.querySelectorAll('#pdf-pages-container canvas');
                            const containerTop = scrollContainer.scrollTop;
                            const containerHeight = scrollContainer.clientHeight;
                            const containerMiddle = containerTop + (containerHeight / 2);

                            let currentPage = 1;
                            for (const canvas of canvases) {
                                const rect = canvas.getBoundingClientRect();
                                const canvasMiddle = rect.top + (rect.height / 2);
                                if (canvasMiddle > 0 && canvasMiddle < containerHeight) {
                                    currentPage = parseInt(canvas.dataset.pageNumber);
                                    break;
                                }
                            }
                            document.getElementById('page_num').textContent = currentPage;
                        }
                    });
                }

                function renderSinglePage(num) {
                    pageRendering = true;
                    const pagesContainer = document.getElementById('pdf-pages-container');
                    pagesContainer.innerHTML = ''; // Clear existing pages

                    const canvas = document.createElement('canvas');
                    canvas.id = 'pdf-canvas';
                    canvas.style.display = 'block';
                    canvas.style.margin = '0 auto';
                    pagesContainer.appendChild(canvas);

                    pdfDoc.getPage(num).then(function(page) {
                        const viewport = page.getViewport({
                            scale: isFullscreen ?
                                (window.innerWidth <= 768 ? 4.0 : 2.0) : scale
                        });

                        canvas.height = viewport.height;
                        canvas.width = viewport.width;

                        const renderContext = {
                            canvasContext: canvas.getContext('2d'),
                            viewport: viewport
                        };

                        page.render(renderContext).promise.then(function() {
                            pageRendering = false;
                            if (pageNumPending !== null) {
                                renderSinglePage(pageNumPending);
                                pageNumPending = null;
                            }
                            document.querySelector('.pdf-scroll-container').scrollTop = 0;
                        });

                        document.getElementById('page_num').textContent = num;
                    });
                }

                // Modify navigation buttons
                document.getElementById('prev').addEventListener('click', function() {
                    if (isFullscreen && window.innerWidth <= 768) {
                        // Scroll to previous page in continuous mode
                        const currentScroll = document.querySelector('.pdf-scroll-container').scrollTop;
                        const pageHeight = document.querySelector('canvas').height;
                        document.querySelector('.pdf-scroll-container').scrollTop = currentScroll - pageHeight - 20;
                    } else if (pageNum > 1) {
                        pageNum--;
                        renderSinglePage(pageNum);
                    }
                });

                document.getElementById('next').addEventListener('click', function() {
                    if (isFullscreen && window.innerWidth <= 768) {
                        // Scroll to next page in continuous mode
                        const currentScroll = document.querySelector('.pdf-scroll-container').scrollTop;
                        const pageHeight = document.querySelector('canvas').height;
                        document.querySelector('.pdf-scroll-container').scrollTop = currentScroll + pageHeight + 20;
                    } else if (pageNum < pdfDoc.numPages) {
                        pageNum++;
                        renderSinglePage(pageNum);
                    }
                });

                // Load the PDF
                pdfjsLib.getDocument(data).promise.then(function(pdfDoc_) {
                    pdfDoc = pdfDoc_;
                    document.getElementById('page_count').textContent = pdfDoc.numPages;
                    renderSinglePage(pageNum);
                }).catch(function(error) {
                    console.error('Error loading PDF:', error);
                    videoWrapper.innerHTML = '<div class="alert alert-danger">Error loading PDF. Please try again.</div>';
                });
            }
        }

        function toggleFullScreen() {
            const videoWrapper = document.querySelector("#videoWrapper");
            const iframe = videoWrapper.querySelector("iframe");
            const pdfContainer = videoWrapper.querySelector(".pdf-container");

            if (document.fullscreenElement) {
                document.exitFullscreen();
            } else {
                if (iframe) {
                    iframe.requestFullscreen();
                } else if (pdfContainer) {
                    pdfContainer.requestFullscreen();
                } else {
                    alert("No content available for fullscreen.");
                }
            }
        }

        function toggleSidebar2(sidebarId) {
            const sidebar = document.getElementById(sidebarId);
            const overlay = document.querySelector('.overlay-unique');
            if (sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            } else {
                sidebar.classList.add('active');
                overlay.classList.add('active');
            }
        }

        function showCourseContent(courseId) {

            document.getElementById('courseContainer').style.display = 'none';
            document.getElementById('contentWrapper').style.display = 'block';
            showUnitDetails(courseId);
        }

        function backToCourse() {
            $('.backToDashboard').show();
            $('#coursesContainer').show();
            document.getElementById('maincontentWrapper').style.display = 'none';
        }

        const loaderContainer = document.getElementById("loaderContainer");

        function showLoader() {
            loaderContainer.classList.add("show");
        }

        function hideLoader() {
            loaderContainer.classList.remove("show");
        }
        document.addEventListener("DOMContentLoaded", function() {
            const loaderContainer = document.getElementById("loaderContainer");
            const contentWrapper = document.getElementById("contentWrapper");
            let loadingTimeout;

            function hideLoader() {
                loaderContainer.classList.add("hide");
                contentWrapper.classList.add("show");
            }

            function showError() {
                console.error("Page load took too long or encountered an error");
            }
            loadingTimeout = setTimeout(showError, 10000);

            window.onload = function() {
                clearTimeout(loadingTimeout);
                setTimeout(hideLoader, 500);
            };

            window.onerror = function(msg, url, lineNo, columnNo, error) {
                clearTimeout(loadingTimeout);
                showError();
                return false;
            };
        });
        const hamburger = document.getElementById("hamburger");
        const sidebar = document.getElementById("sidebar");
        const body = document.body;
        const mobileOverlay = document.getElementById("mobileOverlay");

        function toggleSidebar() {
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle("mobile-show");
                mobileOverlay.classList.toggle("show");
                body.classList.toggle("sidebar-open");
            } else {
                sidebar.classList.toggle("collapsed");
            }
        }
        hamburger.addEventListener("click", toggleSidebar);
        mobileOverlay.addEventListener("click", toggleSidebar);

        const userMenu = document.getElementById("userMenu");
        const dropdownMenu = userMenu.querySelector(".dropdown-menu");

        userMenu.addEventListener("click", (e) => {
            e.stopPropagation();
            dropdownMenu.classList.toggle("show");
        });

        document.addEventListener("click", () => {
            dropdownMenu.classList.remove("show");
        });

        const menuItems = document.querySelectorAll(".has-submenu");
        menuItems.forEach((item) => {
            item.addEventListener("click", () => {
                const submenu = item.nextElementSibling;
                item.classList.toggle("active");
                submenu.classList.toggle("active");
            });
        });

        window.addEventListener("resize", () => {
            if (window.innerWidth <= 768) {
                sidebar.classList.remove("collapsed");
                sidebar.classList.remove("mobile-show");
                mobileOverlay.classList.remove("show");
                body.classList.remove("sidebar-open");
            } else {
                sidebar.style.transform = "";
                mobileOverlay.classList.remove("show");
                body.classList.remove("sidebar-open");
            }
        });



        function toggleSidebar2(sidebarClass) {
            document.querySelector(`.${sidebarClass}`).classList.toggle("active");
            document.querySelector(".overlay-unique").classList.toggle("active");
        }
    </script>

</body>

</html>