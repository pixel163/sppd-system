// ============================================================
// 1. INPUT VALIDATION HELPERS
// ============================================================

// Mencegah pengetikan karakter non-angka seperti e, +, -, ., dan ,
function cekkunci(e) {
    if (['e', 'E', '+', '-', '.', ','].includes(e.key)) {
        e.preventDefault();
    }
}

// Membersihkan input jika di-paste & membatasi nilai max 14 dan min 1
function validasiWaktu(input) {
    input.value = input.value.replace(/[^0-9]/g, '');

    if (input.value !== '') {
        let val = parseInt(input.value, 10);
        
        if (val > 14) {
            input.value = 14;
        }
        
        if (val < 1) {
            input.value = '';
        }
    }
}

// Ekspos ke window agar tetap bisa dipanggil dari inline HTML jika diperlukan
window.cekkunci = cekkunci;
window.validasiWaktu = validasiWaktu;

// ============================================================
// 2. CUSTOM DROPDOWN UI FUNCTIONS
// ============================================================

function toggleDropdown(buttonEl) {
    const container = buttonEl.closest('.relative');
    if (!container) return;

    const menu = container.querySelector('.dropdown-menu');
    const arrow = buttonEl.querySelector('.dropdown-arrow');

    // Tutup dropdown lain yang sedang terbuka
    document.querySelectorAll('.dropdown-menu').forEach(m => {
        if (m !== menu) m.classList.add('hidden');
    });
    document.querySelectorAll('.dropdown-arrow').forEach(a => {
        if (a !== arrow) a.classList.remove('rotate-180');
    });

    // Toggle dropdown yang diklik
    if (menu) menu.classList.toggle('hidden');
    if (arrow) arrow.classList.toggle('rotate-180');
}

function updateDropdownText(checkboxEl, placeholderDefault) {
    const container = checkboxEl.closest('.relative');
    if (!container) return;

    const textSpan = container.querySelector('.selected-text');
    const checkboxes = container.querySelectorAll('.dropdown-checkbox:checked');

    if (!textSpan) return;

    if (checkboxes.length === 0) {
        textSpan.innerText = placeholderDefault;
        textSpan.classList.add('text-slate-400');
        textSpan.classList.remove('text-slate-800');
    } else if (checkboxes.length === 1) {
        const labelText = checkboxes[0].nextElementSibling?.innerText.trim() || '';
        textSpan.innerText = labelText;
        textSpan.classList.remove('text-slate-400');
        textSpan.classList.add('text-slate-800');
    } else {
        textSpan.innerText = `${checkboxes.length} Pilihan Dipilih`;
        textSpan.classList.remove('text-slate-400');
        textSpan.classList.add('text-slate-800');
    }
}

window.toggleDropdown = toggleDropdown;
window.updateDropdownText = updateDropdownText;

// ============================================================
// 3. INITIALIZATION & EVENT LISTENERS
// ============================================================

document.addEventListener('DOMContentLoaded', function() {
    // A. Init Custom Checkbox Dropdown Text
    document.querySelectorAll('.dropdown-checkbox').forEach(cb => {
        const container = cb.closest('.relative');
        const defaultText = container?.querySelector('.selected-text')?.dataset.placeholder || 'Pilih Data';
        updateDropdownText(cb, defaultText);

        // Tambahkan event listener otomatis saat checkbox di-check
        cb.addEventListener('change', function() {
            updateDropdownText(this, defaultText);
        });
    });

    // B. Init TomSelect (Hanya dijalankan jika elemen #kota ada di halaman)
    const kotaSelect = document.getElementById('kota');
    if (kotaSelect && typeof TomSelect !== 'undefined') {
        new TomSelect("#kota", {
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            }
        });
    }

    // C. Event Listener Klik Luar Area (Auto Close Dropdown)
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.relative')) {
            document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.add('hidden'));
            document.querySelectorAll('.dropdown-arrow').forEach(a => a.classList.remove('rotate-180'));
        }
    });
});