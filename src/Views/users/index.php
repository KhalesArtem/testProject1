<?php $title = 'Управление пользователями'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Управление пользователями</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .error { color: red; }
        .success { color: green; }
        form { margin-bottom: 20px; }
        input, select { margin: 5px 0; }
    </style>
</head>
<body>
    <div class="alert alert-dismissible fade show" role="alert">
        <span id="messageText"></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Добавить/редактировать пользователя</h5>
        </div>
        <div class="card-body">
            <form id="userForm" class="row g-3">
                <input type="hidden" name="id" id="userId">
                
                <div class="col-md-4">
                    <label for="firstName" class="form-label">Имя</label>
                    <input type="text" class="form-control" id="firstName" name="first_name" required>
                </div>
                
                <div class="col-md-4">
                    <label for="lastName" class="form-label">Фамилия</label>
                    <input type="text" class="form-control" id="lastName" name="last_name" required>
                </div>
                
                <div class="col-md-4">
                    <label for="position" class="form-label">Должность</label>
                    <select class="form-select" id="position" name="position" required>
                        <option value="">Выберите должность</option>
                        <option value="программист">Программист</option>
                        <option value="менеджер">Менеджер</option>
                        <option value="тестировщик">Тестировщик</option>
                    </select>
                </div>
                
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                    <button type="button" id="cancelEdit" class="btn btn-secondary" style="display:none">Отменить</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Список пользователей</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="usersTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Имя</th>
                            <th>Фамилия</th>
                            <th>Должность</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        let isEditing = false;

        function showMessage(text, isError = false) {
            const alert = $('.alert');
            $('#messageText').text(text);
            alert.removeClass('alert-success alert-danger')
                 .addClass(isError ? 'alert-danger' : 'alert-success')
                 .show();
            setTimeout(() => alert.hide(), 3000);
        }

        function loadUsers() {
            $.get('/api/users', function(users) {
                const tbody = $('#usersTable tbody');
                tbody.empty();
                users.forEach(user => {
                    tbody.append(`
                        <tr data-id="${user.id}">
                            <td>${user.first_name}</td>
                            <td>${user.last_name}</td>
                            <td>${user.position}</td>
                            <td>
                                <button class="btn btn-sm btn-primary" onclick="editUser(${user.id})">
                                    Редактировать
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="deleteUser(${user.id})">
                                    Удалить
                                </button>
                            </td>
                        </tr>
                    `);
                });
            });
        }

        function editUser(id) {
            const row = $(`tr[data-id="${id}"]`);
            $('#userId').val(id);
            $('#firstName').val(row.find('td:eq(0)').text());
            $('#lastName').val(row.find('td:eq(1)').text());
            $('#position').val(row.find('td:eq(2)').text());
            $('#cancelEdit').show();
            isEditing = true;
        }

        function deleteUser(id) {
            if (confirm('Вы уверены?')) {
                $.ajax({
                    url: `/api/users/${id}`,
                    method: 'DELETE',
                    success: function() {
                        showMessage('Пользователь удален');
                        loadUsers();
                    },
                    error: function() {
                        showMessage('Ошибка при удалении', true);
                    }
                });
            }
        }

        $('#userForm').on('submit', function(e) {
            e.preventDefault();
            const data = {
                first_name: $('#firstName').val(),
                last_name: $('#lastName').val(),
                position: $('#position').val()
            };

            const id = $('#userId').val();
            const method = isEditing ? 'PUT' : 'POST';
            const url = isEditing ? `/api/users/${id}` : '/api/users';

            $.ajax({
                url: url,
                method: method,
                data: JSON.stringify(data),
                contentType: 'application/json',
                success: function() {
                    showMessage(isEditing ? 'Пользователь обновлен' : 'Пользователь создан');
                    loadUsers();
                    $('#userForm')[0].reset();
                    $('#userId').val('');
                    $('#cancelEdit').hide();
                    isEditing = false;
                },
                error: function() {
                    showMessage('Ошибка при сохранении', true);
                }
            });
        });

        $('#cancelEdit').on('click', function() {
            $('#userForm')[0].reset();
            $('#userId').val('');
            $(this).hide();
            isEditing = false;
        });

        loadUsers();
    </script>
</body>
</html>
