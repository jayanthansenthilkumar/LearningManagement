const loaderContainer = document.getElementById("loaderContainer");

function showLoader() {
  loaderContainer.classList.add("show");
}

function hideLoader() {
  loaderContainer.classList.remove("show");
}

//    automatic loader
document.addEventListener("DOMContentLoaded", function () {
  const loaderContainer = document.getElementById("loaderContainer");
  const contentWrapper = document.getElementById("contentWrapper");
  let loadingTimeout;

  function hideLoader() {
    loaderContainer.classList.add("hide");
    contentWrapper.classList.add("show");
  }

  function showError() {
    console.error("Page load took too long or encountered an error");
    // You can add custom error handling here
  }

  // Set a maximum loading time (10 seconds)
  loadingTimeout = setTimeout(showError, 10000);

  // Hide loader when everything is loaded
  window.onload = function () {
    clearTimeout(loadingTimeout);

    // Add a small delay to ensure smooth transition
    setTimeout(hideLoader, 500);
  };

  // Error handling
  window.onerror = function (msg, url, lineNo, columnNo, error) {
    clearTimeout(loadingTimeout);
    showError();
    return false;
  };
});

// Toggle Sidebar
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
// Toggle User Menu
const userMenu = document.getElementById("userMenu");
const dropdownMenu = userMenu.querySelector(".dropdown-menu");

userMenu.addEventListener("click", (e) => {
  e.stopPropagation();
  dropdownMenu.classList.toggle("show");
});

// Close dropdown when clicking outside
document.addEventListener("click", () => {
  dropdownMenu.classList.remove("show");
});

// Toggle Submenu
const menuItems = document.querySelectorAll(".has-submenu");
menuItems.forEach((item) => {
  item.addEventListener("click", () => {
    const submenu = item.nextElementSibling;
    item.classList.toggle("active");
    submenu.classList.toggle("active");
  });
});

// Handle responsive behavior
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

