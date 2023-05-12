window.onload = init;

 function init() {
    document.getElementById("showForm").addEventListener("click", (e) => {
        document.getElementById("tallerModal").style.display = "block";
    });
    let editButtons = $("button[name=editarButton]");
    for (let i = 0; i < editButtons.length; i++) {
        editButtons[i].addEventListener("click", (e) => {
            let codi = $(editButtons[i]).attr("data-bs-id");
            editarTaller(codi);
        });
    }

    $('button[name="afegirParticipant"]').on("click", function (e) {
        $('#formAfegirParticipant').attr('action', '/afegirParticipant/' + $(this).attr("data-bs-id"));
        $("#afegirParticipantModal").modal("show");
    });
    $('button[name="eliminarParticipants"]').on("click", function (e) {
        getParticipants($(this).attr("data-bs-id"));
        $('#formeliminarParticipant').attr('action', '/eliminarParticipants/' + $(this).attr("data-bs-id"));
        limpiarErrors();
        $("#eliminarParticipantModal").modal("show");
    });
}

async function editarTaller(codi) {
    try {
        limpiarErrors();
        $("#editarTallerForm").trigger("reset");

        if (codi) {
            let data = await fetch("/dadesTaller", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-Token":
                        document.querySelector("input[name=_token]").value,
                },
                body: JSON.stringify({
                    codi: codi,
                }),
            });

            let dataResponse = await data.json();

            if (dataResponse.success) {
                document.getElementById("editarnomTaller").value =
                    dataResponse.taller.nom;
                document.getElementById("editardescripcio").value =
                    dataResponse.taller.descripcio;
                document.getElementById("editarmaterials").value =
                    dataResponse.taller.materials;
                document.getElementById("editarid").value = dataResponse.codi;

                if (document.getElementById("editarajudants") !== null) {
                    document.getElementById("editarajudants").value =
                        dataResponse.taller.ajudants;
                }
                if (document.getElementById("editarespai") !== null) {
                    document.getElementById("editarespai").value =
                        dataResponse.taller.espai;
                }
                if (document.getElementById("editarplaces") !== null) {
                    document.getElementById("editarplaces").value =
                        dataResponse.taller.nalumnes;
                }

                let adrecat = dataResponse.taller.adrecat.split(",");

                //replace all spaces with empty string and remove last comma
                adrecat = adrecat
                    .map((item) => item.replace(/\s/g, ""))
                    .filter((item) => item !== "");

                for (let i = 0; i < adrecat.length; i++) {
                    adrecat[i] = "edit" + adrecat[i];
                    console.log(adrecat[i]);
                    document.getElementById(adrecat[i]).checked = true;
                }

                //edit form action url with the id of the selected workshop
                document.getElementById("editarTallerForm").action =
                    "/editarTaller";
                document.getElementById("editarTallerForm").action +=
                    "/" + dataResponse.codi;

                $("#editModal").modal("show");
            } else if (dataResponse.error) {
                console.log(dataResponse.error);
                mostrarError(dataResponse.error);
            } else {
                console.log(dataResponse);
            }
        } else {
            mostrarError("No s'ha pogut editar el taller");
        }
    } catch (error) {
        mostrarError("No s'ha pogut editar el taller");
        console.log(error);
    }
}

function mostrarError(error, div = "divAlert") {
    const divAlert = document.getElementById(div);
    divAlert.innerHTML = "";
    const divError = document.createElement("div");
    divError.classList.add(
        "alert",
        "alert-danger",
        "alert-dismissible",
        "fade",
        "show"
    );
    divError.setAttribute("role", "alert");
    const button =
        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
    divError.innerHTML = error + button;

    divAlert.appendChild(divError);
}

function limpiarErrors() {
    const divs = document.getElementsByClassName("alert");
    for (let i = 0; i < divs.length; i++) {
        divs[i].remove();
    }
}

async function getParticipants(codi){
    divLista = $("#listaParticipants");
    divLista.html("");
    let resposta =  await fetch("/participantsTaller/" + codi, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-Token":
                document.querySelector("input[name=_token]").value,
        },
    });

    let data = await resposta.json();

    if(data.status == "success"){
        divLista.html("");
        let participants = data.participants;
        
        console.log(data.participants);
        for(let i = 0; i < participants.length; i++){
            // show participants in a list with a <label> tag and next to it a checkbox to select the participant to delete
            divLista.append("<input class='form-check-input ms-1 me-2'  type='checkbox' name='participants[]' value='" + participants[i] + "' />");
            divLista.append("<label class='form-check-label'>" + participants[i] + "</label><br>");


        }
        divLista.append("</ul>");

    }else if(data.status == "error"){
        mostrarError(data.error, "divAlertEliminarParticipants");
    }else{
        mostrarError("No s'ha pogut obtenir la llista de participants", "divAlertEliminarParticipants");
    }

}


