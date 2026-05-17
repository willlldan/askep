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