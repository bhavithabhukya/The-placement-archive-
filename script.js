// Global state
let currentUser = null;
let experiences = [];

// Initialize app
document.addEventListener("DOMContentLoaded", async () => {
  await initApp();
  loadExperiences();
  updateAuthUI();
});

// Auth functions
async function login(email, password) {
  const response = await fetch("login.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ email, password }),
  });
  const result = await response.json();

  if (result.success) {
    currentUser = result.user;
    localStorage.setItem("user", JSON.stringify(result.user));
    window.location.href = "dashboard.html";
  }
}

async function logout() {
  await fetch("logout.php");
  currentUser = null;
  localStorage.removeItem("user");
  window.location.href = "index.html";
}

// Load experiences
async function loadExperiences(page = 1) {
  const response = await fetch(`api/experiences.php?page=${page}`);
  const data = await response.json();
  experiences = data.experiences;
  renderExperiences();
}

// Render experiences
function renderExperiences() {
  const container = document.getElementById("experiencesGrid");
  if (!container) return;

  container.innerHTML = experiences
    .map(
      (exp) => `
        <div class="experience-card">
            <h3>${exp.company_name}</h3>
            <span class="difficulty-badge ${exp.difficulty}">${exp.difficulty.toUpperCase()}</span>
            <p><strong>Role:</strong> ${exp.role}</p>
            <div class="stats">
                <span>❤️ ${exp.likes}</span>
                <span>👁️ ${exp.views}</span>
            </div>
        </div>
    `,
    )
    .join("");
}
