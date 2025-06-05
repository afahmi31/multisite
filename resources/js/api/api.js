import axios from "axios";
const NONCE = wpApiSettings.nonce ?? "";

export async function apiRequest({ url, method = "GET", data = {}, headers = {} }) {
  try {
    const res = await axios({
      url,
      method,
      data,
      headers: {
        "X-WP-Nonce": NONCE,
        "Content-Type": "application/json",
        ...headers,
      },
      withCredentials: true,
    });
    // Standardize response
    const { message = "", data: d = "", errors = "", error = "" } = res.data;
    return { message, data: d, errors, error };
  } catch (err) {
    const { response } = err;
    const { message = "", data: d = "", errors = "", error = "" } = response?.data ?? {};
    return { message, data: d, errors, error: error || err.message };
  }
}

// Shortcuts
export function apiGet(url) { return apiRequest({ url, method: "GET" }); }
export function apiPost(url, data) { return apiRequest({ url, method: "POST", data }); }
export function apiPut(url, data) { return apiRequest({ url, method: "PUT", data }); }
export function apiDelete(url) { return apiRequest({ url, method: "DELETE" }); }