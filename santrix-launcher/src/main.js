
const subdomainInput = document.querySelector("#subdomain");
const loginForm = document.querySelector("#login-form");
const errorMsg = document.querySelector("#error-msg");
const btnSubmit = document.querySelector("button[type='submit']");

// Base Domain Config
const BASE_DOMAIN = "santrix.my.id"; 

async function validateAndRedirect() {
  const subdomain = subdomainInput.value.trim().toLowerCase();
  
  if (!subdomain) {
    errorMsg.textContent = "ID Pesantren tidak boleh kosong";
    return;
  }
  
  if (!/^[a-z0-9-]+$/.test(subdomain)) {
     errorMsg.textContent = "ID hanya boleh huruf, angka, dan strip (-)";
     return;
  }

  // Disable UI
  btnSubmit.textContent = "Menghubungkan...";
  btnSubmit.disabled = true;
  subdomainInput.disabled = true;

  // Construct URL
  const targetUrl = `https://${subdomain}.${BASE_DOMAIN}/login`;

  try {
    // Check if URL is reachable (Optional, fails if CORS, but let's try basic)
    // Actually, just redirect. 
    
    // Save to LocalStorage
    localStorage.setItem("santrix_last_subdomain", subdomain);

    // Redirect
    window.location.href = targetUrl;
    
  } catch (error) {
    btnSubmit.textContent = "Masuk Aplikasi";
    btnSubmit.disabled = false;
    subdomainInput.disabled = false;
    errorMsg.textContent = "Gagal menghubungkan. Periksa internet.";
  }
}

// Check Auto Login
const last = localStorage.getItem("santrix_last_subdomain");
if (last) {
   // Optional: Auto redirect?
   // For now, just pre-fill
   subdomainInput.value = last;
   document.querySelector("#last-domain").textContent = last;
   document.querySelector("#saved-login").style.display = "block";
   
   // Auto submit logic (commented out for safety on first try)
   // validateAndRedirect();
}

loginForm.addEventListener("submit", (e) => {
  e.preventDefault();
  validateAndRedirect();
});
