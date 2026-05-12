{{-- Modal Pilih Tanggal --}}
<div id="modal-tanggal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeDateModal()"></div>
    
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-[24px] w-full max-w-md shadow-2xl overflow-hidden">
            
            {{-- Header --}}
            <div class="bg-gradient-to-r from-[#7A8C5C] to-[#5C6B44] px-6 py-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-['Playfair_Display'] text-white text-xl font-bold">Pilih Tanggal</h3>
                        <p class="text-white/80 text-xs mt-1">Pilih tanggal pengambilan/pengiriman</p>
                    </div>
                    <button onclick="closeDateModal()" 
                        class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center hover:bg-white/30 transition text-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Content --}}
            <div class="px-6 py-5 max-h-[60vh] overflow-y-auto">
                <div id="available-dates-list" class="space-y-2">
                    {{-- Loading state --}}
                    <div id="dates-loading" class="text-center py-8">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-[#7A8C5C]"></div>
                        <p class="text-sm text-gray-500 mt-3">Memuat tanggal tersedia...</p>
                    </div>

                    {{-- Empty state --}}
                    <div id="dates-empty" class="hidden text-center py-8">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-sm text-gray-500">Belum ada tanggal tersedia</p>
                    </div>

                    {{-- Dates will be loaded here via JavaScript --}}
                </div>
            </div>

        </div>
    </div>
</div>

<script>
let availableDates = [];

// Open modal
document.getElementById('open-date-modal')?.addEventListener('click', function() {
    document.getElementById('modal-tanggal').classList.remove('hidden');
    loadAvailableDates();
});

// Close modal
function closeDateModal() {
    document.getElementById('modal-tanggal').classList.add('hidden');
}

// Load available dates from API
async function loadAvailableDates() {
    const loadingEl = document.getElementById('dates-loading');
    const emptyEl = document.getElementById('dates-empty');
    const listEl = document.getElementById('available-dates-list');

    try {
        loadingEl.classList.remove('hidden');
        emptyEl.classList.add('hidden');

        const response = await fetch('/api/tanggal-tersedia');
        const data = await response.json();

        availableDates = data.data || [];

        loadingEl.classList.add('hidden');

        if (availableDates.length === 0) {
            emptyEl.classList.remove('hidden');
            return;
        }

        // Render dates
        const datesHTML = availableDates.map(date => {
            const isAvailable = date.is_available;
            const statusClass = isAvailable ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200';
            const statusText = date.status;
            const statusColor = isAvailable ? 'text-green-600' : 'text-red-500';
            const cursorClass = isAvailable ? 'cursor-pointer hover:bg-green-100' : 'cursor-not-allowed opacity-60';

            return `
                <div onclick="${isAvailable ? `selectDate('${date.tanggal}', '${date.tanggal_display}')` : ''}" 
                    class="border-2 ${statusClass} rounded-xl p-4 transition ${cursorClass}">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="font-['Playfair_Display'] font-bold text-[#3D2B1F] text-base">
                                ${date.tanggal_display}
                            </p>
                            <p class="text-xs text-[#6B4C3B] mt-1">
                                ${date.keterangan || 'Slot reguler'}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-semibold ${statusColor}">${statusText}</p>
                            <p class="text-xs text-gray-500 mt-1">
                                Sisa: ${date.sisa}/${date.kuota}
                            </p>
                        </div>
                    </div>
                </div>
            `;
        }).join('');

        // Remove loading and empty, add dates
        listEl.innerHTML = datesHTML;

    } catch (error) {
        console.error('Error loading dates:', error);
        loadingEl.classList.add('hidden');
        listEl.innerHTML = `
            <div class="text-center py-8">
                <p class="text-sm text-red-500">Gagal memuat tanggal. Silakan coba lagi.</p>
            </div>
        `;
    }
}

// Select date
function selectDate(tanggal, tanggalFormatted) {
    // Update hidden input
    document.getElementById('actual-periode-input').value = tanggal;
    
    // Update label
    document.getElementById('selected-date-label').textContent = tanggalFormatted;
    document.getElementById('selected-date-label').classList.remove('text-[#6B4C3B]');
    document.getElementById('selected-date-label').classList.add('text-[#3D2B1F]', 'font-bold');
    
    // Close modal
    closeDateModal();
}

// Validate form before submit
document.addEventListener('DOMContentLoaded', function() {
    const checkoutForm = document.querySelector('form[action*="checkout"]');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            const actualPeriode = document.getElementById('actual-periode-input').value;
            
            if (!actualPeriode) {
                e.preventDefault();
                alert('Silakan pilih tanggal pengambilan/pengiriman terlebih dahulu.');
                return false;
            }
        });
    }
});
</script>