function watchVideo(url, topicName) {
  // Hide review modal
  const reviewModal = bootstrap.Modal.getInstance(
    document.getElementById("reviewModal")
  );
  reviewModal.hide();

  // Setup video modal
  const videoModal = new bootstrap.Modal(
    document.getElementById("courseVideoModal")
  );
  const videoFrame = document.getElementById("courseVideoFrame");
  const videoTitle = document.querySelector("#courseVideoModal .modal-title");

  // Update modal title
  videoTitle.textContent = topicName;

  // Convert URL if it's a YouTube link
  if (url.includes("youtube.com") || url.includes("youtu.be")) {
    const videoId = url.match(
      /(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i
    )?.[1];
    if (videoId) {
      url = `https://www.youtube.com/embed/${videoId}`;
    }
  }

  // Set iframe source
  videoFrame.src = url;

  // Show video modal
  videoModal.show();
}

// Add click handler for review buttons
document.addEventListener("DOMContentLoaded", function () {
  // Review button handler
  document.querySelectorAll(".btn-primary").forEach((button) => {
    button.addEventListener("click", function () {
      const courseId = this.closest("tr").dataset.courseId;
      loadCourseDetails(courseId);
    });
  });

  // Reject button handler
  document.querySelectorAll(".btn-danger").forEach((button) => {
    button.addEventListener("click", function () {
      const courseId = this.closest("tr").dataset.courseId;
      showRejectModal(courseId);
    });
  });
});
function showRejectModal(courseId) {
  // Clear previous reason
  document.getElementById("rejectReason").value = "";
  // Set course ID
  document.getElementById("rejectCourseId").value = courseId;
  // Show modal
  const rejectModal = new bootstrap.Modal(
    document.getElementById("rejectModal")
  );
  rejectModal.show();
}

function closeVideoModal() {
  document.getElementById("courseVideoFrame").src = "";

  const videoModal = bootstrap.Modal.getInstance(
    document.getElementById("courseVideoModal")
  );
  videoModal.hide();

  const reviewModal = new bootstrap.Modal(
    document.getElementById("reviewModal")
  );
  reviewModal.show();
}

// Maintain modal stack
document
  .getElementById("courseVideoModal")
  .addEventListener("hide.bs.modal", function () {
    document.body.classList.add("modal-open");
  });

document.addEventListener("DOMContentLoaded", function () {
  // Approve button handler
  document.querySelectorAll(".btn-success").forEach((button) => {
    button.addEventListener("click", function () {
      const courseId = this.closest("tr").dataset.courseId;
      submitApproval(courseId);
    });
  });
});

// First add this HTML for the notes modal
const unitNotesModalHTML = `
<div class="modal fade" id="unitNotesModal" tabindex="-1" aria-labelledby="unitNotesModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="unitNotesModalLabel">Unit Notes</h5>
            <button type="button" class="btn-close" onclick="closeUnitNotesModal()"></button>
        </div>
        <div class="modal-body">
            <div class="ratio ratio-16x9">
                <iframe id="unitNotesFrame" src="" allowfullscreen></iframe>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeUnitNotesModal()">Close</button>
        </div>
    </div>
</div>
</div>`;

document.body.insertAdjacentHTML("beforeend", unitNotesModalHTML);

// Function to show unit notes
function showUnitNotes(filePath, unitTitle) {
  // Hide review modal
  const reviewModal = bootstrap.Modal.getInstance(
    document.getElementById("reviewModal")
  );
  reviewModal.hide();

  // Setup notes modal
  const notesModal = new bootstrap.Modal(
    document.getElementById("unitNotesModal")
  );
  const notesFrame = document.getElementById("unitNotesFrame");
  const notesTitle = document.getElementById("unitNotesModalLabel");

  // Update modal title
  notesTitle.textContent = `Notes: ${unitTitle}`;

  // Set iframe source to file serving endpoint
  notesFrame.src = `backend.php?action=serveFile&file=${encodeURIComponent(
    filePath
  )}`;
  // Show notes modal
  notesModal.show();
}

// Function to close unit notes modal
function closeUnitNotesModal() {
  // Get modal instances
  const notesModal = bootstrap.Modal.getInstance(
    document.getElementById("unitNotesModal")
  );
  const notesFrame = document.getElementById("unitNotesFrame");

  // Clear notes source
  notesFrame.src = "";

  // Hide notes modal
  notesModal.hide();

  // Show review modal again
  const reviewModal = new bootstrap.Modal(
    document.getElementById("reviewModal")
  );
  reviewModal.show();
}

//change from fetch to post - task 1
function loadCourseDetails(courseId) {
  $.ajax({
    url: "backend.php",
    type: "POST",
    data: {
      action: "getCourseDetails",
      course_id: courseId,
    },
    dataType: "json",
    success: function (response) {
      if (response.success) {
        const data = response.data;

        document.getElementById("modalCourseName").textContent =
          data.course_name;
        document.getElementById("modalDescription").textContent =
          data.description;

        const unitsContainer = document.getElementById("unitsContainer");
        unitsContainer.innerHTML = "";

        // Add CSS for hover and accordion effects
        const style = document.createElement("style");
        style.textContent = `
                  .topic-item {
                      transition: background-color 0.3s ease;
                  }
                  .topic-item:hover {
                      background-color: #e9ecef !important;
                  }
                  .unit-header {
                      transition: transform 0.3s ease;
                      padding: 8px;
                      border-radius: 4px;
                      cursor: pointer;
                  }
                  .unit-header:hover {
                      transform: translateX(10px);
                  }
                  .topics-container {
                      display: none;
                      transition: all 0.3s ease;
                  }
                  .unit-section.active .topics-container {
                      display: block;
                  }
                  .chevron-icon {
                      transition: transform 0.3s ease;
                  }
                  .unit-section.active .chevron-icon {
                      transform: rotate(180deg);
                  }
              `;
        document.head.appendChild(style);

        data.units.forEach((unit, index) => {
          const unitElement = document.createElement("div");
          unitElement.className = "unit-section mb-4";
          unitElement.innerHTML = `
                      <div class="unit-header d-flex justify-content-between align-items-center mb-3">
                          <h5 class="mb-2 mb-md-0">
                              <i class="fas fa-book-open me-2 text-primary"></i>
                              Unit ${unit.unit_number}: ${unit.title}
                          </h5>
                          <i class="fas fa-chevron-down chevron-icon"></i>
                      </div>
                      <hr class="my-2">
                      
                      <div class="topics-container">
                          <div class="row row-cols-1 row-cols-md-2 g-3">
                              ${unit.topics
                                .map(
                                  (topic) => `
                                  <div class="col">
                                      <div class="topic-item d-flex justify-content-between align-items-center bg-light rounded p-2">
                                          <div class="me-auto">
                                              <i class="fas fa-circle-dot me-2 text-primary"></i>
                                              ${topic.topic_name}
                                          </div>
                                          <div class="d-flex flex-column align-items-end">
                                              <button class="btn btn-primary btn-sm mb-1" onclick="watchVideo('${topic.video_link}', '${topic.topic_name}')">
                                                  <i class="fas fa-play"></i>
                                              </button>
                                              <button class="btn btn-info btn-sm" onclick="showUnitNotes('${topic.notes}', '${topic.topic_name}')">
                                                  <i class="fas fa-file-alt"></i>
                                              </button>
                                          </div>
                                      </div>
                                  </div>
                              `
                                )
                                .join("")}
                          </div>
                      </div>
                  `;
          unitsContainer.appendChild(unitElement);

          // Add click event for accordion behavior
          const header = unitElement.querySelector(".unit-header");
          header.addEventListener("click", () => {
            // Close all other units
            document.querySelectorAll(".unit-section").forEach((section) => {
              if (section !== unitElement) {
                section.classList.remove("active");
              }
            });
            // Toggle current unit
            unitElement.classList.toggle("active");
          });

          // Show first unit by default
          if (index === 0) {
            unitElement.classList.add("active");
          }
        });

        // Show modal with proper backdrop handling
        const reviewModal = new bootstrap.Modal(
          document.getElementById("reviewModal"),
          {
            backdrop: true,
            keyboard: true,
          }
        );
        reviewModal.show();

        // Add event listener for modal close
        document
          .getElementById("reviewModal")
          .addEventListener("hidden.bs.modal", function () {
            const backdrop = document.querySelector(".modal-backdrop");
            if (backdrop) {
              backdrop.remove();
            }
            document.body.classList.remove("modal-open");
          });
      } else {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "Failed to load course details",
          timer: 3000,
        });
      }
    },
    error: function (xhr, status, error) {
      console.error("Error:", error);
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "An error occurred while loading course details",
        timer: 3000,
      });
    },
  });
}

