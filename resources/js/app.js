import 'bootstrap';
import 'bootstrap-icons/font/bootstrap-icons.css';
import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

function showPageFeedback() {
    const feedback = document.querySelectorAll('.admin-form-alert, .auth-error, .auth-success, .alert[role="alert"], .alert-success, .alert-danger, .alert-info, .alert-warning');
    let previousDialog = Promise.resolve();

    feedback.forEach(function (element) {
        const message = element.innerText.trim();
        if (!message) return;

        const isSuccess = element.classList.contains('admin-form-alert-success') || element.classList.contains('auth-success') || element.classList.contains('alert-success');
        const isInfo = element.classList.contains('alert-info') || (element.classList.contains('admin-form-alert') && !element.classList.contains('admin-form-alert-danger') && !element.classList.contains('admin-form-alert-success'));

        previousDialog = previousDialog.then(function () {
            return Swal.fire({
                icon: isSuccess ? 'success' : isInfo ? 'info' : 'error',
                title: isSuccess ? 'Berhasil' : isInfo ? 'Informasi' : 'Periksa kembali',
                text: message,
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#b42332',
            });
        });

        element.remove();
    });
}

function initializeSweetAlertConfirmations() {
    document.addEventListener('submit', function (event) {
        const form = event.target.closest('form');
        if (!form || form.dataset.swalConfirmed === 'true' || form.dataset.swalNoConfirm === 'true') return;

        const method = (form.getAttribute('method') || 'get').toUpperCase();
        const needsConfirmation = Boolean(form.dataset.swalConfirm) || !['GET', 'DIALOG'].includes(method);
        if (!needsConfirmation) return;

        event.preventDefault();

        const submitter = event.submitter;
        const actionLabel = (submitter?.innerText || submitter?.value || submitter?.title || '').trim().replace(/\s+/g, ' ').toLocaleLowerCase('id-ID');
        const isDelete = /hapus|menghapus/.test(actionLabel);
        const isLogout = /keluar|logout/.test(actionLabel);
        const isLogin = /masuk/.test(actionLabel);
        const isRegister = /daftar|buat akun/.test(actionLabel);
        const isReadStatus = /baca|dibaca|tandai/.test(actionLabel);
        const isSave = /simpan|perbarui|ubah|buat|tambah/.test(actionLabel);
        const title = form.dataset.swalTitle
            || (isDelete ? 'Hapus data ini?'
                : isLogout ? 'Keluar dari akun?'
                    : isLogin ? 'Masuk ke akun?'
                        : isRegister ? 'Kirim pendaftaran?'
                            : isReadStatus ? 'Perbarui status notifikasi?'
                                : isSave ? 'Simpan perubahan?'
                                : 'Lanjutkan tindakan?');
        const message = form.dataset.swalConfirm
            || (isDelete ? 'Data yang dipilih akan dihapus.'
                : isLogout ? 'Anda akan keluar dari akun Blodora.'
                    : isLogin ? 'Blodora akan memeriksa informasi masuk Anda.'
                        : isRegister ? 'Informasi akun akan dikirim untuk diproses.'
                            : isReadStatus ? 'Status notifikasi yang dipilih akan diperbarui.'
                                : isSave ? 'Informasi yang Anda masukkan akan disimpan.'
                                : 'Formulir ini akan dikirim untuk diproses.');
        Swal.fire({
            icon: form.dataset.swalIcon || 'warning',
            title,
            text: message,
            showCancelButton: true,
            confirmButtonText: form.dataset.swalConfirmButton || 'Ya, lanjutkan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#b42332',
            cancelButtonColor: '#64748b',
            reverseButtons: true,
            focusCancel: true,
        }).then(function (result) {
            if (!result.isConfirmed) return;

            form.dataset.swalConfirmed = 'true';
            if (form.requestSubmit) {
                form.requestSubmit(submitter || undefined);
            } else {
                form.submit();
            }
        });
    }, true);
}

function initializeSweetAlertValidation() {
    let validationDialogOpen = false;

    function getLocalizedValidationMessage(field) {
        const label = field.labels?.[0]?.innerText.trim().replace(/\s+/g, ' ') || field.getAttribute('aria-label') || 'Isian ini';
        const validity = field.validity;

        if (validity.customError) return field.validationMessage;
        if (validity.valueMissing) return `${label} wajib diisi.`;
        if (validity.typeMismatch && field.type === 'email') return 'Masukkan alamat surel dengan format yang benar.';
        if (validity.typeMismatch && field.type === 'url') return 'Masukkan alamat situs dengan format yang benar.';
        if (validity.tooShort) return `${label} harus terdiri dari sedikitnya ${field.minLength} karakter.`;
        if (validity.tooLong) return `${label} tidak boleh lebih dari ${field.maxLength} karakter.`;
        if (validity.rangeUnderflow) return `${label} tidak boleh kurang dari ${field.min || field.minLength}.`;
        if (validity.rangeOverflow) return `${label} tidak boleh lebih dari ${field.max || field.maxLength}.`;
        if (validity.stepMismatch) return `Periksa kembali nilai ${label}.`;
        if (validity.patternMismatch) return `${label} belum sesuai dengan format yang diminta.`;
        if (validity.badInput) return `Masukkan nilai ${label} dengan benar.`;

        return `Periksa kembali isian ${label}.`;
    }

    document.addEventListener('invalid', function (event) {
        event.preventDefault();

        const field = event.target;
        const picker = field.closest('.blodora-picker');
        const selectWrapper = field.closest('.blodora-select');
        const focusTarget = picker?.querySelector('.blodora-picker-trigger')
            || selectWrapper?.querySelector('.blodora-select-trigger')
            || field;

        focusTarget.setAttribute('aria-invalid', 'true');
        if (validationDialogOpen) return;

        validationDialogOpen = true;
        Swal.fire({
            icon: 'warning',
            title: 'Periksa isian',
            text: getLocalizedValidationMessage(field),
            confirmButtonText: 'Mengerti',
            confirmButtonColor: '#b42332',
        }).then(function () {
            validationDialogOpen = false;
            focusTarget.focus();
        });
    }, true);
}

