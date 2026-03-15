<script>

// Сортировка строк
function moveRow(button, direction) {
    var row = $(button).closest('tr');
    if (direction === 'up' && row.prev().length) {
        row.insertBefore(row.prev());
    } else if (direction === 'down' && row.next().length) {
        row.insertAfter(row.next());
    }
    updateGateOrder();
}

function updateGateOrder() {
    var gateIds = [];
    $('#sortableGates tr').each(function() {
        gateIds.push($(this).data('gate-id'));
    });
    $('#gate_order_input').val(gateIds.join(','));
    
    $('#sortableGates tr').each(function(index) {
        $(this).find('.badge').text(index + 1);
    });
}

$(document).ready(function() {
    updateGateOrder();
    
    $('#gateOrderForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#gateSettingsModal').modal('hide');
                    location.reload();
                } else {
                    alert('Ошибка: ' + response.message);
                }
            }
        });
    });
});
</script>

<style>
.sort-controls {
    text-align: center;
    min-width: 80px;
}
.sort-controls .badge {
    display: inline-block;
    margin-bottom: 5px;
}
.sort-controls .btn-group-vertical .btn {
    padding: 2px 8px;
    font-size: 12px;
}
#gateSettingsModal .modal-dialog {
    width: 95%;
    max-width: 1400px;
}
</style>

<div class="panel panel-primary"> 
    <div class="panel-heading">
        <h3 class="panel-title"><?php echo __('Панель управления воротами');?></h3>
    </div>
    <div class="panel-body"> 
        <?php
        $_gateList = Arr::get(Model::factory('gates')->get_list_gate(), 'res');
        
        echo '<span class="label label-warning">Видео отключено</span>';
        echo '<a href="#" class="pull-right" data-toggle="modal" data-target="#gateSettingsModal" title="Настройка">⚙️</a>';
        ?>

        <div class="rmo-cameras-container">
            <table class="table table-bordered">
                <tr>
                    <?php foreach($order_gate as $key2): 
                        $key = array();
                        foreach($_gateList as $key3) {
                            if(Arr::get($key3, 'id') == $key2) $key = $key3;
                        }
                    ?>
                    <td>
                        <div class="rmo-camera-view">
                            <h3><?php echo Arr::get($key, 'name'); ?></h3>
                            no_video
                        </div>
                        <div class="rmo-controls">
                            <button class="btn btn-primary btn-sm">Полный экран</button>
                            <button type="button" class="btn btn-success btn-sm" 
                                    data-toggle="modal" data-target="#reasonModal"
                                    data-gate-id="<?php echo Arr::get($key, 'id'); ?>"
                                    data-gate-name="<?php echo Arr::get($key, 'name'); ?>">
                                Открыть ворота
                            </button>
                        </div>
                    </td>
                    <?php endforeach; ?>
                </tr>
            </table>
        </div>
    </div>
</div>

<!-- Модальное окно причины -->
<div class="modal fade" id="reasonModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Управление воротами</h4>
            </div>
            <div class="modal-body">
                <h4 id="gateNameDisplay"></h4>
                <form action="rmo/sendOpen" method="post" id="gateOpenForm">
                    <div class="form-group">
                        <label>Укажите причину:</label>
                        <input type="text" class="form-control" name="mess" required>
                        <input type="hidden" name="id" id="gateIdForOpen">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                <button type="submit" form="gateOpenForm" class="btn btn-primary">Открыть</button>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно настроек -->
<div class="modal fade" id="gateSettingsModal" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog" style="width:95%; max-width:1400px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Настройка порядка ворот</h4>
            </div>
            <div class="modal-body">
                <?php
                $gatesResult = Model::factory('Gates')->get_list_gate();
                $allGates = isset($gatesResult['res']) ? $gatesResult['res'] : array();
                
                if (!empty($allGates)):
                ?>
                <div class="alert alert-info">
                    Текущий порядок: <?php echo implode(' → ', $order_gate); ?>
                </div>
                
                <form id="gateOrderForm" method="post" action="<?php echo URL::site('rmo/saveGateOrder'); ?>">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Порядок</th>
                                <th>ID</th>
                                <th>Название</th>
                                <th>Парковка</th>
                                <th>Тип</th>
                            </tr>
                        </thead>
                        <tbody id="sortableGates">
                            <?php 
                            $sortedGates = array();
                            foreach ($order_gate as $gateId) {
                                foreach ($allGates as $gate) {
                                    if ($gate['id'] == $gateId) {
                                        $sortedGates[] = $gate;
                                        break;
                                    }
                                }
                            }
                            foreach ($sortedGates as $index => $gate): 
                            ?>
                            <tr data-gate-id="<?php echo $gate['id']; ?>">
                                <td class="sort-controls">
                                    <span class="badge"><?php echo $index + 1; ?></span>
                                    <div class="btn-group-vertical">
                                        <button class="btn btn-xs btn-default move-up" onclick="moveRow(this, 'up')" type="button">↑</button>
                                        <button class="btn btn-xs btn-default move-down" onclick="moveRow(this, 'down')" type="button">↓</button>
                                    </div>
                                </td>
                                <td><?php echo $gate['id']; ?></td>
                                <td><?php echo $gate['name']; ?></td>
                                <td>Парковка <?php echo $gate['id_parking']; ?></td>
                                <td>
                                    <span class="label label-<?php echo ($gate['is_enter'] == 1) ? 'success' : 'info'; ?>">
                                        <?php echo ($gate['is_enter'] == 1) ? 'Въезд' : 'Выезд'; ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    
                    <input type="hidden" name="gate_order" id="gate_order_input" value="<?php echo implode(',', $order_gate); ?>">
                    
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                <button type="submit" form="gateOrderForm" class="btn btn-primary">Сохранить</button>
            </div>
        </div>
    </div>
</div>

<script>
$('#reasonModal').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget);
    $(this).find('#gateNameDisplay').text('Ворота: ' + button.data('gate-name'));
    $(this).find('#gateIdForOpen').val(button.data('gate-id'));
});
</script>
<style>
/* Переопределяем только для наших модальных окон */
#reasonModal.modal,
#gateSettingsModal.modal {
    position: fixed !important;
    top: 0 !important;
    right: 0 !important;
    bottom: 0 !important;
    left: 0 !important;
    z-index: 1050 !important;
    display: none !important;
    overflow: hidden !important;
    background-color: transparent !important;
    border: none !important;
    box-shadow: none !important;
}

#reasonModal.modal.fade.in,
#gateSettingsModal.modal.fade.in {
    display: block !important;
}

#reasonModal .modal-content,
#gateSettingsModal .modal-content {
    background-color: #fff !important;
    border: 1px solid rgba(0,0,0,0.2) !important;
    box-shadow: 0 5px 15px rgba(0,0,0,0.5) !important;
}

#reasonModal .modal-header,
#gateSettingsModal .modal-header {
    background-color: #f5f5f5 !important;
    cursor: default !important;
}
</style>