//change from fetch to post - task 2
function submitRejection() {
  const courseId = document.getElementById("rejectCourseId").value;
  const reason = document.getElementById("rejectReason").value.trim();

  if (!reason) {
    Swal.fire({
      icon: "warning",
      title: "Warning",
      text: "Please provide a reason for rejection",
      timer: 3000,
    });
    return;
  }

  $.ajax({
    url: "backend.php",
    type: "POST",
    data: {
      action: "updateStatus",
      course_id: courseId,
      status: "Rejected",
      reason: reason,
    },
    dataType: "json",
    success: function (response) {
      if (response.success) {
        // Remove the row from the table
        const row = document.querySelector(`tr[data-course-id="${courseId}"]`);
        if (row) {
          row.remove();
        }

        // Close the modal and remove backdrop
        const rejectModal = bootstrap.Modal.getInstance(
          document.getElementById("rejectModal")
        );
        rejectModal.hide();

        // Remove modal backdrop and modal-open class
        const backdrop = document.querySelector(".modal-backdrop");
        if (backdrop) {
          backdrop.remove();
        }
        document.body.classList.remove("modal-open");
        document.body.style.overflow = "";
        document.body.style.paddingRight = "";

        // Reload both tables
        reloadTables();

        // Show success message
        Swal.fire({
          icon: "success",
          title: "Success",
          text: "Course rejected successfully",
          timer: 3000,
        });

        // If no more rows, show "No courses found" message
        const tbody = document.querySelector("tbody");
        if (tbody.children.length === 0) {
          tbody.innerHTML =
            '<tr><td colspan="9" class="text-center">No courses found</td></tr>';
        }
      } else {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "Failed to reject the course",
          timer: 3000,
        });
      }
    },
    error: function (xhr, status, error) {
      console.error("Error:", error);
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "An error occurred while rejecting the course",
        timer: 3000,
      });
    },
  });
}

