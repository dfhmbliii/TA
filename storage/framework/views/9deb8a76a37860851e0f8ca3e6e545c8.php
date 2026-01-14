<?php $__env->startSection('title','Lupa Password'); ?>
<?php $__env->startSection('content'); ?>
<div class="mb-4 text-center">
    <h1 class="h4 fw-bold">Lupa Password</h1>
    <p class="text-muted">Masukkan email Anda, kami akan kirimkan link reset password.</p>
</div>
<?php if(session('status')): ?>
    <div class="alert alert-success">Link reset password telah dikirim ke email Anda.</div>
<?php endif; ?>
<form method="POST" action="<?php echo e(route('password.email')); ?>" class="vstack gap-3">
    <?php echo csrf_field(); ?>
    <div class="form-group">
        <label class="form-label">Email</label>
        <input type="email" name="email" value="<?php echo e(old('email')); ?>" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required autofocus>
        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <button class="btn btn-primary w-100">
        <i class="fas fa-envelope me-2"></i>Kirim Link Reset
    </button>
</form>
<div class="mt-4 text-center">
    <a href="<?php echo e(route('login')); ?>" class="text-decoration-none">Kembali ke Login</a>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\tugas_akhir\resources\views/auth/forgot-password.blade.php ENDPATH**/ ?>