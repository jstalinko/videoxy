(function () {
    const TARGET_URL =
        'https://printerkidneyethel.com/aczdrhd2u?key=69818c9464aaa211b4ed47fb49021b13';
    const OPEN_ONCE_PER_SESSION = false;
    const DELAY_MS = 0;

    let opened = false;

    function openAdViaAnchor() {
        if (opened) return;
        opened = true;

        if (OPEN_ONCE_PER_SESSION) {
            try {
                sessionStorage.setItem('ad_opened', '1');
            } catch (e) {}
        }

        // bikin anchor
        const a = document.createElement('a');
        a.href = TARGET_URL;
        a.target = '_blank';
        a.rel = 'noopener noreferrer';
        a.style.display = 'none';

        document.body.appendChild(a);

        // trigger click (HARUS dalam konteks user interaction)
        a.click();

        // cleanup
        document.body.removeChild(a);
    }

    function shouldOpen() {
        if (opened) return false;
        if (OPEN_ONCE_PER_SESSION) {
            try {
                if (sessionStorage.getItem('ad_opened') === '1') return false;
            } catch (e) {}
        }
        return true;
    }

    function onFirstUserClick(e) {
        if (!shouldOpen()) return;

        // kalau mau exclude klik tertentu, bisa aktifin ini:
        // if (e.target.closest("a, button, input, textarea")) return;

        if (DELAY_MS > 0) {
            setTimeout(openAdViaAnchor, DELAY_MS);
        } else {
            openAdViaAnchor();
        }

        document.removeEventListener('click', onFirstUserClick, true);
    }

    // capture phase biar kebaca duluan
    document.addEventListener('click', onFirstUserClick, true);
})();
