
import { apiPost } from '../api/api'; // <--- gunakan helper global
import { clearFormErrors, injectFormErrors } from '../components/FormHelper'; // <--- gunakan helper global
import { showAlert } from '../components/Alert';

document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('registerForm');

  console.log('Register form:', form);
  if (!form) return;

  form.addEventListener('submit', async function (e) {
    e.preventDefault();
    clearFormErrors(form);

    const submitButton = form.querySelector('button[type="submit"]');
    const originalButtonText = submitButton.innerHTML;
    submitButton.disabled = true;
    submitButton.innerHTML = 'Loading...';

    // Serialize all fields (universal, tidak perlu daftarkan field satu-satu)
    const data = Object.fromEntries(new FormData(form).entries());

    try {
      // Gunakan apiPost helper global
      const res = await apiPost('/wp-json/custom/v1/register', data);

      console.log('API response:', res);

      if (res.errors && Object.keys(res.errors).length > 0) {
        injectFormErrors(form, res.errors);
        // Autofocus ke field error pertama
        const firstErrorField = Object.keys(res.errors)[0];
        const firstInput = form.querySelector(`[name="${firstErrorField}"]`);
        if (firstInput) firstInput.focus();
        return;
      }

      // Error umum
      if (res.error) {
        showAlert({ message: res.error, type: 'error' });
        return;
      }

     // Success
      showAlert({ message: res.message || 'Registration success!', type: 'success' });
      form.reset();
      setTimeout(() => {
        window.location.href = '/login';
      }, 2000);


    } catch (err) {
      // Handle network or unexpected error
       showAlert({ message: err.message || 'Registrasi gagal.', type: 'error' });
    } finally {
      submitButton.disabled = false;
      submitButton.innerHTML = originalButtonText;
    }
  });
});