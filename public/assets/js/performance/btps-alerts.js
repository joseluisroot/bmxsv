window.BTPSAlerts = (() => {
    const base = {
        background: '#0f172a',
        color: '#f8fafc',
        confirmButtonColor: '#0891b2',
        cancelButtonColor: '#334155',
        buttonsStyling: true,
        reverseButtons: true,
        customClass: {
            popup: 'btps-swal-popup',
            title: 'btps-swal-title',
            htmlContainer: 'btps-swal-text',
            confirmButton: 'btps-swal-confirm',
            cancelButton: 'btps-swal-cancel'
        }
    };

    const toast = Swal.mixin({
        ...base,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2600,
        timerProgressBar: true,
        width: 360,
        didOpen: toastElement => {
            toastElement.addEventListener('mouseenter', Swal.stopTimer);
            toastElement.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });

    function success(title, text = '') {
        return toast.fire({icon: 'success', title, text});
    }

    function error(title, text = '') {
        return Swal.fire({...base, icon: 'error', title, text, confirmButtonText: 'Entendido'});
    }

    function info(title, html = '') {
        return Swal.fire({...base, icon: 'info', title, html, confirmButtonText: 'Cerrar', width: 640});
    }

    function confirm({title, text = '', confirmText = 'Confirmar', cancelText = 'Cancelar', icon = 'question', danger = false} = {}) {
        return Swal.fire({
            ...base,
            icon,
            title,
            text,
            showCancelButton: true,
            confirmButtonText: confirmText,
            cancelButtonText: cancelText,
            confirmButtonColor: danger ? '#d97706' : base.confirmButtonColor,
            focusCancel: true
        });
    }

    function loading(title = 'Procesando...', text = 'Espera un momento.') {
        return Swal.fire({
            ...base,
            title,
            text,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => Swal.showLoading()
        });
    }

    function close() {
        Swal.close();
    }

    return {success, error, info, confirm, loading, close};
})();
