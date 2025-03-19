document.addEventListener("DOMContentLoaded", function () {
    fetch("/PHP2/api/get_all_data.php")
        .then(response => response.json())
        .then(data => {
            let eventTableBody = document.getElementById("eventTableBody");
            eventTableBody.innerHTML = "";

            // Remplir la table avec les événements
            data.events.forEach(event => {
                let row = document.createElement("tr");
                row.innerHTML = `
                    <td>${event.nom_evenement}</td>
                    <td>${event.description_evenement}</td>
                    <td>${event.nom_organisateur}</td>
                    <td>${event.place_evenement}</td>
                    <td>${event.place_restantes}</td>
                    <td>${event.type_evenement}</td>
                    <td>${event.nom_lieu}</td>
                    <td>${event.date_evenement}</td>
                    <td>
                        <button class="btn btn-primary edit-btn" data-bs-toggle="modal" data-bs-target="#editEventModal"
                            data-id="${event.id_evenement}"
                            data-nom="${event.nom_evenement}"
                            data-description="${event.description_evenement}"
                            data-organisateur="${event.id_organisateur}"
                            data-lieu="${event.id_lieu}"
                            data-date="${event.date_evenement}">
                            Modifier
                        </button>
                    </td>
                `;
                eventTableBody.appendChild(row);
            });

            // Remplir les listes déroulantes
            let selectOrganisateur = document.getElementById("edit_organisateur");
            data.organisateurs.forEach(org => {
                selectOrganisateur.innerHTML += `<option value="${org.id}">${org.nom_organisateur}</option>`;
            });

            let selectLieu = document.getElementById("edit_lieu");
            data.lieux.forEach(lieu => {
                selectLieu.innerHTML += `<option value="${lieu.id}">${lieu.nom_lieu}</option>`;
            });
        });
});
