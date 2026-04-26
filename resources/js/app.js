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

// Jalankan hanya jika elemen checkout ada di halaman
if (document.getElementById("payment-modal")) {
    // ===== MODAL ALAMAT =====
    const addressModal = document.getElementById("address-modal");
    const addressModalBox = document.getElementById("address-modal-box");
    const closeAddressBtn = document.getElementById("close-address-modal");
    const nameEl = document.getElementById("address-name");
    const detailEl = document.getElementById("address-detail");

    document
        .getElementById("open-address-modal")
        .addEventListener("click", () => {
            addressModal.classList.remove("opacity-0", "pointer-events-none");
            addressModalBox.classList.remove("scale-95");
            addressModalBox.classList.add("scale-100");
        });

    closeAddressBtn.addEventListener("click", () => {
        addressModal.classList.add("opacity-0", "pointer-events-none");
        addressModalBox.classList.remove("scale-100");
        addressModalBox.classList.add("scale-95");
    });

    document.querySelectorAll(".address-item").forEach((item) => {
        item.addEventListener("click", () => {
            const name = item.dataset.name;
            const phone = item.dataset.phone;
            const address = item.dataset.address;

            nameEl.innerHTML = `${name} <span class="font-normal text-[#6B4C3B] ml-2">(${phone})</span>`;
            detailEl.textContent = address;

            addressModal.classList.add("opacity-0", "pointer-events-none");
            addressModalBox.classList.remove("scale-100");
            addressModalBox.classList.add("scale-95");
        });
    });

    // ===== MODAL PEMBAYARAN =====
    const paymentModal = document.getElementById("payment-modal");
    const paymentModalBox = document.getElementById("payment-modal-box");

    function openPaymentModal() {
        paymentModal.classList.remove("opacity-0", "pointer-events-none");
        paymentModal.classList.add("opacity-100");
        paymentModalBox.classList.remove("translate-y-4");
    }

    function closePaymentModal() {
        paymentModal.classList.add("opacity-0", "pointer-events-none");
        paymentModal.classList.remove("opacity-100");
        paymentModalBox.classList.add("translate-y-4");
    }

    document
        .getElementById("open-payment-modal")
        .addEventListener("click", openPaymentModal);
    document
        .getElementById("close-payment-modal")
        .addEventListener("click", closePaymentModal);

    paymentModal.addEventListener("click", (e) => {
        if (e.target === paymentModal) closePaymentModal();
    });

    document.querySelectorAll(".bank-option").forEach((btn) => {
        btn.addEventListener("click", () => {
            const label = btn.querySelector("span:last-child").textContent;
            const bankLabel = document.getElementById("selected-bank-label");
            if (bankLabel) bankLabel.textContent = label;
            closePaymentModal();
        });
    });

    // ===== MODAL PENGIRIMAN =====
    const shippingModal = document.getElementById("shipping-modal");
    const shippingModalBox = document.getElementById("shipping-modal-box");

    function openShippingModal() {
        shippingModal.classList.remove("opacity-0", "pointer-events-none");
        shippingModal.classList.add("opacity-100");
        shippingModalBox.classList.remove("translate-y-4");
    }

    function closeShippingModal() {
        shippingModal.classList.add("opacity-0", "pointer-events-none");
        shippingModal.classList.remove("opacity-100");
        shippingModalBox.classList.add("translate-y-4");
    }

    document
        .getElementById("open-shipping-modal")
        .addEventListener("click", openShippingModal);
    document
        .getElementById("close-shipping-modal")
        .addEventListener("click", closeShippingModal);

    shippingModal.addEventListener("click", (e) => {
        if (e.target === shippingModal) closeShippingModal();
    });

    document.querySelectorAll(".shipping-option").forEach((btn) => {
        btn.addEventListener("click", () => {
            document.querySelectorAll(".shipping-option").forEach((b) => {
                const radio = b.querySelector(".shipping-radio");
                radio.classList.remove("border-[#7A8C5C]", "bg-[#7A8C5C]");
                radio.classList.add("border-[#D8CFC4]", "bg-white");
                radio.innerHTML = "";
            });

            const activeRadio = btn.querySelector(".shipping-radio");
            activeRadio.classList.remove("border-[#D8CFC4]", "bg-white");
            activeRadio.classList.add("border-[#7A8C5C]", "bg-[#7A8C5C]");
            activeRadio.innerHTML = `<svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>`;

            const label = btn.querySelector("span").textContent;
            const shippingLabel = document.getElementById(
                "selected-shipping-label",
            );
            if (shippingLabel) shippingLabel.textContent = label;

            setTimeout(closeShippingModal, 200);
        });
    });
}