document.addEventListener('DOMContentLoaded', function () {
    showPageFeedback();
    initializeSweetAlertConfirmations();
    initializeSweetAlertValidation();
});

function initializeThemedPickers() {
    document.querySelectorAll('input[type="date"], input[type="time"], input[type="month"]').forEach(function (input, index) {
        if (input.dataset.blodoraPicker === 'true') return;
        input.dataset.blodoraPicker = 'true';

        const type = input.type;
        const wrapper = document.createElement('div');
        wrapper.className = 'blodora-picker';
        input.parentNode.insertBefore(wrapper, input);
        wrapper.appendChild(input);
        input.classList.add('blodora-picker-native');
        input.tabIndex = -1;
        input.setAttribute('aria-hidden', 'true');

        const trigger = document.createElement('button');
        trigger.type = 'button';
        trigger.className = 'blodora-picker-trigger';
        trigger.classList.toggle('is-invalid', input.classList.contains('is-invalid'));
        trigger.disabled = input.disabled;
        trigger.id = input.id ? `${input.id}-picker` : `blodora-picker-${index}`;
        trigger.setAttribute('aria-haspopup', 'dialog');
        trigger.setAttribute('aria-expanded', 'false');
        const label = input.labels?.[0];
        if (label) {
            if (!label.id) label.id = `${trigger.id}-label`;
            trigger.setAttribute('aria-labelledby', label.id);
            label.addEventListener('click', function (event) {
                event.preventDefault();
                trigger.focus();
                openPicker();
            });
        } else if (input.getAttribute('aria-label')) {
            trigger.setAttribute('aria-label', input.getAttribute('aria-label'));
        }

        const value = document.createElement('span');
        value.className = 'blodora-picker-value';
        const icon = document.createElement('i');
        icon.className = `bi ${type === 'time' ? 'bi-clock' : type === 'month' ? 'bi-calendar-month' : 'bi-calendar3'}`;
        icon.setAttribute('aria-hidden', 'true');
        trigger.append(value, icon);

        const panel = document.createElement('div');
        panel.className = `blodora-picker-panel blodora-picker-${type}`;
        panel.id = `${trigger.id}-panel`;
        panel.setAttribute('role', 'dialog');
        panel.setAttribute('aria-label', type === 'time' ? 'Pilih waktu' : type === 'month' ? 'Pilih bulan' : 'Pilih tanggal');
        panel.hidden = true;
        trigger.setAttribute('aria-controls', panel.id);
        wrapper.append(trigger, panel);

        // Keep clicks inside the floating panel from being treated as outside clicks.
        panel.addEventListener('click', function (event) {
            event.stopPropagation();
        });

        let viewDate = new Date();
        let showYearPicker = false;
        let showMonthPicker = false;
        const pad = number => String(number).padStart(2, '0');
        const localIso = date => `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}`;
        const parseIso = raw => {
            const parts = raw.split('-').map(Number);
            if (parts.length === 3) return new Date(parts[0], parts[1] - 1, parts[2]);
            if (parts.length === 2) return new Date(parts[0], parts[1] - 1, 1);
            return new Date();
        };
        const emitChange = () => {
            input.dispatchEvent(new Event('input', { bubbles: true }));
            input.dispatchEvent(new Event('change', { bubbles: true }));
        };

        function updateValue() {
            if (!input.value) {
                value.textContent = input.placeholder || (type === 'time' ? 'Pilih waktu' : type === 'month' ? 'Pilih bulan' : 'Pilih tanggal');
                trigger.classList.add('is-placeholder');
                return;
            }
            if (type === 'time') {
                value.textContent = input.value.slice(0, 5);
            } else if (type === 'month') {
                const date = parseIso(input.value);
                value.textContent = new Intl.DateTimeFormat('id-ID', { month: 'long', year: 'numeric' }).format(date);
            } else {
                value.textContent = new Intl.DateTimeFormat('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }).format(parseIso(input.value));
            }
            trigger.classList.remove('is-placeholder');
        }

        function setDate(date) {
            input.value = localIso(date);
            viewDate = new Date(date.getFullYear(), date.getMonth(), 1);
            emitChange();
            updateValue();
            closePicker();
            trigger.focus();
        }

        function setMonth(year, month) {
            input.value = `${year}-${pad(month + 1)}`;
            viewDate = new Date(year, month, 1);
            emitChange();
            updateValue();
            closePicker();
            trigger.focus();
        }

        function renderCalendar() {
            panel.replaceChildren();
            const header = document.createElement('div');
            header.className = 'blodora-picker-header';
            const previous = document.createElement('button');
            previous.type = 'button';
            previous.className = 'blodora-picker-nav';
            previous.setAttribute('aria-label', type === 'month' ? 'Tahun sebelumnya' : 'Bulan sebelumnya');
            previous.innerHTML = '<i class="bi bi-chevron-left" aria-hidden="true"></i>';
            const title = document.createElement('strong');
            title.textContent = type === 'month'
                ? String(viewDate.getFullYear())
                : showYearPicker
                    ? 'Pilih tahun'
                    : showMonthPicker
                        ? 'Pilih bulan'
                        : new Intl.DateTimeFormat('id-ID', { month: 'long' }).format(viewDate);
            let yearButton = null;
            let monthButton = null;
            if (type === 'date') {
                yearButton = document.createElement('button');
                yearButton.type = 'button';
                yearButton.className = 'blodora-picker-title-button';
                yearButton.setAttribute('aria-label', 'Pilih tahun');
                yearButton.setAttribute('aria-haspopup', 'grid');
                yearButton.setAttribute('aria-expanded', String(showYearPicker));
                yearButton.innerHTML = `${showYearPicker ? `${Math.floor(viewDate.getFullYear() / 10) * 10 - 1}–${Math.floor(viewDate.getFullYear() / 10) * 10 + 10}` : viewDate.getFullYear()} <i class="bi bi-chevron-down" aria-hidden="true"></i>`;
                yearButton.addEventListener('click', function (event) {
                    event.preventDefault();
                    event.stopPropagation();
                    showYearPicker = !showYearPicker;
                    showMonthPicker = false;
                    renderCalendar();
                });
                monthButton = document.createElement('button');
                monthButton.type = 'button';
                monthButton.className = 'blodora-picker-title-button';
                monthButton.setAttribute('aria-label', 'Pilih bulan');
                monthButton.setAttribute('aria-haspopup', 'grid');
                monthButton.setAttribute('aria-expanded', String(showMonthPicker));
                monthButton.innerHTML = `${new Intl.DateTimeFormat('id-ID', { month: 'short' }).format(viewDate)} <i class="bi bi-chevron-down" aria-hidden="true"></i>`;
                monthButton.addEventListener('click', function (event) {
                    event.preventDefault();
                    event.stopPropagation();
                    showMonthPicker = !showMonthPicker;
                    showYearPicker = false;
                    renderCalendar();
                });
            }
            const next = document.createElement('button');
            next.type = 'button';
            next.className = 'blodora-picker-nav';
            next.setAttribute('aria-label', type === 'month' ? 'Tahun berikutnya' : 'Bulan berikutnya');
            next.innerHTML = '<i class="bi bi-chevron-right" aria-hidden="true"></i>';
            previous.addEventListener('click', function () {
                viewDate = type === 'month'
                    ? new Date(viewDate.getFullYear() - 1, 0, 1)
                    : showYearPicker
                        ? new Date(viewDate.getFullYear() - 10, viewDate.getMonth(), 1)
                        : showMonthPicker
                            ? new Date(viewDate.getFullYear() - 1, viewDate.getMonth(), 1)
                        : new Date(viewDate.getFullYear(), viewDate.getMonth() - 1, 1);
                renderCalendar();
            });
            next.addEventListener('click', function () {
                viewDate = type === 'month'
                    ? new Date(viewDate.getFullYear() + 1, 0, 1)
                    : showYearPicker
                        ? new Date(viewDate.getFullYear() + 10, viewDate.getMonth(), 1)
                        : showMonthPicker
                            ? new Date(viewDate.getFullYear() + 1, viewDate.getMonth(), 1)
                        : new Date(viewDate.getFullYear(), viewDate.getMonth() + 1, 1);
                renderCalendar();
            });
            header.append(previous);
            if (type === 'date' && monthButton) header.appendChild(monthButton);
            else header.appendChild(title);
            if (yearButton) header.appendChild(yearButton);
            header.appendChild(next);
            panel.appendChild(header);

            if (showYearPicker) {
                const years = document.createElement('div');
                years.className = 'blodora-picker-year-grid';
                const firstYear = Math.floor(viewDate.getFullYear() / 10) * 10 - 1;
                for (let offset = 0; offset < 12; offset++) {
                    const year = firstYear + offset;
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'blodora-picker-day';
                    button.textContent = String(year);
                    button.classList.toggle('is-selected', viewDate.getFullYear() === year);
                    const minYear = input.min ? Number(input.min.slice(0, 4)) : null;
                    const maxYear = input.max ? Number(input.max.slice(0, 4)) : null;
                    button.disabled = Boolean((minYear !== null && year < minYear) || (maxYear !== null && year > maxYear));
                    button.addEventListener('click', function () {
                        const month = viewDate.getMonth();
                        viewDate = new Date(year, month, 1);
                        showYearPicker = false;
                        renderCalendar();
                    });
                    years.appendChild(button);
                }
                panel.appendChild(years);
                return;
            }

            if (showMonthPicker && type === 'date') {
                const months = document.createElement('div');
                months.className = 'blodora-picker-month-grid';
                const minMonth = input.min ? input.min.slice(0, 7) : null;
                const maxMonth = input.max ? input.max.slice(0, 7) : null;
                for (let month = 0; month < 12; month++) {
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'blodora-picker-day';
                    button.textContent = new Intl.DateTimeFormat('id-ID', { month: 'short' }).format(new Date(viewDate.getFullYear(), month, 1));
                    const monthValue = `${viewDate.getFullYear()}-${pad(month + 1)}`;
                    button.classList.toggle('is-selected', viewDate.getMonth() === month);
                    button.disabled = Boolean((minMonth && monthValue < minMonth) || (maxMonth && monthValue > maxMonth));
                    button.addEventListener('click', function () {
                        viewDate = new Date(viewDate.getFullYear(), month, 1);
                        showMonthPicker = false;
                        renderCalendar();
                    });
                    months.appendChild(button);
                }
                panel.appendChild(months);
                return;
            }

            if (type === 'month') {
                const grid = document.createElement('div');
                grid.className = 'blodora-picker-month-grid';
                for (let month = 0; month < 12; month++) {
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'blodora-picker-day';
                    button.textContent = new Intl.DateTimeFormat('id-ID', { month: 'short' }).format(new Date(viewDate.getFullYear(), month, 1));
                    const monthValue = `${viewDate.getFullYear()}-${pad(month + 1)}`;
                    button.classList.toggle('is-selected', monthValue === input.value);
                    button.disabled = Boolean((input.min && monthValue < input.min.slice(0, 7)) || (input.max && monthValue > input.max.slice(0, 7)));
                    button.addEventListener('click', () => setMonth(viewDate.getFullYear(), month));
                    grid.appendChild(button);
                }
                panel.appendChild(grid);
                return;
            }

            const grid = document.createElement('div');
            grid.className = 'blodora-picker-calendar-grid';
            ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'].forEach(function (day) {
                const heading = document.createElement('span');
                heading.className = 'blodora-picker-weekday';
                heading.textContent = day;
                grid.appendChild(heading);
            });
            const first = new Date(viewDate.getFullYear(), viewDate.getMonth(), 1);
            const startOffset = first.getDay();
            const dayCount = new Date(viewDate.getFullYear(), viewDate.getMonth() + 1, 0).getDate();
            const minDate = input.min ? parseIso(input.min) : null;
            const maxDate = input.max ? parseIso(input.max) : null;
            for (let cell = 0; cell < 42; cell++) {
                const day = cell - startOffset + 1;
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'blodora-picker-day';
                if (day < 1 || day > dayCount) {
                    button.disabled = true;
                    button.classList.add('is-outside');
                    button.tabIndex = -1;
                } else {
                    const date = new Date(viewDate.getFullYear(), viewDate.getMonth(), day);
                    const iso = localIso(date);
                    button.textContent = String(day);
                    button.disabled = Boolean((minDate && date < minDate) || (maxDate && date > maxDate));
                    button.classList.toggle('is-today', iso === localIso(new Date()));
                    button.classList.toggle('is-selected', iso === input.value);
                    button.addEventListener('click', () => setDate(date));
                }
                grid.appendChild(button);
            }
            panel.appendChild(grid);
            const footer = document.createElement('div');
            footer.className = 'blodora-picker-footer';
            const today = document.createElement('button');
            today.type = 'button';
            today.className = 'blodora-picker-today';
            today.textContent = 'Hari ini';
            today.addEventListener('click', function () {
                const now = new Date();
                if ((!minDate || now >= minDate) && (!maxDate || now <= maxDate)) setDate(now);
            });
            footer.appendChild(today);
            panel.appendChild(footer);
        }

        function renderTime() {
            panel.replaceChildren();
            const head = document.createElement('div');
            head.className = 'blodora-picker-header';
            const title = document.createElement('strong');
            title.textContent = 'Pilih waktu';
            head.appendChild(title);
            panel.appendChild(head);

            const fields = document.createElement('div');
            fields.className = 'blodora-time-fields';
            const [currentHour = '', currentMinute = ''] = input.value.split(':');
            let selectedHour = currentHour;
            let selectedMinute = currentMinute;

            function buildTimeColumn(label, count, getSelected, setSelected) {
                const column = document.createElement('div');
                column.className = 'blodora-time-column';
                const heading = document.createElement('span');
                heading.className = 'blodora-time-column-label';
                heading.textContent = label;
                const options = document.createElement('div');
                options.className = 'blodora-time-options';
                options.setAttribute('role', 'group');
                options.setAttribute('aria-label', label);

                for (let value = 0; value < count; value++) {
                    const optionValue = pad(value);
                    const option = document.createElement('button');
                    option.type = 'button';
                    option.className = 'blodora-time-option';
                    option.textContent = optionValue;
                    option.setAttribute('aria-pressed', String(getSelected() === optionValue));
                    option.classList.toggle('is-selected', getSelected() === optionValue);
                    option.addEventListener('click', function () {
                        setSelected(optionValue);
                        panel.querySelector('.blodora-time-error')?.remove();
                        options.querySelectorAll('.blodora-time-option').forEach(button => {
                            const selected = button === option;
                            button.classList.toggle('is-selected', selected);
                            button.setAttribute('aria-pressed', String(selected));
                        });
                    });
                    options.appendChild(option);
                }

                column.append(heading, options);
                return column;
            }

            fields.append(
                buildTimeColumn('Jam', 24, () => selectedHour, value => { selectedHour = value; }),
                buildTimeColumn('Menit', 60, () => selectedMinute, value => { selectedMinute = value; }),
            );
            panel.appendChild(fields);

            const presets = document.createElement('div');
            presets.className = 'blodora-time-presets';
            ['08:00', '09:00', '12:00', '13:00', '16:00'].forEach(function (time) {
                const option = document.createElement('button');
                option.type = 'button';
                option.className = 'blodora-picker-day';
                option.textContent = time;
                option.addEventListener('click', function () {
                    selectedHour = time.slice(0, 2);
                    selectedMinute = time.slice(3);
                    panel.querySelector('.blodora-time-error')?.remove();
                    fields.querySelectorAll('.blodora-time-option').forEach(button => {
                        const selected = button.textContent === selectedHour || button.textContent === selectedMinute;
                        button.classList.toggle('is-selected', selected);
                        button.setAttribute('aria-pressed', String(selected));
                    });
                });
                presets.appendChild(option);
            });
            panel.appendChild(presets);

            const footer = document.createElement('div');
            footer.className = 'blodora-picker-footer';
            const save = document.createElement('button');
            save.type = 'button';
            save.className = 'blodora-picker-apply';
            save.textContent = 'Pilih waktu';
            save.addEventListener('click', function () {
                if (!selectedHour || !selectedMinute) {
                    let error = panel.querySelector('.blodora-time-error');
                    if (!error) {
                        error = document.createElement('p');
                        error.className = 'blodora-time-error';
                        error.setAttribute('role', 'alert');
                        panel.insertBefore(error, footer);
                    }
                    error.textContent = !selectedHour ? 'Pilih jam terlebih dahulu.' : 'Pilih menit terlebih dahulu.';
                    return;
                }
                const nextValue = `${selectedHour}:${selectedMinute}`;
                if ((input.min && nextValue < input.min.slice(0, 5)) || (input.max && nextValue > input.max.slice(0, 5))) {
                    let error = panel.querySelector('.blodora-time-error');
                    if (!error) {
                        error = document.createElement('p');
                        error.className = 'blodora-time-error';
                        error.setAttribute('role', 'alert');
                        panel.insertBefore(error, footer);
                    }
                    error.textContent = 'Waktu berada di luar batas yang diizinkan.';
                    return;
                }
                input.value = nextValue;
                emitChange();
                updateValue();
                closePicker();
                trigger.focus();
            });
            footer.appendChild(save);
            panel.appendChild(footer);
        }

        function openPicker() {
            if (input.disabled) return;
            if (type === 'time') renderTime();
            else {
                showYearPicker = false;
                showMonthPicker = false;
                if (input.value) viewDate = parseIso(input.value);
                else {
                    const todayIso = localIso(new Date());
                    if (input.min && todayIso < input.min) viewDate = parseIso(input.min);
                    else if (input.max && todayIso > input.max) viewDate = parseIso(input.max);
                    else viewDate = new Date();
                }
                viewDate = new Date(viewDate.getFullYear(), viewDate.getMonth(), 1);
                renderCalendar();
            }
            panel.hidden = false;
            wrapper.classList.add('is-open');
            trigger.setAttribute('aria-expanded', 'true');
            panel.querySelectorAll('.blodora-time-options').forEach(options => {
                const selected = options.querySelector('.is-selected');
                if (!selected) return;
                options.scrollTop = Math.max(0, selected.offsetTop - options.offsetTop - (options.clientHeight / 2) + (selected.clientHeight / 2));
            });
        }

        function closePicker() {
            panel.hidden = true;
            wrapper.classList.remove('is-open');
            trigger.setAttribute('aria-expanded', 'false');
        }

        trigger.addEventListener('click', function () {
            panel.hidden ? openPicker() : closePicker();
        });
        trigger.addEventListener('keydown', function (event) {
            if (['Enter', ' '].includes(event.key)) {
                event.preventDefault();
                openPicker();
            }
            if (event.key === 'Escape') closePicker();
        });
        input.addEventListener('change', function () {
            updateValue();
            if (input.checkValidity()) {
                trigger.classList.remove('is-invalid');
                trigger.removeAttribute('aria-invalid');
            }
        });
        input.form?.addEventListener('reset', () => window.setTimeout(updateValue, 0));
        document.addEventListener('click', event => { if (!wrapper.contains(event.target)) closePicker(); });
        document.addEventListener('keydown', event => { if (event.key === 'Escape') closePicker(); });
        updateValue();
    });
}

