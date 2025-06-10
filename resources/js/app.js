import.meta.glob([
  '../images/**',
  '../fonts/**',
]);

import Swal from 'sweetalert2'

import './auth/register.js';

import { fetchWebsite } from './list-website.js';

document.addEventListener("DOMContentLoaded", function () {
  
  const loginForm = document.getElementById('loginForm');
  if (loginForm) {
    loginForm.addEventListener('submit', async function (e) {
      e.preventDefault();

      const form = e.target;
      const submitButton = form.querySelector('button[type="submit"]');
      const originalButtonText = submitButton.innerHTML;

      submitButton.disabled = true;
      submitButton.innerHTML = 'Loading...';

      const data = {
        email: form.email.value.trim(),
        password: form.password.value.trim()
      };

      try {
        const response = await fetch('/wp-json/custom/v1/login', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-WP-Nonce': wpApiSettings.nonce
          },
          body: JSON.stringify(data)
        });

        const result = await response.json();

        if (response.ok && result.success) {
            Swal.fire({
                icon: "success",
                title: "success",
                text: 'Login success',
            });

          form.reset();

          setTimeout(() => {
            window.location.href = '/dashboard';
          }, 2000);

        } else {
           Swal.fire({
                icon: "success",
                title: "success",
                text: result.data.message || 'Login gagal. Periksa kembali email atau password.',
          });
        }
      } catch (error) {
         Swal.fire({
                icon: "error",
                title: "Error",
                text: error,
        });
        console.error('Error:', error);
      } finally {
        submitButton.disabled = false;
        submitButton.innerHTML = originalButtonText;
      }
    });
  }

  if (document.body.classList.contains('dashboard')) {
    const modal = document.getElementById("project-modal");
        const modalOverlay = document.getElementById("modal-overlay");
        const closeModalBtn = document.getElementById("close-modal");
        const startProjectBtns =
          document.querySelectorAll("#start-new-project");
          const startNewProject = document.querySelector('.start-new-project');
          const nextBtn = document.querySelector("form #next");

          const siteInput = document.getElementById('site-name');
          const messageEl = document.createElement('small');
          messageEl.classList.add('text-sm', 'mt-1', 'block');
          siteInput.parentNode.appendChild(messageEl);

          let timeout = null;

          siteInput.addEventListener('input', () => {
            clearTimeout(timeout);
            const sitename = siteInput.value.trim();

            if (sitename.length < 3) {
              messageEl.textContent = '';
              return;
            }else{
                messageEl.textContent = 'Checking availability...';
                messageEl.classList.remove('text-green-500', 'text-red-500');
              timeout = setTimeout(() => {
                fetch(wpApiSettings.ajax_root + '?action=check_site_availability&site_name=' + sitename)
                  .then(res => res.json())
                  .then(data => {
                    if (data.available) {
                      messageEl.textContent = '✅ Site name is available.';
                      messageEl.classList.remove('text-red-500');
                      messageEl.classList.add('text-green-500');
                    } else {
                      messageEl.textContent = '❌ Site name is already taken.';
                      messageEl.classList.remove('text-green-500');
                      messageEl.classList.add('text-red-500');
                    }
                  });
              }, 1000);
            }
          });


        let formData = {
          orgName: "",
          logo: "",
          email: "",
          password: "",
          description: "",
          sitename: "",
        };

        function openModal() {
          loadAvailableThemes()
          modal.classList.remove("hidden");
          document.body.classList.add("overflow-hidden");
        }

        function closeModal() {
          modal.classList.add("hidden");
          document.body.classList.remove("overflow-hidden");
        }

        function collectFormData() {
          formData.orgName =
            document.getElementById("organization-name").value || "";
          formData.description =
            document.getElementById("project-description").value || "";
          formData.sitename =
            document.querySelector("input[placeholder='Enter your sitename']")
              .value || "";
        }

        function populateForm() {
          document.getElementById("project-name").value = formData.orgName;
          document.querySelector("input[placeholder='Value']").value =
            formData.logo;
          document.querySelector("input[type='email']").value = formData.email;
          document.querySelector("input[type='password']").value =
            formData.password;
          document.getElementById("project-description").value =
            formData.description;
          document.querySelector(
            "input[placeholder='Enter your sitename']"
          ).value = formData.sitename;
        }

        function showResultModal() {
          collectFormData();
          closeModal();

          const resultModal = document.createElement("div");
          resultModal.id = "result-modal";
          resultModal.className =
            "fixed inset-0 flex items-center justify-center z-50";

          resultModal.innerHTML = `
          <div class="absolute inset-0 bg-black opacity-50" id="result-overlay"></div>
          <div class="bg-white rounded-lg max-w-[763px] w-full relative z-10">
            <div class="flex justify-between items-center border-b-1 border-[#EFEFEF] p-6">
              <h2 class="text-primary">Your Website Information</h2>
              <button id="close-result-modal" class="p-1">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M18 6L6 18M6 6L18 18" stroke="#1E1E1E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </button>
            </div>
            <div class="p-6 flex flex-col gap-6">
              <div class="p-2 flex flex-col gap-4">
                <div class="w-full h-[200px] rounded-[8px] bg-[#EDEDED]">
                </div>
                <span>Ecommerce</span>
              </div>
              <div class="flex justify-between items-center gap-1">
                <p class="font-medium">Organization Name:</p>
                <p class="text-gray-700">${
                  formData.orgName || "Not provided"
                }</p>
              </div>
              <div class="flex justify-between items-center gap-1">
                <p class="font-medium">Description:</p>
                <p class="text-gray-700">${
                  formData.description || "Not provided"
                }</p>
              </div>
              <div class="flex justify-between items-center gap-1">
                <p class="font-medium">Sitename:</p>
                <p class="text-gray-700">${
                  formData.sitename || "Not provided"
                }</p>
              </div>
              <div class="flex justify-between items-center mt-4 gap-3">
                <button id="Edit-project" class="bg-[#E3E3E3] text-black py-1 px-2 rounded-lg mt-2 border border-black">
                  Edit Info
                </button>
                <button id="confirm-project" class="bg-black text-white py-1 px-2 rounded-lg">
                  Confirm Project
                </button>
              </div>
            </div>
          </div>
        `;

          document.body.appendChild(resultModal);

          document
            .getElementById("close-result-modal")
            .addEventListener("click", function () {
              document.body.removeChild(resultModal);
              document.body.classList.remove("overflow-hidden");
            });

          document
            .getElementById("result-overlay")
            .addEventListener("click", function () {
              document.body.removeChild(resultModal);
              document.body.classList.remove("overflow-hidden");
            });

          document
            .getElementById("Edit-project")
            .addEventListener("click", function () {
              document.body.removeChild(resultModal);

              openModal();
              populateForm();
            });

          document
            .getElementById("confirm-project")
            .addEventListener("click", async  function (e) {
            const form = document.querySelector(".website-form");
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());
            const response = await fetch(wpApiSettings.root + 'custom/v1/create-site', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': wpApiSettings.nonce
              },
              body: JSON.stringify(data)
            });

            if (response.ok) {
              const result = await response.json();
              document.body.removeChild(resultModal);
              document.body.classList.remove("overflow-hidden");
          
              const successModal = document.createElement("div");
              successModal.id = "success-modal";
              successModal.className = "fixed inset-0 flex items-center justify-center z-50";
          
              successModal.innerHTML = `
                  <div class="absolute inset-0 bg-black opacity-50"></div>
                  <div class="bg-white rounded-lg max-w-[763px] w-full relative z-10">
                    <div class="flex justify-between items-center border-b-1 border-[#EFEFEF] p-6">
                      <h2 class="text-primary">Your Website Successfully Built</h2>
                      <button id="close-success-modal" class="p-1">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                          <path d="M18 6L6 18M6 6L18 18" stroke="#1E1E1E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                      </button>
                    </div>
                    <div class="flex flex-col gap-6 items-center p-6">
                      <div class="w-full flex justify-between gap-2">
                        <p class="text-gray-700">Link to Your Website</p>
                        <div class="flex justify-between">
                          <span></span>
                          <a href="http://${result.data.site_url}" target="_blank" class="text-black underline">
                            ${result.data.site_url}
                          </a>
                        </div>
                      </div>
                    </div>
                    <div class="flex justify-end w-full p-6 border-t-1 border-[#EFEFEF]">
                      <button id="back-to-dashboard" class="bg-black text-white py-2 px-4 rounded-lg">
                        Back To Dashboard
                      </button>
                    </div>
                  </div>
                `;

          
              document.body.appendChild(successModal);
          
              document.getElementById("close-success-modal").addEventListener("click", function () {
                document.body.removeChild(successModal);
              });
          
              document.getElementById("back-to-dashboard").addEventListener("click", function () {
                document.body.removeChild(successModal);
              });

              await  fetchWebsite();

          
            } else {
              let errorMessage = "Unknown error";
              try {
                const err = await response.json();
                errorMessage = err?.data?.message || err.message || errorMessage;
              } catch (e) {
                console.error("Failed to parse error response", e);
              }
            
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: errorMessage,
              });
            }      


            });
        }

        startProjectBtns.forEach((btn) => {
          btn.addEventListener("click", openModal);
        });

        startNewProject.addEventListener('click', openModal);

        closeModalBtn.addEventListener("click", closeModal);
        modalOverlay.addEventListener("click", closeModal);

        modal
          .querySelector(".bg-white")
          .addEventListener("click", function (e) {
            e.stopPropagation();
          });

        nextBtn.addEventListener("click", function (e) {
          e.preventDefault();
          showResultModal();
        });

        document.addEventListener("keydown", function (e) {
          if (e.key === "Escape") {
            if (!modal.classList.contains("hidden")) {
              closeModal();
            }
            const resultModal = document.getElementById("result-modal");
            if (resultModal) {
              document.body.removeChild(resultModal);
              document.body.classList.remove("overflow-hidden");
            }
          }
        });
  }

  const logoutButton = document.querySelector('.logout-button');
  if (logoutButton) {
    logoutButton.addEventListener('click', async function (e) {
      e.preventDefault();
      try {
        const response = await fetch('/wp-json/custom/v1/logout', {
          method: 'POST',
          credentials: 'include'
        });

        const result = await response.json();

        if (response.ok) {
          window.location.href = '/';
        } else {
          Swal.fire({
                icon: "error",
                title: "Error",
                text: result.message || 'Logout Error.',
         });
        }
      } catch (error) {
        Swal.fire({
                icon: "error",
                title: "Error",
                text: 'Logout failed.',
         });
        console.error('Error:', error);
      }
    });
  }

  async function loadAvailableThemes(){
    const select = document.getElementById('site-template');
    select.innerHTML = '';
    try {
      const response = await fetch('/wp-json/mcd/v1/available-themes',{
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
            'X-WP-Nonce': wpApiSettings.nonce
          }
        });
      if (!response.ok) throw new Error('Network response was not ok');
      const themes = await response.json();
      themes.forEach(theme => {
        const option = document.createElement('option');
        option.value = theme.domain;
        option.textContent = theme.name;
        select.appendChild(option);
      });
    } catch (error) {
      console.error('Error fetching themes:', error);
    }
  }

});