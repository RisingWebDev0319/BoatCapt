<?php $__env->startSection('title', 'Owner Reviews'); ?>
<?php $__env->startSection('header'); ?>
	<page-header v-bind:param="<?php echo e($param); ?>"></page-header>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <owner-reviews v-bind:user-info="<?php echo e($userInfo); ?>"></owner-reviews>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('templates.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>