function initializeThemedSelects() {
    document.querySelectorAll('select.form-select, select.admin-form-select, select.admin-form-control').forEach(function (select, index) {
        if (select.multiple || select.dataset.themedSelect === 'true') {
            return;
        }

        select.dataset.themedSelect = 'true';
        const wrapper = document.createElement('div');
        wrapper.className = 'blodora-select';
        select.parentNode.insertBefore(wrapper, select);
        wrapper.appendChild(select);

        const trigger = document.createElement('button');
        trigger.type = 'button';
        trigger.className = 'blodora-select-trigger';
        trigger.disabled = select.disabled;
        trigger.id = select.id ? `${select.id}-trigger` : `blodora-select-${index}`;
        trigger.setAttribute('role', 'combobox');
        trigger.setAttribute('aria-haspopup', 'listbox');
        trigger.setAttribute('aria-expanded', 'false');

        const label = select.labels?.[0];
        if (label) {
            if (!label.id) label.id = `${trigger.id}-label`;
            trigger.setAttribute('aria-labelledby', label.id);
            label.addEventListener('click', function (event) {
                event.preventDefault();
                trigger.focus();
                openMenu();
            });
        } else if (select.getAttribute('aria-label')) {
            trigger.setAttribute('aria-label', select.getAttribute('aria-label'));
        }

        const value = document.createElement('span');
        const chevron = document.createElement('span');
        chevron.className = 'blodora-select-chevron';
        chevron.setAttribute('aria-hidden', 'true');
        trigger.append(value, chevron);

        const menu = document.createElement('div');
        menu.className = 'blodora-select-menu';
        menu.id = `${trigger.id}-options`;
        menu.setAttribute('role', 'listbox');
        trigger.setAttribute('aria-controls', menu.id);

        function createOption(option, optionIndex) {
            const item = document.createElement('button');
            item.type = 'button';
            item.className = 'blodora-select-option';
            item.setAttribute('role', 'option');
            item.textContent = option.textContent.trim();
            item.disabled = option.disabled;
            item.dataset.value = option.value;
            item.dataset.optionIndex = String(optionIndex);
            item.addEventListener('click', function () {
                if (item.disabled) return;
                select.value = option.value;
                select.dispatchEvent(new Event('input', { bubbles: true }));
                select.dispatchEvent(new Event('change', { bubbles: true }));
                updateValue();
                closeMenu();
                trigger.focus();
            });
            menu.appendChild(item);
            return item;
        }

        let options = Array.from(select.options).map(createOption);

        wrapper.append(trigger, menu);
        const validationMessage = document.createElement('span');
        validationMessage.className = 'blodora-select-error';
        validationMessage.id = `${trigger.id}-error`;
        validationMessage.setAttribute('role', 'alert');
        validationMessage.hidden = true;
        wrapper.appendChild(validationMessage);
        trigger.setAttribute('aria-describedby', validationMessage.id);
        select.classList.add('blodora-native-select');
        select.setAttribute('aria-hidden', 'true');
        select.tabIndex = -1;

        function updateValue() {
            const selected = select.options[select.selectedIndex];
            value.textContent = selected ? selected.textContent.trim() : '';
            options.forEach(function (item, optionIndex) {
                const active = optionIndex === select.selectedIndex;
                item.setAttribute('aria-selected', active ? 'true' : 'false');
                item.classList.toggle('is-selected', active);
            });
            trigger.classList.toggle('is-placeholder', !select.value);
            validationMessage.hidden = true;
            trigger.removeAttribute('aria-invalid');
        }

        function openMenu() {
            wrapper.classList.add('is-open');
            trigger.setAttribute('aria-expanded', 'true');
            const selectedIndex = select.selectedIndex >= 0 ? select.selectedIndex : 0;
            const initial = options.slice(selectedIndex).find(function (item) { return !item.disabled; })
                || options.find(function (item) { return !item.disabled; });
            initial?.focus();
        }

        function closeMenu() {
            wrapper.classList.remove('is-open');
            trigger.setAttribute('aria-expanded', 'false');
        }

        trigger.addEventListener('click', function () {
            wrapper.classList.contains('is-open') ? closeMenu() : openMenu();
        });

        trigger.addEventListener('keydown', function (event) {
            if (['ArrowDown', 'ArrowUp', 'Enter', ' '].includes(event.key)) {
                event.preventDefault();
                openMenu();
            }
        });

        menu.addEventListener('keydown', function (event) {
            const enabled = options.filter(function (item) { return !item.disabled; });
            const current = enabled.indexOf(document.activeElement);
            let next = current;

            if (event.key === 'ArrowDown') next = (current + 1) % enabled.length;
            else if (event.key === 'ArrowUp') next = (current - 1 + enabled.length) % enabled.length;
            else if (event.key === 'Home') next = 0;
            else if (event.key === 'End') next = enabled.length - 1;
            else if (event.key === 'Escape') {
                event.preventDefault();
                closeMenu();
                trigger.focus();
                return;
            } else {
                return;
            }

            event.preventDefault();
            enabled[next]?.focus();
        });

        select.addEventListener('change', updateValue);
        select.addEventListener('remote-options-updated', function () {
            options = Array.from(select.options).map(createOption);
            menu.replaceChildren(...options);
            updateValue();
        });
        select.form?.addEventListener('reset', function () {
            window.setTimeout(updateValue, 0);
        });

        document.addEventListener('click', function (event) {
            if (!wrapper.contains(event.target)) closeMenu();
        });

        updateValue();
    });
}

