// SIDEBAR DROPDOWN
const allDropdown = document.querySelectorAll("#sidebar .side-dropdown");
const sidebar = document.getElementById('sidebar');
const toggleSidebar = document.querySelector('nav .toggle-sidebar');
const allSideDivider = document.querySelectorAll('#sidebar .divider');

// Handle dropdown functionality
allDropdown.forEach(item => {
  const a = item.parentElement.querySelector("a:first-child");
  a.addEventListener("click", function (e) {
    e.preventDefault();

    // Close other dropdowns when one is clicked
    if (!this.classList.contains("active")) {
      allDropdown.forEach((i) => {
        const aLink = i.parentElement.querySelector('a:first-child');
        aLink.classList.remove("active");
        i.classList.remove("show");
      });
    }

    // Toggle the clicked dropdown
    this.classList.toggle("active");
    item.classList.toggle("show");
  });
});

// SIDEBAR COLLAPSE AND TOGGLE
toggleSidebar.addEventListener('click', function () {
    sidebar.classList.toggle('hide');

    if (sidebar.classList.contains('hide')) {
      // Hide icons and texts when the sidebar is collapsed
      allSideDivider.forEach(item => {
        item.textContent = '-';  // Show only "-" or any other collapsed indicator
      });

      // Collapse the dropdowns and remove the 'show' class on <ul>
      allDropdown.forEach(item => {
        const a = item.parentElement.querySelector("a:first-child");

        // Only remove the 'show' class from the <ul>, but keep the 'active' class on the <a>
        a.classList.add('active');  // Make sure the <a> remains active
        item.classList.remove('show');  // Hide the dropdown menu (ul)
      });
    } else {
      // Show the full sidebar with texts and icons when expanded
      allSideDivider.forEach(item => {
        item.textContent = item.dataset.text;  // Restore the original text from data-text attribute
      });

      // Make sure the active state is intact when sidebar is expanded
      allDropdown.forEach(item => {
        const a = item.parentElement.querySelector("a:first-child");
        a.classList.add('active');  // Ensure <a> stays active when expanded
      });
    }
  });


// Handle mouseleave to collapse
sidebar.addEventListener('mouseleave', function () {
  if (this.classList.contains('hide')) {
    allDropdown.forEach(item => {
      const a = item.parentElement.querySelector('a:first-child');
      a.classList.remove("active");
      item.classList.remove("show");
    });
    allSideDivider.forEach(item => {
      item.textContent = '-';
    });
  }
});

// Handle mouseenter to expand
sidebar.addEventListener('mouseenter', function () {
    if (this.classList.contains('hide')) {
      // Expanding the sidebar
      sidebar.classList.remove('hide');  // Remove the 'hide' class to expand the sidebar

      allSideDivider.forEach(item => {
        item.textContent = item.dataset.text;  // Show the full text in the sidebar when expanded
      });

      // Keep the dropdowns in expanded state and retain active state
      allDropdown.forEach(item => {
        const a = item.parentElement.querySelector('a:first-child');
        // Keep the <a> tag active when sidebar expands
        a.classList.add("active");
        item.classList.add("show");  // Show the dropdown menu
      });
    }
  });

  // Handle mouseleave to collapse
  sidebar.addEventListener('mouseleave', function () {
    if (!this.classList.contains('hide')) {
      // Collapse the sidebar
      sidebar.classList.add('hide');  // Add the 'hide' class to collapse the sidebar

      // Collapse the dropdowns and keep the active state for <a> only
      allDropdown.forEach(item => {
        const a = item.parentElement.querySelector('a:first-child');
        a.classList.add("active");  // Ensure <a> remains active
        item.classList.remove("show");  // Hide the dropdown menu
      });

      // Hide texts and show just the icon or short indicator (e.g., "-")
      allSideDivider.forEach(item => {
        item.textContent = '-';  // Show only "-" or any other collapsed indicator
      });
    }
  });


// Ensure the sidebar is properly displayed when loaded
if (sidebar.classList.contains('hide')) {
  allSideDivider.forEach(item => {
    item.textContent = '-';  // Initially collapsed state
  });

  allDropdown.forEach(item => {
    const a = item.parentElement.querySelector("a:first-child");
    a.classList.remove('active');
    item.classList.remove('show');
  });
} else {
  allSideDivider.forEach(item => {
    item.textContent = item.dataset.text;
  });
}


// PROFILE DROPDOWN
const profile = document.querySelector("nav .profile");
const imgProfile = profile.querySelector("img");
const dropdownProfile = profile.querySelector(".profile-link");

imgProfile.addEventListener("click", function () {
  dropdownProfile.classList.toggle("show");
});

// MENU
const allMenu = document.querySelectorAll("main .content-data .head .menu");
allMenu.forEach(item=> {
  const icon = item.querySelector("i");
  const menuLink = item.querySelector(".menu-link");

  icon.addEventListener("click", function () {
    menuLink.classList.toggle("show");
  });
});

window.addEventListener("click", function (e) {
  if (e.target !== imgProfile) {
    if (e.target !== dropdownProfile) {
      if (dropdownProfile.classList.contains("show")) {
        dropdownProfile.classList.remove("show");
      }
    }
  }

  allMenu.forEach(item=> {
    const icon = item.querySelector("i");
    const menuLink = item.querySelector(".menu-link");

    if (e.target !== icon) {
      if (e.target !== menuLink) {
        if (menuLink.classList.contains("show")) {
          menuLink.classList.remove("show");
        }
      }
    }
  });
});

// PROGRESSBAR
const allProgress = document.querySelectorAll("main .card .progress");

allProgress.forEach(item => {
  const value = item.dataset.value; // Get the data-value
  item.style.setProperty("--value", `${value}%`); // Append '%' to the value
});



// APEXCHART
var options = {
  series: [44, 55, 13, 43, 22],
  chart: {
    width: 380,
    type: "pie",
  },
  labels: ["Team A", "Team B", "Team C", "Team D", "Team E"],
  responsive: [
    {
      breakpoint: 480,
      options: {
        chart: {
          width: 200,
        },
        legend: {
          position: "bottom",
        },
      },
    },
  ],
};

var chart = new ApexCharts(document.querySelector("#chart"), options);
chart.render();
