import Echo from "laravel-echo";
import Pusher from "pusher-js";

// Ensure Pusher is globally available
window.Pusher = Pusher;

// Initialize Echo with Pusher
const echo = new Echo({
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY, // Use environment variable (Vite syntax)
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER, // Use environment variable (Vite syntax)
    forceTLS: true, // Ensure TLS for secure connection
});

export default echo;