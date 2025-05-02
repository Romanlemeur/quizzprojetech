<div class="container">
    <div class="auth-container">
        <div class="auth-card card">
            <div class="auth-header">
                <h1>Login</h1>
                <p>Enter your credentials to access your account</p>
            </div>
            
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('message')): ?>
                <div class="alert alert-success">
                    <?= session()->getFlashdata('message') ?>
                </div>
            <?php endif; ?>
            
            <?= form_open('login') ?>
                <input type="hidden" name="redirect" value="<?= $redirect ?>">
                
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
                    <button type="submit" class="btn btn-primary" style="width: 100%">Login</button>
                </div>
            <?= form_close() ?>
            
            <div class="auth-footer">
                <p>Don't have an account? <a href="<?= base_url('register') ?>">Register here</a></p>
            </div>
        </div>
    </div>
</div>