                </div> <!-- .content-body -->
            </div> <!-- .content-wrapper -->
        </div> <!-- .admin-content -->
    </div> <!-- .admin-container -->
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    
    <script>
    $(document).ready(function() {
        // Toggle sidebar on mobile
        $('.sidebar-toggle').on('click', function() {
            $('.admin-sidebar').toggleClass('collapsed');
            $('.admin-content').toggleClass('expanded');
        });
        
        // Automatically hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
        
        <?php if (isset($liveSession) && $liveSession): ?>
        // Live quiz specific scripts
        initLiveQuiz();
        <?php endif; ?>
    });
    
    function initLiveQuiz() {
        // Refresh participants list every 5 seconds
        setInterval(function() {
            if ($('#participants-list').length) {
                refreshParticipants();
            }
        }, 5000);
        
        // Handle next question button click
        $('#next-question-btn').on('click', function() {
            nextQuestion();
        });
        
        // Handle score update
        $(document).on('click', '.update-score-btn', function() {
            const userId = $(this).data('user-id');
            const quizId = $(this).data('quiz-id');
            const currentScore = parseInt($('#score-' + userId).text());
            
            // Show modal with score input
            $('#update-score-modal').modal('show');
            $('#user-id-input').val(userId);
            $('#quiz-id-input').val(quizId);
            $('#score-input').val(currentScore);
        });
        
        // Submit score update form
        $('#update-score-form').on('submit', function(e) {
            e.preventDefault();
            
            const userId = $('#user-id-input').val();
            const quizId = $('#quiz-id-input').val();
            const newScore = $('#score-input').val();
            
            $.ajax({
                url: '<?= site_url('admin/live/update-score') ?>',
                type: 'POST',
                data: {
                    user_id: userId,
                    quiz_id: quizId,
                    score: newScore
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#update-score-modal').modal('hide');
                        refreshParticipants();
                    } else {
                        alert('Erreur: ' + response.message);
                    }
                },
                error: function() {
                    alert('Erreur de communication avec le serveur');
                }
            });
        });
    }
    
    function refreshParticipants() {
        const quizId = $('#quiz-id').val();
        
        $.ajax({
            url: '<?= site_url('admin/live/participants') ?>',
            type: 'GET',
            data: { quiz_id: quizId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    let html = '';
                    
                    if (response.participants.length === 0) {
                        html = '<tr><td colspan="4" class="text-center">Aucun participant pour le moment</td></tr>';
                    } else {
                        $.each(response.participants, function(index, participant) {
                            html += `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${participant.username}</td>
                                    <td id="score-${participant.user_id}">${participant.score}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary update-score-btn" 
                                                data-user-id="${participant.user_id}" 
                                                data-quiz-id="${participant.quiz_id}">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });
                    }
                    
                    $('#participants-list').html(html);
                    $('#participants-count').text(response.count);
                }
            }
        });
    }
    
    function nextQuestion() {
        const quizId = $('#quiz-id').val();
        const currentQuestion = parseInt($('#current-question').val());
        
        $.ajax({
            url: '<?= site_url('admin/live/next-question') ?>',
            type: 'POST',
            data: {
                quiz_id: quizId,
                current_question: currentQuestion
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    if (response.finished) {
                        alert('Quiz terminé !');
                        window.location.href = '<?= site_url('admin/quizzes') ?>';
                    } else {
                        $('#current-question').val(response.current_question);
                        $('#question-progress').text(`Question ${response.current_question} sur ${response.total_questions}`);
                        
                        // Update progress bar
                        const progressPercentage = (response.current_question / response.total_questions) * 100;
                        $('#question-progress-bar').css('width', progressPercentage + '%');
                    }
                } else {
                    alert('Erreur: ' + response.message);
                }
            },
            error: function() {
                alert('Erreur de communication avec le serveur');
            }
        });
    }
    </script>
    
    <?php if (isset($extraScripts)): ?>
    <?= $extraScripts ?>
    <?php endif; ?>

    <script src="<?= base_url('js/admin.js') ?>"></script>
</body>
</html> 