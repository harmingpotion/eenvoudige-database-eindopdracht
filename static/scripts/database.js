const view = document.getElementById("edit_user");
const fn = document.getElementById("firstname");
const old_fn = document.getElementById("firstname_old");
const old_fn_2 = document.getElementById("firstname_old_2");
const ln = document.getElementById("lastname");
const old_ln = document.getElementById("lastname_old");
const old_ln_2 = document.getElementById("lastname_old_2");
const em = document.getElementById("email");
const old_em = document.getElementById("email_old");
const old_em_2 = document.getElementById("email_old_2");
const pv = document.getElementById("province");
const old_pv = document.getElementById("province_old");
const old_pv_2 = document.getElementById("province_old_2");

const searchInput = document.getElementById("search_input");
const searchType = document.getElementById("search_type");

function openEdit(row) {
    view.style.display = 'block';

    fn.value = document.querySelector("#row_"+row+" > .firstname").innerText;
    old_fn.value = document.querySelector("#row_"+row+" > .firstname").innerText;
    old_fn_2.value = document.querySelector("#row_"+row+" > .firstname").innerText;
    ln.value = document.querySelector("#row_"+row+" > .lastname").innerText;
    old_ln.value = document.querySelector("#row_"+row+" > .lastname").innerText;
    old_ln_2.value = document.querySelector("#row_"+row+" > .lastname").innerText;
    em.value = document.querySelector("#row_"+row+" > .email").innerText;
    old_em.value = document.querySelector("#row_"+row+" > .email").innerText;
    old_em_2.value = document.querySelector("#row_"+row+" > .email").innerText;
    pv.value = document.querySelector("#row_"+row+" > .province").innerText;
    old_pv.value = document.querySelector("#row_"+row+" > .province").innerText;
    old_pv_2.value = document.querySelector("#row_"+row+" > .province").innerText;
}

function closeEdit() {
    view.style.display = 'none';

    fn.value = '';
    old_fn.value = '';
    old_fn_2.value = '';
    ln.value = '';
    old_ln.value = '';
    old_ln_2.value = '';
    em.value = '';
    old_em.value = '';
    old_em_2.value = '';
    pv.value = '';
    old_pv.value = '';
    old_pv_2.value = '';
}

function filterTable() {
    const searchTerm = searchInput.value.toLowerCase();
    const searchField = searchType.value;
    const rows = document.querySelectorAll("table tr");

    rows.forEach(row => {
        // Skip header rows and form rows
        if (row.querySelector("th") || row.querySelector("input[name='firstname']")) {
            return;
        }

        const firstNameCell = row.querySelector(".firstname");
        const lastNameCell = row.querySelector(".lastname");
        const emailCell = row.querySelector(".email");
        const provinceCell = row.querySelector(".province");

        if (!firstNameCell || !lastNameCell || !emailCell || !provinceCell) {
            return;
        }

        const firstName = firstNameCell.innerText.toLowerCase();
        const lastName = lastNameCell.innerText.toLowerCase();
        const email = emailCell.innerText.toLowerCase();
        const province = provinceCell.innerText.toLowerCase();

        let matches = false;

        if (searchField === "all") {
            matches = firstName.includes(searchTerm) || 
                      lastName.includes(searchTerm) || 
                      email.includes(searchTerm) || 
                      province.includes(searchTerm);
        } else if (searchField === "firstname") {
            matches = firstName.includes(searchTerm);
        } else if (searchField === "lastname") {
            matches = lastName.includes(searchTerm);
        } else if (searchField === "email") {
            matches = email.includes(searchTerm);
        } else if (searchField === "province") {
            matches = province.includes(searchTerm);
        }

        row.style.display = matches ? "" : "none";
    });
}

searchInput.addEventListener("input", filterTable);
searchType.addEventListener("change", filterTable);