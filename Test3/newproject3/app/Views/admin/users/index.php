<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="/admin/quiz/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Créer un quiz
        </a>
    </div>

    <?php if (session()->getFlashdata('message')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= session()->getFlashdata('message') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="container mt-4">
        <div class="admin-users-table-wrapper">
            <table class="table admin-users-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom d'utilisateur</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($users) && is_array($users) && count($users) > 0): ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= esc($user['id']) ?></td>
                                <td><?= esc($user['username']) ?></td>
                                <td><?= esc($user['email']) ?></td>
                                <td>
                                    <span class="badge <?= $user['role'] === 'admin' ? 'bg-danger' : 'bg-secondary' ?>">
                                        <?= esc($user['role']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <?php if ($user['role'] === 'user'): ?>
                                            <a href="<?= base_url('admin/users/set-admin/' . $user['id']) ?>" 
                                               class="btn btn-sm btn-success" 
                                               title="Promouvoir administrateur"
                                               onclick="return confirm('Promouvoir <?= esc($user['username']) ?> en administrateur ?')">
                                                <i class="fas fa-user-shield"></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="<?= base_url('admin/users/remove-admin/' . $user['id']) ?>" 
                                               class="btn btn-sm btn-warning" 
                                               title="Rétrograder en utilisateur"
                                               onclick="return confirm('Rétrograder <?= esc($user['username']) ?> en utilisateur ?')">
                                                <i class="fas fa-user"></i>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php if ($user['id'] != session()->get('user_id')): ?>
                                            <a href="<?= base_url('admin/users/delete/' . $user['id']) ?>" 
                                               class="btn btn-sm btn-danger" 
                                               title="Supprimer l'utilisateur"
                                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer l\'utilisateur <?= esc($user['username']) ?> ? Cette action est irréversible.')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        <?php else: ?>
                                            <span class="btn btn-sm btn-secondary disabled" title="Vous ne pouvez pas vous supprimer">
                                                <i class="fas fa-user"></i>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">Aucun utilisateur trouvé.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