// change from fetch to post - task 3
function submitApproval(courseId) {
  $.ajax({
    url: "backend.php",
    type: "POST",
    data: {
      action: "updateStatus",
      course_id: courseId,
      status: "Approved",
    },
    dataType: "json",
    success: function (response) {
      if (response.success) {
        // Remove the row from the table
        const row = document.querySelector(`tr[data-course-id="${courseId}"]`);
        if (row) {
          row.remove();
        }

        // Reload both tables
        reloadTables();

        // Show success message
        Swal.fire({
          icon: "success",
          title: "Success",
          text: "Course approved successfully",
          timer: 3000,
        });

        // If no more rows, show "No courses found" message
        const tbody = document.querySelector("tbody");
        if (tbody.children.length === 0) {
          tbody.innerHTML =
            '<tr><td colspan="9" class="text-center">No courses found</td></tr>';
        }
      } else {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "Failed to approve the course",
          timer: 3000,
        });
      }
    },
    error: function (xhr, status, error) {
      console.error("Error:", error);
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "An error occurred while approving the course",
        timer: 3000,
      });
    },
  });
}

//change from fetch to post - task 4
function viewSyllabus(courseId) {
  $.ajax({
    url: "backend.php",
    type: "POST",
    data: {
      action: "getSyllabus",
      course_id: courseId,
    },
    dataType: "json",
    success: function (data) {
      if (data.success && data.syllabus) {
        const syllabusModal = new bootstrap.Modal(
          document.getElementById("syllabusModal")
        );
        document.getElementById(
          "syllabusFrame"
        ).src = `backend.php?action=serveFile&file=${encodeURIComponent(
          data.syllabus
        )}`;
        syllabusModal.show();
      } else {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "Failed to load syllabus",
          timer: 3000,
        });
      }
    },
    error: function (xhr, status, error) {
      console.error("Error:", error);
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "An error occurred while loading the syllabus",
        timer: 3000,
      });
    },
  });
}

function closeSyllabusModal() {
  document.getElementById("syllabusFrame").src = "";
  const syllabusModal = bootstrap.Modal.getInstance(
    document.getElementById("syllabusModal")
  );
  syllabusModal.hide();
}

// Add modal stack maintenance
document
  .getElementById("syllabusModal")
  .addEventListener("hide.bs.modal", function () {
    document.body.classList.add("modal-open");
  });

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
document.addEventListener("DOMContentLoaded", function () {
  new TablePagination("approval_table");
  new TablePagination("available_table");
});

