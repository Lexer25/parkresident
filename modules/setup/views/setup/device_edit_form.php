<!-- views/setup/device_edit_form.php -->
<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title"><?php echo __('Редактирование устройства'); ?></h3>
    </div>
    <div class="panel-body">
        <?php echo Form::open('wizard/saveDevice'); ?>
        <div class="form-group">
            <label><?php echo __('Сетевой адрес'); ?></label>
            <?php echo Form::input('NETADDR', htmlspecialchars($device['NETADDR']), array('class' => 'form-control')); ?>
        </div>
        <div class="form-group">
            <label><?php echo __('Название'); ?></label>
            <?php echo Form::input('NAME', htmlspecialchars($device['NAME']), array('class' => 'form-control')); ?>
        </div>
        <div class="form-group">
            <label><?php echo __('Интервал'); ?></label>
            <?php echo Form::input('INTERVAL', htmlspecialchars($device['INTERVAL']), array('class' => 'form-control')); ?>
        </div>
        <div class="form-group">
            <label><?php echo __('DSS1'); ?></label>
            <?php echo Form::input('DSS1', htmlspecialchars($device['DSS1']), array('class' => 'form-control')); ?>
        </div>
        <div class="form-group">
            <label><?php echo __('DSS2'); ?></label>
            <?php echo Form::input('DSS2', htmlspecialchars($device['DSS2']), array('class' => 'form-control')); ?>
        </div>
        <div class="form-group">
            <label><?php echo __('Флаг'); ?></label>
            <?php echo Form::input('FLAG', htmlspecialchars($device['FLAG']), array('class' => 'form-control')); ?>
        </div>
        <div class="form-group">
            <label><?php echo __('ID плана'); ?></label>
            <?php echo Form::input('ID_PLAN', htmlspecialchars($device['ID_PLAN']), array('class' => 'form-control')); ?>
        </div>
        <div class="form-group">
            <label><?php echo __('POS_X'); ?></label>
            <?php echo Form::input('POS_X', htmlspecialchars($device['POS_X']), array('class' => 'form-control')); ?>
        </div>
        <div class="form-group">
            <label><?php echo __('POS_Y'); ?></label>
            <?php echo Form::input('POS_Y', htmlspecialchars($device['POS_Y']), array('class' => 'form-control')); ?>
        </div>
        <div class="form-group">
            <label><?php echo __('Пароль'); ?></label>
            <?php echo Form::input('PSW', htmlspecialchars($device['PSW']), array('class' => 'form-control')); ?>
        </div>
        <div class="form-group">
            <label><?php echo __('Активность'); ?></label>
            <?php echo Form::select('ACTIVE', array('0' => 'Нет', '1' => 'Да'), $device['ACTIVE'], array('class' => 'form-control')); ?>
        </div>
        <div class="form-group">
            <label><?php echo __('Конфигурация'); ?></label>
            <?php echo Form::input('CONFIG', htmlspecialchars($device['CONFIG']), array('class' => 'form-control')); ?>
        </div>
        <div class="form-group">
            <label><?php echo __('Параметры'); ?></label>
            <?php echo Form::input('PARAM', htmlspecialchars($device['PARAM']), array('class' => 'form-control')); ?>
        </div>
        <div class="form-group">
            <label><?php echo __('Тег'); ?></label>
            <?php echo Form::input('TAGNAME', htmlspecialchars($device['TAGNAME']), array('class' => 'form-control')); ?>
        </div>
        <div class="form-group">
            <?php echo Form::hidden('id_dev', $device['ID_DEV']); ?>
            <?php echo Form::button('saveDevice', 'Сохранить', array('type' => 'submit')); ?>
        </div>
        <?php echo Form::close(); ?>
    </div>
</div>