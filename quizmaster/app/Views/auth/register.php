<div class="container">
    <div class="auth-container">
        <div class="auth-card card">
            <div class="auth-header">
                <h1>Register</h1>
                <p>Create a new account to start taking quizzes</p>
            </div>
            
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>
            
            <?= form_open('register', ['id' => 'register-form']) ?>
                <div class="form-group">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" id="username" name="username" class="form-control" value="<?= old('username') ?>" required>
                    <?php if (isset($validation) && $validation->hasError('username')): ?>
                        <div class="form-error"><?= $validation->getError('username') ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?= old('email') ?>" required>
                    <?php if (isset($validation) && $validation->hasError('email')): ?>
                        <div class="form-error"><?= $validation->getError('email') ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                    <?php if (isset($validation) && $validation->hasError('password')): ?>
                        <div class="form-error"><?= $validation->getError('password') ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                    <?php if (isset($validation) && $validation->hasError('confirm_password')): ?>
                        <div class="form-error"><?= $validation->getError('confirm_password') ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary" style="width: 100%">Register</button>
                </div>
            <?= form_close() ?>
            
            <div class="auth-footer">
                <p>Already have an account? <a href="<?= base_url('login') ?>">Login here</a></p>
            </div>
        </div>
    </div>
</div>