function reloadTables() {
  // Reload Approval Table
  $.ajax({
    url: "backend.php",
    type: "POST",
    data: {
      action: "getApprovalTableData",
    },
    dataType: "json",
    success: function (response) {
      if (response.success) {
        const approvalTable = document.getElementById("approval_table");

        // Remove existing pagination controls
        const oldControls = approvalTable.previousElementSibling;
        if (oldControls && oldControls.className === "table-controls") {
          oldControls.remove();
        }
        const oldPagination = approvalTable.nextElementSibling;
        if (oldPagination && oldPagination.className === "custom-pagination") {
          oldPagination.remove();
        }

        // Update table content
        const tbody = approvalTable.querySelector("tbody");
        if (response.data.length > 0) {
          let html = "";
          response.data.forEach((row) => {
            html += `<tr data-course-id="${row.course_id}">
                <td>${row.counter}</td>
                <td>${row.course_name}</td>
                <td>${row.staff_name}</td>
                <td>${row.academic_year}</td>
                <td>Semester ${row.semester}</td>
                <td><span class="badge ${row.statusClass}">${row.status}</span></td>
                <td class="text-center">
                    <button type="button" class="btn btn-info btn-sm" onclick="viewSyllabus(${row.course_id})" title="View Syllabus">
                        <i class="fas fa-file-alt"></i>
                    </button>
                </td>
                <td>
                    <div class="d-flex gap-2 justify-content-center">
                        <button type="button" class="btn btn-primary btn-sm" onclick="loadCourseDetails(${row.course_id})" title="Review">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button type="button" class="btn btn-success btn-sm" onclick="submitApproval(${row.course_id})" title="Approve">
                            <i class="fas fa-check"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-sm" onclick="showRejectModal(${row.course_id})" title="Reject">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </td>
            </tr>`;
          });
          tbody.innerHTML = html;
        } else {
          tbody.innerHTML =
            "<tr><td colspan='8' class='text-center'>No courses found</td></tr>";
        }

        // Reinitialize pagination
        new TablePagination("approval_table");
      }
    },
  });

  // Reload Available Table
  $.ajax({
    url: "backend.php",
    type: "POST",
    data: {
      action: "getAvailableTableData",
    },
    dataType: "json",
    success: function (response) {
      if (response.success) {
        const availableTable = document.getElementById("available_table");

        // Remove existing pagination controls
        const oldControls = availableTable.previousElementSibling;
        if (oldControls && oldControls.className === "table-controls") {
          oldControls.remove();
        }
        const oldPagination = availableTable.nextElementSibling;
        if (oldPagination && oldPagination.className === "custom-pagination") {
          oldPagination.remove();
        }

        // Update table content
        const tbody = availableTable.querySelector("tbody");
        if (response.data.length > 0) {
          let html = "";
          response.data.forEach((row) => {
            html += `<tr data-course-id="${row.course_id}">
                <td>${row.counter}</td>
                <td>${row.course_name}</td>
                <td>${row.staff_name}</td>
                <td>${row.academic_year}</td>
                <td>Semester ${row.semester}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-info btn-sm" onclick="viewSyllabus(${row.course_id})" title="View Syllabus">
                        <i class="fas fa-file-alt"></i>
                    </button>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-primary btn-sm" onclick="loadCourseDetails(${row.course_id})" title="Review">
                        <i class="fas fa-eye"></i>
                    </button>
                </td>
            </tr>`;
          });
          tbody.innerHTML = html;
        } else {
          tbody.innerHTML =
            "<tr><td colspan='7' class='text-center'>No courses available</td></tr>";
        }

        // Reinitialize pagination
        new TablePagination("available_table");
      }
    },
  });
}

// Add this after your existing code
document.addEventListener("DOMContentLoaded", function () {
  // Get the reject modal element
  const rejectModal = document.getElementById("rejectModal");

  // Add event listener for when the modal is hidden
  rejectModal.addEventListener("hidden.bs.modal", function () {
    // Remove backdrop and reset body
    const backdrop = document.querySelector(".modal-backdrop");
    if (backdrop) {
      backdrop.remove();
    }
    document.body.classList.remove("modal-open");
    document.body.style.overflow = "";
    document.body.style.paddingRight = "";
  });
});

