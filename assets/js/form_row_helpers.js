// assets/js/form_row_helpers.js
// Helper untuk tambah/hapus row pada tabel klasifikasi & analisa data


function tambahRowKlasifikasi({tbodyId, rowCountVar, isReadonly, data = null}) {
    const tbody = document.getElementById(tbodyId);
    if (typeof window[rowCountVar] !== 'number' || isNaN(window[rowCountVar])) {
        window[rowCountVar] = 1;
    }
    const index = window[rowCountVar];
    const row = document.createElement('tr');
    row.innerHTML = `
        <td class="text-center align-middle">${index}</td>
        <td>
            <textarea class="form-control form-control-sm" name="klasifikasi[${index}][ds]" rows="2" style="resize:none; overflow:hidden;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" ${isReadonly ? 'readonly' : ''}>${data?.ds ?? ''}</textarea>
        </td>
        <td>
            <textarea class="form-control form-control-sm" name="klasifikasi[${index}][do]" rows="2" style="resize:none; overflow:hidden;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" ${isReadonly ? 'readonly' : ''}>${data?.do ?? ''}</textarea>
        </td>
        <td class="text-center align-middle">
            <button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)" ${isReadonly ? 'disabled' : ''}>x</button>
        </td>
    `;
    tbody.appendChild(row);
    window[rowCountVar]++;
}


function tambahRowAnalisa({tbodyId, rowCountVar, isReadonly, data = null}) {
    const tbody = document.getElementById(tbodyId);
    if (typeof window[rowCountVar] !== 'number' || isNaN(window[rowCountVar])) {
        window[rowCountVar] = 1;
    }
    const index = window[rowCountVar];
    const row = document.createElement('tr');
    row.innerHTML = `
        <td class="text-center align-middle">${index}</td>
        <td>
            <textarea class="form-control form-control-sm" name="analisa[${index}][ds_do]" rows="2" style="resize:none; overflow:hidden;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" ${isReadonly ? 'readonly' : ''}>${data?.ds_do ?? ''}</textarea>
        </td>
        <td>
            <textarea class="form-control form-control-sm" name="analisa[${index}][etiologi]" rows="2" style="resize:none; overflow:hidden;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" ${isReadonly ? 'readonly' : ''}>${data?.etiologi ?? ''}</textarea>
        </td>
        <td>
            <textarea class="form-control form-control-sm" name="analisa[${index}][masalah]" rows="2" style="resize:none; overflow:hidden;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" ${isReadonly ? 'readonly' : ''}>${data?.masalah ?? ''}</textarea>
        </td>
        <td class="text-center align-middle">
            <button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)" ${isReadonly ? 'disabled' : ''}>x</button>
        </td>
    `;
    tbody.appendChild(row);
    window[rowCountVar]++;
}

function hapusRow(btn) {
    btn.closest('tr').remove();
}

// Export for module usage if needed
// export { tambahRowKlasifikasi, tambahRowAnalisa, hapusRow };

