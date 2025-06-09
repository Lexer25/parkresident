<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title"><?php echo __('Редактировать сервер'); ?></h3>
    </div>
    <div class="panel-body">
        <?php if (Session::instance()->get('error')): ?>
            <div class="alert alert-danger"><?php echo Session::instance()->get('error'); ?></div>
            <?php Session::instance()->delete('error'); ?>
        <?php endif; ?>
        <?php echo Form::open('wizard/saveServer', array('accept-charset' => 'UTF-8')); ?>
        <?php echo Form::hidden('id_server', $server['ID_SERVER']); ?>
        <div class="form-group">
            <?php echo Form::label('name', __('Имя сервера')); ?>
            <?php echo Form::input('name', $server['NAME'], array('class' => 'form-control', 'required' => 'required')); ?>
        </div>
        <div class="form-group">
            <?php echo Form::label('ip', __('IP')); ?>
            <?php echo Form::input('ip', long2ip($server['IP']), array('class' => 'form-control', 'required' => 'required')); ?>
        </div>
        <div class="form-group">
            <?php echo Form::label('port', __('Порт')); ?>
            <?php echo Form::input('port', $server['PORT'], array('class' => 'form-control')); ?>
        </div>
        <div class="form-group">
            <?php echo Form::label('active', __('Активность')); ?>
            <?php echo Form::select('active', array('1' => 'Да', '0' => 'Нет'), $server['ACTIVE'], array('class' => 'form-control')); ?>
        </div>
        <div class="form-group">
            <?php echo Form::button('submit', __('Сохранить изменения'), array('value' => 'save', 'class' => 'btn btn-default btn-sm')); ?>
        </div>
        <?php echo Form::close(); ?>
    </div>
</div>