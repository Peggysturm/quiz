<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Квиз</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h1 class="card-title text-center mb-4">Квиз</h1>

                        <div id="name-section" class="mb-4">
                            <p class="fw-semibold">Введите своё имя, чтобы начать:</p>
                            <input type="text" id="username" class="form-control mb-3" placeholder="Ваше имя">
                            <button id="start-btn" class="btn btn-success">Начать тест</button>
                        </div>

                        <div id="quiz-container" class="d-none">
                            <div id="question-section" class="mb-4"></div>
                            <button id="next-btn" class="btn btn-primary d-none">Далее</button>
                            <div id="result-section" class="mt-4"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let questions = <?php echo file_get_contents('questions.json'); ?>;
        questions = questions.sort(() => Math.random() - 0.5); // Перемешивание вопросов

        let currentQuestionIndex = 0;
        let score = 0;
        let username = "";

        function loadQuestion(index) {
            let questionData = questions[index];
            let questionHtml = `<p class="fw-semibold">${index + 1}. ${questionData.question}</p>`;

            questionData.options.forEach((option, idx) => {
                questionHtml += `
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="answer" value="${option}" id="option${idx}">
                        <label class="form-check-label" for="option${idx}">${option}</label>
                    </div>
                `;
            });

            document.getElementById("question-section").innerHTML = questionHtml;
        }

        function showResult() {
            document.getElementById("question-section").style.display = 'none';
            document.getElementById("next-btn").style.display = 'none';

            let resultHtml = `
                <p class="fw-semibold">Спасибо за участие, ${username}!</p>
                <p>Твой результат: ${score} / ${questions.length}</p>
            `;

            document.getElementById("result-section").innerHTML = resultHtml;
        }

        function showNextQuestion() {
            let selectedOption = document.querySelector('input[name="answer"]:checked');

            if (selectedOption) {
                if (selectedOption.value === questions[currentQuestionIndex].correctAnswer) {
                    score++;
                }
                currentQuestionIndex++;

                if (currentQuestionIndex < questions.length) {
                    loadQuestion(currentQuestionIndex);
                } else {
                    showResult();
                }
            }
        }

        document.getElementById("start-btn").addEventListener('click', function () {
            username = document.getElementById("username").value.trim();

            if (username === "") {
                alert("Пожалуйста, введите своё имя!");
                return;
            }

            document.getElementById("name-section").style.display = 'none';
            document.getElementById("quiz-container").classList.remove("d-none");

            loadQuestion(currentQuestionIndex);
        });

        document.getElementById("next-btn").addEventListener('click', function () {
            showNextQuestion();
        });

        document.addEventListener('change', function () {
            document.getElementById("next-btn").classList.remove("d-none");
        });
    </script>
</body>
</html>