document.addEventListener('DOMContentLoaded', initializeThemedSelects);
document.addEventListener('DOMContentLoaded', initializeThemedPickers);

document.addEventListener('DOMContentLoaded', function initializeRemoteSelectSearch() {
    document.querySelectorAll('[data-remote-search-input]').forEach(function (input) {
        const select = document.getElementById(input.dataset.target);
        const status = input.parentElement.querySelector('[data-remote-search-status]');
        if (!select || !status) return;

        let timer = null;
        let activeRequest = null;
        let selectedLabel = select.selectedOptions[0]?.value
            ? select.selectedOptions[0].textContent.trim()
            : '';
        const placeholder = select.options[0]?.textContent || 'Pilih';

        function replaceOptions(items) {
            const selectedValue = select.value;
            const selectedOption = selectedValue
                ? Array.from(select.options).find(option => option.value === selectedValue)
                : null;
            const options = [new Option(placeholder, '')];

            if (selectedOption) {
                options.push(new Option(selectedOption.textContent, selectedOption.value, true, true));
            }

            items.forEach(function (item) {
                if (String(item.id) === selectedValue) return;
                options.push(new Option(item.label, item.id));
            });

            select.replaceChildren(...options);
            select.dispatchEvent(new Event('remote-options-updated'));
        }

        select.addEventListener('change', function () {
            const selected = select.selectedOptions[0];
            if (selected?.value) {
                selectedLabel = selected.textContent.trim();
                input.value = selectedLabel;
                status.textContent = `Pilihan: ${selectedLabel}`;
            } else {
                selectedLabel = '';
            }
        });

        input.addEventListener('input', function () {
            const query = input.value.trim();
            if (query !== selectedLabel) {
                select.value = '';
                selectedLabel = '';
                replaceOptions([]);
            }

            window.clearTimeout(timer);
            activeRequest?.abort();

            if (query.length < 2) {
                replaceOptions([]);
                status.textContent = 'Ketik minimal 2 karakter untuk mencari.';
                return;
            }

            status.textContent = 'Mencari…';
            timer = window.setTimeout(async function () {
                activeRequest = new AbortController();
                try {
                    const url = new URL(input.dataset.url, window.location.origin);
                    url.searchParams.set('q', query);
                    const response = await fetch(url, {
                        headers: { Accept: 'application/json' },
                        credentials: 'same-origin',
                        signal: activeRequest.signal,
                    });
                    if (!response.ok) throw new Error('Pencarian gagal. Coba lagi.');

                    const items = await response.json();
                    replaceOptions(items);
                    status.textContent = items.length === 20
                        ? 'Menampilkan 20 hasil teratas. Persempit pencarian jika belum menemukan pilihan.'
                        : items.length
                            ? `${items.length} hasil ditemukan. Pilih hasil pada daftar.`
                            : 'Tidak ada hasil yang cocok.';
                } catch (error) {
                    if (error.name === 'AbortError') return;
                    status.textContent = 'Pencarian gagal. Coba lagi.';
                }
            }, 250);
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('[data-month-report-filter]').forEach(function (form) {
        form.addEventListener('submit', function () {
            const month = form.querySelector('[data-report-month]')?.value;
            const year = form.querySelector('[data-report-year]')?.value;
            const target = form.querySelector('input[name="bulan"]');
            if (month && year && target) target.value = `${year}-${month}`;
        });
    });

    document.querySelectorAll('[data-clear-search]').forEach(function (button) {
        button.addEventListener('click', function () {
            const form = button.closest('form');
            const input = form?.querySelector('input[name="q"]');

            if (!form || !input) {
                return;
            }

            input.value = '';
            form.requestSubmit();
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Password Show / Hide
    |--------------------------------------------------------------------------
    */

    const passwordToggles = document.querySelectorAll(
        '[data-password-toggle]'
    );

    passwordToggles.forEach(function (toggle) {

        toggle.addEventListener('click', function () {

            const inputId = this.dataset.passwordToggle;
            const input = document.getElementById(inputId);

            if (!input) {
                return;
            }

            const isPassword = input.type === 'password';

            input.type = isPassword ? 'text' : 'password';

            this.innerHTML = isPassword
                ? '<i class="bi bi-eye-slash"></i>'
                : '<i class="bi bi-eye"></i>';

            this.setAttribute(
                'aria-label',
                isPassword
                    ? 'Sembunyikan kata sandi'
                    : 'Tampilkan kata sandi'
            );
            this.setAttribute('aria-pressed', isPassword ? 'true' : 'false');
        });

    });


    /*
    |--------------------------------------------------------------------------
    | Submit Loading
    |--------------------------------------------------------------------------
    */

    const authForms = document.querySelectorAll('.auth-form');

    authForms.forEach(function (form) {

        form.addEventListener('submit', function (event) {

            /*
             * Jangan mengaktifkan loading jika browser
             * menemukan input HTML yang tidak valid.
             */
            if (!form.checkValidity()) {
                return;
            }

            const submitButton = form.querySelector(
                'button[type="submit"]'
            );

            if (!submitButton) {
                return;
            }

            const normalText = submitButton.querySelector('.btn-text');
            const loadingText = submitButton.querySelector('.btn-loading');

            submitButton.disabled = true;
            submitButton.classList.add('is-loading');

            if (normalText) {
                normalText.classList.add('d-none');
            }

            if (loadingText) {
                loadingText.classList.remove('d-none');
            }

        });

    });

});

const initializePageInteractions = function () {

    const sidebar = document.querySelector(".admin-sidebar, .donor-sidebar");
    const sidebarToggles = sidebar?.querySelectorAll(".sidebar-brand-toggle");
    const sidebarBackdrop = document.querySelector(".sidebar-mobile-backdrop");
    const mobileMenuTrigger = document.querySelector("[data-sidebar-open]");

    if (sidebar && sidebarToggles?.length) {
        const isMobileSidebar = function () {
            return window.matchMedia("(max-width: 767.98px)").matches;
        };

        const updateToggleHint = function (isCollapsed) {
            sidebarToggles.forEach(function (toggle) {
                const icon = toggle.querySelector("i");
                if (icon) icon.className = `bi bi-chevron-${isCollapsed ? "right" : "left"}`;
                toggle.setAttribute("aria-expanded", String(!isCollapsed));
                toggle.setAttribute("aria-label", isCollapsed ? "Buka sidebar" : "Tutup sidebar");
            });
        };

        const closeMobileSidebar = function () {
            document.body.classList.remove("sidebar-open");
            mobileMenuTrigger?.setAttribute("aria-expanded", "false");
            if (isMobileSidebar()) {
                updateToggleHint(true);
            }
        };

        mobileMenuTrigger?.addEventListener("click", function () {
            document.body.classList.add("sidebar-open");
            mobileMenuTrigger.setAttribute("aria-expanded", "true");
            sidebarToggles[0]?.focus();
        });

        if (isMobileSidebar()) {
            updateToggleHint(true);
        } else {
            updateToggleHint(document.body.classList.contains("sidebar-collapsed"));
        }

        sidebarToggles.forEach(function (toggle) {
            toggle.addEventListener("click", function () {
                if (isMobileSidebar()) {
                    const isOpen = document.body.classList.toggle("sidebar-open");
                    mobileMenuTrigger?.setAttribute("aria-expanded", String(isOpen));
                    updateToggleHint(!isOpen);
                } else {
                    const isCollapsed = document.body.classList.toggle("sidebar-collapsed");
                    try {
                        localStorage.setItem("blodora-sidebar-collapsed", String(isCollapsed));
                    } catch (error) {
                        // The sidebar still works for this page if storage is unavailable.
                    }
                    updateToggleHint(isCollapsed);
                }
            });
        });

        sidebarBackdrop?.addEventListener("click", closeMobileSidebar);
        document.addEventListener("keydown", function (event) {
            if (event.key === "Escape" && document.body.classList.contains("sidebar-open")) {
                closeMobileSidebar();
                mobileMenuTrigger?.focus();
            }
        });
        sidebar.querySelectorAll("a").forEach(function (link) {
            link.addEventListener("click", function () {
                closeMobileSidebar();
            });
        });

        window.addEventListener("resize", function () {
            document.body.classList.remove("sidebar-open");
            if (isMobileSidebar()) {
                document.body.classList.remove("sidebar-collapsed");
                updateToggleHint(true);
            } else {
                let isCollapsed = false;
                try {
                    isCollapsed = localStorage.getItem("blodora-sidebar-collapsed") === "true";
                } catch (error) {
                    isCollapsed = document.body.classList.contains("sidebar-collapsed");
                }
                document.body.classList.toggle("sidebar-collapsed", isCollapsed);
                updateToggleHint(isCollapsed);
            }
        });
    }

    const revealItems = document.querySelectorAll("[data-reveal]");
    const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

    if (revealItems.length && !reduceMotion && "IntersectionObserver" in window) {
        const revealObserver = new IntersectionObserver(function (entries, observer) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add("is-visible");
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12, rootMargin: "0px 0px -36px 0px" });

        document.body.classList.add("motion-ready");
        revealItems.forEach(function (item) {
            revealObserver.observe(item);
        });
    }

    const navbar = document.querySelector(".blodora-navbar");

    const navLinks = document.querySelectorAll(
        ".blodora-navbar .nav-link"
    );

    const sections = document.querySelectorAll(
        "section[id]"
    );
    let requestedSection = window.location.hash.slice(1) || null;

    if (!navbar) {
        return;
    }


    /* =========================
       NAVBAR SCROLL EFFECT
    ========================= */

    function updateLandingNavigation() {
        navbar.classList.toggle("scrolled", window.scrollY > 50);
        let currentSection = "";
        const sectionMarker = window.scrollY + 150;

        sections.forEach(function (section) {
            if (section.offsetTop <= sectionMarker) {
                currentSection = section.id;
            }
        });

        // The About item remains selected through the footer, after the last
        // landing section has reached the navigation marker.

        if (requestedSection) {
            const target = document.getElementById(requestedSection);
            if (target && Math.abs(target.getBoundingClientRect().top - 90) > 220) {
                currentSection = requestedSection;
            } else {
                requestedSection = null;
            }
        }


        navLinks.forEach(function (link) {

            link.classList.remove("active");
            link.removeAttribute("aria-current");

            const href = link.getAttribute("href");

            if (href === "#" + currentSection) {

                link.classList.add("active");
                link.setAttribute("aria-current", "location");

            }

        });

    }

    updateLandingNavigation();
    window.addEventListener("scroll", updateLandingNavigation, { passive: true });

    const navbarCollapse = document.querySelector("#navbarBlodora");
    const navbarToggler = document.querySelector(".blodora-navbar .navbar-toggler");

    navbarCollapse?.querySelectorAll(".nav-link").forEach(function (link) {
        link.addEventListener("click", function () {
            requestedSection = link.hash.slice(1);
            updateLandingNavigation();

            if (window.matchMedia("(max-width: 991px)").matches && navbarCollapse.classList.contains("show")) {
                navbarCollapse.classList.remove("show");
                navbarToggler?.setAttribute("aria-expanded", "false");
            }
        });
    });

};

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initializePageInteractions, { once: true });
} else {
    initializePageInteractions();
}
