<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--favicon-->
    <link rel="icon" href="{{ url('back/assets/images/logo.png') }}" type="image/png" />

    <title>تنزيل {{ $homework->name }}</title>

    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            direction: rtl; /* RTL direction */
            text-align: right; /* Align text to the right */
        }

        .container {
            width: 90%;
            margin: 0 auto;
        }

        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            margin-bottom: 20px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .question-block {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #f9f9f9;
        }

        .question-text {
            margin-bottom: 15px;
        }

        .question-text h3 {
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }

        .question-image {
            margin-top: 10px;
        }

        .question-image img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            border: 1px solid #ddd;
        }

        .answer-item {
            margin-bottom: 20px;
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 10px;
            background-color: #fff;
        }

        .answer-text {
            font-size: 18px;
            line-height: 1.6;
            color: #333;
            display: flex;
            align-items: flex-start;
            gap: 20px;
        }

        .answer-image {
            margin-top: 10px;
        }

        .answer-image img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            border: 1px solid #ddd;
        }

        .badge {
            padding: 6px 12px;
            color: #fff;
            border-radius: 4px;
            font-size: 0.9em;
        }

        .bg-success {
            background-color: #28a745;
        }
    </style>
</head>

<body>

    <div class="container">
        <!-- PDF Export Button -->
        <button id="exportPDF" style="text-decoration: none; background-color: transparent;">
            <img src="{{ url('back/assets/images/pdf.png') }}" width="70" height="70" alt="Download PDF">
        </button>

        <!-- Homework Content -->
        <div id="contentToExport" class="questions-container" style="margin-top: 20px;">
            @foreach ($questions as $key => $question)
                <div class="question-block">
                    <!-- Question -->
                    <div class="question-text">
                        <h3>{{ $key + 1 }}. {{ $question->question }}</h3>
                        @if ($question->getFirstMedia('questions'))
                            <div class="question-image">
                                <img src="{{ asset('storage/' . $question->getFirstMedia('questions')->id . '/' . $question->getFirstMedia('questions')->file_name) }}"
                                     alt="Question Image">
                            </div>
                        @endif
                    </div>

                    <!-- Answers -->
                    <div class="answers-list">
                        @php
                            $rank = 1;
                        @endphp
                        @foreach ($question->answers as $answer)
                            <div class="answer-item">
                                <div class="answer-text">
                                    <span style="font-weight: bold; font-size: 20px;">{{ $rank }}.</span>
                                    <span>{{ $answer->answer }}</span>
                                    @if ($answer->is_correct)
                                        <span class="badge bg-success">صحيحة</span>
                                    @endif
                                </div>

                                @if ($answer->getFirstMedia('answers'))
                                    <div class="answer-image">
                                        <img src="{{ asset('storage/' . $answer->getFirstMedia('answers')->id . '/' . $answer->getFirstMedia('answers')->file_name) }}"
                                             alt="Answer Image">
                                    </div>
                                @endif
                            </div>
                            @php
                                $rank++;
                            @endphp
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

    </div>

    <!-- Include html2canvas and jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script>
        document.getElementById('exportPDF').addEventListener('click', function() {
            const element = document.getElementById('contentToExport');

            html2canvas(element, {
                useCORS: true
            }).then(canvas => {
                const { jsPDF } = window.jspdf;
                const pdf = new jsPDF('p', 'mm', 'a4');
                const imgData = canvas.toDataURL('image/png');
                const imgWidth = 210;
                const pageHeight = 295;
                const imgHeight = (canvas.height * imgWidth) / canvas.width;
                let heightLeft = imgHeight;
                let position = 0;

                pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                heightLeft -= pageHeight;

                while (heightLeft >= 0) {
                    position = heightLeft - imgHeight;
                    pdf.addPage();
                    pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                    heightLeft -= pageHeight;
                }

                pdf.save('homework-questions.pdf');
            });
        });
    </script>

</body>

</html>
