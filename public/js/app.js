document.addEventListener('DOMContentLoaded', () => {
    const registroForm = document.getElementById('registroForm');
    const personasDiv = document.getElementById('personas');

    const fetchPersonas = () => {
        fetch('http://localhost/sitephp/api.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                personasDiv.innerHTML = '';
                data.personas.forEach(persona => {
                    const personaDiv = document.createElement('div');
                    personaDiv.className = 'persona';
                    personaDiv.innerHTML = `
                        <h3>${persona.nombre} ${persona.apellido}</h3>
                        <p>Edad: ${persona.edad}</p>
                        <p>Sexo: ${persona.sexo}</p>
                        <div class="buttons">
                            <button class="edit" data-id="${persona.id}">Editar</button>
                            <button class="delete" data-id="${persona.id}">Eliminar</button>
                        </div>
                    `;
                    personasDiv.appendChild(personaDiv);
                });

                document.querySelectorAll('.edit').forEach(button => {
                    button.addEventListener('click', handleEdit);
                });

                document.querySelectorAll('.delete').forEach(button => {
                    button.addEventListener('click', handleDelete);
                });
            })
            .catch(error => {
                console.error('Error:', error);
                alert(`Error: ${error.message}`);
            });
    };

    const handleEdit = (event) => {
        const id = event.target.dataset.id;
        const personaDiv = event.target.closest('.persona');
        const nombre = prompt('Nuevo nombre:', personaDiv.querySelector('h3').textContent.split(' ')[0]);
        const apellido = prompt('Nuevo apellido:', personaDiv.querySelector('h3').textContent.split(' ')[1]);
        const edad = prompt('Nueva edad:', personaDiv.querySelector('p').textContent.split(': ')[1]);
        const sexo = prompt('Nuevo sexo:', personaDiv.querySelectorAll('p')[1].textContent.split(': ')[1]);

        const data = { nombre, apellido, edad, sexo };

        fetch(`http://localhost/sitephp/api.php/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                throw new Error(data.error);
            }
            alert(data.message || 'Persona actualizada exitosamente');
            fetchPersonas();
        })
        .catch(error => {
            console.error('Error:', error);
            alert(`Error: ${error.message}`);
        });
    };

    const handleDelete = (event) => {
        const id = event.target.dataset.id;

        fetch(`http://localhost/sitephp/api.php/${id}`, {
            method: 'DELETE'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                throw new Error(data.error);
            }
            alert(data.message || 'Persona eliminada exitosamente');
            fetchPersonas();
        })
        .catch(error => {
            console.error('Error:', error);
            alert(`Error: ${error.message}`);
        });
    };

    registroForm.addEventListener('submit', (event) => {
        event.preventDefault();

        const nombre = document.getElementById('nombre').value;
        const apellido = document.getElementById('apellido').value;
        const edad = document.getElementById('edad').value;
        const sexo = document.getElementById('sexo').value;

        const data = { nombre, apellido, edad, sexo };

        fetch('http://localhost/sitephp/api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                throw new Error(data.error);
            }
            alert(data.message || 'Persona registrada exitosamente');
            registroForm.reset();
            fetchPersonas();
        })
        .catch(error => {
            console.error('Error:', error);
            alert(`Error: ${error.message}`);
        });
    });

    fetchPersonas();
});

