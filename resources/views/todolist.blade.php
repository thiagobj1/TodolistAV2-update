<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Todo list</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>

<body class="bg-img body-class" onload="initialyze()">

    <div class="container">
        <div class="center-screen">
            <div class="row">
                <div class="col-4 ">
                    <form>
                        <div class="form-group">
                            <label for="taskInput" class="h5">Todo-list</label>
                            <input type="text" class="form-control" id="taskInput" aria-describedby="emailHelp">
                        </div>

                    </form>
                    <button class="btn btn-primary" onclick="saveTasks()">Adicionar Tarefa</button>
                </div>
                <div class="col-7 offset-1">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Suas Tarefas</th>
                            </tr>
                        </thead>
                            <tbody>

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal fade" id="editionModal" tabindex="-1" role="dialog" aria-labelledby="editionModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title" id="editionModalLabel">Atualize a Tarefa</h6>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="form-group">
                                    <input type="hidden" id="task-id">
                                    <label for="task-description" class="col-form-label">Descrição da Tarefa</label>
                                    <input type="text" class="form-control" id="task-description">
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                            <button type="button" class="btn btn-primary" onclick="edit()">Salvar</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

<style>

.bg-img {
    position: flex;
    width: 100%;
    height: 100%;
    background-size: cover;
    background-image: linear-gradient(to bottom right, #2b2a2a, #3411d3);
}

.body-class {
    height: 100vh;
    z-index: 2;
}

.center-screen {
    display: flex;
    justify-content: center;
    align-items: left;
    min-height: 100vh;
}

.centered-element {
    justify-content: center;
    align-items: center;
    display: flex;
}

.col-4{
    background-color: #D3D3D3;
    outline: none;
    padding: 30px;
    border-radius: 20px;

}
.col-7 {
    background-color: #D3D3D3;
    outline: none;
    padding: 30px;
    border-radius: 20px;
}

button {
    width: 100%;
    padding: 20px;
}

.form-group,
img {
    margin-bottom: 30px;
}

.row {
    width: 100%;
}

.modal-footer{
    background-color: #d3d3d3;
}

.modal-body{
    background-color: #d3d3d3;
}

.modal-header{
    background-color: #d3d3d3;
}

.form-group{
    display: row;
    width: 100%;
}


</style>

    <script type="text/javascript">
        function initialyze() {
            getTasks();
        }

        function getTasks() {
            $.ajax({
                type: "GET",
                url: "/tasks",
                success: function (data) {
                    console.log(data);
                    if (data.length > 0) {
                        const table = document.getElementsByTagName('tbody')[0];
                        table.innerHTML = "";
                        for (var i = 0; i < data.length; i++) {
                            try {
                                const row = table.insertRow(i);
                                const cell1 = row.insertCell(0);
                                const cell2 = row.insertCell(1);
                                const cell3 = row.insertCell(2);
                                const cell4 = row.insertCell(3);
                                cell1.innerHTML = data[i].id;
                                cell2.innerHTML = data[i].description;
                                cell3.innerHTML = `<button class="btn btn-primary" onclick="openEditModal(${data[i].id},'${data[i].description}')"><i class="fa fa-edit"></i></button>`;
                                cell4.innerHTML = '<button class="btn btn-danger" onclick="deleteTask(' + data[i].id + ')"><i class="fa fa-trash"></i></button>';
                            } catch (error) {
                                console.log(error);
                            }

                        }
                    } else {
                        var row = table.insertRow(0);
                        var cell = row.insertCell(0);
                        cell.innerHTML = 'No tasks';
                    }
                },
                error: function (error) {
                    alert(`Error ${error}`);
                }
            })
        }

        function saveTasks() {
            const task = document.getElementById('taskInput').value;
            $.ajax({
                type: "POST",
                url: "/tasks",
                data: {
                    description: task
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    console.log(data);
                    getTasks();
                },
                error: function (error) {
                    alert(`Error ${error}`);
                }
            })
        }

        function deleteTask(id) {
            $.ajax({
                type: "DELETE",
                url: `/tasks/${id}`,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    console.log(data);
                    getTasks();
                },
                error: function (error) {
                    alert(`Error ${error}`);
                }
            })
        }

        function openEditModal(id, description) {
            $('#editionModal').modal('show');
            $('#task-id').val(id);
            $('#task-description').val(description);
        }

        function edit() {
            var id = $('#task-id').val();
            var description = $('#task-description').val();
            $.ajax({
                type: "PUT",
                url: `/tasks/${id}`,
                data: {
                    description: description
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    console.log(data);
                    getTasks();
                },
                error: function (error) {
                    alert(`Error ${error}`);
                }
            })
        }
    </script>

</body>

</html>