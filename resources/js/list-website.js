document.addEventListener("DOMContentLoaded", async () => {
  if (document.body.classList.contains('dashboard')) {
    if ( wpApiSettings.user_id &&  wpApiSettings.user_id !== 0) {
      fetchWebsite();
    }
  }
});

export async function fetchWebsite() {
    const tableContainer = document.getElementById("website-table-container");
    const tableBody = document.getElementById("website-table-body");
    const emptyState = document.querySelector(".list-website");

    try {
      const res = await fetch(`/wp-json/custom/v1/user-sites`, {
        method: 'GET',
       headers: {
            'Content-Type': 'application/json',
            'X-WP-Nonce': wpApiSettings.nonce
          }
      });

      const json = await res.json();
      tableBody.innerHTML = '';
      if (json.success && json.data.length > 0) {
        emptyState.classList.add("hidden");
        tableContainer.classList.remove("hidden");

        json.data.forEach((site, index) => {
          const row = document.createElement("tr");
          row.className = "border-b text-sm";

          row.innerHTML = `
            <td class="py-2 px-4">${index + 1}</td>
            <td class="py-2 px-4">${site.site_name}</td>
            <td class="py-2 px-4">
              <a href="${site.site_url}" target="_blank" class="text-blue-600 underline">${site.site_url}</a>
            </td>
          `;

          tableBody.appendChild(row);
        });
      } else {
        
      }
    } catch (err) {
      console.error("fetchWebsite - err :", err);
    }
}