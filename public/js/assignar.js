window.onload = init;

function init() {
    checkboxes = $('input[name=admin]');
    for (let i = 0; i < checkboxes.length; i++) {
        $(checkboxes[i]).on('change', (e) => {
            canviarAdmin(e.target.id);
        });
    }
}

async function canviarAdmin(email) {
    try {

        if (email === undefined) {
            console.log(email);
            throw new Error('No s\'ha trobat l\'email');
        }

        data = await fetch('/assignar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                "X-CSRF-Token": document.querySelector('input[name=_token]').value
            },
            body: JSON.stringify({
                email: email,
            }),
        });

        let dataResponse = await data.json();

        if (dataResponse.success) {
            mostrarSuccess(dataResponse.success);
            let td = document.querySelector(`td[name="${CSS.escape(email)}"]`);
            if (td) {
                let value = td.innerHTML;
                td.innerHTML = value === 'NO' ? 'SI' : 'NO';
            }

        } else if (dataResponse.error) {
            mostrarError(dataResponse.error);
        }


    } catch (err) {
        mostrarError(err.message);
    }

}


function mostrarError(error) {
    const divAlert = document.getElementById('divAlert');
    divAlert.innerHTML = '';
    const divError = document.createElement('div');
    divError.classList.add('alert', 'alert-danger', 'alert-dismissible', 'fade', 'show');
    divError.setAttribute('role', 'alert');
    const button = '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
    divError.innerHTML = error + button;

    divAlert.appendChild(divError);

}

function mostrarSuccess(success) {
    const divAlert = document.getElementById('divAlert');
    divAlert.innerHTML = '';
    const divSuccess = document.createElement('div');
    divSuccess.classList.add('alert', 'alert-success', 'alert-dismissible', 'fade', 'show');
    divSuccess.setAttribute('role', 'alert');
    const button = '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
    divSuccess.innerHTML = success + button;

    divAlert.appendChild(divSuccess);
}
