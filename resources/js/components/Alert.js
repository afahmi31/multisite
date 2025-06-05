import Swal from 'sweetalert2';
export function showAlert({ message, type = 'info', title = '', timeout = 4000 }) {
   Swal.fire({
      icon: type,
      title: title || (type === 'success' ? 'Success' : type === 'error' ? 'Error' : 'Info'),
      text: message,
      timer: timeout,
      timerProgressBar: true,
      showConfirmButton: true
    });
}