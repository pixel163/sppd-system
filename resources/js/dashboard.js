/*
|--------------------------------------------------------------------------
| SIDEBAR
|--------------------------------------------------------------------------
*/

function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    if (!sidebar || !overlay) return;

    sidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');
}


/*
|--------------------------------------------------------------------------
| PROFILE DROPDOWN
|--------------------------------------------------------------------------
*/

document.addEventListener('DOMContentLoaded', () => {

    const profileToggle = document.getElementById('profileToggle');
    const profileMenu = document.getElementById('profileMenu');

    if (profileToggle && profileMenu) {

        profileToggle.addEventListener('click', () => {

            profileMenu.classList.toggle('hidden');

            const expanded =
                profileToggle.getAttribute('aria-expanded') === 'true';

            profileToggle.setAttribute(
                'aria-expanded',
                !expanded
            );
        });


        document.addEventListener('click', (event) => {

            if (
                !profileToggle.contains(event.target) &&
                !profileMenu.contains(event.target)
            ) {

                profileMenu.classList.add('hidden');

                profileToggle.setAttribute(
                    'aria-expanded',
                    'false'
                );
            }

        });

    }

});


/*
|--------------------------------------------------------------------------
| NOTIFICATION
|--------------------------------------------------------------------------
*/

function showNotification() {
    alert('Belum ada notifikasi baru.');
}


/*
|--------------------------------------------------------------------------
| DETAIL MODAL
|--------------------------------------------------------------------------
*/

function closeModal() {

    const modal = document.getElementById('detailModal');

    if (!modal) return;

    modal.classList.add('hidden');
    modal.classList.remove('flex');
}