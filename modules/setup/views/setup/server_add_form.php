<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title"><?php echo __('Добавление транспортного сервера'); ?></h3>
    </div>
    <div class="panel-body">
        <?php echo Form::open('wizard/saveServer'); ?>
        <?php if (isset($server)) { echo Form::hidden('id_server', $server['ID_SERVER']); } ?>
        <div class="form-group">
            <?php echo Form::label('name', __('Имя сервера')); ?>
            <?php echo Form::input('name', isset($server) ? iconv('windows-1251', 'UTF-8', $server['NAME']) : '', array('class' => 'form-control', 'required' => 'required')); ?>
        </div>
        <div class="form-group">
            <?php echo Form::label('ip', __('IP')); ?>
            <?php echo Form::input('ip', isset($server) ? long2ip($server['IP']) : '', array('class' => 'form-control', 'required' => 'required')); ?>
        </div>
        <div class="form-group">
            <?php echo Form::label('port', __('Порт')); ?>
            <?php echo Form::input('port', isset($server) ? $server['PORT'] : '', array('class' => 'form-control', 'required' => 'required')); ?>
        </div>
        <div class="form-group">
            <?php echo Form::label('active', __('Активность')); ?>
            <?php echo Form::select('active', array(0 => 'Нет', 1 => 'Да'), isset($server) ? $server['ACTIVE'] : 1, array('class' => 'form-control')); ?>
        </div>
        <div class="form-group">
            <?php echo Form::button('submit', __('Сохранить'), array('value' => 'save', 'class' => 'btn btn-default btn-sm')); ?>
        </div>
        <?php echo Form::close(); ?>
    </div>
</div>