// ========== ANAK FORMAT ANGGREK: CATATAN KEPERAWATAN ==========
function tambahRowDiagnosa({tbodyId, rowCountVar, isReadonly, data = {}}) {
    const tbody = document.getElementById(tbodyId);
    if (typeof window[rowCountVar] !== 'number' || isNaN(window[rowCountVar])) window[rowCountVar] = 1;
    const index = window[rowCountVar];
    const row = document.createElement('tr');
    row.innerHTML = `
        <td class="text-center align-middle">${index}</td>
        <td><input type="text" class="form-control form-control-sm" name="diagnosa[${index}][diagnosa]" value="${data.diagnosa ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
        <td><input type="date" class="form-control form-control-sm" name="diagnosa[${index}][tgl_ditemukan]" value="${data.tgl_ditemukan ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
        <td><input type="date" class="form-control form-control-sm" name="diagnosa[${index}][tgl_teratasi]" value="${data.tgl_teratasi ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
        ${!isReadonly ? `<td class="text-center"><button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)">x</button></td>` : ''}
    `;
    tbody.appendChild(row);
    window[rowCountVar]++;
}

function tambahRowIntervensi({tbodyId, rowCountVar, isReadonly, data = {}}) {
    const tbody = document.getElementById(tbodyId);
    if (typeof window[rowCountVar] !== 'number' || isNaN(window[rowCountVar])) window[rowCountVar] = 1;
    const index = window[rowCountVar];
    const row = document.createElement('tr');
    row.innerHTML = `
        <td class="text-center align-middle">${index}</td>
        <td><input type="text" class="form-control form-control-sm" name="intervensi[${index}][diagnosa]" value="${data.diagnosa ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
        <td><input type="text" class="form-control form-control-sm" name="intervensi[${index}][tujuan_kriteria]" value="${data.tujuan_kriteria ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
        <td><input type="text" class="form-control form-control-sm" name="intervensi[${index}][intervensi]" value="${data.intervensi ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
        ${!isReadonly ? `<td class="text-center"><button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)">x</button></td>` : ''}
    `;
    tbody.appendChild(row);
    window[rowCountVar]++;
}

function tambahRowImplementasi({tbodyId, rowCountVar, isReadonly, data = {}}) {
    const tbody = document.getElementById(tbodyId);
    if (typeof window[rowCountVar] !== 'number' || isNaN(window[rowCountVar])) window[rowCountVar] = 1;
    const index = window[rowCountVar];
    const row = document.createElement('tr');
    row.innerHTML = `
        <td>
            <input
                type="text"
                class="form-control form-control-sm"
                name="implementasi[${index}][no_dx]"
                value="${data.no_dx ?? ''}"
                ${isReadonly ? 'readonly' : ''}
            >
        </td>
        <td>
            <input
                type="date"
                class="form-control form-control-sm"
                name="implementasi[${index}][hari_tgl]"
                value="${data.hari_tgl ?? ''}"
                ${isReadonly ? 'readonly' : ''}
            >
        </td>
        <td>
            <input
                type="time"
                class="form-control form-control-sm"
                name="implementasi[${index}][jam]"
                value="${data.jam ?? ''}"
                ${isReadonly ? 'readonly' : ''}
            >
        </td>
        <td>
            <textarea
                class="form-control form-control-sm"
                name="implementasi[${index}][implementasi]"
                rows="2"
                style="resize:none; overflow:hidden;"
                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                ${isReadonly ? 'readonly' : ''}
            >${data.implementasi ?? ''}</textarea>
        </td>
        <td class="text-center align-middle">
            ${!isReadonly ? `<button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)">x</button>` : ''}
        </td>
    `;
    tbody.appendChild(row);
    window[rowCountVar]++;
}

function tambahRowEvaluasi({tbodyId, rowCountVar, isReadonly, data = {}}) {
    const tbody = document.getElementById(tbodyId);
    if (typeof window[rowCountVar] !== 'number' || isNaN(window[rowCountVar])) window[rowCountVar] = 1;
    const index = window[rowCountVar];
    const row = document.createElement('tr');
    row.innerHTML = `
        <td>
            <input
                type="text"
                class="form-control form-control-sm"
                name="evaluasi[${index}][no_dx]"
                value="${data.no_dx ?? ''}"
                ${isReadonly ? 'readonly' : ''}
            >
        </td>
        <td>
            <input
                type="date"
                class="form-control form-control-sm"
                name="evaluasi[${index}][hari_tgl]"
                value="${data.hari_tgl ?? ''}"
                ${isReadonly ? 'readonly' : ''}
            >
        </td>
        <td>
            <input
                type="time"
                class="form-control form-control-sm"
                name="evaluasi[${index}][jam]"
                value="${data.jam ?? ''}"
                ${isReadonly ? 'readonly' : ''}
            >
        </td>
        <td>
            <div class="mb-1 d-flex align-items-start gap-2">
                <label class="form-label form-label-sm fw-bold mb-0" style="width:20px;">S</label>
                <textarea
                    class="form-control form-control-sm"
                    name="evaluasi[${index}][evaluasi_s]"
                    rows="2"
                    style="resize:none; overflow:hidden;"
                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                    ${isReadonly ? 'readonly' : ''}
                >${data.evaluasi_s ?? ''}</textarea>
            </div>

            <div class="mb-1 d-flex align-items-start gap-2">
                <label class="form-label form-label-sm fw-bold mb-0" style="width:20px;">O</label>
                <textarea
                    class="form-control form-control-sm"
                    name="evaluasi[${index}][evaluasi_o]"
                    rows="2"
                    style="resize:none; overflow:hidden;"
                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                    ${isReadonly ? 'readonly' : ''}
                >${data.evaluasi_o ?? ''}</textarea>
            </div>

            <div class="mb-1 d-flex align-items-start gap-2">
                <label class="form-label form-label-sm fw-bold mb-0" style="width:20px;">A</label>
                <textarea
                    class="form-control form-control-sm"
                    name="evaluasi[${index}][evaluasi_a]"
                    rows="2"
                    style="resize:none; overflow:hidden;"
                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                    ${isReadonly ? 'readonly' : ''}
                >${data.evaluasi_a ?? ''}</textarea>
            </div>

            <div class="d-flex align-items-start gap-2">
                <label class="form-label form-label-sm fw-bold mb-0" style="width:20px;">P</label>
                <textarea
                    class="form-control form-control-sm"
                    name="evaluasi[${index}][evaluasi_p]"
                    rows="2"
                    style="resize:none; overflow:hidden;"
                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                    ${isReadonly ? 'readonly' : ''}
                >${data.evaluasi_p ?? ''}</textarea>
            </div>
        </td>
        <td class="text-center align-middle">
            ${!isReadonly ? `<button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)">x</button>` : ''}
        </td>
    `;
    tbody.appendChild(row);
    window[rowCountVar]++;
}