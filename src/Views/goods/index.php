<?php $title = 'Товары с дополнительными полями'; ?>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Товары с дополнительными полями</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Название товара</th>
                        <th>Доп. поле 1</th>
                        <th>Значение 1</th>
                        <th>Доп. поле 2</th>
                        <th>Значение 2</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($goods as $good): ?>
                    <tr>
                        <td><?= htmlspecialchars($good['good_name']) ?></td>
                        <td><?= htmlspecialchars($good['field1_name']) ?></td>
                        <td><?= htmlspecialchars($good['value1']) ?></td>
                        <td><?= htmlspecialchars($good['field2_name']) ?></td>
                        <td><?= htmlspecialchars($good['value2']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
