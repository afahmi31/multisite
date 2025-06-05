export function clearFormErrors(form) {
  form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
}

export function injectFormErrors(form, errors = {}) {
  Object.entries(errors).forEach(([field, msg]) => {
    const target = form.querySelector(`[data-error="${field}"]`);
    if (target) target.textContent = msg;
  });
}