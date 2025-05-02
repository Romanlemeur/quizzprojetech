document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const menuToggle = document.querySelector('.mobile-menu-toggle');
    const mobileMenu = document.querySelector('.mobile-menu');
    
    if (menuToggle && mobileMenu) {
        menuToggle.addEventListener('click', function() {
            mobileMenu.classList.toggle('active');
            menuToggle.classList.toggle('active');
        });
    }
    
    // Quiz functionality
    const quizForm = document.getElementById('quiz-form');
    if (quizForm) {
        handleQuizFunctionality();
    }
    
    // Register form validation
    const registerForm = document.getElementById('register-form');
    if (registerForm) {
        handleRegistrationValidation();
    }
    
    // Login form validation
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        handleLoginValidation();
    }
});

function handleQuizFunctionality() {
    const quizForm = document.getElementById('quiz-form');
    const optionItems = document.querySelectorAll('.option-item input[type="radio"]');
    const nextButton = document.getElementById('next-question');
    const prevButton = document.getElementById('prev-question');
    const progressFill = document.querySelector('.progress-fill');
    
    let currentQuestion = 1;
    const totalQuestions = document.querySelectorAll('.question-container').length;
    
    // Update progress bar
    function updateProgress() {
        const progressPercentage = (currentQuestion / totalQuestions) * 100;
        progressFill.style.width = `${progressPercentage}%`;
    }
    
    // Show current question
    function showQuestion(questionNumber) {
        document.querySelectorAll('.question-container').forEach((container, index) => {
            if (index + 1 === questionNumber) {
                container.style.display = 'block';
                container.classList.add('active');
            } else {
                container.style.display = 'none';
                container.classList.remove('active');
            }
        });
        
        // Update buttons state
        if (prevButton) {
            prevButton.disabled = questionNumber === 1;
        }
        
        if (nextButton) {
            nextButton.textContent = questionNumber === totalQuestions ? 'Submit' : 'Next';
        }
        
        updateProgress();
    }
    
    // Initialize with first question
    showQuestion(currentQuestion);
    
    // Option selection effect
    optionItems.forEach(item => {
        item.addEventListener('change', function() {
            // Store the answer in browser storage
            const questionId = this.getAttribute('name');
            const answerId = this.value;
            localStorage.setItem(questionId, answerId);
        });
    });
    
    // Navigation buttons
    if (nextButton) {
        nextButton.addEventListener('click', function() {
            if (currentQuestion < totalQuestions) {
                currentQuestion++;
                showQuestion(currentQuestion);
            } else {
                // If last question, submit the form
                quizForm.submit();
            }
        });
    }
    
    if (prevButton) {
        prevButton.addEventListener('click', function() {
            if (currentQuestion > 1) {
                currentQuestion--;
                showQuestion(currentQuestion);
            }
        });
    }
    
    // Load saved answers if any
    document.querySelectorAll('.option-item input[type="radio"]').forEach(radio => {
        const questionId = radio.getAttribute('name');
        const savedAnswer = localStorage.getItem(questionId);
        
        if (savedAnswer && savedAnswer === radio.value) {
            radio.checked = true;
        }
    });
}

function handleRegistrationValidation() {
    const registerForm = document.getElementById('register-form');
    
    registerForm.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Get form fields
        const username = document.getElementById('username');
        const email = document.getElementById('email');
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirm_password');
        
        // Clear previous errors
        document.querySelectorAll('.form-error').forEach(error => error.remove());
        
        // Username validation
        if (!username.value.trim()) {
            showError(username, 'Username is required');
            isValid = false;
        } else if (username.value.length < 3) {
            showError(username, 'Username must be at least 3 characters');
            isValid = false;
        }
        
        // Email validation
        if (!email.value.trim()) {
            showError(email, 'Email is required');
            isValid = false;
        } else if (!isValidEmail(email.value)) {
            showError(email, 'Please enter a valid email address');
            isValid = false;
        }
        
        // Password validation
        if (!password.value) {
            showError(password, 'Password is required');
            isValid = false;
        } else if (password.value.length < 6) {
            showError(password, 'Password must be at least 6 characters');
            isValid = false;
        }
        
        // Confirm password validation
        if (password.value !== confirmPassword.value) {
            showError(confirmPassword, 'Passwords do not match');
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
        }
    });
}

function handleLoginValidation() {
    const loginForm = document.getElementById('login-form');
    
    loginForm.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Get form fields
        const email = document.getElementById('email');
        const password = document.getElementById('password');
        
        // Clear previous errors
        document.querySelectorAll('.form-error').forEach(error => error.remove());
        
        // Email validation
        if (!email.value.trim()) {
            showError(email, 'Email is required');
            isValid = false;
        } else if (!isValidEmail(email.value)) {
            showError(email, 'Please enter a valid email address');
            isValid = false;
        }
        
        // Password validation
        if (!password.value) {
            showError(password, 'Password is required');
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
        }
    });
}

function showError(input, message) {
    const formGroup = input.parentElement;
    const error = document.createElement('div');
    error.className = 'form-error';
    error.innerText = message;
    formGroup.appendChild(error);
    input.classList.add('is-invalid');
}

function isValidEmail(email) {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}