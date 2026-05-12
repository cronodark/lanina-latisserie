import "./bootstrap";
import Swal from 'sweetalert2'
window.Swal = Swal

export default {
    content: ["./resources/**/*.blade.php", "./resources/**/*.js"],
    theme: {
        extend: {
            fontFamily: {
                glacial: ["Glacial", "sans-serif"],
            },
        },
    },
    plugins: [],
};

// Catatan: logic modal checkout (alamat/pembayaran/pengiriman) dipindah ke
// inline <script> di resources/views/pages/checkout/index.blade.php agar tetap
// berfungsi walaupun bundle Vite telat/gagal load di production.
